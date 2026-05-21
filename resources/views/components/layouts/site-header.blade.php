<header class="sticky top-0 z-50 mx-auto max-w-7xl px-6 pt-6 lg:px-8">
    <nav x-data="siteNav" class="rounded-[2rem] border border-slate-200/80 bg-white/90 px-4 py-4 shadow-sm backdrop-blur">
        <div class="flex items-center justify-between gap-5">
            <a href="{{ url('/') }}" class="flex items-center gap-3">
                <div class="grid h-12 w-12 place-items-center rounded-2xl bg-cyan-600 text-sm font-bold text-white shadow-sm">
                    KT
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-semibold uppercase tracking-[0.16em] text-slate-900">SILATAR</p>
                    <p class="truncate text-xs text-slate-500">Sistem Informasi, Layanan, dan Administrasi Kankemenag Tanah Datar</p>
                </div>
            </a>

            <div class="hidden items-center gap-1.5 text-sm font-medium text-slate-600 xl:flex">
                <a href="{{ url('/#home') }}" class="inline-flex items-center gap-2 rounded-full px-2.5 py-2 transition hover:bg-slate-100 hover:text-slate-900">
                    <svg class="h-4 w-4 text-cyan-600" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.7">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 9.5 10 4l7 5.5" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5.5 8.5V16h9V8.5" />
                    </svg>
                    Home
                </a>
                <a href="{{ route('satuan-kerja') }}" class="inline-flex items-center gap-2 rounded-full px-2.5 py-2 transition hover:bg-slate-100 hover:text-slate-900">
                    <svg class="h-4 w-4 text-teal-600" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.7">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.5 16V5.5h5V4h3v1.5h5V16" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 8h6M7 11h6M7 14h3" />
                    </svg>
                    Satuan Kerja
                </a>
                <a href="{{ route('pelayanan') }}" class="inline-flex items-center gap-2 rounded-full px-2.5 py-2 transition hover:bg-slate-100 hover:text-slate-900">
                    <svg class="h-4 w-4 text-indigo-600" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.7">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 5h8M6 9h8M6 13h5" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 3.5h10A1.5 1.5 0 0 1 16.5 5v10A1.5 1.5 0 0 1 15 16.5H5A1.5 1.5 0 0 1 3.5 15V5A1.5 1.5 0 0 1 5 3.5Z" />
                    </svg>
                    Pelayanan
                </a>
                <a href="{{ url('/#kontak-kami') }}" class="inline-flex items-center gap-2 rounded-full px-2.5 py-2 transition hover:bg-slate-100 hover:text-slate-900">
                    <svg class="h-4 w-4 text-rose-500" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.7">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6.5A2.5 2.5 0 0 1 6.5 4h7A2.5 2.5 0 0 1 16 6.5v7A2.5 2.5 0 0 1 13.5 16h-7A2.5 2.5 0 0 1 4 13.5z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 7.5 10 10l4-2.5" />
                    </svg>
                    Kontak Kami
                </a>
            </div>

            <div class="hidden items-center gap-2 lg:flex">
                @auth
                    <div class="relative">
                        <button
                            type="button"
                            @click="toggleUserMenu()"
                            class="flex items-center gap-3 rounded-full border border-slate-200 bg-white px-3 py-2 text-left shadow-sm transition hover:border-cyan-200 hover:shadow-md"
                        >
                            <div class="grid h-9 w-9 place-items-center overflow-hidden rounded-full bg-slate-200 text-xs font-bold text-slate-700">
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
                            <div class="min-w-0">
                                <p class="truncate text-sm font-semibold text-slate-900">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-slate-500">Menu akun</p>
                            </div>
                            <svg class="h-4 w-4 text-rose-600 transition" :class="userMenuOpen ? 'rotate-180' : ''" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m5 8 5 5 5-5" />
                            </svg>
                        </button>

                        <div
                            x-show="userMenuOpen"
                            x-cloak
                            @click.outside="closeUserMenu()"
                            class="absolute right-0 mt-3 w-80 overflow-hidden rounded-[1.5rem] border border-slate-200 bg-white shadow-[0_24px_80px_rgba(15,23,42,0.18)]"
                        >
                            <div class="border-b border-slate-100 bg-slate-50 px-4 py-3">
                                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Akun aktif</p>
                                <p class="mt-1 text-sm font-semibold text-slate-900">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-slate-500">{{ auth()->user()->nomor_induk }}</p>
                            </div>
                            <div class="p-2">
                                <a href="{{ url('/') }}" class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-50">
                                    <svg class="h-4 w-4 text-indigo-600" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 9.5 10 4l7 5.5" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5.5 8.5V16h9V8.5" />
                                    </svg>
                                    Dashboard
                                </a>
                                <a href="{{ route('pengajuan-saya') }}" class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-medium text-slate-700 transition hover:bg-cyan-50">
                                    <svg class="h-4 w-4 text-cyan-600" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 4.5h10A1.5 1.5 0 0 1 16.5 6v8A1.5 1.5 0 0 1 15 15.5H5A1.5 1.5 0 0 1 3.5 14V6A1.5 1.5 0 0 1 5 4.5Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 8h6M7 11h4" />
                                    </svg>
                                    Pengajuan Saya
                                </a>
                                <a href="#" aria-disabled="true" class="flex cursor-not-allowed items-center gap-3 rounded-2xl px-4 py-3 text-sm font-medium text-slate-400 opacity-70 transition">
                                    <svg class="h-4 w-4 text-cyan-600" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.5 16V5.5h5V4h3v1.5h5V16" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 8h6M7 11h6M7 14h3" />
                                    </svg>
                                    Personal File
                                </a>
                                <a href="#" aria-disabled="true" class="flex cursor-not-allowed items-center gap-3 rounded-2xl px-4 py-3 text-sm font-medium text-slate-400 opacity-70 transition">
                                    <svg class="h-4 w-4 text-rose-500" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6.5A2.5 2.5 0 0 1 6.5 4h7A2.5 2.5 0 0 1 16 6.5v7A2.5 2.5 0 0 1 13.5 16h-7A2.5 2.5 0 0 1 4 13.5z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 7.5 10 10l4-2.5" />
                                    </svg>
                                    Profil
                                </a>
                                <a href="#" aria-disabled="true" class="flex cursor-not-allowed items-center gap-3 rounded-2xl px-4 py-3 text-sm font-medium text-slate-400 opacity-70 transition">
                                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 5h8M6 9h8M6 13h8" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 5.5h12M4 9.5h12M4 13.5h12" />
                                    </svg>
                                    Laporan Presensi
                                </a>
                                <a href="{{ route('laporan-kinerja') }}" class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-medium text-slate-700 transition hover:bg-cyan-50 hover:text-slate-900">
                                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 10h10M5 14h6M5 6h10" />
                                    </svg>
                                    Laporan Kinerja
                                </a>
                                <a href="#" aria-disabled="true" class="flex cursor-not-allowed items-center gap-3 rounded-2xl px-4 py-3 text-sm font-medium text-slate-400 opacity-70 transition">
                                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 4v12M4 10h12" />
                                        <circle cx="10" cy="10" r="6.5" />
                                    </svg>
                                    SILATAR Android
                                </a>
                            </div>
                            <div class="border-t border-slate-100 bg-gradient-to-r from-slate-700 to-amber-500 p-2">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="flex w-full items-center gap-3 rounded-2xl px-4 py-3 text-sm font-semibold text-white transition hover:bg-white/10">
                                        <svg class="h-4 w-4 text-white" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 4h3.5A1.5 1.5 0 0 1 16 5.5v9A1.5 1.5 0 0 1 14.5 16H11" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7l3 3-3 3" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 10H4" />
                                        </svg>
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-cyan-200 hover:text-slate-900">
                        <svg class="h-4 w-4 text-cyan-600" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 4h3.5A1.5 1.5 0 0 1 16 5.5v9A1.5 1.5 0 0 1 14.5 16H11" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7l3 3-3 3" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 10H4" />
                        </svg>
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="inline-flex items-center gap-2 rounded-full bg-cyan-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-cyan-700">
                        <svg class="h-4 w-4 text-white" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 4v12M4 10h12" />
                            <circle cx="10" cy="10" r="6.5" />
                        </svg>
                        Daftar
                    </a>
                @endauth
            </div>

            <button
                type="button"
                class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-700 shadow-sm transition hover:border-cyan-200 hover:text-slate-900 lg:hidden"
                @click="toggle()"
                :aria-expanded="open.toString()"
                aria-label="Buka menu"
            >
                <svg x-show="!open" x-cloak class="h-5 w-5" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 6h14M3 10h14M3 14h14" />
                </svg>
                <svg x-show="open" x-cloak class="h-5 w-5" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 5l10 10M15 5L5 15" />
                </svg>
            </button>
        </div>

        <div x-show="open" x-cloak class="mt-4 rounded-[1.5rem] border border-slate-200 bg-slate-50 p-4 lg:hidden">
            <div class="grid gap-3 text-sm font-medium text-slate-600">
                <a href="{{ url('/#home') }}" class="inline-flex items-center gap-2 rounded-2xl bg-white px-4 py-3 transition hover:text-slate-900">Home</a>
                <a href="{{ route('satuan-kerja') }}" class="inline-flex items-center gap-2 rounded-2xl bg-white px-4 py-3 transition hover:text-slate-900">Satuan Kerja</a>
                <a href="{{ url('/#kontak-kami') }}" class="inline-flex items-center gap-2 rounded-2xl bg-white px-4 py-3 transition hover:text-slate-900">Kontak Kami</a>
                <a href="{{ route('pelayanan') }}" class="inline-flex items-center gap-2 rounded-2xl bg-white px-4 py-3 transition hover:text-slate-900">Pelayanan</a>
                <a href="{{ route('pengajuan-saya') }}" class="inline-flex items-center gap-2 rounded-2xl bg-white px-4 py-3 transition hover:text-slate-900">Pengajuan Saya</a>
                <a href="{{ route('laporan-kinerja') }}" class="inline-flex items-center gap-2 rounded-2xl bg-white px-4 py-3 transition hover:text-slate-900">Laporan Kinerja</a>
                @auth
                    <div class="rounded-3xl border border-slate-200 bg-white px-4 py-4 shadow-sm">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Sedang masuk</p>
                        <p class="mt-2 text-sm font-semibold text-slate-950">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-slate-500">{{ auth()->user()->nomor_induk }}</p>
                        <form class="mt-4" method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full rounded-full bg-slate-900 px-4 py-3 text-center font-semibold text-white">Logout</button>
                        </form>
                    </div>
                @else
                    <div class="grid grid-cols-2 gap-3 pt-1">
                        <a href="{{ route('login') }}" class="rounded-full border border-slate-200 bg-white px-4 py-3 text-center font-semibold text-slate-700">Login</a>
                        <a href="{{ route('register') }}" class="rounded-full bg-cyan-600 px-4 py-3 text-center font-semibold text-white">Daftar</a>
                    </div>
                @endauth
            </div>
        </div>
    </nav>
</header>
