<x-layouts.app title="Laporan Bulanan Madrasah - SILATAR">
    <main class="neo-mirai">
        <!-- Hero Section -->
        <section class="hero-page has-bg-image" style="padding: 140px 2rem 3rem; min-height: 280px;">
            <div style="max-width: 48rem; text-align: center;">
                <p style="color: var(--gold); font-family: var(--font-mono); font-size: 0.65rem; text-transform: uppercase; margin: 0 0 0.75rem; display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Laporan Madrasah
                </p>
                <h1 style="font-family: var(--font-display); font-size: clamp(1.6rem, 4vw, 2.4rem); font-weight: 400; color: var(--ink); margin: 0 0 0.75rem; display: flex; align-items: center; justify-content: center; gap: 0.75rem;">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="var(--gold)" stroke-width="1.5"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    LAPORAN BULANAN
                </h1>
                <p style="color: var(--ink-soft); font-size: 0.95rem; max-width: 32rem; margin: 0 auto;">Input data rekap siswa per kelas dan data siswa mutasi, mengundurkan diri, atau drop out.</p>
            </div>
        </section>

        <!-- Section Divider -->
        <div class="section-divider wave-rounded"></div>

        <!-- Content -->
        <section class="page-content">
            <div class="content-centered">

                <!-- Tab Navigation -->
                <div class="neo-tabs" style="margin-bottom: 2rem;">
                    <a href="{{ route("madrasah.profil") }}" class="neo-tab">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        Profil Madrasah
                    </a>
                    <a href="{{ route("madrasah.pegawai") }}" class="neo-tab">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Pegawai
                    </a>
                    <a href="{{ route("madrasah.guru") }}" class="neo-tab">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        Guru
                    </a>
                    <a href="{{ route("madrasah.laporan-semester") }}" class="neo-tab">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Laporan Semester
                    </a>
                    <a href="{{ route("madrasah.laporan-bulanan") }}" class="neo-tab is-active">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        Laporan Bulanan
                    </a>
                </div>
                <!-- Hero Meta Info -->
                <div class="neo-card neo-card-hero" style="margin-bottom: 2rem;">
                    <div class="hero-heading">
                        <h2>Form Input Laporan Bulanan {{ $madrasahTypeLabel ?? '' }}</h2>
                        <span class="status-badge status-{{ $currentStatus ?? 'draft' }}">{{ $currentStatusLabel ?? 'Draft' }}</span>
                    </div>
                    <div class="hero-meta">
                        <div class="meta-card">
                            <small>Tanggal Submit</small>
                            <strong>{{ $formattedSubmittedAt ?? 'Belum dikirim' }}</strong>
                        </div>
                        <div class="meta-card meta-card-wide">
                            <small>Catatan Admin</small>
                            <strong>{{ $currentAdminNote ?? 'Belum ada catatan admin' }}</strong>
                        </div>
                    </div>
                    <p>Input data rekap siswa per kelas dan data siswa mutasi, mengundurkan diri, atau drop out.</p>
                    <div class="hero-actions">
                        <button type="button" class="neo-btn-action-reset" onclick="resetForm()">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 7h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            Reset Semua
                        </button>
                        <button type="submit" class="neo-btn-action-save" name="action" value="draft">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                            Simpan Draft
                        </button>
                        <button type="submit" class="neo-btn-submit" name="action" value="submit">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                            Kirim Laporan
                        </button>
                    </div>
                </div>

                <form action="{{ route("madrasah.laporan-bulanan.save") }}" method="POST" id="laporanBulananForm">
                @csrf

                <!-- Section 1: Informasi Laporan -->
                <div class="neo-card" style="margin-bottom: 1.5rem;">
                    <div class="neo-card-header">
                        <div class="neo-card-icon" style="background: var(--gold); color: var(--night);">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <div>
                            <h3 class="neo-card-title">A. Informasi Laporan</h3>
                            <p class="neo-card-desc">Periode dan identitas laporan</p>
                        </div>
                    </div>
                    <div class="neo-card-body">
                        <div class="neo-grid-3">
                            <div class="neo-field-group">
                                <label class="neo-field-label">Bulan</label>
                                <select name="bulan_laporan" class="neo-form-select" required>
                                    @foreach(["Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"] as $bulan)
                                        <option value="{{ $bulan }}" {{ ($bulan_laporan ?? "Januari") == $bulan ? "selected" : "" }}>{{ $bulan }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="neo-field-group">
                                <label class="neo-field-label">Tahun</label>
                                <input type="number" name="tahun_laporan" class="neo-form-input" value="{{ $tahun_laporan ?? date("Y") }}" min="2000" max="2100" required>
                            </div>
                            <div class="neo-field-group">
                                <label class="neo-field-label">Tahun Ajaran</label>
                                <select name="tahun_ajaran" class="neo-form-select" required>
                                    @for($y = date("Y") - 2; $y <= date("Y") + 1; $y++)
                                        <option value="{{ $y }}/{{ $y + 1 }}" {{ ($tahun_ajaran ?? "") == ($y."/".($y+1)) ? "selected" : "" }}>{{ $y }}/{{ $y + 1 }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="neo-grid-3">
                            <div class="neo-field-group">
                                <label class="neo-field-label">Semester</label>
                                <select name="semester" class="neo-form-select" required>
                                    <option value="Ganjil" {{ ($semester ?? "Genap") == "Ganjil" ? "selected" : "" }}>Ganjil</option>
                                    <option value="Genap" {{ ($semester ?? "Genap") == "Genap" ? "selected" : "" }}>Genap</option>
                                </select>
                            </div>
                            <div class="neo-field-group">
                                <label class="neo-field-label">Nama Madrasah</label>
                                <input type="text" name="nama_madrasah" class="neo-form-input" value="{{ $nama_madrasah ?? "" }}" readonly>
                            </div>
                            <div class="neo-field-group">
                                <label class="neo-field-label">Rombel (RB)</label>
                                <input type="number" name="rb" class="neo-form-input" value="{{ $rb ?? 0 }}" min="0">
                            </div>
                        </div>
                        <div class="neo-grid-1">
                            <div class="neo-field-group">
                                <label class="neo-field-label">Kantor / Instansi</label>
                                <input type="text" name="office_name" class="neo-form-input" value="{{ $office_name ?? "Kantor Kementerian Agama Kab. Tanah Datar" }}" readonly>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Section 2: Keadaan Siswa per Rombel -->
                <div class="neo-card" style="margin-bottom: 1.5rem;">
                    <div class="neo-card-header">
                        <div class="neo-card-icon" style="background: var(--gold); color: var(--night);">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <div>
                            <h3 class="neo-card-title">B. Keadaan Siswa {{ $madrasahTypeLabel ?? 'Madrasah' }}</h3>
                            <p class="neo-card-desc">Jumlah siswa per rombel dan jenjang</p>
                        </div>
                        <div class="neo-card-actions">
                            <button type="button" class="neo-btn-add" onclick="addRombel()">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14m-7-7h14"/></svg>
                                Tambah Rombel
                            </button>
                        </div>
                    </div>
                    <div class="neo-card-body">
                        <!-- Student Overview Cards -->
                        <div class="neo-stat-grid" style="margin-bottom: 1.5rem;">
                            <div class="neo-stat-card">
                                <div class="neo-stat-icon" style="background: var(--gold); color: var(--night);">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/></svg>
                                </div>
                                <div class="neo-stat-info">
                                    <span class="neo-stat-label">Madrasah</span>
                                    <strong class="neo-stat-value">{{ $nama_madrasah ?? "Belum diisi" }}</strong>
                                    <small>RB {{ $rb ?? 0 }}</small>
                                </div>
                            </div>
                            <div class="neo-stat-card">
                                <div class="neo-stat-icon" style="background: var(--ink); color: var(--paper);">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                </div>
                                <div class="neo-stat-info">
                                    <span class="neo-stat-label">Total Laki-laki</span>
                                    <strong class="neo-stat-value" id="totalLaki">0</strong>
                                </div>
                            </div>
                            <div class="neo-stat-card">
                                <div class="neo-stat-icon" style="background: var(--gold); color: var(--night);">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                </div>
                                <div class="neo-stat-info">
                                    <span class="neo-stat-label">Total Perempuan</span>
                                    <strong class="neo-stat-value" id="totalPerempuan">0</strong>
                                </div>
                            </div>
                            <div class="neo-stat-card highlight">
                                <div class="neo-stat-icon" style="background: var(--success); color: white;">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </div>
                                <div class="neo-stat-info">
                                    <span class="neo-stat-label">Total Siswa</span>
                                    <strong class="neo-stat-value" id="totalSiswa">0</strong>
                                </div>
                            </div>
                        </div>

                        <!-- Rombel Cards Grid -->
                        <div id="rombelContainer">

                        <!-- Sample Rombel Cards for different levels -->
                        <div class="rombel-level-section">
                            <div class="level-header">
                                <div>
                                    <h4>I (Satu)</h4>
                                    <small>3 rombel</small>
                                </div>
                                <div style="display: flex; align-items: center; gap: 1rem;">
                                    <span class="neo-badge neo-badge-primary">I</span>
                                    <button type="button" class="neo-btn-add-sm" onclick="addRombelToLevel('I')">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14m-7-7h14"/></svg>
                                        Tambah
                                    </button>
                                </div>
                            </div>
                            <div class="rombel-cards-grid">
                                <div class="rombel-card" data-level="I" data-code="I.A">
                                    <div class="rombel-card-header">
                                        <div class="rombel-card-title">
                                            <strong>I.A</strong>
                                            <span class="rombel-total">0 siswa</span>
                                        </div>
                                        <button type="button" class="neo-btn-remove-sm" onclick="removeRombel(this)" disabled>
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </div>
                                    <div class="rombel-card-body">
                                        <div class="gender-input-grid">
                                            <label class="gender-input-label">
                                                <span>L</span>
                                                <input type="number" name="studentCounts[I.A][l]" value="0" min="0" class="neo-form-input calc-siswa" data-class="I.A" data-gender="l">
                                            </label>
                                            <label class="gender-input-label">
                                                <span>P</span>
                                                <input type="number" name="studentCounts[I.A][p]" value="0" min="0" class="neo-form-input calc-siswa" data-class="I.A" data-gender="p">
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="rombel-card" data-level="I" data-code="I.B">
                                    <div class="rombel-card-header">
                                        <div class="rombel-card-title">
                                            <strong>I.B</strong>
                                            <span class="rombel-total">0 siswa</span>
                                        </div>
                                        <button type="button" class="neo-btn-remove-sm" onclick="removeRombel(this)">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </div>
                                    <div class="rombel-card-body">
                                        <div class="gender-input-grid">
                                            <label class="gender-input-label">
                                                <span>L</span>
                                                <input type="number" name="studentCounts[I.B][l]" value="0" min="0" class="neo-form-input calc-siswa" data-class="I.B" data-gender="l">
                                            </label>
                                            <label class="gender-input-label">
                                                <span>P</span>
                                                <input type="number" name="studentCounts[I.B][p]" value="0" min="0" class="neo-form-input calc-siswa" data-class="I.B" data-gender="p">
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="rombel-card" data-level="I" data-code="I.C">
                                    <div class="rombel-card-header">
                                        <div class="rombel-card-title">
                                            <strong>I.C</strong>
                                            <span class="rombel-total">0 siswa</span>
                                        </div>
                                        <button type="button" class="neo-btn-remove-sm" onclick="removeRombel(this)">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </div>
                                    <div class="rombel-card-body">
                                        <div class="gender-input-grid">
                                            <label class="gender-input-label">
                                                <span>L</span>
                                                <input type="number" name="studentCounts[I.C][l]" value="0" min="0" class="neo-form-input calc-siswa" data-class="I.C" data-gender="l">
                                            </label>
                                            <label class="gender-input-label">
                                                <span>P</span>
                                                <input type="number" name="studentCounts[I.C][p]" value="0" min="0" class="neo-form-input calc-siswa" data-class="I.C" data-gender="p">
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="level-footer">
                                <div><span>Laki-laki:</span> <strong class="level-total-l">0</strong></div>
                                <div><span>Perempuan:</span> <strong class="level-total-p">0</strong></div>
                                <div><span>Total:</span> <strong class="level-total">0</strong></div>
                            </div>
                        </div>

                        <!-- Level II -->
                        <div class="rombel-level-section">
                            <div class="level-header">
                                <div>
                                    <h4>II (Dua)</h4>
                                    <small>3 rombel</small>
                                </div>
                                <div style="display: flex; align-items: center; gap: 1rem;">
                                    <span class="neo-badge neo-badge-primary">II</span>
                                    <button type="button" class="neo-btn-add-sm" onclick="addRombelToLevel('II')">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14m-7-7h14"/></svg>
                                        Tambah
                                    </button>
                                </div>
                            </div>
                            <div class="rombel-cards-grid">
                                <div class="rombel-card" data-level="II" data-code="II.A">
                                    <div class="rombel-card-header">
                                        <div class="rombel-card-title">
                                            <strong>II.A</strong>
                                            <span class="rombel-total">0 siswa</span>
                                        </div>
                                        <button type="button" class="neo-btn-remove-sm" onclick="removeRombel(this)" disabled>
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </div>
                                    <div class="rombel-card-body">
                                        <div class="gender-input-grid">
                                            <label class="gender-input-label">
                                                <span>L</span>
                                                <input type="number" name="studentCounts[II.A][l]" value="0" min="0" class="neo-form-input calc-siswa" data-class="II.A" data-gender="l">
                                            </label>
                                            <label class="gender-input-label">
                                                <span>P</span>
                                                <input type="number" name="studentCounts[II.A][p]" value="0" min="0" class="neo-form-input calc-siswa" data-class="II.A" data-gender="p">
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="rombel-card" data-level="II" data-code="II.B">
                                    <div class="rombel-card-header">
                                        <div class="rombel-card-title">
                                            <strong>II.B</strong>
                                            <span class="rombel-total">0 siswa</span>
                                        </div>
                                        <button type="button" class="neo-btn-remove-sm" onclick="removeRombel(this)">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </div>
                                    <div class="rombel-card-body">
                                        <div class="gender-input-grid">
                                            <label class="gender-input-label">
                                                <span>L</span>
                                                <input type="number" name="studentCounts[II.B][l]" value="0" min="0" class="neo-form-input calc-siswa" data-class="II.B" data-gender="l">
                                            </label>
                                            <label class="gender-input-label">
                                                <span>P</span>
                                                <input type="number" name="studentCounts[II.B][p]" value="0" min="0" class="neo-form-input calc-siswa" data-class="II.B" data-gender="p">
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="rombel-card" data-level="II" data-code="II.C">
                                    <div class="rombel-card-header">
                                        <div class="rombel-card-title">
                                            <strong>II.C</strong>
                                            <span class="rombel-total">0 siswa</span>
                                        </div>
                                        <button type="button" class="neo-btn-remove-sm" onclick="removeRombel(this)">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </div>
                                    <div class="rombel-card-body">
                                        <div class="gender-input-grid">
                                            <label class="gender-input-label">
                                                <span>L</span>
                                                <input type="number" name="studentCounts[II.C][l]" value="0" min="0" class="neo-form-input calc-siswa" data-class="II.C" data-gender="l">
                                            </label>
                                            <label class="gender-input-label">
                                                <span>P</span>
                                                <input type="number" name="studentCounts[II.C][p]" value="0" min="0" class="neo-form-input calc-siswa" data-class="II.C" data-gender="p">
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="level-footer">
                                <div><span>Laki-laki:</span> <strong class="level-total-l">0</strong></div>
                                <div><span>Perempuan:</span> <strong class="level-total-p">0</strong></div>
                                <div><span>Total:</span> <strong class="level-total">0</strong></div>
                            </div>
                        </div>

                        <!-- Level III -->
                        <div class="rombel-level-section">
                            <div class="level-header">
                                <div>
                                    <h4>III (Tiga)</h4>
                                    <small>3 rombel</small>
                                </div>
                                <div style="display: flex; align-items: center; gap: 1rem;">
                                    <span class="neo-badge neo-badge-primary">III</span>
                                    <button type="button" class="neo-btn-add-sm" onclick="addRombelToLevel('III')">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14m-7-7h14"/></svg>
                                        Tambah
                                    </button>
                                </div>
                            </div>
                            <div class="rombel-cards-grid">
                                <div class="rombel-card" data-level="III" data-code="III.A">
                                    <div class="rombel-card-header">
                                        <div class="rombel-card-title">
                                            <strong>III.A</strong>
                                            <span class="rombel-total">0 siswa</span>
                                        </div>
                                        <button type="button" class="neo-btn-remove-sm" onclick="removeRombel(this)" disabled>
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </div>
                                    <div class="rombel-card-body">
                                        <div class="gender-input-grid">
                                            <label class="gender-input-label">
                                                <span>L</span>
                                                <input type="number" name="studentCounts[III.A][l]" value="0" min="0" class="neo-form-input calc-siswa" data-class="III.A" data-gender="l">
                                            </label>
                                            <label class="gender-input-label">
                                                <span>P</span>
                                                <input type="number" name="studentCounts[III.A][p]" value="0" min="0" class="neo-form-input calc-siswa" data-class="III.A" data-gender="p">
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="rombel-card" data-level="III" data-code="III.B">
                                    <div class="rombel-card-header">
                                        <div class="rombel-card-title">
                                            <strong>III.B</strong>
                                            <span class="rombel-total">0 siswa</span>
                                        </div>
                                        <button type="button" class="neo-btn-remove-sm" onclick="removeRombel(this)">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </div>
                                    <div class="rombel-card-body">
                                        <div class="gender-input-grid">
                                            <label class="gender-input-label">
                                                <span>L</span>
                                                <input type="number" name="studentCounts[III.B][l]" value="0" min="0" class="neo-form-input calc-siswa" data-class="III.B" data-gender="l">
                                            </label>
                                            <label class="gender-input-label">
                                                <span>P</span>
                                                <input type="number" name="studentCounts[III.B][p]" value="0" min="0" class="neo-form-input calc-siswa" data-class="III.B" data-gender="p">
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="rombel-card" data-level="III" data-code="III.C">
                                    <div class="rombel-card-header">
                                        <div class="rombel-card-title">
                                            <strong>III.C</strong>
                                            <span class="rombel-total">0 siswa</span>
                                        </div>
                                        <button type="button" class="neo-btn-remove-sm" onclick="removeRombel(this)">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </div>
                                    <div class="rombel-card-body">
                                        <div class="gender-input-grid">
                                            <label class="gender-input-label">
                                                <span>L</span>
                                                <input type="number" name="studentCounts[III.C][l]" value="0" min="0" class="neo-form-input calc-siswa" data-class="III.C" data-gender="l">
                                            </label>
                                            <label class="gender-input-label">
                                                <span>P</span>
                                                <input type="number" name="studentCounts[III.C][p]" value="0" min="0" class="neo-form-input calc-siswa" data-class="III.C" data-gender="p">
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="level-footer">
                                <div><span>Laki-laki:</span> <strong class="level-total-l">0</strong></div>
                                <div><span>Perempuan:</span> <strong class="level-total-p">0</strong></div>
                                <div><span>Total:</span> <strong class="level-total">0</strong></div>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>


                <!-- Section 3: Data Siswa Mutasi -->
                <div class="neo-card" style="margin-bottom: 1.5rem;">
                    <div class="neo-card-header">
                        <div class="neo-card-icon" style="background: var(--gold); color: var(--night);">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <div>
                            <h3 class="neo-card-title">C. Data Siswa Mutasi / Mengundurkan Diri / DO</h3>
                            <p class="neo-card-desc">Input data siswa yang mutasi, mengundurkan diri, atau drop out</p>
                        </div>
                        <div class="neo-card-actions">
                            <button type="button" class="neo-btn-add" onclick="addMutationRow()">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14m-7-7h14"/></svg>
                                Tambah Baris
                            </button>
                        </div>
                    </div>
                    <div class="neo-card-body">
                        <!-- Mutation Stats -->
                        <div class="neo-stat-grid" style="margin-bottom: 1.5rem;">
                            <div class="neo-stat-card">
                                <div class="neo-stat-info">
                                    <span class="neo-stat-label">Total Data</span>
                                    <strong class="neo-stat-value" id="mutationTotal">0</strong>
                                </div>
                            </div>
                            <div class="neo-stat-card">
                                <div class="neo-stat-info">
                                    <span class="neo-stat-label">Mutasi Masuk</span>
                                    <strong class="neo-stat-value" id="mutationMasuk">0</strong>
                                </div>
                            </div>
                            <div class="neo-stat-card">
                                <div class="neo-stat-info">
                                    <span class="neo-stat-label">Mutasi Keluar</span>
                                    <strong class="neo-stat-value" id="mutationKeluar">0</strong>
                                </div>
                            </div>
                            <div class="neo-stat-card">
                                <div class="neo-stat-info">
                                    <span class="neo-stat-label">DO / Mengundurkan Diri</span>
                                    <strong class="neo-stat-value" id="mutationDO">0</strong>
                                </div>
                            </div>
                        </div>

                        <!-- Mutation Cards Container -->
                        <div id="mutationContainer">
                            <!-- Default 3 mutation rows -->
                            <div class="mutation-card">
                                <div class="mutation-card-header">
                                    <div>
                                        <span class="mutation-card-index">Data 1</span>
                                        <h5>Siswa belum diisi</h5>
                                    </div>
                                    <div class="mutation-card-actions">
                                        <span class="mutation-type-badge">Belum dipilih</span>
                                        <button type="button" class="neo-btn-remove-sm" onclick="removeMutationRow(this)">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </div>
                                </div>
                                <div class="mutation-form-grid">
                                    <div class="neo-field-group span-2">
                                        <label class="neo-field-label">Nama Siswa</label>
                                        <input type="text" name="mutationRows[0][nama_siswa]" class="neo-form-input" placeholder="Nama siswa" onchange="updateMutationCard(this)">
                                    </div>
                                    <div class="neo-field-group">
                                        <label class="neo-field-label">Jenis Data</label>
                                        <select name="mutationRows[0][keterangan]" class="neo-form-select" onchange="updateMutationBadge(this)">
                                            <option value="" disabled selected>Pilih jenis data</option>
                                            <option value="Mutasi Masuk">Mutasi Masuk</option>
                                            <option value="Mutasi Keluar">Mutasi Keluar</option>
                                            <option value="Mengundurkan Diri">Mengundurkan Diri</option>
                                            <option value="DO">Drop Out (DO)</option>
                                        </select>
                                    </div>
                                    <div class="neo-field-group">
                                        <label class="neo-field-label">Kelas</label>
                                        <select name="mutationRows[0][kelas]" class="neo-form-select">
                                            <option value="" disabled selected>Pilih kelas</option>
                                            <option value="I.A">I.A</option>
                                            <option value="I.B">I.B</option>
                                            <option value="I.C">I.C</option>
                                            <option value="II.A">II.A</option>
                                            <option value="II.B">II.B</option>
                                            <option value="II.C">II.C</option>
                                            <option value="III.A">III.A</option>
                                            <option value="III.B">III.B</option>
                                            <option value="III.C">III.C</option>
                                        </select>
                                    </div>
                                    <div class="neo-field-group">
                                        <label class="neo-field-label">NISN</label>
                                        <input type="text" name="mutationRows[0][nisn]" class="neo-form-input" placeholder="NISN">
                                    </div>
                                    <div class="neo-field-group">
                                        <label class="neo-field-label">NIK</label>
                                        <input type="text" name="mutationRows[0][nik]" class="neo-form-input" placeholder="NIK">
                                    </div>
                                    <div class="neo-field-group">
                                        <label class="neo-field-label">Jenis Kelamin</label>
                                        <select name="mutationRows[0][jenis_kelamin]" class="neo-form-select">
                                            <option value="" disabled selected>Pilih</option>
                                            <option value="L">Laki-laki</option>
                                            <option value="P">Perempuan</option>
                                        </select>
                                    </div>
                                    <div class="neo-field-group">
                                        <label class="neo-field-label">Tempat Lahir</label>
                                        <input type="text" name="mutationRows[0][tempat_lahir]" class="neo-form-input" placeholder="Tempat lahir">
                                    </div>
                                    <div class="neo-field-group">
                                        <label class="neo-field-label">Tanggal Lahir</label>
                                        <input type="date" name="mutationRows[0][tanggal_lahir]" class="neo-form-input">
                                    </div>
                                    <div class="neo-field-group span-2">
                                        <label class="neo-field-label">Nama Ibu Kandung</label>
                                        <input type="text" name="mutationRows[0][nama_ibu]" class="neo-form-input" placeholder="Nama ibu kandung">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="neo-form-actions">
                    <button type="button" class="neo-btn-action-reset" onclick="resetForm()">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 7h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Reset Semua
                    </button>
                    <button type="submit" class="neo-btn-action-save" name="action" value="draft">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                        Simpan Draft
                    </button>
                    <button type="submit" class="neo-btn-submit" name="action" value="submit">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        Kirim Laporan
                    </button>
                </div>

                </form>
            </div>
        </section>

        <!-- Additional CSS for this page -->
        <style>
            .neo-card-hero {
                background: linear-gradient(135deg, var(--paper) 0%, var(--paper-light) 100%);
                border: 1px solid var(--line);
            }
            .hero-heading {
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-bottom: 1rem;
            }
            .hero-heading h2 {
                margin: 0;
                font-family: var(--font-display);
                color: var(--ink);
            }
            .hero-meta {
                display: flex;
                gap: 1rem;
                margin-bottom: 1rem;
            }
            .meta-card {
                padding: 0.75rem 1rem;
                background: var(--paper-light);
                border: 1px solid var(--line);
                border-radius: 0.5rem;
                flex: 1;
            }
            .meta-card-wide {
                flex: 2;
            }
            .meta-card small {
                display: block;
                color: var(--ink-soft);
                font-size: 0.75rem;
                margin-bottom: 0.25rem;
            }
            .meta-card strong {
                color: var(--ink);
                font-size: 0.9rem;
            }
            .hero-actions {
                display: flex;
                gap: 0.75rem;
                flex-wrap: wrap;
            }
            .status-badge {
                padding: 0.35rem 0.75rem;
                border-radius: 2rem;
                font-size: 0.7rem;
                font-weight: 600;
                font-family: var(--font-mono);
                text-transform: uppercase;
            }
            .status-draft {
                background: var(--ink-soft);
                color: var(--paper);
            }
            .status-submitted {
                background: var(--info);
                color: white;
            }
            .status-approved {
                background: var(--success);
                color: white;
            }
            .status-rejected {
                background: var(--danger);
                color: white;
            }
            .neo-card-actions {
                margin-left: auto;
            }
            .neo-stat-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
                gap: 1rem;
            }
            .neo-stat-card {
                display: flex;
                align-items: center;
                gap: 0.75rem;
                padding: 1rem;
                background: var(--paper);
                border: 1px solid var(--line);
                border-radius: 0.75rem;
            }
            .neo-stat-card.highlight {
                background: var(--gold);
                border-color: var(--gold);
            }
            .neo-stat-card.highlight .neo-stat-label,
            .neo-stat-card.highlight .neo-stat-value {
                color: var(--night);
            }
            .neo-stat-icon {
                width: 48px;
                height: 48px;
                display: flex;
                align-items: center;
                justify-content: center;
                border-radius: 0.5rem;
            }
            .neo-stat-info {
                flex: 1;
            }
            .neo-stat-label {
                display: block;
                font-size: 0.7rem;
                color: var(--ink-soft);
                margin-bottom: 0.25rem;
            }
            .neo-stat-value {
                font-size: 1.25rem;
                font-weight: 700;
                color: var(--ink);
                font-family: var(--font-display);
            }
            .rombel-cards-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
                gap: 1rem;
            }
            .rombel-card {
                background: var(--paper);
                border: 1px solid var(--line);
                border-radius: 0.75rem;
                overflow: hidden;
            }
            .rombel-card-header {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 0.75rem;
                background: var(--paper-light);
                border-bottom: 1px solid var(--line);
            }
            .rombel-card-title strong {
                display: block;
                color: var(--ink);
                font-family: var(--font-display);
            }
            .rombel-card-title .rombel-total {
                font-size: 0.65rem;
                color: var(--ink-soft);
            }
            .rombel-card-body {
                padding: 0.75rem;
            }
            .gender-input-grid {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 0.5rem;
            }
            .gender-input-label {
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 0.25rem;
            }
            .gender-input-label span {
                font-size: 0.65rem;
                font-weight: 600;
                color: var(--ink-soft);
                text-transform: uppercase;
            }
            .gender-input-label .neo-form-input {
                text-align: center;
                padding: 0.5rem;
                font-weight: 600;
            }
            .neo-btn-add-sm {
                display: inline-flex;
                align-items: center;
                gap: 0.35rem;
                padding: 0.35rem 0.65rem;
                background: var(--gold);
                color: var(--night);
                border: none;
                border-radius: 0.35rem;
                font-size: 0.7rem;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.2s;
            }
            .neo-btn-add-sm:hover {
                background: var(--gold-dark);
            }
            .neo-btn-remove-sm {
                display: flex;
                align-items: center;
                justify-content: center;
                width: 24px;
                height: 24px;
                background: transparent;
                color: var(--ink-soft);
                border: 1px solid var(--line);
                border-radius: 0.35rem;
                cursor: pointer;
                transition: all 0.2s;
            }
            .neo-btn-remove-sm:hover:not(:disabled) {
                background: var(--danger);
                color: white;
                border-color: var(--danger);
            }
            .neo-btn-remove-sm:disabled {
                opacity: 0.3;
                cursor: not-allowed;
            }
            .neo-grid-3 {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 1rem;
                margin-bottom: 1rem;
            }
            .neo-grid-1 {
                margin-bottom: 1rem;
            }
            .neo-field-group {
                display: flex;
                flex-direction: column;
                gap: 0.35rem;
            }
            .neo-field-group.span-2 {
                grid-column: span 2;
            }
            .neo-field-label {
                font-size: 0.75rem;
                font-weight: 600;
                color: var(--ink);
            }
            .neo-form-select {
                padding: 0.6rem 0.75rem;
                border: 1px solid var(--line);
                border-radius: 0.5rem;
                font-size: 0.85rem;
                background: var(--paper);
                color: var(--ink);
                cursor: pointer;
            }
            .mutation-card {
                background: var(--paper);
                border: 1px solid var(--line);
                border-radius: 0.75rem;
                margin-bottom: 1rem;
                overflow: hidden;
            }
            .mutation-card-header {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 1rem;
                background: var(--paper-light);
                border-bottom: 1px solid var(--line);
            }
            .mutation-card-index {
                font-size: 0.65rem;
                font-weight: 600;
                color: var(--gold);
                font-family: var(--font-mono);
                text-transform: uppercase;
            }
            .mutation-card-header h5 {
                margin: 0.25rem 0 0;
                color: var(--ink);
                font-size: 0.95rem;
            }
            .mutation-card-actions {
                display: flex;
                align-items: center;
                gap: 0.75rem;
            }
            .mutation-type-badge {
                padding: 0.25rem 0.65rem;
                background: var(--line);
                color: var(--ink-soft);
                border-radius: 2rem;
                font-size: 0.7rem;
                font-weight: 600;
            }
            .mutation-type-badge.mutasi-masuk {
                background: var(--success);
                color: white;
            }
            .mutation-type-badge.mutasi-keluar {
                background: var(--info);
                color: white;
            }
            .mutation-type-badge.mengundurkan-diri,
            .mutation-type-badge.do {
                background: var(--danger);
                color: white;
            }
            .mutation-form-grid {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 1rem;
                padding: 1rem;
            }
            @media (max-width: 768px) {
                .neo-grid-3 {
                    grid-template-columns: 1fr;
                }
                .neo-field-group.span-2 {
                    grid-column: span 1;
                }
                .mutation-form-grid {
                    grid-template-columns: 1fr;
                }
                .rombel-cards-grid {
                    grid-template-columns: repeat(2, 1fr);
                }
                .hero-meta {
                    flex-direction: column;
                }
            }
            /* Level header styling */
            .level-header {
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-bottom: 1rem;
                padding: 0.75rem 1rem;
                background: var(--line);
                border-radius: 0.5rem;
            }
            .level-header h4 {
                margin: 0;
                color: var(--ink);
                font-family: var(--font-display);
            }
            .level-header small {
                color: var(--ink-soft);
            }
            /* Level footer styling */
            .level-footer {
                display: flex;
                justify-content: flex-end;
                gap: 2rem;
                padding: 0.75rem 1rem;
                background: var(--paper);
                border-radius: 0.5rem;
                margin-top: 0.5rem;
            }
            .level-footer div {
                display: flex;
                gap: 0.5rem;
            }
            .level-footer span {
                color: var(--ink-soft);
            }
            .level-footer .level-total {
                color: var(--gold);
            }
            /* Rombel level section */
            .rombel-level-section {
                margin-bottom: 2rem;
            }
            /* Badge styling */
            .neo-badge {
                display: inline-flex;
                align-items: center;
                padding: 0.25rem 0.5rem;
                border-radius: 0.25rem;
                font-size: 0.65rem;
                font-weight: 600;
                font-family: var(--font-mono);
                text-transform: uppercase;
            }
            .neo-badge-primary {
                background: var(--gold);
                color: var(--night);
            }
        </style>

        <!-- JavaScript for reactive calculations -->
        <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Initial calculation
            calculateAllTotals();

            // Add event listeners to all student inputs
            document.querySelectorAll(".calc-siswa").forEach(input => {
                input.addEventListener("input", function() {
                    updateRombelTotal(this.dataset.class);
                    calculateAllTotals();
                });
            });
        });

        function calculateAllTotals() {
            // Calculate per level
            document.querySelectorAll(".rombel-level-section").forEach(section => {
                const level = section.dataset.level || section.querySelector(".level-header h4")?.textContent;
                let totalL = 0, totalP = 0;

                section.querySelectorAll(".rombel-card").forEach(card => {
                    const lInput = card.querySelector("input[data-gender=\"l\"]");
                    const pInput = card.querySelector("input[data-gender=\"p\"]");
                    totalL += parseInt(lInput?.value) || 0;
                    totalP += parseInt(pInput?.value) || 0;
                });

                const footer = section.querySelector(".level-footer");
                if (footer) {
                    footer.querySelector(".level-total-l").textContent = totalL;
                    footer.querySelector(".level-total-p").textContent = totalP;
                    footer.querySelector(".level-total").textContent = totalL + totalP;
                }
            });

            // Calculate grand totals
            let grandTotalL = 0, grandTotalP = 0;
            document.querySelectorAll(".level-total-l").forEach(el => {
                grandTotalL += parseInt(el.textContent) || 0;
            });
            document.querySelectorAll(".level-total-p").forEach(el => {
                grandTotalP += parseInt(el.textContent) || 0;
            });

            document.getElementById("totalLaki").textContent = grandTotalL;
            document.getElementById("totalPerempuan").textContent = grandTotalP;
            document.getElementById("totalSiswa").textContent = grandTotalL + grandTotalP;

            // Calculate mutation stats
            calculateMutationStats();
        }

        function updateRombelTotal(classCode) {
            const card = document.querySelector(`.rombel-card[data-code="${classCode}"]`);
            if (!card) return;

            const lInput = card.querySelector("input[data-gender=\"l\"]");
            const pInput = card.querySelector("input[data-gender=\"p\"]");
            const totalEl = card.querySelector(".rombel-total");

            const l = parseInt(lInput?.value) || 0;
            const p = parseInt(pInput?.value) || 0;

            if (totalEl) {
                totalEl.textContent = (l + p) + " siswa";
            }
        }

        function addRombelToLevel(prefix) {
            const section = document.querySelector(`.rombel-level-section [data-level="${prefix}"]`)?.closest(".rombel-level-section");
            if (!section) return;

            const grid = section.querySelector(".rombel-cards-grid");
            const cards = grid.querySelectorAll(".rombel-card");
            const nextSuffix = String.fromCharCode(65 + cards.length);
            const newCode = prefix + "." + nextSuffix;

            const newCard = document.createElement("div");
            newCard.className = "rombel-card";
            newCard.dataset.level = prefix;
            newCard.dataset.code = newCode;
            newCard.innerHTML = `
                <div class="rombel-card-header">
                    <div class="rombel-card-title">
                        <strong>${newCode}</strong>
                        <span class="rombel-total">0 siswa</span>
                    </div>
                    <button type="button" class="neo-btn-remove-sm" onclick="removeRombel(this)">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <div class="rombel-card-body">
                    <div class="gender-input-grid">
                        <label class="gender-input-label">
                            <span>L</span>
                            <input type="number" name="studentCounts[${newCode}][l]" value="0" min="0" class="neo-form-input calc-siswa" data-class="${newCode}" data-gender="l">
                        </label>
                        <label class="gender-input-label">
                            <span>P</span>
                            <input type="number" name="studentCounts[${newCode}][p]" value="0" min="0" class="neo-form-input calc-siswa" data-class="${newCode}" data-gender="p">
                        </label>
                    </div>
                </div>
            `;

            grid.appendChild(newCard);

            // Add event listener
            newCard.querySelectorAll(".calc-siswa").forEach(input => {
                input.addEventListener("input", function() {
                    updateRombelTotal(this.dataset.class);
                    calculateAllTotals();
                });
            });

            // Update level footer
            const footer = section.querySelector(".level-footer");
            const small = footer.previousElementSibling.querySelector("small");
            if (small) {
                const count = grid.querySelectorAll(".rombel-card").length;
                small.textContent = count + " rombel";
            }

            calculateAllTotals();
        }

        function removeRombel(btn) {
            const card = btn.closest(".rombel-card");
            const grid = card.closest(".rombel-cards-grid");
            const section = grid.closest(".rombel-level-section");

            if (grid.querySelectorAll(".rombel-card").length <= 1) {
                alert("Minimal harus ada satu rombel!");
                return;
            }

            card.remove();

            // Update level footer
            const footer = section.querySelector(".level-footer");
            const small = footer.previousElementSibling.querySelector("small");
            if (small) {
                const count = grid.querySelectorAll(".rombel-card").length;
                small.textContent = count + " rombel";
            }

            calculateAllTotals();
        }

        let mutationRowIndex = 1;

        function addMutationRow() {
            const container = document.getElementById("mutationContainer");
            const rowCount = container.querySelectorAll(".mutation-card").length;
            const idx = mutationRowIndex++;

            const mutationTypes = ["Mutasi Masuk", "Mutasi Keluar", "Mengundurkan Diri", "DO"];
            let kelasOptions = "";
            ["I.A", "I.B", "I.C", "II.A", "II.B", "II.C", "III.A", "III.B", "III.C"].forEach(k => {
                kelasOptions += `<option value="${k}">${k}</option>`;
            });

            const newCard = document.createElement("div");
            newCard.className = "mutation-card";
            newCard.innerHTML = `
                <div class="mutation-card-header">
                    <div>
                        <span class="mutation-card-index">Data ${rowCount + 1}</span>
                        <h5>Siswa belum diisi</h5>
                    </div>
                    <div class="mutation-card-actions">
                        <span class="mutation-type-badge">Belum dipilih</span>
                        <button type="button" class="neo-btn-remove-sm" onclick="removeMutationRow(this)">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>
                <div class="mutation-form-grid">
                    <div class="neo-field-group span-2">
                        <label class="neo-field-label">Nama Siswa</label>
                        <input type="text" name="mutationRows[${idx}][nama_siswa]" class="neo-form-input" placeholder="Nama siswa" onchange="updateMutationCard(this)">
                    </div>
                    <div class="neo-field-group">
                        <label class="neo-field-label">Jenis Data</label>
                        <select name="mutationRows[${idx}][keterangan]" class="neo-form-select" onchange="updateMutationBadge(this)">
                            <option value="" disabled selected>Pilih jenis data</option>
                            ${mutationTypes.map(t => `<option value="${t}">${t}</option>`).join("")}
                        </select>
                    </div>
                    <div class="neo-field-group">
                        <label class="neo-field-label">Kelas</label>
                        <select name="mutationRows[${idx}][kelas]" class="neo-form-select">
                            <option value="" disabled selected>Pilih kelas</option>
                            ${kelasOptions}
                        </select>
                    </div>
                    <div class="neo-field-group">
                        <label class="neo-field-label">NISN</label>
                        <input type="text" name="mutationRows[${idx}][nisn]" class="neo-form-input" placeholder="NISN">
                    </div>
                    <div class="neo-field-group">
                        <label class="neo-field-label">NIK</label>
                        <input type="text" name="mutationRows[${idx}][nik]" class="neo-form-input" placeholder="NIK">
                    </div>
                    <div class="neo-field-group">
                        <label class="neo-field-label">Jenis Kelamin</label>
                        <select name="mutationRows[${idx}][jenis_kelamin]" class="neo-form-select">
                            <option value="" disabled selected>Pilih</option>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                    <div class="neo-field-group">
                        <label class="neo-field-label">Tempat Lahir</label>
                        <input type="text" name="mutationRows[${idx}][tempat_lahir]" class="neo-form-input" placeholder="Tempat lahir">
                    </div>
                    <div class="neo-field-group">
                        <label class="neo-field-label">Tanggal Lahir</label>
                        <input type="date" name="mutationRows[${idx}][tanggal_lahir]" class="neo-form-input">
                    </div>
                    <div class="neo-field-group span-2">
                        <label class="neo-field-label">Nama Ibu Kandung</label>
                        <input type="text" name="mutationRows[${idx}][nama_ibu]" class="neo-form-input" placeholder="Nama ibu kandung">
                    </div>
                </div>
            `;

            container.appendChild(newCard);
            calculateMutationStats();
        }

        function removeMutationRow(btn) {
            const card = btn.closest(".mutation-card");
            const container = document.getElementById("mutationContainer");

            if (container.querySelectorAll(".mutation-card").length <= 1) {
                alert("Minimal harus ada satu data!");
                return;
            }

            card.remove();

            // Renumber remaining cards
            container.querySelectorAll(".mutation-card").forEach((card, i) => {
                card.querySelector(".mutation-card-index").textContent = "Data " + (i + 1);
            });

            calculateMutationStats();
        }

        function updateMutationCard(input) {
            const card = input.closest(".mutation-card");
            const h5 = card.querySelector(".mutation-card-header h5");
            h5.textContent = input.value || "Siswa belum diisi";
        }

        function updateMutationBadge(select) {
            const card = select.closest(".mutation-card");
            const badge = card.querySelector(".mutation-type-badge");
            const value = select.value;

            badge.className = "mutation-type-badge";
            if (value === "Mutasi Masuk") {
                badge.textContent = "Mutasi Masuk";
                badge.classList.add("mutasi-masuk");
            } else if (value === "Mutasi Keluar") {
                badge.textContent = "Mutasi Keluar";
                badge.classList.add("mutasi-keluar");
            } else if (value === "Mengundurkan Diri") {
                badge.textContent = "Mengundurkan Diri";
                badge.classList.add("mengundurkan-diri");
            } else if (value === "DO") {
                badge.textContent = "Drop Out";
                badge.classList.add("do");
            } else {
                badge.textContent = "Belum dipilih";
            }

            calculateMutationStats();
        }

        function calculateMutationStats() {
            const container = document.getElementById("mutationContainer");
            const cards = container.querySelectorAll(".mutation-card");

            let total = cards.length;
            let masuk = 0, keluar = 0, doCount = 0;

            cards.forEach(card => {
                const select = card.querySelector("select[name*=\"[keterangan]\"]");
                const value = select?.value;
                if (value === "Mutasi Masuk") masuk++;
                else if (value === "Mutasi Keluar") keluar++;
                else if (value === "DO" || value === "Mengundurkan Diri") doCount++;
            });

            document.getElementById("mutationTotal").textContent = total;
            document.getElementById("mutationMasuk").textContent = masuk;
            document.getElementById("mutationKeluar").textContent = keluar;
            document.getElementById("mutationDO").textContent = doCount;
        }

        function resetForm() {
            if (confirm("Yakin ingin mereset semua data? Data yang belum disimpan akan hilang.")) {
                document.getElementById("laporanBulananForm").reset();
                calculateAllTotals();

                // Reset rombel totals display
                document.querySelectorAll(".rombel-total").forEach(el => {
                    el.textContent = "0 siswa";
                });

                // Reset mutation badges
                document.querySelectorAll(".mutation-type-badge").forEach(badge => {
                    badge.textContent = "Belum dipilih";
                    badge.className = "mutation-type-badge";
                });

                // Reset mutation card titles
                document.querySelectorAll(".mutation-card-header h5").forEach(h5 => {
                    h5.textContent = "Siswa belum diisi";
                });

                calculateMutationStats();
            }
        }

        function submitLaporan() {
            if (confirm("Kirim laporan bulanan ini? Laporan tidak dapat diedit setelah dikirim.")) {
                document.querySelector("button[name=\"action\"][value=\"submit\"]").click();
            }
        }
        </script>
    </main>
</x-layouts.app>
