<x-admin.layouts.app>
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <span class="page-label">// App Updates</span>
            <h1 class="page-title">Patch Management</h1>
            <p class="page-subtitle">Kelola file patch untuk update aplikasi mobile SILATAR V2</p>
        </div>
        @if($isAdmin)
        <div class="page-actions">
            <a href="{{ route('admin.patches.create') }}" class="btn btn-primary">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Upload Patch
            </a>
        </div>
        @endif
    </div>

    <!-- Alerts -->
    @if(session('success'))
        <div class="alert alert-success mb-6">
            <svg class="alert-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="alert-message">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger mb-6">
            <svg class="alert-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <span class="alert-message">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Stats -->
    <div class="grid-4 mb-6">
        <div class="stat-card">
            <div class="stat-icon emerald">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                </svg>
            </div>
            <div class="stat-content">
                <span class="stat-label">Total Patch</span>
                <span class="stat-value">{{ $patches->total() }}</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon cyan">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="stat-content">
                <span class="stat-label">Patch Aktif</span>
                <span class="stat-value">{{ $patches->where('is_active', true)->count() }}</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon amber">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <div class="stat-content">
                <span class="stat-label">Mandatory</span>
                <span class="stat-value">{{ $patches->where('is_mandatory', true)->count() }}</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon violet">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 11.5V14m0-2.5v-6a1.5 1.5 0 113 0m-3 6a1.5 1.5 0 00-3 0v2a7.5 7.5 0 0015 0v-5a1.5 1.5 0 00-3 0m-6-3V11m0-5.5v-1a1.5 1.5 0 013 0v1m0 0V11m0-5.5a1.5 1.5 0 013 0v3m0 0V11"/>
                </svg>
            </div>
            <div class="stat-content">
                <span class="stat-label">Versi Terbaru</span>
                <span class="stat-value">{{ $latestPatch?->version ?? '-' }}</span>
            </div>
        </div>
    </div>

    <!-- Search Card -->
    <div class="card mb-6">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.patches.index') }}" class="flex items-center gap-4">
                <div class="relative flex-1">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari versi atau changelog..." class="form-input pl-10">
                </div>
                <button type="submit" class="btn btn-primary">Cari</button>
                @if(request('search'))
                    <a href="{{ route('admin.patches.index') }}" class="btn btn-secondary">Reset</a>
                @endif
            </form>
        </div>
    </div>

    <!-- Patches Table -->
    <div class="card">
        <div class="table-header">
            <div class="table-title-icon">
                <div class="icon" style="background: rgba(6,182,212,0.1);">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                </div>
                <div>
                    <h3 class="table-title">Daftar Patch</h3>
                    <p class="table-subtitle">Total {{ $patches->total() }} patch</p>
                </div>
            </div>
        </div>

        <div class="table-wrapper">
            <table class="table">
                <thead>
                    <tr>
                        <th>Versi</th>
                        <th>Version Code</th>
                        <th>Patch / Build#</th>
                        <th>File</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($patches as $patch)
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-primary-100 text-primary-700 flex items-center justify-center font-bold text-sm">
                                        {{ strtoupper($patch->update_type === 'apk' ? 'APK' : 'PAT') }}
                                    </div>
                                    <div>
                                        <span class="font-medium text-ink">{{ $patch->version }}</span>
                                        @if($patch->update_type === 'patch')
                                            <span class="text-xs text-muted block">Patch #{{ $patch->patch_count }}</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="font-mono text-sm">vc={{ $patch->version_code }}</span>
                                @if($patch->update_type === 'apk' && $patch->build_number)
                                    <span class="text-xs text-muted block">build={{ $patch->build_number }}</span>
                                @endif
                            </td>
                            <td>
                                @if($patch->update_type === 'patch')
                                    <span class="badge badge-primary">
                                        #{{ $patch->patch_count }}
                                    </span>
                                @else
                                    <span class="badge badge-info">
                                        Build #{{ $patch->build_number ?? $patch->version_code }}
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="text-xs text-muted">
                                    {{ $patch->size_hint ?? number_format($patch->file_size / 1024, 1) . ' KB' }}
                                </div>
                                @if($patch->file_name)
                                    <span class="text-xs text-muted truncate block max-w-[150px]">
                                        {{ $patch->file_name }}
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="flex flex-col gap-1">
                                    @if($patch->is_mandatory)
                                        <span class="badge badge-danger">Mandatory</span>
                                    @endif
                                    <span class="badge {{ $patch->is_active ? 'badge-success' : 'badge-secondary' }}">
                                        {{ $patch->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </div>
                            </td>
                            <td>
                                <span class="text-sm text-muted">{{ $patch->created_at->format('d M Y') }}</span>
                                <span class="text-xs text-muted block">{{ $patch->created_at->format('H:i') }}</span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('admin.patches.show', $patch->id) }}" class="action-btn" title="Detail">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    <a href="{{ route('admin.patches.edit', $patch->id) }}" class="action-btn" title="Edit">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h5.586a1 1 0 00.707-.293l5.414-5.414a1 1 0 000-1.414l-5.414-5.414A1 1 0 0011.828 6H16"/>
                                        </svg>
                                    </a>
                                    <a href="{{ route('admin.patches.download', $patch->id) }}" class="action-btn" title="Download">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.patches.toggle', $patch->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="action-btn {{ $patch->is_active ? 'warning' : 'success' }}" title="{{ $patch->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                            @if($patch->is_active)
                                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                                </svg>
                                            @else
                                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                            @endif
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.patches.destroy', $patch->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin hapus patch v{{ $patch->version }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn delete" title="Hapus">
                                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-12">
                                <div class="empty-state">
                                    <svg class="w-16 h-16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                    </svg>
                                    <p class="empty-state-title">Belum ada patch</p>
                                    <p class="empty-state-text">Upload patch baru untuk memulai</p>
                                    @if($isAdmin)
                                        <a href="{{ route('admin.patches.create') }}" class="btn btn-primary mt-4">
                                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                                            Upload Patch
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($patches->hasPages())
            <div class="px-6 py-4 border-t flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-sm text-muted">Menampilkan {{ $patches->firstItem() ?? 0 }} - {{ $patches->lastItem() ?? 0 }} dari {{ $patches->total() }} data</p>
                <div class="pagination">
                    @if($patches->onFirstPage())
                        <span class="disabled">Sebelumnya</span>
                    @else
                        <a href="{{ $patches->previousPageUrl() }}">Sebelumnya</a>
                    @endif
                    @foreach($patches->getUrlRange(1, $patches->lastPage()) as $page => $url)
                        @if($page <= 3 || $page > $patches->lastPage() - 2 || abs($page - $patches->currentPage()) < 2)
                            @if($page == $patches->currentPage())
                                <span class="active">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}">{{ $page }}</a>
                            @endif
                        @elseif($loop->index == 2 || $loop->index == $patches->lastPage() - 3)
                            <span class="disabled">...</span>
                        @endif
                    @endforeach
                    @if($patches->hasMorePages())
                        <a href="{{ $patches->nextPageUrl() }}">Selanjutnya</a>
                    @else
                        <span class="disabled">Selanjutnya</span>
                    @endif
                </div>
            </div>
        @endif
    </div>
</x-admin.layouts.app>
