<x-layouts.app title="Login - SILATAR">
    <main class="relative flex min-h-screen items-center justify-center overflow-hidden px-4 py-12">
        <!-- Animated Background Grid -->
        <div class="absolute inset-0 z-0">
            <div class="absolute inset-0 bg-[linear-gradient(rgba(0,212,255,0.03)_1px,transparent_1px),linear-gradient(90deg,rgba(0,212,255,0.03)_1px,transparent_1px)] bg-[size:60px_60px] [mask-image:radial-gradient(ellipse_80%_80%_at_50%_50%,black_40%,transparent_100%)]"></div>
            <!-- Floating orbs -->
            <div class="absolute left-1/4 top-1/4 h-96 w-96 animate-pulse rounded-full bg-cyan-500/10 blur-3xl"></div>
            <div class="absolute right-1/4 bottom-1/4 h-80 w-80 animate-pulse rounded-full bg-violet-500/10 blur-3xl" style="animation-delay: 1s;"></div>
            <div class="absolute left-1/2 top-1/2 h-64 w-64 -translate-x-1/2 -translate-y-1/2 animate-pulse rounded-full bg-amber-500/5 blur-3xl" style="animation-delay: 2s;"></div>
        </div>

        <!-- Login Card -->
        <div class="relative z-10 w-full max-w-md">
            <!-- Glow effect behind card -->
            <div class="absolute -inset-1 rounded-3xl bg-gradient-to-r from-cyan-500/20 via-violet-500/20 to-cyan-500/20 blur-xl"></div>

<div class="relative rounded-3xl border border-cyan-500/30 bg-slate-950/90 p-8 shadow-[0_0_60px_rgba(0,212,255,0.15)] backdrop-blur-xl">
                <!-- Header -->
                <div class="text-center mb-8">
                    <!-- Logo with glow -->
                    <div class="relative mx-auto mb-6 w-20 h-20">
                        <div class="absolute inset-0 rounded-2xl bg-cyan-500/20 blur-xl animate-pulse"></div>
                        <div class="relative flex h-20 w-20 items-center justify-center rounded-2xl border-2 border-cyan-500/50 bg-gradient-to-br from-slate-900 to-slate-950 shadow-[0_0_30px_rgba(0,212,255,0.3)]">
                            <img src="{{ asset('favicon.webp') }}" alt="SILATAR" class="h-12 w-12 object-contain">
                        </div>
                    </div>

                    <div class="flex items-center justify-center gap-2 mb-2">
                        <div class="h-px w-8 bg-gradient-to-r from-transparent to-cyan-500/50"></div>
                        <span class="font-mono text-xs font-semibold uppercase tracking-[0.3em] text-cyan-400/70">Portal Layanan</span>
                        <div class="h-px w-8 bg-gradient-to-l from-transparent to-cyan-500/50"></div>
                    </div>

                    <h1 class="font-mono text-3xl font-black uppercase tracking-wider text-white">
                        <span class="bg-gradient-to-r from-cyan-400 to-cyan-300 bg-clip-text text-transparent">SILATAR</span>
                    </h1>
                    <p class="mt-2 font-mono text-sm text-slate-500">Kementerian Agama Tanah Datar</p>
                </div>

                <!-- Error Alert -->
                @if ($errors->any())
                    <div class="mb-6 flex items-center gap-3 rounded-xl border border-rose-500/30 bg-rose-500/10 p-4">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-rose-500/20">
                            <svg class="h-5 w-5 text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-mono text-sm font-semibold text-rose-400">Login Gagal</p>
                            <p class="font-mono text-xs text-rose-300/80">{{ $errors->first() }}</p>
                        </div>
                    </div>
                @endif

                <!-- Login Form -->
                <form class="space-y-5" method="POST" action="{{ route('login.submit') }}">
                    @csrf

                    <!-- Email/NIP Field -->
                    <div class="group">
                        <label class="mb-2 flex items-center gap-2 font-mono text-xs font-semibold uppercase tracking-wider text-cyan-400/70" for="login">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Email / NIP
                        </label>
                        <div class="relative">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                                <svg class="h-5 w-5 text-cyan-500/50" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <input
                                id="login"
                                name="login"
                                type="text"
                                value="{{ old('login') }}"
                                class="w-full rounded-xl border border-cyan-500/30 bg-slate-900/80 py-4 pl-12 pr-4 font-mono text-sm text-white shadow-[inset_0_2px_4px_rgba(0,0,0,0.3)] transition placeholder:text-slate-600 focus:border-cyan-400 focus:shadow-[0_0_20px_rgba(0,212,255,0.2),inset_0_2px_4px_rgba(0,0,0,0.3)] focus:outline-none"
                                placeholder="nama@email.com atau 1978xxxx"
                                autocomplete="username"
                                required
                            >
