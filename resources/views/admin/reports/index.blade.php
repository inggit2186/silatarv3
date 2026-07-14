<x-admin.layouts.app>
    <div class="page-header">
        <div class="page-header-content">
            <span class="page-label">// Laporan</span>
            <h1 class="page-title">Manajemen Laporan</h1>
            <p class="page-subtitle">Lihat dan unduh laporan sistem</p>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="empty-state">
                <svg class="w-16 h-16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="empty-state-title">Halaman dalam pengembangan</p>
                <p class="empty-state-text">Fitur laporan sedang dalam tahap pengembangan.</p>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary mt-4">Kembali ke Dashboard</a>
            </div>
        </div>
    </div>
</x-admin.layouts.app>
