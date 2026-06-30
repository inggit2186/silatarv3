<header class="site-header" data-reveal="" x-data="{ scrolled: false }" @scroll.window="scrolled = window.pageYOffset > 50" :class="scrolled ? 'is-scrolled' : ''">
    <!-- Logo di kiri -->
    <a class="brand-lockup" href="{{ url("/") }}" aria-label="SILATAR home">
        <span class="brand-mark" aria-hidden="true"><span></span></span>
        <span class="brand-word"><span>SILATAR</span><span>V2</span></span>
    </a>

    <!-- Menu di tengah -->
    <nav class="site-nav" aria-label="PPID navigation">
        <a href="{{ route('ppid') }}" class="{{ request()->routeIs('ppid') && !request()->routeIs('ppid.*') ? 'is-active' : '' }}">Beranda</a>

        {{-- Profil PPID --}}
        <div class="nav-dropdown" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
            <button type="button" class="nav-dropdown-toggle" :class="open ? 'is-open' : ''">
                Profil PPID
                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
            </button>
            <div class="nav-dropdown-menu" x-show="open" x-transition>
                <a href="{{ route('ppid.profil-singkat') }}" class="{{ request()->routeIs('ppid.profil-singkat') ? 'is-active' : '' }}">Profil Singkat</a>
                <a href="{{ route('ppid.visi-misi') }}" class="{{ request()->routeIs('ppid.visi-misi') ? 'is-active' : '' }}">Visi Misi</a>
                <a href="{{ route('ppid.tugas-fungsi') }}" class="{{ request()->routeIs('ppid.tugas-fungsi') ? 'is-active' : '' }}">Tugas, Fungsi & Wewenang</a>
                <a href="{{ route('ppid.struktur') }}" class="{{ request()->routeIs('ppid.struktur') ? 'is-active' : '' }}">Struktur Kelembagaan</a>
            </div>
        </div>

        <a href="{{ route('ppid.regulasi') }}" class="{{ request()->routeIs('ppid.regulasi') ? 'is-active' : '' }}">Regulasi</a>

        {{-- Standar Layanan --}}
        <div class="nav-dropdown" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
            <button type="button" class="nav-dropdown-toggle" :class="open ? 'is-open' : ''">
                Standar Layanan
                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
            </button>
            <div class="nav-dropdown-menu" x-show="open" x-transition>
                <a href="{{ route('ppid.maklumat') }}" class="{{ request()->routeIs('ppid.maklumat') ? 'is-active' : '' }}">Maklumat Pelayanan</a>
                <a href="{{ route('ppid.jadwal') }}" class="{{ request()->routeIs('ppid.jadwal') ? 'is-active' : '' }}">Jadwal Layanan</a>
                <a href="{{ route('ppid.biaya') }}" class="{{ request()->routeIs('ppid.biaya') ? 'is-active' : '' }}">Biaya Layanan</a>
                <a href="{{ route('ppid.laporan-layanan') }}" class="{{ request()->routeIs('ppid.laporan-layanan') ? 'is-active' : '' }}">Laporan Layanan</a>
            </div>
        </div>

        {{-- Layanan Informasi --}}
        <div class="nav-dropdown" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
            <button type="button" class="nav-dropdown-toggle" :class="open ? 'is-open' : ''">
                Layanan Informasi
                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
            </button>
            <div class="nav-dropdown-menu nav-dropdown-wide" x-show="open" x-transition>
                <div class="nav-dropdown-section">
                    <span class="nav-dropdown-label">Prosedur Layanan</span>
                    <a href="{{ route('ppid.prosedur-permohonan') }}" class="{{ request()->routeIs('ppid.prosedur-permohonan') ? 'is-active' : '' }}">Tata Cara Permohonan</a>
                    <a href="{{ route('ppid.prosedur-keberatan') }}" class="{{ request()->routeIs('ppid.prosedur-keberatan') ? 'is-active' : '' }}">Tata Cara Keberatan</a>
                    <a href="{{ route('ppid.prosedur-sengketa') }}" class="{{ request()->routeIs('ppid.prosedur-sengketa') ? 'is-active' : '' }}">Tata Cara Sengketa</a>
                </div>
                <div class="nav-dropdown-section">
                    <span class="nav-dropdown-label">Formulir</span>
                    <a href="{{ route('ppid.formulir-permohonan') }}" class="{{ request()->routeIs('ppid.formulir-permohonan') ? 'is-active' : '' }}">Formulir Permohonan</a>
                    <a href="{{ route('ppid.formulir-keberatan') }}" class="{{ request()->routeIs('ppid.formulir-keberatan') ? 'is-active' : '' }}">Formulir Keberatan</a>
                </div>
                <div class="nav-dropdown-section">
                    <span class="nav-dropdown-label">Daftar Informasi</span>
                    <a href="{{ route('ppid.informasi-berkala') }}" class="{{ request()->routeIs('ppid.informasi-berkala') ? 'is-active' : '' }}">Informasi Berkala</a>
                    <a href="{{ route('ppid.informasi-serta-merta') }}" class="{{ request()->routeIs('ppid.informasi-serta-merta') ? 'is-active' : '' }}">Informasi Serta Merta</a>
                    <a href="{{ route('ppid.informasi-setiap-saat') }}" class="{{ request()->routeIs('ppid.informasi-setiap-saat') ? 'is-active' : '' }}">Informasi Setiap Saat</a>
                </div>
                <div class="nav-dropdown-section">
                    <span class="nav-dropdown-label">Lainnya</span>
                    <a href="{{ route('ppid.pengaduan') }}" class="{{ request()->routeIs('ppid.pengaduan') ? 'is-active' : '' }}">Pengaduan</a>
                </div>
            </div>
        </div>

        {{-- Gallery --}}
        <div class="nav-dropdown" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
            <button type="button" class="nav-dropdown-toggle" :class="open ? 'is-open' : ''">
                Gallery
                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
            </button>
            <div class="nav-dropdown-menu" x-show="open" x-transition>
                <a href="{{ route('ppid.gallery-fasilitas') }}" class="{{ request()->routeIs('ppid.gallery-fasilitas') ? 'is-active' : '' }}">Fasilitas Publik</a>
                <a href="{{ route('ppid.gallery-kegiatan') }}" class="{{ request()->routeIs('ppid.gallery-kegiatan') ? 'is-active' : '' }}">Kegiatan</a>
            </div>
        </div>

        <a href="{{ route('ppid.tentang-kami') }}" class="{{ request()->routeIs('ppid.tentang-kami') ? 'is-active' : '' }}">Tentang Kami</a>
    </nav>

    <!-- Tombol Ajukan Permohonan di kanan -->
    <a href="{{ route('ppid.formulir-permohonan') }}" class="ticket-pill">
        <span>Ajukan Permohonan</span>
        <svg viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h12m-5-5 5 5-5 5"/></svg>
    </a>
</header>
