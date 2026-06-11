<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class AdminAccess
{
    // Modules that only admin/superadmin can access
    private array $adminOnlyModules = [
        'users',
        'services',
        'units',
        'requests',
        'reports',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$requiredAccess): Response
    {
        $user = $request->user();

        // Check if user is authenticated
        if (!$user) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Get user's access from hak_akses table
        $hakAkses = DB::table('hak_akses')
            ->where('user_id', $user->id)
            ->first();

        if (!$hakAkses) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Forbidden - No access rights'], 403);
            }
            return redirect()->route('pelayanan')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        // Decode JSON access array
        $userAccess = json_decode($hakAkses->akses, true);

        if (!is_array($userAccess)) {
            $userAccess = [];
        }

        // Check if user is admin or superadmin
        $isAdmin = in_array('admin', $userAccess) || in_array('superadmin', $userAccess);

        // If specific access required (from route parameter)
        if (!empty($requiredAccess)) {
            $hasAccess = false;
            foreach ($requiredAccess as $access) {
                if (in_array($access, $userAccess)) {
                    $hasAccess = true;
                    break;
                }
            }
            if (!$hasAccess) {
                if ($request->expectsJson()) {
                    return response()->json(['message' => 'Forbidden - Insufficient permissions'], 403);
                }
                return redirect()->route('pelayanan')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
            }
            return $next($request);
        }

        // Auto-detect required access based on route
        $path = $request->path();
        $segments = explode('/', trim($path, '/'));

        // Skip 'admin' prefix if present
        $module = $segments[1] ?? 'dashboard';

        // Dashboard and news are allowed for all with admin access (including humas)
        $allowedForAll = ['dashboard', 'news', 'profile'];

        if (!in_array($module, $allowedForAll) && !$isAdmin) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Forbidden - Admin access required'], 403);
            }
            return redirect()->route('admin.dashboard')->with('error', 'Anda tidak memiliki akses ke menu tersebut.');
        }

        return $next($request);
    }

    /**
     * Get user access from hak_akses table.
     */
    public static function getUserAccess($userId): array
    {
        $hakAkses = DB::table('hak_akses')
            ->where('user_id', $userId)
            ->first();

        if (!$hakAkses) {
            return [];
        }

        return json_decode($hakAkses->akses, true) ?? [];
    }

    /**
     * Check if user is admin or superadmin.
     */
    public static function isAdmin($userId): bool
    {
        $access = self::getUserAccess($userId);
        return in_array('admin', $access) || in_array('superadmin', $access);
    }

    /**
     * Check if user has specific access.
     */
    public static function hasAccess($userId, string $access): bool
    {
        $accessList = self::getUserAccess($userId);
        return in_array($access, $accessList);
    }
}