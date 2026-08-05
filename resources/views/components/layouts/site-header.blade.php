<header class="site-header" data-reveal="">
    @php
    use Illuminate\Support\Facades\DB;
    $showMadrasahMenu = false;
    if (auth()->check() && auth()->user()->dept_id) {
        $dept = DB::table('ktd_department')->where('id', auth()->user()->dept_id)->first();
        if ($dept && in_array($dept->kategori, ['min', 'mtsn', 'man', 'other'])) {
            $showMadrasahMenu = true;
        }
    }
    @endphp
    <a class="brand-lockup" href="{{ url("/") }}" aria-label="SILATAR home">
        <span class="brand-mark" aria-hidden="true"><span></span></span>
        <span class="brand-word"><span>SILATAR</span><span>V2</span></span>
    </a>

    <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="mobile-nav" id="menuToggle"><span>Menu</span><i aria-hidden="true"></i></button>

    <nav class="site-nav" id="site-nav" aria-label="Primary navigation">
        <a href="{{ url("/") }}" class="{{ request()->is('/') ? 'is-active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            Beranda
        </a>
        <a href="{{ route('news.index') }}" class="{{ request()->is('berita*') ? 'is-active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
            Berita
        </a>
        <a href="{{ route('satuan-kerja') }}" class="{{ request()->is('satuan-kerja*') ? 'is-active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21V5a2 2 0 00-2-2H3a2 2 0 00-2 2v16m14 0H5m14 0h2m-2 0h-2M5 21h2m-2 0H3m14 0h2m-2 0h-2M7 7h10M7 11h10M7 15h6"/></svg>
            Unit Kerja
        </a>
        <a href="{{ route('pelayanan') }}" class="{{ request()->is('pelayanan*') ? 'is-active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Layanan
        </a>
        <a href="{{ route('ppid') }}" class="{{ request()->is('ppid*') ? 'is-active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h14a2 2 0 012 2v14a2 2 0 01-2 2h-4m-6-9v6m0-6H5"/></svg>
            PPID
        </a>
        <a href="{{ url("/#kontak") }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            Kontak
        </a>
    </nav>

    @auth
        <div class="user-menu-wrapper" x-data="{ open: false }" @click.away="open = false">
            <button type="button" class="user-menu-btn" @click="open = !open" :aria-expanded="open">
                @if(Auth::user()->pp && Auth::user()->nomor_induk)
                    <img src="{{ asset('storage/users_berkas/' . Auth::user()->nomor_induk . '/' . Auth::user()->pp) }}" alt="PP" class="user-pp">
                @else
                    <div class="user-pp-placeholder">{{ substr(Auth::user()->name, 0, 1) }}</div>
                @endif
                <div class="user-info">
                    <span class="user-name">{{ Auth::user()->name }}</span>
                    <span class="user-role">{{ Auth::user()->pekerjaan ?? 'Pegawai' }}</span>
                </div>
                <svg class="user-chevron" :class="open ? 'is-open' : ''" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
            </button>
            <div class="user-dropdown" x-show="open" x-transition>
                <a href="{{ route('admin.dashboard') }}" class="user-dropdown-item">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                    Dashboard
                </a>
                <a href="{{ route('profil') }}" class="user-dropdown-item">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    Profil Saya
                </a>
                <a href="{{ route('pengajuan-saya') }}" class="user-dropdown-item">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    Pengajuan Saya
                </a>
                <a href="{{ route('laporan-kinerja') }}" class="user-dropdown-item">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Laporan Kinerja
                </a>
                @if(auth()->user()->role === 'kepala')
                <a href="{{ route('penilaian-kinerja.index') }}" class="user-dropdown-item">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    Penilaian Kinerja
                </a>
                @endif
                @if($showMadrasahMenu)
                <a href="{{ route('madrasah.profil') }}" class="user-dropdown-item">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    Laporan Madrasah
                </a>
                @endif
                <div class="user-dropdown-divider"></div>
                <a href="#" class="user-dropdown-item" id="changePasswordBtn">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                    Ubah Password
                </a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="user-dropdown-item user-dropdown-logout">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16,17 21,12 16,7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                        Logout
                    </button>
                </form>
            </div>
        </div>
    @else
        <a href="{{ route('login') }}" class="ticket-pill"><span>Login</span><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h12m-5-5 5 5-5 5"/></svg></a>
    @endauth
</header>

