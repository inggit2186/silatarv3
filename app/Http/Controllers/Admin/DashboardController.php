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
}