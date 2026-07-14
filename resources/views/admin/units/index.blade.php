<x-admin.layouts.app>
    <div class="page-header">
        <div class="page-header-content">
            <span class="page-label">// Unit Kerja</span>
            <h1 class="page-title">Manajemen Unit Kerja</h1>
            <p class="page-subtitle">Kelola data unit kerja dan departemen</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.units.create') }}" class="btn btn-primary">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Unit
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
            <div class="stat-icon amber">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <div class="stat-content">
                <span class="stat-label">Total Unit</span>
                <span class="stat-value">{{ $departments->total() }}</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon emerald">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="stat-content">
                <span class="stat-label">Aktif</span>
                <span class="stat-value">{{ $departments->where('status', 1)->count() }}</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon violet">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <div class="stat-content">
                <span class="stat-label">Total Pengguna</span>
                <span class="stat-value">{{ $departments->sum('user_count') }}</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon cyan">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <div class="stat-content">
                <span class="stat-label">Madrasah</span>
                <span class="stat-value">{{ $departments->whereIn('kategori', ['mi', 'mts', 'mtsn', 'ma', 'man', 'min'])->count() }}</span>
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
                    <p class="text-sm text-muted">Cari unit kerja berdasarkan kriteria</p>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.units.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="form-group">
                    <label class="form-label">Pencarian</label>
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Nama, alamat, NPSM..." class="form-input pl-10">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Kategori</label>
                    <select name="kategori" class="form-select">
                        <option value="">Semua Kategori</option>
                        @foreach($kategoriOptions as $key => $label)
                            <option value="{{ $key }}" {{ ($filters['kategori'] ?? '') == $key ? 'selected' : '' }}>{{ $label }}</option>
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

                <div class="col-span-1 md:col-span-3 flex items-center gap-3">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    @if(($filters['search'] ?? '') || ($filters['kategori'] ?? '') || ($filters['status'] ?? '') !== '')
                        <a href="{{ route('admin.units.index') }}" class="btn btn-secondary">Reset</a>
                    @endif
                </div>
            </form>

            @if(($filters['search'] ?? '') || ($filters['kategori'] ?? '') || ($filters['status'] ?? '') !== '')
                <div class="active-filters mt-4">
                    <span class="text-sm text-muted">Filter aktif:</span>
                    @if($filters['search'] ?? '')
                        <span class="badge badge-info">"{{ $filters['search'] }}"</span>
                    @endif
                    @if($filters['kategori'] ?? '')
                        <span class="badge badge-info">{{ $kategoriOptions[$filters['kategori']] ?? $filters['kategori'] }}</span>
                    @endif
                    @if(($filters['status'] ?? '') !== '')
                        <span class="badge {{ $filters['status'] == '0' ? 'badge-danger' : 'badge-success' }}">{{ $statusOptions[$filters['status']] ?? '' }}</span>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <!-- Units Table -->
    <div class="card">
        <div class="table-header">
            <div class="table-title-icon">
                <div class="icon" style="background: rgba(217,119,6,0.1);">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div>
                    <h3 class="table-title">Daftar Unit Kerja</h3>
                    <p class="table-subtitle">Total {{ $departments->total() }} unit</p>
                </div>
            </div>
            <span class="data-count">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                {{ $departments->count() }} items
            </span>
        </div>
        <div class="table-wrapper">
            <table class="table">
                <thead>
                    <tr>
                        <th>Unit Kerja</th>
                        <th>Kategori</th>
                        <th>NPSM</th>
                        <th>Alamat</th>
                        <th>Pengguna</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($departments as $dept)
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="table-user-avatar" style="background: linear-gradient(135deg, #D97706 0%, #B45309 100%);">
                                        {{ substr($dept->nama, 0, 2) }}
                                    </div>
                                    <div>
                                        <span class="table-user-name">{{ $dept->nama }}</span>
                                        <span class="table-user-email">{{ $dept->email ?? '-' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-info">{{ $kategoriOptions[$dept->kategori] ?? $dept->kategori }}</span>
                            </td>
                            <td>
                                <span class="nip-code">{{ $dept->npsm ?? '-' }}</span>
                            </td>
                            <td>
                                <span class="text-sm">{{ $dept->alamat ?? '-' }}</span>
                            </td>
                            <td>
                                <span class="badge badge-neutral">{{ $dept->user_count ?? 0 }} pengguna</span>
                            </td>
                            <td>
                                @if($dept->status == 1)
                                    <span class="badge badge-success">Aktif (Intern)</span>
                                @elseif($dept->status == 2)
                                    <span class="badge badge-info">Aktif (Satker)</span>
                                @else
                                    <span class="badge badge-danger">Tidak Aktif</span>
                                @endif
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('admin.units.edit', $dept->id) }}" class="action-btn" title="Edit">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h5.586a1 1 0 00.707-.293l5.414-5.414a1 1 0 000-1.414l-5.414-5.414A1 1 0 0011.828 6H16"/>
                                        </svg>
                                    </a>
                                    <button type="button" class="action-btn delete" data-id="{{ $dept->id }}" data-name="{{ $dept->nama }}" data-user-count="{{ $dept->user_count ?? 0 }}" title="Hapus" onclick="openDeleteModal(this)">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-12">
                                <div class="empty-state">
                                    <svg class="w-16 h-16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                    <p class="empty-state-title">Belum ada unit kerja</p>
                                    <p class="empty-state-text">Tambahkan unit kerja baru untuk memulai</p>
                                    <a href="{{ route('admin.units.create') }}" class="btn btn-primary mt-4">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                                        Tambah Unit
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($departments->hasPages())
            <div class="px-6 py-4 border-t flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-sm text-muted">Menampilkan {{ $departments->firstItem() ?? 0 }} - {{ $departments->lastItem() ?? 0 }} dari {{ $departments->total() }} data</p>
                <div class="pagination">
                    @if($departments->onFirstPage())
                        <span class="disabled">Sebelumnya</span>
                    @else
                        <a href="{{ $departments->previousPageUrl() }}">Sebelumnya</a>
                    @endif
                    @foreach($departments->getUrlRange(1, $departments->lastPage()) as $page => $url)
                        @if($page <= 3 || $page > $departments->lastPage() - 2 || abs($page - $departments->currentPage()) < 2)
                            @if($page == $departments->currentPage())
                                <span class="active">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}">{{ $page }}</a>
                            @endif
                        @elseif($loop->index == 2 || $loop->index == $departments->lastPage() - 3)
                            <span class="disabled">...</span>
                        @endif
                    @endforeach
                    @if($departments->hasMorePages())
                        <a href="{{ $departments->nextPageUrl() }}">Selanjutnya</a>
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
                <p>Apakah Anda yakin ingin menghapus unit kerja <strong id="deleteUnitName" class="text-danger"></strong>?</p>
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
let deleteUnitId = null;
const deleteModal = document.getElementById('deleteModal');
const deleteUnitName = document.getElementById('deleteUnitName');
const deleteWarning = document.getElementById('deleteWarning');
const deleteForm = document.getElementById('deleteForm');

