<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AsnImportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class AsnImportController extends Controller
{
    protected $importService;

    public function __construct(AsnImportService $importService)
    {
        $this->importService = $importService;
    }

    /**
     * Tampilkan form upload + history
     */
    public function index()
    {
        $history = $this->importService->getImportHistory();

        return view('admin.asn-import.index', compact('history'));
    }

    /**
     * Preview data sebelum import
     */
    public function preview(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls|max:10240',
        ]);

        try {
            $file = $request->file('file');
            $filename = 'asn_import_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('imports', $filename, 'local');

            $fullPath = Storage::disk('local')->path($path);
            $parsed = $this->importService->parseExcel($fullPath);

            if (!$parsed['success']) {
                return back()->with('error', $parsed['error']);
            }

            $validated = $this->importService->validateData($parsed['data']);

            session([
                'asn_import_data' => $validated,
                'asn_import_file' => $path,
            ]);

            return view('admin.asn-import.preview', [
                'data' => $validated,
                'filename' => $file->getClientOriginalName(),
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
        $validatedData = session('asn_import_data');
        $importFile = session('asn_import_file');

        if (!$validatedData || !$importFile) {
            return back()->with('error', 'Data import tidak ditemukan. Silakan upload ulang.');
        }

        try {
            $userId = Auth::id();
            $result = $this->importService->importData($validatedData, $userId);

            // Cleanup
            if ($importFile && Storage::disk('local')->exists($importFile)) {
                Storage::disk('local')->delete($importFile);
            }
            session()->forget(['asn_import_data', 'asn_import_file']);

            if ($result['success']) {
                return redirect()
                    ->route('admin.import-asn.index')
                    ->with('success', $result['message']);
            } else {
                return back()->with('error', $result['error']);
            }

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal import: ' . $e->getMessage());
        }
    }

    /**
     * History import
     */
    public function history()
    {
        $history = $this->importService->getImportHistory();

        return view('admin.asn-import.history', compact('history'));
    }
}
