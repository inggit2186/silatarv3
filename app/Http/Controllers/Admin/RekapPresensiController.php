<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KtdPresensiFile;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class RekapPresensiController extends Controller
{
    /**
     * Tampilkan form rekap presensi
     */
    public function index()
    {
        $departments = Department::whereIn('status', [1, 2])->orderBy('nama')->get();
        // Default: bulan sebelumnya
        $currentMonth = (int) date('m') - 1;
        if ($currentMonth < 1) {
            $currentMonth = 12;
            $currentYear = (int) date('Y') - 1;
        } else {
            $currentYear = (int) date('Y');
        }

        // Get list of departments that already have generated files
        $generatedRecords = KtdPresensiFile::select('dept', 'bulan', 'tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->get();

        // Map dept name to dept_id
        $generatedDepts = [];
        foreach ($generatedRecords as $record) {
            $dept = Department::where('nama', $record->dept)->first();
            if ($dept) {
                $generatedDepts[] = [
                    'dept_id' => $dept->id,
                    'dept' => $record->dept,
                    'bulan' => $record->bulan,
                    'tahun' => $record->tahun,
                ];
            }
        }

        return view('admin.rekap-presensi.index', compact('departments', 'currentMonth', 'currentYear', 'generatedDepts'));
    }

    /**
     * Generate rekap presensi
     */
    public function generate(Request $request)
    {
        $request->validate([
            'dept_id' => 'required|integer|exists:ktd_department,id',
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|between:2020,2030',
        ]);

        $deptId = $request->dept_id;
        $month = $request->month;
        $year = $request->year;

        // Get department info
        $dept = Department::find($deptId);
        if (!$dept) {
            return back()->with('error', 'Unit kerja tidak ditemukan');
        }

        // Get users in this department
        $users = DB::table('users')
            ->where('dept_id', $deptId)
            ->whereNotNull('nomor_induk')
            ->where('nomor_induk', '!=', '')
            ->select('id', 'name', 'nomor_induk')
            ->orderBy('name')
            ->get();

        if ($users->isEmpty()) {
            return back()->with('error', 'Tidak ada user di unit kerja ini');
        }

        // Get presensi data
        $nips = $users->pluck('nomor_induk')->toArray();
        $presensiData = DB::table('ktd_presensi')
            ->whereIn('user_nip', $nips)
            ->whereYear('tanggal', $year)
            ->whereMonth('tanggal', $month)
            ->get()
            ->groupBy('user_nip')
            ->map(function ($rows) {
                return $rows->keyBy(function ($row) {
                    return (int) date('d', strtotime($row->tanggal));
                });
            });

        // Generate Excel files
        try {
            // Generate file presensi (rekap absensi)
            $presensiFile = $this->generatePresensiExcel($users, $presensiData, $dept, $month, $year);

            // Generate file detail presensi (detail jam) - disimpan di kolom presensi_path
            $detailFile = $this->generateDetailPresensiExcel($users, $presensiData, $dept, $month, $year);

            // Save files to filesystem
            $cleanDeptName = preg_replace('/[^a-zA-Z0-9]/', '_', $dept->nama);
            $rekapDir = storage_path('app/rekap_presensi');
            if (!file_exists($rekapDir)) {
                mkdir($rekapDir, 0755, true);
            }
            $deptDir = "{$rekapDir}/{$cleanDeptName}";
            if (!file_exists($deptDir)) {
                mkdir($deptDir, 0755, true);
            }

            // Save rekap presensi file (value 1)
            $rekapFilename = "rekap_presensi_{$cleanDeptName}_{$year}_{$month}.xlsx";
            $rekapPath = "rekap_presensi/{$cleanDeptName}/{$rekapFilename}";
            file_put_contents(storage_path("app/{$rekapPath}"), base64_decode($presensiFile));

            // Save detail presensi file (jam masuk/pulang)
            $detailFilename = "detail_presensi_{$cleanDeptName}_{$year}_{$month}.xlsx";
            $detailPath = "rekap_presensi/{$cleanDeptName}/{$detailFilename}";
            file_put_contents(storage_path("app/{$detailPath}"), base64_decode($detailFile));

            // Save to ktd_presensifiles table
            $existing = KtdPresensiFile::where('dept', $dept->nama)
                ->where('bulan', $month)
                ->where('tahun', $year)
                ->first();

            if ($existing) {
                // Delete old files if exists
                if ($existing->presensi && file_exists(storage_path('app/' . $existing->presensi))) {
                    unlink(storage_path('app/' . $existing->presensi));
                }
                if ($existing->uangmakan && file_exists(storage_path('app/' . $existing->uangmakan))) {
                    unlink(storage_path('app/' . $existing->uangmakan));
                }

                $existing->update([
                    'presensi' => $detailPath, // detail presensi (jam masuk/pulang)
                    'uangmakan' => $rekapPath, // rekap presensi (value 1)
                ]);
            } else {
                KtdPresensiFile::create([
                    'dept' => $dept->nama,
                    'bulan' => $month,
                    'tahun' => $year,
                    'presensi' => $detailPath, // detail presensi (jam masuk/pulang)
                    'uangmakan' => $rekapPath, // rekap presensi (value 1)
                ]);
            }

            Log::info("Rekap presensi berhasil digenerate", [
                'dept' => $dept->nama,
                'month' => $month,
                'year' => $year,
                'users' => $users->count(),
            ]);

            return back()->with('success', 'Rekap presensi berhasil digenerate untuk ' . $dept->nama)
                ->with('dept_id', $deptId)
                ->with('month', $month)
                ->with('year', $year);

        } catch (\Exception $e) {
            Log::error("Gagal generate rekap presensi: " . $e->getMessage());
            return back()->with('error', 'Gagal generate rekap presensi: ' . $e->getMessage());
        }
    }

    /**
     * Download file presensi (rekap absensi dengan value 1)
     */
    public function downloadPresensi(Request $request)
    {
        $request->validate([
            'dept_id' => 'required|integer',
            'month' => 'required|integer',
            'year' => 'required|integer',
        ]);

        $dept = Department::find($request->dept_id);
        if (!$dept) {
            return back()->with('error', 'Unit kerja tidak ditemukan');
        }

        $record = KtdPresensiFile::where('dept', $dept->nama)
            ->where('bulan', $request->month)
            ->where('tahun', $request->year)
            ->first();

        if (!$record || !$record->uangmakan) {
            return back()->with('error', 'File rekap presensi tidak ditemukan');
        }

        $fullPath = storage_path('app/' . $record->uangmakan);
        if (!file_exists($fullPath)) {
            return back()->with('error', 'File tidak ditemukan di server');
        }

        $filename = basename($record->uangmakan);

        return response()->download($fullPath, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    /**
     * Download file detail presensi (jam masuk/pulang)
     */
    public function downloadDetail(Request $request)
    {
        $request->validate([
            'dept_id' => 'required|integer',
            'month' => 'required|integer',
            'year' => 'required|integer',
        ]);

        $dept = Department::find($request->dept_id);
        if (!$dept) {
            return back()->with('error', 'Unit kerja tidak ditemukan');
        }

        $record = KtdPresensiFile::where('dept', $dept->nama)
            ->where('bulan', $request->month)
            ->where('tahun', $request->year)
            ->first();

        if (!$record || !$record->presensi) {
            return back()->with('error', 'File detail presensi tidak ditemukan');
        }

        $fullPath = storage_path('app/' . $record->presensi);
        if (!file_exists($fullPath)) {
            return back()->with('error', 'File tidak ditemukan di server');
        }

        $filename = basename($record->presensi);

        return response()->download($fullPath, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    /**
     * Generate file Excel presensi (rekap absensi dengan value 1)
     */
    protected function generatePresensiExcel($users, $presensiData, $dept, $month, $year): string
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

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Title
        $lastCol = $getColumnName($daysInMonth + 3);
        $sheet->mergeCells("A1:{$lastCol}1");
        $sheet->setCellValue('A1', 'REKAP ABSENSI - ' . strtoupper($dept->nama));
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);

        $sheet->mergeCells("A2:{$lastCol}2");
        $sheet->setCellValue('A2', 'Bulan: ' . $this->getMonthName($month) . ' ' . $year);

        // Headers
        $sheet->setCellValue('A5', 'NIP');
        $sheet->setCellValue('B5', 'Nama');
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $col = $getColumnName($day + 2);
            $sheet->setCellValue("{$col}5", $day);
        }
        $totalCol = $getColumnName($daysInMonth + 2);
        $sheet->setCellValue("{$totalCol}5", 'Total');

        // Style headers
        $sheet->getStyle("A5:{$totalCol}5")->getFont()->setBold(true);

        // Data
        $rowNum = 6;
        foreach ($users as $user) {
            $sheet->setCellValue("A{$rowNum}", $user->nomor_induk);
            $sheet->setCellValue("B{$rowNum}", $user->name);

            $userPresensi = $presensiData->get($user->nomor_induk, collect());
            $total = 0;

            for ($day = 1; $day <= $daysInMonth; $day++) {
                $col = $getColumnName($day + 2);
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

        // Auto-size columns
        $sheet->getColumnDimension('A')->setWidth(18);
        $sheet->getColumnDimension('B')->setWidth(25);

        // Save to temp file then read
        $tempFile = tempnam(sys_get_temp_dir(), 'presensi_');
        $writer = new Xlsx($spreadsheet);
        $writer->save($tempFile);
        $excelContent = file_get_contents($tempFile);
        unlink($tempFile);

        return base64_encode($excelContent);
    }

    /**
     * Generate file Excel detail presensi (jam masuk/pulang)
     */
    protected function generateDetailPresensiExcel($users, $presensiData, $dept, $month, $year): string
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

        // Helper function to format jam
        $formatJam = function ($jam) {
            if (empty($jam)) {
                return '-';
            }
            if (preg_match('/^(\d{2}:\d{2}):\d{2}$/', $jam, $matches)) {
                return $matches[1];
            }
            if (preg_match('/^\d{2}:\d{2}$/', $jam)) {
                return $jam;
            }
            return $jam;
        };

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Title
        $lastCol = $getColumnName($daysInMonth + 3);
        $sheet->mergeCells("A1:{$lastCol}1");
        $sheet->setCellValue('A1', 'DETAIL JAM PRESENSI - ' . strtoupper($dept->nama));
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);

        $sheet->mergeCells("A2:{$lastCol}2");
        $sheet->setCellValue('A2', 'Bulan: ' . $this->getMonthName($month) . ' ' . $year);

        // Headers
        $sheet->setCellValue('A5', 'NIP');
        $sheet->setCellValue('B5', 'Nama');
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $col = $getColumnName($day + 2);
            $sheet->setCellValue("{$col}5", $day);
        }
        $totalCol = $getColumnName($daysInMonth + 2);
        $sheet->setCellValue("{$totalCol}5", 'Total Hari');

        // Style headers
        $sheet->getStyle("A5:{$totalCol}5")->getFont()->setBold(true);

        // Data
        $rowNum = 6;
        foreach ($users as $user) {
            $sheet->setCellValue("A{$rowNum}", $user->nomor_induk);
            $sheet->setCellValue("B{$rowNum}", $user->name);

            $userPresensi = $presensiData->get($user->nomor_induk, collect());
            $total = 0;

            for ($day = 1; $day <= $daysInMonth; $day++) {
                $col = $getColumnName($day + 2);
                $hasPresensi = isset($userPresensi[$day]) &&
                    (!empty($userPresensi[$day]->m_absen) || !empty($userPresensi[$day]->p_absen)) &&
                    ($userPresensi[$day]->status === null);

                if ($hasPresensi) {
                    $jamMasuk = $formatJam($userPresensi[$day]->m_absen);
                    $jamPulang = $formatJam($userPresensi[$day]->p_absen);
                    $sheet->setCellValue("{$col}{$rowNum}", "{$jamMasuk} / {$jamPulang}");
                    $total++;
                } else {
                    $sheet->setCellValue("{$col}{$rowNum}", '');
                }
            }

            $sheet->setCellValue("{$totalCol}{$rowNum}", $total);
            $rowNum++;
        }

        // Auto-size columns
        $sheet->getColumnDimension('A')->setWidth(18);
        $sheet->getColumnDimension('B')->setWidth(25);
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $sheet->getColumnDimension($getColumnName($day + 2))->setWidth(14);
        }

        // Save to temp file then read
        $tempFile = tempnam(sys_get_temp_dir(), 'presensi_detail_');
        $writer = new Xlsx($spreadsheet);
        $writer->save($tempFile);
        $excelContent = file_get_contents($tempFile);
        unlink($tempFile);

        return base64_encode($excelContent);
    }

    protected function getMonthName(int $month): string
    {
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];
        return $months[$month] ?? 'Unknown';
    }
}
