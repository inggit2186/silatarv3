<x-layouts.ppid-layout title="Formulir Permohonan - PPID">
    <x-ppid.nav />

    <main class="ppid-content">
        <div class="ppid-page">
            <div class="ppid-page-breadcrumb"><a href="{{ route('ppid') }}">PPID</a><span>/</span><span>Formulir</span><span>/</span><span>Permohonan</span></div>
            <div class="ppid-page-header" data-reveal>
                <h1 class="ppid-page-title">Formulir Permohonan Informasi Publik</h1>
                <p class="ppid-page-subtitle">Ajukan permohonan informasi publik secara online</p>
            </div>

            <section class="ppid-section" data-reveal>
                <div style="background: white; border: 1px solid oklch(58% 0.06 76 / 0.15); border-radius: 1.5rem; padding: 2rem;">
                    <form>
                        <div class="ppid-form-group">
                            <label class="ppid-form-label">Nama Lengkap</label>
                            <input type="text" class="ppid-form-input" placeholder="Masukkan nama lengkap" required>
                        </div>
                        <div class="ppid-form-group">
                            <label class="ppid-form-label">NIK</label>
                            <input type="text" class="ppid-form-input" placeholder="Masukkan NIK" required maxlength="16">
                        </div>
                        <div class="ppid-form-group">
                            <label class="ppid-form-label">Alamat</label>
                            <textarea class="ppid-form-input" rows="3" placeholder="Masukkan alamat" required></textarea>
                        </div>
                        <div class="ppid-form-group">
                            <label class="ppid-form-label">Nomor Telepon</label>
                            <input type="tel" class="ppid-form-input" placeholder="08xxxxxxxxxx" required>
                        </div>
                        <div class="ppid-form-group">
                            <label class="ppid-form-label">Email</label>
                            <input type="email" class="ppid-form-input" placeholder="email@contoh.com" required>
                        </div>
                        <div class="ppid-form-group">
                            <label class="ppid-form-label">Rincian Informasi yang Diminta</label>
                            <textarea class="ppid-form-input" rows="4" placeholder="Jelaskan informasi yang Anda minta" required></textarea>
                        </div>
                        <div class="ppid-form-group">
                            <label class="ppid-form-label">Tujuan Penggunaan</label>
                            <textarea class="ppid-form-input" rows="2" placeholder="Jelaskan tujuan penggunaan" required></textarea>
                        </div>
                        <div class="ppid-form-group">
                            <label class="ppid-form-label">Cara Memperoleh</label>
                            <select class="ppid-form-input" required>
                                <option value="">Pilih cara</option>
                                <option value="melihat">Melihat/Membaca</option>
                                <option value="softcopy">Salinan Softcopy</option>
                                <option value="hardcopy">Salinan Hardcopy</option>
                            </select>
                        </div>
                        <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                            <button type="submit" class="ppid-btn ppid-btn-primary"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 2L11 13"/></svg>Kirim</button>
                            <button type="reset" class="ppid-btn ppid-btn-secondary">Reset</button>
                        </div>
                    </form>
                </div>
            </section>
        </div>
        @include('ppid.partials.footer')
    </main></x-layouts.ppid-layout>
