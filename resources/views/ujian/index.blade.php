<x-layouts.app title="Ujian Pegawai - SILATAR">

<main class="neo-mirai">

    <!-- ================= HERO ================= -->
    <section class="hero-page neo-ujian-hero neo-ujian-hero-pad"
        style="background-image: url('/assets/img/template/ujian-bg.webp'); background-size: cover; background-position: center top;">

        <div class="neo-ujian-hero-content">
            <div class="neo-ujian-hero-icon">
                <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>

            <p class="neo-ujian-hero-eyebrow">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2"/>
                    <circle cx="12" cy="12" r="9"/>
                </svg>
                Sistem Ujian Pegawai
            </p>

            <h1 class="neo-ujian-hero-title">Ujian <em>Pegawai</em></h1>

            <p class="neo-ujian-hero-sub">
                Kerjakan ujian yang telah ditugaskan kepada Anda, pantau waktu pengerjaan,
                dan lihat hasil beserta pembahasan soal setelah selesai.
            </p>

            <div class="neo-ujian-hero-actions">
                <a href="{{ url('/') }}" class="neo-hero-cta">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9,22 9,12 15,12 15,22"/></svg>
                    Beranda
                </a>
                <a href="#daftar-ujian" class="neo-hero-cta neo-hero-cta-primary">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 2 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Lihat Daftar Ujian
                </a>
            </div>

            <div class="neo-ujian-hero-stats">
                <span class="neo-ujian-hero-stat">
                    <strong>{{ $stats['total'] }}</strong> Total Tugas
                </span>
                <span class="neo-ujian-hero-stat">
                    <strong>{{ $stats['berjalan'] }}</strong> Sedang Berjalan
                </span>
                <span class="neo-ujian-hero-stat">
                    <strong>{{ $stats['belum_mulai'] }}</strong> Belum Dimulai
                </span>
                <span class="neo-ujian-hero-stat">
                    <strong>{{ $stats['selesai'] }}</strong> Selesai
                </span>
            </div>
        </div>
    </section>

    <div class="section-divider wave-rounded neo-ujian-divider"></div>

    <!-- ================= DAFTAR UJIAN ================= -->
    <div class="page-content neo-ujian-content">
        <div class="content-centered">

            <div class="flex flex-wrap items-center justify-between gap-3 mb-6" id="daftar-ujian" style="scroll-margin-top: 120px;">
                <div>
                    <p class="section-label-gold section-label-sm">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 2 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Tugas Saya
                    </p>
                    <div class="flex items-center gap-3">
                        <h2 class="neo-card-title text-[1.35rem]">Daftar Ujian</h2>
                        <span class="neo-badge neo-badge-gold">
                            <span class="neo-badge-dot"></span>
                            {{ $peserta->count() }} ujian ditugaskan
                        </span>
                    </div>
                </div>
            </div>

            @if($peserta->isEmpty())
                <div class="neo-ujian-empty">
                    <div class="neo-ujian-empty-icon">
                        <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 2 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <p class="neo-ujian-empty-title">Belum ada ujian</p>
                    <p class="neo-ujian-empty-text">
                        Anda belum ditugaskan mengikuti ujian apa pun. Hubungi administrator untuk informasi lebih lanjut.
                    </p>
                </div>
            @else
                <div class="neo-ujian-list">
                    @foreach($peserta as $index => $item)
                        @php
                            $ujian = $item->ujian;
                            $sisa = $item->ends_at ? max(0, now()->diffInSeconds($item->ends_at, false)) : 0;
                            $terbit = $ujian && $ujian->status === 'terbit';
                            $sudahMulai = $ujian && $ujian->mulai_at && now()->gte($ujian->mulai_at);
                            $tersedia = $terbit && $sudahMulai;
                            $label = ['belum_mulai' => 'Belum Dimulai', 'berjalan' => 'Sedang Berjalan', 'selesai' => 'Selesai'][$item->status] ?? $item->status;
                            $kelasStatus = ['belum_mulai' => 'is-belum', 'berjalan' => 'is-berjalan', 'selesai' => 'is-selesai'][$item->status] ?? '';
                        @endphp

                        <article class="neo-ujian-card" style="--ujian-delay: {{ $index * 70 }}ms;">
                            <!-- Cover -->
                            <div class="neo-ujian-card-media">
                                @if($ujian && $ujian->cover_url)
                                    <img src="{{ $ujian->cover_url }}" alt="Cover {{ $ujian->judul }}" class="neo-ujian-card-img" loading="lazy">
                                @else
                                    <div class="neo-ujian-card-placeholder">
                                        <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span>Tanpa Cover</span>
                                    </div>
                                @endif

                                <span class="neo-ujian-card-status {{ $kelasStatus }}">{{ $label }}</span>
                                <span class="neo-ujian-card-code">{{ $ujian->kode ?? '-' }}</span>
                            </div>

                            <!-- Isi -->
                            <div class="neo-ujian-card-body">
                                <h3 class="neo-ujian-card-title">{{ $ujian->judul ?? 'Ujian' }}</h3>

                                @if($ujian->deskripsi)
                                    <p class="neo-ujian-card-desc">{{ \Illuminate\Support\Str::limit($ujian->deskripsi, 130) }}</p>
                                @endif

                                <div class="neo-ujian-card-meta">
                                    <span class="neo-ujian-chip">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 2 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        {{ $ujian->soal_count ?? 0 }} soal
                                    </span>
                                    <span class="neo-ujian-chip">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        {{ $ujian->durasi_menit ?? 0 }} menit
                                    </span>
                                    @if($item->nilai !== null)
                                        <span class="neo-ujian-chip is-score">
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            Nilai {{ number_format($item->nilai, 2) }}
                                        </span>
                                    @endif
                                </div>

                                <div class="neo-ujian-card-actions">
                                    @if($item->status === 'selesai')
                                        <a href="{{ route('ujian.hasil', $ujian->id) }}" class="neo-btn neo-btn-secondary w-full justify-center">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 17v-2m4 2v-2m4 2v-2M3 5h18v14H3z"/></svg>
                                            Lihat Hasil &amp; Pembahasan
                                        </a>
                                    @elseif($item->status === 'berjalan')
                                        <a href="{{ route('ujian.kerjakan', $ujian->id) }}" class="neo-btn w-full justify-center">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                            Lanjutkan Ujian
                                        </a>
                                        @if($sisa > 0)
                                            <p class="neo-ujian-timer"
                                                x-data="{ sisa: {{ $sisa }} }"
                                                x-init="setInterval(() => { if (sisa > 0) sisa-- }, 1000)">
                                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                <span x-text="`Sisa waktu ${String(Math.floor(sisa/3600)).padStart(2,'0')}:${String(Math.floor(sisa%3600/60)).padStart(2,'0')}:${String(sisa%60).padStart(2,'0')}`"></span>
                                            </p>
                                        @endif
                                    @elseif($tersedia)
                                        <a href="{{ route('ujian.kerjakan', $ujian->id) }}" class="neo-btn w-full justify-center">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
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
                        </article>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
</main>

</x-layouts.app>
