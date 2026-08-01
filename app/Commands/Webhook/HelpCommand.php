<?php

namespace App\Commands\Webhook;

class HelpCommand extends BaseCommand
{
    public function execute(): ?array
    {
        $textWA = "*:: SILATAR AI-CHAT ::*\n\n"
            . "*Selamat Datang*\n"
            . "Ini adalah Whatsapp Official SILATAR AI\n"
            . "Silahkan pilih Layanan yang anda inginkan...!! \n\n\n"
            . "_*SILATAR AI*_";

        $sections = [
            [
                "title" => "Layanan SILATAR",
                "description" => "Silahkan Pilih Layanan.",
                "rows" => [
                    ["title" => "*Lupa Password SILATAR*", "rowId" => "id1", "description" => "Jika Anda Lupa Password aplikasi SILATAR Anda"],
                    ["title" => "*Cetak Slip Gaji*", "rowId" => "id2", "description" => "Jika Anda ingin Mencetak Slip Gaji Terbaru Anda"],
                    ["title" => "*Layanan Sertifikasi Halal*", "rowId" => "id3", "description" => "Lihat Menu Layanan Sertifikasi Halal"],
                    ["title" => "*Download Dokumen Amprah*", "rowId" => "id4", "description" => "Jika Anda ingin Mendownload Dokumen Amprah Terbaru Anda"],
                    ["title" => "*Ganti Nomor Whatsapp*", "rowId" => "id5", "description" => "Jika Anda Ingin mengganti Nomor Whatsapp Anda yang terdaftar di Akun SILATAR"],
                    ["title" => "*Cek Data ASN (Berdasarkan Nama)*", "rowId" => "id6", "description" => "Jika Anda Ingin melihat data ASN di Jajaran Kankemenag Tanah Datar"],
                    ["title" => "*Cek Data ASN (Berdasarkan NIP)*", "rowId" => "id7", "description" => "Jika Anda Ingin melihat data ASN di Jajaran Kankemenag Tanah Datar"],
                    ["title" => "*Cek Data Unit Kerja*", "rowId" => "id8", "description" => "Jika Anda Ingin melihat data Unit Kerja di Jajaran Kankemenag Tanah Datar"],
                    ["title" => "*Buat Janji Temu (Appointment)*", "rowId" => "id9", "description" => "Jika Anda ingin Membuat Janji Temu/Appointment dengan Staff di Kantor"],
                    ["title" => "*Konsultasi ke Staff Kantor*", "rowId" => "id10", "description" => "Jika Anda ingin Konsultasi dengan ASN atau Staff di Kantor"],
                    ["title" => "*Kasih Penilaian, Saran, dan Kritik*", "rowId" => "id11", "description" => "Mohon kasih kita Penilaian, Saran ataupun Kritik"],
                ],
            ],
        ];

        return $this->waService->sendList(
            $this->phoneNumber,
            'Layanan SILATAR',
            'Menu Layanan',
            $textWA,
            $sections
        );
    }
}
