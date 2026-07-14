<x-admin.layouts.app>
    <div class="page-header">
        <div class="page-header-content">
            <span class="page-label">// Unit Kerja</span>
            <h1 class="page-title">Tambah Unit Kerja</h1>
            <p class="page-subtitle">Tambahkan unit kerja baru</p>
        </div>
    </div>

    <div class="card" style="max-width: 768px;">
        <div class="card-body">
            <div class="empty-state">
                <svg class="w-16 h-16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                <p class="empty-state-title">Halaman dalam pengembangan</p>
                <p class="empty-state-text">Form tambah unit kerja akan segera tersedia.</p>
                <a href="{{ route('admin.units.index') }}" class="btn btn-secondary mt-4">Kembali</a>
            </div>
        </div>
    </div>
</x-admin.layouts.app>
