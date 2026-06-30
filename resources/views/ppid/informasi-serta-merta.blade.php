<x-layouts.ppid-layout title="Informasi Serta Merta - PPID">
    <x-ppid.nav />

    <main class="ppid-content">
        <div class="ppid-page">
            <div class="ppid-page-breadcrumb"><a href="{{ route('ppid') }}">PPID</a><span>/</span><span>Daftar Informasi</span><span>/</span><span>Serta Merta</span></div>
            <div class="ppid-page-header" data-reveal>
                <h1 class="ppid-page-title">Informasi Serta Merta</h1>
                <p class="ppid-page-subtitle">Informasi yang harus segera diumumkan</p>
            </div>

            <section class="ppid-section" data-reveal>
                <div style="background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%); border-radius: 1.5rem; padding: 2rem; color: white; margin-bottom: 2rem;">
                    <h2 style="font-family: var(--font-display); font-size: 1.25rem; font-weight: 600; margin: 0 0 0.5rem;">Informasi Serta Merta</h2>
                    <p style="opacity: 0.95; margin: 0;">Informasi yang harus segera diumumkan karena menyangkut hal yang dapat mengancam kehidupan dan ketertiban umum.</p>
                </div>
            </section>

            <section class="ppid-section" data-reveal>
                <h2 class="ppid-section-title">Daftar Informasi</h2>
                <ul class="ppid-list">
                    <li>Informasi tentang bencana alam dan wabah penyakit</li>
                    <li>Gangguan layanan publik yang affecting masyarakat</li>
                    <li>Perubahan kebijakan pemerintah</li>
                    <li>Informasi tentang kegiatan ibadah dan hari besar</li>
                    <li>Pengumuman Pemilihan dan hasilnya</li>
                </ul>
            </section>
        </div>
        @include('ppid.partials.footer')
    </main></x-layouts.ppid-layout>
