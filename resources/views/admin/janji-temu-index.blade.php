<x-admin.layouts.app title="Manajemen Janji Temu - Admin SILATAR">

    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <span class="page-label">// Janji Temu</span>
            <h1 class="page-title">Manajemen Janji Temu</h1>
            <p class="page-subtitle">Kelola semua pengajuan janji temu dari pengguna</p>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid-4 mb-6">
        @php
            $totalAll = DB::table('ktd_bukutamu')->count();
            $totalPending = DB::table('ktd_bukutamu')->where('status', 'APPOINTMENT')->count();
            $totalApproved = DB::table('ktd_bukutamu')->where('status', 'DITERIMA')->count();
            $totalRejected = DB::table('ktd_bukutamu')->where('status', 'DITOLAK')->count();
        @endphp

        <div class="stat-card">
            <div class="stat-icon cyan">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <div class="stat-content">
                <span class="stat-label">Total Janji Temu</span>
                <span class="stat-value">{{ $totalAll }}</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon amber">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="stat-content">
                <span class="stat-label">Menunggu</span>
                <span class="stat-value">{{ $totalPending }}</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon emerald">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="stat-content">
                <span class="stat-label">Disetujui</span>
                <span class="stat-value">{{ $totalApproved }}</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon rose">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="stat-content">
                <span class="stat-label">Ditolak</span>
                <span class="stat-value">{{ $totalRejected }}</span>
            </div>
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

    <!-- Filters Card -->
    <div class="card mb-6">
        <div class="card-header">
            <div class="flex items-center gap-3">
                <div class="stat-icon emerald" style="width: 36px; height: 36px;">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 0111v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V16l-4-4z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="card-title">Filter Data</h3>
                    <p class="text-sm text-muted">Cari dan filter janji temu berdasarkan kriteria</p>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.janji-temu') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="form-group">
                    <label class="form-label">Pencarian</label>
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Nama, NIP, atau keterangan..." class="form-input pl-10">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="APPOINTMENT" {{ ($status ?? '') == 'APPOINTMENT' ? 'selected' : '' }}>Menunggu</option>
                        <option value="DITERIMA" {{ ($status ?? '') == 'DITERIMA' ? 'selected' : '' }}>Disetujui</option>
                        <option value="DITOLAK" {{ ($status ?? '') == 'DITOLAK' ? 'selected' : '' }}>Ditolak</option>
                        <option value="BATAL" {{ ($status ?? '') == 'BATAL' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                </div>

                <div class="flex items-end gap-3">
                    <button type="submit" class="btn btn-primary">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Filter
                    </button>
                    @if(($search ?? '') || ($status ?? ''))
                        <a href="{{ route('admin.janji-temu') }}" class="btn btn-secondary">Reset</a>
                    @endif
                </div>
            </form>

            @if(($search ?? '') || ($status ?? ''))
                <div class="active-filters">
                    <span class="text-sm text-muted">Filter aktif:</span>
                    @if($status ?? '')
                        <span class="filter-badge">Status: {{ $status }}</span>
                    @endif
                    @if($search ?? '')
                        <span class="filter-badge">Pencarian: "{{ $search }}"</span>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <!-- Table -->
    <div class="card">
        <div class="card-header">
            <div class="flex items-center gap-3">
                <div class="stat-icon violet" style="width: 36px; height: 36px;">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <div>
                    <h3 class="card-title">Daftar Janji Temu</h3>
                    <p class="text-sm text-muted">Menampilkan {{ $janjiTemuList->total() }} data janji temu</p>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="w-16">ID</th>
                            <th>Pengaju</th>
                            <th>Waktu</th>
                            <th>Tujuan</th>
                            <th>Status</th>
                            <th class="text-right w-24">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($janjiTemuList as $item)
                            @php
                                $statusBadge = match($item->status) {
                                    'APPOINTMENT' => 'badge-warning',
                                    'PENDING' => 'badge-info',
                                    'DITERIMA' => 'badge-success',
                                    'DITOLAK' => 'badge-danger',
                                    'BATAL' => 'badge-secondary',
                                    default => 'badge-secondary',
                                };

                                $statusLabel = match($item->status) {
                                    'APPOINTMENT' => 'Menunggu',
                                    'PENDING' => 'Menunggu',
                                    'DITERIMA' => 'Disetujui',
                                    'DITOLAK' => 'Ditolak',
                                    'BATAL' => 'Dibatalkan',
                                    default => $item->status,
                                };
                            @endphp

                            <tr>
                                <td>
                                    <span class="font-mono text-sm text-muted">#{{ $item->id }}</span>
                                </td>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="avatar avatar-sm" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                            {{ strtoupper(substr($item->nama, 0, 2)) }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-900">{{ $item->nama }}</p>
                                            <p class="text-xs text-muted">{{ $item->asal ?: '-' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <p class="text-sm">{{ \Carbon\Carbon::parse($item->waktu)->format('d M Y') }}</p>
                                    <p class="text-xs text-muted">{{ \Carbon\Carbon::parse($item->waktu)->format('H:i') }} WIB</p>
                                </td>
                                <td>
                                    <p class="text-sm max-w-xs truncate" title="{{ $item->tujuan }}">
                                        {{ Str::limit($item->tujuan, 35) }}
                                    </p>
                                    <p class="text-xs text-muted flex items-center gap-1">
                                        @if($item->tipe === 'asn')
                                            <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                            Pegawai
                                        @else
                                            <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                            </svg>
                                            Seksi
                                        @endif
                                    </p>
                                </td>
                                <td>
                                    <span class="badge {{ $statusBadge }}">{{ $statusLabel }}</span>
                                </td>
                                <td class="text-right">
                                    <a href="{{ route('admin.janji-temu.show', $item->id) }}" class="btn btn-sm btn-outline">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-12">
                                    <div class="flex flex-col items-center">
                                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                            <svg class="w-8 h-8 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                            </svg>
                                        </div>
                                        <p class="text-gray-500 font-medium">Tidak ada data janji temu</p>
                                        <p class="text-sm text-muted mt-1">Belum ada pengajuan janji temu yang masuk</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($janjiTemuList->hasPages())
                <div class="pagination">
                    @if($janjiTemuList->onFirstPage())
                        <span class="disabled">Sebelumnya</span>
                    @else
                        <a href="{{ $janjiTemuList->previousPageUrl() }}">Sebelumnya</a>
                    @endif

                    @foreach($janjiTemuList->getUrlRange(max(1, $janjiTemuList->currentPage() - 2), min($janjiTemuList->lastPage(), $janjiTemuList->currentPage() + 2)) as $page => $url)
                        @if($page == $janjiTemuList->currentPage())
                            <span class="active">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}">{{ $page }}</a>
                        @endif
                    @endforeach

                    @if($janjiTemuList->hasMorePages())
                        <a href="{{ $janjiTemuList->nextPageUrl() }}">Selanjutnya</a>
                    @else
                        <span class="disabled">Selanjutnya</span>
                    @endif
                </div>

                <div class="text-center text-sm text-muted mt-3">
                    Menampilkan {{ $janjiTemuList->firstItem() }} - {{ $janjiTemuList->lastItem() }} dari {{ $janjiTemuList->total() }} data
                </div>
            @endif
        </div>
    </div>

</x-admin.layouts.app>
