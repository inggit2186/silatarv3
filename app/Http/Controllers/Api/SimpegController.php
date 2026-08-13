<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Services\WhatsAppService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SimpegController extends BaseApiController
{
    protected WhatsAppService $waService;

    public function __construct(WhatsAppService $waService)
    {
        $this->waService = $waService;
    }

    /**
     * Submit SIMPEG password reset request
     * POST /api/simpeg/reset-password
     */
    public function submitResetPassword(Request $request): JsonResponse
    {
        $user = $request->user();

        // Get user data from database
        $userData = DB::table('users')
            ->where('id', $user->id)
            ->select('id', 'nomor_induk', 'name', 'email', 'telp', 'dept_id')
            ->first();

        if (!$userData) {
            return $this->error('Data user tidak ditemukan', 404);
        }

        // Check if user already has pending request
        $pendingRequest = DB::table('ktd_pengaduan')
            ->where('user_nip', $userData->nomor_induk)
            ->where('jenis', 'SIMPEG')
            ->where('status', 'pending')
            ->first();

        if ($pendingRequest) {
            return $this->error('Anda sudah memiliki request yang belum diproses', 400);
        }

        // Get target staff (users.id = 45)
        $targetStaff = DB::table('users')->where('id', 45)->first();
        $targetName = $targetStaff ? $targetStaff->name : 'Petugas SIMPEG';

        // Create request in ktd_pengaduan
        $requestId = DB::table('ktd_pengaduan')->insertGetId([
            'kode' => 'SIMPEG-' . date('YmdHis'),
            'jenis' => 'SIMPEG',
            'user_nip' => $userData->nomor_induk,
            'nama' => $userData->name,
            'email' => $userData->email,
            'telp' => $userData->telp,
            'judul' => 'Request Reset Password SIMPEG',
            'keterangan' => "User {$userData->name} (NIP: {$userData->nomor_induk}) meminta reset password SIMPEG. Silakan hubungi user untuk proses selanjutnya.",
            'filename' => null,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Send WhatsApp notification to target staff
        $this->sendWhatsAppNotification($userData, $targetStaff, $requestId);

        return $this->success([
            'id' => $requestId,
            'status' => 'pending',
            'message' => 'Request reset password SIMPEG berhasil dikirim',
        ], 'Request berhasil dikirim', 201);
    }

    /**
     * Get user's SIMPEG requests
     * GET /api/simpeg/my-requests
     */
    public function myRequests(Request $request): JsonResponse
    {
        $user = $request->user();

        $requests = DB::table('ktd_pengaduan')
            ->where('user_nip', $user->nomor_induk)
            ->where('jenis', 'SIMPEG')
            ->orderBy('created_at', 'desc')
            ->get();

        return $this->success($requests, 'Daftar request SIMPEG');
    }

    /**
     * Get SIMPEG request detail
     * GET /api/simpeg/{id}
     */
    public function show(int $id, Request $request): JsonResponse
    {
        $user = $request->user();

        $simpegRequest = DB::table('ktd_pengaduan')
            ->where('id', $id)
            ->where('user_nip', $user->nomor_induk)
            ->where('jenis', 'SIMPEG')
            ->first();

        if (!$simpegRequest) {
            return $this->notFound('Request tidak ditemukan');
        }

        return $this->success($simpegRequest, 'Detail request SIMPEG');
    }

    /**
     * Send WhatsApp notification to staff
     */
    private function sendWhatsAppNotification($userData, $targetStaff, int $requestId): void
    {
        try {
            if (!$targetStaff || !$targetStaff->telp) {
                Log::warning('Target staff not found or no phone number');
                return;
            }

            $phone = WhatsAppService::normalizePhoneNumber($targetStaff->telp);

            $userName = is_object($userData) ? $userData->name : ($userData['name'] ?? '-');
            $userNip = is_object($userData) ? $userData->nomor_induk : ($userData['nomor_induk'] ?? '-');
            $userEmail = is_object($userData) ? $userData->email : ($userData['email'] ?? '-');
            $userTelp = is_object($userData) ? ($userData->telp ?? '-') : ($userData['telp'] ?? '-');

            $message = "🔐 *REQUEST RESET PASSWORD SIMPEG* 🔐\n\n".
                       "Halo, {$targetStaff->name}!\n\n".
                       "Ada request reset password SIMPEG yang perlu diproses:\n\n".
                       "👤 *Nama:* {$userName}\n".
                       "📋 *NIP:* {$userNip}\n".
                       "📧 *Email:* {$userEmail}\n".
                       "📱 *Telp:* {$userTelp}\n\n".
                       "Silakan hubungi user untuk proses selanjutnya.\n\n".
                       "Terima kasih 🙏";

            $this->waService->sendMessage($phone, $message);
        } catch (\Exception $e) {
            Log::error('Failed to send WA notification for SIMPEG', ['error' => $e->getMessage()]);
        }
    }

    // ═══════════════════════════════════════════════════════════════════════
    // ADMIN ENDPOINTS
    // ═══════════════════════════════════════════════════════════════════════

    /**
     * Get all SIMPEG requests for admin
     * GET /api/admin/simpeg
     */
    public function adminIndex(Request $request): JsonResponse
    {
        $perPage = $request->input('per_page', 10);
        $status = $request->input('status');

        $query = DB::table('ktd_pengaduan')
            ->where('jenis', 'SIMPEG');

        if ($status) {
            $query->where('status', $status);
        }

        $requests = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return $this->success($requests, 'Daftar request SIMPEG');
    }

    /**
     * Get SIMPEG request detail for admin
     * GET /api/admin/simpeg/{id}
     */
    public function adminShow(int $id): JsonResponse
    {
        $simpegRequest = DB::table('ktd_pengaduan')
            ->where('id', $id)
            ->where('jenis', 'SIMPEG')
            ->first();

        if (!$simpegRequest) {
            return $this->notFound('Request tidak ditemukan');
        }

        return $this->success($simpegRequest, 'Detail request SIMPEG');
    }

    /**
     * Verify SIMPEG request
     * PUT /api/admin/simpeg/{id}/verify
     */
    public function verify(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'status' => 'required|in:SUKSES,GAGAL,DIPROSES,DITOLAK',
            'keterangan' => 'required|string|max:500',
        ]);

        $simpegRequest = DB::table('ktd_pengaduan')
            ->where('id', $id)
            ->where('jenis', 'SIMPEG')
            ->first();

        if (!$simpegRequest) {
            return $this->notFound('Request tidak ditemukan');
        }

        // Update status
        DB::table('ktd_pengaduan')
            ->where('id', $id)
            ->update([
                'status' => strtolower($request->status),
                'keterangan' => $request->keterangan,
                'updated_at' => now(),
            ]);

        // Send WhatsApp notification to requester
        $this->sendVerificationNotification($simpegRequest, $request->status, $request->keterangan);

        return $this->success([
            'id' => $id,
            'status' => strtolower($request->status),
            'keterangan' => $request->keterangan,
        ], 'Verifikasi berhasil dikirim');
    }

    /**
     * Send verification notification to requester
     */
    private function sendVerificationNotification($simpegRequest, string $status, string $keterangan): void
    {
        try {
            // Get requester phone
            $requester = DB::table('users')
                ->where('nomor_induk', $simpegRequest->user_nip)
                ->where('telp', '!=', null)
                ->first();

            if (!$requester || !$requester->telp) {
                Log::warning('Requester not found or no phone number');
                return;
            }

            $phone = WhatsAppService::normalizePhoneNumber($requester->telp);
            $statusIcon = match($status) {
                'SUKSES' => '✅',
                'GAGAL' => '❌',
                'DIPROSES' => '🔄',
                'DITOLAK' => '🚫',
                default => '📋',
            };

            $statusLabel = match($status) {
                'SUKSES' => 'Berhasil Diproses',
                'GAGAL' => 'Gagal Diproses',
                'DIPROSES' => 'Sedang Diproses',
                'DITOLAK' => 'Ditolak',
                default => $status,
            };

            $message = "{$statusIcon} *VERIFIKASI RESET PASSWORD SIMPEG* {$statusIcon}\n\n".
                       "Halo, {$simpegRequest->nama}!\n\n".
                       "Request reset password SIMPEG Anda telah *{$statusLabel}*.\n\n".
                       "📋 *Status:* {$statusLabel}\n".
                       "💬 *Keterangan:* {$keterangan}\n\n".
                       "Silakan hubungi petugas jika ada pertanyaan.\n\n".
                       "Terima kasih 🙏";

            $this->waService->sendMessage($phone, $message);
        } catch (\Exception $e) {
            Log::error('Failed to send verification notification', ['error' => $e->getMessage()]);
        }
    }
}
