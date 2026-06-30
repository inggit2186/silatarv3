<x-layouts.ppid-layout title="Tugas, Fungsi dan Wewenang - PPID">
    <x-ppid.nav />

    <main class="ppid-content">
        <div class="ppid-page">
            <div class="ppid-page-breadcrumb"><a href="{{ route('ppid') }}">PPID</a><span>/</span><span>Profil PPID</span><span>/</span><span>Tugas Fungsi</span></div>
            <div class="ppid-page-header" data-reveal>
                <h1 class="ppid-page-title">Tugas, Fungsi dan Wewenang</h1>
                <p class="ppid-page-subtitle">Struktur dan Tugas PPID</p>
            </div>

            <section class="ppid-section" data-reveal>
                <h2 class="ppid-section-title">1. Atasan PPID</h2>
                <div class="ppid-section-content"><p>Atasan PPID adalah pejabat yang secara struktural berada di atas PPID dan memiliki tanggung jawab untuk melakukan pengawasan terhadap pelaksanaan tugas PPID.</p></div>
                <div class="ppid-info-box"><h3 class="ppid-info-box-title">Tugas Atasan PPID</h3><ul class="ppid-list"><li>Melakukan pengawasan dan pengendalian terhadap pelaksanaan tugas PPID</li><li>Menerima dan menindaklanjuti keberatan dari pemohon informasi</li><li>Memutuskan atas keberatan yang diajukan pemohon informasi</li></ul></div>
            </section>

            <section class="ppid-section" data-reveal>
                <h2 class="ppid-section-title">2. PPID</h2>
                <div class="ppid-section-content"><p>PPID adalah pejabat yang bertanggung jawab dalam pengelolaan informasi dan dokumentasi.</p></div>
                <div class="ppid-info-box"><h3 class="ppid-info-box-title">Tugas PPID</h3><ul class="ppid-list"><li>Mengkoordinasikan pengumpulan informasi dan dokumentasi</li><li>Memberikan layanan informasi kepada masyarakat</li><li>Melakukan verifikasi bahan informasi publik</li></ul></div>
            </section>

            <section class="ppid-section" data-reveal>
                <h2 class="ppid-section-title">3. PPID Pelaksana</h2>
                <div class="ppid-section-content"><p>PPID Pelaksana adalah pejabat yang ditunjuk untuk membantu PPID dalam melaksanakan tugas-tugas pengelolaan informasi di unit kerja masing-masing.</p></div>
            </section>
        </div>
        @include('ppid.partials.footer')
    </main></x-layouts.ppid-layout>
