<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MadrasahController extends Controller
{
    /**
     * Display the Laporan Madrasah page.
     */
    public function index()
    {
        // Get madrasah categories for dropdown
        $madrasahCategories = [
            'mi' => 'Madrasah Ibtidaiyah (MI)',
            'mts' => 'Madrasah Tsanawiyah (MTs)',
            'ma' => 'Madrasah Aliyah (MA)',
            'man' => 'Madrasah Aliyah Negeri (MAN)',
            'mtsn' => 'Madrasah Tsanawiyah Negeri (MTsN)',
            'min' => 'Madrasah Ibtidaiyah Negeri (MIN)',
        ];

        // Get all madrasah from ktd_madrasah table
        $madrasahs = DB::table('ktd_madrasah')
            ->where('status', 1)
            ->orderBy('kategori')
            ->orderBy('nama')
            ->get();

        // Get departments for dropdown
        $departments = DB::table('ktd_department')
            ->whereIn('kategori', ['kantor', 'kua'])
            ->where('status', '!=', 0)
            ->orderBy('nama')
            ->get();

        // Get status options
        $statusLembaga = ['Negeri', 'Swasta'];
        $waktuBelajar = ['Pagi', 'Siang', 'Malam'];
        $komiteLembaga = ['Sudah Terbentuk', 'Belum Terbentuk'];
        $statusKKM = ['Induk', 'Anggota'];

        return view('admin.madrasah.index', [
            'title' => 'Manajemen Madrasah - SILATAR Admin',
            'breadcrumbs' => [
                ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['label' => 'Manajemen Madrasah', 'url' => null],
            ],
            'madrasahCategories' => $madrasahCategories,
            'madrasahs' => $madrasahs,
            'departments' => $departments,
            'statusLembaga' => $statusLembaga,
            'waktuBelajar' => $waktuBelajar,
            'komiteLembaga' => $komiteLembaga,
            'statusKKM' => $statusKKM,
        ]);
    }

    /**
     * Store or update madrasah profile.
     */
    public function saveProfile(Request $request)
    {
        $validated = $request->validate([
            'madrasah_id' => 'nullable|integer',
            'dept_id' => 'nullable|integer',
            'nama' => 'required|string|max:255',
            'nsm' => 'nullable|string|max:50',
            'npsm' => 'nullable|string|max:50',
            'status_lembaga' => 'nullable|string',
            'kategori' => 'required|in:mi,mts,ma,man,mtsn,min,ra',
            'jalan' => 'nullable|string|max:255',
            'jorong' => 'nullable|string|max:100',
            'nagari' => 'nullable|string|max:100',
            'kecamatan' => 'nullable|string|max:100',
            'koordinat' => 'nullable|string|max:100',
            'telepon' => 'nullable|string|max:20',
            'email' => 'nullable|email',
            'website' => 'nullable|url',
            'waktu_belajar' => 'nullable|string',
            'visi' => 'nullable|string',
            'sk_pendirian' => 'nullable|string|max:100',
            'tanggal_sk' => 'nullable|date',
            'komite_lembaga' => 'nullable|string',
            'akreditasi' => 'nullable|string|max:10',
            'tanggal_akreditasi' => 'nullable|date',
            'status_kkm' => 'nullable|string',
            'jarak_pusat_provinsi' => 'nullable|string|max:50',
            'jarak_pusat_kabupaten' => 'nullable|string|max:50',
            'jarak_kecamatan' => 'nullable|string|max:50',
            'jarak_kanwil_kemenag' => 'nullable|string|max:50',
            'jarak_kemenag_kab' => 'nullable|string|max:50',
            'jarak_kua' => 'nullable|string|max:50',
        ]);

        $data = [
            'dept_id' => $validated['dept_id'] ?? null,
            'nama' => $validated['nama'],
            'nsm' => $validated['nsm'] ?? null,
            'npsm' => $validated['npsm'] ?? null,
            'status_lembaga' => $validated['status_lembaga'] ?? null,
            'kategori' => $validated['kategori'],
            'jalan' => $validated['jalan'] ?? null,
            'jorong' => $validated['jorong'] ?? null,
            'nagari' => $validated['nagari'] ?? null,
            'kecamatan' => $validated['kecamatan'] ?? null,
            'koordinat' => $validated['koordinat'] ?? null,
            'telepon' => $validated['telepon'] ?? null,
            'email' => $validated['email'] ?? null,
            'website' => $validated['website'] ?? null,
            'waktu_belajar' => $validated['waktu_belajar'] ?? null,
            'visi' => $validated['visi'] ?? null,
            'sk_pendirian' => $validated['sk_pendirian'] ?? null,
            'tanggal_sk' => $validated['tanggal_sk'] ?? null,
            'komite_lembaga' => $validated['komite_lembaga'] ?? null,
            'akreditasi' => $validated['akreditasi'] ?? null,
            'tanggal_akreditasi' => $validated['tanggal_akreditasi'] ?? null,
            'status_kkm' => $validated['status_kkm'] ?? null,
            'jarak_pusat_provinsi' => $validated['jarak_pusat_provinsi'] ?? null,
            'jarak_pusat_kabupaten' => $validated['jarak_pusat_kabupaten'] ?? null,
            'jarak_kecamatan' => $validated['jarak_kecamatan'] ?? null,
            'jarak_kanwil_kemenag' => $validated['jarak_kanwil_kemenag'] ?? null,
            'jarak_kemenag_kab' => $validated['jarak_kemenag_kab'] ?? null,
            'jarak_kua' => $validated['jarak_kua'] ?? null,
            'updated_at' => now(),
        ];

        // Check if madrasah exists
        $madrasahId = $validated['madrasah_id'] ?? null;
        $existing = null;

        if ($madrasahId) {
            $existing = DB::table('ktd_madrasah')->where('id', $madrasahId)->first();
        }

        if ($existing) {
            // Update existing
            DB::table('ktd_madrasah')
                ->where('id', $existing->id)
                ->update($data);

            return redirect()->back()->with('success', 'Data madrasah berhasil diperbarui!');
        } else {
            // Create new
            $data['status'] = 1;
            $data['created_at'] = now();
            $insertId = DB::table('ktd_madrasah')->insertGetId($data);

            return redirect()->back()->with('success', 'Data madrasah berhasil disimpan!');
        }
    }

    /**
     * Get madrasah profile by ID (for AJAX).
     */
    public function getProfile($id)
    {
        $madrasah = DB::table('ktd_madrasah')
            ->where('id', $id)
            ->first();

        if (!$madrasah) {
            return response()->json(['error' => 'Madrasah tidak ditemukan'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $madrasah,
        ]);
    }

    /**
     * Delete madrasah.
     */
    public function destroy($id)
    {
        $madrasah = DB::table('ktd_madrasah')->where('id', $id)->first();

        if (!$madrasah) {
            return redirect()->back()->with('error', 'Madrasah tidak ditemukan');
        }

        // Soft delete - set status to 0
        DB::table('ktd_madrasah')
            ->where('id', $id)
            ->update(['status' => 0, 'updated_at' => now()]);

        return redirect()->back()->with('success', 'Madrasah berhasil dihapus!');
    }

    /**
     * Assign user to madrasah.
     */
    public function assignUser(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'madrasah_id' => 'required|exists:ktd_madrasah,id',
        ]);

        DB::table('users')
            ->where('id', $validated['user_id'])
            ->update([
                'madrasah_id' => $validated['madrasah_id'],
                'updated_at' => now(),
            ]);

        return redirect()->back()->with('success', 'User berhasil di-assign ke madrasah!');
    }
}