<!-- Bottom Navigation Bar - Mobile Only -->
<nav class="bottom-nav" aria-label="Mobile navigation">
    <a href="{{ url("/") }}" class="bottom-nav-item {{ request()->is('/') ? 'is-active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
        <span>Beranda</span>
    </a>
    <a href="{{ route('news.index') }}" class="bottom-nav-item {{ request()->is('berita*') ? 'is-active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
        <span>Berita</span>
    </a>
    <a href="{{ route('pelayanan') }}" class="bottom-nav-item {{ request()->is('pelayanan*') ? 'is-active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        <span>Layanan</span>
    </a>
    <a href="{{ route('laporan-kinerja') }}" class="bottom-nav-item {{ request()->is('laporan-kinerja*') ? 'is-active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        <span>Kinerja</span>
    </a>
    @auth
    <div class="bottom-nav-profil" x-data="{ open: false }" @click.away="open = false">
        <button type="button" class="bottom-nav-profil-btn" @click="open = !open" :aria-expanded="open">
            @if(Auth::user()->pp && Auth::user()->nomor_induk)
                <img src="{{ asset('storage/users_berkas/' . Auth::user()->nomor_induk . '/' . Auth::user()->pp) }}" alt="PP" class="bottom-nav-avatar">
            @else
                <div class="bottom-nav-avatar-placeholder">{{ substr(Auth::user()->name, 0, 1) }}</div>
            @endif
        </button>
        <div class="bottom-nav-dropdown" x-show="open" x-transition>
            <div class="bottom-nav-dropdown-header">
                <span class="dropdown-user-name">{{ Auth::user()->name }}</span>
                <span class="dropdown-user-role">{{ Auth::user()->pekerjaan ?? 'Pegawai' }}</span>
            </div>
            <div class="bottom-nav-dropdown-divider"></div>
            <a href="{{ route('profil') }}" class="bottom-nav-dropdown-item">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                Profil Saya
            </a>
            <a href="{{ route('laporan-kinerja') }}" class="bottom-nav-dropdown-item">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Laporan Kinerja
            </a>
            <a href="{{ route('pengajuan-saya') }}" class="bottom-nav-dropdown-item">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                Pengajuan Saya
            </a>
            @if(in_array(auth()->user()->role, ['superadmin', 'admin', 'frontdesk', 'kasubbag', 'kepala', 'kasi']))
            <a href="{{ route('admin.dashboard') }}" class="bottom-nav-dropdown-item bottom-nav-dropdown-admin">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                Admin Panel
            </a>
            @endif
            <div class="bottom-nav-dropdown-divider"></div>
            <a href="#" class="bottom-nav-dropdown-item" id="mobilePasswordBtnBottom">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                Ubah Password
            </a>
            <form action="{{ route('logout') }}" method="POST" class="bottom-nav-dropdown-form">
                @csrf
                <button type="submit" class="bottom-nav-dropdown-item bottom-nav-dropdown-logout">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16,17 21,12 16,7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                    Logout
                </button>
            </form>
        </div>
    </div>
    @else
    <a href="{{ route('login') }}" class="bottom-nav-item">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
        <span>Login</span>
    </a>
    @endauth
</nav>

<style>
    /* Bottom Navigation Dropdown for Profil */
    .bottom-nav-profil {
        position: relative;
    }

    .bottom-nav-profil-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        background: none;
        border: none;
        cursor: pointer;
    }

    .bottom-nav-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid var(--gold);
    }

    .bottom-nav-avatar-placeholder {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: var(--gold);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 0.875rem;
    }

    .bottom-nav-dropdown {
        position: absolute;
        bottom: 100%;
        right: 0;
        margin-bottom: 0.5rem;
        background: var(--rice);
        border: 1px solid var(--line);
        border-radius: 0.75rem;
        min-width: 200px;
        box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.15);
        overflow: hidden;
        z-index: 200;
    }

    .bottom-nav-dropdown-header {
        padding: 0.75rem 1rem;
        background: rgba(212, 168, 83, 0.05); background: oklch(68% 0.145 74 / 0.05);
    }

    .dropdown-user-name {
        display: block;
        font-weight: 600;
        font-size: 0.875rem;
        color: var(--ink);
    }

    .dropdown-user-role {
        display: block;
        font-size: 0.75rem;
        color: var(--ink-soft);
    }

    .bottom-nav-dropdown-divider {
        height: 1px;
        background: var(--line);
    }

    .bottom-nav-dropdown-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.625rem 1rem;
        color: var(--ink);
        text-decoration: none;
        font-size: 0.8125rem;
        transition: background 0.15s ease;
        border: none;
        background: none;
        width: 100%;
        cursor: pointer;
        text-align: left;
    }

    .bottom-nav-dropdown-item:hover {
        background: rgba(212, 168, 83, 0.08); background: oklch(68% 0.145 74 / 0.08);
    }

    .bottom-nav-dropdown-item svg {
        width: 16px;
        height: 16px;
        color: var(--ink-soft);
        flex-shrink: 0;
    }

    .bottom-nav-dropdown-admin {
        color: var(--gold);
        font-weight: 600;
    }

    .bottom-nav-dropdown-admin svg {
        color: var(--gold);
    }

    .bottom-nav-dropdown-logout {
        color: #dc2626;
    }

    .bottom-nav-dropdown-logout svg {
        color: #dc2626;
    }

    .bottom-nav-dropdown-form {
        margin: 0;
        padding: 0;
    }
