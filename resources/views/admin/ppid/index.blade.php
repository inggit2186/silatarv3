<x-admin.layouts.app title="{{ $title }}">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <span class="page-label">PPID Management</span>
            <h1 class="page-title">Kelola Halaman PPID</h1>
            <p class="page-subtitle">Edit konten semua halaman PPID dari admin panel</p>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger mb-6">
            {{ session('error') }}
        </div>
    @endif

    <!-- Stats -->
    <div class="grid-4 mb-6">
        <div class="stat-card">
            <div class="stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    <polyline points="14 2 14 8 20 8"/>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $pages->count() }}</div>
                <div class="stat-label">Total Halaman</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                    <polyline points="22 4 12 14.01 9 11.01"/>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $pages->where('is_active', true)->count() }}</div>
                <div class="stat-label">Aktif</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $pages->where('is_active', false)->count() }}</div>
                <div class="stat-label">Nonaktif</div>
            </div>
        </div>
    </div>

    <!-- Pages Table -->
    <div class="card">
        <div class="table-header">
            <div class="table-title">
                <h3>Daftar Halaman PPID</h3>
                <p class="table-subtitle">Klik edit untuk mengubah konten halaman</p>
            </div>
        </div>
        <div class="table-wrapper">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 5%">No</th>
                        <th style="width: 25%">Slug</th>
                        <th style="width: 35%">Judul</th>
                        <th style="width: 15%">Status</th>
                        <th style="width: 20%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pages as $index => $page)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <code style="background: #f3f4f6; padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-size: 0.85rem;">
                                    {{ $page->slug }}
                                </code>
                            </td>
                            <td>{{ $page->title }}</td>
                            <td>
                                @if($page->is_active)
                                    <span class="badge badge-success">Aktif</span>
                                @else
                                    <span class="badge badge-warning">Nonaktif</span>
                                @endif
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('admin.ppid.edit', $page->slug) }}" class="action-btn" title="Edit">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                        </svg>
                                    </a>
                                    <a href="{{ route('admin.ppid.gallery', $page->slug) }}" class="action-btn" title="Gallery">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                                            <circle cx="8.5" cy="8.5" r="1.5"/>
                                            <polyline points="21 15 16 10 5 21"/>
                                        </svg>
                                    </a>
                                    @php
                                        $publicRoute = $page->slug === 'index' ? 'ppid' : 'ppid.' . $page->slug;
                                    @endphp
                                    <a href="{{ route($publicRoute) }}" class="action-btn" title="Lihat di Website" target="_blank">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>
                                            <polyline points="15 3 21 3 21 9"/>
                                            <line x1="10" y1="14" x2="21" y2="3"/>
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="empty-state">
                                <div class="empty-state-title">Belum ada halaman PPID</div>
                                <div class="empty-state-text">Jalankan database seed untuk membuat halaman PPID.</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-admin.layouts.app>
