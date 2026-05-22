<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <style>
        @page {
            margin: 20mm 12mm 18mm 12mm;
        }

        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 9.5pt;
            color: #0f172a;
            line-height: 1.4;
        }

        .pdf-watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            width: 100%;
            transform: translate(-50%, -50%) rotate(-34deg);
            text-align: center;
            font-family: "DejaVu Serif", serif;
            font-size: 31pt;
            font-weight: 700;
            letter-spacing: 0.16em;
            color: #0f172a;
            opacity: 0.08;
            z-index: 0;
            white-space: nowrap;
        }

        .page-shell {
            position: relative;
            z-index: 1;
        }

        .header-image {
            width: 100%;
            margin-bottom: 8px;
        }

        .report-chip {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 999px;
            background: #e0f2fe;
            color: #0369a1;
            font-size: 8pt;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .report-title {
            font-size: 16pt;
            font-weight: 700;
            text-align: center;
            letter-spacing: 0.05em;
            margin: 0 0 8px;
            text-transform: uppercase;
            border-top: 2px solid #1e293b;
            border-bottom: 2px solid #1e293b;
            padding: 6px 0 5px;
        }

        .report-subtitle {
            text-align: center;
            color: #475569;
            font-size: 8.8pt;
            margin-bottom: 10px;
        }

        .identity-card {
            border: 1px solid #dbeafe;
            border-radius: 10px;
            background: #f8fbff;
            padding: 10px 12px 12px;
            margin-bottom: 10px;
        }

        .identity-card__header {
            display: table;
            width: 100%;
            margin-bottom: 8px;
        }

        .section-badge {
            display: inline-block;
            width: 28px;
            height: 28px;
            line-height: 28px;
            border-radius: 999px;
            text-align: center;
            background: #dbeafe;
            color: #1d4ed8;
            margin-right: 8px;
            vertical-align: middle;
            font-size: 0;
            position: relative;
        }

        .section-badge::before {
            content: "▣";
            display: block;
            font-size: 9pt;
            font-weight: 700;
            line-height: 28px;
            text-align: center;
            color: currentColor;
        }

        .identity-card__title {
            display: inline-block;
            vertical-align: middle;
            font-size: 11pt;
            font-weight: 700;
            color: #0f172a;
            margin-left: 4mm;
        }

        .identity-card__period {
            float: right;
            padding: 4px 9px;
            border-radius: 999px;
            background: #ecfeff;
            color: #0f766e;
            font-size: 8pt;
            font-weight: 700;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }

        .identity-grid {
            width: 100%;
            border-collapse: collapse;
            font-size: 9pt;
        }

        .identity-grid td {
            padding: 5px 0;
            vertical-align: top;
        }

        .identity-icon {
            width: 24px;
            padding-right: 8px;
            vertical-align: top;
        }

        .identity-icon__box {
            width: 22px;
            height: 22px;
            border-radius: 7px;
            background: #dbeafe;
            color: #1d4ed8;
            text-align: center;
            line-height: 22px;
            font-weight: 700;
            font-size: 0;
        }

        .identity-icon__box::before {
            display: block;
            line-height: 22px;
            text-align: center;
            color: currentColor;
            font-weight: 700;
            letter-spacing: 0;
        }

        .identity-icon--user::before {
            content: "◉";
            font-size: 10pt;
        }

        .identity-icon--nip::before {
            content: "ID";
            font-size: 7pt;
            letter-spacing: -0.1pt;
        }

        .identity-icon--unit::before {
            content: "⌂";
            font-size: 9pt;
        }

        .identity-icon--job::before {
            content: "J";
            font-size: 9pt;
        }

        .identity-icon--period::before {
            content: "◔";
            font-size: 9pt;
        }

        .identity-label {
            width: 30mm;
            font-weight: 700;
            color: #334155;
        }

        .identity-sep {
            width: 5mm;
            text-align: center;
            color: #94a3b8;
        }

        .identity-value {
            font-weight: 700;
            color: #0f172a;
        }

        .summary {
            font-size: 8.8pt;
            color: #475569;
            margin: 0 0 8px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-top: 4px;
        }

        .table th,
        .table td {
            border: 1px solid #d1d5db;
            vertical-align: top;
        }

        .table th {
            background: linear-gradient(90deg, #dbeafe 0%, #fef3c7 100%);
            text-align: center;
            font-size: 9pt;
            padding: 8px 8px;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #334155;
        }

        .table td {
            padding: 8px 10px;
        }

        .table-col-date {
            width: 22%;
        }

        .table-col-activity {
            width: 78%;
        }

        .col-date {
            text-align: center;
            font-weight: 700;
            background: #f8fafc;
            color: #0f172a;
            padding-left: 6px;
            padding-right: 6px;
            width: 22%;
        }

        .col-activity {
            padding-left: 12px;
            padding-right: 12px;
            width: 78%;
        }

        .date-label {
            display: block;
            font-weight: 700;
            color: #0f172a;
        }

        .entry-count {
            margin-top: 4px;
            font-size: 8pt;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.12em;
        }

        .activity-list {
            padding-left: 0;
            margin: 0;
            list-style: none;
            width: 100%;
        }

        .activity-item {
            margin-bottom: 8px;
            padding-bottom: 8px;
            border-bottom: 1px solid #e2e8f0;
            page-break-inside: avoid;
        }

        .activity-item:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }

        .activity-name {
            font-weight: 700;
            color: #0f172a;
        }

        .activity-meta {
            color: #475569;
            margin-top: 2px;
        }

        .footer-note {
            margin-top: 8px;
            font-size: 8pt;
            color: #64748b;
            text-align: right;
        }

        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }
    </style>
