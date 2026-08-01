<?php

namespace App\Commands\Webhook;

use App\Models\Department;
use App\Models\Layanan;
use App\Services\WhatsAppService;

class MenuLayananCommand extends BaseCommand
{
    public function execute(): ?array
    {
        $name = $this->message;

        if (empty($name)) {
            return $this->waService->sendMessage(
                $this->phoneNumber,
                $this->validationError('Mohon masukkan nama Unit Kerja')
            );
        }

        $searchName = WhatsAppService::escapeLikeQuery($name);

        $satker = Department::where('nama', 'LIKE', '%' . $searchName . '%')
            ->orWhere('kode', 'LIKE', '%' . $searchName . '%')
            ->first();

        if (!$satker) {
            return $this->waService->sendMessage(
                $this->phoneNumber,
                $this->notFound('Unit Kerja')
            );
        }

        $layanan = Layanan::where('dept_id', (string) $satker->id)->get();

        if ($layanan->isEmpty()) {
            return $this->waService->sendMessage(
                $this->phoneNumber,
                "*:: SILATAR CHAT ::*\n\n"
                    . "*Mohon Maaf, Daftar Layanan pada {$satker->nama} masih kosong / belum diinput*\n\n\n"
                    . "_Hormat Kami,_\n\n"
                    . "_*SILATAR AI*_"
            );
        }

        $listItems = $layanan->pluck('nama')->map(fn($item, $index) => ($index + 1) . ". " . $item)->implode("\n");

        $textWA = "*:: SILATAR CHAT ::*\n\n"
            . "Daftar Layanan pada *{$satker->nama}*\n"
            . $listItems . "\n\n\n"
            . "_Hormat Kami,_\n\n"
            . "_*SILATAR AI*_";

        return $this->waService->sendMessage($this->phoneNumber, $textWA);
    }
}
