<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LayananController extends BaseApiController
{
    /**
     * Get all layanan (katalog)
     * GET /api/layanan
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 12);
        $search = $request->input('search');
        $kategori = $request->input('kategori');
        $unit_id = $request->input('unit_id');

        $query = DB::table('ktd_layanan')
            ->select([
                'id',
                'nama_layanan as nama',
                'deskripsi',
                'kategori',
                'estimasi',
                'ikon',
                'is_active',
            ])
            ->where('is_active', 1)
            ->orderBy('nama_layanan');

        if ($search) {
            $query->where('nama_layanan', 'like', "%{$search}%");
        }

        if ($kategori) {
            $query->where('kategori', $kategori);
        }

        if ($unit_id) {
            $query->where('unit_id', $unit_id);
        }

        $layanan = $query->paginate($perPage);

        return $this->successPaginated($layanan, 'Daftar layanan');
    }

    /**
     * Get single layanan detail
     * GET /api/layanan/{id}
     */
    public function show(string $id)
    {
        $layanan = DB::table('ktd_layanan')
            ->select([
                'id',
                'nama_layanan as nama',
                'deskripsi',
                'kategori',
                'estimasi',
                'ikon',
                'biaya',
                'unit_id',
                'is_active',
                'created_at',
            ])
            ->where('id', $id)
            ->where('is_active', 1)
            ->first();

        if (!$layanan) {
            return $this->notFound('Layanan tidak ditemukan');
        }

        // Get unit info
        $unit = DB::table('ktd_department')
            ->where('id', $layanan->unit_id)
            ->first(['id', 'nama_department as nama']);

        $layanan->unit = $unit;

        return $this->success($layanan, 'Detail layanan');
    }

    /**
     * Get syarat/layanan requirements
     * GET /api/layanan/{id}/syarat
     */
    public function syarat(string $id)
    {
        // Verify layanan exists
        $layanan = DB::table('ktd_layanan')
            ->where('id', $id)
            ->where('is_active', 1)
            ->first();

        if (!$layanan) {
            return $this->notFound('Layanan tidak ditemukan');
        }

        $syarat = DB::table('ktd_syarat')
            ->select([
                'id',
                'nama_syarat as nama',
                'tipe',
                'deskripsi',
                'is_required',
                'urutan',
            ])
            ->where('layanan_id', $id)
            ->orderBy('urutan')
            ->get();

        return $this->success([
            'layanan_id' => $id,
            'layanan_nama' => $layanan->nama_layanan,
            'syarat' => $syarat,
        ], 'Daftar persyaratan');
    }

    /**
     * Get all units (satuan kerja)
     * GET /api/units
     */
    public function units(Request $request)
    {
        $units = DB::table('ktd_department')
            ->select([
                'id',
                'nama_department as nama',
                'kode',
                'alamat',
                'no_telp',
                'email',
            ])
            ->where('is_active', 1)
            ->orderBy('nama_department')
            ->get();

        return $this->success($units, 'Daftar satuan kerja');
    }
}
