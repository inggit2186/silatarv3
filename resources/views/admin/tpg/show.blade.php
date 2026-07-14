<x-admin.layouts.app>
    <?php
    $title = 'Detail Verifikasi TPG';
    $metadataParsed = json_decode($item->metadata ?? '{}', true) ?? [];
    $filesRaw = $item->files ?? '[]';
    $filesParsed = is_string($filesRaw) ? (json_decode($filesRaw, true) ?? []) : [];
    $files = is_array($filesParsed) ? $filesParsed : [];
    ?>

    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <span class="page-label">// Verifikasi</span>
            <h1 class="page-title">Detail Verifikasi TPG</h1>
            <p class="page-subtitle">No. Request: <strong style="color: var(--primary);">{{ $item->noreq }}</strong></p>
        </div>
        <div class="page-actions">
            <?php
                $statusBadgeClass = match($item->status) {
                    'SUBMITTED', 'PENDING' => 'badge-warning',
                    'DITERIMA', 'DIPROSES' => 'badge-info',
                    'SUKSES' => 'badge-success',
                    'DITOLAK' => 'badge-danger',
                    default => 'badge-neutral'
                };
            ?>
            <span class="badge {{ $statusBadgeClass }}" style="font-size: 0.8rem; padding: 0.375rem 0.875rem;">
                {{ $item->status }}
            </span>
            <a href="{{ route('admin.tpg.index') }}" class="btn btn-secondary">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali
            </a>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem;">
        <!-- Left Column -->
        <div style="display: flex; flex-direction: column; gap: 1.5rem;">

            <!-- Info Pemohon -->
            <div class="card">
                <div class="card-header">
                    <div class="flex items-center gap-3">
                        <div class="stat-icon cyan" style="width: 36px; height: 36px;">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="card-title">Informasi Pemohon</h3>
                            <p class="text-sm text-muted">Data pengaju layanan</p>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.25rem;">
                        <div>
                            <p style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-muted); margin-bottom: 0.25rem;">Nama Pemohon</p>
                            <p style="font-weight: 600; color: var(--text-primary);">{{ $user->name ?? '-' }}</p>
                        </div>
                        <div>
                            <p style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-muted); margin-bottom: 0.25rem;">Unit Kerja</p>
                            <p style="font-weight: 600; color: var(--text-primary);">{{ $dept->nama ?? '-' }}</p>
                        </div>
                        <div>
                            <p style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-muted); margin-bottom: 0.25rem;">Jenis Layanan</p>
                            <p style="font-weight: 600; color: var(--text-primary);">{{ $tipeLabel }}</p>
                        </div>
                        <div>
                            <p style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-muted); margin-bottom: 0.25rem;">Periode</p>
                            <p style="font-weight: 600; color: var(--text-primary);">
                                @if(isset($metadataParsed['semester']))
                                    {{ $metadataParsed['tahun_pelajaran'] ?? '' }} - Semester {{ $metadataParsed['semester'] }}
                                @elseif(isset($metadataParsed['bulan']))
                                    {{ $metadataParsed['bulan'] }} {{ $metadataParsed['tahun'] ?? '' }}
                                @else
                                    -
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dokumen -->
            <div class="card">
                <div class="card-header">
@php
    $files = is_array($filesParsed) ? $filesParsed : [];
