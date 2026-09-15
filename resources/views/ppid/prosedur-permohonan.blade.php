<x-layouts.ppid-layout title="{{ $page->title }}">
    <x-ppid.nav />

    <main class="ppid-content">
        <div class="ppid-page">
            <div class="ppid-page-breadcrumb">
                <a href="{{ route('ppid') }}">PPID</a>
                <span>/</span>
                <span>Layanan</span>
                <span>/</span>
                <span>Prosedur</span>
            </div>
            <div class="ppid-page-header" data-reveal>
                <h1 class="ppid-page-title">{{ $page->title }}</h1>
                <p class="ppid-page-subtitle">{{ $page->subtitle ?? 'Prosedur mengajukan permohonan informasi publik' }}</p>
            </div>

            {{-- Timeline Section --}}
            @if(isset($sectionsByType['timeline']))
                @php $timeline = $sectionsByType['timeline']->first(); @endphp
                @php $steps = json_decode($timeline->metadata)->steps ?? []; @endphp
                <section class="ppid-section" data-reveal>
                    <h2 class="ppid-section-title">{{ $timeline->title ?? 'Alur Permohonan' }}</h2>
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

            {{-- Waktu Penyelesaian Section --}}
            @if(isset($sectionsByType['waktu_penyelesaian']))
                @php $waktu = $sectionsByType['waktu_penyelesaian']->first(); @endphp
                @php $cards = json_decode($waktu->metadata)->cards ?? []; @endphp
                <section class="ppid-section" data-reveal>
                    <h2 class="ppid-section-title">{{ $waktu->title ?? 'Jangka Waktu' }}</h2>
                    <div class="ppid-grid">
                        @foreach($cards as $card)
                            <div class="ppid-card" style="text-align: center;">
                                <h3 class="ppid-card-title">{{ $card->title }}</h3>
                                <p style="font-family: var(--font-display); font-size: 2rem; font-weight: 600; color: var(--ppid-primary);">
                                    {{ $card->description }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif
        </div>
        @include('ppid.partials.footer')
    </main>
</x-layouts.ppid-layout>
