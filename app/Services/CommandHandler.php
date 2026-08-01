<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Commands\Webhook\{
    BaseCommand,
    TestCommand,
    CekNipCommand,
    CekAsnCommand,
    CekPtspCommand,
    CekSatkerCommand,
    MenuLayananCommand,
    ReqLayananCommand,
    SetWhatsappCommand,
    HelpCommand,
    LupaPasswordCommand,
    CetakSlipGajiCommand,
    HalalCommand,
    P3HKecamatanCommand
};
use Illuminate\Support\Facades\Log;

class CommandHandler
{
    private Request $request;
    private string $phoneNumber;
    private string $message;
    private string $senderName;
    private string $device;
    private ?string $ppUrl;
    private ?string $participant;
    private ?array $media;
    private ?string $mimetype;
    private array $parts;
    private WhatsAppService $waService;

    public function __construct(Request $request, WhatsAppService $waService)
    {
        $this->request = $request;
        $this->phoneNumber = $request->from;
        $this->message = strtolower(trim($request->message ?? ''));
        $this->senderName = $request->name ?? 'Unknown';
        $this->device = $request->device ?? '';
        $this->ppUrl = $request->ppUrl;
        $this->participant = $request->participant;
        $this->media = $request->media;
        $this->mimetype = $request->mimetype;
        $this->waService = $waService;

        // Parse message into parts
        $this->parts = $this->parseMessage($this->message);
    }

