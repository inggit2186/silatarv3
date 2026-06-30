<x-layouts.ppid-layout title="Visi Misi - PPID">
    <x-ppid.nav />

    <main class="ppid-content">
        <div class="ppid-page">
            <div class="ppid-page-breadcrumb"><a href="{{ route('ppid') }}">PPID</a><span>/</span><span>Profil PPID</span><span>/</span><span>Visi Misi</span></div>
            <div class="ppid-page-header" data-reveal>
                <h1 class="ppid-page-title">Visi dan Misi</h1>
                <p class="ppid-page-subtitle">Visi dan Misi PPID Kemenag Kabupaten Tanah Datar</p>
            </div>

            <section class="ppid-section" data-reveal>
                <h2 class="ppid-section-title">Visi</h2>
                <div class="ppid-info-box" style="background: linear-gradient(135deg, oklch(8% 0.15 190 / 0.1), oklch(8% 0.15 190 / 0.05)); border-radius: 1.5rem; padding: 2rem; text-align: center;">
                    <p style="font-family: var(--font-display); font-size: clamp(1.1rem, 2vw, 1.4rem); font-weight: 500; color: var(--ink); line-height: 1.6; margin: 0;">"Terwujudnya Government Transformation melalui optimalisasi keterbukaan informasi publik yang profesional, transparan, dan akuntabel untuk mendukung good governance di Kementerian Agama Kabupaten Tanah Datar."</p>
                </div>
            </section>

            <section class="ppid-section" data-reveal>
                <h2 class="ppid-section-title">Misi</h2>
                <div class="ppid-grid" style="grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));">
                    <div class="ppid-card">
                        <div class="ppid-card-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg></div>
                        <h3 class="ppid-card-title">Transparansi Optimal</h3>
                        <p class="ppid-card-text">Melaksanakan keterbukaan informasi publik secara optimal dan bertanggung jawab</p>
                    </div>
                    <div class="ppid-card">
                        <div class="ppid-card-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"/></svg></div>
                        <h3 class="ppid-card-title">Sistem Terintegrasi</h3>
                        <p class="ppid-card-text">Mengembangkan sistem informasi dan dokumentasi yang terintegrasi dan akuntabel</p>
                    </div>
                    <div class="ppid-card">
                        <div class="ppid-card-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg></div>
                        <h3 class="ppid-card-title">Pelayanan Prima</h3>
                        <p class="ppid-card-text">Meningkatkan kualitas dan kapasitas pelayanan informasi publik</p>
                    </div>
                    <div class="ppid-card">
                        <div class="ppid-card-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/></svg></div>
                        <h3 class="ppid-card-title">Koordinasi Efektif</h3>
                        <p class="ppid-card-text">Membangun koordinasi yang efektif antar unit kerja dalam pengelolaan informasi</p>
                    </div>
                </div>
            </section>
        </div>
        @include('ppid.partials.footer')
    </main></x-layouts.ppid-layout>
