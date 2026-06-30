<x-layouts.app title="Kankemenag Kab.Tanah Datar">
    <main class="neo-mirai">

        <!-- Fixed Header -->
        <header class="site-header" data-reveal="">
            <a class="brand-lockup" href="{{ url("/") }}" aria-label="SILATAR home">
                <span class="brand-mark" aria-hidden="true"><span></span></span>
                <span class="brand-word"><span>SILATAR</span><span>V2</span></span>
            </a>

            <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="mobile-nav" id="menuToggle"><span>Menu</span><i aria-hidden="true"></i></button>

            <nav class="site-nav" id="site-nav" aria-label="Primary navigation">
                <a href="{{ url("/") }}">Beranda</a>
                <a href="{{ route('news.index') }}">Berita</a>
                <a href="{{ route('satuan-kerja') }}">Unit Kerja</a>
                <a href="{{ route('pelayanan') }}" class="nav-layanan">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Layanan
                </a>
                <a href="{{ route('ppid') }}">PPID</a>
                <a href="{{ url("/#kontak") }}">Kontak</a>
            </nav>

            @auth
                <div class="user-menu-wrapper" x-data="{ open: false }" @click.away="open = false">
                    <button type="button" class="user-menu-btn" @click="open = !open" :aria-expanded="open">
                        @if(Auth::user()->pp && file_exists(public_path('storage/' . Auth::user()->pp)))
                            <img src="{{ asset('storage/' . Auth::user()->pp) }}" alt="PP" class="user-pp">
                        @else
                            <div class="user-pp-placeholder">{{ substr(Auth::user()->name, 0, 1) }}</div>
                        @endif
                        <div class="user-info">
                            <span class="user-name">{{ Auth::user()->name }}</span>
                            <span class="user-role">{{ Auth::user()->pekerjaan ?? 'Pegawai' }}</span>
                        </div>
                        <svg class="user-chevron" :class="open ? 'is-open' : ''" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
                    </button>
                    <div class="user-dropdown" x-show="open" x-transition>
                        <a href="{{ route('admin.dashboard') }}" class="user-dropdown-item">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                            Dashboard
                        </a>
                        <a href="{{ route('profil') }}" class="user-dropdown-item">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            Profil Saya
                        </a>
                        <a href="{{ route('pengajuan-saya') }}" class="user-dropdown-item">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            Pengajuan Saya
                        </a>
                        <div class="user-dropdown-divider"></div>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="user-dropdown-item user-dropdown-logout">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16,17 21,12 16,7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="ticket-pill"><span>Login</span><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h12m-5-5 5 5-5 5"/></svg></a>
            @endauth
        </header>

        <!-- Mobile Navigation -->
        <div id="mobile-nav" class="mobile-nav hidden">
            <button class="mobile-nav-close" id="menuClose" type="button"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 18L18 6M6 6l12 12"/></svg></button>
            <nav aria-label="Mobile navigation">
                <a href="{{ url("/") }}">Beranda</a>
                <a href="{{ route('news.index') }}">Berita</a>
                <a href="{{ route('satuan-kerja') }}">Unit Kerja</a>
                <a href="{{ route('pelayanan') }}">Layanan</a>
                <a href="{{ route('ppid') }}">PPID</a>
                <a href="{{ url("/#kontak") }}">Kontak</a>
                @auth
                    <a href="{{ route('admin.dashboard') }}" class="mobile-nav-cta">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="mobile-nav-cta">Login</a>
                @endauth
            </nav>
        </div>

        <!-- Hero Section -->
        <section class="hero" id="home">
            <div class="vertical-poem" aria-hidden="true"><span>Portal Layanan Digital</span><i></i></div>
            <div class="hero-copy" data-reveal="" style="--reveal-delay: 0ms;">
                <img src="{{ asset("favicon.webp") }}" alt="Logo" class="hero-logo">
                <h1 id="hero-title"><span>KANTOR</span><span>KEMENTERIAN AGAMA</span><span class="hero-subtitle">KABUPATEN TANAH DATAR</span></h1>
                <p class="hero-summary">Sistem Informasi Layanan dan Administrasi untuk Tanah Datar</p>
                <div class="hero-meta" aria-label="Layanan dan informasi">
                    <span style="display: flex; align-items: center; gap: 0.4rem;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                        Portal Layanan Elektronik
                    </span>
                    <span style="display: flex; align-items: center; gap: 0.4rem;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        Kankemenag Kab. Tanah Datar
                    </span>
                </div>
            </div>
            <figure class="hero-art" data-reveal="" style="--reveal-delay: 110ms;">
                <img src="{{ asset("assets/img/template/bg.webp") }}" alt="Kantor Kementerian Agama Tanah Datar">
                <figcaption>Layanan Prima untuk Masyarakat</figcaption>
            </figure>
        </section>

        <!-- Section Divider - Accent Hero -->
        <div class="section-divider accent-hero"></div>

        <!-- Pejabat Section - Carousel Style -->
        <section class="pejabat-section" id="pejabat" aria-labelledby="pejabat-title">
            <div class="pejabat-carousel" data-reveal="" style="--reveal-delay: 0ms;" x-data="{activeSlide:0,totalSlides:3,autoplay:null,init(){this.startAutoplay()},startAutoplay(){this.autoplay=setInterval(()=>{this.next()},5600)},stopAutoplay(){if(this.autoplay)clearInterval(this.autoplay)},next(){this.activeSlide=(this.activeSlide+1)%this.totalSlides},prev(){this.activeSlide=(this.activeSlide-1+this.totalSlides)%this.totalSlides},goTo(index){this.stopAutoplay();this.activeSlide=index;this.startAutoplay()}}" @mouseenter="stopAutoplay()" @mouseleave="startAutoplay()">
                <div class="pejabat-track" :style="'--offset:' + (activeSlide * -100) + '%'">
                    @php
                        $pejabats = [
                            ['img' => 'pejabat-01.webp'],
                            ['img' => 'pejabat-02.webp'],
                            ['img' => 'pejabat-03.webp'],
                            ['img' => 'pejabat-04.webp'],
                            ['img' => 'pejabat-05.webp'],
                            ['img' => 'pejabat-06.webp'],
                            ['img' => 'pejabat-07.webp'],
                        ];
                        $slides = array_chunk($pejabats, 3);
                    @endphp

                    @foreach($slides as $slideIndex => $slide)
                    <div class="pejabat-slide" :class="activeSlide==={{ $slideIndex }} ? 'is-active' : ''" aria-hidden="{{ $slideIndex > 0 ? 'true' : 'false' }}">
                        <ul class="pejabat-grid">
                            @foreach($slide as $pejabat)
                            <li class="pejabat-person">
                                <img src="{{ asset('assets/img/template/' . $pejabat['img']) }}" alt="Pejabat">
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endforeach
                </div>
                <div class="pejabat-controls" aria-label="Pejabat carousel controls">
                    @foreach($slides as $slideIndex => $slide)
                    <button type="button" @click="goTo({{ $slideIndex }})" class="pejabat-dot" :class="activeSlide==={{ $slideIndex }} ? 'is-active' : ''" :aria-label="'Tampilkan slide ' + ({{ $slideIndex }} + 1)"></button>
                    @endforeach
                </div>
            </div>
            <div class="pejabat-intro" data-reveal="" style="--reveal-delay: 110ms;">
                <p class="section-label" style="display: flex; align-items: center; gap: 0.5rem;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    Struktur Organisasi
                </p>
                <h2 id="pejabat-title">Pejabat Kantor</h2>
            </div>
        </section>

        <!-- Slideshow Banner -->
        @if($slideshowNews->count() > 0)
        <section class="slideshow-section" id="slideshow" x-data="{currentSlide:0,totalSlides:{{ $slideshowNews->count() }},autoplay:null,init(){this.startAutoplay()},startAutoplay(){this.autoplay=setInterval(()=>{this.next()},5000)},stopAutoplay(){if(this.autoplay)clearInterval(this.autoplay)},next(){this.currentSlide=(this.currentSlide+1)%this.totalSlides},prev(){this.currentSlide=(this.currentSlide-1+this.totalSlides)%this.totalSlides},goTo(index){this.stopAutoplay();this.currentSlide=index;this.startAutoplay()}}">
            <div class="slideshow-container" @mouseenter="stopAutoplay()" @mouseleave="startAutoplay()">
                @foreach($slideshowNews as $index => $slide)
                <div x-show="currentSlide==={{ $index }}" x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0 translate-x-full" x-transition:enter-end="opacity-100 translate-x-0" x-transition:leave="transition ease-in duration-500" x-transition:leave-start="opacity-100 translate-x-0" x-transition:leave-end="opacity-0 -translate-x-full" class="slideshow-slide">
                    @if($slide->image)<img src="{{ asset("storage/" . $slide->image) }}" alt="{{ $slide->title }}" class="slideshow-image">@else<img src="{{ asset("assets/img/template/banner-02.webp") }}" alt="{{ $slide->title }}" class="slideshow-image">@endif
                    <div class="slideshow-overlay"></div>
                    <div class="slideshow-content">
                        <span class="slideshow-badge">{{ $slide->category }}</span>
                        <h2 class="slideshow-title">{{ $slide->title }}</h2>
                        <p class="slideshow-excerpt">{{ $slide->excerpt }}</p>
                        <a href="{{ route('news.show', $slide->slug ?? $slide->id) }}" class="slideshow-cta">Baca Selengkapnya<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h12m-5-5 5 5-5 5"/></svg></a>
                    </div>
                </div>
                @endforeach
                @if($slideshowNews->count() > 1)
                <button type="button" @click="stopAutoplay();prev();startAutoplay()" class="slideshow-arrow slideshow-arrow-prev" aria-label="Previous"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 19l-7-7 7-7"/></svg></button>
                <button type="button" @click="stopAutoplay();next();startAutoplay()" class="slideshow-arrow slideshow-arrow-next" aria-label="Next"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 5l7 7-7 7"/></svg></button>
                <div class="slideshow-dots"><template x-for="i in totalSlides" :key="i"><button type="button" @click="goTo(i-1)" class="slideshow-dot" :class="currentSlide===(i-1)?'is-active':''" :aria-label="'Go to '+i"></button></template></div>
                <div class="slideshow-progress"><div class="slideshow-progress-bar" :style="'width:'+((currentSlide+1)/totalSlides*100)+'%'"></div></div>
                @endif
            </div>
        </section>
        @endif

        <!-- Section Divider - Wave Rounded -->
        <div class="section-divider wave-rounded"></div>

        <!-- Berita Terbaru Section -->
        @if($latestNews->count() > 0)
        <section class="featured-section" id="berita">
            <div class="section-intro" data-reveal="" style="--reveal-delay: 0ms;"><p class="section-label">Berita Terbaru</p><h2 id="featured-title">Agenda & Kegiatan</h2><a href="{{ route('news.index') }}" class="text-action">Lihat Semua <span><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h12m-5-5 5 5-5 5"/></svg></span></a></div>
            <div class="featured-grid" data-reveal="" style="--reveal-delay: 110ms;">
                @php $newsItems = $latestNews->take(6); @endphp
                @foreach($newsItems as $newsItem)
                <article class="featured-secondary"><a href="{{ route('news.show', $newsItem->slug ?? $newsItem->id) }}"><div class="featured-card featured-card-small">@if($newsItem->image)<img src="{{ asset("storage/" . $newsItem->image) }}" alt="{{ $newsItem->title }}" class="featured-image">@else<img src="{{ asset("assets/img/template/banner-02.webp") }}" alt="{{ $newsItem->title }}" class="featured-image">@endif<div class="featured-overlay"></div><div class="featured-content"><span class="featured-badge">{{ $newsItem->category }}</span><h4 class="featured-title">{{ $newsItem->title }}</h4></div></div></a></article>
                @endforeach
            </div>
        </section>
        @endif

        <!-- Section Divider - Geometric -->
        <div class="section-divider geometric"></div>

        <!-- Services Quick Links -->
        <section class="services-section" id="layanan">
            <div class="services-heading" data-reveal="" style="--reveal-delay: 0ms;"><p class="section-label">Layanan</p><h2>Pelayanan Publik</h2></div>
            <div class="service-grid" data-reveal="" style="--reveal-delay: 110ms;">
                @foreach([['title'=>'Ajukan Layanan','desc'=>'Layanan permohonan baru','icon'=>'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],['title'=>'Lacak Pengajuan','desc'=>'Tracking status','icon'=>'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01'],['title'=>'Janji Temu','desc'=>'Booking pertemuan','icon'=>'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],['title'=>'Whistleblowing','desc'=>'Lapor korupsi','icon'=>'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z']] as $service)
                <article class="service-card"><a href="{{ route('pelayanan') }}" class="service-link"><div class="service-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="{{ $service['icon'] }}"/></svg></div><h3 class="service-title">{{ $service['title'] }}</h3><p class="service-desc">{{ $service['desc'] }}</p><span class="service-arrow">?</span></a></article>
                @endforeach
            </div>
        </section>

        <!-- Section Divider - Gold Line -->
        <div class="section-divider gold-line"></div>

        <!-- Stats Section -->
        <section class="stats-section" id="statistik">
            <div class="stats-content" data-reveal="" style="--reveal-delay: 0ms;"><p class="section-label" style="display: flex; align-items: center; gap: 0.5rem;"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 20V10M12 20V4M6 20v-6"/></svg>Statistik</p><h2>Data Layanan</h2></div>
            <div class="stats-grid" data-reveal="" style="--reveal-delay: 110ms;">
                @foreach([['value'=>'156','label'=>'Pengajuan','icon'=>'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],['value'=>'98%','label'=>'Terselesaikan','icon'=>'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],['value'=>'24h','label'=>'Avg Response','icon'=>'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],['value'=>'12+','label'=>'Layanan','icon'=>'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10']] as $stat)
                <div class="stat-card"><div class="stat-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="{{ $stat['icon'] }}"/></svg></div><strong class="stat-value">{{ $stat['value'] }}</strong><span class="stat-label">{{ $stat['label'] }}</span></div>
                @endforeach
            </div>
            <div class="stats-status" data-reveal="" style="--reveal-delay: 220ms;"><div class="status-indicator"><span class="status-dot"></span><span class="status-text">Sistem Online</span></div><p class="status-time">Update terakhir: {{ now()->format('d M Y, H:i') }} WIB</p></div>
        </section>

        <!-- Section Divider - Mountain -->
        <div class="section-divider mountain"></div>

        <!-- Newsletter Section -->
        <section class="newsletter-section" id="kontak">
            <div class="newsletter-content" data-reveal="" style="--reveal-delay: 0ms;"><p class="section-label" style="display: flex; align-items: center; justify-content: center; gap: 0.5rem;"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>Berlangganan</p><h2>Dapatkan Update Berita</h2><p class="newsletter-desc">Berlangganan untuk menerima pemberitahuan berita dan pengumuman terbaru.</p>
            <form class="newsletter-form"><div class="newsletter-input-wrap"><svg class="newsletter-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg><input type="email" placeholder="email@contoh.com" class="newsletter-input" aria-label="Alamat surel Anda" required></div><button type="submit" class="newsletter-btn">Berlangganan<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h12m-5-5 5 5-5 5"/></svg></button></form></div>
        </section>

        <!-- Section Divider - Shadow Fade -->
        <div class="section-divider shadow-fade"></div>

        <!-- Footer -->
        <footer class="site-footer">
            <a class="brand-lockup brand-lockup-small" href="{{ url("/") }}" aria-label="SILATAR home"><span class="brand-mark" aria-hidden="true"><span></span></span><span class="brand-word"><span>SILATAR</span><span>V2</span></span></a>
            <p>Portal Layanan Digital Kementerian Agama Tanah Datar</p>
            <nav aria-label="Footer navigation"><a href="{{ url("/#home") }}">Beranda</a><a href="{{ url("/#pejabat") }}">Struktur</a><a href="{{ url("/#berita") }}">Berita</a><a href="{{ url("/#layanan") }}">Layanan</a><a href="{{ url("/#kontak") }}">Kontak</a></nav>
            <div class="footer-copyright"><span>&copy; {{ date("Y") }} SILATAR - Kementerian Agama Tanah Datar</span></div>
        </footer>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded',function(){
            var header=document.querySelector('.site-header');
            window.addEventListener('scroll',function(){if(window.pageYOffset>50){header.classList.add('is-scrolled');}else{header.classList.remove('is-scrolled');}});
            var menuToggle=document.getElementById('menuToggle'),menuClose=document.getElementById('menuClose'),mobileNav=document.getElementById('mobile-nav');
            if(menuToggle&&mobileNav){menuToggle.addEventListener('click',function(){mobileNav.classList.remove('hidden');document.body.style.overflow='hidden';});}
            if(menuClose){menuClose.addEventListener('click',function(){mobileNav.classList.add('hidden');document.body.style.overflow='';});}
            document.addEventListener('keydown',function(e){if(e.key==='Escape'&&mobileNav&&!mobileNav.classList.contains('hidden')){mobileNav.classList.add('hidden');document.body.style.overflow='';}});
            var revealElements=document.querySelectorAll('[data-reveal]'),observer=new IntersectionObserver(function(entries){entries.forEach(function(entry){if(entry.isIntersecting){entry.target.classList.add('is-visible');}});},{threshold:0.1});
            revealElements.forEach(function(el){observer.observe(el);var rect=el.getBoundingClientRect();if(rect.top<window.innerHeight){el.classList.add('is-visible');}});

            // Smooth Scrolling with Keyboard Arrow Pad Support
            var scrollSpeed = 80;
            var scrollDistance = 120;
            var keys = {37: false, 38: false, 39: false, 40: false};
            var touchStartY = 0;
            var smoothScrollActive = false;

            // Keyboard arrow navigation
            window.addEventListener('keydown', function(e) {
                if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA' || e.target.isContentEditable) return;
                if (['ArrowUp', 'ArrowDown', 'ArrowLeft', 'ArrowRight', 'PageUp', 'PageDown', 'Home', 'End'].includes(e.key)) {
                    e.preventDefault();
                }
                keys[e.key] = true;
            });

            window.addEventListener('keyup', function(e) {
                keys[e.key] = false;
            });

            function smoothKeyboardScroll() {
                var scrolled = false;

                if (keys[38] || keys[37]) {
                    window.scrollBy({top: -scrollDistance, behavior: 'smooth'});
                    scrolled = true;
                }
                if (keys[40] || keys[39]) {
                    window.scrollBy({top: scrollDistance, behavior: 'smooth'});
                    scrolled = true;
                }
                if (keys[33]) { // Page Up
                    window.scrollBy({top: -window.innerHeight * 0.9, behavior: 'smooth'});
                    scrolled = true;
                }
                if (keys[34]) { // Page Down
                    window.scrollBy({top: window.innerHeight * 0.9, behavior: 'smooth'});
                    scrolled = true;
                }
                if (keys[36]) { // Home
                    window.scrollTo({top: 0, behavior: 'smooth'});
                    scrolled = true;
                }
                if (keys[35]) { // End
                    window.scrollTo({top: document.body.scrollHeight, behavior: 'smooth'});
                    scrolled = true;
                }

                if (scrolled) {
                    requestAnimationFrame(smoothKeyboardScroll);
                }
            }

            // Start smooth scroll on key hold
            var scrollInterval;
            window.addEventListener('keydown', function(e) {
                if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA' || e.target.isContentEditable) return;
                if (['ArrowUp', 'ArrowDown', 'ArrowLeft', 'ArrowRight'].includes(e.key)) {
                    clearInterval(scrollInterval);
                    scrollInterval = setInterval(function() {
                        if (keys[e.key]) {
                            var direction = (e.key === 'ArrowUp' || e.key === 'ArrowLeft') ? -1 : 1;
                            var distance = (e.key === 'ArrowUp' || e.key === 'ArrowDown') ? scrollDistance : scrollDistance;
                            window.scrollBy({top: direction * scrollDistance, behavior: 'smooth'});
                        }
                    }, 20);
                }
            });

            window.addEventListener('keyup', function(e) {
                if (['ArrowUp', 'ArrowDown', 'ArrowLeft', 'ArrowRight'].includes(e.key)) {
                    clearInterval(scrollInterval);
                }
            });

            // Smooth scroll for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
                anchor.addEventListener('click', function(e) {
                    var targetId = this.getAttribute('href');
                    if (targetId === '#') return;
                    var target = document.querySelector(targetId);
                    if (target) {
                        e.preventDefault();
                        target.scrollIntoView({behavior: 'smooth', block: 'start'});
                    }
                });
            });
        });
    </script>
</x-layouts.app>
