<aside class="ppid-sidebar" id="ppidSidebar">
    <nav aria-label="PPID Navigation">
        <ul class="ppid-nav">
            <li class="ppid-nav-item">
                <a href="{{ route('ppid') }}" class="ppid-nav-link {{ request()->routeIs('ppid') && !request()->routeIs('ppid.*') ? 'is-active' : '' }}">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                    Beranda
                </a>
            </li>

            <li class="ppid-nav-item">
                <button type="button" class="ppid-nav-toggle {{ request()->routeIs('ppid.profil-*', 'ppid.struktur', 'ppid.tugas-fungsi') ? 'is-active' : '' }}">
                    <span style="display: flex; align-items: center; gap: 0.75rem;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        Profil PPID
                    </span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
                <ul class="ppid-submenu {{ request()->routeIs('ppid.profil-*', 'ppid.struktur', 'ppid.tugas-fungsi') ? 'is-open' : '' }}">
                    <li class="ppid-submenu-item"><a href="{{ route('ppid.profil-singkat') }}" class="ppid-submenu-link {{ request()->routeIs('ppid.profil-singkat') ? 'is-active' : '' }}">Profil Singkat</a></li>
                    <li class="ppid-submenu-item"><a href="{{ route('ppid.visi-misi') }}" class="ppid-submenu-link {{ request()->routeIs('ppid.visi-misi') ? 'is-active' : '' }}">Visi Misi</a></li>
                    <li class="ppid-submenu-item"><a href="{{ route('ppid.tugas-fungsi') }}" class="ppid-submenu-link {{ request()->routeIs('ppid.tugas-fungsi') ? 'is-active' : '' }}">Tugas, Fungsi & Wewenang</a></li>
                    <li class="ppid-submenu-item"><a href="{{ route('ppid.struktur') }}" class="ppid-submenu-link {{ request()->routeIs('ppid.struktur') ? 'is-active' : '' }}">Struktur Kelembagaan</a></li>
                </ul>
            </li>

            <li class="ppid-nav-item">
                <a href="{{ route('ppid.regulasi') }}" class="ppid-nav-link {{ request()->routeIs('ppid.regulasi') ? 'is-active' : '' }}">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    Regulasi
                </a>
            </li>

            <li class="ppid-nav-item">
                <button type="button" class="ppid-nav-toggle {{ request()->routeIs('ppid.maklumat', 'ppid.jadwal', 'ppid.biaya', 'ppid.laporan-layanan') ? 'is-active' : '' }}">
                    <span style="display: flex; align-items: center; gap: 0.75rem;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        Standar Layanan
                    </span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
                <ul class="ppid-submenu {{ request()->routeIs('ppid.maklumat', 'ppid.jadwal', 'ppid.biaya', 'ppid.laporan-layanan') ? 'is-open' : '' }}">
                    <li class="ppid-submenu-item"><a href="{{ route('ppid.maklumat') }}" class="ppid-submenu-link {{ request()->routeIs('ppid.maklumat') ? 'is-active' : '' }}">Maklumat Pelayanan</a></li>
                    <li class="ppid-submenu-item"><a href="{{ route('ppid.jadwal') }}" class="ppid-submenu-link {{ request()->routeIs('ppid.jadwal') ? 'is-active' : '' }}">Jadwal Layanan</a></li>
                    <li class="ppid-submenu-item"><a href="{{ route('ppid.biaya') }}" class="ppid-submenu-link {{ request()->routeIs('ppid.biaya') ? 'is-active' : '' }}">Biaya Layanan</a></li>
                    <li class="ppid-submenu-item"><a href="{{ route('ppid.laporan-layanan') }}" class="ppid-submenu-link {{ request()->routeIs('ppid.laporan-layanan') ? 'is-active' : '' }}">Laporan Layanan</a></li>
                </ul>
            </li>

            <li class="ppid-nav-item">
                <button type="button" class="ppid-nav-toggle {{ request()->routeIs('ppid.prosedur-*', 'ppid.formulir-*', 'ppid.informasi-*', 'ppid.pengaduan') ? 'is-active' : '' }}">
                    <span style="display: flex; align-items: center; gap: 0.75rem;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                        Layanan Informasi
                    </span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
                <ul class="ppid-submenu {{ request()->routeIs('ppid.prosedur-*', 'ppid.formulir-*', 'ppid.informasi-*', 'ppid.pengaduan') ? 'is-open' : '' }}">
                    <li class="ppid-submenu-item">
                        <button type="button" class="ppid-nav-toggle" style="font-size: 0.68rem; padding: 0.4rem 1rem; background: transparent;">
                            <span>Prosedur Layanan</span>
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                        </button>
                        <ul class="ppid-submenu-level-2 is-open">
                            <li><a href="{{ route('ppid.prosedur-permohonan') }}" class="ppid-submenu-level-2-link {{ request()->routeIs('ppid.prosedur-permohonan') ? 'is-active' : '' }}">Tata Cara Permohonan</a></li>
                            <li><a href="{{ route('ppid.prosedur-keberatan') }}" class="ppid-submenu-level-2-link {{ request()->routeIs('ppid.prosedur-keberatan') ? 'is-active' : '' }}">Tata Cara Keberatan</a></li>
                            <li><a href="{{ route('ppid.prosedur-sengketa') }}" class="ppid-submenu-level-2-link {{ request()->routeIs('ppid.prosedur-sengketa') ? 'is-active' : '' }}">Tata Cara Sengketa</a></li>
                        </ul>
                    </li>
                    <li class="ppid-submenu-item">
                        <button type="button" class="ppid-nav-toggle" style="font-size: 0.68rem; padding: 0.4rem 1rem; background: transparent;">
                            <span>Formulir</span>
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                        </button>
                        <ul class="ppid-submenu-level-2 is-open">
                            <li><a href="{{ route('ppid.formulir-permohonan') }}" class="ppid-submenu-level-2-link {{ request()->routeIs('ppid.formulir-permohonan') ? 'is-active' : '' }}">Formulir Permohonan</a></li>
                            <li><a href="{{ route('ppid.formulir-keberatan') }}" class="ppid-submenu-level-2-link {{ request()->routeIs('ppid.formulir-keberatan') ? 'is-active' : '' }}">Formulir Keberatan</a></li>
                        </ul>
                    </li>
                    <li class="ppid-submenu-item">
                        <button type="button" class="ppid-nav-toggle" style="font-size: 0.68rem; padding: 0.4rem 1rem; background: transparent;">
                            <span>Daftar Informasi</span>
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                        </button>
                        <ul class="ppid-submenu-level-2 is-open">
                            <li><a href="{{ route('ppid.informasi-berkala') }}" class="ppid-submenu-level-2-link {{ request()->routeIs('ppid.informasi-berkala') ? 'is-active' : '' }}">Informasi Berkala</a></li>
                            <li><a href="{{ route('ppid.informasi-serta-merta') }}" class="ppid-submenu-level-2-link {{ request()->routeIs('ppid.informasi-serta-merta') ? 'is-active' : '' }}">Informasi Serta Merta</a></li>
                            <li><a href="{{ route('ppid.informasi-setiap-saat') }}" class="ppid-submenu-level-2-link {{ request()->routeIs('ppid.informasi-setiap-saat') ? 'is-active' : '' }}">Informasi Setiap Saat</a></li>
                        </ul>
                    </li>
                    <li class="ppid-submenu-item"><a href="{{ route('ppid.pengaduan') }}" class="ppid-submenu-link {{ request()->routeIs('ppid.pengaduan') ? 'is-active' : '' }}">Pengaduan</a></li>
                </ul>
            </li>

            <li class="ppid-nav-item">
                <button type="button" class="ppid-nav-toggle {{ request()->routeIs('ppid.gallery-*') ? 'is-active' : '' }}">
                    <span style="display: flex; align-items: center; gap: 0.75rem;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                        Gallery
                    </span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
                <ul class="ppid-submenu {{ request()->routeIs('ppid.gallery-*') ? 'is-open' : '' }}">
                    <li class="ppid-submenu-item"><a href="{{ route('ppid.gallery-fasilitas') }}" class="ppid-submenu-link {{ request()->routeIs('ppid.gallery-fasilitas') ? 'is-active' : '' }}">Fasilitas Publik</a></li>
                    <li class="ppid-submenu-item"><a href="{{ route('ppid.gallery-kegiatan') }}" class="ppid-submenu-link {{ request()->routeIs('ppid.gallery-kegiatan') ? 'is-active' : '' }}">Kegiatan</a></li>
                </ul>
            </li>

            <li class="ppid-nav-item">
                <a href="{{ route('ppid.tentang-kami') }}" class="ppid-nav-link {{ request()->routeIs('ppid.tentang-kami') ? 'is-active' : '' }}">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    Tentang Kami
                </a>
            </li>
        </ul>
    </nav>
</aside>
