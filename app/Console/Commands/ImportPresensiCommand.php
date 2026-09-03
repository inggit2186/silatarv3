<?php

namespace App\Console\Commands;

use App\Services\PresensiImportService;
use App\Models\Department;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class ImportPresensiCommand extends Command
{
    protected $signature = 'presensi:import
                            {--path= : Path to folder containing Excel files. Default: public/uploads/pusaka/presensi}
                            {--rollback= : Batch ID to rollback import}
                            {--history : Show import history}
                            {--dry-run : Run validation only, don\'t actually import}
                            {--force : Skip confirmation prompt}
                            {--keep-files : Keep Excel files after import (default: delete after import)}';

    protected $description = 'Import presensi data from Excel files in bulk';

    protected PresensiImportService $importService;

    public function __construct(PresensiImportService $importService)
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
        $this->info('║           IMPORT PRESANSI EXCEL - BULK IMPORT             ║');
        $this->info('╚════════════════════════════════════════════════════════════╝');
        $this->newLine();

        // Get import path
        $importPath = $this->option('path') ?? 'public/uploads/pusaka/presensi';
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

        // Process each file
        $totalImported = 0;
        $totalSkipped = 0;
        $totalInvalid = 0;
        $importResults = [];
        $filesToDelete = [];

        foreach ($files as $file) {
            $result = $this->processFile($file);
            $importResults[] = $result;
            $totalImported += $result['imported'];
            $totalSkipped += $result['skipped'];
            $totalInvalid += $result['invalid'];

            // Mark file for deletion if import was successful (no errors)
            // Hapus file jika tidak ada error, meskipun semua data duplikat
            if (empty($result['errors'])) {
                $filesToDelete[] = $file;
            }
        }

        // Print summary
        $this->printSummary($importResults, $totalImported, $totalSkipped, $totalInvalid);

        // Delete files after successful import (unless --keep-files is specified)
        if (!$this->option('keep-files') && !empty($filesToDelete)) {
            $this->newLine();
            $this->info('🗑️  Menghapus file Excel yang sudah berhasil diimport...');

            foreach ($filesToDelete as $file) {
                if (file_exists($file)) {
                    unlink($file);
                    $this->line("   🗑️  " . basename($file));
                }
            }

            $this->info("✅ Berhasil menghapus " . count($filesToDelete) . " file");
        }

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

    protected function getDepartmentMapping(): array
    {
        // Load all departments
        $departments = Department::all()->keyBy('nama');

        $mapping = [];

        // Manual mapping based on filename patterns
        $filenameToDept = [
            'batipuh' => 'KUA Kecamatan Batipuh',
            'limakaum' => 'KUA Kecamatan Lima Kaum',
            'parihangan' => 'KUA Kecamatan Pariangan',
            'sungai tarab' => 'KUA Kecamatan Sungai Tarab',
            'tanah selatan' => 'KUA Kecamatan Tanah Selatan',
        ];

        foreach ($filenameToDept as $pattern => $deptName) {
            if (isset($departments[$deptName])) {
                $mapping[strtolower($pattern)] = $departments[$deptName]->id;
            }
        }

        return $mapping;
    }

    protected function processFile(string $filePath): array
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
                'errors' => [$parsed['error']],
            ];
        }

        $this->info("📊 Total baris: " . $parsed['total_rows']);

        // Validate (dept_id akan diambil dari users berdasarkan NIP)
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
                'errors' => [],
            ];
        }

        // Confirm import
        if (!$this->option('force')) {
            $this->newLine();
            if (!$this->confirm("Import {$validated['valid_count']} data yang valid ke database?")) {
                $this->warn("⏭️  Dibatalkan oleh user");
                return [
                    'file' => $filename,
                    'imported' => 0,
                    'skipped' => 0,
                    'invalid' => $validated['invalid_count'],
                    'errors' => [],
                ];
            }
        }

        // Import
        $this->newLine();
        $this->info("📥 Mengimport data...");

        $result = $this->importService->importToDatabase($validated, auth()->id() ?? 1);

        if ($result['success']) {
            $this->info("✅ {$result['message']}");
            if ($result['skipped_count'] > 0) {
                $this->warn("⏭️  {$result['skipped_count']} data dilewati (duplikat)");
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
            'errors' => $result['success'] ? [] : [$result['error']],
        ];
    }

    protected function detectDepartmentFromFilename(string $filename, array $departmentMapping): ?int
    {
        $filenameLower = strtolower($filename);

        foreach ($departmentMapping as $pattern => $deptId) {
            if (strpos($filenameLower, $pattern) !== false) {
                return $deptId;
            }
        }

        return null;
    }

    protected function askDepartment(array $departmentMapping): ?int
    {
        $departments = Department::orderBy('nama')->get();

        $this->newLine();
        $this->info("📋 Daftar Unit Kerja:");

        $deptList = [];
        $index = 1;

        foreach ($departments as $dept) {
            $this->line("   {$index}. {$dept->nama} (ID: {$dept->id})");
            $deptList[$index] = $dept->id;
            $index++;
        }

        $this->newLine();
        $choice = $this->ask('Pilih nomor unit kerja', null);

        if ($choice && isset($deptList[$choice])) {
            return $deptList[$choice];
        }

        return null;
    }

    protected function handleRollback(): int
    {
        $batchId = $this->option('rollback');

        $this->info("╔════════════════════════════════════════════════════════════╗");
        $this->info("║            ROLLBACK IMPORT PRESENSI                       ║");
        $this->info("╚════════════════════════════════════════════════════════════╝");
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
        $this->info("╔════════════════════════════════════════════════════════════╗");
        $this->info("║            RIWAYAT IMPORT PRESENSI                        ║");
        $this->info("╚════════════════════════════════════════════════════════════╝");
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
        $this->info("💡 Untuk rollback, gunakan: php artisan presensi:import --rollback={batch_id}");

        return Command::SUCCESS;
    }

    protected function printSummary(array $results, int $totalImported, int $totalSkipped, int $totalInvalid): void
    {
        $this->newLine();
        $this->info('╔════════════════════════════════════════════════════════════╗');
        $this->info('║                  RINGKASAN IMPORT                         ║');
        $this->info('╚════════════════════════════════════════════════════════════╝');
        $this->newLine();

        $this->info("📊 Total File: " . count($results));
        $this->info("✅ Total Import: {$totalImported} data");
        $this->info("⏭️  Total Skip (Duplikat): {$totalSkipped} data");
        $this->info("❌ Total Invalid: {$totalInvalid} data");
        $this->newLine();

        if ($totalImported > 0) {
            $this->info("✨ Import selesai! Data sudah tersimpan di database.");
        } else {
            $this->warn("⚠️  Tidak ada data yang diimport");
        }

        $this->newLine();
        $this->info("💡 Lihat riwayat: php artisan presensi:import --history");
        $this->info("💡 Rollback: php artisan presensi:import --rollback={batch_id}");
    }
}
