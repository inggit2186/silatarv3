<x-layouts.ppid-layout title="Tata Cara Sengketa - PPID">
    <x-ppid.nav />

    <main class="ppid-content">
        <div class="ppid-page">
            <div class="ppid-page-breadcrumb"><a href="{{ route('ppid') }}">PPID</a><span>/</span><span>Layanan</span><span>/</span><span>Sengketa</span></div>
            <div class="ppid-page-header" data-reveal>
                <h1 class="ppid-page-title">Tata Cara Penyelesaian Sengketa</h1>
                <p class="ppid-page-subtitle">Prosedur pengajuan penyelesaian sengketa</p>
            </div>

            <section class="ppid-section" data-reveal>
                <h2 class="ppid-section-title">Alur Penyelesaian</h2>
                <div class="ppid-timeline">
                    <div class="ppid-timeline-item"><span class="ppid-timeline-number">Langkah 1</span><h3 class="ppid-timeline-title">Pengajuan Sengketa</h3><p class="ppid-timeline-text">Ke Komisi Informasi jika keberatan tidak dapat diselesaikan</p></div>
                    <div class="ppid-timeline-item"><span class="ppid-timeline-number">Langkah 2</span><h3 class="ppid-timeline-title">Verifikasi</h3><p class="ppid-timeline-text">Komisi memverifikasi kelengkapan berkas</p></div>
                    <div class="ppid-timeline-item"><span class="ppid-timeline-number">Langkah 3</span><h3 class="ppid-timeline-title">Mediasi</h3><p class="ppid-timeline-text">Fasilitasi mediasi antara kedua pihak</p></div>
                    <div class="ppid-timeline-item"><span class="ppid-timeline-number">Langkah 4</span><h3 class="ppid-timeline-title">Ajudikasi</h3><p class="ppid-timeline-text">Jika mediasi gagal, dilakukan adjudikasi</p></div>
                    <div class="ppid-timeline-item"><span class="ppid-timeline-number">Langkah 5</span><h3 class="ppid-timeline-title">Putusan</h3><p class="ppid-timeline-text">Komisi menerbitkan putusan final</p></div>
                </div>
            </section>
        </div>
        @include('ppid.partials.footer')
    </main></x-layouts.ppid-layout>
