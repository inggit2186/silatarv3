<?php

namespace App\Commands\Webhook;

use App\Models\Layanan;

class ReqLayananCommand extends BaseCommand
{
    public function execute(): ?array
    {
        $name = $this->message;

        if (empty($name)) {
            return $this->waService->sendMessage(
                $this->phoneNumber,
                $this->validationError('Mohon masukkan nama Layanan')
            );
        }

        $searchName = WhatsAppService::escapeLikeQuery($name);

        $layanan = Layanan::where('nama', 'LIKE', '%' . $searchName . '%')->first();

        if (!$layanan) {
            return $this->waService->sendMessage(
                $this->phoneNumber,
                $this->notFound('Layanan')
            );
        }

        $url = "https://silatar.kemenag.go.id/v2/LayananDetail/" . $layanan->dept_id . "/" . $layanan->id;

        $textWA = "*:: SILATAR AI-CHAT ::*\n\n"
            . "Layanan : *{$layanan->nama}* \n"
            . "Deskripsi : *{$layanan->deskripsi}* \n\n"
            . "Untuk membuat permintaan Layanan ini please click link-dessous ini \n"
            . "*{$url}* \n\n\n"
            . "_Hormat Kami,_\n\n"
            . "_*SILATAR AI*_";

        return $this->waService->sendMessage($this->phoneNumber, $textWA);
    }
}
