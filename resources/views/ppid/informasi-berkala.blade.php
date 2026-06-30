<x-layouts.ppid-layout title="Informasi Berkala - PPID">
    <x-ppid.nav />

    <main class="ppid-content">
        <div class="ppid-page">
            <div class="ppid-page-breadcrumb"><a href="{{ route('ppid') }}">PPID</a><span>/</span><span>Daftar Informasi</span><span>/</span><span>Berkala</span></div>
            <div class="ppid-page-header" data-reveal>
                <h1 class="ppid-page-title">Informasi Diumumkan Secara Berkala</h1>
                <p class="ppid-page-subtitle">Informasi yang wajib diumumkan secara berkala</p>
            </div>

            <section class="ppid-section" data-reveal>
                <div class="ppid-section-content"><p>Informasi berkala adalah informasi yang wajib disediakan dan diumumkan secara berkala sekurang-kurangnya setiap 6 bulan sekali.</p></div>
            </section>

            <section class="ppid-section" data-reveal>
                <h2 class="ppid-section-title">Daftar Informasi</h2>
                <div class="ppid-grid">
                    <div class="ppid-card"><div class="ppid-card-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/></svg></div><h3 class="ppid-card-title">Profil Organisasi</h3><p class="ppid-card-text">Struktur organisasi dan pejabat</p></div>
                    <div class="ppid-card"><div class="ppid-card-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/></svg></div><h3 class="ppid-card-title">Rencana Kerja</h3><p class="ppid-card-text">Rencana kerja tahunan dan anggaran</p></div>
                    <div class="ppid-card"><div class="ppid-card-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 20V10M12 20V4M6 20v-6"/></svg></div><h3 class="ppid-card-title">Laporan Kinerja</h3><p class="ppid-card-text">LAKIP dan laporan kinerja</p></div>
                </div>
            </section>
        </div>
        @include('ppid.partials.footer')
    </main></x-layouts.ppid-layout>
