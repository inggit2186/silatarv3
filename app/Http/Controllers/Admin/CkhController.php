<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CkhController extends Controller
{
    /**
     * Display a listing of CKH reports.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $isAdmin = in_array($user->role, ['admin', 'superadmin', 'kepala']);

        $query = DB::table('satker_ckh as ckh')
            ->leftJoin('users as u', 'u.id', '=', 'ckh.user_id')
            ->leftJoin('ktd_department as dept', 'dept.id', '=', 'ckh.dept_id')
            ->select([
                'ckh.id',
                'ckh.user_id',
                'ckh.dept_id',
                'ckh.bulan',
                'ckh.status',
                'ckh.filename',
                'ckh.sending',
                'u.name as user_name',
                'u.nomor_induk as user_nip',
                'u.role as user_role',
                'dept.nama as dept_nama',
                'dept.kategori as dept_kategori',
            ]);

        // Filter by role (non-admin)
        if (!$isAdmin) {
            $query->where(function ($q) use ($user) {
                // 1. Dept ID yang sama (selalu bisa lihat)
                $q->where('ckh.dept_id', $user->dept_id);

                // 2. Aturan khusus berdasarkan dept_id
                if ($user->dept_id == 5) {
                    $q->orWhere('ckh.dept_id', 998); // Pemerintah Daerah
                }

                if ($user->dept_id == 7) {
                    $q->orWhereIn('dept.kategori', ['min', 'mtsn', 'man']);
                    $q->orWhere('ckh.dept_id', 999); // Swasta/Lainnya
                }

                if ($user->dept_id == 8) {
                    $q->orWhere('dept.kategori', 'kua');
                }
            });
        }

        // Get all filter values first
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');
        $filterDeptId = $request->input('dept_id');
        $status = $request->input('status');
        $search = $request->input('search');

        // Set default values if no filters applied
        $hasAnyFilter = $bulan || $tahun || $filterDeptId || $status || $search;
        if (!$hasAnyFilter) {
            // Default: bulan sebelumnya dan tahun saat ini
            $bulan = now()->subMonth()->month;
            $tahun = now()->year;
        }

        // Filter by bulan
        if ($bulan) {
            $query->whereMonth('ckh.bulan', $bulan);
        }

        // Filter by tahun
        if ($tahun) {
            $query->whereYear('ckh.bulan', $tahun);
        }

        // Filter by dept_id
        if ($filterDeptId) {
            $query->where('ckh.dept_id', $filterDeptId);
        }

        // Filter by status
        if ($status) {
            $query->where('ckh.status', $status);
        }

        // Search
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('u.name', 'like', "%{$search}%")
                  ->orWhere('u.nomor_induk', 'like', "%{$search}%")
                  ->orWhere('dept.nama', 'like', "%{$search}%");
            });
        }

        // Order by
        $query->orderBy('ckh.bulan', 'desc')
              ->orderBy('u.name', 'asc');

        // Paginate
        $ckhList = $query->paginate(15)->withQueryString();

        // Get filter options
        $departments = DB::table('ktd_department')
            ->whereIn('status', [1, 2])
            ->orderBy('nama')
            ->get(['id', 'nama']);

        $statusOptions = [
            'KOSONG' => 'Kosong',
            'DIKIRIM' => 'Dikirim',
            'DISETUJUI' => 'Disetujui',
            'DITOLAK' => 'Ditolak',
        ];

        // Get years for filter (last 5 years)
        $currentYear = date('Y');
        $years = range($currentYear, $currentYear - 4);

        // Stats
        $stats = [
            'total' => $ckhList->total(),
            'kosong' => DB::table('satker_ckh')->where('status', 'KOSONG')->count(),
            'dikirim' => DB::table('satker_ckh')->where('status', 'DIKIRIM')->count(),
            'disetujui' => DB::table('satker_ckh')->where('status', 'DISETUJUI')->count(),
            'ditolak' => DB::table('satker_ckh')->where('status', 'DITOLAK')->count(),
        ];

        return view('admin.ckh.index', [
            'title' => 'Laporan CKH - SILATAR Admin',
            'breadcrumbs' => [
                ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['label' => 'Laporan CKH', 'url' => null],
            ],
            'ckhList' => $ckhList,
            'departments' => $departments,
            'statusOptions' => $statusOptions,
            'years' => $years,
            'stats' => $stats,
            'filters' => [
                'search' => $search,
                'bulan' => $bulan,
                'tahun' => $tahun,
                'dept_id' => $filterDeptId,
                'status' => $status,
            ],
            'isAdmin' => $isAdmin,
        ]);
    }

    /**
     * Display the specified CKH report.
     */
    public function show($id)
    {
        $user = auth()->user();
        $isAdmin = in_array($user->role, ['admin', 'superadmin', 'kepala']);

        $ckh = DB::table('satker_ckh as ckh')
            ->leftJoin('users as u', 'u.id', '=', 'ckh.user_id')
            ->leftJoin('ktd_department as dept', 'dept.id', '=', 'ckh.dept_id')
            ->select([
                'ckh.*',
                'u.name as user_name',
                'u.nomor_induk as user_nip',
                'u.jk as user_jk',
                'u.pekerjaan as user_pekerjaan',
                'dept.nama as dept_nama',
                'dept.kategori as dept_kategori',
            ])
            ->where('ckh.id', $id)
            ->first();

        if (!$ckh) {
            return redirect()->route('admin.ckh.index')
                ->with('error', 'Laporan CKH tidak ditemukan');
        }

        // Check if user can view this CKH
        if (!$isAdmin) {
            if (!$this->canViewCkh($user, $ckh)) {
                abort(403, 'Anda tidak memiliki akses untuk melihat laporan ini.');
            }
        }

        // Get CKH items (data_json)
        $items = [];
        if ($ckh->filename) {
            $filePath = storage_path('app/public/' . $ckh->filename);
            if (file_exists($filePath)) {
                $fileContent = file_get_contents($filePath);
                $items = json_decode($fileContent, true) ?? [];
            }
        }

        // Check if user can verify this CKH
        $canVerify = $this->canVerifyCkh($user, $ckh);

        return view('admin.ckh.show', [
            'title' => 'Detail Laporan CKH - SILATAR Admin',
            'breadcrumbs' => [
                ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['label' => 'Laporan CKH', 'url' => route('admin.ckh.index')],
                ['label' => 'Detail', 'url' => null],
            ],
            'ckh' => $ckh,
            'items' => $items,
            'canVerify' => $canVerify,
        ]);
    }

    /**
     * Approve CKH report.
     */
    public function approve($id)
    {
        $user = auth()->user();
        $ckh = DB::table('satker_ckh')->where('id', $id)->first();

        if (!$ckh) {
            return redirect()->route('admin.ckh.index')
                ->with('error', 'Laporan CKH tidak ditemukan');
        }

        // Check if user can verify this CKH
        if (!$this->canVerifyCkh($user, $ckh)) {
            abort(403, 'Anda tidak memiliki akses untuk memverifikasi laporan ini.');
        }

        DB::table('satker_ckh')
            ->where('id', $id)
            ->update([
                'status' => 'DISETUJUI',
                'petugas' => $user->id,
                'updated_at' => now(),
            ]);

        return redirect()->route('admin.ckh.show', $id)
            ->with('success', 'Laporan CKH berhasil disetujui');
    }

    /**
     * Reject CKH report.
     */
    public function reject(Request $request, $id)
    {
        $user = auth()->user();
        $ckh = DB::table('satker_ckh')->where('id', $id)->first();

        if (!$ckh) {
            return redirect()->route('admin.ckh.index')
                ->with('error', 'Laporan CKH tidak ditemukan');
        }

        // Check if user can verify this CKH
        if (!$this->canVerifyCkh($user, $ckh)) {
            abort(403, 'Anda tidak memiliki akses untuk memverifikasi laporan ini.');
        }

        $request->validate([
            'alasan' => 'required|string|max:255',
        ]);

        DB::table('satker_ckh')
            ->where('id', $id)
            ->update([
                'status' => 'DITOLAK',
                'alasan' => $request->alasan,
                'petugas' => $user->id,
                'updated_at' => now(),
            ]);

        return redirect()->route('admin.ckh.show', $id)
            ->with('success', 'Laporan CKH berhasil ditolak');
    }

    /**
     * Check if user can view CKH.
     */
    private function canViewCkh($user, $ckh): bool
    {
        // Dept ID yang sama selalu bisa lihat
        if ($ckh->dept_id == $user->dept_id) {
            return true;
        }

        // Aturan khusus berdasarkan dept_id
        if ($user->dept_id == 5 && $ckh->dept_id == 998) {
            return true; // Pemerintah Daerah
        }

        if ($user->dept_id == 7) {
            if (in_array($ckh->dept_kategori, ['min', 'mtsn', 'man'])) {
                return true;
            }
            if ($ckh->dept_id == 999) {
                return true; // Swasta/Lainnya
            }
        }

        if ($user->dept_id == 8 && $ckh->dept_kategori == 'kua') {
            return true;
        }

        return false;
    }

    /**
     * Check if user can verify CKH.
     */
    private function canVerifyCkh($user, $ckh): bool
    {
        // Admin/Superadmin/Kepala selalu bisa verifikasi
        if (in_array($user->role, ['admin', 'superadmin', 'kepala'])) {
            return true;
        }

        // dept_id = 5: bisa verifikasi dept_id 998 (semua role), dept_id 5 (hanya kasi)
        if ($user->dept_id == 5) {
            if ($ckh->dept_id == 998) return true;
            if ($ckh->dept_id == 5 && $user->role == 'kasi') return true;
        }

        // dept_id = 7: bisa verifikasi kategori min/mtsn/man (semua role),
        //               dept_id 999 (semua role), dept_id 7 (hanya kasi)
        if ($user->dept_id == 7) {
            if (in_array($ckh->dept_kategori, ['min', 'mtsn', 'man'])) return true;
            if ($ckh->dept_id == 999) return true;
            if ($ckh->dept_id == 7 && $user->role == 'kasi') return true;
        }

        // dept_id = 8: bisa verifikasi kategori kua (semua role), dept_id 8 (hanya kasi)
        if ($user->dept_id == 8) {
            if ($ckh->dept_kategori == 'kua') return true;
            if ($ckh->dept_id == 8 && $user->role == 'kasi') return true;
        }

        // Lainnya: hanya kasi/kasubbag, dept_id sama
        if (in_array($user->role, ['kasi', 'kasubbag'])) {
            if ($ckh->dept_id == $user->dept_id) return true;
        }

        return false;
    }
}
