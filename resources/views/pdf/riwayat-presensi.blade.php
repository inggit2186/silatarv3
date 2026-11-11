@php
    use App\Support\PresensiStatus;
    use Carbon\Carbon;

    $rows = $rows ?? collect();
    $errorStatuses = PresensiStatus::errorStatuses();
    $nomorUrut = 0;
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>{{ $tabLabel }} - {{ $periodeLabel }}</title>
    <style>
        @font-face { font-family: 'Chakra Petch'; font-weight: 400; font-style: normal; src: url('{{ $fontBase }}/ChakraPetch-Regular.ttf') format('truetype'); }
        @font-face { font-family: 'Chakra Petch'; font-weight: 500; font-style: normal; src: url('{{ $fontBase }}/ChakraPetch-Medium.ttf') format('truetype'); }
        @font-face { font-family: 'Chakra Petch'; font-weight: 600; font-style: normal; src: url('{{ $fontBase }}/ChakraPetch-SemiBold.ttf') format('truetype'); }
        @font-face { font-family: 'Chakra Petch'; font-weight: 700; font-style: normal; src: url('{{ $fontBase }}/ChakraPetch-Bold.ttf') format('truetype'); }
        @font-face { font-family: 'Bebas Neue'; font-weight: 400; font-style: normal; src: url('{{ $fontBase }}/BebasNeue-Regular.ttf') format('truetype'); }
        @font-face { font-family: 'Barlow'; font-weight: 400; font-style: normal; src: url('{{ $fontBase }}/Barlow-Regular.ttf') format('truetype'); }
        @font-face { font-family: 'Barlow'; font-weight: 600; font-style: normal; src: url('{{ $fontBase }}/Barlow-SemiBold.ttf') format('truetype'); }
        @font-face { font-family: 'Barlow'; font-weight: 700; font-style: normal; src: url('{{ $fontBase }}/Barlow-Bold.ttf') format('truetype'); }
        @font-face { font-family: 'Barlow Condensed'; font-weight: 500; font-style: normal; src: url('{{ $fontBase }}/BarlowCondensed-Medium.ttf') format('truetype'); }
        @font-face { font-family: 'Barlow Condensed'; font-weight: 600; font-style: normal; src: url('{{ $fontBase }}/BarlowCondensed-SemiBold.ttf') format('truetype'); }
        @font-face { font-family: 'Barlow Condensed'; font-weight: 700; font-style: normal; src: url('{{ $fontBase }}/BarlowCondensed-Bold.ttf') format('truetype'); }
        @font-face { font-family: 'Space Mono'; font-weight: 400; font-style: normal; src: url('{{ $fontBase }}/SpaceMono-Regular.ttf') format('truetype'); }
        @font-face { font-family: 'Space Mono'; font-weight: 700; font-style: normal; src: url('{{ $fontBase }}/SpaceMono-Bold.ttf') format('truetype'); }

        @page { margin: 10mm 12mm 16mm 12mm; }

        * { margin: 0; padding: 0; }
        body {
            font-family: 'Barlow', sans-serif;
            font-size: 8.6pt;
            color: #2d2824;
            background: #ffffff;
            -webkit-print-color-adjust: exact;
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
            font-family: 'Barlow Condensed'; font-weight: 600; font-size: 7.4pt;
            text-transform: uppercase; letter-spacing: 2.4pt; color: #b86e28; margin-top: 5mm;
        }
        .doc-title {
            font-family: 'Bebas Neue'; font-weight: 400; font-size: 30pt; line-height: 1.05;
            color: #1a2c35; letter-spacing: 0.6pt; margin-top: 1.4mm;
        }
        .doc-sub {
            font-family: 'Chakra Petch'; font-weight: 500; font-size: 9pt; color: #514c46; margin-top: 1.2mm;
        }
        .doc-sub b { color: #b86e28; font-weight: 600; }

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
        .meta-value {
            font-family: 'Chakra Petch'; font-weight: 600; font-size: 9pt; color: #2d2824; margin-top: 0.8mm;
        }
        .meta-value.mono { font-family: 'Space Mono'; font-weight: 700; font-size: 8.4pt; }

        /* ── Statistik ──────────────────────────────────────── */
        .stats { margin-top: 4.5mm; }
        .stats td { width: 25%; border: 0.5pt solid #d6d3cb; padding: 0; vertical-align: top; }
        .stat-inner { padding: 2.6mm 3mm 2.8mm 3mm; }
        .stat-bar { height: 1.8pt; }
        .stat-value {
            font-family: 'Bebas Neue'; font-size: 25pt; line-height: 1; color: #1a2c35; margin-top: 1.8mm;
        }
        .stat-label {
            font-family: 'Barlow Condensed'; font-weight: 600; font-size: 6.8pt;
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
        .ledger tbody td {
            border-bottom: 0.4pt solid #e6e4df; padding: 1.6mm 1.6mm; vertical-align: middle;
        }
        .ledger tbody tr.odd td { background: #faf9f6; }
        .c-no { width: 8mm; }
        .c-date { width: 27mm; }
        .c-status { width: 32mm; }
        .c-time { width: 16mm; }
        .c-dur { width: 18mm; }
        .c-note { width: auto; }

        .idx {
            font-family: 'Space Mono'; font-weight: 700; font-size: 7.6pt; color: #8a8580;
        }
        .day {
            font-family: 'Bebas Neue'; font-size: 13pt; line-height: 1; color: #1a2c35;
        }
        .date-sub {
            font-family: 'Barlow Condensed'; font-weight: 600; font-size: 6.4pt;
            text-transform: uppercase; letter-spacing: 0.6pt; color: #8a8580;
        }
        .chip {
            display: inline-block; padding: 0.9mm 2mm; border-radius: 1.2mm;
            font-family: 'Barlow Condensed'; font-weight: 600; font-size: 6.8pt;
            text-transform: uppercase; letter-spacing: 0.5pt; white-space: nowrap;
        }
        .chip-dot {
            display: inline-block; width: 1.5mm; height: 1.5mm; border-radius: 50%;
            margin-right: 1.3mm; vertical-align: middle;
        }
        .clock { font-family: 'Space Mono'; font-weight: 700; font-size: 8.2pt; color: #2d2824; }
        .clock.none { color: #b8b5b0; font-weight: 400; }
        .note-text { font-size: 7.6pt; color: #514c46; }
        .note-loc { font-family: 'Space Mono'; font-size: 6.2pt; color: #8a8580; margin-top: 0.6mm; }
        .flag {
            display: inline-block; padding: 0.4mm 1.4mm; border-radius: 0.8mm;
            font-family: 'Barlow Condensed'; font-weight: 700; font-size: 6.2pt;
            text-transform: uppercase; letter-spacing: 0.6pt;
        }

        /* ── Empty state ────────────────────────────────────── */
        .empty {
            border: 0.6pt dashed #b8b5b0; padding: 9mm; text-align: center; margin-top: 2.5mm;
        }
        .empty-title {
            font-family: 'Bebas Neue'; font-size: 15pt; color: #1a2c35; letter-spacing: 0.6pt;
        }
        .empty-text { font-size: 8pt; color: #8a8580; margin-top: 1.5mm; }

        /* ── Tanda tangan ──────────────────────────────────── */
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
            position: fixed; top: 0; right: 0; padding: 10px;
            font-family: 'Chakra Petch', sans-serif;
        }
        .toolbar a, .toolbar button {
            display: inline-block; padding: 9px 16px; margin-left: 8px; border-radius: 10px;
            font-family: 'Chakra Petch'; font-weight: 600; font-size: 12px; letter-spacing: 0.4pt;
            text-decoration: none; border: 1px solid #d4a853; cursor: pointer;
        }
        .toolbar .btn-primary { background: #d4a853; color: #1a2c35; }
        .toolbar .btn-ghost { background: #ffffff; color: #1a2c35; }

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
        }
    </style>
</head>
<body>
    <div class="toolbar screen-only">
        <a class="btn-ghost" href="{{ $backUrl }}">← Kembali</a>
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

        <div class="kicker">Dokumen Rekapitulasi Absensi</div>
        <div class="doc-title">{{ strtoupper($tabLabel) }}</div>
        <div class="doc-sub">
            Periode <b>{{ $periodeLabel }}</b> &nbsp;·&nbsp; Sumber data <b>tabel ktd_presensi</b>
            &nbsp;·&nbsp; {{ $rows->count() }} baris data
        </div>

        {{-- ── Meta ─────────────────────────────────────────────────── --}}
        <table class="meta" width="100%">
            <tr>
                <td>
                    <div class="meta-label">Nama Pegawai</div>
                    <div class="meta-value">{{ $user->name ?? '-' }}</div>
                </td>
                <td>
                    <div class="meta-label">NIP</div>
                    <div class="meta-value mono">{{ $nip }}</div>
                </td>
                <td>
                    <div class="meta-label">Unit Kerja</div>
                    <div class="meta-value">{{ $unitKerja }}</div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="meta-label">Periode</div>
                    <div class="meta-value">{{ $periodeLabel }}</div>
                </td>
                <td>
                    <div class="meta-label">Jenis Laporan</div>
                    <div class="meta-value">{{ $tabLabel }}</div>
                </td>
                <td>
                    <div class="meta-label">Dicetak Pada</div>
                    <div class="meta-value mono">{{ $cetakPada }}</div>
                </td>
            </tr>
        </table>

        {{-- ── Statistik ─────────────────────────────────────────────── --}}
        <table class="stats" width="100%">
            <tr>
                @foreach ($statistik as $s)
                    <td>
                        <div class="stat-bar" style="background: {{ $s['accent'] }};"></div>
                        <div class="stat-inner">
                            <div class="stat-value" style="color: {{ $s['accent'] }};">{{ $s['value'] }}</div>
                            <div class="stat-label">{{ $s['label'] }}</div>
                            <div class="stat-foot">{{ $s['foot'] }}</div>
                        </div>
                    </td>
                @endforeach
            </tr>
        </table>

        {{-- ── Ledger ────────────────────────────────────────────────── --}}
        <div class="section-tag">Rincian <span>/</span> {{ $tabLabel }}</div>

        @if ($rows->count() > 0)
            <table class="ledger" width="100%">
                <thead>
                    <tr>
                        <th class="c-no">#</th>
                        <th class="c-date">Tanggal</th>
                        <th class="c-status">Status</th>
                        <th class="c-time">Masuk</th>
                        <th class="c-time">Pulang</th>
                        <th class="c-dur">Durasi</th>
                        <th class="c-note">Keterangan / Lokasi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rows as $row)
                        @php
                            $carbon = Carbon::parse($row->tanggal)->locale('id');
                            $meta = PresensiStatus::meta($row->status, (bool) ($row->m_absen || $row->p_absen));
                            $masuk = $row->m_absen ? Carbon::parse($row->tanggal.' '.$row->m_absen) : null;
                            $pulang = $row->p_absen ? Carbon::parse($row->tanggal.' '.$row->p_absen) : null;
                            $durasi = ($masuk && $pulang) ? (int) round($masuk->diffInMinutes($pulang, false)) : null;
                            $nomorUrut++;
                        @endphp
                        <tr class="{{ $nomorUrut % 2 === 0 ? 'odd' : '' }}">
                            <td class="c-no"><span class="idx">{{ str_pad((string) $nomorUrut, 2, '0', STR_PAD_LEFT) }}</span></td>
                            <td class="c-date">
                                <span class="day">{{ $carbon->format('d') }}</span>
                                <span class="date-sub">{{ $carbon->isoFormat('MMM Y') }}</span>
                                <div class="date-sub">{{ $carbon->isoFormat('dddd') }}</div>
                            </td>
                            <td class="c-status">
                                <span class="chip" style="background: {{ $meta['bg'] }}; color: {{ $meta['fg'] }};">
                                    <span class="chip-dot" style="background: {{ $meta['accent'] }};"></span>{{ $meta['label'] }}
                                </span>
                                @if (in_array($row->status, $errorStatuses, true))
                                    <div style="margin-top: 0.8mm;">
                                        <span class="flag" style="background: {{ $meta['accent'] }}; color: #ffffff;">Perlu verifikasi</span>
                                    </div>
                                @endif
                            </td>
                            <td class="c-time">
                                @if ($row->m_absen)
                                    <span class="clock">{{ substr($row->m_absen, 0, 5) }}</span>
                                @else
                                    <span class="clock none">--:--</span>
                                @endif
                            </td>
                            <td class="c-time">
                                @if ($row->p_absen)
                                    <span class="clock">{{ substr($row->p_absen, 0, 5) }}</span>
                                @else
                                    <span class="clock none">--:--</span>
                                @endif
                            </td>
                            <td class="c-dur">
                                @if ($durasi !== null)
                                    <span class="clock">{{ intdiv($durasi, 60) }}j {{ $durasi % 60 }}m</span>
                                @else
                                    <span class="clock none">—</span>
                                @endif
                            </td>
                            <td class="c-note">
                                @if ($row->keterangan)
                                    <div class="note-text">{{ \Illuminate\Support\Str::limit($row->keterangan, 110) }}</div>
                                @else
                                    <div class="note-text" style="color:#b8b5b0;">Tidak ada keterangan tambahan</div>
                                @endif
                                @if ($row->m_latitude && $row->m_longitude)
                                    <div class="note-loc">
                                        {{ number_format($row->m_latitude, 5) }}, {{ number_format($row->m_longitude, 5) }}
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="empty">
                <div class="empty-title">Belum Ada Data</div>
                <div class="empty-text">Tidak ditemukan catatan presensi untuk periode {{ $periodeLabel }} pada jenis laporan ini.</div>
            </div>
        @endif

        {{-- ── Tanda tangan ─────────────────────────────────────────── --}}
        <table class="sign" width="100%">
            <tr>
                <td width="56%">
                    <div class="sign-note">
                        Dokumen ini dihasilkan otomatis oleh <b>SILATAR V2</b> dari basis data
                        <b>ktd_presensi</b> dan sah sebagai bukti rekapitulasi absensi pada periode
                        <b>{{ $periodeLabel }}</b>. Data tidak dapat dimodifikasi setelah dokumen dicetak.
                    </div>
                    <div class="sign-title">Catatan Verifikasi</div>
                </td>
                <td width="44%" style="text-align: right;">
                    <div class="sign-title" style="text-align: right;">Dicetak Oleh</div>
                    <div class="sign-name">{{ $user->name ?? '-' }}</div>
                    <div class="sign-nip">NIP. {{ $nip }}</div>
                </td>
            </tr>
        </table>

        {{-- ── Footer berulang ───────────────────────────────────────── --}}
        <div class="pagefoot print-only">
            <table width="100%">
                <tr>
                    <td>Kantor Kementerian Agama Kabupaten Tanah Datar &nbsp;·&nbsp; SILATAR V2 — Sistem Informasi Layanan Terpadu</td>
                    <td class="right">{{ $tabLabel }} &nbsp;·&nbsp; {{ $periodeLabel }}</td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>
