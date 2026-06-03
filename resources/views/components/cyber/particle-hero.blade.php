@props([
    'badge' => 'SYSTEM ONLINE',
    'title' => 'SILATAR',
    'subtitle' => 'Sistem Informasi Layanan dan Administrasi Terpadu',
    'description' => 'Portal layanan digital untuk Kankemenag Tanah Datar. Dirancang untuk memberikan pengalaman akses informasi yang cepat, transparan, dan mudah.',
    'ctaPrimary' => [
        'text' => 'Masuk ke Sistem',
        'url' => '/login'
    ],
    'ctaSecondary' => [
        'text' => 'Pelajari Lebih',
        'url' => '#features'
    ],
    'showParticles' => true,
])

<section
    class="relative min-h-screen overflow-hidden bg-gradient-to-b from-slate-950 via-slate-900 to-slate-950"
    x-data="{
        mouseX: 0,
        mouseY: 0,
        glitching: false,
        updateMouse(e) {
            this.mouseX = e.clientX;
            this.mouseY = e.clientY;
        },
        startGlitch() {
            this.glitching = true;
            setTimeout(() => {
                this.glitching = false;
            }, 200);
        }
    }"
    x-on:mousemove.window="updateMouse($event)"
>
    <!-- Animated Grid Background -->
    <div class="absolute inset-0 bg-[linear-gradient(rgba(0,212,255,0.08)_1px,transparent_1px),linear-gradient(90deg,rgba(0,212,255,0.08)_1px,transparent_1px)] bg-[size:60px_60px] opacity-30"></div>

    <!-- Particle Canvas -->
    @if($showParticles)
        <div class="pointer-events-none absolute inset-0 overflow-hidden" style="z-index: 1;">
            <canvas
                id="heroParticles"
                data-particle-canvas
                data-particle-count="100"
                data-mouse-influence="true"
                class="absolute inset-0"
                style="width: 100%; height: 100%;"
            ></canvas>
        </div>
    @endif

    <!-- Gradient Overlays -->
    <div class="absolute inset-0 bg-gradient-to-b from-transparent via-cyan-500/5 to-transparent"></div>
    <div class="absolute inset-0 bg-gradient-to-r from-cyan-500/10 via-transparent to-purple-500/10"></div>

    <!-- Mouse Follow Glow -->
    <div
        class="pointer-events-none absolute h-96 w-96 rounded-full bg-cyan-500/10 blur-3xl transition-transform duration-1000 ease-out"
        :style="'transform: translate(' + (mouseX - 192) + 'px, ' + (mouseY - 192) + 'px)'"
    ></div>

    <!-- Content Container -->
    <div class="relative z-10 mx-auto max-w-7xl px-6 py-20 lg:px-8">
        <!-- Top Badge -->
        <div class="mb-8 flex justify-center">
            <div class="inline-flex items-center gap-2 rounded-full border border-cyan-500/30 bg-cyan-500/10 px-5 py-2 backdrop-blur-sm">
                <span class="relative flex h-2.5 w-2.5">
                    <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-cyan-400 opacity-75"></span>
                    <span class="relative inline-flex h-2.5 w-2.5 rounded-full bg-cyan-500"></span>
                </span>
                <span class="font-mono text-sm font-medium uppercase tracking-widest text-cyan-400">{{ $badge }}</span>
            </div>
        </div>

        <!-- Main Title -->
        <div class="text-center">
            <h1
                class="mb-4 font-mono text-6xl font-black uppercase tracking-[0.15em] text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 via-cyan-300 to-cyan-500 sm:text-7xl lg:text-8xl"
                :class="{ 'glitch-text': glitching }"
            >
                {{ $title }}
            </h1>

            <p class="mx-auto mb-6 max-w-2xl font-mono text-lg uppercase tracking-wider text-cyan-400/80 sm:text-xl">
                {{ $subtitle }}
            </p>

            <p class="mx-auto mb-10 max-w-3xl text-base leading-relaxed text-slate-400 sm:text-lg">
                {{ $description }}
            </p>

            <!-- CTA Buttons -->
            <div class="flex flex-col items-center justify-center gap-4 sm:flex-row">
                <a
                    href="{{ $ctaPrimary['url'] }}"
                    class="group relative inline-flex items-center gap-3 rounded-full bg-gradient-to-r from-cyan-600 to-cyan-500 px-8 py-4 font-semibold text-white shadow-[0_0_30px_rgba(0,212,255,0.4)] transition-all duration-300 hover:shadow-[0_0_50px_rgba(0,212,255,0.6)] hover:-translate-y-1 active:translate-y-0"
                >
                    <span>{{ $ctaPrimary['text'] }}</span>
                    <svg class="h-5 w-5 transition-transform duration-300 group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                    <div class="absolute inset-0 rounded-full bg-white/20 opacity-0 transition-opacity duration-300 group-hover:opacity-100"></div>
                </a>

                <a
                    href="{{ $ctaSecondary['url'] }}"
                    class="inline-flex items-center gap-3 rounded-full border border-cyan-500/40 bg-cyan-500/10 px-8 py-4 font-semibold text-cyan-400 backdrop-blur-sm transition-all duration-300 hover:bg-cyan-500/20 hover:border-cyan-400/60"
                >
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                    <span>{{ $ctaSecondary['text'] }}</span>
                </a>
            </div>
        </div>

        <!-- Scroll Indicator -->
        <div class="mt-16 flex justify-center">
            <div class="flex flex-col items-center gap-2 text-cyan-500/50">
                <span class="font-mono text-xs uppercase tracking-widest">Scroll</span>
                <div class="h-12 w-6 rounded-full border-2 border-current p-1">
                    <div class="h-2 w-2 animate-bounce rounded-full bg-current"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Scan Line Effect -->
    <div class="absolute bottom-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-cyan-500/50 to-transparent"></div>

    <!-- Corner Decorations -->
    <div class="absolute left-0 top-0 h-20 w-20 border-l-2 border-t-2 border-cyan-500/30"></div>
    <div class="absolute right-0 top-0 h-20 w-20 border-r-2 border-t-2 border-cyan-500/30"></div>
</section>