<?php

namespace App\Console\Commands;

use App\Services\TukinImportService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ImportTukinCommand extends Command
{
    protected $signature = 'tukin:import
                            {--path= : Path to folder containing Excel files. Default: public/uploads/pusaka/tukin}
                            {--rollback= : Batch ID to rollback import}
                            {--history : Show import history}
                            {--dry-run : Run validation only, don\'t actually import}
                            {--force : Skip confirmation prompt}
                            {--keep-files : Keep Excel files after import (default: delete after import)}';

    protected $description = 'Import tukin data from Excel files in bulk';

    protected TukinImportService $importService;

    public function __construct(TukinImportService $importService)
    {
        parent::__construct();
        $this->importService = $importService;
    }

    public function handle(): int
    {
        // Handle rollback
        if ($this->option('rollback')) {
            return $this->handleRollback();
        }

        // Handle history
        if ($this->option('history')) {
            return $this->handleHistory();
        }

        // Handle import
        return $this->handleImport();
    }

    protected function handleImport(): int
    {
        $this->info('╔════════════════════════════════════════════════════════════╗');
        $this->info('║           IMPORT TUKIN EXCEL - BULK IMPORT                ║');
        $this->info('╚════════════════════════════════════════════════════════════╝');
        $this->newLine();

        // Get import path
        $importPath = $this->option('path') ?? 'public/uploads/pusaka/tukin';
        $fullPath = base_path($importPath);

        if (!File::exists($fullPath)) {
            $this->error("❌ Folder tidak ditemukan: {$fullPath}");
            $this->newLine();
            $this->info("💡 Pastikan folder ada atau gunakan --path untuk menentukan lokasi");
            return Command::FAILURE;
        }

        // Scan Excel files
        $files = $this->scanExcelFiles($fullPath);

        if (empty($files)) {
            $this->warn("⚠️  Tidak ditemukan file Excel (.xlsx) di folder: {$importPath}");
            $this->newLine();
            $this->info("💡 Format file yang diterima: .xlsx");
            return Command::SUCCESS;
        }

        $this->info("📂 Ditemukan " . count($files) . " file Excel:");
        foreach ($files as $file) {
            $this->line("   📄 " . basename($file));
        }
        $this->newLine();

        // Single confirmation before processing all files
        if (!$this->option('force') && !$this->option('dry-run')) {
            if (!$this->confirm("Import semua file ini ke database?")) {
                $this->warn("⏭️  Dibatalkan oleh user");
                return Command::SUCCESS;
            }
        }

        // Process each file
        $totalImported = 0;
        $totalSkipped = 0;
        $totalInvalid = 0;
        $totalUpdatedGolongan = 0;
        $importResults = [];

        foreach ($files as $file) {
            // Free memory before processing next file
            gc_collect_cycles();

            $result = $this->processFile($file, true);
            $importResults[] = $result;
            $totalImported += $result['imported'];
            $totalSkipped += $result['skipped'];
            $totalInvalid += $result['invalid'];
            $totalUpdatedGolongan += $result['updated_golongan'] ?? 0;

            // Delete file immediately after successful import (unless --keep-files or dry-run)
            if (!$this->option('keep-files') && !$this->option('dry-run') && empty($result['errors']) && file_exists($file)) {
                unlink($file);
                $this->line("   🗑️  File dihapus: " . basename($file));
            }
        }

        // Print summary
        $this->printSummary($importResults, $totalImported, $totalSkipped, $totalInvalid, $totalUpdatedGolongan);

        return Command::SUCCESS;
    }

    protected function scanExcelFiles(string $path): array
    {
        $files = [];

        $excelFiles = glob($path . '/*.xlsx');

        if ($excelFiles) {
            foreach ($excelFiles as $file) {
                // Skip hidden files, temp files, and Excel lock files
                $basename = basename($file);
                if (strpos($basename, '.') === 0 || strpos($basename, '~$') === 0) {
                    continue;
                }
                $files[] = $file;
            }
        }

        return $files;
    }

    protected function processFile(string $filePath, bool $skipConfirmation = false): array
    {
        $filename = basename($filePath);
        $this->line("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        $this->info("📄 Processing: {$filename}");
        $this->newLine();

        // Parse Excel
        $parsed = $this->importService->parseExcel($filePath);

        if (!$parsed['success']) {
            $this->error("❌ Gagal membaca file: {$parsed['error']}");
            return [
                'file' => $filename,
                'imported' => 0,
                'skipped' => 0,
                'invalid' => 0,
                'updated_golongan' => 0,
                'errors' => [$parsed['error']],
            ];
        }

        $this->info("📊 Total baris: " . $parsed['total_rows']);

        // Validate
        $validated = $this->importService->validateData($parsed['data']);

        $this->info("✅ Valid: {$validated['valid_count']} baris");
        if ($validated['invalid_count'] > 0) {
            $this->warn("⚠️  Invalid: {$validated['invalid_count']} baris");
            $this->newLine();

            // Show first few errors
            $errors = array_slice($validated['invalid_rows'], 0, 5);
            foreach ($errors as $error) {
                $this->line("   Row {$error['row']}: " . implode(', ', $error['errors']));
            }

            if ($validated['invalid_count'] > 5) {
                $this->line("   ... dan " . ($validated['invalid_count'] - 5) . " error lainnya");
            }
        }

        // If dry-run, stop here
        if ($this->option('dry-run')) {
            $this->newLine();
            $this->info("🔍 [DRY RUN] Tidak ada data yang diimport");
            return [
                'file' => $filename,
                'imported' => 0,
                'skipped' => 0,
                'invalid' => $validated['invalid_count'],
                'updated_golongan' => 0,
                'errors' => [],
            ];
        }

        // Import
        $this->newLine();
        $this->info("📥 Mengimport data...");

        $result = $this->importService->importToDatabase($validated, auth()->id() ?? 1);

        if ($result['success']) {
            $this->info("✅ {$result['message']}");
            if ($result['updated_golongan_count'] > 0) {
                $this->warn("🔄 {$result['updated_golongan_count']} golongan user diupdate");
            }
        } else {
            $this->error("❌ {$result['error']}");
        }

        $this->newLine();

        return [
            'file' => $filename,
            'imported' => $result['imported_count'] ?? 0,
            'skipped' => $result['skipped_count'] ?? 0,
            'invalid' => $validated['invalid_count'],
            'updated_golongan' => $result['updated_golongan_count'] ?? 0,
            'errors' => $result['success'] ? [] : [$result['error']],
        ];
    }

    protected function handleRollback(): int
    {
        $batchId = $this->option('rollback');

        $this->info('╔════════════════════════════════════════════════════════════╗');
        $this->info('║            ROLLBACK IMPORT TUKIN                          ║');
        $this->info('╚════════════════════════════════════════════════════════════╝');
        $this->newLine();

        $this->warn("⚠️  Anda akan melakukan rollback untuk Batch ID: {$batchId}");
        $this->newLine();

        if (!$this->option('force')) {
            if (!$this->confirm("Apakah Anda yakin? Semua data dari batch ini akan dihapus PERMANEN!")) {
                $this->info("❌ Dibatalkan oleh user");
                return Command::SUCCESS;
            }
        }

        $result = $this->importService->rollbackImport($batchId);

        if ($result['success']) {
            $this->info("✅ {$result['message']}");
            return Command::SUCCESS;
        } else {
            $this->error("❌ {$result['error']}");
            return Command::FAILURE;
        }
    }

    protected function handleHistory(): int
    {
        $this->info('╔════════════════════════════════════════════════════════════╗');
        $this->info('║            RIWAYAT IMPORT TUKIN                           ║');
        $this->info('╚════════════════════════════════════════════════════════════╝');
        $this->newLine();

        $history = $this->importService->getImportHistory();

        if (empty($history)) {
            $this->warn("⚠️  Belum ada riwayat import");
            return Command::SUCCESS;
        }

        $this->info("📊 Total import: " . count($history));
        $this->newLine();

        // Table header
        $this->table(
            ['Batch ID', 'Tanggal', 'Imported By', 'Total Record'],
            array_map(function ($item) {
                return [
                    $item['batch_id'],
                    $item['imported_at'],
                    $item['imported_by'],
                    $item['total_records'],
                ];
            }, $history)
        );

        $this->newLine();
        $this->info("💡 Untuk rollback, gunakan: php artisan tukin:import --rollback={batch_id}");

        return Command::SUCCESS;
    }

    protected function printSummary(array $results, int $totalImported, int $totalSkipped, int $totalInvalid, int $totalUpdatedGolongan): void
    {
        $this->newLine();
        $this->info('╔════════════════════════════════════════════════════════════╗');
        $this->info('║                  RINGKASAN IMPORT                         ║');
        $this->info('╚════════════════════════════════════════════════════════════╝');
        $this->newLine();

        $this->info("📊 Total File: " . count($results));
        $this->info("✅ Total Import: {$totalImported} data");
        $this->info("🔄 Golongan Diupdate: {$totalUpdatedGolongan} user");
        $this->info("❌ Total Invalid: {$totalInvalid} data");
        $this->newLine();

        if ($totalImported > 0) {
            $this->info("✨ Import selesai! Data sudah tersimpan di database.");
        } else {
            $this->warn("⚠️  Tidak ada data yang diimport");
        }

        $this->newLine();
        $this->info("💡 Lihat riwayat: php artisan tukin:import --history");
        $this->info("💡 Rollback: php artisan tukin:import --rollback={batch_id}");
    }
}
