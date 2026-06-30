<x-layouts.app title="Laporan Madrasah - SILATAR">
    @php
        $formData = $formData ?? [
            'nama' => '',
            'nsm' => '',
            'npsm' => '',
            'status_lembaga' => '',
            'is_nama_readonly' => false,
            'is_status_readonly' => false,
            'jalan' => '',
            'jorong' => '',
            'nagari' => '',
            'kecamatan' => '',
            'koordinat' => '',
            'telepon' => '',
            'email' => '',
            'website' => '',
            'waktu_belajar' => '',
            'visi' => '',
            'sk_pendirian' => '',
            'tanggal_sk' => '',
            'komite_lembaga' => '',
            'akreditasi' => '',
            'tanggal_akreditasi' => '',
            'status_kkm' => '',
            'jarak_pusat_provinsi' => '',
            'jarak_pusat_kabupaten' => '',
            'jarak_kecamatan' => '',
            'jarak_kanwil_kemenag' => '',
            'jarak_kemenag_kab' => '',
            'jarak_kua' => '',
            'jarak_ra_terdekat' => '',
            'jarak_mi_terdekat' => '',
            'jarak_mts_terdekat' => '',
            'jarak_ma_terdekat' => '',
            'jarak_pontren_terdekat' => '',
            'jarak_tk_terdekat' => '',
            'jarak_sd_terdekat' => '',
            'jarak_smp_terdekat' => '',
            'jarak_sma_terdekat' => '',
        ];
        $statusLembaga = ['NEGERI', 'SWASTA'];
        $statusKkm = ['TERAKREDITASI', 'BELUM TERAKREDITASI'];
    @endphp

    <main class="min-h-screen py-8 px-4 md:px-8 relative" x-data="{ activeTab: 'profil' }">
        <!-- Animated Background Orbs -->
        <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
            <div class="absolute -top-40 -right-40 w-96 h-96 bg-gradient-to-br from-emerald-500/20 to-cyan-500/20 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-gradient-to-tr from-violet-500/20 to-pink-500/20 rounded-full blur-3xl animate-pulse" style="animation-delay: 1s;"></div>
        </div>

        <div class="max-w-6xl mx-auto relative z-10">
            <!-- Hero Header -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center gap-3 mb-6">
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-emerald-500 to-cyan-500 flex items-center justify-center shadow-lg shadow-emerald-500/30">
                        <svg class="w-9 h-9 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21.75V19.5C12 18.12 13.12 17 14.5 17H16.5C17.88 17 19 18.12 19 19.5V21.75M12 21.75V3M12 3H7.5C6.12 3 5 4.12 5 5.5V8.25M12 3H16.5C17.88 3 19 4.12 19 5.5V8.25M5 8.25V12.75C5 14.13 6.12 15.25 7.5 15.25H9M9 15.25C10.38 15.25 11.5 14.13 11.5 12.75V8.25M9 15.25H16.5C17.88 15.25 19 14.13 19 12.75V5.5C19 4.12 17.88 3 16.5 3H12M5 8.25H7.5M7.5 8.25C6.12 8.25 5 9.37 5 10.75V12.75"/>
                        </svg>
                    </div>
                    <span class="px-4 py-1.5 rounded-full border border-emerald-500/30 bg-emerald-500/10 font-mono text-xs font-semibold uppercase tracking-widest text-emerald-400">
                        Formulir
                    </span>
                </div>
                <h1 class="font-mono text-3xl md:text-4xl font-black uppercase tracking-wider text-white mb-3">
                    Laporan <span class="bg-gradient-to-r from-emerald-400 to-cyan-400 bg-clip-text text-transparent">Madrasah</span>
                </h1>
                <p class="text-slate-400 max-w-xl mx-auto">Lengkapi data profil madrasah dengan akurat untuk keperluan pelaporan dan evaluasi kinerja.</p>
            </div>

            <!-- Tab Navigation -->
            <div class="flex items-center justify-center gap-2 mb-8">
                <button
                    type="button"
                    @click="activeTab = 'profil'"
                    :class="activeTab === 'profil'
                        ? 'bg-gradient-to-r from-emerald-600 to-cyan-600 text-white border-emerald-500/50 shadow-lg shadow-emerald-500/20'
                        : 'bg-slate-800/80 text-slate-400 border-slate-700 hover:bg-slate-700/80 hover:text-white'"
                    class="flex items-center gap-2 px-6 py-3 rounded-xl border font-mono text-sm font-semibold uppercase tracking-wider transition-all duration-300"
                >
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    Profil Madrasah
                </button>
                <button
                    type="button"
                    @click="window.location.href = '{{ route('madrasah.pegawai') }}'"
                    :class="'bg-slate-800/80 text-slate-400 border-slate-700 hover:bg-slate-700/80 hover:text-white'"
                    class="flex items-center gap-2 px-6 py-3 rounded-xl border font-mono text-sm font-semibold uppercase tracking-wider transition-all duration-300"
                >
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    Pegawai Madrasah
                </button>
                <button
                    type="button"
                    @click="window.location.href = '{{ route('madrasah.guru') }}'"
                    class="flex items-center gap-2 px-6 py-3 rounded-xl border bg-slate-800/80 text-slate-400 border-slate-700 hover:bg-slate-700/80 hover:text-white font-mono text-sm font-semibold uppercase tracking-wider transition-all duration-300"
                >
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    Guru Madrasah
                </button>
                <button
                    type="button"
                    @click="window.location.href = '{{ route('madrasah.laporan-semester') }}'"
                    class="flex items-center gap-2 px-6 py-3 rounded-xl border bg-slate-800/80 text-slate-400 border-slate-700 hover:bg-slate-700/80 hover:text-white font-mono text-sm font-semibold uppercase tracking-wider transition-all duration-300"
                >
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Laporan Semester
                </button>
            </div>

            <form action="#" method="POST" class="space-y-8" x-show="activeTab === 'profil'" x-transition>
                @csrf

                <!-- Section 1: Identitas Madrasah -->
                <div class="bg-gradient-to-br from-slate-900/90 to-slate-900/50 backdrop-blur-xl rounded-3xl border border-emerald-500/20 shadow-2xl shadow-emerald-500/5 overflow-hidden">
                    <div class="bg-gradient-to-r from-emerald-600/20 via-cyan-600/20 to-emerald-600/20 px-6 py-5 border-b border-emerald-500/20">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-500 to-cyan-500 flex items-center justify-center shadow-lg">
                                <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            <div>
                                <h2 class="font-mono text-lg font-bold text-white uppercase tracking-wider">Identitas Madrasah</h2>
                                <p class="text-xs text-emerald-400/80">Informasi dasar mengenai lembaga pendidikan</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <!-- Nama Madrasah -->
                            <div class="lg:col-span-3">
                                <label class="flex items-center gap-2 mb-3">
                                    <svg class="w-4 h-4 text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                    </svg>
                                    <span class="font-mono text-xs font-semibold uppercase tracking-wider text-emerald-400">Nama Madrasah</span>
                                    @if($formData['is_nama_readonly'])
                                        <span class="ml-2 inline-flex items-center gap-1 rounded-full border border-slate-500/30 bg-slate-500/10 px-2 py-0.5 font-mono text-[10px] text-slate-400">
                                            <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                            </svg>
                                            Auto-fill
                                        </span>
                                    @endif
                                </label>
                                <input type="text" name="nama" value="{{ old('nama', $formData['nama']) }}"
                                    @if($formData['is_nama_readonly']) readonly disabled @endif
                                    class="w-full rounded-xl border border-emerald-500/30 bg-slate-800/80 px-4 py-3.5 text-white placeholder-slate-500 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all font-mono {{ $formData['is_nama_readonly'] ? 'opacity-70 cursor-not-allowed' : '' }}"
                                    placeholder="Contoh: Madrasah Ibtidaiyah Negeri 1 Tanjung">
                            </div>

                            <!-- NSM -->
                            <div>
                                <label class="flex items-center gap-2 mb-3">
                                    <svg class="w-4 h-4 text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                                    </svg>
                                    <span class="font-mono text-xs font-semibold uppercase tracking-wider text-emerald-400">NSM</span>
                                </label>
                                <input type="text" name="nsm" value="{{ old('nsm', $formData['nsm']) }}"
                                    class="w-full rounded-xl border border-emerald-500/30 bg-slate-800/80 px-4 py-3.5 text-white placeholder-slate-500 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all font-mono"
                                    placeholder="Nomor Statistik Madrasah">
                            </div>

                            <!-- NPSM -->
                            <div>
                                <label class="flex items-center gap-2 mb-3">
                                    <svg class="w-4 h-4 text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <span class="font-mono text-xs font-semibold uppercase tracking-wider text-emerald-400">NPSM</span>
                                </label>
                                <input type="text" name="npsm" value="{{ old('npsm', $formData['npsm']) }}"
                                    class="w-full rounded-xl border border-emerald-500/30 bg-slate-800/80 px-4 py-3.5 text-white placeholder-slate-500 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all font-mono"
                                    placeholder="Nomor Pokok Sekolah Madrasah">
                            </div>

                            <!-- Status Lembaga -->
                            <div>
                                <label class="flex items-center gap-2 mb-3">
                                    <svg class="w-4 h-4 text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span class="font-mono text-xs font-semibold uppercase tracking-wider text-emerald-400">Status Lembaga</span>
                                    @if($formData['is_status_readonly'])
                                        <span class="ml-2 inline-flex items-center gap-1 rounded-full border border-slate-500/30 bg-slate-500/10 px-2 py-0.5 font-mono text-[10px] text-slate-400">
                                            <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                            </svg>
                                            Auto-fill
                                        </span>
                                    @endif
                                </label>
                                @if($formData['is_status_readonly'])
                                    <input type="text" value="{{ $formData['status_lembaga'] }}" readonly disabled
                                        class="w-full rounded-xl border border-emerald-500/30 bg-emerald-500/10 px-4 py-3.5 text-emerald-400 font-mono opacity-70 cursor-not-allowed">
                                    <input type="hidden" name="status_lembaga" value="{{ $formData['status_lembaga'] }}">
                                @else
                                    <select name="status_lembaga" class="w-full rounded-xl border border-emerald-500/30 bg-slate-800/80 px-4 py-3.5 text-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all font-mono">
                                        <option value="">Pilih Status</option>
                                        @foreach($statusLembaga as $status)
                                            <option value="{{ $status }}" {{ old('status_lembaga', $formData['status_lembaga']) == $status ? 'selected' : '' }}>{{ $status }}</option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>

                            <!-- Waktu Belajar -->
                            <div class="lg:col-span-2">
                                <label class="flex items-center gap-2 mb-3">
                                    <svg class="w-4 h-4 text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span class="font-mono text-xs font-semibold uppercase tracking-wider text-emerald-400">Waktu Belajar</span>
                                </label>
                                <input type="text" name="waktu_belajar" value="{{ old('waktu_belajar', $formData['waktu_belajar']) }}"
                                    class="w-full rounded-xl border border-emerald-500/30 bg-slate-800/80 px-4 py-3.5 text-white placeholder-slate-500 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all font-mono"
                                    placeholder="Contoh: Pagi (07.00 - 13.00 WIB)">
                            </div>

                            <!-- Akreditasi -->
                            <div>
                                <label class="flex items-center gap-2 mb-3">
                                    <svg class="w-4 h-4 text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                    </svg>
                                    <span class="font-mono text-xs font-semibold uppercase tracking-wider text-emerald-400">Akreditasi</span>
                                </label>
                                <input type="text" name="akreditasi" value="{{ old('akreditasi', $formData['akreditasi']) }}"
                                    class="w-full rounded-xl border border-emerald-500/30 bg-slate-800/80 px-4 py-3.5 text-white placeholder-slate-500 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all font-mono"
                                    placeholder="Contoh: A">
                            </div>

                            <!-- Tanggal Akreditasi -->
                            <div>
                                <label class="flex items-center gap-2 mb-3">
                                    <svg class="w-4 h-4 text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <span class="font-mono text-xs font-semibold uppercase tracking-wider text-emerald-400">Tanggal Akreditasi</span>
                                </label>
                                <input type="date" name="tanggal_akreditasi" value="{{ old('tanggal_akreditasi', $formData['tanggal_akreditasi']) }}"
                                    class="w-full rounded-xl border border-emerald-500/30 bg-slate-800/80 px-4 py-3.5 text-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all font-mono">
                            </div>

                            <!-- Status KKM -->
                            <div>
                                <label class="flex items-center gap-2 mb-3">
                                    <svg class="w-4 h-4 text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                                    </svg>
                                    <span class="font-mono text-xs font-semibold uppercase tracking-wider text-emerald-400">Status KKM</span>
                                </label>
                                <select name="status_kkm" class="w-full rounded-xl border border-emerald-500/30 bg-slate-800/80 px-4 py-3.5 text-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all font-mono">
                                    <option value="">Pilih Status</option>
                                    @foreach($statusKkm as $status)
                                        <option value="{{ $status }}" {{ old('status_kkm', $formData['status_kkm']) == $status ? 'selected' : '' }}>{{ $status }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Alamat & Lokasi -->
                <div class="bg-gradient-to-br from-slate-900/90 to-slate-900/50 backdrop-blur-xl rounded-3xl border border-cyan-500/20 shadow-2xl shadow-cyan-500/5 overflow-hidden">
                    <div class="bg-gradient-to-r from-cyan-600/20 via-emerald-600/20 to-cyan-600/20 px-6 py-5 border-b border-cyan-500/20">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-cyan-500 to-emerald-500 flex items-center justify-center shadow-lg">
                                <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h2 class="font-mono text-lg font-bold text-white uppercase tracking-wider">Alamat & Lokasi</h2>
                                <p class="text-xs text-cyan-400/80">Informasi lokasi dan koordinat lembaga</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <!-- Jalan -->
                            <div class="lg:col-span-2">
                                <label class="flex items-center gap-2 mb-3">
                                    <svg class="w-4 h-4 text-cyan-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                                    </svg>
                                    <span class="font-mono text-xs font-semibold uppercase tracking-wider text-cyan-400">Jalan</span>
                                </label>
                                <input type="text" name="jalan" value="{{ old('jalan', $formData['jalan']) }}"
                                    class="w-full rounded-xl border border-cyan-500/30 bg-slate-800/80 px-4 py-3.5 text-white placeholder-slate-500 focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/20 transition-all font-mono"
                                    placeholder="Nama jalan lengkap">
                            </div>

                            <!-- Jorong -->
                            <div>
                                <label class="flex items-center gap-2 mb-3">
                                    <svg class="w-4 h-4 text-cyan-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                    </svg>
                                    <span class="font-mono text-xs font-semibold uppercase tracking-wider text-cyan-400">Jorong / Kampung</span>
                                </label>
                                <input type="text" name="jorong" value="{{ old('jorong', $formData['jorong']) }}"
                                    class="w-full rounded-xl border border-cyan-500/30 bg-slate-800/80 px-4 py-3.5 text-white placeholder-slate-500 focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/20 transition-all font-mono"
                                    placeholder="Nama jorong atau kampung">
                            </div>

                            <!-- Nagari -->
                            <div>
                                <label class="flex items-center gap-2 mb-3">
                                    <svg class="w-4 h-4 text-cyan-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                    </svg>
                                    <span class="font-mono text-xs font-semibold uppercase tracking-wider text-cyan-400">Nagari / Desa</span>
                                </label>
                                <input type="text" name="nagari" value="{{ old('nagari', $formData['nagari']) }}"
                                    class="w-full rounded-xl border border-cyan-500/30 bg-slate-800/80 px-4 py-3.5 text-white placeholder-slate-500 focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/20 transition-all font-mono"
                                    placeholder="Nama nagari atau desa">
                            </div>

                            <!-- Kecamatan -->
                            <div>
                                <label class="flex items-center gap-2 mb-3">
                                    <svg class="w-4 h-4 text-cyan-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                    <span class="font-mono text-xs font-semibold uppercase tracking-wider text-cyan-400">Kecamatan</span>
                                </label>
                                <input type="text" name="kecamatan" value="{{ old('kecamatan', $formData['kecamatan']) }}"
                                    class="w-full rounded-xl border border-cyan-500/30 bg-slate-800/80 px-4 py-3.5 text-white placeholder-slate-500 focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/20 transition-all font-mono"
                                    placeholder="Nama kecamatan">
                            </div>

                            <!-- Koordinat -->
                            <div class="lg:col-span-2">
                                <label class="flex items-center gap-2 mb-3">
                                    <svg class="w-4 h-4 text-cyan-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                                    </svg>
                                    <span class="font-mono text-xs font-semibold uppercase tracking-wider text-cyan-400">Koordinat GPS</span>
                                </label>
                                <input type="text" name="koordinat" value="{{ old('koordinat', $formData['koordinat']) }}"
                                    class="w-full rounded-xl border border-cyan-500/30 bg-slate-800/80 px-4 py-3.5 text-white placeholder-slate-500 focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/20 transition-all font-mono"
                                    placeholder="Contoh: -0.5071, 100.4478">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 3: Kontak & Website -->
                <div class="bg-gradient-to-br from-slate-900/90 to-slate-900/50 backdrop-blur-xl rounded-3xl border border-violet-500/20 shadow-2xl shadow-violet-500/5 overflow-hidden">
                    <div class="bg-gradient-to-r from-violet-600/20 via-pink-600/20 to-violet-600/20 px-6 py-5 border-b border-violet-500/20">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-violet-500 to-pink-500 flex items-center justify-center shadow-lg">
                                <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <h2 class="font-mono text-lg font-bold text-white uppercase tracking-wider">Kontak & Website</h2>
                                <p class="text-xs text-violet-400/80">Informasi kontak dan media digital</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <!-- Telepon -->
                            <div>
                                <label class="flex items-center gap-2 mb-3">
                                    <svg class="w-4 h-4 text-violet-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                    <span class="font-mono text-xs font-semibold uppercase tracking-wider text-violet-400">Telepon / HP</span>
                                </label>
                                <input type="tel" name="telepon" value="{{ old('telepon', $formData['telepon']) }}"
                                    class="w-full rounded-xl border border-violet-500/30 bg-slate-800/80 px-4 py-3.5 text-white placeholder-slate-500 focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 transition-all font-mono"
                                    placeholder="Contoh: 0812-3456-7890">
                            </div>

                            <!-- Email -->
                            <div>
                                <label class="flex items-center gap-2 mb-3">
                                    <svg class="w-4 h-4 text-violet-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    <span class="font-mono text-xs font-semibold uppercase tracking-wider text-violet-400">Email</span>
                                </label>
                                <input type="email" name="email" value="{{ old('email', $formData['email']) }}"
                                    class="w-full rounded-xl border border-violet-500/30 bg-slate-800/80 px-4 py-3.5 text-white placeholder-slate-500 focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 transition-all font-mono"
                                    placeholder="contoh@email.com">
                            </div>

                            <!-- Website -->
                            <div>
                                <label class="flex items-center gap-2 mb-3">
                                    <svg class="w-4 h-4 text-violet-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                                    </svg>
                                    <span class="font-mono text-xs font-semibold uppercase tracking-wider text-violet-400">Website</span>
                                </label>
                                <input type="url" name="website" value="{{ old('website', $formData['website']) }}"
                                    class="w-full rounded-xl border border-violet-500/30 bg-slate-800/80 px-4 py-3.5 text-white placeholder-slate-500 focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 transition-all font-mono"
                                    placeholder="https://www.madrasah.sch.id">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 4: SK Pendirian -->
                <div class="bg-gradient-to-br from-slate-900/90 to-slate-900/50 backdrop-blur-xl rounded-3xl border border-amber-500/20 shadow-2xl shadow-amber-500/5 overflow-hidden">
                    <div class="bg-gradient-to-r from-amber-600/20 via-orange-600/20 to-amber-600/20 px-6 py-5 border-b border-amber-500/20">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-500 to-orange-500 flex items-center justify-center shadow-lg">
                                <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div>
                                <h2 class="font-mono text-lg font-bold text-white uppercase tracking-wider">SK Pendirian</h2>
                                <p class="text-xs text-amber-400/80">Informasi legalitas pendirian lembaga</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- SK Pendirian -->
                            <div>
                                <label class="flex items-center gap-2 mb-3">
                                    <svg class="w-4 h-4 text-amber-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <span class="font-mono text-xs font-semibold uppercase tracking-wider text-amber-400">Nomor SK Pendirian</span>
                                </label>
                                <input type="text" name="sk_pendirian" value="{{ old('sk_pendirian', $formData['sk_pendirian']) }}"
                                    class="w-full rounded-xl border border-amber-500/30 bg-slate-800/80 px-4 py-3.5 text-white placeholder-slate-500 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 transition-all font-mono"
                                    placeholder="Contoh: SK.1234/PP.03.03/2008">
                            </div>

                            <!-- Tanggal SK -->
                            <div>
                                <label class="flex items-center gap-2 mb-3">
                                    <svg class="w-4 h-4 text-amber-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <span class="font-mono text-xs font-semibold uppercase tracking-wider text-amber-400">Tanggal SK</span>
                                </label>
                                <input type="date" name="tanggal_sk" value="{{ old('tanggal_sk', $formData['tanggal_sk']) }}"
                                    class="w-full rounded-xl border border-amber-500/30 bg-slate-800/80 px-4 py-3.5 text-white focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 transition-all font-mono">
                            </div>
                        </div>

                        <!-- Komite Lembaga -->
                        <div class="mt-6">
                            <label class="flex items-center gap-2 mb-3">
                                <svg class="w-4 h-4 text-amber-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span class="font-mono text-xs font-semibold uppercase tracking-wider text-amber-400">Nama Komite Lembaga</span>
                            </label>
                            <input type="text" name="komite_lembaga" value="{{ old('komite_lembaga', $formData['komite_lembaga']) }}"
                                class="w-full rounded-xl border border-amber-500/30 bg-slate-800/80 px-4 py-3.5 text-white placeholder-slate-500 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 transition-all font-mono"
                                placeholder="Nama Ketua Komite Madrasah">
                        </div>
                    </div>
                </div>

                <!-- Section 5: Visi Misi -->
                <div class="bg-gradient-to-br from-slate-900/90 to-slate-900/50 backdrop-blur-xl rounded-3xl border border-rose-500/20 shadow-2xl shadow-rose-500/5 overflow-hidden">
                    <div class="bg-gradient-to-r from-rose-600/20 via-pink-600/20 to-rose-600/20 px-6 py-5 border-b border-rose-500/20">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-rose-500 to-pink-500 flex items-center justify-center shadow-lg">
                                <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </div>
                            <div>
                                <h2 class="font-mono text-lg font-bold text-white uppercase tracking-wider">Visi Madrasah</h2>
                                <p class="text-xs text-rose-400/80">Visi dan arah lembaga pendidikan</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div>
                            <label class="flex items-center gap-2 mb-3">
                                <svg class="w-4 h-4 text-rose-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                </svg>
                                <span class="font-mono text-xs font-semibold uppercase tracking-wider text-rose-400">Visi</span>
                            </label>
                            <textarea name="visi" rows="4"
                                class="w-full rounded-xl border border-rose-500/30 bg-slate-800/80 px-4 py-3.5 text-white placeholder-slate-500 focus:border-rose-500 focus:ring-2 focus:ring-rose-500/20 transition-all font-mono resize-none"
                                placeholder="Tuliskan visi madrasah secara lengkap...">{{ old('visi', $formData['visi']) }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Section 6: Jarak ke Pusat -->
                <div class="bg-gradient-to-br from-slate-900/90 to-slate-900/50 backdrop-blur-xl rounded-3xl border border-sky-500/20 shadow-2xl shadow-sky-500/5 overflow-hidden">
                    <div class="bg-gradient-to-r from-sky-600/20 via-indigo-600/20 to-sky-600/20 px-6 py-5 border-b border-sky-500/20">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-sky-500 to-indigo-500 flex items-center justify-center shadow-lg">
                                <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                </svg>
                            </div>
                            <div>
                                <h2 class="font-mono text-lg font-bold text-white uppercase tracking-wider">Jarak ke Pusat</h2>
                                <p class="text-xs text-sky-400/80">Jarak ke pusat pemerintahan (dalam kilometer)</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <!-- Jarak Pusat Provinsi -->
                            <div>
                                <label class="flex items-center gap-2 mb-3">
                                    <svg class="w-4 h-4 text-sky-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <span class="font-mono text-xs font-semibold uppercase tracking-wider text-sky-400">Jarak Pusat Provinsi</span>
                                </label>
                                <div class="relative">
                                    <input type="number" step="0.01" name="jarak_pusat_provinsi" value="{{ old('jarak_pusat_provinsi', $formData['jarak_pusat_provinsi']) }}"
                                        class="w-full rounded-xl border border-sky-500/30 bg-slate-800/80 px-4 py-3.5 pr-12 text-white placeholder-slate-500 focus:border-sky-500 focus:ring-2 focus:ring-sky-500/20 transition-all font-mono"
                                        placeholder="0.00">
                                    <span class="absolute right-4 top-1/2 -translate-y-1/2 font-mono text-xs text-slate-400">km</span>
                                </div>
                            </div>

                            <!-- Jarak Pusat Kabupaten -->
                            <div>
                                <label class="flex items-center gap-2 mb-3">
                                    <svg class="w-4 h-4 text-sky-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <span class="font-mono text-xs font-semibold uppercase tracking-wider text-sky-400">Jarak Pusat Kabupaten</span>
                                </label>
                                <div class="relative">
                                    <input type="number" step="0.01" name="jarak_pusat_kabupaten" value="{{ old('jarak_pusat_kabupaten', $formData['jarak_pusat_kabupaten']) }}"
                                        class="w-full rounded-xl border border-sky-500/30 bg-slate-800/80 px-4 py-3.5 pr-12 text-white placeholder-slate-500 focus:border-sky-500 focus:ring-2 focus:ring-sky-500/20 transition-all font-mono"
                                        placeholder="0.00">
                                    <span class="absolute right-4 top-1/2 -translate-y-1/2 font-mono text-xs text-slate-400">km</span>
                                </div>
                            </div>

                            <!-- Jarak Kecamatan -->
                            <div>
                                <label class="flex items-center gap-2 mb-3">
                                    <svg class="w-4 h-4 text-sky-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <span class="font-mono text-xs font-semibold uppercase tracking-wider text-sky-400">Jarak Kecamatan</span>
                                </label>
                                <div class="relative">
                                    <input type="number" step="0.01" name="jarak_kecamatan" value="{{ old('jarak_kecamatan', $formData['jarak_kecamatan']) }}"
                                        class="w-full rounded-xl border border-sky-500/30 bg-slate-800/80 px-4 py-3.5 pr-12 text-white placeholder-slate-500 focus:border-sky-500 focus:ring-2 focus:ring-sky-500/20 transition-all font-mono"
                                        placeholder="0.00">
                                    <span class="absolute right-4 top-1/2 -translate-y-1/2 font-mono text-xs text-slate-400">km</span>
                                </div>
                            </div>

                            <!-- Jarak Kanwil Kemenag -->
                            <div>
                                <label class="flex items-center gap-2 mb-3">
                                    <svg class="w-4 h-4 text-sky-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                    <span class="font-mono text-xs font-semibold uppercase tracking-wider text-sky-400">Jarak Kanwil Kemenag</span>
                                </label>
                                <div class="relative">
                                    <input type="number" step="0.01" name="jarak_kanwil_kemenag" value="{{ old('jarak_kanwil_kemenag', $formData['jarak_kanwil_kemenag']) }}"
                                        class="w-full rounded-xl border border-sky-500/30 bg-slate-800/80 px-4 py-3.5 pr-12 text-white placeholder-slate-500 focus:border-sky-500 focus:ring-2 focus:ring-sky-500/20 transition-all font-mono"
                                        placeholder="0.00">
                                    <span class="absolute right-4 top-1/2 -translate-y-1/2 font-mono text-xs text-slate-400">km</span>
                                </div>
                            </div>

                            <!-- Jarak Kemenag Kab -->
                            <div>
                                <label class="flex items-center gap-2 mb-3">
                                    <svg class="w-4 h-4 text-sky-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                    <span class="font-mono text-xs font-semibold uppercase tracking-wider text-sky-400">Jarak Kemenag Kab/Kota</span>
                                </label>
                                <div class="relative">
                                    <input type="number" step="0.01" name="jarak_kemenag_kab" value="{{ old('jarak_kemenag_kab', $formData['jarak_kemenag_kab']) }}"
                                        class="w-full rounded-xl border border-sky-500/30 bg-slate-800/80 px-4 py-3.5 pr-12 text-white placeholder-slate-500 focus:border-sky-500 focus:ring-2 focus:ring-sky-500/20 transition-all font-mono"
                                        placeholder="0.00">
                                    <span class="absolute right-4 top-1/2 -translate-y-1/2 font-mono text-xs text-slate-400">km</span>
                                </div>
                            </div>

                            <!-- Jarak KUA -->
                            <div>
                                <label class="flex items-center gap-2 mb-3">
                                    <svg class="w-4 h-4 text-sky-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                    <span class="font-mono text-xs font-semibold uppercase tracking-wider text-sky-400">Jarak KUA</span>
                                </label>
                                <div class="relative">
                                    <input type="number" step="0.01" name="jarak_kua" value="{{ old('jarak_kua', $formData['jarak_kua']) }}"
                                        class="w-full rounded-xl border border-sky-500/30 bg-slate-800/80 px-4 py-3.5 pr-12 text-white placeholder-slate-500 focus:border-sky-500 focus:ring-2 focus:ring-sky-500/20 transition-all font-mono"
                                        placeholder="0.00">
                                    <span class="absolute right-4 top-1/2 -translate-y-1/2 font-mono text-xs text-slate-400">km</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 7: Jarak ke Lembaga Terdekat -->
                <div class="bg-gradient-to-br from-slate-900/90 to-slate-900/50 backdrop-blur-xl rounded-3xl border border-teal-500/20 shadow-2xl shadow-teal-500/5 overflow-hidden">
                    <div class="bg-gradient-to-r from-teal-600/20 via-emerald-600/20 to-teal-600/20 px-6 py-5 border-b border-teal-500/20">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-teal-500 to-emerald-500 flex items-center justify-center shadow-lg">
                                <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/>
                                </svg>
                            </div>
                            <div>
                                <h2 class="font-mono text-lg font-bold text-white uppercase tracking-wider">Jarak ke Lembaga Terdekat</h2>
                                <p class="text-xs text-teal-400/80">Jarak ke institusi pendidikan terdekat (dalam kilometer)</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <!-- Jarak RA Terdekat -->
                            <div>
                                <label class="flex items-center gap-2 mb-3">
                                    <svg class="w-4 h-4 text-teal-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21.75V19.5C12 18.12 13.12 17 14.5 17H16.5C17.88 17 19 18.12 19 19.5V21.75M12 21.75V3M12 3H7.5C6.12 3 5 4.12 5 5.5V8.25"/>
                                    </svg>
                                    <span class="font-mono text-xs font-semibold uppercase tracking-wider text-teal-400">Jarak RA Terdekat</span>
                                </label>
                                <div class="relative">
                                    <input type="number" step="0.01" name="jarak_ra_terdekat" value="{{ old('jarak_ra_terdekat', $formData['jarak_ra_terdekat']) }}"
                                        class="w-full rounded-xl border border-teal-500/30 bg-slate-800/80 px-4 py-3.5 pr-12 text-white placeholder-slate-500 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all font-mono"
                                        placeholder="0.00">
                                    <span class="absolute right-4 top-1/2 -translate-y-1/2 font-mono text-xs text-slate-400">km</span>
                                </div>
                            </div>

                            <!-- Jarak MI Terdekat -->
                            <div>
                                <label class="flex items-center gap-2 mb-3">
                                    <svg class="w-4 h-4 text-teal-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21.75V19.5C12 18.12 13.12 17 14.5 17H16.5C17.88 17 19 18.12 19 19.5V21.75M12 21.75V3M12 3H7.5C6.12 3 5 4.12 5 5.5V8.25"/>
                                    </svg>
                                    <span class="font-mono text-xs font-semibold uppercase tracking-wider text-teal-400">Jarak MI Terdekat</span>
                                </label>
                                <div class="relative">
                                    <input type="number" step="0.01" name="jarak_mi_terdekat" value="{{ old('jarak_mi_terdekat', $formData['jarak_mi_terdekat']) }}"
                                        class="w-full rounded-xl border border-teal-500/30 bg-slate-800/80 px-4 py-3.5 pr-12 text-white placeholder-slate-500 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all font-mono"
                                        placeholder="0.00">
                                    <span class="absolute right-4 top-1/2 -translate-y-1/2 font-mono text-xs text-slate-400">km</span>
                                </div>
                            </div>

                            <!-- Jarak MTS Terdekat -->
                            <div>
                                <label class="flex items-center gap-2 mb-3">
                                    <svg class="w-4 h-4 text-teal-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21.75V19.5C12 18.12 13.12 17 14.5 17H16.5C17.88 17 19 18.12 19 19.5V21.75M12 21.75V3M12 3H7.5C6.12 3 5 4.12 5 5.5V8.25"/>
                                    </svg>
                                    <span class="font-mono text-xs font-semibold uppercase tracking-wider text-teal-400">Jarak MTS Terdekat</span>
                                </label>
                                <div class="relative">
                                    <input type="number" step="0.01" name="jarak_mts_terdekat" value="{{ old('jarak_mts_terdekat', $formData['jarak_mts_terdekat']) }}"
                                        class="w-full rounded-xl border border-teal-500/30 bg-slate-800/80 px-4 py-3.5 pr-12 text-white placeholder-slate-500 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all font-mono"
                                        placeholder="0.00">
                                    <span class="absolute right-4 top-1/2 -translate-y-1/2 font-mono text-xs text-slate-400">km</span>
                                </div>
                            </div>

                            <!-- Jarak MA Terdekat -->
                            <div>
                                <label class="flex items-center gap-2 mb-3">
                                    <svg class="w-4 h-4 text-teal-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21.75V19.5C12 18.12 13.12 17 14.5 17H16.5C17.88 17 19 18.12 19 19.5V21.75M12 21.75V3M12 3H7.5C6.12 3 5 4.12 5 5.5V8.25"/>
                                    </svg>
                                    <span class="font-mono text-xs font-semibold uppercase tracking-wider text-teal-400">Jarak MA Terdekat</span>
                                </label>
                                <div class="relative">
                                    <input type="number" step="0.01" name="jarak_ma_terdekat" value="{{ old('jarak_ma_terdekat', $formData['jarak_ma_terdekat']) }}"
                                        class="w-full rounded-xl border border-teal-500/30 bg-slate-800/80 px-4 py-3.5 pr-12 text-white placeholder-slate-500 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all font-mono"
                                        placeholder="0.00">
                                    <span class="absolute right-4 top-1/2 -translate-y-1/2 font-mono text-xs text-slate-400">km</span>
                                </div>
                            </div>

                            <!-- Jarak Pontren Terdekat -->
                            <div>
                                <label class="flex items-center gap-2 mb-3">
                                    <svg class="w-4 h-4 text-teal-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21.75V19.5C12 18.12 13.12 17 14.5 17H16.5C17.88 17 19 18.12 19 19.5V21.75M12 21.75V3M12 3H7.5C6.12 3 5 4.12 5 5.5V8.25"/>
                                    </svg>
                                    <span class="font-mono text-xs font-semibold uppercase tracking-wider text-teal-400">Jarak Pontren Terdekat</span>
                                </label>
                                <div class="relative">
                                    <input type="number" step="0.01" name="jarak_pontren_terdekat" value="{{ old('jarak_pontren_terdekat', $formData['jarak_pontren_terdekat']) }}"
                                        class="w-full rounded-xl border border-teal-500/30 bg-slate-800/80 px-4 py-3.5 pr-12 text-white placeholder-slate-500 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all font-mono"
                                        placeholder="0.00">
                                    <span class="absolute right-4 top-1/2 -translate-y-1/2 font-mono text-xs text-slate-400">km</span>
                                </div>
                            </div>

                            <!-- Jarak TK Terdekat -->
                            <div>
                                <label class="flex items-center gap-2 mb-3">
                                    <svg class="w-4 h-4 text-teal-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21.75V19.5C12 18.12 13.12 17 14.5 17H16.5C17.88 17 19 18.12 19 19.5V21.75M12 21.75V3M12 3H7.5C6.12 3 5 4.12 5 5.5V8.25"/>
                                    </svg>
                                    <span class="font-mono text-xs font-semibold uppercase tracking-wider text-teal-400">Jarak TK Terdekat</span>
                                </label>
                                <div class="relative">
                                    <input type="number" step="0.01" name="jarak_tk_terdekat" value="{{ old('jarak_tk_terdekat', $formData['jarak_tk_terdekat']) }}"
                                        class="w-full rounded-xl border border-teal-500/30 bg-slate-800/80 px-4 py-3.5 pr-12 text-white placeholder-slate-500 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all font-mono"
                                        placeholder="0.00">
                                    <span class="absolute right-4 top-1/2 -translate-y-1/2 font-mono text-xs text-slate-400">km</span>
                                </div>
                            </div>

                            <!-- Jarak SD Terdekat -->
                            <div>
                                <label class="flex items-center gap-2 mb-3">
                                    <svg class="w-4 h-4 text-teal-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21.75V19.5C12 18.12 13.12 17 14.5 17H16.5C17.88 17 19 18.12 19 19.5V21.75M12 21.75V3M12 3H7.5C6.12 3 5 4.12 5 5.5V8.25"/>
                                    </svg>
                                    <span class="font-mono text-xs font-semibold uppercase tracking-wider text-teal-400">Jarak SD Terdekat</span>
                                </label>
                                <div class="relative">
                                    <input type="number" step="0.01" name="jarak_sd_terdekat" value="{{ old('jarak_sd_terdekat', $formData['jarak_sd_terdekat']) }}"
                                        class="w-full rounded-xl border border-teal-500/30 bg-slate-800/80 px-4 py-3.5 pr-12 text-white placeholder-slate-500 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all font-mono"
                                        placeholder="0.00">
                                    <span class="absolute right-4 top-1/2 -translate-y-1/2 font-mono text-xs text-slate-400">km</span>
                                </div>
                            </div>

                            <!-- Jarak SMP Terdekat -->
                            <div>
                                <label class="flex items-center gap-2 mb-3">
                                    <svg class="w-4 h-4 text-teal-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21.75V19.5C12 18.12 13.12 17 14.5 17H16.5C17.88 17 19 18.12 19 19.5V21.75M12 21.75V3M12 3H7.5C6.12 3 5 4.12 5 5.5V8.25"/>
                                    </svg>
                                    <span class="font-mono text-xs font-semibold uppercase tracking-wider text-teal-400">Jarak SMP Terdekat</span>
                                </label>
                                <div class="relative">
                                    <input type="number" step="0.01" name="jarak_smp_terdekat" value="{{ old('jarak_smp_terdekat', $formData['jarak_smp_terdekat']) }}"
                                        class="w-full rounded-xl border border-teal-500/30 bg-slate-800/80 px-4 py-3.5 pr-12 text-white placeholder-slate-500 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all font-mono"
                                        placeholder="0.00">
                                    <span class="absolute right-4 top-1/2 -translate-y-1/2 font-mono text-xs text-slate-400">km</span>
                                </div>
                            </div>

                            <!-- Jarak SMA Terdekat -->
                            <div>
                                <label class="flex items-center gap-2 mb-3">
                                    <svg class="w-4 h-4 text-teal-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21.75V19.5C12 18.12 13.12 17 14.5 17H16.5C17.88 17 19 18.12 19 19.5V21.75M12 21.75V3M12 3H7.5C6.12 3 5 4.12 5 5.5V8.25"/>
                                    </svg>
                                    <span class="font-mono text-xs font-semibold uppercase tracking-wider text-teal-400">Jarak SMA Terdekat</span>
                                </label>
                                <div class="relative">
                                    <input type="number" step="0.01" name="jarak_sma_terdekat" value="{{ old('jarak_sma_terdekat', $formData['jarak_sma_terdekat']) }}"
                                        class="w-full rounded-xl border border-teal-500/30 bg-slate-800/80 px-4 py-3.5 pr-12 text-white placeholder-slate-500 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all font-mono"
                                        placeholder="0.00">
                                    <span class="absolute right-4 top-1/2 -translate-y-1/2 font-mono text-xs text-slate-400">km</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-4">
                    <a href="#" onclick="history.back(); return false;"
                        class="inline-flex items-center gap-3 rounded-xl border border-slate-600/50 bg-slate-800/50 px-6 py-3.5 font-mono text-sm font-semibold uppercase tracking-wider text-slate-300 transition-all hover:bg-slate-700/50 hover:border-slate-500">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Kembali
                    </a>
                    <div class="flex items-center gap-4">
                        <button type="reset"
                            class="inline-flex items-center gap-3 rounded-xl border border-amber-500/30 bg-amber-500/10 px-6 py-3.5 font-mono text-sm font-semibold uppercase tracking-wider text-amber-400 transition-all hover:bg-amber-500/20 hover:border-amber-500">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Reset
                        </button>
                        <button type="submit"
                            class="inline-flex items-center gap-3 rounded-xl bg-gradient-to-r from-emerald-600 to-cyan-600 px-8 py-3.5 font-mono text-sm font-bold uppercase tracking-wider text-white shadow-lg shadow-emerald-500/30 transition-all hover:shadow-emerald-500/50 hover:scale-105">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                            </svg>
                            Simpan Data
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </main>
</x-layouts.app>
