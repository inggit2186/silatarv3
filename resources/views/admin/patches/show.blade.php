<x-admin.layouts.app>
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <span class="page-label">// App Updates</span>
            <h1 class="page-title">Detail Patch v{{ $patch->version }}</h1>
            <p class="page-subtitle">Informasi lengkap patch {{ $patch->version }} ({{ $patch->version_code }})</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.patches.index') }}" class="btn btn-secondary">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali
            </a>
        </div>
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

    <div class="grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Version Info -->
            <div class="card">
                <div class="card-header">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-xl bg-primary-100 text-primary-700 flex items-center justify-center font-bold text-lg">
                            v{{ $patch->version_code }}
                        </div>
                        <div>
                            <h3 class="card-title text-lg">{{ $patch->version }}</h3>
                            <div class="flex items-center gap-2 mt-1">
                                @if($patch->is_mandatory)
                                    <span class="badge badge-danger">Mandatory</span>
                                @endif
                                <span class="badge {{ $patch->is_active ? 'badge-success' : 'badge-secondary' }}">
                                    {{ $patch->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div>
                            <p class="text-xs text-muted uppercase tracking-wider mb-1">Versi</p>
                            <p class="font-medium text-ink">{{ $patch->version }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-muted uppercase tracking-wider mb-1">Version Code</p>
                            <p class="font-medium text-ink">{{ $patch->version_code }}</p>
                        </div>
                        @if($patch->update_type === 'apk')
                        <div>
                            <p class="text-xs text-muted uppercase tracking-wider mb-1">Build Number</p>
                            <p class="font-medium text-ink">{{ $patch->build_number ?? '-' }}</p>
                        </div>
                        @else
                        <div>
                            <p class="text-xs text-muted uppercase tracking-wider mb-1">Patch Number</p>
                            <p class="font-medium text-ink">#{{ $patch->patch_count }}</p>
                        </div>
                        @endif
                        <div>
                            <p class="text-xs text-muted uppercase tracking-wider mb-1">Tipe</p>
                            @if($patch->update_type === 'patch')
                                <span class="badge badge-primary">Patch</span>
                            @else
                                <span class="badge badge-info">APK</span>
                            @endif
                        </div>
                        <div>
                            <p class="text-xs text-muted uppercase tracking-wider mb-1">Status</p>
                            <span class="badge {{ $patch->is_active ? 'badge-success' : 'badge-secondary' }}">
                                {{ $patch->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </div>
                        <div>
                            <p class="text-xs text-muted uppercase tracking-wider mb-1">Mandatory</p>
                            @if($patch->is_mandatory)
                                <span class="badge badge-danger">Ya</span>
                            @else
                                <span class="text-sm text-muted">Tidak</span>
                            @endif
                        </div>
                        <div>
                            <p class="text-xs text-muted uppercase tracking-wider mb-1">File Size</p>
                            <p class="font-medium text-ink">{{ $patch->size_hint ?? number_format($patch->file_size / 1024, 1) . ' KB' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-muted uppercase tracking-wider mb-1">Min Version</p>
                            <p class="font-medium text-ink">{{ $patch->min_app_version ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-muted uppercase tracking-wider mb-1">Max Version</p>
                            <p class="font-medium text-ink">{{ $patch->max_app_version ?? '-' }}</p>
                        </div>
                        <div class="col-span-2 md:col-span-4">
                            <p class="text-xs text-muted uppercase tracking-wider mb-1">MD5</p>
                            <p class="font-mono text-xs text-ink bg-secondary px-2 py-1 rounded">{{ $patch->md5 ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Changelog -->
            @if($patch->changelog)
            <div class="card">
                <div class="card-header">
                    <div class="flex items-center gap-3">
                        <div class="stat-icon cyan">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="card-title">Changelog</h3>
                            <p class="text-sm text-muted">Daftar perubahan di versi ini</p>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <pre class="whitespace-pre-wrap text-sm text-ink font-sans">{{ $patch->changelog }}</pre>
                </div>
            </div>
            @endif

            <!-- File Info -->
            <div class="card">
                <div class="card-header">
                    <div class="flex items-center gap-3">
                        <div class="stat-icon violet">
                            @if($patch->update_type === 'apk')
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                </svg>
                            @else
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            @endif
                        </div>
                        <div>
                            <h3 class="card-title">
                                @if($patch->update_type === 'apk')
                                    File APK
                                @else
                                    File Patch
                                @endif
                            </h3>
                            <p class="text-sm text-muted">Informasi file</p>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="flex items-center justify-between p-4 bg-secondary rounded-lg">
                        <div class="flex items-center gap-3">
                            <svg class="w-8 h-8 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                            <div>
                                <p class="font-medium text-ink">{{ $patch->file_name }}</p>
                                <p class="text-sm text-muted">{{ $patch->size_hint ?? number_format($patch->file_size / 1024, 1) . ' KB' }}</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.patches.download', $patch->id) }}" class="btn btn-primary">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            Download
                        </a>
                    </div>

                    @if($patch->update_type === 'apk' && $patch->apk_url)
                        <div class="mt-4 p-3 bg-info-bg rounded-lg border border-info/20">
                            <p class="text-xs text-muted mb-1">APK URL:</p>
                            <code class="text-xs break-all">{{ $patch->apk_url }}</code>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Timestamps -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Info Tambahan</h3>
                </div>
                <div class="card-body space-y-4">
                    <div>
                        <p class="text-xs text-muted uppercase tracking-wider mb-1">Dibuat</p>
                        <p class="text-sm text-ink">{{ $patch->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-muted uppercase tracking-wider mb-1">Terakhir Diupdate</p>
                        <p class="text-sm text-ink">{{ $patch->updated_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Aksi Cepat</h3>
                </div>
                <div class="card-body space-y-3">
                    <a href="{{ route('admin.patches.edit', $patch->id) }}" class="btn btn-primary w-full">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h5.586a1 1 0 00.707-.293l5.414-5.414a1 1 0 000-1.414l-5.414-5.414A1 1 0 0011.828 6H16"/>
                        </svg>
                        Edit Patch
                    </a>

                    <form action="{{ route('admin.patches.toggle', $patch->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn {{ $patch->is_active ? 'btn-warning w-full' : 'btn-success w-full' }}">
                            @if($patch->is_active)
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                </svg>
                                Nonaktifkan
                            @else
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Aktifkan
                            @endif
                        </button>
                    </form>

                    <form action="{{ route('admin.patches.destroy', $patch->id) }}" method="POST" onsubmit="return confirm('Yakin ingin hapus patch v{{ $patch->version }}? Tindakan ini tidak dapat dibatalkan.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-full">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Hapus Patch
                        </button>
                    </form>
                </div>
            </div>

            <!-- API Info -->
            <div class="card bg-secondary">
                <div class="card-header">
                    <h3 class="card-title">API Endpoint</h3>
                </div>
                <div class="card-body">
                    <p class="text-xs text-muted mb-2">Check Update:</p>
                    <code class="text-xs bg-card px-2 py-1 rounded block mb-3 break-all">
                        {{ url('/api/patch/check') }}
                    </code>
                    <p class="text-xs text-muted mb-2">Download:</p>
                    <code class="text-xs bg-card px-2 py-1 rounded block break-all">
                        {{ url('/api/patch/download/' . $patch->id) }}
                    </code>
                </div>
            </div>
        </div>
    </div>
</x-admin.layouts.app>
