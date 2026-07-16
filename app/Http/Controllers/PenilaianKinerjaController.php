<?php

namespace App\Http\Controllers;

use App\Models\PenilaianKinerja;
use App\Models\PenilaianKriteria;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenilaianKinerjaController extends Controller
{
    /**
     * Display a listing of penilaian.
     */
    public function index(Request $request)
    {
        // Check if user is kepala
        $user = auth()->user();
        if (!$user || $user->role !== 'kepala') {
            abort(403, 'Hanya Kepala Kantor yang dapat mengakses halaman ini.');
        }

        // Get filter values
        $tahun = $request->input('tahun', date('Y'));
        $currentTriwulan = ceil(date('n') / 3);
        $triwulan = $request->input('triwulan', max(1, $currentTriwulan - 1));

        $query = PenilaianKinerja::with(['pejabat'])
            ->where('penilai_id', $user->id)
            ->orderBy('tahun', 'desc')
            ->orderBy('triwulan', 'desc');

        if ($tahun) {
            $query->where('tahun', $tahun);
        }

        if ($triwulan) {
            $query->where('triwulan', $triwulan);
        }

        $penilaians = $query->paginate(15)->withQueryString();

        $tahunOptions = $this->getTahunOptions();
        $triwulanOptions = $this->getTriwulanOptions();

        $stats = [
            'total' => PenilaianKinerja::where('penilai_id', $user->id)->count(),
            'tahun_ini' => PenilaianKinerja::where('penilai_id', $user->id)->where('tahun', date('Y'))->count(),
            'total_up' => PenilaianKinerja::where('penilai_id', $user->id)->sum('total_thumbs_up'),
            'total_down' => PenilaianKinerja::where('penilai_id', $user->id)->sum('total_thumbs_down'),
        ];

        return view('penilaian-kinerja.index', [
            'penilaians' => $penilaians,
            'tahunOptions' => $tahunOptions,
            'triwulanOptions' => $triwulanOptions,
            'stats' => $stats,
            'filters' => [
                'tahun' => $tahun,
                'triwulan' => $triwulan,
            ],
        ]);
    }

    /**
     * Show the form for creating a new penilaian.
     */
    public function create(Request $request)
    {
        $user = auth()->user();
        if (!$user || $user->role !== 'kepala') {
            abort(403, 'Hanya Kepala Kantor yang dapat mengakses halaman ini.');
        }

        $tahun = $request->input('tahun', date('Y'));
        $currentTriwulan = ceil(date('n') / 3);
        $triwulan = $request->input('triwulan', max(1, $currentTriwulan - 1));

        $pejabats = User::dapatDinilai()
            ->leftJoin('ktd_department as dept', 'dept.id', '=', 'users.dept_id')
            ->select(['users.*', 'dept.nama as dept_nama', 'dept.kategori as dept_kategori'])
            ->where('users.id', '!=', $user->id)
            ->whereIn('users.status', [1, 2])
            ->whereNotIn('users.dept_id', [998, 999])
            ->orderByRaw("CASE
                WHEN kat_jabatan IN ('kasubbag', 'kasubag') THEN 1
                WHEN kat_jabatan = 'kasi' THEN 2
                WHEN kat_jabatan = 'kepala' AND dept.kategori = 'kua' THEN 3
                WHEN kat_jabatan = 'kepala' AND dept.kategori = 'min' THEN 4
                WHEN kat_jabatan = 'kepala' AND dept.kategori = 'mtsn' THEN 5
                WHEN kat_jabatan = 'kepala' AND dept.kategori = 'man' THEN 6
                ELSE 7
            END")
            ->orderBy('users.dept_id')
            ->orderBy('users.name')
            ->get();

        $sudahDinilai = PenilaianKinerja::where('penilai_id', $user->id)
            ->where('tahun', $tahun)
            ->where('triwulan', $triwulan)
            ->pluck('pejabat_id')
            ->toArray();

        $kriterias = PenilaianKriteria::getAllKriteria();

        return view('penilaian-kinerja.create', [
            'pejabats' => $pejabats,
            'sudahDinilai' => $sudahDinilai,
            'kriterias' => $kriterias,
            'filters' => [
                'tahun' => $tahun,
                'triwulan' => $triwulan,
            ],
            'tahunOptions' => $this->getTahunOptions(),
            'triwulanOptions' => $this->getTriwulanOptions(),
        ]);
    }

    /**
     * Store a newly created penilaian.
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        if (!$user || $user->role !== 'kepala') {
            abort(403, 'Hanya Kepala Kantor yang dapat mengakses halaman ini.');
        }

        $request->validate([
            'pejabat_id' => 'required|integer|exists:users,id',
            'tahun' => 'required|integer|min:2020|max:2030',
            'triwulan' => 'required|integer|in:1,2,3,4',
        ], [
            'pejabat_id.required' => 'Pilih pejabat yang akan dinilai.',
            'pejabat_id.exists' => 'Pejabat tidak ditemukan.',
        ]);

        $pejabat = User::find($request->pejabat_id);
        if (!in_array($pejabat->kat_jabatan, ['kasubbag', 'kasubag', 'kasi', 'kepala'])) {
            return redirect()->back()->withInput()->with('error', 'Pejabat tidak valid untuk dinilai.');
        }

        if ($request->pejabat_id == $user->id) {
            return redirect()->back()->withInput()->with('error', 'Anda tidak dapat menilai diri sendiri.');
        }

        $exists = PenilaianKinerja::where('tahun', $request->tahun)
            ->where('triwulan', $request->triwulan)
            ->where('pejabat_id', $request->pejabat_id)
            ->first();

        if ($exists) {
            return redirect()->back()->withInput()->with('error', 'Penilaian untuk pejabat ini pada periode ini sudah ada. Gunakan fitur edit.');
        }

        DB::beginTransaction();
        try {
            $totalUp = 0;
            $totalDown = 0;
            $kriteriaData = $request->input('kriteria', []);

            foreach ($kriteriaData as $kriteria => $data) {
                $totalUp += $data['thumbs_up'] ?? 0;
                $totalDown += $data['thumbs_down'] ?? 0;
            }

            $penilaian = PenilaianKinerja::create([
                'tahun' => $request->tahun,
                'triwulan' => $request->triwulan,
                'pejabat_id' => $request->pejabat_id,
                'penilai_id' => $user->id,
                'catatan_umum' => $request->catatan_umum,
                'total_thumbs_up' => $totalUp,
                'total_thumbs_down' => $totalDown,
            ]);

            foreach ($kriteriaData as $kriteria => $data) {
                if (in_array($kriteria, PenilaianKriteria::getKriteriaKeys())) {
                    PenilaianKriteria::create([
                        'penilaian_id' => $penilaian->id,
                        'kriteria' => $kriteria,
                        'thumbs_up' => $data['thumbs_up'] ?? 0,
                        'thumbs_down' => $data['thumbs_down'] ?? 0,
                        'catatan' => $data['catatan'] ?? null,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('penilaian-kinerja.show', $penilaian->id)->with('success', 'Penilaian kinerja berhasil disimpan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified penilaian.
     */
    public function show(int $id)
    {
        $penilaian = PenilaianKinerja::with(['pejabat', 'penilai', 'kriterias'])->findOrFail($id);

        // Load dept_nama for pejabat
        $penilaian->pejabat->dept_nama = DB::table('ktd_department')
            ->where('id', $penilaian->pejabat->dept_id)
            ->value('nama');

        if ($penilaian->penilai_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke penilaian ini.');
        }

        return view('penilaian-kinerja.show', [
            'penilaian' => $penilaian,
            'kriterias' => PenilaianKriteria::getAllKriteria(),
        ]);
    }

    /**
     * Show the form for editing the specified penilaian.
     */
    public function edit(int $id)
    {
        $penilaian = PenilaianKinerja::with(['kriterias', 'pejabat'])->findOrFail($id);

        // Load dept_nama for pejabat
        $penilaian->pejabat->dept_nama = DB::table('ktd_department')
            ->where('id', $penilaian->pejabat->dept_id)
            ->value('nama');

        if ($penilaian->penilai_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit penilaian ini.');
        }

        return view('penilaian-kinerja.edit', [
            'penilaian' => $penilaian,
            'kriterias' => PenilaianKriteria::getAllKriteria(),
        ]);
    }

    /**
     * Update the specified penilaian.
     */
    public function update(Request $request, int $id)
    {
        $penilaian = PenilaianKinerja::findOrFail($id);

        if ($penilaian->penilai_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit penilaian ini.');
        }

        DB::beginTransaction();
        try {
            $totalUp = 0;
            $totalDown = 0;
            $kriteriaData = $request->input('kriteria', []);

            foreach ($kriteriaData as $kriteria => $data) {
                $totalUp += $data['thumbs_up'] ?? 0;
                $totalDown += $data['thumbs_down'] ?? 0;
            }

            $penilaian->update([
                'catatan_umum' => $request->catatan_umum,
                'total_thumbs_up' => $totalUp,
                'total_thumbs_down' => $totalDown,
            ]);

            foreach ($kriteriaData as $kriteria => $data) {
                if (in_array($kriteria, PenilaianKriteria::getKriteriaKeys())) {
                    PenilaianKriteria::updateOrCreate(
                        ['penilaian_id' => $penilaian->id, 'kriteria' => $kriteria],
                        [
                            'thumbs_up' => $data['thumbs_up'] ?? 0,
                            'thumbs_down' => $data['thumbs_down'] ?? 0,
                            'catatan' => $data['catatan'] ?? null,
                        ]
                    );
                }
            }

            DB::commit();

            return redirect()->route('penilaian-kinerja.show', $penilaian->id)->with('success', 'Penilaian kinerja berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified penilaian.
     */
    public function destroy(int $id)
    {
        $penilaian = PenilaianKinerja::findOrFail($id);

        if ($penilaian->penilai_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk menghapus penilaian ini.',
            ], 403);
        }

        $penilaian->delete();

        return response()->json([
            'success' => true,
            'message' => 'Penilaian berhasil dihapus.',
        ]);
    }

    /**
     * Get tahun options
     */
    private function getTahunOptions(): array
    {
        $currentYear = date('Y');
        $years = [];
        for ($y = $currentYear - 2; $y <= $currentYear + 1; $y++) {
            $years[$y] = $y;
        }
        return $years;
    }

    /**
     * Get triwulan options
     */
    private function getTriwulanOptions(): array
    {
        return [
            1 => 'Triwulan I (Jan - Mar)',
            2 => 'Triwulan II (Apr - Jun)',
            3 => 'Triwulan III (Jul - Sep)',
            4 => 'Triwulan IV (Okt - Des)',
        ];
    }
}
