<x-layouts.ppid-layout title="{{ $page->title }}">
    <x-ppid.nav />

    <main class="ppid-content">
        <div class="ppid-page">
            <div class="ppid-page-breadcrumb">
                <a href="{{ route('ppid') }}">PPID</a>
                <span>/</span>
                <span>Standar Layanan</span>
                <span>/</span>
                <span>{{ $page->title }}</span>
            </div>
            <div class="ppid-page-header" data-reveal>
                <h1 class="ppid-page-title">{{ $page->title }}</h1>
                <p class="ppid-page-subtitle">{{ $page->subtitle ?? 'Waktu dan Hari Layanan Informasi Publik' }}</p>
            </div>

            {{-- Jadwal Table Section --}}
            @if(isset($sectionsByType['jadwal_opsional']))
                @php $jadwal = $sectionsByType['jadwal_opsional']->first(); @endphp
                <section class="ppid-section" data-reveal>
                    <h2 class="ppid-section-title">{{ $jadwal->title ?? 'Jam Operasional' }}</h2>
                    @include('ppid.partials.section-body', ['section' => $jadwal])
                </section>
            @endif

            {{-- Kontak Section --}}
            @if(isset($sectionsByType['kontak']))
                @php $kontak = $sectionsByType['kontak']->first(); @endphp
                <section class="ppid-section" data-reveal>
                    <h2 class="ppid-section-title">{{ $kontak->title ?? 'Kontak Layanan' }}</h2>
                    @include('ppid.partials.section-body', ['section' => $kontak])
                </section>
            @endif
        </div>
        @include('ppid.partials.footer')
    </main>
</x-layouts.ppid-layout>
