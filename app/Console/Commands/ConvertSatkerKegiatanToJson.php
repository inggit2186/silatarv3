<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ConvertSatkerKegiatanToJson extends Command
{
    protected $signature = 'satker:convert-kegiatan
                            {--dry-run : Preview without making changes}
                            {--chunk=100 : Records per batch for processing}
                            {--start-id= : Start from specific satker_kegiatan ID}
                            {--limit= : Limit total groups to process}
                            {--user= : Convert only for specific user ID}';

    protected $description = 'Convert satker_kegiatan from per-row format (1 kegiatan = 1 row) to per-date JSON format and delete old rows';

    public function handle(): int
    {
        $this->info('================================================');
        $this->info(' satker_kegiatan - Convert to JSON Format');
        $this->info('================================================');
        $this->newLine();

        $dryRun = $this->option('dry-run');
        $chunkSize = (int) $this->option('chunk');
        $startId = $this->option('start-id');
        $limit = $this->option('limit');
        $userId = $this->option('user');

        if ($dryRun) {
            $this->warn('DRY RUN MODE - No changes will be made');
        }

        $this->info('Step 1: Finding date groups to convert...');

        // Get count using aggregate query
        $countQuery = DB::table('satker_kegiatan')
            ->whereNull('data_json')
            ->whereNotNull('kegiatan')
            ->where('kegiatan', '!=', '');

        if ($startId) {
            $countQuery->where('id', '>=', (int) $startId);
        }

        if ($userId) {
            $countQuery->where('user_id', (int) $userId);
        }

        $countResult = $countQuery->selectRaw('COUNT(DISTINCT CONCAT(user_id, "-", tanggal)) as cnt')->first();
        $totalGroups = $countResult->cnt ?? 0;

        if ($totalGroups === 0) {
            $this->info('No data needs conversion (all records already have data_json or empty kegiatan).');
            return Command::SUCCESS;
        }

        $this->info("Found {$totalGroups} date groups to convert.");

        // Get preview (first 5)
        $this->info('Preview (first 5 groups):');
        $headers = ['User ID', 'Tanggal', 'Rows', 'Keep ID'];
        $rows = [];

        $previewQuery = DB::table('satker_kegiatan')
            ->whereNull('data_json')
            ->whereNotNull('kegiatan')
            ->where('kegiatan', '!=', '');

        if ($startId) {
            $previewQuery->where('id', '>=', (int) $startId);
        }

        if ($userId) {
            $previewQuery->where('user_id', (int) $userId);
        }

        $previewQuery->groupBy('user_id', 'tanggal');
        $previewQuery->selectRaw('MIN(id) as keep_id, user_id, tanggal, COUNT(*) as row_count');
        $previewQuery->orderBy('user_id')->orderBy('tanggal');

        $count = 0;
        foreach ($previewQuery->cursor() as $group) {
            $rows[] = [$group->user_id, $group->tanggal, $group->row_count, $group->keep_id];
            $count++;
            if ($count >= 5) break;
        }

        $this->table($headers, $rows);

        // Calculate total rows to delete
        $totalRowsToDelete = $previewQuery->cursor()->sum('row_count') - $totalGroups;

        $this->info("Will merge {$totalRowsToDelete} duplicate rows into JSON format.");
        $this->newLine();

        if ($dryRun) {
            $this->newLine();
            $this->info('DRY RUN COMPLETE - No changes were made.');
            return Command::SUCCESS;
        }

        if (!$this->confirm("Proceed with conversion? This will merge rows and delete {$totalRowsToDelete} duplicate records.")) {
            $this->info('Conversion cancelled.');
            return Command::SUCCESS;
        }

        $this->newLine();
        $this->info('Step 2: Converting...');
        $bar = $this->output->createProgressBar($totalGroups);
        $bar->setFormat(' %current%/%max% [%bar%] %percent:3s%% | Memory: %memory:6s%');
        $bar->start();

        $converted = 0;
        $deleted = 0;
        $errors = 0;

        // Process each group
        $processQuery = DB::table('satker_kegiatan')
            ->whereNull('data_json')
            ->whereNotNull('kegiatan')
            ->where('kegiatan', '!=', '');

        if ($startId) {
            $processQuery->where('id', '>=', (int) $startId);
        }

        if ($userId) {
            $processQuery->where('user_id', (int) $userId);
        }

        $processQuery->groupBy('user_id', 'tanggal');
        $processQuery->selectRaw('MIN(id) as keep_id, user_id, tanggal, COUNT(*) as row_count');
        $processQuery->orderBy('user_id')->orderBy('tanggal');

        if ($limit) {
            $processQuery->limit((int) $limit);
        }

        foreach ($processQuery->cursor() as $group) {
            try {
                // Get all rows for this user+date
                $kegiatanRows = DB::table('satker_kegiatan')
                    ->where('user_id', $group->user_id)
                    ->where('tanggal', $group->tanggal)
                    ->orderBy('id')
                    ->get();

                if ($kegiatanRows->isEmpty()) {
                    $bar->advance();
                    continue;
                }

                // Build JSON items
                $items = [];
                $itemId = 1;

                foreach ($kegiatanRows as $row) {
                    if (empty(trim((string) $row->kegiatan))) {
                        continue;
                    }

                    $items[] = [
                        'id' => $itemId++,
                        'k' => trim((string) $row->kegiatan),
                        'v' => (int) ($row->volume ?? 0),
                        's' => trim((string) ($row->satuan ?? 'Kegiatan')),
                    ];
                }

                if (empty($items)) {
                    $bar->advance();
                    continue;
                }

                $jsonData = json_encode(['items' => $items], JSON_UNESCAPED_UNICODE);

                // Update the first row with JSON data
                DB::table('satker_kegiatan')
                    ->where('id', $group->keep_id)
                    ->update([
                        'kegiatan' => $items[0]['k'],
                        'volume' => $items[0]['v'],
                        'satuan' => $items[0]['s'],
                        'data_json' => $jsonData,
                        'updated_at' => now(),
                    ]);

                // Delete the other rows
                $deletedCount = DB::table('satker_kegiatan')
                    ->where('user_id', $group->user_id)
                    ->where('tanggal', $group->tanggal)
                    ->where('id', '!=', $group->keep_id)
                    ->delete();

                $converted++;
                $deleted += $deletedCount;

            } catch (\Exception $e) {
                $errors++;
                $this->newLine();
                $this->error("Error on user {$group->user_id}, date {$group->tanggal}: {$e->getMessage()}");
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->info('================================================');
        $this->info(' Conversion Complete!');
        $this->info('================================================');
        $this->newLine();

        $this->table(
            ['Metric', 'Count'],
            [
                ['Groups Converted', $converted],
                ['Rows Deleted (merged)', $deleted],
                ['Errors', $errors],
                ['Total Groups Processed', $totalGroups],
            ]
        );

        $this->newLine();

        // Verify
        $remaining = DB::table('satker_kegiatan')
            ->whereNull('data_json')
            ->whereNotNull('kegiatan')
            ->where('kegiatan', '!=', '')
            ->count();

        if ($remaining > 0) {
            $this->warn("Warning: {$remaining} records still need conversion.");
            $this->info("Run again with --start-id option to continue.");
        } else {
            $this->info('All records have been converted to JSON format!');
        }

        return Command::SUCCESS;
    }
}
