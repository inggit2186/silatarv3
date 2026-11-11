@if($presensi->count() > 0)
    <!-- Statistik -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="neo-stat-card neo-stat-card-total">
            <div class="neo-stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <p class="neo-stat-value">{{ (int) $stats->total }}</p>
            <p class="neo-stat-label">Total Hari</p>
        </div>

        <div class="neo-stat-card neo-stat-card-success">
            <div class="neo-stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707"/><circle cx="12" cy="12" r="4"/>
                </svg>
            </div>
            <p class="neo-stat-value">{{ (int) $stats->total_masuk }}</p>
            <p class="neo-stat-label">Presensi Masuk</p>
        </div>

        <div class="neo-stat-card">
            <div class="neo-stat-icon" style="background: rgba(59, 130, 246, 0.12);">
                <svg style="stroke: #2563eb;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                </svg>
            </div>
            <p class="neo-stat-value" style="color: #2563eb;">{{ (int) $stats->total_pulang }}</p>
            <p class="neo-stat-label">Presensi Pulang</p>
        </div>

        <div class="neo-stat-card neo-stat-card-danger">
            <div class="neo-stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-2.694-.833-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
            </div>
            <p class="neo-stat-value">{{ (int) $stats->total_error }}</p>
            <p class="neo-stat-label">Presensi Error</p>
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
