<?php

/**
 * Controller untuk auto-update app
 * Endpoint: GET /api/app-version
 *
 * Hybrid Versioning:
 * - version: Display version (e.g., "2.0.0")
 * - version_code: appVersionCode (per APK, reset per release)
 * - patch_count: Counter patch (reset when APK upgraded)
 * - build_number: Total global builds
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
     *
     * Returns both APK and patch info for hybrid versioning
     */
    public function index(): JsonResponse
    {
        $latestApk = AppPatch::getLatestApk();
        $latestPatch = AppPatch::where('is_active', true)
            ->where('update_type', 'patch')
            ->orderBy('version_code', 'desc')
            ->orderBy('patch_count', 'desc')
            ->first();

        $response = [
            'version' => '2.0.0',
            'version_code' => 1,
            'patch_count' => 0,
            'build_number' => 0,
            'download_url' => null,
            'changelog' => 'Initial release',
            'is_mandatory' => false,
            'is_patch_available' => false,
            'latest_patch_count' => 0,
        ];

        if ($latestApk) {
            $response['version'] = $latestApk->version;
            $response['version_code'] = $latestApk->version_code;
            $response['build_number'] = $latestApk->build_number;
            $response['download_url'] = url('/api/patch/download/' . $latestApk->id);
            $response['changelog'] = $latestApk->changelog;
            $response['is_mandatory'] = $latestApk->is_mandatory;
        }

        if ($latestPatch) {
            $response['latest_patch_count'] = $latestPatch->patch_count;
            $response['is_patch_available'] = true;
            // Prioritaskan changelog patch jika lebih baru
            if (!$latestApk || $latestPatch->version_code >= $latestApk->version_code) {
                $response['changelog'] = $latestPatch->changelog;
            }
        }

        return response()->json($response);
    }
}
