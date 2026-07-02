<x-layouts.app :title="$department['name'] . ' - SILATAR'">
    <!-- Hero Section -->
    <section class="hero-page" style="background-image: url('/assets/img/template/satker-bg.webp'); background-size: cover; background-position: center top; padding: 120px 2rem 4rem; min-height: 350px;">
        <div class="hero-page-content" style="max-width: 36rem; text-align: center;">
            <p class="section-label" style="color: var(--gold); font-family: var(--font-mono); font-size: 0.65rem; text-transform: uppercase; margin: 0 0 0.5rem; display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 21h18M9 8h1m-1 4h1m4-4h1m-1 4h1M5 21V5a2 2 0 012-2h10a2 2 0 012 2v16"/></svg>
                Detail Unit Kerja
            </p>
            <h1 class="hero-page-title" style="font-family: var(--font-display); font-size: clamp(1.8rem, 4vw, 3rem); font-weight: 400; color: var(--ink); margin: 0 0 1rem; display: flex; align-items: center; justify-content: center; gap: 0.75rem;">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="var(--gold)" stroke-width="1.5"><path d="M19 21V5a2 2 0 00-2-2H3a2 2 0 00-2 2v16m14 0H5m14 0h2m-2 0h-2M5 21h2m-2 0H3m14 0h2m-2 0h-2M7 7h10M7 11h10M7 15h6"/></svg>
                {{ $department['name'] }}
            </h1>
            <p style="color: var(--ink-soft); font-size: 1rem; max-width: 28rem; margin: 0 auto 1.5rem;">
                {{ $department['description'] ?: 'Detail pegawai pada unit kerja ini.' }}
            </p>
            <div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 0.75rem;">
                <span style="display: inline-flex; align-items: center; padding: 0.5rem 1rem; background: var(--gold); color: var(--night); font-family: var(--font-mono); font-size: 0.65rem; font-weight: 700; text-transform: uppercase; border-radius: 2rem;">
                    Kode {{ $department['code'] }}
                </span>
                <span style="display: inline-flex; align-items: center; padding: 0.5rem 1rem; background: var(--paper-soft); color: var(--ink); font-family: var(--font-mono); font-size: 0.65rem; font-weight: 600; text-transform: uppercase; border: 1px solid var(--line); border-radius: 2rem;">
                    {{ strtoupper($department['category']) }}
                </span>
                <a href="{{ route('satuan-kerja', ['tab' => $department['category']]) }}" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; background: transparent; color: var(--ink); font-family: var(--font-mono); font-size: 0.65rem; font-weight: 600; text-transform: uppercase; text-decoration: none; border: 1px solid var(--line); border-radius: 2rem; transition: all 200ms;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5m0 0l6 6m-6-6l6-6"/></svg>
                    Kembali
                </a>
            </div>
        </div>
    </section>

    <!-- Section Divider -->
    <div class="section-divider wave-rounded"></div>

    <!-- Content Section -->
    <section class="page-content">
        <div class="content-centered">
            @if ($leader)
                <div class="neo-leader-section">
                    <div class="neo-leader-main">
                        <p class="neo-leader-label">{{ $leaderLabel }}</p>
                        <x-ui.neo-person-card :person="$leader" :featured="true" />
                    </div>

                    @if (count($kaurs))
                        <div class="neo-leader-sidebar">
                            <p class="neo-leader-label">Kaur</p>
                            <div class="neo-kaurs-grid">
                                @foreach ($kaurs as $kaur)
                                    <x-ui.neo-person-card :person="$kaur" />
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            <div class="neo-section-header">
                <p class="neo-section-step">Daftar Pegawai</p>
                <h2 class="neo-section-title">Pegawai Unit Kerja</h2>
            </div>

            @if ($people->count())
                <div class="neo-people-grid">
                    @foreach ($people as $person)
                        <x-ui.neo-person-card :person="$person" />
                    @endforeach
                </div>
            @else
                <div class="neo-empty-state">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                    </svg>
                    <p class="neo-empty-title">Belum ada pegawai</p>
                    <p class="neo-empty-text">Belum ada pegawai lain pada unit kerja ini.</p>
                </div>
            @endif

            @if ($people->hasPages())
                <x-ui.neo-pagination :paginator="$people" />
            @endif
        </div>
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
</x-layouts.app>
