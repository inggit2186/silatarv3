<x-layouts.app title="Daftar - SILATAR">
    <main class="neo-mirai neo-auth-page">

        <div class="neo-auth-card">
            <div class="neo-auth-form-wrapper">
                <!-- Header -->
                <div class="neo-auth-header">
                    <div class="neo-auth-logo">
                        <img src="{{ asset('favicon.webp') }}" alt="SILATAR">
                    </div>
                    <p class="neo-auth-brand-kicker">Pendaftaran Akun</p>
                    <h1 class="neo-auth-brand-title">SILATAR</h1>
                    <p class="neo-auth-brand-subtitle">Kementerian Agama Tanah Datar</p>
                </div>

                <!-- User Type Badge -->
                <div class="register-type-badge register-type-{{ $userType }}">
                    @if($userType === 'honorer')
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                        </svg>
                        <span>Pegawai Honorer Kemenag Tanah Datar</span>
                    @elseif($userType === 'guru_pai')
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 00-.491 6.347A48.62 48.62 0 0112 20.904a48.62 48.62 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.636 50.636 0 00-2.658-.813A59.906 59.906 0 0112 3.493a59.903 59.903 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5"/>
                        </svg>
                        <span>Guru PAI dari Pemerintah Daerah</span>
                    @else
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/>
                        </svg>
                        <span>Masyarakat Umum</span>
                    @endif
                </div>

                <!-- Success Message -->
                @if(session('success'))
                    <div class="neo-auth-alert neo-auth-alert-success">
                        <div class="neo-auth-alert-icon">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="neo-auth-alert-title">Berhasil!</p>
                            <p class="neo-auth-alert-text">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                <!-- Error Alert -->
                @if ($errors->any())
                    <div class="neo-auth-alert neo-auth-alert-error">
                        <div class="neo-auth-alert-icon">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="neo-auth-alert-title">Gagal Mendaftar</p>
                            <p class="neo-auth-alert-text">{{ $errors->first() }}</p>
                        </div>
                    </div>
                @endif

                <!-- Registration Form -->
                <form method="POST" action="{{ route('register.submit') }}" class="neo-auth-form">
                    @csrf
                    <input type="hidden" name="user_type" value="{{ $userType }}">

                    <!-- Type-specific fields -->
                    @if($userType === 'honorer' || $userType === 'guru_pai')
                        <!-- Pegawai Honorer & Guru PAI fields -->
                        <div class="neo-auth-form-section">
                            <h3 class="neo-auth-section-title">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0"/>
                                </svg>
                                Data Kepegawaian
                            </h3>

                            <div class="neo-auth-form-row">
                                <label class="neo-auth-label" for="nomor_induk">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 21l5.25-11.25L21 21m-9-3h7.5M3 5.621a48.474 48.474 0 016-.371m0 0c1.12 0 2.233.038 3.334.114M9 5.25V3m3.334 2.364C11.176 10.658 7.69 15.08 3 17.502m9.334-12.138c.896.061 1.785.147 2.666.257m-4.589 8.495a18.023 18.023 0 01-3.827-5.802"/>
                                    </svg>
                                    @if($userType === 'guru_pai')
                                        NIP (Nomor Induk Pegawai)
                                    @else
                                        NIK (Nomor Induk Kependudukan)
                                    @endif
                                </label>
                                <input
                                    id="nomor_induk"
                                    name="nomor_induk"
                                    type="text"
                                    class="neo-auth-input"
                                    value="{{ old('nomor_induk') }}"
                                    placeholder="{{ $userType === 'guru_pai' ? 'Contoh: 199903022025211004' : 'NIK 16 Digit' }}"
                                    maxlength="{{ $userType === 'guru_pai' ? 50 : 16 }}"
                                    required
                                >
                                @error('nomor_induk')
                                    <span class="neo-field-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="neo-auth-form-row">
                                <label class="neo-auth-label" for="dept_id">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z"/>
                                    </svg>
                                    Unit Kerja
                                </label>
                                <select id="dept_id" name="dept_id" class="neo-auth-input" required onchange="toggleTempatBekerja()">
                                    <option value="">-- Pilih Unit Kerja --</option>
                                    @php
                                        $departments = $userType === 'guru_pai' ? $guruPaiDepartments : $allDepartments;
                                    @endphp
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}" {{ old('dept_id') == $dept->id ? 'selected' : '' }}>
                                            {{ $dept->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('dept_id')
                                    <span class="neo-field-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Tempat Bekerja (shown for special units) -->
                            <div id="tempat_bekerja_row" class="neo-auth-form-row" style="display: none;">
                                <label class="neo-auth-label" for="satker">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/>
                                    </svg>
                                    Tempat Bekerja
                                </label>
                                <input
                                    id="satker"
                                    name="satker"
                                    type="text"
                                    class="neo-auth-input"
                                    value="{{ old('satker') }}"
                                    placeholder="Contoh: SDN 01 Batipuh, MTS Al-Munawwar"
                                >
                                <span class="neo-auth-hint">Isikan nama sekolah/lembaga tempat Anda mengajar</span>
                                @error('satker')
                                    <span class="neo-field-error">{{ $message }}</span>
                                @enderror
                            </div>

                            @if($userType === 'guru_pai')
                            <!-- Jenis ASN for Guru PAI -->
                            <div class="neo-auth-form-row">
                                <label class="neo-auth-label" for="jenis_asn">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38"/>
                                    </svg>
                                    Jenis ASN
                                </label>
                                <select id="jenis_asn" name="jenis_asn" class="neo-auth-input" required>
                                    <option value="">-- Pilih --</option>
                                    <option value="pns" {{ old('jenis_asn') == 'pns' ? 'selected' : '' }}>PNS</option>
                                    <option value="pppk" {{ old('jenis_asn') == 'pppk' ? 'selected' : '' }}>PPPK</option>
                                    <option value="honorer" {{ old('jenis_asn') == 'honorer' ? 'selected' : '' }}>Honorer</option>
                                </select>
                                @error('jenis_asn')
                                    <span class="neo-field-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="neo-auth-form-row">
                                <label class="neo-auth-label" for="nuptk">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 00-.491 6.347A48.62 48.62 0 0112 20.904a48.62 48.62 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.636 50.636 0 00-2.658-.813A59.906 59.906 0 0112 3.493a59.903 59.903 0 0110.399 5.84c-.896.248-1.783.52-2.658.814"/>
                                    </svg>
                                    NUPTK <span class="neo-auth-label-optional">(opsional)</span>
                                </label>
                                <input
                                    id="nuptk"
                                    name="nuptk"
                                    type="text"
                                    class="neo-auth-input"
                                    value="{{ old('nuptk') }}"
                                    placeholder="Nomor UKG/NUPTK"
                                >
                            </div>
                            @endif
                        </div>
                    @endif

                    <!-- Personal Data (all types) -->
                    <div class="neo-auth-form-section">
                        <h3 class="neo-auth-section-title">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                            </svg>
                            Data Pribadi
                        </h3>

                        <div class="neo-auth-form-row">
                            <label class="neo-auth-label" for="name">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                                </svg>
                                Nama Lengkap
                            </label>
                            <input
                                id="name"
                                name="name"
                                type="text"
                                class="neo-auth-input"
                                value="{{ old('name') }}"
                                placeholder="Sesuai KTP"
                                required
                            >
                            @error('name')
                                <span class="neo-field-error">{{ $message }}</span>
                            @enderror
                        </div>

                        @if($userType === 'honorer' || $userType === 'guru_pai')
                        <!-- Kat Jabatan (for Honorer & Guru PAI) -->
                        <div class="neo-auth-form-row">
                            <label class="neo-auth-label" for="kat_jabatan">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                                </svg>
                                Kategori Jabatan
                            </label>
                            <select id="kat_jabatan" name="kat_jabatan" class="neo-auth-input" required>
                                <option value="">-- Pilih --</option>
                                <option value="adm" {{ old('kat_jabatan') == 'adm' ? 'selected' : '' }}>Admin / Staff</option>
                                <option value="guru" {{ old('kat_jabatan') == 'guru' ? 'selected' : '' }}>Guru</option>
                                <option value="kepala" {{ old('kat_jabatan') == 'kepala' ? 'selected' : '' }}>Kepala / Penyelia</option>
                            </select>
                            @error('kat_jabatan')
                                <span class="neo-field-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Pekerjaan (for Honorer & Guru PAI) -->
                        <div class="neo-auth-form-row">
                            <label class="neo-auth-label" for="pekerjaan">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0"/>
                                </svg>
                                Pekerjaan / Jabatan
                            </label>
                            <input
                                id="pekerjaan"
                                name="pekerjaan"
                                type="text"
                                class="neo-auth-input"
                                value="{{ old('pekerjaan') }}"
                                placeholder="Contoh: Guru IPA, Staff Keuangan, Kepala Madrasah"
                                required
                            >
                            @error('pekerjaan')
                                <span class="neo-field-error">{{ $message }}</span>
                            @enderror
                        </div>
                        @else
                        <!-- Pekerjaan & Tempat Bekerja (for Masyarakat) -->
                        <div class="neo-auth-form-row">
                            <label class="neo-auth-label" for="pekerjaan">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0"/>
                                </svg>
                                Pekerjaan
                            </label>
                            <input
                                id="pekerjaan"
                                name="pekerjaan"
                                type="text"
                                class="neo-auth-input"
                                value="{{ old('pekerjaan') }}"
                                placeholder="Contoh: Wiraswasta, Petani, Guru Honorer"
                                required
                            >
                            @error('pekerjaan')
                                <span class="neo-field-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="neo-auth-form-row">
                            <label class="neo-auth-label" for="tempat_bekerja">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/>
                                </svg>
                                Tempat Bekerja
                            </label>
                            <input
                                id="tempat_bekerja"
                                name="tempat_bekerja"
                                type="text"
                                class="neo-auth-input"
                                value="{{ old('tempat_bekerja') }}"
                                placeholder="Contoh: PT. Maju Mundir, UMKM Desa Sua"
                                required
                            >
                            @error('tempat_bekerja')
                                <span class="neo-field-error">{{ $message }}</span>
                            @enderror
                        </div>
                        @endif

                        <div class="neo-auth-form-row" style="display: grid; gap: 1rem; grid-template-columns: repeat(auto-fit, minmax(10rem, 1fr));">
                            @if($userType === 'guru_pai')
                            <div>
                                <label class="neo-auth-label" for="nik">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 21l5.25-11.25L21 21m-9-3h7.5M3 5.621a48.474 48.474 0 016-.371m0 0c1.12 0 2.233.038 3.334.114M9 5.25V3m3.334 2.364C11.176 10.658 7.69 15.08 3 17.502m9.334-12.138c.896.061 1.785.147 2.666.257m-4.589 8.495a18.023 18.023 0 01-3.827-5.802"/>
                                    </svg>
                                    NIK (No. KTP)
                                </label>
                                <input
                                    id="nik"
                                    name="nik"
                                    type="text"
                                    class="neo-auth-input"
                                    value="{{ old('nik') }}"
                                    placeholder="16 digit"
                                    maxlength="16"
                                    required
                                >
                                @error('nik')
                                    <span class="neo-field-error">{{ $message }}</span>
                                @enderror
                            </div>
                            @endif

                            @if($userType === 'honorer' || $userType === 'guru_pai')
                            <div>
                                <label class="neo-auth-label" for="kk">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z"/>
                                    </svg>
                                    No. Kartu Keluarga
                                </label>
                                <input
                                    id="kk"
                                    name="kk"
                                    type="text"
                                    class="neo-auth-input"
                                    value="{{ old('kk') }}"
                                    placeholder="16 digit"
                                    maxlength="16"
                                    required
                                >
                                @error('kk')
                                    <span class="neo-field-error">{{ $message }}</span>
                                @enderror
                            </div>
                            @endif
                        </div>

                        <div class="neo-auth-form-row" style="display: grid; gap: 1rem; grid-template-columns: repeat(auto-fit, minmax(10rem, 1fr));">
                            <div>
                                <label class="neo-auth-label" for="jk">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"/>
                                    </svg>
                                    Jenis Kelamin
                                </label>
                                <select id="jk" name="jenis_kelamin" class="neo-auth-input" required>
                                    <option value="">-- Pilih --</option>
                                    <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                @error('jenis_kelamin')
                                    <span class="neo-field-error">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="neo-auth-form-row" style="display: grid; gap: 1rem; grid-template-columns: repeat(auto-fit, minmax(10rem, 1fr));">
                            <div>
                                <label class="neo-auth-label" for="tempat_lahir">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z"/>
                                    </svg>
                                    Tempat Lahir
                                </label>
                                <input
                                    id="tempat_lahir"
                                    name="tempat_lahir"
                                    type="text"
                                    class="neo-auth-input"
                                    value="{{ old('tempat_lahir') }}"
                                    placeholder="Kota kelahiran"
                                    required
                                >
                                @error('tempat_lahir')
                                    <span class="neo-field-error">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="neo-auth-label" for="tanggal_lahir">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
                                    </svg>
                                    Tanggal Lahir
                                </label>
                                <input
                                    id="tanggal_lahir"
                                    name="tanggal_lahir"
                                    type="date"
                                    class="neo-auth-input"
                                    value="{{ old('tanggal_lahir') }}"
                                    max="{{ date('Y-m-d', strtotime('-1 day')) }}"
                                    required
                                >
                                @error('tanggal_lahir')
                                    <span class="neo-field-error">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="neo-auth-form-row">
                            <label class="neo-auth-label" for="alamat">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/>
                                </svg>
                                Alamat Lengkap
                            </label>
                            <textarea
                                id="alamat"
                                name="alamat"
                                class="neo-auth-input"
                                rows="2"
                                placeholder="Alamat lengkap sesuai KTP"
                                required
                            >{{ old('alamat') }}</textarea>
                            @error('alamat')
                                <span class="neo-field-error">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Contact Data -->
                    <div class="neo-auth-form-section">
                        <h3 class="neo-auth-section-title">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/>
                            </svg>
                            Data Kontak
                        </h3>

                        <div class="neo-auth-form-row" style="display: grid; gap: 1rem; grid-template-columns: repeat(auto-fit, minmax(10rem, 1fr));">
                            <div>
                                <label class="neo-auth-label" for="telp">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3"/>
                                    </svg>
                                    No. WhatsApp
                                </label>
                                <input
                                    id="telp"
                                    name="telp"
                                    type="tel"
                                    class="neo-auth-input"
                                    value="{{ old('telp') }}"
                                    placeholder="08xxxxxxxxxx"
                                    required
                                >
                                @error('telp')
                                    <span class="neo-field-error">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="neo-auth-label" for="email">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
                                    </svg>
                                    Email
                                </label>
                                <input
                                    id="email"
                                    name="email"
                                    type="email"
                                    class="neo-auth-input"
                                    value="{{ old('email') }}"
                                    placeholder="nama@email.com"
                                    required
                                >
                                @error('email')
                                    <span class="neo-field-error">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="neo-auth-form-section">
                        <h3 class="neo-auth-section-title">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
                            </svg>
                            Kata Sandi
                        </h3>

                        <div class="neo-auth-form-row" style="display: grid; gap: 1rem; grid-template-columns: repeat(auto-fit, minmax(10rem, 1fr));">
                            <div>
                                <label class="neo-auth-label" for="password">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/>
                                    </svg>
                                    Kata Sandi
                                </label>
                                <div class="neo-auth-input-wrap">
                                    <input
                                        id="password"
                                        name="password"
                                        type="password"
                                        class="neo-auth-input neo-auth-input-password"
                                        placeholder="Min. 8 karakter"
                                        required
                                    >
                                    <button type="button" onclick="togglePassword('password')" class="neo-auth-toggle-password">
                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </button>
                                </div>
                                @error('password')
                                    <span class="neo-field-error">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="neo-auth-label" for="password_confirmation">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/>
                                    </svg>
                                    Konfirmasi Kata Sandi
                                </label>
                                <input
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    type="password"
                                    class="neo-auth-input"
                                    placeholder="Ulangi kata sandi"
                                    required
                                >
                            </div>
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="neo-auth-form-row">
                        <button type="submit" class="neo-auth-btn" style="width: 100%;">
                            Daftar Sekarang
                        </button>
                    </div>
                </form>

                <!-- Divider -->
                <div class="section-divider geometric" style="margin: 1.5rem 0;"></div>

                <!-- Back to Login -->
                <a href="{{ route('login') }}" class="neo-auth-btn-secondary">
                    ← Kembali ke Login
                </a>

                <!-- Register Type Selector -->
                <div class="register-type-selector">
                    <p class="register-type-selector-text">Lihat pilihan lain:</p>
                    <div class="register-type-links">
                        @if($userType !== 'honorer')
                            <a href="{{ route('register') }}?type=honorer" class="register-type-link">
                                Honorer
                            </a>
                        @endif
                        @if($userType !== 'guru_pai')
                            <a href="{{ route('register') }}?type=guru_pai" class="register-type-link">
                                Guru PAI
                            </a>
                        @endif
                        @if($userType !== 'masyarakat')
                            <a href="{{ route('register') }}?type=masyarakat" class="register-type-link">
                                Masyarakat
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="neo-auth-footer">
                <p class="neo-auth-footer-text">
                    &copy; {{ date('Y') }} SILATAR - Kemenag Tanah Datar
                </p>
            </div>
        </div>
    </main>

    <script>
        const specialUnits = @json($specialUnits ?? [998, 999]);

        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
            input.setAttribute('type', type);
        }

        function toggleTempatBekerja() {
            const deptSelect = document.getElementById('dept_id');
            const tempatBekerjaRow = document.getElementById('tempat_bekerja_row');

            if (!deptSelect || !tempatBekerjaRow) return;

            const selectedValue = parseInt(deptSelect.value);

            if (specialUnits.includes(selectedValue)) {
                tempatBekerjaRow.style.display = 'flex';
                tempatBekerjaRow.style.animation = 'fadeSlideIn 0.3s ease-out';
            } else {
                tempatBekerjaRow.style.display = 'none';
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            toggleTempatBekerja();
        });
    </script>

    <style>
        @keyframes fadeSlideIn {
            from {
                opacity: 0;
                transform: translateY(-8px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .neo-auth-section-title svg {
            width: 1.25rem;
            height: 1.25rem;
            margin-right: 0.5rem;
            vertical-align: middle;
            color: var(--gold);
        }

        .neo-auth-label svg {
            width: 0.9rem;
            height: 0.9rem;
            margin-right: 0.375rem;
            vertical-align: middle;
            color: var(--ink-soft);
        }

        .neo-auth-hint {
            display: block;
            font-size: 0.7rem;
            color: var(--ink-soft);
            margin-top: 0.25rem;
            font-style: italic;
        }
    </style>
</x-layouts.app>
