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
     * Check for available patch/update
     * GET /api/patch/check
     *
     * Query params:
     * - version: Current app version string
     * - version_code: Current app version code (numeric)
     */
    public function checkUpdate(Request $request): JsonResponse
    {
        $currentVersion = $request->input('version', '1.0.0');
        $currentVersionCode = (int) $request->input('version_code', 1);

        // Get latest active patch (prioritize patch type, then apk)
        $latestPatch = AppPatch::where('is_active', true)
            ->where('version_code', '>', $currentVersionCode)
            ->where(function ($query) use ($currentVersion) {
                $query->whereNull('min_app_version')
                    ->orWhere('min_app_version', '<=', $currentVersion);
            })
            ->where(function ($query) use ($currentVersion) {
                $query->whereNull('max_app_version')
                    ->orWhere('max_app_version', '>=', $currentVersion);
            })
            ->orderByRaw("FIELD(update_type, 'apk', 'patch')")
            ->orderBy('version_code', 'desc')
            ->first();

        if ($latestPatch) {
            return response()->json([
                'hasUpdate' => true,
                'needUpdate' => true,
                'updateType' => $latestPatch->update_type,
                'latestVersion' => $latestPatch->version,
                'version_code' => $latestPatch->version_code,
                'downloadUrl' => $latestPatch->getDownloadUrl(),
                'patchUrl' => $latestPatch->update_type === 'patch'
                    ? url('/api/patch/download/' . $latestPatch->id)
                    : null,
                'md5' => $latestPatch->md5,
                'fileSize' => $latestPatch->file_size,
                'sizeHint' => $latestPatch->size_hint,
                'changelog' => $latestPatch->changelog,
                'isMandatory' => $latestPatch->is_mandatory,
                'isPatch' => $latestPatch->update_type === 'patch',
                'isApk' => $latestPatch->update_type === 'apk',
                'minAppVersion' => $latestPatch->min_app_version,
                'maxAppVersion' => $latestPatch->max_app_version,
            ]);
        }

        return response()->json([
            'hasUpdate' => false,
            'needUpdate' => false,
            'latestVersion' => $currentVersion,
            'version_code' => $currentVersionCode,
        ]);
    }

    /**
     * Get latest app version info (for full APK updates)
     * GET /api/app-version
     */
    public function getLatestVersion(Request $request): JsonResponse
    {
        $latestPatch = AppPatch::where('is_active', true)
            ->orderBy('version_code', 'desc')
            ->first();

        if ($latestPatch) {
            return response()->json([
                'version' => $latestPatch->version,
                'version_code' => $latestPatch->version_code,
                'updateType' => $latestPatch->update_type,
                'download_url' => $latestPatch->getDownloadUrl(),
                'size_hint' => $latestPatch->size_hint,
                'changelog' => $latestPatch->changelog,
                'is_mandatory' => $latestPatch->is_mandatory,
                'isPatch' => $latestPatch->update_type === 'patch',
                'isApk' => $latestPatch->update_type === 'apk',
            ]);
        }

        return response()->json([
            'version' => '1.0.0',
            'version_code' => 1,
            'updateType' => 'patch',
            'download_url' => null,
            'size_hint' => null,
            'changelog' => '',
            'is_mandatory' => false,
            'isPatch' => true,
            'isApk' => false,
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
            // For APK type, use the stored path or URL
            if ($patch->apk_url && !str_starts_with($patch->apk_url, 'http')) {
                $filePath = storage_path('app/' . $patch->apk_url);
            } elseif ($patch->apk_url) {
                // External URL - redirect
                return redirect($patch->apk_url);
            } elseif ($patch->file_path) {
                $filePath = storage_path('app/' . $patch->file_path);
            }
        } else {
            // For patch type
            $filePath = storage_path('app/' . $patch->file_path);
        }

        if (!$filePath || !file_exists($filePath)) {
            abort(404, 'File not found');
        }

        $downloadName = $patch->update_type === 'apk'
            ? 'silatar-' . $patch->version . '.apk'
            : 'patch.zip';

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
        $patches = AppPatch::orderBy('version_code', 'desc')
            ->paginate($request->input('per_page', 20));

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
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'version' => 'required|string|max:20|unique:app_patches,version',
            'version_code' => 'required|integer|min:1',
            'update_type' => 'sometimes|in:patch,apk',
            'file' => 'required_unless:update_type,apk|file|mimes:zip,patch,bz2|max:51200', // max 50MB for patch
            'apk_file' => 'required_if:update_type,apk|file|mimes:apk,zip|max:204800', // max 200MB for APK
            'apk_url' => 'nullable|string|url|max:500',
            'changelog' => 'nullable|string',
            'is_mandatory' => 'boolean',
            'is_active' => 'boolean',
            'min_app_version' => 'nullable|string|max:20',
            'max_app_version' => 'nullable|string|max:20',
        ]);

        $updateType = $validated['update_type'] ?? 'patch';
        $fileName = null;
        $filePath = null;
        $fileSize = 0;
        $md5 = null;
        $sizeHint = null;

        if ($updateType === 'patch') {
            // Handle patch file upload
            $file = $request->file('file');
            $fileName = $file->getClientOriginalName();
            $fileSize = $file->getSize();
            $filePath = $file->storeAs('patches', $fileName);
            $md5 = hash_file('md5', storage_path('app/' . $filePath));
            $sizeHint = $this->_formatFileSize($fileSize);
        } else {
            // Handle APK file or use provided URL
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
                $sizeHint = 'Unknown';
            }
        }

        // Create patch record
        $patch = AppPatch::create([
            'version' => $validated['version'],
            'version_code' => $validated['version_code'],
            'update_type' => $updateType,
            'file_name' => $fileName,
            'file_path' => $filePath,
            'file_size' => $fileSize,
            'md5' => $md5,
            'apk_url' => $updateType === 'apk' ? ($validated['apk_url'] ?? $filePath) : null,
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

    /**
     * Update patch (Admin)
     * PUT /api/admin/patches/{id}
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $patch = AppPatch::findOrFail($id);

        $validated = $request->validate([
            'version' => 'sometimes|string|max:20|unique:app_patches,version,' . $id,
            'version_code' => 'sometimes|integer|min:1',
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

        // Handle patch file upload if provided
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = $file->getClientOriginalName();
            $fileSize = $file->getSize();

            // Delete old file
            $oldFilePath = storage_path('app/' . $patch->file_path);
            if (file_exists($oldFilePath) && $patch->file_path !== 'patches/apk') {
                unlink($oldFilePath);
            }

            // Store new file
            $filePath = $file->storeAs('patches', $fileName);

            $validated['file_name'] = $fileName;
            $validated['file_path'] = $filePath;
            $validated['file_size'] = $fileSize;
            $validated['md5'] = hash_file('md5', storage_path('app/' . $filePath));
            $validated['size_hint'] = $this->_formatFileSize($fileSize);
            $validated['update_type'] = $validated['update_type'] ?? 'patch';
        }

        // Handle APK file upload if provided
        if ($request->hasFile('apk_file')) {
            $file = $request->file('apk_file');
            $fileName = $file->getClientOriginalName();
            $fileSize = $file->getSize();

            // Create apk directory if not exists
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

        // Handle external APK URL
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

        // Delete file
        $filePath = storage_path('app/' . $patch->file_path);
        if (file_exists($filePath)) {
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
        $latestPatch = AppPatch::where('is_active', true)
            ->orderBy('version_code', 'desc')
            ->first();

        if ($latestPatch) {
            return response()->json([
                'version' => $latestPatch->version,
                'version_code' => $latestPatch->version_code,
                'updateType' => $latestPatch->update_type,
                'size_hint' => $latestPatch->size_hint,
                'changelog' => $latestPatch->changelog,
                'is_mandatory' => $latestPatch->is_mandatory,
                'isPatch' => $latestPatch->update_type === 'patch',
                'isApk' => $latestPatch->update_type === 'apk',
                'updated_at' => $latestPatch->updated_at,
            ]);
        }

        return response()->json([
            'version' => '1.0.0',
            'version_code' => 1,
            'updateType' => 'patch',
            'size_hint' => null,
            'changelog' => null,
            'is_mandatory' => false,
            'isPatch' => true,
            'isApk' => false,
            'updated_at' => null,
        ]);
    }
}
