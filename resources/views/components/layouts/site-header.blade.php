<header class="site-header" data-reveal="">
    <a class="brand-lockup" href="{{ url("/") }}" aria-label="SILATAR home">
        <span class="brand-mark" aria-hidden="true"><span></span></span>
        <span class="brand-word"><span>SILATAR</span><span>V2</span></span>
    </a>

    <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="mobile-nav" id="menuToggle"><span>Menu</span><i aria-hidden="true"></i></button>

    <nav class="site-nav" id="site-nav" aria-label="Primary navigation">
        <a href="{{ url("/") }}">Beranda</a>
        <a href="{{ route('news.index') }}">Berita</a>
        <a href="{{ route('satuan-kerja') }}">Unit Kerja</a>
        <a href="{{ route('pelayanan') }}" class="nav-layanan">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Layanan
        </a>
        <a href="{{ route('ppid') }}">PPID</a>
        <a href="{{ url("/#kontak") }}">Kontak</a>
    </nav>

    @auth
        <div class="user-menu-wrapper" x-data="{ open: false }" @click.away="open = false">
            <button type="button" class="user-menu-btn" @click="open = !open" :aria-expanded="open">
                @if(Auth::user()->pp && file_exists(public_path('storage/' . Auth::user()->pp)))
                    <img src="{{ asset('storage/' . Auth::user()->pp) }}" alt="PP" class="user-pp">
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
                <div class="user-dropdown-divider"></div>
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

<!-- Mobile Navigation -->
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
            <a href="{{ route('admin.dashboard') }}" class="mobile-nav-cta">Dashboard</a>
        @else
            <a href="{{ route('login') }}" class="mobile-nav-cta">Login</a>
        @endauth
    </nav>
</div>

<script>
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
