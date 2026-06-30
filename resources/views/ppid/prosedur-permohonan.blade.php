<x-layouts.ppid-layout title="Tata Cara Permohonan - PPID">
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
            <div class="ppid-page-breadcrumb"><a href="{{ route('ppid') }}">PPID</a><span>/</span><span>Layanan</span><span>/</span><span>Prosedur</span></div>
            <div class="ppid-page-header" data-reveal>
                <h1 class="ppid-page-title">Tata Cara Permohonan</h1>
                <p class="ppid-page-subtitle">Prosedur mengajukan permohonan informasi publik</p>
            </div>

            <section class="ppid-section" data-reveal>
                <h2 class="ppid-section-title">Alur Permohonan</h2>
                <div class="ppid-timeline">
                    <div class="ppid-timeline-item"><span class="ppid-timeline-number">Langkah 1</span><h3 class="ppid-timeline-title">Mengisi Formulir</h3><p class="ppid-timeline-text">Pemohon mengisi formulir permohonan informasi</p></div>
                    <div class="ppid-timeline-item"><span class="ppid-timeline-number">Langkah 2</span><h3 class="ppid-timeline-title">Pendaftaran</h3><p class="ppid-timeline-text">PPID mencatat dan memberikan bukti pendaftaran</p></div>
                    <div class="ppid-timeline-item"><span class="ppid-timeline-number">Langkah 3</span><h3 class="ppid-timeline-title">Pemrosesan</h3><p class="ppid-timeline-text">PPID memproses permohonan dan mengecek ketersediaan</p></div>
                    <div class="ppid-timeline-item"><span class="ppid-timeline-number">Langkah 4</span><h3 class="ppid-timeline-title">Pemberian Informasi</h3><p class="ppid-timeline-text">Informasi diberikan kepada pemohon</p></div>
                </div>
            </section>

            <section class="ppid-section" data-reveal>
                <h2 class="ppid-section-title">Jangka Waktu</h2>
                <div class="ppid-grid">
                    <div class="ppid-card" style="text-align: center;"><h3 class="ppid-card-title">Informasi Biasa</h3><p style="font-family: var(--font-display); font-size: 2rem; font-weight: 600; color: var(--ppid-primary);">10 Hari</p><p class="ppid-card-text">Kerja</p></div>
                    <div class="ppid-card" style="text-align: center;"><h3 class="ppid-card-title">Informasi Serta Merta</h3><p style="font-family: var(--font-display); font-size: 2rem; font-weight: 600; color: var(--ppid-primary);">1x24 Jam</p><p class="ppid-card-text">Jam kerja</p></div>
                    <div class="ppid-card" style="text-align: center;"><h3 class="ppid-card-title">Perpanjangan</h3><p style="font-family: var(--font-display); font-size: 2rem; font-weight: 600; color: var(--ppid-primary);">+7 Hari</p><p class="ppid-card-text">Jika diperlukan</p></div>
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
