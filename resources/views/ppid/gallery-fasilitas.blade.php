<x-layouts.ppid-layout title="Fasilitas Publik - Gallery PPID">
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
            <div class="ppid-page-breadcrumb"><a href="{{ route('ppid') }}">PPID</a><span>/</span><span>Gallery</span><span>/</span><span>Fasilitas</span></div>
            <div class="ppid-page-header" data-reveal>
                <h1 class="ppid-page-title">Fasilitas Publik</h1>
                <p class="ppid-page-subtitle">Dokumentasi fasilitas untuk masyarakat</p>
            </div>

            <section class="ppid-section" data-reveal>
                <div class="ppid-grid">
                    <div class="ppid-card" style="padding: 0; overflow: hidden;"><div style="aspect-ratio: 16/10; background: linear-gradient(135deg, oklch(8% 0.15 190 / 0.2), oklch(8% 0.15 190 / 0.1)); display: flex; align-items: center; justify-content: center;"><svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="color: var(--ppid-primary);"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg></div><div style="padding: 1.25rem;"><h3 class="ppid-card-title">Ruang Layanan</h3><p class="ppid-card-text">Ruang khusus pelayanan informasi</p></div></div>
                    <div class="ppid-card" style="padding: 0; overflow: hidden;"><div style="aspect-ratio: 16/10; background: linear-gradient(135deg, oklch(8% 0.15 190 / 0.2), oklch(8% 0.15 190 / 0.1)); display: flex; align-items: center; justify-content: center;"><svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="color: var(--ppid-primary);"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/></svg></div><div style="padding: 1.25rem;"><h3 class="ppid-card-title">Kios Informasi</h3><p class="ppid-card-text">Kios informasi digital</p></div></div>
                    <div class="ppid-card" style="padding: 0; overflow: hidden;"><div style="aspect-ratio: 16/10; background: linear-gradient(135deg, oklch(8% 0.15 190 / 0.2), oklch(8% 0.15 190 / 0.1)); display: flex; align-items: center; justify-content: center;"><svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="color: var(--ppid-primary);"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg></div><div style="padding: 1.25rem;"><h3 class="ppid-card-title">Ruang Baca</h3><p class="ppid-card-text">Area membaca informasi</p></div></div>
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
