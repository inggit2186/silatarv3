<x-layouts.ppid-layout title="{{ $page->title }}">
    <!-- Header -->
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
                <p class="ppid-page-subtitle">{{ $page->subtitle ?? 'Pejabat Pengelola Informasi dan Dokumentasi' }}</p>
            </div>

            {{-- Profil Section --}}
            @if(isset($sectionsByType['profil']))
                @php $profil = $sectionsByType['profil']->first(); @endphp
                <section class="ppid-section" data-reveal>
                    <div class="ppid-section-content">
                        <h2 class="ppid-section-title">{{ $profil->title ?? 'Tentang PPID' }}</h2>
                        {!! $profil->content !!}
                    </div>
                </section>
            @endif

            {{-- Tugas Section --}}
            @if(isset($sectionsByType['tugas']))
                @php $tugas = $sectionsByType['tugas']->first(); @endphp
                @php $items = json_decode($tugas->metadata)->items ?? []; @endphp
                <section class="ppid-section" data-reveal>
                    <div class="ppid-info-box">
                        <h3 class="ppid-info-box-title">{{ $tugas->title ?? 'Tugas Utama PPID' }}</h3>
                        <p class="ppid-info-box-text">{{ implode(' ', array_slice($items, 0, 1)) }}</p>
                    </div>
                </section>
            @endif

            {{-- Fungsi Section --}}
            @if(isset($sectionsByType['fungsi']))
                @php $fungsi = $sectionsByType['fungsi']->first(); @endphp
                @php $items = json_decode($fungsi->metadata)->items ?? []; @endphp
                <section class="ppid-section" data-reveal>
                    <h3 class="ppid-section-title" style="font-size: 1.1rem;">{{ $fungsi->title ?? 'Fungsi PPID' }}</h3>
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
