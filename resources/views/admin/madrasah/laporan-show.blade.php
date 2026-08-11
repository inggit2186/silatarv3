<x-admin.layouts.app>
    <div class="page-header">
        <div class="page-header-content">
            <span class="page-label">// Laporan Madrasah</span>
            <h1 class="page-title">Detail Laporan {{ ucfirst($laporan->jenis) }}</h1>
            <p class="page-subtitle">{{ $laporan->periode_detail }}</p>
        </div>
        <div class="page-actions">
            @php
                $statusClass = match($laporan->status) {
                    'submitted' => 'badge-warning',
                    'approved' => 'badge-success',
                    'revisi' => 'badge-danger',
                    default => 'badge-gray',
                };
            @endphp
            <span class="badge {{ $statusClass }}">{{ ucfirst($laporan->status) }}</span>
        </div>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="alert alert-success mb-6">
            <svg class="alert-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="alert-message">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger mb-6">
            <svg class="alert-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="alert-message">{{ session('error') }}</span>
        </div>
    @endif

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px;">
        <!-- Left Column: Info Cards -->
        <div>
            <!-- Info Umum Laporan -->
            <div class="card mb-6">
                <div class="card-header">
                    <div class="flex items-center gap-3">
                        <div class="stat-icon amber" style="width: 36px; height: 36px;">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <h3 class="card-title">Info Umum Laporan</h3>
                    </div>
                </div>
                <div class="card-body">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="form-label text-muted">Jenis Laporan</label>
                            <div class="font-medium">{{ ucfirst($laporan->jenis) }}</div>
                        </div>
                        <div>
                            <label class="form-label text-muted">Status</label>
                            <span class="badge {{ $statusClass }}">{{ ucfirst($laporan->status) }}</span>
                        </div>
                        <div>
                            <label class="form-label text-muted">Periode</label>
                            <div class="font-medium">{{ $laporan->periode }}</div>
                        </div>
                        <div>
                            <label class="form-label text-muted">Tahun Ajaran</label>
                            <div class="font-medium">{{ $laporan->tahun_ajaran }}</div>
                        </div>
                        @if($laporan->jenis === 'bulanan')
                            <div>
                                <label class="form-label text-muted">Semester</label>
                                <div class="font-medium">{{ $laporan->semester }}</div>
                            </div>
                            <div>
                                <label class="form-label text-muted">Jumlah Rombel</label>
                                <div class="font-medium">{{ $laporan->rb ?? '-' }}</div>
                            </div>
                        @endif
                        <div>
                            <label class="form-label text-muted">Tanggal Submit</label>
                            <div class="font-medium">
                                @if($laporan->submitted_at)
                                    {{ \Carbon\Carbon::parse($laporan->submitted_at)->format('d M Y H:i') }}
                                @else
                                    -
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Madrasah -->
            <div class="card mb-6">
                <div class="card-header">
                    <div class="flex items-center gap-3">
                        <div class="stat-icon emerald" style="width: 36px; height: 36px;">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <h3 class="card-title">Data Madrasah</h3>
                    </div>
                </div>
                <div class="card-body">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="form-label text-muted">Nama Madrasah</label>
                            <div class="font-medium">{{ $laporan->nama_madrasah ?? '-' }}</div>
                        </div>
                        <div>
                            <label class="form-label text-muted">Instansi</label>
                            <div class="font-medium">{{ $laporan->instansi ?? '-' }}</div>
                        </div>
                        @if($laporan->nsm)
                            <div>
                                <label class="form-label text-muted">NSM</label>
                                <div class="font-medium">{{ $laporan->nsm }}</div>
                            </div>
                        @endif
                        @if($laporan->npsm)
                            <div>
                                <label class="form-label text-muted">NPSM</label>
                                <div class="font-medium">{{ $laporan->npsm }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Profil Madrasah -->
            @if($profilMadrasah)
                <div class="card mb-6" x-data="{ open: false }">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="flex items-center gap-3">
                            <div class="stat-icon violet" style="width: 36px; height: 36px;">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <h3 class="card-title">Profil Madrasah</h3>
                        </div>
                        <div class="flex items-center gap-2" @click="open = !open" style="cursor: pointer;">
                            <span class="text-sm text-muted" x-text="open ? 'Tutup' : 'Buka'"></span>
                            <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center transition-transform duration-200" :class="{ 'rotate-180': open }">
                                <svg class="w-4 h-4 text-gray-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" style="display: none;">
                        <div class="grid grid-cols-2 gap-4">
                            @if($profilMadrasah->jalan)
                                <div class="col-span-2">
                                    <label class="form-label text-muted">Alamat</label>
                                    <div class="font-medium">{{ $profilMadrasah->jalan }}</div>
                                </div>
                            @endif
                            @if($profilMadrasah->jorong)
                                <div>
                                    <label class="form-label text-muted">Jorong</label>
                                    <div class="font-medium">{{ $profilMadrasah->jorong }}</div>
                                </div>
                            @endif
                            @if($profilMadrasah->nagari)
                                <div>
                                    <label class="form-label text-muted">Nagari</label>
                                    <div class="font-medium">{{ $profilMadrasah->nagari }}</div>
                                </div>
                            @endif
                            @if($profilMadrasah->kecamatan)
                                <div>
                                    <label class="form-label text-muted">Kecamatan</label>
                                    <div class="font-medium">{{ $profilMadrasah->kecamatan }}</div>
                                </div>
                            @endif
                            @if($profilMadrasah->telepon)
                                <div>
                                    <label class="form-label text-muted">Telepon</label>
                                    <div class="font-medium">{{ $profilMadrasah->telepon }}</div>
                                </div>
                            @endif
                            @if($profilMadrasah->email)
                                <div>
                                    <label class="form-label text-muted">Email</label>
                                    <div class="font-medium">{{ $profilMadrasah->email }}</div>
                                </div>
                            @endif
                            @if($profilMadrasah->website)
                                <div class="col-span-2">
                                    <label class="form-label text-muted">Website</label>
                                    <div class="font-medium">{{ $profilMadrasah->website }}</div>
                                </div>
                            @endif
                            @if($profilMadrasah->waktu_belajar)
                                <div>
                                    <label class="form-label text-muted">Waktu Belajar</label>
                                    <div class="font-medium">{{ $profilMadrasah->waktu_belajar }}</div>
                                </div>
                            @endif
                            @if($profilMadrasah->akreditasi)
                                <div>
                                    <label class="form-label text-muted">Akreditasi</label>
                                    <div class="font-medium">{{ $profilMadrasah->akreditasi }}</div>
                                </div>
                            @endif
                            @if($profilMadrasah->visi)
                                <div class="col-span-2">
                                    <label class="form-label text-muted">Visi</label>
                                    <div class="font-medium" style="white-space: pre-wrap;">{{ $profilMadrasah->visi }}</div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <!-- Data Pegawai -->
            <div class="card mb-6" x-data="{ open: false }">
                <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                    <div class="flex items-center gap-3">
                        <div class="stat-icon amber" style="width: 36px; height: 36px;">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <h3 class="card-title">Data Pegawai ({{ count($pegawai) }} orang)</h3>
                    </div>
                    <div class="flex items-center gap-2" @click="open = !open" style="cursor: pointer;">
                        <span class="text-sm text-muted" x-text="open ? 'Tutup' : 'Buka'"></span>
                        <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center transition-transform duration-200" :class="{ 'rotate-180': open }">
                            <svg class="w-4 h-4 text-gray-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="card-body" x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" style="display: none;">
                    @if(count($pegawai) > 0)
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Status</th>
                                        <th>NIP/Nomor Induk</th>
                                        <th>Jabatan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pegawai as $index => $p)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td class="font-medium">{{ $p->nama ?? '-' }}</td>
                                            <td>
                                                <span class="badge {{ $p->status === 'PNS' ? 'badge-success' : ($p->status === 'PPPK' ? 'badge-info' : 'badge-warning') }}">
                                                    {{ $p->status ?? '-' }}
                                                </span>
                                            </td>
                                            <td>{{ $p->nomor_induk ?? '-' }}</td>
                                            <td>{{ $p->jabatan ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-state" style="padding: 2rem;">
                            <p class="text-muted">Belum ada data pegawai</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Data Guru -->
            <div class="card mb-6" x-data="{ open: false }">
                <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                    <div class="flex items-center gap-3">
                        <div class="stat-icon blue" style="width: 36px; height: 36px;">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <h3 class="card-title">Data Guru ({{ count($guru) }} orang)</h3>
                    </div>
                    <div class="flex items-center gap-2" @click="open = !open" style="cursor: pointer;">
                        <span class="text-sm text-muted" x-text="open ? 'Tutup' : 'Buka'"></span>
                        <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center transition-transform duration-200" :class="{ 'rotate-180': open }">
                            <svg class="w-4 h-4 text-gray-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="card-body" x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" style="display: none;">
                    @if(count($guru) > 0)
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Status</th>
                                        <th>NUPTK</th>
                                        <th>Bidang Studi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($guru as $index => $g)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td class="font-medium">{{ $g->nama ?? '-' }}</td>
                                            <td>
                                                <span class="badge {{ $g->status === 'PNS' ? 'badge-success' : ($g->status === 'PPPK' ? 'badge-info' : 'badge-warning') }}">
                                                    {{ $g->status ?? '-' }}
                                                </span>
                                            </td>
                                            <td>{{ $g->nuptk ?? '-' }}</td>
                                            <td>{{ $g->bidang_studi_diajar ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-state" style="padding: 2rem;">
                            <p class="text-muted">Belum ada data guru</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Data Laporan (varies by type) -->
            @if($type === 'bulanan')
                <!-- Laporan Bulanan: Student Counts -->
                <div class="card mb-6" x-data="{ open: false }">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="flex items-center gap-3">
                            <div class="stat-icon blue" style="width: 36px; height: 36px;">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                            </div>
                            <h3 class="card-title">Data Siswa per Rombel</h3>
                        </div>
                        <div class="flex items-center gap-2" @click="open = !open" style="cursor: pointer;">
                            <span class="text-sm text-muted" x-text="open ? 'Tutup' : 'Buka'"></span>
                            <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center transition-transform duration-200" :class="{ 'rotate-180': open }">
                                <svg class="w-4 h-4 text-gray-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" style="display: none;">
                        @if(!empty($laporan->student_counts) && count($laporan->student_counts) > 0)
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Kelas</th>
                                            <th class="text-center">Laki-laki</th>
                                            <th class="text-center">Perempuan</th>
                                            <th class="text-center">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $totalL = 0;
                                            $totalP = 0;
                                            $totalAll = 0;
                                        @endphp
                                        @foreach($laporan->student_counts as $kelas => $data)
                                            @php
                                                $l = $data['l'] ?? 0;
                                                $p = $data['p'] ?? 0;
                                                $total = $l + $p;
                                                $totalL += $l;
                                                $totalP += $p;
                                                $totalAll += $total;
                                            @endphp
                                            <tr>
                                                <td class="font-medium">{{ $kelas }}</td>
                                                <td class="text-center">{{ $l }}</td>
                                                <td class="text-center">{{ $p }}</td>
                                                <td class="text-center font-bold">{{ $total }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr style="background: var(--primary-light);">
                                            <td class="font-bold">Total</td>
                                            <td class="text-center font-bold">{{ $totalL }}</td>
                                            <td class="text-center font-bold">{{ $totalP }}</td>
                                            <td class="text-center font-bold" style="color: var(--primary);">{{ $totalAll }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        @else
                            <div class="empty-state" style="padding: 2rem;">
                                <p class="text-muted">Tidak ada data siswa</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Laporan Bulanan: Mutation Rows -->
                <div class="card mb-6" x-data="{ open: false }">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="flex items-center gap-3">
                            <div class="stat-icon violet" style="width: 36px; height: 36px;">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                </svg>
                            </div>
                            <h3 class="card-title">Data Mutasi Siswa</h3>
                        </div>
                        <div class="flex items-center gap-2" @click="open = !open" style="cursor: pointer;">
                            <span class="text-sm text-muted" x-text="open ? 'Tutup' : 'Buka'"></span>
                            <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center transition-transform duration-200" :class="{ 'rotate-180': open }">
                                <svg class="w-4 h-4 text-gray-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" style="display: none;">
                        @if(!empty($laporan->mutation_rows) && count($laporan->mutation_rows) > 0)
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Siswa</th>
                                            <th>Kelas</th>
                                            <th>Asal/Tujuan</th>
                                            <th>Jenis Mutasi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($laporan->mutation_rows as $index => $row)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td class="font-medium">{{ $row['nama'] ?? '-' }}</td>
                                                <td>{{ $row['kelas'] ?? '-' }}</td>
                                                <td>{{ $row['asal_tujuan'] ?? '-' }}</td>
                                                <td>
                                                    @php
                                                        $mutasiClass = match($row['jenis'] ?? '') {
                                                            'masuk' => 'badge-success',
                                                            'keluar' => 'badge-warning',
                                                            'mengundurkan_diri' => 'badge-danger',
                                                            'do' => 'badge-danger',
                                                            default => 'badge-gray',
                                                        };
                                                    @endphp
                                                    <span class="badge {{ $mutasiClass }}">{{ ucfirst(str_replace('_', ' ', $row['jenis'] ?? '-')) }}</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="empty-state" style="padding: 2rem;">
                                <p class="text-muted">Tidak ada data mutasi</p>
                            </div>
                        @endif
                    </div>
                </div>

            @elseif($type === 'semester')
                <!-- Laporan Semester: Multiple JSON Sections -->
                @php
                    $sections = [
                        'keadaan_gedung' => ['title' => 'Keadaan Gedung', 'icon' => 'building'],
                        'sarana_pendidikan' => ['title' => 'Sarana Pendidikan', 'icon' => 'book'],
                        'bantuan_pemerintah' => ['title' => 'Bantuan Pemerintah', 'icon' => 'government'],
                        'bantuan_non_pemerintah' => ['title' => 'Bantuan Non Pemerintah', 'icon' => 'heart'],
                        'data_guru_pegawai' => ['title' => 'Data Guru/Pegawai', 'icon' => 'users'],
                        'tingkat_pendidikan' => ['title' => 'Tingkat Pendidikan', 'icon' => 'academic'],
                        'sertifikasi' => ['title' => 'Sertifikasi', 'icon' => 'certificate'],
                        'absensi_siswa' => ['title' => 'Absensi Siswa', 'icon' => 'check'],
                    ];
                @endphp

                @foreach($sections as $key => $section)
                    <div class="card mb-6" x-data="{ open: false }">
                        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                            <div class="flex items-center gap-3">
                                <div class="stat-icon blue" style="width: 36px; height: 36px;">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <h3 class="card-title">{{ $section['title'] }}</h3>
                            </div>
                            <div class="flex items-center gap-2" @click="open = !open" style="cursor: pointer;">
                                <span class="text-sm text-muted" x-text="open ? 'Tutup' : 'Buka'"></span>
                                <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center transition-transform duration-200" :class="{ 'rotate-180': open }">
                                    <svg class="w-4 h-4 text-gray-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div class="card-body" x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" style="display: none;">
                            @if(!empty($laporan->$key) && count($laporan->$key) > 0)
                                @if(is_array($laporan->$key) && isset($laporan->$key[0]))
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    @foreach(array_keys($laporan->$key[0]) as $col)
                                                        <th>{{ ucwords(str_replace('_', ' ', $col)) }}</th>
                                                    @endforeach
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($laporan->$key as $row)
                                                    <tr>
                                                        @foreach($row as $val)
                                                            <td>{{ $val ?? '-' }}</td>
                                                        @endforeach
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <pre style="background: var(--bg-secondary); padding: 1rem; border-radius: 8px; overflow-x: auto; font-size: 0.875rem;">{{ json_encode($laporan->$key, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                @endif
                            @else
                                <div class="empty-state" style="padding: 2rem;">
                                    <p class="text-muted">Tidak ada data</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        <!-- Right Column: Verification Actions -->
        <div>
            <!-- Verification Form -->
            <div class="card mb-6">
                <div class="card-header">
                    <div class="flex items-center gap-3">
                        <div class="stat-icon emerald" style="width: 36px; height: 36px;">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="card-title">Verifikasi Laporan</h3>
                    </div>
                </div>
                <div class="card-body">
                    @if($laporan->status === 'submitted')
                        <!-- Verify Button -->
                        <form id="verifyForm" action="{{ route('admin.madrasah.laporan.verify', [$type, $laporan->id]) }}" method="POST" style="margin-bottom: 1rem;">
                            @csrf
                            <div class="form-group">
                                <label class="form-label">Catatan Admin (Opsional)</label>
                                <textarea name="catatan_admin" class="form-input" rows="3" placeholder="Tambahkan catatan jika diperlukan...">{{ $laporan->catatan_admin }}</textarea>
                            </div>
                            <button type="submit" class="btn btn-primary" style="width: 100%;">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                                Setujui Laporan
                            </button>
                        </form>

                        <hr style="margin: 1.5rem 0; border-color: var(--border);">

                        <!-- Reject Button -->
                        <form id="rejectForm" action="{{ route('admin.madrasah.laporan.reject', [$type, $laporan->id]) }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label class="form-label">Catatan Revisi <span class="text-danger">*</span></label>
                                <textarea name="catatan_admin_reject" id="catatan_admin_reject" class="form-input" rows="3" placeholder="Jelaskan hal yang perlu diperbaiki..." required>{{ old('catatan_admin_reject') }}</textarea>
                                @error('catatan_admin_reject')
                                    <div class="form-error">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-danger" style="width: 100%;">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Minta Revisi
                            </button>
                        </form>

                    @elseif($laporan->status === 'approved' || $laporan->status === 'revisi')
                        <!-- Already verified/rejected - show note update form -->
                        <form action="{{ route('admin.madrasah.laporan.note', [$type, $laporan->id]) }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label class="form-label">Catatan Admin</label>
                                <textarea name="catatan_admin" class="form-input" rows="4">{{ $laporan->catatan_admin }}</textarea>
                            </div>
                            <button type="submit" class="btn btn-secondary" style="width: 100%;">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                                </svg>
                                Simpan Catatan
                            </button>
                        </form>

                    @else
                        <div class="empty-state" style="padding: 2rem;">
                            <p class="text-muted">Status laporan: {{ ucfirst($laporan->status) }}</p>
                            <p class="text-sm text-muted mt-2">Hanya laporan dengan status "submitted" yang dapat diverifikasi.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Timeline/Card Info -->
            <div class="card">
                <div class="card-header">
                    <div class="flex items-center gap-3">
                        <div class="stat-icon violet" style="width: 36px; height: 36px;">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="card-title">Informasi Lainnya</h3>
                    </div>
                </div>
                <div class="card-body">
                    <div style="display: flex; flex-direction: column; gap: 1rem;">
                        <div>
                            <label class="form-label text-muted text-sm">Dibuat pada</label>
                            <div class="text-sm">{{ $laporan->created_at ? \Carbon\Carbon::parse($laporan->created_at)->format('d M Y H:i') : '-' }}</div>
                        </div>
                        <div>
                            <label class="form-label text-muted text-sm">Terakhir diupdate</label>
                            <div class="text-sm">{{ $laporan->updated_at ? \Carbon\Carbon::parse($laporan->updated_at)->format('d M Y H:i') : '-' }}</div>
                        </div>
                        @if($laporan->submitted_at)
                            <div>
                                <label class="form-label text-muted text-sm">Tanggal Submit</label>
                                <div class="text-sm">{{ \Carbon\Carbon::parse($laporan->submitted_at)->format('d M Y H:i') }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Back Button -->
            <div class="mt-4">
                <a href="{{ route('admin.madrasah.laporan.index') }}" class="btn btn-secondary" style="width: 100%;">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali ke Daftar Laporan
                </a>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Verify form confirmation
        document.getElementById('verifyForm')?.addEventListener('submit', function(e) {
            if (!confirm('Apakah Anda yakin ingin menyetujui laporan ini?')) {
                e.preventDefault();
            }
        });

        // Reject form validation
        document.getElementById('rejectForm')?.addEventListener('submit', function(e) {
            const catatan = document.getElementById('catatan_admin_reject').value;
            if (!catatan.trim()) {
                alert('Catatan admin wajib diisi untuk revisi');
                e.preventDefault();
                return;
            }
            if (!confirm('Apakah Anda yakin ingin meminta revisi laporan ini?')) {
                e.preventDefault();
            }
        });
    </script>
    @endpush
</x-admin.layouts.app>
