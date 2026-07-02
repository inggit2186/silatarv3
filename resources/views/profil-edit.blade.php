<x-layouts.app title="Edit Profil - SILATAR">
    <main class="neo-mirai">
        <x-layouts.site-header />

        <!-- Hero Section -->
        <section class="hero-page" style="background-image: url('/assets/img/template/bg2.webp'); background-size: cover; background-position: center top; padding: 2rem 2rem 4rem;">
            <div style="max-width: 28rem; margin: 0 auto; text-align: center;">
                <a href="{{ route('profil') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem; color: var(--ink-soft); font-family: var(--font-mono); font-size: 0.7rem; text-decoration: none;" onmouseover="this.style.color='var(--gold)'" onmouseout="this.style.color='var(--ink-soft)'">
                    <svg width="14" height="14" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Kembali ke Profil
                </a>

                <span style="display: inline-flex; align-items: center; gap: 0.5rem; border: 1px solid var(--gold); background: oklch(68% 0.145 74 / 0.1); padding: 0.4rem 1rem; border-radius: 9999px; font-family: var(--font-mono); font-size: 0.65rem; text-transform: uppercase; color: var(--gold);">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828l-9.193 9.193a2 2 0 01-2.828 0l-2.172-2.172a2 2 0 010-2.828l9.193-9.193z"/>
                    </svg>
                    Edit Profil
                </span>

                <h1 style="font-family: var(--font-display); font-size: clamp(1.8rem, 4vw, 2.5rem); font-weight: 600; color: var(--ink); margin: 1rem 0 0.5rem;">
                    Edit Data Profil
                </h1>
                <p style="color: var(--ink-soft); font-size: 0.9rem;">Perbarui informasi profil Anda</p>
            </div>
        </section>

        <!-- Section Divider -->
        <div class="section-divider wave-rounded"></div>

        <!-- Edit Form -->
        <section class="page-content">
            <div style="max-width: 48rem; margin: 0 auto;">
                <form method="POST" action="{{ route('profil.update') }}" enctype="multipart/form-data" x-data="{ statusNikah: '{{ old('nikah', $user->nikah ?? '0') }}', jenisPjob: '{{ old('jenis_pjob', $user->jenis_pjob ?? '') }}' }">
                    @csrf
                    @method('PUT')

                    <!-- Main Card -->
                    <div class="neo-card" style="padding: 2rem;">
                        <!-- Avatar Section -->
                        <div style="display: flex; flex-direction: column; align-items: center; margin-bottom: 2rem;">
                            <div style="position: relative; group: true;">
                                <div style="width: 8rem; height: 8rem; border-radius: 50%; overflow: hidden; border: 3px solid var(--gold); display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, var(--gold) 0 38%, var(--sun) 38% 58%, var(--night-soft) 58%);">
                                    @if($user->pp && $user->nomor_induk)
                                        <img src="{{ asset('assets/img/users/' . $user->nomor_induk . '/' . $user->pp) }}" alt="{{ $user->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                    @else
                                        <span style="font-family: var(--font-mono); font-size: 2rem; font-weight: 700; color: var(--night);">{{ substr($user->name, 0, 2) }}</span>
                                    @endif
                                </div>
                            </div>
                            <h2 style="margin-top: 1rem; font-family: var(--font-display); font-size: 1.25rem; font-weight: 600; color: var(--ink);">{{ $user->name }}</h2>
                            <p style="font-family: var(--font-mono); font-size: 0.8rem; color: var(--gold);">{{ $user->nomor_induk }}</p>
                        </div>

                        <!-- Divider -->
                        <div style="height: 1px; background: linear-gradient(90deg, transparent, var(--line), transparent); margin: 1.5rem 0;"></div>

                        <!-- Form Fields -->
                        <div style="display: grid; gap: 1.5rem; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));">
                            <!-- Nama Lengkap (Read Only) -->
                            <div style="grid-column: 1 / -1;">
                                <label style="display: block; margin-bottom: 0.5rem; font-family: var(--font-mono); font-size: 0.65rem; text-transform: uppercase; color: var(--ink-soft);">
                                    Nama Lengkap
                                </label>
                                <input type="text" value="{{ $user->name }}" readonly style="width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--line); border-radius: 0.5rem; background: var(--paper-soft); font-family: var(--font-mono); color: var(--ink); opacity: 0.8;">
                            </div>

                            <!-- Pekerjaan/Jabatan (Read Only) -->
                            <div>
                                <label style="display: block; margin-bottom: 0.5rem; font-family: var(--font-mono); font-size: 0.65rem; text-transform: uppercase; color: var(--ink-soft);">
                                    Pekerjaan / Jabatan
                                </label>
                                <input type="text" value="{{ $user->pekerjaan ?? '-' }}" readonly style="width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--line); border-radius: 0.5rem; background: var(--paper-soft); font-family: var(--font-mono); color: var(--ink); opacity: 0.8;">
                            </div>

                            <!-- Unit Kerja (Read Only) -->
                            <div>
                                <label style="display: block; margin-bottom: 0.5rem; font-family: var(--font-mono); font-size: 0.65rem; text-transform: uppercase; color: var(--ink-soft);">
                                    Unit Kerja
                                </label>
                                <input type="text" value="{{ $satuanKerja }}" readonly style="width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--line); border-radius: 0.5rem; background: var(--paper-soft); font-family: var(--font-mono); color: var(--ink); opacity: 0.8;">
                            </div>

                            <!-- NIK -->
                            <div>
                                <label for="nik" style="display: block; margin-bottom: 0.5rem; font-family: var(--font-mono); font-size: 0.65rem; text-transform: uppercase; color: var(--ink-soft);">
                                    NIK (Nomor Induk Kependudukan)
                                </label>
                                <input type="text" name="nik" id="nik" value="{{ old('nik', $user->nik) }}" placeholder="-" style="width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--line); border-radius: 0.5rem; background: var(--paper); font-family: var(--font-mono); color: var(--ink); transition: border-color 180ms;">
                            </div>

                            <!-- NPWP -->
                            <div>
                                <label for="npwp" style="display: block; margin-bottom: 0.5rem; font-family: var(--font-mono); font-size: 0.65rem; text-transform: uppercase; color: var(--ink-soft);">
                                    NPWP
                                </label>
                                <input type="text" name="npwp" id="npwp" value="{{ old('npwp', $user->npwp ?? '') }}" placeholder="00.000.000.0-000.000" style="width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--line); border-radius: 0.5rem; background: var(--paper); font-family: var(--font-mono); color: var(--ink); transition: border-color 180ms;">
                            </div>

                            <!-- Nomor Rekening Gaji -->
                            <div>
                                <label for="no_rekening" style="display: block; margin-bottom: 0.5rem; font-family: var(--font-mono); font-size: 0.65rem; text-transform: uppercase; color: var(--ink-soft);">
                                    Nomor Rekening Gaji
                                </label>
                                <input type="text" name="no_rekening" id="no_rekening" value="{{ old('no_rekening', $user->no_rekening ?? '') }}" placeholder="-" style="width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--line); border-radius: 0.5rem; background: var(--paper); font-family: var(--font-mono); color: var(--ink); transition: border-color 180ms;">
                            </div>

                            <!-- Nama Bank -->
                            <div>
                                <label for="bank" style="display: block; margin-bottom: 0.5rem; font-family: var(--font-mono); font-size: 0.65rem; text-transform: uppercase; color: var(--ink-soft);">
                                    Nama Bank
                                </label>
                                <input type="text" name="bank" id="bank" value="{{ old('bank', $user->bank ?? '') }}" placeholder="-" style="width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--line); border-radius: 0.5rem; background: var(--paper); font-family: var(--font-mono); color: var(--ink); transition: border-color 180ms;">
                            </div>

                            <!-- Alamat -->
                            <div style="grid-column: 1 / -1;">
                                <label for="alamat" style="display: block; margin-bottom: 0.5rem; font-family: var(--font-mono); font-size: 0.65rem; text-transform: uppercase; color: var(--ink-soft);">
                                    Alamat
                                </label>
                                <textarea name="alamat" id="alamat" rows="3" placeholder="-" style="width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--line); border-radius: 0.5rem; background: var(--paper); font-family: var(--font-mono); color: var(--ink); transition: border-color 180ms; resize: vertical;">{{ old('alamat', $user->alamat ?? '') }}</textarea>
                            </div>

                            <!-- No HP -->
                            <div>
                                <label for="hp" style="display: block; margin-bottom: 0.5rem; font-family: var(--font-mono); font-size: 0.65rem; text-transform: uppercase; color: var(--ink-soft);">
                                    No. HP
                                </label>
                                <input type="text" name="hp" id="hp" value="{{ old('hp', $user->hp ?? '') }}" placeholder="-" style="width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--line); border-radius: 0.5rem; background: var(--paper); font-family: var(--font-mono); color: var(--ink); transition: border-color 180ms;">
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" style="display: block; margin-bottom: 0.5rem; font-family: var(--font-mono); font-size: 0.65rem; text-transform: uppercase; color: var(--ink-soft);">
                                    Email
                                </label>
                                <input type="email" name="email" id="email" value="{{ old('email', $user->email ?? '') }}" placeholder="-" style="width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--line); border-radius: 0.5rem; background: var(--paper); font-family: var(--font-mono); color: var(--ink); transition: border-color 180ms;">
                            </div>
                        </div>

                        <!-- Divider -->
                        <div style="height: 1px; background: linear-gradient(90deg, transparent, var(--line), transparent); margin: 1.5rem 0;"></div>

                        <!-- Action Buttons -->
                        <div style="display: flex; justify-content: flex-end; gap: 1rem;">
                            <a href="{{ route('profil') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.5rem; border: 1px solid var(--line); border-radius: 0.5rem; background: transparent; color: var(--ink-soft); font-family: var(--font-mono); font-size: 0.8rem; text-decoration: none; transition: all 180ms;">
                                <svg width="16" height="16" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Batal
                            </a>
                            <button type="submit" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.75rem 2rem; border: none; border-radius: 0.5rem; background: var(--gold); color: var(--night); font-family: var(--font-mono); font-size: 0.8rem; font-weight: 600; cursor: pointer; transition: all 180ms;">
                                <svg width="16" height="16" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                                Simpan Perubahan
                            </button>
                        </div>
                    </div>

                    <!-- Error Messages -->
                    @if ($errors->any())
                        <div style="margin-top: 1.5rem; padding: 1rem; border: 1px solid oklch(45% 0.15 25); border-radius: 0.5rem; background: oklch(45% 0.15 25 / 0.1);">
                            <p style="color: oklch(45% 0.15 25); font-family: var(--font-mono); font-size: 0.85rem;">{{ $errors->first() }}</p>
                        </div>
                    @endif

                    <!-- Success Message -->
                    @if(session('success'))
                        <div style="margin-top: 1.5rem; padding: 1rem; border: 1px solid oklch(45% 0.15 145); border-radius: 0.5rem; background: oklch(45% 0.15 145 / 0.1);">
                            <p style="color: oklch(45% 0.15 145); font-family: var(--font-mono); font-size: 0.85rem;">{{ session('success') }}</p>
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
