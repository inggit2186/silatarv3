<x-layouts.app title="{{ $news->title }}">
    @php
        $siteName = 'Kankemenag Tanah Datar';
        $ogDescription = $news->excerpt ?? strip_tags($news->content);
        if (strlen($ogDescription) > 160) {
            $ogDescription = substr($ogDescription, 0, 157) . '...';
        }
        $ogImage = $news->image ? asset('storage/' . $news->image) : asset('favicon.webp');
        $newsUrl = route('news.show', $news->slug);
    @endphp

    @push('extraHead')
        <meta property="og:site_name" content="{{ $siteName }}">
        <meta property="og:title" content="{{ $news->title }} - {{ $siteName }}">
        <meta property="og:image" content="{{ $ogImage }}">
        <meta property="og:url" content="{{ $newsUrl }}">
        <meta property="og:type" content="article">
        <meta property="article:published_time" content="{{ $news->publish_date }}">
        <meta property="article:author" content="{{ $news->writer ?? config('app.name') }}">
        <meta property="article:section" content="{{ $news->category ?? 'Berita' }}">
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="{{ $news->title }} - {{ $siteName }}">
        <meta name="twitter:image" content="{{ $ogImage }}">
    @endpush

    <main class="neo-mirai">
        <x-layouts.site-header />

        <!-- Hero Section -->
        <section class="hero-page" style="background-image: url('/assets/img/template/news-bg.webp'); background-size: cover; background-position: center top; padding: 120px 2rem 4rem; min-height: 350px;">
            <div style="max-width: 48rem; margin: 0 auto;">
                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem; font-size: 0.8rem; color: var(--ink-soft); justify-content: center;">
                    <a href="{{ route('home') }}" style="color: var(--ink-soft); text-decoration: none; display: flex; align-items: center; gap: 0.3rem;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
                        Beranda
                    </a>
                    <span>
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
                    </span>
                    <a href="{{ route('news.index') }}" style="color: var(--ink-soft); text-decoration: none; display: flex; align-items: center; gap: 0.3rem;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V9a2 2 0 012-2h2a2 2 0 012 2v9a2 2 0 01-2 2h-2z"/></svg>
                        Berita
                    </a>
                </div>
                <span style="display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.2rem 0.6rem; background: var(--gold); color: var(--night); font-family: var(--font-mono); font-size: 0.6rem; font-weight: 700; text-transform: uppercase; margin-bottom: 0.5rem;">
                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 0 1 0 2.828l-7 7a2 2 0 0 1-2.828 0l-7-7A1.994 1.994 0 0 1 3 12V7a4 4 0 0 1 4-4z"/></svg>
                    {{ $news->category }}
                </span>
                <h1 style="font-family: var(--font-display); font-size: clamp(1.5rem, 3vw, 2.25rem); font-weight: 600; color: var(--ink); margin: 0 0 1rem; display: flex; align-items: center; gap: 0.5rem; justify-content: center;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--gold)" stroke-width="1.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14,2 14,8 20,8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10,9 9,9 8,9"/></svg>
                    Konten Berita
                </h1>
                <p style="color: var(--ink-soft); font-size: 0.9rem; margin: 0 0 1rem; max-width: 32rem; margin-left: auto; margin-right: auto; text-align: center;">
                    {{ $news->title }}
                </p>
                <div style="display: flex; gap: 1rem; font-size: 0.8rem; color: var(--ink-soft); justify-content: center;">
                    <span style="display: flex; align-items: center; gap: 0.3rem;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        {{ number_format($viewCount) }} dilihat
                    </span>
                    <span>•</span>
                    <span style="display: flex; align-items: center; gap: 0.3rem;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                        {{ $news->publish_date ? \Carbon\Carbon::parse($news->publish_date)->format('d F Y') : '' }}
                    </span>
                </div>
            </div>
        </section>

        <!-- Section Divider -->
        <div class="section-divider wave-rounded"></div>

        <!-- Article Content -->
        <article class="page-content">
            <div style="max-width: 48rem; margin: 0 auto;">
                <!-- Breadcrumb -->
                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 2rem; font-size: 0.8rem; color: var(--ink-soft);">
                    <a href="{{ route('home') }}" style="color: var(--ink-soft); text-decoration: none; display: flex; align-items: center; gap: 0.3rem;" onmouseover="this.style.color='var(--gold)'" onmouseout="this.style.color='var(--ink-soft)'">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
                        Beranda
                    </a>
                    <span>
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
                    </span>
                    <a href="{{ route('news.index') }}" style="color: var(--ink-soft); text-decoration: none; display: flex; align-items: center; gap: 0.3rem;" onmouseover="this.style.color='var(--gold)'" onmouseout="this.style.color='var(--ink-soft)'">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V9a2 2 0 012-2h2a2 2 0 012 2v9a2 2 0 01-2 2h-2z"/></svg>
                        Berita
                    </a>
                    <span>
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
                    </span>
                    <span style="color: var(--gold); display: flex; align-items: center; gap: 0.3rem;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 0 1 0 2.828l-7 7a2 2 0 0 1-2.828 0l-7-7A1.994 1.994 0 0 1 3 12V7a4 4 0 0 1 4-4z"/></svg>
                        {{ $news->category }}
                    </span>
                </div>

                <!-- Featured Image -->
                @if($news->image)
                <figure style="margin-bottom: 2rem; border-radius: 0.5rem; overflow: hidden; border: 1px solid var(--line);">
                    <img src="{{ asset('storage/' . $news->image) }}" alt="{{ $news->title }}" style="width: 100%; height: auto; max-height: 30rem; object-fit: cover;">
                </figure>
                @endif

                <!-- Main Content Card -->
                <div class="neo-card">
                    <!-- Meta Info -->
                    <div style="display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 1rem; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 1px solid var(--line);">
                        <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 1rem;">
                            <span class="neo-card-badge">{{ $news->category }}</span>
                            @if($news->publish_date)
                            <span style="font-family: var(--font-mono); font-size: 0.7rem; color: var(--ink-soft);">
                                {{ \Carbon\Carbon::parse($news->publish_date)->format('d F Y, H:i') }} WIB
                            </span>
                            @endif
                        </div>
                        <div style="display: flex; align-items: center; gap: 1.5rem; font-family: var(--font-mono); font-size: 0.7rem; color: var(--ink-soft);">
                            <span>{{ number_format($viewCount) }} dilihat</span>
                            <span>{{ number_format($uniqueViewCount) }} pembaca</span>
                        </div>
                    </div>

                    <!-- Title -->
                    <h1 style="font-family: var(--font-display); font-size: clamp(1.5rem, 3vw, 2rem); font-weight: 600; color: var(--ink); margin: 0 0 1.5rem; line-height: 1.3; text-align: center;">
                        {{ $news->title }}
                    </h1>

                    <!-- Excerpt -->
                    @if($news->excerpt)
                    <div style="padding: 1rem 1.5rem; margin-bottom: 1.5rem; border-left: 3px solid var(--gold); background: var(--paper-soft); border-radius: 0 0.5rem 0.5rem 0;">
                        <p style="font-style: italic; color: var(--ink-soft); font-size: 0.95rem; margin: 0;">"{{ $news->excerpt }}"</p>
                    </div>
                    @endif

                    <!-- Team Info -->
                    @if($news->writer || $news->editor || $news->photographer)
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(12rem, 1fr)); gap: 0.75rem; margin-bottom: 1.5rem;">
                        @if($news->writer)
                        <div style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem; background: var(--paper-soft); border: 1px solid var(--line);">
                            <div style="width: 2rem; height: 2rem; border-radius: 50%; background: var(--gold); display: flex; align-items: center; justify-content: center;">
                                <svg style="width: 1rem; height: 1rem; color: var(--night);" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                            </div>
                            <div>
                                <p style="font-family: var(--font-mono); font-size: 0.55rem; text-transform: uppercase; color: var(--ink-soft); margin: 0;">Penulis</p>
                                <p style="font-size: 0.8rem; font-weight: 600; color: var(--ink); margin: 0.1rem 0 0;">{{ $news->writer }}</p>
                            </div>
                        </div>
                        @endif
                        @if($news->editor)
                        <div style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem; background: var(--paper-soft); border: 1px solid var(--line);">
                            <div style="width: 2rem; height: 2rem; border-radius: 50%; background: oklch(60% 0.2 300); display: flex; align-items: center; justify-content: center;">
                                <svg style="width: 1rem; height: 1rem; color: var(--night);" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </div>
                            <div>
                                <p style="font-family: var(--font-mono); font-size: 0.55rem; text-transform: uppercase; color: var(--ink-soft); margin: 0;">Editor</p>
                                <p style="font-size: 0.8rem; font-weight: 600; color: var(--ink); margin: 0.1rem 0 0;">{{ $news->editor }}</p>
                            </div>
                        </div>
                        @endif
                        @if($news->photographer)
                        <div style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem; background: var(--paper-soft); border: 1px solid var(--line);">
                            <div style="width: 2rem; height: 2rem; border-radius: 50%; background: oklch(65% 0.15 45); display: flex; align-items: center; justify-content: center;">
                                <svg style="width: 1rem; height: 1rem; color: var(--night);" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <div>
                                <p style="font-family: var(--font-mono); font-size: 0.55rem; text-transform: uppercase; color: var(--ink-soft); margin: 0;">Fotografer</p>
                                <p style="font-size: 0.8rem; font-weight: 600; color: var(--ink); margin: 0.1rem 0 0;">{{ $news->photographer }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                    @endif

                    <!-- Divider -->
                    <div class="section-divider geometric" style="margin: 1.5rem 0;"></div>

                    <!-- Content -->
                    <div style="font-size: 0.95rem; line-height: 1.8; color: var(--ink);">
                        {!! $news->content !!}
                    </div>

                    <!-- Share Section -->
                    <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid var(--line);">
                        <div style="display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 1rem;">
                            <div style="display: flex; align-items: center; gap: 0.75rem;">
                                <span style="font-family: var(--font-mono); font-size: 0.65rem; text-transform: uppercase; color: var(--ink-soft);">Bagikan:</span>
                                <div style="display: flex; gap: 0.5rem;">
                                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('news.show', $news->slug)) }}&text={{ urlencode($news->title) }}" target="_blank" rel="noopener" class="neo-btn-secondary" style="width: 2.25rem; height: 2.25rem; padding: 0; display: flex; align-items: center; justify-content: center;" title="Twitter/X">
                                        <svg style="width: 1rem; height: 1rem;" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                                    </a>
                                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('news.show', $news->slug)) }}" target="_blank" rel="noopener" class="neo-btn-secondary" style="width: 2.25rem; height: 2.25rem; padding: 0; display: flex; align-items: center; justify-content: center;" title="Facebook">
                                        <svg style="width: 1rem; height: 1rem;" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                    </a>
                                    <a href="https://wa.me/?text={{ urlencode($news->title . ' ' . route('news.show', $news->slug)) }}" target="_blank" rel="noopener" class="neo-btn-secondary" style="width: 2.25rem; height: 2.25rem; padding: 0; display: flex; align-items: center; justify-content: center;" title="WhatsApp">
                                        <svg style="width: 1rem; height: 1rem;" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                    </a>
                                    <button onclick="copyToClipboard()" class="neo-btn-secondary" style="width: 2.25rem; height: 2.25rem; padding: 0; display: flex; align-items: center; justify-content: center;" title="Salin Link">
                                        <svg style="width: 1rem; height: 1rem;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                    </button>
                                </div>
                            </div>
                            <a href="{{ route('home') }}" class="neo-btn-secondary">
                                ← Berita Lainnya
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </article>

        <!-- Related News -->
        @if($relatedNews->count() > 0)
        <section style="padding: 3rem clamp(1.2rem, 3.5vw, 3rem); border-top: 1px solid var(--line);">
            <div style="max-width: 72rem; margin: 0 auto;">
                <h2 style="font-family: var(--font-display); font-size: 1.1rem; font-weight: 600; margin: 0 0 1.5rem; padding-bottom: 0.75rem; border-bottom: 1px solid var(--line); display: flex; align-items: center; gap: 0.5rem;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--gold)" stroke-width="2"><path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/></svg>
                    Berita Terkait
                </h2>
                <div class="neo-grid neo-grid-3">
                    @foreach($relatedNews as $related)
                    <a href="{{ route('news.show', $related->slug ?? $related->id) }}" class="neo-service-card" style="text-decoration: none;">
                        <div class="neo-service-cover">
                            @if($related->image)
                            <img src="{{ asset('storage/' . $related->image) }}" alt="{{ $related->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                            <img src="{{ asset('assets/img/template/banner-02.webp') }}" alt="{{ $related->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                            @endif
                            <div class="neo-service-cover-overlay"></div>
                            <span class="neo-service-tag" style="display: flex; align-items: center; gap: 0.3rem;">
                                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 0 1 0 2.828l-7 7a2 2 0 0 1-2.828 0l-7-7A1.994 1.994 0 0 1 3 12V7a4 4 0 0 1 4-4z"/></svg>
                                {{ $related->category }}
                            </span>
                        </div>
                        <div class="neo-service-body">
                            <h3 class="neo-service-title" style="display: flex; align-items: flex-start; gap: 0.4rem;">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--gold)" stroke-width="1.5" style="flex-shrink: 0; margin-top: 0.1rem;"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/></svg>
                                {{ $related->title }}
                            </h3>
                            <p class="neo-service-desc">{{ $related->excerpt }}</p>
                            <div class="neo-service-meta">
                                <div class="neo-service-meta-item">
                                    <span class="neo-service-meta-label">Tanggal</span>
                                    <span class="neo-service-meta-value" style="display: flex; align-items: center; gap: 0.3rem;">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                        {{ $related->publish_date ? \Carbon\Carbon::parse($related->publish_date)->format('d M Y') : '' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
        </section>
        @endif

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

    <script>
        function copyToClipboard() {
            navigator.clipboard.writeText(window.location.href).then(() => {
                const toast = document.createElement('div');
                toast.style.cssText = 'position: fixed; bottom: 1rem; right: 1rem; z-index: 100; padding: 0.75rem 1.25rem; background: var(--gold); color: var(--night); border-radius: 0.5rem; font-family: var(--font-mono); font-size: 0.75rem; font-weight: 600; box-shadow: 0 8px 24px oklch(50% 0.15 50 / 0.25);';
                toast.innerHTML = '✓ Link berhasil disalin!';
                document.body.appendChild(toast);
                setTimeout(() => toast.remove(), 3000);
            });
        }
    </script>
</x-layouts.app>
