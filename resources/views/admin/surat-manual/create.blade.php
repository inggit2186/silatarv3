<x-admin.layouts.app>
    <div class="page-header">
        <div class="page-header-content">
            <span class="page-label">// Surat Manual</span>
            <h1 class="page-title">Input Surat Baru</h1>
            <p class="page-subtitle">Tambahkan surat baru secara manual</p>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.surat-manual.store') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="grid gap-6 lg:grid-cols-2">
            <!-- Info Surat -->
            <div class="card">
                <div class="card-header">
                    <div class="flex items-center gap-3">
                        <div class="stat-icon emerald" style="width: 36px; height: 36px;">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="card-title">Informasi Surat</h3>
                            <p class="text-sm text-muted">Data utama surat</p>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label">Nama Pengirim <span class="text-danger">*</span></label>
                        <input type="text" name="pemohon" value="{{ old('pemohon') }}" required class="form-input" placeholder="Nama pengirim">
                        @error('pemohon')
                        <p class="text-sm mt-1" style="color: var(--danger);">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Asal Pengirim</label>
                        <input type="text" name="asal_pengirim" value="{{ old('asal_pengirim') }}" class="form-input" placeholder="Instansi / unit asal pengirim">
                        @error('asal_pengirim')
                        <p class="text-sm mt-1" style="color: var(--danger);">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Tanggal Surat <span class="text-danger">*</span></label>
                        <input type="date" name="tgl_surat" value="{{ old('tgl_surat', date('Y-m-d')) }}" required class="form-input">
                        @error('tgl_surat')
                        <p class="text-sm mt-1" style="color: var(--danger);">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Nomor Surat <span class="text-danger">*</span></label>
                        <input type="text" name="no_surat" value="{{ old('no_surat') }}" required class="form-input" placeholder="Contoh: B-001/PP.00/09/2026">
                        @error('no_surat')
                        <p class="text-sm mt-1" style="color: var(--danger);">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Jenis Layanan <span class="text-danger">*</span></label>
                        <select name="layanan_id" required class="form-select">
                            <option value="">-- Pilih Layanan --</option>
                            <option value="999" {{ old('layanan_id') == 999 ? 'selected' : '' }} style="font-weight: bold; color: #0891b2;">
                                ★ Layanan Persuratan / Lainnya
                            </option>
                            <option value="" disabled>────────────────────────</option>
                            @foreach($layanans as $layanan)
                            <option value="{{ $layanan->id }}" {{ old('layanan_id') == $layanan->id ? 'selected' : '' }}>
                                {{ $layanan->nama }}
                            </option>
                            @endforeach
                        </select>
                        @error('layanan_id')
                        <p class="text-sm mt-1" style="color: var(--danger);">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Judul / Perihal <span class="text-danger">*</span></label>
                        <input type="text" name="judul" value="{{ old('judul') }}" required class="form-input" maxlength="50" placeholder="Perihal surat">
                        @error('judul')
                        <p class="text-sm mt-1" style="color: var(--danger);">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Tujuan</label>
                        <select name="tujuan" class="form-select">
                            <option value="">-- Pilih Tujuan --</option>
                            @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ old('tujuan') == $dept->id ? 'selected' : '' }}>
                                {{ $dept->nama }}
                            </option>
                            @endforeach
                        </select>
                        @error('tujuan')
                        <p class="text-sm mt-1" style="color: var(--danger);">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Keterangan</label>
                        <textarea name="deskripsi" rows="4" class="form-input" style="height: auto; padding: 10px 12px;" placeholder="Keterangan singkat (opsional)">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                        <p class="text-sm mt-1" style="color: var(--danger);">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- File Upload -->
            <div class="card">
                <div class="card-header">
                    <div class="flex items-center gap-3">
                        <div class="stat-icon cyan" style="width: 36px; height: 36px;">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="card-title">File Surat</h3>
                            <p class="text-sm text-muted">Upload file surat & lampiran</p>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label">File Surat Utama</label>
                        <input type="file" name="file_surat" class="form-input" accept=".pdf,.doc,.docx">
                        <p class="text-xs mt-1" style="color: var(--text-muted);">Format: PDF, DOC, DOCX. Maks 2MB</p>
                        @error('file_surat')
                        <p class="text-sm mt-1" style="color: var(--danger);">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Lampiran</label>
                        <input type="file" name="lampiran" class="form-input" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                        <p class="text-xs mt-1" style="color: var(--text-muted);">Format: PDF, DOC, DOCX, JPG, PNG. Maks 2MB (opsional)</p>
                        @error('lampiran')
                        <p class="text-sm mt-1" style="color: var(--danger);">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.surat-manual.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
                Simpan Surat
            </button>
        </div>
    </form>
</x-admin.layouts.app>
