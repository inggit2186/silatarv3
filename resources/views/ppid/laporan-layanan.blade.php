<x-layouts.ppid-layout title="{{ $page->title }}">
    <x-ppid.nav />

    <main class="ppid-content">
        <div class="ppid-page">
            <div class="ppid-page-breadcrumb">
                <a href="{{ route('ppid') }}">PPID</a>
                <span>/</span>
                <span>Standar Layanan</span>
                <span>/</span>
                <span>Laporan</span>
            </div>
            <div class="ppid-page-header" data-reveal>
                <h1 class="ppid-page-title">{{ $page->title }}</h1>
                <p class="ppid-page-subtitle">{{ $page->subtitle ?? 'Rekapitulasi Pelayanan Informasi' }}</p>
            </div>

            {{-- Statistik Section --}}
            @if(isset($sectionsByType['statistik']))
                @php $statistik = $sectionsByType['statistik']->first(); @endphp
                <section class="ppid-section" data-reveal>
                    <h2 class="ppid-section-title">{{ $statistik->title ?? 'Statistik' }}</h2>
                    @include('ppid.partials.section-body', ['section' => $statistik])
                </section>
            @endif

            {{-- Rekap Bulanan Section --}}
            @if(isset($sectionsByType['rekap_bulanan']))
                @php $rekap = $sectionsByType['rekap_bulanan']->first(); @endphp
                <section class="ppid-section" data-reveal>
                    <h2 class="ppid-section-title">{{ $rekap->title ?? 'Rekap Bulanan' }}</h2>
                    @include('ppid.partials.section-body', ['section' => $rekap])
                </section>
            @endif
        </div>
        @include('ppid.partials.footer')
    </main>
</x-layouts.ppid-layout>
