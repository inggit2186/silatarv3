<?php

namespace App\Http\Controllers\Api;

use App\Models\Department;
use App\Models\KtdPresensi;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PresensiController extends BaseApiController
{
    public function __construct()
    {
        // Set timezone ke Jakarta
        date_default_timezone_set('Asia/Jakarta');
        Carbon::setLocale('id_ID');
    }

    /**
     * Simpan presensi (masuk/pulang)
     * POST /api/presensi
     */
    public function store(Request $request)
    {
        $request->validate([
            'jenis' => 'required|in:masuk,pulang',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'jarak_meter' => 'nullable|numeric',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $user = $request->user();
        $jenis = $request->input('jenis');

        // Set timezone Jakarta
        $now = Carbon::now('Asia/Jakarta');
        $today = $now->toDateString();
        $jam = $now->format('H:i:s');

        // Ambil data department user
        $deptId = $user->dept_id;
        $dept = $user->dept;

        // Cek apakah sudah ada record presensi hari ini
        $presensi = KtdPresensi::where('user_nip', $user->nomor_induk)
            ->whereDate('tanggal', $today)
            ->first();

        if (! $presensi) {
            // Create new record
            $presensi = new KtdPresensi;
            $presensi->user_nip = $user->nomor_induk;
            $presensi->dept_id = $deptId;
            $presensi->tanggal = $today;
        }

        $message = '';

        if ($dept) {
            $jamMasuk = $dept->jam_masuk ? Carbon::parse($dept->jam_masuk, 'Asia/Jakarta') : null;
            $jamPulang = $dept->jam_pulang ? Carbon::parse($dept->jam_pulang, 'Asia/Jakarta') : null;

            if ($jenis === 'masuk') {
                // Cek apakah sudah presensi masuk
                if ($presensi->m_absen) {
                    return $this->error('Presensi masuk sudah dilakukan hari ini', 400);
                }

                $presensi->m_absen = $jam;
                $presensi->m_latitude = $request->input('latitude');
                $presensi->m_longitude = $request->input('longitude');
                $presensi->m_distance = $request->input('jarak_meter');

                // Hitung selisih dan status
                if ($jamMasuk) {
                    $diff = $jamMasuk->diff($now);
                    $presensi->m_diff = $diff->h * 3600 + $diff->i * 60 + $diff->s; // dalam detik

                    // MASUK = tidak terlambat (<= jam_masuk)
                    // TERLAMBAT = terlambat (> jam_masuk)
                    if ($now->gt($jamMasuk)) {
                        $status = 'TERLAMBAT';
                        $selisihFormatted = $this->formatSelisih($diff);
                        $message = 'Presensi masuk berhasil (Terlambat '.$selisihFormatted.')';
                    } else {
                        $status = 'MASUK';
                        $selisihFormatted = $this->formatSelisih($diff);
                        $message = 'Presensi masuk berhasil (lebih awal '.$selisihFormatted.')';
                    }
                } else {
                    $status = 'MASUK';
                    $message = 'Presensi masuk berhasil';
                }
            } else {
                // Pulang
                if ($presensi->p_absen) {
                    return $this->error('Presensi pulang sudah dilakukan hari ini', 400);
                }

                $presensi->p_absen = $jam;
                $presensi->p_latitude = $request->input('latitude');
                $presensi->p_longitude = $request->input('longitude');
                $presensi->p_distance = $request->input('jarak_meter');

                // Hitung selisih dan status
                if ($jamPulang) {
                    $diff = $now->diff($jamPulang);
                    $presensi->p_diff = $diff->h * 3600 + $diff->i * 60 + $diff->s; // dalam detik

                    // PULANG = tidak pulang cepat (>= jam_pulang)
                    // PULANG_CEPAT = pulang cepat (< jam_pulang)
                    if ($now->lt($jamPulang)) {
                        $status = 'PULANG_CEPAT';
                        $selisihFormatted = $this->formatSelisih($diff);
                        $message = 'Presensi pulang berhasil (Pulang cepat '.$selisihFormatted.')';
                    } else {
                        $status = 'PULANG';
                        $selisihFormatted = $this->formatSelisih($diff);
                        $message = 'Presensi pulang berhasil (Lembur '.$selisihFormatted.')';
                    }
                } else {
                    $status = 'PULANG';
                    $message = 'Presensi pulang berhasil';
                }
            }
        } else {
            // Tidak ada data department
            if ($jenis === 'masuk') {
                if ($presensi->m_absen) {
                    return $this->error('Presensi masuk sudah dilakukan hari ini', 400);
                }
                $presensi->m_absen = $jam;
                $presensi->m_latitude = $request->input('latitude');
                $presensi->m_longitude = $request->input('longitude');
                $presensi->m_distance = $request->input('jarak_meter');
                $status = 'MASUK';
                $message = 'Presensi masuk berhasil';
            } else {
                if ($presensi->p_absen) {
                    return $this->error('Presensi pulang sudah dilakukan hari ini', 400);
                }
                $presensi->p_absen = $jam;
                $presensi->p_latitude = $request->input('latitude');
                $presensi->p_longitude = $request->input('longitude');
                $presensi->p_distance = $request->input('jarak_meter');
                $status = 'PULANG';
                $message = 'Presensi pulang berhasil';
            }
        }

        // Update status
        $presensi->status = $status;
        $presensi->keterangan = $request->input('keterangan');
        $presensi->save();

        return $this->success([
            'presensi' => $this->formatPresensi($presensi),
        ], $message, 201);
    }

    /**
     * Ambil presensi hari ini
     * GET /api/presensi/today
     */
    public function today(Request $request)
    {
        $user = $request->user();

        // Set timezone Jakarta
        $now = Carbon::now('Asia/Jakarta');
        $today = $now->toDateString();

        $presensi = KtdPresensi::where('user_nip', $user->nomor_induk)
            ->whereDate('tanggal', $today)
            ->first();

        return $this->success([
            'tanggal' => $today,
            'status' => $presensi?->status,
            'masuk' => $presensi && $presensi->m_absen ? [
                'jam' => $presensi->m_absen,
                'latitude' => $presensi->m_latitude,
                'longitude' => $presensi->m_longitude,
                'jarak_meter' => $presensi->m_distance,
                'selisih' => $presensi->m_diff,
            ] : null,
            'pulang' => $presensi && $presensi->p_absen ? [
                'jam' => $presensi->p_absen,
                'latitude' => $presensi->p_latitude,
                'longitude' => $presensi->p_longitude,
                'jarak_meter' => $presensi->p_distance,
                'selisih' => $presensi->p_diff,
            ] : null,
        ]);
    }

    /**
     * Ambil riwayat presensi
     * GET /api/presensi/history
     */
    public function history(Request $request)
    {
        $request->validate([
            'bulan' => 'nullable|integer|min:1|max:12',
            'tahun' => 'nullable|integer|min:2020|max:2100',
        ]);

        $user = $request->user();
        $bulan = $request->input('bulan', Carbon::now()->month);
        $tahun = $request->input('tahun', Carbon::now()->year);

        $data = KtdPresensi::where('user_nip', $user->nomor_induk)
            ->whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulan)
            ->orderBy('tanggal', 'asc')
            ->get()
            ->map(fn ($p) => $this->formatPresensi($p));

        return $this->success([
            'bulan' => $bulan,
            'tahun' => $tahun,
            'total' => $data->count(),
            'data' => $data,
        ]);
    }

    /**
     * Rekap presensi bulanan
     * GET /api/presensi/rekap
     */
    public function rekap(Request $request)
    {
        $request->validate([
            'bulan' => 'nullable|integer|min:1|max:12',
            'tahun' => 'nullable|integer|min:2020|max:2100',
        ]);

        $user = $request->user();
        $bulan = $request->input('bulan', Carbon::now()->month);
        $tahun = $request->input('tahun', Carbon::now()->year);

        $data = KtdPresensi::where('user_nip', $user->nomor_induk)
            ->whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulan)
            ->get();

        $stats = [
            'total_hari_kerja' => $data->whereNotNull('m_absen')->count(),
            'telat' => $data->where('status', 'telat')->count(),
            'pulang_cepat' => $data->where('status', 'pulang_cepat')->count(),
        ];

        return $this->success([
            'bulan' => $bulan,
            'tahun' => $tahun,
            'stats' => $stats,
        ]);
    }

    /**
     * Format presensi response
     */
    private function formatPresensi(KtdPresensi $presensi): array
    {
        return [
            'id' => $presensi->id,
            'user_nip' => $presensi->user_nip,
            'dept_id' => $presensi->dept_id,
            'tanggal' => $presensi->tanggal->format('Y-m-d'),
            'm_absen' => $presensi->m_absen,
            'm_diff' => $presensi->m_diff,
            'm_latitude' => $presensi->m_latitude,
            'm_longitude' => $presensi->m_longitude,
            'm_distance' => $presensi->m_distance,
            'p_absen' => $presensi->p_absen,
            'p_diff' => $presensi->p_diff,
            'p_latitude' => $presensi->p_latitude,
            'p_longitude' => $presensi->p_longitude,
            'p_distance' => $presensi->p_distance,
            'status' => $presensi->status,
            'keterangan' => $presensi->keterangan,
            'created_at' => $presensi->created_at,
        ];
    }

    /**
     * Format selisih waktu ke format "XX Jam XX Menit XX Detik"
     */
    private function formatSelisih(CarbonInterval $diff): string
    {
        $jam = $diff->h + ($diff->days * 24);
        $menit = $diff->i;
        $detik = $diff->s;

        $parts = [];
        if ($jam > 0) {
            $parts[] = $jam.' Jam';
        }
        if ($menit > 0) {
            $parts[] = $menit.' Menit';
        }
        if ($detik > 0 || empty($parts)) {
            $parts[] = $detik.' Detik';
        }

        return implode(' ', $parts);
    }

    // ═══════════════════════════════════════════════════════════════════════════
    // PRESENSI ERROR - Mobile App
    // ═══════════════════════════════════════════════════════════════════════════

    /**
     * Get presensi error status today
     * GET /api/presensi-error/today
     */
    public function errorToday(Request $request)
    {
        $user = $request->user();
        $today = Carbon::now('Asia/Jakarta')->toDateString();

        $presensi = KtdPresensi::where('user_nip', $user->nomor_induk)
            ->whereDate('tanggal', $today)
            ->first();

        if (!$presensi) {
            return $this->success([
                'tanggal' => $today,
                'sudah_masuk' => false,
                'sudah_pulang' => false,
                'sudah_presensi_error' => false,
                'm_absen' => null,
                'p_absen' => null,
                'error_status' => null,
            ]);
        }

        $isMasukError = !empty($presensi->error_masuk_taken_at);
        $isPulangError = !empty($presensi->error_pulang_taken_at);

        return $this->success([
            'tanggal' => $today,
            'sudah_masuk' => !empty($presensi->m_absen),
            'sudah_pulang' => !empty($presensi->p_absen),
            'sudah_presensi_error' => $isMasukError || $isPulangError,
            'm_absen' => $presensi->m_absen,
            'p_absen' => $presensi->p_absen,
            'error_masuk_taken_at' => $presensi->error_masuk_taken_at,
            'error_pulang_taken_at' => $presensi->error_pulang_taken_at,
            'error_status' => $presensi->status,
            'keterangan' => $presensi->keterangan,
        ]);
    }

    /**
     * Submit presensi error (Sistem Error / Tugas Luar / Lupa Presensi)
     * POST /api/presensi-error
     *
     * Standarisasi sesuai dengan versi web (PageController::presensiErrorSubmit)
     */
    public function submitError(Request $request)
    {
        \Log::info('submitError called', [
            'user_id' => $request->user()?->id,
            'jenis' => $request->input('jenis'),
            'alasan' => $request->input('alasan'),
            'foto_length' => strlen($request->input('foto') ?? ''),
        ]);

        $request->validate([
            'jenis' => 'required|in:masuk,pulang',
            'alasan' => 'required|in:SISTEM_ERROR,TUGAS_LUAR,LUPA_PRESNSI_PUSAKA',
            'keterangan_tugas_luar' => 'required_if:alasan,TUGAS_LUAR|nullable|string',
            'tanggal_lupa' => 'required_if:alasan,LUPA_PRESNSI_PUSAKA|nullable|date|after_or_equal:yesterday|before_or_equal:today',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'jarak_meter' => 'nullable|numeric',
            'alamat' => 'nullable|string',
            'foto' => 'required|string',
            'supervisor_name' => 'nullable|string',
            'supervisor_nip' => 'nullable|string',
            'unit_kerja_manual' => 'nullable|string',
        ]);

        $user = $request->user();
        $jenis = $request->input('jenis');
        $alasan = $request->input('alasan');
        $now = Carbon::now('Asia/Jakarta');

        // Waktu tetap untuk presensi error (standar web)
        $jamMasuk = '05:59:00';
        $jamPulang = '19:59:00';

        // Tentukan tanggal: lupa presensi pakai tanggal dari form, lainnya pakai hari ini
        $targetTanggal = $alasan === 'LUPA_PRESNSI_PUSAKA'
            ? Carbon::parse($request->input('tanggal_lupa'))->format('Y-m-d')
            : $now->toDateString();

        \Log::info('submitError - target date', ['target_tanggal' => $targetTanggal]);

        // Tentukan status berdasarkan alasan
        $status = $alasan;

        // Tentukan keterangan
        if ($alasan === 'TUGAS_LUAR') {
            $keterangan = $request->input('keterangan_tugas_luar', 'Tugas Luar');
        } elseif ($alasan === 'LUPA_PRESNSI_PUSAKA') {
            $keterangan = 'Dilaporkan melalui halaman Presensi Error (Lupa Presensi Pusaka)';
        } else {
            $keterangan = 'Dilaporkan melalui halaman Presensi Error (Sistem Error)';
        }

        // Handle photo upload
        $fotoPath = $this->saveErrorPhoto($request->input('foto'), $user->nomor_induk);
        if (!$fotoPath) {
            return $this->error('Gagal menyimpan foto. Silakan coba lagi.', 422);
        }

        // Cek apakah sudah ada record
        $presensi = KtdPresensi::where('user_nip', $user->nomor_induk)
            ->whereDate('tanggal', $targetTanggal)
            ->first();

        $dataUpdate = [
            'status' => $status,
            'keterangan' => $keterangan,
            'updated_at' => now(),
        ];

        // Simpan data atasan manual untuk dept 998/999
        $specialDeptIds = [998, 999];
        if (in_array((int) $user->dept_id, $specialDeptIds)) {
            $dataUpdate['manual_supervisor_name'] = $request->input('supervisor_name', '');
            $dataUpdate['manual_supervisor_nip'] = $request->input('supervisor_nip', '');
            $dataUpdate['manual_unit_kerja'] = $request->input('unit_kerja_manual', '');
        }

        // Hitung jarak dari kantor (standar web)
        $distance = $this->calculateDistanceFromOffice(
            $user->dept_id,
            $request->input('latitude', 0),
            $request->input('longitude', 0)
        );

        if ($jenis === 'masuk') {
            $dataUpdate['m_absen'] = $jamMasuk;
            $dataUpdate['m_latitude'] = $request->input('latitude', 0);
            $dataUpdate['m_longitude'] = $request->input('longitude', 0);
            $dataUpdate['m_location'] = $fotoPath;
            $dataUpdate['m_alamat'] = $request->input('alamat', '');
            $dataUpdate['error_masuk_taken_at'] = $now->format('H:i:s');
            $dataUpdate['m_distance'] = $distance ?? $request->input('jarak_meter', 0);
        } else {
            $dataUpdate['p_absen'] = $jamPulang;
            $dataUpdate['p_latitude'] = $request->input('latitude', 0);
            $dataUpdate['p_longitude'] = $request->input('longitude', 0);
            $dataUpdate['p_location'] = $fotoPath;
            $dataUpdate['p_alamat'] = $request->input('alamat', '');
            $dataUpdate['error_pulang_taken_at'] = $now->format('H:i:s');
            $dataUpdate['p_distance'] = $distance ?? $request->input('jarak_meter', 0);
        }

        \Log::info('submitError - about to save', [
            'user_nip' => $user->nomor_induk,
            'tanggal' => $targetTanggal,
            'status' => $status,
        ]);

        try {
            $presensiId = null;
            if ($presensi) {
                KtdPresensi::where('id', $presensi->id)->update($dataUpdate);
                $presensiId = $presensi->id;
            } else {
                $dataInsert = array_merge($dataUpdate, [
                    'user_nip' => $user->nomor_induk,
                    'tanggal' => $targetTanggal,
                    'created_at' => now(),
                ]);
                $newPresensi = new KtdPresensi($dataInsert);
                $newPresensi->save();
                $presensiId = $newPresensi->id;
            }

            \Log::info('submitError - saved successfully', ['id' => $presensiId]);

            return $this->success([
                'id' => $presensiId,
                'tanggal' => $targetTanggal,
                'jenis' => $jenis,
                'alasan' => $alasan,
                'jam' => $now->format('H:i:s'),
            ], 'Presensi error berhasil disimpan', 201);
        } catch (\Exception $e) {
            \Log::error('Failed to save error presensi', ['error' => $e->getMessage()]);
            return $this->error('Gagal menyimpan data presensi: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Calculate distance from office based on department coordinates
     */
    private function calculateDistanceFromOffice(?int $deptId, float $userLat, float $userLon): ?float
    {
        // Jika koordinat user tidak valid, return null
        if ($userLat == 0 && $userLon == 0) {
            return null;
        }

        // Ambil data department
        $dept = DB::table('ktd_department')->where('id', $deptId)->first();
        if (!$dept) {
            return null;
        }

        // Cek apakah department memiliki koordinat
        if (empty($dept->latitude) || empty($dept->longitude)) {
            return null;
        }

        $officeLat = (float) $dept->latitude;
        $officeLon = (float) $dept->longitude;

        // Validasi koordinat office
        if ($officeLat == 0 && $officeLon == 0) {
            return null;
        }

        return $this->calculateDistance($officeLat, $officeLon, $userLat, $userLon);
    }

    /**
     * Calculate distance between two coordinates using Haversine formula
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
     * Get presensi error history
     * GET /api/presensi-error/history
     */
    public function errorHistory(Request $request)
    {
        $user = $request->user();
        $bulan = $request->input('bulan', Carbon::now()->month);
        $tahun = $request->input('tahun', Carbon::now()->year);

        \Log::info('errorHistory called', [
            'user_nip' => $user->nomor_induk,
            'bulan' => $bulan,
            'tahun' => $tahun,
        ]);

        // Debug: cek semua data presensi error user tanpa filter
        $allData = KtdPresensi::where('user_nip', $user->nomor_induk)
            ->whereIn('status', ['SISTEM_ERROR', 'TUGAS_LUAR', 'LUPA_PRESNSI_PUSAKA'])
            ->get(['id', 'user_nip', 'tanggal', 'status', 'keterangan']);

        \Log::info('errorHistory - all data without month/year filter', [
            'count' => $allData->count(),
            'data' => $allData->toArray(),
        ]);

        // Debug: cek data dengan filter bulan/tahun
        $filteredData = KtdPresensi::where('user_nip', $user->nomor_induk)
            ->whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulan)
            ->whereIn('status', ['SISTEM_ERROR', 'TUGAS_LUAR', 'LUPA_PRESNSI_PUSAKA'])
            ->get(['id', 'user_nip', 'tanggal', 'status']);

        \Log::info('errorHistory - filtered data', [
            'bulan' => $bulan,
            'tahun' => $tahun,
            'count' => $filteredData->count(),
            'data' => $filteredData->toArray(),
        ]);

        $data = KtdPresensi::where('user_nip', $user->nomor_induk)
            ->whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulan)
            ->whereIn('status', ['SISTEM_ERROR', 'TUGAS_LUAR', 'LUPA_PRESNSI_PUSAKA'])
            ->orderBy('tanggal', 'desc')
            ->get()
            ->map(fn ($p) => $this->formatErrorPresensi($p));

        \Log::info('errorHistory result', ['count' => $data->count()]);

        return $this->success([
            'bulan' => $bulan,
            'tahun' => $tahun,
            'total' => $data->count(),
            'data' => $data,
        ]);
    }

    /**
     * Format error presensi response
     */
    private function formatErrorPresensi($presensi): array
    {
        return [
            'id' => $presensi->id,
            'tanggal' => $presensi->tanggal instanceof Carbon ? $presensi->tanggal->format('Y-m-d') : $presensi->tanggal,
            'status' => $presensi->status,
            'keterangan' => $presensi->keterangan,
            'm_absen' => $presensi->m_absen,
            'p_absen' => $presensi->p_absen,
            'error_masuk_taken_at' => $presensi->error_masuk_taken_at,
            'error_pulang_taken_at' => $presensi->error_pulang_taken_at,
            'created_at' => $presensi->created_at?->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * Save error presensi photo
     */
    private function saveErrorPhoto(string $base64Photo, string $userNip): ?string
    {
        try {
            if (str_contains($base64Photo, 'base64,')) {
                $base64Photo = explode('base64,', $base64Photo)[1];
            }

            $imageData = base64_decode($base64Photo);
            $filename = 'presensi_error_' . $userNip . '_' . time() . '.jpg';
            $path = 'presensi_error/' . $filename;

            \Storage::disk('public')->put($path, $imageData);

            return $path;
        } catch (\Exception $e) {
            \Log::error('Failed to save error presensi photo', ['error' => $e->getMessage()]);
            return null;
        }
    }
}
