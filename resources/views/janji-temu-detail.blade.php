<x-layouts.app title="Detail Janji Temu - SILATAR">

    <main class="neo-mirai min-h-screen bg-[var(--paper)] pt-16 lg:pt-20">
        <div class="mx-auto max-w-2xl px-6 py-8 lg:px-8">
            {{-- Header --}}
            <div class="neo-card mb-6 p-6 relative overflow-hidden">
                <div class="flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-center gap-5">
                        <div class="w-16 h-16 bg-[var(--gold)] rounded-2xl flex items-center justify-center shadow-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-9 h-9 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl font-extrabold text-[var(--ink)] tracking-tight">
                                Detail <span class="text-[var(--gold)]">Janji Temu</span>
                            </h1>
                            <p class="text-sm text-[var(--ink-soft)] mt-1">Informasi lengkap janji temu Anda</p>
                        </div>
                    </div>
                    <a href="{{ route('janji-temu-history') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-[var(--paper-soft)] hover:bg-[var(--line)] border border-[var(--line)] text-[var(--ink)] font-semibold text-sm rounded-xl transition-all duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali
                    </a>
                </div>
            </div>

            {{-- Status Badge --}}
            @php
                $statusColor = match($janjiTemu->status) {
                    'APPOINTMENT' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                    'PENDING' => 'bg-blue-100 text-blue-800 border-blue-200',
                    'APPROVED' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                    'REJECTED' => 'bg-red-100 text-red-800 border-red-200',
                    'CANCELLED' => 'bg-gray-100 text-gray-800 border-gray-200',
                    default => 'bg-gray-100 text-gray-800 border-gray-200',
                };

                $statusLabel = match($janjiTemu->status) {
                    'APPOINTMENT' => 'Menunggu Konfirmasi',
                    'PENDING' => 'Menunggu',
                    'APPROVED' => 'Disetujui',
                    'REJECTED' => 'Ditolak',
                    'CANCELLED' => 'Dibatalkan',
                    default => $janjiTemu->status,
                };
            @endphp

            <div class="mb-6 p-4 neo-card border-l-4 {{ str_contains($statusColor, 'emerald') ? 'border-emerald-500' : (str_contains($statusColor, 'red') ? 'border-red-500' : (str_contains($statusColor, 'yellow') ? 'border-yellow-500' : 'border-[var(--gold)]')) }}">
                <div class="flex items-center gap-3">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold {{ $statusColor }}">
                        {{ $statusLabel }}
                    </span>
                    @if(in_array($janjiTemu->status, ['APPROVED']))
                        <span class="text-sm text-emerald-600">✓ Janji temu telah disetujui</span>
                    @endif
                </div>
            </div>

            {{-- Detail Card --}}
            <div class="neo-card p-6 sm:p-8 relative overflow-hidden mb-6">
                <div class="space-y-6">
                    {{-- Waktu --}}
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-[var(--gold)]/10 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-[var(--gold)]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-[var(--ink-soft)] mb-1">Waktu Janji Temu</p>
                            <p class="text-xl font-bold text-[var(--ink)]">
                                {{ \Carbon\Carbon::parse($janjiTemu->waktu)->format('d M Y, H:i') }}
                            </p>
                        </div>
                    </div>

                    <hr class="border-[var(--line)]">

                    {{-- Target --}}
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-[var(--gold)]/10 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-[var(--gold)]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-[var(--ink-soft)] mb-1">Tujuan Pertemuan</p>
                            <p class="text-lg font-bold text-[var(--ink)]">{{ $targetNama }}</p>
                            <p class="text-sm text-[var(--ink-soft)]">{{ $targetDetail }}</p>
                        </div>
                    </div>

                    <hr class="border-[var(--line)]">

                    {{-- Keperluan --}}
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-[var(--gold)]/10 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-[var(--gold)]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-[var(--ink-soft)] mb-1">Keperluan / Alasan</p>
                            <p class="text-[var(--ink)] leading-relaxed">{{ $janjiTemu->tujuan }}</p>
                        </div>
                    </div>

                    @if($janjiTemu->komen)
                        <hr class="border-[var(--line)]">

                        {{-- Komentar --}}
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-[var(--gold)]/10 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-[var(--gold)]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-[var(--ink-soft)] mb-1">Keterangan / Komentar</p>
                                <p class="text-[var(--ink)] italic">"{{ $janjiTemu->komen }}"</p>
                                @if($staffNama !== '-')
                                    <p class="text-xs text-[var(--ink-soft)] mt-2">— {{ $staffNama }}</p>
                                @endif
                            </div>
                        </div>
                    @endif

                    <hr class="border-[var(--line)]">

                    {{-- Info Lainnya --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs font-semibold text-[var(--ink-soft)] mb-1">Asal</p>
                            <p class="text-sm font-medium text-[var(--ink)]">{{ $janjiTemu->asal ?: '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-[var(--ink-soft)] mb-1">Diajukan</p>
                            <p class="text-sm font-medium text-[var(--ink)]">{{ \Carbon\Carbon::parse($janjiTemu->created_at)->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            @if(in_array($janjiTemu->status, ['APPOINTMENT', 'PENDING']))
                <div class="neo-card p-6">
                    <h3 class="text-lg font-bold text-[var(--ink)] mb-4">Aksi</h3>
                    <form action="{{ route('janji-temu-cancel', $janjiTemu->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan janji temu ini?')">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label for="alasan" class="block text-sm font-semibold text-[var(--ink)] mb-2">Alasan Pembatalan (Opsional)</label>
                                <textarea
                                    name="alasan"
                                    id="alasan"
                                    class="w-full px-4 py-3 bg-[var(--paper-soft)] border border-[var(--line)] rounded-xl text-[var(--ink)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)] focus:border-transparent"
                                    rows="3"
                                    placeholder="Masukkan alasan pembatalan..."
                                >Dibatalkan oleh pengguna</textarea>
                            </div>
                            <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-6 py-3 bg-red-500 hover:bg-red-600 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition-all duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Batalkan Janji Temu
                            </button>
                        </div>
                    </form>
                </div>
            @endif
        </div>
    </main>
</x-layouts.app>
