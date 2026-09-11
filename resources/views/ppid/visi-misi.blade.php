<x-layouts.ppid-layout title="{{ $page->title }}">
    <x-ppid.nav />

    <main class="ppid-content">
        <div class="ppid-page">
            <div class="ppid-page-breadcrumb">
                <a href="{{ route('ppid') }}">PPID</a>
                <span>/</span>
                <span>Profil PPID</span>
                <span>/</span>
                <span>Visi Misi</span>
            </div>

            <div class="ppid-page-header" data-reveal>
                <h1 class="ppid-page-title">{{ $page->title }}</h1>
                <p class="ppid-page-subtitle">{{ $page->subtitle ?? '' }}</p>
            </div>

            {{-- Visi Section --}}
            @if(isset($sectionsByType['visi']))
                @php $visi = $sectionsByType['visi']->first(); @endphp
                <section class="ppid-section" data-reveal>
                    <h2 class="ppid-section-title">{{ $visi->title ?? 'Visi' }}</h2>
                    <div class="ppid-info-box" style="background: linear-gradient(135deg, rgba(10, 100, 150, 0.1), rgba(10, 100, 150, 0.05)), linear-gradient(135deg, oklch(8% 0.15 190 / 0.1), oklch(8% 0.15 190 / 0.05)); border-radius: 1.5rem; padding: 2rem; text-align: center;">
                        <p style="font-family: var(--font-display); font-size: clamp(1.1rem, 2vw, 1.4rem); font-weight: 500; color: var(--ink); line-height: 1.6; margin: 0;">
                            {!! $visi->content !!}
                        </p>
                    </div>
                </section>
            @endif

            {{-- Misi Section --}}
            @if(isset($sectionsByType['misi']))
                @php $misi = $sectionsByType['misi']->first(); @endphp
                <section class="ppid-section" data-reveal>
                    <h2 class="ppid-section-title">{{ $misi->title ?? 'Misi' }}</h2>
                    <div class="ppid-grid" style="grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));">
                        @php $cards = json_decode($misi->metadata)->cards ?? []; @endphp
                        @foreach($cards as $card)
                            <div class="ppid-card">
                                <div class="ppid-card-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                                    </svg>
                                </div>
                                <h3 class="ppid-card-title">{{ $card->title }}</h3>
                                <p class="ppid-card-text">{{ $card->description }}</p>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif
        </div>

        @include('ppid.partials.footer')
    </main>
</x-layouts.ppid-layout>
