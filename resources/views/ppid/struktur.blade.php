<x-layouts.ppid-layout title="Struktur Kelembagaan - PPID">
    <x-ppid.nav />

    <main class="ppid-content">
        <div class="ppid-page">
            <div class="ppid-page-breadcrumb"><a href="{{ route('ppid') }}">PPID</a><span>/</span><span>Profil PPID</span><span>/</span><span>Struktur</span></div>
            <div class="ppid-page-header" data-reveal>
                <h1 class="ppid-page-title">Struktur Kelembagaan PPID</h1>
                <p class="ppid-page-subtitle">Susunan organisasi PPID</p>
            </div>

            <section class="ppid-section" data-reveal>
                <div style="background: white; border: 1px solid oklch(58% 0.06 76 / 0.15); border-radius: 1.5rem; padding: 2rem; text-align: center; margin-bottom: 2rem;">
                    <div style="display: inline-block; background: linear-gradient(135deg, oklch(8% 0.15 190 / 0.1), oklch(8% 0.15 190 / 0.05)); border-radius: 1rem; padding: 1.5rem 2rem;"><div style="font-family: var(--font-display); font-size: 1.1rem; font-weight: 600; color: var(--ink);">Atasan PPID</div><div style="font-family: var(--font-mono); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.1em; color: var(--ppid-primary);">Kepala Kantor</div></div>
                    <div style="width: 2px; height: 30px; background: var(--ppid-primary); margin: 0 auto;"></div>
                    <div style="display: inline-block; background: var(--ppid-primary); border-radius: 1rem; padding: 1.5rem 2rem; color: white;"><div style="font-family: var(--font-display); font-size: 1.1rem; font-weight: 600;">PPID</div><div style="font-family: var(--font-mono); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.1em; opacity: 0.9;">Pejabat Pengelola</div></div>
                    <div style="width: 2px; height: 30px; background: var(--ppid-primary); margin: 0 auto;"></div>
                    <div style="display: inline-block; background: linear-gradient(135deg, oklch(8% 0.15 190 / 0.1), oklch(8% 0.15 190 / 0.05)); border-radius: 1rem; padding: 1.5rem 2rem;"><div style="font-family: var(--font-display); font-size: 1.1rem; font-weight: 600; color: var(--ink);">PPID Pelaksana</div><div style="font-family: var(--font-mono); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.1em; color: var(--ppid-primary);">Bidang-bidang</div></div>
                </div>
            </section>

            <section class="ppid-section" data-reveal>
                <h2 class="ppid-section-title">Struktur Organisasi</h2>
                <div class="ppid-grid">
                    <div class="ppid-card" style="text-align: center;"><div class="ppid-card-icon" style="margin: 0 auto 1rem;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></div><h3 class="ppid-card-title">Atasan PPID</h3><p class="ppid-card-text">Kepala Kantor Kemenag</p></div>
                    <div class="ppid-card" style="text-align: center;"><div class="ppid-card-icon" style="margin: 0 auto 1rem;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg></div><h3 class="ppid-card-title">PPID Utama</h3><p class="ppid-card-text">Pejabat Pengelola</p></div>
                    <div class="ppid-card" style="text-align: center;"><div class="ppid-card-icon" style="margin: 0 auto 1rem;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg></div><h3 class="ppid-card-title">Tim Pertimbangan</h3><p class="ppid-card-text">Tim penasehat</p></div>
                </div>
            </section>
        </div>
        @include('ppid.partials.footer')
    </main></x-layouts.ppid-layout>
