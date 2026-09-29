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
                <!-- Update Type Selection - Premium Design -->
                <div class="card border-0 shadow-lg overflow-hidden">
                    <div class="bg-gradient-to-r from-primary/10 to-primary/5 px-6 py-4 border-b border-border">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary to-primary-700 flex items-center justify-center shadow-lg">
                                <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="card-title text-lg">Pilih Tipe Update</h3>
                                <p class="text-sm text-muted">Pilih jenis update yang akan dirilis ke пользователей</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Patch Type Card -->
                            <label class="update-type-card group cursor-pointer" data-type="patch">
                                <input type="radio" name="update_type" value="patch" {{ old('update_type', 'patch') == 'patch' ? 'checked' : '' }} onchange="toggleUpdateType('patch')">
                                <div class="relative h-full rounded-2xl border-2 transition-all duration-300
                                    {{ old('update_type', 'patch') == 'patch' ? 'border-primary bg-primary/5 shadow-lg shadow-primary/20' : 'border-border bg-white hover:border-primary/50 hover:shadow-md' }}">
                                    <!-- Selection Indicator -->
                                    <div class="absolute top-4 right-4 w-6 h-6 rounded-full border-2 transition-all duration-300
                                        {{ old('update_type', 'patch') == 'patch' ? 'border-primary bg-primary' : 'border-border group-hover:border-primary/50' }}">
                                        @if(old('update_type', 'patch') == 'patch')
                                            <svg class="w-full h-full text-white p-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        @endif
                                    </div>

                                    <!-- Icon -->
                                    <div class="flex justify-center pt-8 pb-4">
                                        <div class="relative">
                                            <div class="absolute inset-0 bg-gradient-to-br from-amber-400 to-orange-500 rounded-2xl blur-xl opacity-20 group-hover:opacity-40 transition-opacity"></div>
                                            <div class="relative w-20 h-20 rounded-2xl bg-gradient-to-br from-amber-100 to-orange-100 flex items-center justify-center
                                                {{ old('update_type', 'patch') == 'patch' ? 'ring-4 ring-primary/30' : '' }}">
                                                <svg class="w-10 h-10 text-amber-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Content -->
                                    <div class="text-center px-4 pb-6">
                                        <h4 class="text-lg font-bold text-ink mb-1">⚡ Patch Update</h4>
                                        <p class="text-sm text-muted mb-4">Hot Code Push - Kode Dart saja</p>

                                        <!-- Features -->
                                        <div class="space-y-2 text-left">
                                            <div class="flex items-center gap-3 p-2 rounded-lg bg-amber-50/50">
                                                <div class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center">
                                                    <span class="text-amber-600 font-bold text-sm">~</span>
                                                </div>
                                                <div>
                                                    <span class="text-xs font-semibold text-amber-700">Ukuran Kecil</span>
                                                    <p class="text-xs text-muted">100KB - 2MB</p>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-3 p-2 rounded-lg bg-amber-50/50">
                                                <div class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center">
                                                    <svg class="w-4 h-4 text-amber-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <span class="text-xs font-semibold text-amber-700">Instant Apply</span>
                                                    <p class="text-xs text-muted">Tanpa install ulang</p>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-3 p-2 rounded-lg bg-amber-50/50">
                                                <div class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center">
                                                    <svg class="w-4 h-4 text-amber-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <span class="text-xs font-semibold text-amber-700">Cepat</span>
                                                    <p class="text-xs text-muted">Hemat bandwidth</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mt-4 pt-4 border-t border-border/50">
                                            <span class="text-xs text-muted">Cocok untuk: Bug fix, UI updates, Logic changes</span>
                                        </div>
                                    </div>
                                </div>
                            </label>

                            <!-- APK Type Card -->
                            <label class="update-type-card group cursor-pointer" data-type="apk">
                                <input type="radio" name="update_type" value="apk" {{ old('update_type') == 'apk' ? 'checked' : '' }} onchange="toggleUpdateType('apk')">
                                <div class="relative h-full rounded-2xl border-2 transition-all duration-300
                                    {{ old('update_type') == 'apk' ? 'border-blue-500 bg-blue-500/5 shadow-lg shadow-blue-500/20' : 'border-border bg-white hover:border-blue-500/50 hover:shadow-md' }}">
                                    <!-- Selection Indicator -->
                                    <div class="absolute top-4 right-4 w-6 h-6 rounded-full border-2 transition-all duration-300
                                        {{ old('update_type') == 'apk' ? 'border-blue-500 bg-blue-500' : 'border-border group-hover:border-blue-500/50' }}">
                                        @if(old('update_type') == 'apk')
                                            <svg class="w-full h-full text-white p-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        @endif
                                    </div>

                                    <!-- Icon -->
                                    <div class="flex justify-center pt-8 pb-4">
                                        <div class="relative">
                                            <div class="absolute inset-0 bg-gradient-to-br from-blue-400 to-indigo-500 rounded-2xl blur-xl opacity-20 group-hover:opacity-40 transition-opacity"></div>
                                            <div class="relative w-20 h-20 rounded-2xl bg-gradient-to-br from-blue-100 to-indigo-100 flex items-center justify-center
                                                {{ old('update_type') == 'apk' ? 'ring-4 ring-blue-500/30' : '' }}">
                                                <svg class="w-10 h-10 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Content -->
                                    <div class="text-center px-4 pb-6">
                                        <h4 class="text-lg font-bold text-ink mb-1">📦 Full APK</h4>
                                        <p class="text-sm text-muted mb-4">Update Seluruh Aplikasi</p>

                                        <!-- Features -->
                                        <div class="space-y-2 text-left">
                                            <div class="flex items-center gap-3 p-2 rounded-lg bg-blue-50/50">
                                                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                                    <span class="text-blue-600 font-bold text-sm">~</span>
                                                </div>
                                                <div>
                                                    <span class="text-xs font-semibold text-blue-700">Ukuran Besar</span>
                                                    <p class="text-xs text-muted">20MB - 50MB+</p>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-3 p-2 rounded-lg bg-blue-50/50">
                                                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                                    <svg class="w-4 h-4 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <span class="text-xs font-semibold text-blue-700">Install Ulang</span>
                                                    <p class="text-xs text-muted">User install APK baru</p>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-3 p-2 rounded-lg bg-blue-50/50">
                                                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                                    <svg class="w-4 h-4 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <span class="text-xs font-semibold text-blue-700">Native Code</span>
                                                    <p class="text-xs text-muted">Plugin & SDK update</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mt-4 pt-4 border-t border-border/50">
                                            <span class="text-xs text-muted">Cocok untuk: Plugin update, Native crash, Major version</span>
                                        </div>
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
                            <textarea name="changelog" class="form-textarea" rows="4" placeholder="Contoh:
