<?php

namespace App\Commands\Webhook;

use App\Models\HakAkses;
use App\Models\User;
use Carbon\Carbon;

class HalalCommand extends BaseCommand
{
    public function execute(): ?array
    {
        $msg = $this->message;

        // Initial halal menu
        if ($msg === '*layanan sertifikasi halal*') {
            return $this->sendJenisUsahaMenu();
        }

        // UMK menu
        if ($msg === '*usaha mikro dan kecil (umk)*') {
            return $this->sendSehatiMenu();
        }

        // Non UMK menu
        if ($msg === '*usaha non mikro dan kecil (non umk)*') {
            return $this->sendRegulerMenu();
        }

        // Alur SEHATI
        if ($msg === '*alur pendaftaran sertifikasi halal gratis (sehati)*') {
            return $this->sendAlurSehati();
        }

        // Alur Reguler
        if ($msg === '*alur pendaftaran sertifikasi halal reguler*') {
            return $this->sendAlurReguler();
        }

        // Dokumen SEHATI
        if ($msg === '*dokumen persyaratan sertifikasi halal gratis (sehati)*') {
            return $this->sendDokumenSehati();
        }

        // Dokumen Reguler
        if ($msg === '*dokumen persyaratan sertifikasi halal reguler*') {
            return $this->sendDokumenReguler();
        }

        // Biaya SEHATI
        if ($msg === '*biaya sertifikasi halal gratis (sehati)*') {
            return $this->sendBiayaSehati();
        }

        // Biaya Reguler
        if ($msg === '*biaya sertifikasi halal reguler*') {
            return $this->sendBiayaReguler();
        }

        // Pendaftaran SEHATI
        if ($msg === '*pendaftaran sertifikasi halal gratis (sehati)*') {
            return $this->sendPendaftaranSehati();
        }

        // Hubungi LP3H
        if ($msg === '*hubungi lembaga proses produk halal (lp3h)*') {
            return $this->sendLp3hInfo();
        }

        // Pendaftaran Reguler
        if ($msg === '*pendaftaran sertifikasi halal reguler*') {
            return $this->sendPendaftaranReguler();
        }

        // Hotline
        if ($msg === '*hotline sertifikasi halal*') {
            return $this->sendHotline();
        }

        // Hubungi P3H terdekat
        if ($msg === '*hubungi pendamping proses produk halal (p3h) terdekat*') {
            return $this->sendP3HKecamatanMenu();
        }

        // Kecamatan selection
        if (str_starts_with($msg, '*kec.')) {
            return $this->handleKecamatanSelection();
        }

        // P3H ID selection
        if (preg_match('/^\(\d+\)$/', $msg)) {
            return $this->handleP3HSelection();
        }

        return null;
    }

    private function sendJenisUsahaMenu(): ?array
    {
        $textWA = "*:: SILATAR AI-CHAT ::*\n\n"
            . "Silahkan pilih Jenis/Skala Usaha Anda...!! \n\n\n"
            . "_*SILATAR AI*_";

        $sections = [
            [
                "title" => "Jenis/Skala Usaha",
                "description" => "Silahkan Pilih Jenis/Skala Usaha Anda.",
                "rows" => [
                    ["title" => "*Usaha Mikro dan Kecil (UMK)*", "rowId" => "id1", "description" => "Menu Usaha Mikro dan Kecil(UMK)(Self Declare)"],
                    ["title" => "*Usaha Non Mikro dan Kecil (Non UMK)*", "rowId" => "id2", "description" => "Menu Usaha Non Mikro dan Kecil(Non UMK)(Requler)"],
                ],
            ],
        ];

        return $this->waService->sendList(
            $this->phoneNumber,
            'JENIS USAHA',
            'Jenis Usaha',
            $textWA,
            $sections
        );
    }

    private function sendSehatiMenu(): ?array
    {
        $textWA = "*:: SILATAR AI-CHAT ::*\n\n"
            . "Silahkan pilih Layanan Sertifikasi Halal yang anda inginkan...!! \n\n\n"
            . "_*SILATAR AI*_";

        $sections = [
            [
                "title" => "Layanan Sertifikasi Halal Gratis (SEHATI)",
                "description" => "Silahkan Layanan Sertifikasi Halal yang anda inginkan.",
                "rows" => [
                    ["title" => "*Alur Pendaftaran SEHATI*", "rowId" => "id1", "description" => "Informasi Tentang Alur Pendaftaran Sertifikasi Halal"],
                    ["title" => "*Dokumen Persyaratan SEHATI*", "rowId" => "id2", "description" => "Informasi Tentang Dokumen Persyaratan Sertifikasi Halal"],
                    ["title" => "*Biaya Sertifikasi Halal SEHATI*", "rowId" => "id3", "description" => "Informasi Tentang Biaya Pendaftaran Sertifikasi Halal"],
                    ["title" => "*Pendaftaran Sertifikasi Halal SEHATI*", "rowId" => "id4", "description" => "Konsultasi untuk Pendaftaran Sertifikasi Halal"],
                    ["title" => "*Hotline Sertifikasi Halal*", "rowId" => "id5", "description" => "Hotline Sertifikasi Halal"],
                ],
            ],
        ];

        return $this->waService->sendList(
            $this->phoneNumber,
            'DAFTAR LAYANAN',
            'Daftar Layanan',
            $textWA,
            $sections
        );
    }

