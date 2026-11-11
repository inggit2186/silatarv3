@php
    $carbon = \Carbon\Carbon::parse($item->tanggal)->locale('id');
    $status = (string) ($item->status ?? '');

    $iconHadir = '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>';
    $iconBriefcase = '<path stroke-linecap="round" stroke-linejoin="round" d="M20 7H4a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2z"/><path stroke-linecap="round" stroke-linejoin="round" d="M16 21V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v16"/>';
    $iconCalendar = '<path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>';
    $iconInfo = '<path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>';

    $meta = \App\Support\PresensiStatus::meta($item->status, (bool) ($item->m_absen || $item->p_absen));

    $icon = match (true) {
        str_starts_with($status, 'Cuti'), str_contains($status, 'Libur') => $iconCalendar,
        $meta['tone'] === 'emerald' => $iconHadir,
        $meta['tone'] === 'amber' => $status === 'TERLAMBAT'
            ? '<path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>'
            : $iconBriefcase,
        $meta['tone'] === 'blue' => '<path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>',
        $meta['tone'] === 'orange' => '<path stroke-linecap="round" stroke-linejoin="round" d="M11 17l5-5-5-5m6 10h4"/><path stroke-linecap="round" stroke-linejoin="round" d="M3 12h4"/>',
        $meta['tone'] === 'red' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-2.694-.833-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>',
        $meta['tone'] === 'purple' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9.09 9a3 3 0 015.83 1c0 2-3 3-3 3"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 17h.01"/><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',
        default => $iconInfo,
    };

    $tone = [
        'emerald' => ['chip' => 'bg-emerald-50 text-emerald-700 border-emerald-200', 'bar' => 'bg-emerald-500'],
        'amber' => ['chip' => 'bg-amber-50 text-amber-700 border-amber-200', 'bar' => 'bg-amber-500'],
        'blue' => ['chip' => 'bg-blue-50 text-blue-700 border-blue-200', 'bar' => 'bg-blue-500'],
        'orange' => ['chip' => 'bg-orange-50 text-orange-700 border-orange-200', 'bar' => 'bg-orange-500'],
        'red' => ['chip' => 'bg-red-50 text-red-700 border-red-200', 'bar' => 'bg-red-500'],
        'purple' => ['chip' => 'bg-purple-50 text-purple-700 border-purple-200', 'bar' => 'bg-purple-500'],
        'slate' => ['chip' => 'bg-slate-100 text-slate-600 border-slate-200', 'bar' => 'bg-slate-300'],
    ][$meta['tone']];

    $isError = in_array($item->status, ['SISTEM_ERROR', 'TUGAS_LUAR', 'LUPA_PRESNSI_PUSAKA'], true);

    $masuk = $item->m_absen ? \Carbon\Carbon::parse($item->tanggal.' '.$item->m_absen) : null;
    $pulang = $item->p_absen ? \Carbon\Carbon::parse($item->tanggal.' '.$item->p_absen) : null;
    $durasi = ($masuk && $pulang) ? (int) round($masuk->diffInMinutes($pulang, false)) : null;
@endphp

