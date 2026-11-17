@if($presensi->count() > 0)
    <!-- Statistik -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="neo-stat-card neo-stat-card-danger">
            <div class="neo-stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-2.694-.833-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
            </div>
            <p class="neo-stat-value">{{ (int) $stats->total_sistem_error }}</p>
            <p class="neo-stat-label">Sistem Error</p>
        </div>

        <div class="neo-stat-card neo-stat-card-warning">
            <div class="neo-stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7H4a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2z"/><path stroke-linecap="round" stroke-linejoin="round" d="M16 21V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v16"/>
                </svg>
            </div>
            <p class="neo-stat-value">{{ (int) $stats->total_tugas_luar }}</p>
            <p class="neo-stat-label">Tugas Luar</p>
        </div>

        <div class="neo-stat-card">
            <div class="neo-stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.09 9a3 3 0 015.83 1c0 2-3 3-3 3"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 17h.01"/><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <p class="neo-stat-value">{{ (int) $stats->total_lupa }}</p>
            <p class="neo-stat-label">Lupa Presensi</p>
        </div>

        <div class="neo-stat-card neo-stat-card-total">
            <div class="neo-stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <p class="neo-stat-value">{{ (int) $stats->error_di_libur }}</p>
            <p class="neo-stat-label">Error di Hari Libur</p>
        </div>
    </div>

    <!-- Ringkasan hari kerja & hari libur -->
    <div class="neo-card mb-6 px-4 py-3.5">
        <div class="flex flex-wrap items-center gap-x-6 gap-y-2">
            <div class="flex items-center gap-2">
                <span class="w-8 h-8 rounded-xl bg-[var(--gold)]/15 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-[var(--gold)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </span>
                <div>
                    <p style="font-family: var(--font-mono);" class="text-[0.6rem] uppercase tracking-[0.12em] text-[var(--ash)]">Jadwal Kerja</p>
                    <p style="font-family: var(--font-display);" class="text-sm font-semibold text-[var(--ink)]">
                        @if($jadwal['hari_kerja'])
                            {{ substr($jadwal['hari_kerja']->masuk ?? '--:--', 0, 5) }} &ndash; {{ substr($jadwal['hari_kerja']->biasa ?? '--:--', 0, 5) }}
                        @else
                            Belum diatur
                        @endif
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-8 h-8 rounded-xl bg-emerald-500/15 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </span>
                <div>
                    <p style="font-family: var(--font-mono);" class="text-[0.6rem] uppercase tracking-[0.12em] text-[var(--ash)]">Hari Kerja Efektif</p>
                    <p style="font-family: var(--font-display);" class="text-sm font-semibold text-[var(--ink)]">{{ (int) $stats->hari_kerja_efektif }} hari</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-8 h-8 rounded-xl bg-blue-500/15 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </span>
                <div>
                    <p style="font-family: var(--font-mono);" class="text-[0.6rem] uppercase tracking-[0.12em] text-[var(--ash)]">Libur Nasional / Cuti</p>
                    <p style="font-family: var(--font-display);" class="text-sm font-semibold text-[var(--ink)]">{{ (int) $jadwal['hari_libur_nasional'] }} hari</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-8 h-8 rounded-xl bg-red-500/15 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-red-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-2.694-.833-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                </span>
                <div>
                    <p style="font-family: var(--font-mono);" class="text-[0.6rem] uppercase tracking-[0.12em] text-[var(--ash)]">Total Laporan Error</p>
                    <p style="font-family: var(--font-display);" class="text-sm font-semibold text-[var(--ink)]">{{ (int) $stats->total_error }} laporan</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Daftar laporan -->
    <div class="space-y-3">
        @foreach($presensi as $item)
            @include('presensi-history._row', ['item' => $item])
        @endforeach
    </div>

    <!-- Pagination -->
    <x-ui.neo-pagination :paginator="$presensi" />
@else
    <!-- Empty State -->
    <div class="neo-card p-12 text-center">
        <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-[var(--paper-deep)] flex items-center justify-center">
            <svg class="w-8 h-8 text-[var(--ash)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <h3 style="font-family: var(--font-display);" class="text-lg font-semibold text-[var(--ink)] mb-2">Belum ada laporan</h3>
        <p class="text-sm text-[var(--ink-soft)]">Anda belum pernah melaporkan presensi error pada periode ini.</p>
    </div>
@endif
