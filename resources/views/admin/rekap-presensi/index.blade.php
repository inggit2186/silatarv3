@extends('admin.layouts.app')

@section('title', 'Rekap Presensi')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold" style="color: var(--text-primary)">
                Rekap Presensi
            </h1>
            <p class="mt-2 text-sm" style="color: var(--text-secondary)">
                Generate file Excel rekap presensi berdasarkan unit kerja atau kategori bank
            </p>
        </div>

        <!-- Flash messages container (AJAX) -->
        <div id="flashContainer"></div>

        <!-- Alert Messages -->
        @if(session('success'))
            <div class="mb-6 p-4" style="background: var(--success-bg); border: 1px solid var(--success); border-radius: var(--radius)">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="w-5 h-5" style="color: var(--success)" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <p class="ml-3 text-sm" style="color: var(--success)">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4" style="background: var(--danger-bg); border: 1px solid var(--danger); border-radius: var(--radius)">
                <div class="flex items-center">
                    <svg class="w-5 h-5" style="color: var(--danger)" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <p class="ml-3 text-sm" style="color: var(--danger)">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <div style="display: grid; grid-template-columns: 380px 1fr; gap: 24px; align-items: start;">

            <!-- LEFT: Generate Form + Info -->
            <div style="display: flex; flex-direction: column; gap: 24px;">
                <!-- Form Card -->
                <div class="card" style="padding: 24px;" x-data="rekapForm()">
                    <h2 style="font-size: 16px; font-weight: 600; color: var(--text-primary); margin-bottom: 16px;">
                        Generate Rekap Presensi
                    </h2>

                    <form action="{{ route('admin.rekap-presensi.generate') }}" method="POST" id="rekapForm">
                        @csrf

                        <div style="display: flex; flex-direction: column; gap: 14px;">
                            <!-- Method Selection -->
                            <div>
                                <label style="display: block; font-size: 13px; font-weight: 500; color: var(--text-secondary); margin-bottom: 6px;">
                                    Generate Berdasarkan *
                                </label>
                                <select
                                    name="method"
                                    x-model="method"
                                    style="width: 100%; padding: 8px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px; color: var(--text-primary); background: var(--card);"
                                    required
                                >
                                    <option value="unit_kerja">Unit Kerja</option>
                                    <option value="kategori_bank">Kategori Bank</option>
                                </select>
                            </div>

                            <!-- Unit Kerja Dropdown -->
                            <div x-show="method === 'unit_kerja'" x-transition>
                                <label style="display: block; font-size: 13px; font-weight: 500; color: var(--text-secondary); margin-bottom: 6px;">
                                    Unit Kerja *
                                </label>
                                <select
                                    name="dept_id"
                                    :required="method === 'unit_kerja'"
                                    style="width: 100%; padding: 8px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px; color: var(--text-primary); background: var(--card);"
                                >
                                    <option value="">-- Pilih Unit Kerja --</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}" {{ old('dept_id') == $dept->id ? 'selected' : '' }}>
                                            {{ $dept->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('dept_id')
                                    <p style="color: var(--danger); font-size: 12px; margin-top: 4px;">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Kategori Bank Dropdown -->
                            <div x-show="method === 'kategori_bank'" x-transition>
                                <label style="display: block; font-size: 13px; font-weight: 500; color: var(--text-secondary); margin-bottom: 6px;">
                                    Kelompok *
                                </label>
                                <select
                                    name="group_key"
                                    x-model="selectedGroup"
                                    :required="method === 'kategori_bank'"
                                    style="width: 100%; padding: 8px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px; color: var(--text-primary); background: var(--card);"
                                >
                                    <option value="">-- Pilih Kelompok --</option>
                                    @foreach($bankKategoriGroups as $group)
                                        <option value="{{ $group['group_key'] }}" {{ old('group_key') == $group['group_key'] ? 'selected' : '' }}>
                                            {{ $group['label'] }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('group_key')
                                    <p style="color: var(--danger); font-size: 12px; margin-top: 4px;">{{ $message }}</p>
                                @enderror

                                <!-- Status/Deskripsi Kelompok -->
                                <div x-show="selectedGroup && selectedGroup !== ''" x-transition
                                     style="margin-top: 10px; padding: 10px; background: var(--info-bg); border: 1px solid var(--info); border-radius: var(--radius-sm); font-size: 12px; color: var(--info);">
                                    <div style="font-weight: 600; margin-bottom: 4px;">Data yang diambil:</div>
                                    <div style="display: flex; flex-direction: column; gap: 2px;">
                                        <div><span style="font-weight: 500;">Status:</span> <span x-text="groupInfo.status"></span></div>
                                        <div><span style="font-weight: 500;">Bank Kategori:</span> <span x-text="groupInfo.bank_kategori"></span></div>
                                        <div x-show="groupInfo.serdik"><span style="font-weight: 500;">Sertifikasi:</span> <span x-text="groupInfo.serdik"></span></div>
                                        <div x-show="groupInfo.total"><span style="font-weight: 500;">Jumlah User:</span> <span x-text="groupInfo.total"></span></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Month & Year -->
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                                <div>
                                    <label style="display: block; font-size: 13px; font-weight: 500; color: var(--text-secondary); margin-bottom: 6px;">
                                        Bulan *
                                    </label>
                                    <select
                                        name="month"
                                        style="width: 100%; padding: 8px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px; color: var(--text-primary); background: var(--card);"
                                        required
                                    >
                                        @for($m = 1; $m <= 12; $m++)
                                            <option value="{{ $m }}" {{ $m == $currentMonth ? 'selected' : '' }}>
                                                {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>

                                <div>
                                    <label style="display: block; font-size: 13px; font-weight: 500; color: var(--text-secondary); margin-bottom: 6px;">
                                        Tahun *
                                    </label>
                                    <select
                                        name="year"
                                        style="width: 100%; padding: 8px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px; color: var(--text-primary); background: var(--card);"
                                        required
                                    >
                                        @for($y = $currentYear - 2; $y <= $currentYear + 1; $y++)
                                            <option value="{{ $y }}" {{ $y == $currentYear ? 'selected' : '' }}>
                                                {{ $y }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div style="margin-top: 18px;">
                            <button
                                type="submit"
                                id="generateBtn"
                                style="width: 100%; display: flex; justify-content: center; align-items: center; padding: 10px 16px; border: none; border-radius: var(--radius-sm); font-size: 13px; font-weight: 500; color: var(--text-inverse); background: var(--primary); cursor: pointer;"
                            >
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                                Generate Rekap Presensi
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Info Card -->
                <div class="card" style="padding: 16px; background: var(--info-bg); border: 1px solid var(--info);">
                    <h3 style="font-size: 14px; font-weight: 600; color: var(--info); margin-bottom: 8px;">
                        Informasi
                    </h3>
                    <div style="font-size: 12px; color: var(--info); line-height: 1.6;">
                        <p><strong>Metode:</strong></p>
                        <ul style="list-style-type: disc; margin-left: 16px; margin-top: 4px;">
                            <li><strong>Unit Kerja</strong> - Per departemen</li>
                            <li><strong>Kategori Bank</strong> - Per kelompok bank + status + sertifikasi</li>
                        </ul>
                        <p style="margin-top: 8px;"><strong>File:</strong></p>
                        <ul style="list-style-type: disc; margin-left: 16px; margin-top: 4px;">
                            <li><strong>Rekap</strong> - Absensi harian (value 1)</li>
                            <li><strong>Detail</strong> - Jam masuk / pulang</li>
                            <li><strong>Tukin</strong> - Tunjangan kinerja & potongan</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- RIGHT: History -->
            <div class="card" style="padding: 24px;">
                <h2 style="font-size: 16px; font-weight: 600; color: var(--text-primary); margin-bottom: 16px;">
                    Yang Sudah Di-generate
                </h2>

                <!-- Filter -->
                <form method="GET" action="{{ route('admin.rekap-presensi') }}" style="display: flex; gap: 10px; margin-bottom: 16px; align-items: flex-end;">
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 500; color: var(--text-secondary); margin-bottom: 4px;">Bulan</label>
                        <select name="filter_bulan" style="padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px; color: var(--text-primary); background: var(--card);">
                            <option value="">Semua</option>
                            @for($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}" {{ ($filterBulan ?? '') == $m ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 500; color: var(--text-secondary); margin-bottom: 4px;">Tahun</label>
                        <select name="filter_tahun" style="padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px; color: var(--text-primary); background: var(--card);">
                            <option value="">Semua</option>
                            @for($y = date('Y') + 1; $y >= date('Y') - 3; $y--)
                                <option value="{{ $y }}" {{ ($filterTahun ?? '') == $y ? 'selected' : '' }}>
                                    {{ $y }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <button type="submit" style="padding: 7px 14px; border: none; border-radius: var(--radius-sm); font-size: 13px; font-weight: 500; color: var(--text-inverse); background: var(--primary); cursor: pointer; white-space: nowrap;">
                        Filter
                    </button>
                    @if(($filterBulan ?? '') || ($filterTahun ?? ''))
                        <a href="{{ route('admin.rekap-presensi') }}" style="padding: 7px 14px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px; color: var(--text-secondary); text-decoration: none; white-space: nowrap;">
                            Reset
                        </a>
                    @endif
                </form>

                @if(count($generatedItems) > 0)
                    <!-- Table -->
                    <div style="overflow-x: auto;">
                        <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
                            <thead>
                                <tr style="border-bottom: 2px solid var(--border);">
                                    <th style="text-align: left; padding: 10px 8px; color: var(--text-secondary); font-weight: 600; font-size: 12px;">Kelompok</th>
                                    <th style="text-align: left; padding: 10px 8px; color: var(--text-secondary); font-weight: 600; font-size: 12px;">Periode</th>
                                    <th style="text-align: left; padding: 10px 8px; color: var(--text-secondary); font-weight: 600; font-size: 12px;">Generate Oleh</th>
                                    <th style="text-align: left; padding: 10px 8px; color: var(--text-secondary); font-weight: 600; font-size: 12px;">Waktu</th>
                                    <th style="text-align: center; padding: 10px 8px; color: var(--text-secondary); font-weight: 600; font-size: 12px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($generatedItems as $item)
                                    <tr style="border-bottom: 1px solid var(--border); {{ $loop->iteration % 2 === 0 ? 'background: var(--secondary);' : '' }}">
                                        <td style="padding: 10px 8px; color: var(--text-primary); max-width: 250px;">
                                            <div style="display: flex; align-items: center; gap: 8px;">
                                                @if($item['group_key'])
                                                    <div style="width: 28px; height: 28px; background: var(--primary-50); border-radius: var(--radius-sm); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                                        <svg style="width: 14px; height: 14px; color: var(--primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        </svg>
                                                    </div>
                                                @else
                                                    <div style="width: 28px; height: 28px; background: var(--primary-50); border-radius: var(--radius-sm); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                                        <svg style="width: 14px; height: 14px; color: var(--primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                                        </svg>
                                                    </div>
                                                @endif
                                                <span style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $item['label'] }}</span>
                                            </div>
                                        </td>
                                        <td style="padding: 10px 8px; color: var(--text-primary); white-space: nowrap;">
                                            {{ \Carbon\Carbon::create()->month($item['bulan'])->format('F') }} {{ $item['tahun'] }}
                                        </td>
                                        <td style="padding: 10px 8px; color: var(--text-secondary);">
                                            {{ $item['generated_by'] }}
                                        </td>
                                        <td style="padding: 10px 8px; color: var(--text-secondary); white-space: nowrap; font-size: 12px;">
                                            {{ \Carbon\Carbon::parse($item['updated_at'])->format('d M Y H:i') }}
                                        </td>
                                        <td style="padding: 10px 8px; text-align: center;">
                                            <div style="display: flex; gap: 6px; justify-content: center;">
                                                @if($item['group_key'])
                                                    <a href="{{ route('admin.rekap-presensi.download-by-group', ['group_key' => $item['group_key'], 'month' => $item['bulan'], 'year' => $item['tahun'], 'type' => 'presensi']) }}"
                                                       style="display: inline-flex; align-items: center; justify-content: center; width: 28px; height: 28px; border-radius: var(--radius-sm); background: var(--info); color: var(--text-inverse);"
                                                       title="Download Rekap">
                                                        <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                                        </svg>
                                                    </a>
                                                    <a href="{{ route('admin.rekap-presensi.download-by-group', ['group_key' => $item['group_key'], 'month' => $item['bulan'], 'year' => $item['tahun'], 'type' => 'detail']) }}"
                                                       style="display: inline-flex; align-items: center; justify-content: center; width: 28px; height: 28px; border-radius: var(--radius-sm); background: var(--success); color: var(--text-inverse);"
                                                       title="Download Detail">
                                                        <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                        </svg>
                                                    </a>
                                                    <a href="{{ route('admin.rekap-presensi.download-by-group', ['group_key' => $item['group_key'], 'month' => $item['bulan'], 'year' => $item['tahun'], 'type' => 'tukin']) }}"
                                                       style="display: inline-flex; align-items: center; justify-content: center; width: 28px; height: 28px; border-radius: var(--radius-sm); background: #7C3AED; color: var(--text-inverse);"
                                                       title="Download Tukin">
                                                        <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                        </svg>
                                                    </a>
                                                @else
                                                    <a href="{{ route('admin.rekap-presensi.download-presensi', ['dept_id' => $item['dept_id'] ?? 0, 'month' => $item['bulan'], 'year' => $item['tahun']]) }}"
                                                       style="display: inline-flex; align-items: center; justify-content: center; width: 28px; height: 28px; border-radius: var(--radius-sm); background: var(--info); color: var(--text-inverse);"
                                                       title="Download Rekap">
                                                        <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                                        </svg>
                                                    </a>
                                                    <a href="{{ route('admin.rekap-presensi.download-detail', ['dept_id' => $item['dept_id'] ?? 0, 'month' => $item['bulan'], 'year' => $item['tahun']]) }}"
                                                       style="display: inline-flex; align-items: center; justify-content: center; width: 28px; height: 28px; border-radius: var(--radius-sm); background: var(--success); color: var(--text-inverse);"
                                                       title="Download Detail">
                                                        <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                        </svg>
                                                    </a>
                                                    <a href="{{ route('admin.rekap-presensi.download-tukin', ['dept_id' => $item['dept_id'] ?? 0, 'month' => $item['bulan'], 'year' => $item['tahun']]) }}"
                                                       style="display: inline-flex; align-items: center; justify-content: center; width: 28px; height: 28px; border-radius: var(--radius-sm); background: #7C3AED; color: var(--text-inverse);"
                                                       title="Download Tukin">
                                                        <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                        </svg>
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div style="margin-top: 16px;">
                        {{ $generatedPaginated->links() }}
                    </div>
                @else
                    <div style="text-align: center; padding: 40px;">
                        <svg style="width: 48px; height: 48px; margin: 0 auto; color: var(--text-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p style="margin-top: 8px; font-size: 14px; color: var(--text-muted);">
                            Belum ada rekap presensi yang di-generate
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Loading Overlay -->
<div id="generateOverlay" style="display: none; position: fixed; inset: 0; z-index: 9999; background: rgba(0,0,0,0.5); backdrop-filter: blur(4px); align-items: center; justify-content: center;">
    <div style="background: var(--card); border-radius: 12px; padding: 40px 48px; text-align: center; box-shadow: 0 20px 60px rgba(0,0,0,0.3); max-width: 400px; width: 90%;">
        <!-- Spinner -->
        <div style="margin-bottom: 20px;">
            <svg width="56" height="56" viewBox="0 0 56 56" style="animation: spin 1.2s linear infinite;">
                <circle cx="28" cy="28" r="24" fill="none" stroke="var(--border)" stroke-width="4"/>
                <circle cx="28" cy="28" r="24" fill="none" stroke="var(--primary)" stroke-width="4" stroke-dasharray="80 60" stroke-linecap="round"/>
            </svg>
        </div>
        <!-- Status Message -->
        <p id="overlayMessage" style="font-size: 15px; font-weight: 600; color: var(--text-primary); margin-bottom: 8px;">
            Memuat data user...
        </p>
        <!-- Timer -->
        <p id="overlayTimer" style="font-size: 13px; color: var(--text-muted); margin-bottom: 16px;">
            0 detik
        </p>
        <!-- Progress bar -->
        <div style="width: 100%; height: 4px; background: var(--border); border-radius: 2px; overflow: hidden;">
            <div id="overlayProgress" style="height: 100%; background: var(--primary); border-radius: 2px; width: 0%; transition: width 0.5s ease;"></div>
        </div>
        <p style="font-size: 11px; color: var(--text-muted); margin-top: 12px;">
            Mohon jangan tutup halaman ini
        </p>
    </div>
</div>

<style>
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>

@push('scripts')
<script>
    const groupDescriptions = @json($bankKategoriGroups->keyBy('group_key'));

    function parseGroupKey(key) {
        const serdikKeywords = ['serdik', 'nonserdik', 'nonguru', 'unknown'];
        const statusMap = { pns: 'PNS', pppk: 'PPPK', cpns: 'CPNS' };
        const bankMap = { nagari: 'Bank Nagari', 'bank_nagari': 'Bank Nagari', bsi: 'BSI', bri: 'BRI' };

        if (key === 'belum_dikategorikan') {
            return { status: '-', bankKategori: 'Belum Dikategorikan', serdik: null };
        }

        const parts = key.split('_');
        const status = statusMap[parts[0]] || parts[0];
        const bidang = parts[1] === 'keagamaan' ? 'Keagamaan' : 'Kependidikan';

        let serdik = null, serdikIdx = -1;
        for (let i = parts.length - 1; i >= 3; i--) {
            if (serdikKeywords.includes(parts[i])) { serdik = parts[i]; serdikIdx = i; break; }
        }

        const bankParts = parts.slice(2, serdikIdx > 0 ? serdikIdx : parts.length);
        const bankSlug = bankParts.join('_');
        const bankName = bankMap[bankSlug] || bankSlug.toUpperCase();
        const serdikMap = { serdik: 'Bersertifikasi', nonserdik: 'Non-sertifikasi', nonguru: 'Non-guru', unknown: 'Unknown' };

        return {
            status: status,
            bankKategori: bidang + ' ' + bankName,
            serdik: serdik ? (serdikMap[serdik] || serdik) : null,
        };
    }

    function rekapForm() {
        return {
            method: '{{ old("method", "unit_kerja") }}',
            selectedGroup: '{{ old("group_key", "") }}',
            get groupInfo() {
                if (!this.selectedGroup || !groupDescriptions[this.selectedGroup]) {
                    return { status: '', bank_kategori: '', serdik: '', total: '' };
                }
                const g = groupDescriptions[this.selectedGroup];
                const parsed = parseGroupKey(this.selectedGroup);
                return { status: parsed.status, bank_kategori: parsed.bankKategori, serdik: parsed.serdik, total: g.total + ' user' };
            }
        }
    }

    // AJAX Generate dengan loading overlay
    let generateTimer = null;
    let elapsedSeconds = 0;

    const statusMessages = [
        { time: 0, text: 'Memuat data user...' },
        { time: 5, text: 'Mengambil data presensi...' },
        { time: 15, text: 'Membuat file Excel rekap...' },
        { time: 25, text: 'Membuat file Excel detail...' },
        { time: 40, text: 'Menyimpan file ke server...' },
        { time: 60, text: 'Masih diproses, mohon tunggu...' },
    ];

    function getOverlayMessage(elapsed) {
        let msg = statusMessages[0].text;
        for (const s of statusMessages) {
            if (elapsed >= s.time) msg = s.text;
        }
        return msg;
    }

    function showOverlay() {
        elapsedSeconds = 0;
        const overlay = document.getElementById('generateOverlay');
        const msgEl = document.getElementById('overlayMessage');
        const timeEl = document.getElementById('overlayTimer');
        const progressEl = document.getElementById('overlayProgress');
        overlay.style.display = 'flex';
        msgEl.textContent = getOverlayMessage(0);
        timeEl.textContent = '0 detik';
        progressEl.style.width = '0%';

        generateTimer = setInterval(() => {
            elapsedSeconds++;
            msgEl.textContent = getOverlayMessage(elapsedSeconds);
            timeEl.textContent = elapsedSeconds + ' detik';
            // Progress: naik perlahan, max 90% (100% saat selesai)
            const pct = Math.min(90, (elapsedSeconds / 90) * 100);
            progressEl.style.width = pct + '%';
        }, 1000);
    }

    function hideOverlay() {
        if (generateTimer) { clearInterval(generateTimer); generateTimer = null; }
        document.getElementById('generateOverlay').style.display = 'none';
    }

    document.getElementById('rekapForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const form = this;
        const formData = new FormData(form);

        showOverlay();

        fetch(form.action, {
            method: 'POST',
            body: formData,
            credentials: 'same-origin',
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        })
        .then(res => {
            if (!res.ok) {
                return res.json().catch(() => null).then(errData => {
                    throw { status: res.status, data: errData };
                });
            }
            return res.json();
        })
        .then(data => {
            hideOverlay();
            if (data.success) {
                showFlash('success', data.message);
                setTimeout(() => location.reload(), 1500);
            } else {
                showFlash('error', data.message || 'Gagal generate rekap presensi');
            }
        })
        .catch(err => {
            hideOverlay();
            console.error('Generate error:', err);
            const msg = err?.data?.message || 'Terjadi kesalahan. Silakan coba lagi.';
            showFlash('error', msg);
        });
    });

    function showFlash(type, message) {
        const container = document.getElementById('flashContainer');
        const bgColor = type === 'success' ? 'var(--success-bg)' : 'var(--danger-bg)';
        const borderColor = type === 'success' ? 'var(--success)' : 'var(--danger)';
        const textColor = type === 'success' ? 'var(--success)' : 'var(--danger)';
        const icon = type === 'success'
            ? '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>'
            : '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>';

        container.innerHTML = `
            <div class="mb-6 p-4" style="background: ${bgColor}; border: 1px solid ${borderColor}; border-radius: var(--radius); transition: opacity 0.3s;">
                <div class="flex items-center">
                    <svg class="w-5 h-5" style="color: ${textColor}" fill="currentColor" viewBox="0 0 20 20">${icon}</svg>
                    <p class="ml-3 text-sm" style="color: ${textColor}">${message}</p>
                </div>
            </div>`;

        setTimeout(() => { container.innerHTML = ''; }, 5000);
    }
</script>
@endpush
@endsection
