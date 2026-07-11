<x-layouts.app title="Edit Profil - SILATAR">
    <main class="neo-mirai">
        <x-layouts.site-header />

        <!-- Hero Section -->
        <section class="hero-page bg-cover bg-center" style="background-image: url('/assets/img/template/bg2.webp'); padding: 2rem 2rem 4rem;">
            <div class="form-page-container" style="text-align: center;">
                <a href="{{ route('profil') }}" class="back-link" onmouseover="this.style.color='var(--gold)'" onmouseout="this.style.color='var(--ink-soft)'">
                    <svg width="14" height="14" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Kembali ke Profil
                </a>

                <span class="edit-badge">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828l-9.193 9.193a2 2 0 01-2.828 0l-2.172-2.172a2 2 0 010-2.828l9.193-9.193z"/>
                    </svg>
                    Edit Profil
                </span>

                <h1 class="article-hero-title mt-4">Edit Data Profil</h1>
                <p class="text-ink-soft text-sm">Perbarui informasi profil Anda</p>
            </div>
        </section>

        <!-- Section Divider -->
        <div class="section-divider wave-rounded"></div>

        <!-- Edit Form -->
        <section class="page-content">
            <div class="form-page-container">
                <form method="POST" action="{{ route('profil.update') }}" enctype="multipart/form-data" x-data="{ statusNikah: '{{ old('nikah', $user->nikah ?? '0') }}', jenisPjob: '{{ old('jenis_pjob', $user->jenis_pjob ?? '') }}' }">
                    @csrf
                    @method('PUT')

                    <!-- Main Card -->
                    <div class="neo-card p-6">
                        <!-- Avatar Section -->
                        <div class="avatar-section">
                            <div class="avatar-photo-wrapper">
                                <div class="avatar-photo">
                                    @if($user->pp && $user->nomor_induk)
                                        <img src="{{ asset('assets/img/users/' . $user->nomor_induk . '/' . $user->pp) }}" alt="{{ $user->name }}">
                                    @else
                                        <span class="avatar-photo-initials">{{ substr($user->name, 0, 2) }}</span>
                                    @endif
                                </div>
                            </div>
                            <h2 class="avatar-name">{{ $user->name }}</h2>
                            <p class="avatar-id">{{ $user->nomor_induk }}</p>
                        </div>

                        <!-- Divider -->
                        <div class="form-divider"></div>

                        <!-- Form Fields -->
                        <div class="form-grid">
                            <!-- Nama Lengkap (Read Only) -->
                            <div class="form-field form-grid-full">
                                <label class="form-label">
                                    Nama Lengkap
                                </label>
                                <input type="text" value="{{ $user->name }}" readonly class="form-input form-input-readonly">
                            </div>

                            <!-- Pekerjaan/Jabatan (Read Only) -->
                            <div class="form-field">
                                <label class="form-label">
                                    Pekerjaan / Jabatan
                                </label>
                                <input type="text" value="{{ $user->pekerjaan ?? '-' }}" readonly class="form-input form-input-readonly">
                            </div>

                            <!-- Unit Kerja (Read Only) -->
                            <div class="form-field">
                                <label class="form-label">
                                    Unit Kerja
                                </label>
                                <input type="text" value="{{ $satuanKerja }}" readonly class="form-input form-input-readonly">
                            </div>

                            <!-- NIK -->
                            <div class="form-field">
                                <label for="nik" class="form-label">
                                    NIK (Nomor Induk Kependudukan)
                                </label>
                                <input type="text" name="nik" id="nik" value="{{ old('nik', $user->nik) }}" placeholder="-" class="form-input form-input-default">
                            </div>

                            <!-- NPWP -->
                            <div class="form-field">
                                <label for="npwp" class="form-label">
                                    NPWP
                                </label>
                                <input type="text" name="npwp" id="npwp" value="{{ old('npwp', $user->npwp ?? '') }}" placeholder="00.000.000.0-000.000" class="form-input form-input-default">
                            </div>

                            <!-- nomor Rekening Gaji -->
                            <div class="form-field">
                                <label for="no_rekening" class="form-label">
                                    Nomor Rekening Gaji
                                </label>
                                <input type="text" name="no_rekening" id="no_rekening" value="{{ old('no_rekening', $user->no_rekening ?? '') }}" placeholder="-" class="form-input form-input-default">
                            </div>

                            <!-- Nama Bank -->
                            <div class="form-field">
                                <label for="bank" class="form-label">
                                    Nama Bank
                                </label>
                                <input type="text" name="bank" id="bank" value="{{ old('bank', $user->bank ?? '') }}" placeholder="-" class="form-input form-input-default">
                            </div>

                            <!-- Alamat -->
                            <div class="form-field form-grid-full">
                                <label for="alamat" class="form-label">
                                    Alamat
                                </label>
                                <textarea name="alamat" id="alamat" rows="3" placeholder="-" class="form-textarea">{{ old('alamat', $user->alamat ?? '') }}</textarea>
                            </div>

                            <!-- No HP -->
                            <div class="form-field">
                                <label for="hp" class="form-label">
                                    No. HP
                                </label>
                                <input type="text" name="hp" id="hp" value="{{ old('hp', $user->hp ?? '') }}" placeholder="-" class="form-input form-input-default">
                            </div>

                            <!-- Email -->
                            <div class="form-field">
                                <label for="email" class="form-label">
                                    Email
                                </label>
                                <input type="email" name="email" id="email" value="{{ old('email', $user->email ?? '') }}" placeholder="-" class="form-input form-input-default">
                            </div>
                        </div>

                        <!-- Divider -->
                        <div class="form-divider"></div>

                        <!-- Action Buttons -->
                        <div class="form-actions">
                            <a href="{{ route('profil') }}" class="neo-btn-secondary">
                                <svg width="16" height="16" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Batal
                            </a>
                            <button type="submit" class="neo-btn">
                                <svg width="16" height="16" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                                Simpan Perubahan
                            </button>
                        </div>
                    </div>

                    <!-- Error Messages -->
                    @if ($errors->any())
                        <div class="alert-error">
                            <p class="alert-error-text">{{ $errors->first() }}</p>
                        </div>
                    @endif

                    <!-- Success Message -->
                    @if(session('success'))
                        <div class="alert-success">
                            <p class="alert-success-text">{{ session('success') }}</p>
                        </div>
                    @endif
                </form>
            </div>
        </section>

        <!-- Footer -->
        <footer class="site-footer">
            <a class="brand-lockup brand-lockup-small" href="{{ url("/") }}" aria-label="SILATAR home">
                <span class="brand-mark" aria-hidden="true"><span></span></span>
                <span class="brand-word"><span>SILATAR</span><span>V2</span></span>
            </a>
            <p>Portal Layanan Digital Kementerian Agama Tanah Datar</p>
            <nav aria-label="Footer navigation">
                <a href="{{ url("/") }}">Beranda</a>
                <a href="{{ route('pelayanan') }}">Pelayanan</a>
                <a href="{{ route('satuan-kerja') }}">Unit Kerja</a>
                <a href="{{ route('news.index') }}">Berita</a>
            </nav>
            <div class="footer-copyright"><span>&copy; {{ date("Y") }} SILATAR - Kementerian Agama Tanah Datar</span></div>
        </footer>
    </main>
</x-layouts.app>
