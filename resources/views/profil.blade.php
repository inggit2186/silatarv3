<x-layouts.app title="Profil - SILATAR">
    <main class="neo-mirai">
        <x-layouts.site-header />

        <!-- Hero Section -->
        <section class="hero-page" style="background-image: url('/assets/img/template/bg2.webp'); background-size: cover; background-position: center center; padding: 2rem 2rem 4rem;">
            <div style="max-width: 28rem; margin: 0 auto; text-align: center; padding-top: 80px;">
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
            <div style="max-width: 60rem; margin: 0 auto; display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem;">
                @foreach($menuItems as $item)
                    <a href="{{ $item['route'] ? route($item['route']) : '#' }}" class="neo-card" style="text-decoration: none; display: flex; flex-direction: column; overflow: hidden; padding: 0; transition: border-color 180ms, box-shadow 240ms;" onmouseover="this.style.borderColor='var(--gold)'; this.style.boxShadow='0 8px 30px oklch(18% 0.03 76 / 0.08)'" onmouseout="this.style.borderColor='var(--line)'; this.style.boxShadow='none'">
                        <div style="aspect-ratio: 4/3; display: flex; align-items: center; justify-content: center; background: var(--paper-soft);">
                            <img src="{{ asset('assets/img/ikon/' . $item['icon']) }}" alt="" style="width: 4rem; height: 4rem; object-fit: contain; opacity: 0.6;" onerror="this.style.display='none'">
                        </div>
                        <div style="padding: 1.5rem; text-align: center; flex: 1; display: flex; flex-direction: column; justify-content: center;">
                            <h3 style="font-family: var(--font-display); font-size: 1rem; font-weight: 600; color: var(--ink); margin: 0;">{{ $item['title'] }}</h3>
                            <span style="display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; margin-top: 1rem; padding: 0.5rem 1rem; background: var(--gold); color: var(--night); font-family: var(--font-mono); font-size: 0.7rem; font-weight: 600; text-transform: uppercase;">
                                Lihat Detail
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h12m-5-5 5 5-5 5"/></svg>
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
