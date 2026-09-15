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
                @php $statsData = json_decode($statistik->metadata)->stats ?? []; @endphp
                <section class="ppid-section" data-reveal>
                    <h2 class="ppid-section-title">{{ $statistik->title ?? 'Statistik' }}</h2>
                    <div class="ppid-stats">
                        @foreach($statsData as $stat)
                            <div class="ppid-stat">
                                <div class="ppid-stat-value">{{ $stat->value }}</div>
                                <div class="ppid-stat-label">{{ $stat->label }}</div>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

            {{-- Rekap Bulanan Section --}}
            @if(isset($sectionsByType['rekap_bulanan']))
                @php $rekap = $sectionsByType['rekap_bulanan']->first(); @endphp
                @php $tableData = json_decode($rekap->metadata); @endphp
                <section class="ppid-section" data-reveal>
                    <h2 class="ppid-section-title">{{ $rekap->title ?? 'Rekap Bulanan' }}</h2>
                    <div style="background: white; border: 1px solid rgba(140, 135, 130, 0.15); border-radius: 1.5rem; overflow: hidden;">
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
        </div>
        @include('ppid.partials.footer')
    </main>
</x-layouts.ppid-layout>
