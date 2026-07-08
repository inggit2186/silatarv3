<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TpgController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $tipe = $request->get('tipe'); // filter tipe layanan
        $status = $request->get('status'); // filter status

        // Base query - semua pengajuan TPG
        $query = DB::table('satker_pemberkasan')
            ->select([
                'id',
                'noreq',
                'tipe',
                'layanan_id',
                'user_id',
                'dept_id',
                'waktu',
                'item_id',
                'keterangan',
                'deskripsi',
                'status',
                'verifikator_id',
                'created_at',
                'updated_at',
                'metadata',
                'files',
            ]);

        // Exclude DRAFT status - hanya tampilkan yang sudah disubmit
        $query->where('status', '!=', 'DRAFT');

        // Role-based filtering
        if ($user->role !== 'admin') {
            // Petugas hanya bisa lihat dept_id sendiri
            $query->where('dept_id', $user->dept_id);
        }

        // Filter tipe layanan
        if ($tipe) {
            $query->where('tipe', $tipe);
        }

        // Filter status
        if ($status) {
            $query->where('status', $status);
        }

        // Order by created_at desc
        $pemberkasan = $query->orderByDesc('created_at')->paginate(20);

        // Ambil user info untuk setiap pengajuan
        $userIds = $pemberkasan->pluck('user_id')->unique();
        $users = DB::table('users')
            ->whereIn('id', $userIds)
            ->pluck('name', 'id');

        // Ambil dept info
        $deptIds = $pemberkasan->pluck('dept_id')->unique();
        $departments = DB::table('ktd_department')
            ->whereIn('id', $deptIds)
            ->pluck('nama', 'id');

        // Label tipe
        $tipeLabels = [
            'PAIS-TPG-SEMESTER' => 'TPG Semester',
            'PAIS-TPG-BULANAN' => 'TPG Bulanan',
            'PENMAD-TPG-BULANAN' => 'PENMAD TPG Bulanan',
            'PENMAD-PENGAWAS-BULANAN' => 'PENMAD Pengawas Bulanan',
        ];

        // Parse metadata dan files untuk setiap item
        foreach ($pemberkasan as $item) {
            $item->metadata_parsed = json_decode($item->metadata ?? '{}', true);
            $item->files_parsed = json_decode($item->files ?? '[]', true);
            $item->user_name = $users[$item->user_id] ?? 'Unknown';
            $item->dept_name = $departments[$item->dept_id] ?? 'Unknown';
            $item->tipe_label = $tipeLabels[$item->tipe] ?? $item->tipe;
        }

        // Statistik (exclude DRAFT)
        $stats = DB::table('satker_pemberkasan')
            ->selectRaw("
                COUNT(*) as total,
                SUM(status = 'SUBMITTED') as pending,
                SUM(status = 'DITERIMA') as diterima,
                SUM(status = 'SUKSES') as sukses
            ")
            ->where('status', '!=', 'DRAFT')
            ->when($user->role !== 'admin', fn($q) => $q->where('dept_id', $user->dept_id))
            ->first();

        // Dropdown filter options (exclude DRAFT)
        $statusOptions = ['SUBMITTED', 'PENDING', 'DITERIMA', 'DIPROSES', 'SUKSES', 'DITOLAK'];

        return view('admin.tpg.index', [
            'title' => 'Verifikasi TPG - SILATAR Admin',
            'breadcrumbs' => [
                ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['label' => 'Verifikasi TPG', 'url' => null],
            ],
            'pemberkasan' => $pemberkasan,
            'stats' => $stats,
            'tipeOptions' => array_keys($tipeLabels),
            'tipeLabels' => $tipeLabels,
            'statusOptions' => $statusOptions,
            'currentTipe' => $tipe,
            'currentStatus' => $status,
        ]);
    }

    public function show(int $id)
    {
        $user = Auth::user();

        $itemData = DB::table('satker_pemberkasan')->find($id);

        if (!$itemData) {
            abort(404);
        }

        // Check access
        if ($user->role !== 'admin' && $itemData->dept_id != $user->dept_id) {
            abort(403);
        }

        // Parse data
        $item = (object) [
            'id' => $itemData->id,
            'noreq' => $itemData->noreq,
            'tipe' => $itemData->tipe,
            'layanan_id' => $itemData->layanan_id,
            'user_id' => $itemData->user_id,
            'dept_id' => $itemData->dept_id,
            'waktu' => $itemData->waktu,
            'item_id' => $itemData->item_id,
            'keterangan' => $itemData->keterangan,
            'deskripsi' => $itemData->deskripsi,
            'status' => $itemData->status,
            'verifikator_id' => $itemData->verifikator_id,
            'created_at' => $itemData->created_at,
            'updated_at' => $itemData->updated_at,
            'metadata' => $itemData->metadata,
            'files' => $itemData->files,
            'metadata_parsed' => json_decode($itemData->metadata ?? '{}', true) ?? [],
            'files_parsed' => is_string($itemData->files) ? (json_decode($itemData->files, true) ?? []) : [],
        ];

        // User info
        $user = DB::table('users')->find($item->user_id);

        // Dept info
        $dept = DB::table('ktd_department')->find($item->dept_id);

        // Label
        $tipeLabels = [
            'PAIS-TPG-SEMESTER' => 'TPG Semester',
            'PAIS-TPG-BULANAN' => 'TPG Bulanan',
            'PENMAD-TPG-BULANAN' => 'PENMAD TPG Bulanan',
            'PENMAD-PENGAWAS-BULANAN' => 'PENMAD Pengawas Bulanan',
        ];

        return view('admin.tpg.show', [
            'title' => 'Verifikasi ' . ($tipeLabels[$item->tipe] ?? $item->tipe) . ' - SILATAR Admin',
            'breadcrumbs' => [
                ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['label' => 'Verifikasi TPG', 'url' => route('admin.tpg.index')],
                ['label' => "ID #{$item->id}", 'url' => null],
            ],
            'item' => $item,
            'user' => $user,
            'dept' => $dept,
            'tipeLabel' => $tipeLabels[$item->tipe] ?? $item->tipe,
        ]);
    }

    public function verify(Request $request, int $id)
    {
        $user = Auth::user();

        $item = DB::table('satker_pemberkasan')->find($id);
        if (!$item) {
            abort(404);
        }

        if ($user->role !== 'admin' && $item->dept_id != $user->dept_id) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:PENDING,DITERIMA,SUKSES',
        ]);

        DB::table('satker_pemberkasan')
            ->where('id', $id)
            ->update([
                'status' => $request->status,
                'verifikator_id' => Auth::id(),
                'updated_at' => now(),
            ]);

        return back()->with('success', "Pengajuan berhasil diverifikasi ke status {$request->status}");
    }

    public function reject(Request $request, int $id)
    {
        $user = Auth::user();

        $item = DB::table('satker_pemberkasan')->find($id);
        if (!$item) {
            abort(404);
        }

        if ($user->role !== 'admin' && $item->dept_id != $user->dept_id) {
            abort(403);
        }

        $request->validate([
            'keterangan' => 'required|string|max:1000',
        ]);

        DB::table('satker_pemberkasan')
            ->where('id', $id)
            ->update([
                'status' => 'DITOLAK',
                'keterangan' => $request->keterangan,
                'verifikator_id' => Auth::id(),
                'updated_at' => now(),
            ]);

        return redirect()
            ->route('admin.tpg.index')
            ->with('success', 'Pengajuan berhasil ditolak');
    }

    public function downloadFile(int $id, int $syaratId)
    {
        $user = Auth::user();

        $item = DB::table('satker_pemberkasan')->find($id);
        if (!$item) {
            abort(404);
        }

        if ($user->role !== 'admin' && $item->dept_id != $user->dept_id) {
            abort(403);
        }

        $files = json_decode($item->files ?? '[]', true);
        $file = collect($files)->firstWhere('syarat_id', $syaratId);

        if (!$file || empty($file['filename']) || $file['filename'] === 'NONE') {
            abort(404);
        }

        // Ambil user info untuk path
        $userData = DB::table('users')->find($item->user_id);

        $path = "{$userData->nomor_induk}/{$file['filename']}";

        if (!Storage::disk('users_berkas')->exists($path)) {
            abort(404);
        }

        return Storage::disk('users_berkas')->download($path);
    }

    public function previewFile(int $id, int $syaratId)
    {
        $user = Auth::user();

        $item = DB::table('satker_pemberkasan')->find($id);
        if (!$item) {
            abort(404);
        }

        if ($user->role !== 'admin' && $item->dept_id != $user->dept_id) {
            abort(403);
        }

        $files = json_decode($item->files ?? '[]', true);
        $file = collect($files)->firstWhere('syarat_id', $syaratId);

        if (!$file || empty($file['filename']) || $file['filename'] === 'NONE') {
            abort(404);
        }

        $userData = DB::table('users')->find($item->user_id);
        $path = "{$userData->nomor_induk}/{$file['filename']}";

        if (!Storage::disk('users_berkas')->exists($path)) {
            abort(404);
        }

        $fullPath = Storage::disk('users_berkas')->path($path);
        $mimeType = Storage::disk('users_berkas')->mimeType($path);

        return response()->file($fullPath, [
            'Content-Type' => $mimeType,
        ]);
    }
}
