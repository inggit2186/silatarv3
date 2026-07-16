<x-layouts.app title="Penilaian Kinerja - SILATAR">

@push('styles')
<link rel="stylesheet" href="{{ asset('css/penilaian-kinerja-neo.css') }}">
@endpush

<main class="neo-mirai">

        <!-- Site Header -->
        <x-layouts.site-header />

        <!-- Hero Section -->
        <section class="hero-page bg-cover bg-center" style="background-image: url('/assets/img/template/kinerja-bg.webp'); padding: 2rem 2rem 4rem; min-height: 320px;">
            <div class="hero-page-content" style="padding-top: 80px;">
                <p class="section-label-gold section-label-sm">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    Sistem Penilaian
                </p>
                <h1 class="section-title-gold" style="font-size: clamp(2rem, 5vw, 3rem);">Penilaian Kinerja Pejabat</h1>
                <p class="section-subtitle-gold">Nilai kinerja pejabat struktural secara triwulanan</p>
            </div>
        </section>

        <div class="container mx-auto px-4 -mt-8 relative z-10 pb-12">
            <!-- Statistics Cards - Horizontal -->
            <div class="flex flex-wrap gap-4 mb-8">
                <div class="neo-card flex-1 min-w-[240px] flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-cyan-500 to-cyan-600 flex items-center justify-center text-white shadow-lg shadow-cyan-500/30">
                        <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-muted">Total Penilaian</p>
                        <p class="text-3xl font-bold">{{ $stats['total'] }}</p>
                    </div>
                </div>
                <div class="neo-card flex-1 min-w-[240px] flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center text-white shadow-lg shadow-emerald-500/30">
                        <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-muted">Tahun Ini</p>
                        <p class="text-3xl font-bold">{{ $stats['tahun_ini'] }}</p>
                    </div>
                </div>
                <div class="neo-card flex-1 min-w-[240px] flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center text-white shadow-lg shadow-green-500/30">
                        <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-muted">Total Thumbs Up</p>
                        <p class="text-3xl font-bold text-green-600">{{ $stats['total_up'] }}</p>
                    </div>
                </div>
                <div class="neo-card flex-1 min-w-[240px] flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-red-500 to-red-600 flex items-center justify-center text-white shadow-lg shadow-red-500/30">
                        <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M10 15v4a3 3 0 0 0 3 3l4-9V2H5.72a2 2 0 0 0-2 1.7l-1.38 9a2 2 0 0 0 2 2.3zM17 2h2.67A2.31 2.31 0 0 1 22 4v7a2.31 2.31 0 0 1-2.33 2H17"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-muted">Total Thumbs Down</p>
                        <p class="text-3xl font-bold text-red-500">{{ $stats['total_down'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Filter & Action -->
            <div class="neo-card mb-8">
                <div class="flex flex-wrap gap-4 items-end justify-between">
                    <div class="flex flex-wrap gap-4 items-end flex-1">
                        <form method="GET" class="flex flex-wrap gap-4 items-end">
                            <div>
                                <label class="form-label text-sm text-muted mb-1 block">Tahun</label>
                                <select name="tahun" class="neo-input" onchange="this.form.submit()">
                                    @foreach($tahunOptions as $tahun)
                                        <option value="{{ $tahun }}" {{ $tahun == $filters['tahun'] ? 'selected' : '' }}>
                                            {{ $tahun }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="form-label text-sm text-muted mb-1 block">Triwulan</label>
                                <select name="triwulan" class="neo-input" onchange="this.form.submit()">
                                    @foreach($triwulanOptions as $key => $label)
                                        <option value="{{ $key }}" {{ $key == $filters['triwulan'] ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </form>
                    </div>
                    <a href="{{ route('penilaian-kinerja.create', ['tahun' => $filters['tahun'], 'triwulan' => $filters['triwulan']]) }}" class="neo-btn neo-btn-primary">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 4v16m8-8H4"/>
                        </svg>
                        Buat Penilaian Baru
                    </a>
                </div>
            </div>

            <!-- Data Table -->
            <div class="neo-card">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-lg font-semibold">Daftar Penilaian</h2>
                    <span class="neo-badge">{{ $penilaians->total() }} penilaian</span>
                </div>

                @if($penilaians->isEmpty())
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 mx-auto text-muted mb-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <h3 class="text-lg font-semibold mb-2">Belum Ada Penilaian</h3>
                        <p class="text-muted mb-4">Tidak ada penilaian untuk periode ini.</p>
                        <a href="{{ route('penilaian-kinerja.create') }}" class="neo-btn-primary inline-flex items-center gap-2">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 4v16m8-8H4"/>
                            </svg>
                            Buat Penilaian Baru
                        </a>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="neo-table w-full">
                            <thead>
                                <tr>
                                    <th>Pejabat</th>
                                    <th>Jabatan</th>
                                    <th>Periode</th>
                                    <th>Skor</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($penilaians as $penilaian)
                                <tr>
                                    <td>
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-amber-400 to-amber-500 flex items-center justify-center text-white font-bold text-sm">
                                                {{ substr($penilaian->pejabat->name, 0, 2) }}
                                            </div>
                                            <div>
                                                <p class="font-medium">{{ $penilaian->pejabat->name }}</p>
                                                @if($penilaian->pejabat->nomor_induk)
                                                    <p class="text-xs text-muted">{{ $penilaian->pejabat->nomor_induk }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="neo-badge {{ $penilaian->pejabat->kat_jabatan === 'kepala' ? 'neo-badge-amber' : 'neo-badge-cyan' }}">
                                            {{ ucfirst($penilaian->pejabat->kat_jabatan) }}
                                        </span>
                                    </td>
                                    <td>
                                        <p class="text-sm">Triwulan {{ $penilaian->triwulan }}</p>
                                        <p class="text-xs text-muted">{{ $penilaian->tahun }}</p>
                                    </td>
                                    <td>
                                        <div class="flex items-center gap-2">
                                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-green-100 text-green-700 text-sm font-medium">
                                                <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"/>
                                                </svg>
                                                {{ $penilaian->total_thumbs_up }}
                                            </span>
                                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-red-100 text-red-600 text-sm font-medium">
                                                <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M10 15v4a3 3 0 0 0 3 3l4-9V2H5.72a2 2 0 0 0-2 1.7l-1.38 9a2 2 0 0 0 2 2.3zM17 2h2.67A2.31 2.31 0 0 1 22 4v7a2.31 2.31 0 0 1-2.33 2H17"/>
                                                </svg>
                                                {{ $penilaian->total_thumbs_down }}
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('penilaian-kinerja.show', $penilaian->id) }}" class="neo-btn-detail" title="Detail">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                            </a>
                                            <a href="{{ route('penilaian-kinerja.edit', $penilaian->id) }}" class="neo-btn-icon neo-btn-primary" title="Edit">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </a>
                                            <button type="button" onclick="confirmDelete({{ $penilaian->id }})" class="neo-btn-icon neo-btn-danger" title="Hapus">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($penilaians->hasPages())
                        <div class="mt-6">
                            {{ $penilaians->withQueryString()->links() }}
                        </div>
                    @endif
                @endif
            </div>
        </div>

        <!-- Footer -->
        <footer class="site-footer">
            <a class="brand-lockup brand-lockup-small" href="{{ url("/") }}" aria-label="SILATAR home">
                <span class="brand-mark" aria-hidden="true"><span></span></span>
                <span class="brand-word"><span>SILATAR</span><span>V2</span></span>
            </a>
            <p>Portal Layanan Digital Kementerian Agama Tanah Datar</p>
            <nav aria-label="Footer navigation">
                <a href="{{ url("/") }}">Beranda</a>
                <a href="{{ route('pelayanan') }}">Pelayanan</a>
                <a href="{{ route('satuan-kerja') }}">Unit Kerja</a>
                <a href="{{ route('news.index') }}">Berita</a>
            </nav>
            <div class="footer-copyright"><span>&copy; {{ date("Y") }} SILATAR - Kementerian Agama Tanah Datar</span></div>
        </footer>

        <!-- Delete Confirmation Modal -->
        <div id="deleteModal" class="modal-backdrop" x-data="{ show: false, id: null }">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="text-lg font-semibold">Konfirmasi Hapus</h3>
                    <button type="button" class="modal-close" @click="show = false">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus penilaian ini? Tindakan ini tidak dapat dibatalkan.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="neo-btn-secondary" @click="show = false">Batal</button>
                    <form id="deleteForm" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="neo-btn-danger">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </main>
</x-layouts.app>

@push('scripts')
<script>
    function confirmDelete(id) {
        const modal = document.getElementById('deleteModal');
        const form = document.getElementById('deleteForm');
        form.action = '/penilaian-kinerja/' + id;
        modal.classList.add('active');
    }

    function closeDeleteModal() {
        const modal = document.getElementById('deleteModal');
        modal.classList.remove('active');
    }

    document.getElementById('deleteModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeDeleteModal();
        }
    });
</script>
@endpush
