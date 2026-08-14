<x-admin.layouts.app title="Detail Acara - Admin SILATAR">

    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <span class="page-label">// Acara</span>
            <h1 class="page-title">{{ $acara->judul }}</h1>
            <p class="page-subtitle">Detail acara dan daftar kehadiran</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.acara') }}" class="btn btn-secondary">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali
            </a>
            <a href="{{ route('admin.acara.edit', $acara->id) }}" class="btn btn-primary">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Info Acara -->
            <div class="card">
                <div class="card-header">
                    <div class="flex items-center gap-3">
                        <div class="stat-icon cyan" style="width: 36px; height: 36px;">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <h3 class="card-title">Informasi Acara</h3>
                    </div>
                </div>
                <div class="card-body">
                    <div class="space-y-4">
                        <div class="flex items-start gap-4">
                            <div class="stat-icon violet" style="width: 48px; height: 48px;">
                                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-muted mb-1">Tanggal & Waktu</p>
                                <p class="text-lg font-bold text-gray-900">{{ \Carbon\Carbon::parse($acara->tanggal)->format('d M Y') }}</p>
                                <p class="text-sm text-muted">{{ $acara->jam_mulai }} - {{ $acara->jam_selesei }} WIB</p>
                            </div>
                        </div>

                        <hr class="border-gray-200">

                        <div class="flex items-start gap-4">
                            <div class="stat-icon emerald" style="width: 48px; height: 48px;">
                                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-muted mb-1">Lokasi</p>
                                <p class="text-lg font-bold text-gray-900">{{ $acara->lokasi }}</p>
                                @if($acara->latitude && $acara->longitude)
                                    <p class="text-sm text-muted">{{ $acara->latitude }}, {{ $acara->longitude }}</p>
                                @endif
                            </div>
                        </div>

                        @if($acara->deskripsi)
                            <hr class="border-gray-200">
                            <div class="flex items-start gap-4">
                                <div class="stat-icon amber" style="width: 48px; height: 48px;">
                                    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-muted mb-1">Deskripsi</p>
                                    <p class="text-gray-900">{{ $acara->deskripsi }}</p>
                                </div>
                            </div>
                        @endif

                        <hr class="border-gray-200">

                        {{-- Link Presensi --}}
                        <div class="flex items-start gap-4">
                            <div class="stat-icon cyan" style="width: 48px; height: 48px;">
                                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 00-5.656 0l-4 4a4 4 0 005.656 5.656l1.102-1.101"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm text-muted mb-1">Link Presensi</p>
                                <div class="flex items-center gap-2">
                                    <input type="text" id="presensiLink" value="{{ url('/presensi-acara/' . $acara->id) }}" readonly
                                        class="flex-1 px-3 py-2 bg-[var(--paper-soft)] border border-[var(--line)] rounded-lg text-sm text-[var(--ink)] font-mono">
                                    <button onclick="copyLink()" class="px-4 py-2 bg-[var(--gold)] hover:bg-[var(--gold-bright)] text-white text-sm font-semibold rounded-lg transition-colors">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                        </svg>
                                    </button>
                                </div>
                                <p class="text-xs text-muted mt-2">Klik tombol copy untuk menyebarkan link presensi kepada pegawai/user</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Daftar Kehadiran -->
            <div class="card">
                <div class="card-header">
                    <div class="flex items-center gap-3">
                        <div class="stat-icon emerald" style="width: 36px; height: 36px;">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                </svg>
                            </div>
                            <h3 class="card-title">Daftar Kehadiran</h3>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Status</th>
                                    <th>Waktu</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($attendance as $item)
                                    @php
                                        $statusBadge = match($item->status) {
                                            'hadir' => 'badge-success',
                                            'tidak_hadir' => 'badge-danger',
                                            default => 'badge-secondary',
                                        };
                                        $statusLabel = match($item->status) {
                                            'hadir' => 'Hadir',
                                            'tidak_hadir' => 'Tidak Hadir',
                                            default => $item->status,
                                        };
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="font-semibold text-gray-900">{{ $item->name }}</div>
                                        </td>
                                        <td>
                                            <span class="badge {{ $statusBadge }}">{{ $statusLabel }}</span>
                                        </td>
                                        <td>
                                            <span class="text-sm">{{ $item->waktu_absen ?? '-' }}</span>
                                        </td>
                                        <td>
                                            <span class="text-sm max-w-xs truncate block" title="{{ $item->keterangan }}">{{ $item->keterangan ?? '-' }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-8">
                                            <p class="text-gray-500">Belum ada data kehadiran</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Stats -->
        <div class="space-y-6">
            <!-- Status -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Status</h3>
                </div>
                <div class="card-body">
                    @php
                        $statusBadge = match($acara->status) {
                            'active' => 'badge-success',
                            'completed' => 'badge-info',
                            'cancelled' => 'badge-danger',
                            default => 'badge-secondary',
                        };
                        $statusLabel = match($acara->status) {
                            'active' => 'Aktif',
                            'completed' => 'Selesai',
                            'cancelled' => 'Dibatalkan',
                            default => $acara->status,
                        };
                    @endphp
                    <span class="badge {{ $statusBadge }} badge-lg">{{ $statusLabel }}</span>
                </div>
            </div>

            <!-- Stats -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Statistik Kehadiran</h3>
                </div>
                <div class="card-body">
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-muted">Hadir</span>
                            <span class="font-bold text-emerald-600">{{ $hadirCount }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-muted">Tidak Hadir</span>
                            <span class="font-bold text-red-600">{{ $tidakHadirCount }}</span>
                        </div>
                        <hr class="border-gray-200">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-semibold">Total</span>
                            <span class="font-bold text-gray-900">{{ $hadirCount + $tidakHadirCount }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Info GPS -->
            @if($acara->radius)
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Info GPS</h3>
                    </div>
                    <div class="card-body">
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-muted">Radius:</span>
                                <span class="font-semibold text-gray-900">{{ $acara->radius }} meter</span>
                            </div>
                            @if($acara->latitude)
                                <div class="flex justify-between">
                                    <span class="text-muted">Latitude:</span>
                                    <span class="font-semibold text-gray-900">{{ $acara->latitude }}</span>
                                </div>
                            @endif
                            @if($acara->longitude)
                                <div class="flex justify-between">
                                    <span class="text-muted">Longitude:</span>
                                    <span class="font-semibold text-gray-900">{{ $acara->longitude }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script>
        function copyLink() {
            var linkInput = document.getElementById('presensiLink');
            linkInput.select();
            linkInput.setSelectionRange(0, 99999);

            navigator.clipboard.writeText(linkInput.value).then(function() {
                // Show success message
                var btn = event.target.closest('button');
                var originalText = btn.innerHTML;
                btn.innerHTML = '<svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg> Tersalin!';
                btn.classList.add('bg-emerald-500');
                btn.classList.remove('bg-[var(--gold)]');

                setTimeout(function() {
                    btn.innerHTML = originalText;
                    btn.classList.remove('bg-emerald-500');
                    btn.classList.add('bg-[var(--gold)]');
                }, 2000);
            });
        }
    </script>
</x-admin.layouts.app>
