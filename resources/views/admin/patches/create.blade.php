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

    <form action="{{ route('admin.patches.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Form -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Update Type Selection -->
                <div class="card">
                    <div class="card-header">
                        <div class="flex items-center gap-3">
                            <div class="stat-icon amber">
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
                        <div class="grid grid-cols-2 gap-4">
                            <!-- Patch Type Card -->
                            <label class="update-type-card cursor-pointer" data-type="patch">
                                <input type="radio" name="update_type" value="patch" {{ old('update_type', 'patch') == 'patch' ? 'checked' : '' }} onchange="toggleUpdateType('patch')">
                                <div class="type-card-inner {{ old('update_type', 'patch') == 'patch' ? 'selected' : '' }}">
                                    <div class="type-card-header">
                                        <div class="type-icon amber">
                                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="type-title">⚡ Patch Update</h4>
                                            <p class="type-desc">Hot Code Push - Kode Dart saja</p>
                                        </div>
                                        <div class="type-radio">
                                            <div class="radio-dot"></div>
                                        </div>
                                    </div>
                                    <div class="type-features">
                                        <span class="feature-badge amber">
                                            <span class="font-semibold">~</span> 100KB-2MB
                                        </span>
                                        <span class="feature-badge amber">
                                            <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                            </svg>
                                            Instant Apply
                                        </span>
                                        <span class="feature-badge amber">
                                            <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            Hemat Bandwidth
                                        </span>
                                    </div>
                                </div>
                            </label>

                            <!-- APK Type Card -->
                            <label class="update-type-card cursor-pointer" data-type="apk">
                                <input type="radio" name="update_type" value="apk" {{ old('update_type') == 'apk' ? 'checked' : '' }} onchange="toggleUpdateType('apk')">
                                <div class="type-card-inner {{ old('update_type') == 'apk' ? 'selected' : '' }}">
                                    <div class="type-card-header">
                                        <div class="type-icon blue">
                                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="type-title">📦 Full APK</h4>
                                            <p class="type-desc">Update Seluruh Aplikasi</p>
                                        </div>
                                        <div class="type-radio">
                                            <div class="radio-dot"></div>
                                        </div>
                                    </div>
                                    <div class="type-features">
                                        <span class="feature-badge blue">
                                            <span class="font-semibold">~</span> 20MB-50MB+
                                        </span>
                                        <span class="feature-badge blue">
                                            <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                            </svg>
                                            Install Ulang
                                        </span>
                                        <span class="feature-badge blue">
                                            <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                                            </svg>
                                            Native Code
                                        </span>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Basic Info -->
                <div class="card">
                    <div class="card-header">
                        <div class="flex items-center gap-3">
                            <div class="stat-icon cyan">
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
                    <div class="card-body space-y-4">
                        <!-- Version -->
                        <div class="form-group">
                            <label class="form-label">Versi <span class="text-danger">*</span></label>
                            <input type="text" name="version" class="form-input" placeholder="Contoh: 2.0.1" value="{{ old('version') }}" required>
                            <p class="text-xs text-muted mt-1">Format: major.minor.patch (contoh: 2.0.1)</p>
                        </div>

                        <!-- Version Code -->
                        <div class="form-group">
                            <label class="form-label">Version Code <span class="text-danger">*</span></label>
                            <input type="number" name="version_code" class="form-input" placeholder="1" value="{{ old('version_code', $nextVersionCode) }}" required min="1">
                            <p class="text-xs text-muted mt-1">
                                Angka unik untuk perbandingan.<br>
                                <span class="text-amber-600">Patch: code kecil (1, 2, 3...)</span><br>
                                <span class="text-blue-600">APK: code besar (100, 101, 102...)</span>
                            </p>
                        </div>

                        <!-- Changelog -->
                        <div class="form-group">
                            <label class="form-label">Changelog</label>
                            <textarea name="changelog" class="form-textarea" rows="3" placeholder="Contoh:
