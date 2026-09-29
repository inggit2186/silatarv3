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

        // Get latest active patch
        $latestPatch = AppPatch::where('is_active', true)
            ->where('version_code', '>', $currentVersionCode)
            ->where(function ($query) use ($currentVersion) {
                // Check min app version constraint
                $query->whereNull('min_app_version')
                    ->orWhere('min_app_version', '<=', $currentVersion);
            })
            ->where(function ($query) use ($currentVersion) {
                // Check max app version constraint
                $query->whereNull('max_app_version')
                    ->orWhere('max_app_version', '>=', $currentVersion);
            })
            ->orderBy('version_code', 'desc')
            ->first();

        if ($latestPatch) {
            return response()->json([
                'hasUpdate' => true,
                'needUpdate' => true,
                'latestVersion' => $latestPatch->version,
                'version_code' => $latestPatch->version_code,
                'downloadUrl' => url('/api/patch/download/' . $latestPatch->id),
                'patchUrl' => url('/api/patch/download/' . $latestPatch->id),
                'md5' => $latestPatch->md5,
                'fileSize' => $latestPatch->file_size,
                'changelog' => $latestPatch->changelog,
                'isMandatory' => $latestPatch->is_mandatory,
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
                'download_url' => url('/api/patch/download/' . $latestPatch->id),
                'changelog' => $latestPatch->changelog,
                'is_mandatory' => $latestPatch->is_mandatory,
            ]);
        }

        return response()->json([
            'version' => '1.0.0',
            'version_code' => 1,
            'download_url' => null,
            'changelog' => '',
            'is_mandatory' => false,
        ]);
    }

    /**
     * Download patch file
     * GET /api/patch/download/{id}
     */
    public function download(int $id)
    {
        $patch = AppPatch::find($id);

        if (!$patch || !$patch->is_active) {
            abort(404, 'Patch not found or inactive');
        }

        $filePath = storage_path('app/' . $patch->file_path);

        if (!file_exists($filePath)) {
            abort(404, 'Patch file not found');
        }

        return response()->download($filePath, $patch->file_name, [
            'Content-Type' => 'application/octet-stream',
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
            'file' => 'required|file|mimes:zip,patch,bz2|max:51200', // max 50MB
            'changelog' => 'nullable|string',
            'is_mandatory' => 'boolean',
            'is_active' => 'boolean',
            'min_app_version' => 'nullable|string|max:20',
            'max_app_version' => 'nullable|string|max:20',
        ]);

        // Handle file upload
        $file = $request->file('file');
        $fileName = $file->getClientOriginalName();
        $fileSize = $file->getSize();

        // Create patches directory if not exists
        $patchesDir = storage_path('app/patches');
        if (!File::isDirectory($patchesDir)) {
            File::makeDirectory($patchesDir, 0755, true);
        }

        // Store file
        $filePath = $file->storeAs('patches', $fileName);

        // Calculate MD5
        $md5 = hash_file('md5', storage_path('app/' . $filePath));

        // Create patch record
        $patch = AppPatch::create([
            'version' => $validated['version'],
            'version_code' => $validated['version_code'],
            'file_name' => $fileName,
            'file_path' => $filePath,
            'file_size' => $fileSize,
            'md5' => $md5,
            'changelog' => $validated['changelog'] ?? null,
            'is_mandatory' => $validated['is_mandatory'] ?? false,
            'is_active' => $validated['is_active'] ?? true,
            'min_app_version' => $validated['min_app_version'] ?? null,
            'max_app_version' => $validated['max_app_version'] ?? null,
        ]);

        return response()->json([
            'message' => 'Patch created successfully',
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
            'version' => 'sometimes|string|max:20|unique:app_patches,version,' . $id,
            'version_code' => 'sometimes|integer|min:1',
            'file' => 'nullable|file|mimes:zip,patch,bz2|max:51200',
            'changelog' => 'nullable|string',
            'is_mandatory' => 'boolean',
            'is_active' => 'boolean',
            'min_app_version' => 'nullable|string|max:20',
            'max_app_version' => 'nullable|string|max:20',
        ]);

        // Handle new file upload if provided
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = $file->getClientOriginalName();
            $fileSize = $file->getSize();

            // Delete old file
            $oldFilePath = storage_path('app/' . $patch->file_path);
            if (file_exists($oldFilePath)) {
                unlink($oldFilePath);
            }

            // Store new file
            $filePath = $file->storeAs('patches', $fileName);

            $validated['file_name'] = $fileName;
            $validated['file_path'] = $filePath;
            $validated['file_size'] = $fileSize;
            $validated['md5'] = hash_file('md5', storage_path('app/' . $filePath));
        }

        $patch->update($validated);

        return response()->json([
            'message' => 'Patch updated successfully',
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
                'changelog' => $latestPatch->changelog,
                'is_mandatory' => $latestPatch->is_mandatory,
                'updated_at' => $latestPatch->updated_at,
            ]);
        }

        return response()->json([
            'version' => '1.0.0',
            'version_code' => 1,
            'changelog' => null,
            'is_mandatory' => false,
            'updated_at' => null,
        ]);
    }
}
