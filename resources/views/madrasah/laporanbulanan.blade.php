<x-layouts.app title="Laporan Bulanan Madrasah - SILATAR">
    <main class="neo-mirai madrasah-bulanan madrasah-fullwidth">
        <!-- Hero Section -->
        <section class="hero-page has-bg-image">
            <div class="hero-content-wrapper">
                <div class="hero-badge">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Laporan Madrasah
                </div>
                <h1 class="hero-title">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="var(--gold)" stroke-width="1.5"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    LAPORAN BULANAN
                </h1>
                <p class="hero-subtitle">Input data rekap siswa per kelas dan data siswa mutasi, mengundurkan diri, atau drop out</p>
            </div>
        </section>

        <!-- Section Divider -->
        <div class="section-divider wave-rounded"></div>

        <!-- Content -->
        <section class="page-content page-content-expanded">
            <!-- Tab Navigation - Large & Prominent -->
            <div class="neo-tabs neo-tabs-large" role="tablist">
                <a href="{{ route('madrasah.profil') }}" class="neo-tab" role="tab">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="22" height="22"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    <span>Profil</span>
                </a>
                <a href="{{ route('madrasah.pegawai') }}" class="neo-tab" role="tab">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="22" height="22"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span>Pegawai</span>
                </a>
                <a href="{{ route('madrasah.guru') }}" class="neo-tab" role="tab">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="22" height="22"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    <span>Guru</span>
                </a>
                <a href="{{ route('madrasah.laporan-semester') }}" class="neo-tab" role="tab">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="22" height="22"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>Semester</span>
                </a>
                <a href="{{ route('madrasah.laporan-bulanan') }}" class="neo-tab is-active" role="tab">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="22" height="22"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span>Bulanan</span>
                </a>
            </div>

            <div class="content-inner">

                <!-- Hero Meta Info Card -->
                <div class="neo-card info-hero-card">
                    <div class="info-hero-header">
                        <div class="info-hero-title-row">
                            <h2>
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--gold)" stroke-width="2"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                Form Input Laporan Bulanan {{ $madrasahTypeLabel ?? '' }}
                            </h2>
                            <span class="status-pill status-{{ $currentStatus ?? 'draft' }}">{{ $currentStatusLabel ?? 'Draft' }}</span>
                        </div>
                    </div>
                    <div class="info-hero-meta">
                        <div class="meta-item">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                            <div>
                                <small>Tanggal Submit</small>
                                <strong>{{ $formattedSubmittedAt ?? 'Belum dikirim' }}</strong>
                            </div>
                        </div>
                        <div class="meta-item meta-wide">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            <div>
                                <small>Catatan Admin</small>
                                <strong>{{ $currentAdminNote ?? 'Belum ada catatan admin' }}</strong>
                            </div>
                        </div>
                    </div>
                    <p class="info-hero-desc">Input data rekap siswa per kelas dan data siswa mutasi, mengundurkan diri, atau drop out.</p>
                    <div class="info-hero-actions">
                        <button type="button" class="btn-action-secondary" onclick="resetForm()">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            Reset Semua
                        </button>
                        <button type="submit" class="btn-action-save" name="action" value="draft">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                            Simpan Draft
                        </button>
                        <button type="submit" class="btn-action-primary" name="action" value="submit">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                            Kirim Laporan
                        </button>
                    </div>
                </div>

                <form action="{{ route('madrasah.laporan-bulanan.save') }}" method="POST" id="laporanBulananForm">
                @csrf

                <!-- Section 1: Informasi Laporan -->
                <div class="neo-card section-card">
                    <div class="neo-card-header">
                        <div class="neo-card-icon" style="background: var(--gold); color: var(--night);">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <div class="neo-card-text">
                            <h3 class="neo-card-title">A. Informasi Laporan</h3>
                            <p class="neo-card-desc">Periode dan identitas laporan</p>
                        </div>
                    </div>
                    <div class="neo-card-body">
                        <div class="form-grid form-grid-3">
                            <div class="neo-field-group">
                                <label class="neo-field-label">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                    Bulan
                                </label>
                                <select name="bulan_laporan" class="neo-form-select" required>
                                    @foreach(["Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"] as $bulan)
                                        <option value="{{ $bulan }}" {{ ($bulan_laporan ?? "Januari") == $bulan ? "selected" : "" }}>{{ $bulan }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="neo-field-group">
                                <label class="neo-field-label">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                    Tahun
                                </label>
                                <input type="number" name="tahun_laporan" class="neo-form-input" value="{{ $tahun_laporan ?? date("Y") }}" min="2000" max="2100" required>
                            </div>
                            <div class="neo-field-group">
                                <label class="neo-field-label">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                    Tahun Ajaran
                                </label>
                                <select name="tahun_ajaran" class="neo-form-select" required>
                                    @for($y = date("Y") - 2; $y <= date("Y") + 1; $y++)
                                        <option value="{{ $y }}/{{ $y + 1 }}" {{ ($tahun_ajaran ?? "") == ($y."/".($y+1)) ? "selected" : "" }}>{{ $y }}/{{ $y + 1 }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="neo-field-group">
                                <label class="neo-field-label">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    Semester
                                </label>
                                <select name="semester" class="neo-form-select" required>
                                    <option value="Ganjil" {{ ($semester ?? "Genap") == "Ganjil" ? "selected" : "" }}>Ganjil</option>
                                    <option value="Genap" {{ ($semester ?? "Genap") == "Genap" ? "selected" : "" }}>Genap</option>
                                </select>
                            </div>
                            <div class="neo-field-group">
                                <label class="neo-field-label">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/></svg>
                                    Nama Madrasah
                                </label>
                                <input type="text" name="nama_madrasah" class="neo-form-input" value="{{ $nama_madrasah ?? "" }}" readonly>
                            </div>
                            <div class="neo-field-group">
                                <label class="neo-field-label">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    Rombel (RB)
                                </label>
                                <input type="number" name="rb" class="neo-form-input" value="{{ $rb ?? 0 }}" min="0">
                            </div>
                        </div>
                        <div class="form-full mt-4">
                            <label class="neo-field-label">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                Kantor / Instansi
                            </label>
                            <input type="text" name="office_name" class="neo-form-input" value="{{ $office_name ?? "Kantor Kementerian Agama Kab. Tanah Datar" }}" readonly>
                        </div>
                    </div>
                </div>
                <!-- Section 2: Keadaan Siswa per Rombel -->
                <div class="neo-card section-card">
                    <div class="neo-card-header">
                        <div class="neo-card-icon" style="background: var(--gold); color: var(--night);">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <div class="neo-card-text">
                            <h3 class="neo-card-title">B. Keadaan Siswa {{ $madrasahTypeLabel ?? 'Madrasah' }}</h3>
                            <p class="neo-card-desc">Jumlah siswa per rombel dan jenjang</p>
                        </div>
                        <button type="button" class="neo-btn-add" onclick="addRombel()">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14m-7-7h14"/></svg>
                            Tambah Rombel
                        </button>
                    </div>
                    <div class="neo-card-body">
                        <!-- Student Overview Cards -->
                        <div class="stat-grid">
                            <div class="stat-card">
                                <div class="stat-icon" style="background: var(--gold); color: var(--night);">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/></svg>
                                </div>
                                <div class="stat-info">
                                    <span class="stat-label">Madrasah</span>
                                    <strong class="stat-value">{{ $nama_madrasah ?? "Belum diisi" }}</strong>
                                    <small>RB {{ $rb ?? 0 }}</small>
                                </div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-icon" style="background: var(--ink); color: var(--paper);">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                </div>
                                <div class="stat-info">
                                    <span class="stat-label">Total Laki-laki</span>
                                    <strong class="stat-value" id="totalLaki">0</strong>
                                </div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-icon" style="background: var(--gold); color: var(--night);">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                </div>
                                <div class="stat-info">
                                    <span class="stat-label">Total Perempuan</span>
                                    <strong class="stat-value" id="totalPerempuan">0</strong>
                                </div>
                            </div>
                            <div class="stat-card stat-highlight">
                                <div class="stat-icon" style="background: var(--success); color: white;">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </div>
                                <div class="stat-info">
                                    <span class="stat-label">Total Siswa</span>
                                    <strong class="stat-value" id="totalSiswa">0</strong>
                                </div>
                            </div>
                        </div>

                        <!-- Rombel Cards Grid -->
                        <div id="rombelContainer">

                        @php
                        // Default levels configuration
                        $defaultLevels = [
                            ['prefix' => 'I', 'name' => 'I (Satu)'],
                            ['prefix' => 'II', 'name' => 'II (Dua)'],
                            ['prefix' => 'III', 'name' => 'III (Tiga)'],
                        ];
                        @endphp

                        @foreach($defaultLevels as $level)
                        @php
                        $prefix = $level['prefix'];
                        $levelName = $level['name'];

                        // Get existing codes from studentCounts or use defaults
                        $existingCodes = [];
                        if(isset($studentCounts) && is_array($studentCounts)) {
                            foreach($studentCounts as $code => $data) {
                                if(strpos($code, $prefix . '.') === 0) {
                                    $existingCodes[] = $code;
                                }
                            }
                        }
                        $classes = !empty($existingCodes) ? $existingCodes : [$prefix . '.A', $prefix . '.B', $prefix . '.C'];
                        @endphp

                        <div class="rombel-level-section" data-level="{{ $prefix }}">
                            <div class="level-header">
                                <div class="level-title">
                                    <h4>{{ $levelName }}</h4>
                                    <span class="level-count">{{ count($classes) }} rombel</span>
                                </div>
                                <div class="level-actions">
                                    <span class="level-badge">{{ $prefix }}</span>
                                    <button type="button" class="neo-btn-add-sm" onclick="addRombelToLevel('{{ $prefix }}')">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14m-7-7h14"/></svg>
                                        Tambah
                                    </button>
                                </div>
                            </div>
                            <div class="rombel-cards-grid">
                                @foreach($classes as $code)
                                @php
                                $lVal = isset($studentCounts[$code]['l']) ? (int)$studentCounts[$code]['l'] : 0;
                                $pVal = isset($studentCounts[$code]['p']) ? (int)$studentCounts[$code]['p'] : 0;
                                $total = $lVal + $pVal;
                                @endphp
                                <div class="rombel-card" data-level="{{ $prefix }}" data-code="{{ $code }}">
                                    <div class="rombel-card-header">
                                        <div class="rombel-card-title">
                                            <strong>{{ $code }}</strong>
                                            <span class="rombel-total">{{ $total }} siswa</span>
                                        </div>
                                        <button type="button" class="neo-btn-remove-sm" onclick="removeRombel(this)">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </div>
                                    <div class="rombel-card-body">
                                        <div class="gender-input-grid">
                                            <label class="gender-input-label">
                                                <span>L</span>
                                                <input type="number" name="studentCounts[{{ $code }}][l]" value="{{ $lVal }}" min="0" class="neo-form-input calc-siswa" data-class="{{ $code }}" data-gender="l">
                                            </label>
                                            <label class="gender-input-label">
                                                <span>P</span>
                                                <input type="number" name="studentCounts[{{ $code }}][p]" value="{{ $pVal }}" min="0" class="neo-form-input calc-siswa" data-class="{{ $code }}" data-gender="p">
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <div class="level-footer">
                                <div><span>Laki-laki:</span> <strong class="level-total-l">0</strong></div>
                                <div><span>Perempuan:</span> <strong class="level-total-p">0</strong></div>
                                <div class="level-total-all"><span>Total:</span> <strong class="level-total">0</strong></div>
                            </div>
                        </div>
                        @endforeach
                        </div>

                <!-- Section 3: Data Siswa Mutasi -->
                <div class="neo-card section-card">
                    <div class="neo-card-header">
                        <div class="neo-card-icon" style="background: var(--gold); color: var(--night);">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                        </div>
                        <div class="neo-card-text">
                            <h3 class="neo-card-title">C. Data Siswa Mutasi / Mengundurkan Diri / DO</h3>
                            <p class="neo-card-desc">Input data siswa yang mutasi, mengundurkan diri, atau drop out</p>
                        </div>
                        <button type="button" class="neo-btn-add" onclick="addMutationRow()">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14m-7-7h14"/></svg>
                            Tambah Baris
                        </button>
                    </div>
                    <div class="neo-card-body">
                        <!-- Mutation Stats -->
                        <div class="stat-grid stat-grid-sm">
                            <div class="stat-card">
                                <div class="stat-info">
                                    <span class="stat-label">Total Data</span>
                                    <strong class="stat-value" id="mutationTotal">0</strong>
                                </div>
                            </div>
                            <div class="stat-card stat-success">
                                <div class="stat-info">
                                    <span class="stat-label">Mutasi Masuk</span>
                                    <strong class="stat-value" id="mutationMasuk">0</strong>
                                </div>
                            </div>
                            <div class="stat-card stat-info-bg">
                                <div class="stat-info">
                                    <span class="stat-label">Mutasi Keluar</span>
                                    <strong class="stat-value" id="mutationKeluar">0</strong>
                                </div>
                            </div>
                            <div class="stat-card stat-warning">
                                <div class="stat-info">
                                    <span class="stat-label">DO / Mengundurkan Diri</span>
                                    <strong class="stat-value" id="mutationDO">0</strong>
                                </div>
                            </div>
                        </div>

                                                <!-- Mutation Cards Container -->
                        <div id="mutationContainer">

                        @php
                        // Get mutation rows from DB or default empty array
                        $mutationRowsData = $mutationRows ?? [];
                        @endphp

                        @if(count($mutationRowsData) > 0)
                            @foreach($mutationRowsData as $idx => $row)
                            @php
                            $badgeClass = '';
                            $badgeText = 'Belum dipilih';
                            if(isset($row['keterangan'])) {
                                if($row['keterangan'] == 'Mutasi Masuk') { $badgeClass = 'mutasi-masuk'; $badgeText = 'Mutasi Masuk'; }
                                elseif($row['keterangan'] == 'Mutasi Keluar') { $badgeClass = 'mutasi-keluar'; $badgeText = 'Mutasi Keluar'; }
                                elseif($row['keterangan'] == 'Mengundurkan Diri') { $badgeClass = 'mengundurkan-diri'; $badgeText = 'Mengundurkan Diri'; }
                                elseif($row['keterangan'] == 'DO') { $badgeClass = 'do'; $badgeText = 'Drop Out'; }
                            }
                            @endphp
                            <div class="mutation-card">
                                <div class="mutation-card-header">
                                    <div>
                                        <span class="mutation-card-index">Data {{ $idx + 1 }}</span>
                                        <h5>{{ $row['nama_siswa'] ?? 'Siswa belum diisi' }}</h5>
                                    </div>
                                    <div class="mutation-card-actions">
                                        <span class="mutation-type-badge {{ $badgeClass }}">{{ $badgeText }}</span>
                                        <button type="button" class="neo-btn-remove-sm" onclick="removeMutationRow(this)">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </div>
                                </div>
                                <div class="mutation-form-grid">
                                    <div class="neo-field-group span-2">
                                        <label class="neo-field-label">Nama Siswa</label>
                                        <input type="text" name="mutationRows[{{ $idx }}][nama_siswa]" value="{{ $row['nama_siswa'] ?? '' }}" class="neo-form-input" placeholder="Nama siswa" onchange="updateMutationCard(this)">
                                    </div>
                                    <div class="neo-field-group">
                                        <label class="neo-field-label">Jenis Data</label>
                                        <select name="mutationRows[{{ $idx }}][keterangan]" class="neo-form-select" onchange="updateMutationBadge(this)">
                                            <option value="" disabled>Pilih jenis data</option>
                                            <option value="Mutasi Masuk" {{ ($row['keterangan'] ?? '') == 'Mutasi Masuk' ? 'selected' : '' }}>Mutasi Masuk</option>
                                            <option value="Mutasi Keluar" {{ ($row['keterangan'] ?? '') == 'Mutasi Keluar' ? 'selected' : '' }}>Mutasi Keluar</option>
                                            <option value="Mengundurkan Diri" {{ ($row['keterangan'] ?? '') == 'Mengundurkan Diri' ? 'selected' : '' }}>Mengundurkan Diri</option>
                                            <option value="DO" {{ ($row['keterangan'] ?? '') == 'DO' ? 'selected' : '' }}>Drop Out (DO)</option>
                                        </select>
                                    </div>
                                    <div class="neo-field-group">
                                        <label class="neo-field-label">Kelas</label>
                                        <select name="mutationRows[{{ $idx }}][kelas]" class="neo-form-select">
                                            <option value="" disabled>Pilih kelas</option>
                                            <option value="I.A" {{ ($row['kelas'] ?? '') == 'I.A' ? 'selected' : '' }}>I.A</option>
                                            <option value="I.B" {{ ($row['kelas'] ?? '') == 'I.B' ? 'selected' : '' }}>I.B</option>
                                            <option value="I.C" {{ ($row['kelas'] ?? '') == 'I.C' ? 'selected' : '' }}>I.C</option>
                                            <option value="II.A" {{ ($row['kelas'] ?? '') == 'II.A' ? 'selected' : '' }}>II.A</option>
                                            <option value="II.B" {{ ($row['kelas'] ?? '') == 'II.B' ? 'selected' : '' }}>II.B</option>
                                            <option value="II.C" {{ ($row['kelas'] ?? '') == 'II.C' ? 'selected' : '' }}>II.C</option>
                                            <option value="III.A" {{ ($row['kelas'] ?? '') == 'III.A' ? 'selected' : '' }}>III.A</option>
                                            <option value="III.B" {{ ($row['kelas'] ?? '') == 'III.B' ? 'selected' : '' }}>III.B</option>
                                            <option value="III.C" {{ ($row['kelas'] ?? '') == 'III.C' ? 'selected' : '' }}>III.C</option>
                                        </select>
                                    </div>
                                    <div class="neo-field-group">
                                        <label class="neo-field-label">NISN</label>
                                        <input type="text" name="mutationRows[{{ $idx }}][nisn]" value="{{ $row['nisn'] ?? '' }}" class="neo-form-input" placeholder="NISN">
                                    </div>
                                    <div class="neo-field-group">
                                        <label class="neo-field-label">NIK</label>
                                        <input type="text" name="mutationRows[{{ $idx }}][nik]" value="{{ $row['nik'] ?? '' }}" class="neo-form-input" placeholder="NIK">
                                    </div>
                                    <div class="neo-field-group">
                                        <label class="neo-field-label">Jenis Kelamin</label>
                                        <select name="mutationRows[{{ $idx }}][jenis_kelamin]" class="neo-form-select">
                                            <option value="" disabled selected>Pilih</option>
                                            <option value="L" {{ ($row['jenis_kelamin'] ?? '') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                            <option value="P" {{ ($row['jenis_kelamin'] ?? '') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                        </select>
                                    </div>
                                    <div class="neo-field-group">
                                        <label class="neo-field-label">Tempat Lahir</label>
                                        <input type="text" name="mutationRows[{{ $idx }}][tempat_lahir]" value="{{ $row['tempat_lahir'] ?? '' }}" class="neo-form-input" placeholder="Tempat lahir">
                                    </div>
                                    <div class="neo-field-group">
                                        <label class="neo-field-label">Tanggal Lahir</label>
                                        <input type="date" name="mutationRows[{{ $idx }}][tanggal_lahir]" value="{{ $row['tanggal_lahir'] ?? '' }}" class="neo-form-input">
                                    </div>
                                    <div class="neo-field-group span-2">
                                        <label class="neo-field-label">Nama Ibu Kandung</label>
                                        <input type="text" name="mutationRows[{{ $idx }}][nama_ibu]" value="{{ $row['nama_ibu'] ?? '' }}" class="neo-form-input" placeholder="Nama ibu kandung">
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        @else
                            <!-- Empty state - show one default row -->
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
                        @endif
                        </div>

                <!-- Action Buttons -->
                <div class="form-actions-bottom">
                    <button type="button" class="btn-action-secondary" onclick="resetForm()">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        Reset Semua
                    </button>
                    <button type="submit" class="btn-action-save" name="action" value="draft">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                        Simpan Draft
                    </button>
                    <button type="submit" class="btn-action-primary" name="action" value="submit">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        Kirim Laporan
                    </button>
                </div>

                </form>
            </div>
            </div>
        </section>

        <!-- Additional CSS for this page -->
        <style>
            /* ============================================
               Madrasah Bulanan Page Styles
               ============================================ */

            /* Madrasah Full Width Layout */
            .madrasah-fullwidth .page-content {
                padding: 0;
                max-width: none;
            }

            .madrasah-fullwidth .page-content-expanded {
                padding: 0;
            }

            .madrasah-fullwidth .content-inner {
                padding: 2rem;
                max-width: 100%;
                margin: 0 auto;
            }

            /* Large Tabs Navigation */
            .neo-tabs-large {
                display: flex;
                gap: 0;
                padding: 1rem 2rem;
                background: var(--paper);
                border-bottom: 2px solid var(--line);
                justify-content: center;
                flex-wrap: wrap;
                margin-bottom: 0;
            }

            .neo-tabs-large .neo-tab {
                display: flex;
                align-items: center;
                gap: 0.75rem;
                padding: 1rem 1.5rem;
                font-family: var(--font-display);
                font-size: 0.95rem;
                font-weight: 600;
                color: var(--ink-soft);
                border-radius: 0.5rem;
                margin: 0 0.25rem;
                transition: all 200ms var(--ease);
                text-decoration: none;
            }

            .neo-tabs-large .neo-tab:hover {
                color: var(--ink);
                background: var(--paper-soft);
            }

            .neo-tabs-large .neo-tab.is-active {
                color: var(--gold);
                background: oklch(68% 0.145 74 / 0.1);
            }

            .neo-tabs-large .neo-tab svg {
                flex-shrink: 0;
            }

            .neo-tabs-large .neo-tab span {
                white-space: nowrap;
            }

            /* Space between tabs and content */
            .neo-tabs-large {
                margin-bottom: 1.5rem;
            }

            /* Hero Content Wrapper */
            .madrasah-bulanan .hero-content-wrapper {
                max-width: 48rem;
                text-align: center;
            }

            .madrasah-bulanan .hero-badge {
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                padding: 0.5rem 1rem;
                background: oklch(68% 0.145 74 / 0.15);
                color: var(--gold);
                font-family: var(--font-mono);
                font-size: 0.7rem;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                border-radius: 2rem;
                margin-bottom: 1rem;
            }

            .madrasah-bulanan .hero-badge svg {
                opacity: 0.8;
            }

            .madrasah-bulanan .hero-title {
                font-family: var(--font-display);
                font-size: clamp(1.8rem, 4vw, 2.5rem);
                font-weight: 300;
                color: var(--ink);
                margin: 0 0 0.75rem;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 0.75rem;
                line-height: 1.2;
                text-transform: uppercase;
                letter-spacing: 0.05em;
            }

            .madrasah-bulanan .hero-title svg {
                flex-shrink: 0;
                filter: drop-shadow(0 2px 8px oklch(50% 0.15 50 / 0.3));
            }

            .madrasah-bulanan .hero-subtitle {
                color: var(--ink-soft);
                font-size: 1rem;
                max-width: 36rem;
                margin: 0 auto;
                line-height: 1.6;
            }

            /* Info Hero Card */
            .madrasah-bulanan .info-hero-card {
                background: linear-gradient(135deg, var(--paper-soft) 0%, oklch(94% 0.035 78 / 0.5) 100%);
                border: 1px solid oklch(68% 0.145 74 / 0.2);
                margin-bottom: 2rem;
            }

            .info-hero-header {
                margin-bottom: 1rem;
            }

            .info-hero-title-row {
                display: flex;
                align-items: center;
                justify-content: space-between;
                flex-wrap: wrap;
                gap: 1rem;
            }

            .info-hero-title-row h2 {
                display: flex;
                align-items: center;
                gap: 0.75rem;
                margin: 0;
                font-family: var(--font-display);
                font-size: 1.25rem;
                color: var(--ink);
            }

            .info-hero-title-row h2 svg {
                flex-shrink: 0;
                color: var(--gold);
            }

            .status-pill {
                display: inline-flex;
                align-items: center;
                padding: 0.4rem 1rem;
                border-radius: 2rem;
                font-size: 0.7rem;
                font-weight: 600;
                font-family: var(--font-mono);
                text-transform: uppercase;
                letter-spacing: 0.05em;
            }

            .status-pill.status-draft {
                background: var(--ink-soft);
                color: var(--paper);
            }

            .status-pill.status-submitted {
                background: var(--info);
                color: white;
            }

            .status-pill.status-approved {
                background: var(--success);
                color: white;
            }

            .status-pill.status-rejected {
                background: var(--danger);
                color: white;
            }

            .info-hero-meta {
                display: flex;
                gap: 1rem;
                margin-bottom: 1rem;
                flex-wrap: wrap;
            }

            .meta-item {
                display: flex;
                align-items: flex-start;
                gap: 0.75rem;
                padding: 0.75rem 1rem;
                background: var(--paper);
                border: 1px solid var(--line);
                border-radius: 0.5rem;
                flex: 1;
                min-width: 200px;
            }

            .meta-item.meta-wide {
                flex: 2;
            }

            .meta-item svg {
                flex-shrink: 0;
                color: var(--gold);
                margin-top: 0.1rem;
            }

            .meta-item small {
                display: block;
                font-size: 0.7rem;
                color: var(--ink-soft);
                margin-bottom: 0.2rem;
            }

            .meta-item strong {
                font-size: 0.9rem;
                color: var(--ink);
            }

            .info-hero-desc {
                color: var(--ink-soft);
                font-size: 0.9rem;
                margin: 0 0 1.5rem;
                line-height: 1.5;
            }

            .info-hero-actions {
                display: flex;
                gap: 0.75rem;
                flex-wrap: wrap;
            }

            /* Section Card */
            .madrasah-bulanan .section-card {
                margin-bottom: 1.5rem;
                transition: box-shadow 200ms var(--ease), border-color 200ms var(--ease);
            }

            .madrasah-bulanan .section-card:hover {
                box-shadow: 0 8px 30px oklch(18% 0.03 76 / 0.1);
                border-color: var(--gold);
            }

            /* Form Grid */
            .form-grid {
                display: grid;
                gap: 1rem;
            }

            .form-grid-3 {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
                gap: 1rem;
            }

            .form-full {
                grid-column: 1 / -1;
            }

            /* Neo Field Group */
            .neo-field-group {
                display: flex;
                flex-direction: column;
                gap: 0.5rem;
            }

            .neo-field-group.span-2 {
                grid-column: span 2;
            }

            /* Neo Field Label */
            .neo-field-label {
                display: flex;
                align-items: center;
                gap: 0.5rem;
                font-family: var(--font-display);
                font-size: 0.85rem;
                font-weight: 600;
                color: var(--ink);
            }

            .neo-field-label svg {
                flex-shrink: 0;
                opacity: 0.6;
                color: var(--gold);
            }

            /* Neo Form Select */
            .neo-form-select {
                width: 100%;
                padding: 0.75rem 1rem;
                padding-right: 2.5rem;
                background: var(--paper);
                border: 1px solid var(--line);
                border-radius: 0.5rem;
                font-family: var(--font-mono);
                font-size: 0.85rem;
                color: var(--ink);
                cursor: pointer;
                appearance: none;
                background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%23666' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
                background-repeat: no-repeat;
                background-position: right 0.75rem center;
                transition: border-color 180ms, box-shadow 180ms;
            }

            .neo-form-select:focus {
                outline: none;
                border-color: var(--gold);
                box-shadow: 0 0 0 3px oklch(68% 0.145 74 / 0.15);
            }

            /* Neo Form Input */
            .neo-form-input {
                width: 100%;
                padding: 0.75rem 1rem;
                background: var(--paper);
                border: 1px solid var(--line);
                border-radius: 0.5rem;
                font-family: var(--font-mono);
                font-size: 0.85rem;
                color: var(--ink);
                transition: border-color 180ms, box-shadow 180ms;
            }

            .neo-form-input:focus {
                outline: none;
                border-color: var(--gold);
                box-shadow: 0 0 0 3px oklch(68% 0.145 74 / 0.15);
            }

            .neo-form-input::placeholder {
                color: var(--ink-soft);
                opacity: 0.6;
            }

            .neo-form-input[readonly] {
                background: oklch(68% 0.145 74 / 0.05);
                cursor: not-allowed;
            }

            /* Stat Grid */
            .stat-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
                gap: 1rem;
                margin-bottom: 1.5rem;
            }

            .stat-grid-sm {
                grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            }

            .stat-card {
                display: flex;
                align-items: center;
                gap: 0.75rem;
                padding: 1rem;
                background: var(--paper);
                border: 1px solid var(--line);
                border-radius: 0.75rem;
                transition: border-color 180ms, box-shadow 180ms;
            }

            .stat-card:hover {
                border-color: var(--gold);
            }

            .stat-card.stat-highlight {
                background: var(--gold);
                border-color: var(--gold);
            }

            .stat-card.stat-highlight .stat-label,
            .stat-card.stat-highlight .stat-value,
            .stat-card.stat-highlight small {
                color: var(--night);
            }

            .stat-card.stat-success {
                border-color: var(--success);
                background: oklch(65% 0.15 145 / 0.05);
            }

            .stat-card.stat-success .stat-value {
                color: var(--success);
            }

            .stat-card.stat-info-bg {
                border-color: var(--info);
                background: oklch(8% 0.15 190 / 0.05);
            }

            .stat-card.stat-info-bg .stat-value {
                color: var(--info);
            }

            .stat-card.stat-warning {
                border-color: var(--danger);
                background: oklch(60% 0.2 25 / 0.05);
            }

            .stat-card.stat-warning .stat-value {
                color: var(--danger);
            }

            .stat-icon {
                width: 48px;
                height: 48px;
                display: flex;
                align-items: center;
                justify-content: center;
                border-radius: 0.5rem;
                flex-shrink: 0;
            }

            .stat-info {
                flex: 1;
                min-width: 0;
            }

            .stat-label {
                display: block;
                font-size: 0.7rem;
                color: var(--ink-soft);
                margin-bottom: 0.2rem;
                line-height: 1.2;
            }

            .stat-value {
                font-size: 1.25rem;
                font-weight: 700;
                color: var(--ink);
                font-family: var(--font-display);
                line-height: 1.2;
            }

            .stat-info small {
                display: block;
                font-size: 0.7rem;
                color: var(--ink-soft);
                margin-top: 0.2rem;
            }

            /* Rombel Cards Grid */
            .rombel-cards-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
                gap: 1rem;
            }

            .rombel-card {
                background: var(--paper);
                border: 1px solid var(--line);
                border-radius: 0.75rem;
                overflow: hidden;
                transition: border-color 180ms, box-shadow 180ms, transform 240ms;
            }

            .rombel-card:hover {
                border-color: var(--gold);
                box-shadow: 0 4px 16px oklch(18% 0.03 76 / 0.1);
                transform: translateY(-2px);
            }

            .rombel-card-header {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 0.75rem 1rem;
                background: var(--paper-soft);
                border-bottom: 1px solid var(--line);
            }

            .rombel-card-title strong {
                display: block;
                color: var(--ink);
                font-family: var(--font-display);
                font-size: 0.95rem;
            }

            .rombel-card-title .rombel-total {
                font-size: 0.7rem;
                color: var(--ink-soft);
            }

            .rombel-card-body {
                padding: 0.75rem;
            }

            /* Gender Input Grid */
            .gender-input-grid {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 0.5rem;
            }

            .gender-input-label {
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 0.3rem;
            }

            .gender-input-label span {
                font-size: 0.7rem;
                font-weight: 700;
                color: var(--ink-soft);
                text-transform: uppercase;
                font-family: var(--font-mono);
            }

            .gender-input-label .neo-form-input {
                text-align: center;
                padding: 0.5rem;
                font-weight: 700;
                font-family: var(--font-mono);
            }

            /* Level Header */
            .level-header {
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-bottom: 1rem;
                padding: 0.75rem 1rem;
                background: var(--paper);
                border: 1px solid var(--line);
                border-radius: 0.5rem;
            }

            .level-title h4 {
                margin: 0;
                color: var(--ink);
                font-family: var(--font-display);
                font-size: 1rem;
            }

            .level-count {
                font-size: 0.75rem;
                color: var(--ink-soft);
            }

            .level-actions {
                display: flex;
                align-items: center;
                gap: 0.75rem;
            }

            .level-badge {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 32px;
                height: 32px;
                background: var(--gold);
                color: var(--night);
                font-family: var(--font-mono);
                font-size: 0.8rem;
                font-weight: 700;
                border-radius: 0.5rem;
            }

            /* Level Footer */
            .level-footer {
                display: flex;
                justify-content: flex-end;
                gap: 1.5rem;
                padding: 0.75rem 1rem;
                background: var(--paper);
                border-radius: 0.5rem;
                margin-top: 0.75rem;
                border: 1px solid var(--line);
            }

            .level-footer div {
                display: flex;
                gap: 0.5rem;
                align-items: center;
            }

            .level-footer span {
                color: var(--ink-soft);
                font-size: 0.8rem;
            }

            .level-footer strong {
                color: var(--ink);
                font-family: var(--font-mono);
            }

            .level-footer .level-total-all strong {
                color: var(--gold);
            }

            /* Rombel Level Section */
            .rombel-level-section {
                margin-bottom: 2rem;
            }

            /* Mutation Card */
            .mutation-card {
                background: var(--paper);
                border: 1px solid var(--line);
                border-radius: 0.75rem;
                margin-bottom: 1rem;
                overflow: hidden;
                transition: border-color 180ms, box-shadow 180ms;
            }

            .mutation-card:hover {
                border-color: var(--gold);
                box-shadow: 0 4px 16px oklch(18% 0.03 76 / 0.08);
            }

            .mutation-card-header {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 1rem;
                background: var(--paper-soft);
                border-bottom: 1px solid var(--line);
                flex-wrap: wrap;
                gap: 0.75rem;
            }

            .mutation-card-index {
                font-size: 0.65rem;
                font-weight: 700;
                color: var(--gold);
                font-family: var(--font-mono);
                text-transform: uppercase;
                letter-spacing: 0.05em;
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
                padding: 0.3rem 0.75rem;
                background: var(--line);
                color: var(--ink-soft);
                border-radius: 2rem;
                font-size: 0.7rem;
                font-weight: 600;
                font-family: var(--font-mono);
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

            /* Mutation Form Grid */
            .mutation-form-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
                gap: 1rem;
                padding: 1rem;
            }

            .mutation-form-grid .span-2 {
                grid-column: span 2;
            }

            /* Neo Button Add Small */
            .neo-btn-add-sm {
                display: inline-flex;
                align-items: center;
                gap: 0.35rem;
                padding: 0.4rem 0.75rem;
                background: var(--gold);
                color: var(--night);
                border: none;
                border-radius: 0.35rem;
                font-size: 0.7rem;
                font-weight: 600;
                font-family: var(--font-mono);
                cursor: pointer;
                transition: all 180ms var(--ease);
            }

            .neo-btn-add-sm:hover {
                background: var(--gold-dark);
                transform: translateY(-1px);
            }

            /* Neo Button Remove Small */
            .neo-btn-remove-sm {
                display: flex;
                align-items: center;
                justify-content: center;
                width: 28px;
                height: 28px;
                background: transparent;
                color: var(--ink-soft);
                border: 1px solid var(--line);
                border-radius: 0.35rem;
                cursor: pointer;
                transition: all 180ms var(--ease);
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

            /* Form Actions Bottom */
            .form-actions-bottom {
                display: flex;
                justify-content: flex-end;
                gap: 1rem;
                padding: 1.5rem 0;
                margin-top: 1rem;
                border-top: 1px solid var(--line);
                flex-wrap: wrap;
            }

            /* Button Actions */
            .btn-action-secondary {
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                padding: 0.875rem 1.5rem;
                background: transparent;
                color: var(--ink);
                font-family: var(--font-mono);
                font-size: 0.75rem;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                border: 1px solid var(--line);
                border-radius: 0.5rem;
                cursor: pointer;
                transition: all 200ms var(--ease);
            }

            .btn-action-secondary:hover {
                border-color: var(--ink);
                color: var(--ink);
                background: var(--paper-soft);
            }

            .btn-action-save {
                display: inline-flex;
                align-items: center;
                gap: 0.75rem;
                padding: 0.875rem 2rem;
                background: var(--gold);
                color: var(--night);
                font-family: var(--font-mono);
                font-size: 0.8rem;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                border: none;
                border-radius: 0.5rem;
                cursor: pointer;
                transition: all 200ms var(--ease);
            }

            .btn-action-save:hover {
                background: var(--gold-dark);
                transform: translateY(-2px);
                box-shadow: 0 8px 24px oklch(50% 0.15 50 / 0.25);
            }

            .btn-action-primary {
                display: inline-flex;
                align-items: center;
                gap: 0.75rem;
                padding: 0.875rem 2rem;
                background: var(--ink);
                color: var(--paper);
                font-family: var(--font-mono);
                font-size: 0.8rem;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                border: none;
                border-radius: 0.5rem;
                cursor: pointer;
                transition: all 200ms var(--ease);
            }

            .btn-action-primary:hover {
                background: var(--night);
                transform: translateY(-2px);
                box-shadow: 0 8px 24px oklch(18% 0.03 76 / 0.25);
            }

            /* Margin utility */
            .mt-4 {
                margin-top: 1rem;
            }

            /* Responsive */
            @media (max-width: 768px) {
                /* Large Tabs - Responsive */
                .neo-tabs-large {
                    padding: 0.5rem 1rem;
                    overflow-x: auto;
                    -webkit-overflow-scrolling: touch;
                }

                .neo-tabs-large .neo-tab {
                    padding: 0.75rem 1rem;
                    font-size: 0.85rem;
                    gap: 0.5rem;
                }

                .neo-tabs-large .neo-tab svg {
                    width: 20px;
                    height: 20px;
                }

                .madrasah-fullwidth .content-inner {
                    padding: 1rem 0.5rem;
                }

                .form-grid-3 {
                    grid-template-columns: 1fr;
                }

                .mutation-form-grid {
                    grid-template-columns: 1fr;
                }

                .mutation-form-grid .span-2 {
                    grid-column: span 1;
                }

                .rombel-cards-grid {
                    grid-template-columns: repeat(2, 1fr);
                }

                .info-hero-meta {
                    flex-direction: column;
                }

                .info-hero-title-row {
                    flex-direction: column;
                    align-items: flex-start;
                }

                .form-actions-bottom {
                    flex-direction: column;
                }

                .form-actions-bottom button {
                    width: 100%;
                    justify-content: center;
                }

                .neo-tabs {
                    gap: 0.5rem;
                    padding: 0.5rem;
                }

                .neo-tab {
                    padding: 0.5rem 0.75rem;
                    font-size: 0.65rem;
                }

                .neo-tab span {
                    display: none;
                }

                .neo-tab svg {
                    width: 1.25rem;
                    height: 1.25rem;
                }
            }

            @media (max-width: 480px) {
                .rombel-cards-grid {
                    grid-template-columns: 1fr;
                }

                .stat-grid {
                    grid-template-columns: 1fr;
                }

                .level-header {
                    flex-direction: column;
                    align-items: flex-start;
                    gap: 0.75rem;
                }

                .level-actions {
                    width: 100%;
                    justify-content: space-between;
                }
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
