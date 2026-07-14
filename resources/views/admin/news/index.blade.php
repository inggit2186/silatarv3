<x-admin.layouts.app title="{{ $title ?? 'Manajemen Berita' }}">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <span class="page-label">// Konten</span>
            <h1 class="page-title">Manajemen Berita</h1>
            <p class="page-subtitle">Kelola semua berita dan pengumuman portal SILATAR</p>
        </div>
        <div class="page-actions">
            @if($canCreate)
            <a href="{{ route('admin.news.create') }}" class="btn btn-primary">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Berita
            </a>
            @endif
        </div>
    </div>

    <!-- Stats -->
    <div class="grid-4 mb-6">
        <div class="stat-card">
            <div class="stat-icon indigo">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V9a2 2 0 012-2h2a2 2 0 012 2v9a2 2 0 01-2 2h-2z"/>
                </svg>
            </div>
            <div class="stat-content">
                <span class="stat-label">Total Berita</span>
                <span class="stat-value">{{ $stats['total'] }}</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon emerald">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <div class="stat-content">
                <span class="stat-label">Published</span>
                <span class="stat-value">{{ $stats['published'] }}</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon amber">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
            </div>
            <div class="stat-content">
                <span class="stat-label">Draft</span>
                <span class="stat-value">{{ $stats['draft'] }}</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon violet">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                </svg>
            </div>
            <div class="stat-content">
                <span class="stat-label">Archived</span>
                <span class="stat-value">{{ $stats['archived'] }}</span>
            </div>
        </div>
    </div>

    <!-- Filters Card -->
    <div class="card mb-6">
        <div class="card-header">
            <div class="flex items-center gap-3">
                <div class="stat-icon emerald" style="width: 36px; height: 36px;">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V16l-4-4z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="card-title">Filter Data</h3>
                    <p class="text-sm text-muted">Cari berita berdasarkan kriteria</p>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.news.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="form-group">
                    <label class="form-label">Pencarian</label>
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul berita..." class="form-input pl-10">
                    </div>
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
                        <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                    </select>
                </div>

                <div class="col-span-1 md:col-span-2 lg:col-span-4 flex items-center gap-3" style="align-self: flex-end;">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('admin.news.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- News List -->
    <div class="card">
        <div class="table-header">
            <div class="table-title-icon">
                <div class="icon" style="background: rgba(99,102,241,0.1);">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V9a2 2 0 012-2h2a2 2 0 012 2v9a2 2 0 01-2 2h-2z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="table-title">Daftar Berita</h3>
                    <p class="table-subtitle">Total {{ $news->total() }} berita</p>
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
                        <th>Tanggal</th>
                        <th>View</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($news as $item)
                    <tr>
                        <td>
                            <div class="flex items-center gap-3">
                                @if($item->image)
                                <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}" class="w-12 h-12 rounded-lg object-cover flex-shrink-0">
                                @endif
                                <div>
                                    <span class="table-user-name">{{ $item->title }}</span>
                                    <span class="table-user-email line-clamp-1">{{ $item->excerpt }}</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge @if($item->category == 'featured') badge-info
                                @elseif($item->category == 'pengumuman') badge-neutral
                                @elseif($item->category == 'kegiatan') badge-success
                                @elseif($item->category == 'layanan') badge-warning
                                @else badge-neutral @endif">
                                {{ $categories[$item->category] ?? $item->category }}
                            </span>
                        </td>
                        <td>
                            <span class="badge @if($item->status == 'published') badge-success
                                @elseif($item->status == 'draft') badge-warning
                                @else badge-neutral @endif">
                                @if($item->status == 'published') Published
                                @elseif($item->status == 'draft') Draft
                                @else Archived @endif
                            </span>
                            @if($item->is_featured)
                            <span class="badge badge-warning ml-1">Featured</span>
                            @endif
                        </td>
                        <td>
                            <span class="table-time">{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}</span>
                        </td>
                        <td>
                            <span class="text-primary font-medium">{{ number_format($item->view_count ?? 0) }}</span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('admin.news.edit', $item->id) }}" class="action-btn" title="Edit">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                @if($canCreate)
                                <form method="POST" action="{{ route('admin.news.destroy', $item->id) }}" onsubmit="return confirm('Yakin ingin menghapus berita ini?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-btn delete" title="Hapus">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-12">
                            <div class="empty-state">
                                <svg class="w-16 h-16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V9a2 2 0 012-2h2a2 2 0 012 2v9a2 2 0 01-2 2h-2z"/>
                                </svg>
                                <p class="empty-state-title">Belum Ada Berita</p>
                                <p class="empty-state-text">Mulai tambahkan berita pertama untuk portal SILATAR</p>
                                @if($canCreate)
                                <a href="{{ route('admin.news.create') }}" class="btn btn-primary mt-4">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                                    Tambah Berita
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($news->hasPages())
            <div class="px-6 py-4 border-t flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-sm text-muted">Menampilkan {{ $news->firstItem() ?? 0 }} - {{ $news->lastItem() ?? 0 }} dari {{ $news->total() }} data</p>
                <div class="pagination">
                    @if($news->onFirstPage())
                        <span class="disabled">Sebelumnya</span>
                    @else
                        <a href="{{ $news->previousPageUrl() }}">Sebelumnya</a>
                    @endif
                    @foreach($news->getUrlRange(1, $news->lastPage()) as $page => $url)
                        @if($page <= 3 || $page > $news->lastPage() - 2 || abs($page - $news->currentPage()) < 2)
                            @if($page == $news->currentPage())
                                <span class="active">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}">{{ $page }}</a>
                            @endif
                        @elseif($loop->index == 2 || $loop->index == $news->lastPage() - 3)
                            <span class="disabled">...</span>
                        @endif
                    @endforeach
                    @if($news->hasMorePages())
                        <a href="{{ $news->nextPageUrl() }}">Selanjutnya</a>
                    @else
                        <span class="disabled">Selanjutnya</span>
                    @endif
                </div>
            </div>
        @endif
    </div>
</x-admin.layouts.app>