- Perbaikan bug login
- Tambah fitur notifikasi
- Optimasi performa">{{ old('changelog') }}</textarea>
                            <p class="text-xs text-muted mt-1">Deskripsi perubahan yang akan ditampilkan ke user</p>
                        </div>
                    </div>
                </div>

                <!-- File Upload - Patch - Premium Design -->
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
                                <p class="text-sm text-muted">Upload file .zip dari flutter_patcher</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="file-upload-zone" id="patchUploadZone">
                            <input type="file" name="file" id="patchFileInput" class="file-input-hidden" accept=".zip,.patch,.bz2,.tar,.tar.gz,.tgz" onchange="handlePatchFileSelect(this)">
                            <label for="patchFileInput" class="file-upload-label-new">
                                <div class="upload-icon-container">
                                    <svg class="w-12 h-12 text-amber-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                </div>
                                <div class="upload-text">
                                    <span class="upload-title">Klik untuk upload file patch</span>
                                    <span class="upload-subtitle">atau drag & drop file .zip ke sini</span>
                                </div>
                                <div class="upload-formats">
                                    <span class="format-badge">.zip</span>
                                    <span class="format-badge">.patch</span>
                                    <span class="format-badge">.tar.gz</span>
                                </div>
                                <p class="upload-hint">Max: 50MB</p>
                            </label>
                        </div>

                        <!-- Selected File Preview -->
                        <div id="patchFilePreview" class="hidden mt-4">
                            <div class="flex items-center gap-4 p-4 bg-gradient-to-r from-amber-50 to-orange-50 rounded-xl border border-amber-200">
                                <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center shadow-lg">
                                    <svg class="w-7 h-7 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p id="patchFileName" class="font-semibold text-ink"></p>
                                    <p id="patchFileSize" class="text-sm text-muted"></p>
                                </div>
                                <button type="button" onclick="removePatchFile()" class="w-10 h-10 rounded-full bg-red-100 hover:bg-red-200 flex items-center justify-center transition-colors">
                                    <svg class="w-5 h-5 text-red-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- File Upload - APK - Premium Design -->
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
                        <!-- File Upload Zone -->
                        <div class="file-upload-zone" id="apkUploadZone">
                            <input type="file" name="apk_file" id="apkFileInput" class="file-input-hidden" accept=".apk,.zip" onchange="handleApkFileSelect(this)">
                            <label for="apkFileInput" class="file-upload-label-new">
                                <div class="upload-icon-container">
                                    <svg class="w-12 h-12 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                    </svg>
                                </div>
                                <div class="upload-text">
                                    <span class="upload-title">Klik untuk upload file APK</span>
                                    <span class="upload-subtitle">atau drag & drop file ke sini</span>
                                </div>
                                <div class="upload-formats">
                                    <span class="format-badge">.apk</span>
                                    <span class="format-badge">.zip</span>
                                </div>
                                <p class="upload-hint">Max: 200MB</p>
                            </label>
                        </div>

                        <!-- Selected File Preview -->
                        <div id="apkFilePreview" class="hidden">
                            <div class="flex items-center gap-4 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-200">
                                <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center shadow-lg">
                                    <svg class="w-7 h-7 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p id="apkFileName" class="font-semibold text-ink"></p>
                                    <p id="apkFileSize" class="text-sm text-muted"></p>
                                </div>
                                <button type="button" onclick="removeApkFile()" class="w-10 h-10 rounded-full bg-red-100 hover:bg-red-200 flex items-center justify-center transition-colors">
                                    <svg class="w-5 h-5 text-red-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Divider -->
                        <div class="relative py-2">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-border"></div>
                            </div>
                            <div class="relative flex justify-center">
                                <span class="px-4 bg-white text-sm text-muted">atau</span>
                            </div>
                        </div>

                        <!-- External URL -->
                        <div class="form-group mb-0">
                            <label class="form-label">URL APK Eksternal</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-muted" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                                    </svg>
                                </div>
                                <input type="url" name="apk_url" id="apkUrlInput" class="form-input pl-10" placeholder="https://cdn.example.com/silatar-v2.1.0.apk" value="{{ old('apk_url') }}">
                            </div>
                            <p class="text-xs text-muted mt-1">Link download APK dari CDN atau server lain</p>
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
                    <div class="card-body space-y-4">
                        <!-- Active -->
                        <label class="flex items-start gap-3 cursor-pointer p-3 rounded-xl hover:bg-secondary transition-colors">
                            <input type="checkbox" name="is_active" value="1" checked class="form-checkbox mt-0.5">
                            <div>
                                <span class="text-sm font-medium text-ink">Aktifkan Update</span>
                                <p class="text-xs text-muted mt-0.5">Update akan tersedia untuk user</p>
                            </div>
                        </label>

                        <!-- Mandatory -->
                        <label class="flex items-start gap-3 cursor-pointer p-3 rounded-xl hover:bg-secondary transition-colors">
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
                <button type="submit" class="btn btn-primary w-full h-12 text-base font-semibold shadow-lg shadow-primary/30 hover:shadow-xl hover:shadow-primary/40 transition-all">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                    Upload Update
                </button>

                <!-- Help -->
                <div class="p-4 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl border border-blue-100">
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

