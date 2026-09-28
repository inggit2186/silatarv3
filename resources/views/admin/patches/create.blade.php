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
                                <h3 class="card-title">Informasi Patch</h3>
                                <p class="text-sm text-muted">Versi dan detail patch</p>
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
                                Angka unik untuk perbandingan. Patch terbaru: <strong>v{{ $latestPatch?->version ?? '-' }}</strong> (code: {{ $latestPatch?->version_code ?? '-' }})
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

                <!-- File Upload -->
                <div class="card">
                    <div class="card-header">
                        <div class="flex items-center gap-3">
                            <div class="stat-icon violet">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="card-title">File Patch</h3>
                                <p class="text-sm text-muted">Upload file patch (.zip, .patch, .tar.gz)</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label class="form-label">Pilih File <span class="text-danger">*</span></label>
                            <div class="file-upload-wrapper" id="fileUploadWrapper">
                                <input type="file" name="file" id="fileInput" class="file-input" accept=".zip,.patch,.bz2,.tar,.tar.gz,.tgz" required onchange="updateFileName(this)">
                                <label for="fileInput" class="file-upload-label">
                                    <svg class="w-10 h-10 text-muted mb-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                    </svg>
                                    <span class="text-ink font-medium">Klik untuk upload</span>
                                    <span class="text-muted text-sm">atau drag & drop file ke sini</span>
                                    <span class="text-xs text-muted mt-2">Format: .zip, .patch, .bz2, .tar, .tar.gz (Max: 100MB)</span>
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

function formatBytes(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

// Drag and drop
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
</script>

<style>
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
