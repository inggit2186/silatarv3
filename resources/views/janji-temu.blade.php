<x-layouts.app :title="'Janji Temu - ' . $deptName . ' - SILATAR'">

    <main class="neo-mirai min-h-screen bg-[var(--paper)] pt-16 lg:pt-20">
        <!-- Success Notification -->
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => { show = false }, 4000);" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-4" class="fixed top-24 right-4 z-50 w-80">
                <div class="neo-card border-gold">
                    <div class="neo-success-alert">
                        <div class="neo-success-alert-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div class="neo-success-alert-content">
                            <p class="neo-success-alert-label">Success</p>
                            <p class="neo-success-alert-title">Berhasil</p>
                            <p class="neo-success-alert-text">{{ session('success') }}</p>
                        </div>
                        <button @click="show = false" class="neo-success-alert-close">×</button>
                    </div>
                </div>
            </div>
        @endif

        <div class="mx-auto max-w-2xl px-6 py-8 lg:px-8">
            {{-- Header Banner --}}
            <div class="neo-card mb-6 p-6 relative overflow-hidden">
                <div class="flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">
                    <!-- Icon with glow effect -->
                    <div class="flex items-center gap-5">
                        <div class="relative">
                            <div class="w-16 h-16 bg-[var(--gold)] rounded-2xl flex items-center justify-center shadow-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-9 h-9 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 11v4m-2-2h4" stroke-width="2.5"/>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <span class="neo-badge-gold">NEW REQUEST</span>
                            </div>
                            <h1 class="text-2xl font-extrabold text-[var(--ink)] tracking-tight">
                                <span class="text-[var(--gold)]">Janji</span> Temu
                            </h1>
                            <p class="text-sm text-[var(--ink-soft)] mt-1 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[var(--gold)]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                {{ $deptName }}
                            </p>
                        </div>
                    </div>
                    <a href="{{ route('pelayanan') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-[var(--paper-soft)] hover:bg-[var(--line)] border border-[var(--line)] text-[var(--ink)] font-semibold text-sm rounded-xl transition-all duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali
                    </a>
                </div>
            </div>

            {{-- Appointment Form Card --}}
            <div class="neo-card p-6 sm:p-8 relative overflow-hidden">
                <!-- Header with icon -->
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-[var(--line)]">
                    <div class="w-10 h-10 bg-[var(--gold)]/10 border border-[var(--gold)]/30 rounded-xl flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[var(--gold)]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-[var(--ink)]">Form Pengajuan</h2>
                        <p class="text-xs text-[var(--ink-soft)]">Isi data di bawah untuk mengajukan janji temu</p>
                    </div>
                </div>

                <form action="{{ route('pelayanan.janji-temu.submit', ['deptId' => $deptId]) }}" method="POST" class="space-y-6 relative">
                    @csrf

                    @if ($targetData)
                        {{-- Target Person Card --}}
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-[var(--ink)] flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[var(--gold)]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Tujuan Pertemuan
                            </label>
                            <div class="neo-card border-gold p-4 flex items-center gap-4 hover:shadow-md transition-all duration-300">
                                <div class="w-16 h-16 bg-[var(--gold)] rounded-xl flex items-center justify-center flex-shrink-0 overflow-hidden shadow-lg">
                                    @if ($targetData['type'] === 'direct')
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                    @else
                                        @if (!empty($targetData['employee_photo']) && Str::startsWith($targetData['employee_photo'], 'http'))
                                            <img src="{{ $targetData['employee_photo'] }}" alt="{{ $targetData['employee_name'] }}" class="w-full h-full object-cover">
                                        @else
                                            <span class="text-xl font-bold text-white">
                                                {{ substr($targetData['employee_name'], 0, 2) }}
                                            </span>
                                        @endif
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-[var(--ink)] font-bold text-lg truncate">{{ $targetData['employee_name'] }}</p>
                                    <p class="text-[var(--ink-soft)] text-sm flex items-center gap-1">
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
                    <div class="space-y-2">
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
                            <p class="text-red-500 text-sm flex items-center gap-1 mt-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                        @error('jam')
                            <p class="text-red-500 text-sm flex items-center gap-1 mt-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Keterangan --}}
                    <div class="space-y-2">
                        <label for="keterangan" class="block text-sm font-semibold text-[var(--ink)] flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[var(--gold)]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Keperluan / Alasan Bertemu
                        </label>
                        <textarea
                            name="keterangan"
                            id="keterangan"
                            class="neo-form-textarea w-full"
                            rows="4"
                            placeholder="Jelaskan keperluan Anda..."
                            required
                        >{{ old('keterangan') }}</textarea>
                        @error('keterangan')
                            <p class="text-red-500 text-sm flex items-center gap-1 mt-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Info Box --}}
                    <div class="bg-[var(--gold)]/5 border border-[var(--gold)]/20 rounded-xl p-4">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 bg-[var(--gold)]/20 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[var(--gold)]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-[var(--gold)] mb-1">Informasi</p>
                                <p class="text-xs text-[var(--ink-soft)] leading-relaxed">
                                    Pastikan data yang Anda isi benar. Permintaan janji temu akan diproses oleh pihak yang dituju.
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Submit Button --}}
                    <div class="flex justify-end gap-4 pt-4 border-t border-[var(--line)]">
                        <a href="{{ route('pelayanan') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-[var(--paper-soft)] hover:bg-[var(--line)] border border-[var(--line)] text-[var(--ink)] font-semibold rounded-xl transition-all duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Batal
                        </a>
                        <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-[var(--gold)] hover:bg-[var(--gold-bright)] text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition-all duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                            </svg>
                            Ajukan Janji Temu
                        </button>
                    </div>
                </form>
            </div>

            {{-- Footer Info --}}
            <div class="mt-6 text-center">
                <p class="text-xs text-[var(--ink-soft)] flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[var(--gold)]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    Data Anda aman dan terlindungi
                </p>
            </div>
        </div>
    </main>
</x-layouts.app>
