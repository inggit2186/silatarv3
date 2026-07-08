<x-layouts.app title="Pengajuan Saya - SILATAR">
    <main class="neo-mirai">
        <x-layouts.site-header />

        <!-- Hero Section -->
        <section class="hero-page" style="background-image: url('/assets/img/template/bg2.webp'); background-size: cover; background-position: center top; padding: 120px 2rem 4rem; min-height: 300px;">
            <div style="max-width: 36rem; margin: 0 auto;">
                <p style="color: var(--gold); font-family: var(--font-mono); font-size: 0.65rem; text-transform: uppercase; margin: 0 0 0.5rem;">Riwayat Pengajuan</p>
                <h1 style="font-family: var(--font-display); font-size: clamp(1.8rem, 4vw, 3rem); font-weight: 400; color: var(--ink); margin: 0 0 1rem;">Pengajuan Saya</h1>
                <p style="color: var(--ink-soft); font-size: 1rem; max-width: 28rem; margin: 0 auto;">Halaman ini menampilkan semua layanan yang pernah Anda ajukan, termasuk draft dan pengajuan final.</p>
                <div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 1rem; margin-top: 1.5rem;">
                    <a href="{{ route('pelayanan') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.7rem 1.25rem; background: var(--gold); color: var(--night); font-family: var(--font-mono); font-size: 0.7rem; font-weight: 700; text-transform: uppercase; text-decoration: none;">Ajukan layanan baru →</a>
                    <a href="{{ url('/') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.7rem 1.25rem; background: transparent; color: var(--ink); font-family: var(--font-mono); font-size: 0.7rem; font-weight: 600; text-transform: uppercase; text-decoration: none; border: 1px solid var(--line);">Kembali ke beranda</a>
                </div>
            </div>
        </section>

        <!-- Content -->
        <section class="page-content">
            @if (session('success'))
                <div style="margin-bottom: 1.5rem; padding: 1rem 1.25rem; background: oklch(65% 0.15 145 / 0.1); border: 1px solid oklch(65% 0.15 145 / 0.3); border-radius: 0.5rem; color: var(--ink);">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Summary Cards -->
            <div class="neo-grid neo-grid-4" style="max-width: 60rem; margin: 0 auto;">
                <div class="neo-card" style="text-align: center;">
                    <p style="font-family: var(--font-mono); font-size: 0.65rem; text-transform: uppercase; color: var(--ink-soft); margin: 0;">Total Pengajuan</p>
                    <p style="font-family: var(--font-display); font-size: 2rem; font-weight: 600; color: var(--ink); margin: 0.5rem 0 0;">{{ $summary['total'] }}</p>
                </div>
                <div class="neo-card" style="text-align: center; border-color: var(--gold);">
                    <p style="font-family: var(--font-mono); font-size: 0.65rem; text-transform: uppercase; color: var(--gold); margin: 0;">Draft</p>
                    <p style="font-family: var(--font-display); font-size: 2rem; font-weight: 600; color: var(--ink); margin: 0.5rem 0 0;">{{ $summary['draft'] }}</p>
                </div>
                <div class="neo-card" style="text-align: center;">
                    <p style="font-family: var(--font-mono); font-size: 0.65rem; text-transform: uppercase; color: var(--ink-soft); margin: 0;">Diproses</p>
                    <p style="font-family: var(--font-display); font-size: 2rem; font-weight: 600; color: var(--ink); margin: 0.5rem 0 0;">{{ $summary['pending'] + $summary['processed'] }}</p>
                </div>
                <div class="neo-card" style="text-align: center; border-color: oklch(65% 0.15 145);">
                    <p style="font-family: var(--font-mono); font-size: 0.65rem; text-transform: uppercase; color: oklch(65% 0.15 145); margin: 0;">Selesai</p>
                    <p style="font-family: var(--font-display); font-size: 2rem; font-weight: 600; color: var(--ink); margin: 0.5rem 0 0;">{{ $summary['done'] }}</p>
                </div>
            </div>

            <!-- Request List -->
            <div style="margin-top: 2rem;">
                <h2 style="font-family: var(--font-display); font-size: 1.25rem; font-weight: 600; margin: 0 0 1.5rem;">Semua pengajuan Anda</h2>

                @if ($requests->count() === 0)
                    <div class="neo-empty" style="border: 1px dashed var(--line); border-radius: 1rem;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p class="neo-empty-title">Belum ada pengajuan</p>
                        <p class="neo-empty-text">Saat Anda menyimpan draft atau mengirim layanan, datanya akan tampil di sini.</p>
                        <a href="{{ route('pelayanan') }}" class="neo-btn" style="margin-top: 1.5rem;">Mulai pengajuan</a>
                    </div>
                @else
                    <div class="neo-card" style="padding: 0; overflow: hidden;">
                        <div style="overflow-x: auto;">
                            <table style="width: 100%; border-collapse: collapse; min-width: 40rem;">
                                <thead style="background: var(--paper-soft);">
                                    <tr style="text-align: left; font-family: var(--font-mono); font-size: 0.6rem; text-transform: uppercase; color: var(--ink-soft);">
                                        <th style="padding: 1rem;">No Req</th>
                                        <th style="padding: 1rem;">Layanan</th>
                                        <th style="padding: 1rem;">Status</th>
                                        <th style="padding: 1rem;">Lampiran</th>
                                        <th style="padding: 1rem;">Dibuat</th>
                                        <th style="padding: 1rem;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($requests as $request)
                                        @php
                                            $statusMeta = match ($request->status) {
                                                'DRAFT' => ['label' => 'Draft', 'color' => 'var(--gold)'],
                                                'UNCHECK', 'PENDING' => ['label' => 'Pending', 'color' => 'var(--ink-soft)'],
                                                'SUBMITTED', 'DITERIMA', 'DIPROSES' => ['label' => 'Diproses', 'color' => 'var(--gold)'],
                                                'SUKSES' => ['label' => 'Sukses', 'color' => 'oklch(65% 0.15 145)'],
                                                'DITOLAK' => ['label' => 'Ditolak', 'color' => 'oklch(60% 0.2 25)'],
                                                'BATAL' => ['label' => 'Batal', 'color' => 'var(--ink-soft)'],
                                                default => ['label' => $request->status, 'color' => 'var(--ink-soft)'],
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
                                        <tr style="border-top: 1px solid var(--line);">
                                            <td style="padding: 1rem;">
                                                <span style="font-family: var(--font-mono); font-size: 0.85rem; font-weight: 600; color: var(--gold);">{{ $request->no_req }}</span>
                                                <br><span style="font-size: 0.7rem; color: var(--ink-soft);">
                                                    @if ($isTpgSemester)
                                                        TPG Semester
                                                        @if ($displaySubtitle)
                                                            <br><span style="color: var(--gold);">{{ $displaySubtitle }}</span>
                                                        @endif
                                                    @elseif ($isTpgBulanan)
                                                        TPG Bulanan
                                                        @if ($displaySubtitle)
                                                            <br><span style="color: var(--gold);">{{ $displaySubtitle }}</span>
                                                        @endif
                                                    @elseif ($isPenmadTpgBulanan)
                                                        TPG Bulanan PENMAD
                                                        @if ($displaySubtitle)
                                                            <br><span style="color: var(--gold);">{{ $displaySubtitle }}</span>
                                                        @endif
                                                    @elseif ($isPenmadPengawasBulanan)
                                                        TPG Bulanan Pengawas
                                                        @if ($displaySubtitle)
                                                            <br><span style="color: var(--gold);">{{ $displaySubtitle }}</span>
                                                        @endif
                                                    @else
                                                        {{ $request->kategori }}
                                                    @endif
                                                </span>
                                            </td>
                                            <td style="padding: 1rem;">
                                                <span style="font-weight: 600; color: var(--ink);">{{ $request->layanan_name }}</span>
                                            </td>
                                            <td style="padding: 1rem;">
                                                <span style="display: inline-block; padding: 0.25rem 0.75rem; background: oklch(94% 0.035 78); border: 1px solid var(--line); border-radius: 9999px; font-family: var(--font-mono); font-size: 0.6rem; font-weight: 600; text-transform: uppercase; color: {{ $statusMeta['color'] }};">
                                                    {{ $statusMeta['label'] }}
                                                </span>
                                            </td>
                                            <td style="padding: 1rem; font-family: var(--font-mono); font-size: 0.8rem;">
                                                {{ (int) $request->file_count }} file
                                            </td>
                                            <td style="padding: 1rem; font-size: 0.8rem; color: var(--ink-soft);">
                                                {{ \Illuminate\Support\Carbon::parse($request->created_at)->format('d M Y') }}
                                            </td>
                                            <td style="padding: 1rem;">
                                                @if ($isTpgSemester)
                                                    @php
                                                        $editParams = '';
                                                        if ($metadata) {
                                                            $editParams = '?tahun_pelajaran=' . urlencode($metadata['tahun_pelajaran'] ?? '') . '&semester=' . urlencode($metadata['semester'] ?? '');
                                                        }
                                                        $tpgEditRoute = route('pelayanan.tpg.form', ['pemberkasanId' => $request->id]);
                                                        $tpgDeleteRoute = route('pelayanan.tpg.delete', $request->id);
                                                    @endphp
                                                    <div style="display: flex; gap: 0.5rem; align-items: center;">
                                                        <a href="{{ $tpgEditRoute . $editParams }}"
                                                           class="neo-btn"
                                                           style="padding: 0.5rem 1rem; font-size: 0.65rem; display: inline-flex; align-items: center; gap: 0.25rem;">
                                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                                <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                                                                <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                                            </svg>
                                                            Edit
                                                        </a>
                                                        @if ($request->status === 'DRAFT')
                                                            <form action="{{ $tpgDeleteRoute }}" method="POST" onsubmit="return confirm('Yakin ingin hapus draft ini?');" style="display: inline;">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="neo-btn" style="padding: 0.5rem 1rem; font-size: 0.65rem; background: oklch(60% 0.2 25); color: white; display: inline-flex; align-items: center; gap: 0.25rem;">
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
                                                    <div style="display: flex; gap: 0.5rem; align-items: center;">
                                                        <a href="{{ $tpgBulananEditRoute . $editParams }}"
                                                           class="neo-btn"
                                                           style="padding: 0.5rem 1rem; font-size: 0.65rem; display: inline-flex; align-items: center; gap: 0.25rem;">
                                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                                <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                                                                <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                                            </svg>
                                                            Edit
                                                        </a>
                                                        @if ($request->status === 'DRAFT')
                                                            <form action="{{ $tpgBulananDeleteRoute }}" method="POST" onsubmit="return confirm('Yakin ingin hapus draft ini?');" style="display: inline;">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="neo-btn" style="padding: 0.5rem 1rem; font-size: 0.65rem; background: oklch(60% 0.2 25); color: white; display: inline-flex; align-items: center; gap: 0.25rem;">
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
                                                    <div style="display: flex; gap: 0.5rem; align-items: center;">
                                                        <a href="{{ $penmadTpgBulananEditRoute . $editParams }}"
                                                           class="neo-btn"
                                                           style="padding: 0.5rem 1rem; font-size: 0.65rem; display: inline-flex; align-items: center; gap: 0.25rem;">
                                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                                <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                                                                <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                                            </svg>
                                                            Edit
                                                        </a>
                                                        @if ($request->status === 'DRAFT')
                                                            <form action="{{ $penmadTpgBulananDeleteRoute }}" method="POST" onsubmit="return confirm('Yakin ingin hapus draft ini?');" style="display: inline;">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="neo-btn" style="padding: 0.5rem 1rem; font-size: 0.65rem; background: oklch(60% 0.2 25); color: white; display: inline-flex; align-items: center; gap: 0.25rem;">
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
                                                    <div style="display: flex; gap: 0.5rem; align-items: center;">
                                                        <a href="{{ $penmadPengawasBulananEditRoute . $editParams }}"
                                                           class="neo-btn"
                                                           style="padding: 0.5rem 1rem; font-size: 0.65rem; display: inline-flex; align-items: center; gap: 0.25rem;">
                                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                                <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                                                                <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                                            </svg>
                                                            Edit
                                                        </a>
                                                        @if ($request->status === 'DRAFT')
                                                            <form action="{{ $penmadPengawasBulananDeleteRoute }}" method="POST" onsubmit="return confirm('Yakin ingin hapus draft ini?');" style="display: inline;">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="neo-btn" style="padding: 0.5rem 1rem; font-size: 0.65rem; background: oklch(60% 0.2 25); color: white; display: inline-flex; align-items: center; gap: 0.25rem;">
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
                                                    <div style="display: flex; gap: 0.5rem; align-items: center;">
                                                        <a href="{{ route('pengajuan-saya.edit', $request->id) }}" class="neo-btn" style="padding: 0.5rem 1rem; font-size: 0.65rem;">
                                                            Edit →
                                                        </a>
                                                        @if ($request->status === 'DRAFT')
                                                            <form action="{{ route('pengajuan-saya.delete', $request->id) }}" method="POST" onsubmit="return confirm('Yakin ingin hapus draft ini?');" style="display: inline;">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="neo-btn" style="padding: 0.5rem 1rem; font-size: 0.65rem; background: oklch(60% 0.2 25); color: white; display: inline-flex; align-items: center; gap: 0.25rem;">
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
