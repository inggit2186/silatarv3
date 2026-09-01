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
        .profile-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: rgba(255,255,255,0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            font-weight: bold;
            border: 3px solid rgba(255,255,255,0.3);
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
        .section-icon.purple { background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); }
        .section-icon.orange { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); }
        .form-floating {
            position: relative;
        }
        .form-floating label {
            position: absolute;
            top: 50%;
            left: 1rem;
            transform: translateY(-50%);
            color: #94a3b8;
            transition: all 0.2s ease;
            pointer-events: none;
            background: white;
            padding: 0 0.25rem;
        }
        .form-floating input:focus + label,
        .form-floating input:not(:placeholder-shown) + label {
            top: -0.5rem;
            left: 0.75rem;
            font-size: 0.75rem;
            color: #0891b2;
        }
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
            max-height: 2000px;
        }
        .rotate-90 {
            transform: rotate(90deg);
        }
    </style>

    <div class="space-y-6">
        {{-- Profile Header --}}
        <div class="profile-header">
            <div class="relative z-10 flex items-center gap-4">
                <div class="profile-avatar">
                    @php
                        $initials = strtoupper(substr($user->name, 0, 2));
                    @endphp
                    {{ $initials }}
                </div>
                <div>
                    <h1 class="text-2xl font-bold">{{ $user->name }}</h1>
                    <p class="text-cyan-100 text-sm">{{ $user->email ?? 'Belum ada email' }}</p>
                    <div class="mt-2 flex items-center gap-2">
                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-white/20 backdrop-blur-sm">
                            {{ ucfirst($user->role) }}
                        </span>
                        <span class="px-3 py-1 rounded-full text-xs font-medium {{ $user->status == 1 ? 'bg-green-500/30' : 'bg-red-500/30' }}">
                            {{ $user->status == 1 ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </div>
                </div>
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

        <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
            @csrf
            @method('PUT')

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
                                    <input type="text" id="name" name="name" class="form-input @error('name') error @enderror" value="{{ old('name', $user->name) }}" placeholder="Masukkan nama lengkap" required>
                                    @error('name')
                                        <p class="form-error">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" id="email" name="email" class="form-input @error('email') error @enderror" value="{{ old('email', $user->email) }}" placeholder="nama@email.com">
                                    @error('email')
                                        <p class="form-error">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="grid gap-4 md:grid-cols-2">
                                <div class="form-group">
                                    <label for="nomor_induk" class="form-label required">NIP / Nomor Induk</label>
                                    <input type="text" id="nomor_induk" name="nomor_induk" class="form-input @error('nomor_induk') error @enderror" value="{{ old('nomor_induk', $user->nomor_induk) }}" placeholder="Masukkan NIP" required>
                                    @error('nomor_induk')
                                        <p class="form-error">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="jk" class="form-label">Jenis Kelamin</label>
                                    <select id="jk" name="jk" class="form-select">
                                        <option value="Pria" {{ old('jk', $user->jk) === 'Pria' ? 'selected' : '' }}>Pria</option>
                                        <option value="Wanita" {{ old('jk', $user->jk) === 'Wanita' ? 'selected' : '' }}>Wanita</option>
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
                            <p class="text-xs text-slate-500">Penugasan role dan unit kerja</p>
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
                                        @foreach($roles as $role)
                                            <option value="{{ $role }}" {{ old('role', $user->role) === $role ? 'selected' : '' }}>
                                                {{ ucfirst($role) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('role')
                                        <p class="form-error">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="dept_id" class="form-label">Unit Kerja</label>
                                    <select id="dept_id" name="dept_id" class="form-select">
                                        <option value="">Pilih Unit Kerja</option>
                                        @foreach($departments as $dept)
                                            <option value="{{ $dept->id }}" {{ old('dept_id', $user->dept_id) == $dept->id ? 'selected' : '' }}>
                                                {{ $dept->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="grid gap-4 md:grid-cols-2">
                                <div class="form-group">
                                    <label for="kat_jabatan" class="form-label">Kategori Jabatan</label>
                                    <select id="kat_jabatan" name="kat_jabatan" class="form-select">
                                        <option value="">Pilih Kategori Jabatan</option>
                                        @foreach($katJabatanOptions as $jabatan)
                                            <option value="{{ $jabatan }}" {{ old('kat_jabatan', $user->kat_jabatan) === $jabatan ? 'selected' : '' }}>
                                                {{ ucfirst($jabatan) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <p class="text-xs text-slate-500 mt-1">Menentukan hierarki atasan pada laporan kinerja</p>
                                </div>
                                <div class="form-group">
                                    <label for="pekerjaan" class="form-label">Pekerjaan/Jabatan</label>
                                    <input type="text" id="pekerjaan" name="pekerjaan" class="form-input" value="{{ old('pekerjaan', $user->pekerjaan) }}" placeholder="Contoh: Staff Tata Usaha">
                                </div>
                            </div>

                            {{-- Madrasah Assignment --}}
                            <div class="bg-gradient-to-r from-blue-50 to-cyan-50 rounded-xl p-4 border border-blue-100">
                                <div class="flex items-center gap-3 mb-3">
                                    <div class="w-8 h-8 rounded-lg bg-blue-500 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-blue-800">Assign ke Madrasah</h4>
                                        <p class="text-xs text-blue-600">Untuk user di madrasah (MI, MTs, MA)</p>
                                    </div>
                                </div>
                                <div class="form-group mb-0">
                                    <select id="madrasah_id" name="madrasah_id" class="form-select">
                                        <option value="">-- Pilih Madrasah (opsional) --</option>
                                        @php
                                            $groupedMadrasah = $madrasahs->groupBy('kategori');
                                        @endphp
                                        @foreach($groupedMadrasah as $kategori => $madrasahList)
                                            <optgroup label="{{ strtoupper($kategori) }}">
                                                @foreach($madrasahList as $madrasah)
                                                    <option value="{{ $madrasah->id }}" {{ old('madrasah_id', $user->madrasah_id) == $madrasah->id ? 'selected' : '' }}>
                                                        {{ $madrasah->nama }} ({{ $madrasah->nsm ?? 'Tanpa NSM' }})
                                                    </option>
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            {{-- Custom Supervisor Assignment --}}
                            <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl p-4 border border-purple-100">
                                <div class="flex items-center gap-3 mb-3">
                                    <div class="w-8 h-8 rounded-lg bg-purple-500 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-purple-800">Custom Supervisor</h4>
                                        <p class="text-xs text-purple-600">Override atasan untuk laporan kinerja (opsional)</p>
                                    </div>
                                </div>
                                <div class="form-group mb-0">
                                    <select id="custom_supervisor_id" name="custom_supervisor_id" class="form-select">
                                        <option value="">-- Mengikuti Hierarki Unit Kerja --</option>
                                        @foreach($supervisors as $supervisor)
                                            <option value="{{ $supervisor->id }}" {{ old('custom_supervisor_id', $user->custom_supervisor_id) == $supervisor->id ? 'selected' : '' }}>
                                                {{ $supervisor->name }} ({{ ucfirst($supervisor->kat_jabatan) }}) - {{ $supervisor->department_name ?? '-' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <p class="text-xs text-slate-500 mt-2">
                                        <svg class="w-4 h-4 inline text-purple-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Jika dipilih, laporan kinerja akan ditandatangani oleh supervisor ini.
                                        <br>Kosongkan jika ingin mengikuti hierarki unit kerja bawaan.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Section 3: Password --}}
                <div class="section-card">
                    <div class="section-header" onclick="toggleSection('passwordInfo')">
                        <div class="section-icon orange">
                            <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-slate-800">Ubah Password</h3>
                            <p class="text-xs text-slate-500">Kosongkan jika tidak ingin diubah</p>
                        </div>
                        <svg id="passwordInfoIcon" class="w-5 h-5 text-slate-400 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                    <div id="passwordInfoContent" class="collapsible-content">
                        <div class="p-6">
                            <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-4">
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-amber-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                    <p class="text-sm text-amber-700">Kosongkan password jika tidak ingin diubah.</p>
                                </div>
                            </div>
                            <div class="grid gap-4 md:grid-cols-2">
                                <div class="form-group">
                                    <label for="password" class="form-label">Password Baru</label>
                                    <input type="password" id="password" name="password" class="form-input" placeholder="Min. 8 karakter">
                                </div>
                                <div class="form-group">
                                    <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" placeholder="Ulangi password baru">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Section 4: Informasi Tambahan --}}
                <div class="section-card">
                    <div class="section-header" onclick="toggleSection('additionalInfo')">
                        <div class="section-icon purple">
                            <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-slate-800">Informasi Tambahan</h3>
                            <p class="text-xs text-slate-500">Data pendukung dan kontak</p>
                        </div>
                        <svg id="additionalInfoIcon" class="w-5 h-5 text-slate-400 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                    <div id="additionalInfoContent" class="collapsible-content">
                        <div class="p-6 space-y-4">
                            {{-- Pekerjaan & Kontak --}}
                            <div class="grid gap-4 md:grid-cols-2">
                                <div class="form-group">
                                    <label for="pekerjaan" class="form-label">Pekerjaan/Jabatan</label>
                                    <input type="text" id="pekerjaan" name="pekerjaan" class="form-input" value="{{ old('pekerjaan', $user->pekerjaan) }}" placeholder="Contoh: Pranata Komputer">
                                </div>
                                <div class="form-group">
                                    <label for="telp" class="form-label">No. Telepon</label>
                                    <input type="text" id="telp" name="telp" class="form-input" value="{{ old('telp', $user->telp) }}" placeholder="08xxxxxxxxxx">
                                </div>
                            </div>

                            {{-- Identitas --}}
                            <div class="grid gap-4 md:grid-cols-2">
                                <div class="form-group">
                                    <label for="nik" class="form-label">NIK (Nomor Induk Kependudukan)</label>
                                    <input type="text" id="nik" name="nik" class="form-input" value="{{ old('nik', $user->nik ?? '') }}" placeholder="16 digit NIK" maxlength="16">
                                </div>
                                <div class="form-group">
                                    <label for="kk" class="form-label">Nomor KK (Kartu Keluarga)</label>
                                    <input type="text" id="kk" name="kk" class="form-input" value="{{ old('kk', $user->kk ?? '') }}" placeholder="16 digit nomor KK" maxlength="16">
                                </div>
                            </div>

                            <div class="grid gap-4 md:grid-cols-2">
                                <div class="form-group">
                                    <label for="npwp" class="form-label">NPWP</label>
                                    <input type="text" id="npwp" name="npwp" class="form-input" value="{{ old('npwp', $user->npwp ?? '') }}" placeholder="Nomor NPWP">
                                </div>
                                <div class="form-group">
                                    <label for="nikah" class="form-label">Status Nikah</label>
                                    <select id="nikah" name="nikah" class="form-select">
                                        <option value="">Pilih</option>
                                        <option value="1" {{ old('nikah', $user->nikah ?? '') == '1' ? 'selected' : '' }}>Sudah Menikah</option>
                                        <option value="0" {{ old('nikah', $user->nikah ?? '') == '0' ? 'selected' : '' }}>Belum Menikah</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Keluarga --}}
                            <div class="bg-slate-50 rounded-xl p-4 border border-slate-200">
                                <h4 class="font-semibold text-slate-700 mb-3 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-purple-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    Data Keluarga
                                </h4>
                                <div class="grid gap-4 md:grid-cols-2">
                                    <div class="form-group">
                                        <label for="nama_istri_suami" class="form-label">Nama Istri/Suami</label>
                                        <input type="text" id="nama_istri_suami" name="nama_istri_suami" class="form-input" value="{{ old('nama_istri_suami', $user->nama_istri_suami ?? '') }}" placeholder="Nama pasangan">
                                    </div>
                                    <div class="form-group">
                                        <label for="pjob" class="form-label">Pekerjaan Pasangan</label>
                                        <input type="text" id="pjob" name="pjob" class="form-input" value="{{ old('pjob', $user->pjob ?? '') }}" placeholder="Pekerjaan suami/istri">
                                    </div>
                                </div>
                                <div class="grid gap-4 md:grid-cols-2">
                                    <div class="form-group">
                                        <label for="jml_anak" class="form-label">Jumlah Anak</label>
                                        <input type="number" id="jml_anak" name="jml_anak" class="form-input" value="{{ old('jml_anak', $user->jml_anak ?? '') }}" placeholder="0" min="0">
                                    </div>
                                    <div class="form-group">
                                        <label for="nama_ibu" class="form-label">Nama Ibu Kandung</label>
                                        <input type="text" id="nama_ibu" name="nama_ibu" class="form-input" value="{{ old('nama_ibu', $user->nama_ibu ?? '') }}" placeholder="Nama ibu kandung">
                                    </div>
                                </div>
                            </div>

                            {{-- Tanggal Lahir & Alamat --}}
                            <div class="grid gap-4 md:grid-cols-2">
                                <div class="form-group">
                                    <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                                    <input type="text" id="tempat_lahir" name="tempat_lahir" class="form-input" value="{{ old('tempat_lahir', $user->tempat_lahir) }}" placeholder="Kota kelahiran">
                                </div>
                                <div class="form-group">
                                    <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                                    <input type="date" id="tanggal_lahir" name="tanggal_lahir" class="form-input" value="{{ old('tanggal_lahir', $user->tanggal_lahir) }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="alamat" class="form-label">Alamat</label>
                                <textarea id="alamat" name="alamat" class="form-textarea" rows="2" placeholder="Alamat lengkap">{{ old('alamat', $user->alamat) }}</textarea>
                            </div>

                            {{-- Jenis Pegawai & Status --}}
                            <div class="grid gap-4 md:grid-cols-2">
                                <div class="form-group">
                                    <label for="jenis_pjob" class="form-label">Jenis Pekerjaan Pasangan</label>
                                    <select id="jenis_pjob" name="jenis_pjob" class="form-select">
                                        <option value="">Pilih</option>
                                        <option value="ASN" {{ old('jenis_pjob', $user->jenis_pjob ?? '') === 'ASN' ? 'selected' : '' }}>ASN</option>
                                        <option value="NON" {{ old('jenis_pjob', $user->jenis_pjob ?? '') === 'NON' ? 'selected' : '' }}>Non-ASN</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="instansi" class="form-label">Instansi</label>
                                    <input type="text" id="instansi" name="instansi" class="form-input" value="{{ old('instansi', $user->instansi ?? '') }}" placeholder="Nama instansi">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="status" class="form-label">Status</label>
                                <select id="status" name="status" class="form-select">
                                    <option value="1" {{ old('status', $user->status) == '1' ? 'selected' : '' }}>Aktif</option>
                                    <option value="0" {{ old('status', $user->status) == '0' ? 'selected' : '' }}>Nonaktif</option>
                                </select>
                            </div>

                            {{-- Bio --}}
                            <div class="form-group">
                                <label for="bio" class="form-label">Bio / Deskripsi Diri</label>
                                <textarea id="bio" name="bio" class="form-textarea" rows="3" placeholder="Ceritakan tentang diri Anda...">{{ old('bio', $user->bio ?? '') }}</textarea>
                            </div>

                            {{-- Social Media --}}
                            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-4 border border-blue-100">
                                <h4 class="font-semibold text-blue-800 mb-3 flex items-center gap-2">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                                    </svg>
                                    Social Media
                                </h4>
                                <div class="grid gap-4 md:grid-cols-2">
                                    <div class="form-group">
                                        <label for="facebook" class="form-label">Facebook</label>
                                        <input type="text" id="facebook" name="facebook" class="form-input" value="{{ old('facebook', $user->facebook ?? '') }}" placeholder="URL atau username Facebook">
                                    </div>
                                    <div class="form-group">
                                        <label for="twitter" class="form-label">Twitter / X</label>
                                        <input type="text" id="twitter" name="twitter" class="form-input" value="{{ old('twitter', $user->twitter ?? '') }}" placeholder="URL atau username Twitter">
                                    </div>
                                </div>
                                <div class="grid gap-4 md:grid-cols-2">
                                    <div class="form-group">
                                        <label for="instagram" class="form-label">Instagram</label>
                                        <input type="text" id="instagram" name="instagram" class="form-input" value="{{ old('instagram', $user->instagram ?? '') }}" placeholder="URL atau username Instagram">
                                    </div>
                                    <div class="form-group">
                                        <label for="linkedin" class="form-label">LinkedIn</label>
                                        <input type="text" id="linkedin" name="linkedin" class="form-input" value="{{ old('linkedin', $user->linkedin ?? '') }}" placeholder="URL atau username LinkedIn">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- User Info --}}
            <div class="mt-4 bg-slate-50 rounded-xl p-4 border border-slate-200">
                <div class="flex items-center justify-between text-sm text-slate-500">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Dibuat: {{ $user->created_at }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        <span>Diperbarui: {{ $user->updated_at }}</span>
                    </div>
                </div>
            </div>

            {{-- Submit Buttons --}}
            <div class="mt-6 flex items-center gap-3">
                <button type="submit" class="btn-gradient text-white px-6 py-3 rounded-xl font-semibold flex items-center gap-2">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Perubahan
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

        // Initialize sections
        document.addEventListener('DOMContentLoaded', function() {
            // All sections start expanded by default
        });
    </script>
</x-admin.layouts.app>
