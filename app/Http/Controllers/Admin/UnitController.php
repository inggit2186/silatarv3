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
        $this->authorizeUnitAccess();

        $query = DB::table('ktd_department as d')
            ->select([
                'd.*',
                DB::raw('(SELECT COUNT(*) FROM users WHERE users.dept_id = d.id) as user_count'),
                DB::raw("(SELECT COUNT(*) FROM users u WHERE u.dept_id = d.id AND u.kat_jabatan = 'kepala' AND u.status = 1) as kepala_count"),
                DB::raw("(SELECT GROUP_CONCAT(u.name ORDER BY u.name SEPARATOR ', ') FROM users u WHERE u.dept_id = d.id AND u.kat_jabatan = 'kepala' AND u.status = 1) as kepala_names"),
                DB::raw('(SELECT COUNT(*) FROM plt_plh p WHERE p.dept_id_plh = d.id) as plt_plh_count'),
                DB::raw("(SELECT GROUP_CONCAT(u.name ORDER BY p.id DESC SEPARATOR ', ') FROM plt_plh p JOIN users u ON u.id = p.user_id WHERE p.dept_id_plh = d.id) as plt_plh_names"),
            ]);

        // Search filter
        $search = $request->input('search');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('d.nama', 'like', "%{$search}%")
                    ->orWhere('d.kode', 'like', "%{$search}%")
                    ->orWhere('d.deskripsi', 'like', "%{$search}%")
                    ->orWhere('d.npsm', 'like', "%{$search}%")
                    ->orWhere('d.nsm', 'like', "%{$search}%")
                    ->orWhere('d.jalan', 'like', "%{$search}%")
                    ->orWhere('d.jorong', 'like', "%{$search}%")
                    ->orWhere('d.nagari', 'like', "%{$search}%")
                    ->orWhere('d.kecamatan', 'like', "%{$search}%");
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

        $statusOptions = [
            1 => 'Aktif (Intern)',
            2 => 'Aktif (Satker)',
            0 => 'Tidak Aktif',
        ];

        return view('admin.units.index', [
            'title' => 'Manajemen Unit Kerja - SILATAR Admin',
            'departments' => $departments,
            'kategoriOptions' => $this->kategoriOptions(),
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
        return view('admin.units.create', [
            'title' => 'Tambah Unit Kerja - SILATAR Admin',
            'kategoriOptions' => $this->kategoriOptions(),
            'options' => $this->fieldOptions(),
        ]);
    }

    /**
     * Store a newly created department.
     */
    public function store(Request $request)
    {
        $validated = $request->validate($this->rules());

        $data = $this->payload($validated);
        $data['created_at'] = now();
        $data['updated_at'] = now();

        DB::table('ktd_department')->insert($data);

        return redirect()->route('admin.units.index')
            ->with('success', 'Unit kerja berhasil ditambahkan.');
    }

    /**
     * Show the form for editing a department.
     */
    public function edit(Request $request, $id)
    {
        $department = DB::table('ktd_department')->where('id', $id)->first();

        if (! $department) {
            abort(404, 'Unit kerja tidak ditemukan.');
        }

        $pimpinan = $this->pimpinan($department->id);
        $kepalaIds = $pimpinan['kepala']->pluck('id')->all();
        $pltUserIds = $pimpinan['plt_plh']->pluck('id')->all();

        // Kandidat kepala: hanya pegawai dengan dept_id yang sama, yang belum menjadi kepala
        $kandidatKepala = DB::table('users')
            ->where('dept_id', $department->id)
            ->when($kepalaIds, fn ($q) => $q->whereNotIn('id', $kepalaIds))
            ->orderBy('name')
            ->get(['id', 'name', 'nomor_induk', 'kat_jabatan', 'role'])
            ->map(fn ($u) => [
                'id' => $u->id,
                'name' => $u->name,
                'meta' => $this->metaPegawai($u->role, $u->nomor_induk, $u->kat_jabatan),
            ])->values();

        // Kandidat PLH/PLT: seluruh pegawai dengan status = 1
        $kandidatPlt = DB::table('users as u')
            ->leftJoin('ktd_department as d', 'd.id', '=', 'u.dept_id')
            ->where('u.status', 1)
            ->when($pltUserIds, fn ($q) => $q->whereNotIn('u.id', $pltUserIds))
            ->orderBy('u.name')
            ->get(['u.id', 'u.name', 'u.nomor_induk', 'u.kat_jabatan', 'u.role', 'd.nama as dept_nama'])
            ->map(fn ($u) => [
                'id' => $u->id,
                'name' => $u->name,
                'meta' => $this->metaPegawai($u->role, $u->nomor_induk, $u->kat_jabatan, $u->dept_nama),
            ])->values();

        return view('admin.units.edit', [
            'title' => 'Edit Unit Kerja - SILATAR Admin',
            'department' => $department,
            'kategoriOptions' => $this->kategoriOptions(),
            'options' => $this->fieldOptions(),
            'pimpinan' => $pimpinan,
            'kandidatKepala' => $kandidatKepala,
            'kandidatPlt' => $kandidatPlt,
        ]);
    }

    /**
     * Ringkasan keterangan pegawai untuk daftar pencarian.
     */
    private function metaPegawai($role, $nomorInduk, $katJabatan, $unitKerja = null): string
    {
        $parts = array_filter([
            $role,
            $nomorInduk ? 'NIP/NUPTK '.$nomorInduk : null,
            $katJabatan,
            $unitKerja,
        ]);

        return implode(' · ', $parts);
    }

    /**
     * Tandai / lepas penandaan Kepala unit kerja.
     *
     * Penandaan mengubah data pegawai: kat_jabatan menjadi 'kepala' dan status menjadi 1.
     * Pelepasan hanya mengubah kat_jabatan menjadi 'ex-kepala', status pegawai tidak diubah.
     */
    public function updateKepala(Request $request, $id)
    {
        $this->authorizeUnitAccess();

        $department = DB::table('ktd_department')->where('id', $id)->first();

        if (! $department) {
            abort(404, 'Unit kerja tidak ditemukan.');
        }

        $validated = $request->validate([
            'kepala_ids' => 'nullable|array',
            'kepala_ids.*' => 'integer|exists:users,id',
        ]);

        // Hanya pegawai milik unit ini yang boleh ditandai sebagai kepala unit ini
        $terpilih = DB::table('users')
            ->where('dept_id', $department->id)
            ->whereIn('id', $validated['kepala_ids'] ?? [])
            ->pluck('id')
            ->all();

        $sekarang = DB::table('users')
            ->where('dept_id', $department->id)
            ->where('kat_jabatan', 'kepala')
            ->where('status', 1)
            ->pluck('id')
            ->all();

        foreach (array_diff($terpilih, $sekarang) as $userId) {
            DB::table('users')->where('id', $userId)->update([
                'kat_jabatan' => 'kepala',
                'status' => 1,
            ]);
        }

        foreach (array_diff($sekarang, $terpilih) as $userId) {
            DB::table('users')->where('id', $userId)->update([
                'kat_jabatan' => 'ex-kepala',
            ]);
        }

        return back()->with('success', 'Kepala unit kerja berhasil diperbarui.');
    }

    /**
     * Tambah PLH/PLT kepala untuk unit kerja.
     */
    public function storePltPlh(Request $request, $id)
    {
        $this->authorizeUnitAccess();

        $department = DB::table('ktd_department')->where('id', $id)->first();

        if (! $department) {
            abort(404, 'Unit kerja tidak ditemukan.');
        }

        $validated = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
        ]);

        $userId = (int) $validated['user_id'];

        // PLH/PLT hanya boleh diisi pegawai dengan status = 1
        $pegawai = DB::table('users')->where('id', $userId)->where('status', 1)->first();

        if (! $pegawai) {
            return back()->with('error', 'PLH/PLT hanya dapat diisi pegawai dengan status = 1.');
        }

        $sudahAda = DB::table('plt_plh')
            ->where('dept_id_plh', $department->id)
            ->where('user_id', $userId)
            ->exists();

        if ($sudahAda) {
            return back()->with('error', 'Pegawai tersebut sudah tercatat sebagai PLH/PLT unit ini.');
        }

        DB::table('plt_plh')->insert([
            'user_id' => $userId,
            'dept_id_plh' => $department->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'PLH/PLT kepala berhasil ditambahkan.');
    }

    /**
     * Hapus PLH/PLT kepala dari unit kerja.
     */
    public function destroyPltPlh($id, $pltId)
    {
        $this->authorizeUnitAccess();

        $department = DB::table('ktd_department')->where('id', $id)->first();

        if (! $department) {
            abort(404, 'Unit kerja tidak ditemukan.');
        }

        DB::table('plt_plh')
            ->where('id', $pltId)
            ->where('dept_id_plh', $department->id)
            ->delete();

        return back()->with('success', 'PLH/PLT kepala berhasil dihapus.');
    }

    /**
     * Hanya admin/superadmin/kepala yang boleh mengelola unit kerja.
     */
    private function authorizeUnitAccess(): void
    {
        $currentUser = auth()->user();
        if (! $currentUser || ! in_array($currentUser->role, ['admin', 'superadmin', 'kepala'])) {
            abort(403, 'Anda tidak memiliki akses ke menu ini.');
        }
    }

    /**
     * Kepala unit kerja (kat_jabatan=kepala, status=1) dan PLH/PLT kepala.
     */
    private function pimpinan($deptId): array
    {
        $kepala = DB::table('users')
            ->where('dept_id', $deptId)
            ->where('kat_jabatan', 'kepala')
            ->where('status', 1)
            ->orderBy('name')
            ->get(['id', 'name', 'nip', 'nomor_induk', 'role']);

        $pltPlh = DB::table('plt_plh as p')
            ->join('users', 'users.id', '=', 'p.user_id')
            ->where('p.dept_id_plh', $deptId)
            ->orderByDesc('p.id')
            ->get(['users.id', 'users.name', 'users.nip', 'users.nomor_induk', 'users.role', 'p.id as plt_plh_id']);

        return [
            'kepala' => $kepala,
            'plt_plh' => $pltPlh,
        ];
    }

    /**
     * Update the specified department.
     */
    public function update(Request $request, $id)
    {
        $department = DB::table('ktd_department')->where('id', $id)->first();

        if (! $department) {
            abort(404, 'Unit kerja tidak ditemukan.');
        }

        $validated = $request->validate($this->rules());

        DB::table('ktd_department')->where('id', $id)->update($this->payload($validated));

        return redirect()->route('admin.units.index')
            ->with('success', 'Unit kerja berhasil diperbarui.');
    }

    /**
     * Remove the specified department.
     */
    public function destroy($id)
    {
        $department = DB::table('ktd_department')->where('id', $id)->first();

        if (! $department) {
            return response()->json(['success' => false, 'message' => 'Unit kerja tidak ditemukan.'], 404);
        }

        // Check if department has users
        $userCount = DB::table('users')->where('dept_id', $id)->count();
        if ($userCount > 0) {
            return response()->json([
                'success' => false,
                'message' => "Unit kerja tidak dapat dihapus karena masih memiliki {$userCount} pengguna.",
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

        if (! $department) {
            return response()->json(['success' => false, 'message' => 'Unit kerja tidak ditemukan.'], 404);
        }

        return response()->json(['success' => true, 'department' => $department]);
    }

    /**
     * Validation rules mapped to the ktd_department columns.
     */
    private function rules(): array
    {
        return [
            'nama' => 'required|string|max:255',
            'kode' => 'required|string|max:25',
            'kategori' => 'required|string|max:50',
            'deskripsi' => 'nullable|string',
            'status' => 'nullable|integer|in:0,1,2',

            'nsm' => 'nullable|string|max:50',
            'npsm' => 'nullable|string|max:50',
            'status_lembaga' => 'nullable|string|max:20',
            'akreditasi' => 'nullable|string|max:10',
            'tanggal_akreditasi' => 'nullable|date',
            'komite_lembaga' => 'nullable|string|max:30',
            'status_kkm' => 'nullable|string|max:20',
            'sk_pendirian' => 'nullable|string|max:255',
            'tanggal_sk' => 'nullable|date',
            'visi' => 'nullable|string',

            'jalan' => 'nullable|string|max:255',
            'jorong' => 'nullable|string|max:255',
            'nagari' => 'nullable|string|max:255',
            'kecamatan' => 'nullable|string|max:255',
            'koordinat' => 'nullable|string|max:100',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'radius' => 'nullable|integer',
            'gateway_ip' => 'nullable|string|max:255',

            'telepon' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|string|max:255',

            'jam_masuk' => 'nullable|string|max:255',
            'jam_pulang' => 'nullable|string|max:255',
            'hari_kerja' => 'nullable|integer|between:1,7',
            'waktu_belajar' => 'nullable|string|max:20',

            'jarak_pusat_provinsi' => 'nullable|string|max:50',
            'jarak_pusat_kabupaten' => 'nullable|string|max:50',
            'jarak_kecamatan' => 'nullable|string|max:50',
            'jarak_kanwil_kemenag' => 'nullable|string|max:50',
            'jarak_kemenag_kab' => 'nullable|string|max:50',
            'jarak_kua' => 'nullable|string|max:50',
            'jarak_ra_terdekat' => 'nullable|string|max:50',
            'jarak_mi_terdekat' => 'nullable|string|max:50',
            'jarak_mts_terdekat' => 'nullable|string|max:50',
            'jarak_ma_terdekat' => 'nullable|string|max:50',
            'jarak_pontren_terdekat' => 'nullable|string|max:50',
            'jarak_tk_terdekat' => 'nullable|string|max:50',
            'jarak_sd_terdekat' => 'nullable|string|max:50',
            'jarak_smp_terdekat' => 'nullable|string|max:50',
            'jarak_sma_terdekat' => 'nullable|string|max:50',
        ];
    }

    /**
     * Build the database payload from validated input.
     */
    private function payload(array $validated): array
    {
        $data = [];

        foreach (array_keys($this->rules()) as $field) {
            $data[$field] = $validated[$field] ?? null;
        }

        // Defaults for columns that are NOT NULL
        $data['hari_kerja'] = $data['hari_kerja'] ?? 5;
        $data['status'] = $data['status'] ?? 1;

        return $data;
    }

    /**
     * Options for the kategori select.
     */
    private function kategoriOptions(): array
    {
        return [
            'kantor' => 'Kantor Pusat',
            'kua' => 'KUA',
            'mi' => 'MI (Madrasah Ibtidaiyah)',
            'mts' => 'MTs (Madrasah Tsanawiyah)',
            'mtsn' => 'MTsN (MTs Negeri)',
            'ma' => 'MA (Madrasah Aliyah)',
            'man' => 'MAN (Madrasah Aliyah Negeri)',
            'min' => 'MIN (Madrasah Ibtidaiyah Negeri)',
            'slb' => 'SLB',
            'other' => 'Lainnya',
        ];
    }

    /**
     * Options for the enumerated selects.
     */
    private function fieldOptions(): array
    {
        return [
            'statusLembaga' => ['Negeri', 'Swasta'],
            'waktuBelajar' => ['Pagi', 'Siang', 'Malam'],
            'komiteLembaga' => ['Sudah Terbentuk', 'Belum Terbentuk'],
            'statusKKM' => ['Induk', 'Anggota'],
            'statusUnit' => [
                1 => 'Aktif (Intern)',
                2 => 'Aktif (Satker)',
                0 => 'Tidak Aktif',
            ],
        ];
    }
}