    private function sendRegulerMenu(): ?array
    {
        $textWA = "*:: SILATAR AI-CHAT ::*\n\n"
            . "Silahkan pilih Layanan Sertifikasi Halal yang anda inginkan...!! \n\n\n"
            . "_*SILATAR AI*_";

        $sections = [
            [
                "title" => "Layanan Sertifikasi Halal Reguler",
                "description" => "Silahkan Layanan Sertifikasi Halal yang anda inginkan.",
                "rows" => [
                    ["title" => "*Alur Pendaftaran Sertifikasi Halal Reguler*", "rowId" => "id1", "description" => "Informasi Tentang Alur Pendaftaran Sertifikasi Halal Reguler"],
                    ["title" => "*Dokumen Persyaratan Sertifikasi Halal Reguler*", "rowId" => "id2", "description" => "Informasi Tentang Dokumen Persyaratan Sertifikasi Halal Reguler"],
                    ["title" => "*Biaya Sertifikasi Halal Reguler*", "rowId" => "id3", "description" => "Informasi Tentang Biaya Pendaftaran Sertifikasi Halal Reguler"],
                    ["title" => "*Pendaftaran Sertifikasi Halal Reguler*", "rowId" => "id4", "description" => "Konsultasi untuk Pendaftaran Sertifikasi Halal Reguler"],
                    ["title" => "*Hotline Sertifikasi Halal*", "rowId" => "id5", "description" => "Hotline Sertifikasi Halal(Kontak BPJPH)"],
                ],
            ],
        ];

        return $this->waService->sendList(
            $this->phoneNumber,
            'DAFTAR LAYANAN',
            'Daftar Layanan',
            $textWA,
            $sections
        );
    }

    private function sendAlurSehati(): ?array
    {
        $textWA = "*:: SILATAR AI-CHAT ::*\n\n"
            . "*Alur Sertifikasi Halal Gratis (SEHATI) :* \n\n"
            . "*Pelaku Usaha* \n"
            . "- Membuat akun melalui ptsp.halal.go.id \n"
            . "- Mempersiapkan data permohonan sertifikasi halal dan memilih pendamping PPH \n"
            . "- Melengkapi data permohonan bersama pendamping PPH \n"
            . "- Mengajukan permohonan sertifikasi halal dengan pernyataan pelaku usaha melalui SIHALAL \n\n"
            . "*Pendamping Proses Produk Halal (P3H)* \n"
            . "- Pendamping PPH melakukan verifikasi dan validasi atas pernyataan pelaku usaha \n\n"
            . "*BPJPH* \n"
            . "- BPJPH melakukan verifikasi dan validasi secara sistem terhadap laporan hasil \n"
            . "- Menerbitkan STTD (Surat Tanda Terima Dokumen) \n\n"
            . "*Komite Fatwa Produk Halal* \n"
            . "- Menerima laporan hasil pendampingan proses produk halal yang telah terverifikasi secara sistem oleh BPJPH dan melakukan sidang fatwa untuk menetapkan kehalalan produk \n\n"
            . "*BPJPH* \n"
            . "- Menerima ketetapan kehalalan produk \n"
            . "- Menerbitkan sertifikasi halal \n\n"
            . "*Pelaku Usaha* \n"
            . "- Mengunduh sertifikat halal melalui SIHALAL \n"
            . "- Mengunduh label halal nasional untuk dicantumkan pada produk \n\n"
            . "_*SILATAR AI*_";

        return $this->waService->sendMessage($this->phoneNumber, $textWA);
    }

