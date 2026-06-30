<x-layouts.ppid-layout title="Biaya Layanan - PPID">
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
            <div class="ppid-page-breadcrumb"><a href="{{ route('ppid') }}">PPID</a><span>/</span><span>Standar Layanan</span><span>/</span><span>Biaya</span></div>
            <div class="ppid-page-header" data-reveal>
                <h1 class="ppid-page-title">Biaya Layanan</h1>
                <p class="ppid-page-subtitle">Biaya Pelayanan Informasi Publik</p>
            </div>

            <section class="ppid-section" data-reveal>
                <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 1.5rem; padding: 2.5rem; color: white; text-align: center; margin-bottom: 2rem;">
                    <h2 style="font-family: var(--font-display); font-size: 1.5rem; font-weight: 600; margin: 0 0 0.5rem;">Gratis!</h2>
                    <p style="font-size: 1rem; opacity: 0.95; margin: 0;">Layanan Informasi Publik Tidak Dipungut Biaya</p>
                </div>
            </section>

            <section class="ppid-section" data-reveal>
                <h2 class="ppid-section-title">Ketentuan</h2>
                <div class="ppid-section-content"><p>Berdasarkan UU No. 14 Tahun 2008, Badan Publik wajib memberikan informasi secara gratis. Pemohon tidak dipungut biaya untuk:</p></div>
                <ul class="ppid-list">
                    <li>Melihat dan membaca informasi di ruang layanan</li>
                    <li>Mengunduh informasi dari portal online</li>
                    <li>Memohon informasi melalui formulir</li>
                    <li>Menerima salinan informasi dalam bentuk softcopy</li>
                </ul>
            </section>

            <section class="ppid-section" data-reveal>
                <h2 class="ppid-section-title">Pengecualian</h2>
                <div class="ppid-info-box">
                    <h3 class="ppid-info-box-title">Biaya Penggandaan</h3>
                    <p class="ppid-info-box-text">Hanya informasi dalam bentuk hardcopy yang dapat dikenakan biaya penggandaan sesuai biaya riil.</p>
                </div>
                <div class="ppid-grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); margin-top: 1.5rem;">
                    <div class="ppid-card" style="text-align: center;"><h3 class="ppid-card-title">Fotokopi A4</h3><p style="font-family: var(--font-display); font-size: 1.5rem; font-weight: 600; color: var(--ppid-primary);">Rp 200</p><p class="ppid-card-text">per lembar</p></div>
                    <div class="ppid-card" style="text-align: center;"><h3 class="ppid-card-title">CD/DVD</h3><p style="font-family: var(--font-display); font-size: 1.5rem; font-weight: 600; color: var(--ppid-primary);">Rp 5.000</p><p class="ppid-card-text">per keping</p></div>
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
