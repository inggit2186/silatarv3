<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServiceController extends Controller
{
    /**
     * Display a listing of services.
     */
    public function index(Request $request)
    {
        $query = DB::table('ktd_layanan as l')
            ->leftJoin('ktd_department as d', 'd.id', '=', 'l.dept_id')
            ->select([
                'l.id',
                'l.nama',
                'l.deskripsi',
                'l.spesial',
                'l.status',
                'l.dept_id',
                'd.nama as dept_nama',
                DB::raw('(SELECT COUNT(*) FROM users_request WHERE users_request.layanan_id = l.id) as request_count'),
                DB::raw('(SELECT COUNT(*) FROM ktd_syarat WHERE ktd_syarat.layanan_id = l.id) as requirement_count')
            ]);

        // Search filter
        $search = $request->input('search');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('l.nama', 'like', "%{$search}%")
                  ->orWhere('l.deskripsi', 'like', "%{$search}%");
            });
        }

        // Department filter
        $dept_id = $request->input('dept_id');
        if ($dept_id) {
            $query->where('l.dept_id', $dept_id);
        }

        // Status filter
        $status = $request->input('status');
        if ($status !== null && $status !== '') {
            $query->where('l.status', $status);
        }

        // Special filter
        $spesial = $request->input('spesial');
        if ($spesial !== null && $spesial !== '') {
            $query->where('l.spesial', $spesial);
        }

        $services = $query->orderBy('l.nama')->paginate(15);

        // Get departments for filter
        $departments = DB::table('ktd_department')
            ->whereIn('status', [1, 2])
            ->orderBy('nama')
            ->get(['id', 'nama']);

        $statusOptions = [
            1 => 'Aktif',
            0 => 'Tidak Aktif',
        ];

        $spesialOptions = [
            1 => 'Ya',
            0 => 'Tidak',
        ];

        return view('admin.services.index', [
            'title' => 'Manajemen Layanan - SILATAR Admin',
            'services' => $services,
            'departments' => $departments,
            'statusOptions' => $statusOptions,
            'spesialOptions' => $spesialOptions,
            'filters' => [
                'search' => $search,
                'dept_id' => $dept_id,
                'status' => $status,
                'spesial' => $spesial,
            ],
        ]);
    }

    /**
     * Show the form for creating a new service.
     */
    public function create()
    {
        $departments = DB::table('ktd_department')
            ->whereIn('status', [1, 2])
            ->orderBy('nama')
            ->get(['id', 'nama']);

        return view('admin.services.create', [
            'title' => 'Tambah Layanan - SILATAR Admin',
            'departments' => $departments,
        ]);
    }

    /**
     * Store a newly created service.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'dept_id' => 'nullable|integer',
            'deskripsi' => 'nullable|string',
            'waktu' => 'nullable|string|max:100',
            'biaya' => 'nullable|integer|min:0',
            'output' => 'nullable|string',
            'spesial' => 'nullable|integer|in:0,1',
            'status' => 'nullable|integer|in:0,1',
        ]);

        $data = [
            'nama' => $validated['nama'],
            'dept_id' => $validated['dept_id'] ?? null,
            'deskripsi' => $validated['deskripsi'] ?? null,
            'waktu' => $validated['waktu'] ?? null,
            'biaya' => $validated['biaya'] ?? 0,
            'output' => $validated['output'] ?? null,
            'spesial' => $validated['spesial'] ?? 0,
            'status' => $validated['status'] ?? 1,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $serviceId = DB::table('ktd_layanan')->insertGetId($data);

        return redirect()->route('admin.services.edit', $serviceId)
            ->with('success', 'Layanan berhasil ditambahkan. Sekarang Tambahkan Persyaratan layanan.');
    }

    /**
     * Show the form for editing a service.
     */
    public function edit($id)
    {
        $service = DB::table('ktd_layanan')->where('id', $id)->first();

        if (!$service) {
            abort(404, 'Layanan tidak ditemukan.');
        }

        $departments = DB::table('ktd_department')
            ->whereIn('status', [1, 2])
            ->orderBy('nama')
            ->get(['id', 'nama']);

        // Get requirements/syarat
        $requirements = DB::table('ktd_syarat')
            ->where('layanan_id', $id)
            ->orderByDesc('wajib')
            ->orderBy('id')
            ->get();

        return view('admin.services.edit', [
            'title' => 'Edit Layanan - SILATAR Admin',
            'service' => $service,
            'departments' => $departments,
            'requirements' => $requirements,
        ]);
    }

    /**
     * Update the specified service.
     */
    public function update(Request $request, $id)
    {
        $service = DB::table('ktd_layanan')->where('id', $id)->first();

        if (!$service) {
            abort(404, 'Layanan tidak ditemukan.');
        }

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'dept_id' => 'nullable|integer',
            'deskripsi' => 'nullable|string',
            'waktu' => 'nullable|string|max:100',
            'biaya' => 'nullable|integer|min:0',
            'output' => 'nullable|string',
            'spesial' => 'nullable|integer|in:0,1',
            'status' => 'nullable|integer|in:0,1',
        ]);

        $data = [
            'nama' => $validated['nama'],
            'dept_id' => $validated['dept_id'] ?? null,
            'deskripsi' => $validated['deskripsi'] ?? null,
            'waktu' => $validated['waktu'] ?? null,
            'biaya' => $validated['biaya'] ?? 0,
            'output' => $validated['output'] ?? null,
            'spesial' => $validated['spesial'] ?? 0,
            'status' => $validated['status'] ?? 1,
            'updated_at' => now(),
        ];

        DB::table('ktd_layanan')->where('id', $id)->update($data);

        return redirect()->back()
            ->with('success', 'Layanan berhasil diperbarui.');
    }

    /**
     * Remove the specified service.
     */
    public function destroy($id)
    {
        $service = DB::table('ktd_layanan')->where('id', $id)->first();

        if (!$service) {
            return response()->json(['success' => false, 'message' => 'Layanan tidak ditemukan.'], 404);
        }

        // Check if service has requests
        $requestCount = DB::table('users_request')->where('layanan_id', $id)->count();
        if ($requestCount > 0) {
            return response()->json([
                'success' => false,
                'message' => "Layanan tidak dapat dihapus karena masih digunakan oleh {$requestCount} pengajuan."
            ], 400);
        }

        // Delete requirements first
        DB::table('ktd_syarat')->where('layanan_id', $id)->delete();

        // Delete service
        DB::table('ktd_layanan')->where('id', $id)->delete();

        return response()->json(['success' => true, 'message' => 'Layanan berhasil dihapus.']);
    }

    /**
     * Add requirement to service.
     */
    public function addRequirement(Request $request, $id)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'type' => 'required|string|in:file,text,textarea,date,datetime,number',
            'wajib' => 'nullable|integer|in:0,1',
            'deskripsi' => 'nullable|string',
        ]);

        $data = [
            'layanan_id' => $id,
            'syarat' => $validated['nama'],
            'type' => $validated['type'],
            'wajib' => $validated['wajib'] ?? 1,
            'keterangan' => $validated['deskripsi'] ?? null,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        DB::table('ktd_syarat')->insert($data);

        return response()->json(['success' => true, 'message' => 'Persyaratan berhasil ditambahkan.']);
    }

    /**
     * Update requirement.
     */
    public function updateRequirement(Request $request, $id, $reqId)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'type' => 'required|string|in:file,text,textarea,date,datetime,number',
            'wajib' => 'nullable|integer|in:0,1',
            'deskripsi' => 'nullable|string',
        ]);

        $data = [
            'syarat' => $validated['nama'],
            'type' => $validated['type'],
            'wajib' => $validated['wajib'] ?? 1,
            'keterangan' => $validated['deskripsi'] ?? null,
            'updated_at' => now(),
        ];

        DB::table('ktd_syarat')->where('id', $reqId)->where('layanan_id', $id)->update($data);

        return response()->json(['success' => true, 'message' => 'Persyaratan berhasil diperbarui.']);
    }

    /**
     * Delete requirement.
     */
    public function deleteRequirement($id, $reqId)
    {
        $deleted = DB::table('ktd_syarat')
            ->where('id', $reqId)
            ->where('layanan_id', $id)
            ->delete();

        if ($deleted) {
            return response()->json(['success' => true, 'message' => 'Persyaratan berhasil dihapus.']);
        }

        return response()->json(['success' => false, 'message' => 'Persyaratan tidak ditemukan.'], 404);
    }

    /**
     * Get service by ID (API).
     */
    public function show($id)
    {
        $service = DB::table('ktd_layanan as l')
            ->leftJoin('ktd_department as d', 'd.id', '=', 'l.dept_id')
            ->where('l.id', $id)
            ->select(['l.*', 'd.nama as dept_nama'])
            ->first();

        if (!$service) {
            return response()->json(['success' => false, 'message' => 'Layanan tidak ditemukan.'], 404);
        }

        return response()->json(['success' => true, 'service' => $service]);
    }
}
