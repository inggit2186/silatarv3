<x-admin.layouts.app title="{{ $title ?? 'Tambah Publikasi' }}">
    <div class="page-header">
        <div class="page-header-content">
            <span class="page-label">// Dokumen</span>
            <h1 class="page-title">Tambah Publikasi Baru</h1>
            <p class="page-subtitle">Unggah dokumen publikasi yang dapat diunduh oleh pengguna portal SILATAR</p>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.publikasi.store') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="grid gap-6 lg:grid-cols-3">
            <div class="lg:col-span-2 space-y-6">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            <label class="form-label">
                                <svg class="inline w-4 h-4 mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                                Judul Publikasi
                            </label>
                            <input type="text" name="title" value="{{ old('title') }}" required class="form-input" placeholder="Masukkan judul publikasi...">
                            @error('title')
                            <p class="text-danger text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            <label class="form-label">
                                <svg class="inline w-4 h-4 mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 6h16M4 10h16M4 14h10M4 18h7"/></svg>
                                Deskripsi
                            </label>
                            <textarea name="description" rows="5" required class="form-input" placeholder="Jelaskan isi atau keperluan dokumen publikasi ini...">{{ old('description') }}</textarea>
                            @error('description')
                            <p class="text-danger text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            <label class="form-label">
                                <svg class="inline w-4 h-4 mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                File Dokumen
                            </label>
                            <input type="file" name="file" required class="form-input" accept=".pdf,.doc,.docx,.xls,.xlsx,.csv,.ppt,.pptx,.zip,.rar,.png,.jpg,.jpeg">
                            <p class="text-xs mt-1" style="color: var(--text-muted);">Format yang didukung: PDF, Office, CSV, gambar, dan arsip (maks 10MB).</p>
                            @error('file')
                            <p class="text-danger text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title mb-4">
                            <svg class="inline w-4 h-4 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Pengaturan
                        </h3>

                        <div class="space-y-4">
                            <div class="form-group mb-0">
                                <label class="form-label">Kategori</label>
                                <select name="category" class="form-select">
                                    <option value="">Tanpa kategori</option>
                                    @foreach($categories as $key => $label)
                                    <option value="{{ $key }}" {{ old('category') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group mb-0">
                                <label class="form-label">Status</label>
                                <select name="status" required class="form-select">
                                    <option value="draft" {{ old('status', 'published') == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="published" {{ old('status', 'published') == 'published' ? 'selected' : '' }}>Published</option>
                                    <option value="archived" {{ old('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                                </select>
                            </div>

                            <div class="form-group mb-0">
                                <label class="form-label">Tanggal Publikasi</label>
                                <input type="datetime-local" name="published_at" value="{{ old('published_at', now()->format('Y-m-d\TH:i')) }}" class="form-input">
                                <p class="text-xs mt-1" style="color: var(--text-muted);">Bisa dikosongkan agar waktu publish diambil otomatis saat status dipilih published.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col gap-3">
                    <button type="submit" class="btn btn-primary w-full justify-center">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M5 13l4 4L19 7"/></svg>
                        Simpan Publikasi
                    </button>
                    <a href="{{ route('admin.publikasi.index') }}" class="btn btn-secondary w-full justify-center">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        Batal
                    </a>
                </div>
            </div>
        </div>
    </form>
</x-admin.layouts.app>
