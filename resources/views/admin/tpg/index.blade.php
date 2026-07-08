<x-admin.layouts.app>
    <?php $title = 'Verifikasi TPG'; ?>

    <!-- Page Header -->
    <div class="admin-page-header">
        <div class="flex items-center gap-4">
            <div class="cyber-header-icon">
                <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </div>
            <div>
                <h1 class="admin-page-title">
                    <span class="cyber-title-text">Verifikasi TPG</span>
                </h1>
                <p class="admin-page-subtitle">
                    <svg class="inline h-4 w-4 mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Daftar pengajuan TPG yang perlu diverifikasi
                </p>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="cyber-card mb-6" style="border-color: rgba(16, 185, 129, 0.3);">
            <div class="cyber-card-body" style="display: flex; align-items: center; gap: 1rem; padding: 1rem 1.5rem; background: rgba(16, 185, 129, 0.05);">
                <svg class="h-6 w-6" style="color: #16a34a; flex-shrink: 0;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span style="color: var(--ink);">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <!-- Stats Grid -->
    <div class="cyber-stats-grid mb-6">
        <div class="cyber-stat-card">
            <div class="cyber-stat-icon-wrapper">
                <div class="cyber-stat-icon cyan">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
            </div>
            <div class="cyber-stat-content">
                <span class="cyber-stat-value">{{ $stats->total ?? 0 }}</span>
                <span class="cyber-stat-label">Total Pengajuan</span>
            </div>
        </div>

        <div class="cyber-stat-card">
            <div class="cyber-stat-icon-wrapper">
                <div class="cyber-stat-icon amber">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="cyber-stat-content">
                <span class="cyber-stat-value">{{ $stats->pending ?? 0 }}</span>
                <span class="cyber-stat-label">Menunggu</span>
            </div>
        </div>

        <div class="cyber-stat-card">
            <div class="cyber-stat-icon-wrapper">
                <div class="cyber-stat-icon emerald">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="cyber-stat-content">
                <span class="cyber-stat-value">{{ $stats->diterima ?? 0 }}</span>
                <span class="cyber-stat-label">Diterima</span>
            </div>
        </div>

        <div class="cyber-stat-card">
            <div class="cyber-stat-icon-wrapper">
                <div class="cyber-stat-icon" style="background: linear-gradient(135deg, #16a34a 0%, #15803d 100%); color: white;">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                    </svg>
                </div>
            </div>
            <div class="cyber-stat-content">
                <span class="cyber-stat-value" style="color: #16a34a;">{{ $stats->sukses ?? 0 }}</span>
                <span class="cyber-stat-label">Sukses</span>
            </div>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="cyber-card mb-6">
        <div class="cyber-card-header">
            <div class="flex items-center gap-3">
                <div class="cyber-section-icon">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V16l-4-4z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="cyber-section-title">Filter Data</h3>
                    <p class="cyber-section-subtitle">Cari pengajuan berdasarkan kriteria</p>
                </div>
            </div>
        </div>
        <div class="cyber-card-body">
            <form method="GET" action="{{ route('admin.tpg.index') }}" class="cyber-form-grid">
                <div class="cyber-form-group">
                    <label class="cyber-form-label">
                        <svg class="inline h-4 w-4 mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        Tipe Layanan
                    </label>
                    <select name="tipe" class="cyber-select" onchange="this.form.submit()">
                        <option value="">Semua Tipe</option>
                        @foreach($tipeOptions ?? [] as $t)
                            <option value="{{ $t }}" {{ $currentTipe === $t ? 'selected' : '' }}>
                                {{ $tipeLabels[$t] ?? $t }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="cyber-form-group">
                    <label class="cyber-form-label">
                        <svg class="inline h-4 w-4 mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Status
                    </label>
                    <select name="status" class="cyber-select" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        @foreach($statusOptions ?? [] as $s)
                            <option value="{{ $s }}" {{ $currentStatus === $s ? 'selected' : '' }}>
                                {{ $s }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="cyber-form-actions">
                    @if($currentTipe || $currentStatus)
                        <a href="{{ route('admin.tpg.index') }}" class="cyber-btn-secondary">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Reset
                        </a>
                    @endif
                </div>
            </form>

            <!-- Active Filters -->
            @if($currentTipe || $currentStatus)
                <div class="cyber-active-filters">
                    <span class="cyber-filter-label">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V16l-4-4z"/>
                        </svg>
                        Filter aktif:
                    </span>
                    @if($currentTipe)
                        <span class="cyber-filter-tag cyan">
                            {{ $tipeLabels[$currentTipe] ?? $currentTipe }}
                        </span>
                    @endif
                    @if($currentStatus)
                        <span class="cyber-filter-tag amber">{{ $currentStatus }}</span>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <!-- Data Table -->
    @if($pemberkasan->count() > 0)
        <div class="cyber-table-container">
            <div class="cyber-table-header">
                <div class="flex items-center gap-3">
                    <div class="cyber-table-icon">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="cyber-table-title">Daftar Pengajuan</h3>
                        <p class="cyber-table-subtitle">Total {{ $pemberkasan->total() }} pengajuan</p>
                    </div>
                </div>
                <span class="cyber-data-badge">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    {{ $pemberkasan->count() }} items
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="cyber-table">
                    <thead>
                        <tr>
                            <th class="cyber-th">
                                <svg class="inline h-4 w-4 mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                                No. Request
                            </th>
                            <th class="cyber-th">
                                <svg class="inline h-4 w-4 mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                Pemohon
                            </th>
                            <th class="cyber-th">
                                <svg class="inline h-4 w-4 mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                Unit Kerja
                            </th>
                            <th class="cyber-th">
                                <svg class="inline h-4 w-4 mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Periode
                            </th>
                            <th class="cyber-th">
                                <svg class="inline h-4 w-4 mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Status
                            </th>
                            <th class="cyber-th">
                                <svg class="inline h-4 w-4 mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Tanggal
                            </th>
                            <th class="cyber-th">
                                <svg class="inline h-4 w-4 mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                                </svg>
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pemberkasan as $item)
                        <?php
                            $statusColors = [
                                'SUBMITTED' => 'cyber-badge-amber',
                                'PENDING' => 'cyber-badge-amber',
                                'DITERIMA' => 'cyber-badge-cyan',
                                'DIPROSES' => 'cyber-badge-cyan',
                                'SUKSES' => 'cyber-badge-emerald',
                                'DITOLAK' => 'cyber-badge-rose',
                            ];
                            $statusColor = $statusColors[$item->status] ?? 'cyber-badge-slate';
                        ?>
                        <tr class="cyber-tr">
                            <td class="cyber-td">
                                <span class="cyber-ref-code">{{ $item->noreq }}</span>
                                <br>
                                <span class="cyber-filter-tag" style="font-size: 0.65rem; margin-top: 0.25rem;">
                                    {{ $item->tipe_label }}
                                </span>
                            </td>
                            <td class="cyber-td">
                                <div class="flex items-center gap-3">
                                    <div class="cyber-avatar" style="width: 2.25rem; height: 2.25rem;">
                                        {{ substr($item->user_name, 0, 2) }}
                                    </div>
                                    <div>
                                        <p class="cyber-user-name">{{ $item->user_name }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="cyber-td">
                                <span class="cyber-unit">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/>
                                    </svg>
                                    {{ $item->dept_name }}
                                </span>
                            </td>
                            <td class="cyber-td">
                                @if(!empty($item->metadata_parsed['bulan']))
                                    <span style="font-weight: 500;">
                                        {{ $item->metadata_parsed['bulan'] }}
                                        @if(!empty($item->metadata_parsed['tahun']))
                                            {{ $item->metadata_parsed['tahun'] }}
                                        @endif
                                    </span>
                                @elseif(!empty($item->metadata_parsed['tahun_pelajaran']))
                                    <span style="font-weight: 500;">
                                        {{ $item->metadata_parsed['tahun_pelajaran'] }}
                                        @if(!empty($item->metadata_parsed['semester']))
                                            <span class="cyber-filter-tag" style="font-size: 0.6rem;">
                                                {{ $item->metadata_parsed['semester'] }}
                                            </span>
                                        @endif
                                    </span>
                                @else
                                    <span style="color: var(--ash);">-</span>
                                @endif
                            </td>
                            <td class="cyber-td">
                                <span class="cyber-role-badge {{ $statusColor }}">
                                    {{ $item->status }}
                                </span>
                            </td>
                            <td class="cyber-td">
                                <span class="cyber-table-time">
                                    {{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}
                                </span>
                                <br>
                                <span style="font-size: 0.7rem; color: var(--ash);">
                                    {{ \Carbon\Carbon::parse($item->created_at)->format('H:i') }}
                                </span>
                            </td>
                            <td class="cyber-td">
                                <div class="cyber-actions">
                                    <a href="{{ route('admin.tpg.show', $item->id) }}" class="cyber-btn-table" style="display: inline-flex; align-items: center; gap: 0.375rem;">
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        Detail
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($pemberkasan->hasPages())
                <div class="cyber-pagination-wrapper">
                    <div class="cyber-pagination-info">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span>Menampilkan {{ $pemberkasan->firstItem() ?? 0 }} - {{ $pemberkasan->lastItem() ?? 0 }} dari {{ $pemberkasan->total() }} data</span>
                    </div>
                    <div class="cyber-pagination">
                        @if($pemberkasan->onFirstPage())
                            <span class="cyber-pagination-btn disabled">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                                </svg>
                                Prev
                            </span>
                        @else
                            <a href="{{ $pemberkasan->previousPageUrl() }}" class="cyber-pagination-btn">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                                </svg>
                                Prev
                            </a>
                        @endif

                        @foreach($pemberkasan->getUrlRange(1, $pemberkasan->lastPage()) as $page => $url)
                            @if($page <= 3 || $page > $pemberkasan->lastPage() - 2 || abs($page - $pemberkasan->currentPage()) < 2)
                                @if($page == $pemberkasan->currentPage())
                                    <span class="cyber-pagination-btn active">{{ $page }}</span>
                                @else
                                    <a href="{{ $url }}" class="cyber-pagination-btn">{{ $page }}</a>
                                @endif
                            @elseif($loop->index == 2 || $loop->index == $pemberkasan->lastPage() - 3)
                                <span class="cyber-pagination-ellipsis">...</span>
                            @endif
                        @endforeach

                        @if($pemberkasan->hasMorePages())
                            <a href="{{ $pemberkasan->nextPageUrl() }}" class="cyber-pagination-btn">
                                Next
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        @else
                            <span class="cyber-pagination-btn disabled">
                                Next
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                </svg>
                            </span>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    @else
        <!-- Empty State -->
        <div class="cyber-table-container">
            <div class="cyber-empty-state">
                <div class="cyber-empty-icon" style="color: var(--ash);">
                    <svg class="h-16 w-16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                </div>
                <h3 class="cyber-empty-title">Belum Ada Pengajuan</h3>
                <p class="cyber-empty-text">Tidak ada pengajuan TPG yang perlu diverifikasi saat ini.</p>
            </div>
        </div>
    @endif
</x-admin.layouts.app>
