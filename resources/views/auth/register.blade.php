<x-layouts.app title="Daftar - SILATAR">
    <main class="neo-mirai" style="min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 2rem 1rem;">
        <x-layouts.site-header />

        <div style="width: 100%; max-width: 36rem; margin-top: 6rem;">
            <!-- Registration Form -->
            <div style="background: var(--paper-soft); border: 1px solid var(--gold); padding: 2rem; border-radius: 0.5rem;">
                <p style="color: var(--gold); font-family: var(--font-mono); font-size: 0.65rem; text-transform: uppercase; margin: 0 0 0.5rem;">Daftar Akun</p>
                <h1 style="font-family: var(--font-display); font-size: 1.5rem; font-weight: 600; color: var(--ink); margin: 0 0 0.5rem;">Buat Akun Baru</h1>
                <p style="font-size: 0.9rem; color: var(--ink-soft); margin: 0 0 1.5rem;">Daftarkan akun untuk mulai memakai layanan digital, melihat riwayat, dan menerima pembaruan penting.</p>

                <form style="display: flex; flex-direction: column; gap: 1rem;">
                    <div style="display: grid; gap: 1rem; grid-template-columns: repeat(auto-fit, minmax(12rem, 1fr));">
                        <div>
                            <label style="display: block; font-family: var(--font-mono); font-size: 0.65rem; font-weight: 600; text-transform: uppercase; color: var(--gold); margin-bottom: 0.5rem;">Nama</label>
                            <input type="text" placeholder="Nama lengkap" style="width: 100%; padding: 0.75rem 1rem; background: var(--paper); border: 1px solid var(--line); font-family: var(--font-mono); font-size: 0.85rem; color: var(--ink);">
                        </div>
                        <div>
                            <label style="display: block; font-family: var(--font-mono); font-size: 0.65rem; font-weight: 600; text-transform: uppercase; color: var(--gold); margin-bottom: 0.5rem;">Telepon</label>
                            <input type="text" placeholder="08xxxxxxxxxx" style="width: 100%; padding: 0.75rem 1rem; background: var(--paper); border: 1px solid var(--line); font-family: var(--font-mono); font-size: 0.85rem; color: var(--ink);">
                        </div>
                    </div>
                    <div>
                        <label style="display: block; font-family: var(--font-mono); font-size: 0.65rem; font-weight: 600; text-transform: uppercase; color: var(--gold); margin-bottom: 0.5rem;">Email</label>
                        <input type="email" placeholder="nama@contoh.com" style="width: 100%; padding: 0.75rem 1rem; background: var(--paper); border: 1px solid var(--line); font-family: var(--font-mono); font-size: 0.85rem; color: var(--ink);">
                    </div>
                    <div style="display: grid; gap: 1rem; grid-template-columns: repeat(auto-fit, minmax(12rem, 1fr));">
                        <div>
                            <label style="display: block; font-family: var(--font-mono); font-size: 0.65rem; font-weight: 600; text-transform: uppercase; color: var(--gold); margin-bottom: 0.5rem;">Password</label>
                            <input type="password" placeholder="••••••••" style="width: 100%; padding: 0.75rem 1rem; background: var(--paper); border: 1px solid var(--line); font-family: var(--font-mono); font-size: 0.85rem; color: var(--ink);">
                        </div>
                        <div>
                            <label style="display: block; font-family: var(--font-mono); font-size: 0.65rem; font-weight: 600; text-transform: uppercase; color: var(--gold); margin-bottom: 0.5rem;">Konfirmasi</label>
                            <input type="password" placeholder="••••••••" style="width: 100%; padding: 0.75rem 1rem; background: var(--paper); border: 1px solid var(--line); font-family: var(--font-mono); font-size: 0.85rem; color: var(--ink);">
                        </div>
                    </div>
                    <div style="display: flex; flex-wrap: wrap; gap: 1rem; align-items: center;">
                        <button type="button" class="neo-btn">
                            Daftar →
                        </button>
                        <a href="{{ route('login') }}" class="neo-btn-secondary">
                            Sudah punya akun
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </main>
</x-layouts.app>
