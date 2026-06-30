<nav class="ppid-nav-horizontal" x-data="{ open: null }">
    <ul class="ppid-nav-list">
        <li class="ppid-nav-item {{ request()->routeIs('ppid') && !request()->routeIs('ppid.*') ? 'is-active' : '' }}">
            <a href="{{ route('ppid') }}" class="ppid-nav-link">Beranda</a>
        </li>

        {{-- Profil PPID --}}
        <li class="ppid-nav-item has-submenu {{ request()->routeIs('ppid.profil-*', 'ppid.struktur', 'ppid.tugas-fungsi') ? 'is-active' : '' }}"
            @mouseenter="open = 'profil'" @mouseleave="open = null">
            <button type="button" class="ppid-nav-toggle">
                Profil PPID
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
            </button>
            <div class="ppid-dropdown" x-show="open === 'profil'" x-transition>
                <a href="{{ route('ppid.profil-singkat') }}" class="ppid-dropdown-link {{ request()->routeIs('ppid.profil-singkat') ? 'is-active' : '' }}">Profil Singkat</a>
                <a href="{{ route('ppid.visi-misi') }}" class="ppid-dropdown-link {{ request()->routeIs('ppid.visi-misi') ? 'is-active' : '' }}">Visi Misi</a>
                <a href="{{ route('ppid.tugas-fungsi') }}" class="ppid-dropdown-link {{ request()->routeIs('ppid.tugas-fungsi') ? 'is-active' : '' }}">Tugas, Fungsi & Wewenang</a>
                <a href="{{ route('ppid.struktur') }}" class="ppid-dropdown-link {{ request()->routeIs('ppid.struktur') ? 'is-active' : '' }}">Struktur Kelembagaan</a>
            </div>
        </li>

        {{-- Regulasi --}}
        <li class="ppid-nav-item {{ request()->routeIs('ppid.regulasi') ? 'is-active' : '' }}">
            <a href="{{ route('ppid.regulasi') }}" class="ppid-nav-link">Regulasi</a>
        </li>

        {{-- Standar Layanan --}}
        <li class="ppid-nav-item has-submenu {{ request()->routeIs('ppid.maklumat', 'ppid.jadwal', 'ppid.biaya', 'ppid.laporan-layanan') ? 'is-active' : '' }}"
            @mouseenter="open = 'standar'" @mouseleave="open = null">
            <button type="button" class="ppid-nav-toggle">
                Standar Layanan
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
            </button>
            <div class="ppid-dropdown" x-show="open === 'standar'" x-transition>
                <a href="{{ route('ppid.maklumat') }}" class="ppid-dropdown-link {{ request()->routeIs('ppid.maklumat') ? 'is-active' : '' }}">Maklumat Pelayanan</a>
                <a href="{{ route('ppid.jadwal') }}" class="ppid-dropdown-link {{ request()->routeIs('ppid.jadwal') ? 'is-active' : '' }}">Jadwal Layanan</a>
                <a href="{{ route('ppid.biaya') }}" class="ppid-dropdown-link {{ request()->routeIs('ppid.biaya') ? 'is-active' : '' }}">Biaya Layanan</a>
                <a href="{{ route('ppid.laporan-layanan') }}" class="ppid-dropdown-link {{ request()->routeIs('ppid.laporan-layanan') ? 'is-active' : '' }}">Laporan Layanan</a>
            </div>
        </li>

        {{-- Layanan Informasi --}}
        <li class="ppid-nav-item has-submenu {{ request()->routeIs('ppid.prosedur-*', 'ppid.formulir-*', 'ppid.informasi-*', 'ppid.pengaduan') ? 'is-active' : '' }}"
            @mouseenter="open = 'layanan'" @mouseleave="open = null">
            <button type="button" class="ppid-nav-toggle">
                Layanan Informasi
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
            </button>
            <div class="ppid-dropdown ppid-dropdown-wide" x-show="open === 'layanan'" x-transition>
                <div class="ppid-dropdown-section">
                    <span class="ppid-dropdown-label">Prosedur Layanan</span>
                    <a href="{{ route('ppid.prosedur-permohonan') }}" class="ppid-dropdown-link {{ request()->routeIs('ppid.prosedur-permohonan') ? 'is-active' : '' }}">Tata Cara Permohonan</a>
                    <a href="{{ route('ppid.prosedur-keberatan') }}" class="ppid-dropdown-link {{ request()->routeIs('ppid.prosedur-keberatan') ? 'is-active' : '' }}">Tata Cara Keberatan</a>
                    <a href="{{ route('ppid.prosedur-sengketa') }}" class="ppid-dropdown-link {{ request()->routeIs('ppid.prosedur-sengketa') ? 'is-active' : '' }}">Tata Cara Sengketa</a>
                </div>
                <div class="ppid-dropdown-section">
                    <span class="ppid-dropdown-label">Formulir</span>
                    <a href="{{ route('ppid.formulir-permohonan') }}" class="ppid-dropdown-link {{ request()->routeIs('ppid.formulir-permohonan') ? 'is-active' : '' }}">Formulir Permohonan</a>
                    <a href="{{ route('ppid.formulir-keberatan') }}" class="ppid-dropdown-link {{ request()->routeIs('ppid.formulir-keberatan') ? 'is-active' : '' }}">Formulir Keberatan</a>
                </div>
                <div class="ppid-dropdown-section">
                    <span class="ppid-dropdown-label">Daftar Informasi</span>
                    <a href="{{ route('ppid.informasi-berkala') }}" class="ppid-dropdown-link {{ request()->routeIs('ppid.informasi-berkala') ? 'is-active' : '' }}">Informasi Berkala</a>
                    <a href="{{ route('ppid.informasi-serta-merta') }}" class="ppid-dropdown-link {{ request()->routeIs('ppid.informasi-serta-merta') ? 'is-active' : '' }}">Informasi Serta Merta</a>
                    <a href="{{ route('ppid.informasi-setiap-saat') }}" class="ppid-dropdown-link {{ request()->routeIs('ppid.informasi-setiap-saat') ? 'is-active' : '' }}">Informasi Setiap Saat</a>
                </div>
                <div class="ppid-dropdown-section">
                    <span class="ppid-dropdown-label">Lainnya</span>
                    <a href="{{ route('ppid.pengaduan') }}" class="ppid-dropdown-link {{ request()->routeIs('ppid.pengaduan') ? 'is-active' : '' }}">Pengaduan</a>
                </div>
            </div>
        </li>

        {{-- Gallery --}}
        <li class="ppid-nav-item has-submenu {{ request()->routeIs('ppid.gallery-*') ? 'is-active' : '' }}"
            @mouseenter="open = 'gallery'" @mouseleave="open = null">
            <button type="button" class="ppid-nav-toggle">
                Gallery
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
            </button>
            <div class="ppid-dropdown" x-show="open === 'gallery'" x-transition>
                <a href="{{ route('ppid.gallery-fasilitas') }}" class="ppid-dropdown-link {{ request()->routeIs('ppid.gallery-fasilitas') ? 'is-active' : '' }}">Fasilitas Publik</a>
                <a href="{{ route('ppid.gallery-kegiatan') }}" class="ppid-dropdown-link {{ request()->routeIs('ppid.gallery-kegiatan') ? 'is-active' : '' }}">Kegiatan</a>
            </div>
        </li>

        {{-- Tentang Kami --}}
        <li class="ppid-nav-item {{ request()->routeIs('ppid.tentang-kami') ? 'is-active' : '' }}">
            <a href="{{ route('ppid.tentang-kami') }}" class="ppid-nav-link">Tentang Kami</a>
        </li>
    </ul>
</nav>
