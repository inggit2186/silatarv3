<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function home()
    {
        return view('welcome');
    }

    public function pelayanan()
    {
        $kantorUnits = $this->departmentSelection('kantor', 1)->values();

        return view('pelayanan', [
            'kantorUnits' => $kantorUnits,
            'selectedKantorUnitId' => null,
            'generalServices' => $this->generalServices(),
            'specialServicesByUnit' => $this->specialServicesByUnit($kantorUnits->pluck('id')->all()),
        ]);
    }

    public function requestService(int $serviceId)
    {
        $service = $this->serviceDetail($serviceId);
        $requester = auth()->user();

        return view('service-request', array_merge($this->requestFormViewData($service, $requester), [
            'service' => $service,
            'requester' => [
                'name' => $requester?->name,
                'identity' => $requester?->nomor_induk ?? '',
            ],
        ]));
    }

    public function editRequest(int $requestId)
    {
        $user = auth()->user();

        abort_unless($user, 403);

        $requestRow = DB::table('users_request')
            ->where('id', $requestId)
            ->where('user_id', $user->id)
            ->first();

        abort_unless($requestRow, 404);

        $service = $this->serviceDetail((int) $requestRow->layanan_id);
        $existingAnswers = DB::table('users_request_answers')
            ->where('request_id', $requestRow->id)
            ->pluck('value', 'syarat_id')
            ->all();

        $existingFiles = DB::table('users_berkas')
            ->where('no_req', $requestRow->no_req)
            ->get()
            ->keyBy('syarat_id')
            ->map(function ($file) use ($requestRow) {
                return [
                    'filename' => $file->filename,
                    'filetype' => $file->filetype,
                    'size' => $file->size,
                    'url' => route('pengajuan-saya.preview-file', [
                        'requestId' => $requestRow->id,
                        'syaratId' => $file->syarat_id,
                    ]),
                ];
            })
            ->all();
        $requestDescription = preg_replace('/^Nomor Identitas:.*?\n\n/s', '', (string) $requestRow->deskripsi);

        return view('service-request', array_merge($this->requestFormViewData($service, $user, true, $requestRow, $existingAnswers, $existingFiles), [
            'service' => $service,
            'requester' => [
                'name' => $user->name,
                'identity' => $user->nomor_induk ?? '',
            ],
            'requestDescription' => $requestDescription,
            'backUrl' => route('pengajuan-saya'),
        ]));
    }

    public function previewRequestFile(int $requestId, int $syaratId)
    {
        $user = auth()->user();

        abort_unless($user, 403);

        $requestRow = DB::table('users_request')
            ->where('id', $requestId)
            ->where('user_id', $user->id)
            ->first();

        abort_unless($requestRow, 404);

        $file = DB::table('users_berkas')
            ->where('no_req', $requestRow->no_req)
            ->where('syarat_id', $syaratId)
            ->first();

        abort_unless($file, 404);

        $path = "service-requests/{$requestRow->no_req}/{$file->filename}";

        abort_unless(Storage::disk('public')->exists($path), 404);

        return Storage::disk('public')->response($path);
    }

    public function myRequests()
    {
        $user = auth()->user();

        abort_unless($user, 403);

        $baseQuery = DB::table('users_request')->where('user_id', $user->id);

        $summary = [
            'total' => (clone $baseQuery)->count(),
            'draft' => (clone $baseQuery)->where('status', 'DRAFT')->count(),
            'pending' => (clone $baseQuery)->where('status', 'UNCHECK')->count(),
            'processed' => (clone $baseQuery)->whereIn('status', ['PENDING', 'DITERIMA', 'DIPROSES'])->count(),
            'done' => (clone $baseQuery)->whereIn('status', ['SUKSES', 'DITOLAK', 'BATAL'])->count(),
        ];

        $requests = DB::table('users_request as ur')
            ->where('ur.user_id', $user->id)
            ->leftJoin('ktd_layanan as layanan', 'layanan.id', '=', 'ur.layanan_id')
            ->select([
                'ur.id',
                'ur.no_req',
                'ur.pemohon',
                'ur.layanan_id',
                'ur.judul',
                'ur.deskripsi',
                'ur.status',
                'ur.kategori',
                'ur.created_at',
                'ur.updated_at',
                DB::raw('COALESCE(layanan.nama, ur.judul) as layanan_name'),
                DB::raw('COALESCE(layanan.deskripsi, ur.deskripsi) as layanan_description'),
            ])
            ->selectSub(function ($query) {
                $query->from('users_berkas as ub')
                    ->selectRaw('COUNT(*)')
                    ->whereColumn('ub.no_req', 'ur.no_req');
            }, 'file_count')
            ->orderByDesc('ur.created_at')
            ->orderByDesc('ur.id')
            ->paginate(12)
            ->withQueryString();

        return view('pengajuan-saya', [
            'summary' => $summary,
            'requests' => $requests,
        ]);
    }

    public function laporanKinerja(Request $request)
    {
        $user = $request->user();

        abort_unless($user, 403);

        $activeTab = $request->string('tab')->toString();
        $activeTab = in_array($activeTab, ['harian', 'bulanan', 'humas'], true) ? $activeTab : 'harian';

        $selectedMonth = $request->string('month')->toString();
        if (! preg_match('/^\d{4}-\d{2}$/', $selectedMonth)) {
            $selectedMonth = now()->format('Y-m');
        }

        $selectedMonthStart = Carbon::createFromFormat('Y-m', $selectedMonth)->startOfMonth();
        $selectedMonthEnd = $selectedMonthStart->copy()->endOfMonth();

        $selectedYear = $request->string('year')->toString();
        if (! preg_match('/^\d{4}$/', $selectedYear)) {
            $selectedYear = now()->format('Y');
        }

        $selectedYearStart = Carbon::createFromFormat('Y', $selectedYear)->startOfYear();
        $selectedYearEnd = $selectedYearStart->copy()->endOfYear();

        $search = trim((string) $request->query('search', ''));
        $printMode = $request->boolean('print');
        $editingDate = $request->string('edit_date')->toString();
        if (! preg_match('/^\d{4}-\d{2}-\d{2}$/', $editingDate)) {
            $editingDate = '';
        }

        $dailyQuery = DB::table('satker_kegiatan')
            ->where('user_id', $user->id)
            ->whereBetween('tanggal', [$selectedMonthStart->toDateString(), $selectedMonthEnd->toDateString()]);

        if ($search !== '') {
            $dailyQuery->where('kegiatan', 'like', '%' . $search . '%');
        }

        $dailyEntries = $dailyQuery
            ->orderBy('tanggal')
            ->orderBy('created_at')
            ->get();

        $dailyGroups = $dailyEntries
            ->groupBy(fn ($row) => Carbon::parse($row->tanggal)->toDateString())
            ->map(function ($items, $date) {
                $dateCarbon = Carbon::parse($date);

                return [
                    'date' => $dateCarbon->toDateString(),
                    'label' => $this->indonesianDateLabel($dateCarbon),
                    'items' => $items->map(function ($item) {
                        $volume = (int) ($item->volume ?? 0);
                        $unit = trim((string) ($item->satuan ?? ''));

                        return [
                            'id' => $item->id,
                            'kegiatan' => trim((string) $item->kegiatan),
                            'volume' => $volume,
                            'satuan' => $unit,
                            'meta' => $volume > 0 ? trim($volume . ' ' . $unit) : $unit,
                            'tanggal' => $item->tanggal,
                        ];
                    })->values(),
                    'entries' => $items->count(),
                    'volume' => $items->sum(fn ($item) => (int) ($item->volume ?? 0)),
                ];
            })
            ->values();

        $dailySummary = [
            'entries' => $dailyEntries->count(),
            'days' => $dailyGroups->count(),
            'volume' => $dailyEntries->sum(fn ($item) => (int) ($item->volume ?? 0)),
            'latest_update' => optional($dailyEntries->sortByDesc('updated_at')->first())->updated_at,
        ];

        $activityUnits = DB::table('satker_kegiatan')
            ->where('user_id', $user->id)
            ->whereNotNull('satuan')
            ->whereRaw("TRIM(satuan) <> ''")
            ->distinct()
            ->orderBy('satuan')
            ->pluck('satuan')
            ->filter()
            ->values();

        if ($activityUnits->isEmpty()) {
            $activityUnits = collect(['Kegiatan', 'Dokumen', 'Jam']);
        }

        $monthlyActivityStats = DB::table('satker_kegiatan')
            ->where('user_id', $user->id)
            ->whereBetween('tanggal', [$selectedYearStart->toDateString(), $selectedYearEnd->toDateString()])
            ->selectRaw("DATE_FORMAT(tanggal, '%Y-%m-01') as month_key")
            ->selectRaw('COUNT(*) as entries')
            ->selectRaw('COUNT(DISTINCT DATE(tanggal)) as days')
            ->selectRaw('COALESCE(SUM(volume), 0) as total_volume')
            ->groupBy('month_key')
            ->get()
            ->keyBy('month_key');

        $monthlyRecaps = DB::table('satker_ckh as ckh')
            ->where('ckh.user_id', $user->id)
            ->whereBetween('ckh.bulan', [$selectedYearStart->toDateString(), $selectedYearEnd->toDateString()])
            ->orderByDesc('ckh.bulan')
            ->orderByDesc('ckh.updated_at')
            ->get()
            ->map(function ($item) use ($monthlyActivityStats) {
                $monthCarbon = Carbon::parse($item->bulan)->startOfMonth();
                $stats = $monthlyActivityStats->get($monthCarbon->toDateString());
                $status = strtoupper(trim((string) ($item->status ?? 'KOSONG')));
                $pdfPath = filled($item->filename)
                    ? "satker_ckh/{$item->user_id}/{$item->filename}"
                    : '';

                return [
                    'id' => $item->id,
                    'month_key' => $monthCarbon->toDateString(),
                    'month_label' => $monthCarbon->translatedFormat('F'),
                    'days' => (int) ($stats->days ?? 0),
                    'entries' => (int) ($stats->entries ?? 0),
                    'total_volume' => (int) ($stats->total_volume ?? 0),
                    'status' => $status,
                    'status_label' => $this->reportStatusLabel($status),
                    'status_tone' => $this->reportStatusTone($status),
                    'status_class' => $this->reportStatusClass($status),
                    'sending_label' => filled($item->sending)
                        ? Carbon::parse($item->sending)->translatedFormat('d F Y H:i')
                        : '-',
                    'pdf_exists' => $pdfPath !== '' && Storage::disk('public')->exists($pdfPath),
                    'pdf_url' => $pdfPath !== '' && Storage::disk('public')->exists($pdfPath)
                        ? Storage::disk('public')->url($pdfPath)
                        : null,
                    'latest_update' => $item->updated_at,
                ];
            })
            ->values();

        $humasReports = DB::table('laporan_humas as lh')
            ->where('lh.user_id', $user->id)
            ->orderByDesc('lh.bulan')
            ->get()
            ->map(function ($item) {
                $data = json_decode((string) ($item->data ?? '{}'), true) ?: [];
                $channels = collect(['facebook', 'instagram', 'tiktok', 'website', 'youtube'])->map(function (string $channel) use ($data) {
                    $channelData = $data[$channel] ?? [];

                    return [
                        'name' => ucfirst($channel),
                        'has_data' => filled(data_get($channelData, 'first.date')) || filled(data_get($channelData, 'last.date')),
                        'first_content' => data_get($channelData, 'first.content'),
                        'last_content' => data_get($channelData, 'last.content'),
                    ];
                })->values();

                return [
                    'id' => $item->id,
                    'month_label' => Carbon::parse($item->bulan)->format('m/Y'),
                    'status' => $item->status ?: '-',
                    'comment' => $item->komen ?: $item->user_komen ?: '-',
                    'updated_at' => $item->updated_at,
                    'channels' => $channels,
                    'active_channels' => $channels->where('has_data', true)->count(),
                ];
            });

        $tabLabels = [
            'harian' => [
                'label' => 'Kinerja Harian',
                'icon' => 'document',
                'tone' => 'rose',
            ],
            'bulanan' => [
                'label' => 'Laporan Kinerja Bulanan',
                'icon' => 'calendar',
                'tone' => 'slate',
            ],
            'humas' => [
                'label' => 'Laporan Humas',
                'icon' => 'megaphone',
                'tone' => 'amber',
            ],
        ];

        $editingGroup = null;
        if ($editingDate !== '') {
            $editingRows = DB::table('satker_kegiatan')
                ->where('user_id', $user->id)
                ->whereDate('tanggal', $editingDate)
                ->orderBy('created_at')
                ->orderBy('id')
                ->get();

            if ($editingRows->isNotEmpty()) {
                $dateCarbon = Carbon::parse($editingDate);

                $editingGroup = [
                    'date' => $dateCarbon->toDateString(),
                    'label' => $this->indonesianDateLabel($dateCarbon),
                    'items' => $editingRows->map(function ($item) {
                        return [
                            'id' => $item->id,
                            'kegiatan' => trim((string) $item->kegiatan),
                            'volume' => (int) ($item->volume ?? 0),
                            'satuan' => trim((string) ($item->satuan ?? '')),
                        ];
                    })->values(),
                ];
            }
        }

        return view('laporan-kinerja', [
            'activeTab' => $activeTab,
            'selectedMonth' => $selectedMonth,
            'selectedMonthLabel' => $this->indonesianMonthLabel($selectedMonthStart),
            'selectedYear' => $selectedYear,
            'selectedYearLabel' => $selectedYear,
            'search' => $search,
            'printMode' => $printMode,
            'editingGroup' => $editingGroup,
            'tabLabels' => $tabLabels,
            'dailyGroups' => $dailyGroups,
            'dailySummary' => $dailySummary,
            'activityUnits' => $activityUnits,
            'monthlyRecaps' => $monthlyRecaps,
            'monthlySummary' => [
                'months' => $monthlyRecaps->count(),
                'days' => $monthlyRecaps->sum('days'),
                'entries' => $monthlyRecaps->sum('entries'),
                'volume' => $monthlyRecaps->sum('total_volume'),
                'latest_update' => optional($monthlyRecaps->sortByDesc('latest_update')->first())->latest_update,
            ],
            'humasReports' => $humasReports,
        ]);
    }

    public function rekapLaporanKinerja(Request $request)
    {
        $user = $request->user();

        abort_unless($user, 403);

        $selectedMonth = $request->string('month')->toString();
        if (! preg_match('/^\d{4}-\d{2}$/', $selectedMonth)) {
            $selectedMonth = now()->format('Y-m');
        }

        $selectedMonthStart = Carbon::createFromFormat('Y-m', $selectedMonth)->startOfMonth();
        $selectedMonthEnd = $selectedMonthStart->copy()->endOfMonth();
        $periodLabel = $this->indonesianMonthLabel($selectedMonthStart);

        $unitName = DB::table('ktd_department')
            ->where('id', $user->dept_id)
            ->value('nama');

        $dailyEntries = DB::table('satker_kegiatan')
            ->where('user_id', $user->id)
            ->whereBetween('tanggal', [$selectedMonthStart->toDateString(), $selectedMonthEnd->toDateString()])
            ->orderBy('tanggal')
            ->orderBy('created_at')
            ->get();

        $dailyGroups = $dailyEntries
            ->groupBy(fn ($row) => Carbon::parse($row->tanggal)->toDateString())
            ->map(function ($items, $date) {
                $dateCarbon = Carbon::parse($date);

                return [
                    'date' => $dateCarbon->toDateString(),
                    'label' => $this->indonesianDateLabel($dateCarbon),
                    'items' => $items->map(function ($item) {
                        $volume = (int) ($item->volume ?? 0);
                        $unit = trim((string) ($item->satuan ?? ''));

                        return [
                            'kegiatan' => trim((string) $item->kegiatan),
                            'volume' => $volume,
                            'satuan' => $unit,
                            'meta' => $volume > 0 ? trim($volume . ' ' . $unit) : $unit,
                        ];
                    })->values(),
                ];
            })
            ->values()
            ->all();

        $pdfData = [
            'userName' => $user->name,
            'userNip' => $user->nomor_induk ?: '-',
            'unitName' => $unitName ?: '-',
            'positionName' => trim((string) ($user->pekerjaan ?: '-')) ?: '-',
            'periodLabel' => $periodLabel,
            'dailyGroups' => $dailyGroups,
            'headerImage' => $this->assetToDataUri(public_path('assets/img/template/header.png')),
            'generatedAt' => now()->translatedFormat('d F Y H:i'),
            'watermarkText' => 'Kankemenag Kab.Tanah Datar',
        ];

        $pdf = Pdf::loadView('pdf.laporan-kinerja-harian', $pdfData)
            ->setPaper('a4', 'portrait')
            ->setOption('isRemoteEnabled', true)
            ->setOption('isHtml5ParserEnabled', true);

        $filename = sprintf('%s.kinerja-%s.pdf', $user->id, $selectedMonthStart->format('m-Y'));
        $storagePath = "satker_ckh/{$user->id}/{$filename}";
        $pdfBinary = $pdf->output();

        Storage::disk('public')->put($storagePath, $pdfBinary);

        $reportData = [
            'item_id' => 1,
            'dept_id' => $user->dept_id,
            'user_id' => $user->id,
            'bulan' => $selectedMonthStart->toDateString(),
            'filename' => $filename,
            'status' => 'DIKIRIM',
            'alasan' => null,
            'petugas' => 777,
            'sending' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ];

        DB::table('satker_ckh')->updateOrInsert(
            [
                'item_id' => 1,
                'dept_id' => $user->dept_id,
                'user_id' => $user->id,
                'bulan' => $selectedMonthStart->toDateString(),
            ],
            $reportData
        );

        return response($pdfBinary, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
        ]);
    }

    public function downloadLaporanKinerjaPdf(Request $request, int $reportId)
    {
        $user = $request->user();

        abort_unless($user, 403);

        $report = DB::table('satker_ckh')
            ->where('id', $reportId)
            ->where('user_id', $user->id)
            ->first();

        abort_unless($report, 404);
        abort_unless(filled($report->filename), 404);

        $storagePath = "satker_ckh/{$report->user_id}/{$report->filename}";

        abort_unless(Storage::disk('public')->exists($storagePath), 404);

        return Storage::disk('public')->response($storagePath, $report->filename, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $report->filename . '"',
        ]);
    }

    public function storeLaporanKinerja(Request $request)
    {
        $user = $request->user();

        abort_unless($user, 403);

        $validator = Validator::make($request->all(), [
            'tanggal' => ['required', 'date'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.kegiatan' => ['nullable', 'string', 'max:1000'],
            'items.*.volume' => ['nullable', 'integer', 'min:0'],
            'items.*.satuan' => ['nullable', 'string', 'max:50'],
            'tab' => ['nullable', 'string'],
            'month' => ['nullable', 'string'],
            'search' => ['nullable', 'string'],
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('open_add_modal', true);
        }

        $data = $validator->validated();
        $rows = $this->sanitizeActivityRows($data['items'] ?? [], (string) ($request->input('default_unit', 'Kegiatan')))
            ->filter(fn ($item) => $item['kegiatan'] !== '')
            ->values();

        if ($rows->isEmpty()) {
            return back()
                ->withErrors(['items' => 'Tambahkan minimal satu kegiatan.'])
                ->withInput()
                ->with('open_add_modal', true);
        }

        foreach ($rows as $index => $row) {
            $rowValidator = Validator::make($row, [
                'kegiatan' => ['required', 'string', 'max:1000'],
                'volume' => ['nullable', 'integer', 'min:0'],
                'satuan' => ['required', 'string', 'max:50'],
            ]);

            if ($rowValidator->fails()) {
                return back()
                    ->withErrors($rowValidator)
                    ->withInput()
                    ->with('open_add_modal', true);
            }
        }

        $submittedAt = now();
        $insertRows = [];

        foreach ($rows as $row) {
            $insertRows[] = [
                'user_id' => $user->id,
                'tanggal' => $data['tanggal'],
                'kegiatan' => $row['kegiatan'],
                'volume' => $row['volume'],
                'satuan' => $row['satuan'],
                'staff_id' => $user->id,
                'created_at' => $submittedAt,
                'updated_at' => $submittedAt,
            ];
        }

        DB::table('satker_kegiatan')->insert($insertRows);

        return redirect()
            ->route('laporan-kinerja', [
                'tab' => 'harian',
                'month' => Carbon::parse($data['tanggal'])->format('Y-m'),
            ])
            ->with('success', 'Kegiatan harian berhasil ditambahkan.');
    }

    public function updateLaporanKinerjaByDate(Request $request)
    {
        $user = $request->user();

        abort_unless($user, 403);

        $validator = Validator::make($request->all(), [
            'tanggal' => ['required', 'date'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.kegiatan' => ['nullable', 'string', 'max:1000'],
            'items.*.volume' => ['nullable', 'integer', 'min:0'],
            'items.*.satuan' => ['nullable', 'string', 'max:50'],
            'tab' => ['nullable', 'string'],
            'month' => ['nullable', 'string'],
            'search' => ['nullable', 'string'],
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('open_edit_modal', true);
        }

        $data = $validator->validated();
        $rows = $this->sanitizeActivityRows($data['items'] ?? [], (string) $request->input('default_unit', 'Kegiatan'))
            ->filter(fn ($item) => $item['kegiatan'] !== '')
            ->values();

        if ($rows->isEmpty()) {
            return back()
                ->withErrors(['items' => 'Tambahkan minimal satu kegiatan.'])
                ->withInput()
                ->with('open_edit_modal', true);
        }

        foreach ($rows as $row) {
            $rowValidator = Validator::make($row, [
                'kegiatan' => ['required', 'string', 'max:1000'],
                'volume' => ['nullable', 'integer', 'min:0'],
                'satuan' => ['required', 'string', 'max:50'],
            ]);

            if ($rowValidator->fails()) {
                return back()
                    ->withErrors($rowValidator)
                    ->withInput()
                    ->with('open_edit_modal', true);
            }
        }

        DB::table('satker_kegiatan')
            ->where('user_id', $user->id)
            ->whereDate('tanggal', $data['tanggal'])
            ->delete();

        $submittedAt = now();
        $insertRows = [];

        foreach ($rows as $row) {
            $insertRows[] = [
                'user_id' => $user->id,
                'tanggal' => $data['tanggal'],
                'kegiatan' => $row['kegiatan'],
                'volume' => $row['volume'],
                'satuan' => $row['satuan'],
                'staff_id' => $user->id,
                'created_at' => $submittedAt,
                'updated_at' => $submittedAt,
            ];
        }

        DB::table('satker_kegiatan')->insert($insertRows);

        return redirect()
            ->route('laporan-kinerja', [
                'tab' => 'harian',
                'month' => Carbon::parse($data['tanggal'])->format('Y-m'),
            ])
            ->with('success', 'Data laporan kinerja berhasil diperbarui.');
    }

    public function updateLaporanKinerja(Request $request, int $activityId)
    {
        $user = $request->user();

        abort_unless($user, 403);

        $activity = DB::table('satker_kegiatan')
            ->where('id', $activityId)
            ->where('user_id', $user->id)
            ->first();

        abort_unless($activity, 404);

        $data = $request->validate([
            'tanggal' => ['required', 'date'],
            'kegiatan' => ['required', 'string', 'max:1000'],
            'volume' => ['nullable', 'integer', 'min:0'],
            'satuan' => ['nullable', 'string', 'max:50'],
            'tab' => ['nullable', 'string'],
            'month' => ['nullable', 'string'],
            'search' => ['nullable', 'string'],
        ]);

        $updatedAt = now();

        DB::table('satker_kegiatan')
            ->where('id', $activityId)
            ->where('user_id', $user->id)
            ->update([
                'tanggal' => $data['tanggal'],
                'kegiatan' => trim($data['kegiatan']),
                'volume' => (int) ($data['volume'] ?? 0),
                'satuan' => trim((string) ($data['satuan'] ?? '')),
                'updated_at' => $updatedAt,
            ]);

        $month = Carbon::parse($data['tanggal'])->format('Y-m');

        return redirect()
            ->route('laporan-kinerja', [
                'tab' => 'harian',
                'month' => $month,
                'search' => trim((string) ($data['search'] ?? '')),
            ])
            ->with('success', 'Data laporan kinerja berhasil diperbarui.');
    }

    public function deleteLaporanKinerja(Request $request, int $activityId)
    {
        $user = $request->user();

        abort_unless($user, 403);

        $activity = DB::table('satker_kegiatan')
            ->where('id', $activityId)
            ->where('user_id', $user->id)
            ->first();

        abort_unless($activity, 404);

        $data = $request->validate([
            'tab' => ['nullable', 'string'],
            'month' => ['nullable', 'string'],
            'search' => ['nullable', 'string'],
        ]);

        DB::table('satker_kegiatan')
            ->where('id', $activityId)
            ->where('user_id', $user->id)
            ->delete();

        $month = $data['month'] ?? Carbon::parse($activity->tanggal)->format('Y-m');

        return redirect()
            ->route('laporan-kinerja', [
                'tab' => 'harian',
                'month' => $month,
                'search' => trim((string) ($data['search'] ?? '')),
            ])
            ->with('success', 'Data laporan kinerja berhasil dihapus.');
    }

    public function deleteLaporanKinerjaByDate(Request $request)
    {
        $user = $request->user();

        abort_unless($user, 403);

        $data = $request->validate([
            'tanggal' => ['required', 'date'],
            'tab' => ['nullable', 'string'],
            'month' => ['nullable', 'string'],
            'search' => ['nullable', 'string'],
        ]);

        DB::table('satker_kegiatan')
            ->where('user_id', $user->id)
            ->whereDate('tanggal', $data['tanggal'])
            ->delete();

        $month = Carbon::parse($data['tanggal'])->format('Y-m');

        return redirect()
            ->route('laporan-kinerja', [
                'tab' => 'harian',
                'month' => $month,
                'search' => trim((string) ($data['search'] ?? '')),
            ])
            ->with('success', 'Semua kegiatan pada tanggal tersebut berhasil dihapus.');
    }

    private function sanitizeActivityRows(array $items, string $defaultUnit = 'Kegiatan')
    {
        return collect($items)
            ->map(function ($item) use ($defaultUnit) {
                return [
                    'kegiatan' => trim((string) ($item['kegiatan'] ?? '')),
                    'volume' => (int) ($item['volume'] ?? 0),
                    'satuan' => trim((string) ($item['satuan'] ?? $defaultUnit)),
                ];
            });
    }

    public function submitServiceRequest(Request $request, int $serviceId)
    {
        $service = $this->serviceDetail($serviceId);
        $requirements = $service['requirements'];
        $requester = $request->user();
        $isDraft = $request->input('submit_action') === 'draft';
        $editingRequestId = (int) $request->input('request_id', 0);
        $existingRequest = null;
        $existingFiles = [];

        abort_unless($requester, 403);

        if ($editingRequestId > 0) {
            $existingRequest = DB::table('users_request')
                ->where('id', $editingRequestId)
                ->where('user_id', $requester->id)
                ->first();

            abort_unless($existingRequest, 404);
            abort_unless((int) $existingRequest->layanan_id === $service['id'], 403);

            $existingFiles = DB::table('users_berkas')
                ->where('no_req', $existingRequest->no_req)
                ->get()
                ->keyBy('syarat_id');
        }

        $rules = [
            'deskripsi' => ['required', 'string', 'max:2000'],
        ];

        foreach ($requirements as $requirement) {
            $type = $requirement['type_normalized'];
            $fieldKey = $this->requirementFieldKey($type, (int) $requirement['id']);
            $hasExistingUpload = $existingRequest && $this->isUploadRequirement($type) && isset($existingFiles[$requirement['id']]);

            $rules[$fieldKey] = $this->requirementValidationRules($requirement, $isDraft, $hasExistingUpload);
        }

        $data = $request->validate($rules);

        $userId = $requester->id;
        $requestNumber = $existingRequest?->no_req ?? $this->generateRequestNumber($service['dept_id'], $service['id']);
        $submittedAt = now();
        $uploadRoot = "service-requests/{$requestNumber}";
        $deskripsi = "Nomor Identitas: {$requester->nomor_induk}\n\n{$data['deskripsi']}";
        DB::transaction(function () use ($service, $data, $requirements, $requestNumber, $userId, $submittedAt, $uploadRoot, $deskripsi, $request, $isDraft, $requester, $existingRequest, $existingFiles) {
            $requestRowId = $existingRequest?->id;

            if ($existingRequest) {
                DB::table('users_request')
                    ->where('id', $existingRequest->id)
                    ->update([
                        'pemohon' => $requester->name,
                        'no_surat' => null,
                        'tgl_surat' => null,
                        'dept_id' => $service['dept_id'],
                        'layanan_id' => $service['id'],
                        'judul' => $service['title'],
                        'deskripsi' => $deskripsi,
                        'file_offline' => 'NONE',
                        'status' => $isDraft ? 'DRAFT' : 'UNCHECK',
                        'staff_id' => 999,
                        'step' => null,
                        'petugas' => null,
                        'kategori' => 'Personal',
                        'komentar' => null,
                        'updated_at' => $submittedAt,
                    ]);
            } else {
                $requestRowId = DB::table('users_request')->insertGetId([
                    'no_req' => $requestNumber,
                    'pemohon' => $requester->name,
                    'no_surat' => null,
                    'tgl_surat' => null,
                    'user_id' => $userId,
                    'dept_id' => $service['dept_id'],
                    'layanan_id' => $service['id'],
                    'judul' => $service['title'],
                    'deskripsi' => $deskripsi,
                    'file_offline' => 'NONE',
                    'status' => $isDraft ? 'DRAFT' : 'UNCHECK',
                    'staff_id' => 999,
                    'step' => null,
                    'petugas' => null,
                    'kategori' => 'Personal',
                    'komentar' => null,
                    'created_at' => $submittedAt,
                    'updated_at' => $submittedAt,
                ]);
            }

            foreach ($requirements as $requirement) {
                $requirementId = (int) $requirement['id'];
                $type = $requirement['type_normalized'];
                $fieldKey = $this->requirementFieldKey($type, $requirementId);

                if ($this->isUploadRequirement($type)) {
                    $uploadedFile = $request->file($fieldKey);
                    $existingFile = $existingFiles[$requirementId] ?? null;
                    $filename = $existingFile->filename ?? 'NONE';
                    $filetype = $existingFile->filetype ?? null;
                    $size = $existingFile->size ?? null;

                    if ($uploadedFile) {
                        if ($existingFile) {
                            Storage::disk('public')->delete("service-requests/{$requestNumber}/{$existingFile->filename}");
                        }

                        $extension = $uploadedFile->getClientOriginalExtension() ?: $uploadedFile->extension();
                        $safeName = Str::slug($requirement['title'], '');
                        $filename = "{$requestNumber}.{$userId}.{$safeName}." . strtolower($extension);
                        $path = $uploadedFile->storeAs($uploadRoot, $filename, 'public');
                        $filetype = strtolower($extension);
                        $size = (string) $uploadedFile->getSize();
                    }

                    DB::table('users_berkas')->updateOrInsert([
                        'no_req' => $requestNumber,
                        'syarat_id' => $requirementId,
                    ], [
                        'kategori' => 'Personal',
                        'user_id' => $userId,
                        'no_req' => $requestNumber,
                        'layanan_id' => $service['id'],
                        'syarat_id' => $requirementId,
                        'filename' => $filename,
                        'filetype' => $filetype,
                        'size' => $size,
                        'status' => 0,
                        'created_at' => $submittedAt,
                        'updated_at' => $submittedAt,
                    ]);
                } else {
                    $rawValue = $data['values'][$requirementId] ?? null;
                    $value = is_string($rawValue) ? trim($rawValue) : $rawValue;
                    DB::table('users_request_answers')->updateOrInsert([
                        'request_id' => $requestRowId,
                        'syarat_id' => $requirementId,
                    ], [
                        'no_req' => $requestNumber,
                        'value' => $value === '' ? null : $value,
                        'created_at' => $submittedAt,
                        'updated_at' => $submittedAt,
                    ]);
                }
            }
        });

        $message = $isDraft
            ? "Draft {$service['title']} sudah disimpan."
            : "Pengajuan {$service['title']} sudah diterima.";

        return redirect()
            ->route('pengajuan-saya')
            ->with('success', $message);
    }

    public function satuanKerja()
    {
        $departmentGroups = $this->departmentGroups();
        $externalGroups = $this->externalGroups();

        return view('satuan-kerja', [
            'sections' => [
                [
                    'key' => 'kantor',
                    'label' => 'Kantor',
                    'description' => 'Kategori kantor dengan status aktif 1.',
                    'count' => $departmentGroups['kantor']->total(),
                    'cards' => $departmentGroups['kantor'],
                ],
                [
                    'key' => 'kua',
                    'label' => 'KUA',
                    'description' => 'Kategori KUA dengan status 2.',
                    'count' => $departmentGroups['kua']->total(),
                    'cards' => $departmentGroups['kua'],
                ],
                [
                    'key' => 'min',
                    'label' => 'MIN',
                    'description' => 'Kategori MIN dengan status 2.',
                    'count' => $departmentGroups['min']->total(),
                    'cards' => $departmentGroups['min'],
                ],
                [
                    'key' => 'mtsn',
                    'label' => 'MTsN',
                    'description' => 'Kategori MTsN dengan status 2.',
                    'count' => $departmentGroups['mtsn']->total(),
                    'cards' => $departmentGroups['mtsn'],
                ],
                [
                    'key' => 'man',
                    'label' => 'MAN',
                    'description' => 'Kategori MAN dengan status 2.',
                    'count' => $departmentGroups['man']->total(),
                    'cards' => $departmentGroups['man'],
                ],
                [
                    'key' => 'swasta-lainnya',
                    'label' => 'Swasta/Lainnya',
                    'description' => 'Data PP dari users dengan dept_id 999.',
                    'count' => $externalGroups['swasta-lainnya']->total(),
                    'cards' => $externalGroups['swasta-lainnya'],
                ],
                [
                    'key' => 'pemerintah-daerah',
                    'label' => 'Pemerintah Daerah',
                    'description' => 'Data PP dari users dengan dept_id 998.',
                    'count' => $externalGroups['pemerintah-daerah']->total(),
                    'cards' => $externalGroups['pemerintah-daerah'],
                ],
            ],
        ]);
    }

    public function satuanKerjaDetail(int $department)
    {
        $departmentRow = DB::table('ktd_department')->where('id', $department)->first();

        abort_unless($departmentRow, 404);

        $head = $this->departmentHead($departmentRow->id, $departmentRow->kategori, $departmentRow->nama);
        $pltHead = $head ? null : $this->departmentPltHead($departmentRow->id);
        $leader = $head ?? $pltHead;
        $leaderLabel = $this->departmentHeadLabel($departmentRow->kategori, $departmentRow->nama, (bool) $pltHead);

        return view('unit-kerja-detail', [
            'department' => [
                'id' => $departmentRow->id,
                'name' => $departmentRow->nama,
                'description' => $departmentRow->deskripsi,
                'code' => $departmentRow->kode,
                'category' => $departmentRow->kategori,
                'cover' => asset("assets/img/seksi/{$departmentRow->id}.jpg"),
            ],
            'leader' => $leader ? $this->personCard($leader, $leaderLabel, true) : null,
            'leaderLabel' => $leaderLabel,
            'people' => $this->departmentPeople($departmentRow->id, $leader?->id),
        ]);
    }

    private function departmentGroups(): array
    {
        return [
            'kantor' => $this->departmentCards('kantor', 1),
            'kua' => $this->departmentCards('kua', 2),
            'min' => $this->departmentCards('min', 2),
            'mtsn' => $this->departmentCards('mtsn', 2),
            'man' => $this->departmentCards('man', 2),
        ];
    }

    private function departmentSelection(string $kategori, int $status)
    {
        return DB::table('ktd_department')
            ->whereRaw('LOWER(kategori) = ?', [strtolower($kategori)])
            ->where('status', $status)
            ->orderBy('id')
            ->get()
            ->map(function ($item) use ($kategori) {
                $head = $this->departmentHead($item->id, $kategori, $item->nama);
                $pltHead = $head ? null : $this->departmentPltHead($item->id);
                $employeeCount = DB::table('users')
                    ->where('dept_id', $item->id)
                    ->count();

                return [
                    'id' => $item->id,
                    'title' => $item->nama,
                    'subtitle' => $item->deskripsi ?: $item->kode,
                    'meta_label' => 'Nama Unit',
                    'meta_value' => $item->nama,
                    'extra_label' => 'Pegawai',
                    'extra_value' => $employeeCount,
                    'head_label' => $this->departmentHeadLabel($kategori, $item->nama, (bool) $pltHead),
                    'head_value' => $head?->name ?? $pltHead?->name,
                    'cover' => Str::upper(Str::substr($item->nama, 0, 2)),
                    'cover_path' => asset("assets/img/seksi/{$item->id}.jpg"),
                    'href' => route('unit-kerja.detail', $item->id),
                    'type' => $kategori,
                ];
            });
    }

    private function generalServices(): array
    {
        return [
            [
                'key' => 'konsultasi',
                'title' => 'Konsultasi',
                'description' => 'Panduan awal untuk menjawab kebutuhan dan pertanyaan layanan.',
                'tag' => 'Layanan umum',
                'cover_path' => asset('assets/img/ikon/777.png'),
            ],
            [
                'key' => 'janji-temu',
                'title' => 'Janji Temu',
                'description' => 'Atur appointment agar layanan bisa diproses lebih terjadwal.',
                'tag' => 'Layanan umum',
                'cover_path' => asset('assets/img/ikon/508.png'),
            ],
            [
                'key' => 'pengaduan',
                'title' => 'Pengaduan',
                'description' => 'Sampaikan keluhan atau masukan untuk ditindaklanjuti.',
                'tag' => 'Layanan umum',
                'cover_path' => asset('assets/img/ikon/888.png'),
            ],
            [
                'key' => 'satu-data',
                'title' => 'Satu Data',
                'description' => 'Akses data ringkas yang dipakai sebagai referensi layanan.',
                'tag' => 'Layanan umum',
                'cover_path' => asset('assets/img/ikon/507.png'),
            ],
        ];
    }

    private function specialServicesByUnit(array $departmentIds): array
    {
        if ($departmentIds === []) {
            return [];
        }

        return DB::table('ktd_layanan')
            ->whereIn('dept_id', $departmentIds)
            ->orderBy('dept_id')
            ->orderByDesc('spesial')
            ->orderBy('id')
            ->get()
            ->groupBy('dept_id')
            ->map(function ($items) {
                return $items->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'dept_id' => $item->dept_id,
                        'title' => $item->nama,
                        'description' => $item->deskripsi ?: 'Layanan khusus pada unit kerja ini.',
                        'waktu' => $item->waktu ?: '-',
                        'biaya' => $this->serviceCostLabel($item->biaya),
                        'output' => $item->output ?: '-',
                        'status_label' => $this->serviceStatusLabel($item->status),
                        'is_spesial' => (int) $item->spesial === 1,
                        'requirements' => $this->serviceRequirements((int) $item->id),
                        'cover_path' => $this->serviceIconPath($item->id),
                    ];
                })->values();
            })
            ->all();
    }

    private function serviceRequirements(int $serviceId): array
    {
        return DB::table('ktd_syarat')
            ->where('layanan_id', $serviceId)
            ->orderByRaw("CASE LOWER(COALESCE(type, '')) 
                WHEN 'file' THEN 0
                WHEN 'image' THEN 1
                WHEN 'date' THEN 2
                WHEN 'datetime' THEN 3
                WHEN 'input' THEN 4
                WHEN 'textarea' THEN 5
                WHEN 'option' THEN 6
                ELSE 99
            END")
            ->orderByDesc('utama')
            ->orderByDesc('wajib')
            ->orderBy('id')
            ->get()
            ->map(function ($item) {
                $type = $this->normalizedRequirementType($item->type);

                return [
                    'id' => $item->id,
                    'title' => $item->syarat,
                    'note' => $item->keterangan ?: null,
                    'is_required' => (int) ($item->wajib ?? 0) === 1,
                    'is_primary' => (int) ($item->utama ?? 0) === 1,
                    'type' => $item->type,
                    'type_normalized' => $type,
                    'type_label' => $this->requirementTypeLabel($type),
                    'input_type' => $this->requirementInputType($type),
                    'options' => $this->requirementOptions($item->value),
                    'value' => $item->value,
                ];
            })
            ->values()
            ->all();
    }

    private function serviceDetail(int $serviceId): array
    {
        $service = DB::table('ktd_layanan')->where('id', $serviceId)->first();

        abort_unless($service, 404);

        $unitName = DB::table('ktd_department')
            ->where('id', $service->dept_id)
            ->value('nama');

        return [
            'id' => (int) $service->id,
            'dept_id' => (int) $service->dept_id,
            'unit_name' => $unitName ?? '-',
            'title' => $service->nama,
            'description' => $service->deskripsi ?: 'Layanan khusus pada unit kerja ini.',
            'waktu' => $service->waktu ?: '-',
            'biaya' => $this->serviceCostLabel($service->biaya),
            'output' => $service->output ?: '-',
            'status_label' => $this->serviceStatusLabel($service->status),
            'is_spesial' => (int) $service->spesial === 1,
            'requirements' => $this->serviceRequirements((int) $service->id),
            'cover_path' => $this->serviceIconPath((int) $service->id),
        ];
    }

    private function generateRequestNumber(int $departmentId, int $serviceId): string
    {
        $departmentCode = DB::table('ktd_department')
            ->where('id', $departmentId)
            ->value('kode');

        $departmentCode = strtoupper(preg_replace('/[^A-Z0-9]/i', '', (string) $departmentCode));

        if ($departmentCode === '') {
            $departmentCode = 'DEP';
        }

        return sprintf('%s-%s-%s-%03d', $departmentCode, $serviceId, now()->format('ymdHis'), random_int(0, 999));
    }

    private function normalizedRequirementType(?string $type): string
    {
        $normalized = strtolower(trim((string) $type));

        return in_array($normalized, ['file', 'image', 'input', 'textarea', 'date', 'datetime', 'option'], true)
            ? $normalized
            : 'file';
    }

    private function requirementTypeLabel(string $type): string
    {
        return match ($type) {
            'file' => 'PDF',
            'image' => 'Gambar',
            'input' => 'Input',
            'textarea' => 'Textarea',
            'date' => 'Tanggal',
            'datetime' => 'Tanggal & Waktu',
            'option' => 'Pilihan',
            default => 'File',
        };
    }

    private function requirementInputType(string $type): string
    {
        return match ($type) {
            'date' => 'date',
            'datetime' => 'datetime-local',
            default => 'text',
        };
    }

    private function requirementOptions($value): array
    {
        if (! is_string($value) || trim($value) === '') {
            return [];
        }

        $decoded = json_decode($value, true);

        if (! is_array($decoded)) {
            $normalized = preg_split('/[\r\n,|;]+/', $value) ?: [];

            return array_values(array_filter(array_map('trim', $normalized)));
        }

        return array_values(array_filter(array_map(static fn ($option) => (string) $option, $decoded), static fn ($option) => trim($option) !== ''));
    }

    private function isUploadRequirement(string $type): bool
    {
        return in_array($type, ['file', 'image'], true);
    }

    private function reportStatusLabel(string $status): string
    {
        return match ($status) {
            'DIKIRIM' => 'Dikirim',
            'DISETUJUI' => 'Disetujui',
            'DITOLAK' => 'Ditolak',
            'KOSONG' => 'Kosong',
            default => $status !== '' ? Str::headline(strtolower($status)) : 'Kosong',
        };
    }

    private function reportStatusTone(string $status): string
    {
        return match ($status) {
            'DISETUJUI' => 'emerald',
            'DITOLAK' => 'rose',
            'DIKIRIM' => 'amber',
            default => 'slate',
        };
    }

    private function reportStatusClass(string $status): string
    {
        return match ($this->reportStatusTone($status)) {
            'emerald' => 'border-emerald-200 bg-emerald-50 text-emerald-700',
            'rose' => 'border-rose-200 bg-rose-50 text-rose-700',
            'amber' => 'border-amber-200 bg-amber-50 text-amber-700',
            default => 'border-slate-200 bg-slate-50 text-slate-500',
        };
    }

    private function requestFormViewData(array $service, $requester, bool $editing = false, $requestRecord = null, array $existingAnswers = [], array $existingFiles = []): array
    {
        return [
            'service' => $service,
            'requester' => [
                'name' => $requester?->name,
                'identity' => $requester?->nomor_induk ?? '',
            ],
            'editing' => $editing,
            'requestRecord' => $requestRecord,
            'existingAnswers' => $existingAnswers,
            'existingFiles' => $existingFiles,
        ];
    }

    private function indonesianDateLabel(Carbon $date): string
    {
        $days = [
            'Minggu',
            'Senin',
            'Selasa',
            'Rabu',
            'Kamis',
            'Jumat',
            'Sabtu',
        ];

        $months = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        return sprintf(
            '%s, %d %s %d',
            $days[$date->dayOfWeek] ?? $date->format('l'),
            $date->day,
            $months[$date->month] ?? $date->format('F'),
            $date->year
        );
    }

    private function indonesianMonthLabel(Carbon $date): string
    {
        $months = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        return sprintf(
            '%s %d',
            $months[$date->month] ?? $date->format('F'),
            $date->year
        );
    }

    private function assetToDataUri(string $path): string
    {
        if (! file_exists($path)) {
            return '';
        }

        $mime = mime_content_type($path) ?: 'image/png';

        return 'data:' . $mime . ';base64,' . base64_encode((string) file_get_contents($path));
    }

    private function requirementFieldKey(string $type, int $requirementId): string
    {
        return $this->isUploadRequirement($type) ? "files.{$requirementId}" : "values.{$requirementId}";
    }

    private function requirementValidationRules(array $requirement, bool $isDraft, bool $hasExistingUpload = false): array
    {
        $type = $requirement['type_normalized'];
        $isRequired = (bool) $requirement['is_required'] && ! $isDraft && ! $hasExistingUpload;
        $baseRules = $isRequired ? ['required'] : ['nullable'];

        return match ($type) {
            'file' => array_merge($baseRules, ['file', 'mimes:pdf', 'max:10240']),
            'image' => array_merge($baseRules, ['file', 'image', 'max:10240']),
            'textarea' => array_merge($baseRules, ['string', 'max:5000']),
            'input' => array_merge($baseRules, ['string', 'max:1000']),
            'date' => array_merge($baseRules, ['date']),
            'datetime' => array_merge($baseRules, ['date_format:Y-m-d\TH:i']),
            'option' => $this->requirementOptions($requirement['value'] ?? null) !== []
                ? array_merge($baseRules, ['string', Rule::in($this->requirementOptions($requirement['value'] ?? null))])
                : array_merge($baseRules, ['string', 'max:1000']),
            default => array_merge($baseRules, ['string', 'max:1000']),
        };
    }

    private function serviceIconPath(int $seed): string
    {
        $icons = [
            'humas.png',
            'presensi.png',
            'RekapPresensi.png',
            'LaporanKinerja.png',
            'FileUploaded.png',
            'tukin.png',
            'uangmakan.png',
            'logohalal.png',
        ];

        $icon = $icons[$seed % count($icons)] ?? 'FileUploaded.png';

        return asset("assets/img/ikon/{$icon}");
    }

    private function serviceCostLabel(?int $biaya): string
    {
        if ($biaya === null) {
            return 'Tidak tercantum';
        }

        if ($biaya <= 0) {
            return 'Gratis';
        }

        return 'Rp ' . number_format($biaya, 0, ',', '.');
    }

    private function serviceStatusLabel(?int $status): string
    {
        if ($status === null) {
            return 'Tidak diketahui';
        }

        return $status === 1 ? 'Aktif' : 'Nonaktif';
    }

    private function departmentCards(string $kategori, int $status)
    {
        $pageName = 'page_' . str_replace('-', '_', strtolower($kategori));

        $paginator = DB::table('ktd_department')
            ->whereRaw('LOWER(kategori) = ?', [strtolower($kategori)])
            ->where('status', $status)
            ->orderBy('id')
            ->paginate(6, ['*'], $pageName);

        $paginator->withPath(route('satuan-kerja'));
        $paginator->appends(['tab' => $kategori]);

        $paginator->setCollection($paginator->getCollection()->map(function ($item) use ($kategori) {
                $head = $this->departmentHead($item->id, $kategori, $item->nama);
                $pltHead = $head ? null : $this->departmentPltHead($item->id);
                $employeeCount = DB::table('users')
                    ->where('dept_id', $item->id)
                    ->count();

                return [
                    'id' => $item->id,
                    'title' => $item->nama,
                    'subtitle' => $item->deskripsi ?: $item->kode,
                    'meta_label' => 'Nama Unit',
                    'meta_value' => $item->nama,
                    'extra_label' => 'Pegawai',
                    'extra_value' => $employeeCount,
                    'head_label' => $this->departmentHeadLabel($kategori, $item->nama, (bool) $pltHead),
                    'head_value' => $head?->name ?? $pltHead?->name,
                    'cover' => Str::upper(Str::substr($item->nama, 0, 2)),
                    'cover_path' => asset("assets/img/seksi/{$item->id}.jpg"),
                    'href' => route('unit-kerja.detail', $item->id),
                    'type' => $kategori,
                ];
            }));

        return $paginator;
    }

    private function externalGroups(): array
    {
        return [
            'swasta-lainnya' => $this->userCards(999),
            'pemerintah-daerah' => $this->userCards(998),
        ];
    }

    private function userCards(int $deptId)
    {
        $pageName = 'page_user_' . $deptId;

        $paginator = DB::table('users')
            ->where('dept_id', $deptId)
            ->orderBy('id')
            ->paginate(6, ['*'], $pageName);

        $paginator->withPath(route('satuan-kerja'));
        $paginator->appends(['tab' => $deptId === 999 ? 'swasta-lainnya' : 'pemerintah-daerah']);

        $paginator->setCollection($paginator->getCollection()->map(function ($item) use ($deptId) {
                return [
                    'id' => $item->id,
                    'title' => $item->name,
                    'subtitle' => 'PP',
                    'meta_label' => 'Nomor Induk',
                    'meta_value' => $item->nomor_induk,
                    'extra_label' => 'Satker',
                    'extra_value' => $item->satker ?: '-',
                    'cover' => 'PP',
                    'cover_path' => asset("assets/img/seksi/{$item->id}.jpg"),
                    'dept_id' => $deptId,
                    'type' => 'user',
                ];
            }));

        return $paginator;
    }

    private function departmentPeople(int $deptId, ?int $excludeUserId = null)
    {
        $pageName = 'page_people_' . $deptId;
        $query = DB::table('users')
            ->where('dept_id', $deptId)
            ->when($excludeUserId, fn ($builder) => $builder->where('id', '!=', $excludeUserId))
            ->orderBy('id');

        $paginator = $query->paginate(8, ['*'], $pageName);
        $paginator->withPath(route('unit-kerja.detail', $deptId));

        $paginator->setCollection($paginator->getCollection()->map(function ($item) use ($deptId) {
            return $this->personCard($item, $this->personLabel($item), false);
        }));

        return $paginator;
    }

    private function departmentHead(int $deptId, string $kategori, string $departmentName)
    {
        $query = DB::table('users')
            ->where('dept_id', $deptId)
            ->orderBy('id');

        if (strtolower($kategori) === 'kantor') {
            if ($this->isDepartmentName($departmentName, 'Sub-Bagian Tata Usaha')) {
                $query->where('role', 'kasubbag');
            } elseif ($this->isDepartmentName($departmentName, [
                'Seksi Pendidikan Agama Islam',
                'Seksi PD PONTREN',
                'Seksi Pendidikan Madrasah',
                'Seksi BIMAS Islam',
                'Penyelenggara Zakat dan Wakaf',
            ])) {
                $query->where('role', 'kasi');
            } else {
                $query->where('kat_jabatan', 'kepala');
            }
        } else {
            $query->where('kat_jabatan', 'kepala');
        }

        return $query->first();
    }

    private function departmentHeadLabel(string $kategori, string $departmentName, bool $isPlt = false): string
    {
        if ($isPlt) {
            return 'PLT Kepala';
        }

        if (strtolower($kategori) !== 'kantor') {
            return 'Kepala';
        }

        if ($this->isDepartmentName($departmentName, 'Sub-Bagian Tata Usaha')) {
            return 'Kasubbag';
        }

        if ($this->isDepartmentName($departmentName, [
            'Seksi Pendidikan Agama Islam',
            'Seksi PD PONTREN',
            'Seksi Pendidikan Madrasah',
            'Seksi BIMAS Islam',
            'Penyelenggara Zakat dan Wakaf',
        ])) {
            return 'Kasi';
        }

        if ($this->isDepartmentName($departmentName, [
            'Pengawas',
            'Bagian Operasional Kantor(Non ASN)',
        ])) {
            return 'Ketua';
        }

        return 'Kepala';
    }

    private function departmentPltHead(int $deptId)
    {
        return DB::table('plt_plh')
            ->join('users', 'users.id', '=', 'plt_plh.user_id')
            ->where('plt_plh.dept_id_plh', $deptId)
            ->orderByDesc('plt_plh.id')
            ->select('users.id', 'users.name', 'users.nomor_induk', 'users.satker', 'users.role', 'users.kat_jabatan', 'users.pp')
            ->first();
    }

    private function personCard(object $item, string $roleLabel, bool $featured = false): array
    {
        return [
            'id' => $item->id,
            'name' => $item->name,
            'role_label' => $roleLabel,
            'nomor_induk' => $item->nomor_induk ?: '-',
            'satker' => $item->satker ?: '-',
            'avatar_text' => $this->personInitials($item->name),
            'photo_path' => $this->personPhotoUrl($item),
            'featured' => $featured,
        ];
    }

    private function personLabel(object $item): string
    {
        if (! empty($item->kat_jabatan)) {
            return Str::headline($item->kat_jabatan);
        }

        if (! empty($item->role)) {
            return Str::headline($item->role);
        }

        return 'Pegawai';
    }

    private function personInitials(string $name): string
    {
        $words = preg_split('/\s+/', trim($name)) ?: [];
        $letters = collect($words)
            ->filter()
            ->take(2)
            ->map(function ($word) {
                return Str::upper(Str::substr($word, 0, 1));
            })
            ->implode('');

        return $letters !== '' ? $letters : 'PP';
    }

    private function personPhotoUrl(object $item): ?string
    {
        if (empty($item->pp) || empty($item->nomor_induk)) {
            return null;
        }

        $relativePath = "assets/img/users/{$item->nomor_induk}/{$item->pp}";
        $localPath = public_path($relativePath);

        if (file_exists($localPath)) {
            return asset($relativePath);
        }

        return "https://ptsp.kemenagtanahdatar.cloud/uploads/UsersBerkas/{$item->nomor_induk}/{$item->pp}";
    }

    private function isDepartmentName(string $departmentName, string|array $expected): bool
    {
        $candidates = is_array($expected) ? $expected : [$expected];

        foreach ($candidates as $candidate) {
            if (Str::lower(trim($departmentName)) === Str::lower(trim($candidate))) {
                return true;
            }
        }

        return false;
    }
}
