<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PengajuanController extends BaseApiController
{
    /**
     * Get user's pengajuan list
     * GET /api/pengajuan
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $perPage = $request->input('per_page', 10);
        $status = $request->input('status');

        $query = DB::table('users_request as ur')
            ->leftJoin('ktd_layanan as l', 'l.id', '=', 'ur.layanan_id')
            ->leftJoin('ktd_department as d', 'd.id', '=', 'ur.unit_id')
            ->select([
                'ur.id',
                'ur.no_pengajuan',
                'ur.tanggal as tanggal_pengajuan',
                'ur.status',
                'l.nama_layanan as layanan_nama',
                'l.ikon as layanan_ikon',
                'd.nama_department as unit_nama',
                'ur.catatan',
            ])
            ->where('ur.user_id', $user->id)
            ->orderBy('ur.tanggal', 'desc');

        if ($status) {
            $query->where('ur.status', $status);
        }

        $pengajuan = $query->paginate($perPage);

        // Format status for mobile
        foreach ($pengajuan->items() as $item) {
            $item->status_display = $this->formatStatus($item->status);
            $item->tanggal_pengajuan = date('d M Y', strtotime($item->tanggal_pengajuan));
        }

        return $this->successPaginated($pengajuan, 'Daftar pengajuan');
    }

    /**
     * Create new pengajuan
     * POST /api/pengajuan
     */
    public function store(Request $request)
    {
        $request->validate([
            'layanan_id' => 'required|integer',
            'unit_id' => 'required|integer',
            'jawaban' => 'nullable|array',
            'jawaban.*.syarat_id' => 'required|integer',
            'jawaban.*.jawaban' => 'required|string',
        ]);

        $user = $request->user();

        // Verify layanan exists
        $layanan = DB::table('ktd_layanan')
            ->where('id', $request->layanan_id)
            ->where('is_active', 1)
            ->first();

        if (!$layanan) {
            return $this->notFound('Layanan tidak ditemukan');
        }

        // Generate no pengajuan
        $noPengajuan = 'PNJ-' . date('Ymd') . '-' . str_pad($user->id, 4, '0', STR_PAD_LEFT) . '-' . str_pad(mt_rand(1, 999), 3, '0', STR_PAD_LEFT);

        // Check if draft mode
        $isDraft = $request->input('is_draft', false);

        DB::beginTransaction();
        try {
            // Create request
            $requestId = DB::table('users_request')->insertGetId([
                'user_id' => $user->id,
                'layanan_id' => $request->layanan_id,
                'unit_id' => $request->unit_id,
                'no_pengajuan' => $noPengajuan,
                'status' => $isDraft ? 'DRAFT' : 'PENDING',
                'catatan' => $request->input('catatan'),
                'tanggal' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Save answers if provided
            if ($request->has('jawaban')) {
                foreach ($request->jawaban as $jawaban) {
                    DB::table('users_request_answers')->insert([
                        'user_request_id' => $requestId,
                        'syarat_id' => $jawaban['syarat_id'],
                        'jawaban' => $jawaban['jawaban'],
                        'created_at' => now(),
                    ]);
                }
            }

            DB::commit();

            return $this->success([
                'id' => $requestId,
                'no_pengajuan' => $noPengajuan,
                'status' => $isDraft ? 'DRAFT' : 'PENDING',
            ], 'Pengajuan berhasil dibuat', 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error('Gagal membuat pengajuan: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get pengajuan detail
     * GET /api/pengajuan/{id}
     */
    public function show(Request $request, string $id)
    {
        $user = $request->user();

        $pengajuan = DB::table('users_request as ur')
            ->leftJoin('ktd_layanan as l', 'l.id', '=', 'ur.layanan_id')
            ->leftJoin('ktd_department as d', 'd.id', '=', 'ur.unit_id')
            ->select([
                'ur.id',
                'ur.no_pengajuan',
                'ur.tanggal',
                'ur.status',
                'ur.catatan',
                'l.id as layanan_id',
                'l.nama_layanan as layanan_nama',
                'l.estimasi as layanan_estimasi',
                'l.ikon as layanan_ikon',
                'd.nama_department as unit_nama',
                'ur.updated_at',
            ])
            ->where('ur.id', $id)
            ->where('ur.user_id', $user->id)
            ->first();

        if (!$pengajuan) {
            return $this->notFound('Pengajuan tidak ditemukan');
        }

        // Get answers
        $jawaban = DB::table('users_request_answers as ura')
            ->leftJoin('ktd_syarat as s', 's.id', '=', 'ura.syarat_id')
            ->select([
                'ura.id',
                's.nama_syarat as nama',
                's.tipe',
                's.is_required',
                'ura.jawaban',
            ])
            ->where('ura.user_request_id', $id)
            ->get();

        // Get uploaded files
        $berkas = DB::table('users_berkas')
            ->where('user_request_id', $id)
            ->get(['id', 'nama_berkas as nama', 'file_path as path', 'tipe']);

        $pengajuan->jawaban = $jawaban;
        $pengajuan->berkas = $berkas;
        $pengajuan->status_display = $this->formatStatus($pengajuan->status);
        $pengajuan->tanggal = date('d M Y H:i', strtotime($pengajuan->tanggal));

        return $this->success($pengajuan, 'Detail pengajuan');
    }

    /**
     * Update pengajuan
     * PUT /api/pengajuan/{id}
     */
    public function update(Request $request, string $id)
    {
        $user = $request->user();

        // Check ownership and status
        $pengajuan = DB::table('users_request')
            ->where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!$pengajuan) {
            return $this->notFound('Pengajuan tidak ditemukan');
        }

        // Only allow update for DRAFT status
        if ($pengajuan->status !== 'DRAFT') {
            return $this->error('Hanya pengajuan berstatus DRAFT yang dapat diupdate', 400);
        }

        $request->validate([
            'jawaban' => 'nullable|array',
            'jawaban.*.syarat_id' => 'required|integer',
            'jawaban.*.jawaban' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            // Update answers
            if ($request->has('jawaban')) {
                // Delete existing answers
                DB::table('users_request_answers')
                    ->where('user_request_id', $id)
                    ->delete();

                // Insert new answers
                foreach ($request->jawaban as $jawaban) {
                    DB::table('users_request_answers')->insert([
                        'user_request_id' => $id,
                        'syarat_id' => $jawaban['syarat_id'],
                        'jawaban' => $jawaban['jawaban'],
                        'created_at' => now(),
                    ]);
                }
            }

            // Update catatan
            if ($request->has('catatan')) {
                DB::table('users_request')
                    ->where('id', $id)
                    ->update(['catatan' => $request->catatan]);
            }

            // Submit (change status to PENDING)
            if ($request->input('submit', false)) {
                DB::table('users_request')
                    ->where('id', $id)
                    ->update([
                        'status' => 'PENDING',
                        'updated_at' => now(),
                    ]);
            }

            DB::commit();

            return $this->success([
                'id' => $id,
                'status' => $request->input('submit', false) ? 'PENDING' : 'DRAFT',
            ], 'Pengajuan berhasil diupdate');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error('Gagal update pengajuan: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Delete pengajuan (DRAFT only)
     * DELETE /api/pengajuan/{id}
     */
    public function destroy(Request $request, string $id)
    {
        $user = $request->user();

        $pengajuan = DB::table('users_request')
            ->where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!$pengajuan) {
            return $this->notFound('Pengajuan tidak ditemukan');
        }

        if ($pengajuan->status !== 'DRAFT') {
            return $this->error('Hanya pengajuan berstatus DRAFT yang dapat dihapus', 400);
        }

        DB::beginTransaction();
        try {
            // Delete answers
            DB::table('users_request_answers')
                ->where('user_request_id', $id)
                ->delete();

            // Delete files
            $berkas = DB::table('users_berkas')
                ->where('user_request_id', $id)
                ->get();

            foreach ($berkas as $file) {
                Storage::disk('public')->delete($file->file_path);
            }

            DB::table('users_berkas')
                ->where('user_request_id', $id)
                ->delete();

            // Delete request
            DB::table('users_request')
                ->where('id', $id)
                ->delete();

            DB::commit();

            return $this->success(null, 'Pengajuan berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error('Gagal hapus pengajuan: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Upload file for pengajuan
     * POST /api/pengajuan/{id}/upload
     */
    public function upload(Request $request, string $id)
    {
        $user = $request->user();

        // Verify ownership
        $pengajuan = DB::table('users_request')
            ->where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!$pengajuan) {
            return $this->notFound('Pengajuan tidak ditemukan');
        }

        $request->validate([
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120', // 5MB max
            'syarat_id' => 'nullable|integer',
            'tipe' => 'nullable|string|in:pdf,image',
        ]);

        try {
            $file = $request->file('file');
            $filename = $file->getClientOriginalName();
            $path = $file->store('uploads/pengajuan/' . $id, 'public');

            $berkasId = DB::table('users_berkas')->insertGetId([
                'user_request_id' => $id,
                'user_id' => $user->id,
                'syarat_id' => $request->syarat_id,
                'nama_berkas' => $filename,
                'file_path' => $path,
                'tipe' => $request->tipe ?? ($file->getClientMimeType() === 'application/pdf' ? 'pdf' : 'image'),
                'created_at' => now(),
            ]);

            return $this->success([
                'id' => $berkasId,
                'nama' => $filename,
                'path' => $path,
            ], 'File berhasil diupload', 201);
        } catch (\Exception $e) {
            return $this->error('Gagal upload file: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get pengajuan tracking/history
     * GET /api/pengajuan/{id}/tracking
     */
    public function tracking(Request $request, string $id)
    {
        $user = $request->user();

        // Verify ownership
        $pengajuan = DB::table('users_request')
            ->where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!$pengajuan) {
            return $this->notFound('Pengajuan tidak ditemukan');
        }

        // Get activities/log
        $activities = DB::table('activities')
            ->where('user_request_id', $id)
            ->select([
                'id',
                'activity as deskripsi',
                'created_at as waktu',
                'tipe',
            ])
            ->orderBy('created_at', 'asc')
            ->get();

        // Format activities
        foreach ($activities as $activity) {
            $activity->waktu = date('d M Y H:i', strtotime($activity->waktu));
        }

        return $this->success([
            'pengajuan_id' => $id,
            'no_pengajuan' => $pengajuan->no_pengajuan,
            'current_status' => $this->formatStatus($pengajuan->status),
            'activities' => $activities,
        ], 'Tracking pengajuan');
    }

    /**
     * Format status for display
     */
    private function formatStatus(string $status): array
    {
        $statusMap = [
            'DRAFT' => ['label' => 'Draft', 'color' => 'gray', 'icon' => 'draft'],
            'PENDING' => ['label' => 'Menunggu', 'color' => 'yellow', 'icon' => 'pending'],
            'UNCHECK' => ['label' => 'Belum Dicek', 'color' => 'orange', 'icon' => 'uncheck'],
            'DITERIMA' => ['label' => 'Diterima', 'color' => 'blue', 'icon' => 'diterima'],
            'DIPROSES' => ['label' => 'Diproses', 'color' => 'blue', 'icon' => 'diproses'],
            'SUKSES' => ['label' => 'Selesai', 'color' => 'green', 'icon' => 'sukses'],
            'DITOLAK' => ['label' => 'Ditolak', 'color' => 'red', 'icon' => 'ditolak'],
            'BATAL' => ['label' => 'Dibatalkan', 'color' => 'gray', 'icon' => 'batal'],
        ];

        return $statusMap[$status] ?? ['label' => $status, 'color' => 'gray', 'icon' => 'default'];
    }
}
