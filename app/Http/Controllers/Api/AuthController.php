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
        $user = User::query()
            ->where('email', $login)
            ->orWhere('nomor_induk', $login)
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
        $user = $request->user();

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
            'no_hp' => 'sometimes|string|max:20',
            'alamat' => 'sometimes|string|max:500',
            'tempat_lahir' => 'sometimes|string|max:100',
            'tanggal_lahir' => 'sometimes|date',
            'jenis_kelamin' => 'sometimes|in:L,P',
        ]);

        $user->update($request->only([
            'name', 'nik', 'no_hp', 'alamat', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin'
        ]));

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
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'nik' => $user->nik,
            'nomor_induk' => $user->nomor_induk,
            'no_hp' => $user->no_hp,
            'alamat' => $user->alamat,
            'tempat_lahir' => $user->tempat_lahir,
            'tanggal_lahir' => $user->tanggal_lahir,
            'jenis_kelamin' => $user->jenis_kelamin,
            'foto' => $user->pp, // pp field from database
            'pp' => $user->pp,
            'status' => $user->status,
            'unit_id' => $user->dept_id,
            'created_at' => $user->created_at,
        ];
    }
}
