<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class EnsureProfileComplete
{
    /**
     * Role yang wajib dicek kelengkapan datanya
     */
    private array $targetRoles = ['pegawai', 'petugas', 'kasi', 'kasubbag', 'kepala'];

    /**
     * Route yang dikecualikan dari pengecekan
     * (agar user bisa mengakses halaman edit profil, logout, dll)
     */
    private array $exemptRoutes = [
        'profil.edit',
        'profil.update',
        'profil',
        'logout',
        'signature.get',
        'signature.save',
        'impersonate.stop',
        'ubah-password',
        'ubah-password.update',
        'login',
        'login.submit',
    ];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Skip jika belum login
        if (! $user) {
            return $next($request);
        }

        // Skip AJAX/JSON request agar tidak break API/fetch calls.
        if ($request->expectsJson() || $request->ajax()) {
            return $next($request);
        }

        // Skip method non-GET (agar tidak intercept form submit lain).
        if (! $request->isMethod('GET')) {
            return $next($request);
        }

        // Skip route yang dikecualikan agar halaman edit profil tidak redirect loop.
        $currentRoute = $request->route()?->getName();
        if ($currentRoute && in_array($currentRoute, $this->exemptRoutes, true)) {
            return $next($request);
        }

        $role = strtolower(trim((string) $user->role));

        // Admin tidak mengikuti aturan kelengkapan profil agar tetap dapat
        // mengakses panel administrasi tanpa data profil pegawai.
        if (in_array($role, ['superadmin', 'admin'], true)) {
            return $next($request);
        }

        // Role target juga diwajibkan melengkapi jabatan. Semua role lainnya
        // tetap wajib memiliki foto, NIK, dan nomor KK yang valid.
        $isTargetRole = in_array($role, $this->targetRoles, true);

        // Ambil data tenaga_ktd untuk cross-check NIK, KK, dan jabatan.
        $tenaga = DB::table('tenaga_ktd')
            ->where(function ($q) use ($user) {
                $q->where('user_id', $user->id);
                if (! empty($user->nomor_induk)) {
                    $q->orWhere('nomor_induk', $user->nomor_induk);
                }
            })
            ->first();

        // Cek kelengkapan field wajib. NIK/KK berlaku untuk semua role selain
        // superadmin/admin; jabatan hanya ditambahkan untuk role target.
        $missing = $this->getMissingFields($user, $tenaga, $isTargetRole);

        if (! empty($missing)) {
            return redirect()->route('profil.edit')
                ->with('profile_incomplete', $missing)
                ->with('warning', 'Silakan lengkapi data berikut untuk melanjutkan: '.implode(', ', $missing));
        }

        return $next($request);
    }

    /**
     * Cek field wajib yang belum lengkap
     *
     * @return array<string>
     */
    private function getMissingFields($user, $tenaga, bool $isTargetRole): array
    {
        $missing = [];

        // 1. Foto Profil (users.pp)
        if (empty($user->pp)) {
            $missing[] = 'Foto Profil';
        }

        // 2. NIK 16 digit (users.nip atau tenaga_ktd.nik)
        $nikUser = (string) ($user->nip ?? '');
        $nikTenaga = (string) ($tenaga->nik ?? '');
        if (! preg_match('/^\d{16}$/', $nikUser) && ! preg_match('/^\d{16}$/', $nikTenaga)) {
            $missing[] = 'NIK (16 digit)';
        }

        // 3. Nomor KK 16 digit (tenaga_ktd.kk)
        $kk = (string) ($tenaga->kk ?? '');
        if (! preg_match('/^\d{16}$/', $kk)) {
            $missing[] = 'Nomor KK (16 digit)';
        }

        // 4. Jabatan Saat Ini (users.pekerjaan atau tenaga_ktd.pekerjaan)
        if ($isTargetRole) {
            $pekerjaanUser = trim((string) ($user->pekerjaan ?? ''));
            $pekerjaanTenaga = trim((string) ($tenaga->pekerjaan ?? ''));
            if ($pekerjaanUser === '' && $pekerjaanTenaga === '') {
                $missing[] = 'Jabatan Saat Ini';
            }
        }

        return $missing;
    }
}
