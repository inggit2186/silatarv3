<x-layouts.app title="Profil Kantor - Kankemenag Tanah Datar">
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
                <!-- Background Elements -->
                <div class="absolute inset-0 bg-gradient-to-br from-cyan-950 via-slate-950 to-slate-950"></div>
                <div class="absolute top-0 left-0 w-96 h-96 bg-cyan-500/10 rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 right-0 w-64 h-64 bg-purple-500/10 rounded-full blur-3xl"></div>

                <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6">
                    <!-- Header -->
                    <div class="text-center mb-12">
                        <div class="inline-flex items-center gap-3 px-5 py-2 bg-cyan-500/10 border border-cyan-500/30 rounded-full mb-6">
                            <svg class="w-5 h-5 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/>
                                <circle cx="12" cy="7" r="4"/>
                            </svg>
                            <span class="font-mono text-sm font-bold uppercase tracking-wider text-cyan-400">Profil Kantor</span>
                        </div>
                        <h1 class="font-mono text-4xl md:text-5xl font-black uppercase tracking-wider text-white mb-4">
                            Kantor <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-cyan-600">Kementerian Agama</span>
                        </h1>
                        <p class="font-mono text-lg text-slate-400 uppercase tracking-wide">Kabupaten Tanah Datar</p>
                    </div>

                    <!-- Decorative Line -->
                    <div class="flex items-center justify-center gap-4 mb-12">
                        <div class="h-px w-24 bg-gradient-to-r from-transparent to-cyan-500"></div>
                        <div class="w-3 h-3 bg-cyan-500 rounded-full animate-pulse"></div>
                        <div class="h-px w-24 bg-gradient-to-l from-transparent to-cyan-500"></div>
                    </div>
                </div>
            </section>

            <!-- Visi Section -->
            <section class="relative py-16 bg-gradient-to-b from-slate-950 to-slate-900">
                <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6">
                    <div class="grid lg:grid-cols-2 gap-12 items-center">

                        <!-- Left: Icon & Title -->
                        <div class="relative">
                            <div class="sticky top-32">
                                <!-- Large Geometric Icon -->
                                <div class="relative w-48 h-48 mx-auto lg:mx-0 mb-8">
                                    <div class="absolute inset-0 bg-gradient-to-br from-cyan-500/20 to-transparent rounded-3xl transform rotate-6"></div>
                                    <div class="absolute inset-0 bg-gradient-to-tl from-emerald-500/20 to-transparent rounded-3xl transform -rotate-3"></div>
                                    <div class="relative w-full h-full bg-slate-900/80 border-2 border-cyan-500/30 rounded-3xl flex items-center justify-center">
                                        <svg class="w-20 h-20 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                            <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </div>
                                </div>

                                <h2 class="font-mono text-3xl font-bold uppercase tracking-wider text-white mb-2">Visi</h2>
                                <div class="w-16 h-1 bg-gradient-to-r from-cyan-500 to-emerald-500 rounded-full"></div>
                            </div>
                        </div>

                        <!-- Right: Content -->
                        <div>
                            <div class="relative">
                                <div class="absolute -left-4 top-0 bottom-0 w-1 bg-gradient-to-b from-cyan-500 via-emerald-500 to-transparent rounded-full"></div>
                                <div class="pl-8">
                                    <p class="font-mono text-xl text-cyan-100 leading-relaxed mb-6">
                                        "Terwujudnya Madrasah dan KUA yang <span class="text-cyan-400 font-bold">Professional</span>, <span class="text-emerald-400 font-bold">Modern</span>, dan <span class="text-amber-400 font-bold">Integratif</span> dalam Jajaran Kantor Kementerian Agama Kabupaten Tanah Datar"
                                    </p>

                                    <div class="grid sm:grid-cols-3 gap-4">
                                        <div class="p-4 bg-slate-800/50 border border-cyan-500/20 rounded-xl text-center">
                                            <span class="font-mono text-2xl font-bold text-cyan-400">Professional</span>
                                            <p class="font-mono text-xs text-slate-400 mt-1 uppercase tracking-wider">Berkualitas</p>
                                        </div>
                                        <div class="p-4 bg-slate-800/50 border border-emerald-500/20 rounded-xl text-center">
                                            <span class="font-mono text-2xl font-bold text-emerald-400">Modern</span>
                                            <p class="font-mono text-xs text-slate-400 mt-1 uppercase tracking-wider">Berbasis Teknologi</p>
                                        </div>
                                        <div class="p-4 bg-slate-800/50 border border-amber-500/20 rounded-xl text-center">
                                            <span class="font-mono text-2xl font-bold text-amber-400">Integratif</span>
                                            <p class="font-mono text-xs text-slate-400 mt-1 uppercase tracking-wider">Terintegrasi</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Misi Section -->
            <section class="relative py-16 bg-gradient-to-b from-slate-900 to-slate-950">
                <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6">
                    <div class="grid lg:grid-cols-2 gap-12 items-center">

                        <!-- Right: Content (order on mobile) -->
                        <div class="order-2 lg:order-1">
                            <div class="space-y-6">
                                <div class="relative p-6 bg-gradient-to-r from-slate-800/50 to-slate-900/50 border border-slate-700/50 rounded-2xl group hover:border-cyan-500/50 transition-colors">
                                    <div class="absolute -left-3 top-1/2 -translate-y-1/2 w-6 h-6 bg-cyan-500 rounded-full flex items-center justify-center">
                                        <span class="font-mono text-xs font-bold text-slate-900">1</span>
                                    </div>
                                    <p class="font-mono text-slate-300 leading-relaxed pl-4">
                                        Meningkatkan <span class="text-cyan-400 font-semibold">kualitas sumber daya manusia</span> di bidang pendidikan Madrasah dan urusan keagamaan melalui pelatihan dan pengembangan kompetensi.
                                    </p>
                                </div>

                                <div class="relative p-6 bg-gradient-to-r from-slate-800/50 to-slate-900/50 border border-slate-700/50 rounded-2xl group hover:border-emerald-500/50 transition-colors">
                                    <div class="absolute -left-3 top-1/2 -translate-y-1/2 w-6 h-6 bg-emerald-500 rounded-full flex items-center justify-center">
                                        <span class="font-mono text-xs font-bold text-slate-900">2</span>
                                    </div>
                                    <p class="font-mono text-slate-300 leading-relaxed pl-4">
                                        Mengembangkan <span class="text-emerald-400 font-semibold">pelayanan prima</span> yang mudah diakses masyarakat melalui digitalisasi dan optimalisasi teknologi informasi.
                                    </p>
                                </div>

                                <div class="relative p-6 bg-gradient-to-r from-slate-800/50 to-slate-900/50 border border-slate-700/50 rounded-2xl group hover:border-purple-500/50 transition-colors">
                                    <div class="absolute -left-3 top-1/2 -translate-y-1/2 w-6 h-6 bg-purple-500 rounded-full flex items-center justify-center">
                                        <span class="font-mono text-xs font-bold text-slate-900">3</span>
                                    </div>
                                    <p class="font-mono text-slate-300 leading-relaxed pl-4">
                                        Memperkuat <span class="text-purple-400 font-semibold">koordinasi dan sinkronisasi</span> dengan stakeholder terkait untuk meningkatkan efektivitas penyelenggaraan pemerintahan.
                                    </p>
                                </div>

                                <div class="relative p-6 bg-gradient-to-r from-slate-800/50 to-slate-900/50 border border-slate-700/50 rounded-2xl group hover:border-amber-500/50 transition-colors">
                                    <div class="absolute -left-3 top-1/2 -translate-y-1/2 w-6 h-6 bg-amber-500 rounded-full flex items-center justify-center">
                                        <span class="font-mono text-xs font-bold text-slate-900">4</span>
                                    </div>
                                    <p class="font-mono text-slate-300 leading-relaxed pl-4">
                                        Mengawal <span class="text-amber-400 font-semibold">pelaksanaan tugas pokok dan fungsi</span> dengan prinsip transparansi, akuntabilitas, dan pelayanan optimal.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Left: Icon & Title -->
                        <div class="order-1 lg:order-2">
                            <div class="sticky top-32">
                                <!-- Large Geometric Icon -->
                                <div class="relative w-48 h-48 mx-auto lg:mx-0 mb-8">
                                    <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/20 to-transparent rounded-3xl transform -rotate-6"></div>
                                    <div class="absolute inset-0 bg-gradient-to-tl from-purple-500/20 to-transparent rounded-3xl transform rotate-3"></div>
                                    <div class="relative w-full h-full bg-slate-900/80 border-2 border-emerald-500/30 rounded-3xl flex items-center justify-center">
                                        <svg class="w-20 h-20 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                            <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                        </svg>
                                    </div>
                                </div>

                                <h2 class="font-mono text-3xl font-bold uppercase tracking-wider text-white mb-2">Misi</h2>
                                <div class="w-16 h-1 bg-gradient-to-r from-emerald-500 to-purple-500 rounded-full"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Tugas Pokok Section -->
            <section class="relative py-16 bg-gradient-to-b from-slate-950 to-slate-900">
                <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6">
                    <div class="text-center mb-12">
                        <h2 class="font-mono text-3xl font-bold uppercase tracking-wider text-white mb-4">Tugas Pokok & Fungsi</h2>
                        <div class="flex items-center justify-center gap-4">
                            <div class="h-px w-16 bg-gradient-to-r from-transparent to-cyan-500"></div>
                            <svg class="w-6 h-6 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            <div class="h-px w-16 bg-gradient-to-l from-transparent to-cyan-500"></div>
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Card 1 -->
                        <div class="relative group">
                            <div class="absolute inset-0 bg-gradient-to-br from-cyan-500/10 to-transparent rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            <div class="relative p-6 bg-slate-900/50 border border-slate-700/50 rounded-2xl">
                                <div class="w-12 h-12 bg-cyan-500/10 border border-cyan-500/30 rounded-xl flex items-center justify-center mb-4">
                                    <svg class="w-6 h-6 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                    </svg>
                                </div>
                                <h3 class="font-mono text-lg font-bold text-white mb-2">Pendidikan Madrasah</h3>
                                <p class="font-mono text-sm text-slate-400 leading-relaxed">Pengelolaan dan pengawasan pendidikan Madrasah dari tingkat Ibtidaiyah hingga Aliyah di wilayah Kabupaten Tanah Datar.</p>
                            </div>
                        </div>

                        <!-- Card 2 -->
                        <div class="relative group">
                            <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/10 to-transparent rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            <div class="relative p-6 bg-slate-900/50 border border-slate-700/50 rounded-2xl">
                                <div class="w-12 h-12 bg-emerald-500/10 border border-emerald-500/30 rounded-xl flex items-center justify-center mb-4">
                                    <svg class="w-6 h-6 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                </div>
                                <h3 class="font-mono text-lg font-bold text-white mb-2">KUA Kecamatan</h3>
                                <p class="font-mono text-sm text-slate-400 leading-relaxed">Pembinaan dan pengawasan pelayanan keagamaan di Kantor Urusan Agama tingkat kecamatan.</p>
                            </div>
                        </div>

                        <!-- Card 3 -->
                        <div class="relative group">
                            <div class="absolute inset-0 bg-gradient-to-br from-purple-500/10 to-transparent rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            <div class="relative p-6 bg-slate-900/50 border border-slate-700/50 rounded-2xl">
                                <div class="w-12 h-12 bg-purple-500/10 border border-purple-500/30 rounded-xl flex items-center justify-center mb-4">
                                    <svg class="w-6 h-6 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <h3 class="font-mono text-lg font-bold text-white mb-2">Keagamaan</h3>
                                <p class="font-mono text-sm text-slate-400 leading-relaxed">Pembinaan dan pengelolaan urusan keagamaan Islam, Perkara nikah, pengajaran agama, dan dakwah.</p>
                            </div>
                        </div>

                        <!-- Card 4 -->
                        <div class="relative group">
                            <div class="absolute inset-0 bg-gradient-to-br from-amber-500/10 to-transparent rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            <div class="relative p-6 bg-slate-900/50 border border-slate-700/50 rounded-2xl">
                                <div class="w-12 h-12 bg-amber-500/10 border border-amber-500/30 rounded-xl flex items-center justify-center mb-4">
                                    <svg class="w-6 h-6 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                </div>
                                <h3 class="font-mono text-lg font-bold text-white mb-2">Hukum & Rohis</h3>
                                <p class="font-mono text-sm text-slate-400 leading-relaxed">Penanganan urusan hukum agama dan pengelolaan wakaf, shadaqah, serta infaq untuk kemaslahatan umat.</p>
                            </div>
                        </div>

                        <!-- Card 5 -->
                        <div class="relative group">
                            <div class="absolute inset-0 bg-gradient-to-br from-rose-500/10 to-transparent rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            <div class="relative p-6 bg-slate-900/50 border border-slate-700/50 rounded-2xl">
                                <div class="w-12 h-12 bg-rose-500/10 border border-rose-500/30 rounded-xl flex items-center justify-center mb-4">
                                    <svg class="w-6 h-6 text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <h3 class="font-mono text-lg font-bold text-white mb-2">Pelayanan Publik</h3>
                                <p class="font-mono text-sm text-slate-400 leading-relaxed">Penyediaan layanan publik yang optimal untuk masyarakat dalam pengurusan dokumen keagamaan.</p>
                            </div>
                        </div>

                        <!-- Card 6 -->
                        <div class="relative group">
                            <div class="absolute inset-0 bg-gradient-to-br from-sky-500/10 to-transparent rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            <div class="relative p-6 bg-slate-900/50 border border-slate-700/50 rounded-2xl">
                                <div class="w-12 h-12 bg-sky-500/10 border border-sky-500/30 rounded-xl flex items-center justify-center mb-4">
                                    <svg class="w-6 h-6 text-sky-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                                    </svg>
                                </div>
                                <h3 class="font-mono text-lg font-bold text-white mb-2">Pembinaan & Pengawasan</h3>
                                <p class="font-mono text-sm text-slate-400 leading-relaxed">Pembinaan dan pengawasan terhadap seluruh units dan stakeholder terkait.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Footer -->
            <footer class="relative py-8 border-t border-slate-800">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 text-center">
                    <div class="flex items-center justify-center gap-3 mb-4">
                        <img src="{{ asset('favicon.webp') }}" alt="SILATAR" class="h-8 w-8 rounded-lg">
                        <span class="font-mono text-sm font-bold uppercase tracking-wider text-cyan-400">SILATAR</span>
                    </div>
                    <p class="font-mono text-xs text-slate-500">Kantor Kementerian Agama Kabupaten Tanah Datar</p>
                </div>
            </footer>
        </main>
    </div>
</x-layouts.app>
