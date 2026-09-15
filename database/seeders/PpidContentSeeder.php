<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PpidContentSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            // 1. Beranda
            [
                'slug' => 'index',
                'title' => 'PPID - Pejabat Pengelola Informasi dan Dokumentasi',
                'subtitle' => null,
                'meta_description' => 'Portal informasi publik PPID Kementerian Agama Kabupaten Tanah Datar',
                'sections' => [
                    [
                        'section_key' => 'hero',
                        'section_type' => 'text',
                        'title' => 'Portal Informasi Publik',
                        'content' => '<span class="ppid-hero-badge">Pejabat Pengelola Informasi dan Dokumentasi</span><h1 class="ppid-hero-title"><span>Portal Informasi</span> Publik</h1><p class="ppid-hero-subtitle">Menyediakan akses informasi publik yang transparan, akuntabel, dan mudah diakses oleh masyarakat</p>',
                        'metadata' => null,
                        'sort_order' => 0,
                    ],
                    [
                        'section_key' => 'stats',
                        'section_type' => 'stats',
                        'title' => null,
                        'content' => null,
                        'metadata' => json_encode([
                            'stats' => [
                                ['value' => '156', 'label' => 'Permohonan'],
                                ['value' => '98%', 'label' => 'Terselesaikan'],
                                ['value' => '24h', 'label' => 'Avg Response'],
                                ['value' => '365', 'label' => 'Hari Aktif'],
                            ],
                        ]),
                        'sort_order' => 1,
                    ],
                    [
                        'section_key' => 'tentang_ppid',
                        'section_type' => 'text',
                        'title' => 'Tentang PPID',
                        'content' => '<p>Pejabat Pengelola Informasi dan Dokumentasi (PPID) adalah pejabat yang melaksanakan tugas dan fungsi sebagai pintu gerbang informasi bagi masyarakat untuk memperoleh informasi publik. PPID Kementerian Agama Kabupaten Tanah Datar berkomitmen untuk memberikan pelayanan informasi yang terbaik bagi masyarakat.</p><p>Berdasarkan Undang-Undang Nomor 14 Tahun 2008 tentang Keterbukaan Informasi Publik, setiap badan publik wajib menunjuk PPID untuk mengelola informasi dan dokumentasi.</p>',
                        'metadata' => null,
                        'sort_order' => 2,
                    ],
                    [
                        'section_key' => 'motto',
                        'section_type' => 'text',
                        'title' => 'Motto Kami',
                        'content' => '<strong>"Informasi Terbuka, Masyarakat Cerdas"</strong> — Kami percaya bahwa transparansi informasi adalah kunci good governance dan pemberdayaan masyarakat.',
                        'metadata' => null,
                        'sort_order' => 3,
                    ],
                    [
                        'section_key' => 'layanan_populer',
                        'section_type' => 'card_grid',
                        'title' => 'Jelajahi Layanan Kami',
                        'content' => null,
                        'metadata' => json_encode([
                            'cards' => [
                                ['title' => 'Profil PPID', 'description' => 'Kenali lebih dekat tentang PPID Kementerian Agama Kabupaten Tanah Datar', 'link' => '/ppid/profil-singkat'],
                                ['title' => 'Regulasi', 'description' => 'Peraturan perundang-undangan terkait keterbukaan informasi publik', 'link' => '/ppid/regulasi'],
                                ['title' => 'Standar Layanan', 'description' => 'Maklumat pelayanan, jadwal layanan, dan standar operasional', 'link' => '/ppid/maklumat'],
                                ['title' => 'Prosedur Layanan', 'description' => 'Tata cara permohonan informasi, keberatan, dan sengketa', 'link' => '/ppid/prosedur-permohonan'],
                                ['title' => 'Daftar Informasi', 'description' => 'Informasi berkala, serta merta, dan setiap saat', 'link' => '/ppid/informasi-berkala'],
                                ['title' => 'Gallery', 'description' => 'Galeri fasilitas dan kegiatan PPID', 'link' => '/ppid/gallery-fasilitas'],
                            ],
                        ]),
                        'sort_order' => 4,
                    ],
                    [
                        'section_key' => 'prosedur_timeline',
                        'section_type' => 'timeline',
                        'title' => 'Prosedur Permohonan Informasi',
                        'content' => null,
                        'metadata' => json_encode([
                            'steps' => [
                                ['number' => 1, 'title' => 'Ajukan Permohonan', 'description' => 'Isi formulir permohonan informasi secara online atau datang langsung ke kantor'],
                                ['number' => 2, 'title' => 'Verifikasi', 'description' => 'Tim PPID memverifikasi kelengkapan dan kelayakan permohonan'],
                                ['number' => 3, 'title' => 'Pemrosesan', 'description' => 'Informasi yang diminta disiapkan dan diverifikasi oleh PPID'],
                                ['number' => 4, 'title' => 'Penyerahan', 'description' => 'Informasi diserahkan kepada pemohon sesuai permintaan'],
                            ],
                        ]),
                        'sort_order' => 5,
                    ],
                ],
            ],

            // 2. Profil Singkat
            [
                'slug' => 'profil-singkat',
                'title' => 'Profil Singkat PPID',
                'subtitle' => null,
                'meta_description' => 'Profil singkat PPID Kementerian Agama Kabupaten Tanah Datar',
                'sections' => [
                    [
                        'section_key' => 'profil',
                        'section_type' => 'text',
                        'title' => 'Tentang PPID Kemenag Tanah Datar',
                        'content' => '<p>PPID Kementerian Agama Kabupaten Tanah Datar adalah pejabat yang ditunjuk untuk mengelola informasi dan dokumentasi di lingkungan Kementerian Agama Kabupaten Tanah Datar. PPID bertanggung jawab atas penyimpanan, pendokumentasian, penyediaan, dan pemeliharaan informasi publik.</p><p>PPID memiliki peran strategis dalam mewujudkan keterbukaan informasi publik sesuai dengan amanat Undang-Undang Nomor 14 Tahun 2008.</p>',
                        'metadata' => null,
                        'sort_order' => 0,
                    ],
                    [
                        'section_key' => 'tugas',
                        'section_type' => 'list',
                        'title' => 'Tugas Pokok',
                        'content' => null,
                        'metadata' => json_encode([
                            'items' => [
                                'Mengelola informasi dan dokumentasi yang dikuasai oleh Kementerian Agama Kabupaten Tanah Datar',
                                'Menyimpan, mendokumentasikan, menyediakan, dan/atau merawat informasi publik',
                                'Menyiapkan dan mengelola informasi yang harus diumumkan secara proaktif',
                                'Menyediakan informasi yang diminta oleh setiap orang secara cepat, tepat waktu, dan biaya ringan',
                                'Menolak permohonan informasi dengan alasan yang sah',
                                'Menerbitkan keputusan penolakan permohonan informasi',
                                'Melayani keberatan dan/atau komplain',
                            ],
                        ]),
                        'sort_order' => 1,
                    ],
                    [
                        'section_key' => 'fungsi',
                        'section_type' => 'list',
                        'title' => 'Fungsi',
                        'content' => null,
                        'metadata' => json_encode([
                            'items' => [
                                'Menyiapkan kebijakan teknis pengelolaan informasi dan dokumentasi',
                                'Mengkoordinasikan pengelolaan informasi dan dokumentasi',
                                'Memfasilitasi penyelesaian sengketa informasi publik',
                                'Melakukan pengawasan dan pengendalian pengelolaan informasi dan dokumentasi',
                                'Melaporkan pelaksanaan tugas kepada atasan',
                            ],
                        ]),
                        'sort_order' => 2,
                    ],
                ],
            ],

            // 3. Visi Misi
            [
                'slug' => 'visi-misi',
                'title' => 'Visi dan Misi',
                'subtitle' => 'Visi dan Misi PPID Kemenag Kabupaten Tanah Datar',
                'meta_description' => 'Visi dan misi PPID Kementerian Agama Kabupaten Tanah Datar',
                'sections' => [
                    [
                        'section_key' => 'visi',
                        'section_type' => 'text',
                        'title' => 'Visi',
                        'content' => '"Terwujudnya Government Transformation melalui optimalisasi keterbukaan informasi publik yang profesional, transparan, dan akuntabel untuk mendukung good governance di Kementerian Agama Kabupaten Tanah Datar."',
                        'metadata' => null,
                        'sort_order' => 0,
                    ],
                    [
                        'section_key' => 'misi',
                        'section_type' => 'card_grid',
                        'title' => 'Misi',
                        'content' => null,
                        'metadata' => json_encode([
                            'cards' => [
                                ['title' => 'Transparansi Optimal', 'description' => 'Melaksanakan keterbukaan informasi publik secara optimal dan bertanggung jawab', 'icon' => 'shield'],
                                ['title' => 'Sistem Terintegrasi', 'description' => 'Mengembangkan sistem informasi dan dokumentasi yang terintegrasi dan akuntabel', 'icon' => 'database'],
                                ['title' => 'Pelayanan Prima', 'description' => 'Meningkatkan kualitas dan kapasitas pelayanan informasi publik', 'icon' => 'users'],
                                ['title' => 'Koordinasi Efektif', 'description' => 'Membangun koordinasi yang efektif antar unit kerja dalam pengelolaan informasi', 'icon' => 'globe'],
                            ],
                        ]),
                        'sort_order' => 1,
                    ],
                ],
            ],

            // 4. Tugas Fungsi
            [
                'slug' => 'tugas-fungsi',
                'title' => 'Tugas, Fungsi & Wewenang',
                'subtitle' => null,
                'meta_description' => 'Tugas, fungsi, dan wewenang PPID Kementerian Agama Kabupaten Tanah Datar',
                'sections' => [
                    [
                        'section_key' => 'atasan_ppid',
                        'section_type' => 'list',
                        'title' => 'Tugas Atasan PPID',
                        'content' => null,
                        'metadata' => json_encode([
                            'items' => [
                                'Menetapkan kebijakan pengelolaan informasi dan dokumentasi',
                                'Mengawasi pelaksanaan tugas PPID',
                                'Menunjuk Pejabat Pengelola Informasi dan Dokumentasi',
                                'Melaporkan pelaksanaan tugas kepada Presiden',
                            ],
                        ]),
                        'sort_order' => 0,
                    ],
                    [
                        'section_key' => 'ppid_utama',
                        'section_type' => 'list',
                        'title' => 'Tugas PPID Utama',
                        'content' => null,
                        'metadata' => json_encode([
                            'items' => [
                                'Mengelola informasi dan dokumentasi yang dikuasai oleh Kementerian Agama',
                                'Menyimpan, mendokumentasikan, menyediakan, dan/atau merawat informasi publik',
                                'Menyiapkan dan mengelola informasi yang harus diumumkan secara proaktif',
                                'Menyediakan informasi yang diminta oleh setiap orang secara cepat, tepat waktu, dan biaya ringan',
                                'Menolak permohonan informasi dengan alasan yang sah',
                                'Menerbitkan keputusan penolakan permohonan informasi',
                                'Melayani keberatan dan/atau komplain',
                                'Melakukan penyelesaian sengketa informasi di tingkat PPID',
                                'Mempublikasikan informasi berkala setiap 6 bulan',
                            ],
                        ]),
                        'sort_order' => 1,
                    ],
                    [
                        'section_key' => 'ppid_pelaksana',
                        'section_type' => 'list',
                        'title' => 'Tugas PPID Pelaksana',
                        'content' => null,
                        'metadata' => json_encode([
                            'items' => [
                                'Melaksanakan kegiatan teknis pengelolaan informasi dan dokumentasi',
                                'Mengumpulkan informasi dari unit kerja',
                                'Menyimpan dan mengarsipkan informasi',
                                'Menyediakan informasi kepada pemohon',
                                'Membantu proses penyelesaian sengketa informasi',
                                'Menyiapkan laporan pelaksanaan tugas',
                            ],
                        ]),
                        'sort_order' => 2,
                    ],
                ],
            ],

            // 5. Struktur
            [
                'slug' => 'struktur',
                'title' => 'Struktur Kelembagaan',
                'subtitle' => null,
                'meta_description' => 'Struktur kelembagaan PPID Kementerian Agama Kabupaten Tanah Datar',
                'sections' => [
                    [
                        'section_key' => 'peran',
                        'section_type' => 'card_grid',
                        'title' => 'Struktur Organisasi',
                        'content' => null,
                        'metadata' => json_encode([
                            'cards' => [
                                ['title' => 'Atasan PPID', 'description' => 'Kepala Kantor Kementerian Agama Kabupaten Tanah Datar sebagai pengawas dan penanggung jawab utama pengelolaan informasi publik', 'icon' => 'user'],
                                ['title' => 'PPID Utama', 'description' => 'Pejabat yang ditunjuk untuk mengelola informasi dan dokumentasi di tingkat unit kerja pusat', 'icon' => 'shield'],
                                ['title' => 'PPID Pelaksana', 'description' => 'Tim yang melaksanakan kegiatan teknis pengelolaan informasi dan dokumentasi', 'icon' => 'users'],
                            ],
                        ]),
                        'sort_order' => 0,
                    ],
                ],
            ],

            // 6. Regulasi
            [
                'slug' => 'regulasi',
                'title' => 'Regulasi',
                'subtitle' => null,
                'meta_description' => 'Regulasi terkait keterbukaan informasi publik',
                'sections' => [
                    [
                        'section_key' => 'daftar_regulasi',
                        'section_type' => 'card_grid',
                        'title' => 'Dasar Hukum',
                        'content' => null,
                        'metadata' => json_encode([
                            'cards' => [
                                ['title' => 'UU No. 14 Tahun 2008', 'description' => 'Undang-Undang tentang Keterbukaan Informasi Publik', 'link' => '#', 'year' => '2008'],
                                ['title' => 'PP No. 61 Tahun 2010', 'description' => 'Peraturan Pemerintah tentang Pelaksanaan Undang-Undang Nomor 14 Tahun 2008', 'link' => '#', 'year' => '2010'],
                                ['title' => 'PermenPAN RB No. 30 Tahun 2018', 'description' => 'Pedoman Penyelenggaraan Pelayanan Informasi Publik', 'link' => '#', 'year' => '2018'],
                                ['title' => 'PermenPAN RB No. 27 Tahun 2017', 'description' => 'Pedoman Pembentukan Panel Informasi Publik', 'link' => '#', 'year' => '2017'],
                                ['title' => 'PermenPAN RB No. 26 Tahun 2017', 'description' => 'Pedoman Penetapan Informasi Publik yang Dikecualikan', 'link' => '#', 'year' => '2017'],
                                ['title' => 'SK PPID Kemenag', 'description' => 'Surat Keputusan Penunjukan PPID di Lingkungan Kementerian Agama', 'link' => '#', 'year' => '2023'],
                            ],
                        ]),
                        'sort_order' => 0,
                    ],
                ],
            ],

            // 7. Maklumat
            [
                'slug' => 'maklumat',
                'title' => 'Maklumat Pelayanan',
                'subtitle' => null,
                'meta_description' => 'Maklumat pelayanan informasi publik PPID Kementerian Agama Kabupaten Tanah Datar',
                'sections' => [
                    [
                        'section_key' => 'pernyataan',
                        'section_type' => 'text',
                        'title' => 'Maklumat Pelayanan Informasi Publik',
                        'content' => '<p>Kami Pejabat Pengelola Informasi dan Dokumentasi (PPID) Kementerian Agama Kabupaten Tanah Datar dengan ini menyatakan komitmen untuk:</p><ul><li>Memberikan pelayanan informasi publik secara cepat, tepat waktu, biaya ringan, dan transparan</li><li>Menyediakan informasi yang akurat dan terkini</li><li>Menjaga kerahasiaan informasi yang dikecualikan</li><li>Menyelesaikan setiap keberatan dan sengketa informasi secara adil</li></ul>',
                        'metadata' => null,
                        'sort_order' => 0,
                    ],
                    [
                        'section_key' => 'janji_pelayanan',
                        'section_type' => 'list',
                        'title' => 'Janji Pelayanan',
                        'content' => null,
                        'metadata' => json_encode([
                            'items' => [
                                'Menyediakan informasi publik yang diminta dalam waktu paling lambat 10 hari kerja',
                                'Memberikan pelayanan informasi secara online dan offline',
                                'Menjamin kerahasiaan informasi pribadi pemohon',
                                'Memberikan penjelasan yang jelas atas penolakan permohonan informasi',
                                'Menyediakan saluran pengaduan yang mudah diakses',
                                'Melaporkan pelaksanaan pelayanan secara berkala',
                            ],
                        ]),
                        'sort_order' => 1,
                    ],
                ],
            ],

            // 8. Jadwal
            [
                'slug' => 'jadwal',
                'title' => 'Jadwal Layanan',
                'subtitle' => 'Waktu dan Hari Layanan Informasi Publik',
                'meta_description' => 'Jadwal layanan informasi publik PPID Kementerian Agama Kabupaten Tanah Datar',
                'sections' => [
                    [
                        'section_key' => 'jadwal_opsional',
                        'section_type' => 'table',
                        'title' => 'Jam Operasional',
                        'content' => null,
                        'metadata' => json_encode([
                            'headers' => ['Hari', 'Jam Buka', 'Istirahat', 'Status'],
                            'rows' => [
                                ['Senin', '08.00 WIB', '12.00 - 13.00', 'Buka'],
                                ['Selasa', '08.00 WIB', '12.00 - 13.00', 'Buka'],
                                ['Rabu', '08.00 WIB', '12.00 - 13.00', 'Buka'],
                                ['Kamis', '08.00 WIB', '12.00 - 13.00', 'Buka'],
                                ['Jumat', '08.00 WIB', '11.30 - 13.30', 'Buka'],
                                ['Sabtu', '08.00 WIB', '12.00 - 13.00', 'Buka'],
                                ['Minggu', '-', '-', 'Tutup'],
                            ],
                        ]),
                        'sort_order' => 0,
                    ],
                    [
                        'section_key' => 'kontak',
                        'section_type' => 'card_grid',
                        'title' => 'Kontak Layanan',
                        'content' => null,
                        'metadata' => json_encode([
                            'cards' => [
                                ['title' => 'Telepon', 'description' => '(0752) 12345', 'icon' => 'phone'],
                                ['title' => 'Email', 'description' => 'ppid@kemenag-tanahdatar.go.id', 'icon' => 'mail'],
                                ['title' => 'Alamat', 'description' => 'Jl. Raya Batusangkar No. 1', 'icon' => 'map-pin'],
                            ],
                        ]),
                        'sort_order' => 1,
                    ],
                ],
            ],

            // 9. Biaya
            [
                'slug' => 'biaya',
                'title' => 'Biaya Layanan',
                'subtitle' => null,
                'meta_description' => 'Informasi biaya layanan informasi publik PPID Kementerian Agama Kabupaten Tanah Datar',
                'sections' => [
                    [
                        'section_key' => 'informasi_biaya',
                        'section_type' => 'text',
                        'title' => 'Ketentuan Biaya',
                        'content' => '<p>Pelayanan informasi publik oleh PPID Kementerian Agama Kabupaten Tanah Datar <strong>GRATIS</strong> (tidak dipungut biaya) sesuai dengan Undang-Undang Nomor 14 Tahun 2008 tentang Keterbukaan Informasi Publik.</p><p>Pemohon informasi hanya dikenakan biaya reproduksi informasi sesuai dengan ketentuan peraturan perundang-undangan yang berlaku.</p>',
                        'metadata' => null,
                        'sort_order' => 0,
                    ],
                    [
                        'section_key' => 'pengecualian',
                        'section_type' => 'table',
                        'title' => 'Biaya Reproduksi',
                        'content' => null,
                        'metadata' => json_encode([
                            'headers' => ['Jenis', 'Biaya', 'Keterangan'],
                            'rows' => [
                                ['Kertas A4', 'Rp 200,-', 'Per lembar'],
                                ['CD/DVD', 'Rp 5.000,-', 'Per keping'],
                                ['Flashdisk', 'Sesuai Harga', 'Sesuai harga pasar'],
                            ],
                        ]),
                        'sort_order' => 1,
                    ],
                ],
            ],

            // 10. Laporan Layanan
            [
                'slug' => 'laporan-layanan',
                'title' => 'Laporan Layanan',
                'subtitle' => null,
                'meta_description' => 'Laporan layanan informasi publik PPID Kementerian Agama Kabupaten Tanah Datar',
                'sections' => [
                    [
                        'section_key' => 'statistik',
                        'section_type' => 'stats',
                        'title' => 'Statistik Layanan',
                        'content' => null,
                        'metadata' => json_encode([
                            'stats' => [
                                ['value' => '156', 'label' => 'Total Permohonan'],
                                ['value' => '98%', 'label' => 'Tingkat Kepuasan'],
                                ['value' => '24h', 'label' => 'Waktu Respon'],
                                ['value' => '100%', 'label' => 'Transparansi'],
                            ],
                        ]),
                        'sort_order' => 0,
                    ],
                    [
                        'section_key' => 'rekap_bulanan',
                        'section_type' => 'table',
                        'title' => 'Rekap Bulanan 2024',
                        'content' => null,
                        'metadata' => json_encode([
                            'headers' => ['Bulan', 'Diterima', 'Diproses', 'Selesai', 'Persentase'],
                            'rows' => [
                                ['Januari', '12', '12', '12', '100%'],
                                ['Februari', '15', '15', '15', '100%'],
                                ['Maret', '23', '23', '22', '96%'],
                                ['April', '18', '18', '18', '100%'],
                                ['Mei', '30', '30', '30', '100%'],
                                ['Juni', '25', '25', '25', '100%'],
                            ],
                        ]),
                        'sort_order' => 1,
                    ],
                ],
            ],

            // 11. Prosedur Permohonan
            [
                'slug' => 'prosedur-permohonan',
                'title' => 'Tata Cara Permohonan Informasi',
                'subtitle' => null,
                'meta_description' => 'Tata cara permohonan informasi publik PPID Kementerian Agama Kabupaten Tanah Datar',
                'sections' => [
                    [
                        'section_key' => 'timeline',
                        'section_type' => 'timeline',
                        'title' => 'Prosedur Permohonan',
                        'content' => null,
                        'metadata' => json_encode([
                            'steps' => [
                                ['number' => 1, 'title' => 'Pengajuan', 'description' => 'Mengisi formulir permohonan informasi secara lengkap dan benar'],
                                ['number' => 2, 'title' => 'Penerimaan', 'description' => 'PPID menerima dan mencatat permohonan informasi'],
                                ['number' => 3, 'title' => 'Verifikasi', 'description' => 'PPID melakukan verifikasi kelengkapan dan kelayakan permohonan'],
                                ['number' => 4, 'title' => 'Penyerahan', 'description' => 'Informasi yang diminta diserahkan kepada pemohon'],
                            ],
                        ]),
                        'sort_order' => 0,
                    ],
                    [
                        'section_key' => 'waktu_penyelesaian',
                        'section_type' => 'card_grid',
                        'title' => 'Waktu Penyelesaian',
                        'content' => null,
                        'metadata' => json_encode([
                            'cards' => [
                                ['title' => 'Permohonan Biasa', 'description' => '10 hari kerja sejak diterimanya permohonan secara lengkap', 'icon' => 'clock'],
                                ['title' => 'Informasi Serta Merta', 'description' => 'Paling lambat 1x24 jam', 'icon' => 'zap'],
                                ['title' => 'Permohonan Lanjutan', 'description' => 'Diperpanjang 7 hari kerja dengan pemberitahuan tertulis', 'icon' => 'calendar'],
                            ],
                        ]),
                        'sort_order' => 1,
                    ],
                ],
            ],

            // 12. Prosedur Keberatan
            [
                'slug' => 'prosedur-keberatan',
                'title' => 'Tata Cara Keberatan',
                'subtitle' => null,
                'meta_description' => 'Tata cara pengajuan keberatan informasi publik PPID Kementerian Agama Kabupaten Tanah Datar',
                'sections' => [
                    [
                        'section_key' => 'timeline',
                        'section_type' => 'timeline',
                        'title' => 'Prosedur Keberatan',
                        'content' => null,
                        'metadata' => json_encode([
                            'steps' => [
                                ['number' => 1, 'title' => 'Pengajuan Keberatan', 'description' => 'Mengisi formulir keberatan dan menyampaikan alasan keberatan secara tertulis'],
                                ['number' => 2, 'title' => 'Penerimaan', 'description' => 'PPID menerima dan mencatat keberatan yang diajukan'],
                                ['number' => 3, 'title' => 'Peninjauan', 'description' => 'PPID melakukan peninjauan atas keberatan yang diajukan'],
                                ['number' => 4, 'title' => 'Keputusan', 'description' => 'PPID menerbitkan keputusan atas keberatan yang diajukan'],
                            ],
                        ]),
                        'sort_order' => 0,
                    ],
                    [
                        'section_key' => 'alasan_keberatan',
                        'section_type' => 'list',
                        'title' => 'Alasan Keberatan',
                        'content' => null,
                        'metadata' => json_encode([
                            'items' => [
                                'Permohonan informasi ditolak tanpa alasan yang jelas',
                                'Informasi yang diberikan tidak lengkap atau tidak sesuai',
                                'Informasi yang diberikan terlambat dari waktu yang ditentukan',
                                'Biaya yang dibebankan terlalu tinggi',
                                'Cara dan prosedur pengajuan yang rumit',
                            ],
                        ]),
                        'sort_order' => 1,
                    ],
                ],
            ],

            // 13. Prosedur Sengketa
            [
                'slug' => 'prosedur-sengketa',
                'title' => 'Tata Cara Sengketa',
                'subtitle' => null,
                'meta_description' => 'Tata cara penyelesaian sengketa informasi publik PPID Kementerian Agama Kabupaten Tanah Datar',
                'sections' => [
                    [
                        'section_key' => 'timeline',
                        'section_type' => 'timeline',
                        'title' => 'Prosedur Sengketa',
                        'content' => null,
                        'metadata' => json_encode([
                            'steps' => [
                                ['number' => 1, 'title' => 'Pengajuan Sengketa', 'description' => 'Pemohon mengajukan sengketa informasi secara tertulis kepada PPID'],
                                ['number' => 2, 'title' => 'Penerimaan', 'description' => 'PPID menerima dan mencatat sengketa yang diajukan'],
                                ['number' => 3, 'title' => 'Mediasi', 'description' => 'PPID melakukan mediasi antara pemohon dan badan publik'],
                                ['number' => 4, 'title' => 'Keputusan', 'description' => 'PPID menerbitkan keputusan atas sengketa yang diajukan'],
                                ['number' => 5, 'title' => 'Eksekusi', 'description' => 'Keputusan PPID dilaksanakan oleh badan publik'],
                            ],
                        ]),
                        'sort_order' => 0,
                    ],
                ],
            ],

            // 14. Formulir Permohonan
            [
                'slug' => 'formulir-permohonan',
                'title' => 'Formulir Permohonan Informasi',
                'subtitle' => null,
                'meta_description' => 'Formulir permohonan informasi publik PPID Kementerian Agama Kabupaten Tanah Datar',
                'sections' => [
                    [
                        'section_key' => 'instruksi',
                        'section_type' => 'text',
                        'title' => 'Petunjuk Pengisian',
                        'content' => '<p>Isi formulir di bawah ini dengan data yang benar dan lengkap. Semua field yang ditandai dengan (*) wajib diisi.</p><p>Setelah mengirim formulir, Anda akan menerima nomor registrasi yang dapat digunakan untuk melacak status permohonan Anda.</p>',
                        'metadata' => null,
                        'sort_order' => 0,
                    ],
                    [
                        'section_key' => 'form_fields',
                        'section_type' => 'form_fields',
                        'title' => 'Data Pemohon',
                        'content' => null,
                        'metadata' => json_encode([
                            'fields' => [
                                ['name' => 'nama', 'label' => 'Nama Lengkap', 'type' => 'text', 'required' => true],
                                ['name' => 'nik', 'label' => 'NIK', 'type' => 'text', 'required' => true],
                                ['name' => 'alamat', 'label' => 'Alamat', 'type' => 'textarea', 'required' => true],
                                ['name' => 'telepon', 'label' => 'Telepon/HP', 'type' => 'text', 'required' => true],
                                ['name' => 'email', 'label' => 'Email', 'type' => 'email', 'required' => true],
                                ['name' => 'informasi', 'label' => 'Informasi yang Diminta', 'type' => 'textarea', 'required' => true],
                                ['name' => 'tujuan', 'label' => 'Tujuan Penggunaan', 'type' => 'textarea', 'required' => true],
                                ['name' => 'cara_terima', 'label' => 'Cara Menerima Informasi', 'type' => 'select', 'required' => true, 'options' => ['Online', 'Datang Langsung', 'Email']],
                            ],
                        ]),
                        'sort_order' => 1,
                    ],
                ],
            ],

            // 15. Formulir Keberatan
            [
                'slug' => 'formulir-keberatan',
                'title' => 'Formulir Keberatan',
                'subtitle' => null,
                'meta_description' => 'Formulir pengajuan keberatan informasi publik PPID Kementerian Agama Kabupaten Tanah Datar',
                'sections' => [
                    [
                        'section_key' => 'instruksi',
                        'section_type' => 'text',
                        'title' => 'Petunjuk Pengisian',
                        'content' => '<p>Isi formulir di bawah ini jika Anda keberatan dengan keputusan PPID terkait permohonan informasi. Sertakan alasan keberatan secara jelas dan lengkap.</p>',
                        'metadata' => null,
                        'sort_order' => 0,
                    ],
                    [
                        'section_key' => 'form_fields',
                        'section_type' => 'form_fields',
                        'title' => 'Data Keberatan',
                        'content' => null,
                        'metadata' => json_encode([
                            'fields' => [
                                ['name' => 'nama', 'label' => 'Nama Lengkap', 'type' => 'text', 'required' => true],
                                ['name' => 'no_registrasi', 'label' => 'No. Registrasi Permohonan', 'type' => 'text', 'required' => true],
                                ['name' => 'alasan', 'label' => 'Alasan Keberatan', 'type' => 'select', 'required' => true, 'options' => ['Penolakan tanpa alasan', 'Informasi tidak lengkap', 'Informasi terlambat', 'Biaya berlebihan', 'Prosedur rumit']],
                                ['name' => 'uraian', 'label' => 'Uraian Keberatan', 'type' => 'textarea', 'required' => true],
                            ],
                        ]),
                        'sort_order' => 1,
                    ],
                ],
            ],

            // 16. Informasi Berkala
            [
                'slug' => 'informasi-berkala',
                'title' => 'Informasi Berkala',
                'subtitle' => null,
                'meta_description' => 'Daftar informasi berkala yang disediakan oleh PPID Kementerian Agama Kabupaten Tanah Datar',
                'sections' => [
                    [
                        'section_key' => 'daftar_informasi',
                        'section_type' => 'card_grid',
                        'title' => 'Jenis Informasi Berkala',
                        'content' => null,
                        'metadata' => json_encode([
                            'cards' => [
                                ['title' => 'Profil Organisasi', 'description' => 'Struktur organisasi, visi misi, tugas dan fungsi', 'icon' => 'building'],
                                ['title' => 'Rencana Kerja', 'description' => 'Rencana kerja tahunan dan strategi organisasi', 'icon' => 'clipboard'],
                                ['title' => 'Laporan Kinerja', 'description' => 'Laporan capaian kinerja dan realisasi anggaran', 'icon' => 'bar-chart'],
                            ],
                        ]),
                        'sort_order' => 0,
                    ],
                ],
            ],

            // 17. Informasi Serta Merta
            [
                'slug' => 'informasi-serta-merta',
                'title' => 'Informasi Serta Merta',
                'subtitle' => null,
                'meta_description' => 'Informasi yang harus diumumkan serta merta oleh PPID Kementerian Agama Kabupaten Tanah Datar',
                'sections' => [
                    [
                        'section_key' => 'peringatan',
                        'section_type' => 'text',
                        'title' => 'Peringatan',
                        'content' => '<div style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 0.75rem; padding: 1rem; margin-bottom: 1rem;"><strong style="color: #991b1b;">⚠️ Informasi Serta Merta</strong><p style="color: #991b1b; margin: 0.5rem 0 0 0;">Informasi yang harus diumumkan serta merta kepada publik karena menyangkut hajat hidup orang banyak atau kepentingan umum.</p></div>',
                        'metadata' => null,
                        'sort_order' => 0,
                    ],
                    [
                        'section_key' => 'daftar_informasi',
                        'section_type' => 'list',
                        'title' => 'Jenis Informasi Serta Merta',
                        'content' => null,
                        'metadata' => json_encode([
                            'items' => [
                                'Informasi mengenai kecelakaan kerja dan bencana alam',
                                'Informasi mengenai wabah penyakit',
                                'Informasi mengenai kerusuhan atau keamanan',
                                'Informasi mengenai pemadaman listrik dan telekomunikasi',
                                'Informasi mengenai penemuan penyebab kerusakan atau kehilangan aset negara',
                            ],
                        ]),
                        'sort_order' => 1,
                    ],
                ],
            ],

            // 18. Informasi Setiap Saat
            [
                'slug' => 'informasi-setiap-saat',
                'title' => 'Informasi Setiap Saat',
                'subtitle' => null,
                'meta_description' => 'Informasi yang wajib tersedia setiap saat oleh PPID Kementerian Agama Kabupaten Tanah Datar',
                'sections' => [
                    [
                        'section_key' => 'daftar_informasi',
                        'section_type' => 'card_grid',
                        'title' => 'Jenis Informasi Setiap Saat',
                        'content' => null,
                        'metadata' => json_encode([
                            'cards' => [
                                ['title' => 'Daftar Informasi Publik', 'description' => 'Katalog informasi yang dimiliki oleh PPID', 'icon' => 'list'],
                                ['title' => 'Informasi Berkala', 'description' => 'Informasi yang diperbarui secara berkala', 'icon' => 'refresh-cw'],
                                ['title' => 'Statistik Layanan', 'description' => 'Data statistik pelayanan informasi publik', 'icon' => 'bar-chart'],
                            ],
                        ]),
                        'sort_order' => 0,
                    ],
                ],
            ],

            // 19. Pengaduan
            [
                'slug' => 'pengaduan',
                'title' => 'Pengaduan',
                'subtitle' => null,
                'meta_description' => 'Formulir pengaduan layanan informasi publik PPID Kementerian Agama Kabupaten Tanah Datar',
                'sections' => [
                    [
                        'section_key' => 'instruksi',
                        'section_type' => 'text',
                        'title' => 'Petunjuk Pengisian',
                        'content' => '<p>Sampaikan pengaduan atau keluhan Anda mengenai layanan PPID melalui formulir di bawah ini. Kami akan merespon pengaduan Anda dalam waktu paling lambat 3 hari kerja.</p>',
                        'metadata' => null,
                        'sort_order' => 0,
                    ],
                    [
                        'section_key' => 'form_fields',
                        'section_type' => 'form_fields',
                        'title' => 'Formulir Pengaduan',
                        'content' => null,
                        'metadata' => json_encode([
                            'fields' => [
                                ['name' => 'nama', 'label' => 'Nama', 'type' => 'text', 'required' => true],
                                ['name' => 'email', 'label' => 'Email', 'type' => 'email', 'required' => true],
                                ['name' => 'subjek', 'label' => 'Subjek', 'type' => 'text', 'required' => true],
                                ['name' => 'uraian', 'label' => 'Uraian Pengaduan', 'type' => 'textarea', 'required' => true],
                            ],
                        ]),
                        'sort_order' => 1,
                    ],
                ],
            ],

            // 20. Gallery Fasilitas
            [
                'slug' => 'gallery-fasilitas',
                'title' => 'Gallery Fasilitas',
                'subtitle' => null,
                'meta_description' => 'Galeri fasilitas PPID Kementerian Agama Kabupaten Tanah Datar',
                'sections' => [],
            ],

            // 21. Gallery Kegiatan
            [
                'slug' => 'gallery-kegiatan',
                'title' => 'Gallery Kegiatan',
                'subtitle' => null,
                'meta_description' => 'Galeri kegiatan PPID Kementerian Agama Kabupaten Tanah Datar',
                'sections' => [],
            ],

            // 22. Tentang Kami
            [
                'slug' => 'tentang-kami',
                'title' => 'Tentang Kami',
                'subtitle' => null,
                'meta_description' => 'Tentang PPID Kementerian Agama Kabupaten Tanah Datar',
                'sections' => [
                    [
                        'section_key' => 'sambutan',
                        'section_type' => 'text',
                        'title' => 'Selamat Datang',
                        'content' => '<p>Selamat datang di portal informasi publik PPID Kementerian Agama Kabupaten Tanah Datar. Kami berkomitmen untuk memberikan pelayanan informasi publik yang terbaik bagi masyarakat.</p><p>PPID adalah pejabat yang bertanggung jawab atas pengelolaan informasi dan dokumentasi di lingkungan Kementerian Agama Kabupaten Tanah Datar. Kami hadir untuk memastikan keterbukaan informasi publik sesuai dengan amanat undang-undang.</p>',
                        'metadata' => null,
                        'sort_order' => 0,
                    ],
                    [
                        'section_key' => 'visi',
                        'section_type' => 'text',
                        'title' => 'Visi',
                        'content' => '<p>"Terwujudnya Government Transformation melalui optimalisasi keterbukaan informasi publik yang profesional, transparan, dan akuntabel untuk mendukung good governance di Kementerian Agama Kabupaten Tanah Datar."</p>',
                        'metadata' => null,
                        'sort_order' => 1,
                    ],
                    [
                        'section_key' => 'kontak',
                        'section_type' => 'card_grid',
                        'title' => 'Hubungi Kami',
                        'content' => null,
                        'metadata' => json_encode([
                            'cards' => [
                                ['title' => 'Telepon', 'description' => '(0752) 12345', 'icon' => 'phone'],
                                ['title' => 'Email', 'description' => 'ppid@kemenag-tanahdatar.go.id', 'icon' => 'mail'],
                                ['title' => 'Alamat', 'description' => 'Jl. Raya Batusangkar No. 1, Kabupaten Tanah Datar', 'icon' => 'map-pin'],
                            ],
                        ]),
                        'sort_order' => 2,
                    ],
                ],
            ],
        ];

        // Insert pages and sections
        foreach ($pages as $pageData) {
            $sections = $pageData['sections'];
            unset($pageData['sections']);

            $pageId = DB::table('ppid_pages')->insertGetId([
                'slug' => $pageData['slug'],
                'title' => $pageData['title'],
                'subtitle' => $pageData['subtitle'],
                'meta_description' => $pageData['meta_description'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($sections as $section) {
                DB::table('ppid_sections')->insert([
                    'page_id' => $pageId,
                    'section_key' => $section['section_key'],
                    'section_type' => $section['section_type'],
                    'title' => $section['title'],
                    'content' => $section['content'],
                    'metadata' => $section['metadata'],
                    'sort_order' => $section['sort_order'],
                    'is_visible' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
