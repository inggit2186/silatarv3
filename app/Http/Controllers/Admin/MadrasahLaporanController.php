<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MadrasahLaporanController extends Controller
{
    /**
     * Display list of all laporan madrasah (bulanan & semester).
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Only admin/superadmin/kepala or users with dept_id=7 can access Laporan Madrasah
        $isAdmin = in_array($user->role, ['admin', 'superadmin', 'kepala']);
        if (!$isAdmin && $user->dept_id != 7) {
            abort(403, 'Anda tidak memiliki akses ke menu ini.');
        }

        $search = $request->get('search');
        $type = $request->get('type');
        $status = $request->get('status');

        // Fetch all active madrasah
        $madrasahQuery = DB::table('ktd_madrasah')
            ->where('status', 1);

        if ($search) {
            $madrasahQuery->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('nsm', 'like', "%{$search}%")
                    ->orWhere('npsm', 'like', "%{$search}%");
            });
        }

        $madrasahs = $madrasahQuery->orderBy('kategori')->orderBy('nama')->get();

        // For each madrasah, get their latest laporan status (only submitted/approved/revisi)
        $allLaporan = collect();
        foreach ($madrasahs as $madrasah) {
            // Check for latest bulanan laporan (only submitted/approved/revisi)
            $laporanBulanan = DB::table('ktd_laporan_bulanan_madrasah')
                ->where('madrasah_id', $madrasah->id)
                ->whereIn('status', ['submitted', 'approved', 'revisi'])
                ->orderByDesc('submitted_at')
                ->first();

            // Check for latest semester laporan (only submitted/approved/revisi)
            $laporanSemester = DB::table('ktd_laporan_semester_madrasah')
                ->where('madrasah_id', $madrasah->id)
                ->whereIn('status', ['submitted', 'approved', 'revisi'])
                ->orderByDesc('submitted_at')
                ->first();

            // Skip if no laporan submitted yet
            if (!$laporanBulanan && !$laporanSemester) {
                continue;
            }

            // Determine which laporan to show (prefer submitted, then approved, then revisi)
            $showLaporan = null;
            $jenis = null;

            if ($laporanBulanan && $laporanSemester) {
                // Both exist, show the one with better status
                $statusPriority = ['submitted' => 3, 'approved' => 2, 'revisi' => 1];
                $priorityBulanan = $statusPriority[$laporanBulanan->status] ?? 0;
                $prioritySemester = $statusPriority[$laporanSemester->status] ?? 0;

                if ($priorityBulanan >= $prioritySemester) {
                    $showLaporan = $laporanBulanan;
                    $jenis = 'bulanan';
                } else {
                    $showLaporan = $laporanSemester;
                    $jenis = 'semester';
                }
            } elseif ($laporanBulanan) {
                $showLaporan = $laporanBulanan;
                $jenis = 'bulanan';
            } else {
                $showLaporan = $laporanSemester;
                $jenis = 'semester';
            }

            // Apply type filter
            if ($type && $jenis !== $type) {
                continue;
            }

            // Apply status filter
            if ($status && $showLaporan->status !== $status) {
                continue;
            }

            // Create record for display
            $record = (object) [
                'madrasah_id' => $madrasah->id,
                'nama_madrasah' => $madrasah->nama,
                'nsm' => $madrasah->nsm,
                'kategori' => $madrasah->kategori,
                'status_lembaga' => $madrasah->status_lembaga,
                'jenis' => $jenis,
                'id' => $showLaporan->id,
                'periode_info' => $jenis === 'bulanan' ? ($showLaporan->bulan_laporan ?? null) : ucfirst($showLaporan->semester ?? ''),
                'tahun' => $showLaporan->tahun_laporan ?? null,
                'tahun_ajaran' => $showLaporan->tahun_ajaran ?? null,
                'semester' => $showLaporan->semester ?? null,
                'status' => $showLaporan->status,
                'submitted_at' => $showLaporan->submitted_at ?? null,
            ];

            $allLaporan->push($record);
        }

        // Sort by: submitted records first, then by submitted_at desc, then by name
        $allLaporan = $allLaporan->sortBy(function ($item) {
            $statusOrder = ['submitted' => 0, 'approved' => 1, 'revisi' => 2];
            return $statusOrder[$item->status] ?? 5;
        })->sortByDesc('submitted_at')->values();

        // Manual pagination
        $perPage = 20;
        $currentPage = $request->get('page', 1);
        $totalItems = $allLaporan->count();
        $offset = ($currentPage - 1) * $perPage;
        $paginatedLaporan = $allLaporan->slice($offset, $perPage)->values();

        // Create paginator object for view
        $laporan = new \Illuminate\Pagination\LengthAwarePaginator(
            $paginatedLaporan,
            $totalItems,
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // Get statistics
        $totalMadrasah = DB::table('ktd_madrasah')->where('status', 1)->count();
        $madrasahWithLaporan = DB::table('ktd_madrasah as m')
            ->leftJoin('ktd_laporan_bulanan_madrasah as lb', function ($join) {
                $join->on('m.id', '=', 'lb.madrasah_id')
                    ->where('lb.status', '!=', 'draft');
            })
            ->leftJoin('ktd_laporan_semester_madrasah as ls', function ($join) {
                $join->on('m.id', '=', 'ls.madrasah_id')
                    ->where('ls.status', '!=', 'draft');
            })
            ->where('m.status', 1)
            ->where(function ($q) {
                $q->whereNotNull('lb.id')
                    ->orWhereNotNull('ls.id');
            })
            ->count();

        $statsBulanan = DB::table('ktd_laporan_bulanan_madrasah')
            ->selectRaw("
                COUNT(*) as total,
                SUM(status = 'submitted') as pending,
                SUM(status = 'approved') as approved,
                SUM(status = 'revisi') as revisi
            ")
            ->where('status', '!=', 'draft')
            ->first();

        $statsSemester = DB::table('ktd_laporan_semester_madrasah')
            ->selectRaw("
                COUNT(*) as total,
                SUM(status = 'submitted') as pending,
                SUM(status = 'approved') as approved,
                SUM(status = 'revisi') as revisi
            ")
            ->where('status', '!=', 'draft')
            ->first();

        $stats = [
            'total' => $totalMadrasah,
            'pending' => ($statsBulanan->pending ?? 0) + ($statsSemester->pending ?? 0),
            'approved' => ($statsBulanan->approved ?? 0) + ($statsSemester->approved ?? 0),
            'revisi' => ($statsBulanan->revisi ?? 0) + ($statsSemester->revisi ?? 0),
        ];

        return view('admin.madrasah.laporan-index', [
            'title' => 'Laporan Madrasah - SILATAR Admin',
            'breadcrumbs' => [
                ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['label' => 'Laporan Madrasah', 'url' => null],
            ],
            'laporan' => $laporan,
            'stats' => $stats,
            'currentSearch' => $search,
            'currentType' => $type,
            'currentStatus' => $status,
        ]);
    }

    /**
     * Display detail of a specific laporan.
     */
    public function show(string $type, int $id)
    {
        $user = Auth::user();

        if ($type === 'bulanan') {
            $laporan = DB::table('ktd_laporan_bulanan_madrasah')
                ->leftJoin('ktd_madrasah as m', 'm.id', '=', 'ktd_laporan_bulanan_madrasah.madrasah_id')
                ->select([
                    'ktd_laporan_bulanan_madrasah.*',
                    'm.nama as nama_madrasah_db',
                    'm.nsm',
                    'm.npsm',
                ])
                ->where('ktd_laporan_bulanan_madrasah.id', $id)
                ->first();

            if (!$laporan) {
                abort(404);
            }

            // Parse JSON fields
            $laporan->student_counts = json_decode($laporan->student_counts_json, true) ?? [];
            $laporan->mutation_rows = json_decode($laporan->mutation_rows_json, true) ?? [];
            $laporan->jenis = 'bulanan';
            $laporan->nama_madrasah = $laporan->nama_madrasah_snapshot ?? $laporan->nama_madrasah_db;
            $laporan->instansi = $laporan->instansi_snapshot;
            $laporan->periode = $laporan->bulan_laporan . ' ' . $laporan->tahun_laporan;
            $laporan->periode_detail = $laporan->bulan_laporan . ' ' . $laporan->tahun_laporan . ' | ' . $laporan->tahun_ajaran . ' Semester ' . $laporan->semester;

        } elseif ($type === 'semester') {
            $laporan = DB::table('ktd_laporan_semester_madrasah')
                ->leftJoin('ktd_madrasah as m', 'm.id', '=', 'ktd_laporan_semester_madrasah.madrasah_id')
                ->leftJoin('ktd_department as d', 'd.id', '=', 'ktd_laporan_semester_madrasah.dept_id')
                ->select([
                    'ktd_laporan_semester_madrasah.*',
                    'm.nama as nama_madrasah',
                    'm.nsm',
                    'm.npsm',
                    'd.nama as instansi',
                ])
                ->where('ktd_laporan_semester_madrasah.id', $id)
                ->first();

            if (!$laporan) {
                abort(404);
            }

            // Parse all JSON fields
            $jsonFields = [
                'keadaan_gedung',
                'sarana_pendidikan',
                'bantuan_pemerintah',
                'bantuan_non_pemerintah',
                'data_guru_pegawai',
                'tingkat_pendidikan',
                'sertifikasi',
                'absensi_siswa',
                'luas_tanah',
                'sertifikat_tanah',
            ];

            foreach ($jsonFields as $field) {
                $jsonColumn = $field . '_json';
                $laporan->$field = json_decode($laporan->$jsonColumn, true) ?? [];
            }

            $laporan->jenis = 'semester';
            $laporan->periode = ucfirst($laporan->semester) . ' ' . $laporan->tahun_ajaran;
            $laporan->periode_detail = 'Semester ' . ucfirst($laporan->semester) . ' | ' . $laporan->tahun_ajaran;

        } else {
            abort(404);
        }

        // Fetch profil madrasah from ktd_department
        $profilMadrasah = null;
        if ($laporan->dept_id) {
            $profilMadrasah = DB::table('ktd_department')
                ->where('id', $laporan->dept_id)
                ->first();
        }

        // Fetch pegawai (staf & honorer) from tenaga_ktd
        $pegawai = [];
        if ($laporan->madrasah_id) {
            $pegawai = DB::table('tenaga_ktd')
                ->where('madrasah_id', $laporan->madrasah_id)
                ->whereIn('kat_jabatan', ['staf', 'honorer'])
                ->where('is_active', 1)
                ->orderBy('nama')
                ->get()
                ->toArray();
        }

        // Fetch guru from tenaga_ktd
        $guru = [];
        if ($laporan->madrasah_id) {
            $guru = DB::table('tenaga_ktd')
                ->where('madrasah_id', $laporan->madrasah_id)
                ->where('kat_jabatan', 'guru')
                ->where('is_active', 1)
                ->orderBy('nama')
                ->get()
                ->toArray();
        }

        return view('admin.madrasah.laporan-show', [
            'title' => 'Detail Laporan Madrasah - SILATAR Admin',
            'breadcrumbs' => [
                ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['label' => 'Laporan Madrasah', 'url' => route('admin.madrasah.laporan.index')],
                ['label' => 'Detail', 'url' => null],
            ],
            'laporan' => $laporan,
            'type' => $type,
            'profilMadrasah' => $profilMadrasah,
            'pegawai' => $pegawai,
            'guru' => $guru,
        ]);
    }

    /**
     * Verify/approve a laporan.
     */
    public function verify(Request $request, string $type, int $id)
    {
        $user = Auth::user();

        $table = $type === 'bulanan' ? 'ktd_laporan_bulanan_madrasah' : 'ktd_laporan_semester_madrasah';

        $laporan = DB::table($table)->where('id', $id)->first();

        if (!$laporan) {
            abort(404);
        }

        // Only submitted reports can be verified
        if ($laporan->status !== 'submitted') {
            return back()->with('error', 'Hanya laporan dengan status "submitted" yang dapat diverifikasi.');
        }

        DB::table($table)
            ->where('id', $id)
            ->update([
                'status' => 'approved',
                'catatan_admin' => $request->get('catatan_admin') ?? $laporan->catatan_admin,
                'updated_at' => now(),
            ]);

        return back()->with('success', 'Laporan berhasil disetujui!');
    }

    /**
     * Reject/request revision for a laporan.
     */
    public function reject(Request $request, string $type, int $id)
    {
        $user = Auth::user();

        $table = $type === 'bulanan' ? 'ktd_laporan_bulanan_madrasah' : 'ktd_laporan_semester_madrasah';

        $laporan = DB::table($table)->where('id', $id)->first();

        if (!$laporan) {
            abort(404);
        }

        // Validation
        $request->validate([
            'catatan_admin_reject' => 'required|string|max:1000',
        ]);

        // Only submitted reports can be rejected
        if ($laporan->status !== 'submitted') {
            return back()->with('error', 'Hanya laporan dengan status "submitted" yang dapat ditolak.');
        }

        DB::table($table)
            ->where('id', $id)
            ->update([
                'status' => 'revisi',
                'catatan_admin' => $request->get('catatan_admin_reject'),
                'updated_at' => now(),
            ]);

        return back()->with('success', 'Laporan dikembalikan untuk revisi.');
    }

    /**
     * Add/update admin note for a laporan.
     */
    public function addNote(Request $request, string $type, int $id)
    {
        $user = Auth::user();

        $table = $type === 'bulanan' ? 'ktd_laporan_bulanan_madrasah' : 'ktd_laporan_semester_madrasah';

        $laporan = DB::table($table)->where('id', $id)->first();

        if (!$laporan) {
            abort(404);
        }

        $request->validate([
            'catatan_admin' => 'nullable|string|max:1000',
        ]);

        DB::table($table)
            ->where('id', $id)
            ->update([
                'catatan_admin' => $request->get('catatan_admin'),
                'updated_at' => now(),
            ]);

        return back()->with('success', 'Catatan admin berhasil disimpan!');
    }
}
