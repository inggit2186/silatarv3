<x-admin.layouts.app>
    <?php $title = 'Verifikasi TPG'; ?>

    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <span class="page-label">// Verifikasi</span>
            <h1 class="page-title">Verifikasi TPG</h1>
            <p class="page-subtitle">Daftar pengajuan TPG yang perlu diverifikasi</p>
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

    <!-- Stats -->
    <div class="grid-4 mb-6">
        <div class="stat-card">
            <div class="stat-icon cyan">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <div class="stat-content">
                <span class="stat-label">Total Pengajuan</span>
                <span class="stat-value">{{ $stats->total ?? 0 }}</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon amber">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="stat-content">
                <span class="stat-label">Menunggu</span>
                <span class="stat-value">{{ $stats->pending ?? 0 }}</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon emerald">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="stat-content">
                <span class="stat-label">Diterima</span>
                <span class="stat-value">{{ $stats->diterima ?? 0 }}</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon primary">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                </svg>
            </div>
            <div class="stat-content">
                <span class="stat-label">Sukses</span>
                <span class="stat-value" style="color: var(--success);">{{ $stats->sukses ?? 0 }}</span>
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
                    <p class="text-sm text-muted">Cari pengajuan berdasarkan kriteria</p>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.tpg.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="form-group">
                    <label class="form-label">Tipe Layanan</label>
                    <select name="tipe" class="form-select" onchange="this.form.submit()">
                        <option value="">Semua Tipe</option>
                        @foreach($tipeOptions ?? [] as $t)
                            <option value="{{ $t }}" {{ $currentTipe === $t ? 'selected' : '' }}>
                                {{ $tipeLabels[$t] ?? $t }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        @foreach($statusOptions ?? [] as $s)
                            <option value="{{ $s }}" {{ $currentStatus === $s ? 'selected' : '' }}>
                                {{ $s }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-span-1 md:col-span-2 lg:col-span-4 flex items-center gap-3">
                    @if($currentTipe || $currentStatus)
                        <a href="{{ route('admin.tpg.index') }}" class="btn btn-secondary">Reset</a>
                    @endif
                </div>
            </form>

            @if($currentTipe || $currentStatus)
                <div class="active-filters mt-4">
                    <span class="text-sm text-muted">Filter aktif:</span>
                    @if($currentTipe)
                        <span class="badge badge-info">{{ $tipeLabels[$currentTipe] ?? $currentTipe }}</span>
                    @endif
                    @if($currentStatus)
                        <span class="badge badge-warning">{{ $currentStatus }}</span>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <!-- Data Table -->
    @if($pemberkasan->count() > 0)
    <div class="card">
        <div class="table-header">
            <div class="table-title-icon">
                <div class="icon" style="background: rgba(8,145,178,0.1);">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <div>
                    <h3 class="table-title">Daftar Pengajuan</h3>
                    <p class="table-subtitle">Total {{ $pemberkasan->total() }} pengajuan</p>
                </div>
            </div>
            <span class="data-count">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                {{ $pemberkasan->count() }} items
            </span>
        </div>
        <div class="table-wrapper">
            <table class="table">
                <thead>
                    <tr>
                        <th>No. Request</th>
                        <th>Pemohon</th>
                        <th>Unit Kerja</th>
                        <th>Periode</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pemberkasan as $item)
                    <?php
                        $statusBadgeClass = match($item->status) {
                            'SUBMITTED', 'PENDING' => 'badge-warning',
                            'DITERIMA', 'DIPROSES' => 'badge-info',
                            'SUKSES' => 'badge-success',
                            'DITOLAK' => 'badge-danger',
                            default => 'badge-neutral'
                        };
                    ?>
                    <tr>
                        <td>
                            <span class="nip-code">{{ $item->noreq }}</span>
                            <br>
                            <span class="badge badge-neutral" style="font-size: 0.65rem; margin-top: 0.25rem;">
                                {{ $item->tipe_label }}
                            </span>
                        </td>
                        <td>
                            <div class="table-user">
                                <div class="table-user-avatar">
                                    {{ substr($item->user_name, 0, 2) }}
                                </div>
                                <span class="table-user-name">{{ $item->user_name }}</span>
                            </div>
                        </td>
                        <td>
                            <span class="table-unit">{{ $item->dept_name }}</span>
                        </td>
                        <td>
                            @if(!empty($item->metadata_parsed['bulan']))
                                <span class="font-medium">
                                    {{ $item->metadata_parsed['bulan'] }}
                                    @if(!empty($item->metadata_parsed['tahun']))
                                        {{ $item->metadata_parsed['tahun'] }}
                                    @endif
                                </span>
                            @elseif(!empty($item->metadata_parsed['tahun_pelajaran']))
                                <span class="font-medium">
                                    {{ $item->metadata_parsed['tahun_pelajaran'] }}
                                    @if(!empty($item->metadata_parsed['semester']))
                                        <span class="badge badge-neutral" style="font-size: 0.6rem;">
                                            {{ $item->metadata_parsed['semester'] }}
                                        </span>
                                    @endif
                                </span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ $statusBadgeClass }}">{{ $item->status }}</span>
                        </td>
                        <td>
                            <span class="table-time">{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}</span>
                            <br>
                            <span class="text-muted" style="font-size: 0.7rem;">
                                {{ \Carbon\Carbon::parse($item->created_at)->format('H:i') }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.tpg.show', $item->id) }}" class="action-btn" title="Detail">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($pemberkasan->hasPages())
            <div class="px-6 py-4 border-t flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-sm text-muted">Menampilkan {{ $pemberkasan->firstItem() ?? 0 }} - {{ $pemberkasan->lastItem() ?? 0 }} dari {{ $pemberkasan->total() }} data</p>
                <div class="pagination">
                    @if($pemberkasan->onFirstPage())
                        <span class="disabled">Sebelumnya</span>
                    @else
                        <a href="{{ $pemberkasan->previousPageUrl() }}">Sebelumnya</a>
                    @endif
                    @foreach($pemberkasan->getUrlRange(1, $pemberkasan->lastPage()) as $page => $url)
                        @if($page <= 3 || $page > $pemberkasan->lastPage() - 2 || abs($page - $pemberkasan->currentPage()) < 2)
                            @if($page == $pemberkasan->currentPage())
                                <span class="active">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}">{{ $page }}</a>
                            @endif
                        @elseif($loop->index == 2 || $loop->index == $pemberkasan->lastPage() - 3)
                            <span class="disabled">...</span>
                        @endif
                    @endforeach
                    @if($pemberkasan->hasMorePages())
                        <a href="{{ $pemberkasan->nextPageUrl() }}">Selanjutnya</a>
                    @else
                        <span class="disabled">Selanjutnya</span>
                    @endif
                </div>
            </div>
        @endif
    </div>
    @else
    <!-- Empty State -->
    <div class="card">
        <div class="text-center py-12">
            <div class="empty-state">
                <svg class="w-16 h-16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
                <p class="empty-state-title">Belum Ada Pengajuan</p>
                <p class="empty-state-text">Tidak ada pengajuan TPG yang perlu diverifikasi saat ini.</p>
            </div>
        </div>
    </div>
    @endif
</x-admin.layouts.app>
