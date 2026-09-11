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
                @php $tableData = json_decode($jadwal->metadata); @endphp
                <section class="ppid-section" data-reveal>
                    <h2 class="ppid-section-title">{{ $jadwal->title ?? 'Jam Operasional' }}</h2>
                    <div style="background: white; border: 1px solid rgba(140, 135, 130, 0.15); border: 1px solid oklch(58% 0.06 76 / 0.15); border-radius: 1.5rem; overflow: hidden;">
                        <table class="ppid-table" style="border-radius: 0;">
                            <thead>
                                <tr>
                                    @foreach($tableData->headers as $header)
                                        <th>{{ $header }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tableData->rows as $row)
                                    <tr>
                                        @foreach($row as $cell)
                                            <td>{{ $cell }}</td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </section>
            @endif

            {{-- Kontak Section --}}
            @if(isset($sectionsByType['kontak']))
                @php $kontak = $sectionsByType['kontak']->first(); @endphp
                @php $cards = json_decode($kontak->metadata)->cards ?? []; @endphp
                <section class="ppid-section" data-reveal>
                    <h2 class="ppid-section-title">{{ $kontak->title ?? 'Kontak Layanan' }}</h2>
                    <div class="ppid-grid">
                        @foreach($cards as $card)
                            <div class="ppid-card">
                                <div class="ppid-card-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/>
                                    </svg>
                                </div>
                                <h3 class="ppid-card-title">{{ $card->title }}</h3>
                                <p class="ppid-card-text">{{ $card->description }}</p>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif
        </div>
        @include('ppid.partials.footer')
    </main>
</x-layouts.ppid-layout>
