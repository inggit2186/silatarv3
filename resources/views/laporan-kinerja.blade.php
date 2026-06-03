<x-layouts.app title="Laporan Kinerja - SILATAR">
    @php
        $activeTab = $activeTab ?? 'harian';
        $selectedMonth = $selectedMonth ?? now()->format('Y-m');
        $selectedMonthLabel = $selectedMonthLabel ?? now()->format('m/Y');
        $selectedYear = $selectedYear ?? date('Y');
        $search = $search ?? '';
        $dailySummary = $dailySummary ?? ['entries' => 0, 'days' => 0, 'volume' => 0, 'latest_update' => null];
        $bulananReports = $bulananReports ?? collect([]);
        $printMode = $printMode ?? false;
        $activityUnits = isset($activityUnits) ? collect($activityUnits)->filter()->values()->all() : ['Kegiatan', 'Dokumen', 'Jam'];
        $defaultActivityDate = \Illuminate\Support\Carbon::createFromFormat('Y-m', $selectedMonth)->startOfMonth()->format('Y-m-d');
        $addModalOpen = (bool) session('open_add_modal', false);
        $editModalOpen = (bool) session('open_edit_modal', false);
        $oldActivityRows = $addModalOpen ? old('items', []) : [];
        $oldEditingRows = $editModalOpen ? old('items', []) : [];
        $initialActivityRows = ! empty($oldActivityRows)
            ? collect($oldActivityRows)->map(function ($item) use ($activityUnits) {
                return [
                    'kegiatan' => $item['kegiatan'] ?? '',
                    'volume' => $item['volume'] ?? '',
                    'satuan' => $item['satuan'] ?? ($activityUnits[0] ?? 'Kegiatan'),
                ];
            })->values()->all()
            : [[
                'kegiatan' => '',
                'volume' => '',
                'satuan' => $activityUnits[0] ?? 'Kegiatan',
            ]];
        $editingGroupItems = isset($editingGroup['items']) ? collect($editingGroup['items']) : collect();
        $editingDateValue = old('tanggal', $editingGroup['date'] ?? '');
        $monthMap = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];
        $editingDateLabel = '';
        if ($editingDateValue) {
            try {
                $editingDateCarbon = \Illuminate\Support\Carbon::parse($editingDateValue);
                $editingDateLabel = sprintf(
                    '%d %s %d',
                    $editingDateCarbon->day,
                    $monthMap[$editingDateCarbon->month] ?? $editingDateCarbon->format('F'),
                    $editingDateCarbon->year
                );
            } catch (\Throwable $exception) {
                $editingDateLabel = $editingDateValue;
            }
        }
        $editingInitialRows = ! empty($oldEditingRows)
            ? collect($oldEditingRows)->map(function ($item) use ($activityUnits) {
                return [
                    'kegiatan' => $item['kegiatan'] ?? '',
                    'volume' => $item['volume'] ?? '',
                    'satuan' => $item['satuan'] ?? ($activityUnits[0] ?? 'Kegiatan'),
                ];
            })->values()->all()
            : $editingGroupItems->map(function ($item) use ($activityUnits) {
                return [
                    'kegiatan' => $item['kegiatan'] ?? '',
                    'volume' => $item['volume'] ?? '',
                    'satuan' => $item['satuan'] ?? ($activityUnits[0] ?? 'Kegiatan'),
                ];
            })->values()->all();
        $editModalOpen = $editModalOpen || $editingGroupItems->isNotEmpty();
    @endphp

    <main
        x-data="reportKinerjaPage(@js([
            'addModalOpen' => $addModalOpen,
            'editModalOpen' => $editModalOpen,
            'defaultDate' => $defaultActivityDate,
            'defaultUnit' => $activityUnits[0] ?? 'Kegiatan',
            'unitOptions' => $activityUnits,
            'initialRows' => $initialActivityRows,
            'editInitialRows' => $editingInitialRows,
            'humasData' => $humasData ?? [],
        ]))"
        class="silatar-report-page space-y-6 {{ $printMode ? 'silatar-report-print-mode' : '' }}"
    >
        <!-- Page Header -->
        <div class="bg-gradient-to-b from-slate-950 via-slate-900 to-slate-950 py-8">
            <div class="mx-auto max-w-6xl px-6 lg:px-8 text-center">
                <span class="inline-flex items-center gap-2 rounded-full border border-cyan-500/30 bg-cyan-500/10 px-4 py-1.5 font-mono text-xs font-semibold uppercase tracking-widest text-cyan-400">
                    <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    Laporan Kinerja
                </span>
                <h1 class="mt-4 font-mono text-3xl font-black uppercase tracking-wider text-white lg:text-4xl">
                    Sistem Pelaporan Digital
                </h1>
                <p class="mt-2 text-sm text-slate-400">Kelola dan pantau laporan kinerja dengan mudah</p>
            </div>
        </div>

        <section class="silatar-report-tabs">
            <div class="silatar-report-tab-list">
                @foreach ($tabLabels as $tabKey => $tab)
                    <a
                        href="{{ route('laporan-kinerja', ['tab' => $tabKey, 'month' => $selectedMonth, 'search' => $search]) }}"
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
            </div>
        </section>

        <section class="silatar-report-shell">
            <div class="silatar-report-shell-header">
                <div class="min-w-0">
                    <p class="font-mono text-xs font-semibold uppercase tracking-widest text-cyan-400">
                        {{ $tabLabels[$activeTab]['label'] ?? 'Laporan Kinerja' }}
                    </p>
                    <h1 class="silatar-report-title">
                        {{ $activeTab === 'humas' ? 'Laporan Humas' : ($activeTab === 'bulanan' ? 'Laporan Kinerja Bulanan' : 'Laporan Kinerja Harian') }}
                    </h1>
                    <p class="silatar-report-subtitle">
                        {{ $activeTab === 'humas'
                            ? 'Ringkasan publikasi kehumasan berdasarkan laporan bulanan yang tersimpan.'
                            : ($activeTab === 'bulanan'
                                ? 'Rekap bulanan dari entri kegiatan yang telah dicatat.'
                                : 'Daftar kegiatan harian yang tersimpan pada bulan terpilih.') }}
                    </p>
                </div>

                <form method="GET" action="{{ route('laporan-kinerja') }}" class="silatar-report-filter">
                    <input type="hidden" name="tab" value="{{ $activeTab }}">
                    <input type="hidden" name="search" value="{{ $search }}">
                    <div class="flex w-full flex-col gap-3 sm:flex-row sm:items-center sm:justify-end">
                        @if($activeTab === 'bulanan')
                            {{-- Year picker for bulanan tab - use component directly --}}
                            <x-ui.yearpicker
                                name="year"
                                :value="$selectedYear"
                                placeholder="Pilih tahun"
                            />
                        @else
                            {{-- Month picker for harian & humas tab --}}
                            <x-ui.monthpicker
                                name="month"
                                :value="$selectedMonth"
                                placeholder="Pilih bulan"
                            />
                        @endif
                        <a
                            href="{{ route('laporan-kinerja.rekap', ['tab' => $activeTab, 'month' => $selectedMonth]) }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            data-loading-variant="print"
                            data-loading-title="Menyiapkan PDF"
                            data-loading-message="Sedang membuka dialog cetak."
                            class="silatar-report-rekap-button"
                        >
                            <svg class="mr-2 h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 6.5A2 2 0 0 1 6.5 4.5h7A2 2 0 0 1 15.5 6.5v7A2 2 0 0 1 13.5 15.5h-7a2 2 0 0 1-2-2z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 8h6" />
                            </svg>
                            Rekap
                        </a>
                    </div>
                </form>
            </div>

            @if ($activeTab === 'harian')
                <div class="p-5">
                    <div class="silatar-report-summary">
                        <div class="silatar-report-summary-header">
                            <div class="flex items-start gap-4">
                                <div class="silatar-report-summary-icon">
                                    <svg class="h-7 w-7" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 4.5h10A1.5 1.5 0 0 1 16.5 6v8A1.5 1.5 0 0 1 15 15.5H5A1.5 1.5 0 0 1 3.5 14V6A1.5 1.5 0 0 1 5 4.5Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 8h6M7 11h5" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="silatar-report-summary-badge {{ $dailySummary['entries'] > 0 ? 'silatar-report-summary-badge-ready' : 'silatar-report-summary-badge-pending' }}">
                                        {{ $dailySummary['entries'] > 0 ? 'Tersimpan' : 'Belum Dikirim' }}
                                    </div>
                                    <h2 class="mt-2 font-mono text-xl font-bold text-white">
                                        Laporan Kinerja Harian {{ $selectedMonthLabel }}
                                    </h2>
                                    <p class="mt-2 max-w-2xl text-sm leading-6 text-cyan-100">
                                        {{ $dailySummary['entries'] > 0
                                            ? 'Data kegiatan sudah tersimpan dan bisa direkap kembali bila diperlukan.'
                                            : 'Belum ada kegiatan yang tercatat pada bulan terpilih.' }}
                                    </p>
                                </div>
                            </div>

                            <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                                <div class="rounded-xl border border-cyan-500/20 bg-slate-900/80 px-4 py-3 shadow-sm">
                                    <p class="font-mono text-xs font-semibold uppercase tracking-widest text-cyan-100">Entri</p>
                                    <p class="mt-2 font-mono text-2xl font-bold text-cyan-400">{{ $dailySummary['entries'] }}</p>
                                </div>
                                <div class="rounded-xl border border-cyan-500/20 bg-slate-900/80 px-4 py-3 shadow-sm">
                                    <p class="font-mono text-xs font-semibold uppercase tracking-widest text-cyan-100">Hari</p>
                                    <p class="mt-2 font-mono text-2xl font-bold text-cyan-400">{{ $dailySummary['days'] }}</p>
                                </div>
                                <div class="rounded-xl border border-cyan-500/20 bg-slate-900/80 px-4 py-3 shadow-sm">
                                    <p class="font-mono text-xs font-semibold uppercase tracking-widest text-cyan-100">Volume</p>
                                    <p class="mt-2 font-mono text-2xl font-bold text-cyan-400">{{ $dailySummary['volume'] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="silatar-report-actions">
                        <form method="GET" action="{{ route('laporan-kinerja') }}" class="flex flex-1 items-center gap-3">
                            <input type="hidden" name="tab" value="{{ $activeTab }}">
                            <input type="hidden" name="month" value="{{ $selectedMonth }}">
                            <input
                                type="search"
                                name="search"
                                value="{{ $search }}"
                                class="silatar-report-search-input"
                                placeholder="Cari data..."
                            >
                            <button type="submit" class="sr-only">Cari</button>
                        </form>
                        <button type="button" @click="openAddModal()" class="silatar-report-add-button">
                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 4v12M4 10h12" />
                            </svg>
                            Tambah
                        </button>
                    </div>

                    <div id="daftar-kegiatan" class="silatar-report-table-shell">
                        @if ($dailyGroups->isEmpty())
                            <div class="silatar-report-empty">
                                <p class="font-mono text-sm font-semibold uppercase tracking-widest text-cyan-400/50">Kosong</p>
                                <p class="mt-2 font-mono text-base font-semibold text-white">Belum ada kegiatan pada bulan ini.</p>
                                <p class="mt-2 text-sm leading-6 text-cyan-100">
                                    Silakan pilih bulan lain atau lanjutkan input kegiatan bila modul tambah sudah dipakai.
                                </p>
                            </div>
                        @else
                            <div class="overflow-x-auto">
                                <table class="silatar-report-table">
                                    <thead>
                                        <tr>
                                            <th class="silatar-report-th w-64">Tanggal</th>
                                            <th class="silatar-report-th">Kegiatan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($dailyGroups as $group)
                                            <tr>
                                                <td class="silatar-report-td text-center">
                                                    <p class="silatar-report-date">{{ $group['label'] }}</p>
                                                    <p class="mt-2 text-xs uppercase tracking-[0.2em] text-cyan-100">
                                                        {{ $group['entries'] }} entri
                                                    </p>
                                                    <div class="mt-4 flex flex-wrap items-center justify-center gap-2">
                                                        @if (! empty($group['date']))
                                                            <a
                                                                href="{{ route('laporan-kinerja', ['tab' => 'harian', 'month' => $selectedMonth, 'search' => $search, 'edit_date' => $group['date']]) }}"
                                                                class="silatar-report-action-button silatar-report-action-edit"
                                                            >
                                                                <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 13.5 13 5l2.5 2.5-8.5 8.5H4.5v-2.5z" />
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6l2 2" />
                                                                </svg>
                                                                Edit
                                                            </a>
                                                            <form
                                                                method="POST"
                                                                action="{{ route('laporan-kinerja.delete-day') }}"
                                                                onsubmit="return confirm('Hapus semua kegiatan pada tanggal ini?')"
                                                            >
                                                                @csrf
                                                                @method('DELETE')
                                                                <input type="hidden" name="tab" value="harian">
                                                                <input type="hidden" name="month" value="{{ $selectedMonth }}">
                                                                <input type="hidden" name="search" value="{{ $search }}">
                                                                <input type="hidden" name="tanggal" value="{{ $group['date'] }}">
                                                                <button type="submit" class="silatar-report-action-button silatar-report-action-delete">
                                                                    <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 6.5h10" />
                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 6.5V5.25A1.25 1.25 0 0 1 9.25 4h1.5A1.25 1.25 0 0 1 12 5.25V6.5" />
                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 6.5l.5 9A1.5 1.5 0 0 0 9 17h2a1.5 1.5 0 0 0 1.5-1.5l.5-9" />
                                                                    </svg>
                                                                    Delete
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td class="silatar-report-td">
                                                    <div class="silatar-report-activity-list">
                                                        @foreach ($group['items'] as $activity)
                                                            <div class="silatar-report-activity-item">
                                                                <div class="silatar-report-activity-row">
                                                                    <div class="min-w-0 flex-1 text-center sm:text-left">
                                                                        <p class="leading-6 text-cyan-200">{{ $activity['kegiatan'] }}</p>
                                                                        @if (! empty($activity['meta']))
                                                                            <p class="mt-1 font-mono text-xs text-cyan-100">{{ $activity['meta'] }}</p>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            @elseif ($activeTab === 'bulanan')
                <div class="p-5">
                    <div class="silatar-report-summary">
                        <div class="silatar-report-summary-header">
                            <div class="flex items-start gap-4">
                                <div class="silatar-report-summary-icon">
                                    <svg class="h-7 w-7" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 4.5h10A1.5 1.5 0 0 1 16.5 6v8A1.5 1.5 0 0 1 15 15.5H5A1.5 1.5 0 0 1 3.5 14V6A1.5 1.5 0 0 1 5 4.5Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 8h6M7 11h5" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="silatar-report-summary-badge silatar-report-summary-badge-ready">
                                        Rekap Bulanan
                                    </div>
                                    <h2 class="mt-2 font-mono text-xl font-bold text-white">
                                        Rekap Kinerja Tahun {{ $selectedYear }}
                                    </h2>
                                    <p class="mt-2 max-w-2xl text-sm leading-6 text-cyan-100">
                                        Daftar laporan kinerja bulanan per pengguna dari tabel satker_ckh.
                                    </p>
                                </div>
                            </div>

                            <div class="grid gap-3 sm:grid-cols-4">
                                <div class="rounded-xl border border-cyan-500/20 bg-slate-900/80 px-4 py-3">
                                    <p class="font-mono text-xs font-semibold uppercase tracking-widest text-cyan-100">Total Entri</p>
                                    <p class="mt-2 font-mono text-2xl font-bold text-cyan-400">{{ $bulananReports->count() }}</p>
                                </div>
                                <div class="rounded-xl border border-emerald-500/30 bg-emerald-500/10 px-4 py-3">
                                    <p class="font-mono text-xs font-semibold uppercase tracking-widest text-emerald-400">Disetujui</p>
                                    <p class="mt-2 font-mono text-2xl font-bold text-emerald-400">{{ $bulananReports->where('status', 'DISETUJUI')->count() }}</p>
                                </div>
                                <div class="rounded-xl border border-amber-500/30 bg-amber-500/10 px-4 py-3">
                                    <p class="font-mono text-xs font-semibold uppercase tracking-widest text-amber-400">Dikirim</p>
                                    <p class="mt-2 font-mono text-2xl font-bold text-amber-400">{{ $bulananReports->where('status', 'DIKIRIM')->count() }}</p>
                                </div>
                                <div class="rounded-xl border border-rose-500/30 bg-rose-500/10 px-4 py-3">
                                    <p class="font-mono text-xs font-semibold uppercase tracking-widest text-rose-400">Ditolak</p>
                                    <p class="mt-2 font-mono text-2xl font-bold text-rose-400">{{ $bulananReports->where('status', 'DITOLAK')->count() }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="silatar-report-table-shell" id="rekap-bulanan">
                        @if ($bulananReports->isEmpty())
                            <div class="silatar-report-empty">
                                <svg class="mx-auto mb-4 h-16 w-16 text-cyan-500/30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <p class="font-mono text-sm font-semibold uppercase tracking-widest text-cyan-400/50">Kosong</p>
                                <p class="mt-2 font-mono text-base font-semibold text-white">Belum ada laporan kinerja bulanan.</p>
                                <p class="mt-2 text-sm leading-6 text-cyan-100">
                                    Tidak ada data laporan kinerja pada tahun {{ $selectedYear }}.
                                </p>
                            </div>
                        @else
                            <div class="overflow-x-auto">
                                    <table class="silatar-report-table">
                                    <thead>
                                        <tr>
                                            <th class="text-center">No</th>
                                            <th class="text-left">Bulan</th>
                                            <th class="text-center">File</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Keterangan</th>
                                            <th class="text-center">Tanggal Kirim</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($bulananReports as $index => $report)
                                            <tr>
                                                <td class="bulanan-cell text-center font-mono text-cyan-400 font-bold">{{ $index + 1 }}</td>
                                                <td class="bulanan-cell font-mono text-cyan-300">{{ $report['bulan'] }}</td>
                                                <td class="bulanan-cell text-center">
                                                    @if($report['filename'])
                                                        <span class="inline-flex items-center gap-2 rounded-full border border-cyan-500/30 bg-cyan-500/10 px-3 py-1 font-mono text-xs text-white">
                                                            <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                                            PDF
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center gap-2 rounded-full border border-rose-500/30 bg-rose-500/10 px-3 py-1 font-mono text-xs text-rose-400">
                                                            -
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="bulanan-cell text-center">
                                                    @if($report['status'] === 'DISETUJUI')
                                                        <span class="cyber-status-badge cyber-status-disetujui"><span class="cyber-dot"></span>Disetujui</span>
                                                    @elseif($report['status'] === 'DIKIRIM')
                                                        <span class="cyber-status-badge cyber-status-dikirim"><span class="cyber-dot"></span>Dikirim</span>
                                                    @elseif($report['status'] === 'DITOLAK')
                                                        <span class="cyber-status-badge cyber-status-ditolak"><span class="cyber-dot"></span>Ditolak</span>
                                                    @else
                                                        <span class="cyber-status-badge cyber-status-belum"><span class="cyber-dot"></span>Belum Kirim</span>
                                                    @endif
                                                </td>
                                                <td class="text-center font-mono text-xs text-cyan-100">{{ $report['alasan'] ?? '-' }}</td>
                                                <td class="text-center font-mono text-xs text-cyan-100">
                                                    @if($report['sending'])
                                                        {{ \Carbon\Carbon::parse($report['sending'])->format('d/m/Y H:i') }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <div class="p-5">
                    <div class="silatar-report-summary">
                        <div class="silatar-report-summary-header">
                            <div class="flex items-start gap-4">
                                <div class="silatar-report-summary-icon">
                                    <svg class="h-7 w-7" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 11c1.5-3 4-4.5 6-4.5S14.5 8 16 11" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5.5 8.5H14.5" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 13.5c1 1 2.2 1.5 3 1.5s2-.5 3-1.5" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="silatar-report-summary-badge silatar-report-summary-badge-ready">
                                        Laporan Humas
                                    </div>
                                    <h2 class="mt-2 font-mono text-xl font-bold text-white">
                                        Rekap publikasi dan kanal aktif
                                    </h2>
                                    <p class="mt-2 max-w-2xl text-sm leading-6 text-cyan-100">
                                        Menampilkan bulan laporan, kanal yang terisi, serta status pemeriksaan.
                                    </p>
                                </div>
                            </div>

                            <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                                <div class="rounded-xl border border-cyan-500/20 bg-slate-900/80 px-4 py-3">
                                    <p class="font-mono text-xs font-semibold uppercase tracking-widest text-cyan-100">Laporan</p>
                                    <p class="mt-2 font-mono text-2xl font-bold text-cyan-400">{{ $humasData->count() }}</p>
                                </div>
                                <div class="rounded-xl border border-cyan-500/20 bg-slate-900/80 px-4 py-3">
                                    <p class="font-mono text-xs font-semibold uppercase tracking-widest text-cyan-100">Kanal aktif</p>
                                    <p class="mt-2 font-mono text-2xl font-bold text-cyan-400">{{ $humasData->sum('active_channels') }}</p>
                                </div>
                                <div class="rounded-xl border border-cyan-500/20 bg-slate-900/80 px-4 py-3">
                                    <p class="font-mono text-xs font-semibold uppercase tracking-widest text-cyan-100">Status</p>
                                    <p class="mt-2 font-mono text-2xl font-bold text-cyan-400">Tersimpan</p>
                                </div>
                                <div class="rounded-xl border border-cyan-500/30 bg-cyan-500/10 px-4 py-3 flex flex-col justify-center">
                                    <p class="font-mono text-xs font-semibold uppercase tracking-widest text-cyan-100 mb-2">Aksi</p>
                                    <button type="button" @click="humasModalOpen = true" class="rounded-full bg-gradient-to-r from-cyan-600 to-cyan-500 px-5 py-2 font-mono text-sm font-semibold text-white shadow-[0_0_20px_rgba(0,212,255,0.3)]">+ Tambah</button>
                                </div>
                            </div>
                        </div>


                    <!-- Hugias Modal -->
                    <div x-show="humasModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background:rgba(0,0,0,0.85);">
                        <div class="w-full max-w-lg rounded-2xl border border-cyan-500/30 bg-slate-900 p-6 shadow-[0_0_60px_rgba(0,212,255,0.3)]">
                            <div class="flex items-center justify-between mb-5">
                                <div>
                                    <h3 class="font-mono text-lg font-bold text-cyan-400">Tambah Laporan Hugias</h3>
                                    <p class="text-xs text-slate-400 mt-1">Isi platform yang aktif</p>
                                </div>
                                <button type="button" @click="humasModalOpen = false" class="text-slate-400 hover:text-white">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                            <form method="POST" action="{{ route('laporan-humas.store') }}" class="space-y-4">
                                @csrf
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block font-mono text-xs uppercase tracking-wider text-cyan-400 mb-1.5">Facebook</label>
                                        <input type="url" name="facebook" class="w-full rounded-lg border border-cyan-500/30 bg-slate-800 px-4 py-2.5 text-white text-sm" placeholder="https://facebook.com/...">
                                    </div>
                                    <div>
                                        <label class="block font-mono text-xs uppercase tracking-wider text-cyan-400 mb-1.5">Instagram</label>
                                        <input type="url" name="instagram" class="w-full rounded-lg border border-cyan-500/30 bg-slate-800 px-4 py-2.5 text-white text-sm" placeholder="https://instagram.com/...">
                                    </div>
                                    <div>
                                        <label class="block font-mono text-xs uppercase tracking-wider text-cyan-400 mb-1.5">Tiktok</label>
                                        <input type="url" name="tiktok" class="w-full rounded-lg border border-cyan-500/30 bg-slate-800 px-4 py-2.5 text-white text-sm" placeholder="https://tiktok.com/@...">
                                    </div>
                                    <div>
                                        <label class="block font-mono text-xs uppercase tracking-wider text-cyan-400 mb-1.5">Website</label>
                                        <input type="url" name="website" class="w-full rounded-lg border border-cyan-500/30 bg-slate-800 px-4 py-2.5 text-white text-sm" placeholder="https://...">
                                    </div>
                                </div>
                                <div>
                                    <label class="block font-mono text-xs uppercase tracking-wider text-cyan-400 mb-1.5">Youtube</label>
                                    <input type="url" name="youtube" class="w-full rounded-lg border border-cyan-500/30 bg-slate-800 px-4 py-2.5 text-white text-sm" placeholder="https://youtube.com/...">
                                </div>
                                <div>
                                    <label class="block font-mono text-xs uppercase tracking-wider text-cyan-400 mb-2">Komentar / Kendala Yang Dihadapi <span class="text-rose-400">*</span></label>
                                    <textarea name="comment" rows="4" class="w-full rounded-lg border border-cyan-500/30 bg-slate-800 px-4 py-3 text-white resize-none" placeholder="Ceritakan kendala..."></textarea>
                                </div>
                                <div class="flex justify-end gap-3">
                                    <button type="button" @click="humasModalOpen = false" class="rounded-full border border-slate-600 px-6 py-2.5 font-mono text-sm text-slate-300 hover:bg-slate-800">Batal</button>
                                    <button type="submit" class="rounded-full bg-gradient-to-r from-cyan-600 to-cyan-500 px-6 py-2.5 font-mono text-sm font-semibold text-white shadow-[0_0_20px_rgba(0,212,255,0.3)]">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="silatar-report-table-shell">
                        @if ($humasData->isEmpty())
                            <div class="silatar-report-empty">
                                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-cyan-400">Kosong</p>
                                <p class="mt-2 text-base font-semibold text-white">Belum ada laporan humas.</p>
                            </div>
                        @else
                            <div class="overflow-x-auto">
                                <table class="silatar-report-table">
                                    <thead>
                                        <tr>
                                            <th class="silatar-report-th">Bulan</th>
                                            <th class="silatar-report-th">Kanal</th>
                                            <th class="silatar-report-th">Status</th>
                                            <th class="silatar-report-th">Komen</th>
                                            <th class="silatar-report-th">Update</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($humasData as $report)
                                            <tr>
                                                <td class="silatar-report-td text-center">
                                                    <span class="silatar-report-date">{{ $report['month_label'] }}</span>
                                                </td>
                                                <td class="silatar-report-td">
                                                    <div class="flex flex-wrap justify-center gap-2">
                                                        @foreach ($report['platforms'] as $channel)
                                                            <span class="inline-flex items-center rounded-full border px-3 py-1 text-[0.68rem] font-semibold uppercase tracking-[0.18em] {{ $channel['has_data'] ? 'border-cyan-400/40 bg-cyan-500/20 text-cyan-300' : 'border-slate-600/40 bg-slate-800/50 text-slate-400' }}">
                                                                {{ ucfirst($channel['name']) }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                </td>
                                                <td class="silatar-report-td text-center font-semibold text-white">{{ $report['status'] }}</td>
                                                <td class="silatar-report-td text-center text-cyan-400">{{ $report['comment'] }}</td>
                                                <td class="silatar-report-td text-center text-cyan-400">{{ $report['updated_at'] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </section>
    <div
        x-show="addModalOpen"
        x-cloak
        class="silatar-report-create-overlay"
        @click.self="closeAddModal()"
    >
        <div class="silatar-report-create-modal">
            <div class="silatar-report-create-header">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.24em] text-cyan-400">Tambah detail kegiatan harian</p>
                    <h3 class="silatar-report-create-title">::: Laporan Kegiatan :::</h3>
                    <p class="silatar-report-create-subtitle">Isi tanggal di atas, lalu tambahkan satu atau beberapa kegiatan di bawahnya.</p>
                </div>
                <button
                    type="button"
                    @click="closeAddModal()"
                    class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-slate-200 bg-white text-cyan-400 transition hover:border-cyan-200 hover:text-white"
                    aria-label="Tutup"
                >
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 5l10 10M15 5 5 15" />
                    </svg>
                </button>
            </div>

            <form method="POST" action="{{ route('laporan-kinerja.store') }}">
                @csrf
                <input type="hidden" name="tab" value="harian">
                <input type="hidden" name="month" value="{{ $selectedMonth }}">
                <input type="hidden" name="search" value="{{ $search }}">

                <div class="silatar-report-create-body">
                    @if ($errors->any())
                        <div class="rounded-[1.25rem] border border-rose-200 bg-rose-50 px-4 py-3 text-sm leading-6 text-rose-700">
                            <p class="font-semibold">Ada data yang belum lengkap.</p>
                            <p class="mt-1">{{ $errors->first() }}</p>
                        </div>
                    @endif

                    <x-ui.datepicker
                        name="tanggal"
                        label="Tanggal"
                        :value="old('tanggal', $defaultActivityDate)"
                        :required="true"
                    />

                    <div class="silatar-report-create-row">
                        <div class="silatar-report-create-row-head">
                            <div class="flex items-center gap-3">
                                <span class="silatar-report-create-row-badge">1</span>
                                <div>
                                    <p class="text-sm font-semibold text-slate-950">Kegiatan</p>
                                    <p class="text-xs text-cyan-400">Kegiatan Anda - Volume - Jenis</p>
                                </div>
                            </div>

                            <button type="button" @click="addRow()" class="silatar-report-create-add">
                                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 4v12M4 10h12" />
                                </svg>
                                Tambah
                            </button>
                        </div>

                        <div class="silatar-report-create-row-list space-y-3">
                            <template x-for="(row, index) in rows" :key="index">
                                <div class="silatar-report-create-grid">
                                    <div>
                                        <label class="mb-2 block text-xs font-semibold uppercase tracking-[0.18em] text-cyan-400">Kegiatan Anda</label>
                                        <input
                                            :name="`items[${index}][kegiatan]`"
                                            x-model="row.kegiatan"
                                            type="text"
                                            class="silatar-report-edit-field"
                                            placeholder="Kegiatan Anda"
                                        >
                                    </div>
                                    <div>
                                        <label class="mb-2 block text-xs font-semibold uppercase tracking-[0.18em] text-cyan-400">Volume</label>
                                        <input
                                            :name="`items[${index}][volume]`"
                                            x-model="row.volume"
                                            type="number"
                                            min="0"
                                            class="silatar-report-edit-field"
                                            placeholder="Volume"
                                        >
                                    </div>
                                    <div>
                                        <label class="mb-2 block text-xs font-semibold uppercase tracking-[0.18em] text-cyan-400">Jenis</label>
                                        <select
                                            :name="`items[${index}][satuan]`"
                                            x-model="row.satuan"
                                            class="silatar-report-edit-field"
                                        >
                                            @foreach ($activityUnits as $unit)
                                                <option value="{{ $unit }}">{{ $unit }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="flex items-end justify-end">
                                        <button type="button" @click="removeRow(index)" class="silatar-report-create-remove" aria-label="Hapus baris">
                                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 6.5h10" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 6.5V5.25A1.25 1.25 0 0 1 9.25 4h1.5A1.25 1.25 0 0 1 12 5.25V6.5" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 6.5l.5 9A1.5 1.5 0 0 0 9 17h2a1.5 1.5 0 0 0 1.5-1.5l.5-9" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </div>

                        @error('items')
                            <p class="mt-3 text-xs font-medium text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="silatar-report-create-footer">
                    <div class="text-sm text-cyan-400">
                        Baris kosong yang tidak diisi akan diabaikan saat simpan.
                    </div>
                    <div class="silatar-report-create-actions">
                        <button type="button" @click="closeAddModal()" class="silatar-report-edit-cancel">
                            Batal
                        </button>
                        <button type="submit" class="silatar-report-edit-primary">
                            Simpan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div
        x-show="editModalOpen"
        x-cloak
        class="silatar-report-create-overlay"
        @click.self="closeEditModal()"
    >
        <div class="silatar-report-create-modal">
            <div class="silatar-report-create-header">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.24em] text-cyan-400">Edit detail kegiatan harian</p>
                    <h3 class="silatar-report-create-title">::: Laporan Kegiatan :::</h3>
                    <p class="silatar-report-create-subtitle">
                        Ubah tanggal dan semua kegiatan pada hari terpilih, lalu simpan kembali.
                    </p>
                </div>
                <button
                    type="button"
                    @click="closeEditModal()"
                    class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-slate-200 bg-white text-cyan-400 transition hover:border-cyan-200 hover:text-white"
                    aria-label="Tutup"
                >
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 5l10 10M15 5 5 15" />
                    </svg>
                </button>
            </div>

            <form method="POST" action="{{ route('laporan-kinerja.update-day') }}">
                @csrf
                @method('PUT')
                <input type="hidden" name="tab" value="harian">
                <input type="hidden" name="month" value="{{ $selectedMonth }}">
                <input type="hidden" name="search" value="{{ $search }}">

                <div class="silatar-report-create-body">
                    @if ($errors->any())
                        <div class="rounded-[1.25rem] border border-rose-200 bg-rose-50 px-4 py-3 text-sm leading-6 text-rose-700">
                            <p class="font-semibold">Ada data yang belum lengkap.</p>
                            <p class="mt-1">{{ $errors->first() }}</p>
                        </div>
                    @endif

                    <x-ui.datepicker
                        name="tanggal"
                        label="Tanggal"
                        :value="$editingDateValue"
                        :required="true"
                    />

                    <div class="silatar-report-create-row">
                        <div class="silatar-report-create-row-head">
                            <div class="flex items-center gap-3">
                                <span class="silatar-report-create-row-badge">{{ $editingGroupItems->count() ?: count($editingInitialRows) }}</span>
                                <div>
                                    <p class="text-sm font-semibold text-slate-950">Kegiatan</p>
                                    <p class="text-xs text-cyan-400">Kegiatan Anda - Volume - Jenis</p>
                                </div>
                            </div>

                            <button type="button" @click="addEditRow()" class="silatar-report-create-add">
                                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 4v12M4 10h12" />
                                </svg>
                                Tambah
                            </button>
                        </div>

                        <div class="silatar-report-create-row-list space-y-3">
                            <template x-for="(row, index) in editRows" :key="index">
                                <div class="silatar-report-create-grid">
                                    <div>
                                        <label class="mb-2 block text-xs font-semibold uppercase tracking-[0.18em] text-cyan-400">Kegiatan Anda</label>
                                        <input
                                            :name="`items[${index}][kegiatan]`"
                                            x-model="row.kegiatan"
                                            type="text"
                                            class="silatar-report-edit-field"
                                            placeholder="Kegiatan Anda"
                                        >
                                    </div>
                                    <div>
                                        <label class="mb-2 block text-xs font-semibold uppercase tracking-[0.18em] text-cyan-400">Volume</label>
                                        <input
                                            :name="`items[${index}][volume]`"
                                            x-model="row.volume"
                                            type="number"
                                            min="0"
                                            class="silatar-report-edit-field"
                                            placeholder="Volume"
                                        >
                                    </div>
                                    <div>
                                        <label class="mb-2 block text-xs font-semibold uppercase tracking-[0.18em] text-cyan-400">Jenis</label>
                                        <select
                                            :name="`items[${index}][satuan]`"
                                            x-model="row.satuan"
                                            class="silatar-report-edit-field"
                                        >
                                            @foreach ($activityUnits as $unit)
                                                <option value="{{ $unit }}">{{ $unit }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="flex items-end justify-end">
                                        <button type="button" @click="removeEditRow(index)" class="silatar-report-create-remove" aria-label="Hapus baris">
                                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 6.5h10" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 6.5V5.25A1.25 1.25 0 0 1 9.25 4h1.5A1.25 1.25 0 0 1 12 5.25V6.5" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 6.5l.5 9A1.5 1.5 0 0 0 9 17h2a1.5 1.5 0 0 0 1.5-1.5l.5-9" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </div>

                        @error('items')
                            <p class="mt-3 text-xs font-medium text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="silatar-report-create-footer">
                    <div class="text-sm text-cyan-400">
                        Baris kosong yang tidak diisi akan diabaikan saat simpan.
                    </div>
                    <div class="silatar-report-create-actions">
                        <button type="button" @click="closeEditModal()" class="silatar-report-edit-cancel">
                            Batal
                        </button>
                        <button type="submit" class="silatar-report-edit-primary">
                            Simpan Perubahan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if ($printMode)
        <script>
            window.addEventListener('load', function () {
                window.print();
            });
        </script>
    @endif
</main>
</x-layouts.app>
