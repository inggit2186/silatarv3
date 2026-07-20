<x-admin.layouts.app>
    @section('styles')
    <link rel="stylesheet" href="{{ asset('css/penilaian-kinerja.css') }}">
    @endsection

    {{-- Page Header --}}
    <div class="page-header-pk">
        <div class="page-info">
            <span class="page-label">// Penilaian Kinerja</span>
            <h1>Penilaian Kinerja Pejabat</h1>
            <p>Nilai kinerja pejabat struktural secara triwulanan</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.penilaian-kinerja.create') }}" class="btn btn-primary">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Buat Penilaian Baru
            </a>
        </div>
    </div>

    {{-- Statistics Cards --}}
    <div class="grid-4 mb-6">
        <div class="stat-card">
            <div class="stat-icon cyan">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="stat-content">
                <span class="stat-label">Total Penilaian</span>
                <span class="stat-value">{{ $stats['total'] }}</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon emerald">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <div class="stat-content">
                <span class="stat-label">Tahun Ini</span>
                <span class="stat-value">{{ $stats['tahun_ini'] }}</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon green">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="stat-content">
                <span class="stat-label">Total Thumbs Up</span>
                <span class="stat-value text-success">{{ $stats['total_up'] }}</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon red">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/>
                </svg>
            </div>
            <div class="stat-content">
                <span class="stat-label">Total Thumbs Down</span>
                <span class="stat-value text-danger">{{ $stats['total_down'] }}</span>
            </div>
        </div>
    </div>

    {{-- Filter Bar --}}
    <form method="GET" action="{{ route('admin.penilaian-kinerja.index') }}" class="filter-bar">
        <div class="filter-form">
            <div class="filter-group">
                <label class="filter-label">Tahun</label>
                <select name="tahun" class="form-select" onchange="this.form.submit()">
                    @foreach($tahunOptions as $tahun)
                        <option value="{{ $tahun }}" {{ $tahun == $filters['tahun'] ? 'selected' : '' }}>
                            {{ $tahun }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="filter-group">
                <label class="filter-label">Triwulan</label>
                <select name="triwulan" class="form-select" onchange="this.form.submit()">
                    @foreach($triwulanOptions as $key => $label)
                        <option value="{{ $key }}" {{ $key == $filters['triwulan'] ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>
            <a href="{{ route('admin.penilaian-kinerja.create', ['tahun' => $filters['tahun'], 'triwulan' => $filters['triwulan']]) }}" class="btn btn-primary btn-sm">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Buat Baru
            </a>
        </div>
    </form>

    {{-- Data Table --}}
    <div class="card">
        <div class="table-header">
            <h3>Daftar Penilaian</h3>
            <span class="badge">{{ $penilaians->total() }} penilaian</span>
        </div>
        <div class="table-wrapper">
            @if($penilaians->isEmpty())
                <div class="empty-state-pk">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <h3>Belum Ada Penilaian</h3>
                    <p>Tidak ada penilaian untuk periode ini. Klik tombol "Buat Baru" untuk memulai.</p>
                </div>
            @else
                <table class="table">
                    <thead>
                        <tr>
                            <th>Pejabat</th>
                            <th>Jabatan</th>
                            <th>Periode</th>
                            <th>Skor</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($penilaians as $penilaian)
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="table-avatar">
                                        {{ substr($penilaian->pejabat->name, 0, 2) }}
                                    </div>
                                    <div>
                                        <span class="font-medium">{{ $penilaian->pejabat->name }}</span>
                                        @if($penilaian->pejabat->nomor_induk)
                                            <br><span class="text-muted text-xs">{{ $penilaian->pejabat->nomor_induk }}</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-{{ $penilaian->pejabat->kat_jabatan === 'kepala' ? 'amber' : ($penilaian->pejabat->kat_jabatan === 'kasubbag' ? 'cyan' : 'violet') }}">
                                    {{ ucfirst($penilaian->pejabat->kat_jabatan) }}
                                </span>
                            </td>
                            <td>
                                <span class="text-sm">Triwulan {{ $penilaian->triwulan }}</span>
                                <br><span class="text-muted text-xs">{{ $penilaian->tahun }}</span>
                            </td>
                            <td>
                                <div class="flex items-center gap-2">
                                    <span class="score-badge positive">
                                        <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"/>
                                        </svg>
                                        {{ $penilaian->total_thumbs_up }}
                                    </span>
                                    <span class="score-badge negative">
                                        <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M10 15v4a3 3 0 0 0 3 3l4-9V2H5.72a2 2 0 0 0-2 1.7l-1.38 9a2 2 0 0 0 2 2.3zm7-13h2.67A2.31 2.31 0 0 1 22 4v7a2.31 2.31 0 0 1-2.33 2H17"/>
                                        </svg>
                                        {{ $penilaian->total_thumbs_down }}
                                    </span>
                                </div>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('admin.penilaian-kinerja.show', $penilaian->id) }}" class="btn btn-sm btn-secondary" title="Detail">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    <a href="{{ route('admin.penilaian-kinerja.edit', $penilaian->id) }}" class="btn btn-sm btn-primary" title="Edit">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete({{ $penilaian->id }})" title="Hapus">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
        @if($penilaians->hasPages())
            <div class="pagination">
                {{ $penilaians->withQueryString()->links() }}
            </div>
        @endif
    </div>

    {{-- Delete Confirmation Modal --}}
    <div id="deleteModal" class="modal-backdrop" x-data="{ show: false, id: null }">
        <div class="modal">
            <div class="modal-header">
                <h2>Konfirmasi Hapus</h2>
                <button type="button" class="modal-close" @click="show = false">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus penilaian ini? Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" @click="show = false">Batal</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>

    @section('scripts')
    <script>
        function confirmDelete(id) {
            const modal = document.getElementById('deleteModal');
            const form = document.getElementById('deleteForm');
            form.action = '/admin/penilaian-kinerja/' + id;
            modal.classList.add('active');
        }

        function closeDeleteModal() {
            const modal = document.getElementById('deleteModal');
            modal.classList.remove('active');
        }

        // Close modal on backdrop click
        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });

        // Close modal on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeDeleteModal();
            }
        });
    </script>
    @endsection
</x-admin.layouts.app>
