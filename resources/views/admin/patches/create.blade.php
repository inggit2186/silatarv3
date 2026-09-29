<x-admin.layouts.app>
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <span class="page-label">// App Updates</span>
            <h1 class="page-title">Upload Patch Baru</h1>
            <p class="page-subtitle">Upload file patch untuk update aplikasi mobile</p>
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
                <!-- Update Type -->
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
                                <p class="text-sm text-muted">Pilih jenis update yang akan diupload</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="grid grid-cols-2 gap-4">
                            <!-- Patch Type -->
                            <label class="update-type-card {{ old('update_type', 'patch') == 'patch' ? 'selected' : '' }}" data-type="patch">
                                <input type="radio" name="update_type" value="patch" {{ old('update_type', 'patch') == 'patch' ? 'checked' : '' }} onchange="toggleUpdateType('patch')">
                                <div class="update-type-content">
                                    <div class="update-type-icon">
                                        <svg class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                        </svg>
                                    </div>
                                    <h4>Patch (Hot Code)</h4>
                                    <p>Hanya kode Dart</p>
                                    <ul>
                                        <li>Ukuran kecil (~100KB-2MB)</li>
                                        <li>Apply instant tanpa install</li>
                                        <li>Tidak bisa update native code</li>
                                    </ul>
                                </div>
                            </label>

                            <!-- APK Type -->
                            <label class="update-type-card {{ old('update_type') == 'apk' ? 'selected' : '' }}" data-type="apk">
                                <input type="radio" name="update_type" value="apk" {{ old('update_type') == 'apk' ? 'checked' : '' }} onchange="toggleUpdateType('apk')">
                                <div class="update-type-content">
                                    <div class="update-type-icon">
                                        <svg class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                        </svg>
                                    </div>
                                    <h4>Full APK</h4>
                                    <p>Update seluruh aplikasi</p>
                                    <ul>
                                        <li>Ukuran besar (~20-50MB)</li>
                                        <li>Butuh install ulang</li>
                                        <li>Update native code & plugin</li>
                                    </ul>
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
                            <textarea name="changelog" class="form-textarea" rows="5" placeholder="Contoh:
