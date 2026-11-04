<x-layouts.app title="{{ $title }}">

<main class="neo-mirai" x-data="ujian(@js($soal), @js($sudahDijawab), @js($esai), @js($lampiran), @js($remainingSeconds), @js([
    'save' => route('ujian.jawaban', $ujian->id),
    'submit' => route('ujian.submit', $ujian->id),
    'upload' => route('ujian.upload', $ujian->id),
    'hasil' => route('ujian.hasil', $ujian->id),
]))">

    <!-- ============ BAR ATAS ============ -->
    <div class="sticky top-0 z-30 border-b border-[var(--line)] bg-[var(--paper)]">
        <div class="content-centered px-5 py-3 flex flex-wrap items-center justify-between gap-3">
            <div class="min-w-0">
                <div class="flex items-center gap-2 mb-1">
                    <span class="neo-badge neo-badge-gold">
                        <span class="neo-badge-dot"></span>
                        {{ $ujian->kode }}
                    </span>
                    <span class="neo-badge">{{ count($soal) }} soal</span>
                    <span class="neo-badge">{{ $ujian->durasi_menit }} menit</span>
                </div>
                <h1 class="font-display text-lg font-semibold text-[var(--ink)] truncate">{{ $ujian->judul }}</h1>
            </div>

            <div class="flex items-center gap-3">
                <!-- Status simpan -->
                <div class="text-right text-xs">
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
                <div class="flex items-center gap-2 px-3 py-2 rounded-xl border border-[var(--line)] bg-[var(--paper-soft)]">
                    <svg class="w-5 h-5 text-[var(--gold)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="font-mono text-lg font-bold text-[var(--ink)] tabular-nums" :class="remaining <= 60 ? 'text-red-600' : ''" x-text="timeText"></span>
                </div>

                <button type="button" class="neo-btn" @click="confirmOpen = true">Kumpulkan Jawaban</button>
            </div>
        </div>
    </div>

    <div class="page-content">
        <div class="content-centered">
            <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_20rem]">

                <!-- ============ PANEL SOAL ============ -->
                <div class="neo-card">
                    <div class="neo-card-header">
                        <div class="flex items-center justify-between gap-3 flex-wrap">
                            <h2 class="neo-card-title">
                                Soal <span x-text="current + 1"></span> dari <span x-text="soal.length"></span>
                            </h2>
                            <div class="flex items-center gap-2">
                                <span class="neo-badge neo-badge-gold" x-show="active && active.tipe === 'pilihan_ganda'">Pilihan Ganda</span>
                                <span class="neo-badge neo-badge-primary" x-show="active && active.tipe === 'essay'">Esai</span>
                                <span class="neo-badge" x-show="active"><span x-text="(active ? active.poin : 0) + ' poin'"></span></span>
                            </div>
                        </div>
                    </div>

                    <div class="neo-card-body">
                        <template x-if="active">
                            <div>
                                <p class="font-display text-lg font-semibold text-[var(--ink)] leading-relaxed mb-4" x-text="active.pertanyaan"></p>

                                <p class="text-sm text-[var(--ink-soft)] mb-5" x-show="active.petunjuk" x-text="active.petunjuk"></p>

                                <!-- PILIHAN GANDA -->
                                <div x-show="active.tipe === 'pilihan_ganda'" class="grid gap-3">
                                    <template x-for="p in active.pilihan" :key="p.id">
                                        <div class="flex items-start gap-3">
                                            <input type="radio"
                                                   class="peer sr-only"
                                                   :id="`pilihan-${active.id}-${p.id}`"
                                                   :name="`soal-${active.id}`"
                                                   :value="p.id"
                                                   :checked="answers[active.id] == p.id"
                                                   @change="pick(active.id, p.id)">
                                            <label :for="`pilihan-${active.id}-${p.id}`"
                                                   class="shrink-0 w-8 h-8 rounded-full border border-[var(--line)] bg-[var(--paper)] flex items-center justify-center font-mono text-xs font-bold text-[var(--ink-soft)] cursor-pointer peer-checked:bg-[var(--gold)] peer-checked:text-[var(--night)] peer-checked:border-[var(--gold)]"
                                                   x-text="p.label"></label>
                                            <label :for="`pilihan-${active.id}-${p.id}`"
                                                   class="flex-1 p-4 rounded-xl border border-[var(--line)] bg-[var(--paper)] cursor-pointer transition peer-checked:border-[var(--gold)] peer-checked:bg-[var(--paper-soft)]"
                                                   x-text="p.teks"></label>
                                        </div>
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
                    <div class="flex items-center justify-between gap-3 mt-6 pt-5 border-t border-[var(--line)]">
                        <button type="button"
                                class="neo-btn neo-btn-secondary"
                                @click="goto(current - 1)"
                                :disabled="current === 0">Sebelumnya</button>
                        <button type="button"
                                class="neo-btn"
                                @click="goto(current + 1)"
                                :disabled="current >= soal.length - 1">Berikutnya</button>
                    </div>
                </div>

                <!-- ============ SIDEBAR PALET ============ -->
                <aside class="neo-card self-start">
                    <div class="neo-card-header">
                        <h2 class="neo-card-title">Navigasi Soal</h2>
                    </div>
                    <div class="neo-card-body">
                        <div class="grid grid-cols-3 sm:grid-cols-4 gap-2 mb-5">
                            <template x-for="(s, i) in soal" :key="s.id">
                                <button type="button"
                                        @click="goto(i)"
                                        class="flex h-10 items-center justify-center rounded-xl border text-sm font-mono transition"
                                        :class="[
                                            isAnswered(s)
                                                ? 'bg-[var(--gold)] text-[var(--night)] border-[var(--gold)]'
                                                : 'bg-[var(--paper)] text-[var(--ink-soft)] border-[var(--line)] hover:border-[var(--gold)]',
                                            i === current ? 'ring-2 ring-[var(--gold)]' : ''
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
                            <span class="font-mono text-xs text-[var(--gold)]" x-text="progress + '%'"></span>
                        </div>
                        <div class="h-2 rounded-full bg-[var(--paper-deep)] overflow-hidden">
                            <div class="h-2 rounded-full bg-[var(--gold)] transition-all duration-300" :style="`width: ${progress}%`"></div>
                        </div>

                        <button type="button" class="neo-btn w-full justify-center mt-6" @click="confirmOpen = true">
                            Kumpulkan Jawaban
                        </button>
                    </div>
                </aside>

            </div>
        </div>
    </div>

    <!-- ============ MODAL KONFIRMASI ============ -->
    <div class="neo-modal-backdrop" x-show="confirmOpen" x-cloak @keydown.escape.window="confirmOpen = false">
        <div class="neo-modal neo-modal-sm" @click.outside="confirmOpen = false">
            <div class="neo-modal-header">
                <h3 class="neo-modal-title">Kumpulkan Jawaban?</h3>
                <button type="button" class="neo-modal-close" @click="confirmOpen = false">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="neo-modal-body">
                <p class="text-sm text-[var(--ink-soft)]">
                    Anda telah menjawab <span class="font-mono font-bold text-[var(--ink)]" x-text="answeredCount"></span>
                    dari <span class="font-mono font-bold text-[var(--ink)]" x-text="soal.length"></span> soal.
                    Setelah dikumpulkan, jawaban tidak dapat diubah lagi.
                </p>
                <p class="text-sm text-[var(--ink-soft)] mt-3" x-show="answeredCount < soal.length">
                    <span class="text-[var(--sun-deep)]">Perhatian:</span> masih ada soal yang belum dijawab.
                </p>
            </div>
            <div class="neo-modal-footer">
                <button type="button" class="neo-btn-secondary" @click="confirmOpen = false">Batal</button>
                <button type="button" class="neo-btn" @click="doSubmit(false)" :disabled="submitting">Ya, Kumpulkan</button>
            </div>
        </div>
    </div>

</main>

@once
<script>
    document.addEventListener('alpine:init', () => {        window.Alpine.data('ujian', (soal, answers, essays, lampiran, remaining, urls) => ({
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

            init() {
                this.timer = setInterval(() => this.tick(), 1000);

                document.addEventListener('visibilitychange', () => {
                    if (document.visibilityState === 'hidden') {
                        this.beacon();
                    }
                });

                window.addEventListener('pagehide', () => this.beacon());
            },

            tick() {
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
                if (this.submitting) {
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
