<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class AdminAccess
{
    /**
     * Roles that can access admin panel
     */
    private array $allowedRoles = ['petugas', 'kasi', 'kasubbag', 'admin', 'superadmin', 'kepala'];

    /**
     * Handle an incoming request.
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

        // Check if user's role can access admin panel
        if (!in_array($user->role, $this->allowedRoles)) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Forbidden - Admin access required'], 403);
            }
            return redirect()->route('pelayanan')->with('error', 'Anda tidak memiliki akses ke halaman admin.');
        }

        return $next($request);
    }

    /**
     * Check if user is admin or superadmin.
     */
    public static function isAdmin($userId): bool
    {
        $user = DB::table('users')->where('id', $userId)->first();
        if (!$user) return false;
        return in_array($user->role, ['admin', 'superadmin']);
    }

    /**
     * Check if user can access admin panel.
     */
    public static function canAccessAdmin($userId): bool
    {
        $user = DB::table('users')->where('id', $userId)->first();
        if (!$user) return false;
        return in_array($user->role, ['petugas', 'kasi', 'kasubbag', 'admin', 'superadmin', 'kepala']);
    }

    /**
     * Check if user has humas access (from hak_akses table).
     */
    public static function isHumas($userId): bool
    {
        $hakAkses = DB::table('hak_akses')
            ->where('user_id', $userId)
            ->first();

        if (!$hakAkses) return false;

        $akses = json_decode($hakAkses->akses, true);
        return is_array($akses) && in_array('humas', $akses);
    }
}
