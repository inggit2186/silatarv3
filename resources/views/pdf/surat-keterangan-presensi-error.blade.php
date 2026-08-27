<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <style>
        @page {
            margin: 10mm 15mm 10mm 15mm;
        }

        body {
            font-family: "DejaVu Sans", Helvetica, Arial, sans-serif;
            font-size: 10pt;
            color: #1a1a2e;
            line-height: 1.4;
            background: #ffffff;
            margin: 0;
            padding: 0;
        }

        /* Header */
        .header-img {
            width: 100%;
            max-height: 70px;
            object-fit: contain;
            margin-bottom: 3px;
        }

        /* Title */
        .title-section {
            text-align: center;
            margin: 4px 0 6px;
            padding: 0;
        }

        .surat-title {
            font-size: 13pt;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin: 0 0 2px;
            color: #1a1a2e;
        }

        .surat-subtitle {
            font-size: 9pt;
            color: #64748b;
            margin: 0 0 2px;
        }

        .surat-number {
            font-size: 9pt;
            color: #475569;
            margin: 0;
        }

        /* Divider */
        .divider {
            border: none;
            border-top: 2px solid #2563eb;
            margin: 5px 0 10px;
        }

        /* Content */
        .content {
            font-size: 10pt;
            margin-bottom: 8px;
        }

        .content p {
            margin: 0 0 6px;
            text-align: justify;
        }

        /* Info Table */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin: 6px 0;
            font-size: 9.5pt;
        }

        .info-table td {
            padding: 4px 8px;
            border: 1px solid #e2e8f0;
            vertical-align: top;
        }

        .info-table .label {
            width: 35%;
            font-weight: 700;
            background: #f8fafc;
            color: #334155;
        }

        .info-table .value {
            width: 65%;
            color: #1e293b;
        }

        /* Page Break */
        .page-break {
            page-break-before: always;
            break-before: page;
        }

        /* Photo Section */
        .photo-section {
            margin: 15px 0;
            text-align: center;
        }

        .photo-section img {
            max-width: 520px;
            max-height: 350px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
        }

        .photo-label {
            font-size: 9pt;
            color: #64748b;
            margin-top: 6px;
        }

        /* Statement */
        .statement {
            margin: 10px 0;
            padding: 8px 10px;
            background: #f0f9ff;
            border-left: 4px solid #2563eb;
            font-size: 9.5pt;
        }

        /* Upload Info */
        .upload-info {
            margin: 8px 0;
            padding: 8px 10px;
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            font-size: 9pt;
            color: #92400e;
        }

        /* Signature Section */
        .signature-section {
            width: 100%;
            margin-top: 20px;
            page-break-inside: avoid;
        }

        .signature-table {
            width: 100%;
            border-collapse: collapse;
        }

        .signature-table td {
            width: 50%;
            vertical-align: top;
            padding: 0 10px;
        }

        .sig-title {
            font-size: 8pt;
            font-weight: 700;
            margin-bottom: 3px;
            color: #334155;
        }

        .sig-image {
            max-width: 80px;
            max-height: 40px;
            margin: 4px 0;
        }

        .sig-line {
            border-bottom: 1px solid #94a3b8;
            margin: 35px 0 4px;
        }

        .sig-name {
            font-size: 8.5pt;
            font-weight: 700;
            margin: 0;
            color: #1a1a2e;
        }

        .sig-nip {
            font-size: 7.5pt;
            color: #64748b;
            margin: 1px 0 0;
        }

        /* Footer */
        .footer-note {
            margin-top: 12px;
            font-size: 7.5pt;
            color: #94a3b8;
            text-align: center;
            border-top: 1px solid #e2e8f0;
            padding-top: 4px;
        }
    </style>
