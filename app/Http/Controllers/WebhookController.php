<?php

namespace App\Http\Controllers;

use App\Services\CommandHandler;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    /**
     * Main webhook handler - routes all WhatsApp messages
     * Expected JSON format from WA Server:
     * {
     *     "device": "sender/device",
     *     "message": "message text",
     *     "from": "the number of the whatsapp sender",
     *     "name": "the name of the sender",
     *     "participant": "sender number if group",
     *     "ppUrl": "url profile picture sender",
     *     "media": [...],
     *     "mimetype": "image/jpeg"
     * }
     */
    public function Webhook(Request $request)
    {
        try {
            // Log ALL incoming webhook data
            $postData = $request->all();
            Log::channel('whatsapp')->info('=== WHATSAPP WEBHOOK RECEIVED ===', [
                'POST_DATA' => $postData,
                'from' => $request->from ?? 'N/A',
                'message' => $request->message ?? 'N/A',
                'name' => $request->name ?? 'N/A',
                'device' => $request->device ?? 'N/A',
                'participant' => $request->participant ?? null,
                'mimetype' => $request->mimetype ?? null,
                'timestamp' => now()->toIso8601String(),
                'ip' => $request->ip(),
            ]);

            // Validate required fields
            if (!$request->has('from') || !$request->has('message')) {
                Log::channel('whatsapp')->warning('Invalid webhook payload', [
                    'payload' => $request->all(),
                ]);
                return response()->json(['error' => 'Missing required fields'], 400);
            }

            // Skip messages from self (bot)
            $senderNumber = $request->from;
            $botNumber = env('WA_NUMBER');

            if ($senderNumber === $botNumber || $senderNumber === '+' . $botNumber || $senderNumber === '0' . substr($botNumber, 2)) {
                Log::channel('whatsapp')->debug('Skipping message from bot number', [
                    'from' => $senderNumber,
                    'bot' => $botNumber,
                ]);
                return response()->json(['status' => 'ignored', 'reason' => 'message from bot']);
            }

            $waService = new WhatsAppService();
            $handler = new CommandHandler($request, $waService);

            $result = $handler->handle();

            return response()->json([
                'status' => 'success',
                'response' => $result,
            ]);
        } catch (\Exception $e) {
            Log::channel('whatsapp')->error('Webhook processing error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'payload' => $request->except(['api_key']),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Internal server error',
            ], 500);
        }
    }

    /**
     * Generate random rating data for testing
     */
    public function randomx()
    {
        $i = 0;

        for ($i == 0; $i < 11; $i++) {
            $rand = rand(1142, 2445);
            $rating = rand(4, 5);
            $min = strtotime('2023-05-02 07:30:00');
            $max = strtotime('2023-12-20 07:30:00');

            $val = rand($min, $max);
            $date = date('Y-m-d H:i:s', $val);

            $name = \App\Models\User::where('id', $rand)->first();

            if ($name) {
                $input = [
                    "Mantap", "OK", "Pelayanannya mantap", "Bagus",
                    "Puas dengan hasilnya", "Rancak bana", "Mantap Bana",
                    "Gak tau diisi apa", "Ok mantap", "Bagus",
                    "Pelayanannya cepat", "Cepat tepat mantap", "Udah bagus",
                    "Masih ada yang bisa ditingkatkan", "OK bagus", "Bagus sekali", "..."
                ];
                $komen = array_rand($input, 1);

                \App\Models\Rating::create([
                    'nama' => $name->name,
                    'rating' => $rating,
                    'keterangan' => $input[$komen],
                    'created_at' => $date
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'ok',
        ]);
    }

    /**
     * Auto-clean expired consultation documents
     */
    public function autoCleanDoc()
    {
        // Delete pending consultations
        $konsul = \App\Models\Konsul::where('status', 'PENDING')->delete();

        // Get expired pendidikan records
        $zfile = \App\Models\Pendidikan::where('status', 99)->get();

        foreach ($zfile as $zfile) {
            $xfile = public_path('uploads/UsersBerkas/' . $zfile->file);
            if (file_exists($xfile)) {
                unlink($xfile);
            }
            $zfile->delete();
        }

        Log::info('Auto-clean documents completed', [
            'deleted_konsul' => $konsul,
            'deleted_pendidikan' => count($zfile),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Documents cleaned',
        ]);
    }

    /**
     * Verify webhook - GET request for WA Gateway verification
     */
    public function verify(Request $request)
    {
        $token = $request->get('token') ?? $request->get('hub_verify_token');
        $challenge = $request->get('challenge') ?? $request->get('hub_challenge');

        $verifyToken = env('WA_VERIFY_TOKEN');

        if ($token === $verifyToken && $challenge) {
            Log::info('WhatsApp webhook verified successfully');
            return response($challenge, 200);
        }

        Log::warning('WhatsApp webhook verification failed', [
            'token_match' => $token === $verifyToken,
            'has_challenge' => !empty($challenge),
        ]);

        return response('Forbidden', 403);
    }

    /**
     * Test webhook with dummy data - for testing only
     * URL: POST /webhook/whatsapp/test
     */
    public function test(Request $request)
    {
        // Log incoming test webhook
        Log::channel('whatsapp')->info('=== WHATSAPP TEST WEBHOOK ===', [
            'QUERY_PARAMS' => $request->query(),
            'POST_DATA' => $request->all(),
            'timestamp' => now()->toIso8601String(),
        ]);

        // Sample test payload
        $testPayload = [
            'device' => 'test-device',
            'message' => $request->get('message', 'halo'),
            'from' => $request->get('from', '6281234567890'),
            'name' => $request->get('name', 'Test User'),
            'participant' => null,
            'ppUrl' => null,
            'media' => null,
            'mimetype' => null,
        ];

        Log::channel('whatsapp')->info('WhatsApp TEST Webhook received', [
            'payload' => $testPayload,
            'timestamp' => now()->toIso8601String(),
        ]);

        // Create a fake request with test data
        $fakeRequest = new Request($testPayload);

        try {
            $waService = new WhatsAppService();
            $handler = new CommandHandler($fakeRequest, $waService);

            $result = $handler->handle();

            return response()->json([
                'status' => 'success',
                'input' => $testPayload,
                'output' => $result,
            ]);
        } catch (\Exception $e) {
            Log::channel('whatsapp')->error('Test webhook error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'status' => 'error',
                'input' => $testPayload,
                'error' => $e->getMessage(),
                'trace' => explode("\n", $e->getTraceAsString()),
            ], 500);
        }
    }
}
