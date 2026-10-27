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
                <section class="ppid-section" data-reveal>
                    <h2 class="ppid-section-title">{{ $timeline->title ?? 'Alur Permohonan' }}</h2>
                    @include('ppid.partials.section-body', ['section' => $timeline])
                </section>
            @endif

            {{-- Waktu Penyelesaian Section --}}
            @if(isset($sectionsByType['waktu_penyelesaian']))
                @php $waktu = $sectionsByType['waktu_penyelesaian']->first(); @endphp
                <section class="ppid-section" data-reveal>
                    <h2 class="ppid-section-title">{{ $waktu->title ?? 'Jangka Waktu' }}</h2>
                    @include('ppid.partials.section-body', ['section' => $waktu])
                </section>
            @endif
        </div>
        @include('ppid.partials.footer')
    </main>
</x-layouts.ppid-layout>
