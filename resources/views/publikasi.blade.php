<x-layouts.app title="Publikasi - SILATAR">
    <main class="neo-mirai">
        <section class="hero-page" style="background-image: url('/assets/img/template/news-bg.webp'); background-size: cover; background-position: center top; padding: 120px 2rem 4rem; min-height: 350px;">
            <div class="hero-page-content" style="max-width: 36rem; margin: 0 auto; text-align: center;">
                <p class="section-label-gold section-label-sm">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14,2 14,8 20,8"/></svg>
                    Arsip Dokumen
                </p>
                <h1 class="hero-page-title" style="display: flex; align-items: center; justify-content: center; gap: 0.75rem;">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="var(--gold)" stroke-width="1.5"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    Publikasi
                </h1>
                <p style="color: var(--ink-soft); font-size: 1rem; max-width: 28rem; margin: 0 auto;">Kumpulan dokumen resmi Kementerian Agama Tanah Datar yang dapat diunduh oleh masyarakat untuk keperluan informasi atau referensi.</p>
                <div class="hero-page-actions">
                    <a href="{{ url('/') }}" class="neo-hero-cta">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
                        Beranda
                    </a>
                    <a href="{{ route('pelayanan') }}" class="neo-hero-cta neo-hero-cta-primary">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Lihat Layanan
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h12m-5-5 5 5-5 5"/></svg>
                    </a>
                </div>
            </div>
        </section>

        <div class="section-divider wave-rounded"></div>

        <section class="page-content">
            <div class="content-centered">
                <form method="GET" action="{{ route('publikasi') }}" class="neo-publication-search mb-4" role="search">
                    <input type="text" name="search" value="{{ $search ?? '' }}" class="neo-publication-search-input" placeholder="Cari judul publikasi, deskripsi, atau nama file...">
                    <button type="submit" class="neo-btn">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        Cari
                    </button>
                </form>

                @if($categories->count() > 0)
                <div class="neo-publication-filters mb-6">
                    <a href="{{ route('publikasi') }}" class="neo-tab {{ !$selectedCategory ? 'is-active' : '' }}">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                        Semua
                    </a>
                    @foreach($categories as $category)
                    <a href="{{ route('publikasi', ['category' => $category]) }}" class="neo-tab {{ $selectedCategory === $category ? 'is-active' : '' }}">
                        {{ $category }}
                    </a>
                    @endforeach
                </div>
                @endif

                @if($publikasi->count() > 0)
                <div class="neo-publication-list">
                    @foreach($publikasi as $item)
                    <article class="neo-publication-card">
                        <div class="neo-publication-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14,2 14,8 20,8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                        </div>
                        <div class="neo-publication-body">
                            @if($item->category)
                            <span class="neo-badge neo-badge-outline" style="width: fit-content;">{{ $item->category }}</span>
                            @endif
                            <h2 class="neo-publication-title">{{ $item->title }}</h2>
                            <p class="neo-publication-desc">{{ $item->description }}</p>
                            <div class="neo-publication-meta">
                                <span class="neo-publication-meta-item">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                    {{ $item->published_at ? \Carbon\Carbon::parse($item->published_at)->format('d M Y') : \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}
                                </span>
                                <span class="neo-publication-meta-item">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14,2 14,8 20,8"/></svg>
                                    {{ strtoupper(pathinfo($item->original_filename, PATHINFO_EXTENSION) ?: 'FILE') }}
                                </span>
                                @if($item->file_size)
                                <span class="neo-publication-meta-item">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7,10 12,15 17,10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                                    {{ round($item->file_size / 1024, 1) }} KB
                                </span>
                                @endif
                                <span class="neo-publication-meta-item">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                    {{ number_format($item->download_count) }} unduhan
                                </span>
                            </div>
                        </div>
                        <a href="{{ route('publikasi.download', $item->slug) }}" class="neo-publication-action" title="Unduh {{ $item->original_filename }}">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            Unduh
                        </a>
                    </article>
                    @endforeach
                </div>

                @if($publikasi->hasPages())
                <div class="neo-pagination-wrapper" style="margin-top: 3rem;">
                    <div class="neo-pagination">
                        @if($publikasi->onFirstPage())
                            <span class="neo-tab" style="opacity: 0.5; cursor: not-allowed;">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 19l-7-7 7-7"/></svg>
                                Prev
                            </span>
                        @else
                            <a href="{{ $publikasi->previousPageUrl() }}" class="neo-tab">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 19l-7-7 7-7"/></svg>
                                Prev
                            </a>
                        @endif

                        @foreach($publikasi->getUrlRange(1, $publikasi->lastPage()) as $page => $url)
                            @if($page == $publikasi->currentPage())
                                <span class="neo-tab is-active">{{ $page }}</span>
                            @elseif($page <= 3 || $page > $publikasi->lastPage() - 2 || abs($page - $publikasi->currentPage()) <= 1)
                                <a href="{{ $url }}" class="neo-tab">{{ $page }}</a>
                            @elseif($page == 4 || $page == $publikasi->lastPage() - 3)
                                <span class="neo-tab" style="cursor: default;">...</span>
                            @endif
                        @endforeach

                        @if($publikasi->hasMorePages())
                            <a href="{{ $publikasi->nextPageUrl() }}" class="neo-tab">
                                Next
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 5l7 7-7 7"/></svg>
                            </a>
                        @else
                            <span class="neo-tab" style="opacity: 0.5; cursor: not-allowed;">
                                Next
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 5l7 7-7 7"/></svg>
                            </span>
                        @endif
                    </div>
                </div>
                @endif

                @else
                <div class="neo-empty">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14,2 14,8 20,8"/>
                    </svg>
                    <p class="neo-empty-title">Belum Ada Publikasi</p>
                    <p class="neo-empty-text">Saat ini belum ada dokumen publikasi yang tersedia untuk diunduh.</p>
                    <a href="{{ route('home') }}" class="neo-btn" style="margin-top: 1.5rem;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
                        Kembali ke Beranda
                    </a>
                </div>
                @endif
            </div>
        </section>

        <footer class="site-footer">
            <a class="brand-lockup brand-lockup-small" href="{{ url("/") }}" aria-label="SILATAR home">
                <span class="brand-mark" aria-hidden="true"><span></span></span>
                <span class="brand-word"><span>SILATAR</span><span>V2</span></span>
            </a>
            <p>Portal Layanan Digital Kementerian Agama Tanah Datar</p>
            <nav aria-label="Footer navigation">
                <a href="{{ url("/") }}">Beranda</a>
                <a href="{{ route('pelayanan') }}">Pelayanan</a>
                <a href="{{ route('satuan-kerja') }}">Unit Kerja</a>
                <a href="{{ route('publikasi') }}">Publikasi</a>
            </nav>
            <div class="footer-copyright"><span>&copy; {{ date("Y") }} SILATAR - Kementerian Agama Tanah Datar</span></div>
        </footer>
    </main>
</x-layouts.app>
