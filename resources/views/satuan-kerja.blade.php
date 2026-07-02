<x-layouts.app title="Satuan Kerja - SILATAR">
    @php
        $covers = [
            'kantor' => ['chip' => 'background: var(--gold);', 'bg' => ''],
            'kua' => ['chip' => 'background: #c026d3;', 'bg' => ''],
            'min' => ['chip' => 'background: #059669;', 'bg' => ''],
            'mtsn' => ['chip' => 'background: #d97706;', 'bg' => ''],
            'man' => ['chip' => 'background: #7c3aed;', 'bg' => ''],
            'swasta-lainnya' => ['chip' => 'background: #ea580c;', 'bg' => ''],
            'pemerintah-daerah' => ['chip' => 'background: var(--ink-soft);', 'bg' => ''],
        ];
    @endphp

    <main class="neo-mirai"
        x-data="{
            active: '{{ request('tab', 'kantor') }}',
            setTab(key) {
                this.active = key;
                const url = new URL(window.location);
                url.searchParams.set('tab', key);
                history.replaceState({}, '', url);
            }
        }"
    >
        <x-layouts.site-header />

        <!-- Hero Section -->
        <section class="hero-page" style="background-image: url('/assets/img/template/satker-bg.webp'); background-size: cover; background-position: center top; padding: 120px 2rem 4rem; min-height: 350px;">
            <div style="max-width: 36rem; text-align: center;">
                <p style="color: var(--gold); font-family: var(--font-mono); font-size: 0.65rem; text-transform: uppercase; margin: 0 0 0.5rem; display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 21h18M9 8h1m-1 4h1m4-4h1m-1 4h1M5 21V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16"/></svg>
                    Direktori satuan kerja
                </p>
                <h1 style="font-family: var(--font-display); font-size: clamp(1.8rem, 4vw, 3rem); font-weight: 400; color: var(--ink); margin: 0 0 1rem; display: flex; align-items: center; justify-content: center; gap: 0.75rem;">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="var(--gold)" stroke-width="1.5"><path d="M3 21h18M9 8h1m-1 4h1m4-4h1m-1 4h1M5 21V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16"/><path d="M9 18h6"/></svg>
                    UNIT KERJA
                </h1>
                <p style="color: var(--ink-soft); font-size: 1rem; max-width: 28rem; margin: 0 auto;">Kantor Kementerian Agama Kabupaten Tanah Datar terdiri dari Kantor Agama, KUA, Madrasah, dan satuan pendidikan lainnya yang tersebar di seluruh wilayah Kabupaten Tanah Datar.</p>
                <div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 1rem; margin-top: 1.5rem;">
                    <a href="{{ url('/') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.7rem 1.25rem; background: transparent; color: var(--ink); font-family: var(--font-mono); font-size: 0.7rem; font-weight: 600; text-transform: uppercase; text-decoration: none; border: 1px solid var(--line);">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
                        Beranda
                    </a>
                    <a href="{{ route('pelayanan') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.7rem 1.25rem; background: var(--gold); color: var(--night); font-family: var(--font-mono); font-size: 0.7rem; font-weight: 700; text-transform: uppercase; text-decoration: none;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Ajukan Layanan
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h12m-5-5 5 5-5 5"/></svg>
                    </a>
                </div>
            </div>
        </section>

        <!-- Section Divider -->
        <div class="section-divider wave-rounded"></div>

        <!-- Tabs Section -->
        <section class="page-content">
            <div class="content-centered">
                <div class="neo-card">
                    <div class="neo-tabs">
                        @foreach ($sections as $section)
                            @php
                                $tabIcons = [
                                    'kantor' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21V5a2 2 0 00-2-2H3a2 2 0 00-2 2v16m14 0H5m14 0h2m-2 0h-2M5 21h2m-2 0H3m14 0h2m-2 0h-2M7 7h10M7 11h10M7 15h6"/></svg>',
                                    'kua' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 21v-4M12 13V7M7 21V13a5 5 0 0110 0v8"/><path d="M5 11h14"/></svg>',
                                    'min' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 14l9-5-9-5-9 5 9 5z"/><path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/><path d="M12 14l9-5-9-5-9 5 9 5zm0 0v6"/></svg>',
                                    'mtsn' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 14l9-5-9-5-9 5 9 5zm0 0v6"/><path d="M3.05 11a9 9 0 011.87-5.39A9.03 9.03 0 0112 2a9.03 9.03 0 017.08 3.61A9.02 9.02 0 0120.95 11H3.05z"/></svg>',
                                    'man' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 14l9-5-9-5-9 5 9 5z"/><path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>',
                                    'swasta-lainnya' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 21h18M9 8h1m-1 4h1m4-4h1m-1 4h1M5 21V5a2 2 0 012-2h10a2 2 0 012 2v16"/></svg>',
                                    'pemerintah-daerah' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 21h18M9 8h1m-1 4h1m4-4h1m-1 4h1M5 21V5a2 2 0 012-2h10a2 2 0 012 2v16M12 12v.01"/></svg>',
                                ];
                            @endphp
                            <button
                                type="button"
                                @click="setTab('{{ $section['key'] }}')"
                                class="neo-tab"
                                :class="active === '{{ $section['key'] }}' ? 'is-active' : ''"
                            >
                                {!! $tabIcons[$section['key']] ?? '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg>' !!}
                                <span>{{ $section['label'] }}</span>
                                <span class="neo-tab-count">({{ $section['cards']->total() }})</span>
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        <!-- Content Section -->
        <section class="page-content">
            <div class="content-centered">
                @foreach ($sections as $section)
                    <section id="{{ $section['key'] }}" x-show="active === '{{ $section['key'] }}'" x-cloak>
                        <div class="neo-grid neo-grid-unit-lg">
                        @forelse ($section['cards'] as $card)
                            <a href="{{ $card['href'] ?? '#' }}" class="neo-unit-card" style="text-decoration: none;">
                                <div class="neo-unit-card-visual">
                                    @if(!empty($card['cover_path']))
                                        <img src="{{ $card['cover_path'] }}" alt="{{ $card['title'] }}" class="neo-unit-card-img">
                                    @else
                                        <div class="neo-unit-card-placeholder">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z"/>
                                            </svg>
                                        </div>
                                    @endif
                                    <div class="neo-unit-card-overlay"></div>
                                    <div class="neo-unit-card-header">
                                        <span class="neo-unit-card-badge" style="{{ $covers[$section['key']]['chip'] ?? 'background: var(--gold);' }}">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="12" height="12"><path d="M19 21V5a2 2 0 00-2-2H3a2 2 0 00-2 2v16m14 0H5m14 0h2m-2 0h-2M5 21h2m-2 0H3m14 0h2m-2 0h-2M7 7h10M7 11h10M7 15h6"/></svg>
                                            {{ $section['label'] }}
                                        </span>
                                    </div>
                                    <div class="neo-unit-card-footer">
                                        <h3 class="neo-unit-card-title">{{ $card['title'] }}</h3>
                                        @if(!empty($card['subtitle']))
                                            <p class="neo-unit-card-leader" style="color: oklch(90% 0.01 76); font-size: 0.8rem;">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14"><path d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                                                {{ $card['subtitle'] }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                                @if(!empty($card['head_value']))
                                    <div class="neo-unit-card-leader-section">
                                        <div class="neo-unit-card-leader-photo">
                                            @if(!empty($card['head_photo']))
                                                <img src="{{ $card['head_photo'] }}" alt="{{ $card['head_value'] }}" class="neo-unit-card-leader-img">
                                            @else
                                                <div class="neo-unit-card-leader-initials">{{ $card['head_initials'] ?? '' }}</div>
                                            @endif
                                        </div>
                                        <div class="neo-unit-card-leader-info">
                                            <span class="neo-unit-card-leader-jabatan">{{ $card['head_label'] }}</span>
                                            <span class="neo-unit-card-leader-name">{{ $card['head_value'] }}</span>
                                        </div>
                                    </div>
                                @endif
                                @if(!empty($card['extra_value']))
                                    <div class="neo-unit-card-stats">
                                        <div class="neo-unit-card-stat">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                                            </svg>
                                            <div>
                                                <span class="neo-unit-card-stat-value">{{ $card['extra_value'] }}</span>
                                                <span class="neo-unit-card-stat-label">Pegawai Aktif</span>
                                            </div>
                                        </div>
                                        <div class="neo-unit-card-action">
                                            <span>Lihat</span>
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M4.5 12h15m0 0l-6.75-6.75M19.5 12l-6.75 6.75"/></svg>
                                        </div>
                                    </div>
                                @endif
                            </a>
                        @empty
                            <div class="neo-empty" style="grid-column: 1 / -1;">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H3a2 2 0 00-2 2v16m14 0H5m14 0h2m-2 0h-2M5 21h2m-2 0H3m14 0h2m-2 0h-2M7 7h10M7 11h10M7 15h6" />
                                </svg>
                                <p class="neo-empty-title">Belum ada data</p>
                                <p class="neo-empty-text">Belum ada data unit kerja untuk kategori ini.</p>
                            </div>
                        @endforelse
                    </div>

                    @if ($section['cards']->hasPages())
                        <x-ui.neo-pagination :paginator="$section['cards']" />
                    @endif
                </section>
            @endforeach
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
    </main>
</x-layouts.app>
