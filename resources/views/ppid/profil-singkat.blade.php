<x-layouts.ppid-layout title="Profil Singkat - PPID">
    <!-- Header -->
    <header class="site-header ppid-header">
        <div class="ppid-header-top">
            <a class="brand-lockup" href="{{ url("/") }}" aria-label="SILATAR home">
                <span class="brand-mark" aria-hidden="true"><span></span></span>
                <span class="brand-word"><span>SILATAR</span><span>V2</span></span>
            </a>
            <div class="ppid-header-actions">
                <a href="{{ route('ppid.formulir-permohonan') }}" class="ppid-header-badge">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/></svg>
                    Ajukan Permohonan
                </a>
                <button class="ppid-nav-toggle-btn" type="button" id="mobileMenuToggle"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg></button>
            </div>
        </div>
        <x-ppid.nav />
    </header>
    <div class="ppid-mobile-menu" id="ppidMobileMenu">
        <ul class="ppid-mobile-nav">
            <li class="ppid-mobile-nav-item"><a href="{{ route('ppid') }}" class="ppid-mobile-nav-link">Beranda</a></li>
            <li class="ppid-mobile-nav-item"><a href="{{ route('ppid.profil-singkat') }}" class="ppid-mobile-nav-link">Profil Singkat</a></li>
            <li class="ppid-mobile-nav-item"><a href="{{ route('ppid.visi-misi') }}" class="ppid-mobile-nav-link">Visi Misi</a></li>
        </ul>
    </div>

    <main class="ppid-content">
        <div class="ppid-page">
            <div class="ppid-page-breadcrumb">
                <a href="{{ route('ppid') }}">PPID</a>
                <span>/</span>
                <span>Profil PPID</span>
                <span>/</span>
                <span>Profil Singkat</span>
            </div>
            <div class="ppid-page-header" data-reveal>
                <h1 class="ppid-page-title">Profil Singkat PPID</h1>
                <p class="ppid-page-subtitle">Pejabat Pengelola Informasi dan Dokumentasi Kementerian Agama Kabupaten Tanah Datar</p>
            </div>

            <section class="ppid-section" data-reveal>
                <div class="ppid-section-content">
                    <h2 class="ppid-section-title">Tentang PPID Kemenag Tanah Datar</h2>
                    <p>Pejabat Pengelola Informasi dan Dokumentasi (PPID) Kementerian Agama Kabupaten Tanah Datar dibentuk berdasarkan Peraturan Menteri Agama Nomor 37 Tahun 2012 tentang Pedoman Pengelolaan Informasi dan Dokumentasi Kementerian Agama. PPID berfungsi sebagai pusat informasi dan dokumentasi yang memberikan akses mudah bagi masyarakat untuk memperoleh informasi publik.</p>
                    <p>PPID Kemenag Kabupaten Tanah Datar bertanggung jawab untuk memberikan pelayanan informasi yang transparan, akuntabel, dan responsif terhadap kebutuhan masyarakat. Kami berkomitmen untuk memenuhi hak masyarakat atas informasi sesuai dengan Undang-Undang Nomor 14 Tahun 2008 tentang Keterbukaan Informasi Publik.</p>
                </div>
                <div class="ppid-info-box">
                    <h3 class="ppid-info-box-title">Tugas Utama PPID</h3>
                    <p class="ppid-info-box-text">PPID memiliki tugas untuk mengkoordinasikan dan mengkonsolidasikan pengumpulan bahan informasi dan dokumentasi dari Unit Kerja, menyediakan bahan informasi dan dokumentasi untuk masyarakat, dan mengoordinasikan pelayanan informasi dan dokumentasi.</p>
                </div>
                <h3 class="ppid-section-title" style="font-size: 1.1rem;">Fungsi PPID</h3>
                <ul class="ppid-list">
                    <li>Mengkoordinasikan pengumpulan informasi dan dokumentasi dari seluruh unit kerja</li>
                    <li>Menyimpan dan mendokumentasikan informasi publik</li>
                    <li>Memberikan layanan informasi kepada masyarakat</li>
                    <li>Melakukan verifikasi bahan informasi publik</li>
                    <li>Melakukan pemutakhiran informasi dan dokumentasi</li>
                    <li>Menyediakan akses informasi bagi masyarakat</li>
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
