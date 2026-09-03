@extends('admin.layouts.app')

@section('title', 'Import Presensi Excel')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                Import Presensi Excel
            </h1>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                Upload file Excel untuk import data presensi pegawai
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
            <!-- Upload Form -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                    Upload File Excel
                </h2>

                <form action="{{ route('admin.presensi.import.preview') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                    @csrf

                    <!-- Department Selection -->
                    <div class="mb-4">
                        <label for="dept_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Unit Kerja (Departemen) *
                        </label>
                        <select
                            name="dept_id"
                            id="dept_id"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            required
                        >
                            <option value="">-- Pilih Unit Kerja --</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->dept_id }}" {{ old('dept_id') == $dept->dept_id ? 'selected' : '' }}>
                                    {{ $dept->dept_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('dept_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- File Upload -->
                    <div class="mb-6">
                        <label for="file" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            File Excel (.xlsx) *
                        </label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-lg hover:border-blue-400 dark:hover:border-blue-500 transition-colors">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <div class="flex text-sm text-gray-600 dark:text-gray-400">
                                    <label for="file" class="relative cursor-pointer bg-white dark:bg-gray-700 rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                        <span>Upload file</span>
                                        <input
                                            id="file"
                                            name="file"
                                            type="file"
                                            class="sr-only"
                                            accept=".xlsx,.xls"
                                            required
                                        />
                                    </label>
                                    <p class="pl-1">atau drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    XLSX, XLS sampai 5MB
                                </p>
                            </div>
                        </div>
                        <div id="fileName" class="mt-2 text-sm text-gray-600 dark:text-gray-400 hidden"></div>
                        @error('file')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <button
                        type="submit"
                        id="previewBtn"
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        Preview Data
                    </button>
                </form>
            </div>

            <!-- Import History -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                    Riwayat Import
                </h2>

                @if(count($history) > 0)
                    <div class="space-y-3">
                        @foreach($history as $item)
                            <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $item['total_records'] }} record
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $item['imported_by'] }} - {{ $item['imported_at'] }}
                                        </p>
                                    </div>
                                    <form action="{{ route('admin.presensi.import.rollback', $item['batch_id']) }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="confirm" value="yes">
                                        <button
                                            type="submit"
                                            onclick="return confirm('Apakah Anda yakin ingin rollback import ini? Semua data dari import ini akan dihapus.')"
                                            class="text-xs text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300"
                                        >
                                            Rollback
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-4">
                        Belum ada riwayat import
                    </p>
                @endif

                @if(count($history) > 0)
                    <div class="mt-4">
                        <a
                            href="{{ route('admin.presensi.import.history') }}"
                            class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300"
                        >
                            Lihat Semua Riwayat →
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Instructions -->
        <div class="mt-8 bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-800 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-blue-900 dark:text-blue-100 mb-3">
                Format File Excel
            </h3>
            <div class="text-sm text-blue-700 dark:text-blue-300 space-y-2">
                <p><strong>File harus dalam format .xlsx dengan kolom sebagai berikut:</strong></p>
                <ul class="list-disc list-inside ml-4 space-y-1">
                    <li>A: NAMA</li>
                    <li>B: NIP (18 digit)</li>
                    <li>C: JABATAN</li>
                    <li>D: TANGGAL (format: YYYY-MM-DD)</li>
                    <li>E: HARI</li>
                    <li>F: JAM MASUK (format: HH:MM)</li>
                    <li>G: ABSEN MASUK</li>
                    <li>H: CEPAT TELAT</li>
                    <li>I: JAM PULANG (format: HH:MM)</li>
                    <li>J: ABSEN PULANG</li>
                    <li>K-R: Kolom tambahan (opsional)</li>
                </ul>
                <p class="mt-3"><strong>Catatan:</strong></p>
                <ul class="list-disc list-inside ml-4 space-y-1">
                    <li>Baris pertama adalah header</li>
                    <li>Data mulai dari baris kedua</li>
                    <li>NIP harus terdaftar di sistem</li>
                    <li>Data duplikat (NIP + tanggal) akan dilewati</li>
                </ul>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // File upload preview
    document.getElementById('file').addEventListener('change', function(e) {
        const fileName = e.target.files[0]?.name;
        const fileNameDiv = document.getElementById('fileName');

        if (fileName) {
            fileNameDiv.textContent = 'File: ' + fileName;
            fileNameDiv.classList.remove('hidden');
            document.getElementById('previewBtn').disabled = false;
        } else {
            fileNameDiv.classList.add('hidden');
            document.getElementById('previewBtn').disabled = true;
        }
    });

    // Form submission
    document.getElementById('uploadForm').addEventListener('submit', function() {
        const btn = document.getElementById('previewBtn');
        btn.disabled = true;
        btn.innerHTML = `
            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Memproses...
        `;
    });
</script>
@endpush
@endsection
