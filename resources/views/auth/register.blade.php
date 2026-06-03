<x-layouts.app title="Daftar - SILATAR">
    <main class="mx-auto flex min-h-[calc(100vh-4rem)] items-center px-6 py-10 lg:px-8">
        <div class="grid w-full gap-8 lg:grid-cols-2">
            <!-- Info Panel -->
            <aside class="rounded-2xl border border-cyan-500/20 bg-gradient-to-br from-slate-900/90 to-slate-950 p-8 shadow-[0_0_40px_rgba(0,212,255,0.1)] lg:p-10">
                <span class="inline-flex items-center gap-2 rounded-full border border-cyan-500/30 bg-cyan-500/10 px-3 py-1 font-mono text-xs font-semibold uppercase tracking-widest text-cyan-400">
                    <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4h4.5A1.5 1.5 0 0016 5.5v13A1.5 1.5 0 0014.5 20H11M7 10l5 5-5 5M11 15H4"/></svg>
                    Daftar Akun
                </span>
                <h1 class="mt-4 font-mono text-3xl font-bold uppercase tracking-wider text-white">Buat Akun Baru</h1>
                <p class="mt-3 max-w-xl text-sm leading-7 text-slate-400">
                    Daftarkan akun untuk mulai memakai layanan digital, melihat riwayat, dan menerima pembaruan penting.
                </p>

                <div class="mt-8 grid gap-4 sm:grid-cols-2">
                    <div class="rounded-xl border border-cyan-500/20 bg-cyan-500/5 p-5 transition hover:border-cyan-400/30 hover:bg-cyan-500/10">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg border border-cyan-500/30 bg-cyan-500/10">
                                <svg class="h-4 w-4 text-cyan-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            </div>
                            <p class="font-mono text-sm font-semibold text-white">Cepat</p>
                        </div>
                        <p class="font-mono text-xs text-slate-400">Form dibuat ringkas agar mudah diisi.</p>
                    </div>
                    <div class="rounded-xl border border-cyan-500/20 bg-cyan-500/5 p-5 transition hover:border-cyan-400/30 hover:bg-cyan-500/10">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg border border-cyan-500/30 bg-cyan-500/10">
                                <svg class="h-4 w-4 text-cyan-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            </div>
                            <p class="font-mono text-sm font-semibold text-white">Aman</p>
                        </div>
                        <p class="font-mono text-xs text-slate-400">Siap dihubungkan ke autentikasi Laravel.</p>
                    </div>
                </div>
            </aside>

            <!-- Registration Form -->
            <section class="rounded-2xl border border-cyan-500/20 bg-slate-900/80 p-8 shadow-[0_0_40px_rgba(0,212,255,0.1)] backdrop-blur-xl lg:p-10">
                <span class="inline-flex items-center gap-2 rounded-full border border-cyan-500/30 bg-cyan-500/10 px-3 py-1 font-mono text-xs font-semibold uppercase tracking-widest text-cyan-400">
                    <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    Registrasi
                </span>
                <h2 class="mt-4 font-mono text-2xl font-bold uppercase tracking-wider text-white">Lengkapi Data Akun</h2>
                <p class="mt-3 text-sm leading-7 text-slate-400">
                    Halaman ini sudah tersedia sebagai pintu masuk, dan nanti bisa langsung kita sambungkan ke proses registrasi.
                </p>

                <form class="mt-8 space-y-5">
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="mb-2 block font-mono text-sm font-medium text-cyan-400/70" for="name">Nama</label>
                            <input id="name" type="text" class="w-full rounded-xl border border-cyan-500/30 bg-slate-900/80 px-4 py-3 font-mono text-sm text-white shadow-sm transition placeholder:text-slate-500 focus:border-cyan-400 focus:ring-cyan-400/50" placeholder="Nama lengkap">
                        </div>
                        <div>
                            <label class="mb-2 block font-mono text-sm font-medium text-cyan-400/70" for="phone">Telepon</label>
                            <input id="phone" type="text" class="w-full rounded-xl border border-cyan-500/30 bg-slate-900/80 px-4 py-3 font-mono text-sm text-white shadow-sm transition placeholder:text-slate-500 focus:border-cyan-400 focus:ring-cyan-400/50" placeholder="08xxxxxxxxxx">
                        </div>
                    </div>
                    <div>
                        <label class="mb-2 block font-mono text-sm font-medium text-cyan-400/70" for="reg-email">Email</label>
                        <input id="reg-email" type="email" class="w-full rounded-xl border border-cyan-500/30 bg-slate-900/80 px-4 py-3 font-mono text-sm text-white shadow-sm transition placeholder:text-slate-500 focus:border-cyan-400 focus:ring-cyan-400/50" placeholder="nama@contoh.com">
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="mb-2 block font-mono text-sm font-medium text-cyan-400/70" for="reg-password">Password</label>
                            <input id="reg-password" type="password" class="w-full rounded-xl border border-cyan-500/30 bg-slate-900/80 px-4 py-3 font-mono text-sm text-white shadow-sm transition placeholder:text-slate-500 focus:border-cyan-400 focus:ring-cyan-400/50" placeholder="........">
                        </div>
                        <div>
                            <label class="mb-2 block font-mono text-sm font-medium text-cyan-400/70" for="confirm-password">Konfirmasi</label>
                            <input id="confirm-password" type="password" class="w-full rounded-xl border border-cyan-500/30 bg-slate-900/80 px-4 py-3 font-mono text-sm text-white shadow-sm transition placeholder:text-slate-500 focus:border-cyan-400 focus:ring-cyan-400/50" placeholder="........">
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-3 pt-2">
                        <button type="button" class="group relative inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-cyan-600 to-cyan-500 px-6 py-3 font-mono text-sm font-semibold uppercase tracking-wider text-white shadow-[0_0_20px_rgba(0,212,255,0.4)] transition hover:shadow-[0_0_30px_rgba(0,212,255,0.6)]">
                            <span>Daftar</span>
                            <svg class="h-4 w-4 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </button>
                        <a href="{{ route('login') }}" class="inline-flex items-center gap-2 rounded-full border border-cyan-500/30 bg-slate-900/80 px-6 py-3 font-mono text-sm font-semibold text-cyan-400 transition hover:border-cyan-400/50 hover:bg-cyan-500/10">
                            Sudah punya akun
                        </a>
                    </div>
                </form>
            </section>
        </div>
    </main>
</x-layouts.app>