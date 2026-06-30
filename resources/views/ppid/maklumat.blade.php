<x-layouts.ppid-layout title="Maklumat Pelayanan - PPID">
    <x-ppid.nav />

    <main class="ppid-content">
        <div class="ppid-page">
            <div class="ppid-page-breadcrumb"><a href="{{ route('ppid') }}">PPID</a><span>/</span><span>Standar Layanan</span><span>/</span><span>Maklumat</span></div>
            <div class="ppid-page-header" data-reveal>
                <h1 class="ppid-page-title">Maklumat Pelayanan</h1>
                <p class="ppid-page-subtitle">Maklumat Pelayanan Informasi Publik PPID</p>
            </div>

            <section class="ppid-section" data-reveal>
                <div style="background: linear-gradient(135deg, var(--ppid-primary) 0%, var(--ppid-primary-dark) 100%); border-radius: 1.5rem; padding: 2.5rem; color: white; text-align: center; margin-bottom: 2rem;">
                    <h2 style="font-family: var(--font-display); font-size: 1.5rem; font-weight: 600; margin: 0 0 1rem;">Maklumat Pelayanan</h2>
                    <p style="font-size: 1.1rem; line-height: 1.7; max-width: 700px; margin: 0 auto; opacity: 0.95;">Kami PPID Kantor Kementerian Agama Kabupaten Tanah Datar akan memberikan pelayanan informasi publik sesuai dengan standar pelayanan yang telah ditetapkan.</p>
                </div>
            </section>

            <section class="ppid-section" data-reveal>
                <h2 class="ppid-section-title">Komitmen Pelayanan</h2>
                <div class="ppid-grid">
                    <div class="ppid-card">
                        <div class="ppid-card-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div>
                        <h3 class="ppid-card-title">Cepat dan Tepat</h3>
                        <p class="ppid-card-text">Melayani permohonan informasi dengan cepat, tepat, dan akurat</p>
                    </div>
                    <div class="ppid-card">
                        <div class="ppid-card-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg></div>
                        <h3 class="ppid-card-title">Aman dan Rahasia</h3>
                        <p class="ppid-card-text">Menjaga kerahasiaan informasi yang dikecualikan</p>
                    </div>
                    <div class="ppid-card">
                        <div class="ppid-card-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/></svg></div>
                        <h3 class="ppid-card-title">Sesuai Jadwal</h3>
                        <p class="ppid-card-text">Melayani pada jam kerja yang telah ditetapkan</p>
                    </div>
                    <div class="ppid-card">
                        <div class="ppid-card-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/></svg></div>
                        <h3 class="ppid-card-title">Ramah dan Sopan</h3>
                        <p class="ppid-card-text">Melayani dengan ramah, sopan, dan profesional</p>
                    </div>
                </div>
            </section>

            <section class="ppid-section" data-reveal>
                <h2 class="ppid-section-title">Janji Layanan</h2>
                <ul class="ppid-list">
                    <li>Melaksanakan pelayanan informasi publik dengan profesional dan transparan</li>
                    <li>Memberikan akses informasi yang akurat dan dapat dipertanggungjawabkan</li>
                    <li>Melayani permohonan informasi sesuai prosedur yang berlaku</li>
                    <li>Menyelesaikan permohonan informasi sesuai jangka waktu yang telah ditetapkan</li>
                    <li>Memberikan informasi secara cuma-cuma (gratis)</li>
                    <li>Menyediakan informasi melalui berbagai saluran yang mudah diakses</li>
                </ul>
            </section>
        </div>
        @include('ppid.partials.footer')
    </main></x-layouts.ppid-layout>
