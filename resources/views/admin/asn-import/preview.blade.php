<x-admin.layouts.app>
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <span class="page-label">// Import ASN</span>
            <h1 class="page-title">Preview Import ASN</h1>
            <p class="page-subtitle">File: <strong>{{ $filename }}</strong></p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.import-asn.index') }}" class="btn btn-secondary">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali
            </a>
            @if($data['total_valid'] > 0)
                <form action="{{ route('admin.import-asn.import') }}" method="POST" id="importForm" style="display: inline;">
                    @csrf
                    <button type="submit" id="importBtn" class="btn btn-primary">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                        </svg>
                        Import {{ $data['total_valid'] }} Data
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- Stats -->
    <div class="grid-3" style="margin-bottom: 24px;">
        <div class="stat-card">
            <div class="stat-icon blue">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
            </div>
            <div class="stat-content">
                <span class="stat-label">Total Data</span>
                <span class="stat-value">{{ $data['total_rows'] }}</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon emerald">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
            </div>
            <div class="stat-content">
                <span class="stat-label">Akan Di-update</span>
                <span class="stat-value" style="color: var(--success)">{{ $data['total_valid'] }}</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon amber">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
            </div>
            <div class="stat-content">
                <span class="stat-label">Dilewati (Skip)</span>
                <span class="stat-value" style="color: var(--warning)">{{ $data['total_skipped'] }}</span>
            </div>
        </div>
    </div>

    <!-- Valid Data Table -->
    @if(count($data['valid']) > 0)
        <div class="card" style="margin-bottom: 24px;">
            <div class="card-header">
                <h3 class="card-title">Data Akan Di-update ({{ $data['total_valid'] }} record)</h3>
                <span class="badge badge-success">Update Only</span>
            </div>
            <div class="table-wrapper">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Row</th>
                            <th>NIP</th>
                            <th>Nama</th>
                            <th>Kategori</th>
                            <th>ASN</th>
                            <th>JK</th>
                            <th>NIK</th>
                            <th>Kategori Bank</th>
                            <th>Rekening</th>
                            <th>User DB</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(array_slice($data['valid'], 0, 20) as $row)
                            <tr>
                                <td>{{ $row['row'] }}</td>
                                <td style="font-family: var(--font-mono); font-size: 12px;">{{ $row['nip'] }}</td>
                                <td>{{ $row['nama'] }}</td>
                                <td><span class="badge badge-primary">{{ $row['kategori'] ?? '-' }}</span></td>
                                <td>{{ $row['asn'] ?? '-' }}</td>
                                <td>{{ $row['jk'] }}</td>
                                <td style="font-family: var(--font-mono); font-size: 12px;">{{ $row['nik'] }}</td>
                                <td>{{ $row['bank_kategori'] ?: '-' }}</td>
                                <td style="font-family: var(--font-mono); font-size: 12px;">{{ $row['rekening'] ?: '-' }}</td>
                                <td>
                                    @if($row['has_user'])
                                        <span class="badge badge-success">Ada</span>
                                    @else
                                        <span class="badge badge-gray">Tidak</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($data['total_valid'] > 20)
                <div class="card-body" style="text-align: center; padding: 12px; border-top: 1px solid var(--border);">
                    <span class="text-sm" style="color: var(--text-muted)">Dan {{ $data['total_valid'] - 20 }} data lainnya...</span>
                </div>
            @endif
        </div>
    @endif

    <!-- Skipped Data Table -->
    @if(count($data['skipped']) > 0)
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Data Dilewati ({{ $data['total_skipped'] }} record)</h3>
                <span class="badge badge-warning">Skip</span>
            </div>
            <div class="table-wrapper">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Row</th>
                            <th>NIP</th>
                            <th>Nama</th>
                            <th>Alasan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(array_slice($data['skipped'], 0, 20) as $row)
                            <tr>
                                <td>{{ $row['row'] }}</td>
                                <td style="font-family: var(--font-mono); font-size: 12px;">{{ $row['nip'] ?: '-' }}</td>
                                <td>{{ $row['nama'] ?: '-' }}</td>
                                <td style="color: var(--warning)">{{ $row['reason'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($data['total_skipped'] > 20)
                <div class="card-body" style="text-align: center; padding: 12px; border-top: 1px solid var(--border);">
                    <span class="text-sm" style="color: var(--text-muted)">Dan {{ $data['total_skipped'] - 20 }} data lainnya...</span>
                </div>
            @endif
        </div>
    @endif

    @push('scripts')
    <script>
        document.getElementById('importForm')?.addEventListener('submit', function() {
            const btn = document.getElementById('importBtn');
            btn.disabled = true;
            btn.innerHTML = '<svg class="animate-spin w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Mengimport data...';
        });
    </script>
    @endpush
</x-admin.layouts.app>
