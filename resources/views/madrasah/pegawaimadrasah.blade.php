<x-layouts.app title="Pegawai Madrasah - SILATAR">
    <main class="min-h-screen py-8 px-4 md:px-8 relative" x-data="{ expandedRows: [] }">
        <!-- Animated Background Orbs -->
        <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
            <div class="absolute -top-40 -right-40 w-96 h-96 bg-gradient-to-br from-violet-500/20 to-cyan-500/20 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-gradient-to-tr from-emerald-500/20 to-pink-500/20 rounded-full blur-3xl animate-pulse" style="animation-delay: 1s;"></div>
        </div>

        <div class="max-w-6xl mx-auto relative z-10">
            <!-- Breadcrumb & Back -->
            <div class="flex items-center gap-4 mb-6">
                <a href="{{ route('madrasah.profil') }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-600/50 bg-slate-800/50 px-4 py-2 font-mono text-sm text-slate-400 transition-all hover:bg-slate-800 hover:text-white">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali ke Profil
                </a>
            </div>

            <!-- Hero Header -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center gap-3 mb-6">
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-violet-500 to-cyan-500 flex items-center justify-center shadow-lg shadow-violet-500/30">
                        <svg class="w-9 h-9 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <span class="px-4 py-1.5 rounded-full border border-violet-500/30 bg-violet-500/10 font-mono text-xs font-semibold uppercase tracking-widest text-violet-400">
                        Data Pegawai
                    </span>
                </div>
                <h1 class="font-mono text-3xl md:text-4xl font-black uppercase tracking-wider text-white mb-3">
                    Pegawai <span class="bg-gradient-to-r from-violet-400 to-cyan-400 bg-clip-text text-transparent">{{ $deptName }}</span>
                </h1>
                <p class="text-slate-400 max-w-xl mx-auto">Daftar pegawai yang tercatat dalam sistem berdasarkan unit kerja madrasah Anda.</p>
            </div>

            <!-- Tab Navigation -->
            <div class="flex items-center justify-center gap-2 mb-8">
                <a
                    href="{{ route('madrasah.profil') }}"
                    class="flex items-center gap-2 px-6 py-3 rounded-xl border font-mono text-sm font-semibold uppercase tracking-wider transition-all duration-300 bg-slate-800/80 text-slate-400 border-slate-700 hover:bg-slate-700/80 hover:text-white"
                >
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    Profil Madrasah
                </a>
                <div
                    class="flex items-center gap-2 px-6 py-3 rounded-xl border font-mono text-sm font-semibold uppercase tracking-wider transition-all duration-300 bg-gradient-to-r from-violet-600 to-cyan-600 text-white border-violet-500/50 shadow-lg shadow-violet-500/20"
                >
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    Pegawai Madrasah
                </div>
                <a
                    href="{{ route('madrasah.guru') }}"
                    class="flex items-center gap-2 px-6 py-3 rounded-xl border font-mono text-sm font-semibold uppercase tracking-wider transition-all duration-300 bg-slate-800/80 text-slate-400 border-slate-700 hover:bg-slate-700/80 hover:text-white"
                >
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    Guru Madrasah
                </a>
                <a
                    href="{{ route('madrasah.laporan-semester') }}"
                    class="flex items-center gap-2 px-6 py-3 rounded-xl border bg-slate-800/80 text-slate-400 border-slate-700 hover:bg-slate-700/80 hover:text-white font-mono text-sm font-semibold uppercase tracking-wider transition-all duration-300"
                >
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Laporan Semester
                </a>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                <div class="bg-gradient-to-br from-slate-900/90 to-slate-900/50 backdrop-blur-xl rounded-2xl border border-slate-700/50 p-5 shadow-xl">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-violet-500/20 flex items-center justify-center">
                            <svg class="w-6 h-6 text-violet-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-mono text-xs uppercase tracking-wider text-slate-400">Total Pegawai</p>
                            <p class="font-mono text-2xl font-bold text-white">{{ $stats['total'] }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-slate-900/90 to-slate-900/50 backdrop-blur-xl rounded-2xl border border-slate-700/50 p-5 shadow-xl">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-emerald-500/20 flex items-center justify-center">
                            <svg class="w-6 h-6 text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-mono text-xs uppercase tracking-wider text-slate-400">ASN</p>
                            <p class="font-mono text-2xl font-bold text-emerald-400">{{ $stats['asn'] }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-slate-900/90 to-slate-900/50 backdrop-blur-xl rounded-2xl border border-slate-700/50 p-5 shadow-xl">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-amber-500/20 flex items-center justify-center">
                            <svg class="w-6 h-6 text-amber-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-mono text-xs uppercase tracking-wider text-slate-400">Non ASN / Honorer</p>
                            <p class="font-mono text-2xl font-bold text-amber-400">{{ $stats['honorer'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table Section -->
            <div class="bg-gradient-to-br from-slate-900/90 to-slate-900/50 backdrop-blur-xl rounded-3xl border border-violet-500/20 shadow-2xl shadow-violet-500/5 overflow-hidden">
                <div class="bg-gradient-to-r from-violet-600/20 via-cyan-600/20 to-violet-600/20 px-6 py-5 border-b border-violet-500/20">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-violet-500 to-cyan-500 flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="font-mono text-lg font-bold text-white uppercase tracking-wider">Daftar Pegawai</h2>
                            <p class="text-xs text-violet-400/80">Klik baris untuk melihat detail lengkap</p>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-slate-700/50 bg-slate-800/50">
                                <th class="px-6 py-4 text-left font-mono text-xs uppercase tracking-wider text-slate-400">Nama</th>
                                <th class="px-6 py-4 text-left font-mono text-xs uppercase tracking-wider text-slate-400">Jabatan</th>
                                <th class="px-6 py-4 text-left font-mono text-xs uppercase tracking-wider text-slate-400">Status</th>
                                <th class="px-6 py-4 text-left font-mono text-xs uppercase tracking-wider text-slate-400">Kontak</th>
                                <th class="px-6 py-4 text-center font-mono text-xs uppercase tracking-wider text-slate-400">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-700/30">
                            @forelse($pegawaiList as $pegawai)
                                <tr
                                    class="border-b border-slate-700/30 hover:bg-slate-800/50 transition-colors cursor-pointer"
                                    @click="expandedRows.includes({{ $pegawai->id }}) ? expandedRows = expandedRows.filter(id => id !== {{ $pegawai->id }}) : expandedRows.push({{ $pegawai->id }})"
                                >
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-violet-500/30 to-cyan-500/30 flex items-center justify-center overflow-hidden">
                                                @if($pegawai->photo_url)
                                                    <img src="{{ $pegawai->photo_url }}" alt="{{ $pegawai->name }}" class="w-full h-full object-cover" onerror="this.parentElement.innerHTML = '{{ $pegawai->initials }}'">
                                                @else
                                                    <span class="font-mono text-sm font-bold text-violet-400">{{ $pegawai->initials }}</span>
                                                @endif
                                            </div>
                                            <div>
                                                <p class="font-mono text-sm font-semibold text-white">{{ $pegawai->name ?? '-' }}</p>
                                                <p class="font-mono text-xs text-slate-400">{{ $pegawai->nomor_induk ?? 'NIP belum terdaftar' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div>
                                            <p class="font-mono text-sm text-white">{{ $pegawai->jabatan ?? '-' }}</p>
                                            <p class="font-mono text-xs text-slate-400 capitalize">{{ str_replace('_', ' ', $pegawai->kat_jabatan ?? '-') }}</p>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @php
                                            $asnVariant = ($pegawai->asn ?? 'NON ASN') === 'ASN' ? 'emerald' : 'amber';
                                        @endphp
                                        <span class="inline-flex items-center gap-1.5 rounded-full border border-{{ $asnVariant }}-500/30 bg-{{ $asnVariant }}-500/10 px-3 py-1 font-mono text-xs font-semibold text-{{ $asnVariant }}-400">
                                            <span class="h-1.5 w-1.5 rounded-full bg-{{ $asnVariant }}-400"></span>
                                            {{ $pegawai->asn ?? 'NON ASN' }}
                                        </span>
                                        @if($pegawai->status)
                                            <span class="ml-2 inline-flex items-center rounded-full border border-cyan-500/30 bg-cyan-500/10 px-2 py-0.5 font-mono text-[10px] text-cyan-400 capitalize">
                                                {{ $pegawai->status }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="space-y-1">
                                            @if($pegawai->email)
                                                <p class="font-mono text-xs text-slate-400 flex items-center gap-1.5">
                                                    <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                                    </svg>
                                                    {{ $pegawai->email }}
                                                </p>
                                            @endif
                                            @if($pegawai->telp)
                                                <p class="font-mono text-xs text-slate-400 flex items-center gap-1.5">
                                                    <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                                    </svg>
                                                    {{ $pegawai->telp }}
                                                </p>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <button
                                                type="button"
                                                class="rounded-lg border border-cyan-500/30 bg-cyan-500/10 p-2 text-cyan-400 transition-all hover:bg-cyan-500/20 hover:border-cyan-500"
                                                title="Detail"
                                                @click.stop="expandedRows.includes({{ $pegawai->id }}) ? expandedRows = expandedRows.filter(id => id !== {{ $pegawai->id }}) : expandedRows.push({{ $pegawai->id }})"
                                            >
                                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <!-- Expanded Detail Row -->
                                <tr x-show="expandedRows.includes({{ $pegawai->id }})" x-collapse>
                                    <td colspan="5" class="px-6 py-6 bg-slate-800/30">
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                            <!-- Info Column 1 -->
                                            <div class="space-y-4">
                                                <h4 class="font-mono text-sm font-bold text-violet-400 uppercase tracking-wider flex items-center gap-2">
                                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                    </svg>
                                                    Data Pribadi
                                                </h4>
                                                <div class="space-y-2">
                                                    <div class="flex justify-between">
                                                        <span class="font-mono text-xs text-slate-400">Tempat, Tgl Lahir</span>
                                                        <span class="font-mono text-xs text-white text-right">{{ $pegawai->tempat_lahir ?? '-' }}, {{ $pegawai->tanggal_lahir ? date('d/m/Y', strtotime($pegawai->tanggal_lahir)) : '-' }}</span>
                                                    </div>
                                                    <div class="flex justify-between">
                                                        <span class="font-mono text-xs text-slate-400">Jenis Kelamin</span>
                                                        <span class="font-mono text-xs text-white">{{ ucfirst(strtolower($pegawai->jk ?? '-')) }}</span>
                                                    </div>
                                                    <div class="flex justify-between">
                                                        <span class="font-mono text-xs text-slate-400">Golongan</span>
                                                        <span class="font-mono text-xs text-white">{{ $pegawai->gol ?? '-' }}</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Info Column 2 -->
                                            <div class="space-y-4">
                                                <h4 class="font-mono text-sm font-bold text-emerald-400 uppercase tracking-wider flex items-center gap-2">
                                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                                    </svg>
                                                    Data Kepegawaian
                                                </h4>
                                                <div class="space-y-2">
                                                    <div class="flex justify-between">
                                                        <span class="font-mono text-xs text-slate-400">TMT CPNS</span>
                                                        <span class="font-mono text-xs text-white">{{ $pegawai->tmt_cpns ? date('d/m/Y', strtotime($pegawai->tmt_cpns)) : '-' }}</span>
                                                    </div>
                                                    <div class="flex justify-between">
                                                        <span class="font-mono text-xs text-slate-400">TMT PNS</span>
                                                        <span class="font-mono text-xs text-white">{{ $pegawai->tmt_pns ? date('d/m/Y', strtotime($pegawai->tmt_pns)) : '-' }}</span>
                                                    </div>
                                                    <div class="flex justify-between">
                                                        <span class="font-mono text-xs text-slate-400">TMT Tugas</span>
                                                        <span class="font-mono text-xs text-white">{{ $pegawai->tmt_tugas ? date('d/m/Y', strtotime($pegawai->tmt_tugas)) : '-' }}</span>
                                                    </div>
                                                    <div class="flex justify-between">
                                                        <span class="font-mono text-xs text-slate-400">NPWP</span>
                                                        <span class="font-mono text-xs text-white">{{ $pegawai->npwp ?? '-' }}</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Info Column 3 -->
                                            <div class="space-y-4">
                                                <h4 class="font-mono text-sm font-bold text-cyan-400 uppercase tracking-wider flex items-center gap-2">
                                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                                    </svg>
                                                    Alamat & Lainnya
                                                </h4>
                                                <div class="space-y-2">
                                                    <div class="flex justify-between">
                                                        <span class="font-mono text-xs text-slate-400">Alamat KTP</span>
                                                        <span class="font-mono text-xs text-white text-right max-w-[150px] truncate" title="{{ $pegawai->alamat ?? '-' }}">{{ $pegawai->alamat ?? '-' }}</span>
                                                    </div>
                                                    <div class="flex justify-between">
                                                        <span class="font-mono text-xs text-slate-400">Alamat Tinggal</span>
                                                        <span class="font-mono text-xs text-white text-right max-w-[150px] truncate" title="{{ $pegawai->alamat ?? '-' }}">{{ $pegawai->alamat ?? '-' }}</span>
                                                    </div>
                                                    @if($pegawai->ijazah_universitas)
                                                    <div class="flex justify-between">
                                                        <span class="font-mono text-xs text-slate-400">Universitas</span>
                                                        <span class="font-mono text-xs text-white text-right max-w-[150px] truncate" title="{{ $pegawai->ijazah_universitas }}">{{ $pegawai->ijazah_universitas }}</span>
                                                    </div>
                                                    @endif
                                                    @if($pegawai->ijazah_jurusan)
                                                    <div class="flex justify-between">
                                                        <span class="font-mono text-xs text-slate-400">Jurusan</span>
                                                        <span class="font-mono text-xs text-white">{{ $pegawai->ijazah_jurusan }}</span>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center gap-4">
                                            <div class="w-16 h-16 rounded-full bg-slate-800/50 flex items-center justify-center">
                                                <svg class="w-8 h-8 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="font-mono text-sm text-white">Belum ada data pegawai</p>
                                                <p class="font-mono text-xs text-slate-400 mt-1">Data pegawai akan ditampilkan di sini setelah ditambahkan.</p>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($pegawaiList->hasPages())
                <div class="px-6 py-4 border-t border-slate-700/50 bg-slate-800/30">
                    <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                        <p class="font-mono text-xs text-slate-400">
                            Menampilkan {{ $pegawaiList->firstItem() ?? 0 }} - {{ $pegawaiList->lastItem() ?? 0 }} dari {{ $pegawaiList->total() }} data
                        </p>
                        <div class="flex items-center gap-2">
                            @if($pegawaiList->onFirstPage())
                                <span class="rounded-lg border border-slate-700/50 bg-slate-800/50 px-3 py-1.5 font-mono text-xs text-slate-500 cursor-not-allowed">
                                    <svg class="w-4 h-4 inline" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                                    </svg>
                                </span>
                            @else
                                <a href="{{ $pegawaiList->previousPageUrl() }}" class="rounded-lg border border-slate-600/50 bg-slate-800/50 px-3 py-1.5 font-mono text-xs text-slate-400 transition-all hover:bg-slate-700 hover:text-white">
                                    <svg class="w-4 h-4 inline" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                                    </svg>
                                </a>
                            @endif

                            @foreach($pegawaiList->getUrlRange(max(1, $pegawaiList->currentPage() - 2), min($pegawaiList->lastPage(), $pegawaiList->currentPage() + 2)) as $page => $url)
                                @if($page == $pegawaiList->currentPage())
                                    <span class="rounded-lg bg-gradient-to-r from-violet-600 to-cyan-600 px-3 py-1.5 font-mono text-xs font-semibold text-white">
                                        {{ $page }}
                                    </span>
                                @else
                                    <a href="{{ $url }}" class="rounded-lg border border-slate-600/50 bg-slate-800/50 px-3 py-1.5 font-mono text-xs text-slate-400 transition-all hover:bg-slate-700 hover:text-white">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach

                            @if($pegawaiList->hasMorePages())
                                <a href="{{ $pegawaiList->nextPageUrl() }}" class="rounded-lg border border-slate-600/50 bg-slate-800/50 px-3 py-1.5 font-mono text-xs text-slate-400 transition-all hover:bg-slate-700 hover:text-white">
                                    <svg class="w-4 h-4 inline" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            @else
                                <span class="rounded-lg border border-slate-700/50 bg-slate-800/50 px-3 py-1.5 font-mono text-xs text-slate-500 cursor-not-allowed">
                                    <svg class="w-4 h-4 inline" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </main>
</x-layouts.app>
