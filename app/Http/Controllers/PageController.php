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
        // Fetch slideshow news (is_slideshow = 1) - ONLY slideshow news, no fill
        $slideshowNews = DB::table('news')
            ->where('status', 'published')
            ->where('is_slideshow', 1)
            ->orderByDesc('publish_date')
            ->limit(5)
            ->get();

        // Fetch featured news (is_featured = 1) - ONLY featured news, max 3
        $featuredNews = DB::table('news')
            ->where('status', 'published')
            ->where('is_featured', 1)
            ->orderByDesc('publish_date')
            ->limit(3)
            ->get();

        // Fetch latest news - max 6 latest published news
        $latestNews = DB::table('news')
            ->where('status', 'published')
            ->orderByDesc('publish_date')
            ->limit(6)
            ->get();

        return view('welcome', [
            'slideshowNews' => $slideshowNews,
            'featuredNews' => $featuredNews,
            'latestNews' => $latestNews,
        ]);
    }

    public function allNews(Request $request)
    {
        $query = DB::table('news')
            ->where('status', 'published')
            ->orderByDesc('publish_date');

        // Filter by category if provided
        if ($request->has('category') && $request->category) {
            $query->where('category', $request->category);
        }

        $allNews = $query->paginate(12);

        // Get unique categories for filter
        $categories = DB::table('news')
            ->where('status', 'published')
            ->select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view('news.index', [
            'news' => $allNews,
            'categories' => $categories,
            'selectedCategory' => $request->category,
        ]);
    }

    public function newsShow($slug)
    {
        // Try to find by slug first, then by id
        $news = DB::table('news')
            ->where('slug', $slug)
            ->orWhere('id', $slug)
            ->first();

        if (!$news || $news->status !== 'published') {
            abort(404);
        }

        // Track view
        $this->trackNewsView($news->id);

        // Get related news (same category, excluding current)
        $relatedNews = DB::table('news')
            ->where('status', 'published')
            ->where('category', $news->category)
            ->where('id', '!=', $news->id)
            ->orderByDesc('publish_date')
            ->limit(3)
            ->get();

        // Get updated view counts
        $newsStats = DB::table('news')->where('id', $news->id)->first(['view_count', 'unique_view_count']);

        return view('news.show', [
            'news' => $news,
            'relatedNews' => $relatedNews,
            'viewCount' => $newsStats->view_count ?? 0,
            'uniqueViewCount' => $newsStats->unique_view_count ?? 0,
        ]);
    }

    /**
     * Track news view.
     */
    private function trackNewsView(int $newsId): void
    {
        $ipAddress = request()->ip();
        $sessionId = session()->getId();
        $userAgent = request()->userAgent();
        $viewedAt = now();

        // Check if this IP/session already viewed this news today
        $alreadyViewed = DB::table('news_views')
            ->where('news_id', $newsId)
            ->where('ip_address', $ipAddress)
            ->whereDate('viewed_at', $viewedAt->toDateString())
            ->exists();

        if ($alreadyViewed) {
            // Only increment total view count
            DB::table('news')->where('id', $newsId)->increment('view_count');
        } else {
            // Increment both view counts
            DB::table('news')->where('id', $newsId)->increment('view_count');
            DB::table('news')->where('id', $newsId)->increment('unique_view_count');

            // Log the unique view
            DB::table('news_views')->insert([
                'news_id' => $newsId,
                'ip_address' => $ipAddress,
                'session_id' => $sessionId,
                'user_agent' => $userAgent,
                'viewed_at' => $viewedAt,
            ]);
        }
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

    public function janjiTemu(int $deptId, Request $request)
    {
        $dept = DB::table('ktd_department')->where('id', $deptId)->first();
        abort_unless($dept, 404);

        $user = auth()->user();
        $employeeId = $request->query('employee_id');
        $isDirect = $request->query('direct') == '1';

        $targetData = null;

        if ($employeeId) {
            $employee = DB::table('users')->where('id', $employeeId)->first();
            if ($employee) {
                $targetData = [
                    'type' => 'employee',
                    'employee_id' => $employee->id,
                    'employee_name' => $employee->name,
                    'employee_role' => $this->personLabel($employee),
                    'employee_nip' => $employee->nomor_induk,
                    'employee_photo' => $this->personPhotoUrl($employee),
                ];
            }
        } elseif ($isDirect) {
            $targetData = [
                'type' => 'direct',
                'employee_id' => null,
                'employee_name' => 'Langsung ke Seksi',
                'employee_role' => 'Tanpa Pegawai Tertentu',
                'employee_nip' => null,
                'employee_photo' => asset('assets/img/ikon/505.png'),
            ];
        }

        return view('janji-temu', [
            'deptId' => $deptId,
            'deptName' => $dept->nama,
            'targetData' => $targetData,
            'requester' => [
                'name' => $user?->name,
                'identity' => $user?->nomor_induk ?? '',
            ],
        ]);
    }

    public function submitJanjiTemu(int $deptId, Request $request)
    {
        $user = auth()->user();
        abort_unless($user, 403);

        $validator = Validator::make($request->all(), [
            'tanggal' => ['required'],
            'jam' => ['required'],
            'keterangan' => ['required', 'max:1000'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();
        $waktu = $data['tanggal'] . ' ' . $data['jam'] . ':00';

        $userNip = $user->nomor_induk ?? null;
        $userName = $user->name ?? '-';
        $userSatker = $user->satker ?? null;
        $tipe = $request->input('tipe', 'asn');
        $tujuan = $data['keterangan'];

        if($tipe === 'direct') {
            $tipex = 'satker';
        }else{
            $tipex = 'asn';
        }

        $insertData = [
            'nomor_induk' => $userNip,
            'kategori' => $user->role ?? 'public',
            'tipe' => $tipex,
            'nama' => $userName,
            'waktu' => $waktu,
            'nip_tujuan' => $request->input('nip_tujuan'),
            'tujuan' => $tujuan,
            'asal' => $userSatker,
            'status' => 'APPOINTMENT',
            'onStaff' => 999,
            'komen' => null,
            'ttd' => 'NONE',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        DB::table('ktd_bukutamu')->insert($insertData);

        return redirect()->route('pelayanan')->with('success', 'Janji temu berhasil diajukan.');
    }

    public function whistleblowing()
    {
        $user = auth()->user();
        return view('whistleblowing', [
            'user' => $user,
        ]);
    }

    public function submitWhistleblowing(Request $request)
    {
        $user = auth()->user();

        $validator = Validator::make($request->all(), [
            'judul' => ['required', 'max:244'],
            'keterangan' => ['required', 'max:5000'],
            'email' => ['required', 'email', 'max:255'],
            'telp' => ['nullable', 'max:255'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();

        // Generate kode
        $kode = 'PENGADUAN' . date('Ymd') . str_pad(mt_rand(1, 999), 3, '0', STR_PAD_LEFT);

        $insertData = [
            'kode' => $kode,
            'jenis' => 'PENGADUAN',
            'user_nip' => $user->nomor_induk ?? 0,
            'nama' => $user->name ?? '-',
            'email' => $data['email'],
            'telp' => $data['telp'] ?? null,
            'judul' => $data['judul'],
            'keterangan' => $data['keterangan'],
            'filename' => null,
            'status' => 'PENDING',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        DB::table('ktd_pengaduan')->insert($insertData);

        return redirect()->route('pelayanan')->with('success', 'Pengaduan berhasil diajukan. Kami akan segera memproses laporan Anda.');
    }

    public function unitEmployees(int $deptId)
    {
        $departmentRow = DB::table('ktd_department')->where('id', $deptId)->first();

        // Get leaders (kepala/kasi)
        $head = $this->departmentHead($deptId, $departmentRow->kategori ?? 'kantor', $departmentRow->nama ?? '');
        $pltHead = $head ? null : $this->departmentPltHead($deptId);
        $leader = $head ?? $pltHead;
        $leaderLabel = $head ? $this->personLabel($head) : ($pltHead ? 'PLT Kepala' : 'Kepala');
        $isPlt = ! $head && $pltHead;

        $leaders = [];
        if ($leader) {
            $leaders[] = [
                'id' => $leader->id,
                'name' => $leader->name,
                'role_label' => $leaderLabel,
                'is_plt' => $isPlt,
                'nomor_induk' => $leader->nomor_induk ?? '-',
                'avatar_text' => $this->personInitials($leader->name),
                'photo_path' => $this->personPhotoUrl($leader),
            ];
        }

        // Get regular employees (excluding leaders)
        $excludeIds = array_filter([$head?->id, $pltHead?->id]);
        $employees = DB::table('users')
            ->where('dept_id', $deptId)
            ->whereNotIn('role', ['other', 'pensiun', 'pindah'])
            ->when($excludeIds, fn ($q) => $q->whereNotIn('id', $excludeIds))
            ->orderByRaw("FIELD(kat_jabatan, 'kaur', 'kasubag', 'pelaksana', 'staf', 'honorer', 'guru')")
            ->orderBy('name', 'asc')
            ->get()
            ->map(function ($item) {
                $isKaur = strtolower($item->kat_jabatan ?? '') === 'kaur';
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'role_label' => $this->personLabel($item),
                    'nomor_induk' => $item->nomor_induk ?? '-',
                    'avatar_text' => $this->personInitials($item->name),
                    'photo_path' => $this->personPhotoUrl($item),
                    'is_kaur' => $isKaur,
                ];
            })
            ->values()
            ->all();

        return response()->json([
            'leaders' => $leaders,
            'employees' => $employees,
            'dept_id' => $deptId,
        ]);
    }

    public function requestService(int $serviceId, Request $request = null)
    {
        $service = $this->serviceDetail($serviceId);
        $requester = auth()->user();

        $appointmentData = null;
        $req = $request ?? request();
        $employeeId = $req->query('employee_id');
        $isDirect = $req->query('direct') === 'true';

        if ($employeeId) {
            $employee = DB::table('users')->where('id', $employeeId)->first();
            if ($employee) {
                $appointmentData = [
                    'type' => 'employee',
                    'employee_id' => $employee->id,
                    'employee_name' => $employee->name,
                    'employee_role' => $this->personLabel($employee),
                    'employee_photo' => $this->personPhotoUrl($employee),
                ];
            }
        } elseif ($isDirect) {
            $appointmentData = [
                'type' => 'direct',
                'employee_id' => null,
                'employee_name' => 'Langsung ke Seksi',
                'employee_role' => 'Tanpa Pegawai Tertentu',
                'employee_photo' => asset('assets/img/ikon/505.png'),
            ];
        }

        return view('service-request', array_merge($this->requestFormViewData($service, $requester), [
            'service' => $service,
            'requester' => [
                'name' => $requester?->name,
                'identity' => $requester?->nomor_induk ?? '',
            ],
            'appointmentData' => $appointmentData,
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

        // Handle year parameter for bulanan tab, or month for other tabs
        $selectedYear = $request->input('year');
        $selectedMonth = $request->string('month')->toString();

        if ($activeTab === 'bulanan') {
            // For bulanan tab, use year parameter
            if (!$selectedYear || !preg_match('/^\d{4}$/', $selectedYear)) {
                $selectedYear = now()->format('Y');
            }
            $selectedYear = (int) $selectedYear;
            // Convert year to month format for display (use January as default)
            $selectedMonth = $selectedYear . '-01';
        } else {
            // For harian and humas tabs, use month parameter
            if (! preg_match('/^\d{4}-\d{2}$/', $selectedMonth)) {
                $selectedMonth = now()->format('Y-m');
            }
            $selectedYear = (int) Carbon::parse($selectedMonth)->format('Y');
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

        // New query: 1 row per user per date (Option A: JSON Column)
        $dailyQuery = DB::table('satker_kegiatan')
            ->where('user_id', $user->id)
            ->whereBetween('tanggal', [$selectedMonthStart->toDateString(), $selectedMonthEnd->toDateString()])
            ->orderBy('tanggal');

        // For search, we need to search in JSON - handle separately
        $dailyEntries = $dailyQuery->get();

        // Process JSON data format
        $dailyGroups = collect([])
            ->keyBy(fn ($item) => $item['date'])
            ->toArray();

        $totalEntries = 0;
        $totalVolume = 0;
        $latestUpdate = null;

        foreach ($dailyEntries as $row) {
            $date = Carbon::parse($row->tanggal)->toDateString();
            $jsonData = json_decode((string) ($row->data_json ?? '{"items":[]}'), true) ?: ['items' => []];
            $items = $jsonData['items'] ?? [];

            // Handle legacy format (direct columns instead of JSON)
            if (empty($items) && !empty($row->kegiatan)) {
                $items = [[
                    'id' => $row->id,
                    'k' => $row->kegiatan,
                    'v' => $row->volume ?? 0,
                    's' => $row->satuan ?? 'Kegiatan'
                ]];
            }

            // Filter by search if needed
            if ($search !== '') {
                $items = array_filter($items, function ($item) use ($search) {
                    $kegiatan = $item['k'] ?? ($item['kegiatan'] ?? '');
                    return stripos($kegiatan, $search) !== false;
                });
            }

            if (empty($items)) {
                continue;
            }

            $mappedItems = array_map(function ($item) use ($row) {
                $volume = (int) ($item['v'] ?? 0);
                $satuan = $item['s'] ?? 'Kegiatan';

                return [
                    'id' => $item['id'] ?? null,
                    'kegiatan' => trim((string) ($item['k'] ?? '')),
                    'volume' => $volume,
                    'satuan' => $satuan,
                    'meta' => $volume > 0 ? trim($volume . ' ' . $satuan) : $satuan,
                    'tanggal' => $row->tanggal,
                ];
            }, array_values($items));

            $dayVolume = array_sum(array_column($mappedItems, 'volume'));
            $dayEntries = count($mappedItems);
            $totalEntries += $dayEntries;
            $totalVolume += $dayVolume;

            if ($row->updated_at && (!$latestUpdate || $row->updated_at > $latestUpdate)) {
                $latestUpdate = $row->updated_at;
            }

            $dateCarbon = Carbon::parse($date);

            if (!isset($dailyGroups[$date])) {
                $dailyGroups[$date] = [
                    'date' => $dateCarbon->toDateString(),
                    'label' => $this->indonesianDateLabel($dateCarbon),
                    'items' => [],
                    'entries' => 0,
                    'volume' => 0,
                    'row_id' => $row->id,
                ];
            }

            $dailyGroups[$date]['items'] = array_merge($dailyGroups[$date]['items'], $mappedItems);
            $dailyGroups[$date]['entries'] += $dayEntries;
            $dailyGroups[$date]['volume'] += $dayVolume;
        }

        // Sort by date and re-index
        $dailyGroups = collect($dailyGroups)->sortBy('date')->values();

        $dailySummary = [
            'entries' => $totalEntries,
            'days' => count($dailyGroups),
            'volume' => $totalVolume,
            'latest_update' => $latestUpdate,
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

        // Rekap Bulanan dari satker_ckh - data laporan kinerja bulanan user
        // For bulanan tab, show only current user's data for the selected year
        if ($activeTab === 'bulanan') {
            $yearStart = Carbon::createFromFormat('Y-m-d', $selectedYear . '-01-01')->startOfYear();
            $yearEnd = Carbon::createFromFormat('Y-m-d', $selectedYear . '-12-31')->endOfYear();
        } else {
            $yearStart = $selectedMonthStart;
            $yearEnd = $selectedMonthEnd;
        }

        // For bulanan tab, show only current user's reports
        $bulananReports = DB::table('satker_ckh as ck')
            ->leftJoin('users as u', 'u.id', '=', 'ck.user_id')
            ->leftJoin('ktd_department as dept', 'dept.id', '=', 'ck.dept_id')
            ->whereBetween('ck.bulan', [$yearStart->toDateString(), $yearEnd->toDateString()])
            ->where('ck.item_id', 1)
            // Filter: only show current user's reports for bulanan tab
            ->where('ck.user_id', $user->id)
            ->select([
                'ck.id',
                'ck.user_id',
                'ck.dept_id',
                'ck.bulan',
                'ck.filename',
                'ck.status',
                'ck.alasan',
                'ck.petugas',
                'ck.sending',
                'ck.created_at',
                'ck.updated_at',
                'u.name as user_name',
                'u.nomor_induk',
                'dept.nama as dept_name',
            ])
            ->orderBy('ck.bulan', 'desc')
            ->get()
            ->map(function ($item) {
                $statusConfig = [
                    'KOSONG' => ['label' => 'Belum Kirim', 'color' => 'slate'],
                    'DIKIRIM' => ['label' => 'Dikirim', 'color' => 'amber'],
                    'DISETUJUI' => ['label' => 'Disetujui', 'color' => 'emerald'],
                    'DITOLAK' => ['label' => 'Ditolak', 'color' => 'rose'],
                ];
                $status = $statusConfig[$item->status] ?? ['label' => $item->status, 'color' => 'slate'];
                // Handle bulan field - could be date only or datetime
                $bulanRaw = is_string($item->bulan) ? substr($item->bulan, 0, 10) : $item->bulan;
                $bulanDate = Carbon::createFromFormat('Y-m-d', $bulanRaw);

                return [
                    'id' => $item->id,
                    'user_id' => $item->user_id,
                    'user_name' => $item->user_name ?? 'Unknown',
                    'nomor_induk' => $item->nomor_induk ?? '-',
                    'dept_name' => $item->dept_name ?? '-',
                    'bulan' => $bulanDate->format('F Y'),
                    'bulan_raw' => $bulanRaw,
                    'filename' => $item->filename,
                    'status' => $item->status,
                    'status_label' => $status['label'],
                    'status_color' => $status['color'],
                    'alasan' => $item->alasan,
                    'sending' => $item->sending,
                    'created_at' => $item->created_at,
                    'updated_at' => $item->updated_at,
                ];
            });

        // Filter humas data by selected year (only for humas tab)
        $humasYearFilter = null;
        if ($activeTab === 'humas') {
            $humasYearFilter = $request->input('humas_year');
            if (!$humasYearFilter || !preg_match('/^\d{4}$/', $humasYearFilter)) {
                $humasYearFilter = (int) date('Y');
            }
        }

        $humasData = DB::table('laporan_humas as lh')
            ->leftJoin('users as u', 'u.id', '=', 'lh.user_id')
            ->where('lh.user_id', $user->id)
            ->when($humasYearFilter, function ($query) use ($humasYearFilter) {
                // Filter by year if humas_year is provided
                return $query->whereRaw("LEFT(lh.bulan, 4) = ?", [$humasYearFilter]);
            })
            ->orderByDesc('lh.bulan')
            ->get()
            ->map(function ($item) {
                $data = json_decode((string) ($item->data ?? '{}'), true) ?: [];
                $platforms = collect(['facebook', 'instagram', 'tiktok', 'website', 'youtube'])->map(function (string $channel) use ($data) {
                    $channelData = $data[$channel] ?? [];

                    return [
                        'name' => $channel,
                        'has_data' => filled(data_get($channelData, 'first.date')) || filled(data_get($channelData, 'last.date')),
                        'first_date' => data_get($channelData, 'first.date'),
                        'first_content' => data_get($channelData, 'first.content'),
                        'first_link' => data_get($channelData, 'first.link'),
                        'last_date' => data_get($channelData, 'last.date'),
                        'last_content' => data_get($channelData, 'last.content'),
                        'last_link' => data_get($channelData, 'last.link'),
                    ];
                })->values();

                $verifikatorName = null;
                if ($item->verifikator_id) {
                    $verifikator = DB::table('users')->where('id', $item->verifikator_id)->first();
                    $verifikatorName = $verifikator?->name;
                }

                return [
                    'id' => $item->id,
                    'month_label' => $item->bulan ? Carbon::createFromFormat('Y-m-d', substr($item->bulan, 0, 10))->format('m/Y') : '-',
                    'bulan_full' => $item->bulan ? substr($item->bulan, 0, 7) : '',
                    'author' => $item->name ?? 'Unknown',
                    'status' => $item->status ?: '-',
                    'verifikator' => $verifikatorName,
                    'comment' => $item->komen ?: $item->user_komen ?: '-',
                    'updated_at' => $item->updated_at,
                    'platforms' => $platforms,
                    'active_channels' => $platforms->where('has_data', true)->count(),
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
            $editingRow = DB::table('satker_kegiatan')
                ->where('user_id', $user->id)
                ->whereDate('tanggal', $editingDate)
                ->first();

            if ($editingRow) {
                $dateCarbon = Carbon::parse($editingDate);
                $jsonData = json_decode((string) ($editingRow->data_json ?? '{"items":[]}'), true) ?: ['items' => []];
                $items = $jsonData['items'] ?? [];

                $editingGroup = [
                    'row_id' => $editingRow->id,
                    'date' => $dateCarbon->toDateString(),
                    'label' => $this->indonesianDateLabel($dateCarbon),
                    'items' => array_map(function ($item) {
                        return [
                            'id' => $item['id'] ?? null,
                            'kegiatan' => trim((string) ($item['k'] ?? '')),
                            'volume' => (int) ($item['v'] ?? 0),
                            'satuan' => $item['s'] ?? 'Kegiatan',
                        ];
                    }, $items),
                ];
            }
        }

        // Alias for view compatibility
        $humasReports = $humasData;

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
            'bulananReports' => $bulananReports,
            'humasData' => $humasData,
            'humasYear' => $humasYearFilter,
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

    public function laporanKinerjaBawahan(Request $request)
    {
        $user = $request->user();

        abort_unless($user, 403);

        // Check role - only kepala, kasubbag, or kasi can access
        $allowedRoles = ['kepala', 'kasubbag', 'kasi'];
        $userRole = strtolower(trim((string) ($user->kat_jabatan ?? '')));

        if (!in_array($userRole, $allowedRoles)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        if (!$user->dept_id) {
            return view('laporan-kinerja-bawahan', [
                'error' => 'Unit kerja Anda belum ditetapkan. Hubungi administrator.',
                'reports' => collect([]),
                'selectedMonth' => date('Y-m'),
                'selectedMonthLabel' => now()->format('F Y'),
                'totalUsers' => 0,
                'userRole' => $userRole,
            ]);
        }

        $selectedMonth = $request->string('month')->toString();
        if (!preg_match('/^\d{4}-\d{2}$/', $selectedMonth)) {
            $selectedMonth = now()->format('Y-m');
        }

        $monthStart = Carbon::createFromFormat('Y-m', $selectedMonth)->startOfMonth();
        $monthEnd = $monthStart->copy()->endOfMonth();

        // Get all users in the same department
        $bawahanUsers = DB::table('users')
            ->where('dept_id', $user->dept_id)
            ->where('id', '!=', $user->id)
            ->select(['id', 'name', 'kat_jabatan'])
            ->get();

        $userIds = $bawahanUsers->pluck('id')->toArray();
        $totalUsers = count($userIds);

        // Get laporan bulanan (satker_ckh) data for all bawahan in selected month
        $reports = DB::table('satker_ckh as ck')
            ->leftJoin('users as u', 'u.id', '=', 'ck.user_id')
            ->leftJoin('ktd_department as dept', 'dept.id', '=', 'ck.dept_id')
            ->whereIn('ck.user_id', $userIds)
            ->whereBetween('ck.bulan', [$monthStart->toDateString(), $monthEnd->toDateString()])
            ->where('ck.item_id', 1)
            ->select([
                'ck.id',
                'ck.user_id',
                'ck.dept_id',
                'ck.bulan',
                'ck.filename',
                'ck.status',
                'ck.alasan',
                'ck.petugas',
                'ck.sending',
                'ck.created_at',
                'ck.updated_at',
                'u.name as user_name',
                'u.pekerjaan',
                'dept.nama as dept_name',
            ])
            ->orderBy('u.name')
            ->orderBy('ck.bulan', 'desc')
            ->get()
            ->map(function ($item) {
                $bulanDate = Carbon::createFromFormat('Y-m-d', substr($item->bulan, 0, 10));
                $bulanLabel = $bulanDate->format('F Y');

                $statusColors = [
                    'DISETUJUI' => 'emerald',
                    'DIKIRIM' => 'amber',
                    'DITOLAK' => 'rose',
                ];

                return [
                    'id' => $item->id,
                    'user_id' => $item->user_id,
                    'user_name' => $item->user_name ?? 'Unknown',
                    'jabatan' => $item->pekerjaan ?? '-',
                    'bulan' => $bulanDate->format('Y-m'),
                    'bulan_label' => $bulanLabel,
                    'filename' => $item->filename,
                    'status' => $item->status,
                    'status_color' => $statusColors[$item->status] ?? 'slate',
                    'alasan' => $item->alasan,
                    'sending' => $item->sending,
                    'sending_formatted' => $this->indonesianDateTimeFormat($item->sending),
                    'created_at' => $item->created_at,
                    'updated_at' => $item->updated_at,
                ];
            });

        return view('laporan-kinerja-bawahan', [
            'reports' => $reports,
            'selectedMonth' => $selectedMonth,
            'selectedMonthLabel' => $monthStart->format('F Y'),
            'totalUsers' => $totalUsers,
            'userRole' => $userRole,
            'bawahanUsers' => $bawahanUsers,
            'deptName' => DB::table('ktd_department')->where('id', $user->dept_id)->value('nama') ?? 'Unit Kerja',
            'error' => null,
        ]);
    }

    public function profil(Request $request)
    {
        $user = $request->user();

        abort_unless($user, 403);

        $menuItems = [
            [
                'id' => 'data-pribadi',
                'title' => 'Data Pribadi',
                'icon' => '513.png',
                'route' => 'profil.edit',
            ],
            [
                'id' => 'riwayat-pendidikan',
                'title' => 'Riwayat Pendidikan',
                'icon' => '514.png',
                'route' => null,
            ],
            [
                'id' => 'riwayat-pekerjaan',
                'title' => 'Riwayat Pekerjaan',
                'icon' => '515.png',
                'route' => null,
            ],
            [
                'id' => 'data-kgb',
                'title' => 'Data Kenaikan Gaji Berkala',
                'icon' => 'KGB.png',
                'route' => null,
            ],
            [
                'id' => 'riwayat-slip-gaji',
                'title' => 'Riwayat Slip Gaji',
                'icon' => '516.png',
                'route' => null,
            ],
            [
                'id' => 'dokumen-amprah',
                'title' => 'Dokumen Amprah',
                'icon' => 'keu003.png',
                'route' => null,
            ],
        ];

        return view('profil', [
            'menuItems' => $menuItems,
            'user' => $user,
            'userDept' => $user->dept_id ? DB::table('ktd_department')->where('id', $user->dept_id)->value('nama') : null,
        ]);
    }

    public function editProfil(Request $request)
    {
        $user = $request->user();

        abort_unless($user, 403);

        // Get department name
        if ($user->dept_id == 999 || $user->dept_id == 998) {
            $satuanKerja = $user->satker ?? '-';
        } elseif ($user->dept_id) {
            $satuanKerja = DB::table('ktd_department')->where('id', $user->dept_id)->value('nama') ?? '-';
        } else {
            $satuanKerja = '-';
        }

        return view('profil-edit', [
            'user' => $user,
            'satuanKerja' => $satuanKerja,
        ]);
    }

    public function updateProfil(Request $request)
    {
        $user = $request->user();

        abort_unless($user, 403);

        $validator = Validator::make($request->all(), [
            'nip' => ['nullable', 'string', 'max:50'],
            'tempat_lahir' => ['nullable', 'string', 'max:100'],
            'tanggal_lahir' => ['nullable', 'date'],
            'jenis_kelamin' => ['nullable', 'string', 'in:laki-laki,perempuan'],
            'alamat' => ['nullable', 'string', 'max:500'],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email'],
            'npwp' => ['nullable', 'string', 'max:30'],
            'rekening' => ['nullable', 'string', 'max:30'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'facebook' => ['nullable', 'string', 'max:255'],
            'twitter' => ['nullable', 'string', 'max:255'],
            'linkedin' => ['nullable', 'string', 'max:255'],
            'instagram' => ['nullable', 'string', 'max:255'],
            'nikah' => ['nullable', 'in:0,1'],
            'jenis_pjob' => ['nullable', 'string', 'max:50'],
            'pjob' => ['nullable', 'string', 'max:255'],
            'jml_anak' => ['nullable', 'integer', 'min:0'],
            'req_tunjangan' => ['nullable', 'in:0,1'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();

        // Map jk values to jenis_kelamin for compatibility
        $jkMap = [
            'laki-laki' => 'laki-laki',
            'perempuan' => 'perempuan',
            'Pria' => 'laki-laki',
            'Wanita' => 'perempuan',
        ];
        $jenisKelamin = $data['jenis_kelamin'] ?? null;
        if ($jenisKelamin && isset($jkMap[$jenisKelamin])) {
            $jenisKelamin = $jkMap[$jenisKelamin];
        }

        DB::table('users')->where('id', $user->id)->update([
            'nip' => $data['nip'] ?? null,
            'tempat_lahir' => $data['tempat_lahir'] ?? null,
            'tanggal_lahir' => $data['tanggal_lahir'] ?? null,
            'jk' => $jenisKelamin,
            'alamat' => $data['alamat'] ?? null,
            'telp' => $data['no_hp'] ?? null,
            'email' => $data['email'] ?? null,
            'npwp' => $data['npwp'] ?? null,
            'rekening' => $data['rekening'] ?? null,
            'bio' => $data['bio'] ?? null,
            'facebook' => $data['facebook'] ?? null,
            'twitter' => $data['twitter'] ?? null,
            'linkedin' => $data['linkedin'] ?? null,
            'instagram' => $data['instagram'] ?? null,
            'nikah' => $data['nikah'] ?? null,
            'jenis_pjob' => $data['jenis_pjob'] ?? null,
            'pjob' => $data['pjob'] ?? null,
            'jml_anak' => $data['jml_anak'] ?? null,
            'req_tunjangan' => $data['req_tunjangan'] ?? null,
            'updated_at' => now(),
        ]);

        return redirect()->route('profil')->with('success', 'Profil berhasil diperbarui.');
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

        // Cek apakah dept_id memerlukan input manual atasan
        $specialDeptIds = [998, 999];
        $deptId = (int) $user->dept_id;

        if (in_array($deptId, $specialDeptIds)) {
            // Tampilkan form input atasan
            return view('pdf.supervisor-input', [
                'deptId' => $deptId,
                'month' => $selectedMonth,
                'tab' => $request->input('tab', 'harian'),
                'unitName' => $unitName ?: '-',
                'periodLabel' => $periodLabel,
            ]);
        }

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

                $allItems = [];
                foreach ($items as $item) {
                    // Try JSON format first
                    $jsonData = json_decode((string) ($item->data_json ?? '{"items":[]}'), true) ?: ['items' => []];
                    $itemsArr = $jsonData['items'] ?? [];

                    // Handle legacy format (direct columns)
                    if (empty($itemsArr) && !empty($item->kegiatan)) {
                        $itemsArr = [[
                            'k' => $item->kegiatan,
                            'v' => $item->volume ?? 0,
                            's' => $item->satuan ?? 'Kegiatan'
                        ]];
                    }

                    foreach ($itemsArr as $it) {
                        $volume = (int) ($it['v'] ?? ($it['volume'] ?? 0));
                        $unit = trim((string) ($it['s'] ?? ($it['satuan'] ?? 'Kegiatan')));

                        $allItems[] = [
                            'kegiatan' => trim((string) ($it['k'] ?? ($it['kegiatan'] ?? ''))),
                            'volume' => $volume,
                            'satuan' => $unit,
                            'meta' => $volume > 0 ? trim($volume . ' ' . $unit) : $unit,
                        ];
                    }
                }

                return [
                    'date' => $dateCarbon->toDateString(),
                    'label' => $this->indonesianDateLabel($dateCarbon),
                    'items' => $allItems,
                ];
            })
            ->values()
            ->all();

        // Cek PLT/PJH di tabel plt_plh
        $pltPlh = DB::table('plt_plh')
            ->where('dept_id_plh', $user->dept_id)
            ->first();

        $isPlh = false;
        $signatureName = '..................................';
        $signatureNip = '';

        if ($pltPlh) {
            // PLT exist - gunakan user PLT
            $pltUser = DB::table('users')->where('id', $pltPlh->user_id)->first();
            if ($pltUser) {
                $isPlh = true;
                $signatureName = $pltUser->name;
                $signatureNip = $pltUser->nomor_induk ? 'NIP. ' . $pltUser->nomor_induk : '';
            }
        } else {
            // Cari kepala/kasi/kasubbag berdasarkan dept_id
            $kepalaJabatan = ['kepala', 'kasi', 'kasubbag'];
            $kepala = DB::table('users')
                ->where('dept_id', $user->dept_id)
                ->whereIn('kat_jabatan', $kepalaJabatan)
                ->first();

            if ($kepala) {
                $signatureName = $kepala->name;
                $signatureNip = $kepala->nomor_induk ? 'NIP. ' . $kepala->nomor_induk : '';
            }
        }

        $pdfData = [
            'userName' => $user->name,
            'userNip' => $user->nomor_induk ?: '-',
            'unitName' => $unitName ?: '-',
            'positionName' => trim((string) ($user->pekerjaan ?: '-')) ?: '-',
            'periodLabel' => $periodLabel,
            'dailyGroups' => $dailyGroups,
            'headerImage' => $this->assetToDataUri(public_path('assets/img/template/header.png')),
            'generatedAt' => now()->translatedFormat('d F Y H:i'),
            'signatureName' => $signatureName,
            'signatureNip' => $signatureNip,
            'signatureImage' => null,
            'signatureLabel' => $isPlh ? 'Mengetahui<br>PLT Kepala,' : 'Mengetahui<br>Kepala,',
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

    public function submitSupervisor(Request $request)
    {
        $user = $request->user();

        abort_unless($user, 403);

        $validator = Validator::make($request->all(), [
            'dept_id' => ['required', 'integer'],
            'month' => ['required', 'string'],
            'tab' => ['nullable', 'string'],
            'supervisor_name' => ['required', 'string', 'max:255'],
            'supervisor_nip' => ['required', 'string', 'max:50'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();

        $specialDeptIds = [998, 999];
        if (! in_array((int) $data['dept_id'], $specialDeptIds)) {
            abort(403, 'Departemen tidak valid untuk input atasan.');
        }

        $selectedMonth = $data['month'];
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

                $allItems = [];
                foreach ($items as $item) {
                    $jsonData = json_decode((string) ($item->data_json ?? '{"items":[]}'), true) ?: ['items' => []];
                    $itemsArr = $jsonData['items'] ?? [];

                    if (empty($itemsArr) && ! empty($item->kegiatan)) {
                        $itemsArr = [[
                            'k' => $item->kegiatan,
                            'v' => $item->volume ?? 0,
                            's' => $item->satuan ?? 'Kegiatan',
                        ]];
                    }

                    foreach ($itemsArr as $it) {
                        $volume = (int) ($it['v'] ?? ($it['volume'] ?? 0));
                        $unit = trim((string) ($it['s'] ?? ($it['satuan'] ?? 'Kegiatan')));

                        $allItems[] = [
                            'kegiatan' => trim((string) ($it['k'] ?? ($it['kegiatan'] ?? ''))),
                            'volume' => $volume,
                            'satuan' => $unit,
                            'meta' => $volume > 0 ? trim($volume . ' ' . $unit) : $unit,
                        ];
                    }
                }

                return [
                    'date' => $dateCarbon->toDateString(),
                    'label' => $this->indonesianDateLabel($dateCarbon),
                    'items' => $allItems,
                ];
            })
            ->values()
            ->all();

        $pltPlh = DB::table('plt_plh')
            ->where('dept_id_plh', $user->dept_id)
            ->first();

        $isPlh = false;
        $signatureName = $data['supervisor_name'];
        $signatureNip = 'NIP. ' . $data['supervisor_nip'];

        if ($pltPlh) {
            $pltUser = DB::table('users')->where('id', $pltPlh->user_id)->first();
            if ($pltUser) {
                $isPlh = true;
                $signatureName = $pltUser->name;
                $signatureNip = $pltUser->nomor_induk ? 'NIP. ' . $pltUser->nomor_induk : '';
            }
        }

        $pdfData = [
            'userName' => $user->name,
            'userNip' => $user->nomor_induk ?: '-',
            'unitName' => $unitName ?: '-',
            'positionName' => trim((string) ($user->pekerjaan ?: '-')) ?: '-',
            'periodLabel' => $periodLabel,
            'dailyGroups' => $dailyGroups,
            'headerImage' => $this->assetToDataUri(public_path('assets/img/template/header.png')),
            'generatedAt' => now()->translatedFormat('d F Y H:i'),
            'signatureName' => $signatureName,
            'signatureNip' => $signatureNip,
            'signatureImage' => null,
            'signatureLabel' => $isPlh ? 'Mengetahui<br>PLT Kepala,' : 'Mengetahui<br>Kepala,',
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

    public function replaceLaporanKinerjaFile(Request $request, int $reportId)
    {
        $user = $request->user();

        abort_unless($user, 403);

        $validator = Validator::make($request->all(), [
            'file' => ['required', 'file', 'mimes:pdf', 'max:10240'], // max 10MB
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Get existing report
        $report = DB::table('satker_ckh')
            ->where('id', $reportId)
            ->where('user_id', $user->id)
            ->first();

        abort_unless($report, 404);

        // Check if status allows replacement (only DIKIRIM or DITOLAK)
        if (!in_array($report->status, ['DIKIRIM', 'DITOLAK'])) {
            return back()->with('error', 'File hanya bisa diganti jika status laporan DIKIRIM atau DITOLAK.');
        }

        // Get uploaded file
        $uploadedFile = $request->file('file');

        // Delete old file if exists
        if ($report->filename) {
            $oldStoragePath = "satker_ckh/{$report->user_id}/{$report->filename}";
            if (Storage::disk('public')->exists($oldStoragePath)) {
                Storage::disk('public')->delete($oldStoragePath);
            }
        }

        // Generate new filename
        $filename = sprintf('%s.kinerja-%s.pdf', $user->id, $report->bulan);
        $storagePath = "satker_ckh/{$user->id}/{$filename}";

        // Store new file
        Storage::disk('public')->put($storagePath, file_get_contents($uploadedFile->getRealPath()));

        // Update record - set status to DIKIRIM
        DB::table('satker_ckh')
            ->where('id', $reportId)
            ->update([
                'filename' => $filename,
                'status' => 'DIKIRIM',
                'alasan' => null,
                'sending' => now(),
                'updated_at' => now(),
            ]);

        return redirect()
            ->back()
            ->with('success', 'File berhasil diganti. Status laporan diubah menjadi DIKIRIM.');
    }

    public function uploadLaporanKinerjaManual(Request $request)
    {
        $user = $request->user();

        abort_unless($user, 403);

        $validator = Validator::make($request->all(), [
            'bulan' => ['required', 'string', 'regex:/^\d{4}-\d{2}$/'],
            'file' => ['required', 'file', 'mimes:pdf', 'max:10240'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();

        // Parse bulan to get the date
        $bulanDate = Carbon::createFromFormat('Y-m', $data['bulan'])->startOfMonth();

        // Check if report already exists and is DISETUJUI (approved)
        $existingReport = DB::table('satker_ckh')
            ->where('user_id', $user->id)
            ->where('bulan', $bulanDate->toDateString())
            ->where('status', 'DISETUJUI')
            ->first();

        if ($existingReport) {
            return back()->with('error', 'Tidak dapat mengupload laporan. Laporan sudah DISETUJUI oleh atasan.');
        }

        // Get uploaded file
        $uploadedFile = $request->file('file');

        // Delete old file if exists
        $oldReport = DB::table('satker_ckh')
            ->where('user_id', $user->id)
            ->where('bulan', $bulanDate->toDateString())
            ->first();

        if ($oldReport && $oldReport->filename) {
            $oldStoragePath = "satker_ckh/{$user->id}/{$oldReport->filename}";
            if (Storage::disk('public')->exists($oldStoragePath)) {
                Storage::disk('public')->delete($oldStoragePath);
            }
        }

        // Generate filename
        $filename = sprintf('%s.kinerja-%s.pdf', $user->id, $bulanDate->format('m-Y'));
        $storagePath = "satker_ckh/{$user->id}/{$filename}";

        // Store file
        Storage::disk('public')->put($storagePath, file_get_contents($uploadedFile->getRealPath()));

        // Update or insert record
        $reportData = [
            'item_id' => 1,
            'dept_id' => $user->dept_id,
            'user_id' => $user->id,
            'bulan' => $bulanDate->toDateString(),
            'filename' => $filename,
            'status' => 'DIKIRIM',
            'alasan' => null,
            'petugas' => 777,
            'sending' => now(),
            'created_at' => $oldReport?->created_at ?? now(),
            'updated_at' => now(),
        ];

        DB::table('satker_ckh')->updateOrInsert(
            [
                'user_id' => $user->id,
                'bulan' => $bulanDate->toDateString(),
            ],
            $reportData
        );

        $bulanLabel = $this->indonesianMonthLabel($bulanDate);

        return redirect()
            ->back()
            ->with('success', "Laporan {$bulanLabel} berhasil diupload.");
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

        // Build JSON data structure (Option A: JSON Column)
        $jsonItems = $rows->map(fn ($row, $index) => [
            'id' => null, // null = new item
            'k' => $row['kegiatan'],
            'v' => (int) ($row['volume'] ?? 0),
            's' => $row['satuan'],
        ])->toArray();

        $jsonData = json_encode(['items' => $jsonItems], JSON_UNESCAPED_UNICODE);
        $tanggal = $data['tanggal'];
        $submittedAt = now();

        // Check if record exists for this user + date
        $existing = DB::table('satker_kegiatan')
            ->where('user_id', $user->id)
            ->whereDate('tanggal', $tanggal)
            ->first();

        if ($existing) {
            // Merge new items with existing data
            $existingData = json_decode((string) ($existing->data_json ?? '{"items":[]}'), true) ?: ['items' => []];
            $existingItems = $existingData['items'] ?? [];

            // Append new items (those with null id)
            $maxId = 0;
            foreach ($existingItems as $item) {
                if (isset($item['id']) && $item['id'] > $maxId) {
                    $maxId = $item['id'];
                }
            }

            foreach ($jsonItems as &$item) {
                if ($item['id'] === null) {
                    $item['id'] = ++$maxId;
                }
            }
            unset($item);

            $allItems = array_merge($existingItems, $jsonItems);
            $newJsonData = json_encode(['items' => $allItems], JSON_UNESCAPED_UNICODE);

            // Update existing row with merged data
            DB::table('satker_kegiatan')
                ->where('id', $existing->id)
                ->update([
                    'data_json' => $newJsonData,
                    'updated_at' => $submittedAt,
                ]);
        } else {
            // Insert new row with JSON data
            // Re-assign IDs starting from 1
            $jsonItems = array_map(function ($item, $index) {
                $item['id'] = $index + 1;
                return $item;
            }, $jsonItems, array_keys($jsonItems));

            DB::table('satker_kegiatan')->insert([
                'user_id' => $user->id,
                'tanggal' => $tanggal,
                'kegiatan' => $rows->first()['kegiatan'], // Keep first for compatibility
                'volume' => $rows->first()['volume'] ?? 0,
                'satuan' => $rows->first()['satuan'] ?? 'Kegiatan',
                'staff_id' => $user->id,
                'data_json' => json_encode(['items' => $jsonItems], JSON_UNESCAPED_UNICODE),
                'created_at' => $submittedAt,
                'updated_at' => $submittedAt,
            ]);
        }

        return redirect()
            ->route('laporan-kinerja', [
                'tab' => 'harian',
                'month' => Carbon::parse($tanggal)->format('Y-m'),
            ])
            ->with('success', 'Kegiatan harian berhasil ditambahkan.');
    }
    public function storeHumas(Request $request)
    {
        $user = $request->user();
        abort_unless($user, 403);

        $validated = $request->validate([
            'bulan' => ['required', 'date_format:Y-m'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        // Process platform data from request
        $platforms = ['facebook', 'instagram', 'tiktok', 'website', 'youtube'];
        $platformData = [];

        foreach ($platforms as $platform) {
            $platformData[$platform] = [
                'first' => [
                    'date' => $request->input("{$platform}.first.date") ?: '',
                    'content' => $request->input("{$platform}.first.content") ?: '',
                    'link' => $request->input("{$platform}.first.link") ?: '',
                ],
                'last' => [
                    'date' => $request->input("{$platform}.last.date") ?: '',
                    'content' => $request->input("{$platform}.last.content") ?: '',
                    'link' => $request->input("{$platform}.last.link") ?: '',
                ],
            ];
        }

        // Check if record already exists for this month
        $existing = DB::table('laporan_humas')
            ->where('user_id', $user->id)
            ->where('bulan', $validated['bulan'] . '-01')
            ->first();

        if ($existing) {
            // Update existing record
            DB::table('laporan_humas')
                ->where('id', $existing->id)
                ->update([
                    'data' => json_encode($platformData),
                    'user_komen' => $validated['comment'] ?? '',
                    'updated_at' => now(),
                ]);
        } else {
            // Insert new record
            DB::table('laporan_humas')->insert([
                'user_id' => $user->id,
                'dept_id' => $user->dept_id ?? null,
                'bulan' => $validated['bulan'] . '-01',
                'data' => json_encode($platformData),
                'user_komen' => $validated['comment'] ?? '',
                'status' => 'tersimpan',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return redirect()
            ->route('laporan-kinerja', ['tab' => 'humas'])
            ->with('success', 'Laporan humas berhasil disimpan.');
    }

    public function destroyHumas(Request $request, int $id)
    {
        $user = $request->user();
        abort_unless($user, 403);

        $report = DB::table('laporan_humas')
            ->where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        abort_unless($report, 404);

        DB::table('laporan_humas')->where('id', $id)->delete();

        return redirect()
            ->route('laporan-kinerja', ['tab' => 'humas'])
            ->with('success', 'Laporan humas berhasil dihapus.');
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

        $tanggal = $data['tanggal'];
        $submittedAt = now();

        // Build JSON data structure
        $jsonItems = [];
        $itemId = 1;
        foreach ($rows as $row) {
            $jsonItems[] = [
                'id' => $itemId++,
                'k' => $row['kegiatan'],
                'v' => (int) ($row['volume'] ?? 0),
                's' => $row['satuan'],
            ];
        }

        $jsonData = json_encode(['items' => $jsonItems], JSON_UNESCAPED_UNICODE);

        // Find existing record
        $existing = DB::table('satker_kegiatan')
            ->where('user_id', $user->id)
            ->whereDate('tanggal', $tanggal)
            ->first();

        if ($existing) {
            // Update existing row with new JSON data
            DB::table('satker_kegiatan')
                ->where('id', $existing->id)
                ->update([
                    'kegiatan' => $rows->first()['kegiatan'],
                    'volume' => $rows->first()['volume'] ?? 0,
                    'satuan' => $rows->first()['satuan'] ?? 'Kegiatan',
                    'data_json' => $jsonData,
                    'updated_at' => $submittedAt,
                ]);
        }

        return redirect()
            ->route('laporan-kinerja', [
                'tab' => 'harian',
                'month' => Carbon::parse($tanggal)->format('Y-m'),
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

    /**
     * Verify (approve/reject) laporan kinerja from bawahan.
     */
    public function verifyLaporanKinerja(Request $request)
    {
        $user = $request->user();
        abort_unless($user, 403);

        // Custom validation messages
        $messages = [
            'user_id.required' => 'User ID diperlukan.',
            'bulan.required' => 'Bulan diperlukan.',
            'action.required' => 'Action diperlukan.',
            'action.in' => 'Action tidak valid.',
        ];

        $validator = Validator::make($request->all(), [
            'user_id' => ['required', 'integer'],
            'bulan' => ['required', 'string'],
            'action' => ['required', 'in:approve,reject'],
            'alasan' => ['nullable', 'string', 'max:500'],
        ], $messages);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $data = $validator->validated();

        // Verify user is kepala/kasi/kasubbag and has same dept_id
        $validJabatan = ['kepala', 'kasi', 'kasubbag'];
        $atasanRole = in_array(strtolower($user->kat_jabatan ?? ''), $validJabatan);

        if (!$atasanRole) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki hak untuk memverifikasi laporan.',
            ], 403);
        }

        // Find the report by user_id and bulan
        // Convert Y-m to Y-m-01 format for database query
        $bulanDate = Carbon::createFromFormat('Y-m', $data['bulan'])->startOfMonth()->toDateString();

        $report = DB::table('satker_ckh')
            ->where('user_id', $data['user_id'])
            ->where('bulan', $bulanDate)
            ->first();

        if (!$report) {
            return response()->json([
                'success' => false,
                'message' => 'Laporan tidak ditemukan.',
            ], 404);
        }

        // Update status
        $newStatus = $data['action'] === 'approve' ? 'DISETUJUI' : 'DITOLAK';

        try {
            // Get report info before update
            $reportInfo = DB::table('satker_ckh')
                ->where('id', $report->id)
                ->first();

            // Get bawahan user info
            $bawahanUser = DB::table('users')
                ->where('id', $data['user_id'])
                ->first();

            // Check if signature is enabled by atasan
            $signature = DB::table('user_signatures')
                ->where('user_id', $user->id)
                ->where('is_active', true)
                ->first();

            // Update status
            DB::table('satker_ckh')
                ->where('id', $report->id)
                ->update([
                    'status' => $newStatus,
                    'alasan' => $data['action'] === 'reject' ? ($data['alasan'] ?? null) : null,
                    'updated_at' => now(),
                ]);

            // If approved and signature is active, regenerate PDF
            if ($newStatus === 'DISETUJUI' && $signature) {
                // Get period label
                $bulanDate = Carbon::createFromFormat('Y-m-d', $reportInfo->bulan);
                $periodLabel = $bulanDate->translatedFormat('F Y');

                // Generate PDF with signature
                $pdfBinary = $this->generateApprovedPdf($reportInfo, $user, $periodLabel);

                // Save new PDF with approved suffix
                $filename = sprintf('%s.kinerja-%s-DISETUJUI.pdf', $data['user_id'], $bulanDate->format('m-Y'));
                $storagePath = "satker_ckh/{$data['user_id']}/{$filename}";

                Storage::disk('public')->put($storagePath, $pdfBinary);

                // Update filename in database
                DB::table('satker_ckh')
                    ->where('id', $report->id)
                    ->update(['filename' => $filename]);
            }

            // Log activity (optional - skip if table doesn't exist)
            try {
                DB::table('activities')->insert([
                    'user_id' => $user->id,
                    'activity_type' => 'verifikasi_laporan',
                    'description' => "Laporan kinerja {$data['bulan']} " . ($data['action'] === 'approve' ? 'disetujui' : 'ditolak') . " oleh atasan",
                    'ref_id' => $report->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } catch (\Exception $e) {
                // Skip logging if table doesn't exist
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal update status: ' . $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => $data['action'] === 'approve'
                ? 'Laporan berhasil disetujui.'
                : 'Laporan berhasil ditolak.',
            'new_status' => $newStatus,
        ]);
    }

    /**
     * Get user signature settings.
     */
    public function getSignature(Request $request)
    {
        $user = $request->user();

        $signature = DB::table('user_signatures')
            ->where('user_id', $user->id)
            ->first();

        // Default values from users table
        $defaultName = $user->name ?? '';
        $defaultNip = $user->nomor_induk ?? '';

        if ($signature) {
            // Use saved values, fallback to user defaults if empty
            $signature->signature_name = $signature->signature_name ?: $defaultName;
            $signature->nip = $signature->nip ?: ($defaultNip ? 'NIP. ' . $defaultNip : '');
        } else {
            // No signature exists, use user defaults
            $signature = (object) [
                'signature_name' => $defaultName,
                'signature_image' => '',
                'nip' => $defaultNip ? 'NIP. ' . $defaultNip : '',
                'is_active' => false,
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $signature,
        ]);
    }

    /**
     * Save user signature settings.
     */
    public function saveSignature(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'signature_name' => ['nullable', 'string', 'max:255'],
            'signature_image' => ['nullable', 'string'],
            'nip' => ['nullable', 'string', 'max:50'],
            'is_active' => ['nullable', 'string'],
        ]);

        $data = $request->only(['signature_name', 'signature_image', 'nip']);
        $data['user_id'] = $user->id;

        // Convert is_active: '1' = true, anything else = false
        $isActiveRaw = $request->input('is_active', '0');
        $data['is_active'] = ($isActiveRaw === '1') ? true : false;

        // Format NIP with "NIP. " prefix if not empty and doesn't have it
        if (!empty($data['nip']) && !str_starts_with($data['nip'], 'NIP. ')) {
            $data['nip'] = 'NIP. ' . $data['nip'];
        }

        // If signature_image is base64 data, save to file
        if (!empty($request->input('signature_image')) && str_starts_with($request->input('signature_image'), 'data:image')) {
            $imageData = $request->input('signature_image');
            $image = str_replace('data:image/png;base64,', '', $imageData);
            $image = str_replace(' ', '+', $image);
            $decoded = base64_decode($image);

            $filename = 'signatures/' . $user->id . '_' . time() . '.png';
            Storage::disk('public')->put($filename, $decoded);
            $data['signature_image'] = '/storage/' . $filename;
        }

        // Update or insert signature
        $existing = DB::table('user_signatures')
            ->where('user_id', $user->id)
            ->first();

        if ($existing) {
            DB::table('user_signatures')
                ->where('user_id', $user->id)
                ->update($data);
        } else {
            DB::table('user_signatures')->insert($data);
        }

        return response()->json([
            'success' => true,
            'message' => 'Tanda tangan berhasil disimpan.',
            'is_active' => $data['is_active'],
        ]);
    }

    /**
     * Upload signature image.
     */
    public function uploadSignatureImage(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'signature_image' => ['required', 'image', 'max:2048'],
        ]);

        $file = $request->file('signature_image');
        $path = $file->storeAs(
            'signatures',
            $user->id . '_' . time() . '.' . $file->getClientOriginalExtension(),
            'public'
        );

        return response()->json([
            'success' => true,
            'path' => '/storage/' . $path,
        ]);
    }

    /**
     * Generate PDF with signature for approval.
     */
    private function generateApprovedPdf($reportInfo, $atasan, $periodLabel)
    {
        // Get signature if active
        $signature = DB::table('user_signatures')
            ->where('user_id', $atasan->id)
            ->where('is_active', true)
            ->first();

        // Get bawahan user data
        $bawahanUser = DB::table('users')
            ->where('id', $reportInfo->user_id)
            ->first();

        // Get unit name
        $unitName = DB::table('ktd_department')
            ->where('id', $bawahanUser->dept_id ?? $reportInfo->dept_id)
            ->value('nama') ?: '-';

        // Get activities
        $monthStart = Carbon::parse($reportInfo->bulan)->startOfMonth();
        $monthEnd = $monthStart->copy()->endOfMonth();

        $dailyEntries = DB::table('satker_kegiatan')
            ->where('user_id', $reportInfo->user_id)
            ->whereBetween('tanggal', [$monthStart->toDateString(), $monthEnd->toDateString()])
            ->orderBy('tanggal')
            ->orderBy('created_at')
            ->get();

        $dailyGroups = $dailyEntries
            ->groupBy(fn ($row) => Carbon::parse($row->tanggal)->toDateString())
            ->map(function ($items, $date) {
                $dateCarbon = Carbon::parse($date);
                $allItems = [];

                foreach ($items as $item) {
                    $jsonData = json_decode((string) ($item->data_json ?? '{"items":[]}'), true) ?: ['items' => []];
                    $itemsArr = $jsonData['items'] ?? [];

                    if (empty($itemsArr) && ! empty($item->kegiatan)) {
                        $itemsArr = [[
                            'k' => $item->kegiatan,
                            'v' => $item->volume ?? 0,
                            's' => $item->satuan ?? 'Kegiatan',
                        ]];
                    }

                    foreach ($itemsArr as $it) {
                        $volume = (int) ($it['v'] ?? ($it['volume'] ?? 0));
                        $unit = trim((string) ($it['s'] ?? ($it['satuan'] ?? 'Kegiatan')));

                        $allItems[] = [
                            'kegiatan' => trim((string) ($it['k'] ?? ($it['kegiatan'] ?? ''))),
                            'volume' => $volume,
                            'satuan' => $unit,
                            'meta' => $volume > 0 ? trim($volume . ' ' . $unit) : $unit,
                        ];
                    }
                }

                return [
                    'date' => $dateCarbon->toDateString(),
                    'label' => $this->indonesianDateLabel($dateCarbon),
                    'items' => $allItems,
                ];
            })
            ->values()
            ->all();

        // Determine signature data - use signature data if exists, otherwise use atasan data
        $signatureName = $signature->signature_name ?? $atasan->name;
        $signatureNip = $signature->nip ?? ($atasan->nomor_induk ? 'NIP. ' . $atasan->nomor_induk : '');

        // Process signature image - convert storage path to proper URL/path for DomPDF
        $signatureImage = null;
        if (!empty($signature->signature_image)) {
            $imagePath = $signature->signature_image;
            // If it's a storage path, convert to absolute path
            if (str_starts_with($imagePath, '/storage/')) {
                $fullPath = public_path(ltrim($imagePath, '/'));
                if (file_exists($fullPath)) {
                    // Use absolute file path for DomPDF
                    $signatureImage = $fullPath;
                }
            } elseif (file_exists($imagePath)) {
                $signatureImage = $imagePath;
            }
        }

        // Check PLT
        $deptId = $bawahanUser->dept_id ?? $reportInfo->dept_id;
        $pltPlh = DB::table('plt_plh')
            ->where('dept_id_plh', $deptId)
            ->first();

        if ($pltPlh) {
            $pltUser = DB::table('users')->where('id', $pltPlh->user_id)->first();
            if ($pltUser) {
                $signatureName = $signature->signature_name ?? $pltUser->name;
                $signatureNip = $signature->nip ?? ($pltUser->nomor_induk ? 'NIP. ' . $pltUser->nomor_induk : '');
            }
        }

        $pdfData = [
            'userName' => $bawahanUser->name ?? '-',
            'userNip' => $bawahanUser->nomor_induk ?? '-',
            'unitName' => $unitName,
            'positionName' => $bawahanUser->pekerjaan ?? '-',
            'periodLabel' => $periodLabel,
            'dailyGroups' => $dailyGroups,
            'headerImage' => $this->assetToDataUri(public_path('assets/img/template/header.png')),
            'generatedAt' => now()->translatedFormat('d F Y H:i'),
            'signatureName' => $signatureName,
            'signatureNip' => $signatureNip,
            'signatureImage' => $signatureImage,
            'signatureLabel' => $pltPlh ? 'Mengetahui<br>PLT Kepala,' : 'Mengetahui<br>Kepala,',
        ];

        $pdf = Pdf::loadView('pdf.laporan-kinerja-harian', $pdfData)
            ->setPaper('a4', 'portrait')
            ->setOption('isRemoteEnabled', true)
            ->setOption('isHtml5ParserEnabled', true);

        return $pdf->output();
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

        $peoplePaginator = $this->departmentPeople($departmentRow->id, $leader?->id);
        $peopleData = $peoplePaginator->items();
        $kaurs = [];
        $others = [];

        foreach ($peopleData as $person) {
            if ($person['is_kaur'] ?? false) {
                $kaurs[] = $person;
            } else {
                $others[] = $person;
            }
        }

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
            'kaurs' => $kaurs,
            'people' => $peoplePaginator,
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
                $activeHead = $head ?? $pltHead;

                $employeeCount = DB::table('users')
                    ->where('dept_id', $item->id)
                    ->count();

                // Build head photo path
                $headPhotoPath = null;
                if ($activeHead && ! empty($activeHead->pp) && ! empty($activeHead->nomor_induk)) {
                    $headPhotoPath = asset("assets/img/users/{$activeHead->nomor_induk}/{$activeHead->pp}");
                }

                return [
                    'id' => $item->id,
                    'title' => $item->nama,
                    'subtitle' => $item->deskripsi ?: $item->kode,
                    'meta_label' => 'Nama Unit',
                    'meta_value' => $item->nama,
                    'extra_label' => 'Pegawai',
                    'extra_value' => $employeeCount,
                    'head_label' => $this->departmentHeadLabel($kategori, $item->nama, (bool) $pltHead),
                    'head_value' => $activeHead?->name,
                    'head_photo' => $headPhotoPath,
                    'head_initials' => $activeHead ? Str::upper(Str::substr($activeHead->name, 0, 2)) : Str::upper(Str::substr($item->nama, 0, 2)),
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
                'description' => 'Atur appointment dengan pegawai/unit kerja.',
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

    private function indonesianDateTimeFormat(?string $dateTime): string
    {
        if (!$dateTime) {
            return '-';
        }

        try {
            $date = Carbon::parse($dateTime);
            $days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            $months = [1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'];

            return sprintf(
                '%d %s %d, %02d:%02d',
                $date->day,
                $months[$date->month] ?? $date->format('F'),
                $date->year,
                $date->hour,
                $date->minute
            );
        } catch (\Exception $e) {
            return '-';
        }
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

    private function serviceIconPath(int $serviceId): string
    {
        // First check if specific icon exists for this service
        $specificIconPath = public_path("assets/img/ikon/{$serviceId}.png");
        if (file_exists($specificIconPath)) {
            return asset("assets/img/ikon/{$serviceId}.png");
        }

        // Fallback to random icon
        $icons = [
            'humas.png',
            'presensi.png',
            'RekapPresensi.png',
            'LaporanKinerja.png',
            'FileUploaded.png',
            'tukin.png',
            'uangmakan.png',
            'logohalal.png',
            '777.png',
            '508.png',
            '888.png',
            '507.png',
        ];

        $icon = $icons[$serviceId % count($icons)] ?? 'FileUploaded.png';

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
                $activeHead = $head ?? $pltHead;
                $employeeCount = DB::table('users')
                    ->where('dept_id', $item->id)
                    ->count();

                // Build head photo path
                $headPhotoPath = null;
                if ($activeHead && ! empty($activeHead->pp) && ! empty($activeHead->nomor_induk)) {
                    $headPhotoPath = asset("assets/img/users/{$activeHead->nomor_induk}/{$activeHead->pp}");
                }

                return [
                    'id' => $item->id,
                    'title' => $item->nama,
                    'subtitle' => $item->deskripsi ?: $item->kode,
                    'meta_label' => 'Nama Unit',
                    'meta_value' => $item->nama,
                    'extra_label' => 'Pegawai',
                    'extra_value' => $employeeCount,
                    'head_label' => $this->departmentHeadLabel($kategori, $item->nama, (bool) $pltHead),
                    'head_value' => $activeHead?->name,
                    'head_photo' => $headPhotoPath,
                    'head_initials' => $activeHead ? Str::upper(Str::substr($activeHead->name, 0, 2)) : Str::upper(Str::substr($item->nama, 0, 2)),
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
            // Build user photo path
            $userPhotoPath = null;
            if (! empty($item->pp) && ! empty($item->nomor_induk)) {
                $userPhotoPath = asset("assets/img/users/{$item->nomor_induk}/{$item->pp}");
            }

            return [
                'id' => $item->id,
                'title' => $item->name,
                'subtitle' => 'PP',
                'meta_label' => 'Nomor Induk',
                'meta_value' => $item->nomor_induk,
                'extra_label' => 'Satker',
                'extra_value' => $item->satker ?: '-',
                'head_label' => 'Pegawai',
                'head_value' => $item->name,
                'head_photo' => $userPhotoPath,
                'head_initials' => Str::upper(Str::substr($item->name, 0, 2)),
                'cover' => 'PP',
                'cover_path' => $userPhotoPath,
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
            ->whereNotIn('role', ['other', 'pensiun', 'pindah'])
            ->when($excludeUserId, fn ($builder) => $builder->where('id', '!=', $excludeUserId))
            ->orderBy('id');

        $paginator = $query->paginate(8, ['*'], $pageName);
        $paginator->withPath(route('unit-kerja.detail', $deptId));

        $paginator->setCollection($paginator->getCollection()->map(function ($item) use ($deptId) {
            $isKaur = strtolower($item->kat_jabatan ?? '') === 'kaur';
            return $this->personCard($item, $this->personLabel($item), false, $isKaur);
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

    private function personCard(object $item, string $roleLabel, bool $featured = false, bool $isKaur = false): array
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
            'is_kaur' => $isKaur,
        ];
    }

    private function personLabel(object $item): string
    {
        if (! empty($item->pekerjaan)) {
            return Str::headline($item->pekerjaan);
        }

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

    /**
     * Profil Kantor page.
     */

    /**
     * Sejarah page.
     */
    public function sejarah()
    {
        return view('pages.sejarah');
    }

    /**
     * Struktur Organisasi page.
     */
    public function strukturOrganisasi()
    {
        return view('pages.struktur-organisasi');
    }

    /**
     * Profil Madrasah page.
     */
    public function profilMadrasah()
    {
        $user = auth()->user();
        $formData = [
            'nama' => '',
            'nsm' => '',
            'npsm' => '',
            'status_lembaga' => '',
            'is_status_readonly' => false,
            'is_nama_readonly' => false,
            'jalan' => '',
            'jorong' => '',
            'nagari' => '',
            'kecamatan' => '',
            'koordinat' => '',
            'telepon' => '',
            'email' => '',
            'website' => '',
            'waktu_belajar' => '',
            'visi' => '',
            'sk_pendirian' => '',
            'tanggal_sk' => '',
            'komite_lembaga' => '',
            'akreditasi' => '',
            'tanggal_akreditasi' => '',
            'status_kkm' => '',
            'jarak_pusat_provinsi' => '',
            'jarak_pusat_kabupaten' => '',
            'jarak_kecamatan' => '',
            'jarak_kanwil_kemenag' => '',
            'jarak_kemenag_kab' => '',
            'jarak_kua' => '',
            'jarak_ra_terdekat' => '',
            'jarak_mi_terdekat' => '',
            'jarak_mts_terdekat' => '',
            'jarak_ma_terdekat' => '',
            'jarak_pontren_terdekat' => '',
            'jarak_tk_terdekat' => '',
            'jarak_sd_terdekat' => '',
            'jarak_smp_terdekat' => '',
            'jarak_sma_terdekat' => '',
        ];

        // Get department data based on user's dept_id
        if ($user && $user->dept_id) {
            $dept = DB::table('ktd_department')->where('id', $user->dept_id)->first();

            if ($dept) {
                // Nama Madrasah selalu read-only (dari ktd_department)
                $formData['is_nama_readonly'] = true;
                $formData['nama'] = $dept->nama ?? '';

                // Map ktd_department columns to form fields
                $formData['status_lembaga'] = $dept->status ?? '';
                $formData['jarak_kecamatan'] = $dept->jarak_kecamatan ?? '';
                $formData['jarak_kua'] = $dept->jarak_kua ?? '';
                $formData['jarak_kemenag_kab'] = $dept->jarak_kemenag_kab ?? '';
                $formData['jarak_kanwil_kemenag'] = $dept->jarak_kanwil_kemenag ?? '';

                // Handle alamat field (might contain multiple parts)
                if (!empty($dept->alamat)) {
                    $alamatParts = explode(',', $dept->alamat);
                    $formData['jalan'] = trim($alamatParts[0] ?? '');
                    $formData['jorong'] = trim($alamatParts[1] ?? '');
                    $formData['nagari'] = trim($alamatParts[2] ?? '');
                    $formData['kecamatan'] = trim($alamatParts[3] ?? '');
                }

                // Map telepon if available
                if (!empty($dept->telepon)) {
                    $formData['telepon'] = $dept->telepon;
                }

                // Map email if available
                if (!empty($dept->email)) {
                    $formData['email'] = $dept->email;
                }

                // Map website if available
                if (!empty($dept->website)) {
                    $formData['website'] = $dept->website;
                }

                // Map koordinat if available
                if (!empty($dept->koordinat)) {
                    $formData['koordinat'] = $dept->koordinat;
                }

                // Map akreditasi if available
                if (!empty($dept->akreditasi)) {
                    $formData['akreditasi'] = $dept->akreditasi;
                }

                // Map waktu_belajar if available
                if (!empty($dept->waktu_belajar)) {
                    $formData['waktu_belajar'] = $dept->waktu_belajar;
                }

                // Map nsm if available
                if (!empty($dept->nsm)) {
                    $formData['nsm'] = $dept->nsm;
                }

                // Map npsm if available
                if (!empty($dept->npsm)) {
                    $formData['npsm'] = $dept->npsm;
                }

                // Check kategori: jika MAN/MIN/MTSN, status lembaga = NEGERI dan read-only
                $kategoriLower = strtolower($dept->kategori ?? '');
                if (in_array($kategoriLower, ['man', 'min', 'mtsn'])) {
                    $formData['status_lembaga'] = 'NEGERI';
                    $formData['is_status_readonly'] = true;
                }
            }
        }

        return view('madrasah.profilmadrasah', [
            'formData' => $formData,
        ]);
    }

    /**
     * Pegawai Madrasah page - daftar pegawai berdasarkan dept_id user.
     */
    public function pegawaiMadrasah(Request $request)
    {
        $user = auth()->user();
        $deptId = $user->dept_id ?? null;
        $deptName = 'Madrasah';
        $isMadrasahType = false;

        // Get department info
        if ($deptId) {
            $dept = DB::table('ktd_department')->where('id', $deptId)->first();
            if ($dept) {
                $deptName = $dept->nama ?? 'Madrasah';
                $kategoriLower = strtolower($dept->kategori ?? '');
                $isMadrasahType = in_array($kategoriLower, ['man', 'min', 'mtsn', 'ra']);
            }
        }

        // Get users based on dept_id
        $usersQuery = DB::table('users')
            ->where('dept_id', $deptId)
            ->whereNotIn('role', ['other', 'pensiun', 'pindah'])
            ->select([
                'id',
                'name',
                'nomor_induk',
                'kat_jabatan',
                'pekerjaan',
                'satker',
                'email',
                'telp',
                'jk',
                'pp',
                'status',
                'npwp',
                'tempat_lahir',
                'tanggal_lahir',
                'alamat',
                'tmt_cpns',
                'tmt_pns',
                'tmt_tugas',
                'kgb',
                'asn',
                'gol',
                'jabatan',
                'masa_kerja_tahun',
                'masa_kerja_bulan',
                'ijazah_jurusan',
                'ijazah_fakultas',
                'ijazah_universitas',
                'ijazah_pendidikan',
                'ijazah_tahun_lulus',
            ])
            ->orderByRaw("FIELD(kat_jabatan, 'kepala', 'kasubag', 'kasi', 'kaur', 'pelaksana', 'staf', 'honorer', 'guru', '')")
            ->orderBy('name');

        $pegawaiList = $usersQuery->paginate(15);

        // Add profile photo URLs
        $pegawaiList->getCollection()->transform(function ($item) {
            if ($item->pp && $item->nomor_induk) {
                $item->photo_url = asset('assets/img/users/' . $item->nomor_induk . '/' . $item->pp);
            } else {
                $item->photo_url = null;
            }
            $item->initials = $item->name ? strtoupper(substr($item->name, 0, 2)) : 'NA';
            return $item;
        });

        // Summary stats - ASN: cpns/pns/pppk, Non ASN: Honor/GTT/PTT/dll
        $stats = [
            'total' => $pegawaiList->total(),
            'asn' => DB::table('users')->where('dept_id', $deptId)->whereIn('asn', ['cpns', 'pns', 'pppk'])->count(),
            'honorer' => DB::table('users')->where('dept_id', $deptId)->whereNotIn('asn', ['cpns', 'pns', 'pppk'])->count(),
        ];

        return view('madrasah.pegawaimadrasah', [
            'pegawaiList' => $pegawaiList,
            'deptName' => $deptName,
            'isMadrasahType' => $isMadrasahType,
            'stats' => $stats,
        ]);
    }

    /**
     * Guru Madrasah page - daftar guru berdasarkan dept_id user.
     */
    public function guruMadrasah(Request $request)
    {
        $user = auth()->user();
        $deptId = $user->dept_id ?? null;
        $deptName = 'Madrasah';

        // Get department info
        if ($deptId) {
            $dept = DB::table('ktd_department')->where('id', $deptId)->first();
            if ($dept) {
                $deptName = $dept->nama ?? 'Madrasah';
            }
        }

        // Get only guru based on dept_id
        $guruQuery = DB::table('users')
            ->where('dept_id', $deptId)
            ->where('kat_jabatan', 'guru')
            ->whereNotIn('role', ['other', 'pensiun', 'pindah'])
            ->select([
                'id',
                'name',
                'nomor_induk',
                'kat_jabatan',
                'pekerjaan',
                'satker',
                'email',
                'telp',
                'jk',
                'pp',
                'status',
                'npwp',
                'tempat_lahir',
                'tanggal_lahir',
                'alamat',
                'jabatan',
                'nuptk',
                'nrg',
                'serdik',
                'bidang_studi_diajar',
            ])
            ->orderBy('name');

        $guruList = $guruQuery->paginate(15);

        // Add profile photo URLs
        $guruList->getCollection()->transform(function ($item) {
            if ($item->pp && $item->nomor_induk) {
                $item->photo_url = asset('assets/img/users/' . $item->nomor_induk . '/' . $item->pp);
            } else {
                $item->photo_url = null;
            }
            $item->initials = $item->name ? strtoupper(substr($item->name, 0, 2)) : 'NA';
            return $item;
        });

        // Summary stats - serdik: sertifikasi / non-sertifikasi / non-guru / unknown
        $stats = [
            'total' => $guruList->total(),
            'sertifikasi' => DB::table('users')->where('dept_id', $deptId)->where('kat_jabatan', 'guru')->where('serdik', 'sertifikasi')->count(),
            'belum_sertifikasi' => DB::table('users')->where('dept_id', $deptId)->where('kat_jabatan', 'guru')->whereIn('serdik', ['non-sertifikasi', 'non-guru', 'unknown'])->count(),
        ];

        return view('madrasah.gurumadrasah', [
            'guruList' => $guruList,
            'deptName' => $deptName,
            'stats' => $stats,
        ]);
    }

    /**
     * Laporan Semester Madrasah page.
     */
    public function laporanSemesterMadrasah(Request $request)
    {
        $user = auth()->user();
        $deptId = $user->dept_id ?? null;
        $deptName = 'Madrasah';

        // Get department info
        if ($deptId) {
            $dept = DB::table('ktd_department')->where('id', $deptId)->first();
            if ($dept) {
                $deptName = $dept->nama ?? 'Madrasah';
            }
        }

        // Get selected semester and tahun ajaran
        $selectedSemester = $request->input('semester', 'genap');
        $tahunAjaran = $request->input('tahun_ajaran', $this->getDefaultAcademicYear());

        // Get existing report if any
        $existingReport = null;
        if ($deptId) {
            $existingReport = DB::table('ktd_laporan_semester_madrasah')
                ->where('dept_id', $deptId)
                ->where('semester', $selectedSemester)
                ->where('tahun_ajaran', $tahunAjaran)
                ->first();
        }

        // Generate academic year options
        $academicYearOptions = $this->generateAcademicYearOptions();

        // Default form data structure
        $formData = [
            'keadaanGedung' => $existingReport ? json_decode($existingReport->keadaan_gedung ?? '{}', true) : $this->getDefaultKeadaanGedung(),
            'saranaPendidikan' => $existingReport ? json_decode($existingReport->sarana_pendidikan ?? '{}', true) : $this->getDefaultSaranaPendidikan(),
            'bantuanPemerintah' => $existingReport ? json_decode($existingReport->bantuan_pemerintah ?? '{}', true) : $this->getDefaultBantuanPemerintah(),
            'bantuanNonPemerintah' => $existingReport ? json_decode($existingReport->bantuan_non_pemerintah ?? '{}', true) : $this->getDefaultBantuanNonPemerintah(),
            'dataGuruPegawai' => $existingReport ? json_decode($existingReport->data_guru_pegawai ?? '{}', true) : $this->getDefaultDataGuruPegawai(),
            'tingkatPendidikan' => $existingReport ? json_decode($existingReport->tingkat_pendidikan ?? '{}', true) : $this->getDefaultTingkatPendidikan(),
            'sertifikasi' => $existingReport ? json_decode($existingReport->sertifikasi ?? '{}', true) : $this->getDefaultSertifikasi(),
            'banyakHariSekolah' => $existingReport?->banyak_hari_sekolah ?? 0,
            'absensiSiswa' => $existingReport ? json_decode($existingReport->absensi_siswa ?? '{}', true) : $this->getDefaultAbsensiSiswa(),
            'luasTanah' => $existingReport ? json_decode($existingReport->luas_tanah ?? '{}', true) : $this->getDefaultLuasTanah(),
            'sertifikatTanah' => $existingReport ? json_decode($existingReport->sertifikat_tanah ?? '{}', true) : $this->getDefaultSertifikatTanah(),
        ];

        $reportStatus = $existingReport?->status ?? 'draft';
        $submittedAt = $existingReport?->submitted_at;
        $catatanAdmin = $existingReport?->catatan_admin;

        return view('madrasah.laporansemester', [
            'deptName' => $deptName,
            'deptId' => $deptId,
            'selectedSemester' => $selectedSemester,
            'tahunAjaran' => $tahunAjaran,
            'academicYearOptions' => $academicYearOptions,
            'formData' => $formData,
            'reportStatus' => $reportStatus,
            'submittedAt' => $submittedAt,
            'catatanAdmin' => $catatanAdmin,
        ]);
    }

    /**
     * Get default academic year (e.g., 2025/2026).
     */
    private function getDefaultAcademicYear(): string
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;
        $startYear = $currentMonth >= 7 ? $currentYear : $currentYear - 1;
        return $startYear . '/' . ($startYear + 1);
    }

    /**
     * Generate academic year options.
     */
    private function generateAcademicYearOptions(): array
    {
        $defaultYear = $this->getDefaultAcademicYear();
        [$startYear] = explode('/', $defaultYear);
        $startYear = (int) $startYear;
        $options = [];

        for ($year = $startYear - 2; $year <= $startYear + 2; $year++) {
            $options[] = ($year) . '/' . ($year + 1);
        }

        return $options;
    }

    /**
     * Get default keadaan gedung data.
     */
    private function getDefaultKeadaanGedung(): array
    {
        $labels = [
            'Ruang Kelas', 'Ruang Kamad', 'Ruang Guru', 'Ruang TU',
            'Ruang Lab. IPA', 'Ruang Lab. Komputer', 'Ruang Perpustakaan',
            'Ruang Keterampilan', 'Ruang Seni', 'Ruang UKS', 'Aula',
            'Musholla / Ibadah', 'WC', 'Kamar Mandi', 'Kantin'
        ];

        return array_map(fn($label) => [
            'label' => $label,
            'baik' => 0,
            'ringan' => 0,
            'sedang' => 0,
            'berat' => 0,
            'isCustom' => false,
        ], $labels);
    }

    /**
     * Get default sarana pendidikan data.
     */
    private function getDefaultSaranaPendidikan(): array
    {
        $labels = [
            'Bangku Uk. 1 Siswa', 'Bangku Uk. 2 Siswa', 'Kursi Siswa',
            'Lemari', 'Rak Buku', 'Papan Tulis', 'Komputer Kantor',
            'Komputer Siswa', 'Alat Peraga', 'PKn', 'Bahasa Indonesia',
            'Matematika', 'IPA', 'IPS', 'Atlas', 'Globe'
        ];

        return array_map(fn($label) => [
            'label' => $label,
            'baik' => 0,
            'ringan' => 0,
            'sedang' => 0,
            'berat' => 0,
            'isCustom' => false,
        ], $labels);
    }

    /**
     * Get default bantuan pemerintah data.
     */
    private function getDefaultBantuanPemerintah(): array
    {
        return array_map(fn($label) => [
            'label' => $label,
            'diterima' => 0,
            'terserap' => 0,
            'isCustom' => false,
        ], ['BOS', 'BSM', 'Block Grant']);
    }

    /**
     * Get default bantuan non pemerintah data.
     */
    private function getDefaultBantuanNonPemerintah(): array
    {
        return array_map(fn($label) => [
            'label' => $label,
            'diterima' => 0,
            'terserap' => 0,
            'isCustom' => false,
        ], ['Sumbangan']);
    }

    /**
     * Get default data guru pegawai.
     */
    private function getDefaultDataGuruPegawai(): array
    {
        $labels = [
            'Kepala Madrasah', 'Wakil Kepala Madrasah', 'Guru Mapel Umum',
            'Guru Penjaskes', 'Guru Agama', 'Guru BK', 'Guru B. Inggris',
            'Ka TU', 'Staf TU', 'Bendahara', 'Personel Lainnya'
        ];

        return array_map(fn($label) => [
            'label' => $label,
            'l' => 0,
            'p' => 0,
            'isCustom' => false,
        ], $labels);
    }

    /**
     * Get default tingkat pendidikan.
     */
    private function getDefaultTingkatPendidikan(): array
    {
        $labels = ['< SLTA', 'Diploma I (D1)', 'Diploma II (D2)', 'Diploma III (D3)', 'Strata I (S1)', 'Strata II (S2)', 'Strata III (S3)'];

        return array_map(fn($label) => [
            'label' => $label,
            'l' => 0,
            'p' => 0,
            'isCustom' => false,
        ], $labels);
    }

    /**
     * Get default sertifikasi.
     */
    private function getDefaultSertifikasi(): array
    {
        $labels = ['PNS Kemenag', 'PNS Diknas', 'GTT / GTY', 'PPPK', 'PPPK Paruh Waktu', 'Belum Sertifikasi'];

        return array_map(fn($label) => [
            'label' => $label,
            'l' => 0,
            'p' => 0,
            'isCustom' => false,
        ], $labels);
    }

    /**
     * Get default absensi siswa.
     */
    private function getDefaultAbsensiSiswa(): array
    {
        $labels = ['Sakit', 'Ijin', 'Alpa / Tanpa Keterangan'];

        return array_map(fn($label) => [
            'label' => $label,
            'l' => 0,
            'p' => 0,
            'isCustom' => false,
        ], $labels);
    }

    /**
     * Get default luas tanah.
     */
    private function getDefaultLuasTanah(): array
    {
        return [
            'total' => 0,
            'perkaranganLuas' => 0,
            'perkaranganTerbangun' => 0,
            'kebun' => 0,
            'lapanganOlahraga' => 0,
            'masjidMusholla' => 0,
            'wc' => 0,
            'perpustakaan' => 0,
        ];
    }

    /**
     * Get default sertifikat tanah.
     */
    private function getDefaultSertifikatTanah(): array
    {
        return [
            'statusKepemilikan' => '',
            'nomor' => '',
            'tanggal' => '',
            'luas' => 0,
        ];
    }

    /**
     * Save Laporan Semester Madrasah.
     */
    public function saveLaporanSemesterMadrasah(Request $request)
    {
        $user = auth()->user();
        $deptId = $user->dept_id ?? $request->input('dept_id');

        if (!$deptId) {
            return response()->json(['success' => false, 'message' => 'Dept ID diperlukan'], 400);
        }

        $validated = $request->validate([
            'semester' => 'required|in:ganjil,genap',
            'tahun_ajaran' => 'required|string',
            'status' => 'required|in:draft,submitted',
        ]);

        $data = [
            'dept_id' => $deptId,
            'semester' => $validated['semester'],
            'tahun_ajaran' => $validated['tahun_ajaran'],
            'status' => $validated['status'],
            'keadaan_gedung' => json_encode($request->input('keadaanGedung', [])),
            'sarana_pendidikan' => json_encode($request->input('saranaPendidikan', [])),
            'bantuan_pemerintah' => json_encode($request->input('bantuanPemerintah', [])),
            'bantuan_non_pemerintah' => json_encode($request->input('bantuanNonPemerintah', [])),
            'data_guru_pegawai' => json_encode($request->input('dataGuruPegawai', [])),
            'tingkat_pendidikan' => json_encode($request->input('tingkatPendidikan', [])),
            'sertifikasi' => json_encode($request->input('sertifikasi', [])),
            'banyak_hari_sekolah' => $request->input('banyakHariSekolah', 0),
            'absensi_siswa' => json_encode($request->input('absensiSiswa', [])),
            'luas_tanah' => json_encode($request->input('luasTanah', [])),
            'sertifikat_tanah' => json_encode($request->input('sertifikatTanah', [])),
        ];

        if ($validated['status'] === 'submitted') {
            $data['submitted_at'] = now();
        }

        // Check if record exists
        $existing = DB::table('ktd_laporan_semester_madrasah')
            ->where('dept_id', $deptId)
            ->where('semester', $validated['semester'])
            ->where('tahun_ajaran', $validated['tahun_ajaran'])
            ->first();

        if ($existing) {
            $data['updated_at'] = now();
            DB::table('ktd_laporan_semester_madrasah')
                ->where('id', $existing->id)
                ->update($data);
            $reportId = $existing->id;
        } else {
            $data['created_at'] = now();
            $data['updated_at'] = now();
            $reportId = DB::table('ktd_laporan_semester_madrasah')->insertGetId($data);
        }

        $report = DB::table('ktd_laporan_semester_madrasah')->where('id', $reportId)->first();

        return response()->json([
            'success' => true,
            'message' => 'Laporan berhasil disimpan',
            'data' => $report,
        ]);
    }
}
