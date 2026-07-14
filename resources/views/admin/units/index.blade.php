<x-admin.layouts.app>
    <div class="page-header">
        <div class="page-header-content">
            <span class="page-label">// Unit Kerja</span>
            <h1 class="page-title">Manajemen Unit Kerja</h1>
            <p class="page-subtitle">Kelola data unit kerja dan departemen</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.units.create') }}" class="btn btn-primary">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Unit
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="empty-state">
                <svg class="w-16 h-16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                <p class="empty-state-title">Halaman dalam pengembangan</p>
                <p class="empty-state-text">Fitur manajemen unit kerja sedang dalam tahap pengembangan.</p>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary mt-4">Kembali ke Dashboard</a>
            </div>
        </div>
    </div>
</x-admin.layouts.app>
