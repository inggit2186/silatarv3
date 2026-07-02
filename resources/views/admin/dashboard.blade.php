<x-admin.layouts.app>
    @php
    $userAccess = App\Http\Middleware\AdminAccess::getUserAccess(auth()->id());
    $isAdmin = in_array('admin', $userAccess) || in_array('superadmin', $userAccess);
    @endphp

    {{-- Page Header - NEO MIRAI Style --}}
    <div class="neo-page-header">
        <div class="neo-page-header-content">
            <span class="neo-page-label">// Control Center</span>
            <h1 class="neo-page-title">Dashboard</h1>
            <p class="neo-page-subtitle">Selamat datang di panel administrasi SILATAR</p>
        </div>
        <div class="neo-page-actions">
            @if($isAdmin)
            <a href="{{ route('admin.users.create') }}" class="neo-btn">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah User
            </a>
            @endif
        </div>
    </div>

    {{-- Stats Grid - NEO MIRAI --}}
    <div class="neo-stats-grid">
        {{-- Total Users --}}
        <a href="{{ route('admin.users.index') }}" class="neo-stat-card">
            <div class="neo-stat-icon cyan">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <div class="neo-stat-content">
                <div class="neo-stat-label">Total Pengguna</div>
                <div class="neo-stat-value">{{ $stats['total_users'] }}</div>
                <div class="neo-stat-trend">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                    </svg>
                    {{ $stats['new_users_this_month'] }} baru bulan ini
                </div>
            </div>
        </a>

        {{-- Total Requests --}}
        <a href="{{ route('admin.requests.index') }}" class="neo-stat-card">
            <div class="neo-stat-icon emerald">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div class="neo-stat-content">
                <div class="neo-stat-label">Total Pengajuan</div>
                <div class="neo-stat-value">{{ $stats['total_requests'] }}</div>
                <div class="neo-stat-trend">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                    </svg>
                    {{ $stats['processed_this_month'] }} diproses
                </div>
            </div>
        </a>

        {{-- Pending Requests --}}
        <div class="neo-stat-card">
            <div class="neo-stat-icon amber">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="neo-stat-content">
                <div class="neo-stat-label">Menunggu Tindakan</div>
                <div class="neo-stat-value">{{ $stats['pending_requests'] }}</div>
                <div class="neo-stat-trend warning">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    Perlu perhatian
                </div>
            </div>
        </div>

        {{-- Total Services --}}
        <a href="{{ route('admin.services.index') }}" class="neo-stat-card">
            <div class="neo-stat-icon violet">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                </svg>
            </div>
            <div class="neo-stat-content">
                <div class="neo-stat-label">Layanan Aktif</div>
                <div class="neo-stat-value">{{ $stats['total_services'] }}</div>
                <div class="neo-stat-trend">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                    </svg>
                    Sistem aktif
                </div>
            </div>
        </a>
    </div>

    {{-- Charts Row - NEO MIRAI --}}
    <div class="neo-content-grid">
        {{-- Monthly Requests Chart --}}
        <div class="neo-chart-card">
            <div class="neo-chart-header">
                <div class="flex flex-col gap-1">
                    <span class="neo-page-label">// Analytics</span>
                    <h3 class="neo-chart-title">Pengajuan Per Bulan</h3>
                </div>
                <div class="neo-chart-badge">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    {{ now()->format('Y') }}
                </div>
            </div>
            <div class="neo-chart-body">
                <div class="neo-bar-chart">
                    @foreach($monthlyData['labels'] as $index => $month)
                        @php
                            $value = $monthlyData['data'][$index] ?? 0;
                            $maxValue = max($monthlyData['data']) ?: 1;
                            $height = $maxValue > 0 ? ($value / $maxValue) * 100 : 0;
                        @endphp
                        <div class="neo-bar-item">
                            <div class="neo-bar-container" style="height: 180px;">
                                <div class="neo-bar-value">{{ $value }}</div>
                                <div class="neo-bar-fill" style="height: {{ max($height, 5) }}%"></div>
                            </div>
                            <span class="neo-bar-label">{{ $month }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Status Distribution --}}
        <div class="neo-chart-card">
            <div class="neo-chart-header">
                <div class="flex flex-col gap-1">
                    <span class="neo-page-label">// Status</span>
                    <h3 class="neo-chart-title">Distribusi Status</h3>
                </div>
            </div>
            <div class="neo-chart-body">
                <div class="neo-status-list">
                    @foreach($statusDistribution as $item)
                        @if($item['count'] > 0)
                            <div class="neo-status-item">
                                <div class="neo-status-indicator neo-status-{{ $item['color'] }}">
                                    <div class="neo-status-dot"></div>
                                </div>
                                <div class="neo-status-content">
                                    <div class="neo-status-header">
                                        <span class="neo-status-label">{{ $item['label'] }}</span>
                                        <span class="neo-status-count">{{ $item['count'] }}</span>
                                    </div>
                                    <div class="neo-status-bar">
                                        <div class="neo-status-fill neo-status-fill-{{ $item['color'] }}" style="width: {{ $item['percentage'] }}%"></div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- Content Row --}}
    <div class="neo-content-grid">
        {{-- Popular Services --}}
        <div class="neo-card">
            <div class="neo-card-header">
                <div class="flex flex-col gap-1">
                    <span class="neo-page-label">// Popular</span>
                    <h3 class="neo-card-title">Layanan Terpopuler</h3>
                </div>
                <a href="{{ route('admin.services.index') }}" class="neo-btn-secondary neo-btn-sm">
                    Lihat semua
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
            <div class="neo-card-body">
                @if(count($popularServices))
                    <div class="space-y-3">
                        @foreach($popularServices as $index => $service)
                            <a href="{{ route('admin.services.edit', $service['id']) }}" class="flex items-center gap-4 p-3 rounded-lg transition-all" style="background: var(--paper); border: 1px solid var(--line);">
                                <div class="w-8 h-8 flex items-center justify-center rounded-lg font-mono font-bold" style="background: var(--gold); color: var(--night);">
                                    {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-medium truncate" style="color: var(--ink);">{{ $service['name'] }}</p>
                                    <p class="text-xs" style="color: var(--ink-soft);">{{ $service['count'] }} pengajuan</p>
                                </div>
                                <svg class="w-5 h-5" style="color: var(--ink-soft);" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="neo-empty">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                        </svg>
                        <p class="neo-empty-title">Belum ada data</p>
                        <p class="neo-empty-text">Tidak ada layanan yang tersedia</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Pending Requests --}}
        <div class="neo-card">
            <div class="neo-card-header">
                <div class="flex flex-col gap-1">
                    <span class="neo-page-label">// Action Required</span>
                    <h3 class="neo-card-title">Perlu Tindakan</h3>
                </div>
                <a href="{{ route('admin.requests.index') }}?status=pending" class="neo-btn-secondary neo-btn-sm">
                    Lihat semua
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
            <div class="neo-card-body p-0">
                @if(count($pendingRequests))
                    <div class="neo-table-wrapper">
                        <table class="neo-table">
                            <thead>
                                <tr>
                                    <th>No. Referensi</th>
                                    <th>Layanan</th>
                                    <th>Pemohon</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingRequests as $request)
                                    <tr>
                                        <td class="font-mono text-sm">{{ $request['no_req'] }}</td>
                                        <td class="font-medium">{{ $request['title'] }}</td>
                                        <td>{{ $request['user'] }}</td>
                                        <td>
                                            <span class="neo-badge {{ $request['status'] === 'UNCHECK' ? 'neo-badge-warning' : 'neo-badge-info' }}">
                                                {{ $request['status'] === 'UNCHECK' ? 'Belum Dicek' : 'Pending' }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.requests.show', $request['id']) }}" class="neo-btn-table">
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
                @else
                    <div class="neo-empty neo-empty-success">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="neo-empty-title">Semua bersih!</p>
                        <p class="neo-empty-text">Tidak ada pengajuan yang perlu ditindaklanjuti saat ini.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Quick Actions - NEO MIRAI (Admin Only) --}}
    @if($isAdmin)
    <div class="neo-quick-actions">
        <div class="neo-section-header">
            <div class="neo-section-icon">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
            <h2 class="neo-section-title">Aksi Cepat</h2>
        </div>
        <div class="neo-quick-actions-grid">
            <a href="{{ route('admin.users.create') }}" class="neo-quick-action">
                <div class="neo-quick-action-icon neo-quick-action-cyan">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                </div>
                <div class="neo-quick-action-content">
                    <span class="neo-quick-action-title">Tambah User Baru</span>
                    <span class="neo-quick-action-desc">Buat akun pengguna baru</span>
                </div>
                <div class="neo-quick-action-arrow">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
            </a>

            <a href="{{ route('admin.services.create') }}" class="neo-quick-action">
                <div class="neo-quick-action-icon neo-quick-action-emerald">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                    </svg>
                </div>
                <div class="neo-quick-action-content">
                    <span class="neo-quick-action-title">Buat Layanan Baru</span>
                    <span class="neo-quick-action-desc">Tambahkan layanan baru</span>
                </div>
                <div class="neo-quick-action-arrow">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
            </a>

            <a href="{{ route('admin.requests.index') }}" class="neo-quick-action">
                <div class="neo-quick-action-icon neo-quick-action-amber">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                    </svg>
                </div>
                <div class="neo-quick-action-content">
                    <span class="neo-quick-action-title">Verifikasi Pengajuan</span>
                    <span class="neo-quick-action-desc">Periksa pengajuan baru</span>
                </div>
                <div class="neo-quick-action-arrow">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
            </a>

            <a href="{{ route('admin.units.index') }}" class="neo-quick-action">
                <div class="neo-quick-action-icon neo-quick-action-violet">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div class="neo-quick-action-content">
                    <span class="neo-quick-action-title">Kelola Unit Kerja</span>
                    <span class="neo-quick-action-desc">Atur unit dan department</span>
                </div>
                <div class="neo-quick-action-arrow">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
            </a>
        </div>
    </div>
    @endif
</x-admin.layouts.app>
