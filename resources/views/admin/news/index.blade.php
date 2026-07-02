<x-admin.layouts.app title="{{ $title ?? 'Manajemen Berita' }}">
    {{-- Page Header with Neo Mirai Style --}}
    <div class="neo-page-header">
        <div class="flex items-center gap-4">
            <div class="neo-section-icon">
                <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V9a2 2 0 012-2h2a2 2 0 012 2v9a2 2 0 01-2 2h-2z"/>
                </svg>
            </div>
            <div class="neo-page-header-content">
                <span class="neo-page-label">Konten</span>
                <h1 class="neo-page-title">Manajemen Berita</h1>
                <p class="neo-page-subtitle">Kelola semua berita dan pengumuman portal SILATAR</p>
            </div>
        </div>
        <div class="neo-page-actions">
            @if($canCreate)
            <a href="{{ route('admin.news.create') }}" class="neo-btn">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Tambah Berita</span>
            </a>
            @endif
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="neo-stats-grid mb-8">
        <div class="neo-stat-card">
            <div class="neo-stat-icon indigo">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V9a2 2 0 012-2h2a2 2 0 012 2v9a2 2 0 01-2 2h-2z"/>
                </svg>
            </div>
            <div class="neo-stat-content">
                <span class="neo-stat-label">Total Berita</span>
                <span class="neo-stat-value">{{ $stats['total'] }}</span>
            </div>
        </div>
        <div class="neo-stat-card">
            <div class="neo-stat-icon emerald">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <div class="neo-stat-content">
                <span class="neo-stat-label">Published</span>
                <span class="neo-stat-value">{{ $stats['published'] }}</span>
            </div>
        </div>
        <div class="neo-stat-card">
            <div class="neo-stat-icon amber">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
            </div>
            <div class="neo-stat-content">
                <span class="neo-stat-label">Draft</span>
                <span class="neo-stat-value">{{ $stats['draft'] }}</span>
            </div>
        </div>
        <div class="neo-stat-card">
            <div class="neo-stat-icon violet">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                </svg>
            </div>
            <div class="neo-stat-content">
                <span class="neo-stat-label">Archived</span>
                <span class="neo-stat-value">{{ $stats['archived'] }}</span>
            </div>
        </div>
        <div class="neo-stat-card">
            <div class="neo-stat-icon rose">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
            </div>
            <div class="neo-stat-content">
                <span class="neo-stat-label">Total View</span>
                <span class="neo-stat-value">{{ number_format($stats['total_views'] ?? 0) }}</span>
            </div>
        </div>
    </div>

    {{-- Filters Card --}}
    <div class="neo-card mb-6">
        <div class="neo-card-body">
            <form method="GET" class="cyber-form-grid">
                {{-- Search --}}
                <div class="cyber-form-group">
                    <label class="cyber-form-label">
                        <svg class="inline h-4 w-4 mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Pencarian
                    </label>
                    <div class="cyber-input-wrapper">
                        <svg class="cyber-input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul berita..." class="cyber-input">
                    </div>
                </div>

                {{-- Category Filter --}}
                <div class="cyber-form-group">
                    <label class="cyber-form-label">
                        <svg class="inline h-4 w-4 mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        Kategori
                    </label>
                    <select name="category" class="cyber-select">
                        <option value="">Semua</option>
                        @foreach($categories as $key => $label)
                        <option value="{{ $key }}" {{ request('category') == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Status Filter --}}
                <div class="cyber-form-group">
                    <label class="cyber-form-label">
                        <svg class="inline h-4 w-4 mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Status
                    </label>
                    <select name="status" class="cyber-select">
                        <option value="">Semua</option>
                        <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                    </select>
                </div>

                {{-- Filter Buttons --}}
                <div class="cyber-form-actions" style="align-self: flex-end;">
                    <button type="submit" class="neo-btn">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V16l-4-4z"/>
                        </svg>
                        Filter
                    </button>
                    <a href="{{ route('admin.news.index') }}" class="neo-btn-secondary">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- News List --}}
    <div class="space-y-4">
        @forelse($news as $item)
        <div class="neo-card group hover:border-gold transition-all duration-200">
            <div class="neo-card-body">
                <div class="flex flex-col md:flex-row gap-4">
                    @if($item->image)
                    <div class="flex-shrink-0">
                        <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}" class="h-32 w-full rounded-lg object-cover md:w-48">
                    </div>
                    @endif
                    <div class="flex-1 min-w-0">
                        <div class="flex flex-wrap items-center gap-2 mb-2">
                            <span class="neo-badge @if($item->category == 'featured') neo-badge-info
                                @elseif($item->category == 'pengumuman') neo-badge-neutral
                                @elseif($item->category == 'kegiatan') neo-badge-success
                                @elseif($item->category == 'layanan') neo-badge-warning
                                @else neo-badge-neutral @endif">
                                {{ $categories[$item->category] ?? $item->category }}
                            </span>
                            <span class="neo-badge @if($item->status == 'published') neo-badge-success
                                @elseif($item->status == 'draft') neo-badge-warning
                                @else neo-badge-neutral @endif">
                                @if($item->status == 'published')
                                    Published
                                @elseif($item->status == 'draft')
                                    Draft
                                @else
                                    Archived
                                @endif
                            </span>
                            @if($item->is_featured)
                            <span class="neo-badge neo-badge-warning">
                                Featured
                            </span>
                            @endif
                        </div>
                        <h3 class="neo-section-title group-hover:text-gold transition-colors line-clamp-2 mb-2">
                            {{ $item->title }}
                        </h3>
                        <p class="text-sm" style="color: var(--ink-soft); font-family: var(--font-serif); line-clamp-2 mb-3;">{{ $item->excerpt }}</p>
                        <div class="flex items-center justify-between flex-wrap gap-3">
                            <div class="flex items-center gap-4 text-xs" style="color: var(--ink-soft); font-family: var(--font-mono);">
                                <span class="flex items-center gap-1.5">
                                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    {{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}
                                </span>
                                @if($item->publish_date)
                                <span class="flex items-center gap-1.5">
                                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ \Carbon\Carbon::parse($item->publish_date)->format('d M Y') }}
                                </span>
                                @endif
                                <span class="flex items-center gap-1.5" style="color: var(--gold);">
                                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    {{ number_format($item->view_count ?? 0) }} view
                                </span>
                            </div>
                            @if($canCreate)
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.news.edit', $item->id) }}" class="neo-btn-table edit" title="Edit">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <form method="POST" action="{{ route('admin.news.destroy', $item->id) }}" onsubmit="return confirm('Yakin ingin menghapus berita ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="neo-btn-table delete" title="Hapus">
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="neo-card">
            <div class="neo-empty neo-empty-success">
                <svg class="h-16 w-16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V9a2 2 0 012-2h2a2 2 0 012 2v9a2 2 0 01-2 2h-2z"/>
                </svg>
                <h3 class="neo-empty-title">Belum Ada Berita</h3>
                <p class="neo-empty-text">Mulai tambahkan berita pertama untuk portal SILATAR</p>
                @if($canCreate)
                <a href="{{ route('admin.news.create') }}" class="neo-btn mt-4">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah Berita
                </a>
                @endif
            </div>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($news->hasPages())
    <div class="neo-pagination">
        @if($news->onFirstPage())
            <span class="neo-pagination-item disabled">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
                Sebelumnya
            </span>
        @else
            <a href="{{ $news->previousPageUrl() }}" class="neo-pagination-item">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
                Sebelumnya
            </a>
        @endif

        @foreach($news->getUrlRange(1, $news->lastPage()) as $page => $url)
            @if($page == $news->currentPage())
                <span class="neo-pagination-item active">{{ $page }}</span>
            @else
                <a href="{{ $url }}" class="neo-pagination-item">{{ $page }}</a>
            @endif
        @endforeach

        @if($news->hasMorePages())
            <a href="{{ $news->nextPageUrl() }}" class="neo-pagination-item">
                Selanjutnya
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        @else
            <span class="neo-pagination-item disabled">
                Selanjutnya
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </span>
        @endif
    </div>
    @endif
</x-admin.layouts.app>
