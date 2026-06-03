<x-admin.layouts.app>
    <div class="admin-page-header">
        <div>
            <h1 class="admin-page-title">Manajemen Pengajuan</h1>
            <p class="admin-page-subtitle">Verifikasi dan kelola pengajuan layanan</p>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="empty-state py-16">
                <svg class="empty-state-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="empty-state-title">Halaman dalam pengembangan</p>
                <p class="empty-state-description">Fitur manajemen pengajuan sedang dalam tahap pengembangan.</p>
                <div class="empty-state-action">
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Kembali ke Dashboard</a>
                </div>
            </div>
        </div>
    </div>
</x-admin.layouts.app>