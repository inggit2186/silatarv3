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

        // Get all madrasah units
        $madrasahs = DB::table('ktd_department')
            ->whereIn('kategori', ['mi', 'mts', 'ma', 'man', 'mtsn', 'min'])
            ->where('status', '!=', 0)
            ->orderBy('nama')
            ->get();

        // Get status options
        $statusLembaga = ['Negeri', 'Swasta'];
        $waktuBelajar = ['Pagi', 'Siang', 'Malam'];
        $komiteLembaga = ['Sudah Terbentuk', 'Belum Terbentuk'];
        $statusKKM = ['Induk', 'Anggota'];

        return view('admin.madrasah.index', [
            'title' => 'Laporan Madrasah - SILATAR Admin',
            'breadcrumbs' => [
                ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['label' => 'Laporan Madrasah', 'url' => null],
            ],
            'madrasahCategories' => $madrasahCategories,
            'madrasahs' => $madrasahs,
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
            'dept_id' => 'nullable|string',
            'nama' => 'required|string|max:255',
            'nsm' => 'required|string|max:50',
            'npsm' => 'required|string|max:50',
            'status_lembaga' => 'nullable|string',
            'kategori' => 'nullable|string',
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
        ]);

        // Check if madrasah exists
        $existing = DB::table('ktd_department')
            ->where('id', $validated['dept_id'] ?? null)
            ->first();

        if ($existing) {
            // Update existing
            DB::table('ktd_department')
                ->where('id', $existing->id)
                ->update([
                    'nama' => $validated['nama'],
                    'nsm' => $validated['nsm'],
                    'npsm' => $validated['npsm'],
                    'status_lembaga' => $validated['status_lembaga'] ?? null,
                    'kategori' => $validated['kategori'] ?? $existing->kategori ?? null,
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
                    'updated_at' => now(),
                ]);

            return redirect()->back()->with('success', 'Data madrasah berhasil diperbarui!');
        } else {
            // Create new
            $insertId = DB::table('ktd_department')->insertGetId([
                'nama' => $validated['nama'],
                'nsm' => $validated['nsm'],
                'npsm' => $validated['npsm'],
                'status_lembaga' => $validated['status_lembaga'] ?? 'Negeri',
                'kategori' => $validated['kategori'] ?? 'mts',
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
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return redirect()->back()->with('success', 'Data madrasah berhasil disimpan!');
        }
    }

    /**
     * Get madrasah profile by ID (for AJAX).
     */
    public function getProfile($id)
    {
        $madrasah = DB::table('ktd_department')
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
}
