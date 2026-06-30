<x-layouts.ppid-layout title="Tata Cara Keberatan - PPID">
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
            <div class="ppid-page-breadcrumb"><a href="{{ route('ppid') }}">PPID</a><span>/</span><span>Layanan</span><span>/</span><span>Keberatan</span></div>
            <div class="ppid-page-header" data-reveal>
                <h1 class="ppid-page-title">Tata Cara Pengajuan Keberatan</h1>
                <p class="ppid-page-subtitle">Prosedur mengajukan keberatan</p>
            </div>

            <section class="ppid-section" data-reveal>
                <h2 class="ppid-section-title">Alur Pengajuan</h2>
                <div class="ppid-timeline">
                    <div class="ppid-timeline-item"><span class="ppid-timeline-number">Langkah 1</span><h3 class="ppid-timeline-title">Mengajukan Keberatan</h3><p class="ppid-timeline-text">Pengajuan dalam waktu 30 hari kerja</p></div>
                    <div class="ppid-timeline-item"><span class="ppid-timeline-number">Langkah 2</span><h3 class="ppid-timeline-title">Pendaftaran</h3><p class="ppid-timeline-text">Atasan PPID mencatat keberatan</p></div>
                    <div class="ppid-timeline-item"><span class="ppid-timeline-number">Langkah 3</span><h3 class="ppid-timeline-title">Musyawarah</h3><p class="ppid-timeline-text">Fasilitasi antara pemohon dan PPID</p></div>
                    <div class="ppid-timeline-item"><span class="ppid-timeline-number">Langkah 4</span><h3 class="ppid-timeline-title">Keputusan</h3><p class="ppid-timeline-text">Atasan PPID memutuskan dalam 30 hari</p></div>
                </div>
            </section>

            <section class="ppid-section" data-reveal>
                <h2 class="ppid-section-title">Alasan Keberatan</h2>
                <ul class="ppid-list">
                    <li>Permohonan informasi ditolak</li>
                    <li>Informasi tidak sesuai dengan permohonan</li>
                    <li>Tidak diberikan informasi yang diminta</li>
                    <li>Permohonan tidak dilayani dengan baik</li>
                </ul>
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
