<x-admin.layouts.app title="Manajemen Acara - Admin SILATAR">

    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <span class="page-label">// Acara</span>
            <h1 class="page-title">Manajemen Acara</h1>
            <p class="page-subtitle">Kelola kegiatan dan acara kantor</p>
        </div>
        <div class="page-actions">
            @if($isAdmin)
            <a href="{{ route('admin.acara.create') }}" class="btn btn-primary">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Acara
            </a>
            @endif
        </div>
    </div>

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
                    <p class="text-sm text-muted">Cari dan filter acara berdasarkan kriteria</p>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.acara') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="form-group">
                    <label class="form-label">Pencarian</label>
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Judul atau lokasi..." class="form-input pl-10">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="active" {{ ($status ?? '') == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="completed" {{ ($status ?? '') == 'completed' ? 'selected' : '' }}>Selesai</option>
                        <option value="cancelled" {{ ($status ?? '') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
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
                        <a href="{{ route('admin.acara') }}" class="btn btn-secondary">Reset</a>
                    @endif
                </div>
            </form>
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

    <!-- Table -->
    <div class="card">
        <div class="card-header">
            <div class="flex items-center gap-3">
                <div class="stat-icon violet" style="width: 36px; height: 36px;">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="card-title">Daftar Acara</h3>
                    <p class="text-sm text-muted">Menampilkan {{ $acaraList->total() }} data acara</p>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="w-16">ID</th>
                            <th>Judul</th>
                            <th>Tanggal</th>
                            <th>Waktu</th>
                            <th>Lokasi</th>
                            <th>Status</th>
                            <th class="text-right w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($acaraList as $item)
                            @php
                                $statusBadge = match($item->status) {
                                    'active' => 'badge-success',
                                    'completed' => 'badge-info',
                                    'cancelled' => 'badge-danger',
                                    default => 'badge-secondary',
                                };

                                $statusLabel = match($item->status) {
                                    'active' => 'Aktif',
                                    'completed' => 'Selesai',
                                    'cancelled' => 'Dibatalkan',
                                    default => $item->status,
                                };
                            @endphp

                            <tr>
                                <td>
                                    <span class="font-mono text-sm text-muted">#{{ $item->id }}</span>
                                </td>
                                <td>
                                    <div class="font-semibold text-gray-900">{{ $item->judul }}</div>
                                </td>
                                <td>
                                    <span class="text-sm">{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</span>
                                </td>
                                <td>
                                    <span class="text-sm">{{ $item->jam_mulai }} - {{ $item->jam_selesei }}</span>
                                </td>
                                <td>
                                    <span class="text-sm max-w-xs truncate block" title="{{ $item->lokasi }}">{{ Str::limit($item->lokasi, 25) }}</span>
                                </td>
                                <td>
                                    <span class="badge {{ $statusBadge }}">{{ $statusLabel }}</span>
                                </td>
                                <td class="text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.acara.show', $item->id) }}" class="btn btn-sm btn-outline" title="Lihat">
                                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>
                                        @if($isAdmin)
                                        <a href="{{ route('admin.acara.edit', $item->id) }}" class="btn btn-sm btn-outline" title="Edit">
                                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>
                                        <form action="{{ route('admin.acara.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus acara ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-12">
                                    <div class="flex flex-col items-center">
                                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                            <svg class="w-8 h-8 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                        <p class="text-gray-500 font-medium">Tidak ada data acara</p>
                                        <p class="text-sm text-muted mt-1">Buat acara baru untuk memulai</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($acaraList->hasPages())
                <div class="pagination">
                    @if($acaraList->onFirstPage())
                        <span class="disabled">Sebelumnya</span>
                    @else
                        <a href="{{ $acaraList->previousPageUrl() }}">Sebelumnya</a>
                    @endif

                    @foreach($acaraList->getUrlRange(max(1, $acaraList->currentPage() - 2), min($acaraList->lastPage(), $acaraList->currentPage() + 2)) as $page => $url)
                        @if($page == $acaraList->currentPage())
                            <span class="active">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}">{{ $page }}</a>
                        @endif
                    @endforeach

                    @if($acaraList->hasMorePages())
                        <a href="{{ $acaraList->nextPageUrl() }}">Selanjutnya</a>
                    @else
                        <span class="disabled">Selanjutnya</span>
                    @endif
                </div>
            @endif
        </div>
    </div>

</x-admin.layouts.app>
