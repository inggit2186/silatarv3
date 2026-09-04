<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\KtdPresensiFile;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class RekapPresensiController extends Controller
{
    /**
     * Check if user can access rekap presensi
     * Allowed: admin, superadmin, kepala, or users with dept_id = 4
     */
    protected function canAccess(): bool
    {
        $user = auth()->user();
        if (!$user) return false;

        // Admin roles have full access
        if (in_array($user->role, ['admin', 'superadmin', 'kepala'])) {
            return true;
        }

        // Users with dept_id = 4 (Sub-Bagian TU) can access
        if ($user->dept_id == 4) {
            return true;
        }

        return false;
    }

    /**
     * Tampilkan form rekap presensi
     */
    public function index(Request $request)
    {
        // Check access
        if (!$this->canAccess()) {
            abort(403, 'Anda tidak memiliki akses ke halaman rekap presensi.');
        }
        $departments = Department::whereIn('status', [1, 2])->orderBy('nama')->get();

        // Default: bulan sebelumnya
        $currentMonth = (int) date('m') - 1;
        if ($currentMonth < 1) {
            $currentMonth = 12;
            $currentYear = (int) date('Y') - 1;
        } else {
            $currentYear = (int) date('Y');
        }

        // Bank kategori groups: query dari DB, lalu aggregate by group_key
        $rawGroups = DB::table('users')
            ->join('tenaga_ktd', 'users.nomor_induk', '=', 'tenaga_ktd.nomor_induk')
            ->select(
                'users.bank_kategori',
                'tenaga_ktd.status',
                DB::raw('COALESCE(tenaga_ktd.serdik, \'unknown\') as serdik'),
                DB::raw('count(*) as total')
            )
            ->whereNotNull('users.bank_kategori')
            ->where('users.bank_kategori', '!=', '')
            ->groupBy('users.bank_kategori', 'tenaga_ktd.status', DB::raw('COALESCE(tenaga_ktd.serdik, \'unknown\')'))
            ->orderBy('users.bank_kategori')
            ->orderBy('tenaga_ktd.status')
            ->get();

        $aggregated = [];
        foreach ($rawGroups as $row) {
            $key = $this->buildGroupKey($row->bank_kategori, $row->status, $row->serdik);
            if (!isset($aggregated[$key])) {
                $aggregated[$key] = [
                    'group_key' => $key,
                    'bank_kategori' => $row->bank_kategori,
                    'status' => $row->status,
                    'serdik' => $row->serdik,
                    'total' => 0,
                ];
            }
            $aggregated[$key]['total'] += $row->total;
        }

        $bankKategoriGroups = collect($aggregated)
            ->map(fn($g) => [
                'group_key' => $g['group_key'],
                'label' => $this->buildGroupLabel($g['bank_kategori'], $g['status'], $g['serdik']),
                'total' => $g['total'],
            ])
            ->filter(fn($g) => $g['total'] > 0)
            ->values();

        $belumCount = DB::table('users')
            ->where(function ($q) {
                $q->whereNull('bank_kategori')->orWhere('bank_kategori', '=', '');
            })->count();

        if ($belumCount > 0) {
            $bankKategoriGroups->push([
                'group_key' => 'belum_dikategorikan',
                'label' => 'Belum Dikategorikan (' . $belumCount . ' user)',
                'total' => $belumCount,
            ]);
        }

        // History dengan filter & pagination
        $filterBulan = $request->input('filter_bulan');
        $filterTahun = $request->input('filter_tahun');

        $historyQuery = KtdPresensiFile::select('ktd_presensifiles.*')
            ->leftJoin('users', 'ktd_presensifiles.user_id', '=', 'users.id')
            ->select('ktd_presensifiles.*', 'users.name as generated_by');

        if ($filterBulan) {
            $historyQuery->where('bulan', $filterBulan);
        }
        if ($filterTahun) {
            $historyQuery->where('tahun', $filterTahun);
        }

        $historyQuery->orderBy('updated_at', 'desc');

        $generatedPaginated = $historyQuery->paginate(10)->withQueryString();

        // Map dept name -> dept_id
        $deptMap = Department::whereIn('status', [1, 2])
            ->get()
            ->pluck('id', 'nama')
            ->toArray();

        $generatedItems = [];
        foreach ($generatedPaginated as $record) {
            $label = $record->dept;
            $deptId = null;

            if ($record->group_key) {
                $groupMatch = $bankKategoriGroups->firstWhere('group_key', $record->group_key);
                $label = $groupMatch ? $groupMatch['label'] : $record->group_key;
            } else {
                $deptId = $deptMap[$record->dept] ?? null;
            }

            $generatedItems[] = [
                'dept' => $record->dept,
                'dept_id' => $deptId,
                'group_key' => $record->group_key,
                'label' => $label,
                'bulan' => $record->bulan,
                'tahun' => $record->tahun,
                'updated_at' => $record->updated_at,
                'generated_by' => $record->generated_by ?? '-',
            ];
        }

        return view('admin.rekap-presensi.index', compact(
            'departments',
            'currentMonth',
            'currentYear',
            'bankKategoriGroups',
            'generatedItems',
            'generatedPaginated',
            'filterBulan',
            'filterTahun'
        ));
    }

    /**
     * Generate rekap presensi
     */
    public function generate(Request $request)
    {
        // Check access
        if (!$this->canAccess()) {
            abort(403, 'Anda tidak memiliki akses ke halaman rekap presensi.');
        }

        $method = $request->input('method', 'unit_kerja');
        $isAjax = $request->ajax() || $request->expectsJson();

        try {
            if ($method === 'unit_kerja') {
                return $this->generateByDept($request, $isAjax);
            } elseif ($method === 'kategori_bank') {
                return $this->generateByGroup($request, $isAjax);
            }

            if ($isAjax) {
                return response()->json(['success' => false, 'message' => 'Metode generate tidak valid']);
            }
            return back()->with('error', 'Metode generate tidak valid');

        } catch (\Exception $e) {
            Log::error("Generate rekap presensi error: " . $e->getMessage());
            if ($isAjax) {
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan server: ' . $e->getMessage()], 500);
            }
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Generate berdasarkan Unit Kerja (metode lama)
     */
    protected function generateByDept(Request $request, bool $isAjax = false)
    {
        try {
            $request->validate([
                'dept_id' => 'required|integer|exists:ktd_department,id',
                'month' => 'required|integer|between:1,12',
                'year' => 'required|integer|between:2020,2030',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($isAjax) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
            }
            throw $e;
        }

        $deptId = $request->dept_id;
        $month = $request->month;
        $year = $request->year;

        $dept = Department::find($deptId);
        if (!$dept) {
            if ($isAjax) return response()->json(['success' => false, 'message' => 'Unit kerja tidak ditemukan']);
            return back()->with('error', 'Unit kerja tidak ditemukan');
        }

        $users = DB::table('users')
            ->where('dept_id', $deptId)
            ->whereNotNull('nomor_induk')
            ->where('nomor_induk', '!=', '')
            ->select('id', 'name', 'nomor_induk')
            ->orderBy('name')
            ->get();

        if ($users->isEmpty()) {
            if ($isAjax) return response()->json(['success' => false, 'message' => 'Tidak ada user di unit kerja ini']);
            return back()->with('error', 'Tidak ada user di unit kerja ini');
        }

        $title = strtoupper($dept->nama);
        $cleanName = preg_replace('/[^a-zA-Z0-9]/', '_', $dept->nama);
        $groupKey = null;

        $result = $this->processAndSave($users, $title, $cleanName, $month, $year, $dept->nama, $groupKey, $deptId);

        if ($isAjax) {
            return response()->json($result, $result['success'] ? 200 : 500);
        }

        $flashMethod = $result['success'] ? 'success' : 'error';
        return back()->with($flashMethod, $result['message'])
            ->with('dept_id', $deptId)
            ->with('group_key', $groupKey)
            ->with('month', $month)
            ->with('year', $year);
    }

    /**
     * Generate berdasarkan Kategori Bank
     */
    protected function generateByGroup(Request $request, bool $isAjax = false)
    {
        try {
            $request->validate([
                'group_key' => 'required|string',
                'month' => 'required|integer|between:1,12',
                'year' => 'required|integer|between:2020,2030',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($isAjax) return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
            throw $e;
        }

        $groupKey = $request->group_key;
        $month = $request->month;
        $year = $request->year;

        if ($groupKey === 'belum_dikategorikan') {
            return $this->generateBelumDikategorikan($month, $year, $isAjax);
        }

        $group = $this->resolveGroup($groupKey);
        if (!$group) {
            if ($isAjax) return response()->json(['success' => false, 'message' => 'Kelompok tidak ditemukan: ' . $groupKey]);
            return back()->with('error', 'Kelompok tidak ditemukan: ' . $groupKey);
        }

        $query = DB::table('users')
            ->join('tenaga_ktd', 'users.nomor_induk', '=', 'tenaga_ktd.nomor_induk')
            ->where('users.bank_kategori', $group['bank_kategori'])
            ->where('tenaga_ktd.status', $group['status']);

        if (isset($group['serdik'])) {
            $query->where(function ($q) use ($group) {
                $q->where('tenaga_ktd.serdik', $group['serdik'])
                    ->orWhereNull('tenaga_ktd.serdik');
            });
        }

        $users = $query
            ->whereNotNull('users.nomor_induk')
            ->where('users.nomor_induk', '!=', '')
            ->select('users.id', 'users.name', 'users.nomor_induk')
            ->orderBy('users.name')
            ->get();

        if ($users->isEmpty()) {
            if ($isAjax) return response()->json(['success' => false, 'message' => 'Tidak ada user di kelompok ini']);
            return back()->with('error', 'Tidak ada user di kelompok ini');
        }

        $title = $group['label'];
        $cleanName = preg_replace('/[^a-zA-Z0-9]/', '_', $groupKey);

        $result = $this->processAndSave($users, $title, $cleanName, $month, $year, $title, $groupKey);

        if ($isAjax) return response()->json($result, $result['success'] ? 200 : 500);

        $flashMethod = $result['success'] ? 'success' : 'error';
        return back()->with($flashMethod, $result['message'])
            ->with('group_key', $groupKey)
            ->with('month', $month)
            ->with('year', $year);
    }

    /**
     * Generate untuk user yang belum punya kategori_bank
     */
    protected function generateBelumDikategorikan($month, $year, $isAjax = false)
    {
        $users = DB::table('users')
            ->where(function ($q) {
                $q->whereNull('bank_kategori')->orWhere('bank_kategori', '=', '');
            })
            ->whereNotNull('nomor_induk')
            ->where('nomor_induk', '!=', '')
            ->select('id', 'name', 'nomor_induk')
            ->orderBy('name')
            ->get();

        if ($users->isEmpty()) {
            if ($isAjax) return response()->json(['success' => false, 'message' => 'Tidak ada user tanpa kategori bank']);
            return back()->with('error', 'Tidak ada user tanpa kategori bank');
        }

        $title = 'BELUM DIKATEGORIKAN';
        $cleanName = 'belum_dikategorikan';
        $groupKey = 'belum_dikategorikan';

        $result = $this->processAndSave($users, $title, $cleanName, $month, $year, $title, $groupKey);

        if ($isAjax) return response()->json($result, $result['success'] ? 200 : 500);

        $flashMethod = $result['success'] ? 'success' : 'error';
        return back()->with($flashMethod, $result['message']);
    }

    /**
     * Proses generate Excel dan simpan ke storage + DB
     * Return: ['success' => bool, 'message' => string]
     */
    protected function processAndSave(
        $users,
        string $title,
        string $cleanName,
        int $month,
        int $year,
        string $deptLabel,
        ?string $groupKey,
        ?int $deptId = null
    ): array {
        $nips = $users->pluck('nomor_induk')->toArray();
        $presensiData = DB::table('ktd_presensi')
            ->whereIn('user_nip', $nips)
            ->whereYear('tanggal', $year)
            ->whereMonth('tanggal', $month)
            ->select('user_nip', 'tanggal', 'm_absen', 'p_absen', 'status')
            ->get()
            ->groupBy('user_nip')
            ->map(function ($rows) {
                return $rows->keyBy(function ($row) {
                    return (int) date('d', strtotime($row->tanggal));
                });
            });

        // Get tukin data
        $periode = sprintf('%04d-%02d', $year, $month);
        $tukinData = DB::table('ktd_tukin')
            ->whereIn('user_nip', $nips)
            ->where('periode', $periode)
            ->get()
            ->keyBy('user_nip');

        try {
            $presensiFile = $this->generatePresensiExcel($users, $presensiData, $title, $month, $year);
            $detailFile = $this->generateDetailPresensiExcel($users, $presensiData, $title, $month, $year);
            $tukinFile = $this->generateTukinExcel($users, $tukinData, $title, $month, $year);

            $rekapDir = storage_path('app/rekap_presensi');
            if (!file_exists($rekapDir)) {
                mkdir($rekapDir, 0755, true);
            }
            $deptDir = "{$rekapDir}/{$cleanName}";
            if (!file_exists($deptDir)) {
                mkdir($deptDir, 0755, true);
            }

            $rekapFilename = "rekap_presensi_{$cleanName}_{$year}_{$month}.xlsx";
            $rekapPath = "rekap_presensi/{$cleanName}/{$rekapFilename}";
            file_put_contents(storage_path("app/{$rekapPath}"), base64_decode($presensiFile));

            $detailFilename = "detail_presensi_{$cleanName}_{$year}_{$month}.xlsx";
            $detailPath = "rekap_presensi/{$cleanName}/{$detailFilename}";
            file_put_contents(storage_path("app/{$detailPath}"), base64_decode($detailFile));

            $tukinFilename = "rekap_tukin_{$cleanName}_{$year}_{$month}.xlsx";
            $tukinPath = "rekap_presensi/{$cleanName}/{$tukinFilename}";
            file_put_contents(storage_path("app/{$tukinPath}"), base64_decode($tukinFile));

            // Save/update ke ktd_presensifiles
            $existingQuery = KtdPresensiFile::where('dept', $deptLabel)
                ->where('bulan', $month)
                ->where('tahun', $year);

            if ($groupKey) {
                $existingQuery->where('group_key', $groupKey);
            } else {
                $existingQuery->whereNull('group_key');
            }

            $existing = $existingQuery->first();

            if ($existing) {
                // Hapus file LAMA jika path berbeda dari yang baru ditulis
                if ($existing->presensi && $existing->presensi !== $detailPath && file_exists(storage_path('app/' . $existing->presensi))) {
                    unlink(storage_path('app/' . $existing->presensi));
                }
                if ($existing->uangmakan && $existing->uangmakan !== $rekapPath && file_exists(storage_path('app/' . $existing->uangmakan))) {
                    unlink(storage_path('app/' . $existing->uangmakan));
                }
                if (isset($existing->tukin) && $existing->tukin && $existing->tukin !== $tukinPath && file_exists(storage_path('app/' . $existing->tukin))) {
                    unlink(storage_path('app/' . $existing->tukin));
                }

                $existing->update([
                    'presensi' => $detailPath,
                    'uangmakan' => $rekapPath,
                    'tukin' => $tukinPath,
                    'user_id' => auth()->id(),
                ]);
            } else {
                KtdPresensiFile::create([
                    'dept' => $deptLabel,
                    'group_key' => $groupKey,
                    'user_id' => auth()->id(),
                    'bulan' => $month,
                    'tahun' => $year,
                    'presensi' => $detailPath,
                    'uangmakan' => $rekapPath,
                    'tukin' => $tukinPath,
                ]);
            }

            Log::info("Rekap presensi berhasil digenerate", [
                'dept' => $deptLabel,
                'group_key' => $groupKey,
                'month' => $month,
                'year' => $year,
                'users' => $users->count(),
            ]);

            return ['success' => true, 'message' => 'Rekap presensi berhasil digenerate untuk ' . $deptLabel];

        } catch (\Exception $e) {
            Log::error("Gagal generate rekap presensi: " . $e->getMessage());
            return ['success' => false, 'message' => 'Gagal generate rekap presensi: ' . $e->getMessage()];
        }
    }

    /**
     * Download file presensi (rekap absensi dengan value 1)
     */
    public function downloadPresensi(Request $request)
    {
        // Check access
        if (!$this->canAccess()) {
            abort(403, 'Anda tidak memiliki akses ke halaman rekap presensi.');
        }

        $request->validate([
            'dept_id' => 'required|integer',
            'month' => 'required|integer',
            'year' => 'required|integer',
        ]);

        $dept = Department::find($request->dept_id);
        if (!$dept) {
            return back()->with('error', 'Unit kerja tidak ditemukan');
        }

        $record = KtdPresensiFile::where('dept', $dept->nama)
            ->where('bulan', $request->month)
            ->where('tahun', $request->year)
            ->whereNull('group_key')
            ->first();

        return $this->sendFile($record, 'uangmakan');
    }

    /**
     * Download file detail presensi (jam masuk/pulang)
     */
    public function downloadDetail(Request $request)
    {
        $request->validate([
            'dept_id' => 'required|integer',
            'month' => 'required|integer',
            'year' => 'required|integer',
        ]);

        $dept = Department::find($request->dept_id);
        if (!$dept) {
            return back()->with('error', 'Unit kerja tidak ditemukan');
        }

        $record = KtdPresensiFile::where('dept', $dept->nama)
            ->where('bulan', $request->month)
            ->where('tahun', $request->year)
            ->whereNull('group_key')
            ->first();

        return $this->sendFile($record, 'presensi');
    }

    /**
     * Download berdasarkan group_key
     */
    public function downloadByGroup(Request $request)
    {
        // Check access
        if (!$this->canAccess()) {
            abort(403, 'Anda tidak memiliki akses ke halaman rekap presensi.');
        }
        $request->validate([
            'group_key' => 'required|string',
            'month' => 'required|integer',
            'year' => 'required|integer',
            'type' => 'required|in:presensi,detail,tukin',
        ]);

        $record = KtdPresensiFile::where('group_key', $request->group_key)
            ->where('bulan', $request->month)
            ->where('tahun', $request->year)
            ->first();

        $columnMap = [
            'presensi' => 'uangmakan',
            'detail' => 'presensi',
            'tukin' => 'tukin',
        ];

        return $this->sendFile($record, $columnMap[$request->type]);
    }

    /**
     * Kirim file download
     */
    protected function sendFile($record, string $column)
    {
        if (!$record || !$record->{$column}) {
            return back()->with('error', 'File tidak ditemukan');
        }

        $fullPath = storage_path('app/' . $record->{$column});
        if (!file_exists($fullPath)) {
            return back()->with('error', 'File tidak ditemukan di server');
        }

        $filename = basename($record->{$column});

        return response()->download($fullPath, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    /**
     * Download file tukin
     */
    public function downloadTukin(Request $request)
    {
        $request->validate([
            'dept_id' => 'required|integer',
            'month' => 'required|integer',
            'year' => 'required|integer',
        ]);

        $dept = Department::find($request->dept_id);
        if (!$dept) {
            return back()->with('error', 'Unit kerja tidak ditemukan');
        }

        $record = KtdPresensiFile::where('dept', $dept->nama)
            ->where('bulan', $request->month)
            ->where('tahun', $request->year)
            ->whereNull('group_key')
            ->first();

        return $this->sendFile($record, 'tukin');
    }

    /**
     * Generate Excel Tukin
     */
    protected function generateTukinExcel($users, $tukinData, string $title, int $month, int $year): string
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Title
        $sheet->mergeCells('A1:K1');
        $sheet->setCellValue('A1', 'REKAP TUKIN - ' . $title);
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells('A2:K2');
        $sheet->setCellValue('A2', 'Bulan: ' . $this->getMonthName($month) . ' ' . $year);
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(11);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Headers
        $headers = [
            'No', 'NIP', 'Nama', 'TUKIN', 'TK Jumlah', 'TK (%)',
            'TL (Telat)', 'TL (%)', 'PSW', 'Hukdis', 'Total Potongan'
        ];

        foreach ($headers as $col => $header) {
            $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col + 1);
            $sheet->setCellValue("{$colLetter}5", $header);
        }

        // Style headers - use K as last column (11 columns)
        $lastCol = 'K';
        $sheet->getStyle("A5:{$lastCol}5")->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
        $sheet->getStyle("A5:{$lastCol}5")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('7C3AED');
        $sheet->getStyle("A5:{$lastCol}5")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Data
        $rowNum = 6;
        $no = 1;
        $totalTukin = 0;
        $totalPotongan = 0;

        foreach ($users as $user) {
            $tukin = $tukinData->get($user->nomor_induk);

            $sheet->setCellValue("A{$rowNum}", $no++);
            $sheet->setCellValue("B{$rowNum}", $user->nomor_induk);
            $sheet->setCellValue("C{$rowNum}", $user->name);
            $sheet->setCellValue("D{$rowNum}", $tukin ? $tukin->tukin : 0);
            $sheet->setCellValue("E{$rowNum}", $tukin ? $tukin->tk_jumlah : 0);
            $sheet->setCellValue("F{$rowNum}", $tukin ? $tukin->tk_persen : 0);
            $sheet->setCellValue("G{$rowNum}", $tukin ? $tukin->tl : 0);
            $sheet->setCellValue("H{$rowNum}", $tukin ? $tukin->tl_persen : 0);
            $sheet->setCellValue("I{$rowNum}", $tukin ? $tukin->psw : 0);
            $sheet->setCellValue("J{$rowNum}", $tukin ? $tukin->hukdis : 0);
            $sheet->setCellValue("K{$rowNum}", $tukin ? $tukin->total_potongan : 0);

            $totalTukin += $tukin ? $tukin->tukin : 0;
            $totalPotongan += $tukin ? $tukin->total_potongan : 0;

            $rowNum++;
        }

        // Total row
        $sheet->setCellValue("A{$rowNum}", '');
        $sheet->setCellValue("B{$rowNum}", '');
        $sheet->setCellValue("C{$rowNum}", 'TOTAL');
        $sheet->setCellValue("D{$rowNum}", $totalTukin);
        $sheet->setCellValue("E{$rowNum}", '');
        $sheet->setCellValue("F{$rowNum}", '');
        $sheet->setCellValue("G{$rowNum}", '');
        $sheet->setCellValue("H{$rowNum}", '');
        $sheet->setCellValue("I{$rowNum}", '');
        $sheet->setCellValue("J{$rowNum}", '');
        $sheet->setCellValue("K{$rowNum}", $totalPotongan);

        $sheet->getStyle("A{$rowNum}:K{$rowNum}")->getFont()->setBold(true);

        // Auto-size columns
        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(30);
        $sheet->getColumnDimension('D')->setWidth(15);
        $sheet->getColumnDimension('E')->setWidth(15);
        $sheet->getColumnDimension('F')->setWidth(10);
        $sheet->getColumnDimension('G')->setWidth(12);
        $sheet->getColumnDimension('H')->setWidth(10);
        $sheet->getColumnDimension('I')->setWidth(12);
        $sheet->getColumnDimension('J')->setWidth(12);
        $sheet->getColumnDimension('K')->setWidth(15);

        // Save to temp file then read
        $tempFile = tempnam(sys_get_temp_dir(), 'tukin_');
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save($tempFile);
        $excelContent = file_get_contents($tempFile);
        unlink($tempFile);

        return base64_encode($excelContent);
    }

    /**
     * Build group_key dari bank_kategori + status + serdik
     * Normalisasi bank name: PPPK_NAGARI -> nagari, PPPK_BSI -> bsi
     */
    protected function buildGroupKey(string $bankKategori, string $status, string $serdik): string
    {
        $bidang = str_starts_with($bankKategori, 'KEAGAMAAN') ? 'keagamaan' : 'kependidikan';

        // Parse bank name dari bank_kategori, normalisasi PPPK_ prefix
        $bankParts = explode('_', $bankKategori);
        $rawBank = end($bankParts);
        $bankSlug = strtolower(str_replace(' ', '_', $rawBank));
        // Normalisasi: "pppk_nagari" -> "nagari", "pppk_bsi" -> "bsi"
        $bankSlug = preg_replace('/^pppk_/', '', $bankSlug);

        $serdikSlug = match ($serdik) {
            'sertifikasi' => 'serdik',
            'non-sertifikasi' => 'nonserdik',
            'non-guru' => 'nonguru',
            default => 'unknown',
        };

        if ($bidang === 'keagamaan') {
            return "{$status}_{$bidang}_{$bankSlug}";
        }

        return "{$status}_{$bidang}_{$bankSlug}_{$serdikSlug}";
    }

    /**
     * Label tampilan berdasarkan group_key (hardcode sesuai 21 grup)
     */
    protected function buildGroupLabel(string $bankKategori, string $status, string $serdik): string
    {
        $groupKey = $this->buildGroupKey($bankKategori, $status, $serdik);

        $labels = [
            'pns_keagamaan_bank_nagari'          => 'PNS - KEAGAMAAN BANK NAGARI',
            'pppk_keagamaan_bank_nagari'         => 'PPPK - KEAGAMAAN BANK NAGARI',
            'pns_keagamaan_nagari'               => 'PNS - KEAGAMAAN PPPK NAGARI',
            'pppk_keagamaan_nagari'              => 'PPPK - KEAGAMAAN PPPK NAGARI',
            'pns_keagamaan_bsi'                  => 'PNS - KEAGAMAAN BSI',
            'cpns_keagamaan_bsi'                 => 'CPNS - KEAGAMAAN BSI',
            'pns_kependidikan_bank_nagari_serdik'     => 'PNS - KEPENDIDIKAN BANK NAGARI - Sertifikasi',
            'pns_kependidikan_bank_nagari_nonserdik'  => 'PNS - KEPENDIDIKAN BANK NAGARI - Non-sertifikasi',
            'pns_kependidikan_bank_nagari_nonguru'    => 'PNS - KEPENDIDIKAN BANK NAGARI - Non-guru',
            'pns_kependidikan_bank_nagari_unknown'    => 'PNS - KEPENDIDIKAN BANK NAGARI - Unknown',
            'pppk_kependidikan_bsi_serdik'      => 'PPPK - KEPENDIDIKAN PPPK BSI - Sertifikasi',
            'pppk_kependidikan_bsi_nonserdik'   => 'PPPK - KEPENDIDIKAN PPPK BSI - Non-sertifikasi',
            'pppk_kependidikan_bsi_nonguru'     => 'PPPK - KEPENDIDIKAN PPPK BSI - Non-guru',
            'pppk_kependidikan_bsi_unknown'     => 'PPPK - KEPENDIDIKAN PPPK BSI - Unknown',
            'pppk_kependidikan_nagari_serdik'     => 'PPPK - KEPENDIDIKAN PPPK NAGARI - Sertifikasi',
            'pppk_kependidikan_nagari_nonserdik'  => 'PPPK - KEPENDIDIKAN PPPK NAGARI - Non-sertifikasi',
            'pppk_kependidikan_nagari_nonguru'    => 'PPPK - KEPENDIDIKAN PPPK NAGARI - Non-guru',
            'pppk_kependidikan_nagari_unknown'    => 'PPPK - KEPENDIDIKAN PPPK NAGARI - Unknown',
            'pns_kependidikan_bri_serdik'         => 'PNS - KEPENDIDIKAN BRI - Sertifikasi',
            'pns_kependidikan_bri_nonserdik'      => 'PNS - KEPENDIDIKAN BRI - Non-sertifikasi',
            'pns_kependidikan_bri_nonguru'        => 'PNS - KEPENDIDIKAN BRI - Non-guru',
            'pns_kependidikan_bri_unknown'        => 'PNS - KEPENDIDIKAN BRI - Unknown',
            'pppk_kependidikan_bri_serdik'        => 'PPPK - KEPENDIDIKAN BRI - Sertifikasi',
            'pppk_kependidikan_bri_nonserdik'     => 'PPPK - KEPENDIDIKAN BRI - Non-sertifikasi',
            'pppk_kependidikan_bri_nonguru'       => 'PPPK - KEPENDIDIKAN BRI - Non-guru',
            'pppk_kependidikan_bri_unknown'       => 'PPPK - KEPENDIDIKAN BRI - Unknown',
            'pns_kependidikan_bsi_serdik'         => 'PNS - KEPENDIDIKAN BSI - Sertifikasi',
            'pns_kependidikan_bsi_nonserdik'      => 'PNS - KEPENDIDIKAN BSI - Non-sertifikasi',
            'pns_kependidikan_bsi_nonguru'        => 'PNS - KEPENDIDIKAN BSI - Non-guru',
            'pns_kependidikan_bsi_unknown'        => 'PNS - KEPENDIDIKAN BSI - Unknown',
            'cpns_kependidikan_bsi_nonserdik'     => 'CPNS - KEPENDIDIKAN BSI - Non-sertifikasi',
            'pppk_kependidikan_bsi_serdik_bsi'      => 'PPPK - KEPENDIDIKAN BSI - Sertifikasi',
            'pppk_kependidikan_bsi_nonserdik_bsi'   => 'PPPK - KEPENDIDIKAN BSI - Non-sertifikasi',
        ];

        return $labels[$groupKey] ?? strtoupper($status) . ' - ' . $bankKategori;
    }

    /**
     * Resolve group definition dari group_key untuk query user
     */
    protected function resolveGroup(string $groupKey): ?array
    {
        $labels = [
            'pns_keagamaan_bank_nagari'              => ['bk' => 'KEAGAMAAN_BANK NAGARI', 'status' => 'pns'],
            'pppk_keagamaan_bank_nagari'             => ['bk' => 'KEAGAMAAN_BANK NAGARI', 'status' => 'pppk'],
            'pns_keagamaan_nagari'                   => ['bk' => 'KEAGAMAAN_PPPK_NAGARI', 'status' => 'pns'],
            'pppk_keagamaan_nagari'                  => ['bk' => 'KEAGAMAAN_PPPK_NAGARI', 'status' => 'pppk'],
            'pns_keagamaan_bsi'                      => ['bk' => 'KEAGAMAAN_BSI', 'status' => 'pns'],
            'cpns_keagamaan_bsi'                     => ['bk' => 'KEAGAMAAN_BSI', 'status' => 'cpns'],
            'pns_kependidikan_bank_nagari_serdik'     => ['bk' => 'KEPENDIDIKAN_BANK NAGARI', 'status' => 'pns', 'serdik' => 'sertifikasi'],
            'pns_kependidikan_bank_nagari_nonserdik'  => ['bk' => 'KEPENDIDIKAN_BANK NAGARI', 'status' => 'pns', 'serdik' => 'non-sertifikasi'],
            'pns_kependidikan_bank_nagari_nonguru'    => ['bk' => 'KEPENDIDIKAN_BANK NAGARI', 'status' => 'pns', 'serdik' => 'non-guru'],
            'pns_kependidikan_bank_nagari_unknown'    => ['bk' => 'KEPENDIDIKAN_BANK NAGARI', 'status' => 'pns', 'serdik' => 'unknown'],
            'pppk_kependidikan_bsi_serdik'      => ['bk' => 'KEPENDIDIKAN_PPPK_BSI', 'status' => 'pppk', 'serdik' => 'sertifikasi'],
            'pppk_kependidikan_bsi_nonserdik'   => ['bk' => 'KEPENDIDIKAN_PPPK_BSI', 'status' => 'pppk', 'serdik' => 'non-sertifikasi'],
            'pppk_kependidikan_bsi_nonguru'     => ['bk' => 'KEPENDIDIKAN_PPPK_BSI', 'status' => 'pppk', 'serdik' => 'non-guru'],
            'pppk_kependidikan_bsi_unknown'     => ['bk' => 'KEPENDIDIKAN_PPPK_BSI', 'status' => 'pppk', 'serdik' => 'unknown'],
            'pppk_kependidikan_nagari_serdik'     => ['bk' => 'KEPENDIDIKAN_PPPK_NAGARI', 'status' => 'pppk', 'serdik' => 'sertifikasi'],
            'pppk_kependidikan_nagari_nonserdik'  => ['bk' => 'KEPENDIDIKAN_PPPK_NAGARI', 'status' => 'pppk', 'serdik' => 'non-sertifikasi'],
            'pppk_kependidikan_nagari_nonguru'    => ['bk' => 'KEPENDIDIKAN_PPPK_NAGARI', 'status' => 'pppk', 'serdik' => 'non-guru'],
            'pppk_kependidikan_nagari_unknown'    => ['bk' => 'KEPENDIDIKAN_PPPK_NAGARI', 'status' => 'pppk', 'serdik' => 'unknown'],
            'pns_kependidikan_bri_serdik'         => ['bk' => 'KEPENDIDIKAN_BRI', 'status' => 'pns', 'serdik' => 'sertifikasi'],
            'pns_kependidikan_bri_nonserdik'      => ['bk' => 'KEPENDIDIKAN_BRI', 'status' => 'pns', 'serdik' => 'non-sertifikasi'],
            'pns_kependidikan_bri_nonguru'        => ['bk' => 'KEPENDIDIKAN_BRI', 'status' => 'pns', 'serdik' => 'non-guru'],
            'pns_kependidikan_bri_unknown'        => ['bk' => 'KEPENDIDIKAN_BRI', 'status' => 'pns', 'serdik' => 'unknown'],
            'pppk_kependidikan_bri_serdik'        => ['bk' => 'KEPENDIDIKAN_BRI', 'status' => 'pppk', 'serdik' => 'sertifikasi'],
            'pppk_kependidikan_bri_nonserdik'     => ['bk' => 'KEPENDIDIKAN_BRI', 'status' => 'pppk', 'serdik' => 'non-sertifikasi'],
            'pppk_kependidikan_bri_nonguru'       => ['bk' => 'KEPENDIDIKAN_BRI', 'status' => 'pppk', 'serdik' => 'non-guru'],
            'pppk_kependidikan_bri_unknown'       => ['bk' => 'KEPENDIDIKAN_BRI', 'status' => 'pppk', 'serdik' => 'unknown'],
            'pns_kependidikan_bsi_serdik'         => ['bk' => 'KEPENDIDIKAN_BSI', 'status' => 'pns', 'serdik' => 'sertifikasi'],
            'pns_kependidikan_bsi_nonserdik'      => ['bk' => 'KEPENDIDIKAN_BSI', 'status' => 'pns', 'serdik' => 'non-sertifikasi'],
            'pns_kependidikan_bsi_nonguru'        => ['bk' => 'KEPENDIDIKAN_BSI', 'status' => 'pns', 'serdik' => 'non-guru'],
            'pns_kependidikan_bsi_unknown'        => ['bk' => 'KEPENDIDIKAN_BSI', 'status' => 'pns', 'serdik' => 'unknown'],
            'cpns_kependidikan_bsi_nonserdik'     => ['bk' => 'KEPENDIDIKAN_BSI', 'status' => 'cpns', 'serdik' => 'non-sertifikasi'],
            'pppk_kependidikan_bsi_serdik_bsi'      => ['bk' => 'KEPENDIDIKAN_BSI', 'status' => 'pppk', 'serdik' => 'sertifikasi'],
            'pppk_kependidikan_bsi_nonserdik_bsi'   => ['bk' => 'KEPENDIDIKAN_BSI', 'status' => 'pppk', 'serdik' => 'non-sertifikasi'],
        ];

        if (!isset($labels[$groupKey])) {
            return null;
        }

        $def = $labels[$groupKey];
        return [
            'bank_kategori' => $def['bk'],
            'status' => $def['status'],
            'serdik' => $def['serdik'] ?? null,
            'label' => $this->buildGroupLabel($def['bk'], $def['status'], $def['serdik'] ?? 'unknown'),
        ];
    }

    /**
     * Generate file Excel presensi (rekap absensi dengan value 1)
     */
    protected function generatePresensiExcel($users, $presensiData, string $title, int $month, int $year): string
    {
        $daysInMonth = date('t', mktime(0, 0, 0, $month, 1, $year));

        $getColumnName = function ($index) {
            $column = '';
            while ($index > 0) {
                $index--;
                $column = chr(65 + ($index % 26)) . $column;
                $index = (int) ($index / 26);
            }
            return $column;
        };

        // Pre-compute weekend columns (sekali saja, bukan di loop)
        $weekendCols = [];
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = Carbon::create($year, $month, $day);
            if ($date->dayOfWeek === Carbon::SUNDAY || $date->dayOfWeek === Carbon::SATURDAY) {
                $weekendCols[$day] = true;
            }
        }

        $dayNames = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
        $headerBg = '1E3A5F';
        $headerFg = 'FFFFFF';
        $dayRowBg = 'E0F2FE';
        $altRowBg = 'F0F9FF';
        $totalBg = '0E7490';
        $borderColor = 'CBD5E1';

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->freezePane('A6');

        // Kolom: A=NIP, B=Nama, C=1, D=2, ..., AF=31, AG=Total
        $dateEndCol = $getColumnName($daysInMonth + 1); // AF (tanggal 31)
        $totalCol = $getColumnName($daysInMonth + 2); // AG (kolom Total)
        $lastCol = $totalCol; // AG adalah kolom terakhir

        // Row 1: Title
        $sheet->mergeCells("A1:{$lastCol}1");
        $sheet->setCellValue('A1', 'REKAP ABSENSI - ' . $title);
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14)->getColor()->setRGB($headerBg);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center')->setVertical('center');
        $sheet->getStyle("A1:{$lastCol}1")->getFill()->setFillType('solid')->getStartColor()->setRGB($dayRowBg);
        $sheet->getRowDimension(1)->setRowHeight(30);

        // Row 2: Subtitle
        $sheet->mergeCells("A2:{$lastCol}2");
        $sheet->setCellValue('A2', 'Bulan: ' . $this->getMonthName($month) . ' ' . $year);
        $sheet->getStyle('A2')->getFont()->setItalic(true)->setSize(11)->getColor()->setRGB('64748B');
        $sheet->getStyle('A2')->getAlignment()->setHorizontal('center')->setVertical('center');

        // Row 4: Day names
        $dayRowRange = "A4:{$lastCol}4";
        $sheet->getStyle($dayRowRange)->getFill()->setFillType('solid')->getStartColor()->setRGB($dayRowBg);
        $sheet->getStyle($dayRowRange)->getFont()->setBold(true)->setSize(8)->getColor()->setRGB('475569');
        $sheet->getStyle($dayRowRange)->getAlignment()->setHorizontal('center');
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $col = $getColumnName($day + 2);
            $sheet->setCellValue("{$col}4", $dayNames[Carbon::create($year, $month, $day)->dayOfWeek]);
        }
        // Weekend day-name highlight (satu batch)
        foreach ($weekendCols as $day => $_) {
            $col = $getColumnName($day + 2);
            $sheet->getStyle("{$col}4")->getFill()->setFillType('solid')->getStartColor()->setRGB('FEF3C7');
            $sheet->getStyle("{$col}4")->getFont()->getColor()->setRGB('D97706');
        }

        // Row 5: Headers
        $headerRow = 5;
        $headerRange = "A{$headerRow}:{$lastCol}{$headerRow}";
        $sheet->setCellValue("A{$headerRow}", 'NIP');
        $sheet->setCellValue("B{$headerRow}", 'Nama');
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $sheet->setCellValue("{$getColumnName($day + 2)}{$headerRow}", $day);
        }
        // Total column is set by the loop above (day 31 = col AG)
        // No need to set it again
        $sheet->getStyle($headerRange)->getFont()->setBold(true)->setSize(9)->getColor()->setRGB($headerFg);
        $sheet->getStyle($headerRange)->getFill()->setFillType('solid')->getStartColor()->setRGB($headerBg);
        $sheet->getStyle($headerRange)->getAlignment()->setHorizontal('center')->setVertical('center');
        $sheet->getStyle($headerRange)->getBorders()->getAllBorders()
            ->setBorderStyle('thin')->getColor()->setRGB($borderColor);
        $sheet->getRowDimension($headerRow)->setRowHeight(22);

        // Column widths (satu kali)
        $sheet->getColumnDimension('A')->setWidth(18);
        $sheet->getColumnDimension('B')->setWidth(25);
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $sheet->getColumnDimension($getColumnName($day + 2))->setWidth(5);
        }
        $sheet->getColumnDimension($totalCol)->setWidth(8);

        // Data rows - batch styling per range, bukan per cell
        $dataStartRow = 6;
        $rowNum = $dataStartRow;

        foreach ($users as $user) {
            $sheet->setCellValue("A{$rowNum}", $user->nomor_induk);
            $sheet->setCellValue("B{$rowNum}", $user->name);

            $userPresensi = $presensiData->get($user->nomor_induk);
            $total = 0;

            if ($userPresensi) {
                for ($day = 1; $day <= $daysInMonth; $day++) {
                    $presensi = $userPresensi[$day] ?? null;
                    $hasPresensi = $presensi &&
                        (!empty($presensi->m_absen) || !empty($presensi->p_absen)) &&
                        ($presensi->status === null);

                    if ($hasPresensi) {
                        // Kolom C (day=1), D (day=2), ..., AF (day=31)
                        $col = $getColumnName($day + 2);
                        $sheet->setCellValue("{$col}{$rowNum}", 1);
                        $total++;
                    }
                }
            }

            // Kolom AG (totalCol) untuk data total
            $sheet->setCellValue("{$totalCol}{$rowNum}", $total);

            // Apply row styling in batch (1 call per row, bukan per cell)
            $rowRange = "A{$rowNum}:{$totalCol}{$rowNum}";
            $rowBg = (($rowNum - $dataStartRow) % 2 === 0) ? 'FFFFFF' : $altRowBg;
            $sheet->getStyle($rowRange)->getFill()->setFillType('solid')->getStartColor()->setRGB($rowBg);
            $sheet->getStyle($rowRange)->getBorders()->getAllBorders()
                ->setBorderStyle('thin')->getColor()->setRGB($borderColor);
            $sheet->getStyle($rowRange)->getFont()->setSize(9);

            $rowNum++;
        }

        // Batch apply: green bold untuk semua cell yang bernilai 1
        $lastDataRow = $rowNum - 1;
        if ($lastDataRow >= $dataStartRow) {
            for ($day = 1; $day <= $daysInMonth; $day++) {
                $col = $getColumnName($day + 2);
                $sheet->getStyle("{$col}{$dataStartRow}:{$col}{$lastDataRow}")
                    ->getFont()->getColor()->setRGB('16A34A');
            }
        }

        // Batch apply: total column style
        if ($lastDataRow >= $dataStartRow) {
            $totalRange = "{$totalCol}{$dataStartRow}:{$totalCol}{$lastDataRow}";
            $sheet->getStyle($totalRange)->getFont()->setBold(true)->setSize(9)->getColor()->setRGB($headerFg);
            $sheet->getStyle($totalRange)->getAlignment()->setHorizontal('center');
            $sheet->getStyle($totalRange)->getFill()->setFillType('solid')->getStartColor()->setRGB($totalBg);
        }

        // Batch apply: weekend column backgrounds
        if ($lastDataRow >= $dataStartRow && !empty($weekendCols)) {
            foreach ($weekendCols as $day => $_) {
                $col = $getColumnName($day + 2);
                $sheet->getStyle("{$col}{$dataStartRow}:{$col}{$lastDataRow}")
                    ->getFill()->setFillType('solid')->getStartColor()->setRGB('FFFBEB');
            }
        }

        // Stream langsung ke output (tanpa temp file)
        ob_start();
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        $excelContent = ob_get_clean();

        return base64_encode($excelContent);
    }

    /**
     * Generate file Excel detail presensi (jam masuk/pulang)
     */
    protected function generateDetailPresensiExcel($users, $presensiData, string $title, int $month, int $year): string
    {
        $daysInMonth = date('t', mktime(0, 0, 0, $month, 1, $year));

        $getColumnName = function ($index) {
            $column = '';
            while ($index > 0) {
                $index--;
                $column = chr(65 + ($index % 26)) . $column;
                $index = (int) ($index / 26);
            }
            return $column;
        };

        $formatJam = function ($jam) {
            if (empty($jam)) {
                return '-';
            }
            if (preg_match('/^(\d{2}:\d{2}):\d{2}$/', $jam, $matches)) {
                return $matches[1];
            }
            if (preg_match('/^\d{2}:\d{2}$/', $jam)) {
                return $jam;
            }
            return $jam;
        };

        // Pre-compute weekend columns
        $weekendCols = [];
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = Carbon::create($year, $month, $day);
            if ($date->dayOfWeek === Carbon::SUNDAY || $date->dayOfWeek === Carbon::SATURDAY) {
                $weekendCols[$day] = true;
            }
        }

        $dayNames = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
        $headerBg = '1E3A5F';
        $headerFg = 'FFFFFF';
        $dayRowBg = 'E0F2FE';
        $altRowBg = 'F0F9FF';
        $totalBg = '0E7490';
        $borderColor = 'CBD5E1';

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->freezePane('A6');

        // Kolom: A=NIP, B=Nama, C=1, D=2, ..., AF=31, AG=Total
        $dateEndCol = $getColumnName($daysInMonth + 1); // AF (tanggal 31)
        $totalCol = $getColumnName($daysInMonth + 2); // AG (kolom Total)
        $lastCol = $totalCol; // AG adalah kolom terakhir

        // Row 1: Title
        $sheet->mergeCells("A1:{$lastCol}1");
        $sheet->setCellValue('A1', 'DETAIL JAM PRESENSI - ' . $title);
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14)->getColor()->setRGB($headerBg);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center')->setVertical('center');
        $sheet->getStyle("A1:{$lastCol}1")->getFill()->setFillType('solid')->getStartColor()->setRGB($dayRowBg);
        $sheet->getRowDimension(1)->setRowHeight(30);

        // Row 2: Subtitle
        $sheet->mergeCells("A2:{$lastCol}2");
        $sheet->setCellValue('A2', 'Bulan: ' . $this->getMonthName($month) . ' ' . $year);
        $sheet->getStyle('A2')->getFont()->setItalic(true)->setSize(11)->getColor()->setRGB('64748B');
        $sheet->getStyle('A2')->getAlignment()->setHorizontal('center')->setVertical('center');

        // Row 4: Day names
        $dayRowRange = "A4:{$lastCol}4";
        $sheet->getStyle($dayRowRange)->getFill()->setFillType('solid')->getStartColor()->setRGB($dayRowBg);
        $sheet->getStyle($dayRowRange)->getFont()->setBold(true)->setSize(8)->getColor()->setRGB('475569');
        $sheet->getStyle($dayRowRange)->getAlignment()->setHorizontal('center');
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $col = $getColumnName($day + 2);
            $sheet->setCellValue("{$col}4", $dayNames[Carbon::create($year, $month, $day)->dayOfWeek]);
        }
        foreach ($weekendCols as $day => $_) {
            $col = $getColumnName($day + 2);
            $sheet->getStyle("{$col}4")->getFill()->setFillType('solid')->getStartColor()->setRGB('FEF3C7');
            $sheet->getStyle("{$col}4")->getFont()->getColor()->setRGB('D97706');
        }

        // Row 5: Headers
        $headerRow = 5;
        $headerRange = "A{$headerRow}:{$lastCol}{$headerRow}";
        $sheet->setCellValue("A{$headerRow}", 'NIP');
        $sheet->setCellValue("B{$headerRow}", 'Nama');
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $sheet->setCellValue("{$getColumnName($day + 2)}{$headerRow}", $day);
        }
        // Total column is set by the loop above (day 31 = col AG)
        // No need to set it again
        $sheet->getStyle($headerRange)->getFont()->setBold(true)->setSize(9)->getColor()->setRGB($headerFg);
        $sheet->getStyle($headerRange)->getFill()->setFillType('solid')->getStartColor()->setRGB($headerBg);
        $sheet->getStyle($headerRange)->getAlignment()->setHorizontal('center')->setVertical('center');
        $sheet->getStyle($headerRange)->getBorders()->getAllBorders()
            ->setBorderStyle('thin')->getColor()->setRGB($borderColor);
        $sheet->getRowDimension($headerRow)->setRowHeight(22);

        // Column widths
        $sheet->getColumnDimension('A')->setWidth(18);
        $sheet->getColumnDimension('B')->setWidth(25);
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $sheet->getColumnDimension($getColumnName($day + 2))->setWidth(12);
        }
        $sheet->getColumnDimension($totalCol)->setWidth(8);

        // Data rows
        $dataStartRow = 6;
        $rowNum = $dataStartRow;

        foreach ($users as $user) {
            $sheet->setCellValue("A{$rowNum}", $user->nomor_induk);
            $sheet->setCellValue("B{$rowNum}", $user->name);

            $userPresensi = $presensiData->get($user->nomor_induk);
            $total = 0;

            if ($userPresensi) {
                for ($day = 1; $day <= $daysInMonth; $day++) {
                    $presensi = $userPresensi[$day] ?? null;
                    $hasPresensi = $presensi &&
                        (!empty($presensi->m_absen) || !empty($presensi->p_absen)) &&
                        ($presensi->status === null);

                    if ($hasPresensi) {
                        $jamMasuk = $formatJam($presensi->m_absen);
                        $jamPulang = $formatJam($presensi->p_absen);
                        $sheet->setCellValue("{$getColumnName($day + 2)}{$rowNum}", "{$jamMasuk} / {$jamPulang}");
                        $total++;
                    } elseif ($presensi && !empty($presensi->status)) {
                        // Tidak ada jam masuk/pulang, tapi ada status (CUTI, SAKIT, DLL)
                        $sheet->setCellValue("{$getColumnName($day + 2)}{$rowNum}", strtoupper($presensi->status));
                    }
                }
            }

            $sheet->setCellValue("{$totalCol}{$rowNum}", $total);

            // Row styling (1 call per row)
            $rowRange = "A{$rowNum}:{$totalCol}{$rowNum}";
            $rowBg = (($rowNum - $dataStartRow) % 2 === 0) ? 'FFFFFF' : $altRowBg;
            $sheet->getStyle($rowRange)->getFill()->setFillType('solid')->getStartColor()->setRGB($rowBg);
            $sheet->getStyle($rowRange)->getBorders()->getAllBorders()
                ->setBorderStyle('thin')->getColor()->setRGB($borderColor);
            $sheet->getStyle($rowRange)->getFont()->setSize(9);

            $rowNum++;
        }

        $lastDataRow = $rowNum - 1;

        // Batch: green font untuk kolom presensi
        if ($lastDataRow >= $dataStartRow) {
            for ($day = 1; $day <= $daysInMonth; $day++) {
                $col = $getColumnName($day + 2);
                $sheet->getStyle("{$col}{$dataStartRow}:{$col}{$lastDataRow}")
                    ->getFont()->getColor()->setRGB('16A34A');
            }
        }

        // Batch: total column
        if ($lastDataRow >= $dataStartRow) {
            $totalRange = "{$totalCol}{$dataStartRow}:{$totalCol}{$lastDataRow}";
            $sheet->getStyle($totalRange)->getFont()->setBold(true)->setSize(9)->getColor()->setRGB($headerFg);
            $sheet->getStyle($totalRange)->getAlignment()->setHorizontal('center');
            $sheet->getStyle($totalRange)->getFill()->setFillType('solid')->getStartColor()->setRGB($totalBg);
        }

        // Batch: weekend column backgrounds
        if ($lastDataRow >= $dataStartRow && !empty($weekendCols)) {
            foreach ($weekendCols as $day => $_) {
                $col = $getColumnName($day + 2);
                $sheet->getStyle("{$col}{$dataStartRow}:{$col}{$lastDataRow}")
                    ->getFill()->setFillType('solid')->getStartColor()->setRGB('FFFBEB');
            }
        }

        // Stream langsung ke output
        ob_start();
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        $excelContent = ob_get_clean();

        return base64_encode($excelContent);
    }

    protected function getMonthName(int $month): string
    {
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];
        return $months[$month] ?? 'Unknown';
    }
}
