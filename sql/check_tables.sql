-- Cek tabel yang dipakai aplikasi (hasil dari grep kode
-- Jalankan di MySQL/phpmyadmin untuk cek ada/tidak

SHOW TABLES IN kemen agtd_db WHERE Tables_in_kemenagtd_db IN ('satker_pemberkasan','satker_filepemberkasan','satker_ckh','satker_kegatan','users_request','users_berkas','users_files','users_request_answers','users','ktd_layanan','ktd_syarat','ktd_department','ktd_bukutamu','ktd_pengaduan','activities','laporan_humas','news','news_logs','user_signatures','plt_plh','hak_akses');
