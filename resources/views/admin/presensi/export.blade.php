@extends('admin.layouts.app')

@section('title', 'Export Presensi Excel')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                Export Presensi Excel
            </h1>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                Download data presensi dalam format Excel
            </p>
        </div>

        <!-- Alert Messages -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 rounded-lg">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <p class="ml-3 text-sm text-green-700 dark:text-green-300">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-lg">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <p class="ml-3 text-sm text-red-700 dark:text-red-300">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Export Form -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                    Export Per User
                </h2>

                <form id="exportForm">
                    @csrf

                    <!-- User Selection -->
                    <div class="mb-4">
                        <label for="user_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Pilih User *
                        </label>
                        <select
                            name="user_id"
                            id="user_id"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            required
                        >
                            <option value="">-- Pilih User --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">
                                    {{ $user->name }} ({{ $user->nomor_induk }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Month & Year -->
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="month" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Bulan *
                            </label>
                            <select
                                name="month"
                                id="month"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"
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
                            <label for="year" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Tahun *
                            </label>
                            <select
                                name="year"
                                id="year"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"
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

                    <!-- Export Buttons -->
                    <div class="flex gap-3">
                        <button
                            type="button"
                            onclick="exportDetail()"
                            class="flex-1 flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                        >
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Detail
                        </button>

                        <button
                            type="button"
                            onclick="exportAbsensi()"
                            class="flex-1 flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                        >
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Absensi
                        </button>
                    </div>
                </form>
            </div>

            <!-- Bulk Export -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                    Export Semua User
                </h2>

                <form id="bulkExportForm" method="POST" action="{{ route('admin.presensi.export.bulk') }}">
                    @csrf

                    <!-- Month & Year -->
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="bulk_month" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Bulan *
                            </label>
                            <select
                                name="month"
                                id="bulk_month"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"
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
                            <label for="bulk_year" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Tahun *
                            </label>
                            <select
                                name="year"
                                id="bulk_year"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"
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

                    <!-- Export Type -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Tipe Export *
                        </label>
                        <div class="flex gap-4">
                            <label class="flex items-center">
                                <input type="radio" name="type" value="detail" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300" checked>
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Detail</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="type" value="absensi" class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Absensi</span>
                            </label>
                        </div>
                    </div>

                    <!-- Export Button -->
                    <button
                        type="submit"
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Download Semua User (ZIP)
                    </button>
                </form>
            </div>
        </div>

        <!-- Export Info -->
        <div class="mt-8 bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-800 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-blue-900 dark:text-blue-100 mb-3">
                Informasi Export
            </h3>
            <div class="text-sm text-blue-700 dark:text-blue-300 space-y-2">
                <p><strong>Tipe Detail:</strong></p>
                <ul class="list-disc list-inside ml-4 space-y-1">
                    <li>Menampilkan data presensi lengkap (tanggal, jam masuk, jam pulang, status)</li>
                    <li>Cocok untuk laporan presensi bulanan</li>
                    <li>Format user-friendly dengan styling menarik</li>
                </ul>

                <p class="mt-3"><strong>Tipe Absensi:</strong></p>
                <ul class="list-disc list-inside ml-4 space-y-1">
                    <li>Menampilkan kehadiran harian dengan value 1</li>
                    <li>1 = Ada presensi masuk atau pulang</li>
                    <li>Cocok untuk rekap kehadiran</li>
                    <li>Highlight hari libur dan hari dengan kehadiran</li>
                </ul>

                <p class="mt-3"><strong>Catatan:</strong></p>
                <ul class="list-disc list-inside ml-4 space-y-1">
                    <li>File tersimpan di folder exports/</li>
                    <li>Format file: .xlsx (Excel)</li>
                    <li>Bulk export akan menghasilkan file ZIP</li>
                </ul>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function exportDetail() {
        const userId = document.getElementById('user_id').value;
        const month = document.getElementById('month').value;
        const year = document.getElementById('year').value;

        if (!userId) {
            alert('Pilih user terlebih dahulu');
            return;
        }

        window.location.href = `/admin/presensi/export/detail?user_id=${userId}&month=${month}&year=${year}`;
    }

    function exportAbsensi() {
        const userId = document.getElementById('user_id').value;
        const month = document.getElementById('month').value;
        const year = document.getElementById('year').value;

        if (!userId) {
            alert('Pilih user terlebih dahulu');
            return;
        }

        window.location.href = `/admin/presensi/export/absensi?user_id=${userId}&month=${month}&year=${year}`;
    }
</script>
@endpush
@endsection
