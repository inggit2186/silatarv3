<x-layouts.app :title="'Janji Temu - ' . $deptName . ' - SILATAR'">

    <main class="relative cyber-bg pt-20 lg:pt-24">
        <!-- Success Notification -->
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-[-100%]" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-[-100%]" class="fixed top-0 left-0 right-0 z-50 px-4 py-3">
                <div class="mx-auto max-w-2xl">
                    <div class="relative bg-gradient-to-r from-emerald-500 via-cyan-500 to-emerald-500 p-4 rounded-xl shadow-[0_0_40px_rgba(16,185,129,0.5)] overflow-hidden">
                        <!-- Animated background lines -->
                        <div class="absolute inset-0 opacity-20">
                            <div class="absolute inset-0 bg-[linear-gradient(90deg,transparent,rgba(255,255,255,0.4),transparent)] animate-[shimmer_2s_infinite]" style="background-size:200%_100%;"></div>
                        </div>
                        <div class="relative flex items-center gap-4">
                            <div class="flex-shrink-0 relative">
                                <div class="absolute inset-0 bg-emerald-300 blur-xl opacity-50 rounded-full"></div>
                                <div class="relative w-14 h-14 bg-white rounded-full flex items-center justify-center shadow-lg">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                <p class="text-white font-bold text-lg tracking-wide">Berhasil!</p>
                                <p class="text-emerald-100 text-sm">{{ session('success') }}</p>
                            </div>
                            <button @click="show = false" class="flex-shrink-0 w-8 h-8 bg-white/20 hover:bg-white/30 rounded-full flex items-center justify-center transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        <!-- Progress bar -->
                        <div class="absolute bottom-0 left-0 right-0 h-1 bg-emerald-900/20">
                            <div class="h-full bg-white/50 animate-[progress_3s_linear]" x-data="{ progress: 0 }" x-init="
                                setTimeout(() => { progress = 100; }, 50);
                                setTimeout(() => { show = false; }, 3000);
                            " :style="'width: ' + progress + '%'"></div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

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

        <div class="relative mx-auto max-w-2xl px-6 py-8 lg:px-8">
            {{-- Cyber Header Banner --}}
            <div class="cyber-card mb-6 overflow-hidden">
                <!-- Neon glow line -->
                <div class="absolute top-0 left-0 right-0 h-[2px] bg-gradient-to-r from-transparent via-cyan-400 to-transparent animate-pulse"></div>

                <div class="flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between relative">
                    <!-- Icon with glow effect -->
                    <div class="flex items-center gap-5">
                        <div class="relative">
                            <div class="absolute inset-0 bg-cyan-400 blur-xl opacity-30 rounded-2xl"></div>
                            <div class="relative w-16 h-16 bg-gradient-to-br from-cyan-500 to-blue-600 rounded-2xl flex items-center justify-center shadow-[0_0_30px_rgba(0,212,255,0.4)]">
                                <!-- Calendar Icon -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-9 h-9 text-slate-900" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 11v4m-2-2h4" stroke-width="2.5"/>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <span class="cyber-badge">NEW REQUEST</span>
                            </div>
                            <h1 class="text-2xl font-extrabold text-white tracking-tight">
                                <span class="text-cyan-400">Janji</span> Temu
                            </h1>
                            <p class="text-sm text-slate-400 mt-1 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-cyan-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                {{ $deptName }}
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

            {{-- Appointment Form Card --}}
            <div class="cyber-card relative overflow-hidden">
                <!-- Corner decorations -->
                <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-bl from-cyan-500/10 to-transparent rounded-bl-full"></div>
                <div class="absolute bottom-0 left-0 w-32 h-32 bg-gradient-to-tr from-purple-500/10 to-transparent rounded-tr-full"></div>

                <!-- Header with icon -->
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-cyan-500/20">
                    <div class="w-10 h-10 bg-cyan-500/10 border border-cyan-500/30 rounded-xl flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-white">Form Pengajuan</h2>
                        <p class="text-xs text-slate-500">Isi data di bawah untuk mengajukan janji temu</p>
                    </div>
                </div>

                <form action="{{ route('pelayanan.janji-temu.submit', ['deptId' => $deptId]) }}" method="POST" class="space-y-6 relative">
                    @csrf

                    @if ($targetData)
                        {{-- Target Person Card --}}
                        <div class="cyber-appointment-target">
                            <label class="cyber-form-label flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-pink-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Tujuan Pertemuan
                            </label>
                            <div class="cyber-appointment-target-card group hover:border-cyan-400 transition-all duration-300">
                                <div class="cyber-appointment-target-photo">
                                    @if ($targetData['type'] === 'direct')
                                        <img src="{{ $targetData['employee_photo'] }}" alt="Seksi" class="w-full h-full object-contain p-1">
                                    @else
                                        @if (!empty($targetData['employee_photo']) && Str::startsWith($targetData['employee_photo'], 'http'))
                                            <img src="{{ $targetData['employee_photo'] }}" alt="{{ $targetData['employee_name'] }}" class="w-full h-full object-cover">
                                        @else
                                            <span class="text-xl font-bold text-cyber-dark">
                                                {{ substr($targetData['employee_name'], 0, 2) }}
                                            </span>
                                        @endif
                                    @endif
                                </div>
                                <div class="cyber-appointment-target-info">
                                    <p class="cyber-appointment-target-name">{{ $targetData['employee_name'] }}</p>
                                    <p class="cyber-appointment-target-role flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                        </svg>
                                        {{ $targetData['employee_role'] }}
                                    </p>
                                </div>
                                <input type="hidden" name="tipe" value="{{ $targetData['type'] }}">
                                <input type="hidden" name="nip_tujuan" value="{{ $targetData['type'] === 'direct' ? $deptId : $targetData['employee_nip'] }}">
                            </div>
                        </div>
                    @endif

                    {{-- Hidden fields --}}
                    <input type="hidden" name="kategori" value="APPOINTMENT">
                    <input type="hidden" name="dept_id" value="{{ $deptId }}">
                    <input type="hidden" name="asal_nip" value="{{ auth()->user()?->nomor_induk }}">
                    <input type="hidden" name="status" value="999">
                    <input type="hidden" name="onStaff" value="999">

                    {{-- Tanggal & Waktu Janji Temu --}}
                    <div class="cyber-form-group">
                        <x-ui.datetimepicker
                            name="janji_temu"
                            dateName="tanggal"
                            timeName="jam"
                            label="Tanggal & Waktu Janji Temu"
                            :dateValue="old('tanggal')"
                            :timeValue="old('jam')"
                            placeholder="Pilih tanggal & waktu"
                            :min="now()->toDateString()"
                            required
                        />
                        @error('tanggal')
                            <p class="cyber-form-error flex items-center gap-1 mt-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                        @error('jam')
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
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Keperluan / Alasan Bertemu
                        </label>
                        <div class="relative">
                            <textarea
                                name="keterangan"
                                id="keterangan"
                                class="cyber-form-textarea pl-12"
                                rows="4"
                                placeholder="Jelaskan keperluan Anda..."
                                required
                            >{{ old('keterangan') }}</textarea>
                            <div class="absolute left-4 top-4 pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-cyan-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                            </div>
                        </div>
                        @error('keterangan')
                            <p class="cyber-form-error flex items-center gap-1 mt-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Info Box --}}
                    <div class="bg-gradient-to-r from-cyan-500/5 to-purple-500/5 border border-cyan-500/20 rounded-xl p-4">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 bg-cyan-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-cyan-400 mb-1">Informasi</p>
                                <p class="text-xs text-slate-400 leading-relaxed">
                                    Pastikan data yang Anda isi benar. Permintaan janji temu akan diproses oleh pihak yang dituju.
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
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                            </svg>
                            Ajukan Janji Temu
                        </button>
                    </div>
                </form>
            </div>

            {{-- Footer Info --}}
            <div class="mt-6 text-center">
                <p class="text-xs text-slate-500 flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-cyan-500/50" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    Data Anda aman dan terlindungi
                </p>
            </div>
        </div>
    </main>
</x-layouts.app>