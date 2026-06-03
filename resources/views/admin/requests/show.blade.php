<x-admin.layouts.app>
    <div class="admin-page-header">
        <div>
            <h1 class="admin-page-title">Detail Pengajuan</h1>
            <p class="admin-page-subtitle">Lihat detail pengajuan layanan</p>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="empty-state py-8">
                <p class="empty-state-title">Halaman dalam pengembangan</p>
                <p class="empty-state-description">Detail pengajuan akan segera tersedia.</p>
                <div class="empty-state-action">
                    <a href="{{ route('admin.requests.index') }}" class="btn btn-secondary">Kembali</a>
                </div>
            </div>
        </div>
    </div>
</x-admin.layouts.app>