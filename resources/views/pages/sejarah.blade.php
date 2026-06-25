<x-layouts.app title="Sejarah - Kankemenag Tanah Datar">
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
                <div class="absolute inset-0 bg-gradient-to-br from-emerald-950 via-slate-950 to-slate-950"></div>
                <div class="absolute top-0 left-0 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 right-0 w-64 h-64 bg-cyan-500/10 rounded-full blur-3xl"></div>

                <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6">
                    <div class="text-center mb-12">
                        <div class="inline-flex items-center gap-3 px-5 py-2 bg-emerald-500/10 border border-emerald-500/30 rounded-full mb-6">
                            <svg class="w-5 h-5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path d="M12 8v4l3 3"/>
                                <circle cx="12" cy="12" r="9"/>
                            </svg>
                            <span class="font-mono text-sm font-bold uppercase tracking-wider text-emerald-400">Perjalanan Waktu</span>
                        </div>
                        <h1 class="font-mono text-4xl md:text-5xl font-black uppercase tracking-wider text-white mb-4">
                            Sejarah <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-cyan-500">Kami</span>
                        </h1>
                        <p class="font-mono text-lg text-slate-400 uppercase tracking-wide">Mengenang Perjalanan Kantor Kementerian Agama</p>
                    </div>
                </div>
            </section>

            <!-- Era Cards Section -->
            <section class="relative py-16 bg-gradient-to-b from-slate-950 to-slate-900">
                <div class="relative z-10 max-w-6xl mx-auto px-4 sm:px-6">

                    <!-- Decade Cards Grid -->
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 mb-16">

                        <!-- 1945 - Kemerdekaan -->
                        <div class="relative group">
                            <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/20 via-emerald-600/10 to-transparent rounded-2xl blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                            <div class="relative h-full p-6 bg-gradient-to-br from-slate-900 to-slate-900/50 border border-emerald-500/20 rounded-2xl hover:border-emerald-400/50 transition-colors">
                                <div class="flex items-start gap-4 mb-4">
                                    <div class="w-16 h-16 bg-emerald-500/20 border-2 border-emerald-500/30 rounded-2xl flex items-center justify-center flex-shrink-0">
                                        <svg class="w-8 h-8 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 8.143L17 21l-2.286-6.857L13 12l5.714-8.143L13 3z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <span class="font-mono text-3xl font-black text-emerald-400">1945</span>
                                        <p class="font-mono text-xs text-emerald-400/60 uppercase tracking-wider">Awal Kemerdekaan</p>
                                    </div>
                                </div>
                                <p class="font-mono text-sm text-slate-400 leading-relaxed mb-4">
                                    Pasca kemerdekaan Indonesia, pemerintah mulai mendirikan lembaga-lembaga keagamaan untuk melayani masyarakat Muslim di berbagai daerah.
                                </p>
                                <div class="flex items-center gap-2">
                                    <div class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></div>
                                    <span class="font-mono text-xs text-emerald-400/60 uppercase tracking-wider">Titik Awal</span>
                                </div>
                            </div>
                        </div>

                        <!-- 1960 - Pembentukan -->
                        <div class="relative group">
                            <div class="absolute inset-0 bg-gradient-to-br from-cyan-500/20 via-cyan-600/10 to-transparent rounded-2xl blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                            <div class="relative h-full p-6 bg-gradient-to-br from-slate-900 to-slate-900/50 border border-cyan-500/20 rounded-2xl hover:border-cyan-400/50 transition-colors">
                                <div class="flex items-start gap-4 mb-4">
                                    <div class="w-16 h-16 bg-cyan-500/20 border-2 border-cyan-500/30 rounded-2xl flex items-center justify-center flex-shrink-0">
                                        <svg class="w-8 h-8 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <span class="font-mono text-3xl font-black text-cyan-400">1960</span>
                                        <p class="font-mono text-xs text-cyan-400/60 uppercase tracking-wider">Pembentukan</p>
                                    </div>
                                </div>
                                <p class="font-mono text-sm text-slate-400 leading-relaxed mb-4">
                                    Kantor Kementerian Agama Kabupaten Tanah Datar resmi dibentuk untuk mengelola urusan keagamaan Islam di wilayah Sumatera Barat.
                                </p>
                                <div class="flex items-center gap-2">
                                    <div class="w-2 h-2 bg-cyan-400 rounded-full"></div>
                                    <span class="font-mono text-xs text-cyan-400/60 uppercase tracking-wider">Sejarah Bermula</span>
                                </div>
                            </div>
                        </div>

                        <!-- 1975 - Penguatan -->
                        <div class="relative group">
                            <div class="absolute inset-0 bg-gradient-to-br from-teal-500/20 via-teal-600/10 to-transparent rounded-2xl blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                            <div class="relative h-full p-6 bg-gradient-to-br from-slate-900 to-slate-900/50 border border-teal-500/20 rounded-2xl hover:border-teal-400/50 transition-colors">
                                <div class="flex items-start gap-4 mb-4">
                                    <div class="w-16 h-16 bg-teal-500/20 border-2 border-teal-500/30 rounded-2xl flex items-center justify-center flex-shrink-0">
                                        <svg class="w-8 h-8 text-teal-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <span class="font-mono text-3xl font-black text-teal-400">1975</span>
                                        <p class="font-mono text-xs text-teal-400/60 uppercase tracking-wider">Penguatan</p>
                                    </div>
                                </div>
                                <p class="font-mono text-sm text-slate-400 leading-relaxed mb-4">
                                    Pengawasan Madrasah semakin diperkuat dengan pengembangan kurikulum dan peningkatan kualitas tenaga pengajar.
                                </p>
                                <div class="flex items-center gap-2">
                                    <div class="w-2 h-2 bg-teal-400 rounded-full"></div>
                                    <span class="font-mono text-xs text-teal-400/60 uppercase tracking-wider">Pendidikan</span>
                                </div>
                            </div>
                        </div>

                        <!-- 1990 - Ekspansi -->
                        <div class="relative group">
                            <div class="absolute inset-0 bg-gradient-to-br from-amber-500/20 via-amber-600/10 to-transparent rounded-2xl blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                            <div class="relative h-full p-6 bg-gradient-to-br from-slate-900 to-slate-900/50 border border-amber-500/20 rounded-2xl hover:border-amber-400/50 transition-colors">
                                <div class="flex items-start gap-4 mb-4">
                                    <div class="w-16 h-16 bg-amber-500/20 border-2 border-amber-500/30 rounded-2xl flex items-center justify-center flex-shrink-0">
                                        <svg class="w-8 h-8 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <span class="font-mono text-3xl font-black text-amber-400">1990</span>
                                        <p class="font-mono text-xs text-amber-400/60 uppercase tracking-wider">Ekspansi</p>
                                    </div>
                                </div>
                                <p class="font-mono text-sm text-slate-400 leading-relaxed mb-4">
                                    Pembukaan Kantor Urusan Agama (KUA) baru di beberapa kecamatan untuk mendekatkan pelayanan kepada masyarakat.
                                </p>
                                <div class="flex items-center gap-2">
                                    <div class="w-2 h-2 bg-amber-400 rounded-full"></div>
                                    <span class="font-mono text-xs text-amber-400/60 uppercase tracking-wider">KUA Baru</span>
                                </div>
                            </div>
                        </div>

                        <!-- 2010 - Digital -->
                        <div class="relative group">
                            <div class="absolute inset-0 bg-gradient-to-br from-purple-500/20 via-purple-600/10 to-transparent rounded-2xl blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                            <div class="relative h-full p-6 bg-gradient-to-br from-slate-900 to-slate-900/50 border border-purple-500/20 rounded-2xl hover:border-purple-400/50 transition-colors">
                                <div class="flex items-start gap-4 mb-4">
                                    <div class="w-16 h-16 bg-purple-500/20 border-2 border-purple-500/30 rounded-2xl flex items-center justify-center flex-shrink-0">
                                        <svg class="w-8 h-8 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <span class="font-mono text-3xl font-black text-purple-400">2010</span>
                                        <p class="font-mono text-xs text-purple-400/60 uppercase tracking-wider">Era Digital</p>
                                    </div>
                                </div>
                                <p class="font-mono text-sm text-slate-400 leading-relaxed mb-4">
                                    Memulai pemanfaatan teknologi informasi dalam pengelolaan data dan administrasi. Website resmi diluncurkan.
                                </p>
                                <div class="flex items-center gap-2">
                                    <div class="w-2 h-2 bg-purple-400 rounded-full"></div>
                                    <span class="font-mono text-xs text-purple-400/60 uppercase tracking-wider">Digitalisasi</span>
                                </div>
                            </div>
                        </div>

                        <!-- 2024 - SILATAR V2 -->
                        <div class="relative group">
                            <div class="absolute inset-0 bg-gradient-to-br from-rose-500/20 via-rose-600/10 to-transparent rounded-2xl blur-xl opacity-100 group-hover:opacity-100 transition-opacity duration-500"></div>
                            <div class="relative h-full p-6 bg-gradient-to-br from-slate-900 to-slate-900/50 border-2 border-rose-500/50 rounded-2xl shadow-lg shadow-rose-500/10">
                                <div class="flex items-start gap-4 mb-4">
                                    <div class="w-16 h-16 bg-gradient-to-br from-rose-500 to-rose-700 rounded-2xl flex items-center justify-center flex-shrink-0 animate-pulse">
                                        <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <span class="font-mono text-3xl font-black text-rose-400">2024</span>
                                        <p class="font-mono text-xs text-rose-400/60 uppercase tracking-wider">Sekarang</p>
                                    </div>
                                </div>
                                <p class="font-mono text-sm text-slate-300 leading-relaxed mb-4">
                                    Peluncuran <span class="text-rose-400 font-bold">SILATAR V2</span> - Sistem Layanan Digital terintegrasi untuk meningkatkan pelayanan kepada masyarakat.
                                </p>
                                <div class="flex items-center gap-2">
                                    <div class="w-2 h-2 bg-rose-400 rounded-full animate-pulse"></div>
                                    <span class="font-mono text-xs text-rose-400 uppercase tracking-wider font-bold">Sistem Baru</span>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            </section>

            <!-- Detail Sejarah Section -->
            <section class="relative py-16 bg-gradient-to-b from-slate-950 to-slate-900">
                <div class="relative z-10 max-w-5xl mx-auto px-4 sm:px-6">

                    <!-- Header -->
                    <div class="text-center mb-12">
                        <h2 class="font-mono text-2xl font-bold uppercase tracking-wider text-white mb-4">Detail Sejarah</h2>
                        <div class="flex items-center justify-center gap-4">
                            <div class="h-px w-16 bg-gradient-to-r from-transparent to-emerald-500"></div>
                            <div class="w-3 h-3 bg-emerald-500 rounded-full"></div>
                            <div class="h-px w-16 bg-gradient-to-l from-transparent to-emerald-500"></div>
                        </div>
                    </div>

                    <!-- Awal Berdiri Card -->
                    <div class="relative mb-8">
                        <div class="absolute -left-4 top-0 bottom-0 w-1 bg-gradient-to-b from-emerald-500 to-cyan-500 rounded-full"></div>
                        <div class="pl-8">
                            <div class="flex items-center gap-4 mb-4">
                                <div class="w-12 h-12 bg-emerald-500/20 border border-emerald-500/30 rounded-xl flex items-center justify-center">
                                    <svg class="w-6 h-6 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16"/>
                                    </svg>
                                </div>
                                <h3 class="font-mono text-lg font-bold text-emerald-400">Awal Berdiri</h3>
                            </div>
                            <div class="bg-slate-900/50 border border-slate-700/50 rounded-2xl p-6">
                                <div class="flex items-center gap-3 mb-4">
                                    <span class="px-3 py-1 bg-emerald-500/20 border border-emerald-500/30 rounded-full font-mono text-xs font-bold text-emerald-400">3 Januari 1946</span>
                                    <span class="font-mono text-xs text-slate-500">Jakarta</span>
                                </div>
                                <p class="font-mono text-sm text-slate-300 leading-relaxed mb-4">
                                    Kementerian Agama terbentuk pada <span class="text-emerald-400 font-bold">3 Januari 1946</span> di Jakarta. Menteri Agama menginstruksikan kepada Gubernur Kepala Wilayah untuk membentuk Jawatan Agama pada tingkat Provinsi dan Kabupaten termasuk Kewedanaan Batusangkar.
                                </p>
                                <p class="font-mono text-sm text-slate-300 leading-relaxed mb-4">
                                    Atas dasar instruksi Menteri Agama tersebut, maka pada tahun <span class="text-emerald-400 font-bold">1946</span> dibentuklah <span class="text-emerald-400 font-bold">Jawatan Agama di Batusangkar</span> yang berkantor di Rumah Pajak Gadai (Kantor Pajak Gadai Batusangkar sekarang).
                                </p>
                                <div class="bg-emerald-500/10 border border-emerald-500/20 rounded-xl p-4">
                                    <p class="font-mono text-sm text-emerald-300 leading-relaxed mb-2">
                                        Tanggal <span class="font-bold">3 Januari 1946</span> ditetapkan sebagai <span class="font-bold">Hari Amal Bhakti (HAB)</span> Kementerian Agama.
                                    </p>
                                    <p class="font-mono text-sm text-emerald-400/80 italic">Motto: "Ikhlas Beramal"</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Timeline Periods -->
                    <div class="mb-8">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 bg-cyan-500/20 border border-cyan-500/30 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path d="M12 8v4l3 3"/>
                                    <circle cx="12" cy="12" r="9"/>
                                </svg>
                            </div>
                            <h3 class="font-mono text-lg font-bold text-cyan-400">Periode Kelembagaan</h3>
                        </div>

                        <div class="grid gap-4">
                            <!-- Periode 1 -->
                            <div class="relative p-5 bg-slate-900/30 border border-slate-700/30 rounded-xl hover:border-cyan-500/30 transition-colors">
                                <div class="flex items-start gap-4">
                                    <div class="w-10 h-10 bg-cyan-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <span class="font-mono text-sm font-bold text-cyan-400">1946</span>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-mono text-sm text-slate-300 leading-relaxed">
                                            <span class="text-cyan-400 font-bold">Periode 1946 - 1956</span>. Kementerian Agama mulai bertugas pada <span class="text-cyan-400">12 Maret 1946</span>.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Periode 2 -->
                            <div class="relative p-5 bg-slate-900/30 border border-slate-700/30 rounded-xl hover:border-emerald-500/30 transition-colors">
                                <div class="flex items-start gap-4">
                                    <div class="w-10 h-10 bg-emerald-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <span class="font-mono text-sm font-bold text-emerald-400">1966</span>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-mono text-sm text-slate-300 leading-relaxed">
                                            <span class="text-emerald-400 font-bold">Periode 1966 - 1974</span>. Dirumuskan bahwa setiap departemen harus menyusun organisasi dengan pedoman <span class="text-emerald-400">Keputusan Presiden</span>.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Periode 3 -->
                            <div class="relative p-5 bg-slate-900/30 border border-slate-700/30 rounded-xl hover:border-purple-500/30 transition-colors">
                                <div class="flex items-start gap-4">
                                    <div class="w-10 h-10 bg-purple-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <span class="font-mono text-sm font-bold text-purple-400">1975</span>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-mono text-sm text-slate-300 leading-relaxed">
                                            <span class="text-purple-400 font-bold">Periode 1975 - Juni 2003</span>. Atas dasar <span class="text-purple-400">Kepres No. 44 Tahun 1974</span> dan <span class="text-purple-400">No. 45 Tahun 1974</span>, ditetapkan <span class="text-purple-400">Keputusan Menteri Agama No. 18 Tahun 1975</span> (16 April) tentang susunan organisasi dan tata kerja.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Perubahan Nama -->
                            <div class="relative p-5 bg-slate-900/30 border border-slate-700/30 rounded-xl hover:border-amber-500/30 transition-colors">
                                <div class="flex items-start gap-4">
                                    <div class="w-10 h-10 bg-amber-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-mono text-sm text-slate-300 leading-relaxed">
                                            <span class="text-amber-400 font-bold">Perubahan Nama:</span>
                                        </p>
                                        <ul class="mt-2 space-y-2 font-mono text-sm text-slate-400">
                                            <li class="flex items-start gap-2">
                                                <span class="text-amber-400">1949</span>
                                                <span>Jawatan Agama → Kantor Urusan Agama Kabupaten Tanah Datar</span>
                                            </li>
                                            <li class="flex items-start gap-2">
                                                <span class="text-amber-400">1968</span>
                                                <span>Kantor Urusan Agama → Dinas Urusan Agama</span>
                                            </li>
                                            <li class="flex items-start gap-2">
                                                <span class="text-amber-400">1972</span>
                                                <span>Dinas Urusan Agama → Inspeksi Urusan Agama</span>
                                            </li>
                                            <li class="flex items-start gap-2">
                                                <span class="text-amber-400">2010</span>
                                                <span>Departemen Agama → <span class="text-amber-400 font-bold">Kementerian Agama</span></span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Kepada Kepala Kantor Section -->
                    <div>
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 bg-rose-500/20 border border-rose-500/30 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-mono text-lg font-bold text-rose-400">Kepala Kantor</h3>
                                <p class="font-mono text-xs text-slate-500">Pimpinan yang pernah memimpin Kemenag Tanah Datar</p>
                            </div>
                        </div>

                        <div class="bg-slate-900/30 border border-slate-700/30 rounded-2xl p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @php
                                $kepalas = [
                                    ['nama' => 'Drs. H. Muslim Gani', 'periode' => 'Awal'],
                                    ['nama' => 'Amiruddin, S. Ba.', 'periode' => ''],
                                    ['nama' => 'Drs. Idham Lukman', 'periode' => ''],
                                    ['nama' => 'Drs. Aisar Amir', 'periode' => ''],
                                    ['nama' => 'Drs. Darnis Burhan', 'periode' => ''],
                                    ['nama' => 'Drs. Suherman', 'periode' => ''],
                                    ['nama' => 'Drs. H. Asmal', 'periode' => ''],
                                    ['nama' => 'Drs. Malikia, MA', 'periode' => 'Perubahan Nama ke Kemenag'],
                                    ['nama' => 'Drs. H. Ramadhan, M.Si', 'periode' => ''],
                                    ['nama' => 'Drs. H. Syahrul, MM', 'periode' => ''],
                                    ['nama' => 'H. Amril, S.Ag, MM', 'periode' => '2024-2026'],
                                    ['nama' => 'H. Hendri Pani Dias, S.Ag, M.A', 'periode' => 'Sekarang'],
                                ];
                                @endphp

                                @foreach($kepalas as $kepala)
                                <div class="flex items-center gap-3 p-3 bg-slate-900/50 border border-slate-700/30 rounded-xl hover:border-rose-500/30 transition-colors">
                                    <div class="w-10 h-10 bg-gradient-to-br from-rose-500/30 to-rose-600/30 rounded-full flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-mono text-sm text-white font-medium truncate">{{ $kepala['nama'] }}</p>
                                        @if(!empty($kepala['periode']))
                                        <p class="font-mono text-[10px] text-rose-400/60 uppercase tracking-wider">{{ $kepala['periode'] }}</p>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                </div>
            </section>

            <!-- Stats Section -->
            <section class="relative py-16 bg-gradient-to-b from-slate-900 to-slate-950">
                <div class="relative z-10 max-w-5xl mx-auto px-4 sm:px-6">
                    <div class="text-center mb-12">
                        <h2 class="font-mono text-2xl font-bold uppercase tracking-wider text-white mb-2">Perjalanan Kami</h2>
                        <p class="font-mono text-sm text-slate-400">Melayani masyarakat Tanah Datar sejak tahun 1960</p>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                        <div class="text-center">
                            <div class="inline-flex items-center justify-center w-20 h-20 bg-emerald-500/10 border border-emerald-500/30 rounded-full mb-4">
                                <span class="font-mono text-2xl font-black text-emerald-400">64+</span>
                            </div>
                            <p class="font-mono text-xs text-emerald-400/60 uppercase tracking-wider">Tahun Melayani</p>
                        </div>
                        <div class="text-center">
                            <div class="inline-flex items-center justify-center w-20 h-20 bg-cyan-500/10 border border-cyan-500/30 rounded-full mb-4">
                                <span class="font-mono text-2xl font-black text-cyan-400">8+</span>
                            </div>
                            <p class="font-mono text-xs text-cyan-400/60 uppercase tracking-wider">KUA Kecamatan</p>
                        </div>
                        <div class="text-center">
                            <div class="inline-flex items-center justify-center w-20 h-20 bg-amber-500/10 border border-amber-500/30 rounded-full mb-4">
                                <span class="font-mono text-2xl font-black text-amber-400">100+</span>
                            </div>
                            <p class="font-mono text-xs text-amber-400/60 uppercase tracking-wider">Madrasah</p>
                        </div>
                        <div class="text-center">
                            <div class="inline-flex items-center justify-center w-20 h-20 bg-purple-500/10 border border-purple-500/30 rounded-full mb-4">
                                <span class="font-mono text-2xl font-black text-purple-400">50+</span>
                            </div>
                            <p class="font-mono text-xs text-purple-400/60 uppercase tracking-wider">Tenaga pendidik</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Quote Section -->
            <section class="relative py-20 bg-gradient-to-b from-slate-950 to-slate-900">
                <div class="relative z-10 max-w-4xl mx-auto px-4 sm:px-6 text-center">
                    <div class="relative">
                        <svg class="w-20 h-20 text-cyan-500/20 mx-auto mb-8" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/>
                        </svg>
                        <p class="font-mono text-xl md:text-2xl text-slate-300 leading-relaxed mb-8 italic">
                            "Melayani dengan hati, bertransformasi dengan teknologi, untuk kemajuan keagamaan Kabupaten Tanah Datar."
                        </p>
                        <div class="flex items-center justify-center gap-4">
                            <div class="h-px w-16 bg-gradient-to-r from-transparent to-cyan-500"></div>
                            <span class="font-mono text-sm text-cyan-400 uppercase tracking-widest">Kankemenag Tanah Datar</span>
                            <div class="h-px w-16 bg-gradient-to-l from-transparent to-cyan-500"></div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Navigation Cards -->
            <section class="relative py-16 bg-gradient-to-b from-slate-900 to-slate-950">
                <div class="relative z-10 max-w-4xl mx-auto px-4 sm:px-6">
                    <div class="grid md:grid-cols-2 gap-6">
                        <a href="{{ route('profil-kantor') }}" class="group relative">
                            <div class="absolute inset-0 bg-gradient-to-r from-cyan-500/10 to-transparent rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            <div class="relative p-6 bg-slate-900/50 border border-slate-700/50 rounded-2xl hover:border-cyan-500/50 transition-colors">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 bg-cyan-500/20 border border-cyan-500/30 rounded-xl flex items-center justify-center">
                                        <svg class="w-6 h-6 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/>
                                            <circle cx="12" cy="7" r="4"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="font-mono text-lg font-bold text-white mb-1">Profil Kantor</h3>
                                        <p class="font-mono text-xs text-slate-400">Visi, Misi dan Tugas Pokok</p>
                                    </div>
                                    <svg class="w-5 h-5 text-slate-600 group-hover:text-cyan-400 transform group-hover:translate-x-1 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                    </svg>
                                </div>
                            </div>
                        </a>

                        <a href="{{ route('struktur-organisasi') }}" class="group relative">
                            <div class="absolute inset-0 bg-gradient-to-r from-purple-500/10 to-transparent rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            <div class="relative p-6 bg-slate-900/50 border border-slate-700/50 rounded-2xl hover:border-purple-500/50 transition-colors">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 bg-purple-500/20 border border-purple-500/30 rounded-xl flex items-center justify-center">
                                        <svg class="w-6 h-6 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <rect x="9" y="2" width="6" height="6" rx="1"/>
                                            <path d="M4 22v-4a2 2 0 012-2h12a2 2 0 012 2v4"/>
                                            <path d="M12 12v4"/>
                                            <path d="M6 12v4a2 2 0 002 2h8a2 2 0 002-2v-4"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="font-mono text-lg font-bold text-white mb-1">Struktur Organisasi</h3>
                                        <p class="font-mono text-xs text-slate-400">Susunan Organisasi</p>
                                    </div>
                                    <svg class="w-5 h-5 text-slate-600 group-hover:text-purple-400 transform group-hover:translate-x-1 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                    </svg>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </section>

            <!-- Footer -->
            <footer class="relative py-8 border-t border-slate-800">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 text-center">
                    <div class="flex items-center justify-center gap-3 mb-4">
                        <img src="{{ asset('favicon.webp') }}" alt="SILATAR" class="h-8 w-8 rounded-lg">
                        <span class="font-mono text-sm font-bold uppercase tracking-wider text-emerald-400">SILATAR</span>
                    </div>
                    <p class="font-mono text-xs text-slate-500">Kantor Kementerian Agama Kabupaten Tanah Datar</p>
                </div>
            </footer>
        </main>
    </div>
</x-layouts.app>
