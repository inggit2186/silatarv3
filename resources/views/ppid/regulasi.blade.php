<x-layouts.ppid-layout title="Regulasi - PPID">
    <x-ppid.nav />

    <main class="ppid-content">
        <div class="ppid-page">
            <div class="ppid-page-breadcrumb"><a href="{{ route('ppid') }}">PPID</a><span>/</span><span>Regulasi</span></div>
            <div class="ppid-page-header" data-reveal>
                <h1 class="ppid-page-title">Regulasi</h1>
                <p class="ppid-page-subtitle">Peraturan Perundang-undangan Keterbukaan Informasi Publik</p>
            </div>

            <section class="ppid-section" data-reveal>
                <h2 class="ppid-section-title">Peraturan Perundang-undangan</h2>
                <div class="ppid-grid" style="grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));">
                    <div class="ppid-card">
                        <div class="ppid-card-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/></svg></div>
                        <h3 class="ppid-card-title">UU No. 14 Tahun 2008</h3>
                        <p class="ppid-card-text">Undang-Undang tentang Keterbukaan Informasi Publik</p>
                        <a href="#" class="ppid-btn ppid-btn-secondary" style="margin-top: 1rem; font-size: 0.65rem;"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/></svg>Unduh</a>
                    </div>
                    <div class="ppid-card">
                        <div class="ppid-card-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/></svg></div>
                        <h3 class="ppid-card-title">PP No. 61 Tahun 2010</h3>
                        <p class="ppid-card-text">Peraturan Pemerintah tentang Pelaksanaan UU Keterbukaan Informasi Publik</p>
                        <a href="#" class="ppid-btn ppid-btn-secondary" style="margin-top: 1rem; font-size: 0.65rem;"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/></svg>Unduh</a>
                    </div>
                    <div class="ppid-card">
                        <div class="ppid-card-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/></svg></div>
                        <h3 class="ppid-card-title">PermenPAN RB No. 30 Tahun 2018</h3>
                        <p class="ppid-card-text">Pedoman Pengelolaan Informasi Publik</p>
                        <a href="#" class="ppid-btn ppid-btn-secondary" style="margin-top: 1rem; font-size: 0.65rem;"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/></svg>Unduh</a>
                    </div>
                </div>
            </section>

            <section class="ppid-section" data-reveal>
                <h2 class="ppid-section-title">Regulasi Lainnya</h2>
                <div class="ppid-grid" style="grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));">
                    <div class="ppid-card">
                        <div class="ppid-card-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/></svg></div>
                        <h3 class="ppid-card-title">Permenkumham RI</h3>
                        <p class="ppid-card-text">Peraturan Menteri Hukum dan HAM tentang Pedoman Pengelolaan Informasi</p>
                    </div>
                    <div class="ppid-card">
                        <div class="ppid-card-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/></svg></div>
                        <h3 class="ppid-card-title">SE Mensos RI</h3>
                        <p class="ppid-card-text">Surat Edaran tentang Transparansi dan Akuntabilitas</p>
                    </div>
                    <div class="ppid-card">
                        <div class="ppid-card-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/></svg></div>
                        <h3 class="ppid-card-title">Permen Agama</h3>
                        <p class="ppid-card-text">Peraturan Menteri Agama tentang PPID Kemenag</p>
                    </div>
                </div>
            </section>
        </div>
        @include('ppid.partials.footer')
    </main></x-layouts.ppid-layout>
