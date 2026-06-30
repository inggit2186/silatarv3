<x-layouts.app title="Guru Madrasah - SILATAR">
    <main class="min-h-screen py-8 px-4 md:px-8 relative" x-data="{ expandedRows: [] }">
        <!-- Animated Background Orbs -->
        <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
            <div class="absolute -top-40 -right-40 w-96 h-96 bg-gradient-to-br from-amber-500/20 to-orange-500/20 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-gradient-to-tr from-rose-500/20 to-red-500/20 rounded-full blur-3xl animate-pulse" style="animation-delay: 1s;"></div>
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
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-amber-500 to-rose-500 flex items-center justify-center shadow-lg shadow-amber-500/30">
                        <svg class="w-9 h-9 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <span class="px-4 py-1.5 rounded-full border border-amber-500/30 bg-amber-500/10 font-mono text-xs font-semibold uppercase tracking-widest text-amber-400">
                        Data Guru
                    </span>
                </div>
                <h1 class="font-mono text-3xl md:text-4xl font-black uppercase tracking-wider text-white mb-3">
                    Guru <span class="bg-gradient-to-r from-amber-400 to-rose-400 bg-clip-text text-transparent">{{ $deptName }}</span>
                </h1>
                <p class="text-slate-400 max-w-xl mx-auto">Daftar guru yang tercatat dalam sistem berdasarkan unit kerja madrasah Anda.</p>
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
                <a
                    href="{{ route('madrasah.pegawai') }}"
                    class="flex items-center gap-2 px-6 py-3 rounded-xl border font-mono text-sm font-semibold uppercase tracking-wider transition-all duration-300 bg-slate-800/80 text-slate-400 border-slate-700 hover:bg-slate-700/80 hover:text-white"
                >
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Pegawai Madrasah
                </a>
                <div
                    class="flex items-center gap-2 px-6 py-3 rounded-xl border font-mono text-sm font-semibold uppercase tracking-wider transition-all duration-300 bg-gradient-to-r from-amber-600 to-rose-600 text-white border-amber-500/50 shadow-lg shadow-amber-500/20"
                >
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    Guru Madrasah
                </div>
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
                        <div class="w-12 h-12 rounded-xl bg-amber-500/20 flex items-center justify-center">
                            <svg class="w-6 h-6 text-amber-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-mono text-xs uppercase tracking-wider text-slate-400">Total Guru</p>
                            <p class="font-mono text-2xl font-bold text-white">{{ $stats['total'] }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-slate-900/90 to-slate-900/50 backdrop-blur-xl rounded-2xl border border-slate-700/50 p-5 shadow-xl">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-emerald-500/20 flex items-center justify-center">
                            <svg class="w-6 h-6 text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-mono text-xs uppercase tracking-wider text-slate-400">Tersertifikasi</p>
                            <p class="font-mono text-2xl font-bold text-emerald-400">{{ $stats['sertifikasi'] }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-slate-900/90 to-slate-900/50 backdrop-blur-xl rounded-2xl border border-slate-700/50 p-5 shadow-xl">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-rose-500/20 flex items-center justify-center">
                            <svg class="w-6 h-6 text-rose-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-mono text-xs uppercase tracking-wider text-slate-400">Belum Sertifikasi</p>
                            <p class="font-mono text-2xl font-bold text-rose-400">{{ $stats['belum_sertifikasi'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table Section -->
            <div class="bg-gradient-to-br from-slate-900/90 to-slate-900/50 backdrop-blur-xl rounded-3xl border border-amber-500/20 shadow-2xl shadow-amber-500/5 overflow-hidden">
                <div class="bg-gradient-to-r from-amber-600/20 via-rose-600/20 to-amber-600/20 px-6 py-5 border-b border-amber-500/20">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-500 to-rose-500 flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="font-mono text-lg font-bold text-white uppercase tracking-wider">Daftar Guru</h2>
                            <p class="text-xs text-amber-400/80">Klik baris untuk melihat detail lengkap</p>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-slate-700/50 bg-slate-800/50">
                                <th class="px-6 py-4 text-left font-mono text-xs uppercase tracking-wider text-slate-400">Nama</th>
                                <th class="px-6 py-4 text-left font-mono text-xs uppercase tracking-wider text-slate-400">Mapel / Bidang</th>
                                <th class="px-6 py-4 text-left font-mono text-xs uppercase tracking-wider text-slate-400">Status</th>
                                <th class="px-6 py-4 text-left font-mono text-xs uppercase tracking-wider text-slate-400">Kontak</th>
                                <th class="px-6 py-4 text-center font-mono text-xs uppercase tracking-wider text-slate-400">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-700/30">
                            @forelse($guruList as $guru)
                                <tr
                                    class="border-b border-slate-700/30 hover:bg-slate-800/50 transition-colors cursor-pointer"
                                    @click="expandedRows.includes({{ $guru->id }}) ? expandedRows = expandedRows.filter(id => id !== {{ $guru->id }}) : expandedRows.push({{ $guru->id }})"
                                >
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-500/30 to-rose-500/30 flex items-center justify-center overflow-hidden">
                                                @if($guru->photo_url)
                                                    <img src="{{ $guru->photo_url }}" alt="{{ $guru->name }}" class="w-full h-full object-cover" onerror="this.parentElement.innerHTML = '{{ $guru->initials }}'">
                                                @else
                                                    <span class="font-mono text-sm font-bold text-amber-400">{{ $guru->initials }}</span>
                                                @endif
                                            </div>
                                            <div>
                                                <p class="font-mono text-sm font-semibold text-white">{{ $guru->name ?? '-' }}</p>
                                                <p class="font-mono text-xs text-slate-400">{{ $guru->nomor_induk ?? 'NIP belum terdaftar' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div>
                                            <p class="font-mono text-sm text-white">{{ $guru->bidang_studi_diajar ?? '-' }}</p>
                                            <p class="font-mono text-xs text-slate-400">{{ $guru->jabatan ?? 'Guru' }}</p>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @php
                                            $sertifVariant = ($guru->serdik ?? 'non-sertifikasi') === 'sertifikasi' ? 'emerald' : 'rose';
                                        @endphp
                                        <span class="inline-flex items-center gap-1.5 rounded-full border border-{{ $sertifVariant }}-500/30 bg-{{ $sertifVariant }}-500/10 px-3 py-1 font-mono text-xs font-semibold text-{{ $sertifVariant }}-400">
                                            <span class="h-1.5 w-1.5 rounded-full bg-{{ $sertifVariant }}-400"></span>
                                            {{ $guru->serdik ?? 'non-sertifikasi' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="space-y-1">
                                            @if($guru->email)
                                                <p class="font-mono text-xs text-slate-400 flex items-center gap-1.5">
                                                    <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                                    </svg>
                                                    {{ $guru->email }}
                                                </p>
                                            @endif
                                            @if($guru->telp)
                                                <p class="font-mono text-xs text-slate-400 flex items-center gap-1.5">
                                                    <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                                    </svg>
                                                    {{ $guru->telp }}
                                                </p>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <button
                                                type="button"
                                                class="rounded-lg border border-amber-500/30 bg-amber-500/10 p-2 text-amber-400 transition-all hover:bg-amber-500/20 hover:border-amber-500"
                                                title="Detail"
                                                @click.stop="expandedRows.includes({{ $guru->id }}) ? expandedRows = expandedRows.filter(id => id !== {{ $guru->id }}) : expandedRows.push({{ $guru->id }})"
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
                                <tr x-show="expandedRows.includes({{ $guru->id }})" x-collapse>
                                    <td colspan="5" class="px-6 py-6 bg-slate-800/30">
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                            <!-- Info Column 1 -->
                                            <div class="space-y-4">
                                                <h4 class="font-mono text-sm font-bold text-amber-400 uppercase tracking-wider flex items-center gap-2">
                                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                    </svg>
                                                    Data Pribadi
                                                </h4>
                                                <div class="space-y-2">
                                                    <div class="flex justify-between">
                                                        <span class="font-mono text-xs text-slate-400">Tempat, Tgl Lahir</span>
                                                        <span class="font-mono text-xs text-white text-right">{{ $guru->tempat_lahir ?? '-' }}, {{ $guru->tanggal_lahir ? date('d/m/Y', strtotime($guru->tanggal_lahir)) : '-' }}</span>
                                                    </div>
                                                    <div class="flex justify-between">
                                                        <span class="font-mono text-xs text-slate-400">Jenis Kelamin</span>
                                                        <span class="font-mono text-xs text-white">{{ ucfirst(strtolower($guru->jk ?? '-')) }}</span>
                                                    </div>
                                                    <div class="flex justify-between">
                                                        <span class="font-mono text-xs text-slate-400">NUPTK</span>
                                                        <span class="font-mono text-xs text-white">{{ $guru->nuptk ?? '-' }}</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Info Column 2 -->
                                            <div class="space-y-4">
                                                <h4 class="font-mono text-sm font-bold text-emerald-400 uppercase tracking-wider flex items-center gap-2">
                                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                                    </svg>
                                                    Data Sertifikasi
                                                </h4>
                                                <div class="space-y-2">
                                                    <div class="flex justify-between">
                                                        <span class="font-mono text-xs text-slate-400">NRG</span>
                                                        <span class="font-mono text-xs text-white">{{ $guru->nrg ?? '-' }}</span>
                                                    </div>
                                                    <div class="flex justify-between">
                                                        <span class="font-mono text-xs text-slate-400">Status</span>
                                                        <span class="font-mono text-xs text-white">{{ $guru->serdik ?? 'non-sertifikasi' }}</span>
                                                    </div>
                                                    <div class="flex justify-between">
                                                        <span class="font-mono text-xs text-slate-400">Bidang Studi</span>
                                                        <span class="font-mono text-xs text-white">{{ $guru->bidang_studi_diajar ?? '-' }}</span>
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
                                                        <span class="font-mono text-xs text-slate-400">Alamat</span>
                                                        <span class="font-mono text-xs text-white text-right max-w-[150px] truncate" title="{{ $guru->alamat ?? '-' }}">{{ $guru->alamat ?? '-' }}</span>
                                                    </div>
                                                    <div class="flex justify-between">
                                                        <span class="font-mono text-xs text-slate-400">NPWP</span>
                                                        <span class="font-mono text-xs text-white">{{ $guru->npwp ?? '-' }}</span>
                                                    </div>
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
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="font-mono text-sm text-white">Belum ada data guru</p>
                                                <p class="font-mono text-xs text-slate-400 mt-1">Data guru akan ditampilkan di sini setelah ditambahkan.</p>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($guruList->hasPages())
                <div class="px-6 py-4 border-t border-slate-700/50 bg-slate-800/30">
                    <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                        <p class="font-mono text-xs text-slate-400">
                            Menampilkan {{ $guruList->firstItem() ?? 0 }} - {{ $guruList->lastItem() ?? 0 }} dari {{ $guruList->total() }} data
                        </p>
                        <div class="flex items-center gap-2">
                            @if($guruList->onFirstPage())
                                <span class="rounded-lg border border-slate-700/50 bg-slate-800/50 px-3 py-1.5 font-mono text-xs text-slate-500 cursor-not-allowed">
                                    <svg class="w-4 h-4 inline" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                                    </svg>
                                </span>
                            @else
                                <a href="{{ $guruList->previousPageUrl() }}" class="rounded-lg border border-slate-600/50 bg-slate-800/50 px-3 py-1.5 font-mono text-xs text-slate-400 transition-all hover:bg-slate-700 hover:text-white">
                                    <svg class="w-4 h-4 inline" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                                    </svg>
                                </a>
                            @endif

                            @foreach($guruList->getUrlRange(max(1, $guruList->currentPage() - 2), min($guruList->lastPage(), $guruList->currentPage() + 2)) as $page => $url)
                                @if($page == $guruList->currentPage())
                                    <span class="rounded-lg bg-gradient-to-r from-amber-600 to-rose-600 px-3 py-1.5 font-mono text-xs font-semibold text-white">
                                        {{ $page }}
                                    </span>
                                @else
                                    <a href="{{ $url }}" class="rounded-lg border border-slate-600/50 bg-slate-800/50 px-3 py-1.5 font-mono text-xs text-slate-400 transition-all hover:bg-slate-700 hover:text-white">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach

                            @if($guruList->hasMorePages())
                                <a href="{{ $guruList->nextPageUrl() }}" class="rounded-lg border border-slate-600/50 bg-slate-800/50 px-3 py-1.5 font-mono text-xs text-slate-400 transition-all hover:bg-slate-700 hover:text-white">
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
