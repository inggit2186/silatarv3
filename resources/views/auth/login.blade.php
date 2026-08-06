<x-layouts.app title="Login - SILATAR">
    <main class="neo-mirai neo-auth-page">

        <!-- Login Card -->
        <div class="neo-auth-card">
            <div class="neo-auth-form-wrapper">
                <!-- Header -->
                <div class="neo-auth-header">
                    <!-- Logo -->
                    <div class="neo-auth-logo">
                        <img src="{{ asset('favicon.webp') }}" alt="SILATAR">
                    </div>

                    <p class="neo-auth-brand-kicker">Portal Layanan</p>
                    <h1 class="neo-auth-brand-title">SILATAR</h1>
                    <p class="neo-auth-brand-subtitle">Kementerian Agama Tanah Datar</p>
                </div>

                <!-- Error Alert -->
                @if ($errors->any())
                    <div class="neo-auth-alert neo-auth-alert-error">
                        <div class="neo-auth-alert-icon">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="neo-auth-alert-title">Login Gagal</p>
                            <p class="neo-auth-alert-text">{{ $errors->first() }}</p>
                        </div>
                    </div>
                @endif

                <!-- Login Form -->
                <form method="POST" action="{{ route('login.submit') }}" class="neo-auth-form">
                    @csrf

                    <!-- Email/NIP Field -->
                    <div class="neo-auth-form-row">
                        <label class="neo-auth-label" for="login">Email / NIP</label>
                        <div class="neo-auth-input-wrap">
                            <input
                                id="login"
                                name="login"
                                type="text"
                                value="{{ old('login') }}"
                                class="neo-auth-input"
                                placeholder="nama@email.com atau 1978xxxx"
                                autocomplete="username"
                                required
                            >
                        </div>
                    </div>

                    <!-- Password Field -->
                    <div class="neo-auth-form-row">
                        <label class="neo-auth-label" for="password">Password</label>
                        <div class="neo-auth-input-wrap">
                            <input
                                id="password"
                                name="password"
                                type="password"
                                class="neo-auth-input neo-auth-input-password"
                                placeholder="••••••••"
                                autocomplete="current-password"
                                required
                            >
                            <button type="button" id="togglePassword" class="neo-auth-toggle-password">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Remember & Forgot -->
                    <div class="neo-auth-actions-row">
                        <label class="neo-auth-remember">
                            <input name="remember" value="1" type="checkbox">
                            Ingat saya
                        </label>
                        <button type="button" onclick="openForgotModal()" class="neo-auth-forgot">
                            Lupa password?
                        </button>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="neo-auth-btn">
                        Masuk ke Sistem
                    </button>
                </form>

                <!-- Divider -->
                <div class="section-divider geometric" style="margin: 1.5rem 0;"></div>

                <!-- Back to Home -->
                <a href="{{ url('/') }}" class="neo-auth-btn-secondary">
                    ← Kembali ke Beranda
                </a>

                <!-- Register Link -->
                <p class="neo-auth-register-text">
                    Belum punya akun?
                    <button type="button" onclick="openRegisterModal()" class="neo-auth-register-link">
                        Daftar di sini
                    </button>
                </p>
            </div>

            <!-- Footer -->
            <div class="neo-auth-footer">
                <p class="neo-auth-footer-text">
                    &copy; {{ date('Y') }} SILATAR - Kemenag Tanah Datar
                </p>
            </div>
        </div>

        <!-- Forgot Password Modal -->
        <div id="forgotModal" class="neo-forgot-modal">
            <div class="neo-forgot-modal-content">
                <div class="neo-modal-header">
                    <div>
                        <h3 class="neo-modal-title">Lupa Password?</h3>
                        <p class="neo-auth-alert-text">Reset password via WhatsApp</p>
                    </div>
                    <button type="button" onclick="closeForgotModal()" class="neo-modal-close">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form id="forgotForm" onsubmit="submitForgotPassword(event)" class="neo-auth-form">
                    @csrf
                    <div class="neo-auth-form-row">
                        <label class="neo-auth-label">Nomor Induk Kepegawaian (NIP)</label>
                        <input
                            id="nip"
                            name="nip"
                            type="text"
                            class="neo-auth-input"
                            placeholder="1978xxxx"
                            required
                        >
                    </div>
                    <button type="submit" id="forgotSubmitBtn" class="neo-auth-btn">
                        Kirim Password Baru
                    </button>
                </form>

                <div id="forgotResult" class="neo-forgot-result" style="display: none;">
                    <!-- Success Icon -->
                    <div class="forgot-success-icon">
                        <svg viewBox="0 0 24 24" fill="none">
                            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
                            <path d="M8 12l3 3 5-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>

                    <!-- Success Title -->
                    <div class="forgot-success-title">PASSWORD TERKIRIM!</div>

                    <!-- WhatsApp Info Card -->
                    <div class="forgot-whatsapp-card">
                        <div class="forgot-whatsapp-icon">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                            </svg>
                        </div>
                        <div class="forgot-whatsapp-text">
                            Password baru telah dikirim ke WhatsApp<br>
                            <span id="forgotSuccessMessage" class="forgot-whatsapp-number"></span>
                        </div>
                    </div>

                    <!-- Divider -->
                    <div class="forgot-divider">
                        <span>ATAU</span>
                    </div>

                    <!-- Help Card -->
                    <div class="forgot-help-card">
                        <div class="forgot-help-header">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"/>
                                <path d="M9.09 9a3 3 0 015.83 1c0 2-3 3-3 3"/>
                                <circle cx="12" cy="17" r="0.5" fill="currentColor"/>
                            </svg>
                            <span>Nomor WhatsApp salah?</span>
                        </div>
                        <p class="forgot-help-text">
                            Hubungi admin untuk update nomor WhatsApp
                        </p>
                        <div class="forgot-help-contact">
                            <span class="forgot-contact-icon">
                                <svg viewBox="0 0 24 24" fill="currentColor" width="16" height="16">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                </svg>
                            </span>
                            <strong>0895 0900 7078</strong>
                        </div>
                        <p class="forgot-help-instruction">
                            Kirim chat: <code>Set WhatsApp [NIP]</code><br>
                        <span class="forgot-help-example">contoh: <code>Set WhatsApp 199903022025211004</code></span>
                        </p>
                    </div>

                    <!-- Back Button -->
                    <button onclick="closeForgotModal()" class="forgot-back-btn">
                        Tutup
                    </button>
                </div>

                <div id="forgotError" class="neo-forgot-error neo-auth-alert neo-auth-alert-error">
                    <p class="neo-auth-alert-title" id="forgotErrorMessage"></p>
                </div>
            </div>
        </div>

        <!-- Registration Choice Modal -->
        <div id="registerModal" class="neo-register-modal">
            <div class="neo-register-modal-content">
                <div class="neo-modal-header">
                    <div>
                        <h3 class="neo-modal-title">Pilih Jenis Pendaftaran</h3>
                        <p class="neo-auth-alert-text">Anda adalah siapa?</p>
                    </div>
                    <button type="button" onclick="closeRegisterModal()" class="neo-modal-close">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="register-choice-grid">
                    <!-- Option 1: Honorer -->
                    <button type="button" onclick="selectUserType('honorer')" class="register-choice-card">
                        <div class="register-choice-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                            </svg>
                        </div>
                        <div class="register-choice-content">
                            <h4 class="register-choice-title">Pegawai Honorer</h4>
                            <p class="register-choice-desc">di Lingkungan Kantor Kementerian Agama Kab. Tanah Datar</p>
                        </div>
                        <div class="register-choice-arrow">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/>
                            </svg>
                        </div>
                    </button>

                    <!-- Option 2: Guru PAI -->
                    <button type="button" onclick="selectUserType('guru_pai')" class="register-choice-card">
                        <div class="register-choice-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 00-.491 6.347A48.62 48.62 0 0112 20.904a48.62 48.62 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.636 50.636 0 00-2.658-.813A59.906 59.906 0 0112 3.493a59.903 59.903 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5"/>
                            </svg>
                        </div>
                        <div class="register-choice-content">
                            <h4 class="register-choice-title">Guru PAI</h4>
                            <p class="register-choice-desc">Pendidikan Agama Islam dari Pemerintah Daerah Kab. Tanah Datar</p>
                        </div>
                        <div class="register-choice-arrow">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/>
                            </svg>
                        </div>
                    </button>

                    <!-- Option 3: Masyarakat Biasa -->
                    <button type="button" onclick="selectUserType('masyarakat')" class="register-choice-card">
                        <div class="register-choice-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/>
                            </svg>
                        </div>
                        <div class="register-choice-content">
                            <h4 class="register-choice-title">Masyarakat Biasa</h4>
                            <p class="register-choice-desc">Warga masyarakat yang ingin menggunakan layanan</p>
                        </div>
                        <div class="register-choice-arrow">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/>
                            </svg>
                        </div>
                    </button>
                </div>

                <p class="register-modal-footer">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="register-modal-login-link">Masuk di sini</a>
                </p>
            </div>
        </div>
    </main>

    <script>
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
        });

        function openForgotModal() {
            document.getElementById('forgotModal').classList.add('is-open');
        }

        function closeForgotModal() {
            document.getElementById('forgotModal').classList.remove('is-open');
            resetForgotForm();
        }

        function resetForgotForm() {
            document.getElementById('forgotForm').style.display = 'flex';
            document.getElementById('forgotResult').style.display = 'none';
            document.getElementById('forgotError').style.display = 'none';
            document.getElementById('forgotSubmitBtn').disabled = false;
        }

        async function submitForgotPassword(e) {
            e.preventDefault();
            const btn = document.getElementById('forgotSubmitBtn');
            const resultDiv = document.getElementById('forgotResult');
            const errorDiv = document.getElementById('forgotError');

            resultDiv.style.display = 'none';
            errorDiv.style.display = 'none';
            btn.disabled = true;

            const formData = new FormData(document.getElementById('forgotForm'));

            try {
                const response = await fetch('{{ route('forgot-password') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': formData.get('_token'),
                        'Accept': 'application/json',
                    },
                    body: formData
                });
                const data = await response.json();

                if (data.success) {
                    document.getElementById('forgotSuccessMessage').textContent = data.message;
                    document.getElementById('forgotForm').style.display = 'none';
                    resultDiv.style.display = 'block';

                    // Add entrance animation
                    resultDiv.classList.add('is-visible');
                } else {
                    document.getElementById('forgotErrorMessage').textContent = data.message;
                    errorDiv.style.display = 'block';
                }
            } catch (err) {
                document.getElementById('forgotErrorMessage').textContent = 'Terjadi kesalahan. Silakan coba lagi.';
                errorDiv.style.display = 'block';
            } finally {
                btn.disabled = false;
            }
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeForgotModal();
        });

        // Register Modal Functions
        function openRegisterModal() {
            document.getElementById('registerModal').classList.add('is-open');
        }

        function closeRegisterModal() {
            document.getElementById('registerModal').classList.remove('is-open');
        }

        function selectUserType(type) {
            closeRegisterModal();
            // Redirect to registration page with type parameter
            window.location.href = '{{ route('register') }}?type=' + type;
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeForgotModal();
                closeRegisterModal();
            }
        });

        // Check if there's a type parameter in URL and auto-open modal
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const type = urlParams.get('type');
            if (type) {
                openRegisterModal();
            }
        });
    </script>
</x-layouts.app>
