@extends('admin.layouts.app')

@section('title', 'Download Export Presensi')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                Download Export Presensi
            </h1>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                Download file Excel hasil export presensi
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

        @if(empty($years))
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Belum ada file export</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Jalankan command export terlebih dahulu: <code>php artisan presensi:export --all</code>
                </p>
            </div>
        @else
            <!-- File Tree -->
            <div class="space-y-6">
                @foreach($years as $year => $yearData)
                    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
                        <!-- Year Header -->
                        <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Tahun {{ $year }}</h3>
                                </div>
                                <span class="px-2 py-1 text-xs font-medium bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 rounded-full">
                                    {{ $yearData['total_files'] }} file
                                </span>
                            </div>
                        </div>

                        <!-- Months -->
                        <div class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($yearData['months'] as $monthName => $monthData)
                                <div class="px-4 py-3">
                                    <div class="flex items-center justify-between mb-3">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                            </svg>
                                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $monthName }}</h4>
                                            <span class="ml-2 text-xs text-gray-500 dark:text-gray-400">
                                                ({{ $monthData['total_files'] }} file, {{ $monthData['total_size'] }})
                                            </span>
                                        </div>
                                        <a href="{{ route('admin.exports.download-month', ['year' => $year, 'month' => $monthData['month']]) }}"
                                           class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                            </svg>
                                            ZIP
                                        </a>
                                    </div>

                                    <!-- Files -->
                                    <div class="space-y-2">
                                        @foreach($monthData['files'] as $file)
                                            <div class="flex items-center justify-between p-2 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                                                <div class="flex items-center">
                                                    <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                    </svg>
                                                    <div>
                                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $file['name'] }}</p>
                                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                                            {{ $file['size'] }} &middot; {{ $file['modified'] }}
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="flex items-center space-x-2">
                                                    <a href="{{ route('admin.exports.download', ['file' => $file['path']]) }}"
                                                       class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                                        </svg>
                                                        Download
                                                    </a>
                                                    <form action="{{ route('admin.exports.delete') }}" method="POST" class="inline"
                                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus file ini?')">
                                                        @csrf
                                                        <input type="hidden" name="file" value="{{ $file['path'] }}">
                                                        <button type="submit"
                                                                class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                            </svg>
                                                            Hapus
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Info -->
        <div class="mt-8 bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-800 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-blue-900 dark:text-blue-100 mb-3">
                Informasi Export
            </h3>
            <div class="text-sm text-blue-700 dark:text-blue-300 space-y-2">
                <p><strong>Lokasi File:</strong> <code>storage/app/exports/presensi/{year}/{month}/</code></p>
                <p><strong>Format:</strong> Excel (.xlsx)</p>
                <p><strong>Nama File:</strong> presensi_{nama_unit_kerja}_{year}_{month}.xlsx</p>
                <p class="mt-3"><strong>Catatan:</strong></p>
                <ul class="list-disc list-inside ml-4 space-y-1">
                    <li>Download ZIP untuk semua file dalam 1 bulan</li>
                    <li>Download individual untuk 1 file</li>
                    <li>Hapus file jika sudah tidak diperlukan</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
