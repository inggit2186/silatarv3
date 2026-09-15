<x-layouts.ppid-layout title="{{ $page->title }}">
    <x-ppid.nav />

    <main class="ppid-content">
        <div class="ppid-page">
            <div class="ppid-page-breadcrumb">
                <a href="{{ route('ppid') }}">PPID</a>
                <span>/</span>
                <span>Daftar Informasi</span>
                <span>/</span>
                <span>Serta Merta</span>
            </div>
            <div class="ppid-page-header" data-reveal>
                <h1 class="ppid-page-title">{{ $page->title }}</h1>
                <p class="ppid-page-subtitle">{{ $page->subtitle ?? 'Informasi yang harus segera diumumkan' }}</p>
            </div>

            {{-- Peringatan Section --}}
            @if(isset($sectionsByType['peringatan']))
                @php $peringatan = $sectionsByType['peringatan']->first(); @endphp
                <section class="ppid-section" data-reveal>
                    <div style="background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%); border-radius: 1.5rem; padding: 2rem; color: white; margin-bottom: 2rem;">
                        <h2 style="font-family: var(--font-display); font-size: 1.25rem; font-weight: 600; margin: 0 0 0.5rem;">
                            {{ $peringatan->title ?? 'Informasi Serta Merta' }}
                        </h2>
                        <p style="opacity: 0.95; margin: 0;">
                            {!! $peringatan->content ?? 'Informasi yang harus segera diumumkan karena menyangkut hal yang dapat mengancam kehidupan dan ketertiban umum.' !!}
                        </p>
                    </div>
                </section>
            @endif

            {{-- Daftar Informasi Section --}}
            @if(isset($sectionsByType['daftar_informasi']))
                @php $informasi = $sectionsByType['daftar_informasi']->first(); @endphp
                @php $items = json_decode($informasi->metadata)->items ?? []; @endphp
                <section class="ppid-section" data-reveal>
                    <h2 class="ppid-section-title">{{ $informasi->title ?? 'Daftar Informasi' }}</h2>
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
