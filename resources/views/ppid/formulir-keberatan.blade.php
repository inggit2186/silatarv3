<x-layouts.ppid-layout title="Formulir Keberatan - PPID">
    <x-ppid.nav />

    <main class="ppid-content">
        <div class="ppid-page">
            <div class="ppid-page-breadcrumb"><a href="{{ route('ppid') }}">PPID</a><span>/</span><span>Formulir</span><span>/</span><span>Keberatan</span></div>
            <div class="ppid-page-header" data-reveal>
                <h1 class="ppid-page-title">Formulir Pengajuan Keberatan</h1>
                <p class="ppid-page-subtitle">Ajukan keberatan atas pelayanan informasi</p>
            </div>

            <section class="ppid-section" data-reveal>
                <div class="ppid-info-box" style="margin-bottom: 1.5rem;"><p class="ppid-info-box-text"><strong>Perhatian:</strong> Keberatan dapat diajukan dalam waktu 30 hari kerja.</p></div>
                <div style="background: white; border: 1px solid oklch(58% 0.06 76 / 0.15); border-radius: 1.5rem; padding: 2rem;">
                    <form>
                        <div class="ppid-form-group"><label class="ppid-form-label">Nama Lengkap</label><input type="text" class="ppid-form-input" placeholder="Masukkan nama" required></div>
                        <div class="ppid-form-group"><label class="ppid-form-label">Nomor Pendaftaran</label><input type="text" class="ppid-form-input" placeholder="No pendaftaran sebelumnya" required></div>
                        <div class="ppid-form-group"><label class="ppid-form-label">Alasan Keberatan</label><select class="ppid-form-input" required><option value="">Pilih alasan</option><option value="ditolak">Permohonan ditolak</option><option value="tidak-sesuai">Tidak sesuai</option><option value="tidak-diberikan">Tidak diberikan</option></select></div>
                        <div class="ppid-form-group"><label class="ppid-form-label">Uraian</label><textarea class="ppid-form-input" rows="4" placeholder="Jelaskan alasan" required></textarea></div>
                        <div style="display: flex; gap: 1rem; margin-top: 1.5rem;"><button type="submit" class="ppid-btn ppid-btn-primary"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 2L11 13"/></svg>Kirim</button><button type="reset" class="ppid-btn ppid-btn-secondary">Reset</button></div>
                    </form>
                </div>
            </section>
        </div>
        @include('ppid.partials.footer')
    </main></x-layouts.ppid-layout>
