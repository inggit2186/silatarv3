<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SuratManualController extends Controller
{
    public function __construct()
    {
        $user = auth()->user();
        if ($user && $user->dept_id != 4) {
            abort(403, 'Anda tidak memiliki akses ke menu ini.');
        }
    }

    public function index(Request $request)
    {
        $query = DB::table('users_request as ur')
            ->leftJoin('ktd_department as dept', 'dept.id', '=', 'ur.dept_id')
            ->leftJoin('ktd_layanan as lay', 'lay.id', '=', 'ur.layanan_id')
            ->select([
                'ur.id',
                'ur.no_req',
                'ur.pemohon',
                'ur.no_surat',
                'ur.tgl_surat',
                'ur.judul',
                'ur.deskripsi',
                'ur.status',
                'ur.file_surat',
                'ur.lampiran',
                'ur.created_at',
                'dept.nama as dept_name',
                'lay.nama as layanan_name',
            ])
            ->where('ur.no_req', 'like', 'MANUAL-%');

        // Search
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('ur.no_surat', 'like', "%{$search}%")
                  ->orWhere('ur.judul', 'like', "%{$search}%")
                  ->orWhere('ur.no_req', 'like', "%{$search}%");
            });
        }

        // Filter tanggal
        if ($dateFrom = $request->input('date_from')) {
            $query->where('ur.tgl_surat', '>=', $dateFrom);
        }
        if ($dateTo = $request->input('date_to')) {
            $query->where('ur.tgl_surat', '<=', $dateTo);
        }

        $surats = $query->orderBy('ur.created_at', 'desc')->paginate(15)->withQueryString();

        return view('admin.surat-manual.index', compact('surats'));
    }

    public function create()
    {
        $layanans = DB::table('ktd_layanan')
            ->where('status', 1)
            ->orderBy('nama')
            ->get(['id', 'nama']);

        $departments = DB::table('ktd_department')
            ->where('kategori', 'kantor')
            ->where('status', 1)
            ->orderBy('nama')
            ->get(['id', 'nama']);

        return view('admin.surat-manual.create', compact('layanans', 'departments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pemohon'     => 'required|string|max:255',
            'tgl_surat'   => 'required|date',
            'no_surat'    => 'required|string|max:255',
            'layanan_id'  => 'required|integer',
            'judul'       => 'required|string|max:50',
            'tujuan'      => 'nullable|string|max:255',
            'asal_pengirim' => 'nullable|string|max:255',
            'deskripsi'   => 'nullable|string',
            'file_surat'  => 'nullable|file|max:2048',
            'lampiran'    => 'nullable|file|max:2048',
        ]);

        $user = auth()->user();
        $noReq = 'MANUAL-' . now()->format('ymdHis') . '-' . rand(100, 999);

        $fileSuratName = null;
        $lampiranName = null;

        // Upload file surat
        if ($request->hasFile('file_surat')) {
            $file = $request->file('file_surat');
            $fileSuratName = $noReq . '_surat.' . $file->getClientOriginalExtension();
            $file->storeAs('surat/manual', $fileSuratName, 'public');
        }

        // Upload lampiran
        if ($request->hasFile('lampiran')) {
            $file = $request->file('lampiran');
            $lampiranName = $noReq . '_lampiran.' . $file->getClientOriginalExtension();
            $file->storeAs('surat/manual', $lampiranName, 'public');
        }

        DB::table('users_request')->insert([
            'no_req'      => $noReq,
            'pemohon'     => $validated['pemohon'],
            'no_surat'    => $validated['no_surat'],
            'tgl_surat'   => $validated['tgl_surat'],
            'user_id'     => $user->id,
            'dept_id'     => $user->dept_id,
            'layanan_id'  => $validated['layanan_id'],
            'judul'       => $validated['judul'],
            'tujuan'      => $validated['tujuan'] ?? null,
            'asal_pengirim' => $validated['asal_pengirim'] ?? null,
            'deskripsi'   => $validated['deskripsi'] ?? null,
            'file_surat'  => $fileSuratName,
            'lampiran'    => $lampiranName,
            'status'      => 'SUKSES',
            'staff_id'    => $user->id,
            'step'        => 1,
            'petugas'     => $user->id,
            'kategori'    => 'Personal',
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        return redirect()->route('admin.surat-manual.index')
            ->with('success', 'Surat berhasil disimpan dengan nomor req: ' . $noReq)
            ->with('print_url', route('admin.surat-manual.print', $noReq));
    }

    public function print($noReq)
    {
        $surat = DB::table('users_request as ur')
            ->leftJoin('ktd_department as dept', 'dept.id', '=', 'ur.dept_id')
            ->leftJoin('ktd_layanan as lay', 'lay.id', '=', 'ur.layanan_id')
            ->leftJoin('users as u', 'u.id', '=', 'ur.user_id')
            ->leftJoin('ktd_department as tujuan_dept', 'tujuan_dept.id', '=', 'ur.tujuan')
            ->select('ur.*', 'dept.nama as dept_name', 'lay.nama as layanan_name', 'tujuan_dept.nama as tujuan_name')
            ->where('ur.no_req', $noReq)
            ->first();

        if (!$surat) {
            abort(404);
        }

        $admin = auth()->user();

        return view('admin.surat-manual.print', compact('surat', 'admin'));
    }

    public function show($id)
    {
        $surat = DB::table('users_request as ur')
            ->leftJoin('ktd_department as dept', 'dept.id', '=', 'ur.dept_id')
            ->leftJoin('ktd_layanan as lay', 'lay.id', '=', 'ur.layanan_id')
            ->leftJoin('users as u', 'u.id', '=', 'ur.user_id')
            ->leftJoin('ktd_department as tujuan_dept', 'tujuan_dept.id', '=', 'ur.tujuan')
            ->select('ur.*', 'dept.nama as dept_name', 'lay.nama as layanan_name', 'tujuan_dept.nama as tujuan_name')
            ->where('ur.id', $id)
            ->where('ur.no_req', 'like', 'MANUAL-%')
            ->first();

        if (!$surat) {
            abort(404);
        }

        return view('admin.surat-manual.show', compact('surat'));
    }

    public function edit($id)
    {
        $surat = DB::table('users_request')
            ->where('id', $id)
            ->where('no_req', 'like', 'MANUAL-%')
            ->first();

        if (!$surat) {
            abort(404);
        }

        $layanans = DB::table('ktd_layanan')
            ->where('status', 1)
            ->orderBy('nama')
            ->get(['id', 'nama']);

        $departments = DB::table('ktd_department')
            ->where('kategori', 'kantor')
            ->where('status', 1)
            ->orderBy('nama')
            ->get(['id', 'nama']);

        return view('admin.surat-manual.edit', compact('surat', 'layanans', 'departments'));
    }

    public function update(Request $request, $id)
    {
        $surat = DB::table('users_request')
            ->where('id', $id)
            ->where('no_req', 'like', 'MANUAL-%')
            ->first();

        if (!$surat) {
            abort(404);
        }

        $validated = $request->validate([
            'pemohon'     => 'required|string|max:255',
            'tgl_surat'   => 'required|date',
            'no_surat'    => 'required|string|max:255',
            'layanan_id'  => 'required|integer',
            'judul'       => 'required|string|max:50',
            'tujuan'      => 'nullable|string|max:255',
            'asal_pengirim' => 'nullable|string|max:255',
            'deskripsi'   => 'nullable|string',
            'file_surat'  => 'nullable|file|max:2048',
            'lampiran'    => 'nullable|file|max:2048',
        ]);

        $fileSuratName = $surat->file_surat;
        $lampiranName = $surat->lampiran;

        // Upload file surat baru
        if ($request->hasFile('file_surat')) {
            // Hapus file lama
            if ($surat->file_surat) {
                Storage::disk('public')->delete('surat/manual/' . $surat->file_surat);
            }
            $file = $request->file('file_surat');
            $fileSuratName = $surat->no_req . '_surat.' . $file->getClientOriginalExtension();
            $file->storeAs('surat/manual', $fileSuratName, 'public');
        }

        // Upload lampiran baru
        if ($request->hasFile('lampiran')) {
            // Hapus file lama
            if ($surat->lampiran) {
                Storage::disk('public')->delete('surat/manual/' . $surat->lampiran);
            }
            $file = $request->file('lampiran');
            $lampiranName = $surat->no_req . '_lampiran.' . $file->getClientOriginalExtension();
            $file->storeAs('surat/manual', $lampiranName, 'public');
        }

        DB::table('users_request')
            ->where('id', $id)
            ->update([
                'pemohon'    => $validated['pemohon'],
                'no_surat'   => $validated['no_surat'],
                'tgl_surat'  => $validated['tgl_surat'],
                'layanan_id' => $validated['layanan_id'],
                'judul'      => $validated['judul'],
                'tujuan'     => $validated['tujuan'] ?? null,
                'asal_pengirim' => $validated['asal_pengirim'] ?? null,
                'deskripsi'  => $validated['deskripsi'] ?? null,
                'file_surat' => $fileSuratName,
                'lampiran'   => $lampiranName,
                'updated_at' => now(),
            ]);

        return redirect()->route('admin.surat-manual.show', $id)
            ->with('success', 'Surat berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $surat = DB::table('users_request')
            ->where('id', $id)
            ->where('no_req', 'like', 'MANUAL-%')
            ->first();

        if (!$surat) {
            abort(404);
        }

        // Hapus file
        if ($surat->file_surat) {
            Storage::disk('public')->delete('surat/manual/' . $surat->file_surat);
        }
        if ($surat->lampiran) {
            Storage::disk('public')->delete('surat/manual/' . $surat->lampiran);
        }

        DB::table('users_request')->where('id', $id)->delete();

        return redirect()->route('admin.surat-manual.index')
            ->with('success', 'Surat berhasil dihapus.');
    }

    public function download($id)
    {
        $surat = DB::table('users_request')
            ->where('id', $id)
            ->where('no_req', 'like', 'MANUAL-%')
            ->first();

        if (!$surat || !$surat->file_surat) {
            abort(404);
        }

        $path = 'surat/manual/' . $surat->file_surat;

        if (!Storage::disk('public')->exists($path)) {
            abort(404);
        }

        return Storage::disk('public')->download($path, $surat->file_surat);
    }
}
