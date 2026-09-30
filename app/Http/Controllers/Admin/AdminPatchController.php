<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppPatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class AdminPatchController extends Controller
{
    /**
     * Display list of patches
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $isAdmin = in_array($user->role, ['admin', 'superadmin', 'kepala']);

        if (!$isAdmin) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        $query = AppPatch::query()->orderBy('version_code', 'desc')
            ->orderBy('patch_count', 'desc');

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('version', 'like', "%{$search}%")
                    ->orWhere('changelog', 'like', "%{$search}%");
            });
        }

        if ($request->has('type')) {
            $query->where('update_type', $request->input('type'));
        }

        $patches = $query->paginate(10);
        $latestApk = AppPatch::getLatestApk();
        $latestPatch = AppPatch::where('is_active', true)
            ->where('update_type', 'patch')
            ->orderBy('version_code', 'desc')
            ->orderBy('patch_count', 'desc')
            ->first();

        return view('admin.patches.index', compact('patches', 'latestApk', 'latestPatch', 'isAdmin'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        $user = auth()->user();
        $isAdmin = in_array($user->role, ['admin', 'superadmin', 'kepala']);

        if (!$isAdmin) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        $latestApk = AppPatch::getLatestApk();
        $nextVersionCode = $latestApk ? $latestApk->version_code + 1 : 1;
        $nextBuildNumber = AppPatch::getNextBuildNumber();

        // Get next patch count for the latest version
        $latestPatch = AppPatch::where('is_active', true)
            ->where('update_type', 'patch')
            ->where('version_code', $latestApk ? $latestApk->version_code : 1)
            ->orderBy('patch_count', 'desc')
            ->first();
        $nextPatchCount = $latestPatch ? $latestPatch->patch_count + 1 : 1;

        return view('admin.patches.create', compact('latestApk', 'nextVersionCode', 'nextBuildNumber', 'nextPatchCount', 'isAdmin'));
    }

    /**
     * Store new patch
     *
     * New Hybrid Versioning:
     * - For APK: patch_count = 0, build_number auto-increment
     * - For Patch: patch_count required (> 0), build_number not used
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        $isAdmin = in_array($user->role, ['admin', 'superadmin', 'kepala']);

        if (!$isAdmin) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        $updateType = $request->input('update_type', 'patch');

        // Validation based on update type
        $rules = [
            'version' => 'required|string|max:20',
            'version_code' => 'required|integer|min:1',
            'update_type' => 'nullable|in:patch,apk',
            'apk_file' => 'nullable|file|mimes:apk,zip|max:204800',
            'apk_url' => 'nullable|url|max:500',
            'changelog' => 'nullable|string|max:5000',
            'is_mandatory' => 'boolean',
            'is_active' => 'boolean',
            'min_app_version' => 'nullable|string|max:20',
            'max_app_version' => 'nullable|string|max:20',
        ];

        if ($updateType === 'patch') {
            $rules['file'] = 'required|file|mimes:zip,patch,bz2,tar,tar.gz,tgz|max:51200'; // 50MB for patch
            $rules['patch_count'] = 'required|integer|min:1';
        } else {
            $rules['file'] = 'nullable|file|mimes:apk,zip|max:204800'; // 200MB for APK
            $rules['apk_file'] = 'nullable|file|mimes:apk,zip|max:204800';
        }

        $validated = $request->validate($rules, [
            'file.mimes' => 'Format file tidak valid.',
            'file.max' => 'Ukuran file terlalu besar.',
            'apk_file.mimes' => 'Format file tidak valid. Gunakan: apk, zip',
            'apk_file.max' => 'Ukuran file terlalu besar. Maksimal 200MB',
        ]);

        DB::beginTransaction();

        try {
            $uploadedFile = $request->file('apk_file') ?? $request->file('file');

            $fileName = null;
            $fileSize = 0;
            $filePath = null;
            $md5 = null;
            $sizeHint = null;
            $patchCount = 0;
            $buildNumber = null;

            if ($uploadedFile) {
                $fileName = $uploadedFile->getClientOriginalName();
                $fileSize = $uploadedFile->getSize();

                $patchesDir = storage_path('app/patches');
                if (!File::isDirectory($patchesDir)) {
                    File::makeDirectory($patchesDir, 0755, true);
                }

                $uniqueName = time() . '_' . $fileName;
                $fullPath = $patchesDir . '/' . $uniqueName;
                $uploadedFile->move($patchesDir, $uniqueName);

                $filePath = 'patches/' . $uniqueName;
                $md5 = hash_file('md5', $fullPath);
                $sizeHint = $this->_formatFileSize($fileSize);
            }

            if ($updateType === 'patch') {
                $patchCount = (int) $validated['patch_count'];

                // Check if patch already exists for this version_code + patch_count
                $existingPatch = AppPatch::where('version_code', $validated['version_code'])
                    ->where('patch_count', $patchCount)
                    ->where('update_type', 'patch')
                    ->first();

                if ($existingPatch) {
                    DB::rollBack();
                    return redirect()
                        ->back()
                        ->withInput()
                        ->with('error', "Patch dengan version_code {$validated['version_code']} dan patch_count {$patchCount} sudah ada!");
                }
            } else {
                // APK - auto-increment build_number
                $buildNumber = AppPatch::getNextBuildNumber();

                // Check if APK version already exists
                $existingApk = AppPatch::where('version_code', $validated['version_code'])
                    ->where('update_type', 'apk')
                    ->first();

                if ($existingApk) {
                    DB::rollBack();
                    return redirect()
                        ->back()
                        ->withInput()
                        ->with('error', "APK dengan version_code {$validated['version_code']} sudah ada!");
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
                'changelog' => $validated['changelog'] ?? null,
                'is_mandatory' => $request->boolean('is_mandatory'),
                'is_active' => $request->boolean('is_active', true),
                'min_app_version' => $validated['min_app_version'] ?? null,
                'max_app_version' => $validated['max_app_version'] ?? null,
                'apk_url' => $updateType === 'apk' ? ($validated['apk_url'] ?? $filePath) : null,
                'size_hint' => $sizeHint,
            ]);

            DB::commit();

            $typeLabel = $updateType === 'apk' ? 'APK' : 'Patch';
            return redirect()
                ->route('admin.patches.index')
                ->with('success', "{$typeLabel} v{$patch->full_version} berhasil diupload!");
        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Patch upload failed', ['error' => $e->getMessage()]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal upload: ' . $e->getMessage());
        }
    }

    /**
     * Show patch details
     */
    public function show(int $id)
    {
        $user = auth()->user();
        $isAdmin = in_array($user->role, ['admin', 'superadmin', 'kepala']);

        if (!$isAdmin) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        $patch = AppPatch::findOrFail($id);

        return view('admin.patches.show', compact('patch', 'isAdmin'));
    }

    /**
     * Show edit form
     */
    public function edit(int $id)
    {
        $user = auth()->user();
        $isAdmin = in_array($user->role, ['admin', 'superadmin', 'kepala']);

        if (!$isAdmin) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        $patch = AppPatch::findOrFail($id);

        return view('admin.patches.edit', compact('patch', 'isAdmin'));
    }

    /**
     * Update patch
     */
    public function update(Request $request, int $id)
    {
        $user = auth()->user();
        $isAdmin = in_array($user->role, ['admin', 'superadmin', 'kepala']);

        if (!$isAdmin) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        $patch = AppPatch::findOrFail($id);

        $validated = $request->validate([
            'version' => 'sometimes|string|max:20',
            'version_code' => 'sometimes|integer|min:1',
            'patch_count' => 'nullable|integer|min:0',
            'file' => 'nullable|file|mimes:zip,patch,bz2,tar,tar.gz,tgz|max:102400',
            'changelog' => 'nullable|string|max:5000',
            'is_mandatory' => 'boolean',
            'is_active' => 'boolean',
            'min_app_version' => 'nullable|string|max:20',
            'max_app_version' => 'nullable|string|max:20',
        ]);

        DB::beginTransaction();

        try {
            $updateData = [
                'changelog' => $validated['changelog'] ?? null,
                'is_mandatory' => $request->boolean('is_mandatory'),
                'is_active' => $request->boolean('is_active'),
                'min_app_version' => $validated['min_app_version'] ?? null,
                'max_app_version' => $validated['max_app_version'] ?? null,
            ];

            if (isset($validated['version'])) {
                $updateData['version'] = $validated['version'];
            }
            if (isset($validated['version_code'])) {
                $updateData['version_code'] = $validated['version_code'];
            }
            if (isset($validated['patch_count'])) {
                $updateData['patch_count'] = $validated['patch_count'];
            }

            if ($request->hasFile('file')) {
                $oldFilePath = storage_path('app/' . $patch->file_path);
                if (file_exists($oldFilePath) && strpos($patch->file_path, 'patches/apk') === false) {
                    unlink($oldFilePath);
                }

                $file = $request->file('file');
                $fileName = $file->getClientOriginalName();
                $fileSize = $file->getSize();

                $patchesDir = storage_path('app/patches');
                if (!File::isDirectory($patchesDir)) {
                    File::makeDirectory($patchesDir, 0755, true);
                }

                $uniqueName = time() . '_' . $fileName;
                $fullPath = $patchesDir . '/' . $uniqueName;
                $file->move($patchesDir, $uniqueName);

                $updateData['file_name'] = $uniqueName;
                $updateData['file_path'] = 'patches/' . $uniqueName;
                $updateData['file_size'] = $fileSize;
                $updateData['md5'] = hash_file('md5', $fullPath);
                $updateData['size_hint'] = $this->_formatFileSize($fileSize);
            }

            $patch->update($updateData);

            DB::commit();

            return redirect()
                ->route('admin.patches.show', $patch->id)
                ->with('success', 'Patch berhasil diupdate!');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal update patch: ' . $e->getMessage());
        }
    }

    /**
     * Delete patch
     */
    public function destroy(int $id)
    {
        $user = auth()->user();
        $isAdmin = in_array($user->role, ['admin', 'superadmin', 'kepala']);

        if (!$isAdmin) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        $patch = AppPatch::findOrFail($id);

        DB::beginTransaction();

        try {
            $filePath = storage_path('app/' . $patch->file_path);
            if (file_exists($filePath) && strpos($patch->file_path, 'patches/apk') === false) {
                unlink($filePath);
            }

            $patch->delete();

            DB::commit();

            return redirect()
                ->route('admin.patches.index')
                ->with('success', 'Patch berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->with('error', 'Gagal hapus patch: ' . $e->getMessage());
        }
    }

    /**
     * Toggle patch active status
     */
    public function toggleActive(int $id)
    {
        $user = auth()->user();
        $isAdmin = in_array($user->role, ['admin', 'superadmin', 'kepala']);

        if (!$isAdmin) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        $patch = AppPatch::findOrFail($id);
        $patch->update(['is_active' => !$patch->is_active]);

        return redirect()
            ->back()
            ->with('success', "Patch " . ($patch->is_active ? 'diaktifkan' : 'dinonaktifkan') . "!");
    }

    /**
     * Download patch file (for admin testing)
     */
    public function download(int $id)
    {
        $user = auth()->user();
        $isAdmin = in_array($user->role, ['admin', 'superadmin', 'kepala']);

        if (!$isAdmin) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        $patch = AppPatch::findOrFail($id);
        $filePath = storage_path('app/' . $patch->file_path);

        if (!file_exists($filePath)) {
            return redirect()
                ->back()
                ->with('error', 'File patch tidak ditemukan!');
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
     * Format file size helper
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
