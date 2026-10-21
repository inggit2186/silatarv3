<x-layouts.app title="Publikasi - SILATAR">
    <!-- PDF.js for PDF Preview -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
    <script>
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
    </script>

    <main class="neo-mirai">
        <!-- Hero Section -->
        <section class="hero-page" style="background-image: url('/assets/img/template/news-bg.webp'); background-size: cover; background-position: center top; padding: 120px 2rem 4rem; min-height: 350px;">
            <div class="hero-page-content" style="max-width: 36rem; margin: 0 auto; text-align: center;">
                <p class="section-label-gold section-label-sm">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/>
                    </svg>
                    <span style="font-family: var(--font-mono); letter-spacing: 0.15em; font-size: 0.7rem;">ARSIP DOKUMEN RESMI</span>
                </p>
                <h1 class="hero-page-title" style="display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 1.25rem;">
                    <div class="relative">
                        <div class="absolute inset-0 w-16 h-16 rounded-2xl blur-xl opacity-40" style="background: var(--gold);"></div>
                        <div class="relative w-16 h-16 rounded-2xl flex items-center justify-center" style="background: linear-gradient(135deg, var(--gold) 0%, var(--sun) 100%); box-shadow: 0 8px 32px rgba(212, 168, 83, 0.4);">
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
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                            <polyline points="9,22 9,12 15,12 15,22"/>
                        </svg>
                        Beranda
                    </a>
                    <a href="{{ route('pelayanan') }}" class="neo-hero-cta neo-hero-cta-primary">
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
                <!-- Search Form -->
                <form method="GET" action="{{ route('publikasi') }}" role="search" style="margin-bottom: 1.5rem;">
                    <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
                        <div class="relative" style="flex: 1; min-width: 280px;">
                            <svg class="absolute left-4 top-1/2 -translate-y-1/2" style="color: var(--ash);" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="8"/>
                                <path d="m21 21-4.35-4.35"/>
                            </svg>
                            <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari judul, deskripsi, atau nama file..." style="width: 100%; padding: 0.875rem 1rem 0.875rem 3rem; background: var(--paper); border: 1px solid var(--line); border-radius: 0.5rem; font-size: 0.95rem; color: var(--ink);">
                        </div>
                        <button type="submit" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.875rem 1.5rem; background: linear-gradient(135deg, var(--gold) 0%, var(--sun) 100%); border: none; border-radius: 0.5rem; color: #1a2c35; font-family: var(--font-mono); font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; cursor: pointer;">
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
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/>
                            <path d="M2 10h20"/>
                        </svg>
                        @else
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
                <!-- Preview Grid: Max 3 columns x 3 rows -->
                <div class="publication-preview-grid" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; margin-bottom: 2rem;">
                    @foreach($publikasi->take(9) as $item)
                    @php
                        $ext = strtolower(pathinfo($item->original_filename, PATHINFO_EXTENSION) ?: 'file');
                        $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                        $isPdf = $ext === 'pdf';
                        $filePath = $item->file_path ?? null;
                        // Generate proper storage URL
                        $fileUrl = $filePath ? Storage::url($filePath) : null;

                        $iconConfig = match($ext) {
                            'pdf' => ['color' => '#dc2626', 'bg' => 'rgba(220, 38, 38, 0.15)', 'icon' => 'pdf', 'label' => 'PDF'],
                            'doc', 'docx' => ['color' => '#2563eb', 'bg' => 'rgba(37, 99, 235, 0.15)', 'icon' => 'doc', 'label' => 'WORD'],
                            'xls', 'xlsx' => ['color' => '#16a34a', 'bg' => 'rgba(22, 163, 74, 0.15)', 'icon' => 'xls', 'label' => 'EXCEL'],
                            'ppt', 'pptx' => ['color' => '#ea580c', 'bg' => 'rgba(234, 88, 12, 0.15)', 'icon' => 'ppt', 'label' => 'PPT'],
                            'zip', 'rar' => ['color' => '#9333ea', 'bg' => 'rgba(147, 51, 234, 0.15)', 'icon' => 'zip', 'label' => 'ZIP'],
                            'jpg', 'jpeg', 'png', 'gif', 'webp' => ['color' => '#0891b2', 'bg' => 'rgba(8, 145, 178, 0.15)', 'icon' => 'img', 'label' => strtoupper($ext)],
                            'txt' => ['color' => '#64748b', 'bg' => 'rgba(100, 116, 139, 0.15)', 'icon' => 'txt', 'label' => 'TXT'],
                            default => ['color' => 'var(--gold)', 'bg' => 'rgba(212, 168, 83, 0.15)', 'icon' => 'file', 'label' => strtoupper($ext)]
                        };
                    @endphp

                    <a href="{{ route('publikasi.download', $item->slug) }}" class="preview-card" style="text-decoration: none; border-radius: 1rem; overflow: hidden; background: white; border: 1px solid var(--line); transition: all 200ms ease; display: block;">
                        <!-- Preview Area -->
                        <div class="preview-area" style="height: 140px; position: relative; overflow: hidden;">
                            @if($isImage && $fileUrl)
                            <!-- Image Preview -->
                            <img src="{{ $fileUrl }}" alt="{{ $item->title }}" style="width: 100%; height: 100%; object-fit: cover;" loading="lazy" onerror="this.style.display='none'; this.parentElement.innerHTML='<div style=\'display:flex;flex-direction:column;align-items:center;justify-content:center;height:100%;gap:0.5rem;color:var(--ash);background:rgba(8,145,178,0.15);\'><svg width=\'40\' height=\'40\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'#0891b2\' stroke-width=\'1.5\'><rect x=\'3\' y=\'3\' width=\'18\' height=\'18\' rx=\'2\'/><circle cx=\'8.5\' cy=\'8.5\' r=\'1.5\'/><path d=\'M21 15l-5-5L5 21\'/></svg><span style=\'font-size:0.75rem;font-family:var(--font-mono);color:#0891b2;\'>GAMBAR</span></div>'">
                            @elseif($isPdf && $fileUrl)
                            <!-- PDF Preview -->
                            <div class="pdf-preview" data-pdf-url="{{ $fileUrl }}" data-item-id="grid-{{ $item->id }}" style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; position: relative;">
                                <canvas id="grid-pdf-{{ $item->id }}" style="width: 100%; height: 100%; object-fit: contain;"></canvas>
                                <div class="pdf-loading" style="position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; background: rgba(255,255,255,0.8);">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2" style="animation: spin 1s linear infinite;">
                                        <circle cx="12" cy="12" r="10" stroke-dasharray="30 60"/>
                                    </svg>
                                </div>
                            </div>
                            @else
                            <!-- File Icon Preview -->
                            <div style="width: 100%; height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 0.5rem; background: {{ $iconConfig['bg'] }};">
                                @if($iconConfig['icon'] === 'pdf')
                                <svg width="48" height="48" viewBox="0 0 24 24" fill="none">
                                    <rect x="3" y="1" width="18" height="22" rx="2" fill="{{ $iconConfig['color'] }}"/>
                                    <text x="12" y="15" font-family="Arial, sans-serif" font-size="6" font-weight="bold" fill="white" text-anchor="middle">PDF</text>
                                </svg>
                                @elseif($iconConfig['icon'] === 'doc')
                                <svg width="48" height="48" viewBox="0 0 24 24" fill="none">
                                    <rect x="3" y="1" width="18" height="22" rx="2" fill="{{ $iconConfig['color'] }}"/>
                                    <text x="12" y="15" font-family="Arial, sans-serif" font-size="7" font-weight="bold" fill="white" text-anchor="middle">W</text>
                                </svg>
                                @elseif($iconConfig['icon'] === 'xls')
                                <svg width="48" height="48" viewBox="0 0 24 24" fill="none">
                                    <rect x="3" y="1" width="18" height="22" rx="2" fill="{{ $iconConfig['color'] }}"/>
                                    <text x="12" y="15" font-family="Arial, sans-serif" font-size="7" font-weight="bold" fill="white" text-anchor="middle">X</text>
                                </svg>
                                @elseif($iconConfig['icon'] === 'ppt')
                                <svg width="48" height="48" viewBox="0 0 24 24" fill="none">
                                    <rect x="3" y="1" width="18" height="22" rx="2" fill="{{ $iconConfig['color'] }}"/>
                                    <text x="12" y="15" font-family="Arial, sans-serif" font-size="7" font-weight="bold" fill="white" text-anchor="middle">P</text>
                                </svg>
                                @elseif($iconConfig['icon'] === 'img')
                                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="{{ $iconConfig['color'] }}" stroke-width="1.5">
                                    <rect x="3" y="3" width="18" height="18" rx="2"/>
                                    <circle cx="8.5" cy="8.5" r="1.5"/>
                                    <path d="M21 15l-5-5L5 21"/>
                                </svg>
                                @else
                                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="{{ $iconConfig['color'] }}" stroke-width="1.5">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                    <polyline points="14,2 14,8 20,8"/>
                                </svg>
                                @endif
                                <span style="font-size: 0.7rem; font-family: var(--font-mono); font-weight: 700; color: {{ $iconConfig['color'] }};">{{ $iconConfig['label'] }}</span>
                            </div>
                            @endif

                            <!-- File Info Bar - More Visible -->
                        <div style="position: absolute; bottom: 0; left: 0; right: 0; padding: 0.75rem; background: linear-gradient(transparent, rgba(0,0,0,0.7)); display: flex; align-items: center; justify-content: space-between;">
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                <span style="display: inline-flex; align-items: center; padding: 0.25rem 0.6rem; background: white; color: {{ $iconConfig['color'] }}; font-size: 0.7rem; font-family: var(--font-mono); font-weight: 700; border-radius: 0.3rem; box-shadow: 0 2px 8px rgba(0,0,0,0.2);">{{ strtoupper($ext) }}</span>
                                @if($item->file_size)
                                <span style="display: inline-flex; align-items: center; gap: 0.25rem; padding: 0.25rem 0.6rem; background: rgba(255,255,255,0.9); color: #333; font-size: 0.7rem; font-family: var(--font-mono); font-weight: 600; border-radius: 0.3rem; box-shadow: 0 2px 8px rgba(0,0,0,0.2);">
                                    @if($item->file_size >= 1048576)
                                    {{ round($item->file_size / 1048576, 1) }} MB
                                    @elseif($item->file_size >= 1024)
                                    {{ round($item->file_size / 1024, 1) }} KB
                                    @else
                                    {{ $item->file_size }} B
                                    @endif
                                </span>
                                @endif
                            </div>
                            <!-- Download Icon -->
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" style="filter: drop-shadow(0 2px 4px rgba(0,0,0,0.3);">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                <polyline points="7,10 12,15 17,10"/>
                                <line x1="12" y1="15" x2="12" y2="3"/>
                            </svg>
                        </div>
                    </div>

                        <!-- Card Body -->
                        <div style="padding: 1rem;">
                            @if($item->category)
                            <span style="display: inline-block; padding: 0.15rem 0.5rem; background: rgba(212, 168, 83, 0.1); color: var(--sun); font-size: 0.6rem; font-weight: 600; text-transform: uppercase; border-radius: 9999px; margin-bottom: 0.5rem;">
                                {{ $item->category }}
                            </span>
                            @endif
                            <h3 style="font-size: 0.9rem; font-weight: 600; color: var(--ink); margin: 0 0 0.25rem; line-height: 1.3; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">{{ $item->title }}</h3>
                            <p style="font-size: 0.75rem; color: var(--ink-soft); margin: 0 0 0.5rem; display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden;">{{ Str::limit($item->description, 50) }}</p>
                            <div style="display: flex; align-items: center; justify-content: space-between; font-size: 0.65rem; color: var(--ash); font-family: var(--font-mono);">
                                <span>{{ $item->published_at ? \Carbon\Carbon::parse($item->published_at)->format('d M Y') : \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}</span>
                                <span style="display: flex; align-items: center; gap: 0.25rem;">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                        <polyline points="7,10 12,15 17,10"/>
                                        <line x1="12" y1="15" x2="12" y2="3"/>
                                    </svg>
                                    {{ number_format($item->download_count) }}
                                </span>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>

                @if($publikasi->count() > 9)
                <!-- Remaining items count -->
                <div style="text-align: center; padding: 1rem; background: rgba(212, 168, 83, 0.05); border-radius: 0.75rem; margin-bottom: 2rem;">
                    <p style="color: var(--ink-soft); font-size: 0.9rem; margin: 0;">
                        Dan {{ $publikasi->count() - 9 }} dokumen lainnya...
                        <a href="#" style="color: var(--gold); text-decoration: none; font-weight: 600;">Lihat semua</a>
                    </p>
                </div>
                @endif

                @if($publikasi->hasPages())
                <div class="neo-pagination-wrapper" style="margin-top: 2rem;">
                    <div class="neo-pagination">
                        @if($publikasi->onFirstPage())
                            <span class="disabled">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg>
                                Prev
                            </span>
                        @else
                            <a href="{{ $publikasi->previousPageUrl() }}">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg>
                                Prev
                            </a>
                        @endif

                        @foreach($publikasi->getUrlRange(1, $publikasi->lastPage()) as $page => $url)
                            @if($page == $publikasi->currentPage())
                                <span class="current">{{ $page }}</span>
                            @elseif($page <= 3 || $page > $publikasi->lastPage() - 2 || abs($page - $publikasi->currentPage()) <= 1)
                                <a href="{{ $url }}">{{ $page }}</a>
                            @elseif($page == 4 || $page == $publikasi->lastPage() - 3)
                                <span class="dots">...</span>
                            @endif
                        @endforeach

                        @if($publikasi->hasMorePages())
                            <a href="{{ $publikasi->nextPageUrl() }}">
                                Next
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
                            </a>
                        @else
                            <span class="disabled">
                                Next
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
                            </span>
                        @endif
                    </div>
                </div>
                @endif

                @else
                <div class="neo-empty" style="padding: 4rem 2rem; text-align: center;">
                    <div style="width: 8rem; height: 8rem; margin: 0 auto 1.5rem; border-radius: 50%; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, rgba(212, 168, 83, 0.08) 0%, rgba(214, 138, 58, 0.04) 100%); position: relative;">
                        <div style="position: absolute; inset: -8px; border: 2px dashed rgba(212, 168, 83, 0.2); border-radius: 50%;"></div>
                        <svg width="4rem" height="4rem" viewBox="0 0 24 24" fill="none" stroke="var(--ash)" stroke-width="1">
                            <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/>
                            <path d="M2 10h20"/>
                            <circle cx="12" cy="14" r="2" fill="rgba(212, 168, 83, 0.3)" stroke="none"/>
                        </svg>
                    </div>
                    <p class="neo-empty-title" style="font-size: 1.35rem; font-weight: 700; color: var(--ink); margin-bottom: 0.5rem;">Belum Ada Publikasi</p>
                    <p class="neo-empty-text" style="color: var(--ink-soft); max-width: 24rem; margin: 0 auto 2rem;">Dokumen publikasi akan tersedia soon. Silakan kembali lagi nanti atau jelajahi layanan kami.</p>
                    <a href="{{ route('home') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.875rem 1.5rem; background: linear-gradient(135deg, var(--gold) 0%, var(--sun) 100%); color: #1a2c35; font-family: var(--font-mono); font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; text-decoration: none; border-radius: 0.5rem;">
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
    .preview-card:hover {
        border-color: var(--gold);
        box-shadow: 0 8px 30px rgba(212, 168, 83, 0.15);
        transform: translateY(-2px);
    }
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    @media (max-width: 1024px) {
        .publication-preview-grid {
            grid-template-columns: repeat(2, 1fr) !important;
        }
    }
    @media (max-width: 640px) {
        .publication-preview-grid {
            grid-template-columns: 1fr !important;
        }
    }
</style>

<script>
    // PDF.js Preview Loader for Grid
    document.addEventListener('DOMContentLoaded', function() {
        const pdfContainers = document.querySelectorAll('[data-pdf-url]');

        pdfContainers.forEach(function(container) {
            const pdfUrl = container.dataset.pdfUrl;
            const itemId = container.dataset.itemId;
            const canvas = document.getElementById('grid-pdf-' + itemId.replace('grid-', ''));
            const loader = container.querySelector('.pdf-loading');

            if (!pdfUrl || !canvas) return;

            pdfjsLib.getDocument(pdfUrl).promise.then(function(pdf) {
                pdf.getPage(1).then(function(page) {
                    const viewport = page.getViewport({ scale: 0.8 });
                    canvas.width = viewport.width;
                    canvas.height = viewport.height;

                    const context = canvas.getContext('2d');
                    page.render({
                        canvasContext: context,
                        viewport: viewport
                    }).promise.then(function() {
                        if (loader) loader.style.display = 'none';
                    }).catch(function() {
                        if (loader) loader.style.display = 'none';
                    });
                });
            }).catch(function(error) {
                console.log('PDF preview error:', error);
                if (loader) loader.style.display = 'none';
            });
        });
    });
</script>
