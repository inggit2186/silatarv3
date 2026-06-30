<x-layouts.ppid-layout title="Informasi Setiap Saat - PPID">
    <x-ppid.nav />

    <main class="ppid-content">
        <div class="ppid-page">
            <div class="ppid-page-breadcrumb"><a href="{{ route('ppid') }}">PPID</a><span>/</span><span>Daftar Informasi</span><span>/</span><span>Setiap Saat</span></div>
            <div class="ppid-page-header" data-reveal>
                <h1 class="ppid-page-title">Informasi Tersedia Setiap Saat</h1>
                <p class="ppid-page-subtitle">Informasi yang dapat diakses kapan saja</p>
            </div>

            <section class="ppid-section" data-reveal>
                <h2 class="ppid-section-title">Daftar Informasi</h2>
                <div class="ppid-grid">
                    <div class="ppid-card"><div class="ppid-card-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/></svg></div><h3 class="ppid-card-title">Daftar Informasi Publik</h3><p class="ppid-card-text">Seluruh informasi yang tersedia</p></div>
                    <div class="ppid-card"><div class="ppid-card-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/></svg></div><h3 class="ppid-card-title">Informasi Berkala</h3><p class="ppid-card-text">Informasi diumumkan berkala</p></div>
                    <div class="ppid-card"><div class="ppid-card-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 20V10M12 20V4M6 20v-6"/></svg></div><h3 class="ppid-card-title">Statistik Layanan</h3><p class="ppid-card-text">Data statistik pelayanan</p></div>
                </div>
            </section>
        </div>
        @include('ppid.partials.footer')
    </main></x-layouts.ppid-layout>
