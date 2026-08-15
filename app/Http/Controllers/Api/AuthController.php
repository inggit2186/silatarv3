<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AuthController extends BaseApiController
{
    /**
     * Login user
     * POST /api/auth/login
     * Support: email atau nomor_induk (NIP)
     */
    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $login = $request->input('login');

        // Cek apakah login adalah email atau NIP
        $user = User::with('dept.hariKerja')
            ->where(function($query) use ($login) {
                $query->where('email', $login)
                    ->orWhere('nomor_induk', $login);
            })
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->error('Email, NIP, atau password salah', 401);
        }

        // Create Sanctum token
        $token = $user->createToken('mobile-app')->plainTextToken;

        return $this->success([
            'user' => $this->formatUser($user),
            'token' => $token,
            'token_type' => 'Bearer',
        ], 'Login berhasil');
    }

    /**
     * Register new user
     * POST /api/auth/register
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'nik' => 'nullable|string|max:16',
            'phone' => 'nullable|string|max:20',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'nik' => $request->nik,
            'no_hp' => $request->phone,
            'role' => 'other',
            'status' => 'active',
        ]);

        $token = $user->createToken('mobile-app')->plainTextToken;

        return $this->success([
            'user' => $this->formatUser($user),
            'token' => $token,
            'token_type' => 'Bearer',
        ], 'Registrasi berhasil', 201);
    }

    /**
     * Get current user
     * GET /api/auth/me
     */
    public function me(Request $request)
    {
        $user = $request->user()->load('dept.hariKerja');

        return $this->success([
            'user' => $this->formatUser($user),
        ], 'Data user');
    }

    /**
     * Logout user
     * POST /api/auth/logout
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->success(null, 'Logout berhasil');
    }

    /**
     * Update user profile
     * PUT /api/auth/update-profile
     */
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'nik' => 'sometimes|string|max:16',
            'email' => 'sometimes|email|max:255',
            'no_hp' => 'sometimes|string|max:20',
            'alamat' => 'sometimes|string|max:500',
            'tempat_lahir' => 'sometimes|string|max:100',
            'tanggal_lahir' => 'sometimes|date',
            'jenis_kelamin' => 'sometimes|in:L,P',
            'bio' => 'sometimes|string|max:500',
        ]);

        // Map field names from API to database columns
        $data = [];
        if ($request->has('name')) $data['name'] = $request->name;
        if ($request->has('nik')) $data['nik'] = $request->nik;
        if ($request->has('email')) $data['email'] = $request->email;
        if ($request->has('no_hp')) $data['telp'] = $request->no_hp;
        if ($request->has('alamat')) $data['alamat'] = $request->alamat;
        if ($request->has('tempat_lahir')) $data['tempat_lahir'] = $request->tempat_lahir;
        if ($request->has('tanggal_lahir')) $data['tanggal_lahir'] = $request->tanggal_lahir;
        if ($request->has('jenis_kelamin')) $data['jk'] = $request->jenis_kelamin;
        if ($request->has('bio')) $data['bio'] = $request->bio;

        $user->update($data);

        return $this->success([
            'user' => $this->formatUser($user->fresh()),
        ], 'Profil berhasil diupdate');
    }

    /**
     * Change password
     * PUT /api/auth/change-password
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return $this->error('Password lama salah', 400);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        // Revoke all tokens except current
        $user->tokens()->where('id', '!=', $user->currentAccessToken()->id)->delete();

        return $this->success(null, 'Password berhasil diubah');
    }

    /**
     * Forgot password (send reset link)
     * POST /api/auth/forgot-password
     */
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        // Check if user exists
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            // Don't reveal if email exists
            return $this->success(null, 'Jika email terdaftar, link reset akan dikirim');
        }

        // TODO: Implement password reset email
        // For now, just return success
        return $this->success(null, 'Jika email terdaftar, link reset akan dikirim');
    }

    /**
     * Format user data for API response
     */
    private function formatUser(User $user): array
    {
        // Get data from tenaga_ktd table if exists
        $tenaga = \Illuminate\Support\Facades\DB::table('tenaga_ktd')
            ->where('user_id', $user->id)
            ->first();

        // Use tenaga_ktd data if available, fallback to users table
        $nama = $tenaga->nama ?? $user->name;
        $jk = $tenaga->jenis_kelamin ?? $user->jk;
        $tempatLahir = $tenaga->tempat_lahir ?? $user->tempat_lahir;
        $tanggalLahir = $tenaga->tanggal_lahir ?? $user->tanggal_lahir;
        $telp = $tenaga->telp ?? $user->telp;
        $alamat = $tenaga->alamat ?? $user->alamat;
        $bio = $tenaga->bio ?? $user->bio;
        $email = $tenaga->email ?? $user->email;
        $nomorInduk = $tenaga->nomor_induk ?? $user->nomor_induk;

        $data = [
            'id' => $user->id,
            'name' => $nama,
            'email' => $email,
            'role' => $user->role,
            'nik' => $tenaga->nik ?? $user->nik,
            'nomor_induk' => $nomorInduk,
            'no_hp' => $telp,
            'alamat' => $alamat,
            'tempat_lahir' => $tempatLahir,
            'tanggal_lahir' => $tanggalLahir,
            'jenis_kelamin' => $jk,
            'foto' => $user->pp,
            'pp' => $user->pp,
            'bio' => $bio,
            'status' => $user->status,
            'unit_id' => $user->dept_id,
            'created_at' => $user->created_at,
        ];

        // Include department with location and work schedule
        if ($user->dept) {
            $dept = $user->dept;

            // Base dept data
            $deptData = [
                'id' => $dept->id,
                'nama' => $dept->nama,
                'latitude' => $dept->latitude,
                'longitude' => $dept->longitude,
                'radius' => $dept->radius ?? 100,
                'jam_masuk' => $dept->jam_masuk,
                'jam_pulang' => $dept->jam_pulang,
            ];

            // Include hari kerja schedule from relation
            if ($dept->hariKerja) {
                $hk = $dept->hariKerja;
                $deptData['hari_kerja'] = [
                    'id' => $hk->id,
                    'masuk' => $hk->masuk,
                    'biasa' => $hk->biasa,
                    'jumat' => $hk->jumat,
                    'sabtu' => $hk->sabtu,
                    'minggu' => $hk->minggu,
                ];
            }

            $data['dept'] = $deptData;
        }

        return $data;
    }
}
