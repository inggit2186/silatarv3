<x-layouts.app title="SILATAR - Sistem Informasi Layanan dan Administrasi Terpadu">
    <!-- Global Particle System - Full Page -->
    <div class="fixed inset-0 pointer-events-none z-0">
        <canvas
            id="globalParticles"
            data-particle-canvas
            data-particle-count="80"
            data-mouse-influence="true"
            class="w-full h-full"
        ></canvas>
    </div>

    <!-- Floating Cyberpunk Icons - Full Page -->
    <div class="fixed inset-0 pointer-events-none z-5 overflow-hidden">
        <!-- Lightbulb Icon -->
        <div class="absolute top-1/4 left-8 w-16 h-16 animate-pulse" style="animation-duration: 3s;">
            <svg class="w-full h-full text-cyan-400 opacity-20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                <path d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
            </svg>
        </div>

        <!-- Lock Icon -->
        <div class="absolute top-1/3 right-12 w-12 h-12 animate-pulse" style="animation-duration: 4s; animation-delay: 1s;">
            <svg class="w-full h-full text-purple-400 opacity-15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                <path d="M7 11V7a5 5 0 0110 0v4"/>
            </svg>
        </div>

        <!-- Shield Icon -->
        <div class="absolute bottom-1/3 left-1/4 w-14 h-14 animate-pulse" style="animation-duration: 3.5s; animation-delay: 0.5s;">
            <svg class="w-full h-full text-cyan-500 opacity-15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
            </svg>
        </div>

        <!-- Code Icon -->
        <div class="absolute top-1/2 right-1/4 w-10 h-10 animate-pulse" style="animation-duration: 4.5s; animation-delay: 2s;">
            <svg class="w-full h-full text-cyan-400 opacity-15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                <path d="M16 18l6-6-6-6M8 6l-6 6 6 6"/>
            </svg>
        </div>

        <!-- Database Icon -->
        <div class="absolute bottom-1/4 right-1/3 w-8 h-8 animate-pulse" style="animation-duration: 2.5s; animation-delay: 1.5s;">
            <svg class="w-full h-full text-purple-500 opacity-20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                <ellipse cx="12" cy="5" rx="9" ry="3"/>
                <path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"/>
            </svg>
        </div>

        <!-- Chart Icon -->
        <div class="absolute top-2/3 left-1/6 w-10 h-10 animate-pulse" style="animation-duration: 3s; animation-delay: 0.8s;">
            <svg class="w-full h-full text-cyan-300 opacity-15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                <path d="M18 20V10M12 20V4M6 20v-6"/>
            </svg>
        </div>
    </div>

    <main class="relative overflow-x-hidden">

        <!-- Hero Section -->
        <section id="home" class="relative min-h-screen flex items-center py-8">
            <div class="relative z-10 w-full max-w-7xl mx-auto px-4 sm:px-6">
                <div class="grid items-center gap-8 lg:grid-cols-2">
                    <!-- Left: Image Slideshow -->
                    <div
                        class="relative order-2 lg:order-1"
                        x-data="{
                            currentSlide: 0,
                            init() {
                                setInterval(() => { this.currentSlide = (this.currentSlide + 1) % 7; }, 4000);
                            }
                        }"
                    >
                        <div class="relative aspect-[4/3] rounded-2xl border border-cyan-500/30 bg-gradient-to-br from-slate-900 to-slate-950 shadow-[0_0_40px_rgba(0,212,255,0.15)]">
                            <!-- Slides -->
                            <div x-show="currentSlide === 0" x-transition class="absolute inset-0 rounded-2xl overflow-hidden">
                                <img src="{{ asset('assets/img/template/banner-02.png') }}" alt="Slide" class="h-full w-full object-contain" />
                            </div>
                            <div x-show="currentSlide === 1" x-transition class="absolute inset-0 rounded-2xl overflow-hidden">
                                <img src="{{ asset('assets/img/template/banner-03.png') }}" alt="Slide" class="h-full w-full object-contain" />
                            </div>
                            <div x-show="currentSlide === 2" x-transition class="absolute inset-0 rounded-2xl overflow-hidden">
                                <img src="{{ asset('assets/img/template/banner-04.png') }}" alt="Slide" class="h-full w-full object-contain" />
                            </div>
                            <div x-show="currentSlide === 3" x-transition class="absolute inset-0 rounded-2xl overflow-hidden">
                                <img src="{{ asset('assets/img/template/banner-05.png') }}" alt="Slide" class="h-full w-full object-contain" />
                            </div>
                            <div x-show="currentSlide === 4" x-transition class="absolute inset-0 rounded-2xl overflow-hidden">
                                <img src="{{ asset('assets/img/template/banner-06.png') }}" alt="Slide" class="h-full w-full object-contain" />
                            </div>
                            <div x-show="currentSlide === 5" x-transition class="absolute inset-0 rounded-2xl overflow-hidden">
                                <img src="{{ asset('assets/img/template/banner-07.png') }}" alt="Slide" class="h-full w-full object-contain" />
                            </div>
                            <div x-show="currentSlide === 6" x-transition class="absolute inset-0 rounded-2xl overflow-hidden">
                                <img src="{{ asset('assets/img/template/banner-08.png') }}" alt="Slide" class="h-full w-full object-contain" />
                            </div>

                            <!-- Indicators -->
                            <div class="absolute bottom-3 left-1/2 -translate-x-1/2 flex gap-1.5 z-10">
                                <template x-for="i in 7" :key="i">
                                    <button @click="currentSlide = i - 1" class="h-1.5 w-1.5 rounded-full transition-all" :class="currentSlide === i - 1 ? 'bg-cyan-400 w-6' : 'bg-slate-600'"></button>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Content -->
                    <div class="order-1 lg:order-2 space-y-4">
                        <div class="inline-flex items-center gap-2 rounded-full border border-cyan-500/30 bg-cyan-500/10 px-4 py-1.5">
                            <span class="relative flex h-2 w-2">
                                <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-cyan-400 opacity-75"></span>
                                <span class="relative inline-flex h-2 w-2 rounded-full bg-cyan-500"></span>
                            </span>
                            <span class="font-mono text-xs font-medium uppercase tracking-widest text-cyan-400">System Online</span>
                        </div>

                        <h1 class="font-mono text-4xl font-black uppercase tracking-[0.1em] text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-cyan-300 sm:text-5xl lg:text-6xl">
                            SILATAR
                        </h1>

                        <p class="font-mono text-lg uppercase tracking-wider text-cyan-400/80">Sistem Informasi Layanan & Administrasi Terpadu</p>

                        <p class="text-base text-slate-400">Portal layanan digital untuk Kankemenag Tanah Datar. Dirancang untuk memberikan pengalaman akses informasi yang cepat, transparan, dan mudah.</p>

                        <!-- CTA -->
                        <div class="flex flex-wrap gap-4 pt-2">
                            <a href="{{ route('login') }}" class="group relative inline-flex items-center gap-3 rounded-full bg-gradient-to-r from-cyan-600 to-cyan-500 px-8 py-4 font-mono text-sm font-semibold uppercase tracking-wider text-white shadow-[0_0_30px_rgba(0,212,255,0.4)] transition-all hover:shadow-[0_0_50px_rgba(0,212,255,0.6)] hover:-translate-y-1">
                                <span>Masuk ke Sistem</span>
                                <svg class="h-5 w-5 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                            </a>
                            <a href="{{ route('pelayanan') }}" class="inline-flex items-center gap-3 rounded-full border border-cyan-500/40 bg-cyan-500/10 px-8 py-4 font-mono text-sm font-semibold uppercase tracking-wider text-cyan-400 transition-all hover:bg-cyan-500/20">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                <span>Jelajahi Layanan</span>
                            </a>
                        </div>

                        <!-- Stats with Icons -->
                        <div class="grid grid-cols-4 gap-3 pt-4">
                            <div class="relative rounded-xl border border-cyan-500/20 bg-slate-900/60 p-3 text-center">
                                <div class="absolute -top-2 left-3 w-4 h-4 bg-slate-900 border border-cyan-500/30 rounded flex items-center justify-center">
                                    <svg class="w-2 h-2 text-cyan-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <span class="font-mono text-2xl font-bold text-cyan-400">12+</span>
                                <p class="mt-1 text-xs text-slate-400">Layanan Aktif</p>
                            </div>
                            <div class="relative rounded-xl border border-cyan-500/20 bg-slate-900/60 p-3 text-center">
                                <div class="absolute -top-2 left-3 w-4 h-4 bg-slate-900 border border-cyan-500/30 rounded flex items-center justify-center">
                                    <svg class="w-2 h-2 text-cyan-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16"/></svg>
                                </div>
                                <span class="font-mono text-2xl font-bold text-cyan-400">8</span>
                                <p class="mt-1 text-xs text-slate-400">Unit Kerja</p>
                            </div>
                            <div class="relative rounded-xl border border-cyan-500/20 bg-slate-900/60 p-3 text-center">
                                <div class="absolute -top-2 left-3 w-4 h-4 bg-slate-900 border border-cyan-500/30 rounded flex items-center justify-center">
                                    <svg class="w-2 h-2 text-cyan-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586"/></svg>
                                </div>
                                <span class="font-mono text-2xl font-bold text-cyan-400">156</span>
                                <p class="mt-1 text-xs text-slate-400">Pengajuan</p>
                            </div>
                            <div class="relative rounded-xl border border-cyan-500/20 bg-slate-900/60 p-3 text-center">
                                <div class="absolute -top-2 left-3 w-4 h-4 bg-slate-900 border border-cyan-500/30 rounded flex items-center justify-center">
                                    <svg class="w-2 h-2 text-cyan-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <span class="font-mono text-2xl font-bold text-cyan-400">24h</span>
                                <p class="mt-1 text-xs text-slate-400">Respon</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section id="features" class="relative py-12 bg-gradient-to-b from-slate-950 to-slate-900">

            <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6">
                <div class="mb-8 text-center">
                    <span class="inline-flex items-center gap-2 rounded-full border border-cyan-500/30 bg-cyan-500/10 px-4 py-1 font-mono text-xs font-semibold uppercase tracking-widest text-cyan-400">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Features
                    </span>
                    <h2 class="mt-3 font-mono text-2xl font-bold uppercase tracking-wider text-white sm:text-3xl">Unlock Your Experience</h2>
                    <p class="mt-2 mx-auto max-w-xl text-sm text-slate-400">Jelajahi fitur-fitur utama yang dirancang untuk meningkatkan pengalaman layanan Anda.</p>
                </div>

                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    <x-cyber.case-study-card level="01" title="Layanan Cepat" description="Akses informasi dan layanan dengan waktu respons yang cepat dan efisien." progress="85" :unlocked="true" :tags="['Real-time', 'Responsif']" />
                    <x-cyber.case-study-card level="02" title="Transparansi Data" description="Setiap proses terdokumentasi dengan jelas untuk kemudahan seguimiento." progress="70" :unlocked="true" :tags="['Dokumentasi', 'Tracking']" />
                    <x-cyber.case-study-card level="03" title="Akses Terintegrasi" description="Satu platform untuk semua kebutuhan administrasi dan layanan." progress="45" :unlocked="false" :tags="['All-in-one', 'Terintegrasi']" />
                </div>

                <!-- Progress -->
                <div class="mt-10 flex items-center justify-center gap-4">
                    <div class="h-2 w-48 rounded-full bg-slate-800 border border-cyan-500/20 overflow-hidden">
                        <div class="h-full w-2/3 rounded-full bg-gradient-to-r from-cyan-500 to-cyan-400 shadow-[0_0_15px_rgba(0,212,255,0.5)]"></div>
                    </div>
                    <span class="font-mono text-sm text-cyan-400">67% Complete</span>
                </div>
            </div>
        </section>

        <!-- Units Section -->
        <section id="units" class="relative py-12 bg-gradient-to-b from-slate-900 to-slate-950">

            <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6">
                <div class="mb-8 text-center">
                    <span class="inline-flex items-center gap-2 rounded-full border border-cyan-500/30 bg-cyan-500/10 px-4 py-1 font-mono text-xs font-semibold uppercase tracking-widest text-cyan-400">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2M9 3a4 4 0 100 8 4 4 0 000-8zM23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
                        Units
                    </span>
                    <h2 class="mt-3 font-mono text-2xl font-bold uppercase tracking-wider text-white sm:text-3xl">Satuan Kerja</h2>
                    <p class="mt-2 mx-auto max-w-xl text-sm text-slate-400">Tim profesional yang siap membantu Anda 24/7.</p>
                </div>

                <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                    @foreach([
                        ['icon' => 'M4 16V6h12v10M7 6V4h6v2M7 10h6M7 13h4', 'title' => 'Administrasi', 'desc' => 'Melayani alur surat dan berkas'],
                        ['icon' => 'M10 3.5 4.5 6v3c0 3.5 2.3 6.5 5.5 7.5 3.2-1 5.5-4 5.5-7.5V6L10 3.5ZM8 10l1.5 1.5L12.5 8.5', 'title' => 'Pengawasan', 'desc' => 'Menjaga standar layanan'],
                        ['icon' => 'M6 15.5V11M10 15.5V8M14 15.5V5M4.5 16h11', 'title' => 'Data & Laporan', 'desc' => 'Mengelola informasi dan rekap'],
                        ['icon' => 'M4 15.5c1.5-2.5 3.8-4 6-4s4.5 1.5 6 4M10 7.5a2.5 2.5 0 100-5 2.5 2.5 0 000 5z', 'title' => 'Front Office', 'desc' => 'Melayani kebutuhan awal'],
                    ] as $unit)
                    <div class="group rounded-2xl border border-cyan-500/20 bg-gradient-to-br from-slate-900/90 to-slate-950/95 p-6 transition-all hover:border-cyan-400/50 hover:shadow-[0_0_40px_rgba(0,212,255,0.15)]">
                        <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl border border-cyan-500/30 bg-cyan-500/10">
                            <svg class="h-6 w-6 text-cyan-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $unit['icon'] }}"/></svg>
                        </div>
                        <h3 class="mb-2 font-mono text-lg font-semibold uppercase tracking-wider text-white group-hover:text-cyan-300">{{ $unit['title'] }}</h3>
                        <p class="text-sm text-slate-400">{{ $unit['desc'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- Contact Section -->
        <section id="contact" class="relative py-12 bg-gradient-to-b from-slate-950 to-slate-900">

            <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6">
                <div class="grid gap-8 lg:grid-cols-2">
                    <!-- Contact -->
                    <div>
                        <span class="inline-flex items-center gap-2 rounded-full border border-cyan-500/30 bg-cyan-500/10 px-4 py-1 font-mono text-xs font-semibold uppercase tracking-widest text-cyan-400">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            Contact
                        </span>
                        <h2 class="mt-4 mb-6 font-mono text-3xl font-bold uppercase tracking-wider text-white">Hubungi Kami</h2>
                        <p class="mb-8 text-base text-slate-400">Jika ada pertanyaan atau butuh bantuan, silakan hubungi tim kami.</p>

                        <div class="space-y-4">
                            @foreach([
                                ['icon' => 'M4.5 5.5h11v9h-11zM5.5 6.5l4.5 3 4.5-3', 'label' => 'Email', 'value' => 'kontak@kemenagtd.go.id'],
                                ['icon' => 'M7 4.5h6A2.5 2.5 0 0115.5 7v6A2.5 2.5 0 0113 15.5H7A2.5 2.5 0 014.5 13V7A2.5 2.5 0 017 4.5ZM8 9.5l2 1.5 2-1.5', 'label' => 'Telepon', 'value' => '(0752) 555-0123'],
                                ['icon' => 'M10 18s5-4.5 5-9a5 5 0 00-10 0c0 4.5 5 9 5 9Z M10 9.5a1.8 1.8 0 100-3.6 1.8 1.8 0 000 3.6Z', 'label' => 'Alamat', 'value' => 'Jl. Pancasila No. 12, Batusangkar'],
                            ] as $contact)
                            <div class="flex items-center gap-4 rounded-xl border border-cyan-500/20 bg-slate-900/50 p-4">
                                <div class="flex h-12 w-12 items-center justify-center rounded-xl border border-cyan-500/30 bg-cyan-500/10">
                                    <svg class="h-5 w-5 text-cyan-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $contact['icon'] }}"/></svg>
                                </div>
                                <div>
                                    <p class="font-mono text-xs font-semibold uppercase tracking-wider text-cyan-400/70">{{ $contact['label'] }}</p>
                                    <p class="font-mono text-sm text-white">{{ $contact['value'] }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Hours -->
                    <div>
                        <span class="inline-flex items-center gap-2 rounded-full border border-cyan-500/30 bg-cyan-500/10 px-4 py-1 font-mono text-xs font-semibold uppercase tracking-widest text-cyan-400">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                            Hours
                        </span>
                        <h2 class="mt-4 mb-6 font-mono text-3xl font-bold uppercase tracking-wider text-white">Jam Layanan</h2>

                        <div class="space-y-3">
                            @foreach([
                                ['day' => 'Senin - Kamis', 'time' => '08.00 - 16.00 WIB', 'active' => true],
                                ['day' => 'Jumat', 'time' => '08.00 - 16.30 WIB', 'active' => true],
                                ['day' => 'Sabtu - Minggu', 'time' => 'Tutup', 'active' => false],
                            ] as $schedule)
                            <div class="flex items-center justify-between rounded-xl px-5 py-4 @if($schedule['active']) border-emerald-500/30 bg-emerald-500/10 @else border-slate-700/50 bg-slate-900/50 @endif border">
                                <div class="flex items-center gap-3">
                                    @if($schedule['active'])
                                    <span class="relative flex h-3 w-3">
                                        <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-75"></span>
                                        <span class="relative inline-flex h-3 w-3 rounded-full bg-emerald-500"></span>
                                    </span>
                                    @else
                                    <span class="h-3 w-3 rounded-full bg-slate-600"></span>
                                    @endif
                                    <span class="font-mono text-sm font-medium text-white">{{ $schedule['day'] }}</span>
                                </div>
                                <span class="font-mono text-sm @if($schedule['active']) text-emerald-400 @else text-slate-500 @endif">{{ $schedule['time'] }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="relative border-t border-cyan-500/20 bg-slate-950 py-8">

            <div class="max-w-7xl mx-auto px-4 sm:px-6">
                <div class="flex flex-col items-center justify-between gap-6 md:flex-row">
                    <div class="text-center md:text-left">
                        <p class="font-mono text-lg font-bold uppercase tracking-[0.2em] text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-cyan-300">SILATAR</p>
                        <p class="mt-2 font-mono text-xs uppercase tracking-wider text-slate-500">Sistem Informasi, Layanan, dan Administrasi Kankemenag Tanah Datar.</p>
                    </div>
                    <div class="flex flex-wrap items-center justify-center gap-6 font-mono text-sm text-slate-500">
                        <a href="{{ url('/#home') }}" class="transition-colors hover:text-cyan-400">Home</a>
                        <a href="{{ route('satuan-kerja') }}" class="transition-colors hover:text-cyan-400">Units</a>
                        <a href="{{ route('pelayanan') }}" class="transition-colors hover:text-cyan-400">Services</a>
                        <a href="{{ url('/#contact') }}" class="transition-colors hover:text-cyan-400">Contact</a>
                    </div>
                </div>
                <div class="mt-8 h-px bg-gradient-to-r from-transparent via-cyan-500/30 to-transparent"></div>
                <p class="mt-8 text-center font-mono text-xs uppercase tracking-wider text-slate-600">&copy; {{ date('Y') }} SILATAR. All rights reserved.</p>
            </div>
        </footer>
    </main>
</x-layouts.app>