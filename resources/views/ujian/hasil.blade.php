<x-layouts.app title="Hasil Ujian - SILATAR">

<main class="neo-mirai">

    @php
        $nilai = (float) ($sesi->nilai ?? $data['nilai']);
        $totalSoal = count($data['rincian']);
        $durasi = ($sesi->started_at && $sesi->submitted_at)
            ? round(abs($sesi->submitted_at->getTimestamp() - $sesi->started_at->getTimestamp()) / 60, 1)
            : null;
    @endphp

    <div class="page-content">
        <div class="content-centered" x-data="hasilUjian(@js($totalSoal))">

            <!-- ============ HERO RINGKASAN ============ -->
            <div class="neo-hasil-hero">
                <div class="neo-hasil-hero-grid">
                    <div class="min-w-0">
                        <p class="neo-hasil-hero-eyebrow">
                            <svg class="inline-block w-3.5 h-3.5 mr-1 -mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Hasil Ujian &middot; {{ $ujian->kode }}
                        </p>
                        <h1 class="neo-hasil-hero-title">{{ $ujian->judul }}</h1>
                        <p class="neo-hasil-hero-sub">
                            Ujian telah dikumpulkan dan dinilai. Berikut rekap nilai, jawaban, dan pembahasan
                            untuk setiap soal yang dikerjakan.
                        </p>

                        <div class="neo-hasil-chips">
                            <span class="neo-hasil-chip">
                                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $ujian->durasi_menit }} menit
                            </span>
                            <span class="neo-hasil-chip">
                                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-6 4h6"/>
                                </svg>
                                {{ $totalSoal }} soal
                            </span>
                            @if($durasi !== null)
                                <span class="neo-hasil-chip">
                                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                    </svg>
                                    {{ rtrim(rtrim(number_format($durasi, 1), '0'), '.') }} menit pengerjaan
                                </span>
                            @endif
                        </div>

                        @if($sesi->nilai !== null && $sesi->nilai >= 70)
                            <span class="neo-hasil-status">
                                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                                Lulus
                            </span>
                        @elseif($sesi->nilai !== null)
                            <span class="neo-hasil-status is-tidak">
                                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 6L6 18M6 6l12 12"/>
                                </svg>
                                Belum Lulus
                            </span>
                        @else
                            <span class="neo-hasil-status is-menunggu">
                                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Belum Dinilai
                            </span>
                        @endif
                    </div>

                    <!-- Lingkaran nilai -->
                    <div class="neo-hasil-ring">
                        <svg viewBox="0 0 120 120" aria-hidden="true">
                            <defs>
                                <linearGradient id="neoHasilRing" x1="0" y1="0" x2="1" y2="1">
                                    <stop offset="0%" stop-color="var(--gold-bright)"/>
                                    <stop offset="100%" stop-color="var(--sun)"/>
                                </linearGradient>
                            </defs>
                            <circle class="neo-hasil-ring-track" cx="60" cy="60" r="52"></circle>
                            <circle class="neo-hasil-ring-bar" cx="60" cy="60" r="52"
                                    stroke-dasharray="326.7"
                                    stroke-dashoffset="{{ round(326.7 - (326.7 * max(0, min(100, $nilai)) / 100), 2) }}"></circle>
                        </svg>
                        <div class="neo-hasil-ring-value">
                            <strong>{{ number_format($nilai, 2) }}</strong>
                            <span>Nilai Akhir</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ============ STATISTIK ============ -->
            <div class="neo-hasil-stats">
                <div class="neo-hasil-stat is-benar">
                    <span class="neo-hasil-stat-icon">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </span>
                    <div>
                        <p class="neo-hasil-stat-value">{{ $data['benar'] }}</p>
                        <p class="neo-hasil-stat-label">Jawaban Benar</p>
                    </div>
                </div>

                <div class="neo-hasil-stat is-salah">
                    <span class="neo-hasil-stat-icon">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 14L21 3m0 0l-2.5 11.5M21 3L10.5 14.5"/>
                        </svg>
                    </span>
                    <div>
                        <p class="neo-hasil-stat-value">{{ $data['salah'] }}</p>
                        <p class="neo-hasil-stat-label">Jawaban Salah</p>
                    </div>
                </div>

                <div class="neo-hasil-stat">
                    <span class="neo-hasil-stat-icon">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h8"/>
                        </svg>
                    </span>
                    <div>
                        <p class="neo-hasil-stat-value">{{ $data['skip'] }}</p>
                        <p class="neo-hasil-stat-label">Tidak Dijawab</p>
                    </div>
                </div>

                <div class="neo-hasil-stat">
                    <span class="neo-hasil-stat-icon">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <circle cx="12" cy="12" r="6"/>
                            <circle cx="12" cy="12" r="2"/>
                        </svg>
                    </span>
                    <div>
                        <p class="neo-hasil-stat-value">{{ number_format($data['totalSkor'], 0) }}</p>
                        <p class="neo-hasil-stat-label">Skor dari {{ number_format($data['totalPoin'], 0) }} poin</p>
                    </div>
                </div>
            </div>

            <!-- ============ PELANGGARAN ============ -->
            @if($jumlahPelanggaran > 0)
                <div class="neo-hasil-alert">
                    <span class="neo-hasil-alert-icon">
                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                        </svg>
                    </span>
                    <div>
                        <p class="neo-hasil-alert-title">
                            Terdapat {{ $jumlahPelanggaran }} pelanggaran selama ujian
                        </p>
                        <p class="neo-hasil-alert-text">
                            Berpindah tab atau jendela, keluar dari mode layar penuh, menyalin, menempel,
                            dan mencetak halaman ujian dicatat sebagai pelanggaran dan dapat ditinjau oleh admin.
                        </p>
                    </div>
                </div>
            @endif

            <!-- ============ PEMBAHASAN ============ -->
            <div class="neo-hasil-section">
                <span class="neo-hasil-section-icon">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 6.253 9.504 6 8 6c-2.5 0-4 1.5-4 3 0 2.5 4 2 4 4 0 1.5-1.5 3-3.5 3M16.5 6C15.832 6.253 16.5 6 16 6c-1 0-1.5.5-2 .5"/>
                    </svg>
                </span>
                <h2 class="neo-hasil-section-title">Pembahasan</h2>
                <span class="neo-hasil-section-line"></span>
            </div>

            <button type="button"
                    class="neo-hasil-reveal"
                    :class="{ 'is-buka': buka }"
                    @click="buka = ! buka"
                    :aria-expanded="buka ? 'true' : 'false'">
                <span class="neo-hasil-reveal-icon">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </span>
                <span class="neo-hasil-reveal-text">
                    <span class="neo-hasil-reveal-title"
                          x-text="buka ? 'Sembunyikan Pembahasan' : 'Tampilkan Pembahasan'"></span>
                    <span class="neo-hasil-reveal-sub"
                          x-text="buka
                              ? 'Klik untuk menyembunyikan kembali daftar pembahasan'
                              : 'Klik untuk melihat kunci jawaban dan skor tiap soal (' + total + ' soal)'"></span>
                </span>
                <span class="neo-hasil-reveal-badge" x-text="total + ' soal'"></span>
            </button>

            <div class="grid gap-4 mt-4"
                 x-show="buka"
                 x-transition.opacity.duration.250ms>
                @foreach($data['rincian'] as $r)
                    <article class="neo-hasil-card">
                        <div class="neo-hasil-card-head">
                            <span class="neo-hasil-card-num">
                                <span>{{ str_pad((string) $r['nomor'], 2, '0', STR_PAD_LEFT) }}</span>
                                Soal
                            </span>
                            <span class="neo-hasil-badges">
                                @if($r['tipe'] === 'pilihan_ganda')
                                    <span class="neo-hasil-badge is-gold">Pilihan Ganda</span>
                                    @if($r['status'] === 'benar')
                                        <span class="neo-hasil-badge is-benar">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            Benar
                                        </span>
                                    @elseif($r['status'] === 'salah')
                                        <span class="neo-hasil-badge is-salah">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 6L6 18M6 6l12 12"/>
                                            </svg>
                                            Salah
                                        </span>
                                    @else
                                        <span class="neo-hasil-badge">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h8"/>
                                            </svg>
                                            Tidak Dijawab
                                        </span>
                                    @endif
                                @else
                                    <span class="neo-hasil-badge is-gold">Esai</span>
                                    @if($r['dinilai'])
                                        <span class="neo-hasil-badge is-benar">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            Dinilai
                                        </span>
                                    @else
                                        <span class="neo-hasil-badge">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            Menunggu Penilaian
                                        </span>
                                    @endif
                                @endif
                                <span class="neo-hasil-badge">{{ rtrim(rtrim(number_format($r['skorSoal'], 2), '0'), '.') }} / {{ $r['poin'] }} poin</span>
                            </span>
                        </div>

                        <div class="neo-hasil-card-body">
                            <p class="neo-hasil-question">{{ $r['pertanyaan'] }}</p>

                            @if($r['petunjuk'])
                                <p class="neo-hasil-hint">{{ $r['petunjuk'] }}</p>
                            @endif

                            @if($r['tipe'] === 'pilihan_ganda')
                                <div class="neo-hasil-answer {{ $r['status'] === 'benar' ? 'is-benar' : ($r['status'] === 'salah' ? 'is-salah' : '') }}">
                                    <span class="neo-hasil-answer-icon">
                                        @if($r['status'] === 'benar')
                                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        @elseif($r['status'] === 'salah')
                                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 6L6 18M6 6l12 12"/>
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h8"/>
                                            </svg>
                                        @endif
                                    </span>
                                    <div class="min-w-0">
                                        <span class="neo-hasil-answer-label">Jawaban Anda</span>
                                        <p class="neo-hasil-answer-text {{ $r['dipilihTeks'] ? '' : 'is-empty' }}">
                                            @if($r['dipilihTeks'])
                                                <span class="font-mono font-bold">{{ $r['dipilihLabel'] }}.</span> {{ $r['dipilihTeks'] }}
                                            @else
                                                Tidak dijawab
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                <div class="neo-hasil-answer is-kunci">
                                    <span class="neo-hasil-answer-icon">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.5 7.5a4.5 4.5 0 10-5.06 5.06L4 19v2h2v-2h2v-2h2l1.5-1.5a4.5 4.5 0 004-7z"/>
                                        </svg>
                                    </span>
                                    <div class="min-w-0">
                                        <span class="neo-hasil-answer-label">Kunci Jawaban</span>
                                        <p class="neo-hasil-answer-text">
                                            @if($r['kunciTeks'])
                                                <span class="font-mono font-bold">{{ $r['kunciLabel'] }}.</span> {{ $r['kunciTeks'] }}
                                            @else
                                                <span class="is-empty">Kunci jawaban tidak tersedia</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            @else
                                <div class="neo-hasil-answer">
                                    <span class="neo-hasil-answer-icon">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </span>
                                    <div class="min-w-0">
                                        <span class="neo-hasil-answer-label">Jawaban Anda</span>
                                        <p class="neo-hasil-answer-text {{ $r['esai'] ? 'is-pre' : 'is-empty' }}">{{ $r['esai'] ?: 'Tidak dijawab' }}</p>
                                    </div>
                                </div>

                                @if($r['file_url'])
                                    <a href="{{ $r['file_url'] }}" class="neo-hasil-file">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M21.44 11.05l-9.19 9.19a6 6 0 01-8.49-8.49l9.19-9.19a4 4 0 015.66 5.66l-9.2 9.19a2 2 0 01-2.83-2.83l8.49-8.48"/>
                                        </svg>
                                        {{ $r['file_nama'] }}
                                    </a>
                                @endif
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="neo-hasil-foot">
                <a href="{{ route('ujian.index') }}" class="neo-btn neo-btn-secondary">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5m7 7l-7-7 7-7"/>
                    </svg>
                    Kembali ke Daftar Ujian
                </a>
                <button type="button" class="neo-btn" @click="cetak()">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2M6 14h12v8H6z"/>
                    </svg>
                    Cetak Hasil
                </button>
            </div>

        </div>
    </div>
</main>

@once
<script>
    document.addEventListener('alpine:init', () => {
        window.Alpine.data('hasilUjian', (total) => ({
            total: total,

            // Pembahasan tersembunyi saat halaman dibuka; tombol di bawahnya
            // dipakai untuk menampilkan/menyembunyikan seluruh daftar pembahasan.
            buka: false,

            cetak() {
                const sebelumnya = this.buka;

                this.buka = true;

                window.addEventListener('afterprint', () => {
                    this.buka = sebelumnya;
                }, { once: true });

                window.print();
            },
        }));
    });
</script>
@endonce

</x-layouts.app>