- Perbaikan bug login
- Tambah fitur notifikasi
- Optimasi performa">{{ old('changelog') }}</textarea>
                            <p class="text-xs text-muted mt-1">Deskripsi perubahan yang akan ditampilkan ke user</p>
                        </div>
                    </div>
                </div>

                <!-- File Upload - Patch -->
                <div class="card" id="patchFileSection">
                    <div class="card-header">
                        <div class="flex items-center gap-3">
                            <div class="stat-icon violet">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="card-title">File Patch</h3>
                                <p class="text-sm text-muted">Upload file patch (.zip) dari flutter_patcher</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label class="form-label">Pilih File Patch <span class="text-danger">*</span></label>
                            <div class="file-upload-wrapper" id="fileUploadWrapper">
                                <input type="file" name="file" id="fileInput" class="file-input" accept=".zip,.patch,.bz2,.tar,.tar.gz,.tgz" onchange="updateFileName(this)">
                                <label for="fileInput" class="file-upload-label">
                                    <svg class="w-10 h-10 text-muted mb-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                    </svg>
                                    <span class="text-ink font-medium">Klik untuk upload</span>
                                    <span class="text-muted text-sm">atau drag & drop file ke sini</span>
                                    <span class="text-xs text-muted mt-2">Format: .zip (Max: 50MB)</span>
                                </label>
                            </div>
                            <div id="fileInfo" class="hidden mt-3 p-3 bg-success-bg rounded-lg border border-success/20">
                                <div class="flex items-center gap-3">
                                    <svg class="w-5 h-5 text-success" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <div>
                                        <span id="fileName" class="font-medium text-ink"></span>
                                        <span id="fileSize" class="text-sm text-muted ml-2"></span>
                                    </div>
                                </div>
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
                                <p class="text-sm text-muted">Upload file APK atau masukkan URL APK</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-body space-y-4">
                        <div class="form-group">
                            <label class="form-label">Upload File APK</label>
                            <div class="file-upload-wrapper" id="apkFileUploadWrapper">
                                <input type="file" name="apk_file" id="apkFileInput" class="file-input" accept=".apk,.zip" onchange="updateApkFileName(this)">
                                <label for="apkFileInput" class="file-upload-label">
                                    <svg class="w-10 h-10 text-muted mb-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                    </svg>
                                    <span class="text-ink font-medium">Klik untuk upload APK</span>
                                    <span class="text-muted text-sm">atau drag & drop file ke sini</span>
                                    <span class="text-xs text-muted mt-2">Format: .apk, .zip (Max: 200MB)</span>
                                </label>
                            </div>
                            <div id="apkFileInfo" class="hidden mt-3 p-3 bg-success-bg rounded-lg border border-success/20">
                                <div class="flex items-center gap-3">
                                    <svg class="w-5 h-5 text-success" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <div>
                                        <span id="apkFileName" class="font-medium text-ink"></span>
                                        <span id="apkFileSize" class="text-sm text-muted ml-2"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="relative">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-border"></div>
                            </div>
                            <div class="relative flex justify-center text-sm">
                                <span class="px-2 bg-white text-muted">atau</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">URL APK Eksternal</label>
                            <input type="url" name="apk_url" id="apkUrlInput" class="form-input" placeholder="https://cdn.example.com/silatar-v2.1.0.apk" value="{{ old('apk_url') }}">
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
                        <div class="form-group">
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" name="is_active" value="1" checked class="form-checkbox">
                                <span class="text-sm font-medium text-ink">Patch Aktif</span>
                            </label>
                            <p class="text-xs text-muted mt-1 ml-7">Patch akan tersedia untuk didownload user</p>
                        </div>

                        <!-- Mandatory -->
                        <div class="form-group">
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" name="is_mandatory" value="1" class="form-checkbox">
                                <span class="text-sm font-medium text-ink">Mandatory Update</span>
                            </label>
                            <p class="text-xs text-muted mt-1 ml-7">User HARUS update sebelum pakai aplikasi</p>
                        </div>
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
                            <p class="text-xs text-muted mt-1">Patch hanya untuk app versi >= ini</p>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Max App Version</label>
                            <input type="text" name="max_app_version" class="form-input" placeholder="Kosongkan = tidak terbatas" value="{{ old('max_app_version') }}">
                            <p class="text-xs text-muted mt-1">Patch hanya untuk app versi <= ini</p>
                        </div>
                    </div>
                </div>

                <!-- Submit -->
                <button type="submit" class="btn btn-primary w-full">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                    Upload Patch
                </button>
            </div>
        </div>
    </form>
</x-admin.layouts.app>

@push('scripts')
<script>
function updateFileName(input) {
    const file = input.files[0];
    const fileInfo = document.getElementById('fileInfo');
    const fileName = document.getElementById('fileName');
    const fileSize = document.getElementById('fileSize');

    if (file) {
        fileName.textContent = file.name;
        fileSize.textContent = formatBytes(file.size);
        fileInfo.classList.remove('hidden');
    } else {
        fileInfo.classList.add('hidden');
    }
}

function updateApkFileName(input) {
    const file = input.files[0];
    const fileInfo = document.getElementById('apkFileInfo');
    const fileName = document.getElementById('apkFileName');
    const fileSize = document.getElementById('apkFileSize');

    if (file) {
        fileName.textContent = file.name;
        fileSize.textContent = formatBytes(file.size);
        fileInfo.classList.remove('hidden');
        // Clear URL input if file is selected
        document.getElementById('apkUrlInput').value = '';
    } else {
        fileInfo.classList.add('hidden');
    }
}

