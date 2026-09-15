<x-admin.layouts.app title="{{ $title ?? 'Manajemen Publikasi' }}">
    <div class="page-header">
        <div class="page-header-content">
            <span class="page-label">// Dokumen</span>
            <h1 class="page-title">Manajemen Publikasi</h1>
            <p class="page-subtitle">Kelola dokumen publikasi yang dapat diunduh oleh pengguna portal SILATAR</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.publikasi.create') }}" class="btn btn-primary">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 4v16m8-8H4"/></svg>
                Tambah Publikasi
            </a>
        </div>
    </div>

    <div class="grid-4 mb-6">
        <div class="stat-card">
            <div class="stat-icon indigo">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14,2 14,8 20,8"/></svg>
            </div>
            <div class="stat-content">
                <span class="stat-label">Total</span>
                <span class="stat-value">{{ $stats['total'] }}</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon emerald">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 13l4 4L19 7"/></svg>
            </div>
            <div class="stat-content">
                <span class="stat-label">Published</span>
                <span class="stat-value">{{ $stats['published'] }}</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon amber">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            </div>
            <div class="stat-content">
                <span class="stat-label">Draft</span>
                <span class="stat-value">{{ $stats['draft'] }}</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon violet">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 16v1a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            </div>
            <div class="stat-content">
                <span class="stat-label">Download</span>
                <span class="stat-value">{{ number_format($stats['downloads']) }}</span>
            </div>
        </div>
    </div>

    <div class="card mb-6">
        <div class="card-header">
            <div class="flex items-center gap-3">
                <div class="stat-icon emerald" style="width: 36px; height: 36px;">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V16l-4-4z"/></svg>
                </div>
                <div>
                    <h3 class="card-title">Filter Data</h3>
                    <p class="text-sm text-muted">Cari publikasi berdasarkan judul, deskripsi, atau nama file</p>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.publikasi.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="form-group">
                    <label class="form-label">Pencarian</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari publikasi..." class="form-input">
                </div>
                <div class="form-group">
                    <label class="form-label">Kategori</label>
                    <select name="category" class="form-select">
                        <option value="">Semua</option>
                        @foreach($categories as $key => $label)
                        <option value="{{ $key }}" {{ request('category') == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua</option>
                        @foreach($statuses as $key => $label)
                        <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-center gap-3 md:col-span-3" style="align-self: flex-end;">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('admin.publikasi.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="table-header">
            <div class="table-title-icon">
                <div class="icon" style="background: rgba(99,102,241,0.1);">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14,2 14,8 20,8"/></svg>
                </div>
                <div>
                    <h3 class="table-title">Daftar Publikasi</h3>
                    <p class="table-subtitle">Total {{ $publikasi->total() }} publikasi</p>
                </div>
            </div>
        </div>
        <div class="table-wrapper">
            <table class="table">
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Kategori</th>
                        <th>Status</th>
                        <th>Unduhan</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($publikasi as $item)
                    <tr>
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0" style="background: rgba(212,168,83,0.1); color: var(--gold);">
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14,2 14,8 20,8"/></svg>
                                </div>
                                <div>
                                    <span class="table-user-name">{{ $item->title }}</span>
                                    <span class="table-user-email line-clamp-1">{{ $item->original_filename }}</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge badge-neutral">{{ $categories[$item->category] ?? $item->category ?? '-' }}</span>
                        </td>
                        <td>
                            <span class="badge @if($item->status === 'published') badge-success @elseif($item->status === 'draft') badge-warning @else badge-neutral @endif">
                                {{ $statuses[$item->status] ?? ucfirst($item->status) }}
                            </span>
                        </td>
                        <td>
                            <span class="text-primary font-medium">{{ number_format($item->download_count) }}</span>
                        </td>
                        <td>
                            <span class="table-time">{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}</span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('admin.publikasi.edit', $item->id) }}" class="action-btn" title="Edit">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form method="POST" action="{{ route('admin.publikasi.destroy', $item->id) }}" onsubmit="return confirm('Yakin ingin menghapus publikasi ini?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-btn delete" title="Hapus">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-12">
                            <div class="empty-state">
                                <svg class="w-16 h-16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14,2 14,8 20,8"/></svg>
                                <p class="empty-state-title">Belum Ada Publikasi</p>
                                <p class="empty-state-text">Mulai tambahkan dokumen publikasi untuk portal SILATAR</p>
                                <a href="{{ route('admin.publikasi.create') }}" class="btn btn-primary mt-4">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 4v16m8-8H4"/></svg>
                                    Tambah Publikasi
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($publikasi->hasPages())
        <div class="px-6 py-4 border-t flex flex-col sm:flex-row items-center justify-between gap-4">
            <p class="text-sm text-muted">Menampilkan {{ $publikasi->firstItem() ?? 0 }} - {{ $publikasi->lastItem() ?? 0 }} dari {{ $publikasi->total() }} data</p>
            <div class="pagination">
                @if($publikasi->onFirstPage())
                    <span class="disabled">Sebelumnya</span>
                @else
                    <a href="{{ $publikasi->previousPageUrl() }}">Sebelumnya</a>
                @endif
                @foreach($publikasi->getUrlRange(1, $publikasi->lastPage()) as $page => $url)
                    @if($page == $publikasi->currentPage())
                        <span class="active">{{ $page }}</span>
                    @elseif($page <= 3 || $page > $publikasi->lastPage() - 2 || abs($page - $publikasi->currentPage()) < 2)
                        <a href="{{ $url }}">{{ $page }}</a>
                    @elseif($loop->index == 2 || $loop->index == $publikasi->lastPage() - 3)
                        <span class="disabled">...</span>
                    @endif
                @endforeach
                @if($publikasi->hasMorePages())
                    <a href="{{ $publikasi->nextPageUrl() }}">Selanjutnya</a>
                @else
                    <span class="disabled">Selanjutnya</span>
                @endif
            </div>
        </div>
        @endif
    </div>
</x-admin.layouts.app>
