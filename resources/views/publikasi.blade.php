<x-layouts.app title="Publikasi - SILATAR">
    <main class="neo-mirai">
        <!-- Hero Section -->
        <section class="hero-page" style="background-image: url('/assets/img/template/news-bg.webp'); background-size: cover; background-position: center top; padding: 120px 2rem 4rem; min-height: 350px;">
            <div class="hero-page-content" style="max-width: 36rem; margin: 0 auto; text-align: center;">
                <p class="section-label-gold section-label-sm">
                    <!-- Bookmark Icon -->
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/>
                    </svg>
                    <span style="font-family: var(--font-mono); letter-spacing: 0.15em; font-size: 0.7rem;">ARSIP DOKUMEN RESMI</span>
                </p>
                <h1 class="hero-page-title" style="display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 1.25rem;">
                    <!-- Icon Badge with Glow -->
                    <div class="relative">
                        <div class="absolute inset-0 w-16 h-16 rounded-2xl blur-xl opacity-40" style="background: var(--gold);"></div>
                        <div class="relative w-16 h-16 rounded-2xl flex items-center justify-center" style="background: linear-gradient(135deg, var(--gold) 0%, var(--sun) 100%); box-shadow: 0 8px 32px rgba(212, 168, 83, 0.4);">
                            <!-- Archive/Book Icon -->
                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#1a2c35" stroke-width="2">
                                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
                                <path d="M8 7h8"/>
                                <path d="M8 11h6"/>
                                <path d="M8 15h4"/>
                            </svg>
                        </div>
                    </div>
                    <div class="text-center">
                        <span style="font-size: 2.5rem; font-weight: 700; letter-spacing: -0.02em; background: linear-gradient(135deg, var(--ink) 0%, var(--ink-soft) 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">Publikasi</span>
                        <p style="font-size: 0.85rem; color: var(--ink-soft); margin-top: 0.25rem; font-family: var(--font-mono);">Koleksi Dokumen Resmi Kemenag Tanah Datar</p>
                    </div>
                </h1>
                <p style="color: var(--ink-soft); font-size: 1rem; max-width: 28rem; margin: 1.5rem auto;">Kumpulan dokumen resmi yang dapat diunduh untuk keperluan informasi atau referensi.</p>
                <div class="hero-page-actions">
                    <a href="{{ url('/') }}" class="neo-hero-cta">
                        <!-- Home Icon -->
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                            <polyline points="9,22 9,12 15,12 15,22"/>
                        </svg>
                        Beranda
                    </a>
                    <a href="{{ route('pelayanan') }}" class="neo-hero-cta neo-hero-cta-primary">
                        <!-- Document Icon -->
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                            <polyline points="14,2 14,8 20,8"/>
                            <line x1="16" y1="13" x2="8" y2="13"/>
                            <line x1="16" y1="17" x2="8" y2="17"/>
                        </svg>
                        Lihat Layanan
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M5 12h14"/>
                            <path d="M12 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>
        </section>

        <div class="section-divider wave-rounded"></div>

        <section class="page-content">
            <div class="content-centered">
                <!-- Enhanced Search Form -->
                <form method="GET" action="{{ route('publikasi') }}" role="search" style="margin-bottom: 1.5rem;">
                    <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
                        <div class="relative" style="flex: 1; min-width: 280px;">
                            <!-- Search Icon -->
                            <svg class="absolute left-4 top-1/2 -translate-y-1/2" style="color: var(--ash);" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="8"/>
                                <path d="m21 21-4.35-4.35"/>
                            </svg>
                            <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari judul, deskripsi, atau nama file..." style="width: 100%; padding: 0.875rem 1rem 0.875rem 3rem; background: var(--paper); border: 1px solid var(--line); border-radius: 0.5rem; font-size: 0.95rem; color: var(--ink); transition: border-color 200ms, box-shadow 200ms;" onfocus="this.style.borderColor='var(--gold)'; this.style.boxShadow='0 0 0 3px rgba(212, 168, 83, 0.15)';" onblur="this.style.borderColor='var(--line)'; this.style.boxShadow='none';">
                        </div>
                        <button type="submit" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.875rem 1.5rem; background: linear-gradient(135deg, var(--gold) 0%, var(--sun) 100%); border: none; border-radius: 0.5rem; color: #1a2c35; font-family: var(--font-mono); font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; cursor: pointer; transition: transform 150ms, box-shadow 150ms; box-shadow: 0 4px 12px rgba(212, 168, 83, 0.3);" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 20px rgba(212, 168, 83, 0.4)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(212, 168, 83, 0.3)';">
                            <!-- Magnify Icon -->
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <circle cx="11" cy="11" r="8"/>
                                <path d="m21 21-4.35-4.35"/>
                            </svg>
                            Cari
                        </button>
                    </div>
                </form>

                @if($categories->count() > 0)
                <div class="neo-publication-filters mb-8">
                    <!-- All Icon -->
                    <a href="{{ route('publikasi') }}" class="neo-tab {{ !$selectedCategory ? 'is-active' : '' }}" style="padding: 0.625rem 1rem; border-radius: 9999px; display: inline-flex; align-items: center; gap: 0.5rem;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="3" width="7" height="7" rx="1"/>
                            <rect x="14" y="3" width="7" height="7" rx="1"/>
                            <rect x="14" y="14" width="7" height="7" rx="1"/>
                            <rect x="3" y="14" width="7" height="7" rx="1"/>
                        </svg>
                        Semua
                        <span style="font-size: 0.7rem; opacity: 0.7;">({{ $publikasi->total() }})</span>
                    </a>
                    @foreach($categories as $category)
                    <a href="{{ route('publikasi', ['category' => $category]) }}" class="neo-tab {{ $selectedCategory === $category ? 'is-active' : '' }}" style="padding: 0.625rem 1rem; border-radius: 9999px; display: inline-flex; align-items: center; gap: 0.5rem;">
                        @if($selectedCategory === $category)
                        <!-- Folder Open Icon -->
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/>
                            <path d="M2 10h20"/>
                        </svg>
                        @else
                        <!-- Folder Icon -->
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/>
                        </svg>
                        @endif
                        {{ $category }}
                    </a>
                    @endforeach
                </div>
                @endif

                @if($publikasi->count() > 0)
                <div class="neo-publication-list">
                    @foreach($publikasi as $item)
                    <article class="neo-publication-card" style="position: relative; overflow: hidden;">
                        <!-- Gradient Top Border on Hover -->
                        <div style="position: absolute; top: 0; left: 0; right: 0; height: 3px; background: linear-gradient(90deg, var(--gold), var(--sun)); transform: scaleX(0); transform-origin: left; transition: transform 300ms ease;" class="gradient-border"></div>

                        <div class="neo-publication-icon">
                            @php
                                $ext = strtolower(pathinfo($item->original_filename, PATHINFO_EXTENSION) ?: 'file');
                                $iconConfig = match($ext) {
                                    'pdf' => ['color' => '#dc2626', 'bg' => 'rgba(220, 38, 38, 0.12)', 'icon' => 'pdf'],
                                    'doc', 'docx' => ['color' => '#2563eb', 'bg' => 'rgba(37, 99, 235, 0.12)', 'icon' => 'doc'],
                                    'xls', 'xlsx' => ['color' => '#16a34a', 'bg' => 'rgba(22, 163, 74, 0.12)', 'icon' => 'xls'],
                                    'ppt', 'pptx' => ['color' => '#ea580c', 'bg' => 'rgba(234, 88, 12, 0.12)', 'icon' => 'ppt'],
                                    'zip', 'rar' => ['color' => '#9333ea', 'bg' => 'rgba(147, 51, 234, 0.12)', 'icon' => 'zip'],
                                    'jpg', 'jpeg', 'png', 'gif', 'webp' => ['color' => '#0891b2', 'bg' => 'rgba(8, 145, 178, 0.12)', 'icon' => 'img'],
                                    'txt' => ['color' => '#64748b', 'bg' => 'rgba(100, 116, 139, 0.12)', 'icon' => 'txt'],
                                    default => ['color' => 'var(--gold)', 'bg' => 'rgba(212, 168, 83, 0.12)', 'icon' => 'file']
                                };
                            @endphp
                            <div style="width: 4rem; height: 4rem; border-radius: 0.75rem; display: flex; align-items: center; justify-content: center; background: {{ $iconConfig['bg'] }}; position: relative;">
                                @if($iconConfig['icon'] === 'pdf')
                                <!-- Adobe PDF Icon -->
                                <svg width="2.5rem" height="2.5rem" viewBox="0 0 24 24" fill="none">
                                    <!-- PDF Background -->
                                    <rect x="3" y="1" width="18" height="22" rx="2" fill="{{ $iconConfig['color'] }}" opacity="0.15"/>
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" fill="{{ $iconConfig['color'] }}"/>
                                    <!-- PDF Text -->
                                    <text x="12" y="16" font-family="Arial, sans-serif" font-size="7" font-weight="bold" fill="white" text-anchor="middle">PDF</text>
                                </svg>
                                @elseif($iconConfig['icon'] === 'doc')
                                <!-- Microsoft Word Icon -->
                                <svg width="2.5rem" height="2.5rem" viewBox="0 0 24 24" fill="none">
                                    <rect x="3" y="1" width="18" height="22" rx="2" fill="{{ $iconConfig['color'] }}" opacity="0.15"/>
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" fill="{{ $iconConfig['color'] }}"/>
                                    <!-- W letter -->
                                    <text x="12" y="16" font-family="Arial, sans-serif" font-size="8" font-weight="bold" fill="white" text-anchor="middle">W</text>
                                </svg>
                                @elseif($iconConfig['icon'] === 'xls')
                                <!-- Microsoft Excel Icon -->
                                <svg width="2.5rem" height="2.5rem" viewBox="0 0 24 24" fill="none">
                                    <rect x="3" y="1" width="18" height="22" rx="2" fill="{{ $iconConfig['color'] }}" opacity="0.15"/>
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" fill="{{ $iconConfig['color'] }}"/>
                                    <!-- X letter -->
                                    <text x="12" y="16" font-family="Arial, sans-serif" font-size="8" font-weight="bold" fill="white" text-anchor="middle">X</text>
                                </svg>
                                @elseif($iconConfig['icon'] === 'ppt')
                                <!-- Microsoft PowerPoint Icon -->
                                <svg width="2.5rem" height="2.5rem" viewBox="0 0 24 24" fill="none">
                                    <rect x="3" y="1" width="18" height="22" rx="2" fill="{{ $iconConfig['color'] }}" opacity="0.15"/>
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" fill="{{ $iconConfig['color'] }}"/>
                                    <!-- P letter -->
                                    <text x="12" y="16" font-family="Arial, sans-serif" font-size="8" font-weight="bold" fill="white" text-anchor="middle">P</text>
                                </svg>
                                @elseif($iconConfig['icon'] === 'zip')
                                <!-- Archive/ZIP Icon -->
                                <svg width="2.5rem" height="2.5rem" viewBox="0 0 24 24" fill="none">
                                    <rect x="3" y="1" width="18" height="22" rx="2" fill="{{ $iconConfig['color'] }}" opacity="0.15"/>
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" fill="{{ $iconConfig['color'] }}"/>
                                    <!-- Zip lines -->
                                    <path d="M9 9v2M9 13v2M15 9v2M15 13v2" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                                </svg>
                                @elseif($iconConfig['icon'] === 'img')
                                <!-- Image File Icon -->
                                <svg width="2.5rem" height="2.5rem" viewBox="0 0 24 24" fill="none">
                                    <rect x="3" y="1" width="18" height="22" rx="2" fill="{{ $iconConfig['color'] }}" opacity="0.15"/>
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" fill="{{ $iconConfig['color'] }}"/>
                                    <!-- Mountain and sun -->
                                    <circle cx="10" cy="10" r="2" fill="white"/>
                                    <path d="M6 17l4-4 3 3 4-5 3 4" stroke="white" stroke-width="1.5" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                @elseif($iconConfig['icon'] === 'txt')
                                <!-- Text File Icon -->
                                <svg width="2.5rem" height="2.5rem" viewBox="0 0 24 24" fill="none">
                                    <rect x="3" y="1" width="18" height="22" rx="2" fill="{{ $iconConfig['color'] }}" opacity="0.15"/>
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" fill="{{ $iconConfig['color'] }}"/>
                                    <!-- Lines -->
                                    <path d="M7 9h10M7 13h8M7 17h6" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                                </svg>
                                @else
                                <!-- Generic File Icon -->
                                <svg width="2.5rem" height="2.5rem" viewBox="0 0 24 24" fill="none">
                                    <rect x="3" y="1" width="18" height="22" rx="2" fill="{{ $iconConfig['color'] }}" opacity="0.15"/>
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" fill="{{ $iconConfig['color'] }}"/>
                                    <!-- Document lines -->
                                    <path d="M7 9h10M7 13h6" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                                </svg>
                                @endif
                            </div>
                        </div>
                        <div class="neo-publication-body">
                            @if($item->category)
                            <span style="display: inline-flex; align-items: center; gap: 0.35rem; padding: 0.25rem 0.625rem; background: rgba(212, 168, 83, 0.1); color: var(--sun); border: 1px solid rgba(212, 168, 83, 0.25); border-radius: 9999px; font-size: 0.7rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.03em;">
                                <!-- Tag Icon -->
                                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/>
                                    <line x1="7" y1="7" x2="7.01" y2="7"/>
                                </svg>
                                {{ $item->category }}
                            </span>
                            @endif
                            <h2 class="neo-publication-title" style="font-size: 1.1rem; font-weight: 600; margin-top: 0.5rem;">{{ $item->title }}</h2>
                            <p class="neo-publication-desc">{{ $item->description }}</p>
                            <div class="neo-publication-meta" style="margin-top: 0.75rem;">
                                <!-- Calendar Icon -->
                                <span class="neo-publication-meta-item">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                        <line x1="16" y1="2" x2="16" y2="6"/>
                                        <line x1="8" y1="2" x2="8" y2="6"/>
                                        <line x1="3" y1="10" x2="21" y2="10"/>
                                    </svg>
                                    {{ $item->published_at ? \Carbon\Carbon::parse($item->published_at)->format('d M Y') : \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}
                                </span>
                                <!-- File Type Badge -->
                                <span style="display: inline-flex; align-items: center; gap: 0.3rem; padding: 0.2rem 0.5rem; background: {{ $iconConfig['bg'] }}; border-radius: 0.25rem; font-size: 0.68rem; font-weight: 700; color: {{ $iconConfig['color'] }}; font-family: var(--font-mono);">
                                    <!-- File Text Icon -->
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                        <polyline points="14,2 14,8 20,8"/>
                                    </svg>
                                    {{ strtoupper($ext) }}
                                </span>
                                @if($item->file_size)
                                <!-- Hard Drive Icon -->
                                <span class="neo-publication-meta-item">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <line x1="22" y1="12" x2="2" y2="12"/>
                                        <path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/>
                                        <line x1="6" y1="16" x2="6.01" y2="16"/>
                                        <line x1="10" y1="16" x2="10.01" y2="16"/>
                                    </svg>
                                    {{ round($item->file_size / 1024, 1) }} KB
                                </span>
                                @endif
                                <!-- Download Icon -->
                                <span class="neo-publication-meta-item">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                        <polyline points="7,10 12,15 17,10"/>
                                        <line x1="12" y1="15" x2="12" y2="3"/>
                                    </svg>
                                    {{ number_format($item->download_count) }} unduhan
                                </span>
                            </div>
                        </div>
                        <a href="{{ route('publikasi.download', $item->slug) }}" class="neo-publication-action" title="Unduh {{ $item->original_filename }}" style="display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 0.5rem; padding: 1rem 1.25rem; min-width: 7rem; background: linear-gradient(135deg, var(--gold) 0%, var(--sun) 100%); color: #1a2c35; font-family: var(--font-mono); font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; text-decoration: none; transition: all 200ms ease;">
                            <!-- Download Icon -->
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                <polyline points="7,10 12,15 17,10"/>
                                <line x1="12" y1="15" x2="12" y2="3"/>
                            </svg>
                            <span>Unduh</span>
                        </a>
                    </article>
                    @endforeach
                </div>

                @if($publikasi->hasPages())
                <div class="neo-pagination-wrapper" style="margin-top: 3rem;">
                    <div style="display: flex; justify-content: center;">
                        <div class="neo-pagination" style="display: inline-flex; align-items: center; gap: 0.375rem; background: white; padding: 0.5rem; border-radius: 1rem; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06); border: 1px solid var(--line);">
                            @if($publikasi->onFirstPage())
                                <span style="display: inline-flex; align-items: center; gap: 0.375rem; padding: 0.5rem 0.875rem; opacity: 0.4; cursor: not-allowed; color: var(--ink-soft); font-family: var(--font-mono); font-size: 0.75rem; font-weight: 600;">
                                    <!-- Chevron Left Icon -->
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg>
                                    Prev
                                </span>
                            @else
                                <a href="{{ $publikasi->previousPageUrl() }}" style="display: inline-flex; align-items: center; gap: 0.375rem; padding: 0.5rem 0.875rem; color: var(--ink-soft); font-family: var(--font-mono); font-size: 0.75rem; font-weight: 600; text-decoration: none; border-radius: 0.5rem; transition: all 150ms ease;" onmouseover="this.style.background='var(--paper-soft)'; this.style.color='var(--ink)';" onmouseout="this.style.background='transparent'; this.style.color='var(--ink-soft)';">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg>
                                    Prev
                                </a>
                            @endif

                            @foreach($publikasi->getUrlRange(1, $publikasi->lastPage()) as $page => $url)
                                @if($page == $publikasi->currentPage())
                                    <span style="display: inline-flex; align-items: center; justify-content: center; min-width: 2.5rem; height: 2.5rem; padding: 0 0.75rem; background: linear-gradient(135deg, var(--gold) 0%, var(--sun) 100%); color: #1a2c35; border-radius: 0.5rem; font-family: var(--font-mono); font-size: 0.8rem; font-weight: 700; box-shadow: 0 2px 8px rgba(212, 168, 83, 0.3);">{{ $page }}</span>
                                @elseif($page <= 3 || $page > $publikasi->lastPage() - 2 || abs($page - $publikasi->currentPage()) <= 1)
                                    <a href="{{ $url }}" style="display: inline-flex; align-items: center; justify-content: center; min-width: 2.5rem; height: 2.5rem; padding: 0 0.75rem; color: var(--ink-soft); font-family: var(--font-mono); font-size: 0.75rem; font-weight: 600; text-decoration: none; border-radius: 0.5rem; transition: all 150ms ease;" onmouseover="this.style.background='var(--paper-soft)'; this.style.color='var(--ink)';" onmouseout="this.style.background='transparent'; this.style.color='var(--ink-soft)';">{{ $page }}</a>
                                @elseif($page == 4 || $page == $publikasi->lastPage() - 3)
                                    <span style="padding: 0 0.5rem; color: var(--ash);">...</span>
                                @endif
                            @endforeach

                            @if($publikasi->hasMorePages())
                                <a href="{{ $publikasi->nextPageUrl() }}" style="display: inline-flex; align-items: center; gap: 0.375rem; padding: 0.5rem 0.875rem; color: var(--ink-soft); font-family: var(--font-mono); font-size: 0.75rem; font-weight: 600; text-decoration: none; border-radius: 0.5rem; transition: all 150ms ease;" onmouseover="this.style.background='var(--paper-soft)'; this.style.color='var(--ink)';" onmouseout="this.style.background='transparent'; this.style.color='var(--ink-soft)';">
                                    Next
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
                                </a>
                            @else
                                <span style="display: inline-flex; align-items: center; gap: 0.375rem; padding: 0.5rem 0.875rem; opacity: 0.4; cursor: not-allowed; color: var(--ink-soft); font-family: var(--font-mono); font-size: 0.75rem; font-weight: 600;">
                                    Next
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                @else
                <!-- Enhanced Empty State -->
                <div class="neo-empty" style="padding: 4rem 2rem; text-align: center;">
                    <div style="width: 8rem; height: 8rem; margin: 0 auto 1.5rem; border-radius: 50%; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, rgba(212, 168, 83, 0.08) 0%, rgba(214, 138, 58, 0.04) 100%); position: relative;">
                        <!-- Decorative Ring -->
                        <div style="position: absolute; inset: -8px; border: 2px dashed rgba(212, 168, 83, 0.2); border-radius: 50%;"></div>
                        <!-- Empty Folder Icon -->
                        <svg width="4rem" height="4rem" viewBox="0 0 24 24" fill="none" stroke="var(--ash)" stroke-width="1">
                            <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/>
                            <path d="M2 10h20"/>
                            <!-- Plus Sign -->
                            <circle cx="12" cy="14" r="2" fill="rgba(212, 168, 83, 0.3)" stroke="none"/>
                        </svg>
                    </div>
                    <p class="neo-empty-title" style="font-size: 1.35rem; font-weight: 700; color: var(--ink); margin-bottom: 0.5rem;">Belum Ada Publikasi</p>
                    <p class="neo-empty-text" style="color: var(--ink-soft); max-width: 24rem; margin: 0 auto 2rem;">Dokumen publikasi akan tersedia soon. Silakan kembali lagi nanti atau jelajahi layanan kami.</p>
                    <a href="{{ route('home') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.875rem 1.5rem; background: linear-gradient(135deg, var(--gold) 0%, var(--sun) 100%); color: #1a2c35; font-family: var(--font-mono); font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; text-decoration: none; border-radius: 0.5rem; box-shadow: 0 4px 12px rgba(212, 168, 83, 0.3); transition: transform 150ms, box-shadow 150ms;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 20px rgba(212, 168, 83, 0.4)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(212, 168, 83, 0.3)';">
                        <!-- Home Icon -->
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                            <polyline points="9,22 9,12 15,12 15,22"/>
                        </svg>
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

<style>
    .neo-publication-card:hover .gradient-border {
        transform: scaleX(1) !important;
    }
    .neo-publication-card:hover {
        border-color: var(--gold);
        box-shadow: 0 8px 30px rgba(212, 168, 83, 0.12);
    }
    .neo-publication-card:hover .neo-publication-title {
        color: var(--gold);
    }
    .neo-publication-card:hover .neo-publication-icon svg,
    .neo-publication-card:hover .neo-publication-icon > div {
        transform: scale(1.05);
    }
    .neo-publication-icon svg,
    .neo-publication-icon > div {
        transition: transform 200ms ease;
    }
    .neo-publication-title {
        transition: color 150ms ease;
    }
</style>
