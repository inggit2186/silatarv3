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
                Generate file Excel rekap presensi per unit kerja
            </p>
        </div>

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
                    <div class="flex space-x-2">
                        @if(session('dept_id') && session('month') && session('year'))
                            <a href="{{ route('admin.rekap-presensi.download-presensi', ['dept_id' => session('dept_id'), 'month' => session('month'), 'year' => session('year')]) }}"
                               class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded"
                               style="background: var(--info); color: var(--text-inverse)">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                                Download Rekap
                            </a>
                            <a href="{{ route('admin.rekap-presensi.download-detail', ['dept_id' => session('dept_id'), 'month' => session('month'), 'year' => session('year')]) }}"
                               class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded"
                               style="background: var(--success); color: var(--text-inverse)">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                                Download Detail
                            </a>
                        @endif
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

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
            <!-- Form Card -->
            <div class="card" style="padding: 24px;">
                <h2 style="font-size: 18px; font-weight: 600; color: var(--text-primary); margin-bottom: 16px;">
                    Generate Rekap Presensi
                </h2>

                <form action="{{ route('admin.rekap-presensi.generate') }}" method="POST" id="rekapForm">
                    @csrf

                    <div style="display: flex; flex-direction: column; gap: 16px;">
                        <!-- Department -->
                        <div>
                            <label style="display: block; font-size: 14px; font-weight: 500; color: var(--text-secondary); margin-bottom: 8px;">
                                Unit Kerja *
                            </label>
                            <select
                                name="dept_id"
                                id="dept_id"
                                style="width: 100%; padding: 10px 12px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 14px; color: var(--text-primary); background: var(--card);"
                                required
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

                        <!-- Month & Year -->
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                            <div>
                                <label style="display: block; font-size: 14px; font-weight: 500; color: var(--text-secondary); margin-bottom: 8px;">
                                    Bulan *
                                </label>
                                <select
                                    name="month"
                                    id="month"
                                    style="width: 100%; padding: 10px 12px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 14px; color: var(--text-primary); background: var(--card);"
                                    required
                                >
                                    @for($m = 1; $m <= 12; $m++)
                                        <option value="{{ $m }}" {{ $m == $currentMonth ? 'selected' : '' }}>
                                            {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                                        </option>
                                    @endfor
                                </select>
                                @error('month')
                                    <p style="color: var(--danger); font-size: 12px; margin-top: 4px;">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label style="display: block; font-size: 14px; font-weight: 500; color: var(--text-secondary); margin-bottom: 8px;">
                                    Tahun *
                                </label>
                                <select
                                    name="year"
                                    id="year"
                                    style="width: 100%; padding: 10px 12px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 14px; color: var(--text-primary); background: var(--card);"
                                    required
                                >
                                    @for($y = $currentYear - 2; $y <= $currentYear + 1; $y++)
                                        <option value="{{ $y }}" {{ $y == $currentYear ? 'selected' : '' }}>
                                            {{ $y }}
                                        </option>
                                    @endfor
                                </select>
                                @error('year')
                                    <p style="color: var(--danger); font-size: 12px; margin-top: 4px;">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div style="margin-top: 24px;">
                        <button
                            type="submit"
                            id="generateBtn"
                            style="width: 100%; display: flex; justify-content: center; align-items: center; padding: 12px 16px; border: none; border-radius: var(--radius-sm); font-size: 14px; font-weight: 500; color: var(--text-inverse); background: var(--primary); cursor: pointer;"
                        >
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            Generate Rekap Presensi
                        </button>
                    </div>
                </form>
            </div>

            <!-- History Card -->
            <div class="card" style="padding: 24px;">
                <h2 style="font-size: 18px; font-weight: 600; color: var(--text-primary); margin-bottom: 16px;">
                    Unit Kerja yang Sudah Di-generate
                </h2>

                @if(isset($generatedDepts) && count($generatedDepts) > 0)
                    <div style="display: flex; flex-direction: column; gap: 12px;">
                        @foreach($generatedDepts as $item)
                            <div style="display: flex; align-items: center; justify-content: space-between; padding: 12px; background: var(--secondary); border-radius: var(--radius-sm);">
                                <div style="display: flex; align-items: center;">
                                    <div style="width: 40px; height: 40px; background: var(--primary-50); border-radius: var(--radius-sm); display: flex; align-items: center; justify-content: center; margin-right: 12px;">
                                        <svg style="width: 20px; height: 20px; color: var(--primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p style="font-size: 14px; font-weight: 500; color: var(--text-primary);">{{ $item['dept'] }}</p>
                                        <p style="font-size: 12px; color: var(--text-muted);">
                                            {{ \Carbon\Carbon::create()->month($item['bulan'])->format('F') }} {{ $item['tahun'] }}
                                        </p>
                                    </div>
                                </div>
                                <div style="display: flex; gap: 8px;">
                                    <a href="{{ route('admin.rekap-presensi.download-presensi', ['dept_id' => $item['dept_id'], 'month' => $item['bulan'], 'year' => $item['tahun']]) }}"
                                       style="display: flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: var(--radius-sm); background: var(--info); color: var(--text-inverse);"
                                       title="Download Rekap Presensi">
                                        <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                        </svg>
                                    </a>
                                    <a href="{{ route('admin.rekap-presensi.download-detail', ['dept_id' => $item['dept_id'], 'month' => $item['bulan'], 'year' => $item['tahun']]) }}"
                                       style="display: flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: var(--radius-sm); background: var(--success); color: var(--text-inverse);"
                                       title="Download Detail Presensi">
                                        <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div style="text-align: center; padding: 32px;">
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

        <!-- Info Card -->
        <div class="card" style="margin-top: 24px; padding: 24px; background: var(--info-bg); border: 1px solid var(--info);">
            <h3 style="font-size: 18px; font-weight: 600; color: var(--info); margin-bottom: 12px;">
                Informasi
            </h3>
            <div style="font-size: 14px; color: var(--info);">
                <p><strong>File yang dihasilkan:</strong></p>
                <ul style="list-style-type: disc; margin-left: 16px; margin-top: 8px;">
                    <li><strong>Rekap Presensi</strong> - Format absensi harian dengan value 1 (hadir)</li>
                    <li><strong>Detail Presensi</strong> - Format jam masuk / jam pulang per hari</li>
                </ul>
                <p style="margin-top: 12px;"><strong>Catatan:</strong></p>
                <ul style="list-style-type: disc; margin-left: 16px; margin-top: 8px;">
                    <li>File disimpan di server (storage/app/rekap_presensi/)</li>
                    <li>Generate ulang akan menimpa file sebelumnya</li>
                    <li>Download file setelah generate</li>
                </ul>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('rekapForm').addEventListener('submit', function() {
        const btn = document.getElementById('generateBtn');
        btn.disabled = true;
        btn.innerHTML = `
            <svg class="animate-spin -ml-1 mr-3 h-5 w-5" style="color: var(--text-inverse);" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Generating...
        `;
    });
</script>
@endpush
@endsection
