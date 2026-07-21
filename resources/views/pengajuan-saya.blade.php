<x-layouts.app title="Pengajuan Saya - SILATAR">
    <main class="neo-mirai">

        <!-- Hero Section -->
        <section class="hero-page bg-cover bg-center" style="background-image: url('/assets/img/template/bg2.webp'); padding: 120px 2rem 4rem; min-height: 300px;">
            <div class="news-article-container article-hero">
                <p class="section-label-gold section-label-sm">Riwayat Pengajuan</p>
                <h1 class="article-hero-title">Pengajuan Saya</h1>
                <p class="article-hero-subtitle">Halaman ini menampilkan semua layanan yang pernah Anda ajukan, termasuk draft dan pengajuan final.</p>
                <div class="hero-actions">
                    <a href="{{ route('pelayanan') }}" class="neo-hero-cta neo-hero-cta-primary">Ajukan layanan baru</a>
                    <a href="{{ url('/') }}" class="neo-hero-cta">Kembali ke beranda</a>
                </div>
            </div>
        </section>

        <!-- Content -->
        <section class="page-content">
            @if (session('success'))
                <div class="alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Summary Cards -->
            <div class="neo-grid neo-grid-4" style="max-width: 60rem; margin: 0 auto;">
                <div class="neo-card panel-summary-card">
                    <p class="panel-summary-label">Total Pengajuan</p>
                    <p class="panel-summary-value">{{ $summary['total'] }}</p>
                </div>
                <div class="neo-card panel-summary-card panel-summary-card-accent">
                    <p class="panel-summary-label">Draft</p>
                    <p class="panel-summary-value">{{ $summary['draft'] }}</p>
                </div>
                <div class="neo-card panel-summary-card">
                    <p class="panel-summary-label">Diproses</p>
                    <p class="panel-summary-value">{{ $summary['pending'] + $summary['processed'] }}</p>
                </div>
                <div class="neo-card panel-summary-card panel-summary-card-success">
                    <p class="panel-summary-label">Selesai</p>
                    <p class="panel-summary-value">{{ $summary['done'] }}</p>
                </div>
            </div>

            <!-- Request List -->
            <div class="mt-8">
                <h2 class="section-heading">Semua pengajuan Anda</h2>

                @if ($requests->count() === 0)
                    <div class="neo-empty panel-empty">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p class="neo-empty-title">Belum ada pengajuan</p>
                        <p class="neo-empty-text">Saat Anda menyimpan draft atau mengirim layanan, datanya akan tampil di sini.</p>
                        <a href="{{ route('pelayanan') }}" class="neo-btn mt-6">Mulai pengajuan</a>
                    </div>
                @else
                    <div class="neo-card panel-card-wrapper">
                        <div class="panel-table-wrapper">
                            <table class="panel-table">
                                <thead class="panel-table-header">
                                    <tr class="text-left">
                                        <th class="panel-table-header-cell">No Req</th>
                                        <th class="panel-table-header-cell">Layanan</th>
                                        <th class="panel-table-header-cell">Status</th>
                                        <th class="panel-table-header-cell">Lampiran</th>
                                        <th class="panel-table-header-cell">Dibuat</th>
                                        <th class="panel-table-header-cell">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($requests as $request)
                                        @php
                                            $statusMeta = match ($request->status) {
                                                'DRAFT' => ['label' => 'Draft', 'class' => 'panel-status-badge-gold'],
                                                'UNCHECK', 'PENDING' => ['label' => 'Pending', 'class' => 'panel-status-badge-soft'],
                                                'SUBMITTED', 'DITERIMA', 'DIPROSES' => ['label' => 'Diproses', 'class' => 'panel-status-badge-gold'],
                                                'SUKSES' => ['label' => 'Sukses', 'class' => 'panel-status-badge-success'],
                                                'DITOLAK' => ['label' => 'Ditolak', 'class' => 'panel-status-badge-error'],
                                                'BATAL' => ['label' => 'Batal', 'class' => 'panel-status-badge-soft'],
                                                default => ['label' => $request->status, 'class' => 'panel-status-badge-soft'],
                                            };

                                            // Determine if this is a TPG request
                                            $isTpgSemester = !empty($request->tipe) && $request->tipe === 'PAIS-TPG-SEMESTER';
                                            $isTpgBulanan = !empty($request->tipe) && $request->tipe === 'PAIS-TPG-BULANAN';
                                            $isPenmadTpgBulanan = !empty($request->tipe) && $request->tipe === 'PENMAD-TPG-BULANAN';
                                            $isPenmadPengawasBulanan = !empty($request->tipe) && $request->tipe === 'PENMAD-PENGAWAS-BULANAN';
                                            $isTpg = $isTpgSemester || $isTpgBulanan || $isPenmadTpgBulanan || $isPenmadPengawasBulanan;

                                            // Extract metadata for display
                                            $metadata = null;
                                            $displaySubtitle = null;
                                            if ($isTpg && !empty($request->metadata)) {
                                                $metadata = is_string($request->metadata) ? json_decode($request->metadata, true) : $request->metadata;
                                                if (is_string($metadata)) {
                                                    $metadata = json_decode($metadata, true);
                                                }
                                                if ($metadata) {
                                                    if ($isTpgSemester && isset($metadata['tahun_pelajaran'], $metadata['semester'])) {
                                                        $displaySubtitle = $metadata['tahun_pelajaran'] . ' - ' . $metadata['semester'];
                                                    } elseif (($isTpgBulanan || $isPenmadTpgBulanan || $isPenmadPengawasBulanan) && isset($metadata['tahun'], $metadata['bulan'])) {
                                                        $displaySubtitle = $metadata['tahun'] . ' - ' . $metadata['bulan'];
                                                    }
                                                }
                                            }
                                        @endphp
                                        <tr class="panel-table-row">
                                            <td class="panel-table-cell">
                                                <span class="panel-table-cell-mono">{{ $request->no_req }}</span>
                                                <br><span class="panel-table-cell-muted">
                                                    @if ($isTpgSemester)
                                                        TPG Semester
                                                        @if ($displaySubtitle)
                                                            <br><span class="panel-summary-label" style="color: var(--gold);">{{ $displaySubtitle }}</span>
                                                        @endif
                                                    @elseif ($isTpgBulanan)
                                                        TPG Bulanan
                                                        @if ($displaySubtitle)
                                                            <br><span class="panel-summary-label" style="color: var(--gold);">{{ $displaySubtitle }}</span>
                                                        @endif
                                                    @elseif ($isPenmadTpgBulanan)
                                                        TPG Bulanan PENMAD
                                                        @if ($displaySubtitle)
                                                            <br><span class="panel-summary-label" style="color: var(--gold);">{{ $displaySubtitle }}</span>
                                                        @endif
                                                    @elseif ($isPenmadPengawasBulanan)
                                                        TPG Bulanan Pengawas
                                                        @if ($displaySubtitle)
                                                            <br><span class="panel-summary-label" style="color: var(--gold);">{{ $displaySubtitle }}</span>
                                                        @endif
                                                    @else
                                                        {{ $request->kategori }}
                                                    @endif
                                                </span>
                                            </td>
                                            <td class="panel-table-cell">
                                                <span class="panel-table-cell-bold">{{ $request->layanan_name }}</span>
                                            </td>
                                            <td class="panel-table-cell">
                                                <span class="panel-status-badge {{ $statusMeta['class'] }}">
                                                    {{ $statusMeta['label'] }}
                                                </span>
                                            </td>
                                            <td class="panel-table-cell panel-table-cell-files">
                                                {{ (int) $request->file_count }} file
                                            </td>
                                            <td class="panel-table-cell panel-table-cell-date">
                                                {{ \Illuminate\Support\Carbon::parse($request->created_at)->format('d M Y') }}
                                            </td>
                                            <td class="panel-table-cell">
                                                @if ($isTpgSemester)
                                                    @php
                                                        $editParams = '';
                                                        if ($metadata) {
                                                            $editParams = '?tahun_pelajaran=' . urlencode($metadata['tahun_pelajaran'] ?? '') . '&semester=' . urlencode($metadata['semester'] ?? '');
                                                        }
                                                        $tpgEditRoute = route('pelayanan.tpg.form', ['pemberkasanId' => $request->id]);
                                                        $tpgDeleteRoute = route('pelayanan.tpg.delete', $request->id);
                                                    @endphp
                                                    <div class="panel-actions">
                                                        <a href="{{ $tpgEditRoute . $editParams }}" class="neo-btn panel-btn">
                                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                                <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                                                                <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                                            </svg>
                                                            Edit
                                                        </a>
                                                        @if ($request->status === 'DRAFT')
                                                            <form action="{{ $tpgDeleteRoute }}" method="POST" class="inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="neo-btn panel-btn panel-btn-delete" onclick="return confirm('Yakin ingin hapus draft ini?');">
                                                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                                        <polyline points="3 6 5 6 21 6"/>
                                                                        <path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/>
                                                                    </svg>
                                                                    Hapus
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                @elseif ($isTpgBulanan)
                                                    @php
                                                        $editParams = '';
                                                        if ($metadata) {
                                                            $editParams = '?tahun=' . urlencode($metadata['tahun'] ?? '') . '&bulan=' . urlencode($metadata['bulan'] ?? '');
                                                        }
                                                        $tpgBulananEditRoute = route('pelayanan.tpg-bulanan.form', ['pemberkasanId' => $request->id]);
                                                        $tpgBulananDeleteRoute = route('pelayanan.tpg-bulanan.delete', $request->id);
                                                    @endphp
                                                    <div class="panel-actions">
                                                        <a href="{{ $tpgBulananEditRoute . $editParams }}" class="neo-btn panel-btn">
                                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                                <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                                                                <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                                            </svg>
                                                            Edit
                                                        </a>
                                                        @if ($request->status === 'DRAFT')
                                                            <form action="{{ $tpgBulananDeleteRoute }}" method="POST" class="inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="neo-btn panel-btn panel-btn-delete" onclick="return confirm('Yakin ingin hapus draft ini?');">
                                                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                                        <polyline points="3 6 5 6 21 6"/>
                                                                        <path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/>
                                                                    </svg>
                                                                    Hapus
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                @elseif ($isPenmadTpgBulanan)
                                                    @php
                                                        $editParams = '';
                                                        if ($metadata) {
                                                            $editParams = '?tahun=' . urlencode($metadata['tahun'] ?? '') . '&bulan=' . urlencode($metadata['bulan'] ?? '');
                                                        }
                                                        $penmadTpgBulananEditRoute = route('pelayanan.penmad-tpg-bulanan.form', ['pemberkasanId' => $request->id]);
                                                        $penmadTpgBulananDeleteRoute = route('pelayanan.penmad-tpg-bulanan.delete', $request->id);
                                                    @endphp
                                                    <div class="panel-actions">
                                                        <a href="{{ $penmadTpgBulananEditRoute . $editParams }}" class="neo-btn panel-btn">
                                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                                <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                                                                <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                                            </svg>
                                                            Edit
                                                        </a>
                                                        @if ($request->status === 'DRAFT')
                                                            <form action="{{ $penmadTpgBulananDeleteRoute }}" method="POST" class="inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="neo-btn panel-btn panel-btn-delete" onclick="return confirm('Yakin ingin hapus draft ini?');">
                                                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                                        <polyline points="3 6 5 6 21 6"/>
                                                                        <path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/>
                                                                    </svg>
                                                                    Hapus
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                @elseif ($isPenmadPengawasBulanan)
                                                    @php
                                                        $editParams = '';
                                                        if ($metadata) {
                                                            $editParams = '?tahun=' . urlencode($metadata['tahun'] ?? '') . '&bulan=' . urlencode($metadata['bulan'] ?? '');
                                                        }
                                                        $penmadPengawasBulananEditRoute = route('pelayanan.penmad-pengawas-bulanan.form', ['pemberkasanId' => $request->id]);
                                                        $penmadPengawasBulananDeleteRoute = route('pelayanan.penmad-pengawas-bulanan.delete', $request->id);
                                                    @endphp
                                                    <div class="panel-actions">
                                                        <a href="{{ $penmadPengawasBulananEditRoute . $editParams }}" class="neo-btn panel-btn">
                                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                                <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                                                                <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                                            </svg>
                                                            Edit
                                                        </a>
                                                        @if ($request->status === 'DRAFT')
                                                            <form action="{{ $penmadPengawasBulananDeleteRoute }}" method="POST" class="inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="neo-btn panel-btn panel-btn-delete" onclick="return confirm('Yakin ingin hapus draft ini?');">
                                                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                                        <polyline points="3 6 5 6 21 6"/>
                                                                        <path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/>
                                                                    </svg>
                                                                    Hapus
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                @else
                                                    <div class="panel-actions">
                                                        <a href="{{ route('pengajuan-saya.edit', $request->id) }}" class="neo-btn panel-btn">
                                                            Edit
                                                        </a>
                                                        @if ($request->status === 'DRAFT')
                                                            <form action="{{ route('pengajuan-saya.delete', $request->id) }}" method="POST" class="inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="neo-btn panel-btn panel-btn-delete" onclick="return confirm('Yakin ingin hapus draft ini?');">
                                                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                                        <polyline points="3 6 5 6 21 6"/>
                                                                        <path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/>
                                                                    </svg>
                                                                    Hapus
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        </section>

        <!-- Footer -->
        <footer class="site-footer">
            <a class="brand-lockup brand-lockup-small" href="{{ url("/") }}" aria-label="SILATAR home">
                <span class="brand-mark" aria-hidden="true"><span></span></span>
                <span class="brand-word"><span>SILATAR</span><span>V2</span></span>
            </a>
            <p>Portal Layanan Digital Kementerian Agama Tanah Datar</p>
            <nav aria-label="Footer navigation">
                <a href="{{ url("/") }}">Beranda</a>
                <a href="{{ route('pelayanan') }}">Pelayanan</a>
                <a href="{{ route('satuan-kerja') }}">Unit Kerja</a>
                <a href="{{ route('news.index') }}">Berita</a>
            </nav>
            <div class="footer-copyright"><span>&copy; {{ date("Y") }} SILATAR - Kementerian Agama Tanah Datar</span></div>
        </footer>
    </main>
</x-layouts.app>
