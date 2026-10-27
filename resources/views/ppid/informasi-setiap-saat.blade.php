<x-layouts.ppid-layout title="{{ $page->title }}">
    <x-ppid.nav />

    <main class="ppid-content">
        <div class="ppid-page">
            <div class="ppid-page-breadcrumb">
                <a href="{{ route('ppid') }}">PPID</a>
                <span>/</span>
                <span>Daftar Informasi</span>
                <span>/</span>
                <span>Setiap Saat</span>
            </div>
            <div class="ppid-page-header" data-reveal>
                <h1 class="ppid-page-title">{{ $page->title }}</h1>
                <p class="ppid-page-subtitle">{{ $page->subtitle ?? 'Informasi yang dapat diakses kapan saja' }}</p>
            </div>

            {{-- Daftar Informasi Section --}}
            @if(isset($sectionsByType['daftar_informasi']))
                @php $informasi = $sectionsByType['daftar_informasi']->first(); @endphp
                <section class="ppid-section" data-reveal>
                    <h2 class="ppid-section-title">{{ $informasi->title ?? 'Daftar Informasi' }}</h2>
                    @include('ppid.partials.section-body', ['section' => $informasi])
                </section>
            @endif
        </div>
        @include('ppid.partials.footer')
    </main>
</x-layouts.ppid-layout>
