<x-admin.layouts.app>
    <div class="page-header">
        <div class="page-header-content">
            <span class="page-label">// Laporan Madrasah</span>
            <h1 class="page-title">Laporan Madrasah</h1>
            <p class="page-subtitle">Verifikasi dan kelola laporan bulanan serta semester madrasah</p>
        </div>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="alert alert-success mb-6">
            <svg class="alert-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="alert-message">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger mb-6">
            <svg class="alert-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="alert-message">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Stats -->
    <div class="grid-4 mb-6">
        <div class="stat-card">
            <div class="stat-icon amber">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <div class="stat-content">
                <span class="stat-label">Total Madrasah</span>
                <span class="stat-value">{{ $stats['total'] }}</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon rose">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="stat-content">
                <span class="stat-label">Menunggu Verifikasi</span>
                <span class="stat-value">{{ $stats['pending'] }}</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon emerald">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="stat-content">
                <span class="stat-label">Disetujui</span>
                <span class="stat-value">{{ $stats['approved'] }}</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon violet">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
            </div>
            <div class="stat-content">
                <span class="stat-label">Perlu Revisi</span>
                <span class="stat-value">{{ $stats['revisi'] }}</span>
            </div>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="card mb-6">
        <div class="card-header">
            <div class="flex items-center gap-3">
                <div class="stat-icon emerald" style="width: 36px; height: 36px;">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V16l-4-4z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="card-title">Filter Data</h3>
                    <p class="text-sm text-muted">Cari laporan berdasarkan kriteria</p>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.madrasah.laporan.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="form-group">
                    <label class="form-label">Pencarian</label>
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text" name="search" value="{{ $currentSearch }}" placeholder="Nama madrasah, NSM..." class="form-input pl-10">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Jenis Laporan</label>
                    <select name="type" class="form-select">
                        <option value="">Semua Jenis</option>
                        <option value="bulanan" {{ $currentType === 'bulanan' ? 'selected' : '' }}>Bulanan</option>
                        <option value="semester" {{ $currentType === 'semester' ? 'selected' : '' }}>Semester</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="submitted" {{ $currentStatus === 'submitted' ? 'selected' : '' }}>Submitted (Menunggu Verifikasi)</option>
                        <option value="approved" {{ $currentStatus === 'approved' ? 'selected' : '' }}>Approved (Disetujui)</option>
                        <option value="revisi" {{ $currentStatus === 'revisi' ? 'selected' : '' }}>Revisi (Perlu Perbaikan)</option>
                    </select>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Filter
                    </button>
                    <a href="{{ route('admin.madrasah.laporan.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Table Card -->
    <div class="card">
        <div class="card-header">
            <div class="flex items-center gap-3">
                <div class="stat-icon amber" style="width: 36px; height: 36px;">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="card-title">Daftar Laporan</h3>
                    <p class="text-sm text-muted">Menampilkan {{ $laporan->total() }} laporan</p>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if($laporan->count() > 0)
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="w-12">No</th>
                                <th>Jenis</th>
                                <th>Madrasah</th>
                                <th>Periode</th>
                                <th>Status</th>
                                <th>Tanggal Submit</th>
                                <th class="w-32">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($laporan as $item)
                                <tr>
                                    <td>{{ ($laporan->currentPage() - 1) * $laporan->perPage() + $loop->iteration }}</td>
                                    <td>
                                        <span class="badge {{ $item->jenis === 'bulanan' ? 'badge-primary' : 'badge-info' }}">
                                            {{ ucfirst($item->jenis) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="font-medium">{{ $item->nama_madrasah }}</div>
                                        <div class="text-sm text-muted">
                                            {{ strtoupper($item->kategori) }}
                                            @if($item->status_lembaga)
                                                - {{ $item->status_lembaga }}
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        {{ $item->periode_info }} {{ $item->tahun ?? '' }}
                                        <div class="text-sm text-muted">{{ $item->tahun_ajaran }} - {{ ucfirst($item->semester) }}</div>
                                    </td>
                                    <td>
                                        @php
                                            $statusClass = match($item->status) {
                                                'submitted' => 'badge-warning',
                                                'approved' => 'badge-success',
                                                'revisi' => 'badge-danger',
                                                default => 'badge-gray',
                                            };
                                        @endphp
                                        <span class="badge {{ $statusClass }}">{{ ucfirst($item->status) }}</span>
                                    </td>
                                    <td>
                                        @if($item->submitted_at)
                                            <div>{{ \Carbon\Carbon::parse($item->submitted_at)->format('d M Y') }}</div>
                                            <div class="text-sm text-muted">{{ \Carbon\Carbon::parse($item->submitted_at)->format('H:i') }}</div>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.madrasah.laporan.show', [$item->jenis, $item->id]) }}" class="btn btn-sm btn-primary">
                                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            Lihat
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $laporan->withQueryString()->links('pagination::tailwind') }}
                </div>
            @else
                <div class="empty-state">
                    <svg class="empty-state-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <h3 class="empty-state-title">Belum ada laporan</h3>
                    <p class="empty-state-text">Tidak ada laporan yang sesuai dengan filter yang dipilih</p>
                </div>
            @endif
        </div>
    </div>
</x-admin.layouts.app>
