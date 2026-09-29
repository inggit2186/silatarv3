<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppPatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
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

        $query = AppPatch::query()->orderBy('version_code', 'desc');

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('version', 'like', "%{$search}%")
                    ->orWhere('changelog', 'like', "%{$search}%");
            });
        }

        $patches = $query->paginate(10);
        $latestPatch = AppPatch::where('is_active', true)->orderBy('version_code', 'desc')->first();

        return view('admin.patches.index', compact('patches', 'latestPatch', 'isAdmin'));
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

        $latestPatch = AppPatch::where('is_active', true)->orderBy('version_code', 'desc')->first();
        $nextVersionCode = $latestPatch ? $latestPatch->version_code + 1 : 1;

        return view('admin.patches.create', compact('latestPatch', 'nextVersionCode', 'isAdmin'));
    }

    /**
     * Store new patch
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        $isAdmin = in_array($user->role, ['admin', 'superadmin', 'kepala']);

        if (!$isAdmin) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        // Log request info for debugging
        \Log::info('Patch upload request', [
            'has_file' => $request->hasFile('file'),
            'file_size' => $request->hasFile('file') ? $request->file('file')->getSize() : 0,
            'version' => $request->input('version'),
            'version_code' => $request->input('version_code'),
        ]);

        $validated = $request->validate([
            'version' => 'required|string|max:20',
            'version_code' => 'required|integer|min:1|unique:app_patches,version_code',
            'file' => 'nullable|file|mimes:zip,patch,bz2,tar,tar.gz,tgz,apk|max:204800', // max 200MB
            'apk_file' => 'nullable|file|mimes:apk,zip|max:204800', // max 200MB
            'apk_url' => 'nullable|url|max:500',
            'update_type' => 'nullable|in:patch,apk',
            'changelog' => 'nullable|string|max:5000',
            'is_mandatory' => 'boolean',
            'is_active' => 'boolean',
            'min_app_version' => 'nullable|string|max:20',
            'max_app_version' => 'nullable|string|max:20',
        ], [
            'file.mimes' => 'Format file tidak valid. Gunakan: zip, patch, bz2, tar, tar.gz, tgz, apk',
            'file.max' => 'Ukuran file terlalu besar. Maksimal 200MB',
            'apk_file.mimes' => 'Format file tidak valid. Gunakan: apk, zip',
            'apk_file.max' => 'Ukuran file terlalu besar. Maksimal 200MB',
        ]);

        DB::beginTransaction();

        try {
            // Determine which file input was used (patch or full APK)
            $uploadedFile = $request->file('apk_file') ?? $request->file('file');

            if (!$uploadedFile) {
                throw new \Exception('File upload diperlukan');
            }

            $fileName = $uploadedFile->getClientOriginalName();
            $fileSize = $uploadedFile->getSize();

            // Determine update type
            $updateType = $request->input('update_type', 'patch');

            // Create patches directory in storage/app
            $patchesDir = storage_path('app/patches');
            if (!File::isDirectory($patchesDir)) {
                File::makeDirectory($patchesDir, 0755, true);
            }

            // Store file with unique name
            $uniqueName = time() . '_' . $fileName;
            $fullPath = $patchesDir . '/' . $uniqueName;

            // Move uploaded file to storage
            $uploadedFile->move($patchesDir, $uniqueName);

            // Calculate MD5
            $md5 = hash_file('md5', $fullPath);

            // Create patch record
            $patch = AppPatch::create([
                'version' => $validated['version'],
                'version_code' => $validated['version_code'],
                'file_name' => $uniqueName,
                'file_path' => 'patches/' . $uniqueName,
                'file_size' => $fileSize,
                'md5' => $md5,
                'changelog' => $validated['changelog'] ?? null,
                'is_mandatory' => $request->boolean('is_mandatory'),
                'is_active' => $request->boolean('is_active', true),
                'min_app_version' => $validated['min_app_version'] ?? null,
                'max_app_version' => $validated['max_app_version'] ?? null,
                'update_type' => $updateType,
                'apk_url' => $validated['apk_url'] ?? null,
            ]);

            DB::commit();

            return redirect()
                ->route('admin.patches.index')
                ->with('success', "Patch v{$patch->version} berhasil diupload!");
        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Patch upload failed', ['error' => $e->getMessage()]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal upload patch: ' . $e->getMessage());
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
            'version_code' => 'sometimes|integer|min:1|unique:app_patches,version_code,' . $id,
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

            // Handle new file upload if provided
            if ($request->hasFile('file')) {
                // Delete old file
                $oldFilePath = storage_path('app/' . $patch->file_path);
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }

                $file = $request->file('file');
                $fileName = $file->getClientOriginalName();
                $fileSize = $file->getSize();

                $uniqueName = time() . '_' . $fileName;
                $filePath = $file->storeAs('patches', $uniqueName);

                $updateData['file_name'] = $uniqueName;
                $updateData['file_path'] = $filePath;
                $updateData['file_size'] = $fileSize;
                $updateData['md5'] = hash_file('md5', storage_path('app/' . $filePath));
            }

            // Update version if changed
            if (isset($validated['version'])) {
                $updateData['version'] = $validated['version'];
            }
            if (isset($validated['version_code'])) {
                $updateData['version_code'] = $validated['version_code'];
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
            // Delete file
            $filePath = storage_path('app/' . $patch->file_path);
            if (file_exists($filePath)) {
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

        return response()->download($filePath, $patch->file_name, [
            'Content-Type' => 'application/octet-stream',
        ]);
    }
}