@push('scripts')
<script>
// Patch File Handling
function handlePatchFileSelect(input) {
    const file = input.files[0];
    if (file) {
        document.getElementById('patchFileName').textContent = file.name;
        document.getElementById('patchFileSize').textContent = formatBytes(file.size);
        document.getElementById('patchFilePreview').classList.remove('hidden');
        document.getElementById('patchUploadZone').classList.add('has-file');
    }
}

function removePatchFile() {
    document.getElementById('patchFileInput').value = '';
    document.getElementById('patchFilePreview').classList.add('hidden');
    document.getElementById('patchUploadZone').classList.remove('has-file');
}

// APK File Handling
function handleApkFileSelect(input) {
    const file = input.files[0];
    if (file) {
        document.getElementById('apkFileName').textContent = file.name;
        document.getElementById('apkFileSize').textContent = formatBytes(file.size);
        document.getElementById('apkFilePreview').classList.remove('hidden');
        document.getElementById('apkUploadZone').classList.add('has-file');
        // Clear URL input
        document.getElementById('apkUrlInput').value = '';
    }
}

function removeApkFile() {
    document.getElementById('apkFileInput').value = '';
    document.getElementById('apkFilePreview').classList.add('hidden');
    document.getElementById('apkUploadZone').classList.remove('has-file');
}

