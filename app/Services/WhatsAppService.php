<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    private string $apiKey;
    private string $sender;
    private string $waServerUrl;
    private string $defaultFooter;

    public function __construct()
    {
        $this->apiKey = env('WA_TOKEN');
        $this->sender = env('WA_NUMBER');
        $this->waServerUrl = env('URL_WA_SERVER');
        $this->defaultFooter = '© ' . date('Y') . ' SILATAR AI (Reply Otomatis)';
    }

    /**
     * Send a text message via WhatsApp
     */
    public function sendMessage(string $number, string $message, ?string $footer = null): ?array
    {
        try {
            $response = Http::post($this->waServerUrl . "/send-message", [
                "api_key" => $this->apiKey,
                "sender" => $this->sender,
                "number" => $number,
                "message" => $message,
                "footer" => $footer ?? $this->defaultFooter,
            ]);

            $this->logResponse('send-message', $response);

            return $response->successful() ? $response->json() : null;
        } catch (\Exception $e) {
            $this->logError('send-message', $e);
            return null;
        }
    }

    /**
     * Send a media/document message via WhatsApp
     */
    public function sendMedia(
        string $number,
        string $url,
        string $caption,
        string $mediaType = 'document',
        ?string $footer = null
    ): ?array {
        try {
            $response = Http::post($this->waServerUrl . "/send-media", [
                "api_key" => $this->apiKey,
                "sender" => $this->sender,
                "number" => $number,
                "media_type" => $mediaType,
                "caption" => $caption,
                "footer" => $footer ?? $this->defaultFooter,
                "url" => $url,
            ]);

            $this->logResponse('send-media', $response);

            return $response->successful() ? $response->json() : null;
        } catch (\Exception $e) {
            $this->logError('send-media', $e);
            return null;
        }
    }

    /**
     * Send interactive list message via WhatsApp
     */
    public function sendList(
        string $number,
        string $title,
        string $buttonText,
        string $message,
        array $sections,
        bool $full = false,
        ?string $footer = null
    ): ?array {
        try {
            // Default image URL untuk list message
            $defaultImage = 'https://sms.kemenagtanahdatar.id/themes/vuexy/img/front-pages/landing-page/hero-elements-light.png';

            $payload = [
                "api_key" => $this->apiKey,
                "sender" => $this->sender,
                "number" => $number,
                "name" => strtoupper(str_replace(' ', '_', $title)),
                "title" => $title,
                "buttontext" => $buttonText,
                "message" => $message,
                "footer" => $footer ?? $this->defaultFooter,
                "image" => $defaultImage,
                "sections" => $sections,
            ];

            if ($full) {
                $payload['full'] = 1;
            }

            $response = Http::post($this->waServerUrl . "/send-list", $payload);

            $this->logResponse('send-list', $response);

            return $response->successful() ? $response->json() : null;
        } catch (\Exception $e) {
            $this->logError('send-list', $e);
            return null;
        }
    }

    /**
     * Send button message via WhatsApp
     */
    public function sendButton(
        string $number,
        string $message,
        array $buttons,
        ?string $imageUrl = null,
        ?string $footer = null
    ): ?array {
        try {
            $payload = [
                "api_key" => $this->apiKey,
                "sender" => $this->sender,
                "number" => $number,
                "message" => $message,
                "footer" => $footer ?? $this->defaultFooter,
                "button" => $buttons,
            ];

            if ($imageUrl) {
                $payload['url'] = $imageUrl;
            }

            $response = Http::post($this->waServerUrl . "/send-button", $payload);

            $this->logResponse('send-button', $response);

            return $response->successful() ? $response->json() : null;
        } catch (\Exception $e) {
            $this->logError('send-button', $e);
            return null;
        }
    }

    /**
     * Normalize phone number to Indonesian format (without country code prefix)
     */
    public static function normalizePhoneNumber(string $phone): string
    {
        // Remove all non-digit characters
        $phone = preg_replace('/\D/', '', $phone);

        // Remove leading zeros
        if (str_starts_with($phone, '62')) {
            return substr($phone, 2);
        }

        if (str_starts_with($phone, '0')) {
            return substr($phone, 1);
        }

        return $phone;
    }

    /**
     * Format phone number for display (with spaces every 4 digits)
     */
    public static function formatPhoneForDisplay(string $phone): string
    {
        return preg_replace('/(?<=\d)(?=(\d{4})+$)/', ' ', $phone);
    }

    /**
     * Escape special characters for LIKE queries
     */
    public static function escapeLikeQuery(string $value): string
    {
        return str_replace(['%', '_', '\\'], ['\\%', '\\_', '\\\\'], $value);
    }

    /**
     * Log successful API response
     */
    private function logResponse(string $method, $response): void
    {
        Log::channel('whatsapp')->info("WA API {$method} success", [
            'status' => $response->status(),
            'body' => $response->json() ?? $response->body(),
        ]);
    }

    /**
     * Log API error
     */
    private function logError(string $method, \Exception $e): void
    {
        Log::channel('whatsapp')->error("WA API {$method} failed", [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);
    }
}
