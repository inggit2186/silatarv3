<x-layouts.ppid-layout title="Jadwal Layanan - PPID">
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
            <div class="ppid-page-breadcrumb"><a href="{{ route('ppid') }}">PPID</a><span>/</span><span>Standar Layanan</span><span>/</span><span>Jadwal</span></div>
            <div class="ppid-page-header" data-reveal>
                <h1 class="ppid-page-title">Jadwal Layanan</h1>
                <p class="ppid-page-subtitle">Waktu dan Hari Layanan Informasi Publik</p>
            </div>

            <section class="ppid-section" data-reveal>
                <h2 class="ppid-section-title">Jam Operasional</h2>
                <div style="background: white; border: 1px solid oklch(58% 0.06 76 / 0.15); border-radius: 1.5rem; overflow: hidden;">
                    <table class="ppid-table" style="border-radius: 0;">
                        <thead><tr><th>Hari</th><th>Jam Buka</th><th>Istirahat</th><th>Status</th></tr></thead>
                        <tbody>
                            <tr><td>Senin</td><td>08.00 WIB</td><td>12.00 - 13.00</td><td><span style="background: #10b981; color: white; padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.7rem; font-weight: 600;">Buka</span></td></tr>
                            <tr><td>Selasa</td><td>08.00 WIB</td><td>12.00 - 13.00</td><td><span style="background: #10b981; color: white; padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.7rem; font-weight: 600;">Buka</span></td></tr>
                            <tr><td>Rabu</td><td>08.00 WIB</td><td>12.00 - 13.00</td><td><span style="background: #10b981; color: white; padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.7rem; font-weight: 600;">Buka</span></td></tr>
                            <tr><td>Kamis</td><td>08.00 WIB</td><td>12.00 - 13.00</td><td><span style="background: #10b981; color: white; padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.7rem; font-weight: 600;">Buka</span></td></tr>
                            <tr><td>Jumat</td><td>08.00 WIB</td><td>11.30 - 13.30</td><td><span style="background: #10b981; color: white; padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.7rem; font-weight: 600;">Buka</span></td></tr>
                            <tr><td>Sabtu</td><td>08.00 WIB</td><td>12.00 - 13.00</td><td><span style="background: #f59e0b; color: white; padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.7rem; font-weight: 600;">Buka</span></td></tr>
                            <tr><td>Minggu</td><td>-</td><td>-</td><td><span style="background: #ef4444; color: white; padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.7rem; font-weight: 600;">Tutup</span></td></tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <section class="ppid-section" data-reveal>
                <h2 class="ppid-section-title">Kontak Layanan</h2>
                <div class="ppid-grid">
                    <div class="ppid-card">
                        <div class="ppid-card-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2"/></svg></div>
                        <h3 class="ppid-card-title">Telepon</h3>
                        <p class="ppid-card-text">(0752) 12345</p>
                    </div>
                    <div class="ppid-card">
                        <div class="ppid-card-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/></svg></div>
                        <h3 class="ppid-card-title">Email</h3>
                        <p class="ppid-card-text">ppid@kemenag-tanahdatar.go.id</p>
                    </div>
                    <div class="ppid-card">
                        <div class="ppid-card-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/></svg></div>
                        <h3 class="ppid-card-title">Alamat</h3>
                        <p class="ppid-card-text">Jl. Raya Batusangkar No. 1</p>
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
