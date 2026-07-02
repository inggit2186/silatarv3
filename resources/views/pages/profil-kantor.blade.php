<x-layouts.app title="Profil Kantor - Kankemenag Tanah Datar">
    <!-- Profil Menu Sidebar -->
    <x-profil-menu
        profil-url="{{ route('profil-kantor') }}"
        sejarah-url="{{ route('sejarah') }}"
        struktur-url="{{ route('struktur-organisasi') }}"
        unit-url="{{ route('satuan-kerja') }}?tab=kua"
    />

    <div style="padding-left: 5rem;">
        <main class="neo-mirai">
            <x-layouts.site-header />

            <!-- Hero Section -->
            <section style="background: linear-gradient(135deg, rgba(250,248,245,0.92) 0%, rgba(250,248,245,0.85) 50%, rgba(250,248,245,0.92) 100%), url('/assets/img/template/bg2.webp') center/cover no-repeat; padding: 2rem 2rem 4rem;">
                <div style="max-width: 48rem; margin: 0 auto; text-align: center; padding-top: 80px;">
                    <!-- Header -->
                    <div style="margin-bottom: 3rem;">
                        <div style="display: inline-flex; align-items: center; gap: 0.75rem; padding: 0.5rem 1.25rem; border: 1px solid var(--gold); background: oklch(68% 0.145 74 / 0.1); border-radius: 9999px; margin-bottom: 1.5rem;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--gold)" stroke-width="1.5">
                                <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/>
                                <circle cx="12" cy="7" r="4"/>
                            </svg>
                            <span style="font-family: var(--font-mono); font-size: 0.8rem; font-weight: 600; text-transform: uppercase; color: var(--gold);">Profil Kantor</span>
                        </div>
                        <h1 style="font-family: var(--font-display); font-size: clamp(2rem, 5vw, 3rem); font-weight: 600; color: var(--ink); margin: 0 0 1rem;">
                            Kantor <span style="color: var(--gold);">Kementerian Agama</span>
                        </h1>
                        <p style="font-family: var(--font-mono); font-size: 1rem; color: var(--ink-soft); text-transform: uppercase;">Kabupaten Tanah Datar</p>
                    </div>

                    <!-- Decorative Line -->
                    <div style="display: flex; align-items: center; justify-content: center; gap: 1rem;">
                        <div style="width: 6rem; height: 1px; background: linear-gradient(90deg, transparent, var(--gold));"></div>
                        <div style="width: 0.75rem; height: 0.75rem; background: var(--gold); border-radius: 50%;"></div>
                        <div style="width: 6rem; height: 1px; background: linear-gradient(90deg, var(--gold), transparent);"></div>
                    </div>
                </div>
            </section>

            <!-- Section Divider -->
            <div class="section-divider wave-rounded"></div>

            <!-- Content Section -->
            <section class="page-content">

                <!-- Visi Section -->
                <div style="max-width: 64rem; margin: 0 auto;">
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 3rem; align-items: center;">

                        <!-- Left: Icon & Title -->
                        <div>
                            <!-- Large Geometric Icon -->
                            <div style="position: relative; width: 12rem; height: 12rem; margin: 0 auto 2rem;">
                                <div style="position: absolute; inset: 0; background: linear-gradient(135deg, var(--gold) 0%, transparent 100%); border-radius: 1.5rem; transform: rotate(6deg); opacity: 0.2;"></div>
                                <div style="position: absolute; inset: 0; background: linear-gradient(315deg, var(--gold) 0%, transparent 100%); border-radius: 1.5rem; transform: rotate(-3deg); opacity: 0.15;"></div>
                                <div style="position: relative; width: 100%; height: 100%; border: 2px solid var(--gold); border-radius: 1.5rem; display: flex; align-items: center; justify-content: center; background: var(--paper-soft);">
                                    <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="var(--gold)" stroke-width="1">
                                        <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </div>
                            </div>

                            <h2 style="font-family: var(--font-display); font-size: 2rem; font-weight: 600; color: var(--ink); margin: 0;">Visi</h2>
                            <div style="width: 4rem; height: 4px; background: linear-gradient(90deg, var(--gold), oklch(72% 0.15 145)); border-radius: 2px; margin-top: 0.5rem;"></div>
                        </div>

                        <!-- Right: Content -->
                        <div>
                            <p style="font-family: var(--font-display); font-size: 1.25rem; color: var(--ink); line-height: 1.7; margin: 0 0 1.5rem;">
                                "Terwujudnya Madrasah dan KUA yang <strong style="color: var(--gold);">Professional</strong>, <strong style="color: var(--gold);">Modern</strong>, dan <strong style="color: var(--gold);">Integratif</strong> dalam Jajaran Kantor Kementerian Agama Kabupaten Tanah Datar"
                            </p>

                            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem;">
                                <div style="padding: 1rem; border: 1px solid var(--line); border-radius: 0.75rem; text-align: center; background: var(--paper-soft);">
                                    <span style="font-family: var(--font-mono); font-size: 1.25rem; font-weight: 700; color: var(--gold);">Professional</span>
                                    <p style="font-family: var(--font-mono); font-size: 0.65rem; color: var(--ink-soft); text-transform: uppercase; margin: 0.25rem 0 0;">Berkualitas</p>
                                </div>
                                <div style="padding: 1rem; border: 1px solid var(--line); border-radius: 0.75rem; text-align: center; background: var(--paper-soft);">
                                    <span style="font-family: var(--font-mono); font-size: 1.25rem; font-weight: 700; color: var(--gold);">Modern</span>
                                    <p style="font-family: var(--font-mono); font-size: 0.65rem; color: var(--ink-soft); text-transform: uppercase; margin: 0.25rem 0 0;">Berbasis Teknologi</p>
                                </div>
                                <div style="padding: 1rem; border: 1px solid var(--line); border-radius: 0.75rem; text-align: center; background: var(--paper-soft);">
                                    <span style="font-family: var(--font-mono); font-size: 1.25rem; font-weight: 700; color: var(--gold);">Integratif</span>
                                    <p style="font-family: var(--font-mono); font-size: 0.65rem; color: var(--ink-soft); text-transform: uppercase; margin: 0.25rem 0 0;">Terintegrasi</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </section>

            <!-- Footer -->
            <footer class="site-footer">
                <a class="brand-lockup brand-lockup-small" href="{{ url("/") }}" aria-label="SILATAR home">
                    <span class="brand-mark" aria-hidden="true"><span></span></span>
                    <span class="brand-word"><span>SILATAR</span><span>V2</span></span>
                </a>
                <p>Portal Layanan Digital Kementerian Agama Tanah Datar</p>
                <nav aria-label="Footer navigation">
                    <a href="{{ url("/") }}">Beranda</a>
                    <a href="{{ route('pelayanan') }}">Pelayanan</a>
                    <a href="{{ route('satuan-kerja') }}">Unit Kerja</a>
                    <a href="{{ route('news.index') }}">Berita</a>
                </nav>
                <div class="footer-copyright"><span>&copy; {{ date("Y") }} SILATAR - Kementerian Agama Tanah Datar</span></div>
            </footer>
        </main>
    </div>
</x-layouts.app>
