<x-layouts.app title="{{ $title }}">

<main class="neo-mirai" x-data="ujian(@js($soal), @js($sudahDijawab), @js($esai), @js($lampiran), @js($remainingSeconds), @js([
    'save' => route('ujian.jawaban', $ujian->id),
    'submit' => route('ujian.submit', $ujian->id),
    'upload' => route('ujian.upload', $ujian->id),
    'hasil' => route('ujian.hasil', $ujian->id),
    'perangkat' => route('ujian.perangkat', $ujian->id),
    'pelanggaran' => route('ujian.pelanggaran', $ujian->id),
    'mulai' => route('ujian.mulai', $ujian->id),
    'daftar' => route('ujian.index'),
]), @js($jumlahPelanggaran), @js($sesiBerjalan))">

    <!-- ============ BAR ATAS ============ -->
    <div class="neo-ujian-topbar">
        <div class="content-centered px-4 sm:px-5 py-2.5 sm:py-3 flex flex-wrap items-center justify-between gap-2 sm:gap-3">
            <div class="w-full sm:w-auto sm:flex-1 min-w-0">
                <div class="flex items-center gap-1.5 sm:gap-2 mb-1 overflow-x-auto">
                    <span class="neo-badge neo-badge-gold shrink-0">
                        <span class="neo-badge-dot"></span>
                        {{ $ujian->kode }}
                    </span>
                    <span class="neo-badge shrink-0">{{ count($soal) }} soal</span>
                    <span class="neo-badge shrink-0">{{ $ujian->durasi_menit }} menit</span>
                </div>
                <h1 class="font-display text-base sm:text-lg font-semibold text-[var(--ink)] truncate">{{ $ujian->judul }}</h1>
            </div>

            <div class="w-full sm:w-auto flex flex-wrap items-center gap-2 sm:gap-3">
                <!-- Status simpan -->
                <div class="order-4 sm:order-none w-full sm:w-auto text-left sm:text-right text-[0.7rem] sm:text-xs">
                    @if($errors->any())
                        <span class="text-red-600">Gagal menyimpan</span>
                    @endif
                    <span x-show="saving" class="text-[var(--ink-soft)]">Menyimpan&hellip;</span>
                    <span x-show="!saving && savedAt && !error" class="text-[var(--ink-soft)] font-mono">
                        Tersimpan <span x-text="savedAt"></span>
                    </span>
                    <span x-show="!saving && error" class="text-red-600">
                        Gagal menyimpan &mdash;
                        <button type="button" class="underline" @click="flush()">coba lagi</button>
                    </span>
                </div>

                <!-- Timer -->
                <div class="neo-ujian-timer order-1 sm:order-none" :class="{ 'is-low': remaining <= 60 }">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="font-mono text-base sm:text-lg font-bold tabular-nums" x-text="timeText"></span>
                </div>

                <!-- Pelanggaran -->
                <div class="order-2 sm:order-none flex items-center gap-2 px-3 py-2 rounded-xl border text-xs"
                     :class="violasi > 0 ? 'border-red-300 bg-red-50 text-red-700' : 'border-[var(--line)] bg-[var(--paper-soft)] text-[var(--ink-soft)]'"
                     x-show="violasi > 0">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                    </svg>
                    <span>Pelanggaran: <span class="font-mono font-bold" x-text="violasi"></span></span>
                </div>

                <button type="button" class="neo-btn neo-ujian-submit-btn order-3 sm:order-none ml-auto sm:ml-0" @click="confirmOpen = true">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span class="hidden sm:inline">Kumpulkan Jawaban</span>
                    <span class="sm:hidden">Kumpulkan</span>
                </button>
            </div>
        </div>

        <!-- Garis progres jawaban -->
        <div class="neo-ujian-progress-line">
            <div :style="`width: ${progress}%`"></div>
        </div>
    </div>

    <div class="border-b border-[var(--line)] bg-[var(--paper-soft)]">
        <div class="content-centered px-5 py-3">
            <div class="neo-ujian-notice">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span x-show="!dimulai" x-cloak>
                    Ujian <strong class="text-[var(--ink)]">belum dimulai</strong>. Waktu ujian akan
                    berjalan setelah Anda menekan tombol <strong class="text-[var(--ink)]">Mulai Ujian</strong>
                    pada jendela yang terbuka.
                </span>
                <span x-show="dimulai && fsAktif" x-cloak>
                    Ujian berjalan dalam mode layar penuh. Membuka tab atau jendela lain, keluar dari layar penuh,
                    menyalin, menempel, dan mencetak halaman ini dicatat sebagai <strong class="text-[var(--ink)]">pelanggaran</strong>
                    dan terlihat oleh admin.
                </span>
                <span x-show="dimulai && ! fsAktif" x-cloak>
                    Ujian sedang berjalan <strong class="text-[var(--ink)]">tanpa mode layar penuh</strong>.
                    Kembali ke layar penuh untuk menghindari pelanggaran baru.
                </span>
            </div>
        </div>
    </div>

    <div class="page-content">
        <div class="content-centered">
            <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_20rem]">

                <!-- ============ PANEL SOAL ============ -->
                <div class="neo-ujian-panel">
                    <div class="neo-ujian-panel-head">
                        <span class="neo-ujian-panel-index">
                            Soal <strong x-text="current + 1"></strong> dari <span x-text="soal.length"></span>
                        </span>
                        <div class="neo-ujian-meta">
                            <span class="neo-badge neo-badge-gold" x-show="active && active.tipe === 'pilihan_ganda'">Pilihan Ganda</span>
                            <span class="neo-badge neo-badge-primary" x-show="active && active.tipe === 'essay'">Esai</span>
                            <span class="neo-badge" x-show="active"><span x-text="(active ? active.poin : 0) + ' poin'"></span></span>
                        </div>
                    </div>

                    <div class="neo-ujian-panel-body">
                        <template x-if="active">
                            <div>
                                <p class="neo-ujian-question" x-text="active.pertanyaan"></p>

                                <div class="neo-ujian-hint" x-show="active.petunjuk">
                                    <svg class="w-4 h-4 mt-0.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span x-text="active.petunjuk"></span>
                                </div>

                                <!-- PILIHAN GANDA -->
                                <div x-show="active.tipe === 'pilihan_ganda'" class="neo-ujian-options">
                                    <template x-for="p in active.pilihan" :key="p.id">
                                        <label class="neo-ujian-option" :class="{ 'is-active': answers[active.id] == p.id }">
                                            <input type="radio"
                                                   class="sr-only"
                                                   :name="`soal-${active.id}`"
                                                   :value="p.id"
                                                   :checked="answers[active.id] == p.id"
                                                   @change="pick(active.id, p.id)">
                                            <span class="neo-ujian-option-label" x-text="p.label"></span>
                                            <span class="neo-ujian-option-text" x-text="p.teks"></span>
                                            <svg class="neo-ujian-option-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </label>
                                    </template>
                                </div>

                                <!-- ESAI -->
                                <div x-show="active.tipe === 'essay'">
                                    <label class="neo-field-label" for="esai-soal">Jawaban Esai</label>
                                    <textarea id="esai-soal"
                                              class="neo-field-textarea w-full"
                                              rows="7"
                                              :value="essays[active.id] || ''"
                                              @input="onEssay(active.id, $event.target.value)"></textarea>
                                    <p class="neo-field-hint">
                                        Karakter: <span class="font-mono" x-text="(essays[active.id] || '').length"></span>
                                    </p>

                                    <div class="neo-upload-section mt-6">
                                        <div class="neo-upload-grid" style="grid-template-columns: 1fr;">
                                            <div class="neo-upload-card" :class="{ 'has-file': lampiran[active.id] }">
                                                <div class="neo-upload-card-header">
                                                    <span class="neo-upload-number">Lampiran</span>
                                                    <span class="neo-upload-badge opsional">Opsional</span>
                                                </div>
                                                <div class="neo-upload-card-body">
                                                    <label class="neo-upload-dropzone">
                                                        <input type="file" class="neo-upload-input"
                                                               accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png,.txt,.zip,.rar"
                                                               @change="upload(active.id, $event)">
                                                        <template x-if="uploading[active.id]">
                                                            <div class="neo-upload-placeholder">
                                                                <svg class="animate-spin" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                                                </svg>
                                                                <span>Mengunggah berkas&hellip;</span>
                                                            </div>
                                                        </template>
                                                        <template x-if="!uploading[active.id] && !lampiran[active.id]">
                                                            <div class="neo-upload-placeholder">
                                                                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                                                    <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/>
                                                                    <polyline points="17 8 12 3 7 8"/>
                                                                    <line x1="12" y1="3" x2="12" y2="15"/>
                                                                </svg>
                                                                <span>Klik untuk pilih file</span>
                                                                <span class="neo-upload-hint">PDF, DOC, XLS, JPG, PNG (maks 5 MB)</span>
                                                            </div>
                                                        </template>
                                                        <template x-if="!uploading[active.id] && lampiran[active.id]">
                                                            <div class="neo-upload-preview">
                                                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                                                    <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                                                                    <polyline points="14 2 14 8 20 8"/>
                                                                </svg>
                                                                <span class="neo-file-name" x-text="lampiran[active.id] ? lampiran[active.id].nama : ''"></span>
                                                                <span class="neo-upload-hint">Klik untuk mengganti berkas</span>
                                                            </div>
                                                        </template>
                                                    </label>
                                                    <p class="text-xs text-red-600 mt-2" x-show="uploadError[active.id]" x-text="uploadError[active.id]"></p>
                                                    <a x-show="lampiran[active.id]"
                                                       :href="lampiran[active.id] ? lampiran[active.id].url : '#'"
                                                       class="text-xs text-[var(--gold)] hover:underline mt-2 inline-block">Lihat berkas terlampir</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Navigasi -->
                    <div class="neo-ujian-nav">
                        <button type="button"
                                class="neo-btn neo-btn-secondary"
                                @click="goto(current - 1)"
                                :disabled="current === 0">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5m7 7l-7-7 7-7"/>
                            </svg>
                            Sebelumnya
                        </button>

                        <span class="neo-ujian-panel-index">
                            <strong x-text="current + 1"></strong> / <span x-text="soal.length"></span>
                        </span>

                        <button type="button"
                                class="neo-btn"
                                @click="goto(current + 1)"
                                :disabled="current >= soal.length - 1">
                            Berikutnya
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-5-5 5 5-5 5"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- ============ SIDEBAR PALET ============ -->
                <aside class="neo-ujian-side self-start order-first lg:order-none">
                    <div class="neo-ujian-side-head">
                        <h2 class="neo-card-title">Navigasi Soal</h2>
                        <p class="neo-ujian-side-hint text-xs text-[var(--ink-soft)] mt-1">
                            Klik nomor untuk berpindah soal
                        </p>
                    </div>
                    <div class="neo-ujian-side-body">
                        <div class="neo-ujian-palette mb-4">
                            <template x-for="(s, i) in soal" :key="s.id">
                                <button type="button"
                                        @click="goto(i)"
                                        class="neo-ujian-palette-btn"
                                        :class="[
                                            isAnswered(s) ? 'is-answered' : '',
                                            i === current ? 'is-current' : ''
                                        ]"
                                        x-text="i + 1"></button>
                            </template>
                        </div>

                        <div class="flex items-center gap-3 flex-wrap mb-5">
                            <span class="neo-badge"><span class="neo-badge-dot bg-[var(--gold)]"></span>Dijawab</span>
                            <span class="neo-badge"><span class="neo-badge-dot bg-[var(--line)]"></span>Belum</span>
                        </div>

                        <div class="mb-2 flex items-center justify-between">
                            <span class="text-xs text-[var(--ink-soft)]">
                                <span class="font-mono font-bold text-[var(--ink)]" x-text="answeredCount"></span>
                                dari <span class="font-mono" x-text="soal.length"></span> soal terjawab
                            </span>
                            <span class="font-mono text-xs font-bold text-[var(--gold)]" x-text="progress + '%'"></span>
                        </div>
                        <div class="neo-ujian-progress">
                            <div :style="`width: ${progress}%`"></div>
                        </div>

                        <button type="button" class="neo-btn neo-ujian-side-submit w-full justify-center mt-6" @click="confirmOpen = true">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                            Kumpulkan Jawaban
                        </button>
                    </div>
                </aside>

            </div>
        </div>
    </div>

    <!-- ============ MODAL KONFIRMASI ============ -->
    <div class="neo-modal-backdrop neo-ujian-submit-backdrop" x-show="confirmOpen" x-cloak @keydown.escape.window="confirmOpen = false">
        <div class="neo-ujian-submit-modal" @click.outside="confirmOpen = false">
            <!-- Kepala -->
            <div class="neo-ujian-submit-head">
                <div class="neo-ujian-submit-icon">
                    <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m7 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <p class="neo-ujian-submit-eyebrow">Konfirmasi Pengumpulan</p>
                <h3 class="neo-ujian-submit-title">Kumpulkan Jawaban?</h3>
                <p class="neo-ujian-submit-sub">
                    Setelah dikumpulkan, jawaban tidak dapat diubah lagi dan halaman hasil
                    langsung ditampilkan.
                </p>
            </div>

            <!-- Isi -->
            <div class="neo-ujian-submit-body">
                <div class="neo-ujian-ring-wrap">
                    <svg class="neo-ujian-ring" viewBox="0 0 120 120">
                        <defs>
                            <linearGradient id="neoUjianRing" x1="0" y1="0" x2="1" y2="1">
                                <stop offset="0%" stop-color="var(--gold)"/>
                                <stop offset="100%" stop-color="var(--sun)"/>
                            </linearGradient>
                        </defs>
                        <circle class="neo-ujian-ring-track" cx="60" cy="60" r="52"></circle>
                        <circle class="neo-ujian-ring-bar" cx="60" cy="60" r="52"
                                stroke-dasharray="326.7"
                                :stroke-dashoffset="326.7 - (326.7 * progress / 100)"></circle>
                    </svg>
                    <div class="neo-ujian-ring-value">
                        <strong x-text="progress + '%'"></strong>
                        <span>Terjawab</span>
                    </div>
                </div>

                <div class="neo-ujian-submit-meta">
                    <div class="neo-ujian-submit-meta-item">
                        <strong x-text="answeredCount"></strong>
                        <span>Dijawab</span>
                    </div>
                    <div class="neo-ujian-submit-meta-item" :class="{ 'is-warn': soal.length - answeredCount > 0 }">
                        <strong x-text="soal.length - answeredCount"></strong>
                        <span>Kosong</span>
                    </div>
                    <div class="neo-ujian-submit-meta-item">
                        <strong x-text="soal.length"></strong>
                        <span>Total Soal</span>
                    </div>
                </div>

                <div class="neo-ujian-submit-warn" x-show="answeredCount < soal.length" x-cloak>
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                    </svg>
                    <span>
                        Masih ada <strong x-text="soal.length - answeredCount"></strong> soal belum dijawab
                        dan akan dinilai sebagai tidak dijawab.
                    </span>
                </div>

                <p class="neo-ujian-submit-note">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    Jawaban yang sudah tersimpan aman di server.
                </p>
            </div>

            <!-- Aksi -->
            <div class="neo-ujian-submit-foot">
                <button type="button" class="neo-btn neo-btn-secondary" @click="confirmOpen = false">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 6L6 18M6 6l12 12"/>
                    </svg>
                    Batal
                </button>
                <button type="button" class="neo-btn" @click="doSubmit(false)" :disabled="submitting">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span x-text="submitting ? 'Mengirim...' : 'Ya, Kumpulkan'"></span>
                </button>
            </div>
        </div>
    </div>

    <!-- ============ MODAL MODE LAYAR PENUH ============ -->
    <div class="neo-modal-backdrop neo-ujian-fs-backdrop" x-show="fsOpen" x-cloak>
        <div class="neo-ujian-fs-modal">
            <!-- Kepala -->
            <div class="neo-ujian-fs-head">
                <div class="neo-ujian-fs-icon">
                    <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <rect x="2" y="3" width="20" height="14" rx="2"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 21h8m-4-4v4"/>
                    </svg>
                </div>
                <h3 class="neo-ujian-fs-title">Masuk ke Layar Penuh</h3>
                <p class="neo-ujian-fs-sub">
                    Ujian hanya dapat dimulai dalam mode layar penuh. Tekan
                    <strong>Mulai Ujian</strong> untuk masuk ke layar penuh dan memulai waktu ujian.
                </p>
            </div>

            <!-- Isi -->
            <div class="neo-ujian-fs-body">
                <p class="neo-ujian-fs-error" x-show="startError" x-cloak x-text="startError"></p>

                <div class="neo-ujian-fs-rule">
                    <span class="neo-ujian-fs-rule-num">01</span>
                    <span class="neo-ujian-fs-rule-text">
                        Jangan membuka tab, jendela, atau aplikasi lain selama ujian berlangsung.
                    </span>
                </div>
                <div class="neo-ujian-fs-rule">
                    <span class="neo-ujian-fs-rule-num">02</span>
                    <span class="neo-ujian-fs-rule-text">
                        Menyalin, menempel, dan mencetak halaman ujian dinonaktifkan.
                    </span>
                </div>
                <div class="neo-ujian-fs-rule">
                    <span class="neo-ujian-fs-rule-num">03</span>
                    <span class="neo-ujian-fs-rule-text">
                        Jawaban tersimpan otomatis setiap beberapa detik. Jangan menutup jendela browser ini.
                    </span>
                </div>

                <div class="neo-ujian-fs-alert">
                    <svg class="w-4 h-4 mt-0.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                    </svg>
                    <span>
                        <span x-show="!dimulai" x-cloak>Waktu ujian <strong>belum berjalan</strong> dan baru mulai setelah tombol <strong>Mulai Ujian</strong> ditekan. </span>
                        Peserta tetap dapat menutup layar penuh, tetapi setiap keluar dari layar penuh
                        <strong>dicatat sebagai pelanggaran</strong> dan dapat dilihat oleh admin.
                    </span>
                </div>
            </div>

            <!-- Aksi -->
            <div class="neo-ujian-fs-foot">
                <div class="neo-ujian-fs-actions">
                    <button type="button" class="neo-btn neo-btn-secondary" @click="kembaliKeDaftarUjian()">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5m7 7l-7-7 7-7"/>
                        </svg>
                        Kembali
                    </button>
                    <button type="button" class="neo-btn" @click="masukLayarPenuh()" :disabled="memulai">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        <span x-text="memulai ? 'Memulai...' : 'Mulai Ujian'"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ============ MODAL PERINGATAN PELANGGARAN ============ -->
    <div class="neo-modal-backdrop neo-ujian-alert-backdrop" x-show="warnOpen" x-cloak>
        <div class="neo-ujian-alert-modal">
            <!-- Kepala -->
            <div class="neo-ujian-alert-head">
                <div class="neo-ujian-alert-icon">
                    <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                    </svg>
                </div>
                <p class="neo-ujian-alert-eyebrow">Pelanggaran Terdeteksi</p>
                <h3 class="neo-ujian-alert-title" x-text="warnTitle"></h3>
                <p class="neo-ujian-alert-sub">
                    Tindakan ini dicatat oleh sistem dan dapat ditinjau oleh admin ujian.
                </p>
            </div>

            <!-- Isi -->
            <div class="neo-ujian-alert-body">
                <p class="neo-ujian-alert-text" x-text="warnText"></p>

                <div class="neo-ujian-alert-counter">
                    <span class="neo-ujian-alert-count" x-text="violasi"></span>
                    <div>
                        <p class="neo-ujian-alert-count-label">Total Pelanggaran</p>
                        <p class="neo-ujian-alert-count-text">pada sesi ini</p>
                    </div>
                </div>

                <div class="neo-ujian-alert-ticks">
                    <template x-for="i in tickTotal" :key="i">
                        <span class="neo-ujian-alert-tick" :class="{ 'is-empty': i > violasi }"></span>
                    </template>
                </div>

                <p class="neo-ujian-alert-note">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 16v-4m0-4h.01"/>
                    </svg>
                    Tombol di bawah mengembalikan Anda ke halaman ujian dalam mode layar penuh.
                </p>
            </div>

            <!-- Aksi -->
            <div class="neo-ujian-alert-foot">
                <button type="button" class="neo-btn" @click="kembaliKeUjian()">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5m7 7l-7-7 7-7"/>
                    </svg>
                    Kembali ke Ujian
                </button>
            </div>
        </div>
    </div>