<article class="neo-card relative overflow-hidden p-4 sm:p-5 flex gap-4 sm:gap-5 items-start transition-all duration-300 hover:border-[var(--gold)] hover:-translate-y-0.5 hover:shadow-lg">
    {{-- Aksen warna status di sisi kiri --}}
    <span class="absolute left-0 top-0 bottom-0 w-1 {{ $tone['bar'] }}" aria-hidden="true"></span>

    {{-- Blok tanggal --}}
    <div class="flex-shrink-0 w-[4.25rem] rounded-2xl border border-[var(--line)] bg-[var(--paper-soft)] flex flex-col items-center justify-center py-2.5">
        <span style="font-family: var(--font-display);" class="text-[1.6rem] font-bold leading-none text-[var(--ink)]">{{ $carbon->format('d') }}</span>
        <span style="font-family: var(--font-mono);" class="text-[0.6rem] font-semibold uppercase tracking-[0.18em] text-[var(--gold)] mt-1">{{ $carbon->isoFormat('MMM') }}</span>
        <span style="font-family: var(--font-mono);" class="text-[0.6rem] text-[var(--ash)] mt-0.5">{{ $carbon->format('Y') }}</span>
    </div>

    {{-- Isi utama --}}
    <div class="flex-1 min-w-0">
        {{-- Badge status & tanggal --}}
        <div class="flex flex-wrap items-center gap-2 mb-2.5">
            <span style="font-family: var(--font-mono);" class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-1 text-[0.65rem] font-semibold uppercase tracking-[0.06em] {{ $tone['chip'] }}">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">{!! $icon !!}</svg>
                {{ $meta['label'] }}
            </span>
            <span style="font-family: var(--font-display);" class="text-sm font-semibold text-[var(--ink)]">{{ $carbon->isoFormat('dddd, D MMMM Y') }}</span>
        </div>

        {{-- Chip jam masuk / pulang / durasi --}}
        <div class="flex flex-wrap gap-2">
            <span style="font-family: var(--font-mono);" class="inline-flex items-center gap-1.5 rounded-xl border border-[var(--line)] bg-[var(--paper-soft)] px-2.5 py-1.5">
                <svg class="w-3.5 h-3.5 text-emerald-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707"/><circle cx="12" cy="12" r="4"/></svg>
                <span class="text-[0.65rem] uppercase tracking-[0.08em] text-[var(--ink-soft)]">Masuk</span>
                <span class="text-xs font-bold text-[var(--ink)]">{{ $item->m_absen ? substr($item->m_absen, 0, 5) : '--:--' }}</span>
            </span>

            <span style="font-family: var(--font-mono);" class="inline-flex items-center gap-1.5 rounded-xl border border-[var(--line)] bg-[var(--paper-soft)] px-2.5 py-1.5">
                <svg class="w-3.5 h-3.5 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                <span class="text-[0.65rem] uppercase tracking-[0.08em] text-[var(--ink-soft)]">Pulang</span>
                <span class="text-xs font-bold text-[var(--ink)]">{{ $item->p_absen ? substr($item->p_absen, 0, 5) : '--:--' }}</span>
            </span>

            @if($durasi !== null)
                <span style="font-family: var(--font-mono);" class="inline-flex items-center gap-1.5 rounded-xl border border-[var(--line)] bg-[var(--paper-soft)] px-2.5 py-1.5">
                    <svg class="w-3.5 h-3.5 text-[var(--gold)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="text-[0.65rem] uppercase tracking-[0.08em] text-[var(--ink-soft)]">Durasi</span>
                    <span class="text-xs font-bold text-[var(--ink)]">{{ intdiv($durasi, 60) }}j {{ $durasi % 60 }}m</span>
                </span>
            @endif

            @if($item->error_masuk_taken_at)
                <span style="font-family: var(--font-mono);" class="inline-flex items-center gap-1.5 rounded-xl border border-red-200 bg-red-50 px-2.5 py-1.5">
                    <svg class="w-3.5 h-3.5 text-red-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-2.694-.833-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                    <span class="text-[0.65rem] uppercase tracking-[0.08em] text-red-600">Diambil</span>
                    <span class="text-xs font-bold text-red-700">{{ substr($item->error_masuk_taken_at, 0, 5) }}</span>
                </span>
            @endif

            @if($item->error_pulang_taken_at)
                <span style="font-family: var(--font-mono);" class="inline-flex items-center gap-1.5 rounded-xl border border-red-200 bg-red-50 px-2.5 py-1.5">
                    <svg class="w-3.5 h-3.5 text-red-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-2.694-.833-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                    <span class="text-[0.65rem] uppercase tracking-[0.08em] text-red-600">Diambil</span>
                    <span class="text-xs font-bold text-red-700">{{ substr($item->error_pulang_taken_at, 0, 5) }}</span>
                </span>
            @endif
        </div>

        {{-- Keterangan & lokasi --}}
        @if($item->keterangan)
            <p style="font-family: var(--font-mono);" class="mt-2.5 flex items-start gap-1.5 text-[0.7rem] leading-relaxed text-[var(--ink-soft)]">
                <svg class="w-3.5 h-3.5 mt-0.5 flex-shrink-0 text-[var(--ash)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 10h8M8 14h8m-4-8a6 6 0 100 12 6 6 0 000-12z"/></svg>
                <span class="line-clamp-2">{{ $item->keterangan }}</span>
            </p>
        @endif

        @if($item->m_latitude && $item->m_longitude)
            <a href="https://www.google.com/maps?q={{ $item->m_latitude }},{{ $item->m_longitude }}" target="_blank" rel="noopener" style="font-family: var(--font-mono);" class="mt-2 inline-flex items-center gap-1.5 text-[0.65rem] text-[var(--gold)] hover:underline">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                {{ number_format($item->m_latitude, 6) }}, {{ number_format($item->m_longitude, 6) }}
            </a>
        @endif
    </div>

    {{-- Foto & aksi --}}
    <div class="flex flex-col items-center gap-3 flex-shrink-0">
        <div class="relative w-16 h-16 sm:w-20 sm:h-20">
            @if($item->m_location)
                <img src="{{ asset('storage/' . $item->m_location) }}" alt="Foto Presensi" loading="lazy"
                     class="w-full h-full rounded-2xl object-cover border border-[var(--line)]"
                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                <div class="hidden w-full h-full rounded-2xl bg-[var(--paper-deep)] items-center justify-center">
                    <svg class="w-7 h-7 text-[var(--ash)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            @else
                <div class="w-full h-full rounded-2xl bg-[var(--paper-deep)] flex items-center justify-center">
                    <svg class="w-7 h-7 text-[var(--ash)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            @endif
        </div>

        @if($isError)
            <a href="{{ route('presensi-error.surat', ['id' => $item->id, 'jenis' => $item->m_absen ? 'masuk' : 'pulang']) }}"
               target="_blank" rel="noopener"
               style="font-family: var(--font-mono);"
               class="inline-flex items-center gap-1.5 rounded-xl border border-[var(--line)] bg-[var(--paper)] px-3 py-2 text-[0.65rem] font-semibold uppercase tracking-[0.06em] text-[var(--ink)] transition-colors hover:border-[var(--gold)] hover:text-[var(--gold)]">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Surat
            </a>
        @endif
    </div>
</article>
