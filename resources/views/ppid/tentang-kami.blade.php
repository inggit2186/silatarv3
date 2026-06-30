<x-layouts.ppid-layout title="Tentang Kami - PPID">
    <header class="site-header ppid-header">
        <div class="ppid-header-top">
            <a class="brand-lockup" href="{{ url("/") }}"><span class="brand-mark"><span></span></span><span class="brand-word"><span>SILATAR</span><span>V2</span></span></a>
            <div class="ppid-header-actions">
                <a href="{{ route('ppid.formulir-permohonan') }}" class="ppid-header-badge">Ajukan Permohonan</a>
                <button class="ppid-nav-toggle-btn" type="button" id="mobileMenuToggle"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="12" x2="21" y2="12"/></svg></button>
            </div>
        </div>
        <x-ppid.nav />
    </header>
    <div class="ppid-mobile-menu" id="ppidMobileMenu"><ul class="ppid-mobile-nav"><li class="ppid-mobile-nav-item"><a href="{{ route('ppid') }}" class="ppid-mobile-nav-link">Beranda</a></li></ul></div>

    <main class="ppid-content">
        <div class="ppid-page">
            <div class="ppid-page-breadcrumb"><a href="{{ route('ppid') }}">PPID</a><span>/</span><span>Tentang Kami</span></div>
            <div class="ppid-page-header" data-reveal>
                <h1 class="ppid-page-title">Tentang Kami</h1>
                <p class="ppid-page-subtitle">Profil PPID Kemenag Kabupaten Tanah Datar</p>
            </div>

            <section class="ppid-section" data-reveal>
                <div class="ppid-section-content">
                    <h2 class="ppid-section-title">Selamat Datang</h2>
                    <p>Selamat datang di Portal Pejabat Pengelola Informasi dan Dokumentasi (PPID) Kementerian Agama Kabupaten Tanah Datar. Portal ini kami hadirkan sebagai wujud komitmen kami dalam memberikan pelayanan informasi publik yang transparan, akuntabel, dan mudah diakses oleh seluruh masyarakat.</p>
                    <p>PPID merupakan ujung tombak dalam реализации transparency dan accountability di lingkungan Kementerian Agama Kabupaten Tanah Datar.</p>
                </div>
            </section>

            <section class="ppid-section" data-reveal>
                <h2 class="ppid-section-title">Visi Kami</h2>
                <div class="ppid-info-box">
                    <p class="ppid-info-box-text" style="font-size: 1.1rem;">"Terwujudnya Government Transformation melalui optimalisasi keterbukaan informasi publik yang profesional, transparan, dan akuntabel untuk mendukung good governance."</p>
                </div>
            </section>

            <section class="ppid-section" data-reveal>
                <h2 class="ppid-section-title">Hubungi Kami</h2>
                <div class="ppid-grid">
                    <div class="ppid-card">
                        <div class="ppid-card-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/></svg></div>
                        <h3 class="ppid-card-title">Alamat</h3>
                        <p class="ppid-card-text">Jl. Raya Batusangkar No. 1<br>Kabupaten Tanah Datar</p>
                    </div>
                    <div class="ppid-card">
                        <div class="ppid-card-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2"/></svg></div>
                        <h3 class="ppid-card-title">Telepon</h3>
                        <p class="ppid-card-text">(0752) 12345<br>Faks: (0752) 67890</p>
                    </div>
                    <div class="ppid-card">
                        <div class="ppid-card-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/></svg></div>
                        <h3 class="ppid-card-title">Email</h3>
                        <p class="ppid-card-text">ppid@kemenag-tanahdatar.go.id</p>
                    </div>
                </div>
            </section>
        </div>
        @include('ppid.partials.footer')
    </main>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var mobileToggle = document.getElementById('mobileMenuToggle');
            var mobileMenu = document.getElementById('ppidMobileMenu');
            if (mobileToggle && mobileMenu) { mobileToggle.addEventListener('click', function() { mobileMenu.classList.toggle('is-open'); }); }
        });
    </script>
</x-layouts.ppid-layout>