- Perbaikan bug login
- Tambah fitur notifikasi">{{ old('changelog') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- File Upload - Patch -->
                <div class="card" id="patchFileSection">
                    <div class="card-header">
                        <div class="flex items-center gap-3">
                            <div class="stat-icon amber">
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
                        <div class="file-upload-area" id="patchUploadArea">
                            <input type="file" name="file" id="patchFileInput" class="file-input-hidden" accept=".zip,.patch,.bz2,.tar,.tar.gz,.tgz" onchange="handlePatchFileSelect(this)">
                            <label for="patchFileInput" class="file-upload-label-inline">
                                <div class="flex items-center gap-4">
                                    <div class="upload-icon-box amber">
                                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-medium text-ink">Klik untuk upload file patch</p>
                                        <p class="text-sm text-muted">atau drag & drop file .zip ke sini</p>
                                    </div>
                                    <div class="flex gap-2">
                                        <span class="upload-ext">.zip</span>
                                        <span class="upload-ext">.patch</span>
                                        <span class="upload-ext">.tar.gz</span>
                                    </div>
                                </div>
                            </label>
                        </div>
                        <div id="patchFilePreview" class="hidden mt-4">
                            <div class="flex items-center gap-3 p-3 bg-amber-50 rounded-lg border border-amber-200">
                                <div class="upload-icon-box amber">
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p id="patchFileName" class="font-medium text-ink truncate"></p>
                                    <p id="patchFileSize" class="text-sm text-muted"></p>
                                </div>
                                <button type="button" onclick="removePatchFile()" class="p-2 hover:bg-amber-100 rounded-full transition-colors">
                                    <svg class="w-4 h-4 text-amber-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- File Upload - APK -->
                <div class="card hidden" id="apkFileSection">
                    <div class="card-header">
                        <div class="flex items-center gap-3">
                            <div class="stat-icon blue">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="card-title">File APK</h3>
                                <p class="text-sm text-muted">Upload file .apk atau masukkan URL eksternal</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-body space-y-4">
                        <div class="file-upload-area" id="apkUploadArea">
                            <input type="file" name="apk_file" id="apkFileInput" class="file-input-hidden" accept=".apk,.zip" onchange="handleApkFileSelect(this)">
                            <label for="apkFileInput" class="file-upload-label-inline">
                                <div class="flex items-center gap-4">
                                    <div class="upload-icon-box blue">
                                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-medium text-ink">Klik untuk upload file APK</p>
                                        <p class="text-sm text-muted">atau drag & drop file ke sini</p>
                                    </div>
                                    <div class="flex gap-2">
                                        <span class="upload-ext">.apk</span>
                                        <span class="upload-ext">.zip</span>
                                    </div>
                                </div>
                            </label>
                        </div>
                        <div id="apkFilePreview" class="hidden">
                            <div class="flex items-center gap-3 p-3 bg-blue-50 rounded-lg border border-blue-200">
                                <div class="upload-icon-box blue">
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p id="apkFileName" class="font-medium text-ink truncate"></p>
                                    <p id="apkFileSize" class="text-sm text-muted"></p>
                                </div>
                                <button type="button" onclick="removeApkFile()" class="p-2 hover:bg-blue-100 rounded-full transition-colors">
                                    <svg class="w-4 h-4 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <div class="flex-1 border-t border-dashed border-border"></div>
                            <span class="text-sm text-muted px-2">atau</span>
                            <div class="flex-1 border-t border-dashed border-border"></div>
                        </div>

                        <div class="form-group mb-0">
                            <label class="form-label">URL APK Eksternal</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-4 h-4 text-muted" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                                    </svg>
                                </div>
                                <input type="url" name="apk_url" id="apkUrlInput" class="form-input pl-10" placeholder="https://cdn.example.com/silatar-v2.1.0.apk" value="{{ old('apk_url') }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Settings -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Pengaturan</h3>
                    </div>
                    <div class="card-body space-y-3">
                        <label class="flex items-start gap-3 cursor-pointer p-2 rounded-lg hover:bg-secondary transition-colors">
                            <input type="checkbox" name="is_active" value="1" checked class="form-checkbox mt-0.5">
                            <div>
                                <span class="text-sm font-medium text-ink">Aktifkan Update</span>
                                <p class="text-xs text-muted mt-0.5">Update akan tersedia untuk user</p>
                            </div>
                        </label>
                        <label class="flex items-start gap-3 cursor-pointer p-2 rounded-lg hover:bg-secondary transition-colors">
                            <input type="checkbox" name="is_mandatory" value="1" class="form-checkbox mt-0.5">
                            <div>
                                <span class="text-sm font-medium text-ink">Mandatory Update</span>
                                <p class="text-xs text-muted mt-0.5">User harus update sebelum pakai app</p>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Version Constraints -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Batasan Versi</h3>
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

                <!-- Submit -->
                <button type="submit" class="btn btn-primary w-full">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                    Upload Update
                </button>

                <!-- Tips -->
                <div class="p-4 bg-blue-50 rounded-lg border border-blue-100">
                    <h4 class="text-sm font-semibold text-blue-800 mb-2 flex items-center gap-2">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Tips
                    </h4>
                    <ul class="text-xs text-blue-700 space-y-1">
                        <li>• Gunakan <strong>Patch</strong> untuk update kecil</li>
                        <li>• Gunakan <strong>APK</strong> untuk native changes</li>
                        <li>• Version code harus > dari sebelumnya</li>
                    </ul>
                </div>
            </div>
        </div>
    </form>
