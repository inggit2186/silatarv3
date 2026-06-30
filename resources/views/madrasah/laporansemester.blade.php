<x-layouts.app title="Laporan Semester Madrasah - SILATAR">
    <main class="min-h-screen py-8 px-4 md:px-8 relative">

        <div class="max-w-7xl mx-auto relative z-10">
            <div class="flex items-center gap-4 mb-6">
                <a href="/madrasah/profil" class="inline-flex items-center gap-2 rounded-xl border border-slate-600/50 bg-slate-800/50 px-4 py-2 font-mono text-sm text-slate-400 transition-all hover:bg-slate-800 hover:text-white">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Kembali ke Profil
                </a>
            </div>

            <!-- Hero Header -->
            <div class="text-center mb-8">
                <h1 class="font-mono text-3xl md:text-4xl font-black uppercase tracking-wider text-white mb-3">
                    Laporan Semester <span class="bg-gradient-to-r from-teal-400 to-cyan-400 bg-clip-text text-transparent">{{ $deptName }}</span>
                </h1>
                <p class="text-slate-400 max-w-xl mx-auto">Form input laporan semester untuk keperluan pelaporan madrasah.</p>
            </div>

            <!-- Tab Navigation -->
            <div class="flex items-center justify-center gap-2 mb-8">
                <a href="{{ route('madrasah.profil') }}" class="flex items-center gap-2 px-6 py-3 rounded-xl border bg-slate-800/80 text-slate-400 border-slate-700 hover:bg-slate-700/80 hover:text-white font-mono text-sm font-semibold uppercase tracking-wider transition-all duration-300">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    Profil Madrasah
                </a>
                <a href="{{ route('madrasah.pegawai') }}" class="flex items-center gap-2 px-6 py-3 rounded-xl border bg-slate-800/80 text-slate-400 border-slate-700 hover:bg-slate-700/80 hover:text-white font-mono text-sm font-semibold uppercase tracking-wider transition-all duration-300">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Pegawai Madrasah
                </a>
                <a href="{{ route('madrasah.guru') }}" class="flex items-center gap-2 px-6 py-3 rounded-xl border bg-slate-800/80 text-slate-400 border-slate-700 hover:bg-slate-700/80 hover:text-white font-mono text-sm font-semibold uppercase tracking-wider transition-all duration-300">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    Guru Madrasah
                </a>
                <div class="flex items-center gap-2 px-6 py-3 rounded-xl border bg-gradient-to-r from-teal-600 to-cyan-600 text-white border-teal-500/50 shadow-lg shadow-teal-500/20 font-mono text-sm font-semibold uppercase tracking-wider transition-all duration-300">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Laporan Semester
                </div>
            </div>

            <form action="{{ route('madrasah.laporan-semester.save') }}" method="POST">
                @csrf
                <input type="hidden" name="semester" x-model="selectedSemester">
                <input type="hidden" name="tahun_ajaran" x-model="tahunAjaran">
                <input type="hidden" name="status" value="draft">

                <!-- Grid 2: Keadaan Gedung & Sarana -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    <!-- Keadaan Gedung -->
                    <div class="bg-gradient-to-br from-slate-900/90 to-slate-900/50 backdrop-blur-xl rounded-2xl border border-teal-500/20 shadow-xl overflow-hidden">
                        <div class="bg-gradient-to-r from-teal-600/20 to-cyan-600/20 px-5 py-4 border-b border-teal-500/20">
                            <h3 class="font-mono text-sm font-bold text-white uppercase tracking-wider">A. Keadaan Gedung</h3>
                        </div>
                        <div class="p-4 overflow-x-auto">
                            <table class="w-full text-xs">
                                <thead>
                                    <tr class="border-b border-slate-700/50">
                                        <th class="text-left py-2 px-2 text-slate-400 font-mono">Gedung</th>
                                        <th class="text-center py-2 px-1 text-slate-400 font-mono w-12">Baik</th>
                                        <th class="text-center py-2 px-1 text-slate-400 font-mono w-12">Ringan</th>
                                        <th class="text-center py-2 px-1 text-slate-400 font-mono w-12">Sedang</th>
                                        <th class="text-center py-2 px-1 text-slate-400 font-mono w-12">Berat</th>
                                        <th class="text-center py-2 px-1 text-slate-400 font-mono w-10">Jml</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($formData['keadaanGedung'] as $i => $row)
                                    <tr class="border-b border-slate-700/30">
                                        <td class="py-1 px-2 text-white">{{ $row['label'] }}</td>
                                        <td class="py-1 px-1"><input type="number" name="keadaanGedung[{{ $i }}][baik]" value="{{ $row['baik'] ?? 0 }}" min="0" class="w-full bg-slate-800/50 border border-slate-600/50 rounded px-2 py-1 text-white text-center text-xs"></td>
                                        <td class="py-1 px-1"><input type="number" name="keadaanGedung[{{ $i }}][ringan]" value="{{ $row['ringan'] ?? 0 }}" min="0" class="w-full bg-slate-800/50 border border-slate-600/50 rounded px-2 py-1 text-white text-center text-xs"></td>
                                        <td class="py-1 px-1"><input type="number" name="keadaanGedung[{{ $i }}][sedang]" value="{{ $row['sedang'] ?? 0 }}" min="0" class="w-full bg-slate-800/50 border border-slate-600/50 rounded px-2 py-1 text-white text-center text-xs"></td>
                                        <td class="py-1 px-1"><input type="number" name="keadaanGedung[{{ $i }}][berat]" value="{{ $row['berat'] ?? 0 }}" min="0" class="w-full bg-slate-800/50 border border-slate-600/50 rounded px-2 py-1 text-white text-center text-xs"></td>
                                        <td class="py-1 px-1 text-center text-teal-400 font-mono">{{ ($row['baik'] ?? 0) + ($row['ringan'] ?? 0) + ($row['sedang'] ?? 0) + ($row['berat'] ?? 0) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Sarana Pendidikan -->
                    <div class="bg-gradient-to-br from-slate-900/90 to-slate-900/50 backdrop-blur-xl rounded-2xl border border-violet-500/20 shadow-xl overflow-hidden">
                        <div class="bg-gradient-to-r from-violet-600/20 to-purple-600/20 px-5 py-4 border-b border-violet-500/20">
                            <h3 class="font-mono text-sm font-bold text-white uppercase tracking-wider">Keadaan Sarana Pendidikan</h3>
                        </div>
                        <div class="p-4 overflow-x-auto">
                            <table class="w-full text-xs">
                                <thead>
                                    <tr class="border-b border-slate-700/50">
                                        <th class="text-left py-2 px-2 text-slate-400 font-mono">Sarana</th>
                                        <th class="text-center py-2 px-1 text-slate-400 font-mono w-12">Baik</th>
                                        <th class="text-center py-2 px-1 text-slate-400 font-mono w-12">Ringan</th>
                                        <th class="text-center py-2 px-1 text-slate-400 font-mono w-12">Sedang</th>
                                        <th class="text-center py-2 px-1 text-slate-400 font-mono w-12">Berat</th>
                                        <th class="text-center py-2 px-1 text-slate-400 font-mono w-10">Jml</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($formData['saranaPendidikan'] as $i => $row)
                                    <tr class="border-b border-slate-700/30">
                                        <td class="py-1 px-2 text-white">{{ $row['label'] }}</td>
                                        <td class="py-1 px-1"><input type="number" name="saranaPendidikan[{{ $i }}][baik]" value="{{ $row['baik'] ?? 0 }}" min="0" class="w-full bg-slate-800/50 border border-slate-600/50 rounded px-2 py-1 text-white text-center text-xs"></td>
                                        <td class="py-1 px-1"><input type="number" name="saranaPendidikan[{{ $i }}][ringan]" value="{{ $row['ringan'] ?? 0 }}" min="0" class="w-full bg-slate-800/50 border border-slate-600/50 rounded px-2 py-1 text-white text-center text-xs"></td>
                                        <td class="py-1 px-1"><input type="number" name="saranaPendidikan[{{ $i }}][sedang]" value="{{ $row['sedang'] ?? 0 }}" min="0" class="w-full bg-slate-800/50 border border-slate-600/50 rounded px-2 py-1 text-white text-center text-xs"></td>
                                        <td class="py-1 px-1"><input type="number" name="saranaPendidikan[{{ $i }}][berat]" value="{{ $row['berat'] ?? 0 }}" min="0" class="w-full bg-slate-800/50 border border-slate-600/50 rounded px-2 py-1 text-white text-center text-xs"></td>
                                        <td class="py-1 px-1 text-center text-violet-400 font-mono">{{ ($row['baik'] ?? 0) + ($row['ringan'] ?? 0) + ($row['sedang'] ?? 0) + ($row['berat'] ?? 0) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Grid 2: Bantuan Pemerintah & Non Pemerintah -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    <!-- Bantuan Pemerintah -->
                    <div class="bg-gradient-to-br from-slate-900/90 to-slate-900/50 backdrop-blur-xl rounded-2xl border border-emerald-500/20 shadow-xl overflow-hidden">
                        <div class="bg-gradient-to-r from-emerald-600/20 to-green-600/20 px-5 py-4 border-b border-emerald-500/20">
                            <h3 class="font-mono text-sm font-bold text-white uppercase tracking-wider">Jenis Bantuan dari Pemerintah</h3>
                        </div>
                        <div class="p-4">
                            <table class="w-full text-xs">
                                <thead>
                                    <tr class="border-b border-slate-700/50">
                                        <th class="text-left py-2 px-2 text-slate-400 font-mono">Jenis Bantuan</th>
                                        <th class="text-center py-2 px-1 text-slate-400 font-mono">Diterima</th>
                                        <th class="text-center py-2 px-1 text-slate-400 font-mono">Terserap</th>
                                        <th class="text-center py-2 px-1 text-slate-400 font-mono">Saldo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $totDiterima = 0; $totTerserap = 0; @endphp
                                    @foreach($formData['bantuanPemerintah'] as $i => $row)
                                    @php $totDiterima += $row['diterima'] ?? 0; $totTerserap += $row['terserap'] ?? 0; @endphp
                                    <tr class="border-b border-slate-700/30">
                                        <td class="py-1 px-2 text-white">{{ $row['label'] }}</td>
                                        <td class="py-1 px-1"><input type="number" name="bantuanPemerintah[{{ $i }}][diterima]" value="{{ $row['diterima'] ?? 0 }}" min="0" class="w-full bg-slate-800/50 border border-slate-600/50 rounded px-2 py-1 text-white text-center text-xs"></td>
                                        <td class="py-1 px-1"><input type="number" name="bantuanPemerintah[{{ $i }}][terserap]" value="{{ $row['terserap'] ?? 0 }}" min="0" class="w-full bg-slate-800/50 border border-slate-600/50 rounded px-2 py-1 text-white text-center text-xs"></td>
                                        <td class="py-1 px-1 text-center text-emerald-400 font-mono">{{ max(0, ($row['diterima'] ?? 0) - ($row['terserap'] ?? 0)) }}</td>
                                    </tr>
                                    @endforeach
                                    <tr class="bg-slate-800/50 font-bold">
                                        <td class="py-2 px-2 text-white">Jumlah</td>
                                        <td class="py-2 px-1 text-center text-emerald-400">{{ $totDiterima }}</td>
                                        <td class="py-2 px-1 text-center text-emerald-400">{{ $totTerserap }}</td>
                                        <td class="py-2 px-1 text-center text-emerald-400">{{ max(0, $totDiterima - $totTerserap) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Bantuan Non Pemerintah -->
                    <div class="bg-gradient-to-br from-slate-900/90 to-slate-900/50 backdrop-blur-xl rounded-2xl border border-amber-500/20 shadow-xl overflow-hidden">
                        <div class="bg-gradient-to-r from-amber-600/20 to-orange-600/20 px-5 py-4 border-b border-amber-500/20">
                            <h3 class="font-mono text-sm font-bold text-white uppercase tracking-wider">Jenis Bantuan Non Pemerintah</h3>
                        </div>
                        <div class="p-4">
                            <table class="w-full text-xs">
                                <thead>
                                    <tr class="border-b border-slate-700/50">
                                        <th class="text-left py-2 px-2 text-slate-400 font-mono">Jenis Bantuan</th>
                                        <th class="text-center py-2 px-1 text-slate-400 font-mono">Diterima</th>
                                        <th class="text-center py-2 px-1 text-slate-400 font-mono">Terserap</th>
                                        <th class="text-center py-2 px-1 text-slate-400 font-mono">Saldo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $totDiterima2 = 0; $totTerserap2 = 0; @endphp
                                    @foreach($formData['bantuanNonPemerintah'] as $i => $row)
                                    @php $totDiterima2 += $row['diterima'] ?? 0; $totTerserap2 += $row['terserap'] ?? 0; @endphp
                                    <tr class="border-b border-slate-700/30">
                                        <td class="py-1 px-2 text-white">{{ $row['label'] }}</td>
                                        <td class="py-1 px-1"><input type="number" name="bantuanNonPemerintah[{{ $i }}][diterima]" value="{{ $row['diterima'] ?? 0 }}" min="0" class="w-full bg-slate-800/50 border border-slate-600/50 rounded px-2 py-1 text-white text-center text-xs"></td>
                                        <td class="py-1 px-1"><input type="number" name="bantuanNonPemerintah[{{ $i }}][terserap]" value="{{ $row['terserap'] ?? 0 }}" min="0" class="w-full bg-slate-800/50 border border-slate-600/50 rounded px-2 py-1 text-white text-center text-xs"></td>
                                        <td class="py-1 px-1 text-center text-amber-400 font-mono">{{ max(0, ($row['diterima'] ?? 0) - ($row['terserap'] ?? 0)) }}</td>
                                    </tr>
                                    @endforeach
                                    <tr class="bg-slate-800/50 font-bold">
                                        <td class="py-2 px-2 text-white">Jumlah</td>
                                        <td class="py-2 px-1 text-center text-amber-400">{{ $totDiterima2 }}</td>
                                        <td class="py-2 px-1 text-center text-amber-400">{{ $totTerserap2 }}</td>
                                        <td class="py-2 px-1 text-center text-amber-400">{{ max(0, $totDiterima2 - $totTerserap2) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Grid 2: Data Guru/Pegawai & Tingkat Pendidikan -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    <!-- Data Guru/Pegawai -->
                    <div class="bg-gradient-to-br from-slate-900/90 to-slate-900/50 backdrop-blur-xl rounded-2xl border border-rose-500/20 shadow-xl overflow-hidden">
                        <div class="bg-gradient-to-r from-rose-600/20 to-pink-600/20 px-5 py-4 border-b border-rose-500/20">
                            <h3 class="font-mono text-sm font-bold text-white uppercase tracking-wider">Data Guru / Pegawai</h3>
                        </div>
                        <div class="p-4 overflow-x-auto">
                            <table class="w-full text-xs">
                                <thead>
                                    <tr class="border-b border-slate-700/50">
                                        <th class="text-left py-2 px-2 text-slate-400 font-mono">Uraian</th>
                                        <th class="text-center py-2 px-1 text-slate-400 font-mono w-12">L</th>
                                        <th class="text-center py-2 px-1 text-slate-400 font-mono w-12">P</th>
                                        <th class="text-center py-2 px-1 text-slate-400 font-mono w-12">Jml</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $totL = 0; $totP = 0; @endphp
                                    @foreach($formData['dataGuruPegawai'] as $i => $row)
                                    @php $totL += $row['l'] ?? 0; $totP += $row['p'] ?? 0; @endphp
                                    <tr class="border-b border-slate-700/30">
                                        <td class="py-1 px-2 text-white">{{ $row['label'] }}</td>
                                        <td class="py-1 px-1"><input type="number" name="dataGuruPegawai[{{ $i }}][l]" value="{{ $row['l'] ?? 0 }}" min="0" class="w-full bg-slate-800/50 border border-slate-600/50 rounded px-2 py-1 text-white text-center text-xs"></td>
                                        <td class="py-1 px-1"><input type="number" name="dataGuruPegawai[{{ $i }}][p]" value="{{ $row['p'] ?? 0 }}" min="0" class="w-full bg-slate-800/50 border border-slate-600/50 rounded px-2 py-1 text-white text-center text-xs"></td>
                                        <td class="py-1 px-1 text-center text-rose-400 font-mono">{{ ($row['l'] ?? 0) + ($row['p'] ?? 0) }}</td>
                                    </tr>
                                    @endforeach
                                    <tr class="bg-slate-800/50 font-bold">
                                        <td class="py-2 px-2 text-white">Jumlah</td>
                                        <td class="py-2 px-1 text-center text-rose-400">{{ $totL }}</td>
                                        <td class="py-2 px-1 text-center text-rose-400">{{ $totP }}</td>
                                        <td class="py-2 px-1 text-center text-rose-400">{{ $totL + $totP }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Tingkat Pendidikan -->
                    <div class="bg-gradient-to-br from-slate-900/90 to-slate-900/50 backdrop-blur-xl rounded-2xl border border-cyan-500/20 shadow-xl overflow-hidden">
                        <div class="bg-gradient-to-r from-cyan-600/20 to-blue-600/20 px-5 py-4 border-b border-cyan-500/20">
                            <h3 class="font-mono text-sm font-bold text-white uppercase tracking-wider">Tingkat Pendidikan</h3>
                        </div>
                        <div class="p-4 overflow-x-auto">
                            <table class="w-full text-xs">
                                <thead>
                                    <tr class="border-b border-slate-700/50">
                                        <th class="text-left py-2 px-2 text-slate-400 font-mono">Tingkat</th>
                                        <th class="text-center py-2 px-1 text-slate-400 font-mono w-12">L</th>
                                        <th class="text-center py-2 px-1 text-slate-400 font-mono w-12">P</th>
                                        <th class="text-center py-2 px-1 text-slate-400 font-mono w-12">Jml</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $totLTp = 0; $totPTp = 0; @endphp
                                    @foreach($formData['tingkatPendidikan'] as $i => $row)
                                    @php $totLTp += $row['l'] ?? 0; $totPTp += $row['p'] ?? 0; @endphp
                                    <tr class="border-b border-slate-700/30">
                                        <td class="py-1 px-2 text-white">{{ $row['label'] }}</td>
                                        <td class="py-1 px-1"><input type="number" name="tingkatPendidikan[{{ $i }}][l]" value="{{ $row['l'] ?? 0 }}" min="0" class="w-full bg-slate-800/50 border border-slate-600/50 rounded px-2 py-1 text-white text-center text-xs"></td>
                                        <td class="py-1 px-1"><input type="number" name="tingkatPendidikan[{{ $i }}][p]" value="{{ $row['p'] ?? 0 }}" min="0" class="w-full bg-slate-800/50 border border-slate-600/50 rounded px-2 py-1 text-white text-center text-xs"></td>
                                        <td class="py-1 px-1 text-center text-cyan-400 font-mono">{{ ($row['l'] ?? 0) + ($row['p'] ?? 0) }}</td>
                                    </tr>
                                    @endforeach
                                    <tr class="bg-slate-800/50 font-bold">
                                        <td class="py-2 px-2 text-white">Jumlah</td>
                                        <td class="py-2 px-1 text-center text-cyan-400">{{ $totLTp }}</td>
                                        <td class="py-2 px-1 text-center text-cyan-400">{{ $totPTp }}</td>
                                        <td class="py-2 px-1 text-center text-cyan-400">{{ $totLTp + $totPTp }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Grid 2: Sertifikasi & Absensi -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    <!-- Sertifikasi -->
                    <div class="bg-gradient-to-br from-slate-900/90 to-slate-900/50 backdrop-blur-xl rounded-2xl border border-indigo-500/20 shadow-xl overflow-hidden">
                        <div class="bg-gradient-to-r from-indigo-600/20 to-violet-600/20 px-5 py-4 border-b border-indigo-500/20">
                            <h3 class="font-mono text-sm font-bold text-white uppercase tracking-wider">Sertifikasi</h3>
                        </div>
                        <div class="p-4 overflow-x-auto">
                            <table class="w-full text-xs">
                                <thead>
                                    <tr class="border-b border-slate-700/50">
                                        <th class="text-left py-2 px-2 text-slate-400 font-mono">Kategori</th>
                                        <th class="text-center py-2 px-1 text-slate-400 font-mono w-12">L</th>
                                        <th class="text-center py-2 px-1 text-slate-400 font-mono w-12">P</th>
                                        <th class="text-center py-2 px-1 text-slate-400 font-mono w-12">Jml</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $totLSert = 0; $totPSert = 0; @endphp
                                    @foreach($formData['sertifikasi'] as $i => $row)
                                    @php $totLSert += $row['l'] ?? 0; $totPSert += $row['p'] ?? 0; @endphp
                                    <tr class="border-b border-slate-700/30">
                                        <td class="py-1 px-2 text-white">{{ $row['label'] }}</td>
                                        <td class="py-1 px-1"><input type="number" name="sertifikasi[{{ $i }}][l]" value="{{ $row['l'] ?? 0 }}" min="0" class="w-full bg-slate-800/50 border border-slate-600/50 rounded px-2 py-1 text-white text-center text-xs"></td>
                                        <td class="py-1 px-1"><input type="number" name="sertifikasi[{{ $i }}][p]" value="{{ $row['p'] ?? 0 }}" min="0" class="w-full bg-slate-800/50 border border-slate-600/50 rounded px-2 py-1 text-white text-center text-xs"></td>
                                        <td class="py-1 px-1 text-center text-indigo-400 font-mono">{{ ($row['l'] ?? 0) + ($row['p'] ?? 0) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Absensi Siswa -->
                    <div class="bg-gradient-to-br from-slate-900/90 to-slate-900/50 backdrop-blur-xl rounded-2xl border border-orange-500/20 shadow-xl overflow-hidden">
                        <div class="bg-gradient-to-r from-orange-600/20 to-yellow-600/20 px-5 py-4 border-b border-orange-500/20">
                            <h3 class="font-mono text-sm font-bold text-white uppercase tracking-wider">Kehadiran & Absensi</h3>
                        </div>
                        <div class="p-4">
                            <div class="mb-4">
                                <label class="block text-xs text-slate-400 font-mono mb-1">Banyak Hari Sekolah</label>
                                <input type="number" name="banyakHariSekolah" value="{{ $formData['banyakHariSekolah'] ?? 0 }}" min="0" class="w-full bg-slate-800/50 border border-slate-600/50 rounded-lg px-3 py-2 text-white">
                            </div>
                            <table class="w-full text-xs">
                                <thead>
                                    <tr class="border-b border-slate-700/50">
                                        <th class="text-left py-2 px-2 text-slate-400 font-mono">Keterangan</th>
                                        <th class="text-center py-2 px-1 text-slate-400 font-mono w-12">L</th>
                                        <th class="text-center py-2 px-1 text-slate-400 font-mono w-12">P</th>
                                        <th class="text-center py-2 px-1 text-slate-400 font-mono w-12">Jml</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $totLAbs = 0; $totPAbs = 0; @endphp
                                    @foreach($formData['absensiSiswa'] as $i => $row)
                                    @php $totLAbs += $row['l'] ?? 0; $totPAbs += $row['p'] ?? 0; @endphp
                                    <tr class="border-b border-slate-700/30">
                                        <td class="py-1 px-2 text-white">{{ $row['label'] }}</td>
                                        <td class="py-1 px-1"><input type="number" name="absensiSiswa[{{ $i }}][l]" value="{{ $row['l'] ?? 0 }}" min="0" class="w-full bg-slate-800/50 border border-slate-600/50 rounded px-2 py-1 text-white text-center text-xs"></td>
                                        <td class="py-1 px-1"><input type="number" name="absensiSiswa[{{ $i }}][p]" value="{{ $row['p'] ?? 0 }}" min="0" class="w-full bg-slate-800/50 border border-slate-600/50 rounded px-2 py-1 text-white text-center text-xs"></td>
                                        <td class="py-1 px-1 text-center text-orange-400 font-mono">{{ ($row['l'] ?? 0) + ($row['p'] ?? 0) }}</td>
                                    </tr>
                                    @endforeach
                                    <tr class="bg-slate-800/50 font-bold">
                                        <td class="py-2 px-2 text-white">Jumlah</td>
                                        <td class="py-2 px-1 text-center text-orange-400">{{ $totLAbs }}</td>
                                        <td class="py-2 px-1 text-center text-orange-400">{{ $totPAbs }}</td>
                                        <td class="py-2 px-1 text-center text-orange-400">{{ $totLAbs + $totPAbs }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Tanah & Sertifikat -->
                <div class="bg-gradient-to-br from-slate-900/90 to-slate-900/50 backdrop-blur-xl rounded-2xl border border-lime-500/20 shadow-xl overflow-hidden mb-6">
                    <div class="bg-gradient-to-r from-lime-600/20 to-green-600/20 px-5 py-4 border-b border-lime-500/20">
                        <h3 class="font-mono text-sm font-bold text-white uppercase tracking-wider">Tanah & Sertifikat Tanah</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                            @foreach($formData['luasTanah'] as $key => $value)
                            <div>
                                <label class="block text-xs text-slate-400 font-mono mb-1">{{ ucwords(str_replace('_', ' ', $key)) }} (m2)</label>
                                <input type="number" name="luasTanah[{{ $key }}]" value="{{ $value ?? 0 }}" min="0" class="w-full bg-slate-800/50 border border-slate-600/50 rounded-lg px-3 py-2 text-white">
                            </div>
                            @endforeach
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-xs text-slate-400 font-mono mb-1">Status Kepemilikan Tanah</label>
                                <input type="text" name="sertifikatTanah[statusKepemilikan]" value="{{ $formData['sertifikatTanah']['statusKepemilikan'] ?? '' }}" placeholder="Contoh: Milik Sendiri" class="w-full bg-slate-800/50 border border-slate-600/50 rounded-lg px-3 py-2 text-white">
                            </div>
                            <div>
                                <label class="block text-xs text-slate-400 font-mono mb-1">Nomor Sertifikat</label>
                                <input type="text" name="sertifikatTanah[nomor]" value="{{ $formData['sertifikatTanah']['nomor'] ?? '' }}" placeholder="Nomor sertifikat" class="w-full bg-slate-800/50 border border-slate-600/50 rounded-lg px-3 py-2 text-white">
                            </div>
                            <div>
                                <label class="block text-xs text-slate-400 font-mono mb-1">Tanggal Sertifikat</label>
                                <input type="date" name="sertifikatTanah[tanggal]" value="{{ $formData['sertifikatTanah']['tanggal'] ?? '' }}" class="w-full bg-slate-800/50 border border-slate-600/50 rounded-lg px-3 py-2 text-white">
                            </div>
                            <div>
                                <label class="block text-xs text-slate-400 font-mono mb-1">Luas Tanah Sertifikat (m2)</label>
                                <input type="number" name="sertifikatTanah[luas]" value="{{ $formData['sertifikatTanah']['luas'] ?? 0 }}" min="0" class="w-full bg-slate-800/50 border border-slate-600/50 rounded-lg px-3 py-2 text-white">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-4 mt-8">
                    <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-amber-600 to-orange-600 px-6 py-3 font-mono text-sm font-bold uppercase tracking-wider text-white shadow-lg transition-all hover:shadow-amber-500/50">
                        Simpan Draft
                    </button>
                </div>
            </form>
        </div>
    </main>
</x-layouts.app>