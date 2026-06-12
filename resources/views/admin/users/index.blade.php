<x-admin.layouts.app>
    {{-- Page Header with Cyberpunk Style --}}
    <div class="admin-page-header">
        <div class="flex items-center gap-4">
            <div class="cyber-header-icon">
                <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </div>
            <div>
                <h1 class="admin-page-title">
                    <span class="cyber-title-text">Manajemen Pengguna</span>
                </h1>
                <p class="admin-page-subtitle">
                    <svg class="inline h-4 w-4 mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Kelola data pengguna dan hak akses sistem
</p>
            </div>
        </div>
        <div class="admin-page-actions">
            <a href="{{ route('admin.users.create') }}" class="cyber-btn-primary">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
                <span>Tambah User</span>
            </a>
        </div>
    </div>

    {{-- Stats Row --}}
    <div class="cyber-stats-grid mb-6">
        <div class="cyber-stat-card">
            <div class="cyber-stat-icon">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <div class="cyber-stat-info">
                <span class="cyber-stat-value">{{ $users->total() }}</span>
                <span class="cyber-stat-label">Total Pengguna</span>
            </div>
        </div>
    </div>

    {{-- Filters Card --}}
    <div class="cyber-card mb-6">
        <div class="cyber-card-header">
            <div class="flex items-center gap-3">
                <div class="cyber-section-icon">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 0111v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V16l-4-4z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="cyber-section-title">Filter Data</h3>
                    <p class="cyber-section-subtitle">Cari dan filter pengguna berdasarkan kriteria</p>
                </div>
            </div>
        </div>
        <div class="cyber-card-body">
            <form method="GET" action="{{ route('admin.users.index') }}" class="cyber-form-grid">
                {{-- Search --}}
                <div class="cyber-form-group">
                    <label class="cyber-form-label">
                        <svg class="inline h-4 w-4 mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Pencarian
                    </label>
                    <div class="cyber-input-wrapper">
                        <svg class="cyber-input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input
                            type="text"
                            name="search"
                            value="{{ $filters['search'] ?? '' }}"
                            placeholder="Nama, email, atau NIP..."
                            class="cyber-input"
                        >
                    </div>
                </div>

                {{-- Role Filter --}}
                <div class="cyber-form-group">
                    <label class="cyber-form-label">
                        <svg class="inline h-4 w-4 mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M912l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        Role
                    </label>
                    <select name="role" class="cyber-select">
                        <option value="">Semua Role</option>
                        @foreach($roles as $role)
                            <option value="{{ $role }}" {{ ($filters['role'] ?? '') == $role ? 'selected' : '' }}>
                                {{ ucfirst($role) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Department Filter --}}
                <div class="cyber-form-group">
                    <label class="cyber-form-label">
                        <svg class="inline h-4 w-4 mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        Unit Kerja
                    </label>
                    <select name="dept_id" class="cyber-select">
                        <option value="">Semua Unit</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ ($filters['dept_id'] ?? '') == $dept->id ? 'selected' : '' }}>
                                {{ $dept->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Status Filter --}}
                <div class="cyber-form-group">
                    <label class="cyber-form-label">
                        <svg class="inline h-4 w-4 mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Status
                    </label>
                    <select name="status" class="cyber-select">
                        <option value="">Semua</option>
                        <option value="1" {{ ($filters['status'] ?? '') == '1' ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ ($filters['status'] ?? '') == '0' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>

                {{-- Filter Buttons --}}
                <div class="cyber-form-actions" style="align-self: flex-end;">
                    <button type="submit" class="cyber-btn-primary">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V16l-4-4z"/>
                        </svg>
                        Filter
                    </button>
                    @if(($filters['search'] ?? '') || ($filters['role'] ?? '') || ($filters['dept_id'] ?? '') || ($filters['status'] ?? '') !== '')
                        <a href="{{ route('admin.users.index') }}" class="cyber-btn-secondary">
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
                <div class="cyber-active-filters">
                    <span class="cyber-filter-label">
                        <svg class="inline h-4 w-4 mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 0111v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V16l-4-4z"/>
                        </svg>
                        Filter aktif:
                    </span>
                    @if($filters['search'] ?? '')
                        <span class="cyber-filter-tag">
                            <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            "{{ $filters['search'] }}"
                        </span>
                    @endif
                    @if($filters['role'] ?? '')
                        <span class="cyber-filter-tag cyan">Role: {{ ucfirst($filters['role']) }}</span>
                    @endif
                    @if($filters['dept_id'] ?? '')
                        @php
                            $selectedDept = collect($departments)->firstWhere('id', $filters['dept_id']);
                        @endphp
                        <span class="cyber-filter-tag emerald">Unit: {{ $selectedDept ? $selectedDept->nama : $filters['dept_id'] }}</span>
                    @endif
                    @if(($filters['status'] ?? '') !== '')
                        <span class="cyber-filter-tag amber">Status: {{ $filters['status'] == '1' ? 'Aktif' : 'Nonaktif' }}</span>
                    @endif
                </div>
            @endif
        </div>
    </div>

    {{-- Users Table --}}
    <div class="cyber-table-container">
        <div class="cyber-table-header">
            <div class="flex items-center gap-3">
                <div class="cyber-table-icon">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                    </svg>
                </div>
                <div>
                    <h3 class="cyber-table-title">Daftar Pengguna</h3>
                    <p class="cyber-table-subtitle">Total {{ $users->total() }} data</p>
                </div>
            </div>
            <div class="cyber-table-actions">
                <span class="cyber-data-badge">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    {{ $users->total() }} Records
                </span>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="cyber-table">
                <thead>
                    <tr>
                        <th class="cyber-th" style="width: 40px;">
                            <input type="checkbox" class="cyber-checkbox">
                        </th>
                        <th class="cyber-th">
                            <svg class="inline h-4 w-4 mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Pengguna
                        </th>
                        <th class="cyber-th">
                            <svg class="inline h-4 w-4 mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                            </svg>
                            NIP
                        </th>
                        <th class="cyber-th">
                            <svg class="inline h-4 w-4 mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/>
                            </svg>
                            Unit Kerja
                        </th>
                        <th class="cyber-th">
                            <svg class="inline h-4 w-4 mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                            Role
                        </th>
                        <th class="cyber-th">
                            <svg class="inline h-4 w-4 mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Status
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
                    @forelse($users as $user)
                        <tr class="cyber-tr">
                            <td class="cyber-td">
                                <input type="checkbox" class="cyber-checkbox">
                            </td>
                            <td class="cyber-td">
                                <div class="flex items-center gap-3">
                                    <div class="cyber-avatar">
                                        @if(!empty($user->pp) && !empty($user->nomor_induk))
                                            <img
                                                src="{{ asset('assets/img/users/' . $user->nomor_induk . '/' . $user->pp) }}"
                                                alt="{{ $user->name }}"
                                                class="h-full w-full object-cover"
                                                onerror="this.style.display='none'; this.parentElement.innerHTML='<div class=\'cyber-avatar-fallback\'>' + '{{ substr($user->name, 0, 2) }}' + '</div>';"
                                            >
                                        @else
                                            <div class="cyber-avatar-fallback">{{ substr($user->name, 0, 2) }}</div>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="cyber-user-name">{{ $user->name }}</p>
                                        <p class="cyber-user-email">
                                            <svg class="inline h-3 w-3 mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                            </svg>
                                            {{ $user->email ?? '-' }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="cyber-td">
                                <span class="cyber-nip">{{ $user->nomor_induk }}</span>
                            </td>
                            <td class="cyber-td">
                                <span class="cyber-unit">
                                    <svg class="inline h-4 w-4 mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/>
                                    </svg>
                                    {{ $user->dept_name ?? '-' }}
                                </span>
                            </td>
                            <td class="cyber-td">
                                @php
                                    $roleColors = [
                                        'superadmin' => 'cyber-badge-rose',
                                        'admin' => 'cyber-badge-cyan',
                                        'kasubbag' => 'cyber-badge-blue',
                                        'kasi' => 'cyber-badge-indigo',
                                        'kepala' => 'cyber-badge-violet',
                                        'petugas' => 'cyber-badge-emerald',
                                        'pegawai' => 'cyber-badge-slate',
                                    ];
                                    $roleColor = $roleColors[$user->role] ?? 'cyber-badge-slate';
                                @endphp
                                <span class="cyber-role-badge {{ $roleColor }}">{{ ucfirst($user->role) }}</span>
                            </td>
                            <td class="cyber-td">
                                <button
                                    type="button"
                                    data-user-id="{{ $user->id }}"
                                    data-current-status="{{ $user->status }}"
                                    class="cyber-status-toggle"
                                    onclick="toggleUserStatus(this)"
                                >
                                    @if($user->status == 1)
                                        <span class="cyber-status-dot active"></span>
                                        <span class="cyber-status-text active">Aktif</span>
                                    @else
                                        <span class="cyber-status-dot inactive"></span>
                                        <span class="cyber-status-text inactive">Nonaktif</span>
                                    @endif
                                </button>
                            </td>
                            <td class="cyber-td">
                                <div class="cyber-actions">
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="cyber-action-btn edit" title="Edit">
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    <button
                                        type="button"
                                        class="cyber-action-btn delete"
                                        data-user-id="{{ $user->id }}"
                                        data-user-name="{{ $user->name }}"
                                        title="Hapus"
                                        @if($user->role === 'superadmin') disabled @endif
                                    >
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="cyber-empty">
                                <div class="cyber-empty-content">
                                    <div class="cyber-empty-icon">
                                        <svg class="h-16 w-16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                    </div>
                                    <h3 class="cyber-empty-title">Belum ada data pengguna</h3>
                                    <p class="cyber-empty-text">Tambahkan pengguna baru untuk memulai</p>
                                    <a href="{{ route('admin.users.create') }}" class="cyber-btn-primary mt-4">
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                                        </svg>
                                        Tambah User
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($users->hasPages())
            <div class="cyber-pagination-wrapper">
                <div class="cyber-pagination-info">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span>Menampilkan {{ $users->firstItem() ?? 0 }} - {{ $users->lastItem() ?? 0 }} dari {{ $users->total() }} data</span>
                </div>
                <div class="cyber-pagination">
                    @if($users->onFirstPage())
                        <button class="cyber-pagination-btn disabled" disabled>
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                            </svg>
                            Sebelumnya
                        </button>
                    @else
                        <a href="{{ $users->previousPageUrl() }}" class="cyber-pagination-btn">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                            </svg>
                            Sebelumnya
                        </a>
                    @endif

                    @foreach($users->getUrlRange(1, $users->lastPage()) as $page => $url)
                        @if($page <= 3 || $page > $users->lastPage() - 2 || abs($page - $users->currentPage()) < 2)
                            @if($page == $users->currentPage())
                                <span class="cyber-pagination-btn active">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="cyber-pagination-btn">{{ $page }}</a>
                            @endif
                        @elseif($loop->index == 2 || $loop->index == $users->lastPage() - 3)
                            <span class="cyber-pagination-ellipsis">...</span>
                        @endif
                    @endforeach

                    @if($users->hasMorePages())
                        <a href="{{ $users->nextPageUrl() }}" class="cyber-pagination-btn">
                            Selanjutnya
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    @else
                        <button class="cyber-pagination-btn disabled" disabled>
                            Selanjutnya
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                    @endif
                </div>
            </div>
        @endif
    </div>

    {{-- Delete Confirmation Modal --}}
    <div id="deleteModal" class="cyber-modal-overlay">
        <div class="cyber-modal-content">
            <div class="cyber-modal-glow cyber-modal-glow-red"></div>
            <div class="relative z-10">
                <div class="cyber-modal-header">
                    <div class="flex items-center gap-4">
                        <div class="cyber-modal-icon danger">
                            <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="cyber-modal-title">Konfirmasi Hapus</h3>
                            <p class="cyber-modal-subtitle">Tindakan ini tidak dapat dibatalkan</p>
                        </div>
                    </div>
                    <button onclick="closeDeleteModal()" class="cyber-modal-close">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <div class="cyber-modal-body">
                    <p>Apakah Anda yakin ingin menghapus pengguna <strong class="text-rose-400" id="deleteUserName"></strong>?</p>
                    <div class="cyber-warning-box mt-4">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <span>Data yang dihapus tidak dapat dikembalikan</span>
                    </div>
                </div>
                <div class="cyber-modal-footer">
                    <button onclick="closeDeleteModal()" class="cyber-btn-secondary">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Batal
                    </button>
                    <button id="confirmDeleteBtn" class="cyber-btn-danger">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Hapus
                    </button>
                </div>
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
                const newStatus = data.new_status;
                button.dataset.currentStatus = newStatus;

                if (newStatus === 1) {
                    button.innerHTML = `
                        <span class="cyber-status-dot active"></span>
                        <span class="cyber-status-text active">Aktif</span>
                    `;
                } else {
                    button.innerHTML = `
                        <span class="cyber-status-dot inactive"></span>
                        <span class="cyber-status-text inactive">Nonaktif</span>
                    `;
                }

                showCyberToast('success', data.message);
            } else {
                showCyberToast('error', data.message);
            }
        } catch (error) {
            showCyberToast('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }

    // Delete user
    let deleteUserId = null;
    const deleteModal = document.getElementById('deleteModal');
    const deleteUserName = document.getElementById('deleteUserName');
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');

    document.querySelectorAll('.cyber-action-btn.delete:not([disabled])').forEach(button => {
        button.addEventListener('click', function() {
            deleteUserId = this.dataset.userId;
            deleteUserName.textContent = this.dataset.userName;
            deleteModal.classList.add('active');
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
                window.location.reload();
            } else {
                showCyberToast('error', data.message);
            }
        } catch (error) {
            showCyberToast('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    });

    function closeDeleteModal() {
        deleteModal.classList.remove('active');
        deleteUserId = null;
    }

    // Cyberpunk Toast notification
    function showCyberToast(type, message) {
        const toast = document.createElement('div');
        toast.className = `cyber-toast cyber-toast-${type}`;

        const iconSvg = type === 'success'
            ? '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>'
            : '<path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>';

        toast.innerHTML = `
            <div class="cyber-toast-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    ${iconSvg}
                </svg>
            </div>
            <span class="cyber-toast-message">${message}</span>
            <button onclick="this.parentElement.remove()" class="cyber-toast-close">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        `;

        document.body.appendChild(toast);
        setTimeout(() => toast.classList.add('show'), 10);
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }, 4000);
    }

    // Close modal on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeDeleteModal();
    });
</script>
@endpush