<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class KegiatanController extends BaseApiController
{
    public function __construct()
    {
        date_default_timezone_set('Asia/Jakarta');
        Carbon::setLocale('id_ID');
    }

    /**
     * Get kegiatan bulanan
     * GET /api/laporan-kinerja?month=YYYY-MM
     */
    public function index(Request $request)
    {
        try {
            $user = $request->user();
            $month = $request->input('month', Carbon::now()->format('Y-m'));

            // Validate month format
            if (!preg_match('/^\d{4}-\d{2}$/', $month)) {
                return $this->error('Format bulan tidak valid', 400);
            }

            $selectedMonthStart = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
            $selectedMonthEnd = $selectedMonthStart->copy()->endOfMonth();

            // Get kegiatan from database
            $dailyEntries = DB::table('satker_kegiatan')
                ->where('user_id', $user->id)
                ->whereBetween('tanggal', [
                    $selectedMonthStart->toDateString(),
                    $selectedMonthEnd->toDateString()
                ])
                ->orderBy('tanggal')
                ->get();

            // Process JSON data
            $dailyGroups = [];
            $totalEntries = 0;
            $totalVolume = 0;
            $latestUpdate = null;

            Log::info('Found ' . $dailyEntries->count() . ' kegiatan entries');

            foreach ($dailyEntries as $row) {
                $date = Carbon::parse($row->tanggal)->toDateString();
                $jsonData = json_decode((string) ($row->data_json ?? '{"items":[]}'), true) ?: ['items' => []];
                $items = $jsonData['items'] ?? [];

                // Handle legacy format
                if (empty($items) && !empty($row->kegiatan)) {
                    $items = [[
                        'id' => $row->id,
                        'k' => $row->kegiatan,
                        'v' => $row->volume ?? 0,
                        's' => $row->satuan ?? 'Kegiatan'
                    ]];
                }

                if (empty($items)) {
                    continue;
                }

                $mappedItems = array_map(function ($item) use ($row) {
                    $volume = (int) ($item['v'] ?? 0);
                    $satuan = $item['s'] ?? 'Kegiatan';

                    return [
                        'id' => $item['id'] ?? null,
                        'kegiatan' => trim((string) ($item['k'] ?? '')),
                        'volume' => $volume,
                        'satuan' => $satuan,
                        'meta' => $volume > 0 ? trim($volume . ' ' . $satuan) : $satuan,
                        'tanggal' => $row->tanggal,
                    ];
                }, array_values($items));

                $dayVolume = array_sum(array_column($mappedItems, 'volume'));
                $dayEntries = count($mappedItems);
                $totalEntries += $dayEntries;
                $totalVolume += $dayVolume;

                if ($row->updated_at && (!$latestUpdate || $row->updated_at > $latestUpdate)) {
                    $latestUpdate = $row->updated_at;
                }

                $dateCarbon = Carbon::parse($date);

                if (!isset($dailyGroups[$date])) {
                    $dailyGroups[$date] = [
                        'date' => $dateCarbon->toDateString(),
                        'label' => $this->indonesianDateLabel($dateCarbon),
                        'items' => [],
                        'entries' => 0,
                        'volume' => 0,
                        'row_id' => $row->id,
                    ];
                }

                $dailyGroups[$date]['items'] = array_merge($dailyGroups[$date]['items'], $mappedItems);
                $dailyGroups[$date]['entries'] += $dayEntries;
                $dailyGroups[$date]['volume'] += $dayVolume;
            }

            // Sort by date
            ksort($dailyGroups);

            Log::info('Processed kegiatan', [
                'total_groups' => count($dailyGroups),
                'total_entries' => $totalEntries,
                'total_volume' => $totalVolume,
                'sample_data' => array_slice(array_values($dailyGroups), 0, 1),
            ]);

            return $this->success([
                'dailyGroups' => array_values($dailyGroups),
                'summary' => [
                    'entries' => $totalEntries,
                    'days' => count($dailyGroups),
                    'volume' => $totalVolume,
                    'latest_update' => $latestUpdate,
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting kegiatan: ' . $e->getMessage());
            return $this->error('Gagal memuat kegiatan', 500);
        }
    }

    /**
     * Store kegiatan harian
     * POST /api/laporan-kinerja/harian
     */
    public function store(Request $request)
    {
        try {
            $user = $request->user();

            $request->validate([
                'tanggal' => 'required|date',
                'items' => 'required|array|min:1',
                'items.*.k' => 'required|string|max:1000',
                'items.*.v' => 'nullable|integer|min:0',
                'items.*.s' => 'nullable|string|max:50',
            ]);

            $tanggal = $request->input('tanggal');
            $items = $request->input('items');

            // Filter empty items
            $items = array_filter($items, function ($item) {
                return !empty(trim($item['k'] ?? ''));
            });

            if (empty($items)) {
                return $this->error('Tambahkan minimal satu kegiatan', 400);
            }

            $jsonData = json_encode(['items' => array_values($items)], JSON_UNESCAPED_UNICODE);

            // Check if record exists for this user + date
            $existing = DB::table('satker_kegiatan')
                ->where('user_id', $user->id)
                ->whereDate('tanggal', $tanggal)
                ->first();

            if ($existing) {
                // Merge new items with existing data
                $existingData = json_decode((string) ($existing->data_json ?? '{"items":[]}'), true) ?: ['items' => []];
                $existingItems = $existingData['items'] ?? [];

                // Append new items (those with null id)
                $maxId = 0;
                foreach ($existingItems as $item) {
                    if (isset($item['id']) && $item['id'] > $maxId) {
                        $maxId = $item['id'];
                    }
                }

                foreach ($items as &$item) {
                    $maxId++;
                    $item['id'] = $maxId;
                }
                unset($item);

                $allItems = array_merge($existingItems, $items);
                $jsonData = json_encode(['items' => $allItems], JSON_UNESCAPED_UNICODE);

                DB::table('satker_kegiatan')
                    ->where('id', $existing->id)
                    ->update([
                        'data_json' => $jsonData,
                        'updated_at' => now(),
                    ]);
            } else {
                // Create new record
                foreach ($items as &$item) {
                    $item['id'] = 1;
                }
                unset($item);

                $jsonData = json_encode(['items' => $items], JSON_UNESCAPED_UNICODE);

                // Get first item for legacy columns
                $firstKegiatan = $items[0]['k'] ?? '';
                $firstVolume = $items[0]['v'] ?? 0;
                $firstSatuan = $items[0]['s'] ?? 'Kegiatan';

                DB::table('satker_kegiatan')->insert([
                    'user_id' => $user->id,
                    'tanggal' => $tanggal,
                    'kegiatan' => $firstKegiatan,
                    'volume' => $firstVolume,
                    'satuan' => $firstSatuan,
                    'data_json' => $jsonData,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            return $this->success([
                'message' => 'Kegiatan berhasil disimpan',
                'tanggal' => $tanggal,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->error($e->getMessage(), 422, $e->errors());
        } catch (\Exception $e) {
            Log::error('Error storing kegiatan: ' . $e->getMessage());
            return $this->error('Gagal menyimpan kegiatan', 500);
        }
    }

    /**
     * Update kegiatan by date
     * PUT /api/laporan-kinerja/day
     */
    public function updateByDate(Request $request)
    {
        try {
            $user = $request->user();

            $request->validate([
                'tanggal' => 'required|date',
                'items' => 'required|array|min:1',
                'items.*.k' => 'required|string|max:1000',
                'items.*.v' => 'nullable|integer|min:0',
                'items.*.s' => 'nullable|string|max:50',
            ]);

            $tanggal = $request->input('tanggal');
            $items = $request->input('items');

            // Filter empty items
            $items = array_filter($items, function ($item) {
                return !empty(trim($item['k'] ?? ''));
            });

            if (empty($items)) {
                return $this->error('Tambahkan minimal satu kegiatan', 400);
            }

            // Check if record exists
            $existing = DB::table('satker_kegiatan')
                ->where('user_id', $user->id)
                ->whereDate('tanggal', $tanggal)
                ->first();

            if (!$existing) {
                return $this->error('Data kegiatan tidak ditemukan', 404);
            }

            // Update items with IDs
            foreach ($items as &$item) {
                if (!isset($item['id'])) {
                    $item['id'] = uniqid();
                }
            }
            unset($item);

            $jsonData = json_encode(['items' => array_values($items)], JSON_UNESCAPED_UNICODE);

            DB::table('satker_kegiatan')
                ->where('id', $existing->id)
                ->update([
                    'data_json' => $jsonData,
                    'updated_at' => now(),
                ]);

            return $this->success([
                'message' => 'Kegiatan berhasil diupdate',
                'tanggal' => $tanggal,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->error($e->getMessage(), 422, $e->errors());
        } catch (\Exception $e) {
            Log::error('Error updating kegiatan: ' . $e->getMessage());
            return $this->error('Gagal update kegiatan', 500);
        }
    }

    /**
     * Delete kegiatan by date
     * DELETE /api/laporan-kinerja/day
     */
    public function deleteByDate(Request $request)
    {
        try {
            $user = $request->user();

            $request->validate([
                'tanggal' => 'required|date',
            ]);

            $tanggal = $request->input('tanggal');

            $deleted = DB::table('satker_kegiatan')
                ->where('user_id', $user->id)
                ->whereDate('tanggal', $tanggal)
                ->delete();

            if (!$deleted) {
                return $this->error('Data kegiatan tidak ditemukan', 404);
            }

            return $this->success([
                'message' => 'Kegiatan berhasil dihapus',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->error($e->getMessage(), 422, $e->errors());
        } catch (\Exception $e) {
            Log::error('Error deleting kegiatan: ' . $e->getMessage());
            return $this->error('Gagal hapus kegiatan', 500);
        }
    }

    /**
     * Get laporan CKH bulanan (satker_ckh)
     * GET /api/laporan-kinerja/bulanan?year=YYYY
     */
    public function bulanan(Request $request)
    {
        try {
            $user = $request->user();
            $year = $request->input('year', Carbon::now()->format('Y'));

            // Validate year format
            if (!preg_match('/^\d{4}$/', $year)) {
                return $this->error('Format tahun tidak valid', 400);
            }

            $yearStart = Carbon::createFromFormat('Y-m-d', $year . '-01-01')->startOfYear();
            $yearEnd = Carbon::createFromFormat('Y-m-d', $year . '-12-31')->endOfYear();

            // Get bulanan reports from satker_ckh
            $reports = DB::table('satker_ckh as ck')
                ->leftJoin('users as u', 'u.id', '=', 'ck.user_id')
                ->leftJoin('ktd_department as dept', 'dept.id', '=', 'ck.dept_id')
                ->whereBetween('ck.bulan', [$yearStart->toDateString(), $yearEnd->toDateString()])
                ->where('ck.user_id', $user->id)
                ->select([
                    'ck.id',
                    'ck.user_id',
                    'ck.dept_id',
                    'ck.bulan',
                    'ck.filename',
                    'ck.status',
                    'ck.alasan',
                    'ck.petugas',
                    'ck.sending',
                    'ck.created_at',
                    'ck.updated_at',
                    'u.name as user_name',
                    'u.nomor_induk',
                    'dept.nama as dept_name',
                ])
                ->orderBy('ck.bulan', 'desc')
                ->get()
                ->map(function ($item) {
                    $statusConfig = [
                        'KOSONG' => ['label' => 'Belum Kirim', 'color' => 'slate'],
                        'DIKIRIM' => ['label' => 'Dikirim', 'color' => 'amber'],
                        'DISETUJUI' => ['label' => 'Disetujui', 'color' => 'emerald'],
                        'DITOLAK' => ['label' => 'Ditolak', 'color' => 'rose'],
                    ];
                    $status = $statusConfig[$item->status] ?? ['label' => $item->status, 'color' => 'slate'];

                    $bulanRaw = is_string($item->bulan) ? substr($item->bulan, 0, 10) : $item->bulan;
                    $bulanDate = Carbon::createFromFormat('Y-m-d', $bulanRaw);

                    return [
                        'id' => $item->id,
                        'user_id' => $item->user_id,
                        'user_name' => $item->user_name ?? 'Unknown',
                        'nomor_induk' => $item->nomor_induk ?? '-',
                        'dept_name' => $item->dept_name ?? '-',
                        'bulan' => $bulanDate->format('F Y'),
                        'bulan_raw' => $bulanRaw,
                        'filename' => $item->filename,
                        'status' => $item->status,
                        'status_label' => $status['label'],
                        'status_color' => $status['color'],
                        'alasan' => $item->alasan,
                        'sending' => $item->sending ? Carbon::parse($item->sending)->format('d/m/Y H:i') : null,
                        'pdf_url' => $item->filename ? url('storage/satker_ckh/' . $item->user_id . '/' . $item->filename) : null,
                    ];
                });

            // Calculate statistics
            $stats = [
                'total' => $reports->count(),
                'disetujui' => $reports->where('status', 'DISETUJUI')->count(),
                'dikirim' => $reports->where('status', 'DIKIRIM')->count(),
                'ditolak' => $reports->where('status', 'DITOLAK')->count(),
                'belum_kirim' => $reports->where('status', 'KOSONG')->count(),
            ];

            return $this->success([
                'reports' => $reports->values(),
                'stats' => $stats,
                'year' => $year,
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting bulanan reports: ' . $e->getMessage());
            return $this->error('Gagal memuat laporan bulanan', 500);
        }
    }

    /**
     * Get rekap bulanan
     * GET /api/laporan-kinerja/rekap?month=YYYY-MM
     */
    public function rekap(Request $request)
    {
        try {
            $user = $request->user();
            $month = $request->input('month', Carbon::now()->format('Y-m'));

            // Validate month format
            if (!preg_match('/^\d{4}-\d{2}$/', $month)) {
                return $this->error('Format bulan tidak valid', 400);
            }

            $selectedMonthStart = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
            $selectedMonthEnd = $selectedMonthStart->copy()->endOfMonth();

            $dailyEntries = DB::table('satker_kegiatan')
                ->where('user_id', $user->id)
                ->whereBetween('tanggal', [
                    $selectedMonthStart->toDateString(),
                    $selectedMonthEnd->toDateString()
                ])
                ->get();

            $totalEntries = 0;
            $totalVolume = 0;
            $latestUpdate = null;

            foreach ($dailyEntries as $row) {
                $jsonData = json_decode((string) ($row->data_json ?? '{"items":[]}'), true) ?: ['items' => []];
                $items = $jsonData['items'] ?? [];

                if (empty($items) && !empty($row->kegiatan)) {
                    $items = [['k' => $row->kegiatan, 'v' => $row->volume ?? 0, 's' => $row->satuan ?? 'Kegiatan']];
                }

                foreach ($items as $item) {
                    $totalEntries++;
                    $totalVolume += (int) ($item['v'] ?? 0);
                }

                if ($row->updated_at && (!$latestUpdate || $row->updated_at > $latestUpdate)) {
                    $latestUpdate = $row->updated_at;
                }
            }

            return $this->success([
                'entries' => $totalEntries,
                'days' => $dailyEntries->count(),
                'volume' => $totalVolume,
                'latest_update' => $latestUpdate,
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting rekap: ' . $e->getMessage());
            return $this->error('Gagal memuat rekap', 500);
        }
    }

    /**
     * Helper: Format tanggal dalam bahasa Indonesia
     */
    private function indonesianDateLabel(Carbon $date)
    {
        $dayName = match ($date->dayOfWeek) {
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
            7 => 'Minggu',
        };

        $monthName = match ($date->month) {
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        };

        return "{$dayName}, {$date->day} {$monthName} {$date->year}";
    }

    /**
     * Download PDF Laporan Kegiatan
     * GET /api/laporan-kinerja/pdf?month=YYYY-MM&signature_name=...&signature_nip=...
     */
    public function downloadPdf(Request $request)
    {
        try {
            $user = $request->user();
            $month = $request->input('month', Carbon::now()->format('Y-m'));

            // For dept_id 998/999, get manual signature input
            $manualSignatureName = $request->input('signature_name');
            $manualSignatureNip = $request->input('signature_nip');

            // Validate month format
            if (!preg_match('/^\d{4}-\d{2}$/', $month)) {
                return $this->error('Format bulan tidak valid', 400);
            }

            $selectedMonthStart = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
            $selectedMonthEnd = $selectedMonthStart->copy()->endOfMonth();

            // Get kegiatan data
            $dailyEntries = DB::table('satker_kegiatan')
                ->where('user_id', $user->id)
                ->whereBetween('tanggal', [
                    $selectedMonthStart->toDateString(),
                    $selectedMonthEnd->toDateString()
                ])
                ->orderBy('tanggal')
                ->get();

            // Process data
            $dailyGroups = [];
            $totalEntries = 0;
            $totalVolume = 0;

            foreach ($dailyEntries as $row) {
                $date = Carbon::parse($row->tanggal)->toDateString();
                $jsonData = json_decode((string) ($row->data_json ?? '{"items":[]}'), true) ?: ['items' => []];
                $items = $jsonData['items'] ?? [];

                if (empty($items) && !empty($row->kegiatan)) {
                    $items = [[
                        'id' => $row->id,
                        'k' => $row->kegiatan,
                        'v' => $row->volume ?? 0,
                        's' => $row->satuan ?? 'Kegiatan'
                    ]];
                }

                if (empty($items)) {
                    continue;
                }

                $mappedItems = array_map(function ($item) {
                    return [
                        'kegiatan' => trim((string) ($item['k'] ?? '')),
                        'volume' => (int) ($item['v'] ?? 0),
                        'satuan' => $item['s'] ?? 'Kegiatan',
                    ];
                }, array_values($items));

                $dayVolume = array_sum(array_column($mappedItems, 'volume'));

                if (!isset($dailyGroups[$date])) {
                    $dailyGroups[$date] = [
                        'date' => $date,
                        'label' => $this->indonesianDateLabel(Carbon::parse($date)),
                        'items' => [],
                    ];
                }

                $dailyGroups[$date]['items'] = array_merge($dailyGroups[$date]['items'], $mappedItems);
                $totalEntries += count($mappedItems);
                $totalVolume += $dayVolume;
            }

            ksort($dailyGroups);

            // Get user info from database
            $unitName = DB::table('ktd_department')
                ->where('id', $user->dept_id)
                ->value('nama') ?? '-';
            $positionName = $user->pekerjaan ?? '-';

            // Get signature info (kepala/pimpinan)
            $signatureName = '..................................';
            $signatureNip = '';
            $signatureLabel = 'Mengetahui<br>Kepala,';

            // Cek PLT/PJH di tabel plt_plh
            $pltPlh = DB::table('plt_plh')
                ->where('dept_id_plh', $user->dept_id)
                ->first();

            $isPlh = false;
            $atasanJabatan = ['kepala', 'kasi', 'kasubbag'];
            $isUserAtasan = in_array($user->kat_jabatan, $atasanJabatan);

            if ($isUserAtasan) {
                // Jika user adalah atasan, penandatangan adalah Kepala Kankemenag
                $kepalaKankemenag = DB::table('users')
                    ->where('role', 'kepala')
                    ->first();

                if ($kepalaKankemenag) {
                    $signatureName = $kepalaKankemenag->name;
                    $signatureNip = $kepalaKankemenag->nomor_induk ? 'NIP. ' . $kepalaKankemenag->nomor_induk : '';
                }
            } elseif ($pltPlh) {
                // PLT exist - gunakan user PLT
                $pltUser = DB::table('users')->where('id', $pltPlh->user_id)->first();
                if ($pltUser) {
                    $isPlh = true;
                    $signatureName = $pltUser->name;
                    $signatureNip = $pltUser->nomor_induk ? 'NIP. ' . $pltUser->nomor_induk : '';
                }
            } else {
                // Cari kepala/kasi/kasubbag berdasarkan dept_id
                $kepala = DB::table('users')
                    ->where('dept_id', $user->dept_id)
                    ->whereIn('kat_jabatan', $atasanJabatan)
                    ->first();

                if ($kepala) {
                    $signatureName = $kepala->name;
                    $signatureNip = $kepala->nomor_induk ? 'NIP. ' . $kepala->nomor_induk : '';
                }
            }

            // For dept_id 998/999, use manual signature input if provided
            $specialDeptIds = [998, 999];
            if (in_array((int) $user->dept_id, $specialDeptIds)) {
                if (!empty($manualSignatureName)) {
                    $signatureName = $manualSignatureName;
                }
                if (!empty($manualSignatureNip)) {
                    $signatureNip = 'NIP. ' . $manualSignatureNip;
                }
            }

            // Determine signature label based on user role and dept_id (sama seperti PageController)
            $specialDeptIds = [998, 999];
            $kepalaLabel = in_array((int) $user->dept_id, $specialDeptIds)
                ? ($user->satker ?? $unitName)
                : ($unitName ?: '-');

            if ($isUserAtasan) {
                $signatureLabel = 'Mengetahui<br>Kepala Kankemenag Kab. Tanah Datar,';
            } elseif ($isPlh) {
                $signatureLabel = 'Mengetahui<br>PLT Kepala,';
            } else {
                $signatureLabel = "Mengetahui<br>Kepala {$kepalaLabel},";
            }

            // Generate PDF using existing blade view
            $pdfData = [
                'userName' => $user->name,
                'userNip' => $user->nomor_induk ?: '-',
                'unitName' => $unitName,
                'positionName' => $positionName,
                'periodLabel' => $selectedMonthStart->translatedFormat('F Y'),
                'dailyGroups' => array_values($dailyGroups),
                'headerImage' => null,
                'generatedAt' => now()->translatedFormat('d F Y H:i'),
                'signatureName' => $signatureName,
                'signatureNip' => $signatureNip,
                'signatureImage' => null,
                'signatureLabel' => $signatureLabel,
                'watermarkText' => 'Kankemenag Kab.Tanah Datar',
            ];

            $pdf = Pdf::loadView('pdf.laporan-kinerja-harian', $pdfData)
                ->setPaper('a4', 'portrait')
                ->setOption('isRemoteEnabled', true)
                ->setOption('isHtml5ParserEnabled', true);

            $filename = sprintf('%s.kinerja-%s.pdf', $user->id, $selectedMonthStart->format('m-Y'));
            $storagePath = "satker_ckh/{$user->id}/{$filename}";
            $pdfBinary = $pdf->output();

            // Ensure directory exists before saving
            $fullDirPath = storage_path('app/public/satker_ckh/' . $user->id);
            if (! is_dir($fullDirPath)) {
                if (! mkdir($fullDirPath, 0755, true) && ! is_dir($fullDirPath)) {
                    Log::error('Gagal membuat direktori untuk PDF CKH', [
                        'user_id' => $user->id,
                        'path' => $fullDirPath,
                    ]);
                }
            }

            // Save PDF to storage
            $saved = Storage::disk('public')->put($storagePath, $pdfBinary);
            if (! $saved) {
                Log::error('Gagal menyimpan PDF CKH ke storage', [
                    'user_id' => $user->id,
                    'storage_path' => $storagePath,
                ]);
            }

            // Update or insert satker_ckh table
            $reportData = [
                'item_id' => 1,
                'dept_id' => $user->dept_id,
                'user_id' => $user->id,
                'bulan' => $selectedMonthStart->toDateString(),
                'filename' => $filename,
                'status' => 'DIKIRIM',
                'alasan' => null,
                'petugas' => 777,
                'sending' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ];

            DB::table('satker_ckh')->updateOrInsert(
                [
                    'user_id' => $user->id,
                    'bulan' => $selectedMonthStart->toDateString(),
                ],
                $reportData
            );

            // Return PDF as download
            return response($pdfBinary, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => "inline; filename=\"{$filename}\"",
            ]);
        } catch (\Exception $e) {
            Log::error('Error generating PDF: ' . $e->getMessage());
            return $this->error('Gagal generate PDF', 500);
        }
    }
}