<div class="absolute inset-y-0 right-0 flex items-center pr-4">
                                <div class="h-2 w-2 rounded-full bg-emerald-500/50 shadow-[0_0_10px_rgba(16,185,129,0.5)]"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Password Field -->
                    <div class="group">
                        <label class="mb-2 flex items-center gap-2 font-mono text-xs font-semibold uppercase tracking-wider text-cyan-400/70" for="password">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            Password
                        </label>
                        <div class="relative">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                                <svg class="h-5 w-5 text-cyan-500/50" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M157a2 2 0 0122m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                                </svg>
                            </div>
                            <input
                                id="password"
                                name="password"
                                type="password"
                                class="w-full rounded-xl border border-cyan-500/30 bg-slate-900/80 py-4 pl-12 pr-12 font-mono text-sm text-white shadow-[inset_0_2px_4px_rgba(0,0,0,0.3)] transition placeholder:text-slate-600 focus:border-cyan-400 focus:shadow-[0_0_20px_rgba(0,212,255,0.2),inset_0_2px_4px_rgba(0,0,0,0.3)] focus:outline-none"
                                placeholder="••••••••"
                                autocomplete="current-password"
                                required
                            >
                            <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 flex items-center pr-4 text-slate-500 transition hover:text-cyan-400">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Remember& Forgot -->
                    <div class="flex items-center justify-between">
                        <label class="flex cursor-pointer items-center gap-2 font-mono text-sm text-slate-400 transition hover:text-slate-300">
                            <input name="remember" value="1" type="checkbox" class="h-4 w-4 rounded border-cyan-500/30 bg-slate-900 text-cyan-500 shadow-sm transition focus:ring-cyan-400/50 focus:ring-offset-0">
                            <span class="text-xs uppercase tracking-wider">Ingat saya</span>
                        </label>
                        <button type="button" onclick="openForgotModal()" class="group flex items-center gap-1 font-mono text-xs font-semibold uppercase tracking-wider text-amber-400/70 transition hover:text-amber-400">
                            <svg class="h-3 w-3 transition group-hover:rotate-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Lupa password?
                        </button>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="group relative w-full overflow-hidden rounded-xl bg-gradient-to-r from-cyan-600 via-cyan-500 to-cyan-600 bg-[length:200%_100%] py-4 font-mono text-sm font-bold uppercase tracking-wider text-white shadow-[0_0_30px_rgba(0,212,255,0.4)] transition-all hover:shadow-[0_0_40px_rgba(0,212,255,0.6)] hover:bg-[position:100%_0] active:scale-[0.98]">
                        <span class="flex items-center justify-center gap-2">
                            <svg class="h-5 w-5 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 4h4.5A1.5 1.5 0 0016 5.5v13A1.5 1.5 0 0014.5 20H11M7 10l5 5-5 5M11 15H4"/>
                            </svg>
                            Masuk ke Sistem
                        </span>
                        <!-- Shine effect -->
                        <div class="absolute inset-0 -translate-x-full skew-x-12 bg-gradient-to-r from-transparent via-white/10 to-transparent transition-transform duration-1000 group-hover:translate-x-full"></div>
                    </button>
                </form>

                <!-- Divider -->
                <div class="my-6 flex items-center gap-4">
                    <div class="h-px flex-1 bg-gradient-to-r from-transparent via-cyan-500/30 to-transparent"></div>
                    <span class="font-mono text-xs text-slate-600 uppercase tracking-widest">atau</span>
                    <div class="h-px flex-1 bg-gradient-to-r from-transparent via-cyan-500/30 to-transparent"></div>
                </div>

                <!-- Back to Home -->
                <a href="{{ url('/') }}" class="group flex w-full items-center justify-center gap-2 rounded-xl border border-cyan-500/30 bg-slate-900/50 py-3 font-mono text-sm font-medium text-cyan-400/70 transition-all hover:border-cyan-400/50 hover:bg-cyan-500/10 hover:text-cyan-300">
                    <svg class="h-4 w-4 transition-transform group-hover:-translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali ke Beranda
                </a>
            </div>

            <!-- Footer -->
            <div class="mt-6 text-center">
                <p class="font-mono text-xs text-slate-600">
                    <span class="text-cyan-500/50">&copy;</span>2026 SILATAR - Kemenag Tanah Datar. All rights reserved.