</style>

<!-- Mobile Navigation Overlay (hidden by default, only for additional menu) -->
<div id="mobile-nav" class="mobile-nav hidden">
    <button class="mobile-nav-close" id="menuClose" type="button"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 18L18 6M6 6l12 12"/></svg></button>
    <nav aria-label="Mobile navigation">
        <a href="{{ url("/") }}">Beranda</a>
        <a href="{{ route('news.index') }}">Berita</a>
        <a href="{{ route('satuan-kerja') }}">Unit Kerja</a>
        <a href="{{ route('pelayanan') }}">Layanan</a>
        <a href="{{ route('ppid') }}">PPID</a>
        <a href="{{ url("/#kontak") }}">Kontak</a>
        @auth
            @if($showMadrasahMenu)
            <a href="{{ route('madrasah.profil') }}">Laporan Madrasah</a>
            @endif
            @if(auth()->user()->role === 'kepala')
            <a href="{{ route('penilaian-kinerja.index') }}">Penilaian Kinerja</a>
            @endif
            <a href="#" id="mobilePasswordBtn">Ubah Password</a>
            <a href="{{ route('admin.dashboard') }}" class="mobile-nav-cta">Dashboard</a>
        @else
            <a href="{{ route('login') }}" class="mobile-nav-cta">Login</a>
        @endauth
    </nav>
</div>