</main>

@once
<script>
    document.addEventListener('alpine:init', () => {        window.Alpine.data('ujian', (soal, answers, essays, lampiran, remaining, urls, jumlahPelanggaran, started) => ({
            soal: soal,
            answers: answers,
            essays: essays,
            lampiran: lampiran,
            current: 0,
            pending: {},
            saving: false,
            savedAt: null,
            error: false,
            remaining: Math.max(0, Math.round(remaining)),
            confirmOpen: false,
            submitting: false,
            uploading: {},
            uploadError: {},
            token: document.querySelector('meta[name="csrf-token"]')?.content || '',
            timer: null,
            flushTimer: null,
            inflight: Promise.resolve(),
            autoSubmitted: false,
            sudahSelesai: false,
            violasi: Math.max(0, Math.round(jumlahPelanggaran || 0)),
            catatanPelanggaran: {},
            fsOpen: true,
            fsAktif: false,
            fsPernahAktif: false,
            warnOpen: false,
            warnTitle: '',
            warnText: '',
            dimulai: !! started,
            memulai: false,
            startError: '',

            init() {
                this.timer = setInterval(() => this.tick(), 1000);

                document.addEventListener('visibilitychange', () => {
                    if (document.visibilityState === 'hidden') {
                        this.catat('perpindahan_fokus', 'Halaman ujian tidak lagi aktif, kemungkinan tab atau jendela lain dibuka.');
                        this.beacon();
                    }
                });

                window.addEventListener('pagehide', () => {
                    this.catat('perpindahan_fokus', 'Halaman ujian ditutup.');
                    this.beacon();
                });

                this.pasangPenjaga();
                this.daftarPerangkat();
            },

            /**
             * Penjaga browser: tab baru, keluar layar penuh, salin, tempel, cetak.
             */
            pasangPenjaga() {
                // Jendela kehilangan fokus, misalnya karena membuka jendela lain.
                window.addEventListener('blur', () => {
                    if (document.visibilityState === 'hidden') {
                        return;
                    }

                    // Dialog pemilih berkas bawaan juga membuat jendela kehilangan fokus.
                    const aktif = document.activeElement;
                    if (aktif && aktif.type === 'file') {
                        return;
                    }

                    this.catat('perpindahan_fokus', 'Fokus berpindah ke jendela lain.');
                });

                // Keluar dari mode layar penuh.
                const periksaLayarPenuh = () => {
                    if (document.fullscreenElement || document.webkitFullscreenElement) {
                        this.fsAktif = true;
                        this.fsPernahAktif = true;

                        // Jendela popup baru hanya ditutup setelah sesi ujian
                        // benar-benar berjalan.
                        if (this.dimulai) {
                            this.fsOpen = false;
                        }

                        return;
                    }

                    if (this.fsAktif) {
                        this.fsAktif = false;
                        this.catat('keluar_layar_penh', 'Peserta keluar dari mode layar penuh.');
                    }
                };

                document.addEventListener('fullscreenchange', periksaLayarPenuh);
                document.addEventListener('webkitfullscreenchange', periksaLayarPenuh);

                // Pembukaan tab atau jendela baru.
                window.open = () => {
                    this.catat('tab_baru', 'Upaya membuka tab atau jendela baru.');
                    return null;
                };

                document.addEventListener('click', (e) => {
                    const tautan = e.target && e.target.closest ? e.target.closest('a[target="_blank"]') : null;

                    if (tautan) {
                        e.preventDefault();
                        this.catat('tab_baru', 'Upaya membuka tab baru lewat tautan.');
                    }
                }, true);

                // Klik kanan.
                document.addEventListener('contextmenu', (e) => {
                    e.preventDefault();
                    this.tampilkanPeringatan('Klik Kanan Diblokir', 'Menu klik kanan dinonaktifkan selama ujian berlangsung.');
                });

                // Salin dan potong, kecuali teks jawaban sendiri milik peserta.
                document.addEventListener('copy', (e) => {
                    if (this.bolehSalin(e.target)) {
                        return;
                    }

                    e.preventDefault();
                    this.catat('salin', 'Upaya menyalin isi soal ujian.');
                });

                document.addEventListener('cut', (e) => {
                    if (this.bolehSalin(e.target)) {
                        return;
                    }

                    e.preventDefault();
                    this.catat('salin', 'Upaya memotong isi soal ujian.');
                });

                // Tempel.
                document.addEventListener('paste', (e) => {
                    e.preventDefault();
                    this.catat('tempel', 'Upaya menempel teks ke halaman ujian.');
                });

                // Pencetakan dan pintasan papan ketik.
                document.addEventListener('keydown', (e) => {
                    if (! e.ctrlKey && ! e.metaKey) {
                        return;
                    }

                    const tombol = (e.key || '').toLowerCase();

                    if (tombol === 'p') {
                        e.preventDefault();
                        this.catat('cetak', 'Upaya mencetak halaman ujian.');
                        return;
                    }

                    if (tombol === 'c' || tombol === 'x') {
                        if (this.bolehSalin(e.target)) {
                            return;
                        }

                        e.preventDefault();
                        this.catat('salin', 'Upaya menyalin isi soal ujian.');
                        return;
                    }

                    if (tombol === 'v') {
                        e.preventDefault();
                        this.catat('tempel', 'Upaya menempel teks ke halaman ujian.');
                    }
                });

                // Peringatan saat menutup atau meninggalkan halaman ujian.
                window.addEventListener('beforeunload', (e) => {
                    if (this.sudahSelesai || ! this.dimulai) {
                        return;
                    }

                    e.preventDefault();
                    e.returnValue = '';
                });
            },

            /**
             * Teks milik peserta sendiri (kolom jawaban esai) boleh disalin.
             */
            bolehSalin(target) {
                if (! target || ! target.tagName) {
                    return false;
                }

                const tag = target.tagName.toLowerCase();

                return tag === 'textarea' || (tag === 'input' && (target.type === 'text' || target.type === 'search'));
            },

            /**
             * Masuk ke layar penuh lalu memulai sesi ujian. Ujian wajib
             * berjalan dalam mode layar penuh, sehingga bila peramban
             * menolak permintaan layar penuh sesi tidak dimulai.
             */
            async masukLayarPenuh() {
                const berhasil = await this.aktifkanLayarPenuh();

                if (! berhasil) {
                    this.startError = 'Mode layar penuh tidak dapat diaktifkan. Mohon buka halaman ujian di browser Chrome pada perangkat Android.';
                    return;
                }

                this.fsAktif = true;
                this.fsPernahAktif = true;

                const ok = await this.mulaiSesi();

                if (! ok) {
                    this.keluarLayarPenuh();
                    return;
                }

                this.fsOpen = false;
            },

            /**
             * Meminta mode layar penuh ke peramban. Klik tombol adalah gestur
             * pengguna yang diizinkan browser untuk memulai layar penuh.
             */
            async aktifkanLayarPenuh() {
                const elemen = document.documentElement;

                try {
                    if (elemen.requestFullscreen) {
                        await elemen.requestFullscreen();
                    } else if (elumen.webkitRequestFullscreen) {
                        elemen.webkitRequestFullscreen();
                    } else {
                        return false;
                    }

                    return true;
                } catch (e) {
                    return false;
                }
            },

            /**
             * Keluar dari mode layar penuh tanpa mencatat pelanggaran baru.
             */
            keluarLayarPenuh() {
                try {
                    if (document.exitFullscreen) {
                        document.exitFullscreen();
                    } else if (document.webkitExitFullscreen) {
                        document.webkitExitFullscreen();
                    }
                } catch (e) {
                    // Keluar layar penuh hanya tambahan, kegagalan diabaikan.
                }
            },

            /**
             * Mulai sesi ujian di server. Server mengembalikan sisa waktu
             * sehingga jam di browser selalu sama dengan batas waktu server.
             */
            async mulaiSesi() {
                if (this.dimulai) {
                    return true;
                }

                if (this.memulai) {
                    return false;
                }

                this.memulai = true;
                this.startError = '';

                try {
                    const res = await fetch(urls.mulai, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': this.token,
                            'X-Ujian-Perangkat': this.sidikPerangkat(),
                        },
                        body: JSON.stringify({}),
                    });

                    const data = await res.json().catch(() => ({}));

                    if (res.status === 409) {
                        this.startError = data.message || 'Ujian ini sedang berjalan pada perangkat lain.';

                        if (data.konflik) {
                            this.violasi++;
                            this.tampilkanPeringatan('Sesi Ganda Terdeteksi', this.startError);
                        }

                        return false;
                    }

                    if (! res.ok) {
                        this.startError = data.message || 'Gagal memulai ujian.';
                        return false;
                    }

                    this.dimulai = true;
                    this.remaining = Math.max(0, Math.round(data.remaining || 0));

                    return true;
                } catch (e) {
                    this.startError = 'Gagal memulai ujian. Periksa koneksi internet Anda.';
                    return false;
                } finally {
                    this.memulai = false;
                }
            },

            /**
             * Tutup peringatan pelanggaran dan kembalikan peserta ke halaman
             * ujian dalam mode layar penuh.
             */
            async kembaliKeUjian() {
                this.warnOpen = false;

                if (document.fullscreenElement || document.webkitFullscreenElement) {
                    this.fsAktif = true;
                    this.fsPernahAktif = true;
                    return;
                }

                const berhasil = await this.aktifkanLayarPenuh();

                this.fsAktif = berhasil;
                this.fsPernahAktif = this.fsPernahAktif || berhasil;
            },

            /**
             * Peserta batal mengikuti ujian. Sesi belum dimulai sehingga
             * tidak ada pelanggaran yang dicatat dan tidak ada peringatan
             * ketika meninggalkan halaman.
             */
            kembaliKeDaftarUjian() {
                this.fsOpen = false;
                window.location.href = urls.daftar;
            },

            /**
             * Catat satu pelanggaran ke server dan tampilkan peringatan.
             */
            catat(jenis, keterangan) {
                if (this.sudahSelesai || ! this.dimulai) {
                    return;
                }

                const sekarang = Date.now();
                const terakhir = this.catatanPelanggaran[jenis] || 0;

                // Satu kejadian bisa memicu beberapa event sekaligus,
                // misalnya berpindah tab memicu blur dan visibilitychange.
                if (sekarang - terakhir < 3000) {
                    return;
                }

                this.catatanPelanggaran[jenis] = sekarang;
                this.violasi++;

                const payload = JSON.stringify({
                    _token: this.token,
                    jenis: jenis,
                    keterangan: keterangan || '',
                });

                if (navigator.sendBeacon) {
                    navigator.sendBeacon(urls.pelanggaran, new Blob([payload], { type: 'application/json' }));
                } else {
                    fetch(urls.pelanggaran, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': this.token,
                        },
                        body: payload,
                    }).catch(() => {});
                }

                this.tampilkanPeringatan(this.judulPelanggaran(jenis), this.pesanPelanggaran(jenis));
            },

            judulPelanggaran(jenis) {
                return {
                    perpindahan_fokus: 'Fokus Berpindah',
                    keluar_layar_penh: 'Keluar dari Layar Penuh',
                    tanpa_layar_penh: 'Tanpa Mode Layar Penuh',
                    sesi_ganda: 'Sesi Ganda Terdeteksi',
                    tab_baru: 'Tab Baru Diblokir',
                    salin: 'Penyalinan Diblokir',
                    tempel: 'Penempelan Diblokir',
                    cetak: 'Percetakan Diblokir',
                }[jenis] || 'Pelanggaran Terdeteksi';
            },

            pesanPelanggaran(jenis) {
                return {
                    perpindahan_fokus: 'Anda berpindah ke tab, jendela, atau aplikasi lain. Tindakan ini dicatat sebagai pelanggaran oleh admin.',
                    keluar_layar_penh: 'Anda keluar dari mode layar penuh. Tindakan ini dicatat sebagai pelanggaran oleh admin.',
                    tanpa_layar_penh: 'Ujian dilanjutkan tanpa mode layar penuh. Tindakan ini dicatat sebagai pelanggaran oleh admin.',
                    sesi_ganda: 'Ujian ini sedang berjalan pada perangkat lain. Tindakan ini dicatat sebagai pelanggaran oleh admin.',
                    tab_baru: 'Membuka tab atau jendela baru dinonaktifkan selama ujian.',
                    salin: 'Menyalin isi soal ujian dinonaktifkan selama ujian.',
                    tempel: 'Menempel teks ke halaman ujian dinonaktifkan selama ujian.',
                    cetak: 'Mencetak halaman ujian dinonaktifkan selama ujian.',
                }[jenis] || 'Tindakan ini dicatat sebagai pelanggaran oleh admin.';
            },

            tampilkanPeringatan(judul, pesan) {
                this.warnTitle = judul;
                this.warnText = pesan;
                this.warnOpen = true;
            },

            /**
             * Daftarkan perangkat peserta. Perangkat yang berbeda dari perangkat
             * memulai sesi dicatat sebagai pelanggaran sesi ganda.
             */
            async daftarPerangkat() {
                try {
                    const res = await fetch(urls.perangkat, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': this.token,
                            'X-Ujian-Perangkat': this.sidikPerangkat(),
                        },
                        body: JSON.stringify({}),
                    });

                    if (res.status === 409) {
                        const data = await res.json().catch(() => ({}));
                        this.violasi++;
                        this.tampilkanPeringatan(
                            'Sesi Ganda Terdeteksi',
                            data.message || 'Ujian ini sedang berjalan pada perangkat lain.'
                        );
                    }
                } catch (e) {
                    // Gagal mendaftarkan perangkat tidak boleh menghentikan ujian.
                }
            },

            /**
             * Sidik peramban, dikirim apa adanya ke server yang menyimpannya
             * dalam bentuk terenkripsi hashing.
             */
            sidikPerangkat() {
                const dasar = [
                    navigator.userAgent || '',
                    (screen.width || 0) + 'x' + (screen.height || 0),
                    screen.colorDepth || 0,
                    navigator.language || '',
                    navigator.hardwareConcurrency || 0,
                    window.devicePixelRatio || 1,
                ].join('|');

                let peramban = '';

                try {
                    peramban = localStorage.getItem('ujian_sidik') || '';

                    if (! peramban) {
                        peramban = Math.random().toString(36).slice(2) + Date.now().toString(36);
                        localStorage.setItem('ujian_sidik', peramban);
                    }
                } catch (e) {
                    peramban = '';
                }

                return dasar + '#' + peramban;
            },

            tick() {
                if (!this.dimulai) {
                    return;
                }

                if (this.remaining > 0) {
                    this.remaining--;
                }

                if (this.remaining === 0 && !this.autoSubmitted) {
                    this.autoSubmitted = true;
                    this.doSubmit(true);
                }
            },

            get active() {
                return this.soal[this.current] || null;
            },

            get timeText() {
                if (!this.dimulai) {
                    return '--:--';
                }

                const m = Math.floor(this.remaining / 60);
                const s = this.remaining % 60;
                return String(m).padStart(2, '0') + ':' + String(s).padStart(2, '0');
            },

            get answeredCount() {
                return this.soal.filter((s) => this.isAnswered(s)).length;
            },

            get progress() {
                if (!this.soal.length) {
                    return 0;
                }
                return Math.round((this.answeredCount / this.soal.length) * 100);
            },

            /**
             * Jumlah penanda visual pada strip di modal pelanggaran.
             */
            get tickTotal() {
                return Math.min(12, Math.max(this.violasi, 4));
            },

            isAnswered(s) {
                if (s.tipe === 'pilihan_ganda') {
                    return !!this.answers[s.id];
                }
                return !!(this.essays[s.id] && String(this.essays[s.id]).trim() !== '');
            },

            goto(i) {
                if (i < 0 || i >= this.soal.length) {
                    return;
                }
                this.current = i;
            },

            pick(soalId, pilihanId) {
                if (this.answers[soalId] == pilihanId) {
                    return;
                }
                this.answers[soalId] = parseInt(pilihanId);
                this.queue(soalId, { pilihan_id: parseInt(pilihanId) });
            },

            onEssay(soalId, value) {
                this.essays[soalId] = value;
                this.queue(soalId, { esai: value });
            },

            queue(soalId, value) {
                this.pending[soalId] = Object.assign({}, this.pending[soalId], value);
                clearTimeout(this.flushTimer);
                this.flushTimer = setTimeout(() => this.flush(), 1500);
            },

            flush() {
                clearTimeout(this.flushTimer);

                if (this.saving) {
                    return this.inflight;
                }

                const keys = Object.keys(this.pending);
                if (!keys.length) {
                    return Promise.resolve();
                }

                const body = {};
                keys.forEach((k) => body[k] = this.pending[k]);
                this.pending = {};

                this.saving = true;
                this.error = false;

                this.inflight = fetch(urls.save, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': this.token,
                    },
                    body: JSON.stringify({ jawaban: body }),
                })
                    .then((r) => r.json().then((d) => {
                        if (!r.ok) {
                            throw d;
                        }
                        return d;
                    }))
                    .then((d) => {
                        this.savedAt = d.saved_at;
                    })
                    .catch(() => {
                        this.error = true;
                        this.pending = Object.assign({}, body, this.pending);
                    })
                    .finally(() => {
                        this.saving = false;
                    });

                return this.inflight;
            },

            beacon() {
                clearTimeout(this.flushTimer);

                const keys = Object.keys(this.pending);
                if (!keys.length) {
                    return;
                }

                const body = {};
                keys.forEach((k) => body[k] = this.pending[k]);
                this.pending = {};

                const payload = JSON.stringify({ _token: this.token, jawaban: body });

                if (navigator.sendBeacon) {
                    navigator.sendBeacon(urls.save, new Blob([payload], { type: 'application/json' }));
                }
            },

            async upload(soalId, event) {
                const file = event.target.files && event.target.files[0];
                if (!file) {
                    return;
                }

                this.uploading[soalId] = true;
                this.uploadError[soalId] = '';

                const form = new FormData();
                form.append('_token', this.token);
                form.append('soal_id', soalId);
                form.append('berkas', file);

                try {
                    const res = await fetch(urls.upload, {
                        method: 'POST',
                        headers: { 'Accept': 'application/json' },
                        body: form,
                    });
                    const data = await res.json();

                    if (!res.ok) {
                        throw new Error(data.message || 'Gagal mengunggah berkas.');
                    }

                    this.lampiran[soalId] = { nama: data.nama, url: data.url };
                } catch (e) {
                    this.uploadError[soalId] = e.message || 'Gagal mengunggah berkas.';
                } finally {
                    this.uploading[soalId] = false;
                    event.target.value = '';
                }
            },

            async doSubmit(auto) {
                if (this.submitting || !this.dimulai) {
                    return;
                }
                this.submitting = true;
                this.confirmOpen = false;

                // Tunggu penyimpanan yang sedang berjalan, lalu kirim sisa antrean.
                await this.inflight;
                if (auto) {
                    this.beacon();
                } else {
                    await this.flush();
                }

                try {
                    const res = await fetch(urls.submit, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': this.token,
                        },
                        body: JSON.stringify({}),
                    });
                    const data = await res.json().catch(() => ({}));
                    this.sudahSelesai = true;
                    window.location.href = data.redirect || urls.hasil;
                } catch (e) {
                    this.submitting = false;
                }
            },
        }));
    });
</script>
@endonce

</x-layouts.app>
