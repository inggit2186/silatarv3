<x-layouts.app title="Ujian Pegawai - SILATAR">

<main class="neo-mirai">

    <x-ui.page-hero badge="Sistem Ujian" title="Ujian Pegawai">
        <p class="text-sm text-[var(--ink-soft)] mt-2">Kerjakan ujian yang telah ditugaskan kepada Anda.</p>
    </x-ui.page-hero>

    <div class="page-content">
        <div class="content-centered">

            <!-- Statistik -->
            <div class="neo-grid neo-grid-4 mb-8">
                <div class="neo-stat-card">
                    <div class="neo-stat-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div class="neo-stat-value">{{ $stats['total'] }}</div>
                    <div class="neo-stat-label">Total Ujian</div>
                </div>
                <div class="neo-stat-card neo-stat-card-warning">
                    <div class="neo-stat-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="neo-stat-value">{{ $stats['berjalan'] }}</div>
                    <div class="neo-stat-label">Sedang Berjalan</div>
                </div>
                <div class="neo-stat-card">
                    <div class="neo-stat-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <div class="neo-stat-value">{{ $stats['belum_mulai'] }}</div>
                    <div class="neo-stat-label">Belum Dimulai</div>
                </div>
                <div class="neo-stat-card neo-stat-card-success">
                    <div class="neo-stat-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="neo-stat-value">{{ $stats['selesai'] }}</div>
                    <div class="neo-stat-label">Selesai</div>
                </div>
            </div>

            @if($peserta->isEmpty())
                <div class="neo-card">
                    <div class="neo-empty">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p class="neo-empty-title">Belum ada ujian</p>
                        <p class="neo-empty-text">Anda belum ditugaskan mengikuti ujian apa pun. Hubungi administrator untuk informasi lebih lanjut.</p>
                    </div>
                </div>
            @else
                <div class="grid gap-5 md:grid-cols-2">
                    @foreach($peserta as $item)
                        @php
                            $ujian = $item->ujian;
                            $sisa = $item->ends_at ? max(0, now()->diffInSeconds($item->ends_at, false)) : 0;
                            $terbit = $ujian && $ujian->status === 'terbit';
                            $sudahMulai = $ujian && $ujian->mulai_at && now()->gte($ujian->mulai_at);
                            $tersedia = $terbit && $sudahMulai;
                            $label = ['belum_mulai' => 'Belum Dimulai', 'berjalan' => 'Sedang Berjalan', 'selesai' => 'Selesai'][$item->status] ?? $item->status;
                        @endphp

                        <div class="neo-card flex flex-col">
                            <div class="flex items-start justify-between gap-3 mb-3">
                                <div>
                                    <span class="neo-badge neo-badge-gold mb-2">
                                        <span class="neo-badge-dot"></span>
                                        {{ $label }}
                                    </span>
                                    <h3 class="neo-card-title">{{ $ujian->judul ?? 'Ujian' }}</h3>
                                </div>
                                <span class="neo-badge">{{ $ujian->kode ?? '-' }}</span>
                            </div>

                            @if($ujian->deskripsi)
                                <p class="text-sm text-[var(--ink-soft)] mb-4">{{ \Illuminate\Support\Str::limit($ujian->deskripsi, 140) }}</p>
                            @endif

                            <div class="flex flex-wrap gap-4 text-xs text-[var(--ink-soft)] mb-5">
                                <span class="inline-flex items-center gap-1.5">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    {{ $ujian->soal_count ?? 0 }} soal
                                </span>
                                <span class="inline-flex items-center gap-1.5">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ $ujian->durasi_menit ?? 0 }} menit
                                </span>
                                @if($item->nilai !== null)
                                    <span class="inline-flex items-center gap-1.5 font-mono text-[var(--gold)]">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        {{ number_format($item->nilai, 2) }}
                                    </span>
                                @endif
                            </div>

                            <div class="mt-auto">
                                @if($item->status === 'selesai')
                                    <a href="{{ route('ujian.hasil', $ujian->id) }}" class="neo-btn neo-btn-secondary w-full justify-center">
                                        Lihat Hasil
                                    </a>
                                @elseif($item->status === 'berjalan')
                                    <a href="{{ route('ujian.kerjakan', $ujian->id) }}" class="neo-btn w-full justify-center">
                                        Lanjutkan Ujian
                                    </a>
                                    @if($sisa > 0)
                                        <p class="text-center text-xs text-[var(--ink-soft)] mt-2 font-mono">
                                            Sisa waktu {{ gmdate('H:i:s', $sisa) }}
                                        </p>
                                    @endif
                                @elseif($tersedia)
                                    <a href="{{ route('ujian.kerjakan', $ujian->id) }}" class="neo-btn w-full justify-center">
                                        Mulai Ujian
                                    </a>
                                @else
                                    <button type="button" class="neo-btn w-full justify-center opacity-50 cursor-not-allowed" disabled>
                                        Belum Tersedia
                                    </button>
                                    <p class="text-center text-xs text-[var(--ink-soft)] mt-2">
                                        {{ $ujian->status === 'draft' ? 'Ujian belum diterbitkan.' : 'Ujian belum dimulai.' }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
</main>

</x-layouts.app>
