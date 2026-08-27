<x-layouts.app title="Pelaporan Presensi Error - SILATAR">

    <main class="neo-mirai min-h-screen bg-[var(--paper)]">
        <!-- Scroll Info -->
        <div class="bg-red-500/10 border-b border-red-500/20 py-2 px-4 text-center sticky top-0 z-40">
            <p class="text-xs text-red-600 font-semibold flex items-center justify-center gap-2">
                <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
                Halaman Alternatif - Gunakan jika sistem presensi utama bermasalah
            </p>
        </div>

        <!-- Content -->
        <section class="page-content px-4 py-4 lg:px-6">
            <div class="max-w-2xl mx-auto">
                @if(session('success'))
                    <div class="mb-6 p-6 bg-emerald-50 border-2 border-emerald-300 rounded-2xl">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-emerald-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-emerald-800">{{ session('success') }}</h3>
                                <p class="text-sm text-emerald-600 mt-1">Surat keterangan akan ditampilkan dalam beberapa saat...</p>
                            </div>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-xl text-red-700 flex items-center gap-3">
                        <svg class="w-5 h-5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ session('error') }}
                    </div>
                @endif

                {{-- User Info Card --}}
                <div class="neo-card mb-6 p-5">
                    <div class="flex items-center gap-4">
                        @if($user->pp)
                            <div class="w-14 h-14 rounded-full flex-shrink-0 overflow-hidden border-2 border-[var(--gold)]">
                                <img src="{{ asset('storage/users_berkas/' . $user->nomor_induk . '/' . $user->pp) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                            </div>
                        @else
                            <div class="w-14 h-14 bg-[var(--gold)] rounded-full flex items-center justify-center flex-shrink-0">
                                <span class="text-xl font-bold text-white">{{ substr($user->name, 0, 2) }}</span>
                            </div>
                        @endif
                        <div class="flex-1">
                            <h2 class="text-lg font-bold text-[var(--ink)]">{{ $user->name }}</h2>
                            <div class="flex flex-wrap gap-x-4 gap-y-1 text-xs text-[var(--ink-soft)]">
                                <span>NIP: {{ $user->nomor_induk }}</span>
                                @if($user->pekerjaan)
                                    <span>Jabatan: {{ $user->pekerjaan }}</span>
                                @endif
                                @if($unitKerja)
                                    <span>Unit Kerja: {{ $unitKerja }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Status Presensi Hari Ini --}}
                @if($presensi && ($presensi->m_absen || $presensi->p_absen))
                    <div class="neo-card mb-6 p-5">
                        <div class="flex items-center gap-3 mb-4 pb-4 border-b border-[var(--line)]">
                            <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-[var(--ink)]">Status Presensi Hari Ini</h3>
                                <p class="text-xs text-[var(--ink-soft)]">{{ \Carbon\Carbon::now('Asia/Jakarta')->format('d M Y') }}</p>
                            </div>
                        </div>

                        <div class="space-y-3">
                            @if($presensi->m_absen)
                                <div class="p-3 bg-emerald-50 rounded-lg">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-5 h-5 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            <span class="text-sm font-semibold text-emerald-700">Presensi Masuk</span>
                                        </div>
                                        <span class="text-sm text-emerald-700">sudah diambil ({{ $presensi->error_masuk_taken_at ?? $presensi->m_absen }})</span>
                                    </div>
                                    <div class="mt-2 ml-7">
                                        <a href="{{ route('presensi-error.surat', ['id' => $presensi->id, 'jenis' => 'masuk']) }}" target="_blank" class="inline-flex items-center gap-1 text-xs font-semibold text-emerald-600 hover:text-emerald-800">
                                            <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                            </svg>
                                            Lihat Surat Keterangan
                                        </a>
                                    </div>
                                </div>
                            @else
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-5 h-5 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span class="text-sm text-gray-500">Belum presensi masuk</span>
                                    </div>
                                </div>
                            @endif

                            @if($presensi->p_absen)
                                <div class="p-3 bg-blue-50 rounded-lg">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-5 h-5 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            <span class="text-sm font-semibold text-blue-700">Presensi Pulang</span>
                                        </div>
                                        <span class="text-sm text-blue-700">sudah diambil ({{ $presensi->error_pulang_taken_at ?? $presensi->p_absen }})</span>
                                    </div>
                                    <div class="mt-2 ml-7">
                                        <a href="{{ route('presensi-error.surat', ['id' => $presensi->id, 'jenis' => 'pulang']) }}" target="_blank" class="inline-flex items-center gap-1 text-xs font-semibold text-blue-600 hover:text-blue-800">
                                            <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                            </svg>
                                            Lihat Surat Keterangan
                                        </a>
                                    </div>
                                </div>
                            @else
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-5 h-5 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span class="text-sm text-gray-500">Belum presensi pulang</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- Form Presensi Error --}}
                @php
                    $sudahMasuk = $presensi && $presensi->m_absen;
                    $sudahPulang = $presensi && $presensi->p_absen;
                @endphp

                @if($sudahMasuk && $sudahPulang)
                    <div class="neo-card p-6 text-center">
                        <div class="w-16 h-16 mx-auto mb-4 bg-emerald-100 rounded-full flex items-center justify-center">
                            <svg class="w-8 h-8 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-[var(--ink)] mb-2">Presensi Hari Ini Sudah Lengkap</h3>
                        <p class="text-sm text-[var(--ink-soft)]">Anda sudah melakukan presensi masuk dan pulang hari ini</p>
                    </div>
                @else
                    <form action="{{ route('presensi-error.submit') }}" method="POST" id="presensiErrorForm" onsubmit="return validateForm()">
                        @csrf

                        {{-- Jenis Presensi --}}
                        <div class="neo-card p-6 mb-4">
                            <div class="flex items-center gap-3 mb-4 pb-4 border-b border-[var(--line)]">
                                <div class="w-10 h-10 bg-[var(--gold)]/10 rounded-xl flex items-center justify-center">
                                    <svg class="w-5 h-5 text-[var(--gold)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-[var(--ink)]">Jenis Presensi</h3>
                                    <p class="text-xs text-[var(--ink-soft)]">Pilih jenis presensi yang ingin dilaporkan</p>
                                </div>
                            </div>

                            <div class="space-y-3">
                                @if(!$sudahMasuk)
                                    <label class="flex items-center gap-4 p-4 border-2 border-[var(--line)] rounded-xl cursor-pointer hover:border-[var(--gold)] hover:bg-[var(--gold)]/5 transition-all" id="labelMasuk">
                                        <input type="radio" name="jenis" value="masuk" class="w-5 h-5 text-[var(--gold)] focus:ring-[var(--gold)]" onchange="updateSelectedJenis()">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2">
                                                <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center">
                                                    <svg class="w-4 h-4 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <span class="text-sm font-bold text-[var(--ink)]">Presensi Masuk</span>
                                                    <p class="text-xs text-[var(--ink-soft)]">Laporkan jam kehadiran</p>
                                                </div>
                                            </div>
                                        </div>
                                        <svg class="w-5 h-5 text-[var(--ink-soft)] check-icon hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </label>
                                @endif

                                @if(!$sudahPulang)
                                    <label class="flex items-center gap-4 p-4 border-2 border-[var(--line)] rounded-xl cursor-pointer hover:border-[var(--gold)] hover:bg-[var(--gold)]/5 transition-all" id="labelPulang">
                                        <input type="radio" name="jenis" value="pulang" class="w-5 h-5 text-[var(--gold)] focus:ring-[var(--gold)]" onchange="updateSelectedJenis()">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2">
                                                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                                    <svg class="w-4 h-4 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <span class="text-sm font-bold text-[var(--ink)]">Presensi Pulang</span>
                                                    <p class="text-xs text-[var(--ink-soft)]">Laporkan jam pulang</p>
                                                </div>
                                            </div>
                                        </div>
                                        <svg class="w-5 h-5 text-[var(--ink-soft)] check-icon hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </label>
                                @endif
                            </div>
                        </div>

                        {{-- Alasan Pengaduan Presensi --}}
                        <div class="neo-card p-6 mb-4">
                            <div class="flex items-center gap-3 mb-4 pb-4 border-b border-[var(--line)]">
                                <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center">
                                    <svg class="w-5 h-5 text-red-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-[var(--ink)]">Alasan Pengaduan Presensi</h3>
                                    <p class="text-xs text-[var(--ink-soft)]">Pilih alasan pengaduan presensi</p>
                                </div>
                            </div>

                            <div class="space-y-3">
                                <label class="flex items-center gap-4 p-4 border-2 border-[var(--line)] rounded-xl cursor-pointer hover:border-[var(--gold)] hover:bg-[var(--gold)]/5 transition-all" id="labelSistemError">
                                    <input type="radio" name="alasan" value="SISTEM_ERROR" class="w-5 h-5 text-[var(--gold)] focus:ring-[var(--gold)]" onchange="updateSelectedAlasan()">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2">
                                            <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                                                <svg class="w-4 h-4 text-red-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <span class="text-sm font-bold text-[var(--ink)]">Sistem Error</span>
                                                <p class="text-xs text-[var(--ink-soft)]">Presensi utama mengalami gangguan</p>
                                            </div>
                                        </div>
                                    </div>
                                    <svg class="w-5 h-5 text-[var(--ink-soft)] check-icon hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </label>

                                <label class="flex items-center gap-4 p-4 border-2 border-[var(--line)] rounded-xl cursor-pointer hover:border-[var(--gold)] hover:bg-[var(--gold)]/5 transition-all" id="labelTugasLuar">
                                    <input type="radio" name="alasan" value="TUGAS_LUAR" class="w-5 h-5 text-[var(--gold)] focus:ring-[var(--gold)]" onchange="updateSelectedAlasan()">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2">
                                            <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center">
                                                <svg class="w-4 h-4 text-amber-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <span class="text-sm font-bold text-[var(--ink)]">Tugas Luar</span>
                                                <p class="text-xs text-[var(--ink-soft)]">Sedang melaksanakan tugas di luar kantor</p>
                                            </div>
                                        </div>
                                    </div>
                                    <svg class="w-5 h-5 text-[var(--ink-soft)] check-icon hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </label>
                            </div>

                            {{-- Keterangan Tugas Luar --}}
                            <div id="keteranganTugasLuar" class="hidden mt-4">
                                <label class="block text-sm font-semibold text-[var(--ink)] mb-2">Keterangan Tugas Luar <span class="text-red-500">*</span></label>
                                <textarea id="inputKeterangan" name="keterangan_tugas_luar" rows="3" placeholder="Contoh: Dinas ke KUA Banuhampu untuk pengajuan TPG" class="w-full px-4 py-3 bg-[var(--paper-soft)] border border-[var(--line)] rounded-xl text-[var(--ink)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)] resize-none"></textarea>
                            </div>
                        </div>

                        {{-- Hidden Fields --}}
                        <input type="hidden" name="latitude" id="latitude" value="0">
                        <input type="hidden" name="longitude" id="longitude" value="0">
                        <input type="hidden" name="jarak_meter" id="jarak_meter" value="0">
                        <input type="hidden" name="foto" id="foto" value="">
                        <input type="hidden" name="alamat" id="alamat" value="">
                        <input type="hidden" name="supervisor_name" id="supervisor_name" value="{{ $user->dept_id == 998 || $user->dept_id == 999 ? '' : 'N/A' }}">
                        <input type="hidden" name="supervisor_nip" id="supervisor_nip" value="{{ $user->dept_id == 998 || $user->dept_id == 999 ? '' : 'N/A' }}">
                        <input type="hidden" name="unit_kerja_manual" id="unit_kerja_manual" value="{{ $user->dept_id == 998 || $user->dept_id == 999 ? '' : 'N/A' }}">

                        {{-- Location Info --}}
                        <div class="neo-card p-6 mb-4">
                            <div class="flex items-center gap-3 mb-4 pb-4 border-b border-[var(--line)]">
                                <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center">
                                    <svg class="w-5 h-5 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-lg font-bold text-[var(--ink)]">Lokasi Anda</h3>
                                    <p class="text-xs text-[var(--ink-soft)]">GPS akan terdeteksi otomatis</p>
                                </div>
                            </div>

                            <div id="locationInfo" class="p-3 bg-[var(--paper-soft)] rounded-lg">
                                <div id="locationText" class="text-sm text-[var(--ink-soft)]">
                                    Mendeteksi lokasi...
                                </div>
                                <div id="distanceInfo" class="hidden mt-2 pt-2 border-t border-[var(--line)]">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-[var(--gold)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        <span id="distanceText" class="text-sm font-semibold text-[var(--ink)]"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Photo Section --}}
                        <div class="neo-card p-6 mb-4">
                            <div class="flex items-center gap-3 mb-4 pb-4 border-b border-[var(--line)]">
                                <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center">
                                    <svg class="w-5 h-5 text-amber-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-[var(--ink)]">Bukti Foto</h3>
                                    <p class="text-xs text-[var(--ink-soft)]">Wajib - sebagai bukti kehadiran</p>
                                </div>
                            </div>

                            <div id="photoPreview" class="hidden mb-3">
                                <img id="photoImg" src="" alt="Foto" class="w-full h-48 object-cover rounded-lg">
                                <button type="button" onclick="clearPhoto()" class="mt-2 text-sm text-red-600 hover:text-red-800 font-semibold">
                                    Hapus Foto
                                </button>
                            </div>

                            <button type="button" onclick="takePhoto()" class="w-full py-4 border-2 border-dashed border-[var(--gold)] rounded-lg bg-[var(--gold)]/5 text-[var(--gold)] hover:bg-[var(--gold)]/10 transition-colors">
                                <svg class="w-8 h-8 mx-auto mb-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9a2 2 0 012-2z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span class="text-sm font-semibold">Ambil Foto</span>
                            </button>
                        </div>

                        {{-- Submit Button --}}
                        <button type="submit" id="submitBtn" class="w-full py-3 bg-[var(--gold)] hover:bg-[var(--gold-bright)] text-white font-bold rounded-xl shadow-lg transition-all flex items-center justify-center gap-2">
                            <span id="submitText">Kirim Laporan Presensi</span>
                            <span id="submitSpinner" class="hidden">
                                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </span>
                        </button>
                    </form>
                @endif
            </div>
        </section>

        {{-- Modal Input Atasan (dept 998/999) --}}
        @if($user->dept_id == 998 || $user->dept_id == 999)
            <div id="supervisorModal" style="display:none;" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
                <div class="bg-[var(--paper)] rounded-2xl w-full max-w-md p-6 shadow-xl">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-amber-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-[var(--ink)]">Input Data Atasan</h3>
                            <p class="text-xs text-[var(--ink-soft)]">Wajib diisi untuk penandatanganan surat</p>
                        </div>
                    </div>

                    <div class="mb-4 p-3 bg-amber-50 border border-amber-200 rounded-lg">
                        <p class="text-xs text-amber-700">Unit kerja Anda memerlukan input manual atasan untuk surat keterangan.</p>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-[var(--ink)] mb-1">Nama Unit Kerja <span class="text-red-500">*</span></label>
                            <input type="text" id="inputUnitKerja" placeholder="Contoh: MA Bandar Panasqq" class="w-full px-4 py-2 bg-[var(--paper-soft)] border border-[var(--line)] rounded-xl text-[var(--ink)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)]">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-[var(--ink)] mb-1">Nama Atasan <span class="text-red-500">*</span></label>
                            <input type="text" id="inputSupervisorName" placeholder="Contoh: H. HELMIZULDI S.Ag., M.Pd.I." class="w-full px-4 py-2 bg-[var(--paper-soft)] border border-[var(--line)] rounded-xl text-[var(--ink)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)]">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-[var(--ink)] mb-1">NIP Atasan <span class="text-red-500">*</span></label>
                            <input type="text" id="inputSupervisorNip" placeholder="Contoh: 197108101996031002" class="w-full px-4 py-2 bg-[var(--paper-soft)] border border-[var(--line)] rounded-xl text-[var(--ink)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)]">
                        </div>
                    </div>

                    <div class="flex gap-3 mt-6">
                        <button type="button" onclick="closeSupervisorModal()" class="flex-1 py-3 bg-[var(--paper-soft)] hover:bg-[var(--line)] text-[var(--ink)] font-semibold rounded-xl transition-all">
                            Batal
                        </button>
                        <button type="button" onclick="saveSupervisor()" class="flex-1 py-3 bg-[var(--gold)] hover:bg-[var(--gold-bright)] text-white font-bold rounded-xl transition-all">
                            Simpan
                        </button>
                    </div>
                </div>
            </div>
        @endif

    <script>
        var locationDetected = false;

        // Try to get location silently on page load
        document.addEventListener('DOMContentLoaded', function() {
            tryGetLocation();
        });

        function tryGetLocation() {
            var locationText = document.getElementById('locationText');
            if (!locationText) return;

            locationText.innerHTML = '<span class="text-[var(--ink-soft)]">Mendeteksi lokasi...</span>';

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        var lat = position.coords.latitude;
                        var lon = position.coords.longitude;
                        document.getElementById('latitude').value = lat;
                        document.getElementById('longitude').value = lon;
                        locationDetected = true;
                        locationText.innerHTML = '<span class="text-[var(--ink)] font-semibold text-sm">✓ Lokasi terdeteksi</span>';
                        document.getElementById('distanceInfo').classList.remove('hidden');
                        document.getElementById('distanceText').innerHTML = 'Jarak dari kantor akan dihitung otomatis';

                        // Reverse geocoding untuk mendapatkan alamat
                        reverseGeocode(lat, lon);
                    },
                    function(error) {
                        console.log('GPS error:', error.message);
                        locationDetected = false;
                        document.getElementById('latitude').value = '0';
                        document.getElementById('longitude').value = '0';
                        locationText.innerHTML = '<span class="text-[var(--ink-soft)] text-sm">Lokasi tidak tersedia</span>';
                    },
                    {
                        enableHighAccuracy: true,
                        timeout: 15000,
                        maximumAge: 60000
                    }
                );
            } else {
                locationDetected = false;
                document.getElementById('latitude').value = '0';
                document.getElementById('longitude').value = '0';
                locationText.innerHTML = '<span class="text-[var(--ink-soft)] text-sm">GPS tidak tersedia</span>';
            }
        }

        // Reverse geocoding menggunakan OpenStreetMap Nominatim (gratis)
        function reverseGeocode(lat, lon) {
            var alamatInput = document.getElementById('alamat');
            var locationText = document.getElementById('locationText');

            // Tampilkan loading
            locationText.innerHTML = '<span class="text-[var(--ink)] font-semibold text-sm">✓ Lokasi terdeteksi</span>' +
                '<div class="mt-2 text-xs text-[var(--ink-soft)]">Mengambil alamat...</div>';

            // Fetch alamat dari Nominatim
            fetch('https://nominatim.openstreetmap.org/reverse?format=json&lat=' + lat + '&lon=' + lon + '&zoom=18&addressdetails=1', {
                headers: {
                    'Accept-Language': 'id' // Bahasa Indonesia
                }
            })
            .then(function(response) {
                return response.json();
            })
            .then(function(data) {
                if (data && data.display_name) {
                    var alamat = data.display_name;
                    alamatInput.value = alamat;

                    // Format alamat untuk display (ambil bagian penting saja)
                    var alamatShort = alamat.length > 80 ? alamat.substring(0, 80) + '...' : alamat;

                    locationText.innerHTML = '<span class="text-[var(--ink)] font-semibold text-sm">✓ Lokasi terdeteksi</span>' +
                        '<div class="mt-2 text-xs text-[var(--ink-soft)] leading-relaxed">' +
                            '<svg class="w-3 h-3 inline text-[var(--gold)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">' +
                                '<path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>' +
                            '</svg>' +
                            '<span class="font-medium text-[var(--ink)]">' + alamatShort + '</span>' +
                        '</div>';
                } else {
                    alamatInput.value = 'Koordinat: ' + lat + ', ' + lon;
                    locationText.innerHTML = '<span class="text-[var(--ink)] font-semibold text-sm">✓ Lokasi terdeteksi</span>' +
                        '<div class="mt-2 text-xs text-[var(--ink-soft)]">Koordinat: ' + lat.toFixed(6) + ', ' + lon.toFixed(6) + '</div>';
                }
            })
            .catch(function(error) {
                console.log('Geocoding error:', error);
                alamatInput.value = 'Koordinat: ' + lat + ', ' + lon;
                locationText.innerHTML = '<span class="text-[var(--ink)] font-semibold text-sm">✓ Lokasi terdeteksi</span>' +
                    '<div class="mt-2 text-xs text-[var(--ink-soft)]">Koordinat: ' + lat.toFixed(6) + ', ' + lon.toFixed(6) + '</div>';
            });
        }

        function updateSelectedJenis() {
            var radios = document.querySelectorAll('input[name="jenis"]');
            radios.forEach(function(radio) {
                var label = radio.closest('label');
                var checkIcon = label.querySelector('.check-icon');
                if (radio.checked) {
                    label.classList.add('border-[var(--gold)]', 'bg-[var(--gold)]/5');
                    label.classList.remove('border-[var(--line)]');
                    checkIcon.classList.remove('hidden');
                } else {
                    label.classList.remove('border-[var(--gold)]', 'bg-[var(--gold)]/5');
                    label.classList.add('border-[var(--line)]');
                    checkIcon.classList.add('hidden');
                }
            });
        }

        function updateSelectedAlasan() {
            var radios = document.querySelectorAll('input[name="alasan"]');
            var keteranganField = document.getElementById('keteranganTugasLuar');

            radios.forEach(function(radio) {
                var label = radio.closest('label');
                var checkIcon = label.querySelector('.check-icon');
                if (radio.checked) {
                    label.classList.add('border-[var(--gold)]', 'bg-[var(--gold)]/5');
                    label.classList.remove('border-[var(--line)]');
                    checkIcon.classList.remove('hidden');
                } else {
                    label.classList.remove('border-[var(--gold)]', 'bg-[var(--gold)]/5');
                    label.classList.add('border-[var(--line)]');
                    checkIcon.classList.add('hidden');
                }
            });

            // Tampilkan/sembunyikan field keterangan
            var selectedAlasan = document.querySelector('input[name="alasan"]:checked');
            if (selectedAlasan && selectedAlasan.value === 'TUGAS_LUAR') {
                keteranganField.classList.remove('hidden');
            } else {
                keteranganField.classList.add('hidden');
                document.getElementById('inputKeterangan').value = '';
            }
        }

        function validateForm() {
            var jenis = document.querySelector('input[name="jenis"]:checked');
            if (!jenis) {
                alert('Harap pilih jenis presensi (Masuk atau Pulang)');
                return false;
            }

            var alasan = document.querySelector('input[name="alasan"]:checked');
            if (!alasan) {
                alert('Harap pilih alasan presensi error (Sistem Error atau Tugas Luar)');
                return false;
            }

            if (alasan.value === 'TUGAS_LUAR') {
                var keterangan = document.getElementById('inputKeterangan').value.trim();
                if (!keterangan) {
                    alert('Harap isi keterangan tugas luar');
                    return false;
                }
            }

            var foto = document.getElementById('foto').value;
            if (!foto) {
                alert('Harap ambil foto terlebih dahulu sebagai bukti kehadiran');
                return false;
            }

            // Cek apakah perlu input atasan (dept 998/999)
            var deptId = {{ $user->dept_id }};
            if (deptId == 998 || deptId == 999) {
                var supervisorName = document.getElementById('supervisor_name').value;
                if (!supervisorName) {
                    document.getElementById('supervisorModal').style.display = 'flex';
                    return false;
                }
            }

            // Show loading
            document.getElementById('submitText').classList.add('hidden');
            document.getElementById('submitSpinner').classList.remove('hidden');
            document.getElementById('submitBtn').disabled = true;

            return true;
        }

        function closeSupervisorModal() {
            document.getElementById('supervisorModal').style.display = 'none';
        }

        function saveSupervisor() {
            var unitKerja = document.getElementById('inputUnitKerja').value.trim();
            var name = document.getElementById('inputSupervisorName').value.trim();
            var nip = document.getElementById('inputSupervisorNip').value.trim();

            if (!unitKerja || !name || !nip) {
                alert('Semua field wajib diisi');
                return;
            }

            document.getElementById('unit_kerja_manual').value = unitKerja;
            document.getElementById('supervisor_name').value = name;
            document.getElementById('supervisor_nip').value = nip;

            document.getElementById('supervisorModal').style.display = 'none';

            // Submit form
            document.getElementById('presensiErrorForm').submit();
        }

        function clearPhoto() {
            document.getElementById('photoPreview').classList.add('hidden');
            document.getElementById('photoImg').src = '';
            document.getElementById('foto').value = '';
        }

        function takePhoto() {
            if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                openCamera();
            } else {
                var input = document.createElement('input');
                input.type = 'file';
                input.accept = 'image/*';
                input.capture = 'environment';

                input.onchange = function(e) {
                    processFile(e.target.files[0]);
                };
                input.click();
            }
        }

        var currentStream = null;
        var currentFacingMode = 'environment';

        function openCamera() {
            var modal = document.createElement('div');
            modal.id = 'cameraModal';
            modal.style.cssText = 'position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.9);z-index:9999;display:flex;flex-direction:column;align-items:center;justify-content:center;';

            var video = document.createElement('video');
            video.id = 'cameraVideo';
            video.style.cssText = 'max-width:100%;max-height:65vh;border-radius:8px;';
            video.setAttribute('autoplay', '');

            var btnContainer = document.createElement('div');
            btnContainer.style.cssText = 'margin-top:20px;display:flex;gap:15px;justify-content:center;flex-wrap:wrap;';

            var switchBtn = document.createElement('button');
            switchBtn.id = 'switchCameraBtn';
            switchBtn.textContent = 'Ganti Kamera';
            switchBtn.style.cssText = 'padding:12px 20px;background:#3b82f6;color:white;border:none;border-radius:8px;font-size:14px;cursor:pointer;font-weight:bold;';

            var captureBtn = document.createElement('button');
            captureBtn.textContent = 'Ambil Foto';
            captureBtn.style.cssText = 'padding:12px 20px;background:#22c55e;color:white;border:none;border-radius:8px;font-size:14px;cursor:pointer;font-weight:bold;';

            var cancelBtn = document.createElement('button');
            cancelBtn.textContent = 'Batal';
            cancelBtn.style.cssText = 'padding:12px 20px;background:#ef4444;color:white;border:none;border-radius:8px;font-size:14px;cursor:pointer;font-weight:bold;';

            btnContainer.appendChild(switchBtn);
            btnContainer.appendChild(captureBtn);
            btnContainer.appendChild(cancelBtn);
            modal.appendChild(video);
            modal.appendChild(btnContainer);
            document.body.appendChild(modal);

            startCamera(currentFacingMode);

            switchBtn.onclick = function() {
                currentFacingMode = currentFacingMode === 'user' ? 'environment' : 'user';
                startCamera(currentFacingMode);
            };

            captureBtn.onclick = function() {
                var canvas = document.createElement('canvas');
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                canvas.getContext('2d').drawImage(video, 0, 0);

                var compressed = canvas.toDataURL('image/jpeg', 0.7);

                document.getElementById('photoPreview').classList.remove('hidden');
                document.getElementById('photoImg').src = compressed;
                document.getElementById('foto').value = compressed;

                if (currentStream) {
                    currentStream.getTracks().forEach(function(track) { track.stop(); });
                }
                modal.remove();
            };

            cancelBtn.onclick = function() {
                if (currentStream) {
                    currentStream.getTracks().forEach(function(track) { track.stop(); });
                }
                modal.remove();
            };
        }

        function startCamera(facingMode) {
            if (currentStream) {
                currentStream.getTracks().forEach(function(track) { track.stop(); });
            }

            navigator.mediaDevices.getUserMedia({ video: { facingMode: facingMode } })
                .then(function(stream) {
                    currentStream = stream;
                    var video = document.getElementById('cameraVideo');
                    if (video) {
                        video.srcObject = stream;
                        video.play();
                    }
                })
                .catch(function(err) {
                    alert('Tidak dapat mengakses kamera: ' + err.message);
                    var modal = document.getElementById('cameraModal');
                    if (modal) modal.remove();
                });
        }

        function processFile(file) {
            if (!file || !file.type.startsWith('image/')) {
                alert('Hanya file gambar yang diperbolehkan');
                return;
            }

            var reader = new FileReader();
            reader.onload = function(event) {
                var img = new Image();
                img.onload = function() {
                    var canvas = document.createElement('canvas');
                    var maxSize = 800;
                    var width = img.width;
                    var height = img.height;

                    if (width > height) {
                        if (width > maxSize) { height *= maxSize / width; width = maxSize; }
                    } else {
                        if (height > maxSize) { width *= maxSize / height; height = maxSize; }
                    }

                    canvas.width = width;
                    canvas.height = height;
                    canvas.getContext('2d').drawImage(img, 0, 0, width, height);

                    var compressed = canvas.toDataURL('image/jpeg', 0.7);
                    document.getElementById('photoPreview').classList.remove('hidden');
                    document.getElementById('photoImg').src = compressed;
                    document.getElementById('foto').value = compressed;
                };
                img.src = event.target.result;
            };
            reader.readAsDataURL(file);
        }

        // Modal Surat Keterangan
        function showSuratModal() {
            @if(session('suratUrl'))
                document.getElementById('suratModal').style.display = 'flex';
            @endif
        }

        function closeSuratModal() {
            document.getElementById('suratModal').style.display = 'none';
        }

        function downloadSurat() {
            @if(session('suratUrl'))
                var link = document.createElement('a');
                link.href = '{{ session("suratUrl") }}';
                link.download = 'surat-keterangan-pengaduan-presensi.pdf';
                link.target = '_blank';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            @endif
        }

        function openSuratInNewTab() {
            @if(session('suratUrl'))
                window.open('{{ session("suratUrl") }}', '_blank');
            @endif
        }

        // Tampilkan modal saat halaman dimuat dengan success
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('suratUrl'))
                setTimeout(function() {
                    showSuratModal();
                }, 500);
            @endif
        });
    </script>

    {{-- Modal Surat Keterangan --}}
    @if(session('suratUrl'))
        <div id="suratModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
            <div class="bg-white rounded-2xl w-full max-w-4xl h-[90vh] flex flex-col overflow-hidden shadow-2xl">
                {{-- Header Modal --}}
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-[var(--gold)] to-[var(--gold-bright)]">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-white">Surat Keterangan Pengaduan Presensi</h3>
                            <p class="text-xs text-white/80">Preview surat keterangan Anda</p>
                        </div>
                    </div>
                    <button onclick="closeSuratModal()" class="w-10 h-10 bg-white/20 hover:bg-white/30 rounded-xl flex items-center justify-center transition-colors">
                        <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{-- PDF Preview --}}
                <div class="flex-1 overflow-hidden bg-gray-100">
                    <iframe src="{{ session('suratUrl') }}" class="w-full h-full border-0"></iframe>
                </div>

                {{-- Footer Modal --}}
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex items-center justify-between">
                    <p class="text-sm text-gray-600">
                        <svg class="w-4 h-4 inline text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        PDF ini juga dapat diunduh atau dibuka di tab baru
                    </p>
                    <div class="flex items-center gap-3">
                        <button onclick="openSuratInNewTab()" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-xl transition-colors flex items-center gap-2">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                            Buka di Tab Baru
                        </button>
                        <button onclick="downloadSurat()" class="px-6 py-2 bg-[var(--gold)] hover:bg-[var(--gold-bright)] text-white font-bold rounded-xl shadow-md transition-all flex items-center gap-2">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Download PDF
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</x-layouts.app>
