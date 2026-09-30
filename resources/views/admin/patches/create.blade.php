<x-admin.layouts.app>
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <span class="page-label">// App Updates</span>
            <h1 class="page-title">Upload Update Baru</h1>
            <p class="page-subtitle">Pilih tipe update dan upload file untuk aplikasi mobile</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.patches.index') }}" class="btn btn-secondary">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali
            </a>
        </div>
    </div>

    <!-- Alerts -->
    @if($errors->any())
        <div class="alert alert-danger mb-6">
            <svg class="alert-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <div class="alert-message">
                <strong>Terjadi kesalahan:</strong>
                <ul class="mt-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <form action="{{ route('admin.patches.store') }}" method="POST" enctype="multipart/form-data" id="patchForm">
        @csrf

        <!-- Grid Layout: 2 Columns -->
        <div class="grid gap-6 lg:grid-cols-3">

            <!-- Left Column: Tipe Update, Info, File -->
            <div class="lg:col-span-2 space-y-6">

                <!-- Tipe Update -->
                <div class="card">
                    <div class="card-header">
                        <div class="flex items-center gap-3">
                            <div class="stat-icon amber" style="width: 36px; height: 36px;">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="card-title">Tipe Update</h3>
                                <p class="text-sm text-muted">Pilih jenis update yang akan dirilis</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="radio-group">
                            <label class="radio-item selected" id="radioPatch">
                                <input type="radio" name="update_type" value="patch" id="inputPatch" checked>
                                <div class="radio-icon amber">
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                </div>
                                <div class="radio-content">
                                    <span class="radio-title">Patch Update</span>
                                    <span class="radio-desc">Hot Code Push (~100KB-2MB)</span>
                                </div>
                                <div class="radio-check">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                            </label>
                            <label class="radio-item" id="radioApk">
                                <input type="radio" name="update_type" value="apk" id="inputApk">
                                <div class="radio-icon blue">
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                    </svg>
                                </div>
                                <div class="radio-content">
                                    <span class="radio-title">Full APK</span>
                                    <span class="radio-desc">Update Seluruh Aplikasi (~20MB-50MB+)</span>
                                </div>
                                <div class="radio-check">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Informasi Update -->
                <div class="card">
                    <div class="card-header">
                        <div class="flex items-center gap-3">
                            <div class="stat-icon cyan" style="width: 36px; height: 36px;">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="card-title">Informasi Update</h3>
                                <p class="text-sm text-muted">Versi dan detail update</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="form-group">
                                <label class="form-label">Versi <span class="text-danger">*</span></label>
                                <input type="text" name="version" class="form-input" placeholder="Contoh: 2.0.1" value="{{ old('version') }}" required>
                                <p class="text-xs text-muted mt-1">Format: major.minor.patch</p>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Version Code <span class="text-danger">*</span></label>
                                <input type="number" name="version_code" class="form-input" placeholder="1" value="{{ old('version_code', $nextVersionCode) }}" required min="1">
                                <p class="text-xs text-muted mt-1">ID unik per APK release</p>
                            </div>
                        </div>

                        <!-- Patch Count - Only for PATCH type -->
                        <div class="grid grid-cols-2 gap-4 hidden" id="patchCountSection">
                            <div class="form-group">
                                <label class="form-label">Patch Count <span class="text-danger">*</span></label>
                                <input type="number" name="patch_count" class="form-input" placeholder="1" value="{{ old('patch_count', $nextPatchCount ?? 1) }}" min="1">
                                <p class="text-xs text-muted mt-1">Patch ke berapa untuk versi ini</p>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Build Number</label>
                                <input type="text" class="form-input" value="Auto (untuk APK)" disabled>
                                <p class="text-xs text-muted mt-1">Tidak digunakan untuk patch</p>
                            </div>
                        </div>

                        <!-- Build Number - Only for APK type -->
                        <div class="grid grid-cols-2 gap-4" id="buildNumberSection">
                            <div class="form-group">
                                <label class="form-label">Build Number</label>
                                <input type="number" class="form-input" value="{{ $nextBuildNumber }}" disabled>
                                <p class="text-xs text-muted mt-1">Counter global (auto-increment)</p>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Patch Count</label>
                                <input type="text" class="form-input" value="0 (untuk APK)" disabled>
                                <p class="text-xs text-muted mt-1">Tidak digunakan untuk APK</p>
                            </div>
                        </div>
                        <div class="form-group mb-0">
                            <label class="form-label">Changelog</label>
                            <textarea name="changelog" class="form-textarea" rows="3" placeholder="Contoh:
