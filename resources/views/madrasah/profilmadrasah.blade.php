<x-layouts.app title="Profil Madrasah - SILATAR">
    @php
        $formData = $formData ?? [
            'nama' => '',
            'nsm' => '',
            'npsm' => '',
            'status_lembaga' => '',
            'is_nama_readonly' => false,
            'is_status_readonly' => false,
            'jalan' => '',
            'jorong' => '',
            'nagari' => '',
            'kecamatan' => '',
            'koordinat' => '',
            'telepon' => '',
            'email' => '',
            'website' => '',
            'waktu_belajar' => '',
            'visi' => '',
            'sk_pendirian' => '',
            'tanggal_sk' => '',
            'komite_lembaga' => '',
            'akreditasi' => '',
            'tanggal_akreditasi' => '',
            'status_kkm' => '',
            'jarak_pusat_provinsi' => '',
            'jarak_pusat_kabupaten' => '',
            'jarak_kecamatan' => '',
            'jarak_kanwil_kemenag' => '',
            'jarak_kemenag_kab' => '',
            'jarak_kua' => '',
            'jarak_ra_terdekat' => '',
            'jarak_mi_terdekat' => '',
            'jarak_mts_terdekat' => '',
            'jarak_ma_terdekat' => '',
            'jarak_pontren_terdekat' => '',
            'jarak_tk_terdekat' => '',
            'jarak_sd_terdekat' => '',
            'jarak_smp_terdekat' => '',
            'jarak_sma_terdekat' => '',
        ];
        $statusLembaga = ['NEGERI', 'SWASTA'];
        $statusKkm = ['TERAKREDITASI', 'BELUM TERAKREDITASI'];
    @endphp

    <main class="neo-mirai madrasah-profil madrasah-fullwidth" x-data="{ activeTab: 'profil' }">
        <x-layouts.site-header />

        <!-- Hero Section -->
        <section class="hero-page has-bg-image">
            <div class="hero-badge-wrapper">
                <div class="hero-badge">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    Madrasah
                </div>
                <h1 class="hero-title">
                    <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="var(--gold)" stroke-width="1.5"><path d="M12 21.75V19.5C12 18.12 13.12 17 14.5 17H16.5C17.88 17 19 18.12 19 19.5V21.75M12 21.75V3M12 3H7.5C6.12 3 5 4.12 5 5.5V8.25M12 3H16.5C17.88 3 19 4.12 19 5.5V8.25M5 8.25V12.75C5 14.13 6.12 15.25 7.5 15.25H9M9 15.25C10.38 15.25 11.5 14.13 11.5 12.75V8.25M9 15.25H16.5C17.88 15.25 19 14.13 19 12.75V5.5C19 4.12 17.88 3 16.5 3H12M5 8.25H7.5M7.5 8.25C6.12 8.25 5 9.37 5 10.75V12.75"/></svg>
                    PROFIL MADRASAH
                </h1>
                <p class="hero-subtitle">Lengkapi data profil madrasah dengan akurat untuk keperluan pelaporan dan evaluasi kinerja</p>
            </div>
        </section>

        <!-- Section Divider -->
        <div class="section-divider wave-rounded"></div>

        <!-- Content -->
        <section class="page-content page-content-expanded">
            <!-- Tab Navigation - Large & Prominent -->
            <div class="neo-tabs neo-tabs-large" role="tablist">
                <a href="{{ route('madrasah.profil') }}" class="neo-tab is-active" role="tab">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="22" height="22"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        <span>Profil</span>
                    </a>
                    <a href="{{ route('madrasah.pegawai') }}" class="neo-tab" role="tab">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span>Pegawai</span>
                    </a>
                    <a href="{{ route('madrasah.guru') }}" class="neo-tab" role="tab">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        <span>Guru</span>
                    </a>
                    <a href="{{ route('madrasah.laporan-semester') }}" class="neo-tab" role="tab">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <span>Semester</span>
                    </a>
                    <a href="{{ route('madrasah.laporan-bulanan') }}" class="neo-tab" role="tab">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="22" height="22"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span>Bulanan</span>
                    </a>
                </div>

                <div class="content-inner">

                <form action="#" method="POST" class="space-y-8">

                    <!-- Section 1: Identitas Madrasah -->
                    <div class="neo-card section-card">
                        <div class="neo-card-header">
                            <div class="neo-card-icon" style="background: var(--gold); color: var(--night);">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            </div>
                            <div class="neo-card-text">
                                <h2 class="neo-card-title">Identitas Madrasah</h2>
                                <p class="neo-card-desc">Informasi dasar mengenai lembaga pendidikan</p>
                            </div>
                        </div>
                        <div class="neo-card-body">
                            <div class="form-grid form-grid-lg">
                                <div class="form-full">
                                    <label class="neo-field-label">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                        Nama Madrasah
                                    </label>
                                    @if($formData['is_nama_readonly'])
                                        <input type="text" name="nama" value="{{ old('nama', $formData['nama']) }}" readonly disabled class="neo-field-input opacity-70 cursor-not-allowed">
                                        <span class="neo-field-hint">Data auto-fill dari sistem</span>
                                    @else
                                        <input type="text" name="nama" value="{{ old('nama', $formData['nama']) }}" class="neo-field-input" placeholder="Contoh: Madrasah Ibtidaiyah Negeri 1 Tanjung">
                                    @endif
                                </div>
                                <div class="neo-field-group">
                                    <label class="neo-field-label">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/></svg>
                                        NSM
                                    </label>
                                    <input type="text" name="nsm" value="{{ old('nsm', $formData['nsm']) }}" class="neo-field-input" placeholder="Nomor Statistik Madrasah">
                                </div>
                                <div class="neo-field-group">
                                    <label class="neo-field-label">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/></svg>
                                        NPSM
                                    </label>
                                    <input type="text" name="npsm" value="{{ old('npsm', $formData['npsm']) }}" class="neo-field-input" placeholder="Nomor Pokok Sekolah Madrasah">
                                </div>
                                <div class="neo-field-group">
                                    <label class="neo-field-label">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        Status Lembaga
                                    </label>
                                    @if($formData['is_status_readonly'])
                                        <input type="text" value="{{ $formData['status_lembaga'] }}" readonly disabled class="neo-field-input status-negeri" style="border-color: var(--gold); background: var(--paper-soft);">
                                        <input type="hidden" name="status_lembaga" value="{{ $formData['status_lembaga'] }}">
                                    @else
                                        <select name="status_lembaga" class="neo-field-select">
                                            <option value="">Pilih Status</option>
                                            @foreach($statusLembaga as $status)
                                                <option value="{{ $status }}" {{ old('status_lembaga', $formData['status_lembaga']) == $status ? 'selected' : '' }}>{{ $status }}</option>
                                            @endforeach
                                        </select>
                                    @endif
                                </div>
                                <div class="form-full">
                                    <label class="neo-field-label">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                                        Waktu Belajar
                                    </label>
                                    <input type="text" name="waktu_belajar" value="{{ old('waktu_belajar', $formData['waktu_belajar']) }}" class="neo-field-input" placeholder="Contoh: Pagi (07.00 - 13.00 WIB)">
                                </div>
                                <div class="neo-field-group">
                                    <label class="neo-field-label">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                                        Akreditasi
                                    </label>
                                    <input type="text" name="akreditasi" value="{{ old('akreditasi', $formData['akreditasi']) }}" class="neo-field-input akreditasi-badge" placeholder="Contoh: A">
                                </div>
                                <div class="neo-field-group">
                                    <label class="neo-field-label">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                        Tanggal Akreditasi
                                    </label>
                                    <input type="date" name="tanggal_akreditasi" value="{{ old('tanggal_akreditasi', $formData['tanggal_akreditasi']) }}" class="neo-field-input">
                                </div>
                                <div class="neo-field-group">
                                    <label class="neo-field-label">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                        Status KKM
                                    </label>
                                    <select name="status_kkm" class="neo-field-select">
                                        <option value="">Pilih Status</option>
                                        @foreach($statusKkm as $status)
                                            <option value="{{ $status }}" {{ old('status_kkm', $formData['status_kkm']) == $status ? 'selected' : '' }}>{{ $status }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Alamat & Lokasi -->
                    <div class="neo-card section-card">
                        <div class="neo-card-header">
                            <div class="neo-card-icon" style="background: var(--gold); color: var(--night);">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <div class="neo-card-text">
                                <h2 class="neo-card-title">Alamat & Lokasi</h2>
                                <p class="neo-card-desc">Informasi lokasi dan koordinat lembaga</p>
                            </div>
                        </div>
                        <div class="neo-card-body">
                            <div class="form-grid form-grid-lg">
                                <div class="form-full">
                                    <label class="neo-field-label">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        Jalan
                                    </label>
                                    <input type="text" name="jalan" value="{{ old('jalan', $formData['jalan']) }}" class="neo-field-input" placeholder="Nama jalan lengkap">
                                </div>
                                <div class="neo-field-group">
                                    <label class="neo-field-label">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                        Jorong / Kampung
                                    </label>
                                    <input type="text" name="jorong" value="{{ old('jorong', $formData['jorong']) }}" class="neo-field-input" placeholder="Nama jorong atau kampung">
                                </div>
                                <div class="neo-field-group">
                                    <label class="neo-field-label">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                        Nagari / Desa
                                    </label>
                                    <input type="text" name="nagari" value="{{ old('nagari', $formData['nagari']) }}" class="neo-field-input" placeholder="Nama nagari atau desa">
                                </div>
                                <div class="neo-field-group">
                                    <label class="neo-field-label">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                        Kecamatan
                                    </label>
                                    <input type="text" name="kecamatan" value="{{ old('kecamatan', $formData['kecamatan']) }}" class="neo-field-input" placeholder="Nama kecamatan">
                                </div>
                                <div class="form-full">
                                    <label class="neo-field-label">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z"/></svg>
                                        Koordinat GPS
                                    </label>
                                    <input type="text" name="koordinat" value="{{ old('koordinat', $formData['koordinat']) }}" class="neo-field-input mono-input" placeholder="Contoh: -0.5071, 100.4478">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section 3: Kontak & Website -->
                    <div class="neo-card section-card">
                        <div class="neo-card-header">
                            <div class="neo-card-icon" style="background: var(--gold); color: var(--night);">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </div>
                            <div class="neo-card-text">
                                <h2 class="neo-card-title">Kontak & Website</h2>
                                <p class="neo-card-desc">Informasi kontak dan media digital</p>
                            </div>
                        </div>
                        <div class="neo-card-body">
                            <div class="form-grid form-grid-md">
                                <div class="neo-field-group">
                                    <label class="neo-field-label">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                        Telepon / HP
                                    </label>
                                    <input type="tel" name="telepon" value="{{ old('telepon', $formData['telepon']) }}" class="neo-field-input" placeholder="Contoh: 0812-3456-7890">
                                </div>
                                <div class="neo-field-group">
                                    <label class="neo-field-label">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                        Email
                                    </label>
                                    <input type="email" name="email" value="{{ old('email', $formData['email']) }}" class="neo-field-input" placeholder="contoh@email.com">
                                </div>
                                <div class="neo-field-group">
                                    <label class="neo-field-label">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z"/></svg>
                                        Website
                                    </label>
                                    <input type="url" name="website" value="{{ old('website', $formData['website']) }}" class="neo-field-input" placeholder="https://www.madrasah.sch.id">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section 4: SK Pendirian -->
                    <div class="neo-card section-card">
                        <div class="neo-card-header">
                            <div class="neo-card-icon" style="background: var(--gold); color: var(--night);">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                            <div class="neo-card-text">
                                <h2 class="neo-card-title">SK Pendirian</h2>
                                <p class="neo-card-desc">Informasi legalitas pendirian lembaga</p>
                            </div>
                        </div>
                        <div class="neo-card-body">
                            <div class="form-grid form-grid-md">
                                <div class="neo-field-group">
                                    <label class="neo-field-label">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        Nomor SK Pendirian
                                    </label>
                                    <input type="text" name="sk_pendirian" value="{{ old('sk_pendirian', $formData['sk_pendirian']) }}" class="neo-field-input" placeholder="Contoh: SK.1234/PP.03.03/2008">
                                </div>
                                <div class="neo-field-group">
                                    <label class="neo-field-label">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                        Tanggal SK
                                    </label>
                                    <input type="date" name="tanggal_sk" value="{{ old('tanggal_sk', $formData['tanggal_sk']) }}" class="neo-field-input">
                                </div>
                            </div>
                            <div class="form-full mt-4">
                                <label class="neo-field-label">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    Nama Komite Lembaga
                                </label>
                                <input type="text" name="komite_lembaga" value="{{ old('komite_lembaga', $formData['komite_lembaga']) }}" class="neo-field-input" placeholder="Nama Ketua Komite Madrasah">
                            </div>
                        </div>
                    </div>

                    <!-- Section 5: Visi Madrasah -->
                    <div class="neo-card section-card">
                        <div class="neo-card-header">
                            <div class="neo-card-icon" style="background: var(--gold); color: var(--night);">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </div>
                            <div class="neo-card-text">
                                <h2 class="neo-card-title">Visi Madrasah</h2>
                                <p class="neo-card-desc">Visi dan arah lembaga pendidikan</p>
                            </div>
                        </div>
                        <div class="neo-card-body">
                            <label class="neo-field-label">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                Visi
                            </label>
                            <textarea name="visi" rows="4" class="neo-field-input resize-none" placeholder="Tuliskan visi madrasah secara lengkap...">{{ old('visi', $formData['visi']) }}</textarea>
                        </div>
                    </div>

                    <!-- Section 6: Jarak Madrasah ke... -->
                    <div class="neo-card section-card">
                        <div class="neo-card-header">
                            <div class="neo-card-icon" style="background: var(--gold); color: var(--night);">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path d="M21 21l-5-5"/>
                                </svg>
                            </div>
                            <div class="neo-card-text">
                                <h2 class="neo-card-title">Jarak Madrasah ke...</h2>
                                <p class="neo-card-desc">Jarak ke berbagai lokasi penting (dalam kilometer)</p>
                            </div>
                        </div>
                        <div class="neo-card-body">
                            <div class="form-grid form-grid-md mb-6">
                                <div class="neo-field-group">
                                    <label class="neo-field-label">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                        Pusat Provinsi
                                    </label>
                                    <div class="input-with-unit-sm">
                                        <input type="number" name="jarak_pusat_provinsi" value="{{ old('jarak_pusat_provinsi', $formData['jarak_pusat_provinsi']) }}" min="0" step="0.1" class="neo-field-input" placeholder="0">
                                        <span class="unit-label">km</span>
                                    </div>
                                </div>
                                <div class="neo-field-group">
                                    <label class="neo-field-label">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                        Pusat Kabupaten/Kota
                                    </label>
                                    <div class="input-with-unit-sm">
                                        <input type="number" name="jarak_pusat_kabupaten" value="{{ old('jarak_pusat_kabupaten', $formData['jarak_pusat_kabupaten']) }}" min="0" step="0.1" class="neo-field-input" placeholder="0">
                                        <span class="unit-label">km</span>
                                    </div>
                                </div>
                                <div class="neo-field-group">
                                    <label class="neo-field-label">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                        Kecamatan
                                    </label>
                                    <div class="input-with-unit-sm">
                                        <input type="number" name="jarak_kecamatan" value="{{ old('jarak_kecamatan', $formData['jarak_kecamatan']) }}" min="0" step="0.1" class="neo-field-input" placeholder="0">
                                        <span class="unit-label">km</span>
                                    </div>
                                </div>
                                <div class="neo-field-group">
                                    <label class="neo-field-label">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                        Kanwil Kemenag
                                    </label>
                                    <div class="input-with-unit-sm">
                                        <input type="number" name="jarak_kanwil_kemenag" value="{{ old('jarak_kanwil_kemenag', $formData['jarak_kanwil_kemenag']) }}" min="0" step="0.1" class="neo-field-input" placeholder="0">
                                        <span class="unit-label">km</span>
                                    </div>
                                </div>
                                <div class="neo-field-group">
                                    <label class="neo-field-label">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                        Kemenag Kabupaten
                                    </label>
                                    <div class="input-with-unit-sm">
                                        <input type="number" name="jarak_kemenag_kab" value="{{ old('jarak_kemenag_kab', $formData['jarak_kemenag_kab']) }}" min="0" step="0.1" class="neo-field-input" placeholder="0">
                                        <span class="unit-label">km</span>
                                    </div>
                                </div>
                                <div class="neo-field-group">
                                    <label class="neo-field-label">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                        KUA
                                    </label>
                                    <div class="input-with-unit-sm">
                                        <input type="number" name="jarak_kua" value="{{ old('jarak_kua', $formData['jarak_kua']) }}" min="0" step="0.1" class="neo-field-input" placeholder="0">
                                        <span class="unit-label">km</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Jarak ke Lembaga Pendidikan Terdekat -->
                            <div class="sub-section">
                                <h3 class="sub-section-title">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                    Jarak ke Lembaga Pendidikan Terdekat
                                </h3>
                                <div class="form-grid form-grid-xs">
                                    <div class="neo-field-group">
                                        <label class="neo-field-label" style="font-size: 0.8rem;">RA Terdekat</label>
                                        <div class="input-with-unit-sm">
                                            <input type="number" name="jarak_ra_terdekat" value="{{ old('jarak_ra_terdekat', $formData['jarak_ra_terdekat']) }}" min="0" step="0.1" class="neo-field-input" placeholder="0">
                                            <span class="unit-label">km</span>
                                        </div>
                                    </div>
                                    <div class="neo-field-group">
                                        <label class="neo-field-label" style="font-size: 0.8rem;">MI Terdekat</label>
                                        <div class="input-with-unit-sm">
                                            <input type="number" name="jarak_mi_terdekat" value="{{ old('jarak_mi_terdekat', $formData['jarak_mi_terdekat']) }}" min="0" step="0.1" class="neo-field-input" placeholder="0">
                                            <span class="unit-label">km</span>
                                        </div>
                                    </div>
                                    <div class="neo-field-group">
                                        <label class="neo-field-label" style="font-size: 0.8rem;">MTs Terdekat</label>
                                        <div class="input-with-unit-sm">
                                            <input type="number" name="jarak_mts_terdekat" value="{{ old('jarak_mts_terdekat', $formData['jarak_mts_terdekat']) }}" min="0" step="0.1" class="neo-field-input" placeholder="0">
                                            <span class="unit-label">km</span>
                                        </div>
                                    </div>
                                    <div class="neo-field-group">
                                        <label class="neo-field-label" style="font-size: 0.8rem;">MA Terdekat</label>
                                        <div class="input-with-unit-sm">
                                            <input type="number" name="jarak_ma_terdekat" value="{{ old('jarak_ma_terdekat', $formData['jarak_ma_terdekat']) }}" min="0" step="0.1" class="neo-field-input" placeholder="0">
                                            <span class="unit-label">km</span>
                                        </div>
                                    </div>
                                    <div class="neo-field-group">
                                        <label class="neo-field-label" style="font-size: 0.8rem;">Pontren Terdekat</label>
                                        <div class="input-with-unit-sm">
                                            <input type="number" name="jarak_pontren_terdekat" value="{{ old('jarak_pontren_terdekat', $formData['jarak_pontren_terdekat']) }}" min="0" step="0.1" class="neo-field-input" placeholder="0">
                                            <span class="unit-label">km</span>
                                        </div>
                                    </div>
                                    <div class="neo-field-group">
                                        <label class="neo-field-label" style="font-size: 0.8rem;">TK/PAUD Terdekat</label>
                                        <div class="input-with-unit-sm">
                                            <input type="number" name="jarak_tk_terdekat" value="{{ old('jarak_tk_terdekat', $formData['jarak_tk_terdekat']) }}" min="0" step="0.1" class="neo-field-input" placeholder="0">
                                            <span class="unit-label">km</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Jarak ke Sekolah Umum Terdekat -->
                            <div class="sub-section mt-6">
                                <h3 class="sub-section-title">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                    Jarak ke Sekolah Umum Terdekat
                                </h3>
                                <div class="form-grid form-grid-sm">
                                    <div class="neo-field-group">
                                        <label class="neo-field-label">SD Terdekat</label>
                                        <div class="input-with-unit-sm">
                                            <input type="number" name="jarak_sd_terdekat" value="{{ old('jarak_sd_terdekat', $formData['jarak_sd_terdekat']) }}" min="0" step="0.1" class="neo-field-input" placeholder="0">
                                            <span class="unit-label">km</span>
                                        </div>
                                    </div>
                                    <div class="neo-field-group">
                                        <label class="neo-field-label">SMP Terdekat</label>
                                        <div class="input-with-unit-sm">
                                            <input type="number" name="jarak_smp_terdekat" value="{{ old('jarak_smp_terdekat', $formData['jarak_smp_terdekat']) }}" min="0" step="0.1" class="neo-field-input" placeholder="0">
                                            <span class="unit-label">km</span>
                                        </div>
                                    </div>
                                    <div class="neo-field-group">
                                        <label class="neo-field-label">SMA Terdekat</label>
                                        <div class="input-with-unit-sm">
                                            <input type="number" name="jarak_sma_terdekat" value="{{ old('jarak_sma_terdekat', $formData['jarak_sma_terdekat']) }}" min="0" step="0.1" class="neo-field-input" placeholder="0">
                                            <span class="unit-label">km</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="form-actions">
                        <a href="{{ url('/') }}" class="btn-action-cancel">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                            Kembali
                        </a>
                        <button type="reset" class="btn-action-reset">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            Reset
                        </button>
                        <button type="submit" class="btn-action-save">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                            Simpan Data
                        </button>
                    </div>

                </form>
            </div>
            </div>
        </section>

        <!-- Page Styles -->
        <style>
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

            /* Responsive */
            @media (max-width: 768px) {
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
            }
        </style>
    </main>
</x-layouts.app>
