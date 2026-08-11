<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class DownloadPemberkasanFiles extends Command
{
    protected $signature = 'pemberkasan:download-files
                            {--dry-run : Preview tanpa download}
                            {--chunk=50 : Records per batch}
                            {--start-id= : Mulai dari ID tertentu}
                            {--limit= : Batasi jumlah record}
                            {--noreq= : Proses noreq spesifik}
                            {--from-date= : Filter dari tanggal (waktu) - format: YYYY-MM-DD}';

    protected $description = 'Download file syarat pemberkasan dari API PTSP lama ke storage lokal';

    protected string $apiBaseUrl = 'https://ptsp.kemenagtanahdatar.cloud/api/v1';

    public function handle(): int
    {
        $this->info('===========================================');
        $this->info(' Download Pemberkasan Files - PTSP to SILATAR');
        $this->info('===========================================');
        $this->newLine();

        $query = DB::table('satker_pemberkasan as sp')
            ->join('users as u', 'u.id', '=', 'sp.user_id')
            ->where(function ($q) {
                $q->whereNull('sp.files')
                    ->orWhere('sp.files', '');
            })
            ->whereNotNull('sp.noreq')
            ->where('sp.noreq', '!=', '')
            ->select([
                'sp.id',
                'sp.noreq',
                'sp.user_id',
                'sp.layanan_id',
                'sp.tipe',
                'sp.waktu',
                'sp.item_id',
                'u.nomor_induk',
                'u.name',
            ]);

        if ($this->option('start-id')) {
            $query->where('sp.id', '>=', (int) $this->option('start-id'));
        }

        if ($this->option('limit')) {
            $query->limit((int) $this->option('limit'));
        }

        if ($this->option('noreq')) {
            $query->where('sp.noreq', $this->option('noreq'));
        }

        if ($this->option('from-date')) {
            $query->where('sp.waktu', '>=', $this->option('from-date'));
        }

        $records = $query->orderBy('sp.id')->get();
        $totalRecords = $records->count();

        $this->info("Found {$totalRecords} records to process.");
        $this->newLine();

        if ($totalRecords === 0) {
            $this->warn('No records found. Exiting.');
            return Command::SUCCESS;
        }

        if ($this->option('dry-run')) {
            return $this->dryRun($records, $totalRecords);
        }

        if (!$this->confirm("This will download files for {$totalRecords} records. Continue?")) {
            $this->info('Migration cancelled.');
            return Command::FAILURE;
        }

        $this->newLine();
        $bar = $this->output->createProgressBar($totalRecords);
        $bar->setFormat(' %current%/%max% [%bar%] %percent:3s%% | %message%');
        $bar->start();

        $successCount = 0;
        $skippedCount = 0;
        $failedCount = 0;

        $chunkSize = (int) $this->option('chunk');

        foreach ($records->chunk($chunkSize) as $chunkIndex => $chunk) {
            foreach ($chunk as $record) {
                $result = $this->processRecord($record);

                switch ($result) {
                    case 'success':
                        $successCount++;
                        $bar->setMessage('<fg=green>OK</>');
                        break;
                    case 'skipped':
                        $skippedCount++;
                        $bar->setMessage('<fg=yellow>SKIP</>');
                        break;
                    case 'failed':
                        $failedCount++;
                        $bar->setMessage('<fg=red>FAIL</>');
                        break;
                }

                $bar->advance();
            }

            if ($chunkIndex < ceil($totalRecords / $chunkSize) - 1) {
                usleep(100000);
            }
        }

        $bar->finish();
        $this->newLine(2);

        $this->info('Download completed!');
        $this->newLine();

        $this->table(
            ['Status', 'Count'],
            [
                ['<fg=green>Success</>', $successCount],
                ['<fg=yellow>Skipped (already exists or no API data)</>', $skippedCount],
                ['<fg=red>Failed (API error)</>', $failedCount],
                ['Total', $totalRecords],
            ]
        );

        $this->newLine();
        $this->info('Files saved to: storage/app/users_berkas/{nomor_induk}/');

        return Command::SUCCESS;
    }

    protected function dryRun($records, int $totalRecords): int
    {
        $this->warn('DRY RUN MODE - No files will be downloaded.');
        $this->newLine();

        $headers = ['ID', 'noreq', 'User', 'nomor_induk', '# Files', 'API Status'];
        $rows = [];

        foreach ($records->take(20) as $record) {
            $apiResult = $this->fetchApiData($record->noreq);
            $fileCount = 0;
            $apiStatus = 'error';

            if ($apiResult) {
                $apiStatus = 'success';
                $syarat = $apiResult['syarat'] ?? [];
                $fileCount = count(array_filter($syarat, fn($s) => !empty($s['fileUrl']) && $s['fileUrl'] !== 'NONE'));
            } elseif ($apiResult === false) {
                $apiStatus = '404';
            }

            $rows[] = [
                $record->id,
                $record->noreq,
                $record->name ?? '-',
                $record->nomor_induk ?? '-',
                $fileCount,
                $apiStatus,
            ];
        }

        $this->table($headers, $rows);

        if ($totalRecords > 20) {
            $this->newLine();
            $this->info('... and ' . ($totalRecords - 20) . ' more records.');
        }

        return Command::SUCCESS;
    }

    protected function processRecord(object $record): string
    {
        if (empty($record->nomor_induk)) {
            return 'skipped';
        }

        $apiData = $this->fetchApiData($record->noreq);

        if ($apiData === false) {
            return 'skipped';
        }

        if (!$apiData) {
            return 'failed';
        }

        $syaratList = $apiData['syarat'] ?? [];
        if (empty($syaratList)) {
            return 'skipped';
        }

        $files = [];
        $requirementsSnapshot = [];
        $downloaded = 0;
        $failed = 0;

        foreach ($syaratList as $syarat) {
            $syaratId = (int) ($syarat['id'] ?? 0);
            $title = $syarat['nama'] ?? '';
            $note = $syarat['keterangan'] ?? '';
            $isRequired = (int) ($syarat['wajib'] ?? 0) === 1;
            $type = 'file';
            $fileUrl = $syarat['fileUrl'] ?? '';
            $filename = $syarat['filename'] ?? '';
            $filetype = $syarat['filetype'] ?? null;
            $size = $syarat['size'] ?? null;

            $requirementsSnapshot[] = [
                'id' => $syaratId,
                'title' => $title,
                'note' => $note,
                'is_required' => $isRequired,
                'type' => $type,
            ];

            if (empty($fileUrl) || $fileUrl === 'NONE' || empty($filename)) {
                $files[] = [
                    'syarat_id' => $syaratId,
                    'title' => $title,
                    'type' => $type,
                    'is_required' => $isRequired,
                    'filename' => 'NONE',
                    'filetype' => null,
                    'size' => null,
                    'status' => 0,
                    'uploaded_at' => null,
                    'source' => 'ptsp_api',
                ];
                continue;
            }

            $storagePath = "{$record->nomor_induk}/{$filename}";

            if (Storage::disk('users_berkas')->exists($storagePath)) {
                $files[] = [
                    'syarat_id' => $syaratId,
                    'title' => $title,
                    'type' => $type,
                    'is_required' => $isRequired,
                    'filename' => $filename,
                    'filetype' => $filetype,
                    'size' => $size,
                    'status' => 1,
                    'uploaded_at' => null,
                    'source' => 'ptsp_api',
                ];
                $downloaded++;
                continue;
            }

            try {
                $response = Http::timeout(30)
                    ->withHeaders(['User-Agent' => 'SILATAR-V2-Migration/1.0'])
                    ->get($fileUrl);

                if ($response->successful()) {
                    Storage::disk('users_berkas')->put($storagePath, $response->body());

                    $files[] = [
                        'syarat_id' => $syaratId,
                        'title' => $title,
                        'type' => $type,
                        'is_required' => $isRequired,
                        'filename' => $filename,
                        'filetype' => $filetype,
                        'size' => $size,
                        'status' => 1,
                        'uploaded_at' => null,
                        'source' => 'ptsp_api',
                    ];
                    $downloaded++;
                } else {
                    $this->warn("\n  File 404: {$record->noreq} - {$filename} (HTTP {$response->status()})");

                    $files[] = [
                        'syarat_id' => $syaratId,
                        'title' => $title,
                        'type' => $type,
                        'is_required' => $isRequired,
                        'filename' => $filename,
                        'filetype' => $filetype,
                        'size' => $size,
                        'status' => 0,
                        'uploaded_at' => null,
                        'source' => 'ptsp_api',
                    ];
                    $failed++;
                }
            } catch (\Exception $e) {
                $this->warn("\n  Error downloading: {$record->noreq} - {$filename}: {$e->getMessage()}");

                $files[] = [
                    'syarat_id' => $syaratId,
                    'title' => $title,
                    'type' => $type,
                    'is_required' => $isRequired,
                    'filename' => $filename,
                    'filetype' => $filetype,
                    'size' => $size,
                    'status' => 0,
                    'uploaded_at' => null,
                    'source' => 'ptsp_api',
                ];
                $failed++;
            }
        }

        $metadata = [
            'source' => 'ptsp_api',
            'downloaded_at' => now()->toIso8601String(),
            'api_success' => true,
            'files_downloaded' => $downloaded,
            'files_failed' => $failed,
        ];

        DB::table('satker_pemberkasan')
            ->where('id', $record->id)
            ->update([
                'files' => json_encode($files),
                'requirements_snapshot' => json_encode($requirementsSnapshot),
                'metadata' => json_encode($metadata),
                'is_migrated' => true,
                'migrated_at' => now(),
            ]);

        return $downloaded > 0 ? 'success' : 'skipped';
    }

    /**
     * Fetch pemberkasan data from external PTSP API.
     *
     * @return array|false|null  Array with 'data' key on success, false on 404, null on error
     */
    protected function fetchApiData(string $noreq): array|false|null
    {
        try {
            $response = Http::timeout(30)
                ->withHeaders(['User-Agent' => 'SILATAR-V2-Migration/1.0'])
                ->get("{$this->apiBaseUrl}/AgetPemberkasan/{$noreq}");

            if ($response->status() === 404) {
                return false;
            }

            if ($response->successful()) {
                $body = $response->json();
                return $body;
            }

            return null;
        } catch (\Exception $e) {
            $this->warn("\n  API error for {$noreq}: {$e->getMessage()}");
            return null;
        }
    }
}
