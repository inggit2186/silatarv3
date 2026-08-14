<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AcaraController extends Controller
{
    /**
     * Display a listing of acara.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $query = DB::table('ktd_acara');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'LIKE', "%{$search}%")
                  ->orWhere('lokasi', 'LIKE', "%{$search}%");
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        $acaraList = $query->orderBy('tanggal', 'desc')->paginate(15);

        return view('admin.acara.index', compact('acaraList', 'search', 'status'));
    }

    /**
     * Show the form for creating a new acara.
     */
    public function create()
    {
        return view('admin.acara.create');
    }

    /**
     * Store a newly created acara.
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tanggal' => 'required|date',
            'jam_mulai' => 'required',
            'jam_selesei' => 'required',
            'lokasi' => 'required|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'radius' => 'nullable|integer|min:0',
            'status' => 'required|in:active,completed,cancelled',
        ]);

        $insertData = [
            'dept_id' => $request->input('dept_id', 0),
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'tanggal' => $request->tanggal,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesei' => $request->jam_selesei,
            'lokasi' => $request->lokasi,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'radius' => $request->radius,
            'status' => $request->status,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        DB::table('ktd_acara')->insert($insertData);

        return redirect()->route('admin.acara')
            ->with('success', 'Acara berhasil dibuat');
    }

    /**
     * Display the specified acara.
     */
    public function show($id, Request $request)
    {
        $acara = DB::table('ktd_acara')->where('id', $id)->first();

        if (!$acara) {
            return redirect()->route('admin.acara')
                ->with('error', 'Acara tidak ditemukan');
        }

        $search = $request->input('search');
        $statusFilter = $request->input('status_filter');
        $deptFilter = $request->input('dept_filter');

        // Get departments for filter (status 1 and 2 only)
        $departments = DB::table('ktd_department')
            ->whereIn('status', [1, 2])
            ->orderBy('nama')
            ->get();

        // Get attendance list with filters
        $query = DB::table('ktd_presensi_acara')
            ->where('ktd_presensi_acara.acara_id', $id)
            ->join('users', 'ktd_presensi_acara.user_nip', '=', 'users.nomor_induk')
            ->leftJoin('ktd_department', 'users.dept_id', '=', 'ktd_department.id')
            ->select('ktd_presensi_acara.*', 'users.name', 'users.telp', 'ktd_department.nama as unit_kerja');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('users.name', 'LIKE', "%{$search}%")
                  ->orWhere('ktd_presensi_acara.user_nip', 'LIKE', "%{$search}%");
            });
        }

        if ($statusFilter) {
            $query->where('ktd_presensi_acara.status', $statusFilter);
        }

        if ($deptFilter) {
            $query->where('users.dept_id', $deptFilter);
        }

        $attendance = $query->orderBy('ktd_presensi_acara.created_at', 'desc')->get();

        $hadirCount = $attendance->where('status', 'hadir')->count();
        $tidakHadirCount = $attendance->where('status', 'tidak_hadir')->count();

        return view('admin.acara.show', compact('acara', 'attendance', 'hadirCount', 'tidakHadirCount', 'departments'));
    }

    /**
     * Show the form for editing the specified acara.
     */
    public function edit($id)
    {
        $acara = DB::table('ktd_acara')->where('id', $id)->first();

        if (!$acara) {
            return redirect()->route('admin.acara')
                ->with('error', 'Acara tidak ditemukan');
        }

        return view('admin.acara.edit', compact('acara'));
    }

    /**
     * Update the specified acara.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tanggal' => 'required|date',
            'jam_mulai' => 'required',
            'jam_selesei' => 'required',
            'lokasi' => 'required|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'radius' => 'nullable|integer|min:0',
            'status' => 'required|in:active,completed,cancelled',
        ]);

        DB::table('ktd_acara')
            ->where('id', $id)
            ->update([
                'dept_id' => $request->input('dept_id', 0),
                'judul' => $request->judul,
                'deskripsi' => $request->deskripsi,
                'tanggal' => $request->tanggal,
                'jam_mulai' => $request->jam_mulai,
                'jam_selesei' => $request->jam_selesei,
                'lokasi' => $request->lokasi,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'radius' => $request->radius,
                'status' => $request->status,
                'updated_at' => now(),
            ]);

        return redirect()->route('admin.acara')
            ->with('success', 'Acara berhasil diupdate');
    }

    /**
     * Remove the specified acara.
     */
    public function destroy($id)
    {
        DB::table('ktd_acara')->where('id', $id)->delete();

        return redirect()->route('admin.acara.index')
            ->with('success', 'Acara berhasil dihapus');
    }
}
