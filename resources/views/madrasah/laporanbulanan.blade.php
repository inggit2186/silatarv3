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

                <!-- Filter Form - Select periode laporan -->
                @php
                    $bulanIndonesia = ["Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];
                    $currentBulan = $bulanIndonesia[date('n') - 1];
                    $currentTahun = date('Y');
                    $currentSemester = (date('n') >= 7) ? 'Ganjil' : 'Genap';
                    $currentTahunAjaran = (date('n') >= 7) ? (date('Y') . '/' . (date('Y') + 1)) : ((date('Y') - 1) . '/' . date('Y'));
                @endphp
                <div class="filter-container">
                    <div class="filter-header">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
                        <span>Pilih Periode Laporan</span>
                    </div>
                    <form action="{{ route('madrasah.laporan-bulanan') }}" method="GET" id="filterForm" class="filter-form">
                        <div class="filter-item">
                            <label class="filter-label">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                Bulan
                            </label>
                            <select name="bulan" onchange="document.getElementById('filterForm').submit()" class="filter-select">
                                @foreach(["Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"] as $bulan)
                                    <option value="{{ $bulan }}" {{ ($bulan_laporan ?? $currentBulan) == $bulan ? "selected" : "" }}>{{ $bulan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-item">
                            <label class="filter-label">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                Tahun
                            </label>
                            <select name="tahun" onchange="document.getElementById('filterForm').submit()" class="filter-select">
                                @for($y = date('Y') + 1; $y >= date('Y') - 5; $y--)
                                    <option value="{{ $y }}" {{ ($tahun_laporan ?? $currentTahun) == $y ? "selected" : "" }}>{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="filter-item">
                            <label class="filter-label">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 016.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"/></svg>
                                Tahun Ajaran
                            </label>
                            <select name="tahun_ajaran" onchange="document.getElementById('filterForm').submit()" class="filter-select">
                                @for($y = date('Y'); $y >= date('Y') - 3; $y--)
                                    <option value="{{ $y }}/{{ $y + 1 }}" {{ ($tahun_ajaran ?? $currentTahunAjaran) == ($y.'/'.($y+1)) ? "selected" : "" }}>{{ $y }}/{{ $y + 1 }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="filter-item">
                            <label class="filter-label">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                Semester
                            </label>
                            <select name="semester" onchange="document.getElementById('filterForm').submit()" class="filter-select">
                                <option value="Ganjil" {{ ($semester ?? $currentSemester) == "Ganjil" ? "selected" : "" }}>Ganjil</option>
                                <option value="Genap" {{ ($semester ?? $currentSemester) == "Genap" ? "selected" : "" }}>Genap</option>
                            </select>
                        </div>
                    </form>
                </div>

                <!-- Info Banner: Periode Laporan -->
                <div class="info-period-banner">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    <div class="info-period-text">
                        <span class="info-period-label">Periode Laporan</span>
                        <span class="info-period-value">{{ $bulan_laporan ?? $currentBulan }} {{ $tahun_laporan ?? $currentTahun }} | Semester {{ $semester ?? $currentSemester }} TA {{ $tahun_ajaran ?? $currentTahunAjaran }}</span>
                    </div>
                </div>

                <!-- Template Info Banner -->
                @if($templateInfo)
                <div class="template-info-banner" style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem 1.25rem; background: rgba(234, 179, 8, 0.1); border: 1px solid rgba(234, 179, 8, 0.3); border-radius: 0.5rem; margin-bottom: 1.5rem; color: #92400e;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink: 0; color: #d97706;">
                        <path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <span style="font-size: 0.9rem; font-weight: 500;">
                        <strong>Peringatan:</strong> Data untuk periode ini belum ada di database. Menampilkan {{ $templateInfo }}. Silakan isi data untuk periode ini dan simpan.
                    </span>
                </div>
                @endif

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
                </div>

                <form action="{{ route('madrasah.laporan-bulanan.save') }}" method="POST" id="laporanBulananForm">
                @csrf

                <!-- Section 1: Informasi Madrasah -->
                <div class="neo-card section-card">
                    <div class="neo-card-header">
                        <div class="neo-card-icon" style="background: var(--gold); color: var(--night);">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        <div class="neo-card-text">
                            <h3 class="neo-card-title">A. Informasi Madrasah</h3>
                            <p class="neo-card-desc">Identitas madrasah</p>
                        </div>
                    </div>
                    <div class="neo-card-body">
                        <!-- Hidden inputs for periode -->
                        <input type="hidden" name="bulan_laporan" value="{{ $bulan_laporan ?? 'Januari' }}">
                        <input type="hidden" name="tahun_laporan" value="{{ $tahun_laporan ?? date('Y') }}">
                        <input type="hidden" name="tahun_ajaran" value="{{ $tahun_ajaran ?? '' }}">
                        <input type="hidden" name="semester" value="{{ $semester ?? 'Genap' }}">

                        <div class="form-grid form-grid-3">
                            <div class="neo-field-group horizontal">
                                <label class="neo-field-label">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/></svg>
                                    Nama Madrasah
                                </label>
                                <input type="text" class="neo-form-input" value="{{ $nama_madrasah ?? "" }}" readonly>
                            </div>
                            <div class="neo-field-group horizontal">
                                <label class="neo-field-label">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    Rombel (RB)
                                </label>
                                <input type="number" name="rb" id="rbCount" class="neo-form-input" value="{{ $rb ?? 0 }}" min="0" readonly>
                            </div>
                        </div>
                        <div class="form-full mt-4">
                            <div class="neo-field-group horizontal">
                                <label class="neo-field-label">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                    Kantor / Instansi
                                </label>
                                <input type="text" class="neo-form-input" value="{{ $office_name ?? "Kantor Kementerian Agama Kab. Tanah Datar" }}" readonly>
                            </div>
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
                                    <small>RB <span id="rbCountStat">{{ $rb ?? 0 }}</span></small>
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
                                        <select name="mutationRows[{{ $idx }}][kelas]" class="neo-form-select kelas-select" data-selected="{{ $row['kelas'] ?? '' }}">
                                            <option value="">Memuat kelas...</option>
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
                                        <x-ui.datepicker
                                            name="mutationRows[{{ $idx }}][tanggal_lahir]"
                                            label="Tanggal Lahir"
                                            value="{{ $row['tanggal_lahir'] ?? '' }}"
                                            placeholder="Pilih tanggal lahir"
                                        />
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
                                        <select name="mutationRows[0][kelas]" class="neo-form-select kelas-select" data-selected="">
                                            <option value="">Pilih kelas</option>
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
                                        <x-ui.datepicker
                                            name="mutationRows[0][tanggal_lahir]"
                                            label="Tanggal Lahir"
                                            placeholder="Pilih tanggal lahir"
                                        />
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
        <link rel="stylesheet" href="{{ asset('css/laporan-madrasah.css') }}">


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

            // Add event listeners to mutation name inputs
            document.querySelectorAll("input[name*=\"[nama_siswa]\"]").forEach(input => {
                input.addEventListener("input", function() {
                    calculateMutationStats();
                });
            });

            // Initial update for RB count and kelas dropdowns
            updateRombelInfo();
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

        // Update RB count and populate kelas dropdowns based on rombel cards
        function updateRombelInfo() {
            const rombelContainer = document.getElementById("rombelContainer");
            const rombelCards = rombelContainer.querySelectorAll(".rombel-card");

            // Update RB count
            const rbCount = rombelCards.length;
            const rbInput = document.getElementById("rbCount");
            const rbStat = document.getElementById("rbCountStat");
            if (rbInput) rbInput.value = rbCount;
            if (rbStat) rbStat.textContent = rbCount;

            // Get all rombel codes and sort them
            const rombelCodes = [];
            rombelCards.forEach(card => {
                const code = card.dataset.code;
                if (code) rombelCodes.push(code);
            });
            rombelCodes.sort();

            // Update all kelas dropdowns
            document.querySelectorAll(".kelas-select").forEach(select => {
                const selectedValue = select.dataset.selected || '';
                select.innerHTML = '<option value="">Pilih kelas</option>';
                rombelCodes.forEach(code => {
                    const option = document.createElement("option");
                    option.value = code;
                    option.textContent = code;
                    if (code === selectedValue) {
                        option.selected = true;
                    }
                    select.appendChild(option);
                });
            });
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

            // Update RB count and kelas dropdowns
            updateRombelInfo();
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

            // Update RB count and kelas dropdowns
            updateRombelInfo();
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

            // Add event listener to new name input
            const nameInput = newCard.querySelector("input[name*=\"[nama_siswa]\"]");
            if (nameInput) {
                nameInput.addEventListener("input", function() {
                    calculateMutationStats();
                });
            }

            calculateMutationStats();

            // Update RB count and kelas dropdowns
            updateRombelInfo();
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

            let total = 0;
            let masuk = 0, keluar = 0, doCount = 0;

            cards.forEach(card => {
                const namaInput = card.querySelector("input[name*=\"[nama_siswa]\"]");
                const select = card.querySelector("select[name*=\"[keterangan]\"]");
                const namaValue = namaInput?.value?.trim();
                const selectValue = select?.value;

                // Only count if student name is filled
                if (namaValue && namaValue.length > 0) {
                    total++;
                    if (selectValue === "Mutasi Masuk") masuk++;
                    else if (selectValue === "Mutasi Keluar") keluar++;
                    else if (selectValue === "DO" || selectValue === "Mengundurkan Diri") doCount++;
                }
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
