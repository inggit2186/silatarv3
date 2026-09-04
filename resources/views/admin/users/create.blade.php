<x-admin.layouts.app>
    <style>
        .profile-header {
            background: linear-gradient(135deg, #0891b2 0%, #06b6d4 50%, #22d3ee 100%);
            border-radius: 1rem;
            padding: 2rem;
            color: white;
            position: relative;
            overflow: hidden;
        }
        .profile-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        }
        .section-card {
            background: white;
            border-radius: 1rem;
            border: 1px solid #e2e8f0;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        .section-card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        .section-header {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .section-header:hover {
            background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
        }
        .section-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .section-icon.blue { background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); }
        .section-icon.green { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
        .section-icon.orange { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); }
        .btn-gradient {
            background: linear-gradient(135deg, #0891b2 0%, #06b6d4 100%);
            border: none;
            transition: all 0.3s ease;
        }
        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(8, 145, 178, 0.4);
        }
        .collapsible-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }
        .collapsible-content.expanded {
            max-height: none;
            overflow: visible;
        }
        .rotate-90 {
            transform: rotate(90deg);
        }
    </style>

    <div class="space-y-6 pb-24">
        {{-- Page Header --}}
        <div class="profile-header">
            <div class="relative z-10">
                <h1 class="text-2xl font-bold">Tambah Pengguna Baru</h1>
                <p class="text-cyan-100 text-sm mt-1">Tambahkan pengguna baru ke sistem SILATAR</p>
            </div>
        </div>

        {{-- Success Message --}}
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 rounded-xl p-4 flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-green-500 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-green-800">Berhasil!</p>
                    <p class="text-sm text-green-600">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.users.store') }}">
            @csrf

            <div class="space-y-4">
                {{-- Section 1: Informasi Dasar --}}
                <div class="section-card">
                    <div class="section-header" onclick="toggleSection('basicInfo')">
                        <div class="section-icon blue">
                            <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-slate-800">Informasi Dasar</h3>
                            <p class="text-xs text-slate-500">Nama, email, dan identitas pengguna</p>
                        </div>
                        <svg id="basicInfoIcon" class="w-5 h-5 text-slate-400 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                    <div id="basicInfoContent" class="collapsible-content expanded">
                        <div class="p-6 space-y-4">
                            <div class="grid gap-4 md:grid-cols-2">
                                <div class="form-group">
                                    <label for="name" class="form-label required">Nama Lengkap</label>
                                    <input type="text" id="name" name="name" class="form-input @error('name') error @enderror" value="{{ old('name') }}" placeholder="Masukkan nama lengkap" required>
                                    @error('name')
                                        <p class="form-error">
                                            <svg class="form-error-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" id="email" name="email" class="form-input @error('email') error @enderror" value="{{ old('email') }}" placeholder="nama@email.com">
                                    @error('email')
                                        <p class="form-error">
                                            <svg class="form-error-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>

                            <div class="grid gap-4 md:grid-cols-2">
                                <div class="form-group">
                                    <label for="nomor_induk" class="form-label required">NIP / Nomor Induk</label>
                                    <input type="text" id="nomor_induk" name="nomor_induk" class="form-input @error('nomor_induk') error @enderror" value="{{ old('nomor_induk') }}" placeholder="Masukkan NIP / Nomor Induk" required>
                                    @error('nomor_induk')
                                        <p class="form-error">
                                            <svg class="form-error-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                    <p class="text-xs text-slate-500 mt-1">Nomor identitas unik untuk pengguna (NIP/NIK)</p>
                                </div>
                                <div class="form-group">
                                    <label for="jk" class="form-label">Jenis Kelamin</label>
                                    <select id="jk" name="jk" class="form-select">
                                        <option value="Pria" {{ old('jk') === 'Pria' ? 'selected' : '' }}>Pria</option>
                                        <option value="Wanita" {{ old('jk') === 'Wanita' ? 'selected' : '' }}>Wanita</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Section 2: Role & Unit Kerja --}}
                <div class="section-card">
                    <div class="section-header" onclick="toggleSection('roleInfo')">
                        <div class="section-icon green">
                            <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-slate-800">Role & Unit Kerja</h3>
                            <p class="text-xs text-slate-500">Hak akses dan unit kerja pengguna</p>
                        </div>
                        <svg id="roleInfoIcon" class="w-5 h-5 text-slate-400 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                    <div id="roleInfoContent" class="collapsible-content expanded">
                        <div class="p-6 space-y-4">
                            <div class="grid gap-4 md:grid-cols-2">
                                <div class="form-group">
                                    <label for="role" class="form-label required">Role</label>
                                    <select id="role" name="role" class="form-select @error('role') error @enderror" required>
                                        <option value="">Pilih Role</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role }}" {{ old('role') === $role ? 'selected' : '' }}>
                                                {{ ucfirst($role) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('role')
                                        <p class="form-error">
                                            <svg class="form-error-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="dept_id" class="form-label">Unit Kerja</label>
                                    <select id="dept_id" name="dept_id" class="form-select @error('dept_id') error @enderror">
                                        <option value="">Pilih Unit Kerja</option>
                                        @foreach($departments as $dept)
                                            <option value="{{ $dept->id }}" {{ old('dept_id') == $dept->id ? 'selected' : '' }}>
                                                {{ $dept->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('dept_id')
                                        <p class="form-error">
                                            <svg class="form-error-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>

                            <div class="grid gap-4 md:grid-cols-2">
                                <div class="form-group">
                                    <label for="status" class="form-label">Status</label>
                                    <select id="status" name="status" class="form-select">
                                        <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>Aktif</option>
                                        <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Nonaktif</option>
                                    </select>
                                    <p class="text-xs text-slate-500 mt-1">Status aktif/nonaktif pengguna</p>
                                </div>
                                <div class="form-group">
                                    <label for="pekerjaan" class="form-label">Pekerjaan/Jabatan</label>
                                    <input type="text" id="pekerjaan" name="pekerjaan" class="form-input" value="{{ old('pekerjaan') }}" placeholder="Contoh: Pranata Komputer">
                                </div>
                            </div>

                            <div class="grid gap-4 md:grid-cols-2">
                                <div class="form-group">
                                    <label for="telp" class="form-label">No. Telepon</label>
                                    <input type="text" id="telp" name="telp" class="form-input" value="{{ old('telp') }}" placeholder="08xxxxxxxxxx">
                                </div>
                                <div class="form-group">
                                    <label for="alamat" class="form-label">Alamat</label>
                                    <input type="text" id="alamat" name="alamat" class="form-input" value="{{ old('alamat') }}" placeholder="Alamat lengkap">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Section 3: Informasi Kepegawaian --}}
                <div class="section-card">
                    <div class="section-header" onclick="toggleSection('kepegawaianInfo')">
                        <div class="section-icon orange">
                            <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-slate-800">Informasi Kepegawaian</h3>
                            <p class="text-xs text-slate-500">Kategori jabatan, tipe ASN, dan sertifikasi</p>
                        </div>
                        <svg id="kepegawaianInfoIcon" class="w-5 h-5 text-slate-400 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                    <div id="kepegawaianInfoContent" class="collapsible-content expanded">
                        <div class="p-6 space-y-4">
                            <div class="grid gap-4 md:grid-cols-2">
                                <div class="form-group">
                                    <label for="kat_jabatan" class="form-label">Kategori Jabatan</label>
                                    <select id="kat_jabatan" name="kat_jabatan" class="form-select">
                                        <option value="">Pilih Kategori</option>
                                        <option value="guru" {{ old('kat_jabatan') === 'guru' ? 'selected' : '' }}>Guru</option>
                                        <option value="kepala" {{ old('kat_jabatan') === 'kepala' ? 'selected' : '' }}>Kepala</option>
                                        <option value="kasi" {{ old('kat_jabatan') === 'kasi' ? 'selected' : '' }}>Kasi</option>
                                        <option value="kasubbag" {{ old('kat_jabatan') === 'kasubbag' ? 'selected' : '' }}>Kasubbag</option>
                                        <option value="kaur" {{ old('kat_jabatan') === 'kaur' ? 'selected' : '' }}>Kaur</option>
                                        <option value="staf" {{ old('kat_jabatan') === 'staf' ? 'selected' : '' }}>Staf</option>
                                        <option value="penghulu" {{ old('kat_jabatan') === 'penghulu' ? 'selected' : '' }}>Penghulu</option>
                                        <option value="penyuluh" {{ old('kat_jabatan') === 'penyuluh' ? 'selected' : '' }}>Penyuluh</option>
                                        <option value="adm" {{ old('kat_jabatan') === 'adm' ? 'selected' : '' }}>Admin</option>
                                        <option value="honorer" {{ old('kat_jabatan') === 'honorer' ? 'selected' : '' }}>Honorer</option>
                                        <option value="umum" {{ old('kat_jabatan') === 'umum' ? 'selected' : '' }}>Umum</option>
                                    </select>
                                    <p class="text-xs text-slate-500 mt-1">Kategori jabatan pegawai</p>
                                </div>
                                <div class="form-group">
                                    <label for="tipe_asn" class="form-label">Tipe ASN</label>
                                    <select id="tipe_asn" name="tipe_asn" class="form-select">
                                        <option value="">Pilih Tipe</option>
                                        <option value="pns" {{ old('tipe_asn') === 'pns' ? 'selected' : '' }}>PNS</option>
                                        <option value="pppk" {{ old('tipe_asn') === 'pppk' ? 'selected' : '' }}>PPPK</option>
                                        <option value="cpns" {{ old('tipe_asn') === 'cpns' ? 'selected' : '' }}>CPNS</option>
                                        <option value="honorer" {{ old('tipe_asn') === 'honorer' ? 'selected' : '' }}>Honorer</option>
                                        <option value="umum" {{ old('tipe_asn') === 'umum' ? 'selected' : '' }}>Umum</option>
                                    </select>
                                    <p class="text-xs text-slate-500 mt-1">Status kepegawaian</p>
                                </div>
                            </div>

                            <div class="grid gap-4 md:grid-cols-2">
                                <div class="form-group">
                                    <label for="serdik" class="form-label">Sertifikasi Guru</label>
                                    <select id="serdik" name="serdik" class="form-select">
                                        <option value="">Pilih</option>
                                        <option value="sertifikasi" {{ old('serdik') === 'sertifikasi' ? 'selected' : '' }}>Sertifikasi</option>
                                        <option value="non-sertifikasi" {{ old('serdik') === 'non-sertifikasi' ? 'selected' : '' }}>Non-Sertifikasi</option>
                                        <option value="non-guru" {{ old('serdik') === 'non-guru' ? 'selected' : '' }}>Non-Guru</option>
                                    </select>
                                    <p class="text-xs text-slate-500 mt-1">Status sertifikasi (untuk guru)</p>
                                </div>
                                <div class="form-group">
                                    <label for="golongan" class="form-label">Golongan</label>
                                    <select id="golongan" name="golongan" class="form-select">
                                        <option value="">Pilih Golongan</option>
                                        <option value="I/a" {{ old('golongan') === 'I/a' ? 'selected' : '' }}>I/a - Juru Muda</option>
                                        <option value="I/b" {{ old('golongan') === 'I/b' ? 'selected' : '' }}>I/b - Juru Muda TK I</option>
                                        <option value="I/c" {{ old('golongan') === 'I/c' ? 'selected' : '' }}>I/c - Juru TK I</option>
                                        <option value="I/d" {{ old('golongan') === 'I/d' ? 'selected' : '' }}>I/d - Juru</option>
                                        <option value="II/a" {{ old('golongan') === 'II/a' ? 'selected' : '' }}>II/a - Pengatur Muda</option>
                                        <option value="II/b" {{ old('golongan') === 'II/b' ? 'selected' : '' }}>II/b - Pengatur Muda TK I</option>
                                        <option value="II/c" {{ old('golongan') === 'II/c' ? 'selected' : '' }}>II/c - Pengatur TK I</option>
                                        <option value="II/d" {{ old('golongan') === 'II/d' ? 'selected' : '' }}>II/d - Pengatur</option>
                                        <option value="III/a" {{ old('golongan') === 'III/a' ? 'selected' : '' }}>III/a - Penata Muda TK I</option>
                                        <option value="III/b" {{ old('golongan') === 'III/b' ? 'selected' : '' }}>III/b - Penata Muda</option>
                                        <option value="III/c" {{ old('golongan') === 'III/c' ? 'selected' : '' }}>III/c - Penata TK I</option>
                                        <option value="III/d" {{ old('golongan') === 'III/d' ? 'selected' : '' }}>III/d - Penata</option>
                                        <option value="IV/a" {{ old('golongan') === 'IV/a' ? 'selected' : '' }}>IV/a - Pembina TK I</option>
                                        <option value="IV/b" {{ old('golongan') === 'IV/b' ? 'selected' : '' }}>IV/b - Pembina</option>
                                        <option value="IV/c" {{ old('golongan') === 'IV/c' ? 'selected' : '' }}>IV/c - Pembina Utama Muda</option>
                                        <option value="IV/d" {{ old('golongan') === 'IV/d' ? 'selected' : '' }}>IV/d - Pembina Utama Madya</option>
                                        <option value="IV/e" {{ old('golongan') === 'IV/e' ? 'selected' : '' }}>IV/e - Pembina Utama</option>
                                    </select>
                                    <p class="text-xs text-slate-500 mt-1">Pangkat/golongan PNS</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Section 4: Password --}}
                <div class="section-card">
                    <div class="section-header" onclick="toggleSection('passwordInfo')">
                        <div class="section-icon orange">
                            <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-slate-800">Password</h3>
                            <p class="text-xs text-slate-500">Password untuk login pengguna</p>
                        </div>
                        <svg id="passwordInfoIcon" class="w-5 h-5 text-slate-400 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                    <div id="passwordInfoContent" class="collapsible-content expanded">
                        <div class="p-6 space-y-4">
                            <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-4">
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-amber-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                    <p class="text-sm text-amber-700">Password harus minimal 8 karakter.</p>
                                </div>
                            </div>

                            <div class="grid gap-4 md:grid-cols-2">
                                <div class="form-group">
                                    <label for="password" class="form-label required">Password</label>
                                    <input type="password" id="password" name="password" class="form-input @error('password') error @enderror" placeholder="Min. 8 karakter" required>
                                    @error('password')
                                        <p class="form-error">
                                            <svg class="form-error-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="password_confirmation" class="form-label required">Konfirmasi Password</label>
                                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" placeholder="Ulangi password" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Submit Buttons --}}
            <div class="mt-6 flex items-center gap-3">
                <button type="submit" class="btn-gradient text-white px-6 py-3 rounded-xl font-semibold flex items-center gap-2">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Pengguna
                </button>
                <a href="{{ route('admin.users.index') }}" class="px-6 py-3 rounded-xl font-semibold border border-slate-300 text-slate-600 hover:bg-slate-50 transition-colors">
                    Kembali
                </a>
            </div>
        </form>
    </div>

    <script>
        function toggleSection(sectionId) {
            const content = document.getElementById(sectionId + 'Content');
            const icon = document.getElementById(sectionId + 'Icon');

            if (content.classList.contains('expanded')) {
                content.classList.remove('expanded');
                icon.classList.remove('rotate-90');
            } else {
                content.classList.add('expanded');
                icon.classList.add('rotate-90');
            }
        }
    </script>
</x-admin.layouts.app>
