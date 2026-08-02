<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends BaseApiController
{
    /**
     * Get user profile
     * GET /api/user/profile
     */
    public function profile(Request $request)
    {
        $user = $request->user();

        return $this->success([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'nik' => $user->nik,
            'no_hp' => $user->no_hp,
            'alamat' => $user->alamat,
            'tempat_lahir' => $user->tempat_lahir,
            'tanggal_lahir' => $user->tanggal_lahir,
            'jenis_kelamin' => $user->jenis_kelamin,
            'foto' => $user->foto,
            'role' => $user->role,
            'status' => $user->status,
            'created_at' => $user->created_at,
        ], 'Profil user');
    }

    /**
     * Update user profile
     * PUT /api/user/profile
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
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'nik' => $user->nik,
            'no_hp' => $user->no_hp,
            'alamat' => $user->alamat,
            'tempat_lahir' => $user->tempat_lahir,
            'tanggal_lahir' => $user->tanggal_lahir,
            'jenis_kelamin' => $user->jenis_kelamin,
            'foto' => $user->foto,
            'role' => $user->role,
            'status' => $user->status,
        ], 'Profil berhasil diupdate');
    }

    /**
     * Update profile photo
     * PUT /api/user/profile/photo
     */
    public function updatePhoto(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'foto' => 'required|image|mimes:jpg,jpeg,png|max:2048', // 2MB max
        ]);

        try {
            // Delete old photo if exists
            if ($user->foto && Storage::disk('public')->exists($user->foto)) {
                Storage::disk('public')->delete($user->foto);
            }

            // Store new photo
            $path = $request->file('foto')->store('photos', 'public');

            // Update user
            $user->update(['foto' => $path]);

            return $this->success([
                'foto' => $path,
                'foto_url' => asset('storage/' . $path),
            ], 'Foto berhasil diupdate');
        } catch (\Exception $e) {
            return $this->error('Gagal update foto: ' . $e->getMessage(), 500);
        }
    }
}
