<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\PresensiImportService;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class PresensiImportController extends Controller
{
    protected $importService;

    public function __construct(PresensiImportService $importService)
    {
        $this->importService = $importService;
    }

    /**
     * Tampilkan form import
     */
    public function index()
    {
        $departments = Department::orderBy('dept_name')->get();
        $history = $this->importService->getImportHistory();

        return view('admin.presensi.import', compact('departments', 'history'));
    }

    /**
     * Preview data sebelum import
     */
    public function preview(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls|max:5120', // Max 5MB
            'dept_id' => 'required|integer|exists:ktd_department,dept_id',
        ]);

        try {
            // Simpan file sementara
            $file = $request->file('file');
            $filename = 'presensi_import_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('imports', $filename, 'local');

            // Parse Excel
            $fullPath = Storage::disk('local')->path($path);
            $parsed = $this->importService->parseExcel($fullPath);

            if (!$parsed['success']) {
                return back()->with('error', $parsed['error']);
            }

            // Validasi data
            $validated = $this->importService->validateData($parsed['data'], $request->dept_id);

            // Simpan di session untuk preview
            session([
                'import_data' => $validated,
                'import_file' => $path,
                'import_dept_id' => $request->dept_id,
            ]);

            return view('admin.presensi.preview', [
                'data' => $validated,
                'filename' => $file->getClientOriginalName(),
                'dept_id' => $request->dept_id,
            ]);

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses file: ' . $e->getMessage());
        }
    }

    /**
     * Execute import
     */
    public function import(Request $request)
    {
        $validatedData = session('import_data');
        $importFile = session('import_file');
        $deptId = session('import_dept_id');

        if (!$validatedData || !$importFile) {
            return back()->with('error', 'Data import tidak ditemukan. Silakan upload ulang.');
        }

        try {
            $userId = Auth::id();
            $result = $this->importService->importToDatabase($validatedData, $userId);

            // Hapus file sementara
            if ($importFile && Storage::disk('local')->exists($importFile)) {
                Storage::disk('local')->delete($importFile);
            }

            // Clear session
            session()->forget(['import_data', 'import_file', 'import_dept_id']);

            if ($result['success']) {
                return redirect()
                    ->route('admin.presensi.import')
                    ->with('success', $result['message']);
            } else {
                return back()->with('error', $result['error']);
            }

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal import: ' . $e->getMessage());
        }
    }

    /**
     * Rollback import
     */
    public function rollback(Request $request, string $batchId)
    {
        $request->validate([
            'confirm' => 'required|in:yes',
        ]);

        $result = $this->importService->rollbackImport($batchId);

        if ($result['success']) {
            return back()->with('success', $result['message']);
        } else {
            return back()->with('error', $result['error']);
        }
    }

    /**
     * History import
     */
    public function history()
    {
        $history = $this->importService->getImportHistory();

        return view('admin.presensi.history', compact('history'));
    }
}
