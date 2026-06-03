<x-admin.layouts.app>
    <div class="admin-page-header">
        <div>
            <h1 class="admin-page-title">Manajemen Unit Kerja</h1>
            <p class="admin-page-subtitle">Kelola data unit kerja dan departemen</p>
        </div>
        <div class="admin-page-actions">
            <a href="{{ route('admin.units.create') }}" class="btn btn-primary">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Unit
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="empty-state py-16">
                <svg class="empty-state-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                <p class="empty-state-title">Halaman dalam pengembangan</p>
                <p class="empty-state-description">Fitur manajemen unit kerja sedang dalam tahap pengembangan.</p>
                <div class="empty-state-action">
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Kembali ke Dashboard</a>
                </div>
            </div>
        </div>
    </div>
</x-admin.layouts.app>