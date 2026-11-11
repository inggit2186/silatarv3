@php
    $statusMeta = [
        'MASUK' => ['label' => 'Hadir', 'class' => 'bg-emerald-100 text-emerald-700'],
        'TERLAMBAT' => ['label' => 'Terlambat', 'class' => 'bg-amber-100 text-amber-700'],
        'PULANG' => ['label' => 'Sudah Pulang', 'class' => 'bg-blue-100 text-blue-700'],
        'PULANG_CEPAT' => ['label' => 'Pulang Cepat', 'class' => 'bg-orange-100 text-orange-700'],
        'SISTEM_ERROR' => ['label' => 'Sistem Error', 'class' => 'bg-red-100 text-red-700'],
        'TUGAS_LUAR' => ['label' => 'Tugas Luar', 'class' => 'bg-amber-100 text-amber-700'],
        'LUPA_PRESNSI_PUSAKA' => ['label' => 'Lupa Presensi', 'class' => 'bg-purple-100 text-purple-700'],
    ];
@endphp

@if($presensi->count() > 0)
    <!-- Stats -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="neo-card p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-[var(--gold)]/10 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-[var(--gold)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-[var(--ink)]">{{ (int) $stats->total }}</p>
                    <p class="text-xs text-[var(--ink-soft)]">Total Hari</p>
                </div>
            </div>
        </div>
        <div class="neo-card p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-500/10 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-emerald-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707"/><circle cx="12" cy="12" r="4"/>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-[var(--ink)]">{{ (int) $stats->total_masuk }}</p>
                    <p class="text-xs text-[var(--ink-soft)]">Presensi Masuk</p>
                </div>
            </div>
        </div>
        <div class="neo-card p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-500/10 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-[var(--ink)]">{{ (int) $stats->total_pulang }}</p>
                    <p class="text-xs text-[var(--ink-soft)]">Presensi Pulang</p>
                </div>
            </div>
        </div>
        <div class="neo-card p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-red-500/10 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-red-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-2.694-.833-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-[var(--ink)]">{{ (int) $stats->total_error }}</p>
                    <p class="text-xs text-[var(--ink-soft)]">Presensi Error</p>
                </div>
            </div>
        </div>
    </div>

    <!-- List -->
    <div class="space-y-3">
        @foreach($presensi as $item)
            @php
                $meta = $statusMeta[$item->status ?? ''] ?? ($item->m_absen || $item->p_absen
                    ? ['label' => 'Hadir', 'class' => 'bg-emerald-100 text-emerald-700']
                    : ['label' => 'Tanpa Absensi', 'class' => 'bg-slate-100 text-slate-600']);
                $isError = in_array($item->status, ['SISTEM_ERROR', 'TUGAS_LUAR', 'LUPA_PRESNSI_PUSAKA'], true);
                $masuk = $item->m_absen ? \Carbon\Carbon::parse($item->tanggal.' '.$item->m_absen) : null;
                $pulang = $item->p_absen ? \Carbon\Carbon::parse($item->tanggal.' '.$item->p_absen) : null;
                $durasi = ($masuk && $pulang) ? (int) round($masuk->diffInMinutes($pulang, false)) : null;
            @endphp
            <div class="neo-card p-4 flex items-center gap-4">
                <!-- Foto (kiri) -->
                <div class="flex-shrink-0 relative w-20 h-20">
                    @if($item->m_location)
                        <img src="{{ asset('storage/' . $item->m_location) }}" alt="Foto Presensi" class="w-20 h-20 rounded-2xl object-cover border border-[var(--line)]" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="hidden w-20 h-20 rounded-2xl bg-[var(--paper-deep)] items-center justify-center">
                            <svg class="w-7 h-7 text-[var(--ash)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    @else
                        <div class="w-20 h-20 rounded-2xl bg-[var(--paper-deep)] flex items-center justify-center">
                            <svg class="w-7 h-7 text-[var(--ash)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    @endif
                </div>

                <!-- Data & Informasi (tengah) -->
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 mb-1.5">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $meta['class'] }}">{{ $meta['label'] }}</span>
                        <span class="text-sm font-medium text-[var(--ink)]">{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</span>
                    </div>

                    <div class="flex flex-wrap gap-x-5 gap-y-1 text-sm text-[var(--ink)]">
                        <span class="flex items-center gap-1.5 {{ $item->m_absen ? '' : 'text-[var(--ink-soft)]' }}">
                            <svg class="w-3.5 h-3.5 text-emerald-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707"/><circle cx="12" cy="12" r="4"/></svg>
                            Masuk: {{ $item->m_absen ?? '-' }}
                        </span>
                        <span class="flex items-center gap-1.5 {{ $item->p_absen ? '' : 'text-[var(--ink-soft)]' }}">
                            <svg class="w-3.5 h-3.5 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                            Pulang: {{ $item->p_absen ?? '-' }}
                        </span>
                        @if($durasi !== null)
                            <span class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-[var(--ink-soft)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Durasi: {{ intdiv($durasi, 60) }}j {{ $durasi % 60 }}m
                            </span>
                        @endif
                    </div>

                    @if($item->keterangan)
                        <p class="text-xs text-[var(--ink-soft)] mt-1.5 truncate">{{ $item->keterangan }}</p>
                    @endif

                    @if($item->m_latitude && $item->m_longitude)
                        <a href="https://www.google.com/maps?q={{ $item->m_latitude }},{{ $item->m_longitude }}" target="_blank" class="inline-flex items-center gap-1 mt-1.5 text-xs text-[var(--gold)] hover:underline">
                            <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            {{ number_format($item->m_latitude, 6) }}, {{ number_format($item->m_longitude, 6) }}
                        </a>
                    @endif
                </div>

                <!-- Tombol Surat (hanya untuk presensi error) -->
                @if($isError)
                    <a href="{{ route('presensi-error.surat', ['id' => $item->id, 'jenis' => $item->m_absen ? 'masuk' : 'pulang']) }}"
                       target="_blank"
                       class="flex-shrink-0 inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium rounded-xl border border-[var(--line)] text-[var(--ink)] hover:border-[var(--gold)] hover:text-[var(--gold)] transition-colors">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Surat
                    </a>
                @endif
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    @if($presensi->hasPages())
        <div class="mt-6">
            {{ $presensi->links() }}
        </div>
    @endif
@else
    <!-- Empty State -->
    <div class="neo-card p-12 text-center">
        <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-[var(--paper-deep)] flex items-center justify-center">
            <svg class="w-8 h-8 text-[var(--ash)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <h3 class="text-lg font-semibold text-[var(--ink)] mb-2">Belum ada riwayat</h3>
        <p class="text-sm text-[var(--ink-soft)]">Belum ada data presensi pada bulan ini.</p>
    </div>
@endif
