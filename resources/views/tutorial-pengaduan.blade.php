<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tutorial Pengaduan Presensi - SILATAR Kemenag Tanah Datar</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --emerald: #059669;
            --emerald-light: #10b981;
            --emerald-dark: #047857;
            --emerald-50: #ecfdf5;
            --amber: #d97706;
            --amber-light: #f59e0b;
            --amber-50: #fffbeb;
            --red: #dc2626;
            --red-50: #fef2f2;
            --blue: #2563eb;
            --blue-50: #eff6ff;
            --violet: #7c3aed;
            --violet-50: #f5f3ff;
            --slate-50: #f8fafc;
            --slate-100: #f1f5f9;
            --slate-200: #e2e8f0;
            --slate-300: #cbd5e1;
            --slate-400: #94a3b8;
            --slate-500: #64748b;
            --slate-600: #475569;
            --slate-700: #334155;
            --slate-800: #1e293b;
            --slate-900: #0f172a;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, #ecfdf5 0%, #f0fdfa 50%, #eff6ff 100%);
            min-height: 100vh;
            color: var(--slate-800);
            -webkit-font-smoothing: antialiased;
        }

        /* ======= HERO ======= */
        .hero {
            background: linear-gradient(135deg, var(--emerald-dark) 0%, var(--emerald) 40%, var(--emerald-light) 100%);
            padding: 60px 40px 80px;
            text-align: center;
            color: #fff;
            position: relative;
            overflow: hidden;
        }
        .hero::before {
            content: '';
            position: absolute;
            top: -200px; right: -200px;
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(255,255,255,0.12) 0%, transparent 60%);
            border-radius: 50%;
        }
        .hero::after {
            content: '';
            position: absolute;
            bottom: -150px; left: -100px;
            width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, transparent 60%);
            border-radius: 50%;
        }
        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255,255,255,0.18);
            border: 1px solid rgba(255,255,255,0.25);
            padding: 8px 20px;
            border-radius: 50px;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-bottom: 24px;
            backdrop-filter: blur(8px);
            position: relative; z-index: 1;
        }
        .hero-badge .dot {
            width: 8px; height: 8px;
            background: #34d399;
            border-radius: 50%;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.4; }
        }
        .hero h1 {
            font-size: 42px;
            font-weight: 900;
            line-height: 1.15;
            margin-bottom: 16px;
            position: relative; z-index: 1;
            letter-spacing: -0.5px;
        }
        .hero h1 span {
            background: linear-gradient(to right, #fde68a, #fbbf24);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .hero p {
            font-size: 17px;
            opacity: 0.9;
            max-width: 620px;
            margin: 0 auto 28px;
            line-height: 1.7;
            position: relative; z-index: 1;
        }
        .hero-stats {
            display: flex;
            justify-content: center;
            gap: 32px;
            position: relative; z-index: 1;
            flex-wrap: wrap;
        }
        .hero-stat {
            display: flex;
            align-items: center;
            gap: 10px;
            background: rgba(255,255,255,0.12);
            border: 1px solid rgba(255,255,255,0.15);
            padding: 10px 18px;
            border-radius: 12px;
            backdrop-filter: blur(6px);
        }
        .hero-stat svg { width: 18px; height: 18px; opacity: 0.8; }
        .hero-stat span { font-size: 13px; font-weight: 500; }

        /* ======= MAIN CONTENT ======= */
        .main {
            max-width: 860px;
            margin: -40px auto 60px;
            padding: 0 20px;
            position: relative;
            z-index: 2;
        }

        /* ======= INFO CARD ======= */
        .info-card {
            background: #fff;
            border-radius: 20px;
            padding: 28px 32px;
            box-shadow: 0 4px 24px rgba(5,150,105,0.08), 0 1px 3px rgba(0,0,0,0.04);
            border: 1px solid rgba(16,185,129,0.12);
            margin-bottom: 36px;
            display: flex;
            gap: 16px;
            align-items: flex-start;
        }
        .info-card-icon {
            flex-shrink: 0;
            width: 44px; height: 44px;
            background: linear-gradient(135deg, var(--emerald), var(--emerald-light));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .info-card-icon svg { width: 22px; height: 22px; color: #fff; }
        .info-card-text h3 {
            font-size: 15px;
            font-weight: 700;
            color: var(--emerald-dark);
            margin-bottom: 4px;
        }
        .info-card-text p {
            font-size: 13.5px;
            color: var(--slate-600);
            line-height: 1.7;
        }
        .info-card-text strong { color: var(--emerald); }

        /* ======= SECTION HEADER ======= */
        .section-header {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 28px;
        }
        .section-header-icon {
            width: 40px; height: 40px;
            background: var(--slate-900);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .section-header-icon svg { width: 20px; height: 20px; color: #fff; }
        .section-header h2 {
            font-size: 22px;
            font-weight: 800;
            color: var(--slate-900);
        }

        /* ======= STEP CARD ======= */
        .step-card {
            background: #fff;
            border-radius: 20px;
            padding: 0;
            margin-bottom: 24px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.04), 0 1px 3px rgba(0,0,0,0.03);
            border: 1px solid var(--slate-200);
            overflow: hidden;
            transition: box-shadow 0.3s, transform 0.3s;
        }
        .step-card:hover {
            box-shadow: 0 8px 30px rgba(0,0,0,0.08);
            transform: translateY(-2px);
        }
        .step-card-header {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 22px 28px;
            border-bottom: 1px solid var(--slate-100);
        }
        .step-num {
            flex-shrink: 0;
            width: 48px; height: 48px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            font-weight: 800;
            color: #fff;
            background: linear-gradient(135deg, var(--emerald), var(--emerald-light));
            box-shadow: 0 4px 12px rgba(5,150,105,0.3);
        }
        .step-card-header h3 {
            font-size: 17px;
            font-weight: 700;
            color: var(--slate-900);
            line-height: 1.3;
        }
        .step-card-header .tag {
            margin-left: auto;
            font-size: 11px;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 6px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            flex-shrink: 0;
        }
        .tag-green { background: var(--emerald-50); color: var(--emerald); }
        .tag-amber { background: var(--amber-50); color: var(--amber); }
        .tag-red { background: var(--red-50); color: var(--red); }
        .tag-blue { background: var(--blue-50); color: var(--blue); }
        .tag-violet { background: var(--violet-50); color: var(--violet); }

        .step-card-body { padding: 22px 28px; }
        .step-card-body p {
            font-size: 14px;
            color: var(--slate-600);
            line-height: 1.75;
            margin-bottom: 14px;
        }
        .step-card-body p:last-child { margin-bottom: 0; }

        /* ======= URL BOX ======= */
        .url-box {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: var(--amber-50);
            border: 1px solid #fde68a;
            padding: 10px 16px;
            border-radius: 10px;
            margin-bottom: 14px;
        }
        .url-box svg { width: 16px; height: 16px; color: var(--amber); flex-shrink: 0; }
        .url-box code {
            font-family: 'SF Mono', 'Consolas', monospace;
            font-size: 13px;
            font-weight: 600;
            color: var(--amber);
            word-break: break-all;
        }

        /* ======= TIP BOX ======= */
        .tip {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 14px 18px;
            border-radius: 12px;
            font-size: 13px;
            line-height: 1.65;
            margin-top: 14px;
        }
        .tip svg { flex-shrink: 0; width: 18px; height: 18px; margin-top: 1px; }
        .tip-green { background: var(--emerald-50); border: 1px solid #a7f3d0; color: var(--emerald-dark); }
        .tip-amber { background: var(--amber-50); border: 1px solid #fde68a; color: #92400e; }
        .tip-red { background: var(--red-50); border: 1px solid #fecaca; color: #991b1b; }
        .tip-blue { background: var(--blue-50); border: 1px solid #bfdbfe; color: #1e40af; }

        /* ======= IMAGE SCREENSHOT ======= */
        .screenshot {
            margin-top: 16px;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid var(--slate-200);
            background: var(--slate-100);
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }
        .screenshot img {
            width: 100%;
            display: block;
            max-height: 480px;
            object-fit: contain;
        }

        /* ======= LIST ======= */
        .options-list {
            list-style: none;
            padding: 0;
            margin: 10px 0 14px;
        }
        .options-list li {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 8px 14px;
            border-radius: 8px;
            font-size: 13.5px;
            line-height: 1.5;
            margin-bottom: 4px;
        }
        .options-list li:nth-child(odd) { background: var(--slate-50); }
        .options-list .dot-color {
            width: 10px; height: 10px;
            border-radius: 50%;
            flex-shrink: 0;
            margin-top: 4px;
        }

        /* ======= CHECKLIST ======= */
        .check-list {
            list-style: none;
            padding: 0;
            margin: 10px 0;
        }
        .check-list li {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 6px 0;
            font-size: 13.5px;
            color: var(--slate-700);
        }
        .check-list .check {
            width: 20px; height: 20px;
            background: var(--emerald-50);
            border: 1.5px solid var(--emerald-light);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .check-list .check svg { width: 12px; height: 12px; color: var(--emerald); }

        /* ======= STEP 13 SPECIAL ======= */
        .step-card.highlight {
            border: 2px solid var(--amber-light);
            box-shadow: 0 4px 20px rgba(245,158,11,0.12);
        }
        .step-card.highlight .step-num {
            background: linear-gradient(135deg, var(--amber), var(--amber-light));
            box-shadow: 0 4px 12px rgba(245,158,11,0.35);
        }
        .url-highlight {
            display: block;
            background: #fff;
            border: 2px solid #fde68a;
            border-radius: 12px;
            padding: 16px 20px;
            margin: 14px 0;
        }
        .url-highlight small {
            display: block;
            font-size: 11px;
            font-weight: 700;
            color: var(--slate-400);
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 6px;
        }
        .url-highlight code {
            font-family: 'SF Mono', 'Consolas', monospace;
            font-size: 15px;
            font-weight: 700;
            color: var(--amber);
        }

        /* ======= FINAL CARD ======= */
        .final-card {
            background: linear-gradient(135deg, var(--emerald) 0%, var(--emerald-light) 100%);
            border-radius: 20px;
            padding: 40px;
            text-align: center;
            color: #fff;
            box-shadow: 0 8px 30px rgba(5,150,105,0.2);
            margin-top: 36px;
        }
        .final-card h3 {
            font-size: 24px;
            font-weight: 800;
            margin-bottom: 10px;
        }
        .final-card p {
            font-size: 15px;
            opacity: 0.92;
            line-height: 1.7;
            max-width: 500px;
            margin: 0 auto;
        }

        /* ======= FOOTER ======= */
        .footer {
            text-align: center;
            padding: 30px 20px;
            font-size: 12px;
            color: var(--slate-400);
            line-height: 1.8;
        }
        .footer strong { color: var(--slate-600); }

        /* ======= PRINT ======= */
        @media print {
            body { background: #fff; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .hero { padding: 30px 24px 36px; }
            .hero h1 { font-size: 28pt; }
            .main { margin-top: 0; padding: 0 16px; max-width: 100%; }
            .step-card { box-shadow: none; border: 1px solid #ddd; page-break-inside: avoid; break-inside: avoid; }
            .step-card:hover { transform: none; box-shadow: none; }
            .screenshot img { max-height: 350px; }
            .final-card { box-shadow: none; }
        }
        @page { size: A4; margin: 12mm; }
    </style>
</head>
<body>

    {{-- HERO --}}
    <section class="hero">
        <div class="hero-badge">
            <span class="dot"></span>
            SILATAR V2 &mdash; Panduan Resmi
        </div>
        <h1>Tutorial <span>Pengaduan Presensi</span></h1>
        <p>
            Panduan lengkap langkah demi langkah untuk menyampaikan pengaduan sistem error,
            tugas luar, atau lupa presensi bagi seluruh jajaran ASN di lingkungan
            Kantor Kementerian Agama Kabupaten Tanah Datar.
        </p>
        <div class="hero-stats">
            <div class="hero-stat">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                <span>Estimasi 5 Menit</span>
            </div>
            <div class="hero-stat">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="2" width="14" height="20" rx="2"/><path d="M12 18h.01"/></svg>
                <span>HP / Laptop</span>
            </div>
            <div class="hero-stat">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/></svg>
                <span>Surat Keterangan Otomatis</span>
            </div>
        </div>
    </section>

    {{-- MAIN --}}
    <div class="main">

        {{-- INFO CARD --}}
        <div class="info-card">
            <div class="info-card-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
            </div>
            <div class="info-card-text">
                <h3>Apa Itu Halaman Presensi Error?</h3>
                <p>
                    Halaman <strong>Presensi Error</strong> adalah halaman alternatif dari sistem <strong>SILATAR</strong>
                    untuk melaporkan presensi kehadiran saat sistem utama (Pusaka) mengalami gangguan,
                    sedang tugas luar, atau lupa presensi. Hasil pengaduan berupa <strong>Surat Keterangan</strong>
                    resmi yang dapat dijadikan bukti pendukung di sistem <strong>Absensi Kementerian Agama RI</strong>.
                </p>
            </div>
        </div>

        {{-- SECTION HEADER --}}
        <div class="section-header">
            <div class="section-header-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
            </div>
            <h2>Langkah-Langkah Pengaduan</h2>
        </div>

        {{-- STEP 1 --}}
        <div class="step-card">
            <div class="step-card-header">
                <div class="step-num">1</div>
                <h3>Buka Halaman Presensi Error</h3>
                <span class="tag tag-green">Mulai</span>
            </div>
            <div class="step-card-body">
                <p>Akses halaman pengaduan presensi melalui browser di perangkat Anda (HP maupun laptop). Ketik alamat berikut di address bar:</p>
                <div class="url-box">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
                    <code>https://kemenagtanahdatar.id/presensi-error</code>
                </div>
                <div class="screenshot">
                    <img src="{{ asset('images/tutorial/step-01.webp') }}" alt="Halaman Presensi Error">
                </div>
            </div>
        </div>

        {{-- STEP 2 --}}
        <div class="step-card">
            <div class="step-card-header">
                <div class="step-num">2</div>
                <h3>Login dengan Akun SILATAR</h3>
                <span class="tag tag-blue">Autentikasi</span>
            </div>
            <div class="step-card-body">
                <p>Jika Anda belum login, sistem akan mengarahkan ke halaman login. Masukkan <strong>Email atau NIP</strong> dan <strong>Password</strong> akun SILATAR Anda, lalu klik tombol <strong>"Masuk ke Sistem"</strong>.</p>
                <div class="tip tip-blue">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                    <span>Gunakan email atau NIP yang terdaftar di sistem SILATAR. Jika lupa password, klik <strong>"Lupa Password?"</strong> untuk mereset.</span>
                </div>
                <div class="screenshot">
                    <img src="{{ asset('images/tutorial/step-01.webp') }}" alt="Form Login SILATAR">
                </div>
            </div>
        </div>

        {{-- STEP 3 --}}
        <div class="step-card">
            <div class="step-card-header">
                <div class="step-num">3</div>
                <h3>Izinkan Akses Lokasi</h3>
                <span class="tag tag-amber">Penting</span>
            </div>
            <div class="step-card-body">
                <p>Setelah login, browser akan meminta izin untuk mengakses lokasi perangkat Anda. Klik tombol <strong>"Allow"</strong> atau <strong>"Izinkan"</strong> agar sistem dapat mendeteksi lokasi GPS Anda secara otomatis. Lokasi ini akan tercatat dalam Surat Keterangan.</p>
                <div class="tip tip-amber">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
                    <span><strong>Penting:</strong> Jika Anda menolak akses lokasi, pengaduan tidak dapat diproses. Pastikan GPS pada perangkat Anda dalam keadaan aktif.</span>
                </div>
                <div class="screenshot">
                    <img src="{{ asset('images/tutorial/step-02.webp') }}" alt="Permintaan Izin Lokasi">
                </div>
            </div>
        </div>

        {{-- STEP 4 --}}
        <div class="step-card">
            <div class="step-card-header">
                <div class="step-num">4</div>
                <h3>Pilih Jenis Presensi</h3>
                <span class="tag tag-green">Formulir</span>
            </div>
            <div class="step-card-body">
                <p>Pada halaman presensi error, tentukan jenis presensi yang ingin Anda laporkan:</p>
                <ul class="options-list">
                    <li>
                        <span class="dot-color" style="background: var(--emerald);"></span>
                        <span><strong>Presensi Masuk</strong> &mdash; Laporkan jam kehadiran saat datang ke kantor</span>
                    </li>
                    <li>
                        <span class="dot-color" style="background: var(--amber);"></span>
                        <span><strong>Presensi Pulang</strong> &mdash; Laporkan jam pulang saat meninggalkan kantor</span>
                    </li>
                </ul>
                <div class="screenshot">
                    <img src="{{ asset('images/tutorial/step-03.webp') }}" alt="Pilihan Jenis Presensi">
                </div>
            </div>
        </div>

        {{-- STEP 5 --}}
        <div class="step-card">
            <div class="step-card-header">
                <div class="step-num">5</div>
                <h3>Pilih Alasan Pengaduan</h3>
                <span class="tag tag-violet">Formulir</span>
            </div>
            <div class="step-card-body">
                <p>Selanjutnya, pilih alasan pengaduan yang sesuai dengan kondisi Anda:</p>
                <ul class="options-list">
                    <li>
                        <span class="dot-color" style="background: #ef4444;"></span>
                        <span><strong>Sistem Error</strong> &mdash; Sistem presensi utama (Pusaka) mengalami gangguan atau tidak dapat diakses</span>
                    </li>
                    <li>
                        <span class="dot-color" style="background: #f59e0b;"></span>
                        <span><strong>Tugas Luar</strong> &mdash; Sedang melaksanakan tugas dinas di luar kantor</span>
                    </li>
                    <li>
                        <span class="dot-color" style="background: #8b5cf6;"></span>
                        <span><strong>Lupa Presensi Pusaka</strong> &mdash; Lupa melakukan presensi kehadiran pada hari tertentu</span>
                    </li>
                </ul>
                <div class="screenshot">
                    <img src="{{ asset('images/tutorial/step-04.webp') }}" alt="Pilihan Alasan Pengaduan">
                </div>
            </div>
        </div>

        {{-- STEP 6 --}}
        <div class="step-card">
            <div class="step-card-header">
                <div class="step-num">6</div>
                <h3>Tentukan Tanggal Lupa Presensi</h3>
                <span class="tag tag-red">Khusus Lupa Presensi</span>
            </div>
            <div class="step-card-body">
                <p>Jika Anda memilih alasan <strong>"Lupa Presensi Pusaka"</strong>, akan muncul kolom untuk memilih tanggal. Pilih tanggal saat Anda lupa melakukan presensi menggunakan <em>date picker</em> yang tersedia.</p>
                <div class="tip tip-blue">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                    <span>Lokasi GPS akan otomatis terdeteksi dan jarak dari kantor akan dihitung secara otomatis oleh sistem.</span>
                </div>
                <div class="screenshot">
                    <img src="{{ asset('images/tutorial/step-05.webp') }}" alt="Pilih Tanggal Lupa Presensi">
                </div>
            </div>
        </div>

        {{-- STEP 7 --}}
        <div class="step-card">
            <div class="step-card-header">
                <div class="step-num">7</div>
                <h3>Ambil Foto Bukti Kehadiran</h3>
                <span class="tag tag-amber">Wajib</span>
            </div>
            <div class="step-card-body">
                <p>Gulir ke bawah hingga menemukan bagian <strong>"Bukti Foto"</strong>. Klik tombol <strong>"Ambil Foto"</strong> pada area yang tersedia. Foto ini berfungsi sebagai bukti kehadiran Anda dan akan disertakan dalam Surat Keterangan.</p>
                <div class="tip tip-amber">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
                    <span><strong>Tips:</strong> Ambil foto yang menunjukkan Anda sedang berada di lokasi kerja (kantor, ruangan, atau meja kerja) agar pengaduan lebih meyakinkan.</span>
                </div>
                <div class="screenshot">
                    <img src="{{ asset('images/tutorial/step-06.webp') }}" alt="Area Bukti Foto">
                </div>
            </div>
        </div>

        {{-- STEP 8 --}}
        <div class="step-card">
            <div class="step-card-header">
                <div class="step-num">8</div>
                <h3>Izinkan Akses Kamera</h3>
                <span class="tag tag-red">Penting</span>
            </div>
            <div class="step-card-body">
                <p>Browser akan meminta izin untuk mengakses kamera perangkat Anda. Klik tombol <strong>"Allow"</strong> atau <strong>"Izinkan"</strong> agar kamera dapat digunakan untuk mengambil foto bukti kehadiran.</p>
                <div class="tip tip-red">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M15 9l-6 6"/><path d="M9 9l6 6"/></svg>
                    <span><strong>Jika izin ditolak:</strong> Anda tidak akan dapat mengambil foto. Silakan atur ulang izin kamera di pengaturan browser Anda, lalu muat ulang halaman ini.</span>
                </div>
                <div class="screenshot">
                    <img src="{{ asset('images/tutorial/step-07.webp') }}" alt="Permintaan Izin Kamera">
                </div>
            </div>
        </div>

        {{-- STEP 9 --}}
        <div class="step-card">
            <div class="step-card-header">
                <div class="step-num">9</div>
                <h3>Ambil & Verifikasi Foto</h3>
                <span class="tag tag-green">Kamera</span>
            </div>
            <div class="step-card-body">
                <p>Arahkan kamera ke objek yang ingin dijadikan bukti, lalu klik tombol <strong>"Ambil Foto"</strong> untuk mengambil gambar. Anda juga dapat:</p>
                <ul class="check-list">
                    <li>
                        <span class="check"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg></span>
                        Klik <strong>"Ganti Kamera"</strong> untuk beralih antara kamera depan dan belakang (jika perangkat mendukung)
                    </li>
                    <li>
                        <span class="check"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg></span>
                        Klik <strong>"Batal"</strong> jika ingin membatalkan pengambilan foto
                    </li>
                    <li>
                        <span class="check"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg></span>
                        Klik <strong>"Hapus Foto"</strong> untuk mengambil ulang foto baru jika hasilnya kurang jelas
                    </li>
                </ul>
                <div class="screenshot">
                    <img src="{{ asset('images/tutorial/step-08.webp') }}" alt="Interface Pengambilan Foto">
                </div>
            </div>
        </div>

        {{-- STEP 10 --}}
        <div class="step-card">
            <div class="step-card-header">
                <div class="step-num">10</div>
                <h3>Kirim Laporan Presensi</h3>
                <span class="tag tag-green">Kirim</span>
            </div>
            <div class="step-card-body">
                <p>Setelah semua data terisi dengan lengkap dan foto bukti sudah ter-upload, klik tombol <strong>"Kirim Laporan Presensi"</strong> untuk mengirim pengaduan Anda.</p>
                <div class="tip tip-green">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="M22 4L12 14.01l-3-3"/></svg>
                    <span><strong>Sebelum mengirim,</strong> pastikan sudah memeriksa: jenis presensi, alasan pengaduan, tanggal (jika lupa presensi), dan foto bukti kehadiran sudah benar.</span>
                </div>
                <div class="screenshot">
                    <img src="{{ asset('images/tutorial/step-09.webp') }}" alt="Kirim Laporan Presensi">
                </div>
            </div>
        </div>

        {{-- STEP 11 --}}
        <div class="step-card">
            <div class="step-card-header">
                <div class="step-num">11</div>
                <h3>Download Surat Keterangan</h3>
                <span class="tag tag-blue">Unduh</span>
            </div>
            <div class="step-card-body">
                <p>Setelah pengaduan berhasil dikirim, sistem akan otomatis menampilkan <strong>Surat Keterangan Pengaduan Presensi</strong> dalam format PDF. Klik tombol <strong>"Download PDF"</strong> untuk menyimpan file ke perangkat Anda, atau klik <strong>"Buka di Tab Baru"</strong> untuk melihatnya langsung di browser.</p>
                <div class="screenshot">
                    <img src="{{ asset('images/tutorial/step-10.webp') }}" alt="Surat Keterangan PDF">
                </div>
            </div>
        </div>

        {{-- STEP 12 --}}
        <div class="step-card">
            <div class="step-card-header">
                <div class="step-num">12</div>
                <h3>Cetak & Tandatangani Surat Keterangan</h3>
                <span class="tag tag-amber">Cetak</span>
            </div>
            <div class="step-card-body">
                <p>Cetak Surat Keterangan yang sudah di-download. Tanda tangani bagian <strong>"Yang Bersangkutan"</strong> pada surat, kemudian minta tanda tangan atasan langsung Anda pada bagian <strong>"Mengetahui"</strong>. Surat ini memuat informasi lengkap meliputi:</p>
                <ul class="check-list">
                    <li>
                        <span class="check"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg></span>
                        Data diri pegawai (nama, NIP, jabatan, unit kerja)
                    </li>
                    <li>
                        <span class="check"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg></span>
                        Tanggal dan jenis presensi yang dilaporkan
                    </li>
                    <li>
                        <span class="check"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg></span>
                        Lokasi GPS dan jarak dari kantor
                    </li>
                    <li>
                        <span class="check"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg></span>
                        Foto bukti kehadiran
                    </li>
                    <li>
                        <span class="check"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg></span>
                        Ruang untuk catatan pimpinan dan tanda tangan
                    </li>
                </ul>
                <div class="screenshot">
                    <img src="{{ asset('images/tutorial/step-11.webp') }}" alt="Cetak Surat Keterangan">
                </div>
            </div>
        </div>

        {{-- STEP 13 (HIGHLIGHT) --}}
        <div class="step-card highlight">
            <div class="step-card-header">
                <div class="step-num">13</div>
                <h3>Upload Surat Keterangan ke Absensi Kementerian Agama RI</h3>
                <span class="tag tag-red">Wajib</span>
            </div>
            <div class="step-card-body">
                <p>Langkah terakhir: upload Surat Keterangan yang sudah ditandatangani ke sistem <strong>Absensi Kementerian Agama RI</strong> sebagai bukti pendukung pengaduan presensi.</p>
                <div class="url-highlight">
                    <small>Akses halaman Absensi Kementerian Agama RI</small>
                    <code>https://absensi.kemenag.go.id</code>
                </div>
                <p><strong>Langkah-langkah upload:</strong></p>
                <ol style="padding-left: 20px; margin: 10px 0;">
                    <li style="padding: 4px 0; font-size: 13.5px; color: var(--slate-600);">Login ke halaman Absensi Kementerian Agama RI</li>
                    <li style="padding: 4px 0; font-size: 13.5px; color: var(--slate-600);">Cari menu pengaduan atau unggah bukti pendukung</li>
                    <li style="padding: 4px 0; font-size: 13.5px; color: var(--slate-600);">Upload file Surat Keterangan PDF yang sudah ditandatangani</li>
                </ol>
                <div class="tip tip-red">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
                    <span><strong>Penting:</strong> Upload surat keterangan ini adalah langkah wajib agar pengaduan presensi Anda tercatat secara resmi di sistem Absensi Kementerian Agama RI. Pastikan surat sudah ditandatangani sebelum diupload.</span>
                </div>
            </div>
        </div>

        {{-- FINAL CARD --}}
        <div class="final-card">
            <h3>&#127919; Pengaduan Presensi Berhasil Dilakukan!</h3>
            <p>
                Dengan mengikuti seluruh langkah di atas, pengaduan presensi Anda akan tercatat secara resmi
                di sistem SILATAR dan Absensi Kementerian Agama RI. Simpan Surat Keterangan
                yang sudah ditandatangani sebagai arsip pribadi.
                <br><br>
                <strong>Terima kasih, semoga bermanfaat!</strong>
            </p>
        </div>

    </div>

    {{-- FOOTER --}}
    <div class="footer">
        <strong>Sistem Informasi Layanan Terintegrasi (SILATAR)</strong><br>
        Kantor Kementerian Agama Kabupaten Tanah Datar &bull; Sumatera Barat, Indonesia<br>
        <span style="opacity: 0.6;">Dokumen ini digenerate secara otomatis &bull; Hubungi bagian TU jika mengalami kendala</span>
    </div>

</body>
</html>