<x-layouts.ppid-layout title="{{ $page->title }}">
    <!-- Navigation -->
    <x-ppid.nav />

    <!-- Content -->
    <main class="ppid-content">
        <!-- Hero Section -->
        @if(isset($sectionsByType['hero']))
            @php $hero = $sectionsByType['hero']->first(); @endphp
            <section class="ppid-hero">
                <div class="ppid-hero-bg">
                    <img src="{{ asset('assets/img/template/ppid-bg.webp') }}" alt="PPID Background">
                </div>
                <div class="ppid-hero-content" data-reveal>
                    <span class="ppid-hero-badge">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                        </svg>
                        {{ $hero->title ?? 'Pejabat Pengelola Informasi dan Dokumentasi' }}
                    </span>
                    <h1 class="ppid-hero-title">
                        <span>{!! $hero->content ?? 'Portal Informasi Publik' !!}</span>
                    </h1>
                    <p class="ppid-hero-subtitle">
                        {{ $page->subtitle ?? 'Menyediakan akses informasi publik yang transparan, akuntabel, dan mudah diakses oleh masyarakat' }}
                    </p>
                </div>
            </section>
        @endif

        <!-- Main Content -->
        <div class="ppid-page">
            <!-- Stats -->
            @if(isset($sectionsByType['stats']))
                @php $stats = $sectionsByType['stats']->first(); @endphp
                @php $statsData = json_decode($stats->metadata)->stats ?? []; @endphp
                <div class="ppid-stats" data-reveal>
                    @foreach($statsData as $stat)
                        <div class="ppid-stat">
                            <div class="ppid-stat-value">{{ $stat->value }}</div>
                            <div class="ppid-stat-label">{{ $stat->label }}</div>
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- About Section -->
            @if(isset($sectionsByType['tentang_ppid']))
                @php $tentang = $sectionsByType['tentang_ppid']->first(); @endphp
                <section class="ppid-section" data-reveal>
                    <h2 class="ppid-section-title">{{ $tentang->title ?? 'Tentang PPID' }}</h2>
                    <div class="ppid-section-content">
                        {!! $tentang->content !!}
                    </div>
                </section>
            @endif

            <!-- Motto Section -->
            @if(isset($sectionsByType['motto']))
                @php $motto = $sectionsByType['motto']->first(); @endphp
                <section class="ppid-section" data-reveal>
                    <div class="ppid-info-box">
                        <h3 class="ppid-info-box-title">{{ $motto->title ?? 'Motto Kami' }}</h3>
                        <p class="ppid-info-box-text">
                            {!! $motto->content !!}
                        </p>
                    </div>
                </section>
            @endif

            <!-- Layanan Populer -->
            @if(isset($sectionsByType['layanan_populer']))
                @php $layanan = $sectionsByType['layanan_populer']->first(); @endphp
                @php $cards = json_decode($layanan->metadata)->cards ?? []; @endphp
                <section class="ppid-section" data-reveal>
                    <h2 class="ppid-section-title">{{ $layanan->title ?? 'Jelajahi Layanan Kami' }}</h2>
                    <div class="ppid-grid">
                        @foreach($cards as $card)
                            <a href="{{ route('ppid.' . ($card->link ?? '#')) }}" class="ppid-card">
                                <div class="ppid-card-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                        <polyline points="14 2 14 8 20 8"/>
                                    </svg>
                                </div>
                                <h3 class="ppid-card-title">{{ $card->title }}</h3>
                                <p class="ppid-card-text">{{ $card->description }}</p>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endif

            <!-- Alur Prosedur -->
            @if(isset($sectionsByType['prosedur_timeline']))
                @php $timeline = $sectionsByType['prosedur_timeline']->first(); @endphp
                @php $steps = json_decode($timeline->metadata)->steps ?? []; @endphp
                <section class="ppid-section" data-reveal>
                    <h2 class="ppid-section-title">{{ $timeline->title ?? 'Alur Permohonan Informasi' }}</h2>
                    <div class="ppid-timeline">
                        @foreach($steps as $step)
                            <div class="ppid-timeline-item">
                                <span class="ppid-timeline-number">Langkah {{ $step->number }}</span>
                                <h3 class="ppid-timeline-title">{{ $step->title }}</h3>
                                <p class="ppid-timeline-text">{{ $step->description }}</p>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif
        </div>

        @include('ppid.partials.footer')
    </main>
</x-layouts.ppid-layout>
