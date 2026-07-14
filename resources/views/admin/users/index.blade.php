<x-admin.layouts.app>
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <span class="page-label">// Users</span>
            <h1 class="page-title">Manajemen Pengguna</h1>
            <p class="page-subtitle">Kelola data pengguna dan hak akses sistem</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah User
            </a>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid-4 mb-6">
        <div class="stat-card">
            <div class="stat-icon cyan">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <div class="stat-content">
                <span class="stat-label">Total Pengguna</span>
                <span class="stat-value">{{ $users->total() }}</span>
            </div>
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
                    <p class="text-sm text-muted">Cari dan filter pengguna berdasarkan kriteria</p>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.users.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="form-group">
                    <label class="form-label">Pencarian</label>
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Nama, email, atau NIP..." class="form-input pl-10">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Role</label>
                    <select name="role" class="form-select">
                        <option value="">Semua Role</option>
                        @foreach($roles as $role)
                            <option value="{{ $role }}" {{ ($filters['role'] ?? '') == $role ? 'selected' : '' }}>{{ ucfirst($role) }}</option>
                        @endforeach
                    </select>
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
                        <option value="">Semua</option>
                        <option value="1" {{ ($filters['status'] ?? '') == '1' ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ ($filters['status'] ?? '') == '0' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>

                <div class="col-span-1 md:col-span-2 lg:col-span-4 flex items-center gap-3">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    @if(($filters['search'] ?? '') || ($filters['role'] ?? '') || ($filters['dept_id'] ?? '') || ($filters['status'] ?? ''))
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Reset</a>
                    @endif
                </div>
            </form>

            @if(($filters['search'] ?? '') || ($filters['role'] ?? '') || ($filters['dept_id'] ?? '') || ($filters['status'] ?? ''))
                <div class="active-filters">
                    <span class="text-sm text-muted">Filter aktif:</span>
                    @if($filters['search'] ?? '')
                        <span class="badge badge-info">"{{ $filters['search'] }}"</span>
                    @endif
                    @if($filters['role'] ?? '')
                        <span class="badge badge-info">Role: {{ ucfirst($filters['role']) }}</span>
                    @endif
                    @if($filters['dept_id'] ?? '')
                        <span class="badge badge-info">Unit: {{ collect($departments)->firstWhere('id', $filters['dept_id'])?->nama ?? $filters['dept_id'] }}</span>
                    @endif
                    @if(($filters['status'] ?? '') !== '')
                        <span class="badge {{ $filters['status'] == '1' ? 'badge-success' : 'badge-danger' }}">Status: {{ $filters['status'] == '1' ? 'Aktif' : 'Nonaktif' }}</span>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <!-- Users Table -->
    <div class="card">
        <div class="table-header">
            <div class="table-title-icon">
                <div class="icon" style="background: rgba(200,154,43,0.1);">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                    </svg>
                </div>
                <div>
                    <h3 class="table-title">Daftar Pengguna</h3>
                    <p class="table-subtitle">Total {{ $users->total() }} data</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <span class="data-count">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 17V7m0 10a2 2 0 01-2 2H7a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10V7a2 2 0 01-2 2H7a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2z"/>
                    </svg>
                    {{ $users->total() }} Records
                </span>
            </div>
        </div>
        <div class="table-wrapper">
            <table class="table">
                <thead>
                    <tr>
                        <th class="w-10"><input type="checkbox"></th>
                        <th>Pengguna</th>
                        <th>NIP</th>
                        <th>Unit Kerja</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td><input type="checkbox"></td>
                            <td>
                                <div class="table-user">
                                    <div class="table-user-avatar">
                                        @if(!empty($user->pp) && !empty($user->nomor_induk))
                                            <img src="{{ asset('assets/img/users/' . $user->nomor_induk . '/' . $user->pp) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                                        @else
                                            {{ substr($user->name, 0, 2) }}
                                        @endif
                                    </div>
                                    <div>
                                        <span class="table-user-name">{{ $user->name }}</span>
                                        <span class="table-user-email">{{ $user->email ?? '-' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td><span class="nip-code">{{ $user->nomor_induk ?? '-' }}</span></td>
                            <td>{{ $user->dept_name ?? '-' }}</td>
                            <td><span class="role-badge {{ $user->role }}">{{ ucfirst($user->role) }}</span></td>
                            <td>
                                <button type="button" data-user-id="{{ $user->id }}" data-current-status="{{ $user->status }}" class="status-toggle {{ $user->status == 1 ? 'active' : 'inactive' }}" onclick="toggleUserStatus(this)">
                                    <span class="dot"></span>
                                    {{ $user->status == 1 ? 'Aktif' : 'Nonaktif' }}
                                </button>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="action-btn" title="Edit">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h5.586a1 1 0 00.707-.293l5.414-5.414a1 1 0 000-1.414l-5.414-5.414A1 1 0 0011.828 6H16"/>
                                        </svg>
                                    </a>
                                    <button type="button" class="action-btn delete" data-user-id="{{ $user->id }}" data-user-name="{{ $user->name }}" title="Hapus" @if($user->role === 'superadmin') disabled @endif onclick="openDeleteModal(this)">
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
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <p class="empty-state-title">Belum ada data pengguna</p>
                                    <p class="empty-state-text">Tambahkan pengguna baru untuk memulai</p>
                                    <a href="{{ route('admin.users.create') }}" class="btn btn-primary mt-4">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                                        Tambah User
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="px-6 py-4 border-t flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-sm text-muted">Menampilkan {{ $users->firstItem() ?? 0 }} - {{ $users->lastItem() ?? 0 }} dari {{ $users->total() }} data</p>
                <div class="pagination">
                    @if($users->onFirstPage())
                        <span class="disabled">Sebelumnya</span>
                    @else
                        <a href="{{ $users->previousPageUrl() }}">Sebelumnya</a>
                    @endif
                    @foreach($users->getUrlRange(1, $users->lastPage()) as $page => $url)
                        @if($page <= 3 || $page > $users->lastPage() - 2 || abs($page - $users->currentPage()) < 2)
                            @if($page == $users->currentPage())
                                <span class="active">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}">{{ $page }}</a>
                            @endif
                        @elseif($loop->index == 2 || $loop->index == $users->lastPage() - 3)
                            <span class="disabled">...</span>
                        @endif
                    @endforeach
                    @if($users->hasMorePages())
                        <a href="{{ $users->nextPageUrl() }}">Selanjutnya</a>
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
                <p>Apakah Anda yakin ingin menghapus pengguna <strong id="deleteUserName" class="text-danger"></strong>?</p>
                <div class="alert alert-danger mt-4">
                    <svg class="alert-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <span class="alert-message">Data yang dihapus tidak dapat dikembalikan</span>
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
async function toggleUserStatus(button) {
    const userId = button.dataset.userId;
    const currentStatus = button.dataset.currentStatus;
    try {
        const response = await fetch(`/admin/users/${userId}/toggle-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]?.content || '',
                'Accept': 'application/json',
            },
        });
        const data = await response.json();
        if (data.success) {
            const newStatus = data.new_status;
            button.dataset.currentStatus = newStatus;
            button.className = `status-toggle ${newStatus == 1 ? 'active' : 'inactive'}`;
            button.innerHTML = `<span class="dot"></span> ${newStatus == 1 ? 'Aktif' : 'Nonaktif'}`;
            showToast('success', data.message);
        } else {
            showToast('error', data.message);
        }
    } catch (error) {
        showToast('error', 'Terjadi kesalahan. Silakan coba lagi.');
    }
}

let deleteUserId = null;
const deleteModal = document.getElementById('deleteModal');
const deleteUserName = document.getElementById('deleteUserName');
const deleteForm = document.getElementById('deleteForm');

function openDeleteModal(button) {
    deleteUserId = button.dataset.userId;
    deleteUserName.textContent = button.dataset.userName;
    deleteForm.action = `/admin/users/${deleteUserId}`;
    deleteModal.classList.add('active');
}

function closeDeleteModal() {
    deleteModal.classList.remove('active');
    deleteUserId = null;
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeDeleteModal();
});

deleteModal.addEventListener('click', function(e) {
    if (e.target === this) closeDeleteModal();
});

function showToast(type, message) {
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.innerHTML = `
        <svg class="toast-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            ${type === 'success' ? '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>' : '<path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>'}
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
