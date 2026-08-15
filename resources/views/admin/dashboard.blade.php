<x-admin.layouts.app>
    @php
    $userRole = auth()->user()->role ?? '';
    $isAdmin = in_array($userRole, ['admin', 'superadmin', 'kepala']);
    @endphp

    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <span class="page-label">// Control Center</span>
            <h1 class="page-title">Dashboard</h1>
            <p class="page-subtitle">Selamat datang di panel administrasi SILATAR</p>
        </div>
        <div class="page-actions">
            @if($isAdmin)
            <button type="button" onclick="openImpersonateModal()" class="btn btn-secondary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                </svg>
                Login sebagai
            </button>
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah User
            </a>
            @endif
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid-4 mb-6">
        <a href="{{ route('admin.users.index') }}" class="stat-card">
            <div class="stat-icon cyan">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <div class="stat-content">
                <span class="stat-label">Total Pengguna</span>
                <span class="stat-value">{{ $stats['total_users'] }}</span>
                <span class="stat-trend">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                    </svg>
                    {{ $stats['new_users_this_month'] }} baru bulan ini
                </span>
            </div>
        </a>

        <a href="{{ route('admin.requests.index') }}" class="stat-card">
            <div class="stat-icon emerald">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div class="stat-content">
                <span class="stat-label">Total Pengajuan</span>
                <span class="stat-value">{{ $stats['total_requests'] }}</span>
                <span class="stat-trend">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                    </svg>
                    {{ $stats['processed_this_month'] }} diproses
                </span>
            </div>
        </a>

        <div class="stat-card">
            <div class="stat-icon amber">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="stat-content">
                <span class="stat-label">Menunggu Tindakan</span>
                <span class="stat-value">{{ $stats['pending_requests'] }}</span>
                <span class="stat-trend warning">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    Perlu perhatian
                </span>
            </div>
        </div>

        <a href="{{ route('admin.services.index') }}" class="stat-card">
            <div class="stat-icon violet">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                </svg>
            </div>
            <div class="stat-content">
                <span class="stat-label">Layanan Aktif</span>
                <span class="stat-value">{{ $stats['total_services'] }}</span>
                <span class="stat-trend">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                    </svg>
                    Sistem aktif
                </span>
            </div>
        </a>
    </div>

    <!-- Charts Row -->
    <div class="grid-2 mb-6">
        <!-- Monthly Chart -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Pengajuan Per Bulan</h3>
                <span class="badge badge-gray">{{ now()->format('Y') }}</span>
            </div>
            <div class="card-body">
                <div class="chart-container" style="height: 200px;">
                    @foreach($monthlyData['labels'] as $index => $month)
                        @php
                            $value = $monthlyData['data'][$index] ?? 0;
                            $maxValue = max($monthlyData['data']) ?: 1;
                            $height = $maxValue > 0 ? ($value / $maxValue) * 100 : 0;
                        @endphp
                        <div class="chart-bar">
                            <span class="chart-bar-value">{{ $value }}</span>
                            <div class="chart-bar-fill" style="height: {{ max($height, 5) }}%"></div>
                            <span class="chart-bar-label">{{ $month }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Status Distribution -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Distribusi Status</h3>
            </div>
            <div class="card-body">
                <div class="progress-list">
                    @foreach($statusDistribution as $item)
                        @if($item['count'] > 0)
                        <div class="progress-item">
                            <div class="progress-header">
                                <span class="progress-label">{{ $item['label'] }}</span>
                                <span class="progress-value">{{ $item['count'] }}</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: {{ $item['percentage'] }}%; background: var(--chart-{{ $loop->index + 1 }});"></div>
                            </div>
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row -->
    <div class="grid-2 mb-6">
        <!-- Popular Services -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Layanan Terpopuler</h3>
                <a href="{{ route('admin.services.index') }}" class="btn btn-sm btn-secondary">Lihat semua</a>
            </div>
            <div class="card-body" style="padding: 0;">
                @if(count($popularServices))
                    <div class="progress-list" style="padding: 0 20px 20px;">
                        @foreach($popularServices as $index => $service)
                            <div class="progress-item">
                                <div class="progress-header">
                                    <span class="progress-label">{{ $service['name'] }}</span>
                                    <span class="progress-value">{{ $service['count'] }} pengajuan</span>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill primary" style="width: {{ $service['count'] > 0 ? ($service['count'] / $popularServices[0]['count']) * 100 : 0 }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                        </svg>
                        <p class="empty-state-title">Belum ada data</p>
                        <p class="empty-state-text">Tidak ada layanan yang tersedia</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Pending Requests -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Perlu Tindakan</h3>
                <a href="{{ route('admin.requests.index') }}?status=pending" class="btn btn-sm btn-secondary">Lihat semua</a>
            </div>
            <div class="card-body" style="padding: 0;">
                @if(count($pendingRequests))
                    <div class="table-wrapper">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>No. Referensi</th>
                                    <th>Layanan</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingRequests as $request)
                                <tr>
                                    <td class="font-mono text-sm">{{ $request['no_req'] }}</td>
                                    <td>
                                        <div>
                                            <span class="font-medium">{{ $request['title'] }}</span>
                                            <br><span class="text-xs text-muted">{{ $request['user'] }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge {{ $request['status'] === 'UNCHECK' ? 'badge-warning' : 'badge-info' }}">
                                            {{ $request['status'] === 'UNCHECK' ? 'Belum Dicek' : 'Pending' }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.requests.show', $request['id']) }}" class="btn btn-icon btn-secondary btn-icon-sm">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
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
                @else
                    <div class="empty-state success">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="empty-state-title">Semua bersih!</p>
                        <p class="empty-state-text">Tidak ada pengajuan yang perlu ditindaklanjuti saat ini.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Quick Actions (Admin Only) -->
    @if($isAdmin)
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Aksi Cepat</h3>
        </div>
        <div class="card-body">
            <div class="quick-actions">
                <a href="{{ route('admin.users.create') }}" class="quick-action">
                    <div class="quick-action-icon cyan">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                    </div>
                    <div class="quick-action-content">
                        <span class="quick-action-title">Tambah User Baru</span>
                        <span class="quick-action-desc">Buat akun pengguna baru</span>
                    </div>
                    <div class="quick-action-arrow">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                </a>

                <a href="{{ route('admin.services.create') }}" class="quick-action">
                    <div class="quick-action-icon emerald">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                        </svg>
                    </div>
                    <div class="quick-action-content">
                        <span class="quick-action-title">Buat Layanan Baru</span>
                        <span class="quick-action-desc">Tambahkan layanan baru</span>
                    </div>
                    <div class="quick-action-arrow">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                </a>

                <a href="{{ route('admin.requests.index') }}" class="quick-action">
                    <div class="quick-action-icon amber">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                    </div>
                    <div class="quick-action-content">
                        <span class="quick-action-title">Verifikasi Pengajuan</span>
                        <span class="quick-action-desc">Periksa pengajuan baru</span>
                    </div>
                    <div class="quick-action-arrow">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                </a>

                <a href="{{ route('admin.units.index') }}" class="quick-action">
                    <div class="quick-action-icon violet">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <div class="quick-action-content">
                        <span class="quick-action-title">Kelola Unit Kerja</span>
                        <span class="quick-action-desc">Atur unit dan department</span>
                    </div>
                    <div class="quick-action-arrow">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                </a>
            </div>
        </div>
    </div>
    @endif

    <!-- Impersonate Modal -->
    <div id="impersonateModal" class="modal-backdrop">
        <div class="modal">
            <div class="modal-header">
                <div>
                    <h2 class="modal-title">Login Sebagai</h2>
                    <p style="font-size: 13px; color: var(--text-muted); margin: 4px 0 0;">Masuk sebagai user lain</p>
                </div>
                <button type="button" onclick="closeImpersonateModal()" class="modal-close">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <form action="{{ route('admin.impersonate') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">NIP / NIK</label>
                        <input type="text" name="nip" class="form-input" placeholder="1978xxxx" required>
                        @error('nip')
                        <p style="font-size: 12px; color: var(--danger); margin-top: 4px;">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="alert alert-warning" style="margin: 0;">
                        <svg class="alert-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <div class="alert-content">
                            <p class="alert-message">Aktivitas Anda akan tercatat sebagai user yang di-impersonate.</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="closeImpersonateModal()" class="btn btn-secondary">Batal</button>
                    <button type="submit" class="btn btn-primary">Login sebagai User</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openImpersonateModal() {
            document.getElementById('impersonateModal').classList.add('active');
        }

        function closeImpersonateModal() {
            document.getElementById('impersonateModal').classList.remove('active');
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeImpersonateModal();
        });

        document.getElementById('impersonateModal').addEventListener('click', function(e) {
            if (e.target === this) closeImpersonateModal();
        });
    </script>
</x-admin.layouts.app>
