<x-admin.layouts.app>
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <span class="page-label">// Import ASN</span>
            <h1 class="page-title">Import Data ASN</h1>
            <p class="page-subtitle">Upload file Excel untuk update data ASN (PNS/PPPK/Honorer)</p>
        </div>
    </div>

    <!-- Alerts -->
    @if(session('success'))
        <div class="alert alert-success">
            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <span class="alert-message">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            <span class="alert-message">{{ session('error') }}</span>
        </div>
    @endif

    <div class="grid-2">
        <!-- Upload Form -->
        <div class="card">
            <div class="card-header">
                <div class="flex items-center gap-3">
                    <div class="stat-icon cyan" style="width: 36px; height: 36px;">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                        </svg>
                    </div>
                    <h3 class="card-title">Upload File Excel</h3>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.import-asn.preview') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                    @csrf

                    <div class="form-group">
                        <label class="form-label">File Excel (.xlsx / .xls)</label>
                        <div class="flex justify-center px-6 pt-5 pb-6 border-2 border-dashed rounded-lg" style="border-color: var(--border); transition: border-color 0.2s;" onmouseover="this.style.borderColor='var(--primary)'" onmouseout="this.style.borderColor='var(--border)'">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-10 w-10" style="color: var(--text-muted)" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm" style="color: var(--text-secondary)">
                                    <label for="file" class="relative cursor-pointer rounded-md font-medium hover:opacity-80" style="color: var(--primary)">
                                        <span>Pilih file</span>
                                        <input id="file" name="file" type="file" class="sr-only" accept=".xlsx,.xls" required>
                                    </label>
                                    <p class="pl-1">atau drag and drop</p>
                                </div>
                                <p class="text-xs" style="color: var(--text-muted)">XLSX, XLS up to 10MB</p>
                            </div>
                        </div>
                        <div id="fileName" class="mt-2 text-sm hidden" style="color: var(--text-secondary)">
                            <span class="font-medium">Dipilih:</span> <span id="fileNameText"></span>
                        </div>
                        @error('file')
                            <p class="mt-1 text-sm" style="color: var(--danger)">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Info Box -->
                    <div class="alert" style="margin-bottom: 16px; background: var(--info-bg); border: 1px solid rgba(37,99,235,0.2); color: var(--info);">
                        <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <p class="text-sm font-medium">Format Excel yang diperlukan:</p>
                            <p class="text-xs mt-1" style="color: var(--text-secondary)">Kolom A: No | B: Kategori | C: ASN | D: Nama | E: JK | F: NIP | G: NIK | H: KK | I: NPWP | J: Serdik | K: Kategori Bank | L: Rekening</p>
                            <p class="text-xs mt-1 font-medium">* Hanya NIP yang sudah ada di database yang akan di-update</p>
                        </div>
                    </div>

                    <button type="submit" id="previewBtn" class="btn btn-primary" style="width: 100%;">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        Preview Data
                    </button>
                </form>
            </div>
        </div>

        <!-- Info Panel -->
        <div class="card">
            <div class="card-header">
                <div class="flex items-center gap-3">
                    <div class="stat-icon emerald" style="width: 36px; height: 36px;">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="card-title">Informasi Import</h3>
                </div>
            </div>
            <div class="card-body">
                <div class="space-y-4">
                    <div class="flex items-start gap-3">
                        <div class="stat-icon emerald" style="width: 32px; height: 32px; border-radius: var(--radius-sm);">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold" style="color: var(--text-primary)">Update Only</p>
                            <p class="text-xs" style="color: var(--text-muted)">Hanya data yang ada di Excel yang di-update. Kolom lain tidak disentuh.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="stat-icon amber" style="width: 32px; height: 32px; border-radius: var(--radius-sm);">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold" style="color: var(--text-primary)">NIP Tidak Ditemukan = Skip</p>
                            <p class="text-xs" style="color: var(--text-muted)">Data dengan NIP yang belum ada di database akan dilewati.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="stat-icon blue" style="width: 32px; height: 32px; border-radius: var(--radius-sm);">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold" style="color: var(--text-primary)">Kolom yang Di-update</p>
                            <p class="text-xs" style="color: var(--text-muted)">tenaga_ktd: Nama, Kategori, ASN, JK, NIK, KK, NPWP, Serdik, Rekening</p>
                            <p class="text-xs" style="color: var(--text-muted)">users: Nama, JK, ASN, Kategori Bank, Rekening</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Import History -->
    @if(count($history) > 0)
        <div class="card" style="margin-top: 24px;">
            <div class="card-header">
                <h3 class="card-title">Riwayat Import</h3>
            </div>
            <div class="table-wrapper">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Oleh</th>
                            <th style="text-align: center">Total</th>
                            <th style="text-align: center">Update</th>
                            <th style="text-align: center">Skip</th>
                            <th style="text-align: center">Error</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(array_slice($history, 0, 5) as $item)
                            <tr>
                                <td>{{ $item['created_at'] }}</td>
                                <td>{{ $item['user_name'] }}</td>
                                <td style="text-align: center">{{ $item['total_rows'] }}</td>
                                <td style="text-align: center"><span class="badge badge-success">{{ $item['updated'] }}</span></td>
                                <td style="text-align: center"><span class="badge badge-warning">{{ $item['skipped'] }}</span></td>
                                <td style="text-align: center">
                                    @if($item['errors'] > 0)
                                        <span class="badge badge-danger">{{ $item['errors'] }}</span>
                                    @else
                                        <span style="color: var(--text-muted)">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    @push('scripts')
    <script>
        document.getElementById('file')?.addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name;
            if (fileName) {
                document.getElementById('fileName').classList.remove('hidden');
                document.getElementById('fileNameText').textContent = fileName;
            }
        });

        document.getElementById('uploadForm')?.addEventListener('submit', function() {
            const btn = document.getElementById('previewBtn');
            btn.disabled = true;
            btn.innerHTML = '<svg class="animate-spin w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Memproses...';
        });
    </script>
    @endpush
</x-admin.layouts.app>
