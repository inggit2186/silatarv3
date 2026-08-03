<!-- Laporan Kinerja Tab Navigation Component -->
@props([
    'activeTab' => 'harian',
    'tabLabels' => [
        'harian' => ['label' => 'Kinerja Harian'],
        'bulanan' => ['label' => 'Laporan Bulanan'],
        'humas' => ['label' => 'Laporan Humas'],
    ],
    'showBawahan' => false,
    'selectedMonth' => date('Y-m'),
    'selectedYear' => date('Y'),
    'search' => '',
])

<section class="silatar-report-tabs">
    <div class="silatar-report-tab-list">
        @foreach ($tabLabels as $tabKey => $tab)
            @php
                $tabQuery = ['tab' => $tabKey, 'search' => $search];
                if ($tabKey === 'bulanan') {
                    $tabQuery['year'] = $selectedYear;
                } else {
                    $tabQuery['month'] = $selectedMonth;
                }
            @endphp
            <a
                href="{{ route('laporan-kinerja', $tabQuery) }}"
                class="silatar-report-tab {{ $activeTab === $tabKey ? 'silatar-report-tab-active' : 'silatar-report-tab-inactive' }}"
            >
                @if ($tabKey === 'harian')
                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 4.5h10A1.5 1.5 0 0 1 16.5 6v8A1.5 1.5 0 0 1 15 15.5H5A1.5 1.5 0 0 1 3.5 14V6A1.5 1.5 0 0 1 5 4.5Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 8h6M7 11h5" />
                    </svg>
                @elseif ($tabKey === 'bulanan')
                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 4.5h10A1.5 1.5 0 0 1 16.5 6v8A1.5 1.5 0 0 1 15 15.5H5A1.5 1.5 0 0 1 3.5 14V6A1.5 1.5 0 0 1 5 4.5Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.5 8.5h7M6.5 11.5h4.5" />
                    </svg>
                @else
                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 11.5c2-4 4.8-6 6-6s4 2 6 6" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 8.5h10" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.5 13.5c1.2 1 2.6 1.5 3.5 1.5s2.3-.5 3.5-1.5" />
                    </svg>
                @endif
                {{ $tab['label'] }}
            </a>
        @endforeach

        {{-- Laporan Bawahan Tab --}}
        @if($showBawahan)
            <a
                href="{{ route('laporan-kinerja.bawahan') }}"
                class="silatar-report-tab {{ request()->routeIs('laporan-kinerja.bawahan') ? 'silatar-report-tab-active' : 'silatar-report-tab-inactive' }}"
            >
                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Laporan Bawahan
            </a>
        @endif
    </div>
</section>
