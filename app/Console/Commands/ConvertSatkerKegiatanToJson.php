<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ConvertSatkerKegiatanToJson extends Command
{
    protected $signature = 'satker:convert-to-json {--dry-run : Show what would be converted without making changes} {--user= : Convert only for specific user ID}';

    protected $description = 'Convert satker_kegiatan from per-row format to per-date JSON format';

    public function handle(): int
    {
        $dryRun = $this->option('dry-run');
        $userId = $this->option('user');

        if ($dryRun) {
            $this->info('DRY RUN MODE - No changes will be made');
        }

        $this->info('Starting conversion of satker_kegiatan to JSON format...');

        // Get all unique (user_id, tanggal) combinations that need conversion
        $combinations = DB::table('satker_kegiatan')
            ->select('user_id', 'tanggal')
            ->whereNull('data_json')
            ->whereNotNull('kegiatan')
            ->where('kegiatan', '!=', '');

        if ($userId) {
            $combinations->where('user_id', $userId);
        }

        $combinations = $combinations
            ->groupBy('user_id', 'tanggal')
            ->selectRaw('user_id, tanggal, COUNT(*) as count, MIN(id) as keep_id')
            ->get();

        if ($combinations->isEmpty()) {
            $this->info('No data needs conversion.');
            return Command::SUCCESS;
        }

        $totalGroups = $combinations->count();
        $this->info("Found {$totalGroups} date groups to convert.");

        $bar = $this->output->createProgressBar($totalGroups);
        $bar->start();

        $converted = 0;
        $deleted = 0;

        foreach ($combinations as $group) {
            // Get all rows for this user+date combination
            $rows = DB::table('satker_kegiatan')
                ->where('user_id', $group->user_id)
                ->where('tanggal', $group->tanggal)
                ->orderBy('id')
                ->get();

            if ($rows->isEmpty()) {
                $bar->advance();
                continue;
            }

            // Build JSON items
            $items = [];
            $itemId = 1;
            $firstRow = null;

            foreach ($rows as $row) {
                if ($firstRow === null) {
                    $firstRow = $row;
                }

                $items[] = [
                    'id' => $itemId++,
                    'k' => trim((string) $row->kegiatan),
                    'v' => (int) ($row->volume ?? 0),
                    's' => trim((string) ($row->satuan ?? 'Kegiatan')),
                ];
            }

            $jsonData = json_encode(['items' => $items], JSON_UNESCAPED_UNICODE);

            if ($dryRun) {
                $this->line("  [DRY-RUN] Would convert {$group->count} rows for user {$group->user_id} on {$group->tanggal}");
            } else {
                // Update the first row with JSON data
                DB::table('satker_kegiatan')
                    ->where('id', $group->keep_id)
                    ->update([
                        'data_json' => $jsonData,
                        'updated_at' => now(),
                    ]);

                // Delete the other rows (except the one we updated)
                DB::table('satker_kegiatan')
                    ->where('user_id', $group->user_id)
                    ->where('tanggal', $group->tanggal)
                    ->where('id', '!=', $group->keep_id)
                    ->delete();

                $deleted += ($group->count - 1);
            }

            $converted++;
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        if ($dryRun) {
            $this->info('DRY RUN COMPLETE - No changes were made.');
            $this->info("Would convert {$totalGroups} groups.");
        } else {
            $this->info("Conversion complete!");
            $this->info("- Groups converted: {$converted}");
            $this->info("- Rows deleted (merged): {$deleted}");
        }

        return Command::SUCCESS;
    }
}