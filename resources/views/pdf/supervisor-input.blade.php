<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Atasan - SILATAR</title>
    @vite(['resources/css/app.css', 'resource/js/app.js'])
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Segoe UI', system-ui, sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.7);
            backdrop-filter: blur(4px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .modal-box {
            background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
            border: 1px solid rgba(6, 182, 212, 0.3);
            border-radius: 16px;
            padding: 32px;
            max-width: 480px;
            width: 100%;
            box-shadow: 0 0 60px rgba(6, 182, 212, 0.2);
        }
        .modal-header {
            text-align: center;
            margin-bottom: 28px;
        }
        .modal-badge {
            display: inline-block;
            background: rgba(6, 182, 212, 0.1);
            border: 1px solid rgba(6, 182, 212, 0.3);
            border-radius: 20px;
            padding: 4px 12px;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: #06b6d4;
            margin-bottom: 12px;
        }
        .modal-title {
            font-size: 22px;
            font-weight: 700;
            color: #fff;
            margin-bottom: 8px;
        }
        .modal-subtitle {
            font-size: 14px;
            color: #94a3b8;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #06b6d4;
            margin-bottom: 8px;
        }
        .form-label span {
            color: #f87171;
        }
        .form-input {
            width: 100%;
            padding: 12px 16px;
            background: #0f172a;
            border: 1px solid rgba(6, 182, 212, 0.3);
            border-radius: 10px;
            color: #fff;
            font-size: 14px;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .form-input:focus {
            outline: none;
            border-color: #06b6d4;
            box-shadow: 0 0 0 3px rgba(6, 182, 212, 0.15);
        }
        .form-input::placeholder {
            color: #475569;
        }
        .form-hint {
            font-size: 12px;
            color: #64748b;
            margin-top: 6px;
        }
        .btn-group {
            display: flex;
            gap: 12px;
            margin-top: 28px;
        }
        .btn {
            flex: 1;
            padding: 14px 24px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            border: none;
        }
        .btn-cancel {
            background: transparent;
            border: 1px solid #475569;
            color: #94a3b8;
        }
        .btn-cancel:hover {
            background: #1e293b;
            border-color: #64748b;
            color: #fff;
        }
        .btn-submit {
            background: linear-gradient(135deg, #0891b2 0%, #06b6d4 100%);
            color: #fff;
            box-shadow: 0 4px 15px rgba(6, 182, 212, 0.3);
        }
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(6, 182, 212, 0.4);
        }
        .info-box {
            background: rgba(251, 191, 36, 0.1);
            border: 1px solid rgba(251, 191, 36, 0.3);
            border-radius: 10px;
            padding: 14px;
            margin-bottom: 24px;
        }
        .info-box p {
            font-size: 13px;
            color: #fbbf24;
            line-height: 1.5;
        }
        .user-info {
            background: rgba(6, 182, 212, 0.1);
            border-radius: 8px;
            padding: 12px 16px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .user-info-label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #64748b;
        }
        .user-info-value {
            font-size: 14px;
            font-weight: 600;
            color: #06b6d4;
        }
    </style>
</head>
<body>
    <div class="modal-overlay">
        <div class="modal-box">
            <div class="modal-header">
                <div class="modal-badge">Laporan Kinerja</div>
                <h2 class="modal-title">Input Data Atasan</h2>
                <p class="modal-subtitle">Masukkan nama dan NIP atasan untuk penandatanganan laporan</p>
            </div>

            <div class="info-box">
                <p>Unit kerja Anda memerlukan input manual atasan. Data ini akan digunakan sebagai tanda tangan "Mengetahui Kepala" pada PDF laporan.</p>
            </div>

            <div class="user-info">
                <div>
                    <div class="user-info-label">Unit Kerja</div>
                    <div class="user-info-value">{{ $unitName }}</div>
                </div>
                <div>
                    <div class="user-info-label">Periode</div>
                    <div class="user-info-value">{{ $periodLabel }}</div>
                </div>
            </div>

            <form method="POST" action="{{ route('laporan-kinerja.rekap.supervisor') }}">
                @csrf
                <input type="hidden" name="dept_id" value="{{ $deptId }}">
                <input type="hidden" name="month" value="{{ $month }}">
                <input type="hidden" name="tab" value="{{ $tab }}">

                <div class="form-group">
                    <label class="form-label">Nama Atasan <span>*</span></label>
                    <input
                        type="text"
                        name="supervisor_name"
                        class="form-input"
                        placeholder="Contoh: H. HELMIZULDI S.Ag., M.Pd.I."
                        value="{{ old('supervisor_name') }}"
                        required
                    >
                    @error('supervisor_name')
                        <p class="form-hint" style="color: #f87171;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">NIP Atasan <span>*</span></label>
                    <input
                        type="text"
                        name="supervisor_nip"
                        class="form-input"
                        placeholder="Contoh: 197108101996031002"
                        value="{{ old('supervisor_nip') }}"
                        required
                    >
                    @error('supervisor_nip')
                        <p class="form-hint" style="color: #f87171;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="btn-group">
                    <a href="{{ route('laporan-kinerja', ['tab' => $tab, 'month' => $month]) }}" class="btn btn-cancel">Batal</a>
                    <button type="submit" class="btn btn-submit">Buat Rekap</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>