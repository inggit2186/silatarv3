<x-admin.layouts.app>
    {{-- Page Header --}}
    <div class="admin-page-header">
        <div>
            <h1 class="admin-page-title">Manajemen Pengguna</h1>
            <p class="admin-page-subtitle">Kelola data pengguna dan hak akses sistem</p>
        </div>
        <div class="admin-page-actions">
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah User
            </a>
        </div>
    </div>

    {{-- Filters Card --}}
    <div class="card mb-6">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-wrap items-end gap-3">
                {{-- Search --}}
                <div class="flex-1 min-w-[220px]">
                    <label class="form-label text-xs text-slate-500">Pencarian</label>
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input
                            type="text"
                            name="search"
                            value="{{ $filters['search'] ?? '' }}"
                            placeholder="Nama, email, atau NIP..."
                            class="form-input pl-10"
                        >
                    </div>
                </div>

                {{-- Role Filter --}}
                <div class="min-w-[140px]">
                    <label class="form-label text-xs text-slate-500">Role</label>
                    <select name="role" class="form-select">
                        <option value="">Semua Role</option>
                        @foreach($roles as $role)
                            <option value="{{ $role }}" {{ ($filters['role'] ?? '') == $role ? 'selected' : '' }}>
                                {{ ucfirst($role) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Department Filter --}}
                <div class="min-w-[180px]">
                    <label class="form-label text-xs text-slate-500">Unit Kerja</label>
                    <select name="dept_id" class="form-select">
                        <option value="">Semua Unit</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ ($filters['dept_id'] ?? '') == $dept->id ? 'selected' : '' }}>
                                {{ $dept->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Status Filter --}}
                <div class="min-w-[120px]">
                    <label class="form-label text-xs text-slate-500">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua</option>
                        <option value="1" {{ ($filters['status'] ?? '') == '1' ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ ($filters['status'] ?? '') == '0' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>

                {{-- Filter Buttons --}}
                <div class="flex items-end gap-2">
                    <button type="submit" class="btn btn-primary">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V16l-4-4z"/>
                        </svg>
                        Filter
                    </button>
                    @if(($filters['search'] ?? '') || ($filters['role'] ?? '') || ($filters['dept_id'] ?? '') || ($filters['status'] ?? '') !== '')
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Reset
                        </a>
                    @endif
                </div>
            </form>

            {{-- Active Filters Summary --}}
            @if(($filters['search'] ?? '') || ($filters['role'] ?? '') || ($filters['dept_id'] ?? '') || ($filters['status'] ?? '') !== '')
                <div class="mt-3 flex flex-wrap items-center gap-2 border-t border-slate-100 pt-3">
                    <span class="text-xs text-slate-400">Filter aktif:</span>
                    @if($filters['search'] ?? '')
                        <span class="badge badge-slate">
                            <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            "{{ $filters['search'] }}"
                        </span>
                    @endif
                    @if($filters['role'] ?? '')
                        <span class="badge badge-cyan">Role: {{ ucfirst($filters['role']) }}</span>
                    @endif
                    @if($filters['dept_id'] ?? '')
                        @php
                            $selectedDept = collect($departments)->firstWhere('id', $filters['dept_id']);
                        @endphp
                        <span class="badge badge-emerald">Unit: {{ $selectedDept ? $selectedDept->nama : $filters['dept_id'] }}</span>
                    @endif
                    @if(($filters['status'] ?? '') !== '')
                        <span class="badge badge-amber">Status: {{ $filters['status'] == '1' ? 'Aktif' : 'Nonaktif' }}</span>
                    @endif
                </div>
            @endif
        </div>
    </div>

    {{-- Users Table --}}
    <div class="data-table-container">
        <div class="data-table-header">
            <h3 class="data-table-title">
                <span>Daftar Pengguna</span>
                <span class="ml-2 rounded-full bg-cyan-100 px-2.5 py-0.5 text-xs font-semibold text-cyan-700">
                    {{ $users->total() }} data
                </span>
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>
                            <input type="checkbox" class="h-4 w-4 rounded border-slate-300">
                        </th>
                        <th>Pengguna</th>
                        <th>NIP / Nomor Induk</th>
                        <th>Unit Kerja</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr class="group">
                            <td>
                                <input type="checkbox" class="h-4 w-4 rounded border-slate-300">
                            </td>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="avatar avatar-md bg-gradient-to-br from-cyan-500 to-blue-600">
                                        @if($user->email)
                                            {{ substr($user->email, 0, 2) }}
                                        @else
                                            {{ substr($user->name, 0, 2) }}
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-semibold text-slate-900">{{ $user->name }}</p>
                                        <p class="text-xs text-slate-500">{{ $user->email ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="font-mono text-sm text-slate-600">{{ $user->nomor_induk }}</span>
                            </td>
                            <td>
                                <span class="text-sm text-slate-600">{{ $user->dept_name ?? '-' }}</span>
                            </td>
                            <td>
                                @php
                                    $roleColors = [
                                        'superadmin' => 'badge-rose',
                                        'admin' => 'badge-cyan',
                                        'kasubbag' => 'badge-blue',
                                        'kasi' => 'badge-indigo',
                                        'kepala' => 'badge-violet',
                                        'petugas' => 'badge-emerald',
                                        'pegawai' => 'badge-slate',
                                    ];
                                    $roleColor = $roleColors[$user->role] ?? 'badge-slate';
                                @endphp
                                <span class="badge {{ $roleColor }}">{{ ucfirst($user->role) }}</span>
                            </td>
                            <td>
                                <button
                                    type="button"
                                    data-user-id="{{ $user->id }}"
                                    data-current-status="{{ $user->status }}"
                                    class="toggle-status group/btn inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold transition-all"
                                    onclick="toggleUserStatus(this)"
                                >
                                    @if($user->status == 1)
                                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                                        <span class="text-emerald-700 group-hover/btn:text-emerald-900">Aktif</span>
                                    @else
                                        <span class="h-2 w-2 rounded-full bg-slate-400"></span>
                                        <span class="text-slate-500 group-hover/btn:text-slate-700">Nonaktif</span>
                                    @endif
                                </button>
                            </td>
                            <td>
                                <div class="data-table-actions-cell">
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-icon btn-ghost" title="Edit">
                                        <svg class="h-4 w-4 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    <button
                                        type="button"
                                        class="btn btn-icon btn-ghost delete-user"
                                        data-user-id="{{ $user->id }}"
                                        data-user-name="{{ $user->name }}"
                                        title="Hapus"
                                        @if($user->role === 'superadmin') disabled @endif
                                    >
                                        <svg class="h-4 w-4 text-rose-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-12">
                                <svg class="mx-auto mb-4 h-16 w-16 text-slate-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                <p class="text-lg font-semibold text-slate-600">Belum ada data pengguna</p>
                                <p class="text-sm text-slate-400">Tambahkan pengguna baru untuk memulai</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($users->hasPages())
            <div class="flex items-center justify-between border-t border-slate-100 bg-slate-50 px-6 py-4">
                <p class="text-sm text-slate-500">
                    Menampilkan {{ $users->firstItem() ?? 0 }} - {{ $users->lastItem() ?? 0 }} dari {{ $users->total() }} data
                </p>
                <div class="pagination">
                    @if($users->onFirstPage())
                        <button class="pagination-item disabled" disabled>Sebelumnya</button>
                    @else
                        <a href="{{ $users->previousPageUrl() }}" class="pagination-item">Sebelumnya</a>
                    @endif

                    @foreach($users->getUrlRange(1, $users->lastPage()) as $page => $url)
                        @if($page <= 3 || $page > $users->lastPage() - 2 || abs($page - $users->currentPage()) < 2)
                            @if($page == $users->currentPage())
                                <span class="pagination-item active">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="pagination-item">{{ $page }}</a>
                            @endif
                        @elseif($loop->index == 2 || $loop->index == $users->lastPage() - 3)
                            <span class="pagination-ellipsis">...</span>
                        @endif
                    @endforeach

                    @if($users->hasMorePages())
                        <a href="{{ $users->nextPageUrl() }}" class="pagination-item">Selanjutnya</a>
                    @else
                        <button class="pagination-item disabled" disabled>Selanjutnya</button>
                    @endif
                </div>
            </div>
        @endif
    </div>

    {{-- Delete Confirmation Modal --}}
    <div id="deleteModal" class="fixed inset-0 z-50 hidden">
        <div class="modal-overlay" onclick="closeDeleteModal()"></div>
        <div class="modal">
            <div class="modal-header">
                <h3 class="modal-title">Konfirmasi Hapus</h3>
                <button onclick="closeDeleteModal()" class="modal-close">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus pengguna <strong id="deleteUserName"></strong>?</p>
                <p class="mt-2 text-sm text-slate-500">Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-footer">
                <button onclick="closeDeleteModal()" class="btn btn-secondary">Batal</button>
                <button id="confirmDeleteBtn" class="btn btn-danger">Hapus</button>
            </div>
        </div>
    </div>
</x-admin.layouts.app>

@push('scripts')
<script>
    // Toggle user status
    async function toggleUserStatus(button) {
        const userId = button.dataset.userId;
        const currentStatus = button.dataset.currentStatus;

        try {
            const response = await fetch(`/admin/users/${userId}/toggle-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'Accept': 'application/json',
                },
            });

            const data = await response.json();

            if (data.success) {
                // Update button appearance
                const newStatus = data.new_status;
                button.dataset.currentStatus = newStatus;

                if (newStatus === 1) {
                    button.innerHTML = `
                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                        <span class="text-emerald-700 group-hover/btn:text-emerald-900">Aktif</span>
                    `;
                } else {
                    button.innerHTML = `
                        <span class="h-2 w-2 rounded-full bg-slate-400"></span>
                        <span class="text-slate-500 group-hover/btn:text-slate-700">Nonaktif</span>
                    `;
                }

                // Show success message
                showToast('success', data.message);
            } else {
                showToast('error', data.message);
            }
        } catch (error) {
            showToast('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }

    // Delete user
    let deleteUserId = null;
    const deleteModal = document.getElementById('deleteModal');
    const deleteUserName = document.getElementById('deleteUserName');
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');

    document.querySelectorAll('.delete-user:not([disabled])').forEach(button => {
        button.addEventListener('click', function() {
            deleteUserId = this.dataset.userId;
            deleteUserName.textContent = this.dataset.userName;
            deleteModal.classList.remove('hidden');
        });
    });

    confirmDeleteBtn.addEventListener('click', async function() {
        if (!deleteUserId) return;

        try {
            const response = await fetch(`/admin/users/${deleteUserId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'Accept': 'application/json',
                },
            });

            const data = await response.json();

            if (data.success) {
                closeDeleteModal();
                // Reload page to show updated list
                window.location.reload();
            } else {
                showToast('error', data.message);
            }
        } catch (error) {
            showToast('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    });

    function closeDeleteModal() {
        deleteModal.classList.add('hidden');
        deleteUserId = null;
    }

    // Toast notification
    function showToast(type, message) {
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.innerHTML = `
            <svg class="toast-icon text-${type === 'success' ? 'emerald' : 'rose'}-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                ${type === 'success'
                    ? '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>'
                    : '<path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>'
                }
            </svg>
            <p class="flex-1 text-sm font-medium text-slate-700">${message}</p>
        `;

        document.querySelector('.toast-container')?.appendChild(toast);

        setTimeout(() => {
            toast.remove();
        }, 3000);
    }
</script>
@endpush