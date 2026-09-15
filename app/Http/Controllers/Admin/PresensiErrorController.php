<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PresensiErrorController extends Controller
{
    /**
     * Display listing of presensi error records.
     */
    public function index(Request $request)
    {
        $query = DB::table('ktd_presensi as p')
            ->leftJoin('users as u', 'u.nomor_induk', '=', 'p.user_nip')
            ->leftJoin('ktd_department as d', 'd.id', '=', 'u.dept_id')
            ->whereIn('p.status', ['SISTEM_ERROR', 'TUGAS_LUAR'])
            ->select([
                'p.id',
                'p.user_nip',
                'u.name as user_name',
                'd.nama as dept_name',
                'p.tanggal',
                'p.status',
                'p.keterangan',
                'p.m_absen',
                'p.p_absen',
                'p.m_latitude',
                'p.m_longitude',
                'p.m_alamat',
                'p.p_alamat',
                'p.m_location as foto_path',
                'p.error_masuk_taken_at',
                'p.error_pulang_taken_at',
                'p.created_at',
            ]);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('u.name', 'like', "%{$search}%")
                    ->orWhere('p.user_nip', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('p.status', $request->status);
        }

        // Date range filter
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('p.tanggal', [$request->start_date, $request->end_date]);
        }

        // Department filter
        if ($request->filled('dept_id')) {
            $query->where('u.dept_id', $request->dept_id);
        }

        // Sort and paginate
        $presensi = $query->orderBy('p.tanggal', 'desc')
            ->orderBy('p.created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        // Get stats
        $stats = [
            'total_sistem_error' => DB::table('ktd_presensi')
                ->where('status', 'SISTEM_ERROR')
                ->count(),
            'total_tugas_luar' => DB::table('ktd_presensi')
                ->where('status', 'TUGAS_LUAR')
                ->count(),
            'today' => DB::table('ktd_presensi')
                ->whereIn('status', ['SISTEM_ERROR', 'TUGAS_LUAR'])
                ->whereDate('tanggal', date('Y-m-d'))
                ->count(),
            'this_month' => DB::table('ktd_presensi')
                ->whereIn('status', ['SISTEM_ERROR', 'TUGAS_LUAR'])
                ->whereMonth('tanggal', date('m'))
                ->whereYear('tanggal', date('Y'))
                ->count(),
        ];

        // Get departments for filter
        $departments = DB::table('ktd_department')
            ->whereIn('status', [1, 2])
            ->orderBy('nama')
            ->get(['id', 'nama']);

        return view('admin.presensi-error.index', [
            'title' => 'Laporan Presensi Error - SILATAR Admin',
            'breadcrumbs' => [
                ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['label' => 'Presensi Error', 'url' => null],
            ],
            'presensi' => $presensi,
            'stats' => $stats,
            'departments' => $departments,
            'filters' => [
                'search' => $request->input('search'),
                'status' => $request->input('status'),
                'start_date' => $request->input('start_date'),
                'end_date' => $request->input('end_date'),
                'dept_id' => $request->input('dept_id'),
            ],
        ]);
    }

    /**
     * Reset presensi error fields (tanpa menghapus record utama).
     */
    public function destroy($id)
    {
        try {
            $presensi = DB::table('ktd_presensi')->where('id', $id)->first();

            if (! $presensi) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data presensi tidak ditemukan',
                ], 404);
            }

            $isMasukError = ! empty($presensi->error_masuk_taken_at);
            $isPulangError = ! empty($presensi->error_pulang_taken_at);

            $dataReset = [
                'updated_at' => now(),
            ];

            // Reset field presensi masuk error
            if ($isMasukError) {
                $this->deletePhoto($presensi->m_location);

                $dataReset = array_merge($dataReset, [
                    'm_absen' => null,
                    'm_latitude' => null,
                    'm_longitude' => null,
                    'm_location' => null,
                    'm_alamat' => null,
                    'm_distance' => null,
                    'error_masuk_taken_at' => null,
                ]);
            }

            // Reset field presensi pulang error
            if ($isPulangError) {
                $this->deletePhoto($presensi->p_location);

                $dataReset = array_merge($dataReset, [
                    'p_absen' => null,
                    'p_latitude' => null,
                    'p_longitude' => null,
                    'p_location' => null,
                    'p_alamat' => null,
                    'p_distance' => null,
                    'error_pulang_taken_at' => null,
                ]);
            }

            // Reset status & keterangan hanya jika kedua sisi sudah bersih
            if ($isMasukError && ! $isPulangError) {
                $dataReset['status'] = null;
                $dataReset['keterangan'] = null;
            } elseif ($isPulangError && ! $isMasukError) {
                $dataReset['status'] = null;
                $dataReset['keterangan'] = null;
            } elseif ($isMasukError && $isPulangError) {
                // Keduanya error, reset status/keterangan
                $dataReset['status'] = null;
                $dataReset['keterangan'] = null;
            }

            DB::table('ktd_presensi')->where('id', $id)->update($dataReset);

            Log::info('Presensi error reset', [
                'id' => $id,
                'user_nip' => $presensi->user_nip,
                'reset_masuk' => $isMasukError,
                'reset_pulang' => $isPulangError,
                'deleted_by' => auth()->user()->id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data presensi error berhasil dihapus',
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to reset presensi error', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Hapus foto dari storage.
     */
    private function deletePhoto(?string $photoPath): void
    {
        if ($photoPath) {
            $fullPath = storage_path('app/public/'.$photoPath);
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }
        }
    }
}
