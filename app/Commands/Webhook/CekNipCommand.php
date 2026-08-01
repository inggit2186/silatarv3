<?php

namespace App\Commands\Webhook;

use App\Models\User;

class CekNipCommand extends BaseCommand
{
    public function execute(): ?array
    {
        $nip = $this->message;

        if (empty($nip)) {
            return $this->waService->sendMessage(
                $this->phoneNumber,
                $this->validationError('Mohon masukkan NIP yang valid')
            );
        }

        $user = User::where('nomor_induk', (string) $nip)
            ->with('dept')
            ->first();

        if (!$user) {
            return $this->waService->sendMessage(
                $this->phoneNumber,
                $this->notFound('Data dengan NIP tersebut')
            );
        }

        $textWA = $this->formatUserProfile([
            'nomor_induk' => $user->nomor_induk,
            'name' => $user->name,
            'pekerjaan' => $user->pekerjaan,
            'dept' => $user->dept->nama ?? '-',
            'alamat' => $user->alamat ?? '-',
            'email' => $user->email ?? '-',
        ]);

        return $this->waService->sendMessage($this->phoneNumber, $textWA);
    }
}
