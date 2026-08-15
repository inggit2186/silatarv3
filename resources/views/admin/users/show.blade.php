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
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: rgba(255,255,255,0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            font-weight: bold;
            border: 4px solid rgba(255,255,255,0.3);
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
    </style>

    <div class="space-y-6">
        {{-- Profile Header --}}
        <div class="profile-header">
            <div class="relative z-10 flex items-center gap-4">
                <div class="profile-avatar">
                    @php
                        $initials = strtoupper(substr($user->name, 0, 2));
                    @endphp
                    {{ $initials }}
                </div>
                <div>
                    <h1 class="text-2xl font-bold">{{ $user->name }}</h1>
                    <p class="text-cyan-100 text-sm">{{ $user->email ?? 'Belum ada email' }}</p>
                    <div class="mt-2 flex items-center gap-2">
                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-white/20 backdrop-blur-sm">
                            {{ ucfirst($user->role) }}
                        </span>
                        <span class="px-3 py-1 rounded-full text-xs font-medium {{ $user->status == 1 ? 'bg-green-500/30' : 'bg-red-500/30' }}">
                            {{ $user->status == 1 ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-2">
            {{-- Informasi Dasar --}}
            <div class="info-card">
                <div class="info-card-header">
                    <div class="info-card-icon blue">
                        <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-slate-800">Informasi Dasar</h3>
                </div>
                <div>
                    <div class="info-item">
                        <div class="info-label">Nama Lengkap</div>
                        <div class="info-value">{{ $user->name }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Email</div>
                        <div class="info-value">{{ $user->email ?? '-' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">NIP / Nomor Induk</div>
                        <div class="info-value">{{ $user->nomor_induk }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">NIK</div>
                        <div class="info-value">{{ $user->nik ?? '-' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Jenis Kelamin</div>
                        <div class="info-value">{{ $user->jk ?? '-' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Role</div>
                        <div class="info-value">{{ ucfirst($user->role) }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Status</div>
                        <div class="info-value">
                            <span class="px-2 py-1 rounded-full text-xs font-medium {{ $user->status == 1 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $user->status == 1 ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Informasi Kontak --}}
            <div class="info-card">
                <div class="info-card-header">
                    <div class="info-card-icon green">
                        <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-slate-800">Informasi Kontak</h3>
                </div>
                <div>
                    <div class="info-item">
                        <div class="info-label">No. Telepon</div>
                        <div class="info-value">{{ $user->telp ?? '-' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Pekerjaan</div>
                        <div class="info-value">{{ $user->pekerjaan ?? '-' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Unit Kerja</div>
                        <div class="info-value">{{ $user->dept_name ?? '-' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Madrasah</div>
                        <div class="info-value">{{ $user->madrasah_name ?? '-' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Alamat</div>
                        <div class="info-value">{{ $user->alamat ?? '-' }}</div>
                    </div>
                </div>
            </div>

            {{-- Informasi Lainnya --}}
            <div class="info-card">
                <div class="info-card-header">
                    <div class="info-card-icon purple">
                        <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-slate-800">Informasi Tambahan</h3>
                </div>
                <div>
                    <div class="info-item">
                        <div class="info-label">Tempat Lahir</div>
                        <div class="info-value">{{ $user->tempat_lahir ?? '-' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Tanggal Lahir</div>
                        <div class="info-value">{{ $user->tanggal_lahir ?? '-' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Nomor KK</div>
                        <div class="info-value">{{ $user->kk ?? '-' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">NPWP</div>
                        <div class="info-value">{{ $user->npwp ?? '-' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Status Nikah</div>
                        <div class="info-value">{{ $user->nikah == '1' ? 'Sudah Menikah' : 'Belum Menikah' }}</div>
                    </div>
                </div>
            </div>

            {{-- Informasi Keluarga --}}
            <div class="info-card">
                <div class="info-card-header">
                    <div class="info-card-icon orange">
                        <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-slate-800">Informasi Keluarga</h3>
                </div>
                <div>
                    <div class="info-item">
                        <div class="info-label">Nama Istri/Suami</div>
                        <div class="info-value">{{ $user->nama_istri_suami ?? '-' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Pekerjaan Pasangan</div>
                        <div class="info-value">{{ $user->pjob ?? '-' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Jumlah Anak</div>
                        <div class="info-value">{{ $user->jml_anak ?? '-' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Nama Ibu Kandung</div>
                        <div class="info-value">{{ $user->nama_ibu ?? '-' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Bio</div>
                        <div class="info-value">{{ $user->bio ?? '-' }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- User Info --}}
        <div class="bg-slate-50 rounded-xl p-4 border border-slate-200">
            <div class="flex items-center justify-between text-sm text-slate-500">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>Dibuat: {{ $user->created_at }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    <span>Diperbarui: {{ $user->updated_at }}</span>
                </div>
            </div>
        </div>

        {{-- Back Button --}}
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.users.index') }}" class="px-6 py-3 rounded-xl font-semibold border border-slate-300 text-slate-600 hover:bg-slate-50 transition-colors flex items-center gap-2">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali
            </a>
        </div>
    </div>
</x-admin.layouts.app>
