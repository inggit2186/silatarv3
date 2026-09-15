<x-layouts.app title="Laporan Presensi - SILATAR">

    <main class="neo-mirai min-h-screen bg-[var(--paper)]">
        <section class="page-content px-4 py-6 lg:px-6">
            <div class="max-w-4xl mx-auto">

                <!-- Header -->
                <div class="mb-8">
                    <h1 class="text-2xl font-bold text-[var(--ink)]">Laporan Presensi</h1>
                    <p class="text-[var(--ink-soft)] mt-1">Riwayat pelaporan presensi error Anda</p>
                </div>

                <!-- Filter -->
                <div class="neo-card p-4 mb-6">
                    <form method="GET" action="{{ route('presensi-history') }}" class="flex flex-wrap items-end gap-3">
                        <div class="flex-1 min-w-[140px]">
                            <label class="block text-sm font-medium text-[var(--ink)] mb-1">Bulan</label>
                            <select name="month" class="w-full px-3 py-2 rounded-xl border border-[var(--line)] bg-[var(--paper)] text-[var(--ink)] text-sm focus:outline-none focus:border-[var(--gold)]">
                                @foreach(range(1, 12) as $m)
                                    <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($m)->locale('id')->isoFormat('MMMM') }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex-1 min-w-[100px]">
                            <label class="block text-sm font-medium text-[var(--ink)] mb-1">Tahun</label>
                            <select name="year" class="w-full px-3 py-2 rounded-xl border border-[var(--line)] bg-[var(--paper)] text-[var(--ink)] text-sm focus:outline-none focus:border-[var(--gold)]">
                                @foreach(range(date('Y') - 2, date('Y') + 1) as $y)
                                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="px-5 py-2 rounded-xl text-sm font-medium text-white transition-colors" style="background: linear-gradient(135deg, var(--gold) 0%, var(--sun-deep) 100%);">
                            Filter
                        </button>
                    </form>
                </div>

                @if($presensi->count() > 0)
                    <!-- Stats -->
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div class="neo-card p-5">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-red-500/10 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-red-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-2.694-.833-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-2xl font-bold text-[var(--ink)]">{{ $presensi->where('status', 'SISTEM_ERROR')->count() }}</p>
                                    <p class="text-xs text-[var(--ink-soft)]">Sistem Error</p>
                                </div>
                            </div>
                        </div>
                        <div class="neo-card p-5">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-amber-500/10 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-amber-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-2xl font-bold text-[var(--ink)]">{{ $presensi->where('status', 'TUGAS_LUAR')->count() }}</p>
                                    <p class="text-xs text-[var(--ink-soft)]">Tugas Luar</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- List -->
                    <div class="space-y-3">
                        @foreach($presensi as $item)
                            <div class="neo-card p-4 flex items-center gap-4">
                                <!-- Foto (kiri) -->
                                <div class="flex-shrink-0">
                                    @if($item->m_location)
                                        <img src="{{ asset('storage/' . $item->m_location) }}" alt="Foto Presensi" class="w-20 h-20 rounded-2xl object-cover border border-[var(--line)]" onerror="this.parentElement.innerHTML='<div class=\'w-20 h-20 rounded-2xl bg-[var(--paper-deep)] flex items-center justify-center\'><svg class=\'w-7 h-7 text-[var(--ash)]\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'currentColor\' stroke-width=\'2\'><path d=\'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z\'/></svg></div>'">
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
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                                            {{ $item->status === 'SISTEM_ERROR' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700' }}">
                                            {{ $item->status === 'SISTEM_ERROR' ? 'Sistem Error' : 'Tugas Luar' }}
                                        </span>
                                        <span class="text-sm font-medium text-[var(--ink)]">{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</span>
                                    </div>

                                    <div class="flex flex-wrap gap-x-5 gap-y-1 text-sm text-[var(--ink)]">
                                        @if($item->m_absen)
                                            <span class="flex items-center gap-1.5">
                                                <svg class="w-3.5 h-3.5 text-emerald-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707"/><circle cx="12" cy="12" r="4"/></svg>
                                                Masuk: {{ $item->m_absen }}
                                            </span>
                                        @endif
                                        @if($item->p_absen)
                                            <span class="flex items-center gap-1.5">
                                                <svg class="w-3.5 h-3.5 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                                                Pulang: {{ $item->p_absen }}
                                            </span>
                                        @endif
                                        @if($item->error_masuk_taken_at)
                                            <span class="text-[var(--ink-soft)]">Diambil: {{ $item->error_masuk_taken_at }}</span>
                                        @endif
                                        @if($item->error_pulang_taken_at)
                                            <span class="text-[var(--ink-soft)]">Diambil: {{ $item->error_pulang_taken_at }}</span>
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

                                <!-- Tombol Surat (kanan) -->
                                <a href="{{ route('presensi-error.surat', ['id' => $item->id, 'jenis' => $item->m_absen ? 'masuk' : 'pulang']) }}"
                                   target="_blank"
                                   class="flex-shrink-0 inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium rounded-xl border border-[var(--line)] text-[var(--ink)] hover:border-[var(--gold)] hover:text-[var(--gold)] transition-colors">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    Surat
                                </a>
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
                        <h3 class="text-lg font-semibold text-[var(--ink)] mb-2">Belum ada laporan</h3>
                        <p class="text-sm text-[var(--ink-soft)]">Anda belum pernah melaporkan presensi error.</p>
                    </div>
                @endif

            </div>
        </section>
    </main>

</x-layouts.app>
