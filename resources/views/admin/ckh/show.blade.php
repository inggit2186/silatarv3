<x-admin.layouts.app>
    <style>
        .profile-header {
            background: linear-gradient(135deg, #0891b2 0%, #06b6d4 50%, #22d3ee 100%);
            border-radius: 1rem;
            padding: 2rem;
            color: white;
            position: relative;
            overflow: hidden;
        }
        .profile-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        }
        .profile-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: rgba(255,255,255,0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            font-weight: bold;
            border: 3px solid rgba(255,255,255,0.3);
        }
        .info-card {
            background: white;
            border-radius: 1rem;
            border: 1px solid #e2e8f0;
            overflow: hidden;
        }
        .info-card-header {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .info-card-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .info-card-icon.blue { background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); }
        .info-card-icon.green { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
        .info-card-icon.purple { background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); }
        .info-card-icon.orange { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); }
        .info-item {
            display: flex;
            padding: 0.75rem 1.5rem;
            border-bottom: 1px solid #f1f5f9;
        }
        .info-item:last-child {
            border-bottom: none;
        }
        .info-label {
            width: 180px;
            flex-shrink: 0;
            color: #64748b;
            font-size: 0.875rem;
        }
        .info-value {
            color: #1e293b;
            font-weight: 500;
        }
        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .status-kosong { background: #f3f4f6; color: #374151; }
        .status-dikirim { background: #fef3c7; color: #92400e; }
        .status-disetujui { background: #d1fae5; color: #065f46; }
        .status-ditolak { background: #fee2e2; color: #991b1b; }
    </style>

    <div class="space-y-6">
        {{-- Profile Header --}}
        <div class="profile-header">
            <div class="relative z-10 flex items-center gap-4">
                <div class="profile-avatar">
                    {{ strtoupper(substr($ckh->user_name, 0, 2)) }}
                </div>
                <div>
                    <h1 class="text-2xl font-bold">{{ $ckh->user_name }}</h1>
                    <p class="text-cyan-100 text-sm">NIP: {{ $ckh->user_nip }}</p>
                    <div class="mt-2 flex items-center gap-2">
                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-white/20 backdrop-blur-sm">
                            {{ $ckh->dept_nama ?? '-' }}
                        </span>
                        <span class="status-badge status-{{ strtolower($ckh->status) }}">
                            {{ $ckh->status }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Success/Error Message --}}
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 rounded-xl p-4 flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-green-500 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-green-800">Berhasil!</p>
                    <p class="text-sm text-green-600">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 rounded-xl p-4 flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-red-500 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-red-800">Error!</p>
                    <p class="text-sm text-red-600">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <div class="grid gap-6 lg:grid-cols-2">
            {{-- Informasi Laporan --}}
            <div class="info-card">
                <div class="info-card-header">
                    <div class="info-card-icon blue">
                        <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-slate-800">Informasi Laporan</h3>
                </div>
                <div>
                    <div class="info-item">
                        <div class="info-label">Bulan</div>
                        <div class="info-value">{{ \Carbon\Carbon::parse($ckh->bulan)->translatedFormat('F Y') }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Status</div>
                        <div class="info-value">
                            <span class="status-badge status-{{ strtolower($ckh->status) }}">
                                {{ $ckh->status }}
                            </span>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Waktu Kirim</div>
                        <div class="info-value">{{ $ckh->sending ? \Carbon\Carbon::parse($ckh->sending)->format('d M Y H:i') : '-' }}</div>
                    </div>
                    @if($ckh->alasan)
                    <div class="info-item">
                        <div class="info-label">Alasan Penolakan</div>
                        <div class="info-value text-red-600">{{ $ckh->alasan }}</div>
                    </div>
                    @endif
                    @if($ckh->filename)
                    <div class="info-item">
                        <div class="info-label">File Laporan</div>
                        <div class="info-value">
                            <button type="button" onclick="openPdfModal()" class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Lihat PDF
                            </button>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Informasi User --}}
            <div class="info-card">
                <div class="info-card-header">
                    <div class="info-card-icon green">
                        <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-slate-800">Informasi Pengguna</h3>
                </div>
                <div>
                    <div class="info-item">
                        <div class="info-label">Nama</div>
                        <div class="info-value">{{ $ckh->user_name }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">NIP</div>
                        <div class="info-value">{{ $ckh->user_nip }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Unit Kerja</div>
                        <div class="info-value">{{ $ckh->dept_nama ?? '-' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Jenis Kelamin</div>
                        <div class="info-value">{{ $ckh->user_jk ?? '-' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Pekerjaan</div>
                        <div class="info-value">{{ $ckh->user_pekerjaan ?? '-' }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Detail Item Laporan --}}
        @if(!empty($items))
        <div class="info-card">
            <div class="info-card-header">
                <div class="info-card-icon purple">
                    <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <h3 class="font-semibold text-slate-800">Detail Item Laporan</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Item</th>
                                <th>Keterangan</th>
                                <th>Volume</th>
                                <th>Satuan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item['item'] ?? '-' }}</td>
                                <td>{{ $item['keterangan'] ?? '-' }}</td>
                                <td>{{ $item['volume'] ?? '-' }}</td>
                                <td>{{ $item['satuan'] ?? '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

        {{-- File PDF Laporan --}}
        @if($ckh->filename)
        <div class="info-card">
            <div class="info-card-header">
                <div class="info-card-icon orange">
                    <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h3 class="font-semibold text-slate-800">File Laporan PDF</h3>
            </div>
            <div class="card-body">
                <div class="flex items-center gap-4">
                    <button type="button" onclick="openPdfModal()" class="px-6 py-3 bg-blue-500 hover:bg-blue-600 text-white rounded-lg font-medium flex items-center gap-2 transition-colors">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        Lihat PDF
                    </button>
                    <a href="{{ asset('storage/satker_ckh/' . $ckh->user_id . '/' . $ckh->filename) }}" download class="px-6 py-3 bg-green-500 hover:bg-green-600 text-white rounded-lg font-medium flex items-center gap-2 transition-colors">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Download PDF
                    </a>
                    <span class="text-sm text-muted">{{ basename($ckh->filename) }}</span>
                </div>
            </div>
        </div>
        @endif

        {{-- Verifikasi Section --}}
        @if($canVerify && $ckh->status !== 'DISETUJUI')
        <div class="info-card">
            <div class="info-card-header">
                <div class="info-card-icon orange">
                    <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="font-semibold text-slate-800">Verifikasi Laporan</h3>
            </div>
            <div class="card-body">
                <div class="flex items-center gap-4">
                    <form action="{{ route('admin.ckh.approve', $ckh->id) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="px-6 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg font-medium flex items-center gap-2 transition-colors">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                            Setujui
                        </button>
                    </form>

                    <button type="button" onclick="openRejectModal()" class="px-6 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg font-medium flex items-center gap-2 transition-colors">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Tolak
                    </button>
                </div>
            </div>
        </div>
        @endif

        {{-- Back Button --}}
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.ckh.index') }}" class="px-6 py-3 rounded-xl font-semibold border border-slate-300 text-slate-600 hover:bg-slate-50 transition-colors flex items-center gap-2">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali
            </a>
        </div>
    </div>

    {{-- Reject Modal --}}
    <div id="rejectModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-2xl p-6 w-full max-w-md mx-4">
            <h3 class="text-lg font-bold text-slate-800 mb-4">Tolak Laporan</h3>
            <form action="{{ route('admin.ckh.reject', $ckh->id) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="form-label">Alasan Penolakan</label>
                    <textarea name="alasan" class="form-textarea" rows="3" placeholder="Masukkan alasan penolakan..." required></textarea>
                </div>
                <div class="flex items-center gap-3 justify-end">
                    <button type="button" onclick="closeRejectModal()" class="px-4 py-2 border border-slate-300 text-slate-600 rounded-lg hover:bg-slate-50 transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg font-medium transition-colors">
                        Tolak
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openRejectModal() {
            document.getElementById('rejectModal').classList.remove('hidden');
            document.getElementById('rejectModal').classList.add('flex');
        }

        function closeRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
            document.getElementById('rejectModal').classList.remove('flex');
        }

        function openPdfModal() {
            document.getElementById('pdfModal').classList.remove('hidden');
            document.getElementById('pdfModal').classList.add('flex');
        }

        function closePdfModal() {
            document.getElementById('pdfModal').classList.add('hidden');
            document.getElementById('pdfModal').classList.remove('flex');
        }
    </script>

    {{-- PDF Preview Modal --}}
    @if($ckh->filename)
    <div id="pdfModal" class="fixed inset-0 bg-black/70 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl w-full max-w-5xl max-h-[90vh] flex flex-col">
            <div class="flex items-center justify-between p-4 border-b">
                <h3 class="text-lg font-bold text-slate-800">Preview Laporan CKH</h3>
                <button type="button" onclick="closePdfModal()" class="p-2 hover:bg-slate-100 rounded-lg transition-colors">
                    <svg class="w-5 h-5 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="flex-1 overflow-auto p-4">
                <iframe src="{{ asset('storage/satker_ckh/' . $ckh->user_id . '/' . $ckh->filename) }}" class="w-full h-[70vh] border-0 rounded-lg"></iframe>
            </div>
            <div class="flex items-center justify-end gap-3 p-4 border-t">
                <a href="{{ asset('storage/satker_ckh/' . $ckh->user_id . '/' . $ckh->filename) }}" download class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg font-medium flex items-center gap-2 transition-colors">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Download
                </a>
                <button type="button" onclick="closePdfModal()" class="px-4 py-2 border border-slate-300 text-slate-600 rounded-lg hover:bg-slate-50 transition-colors">
                    Tutup
                </button>
            </div>
        </div>
    </div>
    @endif
</x-admin.layouts.app>
