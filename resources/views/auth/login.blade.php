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

                <div id="forgotResult" class="neo-forgot-result neo-auth-alert neo-auth-alert-success">
                    <p class="neo-auth-alert-title">PASSWORD TERKIRIM!</p>
                    <p class="neo-auth-alert-text" id="forgotSuccessMessage"></p>
                </div>

                <div id="forgotError" class="neo-forgot-error neo-auth-alert neo-auth-alert-error">
                    <p class="neo-auth-alert-title" id="forgotErrorMessage"></p>
                </div>
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
    </script>
</x-layouts.app>
