<x-layouts.ppid-layout title="{{ $page->title }}">
    <x-ppid.nav />

    <main class="ppid-content">
        <div class="ppid-page">
            <div class="ppid-page-breadcrumb">
                <a href="{{ route('ppid') }}">PPID</a>
                <span>/</span>
                <span>Daftar Informasi</span>
                <span>/</span>
                <span>Berkala</span>
            </div>
            <div class="ppid-page-header" data-reveal>
                <h1 class="ppid-page-title">{{ $page->title }}</h1>
                <p class="ppid-page-subtitle">{{ $page->subtitle ?? 'Informasi yang wajib diumumkan secara berkala' }}</p>
            </div>

            <section class="ppid-section" data-reveal>
                <div class="ppid-section-content">
                    <p>Informasi berkala adalah informasi yang wajib disediakan dan diumumkan secara berkala sekurang-kurangnya setiap 6 bulan sekali.</p>
                </div>
            </section>

            {{-- Daftar Informasi Section --}}
            @if(isset($sectionsByType['daftar_informasi']))
                @php $informasi = $sectionsByType['daftar_informasi']->first(); @endphp
                @php $cards = json_decode($informasi->metadata)->cards ?? []; @endphp
                <section class="ppid-section" data-reveal>
                    <h2 class="ppid-section-title">{{ $informasi->title ?? 'Daftar Informasi' }}</h2>
                    <div class="ppid-grid">
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
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif
        </div>
        @include('ppid.partials.footer')
    </main>
</x-layouts.ppid-layout>
