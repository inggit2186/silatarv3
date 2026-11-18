@php
    $statusLabel = ['belum_mulai' => 'Belum Mulai', 'berjalan' => 'Berjalan', 'selesai' => 'Selesai'];
    $statusColor = ['belum_mulai' => '#8a8580', 'berjalan' => '#b86e28', 'selesai' => '#1f7a5c'];
    $nomorUrut = 0;
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hasil Ujian {{ $ujian->kode }} - SILATAR</title>
    <style>
        @font-face { font-family: 'Chakra Petch'; font-weight: 400; font-style: normal; src: url('{{ $fontBase }}/ChakraPetch-Regular.ttf') format('truetype'); }
        @font-face { font-family: 'Chakra Petch'; font-weight: 500; font-style: normal; src: url('{{ $fontBase }}/ChakraPetch-Medium.ttf') format('truetype'); }
        @font-face { font-family: 'Chakra Petch'; font-weight: 600; font-style: normal; src: url('{{ $fontBase }}/ChakraPetch-SemiBold.ttf') format('truetype'); }
        @font-face { font-family: 'Chakra Petch'; font-weight: 700; font-style: normal; src: url('{{ $fontBase }}/ChakraPetch-Bold.ttf') format('truetype'); }
        @font-face { font-family: 'Bebas Neue'; font-weight: 400; font-style: normal; src: url('{{ $fontBase }}/BebasNeue-Regular.ttf') format('truetype'); }
        @font-face { font-family: 'Barlow'; font-weight: 400; font-style: normal; src: url('{{ $fontBase }}/Barlow-Regular.ttf') format('truetype'); }
        @font-face { font-family: 'Barlow'; font-weight: 600; font-style: normal; src: url('{{ $fontBase }}/Barlow-SemiBold.ttf') format('truetype'); }
        @font-face { font-family: 'Barlow'; font-weight: 700; font-style: normal; src: url('{{ $fontBase }}/Barlow-Bold.ttf') format('truetype'); }
        @font-face { font-family: 'Barlow Condensed'; font-weight: 600; font-style: normal; src: url('{{ $fontBase }}/BarlowCondensed-SemiBold.ttf') format('truetype'); }
        @font-face { font-family: 'Barlow Condensed'; font-weight: 700; font-style: normal; src: url('{{ $fontBase }}/BarlowCondensed-Bold.ttf') format('truetype'); }
        @font-face { font-family: 'Space Mono'; font-weight: 400; font-style: normal; src: url('{{ $fontBase }}/SpaceMono-Regular.ttf') format('truetype'); }
        @font-face { font-family: 'Space Mono'; font-weight: 700; font-style: normal; src: url('{{ $fontBase }}/SpaceMono-Bold.ttf') format('truetype'); }

        @page { margin: 10mm 12mm 16mm 12mm; }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Barlow', sans-serif;
            font-size: 8.6pt;
            color: #2d2824;
            background: #ffffff;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        table { border-collapse: collapse; }

        /* ── Masthead ───────────────────────────────────────── */
        .masthead td { vertical-align: middle; }
        .letterhead { width: 132mm; }
        .seal-cell { width: 26mm; text-align: right; }
        .seal {
            width: 17mm; height: 17mm; border: 0.7pt solid #d4a853; border-radius: 50%;
            padding: 2.2mm; margin-left: auto;
        }
        .seal-ring { width: 100%; height: 100%; border: 0.7pt solid #d4a853; border-radius: 50%; padding: 1.6mm; }
        .seal-core { width: 100%; height: 100%; background: #d4a853; border-radius: 50%; }
        .seal-word {
            font-family: 'Bebas Neue'; font-size: 11pt; color: #b86e28;
            letter-spacing: 1.2pt; margin-top: 1mm;
        }

        /* ── Judul dokumen ──────────────────────────────────── */
        .head-rule { margin-top: 3mm; border-top: 1.6pt solid #1a2c35; }
        .head-rule-accent { margin-top: 0.8mm; border-top: 0.6pt solid #d4a853; width: 46mm; }
        .kicker {
            font-family: 'Barlow Condensed'; font-weight: 700; font-size: 7.4pt;
            text-transform: uppercase; letter-spacing: 2.4pt; color: #b86e28; margin-top: 5mm;
        }
        .doc-title {
            font-family: 'Bebas Neue'; font-weight: 400; font-size: 30pt; line-height: 1.05;
            color: #1a2c35; letter-spacing: 0.6pt; margin-top: 1.4mm;
        }
        .doc-sub {
            font-family: 'Chakra Petch'; font-weight: 500; font-size: 9pt; color: #514c46; margin-top: 1.2mm;
        }
        .doc-sub b { font-family: 'Chakra Petch'; font-weight: 600; color: #b86e28; }

        /* ── Meta ───────────────────────────────────────────── */
        .meta { margin-top: 4mm; }
        .meta td {
            width: 33.33%; border: 0.5pt solid #d6d3cb; background: #f7f6f3;
            padding: 2.2mm 2.6mm; vertical-align: top;
        }
        .meta-label {
            font-family: 'Barlow Condensed'; font-weight: 600; font-size: 6.4pt;
            text-transform: uppercase; letter-spacing: 1.1pt; color: #8a8580;
        }
        .meta-value { font-family: 'Chakra Petch'; font-weight: 600; font-size: 9pt; color: #2d2824; margin-top: 0.8mm; }
        .meta-value.mono { font-family: 'Space Mono'; font-weight: 700; font-size: 8.4pt; }

        /* ── Statistik ──────────────────────────────────────── */
        .stats { margin-top: 4.5mm; }
        .stats td { width: 16.66%; border: 0.5pt solid #d6d3cb; padding: 0; vertical-align: top; }
        .stat-inner { padding: 2.6mm 3mm 2.8mm 3mm; }
        .stat-bar { height: 1.8pt; }
        .stat-value { font-family: 'Bebas Neue'; font-size: 23pt; line-height: 1; color: #1a2c35; margin-top: 1.8mm; }
        .stat-label {
            font-family: 'Barlow Condensed'; font-weight: 600; font-size: 6.6pt;
            text-transform: uppercase; letter-spacing: 1pt; color: #8a8580; margin-top: 1mm;
        }
        .stat-foot { font-family: 'Space Mono'; font-size: 6pt; color: #b8b5b0; margin-top: 0.8mm; }

        /* ── Ledger ─────────────────────────────────────────── */
        .section-tag {
            font-family: 'Barlow Condensed'; font-weight: 700; font-size: 8pt; text-transform: uppercase;
            letter-spacing: 1.6pt; color: #1a2c35; margin-top: 6mm;
            border-bottom: 0.8pt solid #1a2c35; padding-bottom: 1.2mm;
        }
        .section-tag span { color: #b86e28; }
        .ledger { margin-top: 2.5mm; }
        .ledger thead th {
            background: #1a2c35; color: #e4c078;
            font-family: 'Barlow Condensed'; font-weight: 600; font-size: 6.8pt;
            text-transform: uppercase; letter-spacing: 0.9pt;
            padding: 1.9mm 1.6mm; text-align: left;
        }
        .ledger tbody tr { page-break-inside: avoid; }
        .ledger tbody td { border-bottom: 0.4pt solid #e6e4df; padding: 1.6mm 1.6mm; vertical-align: middle; }
        .ledger tbody tr.odd td { background: #faf9f6; }
        .c-no { width: 8mm; }
        .c-nama { width: auto; }
        .c-nip { width: 32mm; }
        .c-status { width: 24mm; }
        .c-nilai { width: 16mm; }
        .c-waktu { width: 28mm; }
        .c-pelanggaran { width: 22mm; }

        .idx { font-family: 'Space Mono'; font-weight: 700; font-size: 7.6pt; color: #8a8580; }
        .nama { font-family: 'Chakra Petch'; font-weight: 600; font-size: 8.4pt; color: #2d2824; }
        .nip { font-family: 'Space Mono'; font-size: 6.8pt; color: #8a8580; margin-top: 0.5mm; }
        .clock { font-family: 'Space Mono'; font-weight: 700; font-size: 7.4pt; color: #2d2824; }
        .clock.none { color: #b8b5b0; font-weight: 400; }
        .nilai { font-family: 'Space Mono'; font-weight: 700; font-size: 8.6pt; color: #b86e28; }
        .chip {
            display: inline-block; padding: 0.9mm 2mm; border-radius: 1.2mm;
            font-family: 'Barlow Condensed'; font-weight: 600; font-size: 6.8pt;
            text-transform: uppercase; letter-spacing: 0.5pt; white-space: nowrap;
        }
        .chip-dot { display: inline-block; width: 1.5mm; height: 1.5mm; border-radius: 50%; margin-right: 1.3mm; vertical-align: middle; }
        .violation { font-family: 'Barlow Condensed'; font-weight: 700; font-size: 7pt; color: #b03a3a; }

        /* ── Rincian jawaban per peserta ────────────────────── */
        .detail-page { page-break-before: always; margin-top: 0; }
        .detail-head {
            border: 0.6pt solid #d4a853; background: #fdfaf3;
            padding: 2.6mm 3mm; margin-bottom: 3mm;
        }
        .detail-name { font-family: 'Chakra Petch'; font-weight: 700; font-size: 11pt; color: #1a2c35; }
        .detail-meta { font-family: 'Space Mono'; font-size: 7pt; color: #8a8580; margin-top: 0.8mm; }
        .detail-score {
            float: right; text-align: right;
            font-family: 'Bebas Neue'; font-size: 22pt; line-height: 1; color: #b86e28;
        }
        .detail-score small {
            display: block; font-family: 'Barlow Condensed'; font-size: 6.4pt;
            text-transform: uppercase; letter-spacing: 1pt; color: #8a8580; margin-bottom: 0.8mm;
        }
        .qa { margin-top: 2.5mm; }
        .qa-head { display: flex; gap: 2.4mm; align-items: baseline; }
        .qa-no {
            font-family: 'Space Mono'; font-weight: 700; font-size: 7.4pt; color: #b86e28;
            min-width: 7mm;
        }
        .qa-text { font-family: 'Chakra Petch'; font-weight: 600; font-size: 8.2pt; color: #2d2824; }
        .qa-poin { font-family: 'Space Mono'; font-size: 6.4pt; color: #b8b5b0; margin-left: 1.6mm; }
        .qa-opts { margin: 1.2mm 0 0 9.4mm; }
        .qa-opt {
            font-size: 7.4pt; padding: 0.9mm 2mm; border-left: 1.6pt solid #e6e4df;
            margin-bottom: 0.8mm; color: #514c46;
        }
        .qa-opt.chosen { border-left-color: #1f7a5c; background: #f2f8f5; color: #2d2824; }
        .qa-opt.chosen.wrong { border-left-color: #b03a3a; background: #fdf4f4; }
        .qa-opt.key { border-left-color: #1f7a5c; color: #1f7a5c; font-weight: 600; }
        .qa-opt .tag {
            font-family: 'Barlow Condensed'; font-weight: 700; font-size: 6pt;
            text-transform: uppercase; letter-spacing: 0.6pt; margin-left: 1.6mm;
        }
        .qa-opt.chosen .tag { color: #1f7a5c; }
        .qa-opt.chosen.wrong .tag { color: #b03a3a; }
        .qa-opt.key .tag { color: #1f7a5c; }
        .qa-esai {
            margin: 1.2mm 0 0 9.4mm; padding: 1.8mm 2.4mm;
            background: #f7f6f3; border: 0.4pt solid #e6e4df;
            font-size: 7.6pt; color: #2d2824; white-space: pre-line;
        }
        .qa-empty { margin: 1.2mm 0 0 9.4mm; font-size: 7.4pt; color: #b8b5b0; font-style: italic; }
        .qa-file { margin: 0.8mm 0 0 9.4mm; font-family: 'Space Mono'; font-size: 6.6pt; color: #b86e28; }

        /* ── Empty state ────────────────────────────────────── */
        .empty {
            border: 0.6pt dashed #b8b5b0; padding: 9mm; text-align: center; margin-top: 2.5mm;
        }
        .empty-title { font-family: 'Bebas Neue'; font-size: 15pt; color: #1a2c35; letter-spacing: 0.6pt; }
        .empty-text { font-size: 8pt; color: #8a8580; margin-top: 1.5mm; }

        /* ── Tanda tangan ───────────────────────────────────── */
        .sign { margin-top: 8mm; }
        .sign td { vertical-align: top; }
        .sign-note { font-size: 7.4pt; color: #514c46; line-height: 1.5; }
        .sign-title {
            font-family: 'Barlow Condensed'; font-weight: 600; font-size: 7pt; text-transform: uppercase;
            letter-spacing: 1pt; color: #8a8580; margin-top: 1mm;
        }
        .sign-name {
            font-family: 'Chakra Petch'; font-weight: 600; font-size: 9pt; color: #2d2824;
            border-bottom: 0.5pt solid #b8b5b0; padding-bottom: 1mm; margin-top: 16mm;
        }
        .sign-nip { font-family: 'Space Mono'; font-size: 7pt; color: #8a8580; margin-top: 1mm; }

        /* ── Footer halaman ─────────────────────────────────── */
        .pagefoot {
            position: fixed; bottom: 0; left: 0; right: 0;
            border-top: 0.4pt solid #d6d3cb; padding-top: 1.4mm;
            font-family: 'Barlow Condensed'; font-size: 6.4pt; color: #8a8580; letter-spacing: 0.4pt;
        }
        .pagefoot td { vertical-align: middle; }
        .pagefoot .right { text-align: right; }

        /* ── Toolbar layar (tidak ikut ke PDF/print) ───────── */
        .toolbar {
            position: fixed; top: 0; right: 0; padding: 10px; z-index: 10;
            font-family: 'Chakra Petch', sans-serif;
        }
        .toolbar a, .toolbar button, .toolbar label {
            display: inline-block; padding: 9px 16px; margin-left: 8px; border-radius: 10px;
            font-family: 'Chakra Petch'; font-weight: 600; font-size: 12px; letter-spacing: 0.4pt;
            text-decoration: none; border: 1px solid #d4a853; cursor: pointer;
            background: #ffffff; color: #1a2c35;
        }
        .toolbar .btn-primary { background: #d4a853; color: #1a2c35; }
        .toolbar .toggle { display: inline-flex; align-items: center; gap: 7px; }
        .toolbar .toggle input { accent-color: #d4a853; width: 15px; height: 15px; }

        /* Rincian jawaban disembunyikan secara default, dapat diaktifkan di layar. */
        .detail-block { display: none; }
        body.with-detail .detail-block { display: block; }

        @media screen {
            body { background: #efece6; padding: 24px; }
            .sheet {
                max-width: 210mm; margin: 0 auto; background: #ffffff; padding: 10mm 12mm 16mm 12mm;
                box-shadow: 0 8px 30px rgba(26, 44, 53, 0.18);
            }
            .print-only { display: none; }
        }
        @media print {
            .screen-only { display: none; }
            .print-only { display: block; }
            .sheet { box-shadow: none; padding: 0; max-width: none; }
        }
    </style>
</head>
<body>
    <div class="toolbar screen-only">
        <a href="{{ route('admin.ujian.hasil', $ujian->id) }}">&larr; Kembali</a>
        <label class="toggle"><input type="checkbox" id="toggleDetail"> Sertakan rincian jawaban</label>
        <button class="btn-primary" onclick="window.print()">Cetak / Simpan PDF</button>
    </div>

    <div class="sheet">
        {{-- ── Masthead ─────────────────────────────────────────────── --}}
        <table class="masthead" width="100%">
            <tr>
                <td class="letterhead">
                    <img src="{{ $letterhead }}" alt="Kementerian Agama" style="width: 132mm;">
                </td>
                <td class="seal-cell">
                    <div class="seal">
                        <div class="seal-ring">
                            <div class="seal-core"></div>
                        </div>
                    </div>
                    <div class="seal-word">SILATAR</div>
                </td>
            </tr>
        </table>

        <div class="head-rule"></div>
        <div class="head-rule-accent"></div>

        <div class="kicker">Dokumen Rekapitulasi Hasil Ujian</div>
        <div class="doc-title">HASIL UJIAN</div>
        <div class="doc-sub">
            <b>{{ $ujian->kode }}</b> &nbsp;&middot;&nbsp; {{ $ujian->judul }}
            &nbsp;&middot;&nbsp; {{ $peserta->count() }} peserta terdaftar
        </div>

        {{-- ── Meta ─────────────────────────────────────────────────── --}}
        <table class="meta" width="100%">
            <tr>
                <td>
                    <div class="meta-label">Kode Ujian</div>
                    <div class="meta-value mono">{{ $ujian->kode }}</div>
                </td>
                <td>
                    <div class="meta-label">Durasi Ujian</div>
                    <div class="meta-value mono">{{ $ujian->durasi_menit }} menit</div>
                </td>
                <td>
                    <div class="meta-label">Jumlah Soal</div>
                    <div class="meta-value mono">{{ $soal->count() }} soal / {{ $totalPoin }} poin</div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="meta-label">Status Ujian</div>
                    <div class="meta-value">{{ ['draft' => 'Draft', 'terbit' => 'Terbit', 'selesai' => 'Selesai'][$ujian->status] ?? $ujian->status }}</div>
                </td>
                <td>
                    <div class="meta-label">Dicetak Pada</div>
                    <div class="meta-value mono">{{ $cetakPada }}</div>
                </td>
                <td>
                    <div class="meta-label">Dicetak Oleh</div>
                    <div class="meta-value">{{ $dicetakOleh }}</div>
                </td>
            </tr>
        </table>

        {{-- ── Statistik ─────────────────────────────────────────────── --}}
        <table class="stats" width="100%">
            <tr>
                @php
                    $kartu = [
                        ['label' => 'Total Peserta', 'value' => $stats['total'], 'accent' => '#1a2c35', 'foot' => $stats['total'] . ' terdaftar'],
                        ['label' => 'Selesai', 'value' => $stats['selesai'], 'accent' => '#1f7a5c', 'foot' => $stats['berjalan'] . ' sedang berjalan'],
                        ['label' => 'Rata-rata Nilai', 'value' => $stats['rata_rata'] !== null ? number_format($stats['rata_rata'], 2) : '-', 'accent' => '#b86e28', 'foot' => 'dari nilai peserta selesai'],
                        ['label' => 'Nilai Tertinggi', 'value' => $stats['tertinggi'] !== null ? number_format($stats['tertinggi'], 2) : '-', 'accent' => '#b86e28', 'foot' => 'nilai maksimal'],
                        ['label' => 'Nilai Terendah', 'value' => $stats['terendah'] !== null ? number_format($stats['terendah'], 2) : '-', 'accent' => '#8a8580', 'foot' => 'nilai minimal'],
                        ['label' => 'Pelanggaran', 'value' => $stats['total_pelanggaran'], 'accent' => '#b03a3a', 'foot' => 'kejadian tercatat'],
                    ];
                @endphp
                @foreach ($kartu as $k)
                    <td>
                        <div class="stat-bar" style="background: {{ $k['accent'] }};"></div>
                        <div class="stat-inner">
                            <div class="stat-value" style="color: {{ $k['accent'] }};">{{ $k['value'] }}</div>
                            <div class="stat-label">{{ $k['label'] }}</div>
                            <div class="stat-foot">{{ $k['foot'] }}</div>
                        </div>
                    </td>
                @endforeach
            </tr>
        </table>

        {{-- ── Rekap peserta ─────────────────────────────────────────── --}}
        <div class="section-tag">Rekap <span>/</span> Hasil Peserta</div>

        @if ($peserta->count() > 0)
            <table class="ledger" width="100%">
                <thead>
                    <tr>
                        <th class="c-no">#</th>
                        <th class="c-nama">Nama Pegawai</th>
                        <th class="c-nip">Nomor Induk</th>
                        <th class="c-status">Status</th>
                        <th class="c-nilai">Nilai</th>
                        <th class="c-waktu">Dikerjakan</th>
                        <th class="c-pelanggaran">Pelanggaran</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($peserta as $item)
                        @php
                            $nomorUrut++;
                            $warna = $statusColor[$item->status] ?? '#8a8580';
                            $daftarPelanggaran = $pelanggaran->get($item->user_id, collect());
                        @endphp
                        <tr class="{{ $nomorUrut % 2 === 0 ? 'odd' : '' }}">
                            <td class="c-no"><span class="idx">{{ str_pad((string) $nomorUrut, 2, '0', STR_PAD_LEFT) }}</span></td>
                            <td class="c-nama"><span class="nama">{{ $item->user->name ?? '-' }}</span></td>
                            <td class="c-nip"><span class="nip">{{ $item->user->nomor_induk ?? '-' }}</span></td>
                            <td class="c-status">
                                <span class="chip" style="background: {{ $warna }}18; color: {{ $warna }};">
                                    <span class="chip-dot" style="background: {{ $warna }};"></span>{{ $statusLabel[$item->status] ?? $item->status }}
                                </span>
                            </td>
                            <td class="c-nilai">
                                @if ($item->nilai !== null)
                                    <span class="nilai">{{ number_format($item->nilai, 2) }}</span>
                                @else
                                    <span class="clock none">—</span>
                                @endif
                            </td>
                            <td class="c-waktu">
                                @if ($item->submitted_at)
                                    <span class="clock">{{ $item->submitted_at->format('d/m/Y H:i') }}</span>
                                @elseif ($item->started_at)
                                    <span class="clock">Mulai {{ $item->started_at->format('d/m/Y H:i') }}</span>
                                @else
                                    <span class="clock none">—</span>
                                @endif
                            </td>
                            <td class="c-pelanggaran">
                                @if ($daftarPelanggaran->isNotEmpty())
                                    <span class="violation">{{ $daftarPelanggaran->count() }} kali</span>
                                @else
                                    <span class="clock none">—</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="empty">
                <div class="empty-title">Belum Ada Peserta</div>
                <div class="empty-text">Belum ada peserta yang terdaftar pada ujian {{ $ujian->kode }}.</div>
            </div>
        @endif

        {{-- ── Rincian jawaban setiap peserta ────────────────────────── --}}
        @if ($peserta->count() > 0)
            <div class="section-tag">Rincian <span>/</span> Jawaban Peserta</div>
            <p style="font-size:7.4pt; color:#8a8580; margin-top:1.4mm;">
                Bagian ini hanya dicetak bila opsi &ldquo;Sertakan rincian jawaban&rdquo; diaktifkan pada toolbar di atas.
            </p>

            @foreach ($peserta as $item)
                @php
                    $data = $rincianPeserta[$item->user_id] ?? ['rincian' => [], 'benar' => 0, 'salah' => 0, 'kosong' => 0];
                    $daftarPelanggaran = $pelanggaran->get($item->user_id, collect());
                @endphp
                <div class="detail-block detail-page">
                    <div class="detail-head">
                        <div class="detail-score">
                            <small>Nilai</small>
                            {{ $item->nilai !== null ? number_format($item->nilai, 2) : '—' }}
                        </div>
                        <div class="detail-name">{{ $item->user->name ?? '-' }}</div>
                        <div class="detail-meta">
                            {{ $item->user->nomor_induk ?? '-' }}
                            &nbsp;·&nbsp; {{ $statusLabel[$item->status] ?? $item->status }}
                            &nbsp;·&nbsp; Benar {{ $data['benar'] }} / Salah {{ $data['salah'] }} / Kosong {{ $data['kosong'] }}
                            @if ($daftarPelanggaran->isNotEmpty())
                                &nbsp;·&nbsp; Pelanggaran {{ $daftarPelanggaran->count() }}
                            @endif
                        </div>
                    </div>

                    @forelse ($data['rincian'] as $soal)
                        <div class="qa">
                            <div class="qa-head">
                                <span class="qa-no">{{ str_pad((string) $soal['nomor'], 2, '0', STR_PAD_LEFT) }}</span>
                                <span class="qa-text">{{ $soal['pertanyaan'] }}</span>
                                <span class="qa-poin">{{ $soal['poin'] }} poin</span>
                            </div>

                            @if ($soal['tipe'] === 'pilihan_ganda')
                                <div class="qa-opts">
                                    @foreach ($soal['pilihan'] as $pilihan)
                                        @php
                                            $kelas = '';
                                            if ($pilihan['dipilih'] && $pilihan['benar']) {
                                                $kelas = 'chosen';
                                            } elseif ($pilihan['dipilih']) {
                                                $kelas = 'chosen wrong';
                                            } elseif ($pilihan['benar']) {
                                                $kelas = 'key';
                                            }
                                        @endphp
                                        <div class="qa-opt {{ $kelas }}">
                                            <b>{{ $pilihan['label'] }}.</b> {{ $pilihan['teks'] }}
                                            @if ($pilihan['dipilih'] && $pilihan['benar'])
                                                <span class="tag">&bull; Jawaban peserta (benar)</span>
                                            @elseif ($pilihan['dipilih'])
                                                <span class="tag">&bull; Jawaban peserta</span>
                                            @elseif ($pilihan['benar'])
                                                <span class="tag">&bull; Kunci jawaban</span>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                @if ($soal['esai'])
                                    <div class="qa-esai">{{ $soal['esai'] }}</div>
                                @else
                                    <div class="qa-empty">Peserta tidak menjawab soal ini.</div>
                                @endif
                                @if ($soal['file_nama'])
                                    <div class="qa-file">Lampiran: {{ $soal['file_nama'] }}</div>
                                @endif
                            @endif
                        </div>
                    @empty
                        <div class="qa-empty">Ujian belum memiliki soal.</div>
                    @endforelse
                </div>
            @endforeach
        @endif

        {{-- ── Tanda tangan ─────────────────────────────────────────── --}}
        <table class="sign" width="100%">
            <tr>
                <td width="56%">
                    <div class="sign-note">
                        Dokumen ini dihasilkan otomatis oleh <b>SILATAR V2</b> dari basis data
                        <b>ujian</b>, <b>ujian_peserta</b>, dan <b>ujian_jawaban</b> serta sah sebagai
                        bukti rekapitulasi hasil ujian <b>{{ $ujian->kode }}</b>. Data tidak dapat
                        dimodifikasi setelah dokumen dicetak.
                    </div>
                    <div class="sign-title">Catatan Verifikasi</div>
                </td>
                <td width="44%" style="text-align: right;">
                    <div class="sign-title" style="text-align: right;">Dicetak Oleh</div>
                    <div class="sign-name">{{ $dicetakOleh }}</div>
                    <div class="sign-nip">Administrator SILATAR</div>
                </td>
            </tr>
        </table>

        {{-- ── Footer berulang ───────────────────────────────────────── --}}
        <div class="pagefoot print-only">
            <table width="100%">
                <tr>
                    <td>Kantor Kementerian Agama Kabupaten Tanah Datar &nbsp;·&nbsp; SILATAR V2 — Sistem Informasi Layanan Terpadu</td>
                    <td class="right">{{ $ujian->kode }} &nbsp;·&nbsp; {{ $ujian->judul }}</td>
                </tr>
            </table>
        </div>
    </div>

    <script>
        (function () {
            var toggle = document.getElementById('toggleDetail');
            if (!toggle) {
                return;
            }
            toggle.addEventListener('change', function () {
                document.body.classList.toggle('with-detail', toggle.checked);
            });
        })();
    </script>
</body>
</html>
