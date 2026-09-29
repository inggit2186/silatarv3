<?php

/**
 * Controller untuk hot code push dengan flutter_patcher
 *
 * Install:
 * 1. Copy file ini ke app/Http/Controllers/Api/PatchController.php
 * 2. Tambahkan route di routes/api.php:
 *
 *    Route::get('/app-version', [PatchController::class, 'check']);
 *    Route::get('/app-patch/{version}', [PatchController::class, 'download']);
 *
 * 3. Upload patch.zip ke storage/app/patches/
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class PatchController extends Controller
{
    /**
     * Check versi terbaru dan info patch
     * GET /api/app-version
     *
     * Response:
     * {
     *   "version": "1.0.1",
     *   "version_code": 2,
     *   "md5": "abc123...",
     *   "download_url": "https://domain.com/api/app-patch/1.0.1",
     *   "changelog": "- Perbaikan bug\n- Fitur baru"
     * }
     */
    public function check(): JsonResponse
    {
        // Base version code dari APK yang terinstall
        $baseVersionCode = (int) env('APP_VERSION_CODE', 1);

        // Latest patch version (increment setiap release)
        $latestVersionCode = (int) env('APP_PATCH_VERSION_CODE', $baseVersionCode);

        // Cek apakah ada patch baru
        if ($latestVersionCode <= $baseVersionCode) {
            return response()->json([
                'version' => null,
                'version_code' => $baseVersionCode,
                'has_update' => false,
            ]);
        }

        // Path ke manifest patch
        $patchPath = 'patches/v' . $latestVersionCode . '/manifest.json';

        // Cek apakah patch exists
        if (!Storage::disk('local')->exists($patchPath)) {
            return response()->json([
                'version' => null,
                'version_code' => $baseVersionCode,
                'has_update' => false,
            ]);
        }

        // Read manifest
        $manifest = json_decode(Storage::disk('local')->get($patchPath), true);

        return response()->json([
            'version' => 'v' . $latestVersionCode,
            'version_code' => $latestVersionCode,
            'md5' => $manifest['md5'] ?? '',
            'download_url' => url('/api/app-patch/v' . $latestVersionCode),
            'changelog' => $manifest['changelog'] ?? '- Update aplikasi',
            'has_update' => true,
        ]);
    }

    /**
     * Download patch file
     * GET /api/app-patch/{version}
     */
    public function download(string $version): JsonResponse|\Symfony\Component\HttpFoundation\StreamedResponse
    {
        $patchPath = 'patches/' . $version . '/patch.zip';

        if (!Storage::disk('local')->exists($patchPath)) {
            return response()->json([
                'error' => 'Patch not found',
            ], 404);
        }

        $fullPath = Storage::disk('local')->path($patchPath);

        return response()->download($fullPath, 'patch.zip', [
            'Content-Type' => 'application/zip',
        ]);
    }

    /**
     * Upload patch baru (untuk development)
     * POST /api/patch/upload
     */
    public function upload(): JsonResponse
    {
        // Validasi file
        request()->validate([
            'patch' => 'required|file|mimes:zip|max:102400', // max 100MB
            'version' => 'required|string',
            'md5' => 'required|string|size:32',
            'changelog' => 'nullable|string',
        ]);

        $version = request('version');
        $md5 = request('md5');
        $changelog = request('changelog', '- Update aplikasi');

        // Buat directory
        $dir = 'patches/v' . $version;
        Storage::disk('local')->makeDirectory($dir);

        // Simpan manifest
        $manifest = [
            'version' => $version,
            'md5' => $md5,
            'changelog' => $changelog,
            'created_at' => now()->toIso8601String(),
        ];
        Storage::disk('local')->put($dir . '/manifest.json', json_encode($manifest, JSON_PRETTY_PRINT));

        // Simpan patch file
        $file = request()->file('patch');
        $file->storeAs('', 'patches/' . $version . '/patch.zip', ['disk' => 'local']);

        // Update env
        file_put_contents(base_path('.env'), "\nAPP_PATCH_VERSION_CODE=" . str_replace('v', '', $version), FILE_APPEND);

        return response()->json([
            'success' => true,
            'version' => $version,
            'message' => 'Patch uploaded successfully',
        ]);
    }
}
