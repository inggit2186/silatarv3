<?php

namespace App\Commands\Webhook;

use App\Services\WhatsAppService;

abstract class BaseCommand
{
    protected string $phoneNumber;
    protected string $message;
    protected WhatsAppService $waService;

    public function __construct(string $phoneNumber, string $message, WhatsAppService $waService)
    {
        $this->phoneNumber = $phoneNumber;
        $this->message = $message;
        $this->waService = $waService;
    }

    abstract public function execute(): ?array;

    /**
     * Format user profile message
     */
    protected function formatUserProfile(array $user): string
    {
        return "*:: SILATAR AI-CHAT ::*\n\n"
            . "NIP : *{$user['nomor_induk']}* \n"
            . "Nama : *{$user['name']}* \n\n"
            . "Jabatan : *{$user['pekerjaan']}* \n"
            . "Unit Kerja : *{$user['dept']}* \n\n"
            . "Alamat : *{$user['alamat']}* \n\n"
            . "Email : *{$user['email']}* \n"
            . "Kontak : _<Hidden>_ \n\n\n"
            . "_Hormat Kami,_\n\n"
            . "_*SILATAR AI*_";
    }

    /**
     * Format not found message
     */
    protected function notFound(string $context = 'Data'): string
    {
        return "*:: SILATAR CHAT ::*\n\n"
            . "*Mohon Maaf, {$context} Tidak Ditemukan*\n\n\n"
            . "_Hormat Kami,_\n\n"
            . "_*SILATAR AI*_";
    }

    /**
     * Format validation error message
     */
    protected function validationError(string $message): string
    {
        return "*:: SILATAR CHAT ::*\n\n"
            . "*{$message}*\n\n\n"
            . "_Hormat Kami,_\n\n"
            . "_*SILATAR AI*_";
    }
}
