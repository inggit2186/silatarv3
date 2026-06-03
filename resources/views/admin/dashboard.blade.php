<x-admin.layouts.app>
    {{-- Page Header --}}
    <div class="admin-page-header">
        <div>
            <h1 class="admin-page-title">Dashboard</h1>
            <p class="admin-page-subtitle">Selamat datang di panel administrasi SILATAR</p>
        </div>
        <div class="admin-page-actions">
            <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Laporan
            </a>
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah User
            </a>
        </div>
    </div>

    {{-- Statistics Cards --}}
    <div class="mb-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
        {{-- Total Users --}}
        <x-admin.components.stat-card
            :icon="'<svg class=\'h-6 w-6\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'currentColor\' stroke-width=\'1.8\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z\'/></svg>'"
            :value="$stats['total_users']"
            label="Total Pengguna"
            :trend="$stats['new_users_this_month'] > 0 ? 'up' : 'neutral'"
            :trendValue="$stats['new_users_this_month'] . ' baru'"
            color="cyan"
            :href="route('admin.users.index')"
        />

        {{-- Total Requests --}}
        <x-admin.components.stat-card
            :icon="'<svg class=\'h-6 w-6\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'currentColor\' stroke-width=\'1.8\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z\'/></svg>'"
            :value="$stats['total_requests']"
            label="Total Pengajuan"
            :trend="$stats['processed_this_month'] > 0 ? 'up' : 'neutral'"
            :trendValue="$stats['processed_this_month'] . ' diproses'"
            color="emerald"
            :href="route('admin.requests.index')"
        />

        {{-- Pending Requests --}}
        <x-admin.components.stat-card
            :icon="'<svg class=\'h-6 w-6\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'currentColor\' stroke-width=\'1.8\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z\'/></svg>'"
            :value="$stats['pending_requests']"
            label="Menunggu Tindakan"
            color="amber"
        />

        {{-- Total Services --}}
        <x-admin.components.stat-card
            :icon="'<svg class=\'h-6 w-6\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'currentColor\' stroke-width=\'1.8\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z\'/></svg>'"
            :value="$stats['total_services']"
            label="Layanan Aktif"
            color="violet"
            :href="route('admin.services.index')"
        />
    </div>

    {{-- Charts Row --}}
    <div class="mb-8 grid gap-6 lg:grid-cols-3">
        {{-- Monthly Requests Chart --}}
        <div class="card lg:col-span-2">
            <div class="card-header">
                <h3 class="card-title">Pengajuan Per Bulan</h3>
                <span class="badge badge-slate">{{ now()->format('Y') }}</span>
            </div>
            <div class="card-body">
                <div class="chart-container flex items-end justify-around gap-3" style="height: 280px;">
                    @foreach($monthlyData['labels'] as $index => $month)
                        @php
                            $value = $monthlyData['data'][$index] ?? 0;
                            $maxValue = max($monthlyData['data']) ?: 1;
                            $height = $maxValue > 0 ? ($value / $maxValue) * 100 : 0;
                        @endphp
                        <div class="flex flex-1 flex-col items-center gap-2">
                            <div class="w-full max-w-[60px] rounded-t-xl bg-gradient-to-t from-cyan-600 to-cyan-400 transition-all duration-500"
                                 style="height: {{ max($height, 5) }}%"
                                 title="{{ $value }} pengajuan">
                            </div>
                            <span class="text-xs font-medium text-slate-500">{{ $month }}</span>
                            <span class="text-xs font-bold text-slate-700">{{ $value }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Status Distribution --}}
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Distribusi Status</h3>
            </div>
            <div class="card-body">
                <div class="space-y-4">
                    @foreach($statusDistribution as $item)
                        @if($item['count'] > 0)
                            <div class="flex items-center gap-3">
                                <div class="h-3 w-3 flex-shrink-0 rounded-full bg-{{ $item['color'] }}-500"></div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm font-medium text-slate-700">{{ $item['label'] }}</span>
                                        <span class="text-sm font-semibold text-slate-900">{{ $item['count'] }}</span>
                                    </div>
                                    <div class="progress mt-1.5">
                                        <div class="progress-bar bg-{{ $item['color'] }}-500" style="width: {{ $item['percentage'] }}%"></div>
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
    <div class="grid gap-6 lg:grid-cols-3">
        {{-- Popular Services --}}
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Layanan Terpopuler</h3>
                <a href="{{ route('admin.services.index') }}" class="text-xs font-medium text-cyan-600 hover:text-cyan-700">Lihat semua</a>
            </div>
            <div class="card-body">
                @if(count($popularServices))
                    <div class="space-y-4">
                        @foreach($popularServices as $index => $service)
                            <div class="flex items-center gap-3">
                                <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-cyan-100 text-xs font-bold text-cyan-700">
                                    {{ $index + 1 }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="truncate text-sm font-medium text-slate-700">{{ $service['name'] }}</p>
                                    <p class="text-xs text-slate-500">{{ $service['count'] }} pengajuan</p>
                                </div>
                                <a href="{{ route('admin.services.edit', $service['id']) }}" class="rounded-lg p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-600">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state py-8">
                        <svg class="empty-state-icon mx-auto text-slate-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                        </svg>
                        <p class="text-sm text-slate-500">Belum ada data layanan</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Pending Requests --}}
        <div class="card lg:col-span-2">
            <div class="card-header">
                <h3 class="card-title">
                    <span class="inline-flex items-center gap-2">
                        <span class="relative flex h-3 w-3">
                            <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-amber-400 opacity-75"></span>
                            <span class="relative inline-flex h-3 w-3 rounded-full bg-amber-500"></span>
                        </span>
                        Perlu Tindakan
                    </span>
                </h3>
                <a href="{{ route('admin.requests.index') }}?status=pending" class="text-xs font-medium text-cyan-600 hover:text-cyan-700">Lihat semua</a>
            </div>
            <div class="card-body p-0">
                @if(count($pendingRequests))
                    <div class="overflow-x-auto">
                        <table class="data-table">
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
                                            <span class="font-mono text-xs font-semibold text-cyan-700">{{ $request['no_req'] }}</span>
                                        </td>
                                        <td class="font-medium">{{ $request['title'] }}</td>
                                        <td>{{ $request['user'] }}</td>
                                        <td>
                                            <span class="badge badge-{{ $request['status'] === 'UNCHECK' ? 'amber' : 'blue' }}">
                                                {{ $request['status'] === 'UNCHECK' ? 'Belum Dicek' : 'Pending' }}
                                            </span>
                                        </td>
                                        <td class="text-slate-500">{{ $request['time_ago'] }}</td>
                                        <td>
                                            <a href="{{ route('admin.requests.show', $request['id']) }}" class="btn btn-sm btn-secondary">
                                                Detail
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="empty-state py-12">
                        <svg class="empty-state-icon mx-auto text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="empty-state-title">Semua bersih!</p>
                        <p class="empty-state-description">Tidak ada pengajuan yang perlu ditindaklanjuti saat ini.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="mt-8">
        <h2 class="mb-4 text-lg font-semibold text-slate-900">Aksi Cepat</h2>
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <a href="{{ route('admin.users.create') }}" class="quick-action">
                <div class="quick-action-icon">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                </div>
                <span class="quick-action-label">Tambah User Baru</span>
            </a>

            <a href="{{ route('admin.services.create') }}" class="quick-action">
                <div class="quick-action-icon">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                    </svg>
                </div>
                <span class="quick-action-label">Buat Layanan Baru</span>
            </a>

            <a href="{{ route('admin.requests.index') }}" class="quick-action">
                <div class="quick-action-icon">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                    </svg>
                </div>
                <span class="quick-action-label">Verifikasi Pengajuan</span>
            </a>

            <a href="{{ route('admin.units.index') }}" class="quick-action">
                <div class="quick-action-icon">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <span class="quick-action-label">Kelola Unit Kerja</span>
            </a>
        </div>
    </div>
</x-admin.layouts.app>