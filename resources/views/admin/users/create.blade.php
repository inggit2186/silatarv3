<x-admin.layouts.app>
    {{-- Page Header --}}
    <div class="admin-page-header">
        <div>
            <h1 class="admin-page-title">Tambah Pengguna Baru</h1>
            <p class="admin-page-subtitle">Tambahkan pengguna baru ke sistem SILATAR</p>
        </div>
    </div>

    {{-- Form Card --}}
    <div class="card max-w-2xl">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.users.store') }}">
                @csrf

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
                                value="{{ old('name') }}"
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

                        {{-- Email --}}
                        <div class="form-group">
                            <label for="email" class="form-label">Email</label>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                class="form-input @error('email') error @enderror"
                                value="{{ old('email') }}"
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
                                value="{{ old('nomor_induk') }}"
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
                            <p class="form-helper">Nomor identitas unik untuk pengguna (NIP/NIK/NIPON)</p>
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
                </div>

                {{-- Password Section --}}
                <div class="mb-6">
                    <h3 class="mb-4 flex items-center gap-2 text-sm font-semibold text-slate-700">
                        <svg class="h-5 w-5 text-cyan-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        Password
                    </h3>

                    <div class="space-y-4">
                        <div class="form-group max-w-md">
                            <label for="password" class="form-label required">Password</label>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                class="form-input @error('password') error @enderror"
                                placeholder="Min. 8 karakter"
                                required
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

                        <div class="form-group max-w-md">
                            <label for="password_confirmation" class="form-label required">Konfirmasi Password</label>
                            <input
                                type="password"
                                id="password_confirmation"
                                name="password_confirmation"
                                class="form-input"
                                placeholder="Ulangi password"
                                required
                            >
                        </div>
                    </div>
                </div>

                {{-- Additional Info Section (Collapsible) --}}
                <div class="mb-6 border-t border-slate-100 pt-6">
                    <button
                        type="button"
                        class="flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-cyan-600"
                        onclick="toggleAdditionalInfo()"
                    >
                        <svg id="additionalInfoIcon" class="h-4 w-4 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                        Informasi Tambahan (Opsional)
                    </button>

                    <div id="additionalInfoSection" class="mt-4 hidden space-y-4">
                        <div class="grid gap-4 md:grid-cols-2">
                            <div class="form-group">
                                <label for="jk" class="form-label">Jenis Kelamin</label>
                                <select id="jk" name="jk" class="form-select">
                                    <option value="Pria" {{ old('jk') === 'Pria' ? 'selected' : '' }}>Pria</option>
                                    <option value="Wanita" {{ old('jk') === 'Wanita' ? 'selected' : '' }}>Wanita</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="pekerjaan" class="form-label">Pekerjaan/Jabatan</label>
                                <input
                                    type="text"
                                    id="pekerjaan"
                                    name="pekerjaan"
                                    class="form-input"
                                    value="{{ old('pekerjaan') }}"
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
                                    value="{{ old('telp') }}"
                                    placeholder="08xxxxxxxxxx"
                                >
                            </div>

                            <div class="form-group">
                                <label for="status" class="form-label">Status</label>
                                <select id="status" name="status" class="form-select">
                                    <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>Aktif</option>
                                    <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Nonaktif</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="alamat" class="form-label">Alamat</label>
                            <textarea
                                id="alamat"
                                name="alamat"
                                class="form-textarea"
                                rows="2"
                                placeholder="Alamat lengkap">{{ old('alamat') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Submit Buttons --}}
                <div class="flex items-center gap-3 border-t border-slate-100 pt-6">
                    <button type="submit" class="btn btn-primary">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        Simpan
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Batal</a>
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