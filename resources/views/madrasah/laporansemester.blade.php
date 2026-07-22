<x-layouts.app title="Laporan Semester Madrasah - SILATAR">
    <main class="neo-mirai">
        <!-- Hero Section -->
        <section class="hero-page has-bg-image" style="padding: 140px 2rem 4rem; min-height: 320px;">
            <div style="max-width: 42rem; text-align: center;">
                <p style="color: var(--gold); font-family: var(--font-mono); font-size: 0.65rem; text-transform: uppercase; margin: 0 0 0.75rem; display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Laporan Madrasah
                </p>
                <h1 style="font-family: var(--font-display); font-size: clamp(1.8rem, 4vw, 2.8rem); font-weight: 400; color: var(--ink); margin: 0 0 1rem; display: flex; align-items: center; justify-content: center; gap: 0.75rem;">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="var(--gold)" stroke-width="1.5"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    LAPORAN SEMESTER
                </h1>
                <p style="color: var(--ink-soft); font-size: 1rem; max-width: 32rem; margin: 0 auto;">Form input laporan semester untuk keperluan pelaporan madrasah.</p>
                <div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 1rem; margin-top: 1.5rem;">
                    <a href="{{ route('madrasah.profil') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.7rem 1.25rem; background: transparent; color: var(--ink); font-family: var(--font-mono); font-size: 0.7rem; font-weight: 600; text-transform: uppercase; text-decoration: none; border: 1px solid var(--line);">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        Kembali ke Profil
                    </a>
                </div>
            </div>
        </section>

        <!-- Section Divider -->
        <div class="section-divider wave-rounded"></div>

        <!-- Content -->
        <section class="page-content">
            <div class="content-centered">

                <!-- Tab Navigation -->
                <div class="neo-tabs" style="margin-bottom: 2rem;">
                    <a href="{{ route('madrasah.profil') }}" class="neo-tab">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        Profil Madrasah
                    </a>
                    <a href="{{ route('madrasah.pegawai') }}" class="neo-tab">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Pegawai
                    </a>
                    <a href="{{ route('madrasah.guru') }}" class="neo-tab">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        Guru
                    </a>
                    <a href="{{ route('madrasah.laporan-semester') }}" class="neo-tab is-active">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Laporan Semester
                    </a>
                    <a href="{{ route('madrasah.laporan-bulanan') }}" class="neo-tab">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        Laporan Bulanan
                    </a>
                </div>

                <form action="{{ route('madrasah.laporan-semester.save') }}" method="POST">
                @csrf
                <input type="hidden" name="semester" x-model="selectedSemester">
                <input type="hidden" name="tahun_ajaran" x-model="tahunAjaran">
                <input type="hidden" name="status" value="draft">

                
                <!-- Section 1: Informasi Laporan -->
                <div class="neo-card" style="margin-bottom: 1.5rem;">
                    <div class="neo-card-header">
                        <div class="neo-card-icon" style="background: var(--gold); color: var(--night);">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <div>
                            <h3 class="neo-card-title">A. Informasi Laporan</h3>
                            <p class="neo-card-desc">Periode dan identitas laporan</p>
                        </div>
                    </div>
                    <div class="neo-card-body">
                        <div class="neo-grid-3">
                            <div class="neo-field-group">
                                <label class="neo-field-label">Semester</label>
                                <select name="semester" class="neo-form-select" required>
                                    <option value="ganjil" {{ ($selectedSemester ?? 'genap') == 'ganjil' ? 'selected' : '' }}>Ganjil</option>
                                    <option value="genap" {{ ($selectedSemester ?? 'genap') == 'genap' ? 'selected' : '' }}>Genap</option>
                                </select>
                            </div>
                            <div class="neo-field-group">
                                <label class="neo-field-label">Tahun Ajaran</label>
                                <select name="tahun_ajaran" class="neo-form-select" required>
                                    @foreach($academicYearOptions ?? [] as $ta)
                                    <option value="{{ $ta }}" {{ ($tahunAjaran ?? '') == $ta ? 'selected' : '' }}>{{ $ta }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="neo-field-group">
                                <label class="neo-field-label">Status</label>
                                <input type="text" class="neo-form-input" value="{{ $reportStatus ?? 'Draft' }}" readonly>
                            </div>
                        </div>
                        <div class="neo-grid-1">
                            <div class="neo-field-group">
                                <small style="color: var(--ink-soft);">
                                    @if(isset($submittedAt) && $submittedAt)
                                    Dikirim pada: {{ \Carbon\Carbon::parse($submittedAt)->format('d F Y H:i') }}
                                    @else
                                    Laporan belum dikirim
                                    @endif
                                </small>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Grid 2: Keadaan Gedung & Sarana -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    <!-- Keadaan Gedung -->
                    <div class="neo-card">
                        <div class="neo-card-header">
                            <div class="neo-card-icon" style="background: var(--gold); color: var(--night);">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            </div>
                            <div>
                                <h3 class="neo-card-title">A. Keadaan Gedung</h3>
                                <p class="neo-card-desc">Kondisi bangunan gedung</p>
                            </div>
                            <button type="button" onclick="addRow(this, 'gedung')" class="neo-btn-add">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14m-7-7h14"/></svg>
                                Tambah
                            </button>
                        </div>
                        <div class="neo-card-body">
                            <div class="neo-table-wrapper">
                                <table class="neo-table" data-table="gedung">
                                    <thead class="neo-table-header">
                                        <tr>
                                            <th>Gedung</th>
                                            <th style="text-align: center;">Baik</th>
                                            <th style="text-align: center;">Ringan</th>
                                            <th style="text-align: center;">Sedang</th>
                                            <th style="text-align: center;">Berat</th>
                                            <th style="text-align: center;">Jml</th>
                                            <th style="width: 50px;"></th>
                                        </tr>
                                    </thead>
                                    <tbody data-tbody="gedung">
                                        @foreach($formData['keadaanGedung'] as $i => $row)
                                        <tr class="neo-table-row">
                                            <td><input type="text" name="keadaanGedung[{{ $i }}][label]" value="{{ $row['label'] }}" class="neo-form-input" placeholder="Nama gedung"></td>
                                            <td><input type="number" name="keadaanGedung[{{ $i }}][baik]" value="{{ $row['baik'] ?? 0 }}" min="0" class="neo-form-input calc-input" style="padding: 0.5rem; text-align: center;"></td>
                                            <td><input type="number" name="keadaanGedung[{{ $i }}][ringan]" value="{{ $row['ringan'] ?? 0 }}" min="0" class="neo-form-input calc-input" style="padding: 0.5rem; text-align: center;"></td>
                                            <td><input type="number" name="keadaanGedung[{{ $i }}][sedang]" value="{{ $row['sedang'] ?? 0 }}" min="0" class="neo-form-input calc-input" style="padding: 0.5rem; text-align: center;"></td>
                                            <td><input type="number" name="keadaanGedung[{{ $i }}][berat]" value="{{ $row['berat'] ?? 0 }}" min="0" class="neo-form-input calc-input" style="padding: 0.5rem; text-align: center;"></td>
                                            <td class="neo-table-cell-mono neo-table-total highlight" style="text-align: center;" data-row-total>{{ ($row['baik'] ?? 0) + ($row['ringan'] ?? 0) + ($row['sedang'] ?? 0) + ($row['berat'] ?? 0) }}</td>
                                            <td><button type="button" onclick="removeRow(this)" class="neo-btn-remove"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 18L18 6M6 6l12 12"/></svg></button></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr class="neo-table-footer">
                                            <td class="neo-table-cell-primary neo-table-total">TOTAL</td>
                                            <td class="neo-table-cell-mono neo-table-total highlight" style="text-align: center;" data-col-total="baik">0</td>
                                            <td class="neo-table-cell-mono neo-table-total highlight" style="text-align: center;" data-col-total="ringan">0</td>
                                            <td class="neo-table-cell-mono neo-table-total highlight" style="text-align: center;" data-col-total="sedang">0</td>
                                            <td class="neo-table-cell-mono neo-table-total highlight" style="text-align: center;" data-col-total="berat">0</td>
                                            <td class="neo-table-cell-mono neo-table-total highlight" style="text-align: center;" data-grand-total>0</td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Sarana Pendidikan -->
                    <div class="neo-card">
                        <div class="neo-card-header">
                            <div class="neo-card-icon" style="background: var(--gold); color: var(--night);">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            </div>
                            <div>
                                <h3 class="neo-card-title">Keadaan Sarana Pendidikan</h3>
                                <p class="neo-card-desc">Kondisi sarana dan prasarana</p>
                            </div>
                            <button type="button" onclick="addRow(this, 'sarana')" class="neo-btn-add">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14m-7-7h14"/></svg>
                                Tambah
                            </button>
                        </div>
                        <div class="neo-card-body">
                            <div class="neo-table-wrapper">
                                <table class="neo-table" data-table="sarana">
                                    <thead class="neo-table-header">
                                        <tr>
                                            <th>Sarana</th>
                                            <th style="text-align: center;">Baik</th>
                                            <th style="text-align: center;">Ringan</th>
                                            <th style="text-align: center;">Sedang</th>
                                            <th style="text-align: center;">Berat</th>
                                            <th style="text-align: center;">Jml</th>
                                            <th style="width: 50px;"></th>
                                        </tr>
                                    </thead>
                                    <tbody data-tbody="sarana">
                                        @foreach($formData['saranaPendidikan'] as $i => $row)
                                        <tr class="neo-table-row">
                                            <td><input type="text" name="saranaPendidikan[{{ $i }}][label]" value="{{ $row['label'] }}" class="neo-form-input" placeholder="Nama sarana"></td>
                                            <td><input type="number" name="saranaPendidikan[{{ $i }}][baik]" value="{{ $row['baik'] ?? 0 }}" min="0" class="neo-form-input calc-input" style="padding: 0.5rem; text-align: center;"></td>
                                            <td><input type="number" name="saranaPendidikan[{{ $i }}][ringan]" value="{{ $row['ringan'] ?? 0 }}" min="0" class="neo-form-input calc-input" style="padding: 0.5rem; text-align: center;"></td>
                                            <td><input type="number" name="saranaPendidikan[{{ $i }}][sedang]" value="{{ $row['sedang'] ?? 0 }}" min="0" class="neo-form-input calc-input" style="padding: 0.5rem; text-align: center;"></td>
                                            <td><input type="number" name="saranaPendidikan[{{ $i }}][berat]" value="{{ $row['berat'] ?? 0 }}" min="0" class="neo-form-input calc-input" style="padding: 0.5rem; text-align: center;"></td>
                                            <td class="neo-table-cell-mono neo-table-total highlight" style="text-align: center;" data-row-total>{{ ($row['baik'] ?? 0) + ($row['ringan'] ?? 0) + ($row['sedang'] ?? 0) + ($row['berat'] ?? 0) }}</td>
                                            <td><button type="button" onclick="removeRow(this)" class="neo-btn-remove"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 18L18 6M6 6l12 12"/></svg></button></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr class="neo-table-footer">
                                            <td class="neo-table-cell-primary neo-table-total">TOTAL</td>
                                            <td class="neo-table-cell-mono neo-table-total highlight" style="text-align: center;" data-col-total="baik">0</td>
                                            <td class="neo-table-cell-mono neo-table-total highlight" style="text-align: center;" data-col-total="ringan">0</td>
                                            <td class="neo-table-cell-mono neo-table-total highlight" style="text-align: center;" data-col-total="sedang">0</td>
                                            <td class="neo-table-cell-mono neo-table-total highlight" style="text-align: center;" data-col-total="berat">0</td>
                                            <td class="neo-table-cell-mono neo-table-total highlight" style="text-align: center;" data-grand-total>0</td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Grid 2: Bantuan Pemerintah & Non Pemerintah -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    <!-- Bantuan Pemerintah -->
                    <div class="neo-card">
                        <div class="neo-card-header">
                            <div class="neo-card-icon" style="background: var(--gold); color: var(--night);">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div>
                                <h3 class="neo-card-title">Jenis Bantuan dari Pemerintah</h3>
                                <p class="neo-card-desc">Bantuan dana dan sarana</p>
                            </div>
                            <button type="button" onclick="addRow(this, 'bantuanP')" class="neo-btn-add">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14m-7-7h14"/></svg>
                                Tambah
                            </button>
                        </div>
                        <div class="neo-card-body">
                            <div class="neo-table-wrapper">
                                <table class="neo-table" data-table="bantuanP">
                                    <thead class="neo-table-header">
                                        <tr>
                                            <th>Jenis Bantuan</th>
                                            <th style="text-align: center;">Diterima</th>
                                            <th style="text-align: center;">Terserap</th>
                                            <th style="text-align: center;">Saldo</th>
                                            <th style="width: 50px;"></th>
                                        </tr>
                                    </thead>
                                    <tbody data-tbody="bantuanP">
                                        @php $totDiterima = 0; $totTerserap = 0; @endphp
                                        @foreach($formData['bantuanPemerintah'] as $i => $row)
                                        @php $totDiterima += $row['diterima'] ?? 0; $totTerserap += $row['terserap'] ?? 0; @endphp
                                        <tr class="neo-table-row">
                                            <td><input type="text" name="bantuanPemerintah[{{ $i }}][label]" value="{{ $row['label'] }}" class="neo-form-input" placeholder="Nama bantuan"></td>
                                            <td><input type="number" name="bantuanPemerintah[{{ $i }}][diterima]" value="{{ $row['diterima'] ?? 0 }}" min="0" class="neo-form-input calc-input" style="padding: 0.5rem; text-align: center;"></td>
                                            <td><input type="number" name="bantuanPemerintah[{{ $i }}][terserap]" value="{{ $row['terserap'] ?? 0 }}" min="0" class="neo-form-input calc-input" style="padding: 0.5rem; text-align: center;"></td>
                                            <td class="neo-table-cell-mono neo-table-total highlight" style="text-align: center;" data-row-total>{{ max(0, ($row['diterima'] ?? 0) - ($row['terserap'] ?? 0)) }}</td>
                                            <td><button type="button" onclick="removeRow(this)" class="neo-btn-remove"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 18L18 6M6 6l12 12"/></svg></button></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr class="neo-table-footer">
                                            <td class="neo-table-cell-primary neo-table-total">TOTAL</td>
                                            <td class="neo-table-cell-mono neo-table-total highlight" style="text-align: center;" data-col-total="diterima">0</td>
                                            <td class="neo-table-cell-mono neo-table-total highlight" style="text-align: center;" data-col-total="terserap">0</td>
                                            <td class="neo-table-cell-mono neo-table-total highlight" style="text-align: center;" data-grand-total>0</td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Bantuan Non Pemerintah -->
                    <div class="neo-card">
                        <div class="neo-card-header">
                            <div class="neo-card-icon" style="background: var(--gold); color: var(--night);">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <div>
                                <h3 class="neo-card-title">Jenis Bantuan Non Pemerintah</h3>
                                <p class="neo-card-desc">Bantuan dari pihak lain</p>
                            </div>
                            <button type="button" onclick="addRow(this, 'bantuanNP')" class="neo-btn-add">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14m-7-7h14"/></svg>
                                Tambah
                            </button>
                        </div>
                        <div class="neo-card-body">
                            <div class="neo-table-wrapper">
                                <table class="neo-table" data-table="bantuanNP">
                                    <thead class="neo-table-header">
                                        <tr>
                                            <th>Jenis Bantuan</th>
                                            <th style="text-align: center;">Diterima</th>
                                            <th style="text-align: center;">Terserap</th>
                                            <th style="text-align: center;">Saldo</th>
                                            <th style="width: 50px;"></th>
                                        </tr>
                                    </thead>
                                    <tbody data-tbody="bantuanNP">
                                        @php $totDiterima2 = 0; $totTerserap2 = 0; @endphp
                                        @foreach($formData['bantuanNonPemerintah'] as $i => $row)
                                        @php $totDiterima2 += $row['diterima'] ?? 0; $totTerserap2 += $row['terserap'] ?? 0; @endphp
                                        <tr class="neo-table-row">
                                            <td><input type="text" name="bantuanNonPemerintah[{{ $i }}][label]" value="{{ $row['label'] }}" class="neo-form-input" placeholder="Nama bantuan"></td>
                                            <td><input type="number" name="bantuanNonPemerintah[{{ $i }}][diterima]" value="{{ $row['diterima'] ?? 0 }}" min="0" class="neo-form-input calc-input" style="padding: 0.5rem; text-align: center;"></td>
                                            <td><input type="number" name="bantuanNonPemerintah[{{ $i }}][terserap]" value="{{ $row['terserap'] ?? 0 }}" min="0" class="neo-form-input calc-input" style="padding: 0.5rem; text-align: center;"></td>
                                            <td class="neo-table-cell-mono neo-table-total highlight" style="text-align: center;" data-row-total>{{ max(0, ($row['diterima'] ?? 0) - ($row['terserap'] ?? 0)) }}</td>
                                            <td><button type="button" onclick="removeRow(this)" class="neo-btn-remove"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 18L18 6M6 6l12 12"/></svg></button></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr class="neo-table-footer">
                                            <td class="neo-table-cell-primary neo-table-total">TOTAL</td>
                                            <td class="neo-table-cell-mono neo-table-total highlight" style="text-align: center;" data-col-total="diterima">0</td>
                                            <td class="neo-table-cell-mono neo-table-total highlight" style="text-align: center;" data-col-total="terserap">0</td>
                                            <td class="neo-table-cell-mono neo-table-total highlight" style="text-align: center;" data-grand-total>0</td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Grid 2: Data Guru/Pegawai & Tingkat Pendidikan -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    <!-- Data Guru/Pegawai -->
                    <div class="neo-card">
                        <div class="neo-card-header">
                            <div class="neo-card-icon" style="background: var(--gold); color: var(--night);">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <div>
                                <h3 class="neo-card-title">Data Guru / Pegawai</h3>
                                <p class="neo-card-desc">Jumlah SDM berdasarkan gender</p>
                            </div>
                            <button type="button" onclick="addRow(this, 'guru')" class="neo-btn-add">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14m-7-7h14"/></svg>
                                Tambah
                            </button>
                        </div>
                        <div class="neo-card-body">
                            <div class="neo-table-wrapper">
                                <table class="neo-table" data-table="guru">
                                    <thead class="neo-table-header">
                                        <tr>
                                            <th>Uraian</th>
                                            <th style="text-align: center;">L</th>
                                            <th style="text-align: center;">P</th>
                                            <th style="text-align: center;">Jml</th>
                                            <th style="width: 50px;"></th>
                                        </tr>
                                    </thead>
                                    <tbody data-tbody="guru">
                                        @php $totL = 0; $totP = 0; @endphp
                                        @foreach($formData['dataGuruPegawai'] as $i => $row)
                                        @php $totL += $row['l'] ?? 0; $totP += $row['p'] ?? 0; @endphp
                                        <tr class="neo-table-row">
                                            <td><input type="text" name="dataGuruPegawai[{{ $i }}][label]" value="{{ $row['label'] }}" class="neo-form-input" placeholder="Nama uraian"></td>
                                            <td><input type="number" name="dataGuruPegawai[{{ $i }}][l]" value="{{ $row['l'] ?? 0 }}" min="0" class="neo-form-input calc-input" style="padding: 0.5rem; text-align: center;"></td>
                                            <td><input type="number" name="dataGuruPegawai[{{ $i }}][p]" value="{{ $row['p'] ?? 0 }}" min="0" class="neo-form-input calc-input" style="padding: 0.5rem; text-align: center;"></td>
                                            <td class="neo-table-cell-mono neo-table-total highlight" style="text-align: center;" data-row-total>{{ ($row['l'] ?? 0) + ($row['p'] ?? 0) }}</td>
                                            <td><button type="button" onclick="removeRow(this)" class="neo-btn-remove"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 18L18 6M6 6l12 12"/></svg></button></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr class="neo-table-footer">
                                            <td class="neo-table-cell-primary neo-table-total">TOTAL</td>
                                            <td class="neo-table-cell-mono neo-table-total highlight" style="text-align: center;" data-col-total="l">0</td>
                                            <td class="neo-table-cell-mono neo-table-total highlight" style="text-align: center;" data-col-total="p">0</td>
                                            <td class="neo-table-cell-mono neo-table-total highlight" style="text-align: center;" data-grand-total>0</td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Tingkat Pendidikan -->
                    <div class="neo-card">
                        <div class="neo-card-header">
                            <div class="neo-card-icon" style="background: var(--gold); color: var(--night);">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            </div>
                            <div>
                                <h3 class="neo-card-title">Tingkat Pendidikan</h3>
                                <p class="neo-card-desc">Jumlah siswa per tingkat</p>
                            </div>
                        </div>
                        <div class="neo-card-body">
                            <div class="neo-table-wrapper">
                                <table class="neo-table" data-table="tingkat">
                                    <thead class="neo-table-header">
                                        <tr>
                                            <th>Tingkat</th>
                                            <th style="text-align: center;">L</th>
                                            <th style="text-align: center;">P</th>
                                            <th style="text-align: center;">Jml</th>
                                            <th style="width: 50px;"></th>
                                        </tr>
                                    </thead>
                                    <tbody data-tbody="tingkat">
                                        @php $totLTp = 0; $totPTp = 0; @endphp
                                        @foreach($formData['tingkatPendidikan'] as $i => $row)
                                        @php $totLTp += $row['l'] ?? 0; $totPTp += $row['p'] ?? 0; @endphp
                                        <tr class="neo-table-row">
                                            <td><input type="text" name="tingkatPendidikan[{{ $i }}][label]" value="{{ $row['label'] }}" class="neo-form-input" placeholder="Nama tingkat"></td>
                                            <td><input type="number" name="tingkatPendidikan[{{ $i }}][l]" value="{{ $row['l'] ?? 0 }}" min="0" class="neo-form-input calc-input" style="padding: 0.5rem; text-align: center;"></td>
                                            <td><input type="number" name="tingkatPendidikan[{{ $i }}][p]" value="{{ $row['p'] ?? 0 }}" min="0" class="neo-form-input calc-input" style="padding: 0.5rem; text-align: center;"></td>
                                            <td class="neo-table-cell-mono neo-table-total highlight" style="text-align: center;" data-row-total>{{ ($row['l'] ?? 0) + ($row['p'] ?? 0) }}</td>
                                            <td><button type="button" onclick="removeRow(this)" class="neo-btn-remove"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 18L18 6M6 6l12 12"/></svg></button></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr class="neo-table-footer">
                                            <td class="neo-table-cell-primary neo-table-total">TOTAL</td>
                                            <td class="neo-table-cell-mono neo-table-total highlight" style="text-align: center;" data-col-total="l">0</td>
                                            <td class="neo-table-cell-mono neo-table-total highlight" style="text-align: center;" data-col-total="p">0</td>
                                            <td class="neo-table-cell-mono neo-table-total highlight" style="text-align: center;" data-grand-total>0</td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Grid 2: Sertifikasi & Absensi -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    <!-- Sertifikasi -->
                    <div class="neo-card">
                        <div class="neo-card-header">
                            <div class="neo-card-icon" style="background: var(--gold); color: var(--night);">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                            </div>
                            <div>
                                <h3 class="neo-card-title">Sertifikasi</h3>
                                <p class="neo-card-desc">Status sertifikasi guru</p>
                            </div>
                        </div>
                        <div class="neo-card-body">
                            <div class="neo-table-wrapper">
                                <table class="neo-table" data-table="sertifikasi">
                                    <thead class="neo-table-header">
                                        <tr>
                                            <th>Kategori</th>
                                            <th style="text-align: center;">L</th>
                                            <th style="text-align: center;">P</th>
                                            <th style="text-align: center;">Jml</th>
                                            <th style="width: 50px;"></th>
                                        </tr>
                                    </thead>
                                    <tbody data-tbody="sertifikasi">
                                        @php $totLSert = 0; $totPSert = 0; @endphp
                                        @foreach($formData['sertifikasi'] as $i => $row)
                                        @php $totLSert += $row['l'] ?? 0; $totPSert += $row['p'] ?? 0; @endphp
                                        <tr class="neo-table-row">
                                            <td><input type="text" name="sertifikasi[{{ $i }}][label]" value="{{ $row['label'] }}" class="neo-form-input" placeholder="Nama kategori"></td>
                                            <td><input type="number" name="sertifikasi[{{ $i }}][l]" value="{{ $row['l'] ?? 0 }}" min="0" class="neo-form-input calc-input" style="padding: 0.5rem; text-align: center;"></td>
                                            <td><input type="number" name="sertifikasi[{{ $i }}][p]" value="{{ $row['p'] ?? 0 }}" min="0" class="neo-form-input calc-input" style="padding: 0.5rem; text-align: center;"></td>
                                            <td class="neo-table-cell-mono neo-table-total highlight" style="text-align: center;" data-row-total>{{ ($row['l'] ?? 0) + ($row['p'] ?? 0) }}</td>
                                            <td><button type="button" onclick="removeRow(this)" class="neo-btn-remove"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 18L18 6M6 6l12 12"/></svg></button></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr class="neo-table-footer">
                                            <td class="neo-table-cell-primary neo-table-total">TOTAL</td>
                                            <td class="neo-table-cell-mono neo-table-total highlight" style="text-align: center;" data-col-total="l">0</td>
                                            <td class="neo-table-cell-mono neo-table-total highlight" style="text-align: center;" data-col-total="p">0</td>
                                            <td class="neo-table-cell-mono neo-table-total highlight" style="text-align: center;" data-grand-total>0</td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Absensi Siswa -->
                    <div class="neo-card">
                        <div class="neo-card-header">
                            <div class="neo-card-icon" style="background: var(--gold); color: var(--night);">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                            </div>
                            <div>
                                <h3 class="neo-card-title">Kehadiran & Absensi</h3>
                                <p class="neo-card-desc">Data kehadiran siswa</p>
                            </div>
                            <button type="button" onclick="addRow(this, 'absensi')" class="neo-btn-add">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14m-7-7h14"/></svg>
                                Tambah
                            </button>
                        </div>
                        <div class="neo-card-body">
                            <div class="mb-4">
                                <label class="neo-field-label">Banyak Hari Sekolah</label>
                                <input type="number" name="banyakHariSekolah" value="{{ $formData['banyakHariSekolah'] ?? 0 }}" min="0" class="neo-form-input">
                            </div>
                            <div class="neo-table-wrapper">
                                <table class="neo-table" data-table="absensi">
                                    <thead class="neo-table-header">
                                        <tr>
                                            <th>Keterangan</th>
                                            <th style="text-align: center;">L</th>
                                            <th style="text-align: center;">P</th>
                                            <th style="text-align: center;">Jml</th>
                                            <th style="width: 50px;"></th>
                                        </tr>
                                    </thead>
                                    <tbody data-tbody="absensi">
                                        @php $totLAbs = 0; $totPAbs = 0; @endphp
                                        @foreach($formData['absensiSiswa'] as $i => $row)
                                        @php $totLAbs += $row['l'] ?? 0; $totPAbs += $row['p'] ?? 0; @endphp
                                        <tr class="neo-table-row">
                                            <td><input type="text" name="absensiSiswa[{{ $i }}][label]" value="{{ $row['label'] }}" class="neo-form-input" placeholder="Nama keterangan"></td>
                                            <td><input type="number" name="absensiSiswa[{{ $i }}][l]" value="{{ $row['l'] ?? 0 }}" min="0" class="neo-form-input calc-input" style="padding: 0.5rem; text-align: center;"></td>
                                            <td><input type="number" name="absensiSiswa[{{ $i }}][p]" value="{{ $row['p'] ?? 0 }}" min="0" class="neo-form-input calc-input" style="padding: 0.5rem; text-align: center;"></td>
                                            <td class="neo-table-cell-mono neo-table-total highlight" style="text-align: center;" data-row-total>{{ ($row['l'] ?? 0) + ($row['p'] ?? 0) }}</td>
                                            <td><button type="button" onclick="removeRow(this)" class="neo-btn-remove"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 18L18 6M6 6l12 12"/></svg></button></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr class="neo-table-footer">
                                            <td class="neo-table-cell-primary neo-table-total">TOTAL</td>
                                            <td class="neo-table-cell-mono neo-table-total highlight" style="text-align: center;" data-col-total="l">0</td>
                                            <td class="neo-table-cell-mono neo-table-total highlight" style="text-align: center;" data-col-total="p">0</td>
                                            <td class="neo-table-cell-mono neo-table-total highlight" style="text-align: center;" data-grand-total>0</td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tanah & Sertifikat -->
                <div class="neo-card">
                    <div class="neo-card-header">
                        <div class="neo-card-icon" style="background: var(--gold); color: var(--night);">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <div>
                            <h3 class="neo-card-title">Tanah & Sertifikat Tanah</h3>
                            <p class="neo-card-desc">Informasi kepemilikan tanah</p>
                        </div>
                    </div>
                    <div class="neo-card-body">
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                            @foreach($formData['luasTanah'] as $key => $value)
                            <div>
                                <label class="neo-field-label">{{ ucwords(str_replace('_', ' ', $key)) }} (m2)</label>
                                <input type="number" name="luasTanah[{{ $key }}]" value="{{ $value ?? 0 }}" min="0" class="neo-form-input">
                            </div>
                            @endforeach
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div>
                                <label class="neo-field-label">Status Kepemilikan Tanah</label>
                                <input type="text" name="sertifikatTanah[statusKepemilikan]" value="{{ $formData['sertifikatTanah']['statusKepemilikan'] ?? '' }}" placeholder="Contoh: Milik Sendiri" class="neo-form-input">
                            </div>
                            <div>
                                <label class="neo-field-label">Nomor Sertifikat</label>
                                <input type="text" name="sertifikatTanah[nomor]" value="{{ $formData['sertifikatTanah']['nomor'] ?? '' }}" placeholder="Nomor sertifikat" class="neo-form-input">
                            </div>
                            <div>
                                <label class="neo-field-label">Tanggal Sertifikat</label>
                                <input type="date" name="sertifikatTanah[tanggal]" value="{{ $formData['sertifikatTanah']['tanggal'] ?? '' }}" class="neo-form-input">
                            </div>
                            <div>
                                <label class="neo-field-label">Luas Tanah Sertifikat (m2)</label>
                                <input type="number" name="sertifikatTanah[luas]" value="{{ $formData['sertifikatTanah']['luas'] ?? 0 }}" min="0" class="neo-form-input">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="neo-form-actions">
                    <button type="submit" class="neo-btn-action-save">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                        Simpan Draft
                    </button>
                </div>

                </form>
            </div>
        </section>

        <!-- Reactive Totals Script -->
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Calculate initial totals for all tables
            document.querySelectorAll('.neo-table').forEach(table => {
                calculateTableTotals(table);
            });

            // Add event listeners to all calc-inputs
            document.querySelectorAll('.calc-input').forEach(input => {
                input.addEventListener('input', function() {
                    const table = this.closest('.neo-table');
                    if (table) {
                        updateRowTotal(this.closest('tr'));
                        calculateTableTotals(table);
                    }
                });
            });

            // Function to update single row total
            function updateRowTotal(row) {
                const inputs = row.querySelectorAll('.calc-input');
                let total = 0;
                inputs.forEach(input => {
                    total += parseInt(input.value) || 0;
                });
                const totalCell = row.querySelector('[data-row-total]');
                if (totalCell) {
                    totalCell.textContent = total;
                    totalCell.classList.add('neo-table-total', 'highlight');
                }
            }

            // Function to calculate all totals for a table
            function calculateTableTotals(table) {
                const tbody = table.querySelector('tbody');
                const tfoot = table.querySelector('tfoot');
                if (!tbody || !tfoot) return;

                const rows = tbody.querySelectorAll('tr');
                const inputs = rows[0]?.querySelectorAll('.calc-input') || [];
                const numCols = inputs.length;

                // Get column totals
                const colTotals = new Array(numCols).fill(0);
                let grandTotal = 0;

                rows.forEach(row => {
                    const rowInputs = row.querySelectorAll('.calc-input');
                    rowInputs.forEach((input, idx) => {
                        colTotals[idx] += parseInt(input.value) || 0;
                    });
                    // Update row total
                    let rowTotal = 0;
                    rowInputs.forEach(input => {
                        rowTotal += parseInt(input.value) || 0;
                    });
                    const totalCell = row.querySelector('[data-row-total]');
                    if (totalCell) {
                        totalCell.textContent = rowTotal;
                    }
                    grandTotal += rowTotal;
                });

                // Update footer
                const footerRow = tfoot.querySelector('tr');
                if (footerRow) {
                    // Update column totals
                    const colTotalCells = footerRow.querySelectorAll('[data-col-total]');
                    colTotalCells.forEach((cell, idx) => {
                        const colName = cell.dataset.colTotal;
                        const colIndex = ['baik', 'ringan', 'sedang', 'berat', 'diterima', 'terserap', 'l', 'p'].indexOf(colName);
                        if (colIndex >= 0 && colIndex < colTotals.length) {
                            cell.textContent = colTotals[colIndex];
                            cell.classList.add('neo-table-total', 'highlight');
                        }
                    });
                    // Update grand total
                    const grandTotalCell = footerRow.querySelector('[data-grand-total]');
                    if (grandTotalCell) {
                        grandTotalCell.textContent = grandTotal;
                        grandTotalCell.classList.add('neo-table-total', 'highlight');
                    }
                }
            }
        });

        // Add row function
        function addRow(btn, type) {
            const card = btn.closest('.neo-card');
            const table = card.querySelector('.neo-table');
            const tbody = table.querySelector('tbody');
            const template = getTemplate(type);
            const newIndex = tbody.children.length;
            const newRow = document.createElement('tr');
            newRow.className = 'neo-table-row';
            newRow.innerHTML = template.replace(/__INDEX__/g, newIndex);

            // Append to tbody
            tbody.appendChild(newRow);

            // Add event listener to new inputs
            newRow.querySelectorAll('.calc-input').forEach(input => {
                input.addEventListener('input', function() {
                    updateRowTotal(this.closest('tr'));
                    calculateTableTotals(table);
                });
            });

            // Focus the first input
            newRow.querySelector('input[type="text"]')?.focus();

            // Recalculate totals
            calculateTableTotals(table);
        }

        // Remove row function
        function removeRow(btn) {
            const row = btn.closest('tr');
            const table = row.closest('.neo-table');
            const tbody = table.querySelector('tbody');

            // Don't remove if only 1 row left
            if (tbody.children.length <= 1) return;

            row.remove();

            // Recalculate totals
            calculateTableTotals(table);
        }

        // Get row template based on table type
        function getTemplate(type) {
            const templates = {
                gedung: `<td><input type="text" name="keadaanGedung[__INDEX__][label]" class="neo-form-input" placeholder="Nama gedung"></td>
                         <td><input type="number" name="keadaanGedung[__INDEX__][baik]" value="0" min="0" class="neo-form-input calc-input" style="padding: 0.5rem; text-align: center;"></td>
                         <td><input type="number" name="keadaanGedung[__INDEX__][ringan]" value="0" min="0" class="neo-form-input calc-input" style="padding: 0.5rem; text-align: center;"></td>
                         <td><input type="number" name="keadaanGedung[__INDEX__][sedang]" value="0" min="0" class="neo-form-input calc-input" style="padding: 0.5rem; text-align: center;"></td>
                         <td><input type="number" name="keadaanGedung[__INDEX__][berat]" value="0" min="0" class="neo-form-input calc-input" style="padding: 0.5rem; text-align: center;"></td>
                         <td class="neo-table-cell-mono neo-table-total highlight" style="text-align: center;" data-row-total>0</td>
                         <td><button type="button" onclick="removeRow(this)" class="neo-btn-remove"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 18L18 6M6 6l12 12"/></svg></button></td>`,
                sarana: `<td><input type="text" name="saranaPendidikan[__INDEX__][label]" class="neo-form-input" placeholder="Nama sarana"></td>
                         <td><input type="number" name="saranaPendidikan[__INDEX__][baik]" value="0" min="0" class="neo-form-input calc-input" style="padding: 0.5rem; text-align: center;"></td>
                         <td><input type="number" name="saranaPendidikan[__INDEX__][ringan]" value="0" min="0" class="neo-form-input calc-input" style="padding: 0.5rem; text-align: center;"></td>
                         <td><input type="number" name="saranaPendidikan[__INDEX__][sedang]" value="0" min="0" class="neo-form-input calc-input" style="padding: 0.5rem; text-align: center;"></td>
                         <td><input type="number" name="saranaPendidikan[__INDEX__][berat]" value="0" min="0" class="neo-form-input calc-input" style="padding: 0.5rem; text-align: center;"></td>
                         <td class="neo-table-cell-mono neo-table-total highlight" style="text-align: center;" data-row-total>0</td>
                         <td><button type="button" onclick="removeRow(this)" class="neo-btn-remove"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 18L18 6M6 6l12 12"/></svg></button></td>`,
                bantuanP: `<td><input type="text" name="bantuanPemerintah[__INDEX__][label]" class="neo-form-input" placeholder="Nama bantuan"></td>
                           <td><input type="number" name="bantuanPemerintah[__INDEX__][diterima]" value="0" min="0" class="neo-form-input calc-input" style="padding: 0.5rem; text-align: center;"></td>
                           <td><input type="number" name="bantuanPemerintah[__INDEX__][terserap]" value="0" min="0" class="neo-form-input calc-input" style="padding: 0.5rem; text-align: center;"></td>
                           <td class="neo-table-cell-mono neo-table-total highlight" style="text-align: center;" data-row-total>0</td>
                           <td><button type="button" onclick="removeRow(this)" class="neo-btn-remove"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 18L18 6M6 6l12 12"/></svg></button></td>`,
                bantuanNP: `<td><input type="text" name="bantuanNonPemerintah[__INDEX__][label]" class="neo-form-input" placeholder="Nama bantuan"></td>
                            <td><input type="number" name="bantuanNonPemerintah[__INDEX__][diterima]" value="0" min="0" class="neo-form-input calc-input" style="padding: 0.5rem; text-align: center;"></td>
                            <td><input type="number" name="bantuanNonPemerintah[__INDEX__][terserap]" value="0" min="0" class="neo-form-input calc-input" style="padding: 0.5rem; text-align: center;"></td>
                            <td class="neo-table-cell-mono neo-table-total highlight" style="text-align: center;" data-row-total>0</td>
                            <td><button type="button" onclick="removeRow(this)" class="neo-btn-remove"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 18L18 6M6 6l12 12"/></svg></button></td>`,
                guru: `<td><input type="text" name="dataGuruPegawai[__INDEX__][label]" class="neo-form-input" placeholder="Nama uraian"></td>
                       <td><input type="number" name="dataGuruPegawai[__INDEX__][l]" value="0" min="0" class="neo-form-input calc-input" style="padding: 0.5rem; text-align: center;"></td>
                       <td><input type="number" name="dataGuruPegawai[__INDEX__][p]" value="0" min="0" class="neo-form-input calc-input" style="padding: 0.5rem; text-align: center;"></td>
                       <td class="neo-table-cell-mono neo-table-total highlight" style="text-align: center;" data-row-total>0</td>
                       <td><button type="button" onclick="removeRow(this)" class="neo-btn-remove"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 18L18 6M6 6l12 12"/></svg></button></td>`,
                tingkat: `<td><input type="text" name="tingkatPendidikan[__INDEX__][label]" class="neo-form-input" placeholder="Nama tingkat"></td>
                          <td><input type="number" name="tingkatPendidikan[__INDEX__][l]" value="0" min="0" class="neo-form-input calc-input" style="padding: 0.5rem; text-align: center;"></td>
                          <td><input type="number" name="tingkatPendidikan[__INDEX__][p]" value="0" min="0" class="neo-form-input calc-input" style="padding: 0.5rem; text-align: center;"></td>
                          <td class="neo-table-cell-mono neo-table-total highlight" style="text-align: center;" data-row-total>0</td>
                          <td><button type="button" onclick="removeRow(this)" class="neo-btn-remove"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 18L18 6M6 6l12 12"/></svg></button></td>`,
                sertifikasi: `<td><input type="text" name="sertifikasi[__INDEX__][label]" class="neo-form-input" placeholder="Nama kategori"></td>
                             <td><input type="number" name="sertifikasi[__INDEX__][l]" value="0" min="0" class="neo-form-input calc-input" style="padding: 0.5rem; text-align: center;"></td>
                             <td><input type="number" name="sertifikasi[__INDEX__][p]" value="0" min="0" class="neo-form-input calc-input" style="padding: 0.5rem; text-align: center;"></td>
                             <td class="neo-table-cell-mono neo-table-total highlight" style="text-align: center;" data-row-total>0</td>
                             <td><button type="button" onclick="removeRow(this)" class="neo-btn-remove"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 18L18 6M6 6l12 12"/></svg></button></td>`,
                absensi: `<td><input type="text" name="absensiSiswa[__INDEX__][label]" class="neo-form-input" placeholder="Nama keterangan"></td>
                          <td><input type="number" name="absensiSiswa[__INDEX__][l]" value="0" min="0" class="neo-form-input calc-input" style="padding: 0.5rem; text-align: center;"></td>
                          <td><input type="number" name="absensiSiswa[__INDEX__][p]" value="0" min="0" class="neo-form-input calc-input" style="padding: 0.5rem; text-align: center;"></td>
                          <td class="neo-table-cell-mono neo-table-total highlight" style="text-align: center;" data-row-total>0</td>
                          <td><button type="button" onclick="removeRow(this)" class="neo-btn-remove"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 18L18 6M6 6l12 12"/></svg></button></td>`
            };
            return templates[type] || templates.gedung;
        }

        // Update row total
        function updateRowTotal(row) {
            const inputs = row.querySelectorAll('.calc-input');
            let total = 0;
            inputs.forEach(input => {
                total += parseInt(input.value) || 0;
            });
            const totalCell = row.querySelector('[data-row-total]');
            if (totalCell) {
                totalCell.textContent = total;
                totalCell.classList.add('neo-table-total', 'highlight');
            }
        }

        // Calculate all totals for a table
        function calculateTableTotals(table) {
            const tbody = table.querySelector('tbody');
            const tfoot = table.querySelector('tfoot');
            if (!tbody || !tfoot) return;

            const rows = tbody.querySelectorAll('tr');
            const inputs = rows[0]?.querySelectorAll('.calc-input') || [];
            const numCols = inputs.length;

            // Get column totals
            const colTotals = new Array(numCols).fill(0);
            let grandTotal = 0;

            rows.forEach(row => {
                const rowInputs = row.querySelectorAll('.calc-input');
                rowInputs.forEach((input, idx) => {
                    colTotals[idx] += parseInt(input.value) || 0;
                });
                // Update row total
                let rowTotal = 0;
                rowInputs.forEach(input => {
                    rowTotal += parseInt(input.value) || 0;
                });
                const totalCell = row.querySelector('[data-row-total]');
                if (totalCell) {
                    totalCell.textContent = rowTotal;
                }
                grandTotal += rowTotal;
            });

            // Update footer
            const footerRow = tfoot.querySelector('tr');
            if (footerRow) {
                // Update column totals
                const colTotalCells = footerRow.querySelectorAll('[data-col-total]');
                colTotalCells.forEach((cell) => {
                    const colName = cell.dataset.colTotal;
                    const colIndex = ['baik', 'ringan', 'sedang', 'berat', 'diterima', 'terserap', 'l', 'p'].indexOf(colName);
                    if (colIndex >= 0 && colIndex < colTotals.length) {
                        cell.textContent = colTotals[colIndex];
                        cell.classList.add('neo-table-total', 'highlight');
                    }
                });
                // Update grand total
                const grandTotalCell = footerRow.querySelector('[data-grand-total]');
                if (grandTotalCell) {
                    grandTotalCell.textContent = grandTotal;
                    grandTotalCell.classList.add('neo-table-total', 'highlight');
                }
            }
        }
        </script>
    </main>
</x-layouts.app>