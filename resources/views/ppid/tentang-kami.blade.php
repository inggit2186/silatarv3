<x-layouts.ppid-layout title="{{ $page->title }}">
    <x-ppid.nav />

    <main class="ppid-content">
        <div class="ppid-page">
            <div class="ppid-page-breadcrumb">
                <a href="{{ route('ppid') }}">PPID</a>
                <span>/</span>
                <span>{{ $page->title }}</span>
            </div>
            <div class="ppid-page-header" data-reveal>
                <h1 class="ppid-page-title">{{ $page->title }}</h1>
                <p class="ppid-page-subtitle">{{ $page->subtitle ?? 'Profil PPID Kemenag Kabupaten Tanah Datar' }}</p>
            </div>

            {{-- Sambutan Section --}}
            @if(isset($sectionsByType['sambutan']))
                @php $sambutan = $sectionsByType['sambutan']->first(); @endphp
                <section class="ppid-section" data-reveal>
                    <div class="ppid-section-content">
                        <h2 class="ppid-section-title">{{ $sambutan->title ?? 'Selamat Datang' }}</h2>
                        @include('ppid.partials.section-body', ['section' => $sambutan])
                    </div>
                </section>
            @endif

            {{-- Visi Section --}}
            @if(isset($sectionsByType['visi']))
                @php $visi = $sectionsByType['visi']->first(); @endphp
                <section class="ppid-section" data-reveal>
                    <h2 class="ppid-section-title">{{ $visi->title ?? 'Visi Kami' }}</h2>
                    <div class="ppid-info-box">
                        <div class="ppid-info-box-text" style="font-size: 1.1rem;">
                            @include('ppid.partials.section-body', ['section' => $visi])
                        </div>
                    </div>
                </section>
            @endif

            {{-- Kontak Section --}}
            @if(isset($sectionsByType['kontak']))
                @php $kontak = $sectionsByType['kontak']->first(); @endphp
                <section class="ppid-section" data-reveal>
                    <h2 class="ppid-section-title">{{ $kontak->title ?? 'Hubungi Kami' }}</h2>
                    @include('ppid.partials.section-body', ['section' => $kontak])
                </section>
            @endif
        </div>
        @include('ppid.partials.footer')
    </main>
</x-layouts.ppid-layout>
