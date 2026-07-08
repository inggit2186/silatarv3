<x-admin.layouts.app>
    <?php $title = 'Detail Verifikasi TPG'; ?>

    <!-- Page Header -->
    <div class="admin-page-header">
        <div class="flex items-center gap-4">
            <div class="cyber-header-icon">
                <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
            </div>
            <div>
                <h1 class="admin-page-title">
                    <span class="cyber-title-text">Detail Verifikasi TPG</span>
                </h1>
                <p class="admin-page-subtitle">
                    <svg class="inline h-4 w-4 mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                    No. Request: <strong style="color: var(--gold);">{{ $item->noreq }}</strong>
                </p>
            </div>
        </div>
        <div class="admin-page-actions">
            <?php
                $statusColors = [
                    'SUBMITTED' => 'cyber-badge-amber',
                    'PENDING' => 'cyber-badge-amber',
                    'DITERIMA' => 'cyber-badge-cyan',
                    'DIPROSES' => 'cyber-badge-cyan',
                    'SUKSES' => 'cyber-badge-emerald',
                    'DITOLAK' => 'cyber-badge-rose',
                ];
                $statusClass = $statusColors[$item->status] ?? 'cyber-badge-slate';
            ?>
            <span class="cyber-role-badge {{ $statusClass }}" style="font-size: 0.8rem; padding: 0.375rem 0.875rem;">
                {{ $item->status }}
            </span>
            <a href="{{ route('admin.tpg.index') }}" class="cyber-btn-secondary">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
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
            <div class="cyber-card">
                <div class="cyber-card-header">
                    <div class="flex items-center gap-3">
                        <div class="cyber-section-icon">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="cyber-section-title">Informasi Pemohon</h3>
                            <p class="cyber-section-subtitle">Data pengaju layanan</p>
                        </div>
                    </div>
                </div>
                <div class="cyber-card-body">
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.25rem;">
                        <div>
                            <p style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--ink-soft); margin-bottom: 0.25rem;">Nama Pemohon</p>
                            <p style="font-weight: 600; color: var(--ink);">{{ $user->name ?? '-' }}</p>
                        </div>
                        <div>
                            <p style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--ink-soft); margin-bottom: 0.25rem;">Unit Kerja</p>
                            <p style="font-weight: 600; color: var(--ink);">{{ $dept->nama ?? '-' }}</p>
                        </div>
                        <div>
                            <p style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--ink-soft); margin-bottom: 0.25rem;">Jenis Layanan</p>
                            <p style="font-weight: 600; color: var(--ink);">{{ $tipeLabel }}</p>
                        </div>
                        <div>
                            <p style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--ink-soft); margin-bottom: 0.25rem;">Periode</p>
                            <p style="font-weight: 600; color: var(--ink);">
                                @if(isset($item->metadata_parsed['semester']))
                                    {{ $item->metadata_parsed['tahun_pelajaran'] ?? '' }} - Semester {{ $item->metadata_parsed['semester'] }}
                                @elseif(isset($item->metadata_parsed['bulan']))
                                    {{ $item->metadata_parsed['bulan'] }} {{ $item->metadata_parsed['tahun'] ?? '' }}
                                @else
                                    -
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dokumen -->
            <div class="cyber-card">
                <div class="cyber-card-header">
                    <div class="flex items-center gap-3">
                        <div class="cyber-section-icon">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="cyber-section-title">Dokumen Upload</h3>
                            <p class="cyber-section-subtitle">{{ count($item->files_parsed) }} file dilampirkan</p>
                        </div>
                    </div>
                </div>
                <div class="cyber-card-body">
                    @if(count($item->files_parsed) > 0)
                        <div class="files-grid">
                            @foreach($item->files_parsed as $index => $file)
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
                        <div style="text-align: center; padding: 2rem; color: var(--ash);">
                            <svg class="h-12 w-12 mx-auto mb-2" style="color: var(--ash);" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p>Tidak ada dokumen dilampirkan</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- File Preview Modal -->
            <div id="fileModal" class="cyber-modal-overlay">
                <div class="cyber-modal-content" style="max-width: 600px;">
                    <div class="cyber-modal-header">
                        <div>
                            <h3 class="cyber-modal-title" id="modalFileTitle">Preview Dokumen</h3>
                            <p class="cyber-modal-subtitle" id="modalFileInfo"></p>
                        </div>
                        <button onclick="closeFileModal()" class="cyber-modal-close">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    <div class="cyber-modal-body" id="modalFileContent" style="padding: 1rem 0;">
                        <!-- Preview content here -->
                    </div>
                    <div class="cyber-modal-footer">
                        <button onclick="closeFileModal()" class="cyber-btn-secondary">
                            Tutup
                        </button>
                        <a id="modalDownloadBtn" href="#" class="cyber-btn-primary" download>
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            Download
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div style="display: flex; flex-direction: column; gap: 1.5rem;">

            <!-- Form Verifikasi -->
            @if(!in_array($item->status, ['SUKSES', 'DITOLAK', 'BATAL']))
                <div class="cyber-card">
                    <div class="cyber-card-header">
                        <div class="flex items-center gap-3">
                            <div class="cyber-section-icon" style="background: linear-gradient(135deg, var(--sun) 0%, var(--sun-deep) 100%);">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="cyber-section-title">Aksi Verifikasi</h3>
                                <p class="cyber-section-subtitle">Proses persetujuan</p>
                            </div>
                        </div>
                    </div>
                    <div class="cyber-card-body">
                        <form method="POST" action="{{ route('admin.tpg.verify', $item->id) }}">
                            @csrf
                            <div style="display: flex; flex-direction: column; gap: 1rem;">
                                <div class="cyber-form-group">
                                    <label class="cyber-form-label">Ubah Status</label>
                                    <select name="status" class="cyber-select">
                                        <option value="DITERIMA">Terima (DITERIMA)</option>
                                        <option value="PENDING">Proses (PENDING)</option>
                                        <option value="SUKSES">Selesai (SUKSES)</option>
                                    </select>
                                </div>
                                <button type="submit" class="cyber-btn-primary" style="width: 100%;">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Simpan Status
                                </button>
                            </div>
                        </form>

                        <div style="border-top: 1px solid var(--line); margin: 1.25rem 0;"></div>

                        <form method="POST" action="{{ route('admin.tpg.reject', $item->id) }}">
                            @csrf
                            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                                <label class="cyber-form-label">Alasan Penolakan</label>
                                <textarea name="keterangan" class="cyber-input" rows="3" placeholder="Wajib diisi jika menolak..." style="height: auto; min-height: 80px; padding: 0.75rem;"></textarea>
                                @error('keterangan')
                                    <span style="color: #dc2626; font-size: 0.8rem;">{{ $message }}</span>
                                @enderror
                                <button type="submit" class="cyber-btn-danger" style="width: 100%;">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
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
            <div class="cyber-card">
                <div class="cyber-card-header">
                    <div class="flex items-center gap-3">
                        <div class="cyber-section-icon" style="background: var(--ink);">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="cyber-section-title">Info</h3>
                            <p class="cyber-section-subtitle">Detail pengajuan</p>
                        </div>
                    </div>
                </div>
                <div class="cyber-card-body" style="display: flex; flex-direction: column; gap: 0.75rem;">
                    <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid var(--line);">
                        <span style="color: var(--ink-soft); font-size: 0.85rem;">Diajukan</span>
                        <span style="font-weight: 600; font-size: 0.85rem;">{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i') }}</span>
                    </div>
                    @if($item->updated_at != $item->created_at)
                        <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid var(--line);">
                            <span style="color: var(--ink-soft); font-size: 0.85rem;">Diperbarui</span>
                            <span style="font-weight: 600; font-size: 0.85rem;">{{ \Carbon\Carbon::parse($item->updated_at)->format('d/m/Y H:i') }}</span>
                        </div>
                    @endif
                    @if($item->verifikator_id)
                        <div style="display: flex; justify-content: space-between; padding: 0.5rem 0;">
                            <span style="color: var(--ink-soft); font-size: 0.85rem;">Verifikator</span>
                            <span style="font-weight: 600; font-size: 0.85rem; color: var(--gold);">#{{ $item->verifikator_id }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        /* File Card Grid - Fixed width, wrap to next line */
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
            background: var(--paper);
            border: 1px solid var(--line);
            border-radius: 0.75rem;
            text-align: center;
            transition: all 0.2s ease;
            box-sizing: border-box;
        }

        .file-card.has-file {
            cursor: pointer;
        }

        .file-card.has-file:hover {
            border-color: var(--gold);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(201, 165, 90, 0.15);
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
            border-radius: 0.5rem;
            margin-bottom: 0.5rem;
            background: rgba(201, 165, 90, 0.1);
            color: var(--gold);
            flex-shrink: 0;
        }

        .file-card.no-file .file-icon-wrapper {
            background: var(--paper-deep);
            color: var(--ash);
        }

        .file-info {
            width: 100%;
            min-width: 0;
        }

        .file-title {
            font-weight: 600;
            font-size: 0.75rem;
            color: var(--ink);
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
            font-family: var(--font-mono);
            font-size: 0.65rem;
            color: var(--ink-soft);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin-bottom: 0.125rem;
        }

        .file-size {
            font-size: 0.65rem;
            color: var(--ash);
        }

        .file-status {
            font-size: 0.7rem;
            color: var(--ash);
            font-style: italic;
        }

        /* Modal Preview */
        .preview-image {
            max-width: 100%;
            max-height: 400px;
            object-fit: contain;
            border-radius: 0.5rem;
            margin: 0 auto;
            display: block;
        }

        .preview-pdf {
            width: 100%;
            height: 400px;
            border: none;
            border-radius: 0.5rem;
            background: var(--paper-deep);
        }

        .preview-placeholder {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 3rem;
            background: var(--paper-deep);
            border-radius: 0.75rem;
            color: var(--ink-soft);
        }

        .preview-placeholder svg {
            width: 4rem;
            height: 4rem;
            margin-bottom: 1rem;
            color: var(--ash);
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