    /**
     * Get sender phone number
     */
    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }

    /**
     * Get sender name
     */
    public function getSenderName(): string
    {
        return $this->senderName;
    }

    /**
     * Get device info
     */
    public function getDevice(): string
    {
        return $this->device;
    }

    /**
     * Get profile picture URL
     */
    public function getPpUrl(): ?string
    {
        return $this->ppUrl;
    }

    /**
     * Check if message is from a group
     * Group JID format: xxxxxxxxx@g.us
     * Personal JID format: xxxxxxxxx@s.whatsapp.net
     */
    public function isGroupMessage(): bool
    {
        if (empty($this->participant)) {
            return false;
        }

        // Check if participant is a group (contains @g.us)
        if (str_contains($this->participant, '@g.us')) {
            return true;
        }

        // If participant contains @s.whatsapp.net but equals sender, it's not a group
        if (str_contains($this->participant, '@s.whatsapp.net')) {
            return false;
        }

        return false;
    }

    /**
     * Check if participant is the sender's own JID (not a group)
     */
    public function isOwnJid(): bool
    {
        if (empty($this->participant)) {
            return false;
        }

        // Extract number from participant JID (remove @s.whatsapp.net or @g.us)
        $participantNumber = preg_replace('/@(s\.whatsapp\.net|g\.us)/', '', $this->participant);

        // Compare with sender number (normalize both)
        $normalizedParticipant = preg_replace('/[^0-9]/', '', $participantNumber);
        $normalizedSender = preg_replace('/[^0-9]/', '', $this->phoneNumber);

        return $normalizedParticipant === $normalizedSender;
    }

    /**
     * Get media data if any
     */
    public function getMedia(): ?array
    {
        return $this->media;
    }

    /**
     * Get raw request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * Parse message into command parts
     */
    private function parseMessage(string $message): array
    {
        // Remove asterisks and normalize whitespace
        $cleaned = preg_replace("/[[:blank:]]+/", " ", trim($message, " *"));

        return explode(' ', $cleaned);
    }

    /**
     * Get the command word (first part)
     */
    private function getCommand(): ?string
    {
        return $this->parts[0] ?? null;
    }

    /**
     * Get the subcommand (second part)
     */
    private function getSubCommand(): ?string
    {
        return $this->parts[1] ?? null;
    }

    /**
     * Get all arguments (parts after command and subcommand)
     */
    private function getArgs(): array
    {
        return array_slice($this->parts, 2);
    }

    /**
     * Get the raw argument string (for multi-word args)
     */
    private function getRawArg(): ?string
    {
        $args = $this->getArgs();
        return !empty($args) ? implode(' ', $args) : null;
    }

    /**
     * Main handler - route to appropriate command
     */
    public function handle(): ?array
    {
        $this->logIncoming();

        // Skip group messages (only handle direct messages)
        if ($this->isGroupMessage()) {
            Log::channel('whatsapp')->debug('Skipping group message', [
                'from' => $this->phoneNumber,
                'participant' => $this->participant,
            ]);
            return null;
        }

        // WA server sends 2 requests: one with participant (own JID), one without
        // If participant == sender, use the request WITHOUT participant (cleaner)
        if ($this->isOwnJid()) {
            Log::channel('whatsapp')->debug('Skipping duplicate (own JID), using request without participant', [
                'from' => $this->phoneNumber,
                'participant' => $this->participant,
            ]);
            return null;
        }

        // Test webhook
        if ($this->message === 'test webhook') {
            return (new TestCommand($this->phoneNumber, $this->message, $this->waService))->execute();
        }

        // Cek commands: cek nip <NIP>, cek asn <nama>, cek ptsp <no_req>, cek satker <nama>
        if ($this->getCommand() === 'cek') {
            return $this->handleCekCommand();
        }

        // Menu commands: menu layanan <satker>
        if ($this->getCommand() === 'menu') {
            return $this->handleMenuCommand();
        }

        // Request commands: req layanan <nama>
        if ($this->getCommand() === 'req') {
            return $this->handleReqCommand();
        }

        // Set commands: set whatsapp <nip>
        if ($this->getCommand() === 'set') {
            return $this->handleSetCommand();
        }

        // Help/greeting commands
        if ($this->isGreeting()) {
            return (new HelpCommand($this->phoneNumber, $this->message, $this->waService))->execute();
        }

        // Lupa password
        if ($this->message === '*lupa password silatar*') {
            return (new LupaPasswordCommand($this->phoneNumber, $this->message, $this->waService))->execute();
        }

        // Cetak slip gaji
        if ($this->message === '*cetak slip gaji*') {
            return (new CetakSlipGajiCommand($this->phoneNumber, $this->message, $this->waService))->execute();
        }

        // Download dokumen amprah
        if ($this->message === '*download dokumen amprah*') {
            return $this->sendLink(
                'Dokumen Amprah',
                'https://kemenagtanahdatar.cloud/v2/amprah',
                'Untuk Mendownload Dokumen Amprah Anda di SILATAR (Anda harus Login terlebih dahulu)'
            );
        }

        // Ganti nomor whatsapp
        if ($this->message === '*ganti nomor whatsapp*') {
            return $this->sendGuide(
                'Ganti Nomor Whatsapp',
                'Silahkan ketik',
                '*set whatsapp <NIP Anda>*',
                'contoh : *set whatsapp 197811032007031001*'
            );
        }

        // Cek data ASN guides
        if ($this->message === '*cek data asn (berdasarkan nama)*') {
            return $this->sendGuide(
                'Cek Data ASN (Berdasarkan Nama)',
                'Silahkan ketik',
                '*cek ASN <Nama ASN>*',
                'contoh : *cek ASN Anggi Pratama*'
            );
        }

        if ($this->message === '*cek data asn (berdasarkan nip)*') {
            return $this->sendGuide(
                'Cek Data ASN (Berdasarkan NIP)',
                'Silahkan ketik',
                '*cek NIP <NIP ASN>*',
                'contoh : *cek NIP 197811032007031001*'
            );
        }

        if ($this->message === '*cek data unit kerja*') {
            return $this->sendGuide(
                'Cek Data Unit Kerja',
                'Silahkan ketik',
                '*cek satker <Nama Unit Kerja>*',
                'contoh : *cek Satker MAN 1*'
            );
        }

        // Buat janji temu
        if ($this->message === '*buat janji temu (appointment)*') {
            return $this->sendLink(
                'Buat Janji Temu',
                'https://kemenagtanahdatar.cloud/v2/bukutamu',
                'Untuk Membuat Janji Temu/Appointment di SILATAR dengan Staff yang di Kantor'
            );
        }

        // Konsultasi
        if ($this->message === '*konsultasi ke staff kantor*') {
            return $this->sendLink(
                'Konsultasi Staff Kantor',
                'https://kemenagtanahdatar.cloud/v2/UnitKerja',
                'Jika Anda ingin Berkonsultasi di SILATAR dengan Staff yang di Kantor (Anda harus Login terlebih dahulu)'
            );
        }

        // Rating
        if ($this->message === '*kasih penilaian, saran, dan kritik*') {
            return $this->sendLink(
                'Penilaian, Saran, dan Kritik',
                'https://kemenagtanahdatar.cloud/v2/rateUs',
                'Mohon Berikan Kami Penilaian, Saran, ataupun Kritik ke Kami Supaya Kami bisa menjadi lebih baik lagi'
            );
        }

        // Halal commands
        if ($this->isHalalCommand()) {
            return $this->handleHalalCommand();
        }

        // Kecamatan command
        if (str_starts_with($this->message, '*kec.')) {
            return (new P3HKecamatanCommand($this->phoneNumber, $this->message, $this->waService))->execute();
        }

        // P3H ID selection
        if (preg_match('/^\(\d+\)$/', $this->getCommand())) {
            return $this->handleP3HSelection();
        }

        // Unknown command
        return $this->sendUnknownCommand();
    }

    /**
     * Handle cek command variants
     */
    private function handleCekCommand(): ?array
    {
        $subCommand = $this->getSubCommand();

        return match ($subCommand) {
            'nip' => (new CekNipCommand($this->phoneNumber, $this->getRawArg(), $this->waService))->execute(),
            'asn' => (new CekAsnCommand($this->phoneNumber, $this->getRawArg(), $this->waService))->execute(),
            'ptsp' => (new CekPtspCommand($this->phoneNumber, $this->getRawArg(), $this->waService))->execute(),
            'satker' => (new CekSatkerCommand($this->phoneNumber, $this->getRawArg(), $this->waService))->execute(),
            default => $this->sendUnknownCommand(),
        };
    }

    /**
     * Handle menu command variants
     */
    private function handleMenuCommand(): ?array
    {
        if ($this->getSubCommand() === 'layanan') {
            return (new MenuLayananCommand($this->phoneNumber, $this->getRawArg(), $this->waService))->execute();
        }

        return $this->sendUnknownCommand();
    }

    /**
     * Handle req command variants
     */
    private function handleReqCommand(): ?array
    {
        if ($this->getSubCommand() === 'layanan') {
            return (new ReqLayananCommand($this->phoneNumber, $this->getRawArg(), $this->waService))->execute();
        }

        return $this->sendUnknownCommand();
    }

    /**
     * Handle set command variants
     */
    private function handleSetCommand(): ?array
    {
        if ($this->getSubCommand() === 'whatsapp') {
            return (new SetWhatsappCommand($this->phoneNumber, $this->getRawArg(), $this->request, $this->waService))->execute();
        }

        return $this->sendUnknownCommand();
    }

    /**
     * Handle halal command variants
     */
    private function handleHalalCommand(): ?array
    {
        return (new HalalCommand($this->phoneNumber, $this->message, $this->waService))->execute();
    }

    /**
     * Handle P3H selection by ID
     */
    private function handleP3HSelection(): ?array
    {
        // This is handled by HalalCommand for now
        return (new HalalCommand($this->phoneNumber, $this->message, $this->waService))->execute();
    }

    /**
     * Check if message is a greeting
     */
    private function isGreeting(): bool
    {
        $greetings = ['halo', 'hai', 'assalamualaikum', 'hello', 'asw'];
        return in_array($this->message, $greetings) ||
               str_starts_with($this->message, 'halo') ||
               str_starts_with($this->message, 'hai') ||
               str_starts_with($this->message, 'assalamualaikum') ||
               str_starts_with($this->message, 'hello') ||
               str_starts_with($this->message, 'asw');
    }

    /**
     * Check if message is a halal-related command
     */
    private function isHalalCommand(): bool
    {
        $halalKeywords = [
            '*layanan sertifikasi halal*',
            '*usaha mikro dan kecil*',
            '*usaha non mikro*',
            '*alur pendaftaran*',
            '*dokumen persyaratan*',
            '*biaya sertifikasi*',
            '*pendaftaran sertifikasi*',
            '*hubungi lembaga*',
            '*hotline sertifikasi*',
            '*hubungi pendamping*',
        ];

        foreach ($halalKeywords as $keyword) {
            if (str_contains($this->message, str_replace('*', '', $keyword))) {
                return true;
            }
        }

        return false;
    }

    /**
     * Send link message
     */
    private function sendLink(string $title, string $url, string $description): ?array
    {
        $textWA = $this->formatMessage($title, $description, $url);
        return $this->waService->sendMessage($this->phoneNumber, $textWA);
    }

    /**
     * Send guide message
     */
    private function sendGuide(string $title, string $intro, string $command, string $example): ?array
    {
        $textWA = $this->formatGuideMessage($title, $intro, $command, $example);
        return $this->waService->sendMessage($this->phoneNumber, $textWA);
    }

    /**
     * Send unknown command response
     */
    private function sendUnknownCommand(): ?array
    {
        $textWA = "*:: SILATAR CHAT ::*\n\n"
            . "*Maaf, perintah yang Anda masukkan tidak dikenali*\n\n"
            . "Silahkan ketik *halo* untuk melihat menu layanan\n\n\n"
            . "_Hormat Kami,_\n\n"
            . "_*SILATAR AI*_";

        return $this->waService->sendMessage($this->phoneNumber, $textWA);
    }

    /**
     * Format message with link
     */
    private function formatMessage(string $title, string $description, string $url): string
    {
        return "*:: SILATAR AI-CHAT ::*\n\n"
            . $description . "\n\n"
            . "Silahkan klik Link-dessous ini : \n\n"
            . "*{$url}* \n\n\n"
            . "_Hormat Kami,_\n\n"
            . "_*SILATAR AI*_";
    }

    /**
     * Format guide message
     */
    private function formatGuideMessage(string $title, string $intro, string $command, string $example): string
    {
        return "*:: SILATAR AI-CHAT ::*\n\n"
            . $intro . " \n {$command} \n"
            . "contoh : \n {$example} \n\n\n"
            . "_Hormat Kami,_\n\n"
            . "_*SILATAR AI*_";
    }

    /**
     * Log incoming webhook
     */
    private function logIncoming(): void
    {
        // Logging is done in WebhookController, no need to duplicate here
    }
}
