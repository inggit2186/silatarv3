<x-admin.layouts.app>
    <div class="page-header">
        <div class="page-header-content">
            <span class="page-label">// Layanan</span>
            <h1 class="page-title">Manajemen Layanan</h1>
            <p class="page-subtitle">Kelola layanan dan persyaratan pengajuan</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.services.create') }}" class="btn btn-primary">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Layanan
            </a>
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
            <div class="stat-icon emerald">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                </svg>
            </div>
            <div class="stat-content">
                <span class="stat-label">Total Layanan</span>
                <span class="stat-value">{{ $services->total() }}</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon cyan">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="stat-content">
                <span class="stat-label">Aktif</span>
                <span class="stat-value">{{ $services->where('status', 1)->count() }}</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon violet">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                </svg>
            </div>
            <div class="stat-content">
                <span class="stat-label">Layanan Khusus</span>
                <span class="stat-value">{{ $services->where('spesial', 1)->count() }}</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon amber">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div class="stat-content">
                <span class="stat-label">Total Pengajuan</span>
                <span class="stat-value">{{ $services->sum('request_count') }}</span>
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
                    <p class="text-sm text-muted">Cari layanan berdasarkan kriteria</p>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.services.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="form-group">
                    <label class="form-label">Pencarian</label>
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Nama layanan..." class="form-input pl-10">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Unit Kerja</label>
                    <select name="dept_id" class="form-select">
                        <option value="">Semua Unit</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ ($filters['dept_id'] ?? '') == $dept->id ? 'selected' : '' }}>{{ $dept->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        @foreach($statusOptions as $key => $label)
                            <option value="{{ $key }}" {{ ($filters['status'] ?? '') == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Layanan Khusus</label>
                    <select name="spesial" class="form-select">
                        <option value="">Semua</option>
                        @foreach($spesialOptions as $key => $label)
                            <option value="{{ $key }}" {{ ($filters['spesial'] ?? '') == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-span-1 md:col-span-4 flex items-center gap-3">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    @if(($filters['search'] ?? '') || ($filters['dept_id'] ?? '') || ($filters['status'] ?? '') !== '' || ($filters['spesial'] ?? '') !== '')
                        <a href="{{ route('admin.services.index') }}" class="btn btn-secondary">Reset</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Services Table -->
    <div class="card">
        <div class="table-header">
            <div class="table-title-icon">
                <div class="icon" style="background: rgba(5,150,105,0.1);">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="table-title">Daftar Layanan</h3>
                    <p class="table-subtitle">Total {{ $services->total() }} layanan</p>
                </div>
            </div>
            <span class="data-count">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                {{ $services->count() }} items
            </span>
        </div>
        <div class="table-wrapper">
            <table class="table">
	                <thead>
                    <tr>
                        <th>Layanan</th>
                        <th>Unit Kerja</th>
                        <th>Syarat</th>
                        <th>Pengajuan</th>
                        <th>Spesial</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($services as $svc)
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="table-user-avatar" style="background: linear-gradient(135deg, #059669 0%, #047857 100%);">
                                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <span class="table-user-name">{{ $svc->nama }}</span>
                                        <span class="table-user-email line-clamp-1">{{ Str::limit($svc->deskripsi, 50) }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-neutral">{{ $svc->dept_nama ?? 'Semua Unit' }}</span>
                            </td>
                            <td>
                                <span class="badge badge-info">{{ $svc->requirement_count ?? 0 }} syarat</span>
                            </td>
                            <td>
                                <span class="badge badge-warning">{{ $svc->request_count ?? 0 }} pengajuan</span>
                            </td>
                            <td>
                                @if($svc->spesial == 1)
                                    <span class="badge badge-warning">Ya</span>
                                @else
                                    <span class="badge badge-neutral">Tidak</span>
                                @endif
                            </td>
                            <td>
                                @if($svc->status == 1)
                                    <span class="badge badge-success">Aktif</span>
                                @else
                                    <span class="badge badge-danger">Tidak Aktif</span>
                                @endif
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('admin.services.edit', $svc->id) }}" class="action-btn" title="Edit">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h5.586a1 1 0 00.707-.293l5.414-5.414a1 1 0 000-1.414l-5.414-5.414A1 1 0 0011.828 6H16"/>
                                        </svg>
                                    </a>
                                    <button type="button" class="action-btn delete" data-id="{{ $svc->id }}" data-name="{{ $svc->nama }}" data-request-count="{{ $svc->request_count ?? 0 }}" title="Hapus" onclick="openDeleteModal(this)">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-12">
                                <div class="empty-state">
                                    <svg class="w-16 h-16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                                    </svg>
                                    <p class="empty-state-title">Belum ada layanan</p>
                                    <p class="empty-state-text">Tambahkan layanan baru untuk memulai</p>
                                    <a href="{{ route('admin.services.create') }}" class="btn btn-primary mt-4">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                                        Tambah Layanan
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($services->hasPages())
            <div class="px-6 py-4 border-t flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-sm text-muted">Menampilkan {{ $services->firstItem() ?? 0 }} - {{ $services->lastItem() ?? 0 }} dari {{ $services->total() }} data</p>
                <div class="pagination">
                    @if($services->onFirstPage())
                        <span class="disabled">Sebelumnya</span>
                    @else
                        <a href="{{ $services->previousPageUrl() }}">Sebelumnya</a>
                    @endif
                    @foreach($services->getUrlRange(1, $services->lastPage()) as $page => $url)
                        @if($page <= 3 || $page > $services->lastPage() - 2 || abs($page - $services->currentPage()) < 2)
                            @if($page == $services->currentPage())
                                <span class="active">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}">{{ $page }}</a>
                            @endif
                        @elseif($loop->index == 2 || $loop->index == $services->lastPage() - 3)
                            <span class="disabled">...</span>
                        @endif
                    @endforeach
                    @if($services->hasMorePages())
                        <a href="{{ $services->nextPageUrl() }}">Selanjutnya</a>
                    @else
                        <span class="disabled">Selanjutnya</span>
                    @endif
                </div>
            </div>
        @endif
    </div>

    <!-- Delete Modal -->
    <div id="deleteModal" class="modal-backdrop">
        <div class="modal">
            <div class="modal-header">
                <div>
                    <h2 class="modal-title">Konfirmasi Hapus</h2>
                    <p class="text-sm text-muted">Tindakan ini tidak dapat dibatalkan</p>
                </div>
                <button onclick="closeDeleteModal()" class="modal-close">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus layanan <strong id="deleteServiceName" class="text-danger"></strong>?</p>
                <div class="alert alert-danger mt-4">
                    <svg class="alert-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <span class="alert-message" id="deleteWarning"></span>
                </div>
            </div>
            <div class="modal-footer">
                <button onclick="closeDeleteModal()" class="btn btn-secondary">Batal</button>
                <form id="deleteForm" method="POST" class="inline">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</x-admin.layouts.app>

@push('scripts')
<script>
const deleteModal = document.getElementById('deleteModal');
const deleteServiceName = document.getElementById('deleteServiceName');
const deleteWarning = document.getElementById('deleteWarning');
const deleteForm = document.getElementById('deleteForm');

function openDeleteModal(button) {
    const id = button.dataset.id;
    const name = button.dataset.name;
    const requestCount = parseInt(button.dataset.requestCount) || 0;

    deleteServiceName.textContent = name;

    if (requestCount > 0) {
        deleteWarning.textContent = `Layanan ini digunakan oleh ${requestCount} pengajuan dan tidak dapat dihapus.`;
        deleteWarning.parentElement.classList.remove('hidden');
        deleteForm.querySelector('button[type="submit"]').disabled = true;
    } else {
        deleteWarning.textContent = 'Semua persyaratan layanan juga akan dihapus. Data yang dihapus tidak dapat dikembalikan.';
        deleteWarning.parentElement.classList.add('hidden');
        deleteForm.querySelector('button[type="submit"]').disabled = false;
    }

    deleteForm.action = `/admin/services/${id}`;
    deleteModal.classList.add('active');
}

function closeDeleteModal() {
    deleteModal.classList.remove('active');
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeDeleteModal();
});

deleteModal.addEventListener('click', function(e) {
    if (e.target === this) closeDeleteModal();
});

deleteForm.addEventListener('submit', async function(e) {
    e.preventDefault();
    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.innerHTML = 'Menghapus...';

    try {
        const response = await fetch(this.action, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
        });
        const data = await response.json();
        if (data.success) {
            showToast('success', data.message);
            closeDeleteModal();
            setTimeout(() => location.reload(), 500);
        } else {
            showToast('error', data.message);
            submitBtn.disabled = false;
            submitBtn.innerHTML = 'Hapus';
        }
    } catch (error) {
        showToast('error', 'Terjadi kesalahan. Silakan coba lagi.');
        submitBtn.disabled = false;
        submitBtn.innerHTML = 'Hapus';
    }
});

function showToast(type, message) {
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.innerHTML = `
        <svg class="toast-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            ${type === 'success' ? '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>' : '<path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l2-2m-2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>'}
        </svg>
        <span class="toast-message">${message}</span>
        <button onclick="this.parentElement.remove()" class="toast-close">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    `;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 4000);
}
</script>
@endpush
