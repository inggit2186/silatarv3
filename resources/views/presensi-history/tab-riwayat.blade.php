@if($presensi->count() > 0)
    <!-- Statistik -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="neo-stat-card neo-stat-card-total">
            <div class="neo-stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <p class="neo-stat-value">{{ (int) $stats->hari_kerja_efektif }}</p>
            <p class="neo-stat-label">Hari Kerja Efektif</p>
        </div>

        <div class="neo-stat-card neo-stat-card-success">
            <div class="neo-stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707"/><circle cx="12" cy="12" r="4"/>
                </svg>
            </div>
            <p class="neo-stat-value">{{ (int) $stats->total_masuk_kerja }}</p>
            <p class="neo-stat-label">Presensi Masuk</p>
        </div>

        <div class="neo-stat-card">
            <div class="neo-stat-icon" style="background: rgba(59, 130, 246, 0.12);">
                <svg style="stroke: #2563eb;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                </svg>
            </div>
            <p class="neo-stat-value" style="color: #2563eb;">{{ (int) $stats->total_pulang_kerja }}</p>
            <p class="neo-stat-label">Presensi Pulang</p>
        </div>

        <div class="neo-stat-card neo-stat-card-danger">
            <div class="neo-stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-2.694-.833-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
            </div>
            <p class="neo-stat-value">{{ (int) $stats->persentase_kehadiran }}%</p>
            <p class="neo-stat-label">Tingkat Kehadiran</p>
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
                <span class="w-8 h-8 rounded-xl bg-slate-500/15 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-slate-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h4l2 5 4-10 2 5h2"/>
                    </svg>
                </span>
                <div>
                    <p style="font-family: var(--font-mono);" class="text-[0.6rem] uppercase tracking-[0.12em] text-[var(--ash)]">Libur Sesuai Jadwal</p>
                    <p style="font-family: var(--font-display);" class="text-sm font-semibold text-[var(--ink)]">{{ (int) $jadwal['hari_libur_jadwal'] }} hari</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-8 h-8 rounded-xl bg-red-500/15 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-red-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-2.694-.833-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                </span>
                <div>
                    <p style="font-family: var(--font-mono);" class="text-[0.6rem] uppercase tracking-[0.12em] text-[var(--ash)]">Tanpa Absensi (Hari Kerja)</p>
                    <p style="font-family: var(--font-display);" class="text-sm font-semibold text-[var(--ink)]">{{ (int) $stats->total_tanpa_absensi }} hari</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Daftar riwayat -->
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
        <h3 style="font-family: var(--font-display);" class="text-lg font-semibold text-[var(--ink)] mb-2">Belum ada riwayat</h3>
        <p class="text-sm text-[var(--ink-soft)]">Belum ada data presensi pada periode ini. Coba pilih bulan atau tahun lain.</p>
    </div>
@endif
