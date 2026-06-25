<x-layouts.app title="Struktur Organisasi - Kankemenag Tanah Datar">
    <!-- Profil Menu Sidebar -->
    <x-profil-menu
        profil-url="{{ route('profil-kantor') }}"
        sejarah-url="{{ route('sejarah') }}"
        struktur-url="{{ route('struktur-organisasi') }}"
        unit-url="{{ route('satuan-kerja') }}?tab=kua"
    />

    <div class="lg:pl-20">
        <main class="relative overflow-x-hidden">

            <!-- Hero Section -->
            <section class="relative py-16 overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-purple-950 via-slate-950 to-slate-950"></div>
                <div class="absolute top-0 right-0 w-96 h-96 bg-purple-500/10 rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 left-0 w-64 h-64 bg-cyan-500/10 rounded-full blur-3xl"></div>

                <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6">
                    <div class="text-center mb-12">
                        <div class="inline-flex items-center gap-3 px-5 py-2 bg-purple-500/10 border border-purple-500/30 rounded-full mb-6">
                            <svg class="w-5 h-5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <rect x="9" y="2" width="6" height="6" rx="1"/>
                                <path d="M4 22v-4a2 2 0 012-2h12a2 2 0 012 2v4"/>
                                <path d="M12 12v4"/>
                                <path d="M6 12v4a2 2 0 002 2h8a2 2 0 002-2v-4"/>
                            </svg>
                            <span class="font-mono text-sm font-bold uppercase tracking-wider text-purple-400">Struktur</span>
                        </div>
                        <h1 class="font-mono text-4xl md:text-5xl font-black uppercase tracking-wider text-white mb-4">
                            Organisasi <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-cyan-500">Kementerian Agama</span>
                        </h1>
                        <p class="font-mono text-lg text-slate-400 uppercase tracking-wide">Kabupaten Tanah Datar</p>
                    </div>
                </div>
            </section>

            <!-- Org Chart Section -->
            <section class="relative py-16 bg-gradient-to-b from-slate-950 to-slate-900">
                <div class="relative z-10 max-w-5xl mx-auto px-4 sm:px-6">

                    <!-- KEPALA KANTOR -->
                    <div class="flex justify-center mb-8">
                        <div class="relative group">
                            <div class="absolute inset-0 bg-gradient-to-br from-cyan-500/20 to-purple-500/10 rounded-2xl blur-xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            <div class="relative p-5 bg-gradient-to-br from-slate-900 to-slate-800/80 border-2 border-cyan-500/50 rounded-2xl shadow-xl shadow-cyan-500/10 text-center">
                                <div class="w-16 h-16 bg-gradient-to-br from-cyan-500 to-cyan-700 rounded-2xl flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <h3 class="font-mono text-base font-bold text-cyan-400 uppercase tracking-wider">Kepala Kantor</h3>
                            </div>
                        </div>
                    </div>

                    <!-- Connector: Kepala Kantor to middle -->
                    <div class="flex justify-center mb-0">
                        <div class="w-px h-8 bg-gradient-to-b from-cyan-500 to-slate-700"></div>
                    </div>

                    <!-- Horizontal Line -->
                    <div class="relative mb-0">
                        <div class="h-px bg-gradient-to-r from-transparent via-slate-600 to-transparent"></div>
                    </div>

                    <!-- Vertical Line down to middle -->
                    <div class="flex justify-center mb-8">
                        <div class="w-px h-8 bg-slate-700"></div>
                    </div>

                    <!-- KASUBAG TU and KASI Row -->
                    <div class="relative">
                        <!-- Vertical line down to Kasubag -->
                        <div class="absolute left-1/2 top-0 w-px h-full bg-gradient-to-b from-slate-600 to-slate-700 hidden md:block" style="transform: translateX(-50%);"></div>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">
                            <!-- Left: KASI - Bimas, PAIS, PD Pontren, Penmad -->
                            <div class="space-y-4">
                                <div class="text-center lg:text-right lg:pr-8">
                                    <span class="inline-block px-4 py-2 bg-slate-800/50 border border-slate-600/30 rounded-full font-mono text-xs text-slate-400 uppercase tracking-wider mb-4">Jabatan Struktural</span>
                                </div>

                                <div class="relative space-y-3">
                                    <div class="h-px bg-gradient-to-l from-transparent to-slate-700 absolute top-1/2 w-8 right-0 hidden lg:block"></div>

                                    <div class="relative p-4 bg-slate-900/60 border border-rose-500/30 rounded-xl hover:border-rose-400/50 transition-colors">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 bg-rose-500/20 border border-rose-500/30 rounded-xl flex items-center justify-center flex-shrink-0">
                                                <svg class="w-5 h-5 text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                    <path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <h4 class="font-mono text-sm font-bold text-rose-400 uppercase tracking-wider">Kasi Bimas Islam</h4>
                                                <p class="font-mono text-[10px] text-slate-500 uppercase tracking-wider">Bimbingan Masyarakat Islam</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="p-4 bg-slate-900/60 border border-emerald-500/30 rounded-xl hover:border-emerald-400/50 transition-colors">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 bg-emerald-500/20 border border-emerald-500/30 rounded-xl flex items-center justify-center flex-shrink-0">
                                                <svg class="w-5 h-5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                    <path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <h4 class="font-mono text-sm font-bold text-emerald-400 uppercase tracking-wider">Kasi PAIS</h4>
                                                <p class="font-mono text-[10px] text-slate-500 uppercase tracking-wider">Urusan Agama Islam</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="p-4 bg-slate-900/60 border border-amber-500/30 rounded-xl hover:border-amber-400/50 transition-colors">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 bg-amber-500/20 border border-amber-500/30 rounded-xl flex items-center justify-center flex-shrink-0">
                                                <svg class="w-5 h-5 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                    <path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <h4 class="font-mono text-sm font-bold text-amber-400 uppercase tracking-wider">Kasi PD Pontren</h4>
                                                <p class="font-mono text-[10px] text-slate-500 uppercase tracking-wider">Pendidikan Diniyah</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="p-4 bg-slate-900/60 border border-teal-500/30 rounded-xl hover:border-teal-400/50 transition-colors">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 bg-teal-500/20 border border-teal-500/30 rounded-xl flex items-center justify-center flex-shrink-0">
                                                <svg class="w-5 h-5 text-teal-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                    <path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.32"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <h4 class="font-mono text-sm font-bold text-teal-400 uppercase tracking-wider">Kasi Penmad</h4>
                                                <p class="font-mono text-[10px] text-slate-500 uppercase tracking-wider">Pendidikan Madrasah</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4 p-3 bg-slate-900/40 border border-slate-700/30 rounded-lg text-center">
                                    <span class="font-mono text-[10px] text-slate-500 uppercase tracking-wider">JFT dan JFU Seksi masing-masing</span>
                                </div>
                            </div>

                            <!-- Right: KASUBAG TU -->
                            <div class="lg:pl-8">
                                <div class="text-center lg:text-left mb-4">
                                    <span class="inline-block px-4 py-2 bg-slate-800/50 border border-slate-600/30 rounded-full font-mono text-xs text-slate-400 uppercase tracking-wider">Pembantu Tulsa</span>
                                </div>

                                <div class="relative p-5 bg-gradient-to-br from-slate-900/80 to-slate-800/60 border border-purple-500/40 rounded-2xl">
                                    <div class="flex items-center gap-4">
                                        <div class="w-14 h-14 bg-gradient-to-br from-purple-500/20 to-purple-600/20 border border-purple-500/40 rounded-xl flex items-center justify-center flex-shrink-0">
                                            <svg class="w-7 h-7 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="font-mono text-base font-bold text-purple-400 uppercase tracking-wider">Kepala Subbagian TU</h3>
                                            <p class="font-mono text-xs text-slate-400 uppercase tracking-wider">Tata Usaha</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- JFT dan JFU Subbag TU -->
                                <div class="mt-4 p-4 bg-slate-900/40 border border-slate-700/30 rounded-xl">
                                    <div class="flex items-center gap-3 mb-3">
                                        <div class="w-8 h-8 bg-slate-800 border border-slate-600/30 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                            </svg>
                                        </div>
                                        <span class="font-mono text-xs text-slate-500 uppercase tracking-wider">Jabatan Fungsional Tertentu</span>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 bg-slate-800 border border-slate-600/30 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/>
                                            </svg>
                                        </div>
                                        <span class="font-mono text-xs text-slate-500 uppercase tracking-wider">JFU Subbag TU</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- KUA Section -->
                    <div class="mt-12 p-6 bg-slate-900/30 border border-amber-500/20 rounded-2xl">
                        <div class="text-center mb-6">
                            <div class="inline-flex items-center gap-2 px-4 py-2 bg-amber-500/10 border border-amber-500/30 rounded-full">
                                <svg class="w-4 h-4 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1"/>
                                </svg>
                                <span class="font-mono text-xs font-bold uppercase tracking-wider text-amber-400">Kantor Urusan Agama Nagari/Kecamatan</span>
                            </div>
                        </div>

                        <div class="flex flex-wrap justify-center gap-3">
                            @php
                            $kuas = [
                                'KUA X Batusangkar',
                                'KUA Lima Katung',
                                'KUA Pagaruyung',
                                'KUA Junjung Sirih',
                                'KUA Sumpur',
                                'KUA Scalar Barat',
                                'KUA Scalar Timur',
                                'KUA Talawi',
                            ];
                            @endphp
                            @foreach($kuas as $kua)
                            <a href="{{ route('satuan-kerja') }}?tab=kua" class="group inline-flex items-center gap-2 px-4 py-2 bg-slate-900/60 border border-slate-700/50 rounded-lg hover:border-amber-400/50 hover:bg-amber-500/10 transition-all">
                                <svg class="w-4 h-4 text-slate-600 group-hover:text-amber-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16"/>
                                </svg>
                                <span class="font-mono text-xs text-slate-400 group-hover:text-amber-300 transition-colors">{{ $kua }}</span>
                            </a>
                            @endforeach
                        </div>
                    </div>

                </div>
            </section>

            <!-- Footer -->
            <footer class="relative py-8 border-t border-slate-800">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 text-center">
                    <div class="flex items-center justify-center gap-3 mb-4">
                        <img src="{{ asset('favicon.webp') }}" alt="SILATAR" class="h-8 w-8 rounded-lg">
                        <span class="font-mono text-sm font-bold uppercase tracking-wider text-purple-400">SILATAR</span>
                    </div>
                    <p class="font-mono text-xs text-slate-500">Kantor Kementerian Agama Kabupaten Tanah Datar</p>
                </div>
            </footer>
        </main>
    </div>
</x-layouts.app>
