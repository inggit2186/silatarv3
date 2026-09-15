<x-admin.layouts.app>
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <span class="page-label">// Presensi Error</span>
            <h1 class="page-title">Laporan Presensi Error</h1>
            <p class="page-subtitle">Kelola laporan presensi error dari pengguna</p>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid-4 mb-6">
        <div class="stat-card">
            <div class="stat-icon red">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4.5c-.77-.833-2.694-.833-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
            </div>
            <div class="stat-content">
                <span class="stat-label">Sistem Error</span>
                <span class="stat-value">{{ $stats['total_sistem_error'] }}</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon yellow">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="stat-content">
                <span class="stat-label">Tugas Luar</span>
                <span class="stat-value">{{ $stats['total_tugas_luar'] }}</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon cyan">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <div class="stat-content">
                <span class="stat-label">Hari Ini</span>
                <span class="stat-value">{{ $stats['today'] }}</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon emerald">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
            </div>
            <div class="stat-content">
                <span class="stat-label">Bulan Ini</span>
                <span class="stat-value">{{ $stats['this_month'] }}</span>
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
                    <p class="text-sm text-muted">Cari dan filter laporan presensi error</p>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.presensi-error.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="form-group">
                    <label class="form-label">Pencarian</label>
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Nama atau NIP..." class="form-input pl-10">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="SISTEM_ERROR" {{ ($filters['status'] ?? '') == 'SISTEM_ERROR' ? 'selected' : '' }}>Sistem Error</option>
                        <option value="TUGAS_LUAR" {{ ($filters['status'] ?? '') == 'TUGAS_LUAR' ? 'selected' : '' }}>Tugas Luar</option>
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
                    <label class="form-label">Tanggal Mulai</label>
                    <input type="date" name="start_date" value="{{ $filters['start_date'] ?? '' }}" class="form-input">
                </div>

                <div class="form-group">
                    <label class="form-label">Tanggal Akhir</label>
                    <input type="date" name="end_date" value="{{ $filters['end_date'] ?? '' }}" class="form-input">
                </div>

                <div class="col-span-1 md:col-span-2 lg:col-span-4 flex items-center gap-3">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    @if(($filters['search'] ?? '') || ($filters['status'] ?? '') || ($filters['dept_id'] ?? '') || ($filters['start_date'] ?? '') || ($filters['end_date'] ?? ''))
                        <a href="{{ route('admin.presensi-error.index') }}" class="btn btn-secondary">Reset</a>
                    @endif
                </div>
            </form>

            @if(($filters['search'] ?? '') || ($filters['status'] ?? '') || ($filters['dept_id'] ?? '') || ($filters['start_date'] ?? '') || ($filters['end_date'] ?? ''))
                <div class="active-filters">
                    <span class="text-sm text-muted">Filter aktif:</span>
                    @if($filters['search'] ?? '')
                        <span class="badge badge-info">"{{ $filters['search'] }}"</span>
                    @endif
                    @if($filters['status'] ?? '')
                        <span class="badge badge-info">Status: {{ $filters['status'] }}</span>
                    @endif
                    @if($filters['dept_id'] ?? '')
                        <span class="badge badge-info">Unit: {{ collect($departments)->firstWhere('id', $filters['dept_id'])?->nama ?? $filters['dept_id'] }}</span>
                    @endif
                    @if($filters['start_date'] ?? '')
                        <span class="badge badge-info">Dari: {{ $filters['start_date'] }}</span>
                    @endif
                    @if($filters['end_date'] ?? '')
                        <span class="badge badge-info">Sampai: {{ $filters['end_date'] }}</span>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <!-- Presensi Error Table -->
    <div class="card">
        <div class="table-header">
            <div class="table-title-icon">
                <div class="icon" style="background: rgba(239,68,68,0.1);">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                    </svg>
                </div>
                <div>
                    <h3 class="table-title">Daftar Laporan Presensi Error</h3>
                    <p class="table-subtitle">Total {{ $presensi->total() }} data</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <span class="data-count">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 17V7m0 10a2 2 0 01-2 2H7a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10V7a2 2 0 01-2 2H7a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2z"/>
                    </svg>
                    {{ $presensi->total() }} Records
                </span>
            </div>
        </div>
        <div class="table-wrapper">
            <table class="table">
                <thead>
                    <tr>
                        <th>Foto</th>
                        <th>User</th>
                        <th>Unit Kerja</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Jam Masuk/Pulang</th>
                        <th>Jam Ambil</th>
                        <th>Koordinat</th>
                        <th>Lokasi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($presensi as $item)
                        <tr>
                            <td>
                                @if($item->foto_path)
                                    <img src="{{ asset('storage/' . $item->foto_path) }}" alt="Foto Presensi" class="w-10 h-10 rounded-lg object-cover border border-border" onerror="this.style.display='none'">
                                @else
                                    <div class="w-10 h-10 rounded-lg bg-muted flex items-center justify-center">
                                        <svg class="w-5 h-5 text-muted" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="font-medium">{{ $item->user_name ?? '-' }}</div>
                                <div class="text-sm text-muted">{{ $item->user_nip }}</div>
                            </td>
                            <td>{{ $item->dept_name ?? '-' }}</td>
                            <td>
                                <span class="badge {{ $item->status === 'SISTEM_ERROR' ? 'badge-danger' : 'badge-warning' }}">
                                    {{ $item->status === 'SISTEM_ERROR' ? 'Sistem Error' : 'Tugas Luar' }}
                                </span>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                            <td>
                                @if($item->m_absen)
                                    <div class="text-sm">Masuk: {{ $item->m_absen }}</div>
                                @endif
                                @if($item->p_absen)
                                    <div class="text-sm">Pulang: {{ $item->p_absen }}</div>
                                @endif
                                @if(!$item->m_absen && !$item->p_absen)
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($item->error_masuk_taken_at)
                                    <div class="text-sm">{{ $item->error_masuk_taken_at }}</div>
                                @endif
                                @if($item->error_pulang_taken_at)
                                    <div class="text-sm">{{ $item->error_pulang_taken_at }}</div>
                                @endif
                                @if(!$item->error_masuk_taken_at && !$item->error_pulang_taken_at)
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($item->m_latitude && $item->m_longitude)
                                    <a href="https://www.google.com/maps?q={{ $item->m_latitude }},{{ $item->m_longitude }}"
                                       target="_blank"
                                       class="text-primary hover:underline text-sm"
                                       title="Buka di Google Maps">
                                        {{ number_format($item->m_latitude, 6) }}, {{ number_format($item->m_longitude, 6) }}
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <div class="max-w-xs truncate text-sm">{{ $item->m_alamat ?? $item->p_alamat ?? '-' }}</div>
                            </td>
                            <td>
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('presensi-error.surat', ['id' => $item->id, 'jenis' => $item->m_absen ? 'masuk' : 'pulang']) }}"
                                       target="_blank"
                                       class="action-btn"
                                       title="Lihat Surat Keterangan">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </a>
                                    <button type="button" class="action-btn delete"
                                            data-id="{{ $item->id }}"
                                            data-name="{{ $item->user_name }}"
                                            data-nip="{{ $item->user_nip }}"
                                            data-tanggal="{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}"
                                            onclick="openDeleteModal(this)"
                                            title="Hapus">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10">
                                <div class="empty-state">
                                    <svg class="w-12 h-12 mx-auto mb-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <h3 class="text-lg font-semibold mb-2">Tidak ada data presensi error</h3>
                                    <p class="text-muted">Belum ada laporan presensi error yang masuk</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($presensi->hasPages())
            <div class="table-footer">
                {{ $presensi->links() }}
            </div>
        @endif
    </div>

    <!-- Delete Modal -->
    <div id="deleteModal" class="modal-backdrop">
        <div class="modal" style="max-width: 440px;">
            <div class="modal-header">
                <div>
                    <h2 class="modal-title">Hapus Laporan Presensi</h2>
                    <p class="text-sm text-muted">Tindakan ini tidak dapat dibatalkan</p>
                </div>
                <button onclick="closeDeleteModal()" class="modal-close">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="modal-body">
                <p class="mb-4">Apakah Anda yakin ingin menghapus data presensi error ini? Data presensi normal (jika ada) tidak akan terpengaruh.</p>
                <div class="bg-muted p-4 rounded-lg">
                    <p><strong>User:</strong> <span id="deleteUserName"></span></p>
                    <p><strong>NIP:</strong> <span id="deleteUserNip"></span></p>
                    <p><strong>Tanggal:</strong> <span id="deleteTanggal"></span></p>
                </div>
                <div class="alert alert-danger mt-4">
                    <svg class="alert-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <span class="alert-message">Data yang dihapus tidak dapat dikembalikan</span>
                </div>
            </div>
            <div class="modal-footer">
                <button onclick="closeDeleteModal()" class="btn btn-secondary">Batal</button>
                <form id="deleteForm" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Toast Container -->
    <div id="toastContainer" class="fixed bottom-4 right-4 z-50 space-y-2"></div>

    <script>
        function openDeleteModal(button) {
            const id = button.dataset.id;
            const name = button.dataset.name;
            const nip = button.dataset.nip;
            const tanggal = button.dataset.tanggal;

            document.getElementById('deleteUserName').textContent = name;
            document.getElementById('deleteUserNip').textContent = nip;
            document.getElementById('deleteTanggal').textContent = tanggal;
            document.getElementById('deleteForm').action = `/admin/presensi-error/${id}`;
            document.getElementById('deleteModal').classList.add('active');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.remove('active');
        }

        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) closeDeleteModal();
        });

        // Handle delete form submission via AJAX
        document.getElementById('deleteForm').addEventListener('submit', async function(e) {
            e.preventDefault();

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
                setTimeout(() => location.reload(), 1500);
            } else {
                showToast('error', data.message);
            }

            closeDeleteModal();
        });

        function showToast(type, message) {
            const toast = document.createElement('div');
            toast.className = `toast toast-${type}`;
            toast.innerHTML = `
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    ${type === 'success'
                        ? '<path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>'
                        : '<path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>'}
                </svg>
                <span>${message}</span>
            `;
            document.getElementById('toastContainer').appendChild(toast);
            setTimeout(() => toast.remove(), 5000);
        }
    </script>
</x-admin.layouts.app>
