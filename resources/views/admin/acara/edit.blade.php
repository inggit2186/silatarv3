<x-admin.layouts.app title="Edit Acara - Admin SILATAR">

    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <span class="page-label">// Acara</span>
            <h1 class="page-title">Edit Acara</h1>
            <p class="page-subtitle">{{ $acara->judul }}</p>
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Form -->
        <div class="lg:col-span-2">
            <form action="{{ route('admin.acara.update', $acara->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card">
                    <div class="card-header">
                        <div class="flex items-center gap-3">
                            <div class="stat-icon cyan" style="width: 36px; height: 36px;">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </div>
                            <h3 class="card-title">Form Edit Acara</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Judul -->
                            <div class="form-group md:col-span-2">
                                <label class="form-label">Judul Acara <span class="text-red-500">*</span></label>
                                <input type="text" name="judul" class="form-input" value="{{ old('judul', $acara->judul) }}" required>
                            </div>

                            <!-- Deskripsi -->
                            <div class="form-group md:col-span-2">
                                <label class="form-label">Deskripsi</label>
                                <textarea name="deskripsi" class="form-textarea" rows="3">{{ old('deskripsi', $acara->deskripsi) }}</textarea>
                            </div>

                            <!-- Tanggal -->
                            <div class="form-group">
                                <label class="form-label">Tanggal <span class="text-red-500">*</span></label>
                                <input type="date" name="tanggal" class="form-input" value="{{ old('tanggal', $acara->tanggal) }}" required>
                            </div>

                            <!-- Status -->
                            <div class="form-group">
                                <label class="form-label">Status <span class="text-red-500">*</span></label>
                                <select name="status" class="form-select" required>
                                    <option value="active" {{ old('status', $acara->status) == 'active' ? 'selected' : '' }}>Aktif</option>
                                    <option value="completed" {{ old('status', $acara->status) == 'completed' ? 'selected' : '' }}>Selesai</option>
                                    <option value="cancelled" {{ old('status', $acara->status) == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                                </select>
                            </div>

                            <!-- Jam Mulai -->
                            <div class="form-group">
                                <label class="form-label">Jam Mulai <span class="text-red-500">*</span></label>
                                <input type="time" name="jam_mulai" class="form-input" value="{{ old('jam_mulai', $acara->jam_mulai) }}" required>
                            </div>

                            <!-- Jam Selesai -->
                            <div class="form-group">
                                <label class="form-label">Jam Selesai <span class="text-red-500">*</span></label>
                                <input type="time" name="jam_selesei" class="form-input" value="{{ old('jam_selesei', $acara->jam_selesei) }}" required>
                            </div>

                            <!-- Lokasi -->
                            <div class="form-group md:col-span-2">
                                <label class="form-label">Lokasi <span class="text-red-500">*</span></label>
                                <input type="text" name="lokasi" class="form-input" value="{{ old('lokasi', $acara->lokasi) }}" required>
                            </div>

                            <!-- Latitude -->
                            <div class="form-group">
                                <label class="form-label">Latitude</label>
                                <input type="number" step="any" name="latitude" class="form-input" value="{{ old('latitude', $acara->latitude) }}">
                            </div>

                            <!-- Longitude -->
                            <div class="form-group">
                                <label class="form-label">Longitude</label>
                                <input type="number" step="any" name="longitude" class="form-input" value="{{ old('longitude', $acara->longitude) }}">
                            </div>

                            <!-- Radius -->
                            <div class="form-group">
                                <label class="form-label">Radius (meter)</label>
                                <input type="number" name="radius" class="form-input" value="{{ old('radius', $acara->radius) }}" min="0">
                            </div>
                        </div>

                        <div class="mt-6 flex items-center gap-3">
                            <button type="submit" class="btn btn-primary">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                                Simpan Perubahan
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
                        <h3 class="card-title">Informasi Acara</h3>
                    </div>
                </div>
                <div class="card-body">
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-muted">ID:</span>
                            <span class="font-semibold text-gray-900">#{{ $acara->id }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-muted">Dibuat:</span>
                            <span class="font-semibold text-gray-900">{{ \Carbon\Carbon::parse($acara->created_at)->format('d M Y H:i') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-muted">Update:</span>
                            <span class="font-semibold text-gray-900">{{ \Carbon\Carbon::parse($acara->updated_at)->format('d M Y H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-admin.layouts.app>