</head>
<body>
    <div class="pdf-watermark">{{ strtoupper($watermarkText ?? 'Kankemenag Kab.Tanah Datar') }}</div>

    <div class="page-shell">
        @if (! empty($headerImage))
            <img src="{{ $headerImage }}" alt="Header" class="header-image">
        @endif

        <div class="report-title">Laporan Capaian Kinerja Harian</div>
        <div class="report-subtitle">Dokumen ringkas capaian aktivitas harian selama periode {{ $periodLabel }}</div>

        <div class="identity-card clearfix">
            <div class="identity-card__header">
                <span class="section-badge"></span>
                <span class="identity-card__title">Identitas ASN</span>
                <span class="identity-card__period">{{ $periodLabel }}</span>
            </div>

            <table class="identity-grid">
                <tr>
                    <td class="identity-icon">
                        <span class="identity-icon__box identity-icon--user"></span>
                    </td>
                    <td class="identity-label">Nama</td>
                    <td class="identity-sep">:</td>
                    <td class="identity-value">{{ $userName }}</td>
                </tr>
                <tr>
                    <td class="identity-icon">
                        <span class="identity-icon__box identity-icon--nip"></span>
                    </td>
                    <td class="identity-label">NIP</td>
                    <td class="identity-sep">:</td>
                    <td class="identity-value">{{ $userNip }}</td>
                </tr>
                <tr>
                    <td class="identity-icon">
                        <span class="identity-icon__box identity-icon--unit"></span>
                    </td>
                    <td class="identity-label">Unit Kerja</td>
                    <td class="identity-sep">:</td>
                    <td class="identity-value">{{ $unitName }}</td>
                </tr>
                <tr>
                    <td class="identity-icon">
                        <span class="identity-icon__box identity-icon--job"></span>
                    </td>
                    <td class="identity-label">Jabatan</td>
                    <td class="identity-sep">:</td>
                    <td class="identity-value">{{ $positionName }}</td>
                </tr>
                <tr>
                    <td class="identity-icon">
                        <span class="identity-icon__box identity-icon--period"></span>
                    </td>
                    <td class="identity-label">Periode</td>
                    <td class="identity-sep">:</td>
                    <td class="identity-value">{{ $periodLabel }}</td>
                </tr>
            </table>
        </div>

        <div class="summary">Dicetak pada {{ $generatedAt }}</div>

        <table class="table">
            <colgroup>
                <col class="table-col-date">
                <col class="table-col-activity">
            </colgroup>
            <thead>
                <tr>
                    <th class="col-date">Tanggal</th>
                    <th class="col-activity">Kegiatan</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($dailyGroups as $group)
                    <tr>
                        <td class="col-date">
                            <span class="date-label">{{ $group['label'] }}</span>
                            <div class="entry-count">{{ count($group['items']) }} entri</div>
                        </td>
                        <td class="col-activity">
                            <ul class="activity-list">
                                @foreach ($group['items'] as $activity)
                                    <li class="activity-item">
                                        <div class="activity-name">{{ $activity['kegiatan'] }}</div>
                                        @if (! empty($activity['meta']))
                                            <div class="activity-meta">{{ $activity['meta'] }}</div>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="col-date">{{ $periodLabel }}</td>
                        <td style="text-align:center; color:#64748b;">Belum ada kegiatan pada periode ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="footer-note">SILATAR - Capaian Kinerja Harian</div>

    <script type="text/php">
        if (isset($pdf)) {
            $font = $fontMetrics->get_font("DejaVu Sans", "normal");
            $pdf->page_text(500, 805, "Halaman {PAGE_NUM} / {PAGE_COUNT}", $font, 8, [100, 116, 139]);
        }
    </script>
</body>
</html>
