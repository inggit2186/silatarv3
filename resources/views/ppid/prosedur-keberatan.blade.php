<x-layouts.ppid-layout title="{{ $page->title }}">
    <x-ppid.nav />

    <main class="ppid-content">
        <div class="ppid-page">
            <div class="ppid-page-breadcrumb">
                <a href="{{ route('ppid') }}">PPID</a>
                <span>/</span>
                <span>Layanan</span>
                <span>/</span>
                <span>Keberatan</span>
            </div>
            <div class="ppid-page-header" data-reveal>
                <h1 class="ppid-page-title">{{ $page->title }}</h1>
                <p class="ppid-page-subtitle">{{ $page->subtitle ?? 'Prosedur mengajukan keberatan' }}</p>
            </div>

            {{-- Timeline Section --}}
            @if(isset($sectionsByType['timeline']))
                @php $timeline = $sectionsByType['timeline']->first(); @endphp
                <section class="ppid-section" data-reveal>
                    <h2 class="ppid-section-title">{{ $timeline->title ?? 'Alur Pengajuan' }}</h2>
                    @include('ppid.partials.section-body', ['section' => $timeline])
                </section>
            @endif

            {{-- Alasan Keberatan Section --}}
            @if(isset($sectionsByType['alasan_keberatan']))
                @php $alasan = $sectionsByType['alasan_keberatan']->first(); @endphp
                <section class="ppid-section" data-reveal>
                    <h2 class="ppid-section-title">{{ $alasan->title ?? 'Alasan Keberatan' }}</h2>
                    @include('ppid.partials.section-body', ['section' => $alasan])
                </section>
            @endif
        </div>
        @include('ppid.partials.footer')
    </main>
</x-layouts.ppid-layout>
