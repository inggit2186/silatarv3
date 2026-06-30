<x-layouts.ppid-layout title="Kegiatan - Gallery PPID">
    <x-ppid.nav />

    <main class="ppid-content">
        <div class="ppid-page">
            <div class="ppid-page-breadcrumb"><a href="{{ route('ppid') }}">PPID</a><span>/</span><span>Gallery</span><span>/</span><span>Kegiatan</span></div>
            <div class="ppid-page-header" data-reveal>
                <h1 class="ppid-page-title">Kegiatan</h1>
                <p class="ppid-page-subtitle">Dokumentasi kegiatan PPID</p>
            </div>

            <section class="ppid-section" data-reveal>
                <div class="ppid-grid">
                    <div class="ppid-card" style="padding: 0; overflow: hidden;"><div style="aspect-ratio: 16/10; background: linear-gradient(135deg, oklch(68% 0.145 74 / 0.2), oklch(68% 0.145 74 / 0.1)); display: flex; align-items: center; justify-content: center;"><svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="color: var(--gold);"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/></svg></div><div style="padding: 1.25rem;"><span style="font-family: var(--font-mono); font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.1em; color: var(--ppid-primary);">Januari 2024</span><h3 class="ppid-card-title">Sosialisasi PPID</h3><p class="ppid-card-text">Sosialisasi kepada masyarakat</p></div></div>
                    <div class="ppid-card" style="padding: 0; overflow: hidden;"><div style="aspect-ratio: 16/10; background: linear-gradient(135deg, oklch(68% 0.145 74 / 0.2), oklch(68% 0.145 74 / 0.1)); display: flex; align-items: center; justify-content: center;"><svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="color: var(--gold);"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/></svg></div><div style="padding: 1.25rem;"><span style="font-family: var(--font-mono); font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.1em; color: var(--ppid-primary);">Maret 2024</span><h3 class="ppid-card-title">Workshop</h3><p class="ppid-card-text">Pelatihan manajemen informasi</p></div></div>
                    <div class="ppid-card" style="padding: 0; overflow: hidden;"><div style="aspect-ratio: 16/10; background: linear-gradient(135deg, oklch(68% 0.145 74 / 0.2), oklch(68% 0.145 74 / 0.1)); display: flex; align-items: center; justify-content: center;"><svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="color: var(--gold);"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div><div style="padding: 1.25rem;"><span style="font-family: var(--font-mono); font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.1em; color: var(--ppid-primary);">Mei 2024</span><h3 class="ppid-card-title">Evaluasi Kinerja</h3><p class="ppid-card-text">Evaluasi semester pertama</p></div></div>
                </div>
            </section>
        </div>
        @include('ppid.partials.footer')
    </main></x-layouts.ppid-layout>
