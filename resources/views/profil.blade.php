<x-layouts.app title="Profil - SILATAR">
    <main class="silatar-report-page space-y-6">
        <!-- Page Header -->
        <div class="relative bg-gradient-to-b from-slate-950 via-slate-900 to-slate-950 py-8 overflow-hidden">
            <!-- Cyberpunk Background Effects -->
            <div class="absolute inset-0 opacity-30">
                <!-- Grid Pattern -->
                <div class="absolute inset-0" style="background-image: linear-gradient(rgba(0,212,255,0.03) 1px, transparent 1px), linear-gradient(90deg, rgba(0,212,255,0.03) 1px, transparent 1px); background-size: 50px 50px;"></div>
                <!-- Scan Lines -->
                <div class="absolute inset-0 bg-gradient-to-b from-transparent via-cyan-500/5 to-transparent" style="background-size: 100% 4px;"></div>
            </div>

            <!-- Corner Decorations -->
            <div class="absolute top-4 left-4 h-16 w-16 border-l-2 border-t-2 border-cyan-500/40"></div>
            <div class="absolute top-4 right-4 h-16 w-16 border-r-2 border-t-2 border-cyan-500/40"></div>
            <div class="absolute bottom-4 left-4 h-16 w-16 border-b-2 border-l-2 border-cyan-500/40"></div>
            <div class="absolute bottom-4 right-4 h-16 w-16 border-b-2 border-r-2 border-cyan-500/40"></div>

            <!-- Floating Particles -->
            <div class="absolute top-20 left-[10%] h-2 w-2 rounded-full bg-cyan-400/50 animate-pulse"></div>
            <div class="absolute top-32 right-[15%] h-1 w-1 rounded-full bg-cyan-300/70 animate-pulse" style="animation-delay: 0.5s;"></div>
            <div class="absolute bottom-20 left-[20%] h-1.5 w-1.5 rounded-full bg-cyan-500/40 animate-pulse" style="animation-delay: 1s;"></div>
            <div class="absolute top-40 right-[25%] h-1 w-1 rounded-full bg-cyan-400/60 animate-pulse" style="animation-delay: 1.5s;"></div>

            <div class="relative mx-auto max-w-6xl px-6 lg:px-8 text-center">
                <span class="inline-flex items-center gap-2 rounded-full border border-cyan-500/30 bg-cyan-500/10 px-4 py-1.5 font-mono text-xs font-semibold uppercase tracking-widest text-cyan-400 shadow-[0_0_20px_rgba(0,212,255,0.3)]">
                    <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Profil
                </span>
                <h1 class="mt-4 font-mono text-3xl font-black uppercase tracking-wider text-white lg:text-4xl drop-shadow-[0_0_30px_rgba(0,212,255,0.5)]">
                   Profil Saya
                </h1>
            </div>

            <!-- User Info Card -->
            <div class="relative mx-auto mt-6 max-w-xl">
                <div class="relative flex flex-col items-center rounded-2xl border border-cyan-500/30 bg-slate-900/80 px-8 py-8 shadow-[0_0_40px_rgba(0,212,255,0.15)] backdrop-blur-sm">
                    <!-- Inner Glow Border -->
                    <div class="absolute inset-0 rounded-2xl border border-cyan-500/10"></div>

                    <!-- Corner Tech Decorations -->
                    <div class="absolute -top-px left-8 h-4 w-4 border-l-2 border-t-2 border-cyan-400/60"></div>
                    <div class="absolute -top-px right-8 h-4 w-4 border-r-2 border-t-2 border-cyan-400/60"></div>
                    <div class="absolute -bottom-px left-8 h-4 w-4 border-b-2 border-l-2 border-cyan-400/60"></div>
                    <div class="absolute -bottom-px right-8 h-4 w-4 border-b-2 border-r-2 border-cyan-400/60"></div>

                    <!-- Pulse Ring behind Avatar -->
                    <div class="absolute top-16 h-36 w-36 rounded-full border border-cyan-500/20 animate-pulse"></div>

                    <!-- Large Avatar -->
                    <div class="relative flex h-32 w-32 items-center justify-center overflow-hidden rounded-3xl border-4 border-cyan-500/50 bg-gradient-to-br from-cyan-500/30 to-cyan-600/20 shadow-[0_0_50px_rgba(0,212,255,0.3)]">
                        @if($user->pp && $user->nomor_induk)
                            <img src="{{ asset('assets/img/users/' . $user->nomor_induk . '/' . $user->pp) }}" alt="{{ $user->name }}" class="h-full w-full object-cover">
                        @else
                            <span class="font-mono text-4xl font-bold uppercase text-cyan-400">{{ substr($user->name, 0, 2) }}</span>
                        @endif
                    </div>

                    <!-- Scan line on avatar -->
                    <div class="absolute top-16 h-32 w-32 overflow-hidden rounded-3xl">
                        <div class="absolute inset-0 bg-gradient-to-b from-transparent via-cyan-400/10 to-transparent animate-pulse" style="height: 4px; top: 0;"></div>
                    </div>

                    <!-- Info -->
                    <div class="relative mt-6 text-center">
                        <h2 class="font-mono text-2xl font-bold text-white drop-shadow-[0_0_10px_rgba(0,212,255,0.3)]">{{ $user->name }}</h2>
                        <p class="mt-2 font-mono text-base text-cyan-400">{{ $user->nomor_induk }}</p>
                        <div class="mt-3 flex items-center justify-center gap-3">
                            <span class="h-px flex-1 bg-gradient-to-r from-transparent via-cyan-500/30 to-transparent"></span>
                            <p class="text-sm text-slate-400">
                                {{ $user->pekerjaan ?? '-' }}
                                @if($userDept)
                                    <span class="mx-2 text-cyan-500/50">|</span>
                                    {{ $userDept }}
                                @endif
                            </p>
                            <span class="h-px flex-1 bg-gradient-to-r from-transparent via-cyan-500/30 to-transparent"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Menu Section -->
        <section class="relative mx-auto max-w-6xl px-6 lg:px-8">
            <!-- Section Header Glow -->
            <div class="absolute inset-x-0 -top-4 h-px bg-gradient-to-r from-transparent via-cyan-500/50 to-transparent"></div>

            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($menuItems as $index => $item)
                    <a href="{{ $item['route'] ? route($item['route']) : '#' }}" class="group relative flex h-56 flex-col items-center justify-center overflow-hidden rounded-3xl border border-cyan-500/30 bg-gradient-to-br from-slate-900 via-slate-800/90 to-slate-900 shadow-xl transition-all duration-300 hover:border-cyan-400/60 hover:shadow-[0_0_60px_rgba(0,212,255,0.3)]">
                        <!-- Background Effects -->
                        <div class="absolute inset-0">
                            <!-- Grid Pattern -->
                            <div class="absolute inset-0 opacity-10" style="background-image: linear-gradient(rgba(0,212,255,0.1) 1px, transparent 1px), linear-gradient(90deg, rgba(0,212,255,0.1) 1px, transparent 1px); background-size: 20px 20px;"></div>
                            <!-- Scan Lines -->
                            <div class="absolute inset-0 bg-gradient-to-b from-transparent via-cyan-500/2 to-transparent" style="background-size: 100% 3px;"></div>
                        </div>

                        <!-- Large Centered Background Icon with Glow -->
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="absolute opacity-30 saturate-[1.5] transition-all duration-500 group-hover:opacity-50 group-hover:scale-110">
                                <div class="rounded-full bg-gradient-to-br from-cyan-500/20 via-transparent to-transparent blur-xl"></div>
                            </div>
                            <div class="relative -mt-4 opacity-40 transition-all duration-500 group-hover:opacity-60 group-hover:scale-110">
                                <img src="{{ asset('assets/img/ikon/' . $item['icon']) }}" alt="" class="h-58 w-58 object-contain drop-shadow-[0_0_20px_rgba(0,212,255,0.5)]" onerror="this.style.display='none'">
                            </div>
                        </div>

                        <!-- Corner Decorations -->
                        <div class="absolute top-3 left-3 h-6 w-6 border-l border-t border-cyan-500/40 transition-colors duration-300 group-hover:border-cyan-400/60"></div>
                        <div class="absolute top-3 right-3 h-6 w-6 border-r border-t border-cyan-500/40 transition-colors duration-300 group-hover:border-cyan-400/60"></div>
                        <div class="absolute bottom-3 left-3 h-6 w-6 border-b border-l border-cyan-500/40 transition-colors duration-300 group-hover:border-cyan-400/60"></div>
                        <div class="absolute bottom-3 right-3 h-6 w-6 border-b border-r border-cyan-500/40 transition-colors duration-300 group-hover:border-cyan-400/60"></div>

                        <!-- Pulse Dot -->
                        <div class="absolute top-4 right-4 h-2 w-2 rounded-full bg-cyan-400/60 animate-ping"></div>

                        <!-- Content -->
                        <div class="relative z-10 px-6 text-center mt-16">
                            <h3 class="font-mono text-lg font-bold uppercase tracking-wider text-white drop-shadow-[0_2px_8px_rgba(0,0,0,0.9)] transition-all duration-300 group-hover:text-cyan-300 group-hover:drop-shadow-[0_0_15px_rgba(0,212,255,0.5)]">
                                {{ $item['title'] }}
                            </h3>
                            <div class="mt-3 flex items-center justify-center gap-2 text-sm text-cyan-400/70 transition-all duration-300 group-hover:text-cyan-400">
                                <span>Lihat Detail</span>
                                <svg class="h-4 w-4 transition-transform duration-300 group-hover:translate-x-1" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                        </div>

                        <!-- Bottom Glow Line -->
                        <div class="absolute bottom-0 left-0 h-1 w-0 bg-gradient-to-r from-cyan-400 via-cyan-500 to-cyan-600 transition-all duration-500 group-hover:w-full shadow-[0_0_20px_rgba(0,212,255,0.5)]"></div>

                        <!-- Circuit Lines -->
                        <div class="absolute left-0 top-1/2 h-px w-0 bg-gradient-to-r from-cyan-400/0 via-cyan-400/60 to-transparent transition-all duration-700 group-hover:w-12"></div>
                        <div class="absolute right-0 top-1/2 h-px w-0 bg-gradient-to-l from-cyan-400/0 via-cyan-400/60 to-transparent transition-all duration-700 group-hover:w-12"></div>
                    </a>
                @endforeach
            </div>
        </section>

        <!-- Bottom Glow -->
        <div class="mx-auto h-px w-full max-w-4xl bg-gradient-to-r from-transparent via-cyan-500/30 to-transparent"></div>
    </main>
</x-layouts.app>