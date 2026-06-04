<x-layouts.app title="Edit Profil - SILATAR">
    <main class="silatar-report-page space-y-6">
        <!-- Page Header -->
        <div class="relative bg-gradient-to-b from-slate-950 via-slate-900 to-slate-950 py-8 overflow-hidden">
            <!-- Cyberpunk Background Effects -->
            <div class="absolute inset-0 opacity-30">
                <div class="absolute inset-0" style="background-image: linear-gradient(rgba(0,212,255,0.03) 1px, transparent 1px), linear-gradient(90deg, rgba(0,212,255,0.03) 1px, transparent 1px); background-size: 50px 50px;"></div>
                <div class="absolute inset-0 bg-gradient-to-b from-transparent via-cyan-500/5 to-transparent" style="background-size: 100% 4px;"></div>
            </div>

            <!-- Corner Decorations -->
            <div class="absolute top-4 left-4 h-16 w-16 border-l-2 border-t-2 border-cyan-500/40"></div>
            <div class="absolute top-4 right-4 h-16 w-16 border-r-2 border-t-2 border-cyan-500/40"></div>
            <div class="absolute bottom-4 left-4 h-16 w-16 border-b-2 border-l-2 border-cyan-500/40"></div>
            <div class="absolute bottom-4 right-4 h-16 w-16 border-b-2 border-r-2 border-cyan-500/40"></div>

            <!-- Floating Particles -->
            <div class="absolute top-20 left-[10%] h-2 w-2 rounded-full bg-cyan-400/50 animate-pulse"></div>
            <div class="absolute top-32 right-[15%] h-1 w-1 rounded-full bg-cyan-300/70 animate-pulse" style="animation-delay: 0.5s;"></div>
            <div class="absolute bottom-20 left-[20%] h-1.5 w-1.5 rounded-full bg-cyan-500/40 animate-pulse" style="animation-delay: 1s;"></div>

            <div class="relative mx-auto max-w-4xl px-6 lg:px-8 text-center">
                <a href="{{ route('profil') }}" class="mb-4 inline-flex items-center gap-2 text-sm text-cyan-400/60 transition-colors hover:text-cyan-400">
                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Kembali ke Profil
                </a>

                <span class="mt-4 inline-flex items-center gap-2 rounded-full border border-cyan-500/30 bg-cyan-500/10 px-4 py-1.5 font-mono text-xs font-semibold uppercase tracking-widest text-cyan-400 shadow-[0_0_20px_rgba(0,212,255,0.3)]">
                    <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828l-9.193 9.193a2 2 0 01-2.828 0l-2.172-2.172a2 2 0 010-2.828l9.193-9.193z"/>
                    </svg>
                    Edit Profil
                </span>
                <h1 class="mt-4 font-mono text-3xl font-black uppercase tracking-wider text-white lg:text-4xl drop-shadow-[0_0_30px_rgba(0,212,255,0.5)]">
                    Edit Data Profil
                </h1>
                <p class="mt-2 text-sm text-slate-400">Perbarui informasi profil Anda</p>
            </div>
        </div>

        <!-- Edit Form -->
        <div class="relative mx-auto max-w-3xl px-6 lg:px-8">
            <form method="POST" action="{{ route('profil.update') }}" enctype="multipart/form-data" x-data="{ statusNikah: '{{ old('nikah', $user->nikah ?? '0') }}', jenisPjob: '{{ old('jenis_pjob', $user->jenis_pjob ?? '') }}' }">
                @csrf
                @method('PUT')

                <!-- Main Card -->
                <div class="relative rounded-3xl border border-cyan-500/30 bg-gradient-to-br from-slate-900 via-slate-800/90 to-slate-900 shadow-[0_0_50px_rgba(0,212,255,0.15)]">
                    <!-- Inner Glow -->
                    <div class="absolute inset-0 rounded-3xl border border-cyan-500/10"></div>

                    <!-- Corner Tech Decorations -->
                    <div class="absolute -top-px left-8 h-6 w-6 border-l-2 border-t-2 border-cyan-400/60"></div>
                    <div class="absolute -top-px right-8 h-6 w-6 border-r-2 border-t-2 border-cyan-400/60"></div>
                    <div class="absolute -bottom-px left-8 h-6 w-6 border-b-2 border-l-2 border-cyan-400/60"></div>
                    <div class="absolute -bottom-px right-8 h-6 w-6 border-b-2 border-r-2 border-cyan-400/60"></div>

                    <!-- Scan Line Effect -->
                    <div class="absolute inset-0 rounded-3xl overflow-hidden pointer-events-none">
                        <div class="absolute inset-0 bg-gradient-to-b from-transparent via-cyan-500/3 to-transparent" style="background-size: 100% 3px;"></div>
                    </div>

                    <div class="relative p-8 space-y-8">
                        <!-- Avatar Section with Change Photo Button -->
                        <div class="flex flex-col items-center">
                            <div class="relative group">
                                <div class="absolute inset-0 h-32 w-32 rounded-full border-2 border-cyan-500/30 animate-pulse"></div>
                                <div class="flex h-32 w-32 items-center justify-center overflow-hidden rounded-full border-4 border-cyan-500/50 bg-gradient-to-br from-cyan-500/30 to-cyan-600/20 shadow-[0_0_40px_rgba(0,212,255,0.3)]">
                                    @if($user->pp && $user->nomor_induk)
                                        <img src="{{ asset('assets/img/users/' . $user->nomor_induk . '/' . $user->pp) }}" alt="{{ $user->name }}" class="h-full w-full object-cover">
                                    @else
                                        <span class="font-mono text-4xl font-bold uppercase text-cyan-400">{{ substr($user->name, 0, 2) }}</span>
                                    @endif
                                </div>
                                <!-- Change Photo Button -->
                                <button type="button" class="absolute bottom-0 right-0 flex h-9 w-9 items-center justify-center rounded-full border-2 border-cyan-500/50 bg-slate-900 shadow-lg transition-all hover:border-cyan-400 hover:shadow-[0_0_20px_rgba(0,212,255,0.5)]">
                                    <svg class="h-4 w-4 text-cyan-400" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2h-5.586a2 2 0 00-1.414.586l-4.414 4.414V20a2 2 0 01-2 2z"/>
                                    </svg>
                                </button>
                            </div>
                            <h2 class="mt-4 font-mono text-xl font-bold text-white">{{ $user->name }}</h2>
                            <p class="mt-1 font-mono text-sm text-cyan-400">{{ $user->nomor_induk }}</p>
                        </div>

                        <!-- Divider with glow -->
                        <div class="relative h-px bg-gradient-to-r from-transparent via-cyan-500/30 to-transparent">
                            <div class="absolute inset-0 h-px bg-gradient-to-r from-transparent via-cyan-400/50 to-transparent blur-sm"></div>
                        </div>

                        <!-- Form Fields -->
                        <div class="grid gap-6 md:grid-cols-2">
                            <!-- Nama Lengkap (Read Only) -->
                            <div class="group relative md:col-span-2">
                                <div class="absolute -left-4 top-0 h-12 w-1 rounded-full bg-cyan-500/30 group-hover:bg-cyan-400/50 transition-colors"></div>
                                <label class="mb-2 block">
                                    <span class="flex items-center gap-2 font-mono text-xs font-semibold uppercase tracking-wider text-cyan-400/80">
                                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        Nama Lengkap
                                    </span>
                                </label>
                                <div class="relative">
                                    <input type="text" value="{{ $user->name }}" readonly class="w-full rounded-xl border border-cyan-500/20 bg-slate-900/50 px-4 py-3 font-mono text-white shadow-inner">
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4">
                                        <svg class="h-5 w-5 text-cyan-500/50" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <!-- Pekerjaan/Jabatan (Read Only) -->
                            <div class="group relative">
                                <div class="absolute -left-4 top-0 h-12 w-1 rounded-full bg-cyan-500/30 group-hover:bg-cyan-400/50 transition-colors"></div>
                                <label class="mb-2 block">
                                    <span class="flex items-center gap-2 font-mono text-xs font-semibold uppercase tracking-wider text-cyan-400/80">
                                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.385 23.385 0 0012 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 12s3 5 7.75 5a9.753 9.753 0 009.257-5.004z"/>
                                        </svg>
                                        Pekerjaan / Jabatan
                                    </span>
                                </label>
                                <div class="relative">
                                    <input type="text" value="{{ $user->pekerjaan ?? '-' }}" readonly class="w-full rounded-xl border border-cyan-500/20 bg-slate-900/50 px-4 py-3 font-mono text-white shadow-inner">
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4">
                                        <svg class="h-5 w-5 text-cyan-500/50" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <!-- Unit Kerja (Read Only) -->
                            <div class="group relative">
                                <div class="absolute -left-4 top-0 h-12 w-1 rounded-full bg-cyan-500/30 group-hover:bg-cyan-400/50 transition-colors"></div>
                                <label class="mb-2 block">
                                    <span class="flex items-center gap-2 font-mono text-xs font-semibold uppercase tracking-wider text-cyan-400/80">
                                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H3a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                        Unit Kerja
                                    </span>
                                </label>
                                <div class="relative">
                                    <input type="text" value="{{ $satuanKerja }}" readonly class="w-full rounded-xl border border-cyan-500/20 bg-slate-900/50 px-4 py-3 font-mono text-white shadow-inner">
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4">
                                        <svg class="h-5 w-5 text-cyan-500/50" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <!-- NIK (Nomor Induk Kependudukan) -->
                            <div class="group relative">
                                <div class="absolute -left-4 top-0 h-12 w-1 rounded-full bg-emerald-500/30 group-hover:bg-emerald-400/50 transition-colors"></div>
                                <label for="nip" class="mb-2 block">
                                    <span class="flex items-center gap-2 font-mono text-xs font-semibold uppercase tracking-wider text-emerald-400/80">
                                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                                        </svg>
                                        NIK (Nomor Induk Kependudukan)
                                    </span>
                                </label>
                                <div class="relative">
                                    <input type="text" name="nip" id="nip" value="{{ old('nip', $user->nip) }}" placeholder="-" class="w-full rounded-xl border border-emerald-500/30 bg-slate-800/80 px-4 py-3 font-mono text-white placeholder-slate-600 transition-all focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400/20">
                                </div>
                            </div>

                            <!-- NPWP -->
                            <div class="group relative">
                                <div class="absolute -left-4 top-0 h-12 w-1 rounded-full bg-orange-500/30 group-hover:bg-orange-400/50 transition-colors"></div>
                                <label for="npwp" class="mb-2 block">
                                    <span class="flex items-center gap-2 font-mono text-xs font-semibold uppercase tracking-wider text-orange-400/80">
                                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H3a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z"/>
                                        </svg>
                                        NPWP
                                    </span>
                                </label>
                                <div class="relative">
                                    <input type="text" name="npwp" id="npwp" value="{{ old('npwp', $user->npwp ?? '') }}" placeholder="00.000.000.0-000.000" class="w-full rounded-xl border border-orange-500/30 bg-slate-800/80 px-4 py-3 font-mono text-white placeholder-slate-600 transition-all focus:border-orange-400 focus:outline-none focus:ring-2 focus:ring-orange-400/20">
                                </div>
                            </div>

                            <!-- Nomor Rekening Gaji -->
                            <div class="group relative">
                                <div class="absolute -left-4 top-0 h-12 w-1 rounded-full bg-cyan-500/30 group-hover:bg-cyan-400/50 transition-colors"></div>
                                <label for="rekening" class="mb-2 block">
                                    <span class="flex items-center gap-2 font-mono text-xs font-semibold uppercase tracking-wider text-cyan-400/80">
                                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                        </svg>
                                        Nomor Rekening Gaji
                                    </span>
                                </label>
                                <div class="relative">
                                    <input type="text" name="rekening" id="rekening" value="{{ old('rekening', $user->rekening ?? '') }}" placeholder="-" class="w-full rounded-xl border border-cyan-500/30 bg-slate-800/80 px-4 py-3 font-mono text-white placeholder-slate-600 transition-all focus:border-cyan-400 focus:outline-none focus:ring-2 focus:ring-cyan-400/20">
                                </div>
                            </div>

                            <!-- Tempat Lahir -->
                            <div class="group relative">
                                <div class="absolute -left-4 top-0 h-12 w-1 rounded-full bg-purple-500/30 group-hover:bg-purple-400/50 transition-colors"></div>
                                <label for="tempat_lahir" class="mb-2 block">
                                    <span class="flex items-center gap-2 font-mono text-xs font-semibold uppercase tracking-wider text-purple-400/80">
                                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        Tempat Lahir
                                    </span>
                                </label>
                                <div class="relative">
                                    <input type="text" name="tempat_lahir" id="tempat_lahir" value="{{ old('tempat_lahir', $user->tempat_lahir ?? '') }}" placeholder="-" class="w-full rounded-xl border border-purple-500/30 bg-slate-800/80 px-4 py-3 font-mono text-white placeholder-slate-600 transition-all focus:border-purple-400 focus:outline-none focus:ring-2 focus:ring-purple-400/20">
                                </div>
                            </div>

                            <!-- Tanggal Lahir -->
                            <div class="group relative">
                                <div class="absolute -left-4 top-0 h-12 w-1 rounded-full bg-amber-500/30 group-hover:bg-amber-400/50 transition-colors"></div>
                                <label for="tanggal_lahir" class="mb-2 block">
                                    <span class="flex items-center gap-2 font-mono text-xs font-semibold uppercase tracking-wider text-amber-400/80">
                                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v2.25m0 0v-2.25m0 2.25h-2.25m2.25 0v2.25m2.25-2.25h-2.25m2.25 0H21"/>
                                        </svg>
                                        Tanggal Lahir
                                    </span>
                                </label>
                                <div class="relative">
                                    <input type="date" name="tanggal_lahir" id="tanggal_lahir" value="{{ old('tanggal_lahir', $user->tanggal_lahir ?? '') }}" class="w-full rounded-xl border border-amber-500/30 bg-slate-800/80 px-4 py-3 font-mono text-white transition-all focus:border-amber-400 focus:outline-none focus:ring-2 focus:ring-amber-400/20">
                                </div>
                            </div>

                            <!-- Jenis Kelamin -->
                            <div class="group relative">
                                <div class="absolute -left-4 top-0 h-12 w-1 rounded-full bg-pink-500/30 group-hover:bg-pink-400/50 transition-colors"></div>
                                <label for="jenis_kelamin" class="mb-2 block">
                                    <span class="flex items-center gap-2 font-mono text-xs font-semibold uppercase tracking-wider text-pink-400/80">
                                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4a1 1 0 100-2 1 1 0 000 2zm5 3.5A1.5 1.5 0 1115.5 8H17a1 1 0 100-2h-1.5M5 16a1 1 0 11-2 0 1 1 0 012 0zm10-2.5h.5a1.5 1.5 0 011.5 1.5v5a1.5 1.5 0 01-1.5 1.5H15a1 1 0 110-2H7.5"/>
                                        </svg>
                                        Jenis Kelamin
                                    </span>
                                </label>
                                <div class="relative">
                                    <select name="jenis_kelamin" id="jenis_kelamin" class="w-full rounded-xl border border-pink-500/30 bg-slate-800/80 px-4 py-3 font-mono text-white transition-all focus:border-pink-400 focus:outline-none focus:ring-2 focus:ring-pink-400/20">
                                        <option value="">-</option>
                                        <option value="laki-laki" {{ in_array(old('jenis_kelamin', $user->jk ?? ''), ['laki-laki', 'Pria']) ? 'selected' : '' }}>Laki-Laki</option>
                                        <option value="perempuan" {{ in_array(old('jenis_kelamin', $user->jk ?? ''), ['perempuan', 'Wanita']) ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Status Pernikahan -->
                            <div class="group relative">
                                <div class="absolute -left-4 top-0 h-12 w-1 rounded-full bg-amber-500/30 group-hover:bg-amber-400/50 transition-colors"></div>
                                <label for="nikah" class="mb-2 block">
                                    <span class="flex items-center gap-2 font-mono text-xs font-semibold uppercase tracking-wider text-amber-400/80">
                                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                        </svg>
                                        Status Pernikahan
                                    </span>
                                </label>
                                <div class="relative">
                                    <select name="nikah" id="nikah" x-model="statusNikah" @change="toggleNikahFields()" class="w-full rounded-xl border border-amber-500/30 bg-slate-800/80 px-4 py-3 font-mono text-white transition-all focus:border-amber-400 focus:outline-none focus:ring-2 focus:ring-amber-400/20">
                                        <option value="0" {{ old('nikah', $user->nikah ?? '') == '0' ? 'selected' : '' }}>Belum Menikah</option>
                                        <option value="1" {{ old('nikah', $user->nikah ?? '') == '1' ? 'selected' : '' }}>Sudah Menikah</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Data Suami/Istri (Dynamic) -->
                            <div x-show="statusNikah == '1'" x-transition class="group relative md:col-span-2">
                                <div class="absolute -left-4 top-0 h-12 w-1 rounded-full bg-violet-500/30 group-hover:bg-violet-400/50 transition-colors"></div>
                                <h4 class="mb-4 flex items-center gap-2 font-mono text-xs font-semibold uppercase tracking-wider text-violet-400">
                                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    Data Suami/Istri
                                </h4>
                                <div class="grid gap-4 md:grid-cols-2">
                                    <div class="form-group mb-0">
                                        <label for="jenis_pjob" class="mb-2 block">
                                            <span class="flex items-center gap-2 font-mono text-xs font-semibold uppercase tracking-wider text-violet-400/80">Jenis Pekerjaan</span>
                                        </label>
                                        <select name="jenis_pjob" id="jenis_pjob" x-model="jenisPjob" @change="toggleTunjanganField()" class="w-full rounded-xl border border-violet-500/30 bg-slate-800/80 px-4 py-3 font-mono text-white transition-all focus:border-violet-400 focus:outline-none focus:ring-2 focus:ring-violet-400/20">
                                            <option value="">-</option>
                                            <option value="ASN" {{ old('jenis_pjob', $user->jenis_pjob ?? '') === 'ASN' ? 'selected' : '' }}>ASN</option>
                                            <option value="NON" {{ old('jenis_pjob', $user->jenis_pjob ?? '') === 'NON' ? 'selected' : '' }}>Non-ASN</option>
                                        </select>
                                    </div>
                                    <div class="form-group mb-0">
                                        <label for="pjob" class="mb-2 block">
                                            <span class="flex items-center gap-2 font-mono text-xs font-semibold uppercase tracking-wider text-violet-400/80">Pekerjaan</span>
                                        </label>
                                        <input type="text" name="pjob" id="pjob" value="{{ old('pjob', $user->pjob ?? '') }}" placeholder="Contoh: PNS, Wiraswasta, dll" class="w-full rounded-xl border border-violet-500/30 bg-slate-800/80 px-4 py-3 font-mono text-white placeholder-slate-600 transition-all focus:border-violet-400 focus:outline-none focus:ring-2 focus:ring-violet-400/20">
                                    </div>
                                    <div class="form-group mb-0">
                                        <label for="jml_anak" class="mb-2 block">
                                            <span class="flex items-center gap-2 font-mono text-xs font-semibold uppercase tracking-wider text-violet-400/80">Jumlah Anak</span>
                                        </label>
                                        <input type="number" name="jml_anak" id="jml_anak" value="{{ old('jml_anak', $user->jml_anak ?? '') }}" placeholder="0" min="0" class="w-full rounded-xl border border-violet-500/30 bg-slate-800/80 px-4 py-3 font-mono text-white placeholder-slate-600 transition-all focus:border-violet-400 focus:outline-none focus:ring-2 focus:ring-violet-400/20">
                                    </div>
                                    <!-- Permintaan Tunjangan ( ASN only ) -->
                                    <div x-show="jenisPjob === 'ASN'" x-transition class="form-group mb-0">
                                        <label for="req_tunjangan" class="mb-2 block">
                                            <span class="flex items-center gap-2 font-mono text-xs font-semibold uppercase tracking-wider text-violet-400/80">Permintaan Tunjangan</span>
                                        </label>
                                        <select name="req_tunjangan" id="req_tunjangan" class="w-full rounded-xl border border-violet-500/30 bg-slate-800/80 px-4 py-3 font-mono text-white transition-all focus:border-violet-400 focus:outline-none focus:ring-2 focus:ring-violet-400/20">
                                            <option value="0" {{ old('req_tunjangan', $user->req_tunjangan ?? '') == '0' ? 'selected' : '' }}>Pasangan</option>
                                            <option value="1" {{ old('req_tunjangan', $user->req_tunjangan ?? '') == '1' ? 'selected' : '' }}>Personal</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- No. HP -->
                            <div class="group relative">
                                <div class="absolute -left-4 top-0 h-12 w-1 rounded-full bg-blue-500/30 group-hover:bg-blue-400/50 transition-colors"></div>
                                <label for="no_hp" class="mb-2 block">
                                    <span class="flex items-center gap-2 font-mono text-xs font-semibold uppercase tracking-wider text-blue-400/80">
                                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.224 21 3 14.775 3 12V5a2 2 0 012-2z"/>
                                        </svg>
                                        No. HP
                                    </span>
                                </label>
                                <div class="relative">
                                    <input type="text" name="no_hp" id="no_hp" value="{{ old('no_hp', $user->telp ?? '') }}" placeholder="-" class="w-full rounded-xl border border-blue-500/30 bg-slate-800/80 px-4 py-3 font-mono text-white placeholder-slate-600 transition-all focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-400/20">
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="group relative md:col-span-2">
                                <div class="absolute -left-4 top-0 h-12 w-1 rounded-full bg-indigo-500/30 group-hover:bg-indigo-400/50 transition-colors"></div>
                                <label for="email" class="mb-2 block">
                                    <span class="flex items-center gap-2 font-mono text-xs font-semibold uppercase tracking-wider text-indigo-400/80">
                                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L17 8m-2 2l1.293 1.293a1 1 0 101.414-1.414L13.707 8.293a2 2 0 000-2.828l.586-.586m-2 2l-.586-.586a2 2 0 00-2.828 0"/>
                                        </svg>
                                        Email
                                    </span>
                                </label>
                                <div class="relative">
                                    <input type="email" name="email" id="email" value="{{ old('email', $user->email ?? '') }}" placeholder="-" class="w-full rounded-xl border border-indigo-500/30 bg-slate-800/80 px-4 py-3 font-mono text-white placeholder-slate-600 transition-all focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-400/20">
                                </div>
                            </div>

                            <!-- Alamat -->
                            <div class="group relative md:col-span-2">
                                <div class="absolute -left-4 top-0 h-12 w-1 rounded-full bg-teal-500/30 group-hover:bg-teal-400/50 transition-colors"></div>
                                <label for="alamat" class="mb-2 block">
                                    <span class="flex items-center gap-2 font-mono text-xs font-semibold uppercase tracking-wider text-teal-400/80">
                                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        Alamat
                                    </span>
                                </label>
                                <div class="relative">
                                    <textarea name="alamat" id="alamat" rows="3" placeholder="-" class="w-full rounded-xl border border-teal-500/30 bg-slate-800/80 px-4 py-3 font-mono text-white placeholder-slate-600 transition-all focus:border-teal-400 focus:outline-none focus:ring-2 focus:ring-teal-400/20 resize-none">{{ old('alamat', $user->alamat ?? '') }}</textarea>
                                </div>
                            </div>

                            <!-- Ceritakan Sedikit Tentang Diri Anda -->
                            <div class="group relative md:col-span-2">
                                <div class="absolute -left-4 top-0 h-12 w-1 rounded-full bg-rose-500/30 group-hover:bg-rose-400/50 transition-colors"></div>
                                <label for="bio" class="mb-2 block">
                                    <span class="flex items-center gap-2 font-mono text-xs font-semibold uppercase tracking-wider text-rose-400/80">
                                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828l-9.193 9.193a2 2 0 01-2.828 0l-2.172-2.172a2 2 0 010-2.828l9.193-9.193z"/>
                                        </svg>
                                        Ceritakan Sedikit Tentang Diri Anda
                                    </span>
                                </label>
                                <div class="relative">
                                    <textarea name="bio" id="bio" rows="4" placeholder="Deskripsikan tentang diri Anda..." class="w-full rounded-xl border border-rose-500/30 bg-slate-800/80 px-4 py-3 font-mono text-white placeholder-slate-600 transition-all focus:border-rose-400 focus:outline-none focus:ring-2 focus:ring-rose-400/20 resize-none">{{ old('bio', $user->bio ?? '') }}</textarea>
                                </div>
                            </div>

                            <!-- Divider with glow for Social Media -->
                            <div class="relative h-px bg-gradient-to-r from-transparent via-cyan-500/30 to-transparent md:col-span-2">
                                <div class="absolute inset-0 h-px bg-gradient-to-r from-transparent via-cyan-400/50 to-transparent blur-sm"></div>
                            </div>

                            <!-- Social Media Header -->
                            <div class="group relative md:col-span-2">
                                <div class="flex items-center gap-2 mb-4">
                                    <svg class="h-5 w-5 text-cyan-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                                    </svg>
                                    <span class="font-mono text-sm font-bold uppercase tracking-wider text-cyan-400">Media Sosial</span>
                                </div>
                            </div>

                            <!-- Facebook -->
                            <div class="group relative">
                                <div class="absolute -left-4 top-0 h-12 w-1 rounded-full bg-blue-500/30 group-hover:bg-blue-400/50 transition-colors"></div>
                                <label for="facebook" class="mb-2 block">
                                    <span class="flex items-center gap-2 font-mono text-xs font-semibold uppercase tracking-wider text-blue-400/80">
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                        </svg>
                                        Facebook
                                    </span>
                                </label>
                                <div class="relative">
                                    <input type="url" name="facebook" id="facebook" value="{{ old('facebook', $user->facebook ?? '') }}" placeholder="https://facebook.com/username" class="w-full rounded-xl border border-blue-500/30 bg-slate-800/80 px-4 py-3 font-mono text-white placeholder-slate-600 transition-all focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-400/20">
                                </div>
                            </div>

                            <!-- Twitter / X -->
                            <div class="group relative">
                                <div class="absolute -left-4 top-0 h-12 w-1 rounded-full bg-slate-400/30 group-hover:bg-slate-300/50 transition-colors"></div>
                                <label for="twitter" class="mb-2 block">
                                    <span class="flex items-center gap-2 font-mono text-xs font-semibold uppercase tracking-wider text-slate-400/80">
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                                        </svg>
                                        Twitter / X
                                    </span>
                                </label>
                                <div class="relative">
                                    <input type="url" name="twitter" id="twitter" value="{{ old('twitter', $user->twitter ?? '') }}" placeholder="https://x.com/username" class="w-full rounded-xl border border-slate-500/30 bg-slate-800/80 px-4 py-3 font-mono text-white placeholder-slate-600 transition-all focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400/20">
                                </div>
                            </div>

                            <!-- LinkedIn -->
                            <div class="group relative">
                                <div class="absolute -left-4 top-0 h-12 w-1 rounded-full bg-blue-600/30 group-hover:bg-blue-500/50 transition-colors"></div>
                                <label for="linkedin" class="mb-2 block">
                                    <span class="flex items-center gap-2 font-mono text-xs font-semibold uppercase tracking-wider text-blue-600/80">
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                        </svg>
                                        LinkedIn
                                    </span>
                                </label>
                                <div class="relative">
                                    <input type="url" name="linkedin" id="linkedin" value="{{ old('linkedin', $user->linkedin ?? '') }}" placeholder="https://linkedin.com/in/username" class="w-full rounded-xl border border-blue-600/30 bg-slate-800/80 px-4 py-3 font-mono text-white placeholder-slate-600 transition-all focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                                </div>
                            </div>

                            <!-- Instagram -->
                            <div class="group relative">
                                <div class="absolute -left-4 top-0 h-12 w-1 rounded-full bg-pink-500/30 group-hover:bg-pink-400/50 transition-colors"></div>
                                <label for="instagram" class="mb-2 block">
                                    <span class="flex items-center gap-2 font-mono text-xs font-semibold uppercase tracking-wider text-pink-400/80">
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                        </svg>
                                        Instagram
                                    </span>
                                </label>
                                <div class="relative">
                                    <input type="url" name="instagram" id="instagram" value="{{ old('instagram', $user->instagram ?? '') }}" placeholder="https://instagram.com/username" class="w-full rounded-xl border border-pink-500/30 bg-slate-800/80 px-4 py-3 font-mono text-white placeholder-slate-600 transition-all focus:border-pink-400 focus:outline-none focus:ring-2 focus:ring-pink-400/20">
                                </div>
                            </div>
                        </div>

                        <!-- Divider with glow -->
                        <div class="relative h-px bg-gradient-to-r from-transparent via-cyan-500/30 to-transparent">
                            <div class="absolute inset-0 h-px bg-gradient-to-r from-transparent via-cyan-400/50 to-transparent blur-sm"></div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-end gap-4">
                            <a href="{{ route('profil') }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-600/50 bg-slate-800/50 px-6 py-3 font-mono text-sm font-semibold text-slate-400 transition-all hover:border-slate-500 hover:text-white">
                                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Batal
                            </a>
                            <button type="submit" class="group/btn relative inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-cyan-600 to-cyan-500 px-8 py-3 font-mono text-sm font-semibold text-white shadow-[0_0_30px_rgba(0,212,255,0.3)] transition-all hover:shadow-[0_0_40px_rgba(0,212,255,0.5)] hover:scale-105">
                                <span class="absolute inset-0 rounded-xl bg-gradient-to-r from-cyan-400/0 to-cyan-300/0 transition-all group-hover/btn:from-cyan-400/10 group-hover/btn:to-cyan-300/10"></span>
                                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                                Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="mt-6 rounded-xl border border-rose-500/30 bg-rose-500/10 p-4 shadow-[0_0_30px_rgba(239,68,68,0.2)]">
                    <div class="flex items-center gap-3">
                        <svg class="h-6 w-6 text-rose-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm2 0a1 1 0 11-2 0 1 1 0 012 0zm-3 3a1 1 0 011-1h2a1 1 0 110 2H8a1 1 0 011-1z" clip-rule="evenodd"/>
                        </svg>
                        <p class="font-mono text-sm text-rose-300">{{ $errors->first() }}</p>
                    </div>
                </div>
            @endif

            <!-- Success Message -->
            @if(session('success'))
                <div class="mt-6 rounded-xl border border-emerald-500/30 bg-emerald-500/10 p-4 shadow-[0_0_30px_rgba(16,185,129,0.2)]">
                    <div class="flex items-center gap-3">
                        <svg class="h-6 w-6 text-emerald-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <p class="font-mono text-sm text-emerald-300">{{ session('success') }}</p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Bottom Glow -->
        <div class="mx-auto h-px w-full max-w-4xl bg-gradient-to-r from-transparent via-cyan-500/30 to-transparent"></div>
    </main>
</x-layouts.app>