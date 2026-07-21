<x-layouts.app title="Daftar - SILATAR">
    <main class="neo-mirai neo-auth-page">

        <div class="neo-auth-register-wrapper">
            <!-- Registration Form -->
            <div class="neo-auth-form-wrapper">
                <div class="neo-auth-register-header">
                    <p class="neo-auth-brand-kicker">Daftar Akun</p>
                    <h1 class="neo-auth-register-title">Buat Akun Baru</h1>
                    <p class="neo-auth-register-desc">Daftarkan akun untuk mulai memakai layanan digital, melihat riwayat, dan menerima pembaruan penting.</p>
                </div>

                <form class="neo-auth-form">
                    <div class="neo-auth-form-row" style="display: grid; gap: 1rem; grid-template-columns: repeat(auto-fit, minmax(12rem, 1fr));">
                        <div>
                            <label class="neo-auth-label">Nama</label>
                            <input type="text" placeholder="Nama lengkap" class="neo-auth-input">
                        </div>
                        <div>
                            <label class="neo-auth-label">Telepon</label>
                            <input type="text" placeholder="08xxxxxxxxxx" class="neo-auth-input">
                        </div>
                    </div>
                    <div class="neo-auth-form-row">
                        <label class="neo-auth-label">Email</label>
                        <input type="email" placeholder="nama@contoh.com" class="neo-auth-input">
                    </div>
                    <div class="neo-auth-form-row" style="display: grid; gap: 1rem; grid-template-columns: repeat(auto-fit, minmax(12rem, 1fr));">
                        <div>
                            <label class="neo-auth-label">Password</label>
                            <input type="password" placeholder="••••••••" class="neo-auth-input">
                        </div>
                        <div>
                            <label class="neo-auth-label">Konfirmasi</label>
                            <input type="password" placeholder="••••••••" class="neo-auth-input">
                        </div>
                    </div>
                    <div class="neo-auth-actions-row" style="flex-wrap: wrap;">
                        <button type="button" class="neo-auth-btn" style="flex: 1;">
                            Daftar →
                        </button>
                        <a href="{{ route('login') }}" class="neo-auth-forgot" style="flex: none;">
                            Sudah punya akun
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </main>
</x-layouts.app>
