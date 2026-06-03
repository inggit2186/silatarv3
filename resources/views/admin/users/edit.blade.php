<x-admin.layouts.app>
    {{-- Page Header --}}
    <div class="admin-page-header">
        <div>
            <h1 class="admin-page-title">Edit Pengguna</h1>
            <p class="admin-page-subtitle">Perbarui data pengguna: {{ $user->name }}</p>
        </div>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success mb-6">
            <svg class="alert-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div class="alert-content">
                <p class="alert-title">Berhasil</p>
                <p class="alert-message">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    {{-- Form Card --}}
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
                @csrf
                @method('PUT')

                {{-- Basic Info Section --}}
                <div class="mb-6">
                    <h3 class="mb-4 flex items-center gap-2 text-sm font-semibold text-slate-700">
                        <svg class="h-5 w-5 text-cyan-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Informasi Dasar
                    </h3>

                    <div class="space-y-4">
                        {{-- Name --}}
                        <div class="form-group">
                            <label for="name" class="form-label required">Nama Lengkap</label>
                            <input
                                type="text"
                                id="name"
                                name="name"
                                class="form-input @error('name') error @enderror"
                                value="{{ old('name', $user->name) }}"
                                placeholder="Masukkan nama lengkap"
                                required
                            >
                            @error('name')
                                <p class="form-error">
                                    <svg class="form-error-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="grid gap-4 md:grid-cols-2">
                            {{-- Email --}}
                            <div class="form-group">
                                <label for="email" class="form-label">Email</label>
                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    class="form-input @error('email') error @enderror"
                                    value="{{ old('email', $user->email) }}"
                                    placeholder="nama@email.com"
                                >
                                @error('email')
                                    <p class="form-error">
                                        <svg class="form-error-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- NIP / Nomor Induk --}}
                            <div class="form-group">
                                <label for="nomor_induk" class="form-label required">NIP / Nomor Induk</label>
                                <input
                                    type="text"
                                    id="nomor_induk"
                                    name="nomor_induk"
                                    class="form-input @error('nomor_induk') error @enderror"
                                    value="{{ old('nomor_induk', $user->nomor_induk) }}"
                                    placeholder="Masukkan NIP / Nomor Induk"
                                    required
                                >
                                @error('nomor_induk')
                                    <p class="form-error">
                                        <svg class="form-error-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Role & Department Section --}}
                <div class="mb-6">
                    <h3 class="mb-4 flex items-center gap-2 text-sm font-semibold text-slate-700">
                        <svg class="h-5 w-5 text-cyan-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        Role & Unit Kerja
                    </h3>

                    <div class="grid gap-4 md:grid-cols-2">
                        {{-- Role --}}
                        <div class="form-group">
                            <label for="role" class="form-label required">Role</label>
                            <select
                                id="role"
                                name="role"
                                class="form-select @error('role') error @enderror"
                                required
                            >
                                @foreach($roles as $role)
                                    <option value="{{ $role }}" {{ old('role', $user->role) === $role ? 'selected' : '' }}>
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

                        {{-- Department --}}
                        <div class="form-group">
                            <label for="dept_id" class="form-label">Unit Kerja</label>
                            <select
                                id="dept_id"
                                name="dept_id"
                                class="form-select @error('dept_id') error @enderror"
                            >
                                <option value="">Pilih Unit Kerja</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}" {{ old('dept_id', $user->dept_id) == $dept->id ? 'selected' : '' }}>
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
                </div>

                {{-- Password Section --}}
                <div class="mb-6">
                    <h3 class="mb-4 flex items-center gap-2 text-sm font-semibold text-slate-700">
                        <svg class="h-5 w-5 text-cyan-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        Ubah Password
                    </h3>

                    <div class="rounded-xl border border-amber-200 bg-amber-50 p-4">
                        <p class="text-sm text-amber-700">
                            <svg class="inline h-4 w-4 mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            Kosongkan password jika tidak ingin diubah.
                        </p>
                        <div class="mt-4 grid gap-4 md:grid-cols-2">
                            <div class="form-group mb-0">
                                <label for="password" class="form-label">Password Baru</label>
                                <input
                                    type="password"
                                    id="password"
                                    name="password"
                                    class="form-input @error('password') error @enderror"
                                    placeholder="Min. 8 karakter"
                                >
                                @error('password')
                                    <p class="form-error">
                                        <svg class="form-error-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div class="form-group mb-0">
                                <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                                <input
                                    type="password"
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    class="form-input"
                                    placeholder="Ulangi password baru"
                                >
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Additional Info Section --}}
                <div class="mb-6 border-t border-slate-100 pt-6">
                    <button
                        type="button"
                        class="flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-cyan-600"
                        onclick="toggleAdditionalInfo()"
                    >
                        <svg id="additionalInfoIcon" class="h-4 w-4 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                        Informasi Tambahan
                    </button>

                    <div id="additionalInfoSection" class="mt-4 hidden space-y-4">
                        <div class="grid gap-4 md:grid-cols-2">
                            <div class="form-group">
                                <label for="jk" class="form-label">Jenis Kelamin</label>
                                <select id="jk" name="jk" class="form-select">
                                    <option value="Pria" {{ old('jk', $user->jk) === 'Pria' ? 'selected' : '' }}>Pria</option>
                                    <option value="Wanita" {{ old('jk', $user->jk) === 'Wanita' ? 'selected' : '' }}>Wanita</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="pekerjaan" class="form-label">Pekerjaan/Jabatan</label>
                                <input
                                    type="text"
                                    id="pekerjaan"
                                    name="pekerjaan"
                                    class="form-input"
                                    value="{{ old('pekerjaan', $user->pekerjaan) }}"
                                    placeholder="Contoh: Pranata Komputer"
                                >
                            </div>
                        </div>

                        <div class="grid gap-4 md:grid-cols-2">
                            <div class="form-group">
                                <label for="telp" class="form-label">No. Telepon</label>
                                <input
                                    type="text"
                                    id="telp"
                                    name="telp"
                                    class="form-input"
                                    value="{{ old('telp', $user->telp) }}"
                                    placeholder="08xxxxxxxxxx"
                                >
                            </div>

                            <div class="form-group">
                                <label for="status" class="form-label">Status</label>
                                <select id="status" name="status" class="form-select">
                                    <option value="1" {{ old('status', $user->status) == '1' ? 'selected' : '' }}>Aktif</option>
                                    <option value="0" {{ old('status', $user->status) == '0' ? 'selected' : '' }}>Nonaktif</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid gap-4 md:grid-cols-2">
                            <div class="form-group">
                                <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                                <input
                                    type="text"
                                    id="tempat_lahir"
                                    name="tempat_lahir"
                                    class="form-input"
                                    value="{{ old('tempat_lahir', $user->tempat_lahir) }}"
                                    placeholder="Kota kelahiran"
                                >
                            </div>

                            <div class="form-group">
                                <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                                <input
                                    type="date"
                                    id="tanggal_lahir"
                                    name="tanggal_lahir"
                                    class="form-input"
                                    value="{{ old('tanggal_lahir', $user->tanggal_lahir) }}"
                                >
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="alamat" class="form-label">Alamat</label>
                            <textarea
                                id="alamat"
                                name="alamat"
                                class="form-textarea"
                                rows="2"
                                placeholder="Alamat lengkap">{{ old('alamat', $user->alamat) }}</textarea>
                        </div>

                        <div class="grid gap-4 md:grid-cols-2">
                            <div class="form-group">
                                <label for="jenis_pjob" class="form-label">Jenis Pegawai</label>
                                <select id="jenis_pjob" name="jenis_pjob" class="form-select">
                                    <option value="">Pilih</option>
                                    <option value="ASN" {{ old('jenis_pjob', $user->jenis_pjob) === 'ASN' ? 'selected' : '' }}>ASN</option>
                                    <option value="NON" {{ old('jenis_pjob', $user->jenis_pjob) === 'NON' ? 'selected' : '' }}>Non-ASN</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="instansi" class="form-label">Instansi</label>
                                <input
                                    type="text"
                                    id="instansi"
                                    name="instansi"
                                    class="form-input"
                                    value="{{ old('instansi', $user->instansi) }}"
                                    placeholder="Nama instansi"
                                >
                            </div>
                        </div>
                    </div>
                </div>

                {{-- User Info --}}
                <div class="mb-6 rounded-xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-500">
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <span class="font-medium text-slate-700">Dibuat:</span>
                            {{ $user->created_at }}
                        </div>
                        <div>
                            <span class="font-medium text-slate-700">Diperbarui:</span>
                            {{ $user->updated_at }}
                        </div>
                    </div>
                </div>

                {{-- Submit Buttons --}}
                <div class="flex items-center gap-3 border-t border-slate-100 pt-6">
                    <button type="submit" class="btn btn-primary">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        Simpan Perubahan
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Kembali</a>
                </div>
            </form>
        </div>
    </div>
</x-admin.layouts.app>

@push('scripts')
<script>
    function toggleAdditionalInfo() {
        const section = document.getElementById('additionalInfoSection');
        const icon = document.getElementById('additionalInfoIcon');

        section.classList.toggle('hidden');
        icon.classList.toggle('rotate-90');
    }
</script>
@endpush