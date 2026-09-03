<?php

namespace App\Console\Commands;

use App\Exports\PresensiDetailExport;
use App\Exports\PresensiAbsensiExport;
use App\Exports\PresensiAbsensiHorizontalExport;
use App\Exports\PresensiMultiUserHorizontalExport;
use App\Exports\PresensiDetailHorizontalExport;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ExportPresensiCommand extends Command
{
    protected $signature = 'presensi:export
                            {--user= : Export untuk user tertentu (user_id). Jika tidak diisi, export semua user}
                            {--dept= : Export untuk dept_id tertentu (unit kerja). Jika tidak diisi, export per dept_id}
                            {--month= : Bulan export (1-12). Default: bulan sekarang}
                            {--year= : Tahun export. Default: tahun sekarang}
                            {--type=horizontal : Tipe export (horizontal|detail-horizontal). Default: horizontal}
                            {--output=exports/presensi : Folder output untuk file excel}
                            {--all : Export semua dept_id dalam 1 command (per dept_id)}';

    protected $description = 'Export presensi data to Excel files';

    public function handle(): int
    {
        $this->info('╔════════════════════════════════════════════════════════════╗');
        $this->info('║           EXPORT PRESENSI EXCEL                          ║');
        $this->info('╚════════════════════════════════════════════════════════════╝');
        $this->newLine();

        // Get parameters
        $month = $this->option('month') ?? (int) date('m');
        $year = $this->option('year') ?? (int) date('Y');
        $userId = $this->option('user');
        $deptId = $this->option('dept');
        $type = $this->option('type') ?? 'horizontal';
        $outputPath = $this->option('output');
        $exportAll = $this->option('all');

        // Validate month and year
        if ($month < 1 || $month > 12) {
            $this->error("❌ Bulan tidak valid: {$month}. Gunakan 1-12.");
            return Command::FAILURE;
        }

        if ($year < 2020 || $year > 2030) {
            $this->error("❌ Tahun tidak valid: {$year}.");
            return Command::FAILURE;
        }

        // Get departments to export
        $departments = $this->getDepartmentsToExport($deptId, $exportAll);

        if ($departments->isEmpty()) {
            $this->warn("⚠️  Tidak ada unit kerja yang ditemukan untuk diexport.");
            return Command::SUCCESS;
        }

        $this->info("📊 Parameter Export:");
        $this->info("   Bulan: {$this->getMonthName($month)} {$year}");
        $this->info("   Total Unit Kerja: {$departments->count()}");
        $this->info("   Tipe: " . ucfirst($type));
        $this->newLine();

        // Create output directory
        $fullOutputPath = storage_path("app/{$outputPath}");
        if (!file_exists($fullOutputPath)) {
            mkdir($fullOutputPath, 0755, true);
        }

        // Create year directory
        $yearDir = "{$fullOutputPath}/{$year}";
        if (!file_exists($yearDir)) {
            mkdir($yearDir, 0755, true);
        }

        // Create month directory
        $monthDir = "{$yearDir}/{$month}";
        if (!file_exists($monthDir)) {
            mkdir($monthDir, 0755, true);
        }

        // Process each department
        $exportedCount = 0;
        $errors = [];

        foreach ($departments as $dept) {
            $this->line("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
            $this->info("🏢 Unit Kerja: {$dept->nama} (ID: {$dept->id})");

            // Get users in this department
            $users = $this->getUsersByDept($dept->id);

            if ($users->isEmpty()) {
                $this->warn("   ⚠️  Tidak ada user di unit kerja ini");
                continue;
            }

            // Count users with and without presensi data (dengan logic yang benar)
            $nips = $users->pluck('nomor_induk')->toArray();
            $usersWithPresensi = DB::table('ktd_presensi')
                ->whereIn('user_nip', $nips)
                ->whereYear('tanggal', $year)
                ->whereMonth('tanggal', $month)
                ->where(function ($q) {
                    $q->whereNotNull('m_absen')->orWhereNotNull('p_absen');
                })
                ->whereNull('status')
                ->distinct()
                ->count('user_nip');

            $usersWithoutPresensi = $users->count() - $usersWithPresensi;

            $this->info("   👥 Total User: {$users->count()} ({$usersWithPresensi} ada presensi, {$usersWithoutPresensi} tanpa presensi)");

            try {
                // Export per dept based on type
                if ($type === 'detail-horizontal') {
                    $this->exportDeptDetailHorizontal($users, $dept, $month, $year, $monthDir);
                } else {
                    $this->exportDeptHorizontal($users, $dept, $month, $year, $monthDir);
                }
                $exportedCount++;
                $this->info("   ✅ Berhasil");
            } catch (\Exception $e) {
                $errors[] = [
                    'dept' => $dept->nama,
                    'error' => $e->getMessage(),
                ];
                $this->error("   ❌ Gagal: {$e->getMessage()}");
            }

            $this->newLine();
        }

        // Print summary
        $this->printSummary($exportedCount, $errors, $fullOutputPath, $month, $year);

        return Command::SUCCESS;
    }

    protected function getDepartmentsToExport(?int $deptId, bool $exportAll): \Illuminate\Support\Collection
    {
        // Pendekatan efisien: Ambil dept_id yang ada di ktd_presensi
        // Tidak perlu ambil semua users dulu
        $query = DB::table('ktd_presensi')
            ->join('users', 'ktd_presensi.user_nip', '=', 'users.nomor_induk')
            ->whereNotNull('users.dept_id')
            ->select('users.dept_id')
            ->groupBy('users.dept_id');

        if (!$exportAll && $deptId) {
            $query->where('users.dept_id', $deptId);
        }

        $deptIds = $query->pluck('dept_id')->toArray();

        if (empty($deptIds)) {
            return collect();
        }

        // Get department details
        return DB::table('ktd_department')
            ->whereIn('id', $deptIds)
            ->orderBy('nama')
            ->get();
    }

    protected function getUsersByDept(int $deptId): \Illuminate\Support\Collection
    {
        // Ambil SEMUA users di dept_id tertentu
        // (termasuk yang tidak ada data presensinya)
        return DB::table('users')
            ->where('dept_id', $deptId)
            ->whereNotNull('nomor_induk')
            ->where('nomor_induk', '!=', '')
            ->select('id', 'name', 'nomor_induk', 'dept_id')
            ->orderBy('name')
            ->get();
    }

    protected function exportDetail($user, int $month, int $year, string $outputPath): void
    {
        $filename = "presensi_detail_{$user->nomor_induk}_{$year}_{$month}.xlsx";
        $fullPath = "{$outputPath}/{$filename}";

        // Create export instance
        $export = new PresensiDetailExport($user->id, $month, $year);

        // Write directly to file using PhpSpreadsheet
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Get the export data
        $exportData = $export->sheets();
        $sheetExport = reset($exportData);

        // Build the sheet manually
        $this->buildDetailSheet($sheet, $sheetExport, $user, $month, $year);

        // Save to file
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save($fullPath);

        $this->line("   📄 Detail: {$filename}");
    }

    protected function exportAbsensi($user, int $month, int $year, string $outputPath): void
    {
        $filename = "presensi_absensi_{$user->nomor_induk}_{$year}_{$month}.xlsx";
        $fullPath = "{$outputPath}/{$filename}";

        // Create export instance
        $export = new PresensiAbsensiExport($user->id, $month, $year);

        // Write directly to file using PhpSpreadsheet
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Build the sheet manually
        $this->buildAbsensiSheet($sheet, $export, $user, $month, $year);

        // Save to file
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save($fullPath);

        $this->line("   📄 Absensi: {$filename}");
    }

    protected function exportAbsensiHorizontal($user, int $month, int $year, string $outputPath): void
    {
        $filename = "presensi_absensi_horizontal_{$user->nomor_induk}_{$year}_{$month}.xlsx";
        $fullPath = "{$outputPath}/{$filename}";

        // Create export instance
        $export = new PresensiAbsensiHorizontalExport($user->id, $month, $year);

        // Write directly to file using PhpSpreadsheet
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Build the sheet manually
        $this->buildAbsensiHorizontalSheet($sheet, $export, $user, $month, $year);

        // Save to file
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save($fullPath);

        $this->line("   📄 Absensi Horizontal: {$filename}");
    }

    protected function exportDeptHorizontal($users, $dept, int $month, int $year, string $outputPath): void
    {
        // Clean dept name for filename
        $cleanName = preg_replace('/[^a-zA-Z0-9]/', '_', $dept->nama);
        $filename = "presensi_{$cleanName}_{$year}_{$month}.xlsx";
        $fullPath = "{$outputPath}/{$filename}";

        // Create export instance
        $export = new PresensiMultiUserHorizontalExport($users, $month, $year);

        // Write directly to file using PhpSpreadsheet
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Build the sheet manually
        $this->buildDeptHorizontalSheet($sheet, $export, $users, $dept, $month, $year);

        // Save to file
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save($fullPath);

        $this->line("   📄 File: {$filename}");
    }

    protected function exportDeptDetailHorizontal($users, $dept, int $month, int $year, string $outputPath): void
    {
        // Clean dept name for filename
        $cleanName = preg_replace('/[^a-zA-Z0-9]/', '_', $dept->nama);
        $filename = "presensi_detail_{$cleanName}_{$year}_{$month}.xlsx";
        $fullPath = "{$outputPath}/{$filename}";

        // Create export instance
        $export = new PresensiDetailHorizontalExport($users, $month, $year);

        // Write directly to file using PhpSpreadsheet
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Build the sheet manually
        $this->buildDeptDetailHorizontalSheet($sheet, $export, $users, $dept, $month, $year);

        // Save to file
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save($fullPath);

        $this->line("   📄 File: {$filename}");
    }

    protected function exportMultiHorizontal($users, int $month, int $year, string $outputPath): void
    {
        $filename = "presensi_multi_horizontal_{$year}_{$month}.xlsx";
        $fullPath = "{$outputPath}/{$filename}";

        // Create export instance
        $export = new PresensiMultiUserHorizontalExport($users, $month, $year);

        // Write directly to file using PhpSpreadsheet
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Build the sheet manually
        $this->buildMultiHorizontalSheet($sheet, $export, $users, $month, $year);

        // Save to file
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save($fullPath);

        $this->line("   📄 Multi-User Horizontal: {$filename}");
    }

    protected function buildDetailSheet($sheet, $sheetExport, $user, $month, $year): void
    {
        // Title
        $sheet->mergeCells('A1:I1');
        $sheet->setCellValue('A1', 'REKAP PRESENSI BULANAN');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells('A2:I2');
        $sheet->setCellValue('A2', $user->name . ' - NIP: ' . $user->nomor_induk);
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(11);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells('A3:I3');
        $sheet->setCellValue('A3', 'Bulan: ' . $this->getMonthName($month) . ' ' . $year);
        $sheet->getStyle('A3')->getFont()->setBold(true)->setSize(11);
        $sheet->getStyle('A3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Headers
        $headers = ['No', 'Tanggal', 'Hari', 'Jam Masuk', 'Telat (Menit)', 'Jam Pulang', 'PSW (Menit)', 'Status', 'Keterangan'];
        foreach ($headers as $col => $header) {
            $column = chr(65 + $col);
            $sheet->setCellValue("{$column}5", $header);
        }

        // Style headers
        $sheet->getStyle('A5:I5')->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
        $sheet->getStyle('A5:I5')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('2E86AB');
        $sheet->getStyle('A5:I5')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Get data
        $data = $sheetExport->collection()->toArray();
        $rowNum = 6;
        $no = 1;

        foreach ($data as $row) {
            $date = new \DateTime($row->tanggal);
            $hari = [
                'Sunday' => 'Minggu',
                'Monday' => 'Senin',
                'Tuesday' => 'Selasa',
                'Wednesday' => 'Rabu',
                'Thursday' => 'Kamis',
                'Friday' => 'Jumat',
                'Saturday' => 'Sabtu',
            ];
            $dayName = $hari[$date->format('l')] ?? $date->format('l');

            $sheet->setCellValue("A{$rowNum}", $no++);
            $sheet->setCellValue("B{$rowNum}", $date->format('d/m/Y'));
            $sheet->setCellValue("C{$rowNum}", $dayName);
            $sheet->setCellValue("D{$rowNum}", $row->m_absen ?: '-');
            $sheet->setCellValue("E{$rowNum}", $row->m_diff ?: '-');
            $sheet->setCellValue("F{$rowNum}", $row->p_absen ?: '-');
            $sheet->setCellValue("G{$rowNum}", $row->p_diff ?: '-');
            $sheet->setCellValue("H{$rowNum}", $row->status ?: '-');
            $sheet->setCellValue("I{$rowNum}", $row->keterangan ?: '-');

            $rowNum++;
        }

        // Auto size columns
        for ($col = 'A'; $col <= 'I'; $col++) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Freeze pane
        $sheet->freezePane('A6');
    }

    protected function buildAbsensiSheet($sheet, $export, $user, $month, $year): void
    {
        // Title
        $sheet->mergeCells('A1:E1');
        $sheet->setCellValue('A1', 'REKAP ABSENSI HARIAN');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells('A2:E2');
        $sheet->setCellValue('A2', $user->name . ' - NIP: ' . $user->nomor_induk);
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(11);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells('A3:E3');
        $sheet->setCellValue('A3', 'Bulan: ' . $this->getMonthName($month) . ' ' . $year);
        $sheet->getStyle('A3')->getFont()->setBold(true)->setSize(11);
        $sheet->getStyle('A3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Headers
        $headers = ['Tanggal', 'Hari', 'Absen Masuk', 'Absen Pulang', 'Status'];
        foreach ($headers as $col => $header) {
            $column = chr(65 + $col);
            $sheet->setCellValue("{$column}5", $header);
        }

        // Style headers
        $sheet->getStyle('A5:E5')->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
        $sheet->getStyle('A5:E5')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('28A745');
        $sheet->getStyle('A5:E5')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Get data
        $data = $export->collection()->toArray();
        $rowNum = 6;

        foreach ($data as $row) {
            $sheet->setCellValue("A{$rowNum}", $row['date']);
            $sheet->setCellValue("B{$rowNum}", $row['day_name']);
            $sheet->setCellValue("C{$rowNum}", $row['has_masuk']);
            $sheet->setCellValue("D{$rowNum}", $row['has_pulang']);
            $sheet->setCellValue("E{$rowNum}", $row['status']);

            // Highlight weekends
            if ($row['is_weekend']) {
                $sheet->getStyle("A{$rowNum}:E{$rowNum}")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('FFF3CD');
            }

            $rowNum++;
        }

        // Set column widths
        $sheet->getColumnDimension('A')->setWidth(15);
        $sheet->getColumnDimension('B')->setWidth(15);
        $sheet->getColumnDimension('C')->setWidth(15);
        $sheet->getColumnDimension('D')->setWidth(15);
        $sheet->getColumnDimension('E')->setWidth(20);

        // Freeze pane
        $sheet->freezePane('A6');
    }

    protected function buildAbsensiHorizontalSheet($sheet, $export, $user, $month, $year): void
    {
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        // Helper function to get column name
        $getColumnName = function ($index) {
            $column = '';
            while ($index > 0) {
                $index--;
                $column = chr(65 + ($index % 26)) . $column;
                $index = (int) ($index / 26);
            }
            return $column;
        };

        // Title
        $lastCol = $getColumnName($daysInMonth + 2); // +2 for Hari and Total columns
        $sheet->mergeCells("A1:{$lastCol}1");
        $sheet->setCellValue('A1', 'REKAP ABSENSI BULANAN - FORMAT HORIZONTAL');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells("A2:{$lastCol}2");
        $sheet->setCellValue('A2', $user->name . ' - NIP: ' . $user->nomor_induk);
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(11);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells("A3:{$lastCol}3");
        $sheet->setCellValue('A3', 'Bulan: ' . $this->getMonthName($month) . ' ' . $year);
        $sheet->getStyle('A3')->getFont()->setBold(true)->setSize(11);
        $sheet->getStyle('A3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Headers: Hari, 1, 2, 3, ..., 31, Total
        $sheet->setCellValue('A5', 'Hari');
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $col = $getColumnName($day + 1); // +1 karena kolom A adalah Hari
            $sheet->setCellValue("{$col}5", $day);
        }
        $totalCol = $getColumnName($daysInMonth + 2);
        $sheet->setCellValue("{$totalCol}5", 'Total');

        // Style headers
        $sheet->getStyle("A5:{$totalCol}5")->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
        $sheet->getStyle("A5:{$totalCol}5")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('28A745');
        $sheet->getStyle("A5:{$totalCol}5")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Get data
        $data = $export->collection()->toArray();
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
        $rowNum = 6;

        foreach ($data as $row) {
            $sheet->setCellValue("A{$rowNum}", $row['day_name']);

            $total = 0;
            for ($day = 1; $day <= $daysInMonth; $day++) {
                $col = $getColumnName($day + 1);
                $value = $row['values'][$day] ?? null;
                $sheet->setCellValue("{$col}{$rowNum}", $value ?? '');

                if ($value === 1) {
                    $total++;
                }
            }

            $sheet->setCellValue("{$totalCol}{$rowNum}", $total);

            // Highlight weekends (Sabtu=5, Minggu=6)
            if (in_array($row['day_name'], ['Sabtu', 'Minggu'])) {
                $sheet->getStyle("A{$rowNum}:{$totalCol}{$rowNum}")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('FFF3CD');
            }

            $rowNum++;
        }

        // Style data area
        $sheet->getStyle("A6:{$totalCol}" . ($rowNum - 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("A6:{$totalCol}" . ($rowNum - 1))->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        // Auto filter
        $sheet->setAutoFilter("A5:{$totalCol}5");

        // Freeze pane
        $sheet->freezePane('B6');

        // Column widths
        $sheet->getColumnDimension('A')->setWidth(12);
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $sheet->getColumnDimension($getColumnName($day + 1))->setWidth(6);
        }
        $sheet->getColumnDimension($totalCol)->setWidth(8);
    }

    protected function buildMultiHorizontalSheet($sheet, $export, $users, $month, $year): void
    {
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        // Helper function to get column name
        $getColumnName = function ($index) {
            $column = '';
            while ($index > 0) {
                $index--;
                $column = chr(65 + ($index % 26)) . $column;
                $index = (int) ($index / 26);
            }
            return $column;
        };

        // Title
        $lastCol = $getColumnName($daysInMonth + 3); // +3 for NIP, Nama, Total
        $sheet->mergeCells("A1:{$lastCol}1");
        $sheet->setCellValue('A1', 'REKAP ABSENSI BULANAN - SEMUA USER');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells("A2:{$lastCol}2");
        $sheet->setCellValue('A2', 'Bulan: ' . $this->getMonthName($month) . ' ' . $year);
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(11);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells("A3:{$lastCol}3");
        $sheet->setCellValue('A3', 'Total: ' . $users->count() . ' User');
        $sheet->getStyle('A3')->getFont()->setBold(true)->setSize(11);
        $sheet->getStyle('A3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Headers: NIP, Nama, 1, 2, ..., 31, Total
        $sheet->setCellValue('A5', 'NIP');
        $sheet->setCellValue('B5', 'Nama');
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $col = $getColumnName($day + 2); // +2 karena kolom A dan B sudah terpakai
            $sheet->setCellValue("{$col}5", $day);
        }
        $totalCol = $getColumnName($daysInMonth + 2);
        $sheet->setCellValue("{$totalCol}5", 'Total');

        // Style headers
        $sheet->getStyle("A5:{$totalCol}5")->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
        $sheet->getStyle("A5:{$totalCol}5")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('2E86AB');
        $sheet->getStyle("A5:{$totalCol}5")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Get data
        $data = $export->collection()->toArray();
        $rowNum = 6;

        foreach ($data as $row) {
            $sheet->setCellValue("A{$rowNum}", $row->nomor_induk);
            $sheet->setCellValue("B{$rowNum}", $row->name);

            // Get presensi for this user
            $userPresensi = $export->presensiData->get($row->nomor_induk, collect());
            $total = 0;

            for ($day = 1; $day <= $daysInMonth; $day++) {
                $col = $getColumnName($day + 2);
                // Dianggap hadir jika ada m_absen atau p_absen, dengan status null
                $hasPresensi = isset($userPresensi[$day]) &&
                    (!empty($userPresensi[$day]->m_absen) || !empty($userPresensi[$day]->p_absen)) &&
                    ($userPresensi[$day]->status === null);

                $sheet->setCellValue("{$col}{$rowNum}", $hasPresensi ? 1 : '');
                if ($hasPresensi) {
                    $total++;
                }
            }

            $sheet->setCellValue("{$totalCol}{$rowNum}", $total);
            $rowNum++;
        }

        // Style data area
        $sheet->getStyle("A6:{$totalCol}" . ($rowNum - 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("A6:{$totalCol}" . ($rowNum - 1))->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        // Auto filter
        $sheet->setAutoFilter("A5:{$totalCol}5");

        // Freeze pane
        $sheet->freezePane('C6');

        // Column widths
        $sheet->getColumnDimension('A')->setWidth(18); // NIP
        $sheet->getColumnDimension('B')->setWidth(25); // Nama
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $col = $getColumnName($day + 2);
            $sheet->getColumnDimension($col)->setWidth(6);
        }
        $sheet->getColumnDimension($totalCol)->setWidth(8);
    }

    protected function buildDeptHorizontalSheet($sheet, $export, $users, $dept, $month, $year): void
    {
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        // Helper function to get column name
        $getColumnName = function ($index) {
            $column = '';
            while ($index > 0) {
                $index--;
                $column = chr(65 + ($index % 26)) . $column;
                $index = (int) ($index / 26);
            }
            return $column;
        };

        // Title
        $lastCol = $getColumnName($daysInMonth + 3); // +3 for NIP, Nama, Total
        $sheet->mergeCells("A1:{$lastCol}1");
        $sheet->setCellValue('A1', 'REKAP ABSENSI BULANAN - ' . strtoupper($dept->nama));
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells("A2:{$lastCol}2");
        $sheet->setCellValue('A2', 'Bulan: ' . $this->getMonthName($month) . ' ' . $year);
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(11);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells("A3:{$lastCol}3");
        $sheet->setCellValue('A3', 'Total: ' . $users->count() . ' User');
        $sheet->getStyle('A3')->getFont()->setBold(true)->setSize(11);
        $sheet->getStyle('A3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Headers: NIP, Nama, 1, 2, ..., 31, Total
        $sheet->setCellValue('A5', 'NIP');
        $sheet->setCellValue('B5', 'Nama');
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $col = $getColumnName($day + 2); // +2 because NIP and Nama columns
            $sheet->setCellValue("{$col}5", $day);
        }
        $totalCol = $getColumnName($daysInMonth + 2);
        $sheet->setCellValue("{$totalCol}5", 'Total');

        // Style headers
        $sheet->getStyle("A5:{$totalCol}5")->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
        $sheet->getStyle("A5:{$totalCol}5")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('2E86AB');
        $sheet->getStyle("A5:{$totalCol}5")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Get data
        $data = $export->collection()->toArray();
        $rowNum = 6;

        foreach ($data as $row) {
            $sheet->setCellValue("A{$rowNum}", $row->nomor_induk);
            $sheet->setCellValue("B{$rowNum}", $row->name);

            // Get presensi for this user
            $userPresensi = $export->presensiData->get($row->nomor_induk, collect());
            $total = 0;

            for ($day = 1; $day <= $daysInMonth; $day++) {
                $col = $getColumnName($day + 2);
                // Dianggap hadir jika ada m_absen atau p_absen, dengan status null
                $hasPresensi = isset($userPresensi[$day]) &&
                    (!empty($userPresensi[$day]->m_absen) || !empty($userPresensi[$day]->p_absen)) &&
                    ($userPresensi[$day]->status === null);

                $sheet->setCellValue("{$col}{$rowNum}", $hasPresensi ? 1 : '');
                if ($hasPresensi) {
                    $total++;
                }
            }

            $sheet->setCellValue("{$totalCol}{$rowNum}", $total);
            $rowNum++;
        }

        // Style data area
        $sheet->getStyle("A6:{$totalCol}" . ($rowNum - 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("A6:{$totalCol}" . ($rowNum - 1))->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        // Highlight value 1 (green)
        for ($row = 6; $row < $rowNum; $row++) {
            for ($day = 1; $day <= $daysInMonth; $day++) {
                $col = $getColumnName($day + 2);
                $cell = $sheet->getCell("{$col}{$row}");
                if ($cell->getValue() === 1) {
                    $sheet->getStyle("{$col}{$row}")->getFont()->setBold(true)->getColor()->setRGB('155724');
                    $sheet->getStyle("{$col}{$row}")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('D4EDDA');
                }
            }
        }

        // Total column styling
        $sheet->getStyle("{$totalCol}6:{$totalCol}" . ($rowNum - 1))->getFont()->setBold(true);
        $sheet->getStyle("{$totalCol}6:{$totalCol}" . ($rowNum - 1))->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('E2E3E5');

        // Auto filter
        $sheet->setAutoFilter("A5:{$totalCol}5");

        // Freeze pane
        $sheet->freezePane('C6');

        // Column widths
        $sheet->getColumnDimension('A')->setWidth(18); // NIP
        $sheet->getColumnDimension('B')->setWidth(25); // Nama
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $col = $getColumnName($day + 2);
            $sheet->getColumnDimension($col)->setWidth(6);
        }
        $sheet->getColumnDimension($totalCol)->setWidth(8);
    }

    protected function buildDeptDetailHorizontalSheet($sheet, $export, $users, $dept, $month, $year): void
    {
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        // Helper function to get column name
        $getColumnName = function ($index) {
            $column = '';
            while ($index > 0) {
                $index--;
                $column = chr(65 + ($index % 26)) . $column;
                $index = (int) ($index / 26);
            }
            return $column;
        };

        // Title
        $lastCol = $getColumnName($daysInMonth + 3); // +3 for NIP, Nama, Total
        $sheet->mergeCells("A1:{$lastCol}1");
        $sheet->setCellValue('A1', 'DETAIL JAM PRESENSI - ' . strtoupper($dept->nama));
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells("A2:{$lastCol}2");
        $sheet->setCellValue('A2', 'Bulan: ' . $this->getMonthName($month) . ' ' . $year);
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(11);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells("A3:{$lastCol}3");
        $sheet->setCellValue('A3', 'Format: Jam Masuk / Jam Pulang');
        $sheet->getStyle('A3')->getFont()->setItalic(true)->setSize(10);
        $sheet->getStyle('A3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Headers: NIP, Nama, 1, 2, ..., 31, Total
        $sheet->setCellValue('A5', 'NIP');
        $sheet->setCellValue('B5', 'Nama');
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $col = $getColumnName($day + 2);
            $sheet->setCellValue("{$col}5", $day);
        }
        $totalCol = $getColumnName($daysInMonth + 2);
        $sheet->setCellValue("{$totalCol}5", 'Total Hari');

        // Style headers
        $sheet->getStyle("A5:{$totalCol}5")->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
        $sheet->getStyle("A5:{$totalCol}5")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('6C757D');
        $sheet->getStyle("A5:{$totalCol}5")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Get data
        $data = $export->collection()->toArray();
        $rowNum = 6;

        foreach ($data as $row) {
            $sheet->setCellValue("A{$rowNum}", $row->nomor_induk);
            $sheet->setCellValue("B{$rowNum}", $row->name);

            // Get presensi for this user
            $userPresensi = $export->presensiData->get($row->nomor_induk, collect());
            $total = 0;

            for ($day = 1; $day <= $daysInMonth; $day++) {
                $col = $getColumnName($day + 2);
                // Dianggap hadir jika ada m_absen atau p_absen, dengan status null
                $hasPresensi = isset($userPresensi[$day]) &&
                    (!empty($userPresensi[$day]->m_absen) || !empty($userPresensi[$day]->p_absen)) &&
                    ($userPresensi[$day]->status === null);

                if ($hasPresensi) {
                    $jamMasuk = $this->formatJam($userPresensi[$day]->m_absen);
                    $jamPulang = $this->formatJam($userPresensi[$day]->p_absen);
                    $sheet->setCellValue("{$col}{$rowNum}", "{$jamMasuk} / {$jamPulang}");
                    $total++;
                } else {
                    $sheet->setCellValue("{$col}{$rowNum}", '');
                }
            }

            $sheet->setCellValue("{$totalCol}{$rowNum}", $total);
            $rowNum++;
        }

        // Style data area
        $sheet->getStyle("A6:{$totalCol}" . ($rowNum - 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("A6:{$totalCol}" . ($rowNum - 1))->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        // Total column styling
        $sheet->getStyle("{$totalCol}6:{$totalCol}" . ($rowNum - 1))->getFont()->setBold(true);
        $sheet->getStyle("{$totalCol}6:{$totalCol}" . ($rowNum - 1))->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('E2E3E5');

        // Auto filter
        $sheet->setAutoFilter("A5:{$totalCol}5");

        // Freeze pane
        $sheet->freezePane('C6');

        // Column widths
        $sheet->getColumnDimension('A')->setWidth(18); // NIP
        $sheet->getColumnDimension('B')->setWidth(25); // Nama
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $col = $getColumnName($day + 2);
            $sheet->getColumnDimension($col)->setWidth(14); // Width untuk "07:30/16:00"
        }
        $sheet->getColumnDimension($totalCol)->setWidth(10); // Total
    }

    protected function formatJam($jam): string
    {
        if (empty($jam)) {
            return '-';
        }

        // Jika format HH:MM:SS, ambil HH:MM saja
        if (preg_match('/^(\d{2}:\d{2}):\d{2}$/', $jam, $matches)) {
            return $matches[1];
        }

        // Jika sudah format HH:MM, return langsung
        if (preg_match('/^\d{2}:\d{2}$/', $jam)) {
            return $jam;
        }

        return $jam;
    }

    protected function getMonthName(int $month): string
    {
        $months = [
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
        ];

        return $months[$month] ?? 'Unknown';
    }

    protected function printSummary(int $exportedCount, array $errors, string $outputPath, int $month, int $year): void
    {
        $this->info('╔════════════════════════════════════════════════════════════╗');
        $this->info('║                  RINGKASAN EXPORT                        ║');
        $this->info('╚════════════════════════════════════════════════════════════╝');
        $this->newLine();

        $this->info("📊 Total Unit Kerja Diexport: {$exportedCount}");

        if (!empty($errors)) {
            $this->error("❌ Total Gagal: " . count($errors));
            $this->newLine();

            foreach ($errors as $error) {
                $this->line("   - {$error['dept']}: {$error['error']}");
            }
        }

        $this->newLine();
        $this->info("📂 Lokasi File: {$outputPath}/{$year}/{$month}/");
        $this->newLine();
        $this->info("💡 File yang dihasilkan (1 per unit kerja):");
        $this->line("   - presensi_{nama_unit_kerja}_{year}_{month}.xlsx");
    }
}
