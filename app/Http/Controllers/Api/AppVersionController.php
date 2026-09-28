<?php

/**
 * Controller untuk auto-update app
 * Endpoint: GET /api/app-version
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AppPatch;
use Illuminate\Http\JsonResponse;

class AppVersionController extends Controller
{
    /**
     * Get latest app version info
     * GET /api/app-version
     */
    public function index(): JsonResponse
    {
        // Ambil dari database
        $latestPatch = AppPatch::where('is_active', true)
            ->orderBy('version_code', 'desc')
            ->first();

        if ($latestPatch) {
            return response()->json([
                'version' => $latestPatch->version,
                'version_code' => $latestPatch->version_code,
                'download_url' => url('/api/patch/download/' . $latestPatch->id),
                'changelog' => $latestPatch->changelog,
                'is_mandatory' => $latestPatch->is_mandatory,
            ]);
        }

        // Fallback jika tidak ada patch
        return response()->json([
            'version' => '2.0.0',
            'version_code' => 1,
            'download_url' => null,
            'changelog' => 'Initial release',
            'is_mandatory' => false,
        ]);
    }
}
