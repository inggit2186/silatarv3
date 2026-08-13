<x-admin.layouts.app title="Detail Janji Temu - Admin SILATAR">

    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <span class="page-label">// Detail Janji Temu</span>
            <h1 class="page-title">Detail Janji Temu #{{ $janjiTemu->id }}</h1>
            <p class="page-subtitle">Informasi lengkap dan proses pengajuan janji temu</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.janji-temu') }}" class="btn btn-secondary">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali
            </a>
        </div>
    </div>

    <!-- Success/Error Message -->
    @if(session('success'))
        <div class="alert alert-success">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-6">
            @php
                $statusBadge = match($janjiTemu->status) {
                    'APPOINTMENT' => 'badge-warning',
                    'PENDING' => 'badge-info',
                    'DITERIMA' => 'badge-success',
                    'DITOLAK' => 'badge-danger',
                    'BATAL' => 'badge-secondary',
                    default => 'badge-secondary',
                };

                $statusLabel = match($janjiTemu->status) {
                    'APPOINTMENT' => 'Menunggu Konfirmasi',
                    'PENDING' => 'Menunggu',
                    'DITERIMA' => 'Disetujui',
                    'DITOLAK' => 'Ditolak',
                    'BATAL' => 'Dibatalkan',
                    default => $janjiTemu->status,
                };
            @endphp

            <!-- Status Card -->
            <div class="card">
                <div class="card-body">
                    <div class="flex items-center gap-3 mb-6">
                        <span class="badge {{ $statusBadge }} badge-lg">{{ $statusLabel }}</span>
                        @if($janjiTemu->onStaff && $janjiTemu->onStaff != 999)
                            <span class="text-sm text-muted">Ditangani oleh: <strong>{{ $staffNama }}</strong></span>
                        @endif
                    </div>

                    <div class="space-y-6">
                        <!-- Pengaju -->
                        <div class="flex items-start gap-4">
                            <div class="stat-icon cyan" style="width: 48px; height: 48px;">
                                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-muted mb-1">Pengaju</p>
                                <p class="text-lg font-bold text-gray-900">{{ $janjiTemu->nama }}</p>
                                <p class="text-sm text-muted">NIP: {{ $janjiTemu->nomor_induk }}</p>
                                <p class="text-sm text-muted">Asal: {{ $janjiTemu->asal ?: '-' }}</p>
                            </div>
                        </div>

                        <hr class="border-gray-200">

                        <!-- Waktu -->
                        <div class="flex items-start gap-4">
                            <div class="stat-icon violet" style="width: 48px; height: 48px;">
                                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-muted mb-1">Waktu Janji Temu</p>
                                <p class="text-xl font-bold text-gray-900">
                                    {{ \Carbon\Carbon::parse($janjiTemu->waktu)->format('d M Y, H:i') }} WIB
                                </p>
                            </div>
                        </div>

                        <hr class="border-gray-200">

                        <!-- Target -->
                        <div class="flex items-start gap-4">
                            <div class="stat-icon emerald" style="width: 48px; height: 48px;">
                                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-muted mb-1">Tujuan Pertemuan</p>
                                <p class="text-lg font-bold text-gray-900">{{ $targetNama }}</p>
                                <p class="text-sm text-muted">{{ $targetDetail }}</p>
                                @if($targetTelp)
                                    <p class="text-sm text-muted flex items-center gap-1 mt-1">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                        </svg>
                                        {{ $targetTelp }}
                                    </p>
                                @endif
                            </div>
                        </div>

                        <hr class="border-gray-200">

                        <!-- Keperluan -->
                        <div class="flex items-start gap-4">
                            <div class="stat-icon amber" style="width: 48px; height: 48px;">
                                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-muted mb-1">Keperluan / Alasan</p>
                                <p class="text-gray-900 leading-relaxed">{{ $janjiTemu->tujuan }}</p>
                            </div>
                        </div>

                        @if($janjiTemu->komen)
                            <hr class="border-gray-200">

                            <!-- Komentar -->
                            <div class="flex items-start gap-4">
                                <div class="stat-icon secondary" style="width: 48px; height: 48px;">
                                    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-muted mb-1">Komentar / Keterangan</p>
                                    <p class="text-gray-900 italic bg-gray-50 p-3 rounded-lg">"{{ $janjiTemu->komen }}"</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar - Actions -->
        <div class="space-y-6">
            <!-- Info Box -->
            <div class="card">
                <div class="card-header">
                    <div class="flex items-center gap-3">
                        <div class="stat-icon cyan" style="width: 36px; height: 36px;">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="card-title">Informasi</h3>
                    </div>
                </div>
                <div class="card-body">
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-muted">ID:</span>
                            <span class="font-semibold text-gray-900">#{{ $janjiTemu->id }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-muted">Dibuat:</span>
                            <span class="font-semibold text-gray-900">{{ \Carbon\Carbon::parse($janjiTemu->created_at)->format('d M Y H:i') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-muted">Update:</span>
                            <span class="font-semibold text-gray-900">{{ \Carbon\Carbon::parse($janjiTemu->updated_at)->format('d M Y H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            @if(in_array($janjiTemu->status, ['APPOINTMENT', 'PENDING']))
                <div class="card">
                    <div class="card-header">
                        <div class="flex items-center gap-3">
                            <div class="stat-icon emerald" style="width: 36px; height: 36px;">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                                </svg>
                            </div>
                            <h3 class="card-title">Aksi</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="space-y-4">
                            <!-- Approve -->
                            <form action="{{ route('admin.janji-temu.approve', $janjiTemu->id) }}" method="POST" onsubmit="return confirm('Setujui janji temu ini?')">
                                @csrf
                                <div class="space-y-3">
                                    <div class="form-group">
                                        <label class="form-label">Keterangan (Opsional)</label>
                                        <input
                                            type="text"
                                            name="komen"
                                            class="form-input"
                                            placeholder="Disetujui oleh petugas"
                                            value="Disetujui oleh petugas"
                                        >
                                    </div>
                                    <button type="submit" class="btn btn-success w-full">
                                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Setujui
                                    </button>
                                </div>
                            </form>

                            <hr class="border-gray-200">

                            <!-- Reject -->
                            <form action="{{ route('admin.janji-temu.reject', $janjiTemu->id) }}" method="POST" onsubmit="return confirm('Tolak janji temu ini?')">
                                @csrf
                                <div class="space-y-3">
                                    <div class="form-group">
                                        <label class="form-label">Alasan Penolakan <span class="text-red-500">*</span></label>
                                        <textarea
                                            name="komen"
                                            class="form-textarea"
                                            rows="3"
                                            placeholder="Masukkan alasan penolakan..."
                                            required
                                        ></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-danger w-full">
                                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                        Tolak
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @else
                <div class="card">
                    <div class="card-header">
                        <div class="flex items-center gap-3">
                            <div class="stat-icon secondary" style="width: 36px; height: 36px;">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <h3 class="card-title">Status</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="text-sm text-muted">
                            Janji temu ini sudah dalam status <strong>{{ $statusLabel }}</strong> dan tidak dapat diproses lagi.
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </div>

</x-admin.layouts.app>