<!-- Password Modal - NEO MIRAI Theme -->
<div id="passwordModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header-custom">
            <div class="flex items-center gap-3">
                <div class="modal-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="modal-title-custom">Ubah Password</h3>
                    <p class="modal-subtitle">Update password akun Anda</p>
                </div>
            </div>
            <button type="button" class="modal-close-btn" onclick="closePasswordModal()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form id="passwordForm">
            <div class="modal-body-custom">
                <div class="form-group-custom">
                    <label class="form-label-custom">Password Lama</label>
                    <div class="input-wrapper">
                        <input type="password" id="current_password" class="input-custom" placeholder="Masukkan password lama" required>
                        <button type="button" class="toggle-password-btn" onclick="togglePasswordVisibility('current_password')">
                            <svg id="eye-current_password" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="form-group-custom">
                    <label class="form-label-custom">Password Baru</label>
                    <div class="input-wrapper">
                        <input type="password" id="new_password" class="input-custom" placeholder="Minimal 6 karakter" required minlength="6">
                        <button type="button" class="toggle-password-btn" onclick="togglePasswordVisibility('new_password')">
                            <svg id="eye-new_password" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="form-group-custom">
                    <label class="form-label-custom">Konfirmasi Password Baru</label>
                    <div class="input-wrapper">
                        <input type="password" id="password_confirmation" class="input-custom" placeholder="Ulangi password baru" required minlength="6">
                        <button type="button" class="toggle-password-btn" onclick="togglePasswordVisibility('password_confirmation')">
                            <svg id="eye-password_confirmation" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <div id="passwordError" class="error-alert hidden">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span id="passwordErrorMessage"></span>
                </div>
            </div>

            <div class="modal-footer-custom">
                <button type="button" class="btn-secondary" onclick="closePasswordModal()">Batal</button>
                <button type="submit" class="btn-primary" id="submitPasswordBtn">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    /* Bottom Navigation Bar - Mobile Only */
    .bottom-nav {
        display: none;
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        z-index: 100;
        background: var(--rice);
        border-top: 1px solid var(--line);
        padding: 0.5rem 0;
        padding-bottom: calc(0.5rem + env(safe-area-inset-bottom, 0px));
        justify-content: space-around;
        align-items: center;
        box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.08);
    }

    .bottom-nav-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.25rem;
        padding: 0.5rem 1rem;
        color: var(--ink-soft);
        text-decoration: none;
        transition: color 0.2s ease;
        min-width: 60px;
    }

    .bottom-nav-item svg {
        width: 22px;
        height: 22px;
        transition: transform 0.2s ease;
    }

    .bottom-nav-item span {
        font-size: 0.7rem;
        font-weight: 500;
    }

    .bottom-nav-item:hover,
    .bottom-nav-item.is-active {
        color: var(--gold);
    }

    .bottom-nav-item.is-active svg {
        transform: scale(1.1);
    }

    .bottom-nav-item.is-active::before {
        content: '';
        position: absolute;
        top: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 24px;
        height: 3px;
        background: var(--gold);
        border-radius: 0 0 2px 2px;
    }

    /* Show bottom nav only on mobile */
    @media (max-width: 900px) {
        .bottom-nav {
            display: flex;
        }

        /* Add padding to main content for bottom nav */
        main, .neo-mirai {
            padding-bottom: calc(70px + env(safe-area-inset-bottom, 0px));
        }

        /* Hide desktop header nav on mobile */
        .site-nav {
            display: none !important;
        }

        .nav-toggle {
            display: inline-flex !important;
        }

        .ticket-pill {
            display: none !important;
        }

        .user-menu-wrapper {
            display: none !important;
        }

        /* Adjust header for mobile */
        .site-header {
            padding: 0.75rem 1rem;
        }

        .site-header .brand-lockup {
            max-width: 140px;
        }
    }

    @media (max-width: 480px) {
        .bottom-nav-item {
            padding: 0.4rem 0.75rem;
            min-width: 50px;
        }

        .bottom-nav-item svg {
            width: 20px;
            height: 20px;
        }

        .bottom-nav-item span {
            font-size: 0.65rem;
        }
    }

    /* Modal Overlay */
    .modal-overlay {
        position: fixed;
        inset: 0;
        z-index: 9999;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(4px);
        display: none;
        align-items: center;
        justify-content: center;
        padding: 1rem;
    }
    .modal-overlay.active {
        display: flex;
    }

    /* Modal Content */
    .modal-content {
        background: var(--rice);
        border: 1px solid var(--line);
        border-radius: 1rem;
        width: 100%;
        max-width: 440px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        animation: modalIn 0.3s var(--ease);
    }
    @keyframes modalIn {
        from {
            opacity: 0;
            transform: scale(0.95) translateY(-10px);
        }
        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }

    /* Modal Header */
    .modal-header-custom {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 1rem;
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid var(--line);
    }
    .modal-icon {
        width: 44px;
        height: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(212, 168, 83, 0.1); background: oklch(68% 0.145 74 / 0.1);
        border-radius: 0.75rem;
        color: var(--gold);
    }
    .modal-icon svg {
        width: 22px;
        height: 22px;
    }
    .modal-title-custom {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--ink);
        margin: 0;
    }
    .modal-subtitle {
        font-size: 0.875rem;
        color: var(--ink-soft);
        margin: 0.125rem 0 0;
    }
    .modal-close-btn {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: transparent;
        border: 1px solid var(--line);
        border-radius: 0.5rem;
        color: var(--ink-soft);
        cursor: pointer;
        transition: all 0.2s;
        flex-shrink: 0;
    }
    .modal-close-btn:hover {
        border-color: var(--gold);
        color: var(--gold);
    }
    .modal-close-btn svg {
        width: 18px;
        height: 18px;
    }

    /* Modal Body */
    .modal-body-custom {
        padding: 1.5rem;
    }
    .form-group-custom {
        margin-bottom: 1rem;
    }
    .form-label-custom {
        display: block;
        font-size: 0.875rem;
        font-weight: 500;
        color: var(--ink);
        margin-bottom: 0.5rem;
    }
    .input-wrapper {
        position: relative;
    }
    .input-custom {
        width: 100%;
        padding: 0.75rem 2.75rem 0.75rem 1rem;
        border: 1px solid var(--line);
        border-radius: 0.5rem;
        background: var(--paper);
        color: var(--ink);
        font-size: 0.9375rem;
        transition: all 0.2s;
    }
    .input-custom:focus {
        outline: none;
        border-color: var(--gold);
        box-shadow: 0 0 0 3px rgba(212, 168, 83, 0.15); box-shadow: 0 0 0 3px oklch(68% 0.145 74 / 0.15);
    }
    .input-custom::placeholder {
        color: var(--ash);
    }
    .toggle-password-btn {
        position: absolute;
        right: 0.75rem;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: var(--ink-soft);
        cursor: pointer;
        padding: 0.25rem;
        transition: color 0.2s;
    }
    .toggle-password-btn:hover {
        color: var(--ink);
    }

    /* Error Alert */
    .error-alert {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        padding: 0.875rem 1rem;
        background: rgba(250, 245, 240, 0.15); background: oklch(97% 0.06 25 / 0.15);
        border: 1px solid rgba(180, 80, 60, 0.3); border: 1px solid oklch(70% 0.18 25 / 0.3);
        border-radius: 0.5rem;
        font-size: 0.875rem;
        color: rgba(139, 64, 48, 1); color: oklch(45% 0.2 25);
        margin-top: 0.5rem;
    }
    .error-alert svg {
        width: 18px;
        height: 18px;
        flex-shrink: 0;
        color: rgba(160, 100, 70, 1); color: oklch(55% 0.2 25);
        margin-top: 0.125rem;
    }
    .error-alert.hidden {
        display: none;
    }

    /* Modal Footer */
    .modal-footer-custom {
        display: flex;
        justify-content: flex-end;
        gap: 0.75rem;
        padding: 1rem 1.5rem;
        border-top: 1px solid var(--line);
        background: var(--paper);
        border-radius: 0 0 1rem 1rem;
    }
    .btn-secondary {
        padding: 0.625rem 1.25rem;
        background: var(--paper-soft);
        border: 1px solid var(--line);
        border-radius: 0.5rem;
        color: var(--ink);
        font-size: 0.9375rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-secondary:hover {
        background: var(--paper-deep);
        border-color: var(--ash);
    }
    .btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.625rem 1.25rem;
        background: linear-gradient(135deg, var(--gold) 0%, var(--sun-deep) 100%);
        border: none;
        border-radius: 0.5rem;
        color: white;
        font-size: 0.9375rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(212, 168, 83, 0.4); box-shadow: 0 4px 12px oklch(68% 0.145 74 / 0.4);
    }
    .btn-primary:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }
    .btn-primary svg {
        width: 18px;
        height: 18px;
    }

    /* Success Toast */
    .toast-notification {
        position: fixed;
        bottom: 1.5rem;
        right: 1.5rem;
        z-index: 10000;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 1rem 1.25rem;
        background: var(--rice);
        border: 1px solid rgba(70, 160, 100, 0.4); border: 1px solid oklch(70% 0.18 145 / 0.4);
        border-radius: 0.75rem;
        font-size: 0.9375rem;
        font-weight: 500;
        color: rgba(70, 160, 100, 1); color: oklch(45% 0.15 145);
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
        animation: toastIn 0.3s var(--ease);
    }
    @keyframes toastIn {
        from {
            opacity: 0;
            transform: translateY(1rem);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .toast-notification svg {
        width: 20px;
        height: 20px;
        color: rgba(70, 160, 100, 1); color: oklch(50% 0.15 145);
        flex-shrink: 0;
    }
</style>

<script>
    // Password Modal Functions
    const passwordModal = document.getElementById('passwordModal');

    // Close dropdown when clicking change password
    const changePasswordBtn = document.getElementById('changePasswordBtn');
    if (changePasswordBtn) {
        changePasswordBtn.addEventListener('click', function(e) {
            e.preventDefault();
            document.body.click();
            openPasswordModal();
        });
    }

    function openPasswordModal() {
        document.getElementById('passwordError').classList.add('hidden');
        document.getElementById('current_password').value = '';
        document.getElementById('new_password').value = '';
        document.getElementById('password_confirmation').value = '';
        passwordModal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closePasswordModal() {
        passwordModal.classList.remove('active');
        document.body.style.overflow = '';
    }

    // Close on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && passwordModal.classList.contains('active')) {
            closePasswordModal();
        }
    });

    // Close on backdrop click
    passwordModal.addEventListener('click', function(e) {
        if (e.target === passwordModal) {
            closePasswordModal();
        }
    });

    // Mobile button
    const mobilePasswordBtn = document.getElementById('mobilePasswordBtn');
    if (mobilePasswordBtn) {
        mobilePasswordBtn.addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('mobile-nav').classList.add('hidden');
            document.body.style.overflow = '';
            openPasswordModal();
        });
    }

    // Bottom nav password button
    const mobilePasswordBtnBottom = document.getElementById('mobilePasswordBtnBottom');
    if (mobilePasswordBtnBottom) {
        mobilePasswordBtnBottom.addEventListener('click', function(e) {
            e.preventDefault();
            openPasswordModal();
        });
    }

    // Form Submit
    document.getElementById('passwordForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const submitBtn = document.getElementById('submitPasswordBtn');
        const currentPassword = document.getElementById('current_password').value;
        const newPassword = document.getElementById('new_password').value;
        const confirmPassword = document.getElementById('password_confirmation').value;

        // Client-side validation
        if (newPassword.length < 6) {
            document.getElementById('passwordErrorMessage').textContent = 'Password baru minimal 6 karakter.';
            document.getElementById('passwordError').classList.remove('hidden');
            return;
        }

        if (newPassword !== confirmPassword) {
            document.getElementById('passwordErrorMessage').textContent = 'Konfirmasi password baru tidak cocok.';
            document.getElementById('passwordError').classList.remove('hidden');
            return;
        }

        // Disable button
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<svg class="animate-spin w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10" stroke-opacity="0.25"/><path d="M12 2a10 10 0 0 1 10 10" stroke-opacity="1"/></svg> Menyimpan...';

        try {
            const response = await fetch('{{ route('ubah-password.update') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    current_password: currentPassword,
                    password: newPassword,
                    password_confirmation: confirmPassword,
                }),
            });

            const data = await response.json();

            if (data.success) {
                closePasswordModal();
                showToast('Password berhasil diubah.');
            } else {
                const errorEl = document.getElementById('passwordErrorMessage');
                if (data.errors?.current_password) {
                    errorEl.textContent = data.errors.current_password[0];
                } else if (data.errors?.password) {
                    errorEl.textContent = data.errors.password[0];
                } else {
                    errorEl.textContent = data.message || 'Terjadi kesalahan.';
                }
                document.getElementById('passwordError').classList.remove('hidden');
            }
        } catch (error) {
            document.getElementById('passwordErrorMessage').textContent = 'Terjadi kesalahan. Silakan coba lagi.';
            document.getElementById('passwordError').classList.remove('hidden');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg> Simpan';
        }
    });

    // Toggle Password Visibility
    function togglePasswordVisibility(inputId) {
        const input = document.getElementById(inputId);
        const eyeIcon = document.getElementById('eye-' + inputId);

        if (input.type === 'password') {
            input.type = 'text';
            eyeIcon.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
            `;
        } else {
            input.type = 'password';
            eyeIcon.innerHTML = `
                <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            `;
        }
    }

    // Toast Notification
    function showToast(message) {
        const toast = document.createElement('div');
        toast.className = 'toast-notification';
        toast.innerHTML = `
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>${message}</span>
        `;
        document.body.appendChild(toast);

        setTimeout(() => {
            toast.style.animation = 'toastIn 0.3s var(--ease) reverse';
            setTimeout(() => toast.remove(), 300);
        }, 4000);
    }

    // Mobile nav toggle
    document.addEventListener('DOMContentLoaded', function() {
        var header = document.querySelector('.site-header');
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 50) {
                header.classList.add('is-scrolled');
            } else {
                header.classList.remove('is-scrolled');
            }
        });

        var menuToggle = document.getElementById('menuToggle');
        var menuClose = document.getElementById('menuClose');
        var mobileNav = document.getElementById('mobile-nav');

        if (menuToggle && mobileNav) {
            menuToggle.addEventListener('click', function() {
                mobileNav.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            });
        }
        if (menuClose) {
            menuClose.addEventListener('click', function() {
                mobileNav.classList.add('hidden');
                document.body.style.overflow = '';
            });
        }
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && mobileNav && !mobileNav.classList.contains('hidden')) {
                mobileNav.classList.add('hidden');
                document.body.style.overflow = '';
            }
        });
    });
</script>
