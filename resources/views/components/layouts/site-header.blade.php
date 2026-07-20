<header class="site-header" data-reveal="">
    @php
    use Illuminate\Support\Facades\DB;
    $showMadrasahMenu = false;
    if (auth()->check() && auth()->user()->dept_id) {
        $dept = DB::table('ktd_department')->where('id', auth()->user()->dept_id)->first();
        if ($dept && in_array($dept->kategori, ['min', 'mtsn', 'man', 'other'])) {
            $showMadrasahMenu = true;
        }
    }
    @endphp
    <a class="brand-lockup" href="{{ url("/") }}" aria-label="SILATAR home">
        <span class="brand-mark" aria-hidden="true"><span></span></span>
        <span class="brand-word"><span>SILATAR</span><span>V2</span></span>
    </a>

    <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="mobile-nav" id="menuToggle"><span>Menu</span><i aria-hidden="true"></i></button>

    <nav class="site-nav" id="site-nav" aria-label="Primary navigation">
        <a href="{{ url("/") }}">Beranda</a>
        <a href="{{ route('news.index') }}">Berita</a>
        <a href="{{ route('satuan-kerja') }}">Unit Kerja</a>
        <a href="{{ route('pelayanan') }}" class="nav-layanan">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Layanan
        </a>
        <a href="{{ route('ppid') }}">PPID</a>
        <a href="{{ url("/#kontak") }}">Kontak</a>
    </nav>

    @auth
        <div class="user-menu-wrapper" x-data="{ open: false }" @click.away="open = false">
            <button type="button" class="user-menu-btn" @click="open = !open" :aria-expanded="open">
                @if(Auth::user()->pp && file_exists(public_path('storage/' . Auth::user()->pp)))
                    <img src="{{ asset('storage/' . Auth::user()->pp) }}" alt="PP" class="user-pp">
                @else
                    <div class="user-pp-placeholder">{{ substr(Auth::user()->name, 0, 1) }}</div>
                @endif
                <div class="user-info">
                    <span class="user-name">{{ Auth::user()->name }}</span>
                    <span class="user-role">{{ Auth::user()->pekerjaan ?? 'Pegawai' }}</span>
                </div>
                <svg class="user-chevron" :class="open ? 'is-open' : ''" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
            </button>
            <div class="user-dropdown" x-show="open" x-transition>
                <a href="{{ route('admin.dashboard') }}" class="user-dropdown-item">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                    Dashboard
                </a>
                <a href="{{ route('profil') }}" class="user-dropdown-item">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    Profil Saya
                </a>
                <a href="{{ route('pengajuan-saya') }}" class="user-dropdown-item">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    Pengajuan Saya
                </a>
                <a href="{{ route('laporan-kinerja') }}" class="user-dropdown-item">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Laporan Kinerja
                </a>
                <a href="#" class="user-dropdown-item" id="changePasswordBtn">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                    Ubah Password
                </a>
                @if(auth()->user()->role === 'kepala')
                <a href="{{ route('penilaian-kinerja.index') }}" class="user-dropdown-item">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    Penilaian Kinerja
                </a>
                @endif
                @if($showMadrasahMenu)
                <a href="{{ route('madrasah.profil') }}" class="user-dropdown-item">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    Laporan Madrasah
                </a>
                @endif
                <div class="user-dropdown-divider"></div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="user-dropdown-item user-dropdown-logout">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16,17 21,12 16,7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                        Logout
                    </button>
                </form>
            </div>
        </div>
    @else
        <a href="{{ route('login') }}" class="ticket-pill"><span>Login</span><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h12m-5-5 5 5-5 5"/></svg></a>
    @endauth
</header>

