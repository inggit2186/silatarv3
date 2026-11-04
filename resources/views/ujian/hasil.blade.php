<x-layouts.app title="Hasil Ujian - SILATAR">

<main class="neo-mirai">

    <x-ui.page-hero badge="Hasil Ujian" title="{{ $ujian->judul }}">
        <p class="text-sm text-[var(--ink-soft)] mt-2">{{ $ujian->kode }} &middot; {{ $ujian->durasi_menit }} menit</p>
    </x-ui.page-hero>

    <div class="page-content">
        <div class="content-centered">

            <!-- Ringkasan -->
            <div class="neo-card mb-6">
                <div class="neo-card-body flex flex-wrap items-center justify-between gap-6">
                    <div class="text-center">
                        <p class="neo-stat-label mb-1">Nilai Akhir</p>
                        <p class="font-display text-5xl font-bold text-[var(--gold)]">
                            {{ number_format($sesi->nilai ?? $data['nilai'], 2) }}
                        </p>
                        <div class="mt-3">
                            @if($sesi->nilai !== null && $sesi->nilai >= 70)
                                <span class="neo-badge neo-badge-success"><span class="neo-badge-dot"></span>Lulus</span>
                            @elseif($sesi->nilai !== null)
                                <span class="neo-badge neo-badge-warning"><span class="neo-badge-dot"></span>Belum Lulus</span>
                            @else
                                <span class="neo-badge"><span class="neo-badge-dot"></span>Belum Dinilai</span>
                            @endif
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-4 justify-center">
                        <div class="neo-stat-card min-w-[8rem]">
                            <div class="neo-stat-value text-2xl">{{ $data['benar'] }}</div>
                            <div class="neo-stat-label">Benar</div>
                        </div>
                        <div class="neo-stat-card min-w-[8rem]">
                            <div class="neo-stat-value text-2xl">{{ $data['salah'] }}</div>
                            <div class="neo-stat-label">Salah</div>
                        </div>
                        <div class="neo-stat-card min-w-[8rem]">
                            <div class="neo-stat-value text-2xl">{{ $data['skip'] }}</div>
                            <div class="neo-stat-label">Tidak Dijawab</div>
                        </div>
                        <div class="neo-stat-card min-w-[8rem]">
                            <div class="neo-stat-value text-2xl">{{ number_format($data['totalSkor'], 0) }}</div>
                            <div class="neo-stat-label">Skor / {{ number_format($data['totalPoin'], 0) }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pembahasan -->
            <h2 class="font-display text-xl font-semibold text-[var(--ink)] mb-4">Pembahasan</h2>

            <div class="grid gap-5">
                @foreach($data['rincian'] as $r)
                    <div class="neo-card">
                        <div class="neo-card-header">
                            <div class="flex items-center justify-between gap-3 flex-wrap">
                                <h3 class="neo-card-title">Soal {{ $r['nomor'] }}</h3>
                                <div class="flex items-center gap-2">
                                    @if($r['tipe'] === 'pilihan_ganda')
                                        <span class="neo-badge neo-badge-gold">Pilihan Ganda</span>
                                        @if($r['status'] === 'benar')
                                            <span class="neo-badge neo-badge-success"><span class="neo-badge-dot"></span>Benar</span>
                                        @elseif($r['status'] === 'salah')
                                            <span class="neo-badge text-red-600"><span class="neo-badge-dot bg-red-600"></span>Salah</span>
                                        @else
                                            <span class="neo-badge"><span class="neo-badge-dot"></span>Tidak Dijawab</span>
                                        @endif
                                    @else
                                        <span class="neo-badge neo-badge-primary">Esai</span>
                                        @if($r['dinilai'])
                                            <span class="neo-badge neo-badge-success"><span class="neo-badge-dot"></span> Dinilai</span>
                                        @else
                                            <span class="neo-badge neo-badge-warning"><span class="neo-badge-dot"></span>Menunggu Penilaian</span>
                                        @endif
                                    @endif
                                    <span class="neo-badge">{{ $r['skorSoal'] }} / {{ $r['poin'] }} poin</span>
                                </div>
                            </div>
                        </div>

                        <div class="neo-card-body">
                            <p class="font-medium text-[var(--ink)] leading-relaxed mb-4">{{ $r['pertanyaan'] }}</p>

                            @if($r['petunjuk'])
                                <p class="text-sm text-[var(--ink-soft)] mb-4">{{ $r['petunjuk'] }}</p>
                            @endif

                            @if($r['tipe'] === 'pilihan_ganda')
                                <div class="grid gap-2">
                                    <div class="flex items-start gap-3 p-3 rounded-xl border border-[var(--line)] bg-[var(--paper)]">
                                        <span class="shrink-0 w-7 h-7 rounded-full border border-[var(--line)] flex items-center justify-center font-mono text-xs font-bold text-[var(--ink-soft)]">
                                            {{ $r['dipilihLabel'] ?? '-' }}
                                        </span>
                                        <div class="text-sm">
                                            <p class="text-[var(--ash)] uppercase text-[0.65rem] font-mono tracking-wider mb-0.5">Jawaban Anda</p>
                                            <p class="text-[var(--ink)]">{{ $r['dipilihTeks'] ?? 'Tidak dijawab' }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-start gap-3 p-3 rounded-xl border border-[var(--gold)] bg-[var(--paper-soft)]">
                                        <span class="shrink-0 w-7 h-7 rounded-full border border-[var(--gold)] bg-[var(--gold)] flex items-center justify-center font-mono text-xs font-bold text-[var(--night)]">
                                            {{ $r['kunciLabel'] ?? '-' }}
                                        </span>
                                        <div class="text-sm">
                                            <p class="text-[var(--gold)] uppercase text-[0.65rem] font-mono tracking-wider mb-0.5">Kunci Jawaban</p>
                                            <p class="text-[var(--ink)]">{{ $r['kunciTeks'] ?? '-' }}</p>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="p-4 rounded-xl border border-[var(--line)] bg-[var(--paper)]">
                                    <p class="text-[var(--ash)] uppercase text-[0.65rem] font-mono tracking-wider mb-1">Jawaban Anda</p>
                                    <p class="text-sm text-[var(--ink)] whitespace-pre-line">{{ $r['esai'] ?: 'Tidak dijawab' }}</p>
                                </div>

                                @if($r['file_url'])
                                    <a href="{{ $r['file_url'] }}" class="inline-flex items-center gap-2 text-sm text-[var(--gold)] hover:underline mt-3">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M21.44 11.05l-9.19 9.19a6 6 0 01-8.49-8.49l9.19-9.19a4 4 0 015.66 5.66l-9.2 9.19a2 2 0 01-2.83-2.83l8.49-8.48"/>
                                        </svg>
                                        {{ $r['file_nama'] }}
                                    </a>
                                @endif
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8 flex justify-center">
                <a href="{{ route('ujian.index') }}" class="neo-btn neo-btn-secondary">Kembali ke Daftar Ujian</a>
            </div>

        </div>
    </div>
</main>

</x-layouts.app>
