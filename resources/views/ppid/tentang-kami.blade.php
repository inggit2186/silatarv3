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
                <p class="ppid-page-subtitle">{{ $page->subtitle ?? 'Profil PPID Kemenag Kabupaten Tanah Datar' }}</p>
            </div>

            {{-- Sambutan Section --}}
            @if(isset($sectionsByType['sambutan']))
                @php $sambutan = $sectionsByType['sambutan']->first(); @endphp
                <section class="ppid-section" data-reveal>
                    <div class="ppid-section-content">
                        <h2 class="ppid-section-title">{{ $sambutan->title ?? 'Selamat Datang' }}</h2>
                        {!! $sambutan->content !!}
                    </div>
                </section>
            @endif

            {{-- Visi Section --}}
            @if(isset($sectionsByType['visi']))
                @php $visi = $sectionsByType['visi']->first(); @endphp
                <section class="ppid-section" data-reveal>
                    <h2 class="ppid-section-title">{{ $visi->title ?? 'Visi Kami' }}</h2>
                    <div class="ppid-info-box">
                        <p class="ppid-info-box-text" style="font-size: 1.1rem;">
                            {!! $visi->content !!}
                        </p>
                    </div>
                </section>
            @endif

            {{-- Kontak Section --}}
            @if(isset($sectionsByType['kontak']))
                @php $kontak = $sectionsByType['kontak']->first(); @endphp
                @php $cards = json_decode($kontak->metadata)->cards ?? []; @endphp
                <section class="ppid-section" data-reveal>
                    <h2 class="ppid-section-title">{{ $kontak->title ?? 'Hubungi Kami' }}</h2>
                    <div class="ppid-grid">
                        @foreach($cards as $card)
                            <div class="ppid-card">
                                <div class="ppid-card-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                    </svg>
                                </div>
                                <h3 class="ppid-card-title">{{ $card->title }}</h3>
                                <p class="ppid-card-text">{!! nl2br($card->description) !!}</p>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif
        </div>
        @include('ppid.partials.footer')
    </main>
</x-layouts.ppid-layout>
