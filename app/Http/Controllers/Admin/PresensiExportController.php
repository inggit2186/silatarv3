<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Exports\PresensiDetailExport;
use App\Exports\PresensiAbsensiExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\HeadingsToArrayImport;
use PhpOffice\PhpSpreadsheet\IOFactory;

class PresensiExportController extends Controller
{
    /**
     * Tampilkan form export
     */
    public function index()
    {
        $users = DB::table('users')
            ->whereNotNull('nomor_induk')
            ->where('nomor_induk', '!=', '')
            ->orderBy('name')
            ->get();

        $currentMonth = (int) date('m');
        $currentYear = (int) date('Y');

        return view('admin.presensi.export', compact('users', 'currentMonth', 'currentYear'));
    }

    /**
     * Export presensi detail
     */
    public function exportDetail(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|between:2020,2030',
        ]);

        $user = DB::table('users')->find($request->user_id);

        if (!$user) {
            return back()->with('error', 'User tidak ditemukan');
        }

        $filename = "presensi_detail_{$user->nomor_induk}_{$request->year}_{$request->month}.xlsx";

        return Excel::download(
            new PresensiDetailExport($request->user_id, $request->month, $request->year),
            $filename
        );
    }

    /**
     * Export presensi absensi
     */
    public function exportAbsensi(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|between:2020,2030',
        ]);

        $user = DB::table('users')->find($request->user_id);

        if (!$user) {
            return back()->with('error', 'User tidak ditemukan');
        }

        $filename = "presensi_absensi_{$user->nomor_induk}_{$request->year}_{$request->month}.xlsx";

        return Excel::download(
            new PresensiAbsensiExport($request->user_id, $request->month, $request->year),
            $filename
        );
    }

    /**
     * Export semua user (bulk)
     */
    public function exportBulk(Request $request)
    {
        $request->validate([
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|between:2020,2030',
            'type' => 'required|in:detail,absensi',
        ]);

        $users = DB::table('users')
            ->whereNotNull('nomor_induk')
            ->where('nomor_induk', '!=', '')
            ->orderBy('name')
            ->get();

        $month = $request->month;
        $year = $request->year;
        $type = $request->type;

        // Create temp directory
        $tempPath = storage_path("app/exports/temp");
        if (!file_exists($tempPath)) {
            mkdir($tempPath, 0755, true);
        }

        $files = [];

        foreach ($users as $user) {
            if ($type === 'detail') {
                $filename = "presensi_detail_{$user->nomor_induk}_{$year}_{$month}.xlsx";
                Excel::store(
                    new PresensiDetailExport($user->id, $month, $year),
                    "exports/temp/{$filename}",
                    'local'
                );
            } else {
                $filename = "presensi_absensi_{$user->nomor_induk}_{$year}_{$month}.xlsx";
                Excel::store(
                    new PresensiAbsensiExport($user->id, $month, $year),
                    "exports/temp/{$filename}",
                    'local'
                );
            }

            $files[] = $tempPath . "/{$filename}";
        }

        // Create zip file
        $zipFilename = "presensi_{$type}_{$year}_{$month}.zip";
        $zipPath = storage_path("app/exports/{$zipFilename}");

        $zip = new \ZipArchive();
        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === true) {
            foreach ($files as $file) {
                $zip->addFile($file, basename($file));
            }
            $zip->close();
        }

        // Cleanup temp files
        foreach ($files as $file) {
            if (file_exists($file)) {
                unlink($file);
            }
        }

        if (file_exists($tempPath)) {
            rmdir($tempPath);
        }

        // Download zip
        return response()->download($zipPath)->deleteFileAfterSend(true);
    }
}
