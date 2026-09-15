<x-layouts.ppid-layout title="{{ $page->title }}">
    <x-ppid.nav />

    <main class="ppid-content">
        <div class="ppid-page">
            <div class="ppid-page-breadcrumb">
                <a href="{{ route('ppid') }}">PPID</a>
                <span>/</span>
                <span>{{ $page->title }}</span>
            </div>
            <div class="ppid-page-header" data-reveal>
                <h1 class="ppid-page-title">{{ $page->title }}</h1>
                <p class="ppid-page-subtitle">{{ $page->subtitle ?? 'Peraturan Perundang-undangan Keterbukaan Informasi Publik' }}</p>
            </div>

            {{-- Daftar Regulasi Section --}}
            @if(isset($sectionsByType['daftar_regulasi']))
                @php $regulasi = $sectionsByType['daftar_regulasi']->first(); @endphp
                @php $cards = json_decode($regulasi->metadata)->cards ?? []; @endphp
                <section class="ppid-section" data-reveal>
                    <h2 class="ppid-section-title">{{ $regulasi->title ?? 'Peraturan Perundang-undangan' }}</h2>
                    <div class="ppid-grid" style="grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));">
                        @foreach($cards as $card)
                            <div class="ppid-card">
                                <div class="ppid-card-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                        <polyline points="14 2 14 8 20 8"/>
                                    </svg>
                                </div>
                                <h3 class="ppid-card-title">{{ $card->title }}</h3>
                                <p class="ppid-card-text">{{ $card->description }}</p>
                                @if(isset($card->link) && $card->link !== '#')
                                    <a href="{{ $card->link }}" class="ppid-btn ppid-btn-secondary" style="margin-top: 1rem; font-size: 0.65rem;" target="_blank">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                            <polyline points="7 10 12 15 17 10"/>
                                            <line x1="12" y1="15" x2="12" y2="3"/>
                                        </svg>
                                        Unduh
                                    </a>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif
        </div>
        @include('ppid.partials.footer')
    </main>
</x-layouts.ppid-layout>
