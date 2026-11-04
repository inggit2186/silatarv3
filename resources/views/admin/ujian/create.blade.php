<x-admin.layouts.app title="{{ $title ?? 'Tambah Ujian' }}">
    <div class="page-header">
        <div class="page-header-content">
            <span class="page-label">// Ujian</span>
            <h1 class="page-title">Tambah Ujian</h1>
            <p class="page-subtitle">Buat paket ujian baru, lalu tambahkan soal dan peserta</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.ujian.index') }}" class="btn btn-secondary">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5m7 7l-7-7 7-7"/></svg>
                Kembali
            </a>
        </div>
    </div>

    @include('admin.ujian._partials.flash')

    <form method="POST" action="{{ route('admin.ujian.store') }}">
        @csrf
        <div class="card">
            <div class="card-header">
                <div class="flex items-center gap-3">
                    <div class="stat-icon primary" style="width: 36px; height: 36px;">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14,2 14,8 20,8"/></svg>
                    </div>
                    <div>
                        <h3 class="card-title">Identitas Ujian</h3>
                        <p class="text-sm text-muted">Informasi dasar paket ujian yang akan dibuat</p>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-group">
                        <label class="form-label">Kode Ujian <span class="text-danger">*</span></label>
                        <input type="text" name="kode" value="{{ old('kode') }}" placeholder="Contoh: UJN-2026-001" class="form-input" required>
                        @error('kode')
                        <p class="form-error text-danger text-sm">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="draft">Draft</option>
                            <option value="terbit">Terbit</option>
                            <option value="selesai">Selesai</option>
                        </select>
                        <p class="form-hint text-sm text-muted">Ujian baru otomatis berstatus Draft sampai soal dan peserta tersedia.</p>
                    </div>
                    <div class="form-group md:col-span-2">
                        <label class="form-label">Judul Ujian <span class="text-danger">*</span></label>
                        <input type="text" name="judul" value="{{ old('judul') }}" placeholder="Contoh: Ujian Competensi Wajib PEG 2026" class="form-input" required>
                        @error('judul')
                        <p class="form-error text-danger text-sm">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-group md:col-span-2">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" rows="3" placeholder="Penjelasan singkat mengenai ujian ini" class="form-textarea">{{ old('deskripsi') }}</textarea>
                    </div>
                    <div class="form-group md:col-span-2">
                        <label class="form-label">Materi / Referensi</label>
                        <textarea name="materi" rows="4" placeholder="Materi yang perlu dipelajari peserta sebelum mengikuti ujian" class="form-textarea">{{ old('materi') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Durasi (menit) <span class="text-danger">*</span></label>
                        <input type="number" name="durasi_menit" value="{{ old('durasi_menit', 60) }}" min="1" max="600" class="form-input" required>
                        @error('durasi_menit')
                        <p class="form-error text-danger text-sm">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Acak Urutan Soal</label>
                        <select name="acak_soal" class="form-select">
                            <option value="0">Tidak</option>
                            <option value="1">Ya</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Mulai Ujian</label>
                        <input type="datetime-local" name="mulai_at" value="{{ old('mulai_at') }}" class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Batas Ujian Selesai</label>
                        <input type="datetime-local" name="selesai_at" value="{{ old('selesai_at') }}" class="form-input">
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-3 mt-6">
            <button type="submit" class="btn btn-primary">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                Simpan Ujian
            </button>
            <a href="{{ route('admin.ujian.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</x-admin.layouts.app>
