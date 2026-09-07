<x-admin.layouts.app>
    <div class="page-header">
        <div class="page-header-content">
            <span class="page-label">// Surat Manual</span>
            <h1 class="page-title">Detail Surat</h1>
            <p class="page-subtitle">No. Req: {{ $surat->no_req }}</p>
        </div>
        <div class="page-header-actions">
            <a href="{{ route('admin.surat-manual.print', $surat->no_req) }}" class="btn btn-primary" target="_blank">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                Cetak Bukti
            </a>
            <a href="{{ route('admin.surat-manual.edit', $surat->id) }}" class="btn btn-secondary">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit
            </a>
            <a href="{{ route('admin.surat-manual.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success">
        <svg class="alert-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <span class="alert-message">{{ session('success') }}</span>
    </div>
    @endif

    <div class="grid gap-6 lg:grid-cols-2">
        <!-- Info Surat -->
        <div class="card">
            <div class="card-header">
                <div class="flex items-center gap-3">
                    <div class="stat-icon emerald" style="width: 36px; height: 36px;">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="card-title">Informasi Surat</h3>
                        <p class="text-sm text-muted">Data surat manual</p>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <dl class="space-y-4">
                    <div>
                        <dt class="text-xs font-medium text-muted mb-1">Pengirim</dt>
                        <dd class="font-medium">{{ $surat->pemohon }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-muted mb-1">Asal Pengirim</dt>
                        <dd>{{ $surat->asal_pengirim ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-muted mb-1">No. Requisition</dt>
                        <dd><code class="text-sm">{{ $surat->no_req }}</code></dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-muted mb-1">Nomor Surat</dt>
                        <dd class="font-medium">{{ $surat->no_surat }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-muted mb-1">Tanggal Surat</dt>
                        <dd>{{ $surat->tgl_surat ? \Carbon\Carbon::parse($surat->tgl_surat)->format('d F Y') : '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-muted mb-1">Judul / Perihal</dt>
                        <dd>{{ $surat->judul }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-muted mb-1">Tujuan</dt>
                        <dd>{{ $surat->tujuan_name ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-muted mb-1">Unit Kerja</dt>
                        <dd>{{ $surat->dept_name ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-muted mb-1">Layanan</dt>
                        <dd>{{ $surat->layanan_name ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-muted mb-1">Status</dt>
                        <dd>
                            <span class="badge badge-success">{{ $surat->status }}</span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-muted mb-1">Input Oleh</dt>
                        <dd>{{ $surat->pemohon ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-muted mb-1">Tanggal Input</dt>
                        <dd>{{ $surat->created_at ? \Carbon\Carbon::parse($surat->created_at)->format('d F Y H:i') : '-' }}</dd>
                    </div>
                    @if($surat->deskripsi)
                    <div>
                        <dt class="text-xs font-medium text-muted mb-1">Keterangan</dt>
                        <dd class="whitespace-pre-line">{{ $surat->deskripsi }}</dd>
                    </div>
                    @endif
                </dl>
            </div>
        </div>

        <!-- File -->
        <div class="card">
            <div class="card-header">
                <div class="flex items-center gap-3">
                    <div class="stat-icon cyan" style="width: 36px; height: 36px;">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="card-title">File Surat</h3>
                        <p class="text-sm text-muted">File yang dilampirkan</p>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="space-y-4">
                    <!-- File Surat Utama -->
                    <div>
                        <p class="text-xs font-medium text-muted mb-2">File Surat Utama</p>
                        @if($surat->file_surat)
                        <div class="flex items-center gap-3 p-3 rounded-lg" style="background: var(--bg-secondary);">
                            <svg class="w-8 h-8 text-emerald-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <div class="flex-1">
                                <p class="text-sm font-medium">{{ $surat->file_surat }}</p>
                            </div>
                            <a href="{{ route('admin.surat-manual.download', $surat->id) }}" class="btn btn-sm btn-primary">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Unduh
                            </a>
                        </div>
                        @else
                        <p class="text-sm text-muted">Tidak ada file</p>
                        @endif
                    </div>

                    <!-- Lampiran -->
                    <div>
                        <p class="text-xs font-medium text-muted mb-2">Lampiran</p>
                        @if($surat->lampiran)
                        <div class="flex items-center gap-3 p-3 rounded-lg" style="background: var(--bg-secondary);">
                            <svg class="w-8 h-8 text-cyan-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                            </svg>
                            <div class="flex-1">
                                <p class="text-sm font-medium">{{ $surat->lampiran }}</p>
                            </div>
                        </div>
                        @else
                        <p class="text-sm text-muted">Tidak ada lampiran</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="flex justify-end gap-3 mt-6">
        <form method="POST" action="{{ route('admin.surat-manual.destroy', $surat->id) }}" x-data
              @submit.prevent="if(confirm('Yakin ingin menghapus surat ini? Tindakan ini tidak dapat dibatalkan.')) $el.submit()">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                Hapus Surat
            </button>
        </form>
    </div>
</x-admin.layouts.app>
