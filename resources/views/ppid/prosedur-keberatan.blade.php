<x-layouts.ppid-layout title="Tata Cara Keberatan - PPID">
    <x-ppid.nav />

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
    </main></x-layouts.ppid-layout>