@endphp
                    <div class="flex items-center gap-3">
                        <div class="stat-icon emerald" style="width: 36px; height: 36px;">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="card-title">Dokumen Upload</h3>
                            <p class="text-sm text-muted">{{ count($files) }} file dilampirkan</p>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if(count($files) > 0)
                        <div class="files-grid">
                            @foreach($files as $index => $file)
                                @php
                                    $fileName = $file['filename'] ?? '';
                                    $hasFile = !empty($fileName) && $fileName !== 'NONE';
                                    $fileSize = isset($file['size']) ? number_format((int) $file['size'] / 1024, 1) . ' KB' : '';
                                    $fileExt = $hasFile ? strtolower(pathinfo($fileName, PATHINFO_EXTENSION)) : '';
                                    $isPdf = $fileExt === 'pdf';
                                    $isImage = in_array($fileExt, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                @endphp
                                <a href="javascript:void(0)"
                                   class="file-card {{ $hasFile ? 'has-file' : 'no-file' }}"
                                   onclick="{{ $hasFile ? "openFileModal(event, '" . addslashes($file['title'] ?? 'Dokumen') . "', '" . addslashes($fileName) . "', '" . $fileSize . "', '" . route('admin.tpg.preview', ['id' => $item->id, 'syaratId' => $file['syarat_id']]) . "', '" . route('admin.tpg.file', ['id' => $item->id, 'syaratId' => $file['syarat_id']]) . "', '" . ($isImage ? 'image' : ($isPdf ? 'pdf' : 'file')) . "')" : '' }}"
                                   style="text-decoration: none; cursor: {{ $hasFile ? 'pointer' : 'default' }};">
                                    <div class="file-icon-wrapper">
                                        @if($hasFile)
                                            @if($isImage)
                                                <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                            @elseif($isPdf)
                                                <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v6a1 1 0 001 1h6"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 13h1m4 0h1m-5 3h1m3 0h1"/>
                                                </svg>
                                            @else
                                                <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                            @endif
                                        @else
                                            <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                            </svg>
                                        @endif
                                    </div>
                                    <div class="file-info">
                                        <p class="file-title" title="{{ $file['title'] ?? 'Dokumen' }}">{{ $file['title'] ?? 'Dokumen' }}</p>
                                        @if($hasFile)
                                            <p class="file-name" title="{{ $fileName }}">{{ $fileName }}</p>
                                            <p class="file-size">{{ $fileSize }}</p>
                                        @else
                                            <p class="file-status">Belum upload</p>
                                        @endif
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div style="text-align: center; padding: 2rem; color: var(--text-muted);">
                            <svg class="h-12 w-12 mx-auto mb-2" style="color: var(--text-muted);" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p>Tidak ada dokumen dilampirkan</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div style="display: flex; flex-direction: column; gap: 1.5rem;">

            <!-- Form Verifikasi -->
            @if(!in_array($item->status, ['SUKSES', 'DITOLAK', 'BATAL']))
                <div class="card">
                    <div class="card-header">
                        <div class="flex items-center gap-3">
                            <div class="stat-icon amber" style="width: 36px; height: 36px;">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="card-title">Aksi Verifikasi</h3>
                                <p class="text-sm text-muted">Proses persetujuan</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.tpg.verify', $item->id) }}">
                            @csrf
                            <div style="display: flex; flex-direction: column; gap: 1rem;">
                                <div class="form-group mb-0">
                                    <label class="form-label">Ubah Status</label>
                                    <select name="status" class="form-select">
                                        <option value="DITERIMA">Terima (DITERIMA)</option>
                                        <option value="PENDING">Proses (PENDING)</option>
                                        <option value="SUKSES">Selesai (SUKSES)</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary" style="width: 100%;">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Simpan Status
                                </button>
                            </div>
                        </form>

                        <div style="border-top: 1px solid var(--border); margin: 1.25rem 0;"></div>

                        <form method="POST" action="{{ route('admin.tpg.reject', $item->id) }}">
                            @csrf
                            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                                <div class="form-group mb-0">
                                    <label class="form-label">Alasan Penolakan</label>
                                    <textarea name="keterangan" class="form-input" rows="3" placeholder="Wajib diisi jika menolak..." style="height: auto; min-height: 80px; padding: 10px 12px;"></textarea>
                                </div>
                                @error('keterangan')
                                    <span style="color: var(--danger); font-size: 0.8rem;">{{ $message }}</span>
                                @enderror
                                <button type="submit" class="btn btn-danger" style="width: 100%;">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    Tolak Pengajuan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif

            <!-- Info Timeline -->
            <div class="card">
                <div class="card-header">
                    <div class="flex items-center gap-3">
                        <div class="stat-icon blue" style="width: 36px; height: 36px;">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="card-title">Info</h3>
                            <p class="text-sm text-muted">Detail pengajuan</p>
                        </div>
                    </div>
                </div>
                <div class="card-body" style="display: flex; flex-direction: column; gap: 0.75rem;">
                    <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid var(--border);">
                        <span style="color: var(--text-muted); font-size: 0.85rem;">Diajukan</span>
                        <span style="font-weight: 600; font-size: 0.85rem;">{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i') }}</span>
                    </div>
                    @if($item->updated_at != $item->created_at)
                        <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid var(--border);">
                            <span style="color: var(--text-muted); font-size: 0.85rem;">Diperbarui</span>
                            <span style="font-weight: 600; font-size: 0.85rem;">{{ \Carbon\Carbon::parse($item->updated_at)->format('d/m/Y H:i') }}</span>
                        </div>
                    @endif
                    @if($item->verifikator_id)
                        <div style="display: flex; justify-content: space-between; padding: 0.5rem 0;">
                            <span style="color: var(--text-muted); font-size: 0.85rem;">Verifikator</span>
                            <span style="font-weight: 600; font-size: 0.85rem; color: var(--primary);">#{{ $item->verifikator_id }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- File Modal -->
    <div id="fileModal" class="modal-backdrop">
        <div class="modal" style="max-width: 600px;">
            <div class="modal-header">
                <div>
                    <h2 class="modal-title" id="modalFileTitle">Preview Dokumen</h2>
                    <p class="text-sm text-muted" id="modalFileInfo"></p>
                </div>
                <button onclick="closeFileModal()" class="modal-close">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="modal-body" id="modalFileContent" style="padding: 1rem 0;">
            </div>
            <div class="modal-footer">
                <button onclick="closeFileModal()" class="btn btn-secondary">Tutup</button>
                <a id="modalDownloadBtn" href="#" class="btn btn-primary" download>
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Download
                </a>
            </div>
        </div>
    </div>

    <style>
        .files-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
        }

        .file-card {
            flex: 0 0 calc(33.333% - 0.5rem);
            min-width: 180px;
            max-width: calc(33.333% - 0.5rem);
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 1rem;
            background: var(--secondary);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            text-align: center;
            transition: all 0.2s ease;
            box-sizing: border-box;
        }

        .file-card.has-file {
            cursor: pointer;
        }

        .file-card.has-file:hover {
            border-color: var(--primary);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(200,154,43,0.15);
        }

        .file-card.no-file {
            opacity: 0.6;
        }

        .file-icon-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 3rem;
            height: 3rem;
            border-radius: var(--radius-sm);
            margin-bottom: 0.5rem;
            background: rgba(200,154,43,0.1);
            color: var(--primary);
            flex-shrink: 0;
        }

        .file-card.no-file .file-icon-wrapper {
            background: var(--secondary);
            color: var(--text-muted);
        }

        .file-info {
            width: 100%;
            min-width: 0;
        }

        .file-title {
            font-weight: 600;
            font-size: 0.75rem;
            color: var(--text-primary);
            margin-bottom: 0.25rem;
            white-space: normal;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            line-height: 1.3;
        }

        .file-name {
            font-family: monospace;
            font-size: 0.65rem;
            color: var(--text-muted);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin-bottom: 0.125rem;
        }

        .file-size {
            font-size: 0.65rem;
            color: var(--text-muted);
        }

        .file-status {
            font-size: 0.7rem;
            color: var(--text-muted);
            font-style: italic;
        }

        .preview-image {
            max-width: 100%;
            max-height: 400px;
            object-fit: contain;
            border-radius: var(--radius-sm);
            margin: 0 auto;
            display: block;
        }

        .preview-pdf {
            width: 100%;
            height: 400px;
            border: none;
            border-radius: var(--radius-sm);
            background: var(--secondary);
        }

        .preview-placeholder {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 3rem;
            background: var(--secondary);
            border-radius: var(--radius);
            color: var(--text-muted);
        }

        .preview-placeholder svg {
            width: 4rem;
            height: 4rem;
            margin-bottom: 1rem;
        }
    </style>

    <script>
        function openFileModal(e, title, filename, size, previewUrl, downloadUrl, fileType) {
            e.preventDefault();
            e.stopPropagation();

            document.getElementById('modalFileTitle').textContent = title;
            document.getElementById('modalFileInfo').textContent = filename + ' (' + size + ')';
            document.getElementById('modalDownloadBtn').href = downloadUrl;

            const content = document.getElementById('modalFileContent');

            if (fileType === 'image') {
                content.innerHTML = '<img src="' + previewUrl + '" alt="' + title + '" class="preview-image">';
            } else if (fileType === 'pdf') {
                content.innerHTML = '<iframe src="' + previewUrl + '" class="preview-pdf" title="' + title + '"></iframe>';
            } else {
                content.innerHTML = '<div class="preview-placeholder"><p>File tidak dapat di-preview</p><p style="font-size: 0.85rem; margin-top: 0.5rem;">Klik Download untuk melihat file</p></div>';
            }

            document.getElementById('fileModal').classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeFileModal() {
            document.getElementById('fileModal').classList.remove('active');
            document.body.style.overflow = '';
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeFileModal();
            }
        });

        document.getElementById('fileModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeFileModal();
            }
        });
    </script>
</x-admin.layouts.app>
