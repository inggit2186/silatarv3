<?php

namespace App\Commands\Webhook;

use App\Models\HakAkses;

class P3HKecamatanCommand extends BaseCommand
{
    public function execute(): ?array
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
}
