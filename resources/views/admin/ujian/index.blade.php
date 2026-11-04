<x-admin.layouts.app title="{{ $title ?? 'Manajemen Ujian' }}">
    <div class="page-header">
        <div class="page-header-content">
            <span class="page-label">// Ujian</span>
            <h1 class="page-title">Manajemen Ujian</h1>
            <p class="page-subtitle">Kelola paket ujian, soal, dan peserta ujian pegawai</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.ujian.create') }}" class="btn btn-primary">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 4v16m8-8H4"/></svg>
                Tambah Ujian
            </a>
        </div>
    </div>

    @include('admin.ujian._partials.flash')

    <div class="grid-4 mb-6">
        <div class="stat-card">
            <div class="stat-icon primary">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div class="stat-content">
                <span class="stat-label">Total Ujian</span>
                <span class="stat-value">{{ $stats['total'] }}</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon emerald">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 13l4 4L19 7"/></svg>
            </div>
            <div class="stat-content">
                <span class="stat-label">Terbit</span>
                <span class="stat-value">{{ $stats['terbit'] }}</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon amber">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            </div>
            <div class="stat-content">
                <span class="stat-label">Draft</span>
                <span class="stat-value">{{ $stats['draft'] }}</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon violet">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
            </div>
            <div class="stat-content">
                <span class="stat-label">Peserta</span>
                <span class="stat-value">{{ $stats['peserta'] }}</span>
            </div>
        </div>
    </div>

    <div class="card mb-6">
        <div class="card-header">
            <div class="flex items-center gap-3">
                <div class="stat-icon primary" style="width: 36px; height: 36px;">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <h3 class="card-title">Filter Data</h3>
                    <p class="text-sm text-muted">Cari ujian berdasarkan kode atau judul</p>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.ujian.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="form-group">
                    <label class="form-label">Pencarian</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari ujian..." class="form-input">
                </div>
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua</option>
                        @foreach($statuses as $key => $label)
                        <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-center gap-3 md:col-span-1" style="align-self: flex-end;">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('admin.ujian.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="table-header">
            <div class="table-title-group">
                <h3 class="table-title">Daftar Ujian</h3>
                <p class="table-subtitle">Total {{ $ujians->total() }} ujian</p>
            </div>
        </div>
        <div class="table-wrapper">
            <table class="table">
                <thead>
                    <tr>
                        <th>Ujian</th>
                        <th>Status</th>
                        <th>Soal</th>
                        <th>Peserta</th>
                        <th>Durasi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ujians as $item)
                    <tr>
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0" style="background: rgba(212,168,83,0.1); color: var(--gold);">
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14,2 14,8 20,8"/></svg>
                                </div>
                                <div>
                                    <span class="table-user-name">{{ $item->judul }}</span>
                                    <span class="table-user-email line-clamp-1">{{ $item->kode }}</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge @if($item->status === 'terbit') badge-success @elseif($item->status === 'draft') badge-warning @else badge-gray @endif">
                                {{ $statuses[$item->status] ?? ucfirst($item->status) }}
                            </span>
                        </td>
                        <td>
                            <span class="text-primary font-medium">{{ $item->soal_count }}</span>
                        </td>
                        <td>
                            <span class="text-primary font-medium">{{ $item->peserta_count }}</span>
                        </td>
                        <td>
                            <span class="table-time">{{ $item->durasi_menit }} menit</span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('admin.ujian.edit', $item->id) }}" class="action-btn" title="Kelola">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <a href="{{ route('admin.ujian.hasil', $item->id) }}" class="action-btn" title="Hasil Ujian">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 17v-2m4 2v-2m4 2v-2M3 5h18v14H3z"/></svg>
                                </a>
                                <form method="POST" action="{{ route('admin.ujian.destroy', $item->id) }}" onsubmit="return confirm('Yakin ingin menghapus ujian ini beserta seluruh soalnya?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-btn delete" title="Hapus">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 2 0 00-1-1h-4a1 2 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <svg class="w-12 h-12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <p class="empty-state-title">Belum ada ujian</p>
                                <p class="empty-state-text">Mulai dengan membuat paket ujian pertama.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($ujians->hasPages())
    <div class="pagination">
        {{ $ujians->links() }}
    </div>
    @endif
</x-admin.layouts.app>
