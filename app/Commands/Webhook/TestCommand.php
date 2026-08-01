<?php

namespace App\Commands\Webhook;

use App\Services\WhatsAppService;

class TestCommand extends BaseCommand
{
    public function execute(): ?array
    {
        return $this->waService->sendMessage(
            $this->phoneNumber,
            $this->message
        );
    }
}
