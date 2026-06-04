<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        // Get statistics
        $stats = $this->getStatistics();

        // Get recent activities
        $recentActivities = $this->getRecentActivities();

        // Get monthly requests chart data
        $monthlyData = $this->getMonthlyRequestsData();

        // Get popular services
        $popularServices = $this->getPopularServices();

        // Get status distribution
        $statusDistribution = $this->getStatusDistribution();

        // Get pending requests for quick actions
        $pendingRequests = $this->getPendingRequests();

        return view('admin.dashboard', [
            'title' => 'Dashboard - SILATAR Admin',
            'breadcrumbs' => [
                ['label' => 'Dashboard', 'url' => null],
            ],
            'stats' => $stats,
            'recentActivities' => $recentActivities,
            'monthlyData' => $monthlyData,
            'popularServices' => $popularServices,
            'statusDistribution' => $statusDistribution,
            'pendingRequests' => $pendingRequests,
        ]);
    }

    /**
     * Get overall statistics for dashboard.
     */
    private function getStatistics(): array
    {
        // Total users (employees)
        $totalUsers = DB::table('users')->count();

        // New users this month
        $newUsersThisMonth = DB::table('users')
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->count();

        // Total departments
        $totalDepartments = DB::table('ktd_department')
            ->whereIn('status', [1, 2])
            ->count();

        // Total active services
        $totalServices = DB::table('ktd_layanan')
            ->where('status', 1)
            ->count();

        // Total requests
        $totalRequests = DB::table('users_request')->count();

        // Pending requests
        $pendingRequests = DB::table('users_request')
            ->whereIn('status', ['UNCHECK', 'PENDING'])
            ->count();

        // Processed this month
        $processedThisMonth = DB::table('users_request')
            ->whereIn('status', ['SUKSES', 'DITERIMA'])
            ->whereYear('updated_at', now()->year)
            ->whereMonth('updated_at', now()->month)
            ->count();

        return [
            'total_users' => $totalUsers,
            'new_users_this_month' => $newUsersThisMonth,
            'total_departments' => $totalDepartments,
            'total_services' => $totalServices,
            'total_requests' => $totalRequests,
            'pending_requests' => $pendingRequests,
            'processed_this_month' => $processedThisMonth,
        ];
    }

    /**
     * Get recent activities for dashboard.
     */
    private function getRecentActivities(): array
    {
        // Get recent requests
        $recentRequests = DB::table('users_request as ur')
            ->leftJoin('ktd_layanan as layanan', 'layanan.id', '=', 'ur.layanan_id')
            ->leftJoin('users as pemohon', 'pemohon.id', '=', 'ur.user_id')
            ->select([
                'ur.id',
                'ur.no_req',
                'ur.status',
                'ur.created_at',
                DB::raw('COALESCE(layanan.nama, ur.judul) as layanan_name'),
                DB::raw('COALESCE(pemohon.name, ur.pemohon) as user_name'),
            ])
            ->orderByDesc('ur.created_at')
            ->limit(10)
            ->get();

        return $recentRequests->map(function ($item) {
            return [
                'id' => $item->id,
                'no_req' => $item->no_req,
                'title' => $item->layanan_name,
                'user' => $item->user_name,
                'status' => $item->status,
                'created_at' => $item->created_at,
                'time_ago' => $this->getTimeAgo($item->created_at),
                'type' => 'request',
            ];
        })->toArray();
    }

    /**
     * Get monthly requests data for chart.
     */
    private function getMonthlyRequestsData(): array
    {
        $months = [];
        $counts = [];

        // Get last 6 months data
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthStart = $date->copy()->startOfMonth();
            $monthEnd = $date->copy()->endOfMonth();

            $count = DB::table('users_request')
                ->whereBetween('created_at', [$monthStart->toDateTimeString(), $monthEnd->toDateTimeString()])
                ->count();

            $months[] = $date->format('M');
            $counts[] = $count;
        }

        return [
            'labels' => $months,
            'data' => $counts,
        ];
    }

    /**
     * Get popular services.
     */
    private function getPopularServices(): array
    {
        return DB::table('users_request as ur')
            ->join('ktd_layanan as layanan', 'layanan.id', '=', 'ur.layanan_id')
            ->select([
                'layanan.id',
                'layanan.nama',
                DB::raw('COUNT(*) as request_count'),
            ])
            ->groupBy('layanan.id', 'layanan.nama')
            ->orderByDesc('request_count')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->nama,
                    'count' => $item->request_count,
                ];
            })
            ->toArray();
    }

    /**
     * Get status distribution for requests.
     */
    private function getStatusDistribution(): array
    {
        $statuses = DB::table('users_request')
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $labels = [
            'DRAFT' => 'Draft',
            'UNCHECK' => 'Belum Dicek',
            'PENDING' => 'Pending',
            'DITERIMA' => 'Diterima',
            'DIPROSES' => 'Diproses',
            'SUKSES' => 'Sukses',
            'DITOLAK' => 'Ditolak',
            'BATAL' => 'Batal',
        ];

        $colors = [
            'DRAFT' => 'slate',
            'UNCHECK' => 'amber',
            'PENDING' => 'blue',
            'DITERIMA' => 'cyan',
            'DIPROSES' => 'violet',
            'SUKSES' => 'emerald',
            'DITOLAK' => 'rose',
            'BATAL' => 'slate',
        ];

        $result = [];
        $total = array_sum($statuses);

        foreach ($labels as $key => $label) {
            $count = $statuses[$key] ?? 0;
            $percentage = $total > 0 ? round(($count / $total) * 100, 1) : 0;

            $result[] = [
                'status' => $key,
                'label' => $label,
                'count' => $count,
                'percentage' => $percentage,
                'color' => $colors[$key] ?? 'slate',
            ];
        }

        return $result;
    }

    /**
     * Get pending requests for quick actions.
     */
    private function getPendingRequests(): array
    {
        return DB::table('users_request as ur')
            ->leftJoin('ktd_layanan as layanan', 'layanan.id', '=', 'ur.layanan_id')
            ->leftJoin('users as pemohon', 'pemohon.id', '=', 'ur.user_id')
            ->select([
                'ur.id',
                'ur.no_req',
                'ur.status',
                'ur.created_at',
                DB::raw('COALESCE(layanan.nama, ur.judul) as layanan_name'),
                DB::raw('COALESCE(pemohon.name, ur.pemohon) as user_name'),
            ])
            ->whereIn('ur.status', ['UNCHECK', 'PENDING'])
            ->orderBy('ur.created_at')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'no_req' => $item->no_req,
                    'title' => $item->layanan_name,
                    'user' => $item->user_name,
                    'status' => $item->status,
                    'created_at' => $item->created_at,
                    'time_ago' => $this->getTimeAgo($item->created_at),
                ];
            })
            ->toArray();
    }

    /**
     * Convert timestamp to time ago string.
     */
    private function getTimeAgo($timestamp): string
    {
        $diff = now()->diffInMinutes($timestamp);

        if ($diff < 1) {
            return 'Baru saja';
        } elseif ($diff < 60) {
            return $diff . ' menit lalu';
        } elseif ($diff < 1440) {
            $hours = floor($diff / 60);
            return $hours . ' jam lalu';
        } elseif ($diff < 10080) {
            $days = floor($diff / 1440);
            return $days . ' hari lalu';
        } else {
            return \Carbon\Carbon::parse($timestamp)->format('d M');
        }
    }

    /**
     * Preview migration (dry run) for satker_kegiatan to JSON format.
     */
    public function migrateSatkerPreview(Request $request)
    {
        $combinations = DB::table('satker_kegiatan')
            ->select('user_id', 'tanggal')
            ->whereNull('data_json')
            ->whereNotNull('kegiatan')
            ->where('kegiatan', '!=', '')
            ->groupBy('user_id', 'tanggal')
            ->selectRaw('user_id, tanggal, COUNT(*) as count, MIN(id) as keep_id')
            ->get();

        $totalGroups = $combinations->count();
        $totalRows = $combinations->sum('count');
        $rowsToDelete = $totalRows - $totalGroups;

        $result = "PREVIEW (Dry Run) - Tidak ada perubahan dilakukan\n";
        $result .= "===============================================\n";
        $result .= "Grup tanggal yang perlu dikonversi: {$totalGroups}\n";
        $result .= "Total baris saat ini: {$totalRows}\n";
        $result .= "Baris yang akan dihapus (setelah merge): {$rowsToDelete}\n";
        $result .= "Baris yang akan dipertahankan: {$totalGroups}\n";

        if ($combinations->isNotEmpty()) {
            $result .= "\nContoh grup (5 pertama):\n";
            $examples = $combinations->take(5);
            foreach ($examples as $group) {
                $result .= "- User {$group->user_id}, Tanggal {$group->tanggal}: {$group->count} baris -> 1 baris\n";
            }
        }

        return redirect()->route('admin.dashboard')->with('migration_result', $result);
    }

    /**
     * Execute migration for satker_kegiatan to JSON format.
     */
    public function migrateSatker(Request $request)
    {
        $combinations = DB::table('satker_kegiatan')
            ->select('user_id', 'tanggal')
            ->whereNull('data_json')
            ->whereNotNull('kegiatan')
            ->where('kegiatan', '!=', '')
            ->groupBy('user_id', 'tanggal')
            ->selectRaw('user_id, tanggal, COUNT(*) as count, MIN(id) as keep_id')
            ->get();

        if ($combinations->isEmpty()) {
            $result = "Tidak ada data yang perlu dikonversi.\n";
            return redirect()->route('admin.dashboard')->with('migration_result', $result);
        }

        $totalGroups = $combinations->count();
        $converted = 0;
        $deleted = 0;
        $errors = 0;

        foreach ($combinations as $group) {
            try {
                $rows = DB::table('satker_kegiatan')
                    ->where('user_id', $group->user_id)
                    ->where('tanggal', $group->tanggal)
                    ->orderBy('id')
                    ->get();

                if ($rows->isEmpty()) {
                    continue;
                }

                // Build JSON items
                $items = [];
                $itemId = 1;

                foreach ($rows as $row) {
                    $items[] = [
                        'id' => $itemId++,
                        'k' => trim((string) $row->kegiatan),
                        'v' => (int) ($row->volume ?? 0),
                        's' => trim((string) ($row->satuan ?? 'Kegiatan')),
                    ];
                }

                $jsonData = json_encode(['items' => $items], JSON_UNESCAPED_UNICODE);

                // Update the first row with JSON data
                DB::table('satker_kegiatan')
                    ->where('id', $group->keep_id)
                    ->update([
                        'data_json' => $jsonData,
                        'updated_at' => now(),
                    ]);

                // Delete the other rows
                $deletedRows = DB::table('satker_kegiatan')
                    ->where('user_id', $group->user_id)
                    ->where('tanggal', $group->tanggal)
                    ->where('id', '!=', $group->keep_id)
                    ->delete();

                $converted++;
                $deleted += $deletedRows;
            } catch (\Exception $e) {
                $errors++;
            }
        }

        $result = "MIGRASI SELESAI\n";
        $result .= "===============================================\n";
        $result .= "Grup berhasil dikonversi: {$converted}\n";
        $result .= "Total baris dihapus: {$deleted}\n";
        if ($errors > 0) {
            $result .= "Error: {$errors}\n";
        }
        $result .= "\nData lama (per-baris) telah dikonversi ke format baru (per-tanggal JSON).";

        return redirect()->route('admin.dashboard')->with('migration_result', $result);
    }

    /**
     * Approve or reject laporan kinerja from bawahan.
     */
    public function approveLaporanKinerja(Request $request)
    {
        $request->validate([
            'report_id' => ['nullable', 'integer'],
            'user_id' => ['required', 'integer'],
            'bulan' => ['required', 'string'],
            'action' => ['required', 'in:approve,reject'],
            'alasan' => ['nullable', 'string', 'max:500'],
        ]);

        $data = $request->only(['user_id', 'bulan', 'action', 'alasan']);

        // Find the report by user_id and bulan
        $report = DB::table('satker_ckh')
            ->where('user_id', $data['user_id'])
            ->where('bulan', $data['bulan'])
            ->first();

        if (!$report) {
            return response()->json([
                'success' => false,
                'message' => 'Laporan tidak ditemukan.',
            ], 404);
        }

        // Update status
        $newStatus = $data['action'] === 'approve' ? 'DISETUJUI' : 'DITOLAK';

        DB::table('satker_ckh')
            ->where('id', $report->id)
            ->update([
                'status' => $newStatus,
                'alasan' => $data['action'] === 'reject' ? ($data['alasan'] ?? null) : null,
                'updated_at' => now(),
            ]);

        // Log activity
        $this->logActivity(
            $request->user()->id,
            'verifikasi_laporan',
            "Laporan kinerja {$data['bulan']} " . ($data['action'] === 'approve' ? 'disetujui' : 'ditolak'),
            $report->id
        );

        return response()->json([
            'success' => true,
            'message' => $data['action'] === 'approve'
                ? 'Laporan berhasil disetujui.'
                : 'Laporan berhasil ditolak.',
            'new_status' => $newStatus,
        ]);
    }

    /**
     * Log user activity.
     */
    private function logActivity($userId, $action, $description, $refId = null)
    {
        DB::table('activities')->insert([
            'user_id' => $userId,
            'activity_type' => $action,
            'description' => $description,
            'ref_id' => $refId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}