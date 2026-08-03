<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class MigrateCkhFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ckh:migrate-files
                            {--dry-run : Preview without downloading}
                            {--chunk=50 : Records per batch}
                            {--start-id= : Start from specific ID}
                            {--limit= : Limit total records}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate CKH PDF files from ptsp.kemenagtanahdatar.cloud to local storage';

    /**
     * Source base URL
     */
    protected string $sourceBaseUrl = 'https://ptsp.kemenagtanahdatar.cloud/uploads/UsersBerkas';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('===========================================');
        $this->info(' CKH Files Migration - PTSP to SILATAR V2');
        $this->info('===========================================');
        $this->newLine();

        // Build query
        $query = DB::table('satker_ckh as ckh')
            ->join('users as u', 'u.id', '=', 'ckh.user_id')
            ->whereNotNull('ckh.filename')
            ->where('ckh.filename', '!=', '')
            ->select([
                'ckh.id',
                'ckh.user_id',
                'ckh.filename',
                'ckh.bulan',
                'u.nomor_induk',
            ]);

        // Apply filters
        if ($this->option('start-id')) {
            $query->where('ckh.id', '>=', (int) $this->option('start-id'));
        }

        if ($this->option('limit')) {
            $query->limit((int) $this->option('limit'));
        }

        $records = $query->orderBy('ckh.id')->get();
        $totalRecords = $records->count();

        $this->info("Found {$totalRecords} records to process.");
        $this->newLine();

        if ($totalRecords === 0) {
            $this->warn('No records found. Exiting.');
            return Command::SUCCESS;
        }

        // Dry run mode
        if ($this->option('dry-run')) {
            $this->warn('DRY RUN MODE - No files will be downloaded.');
            $this->newLine();

            $headers = ['ID', 'User ID', 'Filename', 'Source URL'];
            $rows = $records->take(10)->map(function ($record) {
                return [
                    $record->id,
                    $record->user_id,
                    $record->filename,
                    $this->buildSourceUrl($record->nomor_induk, $record->filename),
                ];
            })->toArray();

            $this->table($headers, $rows);

            if ($totalRecords > 10) {
                $this->newLine();
                $this->info("... and " . ($totalRecords - 10) . " more records.");
            }

            return Command::SUCCESS;
        }

        // Confirm before starting
        if (!$this->confirm("This will attempt to download {$totalRecords} files. Continue?")) {
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
                        $bar->setMessage("<fg=green>OK</>");
                        break;
                    case 'skipped':
                        $skippedCount++;
                        $bar->setMessage("<fg=yellow>SKIP</>");
                        break;
                    case 'failed':
                        $failedCount++;
                        $bar->setMessage("<fg=red>FAIL</>");
                        break;
                }

                $bar->advance();
            }

            // Small delay between chunks to avoid overwhelming the server
            if ($chunkIndex < ceil($totalRecords / $chunkSize) - 1) {
                usleep(100000); // 100ms delay
            }
        }

        $bar->finish();
        $this->newLine(2);

        $this->info('Migration completed!');
        $this->newLine();

        $this->table(
            ['Status', 'Count'],
            [
                ['<fg=green>Success</>', $successCount],
                ['<fg=yellow>Skipped (file exists)</>', $skippedCount],
                ['<fg=red>Failed (download error)</>', $failedCount],
                ['Total', $totalRecords],
            ]
        );

        $this->newLine();
        $this->info('Files saved to: storage/app/public/satker_ckh/{user_id}/');

        return Command::SUCCESS;
    }

    /**
     * Build source URL for a record
     */
    protected function buildSourceUrl(?string $nomorInduk, string $filename): string
    {
        if (empty($nomorInduk)) {
            return '<fg=red>No nomor_induk</>';
        }

        return "{$this->sourceBaseUrl}/{$nomorInduk}/Kinerja/{$filename}";
    }

    /**
     * Process a single record
     */
    protected function processRecord(object $record): string
    {
        // Skip if no nomor_induk
        if (empty($record->nomor_induk)) {
            return 'skipped';
        }

        // Build paths
        $sourceUrl = $this->buildSourceUrl($record->nomor_induk, $record->filename);
        $destinationPath = "satker_ckh/{$record->user_id}/{$record->filename}";

        // Check if file already exists
        if (Storage::disk('public')->exists($destinationPath)) {
            return 'skipped';
        }

        // Try to download
        try {
            $response = Http::timeout(30)
                ->withHeaders([
                    'User-Agent' => 'SILATAR-V2-Migration/1.0',
                ])
                ->get($sourceUrl);

            if ($response->successful()) {
                // Save file
                Storage::disk('public')->put($destinationPath, $response->body());
                return 'success';
            }

            // Log failed download
            $this->warn("\nFailed to download ID {$record->id}: HTTP {$response->status()} - {$sourceUrl}");
            return 'failed';

        } catch (\Exception $e) {
            $this->warn("\nError downloading ID {$record->id}: {$e->getMessage()}");
            return 'failed';
        }
    }
}
