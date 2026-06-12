<x-layouts.app title="Laporan Kinerja - SILATAR">
    @php
        $activeTab = $activeTab ?? 'harian';
        $selectedMonth = $selectedMonth ?? now()->format('Y-m');
        $selectedMonthLabel = $selectedMonthLabel ?? now()->format('m/Y');
        $selectedYear = $selectedYear ?? date('Y');
        $selectedYearLabel = $selectedYearLabel ?? $selectedYear;
        $search = $search ?? '';
        $dailySummary = $dailySummary ?? ['entries' => 0, 'days' => 0, 'volume' => 0, 'latest_update' => null];
        $bulananReports = $bulananReports ?? collect([]);
        $monthlySummary = $monthlySummary ?? ['months' => 0, 'days' => 0, 'entries' => 0, 'volume' => 0, 'latest_update' => null];
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

                {{-- Laporan Bawahan Tab - only for kepala, kasubbag, kasi --}}
                @if(in_array(strtolower(auth()->user()->kat_jabatan ?? ''), ['kepala', 'kasubbag', 'kasi']))
                    <a
                        href="{{ route('laporan-kinerja.bawahan') }}"
                        class="silatar-report-tab silatar-report-tab-inactive"
                    >
                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Laporan Bawahan
                    </a>
                @endif
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
                    <p class="silatar-report-subtitle {{ $activeTab === 'bulanan' ? 'hidden' : '' }}">
                        {{ $activeTab === 'humas'
                            ? 'Ringkasan publikasi kehumasan berdasarkan laporan bulanan yang tersimpan.'
                            : 'Daftar kegiatan harian yang tersimpan pada bulan terpilih.' }}
                    </p>
                </div>

                <form method="GET" action="{{ route('laporan-kinerja') }}" class="silatar-report-filter">
                    <input type="hidden" name="tab" value="{{ $activeTab }}">
                    <input type="hidden" name="search" value="{{ $search }}">
                    <div class="flex w-full flex-col gap-3 sm:flex-row sm:items-center sm:justify-end">
                        @if ($activeTab === 'bulanan')
                            {{-- Year picker for bulanan tab - use cyber component --}}
                            <x-ui.cyber-yearpicker
                                name="year"
                                :value="$selectedYear"
                                placeholder="Pilih tahun"
                            />
                        @elseif ($activeTab === 'humas')
                            {{-- Year picker for humas tab - use cyber component --}}
                            <x-ui.cyber-yearpicker
                                name="humas_year"
                                :value="$humasYear ?? date('Y')"
                                placeholder="Pilih tahun"
                            />
                        @else
                            {{-- Month picker for harian tab --}}
                            <x-ui.monthpicker
                                name="month"
                                :value="$selectedMonth"
                                placeholder="Pilih bulan"
                            />
                        @endif
                        @if ($activeTab === 'harian')
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
                        @endif
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

                            {{-- Upload Button --}}
                            <div class="mt-4 flex justify-end">
                                <button
                                    type="button"
                                    @click="openUploadModal()"
                                    class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-cyan-600 to-cyan-500 px-5 py-2.5 font-mono text-sm font-bold uppercase tracking-wider text-white shadow-[0_0_20px_rgba(0,212,255,0.3)] transition-all hover:shadow-[0_0_30px_rgba(0,212,255,0.5)]"
                                >
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                    </svg>
                                    Upload Laporan
                                </button>
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
                                                        <div class="flex flex-col items-center gap-2">
                                                            <button
                                                                type="button"
                                                                @click="openPdfPreview('/storage/satker_ckh/{{ $report['user_id'] }}/{{ $report['filename'] }}', '{{ $report['bulan'] }}')"
                                                                class="inline-flex items-center gap-2 rounded-full border border-cyan-500/30 bg-cyan-500/10 px-3 py-1 font-mono text-xs text-white hover:bg-cyan-500/20 hover:border-cyan-500/50 transition cursor-pointer"
                                                            >
                                                                <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                                                PDF
                                                            </button>
                                                            @if(in_array($report['status'], ['DIKIRIM', 'DITOLAK']))
                                                                <button
                                                                    type="button"
                                                                    @click="openReplaceModal({{ $report['id'] }}, '{{ $report['bulan'] }}')"
                                                                    class="inline-flex items-center gap-1.5 rounded-full border border-amber-500/30 bg-amber-500/10 px-3 py-1 font-mono text-xs text-amber-400 hover:bg-amber-500/20 hover:border-amber-500/50 transition cursor-pointer"
                                                                >
                                                                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                                                    Ganti File
                                                                </button>
                                                            @endif
                                                        </div>
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
                <div x-data="humasPage()">
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
                                    <button type="button" @click="openAddModal()" class="rounded-full bg-gradient-to-r from-rose-600 to-rose-500 px-5 py-2 font-mono text-sm font-semibold text-white shadow-[0_0_20px_rgba(244,63,94,0.3)]">
                                    TAMBAH
                                </button>
                                </div>
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
                                            <th class="silatar-report-th text-center">Platform</th>
                                            <th class="silatar-report-th">Status</th>
                                            <th class="silatar-report-th">Komen</th>
                                            <th class="silatar-report-th">Update</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($humasData as $report)
                                            <tr>
                                                <td class="silatar-report-td text-center">
                                                    @php
                                                                $namaBulan = [
                                                                    '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
                                                                    '04' => 'April', '05' => 'Mei', '06' => 'Juni',
                                                                    '07' => 'Juli', '08' => 'Agustus', '09' => 'September',
                                                                    '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
                                                                ];
                                                                $parts = explode('/', $report['month_label']);
                                                                $bulanNama = $namaBulan[$parts[0]] ?? $report['month_label'];
                                                                $tahun = $parts[1] ?? '';
                                                            @endphp
                                                    <span class="silatar-report-date">{{ $bulanNama }} {{ $tahun }}</span>
                                                </td>
                                                <td class="silatar-report-td">
                                                    <div class="flex items-center justify-center gap-2">
                                                        @foreach ($report['platforms'] as $platform)
                                                            @if ($platform['has_data'])
                                                                <button type="button" @click="openPlatformDetail({{ $report['id'] }}, '{{ $platform['name'] }}')" class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-white/10 shadow-lg hover:bg-white/20 transition cursor-pointer" title="{{ ucfirst($platform['name']) }}">
                                                                    @if ($platform['name'] === 'facebook')
                                                                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="#1877F2"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                                                    @elseif ($platform['name'] === 'instagram')
                                                                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="url(#instagram-gradient)"><defs><linearGradient id="instagram-gradient" x1="0%" y1="100%" x2="100%" y2="0%"><stop offset="0%" style="stop-color:#FFDC80"/><stop offset="50%" style="stop-color:#F56040"/><stop offset="100%" style="stop-color:#833AB4"/></linearGradient></defs><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                                                                    @elseif ($platform['name'] === 'tiktok')
                                                                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="#000"><path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-5.2 1.74 2.89 2.89 0 012.31-4.64 2.93 2.93 0 01.88.13V9.4a6.84 6.84 0 00-1-.05A6.33 6.33 0 005 20.1a6.34 6.34 0 0010.86-4.43v-7a8.16 8.16 0 004.77 1.52v-3.4a4.85 4.85 0 01-1-.1z"/></svg>
                                                                    @elseif ($platform['name'] === 'website')
                                                                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="#06b6d4" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z"/></svg>
                                                                    @elseif ($platform['name'] === 'youtube')
                                                                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="#FF0000"><path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                                                                    @endif
                                                                </button>
                                                            @endif
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

                {{-- Platform Detail Modal --}}
                <div x-show="platformDetailOpen" x-cloak class="fixed inset-0 z-[60] flex items-center justify-center p-4" style="background:rgba(0,0,0,0.9); backdrop-filter:blur(4px);">
                    <div class="w-full max-w-lg rounded-2xl border border-cyan-500/40 bg-gradient-to-b from-slate-900 to-slate-950 shadow-[0_0_80px_rgba(0,212,255,0.4)]">
                        {{-- Header with Platform Logo --}}
                        <div class="relative rounded-t-2xl overflow-hidden">
                            <div class="absolute inset-0 bg-gradient-to-r from-cyan-500/20 via-transparent to-cyan-500/20"></div>
                            <div class="relative flex items-center gap-4 px-6 py-5 border-b border-cyan-500/20">
                                <template x-if="platformDetailName === 'Facebook'">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 rounded-xl bg-[#1877F2] flex items-center justify-center shadow-lg">
                                            <svg class="w-7 h-7" viewBox="0 0 24 24" fill="#fff"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                        </div>
                                        <div>
                                            <h3 class="font-bold text-xl text-white">Facebook</h3>
                                            <p class="text-xs text-cyan-400">Platform Media Sosial</p>
                                        </div>
                                    </div>
                                </template>
                                <template x-if="platformDetailName === 'Instagram'">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-yellow-400 via-pink-500 to-purple-600 flex items-center justify-center shadow-lg">
                                            <svg class="w-7 h-7" viewBox="0 0 24 24" fill="#fff"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                                        </div>
                                        <div>
                                            <h3 class="font-bold text-xl text-white">Instagram</h3>
                                            <p class="text-xs text-pink-400">Platform Media Sosial</p>
                                        </div>
                                    </div>
                                </template>
                                <template x-if="platformDetailName === 'TikTok'">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 rounded-xl bg-black flex items-center justify-center shadow-lg border border-slate-700">
                                            <svg class="w-7 h-7" viewBox="0 0 24 24" fill="#fff"><path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-5.2 1.74 2.89 2.89 0 012.31-4.64 2.93 2.93 0 01.88.13V9.4a6.84 6.84 0 00-1-.05A6.33 6.33 0 005 20.1a6.34 6.34 0 0010.86-4.43v-7a8.16 8.16 0 004.77 1.52v-3.4a4.85 4.85 0 01-1-.1z"/></svg>
                                        </div>
                                        <div>
                                            <h3 class="font-bold text-xl text-white">TikTok</h3>
                                            <p class="text-xs text-slate-400">Platform Media Sosial</p>
                                        </div>
                                    </div>
                                </template>
                                <template x-if="platformDetailName === 'Website'">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 rounded-xl bg-cyan-500/20 border border-cyan-500/40 flex items-center justify-center shadow-lg">
                                            <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="#06b6d4" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10 15.3 15.3 0 014-10z"/></svg>
                                        </div>
                                        <div>
                                            <h3 class="font-bold text-xl text-white">Website</h3>
                                            <p class="text-xs text-cyan-400">Website Resmi</p>
                                        </div>
                                    </div>
                                </template>
                                <template x-if="platformDetailName === 'YouTube'">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 rounded-xl bg-red-600 flex items-center justify-center shadow-lg">
                                            <svg class="w-7 h-7" viewBox="0 0 24 24" fill="#fff"><path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                                        </div>
                                        <div>
                                            <h3 class="font-bold text-xl text-white">YouTube</h3>
                                            <p class="text-xs text-red-400">Platform Video</p>
                                        </div>
                                    </div>
                                </template>
                                <button type="button" @click="closePlatformDetail()" class="ml-auto rounded-full bg-slate-800/50 p-2 text-slate-400 hover:text-white hover:bg-slate-700 transition">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                        </div>

                        {{-- Content --}}
                        <div class="p-6 space-y-6">
                            {{-- First Posting --}}
                            <div class="bg-white/5 rounded-xl border border-white/10 overflow-hidden">
                                <div class="bg-gradient-to-r from-cyan-500/10 to-transparent px-4 py-3 border-b border-white/10">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-cyan-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.828a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/></svg>
                                        <span class="font-semibold text-cyan-400 text-sm">Posting Pertama</span>
                                    </div>
                                </div>
                                <div class="p-4 space-y-3">
                                    <div class="flex items-start gap-3">
                                        <span class="text-slate-500 text-xs font-mono w-16 mt-0.5">Tanggal</span>
                                        <span class="text-white text-sm" x-text="platformDetailData?.first_date ? new Date(platformDetailData.first_date).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }) : '-'">-</span>
                                    </div>
                                    <div class="flex items-start gap-3">
                                        <span class="text-slate-500 text-xs font-mono w-16 mt-0.5">Judul</span>
                                        <span class="text-white text-sm" x-text="platformDetailData?.first_content || '-'">-</span>
                                    </div>
                                    <div class="flex items-start gap-3">
                                        <span class="text-slate-500 text-xs font-mono w-16 mt-0.5">Link</span>
                                        <template x-if="platformDetailData?.first_link">
                                            <a :href="platformDetailData.first_link" target="_blank" class="text-cyan-400 hover:text-cyan-300 text-sm underline break-all" x-text="platformDetailData.first_link"></a>
                                        </template>
                                        <span x-if="!platformDetailData?.first_link" class="text-slate-600 text-sm">-</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Last Posting --}}
                            <div class="bg-white/5 rounded-xl border border-white/10 overflow-hidden">
                                <div class="bg-gradient-to-r from-amber-500/10 to-transparent px-4 py-3 border-b border-white/10">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.828a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/><path fill="currentColor" d="M10 10a.75.75 0 01.75.75v4a.75.75 0 01-.75.75h-4a.75.75 0 010-1.5h4z"/></svg>
                                        <span class="font-semibold text-amber-400 text-sm">Posting Terakhir</span>
                                    </div>
                                </div>
                                <div class="p-4 space-y-3">
                                    <div class="flex items-start gap-3">
                                        <span class="text-slate-500 text-xs font-mono w-16 mt-0.5">Tanggal</span>
                                        <span class="text-white text-sm" x-text="platformDetailData?.last_date ? new Date(platformDetailData.last_date).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }) : '-'">-</span>
                                    </div>
                                    <div class="flex items-start gap-3">
                                        <span class="text-slate-500 text-xs font-mono w-16 mt-0.5">Judul</span>
                                        <span class="text-white text-sm" x-text="platformDetailData?.last_content || '-'">-</span>
                                    </div>
                                    <div class="flex items-start gap-3">
                                        <span class="text-slate-500 text-xs font-mono w-16 mt-0.5">Link</span>
                                        <template x-if="platformDetailData?.last_link">
                                            <a :href="platformDetailData.last_link" target="_blank" class="text-amber-400 hover:text-amber-300 text-sm underline break-all" x-text="platformDetailData.last_link"></a>
                                        </template>
                                        <span x-if="!platformDetailData?.last_link" class="text-slate-600 text-sm">-</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Hummas Modal --}}
                <div x-show="modalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background:rgba(0,0,0,0.85);">
                    <div class="w-full max-w-4xl rounded-2xl border border-cyan-500/30 bg-slate-900 shadow-[0_0_60px_rgba(0,212,255,0.3)] max-h-[90vh] overflow-y-auto">
                        <div class="sticky top-0 z-10 flex items-center justify-between rounded-t-2xl border-b border-cyan-500/30 bg-gradient-to-r from-slate-900 to-slate-800 px-6 py-4">
                            <div>
                                <h3 class="font-mono text-lg font-bold text-cyan-400">::: Laporan Kehumasan :::</h3>
                                <p class="text-xs text-slate-400 mt-1" x-text="isEdit ? 'Edit Detail Kegiatan' : 'Detail Kegiatan Baru'"></p>
                            </div>
                            <button type="button" @click="closeModal()" class="rounded-full bg-slate-700/50 p-2 text-slate-400 transition hover:bg-slate-600 hover:text-white">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>

                        <form method="POST" :action="isEdit ? '/laporan-kinerja/humas/' + editingId : '{{ route('laporan-humas.store') }}'" class="p-6 space-y-6">
                            @csrf
                            <template x-if="isEdit">
                                @method('PUT')
                            </template>

                            <div>
                                <label class="block font-mono text-xs uppercase tracking-wider text-cyan-400 mb-2">Bulan Pelaporan <span class="text-rose-400">*</span></label>
                                <input type="month" name="bulan" x-model="selectedMonth" class="w-full rounded-lg border border-cyan-500/30 bg-slate-800 px-4 py-3 text-white font-mono" required>
                            </div>

                            <div class="space-y-4">
                                <h4 class="font-mono text-sm font-semibold uppercase tracking-wider text-cyan-300 border-b border-cyan-500/20 pb-2">Data Posting per Platform</h4>

                                <template x-for="platform in platforms" :key="platform">
                                    <div class="rounded-xl border border-cyan-500/20 bg-slate-800/50 p-4">
                                        <div class="flex items-center justify-center gap-3 mb-4">
                                            {{-- Platform Logo SVG --}}
                                            <template x-if="platform === 'Facebook'">
                                                <svg class="w-8 h-8" viewBox="0 0 24 24" fill="#1877F2"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                            </template>
                                            <template x-if="platform === 'Instagram'">
                                                <svg class="w-8 h-8" viewBox="0 0 24 24" fill="url(#instagram-gradient)"><defs><linearGradient id="instagram-gradient" x1="0%" y1="100%" x2="100%" y2="0%"><stop offset="0%" style="stop-color:#FFDC80"/><stop offset="50%" style="stop-color:#F56040"/><stop offset="100%" style="stop-color:#833AB4"/></linearGradient></defs><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                                            </template>
                                            <template x-if="platform === 'TikTok'">
                                                <svg class="w-8 h-8" viewBox="0 0 24 24" fill="#000"><path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-5.2 1.74 2.89 2.89 0 012.31-4.64 2.93 2.93 0 01.88.13V9.4a6.84 6.84 0 00-1-.05A6.33 6.33 0 005 20.1a6.34 6.34 0 0010.86-4.43v-7a8.16 8.16 0 004.77 1.52v-3.4a4.85 4.85 0 01-1-.1z"/></svg>
                                            </template>
                                            <template x-if="platform === 'Website'">
                                                <svg class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="#06b6d4" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z"/></svg>
                                            </template>
                                            <template x-if="platform === 'YouTube'">
                                                <svg class="w-8 h-8" viewBox="0 0 24 24" fill="#FF0000"><path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                                            </template>
                                            <h5 class="font-mono text-sm font-bold text-cyan-400 uppercase tracking-wider" x-text="platform"></h5>
                                        </div>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div class="space-y-3">
                                                <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Posting Pertama</div>
                                                <div>
                                                    <label class="block text-xs text-slate-400 mb-1">Tanggal</label>
                                                    <input type="date" :name="platform.toLowerCase() + '[first][date]'" x-model="platformData[platform].first.date" class="w-full rounded-lg border border-cyan-500/30 bg-slate-900 px-3 py-2 text-white text-sm">
                                                </div>
                                                <div>
                                                    <label class="block text-xs text-slate-400 mb-1">Judul Pemberitaan</label>
                                                    <input type="text" :name="platform.toLowerCase() + '[first][content]'" x-model="platformData[platform].first.content" placeholder="Judul pemberitaan..." class="w-full rounded-lg border border-cyan-500/30 bg-slate-900 px-3 py-2 text-white text-sm">
                                                </div>
                                                <div>
                                                    <label class="block text-xs text-slate-400 mb-1">Link</label>
                                                    <input type="url" :name="platform.toLowerCase() + '[first][link]'" x-model="platformData[platform].first.link" placeholder="https://..." class="w-full rounded-lg border border-cyan-500/30 bg-slate-900 px-3 py-2 text-white text-sm">
                                                </div>
                                            </div>
                                            <div class="space-y-3">
                                                <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Posting Terakhir</div>
                                                <div>
                                                    <label class="block text-xs text-slate-400 mb-1">Tanggal</label>
                                                    <input type="date" :name="platform.toLowerCase() + '[last][date]'" x-model="platformData[platform].last.date" class="w-full rounded-lg border border-cyan-500/30 bg-slate-900 px-3 py-2 text-white text-sm">
                                                </div>
                                                <div>
                                                    <label class="block text-xs text-slate-400 mb-1">Judul Pemberitaan</label>
                                                    <input type="text" :name="platform.toLowerCase() + '[last][content]'" x-model="platformData[platform].last.content" placeholder="Judul pemberitaan..." class="w-full rounded-lg border border-cyan-500/30 bg-slate-900 px-3 py-2 text-white text-sm">
                                                </div>
                                                <div>
                                                    <label class="block text-xs text-slate-400 mb-1">Link</label>
                                                    <input type="url" :name="platform.toLowerCase() + '[last][link]'" x-model="platformData[platform].last.link" placeholder="https://..." class="w-full rounded-lg border border-cyan-500/30 bg-slate-900 px-3 py-2 text-white text-sm">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <div>
                                <label class="block font-mono text-xs uppercase tracking-wider text-cyan-400 mb-2">Komentar / Kendala Yang Dihadapi</label>
                                <textarea name="comment" x-model="comment" rows="4" class="w-full rounded-lg border border-cyan-500/30 bg-slate-800 px-4 py-3 text-white resize-none" placeholder="Ceritakan kendala atau komentar..."></textarea>
                            </div>

                            <div class="flex justify-end gap-3 pt-4 border-t border-cyan-500/20">
                                <button type="button" @click="closeModal()" class="rounded-full border border-slate-600 px-6 py-2.5 font-mono text-sm text-slate-300 hover:bg-slate-800">Batal</button>
                                <button type="submit" class="rounded-full bg-gradient-to-r from-cyan-600 to-cyan-500 px-6 py-2.5 font-mono text-sm font-semibold text-white shadow-[0_0_20px_rgba(0,212,255,0.3)]">Simpan</button>
                            </div>
                        </form>
                    </div>
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

    {{-- PDF Preview Modal --}}
    <div
        x-show="pdfPreviewOpen"
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center p-4"
        style="background: rgba(0,0,0,0.85); backdrop-filter: blur(4px);"
        @keydown.escape.window="closePdfPreview()"
    >
        <div class="relative w-full max-w-5xl h-[90vh] bg-gradient-to-b from-slate-900 to-slate-950 border border-cyan-500/30 rounded-2xl shadow-[0_0_60px_rgba(0,212,255,0.3)] overflow-hidden flex flex-col">
            {{-- Header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-cyan-500/30 bg-slate-900/80">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-cyan-500/20 border border-cyan-500/30 flex items-center justify-center">
                        <svg class="w-5 h-5 text-cyan-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            <path d="M9 13h6M9 17h4"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-mono text-sm font-semibold text-cyan-400">Preview Laporan</h3>
                        <p class="text-xs text-slate-400" x-text="pdfPreviewTitle"></p>
                    </div>
                </div>
                <button
                    type="button"
                    @click="closePdfPreview()"
                    class="rounded-full bg-slate-700/50 p-2 text-slate-400 hover:text-white hover:bg-slate-600 transition"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            {{-- PDF Content --}}
            <div class="flex-1 overflow-hidden bg-slate-800/50">
                <iframe
                    x-show="pdfPreviewUrl"
                    :src="pdfPreviewUrl"
                    class="w-full h-full border-0"
                    title="PDF Preview"
                ></iframe>
                <div x-show="!pdfPreviewUrl" class="flex items-center justify-center h-full">
                    <div class="text-center">
                        <svg class="w-16 h-16 mx-auto text-slate-600 mb-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                        <p class="text-slate-400">Memuat PDF...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Replace File Modal --}}
    <div
        x-show="replaceModalOpen"
        x-cloak
        class="fixed inset-0 z-[90] flex items-center justify-center p-4"
        style="background: rgba(0,0,0,0.85); backdrop-filter: blur(4px);"
        @keydown.escape.window="closeReplaceModal()"
    >
        <div class="relative w-full max-w-lg rounded-2xl border border-amber-500/30 bg-gradient-to-b from-slate-900 to-slate-950 shadow-[0_0_60px_rgba(245,158,11,0.2)] overflow-hidden">
            {{-- Glow effect --}}
            <div class="absolute -inset-0.5 rounded-2xl bg-gradient-to-r from-amber-500/10 via-transparent to-amber-500/10 pointer-events-none"></div>

            {{-- Header --}}
            <div class="relative flex items-center justify-between px-6 py-5 border-b border-amber-500/20">
                <div class="flex items-center gap-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-500/20 border border-amber-500/30">
                        <svg class="h-6 w-6 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-mono text-lg font-bold text-amber-400 uppercase tracking-wider">Ganti File PDF</h3>
                        <p class="text-xs text-slate-400 mt-1" x-text="'Bulan: ' + replaceBulan"></p>
                    </div>
                </div>
                <button
                    type="button"
                    @click="closeReplaceModal()"
                    class="rounded-full bg-slate-700/50 p-2 text-slate-400 hover:text-white hover:bg-slate-600 transition"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Content --}}
            <div class="relative p-6">
                <form
                    method="POST"
                    :action="'/laporan-kinerja/bulanan/' + replaceReportId + '/replace'"
                    enctype="multipart/form-data"
                    @submit="return confirm('Yakin ingin mengganti file laporan? Status akan berubah menjadi DIKIRIM.')"
                >
                    @csrf

                    <div class="mb-5">
                        <label class="block font-mono text-xs font-semibold uppercase tracking-wider text-amber-400/70 mb-3">
                            Pilih File PDF Baru
                        </label>
                        <div class="relative">
                            <input
                                type="file"
                                name="file"
                                id="replaceFile"
                                accept=".pdf,application/pdf"
                                @change="handleFileSelect($event)"
                                class="hidden"
                            >
                            <label
                                for="replaceFile"
                                class="flex flex-col items-center justify-center w-full h-40 rounded-xl border-2 border-dashed border-amber-500/30 bg-slate-800/50 cursor-pointer transition-all hover:border-amber-400/50 hover:bg-slate-800"
                            >
                                <template x-if="!selectedFile">
                                    <div class="text-center">
                                        <svg class="w-12 h-12 mx-auto text-amber-500/50 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                        </svg>
                                        <p class="font-mono text-sm text-amber-400/70">Klik untuk pilih file PDF</p>
                                        <p class="font-mono text-xs text-slate-500 mt-1">Maksimal 10MB</p>
                                    </div>
                                </template>
                                <template x-if="selectedFile">
                                    <div class="text-center">
                                        <svg class="w-12 h-12 mx-auto text-emerald-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <p class="font-mono text-sm text-emerald-400" x-text="selectedFile.name"></p>
                                        <p class="font-mono text-xs text-slate-500 mt-1" x-text="(selectedFile.size / 1024 / 1024).toFixed(2) + ' MB'"></p>
                                        <p class="font-mono text-xs text-amber-400/70 mt-2">Klik untuk ganti file</p>
                                    </div>
                                </template>
                            </label>
                        </div>
                    </div>

                    <div class="bg-amber-500/10 border border-amber-500/20 rounded-xl p-4 mb-5">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-amber-400 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div>
                                <p class="font-mono text-xs font-semibold text-amber-400 uppercase tracking-wider">Informasi</p>
                                <ul class="mt-2 space-y-1.5 font-mono text-xs text-slate-400">
                                    <li class="flex items-center gap-2">
                                        <svg class="w-3 h-3 text-amber-500/70" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                        Hanya file PDF yang diperbolehkan
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <svg class="w-3 h-3 text-amber-500/70" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                        Ukuran maksimal 10MB
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <svg class="w-3 h-3 text-amber-500/70" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                        Status akan berubah menjadi DIKIRIM
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3">
                        <button
                            type="button"
                            @click="closeReplaceModal()"
                            class="rounded-xl border border-slate-600/50 bg-slate-800/50 px-5 py-2.5 font-mono text-sm text-slate-300 transition hover:bg-slate-700/50 hover:border-slate-500"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            :disabled="!selectedFile"
                            class="rounded-xl bg-gradient-to-r from-amber-600 to-amber-500 px-6 py-2.5 font-mono text-sm font-bold uppercase tracking-wider text-white shadow-[0_0_20px_rgba(245,158,11,0.3)] transition-all hover:shadow-[0_0_30px_rgba(245,158,11,0.5)] disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:shadow-none"
                        >
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                                Ganti File
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Upload Laporan Modal --}}
    <div
        x-show="uploadModalOpen"
        x-cloak
        class="fixed inset-0 z-[90] flex items-center justify-center p-4"
        style="background: rgba(0,0,0,0.85); backdrop-filter: blur(4px);"
        @keydown.escape.window="closeUploadModal()"
    >
        <div class="relative w-full max-w-lg rounded-2xl border border-cyan-500/30 bg-gradient-to-b from-slate-900 to-slate-950 shadow-[0_0_60px_rgba(0,212,255,0.2)] overflow-hidden">
            {{-- Glow effect --}}
            <div class="absolute -inset-0.5 rounded-2xl bg-gradient-to-r from-cyan-500/10 via-transparent to-cyan-500/10 pointer-events-none"></div>

            {{-- Header --}}
            <div class="relative flex items-center justify-between px-6 py-5 border-b border-cyan-500/20">
                <div class="flex items-center gap-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-cyan-500/20 border border-cyan-500/30">
                        <svg class="h-6 w-6 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-mono text-lg font-bold text-cyan-400 uppercase tracking-wider">Upload Laporan</h3>
                        <p class="text-xs text-slate-400 mt-1">Upload file PDF laporan kinerja bulanan</p>
                    </div>
                </div>
                <button
                    type="button"
                    @click="closeUploadModal()"
                    class="rounded-full bg-slate-700/50 p-2 text-slate-400 hover:text-white hover:bg-slate-600 transition"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Content --}}
            <div class="relative p-6">
                <form
                    method="POST"
                    action="{{ route('laporan-kinerja.upload') }}"
                    enctype="multipart/form-data"
                    @submit="return confirm('Yakin ingin mengupload laporan ini?')"
                >
                    @csrf

                    <div class="mb-5">
                        <label class="block font-mono text-xs font-semibold uppercase tracking-wider text-cyan-400/70 mb-3">
                            Pilih Bulan & Tahun
                        </label>
                        <x-ui.cyber-monthpicker
                            name="bulan"
                            :value="date('Y-m')"
                            placeholder="Pilih bulan"
                        />
                    </div>

                    <div class="mb-5">
                        <label class="block font-mono text-xs font-semibold uppercase tracking-wider text-cyan-400/70 mb-3">
                            Pilih File PDF
                        </label>
                        <div class="relative">
                            <input
                                type="file"
                                name="file"
                                id="uploadFile"
                                accept=".pdf,application/pdf"
                                @change="handleUploadFileSelect($event)"
                                class="hidden"
                            >
                            <label
                                for="uploadFile"
                                class="flex flex-col items-center justify-center w-full h-40 rounded-xl border-2 border-dashed border-cyan-500/30 bg-slate-800/50 cursor-pointer transition-all hover:border-cyan-400/50 hover:bg-slate-800"
                            >
                                <template x-if="!selectedUploadFile">
                                    <div class="text-center">
                                        <svg class="w-12 h-12 mx-auto text-cyan-500/50 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                        </svg>
                                        <p class="font-mono text-sm text-cyan-400/70">Klik untuk pilih file PDF</p>
                                        <p class="font-mono text-xs text-slate-500 mt-1">Maksimal 10MB</p>
                                    </div>
                                </template>
                                <template x-if="selectedUploadFile">
                                    <div class="text-center">
                                        <svg class="w-12 h-12 mx-auto text-emerald-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <p class="font-mono text-sm text-emerald-400" x-text="selectedUploadFile.name"></p>
                                        <p class="font-mono text-xs text-slate-500 mt-1" x-text="(selectedUploadFile.size / 1024 / 1024).toFixed(2) + ' MB'"></p>
                                        <p class="font-mono text-xs text-cyan-400/70 mt-2">Klik untuk ganti file</p>
                                    </div>
                                </template>
                            </label>
                        </div>
                    </div>

                    <div class="bg-cyan-500/10 border border-cyan-500/20 rounded-xl p-4 mb-5">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-cyan-400 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div>
                                <p class="font-mono text-xs font-semibold text-cyan-400 uppercase tracking-wider">Informasi</p>
                                <ul class="mt-2 space-y-1.5 font-mono text-xs text-slate-400">
                                    <li class="flex items-center gap-2">
                                        <svg class="w-3 h-3 text-cyan-500/70" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                        Hanya file PDF yang diperbolehkan
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <svg class="w-3 h-3 text-cyan-500/70" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                        Ukuran maksimal 10MB
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <svg class="w-3 h-3 text-cyan-500/70" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                        Laporan yang sudah DISETUJUI tidak bisa diupload ulang
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3">
                        <button
                            type="button"
                            @click="closeUploadModal()"
                            class="rounded-xl border border-slate-600/50 bg-slate-800/50 px-5 py-2.5 font-mono text-sm text-slate-300 transition hover:bg-slate-700/50 hover:border-slate-500"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            :disabled="!selectedUploadFile"
                            class="rounded-xl bg-gradient-to-r from-cyan-600 to-cyan-500 px-6 py-2.5 font-mono text-sm font-bold uppercase tracking-wider text-white shadow-[0_0_20px_rgba(0,212,255,0.3)] transition-all hover:shadow-[0_0_30px_rgba(0,212,255,0.5)] disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:shadow-none"
                        >
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                </svg>
                                Upload
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
</x-layouts.app>