function openDeleteModal(button) {
    const id = button.dataset.id;
    const name = button.dataset.name;
    const userCount = parseInt(button.dataset.userCount) || 0;

    deleteUnitId = id;
    deleteUnitName.textContent = name;

    if (userCount > 0) {
        deleteWarning.textContent = `Unit kerja ini memiliki ${userCount} pengguna. Silakan pindahkan atau hapus pengguna tersebut terlebih dahulu.`;
        deleteWarning.parentElement.classList.remove('hidden');
        document.querySelector('#deleteForm button[type="submit"]').disabled = true;
    } else {
        deleteWarning.textContent = 'Data yang dihapus tidak dapat dikembalikan';
        deleteWarning.parentElement.classList.add('hidden');
        document.querySelector('#deleteForm button[type="submit"]').disabled = false;
    }

    deleteForm.action = `/admin/units/${deleteUnitId}`;
    deleteModal.classList.add('active');
}

function closeDeleteModal() {
    deleteModal.classList.remove('active');
    deleteUnitId = null;
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeDeleteModal();
});

deleteModal.addEventListener('click', function(e) {
    if (e.target === this) closeDeleteModal();
});

// Handle delete form submission
deleteForm.addEventListener('submit', async function(e) {
    e.preventDefault();

    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<svg class="w-4 h-4 animate-spin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10" stroke-opacity="0.25"/><path d="M12 2a10 10 0 0 1 10 10" stroke-opacity="1"/></svg> Menghapus...';

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
            // Reload page or remove row
            setTimeout(() => location.reload(), 500);
        } else {
            showToast('error', data.message);
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    } catch (error) {
        showToast('error', 'Terjadi kesalahan. Silakan coba lagi.');
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
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
