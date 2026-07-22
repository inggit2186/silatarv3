<x-layouts.app title="Laporan Semester Madrasah - SILATAR">
    <main class="neo-mirai madrasah-semester madrasah-fullwidth">
        <!-- Hero Section -->
        <section class="hero-page has-bg-image" style="padding: 100px 2rem 2rem; min-height: 240px;">
            <div style="max-width: 48rem; text-align: center;">
                <div class="hero-badge">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Laporan Madrasah
                </div>
                <h1 class="hero-title">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="var(--gold)" stroke-width="1.5"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    LAPORAN SEMESTER
                </h1>
                <p class="hero-subtitle">Form input laporan semester untuk keperluan pelaporan madrasah</p>
            </div>
        </section>

        <!-- Section Divider -->
        <div class="section-divider wave-rounded"></div>

        <!-- Content -->
        <section class="page-content page-content-expanded">
            <!-- Tab Navigation - Large & Prominent -->
            <div class="neo-tabs neo-tabs-large" role="tablist">
                <a href="{{ route('madrasah.profil') }}" class="neo-tab" role="tab">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="22" height="22"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    <span>Profil</span>
                </a>
                <a href="{{ route('madrasah.pegawai') }}" class="neo-tab" role="tab">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="22" height="22"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span>Pegawai</span>
                </a>
                <a href="{{ route('madrasah.guru') }}" class="neo-tab" role="tab">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="22" height="22"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    <span>Guru</span>
                </a>
                <a href="{{ route('madrasah.laporan-semester') }}" class="neo-tab is-active" role="tab">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="22" height="22"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>Semester</span>
                </a>
                <a href="{{ route('madrasah.laporan-bulanan') }}" class="neo-tab" role="tab">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="22" height="22"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span>Bulanan</span>
                </a>
            </div>

            <div class="content-inner">
                @csrf
                <input type="hidden" name="semester" x-model="selectedSemester">
                <input type="hidden" name="tahun_ajaran" x-model="tahunAjaran">
                <input type="hidden" name="status" value="draft">

                <!-- Section 1: Informasi Laporan -->
                <div class="neo-card info-card">
                    <div class="neo-card-header">
                        <div class="neo-card-icon" style="background: var(--gold); color: var(--night);">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <div class="neo-card-text">
                            <h3 class="neo-card-title">A. Informasi Laporan</h3>
                            <p class="neo-card-desc">Periode dan identitas laporan</p>
                        </div>
                    </div>
                    <div class="neo-card-body">
                        <!-- Filter Row: Semester, Tahun Ajaran, Status -->
                        <div class="filter-row">
                            <div class="filter-item">
                                <label class="filter-label">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    Semester
                                </label>
                                <select name="semester" class="neo-form-select filter-select" required>
                                    <option value="ganjil" {{ ($selectedSemester ?? 'genap') == 'ganjil' ? 'selected' : '' }}>Ganjil</option>
                                    <option value="genap" {{ ($selectedSemester ?? 'genap') == 'genap' ? 'selected' : '' }}>Genap</option>
                                </select>
                            </div>
                            <div class="filter-item">
                                <label class="filter-label">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                    Tahun Ajaran
                                </label>
                                <select name="tahun_ajaran" class="neo-form-select filter-select" required>
                                    @foreach($academicYearOptions ?? [] as $ta)
                                    <option value="{{ $ta }}" {{ ($tahunAjaran ?? '') == $ta ? 'selected' : '' }}>{{ $ta }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="filter-item">
                                <label class="filter-label">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Status
                                </label>
                                <div class="status-display">
                                    <span class="status-pill status-{{ strtolower($reportStatus ?? 'draft') }}">{{ $reportStatus ?? 'Draft' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Info & Action Buttons -->
                        <div class="submit-row">
                            <div class="submit-info">
                                @if(isset($submittedAt) && $submittedAt)
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>Dikirim pada: {{ \Carbon\Carbon::parse($submittedAt)->format('d F Y H:i') }}</span>
                                @else
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>Laporan belum dikirim</span>
                                @endif
                            </div>
                            <div class="submit-actions">
                                <button type="submit" name="action" value="draft" class="btn-action-save">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                                    Simpan Draft
                                </button>
                                <button type="submit" name="action" value="submit" class="btn-action-primary">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                    Kirim Laporan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Grid 2: Keadaan Gedung & Sarana -->
                <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mb-6">
                    <!-- Keadaan Gedung -->
                    <div class="neo-card table-card">
                        <div class="neo-card-header">
                            <div class="neo-card-icon" style="background: var(--gold); color: var(--night);">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            </div>
                            <div class="neo-card-text">
                                <h3 class="neo-card-title">A. Keadaan Gedung</h3>
                                <p class="neo-card-desc">Kondisi bangunan gedung</p>
                            </div>
                            <button type="button" onclick="addRow(this, 'gedung')" class="neo-btn-add">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14m-7-7h14"/></svg>
                                Tambah
                            </button>
                        </div>
                        <div class="neo-card-body">
                            <div class="neo-table-wrapper table-responsive">
                                <table class="neo-table" data-table="gedung">
                                    <thead class="neo-table-header">
                                        <tr>
                                            <th class="col-label">Gedung / Bangunan</th>
                                            <th class="col-num">Baik</th>
                                            <th class="col-num">Ringan</th>
                                            <th class="col-num">Sedang</th>
                                            <th class="col-num">Berat</th>
                                            <th class="col-total">Jml</th>
                                            <th class="col-action"></th>
                                        </tr>
                                    </thead>
                                    <tbody data-tbody="gedung">
                                        @foreach($formData['keadaanGedung'] as $i => $row)
                                        <tr class="neo-table-row">
                                            <td class="col-label"><input type="text" name="keadaanGedung[{{ $i }}][label]" value="{{ $row['label'] }}" class="neo-form-input" placeholder="Nama gedung/bangunan"></td>
                                            <td class="col-num"><input type="number" name="keadaanGedung[{{ $i }}][baik]" value="{{ $row['baik'] ?? 0 }}" min="0" class="neo-form-input calc-input"></td>
                                            <td class="col-num"><input type="number" name="keadaanGedung[{{ $i }}][ringan]" value="{{ $row['ringan'] ?? 0 }}" min="0" class="neo-form-input calc-input"></td>
                                            <td class="col-num"><input type="number" name="keadaanGedung[{{ $i }}][sedang]" value="{{ $row['sedang'] ?? 0 }}" min="0" class="neo-form-input calc-input"></td>
                                            <td class="col-num"><input type="number" name="keadaanGedung[{{ $i }}][berat]" value="{{ $row['berat'] ?? 0 }}" min="0" class="neo-form-input calc-input"></td>
                                            <td class="col-total neo-table-cell-mono neo-table-total highlight" data-row-total>{{ ($row['baik'] ?? 0) + ($row['ringan'] ?? 0) + ($row['sedang'] ?? 0) + ($row['berat'] ?? 0) }}</td>
                                            <td class="col-action"><button type="button" onclick="removeRow(this)" class="neo-btn-remove"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 18L18 6M6 6l12 12"/></svg></button></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr class="neo-table-footer">
                                            <td class="col-label neo-table-cell-primary neo-table-total">TOTAL</td>
                                            <td class="col-num neo-table-cell-mono neo-table-total highlight" data-col-total="baik">{{ array_sum(array_column($formData['keadaanGedung'], 'baik')) }}</td>
                                            <td class="col-num neo-table-cell-mono neo-table-total highlight" data-col-total="ringan">{{ array_sum(array_column($formData['keadaanGedung'], 'ringan')) }}</td>
                                            <td class="col-num neo-table-cell-mono neo-table-total highlight" data-col-total="sedang">{{ array_sum(array_column($formData['keadaanGedung'], 'sedang')) }}</td>
                                            <td class="col-num neo-table-cell-mono neo-table-total highlight" data-col-total="berat">{{ array_sum(array_column($formData['keadaanGedung'], 'berat')) }}</td>
                                            <td class="col-total neo-table-cell-mono neo-table-total highlight" data-grand-total>0</td>
                                            <td class="col-action"></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Sarana Pendidikan -->
                    <div class="neo-card table-card">
                        <div class="neo-card-header">
                            <div class="neo-card-icon" style="background: var(--gold); color: var(--night);">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            </div>
                            <div class="neo-card-text">
                                <h3 class="neo-card-title">Keadaan Sarana Pendidikan</h3>
                                <p class="neo-card-desc">Kondisi sarana dan prasarana</p>
                            </div>
                            <button type="button" onclick="addRow(this, 'sarana')" class="neo-btn-add">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14m-7-7h14"/></svg>
                                Tambah
                            </button>
                        </div>
                        <div class="neo-card-body">
                            <div class="neo-table-wrapper table-responsive">
                                <table class="neo-table" data-table="sarana">
                                    <thead class="neo-table-header">
                                        <tr>
                                            <th class="col-label">Sarana / Prasarana</th>
                                            <th class="col-num">Baik</th>
                                            <th class="col-num">Ringan</th>
                                            <th class="col-num">Sedang</th>
                                            <th class="col-num">Berat</th>
                                            <th class="col-total">Jml</th>
                                            <th class="col-action"></th>
                                        </tr>
                                    </thead>
                                    <tbody data-tbody="sarana">
                                        @foreach($formData['saranaPendidikan'] as $i => $row)
                                        <tr class="neo-table-row">
                                            <td class="col-label"><input type="text" name="saranaPendidikan[{{ $i }}][label]" value="{{ $row['label'] }}" class="neo-form-input" placeholder="Nama sarana/prasarana"></td>
                                            <td class="col-num"><input type="number" name="saranaPendidikan[{{ $i }}][baik]" value="{{ $row['baik'] ?? 0 }}" min="0" class="neo-form-input calc-input"></td>
                                            <td class="col-num"><input type="number" name="saranaPendidikan[{{ $i }}][ringan]" value="{{ $row['ringan'] ?? 0 }}" min="0" class="neo-form-input calc-input"></td>
                                            <td class="col-num"><input type="number" name="saranaPendidikan[{{ $i }}][sedang]" value="{{ $row['sedang'] ?? 0 }}" min="0" class="neo-form-input calc-input"></td>
                                            <td class="col-num"><input type="number" name="saranaPendidikan[{{ $i }}][berat]" value="{{ $row['berat'] ?? 0 }}" min="0" class="neo-form-input calc-input"></td>
                                            <td class="col-total neo-table-cell-mono neo-table-total highlight" data-row-total>{{ ($row['baik'] ?? 0) + ($row['ringan'] ?? 0) + ($row['sedang'] ?? 0) + ($row['berat'] ?? 0) }}</td>
                                            <td class="col-action"><button type="button" onclick="removeRow(this)" class="neo-btn-remove"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 18L18 6M6 6l12 12"/></svg></button></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr class="neo-table-footer">
                                            <td class="col-label neo-table-cell-primary neo-table-total">TOTAL</td>
                                            <td class="col-num neo-table-cell-mono neo-table-total highlight" data-col-total="baik">{{ array_sum(array_column($formData['saranaPendidikan'], 'baik')) }}</td>
                                            <td class="col-num neo-table-cell-mono neo-table-total highlight" data-col-total="ringan">{{ array_sum(array_column($formData['saranaPendidikan'], 'ringan')) }}</td>
                                            <td class="col-num neo-table-cell-mono neo-table-total highlight" data-col-total="sedang">{{ array_sum(array_column($formData['saranaPendidikan'], 'sedang')) }}</td>
                                            <td class="col-num neo-table-cell-mono neo-table-total highlight" data-col-total="berat">{{ array_sum(array_column($formData['saranaPendidikan'], 'berat')) }}</td>
                                            <td class="col-total neo-table-cell-mono neo-table-total highlight" data-grand-total>0</td>
                                            <td class="col-action"></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Grid 2: Bantuan Pemerintah & Non Pemerintah -->
                <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mb-6">
                    <!-- Bantuan Pemerintah -->
                    <div class="neo-card table-card">
                        <div class="neo-card-header">
                            <div class="neo-card-icon" style="background: var(--gold); color: var(--night);">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div class="neo-card-text">
                                <h3 class="neo-card-title">Jenis Bantuan dari Pemerintah</h3>
                                <p class="neo-card-desc">Bantuan dana dan sarana</p>
                            </div>
                            <button type="button" onclick="addRow(this, 'bantuanP')" class="neo-btn-add">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14m-7-7h14"/></svg>
                                Tambah
                            </button>
                        </div>
                        <div class="neo-card-body">
                            <div class="neo-table-wrapper table-responsive">
                                <table class="neo-table" data-table="bantuanP">
                                    <thead class="neo-table-header">
                                        <tr>
                                            <th class="col-label">Jenis Bantuan</th>
                                            <th class="col-num">Diterima</th>
                                            <th class="col-num">Terserap</th>
                                            <th class="col-total">Saldo</th>
                                            <th class="col-action"></th>
                                        </tr>
                                    </thead>
                                    <tbody data-tbody="bantuanP">
                                        @php $totDiterima = 0; $totTerserap = 0; @endphp
                                        @foreach($formData['bantuanPemerintah'] as $i => $row)
                                        @php $totDiterima += $row['diterima'] ?? 0; $totTerserap += $row['terserap'] ?? 0; @endphp
                                        <tr class="neo-table-row">
                                            <td class="col-label"><input type="text" name="bantuanPemerintah[{{ $i }}][label]" value="{{ $row['label'] }}" class="neo-form-input" placeholder="Nama bantuan"></td>
                                            <td class="col-num"><input type="number" name="bantuanPemerintah[{{ $i }}][diterima]" value="{{ $row['diterima'] ?? 0 }}" min="0" class="neo-form-input calc-input"></td>
                                            <td class="col-num"><input type="number" name="bantuanPemerintah[{{ $i }}][terserap]" value="{{ $row['terserap'] ?? 0 }}" min="0" class="neo-form-input calc-input"></td>
                                            <td class="col-total neo-table-cell-mono neo-table-total highlight" data-row-total>{{ max(0, ($row['diterima'] ?? 0) - ($row['terserap'] ?? 0)) }}</td>
                                            <td class="col-action"><button type="button" onclick="removeRow(this)" class="neo-btn-remove"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 18L18 6M6 6l12 12"/></svg></button></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr class="neo-table-footer">
                                            <td class="col-label neo-table-cell-primary neo-table-total">TOTAL</td>
                                            <td class="col-num neo-table-cell-mono neo-table-total highlight" data-col-total="diterima">{{ $totDiterima }}</td>
                                            <td class="col-num neo-table-cell-mono neo-table-total highlight" data-col-total="terserap">{{ $totTerserap }}</td>
                                            <td class="col-total neo-table-cell-mono neo-table-total highlight" data-grand-total>{{ max(0, $totDiterima - $totTerserap) }}</td>
                                            <td class="col-action"></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Bantuan Non Pemerintah -->
                    <div class="neo-card table-card">
                        <div class="neo-card-header">
                            <div class="neo-card-icon" style="background: var(--gold); color: var(--night);">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <div class="neo-card-text">
                                <h3 class="neo-card-title">Jenis Bantuan Non Pemerintah</h3>
                                <p class="neo-card-desc">Bantuan dari pihak lain</p>
                            </div>
                            <button type="button" onclick="addRow(this, 'bantuanNP')" class="neo-btn-add">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14m-7-7h14"/></svg>
                                Tambah
                            </button>
                        </div>
                        <div class="neo-card-body">
                            <div class="neo-table-wrapper table-responsive">
                                <table class="neo-table" data-table="bantuanNP">
                                    <thead class="neo-table-header">
                                        <tr>
                                            <th class="col-label">Jenis Bantuan</th>
                                            <th class="col-num">Diterima</th>
                                            <th class="col-num">Terserap</th>
                                            <th class="col-total">Saldo</th>
                                            <th class="col-action"></th>
                                        </tr>
                                    </thead>
                                    <tbody data-tbody="bantuanNP">
                                        @php $totDiterima2 = 0; $totTerserap2 = 0; @endphp
                                        @foreach($formData['bantuanNonPemerintah'] as $i => $row)
                                        @php $totDiterima2 += $row['diterima'] ?? 0; $totTerserap2 += $row['terserap'] ?? 0; @endphp
                                        <tr class="neo-table-row">
                                            <td class="col-label"><input type="text" name="bantuanNonPemerintah[{{ $i }}][label]" value="{{ $row['label'] }}" class="neo-form-input" placeholder="Nama bantuan"></td>
                                            <td class="col-num"><input type="number" name="bantuanNonPemerintah[{{ $i }}][diterima]" value="{{ $row['diterima'] ?? 0 }}" min="0" class="neo-form-input calc-input"></td>
                                            <td class="col-num"><input type="number" name="bantuanNonPemerintah[{{ $i }}][terserap]" value="{{ $row['terserap'] ?? 0 }}" min="0" class="neo-form-input calc-input"></td>
                                            <td class="col-total neo-table-cell-mono neo-table-total highlight" data-row-total>{{ max(0, ($row['diterima'] ?? 0) - ($row['terserap'] ?? 0)) }}</td>
                                            <td class="col-action"><button type="button" onclick="removeRow(this)" class="neo-btn-remove"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 18L18 6M6 6l12 12"/></svg></button></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr class="neo-table-footer">
                                            <td class="col-label neo-table-cell-primary neo-table-total">TOTAL</td>
                                            <td class="col-num neo-table-cell-mono neo-table-total highlight" data-col-total="diterima">{{ $totDiterima2 }}</td>
                                            <td class="col-num neo-table-cell-mono neo-table-total highlight" data-col-total="terserap">{{ $totTerserap2 }}</td>
                                            <td class="col-total neo-table-cell-mono neo-table-total highlight" data-grand-total>{{ max(0, $totDiterima2 - $totTerserap2) }}</td>
                                            <td class="col-action"></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Grid 2: Data Guru/Pegawai & Tingkat Pendidikan -->
                <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mb-6">
                    <!-- Data Guru/Pegawai -->
                    <div class="neo-card table-card">
                        <div class="neo-card-header">
                            <div class="neo-card-icon" style="background: var(--gold); color: var(--night);">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <div class="neo-card-text">
                                <h3 class="neo-card-title">Data Guru / Pegawai</h3>
                                <p class="neo-card-desc">Jumlah SDM berdasarkan gender</p>
                            </div>
                            <button type="button" onclick="addRow(this, 'guru')" class="neo-btn-add">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14m-7-7h14"/></svg>
                                Tambah
                            </button>
                        </div>
                        <div class="neo-card-body">
                            <div class="neo-table-wrapper table-responsive">
                                <table class="neo-table" data-table="guru">
                                    <thead class="neo-table-header">
                                        <tr>
                                            <th class="col-label">Uraian</th>
                                            <th class="col-gender">L</th>
                                            <th class="col-gender">P</th>
                                            <th class="col-total">Jml</th>
                                            <th class="col-action"></th>
                                        </tr>
                                    </thead>
                                    <tbody data-tbody="guru">
                                        @php $totL = 0; $totP = 0; @endphp
                                        @foreach($formData['dataGuruPegawai'] as $i => $row)
                                        @php $totL += $row['l'] ?? 0; $totP += $row['p'] ?? 0; @endphp
                                        <tr class="neo-table-row">
                                            <td class="col-label"><input type="text" name="dataGuruPegawai[{{ $i }}][label]" value="{{ $row['label'] }}" class="neo-form-input" placeholder="Nama uraian"></td>
                                            <td class="col-gender"><input type="number" name="dataGuruPegawai[{{ $i }}][l]" value="{{ $row['l'] ?? 0 }}" min="0" class="neo-form-input calc-input"></td>
                                            <td class="col-gender"><input type="number" name="dataGuruPegawai[{{ $i }}][p]" value="{{ $row['p'] ?? 0 }}" min="0" class="neo-form-input calc-input"></td>
                                            <td class="col-total neo-table-cell-mono neo-table-total highlight" data-row-total>{{ ($row['l'] ?? 0) + ($row['p'] ?? 0) }}</td>
                                            <td class="col-action"><button type="button" onclick="removeRow(this)" class="neo-btn-remove"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 18L18 6M6 6l12 12"/></svg></button></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr class="neo-table-footer">
                                            <td class="col-label neo-table-cell-primary neo-table-total">TOTAL</td>
                                            <td class="col-gender neo-table-cell-mono neo-table-total highlight" data-col-total="l">{{ $totL }}</td>
                                            <td class="col-gender neo-table-cell-mono neo-table-total highlight" data-col-total="p">{{ $totP }}</td>
                                            <td class="col-total neo-table-cell-mono neo-table-total highlight" data-grand-total>{{ $totL + $totP }}</td>
                                            <td class="col-action"></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Tingkat Pendidikan -->
                    <div class="neo-card table-card">
                        <div class="neo-card-header">
                            <div class="neo-card-icon" style="background: var(--gold); color: var(--night);">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            </div>
                            <div class="neo-card-text">
                                <h3 class="neo-card-title">Tingkat Pendidikan</h3>
                                <p class="neo-card-desc">Jumlah siswa per tingkat</p>
                            </div>
                            <button type="button" onclick="addRow(this, 'tingkat')" class="neo-btn-add">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14m-7-7h14"/></svg>
                                Tambah
                            </button>
                        </div>
                        <div class="neo-card-body">
                            <div class="neo-table-wrapper table-responsive">
                                <table class="neo-table" data-table="tingkat">
                                    <thead class="neo-table-header">
                                        <tr>
                                            <th class="col-label">Tingkat</th>
                                            <th class="col-gender">L</th>
                                            <th class="col-gender">P</th>
                                            <th class="col-total">Jml</th>
                                            <th class="col-action"></th>
                                        </tr>
                                    </thead>
                                    <tbody data-tbody="tingkat">
                                        @php $totLTp = 0; $totPTp = 0; @endphp
                                        @foreach($formData['tingkatPendidikan'] as $i => $row)
                                        @php $totLTp += $row['l'] ?? 0; $totPTp += $row['p'] ?? 0; @endphp
                                        <tr class="neo-table-row">
                                            <td class="col-label"><input type="text" name="tingkatPendidikan[{{ $i }}][label]" value="{{ $row['label'] }}" class="neo-form-input" placeholder="Nama tingkat"></td>
                                            <td class="col-gender"><input type="number" name="tingkatPendidikan[{{ $i }}][l]" value="{{ $row['l'] ?? 0 }}" min="0" class="neo-form-input calc-input"></td>
                                            <td class="col-gender"><input type="number" name="tingkatPendidikan[{{ $i }}][p]" value="{{ $row['p'] ?? 0 }}" min="0" class="neo-form-input calc-input"></td>
                                            <td class="col-total neo-table-cell-mono neo-table-total highlight" data-row-total>{{ ($row['l'] ?? 0) + ($row['p'] ?? 0) }}</td>
                                            <td class="col-action"><button type="button" onclick="removeRow(this)" class="neo-btn-remove"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 18L18 6M6 6l12 12"/></svg></button></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr class="neo-table-footer">
                                            <td class="col-label neo-table-cell-primary neo-table-total">TOTAL</td>
                                            <td class="col-gender neo-table-cell-mono neo-table-total highlight" data-col-total="l">{{ $totLTp }}</td>
                                            <td class="col-gender neo-table-cell-mono neo-table-total highlight" data-col-total="p">{{ $totPTp }}</td>
                                            <td class="col-total neo-table-cell-mono neo-table-total highlight" data-grand-total>{{ $totLTp + $totPTp }}</td>
                                            <td class="col-action"></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Grid 2: Sertifikasi & Absensi -->
                <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mb-6">
                    <!-- Sertifikasi -->
                    <div class="neo-card table-card">
                        <div class="neo-card-header">
                            <div class="neo-card-icon" style="background: var(--gold); color: var(--night);">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                            </div>
                            <div class="neo-card-text">
                                <h3 class="neo-card-title">Sertifikasi</h3>
                                <p class="neo-card-desc">Status sertifikasi guru</p>
                            </div>
                            <button type="button" onclick="addRow(this, 'sertifikasi')" class="neo-btn-add">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14m-7-7h14"/></svg>
                                Tambah
                            </button>
                        </div>
                        <div class="neo-card-body">
                            <div class="neo-table-wrapper table-responsive">
                                <table class="neo-table" data-table="sertifikasi">
                                    <thead class="neo-table-header">
                                        <tr>
                                            <th class="col-label">Kategori</th>
                                            <th class="col-gender">L</th>
                                            <th class="col-gender">P</th>
                                            <th class="col-total">Jml</th>
                                            <th class="col-action"></th>
                                        </tr>
                                    </thead>
                                    <tbody data-tbody="sertifikasi">
                                        @php $totLSert = 0; $totPSert = 0; @endphp
                                        @foreach($formData['sertifikasi'] as $i => $row)
                                        @php $totLSert += $row['l'] ?? 0; $totPSert += $row['p'] ?? 0; @endphp
                                        <tr class="neo-table-row">
                                            <td class="col-label"><input type="text" name="sertifikasi[{{ $i }}][label]" value="{{ $row['label'] }}" class="neo-form-input" placeholder="Nama kategori"></td>
                                            <td class="col-gender"><input type="number" name="sertifikasi[{{ $i }}][l]" value="{{ $row['l'] ?? 0 }}" min="0" class="neo-form-input calc-input"></td>
                                            <td class="col-gender"><input type="number" name="sertifikasi[{{ $i }}][p]" value="{{ $row['p'] ?? 0 }}" min="0" class="neo-form-input calc-input"></td>
                                            <td class="col-total neo-table-cell-mono neo-table-total highlight" data-row-total>{{ ($row['l'] ?? 0) + ($row['p'] ?? 0) }}</td>
                                            <td class="col-action"><button type="button" onclick="removeRow(this)" class="neo-btn-remove"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 18L18 6M6 6l12 12"/></svg></button></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr class="neo-table-footer">
                                            <td class="col-label neo-table-cell-primary neo-table-total">TOTAL</td>
                                            <td class="col-gender neo-table-cell-mono neo-table-total highlight" data-col-total="l">{{ $totLSert }}</td>
                                            <td class="col-gender neo-table-cell-mono neo-table-total highlight" data-col-total="p">{{ $totPSert }}</td>
                                            <td class="col-total neo-table-cell-mono neo-table-total highlight" data-grand-total>{{ $totLSert + $totPSert }}</td>
                                            <td class="col-action"></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Absensi Siswa -->
                    <div class="neo-card table-card">
                        <div class="neo-card-header">
                            <div class="neo-card-icon" style="background: var(--gold); color: var(--night);">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                            </div>
                            <div class="neo-card-text">
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
                                <label class="neo-field-label">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                    Banyak Hari Sekolah
                                </label>
                                <input type="number" name="banyakHariSekolah" value="{{ $formData['banyakHariSekolah'] ?? 0 }}" min="0" class="neo-form-input">
                            </div>
                            <div class="neo-table-wrapper table-responsive">
                                <table class="neo-table" data-table="absensi">
                                    <thead class="neo-table-header">
                                        <tr>
                                            <th class="col-label">Keterangan</th>
                                            <th class="col-gender">L</th>
                                            <th class="col-gender">P</th>
                                            <th class="col-total">Jml</th>
                                            <th class="col-action"></th>
                                        </tr>
                                    </thead>
                                    <tbody data-tbody="absensi">
                                        @php $totLAbs = 0; $totPAbs = 0; @endphp
                                        @foreach($formData['absensiSiswa'] as $i => $row)
                                        @php $totLAbs += $row['l'] ?? 0; $totPAbs += $row['p'] ?? 0; @endphp
                                        <tr class="neo-table-row">
                                            <td class="col-label"><input type="text" name="absensiSiswa[{{ $i }}][label]" value="{{ $row['label'] }}" class="neo-form-input" placeholder="Nama keterangan"></td>
                                            <td class="col-gender"><input type="number" name="absensiSiswa[{{ $i }}][l]" value="{{ $row['l'] ?? 0 }}" min="0" class="neo-form-input calc-input"></td>
                                            <td class="col-gender"><input type="number" name="absensiSiswa[{{ $i }}][p]" value="{{ $row['p'] ?? 0 }}" min="0" class="neo-form-input calc-input"></td>
                                            <td class="col-total neo-table-cell-mono neo-table-total highlight" data-row-total>{{ ($row['l'] ?? 0) + ($row['p'] ?? 0) }}</td>
                                            <td class="col-action"><button type="button" onclick="removeRow(this)" class="neo-btn-remove"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 18L18 6M6 6l12 12"/></svg></button></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr class="neo-table-footer">
                                            <td class="col-label neo-table-cell-primary neo-table-total">TOTAL</td>
                                            <td class="col-gender neo-table-cell-mono neo-table-total highlight" data-col-total="l">{{ $totLAbs }}</td>
                                            <td class="col-gender neo-table-cell-mono neo-table-total highlight" data-col-total="p">{{ $totPAbs }}</td>
                                            <td class="col-total neo-table-cell-mono neo-table-total highlight" data-grand-total>{{ $totLAbs + $totPAbs }}</td>
                                            <td class="col-action"></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tanah & Sertifikat -->
                <div class="neo-card tanah-card">
                    <div class="neo-card-header">
                        <div class="neo-card-icon" style="background: var(--gold); color: var(--night);">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <div class="neo-card-text">
                            <h3 class="neo-card-title">Tanah & Sertifikat Tanah</h3>
                            <p class="neo-card-desc">Informasi kepemilikan tanah</p>
                        </div>
                    </div>
                    <div class="neo-card-body">
                        <div class="tanah-grid">
                            @foreach($formData['luasTanah'] as $key => $value)
                            <div class="tanah-field">
                                <label class="neo-field-label">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                                    {{ ucwords(str_replace('_', ' ', $key)) }}
                                </label>
                                <div class="input-with-unit">
                                    <input type="number" name="luasTanah[{{ $key }}]" value="{{ $value ?? 0 }}" min="0" class="neo-form-input">
                                    <span class="input-unit">m²</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="sertifikat-grid">
                            <div class="neo-field-group">
                                <label class="neo-field-label">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                    Status Kepemilikan
                                </label>
                                <input type="text" name="sertifikatTanah[statusKepemilikan]" value="{{ $formData['sertifikatTanah']['statusKepemilikan'] ?? '' }}" placeholder="Contoh: Milik Sendiri" class="neo-form-input">
                            </div>
                            <div class="neo-field-group">
                                <label class="neo-field-label">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    Nomor Sertifikat
                                </label>
                                <input type="text" name="sertifikatTanah[nomor]" value="{{ $formData['sertifikatTanah']['nomor'] ?? '' }}" placeholder="Nomor sertifikat" class="neo-form-input">
                            </div>
                            <div class="neo-field-group">
                                <label class="neo-field-label">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                    Tanggal Sertifikat
                                </label>
                                <input type="date" name="sertifikatTanah[tanggal]" value="{{ $formData['sertifikatTanah']['tanggal'] ?? '' }}" class="neo-form-input">
                            </div>
                            <div class="neo-field-group">
                                <label class="neo-field-label">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg>
                                    Luas Tanah Sertifikat
                                </label>
                                <div class="input-with-unit">
                                    <input type="number" name="sertifikatTanah[luas]" value="{{ $formData['sertifikatTanah']['luas'] ?? 0 }}" min="0" class="neo-form-input">
                                    <span class="input-unit">m²</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bottom Action Buttons -->
                <div class="bottom-actions">
                    <button type="submit" name="action" value="draft" class="btn-action-save">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                        Simpan Draft
                    </button>
                    <button type="submit" name="action" value="submit" class="btn-action-primary">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        Kirim Laporan
                    </button>
                </div>

                </form>
            </div>
            </div>
        </section>

        <!-- Page Styles -->
        <style>
            /* Madrasah Full Width Layout */
            .madrasah-fullwidth .page-content {
                padding: 0;
                max-width: none;
            }

            .madrasah-fullwidth .page-content-expanded {
                padding: 0;
            }

            .madrasah-fullwidth .content-inner {
                padding: 2rem;
                max-width: 100%;
                margin: 0 auto;
            }

            /* Large Tabs Navigation */
            .neo-tabs-large {
                display: flex;
                gap: 0;
                padding: 1rem 2rem;
                background: var(--paper);
                border-bottom: 2px solid var(--line);
                justify-content: center;
                flex-wrap: wrap;
                margin-bottom: 0;
            }

            .neo-tabs-large .neo-tab {
                display: flex;
                align-items: center;
                gap: 0.75rem;
                padding: 1rem 1.5rem;
                font-family: var(--font-display);
                font-size: 0.95rem;
                font-weight: 600;
                color: var(--ink-soft);
                border-radius: 0.5rem;
                margin: 0 0.25rem;
                transition: all 200ms var(--ease);
                text-decoration: none;
            }

            .neo-tabs-large .neo-tab:hover {
                color: var(--ink);
                background: var(--paper-soft);
            }

            .neo-tabs-large .neo-tab.is-active {
                color: var(--gold);
                background: oklch(68% 0.145 74 / 0.1);
            }

            .neo-tabs-large .neo-tab svg {
                flex-shrink: 0;
            }

            .neo-tabs-large .neo-tab span {
                white-space: nowrap;
            }

            /* Space between tabs and content */
            .neo-tabs-large {
                margin-bottom: 1.5rem;
            }

            /* Filter Row - Semester, Tahun Ajaran, Status */
            .filter-row {
                display: flex;
                gap: 1rem;
                align-items: flex-end;
                flex-wrap: wrap;
                margin-bottom: 1.5rem;
                padding-bottom: 1.5rem;
                border-bottom: 1px solid var(--line);
            }

            .filter-item {
                flex: 1;
                min-width: 160px;
            }

            .filter-label {
                display: flex;
                align-items: center;
                gap: 0.5rem;
                font-family: var(--font-display);
                font-size: 0.8rem;
                font-weight: 600;
                color: var(--ink);
                margin-bottom: 0.5rem;
            }

            .filter-label svg {
                color: var(--gold);
                opacity: 0.8;
            }

            .filter-select {
                width: 100%;
                padding: 0.75rem 1rem;
                padding-right: 2.5rem;
                background: var(--paper);
                border: 1px solid var(--line);
                border-radius: 0.5rem;
                font-family: var(--font-mono);
                font-size: 0.85rem;
                color: var(--ink);
                cursor: pointer;
                appearance: none;
                background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%23666' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
                background-repeat: no-repeat;
                background-position: right 0.75rem center;
                transition: border-color 180ms, box-shadow 180ms;
            }

            .filter-select:focus {
                outline: none;
                border-color: var(--gold);
                box-shadow: 0 0 0 3px oklch(68% 0.145 74 / 0.15);
            }

            .status-display {
                display: flex;
                align-items: center;
            }

            .status-pill {
                display: inline-flex;
                align-items: center;
                padding: 0.75rem 1.25rem;
                border-radius: 0.5rem;
                font-family: var(--font-mono);
                font-size: 0.85rem;
                font-weight: 600;
            }

            .status-pill.status-draft {
                background: var(--ink-soft);
                color: var(--paper);
            }

            .status-pill.status-submitted,
            .status-pill.status-pending {
                background: var(--info);
                color: white;
            }

            .status-pill.status-approved {
                background: var(--success);
                color: white;
            }

            .status-pill.status-rejected {
                background: var(--danger);
                color: white;
            }

            /* Submit Row */
            .submit-row {
                display: flex;
                justify-content: space-between;
                align-items: center;
                flex-wrap: wrap;
                gap: 1rem;
            }

            .submit-info {
                display: flex;
                align-items: center;
                gap: 0.5rem;
                color: var(--ink-soft);
                font-size: 0.85rem;
            }

            .submit-info svg {
                flex-shrink: 0;
            }

            .submit-actions {
                display: flex;
                gap: 0.75rem;
            }

            /* Action Buttons */
            .btn-action-save {
                display: inline-flex;
                align-items: center;
                gap: 0.75rem;
                padding: 0.875rem 1.5rem;
                background: var(--gold);
                color: var(--night);
                font-family: var(--font-mono);
                font-size: 0.8rem;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                border: none;
                border-radius: 0.5rem;
                cursor: pointer;
                transition: all 200ms var(--ease);
            }

            .btn-action-save:hover {
                background: var(--gold-dark);
                transform: translateY(-2px);
                box-shadow: 0 8px 24px oklch(50% 0.15 50 / 0.25);
            }

            .btn-action-primary {
                display: inline-flex;
                align-items: center;
                gap: 0.75rem;
                padding: 0.875rem 1.5rem;
                background: var(--ink);
                color: var(--paper);
                font-family: var(--font-mono);
                font-size: 0.8rem;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                border: none;
                border-radius: 0.5rem;
                cursor: pointer;
                transition: all 200ms var(--ease);
            }

            .btn-action-primary:hover {
                background: var(--night);
                transform: translateY(-2px);
                box-shadow: 0 8px 24px oklch(18% 0.03 76 / 0.25);
            }

            /* Bottom Action Buttons */
            .bottom-actions {
                display: flex;
                justify-content: center;
                gap: 1rem;
                padding: 2rem 0;
                margin-top: 1rem;
                border-top: 1px solid var(--line);
                flex-wrap: wrap;
            }

            /* Table Column Widths - Make columns user-friendly */
            .neo-table .col-label {
                min-width: 180px;
                width: 30%;
            }

            .neo-table .col-num {
                min-width: 80px;
                width: 10%;
                text-align: center;
            }

            .neo-table .col-gender {
                min-width: 70px;
                width: 8%;
                text-align: center;
            }

            .neo-table .col-total {
                min-width: 80px;
                width: 12%;
                text-align: center;
                background: oklch(68% 0.145 74 / 0.08);
            }

            .neo-table .col-action {
                min-width: 50px;
                width: 5%;
                text-align: center;
            }

            /* Table Input Improvements - Make inputs larger and easier to fill */
            .neo-table td.col-label input[type="text"] {
                width: 100%;
                padding: 0.75rem;
                border: 1px solid var(--line);
                border-radius: 0.4rem;
                font-size: 0.9rem;
                background: var(--paper);
                transition: border-color 180ms, box-shadow 180ms;
            }

            .neo-table td.col-label input[type="text"]:focus {
                outline: none;
                border-color: var(--gold);
                box-shadow: 0 0 0 3px oklch(68% 0.145 74 / 0.15);
            }

            .neo-table td.col-num input[type="number"],
            .neo-table td.col-gender input[type="number"] {
                width: 100%;
                padding: 0.75rem 0.5rem;
                border: 1px solid var(--line);
                border-radius: 0.4rem;
                font-family: var(--font-mono);
                font-size: 0.95rem;
                font-weight: 600;
                text-align: center;
                background: var(--paper);
                transition: border-color 180ms, box-shadow 180ms;
            }

            .neo-table td.col-num input[type="number"]:focus,
            .neo-table td.col-gender input[type="number"]:focus {
                outline: none;
                border-color: var(--gold);
                box-shadow: 0 0 0 3px oklch(68% 0.145 74 / 0.15);
            }

            /* Table cell total styling */
            .neo-table td[data-row-total] {
                font-family: var(--font-mono);
                font-weight: 700;
                font-size: 0.95rem;
                color: var(--ink);
                text-align: center;
                padding: 0.75rem;
                background: oklch(68% 0.145 74 / 0.05);
                border-radius: 0.4rem;
            }

            /* Table Responsive */
            .table-responsive {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            .table-responsive .neo-table {
                min-width: 700px;
            }

            /* Responsive */
            @media (max-width: 768px) {
                /* Large Tabs - Responsive */
                .neo-tabs-large {
                    padding: 0.5rem 1rem;
                    overflow-x: auto;
                    -webkit-overflow-scrolling: touch;
                }

                .neo-tabs-large .neo-tab {
                    padding: 0.75rem 1rem;
                    font-size: 0.85rem;
                    gap: 0.5rem;
                }

                .neo-tabs-large .neo-tab svg {
                    width: 20px;
                    height: 20px;
                }

                .madrasah-fullwidth .content-inner {
                    padding: 1rem 0.5rem;
                }

                .filter-row {
                    flex-direction: column;
                }

                .filter-item {
                    width: 100%;
                }

                .submit-row {
                    flex-direction: column;
                    align-items: stretch;
                }

                .submit-info {
                    justify-content: center;
                }

                .submit-actions {
                    flex-direction: column;
                }

                .submit-actions button {
                    width: 100%;
                    justify-content: center;
                }

                .bottom-actions {
                    flex-direction: column;
                    padding: 1.5rem 0;
                }

                .bottom-actions button {
                    width: 100%;
                    justify-content: center;
                }

                .neo-table {
                    display: block;
                    overflow-x: auto;
                }

                .neo-table .col-label {
                    min-width: 150px;
                }
            }

            @media (max-width: 480px) {
                .btn-action-save,
                .btn-action-primary {
                    padding: 0.75rem 1rem;
                    font-size: 0.75rem;
                }

                .bottom-actions button {
                    padding: 0.875rem 1.25rem;
                }
            }
        </style>

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
                gedung: `<td class="col-label"><input type="text" name="keadaanGedung[__INDEX__][label]" class="neo-form-input" placeholder="Nama gedung/bangunan"></td>
                         <td class="col-num"><input type="number" name="keadaanGedung[__INDEX__][baik]" value="0" min="0" class="neo-form-input calc-input"></td>
                         <td class="col-num"><input type="number" name="keadaanGedung[__INDEX__][ringan]" value="0" min="0" class="neo-form-input calc-input"></td>
                         <td class="col-num"><input type="number" name="keadaanGedung[__INDEX__][sedang]" value="0" min="0" class="neo-form-input calc-input"></td>
                         <td class="col-num"><input type="number" name="keadaanGedung[__INDEX__][berat]" value="0" min="0" class="neo-form-input calc-input"></td>
                         <td class="col-total neo-table-cell-mono neo-table-total highlight" data-row-total>0</td>
                         <td class="col-action"><button type="button" onclick="removeRow(this)" class="neo-btn-remove"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 18L18 6M6 6l12 12"/></svg></button></td>`,
                sarana: `<td class="col-label"><input type="text" name="saranaPendidikan[__INDEX__][label]" class="neo-form-input" placeholder="Nama sarana/prasarana"></td>
                         <td class="col-num"><input type="number" name="saranaPendidikan[__INDEX__][baik]" value="0" min="0" class="neo-form-input calc-input"></td>
                         <td class="col-num"><input type="number" name="saranaPendidikan[__INDEX__][ringan]" value="0" min="0" class="neo-form-input calc-input"></td>
                         <td class="col-num"><input type="number" name="saranaPendidikan[__INDEX__][sedang]" value="0" min="0" class="neo-form-input calc-input"></td>
                         <td class="col-num"><input type="number" name="saranaPendidikan[__INDEX__][berat]" value="0" min="0" class="neo-form-input calc-input"></td>
                         <td class="col-total neo-table-cell-mono neo-table-total highlight" data-row-total>0</td>
                         <td class="col-action"><button type="button" onclick="removeRow(this)" class="neo-btn-remove"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 18L18 6M6 6l12 12"/></svg></button></td>`,
                bantuanP: `<td class="col-label"><input type="text" name="bantuanPemerintah[__INDEX__][label]" class="neo-form-input" placeholder="Nama bantuan"></td>
                           <td class="col-num"><input type="number" name="bantuanPemerintah[__INDEX__][diterima]" value="0" min="0" class="neo-form-input calc-input"></td>
                           <td class="col-num"><input type="number" name="bantuanPemerintah[__INDEX__][terserap]" value="0" min="0" class="neo-form-input calc-input"></td>
                           <td class="col-total neo-table-cell-mono neo-table-total highlight" data-row-total>0</td>
                           <td class="col-action"><button type="button" onclick="removeRow(this)" class="neo-btn-remove"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 18L18 6M6 6l12 12"/></svg></button></td>`,
                bantuanNP: `<td class="col-label"><input type="text" name="bantuanNonPemerintah[__INDEX__][label]" class="neo-form-input" placeholder="Nama bantuan"></td>
                            <td class="col-num"><input type="number" name="bantuanNonPemerintah[__INDEX__][diterima]" value="0" min="0" class="neo-form-input calc-input"></td>
                            <td class="col-num"><input type="number" name="bantuanNonPemerintah[__INDEX__][terserap]" value="0" min="0" class="neo-form-input calc-input"></td>
                            <td class="col-total neo-table-cell-mono neo-table-total highlight" data-row-total>0</td>
                            <td class="col-action"><button type="button" onclick="removeRow(this)" class="neo-btn-remove"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 18L18 6M6 6l12 12"/></svg></button></td>`,
                guru: `<td class="col-label"><input type="text" name="dataGuruPegawai[__INDEX__][label]" class="neo-form-input" placeholder="Nama uraian"></td>
                       <td class="col-gender"><input type="number" name="dataGuruPegawai[__INDEX__][l]" value="0" min="0" class="neo-form-input calc-input"></td>
                       <td class="col-gender"><input type="number" name="dataGuruPegawai[__INDEX__][p]" value="0" min="0" class="neo-form-input calc-input"></td>
                       <td class="col-total neo-table-cell-mono neo-table-total highlight" data-row-total>0</td>
                       <td class="col-action"><button type="button" onclick="removeRow(this)" class="neo-btn-remove"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 18L18 6M6 6l12 12"/></svg></button></td>`,
                tingkat: `<td class="col-label"><input type="text" name="tingkatPendidikan[__INDEX__][label]" class="neo-form-input" placeholder="Nama tingkat"></td>
                          <td class="col-gender"><input type="number" name="tingkatPendidikan[__INDEX__][l]" value="0" min="0" class="neo-form-input calc-input"></td>
                          <td class="col-gender"><input type="number" name="tingkatPendidikan[__INDEX__][p]" value="0" min="0" class="neo-form-input calc-input"></td>
                          <td class="col-total neo-table-cell-mono neo-table-total highlight" data-row-total>0</td>
                          <td class="col-action"><button type="button" onclick="removeRow(this)" class="neo-btn-remove"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 18L18 6M6 6l12 12"/></svg></button></td>`,
                sertifikasi: `<td class="col-label"><input type="text" name="sertifikasi[__INDEX__][label]" class="neo-form-input" placeholder="Nama kategori"></td>
                             <td class="col-gender"><input type="number" name="sertifikasi[__INDEX__][l]" value="0" min="0" class="neo-form-input calc-input"></td>
                             <td class="col-gender"><input type="number" name="sertifikasi[__INDEX__][p]" value="0" min="0" class="neo-form-input calc-input"></td>
                             <td class="col-total neo-table-cell-mono neo-table-total highlight" data-row-total>0</td>
                             <td class="col-action"><button type="button" onclick="removeRow(this)" class="neo-btn-remove"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 18L18 6M6 6l12 12"/></svg></button></td>`,
                absensi: `<td class="col-label"><input type="text" name="absensiSiswa[__INDEX__][label]" class="neo-form-input" placeholder="Nama keterangan"></td>
                          <td class="col-gender"><input type="number" name="absensiSiswa[__INDEX__][l]" value="0" min="0" class="neo-form-input calc-input"></td>
                          <td class="col-gender"><input type="number" name="absensiSiswa[__INDEX__][p]" value="0" min="0" class="neo-form-input calc-input"></td>
                          <td class="col-total neo-table-cell-mono neo-table-total highlight" data-row-total>0</td>
                          <td class="col-action"><button type="button" onclick="removeRow(this)" class="neo-btn-remove"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 18L18 6M6 6l12 12"/></svg></button></td>`
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