<?php

namespace App\Console\Commands;

use App\Models\SatkerPemberkasan;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AutoRekapCKH extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ckh:auto-rekap
                            {--bulan= : Format YYYY-MM, contoh: 2026-07 (default: bulan lalu)}
                            {--dry-run : Preview tanpa generate PDF}
                            {--user= : User ID tertentu saja}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto rekap laporan kinerja CKH untuk seluruh pegawai aktif (status=1)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $targetMonth = $this->option('bulan');

        // Default ke bulan lalu jika tidak指定
        if (!$targetMonth) {
            $targetMonth = Carbon::now()->subMonth()->format('Y-m');
        }

        // Validate format YYYY-MM
        if (!preg_match('/^\d{4}-\d{2}$/', $targetMonth)) {
            $this->error('Format bulan tidak valid. Gunakan format YYYY-MM, contoh: 2026-07');
            return Command::FAILURE;
        }

        $this->info('===========================================');
        $this->info(' Auto Rekap CKH - Laporan Kinerja Bulanan');
        $this->info('===========================================');
        $this->newLine();

        $selectedMonthStart = Carbon::createFromFormat('Y-m', $targetMonth)->startOfMonth();
        $selectedMonthEnd = $selectedMonthStart->copy()->endOfMonth();
        $periodLabel = $this->indonesianMonthLabel($selectedMonthStart);

        $this->info("Bulan: {$periodLabel}");
        $this->newLine();

        // Query user
        $userQuery = DB::table('users')
            ->where('status', 1)
            ->whereNotNull('dept_id');

        // Filter user tertentu jika ada
        $targetUserId = $this->option('user');
        if ($targetUserId) {
            $userQuery->where('id', $targetUserId);
        }

        $users = $userQuery->orderBy('dept_id')->orderBy('name')->get();
        $totalUsers = $users->count();

        $this->info("Ditemukan {$totalUsers} pegawai aktif");

        if ($totalUsers === 0) {
            $this->warn('Tidak ada pegawai yang diproses.');
            return Command::SUCCESS;
        }

        // Dry run mode
        if ($this->option('dry-run')) {
            $this->warn('DRY RUN MODE - PDF tidak akan di-generate.');
            $this->newLine();

            $headers = ['ID', 'Nama', 'Dept', 'Jabatan'];
            $rows = $users->take(20)->map(function ($user) {
                $deptName = DB::table('ktd_department')
                    ->where('id', $user->dept_id)
                    ->value('nama') ?? '-';

                return [
                    $user->id,
                    $user->name,
                    $deptName,
                    $user->kat_jabatan ?? '-',
                ];
            })->toArray();

            $this->table($headers, $rows);

            if ($totalUsers > 20) {
                $this->newLine();
                $this->info("... dan " . ($totalUsers - 20) . " pegawai lainnya.");
            }

            return Command::SUCCESS;
        }

        // Konfirmasi
        if (!$this->confirm("Generate PDF untuk {$totalUsers} pegawai?")) {
            $this->info('Dibatalkan.');
            return Command::FAILURE;
        }

        $this->newLine();
        $bar = $this->output->createProgressBar($totalUsers);
        $bar->setFormat(' %current%/%max% [%bar%] %percent:3s%% %message%');
        $bar->start();

        $successCount = 0;
        $skippedCount = 0;
        $failedCount = 0;
        $errors = [];

        foreach ($users as $user) {
            $result = $this->processUser($user, $selectedMonthStart, $selectedMonthEnd, $periodLabel);

            switch ($result['status']) {
                case 'success':
                    $successCount++;
                    $bar->setMessage("<fg=green>OK</>");
                    break;
                case 'skipped':
                    $skippedCount++;
                    $bar->setMessage("<fg=yellow>SKIP</>");
                    break;
                case 'failed':
                    $failedCount++;
                    $bar->setMessage("<fg=red>FAIL</>");
                    $errors[] = "User #{$user->id} ({$user->name}): {$result['message']}";
                    break;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        // Summary
        $this->info('Selesai!');
        $this->newLine();

        $this->table(
            ['Status', 'Jumlah'],
            [
                ['<fg=green>Berhasil</>', $successCount],
                ['<fg=yellow>Dilewati (tidak ada kegiatan)</>', $skippedCount],
                ['<fg=red>Gagal</>', $failedCount],
                ['Total Diproses', $totalUsers],
            ]
        );

        // Show errors
        if (!empty($errors)) {
            $this->newLine();
            $this->error('Error Details:');
            foreach (array_slice($errors, 0, 10) as $error) {
                $this->line("  - {$error}");
            }
            if (count($errors) > 10) {
                $this->line("  ... dan " . (count($errors) - 10) . " error lainnya");
            }
        }

        $this->newLine();
        $this->info('PDF disimpan ke: storage/app/public/satker_ckh/{user_id}/');

        return $failedCount > 0 ? Command::FAILURE : Command::SUCCESS;
    }

    /**
     * Process single user to generate CKH PDF
     */
    protected function processUser(object $user, Carbon $monthStart, Carbon $monthEnd, string $periodLabel): array
    {
        try {
            // Get unit name
            $unitName = DB::table('ktd_department')
                ->where('id', $user->dept_id)
                ->value('nama');

            // Get daily activities
            $dailyEntries = DB::table('satker_kegiatan')
                ->where('user_id', $user->id)
                ->whereBetween('tanggal', [$monthStart->toDateString(), $monthEnd->toDateString()])
                ->orderBy('tanggal')
                ->orderBy('created_at')
                ->get();

            // Skip if no activities
            if ($dailyEntries->isEmpty()) {
                return ['status' => 'skipped', 'message' => 'Tidak ada kegiatan'];
            }

            // Group by date
            $dailyGroups = $dailyEntries
                ->groupBy(fn ($row) => Carbon::parse($row->tanggal)->toDateString())
                ->map(function ($items, $date) {
                    $dateCarbon = Carbon::parse($date);
                    $allItems = [];

                    foreach ($items as $item) {
                        $jsonData = json_decode((string) ($item->data_json ?? '{"items":[]}'), true) ?: ['items' => []];
                        $itemsArr = $jsonData['items'] ?? [];

                        // Handle legacy format
                        if (empty($itemsArr) && !empty($item->kegiatan)) {
                            $itemsArr = [[
                                'k' => $item->kegiatan,
                                'v' => $item->volume ?? 0,
                                's' => $item->satuan ?? 'Kegiatan'
                            ]];
                        }

                        foreach ($itemsArr as $it) {
                            $volume = (int) ($it['v'] ?? ($it['volume'] ?? 0));
                            $unit = trim((string) ($it['s'] ?? ($it['satuan'] ?? 'Kegiatan')));

                            $allItems[] = [
                                'kegiatan' => trim((string) ($it['k'] ?? ($it['kegiatan'] ?? ''))),
                                'volume' => $volume,
                                'satuan' => $unit,
                                'meta' => $volume > 0 ? trim($volume . ' ' . $unit) : $unit,
                            ];
                        }
                    }

                    return [
                        'date' => $dateCarbon->toDateString(),
                        'label' => $this->indonesianDateLabel($dateCarbon),
                        'items' => $allItems,
                    ];
                })
                ->values()
                ->all();

            // Determine signature
            $signatureName = '..................................';
            $signatureNip = '';
            $signatureLabel = 'Mengetahui';

            // Cek PLT/PJH
            $pltPlh = DB::table('plt_plh')
                ->where('dept_id_plh', $user->dept_id)
                ->first();

            $isPlh = false;
            $atasanJabatan = ['kepala', 'kasi', 'kasubbag'];
            $isUserAtasan = in_array($user->kat_jabatan, $atasanJabatan);

            if ($isUserAtasan) {
                $kepalaKankemenag = DB::table('users')
                    ->where('role', 'kepala')
                    ->first();

                if ($kepalaKankemenag) {
                    $signatureName = $kepalaKankemenag->name;
                    $signatureNip = $kepalaKankemenag->nomor_induk ? 'NIP. ' . $kepalaKankemenag->nomor_induk : '';
                    $signatureLabel = 'Mengetahui<br>Kepala Kankemenag Kab. Tanah Datar,';
                }
            } elseif ($pltPlh) {
                $pltUser = DB::table('users')->where('id', $pltPlh->user_id)->first();
                if ($pltUser) {
                    $isPlh = true;
                    $signatureName = $pltUser->name;
                    $signatureNip = $pltUser->nomor_induk ? 'NIP. ' . $pltUser->nomor_induk : '';
                    $signatureLabel = 'Mengetahui<br>PLT Kepala,';
                }
            } else {
                $kepala = DB::table('users')
                    ->where('dept_id', $user->dept_id)
                    ->whereIn('kat_jabatan', $atasanJabatan)
                    ->first();

                if ($kepala) {
                    $signatureName = $kepala->name;
                    $signatureNip = $kepala->nomor_induk ? 'NIP. ' . $kepala->nomor_induk : '';
                }

                $specialDeptIds = [998, 999];
                $kepalaLabel = in_array((int) $user->dept_id, $specialDeptIds)
                    ? ($user->satker ?? $unitName)
                    : ($unitName ?: '-');

                $signatureLabel = "Mengetahui<br>Kepala {$kepalaLabel},";
            }

            // Get header image
            $headerImage = null;
            $headerPath = public_path('assets/img/template/header.png');
            if (file_exists($headerPath)) {
                $headerImage = $this->assetToDataUri($headerPath);
            }

            // Build PDF data
            $pdfData = [
                'userName' => $user->name,
                'userNip' => $user->nomor_induk ?: '-',
                'unitName' => $unitName ?: '-',
                'positionName' => trim((string) ($user->pekerjaan ?: '-')) ?: '-',
                'periodLabel' => $periodLabel,
                'dailyGroups' => $dailyGroups,
                'headerImage' => $headerImage,
                'generatedAt' => now()->translatedFormat('d F Y H:i'),
                'signatureName' => $signatureName,
                'signatureNip' => $signatureNip,
                'signatureImage' => null,
                'signatureLabel' => $signatureLabel,
                'watermarkText' => 'Kankemenag Kab.Tanah Datar',
            ];

            // Generate PDF
            $pdf = Pdf::loadView('pdf.laporan-kinerja-harian', $pdfData)
                ->setPaper('a4', 'portrait')
                ->setOption('isRemoteEnabled', true)
                ->setOption('isHtml5ParserEnabled', true);

            $filename = sprintf('%s.kinerja-%s.pdf', $user->id, $monthStart->format('m-Y'));
            $storagePath = "satker_ckh/{$user->id}/{$filename}";
            $pdfBinary = $pdf->output();

            // Ensure directory exists
            $fullDirPath = storage_path('app/public/satker_ckh/' . $user->id);
            if (!is_dir($fullDirPath)) {
                if (!mkdir($fullDirPath, 0755, true) && !is_dir($fullDirPath)) {
                    return ['status' => 'failed', 'message' => 'Gagal membuat direktori'];
                }
            }

            // Save PDF
            $saved = Storage::disk('public')->put($storagePath, $pdfBinary);
            if (!$saved) {
                return ['status' => 'failed', 'message' => 'Storage::put gagal'];
            }

            // Update/Insert satker_ckh record
            DB::table('satker_ckh')->updateOrInsert(
                [
                    'item_id' => 1,
                    'dept_id' => $user->dept_id,
                    'user_id' => $user->id,
                    'bulan' => $monthStart->toDateString(),
                ],
                [
                    'item_id' => 1,
                    'dept_id' => $user->dept_id,
                    'user_id' => $user->id,
                    'bulan' => $monthStart->toDateString(),
                    'filename' => $filename,
                    'status' => 'DIKIRIM',
                    'alasan' => null,
                    'petugas' => 777,
                    'sending' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

            return ['status' => 'success', 'message' => 'OK'];

        } catch (\Exception $e) {
            Log::error("Auto Rekap CKH Error - User #{$user->id}: {$e->getMessage()}", [
                'user_id' => $user->id,
                'trace' => $e->getTraceAsString(),
            ]);
            return ['status' => 'failed', 'message' => $e->getMessage()];
        }
    }

    /**
     * Convert image to data URI
     */
    protected function assetToDataUri(string $path): ?string
    {
        if (!file_exists($path)) {
            return null;
        }

        $mime = mime_content_type($path);
        $data = file_get_contents($path);

        if ($data === false) {
            return null;
        }

        return 'data:' . $mime . ';base64,' . base64_encode($data);
    }

    /**
     * Format Indonesian month label
     */
    protected function indonesianMonthLabel(Carbon $date): string
    {
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
            4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September',
            10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        return $months[(int) $date->format('n')] . ' ' . $date->format('Y');
    }

    /**
     * Format Indonesian date label
     */
    protected function indonesianDateLabel(Carbon $date): string
    {
        $days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
            4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September',
            10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        return $days[(int) $date->format('w')] . ', ' . $date->format('j') . ' ' . $months[(int) $date->format('n')] . ' ' . $date->format('Y');
    }
}
