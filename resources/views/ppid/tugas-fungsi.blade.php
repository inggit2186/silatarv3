<x-layouts.ppid-layout title="{{ $page->title }}">
    <x-ppid.nav />

    <main class="ppid-content">
        <div class="ppid-page">
            <div class="ppid-page-breadcrumb">
                <a href="{{ route('ppid') }}">PPID</a>
                <span>/</span>
                <span>Profil PPID</span>
                <span>/</span>
                <span>{{ $page->title }}</span>
            </div>
            <div class="ppid-page-header" data-reveal>
                <h1 class="ppid-page-title">{{ $page->title }}</h1>
                <p class="ppid-page-subtitle">{{ $page->subtitle ?? 'Struktur dan Tugas PPID' }}</p>
            </div>

            {{-- Atasan PPID Section --}}
            @if(isset($sectionsByType['atasan_ppid']))
                @php $atasan = $sectionsByType['atasan_ppid']->first(); @endphp
                @php $items = json_decode($atasan->metadata)->items ?? []; @endphp
                <section class="ppid-section" data-reveal>
                    <h2 class="ppid-section-title">1. {{ $atasan->title ?? 'Atasan PPID' }}</h2>
                    <div class="ppid-section-content">
                        <p>Atasan PPID adalah pejabat yang secara struktural berada di atas PPID dan memiliki tanggung jawab untuk melakukan pengawasan terhadap pelaksanaan tugas PPID.</p>
                    </div>
                    <div class="ppid-info-box">
                        <h3 class="ppid-info-box-title">{{ $atasan->title ?? 'Tugas Atasan PPID' }}</h3>
                        <ul class="ppid-list">
                            @foreach($items as $item)
                                <li>{{ $item }}</li>
                            @endforeach
                        </ul>
                    </div>
                </section>
            @endif

            {{-- PPID Utama Section --}}
            @if(isset($sectionsByType['ppid_utama']))
                @php $ppid = $sectionsByType['ppid_utama']->first(); @endphp
                @php $items = json_decode($ppid->metadata)->items ?? []; @endphp
                <section class="ppid-section" data-reveal>
                    <h2 class="ppid-section-title">2. {{ $ppid->title ?? 'PPID' }}</h2>
                    <div class="ppid-section-content">
                        <p>PPID adalah pejabat yang bertanggung jawab dalam pengelolaan informasi dan dokumentasi.</p>
                    </div>
                    <div class="ppid-info-box">
                        <h3 class="ppid-info-box-title">{{ $ppid->title ?? 'Tugas PPID' }}</h3>
                        <ul class="ppid-list">
                            @foreach($items as $item)
                                <li>{{ $item }}</li>
                            @endforeach
                        </ul>
                    </div>
                </section>
            @endif

            {{-- PPID Pelaksana Section --}}
            @if(isset($sectionsByType['ppid_pelaksana']))
                @php $pelaksana = $sectionsByType['ppid_pelaksana']->first(); @endphp
                @php $items = json_decode($pelaksana->metadata)->items ?? []; @endphp
                <section class="ppid-section" data-reveal>
                    <h2 class="ppid-section-title">3. {{ $pelaksana->title ?? 'PPID Pelaksana' }}</h2>
                    <div class="ppid-section-content">
                        <p>PPID Pelaksana adalah pejabat yang ditunjuk untuk membantu PPID dalam melaksanakan tugas-tugas pengelolaan informasi di unit kerja masing-masing.</p>
                    </div>
                    <ul class="ppid-list">
                        @foreach($items as $item)
                            <li>{{ $item }}</li>
                        @endforeach
                    </ul>
                </section>
            @endif
        </div>
        @include('ppid.partials.footer')
    </main>
</x-layouts.ppid-layout>
