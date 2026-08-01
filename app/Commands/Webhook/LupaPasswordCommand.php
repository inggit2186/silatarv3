<?php

namespace App\Commands\Webhook;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;

class LupaPasswordCommand extends BaseCommand
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
                    . "Silahkan Hubungi Admin di Subbagian Tata Usaha Kankemenag Kab.Tanah Datar \n\n\n"
                    . "_Hormat Kami,_\n\n"
                    . "_*SILATAR AI*_"
            );
        }

        // Generate new password
        $tanggalLahir = Carbon::parse($user->tanggal_lahir)->format('d-m-y');
        $xdate = preg_replace('/[^0-9]/', '', $tanggalLahir);
        $newPass = 'ASN' . substr($user->nomor_induk, -3) . $xdate;

        // Update password
        User::where('id', $user->id)->update([
            'password' => bcrypt($newPass),
        ]);

        $textWA = "*:: SILATAR Notifikasi ::*\n\n"
            . "*Assalamualaikum Wr. Wb.*\n"
            . "Yth. Bpk/Ibu *{$user->name}*\n"
            . "Berikut Data Baru Akun SILATAR Bpk/Ibu \n\n"
            . " eMail : {$user->email}\n"
            . " NIP : *{$user->nomor_induk}* \n"
            . " Password : *{$newPass}* \n\n\n"
            . "_Tertanda_\n\n"
            . "*_SILATAR AI_*";

        return $this->waService->sendMessage($this->phoneNumber, $textWA);
    }
}
