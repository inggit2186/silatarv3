<x-layouts.app title="Semua Berita - SILATAR">
    <main class="neo-mirai">
        <x-layouts.site-header />

        <!-- Hero Section -->
        <section class="hero-page" style="background-image: url('/assets/img/template/news-bg.webp'); background-size: cover; background-position: center top; padding: 120px 2rem 4rem; min-height: 350px;">
            <div style="max-width: 36rem; margin: 0 auto; text-align: center;">
                <p style="color: var(--gold); font-family: var(--font-mono); font-size: 0.65rem; text-transform: uppercase; margin: 0 0 0.5rem; display: inline-flex; align-items: center; gap: 0.5rem;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V9a2 2 0 012-2h2a2 2 0 012 2v9a2 2 0 01-2 2h-2z"/></svg>
                    Arsip Berita
                </p>
                <h1 style="font-family: var(--font-display); font-size: clamp(1.8rem, 4vw, 3rem); font-weight: 400; color: var(--ink); margin: 0 0 1rem; display: flex; align-items: center; justify-content: center; gap: 0.75rem;">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="var(--gold)" stroke-width="1.5"><path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/></svg>
                    Semua Berita
                </h1>
                <p style="color: var(--ink-soft); font-size: 1rem; max-width: 28rem; margin: 0 auto;">Berita dan informasi terkini dari Kementerian Agama Tanah Datar tentang kegiatan, pengumuman, dan informasi layanan untuk masyarakat.</p>
                <div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 1rem; margin-top: 1.5rem;">
                    <a href="{{ url('/') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.7rem 1.25rem; background: transparent; color: var(--ink); font-family: var(--font-mono); font-size: 0.7rem; font-weight: 600; text-transform: uppercase; text-decoration: none; border: 1px solid var(--line);">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9,22 9,12 15,12 15,22"/></svg>
                        Beranda
                    </a>
                    <a href="{{ route('pelayanan') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.7rem 1.25rem; background: var(--gold); color: var(--night); font-family: var(--font-mono); font-size: 0.7rem; font-weight: 700; text-transform: uppercase; text-decoration: none;">
                        Ajukan Layanan
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h12m-5-5 5 5-5 5"/></svg>
                    </a>
                </div>
            </div>
        </section>

        <!-- Section Divider -->
        <div class="section-divider wave-rounded"></div>

        <!-- Content Section -->
        <section class="page-content">
            <!-- Category Filter -->
            @if($categories->count() > 0)
            <div class="neo-tabs" style="margin-bottom: 2rem;">
                <a href="{{ route('news.index') }}"
                   class="neo-tab {{ !$selectedCategory ? 'is-active' : '' }}">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                    Semua
                </a>
                @foreach($categories as $category)
                <a href="{{ route('news.index', ['category' => $category]) }}"
                   class="neo-tab {{ $selectedCategory === $category ? 'is-active' : '' }}">
                    {{ $category }}
                </a>
                @endforeach
            </div>
            @endif

            @if($news->count() > 0)
            <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                @foreach($news as $item)
                <article>
                    <a href="{{ route('news.show', $item->slug ?? $item->id) }}" style="display: flex; flex-direction: row; gap: 1.5rem; background: var(--paper-soft); border: 1px solid var(--line); text-decoration: none; color: var(--ink); transition: border-color 180ms, box-shadow 240ms; overflow: hidden; border-radius: 0.5rem;" onmouseover="this.style.borderColor='var(--gold)'; this.style.boxShadow='0 8px 30px oklch(18% 0.03 76 / 0.08)'" onmouseout="this.style.borderColor='var(--line)'; this.style.boxShadow='none'">
                        <!-- Image -->
                        <div style="width: 280px; min-width: 280px; aspect-ratio: 16/10; overflow: hidden; flex-shrink: 0; position: relative;">
                            @if($item->image)
                            <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}" style="width: 100%; height: 100%; object-fit: cover; transition: transform 520ms;">
                            @else
                            <img src="{{ asset('assets/img/template/banner-02.webp') }}" alt="{{ $item->title }}" style="width: 100%; height: 100%; object-fit: cover; transition: transform 520ms;">
                            @endif
                            <!-- Category Icon Overlay -->
                            <div style="position: absolute; top: 0.75rem; right: 0.75rem; background: var(--gold); color: var(--night); padding: 0.25rem 0.5rem; border-radius: 0.25rem;">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 0 1 0 2.828l-7 7a2 2 0 0 1-2.828 0l-7-7A1.994 1.994 0 0 1 3 12V7a4 4 0 0 1 4-4z"/></svg>
                            </div>
                        </div>
                        <!-- Content -->
                        <div style="padding: 1.5rem; display: flex; flex-direction: column; justify-content: center; flex: 1;">
                            <span style="display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.2rem 0.6rem; background: var(--gold); color: var(--night); font-family: var(--font-mono); font-size: 0.55rem; font-weight: 700; text-transform: uppercase; margin-bottom: 0.75rem; width: fit-content;">
                                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                                {{ $item->category }}
                            </span>
                            <h3 style="font-family: var(--font-display); font-size: 1.1rem; font-weight: 600; color: var(--ink); margin: 0 0 0.5rem; line-height: 1.3; display: flex; align-items: flex-start; gap: 0.5rem;">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--gold)" stroke-width="1.5" style="flex-shrink: 0; margin-top: 0.1rem;"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14,2 14,8 20,8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10,9 9,9 8,9"/></svg>
                                {{ $item->title }}
                            </h3>
                            <p style="font-size: 0.9rem; color: var(--ink-soft); margin: 0 0 1rem; line-height: 1.5; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">{{ $item->excerpt }}</p>
                            <div style="display: flex; align-items: center; justify-content: space-between; margin-top: auto;">
                                <span style="display: flex; align-items: center; gap: 0.4rem; font-family: var(--font-mono); font-size: 0.7rem; color: var(--ink-soft);">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                    {{ $item->publish_date ? \Carbon\Carbon::parse($item->publish_date)->format('d M Y') : '' }}
                                </span>
                                <span style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; background: var(--gold); color: var(--night); font-family: var(--font-mono); font-size: 0.65rem; font-weight: 700; text-transform: uppercase;">
                                    Baca
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h12m-5-5 5 5-5 5"/></svg>
                                </span>
                            </div>
                        </div>
                    </a>
                </article>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($news->hasPages())
            <div style="margin-top: 3rem; display: flex; justify-content: center;">
                <div class="neo-tabs">
                    @if($news->onFirstPage())
                        <span class="neo-tab" style="opacity: 0.5; cursor: not-allowed;">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 19l-7-7 7-7"/></svg>
                            Prev
                        </span>
                    @else
                        <a href="{{ $news->previousPageUrl() }}" class="neo-tab">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 19l-7-7 7-7"/></svg>
                            Prev
                        </a>
                    @endif

                    @foreach($news->getUrlRange(1, $news->lastPage()) as $page => $url)
                        @if($page == $news->currentPage())
                            <span class="neo-tab is-active">{{ $page }}</span>
                        @elseif($page <= 3 || $page > $news->lastPage() - 2 || abs($page - $news->currentPage()) <= 1)
                            <a href="{{ $url }}" class="neo-tab">{{ $page }}</a>
                        @elseif($page == 4 || $page == $news->lastPage() - 3)
                            <span class="neo-tab" style="cursor: default;">...</span>
                        @endif
                    @endforeach

                    @if($news->hasMorePages())
                        <a href="{{ $news->nextPageUrl() }}" class="neo-tab">
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
            <div class="neo-empty" style="padding: 4rem 1rem;">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" style="opacity: 0.3;">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    <polyline points="14,2 14,8 20,8"/>
                    <line x1="16" y1="13" x2="8" y2="13"/>
                    <line x1="16" y1="17" x2="8" y2="17"/>
                    <polyline points="10,9 9,9 8,9"/>
                </svg>
                <p class="neo-empty-title">Belum Ada Berita</p>
                <p class="neo-empty-text">Tidak ada berita yang ditemukan</p>
                <a href="{{ route('home') }}" class="neo-btn" style="margin-top: 1.5rem;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
                    Kembali ke Beranda
                </a>
            </div>
            @endif
        </section>

        <!-- Footer -->
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
                <a href="{{ route('news.index') }}">Berita</a>
            </nav>
            <div class="footer-copyright"><span>&copy; {{ date("Y") }} SILATAR - Kementerian Agama Tanah Datar</span></div>
        </footer>
    </main>
</x-layouts.app>
