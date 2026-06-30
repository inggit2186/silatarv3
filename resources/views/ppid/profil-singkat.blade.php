<x-layouts.ppid-layout title="Profil Singkat - PPID">
    <!-- Header -->
    <x-ppid.nav />

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
    </main></x-layouts.ppid-layout>
