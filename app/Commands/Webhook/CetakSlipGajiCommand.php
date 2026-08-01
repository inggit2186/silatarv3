<?php

namespace App\Commands\Webhook;

use App\Models\SlipGaji;
use App\Models\User;
use App\Services\WhatsAppService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CetakSlipGajiCommand extends BaseCommand
{
    public function execute(): ?array
    {
        $number = WhatsAppService::normalizePhoneNumber($this->phoneNumber);

        $user = User::where('telp', $number)->first();

        if (!$user) {
            return $this->waService->sendMessage(
                $this->phoneNumber,
                "*:: SILATAR AI-CHAT ::*\n\n"
                    . "*Mohon Maaf*\n"
                    . "Nomor Anda ini Belum terdaftar di akun SILATAR \n"
                    . "Silahkan update terlebih dahulu nomor Whatsapp Anda dengan mengklik *Ganti Nomor Whatsapp* pada daftar Layanan \n"
                    . "atau Silahkan Hubungi Admin di Subbagian Tata Usaha Kankemenag Kab.Tanah Datar \n\n\n"
                    . "_Hormat Kami,_\n\n"
                    . "_*SILATAR AI*_"
            );
        }

        $gaji = SlipGaji::where('user_nip', (string) $user->nomor_induk)
            ->orderBy('tanggal', 'DESC')
            ->first();

        if (!$gaji) {
            return $this->waService->sendMessage(
                $this->phoneNumber,
                "*:: SILATAR AI-CHAT ::*\n\n"
                    . "Nomor Terdaftar : Bpk/Ibu *{$user->name}*\n\n"
                    . "*Mohon Maaf*\n"
                    . "Slip Gaji Anda tidak ditemukan di SILATAR \n"
                    . "Silahkan Hubungi Bagian Keuangan di Subbagian Tata Usaha Kankemenag Kab.Tanah Datar\n\n\n"
                    . "_Hormat Kami,_\n\n"
                    . "_*SILATAR AI*_"
            );
        }

        $bulan = Carbon::parse($gaji->tanggal)->format('m');
        $tahun = Carbon::parse($gaji->tanggal)->format('Y');
        $bulanx = Carbon::parse($gaji->tanggal)->translatedFormat('F Y');
        $ttd = Carbon::parse($gaji->tanggal)->translatedFormat('F Y');

        // Format bank name
        $bank = match ($gaji->user->bank ?? '') {
            'KEAGAMAAN_BANK NAGARI', 'KEPENDIDIKAN_BANK NAGARI' => 'Bank Nagari',
            'KEPENDIDIKAN_BRI' => 'Bank Rakyat Indonesia (BRI)',
            'KEPENDIDIKAN_BSI' => 'Bank Syariah Indonesia (BSI)',
            default => 'Bank belum diupdate',
        };

        $data = [
            'bulan' => $bulan,
            'tahun' => $tahun,
            'bulanx' => $bulanx,
            'bank' => $bank,
            'ttd' => $ttd,
            'gaji' => $gaji,
        ];

        // Generate PDF
        $pdf = Pdf::loadView('satker.layout.slipgaji', $data);
        $flname = $user->id . '.SlipGaji-' . $bulan . "-" . $tahun . '.pdf';

        $content = $pdf->setPaper('a5', 'portrait')->setWarnings(false)->download($flname)->getOriginalContent();
        $path = (string) $user->nomor_induk . "/SlipGaji/" . $flname;

        Storage::disk('users_berkas')->put($path, $content);

        $textWA = "*:: SILATAR Notifikasi ::*\n\n"
            . "*Assalamualaikum Wr. Wb.*\n"
            . "Yth. Bpk/Ibu *{$user->name}*\n"
            . "Sesuai dengan nomor Whatsapp yang terdaftar di akun Anda*\n"
            . "Berikut File Slip Gaji Terbaru Bpk/Ibu \n"
            . "*Terima Kasih* \n\n"
            . "_Tertanda_\n\n"
            . "*_SILATAR AI_*";

        $pdfUrl = asset('uploads/UsersBerkas') . '/' . (string) $user->nomor_induk . "/SlipGaji/" . $flname;

        return $this->waService->sendMedia(
            $this->phoneNumber,
            $pdfUrl,
            $textWA,
            'document'
        );
    }
}