    private function sendAlurReguler(): ?array
    {
        $textWA = "*:: SILATAR AI-CHAT ::*\n\n"
            . "*Alur Sertifikasi Halal Reguler :* \n\n"
            . "- Sebelum mendaftar pastikan pelaku usaha memiliki email aktif dan NIB Berbasis Risiko (jika belum, please daftar atau migrasi NIB melalui https://oss.go.id) \n\n"
            . "- Pelaku usaha membuat akun, kemudian mengajukan permohonan sertifikasi halal dengan mengisikan data dan mengunggah dokumen persyaratan melalui https://ptsp.halal.go.id/ (SIHALAL) \n\n"
            . "- BPJPH memverifikasi kesesuaian data dan kelengkapan dokumen permohonan \n\n"
            . "- LPH menghitung, menetapkan dan mengisikan biaya pemeriksaan di SIHALAL \n\n"
            . "- Pelaku Usaha melakukan pembayaran melalui virtual account sesuai dengan kode pembayaran yang tertera pada invoice di SIHALAL \n\n"
            . "- BPJPH melakukan verifikasi pembayaran dan menerbitkan STTD (Surat Tanda Terima Dokumen) di SIHALAL \n\n"
            . "- LPH melakukan proses pemeriksaan (audit) dan mengunggah Laporan Pemeriksaan di SIHALAL \n\n"
            . "- Komisi Fatwa MUI/MPU Aceh/Komite Fatwa Produk Halal melakukan Sidang Fatwa dan mengunggah Ketetapan Halal di SIHALAL \n\n"
            . "- BPJPH menerbitkan Sertifikat Halal \n\n"
            . "- Pelaku usaha mengunduh sertifikat halal di SIHALAL jika statusnya _*Terbit SH*_ \n\n"
            . "_*SILATAR AI*_";

        return $this->waService->sendMessage($this->phoneNumber, $textWA);
    }

    private function sendDokumenSehati(): ?array
    {
        $textWA = "*:: SILATAR AI-CHAT ::*\n\n"
            . "*Persyaratan Sertifikasi Halal* : \n\n"
            . "- Pelaku Usaha memiliki NIB dan termasuk skala usaha Mikro atau Kecil \n\n"
            . "- Pelaku Usaha memiliki Akun di SIHALAL \n\n"
            . "- Produk yang diajukan berupa barang dan tidak berisiko \n\n"
            . "- Produk yang diajukan tidak menggunakan bahan berbahaya dan hanya menggunakan bahan yang sudah pastikan kehalalannya \n\n"
            . "1. Dibuktikan dengan Sertifikat Halal, atau \n"
            . "2. Termasuk dalam daftar bahan sesuai KMA Nomor 1360 tentang Bahan yang Dikecualikan dari Kewajiban Bersertifikat Halal \n\n"
            . "- Proses Produksi secara sederhana dan pastinya bebas dari kontaminasi najis dan bahan tidak halal \n\n"
            . "- Menggunakan peralatan produksi dengan teknologi sederhana atau dilakukan secara manual dan/atau semi otomatis (usaha rumahan bukan usaha pabrik) \n\n"
            . "- Telah diverifikasi kehalalannya oleh Pendamping Proses Produk Halal \n\n"
            . "- Proses pengawetan produk dilakukan secara sederhana dan tidak menggunakan kombinasi metode pengawetan \n\n"
            . "- Bersedia melengkapi dokumen pengajuan Sertifikasi Halal dengan mekanisme pernyataan mandiri secara online melalui SIHALAL \n\n"
            . "_*SILATAR AI*_";

        return $this->waService->sendMessage($this->phoneNumber, $textWA);
    }

    private function sendDokumenReguler(): ?array
    {
        $textWA = "*:: SILATAR AI-CHAT ::*\n\n"
            . "*Persyaratan Sertifikasi Halal* : \n\n"
            . "- *Surat Permohonan* \n"
            . "Format dapat diunduh di https://bpjph.halal.go.id/detail/informasi-1 \n"
            . "Bagi Pelaku Usaha Luar Negeri (PULN), surat permohonan dibuat oleh importir/perwakilan resmi dan harus melampirkan surat kuasa penunjukan importir/perwakilan resmi dari PULN \n\n"
            . "- *Formulir Pendaftaran* \n"
            . "Format dapat diunduh di https://bpjph.halal.go.id/detail/informasi-1 \n"
            . "Khusus Jasa Penyembelihan wajib memiliki 2 nama Juru Sembelih Halal (Juleha) yang memiliki sertifikat pelatihan berbasis SKKNI dan/atau sertifikat kompetensi sebagai Juleha \n\n"
            . "- *Aspek Legal* \n"
            . "NIB berbasis risiko \n"
            . "Pelaku Usaha Luar Negeri : Lisensi Bisnis dan NIB importir/perwakilan resmi \n\n"
            . "- *Dokumen Penyelia Halal* \n"
            . "1. SK penetapan penyelia halal dari pimpiinan perusahaan \n"
            . "2. Kartu Identitas \n"
            . "3. Daftar riwayat Hidup \n"
            . "4. Sertifikat pelatihan dan/atau sertifikat kompetensi Penyelia Halal \n\n"
            . "- *Daftar Nama Produk dan Bahan/Menu/Barang* \n"
            . "Format dapat diunduh di https://bpjph.halal.go.id/detail/informasi-1 \n\n"
            . "- *Proses Pengolahan Produk* \n"
            . "Diagram alur atau deskripsi proses produksi \n\n"
            . "- *Manual SJPH* \n"
            . "Format dapat diunduh di https://bpjph.halal.go.id/detail/informasi-1 \n\n\n"
            . "_*SILATAR AI*_";

        return $this->waService->sendMessage($this->phoneNumber, $textWA);
    }

