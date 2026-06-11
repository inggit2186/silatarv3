<x-admin.layouts.app>
    {{-- Page Header - Cyberpunk Style --}}
    <div class="cyber-dashboard-header">
        <div class="cyber-header-glow"></div>
        <div class="cyber-header-content">
            <div class="cyber-header-text">
                <div class="cyber-step-label">// CONTROL CENTER v2.0</div>
                <h1 class="cyber-title-lg">Dashboard</h1>
                <p class="cyber-text-subtle">Selamat datang di panel administrasi SILATAR</p>
            </div>
            <div class="cyber-header-actions">
                <a href="{{ route('admin.reports.index') }}" class="cyber-btn-secondary cyber-btn-secondary-sm">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Laporan
                </a>
                <a href="{{ route('admin.users.create') }}" class="cyber-btn">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah User
                </a>
            </div>
        </div>
    </div>

    {{-- Stats Grid - Cyberpunk Cards --}}
    <div class="cyber-dashboard-stats">
        {{-- Total Users --}}
        <a href="{{ route('admin.users.index') }}" class="cyber-stat-card cyber-stat-card-cyan">
            <div class="cyber-stat-card-glow"></div>
            <div class="cyber-stat-icon-wrapper">
                <svg class="cyber-stat-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <div class="cyber-stat-content">
                <div class="cyber-stat-label">TOTAL PENGGUNA</div>
                <div class="cyber-stat-value">{{ $stats['total_users'] }}</div>
                <div class="cyber-stat-trend">
                    <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                    </svg>
                    {{ $stats['new_users_this_month'] }} baru bulan ini
                </div>
            </div>
            <div class="cyber-stat-decoration">
                <div class="cyber-stat-line"></div>
            </div>
        </a>

        {{-- Total Requests --}}
        <a href="{{ route('admin.requests.index') }}" class="cyber-stat-card cyber-stat-card-emerald">
            <div class="cyber-stat-card-glow"></div>
            <div class="cyber-stat-icon-wrapper">
                <svg class="cyber-stat-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div class="cyber-stat-content">
                <div class="cyber-stat-label">TOTAL PENGAJUAN</div>
                <div class="cyber-stat-value">{{ $stats['total_requests'] }}</div>
                <div class="cyber-stat-trend">
                    <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                    </svg>
                    {{ $stats['processed_this_month'] }} diproses
                </div>
            </div>
            <div class="cyber-stat-decoration">
                <div class="cyber-stat-line"></div>
            </div>
        </a>

        {{-- Pending Requests --}}
        <div class="cyber-stat-card cyber-stat-card-amber">
            <div class="cyber-stat-card-glow"></div>
            <div class="cyber-stat-icon-wrapper">
                <svg class="cyber-stat-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="cyber-stat-content">
                <div class="cyber-stat-label">MENUNGGU TINDAKAN</div>
                <div class="cyber-stat-value">{{ $stats['pending_requests'] }}</div>
                <div class="cyber-stat-trend cyber-stat-trend-warning">
                    <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    Perlu perhatian
                </div>
            </div>
            <div class="cyber-stat-decoration">
                <div class="cyber-stat-line"></div>
            </div>
        </div>

        {{-- Total Services --}}
        <a href="{{ route('admin.services.index') }}" class="cyber-stat-card cyber-stat-card-violet">
            <div class="cyber-stat-card-glow"></div>
            <div class="cyber-stat-icon-wrapper">
                <svg class="cyber-stat-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                </svg>
            </div>
            <div class="cyber-stat-content">
                <div class="cyber-stat-label">LAYANAN AKTIF</div>
                <div class="cyber-stat-value">{{ $stats['total_services'] }}</div>
                <div class="cyber-stat-trend">
                    <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                    </svg>
                    Sistem aktif
                </div>
            </div>
            <div class="cyber-stat-decoration">
                <div class="cyber-stat-line"></div>
            </div>
        </a>
    </div>

    {{-- Charts Row --}}
    <div class="cyber-dashboard-charts">
        {{-- Monthly Requests Chart --}}
        <div class="cyber-chart-card cyber-chart-card-main">
            <div class="cyber-chart-header">
                <div class="cyber-chart-title-group">
                    <div class="cyber-step-label">// ANALYTICS</div>
                    <h3 class="cyber-chart-title">Pengajuan Per Bulan</h3>
                </div>
                <div class="cyber-chart-badge">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    {{ now()->format('Y') }}
                </div>
            </div>
            <div class="cyber-chart-body">
                <div class="cyber-bar-chart">
                    @foreach($monthlyData['labels'] as $index => $month)
                        @php
                            $value = $monthlyData['data'][$index] ?? 0;
                            $maxValue = max($monthlyData['data']) ?: 1;
                            $height = $maxValue > 0 ? ($value / $maxValue) * 100 : 0;
                        @endphp
                        <div class="cyber-bar-item">
                            <div class="cyber-bar-container" style="height: 180px;">
                                <div class="cyber-bar-value">{{ $value }}</div>
                                <div class="cyber-bar-fill" style="height: {{ max($height, 5) }}%"></div>
                            </div>
                            <span class="cyber-bar-label">{{ $month }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Status Distribution --}}
        <div class="cyber-chart-card">
            <div class="cyber-chart-header">
                <div class="cyber-chart-title-group">
                    <div class="cyber-step-label">// STATUS</div>
                    <h3 class="cyber-chart-title">Distribusi Status</h3>
                </div>
            </div>
            <div class="cyber-chart-body">
                <div class="cyber-status-list">
                    @foreach($statusDistribution as $item)
                        @if($item['count'] > 0)
                            <div class="cyber-status-item">
                                <div class="cyber-status-indicator cyber-status-{{ $item['color'] }}">
                                    <div class="cyber-status-dot"></div>
                                </div>
                                <div class="cyber-status-content">
                                    <div class="cyber-status-header">
                                        <span class="cyber-status-label">{{ $item['label'] }}</span>
                                        <span class="cyber-status-count">{{ $item['count'] }}</span>
                                    </div>
                                    <div class="cyber-status-bar">
                                        <div class="cyber-status-fill cyber-status-fill-{{ $item['color'] }}" style="width: {{ $item['percentage'] }}%"></div>
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
    <div class="cyber-dashboard-content">
        {{-- Popular Services --}}
        <div class="cyber-content-card">
            <div class="cyber-content-header">
                <div class="cyber-content-title-group">
                    <div class="cyber-step-label">// POPULAR</div>
                    <h3 class="cyber-content-title">Layanan Terpopuler</h3>
                </div>
                <a href="{{ route('admin.services.index') }}" class="cyber-content-link">
                    Lihat semua
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
            <div class="cyber-content-body">
                @if(count($popularServices))
                    <div class="cyber-service-list">
                        @foreach($popularServices as $index => $service)
                            <a href="{{ route('admin.services.edit', $service['id']) }}" class="cyber-service-item">
                                <div class="cyber-service-rank">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</div>
                                <div class="cyber-service-info">
                                    <p class="cyber-service-name">{{ $service['name'] }}</p>
                                    <p class="cyber-service-meta">{{ $service['count'] }} pengajuan</p>
                                </div>
                                <div class="cyber-service-arrow">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="cyber-empty-state">
                        <svg class="cyber-empty-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                        </svg>
                        <p class="cyber-empty-text">Belum ada data layanan</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Pending Requests --}}
        <div class="cyber-content-card cyber-content-card-wide">
            <div class="cyber-content-header">
                <div class="cyber-content-title-group">
                    <div class="cyber-step-label">// ACTION REQUIRED</div>
                    <h3 class="cyber-content-title">
                        <span class="cyber-pulse-indicator"></span>
                        Perlu Tindakan
                    </h3>
                </div>
                <a href="{{ route('admin.requests.index') }}?status=pending" class="cyber-content-link">
                    Lihat semua
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
            <div class="cyber-content-body cyber-content-body-table">
                @if(count($pendingRequests))
                    <div class="cyber-table-wrapper">
                        <table class="cyber-table">
                            <thead>
                                <tr>
                                    <th>No. Referensi</th>
                                    <th>Layanan</th>
                                    <th>Pemohon</th>
                                    <th>Status</th>
                                    <th>Waktu</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingRequests as $request)
                                    <tr>
                                        <td>
                                            <span class="cyber-ref-code">{{ $request['no_req'] }}</span>
                                        </td>
                                        <td class="cyber-table-title">{{ $request['title'] }}</td>
                                        <td>{{ $request['user'] }}</td>
                                        <td>
                                            <span class="cyber-badge-status cyber-badge-{{ $request['status'] === 'UNCHECK' ? 'amber' : 'blue' }}">
                                                {{ $request['status'] === 'UNCHECK' ? 'Belum Dicek' : 'Pending' }}
                                            </span>
                                        </td>
                                        <td class="cyber-table-time">{{ $request['time_ago'] }}</td>
                                        <td>
                                            <a href="{{ route('admin.requests.show', $request['id']) }}" class="cyber-btn-table">
                                                Detail
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="cyber-empty-state cyber-empty-state-success">
                        <svg class="cyber-empty-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="cyber-empty-title">Semua bersih!</p>
                        <p class="cyber-empty-text">Tidak ada pengajuan yang perlu ditindaklanjuti saat ini.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Quick Actions - Cyberpunk Style --}}
    <div class="cyber-quick-actions">
        <div class="cyber-section-header">
            <div class="cyber-section-icon">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
            <h2 class="cyber-section-title">Aksi Cepat</h2>
        </div>
        <div class="cyber-quick-actions-grid">
            <a href="{{ route('admin.users.create') }}" class="cyber-quick-action">
                <div class="cyber-quick-action-icon cyber-quick-action-cyan">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                </div>
                <div class="cyber-quick-action-content">
                    <span class="cyber-quick-action-title">Tambah User Baru</span>
                    <span class="cyber-quick-action-desc">Buat akun pengguna baru</span>
                </div>
                <div class="cyber-quick-action-arrow">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
            </a>

            <a href="{{ route('admin.services.create') }}" class="cyber-quick-action">
                <div class="cyber-quick-action-icon cyber-quick-action-emerald">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                    </svg>
                </div>
                <div class="cyber-quick-action-content">
                    <span class="cyber-quick-action-title">Buat Layanan Baru</span>
                    <span class="cyber-quick-action-desc">Tambahkan layanan baru</span>
                </div>
                <div class="cyber-quick-action-arrow">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
            </a>

            <a href="{{ route('admin.requests.index') }}" class="cyber-quick-action">
                <div class="cyber-quick-action-icon cyber-quick-action-amber">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                    </svg>
                </div>
                <div class="cyber-quick-action-content">
                    <span class="cyber-quick-action-title">Verifikasi Pengajuan</span>
                    <span class="cyber-quick-action-desc">Periksa pengajuan baru</span>
                </div>
                <div class="cyber-quick-action-arrow">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
            </a>

            <a href="{{ route('admin.units.index') }}" class="cyber-quick-action">
                <div class="cyber-quick-action-icon cyber-quick-action-violet">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div class="cyber-quick-action-content">
                    <span class="cyber-quick-action-title">Kelola Unit Kerja</span>
                    <span class="cyber-quick-action-desc">Atur unit dan department</span>
                </div>
                <div class="cyber-quick-action-arrow">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
            </a>
        </div>
    </div>

    {{-- Database Utilities - Cyberpunk Style --}}
    <div class="cyber-utilities-section">
        <div class="cyber-section-header">
            <div class="cyber-section-icon cyber-section-icon-special">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 7v16c0 1.1.9 2 2 2h12a2 2 0 002-2V7M4 7l8-4 8 4M4 7l8 4 8-4"/>
                </svg>
            </div>
            <h2 class="cyber-section-title">Utilities Database</h2>
        </div>
        <div class="cyber-utility-card">
            <div class="cyber-utility-header">
                <div class="cyber-utility-icon">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 7v16c0 1.1.9 2 2 2h12a2 2 0 002-2V7M4 7l8-4 8 4M4 7l8 4 8-4"/>
                    </svg>
                </div>
                <div class="cyber-utility-info">
                    <h3 class="cyber-utility-title">Migrasi Data Laporan Kinerja</h3>
                    <p class="cyber-utility-desc">Konversi format data lama (per-baris) ke format baru (per-tanggal JSON). Data akan digabungkan per user per tanggal.</p>
                </div>
            </div>
            <div class="cyber-utility-actions">
                <form method="POST" action="{{ route('admin.utilities.migrate-satker') }}" onsubmit="return confirm('Yakin ingin menjalankan migrasi data? Pastikan sudah backup database.');">
                    @csrf
                    <button type="submit" class="cyber-btn-warning">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        Jalankan Migrasi
                    </button>
                </form>
                <form method="POST" action="{{ route('admin.utilities.migrate-satker-preview') }}">
                    @csrf
                    <button type="submit" class="cyber-btn-secondary cyber-btn-secondary-sm">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        Preview (Dry Run)
                    </button>
                </form>
            </div>
            @if(session('migration_result'))
                <div class="cyber-utility-result">
                    <h4 class="cyber-utility-result-title">Hasil Migrasi:</h4>
                    <pre class="cyber-utility-result-content">{{ session('migration_result') }}</pre>
                </div>
            @endif
        </div>
    </div>
</x-admin.layouts.app>