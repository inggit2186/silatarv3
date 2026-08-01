<?php

namespace App\Commands\Webhook;

use App\Models\User;

class CekAsnCommand extends BaseCommand
{
    private const MIN_NAME_LENGTH = 3;

    public function execute(): ?array
    {
        $name = $this->message;

        if (strlen($name) < self::MIN_NAME_LENGTH) {
            return $this->waService->sendMessage(
                $this->phoneNumber,
                $this->validationError('Mohon Masukkan Nama minimal 3 Huruf')
            );
        }

        // Escape special LIKE characters to prevent unexpected results
        $searchName = WhatsAppService::escapeLikeQuery($name);

        $users = User::where('name', 'LIKE', '%' . $searchName . '%')
            ->with('dept')
            ->get();

        if ($users->isEmpty()) {
            return $this->waService->sendMessage(
                $this->phoneNumber,
                $this->notFound('Data ASN dengan nama tersebut')
            );
        }

        // Send message for each user found
        foreach ($users as $user) {
            $textWA = $this->formatUserProfile([
                'nomor_induk' => $user->nomor_induk,
                'name' => $user->name,
                'pekerjaan' => $user->pekerjaan,
                'dept' => $user->dept->nama ?? '-',
                'alamat' => $user->alamat ?? '-',
                'email' => $user->email ?? '-',
            ]);

            $this->waService->sendMessage($this->phoneNumber, $textWA);
        }

        return null;
    }
}