    private function sendBiayaSehati(): ?array
    {
        $textWA = "*:: SILATAR AI-CHAT ::*\n\n"
            . "Untuk Sertifikasi Halal (*SEHATI*) *tidak dipungut biaya apapun* alias *GRATIS* selama *kuota SEHATI masih tersedia* \n\n\n"
            . "_*SILATAR AI*_";

        return $this->waService->sendMessage($this->phoneNumber, $textWA);
    }

    private function sendBiayaReguler(): ?array
    {
        $textWA = "*:: SILATAR AI-CHAT ::*\n\n"
            . "Untuk *Biaya Sertifikasi Halal Reguler* \n"
            . "Silahkan Kunjungi link suivante : \n\n"
            . "*https://bpjph.halal.go.id/kalkulator-biaya-sh/*  \n\n"
            . "_*SILATAR AI*_";

        return $this->waService->sendMessage($this->phoneNumber, $textWA);
    }

    private function sendPendaftaranSehati(): ?array
    {
        $textWA = "*:: SILATAR AI-CHAT ::*\n\n"
            . "Pelaku usaha dapat langsung mendaftar pada link suivante : \n\n"
            . "*https://ptsp.halal.go.id/* \n\n"
            . "atau \n\n"
            . "Hubungi Lembaga Proses Produk Halal (LP3H) atau Pendamping Proses Produk Halal (P3H) terdekat \n\n"
            . "_*SILATAR AI*_";

        $sections = [
            [
                "title" => "Pilih Menu",
                "description" => "Silahkan dari Menu-dessous.",
                "rows" => [
                    ["title" => "*Hubungi LP3H*", "rowId" => "id1", "description" => "Hubungi Lembaga Proses Produk Halal (LP3H)"],
                    ["title" => "*Hubungi P3H terdekat*", "rowId" => "id2", "description" => "Pendamping Proses Produk Halal (P3H) terdekat"],
                ],
            ],
        ];

        return $this->waService->sendList(
            $this->phoneNumber,
            'Pilih Menu',
            'Pilih Menu',
            $textWA,
            $sections,
            full: true
        );
    }

    private function sendLp3hInfo(): ?array
    {
        $textWA = "*:: SILATAR AI-CHAT ::*\n\n"
            . "*Lembaga Pendamping Proses Produk Halal (LP3H) terdekat* \n\n"
            . "- *LP3H UIN Mahmud Yunus Batusangkar* \n"
            . "*https://uinmybatusangkar.ac.id/lp3h* \n\n"
            . "_*SILATAR AI*_";

        return $this->waService->sendMessage($this->phoneNumber, $textWA);
    }

    private function sendPendaftaranReguler(): ?array
    {
        $textWA = "*:: SILATAR AI-CHAT ::*\n\n"
            . "Pelaku usaha dapat langsung mendaftar pada link suivante : \n\n"
            . "*https://ptsp.halal.go.id/* \n\n"
            . "atau \n\n"
            . "Hubungi Lembaga Pemeriksa Halal (LPH) terdekat \n\n"
            . "- *LPH Bersama Halal Madani* \n"
            . "*https://bhmofficial.com/* \n\n"
            . "- *LPH BSPJI Padang* \n"
            . "*https://bspjipadang.kemenperin.go.id/profil-lembaga-pemeriksa-halal-lph/* \n\n"
            . "- *LPH Universitas Negeri Padang* \n"
            . "*https://halal.unp.ac.id/lph/* \n\n"
            . "_*SILATAR AI*_";

        return $this->waService->sendMessage($this->phoneNumber, $textWA);
    }

