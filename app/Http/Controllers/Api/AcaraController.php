<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Services\WhatsAppService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AcaraController extends BaseApiController
{
    protected WhatsAppService $waService;

    public function __construct(WhatsAppService $waService)
    {
        $this->waService = $waService;
    }

    /**
     * Get list of acara/events
     * GET /api/acara
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        // Get acara that user is invited to (based on dept_id)
        $acaraList = DB::table('ktd_acara')
            ->where('status', '!=', 'deleted')
            ->where(function ($query) use ($user) {
                // Show acara for user's department or all departments
                $query->where('dept_id', $user->dept_id)
                    ->orWhere('dept_id', 0); // 0 means all departments
            })
            ->orderBy('tanggal', 'desc')
            ->get();

        // Check attendance status for each acara
        foreach ($acaraList as $acara) {
            $attendance = DB::table('ktd_presensi_acara')
                ->where('acara_id', $acara->id)
                ->where('user_nip', $user->nomor_induk)
                ->first();

            $acara->status_kehadiran = $attendance ? $attendance->status : null;
            $acara->keterangan_kehadiran = $attendance ? $attendance->keterangan : null;
            $acara->sudah_presensi = $attendance ? true : false;
        }

        return $this->success($acaraList, 'Daftar acara');
    }

    /**
     * Get acara detail
     * GET /api/acara/{id}
     */
    public function show(int $id, Request $request): JsonResponse
    {
        $user = $request->user();

        $acara = DB::table('ktd_acara')->where('id', $id)->first();

        if (!$acara) {
            return $this->notFound('Acara tidak ditemukan');
        }

        // Check attendance
        $attendance = DB::table('ktd_presensi_acara')
            ->where('acara_id', $id)
            ->where('user_nip', $user->nomor_induk)
            ->first();

        $acara->status_kehadiran = $attendance ? $attendance->status : null;
        $acara->keterangan_kehadiran = $attendance ? $attendance->keterangan : null;
        $acara->sudah_presensi = $attendance ? true : false;

        return $this->success($acara, 'Detail acara');
    }

    /**
     * Submit attendance (Hadir)
     * POST /api/acara/{id}/hadir
     */
    public function hadir(Request $request, int $id): JsonResponse
    {
        $user = $request->user();

        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'distance' => 'nullable|numeric',
            'location' => 'nullable|string',
            'foto' => 'nullable|string', // Base64 encoded photo
        ]);

        $acara = DB::table('ktd_acara')->where('id', $id)->first();

        if (!$acara) {
            return $this->notFound('Acara tidak ditemukan');
        }

        // Calculate distance if acara has location
        $distance = null;
        if ($acara->latitude && $acara->longitude && $request->latitude && $request->longitude
            && $request->latitude != 0 && $request->longitude != 0) {
            $distance = $this->calculateDistance(
                $acara->latitude, $acara->longitude,
                $request->latitude, $request->longitude
            );

            // Validate GPS location if radius is set
            if ($acara->radius && $acara->radius > 0 && $distance > $acara->radius) {
                return $this->error("Anda berada di luar radius lokasi acara. Jarak: " . round($distance) . "m, Radius: {$acara->radius}m", 400);
            }
        }

        // Handle photo upload (base64)
        $fotoPath = null;
        if ($request->has('foto') && $request->foto) {
            $fotoPath = $this->savePhoto($request->foto, $id, $user->nomor_induk);
        }

        // Check if already has attendance
        $existingAttendance = DB::table('ktd_presensi_acara')
            ->where('acara_id', $id)
            ->where('user_nip', $user->nomor_induk)
            ->first();

        if ($existingAttendance) {
            // Update existing attendance
            DB::table('ktd_presensi_acara')
                ->where('id', $existingAttendance->id)
                ->update([
                    'status' => 'hadir',
                    'keterangan' => null,
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                    'distance' => $request->distance,
                    'location' => $request->location,
                    'foto' => $fotoPath,
                    'waktu_absen' => \Carbon\Carbon::now('Asia/Jakarta')->format('H:i:s'),
                    'updated_at' => now(),
                ]);
        } else {
            // Create new attendance
            DB::table('ktd_presensi_acara')->insert([
                'acara_id' => $id,
                'user_nip' => $user->nomor_induk,
                'status' => 'hadir',
                'keterangan' => null,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'distance' => $request->distance,
                'location' => $request->location,
                'foto' => $fotoPath,
                'waktu_absen' => \Carbon\Carbon::now('Asia/Jakarta')->format('H:i:s'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return $this->success([
            'status' => 'hadir',
            'waktu' => \Carbon\Carbon::now('Asia/Jakarta')->format('H:i:s'),
        ], 'Presensi berhasil');
    }

    /**
     * Calculate distance between two coordinates (Haversine formula)
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2): float
    {
        $earthRadius = 6371000; // meters

        $lat1 = deg2rad($lat1);
        $lon1 = deg2rad($lon1);
        $lat2 = deg2rad($lat2);
        $lon2 = deg2rad($lon2);

        $dLat = $lat2 - $lat1;
        $dLon = $lon2 - $lon1;

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos($lat1) * cos($lat2) * sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    /**
     * Save base64 photo to storage
     */
    private function savePhoto(string $base64Photo, int $acaraId, string $userNip): ?string
    {
        try {
            // Remove data:image prefix if present
            if (str_contains($base64Photo, 'base64,')) {
                $base64Photo = explode('base64,', $base64Photo)[1];
            }

            $imageData = base64_decode($base64Photo);
            $filename = 'presensi_acara_' . $acaraId . '_' . $userNip . '_' . time() . '.jpg';
            $path = 'presensi_acara/' . $filename;

            Storage::disk('public')->put($path, $imageData);

            return $path;
        } catch (\Exception $e) {
            Log::error('Failed to save photo', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Submit not attendance (Tidak Hadir)
     * POST /api/acara/{id}/tidak-hadir
     */
    public function tidakHadir(Request $request, int $id): JsonResponse
    {
        $user = $request->user();

        $request->validate([
            'keterangan' => 'required|string|max:500',
        ]);

        $acara = DB::table('ktd_acara')->where('id', $id)->first();

        if (!$acara) {
            return $this->notFound('Acara tidak ditemukan');
        }

        // Check if already has attendance
        $existingAttendance = DB::table('ktd_presensi_acara')
            ->where('acara_id', $id)
            ->where('user_nip', $user->nomor_induk)
            ->first();

        if ($existingAttendance) {
            // Update existing attendance
            DB::table('ktd_presensi_acara')
                ->where('id', $existingAttendance->id)
                ->update([
                    'status' => 'tidak_hadir',
                    'keterangan' => $request->keterangan,
                    'updated_at' => now(),
                ]);
        } else {
            // Create new attendance
            DB::table('ktd_presensi_acara')->insert([
                'acara_id' => $id,
                'user_nip' => $user->nomor_induk,
                'status' => 'tidak_hadir',
                'keterangan' => $request->keterangan,
                'latitude' => null,
                'longitude' => null,
                'distance' => null,
                'location' => null,
                'waktu_absen' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return $this->success([
            'status' => 'tidak_hadir',
            'keterangan' => $request->keterangan,
        ], 'Keterangan berhasil dikirim');
    }

    /**
     * Get user's attendance history
     * GET /api/acara/history
     */
    public function history(Request $request): JsonResponse
    {
        $user = $request->user();

        $history = DB::table('ktd_presensi_acara')
            ->join('ktd_acara', 'ktd_presensi_acara.acara_id', '=', 'ktd_acara.id')
            ->where('ktd_presensi_acara.user_nip', $user->nomor_induk)
            ->select('ktd_presensi_acara.*', 'ktd_acara.judul', 'ktd_acara.tanggal', 'ktd_acara.lokasi')
            ->orderBy('ktd_acara.tanggal', 'desc')
            ->get();

        return $this->success($history, 'Riwayat kehadiran');
    }
}
