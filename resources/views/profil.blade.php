<x-layouts.app title="Profil - SILATAR">
    <main class="neo-mirai">
        <x-layouts.site-header />

        <!-- Hero Section -->
        <section class="hero-page" style="background-image: url('/assets/img/template/bg2.webp'); background-size: cover; background-position: center top; padding: 120px 2rem 4rem;">
            <div style="max-width: 28rem; margin: 0 auto;">
                <!-- Avatar -->
                <div style="width: 6rem; height: 6rem; margin: 0 auto 1.5rem; border-radius: 0.75rem; overflow: hidden; border: 2px solid var(--gold); display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, var(--gold) 0 38%, var(--sun) 38% 58%, var(--night-soft) 58%);">
                    @if($user->pp && $user->nomor_induk)
                        <img src="{{ asset('assets/img/users/' . $user->nomor_induk . '/' . $user->pp) }}" alt="{{ $user->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        <span style="font-family: var(--font-mono); font-size: 1.5rem; font-weight: 700; color: var(--night);">{{ substr($user->name, 0, 2) }}</span>
                    @endif
                </div>
                <h1 style="font-family: var(--font-display); font-size: clamp(1.5rem, 3vw, 2.25rem); font-weight: 600; color: var(--ink); margin: 0 0 0.25rem;">{{ $user->name }}</h1>
                <p style="font-family: var(--font-mono); font-size: 0.85rem; color: var(--gold); margin: 0;">{{ $user->nomor_induk }}</p>
                <p style="color: var(--ink-soft); font-size: 0.85rem; margin: 0.5rem 0 0;">
                    {{ $user->pekerjaan ?? '-' }}
                    @if($userDept)
                        <span style="margin: 0 0.5rem; color: var(--line);">|</span>
                        {{ $userDept }}
                    @endif
                </p>
            </div>
        </section>

        <!-- Section Divider -->
        <div class="section-divider wave-rounded"></div>

        <!-- Menu Section -->
        <section class="page-content" style="padding-top: 0;">
            <div class="neo-grid neo-grid-3" style="max-width: 60rem; margin: 0 auto;">
                @foreach($menuItems as $item)
                    <a href="{{ $item['route'] ? route($item['route']) : '#' }}" class="neo-service-card" style="text-decoration: none;">
                        <div class="neo-service-cover" style="aspect-ratio: 4/3; display: flex; align-items: center; justify-content: center; background: var(--paper-soft);">
                            <img src="{{ asset('assets/img/ikon/' . $item['icon']) }}" alt="" style="width: 4rem; height: 4rem; object-fit: contain; opacity: 0.6;" onerror="this.style.display='none'">
                            <div class="neo-service-cover-overlay"></div>
                        </div>
                        <div class="neo-service-body" style="text-align: center;">
                            <h3 class="neo-service-title">{{ $item['title'] }}</h3>
                            <span class="neo-btn" style="margin-top: 1rem; width: 100%; justify-content: center;">
                                Lihat Detail →
                            </span>
                        </div>
                    </a>
                @endforeach
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
</x-layouts.app>
