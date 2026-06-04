<header class="fixed top-0 left-0 right-0 z-50 px-4 py-3">
    <div class="mx-auto max-w-7xl">
        <nav
            x-data="siteNav"
            class="rounded-2xl border border-cyan-500/30 bg-slate-950/95 px-5 py-3 shadow-[0_0_30px_rgba(0,212,255,0.15)] backdrop-blur-xl"
        >
            <div class="flex items-center justify-between gap-6">
                {{-- Logo --}}
                <a href="{{ url('/') }}" class="flex items-center gap-3 shrink-0 group">
                    <div class="relative flex h-11 w-11 items-center justify-center rounded-xl border border-cyan-500/40 bg-gradient-to-br from-cyan-500/20 to-cyan-600/10 shadow-[0_0_15px_rgba(0,212,255,0.3)]">
                        <span class="font-mono text-sm font-bold uppercase tracking-wider text-cyan-400">KT</span>
                    </div>
                    <div class="hidden sm:block">
                        <p class="font-mono text-sm font-bold uppercase tracking-[0.2em] text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-cyan-300">SILATAR</p>
                        <p class="font-mono text-[9px] uppercase tracking-widest text-slate-500">Kankemenag Tanah Datar</p>
                    </div>
                </a>

                {{-- Desktop Navigation --}}
                <div class="hidden items-center gap-1 lg:flex">
                <a href="{{ url('/#home') }}" class="group relative flex items-center gap-2 rounded-xl px-5 py-2.5 font-mono text-sm font-medium uppercase tracking-wider text-slate-400 transition-all hover:text-cyan-400">
                    <span class="relative z-10">Home</span>
                    <span class="absolute inset-0 rounded-xl bg-cyan-500/10 opacity-0 transition-opacity group-hover:opacity-100"></span>
                    <span class="absolute bottom-0 left-1/2 h-0.5 w-0 -translate-x-1/2 bg-cyan-400 shadow-[0_0_10px_rgba(0,212,255,0.5)] transition-all duration-300 group-hover:w-4/5"></span>
                </a>
                <a href="{{ route('satuan-kerja') }}" class="group relative flex items-center gap-2 rounded-xl px-5 py-2.5 font-mono text-sm font-medium uppercase tracking-wider text-slate-400 transition-all hover:text-cyan-400">
                    <span class="relative z-10">Unit Kerja</span>
                    <span class="absolute inset-0 rounded-xl bg-cyan-500/10 opacity-0 transition-opacity group-hover:opacity-100"></span>
                    <span class="absolute bottom-0 left-1/2 h-0.5 w-0 -translate-x-1/2 bg-cyan-400 shadow-[0_0_10px_rgba(0,212,255,0.5)] transition-all duration-300 group-hover:w-4/5"></span>
                </a>
                <a href="{{ route('pelayanan') }}" class="group relative flex items-center gap-2 rounded-xl px-5 py-2.5 font-mono text-sm font-medium uppercase tracking-wider text-slate-400 transition-all hover:text-cyan-400">
                    <span class="relative z-10">Pelayanan</span>
                    <span class="absolute inset-0 rounded-xl bg-cyan-500/10 opacity-0 transition-opacity group-hover:opacity-100"></span>
                    <span class="absolute bottom-0 left-1/2 h-0.5 w-0 -translate-x-1/2 bg-cyan-400 shadow-[0_0_10px_rgba(0,212,255,0.5)] transition-all duration-300 group-hover:w-4/5"></span>
                </a>
                <a href="{{ url('/#contact') }}" class="group relative flex items-center gap-2 rounded-xl px-5 py-2.5 font-mono text-sm font-medium uppercase tracking-wider text-slate-400 transition-all hover:text-cyan-400">
                    <span class="relative z-10">Kontak</span>
                    <span class="absolute inset-0 rounded-xl bg-cyan-500/10 opacity-0 transition-opacity group-hover:opacity-100"></span>
                    <span class="absolute bottom-0 left-1/2 h-0.5 w-0 -translate-x-1/2 bg-cyan-400 shadow-[0_0_10px_rgba(0,212,255,0.5)] transition-all duration-300 group-hover:w-4/5"></span>
                </a>
            </div>

            {{-- Auth Buttons --}}
            <div class="flex items-center gap-3">
                @auth
                    <div class="relative">
                        <button
                            type="button"
                            @click="toggleUserMenu()"
                            class="group flex items-center gap-3 rounded-full border border-cyan-500/30 bg-slate-900/80 px-4 py-2 backdrop-blur-sm transition-all hover:border-cyan-400/50 hover:bg-slate-900"
                        >
                            <div class="flex h-9 w-9 items-center justify-center overflow-hidden rounded-full bg-gradient-to-br from-cyan-500 to-cyan-600 text-sm font-bold text-white shadow-[0_0_15px_rgba(0,212,255,0.3)]">
                                @if (auth()->user()->pp && auth()->user()->nomor_induk)
                                    <img
                                        src="{{ asset('assets/img/users/' . auth()->user()->nomor_induk . '/' . auth()->user()->pp) }}"
                                        alt="{{ auth()->user()->name }}"
                                        class="h-full w-full object-cover"
                                        onerror="this.style.display='none'; this.parentElement.textContent='{{ \Illuminate\Support\Str::of(auth()->user()->name)->substr(0, 2)->upper() }}';"
                                    >
                                @else
                                    {{ \Illuminate\Support\Str::of(auth()->user()->name)->substr(0, 2)->upper() }}
                                @endif
                            </div>
                            <span class="hidden md:block">
                                <p class="font-mono text-sm font-semibold text-cyan-300">{{ auth()->user()->name }}</p>
                            </span>
                            <svg class="h-4 w-4 text-cyan-500 transition-transform group-hover:rotate-180" :class="userMenuOpen ? 'rotate-180' : ''" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m19 9-7 7-7-7" />
                            </svg>
                        </button>

                        <div
                            x-show="userMenuOpen"
                            x-cloak
                            @click.outside="closeUserMenu()"
                            class="absolute right-0 top-full mt-3 w-72 overflow-hidden rounded-xl border border-cyan-500/20 bg-slate-950/95 shadow-[0_0_30px_rgba(0,212,255,0.1)] backdrop-blur-xl"
                        >
                            <div class="border-b border-cyan-500/20 bg-gradient-to-r from-cyan-500/10 to-transparent px-5 py-4">
                                <p class="font-mono text-xs font-semibold uppercase tracking-wider text-cyan-400/70">Logged in as</p>
                                <p class="mt-1 font-mono text-base font-semibold text-cyan-300">{{ auth()->user()->name }}</p>
                                <p class="font-mono text-xs text-slate-500">{{ auth()->user()->nomor_induk }}</p>
                            </div>
                            <div class="p-2">
                                <a href="{{ url('/') }}" class="flex items-center gap-3 rounded-lg px-4 py-3 font-mono text-sm font-medium text-slate-400 transition-all hover:bg-cyan-500/10 hover:text-cyan-400">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                                    </svg>
                                    Dashboard
                                </a>
                                @if(in_array(auth()->user()->role, ['admin', 'superadmin']))
                                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 rounded-lg px-4 py-3 font-mono text-sm font-medium text-slate-400 transition-all hover:bg-cyan-500/10 hover:text-cyan-400">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    Admin Panel
                                </a>
                                @endif
                                <a href="{{ route('pengajuan-saya') }}" class="flex items-center gap-3 rounded-lg px-4 py-3 font-mono text-sm font-medium text-slate-400 transition-all hover:bg-cyan-500/10 hover:text-cyan-400">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    Requests
                                </a>
                                <a href="{{ route('laporan-kinerja') }}" class="flex items-center gap-3 rounded-lg px-4 py-3 font-mono text-sm font-medium text-slate-400 transition-all hover:bg-cyan-500/10 hover:text-cyan-400">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                    Laporan Kinerja
                                </a>
                                <a href="{{ route('profil') }}" class="flex items-center gap-3 rounded-lg px-4 py-3 font-mono text-sm font-medium text-slate-400 transition-all hover:bg-cyan-500/10 hover:text-cyan-400">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    Profil
                                </a>
                            </div>
                            <div class="border-t border-cyan-500/20 bg-slate-900/50 p-2">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="flex w-full items-center gap-3 rounded-lg px-4 py-3 font-mono text-sm font-semibold text-rose-400 transition-all hover:bg-rose-500/10">
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                        </svg>
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="group relative flex items-center gap-2 rounded-full border border-cyan-500/30 bg-slate-900/80 px-6 py-2.5 font-mono text-sm font-semibold uppercase tracking-wider text-cyan-400 backdrop-blur-sm transition-all hover:bg-cyan-500/10 hover:border-cyan-400/50">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 4h4.5A1.5 1.5 0 0016 5.5v13A1.5 1.5 0 0014.5 20H11M7 10l5 5-5 5M11 15H4"/>
                        </svg>
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="group relative inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-cyan-600 to-cyan-500 px-6 py-2.5 font-mono text-sm font-semibold uppercase tracking-wider text-white shadow-[0_0_20px_rgba(0,212,255,0.4)] transition-all hover:shadow-[0_0_30px_rgba(0,212,255,0.6)] hover:-translate-y-0.5">
                        Register
                        <svg class="h-4 w-4 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </a>
                @endauth
            </div>

            {{-- Mobile Menu Toggle --}}
            <button
                type="button"
                class="inline-flex h-11 w-11 items-center justify-center rounded-xl border border-cyan-500/30 bg-slate-900/80 text-cyan-400 backdrop-blur-sm transition-all hover:border-cyan-400/50 lg:hidden"
                @click="toggle()"
                :aria-expanded="open.toString()"
                aria-label="Toggle menu"
            >
                <svg x-show="!open" x-cloak class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <svg x-show="open" x-cloak class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Mobile Menu --}}
        <div x-show="open" x-cloak class="mt-4 rounded-xl border border-cyan-500/20 bg-slate-950/95 p-4 backdrop-blur-xl lg:hidden">
            <div class="grid gap-2">
                <a href="{{ url('/#home') }}" class="group flex items-center gap-4 rounded-xl px-4 py-3 font-mono text-sm font-medium uppercase tracking-wider text-slate-400 transition-all hover:bg-cyan-500/10 hover:text-cyan-400">
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl border border-cyan-500/30 bg-cyan-500/10 text-cyan-400">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 9.5L12 4l9 5.5M5.5 8.5V17h13V8.5" />
                        </svg>
                    </span>
                    Home
                </a>
                <a href="{{ route('satuan-kerja') }}" class="group flex items-center gap-4 rounded-xl px-4 py-3 font-mono text-sm font-medium uppercase tracking-wider text-slate-400 transition-all hover:bg-cyan-500/10 hover:text-cyan-400">
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl border border-cyan-500/30 bg-cyan-500/10 text-cyan-400">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </span>
                    Unit Kerja
                </a>
                <a href="{{ route('pelayanan') }}" class="group flex items-center gap-4 rounded-xl px-4 py-3 font-mono text-sm font-medium uppercase tracking-wider text-slate-400 transition-all hover:bg-cyan-500/10 hover:text-cyan-400">
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl border border-cyan-500/30 bg-cyan-500/10 text-cyan-400">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                    </span>
                    Pelayanan
                </a>
                <a href="{{ url('/#contact') }}" class="group flex items-center gap-4 rounded-xl px-4 py-3 font-mono text-sm font-medium uppercase tracking-wider text-slate-400 transition-all hover:bg-cyan-500/10 hover:text-cyan-400">
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl border border-cyan-500/30 bg-cyan-500/10 text-cyan-400">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </span>
                    Kontak
                </a>
                @auth
                    <div class="mt-4 rounded-xl border border-cyan-500/20 bg-slate-900/50 p-4">
                        <p class="font-mono text-xs font-semibold uppercase tracking-wider text-cyan-400/70">Logged in as</p>
                        <p class="mt-1 font-mono text-sm font-semibold text-cyan-300">{{ auth()->user()->name }}</p>
                        <form class="mt-4" method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full rounded-lg bg-gradient-to-r from-rose-600/80 to-rose-500/80 px-4 py-3 font-mono text-sm font-semibold text-white shadow-[0_0_15px_rgba(239,68,68,0.3)] transition-all hover:shadow-[0_0_25px_rgba(239,68,68,0.4)]">
                                Logout
                            </button>
                        </form>
                    </div>
                @else
                    <div class="mt-4 grid grid-cols-2 gap-3">
                        <a href="{{ route('login') }}" class="flex items-center justify-center gap-2 rounded-xl border border-cyan-500/30 bg-slate-900/80 px-4 py-4 font-mono text-sm font-semibold uppercase tracking-wider text-cyan-400 backdrop-blur-sm">
                            Login
                        </a>
                        <a href="{{ route('register') }}" class="flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-cyan-600 to-cyan-500 px-4 py-4 font-mono text-sm font-semibold uppercase tracking-wider text-white shadow-[0_0_15px_rgba(0,212,255,0.3)]">
                            Register
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </nav>
</header>