// Format bytes
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
    const patchCard = document.querySelector('[data-type="patch"]');
    const apkCard = document.querySelector('[data-type="apk"]');

    if (type === 'patch') {
        patchSection.classList.remove('hidden');
        apkSection.classList.add('hidden');
        patchCard.querySelector('div').classList.add('selected');
        patchCard.querySelector('div').classList.remove('not-selected');
        apkCard.querySelector('div').classList.remove('selected');
        apkCard.querySelector('div').classList.add('not-selected');
        document.getElementById('patchFileInput').required = true;
    } else {
        patchSection.classList.add('hidden');
        apkSection.classList.remove('hidden');
        apkCard.querySelector('div').classList.add('selected');
        apkCard.querySelector('div').classList.remove('not-selected');
        patchCard.querySelector('div').classList.remove('selected');
        patchCard.querySelector('div').classList.add('not-selected');
        document.getElementById('patchFileInput').required = false;
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    const selectedType = document.querySelector('input[name="update_type"]:checked')?.value || 'patch';
    toggleUpdateType(selectedType);
});

// Drag and drop for patch
const patchZone = document.getElementById('patchUploadZone');
if (patchZone) {
    patchZone.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.classList.add('dragover');
    });
    patchZone.addEventListener('dragleave', function(e) {
        e.preventDefault();
        this.classList.remove('dragover');
    });
    patchZone.addEventListener('drop', function(e) {
        e.preventDefault();
        this.classList.remove('dragover');
        const input = document.getElementById('patchFileInput');
        input.files = e.dataTransfer.files;
        handlePatchFileSelect(input);
    });
}

// Drag and drop for APK
const apkZone = document.getElementById('apkUploadZone');
if (apkZone) {
    apkZone.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.classList.add('dragover');
    });
    apkZone.addEventListener('dragleave', function(e) {
        e.preventDefault();
        this.classList.remove('dragover');
    });
    apkZone.addEventListener('drop', function(e) {
        e.preventDefault();
        this.classList.remove('dragover');
        const input = document.getElementById('apkFileInput');
        input.files = e.dataTransfer.files;
        handleApkFileSelect(input);
    });
}
</script>

<style>
/* Premium Update Type Cards */
.update-type-card {
    position: relative;
}
.update-type-card input[type="radio"] {
    position: absolute;
    opacity: 0;
    width: 0;
    height: 0;
}

/* Premium File Upload Zone */
.file-upload-zone {
    position: relative;
}
.file-input-hidden {
    position: absolute;
    width: 0;
    height: 0;
    opacity: 0;
}
.file-upload-label-new {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 2.5rem 2rem;
    border: 2px dashed var(--border);
    border-radius: 1rem;
    background: linear-gradient(to bottom, var(--secondary-light), white);
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
}
.file-upload-zone:hover .file-upload-label-new,
.file-upload-zone.dragover .file-upload-label-new {
    border-color: var(--primary);
    background: linear-gradient(to bottom, var(--primary-50), white);
    transform: translateY(-2px);
}
.file-upload-zone.has-file .file-upload-label-new {
    border-color: var(--success);
    background: linear-gradient(to bottom, var(--success-bg), white);
}
.upload-icon-container {
    width: 80px;
    height: 80px;
    border-radius: 1rem;
    background: linear-gradient(135deg, var(--primary-100), var(--primary-50));
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1rem;
    transition: all 0.3s ease;
}
.file-upload-zone:hover .upload-icon-container {
    transform: scale(1.1);
    background: linear-gradient(135deg, var(--primary), var(--primary-700));
}
.file-upload-zone:hover .upload-icon-container svg {
    color: white;
}
.upload-text {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
    margin-bottom: 1rem;
}
.upload-title {
    font-size: 1rem;
    font-weight: 600;
    color: var(--ink);
}
.upload-subtitle {
    font-size: 0.875rem;
    color: var(--muted);
}
.upload-formats {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 0.5rem;
}
.format-badge {
    padding: 0.25rem 0.75rem;
    background: var(--secondary);
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 500;
    color: var(--ink-soft);
}
.upload-hint {
    font-size: 0.75rem;
    color: var(--muted);
    margin: 0;
}

/* Drag over effect */
.file-upload-zone.dragover {
    transform: scale(1.02);
}
</style>
@endpush