<!-- Mobile Navigation -->
<div id="mobile-nav" class="mobile-nav hidden">
    <button class="mobile-nav-close" id="menuClose" type="button"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 18L18 6M6 6l12 12"/></svg></button>
    <nav aria-label="Mobile navigation">
        <a href="{{ url("/") }}">Beranda</a>
        <a href="{{ route('news.index') }}">Berita</a>
        <a href="{{ route('satuan-kerja') }}">Unit Kerja</a>
        <a href="{{ route('pelayanan') }}">Layanan</a>
        <a href="{{ route('ppid') }}">PPID</a>
        <a href="{{ url("/#kontak") }}">Kontak</a>
        @auth
            @if($showMadrasahMenu)
            <a href="{{ route('madrasah.profil') }}">Laporan Madrasah</a>
            @endif
            @if(auth()->user()->role === 'kepala')
            <a href="{{ route('penilaian-kinerja.index') }}">Penilaian Kinerja</a>
            @endif
            <a href="#" id="mobilePasswordBtn">Ubah Password</a>
            <a href="{{ route('admin.dashboard') }}" class="mobile-nav-cta">Dashboard</a>
        @else
            <a href="{{ route('login') }}" class="mobile-nav-cta">Login</a>
        @endauth
    </nav>
</div>

<!-- Password Modal - NEO MIRAI Theme -->
<div id="passwordModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header-custom">
            <div class="flex items-center gap-3">
                <div class="modal-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="modal-title-custom">Ubah Password</h3>
                    <p class="modal-subtitle">Update password akun Anda</p>
                </div>
            </div>
            <button type="button" class="modal-close-btn" onclick="closePasswordModal()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form id="passwordForm">
            <div class="modal-body-custom">
                <div class="form-group-custom">
                    <label class="form-label-custom">Password Lama</label>
                    <div class="input-wrapper">
                        <input type="password" id="current_password" class="input-custom" placeholder="Masukkan password lama" required>
                        <button type="button" class="toggle-password-btn" onclick="togglePasswordVisibility('current_password')">
                            <svg id="eye-current_password" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="form-group-custom">
                    <label class="form-label-custom">Password Baru</label>
                    <div class="input-wrapper">
                        <input type="password" id="new_password" class="input-custom" placeholder="Minimal 6 karakter" required minlength="6">
                        <button type="button" class="toggle-password-btn" onclick="togglePasswordVisibility('new_password')">
                            <svg id="eye-new_password" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="form-group-custom">
                    <label class="form-label-custom">Konfirmasi Password Baru</label>
                    <div class="input-wrapper">
                        <input type="password" id="password_confirmation" class="input-custom" placeholder="Ulangi password baru" required minlength="6">
                        <button type="button" class="toggle-password-btn" onclick="togglePasswordVisibility('password_confirmation')">
                            <svg id="eye-password_confirmation" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <div id="passwordError" class="error-alert hidden">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span id="passwordErrorMessage"></span>
                </div>
            </div>

            <div class="modal-footer-custom">
                <button type="button" class="btn-secondary" onclick="closePasswordModal()">Batal</button>
                <button type="submit" class="btn-primary" id="submitPasswordBtn">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    /* Modal Overlay */
    .modal-overlay {
        position: fixed;
        inset: 0;
        z-index: 9999;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(4px);
        display: none;
        align-items: center;
        justify-content: center;
        padding: 1rem;
    }
    .modal-overlay.active {
        display: flex;
    }

    /* Modal Content */
    .modal-content {
        background: var(--rice);
        border: 1px solid var(--line);
        border-radius: 1rem;
        width: 100%;
        max-width: 440px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        animation: modalIn 0.3s var(--ease);
    }
    @keyframes modalIn {
        from {
            opacity: 0;
            transform: scale(0.95) translateY(-10px);
        }
        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }

    /* Modal Header */
    .modal-header-custom {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 1rem;
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid var(--line);
    }
    .modal-icon {
        width: 44px;
        height: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: oklch(68% 0.145 74 / 0.1);
        border-radius: 0.75rem;
        color: var(--gold);
    }
    .modal-icon svg {
        width: 22px;
        height: 22px;
    }
    .modal-title-custom {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--ink);
        margin: 0;
    }
    .modal-subtitle {
        font-size: 0.875rem;
        color: var(--ink-soft);
        margin: 0.125rem 0 0;
    }
    .modal-close-btn {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: transparent;
        border: 1px solid var(--line);
        border-radius: 0.5rem;
        color: var(--ink-soft);
        cursor: pointer;
        transition: all 0.2s;
        flex-shrink: 0;
    }
    .modal-close-btn:hover {
        border-color: var(--gold);
        color: var(--gold);
    }
    .modal-close-btn svg {
        width: 18px;
        height: 18px;
    }

    /* Modal Body */
    .modal-body-custom {
        padding: 1.5rem;
    }
    .form-group-custom {
        margin-bottom: 1rem;
    }
    .form-label-custom {
        display: block;
        font-size: 0.875rem;
        font-weight: 500;
        color: var(--ink);
        margin-bottom: 0.5rem;
    }
    .input-wrapper {
        position: relative;
    }
    .input-custom {
        width: 100%;
        padding: 0.75rem 2.75rem 0.75rem 1rem;
        border: 1px solid var(--line);
        border-radius: 0.5rem;
        background: var(--paper);
        color: var(--ink);
        font-size: 0.9375rem;
        transition: all 0.2s;
    }
    .input-custom:focus {
        outline: none;
        border-color: var(--gold);
        box-shadow: 0 0 0 3px oklch(68% 0.145 74 / 0.15);
    }
    .input-custom::placeholder {
        color: var(--ash);
    }
    .toggle-password-btn {
        position: absolute;
        right: 0.75rem;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: var(--ink-soft);
        cursor: pointer;
        padding: 0.25rem;
        transition: color 0.2s;
    }
    .toggle-password-btn:hover {
        color: var(--ink);
    }

    /* Error Alert */
    .error-alert {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        padding: 0.875rem 1rem;
        background: oklch(97% 0.06 25 / 0.15);
        border: 1px solid oklch(70% 0.18 25 / 0.3);
        border-radius: 0.5rem;
        font-size: 0.875rem;
        color: oklch(45% 0.2 25);
        margin-top: 0.5rem;
    }
    .error-alert svg {
        width: 18px;
        height: 18px;
        flex-shrink: 0;
        color: oklch(55% 0.2 25);
        margin-top: 0.125rem;
    }
    .error-alert.hidden {
        display: none;
    }

    /* Modal Footer */
    .modal-footer-custom {
        display: flex;
        justify-content: flex-end;
        gap: 0.75rem;
        padding: 1rem 1.5rem;
        border-top: 1px solid var(--line);
        background: var(--paper);
        border-radius: 0 0 1rem 1rem;
    }
    .btn-secondary {
        padding: 0.625rem 1.25rem;
        background: var(--paper-soft);
        border: 1px solid var(--line);
        border-radius: 0.5rem;
        color: var(--ink);
        font-size: 0.9375rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-secondary:hover {
        background: var(--paper-deep);
        border-color: var(--ash);
    }
    .btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.625rem 1.25rem;
        background: linear-gradient(135deg, var(--gold) 0%, var(--sun-deep) 100%);
        border: none;
        border-radius: 0.5rem;
        color: white;
        font-size: 0.9375rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px oklch(68% 0.145 74 / 0.4);
    }
    .btn-primary:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }
    .btn-primary svg {
        width: 18px;
        height: 18px;
    }

    /* Success Toast */
    .toast-notification {
        position: fixed;
        bottom: 1.5rem;
        right: 1.5rem;
        z-index: 10000;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 1rem 1.25rem;
        background: var(--rice);
        border: 1px solid oklch(70% 0.18 145 / 0.4);
        border-radius: 0.75rem;
        font-size: 0.9375rem;
        font-weight: 500;
        color: oklch(45% 0.15 145);
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
        animation: toastIn 0.3s var(--ease);
    }
    @keyframes toastIn {
        from {
            opacity: 0;
            transform: translateY(1rem);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .toast-notification svg {
        width: 20px;
        height: 20px;
        color: oklch(50% 0.15 145);
        flex-shrink: 0;
    }
</style>

<script>
    // Password Modal Functions
    const passwordModal = document.getElementById('passwordModal');

    // Close dropdown when clicking change password
    document.getElementById('changePasswordBtn').addEventListener('click', function(e) {
        e.preventDefault();
        // Close the Alpine dropdown by dispatching a click outside
        document.body.click();
        openPasswordModal();
    });

    function openPasswordModal() {
        document.getElementById('passwordError').classList.add('hidden');
        document.getElementById('current_password').value = '';
        document.getElementById('new_password').value = '';
        document.getElementById('password_confirmation').value = '';
        passwordModal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closePasswordModal() {
        passwordModal.classList.remove('active');
        document.body.style.overflow = '';
    }

    // Close on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && passwordModal.classList.contains('active')) {
            closePasswordModal();
        }
    });

    // Close on backdrop click
    passwordModal.addEventListener('click', function(e) {
        if (e.target === passwordModal) {
            closePasswordModal();
        }
    });

    // Mobile button
    document.getElementById('mobilePasswordBtn').addEventListener('click', function(e) {
        e.preventDefault();
        document.getElementById('mobile-nav').classList.add('hidden');
        document.body.style.overflow = '';
        openPasswordModal();
    });

    // Form Submit
    document.getElementById('passwordForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const submitBtn = document.getElementById('submitPasswordBtn');
        const currentPassword = document.getElementById('current_password').value;
        const newPassword = document.getElementById('new_password').value;
        const confirmPassword = document.getElementById('password_confirmation').value;

        // Client-side validation
        if (newPassword.length < 6) {
            document.getElementById('passwordErrorMessage').textContent = 'Password baru minimal 6 karakter.';
            document.getElementById('passwordError').classList.remove('hidden');
            return;
        }

        if (newPassword !== confirmPassword) {
            document.getElementById('passwordErrorMessage').textContent = 'Konfirmasi password baru tidak cocok.';
            document.getElementById('passwordError').classList.remove('hidden');
            return;
        }

        // Disable button
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<svg class="animate-spin w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10" stroke-opacity="0.25"/><path d="M12 2a10 10 0 0 1 10 10" stroke-opacity="1"/></svg> Menyimpan...';

        try {
            const response = await fetch('{{ route('ubah-password.update') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    current_password: currentPassword,
                    password: newPassword,
                    password_confirmation: confirmPassword,
                }),
            });

            const data = await response.json();

            if (data.success) {
                closePasswordModal();
                showToast('Password berhasil diubah.');
            } else {
                const errorEl = document.getElementById('passwordErrorMessage');
                if (data.errors?.current_password) {
                    errorEl.textContent = data.errors.current_password[0];
                } else if (data.errors?.password) {
                    errorEl.textContent = data.errors.password[0];
                } else {
                    errorEl.textContent = data.message || 'Terjadi kesalahan.';
                }
                document.getElementById('passwordError').classList.remove('hidden');
            }
        } catch (error) {
            document.getElementById('passwordErrorMessage').textContent = 'Terjadi kesalahan. Silakan coba lagi.';
            document.getElementById('passwordError').classList.remove('hidden');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg> Simpan';
        }
    });

    // Toggle Password Visibility
    function togglePasswordVisibility(inputId) {
        const input = document.getElementById(inputId);
        const eyeIcon = document.getElementById('eye-' + inputId);

        if (input.type === 'password') {
            input.type = 'text';
            eyeIcon.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
            `;
        } else {
            input.type = 'password';
            eyeIcon.innerHTML = `
                <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            `;
        }
    }

    // Toast Notification
    function showToast(message) {
        const toast = document.createElement('div');
        toast.className = 'toast-notification';
        toast.innerHTML = `
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>${message}</span>
        `;
        document.body.appendChild(toast);

        setTimeout(() => {
            toast.style.animation = 'toastIn 0.3s var(--ease) reverse';
            setTimeout(() => toast.remove(), 300);
        }, 4000);
    }

    // Mobile nav toggle
    document.addEventListener('DOMContentLoaded', function() {
        var header = document.querySelector('.site-header');
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 50) {
                header.classList.add('is-scrolled');
            } else {
                header.classList.remove('is-scrolled');
            }
        });

        var menuToggle = document.getElementById('menuToggle');
        var menuClose = document.getElementById('menuClose');
        var mobileNav = document.getElementById('mobile-nav');

        if (menuToggle && mobileNav) {
            menuToggle.addEventListener('click', function() {
                mobileNav.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            });
        }
        if (menuClose) {
            menuClose.addEventListener('click', function() {
                mobileNav.classList.add('hidden');
                document.body.style.overflow = '';
            });
        }
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && mobileNav && !mobileNav.classList.contains('hidden')) {
                mobileNav.classList.add('hidden');
                document.body.style.overflow = '';
            }
        });
    });
</script>
