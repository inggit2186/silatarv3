<x-layouts.ppid-layout title="Pengaduan - PPID">
    <header class="site-header ppid-header">
        <div class="ppid-header-top">
            <a class="brand-lockup" href="{{ url("/") }}"><span class="brand-mark"><span></span></span><span class="brand-word"><span>SILATAR</span><span>V2</span></span></a>
            <div class="ppid-header-actions">
                <a href="{{ route('ppid.formulir-permohonan') }}" class="ppid-header-badge">Ajukan Permohonan</a>
                <button class="ppid-nav-toggle-btn" type="button" id="mobileMenuToggle"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="12" x2="21" y2="12"/></svg></button>
            </div>
        </div>
        <x-ppid.nav />
    </header>
    <div class="ppid-mobile-menu" id="ppidMobileMenu"><ul class="ppid-mobile-nav"><li class="ppid-mobile-nav-item"><a href="{{ route('ppid') }}" class="ppid-mobile-nav-link">Beranda</a></li></ul></div>

    <main class="ppid-content">
        <div class="ppid-page">
            <div class="ppid-page-breadcrumb"><a href="{{ route('ppid') }}">PPID</a><span>/</span><span>Pengaduan</span></div>
            <div class="ppid-page-header" data-reveal>
                <h1 class="ppid-page-title">Pengaduan</h1>
                <p class="ppid-page-subtitle">Sampaikan pengaduan terkait pelayanan</p>
            </div>

            <section class="ppid-section" data-reveal>
                <div class="ppid-section-content"><p>Jika tidak puas dengan pelayanan, silakan sampaikan pengaduan.</p></div>
            </section>

            <section class="ppid-section" data-reveal>
                <div style="background: white; border: 1px solid oklch(58% 0.06 76 / 0.15); border-radius: 1.5rem; padding: 2rem;">
                    <form>
                        <div class="ppid-form-group"><label class="ppid-form-label">Nama</label><input type="text" class="ppid-form-input" placeholder="Nama lengkap" required></div>
                        <div class="ppid-form-group"><label class="ppid-form-label">Email</label><input type="email" class="ppid-form-input" placeholder="email@contoh.com" required></div>
                        <div class="ppid-form-group"><label class="ppid-form-label">Subjek</label><input type="text" class="ppid-form-input" placeholder="Subjek pengaduan" required></div>
                        <div class="ppid-form-group"><label class="ppid-form-label">Uraian</label><textarea class="ppid-form-input" rows="5" placeholder="Jelaskan pengaduan" required></textarea></div>
                        <div style="display: flex; gap: 1rem; margin-top: 1.5rem;"><button type="submit" class="ppid-btn ppid-btn-primary"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/></svg>Kirim</button></div>
                    </form>
                </div>
            </section>
        </div>
        @include('ppid.partials.footer')
    </main>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var mobileToggle = document.getElementById('mobileMenuToggle');
            var mobileMenu = document.getElementById('ppidMobileMenu');
            if (mobileToggle && mobileMenu) { mobileToggle.addEventListener('click', function() { mobileMenu.classList.toggle('is-open'); }); }
        });
    </script>
</x-layouts.ppid-layout>
