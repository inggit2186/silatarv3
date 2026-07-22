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

    <main class="neo-mirai" x-data="{ activeTab: 'profil' }">
        <x-layouts.site-header />

        <!-- Hero Section -->
        <section class="hero-page has-bg-image" style="padding: 140px 2rem 4rem; min-height: 320px;">
            <div style="max-width: 42rem; text-align: center;">
                <p style="color: var(--gold); font-family: var(--font-mono); font-size: 0.65rem; text-transform: uppercase; margin: 0 0 0.75rem; display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    Laporan Madrasah
                </p>
                <h1 style="font-family: var(--font-display); font-size: clamp(1.8rem, 4vw, 2.8rem); font-weight: 400; color: var(--ink); margin: 0 0 1rem; display: flex; align-items: center; justify-content: center; gap: 0.75rem;">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="var(--gold)" stroke-width="1.5"><path d="M12 21.75V19.5C12 18.12 13.12 17 14.5 17H16.5C17.88 17 19 18.12 19 19.5V21.75M12 21.75V3M12 3H7.5C6.12 3 5 4.12 5 5.5V8.25M12 3H16.5C17.88 3 19 4.12 19 5.5V8.25M5 8.25V12.75C5 14.13 6.12 15.25 7.5 15.25H9M9 15.25C10.38 15.25 11.5 14.13 11.5 12.75V8.25M9 15.25H16.5C17.88 15.25 19 14.13 19 12.75V5.5C19 4.12 17.88 3 16.5 3H12M5 8.25H7.5M7.5 8.25C6.12 8.25 5 9.37 5 10.75V12.75"/></svg>
                    PROFIL MADRASAH
                </h1>
                <p style="color: var(--ink-soft); font-size: 1rem; max-width: 32rem; margin: 0 auto;">Lengkapi data profil madrasah dengan akurat untuk keperluan pelaporan dan evaluasi kinerja.</p>
                <div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 1rem; margin-top: 1.5rem;">
                    <a href="{{ url('/') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.7rem 1.25rem; background: transparent; color: var(--ink); font-family: var(--font-mono); font-size: 0.7rem; font-weight: 600; text-transform: uppercase; text-decoration: none; border: 1px solid var(--line);">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
                        Beranda
                    </a>
                </div>
            </div>
        </section>

        <!-- Section Divider -->
        <div class="section-divider wave-rounded"></div>

        <!-- Content -->
        <section class="page-content">
            <div class="content-centered">

                <!-- Tab Navigation -->
                <div class="neo-tabs" style="margin-bottom: 2rem;">
                    <a href="{{ route('madrasah.profil') }}" class="neo-tab is-active">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        Profil Madrasah
                    </a>
                    <a href="{{ route('madrasah.pegawai') }}" class="neo-tab">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Pegawai
                    </a>
                    <a href="{{ route('madrasah.guru') }}" class="neo-tab">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        Guru
                    </a>
                    <a href="{{ route('madrasah.laporan-semester') }}" class="neo-tab">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Laporan Semester
                    </a>
                    <a href="{{ route('madrasah.laporan-bulanan') }}" class="neo-tab">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        Laporan Bulanan
                    </a>
                </div>

                <form action="#" method="POST" class="space-y-8">

                    <!-- Section 1: Identitas Madrasah -->
                    <div class="neo-card">
                        <div class="neo-card-header">
                            <div class="neo-card-icon" style="background: var(--gold); color: var(--night);">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            </div>
                            <div>
                                <h2 class="neo-card-title">Identitas Madrasah</h2>
                                <p class="neo-card-desc">Informasi dasar mengenai lembaga pendidikan</p>
                            </div>
                        </div>
                        <div class="neo-card-body">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                <div class="lg:col-span-3">
                                    <label class="neo-field-label">Nama Madrasah</label>
                                    @if($formData['is_nama_readonly'])
                                        <input type="text" name="nama" value="{{ old('nama', $formData['nama']) }}" readonly disabled class="neo-field-input opacity-70 cursor-not-allowed">
                                        <span class="neo-field-hint" style="color: var(--ink-soft);">Data auto-fill dari sistem</span>
                                    @else
                                        <input type="text" name="nama" value="{{ old('nama', $formData['nama']) }}" class="neo-field-input" placeholder="Contoh: Madrasah Ibtidaiyah Negeri 1 Tanjung">
                                    @endif
                                </div>
                                <div>
                                    <label class="neo-field-label">NSM</label>
                                    <input type="text" name="nsm" value="{{ old('nsm', $formData['nsm']) }}" class="neo-field-input" placeholder="Nomor Statistik Madrasah">
                                </div>
                                <div>
                                    <label class="neo-field-label">NPSM</label>
                                    <input type="text" name="npsm" value="{{ old('npsm', $formData['npsm']) }}" class="neo-field-input" placeholder="Nomor Pokok Sekolah Madrasah">
                                </div>
                                <div>
                                    <label class="neo-field-label">Status Lembaga</label>
                                    @if($formData['is_status_readonly'])
                                        <input type="text" value="{{ $formData['status_lembaga'] }}" readonly disabled class="neo-field-input" style="border-color: var(--gold); background: var(--paper-soft); color: var(--gold);">
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
                                <div class="lg:col-span-2">
                                    <label class="neo-field-label">Waktu Belajar</label>
                                    <input type="text" name="waktu_belajar" value="{{ old('waktu_belajar', $formData['waktu_belajar']) }}" class="neo-field-input" placeholder="Contoh: Pagi (07.00 - 13.00 WIB)">
                                </div>
                                <div>
                                    <label class="neo-field-label">Akreditasi</label>
                                    <input type="text" name="akreditasi" value="{{ old('akreditasi', $formData['akreditasi']) }}" class="neo-field-input" placeholder="Contoh: A">
                                </div>
                                <div>
                                    <label class="neo-field-label">Tanggal Akreditasi</label>
                                    <input type="date" name="tanggal_akreditasi" value="{{ old('tanggal_akreditasi', $formData['tanggal_akreditasi']) }}" class="neo-field-input">
                                </div>
                                <div>
                                    <label class="neo-field-label">Status KKM</label>
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
                    <div class="neo-card">
                        <div class="neo-card-header">
                            <div class="neo-card-icon" style="background: var(--gold); color: var(--night);">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <div>
                                <h2 class="neo-card-title">Alamat & Lokasi</h2>
                                <p class="neo-card-desc">Informasi lokasi dan koordinat lembaga</p>
                            </div>
                        </div>
                        <div class="neo-card-body">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                <div class="lg:col-span-2">
                                    <label class="neo-field-label">Jalan</label>
                                    <input type="text" name="jalan" value="{{ old('jalan', $formData['jalan']) }}" class="neo-field-input" placeholder="Nama jalan lengkap">
                                </div>
                                <div>
                                    <label class="neo-field-label">Jorong / Kampung</label>
                                    <input type="text" name="jorong" value="{{ old('jorong', $formData['jorong']) }}" class="neo-field-input" placeholder="Nama jorong atau kampung">
                                </div>
                                <div>
                                    <label class="neo-field-label">Nagari / Desa</label>
                                    <input type="text" name="nagari" value="{{ old('nagari', $formData['nagari']) }}" class="neo-field-input" placeholder="Nama nagari atau desa">
                                </div>
                                <div>
                                    <label class="neo-field-label">Kecamatan</label>
                                    <input type="text" name="kecamatan" value="{{ old('kecamatan', $formData['kecamatan']) }}" class="neo-field-input" placeholder="Nama kecamatan">
                                </div>
                                <div class="lg:col-span-2">
                                    <label class="neo-field-label">Koordinat GPS</label>
                                    <input type="text" name="koordinat" value="{{ old('koordinat', $formData['koordinat']) }}" class="neo-field-input" placeholder="Contoh: -0.5071, 100.4478">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section 3: Kontak & Website -->
                    <div class="neo-card">
                        <div class="neo-card-header">
                            <div class="neo-card-icon" style="background: var(--gold); color: var(--night);">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <h2 class="neo-card-title">Kontak & Website</h2>
                                <p class="neo-card-desc">Informasi kontak dan media digital</p>
                            </div>
                        </div>
                        <div class="neo-card-body">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label class="neo-field-label">Telepon / HP</label>
                                    <input type="tel" name="telepon" value="{{ old('telepon', $formData['telepon']) }}" class="neo-field-input" placeholder="Contoh: 0812-3456-7890">
                                </div>
                                <div>
                                    <label class="neo-field-label">Email</label>
                                    <input type="email" name="email" value="{{ old('email', $formData['email']) }}" class="neo-field-input" placeholder="contoh@email.com">
                                </div>
                                <div>
                                    <label class="neo-field-label">Website</label>
                                    <input type="url" name="website" value="{{ old('website', $formData['website']) }}" class="neo-field-input" placeholder="https://www.madrasah.sch.id">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section 4: SK Pendirian -->
                    <div class="neo-card">
                        <div class="neo-card-header">
                            <div class="neo-card-icon" style="background: var(--gold); color: var(--night);">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                            <div>
                                <h2 class="neo-card-title">SK Pendirian</h2>
                                <p class="neo-card-desc">Informasi legalitas pendirian lembaga</p>
                            </div>
                        </div>
                        <div class="neo-card-body">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="neo-field-label">Nomor SK Pendirian</label>
                                    <input type="text" name="sk_pendirian" value="{{ old('sk_pendirian', $formData['sk_pendirian']) }}" class="neo-field-input" placeholder="Contoh: SK.1234/PP.03.03/2008">
                                </div>
                                <div>
                                    <label class="neo-field-label">Tanggal SK</label>
                                    <input type="date" name="tanggal_sk" value="{{ old('tanggal_sk', $formData['tanggal_sk']) }}" class="neo-field-input">
                                </div>
                            </div>
                            <div class="mt-6">
                                <label class="neo-field-label">Nama Komite Lembaga</label>
                                <input type="text" name="komite_lembaga" value="{{ old('komite_lembaga', $formData['komite_lembaga']) }}" class="neo-field-input" placeholder="Nama Ketua Komite Madrasah">
                            </div>
                        </div>
                    </div>

                    <!-- Section 5: Visi Madrasah -->
                    <div class="neo-card">
                        <div class="neo-card-header">
                            <div class="neo-card-icon" style="background: var(--gold); color: var(--night);">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </div>
                            <div>
                                <h2 class="neo-card-title">Visi Madrasah</h2>
                                <p class="neo-card-desc">Visi dan arah lembaga pendidikan</p>
                            </div>
                        </div>
                        <div class="neo-card-body">
                            <label class="neo-field-label">Visi</label>
                            <textarea name="visi" rows="4" class="neo-field-input resize-none" placeholder="Tuliskan visi madrasah secara lengkap...">{{ old('visi', $formData['visi']) }}</textarea>
                        </div>
                    </div>

                    <!-- Section 6: Jarak Madrasah ke... -->
                    <div class="neo-card">
                        <div class="neo-card-header">
                            <div class="neo-card-icon" style="background: var(--gold); color: var(--night);">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path d="M21 21l-5-5"/>
                                </svg>
                            </div>
                            <div>
                                <h2 class="neo-card-title">Jarak Madrasah ke...</h2>
                                <p class="neo-card-desc">Jarak ke berbagai lokasi penting (dalam kilometer)</p>
                            </div>
                        </div>
                        <div class="neo-card-body">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                <div>
                                    <label class="neo-field-label">Pusat Provinsi</label>
                                    <input type="number" name="jarak_pusat_provinsi" value="{{ old('jarak_pusat_provinsi', $formData['jarak_pusat_provinsi']) }}" min="0" step="0.1" class="neo-field-input" placeholder="0">
                                </div>
                                <div>
                                    <label class="neo-field-label">Pusat Kabupaten/Kota</label>
                                    <input type="number" name="jarak_pusat_kabupaten" value="{{ old('jarak_pusat_kabupaten', $formData['jarak_pusat_kabupaten']) }}" min="0" step="0.1" class="neo-field-input" placeholder="0">
                                </div>
                                <div>
                                    <label class="neo-field-label">Kecamatan</label>
                                    <input type="number" name="jarak_kecamatan" value="{{ old('jarak_kecamatan', $formData['jarak_kecamatan']) }}" min="0" step="0.1" class="neo-field-input" placeholder="0">
                                </div>
                                <div>
                                    <label class="neo-field-label">Kanwil Kemenag</label>
                                    <input type="number" name="jarak_kanwil_kemenag" value="{{ old('jarak_kanwil_kemenag', $formData['jarak_kanwil_kemenag']) }}" min="0" step="0.1" class="neo-field-input" placeholder="0">
                                </div>
                                <div>
                                    <label class="neo-field-label">Kemenag Kabupaten</label>
                                    <input type="number" name="jarak_kemenag_kab" value="{{ old('jarak_kemenag_kab', $formData['jarak_kemenag_kab']) }}" min="0" step="0.1" class="neo-field-input" placeholder="0">
                                </div>
                                <div>
                                    <label class="neo-field-label">KUA</label>
                                    <input type="number" name="jarak_kua" value="{{ old('jarak_kua', $formData['jarak_kua']) }}" min="0" step="0.1" class="neo-field-input" placeholder="0">
                                </div>
                            </div>
                            <div class="mt-6">
                                <h3 class="neo-field-label" style="margin-bottom: 1rem;">Jarak ke Lembaga Pendidikan Terdekat</h3>
                                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                                    <div>
                                        <label class="neo-field-label" style="font-size: 0.75rem;">RA Terdekat</label>
                                        <input type="number" name="jarak_ra_terdekat" value="{{ old('jarak_ra_terdekat', $formData['jarak_ra_terdekat']) }}" min="0" step="0.1" class="neo-field-input" placeholder="0">
                                    </div>
                                    <div>
                                        <label class="neo-field-label" style="font-size: 0.75rem;">MI Terdekat</label>
                                        <input type="number" name="jarak_mi_terdekat" value="{{ old('jarak_mi_terdekat', $formData['jarak_mi_terdekat']) }}" min="0" step="0.1" class="neo-field-input" placeholder="0">
                                    </div>
                                    <div>
                                        <label class="neo-field-label" style="font-size: 0.75rem;">MTs Terdekat</label>
                                        <input type="number" name="jarak_mts_terdekat" value="{{ old('jarak_mts_terdekat', $formData['jarak_mts_terdekat']) }}" min="0" step="0.1" class="neo-field-input" placeholder="0">
                                    </div>
                                    <div>
                                        <label class="neo-field-label" style="font-size: 0.75rem;">MA Terdekat</label>
                                        <input type="number" name="jarak_ma_terdekat" value="{{ old('jarak_ma_terdekat', $formData['jarak_ma_terdekat']) }}" min="0" step="0.1" class="neo-field-input" placeholder="0">
                                    </div>
                                    <div>
                                        <label class="neo-field-label" style="font-size: 0.75rem;">Pontren Terdekat</label>
                                        <input type="number" name="jarak_pontren_terdekat" value="{{ old('jarak_pontren_terdekat', $formData['jarak_pontren_terdekat']) }}" min="0" step="0.1" class="neo-field-input" placeholder="0">
                                    </div>
                                    <div>
                                        <label class="neo-field-label" style="font-size: 0.75rem;">TK/PAUD Terdekat</label>
                                        <input type="number" name="jarak_tk_terdekat" value="{{ old('jarak_tk_terdekat', $formData['jarak_tk_terdekat']) }}" min="0" step="0.1" class="neo-field-input" placeholder="0">
                                    </div>
                                </div>
                            </div>
                            <div class="mt-6">
                                <h3 class="neo-field-label" style="margin-bottom: 1rem;">Jarak ke Sekolah Umum Terdekat</h3>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label class="neo-field-label">SD Terdekat</label>
                                        <input type="number" name="jarak_sd_terdekat" value="{{ old('jarak_sd_terdekat', $formData['jarak_sd_terdekat']) }}" min="0" step="0.1" class="neo-field-input" placeholder="0">
                                    </div>
                                    <div>
                                        <label class="neo-field-label">SMP Terdekat</label>
                                        <input type="number" name="jarak_smp_terdekat" value="{{ old('jarak_smp_terdekat', $formData['jarak_smp_terdekat']) }}" min="0" step="0.1" class="neo-field-input" placeholder="0">
                                    </div>
                                    <div>
                                        <label class="neo-field-label">SMA Terdekat</label>
                                        <input type="number" name="jarak_sma_terdekat" value="{{ old('jarak_sma_terdekat', $formData['jarak_sma_terdekat']) }}" min="0" step="0.1" class="neo-field-input" placeholder="0">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="neo-form-actions">
                        <a href="{{ url('/') }}" class="neo-btn-action-cancel">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                            Kembali
                        </a>
                        <button type="reset" class="neo-btn-action-reset">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            Reset
                        </button>
                        <button type="submit" class="neo-btn-action-save">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                            Simpan Data
                        </button>
                    </div>

                </form>
            </div>
        </section>
    </main>
</x-layouts.app>