</p>
            </div>
        </div>

        <!-- Forgot Password Modal -->
        <div id="forgotModal" class="fixed inset-0 z-50 hidden" x-data="{ showResult: false, showError: false }">
            <div class="absolute inset-0 bg-slate-950/95 backdrop-blur-xl" onclick="closeForgotModal()"></div>
            <div class="absolute inset-0 flex items-center justify-center p-4">
                <div class="relative w-full max-w-md">
                    <div class="absolute -inset-0.5 bg-gradient-to-r from-amber-500 via-yellow-500 to-amber-500 rounded-2xl blur opacity-30 animate-pulse"></div>
                    <div class="relative rounded-2xl border border-amber-500/50 bg-slate-900/95 p-8 shadow-[0_0_60px_rgba(245,158,11,0.3)]">
                        <button type="button" onclick="closeForgotModal()" class="absolute right-4 top-4 z-10 flex h-8 w-8 items-center justify-center rounded-full border border-rose-500/30 bg-rose-500/10 text-rose-400 transition-all hover:bg-rose-500/20 hover:border-rose-500/50">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>

                        <div class="text-center mb-6">
                            <div class="relative mx-auto w-20 h-20 mb-4">
                                <div class="absolute inset-0 bg-amber-500/20 rounded-full blur-xl animate-ping"></div>
                                <div class="relative flex h-20 w-20 items-center justify-center rounded-full border-2 border-amber-500/50 bg-gradient-to-br from-amber-500/20 to-orange-500/20 shadow-[0_0_30px_rgba(245,158,11,0.4)]">
                                    <svg class="w-10 h-10 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                            </div>
                            <h2 class="font-mono text-2xl font-black uppercase tracking-wider text-white">
                                <span class="text-amber-400">LUPA</span> PASSWORD?
                            </h2>
                            <div class="mt-2 flex items-center justify-center gap-2">
                                <div class="h-px w-8 bg-gradient-to-r from-transparent to-amber-500/50"></div>
                                <p class="font-mono text-xs text-amber-400/70 uppercase tracking-widest">Reset via WhatsApp</p>
                                <div class="h-px w-8 bg-gradient-to-l from-transparent to-amber-500/50"></div>
                            </div>
                        </div>

                        <form id="forgotForm" onsubmit="submitForgotPassword(event)" class="space-y-5">
                            @csrf
                            <div>
                                <label class="mb-2 flex items-center gap-2 font-mono text-sm font-medium text-amber-400/70">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/></svg>
                                    Nomor Induk Kepegawaian (NIP)
                                </label>
                                <input
                                    id="nip"
                                    name="nip"
                                    type="text"
                                    class="w-full rounded-xl border border-amber-500/30 bg-slate-900/80 px-4 py-3.5 font-mono text-lg text-white shadow-[inset_0_2px_4px_rgba(0,0,0,0.3)] transition placeholder:text-slate-500 focus:border-amber-400 focus:ring-2 focus:ring-amber-400/30 focus:shadow-[0_0_20px_rgba(245,158,11,0.3)] focus:outline-none"
                                    placeholder="1978xxxx"
                                    required
                                >
                            </div>
                            <button type="submit" id="forgotSubmitBtn" class="group relative w-full overflow-hidden rounded-xl bg-gradient-to-r from-amber-600 via-amber-500 to-amber-600 bg-[length:200%_100%] px-6 py-3.5 font-mono text-sm font-bold uppercase tracking-wider text-slate-900 shadow-[0_0_30px_rgba(245,158,11,0.5)] transition-all hover:shadow-[0_0_40px_rgba(245,158,11,0.7)] hover:bg-[position:100%_0]">
                                <span id="forgotBtnText" class="flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                    Kirim Password Baru
                                </span>
                                <svg id="forgotBtnSpinner" class="hidden w-5 h-5 animate-spin mx-auto" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                            </button>
                        </form>

                        <div id="forgotResult" class="hidden mt-6">
                            <div class="relative rounded-xl border border-emerald-500/50 bg-gradient-to-br from-emerald-500/10 to-emerald-900/20 p-6 text-center overflow-hidden">
                                <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/5 to-transparent"></div>
                                <div class="absolute inset-0 border border-emerald-500/20 rounded-xl"></div>
                                <div class="relative mb-4">
                                    <div class="absolute inset-0 bg-emerald-500/20 rounded-full blur-xl animate-ping"></div>
                                    <div class="relative mx-auto flex h-16 w-16 items-center justify-center rounded-full border-2 border-emerald-500/50 bg-gradient-to-br from-emerald-500/30 to-emerald-600/30 shadow-[0_0_30px_rgba(16,185,129,0.5)]">
                                        <svg class="h-8 w-8 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    </div>
                                </div>
                                <h3 class="relative font-mono text-lg font-bold uppercase tracking-wider text-emerald-400">PASSWORD TERKIRIM!</h3>
                                <p class="relative mt-2 font-mono text-sm text-emerald-300/80 leading-relaxed" id="forgotSuccessMessage"></p>
                                <div class="relative mt-4 flex items-center justify-center gap-2 text-xs text-slate-400">
                                    <svg class="w-4 h-4 text-emerald-400/50" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span>Cek WhatsApp Anda sekarang</span>
                                </div>
                            </div>
                            <button type="button" onclick="closeForgotModal()" class="mt-4 w-full flex items-center justify-center gap-2 rounded-xl border border-slate-600/50 bg-slate-800/50 px-6 py-3 font-mono text-sm text-slate-300 transition hover:bg-slate-700/50 hover:border-slate-500">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                Tutup
                            </button>
                        </div>

                        <div id="forgotError" class="hidden mt-6">
                            <div class="relative rounded-xl border border-rose-500/50 bg-gradient-to-br from-rose-500/10 to-rose-900/20 p-6 text-center overflow-hidden">
                                <div class="absolute inset-0 bg-gradient-to-br from-rose-500/5 to-transparent"></div>
                                <div class="absolute inset-0 border border-rose-500/20 rounded-xl"></div>
                                <div class="relative mb-4">
                                    <div class="absolute inset-0 bg-rose-500/20 rounded-full blur-xl animate-ping"></div>
                                    <div class="relative mx-auto flex h-16 w-16 items-center justify-center rounded-full border-2 border-rose-500/50 bg-gradient-to-br from-rose-500/30 to-rose-600/30 shadow-[0_0_30px_rgba(244,63,94,0.5)]">
                                        <svg class="h-8 w-8 text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                </div>
                                <h3 class="relative font-mono text-lg font-bold uppercase tracking-wider text-rose-400">GAGAL!</h3>
                                <p class="relative mt-2 font-mono text-sm text-rose-300/80" id="forgotErrorMessage"></p>
                            </div>
                            <button type="button" onclick="resetForgotForm()" class="mt-4 w-full flex items-center justify-center gap-2 rounded-xl border border-amber-500/30 bg-amber-500/10 px-6 py-3 font-mono text-sm font-semibold text-amber-400 transition hover:bg-amber-500/20 hover:border-amber-400/50">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                Coba Lagi
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.classList.toggle('text-cyan-400');
        });

        function openForgotModal() {
            document.getElementById('forgotModal').classList.remove('hidden');
            document.getElementById('nip').focus();
        }

        function closeForgotModal() {
            document.getElementById('forgotModal').classList.add('hidden');
            resetForgotForm();
        }

        function resetForgotForm() {
            document.getElementById('forgotForm').reset();
            document.getElementById('forgotForm').classList.remove('hidden');
            document.getElementById('forgotResult').classList.add('hidden');
            document.getElementById('forgotError').classList.add('hidden');
            document.getElementById('forgotSubmitBtn').disabled = false;
            document.getElementById('forgotBtnText').innerHTML = '<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg> Kirim Password Baru';
            document.getElementById('forgotBtnSpinner').classList.add('hidden');
        }

        async function submitForgotPassword(e) {
            e.preventDefault();

            const btn = document.getElementById('forgotSubmitBtn');
            const btnText = document.getElementById('forgotBtnText');
            const spinner = document.getElementById('forgotBtnSpinner');
            const resultDiv = document.getElementById('forgotResult');
            const errorDiv = document.getElementById('forgotError');

            resultDiv.classList.add('hidden');
            errorDiv.classList.add('hidden');
            btn.disabled = true;
            btnText.innerHTML = '<svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg> Mengirim...';
            spinner.classList.remove('hidden');

            const formData = new FormData(document.getElementById('forgotForm'));

            try {
                const response = await fetch('{{ route('forgot-password') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': formData.get('_token'),
                        'Accept': 'application/json',
                    },
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    document.getElementById('forgotSuccessMessage').innerHTML = data.message;
                    document.getElementById('forgotForm').classList.add('hidden');
                    resultDiv.classList.remove('hidden');
                } else {
                    document.getElementById('forgotErrorMessage').textContent = data.message;
                    errorDiv.classList.remove('hidden');
                }
            } catch (err) {
                document.getElementById('forgotErrorMessage').textContent = 'Terjadi kesalahan. Silakan coba lagi.';
                errorDiv.classList.remove('hidden');
            } finally {
                btn.disabled = false;
                btnText.innerHTML = '<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg> Kirim Password Baru';
                spinner.classList.add('hidden');
            }
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeForgotModal();
        });
    </script>
</x-layouts.app>
