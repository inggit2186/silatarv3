<?php

namespace App\Commands\Webhook;

use App\Models\UsersRequest;
use App\Services\WhatsAppService;
use Carbon\Carbon;

class CekPtspCommand extends BaseCommand
{
    public function execute(): ?array
    {
        $noReq = $this->message;

        if (empty($noReq)) {
            return $this->waService->sendMessage(
                $this->phoneNumber,
                $this->validationError('Mohon masukkan Nomor Registrasi PTSP yang valid')
            );
        }

        $request = UsersRequest::where('no_req', $noReq)
            ->with(['user', 'bp'])
            ->first();

        if (!$request) {
            $textWA = $this->notFound('Data PTSP')
                . "\nMohon Periksa Kembali Nomor Registrasi PTSP Anda";

            return $this->waService->sendMessage($this->phoneNumber, $textWA);
        }

        // Determine sender name based on category
        $sender = $request->kategori === 'Personal'
            ? ($request->user->name ?? $request->user_id)
            : ($request->bp->instansi ?? $request->user_id);

        $tglsurat = Carbon::parse($request->tgl_surat)->translatedFormat('d F Y');

        $textWA = "*:: SILATAR AI-CHAT ::*\n\n"
            . "```NoReq: {$request->no_req}```\n\n"
            . "*No Surat: {$request->no_surat}*\n"
            . "*Tanggal Surat: {$tglsurat}*\n\n"
            . "_From: *{$sender}*_\n"
            . "_About: *{$request->judul}*_\n"
            . "_Desc: *{$request->deskripsi}*_\n\n"
            . "_Status: *{$request->status}*_\n\n\n"
            . "_Hormat Kami,_\n\n"
            . "_*SILATAR AI*_";

        return $this->waService->sendMessage($this->phoneNumber, $textWA);
    }
}
