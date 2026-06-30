<x-layouts.ppid-layout title="PPID - Pejabat Pengelola Informasi dan Dokumentasi">
    <!-- Navigation -->
    <x-ppid.nav />

    <!-- Content -->
    <main class="ppid-content">
        <!-- Hero Section -->
        <section class="ppid-hero">
            <div class="ppid-hero-bg">
                <img src="{{ asset('assets/img/template/ppid-bg.webp') }}" alt="PPID Background">
            </div>
            <div class="ppid-hero-content" data-reveal>
                <span class="ppid-hero-badge">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                    </svg>
                    Pejabat Pengelola Informasi dan Dokumentasi
                </span>
                <h1 class="ppid-hero-title">
                    <span>Portal Informasi</span>
                    Publik
                </h1>
                <p class="ppid-hero-subtitle">
                    Menyediakan akses informasi publik yang transparan, akuntabel, dan mudah diakses oleh masyarakat
                </p>
            </div>
        </section>

        <!-- Main Content -->
        <div class="ppid-page">
            <!-- Stats -->
            <div class="ppid-stats" data-reveal>
                <div class="ppid-stat">
                    <div class="ppid-stat-value">156</div>
                    <div class="ppid-stat-label">Permohonan</div>
                </div>
                <div class="ppid-stat">
                    <div class="ppid-stat-value">98%</div>
                    <div class="ppid-stat-label">Terselesaikan</div>
                </div>
                <div class="ppid-stat">
                    <div class="ppid-stat-value">24h</div>
                    <div class="ppid-stat-label">Avg Response</div>
                </div>
                <div class="ppid-stat">
                    <div class="ppid-stat-value">365</div>
                    <div class="ppid-stat-label">Hari Aktif</div>
                </div>
            </div>

            <!-- About Section -->
            <section class="ppid-section" data-reveal>
                <h2 class="ppid-section-title">Tentang PPID</h2>
                <div class="ppid-section-content">
                    <p>
                        Pejabat Pengelola Informasi dan Dokumentasi (PPID) adalah pejabat yang melaksanakan tugas dan fungsi sebagai pintu gerbang informasi bagi masyarakat untuk memperoleh informasi publik. PPID Kementerian Agama Kabupaten Tanah Datar berkomitmen untuk memberikan pelayanan informasi yang terbaik bagi masyarakat.
                    </p>
                    <p>
                        Berdasarkan Undang-Undang Nomor 14 Tahun 2008 tentang Keterbukaan Informasi Publik, setiap badan publik wajib menunjuk PPID untuk mengelola informasi dan dokumentasi.
                    </p>
                </div>

                <div class="ppid-info-box">
                    <h3 class="ppid-info-box-title">Motto Kami</h3>
                    <p class="ppid-info-box-text">
                        <strong>"Informasi Terbuka, Masyarakat Cerdas"</strong> — Kami percaya bahwa transparansi informasi adalah kunci good governance dan pemberdayaan masyarakat.
                    </p>
                </div>
            </section>

            <!-- Quick Links -->
            <section class="ppid-section" data-reveal>
                <h2 class="ppid-section-title">Layanan Cepat</h2>
                <div class="ppid-quick-links">
                    <a href="{{ route('ppid.formulir-permohonan') }}" class="ppid-quick-link">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                            <polyline points="14 2 14 8 20 8"/>
                            <line x1="12" y1="18" x2="12" y2="12"/>
                            <line x1="9" y1="15" x2="15" y2="15"/>
                        </svg>
                        Ajukan Permohonan Informasi
                    </a>
                    <a href="{{ route('ppid.formulir-keberatan') }}" class="ppid-quick-link">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="12" y1="8" x2="12" y2="12"/>
                            <line x1="12" y1="16" x2="12.01" y2="16"/>
                        </svg>
                        Ajukan Keberatan
                    </a>
                    <a href="{{ route('ppid.pengaduan') }}" class="ppid-quick-link">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/>
                            <line x1="4" y1="22" x2="4" y2="15"/>
                        </svg>
                        Sampaikan Pengaduan
                    </a>
                    <a href="{{ route('ppid.informasi-berkala') }}" class="ppid-quick-link">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                            <polyline points="7 10 12 15 17 10"/>
                            <line x1="12" y1="15" x2="12" y2="3"/>
                        </svg>
                        Unduh Informasi
                    </a>
                </div>
            </section>

            <!-- Services Grid -->
            <section class="ppid-section" data-reveal>
                <h2 class="ppid-section-title">Jelajahi Layanan Kami</h2>
                <div class="ppid-grid">
                    <a href="{{ route('ppid.profil-singkat') }}" class="ppid-card">
                        <div class="ppid-card-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                <circle cx="12" cy="7" r="4"/>
                            </svg>
                        </div>
                        <h3 class="ppid-card-title">Profil PPID</h3>
                        <p class="ppid-card-text">Kenali lebih dekat tentang PPID Kementerian Agama Kabupaten Tanah Datar</p>
                    </a>

                    <a href="{{ route('ppid.regulasi') }}" class="ppid-card">
                        <div class="ppid-card-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                <polyline points="14 2 14 8 20 8"/>
                                <line x1="16" y1="13" x2="8" y2="13"/>
                                <line x1="16" y1="17" x2="8" y2="17"/>
                            </svg>
                        </div>
                        <h3 class="ppid-card-title">Regulasi</h3>
                        <p class="ppid-card-text">Peraturan perundang-undangan terkait keterbukaan informasi publik</p>
                    </a>

                    <a href="{{ route('ppid.maklumat') }}" class="ppid-card">
                        <div class="ppid-card-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                            </svg>
                        </div>
                        <h3 class="ppid-card-title">Standar Layanan</h3>
                        <p class="ppid-card-text">Maklumat pelayanan, jadwal layanan, dan standar operasional</p>
                    </a>

                    <a href="{{ route('ppid.prosedur-permohonan') }}" class="ppid-card">
                        <div class="ppid-card-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"/>
                                <polyline points="12 6 12 12 16 14"/>
                            </svg>
                        </div>
                        <h3 class="ppid-card-title">Prosedur Layanan</h3>
                        <p class="ppid-card-text">Tata cara permohonan informasi, keberatan, dan penyelesaian sengketa</p>
                    </a>

                    <a href="{{ route('ppid.informasi-berkala') }}" class="ppid-card">
                        <div class="ppid-card-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                <polyline points="17 8 12 3 7 8"/>
                                <line x1="12" y1="3" x2="12" y2="15"/>
                            </svg>
                        </div>
                        <h3 class="ppid-card-title">Daftar Informasi</h3>
                        <p class="ppid-card-text">Informasi berkala, serta merta, dan informasi tersedia setiap saat</p>
                    </a>

                    <a href="{{ route('ppid.gallery-fasilitas') }}" class="ppid-card">
                        <div class="ppid-card-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                                <circle cx="8.5" cy="8.5" r="1.5"/>
                                <polyline points="21 15 16 10 5 21"/>
                            </svg>
                        </div>
                        <h3 class="ppid-card-title">Gallery</h3>
                        <p class="ppid-card-text">Dokumentasi fasilitas publik dan kegiatan PPID</p>
                    </a>
                </div>
            </section>

            <!-- Alur Prosedur -->
            <section class="ppid-section" data-reveal>
                <h2 class="ppid-section-title">Alur Permohonan Informasi</h2>
                <div class="ppid-timeline">
                    <div class="ppid-timeline-item">
                        <span class="ppid-timeline-number">Langkah 1</span>
                        <h3 class="ppid-timeline-title">Pengajuan Permohonan</h3>
                        <p class="ppid-timeline-text">Masyarakat mengajukan permohonan informasi melalui formulir yang tersedia atau langsung ke PPID</p>
                    </div>
                    <div class="ppid-timeline-item">
                        <span class="ppid-timeline-number">Langkah 2</span>
                        <h3 class="ppid-timeline-title">Pendaftaran</h3>
                        <p class="ppid-timeline-text">PPID mencatat dan meregistrasi permohonan informasi publik</p>
                    </div>
                    <div class="ppid-timeline-item">
                        <span class="ppid-timeline-number">Langkah 3</span>
                        <h3 class="ppid-timeline-title">Pemrosesan</h3>
                        <p class="ppid-timeline-text">PPID memproses permohonan dan mengecek ketersediaan informasi</p>
                    </div>
                    <div class="ppid-timeline-item">
                        <span class="ppid-timeline-number">Langkah 4</span>
                        <h3 class="ppid-timeline-title">Pemberian Informasi</h3>
                        <p class="ppid-timeline-text">Informasi diberikan kepada pemohon sesuai jangka waktu yang ditentukan</p>
                    </div>
                    <div class="ppid-timeline-item">
                        <span class="ppid-timeline-number">Langkah 5</span>
                        <h3 class="ppid-timeline-title">Keberatan (Opsional)</h3>
                        <p class="ppid-timeline-text">Jika tidak puas, pemohon dapat mengajukan keberatan</p>
                    </div>
                </div>
            </section>
        </div>

        <!-- Footer -->
        <footer class="ppid-footer">
            <div class="ppid-footer-inner">
                <div>
                    <div class="ppid-footer-brand">
                        <a class="brand-lockup" href="{{ url("/") }}">
                            <span class="brand-mark" aria-hidden="true"><span></span></span>
                            <span class="brand-word"><span>SILATAR</span><span>V2</span></span>
                        </a>
                    </div>
                    <h4 class="ppid-footer-title">PPID Kemenag Tanah Datar</h4>
                    <p class="ppid-footer-text">
                        Pejabat Pengelola Informasi dan Dokumentasi Kementerian Agama Kabupaten Tanah Datar
                    </p>
                </div>
                <div>
                    <h4 class="ppid-footer-title">Link Cepat</h4>
                    <ul class="ppid-footer-links">
                        <li><a href="{{ route('ppid.formulir-permohonan') }}">Ajukan Permohonan</a></li>
                        <li><a href="{{ route('ppid.formulir-keberatan') }}">Ajukan Keberatan</a></li>
                        <li><a href="{{ route('ppid.pengaduan') }}">Sampaikan Pengaduan</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="ppid-footer-title">Kontak</h4>
                    <ul class="ppid-footer-links">
                        <li>Kantor Kemenag Kab. Tanah Datar</li>
                        <li>Jl. Raya Batusangkar No. 1</li>
                        <li>Telp: (0752) 12345</li>
                        <li>Email: ppid@kemenag-tanahdatar.go.id</li>
                    </ul>
                </div>
            </div>
            <div class="ppid-footer-bottom">
                &copy; {{ date('Y') }} SILATAR - Kementerian Agama Kabupaten Tanah Datar
            </div>
        </footer>
    </main>

    </x-layouts.ppid-layout>
