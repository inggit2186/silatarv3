<x-admin.layouts.app>
    <?php $title = 'Verifikasi TPG'; ?>

    <div class="page-header">
        <div class="page-header-content">
            <span class="page-label">// Verifikasi</span>
            <h1 class="page-title">Verifikasi TPG</h1>
            <p class="page-subtitle">Daftar pengajuan TPG yang perlu diverifikasi</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success mb-6">
            <svg class="alert-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="alert-message">{{ session('success') }}</span>
        </div>
    @endif

    <div class="card mb-6">
        <div class="card-body" style="padding-bottom: 0;">
            <div class="flex gap-4">
                <a href="{{ route('admin.tpg.index') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-lg font-semibold transition-all duration-200 {{ $activeTab === 'bulanan' ? 'bg-cyan-500 text-white shadow-lg shadow-cyan-500/30' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Bulanan
                </a>
                <a href="{{ route('admin.tpg.semester.index') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-lg font-semibold transition-all duration-200 {{ $activeTab === 'semester' ? 'bg-violet-500 text-white shadow-lg shadow-violet-500/30' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/><path stroke-linecap="round" stroke-linejoin="round" d="M17 21v-8H7v8M7 3v5h8"/></svg>
                    Semester
                </a>
            </div>
        </div>
    </div>

    @php
        $activeFilters = collect([
            $currentLayananId ? ($layananOptions[$currentLayananId] ?? null) : null,
            $currentBulan ?: null,
            $currentTahun ? (string) $currentTahun : null,
            $currentStatus ?: null,
            $currentSearch ?: null,
        ])->filter()->values();
    @endphp

    <div class="grid-4 mb-6">
        <div class="stat-card">
            <div class="stat-icon cyan">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
            <div class="stat-content">
                <span class="stat-label">Total Pengajuan</span>
                <span class="stat-value">{{ $stats->total ?? 0 }}</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon amber">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div class="stat-content">
                <span class="stat-label">Draft</span>
                <span class="stat-value">{{ $stats->draft ?? 0 }}</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon violet">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div class="stat-content">
                <span class="stat-label">Pending</span>
                <span class="stat-value">{{ $stats->pending ?? 0 }}</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon emerald">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div class="stat-content">
                <span class="stat-label">Diterima</span>
                <span class="stat-value">{{ $stats->diterima ?? 0 }}</span>
            </div>
        </div>
    </div>

    <div class="grid-4 mb-6">
        <div class="stat-card">
            <div class="stat-icon cyan">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div class="stat-content">
                <span class="stat-label">Diproses</span>
                <span class="stat-value">{{ $stats->diproses ?? 0 }}</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon emerald">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div class="stat-content">
                <span class="stat-label">Sukses</span>
                <span class="stat-value">{{ $stats->sukses ?? 0 }}</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon rose">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </div>
            <div class="stat-content">
                <span class="stat-label">Ditolak</span>
                <span class="stat-value">{{ $pemberkasan->where('status', 'DITOLAK')->count() }}</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon slate">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <div class="stat-content">
                <span class="stat-label">Periode Aktif</span>
                <span class="stat-value">{{ $currentBulan ?? '-' }} {{ $currentTahun ?? '' }}</span>
            </div>
        </div>
    </div>

    <div class="card mb-6">
        <div class="card-header">
            <div class="flex items-center gap-3">
                <div class="stat-icon emerald" style="width: 36px; height: 36px;">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V16l-4-4z"/></svg>
                </div>
                <div>
                    <h3 class="card-title">Filter Data</h3>
                    <p class="text-sm text-muted">Cari pengajuan berdasarkan periode dan status</p>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.tpg.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                <div class="form-group">
                    <label class="form-label">Nama Layanan</label>
                    <select name="layanan_id" class="form-select" onchange="this.form.submit()">
                        <option value="">Semua Layanan</option>
                        @foreach($layananOptions as $id => $nama)
                            <option value="{{ $id }}" {{ $currentLayananId == $id ? 'selected' : '' }}>{{ $nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Bulan</label>
                    <select name="bulan" class="form-select" onchange="this.form.submit()">
                        <option value="">Semua Bulan</option>
                        @foreach($bulanOptions ?? [] as $b)
                            <option value="{{ $b }}" {{ $currentBulan === $b ? 'selected' : '' }}>{{ $b }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Tahun</label>
                    <input type="number" name="tahun" value="{{ $currentTahun ?? date('Y') }}" class="form-input" min="2020" max="2099" onchange="this.form.submit()">
                </div>

                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        @foreach($statusOptions ?? [] as $s)
                            <option value="{{ $s }}" {{ $currentStatus === $s ? 'selected' : '' }}>{{ $s }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Pencarian</label>
                    <input type="text" name="search" value="{{ $currentSearch ?? '' }}" placeholder="Nama, NIP, no req..." class="form-input" onchange="this.form.submit()">
                </div>

                <div class="col-span-1 md:col-span-2 lg:col-span-5 flex items-center gap-3">
                    @if($activeFilters->isNotEmpty())
                        <a href="{{ route('admin.tpg.index') }}" class="btn btn-secondary">Reset</a>
                    @endif
                </div>
            </form>

            @if($activeFilters->isNotEmpty())
                <div class="active-filters mt-4">
                    <span class="text-sm text-muted">Filter aktif:</span>
                    @if($currentLayananId)
                        <span class="badge badge-info">{{ $layananOptions[$currentLayananId] ?? null }}</span>
                    @endif
                    @if($currentBulan)
                        <span class="badge badge-neutral">{{ $currentBulan }}</span>
                    @endif
                    @if($currentTahun)
                        <span class="badge badge-neutral">{{ $currentTahun }}</span>
                    @endif
                    @if($currentStatus)
                        <span class="badge badge-warning">{{ $currentStatus }}</span>
                    @endif
                    @if($currentSearch)
                        <span class="badge badge-primary">{{ $currentSearch }}</span>
                    @endif
                </div>
            @endif
        </div>
    </div>

    @if($pemberkasan->count() > 0)
        <div class="card">
            <div class="table-header">
                <div class="table-title-icon">
                    <div class="icon" style="background: rgba(8,145,178,0.1);">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                    <div>
                        <h3 class="table-title">Daftar Pengajuan</h3>
                        <p class="table-subtitle">Total {{ $pemberkasan->total() }} pengajuan</p>
                    </div>
                </div>
                <span class="data-count">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
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
                            <th>Layanan</th>
                            <th>Periode</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pemberkasan as $item)
                            @php
                                $statusBadgeClass = match($item->status) {
                                    'DRAFT' => 'badge-gray',
                                    'SUBMITTED', 'PENDING' => 'badge-warning',
                                    'DITERIMA', 'DIPROSES' => 'badge-info',
                                    'SUKSES' => 'badge-success',
                                    'DITOLAK' => 'badge-danger',
                                    default => 'badge-neutral'
                                };
                            @endphp
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
                                        <div class="table-user-avatar">{{ substr($item->user_name, 0, 2) }}</div>
                                        <span class="table-user-name">{{ $item->user_name }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="table-unit">{{ $item->dept_name }}</span>
                                </td>
                                <td>
                                    <div class="font-medium">{{ $item->layanan_name ?? '-' }}</div>
                                </td>
                                <td>
                                    <div class="font-medium">{{ $item->periode_label }}</div>
                                </td>
                                <td>
                                    <span class="badge {{ $statusBadgeClass }}">{{ $item->status_label }}</span>
                                </td>
                                <td>
                                    <span class="table-time">{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}</span>
                                    <br>
                                    <span class="text-muted" style="font-size: 0.7rem;">{{ \Carbon\Carbon::parse($item->created_at)->format('H:i') }}</span>
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
                        @foreach($pemberkasan->appends(request()->query())->getUrlRange(1, $pemberkasan->lastPage()) as $page => $url)
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
        <div class="card">
            <div class="text-center py-12">
                <div class="empty-state">
                    <svg class="w-16 h-16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                    <p class="empty-state-title">Belum Ada Pengajuan</p>
                    <p class="empty-state-text">Tidak ada pengajuan TPG yang sesuai dengan filter saat ini.</p>
                </div>
            </div>
        </div>
    @endif
</x-admin.layouts.app>
