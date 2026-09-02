<x-layouts.app title="Pengajuan Saya - SILATAR">
    <main class="neo-mirai min-h-screen bg-[var(--paper)]">

        <!-- Hero Section -->
        <section class="hero-page bg-cover bg-center" style="background-image: url('/assets/img/template/bg2.webp'); padding: 100px 2rem 3rem; min-height: 250px;">
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
                <div class="max-w-6xl mx-auto mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-700 flex items-center gap-3">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="max-w-6xl mx-auto mb-6 p-4 bg-red-50 border border-red-200 rounded-xl text-red-700 flex items-center gap-3">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    {{ session('error') }}
                </div>
            @endif

            <div class="max-w-6xl mx-auto">
                {{-- Tabs Navigation --}}
                @php
                    $activeTab = request('tab', 'pengajuan');
                @endphp

                <div class="flex flex-wrap gap-2 mb-6 border-b border-[var(--line)] pb-4">
                    <a href="{{ route('pengajuan-saya', ['tab' => 'pengajuan']) }}"
                       class="inline-flex items-center gap-2 px-6 py-3 rounded-xl font-semibold transition-all duration-200 {{ $activeTab === 'pengajuan' ? 'bg-[var(--gold)] text-white shadow-lg' : 'bg-[var(--paper-soft)] text-[var(--ink)] hover:bg-[var(--line)]' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Pengajuan Layanan
                        @if(($summary['total'] - ($summary['janji_temu']['total'] ?? 0)) > 0)
                            <span class="px-2.5 py-0.5 text-xs bg-white/20 rounded-full">{{ $summary['total'] - ($summary['janji_temu']['total'] ?? 0) }}</span>
                        @endif
                    </a>
                    <a href="{{ route('pengajuan-saya', ['tab' => 'janji-temu']) }}"
                       class="inline-flex items-center gap-2 px-6 py-3 rounded-xl font-semibold transition-all duration-200 {{ $activeTab === 'janji-temu' ? 'bg-[var(--gold)] text-white shadow-lg' : 'bg-[var(--paper-soft)] text-[var(--ink)] hover:bg-[var(--line)]' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Janji Temu
                        @if(($summary['janji_temu']['total'] ?? 0) > 0)
                            <span class="px-2.5 py-0.5 text-xs bg-white/20 rounded-full">{{ $summary['janji_temu']['total'] }}</span>
                        @endif
                    </a>
                </div>

                {{-- Tab Content: Pengajuan Layanan --}}
                @if($activeTab === 'pengajuan')
                    {{-- Summary Cards --}}
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                        <div class="neo-card p-4 text-center">
                            <p class="text-sm font-semibold text-[var(--ink-soft)] mb-1">Total Pengajuan</p>
                            <p class="text-3xl font-bold text-[var(--ink)]">{{ $summary['total'] - ($summary['janji_temu']['total'] ?? 0) }}</p>
                        </div>
                        <div class="neo-card p-4 text-center border-l-4 border-yellow-400">
                            <p class="text-sm font-semibold text-[var(--ink-soft)] mb-1">Draft</p>
                            <p class="text-3xl font-bold text-yellow-600">{{ $summary['draft'] }}</p>
                            <p class="text-[10px] text-yellow-500 mt-1">Belum dikirim</p>
                        </div>
                        <div class="neo-card p-4 text-center border-l-4 border-blue-400">
                            <p class="text-sm font-semibold text-[var(--ink-soft)] mb-1">Diproses</p>
                            <p class="text-3xl font-bold text-blue-600">{{ $summary['pending'] + $summary['processed'] - (($summary['janji_temu']['appointment'] ?? 0) + ($summary['janji_temu']['pending'] ?? 0) + ($summary['janji_temu']['approved'] ?? 0)) }}</p>
                            <p class="text-[10px] text-blue-500 mt-1">Sedang diproses</p>
                        </div>
                        <div class="neo-card p-4 text-center border-l-4 border-emerald-400">
                            <p class="text-sm font-semibold text-[var(--ink-soft)] mb-1">Selesai</p>
                            <p class="text-3xl font-bold text-emerald-600">{{ $summary['done'] - (($summary['janji_temu']['rejected'] ?? 0) + ($summary['janji_temu']['cancelled'] ?? 0)) }}</p>
                            <p class="text-[10px] text-emerald-500 mt-1">Diterima/Ditolak</p>
                        </div>
                    </div>

                    {{-- Filter Periode --}}
                    <div class="bg-white border border-[var(--line)] rounded-xl p-4 mb-6">
                        <div class="flex flex-wrap items-center gap-4">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-[var(--ink-soft)]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" /></svg>
                                <span class="text-sm font-semibold text-[var(--ink)]">Filter Tahun:</span>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                @php
                                    $currentYear = request('year', now()->format('Y'));
                                    $years = collect();
                                    for ($i = 0; $i < 5; $i++) {
                                        $year = now()->subYears($i)->format('Y');
                                        $years->push($year);
                                    }
                                @endphp
                                <a href="{{ route('pengajuan-saya', ['tab' => 'pengajuan']) }}"
                                   class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ empty(request('year')) ? 'bg-[var(--gold)] text-white' : 'bg-[var(--paper-soft)] text-[var(--ink)] hover:bg-[var(--line)]' }}">
                                    Semua
                                </a>
                                @foreach($years as $year)
                                    <a href="{{ route('pengajuan-saya', ['tab' => 'pengajuan', 'year' => $year]) }}"
                                       class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ $currentYear === $year ? 'bg-[var(--gold)] text-white' : 'bg-[var(--paper-soft)] text-[var(--ink)] hover:bg-[var(--line)]' }}">
                                        {{ $year }}
                                    </a>
                                @endforeach
                            </div>
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
                        <div class="space-y-3">
                            @foreach ($requests as $request)
                                @php
                                    $statusMeta = match ($request->status) {
                                        'DRAFT' => ['label' => 'Draft', 'class' => 'bg-yellow-100 text-yellow-800 border-yellow-200', 'icon' => 'bg-yellow-50 text-yellow-600'],
                                        'UNCHECK', 'PENDING' => ['label' => 'Pending', 'class' => 'bg-blue-100 text-blue-800 border-blue-200', 'icon' => 'bg-blue-50 text-blue-600'],
                                        'SUBMITTED', 'DITERIMA', 'DIPROSES' => ['label' => 'Diproses', 'class' => 'bg-blue-100 text-blue-800 border-blue-200', 'icon' => 'bg-blue-50 text-blue-600'],
                                        'SUKSES' => ['label' => 'Selesai', 'class' => 'bg-emerald-100 text-emerald-800 border-emerald-200', 'icon' => 'bg-emerald-50 text-emerald-600'],
                                        'DITOLAK' => ['label' => 'Ditolak', 'class' => 'bg-red-100 text-red-800 border-red-200', 'icon' => 'bg-red-50 text-red-600'],
                                        'BATAL' => ['label' => 'Batal', 'class' => 'bg-gray-100 text-gray-800 border-gray-200', 'icon' => 'bg-gray-50 text-gray-600'],
                                        default => ['label' => $request->status, 'class' => 'bg-gray-100 text-gray-800 border-gray-200', 'icon' => 'bg-gray-50 text-gray-600'],
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

                                    $createdAt = \Carbon\Carbon::parse($request->created_at);
                                    $isToday = $createdAt->isToday();
                                    $isYesterday = $createdAt->isYesterday();
                                    $isThisYear = $createdAt->isCurrentYear();
                                @endphp

                                <a href="{{ $editRoute }}" class="group block bg-white border border-[var(--line)] rounded-xl p-4 hover:border-[var(--gold)] hover:shadow-md transition-all duration-200">
                                    <div class="flex items-start gap-4">
                                        {{-- Icon --}}
                                        <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0 {{ $statusMeta['icon'] }}">
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
                                            <div class="flex items-center gap-2 mb-1.5">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider {{ $statusMeta['class'] }}">
                                                    {{ $statusMeta['label'] }}
                                                </span>
                                                @if($isTpg)
                                                    <span class="text-[10px] font-semibold text-[var(--gold)] bg-[var(--gold)]/10 px-1.5 py-0.5 rounded">TPG</span>
                                                @endif
                                            </div>

                                            {{-- Title with Period for TPG --}}
                                            @if($isTpg && $request->periode)
                                                <h3 class="text-sm font-bold text-[var(--ink)] group-hover:text-[var(--gold)] transition-colors mb-2">
                                                    {{ $request->layanan_name }} <span class="text-[var(--gold)]">- {{ $request->periode }}</span>
                                                </h3>
                                            @else
                                                <h3 class="text-sm font-bold text-[var(--ink)] group-hover:text-[var(--gold)] transition-colors mb-2">{{ $request->layanan_name }}</h3>
                                            @endif

                                            {{-- Date/Time Info - More Prominent --}}
                                            <div class="flex items-center gap-2 text-xs flex-wrap">

                                                {{-- Created Date --}}
                                                <span class="flex items-center gap-1.5 px-2 py-1 bg-[var(--paper-soft)] rounded-lg">
                                                    <svg class="w-3.5 h-3.5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                    <span class="font-medium text-[var(--ink-soft)]">
                                                        @if($isToday)
                                                            Dibuat hari ini, {{ $createdAt->format('H:i') }}
                                                        @elseif($isYesterday)
                                                            Dibuat kemarin, {{ $createdAt->format('H:i') }}
                                                        @else
                                                            Dibuat {{ $createdAt->format('d M Y') }}
                                                        @endif
                                                    </span>
                                                </span>

                                                {{-- No Request --}}
                                                <span class="flex items-center gap-1.5 px-2 py-1 bg-[var(--paper-soft)] rounded-lg">
                                                    <svg class="w-3.5 h-3.5 text-[var(--ink-soft)]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" /></svg>
                                                    <span class="font-medium text-[var(--ink-soft)]">{{ Str::limit($request->no_req, 20) }}</span>
                                                </span>

                                                {{-- File Count --}}
                                                @if($request->file_count > 0)
                                                    <span class="flex items-center gap-1.5 px-2 py-1 bg-[var(--paper-soft)] rounded-lg">
                                                        <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" /></svg>
                                                        <span class="font-medium text-[var(--ink-soft)]">{{ $request->file_count }} file</span>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        {{-- Actions --}}
                                        <div class="flex items-center gap-1.5 flex-shrink-0">
                                            @if($request->status === 'DRAFT')
                                                <span class="px-3 py-1.5 bg-[var(--gold)] hover:bg-[var(--gold-bright)] text-white text-xs font-semibold rounded-lg transition-colors flex items-center gap-1">
                                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                                    Edit
                                                </span>
                                            @else
                                                <span class="px-3 py-1.5 bg-[var(--paper-soft)] text-[var(--ink-soft)] text-xs font-semibold rounded-lg flex items-center gap-1 group-hover:bg-[var(--gold)]/10 group-hover:text-[var(--gold)] transition-colors">
                                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                                    Lihat
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>

                        <div class="mt-8">
                            {{ $requests->links() }}
                        </div>
                    @endif

                {{-- Tab Content: Janji Temu --}}
                @elseif($activeTab === 'janji-temu')
                    {{-- Summary Cards --}}
                    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
                        <div class="neo-card p-4 text-center">
                            <p class="text-sm font-semibold text-[var(--ink-soft)] mb-1">Total Janji Temu</p>
                            <p class="text-3xl font-bold text-[var(--ink)]">{{ $summary['janji_temu']['total'] }}</p>
                        </div>
                        <div class="neo-card p-4 text-center border-l-4 border-yellow-400">
                            <p class="text-sm font-semibold text-[var(--ink-soft)] mb-1">Menunggu</p>
                            <p class="text-3xl font-bold text-yellow-600">{{ $summary['janji_temu']['appointment'] + $summary['janji_temu']['pending'] }}</p>
                            <p class="text-[10px] text-yellow-500 mt-1">Perlu konfirmasi</p>
                        </div>
                        <div class="neo-card p-4 text-center border-l-4 border-emerald-400">
                            <p class="text-sm font-semibold text-[var(--ink-soft)] mb-1">Disetujui</p>
                            <p class="text-3xl font-bold text-emerald-600">{{ $summary['janji_temu']['approved'] }}</p>
                            <p class="text-[10px] text-emerald-500 mt-1">Siap dijalankan</p>
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

                    {{-- Filter Periode Janji Temu --}}
                    <div class="bg-white border border-[var(--line)] rounded-xl p-4 mb-6">
                        <div class="flex flex-wrap items-center gap-4">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-[var(--ink-soft)]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" /></svg>
                                <span class="text-sm font-semibold text-[var(--ink)]">Filter Tahun:</span>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                @php
                                    $currentYearJT = request('year', now()->format('Y'));
                                    $yearsJT = collect();
                                    for ($i = 0; $i < 5; $i++) {
                                        $year = now()->subYears($i)->format('Y');
                                        $yearsJT->push($year);
                                    }
                                @endphp
                                <a href="{{ route('pengajuan-saya', ['tab' => 'janji-temu']) }}"
                                   class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ empty(request('year')) ? 'bg-[var(--gold)] text-white' : 'bg-[var(--paper-soft)] text-[var(--ink)] hover:bg-[var(--line)]' }}">
                                    Semua
                                </a>
                                @foreach($yearsJT as $year)
                                    <a href="{{ route('pengajuan-saya', ['tab' => 'janji-temu', 'year' => $year]) }}"
                                       class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ $currentYearJT === $year ? 'bg-[var(--gold)] text-white' : 'bg-[var(--paper-soft)] text-[var(--ink)] hover:bg-[var(--line)]' }}">
                                        {{ $year }}
                                    </a>
                                @endforeach
                            </div>
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
                        <div class="space-y-3">
                            @foreach($janjiTemuList as $item)
                                @php
                                    $statusMeta = match($item->status) {
                                        'APPOINTMENT' => ['label' => 'Menunggu Konfirmasi', 'class' => 'bg-yellow-100 text-yellow-800 border-yellow-200', 'icon' => 'bg-yellow-50 text-yellow-600'],
                                        'PENDING' => ['label' => 'Menunggu', 'class' => 'bg-blue-100 text-blue-800 border-blue-200', 'icon' => 'bg-blue-50 text-blue-600'],
                                        'APPROVED' => ['label' => 'Disetujui', 'class' => 'bg-emerald-100 text-emerald-800 border-emerald-200', 'icon' => 'bg-emerald-50 text-emerald-600'],
                                        'REJECTED' => ['label' => 'Ditolak', 'class' => 'bg-red-100 text-red-800 border-red-200', 'icon' => 'bg-red-50 text-red-600'],
                                        'CANCELLED' => ['label' => 'Dibatalkan', 'class' => 'bg-gray-100 text-gray-800 border-gray-200', 'icon' => 'bg-gray-50 text-gray-600'],
                                        default => ['label' => $item->status, 'class' => 'bg-gray-100 text-gray-800 border-gray-200', 'icon' => 'bg-gray-50 text-gray-600'],
                                    };

                                    $waktu = \Carbon\Carbon::parse($item->waktu);
                                    $isToday = $waktu->isToday();
                                    $isTomorrow = $waktu->isTomorrow();
                                    $isThisWeek = $waktu->isCurrentWeek();
                                    $isThisYear = $waktu->isCurrentYear();
                                @endphp

                                <a href="{{ route('janji-temu-detail', $item->id) }}" class="group block bg-white border border-[var(--line)] rounded-xl p-4 hover:border-[var(--gold)] hover:shadow-md transition-all duration-200">
                                    <div class="flex items-start gap-4">
                                        {{-- Icon --}}
                                        <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0 {{ $statusMeta['icon'] }}">
                                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                            </svg>
                                        </div>

                                        {{-- Content --}}
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-2 mb-1.5">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider {{ $statusMeta['class'] }}">
                                                    {{ $statusMeta['label'] }}
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

                                            <h3 class="text-sm font-bold text-[var(--ink)] group-hover:text-[var(--gold)] transition-colors mb-2">
                                                {{ Str::limit($item->tujuan, 50) }}
                                            </h3>

                                            {{-- Date/Time Info - More Prominent --}}
                                            <div class="flex items-center gap-2 text-xs">
                                                <span class="flex items-center gap-1.5 px-2 py-1 bg-[var(--paper-soft)] rounded-lg">
                                                    <svg class="w-3.5 h-3.5 text-[var(--gold)]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" /></svg>
                                                    <span class="font-semibold text-[var(--ink)]">
                                                        @if($isToday)
                                                            Hari ini, {{ $waktu->format('H:i') }}
                                                        @elseif($isTomorrow)
                                                            Besok, {{ $waktu->format('H:i') }}
                                                        @elseif($isThisWeek)
                                                            {{ $waktu->translatedFormat('l') }}, {{ $waktu->format('H:i') }}
                                                        @elseif($isThisYear)
                                                            {{ $waktu->format('d M') }}, {{ $waktu->format('H:i') }}
                                                        @else
                                                            {{ $waktu->format('d M Y') }}, {{ $waktu->format('H:i') }}
                                                        @endif
                                                    </span>
                                                </span>
                                                @if($item->komen)
                                                    <span class="flex items-center gap-1.5 px-2 py-1 bg-[var(--paper-soft)] rounded-lg italic">
                                                        <svg class="w-3.5 h-3.5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
                                                        <span class="font-medium text-[var(--ink-soft)]">{{ Str::limit($item->komen, 25) }}</span>
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
