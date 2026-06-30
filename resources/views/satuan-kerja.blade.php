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
            <div class="neo-card">
                <div class="neo-tabs">
                    @foreach ($sections as $section)
                        <button
                            type="button"
                            @click="setTab('{{ $section['key'] }}')"
                            class="neo-tab"
                            :class="active === '{{ $section['key'] }}' ? 'is-active' : ''"
                        >
                            {{ $section['label'] }}
                        </button>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- Content Section -->
        <section class="page-content">
            @foreach ($sections as $section)
                <section id="{{ $section['key'] }}" x-show="active === '{{ $section['key'] }}'" x-cloak>
                    <div class="neo-grid neo-grid-3">
                        @forelse ($section['cards'] as $card)
                            <a href="{{ $card['href'] ?? '#' }}" class="neo-service-card" style="text-decoration: none;">
                                <div class="neo-service-cover">
                                    @if(!empty($card['cover_path']))
                                        <img src="{{ $card['cover_path'] }}" alt="{{ $card['title'] }}" style="width: 100%; height: 100%; object-fit: cover;">
                                    @endif
                                    <div class="neo-service-cover-overlay"></div>
                                    <span class="neo-service-tag" style="{{ $covers[$section['key']]['chip'] ?? 'background: var(--gold);' }}">{{ $section['label'] }}</span>
                                </div>
                                <div class="neo-service-body">
                                    <h3 class="neo-service-title">{{ $card['title'] }}</h3>
                                    @if(!empty($card['subtitle']))
                                        <p class="neo-service-desc">{{ $card['subtitle'] }}</p>
                                    @endif
                                    @if(!empty($card['extra_value']))
                                        <div class="neo-service-meta">
                                            <div class="neo-service-meta-item">
                                                <span class="neo-service-meta-label">Info</span>
                                                <span class="neo-service-meta-value">{{ $card['extra_value'] }}</span>
                                            </div>
                                        </div>
                                    @endif
                                </div>
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
                        <div style="margin-top: 2rem; display: flex; justify-content: center;">
                            {{ $section['cards']->links() }}
                        </div>
                    @endif
                </section>
            @endforeach
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