- Perbaikan bug login
- Tambah fitur notifikasi">{{ old('changelog') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- File Patch -->
                <div class="card" id="patchFileSection">
                    <div class="card-header">
                        <div class="flex items-center gap-3">
                            <div class="stat-icon amber" style="width: 36px; height: 36px;">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="card-title">File Patch</h3>
                                <p class="text-sm text-muted">Upload file dari flutter_patcher</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="file-upload-wrapper" id="patchDropZone">
                            <input type="file" name="file" id="patchFileInput" accept=".zip,.patch,.apk,.bz2,.tar,.tar.gz,.tgz">
                            <label for="patchFileInput" class="file-upload-label">
                                <div class="file-upload-icon">
                                    <svg class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                </div>
                                <span class="file-upload-text">Klik atau drag file ke sini</span>
                                <span class="file-upload-hint">.zip, .patch, .apk, .bz2, .tar</span>
                            </label>
                        </div>
                        <div class="file-info-box" id="patchFilePreview">
                            <svg class="w-5 h-5" style="color: var(--success)" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div class="file-info-content">
                                <span class="file-info-name" id="patchFileName"></span>
                                <span class="file-info-size" id="patchFileSize"></span>
                            </div>
                            <button type="button" class="file-info-remove" id="patchFileRemove">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- File APK -->
                <div class="card hidden" id="apkFileSection">
                    <div class="card-header">
                        <div class="flex items-center gap-3">
                            <div class="stat-icon blue" style="width: 36px; height: 36px;">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="card-title">File APK</h3>
                                <p class="text-sm text-muted">Upload file .apk atau masukkan URL</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="file-upload-wrapper" id="apkDropZone">
                            <input type="file" name="apk_file" id="apkFileInput" accept=".apk,.zip">
                            <label for="apkFileInput" class="file-upload-label">
                                <div class="file-upload-icon">
                                    <svg class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                    </svg>
                                </div>
                                <span class="file-upload-text">Klik atau drag file ke sini</span>
                                <span class="file-upload-hint">.apk, .zip</span>
                            </label>
                        </div>
                        <div class="file-info-box" id="apkFilePreview">
                            <svg class="w-5 h-5" style="color: var(--success)" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div class="file-info-content">
                                <span class="file-info-name" id="apkFileName"></span>
                                <span class="file-info-size" id="apkFileSize"></span>
                            </div>
                            <button type="button" class="file-info-remove" id="apkFileRemove">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>

                        <div class="flex items-center gap-3 my-4">
                            <div class="flex-1 border-t border-dashed" style="border-color: var(--border)"></div>
                            <span class="text-sm text-muted px-2">atau</span>
                            <div class="flex-1 border-t border-dashed" style="border-color: var(--border)"></div>
                        </div>

                        <div class="form-group mb-0">
                            <label class="form-label">URL APK Eksternal</label>
                            <input type="url" name="apk_url" id="apkUrlInput" class="form-input" placeholder="https://cdn.example.com/silatar-v2.1.0.apk" value="{{ old('apk_url') }}">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Settings, Version, Buttons, Tips -->
            <div class="lg:col-span-1 space-y-6">

                <!-- Pengaturan -->
                <div class="card">
                    <div class="card-header">
                        <div class="flex items-center gap-3">
                            <div class="stat-icon violet" style="width: 36px; height: 36px;">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="card-title">Pengaturan</h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-body space-y-3">
                        <label class="flex items-start gap-3 cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" checked class="form-checkbox mt-1">
                            <div>
                                <span class="text-sm font-medium text-primary">Aktifkan Update</span>
                                <p class="text-xs text-muted mt-1">Update akan tersedia untuk user</p>
                            </div>
                        </label>
                        <label class="flex items-start gap-3 cursor-pointer">
                            <input type="checkbox" name="is_mandatory" value="1" class="form-checkbox mt-1">
                            <div>
                                <span class="text-sm font-medium text-primary">Mandatory Update</span>
                                <p class="text-xs text-muted mt-1">User harus update sebelum pakai app</p>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Batasan Versi -->
                <div class="card">
                    <div class="card-header">
                        <div class="flex items-center gap-3">
                            <div class="stat-icon rose" style="width: 36px; height: 36px;">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="card-title">Batasan Versi</h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-body space-y-4">
                        <div class="form-group">
                            <label class="form-label">Min App Version</label>
                            <input type="text" name="min_app_version" class="form-input" placeholder="Contoh: 2.0.0" value="{{ old('min_app_version') }}">
                            <p class="text-xs text-muted mt-1">Patch hanya untuk app >= versi ini</p>
                        </div>
                        <div class="form-group mb-0">
                            <label class="form-label">Max App Version</label>
                            <input type="text" name="max_app_version" class="form-input" placeholder="Kosongkan = tidak terbatas" value="{{ old('max_app_version') }}">
                            <p class="text-xs text-muted mt-1">Patch hanya untuk app <= versi ini</p>
                        </div>
                    </div>
                </div>

                <!-- Tips -->
                <div class="tips-box">
                    <div class="tips-box-header">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Tips
                    </div>
                    <ul class="tips-box-list">
                        <li>Gunakan <strong>Patch</strong> untuk update kecil</li>
                        <li>Gunakan <strong>APK</strong> untuk native changes</li>
                        <li>Version code harus > dari sebelumnya</li>
                    </ul>
                </div>

                <!-- Submit Buttons -->
                <div class="flex flex-col gap-3">
                    <button type="submit" class="btn btn-primary w-full">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                        </svg>
                        Upload Update
                    </button>
                    <a href="{{ route('admin.patches.index') }}" class="btn btn-secondary w-full">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Batal
                    </a>
                </div>
            </div>
        </div>
    </form>

    @push('styles')
    <style>
        /* Radio Group - Horizontal Layout */
        .radio-group {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
        }
        .radio-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 16px;
            border: 2px solid var(--border);
            border-radius: var(--radius);
            background: var(--card);
            cursor: pointer;
            transition: all 0.2s;
        }
        .radio-item:hover {
            border-color: var(--primary);
            background: var(--primary-50);
        }
        .radio-item.selected {
            border-color: var(--primary);
            background: var(--primary-50);
            box-shadow: 0 0 0 3px rgba(200, 154, 43, 0.15);
        }
        .radio-item input[type="radio"] {
            display: none;
        }
        .radio-icon {
            width: 44px;
            height: 44px;
            border-radius: var(--radius);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: all 0.2s;
        }
        .radio-icon.amber {
            background: rgba(217, 119, 6, 0.1);
            color: #D97706;
        }
        .radio-icon.blue {
            background: rgba(59, 130, 246, 0.1);
            color: #3B82F6;
        }
        .radio-item.selected .radio-icon.amber {
            background: #D97706;
            color: white;
        }
        .radio-item.selected .radio-icon.blue {
            background: #3B82F6;
            color: white;
        }
        .radio-content {
            flex: 1;
        }
        .radio-title {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: var(--text-primary);
        }
        .radio-desc {
            display: block;
            font-size: 12px;
            color: var(--text-muted);
            margin-top: 2px;
        }
        .radio-check {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            border: 2px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: all 0.2s;
            color: white;
            opacity: 0;
        }
        .radio-item.selected .radio-check {
            background: var(--primary);
            border-color: var(--primary);
            opacity: 1;
        }
        /* File Info Box */
        .file-info-box {
            display: none;
            align-items: center;
            gap: 12px;
            padding: 14px 16px;
            background: var(--success-bg);
            border: 1px solid rgba(22, 163, 74, 0.2);
            border-radius: var(--radius);
            margin-top: 12px;
        }
        .file-info-content {
            flex: 1;
            min-width: 0;
        }
        .file-info-name {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: var(--text-primary);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .file-info-size {
            display: block;
            font-size: 11px;
            color: var(--text-muted);
        }
        .file-info-remove {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: transparent;
            border: none;
            border-radius: 50%;
            color: var(--text-muted);
            cursor: pointer;
            transition: all 0.2s;
            flex-shrink: 0;
        }
        .file-info-remove:hover {
            background: var(--danger-bg);
            color: var(--danger);
        }
        /* File Upload Wrapper */
        .file-upload-wrapper {
            position: relative;
        }
        .file-upload-wrapper input[type="file"] {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }
        .file-upload-label {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 32px 20px;
            border: 2px dashed var(--border);
            border-radius: var(--radius);
            background: var(--secondary-light);
            text-align: center;
            transition: all 0.2s;
            cursor: pointer;
        }
        .file-upload-wrapper:hover .file-upload-label {
            border-color: var(--primary);
            background: var(--primary-50);
        }
        .file-upload-wrapper.dragover .file-upload-label {
            border-color: var(--primary);
            background: var(--primary-100);
            transform: scale(1.01);
        }
        .file-upload-icon {
            width: 56px;
            height: 56px;
            border-radius: var(--radius);
            background: rgba(217, 119, 6, 0.1);
            color: #D97706;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 12px;
        }
        .file-upload-text {
            font-size: 14px;
            font-weight: 500;
            color: var(--text-primary);
            margin-bottom: 4px;
        }
        .file-upload-hint {
            font-size: 12px;
            color: var(--text-muted);
        }
        /* Tips Box */
        .tips-box {
            padding: 16px;
            background: var(--secondary-light);
            border: 1px solid var(--border);
            border-radius: var(--radius);
        }
        .tips-box-header {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 12px;
        }
        .tips-box-header svg {
            color: var(--primary);
        }
        .tips-box-list {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .tips-box-list li {
            font-size: 12px;
            color: var(--text-secondary);
            padding-left: 16px;
            position: relative;
        }
        .tips-box-list li::before {
            content: "";
            position: absolute;
            left: 0;
            top: 6px;
            width: 4px;
            height: 4px;
            border-radius: 50%;
            background: var(--text-muted);
        }
        .tips-box-list li strong {
            color: var(--text-primary);
            font-weight: 600;
        }
        /* Utility */
        .my-4 {
            margin-top: 16px;
            margin-bottom: 16px;
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Format Bytes
            function formatBytes(bytes) {
                if (bytes === 0) return '0 Bytes';
                var k = 1024;
                var sizes = ['Bytes', 'KB', 'MB', 'GB'];
                var i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
            }

            // Update Type Selection
            var radioPatch = document.getElementById('radioPatch');
            var radioApk = document.getElementById('radioApk');
            var patchFileSection = document.getElementById('patchFileSection');
            var apkFileSection = document.getElementById('apkFileSection');

	            function selectPatch() {
	                radioPatch.classList.add('selected');
	                radioApk.classList.remove('selected');
	                patchFileSection.classList.remove('hidden');
	                apkFileSection.classList.add('hidden');

	                // Show Patch Count section, Hide Build Number section
	                var patchCountSection = document.getElementById('patchCountSection');
	                var buildNumberSection = document.getElementById('buildNumberSection');
	                if (patchCountSection) patchCountSection.classList.remove('hidden');
	                if (buildNumberSection) buildNumberSection.classList.add('hidden');

	                // Make patch_count required
	                var patchCountInput = document.querySelector('input[name="patch_count"]');
	                if (patchCountInput) {
	                    patchCountInput.required = true;
	                }
	            }

	            function selectApk() {
	                radioApk.classList.add('selected');
	                radioPatch.classList.remove('selected');
	                patchFileSection.classList.add('hidden');
	                apkFileSection.classList.remove('hidden');

	                // Hide Patch Count section, Show Build Number section
	                var patchCountSection = document.getElementById('patchCountSection');
	                var buildNumberSection = document.getElementById('buildNumberSection');
	                if (patchCountSection) patchCountSection.classList.add('hidden');
	                if (buildNumberSection) buildNumberSection.classList.remove('hidden');

	                // Make patch_count not required
	                var patchCountInput = document.querySelector('input[name="patch_count"]');
	                if (patchCountInput) {
	                    patchCountInput.required = false;
	                }
	            }

            radioPatch.addEventListener('click', function() {
                document.getElementById('inputPatch').checked = true;
                selectPatch();
            });

            radioApk.addEventListener('click', function() {
                document.getElementById('inputApk').checked = true;
                selectApk();
            });

            // Initialize
            if (document.getElementById('inputPatch').checked) {
                selectPatch();
            } else {
                selectApk();
            }

            // Patch File Upload
            var patchFileInput = document.getElementById('patchFileInput');
            var patchFilePreview = document.getElementById('patchFilePreview');
            var patchFileName = document.getElementById('patchFileName');
            var patchFileSize = document.getElementById('patchFileSize');
            var patchFileRemove = document.getElementById('patchFileRemove');

            patchFileInput.addEventListener('change', function() {
                var file = this.files[0];
                if (file) {
                    patchFileName.textContent = file.name;
                    patchFileSize.textContent = formatBytes(file.size);
                    patchFilePreview.style.display = 'flex';
                } else {
                    patchFilePreview.style.display = 'none';
                }
            });

            patchFileRemove.addEventListener('click', function() {
                patchFileInput.value = '';
                patchFilePreview.style.display = 'none';
                patchFileName.textContent = '';
                patchFileSize.textContent = '';
            });

            // APK File Upload
            var apkFileInput = document.getElementById('apkFileInput');
            var apkFilePreview = document.getElementById('apkFilePreview');
            var apkFileName = document.getElementById('apkFileName');
            var apkFileSize = document.getElementById('apkFileSize');
            var apkFileRemove = document.getElementById('apkFileRemove');

            apkFileInput.addEventListener('change', function() {
                var file = this.files[0];
                if (file) {
                    apkFileName.textContent = file.name;
                    apkFileSize.textContent = formatBytes(file.size);
                    apkFilePreview.style.display = 'flex';
                } else {
                    apkFilePreview.style.display = 'none';
                }
            });

            apkFileRemove.addEventListener('click', function() {
                apkFileInput.value = '';
                apkFilePreview.style.display = 'none';
                apkFileName.textContent = '';
                apkFileSize.textContent = '';
            });
        });
    </script>
    @endpush
</x-admin.layouts.app>
