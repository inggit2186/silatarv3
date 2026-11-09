<x-admin.layouts.app>
    <div class="page-header">
        <div class="page-header-content">
            <span class="page-label">// Unit Kerja</span>
            <h1 class="page-title">Tambah Unit Kerja</h1>
            <p class="page-subtitle">Tambahkan unit kerja baru ke sistem</p>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.units.store') }}" class="space-y-6">
        @csrf

        @include('admin.units._fields')

        <!-- Submit -->
        <div class="flex items-center gap-3">
            <button type="submit" class="btn btn-primary">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
                Simpan
            </button>
            <a href="{{ route('admin.units.index') }}" class="btn btn-secondary">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Batal
            </a>
        </div>
    </form>
</x-admin.layouts.app>
