<x-layouts.app title="Laporan Kinerja Bawahan - SILATAR">
    @php
        $selectedMonth = $selectedMonth ?? date('Y-m');
        $selectedMonthLabel = $selectedMonthLabel ?? date('m/Y');
        $userRole = $userRole ?? '';
    @endphp

    <script>
        function bawahanPdfPreview() {
            return {
                pdfPreviewOpen: false,
                pdfPreviewUrl: '',
                pdfPreviewTitle: '',
                currentReportId: null,
                currentUserId: null,
                currentBulan: '',
                isProcessing: false,
                // Toast notifications
                toastMessage: '',
                toastType: 'success',
                toastShow: false,
                showToast(message, type = 'success') {
                    this.toastMessage = message;
                    this.toastType = type;
                    this.toastShow = true;
                    setTimeout(() => {
                        this.toastShow = false;
                    }, 3000);
                },
                // Signature settings
                signatureIsActive: false,
                signatureName: '',
                signatureNip: '',
                signatureData: '',
                signaturePad: null,
                signatureIsLoaded: false,
                init() {
                    this.loadSignature();
                },
                loadSignature() {
                    if (this.signatureIsLoaded) return;
                    fetch('/signature')
                        .then(res => res.json())
                        .then(data => {
                            if (data.success && data.data) {
                                this.signatureName = data.data.signature_name || '';
                                this.signatureNip = data.data.nip || '';
                                this.signatureData = data.data.signature_image || '';
                                this.signatureIsActive = data.data.is_active || false;
                            }
                            this.signatureIsLoaded = true;
                            this.initSignaturePad();
                        })
                        .catch(err => {
                            console.error('Failed to load signature:', err);
                            this.signatureIsLoaded = true;
                            this.initSignaturePad();
                        });
                },
                initSignaturePad() {
                    if (this.signaturePad) return;

                    const canvas = document.getElementById('signaturePad');
                    if (!canvas) return;

                    this.signaturePad = canvas.getContext('2d');

                    // Set canvas size
                    canvas.width = 400;
                    canvas.height = 150;

                    // Set drawing style
                    this.signaturePad.strokeStyle = '#1e40af';
                    this.signaturePad.lineWidth = 2;
                    this.signaturePad.lineCap = 'round';
                    this.signaturePad.lineJoin = 'round';

                    // Load existing signature if available
                    if (this.signatureData) {
                        const img = new Image();
                        img.onload = () => {
                            this.signaturePad.drawImage(img, 0, 0);
                        };
                        img.src = this.signatureData;
                    }

                    // Drawing state
                    let isDrawing = false;
                    let lastX = 0;
                    let lastY = 0;

                    // Get position relative to canvas
                    const getPosition = (e) => {
                        const rect = canvas.getBoundingClientRect();
                        const scaleX = canvas.width / rect.width;
                        const scaleY = canvas.height / rect.height;

                        if (e.touches) {
                            return {
                                x: (e.touches[0].clientX - rect.left) * scaleX,
                                y: (e.touches[0].clientY - rect.top) * scaleY
                            };
                        }
                        return {
                            x: (e.clientX - rect.left) * scaleX,
                            y: (e.clientY - rect.top) * scaleY
                        };
                    };

                    // Mouse events
                    canvas.addEventListener('mousedown', (e) => {
                        isDrawing = true;
                        const pos = getPosition(e);
                        lastX = pos.x;
                        lastY = pos.y;
                    });

                    canvas.addEventListener('mousemove', (e) => {
                        if (!isDrawing) return;
                        const pos = getPosition(e);
                        this.signaturePad.beginPath();
                        this.signaturePad.moveTo(lastX, lastY);
                        this.signaturePad.lineTo(pos.x, pos.y);
                        this.signaturePad.stroke();
                        lastX = pos.x;
                        lastY = pos.y;
                    });

                    canvas.addEventListener('mouseup', () => isDrawing = false);
                    canvas.addEventListener('mouseout', () => isDrawing = false);

                    // Touch events
                    canvas.addEventListener('touchstart', (e) => {
                        e.preventDefault();
                        isDrawing = true;
                        const pos = getPosition(e);
                        lastX = pos.x;
                        lastY = pos.y;
                    });

                    canvas.addEventListener('touchmove', (e) => {
                        e.preventDefault();
                        if (!isDrawing) return;
                        const pos = getPosition(e);
                        this.signaturePad.beginPath();
                        this.signaturePad.moveTo(lastX, lastY);
                        this.signaturePad.lineTo(pos.x, pos.y);
                        this.signaturePad.stroke();
                        lastX = pos.x;
                        lastY = pos.y;
                    });

                    canvas.addEventListener('touchend', () => isDrawing = false);
                },
                clearSignature() {
                    if (!this.signaturePad) return;
                    const canvas = document.getElementById('signaturePad');
                    this.signaturePad.clearRect(0, 0, canvas.width, canvas.height);
                    this.signatureData = '';
                },
                saveSignature() {
                    const canvas = document.getElementById('signaturePad');
                    if (canvas) {
                        this.signatureData = canvas.toDataURL('image/png');
                    }

                    const formData = new FormData();
                    formData.append('signature_name', this.signatureName);
                    formData.append('signature_image', this.signatureData);
                    formData.append('nip', this.signatureNip);
                    formData.append('is_active', this.signatureIsActive ? '1' : '0');
                    formData.append('_token', document.querySelector('meta[name="csrf-token"]')?.content || '');

                    fetch('/signature', {
                        method: 'POST',
                        body: formData
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            this.signatureIsActive = data.is_active === true;
                            this.showToast('Tanda tangan berhasil disimpan!', 'success');
                        } else {
                            this.showToast(data.message || 'Gagal menyimpan tanda tangan', 'error');
                        }
                    })
                    .catch(err => {
                        this.showToast('Terjadi kesalahan: ' + err.message, 'error');
                    });
                },
                openPdfPreview(url, title, reportId, userId, bulan) {
                    this.pdfPreviewUrl = url;
                    this.pdfPreviewTitle = title || 'Preview PDF';
                    this.currentReportId = reportId;
                    this.currentUserId = userId;
                    this.currentBulan = bulan;
                    this.pdfPreviewOpen = true;
                },
                closePdfPreview() {
                    this.pdfPreviewOpen = false;
                    this.pdfPreviewUrl = '';
                    this.pdfPreviewTitle = '';
                    this.currentReportId = null;
                    this.currentUserId = null;
                    this.currentBulan = '';
                },
                approveReport() {
                    if (this.isProcessing || !this.currentUserId) return;
                    this.isProcessing = true;

                    const formData = new FormData();
                    formData.append('user_id', this.currentUserId);
                    formData.append('bulan', this.currentBulan);
                    formData.append('action', 'approve');
                    formData.append('_token', document.querySelector('meta[name="csrf-token"]')?.content || '');

                    fetch('/laporan-kinerja/verify', {
                        method: 'POST',
                        body: formData
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            this.closePdfPreview();
                            window.location.reload();
                        } else {
                            this.showToast(data.message || 'Gagal menyetujui laporan', 'error');
                            this.isProcessing = false;
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        this.showToast('Terjadi kesalahan server. Silakan coba lagi.', 'error');
                        this.isProcessing = false;
                    });
                },
                rejectReport() {
                    if (this.isProcessing || !this.currentUserId) return;
                    if (!confirm('Yakin ingin menolak laporan ini?')) return;

                    const alasan = prompt('Masukkan alasan penolakan (opsional):');
                    // User cancelled prompt
                    if (alasan === null) return;

                    this.isProcessing = true;

                    const formData = new FormData();
                    formData.append('user_id', this.currentUserId);
                    formData.append('bulan', this.currentBulan);
                    formData.append('action', 'reject');
                    formData.append('alasan', alasan);
                    formData.append('_token', document.querySelector('meta[name="csrf-token"]')?.content || '');

                    fetch('/laporan-kinerja/verify', {
                        method: 'POST',
                        body: formData
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            this.closePdfPreview();
                            window.location.reload();
                        } else {
                            this.showToast(data.message || 'Gagal menolak laporan', 'error');
                            this.isProcessing = false;
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        this.showToast('Terjadi kesalahan server. Silakan coba lagi.', 'error');
                        this.isProcessing = false;
                    });
                },
            };
        }
    </script>

    <main class="silatar-report-page space-y-6" x-data="bawahanPdfPreview()">
        <!-- Page Header -->
        <div class="bg-gradient-to-b from-slate-950 via-slate-900 to-slate-950 py-8">
            <div class="mx-auto max-w-6xl px-6 lg:px-8 text-center">
                <span class="inline-flex items-center gap-2 rounded-full border border-cyan-500/30 bg-cyan-500/10 px-4 py-1.5 font-mono text-xs font-semibold uppercase tracking-widest text-cyan-400">
                    <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Laporan Bawahan
                </span>
                <h1 class="mt-4 font-mono text-3xl font-black uppercase tracking-wider text-white lg:text-4xl">
                    Rekap Kinerja Bawahan
                </h1>
                <p class="mt-2 text-sm text-slate-400">Pantau laporan kinerja bulanan staf di unit kerja Anda</p>
            </div>
        </div>

        @if($error)
            <div class="mx-auto max-w-6xl px-6 lg:px-8">
                <div class="rounded-2xl border border-rose-500/30 bg-rose-500/10 p-6 text-center">
                    <svg class="mx-auto h-12 w-12 text-rose-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <p class="mt-4 font-semibold text-rose-300">{{ $error }}</p>
                    <a href="{{ route('home') }}" class="mt-4 inline-block rounded-lg bg-rose-600 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-700">
                        Kembali ke Beranda
                    </a>
                </div>
            </div>
        @else
            <section class="silatar-report-shell">
                <div class="silatar-report-shell-header">
                    <div class="min-w-0">
                        <p class="font-mono text-xs font-semibold uppercase tracking-widest text-cyan-400">Laporan Kinerja Bulanan</p>
                        <h1 class="silatar-report-title">
                            Laporan Bawahan {{ $selectedMonthLabel }}
                        </h1>
                        <p class="silatar-report-subtitle">
                            Daftar laporan bulanan dari {{ $totalUsers }} staf di {{ $deptName ?? 'Unit Kerja' }}.
                        </p>
                    </div>

                    <form method="GET" action="{{ route('laporan-kinerja.bawahan') }}" class="silatar-report-filter">
                        <div class="flex w-full flex-col gap-3 sm:flex-row sm:items-center sm:justify-end">
                            <x-ui.monthpicker
                                name="month"
                                :value="$selectedMonth"
                                placeholder="Pilih bulan"
                            />
                        </div>
                    </form>
                </div>

                {{-- Signature Settings Section --}}
                <div class="mx-auto max-w-6xl px-6 lg:px-8 -mt-4">
                    <div class="rounded-2xl border border-amber-500/30 bg-gradient-to-r from-amber-500/5 to-slate-900/80 p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-amber-500/20 border border-amber-500/30 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-amber-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-mono text-sm font-semibold text-amber-400">Pengaturan Tanda Tangan</h3>
                                    <p class="text-xs text-slate-400">Tanda tangan akan muncul di PDF laporan yang disetujui</p>
                                </div>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" x-model="signatureIsActive" @change="saveSignature()" class="sr-only peer">
                                <div class="w-11 h-6 bg-slate-600 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-cyan-500"></div>
                                <span class="ml-3 text-sm font-medium text-slate-300" x-text="signatureIsActive ? 'Aktif' : 'Nonaktif'"></span>
                            </label>
                        </div>

                        <div x-show="signatureIsActive" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block font-mono text-xs uppercase tracking-wider text-cyan-400 mb-2">Nama</label>
                                <input
                                    type="text"
                                    x-model="signatureName"
                                    class="w-full rounded-lg border border-cyan-500/30 bg-slate-800 px-4 py-2.5 text-white font-mono text-sm"
                                    placeholder="Nama Lengkap"
                                >
                            </div>
                            <div>
                                <label class="block font-mono text-xs uppercase tracking-wider text-cyan-400 mb-2">NIP</label>
                                <input
                                    type="text"
                                    x-model="signatureNip"
                                    class="w-full rounded-lg border border-cyan-500/30 bg-slate-800 px-4 py-2.5 text-white font-mono text-sm"
                                    placeholder="NIP"
                                >
                            </div>
                            <div class="flex items-end">
                                <button
                                    type="button"
                                    @click="saveSignature()"
                                    class="w-full rounded-lg bg-gradient-to-r from-cyan-600 to-cyan-500 px-4 py-2.5 font-mono text-sm font-semibold text-white shadow-[0_0_20px_rgba(0,212,255,0.3)] hover:from-cyan-500 hover:to-cyan-400 transition"
                                >
                                    Simpan
                                </button>
                            </div>
                        </div>

                        <div x-show="signatureIsActive" class="mt-4">
                            <div class="flex items-center justify-between mb-2">
                                <label class="font-mono text-xs uppercase tracking-wider text-cyan-400">Gambar Tanda Tangan</label>
                                <button
                                    type="button"
                                    @click="clearSignature()"
                                    class="text-xs text-rose-400 hover:text-rose-300 transition"
                                >
                                    Clear
                                </button>
                            </div>
                            <div class="relative rounded-lg border border-cyan-500/30 bg-white overflow-hidden">
                                <canvas id="signaturePad" class="w-full cursor-crosshair touch-none" style="max-width: 100%; height: 150px;"></canvas>
                                <p class="absolute bottom-2 right-2 text-xs text-slate-400">Gambar tanda tangan di sini</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-5">
                    <div class="silatar-report-summary">
                        <div class="silatar-report-summary-header">
                            <div class="flex items-start gap-4">
                                <div class="silatar-report-summary-icon">
                                    <svg class="h-7 w-7" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="silatar-report-summary-badge silatar-report-summary-badge-ready">
                                        {{ $selectedMonthLabel }}
                                    </div>
                                    <h2 class="mt-2 font-mono text-xl font-bold text-white">
                                        Monitoring Kinerja Staf
                                    </h2>
                                    <p class="mt-2 max-w-2xl text-sm leading-6 text-cyan-100">
                                        Laporan bulanan semua staf di unit kerja Anda.
                                    </p>
                                </div>
                            </div>

                            <div class="grid gap-3 sm:grid-cols-4">
                                <div class="rounded-xl border border-cyan-500/20 bg-slate-900/80 px-4 py-3">
                                    <p class="font-mono text-xs font-semibold uppercase tracking-widest text-cyan-100">Total Laporan</p>
                                    <p class="mt-2 font-mono text-2xl font-bold text-cyan-400">{{ $reports->count() }}</p>
                                </div>
                                <div class="rounded-xl border border-emerald-500/30 bg-emerald-500/10 px-4 py-3">
                                    <p class="font-mono text-xs font-semibold uppercase tracking-widest text-emerald-400">Disetujui</p>
                                    <p class="mt-2 font-mono text-2xl font-bold text-emerald-400">{{ $reports->where('status', 'DISETUJUI')->count() }}</p>
                                </div>
                                <div class="rounded-xl border border-amber-500/30 bg-amber-500/10 px-4 py-3">
                                    <p class="font-mono text-xs font-semibold uppercase tracking-widest text-amber-400">Dikirim</p>
                                    <p class="mt-2 font-mono text-2xl font-bold text-amber-400">{{ $reports->where('status', 'DIKIRIM')->count() }}</p>
                                </div>
                                <div class="rounded-xl border border-rose-500/30 bg-rose-500/10 px-4 py-3">
                                    <p class="font-mono text-xs font-semibold uppercase tracking-widest text-rose-400">Ditolak</p>
                                    <p class="mt-2 font-mono text-2xl font-bold text-rose-400">{{ $reports->where('status', 'DITOLAK')->count() }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="silatar-report-table-shell" id="rekap-bulanan">
                        @if ($reports->isEmpty())
                            <div class="silatar-report-empty">
                                <svg class="mx-auto mb-4 h-16 w-16 text-cyan-500/30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <p class="font-mono text-sm font-semibold uppercase tracking-widest text-cyan-400/50">Kosong</p>
                                <p class="mt-2 font-mono text-base font-semibold text-white">Belum ada laporan kinerja bulanan dari bawahan.</p>
                                <p class="mt-2 text-sm leading-6 text-cyan-100">
                                    Tidak ada data laporan bulanan pada bulan {{ $selectedMonthLabel }}.
                                </p>
                            </div>
                        @else
                            <div class="overflow-x-auto">
                                <table class="silatar-report-table">
                                    <thead>
                                        <tr>
                                            <th class="text-center">No</th>
                                            <th class="text-left">Nama</th>
                                            <th class="text-center">File</th>
                                            <th class="text-center">Tanggal Kirim</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($reports as $index => $report)
                                            <tr>
                                                <td class="bulanan-cell text-center font-mono text-cyan-400 font-bold">{{ $index + 1 }}</td>
                                                <td class="bulanan-cell">
                                                    <p class="font-semibold text-white">{{ $report['user_name'] }}</p>
                                                    <p class="text-xs text-cyan-100/70">{{ $report['jabatan'] }}</p>
                                                </td>
                                                <td class="bulanan-cell text-center">
                                                    @if($report['filename'])
                                                        <button
                                                            type="button"
                                                            @click="openPdfPreview('/storage/satker_ckh/{{ $report['user_id'] }}/{{ $report['filename'] }}', '{{ $report['user_name'] }} - {{ $report['bulan'] }}', {{ $report['id'] ?? 'null' }}, {{ $report['user_id'] }}, '{{ $report['bulan'] }}')"
                                                            class="inline-flex items-center gap-2 rounded-full border border-cyan-500/30 bg-cyan-500/10 px-3 py-1 font-mono text-xs text-white hover:bg-cyan-500/20 hover:border-cyan-500/50 transition cursor-pointer"
                                                        >
                                                            <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                                            PDF
                                                        </button>
                                                    @else
                                                        <span class="inline-flex items-center gap-2 rounded-full border border-slate-500/30 bg-slate-500/10 px-3 py-1 font-mono text-xs text-slate-400">
                                                            -
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="text-center font-mono text-xs text-cyan-100">
                                                    {{ $report['sending_formatted'] }}
                                                </td>
                                                <td class="bulanan-cell text-center">
                                                    @if($report['status'] === 'DISETUJUI')
                                                        <span class="cyber-status-badge cyber-status-disetujui"><span class="cyber-dot"></span>Disetujui</span>
                                                    @elseif($report['status'] === 'DIKIRIM')
                                                        <span class="cyber-status-badge cyber-status-dikirim"><span class="cyber-dot"></span>Dikirim</span>
                                                    @elseif($report['status'] === 'DITOLAK')
                                                        <span class="cyber-status-badge cyber-status-ditolak"><span class="cyber-dot"></span>Ditolak</span>
                                                    @else
                                                        <span class="cyber-status-badge cyber-status-belum"><span class="cyber-dot"></span>Belum Kirim</span>
                                                    @endif
                                                </td>
                                                <td class="bulanan-cell text-center">
                                                    @if($report['alasan'])
                                                        <span class="text-xs text-rose-300" title="{{ $report['alasan'] }}">
                                                            {{ Str::limit($report['alasan'], 20) }}
                                                        </span>
                                                    @else
                                                        <span class="text-xs text-slate-500">-</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </section>

            {{-- PDF Preview Modal --}}
            <div
                x-show="pdfPreviewOpen"
                x-cloak
                class="fixed inset-0 z-50 flex items-center justify-center p-4"
                style="background: rgba(0,0,0,0.85); backdrop-filter: blur(4px);"
                @keydown.escape.window="closePdfPreview()"
            >
                <div class="relative w-full max-w-5xl h-[90vh] bg-gradient-to-b from-slate-900 to-slate-950 border border-cyan-500/30 rounded-2xl shadow-[0_0_60px_rgba(0,212,255,0.3)] overflow-hidden flex flex-col">
                    {{-- Header --}}
                    <div class="flex items-center justify-between px-6 py-4 border-b border-cyan-500/30 bg-slate-900/80">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-cyan-500/20 border border-cyan-500/30 flex items-center justify-center">
                                <svg class="w-5 h-5 text-cyan-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                    <path d="M9 13h6M9 17h4"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-mono text-sm font-semibold text-cyan-400">Preview Laporan</h3>
                                <p class="text-xs text-slate-400" x-text="pdfPreviewTitle"></p>
                            </div>
                        </div>
                        <button
                            type="button"
                            @click="closePdfPreview()"
                            class="rounded-full bg-slate-700/50 p-2 text-slate-400 hover:text-white hover:bg-slate-600 transition"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    {{-- Action Buttons --}}
                    <div class="flex items-center justify-between px-6 py-3 border-b border-cyan-500/20 bg-slate-900/50">
                        <p class="text-xs text-slate-400">Verifikasi laporan kinerja bawahan</p>
                        <div class="flex items-center gap-3">
                            <button
                                type="button"
                                @click="rejectReport()"
                                :disabled="isProcessing"
                                class="inline-flex items-center gap-2 rounded-full border border-rose-500/30 bg-rose-500/10 px-4 py-2 font-mono text-xs font-semibold text-rose-400 hover:bg-rose-500/20 hover:border-rose-500/50 transition disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Tolak
                            </button>
                            <button
                                type="button"
                                @click="approveReport()"
                                :disabled="isProcessing"
                                class="inline-flex items-center gap-2 rounded-full border border-emerald-500/30 bg-emerald-500/10 px-4 py-2 font-mono text-xs font-semibold text-emerald-400 hover:bg-emerald-500/20 hover:border-emerald-500/50 transition disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                                Setuju
                            </button>
                        </div>
                    </div>
                    {{-- PDF Content --}}
                    <div class="flex-1 overflow-hidden bg-slate-800/50">
                        <iframe
                            x-show="pdfPreviewUrl"
                            :src="pdfPreviewUrl"
                            class="w-full h-full border-0"
                            title="PDF Preview"
                        ></iframe>
                        <div x-show="!pdfPreviewUrl" class="flex items-center justify-center h-full">
                            <div class="text-center">
                                <svg class="w-16 h-16 mx-auto text-slate-600 mb-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <path d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                                <p class="text-slate-400">Memuat PDF...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Toast Notification --}}
            <div
                x-show="toastShow"
                x-cloak
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 translate-y-4"
                class="fixed bottom-4 right-4 z-[100] max-w-md"
            >
                <div
                    :class="toastType === 'success' ? 'bg-emerald-600' : 'bg-rose-600'"
                    class="rounded-lg px-6 py-4 shadow-lg flex items-center gap-3"
                >
                    <svg x-show="toastType === 'success'" class="w-5 h-5 text-white" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 01-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <svg x-show="toastType === 'error'" class="w-5 h-5 text-white" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-white text-sm font-medium" x-text="toastMessage"></p>
                </div>
            </div>
        @endif
    </main>
</x-layouts.app>