</head>
<body>

    {{-- ═══════════════════════════════════════ HALAMAN 1 ═══════════════════════════════════════ --}}

    {{-- Header --}}
    @if($headerPath)
        <img src="file://{{ $headerPath }}" alt="Header" class="header-img">
    @endif

    {{-- Title --}}
    <div class="title-section">
        <h1 class="surat-title">Surat Keterangan</h1>
        <p class="surat-subtitle">Pelaporan Pengaduan Presensi</p>
        <p class="surat-number">Nomor: {{ $nomorSurat }}</p>
    </div>

    <hr class="divider">

    {{-- Content --}}
    <div class="content">
        <p>
            Yang bertanda tangan di bawah ini, Kepala {{ $unitKerja }}, Kementerian Agama Kabupaten Tanah Datar,
            dengan ini menerangkan bahwa:
        </p>

        <table class="info-table">
            <tr>
                <td class="label">Nama</td>
                <td class="value">{{ $nama }}</td>
            </tr>
            <tr>
                <td class="label">NIP</td>
                <td class="value">{{ $nip }}</td>
            </tr>
            <tr>
                <td class="label">Jabatan</td>
                <td class="value">{{ $jabatan }}</td>
            </tr>
            <tr>
                <td class="label">Unit Kerja</td>
                <td class="value">{{ $unitKerja }}</td>
            </tr>
        </table>

        <p>
            Telah melakukan presensi kehadiran kerja pada:
        </p>

        <table class="info-table">
            <tr>
                <td class="label">Hari / Tanggal</td>
                <td class="value">{{ $tanggal }}</td>
            </tr>
            <tr>
                <td class="label">Tipe Presensi</td>
                <td class="value">{{ $jenisPresensi }}</td>
            </tr>
            <tr>
                <td class="label">Alasan</td>
                <td class="value">{{ $alasan === 'TUGAS_LUAR' ? 'Tugas Luar' : 'Sistem Error' }}</td>
            </tr>
            <tr>
                <td class="label">Waktu Pengambilan</td>
                <td class="value">{{ $jamActual }} WIB</td>
            </tr>
            <tr>
                <td class="label">Lokasi (Koordinat)</td>
                <td class="value">{{ $lokasi }}</td>
            </tr>
            @if(!empty($alamat))
            <tr>
                <td class="label">Alamat</td>
                <td class="value">{{ $alamat }}</td>
            </tr>
            @endif
            <tr>
                <td class="label">Jarak dari Kantor</td>
                <td class="value">{{ $jarak }}</td>
            </tr>
            <tr>
                <td class="label">Keterangan</td>
                <td class="value">{{ $keterangan }}</td>
            </tr>
        </table>

        {{-- Pernyataan --}}
        <div class="statement">
            <p style="margin: 0;">
                Demikian surat keterangan ini dibuat dengan sebenar-benarnya dan dapat dipertanggungjawabkan.
                Surat ini diterbitkan karena terjadi gangguan pada sistem presensi utama, sehingga presensi
                dilakukan melalui halaman alternatif <strong>Pengaduan Presensi</strong>.
            </p>
        </div>

        {{-- Info Upload --}}
        <div class="upload-info">
            <p style="margin: 0;">
                Surat Keterangan ini diupload ke <strong>https://absensi.kemenag.go.id</strong> sebagai
                bukti pelaporan pengaduan presensi PUSAKA.
            </p>
        </div>
    </div>

    {{-- Page Break --}}
    <div style="page-break-before: always;"></div>

    {{-- Foto Bukti --}}
    @if($fotoPath)
        <div class="photo-section">
            <p style="font-size: 10pt; font-weight: 700; margin-bottom: 6px; color: #334155;">Foto Bukti Kehadiran</p>
            <img src="file://{{ $fotoPath }}" alt="Foto Bukti Kehadiran">
            <p class="photo-label">Foto diambil saat melakukan pengaduan presensi</p>
        </div>
    @endif

    {{-- Catatan Pimpinan --}}
    <div class="catatan-pimpinan" style="margin: 10px 0; padding: 8px; border: 1px solid #e2e8f0; border-radius: 4px;">
        <p style="margin: 0 0 5px; font-size: 9pt; font-weight: 700; color: #334155;">Catatan Pimpinan:</p>
        <div style="min-height: 40px; border-bottom: 1px solid #d1d5db; margin-bottom: 4px;"></div>
        <div style="min-height: 40px; border-bottom: 1px solid #d1d5db; margin-bottom: 4px;"></div>
        <div style="min-height: 40px;"></div>
    </div>

    {{-- Tanda Tangan --}}
    <div class="signature-section">
        <table class="signature-table">
            <tr>
                {{-- Atasan (Kiri) --}}
                <td style="text-align: center;">
                    <p class="sig-title">{!! $kepalaLabel !!}</p>
                    <br/>
                    <div class="sig-line"></div>
                    <p class="sig-name">{{ $kepalaNama }}</p>
                    <p class="sig-nip">NIP. {{ $kepalaNip }}</p>
                </td>

                {{-- User (Kanan) --}}
                <td style="text-align: center;">
                    <br/>
                    <p class="sig-title">Yang Bersangkutan,</p>
                    <br/>
                    <div class="sig-line"></div>
                    <p class="sig-name">{{ $nama }}</p>
                    <p class="sig-nip">NIP. {{ $nip }}</p>
                </td>
            </tr>
        </table>
    </div>

    {{-- Footer --}}
    <p class="footer-note">
        Dokumen ini digenerate otomatis oleh Sistem Informasi Layanan Terintegrasi (SILATAR) |
        Kementerian Agama Kabupaten Tanah Datar
    </p>

</body>
</html>
