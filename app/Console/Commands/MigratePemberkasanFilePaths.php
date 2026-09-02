<?php

namespace App\Console\Commands;

use App\Helpers\FileHelper;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MigratePemberkasanFilePaths extends Command
{
    protected $signature = 'pemberkasan:migrate-file-paths
                            {--dry-run : Preview tanpa pindah file}
                            {--delete-old : Hapus file lama setelah dipindah}
                            {--batch=50 : Records per batch}
                            {--start-id= : Mulai dari ID tertentu}
                            {--limit= : Batasi jumlah record}';

    protected $description = 'Migrate pemberkasan files from users_berkas to public/users_berkas with Request subdirectory';

    public function handle(): int
    {
        $this->info('===========================================');
        $this->info(' Migrate Pemberkasan File Paths');
        $this->info('===========================================');
        $this->newLine();

        // Get total count first (without loading all records)
        $totalRecords = DB::table('satker_pemberkasan')
            ->whereNotNull('files')
            ->where('files', '!=', '')
            ->where('files', '!=', '[]')
            ->where('files', '!=', 'null')
            ->count();

        $this->info("Found {$totalRecords} records with files.");
        $this->newLine();

        if ($totalRecords === 0) {
            $this->warn('No records found. Exiting.');
            return Command::SUCCESS;
        }

        if ($this->option('dry-run')) {
            return $this->dryRun($totalRecords);
        }

        if (!$this->confirm("This will migrate files for {$totalRecords} records. Continue?")) {
            $this->info('Migration cancelled.');
            return Command::FAILURE;
        }

        $this->newLine();
        $bar = $this->output->createProgressBar($totalRecords);
        $bar->start();

        $successCount = 0;
        $skippedCount = 0;
        $failedCount = 0;
        $filesMigrated = 0;
        $filesSkipped = 0;
        $filesFailed = 0;

        $batchSize = (int) $this->option('batch');
        $deleteOld = $this->option('delete-old');
        $lastId = 0;

        // Use cursor for memory-efficient processing
        $query = DB::table('satker_pemberkasan')
            ->whereNotNull('files')
            ->where('files', '!=', '')
            ->where('files', '!=', '[]')
            ->where('files', '!=', 'null')
            ->select(['id', 'noreq', 'user_id', 'files']);

        if ($this->option('start-id')) {
            $lastId = (int) $this->option('start-id') - 1;
            $query->where('id', '>=', $this->option('start-id'));
        }

        if ($this->option('limit')) {
            $query->limit((int) $this->option('limit'));
        }

        // Process records one by one using cursor
        foreach ($query->orderBy('id')->cursor() as $record) {
            $result = $this->processRecord($record, $deleteOld);

            if ($result['success']) {
                $successCount++;
                $filesMigrated += $result['migrated'];
                $filesSkipped += $result['skipped'];
                $bar->setMessage('<fg=green>OK</>');
            } else {
                if ($result['reason'] === 'skip') {
                    $skippedCount++;
                    $filesSkipped += $result['skipped'];
                    $bar->setMessage('<fg=yellow>SKIP</>');
                } else {
                    $failedCount++;
                    $filesFailed += $result['failed'];
                    $bar->setMessage('<fg=red>FAIL</>');
                }
            }

            $bar->advance();
            $lastId = $record->id;

            // Free memory periodically
            if (($successCount + $skippedCount + $failedCount) % 100 === 0) {
                gc_collect_cycles();
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
                ['<fg=yellow>Skipped (already migrated)</>', $skippedCount],
                ['<fg=red>Failed</>', $failedCount],
                ['Total Records', $totalRecords],
                ['Files Migrated', $filesMigrated],
                ['Files Skipped', $filesSkipped],
                ['Files Failed', $filesFailed],
            ]
        );

        $this->newLine();
        $this->info('Files migrated to: storage/app/public/users_berkas/{nomor_induk}/Request/');

        if ($deleteOld) {
            $this->info('Old files have been deleted from: storage/app/users_berkas/{nomor_induk}/');
        } else {
            $this->warn('Old files kept. Run with --delete-old to remove them.');
        }

        $this->newLine();
        $this->info("Last processed ID: {$lastId}");

        return Command::SUCCESS;
    }

    protected function dryRun($totalRecords): int
    {
        $this->warn('DRY RUN MODE - No files will be moved.');
        $this->newLine();

        $headers = ['ID', 'noreq', 'Files Count', 'Status', 'Details'];
        $rows = [];
        $filesToMigrate = 0;
        $sampleSize = min(20, $totalRecords);

        // Process only first 20 records for dry-run
        $records = DB::table('satker_pemberkasan')
            ->whereNotNull('files')
            ->where('files', '!=', '')
            ->where('files', '!=', '[]')
            ->where('files', '!=', 'null')
            ->orderBy('id')
            ->limit($sampleSize)
            ->get();

        foreach ($records as $record) {
            $files = json_decode($record->files, true) ?: [];
            $fileCount = count($files);

            $status = 'ready';
            $details = '';

            $migrateCount = 0;
            foreach ($files as $file) {
                $filename = $file['filename'] ?? 'NONE';
                if ($filename === 'NONE') {
                    continue;
                }

                // Get nomor_induk from users table
                $user = DB::table('users')->where('id', $record->user_id)->first();
                $nomorInduk = $user->nomor_induk ?? null;

                if (!$nomorInduk) {
                    $status = 'error';
                    $details = 'User not found';
                    continue;
                }

                $legacyPath = FileHelper::getLegacyPath($nomorInduk, $filename);
                $newPath = FileHelper::getPemberkasanPath($nomorInduk, $filename);

                $existsLegacy = Storage::disk('users_berkas')->exists($legacyPath);
                $existsNew = Storage::disk('public')->exists($newPath);

                if ($existsNew) {
                    $status = 'skip';
                    $details = 'Already in new location';
                } elseif ($existsLegacy) {
                    $status = 'migrate';
                    $details = 'Move from legacy to public';
                    $migrateCount++;
                } else {
                    $status = 'warning';
                    $details = 'File not found in either location';
                }
            }

            $filesToMigrate += $migrateCount;

            $rows[] = [
                $record->id,
                $record->noreq,
                $fileCount,
                $status,
                $details,
            ];
        }

        $this->table($headers, $rows);

        if ($totalRecords > $sampleSize) {
            $this->newLine();
            $this->info("... and " . ($totalRecords - $sampleSize) . " more records.");
        }

        $this->newLine();
        $this->info("Total records: {$totalRecords}");
        $this->info("Files to migrate (sampled): {$filesToMigrate}");

        return Command::SUCCESS;
    }

    protected function processRecord(object $record, bool $deleteOld): array
    {
        $files = json_decode($record->files, true) ?: [];

        if (empty($files)) {
            return [
                'success' => true,
                'reason' => 'skip',
                'migrated' => 0,
                'skipped' => 0,
                'failed' => 0,
            ];
        }

        // Get nomor_induk
        $user = DB::table('users')->where('id', $record->user_id)->first();
        if (!$user || empty($user->nomor_induk)) {
            return [
                'success' => false,
                'reason' => 'error',
                'migrated' => 0,
                'skipped' => 0,
                'failed' => 0,
            ];
        }

        $nomorInduk = $user->nomor_induk;
        $migrated = 0;
        $skipped = 0;
        $failed = 0;
        $filesUpdated = false;

        foreach ($files as &$file) {
            $filename = $file['filename'] ?? 'NONE';

            if ($filename === 'NONE') {
                continue;
            }

            $legacyPath = FileHelper::getLegacyPath($nomorInduk, $filename);
            $newPath = FileHelper::getPemberkasanPath($nomorInduk, $filename);

            $existsLegacy = Storage::disk('users_berkas')->exists($legacyPath);
            $existsNew = Storage::disk('public')->exists($newPath);

            // Already in new location
            if ($existsNew) {
                $skipped++;
                continue;
            }

            // Not in legacy location either
            if (!$existsLegacy) {
                $this->warn("  File not found: {$filename} for noreq {$record->noreq}");
                $failed++;
                continue;
            }

            // Migrate the file
            $success = FileHelper::migrateFileToPublic($nomorInduk, $filename, $deleteOld);

            if ($success) {
                $migrated++;
                $filesUpdated = true;
            } else {
                $failed++;
            }
        }
        unset($file);

        // Update database if any files were migrated
        if ($filesUpdated) {
            DB::table('satker_pemberkasan')
                ->where('id', $record->id)
                ->update([
                    'files' => json_encode($files),
                    'updated_at' => now(),
                ]);
        }

        return [
            'success' => $failed === 0,
            'reason' => $failed > 0 ? 'error' : 'ok',
            'migrated' => $migrated,
            'skipped' => $skipped,
            'failed' => $failed,
        ];
    }
}
