<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class UserController extends BaseApiController
{
    /**
     * Get user profile
     * GET /api/user/profile
     */
    public function profile(Request $request)
    {
        $user = $request->user();

        // Get data from tenaga_ktd table if exists
        $tenaga = DB::table('tenaga_ktd')
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

        return $this->success([
            'id' => $user->id,
            'name' => $nama,
            'email' => $email,
            'nik' => $tenaga->nik ?? $user->nik,
            'nomor_induk' => $nomorInduk,
            'nip' => $user->nip,
            'no_hp' => $telp,
            'alamat' => $alamat,
            'tempat_lahir' => $tempatLahir,
            'tanggal_lahir' => $tanggalLahir,
            'jenis_kelamin' => $jk,
            'foto' => $user->pp,
            'pp' => $user->pp,
            'bio' => $bio,
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
            'email' => 'sometimes|email|max:255',
            'no_hp' => 'sometimes|string|max:20',
            'alamat' => 'sometimes|string|max:500',
            'tempat_lahir' => 'sometimes|string|max:100',
            'tanggal_lahir' => 'sometimes|date',
            'jenis_kelamin' => 'sometimes|in:L,P',
            'bio' => 'sometimes|string|max:500',
        ]);

        // Get tenaga_ktd record
        $tenaga = DB::table('tenaga_ktd')
            ->where('user_id', $user->id)
            ->first();

        if ($tenaga) {
            // Update tenaga_ktd table
            $tenagaData = [];
            if ($request->has('name')) $tenagaData['nama'] = $request->name;
            if ($request->has('nik')) $tenagaData['nik'] = $request->nik;
            if ($request->has('email')) $tenagaData['email'] = $request->email;
            if ($request->has('no_hp')) $tenagaData['telp'] = $request->no_hp;
            if ($request->has('alamat')) $tenagaData['alamat'] = $request->alamat;
            if ($request->has('tempat_lahir')) $tenagaData['tempat_lahir'] = $request->tempat_lahir;
            if ($request->has('tanggal_lahir')) $tenagaData['tanggal_lahir'] = $request->tanggal_lahir;
            if ($request->has('jenis_kelamin')) $tenagaData['jenis_kelamin'] = $request->jenis_kelamin;
            if ($request->has('bio')) $tenagaData['bio'] = $request->bio;

            if (!empty($tenagaData)) {
                DB::table('tenaga_ktd')
                    ->where('user_id', $user->id)
                    ->update($tenagaData);
            }
        } else {
            // Update users table
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

            if (!empty($data)) {
                $user->update($data);
            }
        }

        return $this->success([
            'user' => $this->formatUser($user->fresh()),
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
            'foto' => 'required|image|mimes:jpg,jpeg,png|max:2048',
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
