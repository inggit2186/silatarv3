<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Section Types
    |--------------------------------------------------------------------------
    | Registry tipe section yang dipakai admin & controller.
    | Menambahkan tipe baru cukup di sini — controller validasi dan
    | tampilan admin keduanya membaca dari sumber yang sama.
    */
    'section_types' => [
        'text' => [
            'label' => 'Teks Rich',
            'short' => 'Teks',
            'description' => 'Paragraf artikel dengan format kaya — bold, list, link, tabel, dan gambar.',
            'color' => 'cyan',
            'icon' => '<path d="M4 6h16M4 12h16M4 18h7"/>',
        ],
        'list' => [
            'label' => 'Daftar Poin',
            'short' => 'Daftar',
            'description' => 'Daftar poin berurutan dengan penomoran otomatis.',
            'color' => 'blue',
            'icon' => '<line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/>',
        ],
        'card_grid' => [
            'label' => 'Grid Kartu',
            'short' => 'Kartu',
            'description' => 'Kumpulan kartu bergaya grid — judul, deskripsi, ikon, dan tautan.',
            'color' => 'violet',
            'icon' => '<rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/>',
        ],
        'timeline' => [
            'label' => 'Timeline',
            'short' => 'Langkah',
            'description' => 'Proses bertahap berurutan — setiap langkah punya judul dan penjelasan.',
            'color' => 'amber',
            'icon' => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
        ],
        'stats' => [
            'label' => 'Statistik',
            'short' => 'Statistik',
            'description' => 'Angka sorotan dengan label dan ikon untuk menyorot data penting.',
            'color' => 'emerald',
            'icon' => '<path d="M18 20V10M12 20V4M6 20v-6"/>',
        ],
        'table' => [
            'label' => 'Tabel Data',
            'short' => 'Tabel',
            'description' => 'Tabel dengan header dinamis dan baris data yang bisa ditambah.',
            'color' => 'indigo',
            'icon' => '<rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M3 15h18M12 3v18"/>',
        ],
        'form_fields' => [
            'label' => 'Field Formulir',
            'short' => 'Formulir',
            'description' => 'Definisi field formulir dinamis — input, select, checkbox, dan opsi.',
            'color' => 'rose',
            'icon' => '<rect x="4" y="4" width="16" height="16" rx="2"/><path d="M8 8h8M8 12h8M8 16h5"/>',
        ],
        'image' => [
            'label' => 'Gambar',
            'short' => 'Gambar',
            'description' => 'Satu gambar utama dengan alt text untuk aksesibilitas.',
            'color' => 'primary',
            'icon' => '<rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/>',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Icons
    |--------------------------------------------------------------------------
    | Daftar ikon (Feather Icons) yang bisa dipilih pada section card_grid
    | dan stats. Dipakai oleh picker di admin dan renderer halaman publik,
    | sehingga nama ikon yang sama selalu valid di kedua sisi.
    */
    'icons' => [
        'file' => [
            'label' => 'Dokumen',
            'paths' => '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/>',
        ],
        'folder' => [
            'label' => 'Folder',
            'paths' => '<path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/>',
        ],
        'book-open' => [
            'label' => 'Buku',
            'paths' => '<path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>',
        ],
        'clipboard' => [
            'label' => 'Clipboard',
            'paths' => '<path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><rect x="8" y="2" width="8" height="4" rx="1" ry="1"/>',
        ],
        'award' => [
            'label' => 'Sertifikat',
            'paths' => '<circle cx="12" cy="8" r="7"/><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"/>',
        ],
        'download' => [
            'label' => 'Unduh',
            'paths' => '<path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/>',
        ],
        'printer' => [
            'label' => 'Cetak',
            'paths' => '<polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/>',
        ],
        'building' => [
            'label' => 'Gedung',
            'paths' => '<rect x="4" y="2" width="16" height="20" rx="2"/><path d="M9 22v-4h6v4"/><path d="M8 6h.01M16 6h.01M12 6h.01M8 10h.01M16 10h.01M12 10h.01M8 14h.01M16 14h.01M12 14h.01"/>',
        ],
        'home' => [
            'label' => 'Rumah',
            'paths' => '<path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>',
        ],
        'briefcase' => [
            'label' => 'Kantor',
            'paths' => '<rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>',
        ],
        'shield' => [
            'label' => 'Perisai',
            'paths' => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>',
        ],
        'lock' => [
            'label' => 'Keamanan',
            'paths' => '<rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>',
        ],
        'users' => [
            'label' => 'Masyarakat',
            'paths' => '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>',
        ],
        'user' => [
            'label' => 'Pengguna',
            'paths' => '<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>',
        ],
        'database' => [
            'label' => 'Basis Data',
            'paths' => '<ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"/><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"/>',
        ],
        'globe' => [
            'label' => 'Dunia / Website',
            'paths' => '<circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>',
        ],
        'phone' => [
            'label' => 'Telepon',
            'paths' => '<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/>',
        ],
        'mail' => [
            'label' => 'Email',
            'paths' => '<path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/>',
        ],
        'message-square' => [
            'label' => 'Pesan',
            'paths' => '<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>',
        ],
        'map-pin' => [
            'label' => 'Lokasi',
            'paths' => '<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>',
        ],
        'clock' => [
            'label' => 'Waktu',
            'paths' => '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>',
        ],
        'calendar' => [
            'label' => 'Kalender',
            'paths' => '<rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>',
        ],
        'zap' => [
            'label' => 'Cepat / Energi',
            'paths' => '<polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>',
        ],
        'star' => [
            'label' => 'Bintang',
            'paths' => '<polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>',
        ],
        'heart' => [
            'label' => 'Hati',
            'paths' => '<path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>',
        ],
        'bar-chart' => [
            'label' => 'Grafik Batang',
            'paths' => '<line x1="12" y1="20" x2="12" y2="10"/><line x1="18" y1="20" x2="18" y2="4"/><line x1="6" y1="20" x2="6" y2="16"/>',
        ],
        'pie-chart' => [
            'label' => 'Grafik Lingkaran',
            'paths' => '<path d="M21.21 15.89A10 10 0 1 1 8 2.83"/><path d="M22 12A10 10 0 0 0 12 2v10z"/>',
        ],
        'trending-up' => [
            'label' => 'Pertumbuhan',
            'paths' => '<polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/>',
        ],
        'dollar-sign' => [
            'label' => 'Biaya / Rupiah',
            'paths' => '<line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>',
        ],
        'list' => [
            'label' => 'Daftar',
            'paths' => '<line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/>',
        ],
        'refresh-cw' => [
            'label' => 'Proses / Ulang',
            'paths' => '<polyline points="23 4 23 10 17 10"/><polyline points="1 20 1 14 7 14"/><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/>',
        ],
        'check-circle' => [
            'label' => 'Selesai / Valid',
            'paths' => '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>',
        ],
        'alert-circle' => [
            'label' => 'Peringatan',
            'paths' => '<circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>',
        ],
        'info' => [
            'label' => 'Informasi',
            'paths' => '<circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/>',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Ikon default
    |--------------------------------------------------------------------------
    | Dipakai bila section memilih ikon yang tidak dikenal / kosong.
    */
    'default_icon' => 'file',

    /*
    |--------------------------------------------------------------------------
    | Page Slots
    |--------------------------------------------------------------------------
    | Section key yang sudah punya tempat di template halaman publik.
    | Section dengan key di luar daftar ini tetap tampil — di render sebagai
    | section tambahan di akhir halaman.
    */
    'page_slots' => [
        'index' => ['hero', 'stats', 'tentang_ppid', 'motto', 'layanan_populer', 'prosedur_timeline'],
        'profil-singkat' => ['profil', 'tugas', 'fungsi'],
        'visi-misi' => ['visi', 'misi'],
        'tugas-fungsi' => ['atasan_ppid', 'ppid_utama', 'ppid_pelaksana'],
        'struktur' => ['peran'],
        'regulasi' => ['daftar_regulasi'],
        'maklumat' => ['pernyataan', 'janji_pelayanan'],
        'jadwal' => ['jadwal_opsional', 'kontak'],
        'biaya' => ['informasi_biaya', 'pengecualian'],
        'laporan-layanan' => ['statistik', 'rekap_bulanan'],
        'prosedur-permohonan' => ['timeline', 'waktu_penyelesaian'],
        'prosedur-keberatan' => ['timeline', 'alasan_keberatan'],
        'prosedur-sengketa' => ['timeline'],
        'formulir-permohonan' => ['instruksi', 'form_fields'],
        'formulir-keberatan' => ['instruksi', 'form_fields'],
        'informasi-berkala' => ['daftar_informasi'],
        'informasi-serta-merta' => ['peringatan', 'daftar_informasi'],
        'informasi-setiap-saat' => ['daftar_informasi'],
        'pengaduan' => ['instruksi', 'form_fields'],
        'tentang-kami' => ['sambutan', 'visi', 'kontak'],
        'gallery-fasilitas' => [],
        'gallery-kegiatan' => [],
    ],
];