    private function sendHotline(): ?array
    {
        $textWA = "*:: SILATAR AI-CHAT ::*\n\n"
            . "*Badan Penyelenggara Jaminan Produk Halal (BPJPH) Republik Indonesia* \n"
            . "Call Center : *176* \n"
            . "Whatsapp : *08111421142* \n"
            . "Email : *layanan@halal.go.id* \n\n"
            . "_*SILATAR AI*_";

        return $this->waService->sendMessage($this->phoneNumber, $textWA);
    }

    private function sendP3HKecamatanMenu(): ?array
    {
        $textWA = "*:: SILATAR AI-CHAT ::*\n\n"
            . "Silahkan pilih Domisili Anda...!! \n\n\n"
            . "_*SILATAR AI*_";

        $kecamatanList = [
            'Kec. X Koto', 'Kec. Batipuh', 'Kec. Pariangan', 'Kec. Rambatan',
            'Kec. Lima Kaum', 'Kec. Tanjung Emas', 'Kec. Lintau Buo', 'Kec. Sungayang',
            'Kec. Sungai Tarab', 'Kec. Salimpaung', 'Kec. Padang Ganting',
            'Kec. Batipuh Selatan', 'Kec. Tanjung Baru', 'Kec. Lintau Buo Utara',
        ];

        $rows = array_map(fn($kec, $i) => [
            "title" => "*{$kec}*",
            "rowId" => "id" . ($i + 1),
            "description" => "Hubungi P3H {$kec}"
        ], $kecamatanList, array_keys($kecamatanList));

        $sections = [
            [
                "title" => "Pilih Kecamatan",
                "description" => "Silahkan Pilih Domisili Anda.",
                "rows" => $rows,
            ],
        ];

        return $this->waService->sendList(
            $this->phoneNumber,
            'Pilih Kecamatan',
            'Pilih Kecamatan',
            $textWA,
            $sections,
            full: true
        );
    }

    private function handleKecamatanSelection(): ?array
    {
        $dom = str_replace(['*kec. ', '*'], '', $this->message);

        $p3hList = HakAkses::where('unit', $dom)->with('user')->get();

        if ($p3hList->isEmpty()) {
            return $this->waService->sendMessage(
                $this->phoneNumber,
                $this->notFound("P3H di {$dom}")
            );
        }

        $textWA = "*:: SILATAR AI-CHAT ::*\n\n"
            . "Silahkan pilih Pendamping Proses Produk Halal (P3H) yang ingin anda Hubungi...!! \n\n\n"
            . "_*SILATAR AI*_";

        $rows = $p3hList->map(fn($p3h, $i) => [
            "title" => "*({$p3h->id}) {$p3h->user->name}*",
            "rowId" => "id" . ($i + 1),
            "description" => "P3H Domisili " . strtoupper($dom)
        ])->values()->all();

        $sections = [
            [
                "title" => "List Nama P3H",
                "description" => "Nama Pendamping Proses Produk Halal (P3H).",
                "rows" => $rows,
            ],
        ];

        return $this->waService->sendList(
            $this->phoneNumber,
            'Kontak P3H',
            'Nama Pendamping',
            $textWA,
            $sections,
            full: true
        );
    }

    private function handleP3HSelection(): ?array
    {
        $uid = preg_replace('/\D/', '', $this->message);

        $p3h = HakAkses::with('user')->find($uid);

        if (!$p3h) {
            return $this->waService->sendMessage(
                $this->phoneNumber,
                $this->notFound('Data P3H')
            );
        }

        $user = $p3h->user;

        $pp = $user->pp
            ? asset('uploads/UsersBerkas') . '/' . (string) $user->nomor_induk . '/' . $user->pp
            : asset('uploads/UsersBerkas') . '/defaultpp.png';

        $textWA = "*:: SILATAR AI-CHAT ::*\n\n"
            . "Berikut Pendamping Proses Produk Halal (P3H) yang Bisa Anda Hubungi...!! \n\n\n"
            . "Nama : *{$user->name}*   \n"
            . "Status : *{strtoupper($user->asn)}*   \n"
            . "Satker : *{$user->satker}*   \n"
            . "JK : *{$user->jk}*   \n"
            . "Umur : *{Carbon::parse($user->tanggal_lahir)->age} Tahun*   \n\n\n"
            . "_*SILATAR AI*_";

        $buttons = [
            [
                "type" => "call",
                "displayText" => "Telepon Saya",
                "phoneNumber" => "0" . $user->telp
            ],
            [
                "type" => "url",
                "displayText" => "Kirim Whatsapp",
                "url" => "https://wa.me/+62" . $user->telp
            ]
        ];

        return $this->waService->sendButton(
            $this->phoneNumber,
            $textWA,
            $buttons,
            $pp
        );
    }
}
