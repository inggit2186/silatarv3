<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AppPatch;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class AppPatchController extends Controller
{
    /**
     * Check for available patch update
     * GET /api/patch/check
     *
     * Query params:
     * - version: Current full version string (e.g., "2.0.0.1")
     * - patch_count: Current patch count (numeric)
     * - app_version_code: Current app version code (numeric)
     *
     * New Hybrid Versioning Flow:
     * 1. First check for patch updates (if app_version_code matches)
     * 2. Then check for APK updates (if app_version_code differs)
     */
    public function checkUpdate(Request $request): JsonResponse
    {
        $currentVersion = $request->input('version', '2.0.0');
        $currentPatchCount = (int) $request->input('patch_count', 0);
        $currentVersionCode = (int) $request->input('app_version_code', 1);

        // Log for debugging
        \Log::info("[PatchCheck] version=$currentVersion, patch_count=$currentPatchCount, app_version_code=$currentVersionCode");

        // Step 1: Check for PATCH updates
        // Only offer patch if app_version_code matches
        $latestPatch = $this->getAvailablePatch($currentVersionCode, $currentPatchCount);

        if ($latestPatch) {
            return $this->buildPatchResponse($latestPatch, $currentVersionCode, $currentPatchCount);
        }

        // Step 2: Check for APK updates
        // If app_version_code differs, user needs to install new APK
        $latestApk = AppPatch::getAvailableApkUpdate($currentVersionCode);

        if ($latestApk) {
            return $this->buildApkResponse($latestApk, $currentVersionCode);
        }

        // No updates available
        return response()->json([
            'hasUpdate' => false,
            'needUpdate' => false,
            'latestVersion' => $currentVersion,
            'version_code' => $currentVersionCode,
            'patch_count' => $currentPatchCount,
        ]);
    }

    /**
     * Get available patch for current app version
     */
    private function getAvailablePatch(int $versionCode, int $currentPatchCount): ?AppPatch
    {
        return AppPatch::where('is_active', true)
            ->where('update_type', 'patch')
            ->where('version_code', $versionCode)
            ->where('patch_count', '>', $currentPatchCount)
            ->where(function ($query) {
                $query->whereNull('min_app_version')
                    ->orWhereRaw("COALESCE(min_app_version, '') = ''");
            })
            ->where(function ($query) {
                $query->whereNull('max_app_version')
                    ->orWhereRaw("COALESCE(max_app_version, '') = ''");
            })
            ->orderBy('patch_count', 'desc')
            ->first();
    }

    /**
     * Build response for patch update
     */
    private function buildPatchResponse(AppPatch $patch, int $currentVersionCode, int $currentPatchCount): JsonResponse
    {
        return response()->json([
            'hasUpdate' => true,
            'needUpdate' => true,
            'updateType' => 'patch',
            'latestVersion' => $patch->full_version,
            'version_code' => $patch->version_code,
            'patch_count' => $patch->patch_count,
            'downloadUrl' => $patch->getDownloadUrl(),
            'patchUrl' => url('/api/patch/download/' . $patch->id),
            'md5' => $patch->md5,
            'fileSize' => $patch->file_size,
            'sizeHint' => $patch->size_hint,
            'changelog' => $patch->changelog,
            'isMandatory' => $patch->is_mandatory,
            'isPatch' => true,
            'isApk' => false,
        ]);
    }

    /**
     * Build response for APK update
     */
    private function buildApkResponse(AppPatch $apk, int $currentVersionCode): JsonResponse
    {
        return response()->json([
            'hasUpdate' => true,
            'needUpdate' => true,
            'updateType' => 'apk',
            'latestVersion' => $apk->version,
            'version_code' => $apk->version_code,
            'patch_count' => 0, // Reset patch count on APK update
            'downloadUrl' => $apk->getDownloadUrl(),
            'md5' => $apk->md5,
            'fileSize' => $apk->file_size,
            'sizeHint' => $apk->size_hint,
            'changelog' => $apk->changelog,
            'isMandatory' => $apk->is_mandatory,
            'isPatch' => false,
            'isApk' => true,
        ]);
    }

    /**
     * Download patch/APK file
     * GET /api/patch/download/{id}
     */
    public function download(int $id)
    {
        $patch = AppPatch::find($id);

        if (!$patch || !$patch->is_active) {
            abort(404, 'Update not found or inactive');
        }

        $filePath = null;

        if ($patch->update_type === 'apk') {
            if ($patch->apk_url && !str_starts_with($patch->apk_url, 'http')) {
                $filePath = storage_path('app/' . $patch->apk_url);
            } elseif ($patch->apk_url) {
                return redirect($patch->apk_url);
            } elseif ($patch->file_path) {
                $filePath = storage_path('app/' . $patch->file_path);
            }
        } else {
            $filePath = storage_path('app/' . $patch->file_path);
        }

        if (!$filePath || !file_exists($filePath)) {
            abort(404, 'File not found');
        }

        $downloadName = $patch->update_type === 'apk'
            ? 'silatar_v2_v' . $patch->version . '_build' . $patch->version_code . '.apk'
            : 'silatar_v2_patch_' . $patch->full_version . '.zip';

        return response()->download($filePath, $downloadName, [
            'Content-Type' => $patch->update_type === 'apk'
                ? 'application/vnd.android.package-archive'
                : 'application/octet-stream',
        ]);
    }

    /**
     * Get all patches (Admin)
     * GET /api/admin/patches
     */
    public function index(Request $request): JsonResponse
    {
        $query = AppPatch::query();

        // Filter by update type
        if ($request->has('type')) {
            $query->where('update_type', $request->input('type'));
        }

        // Filter by active status
        if ($request->has('active')) {
            $query->where('is_active', $request->boolean('active'));
        }

        // Order by version_code desc, then patch_count desc
        $query->orderBy('version_code', 'desc')
            ->orderBy('patch_count', 'desc');

        $patches = $query->paginate($request->input('per_page', 20));

        return response()->json($patches);
    }

    /**
     * Get single patch (Admin)
     * GET /api/admin/patches/{id}
     */
    public function show(int $id): JsonResponse
    {
        $patch = AppPatch::findOrFail($id);

        return response()->json($patch);
    }

    /**
     * Create new patch (Admin)
     * POST /api/admin/patches
     *
     * New fields:
     * - patch_count: required for patch type, auto for APK (set to 0)
     * - build_number: auto-incremented for APK, not used for patch
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'version' => 'required|string|max:20',
            'version_code' => 'required|integer|min:1',
            'patch_count' => 'nullable|integer|min:0',
            'update_type' => 'sometimes|in:patch,apk',
            'file' => 'required_unless:update_type,apk|file|mimes:zip,patch,bz2|max:51200',
            'apk_file' => 'required_if:update_type,apk|file|mimes:apk,zip|max:204800',
            'apk_url' => 'nullable|string|url|max:500',
            'changelog' => 'nullable|string',
            'is_mandatory' => 'boolean',
            'is_active' => 'boolean',
            'min_app_version' => 'nullable|string|max:20',
            'max_app_version' => 'nullable|string|max:20',
        ]);

        $updateType = $validated['update_type'] ?? 'patch';
        $patchCount = $validated['patch_count'] ?? 0;
        $fileName = null;
        $filePath = null;
        $fileSize = 0;
        $md5 = null;
        $sizeHint = null;
        $buildNumber = null;

        if ($updateType === 'patch') {
            // Patch requires patch_count
            if ($patchCount <= 0) {
                return response()->json([
                    'error' => 'patch_count is required for patch type and must be > 0',
                ], 422);
            }

            $file = $request->file('file');
            $fileName = $file->getClientOriginalName();
            $fileSize = $file->getSize();
            $filePath = $file->storeAs('patches', $fileName);
            $md5 = hash_file('md5', storage_path('app/' . $filePath));
            $sizeHint = $this->_formatFileSize($fileSize);
        } else {
            // APK - build_number auto-increment, patch_count = 0
            $buildNumber = AppPatch::getNextBuildNumber();
            $patchCount = 0;

            if ($request->hasFile('apk_file')) {
                $file = $request->file('apk_file');
                $fileName = $file->getClientOriginalName();
                $fileSize = $file->getSize();
                $filePath = $file->storeAs('patches/apk', $fileName);
                $md5 = hash_file('md5', storage_path('app/' . $filePath));
                $sizeHint = $this->_formatFileSize($fileSize);
            } else {
                $filePath = $validated['apk_url'] ?? null;
                $fileName = 'external_apk';
                $sizeHint = 'External APK';
            }
        }

        $patch = AppPatch::create([
            'version' => $validated['version'],
            'version_code' => $validated['version_code'],
            'patch_count' => $patchCount,
            'build_number' => $buildNumber,
            'update_type' => $updateType,
            'file_name' => $fileName,
            'file_path' => $filePath,
            'file_size' => $fileSize,
            'md5' => $md5,
            'apk_url' => $updateType === 'apk' && isset($validated['apk_url']) ? $validated['apk_url'] : null,
            'size_hint' => $sizeHint,
            'changelog' => $validated['changelog'] ?? null,
            'is_mandatory' => $validated['is_mandatory'] ?? false,
            'is_active' => $validated['is_active'] ?? true,
            'min_app_version' => $validated['min_app_version'] ?? null,
            'max_app_version' => $validated['max_app_version'] ?? null,
        ]);

        return response()->json([
            'message' => 'Update created successfully',
            'patch' => $patch,
        ], 201);
    }

    /**
     * Update patch (Admin)
     * PUT /api/admin/patches/{id}
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $patch = AppPatch::findOrFail($id);

        $validated = $request->validate([
            'version' => 'sometimes|string|max:20',
            'version_code' => 'sometimes|integer|min:1',
            'patch_count' => 'nullable|integer|min:0',
            'update_type' => 'sometimes|in:patch,apk',
            'file' => 'nullable|file|mimes:zip,patch,bz2|max:51200',
            'apk_file' => 'nullable|file|mimes:apk,zip|max:204800',
            'apk_url' => 'nullable|string|url|max:500',
            'changelog' => 'nullable|string',
            'is_mandatory' => 'boolean',
            'is_active' => 'boolean',
            'min_app_version' => 'nullable|string|max:20',
            'max_app_version' => 'nullable|string|max:20',
        ]);

        // Handle patch file upload
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = $file->getClientOriginalName();
            $fileSize = $file->getSize();

            $oldFilePath = storage_path('app/' . $patch->file_path);
            if (file_exists($oldFilePath) && strpos($patch->file_path, 'patches/apk') === false) {
                unlink($oldFilePath);
            }

            $filePath = $file->storeAs('patches', $fileName);

            $validated['file_name'] = $fileName;
            $validated['file_path'] = $filePath;
            $validated['file_size'] = $fileSize;
            $validated['md5'] = hash_file('md5', storage_path('app/' . $filePath));
            $validated['size_hint'] = $this->_formatFileSize($fileSize);
            $validated['update_type'] = $validated['update_type'] ?? 'patch';
        }

        // Handle APK file upload
        if ($request->hasFile('apk_file')) {
            $file = $request->file('apk_file');
            $fileName = $file->getClientOriginalName();
            $fileSize = $file->getSize();

            $apkDir = storage_path('app/patches/apk');
            if (!File::isDirectory($apkDir)) {
                File::makeDirectory($apkDir, 0755, true);
            }

            $filePath = $file->storeAs('patches/apk', $fileName);

            $validated['file_name'] = $fileName;
            $validated['file_path'] = $filePath;
            $validated['file_size'] = $fileSize;
            $validated['md5'] = hash_file('md5', storage_path('app/' . $filePath));
            $validated['size_hint'] = $this->_formatFileSize($fileSize);
            $validated['update_type'] = 'apk';
            $validated['apk_url'] = storage_path('app/' . $filePath);
        }

        if (isset($validated['apk_url']) && !$request->hasFile('apk_file')) {
            $validated['apk_url'] = $validated['apk_url'];
        }

        $patch->update($validated);

        return response()->json([
            'message' => 'Update updated successfully',
            'patch' => $patch->fresh(),
        ]);
    }

    /**
     * Delete patch (Admin)
     * DELETE /api/admin/patches/{id}
     */
    public function destroy(int $id): JsonResponse
    {
        $patch = AppPatch::findOrFail($id);

        $filePath = storage_path('app/' . $patch->file_path);
        if (file_exists($filePath) && strpos($patch->file_path, 'patches/apk') === false) {
            unlink($filePath);
        }

        $patch->delete();

        return response()->json([
            'message' => 'Patch deleted successfully',
        ]);
    }

    /**
     * Get public patch info
     * GET /api/patch/info
     */
    public function getInfo(): JsonResponse
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
            'updateType' => 'patch',
            'size_hint' => null,
            'changelog' => null,
            'is_mandatory' => false,
            'isPatch' => true,
            'isApk' => false,
            'updated_at' => null,
        ];

        if ($latestApk) {
            $response['version'] = $latestApk->version;
            $response['version_code'] = $latestApk->version_code;
            $response['build_number'] = $latestApk->build_number;
            $response['updateType'] = 'apk';
            $response['size_hint'] = $latestApk->size_hint;
            $response['changelog'] = $latestApk->changelog;
            $response['is_mandatory'] = $latestApk->is_mandatory;
            $response['isApk'] = true;
            $response['isPatch'] = false;
            $response['updated_at'] = $latestApk->updated_at;
        }

        if ($latestPatch) {
            $response['patch_count'] = $latestPatch->patch_count;
            if (!$latestApk || $latestPatch->version_code >= $latestApk->version_code) {
                $response['changelog'] = $latestPatch->changelog;
            }
        }

        return response()->json($response);
    }

    /**
     * Helper to format file size
     */
    private function _formatFileSize(int $bytes): string
    {
        if ($bytes < 1024) {
            return $bytes . ' B';
        }
        if ($bytes < 1024 * 1024) {
            return round($bytes / 1024, 1) . ' KB';
        }
        return round($bytes / 1024 / 1024, 1) . ' MB';
    }
}