function formatBytes(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

function toggleUpdateType(type) {
    const patchSection = document.getElementById('patchFileSection');
    const apkSection = document.getElementById('apkFileSection');
    const patchCard = document.querySelector('[data-type="patch"]');
    const apkCard = document.querySelector('[data-type="apk"]');

    if (type === 'patch') {
        patchSection.classList.remove('hidden');
        apkSection.classList.add('hidden');
        patchCard.classList.add('selected');
        apkCard.classList.remove('selected');
        // Make patch file required
        document.getElementById('fileInput').required = true;
    } else {
        patchSection.classList.add('hidden');
        apkSection.classList.remove('hidden');
        patchCard.classList.remove('selected');
        apkCard.classList.add('selected');
        // Make patch file not required
        document.getElementById('fileInput').required = false;
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    const selectedType = document.querySelector('input[name="update_type"]:checked')?.value || 'patch';
    toggleUpdateType(selectedType);
});

// Drag and drop for patch file
const fileUploadWrapper = document.getElementById('fileUploadWrapper');
fileUploadWrapper.addEventListener('dragover', function(e) {
    e.preventDefault();
    this.classList.add('dragover');
});
fileUploadWrapper.addEventListener('dragleave', function(e) {
    e.preventDefault();
    this.classList.remove('dragover');
});
fileUploadWrapper.addEventListener('drop', function(e) {
    e.preventDefault();
    this.classList.remove('dragover');
    const input = document.getElementById('fileInput');
    input.files = e.dataTransfer.files;
    updateFileName(input);
});

// Drag and drop for APK file
const apkFileUploadWrapper = document.getElementById('apkFileUploadWrapper');
if (apkFileUploadWrapper) {
    apkFileUploadWrapper.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.classList.add('dragover');
    });
    apkFileUploadWrapper.addEventListener('dragleave', function(e) {
        e.preventDefault();
        this.classList.remove('dragover');
    });
    apkFileUploadWrapper.addEventListener('drop', function(e) {
        e.preventDefault();
        this.classList.remove('dragover');
        const input = document.getElementById('apkFileInput');
        input.files = e.dataTransfer.files;
        updateApkFileName(input);
    });
}
</script>

<style>
/* Update Type Cards */
.update-type-card {
    position: relative;
    cursor: pointer;
}

.update-type-card input[type="radio"] {
    position: absolute;
    opacity: 0;
    width: 0;
    height: 0;
}

.update-type-content {
    padding: 1.5rem;
    border: 2px dashed var(--border);
    border-radius: var(--radius-lg);
    text-align: center;
    transition: all 0.2s;
    background: var(--secondary-light);
}

.update-type-card:hover .update-type-content {
    border-color: var(--primary);
    background: var(--primary-50);
}

.update-type-card.selected .update-type-content {
    border-color: var(--primary);
    border-style: solid;
    background: var(--primary-50);
    box-shadow: 0 0 0 3px rgba(6, 182, 212, 0.2);
}

.update-type-icon {
    width: 60px;
    height: 60px;
    margin: 0 auto 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: var(--primary-100);
    color: var(--primary);
}

.update-type-card.selected .update-type-icon {
    background: var(--primary);
    color: white;
}

.update-type-content h4 {
    font-size: 1rem;
    font-weight: 600;
    color: var(--ink);
    margin-bottom: 0.25rem;
}

.update-type-content p {
    font-size: 0.75rem;
    color: var(--muted);
    margin-bottom: 0.75rem;
}

.update-type-content ul {
    text-align: left;
    list-style: none;
    padding: 0;
    margin: 0;
    font-size: 0.7rem;
    color: var(--muted);
}

.update-type-content ul li {
    padding: 0.25rem 0;
    padding-left: 1rem;
    position: relative;
}

.update-type-content ul li::before {
    content: "•";
    position: absolute;
    left: 0;
    color: var(--primary);
}

.update-type-card.selected .update-type-content ul li::before {
    color: white;
}

.file-upload-wrapper {
    position: relative;
}

.file-input {
    position: absolute;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    opacity: 0;
    cursor: pointer;
}

.file-upload-label {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 2rem;
    border: 2px dashed var(--border);
    border-radius: var(--radius);
    background: var(--secondary-light);
    text-align: center;
    transition: all 0.2s;
}

.file-upload-wrapper:hover .file-upload-label,
.file-upload-wrapper.dragover .file-upload-label {
    border-color: var(--primary);
    background: var(--primary-50);
}

.file-upload-wrapper.dragover .file-upload-label {
    transform: scale(1.02);
}
</style>
@endpush
