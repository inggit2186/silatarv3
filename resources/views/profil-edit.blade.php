<x-layouts.app title="Edit Profil - SILATAR">
    {{-- Cropper.js untuk cropping foto profil --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js"></script>

    <main class="neo-mirai">

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
                {{-- ═══════════════════════════════════════════════════════════════════════════
                     BANNER PERINGATAN: DATA BELUM LENGKAP - MENCOLOK & MENONJOL
                     ═══════════════════════════════════════════════════════════════════════════ --}}
                @if(session('warning') || session('profile_incomplete'))
                <div class="profile-warning-banner" role="alert" aria-labelledby="warning-title">
                    <div class="warning-banner-inner">
                        {{-- Icon Warning dengan Animasi Pulsing --}}
                        <div class="warning-icon-wrapper">
                            <div class="warning-pulse-ring"></div>
                            <div class="warning-pulse-ring warning-pulse-ring-delay"></div>
                            <svg class="warning-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>

                        {{-- Content --}}
                        <div class="warning-content">
                            <h2 id="warning-title" class="warning-title">
                                ⚠️ LENGKAPI DATA PROFIL ANDA SEKARANG
                            </h2>
                            <p class="warning-subtitle">
                                Data berikut <strong>WAJIB</strong> diisi untuk melanjutkan menggunakan portal SILATAR.
                                Anda tidak dapat mengakses fitur lain sebelum melengkapi data ini.
                            </p>

                            {{-- Grid Checklist Data yang Belum Lengkap --}}
                            <div class="warning-checklist">
                                @if(session('profile_incomplete'))
                                    @foreach(session('profile_incomplete') as $field)
                                        <div class="warning-item warning-item-missing">
                                            <svg viewBox="0 0 20 20" fill="currentColor" class="warning-item-icon">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                            </svg>
                                            <span class="warning-item-text">{{ $field }}</span>
                                        </div>
                                    @endforeach
                                @endif
                            </div>

                            {{-- Call to Action --}}
                            <div class="warning-cta">
                                <div class="warning-arrow">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                                    </svg>
                                </div>
                                <span>Isi data di bawah ini</span>
                            </div>
                        </div>
                    </div>

                    {{-- Progress Indicator --}}
                    <div class="warning-progress-bar">
                        <div class="warning-progress-fill"></div>
                    </div>
                </div>
                @endif

                <form method="POST" action="{{ route('profil.update') }}" enctype="multipart/form-data" x-data="{ statusNikah: '{{ old('nikah', $user->nikah ?? '0') }}', jenisPjob: '{{ old('jenis_pjob', $user->jenis_pjob ?? '') }}' }">
                    @csrf
                    @method('PUT')

                    {{-- Section Data Wajib --}}
                    <div class="neo-card p-6 mb-4" style="border: 2px solid #f59e0b;">
                        <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem;">
                            <span style="background: #fbbf24; color: #78350f; padding: 4px 10px; border-radius: 999px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase;">Data Wajib</span>
                            <h3 style="margin: 0; font-size: 1rem; font-weight: 700; color: var(--ink);">Wajib Diisi Lengkap</h3>
                        </div>

                        <!-- Avatar & Upload Foto -->
                        <div class="avatar-section">
                            <div class="avatar-photo-wrapper">
                                <div class="avatar-photo">
                                    @if($user->pp && $user->nomor_induk)
                                        <img src="{{ asset('storage/users_berkas/' . $user->nomor_induk . '/' . $user->pp) }}" alt="{{ $user->name }}" id="avatar-preview">
                                    @else
                                        <span class="avatar-photo-initials" id="avatar-initials">{{ substr($user->name, 0, 2) }}</span>
                                        <img src="" alt="preview" id="avatar-preview" style="display:none;">
                                    @endif
                                </div>
                            </div>
                            <h2 class="avatar-name">{{ $user->name }}</h2>
                            <p class="avatar-id">{{ $user->nomor_induk }}</p>

                            {{-- Upload Foto Profil dengan Cropper --}}
                            <div style="margin-top: 1rem; text-align: center;" x-data="{ showMenu: false }">
                                {{-- Button trigger dengan dropdown --}}
                                <div style="position: relative; display: inline-block;">
                                    <button type="button" @click="showMenu = !showMenu" @click.away="showMenu = false"
                                        style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 10px 20px; background: var(--color-primary, #0891b2); color: white; border: none; border-radius: 10px; cursor: pointer; font-size: 0.875rem; font-weight: 600; transition: all 0.2s; box-shadow: 0 4px 12px rgba(8, 145, 178, 0.3);">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <span>Upload Foto Profil</span>
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="transition: transform 0.2s;" :style="showMenu ? 'transform: rotate(180deg)' : ''">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </button>

                                    {{-- Dropdown Menu --}}
                                    <div x-show="showMenu"
                                        x-transition:enter="transition ease-out duration-100"
                                        x-transition:enter-start="transform opacity-0 scale-95"
                                        x-transition:enter-end="transform opacity-100 scale-100"
                                        x-transition:leave="transition ease-in duration-75"
                                        x-transition:leave-start="transform opacity-100 scale-100"
                                        x-transition:leave-end="transform opacity-0 scale-95"
                                        style="position: absolute; top: calc(100% + 8px); left: 50%; transform: translateX(-50%); min-width: 220px; background: white; border-radius: 12px; box-shadow: 0 10px 40px rgba(0,0,0,0.15), 0 0 0 1px rgba(0,0,0,0.05); overflow: hidden; z-index: 100;">
                                        <div style="padding: 8px 0;">
                                            <label for="pp_selector" @click="showMenu = false"
                                                style="display: flex; align-items: center; gap: 12px; padding: 12px 16px; cursor: pointer; transition: background 0.15s; text-decoration: none; color: inherit;"
                                                onmouseover="this.style.background='#f5f5f4'" onmouseout="this.style.background='transparent'">
                                                <div style="width: 40px; height: 40px; border-radius: 10px; background: linear-gradient(135deg, #0891b2 0%, #2563eb 100%); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <div style="font-weight: 600; font-size: 0.875rem; color: var(--ink, #1c1917);">Pilih dari Galeri</div>
                                                    <div style="font-size: 0.75rem; color: var(--ink-soft, #78716c);">Upload dari perangkat</div>
                                                </div>
                                            </label>
                                            <hr style="border: none; border-top: 1px solid #e5e5e5; margin: 4px 12px;">
                                            <button type="button" onclick="openCameraModal(); showMenu = false;" @click="showMenu = false"
                                                style="display: flex; align-items: center; gap: 12px; width: 100%; padding: 12px 16px; background: transparent; border: none; cursor: pointer; text-align: left; transition: background 0.15s;"
                                                onmouseover="this.style.background='#f5f5f4'" onmouseout="this.style.background='transparent'">
                                                <div style="width: 40px; height: 40px; border-radius: 10px; background: linear-gradient(135deg, #059669 0%, #047857 100%); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                                        <circle cx="12" cy="13" r="3"/>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <div style="font-weight: 600; font-size: 0.875rem; color: var(--ink, #1c1917);">Ambil dari Kamera</div>
                                                    <div style="font-size: 0.75rem; color: var(--ink-soft, #78716c);">Foto langsung dengan kamera</div>
                                                </div>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                @if(!$user->pp)
                                    <span style="color: #dc2626; font-size: 0.8rem; display: block; margin-top: 0.5rem;">* Wajib diisi</span>
                                @endif
                                {{-- Selector file (tidak dikirim ke server, hanya trigger cropper) --}}
                                <input type="file" id="pp_selector" accept="image/jpeg,image/jpg,image/png,image/webp" style="display: none;"
                                    onchange="openCropperModal(event)">
                                {{-- Hidden file input yang akan dikirim ke server (isi dari crop result) --}}
                                <input type="file" name="pp" id="pp" accept="image/jpeg,image/jpg,image/png,image/webp" style="display: none;">
                                <p style="margin: 8px 0 0; font-size: 0.75rem; color: var(--ink-soft, #6b7280);">JPG/PNG/WEBP, maksimal 2MB</p>
                                <p id="pp-crop-status" style="margin: 4px 0 0; font-size: 0.75rem; color: #059669; font-weight: 600; display: none;">
                                    ✓ Foto siap diupload
                                </p>
                                @error('pp')
                                    <p style="color: #dc2626; font-size: 0.8rem; margin-top: 4px;">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Divider -->
                        <div class="form-divider"></div>

                        <div class="form-grid">
                            {{-- NIK (16 digit) --}}
                            <div class="form-field">
                                <label for="nik_wajib" class="form-label">
                                    NIK (Nomor Induk Kependudukan) <span style="color: #dc2626;">*</span>
                                </label>
                                <input type="text" name="nik" id="nik_wajib"
                                    value="{{ old('nik', $tenagaKtd->nik ?? $user->nip ?? '') }}"
                                    placeholder="16 digit NIK"
                                    inputmode="numeric"
                                    maxlength="16"
                                    pattern="\d{16}"
                                    oninput="this.value=this.value.replace(/\D/g,'').slice(0,16)"
                                    class="form-input form-input-default"
                                    required>
                                <p style="margin: 4px 0 0; font-size: 0.75rem; color: var(--ink-soft, #6b7280);">Harus 16 digit angka</p>
                                @error('nik')
                                    <p style="color: #dc2626; font-size: 0.8rem; margin-top: 4px;">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Nomor KK (16 digit) --}}
                            <div class="form-field">
                                <label for="no_kk" class="form-label">
                                    Nomor Kartu Keluarga (KK) <span style="color: #dc2626;">*</span>
                                </label>
                                <input type="text" name="no_kk" id="no_kk"
                                    value="{{ old('no_kk', $tenagaKtd->kk ?? '') }}"
                                    placeholder="16 digit Nomor KK"
                                    inputmode="numeric"
                                    maxlength="16"
                                    pattern="\d{16}"
                                    oninput="this.value=this.value.replace(/\D/g,'').slice(0,16)"
                                    class="form-input form-input-default"
                                    required>
                                <p style="margin: 4px 0 0; font-size: 0.75rem; color: var(--ink-soft, #6b7280);">Harus 16 digit angka</p>
                                @error('no_kk')
                                    <p style="color: #dc2626; font-size: 0.8rem; margin-top: 4px;">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Jabatan Saat Ini --}}
                            <div class="form-field form-grid-full">
                                <label for="jabatan_wajib" class="form-label">
                                    Jabatan Saat Ini <span style="color: #dc2626;">*</span>
                                </label>
                                <input type="text" name="jabatan" id="jabatan_wajib"
                                    value="{{ old('jabatan', $user->pekerjaan ?? ($tenagaKtd->pekerjaan ?? '')) }}"
                                    placeholder="Contoh: Pranata Komputer"
                                    class="form-input form-input-default"
                                    required>
                                @error('jabatan')
                                    <p style="color: #dc2626; font-size: 0.8rem; margin-top: 4px;">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Main Card -->
                    <div class="neo-card p-6">
                        <!-- Avatar Section -->
                        <div class="avatar-section" style="display:none;">
                            <div class="avatar-photo-wrapper">
                                <div class="avatar-photo">
                                    @if($user->pp && $user->nomor_induk)
                                        <img src="{{ asset('storage/users_berkas/' . $user->nomor_induk . '/' . $user->pp) }}" alt="{{ $user->name }}">
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

                            <!-- NPWP -->
                            <div class="form-field">
                                <label for="npwp" class="form-label">
                                    NPWP
                                </label>
                                <input type="text" name="npwp" id="npwp" value="{{ old('npwp', $tenagaKtd->npwp ?? '') }}" placeholder="00.000.000.0-000.000" class="form-input form-input-default">
                            </div>

                            <!-- nomor Rekening Gaji -->
                            <div class="form-field">
                                <label for="no_rekening" class="form-label">
                                    Nomor Rekening Gaji
                                </label>
                                <input type="text" name="no_rekening" id="no_rekening" value="{{ old('no_rekening', $tenagaKtd->rekening ?? '') }}" placeholder="-" class="form-input form-input-default">
                            </div>

                            <!-- Nama Bank -->
                            <div class="form-field">
                                <label for="bank" class="form-label">
                                    Nama Bank
                                </label>
                                <input type="text" name="bank" id="bank" value="{{ old('bank', $tenagaKtd->bank ?? '') }}" placeholder="-" class="form-input form-input-default">
                            </div>

                            <!-- Alamat -->
                            <div class="form-field form-grid-full">
                                <label for="alamat" class="form-label">
                                    Alamat
                                </label>
                                <textarea name="alamat" id="alamat" rows="3" placeholder="-" class="form-textarea">{{ old('alamat', $tenagaKtd->alamat ?? $user->alamat ?? '') }}</textarea>
                            </div>

                            <!-- No HP -->
                            <div class="form-field">
                                <label for="hp" class="form-label">
                                    No. HP
                                </label>
                                <input type="text" name="hp" id="hp" value="{{ old('hp', $user->telp ?? $tenagaKtd->telp ?? '') }}" placeholder="-" class="form-input form-input-default">
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

                {{-- ═══════════════════════════════════════════════════════════
                     MODAL CROP FOTO PROFIL - NEO MIRAI THEME
                     ═══════════════════════════════════════════════════════════ --}}
                <div id="cropperModal" class="cropper-modal-overlay" style="display: none;">
                    <div class="cropper-modal-box">
                        {{-- Header --}}
                        <div class="cropper-modal-header">
                            <div class="cropper-modal-header-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                    <circle cx="12" cy="13" r="4"/>
                                </svg>
                            </div>
                            <div class="cropper-modal-header-text">
                                <span class="cropper-modal-badge">FOTO PROFIL</span>
                                <h3 class="cropper-modal-title">Sesuaikan Foto Profil</h3>
                                <p class="cropper-modal-subtitle">Geser & zoom untuk memilih area lingkaran foto</p>
                            </div>
                            <button type="button" onclick="closeCropperModal()" class="cropper-modal-close" aria-label="Tutup">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20">
                                    <path d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>

                        {{-- Body: Cropper canvas --}}
                        <div class="cropper-modal-body">
                            <div class="cropper-image-wrapper cropper-round">
                                <img id="cropperImage" alt="Foto untuk di-crop">
                            </div>

                            <p class="cropper-hint">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14">
                                    <circle cx="12" cy="12" r="10"/>
                                    <line x1="12" y1="16" x2="12" y2="12"/>
                                    <line x1="12" y1="8" x2="12.01" y2="8"/>
                                </svg>
                                Area di dalam lingkaran akan menjadi foto profil Anda
                            </p>

                            {{-- Tools --}}
                            <div class="cropper-tools">
                                <button type="button" class="cropper-tool-btn" onclick="cropperZoom(0.1)" title="Zoom In">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18">
                                        <circle cx="11" cy="11" r="8"/>
                                        <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                                        <line x1="11" y1="8" x2="11" y2="14"/>
                                        <line x1="8" y1="11" x2="14" y2="11"/>
                                    </svg>
                                    <span>Zoom In</span>
                                </button>
                                <button type="button" class="cropper-tool-btn" onclick="cropperZoom(-0.1)" title="Zoom Out">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18">
                                        <circle cx="11" cy="11" r="8"/>
                                        <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                                        <line x1="8" y1="11" x2="14" y2="11"/>
                                    </svg>
                                    <span>Zoom Out</span>
                                </button>
                                <button type="button" class="cropper-tool-btn" onclick="cropperRotate(-90)" title="Putar Kiri">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18">
                                        <path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/>
                                        <path d="M21 3v5h-5"/>
                                    </svg>
                                    <span>Putar Kiri</span>
                                </button>
                                <button type="button" class="cropper-tool-btn" onclick="cropperRotate(90)" title="Putar Kanan">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18">
                                        <path d="M21 12a9 9 0 1 1-9-9c2.52 0 4.93 1 6.74 2.74L21 8"/>
                                        <path d="M21 3v5h-5"/>
                                    </svg>
                                    <span>Putar Kanan</span>
                                </button>
                                <button type="button" class="cropper-tool-btn" onclick="cropperReset()" title="Reset">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18">
                                        <path d="M1 4v6h6"/>
                                        <path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"/>
                                    </svg>
                                    <span>Reset</span>
                                </button>
                            </div>
                        </div>

                        {{-- Footer actions --}}
                        <div class="cropper-modal-footer">
                            <button type="button" onclick="closeCropperModal()" class="cropper-btn-cancel">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                                    <path d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Batal
                            </button>
                            <button type="button" onclick="applyCrop()" class="cropper-btn-apply">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                                    <path d="M5 13l4 4L19 7"/>
                                </svg>
                                Terapkan Foto
                            </button>
                        </div>
                    </div>
                </div>

                {{-- ═══════════════════════════════════════════════════════════
                     CAMERA CAPTURE MODAL
                     ═══════════════════════════════════════════════════════════ --}}
                <div id="cameraModal" class="camera-modal-overlay" style="display: none;">
                    <div class="camera-modal-box">
                        {{-- Header --}}
                        <div class="camera-modal-header">
                            <div class="camera-modal-header-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="22" height="22">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                    <circle cx="12" cy="13" r="3"/>
                                </svg>
                            </div>
                            <div class="camera-modal-header-text">
                                <span class="camera-modal-badge">KAMERA</span>
                                <h3 class="camera-modal-title">Ambil Foto Profil</h3>
                                <p class="camera-modal-subtitle">Posisikan wajah Anda di dalam lingkaran</p>
                            </div>
                            <button type="button" onclick="closeCameraModal()" class="camera-modal-close" aria-label="Tutup">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20">
                                    <path d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>

                        {{-- Body: Camera Preview --}}
                        <div class="camera-modal-body">
                            <div class="camera-preview-wrapper">
                                <video id="cameraVideo" autoplay playsinline style="display: none;"></video>
                                <canvas id="cameraCanvas" style="display: none;"></canvas>
                                <img id="cameraPreview" src="" alt="Preview" style="display: none;">
                                <div id="cameraPlaceholder" class="camera-placeholder">
                                    <div class="camera-placeholder-inner">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" width="48" height="48">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                            <circle cx="12" cy="13" r="3"/>
                                        </svg>
                                        <p>Memuat kamera...</p>
                                    </div>
                                </div>
                                {{-- Face Circle Overlay --}}
                                <div class="camera-face-circle"></div>
                                {{-- Face Guide Text --}}
                                <div class="camera-face-guide">
                                    <span>Posisikan wajah Anda di sini</span>
                                </div>
                            </div>

                            {{-- Camera Controls (show when streaming) --}}
                            <div id="cameraControls" class="camera-controls" style="display: none;">
                                <button type="button" class="camera-btn-flip" onclick="flipCamera()" title="Ganti Kamera">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20">
                                        <path d="M8 3H5a2 2 0 00-2 2v3m18 0V5a2 2 0 00-2-2h-3m0 18h3a2 2 0 002-2v-3M3 16v3a2 2 0 002 2h3"/>
                                    </svg>
                                    <span>Flip</span>
                                </button>
                                <button type="button" class="camera-btn-capture" onclick="capturePhoto()" title="Ambil Foto">
                                    <div class="camera-btn-capture-inner"></div>
                                </button>
                                <div style="width: 52px;"></div>
                            </div>

                            {{-- Capture Controls (show after capture) --}}
                            <div id="captureControls" class="capture-controls" style="display: none;">
                                <button type="button" class="capture-btn-retake" onclick="retakePhoto()">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18">
                                        <path d="M1 4v6h6M23 20v-6h-6"/>
                                        <path d="M20.49 9A9 9 0 005.64 5.64L1 10m22 4l-4.64 4.36A9 9 0 013.51 15"/>
                                    </svg>
                                    Ambil Ulang
                                </button>
                                <button type="button" class="capture-btn-use" onclick="usePhoto()">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18">
                                        <path d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Gunakan Foto
                                </button>
                            </div>

                            <p id="cameraError" class="camera-error" style="display: none;">
                                Tidak dapat mengakses kamera. Pastikan izin kamera diberikan.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- ═══════════════════════════════════════════════════════════
                     STYLES CROPPER MODAL
                     ═══════════════════════════════════════════════════════════ --}}
                <style>
                    /* ═══════════════════════════════════════════════════════════
                       CROPPER MODAL - NEO MIRAI THEME
                       Palette: --gold, --sun, --ink, --paper, --rice, --line
                       ═══════════════════════════════════════════════════════════ */
                    .cropper-modal-overlay {
                        position: fixed;
                        inset: 0;
                        background: rgba(28, 25, 23, 0.72);
                        backdrop-filter: blur(6px);
                        -webkit-backdrop-filter: blur(6px);
                        z-index: 9999;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        padding: 1rem;
                        animation: cropperFadeIn 0.2s ease-out;
                    }

                    @keyframes cropperFadeIn {
                        from { opacity: 0; }
                        to { opacity: 1; }
                    }

                    .cropper-modal-box {
                        background: var(--rice, #fafaf9);
                        border-radius: 20px;
                        width: 100%;
                        max-width: 640px;
                        max-height: 92vh;
                        display: flex;
                        flex-direction: column;
                        box-shadow: 0 30px 60px -12px rgba(28, 25, 23, 0.45),
                                    0 0 0 1px var(--line, #d6d3d1);
                        overflow: hidden;
                        animation: cropperSlideUp 0.28s cubic-bezier(0.4, 0, 0.2, 1);
                        border: none;
                    }

                    @keyframes cropperSlideUp {
                        from { opacity: 0; transform: translateY(20px) scale(0.96); }
                        to { opacity: 1; transform: translateY(0) scale(1); }
                    }

                    /* Header dengan aksen gold NEO MIRAI */
                    .cropper-modal-header {
                        display: flex;
                        align-items: flex-start;
                        padding: 1.25rem 1.5rem;
                        border-bottom: 1px solid var(--line, #d6d3d1);
                        background: linear-gradient(135deg, var(--paper, #f5f5f4) 0%, var(--rice, #fafaf9) 100%);
                        gap: 0.875rem;
                        position: relative;
                    }
                    .cropper-modal-header::before {
                        content: "";
                        position: absolute;
                        left: 0;
                        top: 0;
                        bottom: 0;
                        width: 4px;
                        background: linear-gradient(180deg, var(--gold, #c9a227) 0%, var(--sun, #f59e0b) 100%);
                    }

                    .cropper-modal-header-icon {
                        width: 44px;
                        height: 44px;
                        border-radius: 12px;
                        background: linear-gradient(135deg, var(--gold, #c9a227) 0%, var(--sun, #f59e0b) 100%);
                        color: var(--ink, #1c1917);
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        flex-shrink: 0;
                        box-shadow: 0 4px 12px rgba(201, 162, 39, 0.35);
                    }

                    .cropper-modal-header-text {
                        flex: 1;
                        min-width: 0;
                    }

                    .cropper-modal-badge {
                        display: inline-block;
                        font-family: var(--font-mono, 'Azeret Mono', monospace);
                        font-size: 0.65rem;
                        font-weight: 700;
                        letter-spacing: 0.08em;
                        color: var(--gold-dark, #a8871f);
                        background: rgba(201, 162, 39, 0.14);
                        padding: 2px 8px;
                        border-radius: 4px;
                        margin-bottom: 4px;
                    }

                    .cropper-modal-title {
                        margin: 0 0 2px 0;
                        font-family: var(--font-display, 'Chakra Petch', sans-serif);
                        font-size: 1.15rem;
                        font-weight: 700;
                        color: var(--ink, #1c1917);
                        line-height: 1.2;
                    }

                    .cropper-modal-subtitle {
                        margin: 0;
                        font-size: 0.8125rem;
                        color: var(--ink-soft, #78716c);
                        line-height: 1.4;
                    }

                    .cropper-modal-close {
                        background: var(--paper, #f5f5f4);
                        border: 1px solid var(--line, #d6d3d1);
                        width: 34px;
                        height: 34px;
                        border-radius: 10px;
                        cursor: pointer;
                        color: var(--ink-soft, #78716c);
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        flex-shrink: 0;
                        transition: all 0.2s ease;
                    }

                    .cropper-modal-close:hover {
                        background: var(--ink, #1c1917);
                        color: var(--gold, #c9a227);
                        border-color: var(--ink, #1c1917);
                        transform: rotate(90deg);
                    }

                    .cropper-modal-body {
                        padding: 1.5rem;
                        overflow-y: auto;
                        flex: 1;
                        background: var(--rice, #fafaf9);
                    }

                    /* Wrapper cropper dengan bg gelap ala NEO MIRAI */
                    .cropper-image-wrapper {
                        background: var(--night, #1c1917);
                        border-radius: 14px;
                        overflow: hidden;
                        max-height: 420px;
                        min-height: 300px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        border: 2px solid var(--ink-soft, #78716c);
                        box-shadow: inset 0 0 0 1px rgba(201, 162, 39, 0.15);
                    }

                    .cropper-image-wrapper img {
                        display: block;
                        max-width: 100%;
                    }

                    /* Crop box lingkaran - override cropperjs default */
                    .cropper-round .cropper-view-box,
                    .cropper-round .cropper-face {
                        border-radius: 50% !important;
                    }
                    .cropper-round .cropper-view-box {
                        outline: 3px solid var(--gold, #c9a227) !important;
                        outline-color: var(--gold, #c9a227) !important;
                        box-shadow: 0 0 0 9999px rgba(28, 25, 23, 0.55);
                    }
                    .cropper-round .cropper-line,
                    .cropper-round .cropper-point {
                        background-color: var(--gold, #c9a227) !important;
                    }
                    .cropper-round .cropper-point {
                        width: 8px !important;
                        height: 8px !important;
                        opacity: 0.9;
                    }
                    .cropper-round .cropper-point.point-se {
                        width: 10px !important;
                        height: 10px !important;
                    }
                    .cropper-round .cropper-dashed {
                        border-color: rgba(201, 162, 39, 0.6) !important;
                    }
                    .cropper-round .cropper-modal {
                        background-color: var(--night, #1c1917);
                        opacity: 0.65;
                    }

                    /* Hint info */
                    .cropper-hint {
                        display: flex;
                        align-items: center;
                        gap: 6px;
                        margin: 12px 0 0;
                        padding: 8px 12px;
                        background: rgba(201, 162, 39, 0.08);
                        border-left: 3px solid var(--gold, #c9a227);
                        border-radius: 6px;
                        font-size: 0.78rem;
                        color: var(--ink-soft, #78716c);
                    }
                    .cropper-hint svg {
                        color: var(--gold-dark, #a8871f);
                        flex-shrink: 0;
                    }

                    .cropper-tools {
                        display: flex;
                        gap: 0.5rem;
                        flex-wrap: wrap;
                        justify-content: center;
                        margin-top: 1rem;
                        padding: 0.75rem;
                        background: var(--paper, #f5f5f4);
                        border-radius: 12px;
                        border: 1px solid var(--line, #d6d3d1);
                    }

                    .cropper-tool-btn {
                        display: inline-flex;
                        align-items: center;
                        gap: 6px;
                        padding: 8px 14px;
                        background: var(--rice, #fafaf9);
                        color: var(--ink, #1c1917);
                        border: 1px solid var(--line, #d6d3d1);
                        border-radius: 8px;
                        font-family: var(--font-body, 'Instrument Sans', sans-serif);
                        font-size: 0.8125rem;
                        font-weight: 600;
                        cursor: pointer;
                        transition: all 0.2s ease;
                    }

                    .cropper-tool-btn:hover {
                        background: var(--gold, #c9a227);
                        color: var(--ink, #1c1917);
                        border-color: var(--gold-dark, #a8871f);
                        transform: translateY(-1px);
                        box-shadow: 0 4px 10px rgba(201, 162, 39, 0.3);
                    }

                    .cropper-tool-btn:active {
                        transform: translateY(0);
                    }

                    /* Footer */
                    .cropper-modal-footer {
                        display: flex;
                        justify-content: flex-end;
                        gap: 0.75rem;
                        padding: 1rem 1.5rem;
                        border-top: 1px solid var(--line, #d6d3d1);
                        background: var(--paper, #f5f5f4);
                    }

                    .cropper-btn-cancel,
                    .cropper-btn-apply {
                        display: inline-flex;
                        align-items: center;
                        gap: 6px;
                        padding: 10px 20px;
                        border-radius: 10px;
                        font-family: var(--font-body, 'Instrument Sans', sans-serif);
                        font-size: 0.875rem;
                        font-weight: 600;
                        cursor: pointer;
                        border: none;
                        transition: all 0.2s ease;
                    }

                    .cropper-btn-cancel {
                        background: var(--rice, #fafaf9);
                        color: var(--ink-soft, #78716c);
                        border: 1px solid var(--line, #d6d3d1);
                    }

                    .cropper-btn-cancel:hover {
                        background: var(--paper, #f5f5f4);
                        color: var(--ink, #1c1917);
                        border-color: var(--ink-soft, #78716c);
                    }

                    .cropper-btn-apply {
                        background: linear-gradient(135deg, var(--gold, #c9a227) 0%, var(--sun, #f59e0b) 100%);
                        color: var(--ink, #1c1917);
                        box-shadow: 0 4px 12px rgba(201, 162, 39, 0.35);
                        font-weight: 700;
                    }

                    .cropper-btn-apply:hover {
                        background: linear-gradient(135deg, var(--gold-bright, #e3b941) 0%, var(--sun, #f59e0b) 100%);
                        transform: translateY(-1px);
                        box-shadow: 0 6px 16px rgba(201, 162, 39, 0.45);
                    }

                    .cropper-btn-apply:active {
                        transform: translateY(0);
                    }

                    /* Mobile responsive */
                    @media (max-width: 640px) {
                        .cropper-modal-box {
                            max-height: 95vh;
                            border-radius: 16px;
                        }
                        .cropper-modal-header {
                            padding: 1rem 1rem 1rem 1.25rem;
                            gap: 0.625rem;
                        }
                        .cropper-modal-header-icon {
                            width: 38px;
                            height: 38px;
                        }
                        .cropper-modal-title {
                            font-size: 1rem;
                        }
                        .cropper-modal-body {
                            padding: 1rem;
                        }
                        .cropper-image-wrapper {
                            max-height: 340px;
                            min-height: 240px;
                        }
                        .cropper-tool-btn span {
                            display: none;
                        }
                        .cropper-tool-btn {
                            padding: 10px;
                        }
                        .cropper-modal-footer {
                            padding: 0.875rem 1rem;
                        }
                    }

                    /* Dark mode - NEO MIRAI dark palette */
                    :root:not([data-theme="light"]) .cropper-modal-box,
                    :root[data-theme="dark"] .cropper-modal-box {
                        background: var(--night-soft, #292524);
                        box-shadow: 0 30px 60px -12px rgba(0, 0, 0, 0.7),
                                    0 0 0 1px rgba(201, 162, 39, 0.2);
                    }
                    :root:not([data-theme="light"]) .cropper-modal-header,
                    :root[data-theme="dark"] .cropper-modal-header {
                        background: linear-gradient(135deg, var(--night, #1c1917) 0%, var(--night-soft, #292524) 100%);
                        border-bottom-color: rgba(201, 162, 39, 0.15);
                    }
                    :root:not([data-theme="light"]) .cropper-modal-title,
                    :root[data-theme="dark"] .cropper-modal-title { color: var(--rice, #fafaf9); }
                    :root:not([data-theme="light"]) .cropper-modal-subtitle,
                    :root[data-theme="dark"] .cropper-modal-subtitle { color: #a8a29e; }
                    :root:not([data-theme="light"]) .cropper-modal-body,
                    :root[data-theme="dark"] .cropper-modal-body { background: var(--night-soft, #292524); }
                    :root:not([data-theme="light"]) .cropper-modal-close,
                    :root[data-theme="dark"] .cropper-modal-close {
                        background: rgba(255, 255, 255, 0.05);
                        border-color: rgba(255, 255, 255, 0.1);
                        color: #d6d3d1;
                    }
                    :root:not([data-theme="light"]) .cropper-modal-close:hover,
                    :root[data-theme="dark"] .cropper-modal-close:hover {
                        background: var(--gold, #c9a227);
                        color: var(--ink, #1c1917);
                        border-color: var(--gold, #c9a227);
                    }
                    :root:not([data-theme="light"]) .cropper-tools,
                    :root[data-theme="dark"] .cropper-tools {
                        background: var(--night, #1c1917);
                        border-color: rgba(255, 255, 255, 0.08);
                    }
                    :root:not([data-theme="light"]) .cropper-tool-btn,
                    :root[data-theme="dark"] .cropper-tool-btn {
                        background: var(--night-soft, #292524);
                        color: var(--rice, #fafaf9);
                        border-color: rgba(255, 255, 255, 0.1);
                    }
                    :root:not([data-theme="light"]) .cropper-hint,
                    :root[data-theme="dark"] .cropper-hint {
                        background: rgba(201, 162, 39, 0.12);
                        color: #d6d3d1;
                    }
                    :root:not([data-theme="light"]) .cropper-modal-footer,
                    :root[data-theme="dark"] .cropper-modal-footer {
                        background: var(--night, #1c1917);
                        border-top-color: rgba(255, 255, 255, 0.08);
                    }
                    :root:not([data-theme="light"]) .cropper-btn-cancel,
                    :root[data-theme="dark"] .cropper-btn-cancel {
                        background: var(--night-soft, #292524);
                        color: #d6d3d1;
                        border-color: rgba(255, 255, 255, 0.15);
                    }
                    :root:not([data-theme="light"]) .cropper-btn-cancel:hover,
                    :root[data-theme="dark"] .cropper-btn-cancel:hover {
                        background: var(--night, #1c1917);
                        color: var(--rice, #fafaf9);
                    }

                    /* ═══════════════════════════════════════════════════════════
                       PROFILE WARNING BANNER - MENCOLOK & MENONJOL
                       ═══════════════════════════════════════════════════════════ */

                    .profile-warning-banner {
                        background: linear-gradient(135deg, #dc2626 0%, #b91c1c 50%, #991b1b 100%);
                        border-radius: 16px;
                        overflow: hidden;
                        margin-bottom: 1.5rem;
                        box-shadow:
                            0 10px 40px rgba(220, 38, 38, 0.4),
                            0 0 0 1px rgba(255, 255, 255, 0.1) inset;
                        animation: bannerSlideIn 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
                        position: relative;
                    }

                    @keyframes bannerSlideIn {
                        from {
                            opacity: 0;
                            transform: translateY(-20px) scale(0.95);
                        }
                        to {
                            opacity: 1;
                            transform: translateY(0) scale(1);
                        }
                    }

                    @keyframes bannerPulse {
                        0%, 100% {
                            box-shadow:
                                0 10px 40px rgba(220, 38, 38, 0.4),
                                0 0 0 1px rgba(255, 255, 255, 0.1) inset;
                        }
                        50% {
                            box-shadow:
                                0 15px 50px rgba(220, 38, 38, 0.6),
                                0 0 0 2px rgba(255, 255, 255, 0.2) inset;
                        }
                    }

                    .profile-warning-banner {
                        animation: bannerSlideIn 0.5s cubic-bezier(0.34, 1.56, 0.64, 1),
                                   bannerPulse 2s ease-in-out infinite;
                    }

                    .warning-banner-inner {
                        padding: 1.5rem 1.75rem;
                        display: flex;
                        gap: 1.25rem;
                        align-items: flex-start;
                    }

                    /* Warning Icon dengan Pulsing Animation */
                    .warning-icon-wrapper {
                        position: relative;
                        width: 56px;
                        height: 56px;
                        flex-shrink: 0;
                    }

                    .warning-pulse-ring {
                        position: absolute;
                        inset: 0;
                        border-radius: 50%;
                        border: 3px solid rgba(255, 255, 255, 0.5);
                        animation: pulseRing 1.5s ease-out infinite;
                    }

                    .warning-pulse-ring-delay {
                        animation-delay: 0.5s;
                    }

                    @keyframes pulseRing {
                        0% {
                            transform: scale(0.8);
                            opacity: 1;
                        }
                        100% {
                            transform: scale(1.5);
                            opacity: 0;
                        }
                    }

                    .warning-icon {
                        width: 56px;
                        height: 56px;
                        color: #fef08a;
                        position: relative;
                        z-index: 1;
                        filter: drop-shadow(0 2px 8px rgba(0, 0, 0, 0.3));
                        animation: iconBounce 1s ease-in-out infinite;
                    }

                    @keyframes iconBounce {
                        0%, 100% { transform: translateY(0); }
                        50% { transform: translateY(-4px); }
                    }

                    /* Content */
                    .warning-content {
                        flex: 1;
                        min-width: 0;
                    }

                    .warning-title {
                        margin: 0 0 0.5rem 0;
                        font-family: var(--font-display, 'Chakra Petch', sans-serif);
                        font-size: 1.35rem;
                        font-weight: 800;
                        color: #ffffff;
                        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
                        letter-spacing: 0.02em;
                        line-height: 1.3;
                    }

                    .warning-subtitle {
                        margin: 0 0 1rem 0;
                        font-size: 0.95rem;
                        color: rgba(255, 255, 255, 0.9);
                        line-height: 1.5;
                    }

                    .warning-subtitle strong {
                        color: #fef08a;
                    }

                    /* Checklist Grid */
                    .warning-checklist {
                        display: grid;
                        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
                        gap: 0.75rem;
                        margin-bottom: 1rem;
                    }

                    .warning-item {
                        display: flex;
                        align-items: center;
                        gap: 0.5rem;
                        padding: 0.625rem 0.875rem;
                        background: rgba(255, 255, 255, 0.15);
                        border-radius: 10px;
                        border: 1px solid rgba(255, 255, 255, 0.2);
                        backdrop-filter: blur(4px);
                        transition: all 0.2s ease;
                    }

                    .warning-item:hover {
                        background: rgba(255, 255, 255, 0.2);
                        transform: translateX(4px);
                    }

                    .warning-item-missing .warning-item-icon {
                        color: #fca5a5;
                        width: 20px;
                        height: 20px;
                        flex-shrink: 0;
                    }

                    .warning-item-text {
                        font-size: 0.875rem;
                        font-weight: 600;
                        color: #ffffff;
                    }

                    /* Call to Action */
                    .warning-cta {
                        display: flex;
                        align-items: center;
                        gap: 0.5rem;
                        padding: 0.5rem 1rem;
                        background: rgba(254, 240, 138, 0.2);
                        border: 1px dashed rgba(254, 240, 138, 0.5);
                        border-radius: 8px;
                        color: #fef08a;
                        font-size: 0.85rem;
                        font-weight: 600;
                    }

                    .warning-arrow {
                        animation: arrowBounce 1s ease-in-out infinite;
                    }

                    .warning-arrow svg {
                        width: 20px;
                        height: 20px;
                    }

                    @keyframes arrowBounce {
                        0%, 100% { transform: translateY(0); }
                        50% { transform: translateY(4px); }
                    }

                    /* Progress Bar */
                    .warning-progress-bar {
                        height: 4px;
                        background: rgba(0, 0, 0, 0.2);
                        position: relative;
                        overflow: hidden;
                    }

                    .warning-progress-fill {
                        height: 100%;
                        width: 30%;
                        background: linear-gradient(90deg, #fbbf24, #f59e0b);
                        border-radius: 0 2px 2px 0;
                        animation: progressShimmer 2s ease-in-out infinite;
                    }

                    @keyframes progressShimmer {
                        0% {
                            width: 10%;
                            opacity: 0.5;
                        }
                        50% {
                            width: 40%;
                            opacity: 1;
                        }
                        100% {
                            width: 10%;
                            opacity: 0.5;
                        }
                    }

                    /* Dark mode adjustments */
                    :root:not([data-theme="light"]) .profile-warning-banner,
                    :root[data-theme="dark"] .profile-warning-banner {
                        background: linear-gradient(135deg, #7f1d1d 0%, #991b1b 50%, #b91c1c 100%);
                        box-shadow:
                            0 10px 40px rgba(127, 29, 29, 0.5),
                            0 0 0 1px rgba(255, 255, 255, 0.05) inset;
                    }

                    /* ═══════════════════════════════════════════════════════════
                       CAMERA MODAL - NEO MIRAI THEME
                       ═══════════════════════════════════════════════════════════ */
                    .camera-modal-overlay {
                        position: fixed;
                        inset: 0;
                        background: rgba(0, 0, 0, 0.85);
                        backdrop-filter: blur(8px);
                        z-index: 99999;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        padding: 1rem;
                        animation: cameraFadeIn 0.25s ease-out;
                    }

                    @keyframes cameraFadeIn {
                        from { opacity: 0; }
                        to { opacity: 1; }
                    }

                    .camera-modal-box {
                        background: var(--rice, #fafaf9);
                        border-radius: 20px;
                        width: 100%;
                        max-width: 480px;
                        display: flex;
                        flex-direction: column;
                        box-shadow: 0 25px 80px rgba(0, 0, 0, 0.5);
                        overflow: hidden;
                        animation: cameraSlideUp 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
                    }

                    @keyframes cameraSlideUp {
                        from { opacity: 0; transform: translateY(30px) scale(0.9); }
                        to { opacity: 1; transform: translateY(0) scale(1); }
                    }

                    /* Header */
                    .camera-modal-header {
                        display: flex;
                        align-items: flex-start;
                        padding: 1.25rem 1.5rem;
                        border-bottom: 1px solid var(--line, #d6d3d1);
                        background: linear-gradient(135deg, var(--paper, #f5f5f4) 0%, var(--rice, #fafaf9) 100%);
                        gap: 0.875rem;
                        position: relative;
                    }
                    .camera-modal-header::before {
                        content: "";
                        position: absolute;
                        left: 0;
                        top: 0;
                        bottom: 0;
                        width: 4px;
                        background: linear-gradient(180deg, #059669 0%, #047857 100%);
                    }

                    .camera-modal-header-icon {
                        width: 44px;
                        height: 44px;
                        border-radius: 12px;
                        background: linear-gradient(135deg, #059669 0%, #047857 100%);
                        color: white;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        flex-shrink: 0;
                        box-shadow: 0 4px 12px rgba(5, 150, 105, 0.35);
                    }

                    .camera-modal-header-text {
                        flex: 1;
                        min-width: 0;
                    }

                    .camera-modal-badge {
                        display: inline-block;
                        font-family: var(--font-mono, 'Azeret Mono', monospace);
                        font-size: 0.65rem;
                        font-weight: 700;
                        letter-spacing: 0.08em;
                        color: #047857;
                        background: rgba(5, 150, 105, 0.12);
                        padding: 2px 8px;
                        border-radius: 4px;
                        margin-bottom: 4px;
                    }

                    .camera-modal-title {
                        margin: 0 0 2px 0;
                        font-family: var(--font-display, 'Chakra Petch', sans-serif);
                        font-size: 1.1rem;
                        font-weight: 700;
                        color: var(--ink, #1c1917);
                        line-height: 1.2;
                    }

                    .camera-modal-subtitle {
                        margin: 0;
                        font-size: 0.8125rem;
                        color: var(--ink-soft, #78716c);
                    }

                    .camera-modal-close {
                        background: var(--paper, #f5f5f4);
                        border: 1px solid var(--line, #d6d3d1);
                        width: 34px;
                        height: 34px;
                        border-radius: 10px;
                        cursor: pointer;
                        color: var(--ink-soft, #78716c);
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        flex-shrink: 0;
                        transition: all 0.2s ease;
                    }
                    .camera-modal-close:hover {
                        background: var(--ink, #1c1917);
                        color: white;
                        border-color: var(--ink, #1c1917);
                        transform: rotate(90deg);
                    }

                    /* Body */
                    .camera-modal-body {
                        padding: 1.5rem;
                        background: var(--rice, #fafaf9);
                    }

                    /* Preview Wrapper */
                    .camera-preview-wrapper {
                        position: relative;
                        background: #1c1917;
                        border-radius: 16px;
                        overflow: hidden;
                        aspect-ratio: 1;
                        max-height: 360px;
                        margin: 0 auto;
                        border: 3px solid var(--line, #d6d3d1);
                    }

                    .camera-preview-wrapper video,
                    .camera-preview-wrapper canvas,
                    .camera-preview-wrapper img {
                        position: absolute;
                        inset: 0;
                        width: 100%;
                        height: 100%;
                        object-fit: cover;
                    }

                    /* Placeholder */
                    .camera-placeholder {
                        position: absolute;
                        inset: 0;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        background: linear-gradient(135deg, #1c1917 0%, #292524 100%);
                    }

                    .camera-placeholder-inner {
                        text-align: center;
                        color: #78716c;
                    }

                    .camera-placeholder-inner svg {
                        margin-bottom: 0.75rem;
                        opacity: 0.5;
                        animation: cameraPulse 2s ease-in-out infinite;
                    }

                    @keyframes cameraPulse {
                        0%, 100% { opacity: 0.3; transform: scale(1); }
                        50% { opacity: 0.6; transform: scale(1.05); }
                    }

                    .camera-placeholder-inner p {
                        margin: 0;
                        font-size: 0.875rem;
                    }

                    /* Face Circle Overlay */
                    .camera-face-circle {
                        position: absolute;
                        top: 50%;
                        left: 50%;
                        transform: translate(-50%, -50%);
                        width: 65%;
                        aspect-ratio: 1;
                        border: 3px dashed rgba(201, 162, 39, 0.8);
                        border-radius: 50%;
                        box-shadow: 0 0 0 9999px rgba(0, 0, 0, 0.4);
                        pointer-events: none;
                        animation: faceCirclePulse 2s ease-in-out infinite;
                    }

                    @keyframes faceCirclePulse {
                        0%, 100% { border-color: rgba(201, 162, 39, 0.6); }
                        50% { border-color: rgba(201, 162, 39, 1); }
                    }

                    .camera-face-guide {
                        position: absolute;
                        bottom: 1rem;
                        left: 50%;
                        transform: translateX(-50%);
                        background: rgba(0, 0, 0, 0.6);
                        color: white;
                        padding: 6px 14px;
                        border-radius: 20px;
                        font-size: 0.75rem;
                        font-weight: 500;
                        pointer-events: none;
                    }

                    /* Camera Controls */
                    .camera-controls {
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        gap: 1.5rem;
                        margin-top: 1.25rem;
                    }

                    .camera-btn-flip {
                        width: 52px;
                        height: 52px;
                        border-radius: 50%;
                        background: var(--paper, #f5f5f4);
                        border: 2px solid var(--line, #d6d3d1);
                        cursor: pointer;
                        display: flex;
                        flex-direction: column;
                        align-items: center;
                        justify-content: center;
                        gap: 2px;
                        color: var(--ink, #1c1917);
                        transition: all 0.2s ease;
                    }

                    .camera-btn-flip:hover {
                        background: var(--gold, #c9a227);
                        border-color: var(--gold, #c9a227);
                        color: var(--ink, #1c1917);
                    }

                    .camera-btn-flip span {
                        font-size: 0.6rem;
                        font-weight: 600;
                    }

                    .camera-btn-capture {
                        width: 72px;
                        height: 72px;
                        border-radius: 50%;
                        background: white;
                        border: 4px solid var(--line, #d6d3d1);
                        cursor: pointer;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        transition: all 0.2s ease;
                        padding: 4px;
                    }

                    .camera-btn-capture:hover {
                        transform: scale(1.05);
                    }

                    .camera-btn-capture:active {
                        transform: scale(0.95);
                    }

                    .camera-btn-capture-inner {
                        width: 100%;
                        height: 100%;
                        border-radius: 50%;
                        background: linear-gradient(135deg, #059669 0%, #047857 100%);
                        transition: all 0.2s ease;
                    }

                    .camera-btn-capture:hover .camera-btn-capture-inner {
                        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
                        box-shadow: 0 0 20px rgba(5, 150, 105, 0.5);
                    }

                    /* Capture Controls */
                    .capture-controls {
                        display: flex;
                        gap: 1rem;
                        margin-top: 1.25rem;
                    }

                    .capture-btn-retake,
                    .capture-btn-use {
                        flex: 1;
                        display: inline-flex;
                        align-items: center;
                        justify-content: center;
                        gap: 0.5rem;
                        padding: 12px 20px;
                        border-radius: 12px;
                        font-family: var(--font-body, 'Instrument Sans', sans-serif);
                        font-size: 0.875rem;
                        font-weight: 600;
                        cursor: pointer;
                        border: none;
                        transition: all 0.2s ease;
                    }

                    .capture-btn-retake {
                        background: var(--paper, #f5f5f4);
                        color: var(--ink-soft, #78716c);
                        border: 1px solid var(--line, #d6d3d1);
                    }

                    .capture-btn-retake:hover {
                        background: var(--line, #d6d3d1);
                        color: var(--ink, #1c1917);
                    }

                    .capture-btn-use {
                        background: linear-gradient(135deg, #059669 0%, #047857 100%);
                        color: white;
                        box-shadow: 0 4px 12px rgba(5, 150, 105, 0.3);
                    }

                    .capture-btn-use:hover {
                        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
                        transform: translateY(-1px);
                        box-shadow: 0 6px 16px rgba(5, 150, 105, 0.4);
                    }

                    /* Error */
                    .camera-error {
                        text-align: center;
                        color: #dc2626;
                        font-size: 0.875rem;
                        margin-top: 1rem;
                        padding: 0.75rem;
                        background: rgba(220, 38, 38, 0.1);
                        border-radius: 8px;
                    }

                    /* Dark mode */
                    :root:not([data-theme="light"]) .camera-modal-box,
                    :root[data-theme="dark"] .camera-modal-box {
                        background: var(--night-soft, #292524);
                        box-shadow: 0 25px 80px rgba(0, 0, 0, 0.7);
                    }
                    :root:not([data-theme="light"]) .camera-modal-header,
                    :root[data-theme="dark"] .camera-modal-header {
                        background: linear-gradient(135deg, var(--night, #1c1917) 0%, var(--night-soft, #292524) 100%);
                        border-bottom-color: rgba(255, 255, 255, 0.08);
                    }
                    :root:not([data-theme="light"]) .camera-modal-title,
                    :root[data-theme="dark"] .camera-modal-title { color: var(--rice, #fafaf9); }
                    :root:not([data-theme="light"]) .camera-modal-close,
                    :root[data-theme="dark"] .camera-modal-close {
                        background: rgba(255, 255, 255, 0.05);
                        border-color: rgba(255, 255, 255, 0.1);
                        color: #d6d3d1;
                    }
                    :root:not([data-theme="light"]) .camera-btn-flip {
                        background: var(--night, #1c1917);
                        border-color: rgba(255, 255, 255, 0.1);
                        color: var(--rice, #fafaf9);
                    }
                    :root:not([data-theme="light"]) .capture-btn-retake {
                        background: var(--night, #1c1917);
                        border-color: rgba(255, 255, 255, 0.1);
                        color: #a8a29e;
                    }
                    :root:not([data-theme="dark"]) .camera-preview-wrapper {
                        border-color: rgba(0, 0, 0, 0.2);
                    }

                    /* Responsive */
                    @media (max-width: 640px) {
                        .warning-banner-inner {
                            flex-direction: column;
                            align-items: center;
                            text-align: center;
                            padding: 1.25rem;
                        }

                        .warning-icon-wrapper {
                            width: 64px;
                            height: 64px;
                        }

                        .warning-icon {
                            width: 64px;
                            height: 64px;
                        }

                        .warning-title {
                            font-size: 1.15rem;
                        }

                        .warning-checklist {
                            grid-template-columns: 1fr;
                        }

                        .warning-cta {
                            justify-content: center;
                        }
                    }
                </style>

                <script>
                    // ═══════════════════════════════════════════════════════════
                    // CROPPER FOTO PROFIL
                    // ═══════════════════════════════════════════════════════════
                    let cropperInstance = null;
                    let selectedFileName = 'foto-profil.jpg';
                    let selectedFileType = 'image/jpeg';

                    /**
                     * Buka modal cropper saat user pilih file
                     */
                    function openCropperModal(event) {
                        const file = event.target.files[0];
                        if (!file) return;

                        // Validasi ukuran (2MB max)
                        if (file.size > 2 * 1024 * 1024) {
                            alert('Ukuran foto maksimal 2MB. File Anda: ' + (file.size / 1024 / 1024).toFixed(2) + ' MB');
                            event.target.value = '';
                            return;
                        }

                        // Validasi tipe file
                        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
                        if (!allowedTypes.includes(file.type)) {
                            alert('Format file tidak didukung. Gunakan JPG, PNG, atau WEBP.');
                            event.target.value = '';
                            return;
                        }

                        selectedFileName = file.name;
                        selectedFileType = file.type;

                        // Baca file → tampilkan di cropper
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const img = document.getElementById('cropperImage');
                            img.src = e.target.result;

                            // Show modal
                            const modal = document.getElementById('cropperModal');
                            modal.style.display = 'flex';
                            document.body.style.overflow = 'hidden';

                            // Destroy instance lama jika ada
                            if (cropperInstance) {
                                cropperInstance.destroy();
                                cropperInstance = null;
                            }

                            // Inisialisasi Cropper dengan aspect ratio 1:1 (square) untuk foto profil
                            cropperInstance = new Cropper(img, {
                                aspectRatio: 1,
                                viewMode: 1,
                                dragMode: 'move',
                                autoCropArea: 0.85,
                                restore: false,
                                guides: true,
                                center: true,
                                highlight: false,
                                cropBoxMovable: true,
                                cropBoxResizable: true,
                                toggleDragModeOnDblclick: false,
                                background: false,
                                responsive: true,
                                minContainerWidth: 200,
                                minContainerHeight: 200,
                            });
                        };
                        reader.readAsDataURL(file);
                    }

                    /**
                     * Tutup modal (batal)
                     */
                    function closeCropperModal() {
                        const modal = document.getElementById('cropperModal');
                        modal.style.display = 'none';
                        document.body.style.overflow = '';

                        if (cropperInstance) {
                            cropperInstance.destroy();
                            cropperInstance = null;
                        }

                        // Reset selector agar bisa pilih file yang sama lagi
                        document.getElementById('pp_selector').value = '';
                    }

                    /**
                     * Terapkan hasil crop → convert ke Blob → set ke input file.
                     * Output berupa foto lingkaran; kompresi final dilakukan server-side oleh Intervention Image.
                     */
                    async function applyCrop() {
                        if (!cropperInstance) return;

                        try {
                            // Get cropped canvas (square 800x800 untuk optimasi)
                            const squareCanvas = cropperInstance.getCroppedCanvas({
                                width: 800,
                                height: 800,
                                minWidth: 200,
                                minHeight: 200,
                                maxWidth: 1200,
                                maxHeight: 1200,
                                fillColor: '#ffffff',
                                imageSmoothingEnabled: true,
                                imageSmoothingQuality: 'high',
                            });

                            if (!squareCanvas) {
                                alert('Gagal memproses foto. Silakan coba lagi.');
                                return;
                            }

                            // Buat canvas baru untuk hasil lingkaran (PNG transparan)
                            const size = squareCanvas.width;
                            const canvas = document.createElement('canvas');
                            canvas.width = size;
                            canvas.height = size;
                            const ctx = canvas.getContext('2d');

                            // Clip menjadi lingkaran
                            ctx.save();
                            ctx.beginPath();
                            ctx.arc(size / 2, size / 2, size / 2, 0, Math.PI * 2, true);
                            ctx.closePath();
                            ctx.clip();
                            ctx.drawImage(squareCanvas, 0, 0, size, size);
                            ctx.restore();

                            // Convert canvas → Blob
                            const circleBlob = await new Promise((resolve, reject) => {
                                canvas.toBlob((blob) => {
                                    if (blob) resolve(blob);
                                    else reject(new Error('Canvas toBlob failed'));
                                }, 'image/png');
                            });

                            // Canvas sudah membatasi hasil crop menjadi 800x800 sehingga payload jauh lebih
                            // ringan sebelum dikirim. Kompresi final dilakukan server-side dengan Intervention Image.
                            const compressedBlob = circleBlob;
                            console.info('Foto profil siap diunggah:', (compressedBlob.size / 1024).toFixed(2), 'KB');

                            // Validasi ukuran payload sebelum submit.
                            if (compressedBlob.size > 2 * 1024 * 1024) {
                                alert('Ukuran file foto masih > 2MB (' + (compressedBlob.size / 1024 / 1024).toFixed(2) + ' MB). Silakan crop area yang lebih kecil.');
                                return;
                            }

                            // PNG dipakai agar hasil crop transparan tetap terjaga.
                            const ext = 'png';
                            const baseName = selectedFileName.replace(/\.[^.]+$/, '') || 'foto-profil';
                            const finalName = baseName + '.' + ext;

                            const croppedFile = new File([compressedBlob], finalName, {
                                type: 'image/png',
                                lastModified: Date.now(),
                            });

                            // Assign ke input file via DataTransfer
                            const dataTransfer = new DataTransfer();
                            dataTransfer.items.add(croppedFile);
                            document.getElementById('pp').files = dataTransfer.files;

                            // Update preview avatar
                            const previewUrl = URL.createObjectURL(compressedBlob);
                            const preview = document.getElementById('avatar-preview');
                            const initials = document.getElementById('avatar-initials');
                            if (preview) {
                                preview.src = previewUrl;
                                preview.style.display = 'block';
                                if (initials) initials.style.display = 'none';
                            }

                            // Update label & status
                            const labelText = document.getElementById('pp-label-text');
                            if (labelText) labelText.textContent = 'Ganti Foto Profil';
                            const status = document.getElementById('pp-crop-status');
                            if (status) {
                                status.innerHTML = '✓ Foto siap diupload (' + (compressedBlob.size / 1024).toFixed(1) + ' KB)';
                                status.style.display = 'block';
                            }

                            closeCropperModal();
                        } catch (error) {
                            console.error('Error saat memproses crop:', error);
                            alert('Gagal memproses foto. Error: ' + error.message);
                        }
                    }

                    /**
                     * Zoom control
                     */
                    function cropperZoom(delta) {
                        if (cropperInstance) cropperInstance.zoom(delta);
                    }

                    /**
                     * Rotate control
                     */
                    function cropperRotate(degree) {
                        if (cropperInstance) cropperInstance.rotate(degree);
                    }

                    /**
                     * Reset ke posisi awal
                     */
                    function cropperReset() {
                        if (cropperInstance) cropperInstance.reset();
                    }

                    // ESC untuk tutup modal
                    document.addEventListener('keydown', function(e) {
                        if (e.key === 'Escape') {
                            const modal = document.getElementById('cropperModal');
                            if (modal && modal.style.display === 'flex') {
                                closeCropperModal();
                            }
                        }
                    });

                    // Klik overlay untuk tutup (tapi tidak jika klik konten modal)
                    document.addEventListener('DOMContentLoaded', function() {
                        const modal = document.getElementById('cropperModal');
                        if (modal) {
                            modal.addEventListener('click', function(e) {
                                if (e.target === modal) {
                                    closeCropperModal();
                                }
                            });
                        }
                    });

                    // ═══════════════════════════════════════════════════════════
                    // CAMERA CAPTURE FUNCTIONALITY
                    // ═══════════════════════════════════════════════════════════
                    let cameraStream = null;
                    let currentFacingMode = 'user';
                    let capturedImageData = null;
                    let mediaStreamTrack = null;

                    /**
                     * Open camera modal and start streaming
                     */
                    async function openCameraModal() {
                        const modal = document.getElementById('cameraModal');
                        const video = document.getElementById('cameraVideo');
                        const placeholder = document.getElementById('cameraPlaceholder');
                        const preview = document.getElementById('cameraPreview');
                        const controls = document.getElementById('cameraControls');
                        const captureControls = document.getElementById('captureControls');
                        const errorMsg = document.getElementById('cameraError');

                        // Reset state
                        video.style.display = 'none';
                        preview.style.display = 'none';
                        placeholder.style.display = 'flex';
                        controls.style.display = 'none';
                        captureControls.style.display = 'none';
                        errorMsg.style.display = 'none';
                        capturedImageData = null;

                        // Show modal
                        modal.style.display = 'flex';
                        document.body.style.overflow = 'hidden';

                        // Start camera
                        await startCamera();
                    }

                    /**
                     * Start camera stream
                     */
                    async function startCamera() {
                        const video = document.getElementById('cameraVideo');
                        const placeholder = document.getElementById('cameraPlaceholder');
                        const controls = document.getElementById('cameraControls');
                        const errorMsg = document.getElementById('cameraError');

                        // Stop any existing stream
                        stopCameraStream();

                        try {
                            // Request camera access
                            const constraints = {
                                video: {
                                    facingMode: currentFacingMode,
                                    width: { ideal: 1280 },
                                    height: { ideal: 1280 }
                                }
                            };

                            cameraStream = await navigator.mediaDevices.getUserMedia(constraints);
                            mediaStreamTrack = cameraStream.getVideoTracks()[0];

                            // Get the settings to check actual resolution
                            const settings = mediaStreamTrack.getSettings();

                            // Attach stream to video
                            video.srcObject = cameraStream;
                            await video.play();

                            // Show video, hide placeholder
                            video.style.display = 'block';
                            placeholder.style.display = 'none';
                            controls.style.display = 'flex';
                            errorMsg.style.display = 'none';

                        } catch (err) {
                            console.error('Camera error:', err);
                            placeholder.style.display = 'flex';
                            const placeholderInner = placeholder.querySelector('.camera-placeholder-inner p');
                            if (placeholderInner) {
                                placeholderInner.textContent = 'Tidak dapat mengakses kamera';
                            }
                            errorMsg.style.display = 'block';
                            errorMsg.textContent = 'Tidak dapat mengakses kamera. Pastikan izin kamera diberikan dan tidak digunakan oleh aplikasi lain.';
                        }
                    }

                    /**
                     * Stop camera stream
                     */
                    function stopCameraStream() {
                        if (cameraStream) {
                            cameraStream.getTracks().forEach(track => track.stop());
                            cameraStream = null;
                        }
                        if (mediaStreamTrack) {
                            mediaStreamTrack = null;
                        }
                    }

                    /**
                     * Flip between front and back camera
                     */
                    async function flipCamera() {
                        currentFacingMode = currentFacingMode === 'user' ? 'environment' : 'user';
                        await startCamera();
                    }

                    /**
                     * Capture photo from video stream
                     */
                    function capturePhoto() {
                        const video = document.getElementById('cameraVideo');
                        const canvas = document.getElementById('cameraCanvas');
                        const preview = document.getElementById('cameraPreview');
                        const controls = document.getElementById('cameraControls');
                        const captureControls = document.getElementById('captureControls');

                        if (!video.srcObject) return;

                        // Set canvas size to match video
                        canvas.width = video.videoWidth;
                        canvas.height = video.videoHeight;

                        // Draw video frame to canvas
                        const ctx = canvas.getContext('2d');
                        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

                        // Stop stream
                        stopCameraStream();

                        // Store image data
                        capturedImageData = canvas.toDataURL('image/jpeg', 0.9);

                        // Show preview
                        preview.src = capturedImageData;
                        preview.style.display = 'block';
                        video.style.display = 'none';

                        // Toggle controls
                        controls.style.display = 'none';
                        captureControls.style.display = 'flex';
                    }

                    /**
                     * Retake photo - go back to camera
                     */
                    async function retakePhoto() {
                        const video = document.getElementById('cameraVideo');
                        const canvas = document.getElementById('cameraCanvas');
                        const preview = document.getElementById('cameraPreview');
                        const controls = document.getElementById('cameraControls');
                        const captureControls = document.getElementById('captureControls');

                        // Reset preview
                        preview.style.display = 'none';
                        capturedImageData = null;

                        // Restart camera
                        await startCamera();

                        // Toggle controls
                        controls.style.display = 'flex';
                        captureControls.style.display = 'none';
                    }

                    /**
                     * Use the captured photo - send to cropper
                     */
                    function usePhoto() {
                        if (!capturedImageData) return;

                        const video = document.getElementById('cameraVideo');
                        const canvas = document.getElementById('cameraCanvas');
                        const preview = document.getElementById('cameraPreview');
                        const controls = document.getElementById('cameraControls');
                        const captureControls = document.getElementById('captureControls');

                        // Convert data URL to blob/file for cropper
                        fetch(capturedImageData)
                            .then(res => res.blob())
                            .then(blob => {
                                // Create a file from blob
                                const file = new File([blob], 'camera-photo.jpg', { type: 'image/jpeg' });

                                // Trigger the cropper with this image
                                const dataTransfer = new DataTransfer();
                                dataTransfer.items.add(file);
                                const ppSelector = document.getElementById('pp_selector');
                                ppSelector.files = dataTransfer.files;

                                // Create a synthetic event
                                const event = new Event('change', { bubbles: true });
                                Object.defineProperty(event, 'target', { value: ppSelector });
                                openCropperModal(event);

                                // Update UI
                                const labelText = document.getElementById('pp-label-text');
                                if (labelText) labelText.textContent = 'Ganti Foto Profil';

                                const status = document.getElementById('pp-crop-status');
                                if (status) {
                                    status.innerHTML = '✓ Foto dari kamera siap di-crop';
                                    status.style.display = 'block';
                                }

                                // Show preview in avatar
                                const avatarPreview = document.getElementById('avatar-preview');
                                const avatarInitials = document.getElementById('avatar-initials');
                                if (avatarPreview) {
                                    avatarPreview.src = capturedImageData;
                                    avatarPreview.style.display = 'block';
                                    if (avatarInitials) avatarInitials.style.display = 'none';
                                }
                            })
                            .catch(err => {
                                console.error('Error processing camera photo:', err);
                                alert('Gagal memproses foto dari kamera.');
                            });

                        // Close camera modal
                        closeCameraModal();

                        // Reset camera modal state for next time
                        preview.style.display = 'none';
                        controls.style.display = 'none';
                        captureControls.style.display = 'none';
                    }

                    /**
                     * Close camera modal
                     */
                    function closeCameraModal() {
                        const modal = document.getElementById('cameraModal');
                        modal.style.display = 'none';
                        document.body.style.overflow = '';
                        stopCameraStream();
                        capturedImageData = null;
                    }

                    // ESC to close camera modal
                    document.addEventListener('keydown', function(e) {
                        if (e.key === 'Escape') {
                            const modal = document.getElementById('cameraModal');
                            if (modal && modal.style.display === 'flex') {
                                closeCameraModal();
                            }
                        }
                    });

                    // Click overlay to close
                    document.addEventListener('DOMContentLoaded', function() {
                        const cameraModal = document.getElementById('cameraModal');
                        if (cameraModal) {
                            cameraModal.addEventListener('click', function(e) {
                                if (e.target === cameraModal) {
                                    closeCameraModal();
                                }
                            });
                        }
                    });
                </script>
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
