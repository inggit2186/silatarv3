<x-layouts.app title="Laporan Kinerja Bawahan - SILATAR">
    @php
    $sortBy = $sortBy ?? 'status_priority';
    $sortDir = $sortDir ?? 'asc';

    function sortUrl($field, $currentSort, $currentDir, $month) {
        $newDir = ($currentSort === $field && $currentDir === 'asc') ? 'desc' : 'asc';
        return route('laporan-kinerja.bawahan', ['month' => $month, 'sort' => $field, 'dir' => $newDir]);
    }

    function sortIcon($field, $currentSort, $currentDir) {
        if ($currentSort !== $field) {
            return '<svg class="w-4 h-4 opacity-30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>';
        }
        if ($currentDir === 'asc') {
            return '<svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 15l7-7 7 7"/></svg>';
        }
        return '<svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 9l-7 7-7-7"/></svg>';
    }
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

    <main class="neo-mirai silatar-report-page" x-data="bawahanPdfPreview()">
        <x-layouts.site-header />

        <!-- Hero Section -->
        <section class="hero-page bg-cover bg-center" style="background-image: url('/assets/img/template/ckh-bg.webp'); padding: 2rem 2rem 4rem; min-height: 280px;">
            <div class="news-article-container article-hero" style="padding-top: 80px;">
                <p class="section-label-gold section-label-sm">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Laporan Bawahan
                </p>
                <h1 class="article-hero-title">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="var(--gold)" stroke-width="1.5"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Rekap Kinerja Bawahan
                </h1>
                <p class="article-hero-subtitle">Pantau laporan kinerja bulanan staf di unit kerja Anda.</p>
                <div class="hero-actions">
                    <a href="{{ url('/') }}" class="neo-hero-cta">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
                        Beranda
                    </a>
                    <a href="{{ route('laporan-kinerja') }}" class="neo-hero-cta neo-hero-cta-primary">
                        Laporan Saya
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h12m-5-5 5 5-5 5"/></svg>
                    </a>
                </div>
            </div>
        </section>

        <!-- Section Divider -->
        <div class="section-divider wave-rounded"></div>

        <!-- Content Section -->
        <section class="page-content">

        <!-- Tabs Navigation -->
        <x-laporan-kinerja.tabs
            active-tab="bawahan"
            :tab-labels="$tabLabels"
            :selected-month="$selectedMonth"
            :selected-year="date('Y')"
            search=""
            :show-bawahan="true"
        />

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
                            Daftar {{ $reports->count() > 0 ? (($reports->currentPage() - 1) * $reports->perPage() + 1) . '-' . min($reports->currentPage() * $reports->perPage(), $reports->total()) : 0 }} dari {{ $reportStats['total'] }} staf di {{ $deptName ?? 'Unit Kerja' }}.
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
                    <div class="neo-card border-gold">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="signature-icon-wrapper">
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="var(--night)" stroke-width="2">
                                        <path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="signature-title">Pengaturan Tanda Tangan</h3>
                                    <p class="signature-desc">Tanda tangan akan muncul di PDF laporan yang disetujui</p>
                                </div>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" x-model="signatureIsActive" @change="saveSignature()" class="sr-only peer">
                                <div class="toggle-switch peer-checked:bg-[var(--gold)]">
                                    <span class="peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all"></span>
                                </div>
                                <span class="ml-3 text-sm font-medium text-ink" x-text="signatureIsActive ? 'Aktif' : 'Nonaktif'"></span>
                            </label>
                        </div>

                        <div x-show="signatureIsActive" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="report-form-label">Nama</label>
                                <input
                                    type="text"
                                    x-model="signatureName"
                                    class="report-form-input"
                                    placeholder="Nama Lengkap"
                                >
                            </div>
                            <div>
                                <label class="report-form-label">NIP</label>
                                <input
                                    type="text"
                                    x-model="signatureNip"
                                    class="report-form-input"
                                    placeholder="NIP"
                                >
                            </div>
                            <div class="flex items-end">
                                <button
                                    type="button"
                                    @click="saveSignature()"
                                    class="neo-btn w-full"
                                >
                                    Simpan
                                </button>
                            </div>
                        </div>

                        <div x-show="signatureIsActive" class="mt-4">
                            <div class="flex items-center justify-between mb-2">
                                <label class="report-form-label">Gambar Tanda Tangan</label>
                                <button
                                    type="button"
                                    @click="clearSignature()"
                                    class="text-xs transition text-error"
                                >
                                    Clear
                                </button>
                            </div>
                            <div class="signature-canvas-wrapper">
                                <canvas id="signaturePad" class="signature-canvas"></canvas>
                                <p class="signature-placeholder">Gambar tanda tangan di sini</p>
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
                                    <h2 class="mt-2 font-mono text-xl font-bold heading-color">
                                        Monitoring Kinerja Staf
                                    </h2>
                                    <p class="mt-2 max-w-2xl text-sm leading-6 heading-muted">
                                        Laporan bulanan semua staf di unit kerja Anda.
                                    </p>
                                </div>
                            </div>

                            <div class="grid gap-3 sm:grid-cols-4">
                                <div class="report-stat-card report-stat-card-neutral">
                                    <p class="report-stat-card-label">Total Staff</p>
                                    <p class="report-stat-card-value">{{ $reportStats['total'] }}</p>
                                </div>
                                <div class="report-stat-card report-stat-card-approved">
                                    <p class="report-stat-card-label">Disetujui</p>
                                    <p class="report-stat-card-value">{{ $reportStats['disetujui'] }}</p>
                                </div>
                                <div class="report-stat-card report-stat-card-sent">
                                    <p class="report-stat-card-label">Dikirim</p>
                                    <p class="report-stat-card-value">{{ $reportStats['dikirim'] }}</p>
                                </div>
                                <div class="report-stat-card report-stat-card-neutral">
                                    <p class="report-stat-card-label">Belum Upload</p>
                                    <p class="report-stat-card-value">{{ $reportStats['belum_upload'] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="silatar-report-table-shell" id="rekap-bulanan">
                        @if ($reports->isEmpty())
                            <div class="silatar-report-empty report-empty-state">
                                <svg class="mx-auto" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <p class="report-empty-title">Tidak Ada Staff</p>
                                <p class="report-empty-desc">
                                    Tidak ada staff di unit kerja Anda.
                                </p>
                            </div>
                        @else
                            <div class="overflow-x-auto">
                                <table class="silatar-report-table">
                                    <thead>
                                        <tr>
                                            <th class="text-center w-12">No</th>
                                            <th class="text-left">
                                                <a href="{{ sortUrl('nama', $sortBy, $sortDir, $selectedMonth) }}" class="sortable-header {{ $sortBy === 'nama' ? 'is-sorted' : '' }}">
                                                    <span>Nama</span>
                                                    <span class="sort-icon">{!! sortIcon('nama', $sortBy, $sortDir) !!}</span>
                                                </a>
                                            </th>
                                            <th class="text-center">File</th>
                                            <th class="text-center">
                                                <a href="{{ sortUrl('tanggal', $sortBy, $sortDir, $selectedMonth) }}" class="sortable-header {{ $sortBy === 'tanggal' ? 'is-sorted' : '' }}">
                                                    <span>Tanggal Kirim</span>
                                                    <span class="sort-icon">{!! sortIcon('tanggal', $sortBy, $sortDir) !!}</span>
                                                </a>
                                            </th>
                                            <th class="text-center">
                                                <a href="{{ sortUrl('status', $sortBy, $sortDir, $selectedMonth) }}" class="sortable-header {{ $sortBy === 'status' || $sortBy === 'status_priority' ? 'is-sorted' : '' }}">
                                                    <span>Status</span>
                                                    <span class="sort-icon">{!! sortIcon('status', $sortBy, $sortDir) !!}</span>
                                                </a>
                                            </th>
                                            <th class="text-center">Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($reports as $index => $report)
                                            <tr class="{{ !$report['has_report'] ? 'opacity-60' : '' }}">
                                                <td class="bulanan-cell text-center report-table-cell-index">{{ $index + 1 }}</td>
                                                <td class="bulanan-cell">
                                                    <p class="report-table-cell-bold">{{ $report['user_name'] }}</p>
                                                    <p class="report-table-cell-muted">{{ $report['jabatan'] }}</p>
                                                </td>
                                                <td class="bulanan-cell text-center">
                                                    @if($report['has_report'] && $report['filename'])
                                                        <button
                                                            type="button"
                                                            @click="openPdfPreview('/storage/satker_ckh/{{ $report['user_id'] }}/{{ $report['filename'] }}', '{{ $report['user_name'] }} - {{ $report['bulan'] }}', {{ $report['id'] ?? 'null' }}, {{ $report['user_id'] }}, '{{ $report['bulan'] }}')"
                                                            class="report-pdf-btn report-pdf-btn-primary"
                                                        >
                                                            <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                                            PDF
                                                        </button>
                                                    @else
                                                        <span class="report-pdf-btn report-pdf-btn-secondary">-</span>
                                                    @endif
                                                </td>
                                                <td class="report-table-cell-date">
                                                    {{ $report['sending_formatted'] }}
                                                </td>
                                                <td class="bulanan-cell text-center">
                                                    @if($report['status'] === 'DISETUJUI')
                                                        <span class="cyber-status-badge cyber-status-disetujui"><span class="cyber-dot"></span>Disetujui</span>
                                                    @elseif($report['status'] === 'DIKIRIM')
                                                        <span class="cyber-status-badge cyber-status-dikirim"><span class="cyber-dot"></span>Dikirim</span>
                                                    @elseif($report['status'] === 'DITOLAK')
                                                        <span class="cyber-status-badge cyber-status-ditolak"><span class="cyber-dot"></span>Ditolak</span>
                                                    @elseif($report['status'] === 'BELUM_UPLOAD')
                                                        <span class="cyber-status-badge cyber-status-belum"><span class="cyber-dot"></span>Belum Upload</span>
                                                    @else
                                                        <span class="cyber-status-badge cyber-status-belum"><span class="cyber-dot"></span>{{ $report['status'] }}</span>
                                                    @endif
                                                </td>
                                                <td class="bulanan-cell text-center">
                                                    @if($report['alasan'])
                                                        <span class="report-table-cell-note" title="{{ $report['alasan'] }}">
                                                            {{ Str::limit($report['alasan'], 20) }}
                                                        </span>
                                                    @else
                                                        <span class="report-table-cell-muted">-</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            {{-- Pagination --}}
                            <div class="mt-6">
                                <x-ui.neo-pagination :paginator="$reports" />
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
                style="background: oklch(20% 0.015 80 / 0.8); backdrop-filter: blur(8px);"
                @keydown.escape.window="closePdfPreview()"
            >
                <div class="neo-modal" style="max-width: 56rem; width: 100%; max-height: 92vh;">
                    {{-- Header --}}
                    <div class="neo-modal-header">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 rounded-2xl flex items-center justify-center" style="background: var(--gold);">
                                <svg class="w-7 h-7" style="color: var(--night);" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                    <path d="M9 13h6M9 17h4"/>
                                </svg>
                            </div>
                            <div>
                                <span class="neo-modal-badge" style="background: var(--gold); color: var(--night);">
                                    <svg class="neo-modal-badge-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    Preview Laporan
                                </span>
                                <p class="neo-modal-subtitle" x-text="pdfPreviewTitle"></p>
                            </div>
                        </div>
                        <button
                            type="button"
                            @click="closePdfPreview()"
                            class="neo-modal-close"
                        >
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    {{-- Verifikasi Section --}}
                    <div class="px-6 py-5 border-b" style="border-color: var(--line);">
                        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: oklch(94% 0.035 78 / 0.5);">
                                    <svg class="w-5 h-5" style="color: var(--gold);" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-lg" style="color: var(--ink); font-family: var(--font-display);">Verifikasi Laporan</h3>
                                    <p class="text-sm" style="color: var(--ink-soft);">Periksa dan setujui atau tolak laporan kinerja bawahan</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4 w-full sm:w-auto">
                                <button
                                    type="button"
                                    @click="rejectReport()"
                                    :disabled="isProcessing"
                                    class="flex-1 sm:flex-none inline-flex items-center justify-center gap-3 px-8 py-4 rounded-2xl font-semibold text-base transition-all"
                                    style="background: transparent; border: 2px solid #f43f5e; color: #f43f5e;"
                                >
                                    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    Tolak Laporan
                                </button>
                                <button
                                    type="button"
                                    @click="approveReport()"
                                    :disabled="isProcessing"
                                    class="flex-1 sm:flex-none inline-flex items-center justify-center gap-3 px-8 py-4 rounded-2xl font-semibold text-base transition-all"
                                    style="background: var(--gold); color: var(--night);"
                                >
                                    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Setujui Laporan
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- PDF Content --}}
                    <div class="flex-1 overflow-hidden" style="height: calc(92vh - 200px);">
                        <iframe
                            x-show="pdfPreviewUrl"
                            :src="pdfPreviewUrl"
                            class="w-full h-full border-0"
                            title="PDF Preview"
                        ></iframe>
                        <div x-show="!pdfPreviewUrl" class="flex items-center justify-center h-full">
                            <div class="text-center">
                                <svg class="w-16 h-16 mx-auto mb-4" style="color: var(--ink-soft); opacity: 0.5;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <path d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                                <p style="color: var(--ink-soft);">Memuat PDF...</p>
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

        </section>

        <style>
            .sortable-header {
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                color: inherit;
                text-decoration: none;
                transition: color 0.2s;
            }
            .sortable-header:hover {
                color: var(--gold);
            }
            .sortable-header.is-sorted {
                color: var(--gold);
            }
            .sort-icon {
                display: inline-flex;
                align-items: center;
            }
            .report-table-cell-date {
                font-family: var(--font-mono);
                font-size: 0.75rem;
            }
        </style>
    </main>
</x-layouts.app>
