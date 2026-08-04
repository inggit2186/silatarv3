<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportSatkerKegiatanSupplement extends Command
{
    protected $signature = 'satker:import-supplement
                            {file : Path ke file SQL}
                            {--dry-run : Preview tanpa menyimpan perubahan}
                            {--chunk=1000 : Ukuran chunk untuk proses}';

    protected $description = 'Import data kegiatan dari file SQL ke satker_kegiatan (hanya tanggal kosong)';

    protected int $chunkSize = 1000;

    public function handle(): int
    {
        $file = $this->argument('file');
        $dryRun = $this->option('dry-run');
        $this->chunkSize = (int) $this->option('chunk');

        $this->info('================================================');
        $this->info('  Import Supplement Data Kegiatan');
        $this->info('  File: ' . basename($file));
        $this->info('================================================');
        $this->newLine();

        if (!file_exists($file)) {
            $this->error("File tidak ditemukan: {$file}");
            return Command::FAILURE;
        }

        if ($dryRun) {
            $this->warn('DRY RUN MODE - Tidak ada perubahan akan disimpan.');
            $this->newLine();
        }

        // Step 1: Get valid user_ids
        $this->info('Step 1: Load user_id valid...');
        $validUserIds = DB::table('users')->pluck('id')->flip()->toArray();
        $this->line("   Valid user_id: " . count($validUserIds));
        $this->newLine();

        // Step 2: Get existing dates (chunked to avoid memory issues)
        $this->info('Step 2: Load tanggal existing (chunked)...');
        $existingDates = [];
        $existingCount = 0;

        DB::table('satker_kegiatan')
            ->select('user_id', 'tanggal')
            ->orderBy('user_id')
            ->orderBy('tanggal')
            ->chunk(1000, function ($rows) use (&$existingDates, &$existingCount) {
                foreach ($rows as $row) {
                    $key = $row->user_id . '-' . $row->tanggal;
                    $existingDates[$key] = true;
                    $existingCount++;
                }
                // Free memory
                $this->line("   Loaded {$existingCount} existing dates...");
            });

        $this->line("   Tanggal existing: " . count($existingDates));
        $this->newLine();

        // Step 3: Stream parse file and count
        $this->info('Step 3: Parse file SQL (streaming)...');
        $stats = $this->streamParseAndCount($file, $validUserIds, $existingDates);

        $this->line("   Total record di SQL: " . $stats['total']);
        $this->line("   Record dengan user_id valid: " . $stats['valid']);
        $this->line("   Grup (user_id + tanggal): " . $stats['groups']);
        $this->line("   Grup dengan tanggal KOSONG: " . $stats['to_import']);
        $this->line("   Grup dengan tanggal SUDAH ada: " . ($stats['groups'] - $stats['to_import']));
        $this->newLine();

        if ($stats['to_import'] === 0) {
            $this->warn('Semua tanggal sudah ada. Tidak ada yang perlu diimport.');
            return Command::SUCCESS;
        }

        // Preview first 5
        $this->info('Preview (5 grup pertama):');
        $preview = $this->getPreview($file, $validUserIds, $existingDates, 5);
        if (count($preview) > 0) {
            $this->table(['User ID', 'Tanggal', 'Items'], $preview);
        }
        $this->newLine();

        if ($dryRun) {
            $this->info('DRY RUN COMPLETE.');
            return Command::SUCCESS;
        }

        // Step 4: Confirm
        if (!$this->confirm($stats['to_import'] . " grup data kegiatan akan diimport. Lanjutkan?")) {
            $this->info('Import dibatalkan.');
            return Command::SUCCESS;
        }

        // Step 5: Import
        $this->newLine();
        $this->info('Step 4: Import data (streaming)...');
        $result = $this->streamImport($file, $validUserIds, $existingDates);

        $this->newLine();
        $this->info('================================================');
        $this->info('  Import Complete!');
        $this->info('================================================');
        $this->newLine();
        $this->table(
            ['Metric', 'Count'],
            [
                ['Total Record di SQL', $stats['total']],
                ['Record user_id valid', $stats['valid']],
                ['Grup (user_id + tanggal)', $stats['groups']],
                ['Grup di-skip (existing)', $stats['groups'] - $result['imported']],
                ['Berhasil diimport', $result['imported']],
                ['Errors', $result['errors']],
            ]
        );

        return Command::SUCCESS;
    }

    protected function streamParseAndCount(string $file, array $validUserIds, array $existingDates): array
    {
        $stats = [
            'total' => 0,
            'valid' => 0,
            'groups' => 0,
            'to_import' => 0,
        ];

        $handle = fopen($file, 'r');
        if (!$handle) {
            return $stats;
        }

        $inInsert = false;
        $buffer = '';

        while (($line = fgets($handle)) !== false) {
            $line = trim($line);

            // Start of INSERT
            if (preg_match('/INSERT INTO\s+`?satker_kegiatan`?/i', $line)) {
                $inInsert = true;
                $buffer = $line;
                continue;
            }

            if ($inInsert) {
                $buffer .= ' ' . $line;

                // End of INSERT statement
                if (str_ends_with($line, ';')) {
                    $records = $this->extractRecordsFromInsert($buffer);

                    foreach ($records as $record) {
                        $stats['total']++;
                        $key = $record['user_id'] . '-' . $record['tanggal'];

                        // Check user_id
                        if (!isset($validUserIds[$record['user_id']])) {
                            continue;
                        }
                        $stats['valid']++;

                        // Check existing date
                        if (isset($existingDates[$key])) {
                            continue;
                        }

                        // New group
                        if (!isset($this->groupCounts[$key])) {
                            $this->groupCounts[$key] = true;
                            $stats['groups']++;
                            $stats['to_import']++;
                        }
                    }

                    $inInsert = false;
                    $buffer = '';

                    // Progress indicator every chunk
                    if ($stats['total'] % 5000 === 0) {
                        $this->line("   Processed {$stats['total']} records...");
                    }
                }
            }
        }

        fclose($handle);
        return $stats;
    }

    protected function getPreview(string $file, array $validUserIds, array $existingDates, int $limit): array
    {
        $preview = [];
        $found = 0;

        $handle = fopen($file, 'r');
        if (!$handle) {
            return $preview;
        }

        $inInsert = false;
        $buffer = '';
        $groupItems = [];

        while (($line = fgets($handle)) !== false && $found < $limit) {
            $line = trim($line);

            if (preg_match('/INSERT INTO\s+`?satker_kegiatan`?/i', $line)) {
                if ($inInsert && !empty($groupItems)) {
                    // Process previous group
                    foreach ($groupItems as $key => $items) {
                        if (!isset($existingDates[$key]) && $found < $limit) {
                            $parts = explode('-', $key);
                            $preview[] = [(int)$parts[0], $parts[1], count($items)];
                            $found++;
                        }
                    }
                }
                $inInsert = true;
                $buffer = $line;
                $groupItems = [];
                continue;
            }

            if ($inInsert) {
                $buffer .= ' ' . $line;
                if (str_ends_with($line, ';')) {
                    $records = $this->extractRecordsFromInsert($buffer);
                    foreach ($records as $record) {
                        if (!isset($validUserIds[$record['user_id']])) {
                            continue;
                        }
                        $key = $record['user_id'] . '-' . $record['tanggal'];
                        if (!isset($groupItems[$key])) {
                            $groupItems[$key] = [];
                        }
                        $groupItems[$key][] = $record;
                    }
                    $inInsert = false;
                    $buffer = '';
                }
            }
        }

        // Process last batch
        if (!empty($groupItems)) {
            foreach ($groupItems as $key => $items) {
                if (!isset($existingDates[$key]) && $found < $limit) {
                    $parts = explode('-', $key);
                    $preview[] = [(int)$parts[0], $parts[1], count($items)];
                    $found++;
                }
            }
        }

        fclose($handle);
        return $preview;
    }

    protected function streamImport(string $file, array $validUserIds, array $existingDates): array
    {
        $result = ['imported' => 0, 'errors' => 0];
        $groupItems = [];
        $lastKey = null;

        $handle = fopen($file, 'r');
        if (!$handle) {
            return $result;
        }

        $bar = $this->output->createProgressBar();
        $bar->setFormat(' %current% groups [%bar%] %percent:3s%%');

        $inInsert = false;
        $buffer = '';

        while (($line = fgets($handle)) !== false) {
            $line = trim($line);

            if (preg_match('/INSERT INTO\s+`?satker_kegiatan`?/i', $line)) {
                $inInsert = true;
                $buffer = $line;
                continue;
            }

            if ($inInsert) {
                $buffer .= ' ' . $line;

                if (str_ends_with($line, ';')) {
                    $records = $this->extractRecordsFromInsert($buffer);

                    foreach ($records as $record) {
                        if (!isset($validUserIds[$record['user_id']])) {
                            continue;
                        }

                        $key = $record['user_id'] . '-' . $record['tanggal'];

                        // Skip if date already exists
                        if (isset($existingDates[$key])) {
                            continue;
                        }

                        // Flush previous group if key changed
                        if ($lastKey !== null && $lastKey !== $key) {
                            $this->flushGroup($lastKey, $groupItems[$lastKey] ?? [], $result);
                            unset($groupItems[$lastKey]);
                        }

                        // Add to current group
                        if (!isset($groupItems[$key])) {
                            $groupItems[$key] = [];
                        }
                        $groupItems[$key][] = $record;
                        $lastKey = $key;
                    }

                    $inInsert = false;
                    $buffer = '';
                }
            }
        }

        // Flush last group
        if ($lastKey !== null && isset($groupItems[$lastKey])) {
            $this->flushGroup($lastKey, $groupItems[$lastKey], $result);
        }

        fclose($handle);
        $bar->finish();

        return $result;
    }

    protected function flushGroup(string $key, array $items, array &$result): void
    {
        if (empty($items)) {
            return;
        }

        try {
            $kegiatanItems = [];
            foreach ($items as $i => $item) {
                $kegiatanItems[] = [
                    'id' => $i + 1,
                    'k' => trim($item['k']),
                    'v' => (int) ($item['v'] ?? 1),
                    's' => trim($item['s'] ?? 'Kegiatan'),
                ];
            }

            $jsonData = json_encode(['items' => $kegiatanItems], JSON_UNESCAPED_UNICODE);
            $first = $kegiatanItems[0] ?? ['k' => '', 'v' => 0, 's' => 'Kegiatan'];

            // Parse key
            $parts = explode('-', $key);
            $userId = (int) $parts[0];
            $tanggal = $parts[1] . '-' . $parts[2] . '-' . $parts[3]; // Reconstruct date

            DB::table('satker_kegiatan')->insert([
                'user_id' => $userId,
                'tanggal' => $tanggal,
                'kegiatan' => $first['k'],
                'volume' => $first['v'],
                'satuan' => $first['s'],
                'data_json' => $jsonData,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $result['imported']++;
        } catch (\Exception $e) {
            $result['errors']++;
        }
    }

    protected function extractRecordsFromInsert(string $insertStatement): array
    {
        $records = [];

        // Extract column names
        if (!preg_match('/INSERT INTO\s+`?satker_kegiatan`?\s*\(([^)]+)\)/i', $insertStatement, $colMatch)) {
            return $records;
        }

        // Extract VALUES part
        if (!preg_match('/VALUES\s*(.+?);?$/is', $insertStatement, $valMatch)) {
            return $records;
        }

        $valuesPart = $valMatch[1];

        // Match individual value rows
        preg_match_all('/\(([^)]+(?:\'[^)]*\'[^)]*)*)\)/', $valuesPart, $rowMatches);

        foreach ($rowMatches[1] as $row) {
            $record = $this->parseRow($row);
            if ($record) {
                $records[] = $record;
            }
        }

        return $records;
    }

    protected function parseRow(string $row): ?array
    {
        // Simple parser for: id, user_id, tanggal, kegiatan, volume, satuan
        $fields = $this->splitFields($row);

        if (count($fields) < 6) {
            return null;
        }

        $tanggal = trim($fields[2], " '\"");

        if (!$this->isValidDate($tanggal)) {
            return null;
        }

        return [
            'user_id' => (int) trim($fields[1]),
            'tanggal' => $tanggal,
            'k' => $this->unescape(trim($fields[3], " '\"")),
            'v' => (int) (trim($fields[4]) ?: 1),
            's' => $this->unescape(trim($fields[5], " '\"")),
        ];
    }

    protected function splitFields(string $row): array
    {
        $fields = [];
        $current = '';
        $inQuote = false;
        $quoteChar = '';

        for ($i = 0; $i < strlen($row); $i++) {
            $char = $row[$i];

            if (($char === "'" || $char === '"') && ($i === 0 || $row[$i - 1] !== '\\')) {
                if (!$inQuote) {
                    $inQuote = true;
                    $quoteChar = $char;
                } elseif ($char === $quoteChar) {
                    $inQuote = false;
                }
                $current .= $char;
            } elseif ($char === ',' && !$inQuote) {
                $fields[] = $current;
                $current = '';
            } else {
                $current .= $char;
            }
        }

        if ($current !== '') {
            $fields[] = $current;
        }

        return $fields;
    }

    protected function isValidDate(string $date): bool
    {
        return !empty($date) && $date !== '0000-00-00' && (bool) preg_match('/^\d{4}-\d{2}-\d{2}$/', $date);
    }

    protected function unescape(string $value): string
    {
        $value = trim($value, " '\"");
        $value = str_replace(["\\'", '\\"', '\\\\'], ["'", '"', '\\'], $value);
        return $value;
    }
}
