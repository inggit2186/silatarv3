<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TpgController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $search = $request->get('search');
        $layananId = $request->get('layanan_id');

        $bulanOptions = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember',
        ];

        $defaultMonthIndex = (int) date('n') - 1;
        $defaultBulan = $defaultMonthIndex > 0
            ? $bulanOptions[$defaultMonthIndex - 1]
            : 'Desember';
        $defaultTahun = $defaultMonthIndex > 0 ? date('Y') : (date('Y') - 1);

        $currentBulan = $request->has('bulan') ? ($request->get('bulan') ?: null) : $defaultBulan;
        $currentTahun = $request->has('tahun') ? ($request->get('tahun') ?: null) : $defaultTahun;
        $currentStatus = $request->has('status') ? ($request->get('status') ?: null) : 'SUBMITTED';

        $tipeLabels = [
            'PAIS-TPG-SEMESTER' => 'TPG Semester',
            'PAIS-TPG-BULANAN' => 'TPG Bulanan',
            'PENMAD-TPG-BULANAN' => 'PENMAD TPG Bulanan',
            'PENMAD-PENGAWAS-BULANAN' => 'PENMAD Pengawas Bulanan',
        ];

        $allowedServiceIds = $this->getDynamicServiceIds($user, 'bulanan');

        $query = DB::table('satker_pemberkasan as p')
            ->join('users as u', 'u.id', '=', 'p.user_id')
            ->leftJoin('ktd_layanan as l', 'l.id', '=', 'p.layanan_id')
            ->leftJoin('ktd_department as d', 'd.id', '=', 'p.dept_id')
            ->select([
                'p.id',
                'p.noreq',
                'p.tipe',
                'p.layanan_id',
                'p.user_id',
                'p.dept_id',
                'p.waktu',
                'p.item_id',
                'p.keterangan',
                'p.deskripsi',
                'p.status',
                'p.verifikator_id',
                'p.created_at',
                'p.updated_at',
                'p.metadata',
                'p.files',
                'u.name as user_name',
                'u.nomor_induk as user_nip',
                'd.nama as dept_name',
                'l.nama as layanan_name',
            ])
            ->whereIn('p.layanan_id', !empty($allowedServiceIds) ? $allowedServiceIds : [0]);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('u.name', 'like', "%{$search}%")
                    ->orWhere('u.nomor_induk', 'like', "%{$search}%")
                    ->orWhere('p.noreq', 'like', "%{$search}%")
                    ->orWhere('p.deskripsi', 'like', "%{$search}%");
            });
        }

        if ($layananId) {
            $query->where('p.layanan_id', $layananId);
        }

        if ($currentStatus) {
            $query->where('p.status', $currentStatus);
        }

        if ($currentBulan) {
            $bulanNumber = $this->bulanToNumber($currentBulan);
            if ($bulanNumber) {
                $query->whereMonth('p.waktu', $bulanNumber);
            }
        }

        if ($currentTahun) {
            $query->whereYear('p.waktu', $currentTahun);
        }

        if ($user->role !== 'admin') {
            $query->where('p.dept_id', $user->dept_id);
        }

        $pemberkasan = $query
            ->orderByDesc('p.waktu')
            ->orderByDesc('p.created_at')
            ->paginate(20);

        foreach ($pemberkasan as $item) {
            $item->metadata_parsed = json_decode($item->metadata ?? '{}', true) ?: [];
            $item->files_parsed = json_decode($item->files ?? '[]', true) ?: [];
            $item->tipe_label = $tipeLabels[$item->tipe] ?? $item->tipe;
            $item->periode_label = $this->formatTpgPeriode($item);
            $item->status_label = $item->status === 'DRAFT'
                ? 'Draft'
                : strtoupper($item->status ?? '');
        }

        $statsQuery = DB::table('satker_pemberkasan as p')
            ->whereIn('p.layanan_id', !empty($allowedServiceIds) ? $allowedServiceIds : [0]);

        if ($currentBulan) {
            $bulanNumber = $this->bulanToNumber($currentBulan);
            if ($bulanNumber) {
                $statsQuery->whereMonth('p.waktu', $bulanNumber);
            }
        }

        if ($currentTahun) {
            $statsQuery->whereYear('p.waktu', $currentTahun);
        }

        $stats = $statsQuery
            ->selectRaw("\n                COUNT(*) as total,\n                SUM(p.status = 'DRAFT') as draft,\n                SUM(p.status IN ('SUBMITTED', 'PENDING')) as pending,\n                SUM(p.status = 'DITERIMA') as diterima,\n                SUM(p.status = 'DIPROSES') as diproses,\n                SUM(p.status = 'SUKSES') as sukses\n            ")
            ->first();

        $statusOptions = ['DRAFT', 'SUBMITTED', 'PENDING', 'DITERIMA', 'DIPROSES', 'SUKSES', 'DITOLAK'];

        return view('admin.tpg.index', [
            'title' => 'Verifikasi TPG - SILATAR Admin',
            'breadcrumbs' => [
                ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['label' => 'Verifikasi TPG', 'url' => null],
            ],
            'pemberkasan' => $pemberkasan,
            'stats' => $stats,
            'layananOptions' => $this->getDynamicLayananOptions($user, 'bulanan'),
            'statusOptions' => $statusOptions,
            'bulanOptions' => $bulanOptions,
            'currentLayananId' => $layananId,
            'currentStatus' => $currentStatus,
            'currentBulan' => $currentBulan,
            'currentTahun' => $currentTahun,
            'currentSearch' => $search,
            'activeTab' => 'bulanan',
        ]);
    }

    public function semesterIndex(Request $request)
    {
        $user = Auth::user();
        $search = $request->get('search');
        $semesterOptions = ['Ganjil', 'Genap'];

        $defaultYear = ((int) date('n') >= 7)
            ? date('Y') . '/' . (date('Y') + 1)
            : (date('Y') - 1) . '/' . date('Y');

        $tahunAjaranOptions = collect();
        $startYear = (int) date('Y') - 3;
        $endYear = (int) date('Y') + 1;
        for ($y = $startYear; $y <= $endYear; $y++) {
            $tahunAjaranOptions->push($y . '/' . ($y + 1));
        }
        $tahunAjaranOptions = $tahunAjaranOptions->unique()->values()->all();

        $currentSemester = $request->has('semester') ? ($request->get('semester') ?: null) : 'Ganjil';
        $currentTahunAjaran = $request->has('tahun_ajaran') ? ($request->get('tahun_ajaran') ?: null) : $defaultYear;
        $currentStatus = $request->has('status') ? ($request->get('status') ?: null) : 'SUBMITTED';
        $layananId = $request->get('layanan_id');

        $allowedServiceIds = $this->getDynamicServiceIds($user, 'semester');

        $query = DB::table('satker_pemberkasan as p')
            ->join('users as u', 'u.id', '=', 'p.user_id')
            ->leftJoin('ktd_layanan as l', 'l.id', '=', 'p.layanan_id')
            ->leftJoin('ktd_department as d', 'd.id', '=', 'p.dept_id')
            ->select([
                'p.id',
                'p.noreq',
                'p.tipe',
                'p.layanan_id',
                'p.user_id',
                'p.dept_id',
                'p.waktu',
                'p.item_id',
                'p.keterangan',
                'p.deskripsi',
                'p.status',
                'p.verifikator_id',
                'p.created_at',
                'p.updated_at',
                'p.metadata',
                'p.files',
                'u.name as user_name',
                'u.nomor_induk as user_nip',
                'd.nama as dept_name',
                'l.nama as layanan_name',
            ])
            ->whereIn('p.layanan_id', !empty($allowedServiceIds) ? $allowedServiceIds : [0]);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('u.name', 'like', "%{$search}%")
                    ->orWhere('u.nomor_induk', 'like', "%{$search}%")
                    ->orWhere('p.noreq', 'like', "%{$search}%")
                    ->orWhere('p.deskripsi', 'like', "%{$search}%");
            });
        }

        if ($layananId) {
            $query->where('p.layanan_id', $layananId);
        }

        if ($currentSemester) {
            $query->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(p.metadata, '$.semester')) = ?", [$currentSemester]);
        }

        if ($currentTahunAjaran) {
            $query->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(p.metadata, '$.tahun_pelajaran')) = ?", [$currentTahunAjaran]);
        }

        if ($currentStatus) {
            $query->where('p.status', $currentStatus);
        }

        if ($user->role !== 'admin') {
            $query->where('p.dept_id', $user->dept_id);
        }

        $pemberkasan = $query
            ->orderByDesc('p.waktu')
            ->orderByDesc('p.created_at')
            ->paginate(20);

        foreach ($pemberkasan as $item) {
            $item->metadata_parsed = json_decode($item->metadata ?? '{}', true) ?: [];
            $item->files_parsed = json_decode($item->files ?? '[]', true) ?: [];
            $item->tipe_label = $tipeLabels[$item->tipe] ?? $item->tipe;
            $item->periode_label = $this->formatTpgPeriode($item);
            $item->status_label = $item->status === 'DRAFT'
                ? 'Draft'
                : strtoupper($item->status ?? '');
        }

        $statsQuery = DB::table('satker_pemberkasan as p')
            ->whereIn('p.layanan_id', !empty($allowedServiceIds) ? $allowedServiceIds : [0]);

        if ($currentSemester) {
            $statsQuery->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(p.metadata, '$.semester')) = ?", [$currentSemester]);
        }

        if ($currentTahunAjaran) {
            $statsQuery->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(p.metadata, '$.tahun_pelajaran')) = ?", [$currentTahunAjaran]);
        }

        $stats = $statsQuery
            ->selectRaw("\n                COUNT(*) as total,\n                SUM(p.status = 'DRAFT') as draft,\n                SUM(p.status IN ('SUBMITTED', 'PENDING')) as pending,\n                SUM(p.status = 'DITERIMA') as diterima,\n                SUM(p.status = 'DIPROSES') as diproses,\n                SUM(p.status = 'SUKSES') as sukses\n            ")
            ->first();

        $statusOptions = ['DRAFT', 'SUBMITTED', 'PENDING', 'DITERIMA', 'DIPROSES', 'SUKSES', 'DITOLAK'];
        $layananOptions = $this->getDynamicLayananOptions($user, 'semester');

        return view('admin.tpg.semester-index', [
            'title' => 'Verifikasi TPG Semester - SILATAR Admin',
            'breadcrumbs' => [
                ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['label' => 'Verifikasi TPG', 'url' => route('admin.tpg.index')],
                ['label' => 'Semester', 'url' => null],
            ],
            'pemberkasan' => $pemberkasan,
            'stats' => $stats,
            'semesterOptions' => $semesterOptions,
            'tahunAjaranOptions' => $tahunAjaranOptions,
            'statusOptions' => $statusOptions,
            'layananOptions' => $layananOptions,
            'currentSemester' => $currentSemester,
            'currentTahunAjaran' => $currentTahunAjaran,
            'currentStatus' => $currentStatus,
            'currentLayananId' => $layananId,
            'currentSearch' => $search,
            'activeTab' => 'semester',
        ]);
    }

    private function bulanToNumber(?string $bulan): ?int
    {
        if (! $bulan) {
            return null;
        }

        $bulanMap = [
            'Januari' => 1,
            'Februari' => 2,
            'Maret' => 3,
            'April' => 4,
            'Mei' => 5,
            'Juni' => 6,
            'Juli' => 7,
            'Agustus' => 8,
            'September' => 9,
            'Oktober' => 10,
            'November' => 11,
            'Desember' => 12,
        ];

        return $bulanMap[$bulan] ?? null;
    }

    private function getDynamicServiceIds($user, string $mode): array
    {
        return DB::table('ktd_layanan as l')
            ->where('l.status', 1)
            ->where('l.tipe', $mode)
            ->when($user->role !== 'admin', fn ($q) => $q->where('l.dept_id', $user->dept_id))
            ->pluck('l.id')
            ->all();
    }

    private function getDynamicLayananOptions($user, string $mode): array
    {
        return DB::table('ktd_layanan as l')
            ->leftJoin('ktd_department as d', 'd.id', '=', 'l.dept_id')
            ->select([
                'l.id',
                DB::raw("CONCAT(d.nama, ' - ', l.nama) as full_name"),
            ])
            ->where('l.status', 1)
            ->where('l.tipe', $mode)
            ->when($user->role !== 'admin', fn ($q) => $q->where('l.dept_id', $user->dept_id))
            ->orderBy('d.nama')
            ->orderBy('l.nama')
            ->get()
            ->pluck('full_name', 'id')
            ->all();
    }

    private function formatTpgPeriode(object $item): string
    {
        $metadata = $item->metadata_parsed ?? [];

        if (! empty($metadata['bulan']) && ! empty($metadata['tahun'])) {
            return $metadata['bulan'] . ' ' . $metadata['tahun'];
        }

        if (! empty($metadata['bulan']) && ! empty($metadata['tahun_ajaran'])) {
            return $metadata['bulan'] . ' ' . $metadata['tahun_ajaran'];
        }

        if (! empty($item->waktu)) {
            return date('F Y', strtotime($item->waktu));
        }

        return '-';
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

        $pemohon = DB::table('users')->find($item->user_id);
        $dept = DB::table('ktd_department')->find($item->dept_id);

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
            'user' => $pemohon,
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

        // Notify user via WhatsApp
        $layananName = DB::table('ktd_layanan')->where('id', $item->layanan_id)->value('nama') ?? 'Layanan';
        PageController::notifyUserViaWhatsApp($item->user_id, $layananName, $item->noreq, $request->status);

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

        // Notify user via WhatsApp
        $layananName = DB::table('ktd_layanan')->where('id', $item->layanan_id)->value('nama') ?? 'Layanan';
        PageController::notifyUserViaWhatsApp($item->user_id, $layananName, $item->noreq, 'DITOLAK', $request->keterangan);

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
