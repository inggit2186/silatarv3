<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function showForm(Request $request)
    {
        $type = $request->get('type', 'masyarakat');

        // All departments for selection
        $allDepartments = Department::whereIn('status', [1, 2])
            ->orderBy('kategori')
            ->orderBy('nama')
            ->get();

        // For Guru PAI: only Pemerintah Daerah (id=998)
        $guruPaiDepartments = $allDepartments->where('id', 998)->values();

        // Special units that need tempat_bekerja (non-Kemenag units)
        $specialUnits = [998, 999]; // Pemerintah Daerah, Swasta/Lainnya

        return view('auth.register', [
            'userType' => $type,
            'allDepartments' => $allDepartments,
            'guruPaiDepartments' => $guruPaiDepartments,
            'specialUnits' => $specialUnits,
        ]);
    }

    public function register(Request $request)
    {
        $type = $request->input('user_type');

        // Base validation rules
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
            'user_type' => ['required', 'in:honorer,guru_pai,masyarakat'],
            'telp' => ['required', 'string', 'max:20'],
        ];

        // Type-specific validation rules
        if ($type === 'honorer') {
            $rules = array_merge($rules, [
                'nomor_induk' => ['required', 'string', 'max:20', 'digits:16', 'unique:users,nomor_induk'], // Pakai NIK
                'kk' => ['required', 'string', 'max:20'],
                'dept_id' => ['required', 'integer', 'exists:ktd_department,id'],
                'kat_jabatan' => ['required', 'in:adm,guru,kepala'],
                'pekerjaan' => ['required', 'string', 'max:255'],
                'tempat_lahir' => ['required', 'string', 'max:100'],
                'tanggal_lahir' => ['required', 'date', 'before:today'],
                'jenis_kelamin' => ['required', 'in:Pria,Wanita'],
                'alamat' => ['required', 'string', 'max:500'],
            ]);
        } elseif ($type === 'guru_pai') {
            $rules = array_merge($rules, [
                'nomor_induk' => ['required', 'string', 'max:50', 'unique:users,nomor_induk'], // Pakai NIP
                'nik' => ['required', 'string', 'max:20', 'digits:16'],
                'kk' => ['required', 'string', 'max:20'],
                'dept_id' => ['required', 'integer', 'in:998'],
                'jenis_asn' => ['required', 'in:pns,pppk,honorer'],
                'pekerjaan' => ['required', 'string', 'max:255'],
                'tempat_lahir' => ['required', 'string', 'max:100'],
                'tanggal_lahir' => ['required', 'date', 'before:today'],
                'jenis_kelamin' => ['required', 'in:Pria,Wanita'],
                'nuptk' => ['nullable', 'string', 'max:20'],
                'alamat' => ['required', 'string', 'max:500'],
                'satker' => ['required', 'string', 'max:255'],
            ]);
        } else {
            // masyarakat biasa - use NIK as nomor_induk for login
            $rules = array_merge($rules, [
                'nomor_induk' => ['required', 'string', 'max:20', 'digits:16', 'unique:users,nomor_induk'],
                'pekerjaan' => ['required', 'string', 'max:255'],
                'tempat_bekerja' => ['required', 'string', 'max:255'],
                'tempat_lahir' => ['required', 'string', 'max:100'],
                'tanggal_lahir' => ['required', 'date', 'before:today'],
                'jenis_kelamin' => ['required', 'in:Pria,Wanita'],
                'alamat' => ['required', 'string', 'max:500'],
            ]);
        }

        $validated = $request->validate($rules);

        DB::beginTransaction();
        try {
            // Determine role and kat_jabatan based on user type
            $role = ($type === 'masyarakat') ? 'user' : 'pegawai';
            $katJabatan = ($type === 'masyarakat') ? null : ($validated['kat_jabatan'] ?? null);

            // nomor_induk: use NIP for honorer/guru_pai, NIK for masyarakat
            $nomorInduk = $validated['nomor_induk'];

            // dept_id: masyarakat = 103, others use selected dept
            $deptId = ($type === 'masyarakat') ? 103 : ($validated['dept_id'] ?? null);

            // Create user
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'telp' => $validated['telp'],
                'role' => $role,
                'jk' => $validated['jenis_kelamin'] ?? null,
                'tempat_lahir' => $validated['tempat_lahir'] ?? null,
                'tanggal_lahir' => $validated['tanggal_lahir'] ?? null,
                'alamat' => $validated['alamat'] ?? null,
                'nomor_induk' => $nomorInduk,
                'dept_id' => $deptId,
                'satker' => $validated['satker'] ?? $validated['tempat_bekerja'] ?? null,
                'kat_jabatan' => $katJabatan,
                'pekerjaan' => $validated['pekerjaan'] ?? null,
                'status' => 1, // 1 = active, 0 = non-active
            ]);

            // Determine jenis_guru
            $jenisGuru = null;
            if ($type === 'honorer' && $katJabatan === 'guru') {
                $jenisGuru = 'mad';
            } elseif ($type === 'guru_pai') {
                $jenisGuru = 'pemda';
            }

            // Create tenaga_ktd record
            $tenagaKtdData = [
                'nama' => $validated['name'],
                'email' => $validated['email'],
                'telp' => $validated['telp'],
                'tempat_lahir' => $validated['tempat_lahir'] ?? null,
                'tanggal_lahir' => $validated['tanggal_lahir'] ?? null,
                'jenis_kelamin' => $validated['jenis_kelamin'] ?? null,
                'alamat' => $validated['alamat'] ?? null,
                'dept_id' => $deptId,
                'user_id' => $user->id,
                'status' => 1, // 1 = active, 0 = non-active
                'kat_jabatan' => $katJabatan,
                'pekerjaan' => $validated['pekerjaan'] ?? null,
                'nomor_induk' => $nomorInduk,
                'nik' => $validated['nik'] ?? $nomorInduk, // Use NIK from form or nomor_induk for masyarakat
                'kk' => $validated['kk'] ?? null,
                'nuptk' => $validated['nuptk'] ?? null,
                'jenis_guru' => $jenisGuru,
            ];

            // Set kat_jabatan based on type
            if ($type === 'honorer') {
                $tenagaKtdData['kat_jabatan'] = 'honorer';
            } elseif ($type === 'guru_pai') {
                $tenagaKtdData['kat_jabatan'] = 'guru_pai';
            }

            DB::table('tenaga_ktd')->insert($tenagaKtdData);

            DB::commit();

            return redirect()->route('login')->with('success', 'Pendaftaran berhasil! Silakan login dengan akun Anda.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    private function getKatJabatan($type)
    {
        return match($type) {
            'honorer' => 'honorer',
            'guru_pai' => 'guru_pai',
            default => 'masyarakat',
        };
    }
}
