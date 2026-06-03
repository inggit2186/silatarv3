<x-admin.layouts.app>
    <div class="admin-page-header">
        <div>
            <h1 class="admin-page-title">Manajemen Layanan</h1>
            <p class="admin-page-subtitle">Kelola layanan dan persyaratan</p>
        </div>
        <div class="admin-page-actions">
            <a href="{{ route('admin.services.create') }}" class="btn btn-primary">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Layanan
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="empty-state py-16">
                <svg class="empty-state-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                </svg>
                <p class="empty-state-title">Halaman dalam pengembangan</p>
                <p class="empty-state-description">Fitur manajemen layanan sedang dalam tahap pengembangan.</p>
                <div class="empty-state-action">
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Kembali ke Dashboard</a>
                </div>
            </div>
        </div>
    </div>
</x-admin.layouts.app>