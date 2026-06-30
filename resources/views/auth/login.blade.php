<x-layouts.app title="Login - SILATAR">
    <main class="neo-mirai" style="min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 2rem 1rem;">
        <x-layouts.site-header />

        <!-- Login Card -->
        <div style="width: 100%; max-width: 28rem; margin-top: 6rem;">
            <div style="background: var(--paper-soft); border: 1px solid var(--gold); padding: 2rem; border-radius: 0.5rem;">
                <!-- Header -->
                <div style="text-align: center; margin-bottom: 2rem;">
                    <!-- Logo -->
                    <div style="width: 5rem; height: 5rem; margin: 0 auto 1.5rem; border-radius: 0.75rem; overflow: hidden; border: 1px solid oklch(30% 0.035 78 / 0.45); background: linear-gradient(135deg, var(--gold) 0 38%, var(--sun) 38% 58%, var(--night-soft) 58%); display: flex; align-items: center; justify-content: center;">
                        <img src="{{ asset('favicon.webp') }}" alt="SILATAR" style="width: 3rem; height: 3rem; object-fit: contain;">
                    </div>

                    <p style="color: var(--gold); font-family: var(--font-mono); font-size: 0.65rem; text-transform: uppercase; margin: 0 0 0.5rem;">Portal Layanan</p>
                    <h1 style="font-family: var(--font-display); font-size: 2rem; font-weight: 600; color: var(--ink); margin: 0;">SILATAR</h1>
                    <p style="font-family: var(--font-mono); font-size: 0.7rem; color: var(--ink-soft); margin: 0.5rem 0 0;">Kementerian Agama Tanah Datar</p>
                </div>

                <!-- Error Alert -->
                @if ($errors->any())
                    <div style="display: flex; align-items: center; gap: 1rem; padding: 1rem; background: oklch(60% 0.2 25 / 0.1); border: 1px solid oklch(60% 0.2 25 / 0.3); border-radius: 0.5rem; margin-bottom: 1.5rem;">
                        <div style="width: 2.5rem; height: 2.5rem; border-radius: 0.5rem; background: oklch(60% 0.2 25 / 0.2); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <svg style="width: 1.25rem; height: 1.25rem; color: oklch(70% 0.2 25);" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p style="font-family: var(--font-mono); font-size: 0.7rem; font-weight: 700; color: oklch(70% 0.2 25); margin: 0;">Login Gagal</p>
                            <p style="font-family: var(--font-mono); font-size: 0.65rem; color: var(--ink-soft); margin: 0.2rem 0 0;">{{ $errors->first() }}</p>
                        </div>
                    </div>
                @endif

                <!-- Login Form -->
                <form method="POST" action="{{ route('login.submit') }}" style="display: flex; flex-direction: column; gap: 1.25rem;">
                    @csrf

                    <!-- Email/NIP Field -->
                    <div>
                        <label style="display: block; font-family: var(--font-mono); font-size: 0.65rem; font-weight: 600; text-transform: uppercase; color: var(--gold); margin-bottom: 0.5rem;" for="login">Email / NIP</label>
                        <div style="position: relative;">
                            <input
                                id="login"
                                name="login"
                                type="text"
                                value="{{ old('login') }}"
                                style="width: 100%; padding: 0.85rem 1rem; background: var(--paper); border: 1px solid var(--line); font-family: var(--font-mono); font-size: 0.85rem; color: var(--ink); transition: border-color 180ms;"
                                placeholder="nama@email.com atau 1978xxxx"
                                autocomplete="username"
                                required
                            >
                        </div>
                    </div>

                    <!-- Password Field -->
                    <div>
                        <label style="display: block; font-family: var(--font-mono); font-size: 0.65rem; font-weight: 600; text-transform: uppercase; color: var(--gold); margin-bottom: 0.5rem;" for="password">Password</label>
                        <div style="position: relative;">
                            <input
                                id="password"
                                name="password"
                                type="password"
                                style="width: 100%; padding: 0.85rem 3rem 0.85rem 1rem; background: var(--paper); border: 1px solid var(--line); font-family: var(--font-mono); font-size: 0.85rem; color: var(--ink); transition: border-color 180ms;"
                                placeholder="••••••••"
                                autocomplete="current-password"
                                required
                            >
                            <button type="button" id="togglePassword" style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: var(--ink-soft);">
                                <svg style="width: 1.25rem; height: 1.25rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Remember & Forgot -->
                    <div style="display: flex; align-items: center; justify-content: space-between;">
                        <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; font-family: var(--font-mono); font-size: 0.65rem; text-transform: uppercase; color: var(--ink-soft);">
                            <input name="remember" value="1" type="checkbox" style="width: 1rem; height: 1rem; accent-color: var(--gold);">
                            Ingat saya
                        </label>
                        <button type="button" onclick="openForgotModal()" style="background: none; border: none; cursor: pointer; font-family: var(--font-mono); font-size: 0.65rem; font-weight: 600; text-transform: uppercase; color: var(--gold);">
                            Lupa password?
                        </button>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" style="width: 100%; padding: 1rem; background: var(--gold); color: var(--night); font-family: var(--font-mono); font-size: 0.75rem; font-weight: 700; text-transform: uppercase; border: none; cursor: pointer; transition: background 180ms, transform 240ms, box-shadow 240ms;">
                        Masuk ke Sistem
                    </button>
                </form>

                <!-- Divider -->
                <div class="section-divider geometric" style="margin: 1.5rem 0;"></div>

                <!-- Back to Home -->
                <a href="{{ url('/') }}" style="display: flex; align-items: center; justify-content: center; gap: 0.5rem; padding: 0.85rem; background: transparent; color: var(--ink); font-family: var(--font-mono); font-size: 0.7rem; font-weight: 600; text-transform: uppercase; text-decoration: none; border: 1px solid var(--line); transition: border-color 180ms, color 180ms;">
                    ← Kembali ke Beranda
                </a>
            </div>

            <!-- Footer -->
            <div style="text-align: center; margin-top: 1.5rem;">
                <p style="font-family: var(--font-mono); font-size: 0.6rem; color: var(--ink-soft); margin: 0;">
                    &copy; {{ date('Y') }} SILATAR - Kemenag Tanah Datar
                </p>
            </div>
        </div>

        <!-- Forgot Password Modal -->
        <div id="forgotModal" style="display: none; position: fixed; inset: 0; z-index: 50; background: rgba(0,0,0,0.8); backdrop-filter: blur(4px); display: none; align-items: center; justify-content: center; padding: 1rem;">
            <div style="position: relative; width: 100%; max-width: 28rem; background: var(--paper); border: 1px solid var(--line); padding: 2rem; border-radius: 0.5rem; max-height: 90vh; overflow-y: auto;">
                <div class="neo-modal-header">
                    <div>
                        <h3 class="neo-modal-title">Lupa Password?</h3>
                        <p style="font-size: 0.85rem; color: var(--ink-soft); margin: 0.25rem 0 0;">Reset password via WhatsApp</p>
                    </div>
                    <button type="button" onclick="closeForgotModal()" class="neo-modal-close">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form id="forgotForm" onsubmit="submitForgotPassword(event)" style="display: flex; flex-direction: column; gap: 1rem;">
                    @csrf
                    <div>
                        <label style="display: block; font-family: var(--font-mono); font-size: 0.65rem; font-weight: 600; text-transform: uppercase; color: var(--gold); margin-bottom: 0.5rem;">Nomor Induk Kepegawaian (NIP)</label>
                        <input
                            id="nip"
                            name="nip"
                            type="text"
                            style="width: 100%; padding: 0.85rem 1rem; background: var(--paper); border: 1px solid var(--line); font-family: var(--font-mono); font-size: 0.9rem; color: var(--ink);"
                            placeholder="1978xxxx"
                            required
                        >
                    </div>
                    <button type="submit" id="forgotSubmitBtn" style="width: 100%; padding: 1rem; background: var(--gold); color: var(--night); font-family: var(--font-mono); font-size: 0.75rem; font-weight: 700; text-transform: uppercase; border: none; cursor: pointer;">
                        Kirim Password Baru
                    </button>
                </form>

                <div id="forgotResult" style="display: none; margin-top: 1.5rem; padding: 1.5rem; background: oklch(65% 0.15 145 / 0.1); border: 1px solid oklch(65% 0.15 145 / 0.3); border-radius: 0.5rem; text-align: center;">
                    <p style="font-family: var(--font-mono); font-size: 0.8rem; font-weight: 700; color: oklch(65% 0.15 145); margin: 0 0 0.5rem;">PASSWORD TERKIRIM!</p>
                    <p style="font-size: 0.85rem; color: var(--ink-soft); margin: 0;" id="forgotSuccessMessage"></p>
                </div>

                <div id="forgotError" style="display: none; margin-top: 1.5rem; padding: 1.5rem; background: oklch(60% 0.2 25 / 0.1); border: 1px solid oklch(60% 0.2 25 / 0.3); border-radius: 0.5rem; text-align: center;">
                    <p style="font-family: var(--font-mono); font-size: 0.8rem; font-weight: 700; color: oklch(60% 0.2 25); margin: 0;" id="forgotErrorMessage"></p>
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
            document.getElementById('forgotModal').style.display = 'flex';
        }

        function closeForgotModal() {
            document.getElementById('forgotModal').style.display = 'none';
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
