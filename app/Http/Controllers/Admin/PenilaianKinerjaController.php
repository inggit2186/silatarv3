<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
        // Get filter values
        $tahun = $request->input('tahun', date('Y'));
        $triwulan = $request->input('triwulan', ceil(date('n') / 3));

        // Get current user's penilaian
        $user = auth()->user();

        $query = PenilaianKinerja::with(['pejabat'])
            ->where('penilai_id', $user->id)
            ->orderBy('tahun', 'desc')
            ->orderBy('triwulan', 'desc');

        // Filter by tahun
        if ($tahun) {
            $query->where('tahun', $tahun);
        }

        // Filter by triwulan
        if ($triwulan) {
            $query->where('triwulan', $triwulan);
        }

        $penilaians = $query->paginate(15)->withQueryString();

        // Get options for dropdowns
        $tahunOptions = $this->getTahunOptions();
        $triwulanOptions = [
            1 => 'Triwulan I (Jan - Mar)',
            2 => 'Triwulan II (Apr - Jun)',
            3 => 'Triwulan III (Jul - Sep)',
            4 => 'Triwulan IV (Okt - Des)',
        ];

        // Get statistics
        $stats = [
            'total' => PenilaianKinerja::where('penilai_id', $user->id)->count(),
            'tahun_ini' => PenilaianKinerja::where('penilai_id', $user->id)
                ->where('tahun', date('Y'))->count(),
            'total_up' => PenilaianKinerja::where('penilai_id', $user->id)->sum('total_thumbs_up'),
            'total_down' => PenilaianKinerja::where('penilai_id', $user->id)->sum('total_thumbs_down'),
        ];

        return view('admin.penilaian-kinerja.index', [
            'title' => 'Penilaian Kinerja - SILATAR Admin',
            'breadcrumbs' => [
                ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['label' => 'Penilaian Kinerja', 'url' => null],
            ],
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

        // Get filter values
        $tahun = $request->input('tahun', date('Y'));
        $triwulan = $request->input('triwulan', ceil(date('n') / 3));

        // Get pejabat struktural (kasubbag, kasubag, kasi, kepala) dari semua dept
        $pejabatsStruktural = User::dapatDinilai()
            ->where('id', '!=', $user->id)
            ->whereNotIn('dept_id', [998, 999, 14])
            ->orderByRaw("FIELD(kat_jabatan, 'kasubbag', 'kasubag', 'kasi')")
            ->orderBy('name');

        // Query semua user dari dept_id = 14 (kecuali status = 3 dan kat_jabatan = kepala)
        $pejabatsDept14 = User::where('id', '!=', $user->id)
            ->where('dept_id', 14)
            ->whereIn('status', [1, 2])
            ->where('kat_jabatan', '!=', 'kepala')
            ->orderBy('name');

        // Combine dengan union
        $pejabats = $pejabatsStruktural->union($pejabatsDept14)->get();

        // Get pejabat yang sudah dinilai di periode ini
        $sudahDinilai = PenilaianKinerja::where('penilai_id', $user->id)
            ->where('tahun', $tahun)
            ->where('triwulan', $triwulan)
            ->pluck('pejabat_id')
            ->toArray();

        // Get all kriteria
        $kriterias = PenilaianKriteria::getAllKriteria();

        return view('admin.penilaian-kinerja.create', [
            'title' => 'Buat Penilaian Kinerja - SILATAR Admin',
            'breadcrumbs' => [
                ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['label' => 'Penilaian Kinerja', 'url' => route('admin.penilaian-kinerja.index')],
                ['label' => 'Buat Baru', 'url' => null],
            ],
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
        $request->validate([
            'pejabat_id' => 'required|integer|exists:users,id',
            'tahun' => 'required|integer|min:2020|max:2030',
            'triwulan' => 'required|integer|in:1,2,3,4',
        ], [
            'pejabat_id.required' => 'Pilih pejabat yang akan dinilai.',
            'pejabat_id.exists' => 'Pejabat tidak ditemukan.',
        ]);

        $user = auth()->user();

        // Cek apakah pejabat valid untuk dinilai
        $pejabat = User::find($request->pejabat_id);
        if (!in_array($pejabat->kat_jabatan, ['kasubbag', 'kasubag', 'kasi', 'kepala'])) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Pejabat tidak valid untuk dinilai.');
        }

        // Cek apakah menilai diri sendiri
        if ($request->pejabat_id == $user->id) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Anda tidak dapat menilai diri sendiri.');
        }

        // Cek apakah sudah ada penilaian untuk periode ini
        $exists = PenilaianKinerja::where('tahun', $request->tahun)
            ->where('triwulan', $request->triwulan)
            ->where('pejabat_id', $request->pejabat_id)
            ->first();

        if ($exists) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Penilaian untuk pejabat ini pada periode ini sudah ada. Gunakan fitur edit.');
        }

        DB::beginTransaction();
        try {
            // Hitung total thumbs
            $totalUp = 0;
            $totalDown = 0;
            $kriteriaData = $request->input('kriteria', []);

            foreach ($kriteriaData as $kriteria => $data) {
                $totalUp += $data['thumbs_up'] ?? 0;
                $totalDown += $data['thumbs_down'] ?? 0;
            }

            // Buat penilaian
            $penilaian = PenilaianKinerja::create([
                'tahun' => $request->tahun,
                'triwulan' => $request->triwulan,
                'pejabat_id' => $request->pejabat_id,
                'penilai_id' => $user->id,
                'catatan_umum' => $request->catatan_umum,
                'total_thumbs_up' => $totalUp,
                'total_thumbs_down' => $totalDown,
            ]);

            // Simpan kriteria
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

            return redirect()
                ->route('admin.penilaian-kinerja.show', $penilaian->id)
                ->with('success', 'Penilaian kinerja berhasil disimpan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified penilaian.
     */
    public function show(int $id)
    {
        $penilaian = PenilaianKinerja::with(['pejabat', 'penilai', 'kriterias'])
            ->findOrFail($id);

        // Cek apakah penilai adalah user saat ini
        if ($penilaian->penilai_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke penilaian ini.');
        }

        return view('admin.penilaian-kinerja.show', [
            'title' => 'Detail Penilaian Kinerja - SILATAR Admin',
            'breadcrumbs' => [
                ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['label' => 'Penilaian Kinerja', 'url' => route('admin.penilaian-kinerja.index')],
                ['label' => $penilaian->pejabat->name, 'url' => null],
            ],
            'penilaian' => $penilaian,
            'kriterias' => PenilaianKriteria::getAllKriteria(),
        ]);
    }

    /**
     * Show the form for editing the specified penilaian.
     */
    public function edit(int $id)
    {
        $penilaian = PenilaianKinerja::with(['kriterias'])
            ->findOrFail($id);

        // Cek apakah penilai adalah user saat ini
        if ($penilaian->penilai_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit penilaian ini.');
        }

        $kriterias = PenilaianKriteria::getAllKriteria();

        return view('admin.penilaian-kinerja.edit', [
            'title' => 'Edit Penilaian Kinerja - SILATAR Admin',
            'breadcrumbs' => [
                ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['label' => 'Penilaian Kinerja', 'url' => route('admin.penilaian-kinerja.index')],
                ['label' => 'Edit: ' . $penilaian->pejabat->name, 'url' => null],
            ],
            'penilaian' => $penilaian,
            'kriterias' => $kriterias,
            'tahunOptions' => $this->getTahunOptions(),
            'triwulanOptions' => $this->getTriwulanOptions(),
        ]);
    }

    /**
     * Update the specified penilaian.
     */
    public function update(Request $request, int $id)
    {
        $request->validate([
            'tahun' => 'required|integer|min:2020|max:2030',
            'triwulan' => 'required|integer|in:1,2,3,4',
        ]);

        $penilaian = PenilaianKinerja::findOrFail($id);

        // Cek apakah penilai adalah user saat ini
        if ($penilaian->penilai_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit penilaian ini.');
        }

        DB::beginTransaction();
        try {
            // Hitung total thumbs
            $totalUp = 0;
            $totalDown = 0;
            $kriteriaData = $request->input('kriteria', []);

            foreach ($kriteriaData as $kriteria => $data) {
                $totalUp += $data['thumbs_up'] ?? 0;
                $totalDown += $data['thumbs_down'] ?? 0;
            }

            // Update penilaian
            $penilaian->update([
                'tahun' => $request->tahun,
                'triwulan' => $request->triwulan,
                'catatan_umum' => $request->catatan_umum,
                'total_thumbs_up' => $totalUp,
                'total_thumbs_down' => $totalDown,
            ]);

            // Update/hapus kriteria
            $existingKriterias = $penilaian->kriterias()->pluck('kriteria')->toArray();

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

            return redirect()
                ->route('admin.penilaian-kinerja.show', $penilaian->id)
                ->with('success', 'Penilaian kinerja berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified penilaian.
     */
    public function destroy(int $id)
    {
        $penilaian = PenilaianKinerja::findOrFail($id);

        // Cek apakah penilai adalah user saat ini
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
     * Get pejabat list for AJAX
     */
    public function getPejabat(Request $request)
    {
        $user = auth()->user();
        $tahun = $request->input('tahun', date('Y'));
        $triwulan = $request->input('triwulan');

        // Pejabat struktural
        $queryStruktural = User::dapatDinilai()
            ->where('id', '!=', $user->id)
            ->whereNotIn('dept_id', [998, 999, 14])
            ->orderByRaw("FIELD(kat_jabatan, 'kasubbag', 'kasubag', 'kasi')")
            ->orderBy('name');

        // Semua user dari dept_id = 14 (kecuali status = 3 dan kat_jabatan = kepala)
        $queryDept14 = User::where('id', '!=', $user->id)
            ->where('dept_id', 14)
            ->whereIn('status', [1, 2])
            ->where('kat_jabatan', '!=', 'kepala')
            ->orderBy('name');

        $pejabats = $queryStruktural->union($queryDept14)->get(['id', 'name', 'kat_jabatan', 'jabatan']);

        // Get already rated pejabat if triwulan provided
        if ($tahun && $triwulan) {
            $sudahDinilai = PenilaianKinerja::where('penilai_id', $user->id)
                ->where('tahun', $tahun)
                ->where('triwulan', $triwulan)
                ->pluck('pejabat_id')
                ->toArray();
        } else {
            $sudahDinilai = [];
        }

        return response()->json([
            'success' => true,
            'pejabats' => $pejabats,
            'sudahDinilai' => $sudahDinilai,
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
