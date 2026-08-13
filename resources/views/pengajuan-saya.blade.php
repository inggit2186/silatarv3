<x-layouts.app title="Pengajuan Saya - SILATAR">
    <main class="neo-mirai min-h-screen bg-[var(--paper)]">

        <!-- Hero Section -->
        <section class="hero-page bg-cover bg-center" style="background-image: url('/assets/img/template/bg2.webp'); padding: 120px 2rem 4rem; min-height: 300px;">
            <div class="news-article-container article-hero">
                <p class="section-label-gold section-label-sm">Riwayat Pengajuan</p>
                <h1 class="article-hero-title">Pengajuan Saya</h1>
                <p class="article-hero-subtitle">Halaman ini menampilkan semua layanan yang pernah Anda ajukan, termasuk draft, pengajuan final, dan janji temu.</p>
                <div class="hero-actions">
                    <a href="{{ route('pelayanan') }}" class="neo-hero-cta neo-hero-cta-primary">Ajukan layanan baru</a>
                    <a href="{{ url('/') }}" class="neo-hero-cta">Kembali ke beranda</a>
                </div>
            </div>
        </section>

        <!-- Content -->
        <section class="page-content px-6 py-8 lg:px-8">
            @if (session('success'))
                <div class="max-w-6xl mx-auto mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-700">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="max-w-6xl mx-auto mb-6 p-4 bg-red-50 border border-red-200 rounded-xl text-red-700">
                    {{ session('error') }}
                </div>
            @endif

            <div class="max-w-6xl mx-auto">
                {{-- Tabs Navigation --}}
                @php
                    $activeTab = request('tab', 'pengajuan');
                @endphp

                <div class="flex flex-wrap gap-2 mb-8 border-b border-[var(--line)] pb-4">
                    <a href="{{ route('pengajuan-saya', ['tab' => 'pengajuan']) }}"
                       class="inline-flex items-center gap-2 px-6 py-3 rounded-xl font-semibold transition-all duration-200 {{ $activeTab === 'pengajuan' ? 'bg-[var(--gold)] text-white shadow-lg' : 'bg-[var(--paper-soft)] text-[var(--ink)] hover:bg-[var(--line)]' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Pengajuan Layanan
                        @if(($summary['total'] - ($summary['janji_temu']['total'] ?? 0)) > 0)
                            <span class="px-2 py-0.5 text-xs bg-white/20 rounded-full">{{ $summary['total'] - ($summary['janji_temu']['total'] ?? 0) }}</span>
                        @endif
                    </a>
                    <a href="{{ route('pengajuan-saya', ['tab' => 'janji-temu']) }}"
                       class="inline-flex items-center gap-2 px-6 py-3 rounded-xl font-semibold transition-all duration-200 {{ $activeTab === 'janji-temu' ? 'bg-[var(--gold)] text-white shadow-lg' : 'bg-[var(--paper-soft)] text-[var(--ink)] hover:bg-[var(--line)]' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Janji Temu
                        @if(($summary['janji_temu']['total'] ?? 0) > 0)
                            <span class="px-2 py-0.5 text-xs bg-white/20 rounded-full">{{ $summary['janji_temu']['total'] }}</span>
                        @endif
                    </a>
                </div>

                {{-- Tab Content: Pengajuan Layanan --}}
                @if($activeTab === 'pengajuan')
                    {{-- Summary Cards --}}
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                        <div class="neo-card p-4 text-center">
                            <p class="text-sm font-semibold text-[var(--ink-soft)] mb-1">Total</p>
                            <p class="text-3xl font-bold text-[var(--ink)]">{{ $summary['total'] - ($summary['janji_temu']['total'] ?? 0) }}</p>
                        </div>
                        <div class="neo-card p-4 text-center border-l-4 border-yellow-400">
                            <p class="text-sm font-semibold text-[var(--ink-soft)] mb-1">Draft</p>
                            <p class="text-3xl font-bold text-yellow-600">{{ $summary['draft'] }}</p>
                        </div>
                        <div class="neo-card p-4 text-center border-l-4 border-blue-400">
                            <p class="text-sm font-semibold text-[var(--ink-soft)] mb-1">Diproses</p>
                            <p class="text-3xl font-bold text-blue-600">{{ $summary['pending'] + $summary['processed'] - (($summary['janji_temu']['appointment'] ?? 0) + ($summary['janji_temu']['pending'] ?? 0) + ($summary['janji_temu']['approved'] ?? 0)) }}</p>
                        </div>
                        <div class="neo-card p-4 text-center border-l-4 border-emerald-400">
                            <p class="text-sm font-semibold text-[var(--ink-soft)] mb-1">Selesai</p>
                            <p class="text-3xl font-bold text-emerald-600">{{ $summary['done'] - (($summary['janji_temu']['rejected'] ?? 0) + ($summary['janji_temu']['cancelled'] ?? 0)) }}</p>
                        </div>
                    </div>

                    {{-- Request List --}}
                    @if ($requests->count() === 0)
                        <div class="neo-card p-12 text-center">
                            <div class="w-20 h-20 bg-[var(--gold)]/10 rounded-full flex items-center justify-center mx-auto mb-6">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-[var(--gold)]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-[var(--ink)] mb-2">Belum ada pengajuan</h3>
                            <p class="text-[var(--ink-soft)] mb-6">Saat Anda menyimpan draft atau mengirim layanan, datanya akan tampil di sini.</p>
                            <a href="{{ route('pelayanan') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-[var(--gold)] hover:bg-[var(--gold-bright)] text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition-all duration-200">
                                Mulai pengajuan
                            </a>
                        </div>
                    @else
                        <div class="space-y-2">
                            @foreach ($requests as $request)
                                @php
                                    $statusMeta = match ($request->status) {
                                        'DRAFT' => ['label' => 'Draft', 'class' => 'bg-yellow-100 text-yellow-800 border-yellow-200'],
                                        'UNCHECK', 'PENDING' => ['label' => 'Pending', 'class' => 'bg-blue-100 text-blue-800 border-blue-200'],
                                        'SUBMITTED', 'DITERIMA', 'DIPROSES' => ['label' => 'Diproses', 'class' => 'bg-blue-100 text-blue-800 border-blue-200'],
                                        'SUKSES' => ['label' => 'Sukses', 'class' => 'bg-emerald-100 text-emerald-800 border-emerald-200'],
                                        'DITOLAK' => ['label' => 'Ditolak', 'class' => 'bg-red-100 text-red-800 border-red-200'],
                                        'BATAL' => ['label' => 'Batal', 'class' => 'bg-gray-100 text-gray-800 border-gray-200'],
                                        default => ['label' => $request->status, 'class' => 'bg-gray-100 text-gray-800 border-gray-200'],
                                    };

                                    $isTpg = !empty($request->tipe) && in_array($request->tipe, ['PAIS-TPG-SEMESTER', 'PAIS-TPG-BULANAN', 'PENMAD-TPG-BULANAN', 'PENMAD-PENGAWAS-BULANAN']);

                                    // Determine edit route based on tipe
                                    $editRoute = match($request->tipe) {
                                        'PAIS-TPG-SEMESTER' => route('pelayanan.tpg.form', $request->id),
                                        'PAIS-TPG-BULANAN' => route('pelayanan.tpg-bulanan.form', $request->id),
                                        'PENMAD-TPG-BULANAN' => route('pelayanan.penmad-tpg-bulanan.form', $request->id),
                                        'PENMAD-PENGAWAS-BULANAN' => route('pelayanan.penmad-pengawas-bulanan.form', $request->id),
                                        default => route('pengajuan-saya.edit', $request->id),
                                    };
                                @endphp

                                <div class="group bg-white border border-[var(--line)] rounded-xl p-4 hover:border-[var(--gold)] hover:shadow-md transition-all duration-200 cursor-pointer">
                                    <div class="flex items-center gap-4">
                                        {{-- Icon --}}
                                        <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0 {{ str_contains($statusMeta['class'], 'emerald') ? 'bg-emerald-50 text-emerald-600' : (str_contains($statusMeta['class'], 'red') ? 'bg-red-50 text-red-600' : (str_contains($statusMeta['class'], 'yellow') ? 'bg-yellow-50 text-yellow-600' : 'bg-blue-50 text-blue-600')) }}">
                                            @if($isTpg)
                                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                                </svg>
                                            @else
                                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                            @endif
                                        </div>

                                        {{-- Content --}}
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-2 mb-1">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider {{ $statusMeta['class'] }}">
                                                    {{ $statusMeta['label'] }}
                                                </span>
                                                @if($isTpg)
                                                    <span class="text-[10px] font-semibold text-[var(--gold)] bg-[var(--gold)]/10 px-1.5 py-0.5 rounded">TPG</span>
                                                @endif
                                            </div>
                                            <h3 class="text-sm font-bold text-[var(--ink)] truncate group-hover:text-[var(--gold)] transition-colors">{{ $request->layanan_name }}</h3>
                                            <div class="flex items-center gap-3 text-[11px] text-[var(--ink-soft)] mt-1">
                                                <span class="flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" /></svg>
                                                    {{ Str::limit($request->no_req, 20) }}
                                                </span>
                                                <span class="w-1 h-1 bg-[var(--line)] rounded-full"></span>
                                                <span class="flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                                    {{ \Carbon\Carbon::parse($request->created_at)->format('d M Y') }}
                                                </span>
                                                @if($request->file_count > 0)
                                                    <span class="w-1 h-1 bg-[var(--line)] rounded-full"></span>
                                                    <span class="flex items-center gap-1">
                                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" /></svg>
                                                        {{ $request->file_count }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        {{-- Actions --}}
                                        <div class="flex items-center gap-1 flex-shrink-0">
                                            @if($request->status === 'DRAFT')
                                                <a href="{{ $editRoute }}" class="p-2 bg-[var(--gold)] hover:bg-[var(--gold-bright)] text-white rounded-lg transition-colors" title="Edit Draft">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                                </a>
                                                <form action="{{ route('pengajuan-saya.delete', $request->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengajuan ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-2 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg transition-colors" title="Hapus Draft">
                                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                    </button>
                                                </form>
                                            @endif
                                            <a href="{{ $editRoute }}" class="p-2 bg-[var(--paper-soft)] hover:bg-[var(--line)] text-[var(--ink-soft)] hover:text-[var(--ink)] rounded-lg transition-colors" title="Lihat Detail">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-8">
                            {{ $requests->links() }}
                        </div>
                    @endif

                {{-- Tab Content: Janji Temu --}}
                @elseif($activeTab === 'janji-temu')
                    {{-- Summary Cards --}}
                    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
                        <div class="neo-card p-4 text-center">
                            <p class="text-sm font-semibold text-[var(--ink-soft)] mb-1">Total</p>
                            <p class="text-3xl font-bold text-[var(--ink)]">{{ $summary['janji_temu']['total'] }}</p>
                        </div>
                        <div class="neo-card p-4 text-center border-l-4 border-yellow-400">
                            <p class="text-sm font-semibold text-[var(--ink-soft)] mb-1">Menunggu</p>
                            <p class="text-3xl font-bold text-yellow-600">{{ $summary['janji_temu']['appointment'] + $summary['janji_temu']['pending'] }}</p>
                        </div>
                        <div class="neo-card p-4 text-center border-l-4 border-emerald-400">
                            <p class="text-sm font-semibold text-[var(--ink-soft)] mb-1">Disetujui</p>
                            <p class="text-3xl font-bold text-emerald-600">{{ $summary['janji_temu']['approved'] }}</p>
                        </div>
                        <div class="neo-card p-4 text-center border-l-4 border-red-400">
                            <p class="text-sm font-semibold text-[var(--ink-soft)] mb-1">Ditolak</p>
                            <p class="text-3xl font-bold text-red-600">{{ $summary['janji_temu']['rejected'] }}</p>
                        </div>
                        <div class="neo-card p-4 text-center border-l-4 border-gray-400">
                            <p class="text-sm font-semibold text-[var(--ink-soft)] mb-1">Dibatalkan</p>
                            <p class="text-3xl font-bold text-gray-600">{{ $summary['janji_temu']['cancelled'] }}</p>
                        </div>
                    </div>

                    {{-- Janji Temu List --}}
                    @if ($janjiTemuList->count() === 0)
                        <div class="neo-card p-12 text-center">
                            <div class="w-20 h-20 bg-[var(--gold)]/10 rounded-full flex items-center justify-center mx-auto mb-6">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-[var(--gold)]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-[var(--ink)] mb-2">Belum ada janji temu</h3>
                            <p class="text-[var(--ink-soft)] mb-6">Anda belum pernah mengajukan janji temu.</p>
                            <a href="{{ route('pelayanan') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-[var(--gold)] hover:bg-[var(--gold-bright)] text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition-all duration-200">
                                Ajukan Janji Temu
                            </a>
                        </div>
                    @else
                        <div class="space-y-2">
                            @foreach($janjiTemuList as $item)
                                @php
                                    $statusColor = match($item->status) {
                                        'APPOINTMENT' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                        'PENDING' => 'bg-blue-100 text-blue-800 border-blue-200',
                                        'APPROVED' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                        'REJECTED' => 'bg-red-100 text-red-800 border-red-200',
                                        'CANCELLED' => 'bg-gray-100 text-gray-800 border-gray-200',
                                        default => 'bg-gray-100 text-gray-800 border-gray-200',
                                    };

                                    $statusLabel = match($item->status) {
                                        'APPOINTMENT' => 'Menunggu Konfirmasi',
                                        'PENDING' => 'Menunggu',
                                        'APPROVED' => 'Disetujui',
                                        'REJECTED' => 'Ditolak',
                                        'CANCELLED' => 'Dibatalkan',
                                        default => $item->status,
                                    };

                                    $borderColor = match($item->status) {
                                        'APPROVED' => 'border-emerald-500',
                                        'REJECTED' => 'border-red-500',
                                        'CANCELLED' => 'border-gray-400',
                                        default => 'border-[var(--gold)]',
                                    };
                                @endphp

                                <a href="{{ route('janji-temu-detail', $item->id) }}" class="group block bg-white border border-[var(--line)] rounded-xl p-4 hover:border-[var(--gold)] hover:shadow-md transition-all duration-200">
                                    <div class="flex items-center gap-4">
                                        {{-- Icon --}}
                                        <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0 {{ str_contains($statusColor, 'emerald') ? 'bg-emerald-50 text-emerald-600' : (str_contains($statusColor, 'red') ? 'bg-red-50 text-red-600' : (str_contains($statusColor, 'yellow') ? 'bg-yellow-50 text-yellow-600' : 'bg-blue-50 text-blue-600')) }}">
                                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                            </svg>
                                        </div>

                                        {{-- Content --}}
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-2 mb-1">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider {{ $statusColor }}">
                                                    {{ $statusLabel }}
                                                </span>
                                                @if($item->tipe === 'asn')
                                                    <span class="text-[10px] font-semibold text-[var(--gold)] bg-[var(--gold)]/10 px-1.5 py-0.5 rounded flex items-center gap-1">
                                                        <svg class="w-2.5 h-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                                        Pegawai
                                                    </span>
                                                @else
                                                    <span class="text-[10px] font-semibold text-blue-600 bg-blue-50 px-1.5 py-0.5 rounded flex items-center gap-1">
                                                        <svg class="w-2.5 h-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                                                        Seksi
                                                    </span>
                                                @endif
                                            </div>
                                            <h3 class="text-sm font-bold text-[var(--ink)] truncate group-hover:text-[var(--gold)] transition-colors">
                                                {{ \Carbon\Carbon::parse($item->waktu)->format('d M Y, H:i') }}
                                            </h3>
                                            <div class="flex items-center gap-3 text-[11px] text-[var(--ink-soft)] mt-1">
                                                <span class="flex items-center gap-1 truncate">
                                                    <svg class="w-3 h-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                                    {{ Str::limit($item->tujuan, 30) }}
                                                </span>
                                                @if($item->komen)
                                                    <span class="w-1 h-1 bg-[var(--line)] rounded-full flex-shrink-0"></span>
                                                    <span class="flex items-center gap-1 italic truncate">
                                                        <svg class="w-3 h-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
                                                        {{ Str::limit($item->komen, 20) }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        {{-- Arrow --}}
                                        <div class="flex items-center text-[var(--gold)] opacity-0 group-hover:opacity-100 transition-opacity">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                            </svg>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>

                        <div class="mt-8">
                            {{ $janjiTemuList->links() }}
                        </div>
                    @endif
                @endif
            </div>
        </section>
    </main>
</x-layouts.app>
