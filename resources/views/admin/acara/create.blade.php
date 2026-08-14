<x-admin.layouts.app title="Tambah Acara - Admin SILATAR">

    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <span class="page-label">// Acara</span>
            <h1 class="page-title">Tambah Acara Baru</h1>
            <p class="page-subtitle">Buat acara atau kegiatan baru</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.acara') }}" class="btn btn-secondary">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali
            </a>
        </div>
    </div>

    <!-- Success/Error Message -->
    @if(session('error'))
        <div class="alert alert-error">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Form -->
        <div class="lg:col-span-2">
            <form action="{{ route('admin.acara.store') }}" method="POST">
                @csrf
                <div class="card">
                    <div class="card-header">
                        <div class="flex items-center gap-3">
                            <div class="stat-icon cyan" style="width: 36px; height: 36px;">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <h3 class="card-title">Form Acara</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Judul -->
                            <div class="form-group md:col-span-2">
                                <label class="form-label">Judul Acara <span class="text-red-500">*</span></label>
                                <input type="text" name="judul" class="form-input" value="{{ old('judul') }}" required placeholder="Masukkan judul acara">
                                @error('judul')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Deskripsi -->
                            <div class="form-group md:col-span-2">
                                <label class="form-label">Deskripsi</label>
                                <textarea name="deskripsi" class="form-textarea" rows="3" placeholder="Deskripsi acara (opsional)">{{ old('deskripsi') }}</textarea>
                            </div>

                            <!-- Tanggal -->
                            <div class="form-group">
                                <label class="form-label">Tanggal <span class="text-red-500">*</span></label>
                                <input type="date" name="tanggal" class="form-input" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                                @error('tanggal')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div class="form-group">
                                <label class="form-label">Status <span class="text-red-500">*</span></label>
                                <select name="status" class="form-select" required>
                                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                                    <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                                    <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                                </select>
                            </div>

                            <!-- Jam Mulai -->
                            <div class="form-group">
                                <label class="form-label">Jam Mulai <span class="text-red-500">*</span></label>
                                <input type="time" name="jam_mulai" class="form-input" value="{{ old('jam_mulai', '08:00') }}" required>
                            </div>

                            <!-- Jam Selesai -->
                            <div class="form-group">
                                <label class="form-label">Jam Selesai <span class="text-red-500">*</span></label>
                                <input type="time" name="jam_selesei" class="form-input" value="{{ old('jam_selesei', '17:00') }}" required>
                            </div>

                            <!-- Lokasi -->
                            <div class="form-group md:col-span-2">
                                <label class="form-label">Lokasi <span class="text-red-500">*</span></label>
                                <input type="text" name="lokasi" class="form-input" value="{{ old('lokasi') }}" required placeholder="Masukkan lokasi acara">
                            </div>

                            <!-- Latitude -->
                            <div class="form-group">
                                <label class="form-label">Latitude</label>
                                <input type="number" step="any" name="latitude" class="form-input" value="{{ old('latitude') }}" placeholder="Contoh: -0.947083">
                                <p class="text-xs text-muted mt-1">Koordinat GPS (opsional)</p>
                            </div>

                            <!-- Longitude -->
                            <div class="form-group">
                                <label class="form-label">Longitude</label>
                                <input type="number" step="any" name="longitude" class="form-input" value="{{ old('longitude') }}" placeholder="Contoh: 100.417283">
                                <p class="text-xs text-muted mt-1">Koordinat GPS (opsional)</p>
                            </div>

                            <!-- Radius -->
                            <div class="form-group">
                                <label class="form-label">Radius (meter)</label>
                                <input type="number" name="radius" class="form-input" value="{{ old('radius', 0) }}" min="0" placeholder="0 = tanpa batasan">
                                <p class="text-xs text-muted mt-1">0 atau kosong = presensi dari mana saja</p>
                            </div>
                        </div>

                        <div class="mt-6 flex items-center gap-3">
                            <button type="submit" class="btn btn-primary">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                                Simpan Acara
                            </button>
                            <a href="{{ route('admin.acara') }}" class="btn btn-secondary">Batal</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Info Sidebar -->
        <div class="space-y-6">
            <div class="card">
                <div class="card-header">
                    <div class="flex items-center gap-3">
                        <div class="stat-icon amber" style="width: 36px; height: 36px;">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="card-title">Informasi GPS</h3>
                    </div>
                </div>
                <div class="card-body">
                    <p class="text-sm text-muted mb-4">Koordinat GPS digunakan untuk validasi lokasi presensi karyawan.</p>
                    <ul class="text-sm text-muted space-y-2">
                        <li class="flex items-start gap-2">
                            <span class="text-emerald-500 mt-1">•</span>
                            <span><strong>Latitude:</strong> Garis lintang lokasi</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-emerald-500 mt-1">•</span>
                            <span><strong>Longitude:</strong> Garis bujur lokasi</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-emerald-500 mt-1">•</span>
                            <span><strong>Radius:</strong> Jarak maksimal presensi (meter)</span>
                        </li>
                    </ul>
                    <div class="mt-4 p-3 bg-amber-50 border border-amber-200 rounded-lg">
                        <p class="text-xs text-amber-700"><strong>Catatan:</strong> Jika radius = 0 atau kosong, karyawan bisa presensi dari lokasi mana saja.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-admin.layouts.app>
