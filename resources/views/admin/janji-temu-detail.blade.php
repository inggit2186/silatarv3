<x-layouts.admin title="Detail Janji Temu - Admin SILATAR">

    <div class="space-y-6">
        {{-- Page Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Detail Janji Temu #{{ $janjiTemu->id }}</h1>
                <p class="text-sm text-gray-500 mt-1">Informasi lengkap dan proses pengajuan janji temu</p>
            </div>
            <a href="{{ route('admin.janji-temu') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-sm rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
        </div>

        {{-- Success/Error Message --}}
        @if(session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 bg-red-50 border border-red-200 rounded-xl text-red-700">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main Info --}}
            <div class="lg:col-span-2 space-y-6">
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

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center gap-3 mb-6">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold {{ $statusColor }}">
                            {{ $statusLabel }}
                        </span>
                        @if($janjiTemu->onStaff && $janjiTemu->onStaff != 999)
                            <span class="text-sm text-gray-500">Ditangani oleh: <strong>{{ $staffNama }}</strong></span>
                        @endif
                    </div>

                    <div class="space-y-6">
                        {{-- Pengaju --}}
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-500 mb-1">Pengaju</p>
                                <p class="text-lg font-bold text-gray-900">{{ $janjiTemu->nama }}</p>
                                <p class="text-sm text-gray-500">NIP: {{ $janjiTemu->nomor_induk }}</p>
                                <p class="text-sm text-gray-500">Asal: {{ $janjiTemu->asal ?: '-' }}</p>
                            </div>
                        </div>

                        <hr class="border-gray-200">

                        {{-- Waktu --}}
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-500 mb-1">Waktu Janji Temu</p>
                                <p class="text-xl font-bold text-gray-900">
                                    {{ \Carbon\Carbon::parse($janjiTemu->waktu)->format('d M Y, H:i') }}
                                </p>
                            </div>
                        </div>

                        <hr class="border-gray-200">

                        {{-- Target --}}
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-500 mb-1">Tujuan Pertemuan</p>
                                <p class="text-lg font-bold text-gray-900">{{ $targetNama }}</p>
                                <p class="text-sm text-gray-500">{{ $targetDetail }}</p>
                                @if($targetTelp)
                                    <p class="text-sm text-gray-500">Telp: {{ $targetTelp }}</p>
                                @endif
                            </div>
                        </div>

                        <hr class="border-gray-200">

                        {{-- Keperluan --}}
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-500 mb-1">Keperluan / Alasan</p>
                                <p class="text-gray-900 leading-relaxed">{{ $janjiTemu->tujuan }}</p>
                            </div>
                        </div>

                        @if($janjiTemu->komen)
                            <hr class="border-gray-200">

                            {{-- Komentar --}}
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-500 mb-1">Komentar / Keterangan</p>
                                    <p class="text-gray-900 italic">"{{ $janjiTemu->komen }}"</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Sidebar - Actions --}}
            <div class="space-y-6">
                {{-- Info Box --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Informasi</h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">ID:</span>
                            <span class="font-semibold text-gray-900">#{{ $janjiTemu->id }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Dibuat:</span>
                            <span class="font-semibold text-gray-900">{{ \Carbon\Carbon::parse($janjiTemu->created_at)->format('d M Y H:i') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Update:</span>
                            <span class="font-semibold text-gray-900">{{ \Carbon\Carbon::parse($janjiTemu->updated_at)->format('d M Y H:i') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                @if(in_array($janjiTemu->status, ['APPOINTMENT', 'PENDING']))
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Aksi</h3>
                        <div class="space-y-3">
                            {{-- Approve --}}
                            <form action="{{ route('admin.janji-temu.approve', $janjiTemu->id) }}" method="POST" onsubmit="return confirm('Setujui janji temu ini?')">
                                @csrf
                                <div class="space-y-3">
                                    <div>
                                        <label for="komen_approve" class="block text-sm font-semibold text-gray-700 mb-1">Keterangan (Opsional)</label>
                                        <input
                                            type="text"
                                            name="komen"
                                            id="komen_approve"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                                            placeholder="Disetujui oleh petugas"
                                            value="Disetujui oleh petugas"
                                        >
                                    </div>
                                    <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-lg shadow-sm transition-colors">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        Setujui
                                    </button>
                                </div>
                            </form>

                            <hr class="border-gray-200">

                            {{-- Reject --}}
                            <form action="{{ route('admin.janji-temu.reject', $janjiTemu->id) }}" method="POST" onsubmit="return confirm('Tolak janji temu ini?')">
                                @csrf
                                <div class="space-y-3">
                                    <div>
                                        <label for="komen_reject" class="block text-sm font-semibold text-gray-700 mb-1">Alasan Penolakan <span class="text-red-500">*</span></label>
                                        <textarea
                                            name="komen"
                                            id="komen_reject"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                            rows="3"
                                            placeholder="Masukkan alasan penolakan..."
                                            required
                                        ></textarea>
                                    </div>
                                    <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-red-600 hover:bg-red-700 text-white font-bold rounded-lg shadow-sm transition-colors">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                        Tolak
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Status</h3>
                        <p class="text-sm text-gray-500">
                            Janji temu ini sudah dalam status <strong>{{ $statusLabel }}</strong> dan tidak dapat diproses lagi.
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>

</x-layouts.admin>
