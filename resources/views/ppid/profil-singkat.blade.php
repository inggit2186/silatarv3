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
                        @include('ppid.partials.section-body', ['section' => $profil])
                    </div>
                </section>
            @endif

            {{-- Tugas Section --}}
            @if(isset($sectionsByType['tugas']))
                @php $tugas = $sectionsByType['tugas']->first(); @endphp
                <section class="ppid-section" data-reveal>
                    <div class="ppid-info-box">
                        <h3 class="ppid-info-box-title">{{ $tugas->title ?? 'Tugas Utama PPID' }}</h3>
                    </div>
                    @include('ppid.partials.section-body', ['section' => $tugas])
                </section>
            @endif

            {{-- Fungsi Section --}}
            @if(isset($sectionsByType['fungsi']))
                @php $fungsi = $sectionsByType['fungsi']->first(); @endphp
                <section class="ppid-section" data-reveal>
                    <h3 class="ppid-section-title" style="font-size: 1.1rem;">{{ $fungsi->title ?? 'Fungsi PPID' }}</h3>
                    @include('ppid.partials.section-body', ['section' => $fungsi])
                </section>
            @endif
        </div>
        @include('ppid.partials.footer')
    </main>
</x-layouts.ppid-layout>
