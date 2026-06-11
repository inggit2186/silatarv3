<x-layouts.app title="Whistleblowing - SILATAR">

    <!-- Cyberpunk Success Notification -->
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="
            setTimeout(() => { show = false }, 4000);
        " x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-8 -translate-y-4" x-transition:enter-end="opacity-100 translate-x-0 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-x-0 translate-y-0" x-transition:leave-end="opacity-0 translate-x-8 -translate-y-4" class="fixed top-20 right-4 z-50 w-80">
            <div class="relative bg-[#0a0f1a] border-2 border-cyan-500 rounded-xl overflow-hidden shadow-[0_0_30px_rgba(0,212,255,0.3)]">
                <div class="absolute top-0 left-0 right-0 h-[2px] bg-gradient-to-r from-transparent via-cyan-400 to-transparent animate-pulse"></div>
                <div class="absolute top-0 right-0 w-16 h-16 bg-gradient-to-bl from-cyan-500/20 to-transparent"></div>
                <div class="absolute bottom-0 left-0 w-12 h-12 bg-gradient-to-tr from-purple-500/20 to-transparent"></div>

                <div class="relative p-4">
                    <div class="flex items-start gap-3">
                        <div class="relative flex-shrink-0">
                            <div class="absolute inset-0 bg-cyan-400 blur-lg opacity-50"></div>
                            <div class="relative w-12 h-12 bg-gradient-to-br from-cyan-500 to-blue-600 rounded-lg flex items-center justify-center shadow-[0_0_20px_rgba(0,212,255,0.5)]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-slate-900" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                        </div>

                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="px-2 py-0.5 bg-cyan-500/20 border border-cyan-500/30 rounded text-[10px] font-bold text-cyan-400 uppercase tracking-wider">SUCCESS</span>
                            </div>
                            <p class="text-white font-bold text-sm">Pengaduan Diajukan!</p>
                            <p class="text-slate-400 text-xs mt-1 leading-relaxed">{{ session('success') }}</p>
                        </div>

                        <button @click="show = false" class="flex-shrink-0 w-7 h-7 bg-slate-800/50 hover:bg-slate-700/50 border border-slate-600/30 rounded-lg flex items-center justify-center transition-all hover:border-cyan-500/50">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="absolute inset-0 pointer-events-none overflow-hidden">
                    <div class="absolute w-full h-[2px] bg-gradient-to-r from-transparent via-cyan-400/50 to-transparent" style="animation: scanDown 2s linear infinite;"></div>
                </div>
            </div>
        </div>
    @endif

    <main class="relative cyber-bg pt-20 lg:pt-24">
        <!-- Animated Grid Background -->
        <div class="fixed inset-0 overflow-hidden pointer-events-none">
            <div class="absolute inset-0 opacity-[0.03]"
                 style="background-image: linear-gradient(rgba(0, 212, 255, 0.3) 1px, transparent 1px),
                                        linear-gradient(90deg, rgba(0, 212, 255, 0.3) 1px, transparent 1px);
                        background-size: 60px 60px;">
            </div>
            <!-- Floating particles -->
            <div class="absolute top-1/4 left-1/4 w-2 h-2 bg-cyan-400 rounded-full animate-pulse opacity-40"></div>
            <div class="absolute top-3/4 right-1/4 w-3 h-3 bg-purple-500 rounded-full animate-pulse opacity-30" style="animation-delay: 1s;"></div>
            <div class="absolute top-1/2 right-1/3 w-2 h-2 bg-pink-500 rounded-full animate-pulse opacity-40" style="animation-delay: 2s;"></div>
        </div>

        <div class="relative mx-auto max-w-3xl px-6 py-8 lg:px-8">
            {{-- Cyber Header Banner --}}
            <div class="cyber-card mb-6 overflow-hidden">
                <!-- Neon glow line -->
                <div class="absolute top-0 left-0 right-0 h-[2px] bg-gradient-to-r from-transparent via-cyan-400 to-transparent animate-pulse"></div>

                <div class="flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between relative">
                    <div class="flex items-center gap-5">
                        <div class="relative">
                            <div class="absolute inset-0 bg-cyan-400 blur-xl opacity-30 rounded-2xl"></div>
                            <div class="relative w-16 h-16 bg-gradient-to-br from-cyan-500 to-blue-600 rounded-2xl flex items-center justify-center shadow-[0_0_30px_rgba(0,212,255,0.4)]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-9 h-9 text-slate-900" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3v2.25M3 21v-6m0 0l2.252.25M3 15V21m12-9V3m-12 0l2.25-2.25M21 15h-12m0 0V3m0 12v12M9 9l6 6M9 9l6-6" />
                                </svg>
                            </div>
                        </div>
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <span class="cyber-badge">WHISTLEBLOWING</span>
                            </div>
                            <h1 class="text-2xl font-extrabold text-white tracking-tight">
                                <span class="text-cyan-400">Sistem</span> Pengaduan
                            </h1>
                            <p class="text-sm text-slate-400 mt-1 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-cyan-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                                </svg>
                                Laporkan pelanggaran secara anonim & aman
                            </p>
                        </div>
                    </div>
                    <a href="{{ route('pelayanan') }}" class="cyber-btn-secondary-sm group flex-shrink-0 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali
                    </a>
                </div>
            </div>

            {{-- Whistleblowing Form Card --}}
            <div class="cyber-card relative overflow-hidden">
                <!-- Corner decorations -->
                <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-bl from-cyan-500/10 to-transparent rounded-bl-full"></div>
                <div class="absolute bottom-0 left-0 w-32 h-32 bg-gradient-to-tr from-purple-500/10 to-transparent rounded-tr-full"></div>

                <!-- Header with icon -->
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-cyan-500/20">
                    <div class="w-10 h-10 bg-cyan-500/10 border border-cyan-500/30 rounded-xl flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-white">Form Pengaduan</h2>
                        <p class="text-xs text-slate-500">Isi data di bawah untuk mengajukan pengaduan</p>
                    </div>
                </div>

                <form action="{{ route('whistleblowing.submit') }}" method="POST" class="space-y-6 relative">
                    @csrf

                    {{-- Info Box --}}
                    <div class="bg-gradient-to-r from-cyan-500/5 to-purple-500/5 border border-cyan-500/20 rounded-xl p-4">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 bg-cyan-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-cyan-400 mb-1">Pedoman Pelaporan</p>
                                <p class="text-xs text-slate-400 leading-relaxed">
                                    Pastikan laporan Anda berisi informasi yang akurat dan dapat dipertanggungjawabkan. Identitas pelapor dijamin kerahasiaannya.
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Judul Pengaduan --}}
                    <div class="cyber-form-group">
                        <label for="judul" class="cyber-form-label flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                            </svg>
                            Judul Pengaduan
                        </label>
                        <div class="relative">
                            <input
                                type="text"
                                name="judul"
                                id="judul"
                                class="cyber-form-input pl-12"
                                placeholder="Ringkasan singkat pengaduan Anda..."
                                required
                                maxlength="244"
                                value="{{ old('judul') }}"
                            >
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-cyan-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z" />
                                </svg>
                            </div>
                        </div>
                        @error('judul')
                            <p class="cyber-form-error flex items-center gap-1 mt-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="cyber-form-group">
                        <label for="email" class="cyber-form-label flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                            </svg>
                            Email
                        </label>
                        <div class="relative">
                            <input
                                type="email"
                                name="email"
                                id="email"
                                class="cyber-form-input pl-12"
                                placeholder="email@contoh.com"
                                required
                                value="{{ old('email', $user->email ?? '') }}"
                            >
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-cyan-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                                </svg>
                            </div>
                        </div>
                        @error('email')
                            <p class="cyber-form-error flex items-center gap-1 mt-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- No. Telepon --}}
                    <div class="cyber-form-group">
                        <label for="telp" class="cyber-form-label flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.467c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                            </svg>
                            No. Telepon (opsional)
                        </label>
                        <div class="relative">
                            <input
                                type="tel"
                                name="telp"
                                id="telp"
                                class="cyber-form-input pl-12"
                                placeholder="08xxxxxxxxxx"
                                value="{{ old('telp') }}"
                            >
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-cyan-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.467c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                                </svg>
                            </div>
                        </div>
                        @error('telp')
                            <p class="cyber-form-error flex items-center gap-1 mt-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Keterangan --}}
                    <div class="cyber-form-group">
                        <label for="keterangan" class="cyber-form-label flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                            </svg>
                            Uraian Pengaduan
                        </label>
                        <div class="relative">
                            <textarea
                                name="keterangan"
                                id="keterangan"
                                class="cyber-form-textarea pl-12"
                                rows="6"
                                placeholder="Jelaskan secara detail kronologi dan fakta yang Anda ketahui..."
                                required
                                maxlength="5000"
                            >{{ old('keterangan') }}</textarea>
                            <div class="absolute left-4 top-4 pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-cyan-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                </svg>
                            </div>
                        </div>
                        <p class="text-xs text-slate-500 mt-2">Maks. 5000 karakter</p>
                        @error('keterangan')
                            <p class="cyber-form-error flex items-center gap-1 mt-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Privacy Notice --}}
                    <div class="bg-slate-800/30 border border-slate-700/50 rounded-xl p-4">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 bg-purple-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-purple-400 mb-1">Jaminan Kerahasiaan</p>
                                <p class="text-xs text-slate-400 leading-relaxed">
                                    Identitas Anda sebagai pelapor akan dijaga kerahasiaannya. Informasi ini hanya digunakan untuk memproses pengaduan Anda.
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Submit Button --}}
                    <div class="flex justify-end gap-4 pt-4 border-t border-cyan-500/10">
                        <a href="{{ route('pelayanan') }}" class="cyber-btn-secondary flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Batal
                        </a>
                        <button type="submit" class="cyber-btn group flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 group-hover:rotate-12 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3 3l6 6 3-3-6-6-3 3zm0 6h12" />
                            </svg>
                            Kirim Pengaduan
                        </button>
                    </div>
                </form>
            </div>

            {{-- Footer Info --}}
            <div class="mt-6 text-center">
                <p class="text-xs text-slate-500 flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-cyan-500/50" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                    </svg>
                    Data Anda aman dan identitas dijamin kerahasiaannya
                </p>
            </div>
        </div>
    </main>
</x-layouts.app>