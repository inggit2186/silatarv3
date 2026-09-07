<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti Input Surat - {{ $surat->no_req }}</title>
    <style>
        @page {
            size: 330mm 215mm;
            margin: 6mm 5mm;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 11px;
            color: #1e293b;
            background: #fff;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        /* === PAGE CONTAINER === */
        .page {
            width: 320mm;
            height: 203mm;
            display: flex;
            gap: 3mm;
            page-break-after: always;
        }

        .page:last-child {
            page-break-after: avoid;
        }

        /* === RECEIPT CARD === */
        .receipt {
            flex: 1;
            border: 1.5px solid #0e7490;
            border-radius: 5px;
            padding: 3mm 3mm;
            display: flex;
            flex-direction: column;
            position: relative;
            overflow: hidden;
        }

        /* Header */
        .receipt-header {
            text-align: center;
            padding-bottom: 2mm;
            border-bottom: 2px solid #0e7490;
            margin-bottom: 2mm;
        }

        /* Title */
        .receipt-title {
            text-align: center;
            font-size: 11px;
            font-weight: 700;
            color: #fff;
            background: linear-gradient(135deg, #0891b2, #0e7490);
            padding: 1.5mm 2mm;
            border-radius: 3px;
            margin-bottom: 2mm;
            letter-spacing: 0.5px;
        }

        /* Copy Label */
        .copy-label {
            text-align: center;
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 2mm;
            padding: 1mm 2mm;
            border: 1.5px solid #0e7490;
            border-radius: 3px;
            background: #ecfeff;
        }

        /* No. Req */
        .no-req-box {
            background: linear-gradient(135deg, #f0fdfa 0%, #ccfbf1 100%);
            border: 1px solid #99f6e4;
            border-radius: 4px;
            padding: 2mm;
            text-align: center;
            margin-bottom: 2.5mm;
        }

        .no-req-label {
            font-size: 8px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .no-req-value {
            font-size: 11px;
            font-weight: 700;
            color: #0e7490;
            font-family: 'Courier New', monospace;
            margin-top: 0.5mm;
            letter-spacing: 0.3px;
        }

        /* Sections */
        .info-section {
            margin-bottom: 2mm;
        }

        .info-section-title {
            font-size: 9px;
            font-weight: 700;
            color: #0e7490;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1.5px solid #e2e8f0;
            padding-bottom: 0.8mm;
            margin-bottom: 1.5mm;
        }

        .info-row {
            display: flex;
            padding: 0.8mm 0;
            border-bottom: 0.3px solid #f1f5f9;
            align-items: flex-start;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            width: 26mm;
            font-size: 9px;
            color: #64748b;
            flex-shrink: 0;
        }

        .info-dot {
            width: 3mm;
            flex-shrink: 0;
            text-align: center;
            color: #94a3b8;
            font-size: 9px;
        }

        .info-value {
            flex: 1;
            font-size: 10px;
            font-weight: 500;
            color: #1e293b;
            word-break: break-word;
            line-height: 1.3;
        }

        /* Status badge */
        .status-badge {
            display: inline-block;
            background: #10b981;
            color: #fff;
            font-size: 8px;
            font-weight: 600;
            padding: 0.6mm 2mm;
            border-radius: 2px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        /* Keterangan */
        .keterangan-box {
            border: 1px solid #e2e8f0;
            border-radius: 3px;
            padding: 2mm;
            background: #fafafa;
            min-height: 10mm;
            margin-bottom: 2mm;
        }

        .keterangan-text {
            font-size: 9px;
            line-height: 1.4;
            color: #334155;
            white-space: pre-wrap;
        }

        /* Disposisi area */
        .disposisi-area {
            border: 1.5px solid #0e7490;
            border-radius: 4px;
            padding: 2mm;
            background: #f0fdfa;
            flex: 1;
        }

        .disposisi-title {
            font-size: 7px;
            font-weight: 700;
            color: #0e7490;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 1mm;
            padding-bottom: 0.5mm;
            border-bottom: 1px solid #99f6e4;
        }

        .disposisi-note {
            margin-bottom: 1.5mm;
        }

        .disposisi-note:last-child {
            margin-bottom: 0;
        }

        .disposisi-note-header {
            display: flex;
            align-items: center;
            gap: 1mm;
            margin-bottom: 1mm;
        }

        .disposisi-note-label {
            font-size: 8px;
            font-weight: 700;
            color: #334155;
        }

        .disposisi-note-box {
            border: 0.5px solid #cbd5e1;
            border-radius: 2px;
            height: 14mm;
            padding: 1.5mm;
            background: #fff;
        }

        .disposisi-note-line {
            border-bottom: 0.3px solid #e2e8f0;
            height: 3mm;
            margin-bottom: 0.2mm;
        }

        .disposisi-note-line:last-child {
            margin-bottom: 0;
        }

        /* Footer */
        .receipt-footer {
            margin-top: auto;
            padding-top: 1.5mm;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            font-size: 7px;
            color: #94a3b8;
        }

        /* Print controls */
        .print-controls {
            position: fixed;
            top: 12px;
            right: 12px;
            z-index: 9999;
            display: flex;
            gap: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            border-radius: 8px;
            padding: 6px;
            background: #fff;
        }

        .print-btn {
            padding: 10px 24px;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .print-btn.primary {
            background: #0891b2;
            color: #fff;
        }

        .print-btn.primary:hover {
            background: #0e7490;
        }

        .print-btn.secondary {
            background: #f1f5f9;
            color: #475569;
        }

        .print-btn.secondary:hover {
            background: #e2e8f0;
        }

        @media print {
            .print-controls { display: none !important; }
            body { background: #fff; margin: 0; }
            .page { margin: 0; gap: 3mm; width: 320mm; height: 203mm; }
        }
    </style>
</head>
<body>

    <!-- Print Controls -->
    <div class="print-controls">
        <button class="print-btn secondary" onclick="window.close()">Tutup</button>
        <button class="print-btn primary" onclick="window.print()">Cetak</button>
    </div>

    @php
        $copies = [
            ['label' => 'DISPOSISI', 'color' => '#0891b2'],
            ['label' => 'PENGIRIM', 'color' => '#059669'],
            ['label' => 'PENERIMA', 'color' => '#7c3aed'],
        ];
        $dateFormatted = \Carbon\Carbon::parse($surat->tgl_surat)->format('d/m/Y');
        $timeFormatted = \Carbon\Carbon::parse($surat->created_at)->format('d/m/Y H:i');
    @endphp

    <!-- PAGE 1 -->
    <div class="page">
        @foreach($copies as $copy)
        <div class="receipt">

            <!-- Header -->
            <div class="receipt-header">
                <img src="{{ asset('assets/img/template/header.webp') }}" alt="Header" style="width: 100%; max-height: 18mm; object-fit: contain;">
            </div>

            <!-- Title -->
            <div class="receipt-title">BUKTI INPUT SURAT</div>

            <!-- Copy Label -->
            <div class="copy-label" style="border-color: {{ $copy['color'] }}; color: {{ $copy['color'] }}; background: {{ $copy['color'] }}15;">
                Salinan {{ $copy['label'] }}
            </div>

            <!-- No. Req -->
            <div class="no-req-box">
                <div class="no-req-label">Nomor Registrasi</div>
                <div class="no-req-value">{{ $surat->no_req }}</div>
            </div>

            <!-- Data Surat -->
            <div class="info-section">
                <div class="info-section-title">Data Surat</div>
                <div class="info-row">
                    <span class="info-label">No. Surat</span>
                    <span class="info-dot">:</span>
                    <span class="info-value">{{ $surat->no_surat }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Tanggal</span>
                    <span class="info-dot">:</span>
                    <span class="info-value">{{ $dateFormatted }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Judul</span>
                    <span class="info-dot">:</span>
                    <span class="info-value">{{ $surat->judul }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Layanan</span>
                    <span class="info-dot">:</span>
                    <span class="info-value">{{ $surat->layanan_name ?? '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Tujuan</span>
                    <span class="info-dot">:</span>
                    <span class="info-value">{{ $surat->tujuan_name ?? '-' }}</span>
                </div>
            </div>

            <!-- Data Pengirim -->
            <div class="info-section">
                <div class="info-section-title">Pengirim</div>
                <div class="info-row">
                    <span class="info-label">Nama</span>
                    <span class="info-dot">:</span>
                    <span class="info-value">{{ $surat->pemohon }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Asal</span>
                    <span class="info-dot">:</span>
                    <span class="info-value">{{ $surat->asal_pengirim ?? '-' }}</span>
                </div>
            </div>

            <!-- Data Penerima -->
            <div class="info-section">
                <div class="info-section-title">Penerima</div>
                <div class="info-row">
                    <span class="info-label">Nama</span>
                    <span class="info-dot">:</span>
                    <span class="info-value">{{ $admin->name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Status</span>
                    <span class="info-dot">:</span>
                    <span class="info-value">
                        <span class="status-badge">{{ $surat->status }}</span>
                    </span>
                </div>
            </div>

            <!-- Keterangan -->
            @if($surat->deskripsi)
            <div class="keterangan-box">
                <div class="info-section-title" style="border-bottom: none; margin-bottom: 1mm; padding-bottom: 0;">Keterangan</div>
                <div class="keterangan-text">{{ Str::limit($surat->deskripsi, 150) }}</div>
            </div>
            @endif

            <!-- Form Disposisi (DISPOSISI & PENERIMA) -->
            @if(in_array($copy['label'], ['DISPOSISI', 'PENERIMA']))
            <div class="disposisi-area">
                <div class="disposisi-title">Form Disposisi</div>

                <div class="disposisi-note">
                    <div class="disposisi-note-header">
                        <span class="disposisi-note-label">Catatan Kasubbag TU</span>
                    </div>
                    <div class="disposisi-note-box">
                        <div class="disposisi-note-line"></div>
                        <div class="disposisi-note-line"></div>
                        <div class="disposisi-note-line"></div>
                    </div>
                </div>

                <div class="disposisi-note">
                    <div class="disposisi-note-header">
                        <span class="disposisi-note-label">Catatan Kakankemenag</span>
                    </div>
                    <div class="disposisi-note-box">
                        <div class="disposisi-note-line"></div>
                        <div class="disposisi-note-line"></div>
                        <div class="disposisi-note-line"></div>
                    </div>
                </div>

                <div class="disposisi-note">
                    <div class="disposisi-note-header">
                        <span class="disposisi-note-label">Catatan Kasi</span>
                    </div>
                    <div class="disposisi-note-box">
                        <div class="disposisi-note-line"></div>
                        <div class="disposisi-note-line"></div>
                        <div class="disposisi-note-line"></div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Footer -->
            <div class="receipt-footer">
                Dicetak: {{ $timeFormatted }} | SILATAR v2
            </div>
        </div>
        @endforeach
    </div>

</body>
</html>
