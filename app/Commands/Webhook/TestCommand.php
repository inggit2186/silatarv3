<?php

namespace App\Commands\Webhook;

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
