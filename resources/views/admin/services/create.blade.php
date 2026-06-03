<x-admin.layouts.app>
    <div class="admin-page-header">
        <div>
            <h1 class="admin-page-title">Tambah User Baru</h1>
            <p class="admin-page-subtitle">Tambahkan pengguna baru ke sistem</p>
        </div>
    </div>

    <div class="card max-w-2xl">
        <div class="card-body">
            <div class="empty-state py-8">
                <p class="empty-state-title">Halaman dalam pengembangan</p>
                <p class="empty-state-description">Form tambah user akan segera tersedia.</p>
                <div class="empty-state-action">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Kembali</a>
                </div>
            </div>
        </div>
    </div>
</x-admin.layouts.app>