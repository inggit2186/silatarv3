<?php

return [
    'loading' => [
        'enabled_routes' => [
            'laporan-kinerja',
            'pengajuan-saya',
            'pelayanan.request',
            'pelayanan.request.submit',
            'satuan-kerja',
            'unit-kerja.detail',
        ],
        'title' => 'Memuat data',
        'message' => 'Mohon tunggu sebentar.',
        'delay' => 150,
        'variants' => [
            'navigate' => [
                'title' => 'Membuka halaman',
                'message' => 'Sedang memindahkan tampilan.',
                'tone' => 'cyan',
            ],
            'submit' => [
                'title' => 'Mengirim data',
                'message' => 'Sedang memproses permintaan.',
                'tone' => 'amber',
            ],
            'print' => [
                'title' => 'Menyiapkan PDF',
                'message' => 'Sedang membuka dialog cetak.',
                'tone' => 'violet',
            ],
        ],
    ],
    'datepicker' => [
        'placeholder' => 'Pilih tanggal',
        'today_label' => 'Hari ini',
        'clear_label' => 'Hapus',
        'months' => [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December',
        ],
        'weekdays' => ['Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa', 'Su'],
        'locale' => 'en-US',
    ],
    'monthpicker' => [
        'placeholder' => 'Pilih bulan',
        'clear_label' => 'Hapus',
        'apply_label' => 'Pilih',
        'months' => [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember',
        ],
        'locale' => 'id-ID',
    ],
    'yearpicker' => [
        'placeholder' => 'Pilih tahun',
        'clear_label' => 'Hapus',
        'apply_label' => 'Pilih',
        'locale' => 'id-ID',
    ],
];
