<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportDownloadController extends Controller
{
    /**
     * Tampilkan list file export
     */
    public function index()
    {
        $exportPath = storage_path('app/exports/presensi');

        // Get all year directories
        $years = [];
        if (file_exists($exportPath)) {
            $yearDirs = glob($exportPath . '/*', GLOB_ONLYDIR);
            foreach ($yearDirs as $yearDir) {
                $year = basename($yearDir);
                $months = [];

                $monthDirs = glob($yearDir . '/*', GLOB_ONLYDIR);
                foreach ($monthDirs as $monthDir) {
                    $month = basename($monthDir);
                    $files = glob($monthDir . '/*.xlsx');

                    $monthFiles = [];
                    foreach ($files as $file) {
                        $monthFiles[] = [
                            'name' => basename($file),
                            'size' => $this->formatFileSize(filesize($file)),
                            'size_bytes' => filesize($file),
                            'modified' => date('d M Y H:i', filemtime($file)),
                            'path' => str_replace($exportPath . '/', '', $file),
                        ];
                    }

                    if (!empty($monthFiles)) {
                        $months[$this->getMonthName((int) $month)] = [
                            'month' => $month,
                            'files' => $monthFiles,
                            'total_files' => count($monthFiles),
                            'total_size' => $this->formatFileSize(array_sum(array_column($monthFiles, 'size_bytes'))),
                        ];
                    }
                }

                if (!empty($months)) {
                    $years[$year] = [
                        'months' => $months,
                        'total_files' => array_sum(array_column($months, 'total_files')),
                    ];
                }
            }
        }

        // Sort by year descending
        krsort($years);

        return view('admin.exports.index', compact('years'));
    }

    /**
     * Download file export
     */
    public function download(Request $request)
    {
        $request->validate([
            'file' => 'required|string',
        ]);

        $filePath = $request->file;
        $fullPath = storage_path('app/exports/presensi/' . $filePath);

        // Security check: ensure file is within exports directory
        if (!str_starts_with(realpath($fullPath), realpath(storage_path('app/exports/presensi')))) {
            abort(403, 'Unauthorized file access');
        }

        if (!file_exists($fullPath)) {
            abort(404, 'File not found');
        }

        $filename = basename($fullPath);

        return response()->download($fullPath, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(false);
    }

    /**
     * Download semua file dalam 1 bulan sebagai ZIP
     */
    public function downloadMonth(Request $request)
    {
        $request->validate([
            'year' => 'required|integer',
            'month' => 'required|integer|between:1,12',
        ]);

        $year = $request->year;
        $month = $request->month;
        $monthPath = storage_path("app/exports/presensi/{$year}/{$month}");

        if (!file_exists($monthPath)) {
            abort(404, 'Month directory not found');
        }

        $files = glob($monthPath . '/*.xlsx');
        if (empty($files)) {
            abort(404, 'No files found for this month');
        }

        // Create temp zip file
        $zipFilename = "presensi_{$year}_{$month}.zip";
        $zipPath = storage_path("app/exports/{$zipFilename}");

        $zip = new \ZipArchive();
        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === true) {
            foreach ($files as $file) {
                $zip->addFile($file, basename($file));
            }
            $zip->close();
        }

        return response()->download($zipPath, $zipFilename, [
            'Content-Type' => 'application/zip',
        ])->deleteFileAfterSend(true);
    }

    /**
     * Delete file export
     */
    public function delete(Request $request)
    {
        $request->validate([
            'file' => 'required|string',
        ]);

        $filePath = $request->file;
        $fullPath = storage_path('app/exports/presensi/' . $filePath);

        // Security check
        if (!str_starts_with(realpath($fullPath), realpath(storage_path('app/exports/presensi')))) {
            abort(403, 'Unauthorized file access');
        }

        if (!file_exists($fullPath)) {
            abort(404, 'File not found');
        }

        unlink($fullPath);

        return back()->with('success', 'File berhasil dihapus');
    }

    protected function formatFileSize(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);

        return round($bytes, 2) . ' ' . $units[$pow];
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
}
