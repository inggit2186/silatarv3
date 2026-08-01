<?php

namespace App\Commands\Webhook;

use App\Models\User;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;

class SetWhatsappCommand extends BaseCommand
{
    private Request $request;

    public function __construct(
        string $phoneNumber,
        string $message,
        Request $request,
        WhatsAppService $waService
    ) {
        parent::__construct($phoneNumber, $message, $waService);
        $this->request = $request;
    }

    public function execute(): ?array
    {
        $nip = $this->message;
        $number = WhatsAppService::normalizePhoneNumber($this->phoneNumber);
        $telpFormatted = WhatsAppService::formatPhoneForDisplay($number);

        $user = User::where('nomor_induk', (string) $nip)->first();

        if (!$user) {
            return $this->waService->sendMessage(
                $this->phoneNumber,
                "*:: SILATAR AI-CHAT ::*\n\n"
                    . "*Mohon Maaf* \n"
                    . "NIP Anda tidak ditemukan didatabase kami\n"
                    . "Silahkan Hubungi bagian Kepegawaian untuk informasi lebih lanjut \n\n\n"
                    . "_Hormat Kami,_\n\n"
                    . "_*SILATAR AI*_"
            );
        }

        // Update user's phone number
        User::where('nomor_induk', (string) $nip)->update([
            'telp' => $number,
        ]);

        $textWA = "*:: SILATAR AI-CHAT ::*\n\n"
            . "*Terima Kasih dan Selamat Datang*\n"
            . "Bpk/Ibu {$user->name}\n\n"
            . "Nomor Whatsapp baru Anda telah didaftarkan\n"
            . "Reset password baru anda akan dikirim ke \n"
            . "*Nomor : 0{$telpFormatted}* \n\n\n"
            . "_Hormat Kami,_\n\n"
            . "_*SILATAR AI*_";

        return $this->waService->sendMessage($this->phoneNumber, $textWA);
    }
}
