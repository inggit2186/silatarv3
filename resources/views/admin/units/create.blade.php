<x-admin.layouts.app>
    <div class="page-header">
        <div class="page-header-content">
            <span class="page-label">// Unit Kerja</span>
            <h1 class="page-title">Tambah Unit Kerja</h1>
            <p class="page-subtitle">Tambahkan unit kerja baru ke sistem</p>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.units.store') }}" class="space-y-6">
        @csrf

        <div class="grid gap-6 lg:grid-cols-2">
            <!-- Basic Info -->
            <div class="card">
                <div class="card-header">
                    <div class="flex items-center gap-3">
                        <div class="stat-icon amber" style="width: 36px; height: 36px;">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="card-title">Informasi Dasar</h3>
                            <p class="text-sm text-muted">Data utama unit kerja</p>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label">Nama Unit Kerja <span class="text-danger">*</span></label>
                        <input type="text" name="nama" value="{{ old('nama') }}" required class="form-input" placeholder="Contoh: KUA Kecamatan X">
                        @error('nama')
                        <p class="text-sm mt-1" style="color: var(--danger);">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Kategori <span class="text-danger">*</span></label>
                        <select name="kategori" required class="form-select">
                            <option value="">Pilih Kategori</option>
                            @foreach($kategoriOptions as $key => $label)
                                <option value="{{ $key }}" {{ old('kategori') == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('kategori')
                        <p class="text-sm mt-1" style="color: var(--danger);">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">NPSM / NPS</label>
                        <input type="text" name="npsm" value="{{ old('npsm') }}" class="form-input" placeholder="Contoh: 1234567890">
                        @error('npsm')
                        <p class="text-sm mt-1" style="color: var(--danger);">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>Aktif (Intern)</option>
                            <option value="2" {{ old('status') == '2' ? 'selected' : '' }}>Aktif (Satker)</option>
                            <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Tidak Aktif</option>
                        </select>
                        @error('status')
                        <p class="text-sm mt-1" style="color: var(--danger);">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Contact Info -->
            <div class="card">
                <div class="card-header">
                    <div class="flex items-center gap-3">
                        <div class="stat-icon cyan" style="width: 36px; height: 36px;">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="card-title">Informasi Kontak</h3>
                            <p class="text-sm text-muted">Alamat dan kontak unit kerja</p>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label">Alamat</label>
                        <textarea name="alamat" rows="3" class="form-input" style="height: auto; padding: 10px 12px;" placeholder="Alamat lengkap">{{ old('alamat') }}</textarea>
                        @error('alamat')
                        <p class="text-sm mt-1" style="color: var(--danger);">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="form-group">
                            <label class="form-label">Kecamatan</label>
                            <input type="text" name="kecamatan" value="{{ old('kecamatan') }}" class="form-input" placeholder="Kecamatan">
                            @error('kecamatan')
                            <p class="text-sm mt-1" style="color: var(--danger);">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Kabupaten/Kota</label>
                            <input type="text" name="kabupaten" value="{{ old('kabupaten') }}" class="form-input" placeholder="Kabupaten/Kota">
                            @error('kabupaten')
                            <p class="text-sm mt-1" style="color: var(--danger);">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Telepon</label>
                        <input type="text" name="telepon" value="{{ old('telepon') }}" class="form-input" placeholder="Contoh: 0752-123456">
                        @error('telepon')
                        <p class="text-sm mt-1" style="color: var(--danger);">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="form-input" placeholder="email@example.com">
                        @error('email')
                        <p class="text-sm mt-1" style="color: var(--danger);">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit -->
        <div class="flex items-center gap-3">
            <button type="submit" class="btn btn-primary">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
                Simpan
            </button>
            <a href="{{ route('admin.units.index') }}" class="btn btn-secondary">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Batal
            </a>
        </div>
    </form>
</x-admin.layouts.app>
