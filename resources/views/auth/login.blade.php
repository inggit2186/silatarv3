<x-layouts.app title="Login - SILATAR">
    <main class="mx-auto flex min-h-[calc(100vh-4rem)] items-center px-6 py-10 lg:px-8">
        <div class="grid w-full gap-8 lg:grid-cols-2">
            <!-- Login Form -->
            <section class="rounded-2xl border border-cyan-500/20 bg-slate-900/80 p-8 shadow-[0_0_40px_rgba(0,212,255,0.1)] backdrop-blur-xl lg:p-10">
                <span class="inline-flex items-center gap-2 rounded-full border border-cyan-500/30 bg-cyan-500/10 px-3 py-1 font-mono text-xs font-semibold uppercase tracking-widest text-cyan-400">
                    <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4h4.5A1.5 1.5 0 0016 5.5v13A1.5 1.5 0 0014.5 20H11M7 10l5 5-5 5M11 15H4"/></svg>
                    Login
                </span>
                <h1 class="mt-4 font-mono text-3xl font-bold uppercase tracking-wider text-white">Selamat Datang</h1>
                <p class="mt-3 max-w-xl text-sm leading-7 text-slate-400">
                    Masuk untuk melanjutkan ke dashboard layanan, melihat status pengajuan, dan mengelola data akun.
                </p>

                @if ($errors->any())
                    <div class="mt-6 rounded-xl border border-rose-500/30 bg-rose-500/10 px-5 py-4">
                        <p class="font-mono text-sm font-semibold text-rose-400">Login gagal</p>
                        <ul class="mt-2 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li class="font-mono text-xs text-rose-300">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form class="mt-8 space-y-5" method="POST" action="{{ route('login.submit') }}">
                    @csrf
                    <div>
                        <label class="mb-2 block font-mono text-sm font-medium text-cyan-400/70" for="login">Email / Nomor Induk</label>
                        <input
                            id="login"
                            name="login"
                            type="text"
                            value="{{ old('login') }}"
                            class="w-full rounded-xl border border-cyan-500/30 bg-slate-900/80 px-4 py-3 font-mono text-sm text-white shadow-sm transition placeholder:text-slate-500 focus:border-cyan-400 focus:ring-cyan-400/50"
                            placeholder="nama@contoh.com atau 1978xxxx"
                            autocomplete="username"
                            required
                        >
                    </div>
                    <div>
                        <label class="mb-2 block font-mono text-sm font-medium text-cyan-400/70" for="password">Password</label>
                        <input
                            id="password"
                            name="password"
                            type="password"
                            class="w-full rounded-xl border border-cyan-500/30 bg-slate-900/80 px-4 py-3 font-mono text-sm text-white shadow-sm transition placeholder:text-slate-500 focus:border-cyan-400 focus:ring-cyan-400/50"
                            placeholder="........"
                            autocomplete="current-password"
                            required
                        >
                    </div>
                    <div class="flex items-center justify-between gap-3">
                        <label class="flex items-center gap-2 font-mono text-sm text-slate-400">
                            <input name="remember" value="1" type="checkbox" class="h-4 w-4 rounded border-cyan-500/30 bg-slate-900 text-cyan-500 focus:ring-cyan-400/50">
                            Ingat saya
                        </label>
                        <a href="{{ route('register') }}" class="font-mono text-sm font-medium text-cyan-400 transition hover:text-cyan-300">Buat akun baru</a>
                    </div>
                    <div class="flex flex-wrap gap-3 pt-2">
                        <button type="submit" class="group relative inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-cyan-600 to-cyan-500 px-6 py-3 font-mono text-sm font-semibold uppercase tracking-wider text-white shadow-[0_0_20px_rgba(0,212,255,0.4)] transition hover:shadow-[0_0_30px_rgba(0,212,255,0.6)]">
                            <span>Masuk</span>
                            <svg class="h-4 w-4 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </button>
                        <a href="{{ url('/') }}" class="inline-flex items-center gap-2 rounded-full border border-cyan-500/30 bg-slate-900/80 px-6 py-3 font-mono text-sm font-semibold text-cyan-400 transition hover:border-cyan-400/50 hover:bg-cyan-500/10">
                            Kembali
                        </a>
                    </div>
                </form>
            </section>

            <!-- Info Panel -->
            <aside class="rounded-2xl border border-cyan-500/20 bg-gradient-to-br from-slate-900/90 to-slate-950 p-8 shadow-[0_0_40px_rgba(0,212,255,0.1)] lg:p-10">
                <span class="inline-flex items-center gap-2 rounded-full border border-cyan-500/30 bg-cyan-500/10 px-3 py-1 font-mono text-xs font-semibold uppercase tracking-widest text-cyan-400">
                    <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    Fitur
                </span>
                <h2 class="mt-4 font-mono text-2xl font-bold uppercase tracking-wider text-white">Layanan Digital Dalam Satu Portal</h2>
                <p class="mt-4 text-sm leading-7 text-slate-400">
                    Gunakan akun untuk mengakses informasi yang lebih personal, memantau layanan, dan membaca pembaruan terbaru.
                </p>

                <div class="mt-8 space-y-4">
                    <div class="rounded-xl border border-cyan-500/20 bg-cyan-500/5 p-5 transition hover:border-cyan-400/30 hover:bg-cyan-500/10">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg border border-cyan-500/30 bg-cyan-500/10">
                                <svg class="h-4 w-4 text-cyan-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <p class="font-mono text-sm font-semibold text-white">Informasi Terpusat</p>
                        </div>
                        <p class="font-mono text-xs text-slate-400">Semua info layanan dikemas rapi dan mudah dicari.</p>
                    </div>
                    <div class="rounded-xl border border-cyan-500/20 bg-cyan-500/5 p-5 transition hover:border-cyan-400/30 hover:bg-cyan-500/10">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg border border-cyan-500/30 bg-cyan-500/10">
                                <svg class="h-4 w-4 text-cyan-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            </div>
                            <p class="font-mono text-sm font-semibold text-white">Tracking Real-time</p>
                        </div>
                        <p class="font-mono text-xs text-slate-400">Pantau status pengajuan dan layanan secara langsung.</p>
                    </div>
                    <div class="rounded-xl border border-cyan-500/20 bg-cyan-500/5 p-5 transition hover:border-cyan-400/30 hover:bg-cyan-500/10">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg border border-cyan-500/30 bg-cyan-500/10">
                                <svg class="h-4 w-4 text-cyan-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            </div>
                            <p class="font-mono text-sm font-semibold text-white">Keamanan Data</p>
                        </div>
                        <p class="font-mono text-xs text-slate-400">Data pribadi dilindungi dengan enkripsi standar.</p>
                    </div>
                </div>
            </aside>
        </div>
    </main>
</x-layouts.app>