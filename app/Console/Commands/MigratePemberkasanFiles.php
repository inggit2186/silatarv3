<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigratePemberkasanFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pemberkasan:migrate-files
                            {--dry-run : Preview without making changes}
                            {--force : Skip confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate files from satker_filepemberkasan to JSON format in satker_pemberkasan';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Starting Pemberkasan Files Migration...');
        $this->newLine();

        // Get all unique noreq first, then process each
        $noreqList = DB::table('satker_filepemberkasan')
            ->distinct()
            ->pluck('noreq');

        $totalRecords = $noreqList->count();
        $this->info("Found {$totalRecords} unique noreq to migrate.");
        $this->newLine();

        if ($this->option('dry-run')) {
            $this->warn('DRY RUN MODE - No changes will be made.');
            $this->newLine();

            $headers = ['noreq'];
            $rows = $noreqList->map(fn($noreq) => [$noreq])->toArray();

            $this->table($headers, $rows);
            return Command::SUCCESS;
        }

        if (!$this->option('force')) {
            if (!$this->confirm("This will migrate {$totalRecords} noreq records. Continue?")) {
                $this->info('Migration cancelled.');
                return Command::FAILURE;
            }
        }

        $this->newLine();
        $bar = $this->output->createProgressBar($totalRecords);
        $bar->start();

        $successCount = 0;
        $skipCount = 0;
        $errorCount = 0;

        DB::beginTransaction();

        try {
            foreach ($noreqList as $noreq) {
                // Get all files for this noreq
                $files = DB::table('satker_filepemberkasan')
                    ->where('noreq', $noreq)
                    ->get();

                if ($files->isEmpty()) {
                    $skipCount++;
                    $bar->advance();
                    continue;
                }

                $firstFile = $files->first();

                // Check if parent record exists
                $existing = DB::table('satker_pemberkasan')
                    ->where('noreq', $noreq)
                    ->first();

                if (!$existing) {
                    $skipCount++;
                    $bar->advance();
                    continue;
                }

                // Check if already migrated
                if (!empty($existing->files)) {
                    $skipCount++;
                    $bar->advance();
                    continue;
                }

                // Prepare files array
                $formattedFiles = $files->map(function ($file) {
                    return [
                        'syarat_id' => $file->syarat_id,
                        'filename' => $file->filename ?? 'NONE',
                        'filetype' => $file->filetype,
                        'size' => $file->size,
                        'status' => (int) $file->status,
                    ];
                })->toArray();

                // Get requirements snapshot from ktd_syarat at migration time
                $requirementsSnapshot = DB::table('ktd_syarat')
                    ->where('layanan_id', $firstFile->layanan_id)
                    ->where('wajib', '!=', 2)
                    ->get()
                    ->map(function ($syarat) {
                        return [
                            'id' => $syarat->id,
                            'title' => $syarat->syarat,
                            'note' => $syarat->keterangan,
                            'is_required' => (int) $syarat->wajib === 1,
                            'type' => $syarat->type ?? 'file',
                        ];
                    })
                    ->toArray();

                // Build metadata
                $metadata = [
                    'migrated_at' => now()->toIso8601String(),
                    'source' => 'migration',
                ];

                // Update parent record with JSON
                DB::table('satker_pemberkasan')
                    ->where('noreq', $noreq)
                    ->update([
                        'files' => json_encode($formattedFiles),
                        'requirements_snapshot' => json_encode($requirementsSnapshot),
                        'metadata' => json_encode($metadata),
                        'is_migrated' => true,
                        'migrated_at' => now(),
                    ]);

                $successCount++;
                $bar->advance();
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->newLine(2);
            $this->error("Migration failed: {$e->getMessage()}");
            return Command::FAILURE;
        }

        $bar->finish();
        $this->newLine(2);

        $this->info('Migration completed!');
        $this->table(
            ['Status', 'Count'],
            [
                ['Success', $successCount],
                ['Skipped (no parent record or already migrated)', $skipCount],
                ['Errors', $errorCount],
                ['Total', $totalRecords],
            ]
        );

        return Command::SUCCESS;
    }
}
