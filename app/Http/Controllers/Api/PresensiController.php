<?php

namespace App\Http\Controllers\Api;

use App\Models\KtdPresensi;
use App\Models\Department;
use Illuminate\Http\Request;
use Carbon\Carbon;

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

        if (!$presensi) {
            // Create new record
            $presensi = new KtdPresensi();
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
                        $message = 'Presensi masuk berhasil (Terlambat ' . $selisihFormatted . ')';
                    } else {
                        $status = 'MASUK';
                        $selisihFormatted = $this->formatSelisih($diff);
                        $message = 'Presensi masuk berhasil (lebih awal ' . $selisihFormatted . ')';
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
                        $message = 'Presensi pulang berhasil (Pulang cepat ' . $selisihFormatted . ')';
                    } else {
                        $status = 'PULANG';
                        $selisihFormatted = $this->formatSelisih($diff);
                        $message = 'Presensi pulang berhasil (Lembur ' . $selisihFormatted . ')';
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
            ->map(fn($p) => $this->formatPresensi($p));

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
    private function formatSelisih(\Carbon\CarbonInterval $diff): string
    {
        $jam = $diff->h + ($diff->days * 24);
        $menit = $diff->i;
        $detik = $diff->s;

        $parts = [];
        if ($jam > 0) {
            $parts[] = $jam . ' Jam';
        }
        if ($menit > 0) {
            $parts[] = $menit . ' Menit';
        }
        if ($detik > 0 || empty($parts)) {
            $parts[] = $detik . ' Detik';
        }

        return implode(' ', $parts);
    }
}
