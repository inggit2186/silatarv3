<x-layouts.admin title="Manajemen Janji Temu - Admin SILATAR">

    <div class="space-y-6">
        {{-- Page Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Manajemen Janji Temu</h1>
                <p class="text-sm text-gray-500 mt-1">Kelola semua pengajuan janji temu dari pengguna</p>
            </div>
        </div>

        {{-- Stats --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            @php
                $totalAll = DB::table('ktd_bukutamu')->count();
                $totalPending = DB::table('ktd_bukutamu')->where('status', 'APPOINTMENT')->count();
                $totalApproved = DB::table('ktd_bukutamu')->where('status', 'APPROVED')->count();
                $totalRejected = DB::table('ktd_bukutamu')->where('status', 'REJECTED')->count();
            @endphp

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalAll }}</p>
                        <p class="text-xs text-gray-500">Total</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-yellow-600">{{ $totalPending }}</p>
                        <p class="text-xs text-gray-500">Menunggu</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-emerald-600">{{ $totalApproved }}</p>
                        <p class="text-xs text-gray-500">Disetujui</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-red-600">{{ $totalRejected }}</p>
                        <p class="text-xs text-gray-500">Ditolak</p>
                    </div>
                </div>
            </div>
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

        {{-- Table --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Pengaju</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Waktu</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tujuan</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($janjiTemuList as $item)
                            @php
                                $statusColor = match($item->status) {
                                    'APPOINTMENT' => 'bg-yellow-100 text-yellow-800',
                                    'PENDING' => 'bg-blue-100 text-blue-800',
                                    'APPROVED' => 'bg-emerald-100 text-emerald-800',
                                    'REJECTED' => 'bg-red-100 text-red-800',
                                    'CANCELLED' => 'bg-gray-100 text-gray-800',
                                    default => 'bg-gray-100 text-gray-800',
                                };

                                $statusLabel = match($item->status) {
                                    'APPOINTMENT' => 'Menunggu',
                                    'PENDING' => 'Menunggu',
                                    'APPROVED' => 'Disetujui',
                                    'REJECTED' => 'Ditolak',
                                    'CANCELLED' => 'Dibatalkan',
                                    default => $item->status,
                                };
                            @endphp

                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-mono text-gray-900">#{{ $item->id }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">{{ $item->nama }}</p>
                                        <p class="text-xs text-gray-500">{{ $item->asal ?: '-' }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <p class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($item->waktu)->format('d M Y') }}</p>
                                    <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($item->waktu)->format('H:i') }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm text-gray-900 max-w-xs truncate" title="{{ $item->tujuan }}">
                                        {{ Str::limit($item->tujuan, 30) }}
                                    </p>
                                    <p class="text-xs text-gray-500">{{ $item->tipe === 'asn' ? '→ Pegawai' : '→ Seksi' }}</p>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $statusColor }}">
                                        {{ $statusLabel }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('admin.janji-temu.detail', $item->id) }}" class="text-blue-600 hover:text-blue-900 font-semibold">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                        <p class="text-gray-500 font-medium">Tidak ada data janji temu</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($janjiTemuList->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    {{ $janjiTemuList->links() }}
                </div>
            @endif
        </div>
    </div>

</x-layouts.admin>