</x-admin.layouts.app>

@push('styles')
<style>
/* Utility Classes */
.text-danger { color: var(--danger); }
.text-primary { color: var(--primary); }
.text-secondary { color: var(--text-secondary); }
.text-ink { color: var(--text-primary); }
.text-muted { color: var(--text-muted); }
.text-sm { font-size: 0.875rem; }
.text-xs { font-size: 0.75rem; }
.text-sm { font-size: 0.875rem; }
.text-lg { font-size: 1.125rem; }
.font-medium { font-weight: 500; }
.font-semibold { font-weight: 600; }
.font-bold { font-weight: 700; }
.truncate { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.p-2 { padding: 0.5rem; }
.p-3 { padding: 0.75rem; }
.p-4 { padding: 1rem; }
.px-2 { padding-left: 0.5rem; padding-right: 0.5rem; }
.px-4 { padding-left: 1rem; padding-right: 1rem; }
.py-4 { padding-top: 1rem; padding-bottom: 1rem; }
.mt-0\.5 { margin-top: 0.125rem; }
.mt-1 { margin-top: 0.25rem; }
.mt-4 { margin-top: 1rem; }
.mb-0 { margin-bottom: 0; }
.mb-2 { margin-bottom: 0.5rem; }
.mb-6 { margin-bottom: 1.5rem; }
.gap-2 { gap: 0.5rem; }
.gap-3 { gap: 0.75rem; }
.gap-4 { gap: 1rem; }
.space-y-1 > * + * { margin-top: 0.25rem; }
.space-y-3 > * + * { margin-top: 0.75rem; }
.space-y-4 > * + * { margin-top: 1rem; }
.space-y-6 > * + * { margin-top: 1.5rem; }
.flex { display: flex; }
.flex-1 { flex: 1 1 0%; }
.flex items-center { align-items: center; }
.items-start { align-items: flex-start; }
.justify-between { justify-content: space-between; }
.items-center { justify-content: center; }
.min-w-0 { min-width: 0; }
.min-w-full { min-width: 100%; }
.rounded-lg { border-radius: 0.5rem; }
.rounded-xl { border-radius: 0.75rem; }
.rounded-full { border-radius: 9999px; }
.rounded { border-radius: var(--radius); }
.border { border: 1px solid var(--border); }
.border-2 { border-width: 2px; }
.border-dashed { border-style: dashed; }
.border-amber-200 { border-color: #fed7aa; }
.border-amber-100 { border-color: #fef3c7; }
.border-blue-200 { border-color: #bfdbfe; }
.border-blue-100 { border-color: #dbeafe; }
.border-blue-50 { border-color: #eff6ff; }
.border-primary { border-color: var(--primary); }
.border-border { border-color: var(--border); }
.border-blue-100 { border-color: #dbeafe; }
.border-danger { border-color: var(--danger); }
.border-t { border-top: 1px solid var(--border); }
.border-dashed { border-style: dashed; }
.bg-amber-50 { background-color: #fffbeb; }
.bg-amber-100 { background-color: #fef3c7; }
.bg-blue-50 { background-color: #eff6ff; }
.bg-blue-100 { background-color: #dbeafe; }
.bg-red-100 { background-color: #fee2e2; }
.bg-secondary { background-color: var(--secondary); }
.shadow-lg { box-shadow: var(--shadow-lg); }
.pointer-events-none { pointer-events: none; }
.inline-flex { display: inline-flex; }
.overflow-hidden { overflow: hidden; }
.hidden { display: none; }
.truncate { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.w-full { width: 100%; }
.h-full { height: 100%; }
.px-2 { padding-left: 0.5rem; padding-right: 0.5rem; }

/* Update Type Cards - Compact Design */
.update-type-card {
    position: relative;
}
.update-type-card input[type="radio"] {
    position: absolute;
    opacity: 0;
    width: 0;
    height: 0;
}
.type-card-inner {
    padding: 1rem;
    border: 2px solid var(--border);
    border-radius: var(--radius);
    background: var(--card);
    transition: all 0.2s;
}
.type-card-inner:hover {
    border-color: var(--primary);
    background: var(--primary-50);
}
.type-card-inner.selected {
    border-color: var(--primary);
    background: var(--primary-50);
    box-shadow: 0 0 0 3px rgba(200, 154, 43, 0.15);
}
.type-card-header {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
}
.type-icon {
    width: 36px;
    height: 36px;
    border-radius: var(--radius-sm);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.type-icon.amber {
    background: rgba(217, 119, 6, 0.1);
    color: #D97706;
}
.type-icon.blue {
    background: rgba(59, 130, 246, 0.1);
    color: #3B82F6;
}
.type-card-inner.selected .type-icon.amber {
    background: #D97706;
    color: white;
}
.type-card-inner.selected .type-icon.blue {
    background: #3B82F6;
    color: white;
}
.type-title {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.125rem;
}
.type-desc {
    font-size: 0.75rem;
    color: var(--text-muted);
}
.type-radio {
    width: 18px;
    height: 18px;
    border-radius: 50%;
    border: 2px solid var(--border);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-left: auto;
    flex-shrink: 0;
    transition: all 0.2s;
}
.type-card-inner.selected .type-radio {
    border-color: var(--primary);
    background: var(--primary);
}
.radio-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: white;
    opacity: 0;
    transform: scale(0);
    transition: all 0.2s;
}
.type-card-inner.selected .radio-dot {
    opacity: 1;
    transform: scale(1);
}
.type-features {
    display: flex;
    flex-wrap: wrap;
    gap: 0.375rem;
    margin-top: 0.75rem;
    padding-top: 0.75rem;
    border-top: 1px solid var(--border);
}
.feature-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    padding: 0.25rem 0.5rem;
    border-radius: 9999px;
    font-size: 0.7rem;
    font-weight: 500;
}
.feature-badge.amber {
    background: rgba(217, 119, 6, 0.1);
    color: #B45309;
}
.feature-badge.blue {
    background: rgba(59, 130, 246, 0.1);
    color: #1D4ED8;
}

/* File Upload Area */
.file-upload-area {
    position: relative;
}
.file-input-hidden {
    position: absolute;
    width: 0;
    height: 0;
    opacity: 0;
}
.file-upload-label-inline {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.5rem;
    border: 2px dashed var(--border);
    border-radius: var(--radius);
    background: var(--secondary-light);
    cursor: pointer;
    transition: all 0.2s;
}
.file-upload-label-inline:hover {
    border-color: var(--primary);
    background: var(--primary-50);
}
.file-upload-area.dragover .file-upload-label-inline {
    border-color: var(--primary);
    background: var(--primary-100);
    transform: scale(1.01);
}
.upload-icon-box {
    width: 48px;
    height: 48px;
    border-radius: var(--radius);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.upload-icon-box.amber {
    background: rgba(217, 119, 6, 0.1);
    color: #D97706;
}
.upload-icon-box.blue {
    background: rgba(59, 130, 246, 0.1);
    color: #3B82F6;
}
.upload-ext {
    padding: 0.25rem 0.5rem;
    background: var(--secondary);
    border-radius: 9999px;
    font-size: 0.7rem;
    font-weight: 500;
    color: var(--text-secondary);
}
</style>
@endpush

@push('scripts')
<script>
// File Handling
function handlePatchFileSelect(input) {
    const file = input.files[0];
    if (file) {
        document.getElementById('patchFileName').textContent = file.name;
        document.getElementById('patchFileSize').textContent = formatBytes(file.size);
        document.getElementById('patchFilePreview').classList.remove('hidden');
    }
}

function removePatchFile() {
    document.getElementById('patchFileInput').value = '';
    document.getElementById('patchFilePreview').classList.add('hidden');
}

function handleApkFileSelect(input) {
    const file = input.files[0];
    if (file) {
        document.getElementById('apkFileName').textContent = file.name;
        document.getElementById('apkFileSize').textContent = formatBytes(file.size);
        document.getElementById('apkFilePreview').classList.remove('hidden');
        document.getElementById('apkUrlInput').value = '';
    }
}

function removeApkFile() {
    document.getElementById('apkFileInput').value = '';
    document.getElementById('apkFilePreview').classList.add('hidden');
}

function formatBytes(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

// Toggle Update Type
function toggleUpdateType(type) {
    const patchSection = document.getElementById('patchFileSection');
    const apkSection = document.getElementById('apkFileSection');
    const patchCards = document.querySelectorAll('[data-type="patch"] .type-card-inner');
    const apkCards = document.querySelectorAll('[data-type="apk"] .type-card-inner');

    if (type === 'patch') {
        patchSection.classList.remove('hidden');
        apkSection.classList.add('hidden');
        patchCards.forEach(card => card.classList.add('selected'));
        apkCards.forEach(card => card.classList.remove('selected'));
        document.getElementById('patchFileInput').required = true;
    } else {
        patchSection.classList.add('hidden');
        apkSection.classList.remove('hidden');
        apkCards.forEach(card => card.classList.add('selected'));
        patchCards.forEach(card => card.classList.remove('selected'));
        document.getElementById('patchFileInput').required = false;
    }
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    const selectedType = document.querySelector('input[name="update_type"]:checked')?.value || 'patch';
    toggleUpdateType(selectedType);
});

// Drag and drop
['patchUploadArea', 'apkUploadArea'].forEach(id => {
    const zone = document.getElementById(id);
    if (zone) {
        zone.addEventListener('dragover', function(e) {
            e.preventDefault();
            this.classList.add('dragover');
        });
        zone.addEventListener('dragleave', function(e) {
            e.preventDefault();
            this.classList.remove('dragover');
        });
        zone.addEventListener('drop', function(e) {
            e.preventDefault();
            this.classList.remove('dragover');
            const input = this.querySelector('input[type="file"]');
            input.files = e.dataTransfer.files;
            if (id === 'patchUploadArea') handlePatchFileSelect(input);
            else handleApkFileSelect(input);
        });
    }
});
</script>
@endpush
