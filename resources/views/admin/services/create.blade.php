<x-admin.layouts.app>
    <div class="page-header">
        <div class="page-header-content">
            <span class="page-label">// Layanan</span>
            <h1 class="page-title">Tambah Layanan Baru</h1>
            <p class="page-subtitle">Tambahkan layanan baru ke sistem</p>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.services.store') }}" class="space-y-6">
        @csrf

        <div class="grid gap-6 lg:grid-cols-2">
            <!-- Basic Info -->
            <div class="card">
                <div class="card-header">
                    <div class="flex items-center gap-3">
                        <div class="stat-icon emerald" style="width: 36px; height: 36px;">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="card-title">Informasi Dasar</h3>
                            <p class="text-sm text-muted">Data utama layanan</p>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label">Nama Layanan <span class="text-danger">*</span></label>
                        <input type="text" name="nama" value="{{ old('nama') }}" required class="form-input" placeholder="Contoh: Pembuatan KTP">
                        @error('nama')
                        <p class="text-sm mt-1" style="color: var(--danger);">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Unit Kerja</label>
                        <select name="dept_id" class="form-select">
                            <option value="">Semua Unit</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" {{ old('dept_id') == $dept->id ? 'selected' : '' }}>{{ $dept->nama }}</option>
                            @endforeach
                        </select>
                        <p class="text-xs mt-1" style="color: var(--text-muted);">Kosongkan jika berlaku untuk semua unit</p>
                        @error('dept_id')
                        <p class="text-sm mt-1" style="color: var(--danger);">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" rows="4" class="form-input" style="height: auto; padding: 10px 12px;" placeholder="Deskripsi singkat layanan">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                        <p class="text-sm mt-1" style="color: var(--danger);">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Output Layanan</label>
                        <input type="text" name="output" value="{{ old('output') }}" class="form-input" placeholder="Contoh: Terbitnya Surat Keterangan">
                        @error('output')
                        <p class="text-sm mt-1" style="color: var(--danger);">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Settings -->
            <div class="card">
                <div class="card-header">
                    <div class="flex items-center gap-3">
                        <div class="stat-icon cyan" style="width: 36px; height: 36px;">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="card-title">Pengaturan</h3>
                            <p class="text-sm text-muted">Opsi tambahan layanan</p>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="form-group">
                            <label class="form-label">Waktu Proses</label>
                            <input type="text" name="waktu" value="{{ old('waktu') }}" class="form-input" placeholder="Contoh: 3x24 jam">
                            @error('waktu')
                            <p class="text-sm mt-1" style="color: var(--danger);">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Biaya (Rp)</label>
                            <input type="number" name="biaya" value="{{ old('biaya', 0) }}" min="0" class="form-input" placeholder="0">
                            <p class="text-xs mt-1" style="color: var(--text-muted);">0 = Gratis</p>
                            @error('biaya')
                            <p class="text-sm mt-1" style="color: var(--danger);">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Tidak Aktif</option>
                        </select>
                        @error('status')
                        <p class="text-sm mt-1" style="color: var(--danger);">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Layanan Khusus</label>
                        <select name="spesial" class="form-select">
                            <option value="0" {{ old('spesial', '0') == '0' ? 'selected' : '' }}>Tidak</option>
                            <option value="1" {{ old('spesial') == '1' ? 'selected' : '' }}>Ya</option>
                        </select>
                        <p class="text-xs mt-1" style="color: var(--text-muted);">Layanan khusus ditampilkan di halaman utama</p>
                        @error('spesial')
                        <p class="text-sm mt-1" style="color: var(--danger);">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="alert alert-info mt-4">
                        <svg class="alert-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="alert-message">Setelah layanan dibuat, Anda dapat menambahkan persyaratan/berkas yang diperlukan.</span>
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
                Simpan & Lanjut ke Persyaratan
            </button>
            <a href="{{ route('admin.services.index') }}" class="btn btn-secondary">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Batal
            </a>
        </div>
    </form>
</x-admin.layouts.app>
