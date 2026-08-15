<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UnitController extends Controller
{
    /**
     * Display a listing of departments.
     */
    public function index(Request $request)
    {
        // Only admin/superadmin/kepala can access Unit Kerja
        $currentUser = auth()->user();
        if (!in_array($currentUser->role, ['admin', 'superadmin', 'kepala'])) {
            abort(403, 'Anda tidak memiliki akses ke menu ini.');
        }

        $query = DB::table('ktd_department as d')
            ->select([
                'd.*',
                DB::raw('(SELECT COUNT(*) FROM users WHERE users.dept_id = d.id) as user_count')
            ]);

        // Search filter
        $search = $request->input('search');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('d.nama', 'like', "%{$search}%")
                  ->orWhere('d.alamat', 'like', "%{$search}%")
                  ->orWhere('d.npsm', 'like', "%{$search}%");
            });
        }

        // Category filter
        $kategori = $request->input('kategori');
        if ($kategori) {
            $query->where('d.kategori', $kategori);
        }

        // Status filter
        $status = $request->input('status');
        if ($status !== null && $status !== '') {
            $query->where('d.status', $status);
        }

        $departments = $query->orderBy('d.nama')->paginate(15);

        // Get category options
        $kategoriOptions = [
            'kantor' => 'Kantor Pusat',
            'kua' => 'KUA',
            'mi' => 'MI (Madrasah Ibtidaiyah)',
            'mts' => 'MTs (Madrasah Tsanawiyah)',
            'mtsn' => 'MTsN (MTs Negeri)',
            'ma' => 'MA (Madrasah Aliyah)',
            'man' => 'MAN (Madrasah Aliyah Negeri)',
            'min' => 'MIN (Madrasah Ibtidaiyah Negeri)',
            'slb' => 'SLB',
            '的其他' => '其他',
        ];

        $statusOptions = [
            1 => 'Aktif (Intern)',
            2 => 'Aktif (Satker)',
            0 => 'Tidak Aktif',
        ];

        return view('admin.units.index', [
            'title' => 'Manajemen Unit Kerja - SILATAR Admin',
            'departments' => $departments,
            'kategoriOptions' => $kategoriOptions,
            'statusOptions' => $statusOptions,
            'filters' => [
                'search' => $search,
                'kategori' => $kategori,
                'status' => $status,
            ],
        ]);
    }

    /**
     * Show the form for creating a new department.
     */
    public function create()
    {
        $kategoriOptions = [
            'kantor' => 'Kantor Pusat',
            'kua' => 'KUA',
            'mi' => 'MI (Madrasah Ibtidaiyah)',
            'mts' => 'MTs (Madrasah Tsanawiyah)',
            'mtsn' => 'MTsN (MTs Negeri)',
            'ma' => 'MA (Madrasah Aliyah)',
            'man' => 'MAN (Madrasah Aliyah Negeri)',
            'min' => 'MIN (Madrasah Ibtidaiyah Negeri)',
            'slb' => 'SLB',
        ];

        return view('admin.units.create', [
            'title' => 'Tambah Unit Kerja - SILATAR Admin',
            'kategoriOptions' => $kategoriOptions,
        ]);
    }

    /**
     * Store a newly created department.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'kategori' => 'required|string|max:50',
            'npsm' => 'nullable|string|max:50',
            'alamat' => 'nullable|string|max:255',
            'kecamatan' => 'nullable|string|max:100',
            'kabupaten' => 'nullable|string|max:100',
            'telepon' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'status' => 'nullable|integer|in:0,1,2',
        ]);

        $data = [
            'nama' => $validated['nama'],
            'kategori' => $validated['kategori'],
            'npsm' => $validated['npsm'] ?? null,
            'alamat' => $validated['alamat'] ?? null,
            'kecamatan' => $validated['kecamatan'] ?? null,
            'kabupaten' => $validated['kabupaten'] ?? null,
            'telepon' => $validated['telepon'] ?? null,
            'email' => $validated['email'] ?? null,
            'status' => $validated['status'] ?? 1,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        DB::table('ktd_department')->insert($data);

        return redirect()->route('admin.units.index')
            ->with('success', 'Unit kerja berhasil ditambahkan.');
    }

    /**
     * Show the form for editing a department.
     */
    public function edit($id)
    {
        $department = DB::table('ktd_department')->where('id', $id)->first();

        if (!$department) {
            abort(404, 'Unit kerja tidak ditemukan.');
        }

        $kategoriOptions = [
            'kantor' => 'Kantor Pusat',
            'kua' => 'KUA',
            'mi' => 'MI (Madrasah Ibtidaiyah)',
            'mts' => 'MTs (Madrasah Tsanawiyah)',
            'mtsn' => 'MTsN (MTs Negeri)',
            'ma' => 'MA (Madrasah Aliyah)',
            'man' => 'MAN (Madrasah Aliyah Negeri)',
            'min' => 'MIN (Madrasah Ibtidaiyah Negeri)',
            'slb' => 'SLB',
        ];

        return view('admin.units.edit', [
            'title' => 'Edit Unit Kerja - SILATAR Admin',
            'department' => $department,
            'kategoriOptions' => $kategoriOptions,
        ]);
    }

    /**
     * Update the specified department.
     */
    public function update(Request $request, $id)
    {
        $department = DB::table('ktd_department')->where('id', $id)->first();

        if (!$department) {
            abort(404, 'Unit kerja tidak ditemukan.');
        }

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'kategori' => 'required|string|max:50',
            'npsm' => 'nullable|string|max:50',
            'alamat' => 'nullable|string|max:255',
            'kecamatan' => 'nullable|string|max:100',
            'kabupaten' => 'nullable|string|max:100',
            'telepon' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'status' => 'nullable|integer|in:0,1,2',
        ]);

        $data = [
            'nama' => $validated['nama'],
            'kategori' => $validated['kategori'],
            'npsm' => $validated['npsm'] ?? null,
            'alamat' => $validated['alamat'] ?? null,
            'kecamatan' => $validated['kecamatan'] ?? null,
            'kabupaten' => $validated['kabupaten'] ?? null,
            'telepon' => $validated['telepon'] ?? null,
            'email' => $validated['email'] ?? null,
            'status' => $validated['status'] ?? 1,
            'updated_at' => now(),
        ];

        DB::table('ktd_department')->where('id', $id)->update($data);

        return redirect()->route('admin.units.index')
            ->with('success', 'Unit kerja berhasil diperbarui.');
    }

    /**
     * Remove the specified department.
     */
    public function destroy($id)
    {
        $department = DB::table('ktd_department')->where('id', $id)->first();

        if (!$department) {
            return response()->json(['success' => false, 'message' => 'Unit kerja tidak ditemukan.'], 404);
        }

        // Check if department has users
        $userCount = DB::table('users')->where('dept_id', $id)->count();
        if ($userCount > 0) {
            return response()->json([
                'success' => false,
                'message' => "Unit kerja tidak dapat dihapus karena masih memiliki {$userCount} pengguna."
            ], 400);
        }

        DB::table('ktd_department')->where('id', $id)->update(['status' => 0, 'updated_at' => now()]);

        return response()->json(['success' => true, 'message' => 'Unit kerja berhasil dihapus.']);
    }

    /**
     * Get department by ID (API).
     */
    public function show($id)
    {
        $department = DB::table('ktd_department')->where('id', $id)->first();

        if (!$department) {
            return response()->json(['success' => false, 'message' => 'Unit kerja tidak ditemukan.'], 404);
        }

        return response()->json(['success' => true, 'department' => $department]);
    }
}
