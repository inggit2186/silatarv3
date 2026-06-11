<x-layouts.app title="Kankemenag Kab.Tanah Datar">
    <main class="relative overflow-x-hidden">

        <!-- Prominent Menu Button -->
        <div class="fixed top-4 right-4 z-[60] flex items-center gap-3">
            @auth
                @if(in_array(auth()->user()->role, ['admin', 'superadmin', 'humas', 'kasubbag']))
                <a href="{{ route('admin.news.index') }}" class="flex items-center gap-2 px-4 py-2.5 bg-amber-500/20 border border-amber-500/50 rounded-xl text-amber-400 font-mono text-xs font-bold uppercase tracking-wider hover:bg-amber-500/30 transition-all shadow-[0_0_20px_rgba(245,158,11,0.2)]">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V9a2 2 0 012-2h2a2 2 0 012 2v9a2 2 0 01-2 2h-2z"/></svg>
                    Berita
                </a>
                @endif
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 px-4 py-2.5 bg-emerald-500/20 border border-emerald-500/50 rounded-xl text-emerald-400 font-mono text-xs font-bold uppercase tracking-wider hover:bg-emerald-500/30 transition-all shadow-[0_0_20px_rgba(16,185,129,0.2)]">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="flex items-center gap-2 px-4 py-2.5 bg-cyan-500/20 border border-cyan-500/50 rounded-xl text-cyan-400 font-mono text-xs font-bold uppercase tracking-wider hover:bg-cyan-500/30 transition-all shadow-[0_0_20px_rgba(0,212,255,0.2)]">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 4h4.5A1.5 1.5 0 0016 5.5v13A1.5 1.5 0 0014.5 20H11M7 10l5 5-5 5M11 15H4"/></svg>
                    Login
                </a>
            @endauth
            <button type="button" id="menuToggle" class="flex items-center gap-2 px-5 py-3 bg-gradient-to-r from-cyan-600 to-cyan-500 rounded-xl text-white font-mono text-sm font-bold uppercase tracking-wider shadow-[0_0_25px_rgba(0,212,255,0.4)] hover:shadow-[0_0_35px_rgba(0,212,255,0.6)] hover:-translate-y-0.5 transition-all">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                Menu
            </button>
        </div>

        <!-- Mobile Menu Overlay -->
        <div id="mobileMenu" class="fixed inset-0 z-[55] hidden">
            <div id="menuOverlay" class="absolute inset-0 bg-slate-950/95 backdrop-blur-xl"></div>
            <div class="relative h-full flex flex-col items-center justify-center p-8">
                <button type="button" id="menuClose" class="mt-8 flex items-center gap-2 px-6 py-3 bg-rose-500/20 border border-rose-500/50 rounded-xl text-rose-400 font-mono text-sm font-bold uppercase tracking-wider hover:bg-rose-500/30 transition-all">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    Close
                </button>
                <div class="text-center mb-8">
                    <img src="{{ asset('favicon.webp') }}" alt="SILATAR Logo" class="w-20 h-20 mx-auto mb-4 rounded-2xl object-cover shadow-[0_0_30px_rgba(0,212,255,0.3)]">
                    <p class="text-xl font-bold uppercase tracking-wider text-cyan-400">SILATAR</p>
                    <p class="text-xs uppercase tracking-widest text-slate-500 mt-1">Kankemenag Tanah Datar</p>
                </div>
                <nav class="grid gap-4 w-full max-w-sm">
                    <a href="{{ url('/') }}" class="flex items-center gap-4 p-4 rounded-xl bg-slate-900/80 border border-cyan-500/30 text-cyan-400 font-mono text-sm font-bold uppercase tracking-wider hover:bg-cyan-500/10 transition-all">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        Beranda
                    </a>
                    <a href="{{ route('satuan-kerja') }}" class="flex items-center gap-4 p-4 rounded-xl bg-slate-900/80 border border-cyan-500/30 text-cyan-400 font-mono text-sm font-bold uppercase tracking-wider hover:bg-cyan-500/10 transition-all">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        Unit Kerja
                    </a>
                    <a href="{{ route('pelayanan') }}" class="flex items-center gap-4 p-4 rounded-xl bg-slate-900/80 border border-cyan-500/30 text-cyan-400 font-mono text-sm font-bold uppercase tracking-wider hover:bg-cyan-500/10 transition-all">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                        Pelayanan
                    </a>
                    @auth
                        @if(in_array(auth()->user()->role, ['admin', 'superadmin', 'humas', 'kasubbag']))
                    <a href="{{ route('admin.news.index') }}" class="flex items-center gap-4 p-4 rounded-xl bg-gradient-to-r from-amber-500/10 to-orange-500/10 border border-amber-500/30 text-amber-400 font-mono text-sm font-bold uppercase tracking-wider hover:bg-amber-500/20 transition-all">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V9a2 2 0 012-2h2a2 2 0 012 2v9a2 2 0 01-2 2h-2z"/></svg>
                        Kelola Berita
                    </a>
                        @endif
                    @endauth
                    <a href="{{ route('pelayanan') }}" class="flex items-center gap-4 p-4 rounded-xl bg-slate-900/80 border border-cyan-500/30 text-cyan-400 font-mono text-sm font-bold uppercase tracking-wider hover:bg-cyan-500/10 transition-all">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        Janji Temu
                    </a>
                    <a href="{{ route('whistleblowing') }}" class="flex items-center gap-4 p-4 rounded-xl bg-slate-900/80 border border-cyan-500/30 text-cyan-400 font-mono text-sm font-bold uppercase tracking-wider hover:bg-cyan-500/10 transition-all">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        Whistleblowing
                    </a>
                    <a href="{{ url('/#contact') }}" class="flex items-center gap-4 p-4 rounded-xl bg-slate-900/80 border border-cyan-500/30 text-cyan-400 font-mono text-sm font-bold uppercase tracking-wider hover:bg-cyan-500/10 transition-all">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        Kontak
                    </a>
                </nav>
            </div>
        </div>

        <!-- Slideshow Banner -->
        @if($slideshowNews->count() > 0)
        <section class="relative pb-0" x-data="{
            currentSlide: 0,
            totalSlides: {{ $slideshowNews->count() }},
            autoplay: null,
            init() {
                this.startAutoplay();
            },
            startAutoplay() {
                this.autoplay = setInterval(() => {
                    this.next();
                }, 5000);
            },
            stopAutoplay() {
                if (this.autoplay) clearInterval(this.autoplay);
            },
            next() {
                this.currentSlide = (this.currentSlide + 1) % this.totalSlides;
            },
            prev() {
                this.currentSlide = (this.currentSlide - 1 + this.totalSlides) % this.totalSlides;
            },
            goTo(index) {
                this.stopAutoplay();
                this.currentSlide = index;
                this.startAutoplay();
            }
        }">
            <!-- Slides -->
            <div class="relative overflow-hidden" @mouseenter="stopAutoplay()" @mouseleave="startAutoplay()">
                <div class="relative w-full h-[400px] md:h-[500px] bg-gradient-to-br from-slate-900 to-slate-950">
                    @foreach($slideshowNews as $index => $slide)
                    <div x-show="currentSlide === {{ $index }}" x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0 translate-x-full" x-transition:enter-end="opacity-100 translate-x-0" x-transition:leave="transition ease-in duration-500" x-transition:leave-start="opacity-100 translate-x-0" x-transition:leave-end="opacity-0 -translate-x-full" class="absolute inset-0">
                        @if($slide->image)
                        <img src="{{ asset('storage/' . $slide->image) }}" alt="{{ $slide->title }}" class="absolute inset-0 w-full h-full object-cover object-center" />
                        @else
                        <img src="{{ asset('assets/img/template/banner-02.png') }}" alt="{{ $slide->title }}" class="absolute inset-0 w-full h-full object-cover object-center" />
                        @endif
                        <!-- Gradient overlays for better text readability -->
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/40 to-transparent"></div>
                        <div class="absolute inset-0 bg-gradient-to-r from-slate-950/80 via-slate-950/40 to-transparent"></div>

                        <!-- Content Card - Bottom Left -->
                        <div class="absolute bottom-0 left-0 right-0 p-6 md:p-10">
                            <div class="max-w-2xl">
                                <!-- Category Badge -->
                                <div class="inline-flex items-center gap-2 px-3 py-1.5 mb-4 rounded-lg bg-cyan-500/90 backdrop-blur-sm shadow-lg">
                                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-900">{{ $slide->category }}</span>
                                </div>

                                <!-- Title -->
                                <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-white mb-3 leading-snug line-clamp-2 drop-shadow-lg">
                                    {{ $slide->title }}
                                </h2>

                                <!-- Excerpt -->
                                <p class="text-sm text-slate-200/90 mb-5 line-clamp-2 drop-shadow-md max-w-lg">
                                    {{ $slide->excerpt }}
                                </p>

                                <!-- CTA Button -->
                                <a href="{{ route('news.show', $slide->slug ?? $slide->id) }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-white/10 backdrop-blur-sm border border-white/30 text-white text-sm font-semibold hover:bg-white/20 hover:border-white/50 transition-all duration-300 shadow-lg">
                                    <span>Baca Selengkapnya</span>
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Navigation Arrows -->
                @if($slideshowNews->count() > 1)
                <button type="button" @click="stopAutoplay(); prev(); startAutoplay()" class="absolute left-4 top-1/2 -translate-y-1/2 z-20 flex h-10 w-10 items-center justify-center rounded-full bg-slate-950/60 backdrop-blur-sm border border-white/20 text-white/80 hover:bg-slate-950/80 hover:text-white hover:border-white/40 transition-all" aria-label="Previous slide">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <button type="button" @click="stopAutoplay(); next(); startAutoplay()" class="absolute right-4 top-1/2 -translate-y-1/2 z-20 flex h-10 w-10 items-center justify-center rounded-full bg-slate-950/60 backdrop-blur-sm border border-white/20 text-white/80 hover:bg-slate-950/80 hover:text-white hover:border-white/40 transition-all" aria-label="Next slide">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </button>

                <!-- Pagination Dots -->
                <div class="absolute bottom-4 right-4 z-20 flex items-center gap-2">
                    <template x-for="i in totalSlides" :key="i">
                        <button type="button" @click="goTo(i - 1)" class="group relative flex items-center justify-center transition-all" :aria-label="'Go to slide ' + i">
                            <span class="block h-2 rounded-full transition-all duration-300" :class="currentSlide === (i - 1) ? 'w-8 bg-cyan-400 shadow-[0_0_8px_rgba(0,212,255,0.6)]' : 'w-2 bg-white/40 hover:bg-white/60'"></span>
                        </button>
                    </template>
                </div>
                @endif

                <!-- Progress Bar -->
                <div class="absolute bottom-0 left-0 right-0 z-20 h-1 bg-slate-800/50">
                    <div class="h-full bg-gradient-to-r from-cyan-500 to-cyan-400 transition-all duration-300" :style="'width: ' + ((currentSlide + 1) / totalSlides * 100) + '%'"></div>
                </div>
            </div>
        </section>
        @else
        <!-- Default Slideshow when no news -->
        <section class="relative pb-0">
            <div class="relative aspect-[21/9] max-h-[500px] bg-gradient-to-br from-slate-900 to-slate-950">
                <img src="{{ asset('assets/img/template/banner-02.png') }}" alt="SILATAR" class="h-full w-full object-cover" />
                <div class="absolute inset-0 bg-gradient-to-r from-slate-950/90 via-slate-950/60 to-transparent"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-transparent to-transparent/30"></div>
                <div class="absolute inset-0 flex items-center">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 w-full">
                        <div class="max-w-2xl">
                            <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full font-mono text-xs font-bold uppercase tracking-wider mb-4 bg-cyan-500 text-slate-900">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                                Selamat Datang
                            </span>
                            <h2 class="font-mono text-3xl md:text-4xl lg:text-5xl font-black uppercase tracking-wider text-white mb-4 leading-tight">Sistem Layanan Digital SILATAR V2</h2>
                            <p class="text-base md:text-lg text-slate-300 mb-6 max-w-xl">Kementerian Agama Tanah Datar resmi launching platform layanan digital terintegrasi</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        @endif

        <!-- Hero News Ticker -->
        <section class="relative bg-gradient-to-r from-cyan-900/80 via-cyan-800/60 to-cyan-900/80 border-b border-cyan-500/30 py-3 overflow-hidden">
            <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 flex items-center gap-6">
                <div class="flex-shrink-0 flex items-center gap-2 px-4 py-1.5 bg-cyan-500 rounded-full">
                    <svg class="w-3.5 h-3.5 text-slate-900" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V9a2 2 0 012-2h2a2 2 0 012 2v9a2 2 0 01-2 2h-2z"/></svg>
                    <span class="font-mono text-xs font-bold uppercase tracking-wider text-slate-900">Breaking</span>
                </div>
                <div class="overflow-hidden flex-1">
                    <p class="font-mono text-sm text-cyan-100 animate-pulse">Portal Berita Resmi Kementerian Agama Tanah Datar - Informasi layanan, kegiatan, dan pengumuman terbaru</p>
                </div>
            </div>
        </section>

        <!-- Featured News Hero -->
        @if($featuredNews->count() > 0)
        <section class="relative py-8">
            <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6">
                <div class="grid gap-6 lg:grid-cols-4">
                    @php
                    $firstFeatured = $featuredNews->first();
                    $otherFeatured = $featuredNews->skip(1)->take(3);
                    @endphp

                    <!-- Card 1 - Featured Large (2 columns) -->
                    @if($firstFeatured)
                    <div class="lg:col-span-2 relative group">
                        <a href="{{ route('news.show', $firstFeatured->slug ?? $firstFeatured->id) }}">
                            <div class="relative h-[380px] lg:h-[420px] rounded-2xl overflow-hidden border-2 border-cyan-500/50 bg-gradient-to-br from-slate-900 to-slate-950 shadow-[0_0_40px_rgba(0,212,255,0.15)]">
                                @if($firstFeatured->image)
                                <img src="{{ asset('storage/' . $firstFeatured->image) }}" alt="{{ $firstFeatured->title }}" class="h-full w-full object-cover group-hover:scale-105 transition-transform duration-700" />
                                @else
                                <img src="{{ asset('assets/img/template/banner-02.png') }}" alt="{{ $firstFeatured->title }}" class="h-full w-full object-cover group-hover:scale-105 transition-transform duration-700" />
                                @endif
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/70 to-transparent"></div>

                                <!-- Glowing Effect -->
                                <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-cyan-400 to-transparent opacity-60"></div>

                                <div class="absolute top-5 left-5">
                                    <span class="inline-flex items-center gap-2 px-4 py-2 bg-cyan-500 text-slate-900 font-mono text-xs font-bold uppercase tracking-wider rounded-full shadow-[0_0_20px_rgba(0,212,255,0.4)]">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                                        Featured
                                    </span>
                                </div>

                                <div class="absolute bottom-0 left-0 right-0 p-6">
                                    <div class="flex items-center gap-3 mb-3">
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-amber-500/90 text-white font-mono text-[11px] font-bold uppercase tracking-wider rounded-full">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            {{ $firstFeatured->category }}
                                        </span>
                                        <span class="font-mono text-xs text-cyan-300">{{ $firstFeatured->publish_date ? \Carbon\Carbon::parse($firstFeatured->publish_date)->format('d M Y') : '' }}</span>
                                    </div>
                                    <h2 class="font-mono text-2xl md:text-3xl font-black uppercase tracking-wider text-white mb-2 leading-tight line-clamp-2">
                                        {{ $firstFeatured->title }}
                                    </h2>
                                    <p class="text-sm text-slate-300 line-clamp-2">{{ $firstFeatured->excerpt }}</p>
                                    <div class="mt-4 flex items-center gap-2 text-cyan-400 font-mono text-sm font-semibold">
                                        <span>Baca Selengkapnya</span>
                                        <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    @endif

                    @foreach($otherFeatured as $idx => $news)
                    @php
                    $colors = ['purple', 'emerald', 'amber'];
                    $color = $colors[$idx] ?? 'cyan';
                    @endphp
                    <!-- Card {{ $idx + 2 }} -->
                    <div class="relative group">
                        <a href="{{ route('news.show', $news->slug ?? $news->id) }}">
                            <div class="relative h-[380px] lg:h-[420px] rounded-2xl overflow-hidden border border-{{ $color }}-500/30 bg-gradient-to-br from-{{ $color }}-950/50 to-slate-900 hover:border-{{ $color }}-400/50 hover:shadow-[0_0_30px_rgba(var(--tw-{{ $color }}),0.15)] transition-all duration-300">
                                @if($news->image)
                                <img src="{{ asset('storage/' . $news->image) }}" alt="{{ $news->title }}" class="h-full w-full object-cover group-hover:scale-110 transition-transform duration-500" />
                                @else
                                <img src="{{ asset('assets/img/template/banner-0' . ($idx + 3) . '.png') }}" alt="{{ $news->title }}" class="h-full w-full object-cover group-hover:scale-110 transition-transform duration-500" />
                                @endif
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/60 to-transparent"></div>

                                <!-- Top Accent -->
                                <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-{{ $color }}-600 to-{{ $color }}-400"></div>

                                <div class="absolute top-5 left-5">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-{{ $color }}-500/90 text-white font-mono text-[10px] font-bold uppercase tracking-wider rounded-full">
                                        {{ $news->category }}
                                    </span>
                                </div>

                                <div class="absolute bottom-0 left-0 right-0 p-5">
                                    <div class="flex items-center gap-2 mb-3">
                                        <svg class="w-4 h-4 text-{{ $color }}-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        <span class="font-mono text-[11px] text-{{ $color }}-300">{{ $news->publish_date ? \Carbon\Carbon::parse($news->publish_date)->format('d M Y') : '' }}</span>
                                    </div>
                                    <h3 class="font-mono text-lg font-bold uppercase tracking-wider text-white leading-tight line-clamp-2 mb-2">{{ $news->title }}</h3>
                                    <p class="text-xs text-slate-400 line-clamp-2">{{ $news->excerpt }}</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>
        @endif

        <!-- Latest News Grid -->
        <section class="relative py-8 bg-gradient-to-b from-slate-950 to-slate-900">
            <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-1 h-8 bg-gradient-to-b from-cyan-400 to-cyan-600 rounded-full"></div>
                        <svg class="w-6 h-6 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V9a2 2 0 012-2h2a2 2 0 012 2v9a2 2 0 01-2 2h-2z"/></svg>
                        <h2 class="font-mono text-xl font-bold uppercase tracking-wider text-white">Berita Terbaru</h2>
                    </div>
                    <a href="{{ route('news.index') }}" class="flex items-center gap-2 px-4 py-2 bg-cyan-500/10 border border-cyan-500/30 rounded-full text-cyan-400 font-mono text-xs font-semibold uppercase tracking-wider hover:bg-cyan-500/20 transition-all">
                        Lihat Semua
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </a>
                </div>

                @if($latestNews->count() > 0)
                <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-3">
                    @foreach($latestNews as $news)
                    <article class="group relative rounded-xl border border-cyan-500/20 bg-slate-900/80 overflow-hidden hover:border-cyan-400/50 hover:shadow-[0_0_30px_rgba(0,212,255,0.15)] transition-all duration-300">
                        <a href="{{ route('news.show', $news->slug ?? $news->id) }}">
                            <div class="relative aspect-[16/9] overflow-hidden">
                                @if($news->image)
                                <img src="{{ asset('storage/' . $news->image) }}" alt="{{ $news->title }}" class="h-full w-full object-cover group-hover:scale-110 transition-transform duration-500" />
                                @else
                                <img src="{{ asset('assets/img/template/banner-02.png') }}" alt="{{ $news->title }}" class="h-full w-full object-cover group-hover:scale-110 transition-transform duration-500" />
                                @endif
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/90 via-transparent to-transparent"></div>
                                <div class="absolute top-3 left-3 flex items-center gap-2">
                                    <span class="px-2.5 py-1 bg-cyan-500/90 text-slate-900 font-mono text-[10px] font-bold uppercase tracking-wider rounded flex items-center gap-1.5">
                                        {{ $news->category }}
                                    </span>
                                </div>
                            </div>
                            <div class="p-4">
                                <div class="flex items-center gap-2 mb-2">
                                    <svg class="w-3.5 h-3.5 text-cyan-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span class="font-mono text-[11px] text-slate-400">{{ $news->publish_date ? \Carbon\Carbon::parse($news->publish_date)->format('d M Y') : '' }}</span>
                                </div>
                                <h3 class="font-mono text-sm font-bold uppercase tracking-wider text-white group-hover:text-cyan-300 transition-colors line-clamp-2 leading-snug">{{ $news->title }}</h3>
                                <p class="mt-2 text-xs text-slate-400 line-clamp-2 leading-relaxed">{{ $news->excerpt }}</p>
                                <div class="mt-3 flex items-center gap-1 text-cyan-400 font-mono text-xs font-semibold uppercase tracking-wider">
                                    Baca Selengkapnya
                                    <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                                </div>
                            </div>
                        </a>
                    </article>
                    @endforeach
                </div>
                @else
                <div class="text-center py-12 rounded-xl border border-slate-700/30 bg-slate-900/40">
                    <svg class="w-16 h-16 mx-auto text-slate-600 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V9a2 2 0 012-2h2a2 2 0 012 2v9a2 2 0 01-2 2h-2z"/></svg>
                    <p class="font-mono text-slate-500">Belum ada berita terbaru</p>
                </div>
                @endif
            </div>
        </section>

        <!-- Categories & Quick Links -->
        <section class="relative py-8 bg-gradient-to-b from-slate-900 to-slate-950">
            <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6">
                <div class="grid gap-6 lg:grid-cols-4">
                    <!-- Categories -->
                    <div class="lg:col-span-2">
                        <div class="flex items-center gap-3 mb-5">
                            <div class="w-1 h-8 bg-gradient-to-b from-purple-400 to-purple-600 rounded-full"></div>
                            <svg class="w-6 h-6 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                            <h2 class="font-mono text-xl font-bold uppercase tracking-wider text-white">Kategori Berita</h2>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            @foreach([
                                ['icon' => 'M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z', 'label' => 'Pengumuman', 'count' => '24', 'color' => 'purple'],
                                ['icon' => 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', 'label' => 'Layanan', 'count' => '18', 'color' => 'cyan'],
                                ['icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', 'label' => 'Agenda', 'count' => '12', 'color' => 'amber'],
                                ['icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z', 'label' => 'Kegiatan', 'count' => '32', 'color' => 'emerald'],
                                ['icon' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => 'Informasi', 'count' => '15', 'color' => 'blue'],
                                ['icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'label' => 'Panduan', 'count' => '8', 'color' => 'pink'],
                            ] as $cat)
                            <a href="#" class="group flex items-center gap-3 p-4 rounded-xl border border-{{ $cat['color'] }}-500/20 bg-slate-900/60 hover:bg-{{ $cat['color'] }}-500/10 hover:border-{{ $cat['color'] }}-400/40 transition-all">
                                <div class="flex-shrink-0 w-11 h-11 flex items-center justify-center rounded-xl bg-{{ $cat['color'] }}-500/10 border border-{{ $cat['color'] }}-500/30 group-hover:bg-{{ $cat['color'] }}-500/20 transition-all">
                                    <svg class="w-5 h-5 text-{{ $cat['color'] }}-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $cat['icon'] }}"/></svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-mono text-sm font-semibold text-white truncate">{{ $cat['label'] }}</p>
                                    <p class="font-mono text-xs text-{{ $cat['color'] }}-400 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V9a2 2 0 012-2h2a2 2 0 012 2v9a2 2 0 01-2 2h-2z"/></svg>
                                        {{ $cat['count'] }} berita
                                    </p>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Quick Links -->
                    <div>
                        <div class="flex items-center gap-3 mb-5">
                            <div class="w-1 h-8 bg-gradient-to-b from-amber-400 to-amber-600 rounded-full"></div>
                            <svg class="w-6 h-6 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            <h2 class="font-mono text-xl font-bold uppercase tracking-wider text-white">Layanan Cepat</h2>
                        </div>
                        <div class="space-y-3">
                            @foreach([
                                ['icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'label' => 'Ajukan Layanan', 'desc' => 'Layanan permohonan baru'],
                                ['icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01', 'label' => 'Lacak Pengajuan', 'desc' => 'Tracking status'],
                                ['icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', 'label' => 'Janji Temu', 'desc' => 'Booking pertemuan'],
                                ['icon' => 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', 'label' => 'Whistleblowing', 'desc' => 'Lapor korupsi/gratifikasi'],
                            ] as $link)
                            <a href="#" class="group flex items-center gap-3 p-4 rounded-xl border border-amber-500/20 bg-amber-500/5 hover:bg-amber-500/10 hover:border-amber-400/40 transition-all">
                                <div class="flex-shrink-0 w-11 h-11 flex items-center justify-center rounded-xl bg-amber-500/10 border border-amber-500/30 group-hover:bg-amber-500/20 transition-all">
                                    <svg class="w-5 h-5 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $link['icon'] }}"/></svg>
                                </div>
                                <div>
                                    <p class="font-mono text-sm font-semibold text-white">{{ $link['label'] }}</p>
                                    <p class="font-mono text-xs text-amber-400/70 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        {{ $link['desc'] }}
                                    </p>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Stats & Info -->
                    <div>
                        <div class="flex items-center gap-3 mb-5">
                            <div class="w-1 h-8 bg-gradient-to-b from-emerald-400 to-emerald-600 rounded-full"></div>
                            <svg class="w-6 h-6 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            <h2 class="font-mono text-xl font-bold uppercase tracking-wider text-white">Statistik</h2>
                        </div>
                        <div class="rounded-xl border border-emerald-500/20 bg-slate-900/60 p-5">
                            <div class="grid grid-cols-2 gap-4">
                                @foreach([
                                    ['value' => '156', 'label' => 'Pengajuan', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                                    ['value' => '98%', 'label' => 'Terselesaikan', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                                    ['value' => '24h', 'label' => 'Avg Response', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                                    ['value' => '12+', 'label' => 'Layanan', 'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10'],
                                ] as $stat)
                                <div class="text-center p-3 rounded-lg bg-emerald-500/5 border border-emerald-500/20 hover:bg-emerald-500/10 transition-all group">
                                    <div class="flex justify-center mb-1">
                                        <div class="w-8 h-8 flex items-center justify-center rounded-lg bg-emerald-500/10 group-hover:bg-emerald-500/20 transition-all">
                                            <svg class="w-4 h-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $stat['icon'] }}"/></svg>
                                        </div>
                                    </div>
                                    <p class="font-mono text-2xl font-black text-emerald-400">{{ $stat['value'] }}</p>
                                    <p class="font-mono text-[10px] uppercase tracking-wider text-emerald-400/60 mt-1">{{ $stat['label'] }}</p>
                                </div>
                                @endforeach
                            </div>

                            <div class="mt-4 pt-4 border-t border-emerald-500/20">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="relative flex h-2 w-2">
                                        <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-75"></span>
                                        <span class="relative inline-flex h-2 w-2 rounded-full bg-emerald-500"></span>
                                    </span>
                                    <span class="font-mono text-xs text-emerald-400 flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                        Sistem Online
                                    </span>
                                </div>
                                <p class="font-mono text-[11px] text-slate-400 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Update terakhir: {{ now()->format('d M Y, H:i') }} WIB
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Newsletter / Subscribe -->
        <section class="relative py-10 bg-gradient-to-b from-slate-950 via-cyan-950/30 to-slate-950">
            <div class="relative z-10 max-w-3xl mx-auto px-4 sm:px-6 text-center">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-cyan-500/10 border border-cyan-500/30 rounded-full mb-4">
                    <svg class="w-4 h-4 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    <span class="font-mono text-xs font-semibold uppercase tracking-wider text-cyan-400">Notifikasi</span>
                </div>
                <div class="flex items-center justify-center gap-3 mb-2">
                    <svg class="w-8 h-8 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    <h2 class="font-mono text-2xl font-black uppercase tracking-wider text-white">Dapatkan Update Berita</h2>
                </div>
                <p class="text-sm text-slate-400 mb-6 flex items-center justify-center gap-2">
                    <svg class="w-4 h-4 text-cyan-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Berlangganan untuk menerima pemberitahuan berita dan pengumuman terbaru dari SILATAR.
                </p>
                <form class="flex flex-col sm:flex-row gap-3 max-w-md mx-auto">
                    <div class="relative flex-1">
                        <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/></svg>
                        <input type="email" placeholder="email@contoh.com" class="w-full pl-11 pr-4 py-3 bg-slate-900/80 border border-cyan-500/30 rounded-full text-white font-mono text-sm placeholder-slate-500 focus:outline-none focus:border-cyan-400 focus:shadow-[0_0_15px_rgba(0,212,255,0.3)]" />
                    </div>
                    <button type="submit" class="flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-cyan-600 to-cyan-500 rounded-full font-mono text-sm font-bold uppercase tracking-wider text-white hover:shadow-[0_0_25px_rgba(0,212,255,0.5)] transition-all">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        Berlangganan
                    </button>
                </form>
            </div>
        </section>

        <!-- Footer -->
        <footer class="relative border-t border-cyan-500/20 bg-slate-950 py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6">
                <div class="flex flex-col items-center justify-between gap-6 md:flex-row">
                    <div class="text-center md:text-left">
                        <div class="flex items-center justify-center md:justify-start gap-3">
                            <img src="{{ asset('favicon.webp') }}" alt="SILATAR" class="w-10 h-10 rounded-lg object-cover">
                            <div>
                                <p class="text-sm font-bold uppercase tracking-wider text-cyan-400">SILATAR</p>
                                <p class="text-[9px] uppercase tracking-widest text-slate-500">Kankemenag Tanah Datar</p>
                            </div>
                        </div>
                        <p class="mt-3 text-xs text-slate-500 flex items-center gap-2">
                            <svg class="w-3.5 h-3.5 text-cyan-500/50" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Portal Berita & Layanan Digital
                        </p>
                    </div>
                    <div class="flex flex-wrap items-center justify-center gap-6 font-mono text-sm text-slate-500">
                        <a href="{{ url('/') }}" class="flex items-center gap-2 transition-colors hover:text-cyan-400">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            Beranda
                        </a>
                        <a href="{{ route('satuan-kerja') }}" class="flex items-center gap-2 transition-colors hover:text-cyan-400">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            Units
                        </a>
                        <a href="{{ route('pelayanan') }}" class="flex items-center gap-2 transition-colors hover:text-cyan-400">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                            Layanan
                        </a>
                        <a href="{{ url('/#contact') }}" class="flex items-center gap-2 transition-colors hover:text-cyan-400">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            Kontak
                        </a>
                    </div>
                </div>
                <div class="mt-8 h-px bg-gradient-to-r from-transparent via-cyan-500/30 to-transparent"></div>
                <div class="mt-8 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <p class="font-mono text-xs uppercase tracking-wider text-slate-600 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        &copy; {{ date('Y') }} SILATAR - Kementerian Agama Tanah Datar
                    </p>
                    <div class="flex items-center gap-4">
                        <span class="font-mono text-[10px] text-slate-600 flex items-center gap-1">
                            <svg class="w-3 h-3 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            All rights reserved
                        </span>
                    </div>
                </div>
            </div>
        </footer>
    </main>

    <!-- Menu Toggle Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const menuToggle = document.getElementById('menuToggle');
            const menuClose = document.getElementById('menuClose');
            const mobileMenu = document.getElementById('mobileMenu');
            const menuOverlay = document.getElementById('menuOverlay');

            if (menuToggle && mobileMenu) {
                menuToggle.addEventListener('click', function() {
                    mobileMenu.classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                });
            }

            if (menuClose && mobileMenu) {
                menuClose.addEventListener('click', function() {
                    mobileMenu.classList.add('hidden');
                    document.body.style.overflow = '';
                });
            }

            // Close menu when clicking on overlay (background)
            if (menuOverlay) {
                menuOverlay.addEventListener('click', function() {
                    mobileMenu.classList.add('hidden');
                    document.body.style.overflow = '';
                });
            }

            // Close menu with Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && mobileMenu && !mobileMenu.classList.contains('hidden')) {
                    mobileMenu.classList.add('hidden');
                    document.body.style.overflow = '';
                }
            });
        });
    </script>
</x-layouts.app>