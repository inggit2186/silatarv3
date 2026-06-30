<x-layouts.ppid-layout title="Laporan Layanan - PPID">
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
            <div class="ppid-page-breadcrumb"><a href="{{ route('ppid') }}">PPID</a><span>/</span><span>Standar Layanan</span><span>/</span><span>Laporan</span></div>
            <div class="ppid-page-header" data-reveal>
                <h1 class="ppid-page-title">Laporan Layanan</h1>
                <p class="ppid-page-subtitle">Rekapitulasi Pelayanan Informasi</p>
            </div>

            <section class="ppid-section" data-reveal>
                <h2 class="ppid-section-title">Statistik</h2>
                <div class="ppid-stats">
                    <div class="ppid-stat"><div class="ppid-stat-value">156</div><div class="ppid-stat-label">Total Permohonan</div></div>
                    <div class="ppid-stat"><div class="ppid-stat-value">98%</div><div class="ppid-stat-label">Terselesaikan</div></div>
                    <div class="ppid-stat"><div class="ppid-stat-value">24h</div><div class="ppid-stat-label">Rata-rata Waktu</div></div>
                    <div class="ppid-stat"><div class="ppid-stat-value">100%</div><div class="ppid-stat-label">Kepuasan</div></div>
                </div>
            </section>

            <section class="ppid-section" data-reveal>
                <h2 class="ppid-section-title">Rekap Bulanan</h2>
                <div style="background: white; border: 1px solid oklch(58% 0.06 76 / 0.15); border-radius: 1.5rem; overflow: hidden;">
                    <table class="ppid-table" style="border-radius: 0;">
                        <thead><tr><th>Bulan</th><th>Permohonan</th><th>Diproses</th><th>Selesai</th></tr></thead>
                        <tbody>
                            <tr><td>Januari</td><td>12</td><td>12</td><td>12</td></tr>
                            <tr><td>Februari</td><td>15</td><td>15</td><td>14</td></tr>
                            <tr><td>Maret</td><td>18</td><td>18</td><td>18</td></tr>
                            <tr><td>April</td><td>14</td><td>14</td><td>14</td></tr>
                            <tr><td>Mei</td><td>20</td><td>20</td><td>19</td></tr>
                            <tr><td>Juni</td><td>16</td><td>16</td><td>16</td></tr>
                        </tbody>
                    </table>
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
