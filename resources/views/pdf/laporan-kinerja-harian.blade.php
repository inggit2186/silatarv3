<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <style>
        @page {
            margin: 12mm 18mm 12mm 18mm;
        }

        body {
            font-family: Helvetica, Arial, sans-serif;
            font-size: 10pt;
            color: #1a1a2e;
            line-height: 1.45;
            background: #ffffff;
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

        /* Header Image */
        .header-image {
            width: 100%;
            margin-bottom: 8px;
        }

        /* Title Section */
        .title-section {
            text-align: center;
            margin-bottom: 12px;
            padding-bottom: 10px;
            border-bottom: 2px solid #2563eb;
        }

        .title-chip {
            display: inline-block;
            background: #2563eb;
            color: #ffffff;
            padding: 2px 12px;
            border-radius: 12px;
            font-size: 7pt;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        .report-title {
            font-size: 15pt;
            font-weight: 700;
            margin: 0 0 2px;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #1a1a2e;
        }

        .report-subtitle {
            font-size: 9.5pt;
            color: #64748b;
            margin: 0;
        }

        /* Identity Section */
        .identity-section {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 10px 14px;
            margin-bottom: 12px;
        }

        .identity-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-bottom: 6px;
            margin-bottom: 8px;
            border-bottom: 1px dashed #e2e8f0;
        }

        .identity-title {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 9pt;
            font-weight: 700;
            color: #2563eb;
            text-transform: uppercase;
        }

        .identity-icon {
            width: 18px;
            height: 18px;
            background: #2563eb;
            border-radius: 50%;
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            font-weight: 700;
        }

        .identity-period {
            background: #dbeafe;
            color: #2563eb;
            padding: 2px 10px;
            border-radius: 10px;
            font-size: 7pt;
            font-weight: 700;
            text-transform: uppercase;
        }

        .identity-grid {
            display: table;
            width: 100%;
        }

        .identity-row {
            display: table-row;
        }

        .identity-row td {
            padding: 4px 0;
            vertical-align: middle;
        }

        .identity-icon-cell {
            width: 24px;
            padding-right: 8px;
        }

        .identity-icon-box {
            width: 18px;
            height: 18px;
            background: #dbeafe;
            border-radius: 4px;
            color: #2563eb;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 7px;
            font-weight: 700;
        }

        .identity-label-cell {
            width: 80px;
            padding-right: 8px;
        }

        .identity-label {
            font-size: 8pt;
            color: #64748b;
        }

        .identity-sep-cell {
            width: 15px;
            text-align: center;
            color: #94a3b8;
        }

        .identity-value {
            font-size: 9pt;
            font-weight: 600;
            color: #1a1a2e;
        }

        /* Table */
        .table-wrapper {
            border: 1px solid #2563eb;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 20px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            border: none;
            padding: 0;
            vertical-align: top;
        }

        .table th {
            background: #2563eb;
            color: #ffffff;
            padding: 8px 12px;
            text-align: center;
            font-size: 9pt;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .col-date {
            width: 160px;
        }

        .col-activity {
            width: auto;
        }

        /* Date Cell */
        .date-cell {
            background: linear-gradient(180deg, #f1f5f9 0%, #e2e8f0 100%);
            padding: 12px 10px;
            border-right: 2px solid #2563eb;
            border-bottom: 1px solid #e2e8f0;
        }

        .date-day {
            font-size: 9pt;
            font-weight: 600;
            color: #2563eb;
        }

        .date-number {
            font-size: 18pt;
            font-weight: 700;
            color: #1e40af;
            line-height: 1.1;
        }

        .date-month {
            font-size: 7pt;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }

        /* Activity Cell */
        .activity-cell {
            padding: 12px 12px;
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
        }

        .activity-header {
            padding-bottom: 6px;
            margin-bottom: 8px;
            border-bottom: 1px solid #f1f5f9;
        }

        .activity-header-day {
            font-size: 8pt;
            font-weight: 600;
            color: #2563eb;
        }

        .activity-header-count {
            font-size: 7pt;
            color: #94a3b8;
        }

        .activity-list {
            padding-left: 0;
            margin: 0;
            list-style: none;
        }

        .activity-item {
            margin-bottom: 6px;
            padding-left: 14px;
            position: relative;
        }

        .activity-item::before {
            content: "-";
            position: absolute;
            left: 0;
            color: #2563eb;
            font-size: 11px;
            font-weight: 700;
        }

        .activity-item:last-child {
            margin-bottom: 0;
        }

        .activity-text {
            font-size: 9pt;
            color: #1a1a2e;
        }

        .activity-meta {
            font-size: 7.5pt;
            color: #2563eb;
            font-weight: 600;
            margin-top: 1px;
        }

        /* Row Alternating */
        .data-row:nth-child(even) .activity-cell {
            background: #fafbfc;
        }

        /* Signature Section - using table for PDF compatibility */
        .signature-section {
            margin-top: 30px;
        }

        .signature-table {
            width: 100%;
            border-collapse: collapse;
        }

        .signature-table td {
            width: 50%;
            text-align: center;
            vertical-align: top;
            padding: 0 20px;
        }

        .signature-label {
            font-size: 9pt;
            color: #64748b;
            margin-bottom: 5px;
            display: block;
        }

        .signature-image {
            max-width: 350px;
            max-height: 60px;
            margin-bottom: 3px;
        }

        .signature-name {
            font-size: 9pt;
            font-weight: 700;
            text-decoration: underline;
            color: #1a1a2e;
            margin-bottom: 2px;
            margin-top: 5px;
        }

        .signature-nip {
            font-size: 8pt;
            color: #64748b;
        }

        .signature-name-right {
            margin-top: 85px !important;
        }

        .signature-name-no-image {
            margin-top: 65px !important;
        }

        .signature-image {
            max-width: 150px;
            max-height: 60px;
            margin-bottom: 5px;
        }

        /* Footer */
        .footer-section {
            margin-top: 15px;
            text-align: center;
            padding: 8px;
            background: #f8fafc;
            border-radius: 6px;
            border: 1px solid #e2e8f0;
        }

        .footer-text {
            font-size: 7.5pt;
            color: #94a3b8;
        }

        .footer-brand {
            color: #2563eb;
            font-weight: 700;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 30px;
            color: #94a3b8;
            font-size: 10pt;
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

        <div class="title-section">
            <div class="title-chip">* Digital Report</div>
            <h1 class="report-title">Laporan Capaian Kinerja Harian</h1>
            <p class="report-subtitle">Periode {{ $periodLabel }}</p>
        </div>

        <div class="identity-section">
            <div class="identity-header">
                <span class="identity-title">
                    <span class="identity-icon">i</span>
                    Identitas ASN
                </span>
                <span class="identity-period">{{ $periodLabel }}</span>
            </div>

            <div class="identity-grid">
                <div class="identity-row">
                    <td class="identity-icon-cell">
                        <span class="identity-icon-box">N</span>
                    </td>
                    <td class="identity-label-cell">
                        <span class="identity-label">Nama</span>
                    </td>
                    <td class="identity-sep-cell">:</td>
                    <td>
                        <span class="identity-value">{{ $userName }}</span>
                    </td>
                </div>
                <div class="identity-row">
                    <td class="identity-icon-cell">
                        <span class="identity-icon-box">ID</span>
                    </td>
                    <td class="identity-label-cell">
                        <span class="identity-label">NIP</span>
                    </td>
                    <td class="identity-sep-cell">:</td>
                    <td>
                        <span class="identity-value">{{ $userNip }}</span>
                    </td>
                </div>
                <div class="identity-row">
                    <td class="identity-icon-cell">
                        <span class="identity-icon-box">U</span>
                    </td>
                    <td class="identity-label-cell">
                        <span class="identity-label">Unit Kerja</span>
                    </td>
                    <td class="identity-sep-cell">:</td>
                    <td>
                        <span class="identity-value">{{ $unitName }}</span>
                    </td>
                </div>
                <div class="identity-row">
                    <td class="identity-icon-cell">
                        <span class="identity-icon-box">J</span>
                    </td>
                    <td class="identity-label-cell">
                        <span class="identity-label">Jabatan</span>
                    </td>
                    <td class="identity-sep-cell">:</td>
                    <td>
                        <span class="identity-value">{{ $positionName }}</span>
                    </td>
                </div>
            </div>
        </div>

        <div class="table-wrapper">
            <table class="table">
                <thead>
                    <tr>
                        <th class="col-date">Tanggal</th>
                        <th class="col-activity">Detail Kegiatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($dailyGroups as $group)
                        @php
                            $dateObj = \Carbon\Carbon::parse($group['date']);
                            $dayNum = $dateObj->format('d');
                            $monthName = $dateObj->format('F');
                            $monthMap = [
                                'January' => 'Januari', 'February' => 'Februari', 'March' => 'Maret',
                                'April' => 'April', 'May' => 'Mei', 'June' => 'Juni',
                                'July' => 'Juli', 'August' => 'Agustus', 'September' => 'September',
                                'October' => 'Oktober', 'November' => 'November', 'December' => 'Desember'
                            ];
                            $weekday = $dateObj->format('l');
                            $weekdayMap = [
                                'Monday' => 'Senin', 'Tuesday' => 'Selasa',
                                'Wednesday' => 'Rabu', 'Thursday' => 'Kamis',
                                'Friday' => 'Jumat', 'Saturday' => 'Sabtu',
                                'Sunday' => 'Minggu'
                            ];
                            $weekdayId = $weekdayMap[$weekday] ?? $weekday;
                            $monthId = $monthMap[$monthName] ?? $monthName;
                        @endphp
                        <tr class="data-row">
                            <td class="date-cell">
                                <div class="date-day">{{ $weekdayId }}</div>
                                <div class="date-number">{{ $dayNum }}</div>
                                <div class="date-month">{{ $monthId }} {{ $dateObj->format('Y') }}</div>
                            </td>
                            <td class="activity-cell">
                                <div class="activity-header">
                                    <span class="activity-header-day">{{ $weekdayId }}, {{ $dayNum }} {{ $monthId }} {{ $dateObj->format('Y') }}</span>
                                    <span class="activity-header-count">* {{ count($group['items']) }} kegiatan</span>
                                </div>
                                <ul class="activity-list">
                                    @foreach ($group['items'] as $activity)
                                        <li class="activity-item">
                                            <span class="activity-text">{{ $activity['kegiatan'] }}</span>
                                            @if (! empty($activity['meta']))
                                                <div class="activity-meta">* {{ $activity['meta'] }}</div>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="empty-state">
                                Belum ada kegiatan pada periode ini
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="signature-section">
            <table class="signature-table">
                <tr>
                    <td>
                        <p class="signature-label">{!! $signatureLabel !!}</p>
                        @if($signatureImage)
                        <img src="{{ $signatureImage }}" alt="Tanda Tangan" class="signature-image">
                        @endif
                        <p class="signature-name{{ !$signatureImage ? ' signature-name-no-image' : '' }}">{{ $signatureName }}</p>
                        <p class="signature-nip">{{ $signatureNip }}</p>
                    </td>
                    <td>
                        <p class="signature-label">Yang Bersangkutan,</p>
                        <p class="signature-name signature-name-right">{{ $userName }}</p>
                        <p class="signature-nip">{{ $userNip }}</p>
                    </td>
                </tr>
            </table>
        </div>

        <div class="footer-section">
            <span class="footer-text">* Dicetak pada {{ $generatedAt }} | <span class="footer-brand">SILATAR</span> v2 *</span>
        </div>
    </div>

    <script type="text/php">
        if (isset($pdf)) {
            $font = $fontMetrics->get_font("Helvetica", "normal");
            $pdf->page_text(500, 815, "Halaman {PAGE_NUM} / {PAGE_COUNT}", $font, 7, [100, 116, 139]);
        }
    </script>
</body>
</html>