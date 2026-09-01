<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(Request $request)
    {
        $query = DB::table('users as u')
            ->leftJoin('ktd_department as dept', 'dept.id', '=', 'u.dept_id')
            ->select([
                'u.id',
                'u.name',
                'u.email',
                'u.nomor_induk',
                'u.pp',
                'u.role',
                'u.status',
                'u.jk',
                'u.pekerjaan',
                'dept.nama as dept_name',
                'u.created_at',
                'u.updated_at',
            ]);

        // Search filter - search by name, email, or NIP
        $search = $request->input('search');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('u.name', 'like', "%{$search}%")
                  ->orWhere('u.email', 'like', "%{$search}%")
                  ->orWhere('u.nomor_induk', 'like', "%{$search}%");
            });
        }

        // Role filter
        $role = $request->input('role');
        if ($role) {
            $query->where('u.role', $role);
        }

        // Department filter
        $deptId = $request->input('dept_id');
        if ($deptId) {
            $query->where('u.dept_id', $deptId);
        }

        // Status filter (1 = aktif, 0 = nonaktif)
        $status = $request->input('status');
        if ($status !== null && $status !== '') {
            $query->where('u.status', (int) $status);
        }

        // Order by
        $sortBy = $request->input('sort_by', 'created_at');
        $sortDir = $request->input('sort_dir', 'desc');
        $query->orderBy($sortBy, $sortDir);

        // Paginate
        $users = $query->paginate(15)->withQueryString();

        // Get filter options
        $roles = ['superadmin', 'admin', 'kasubbag', 'kasi', 'kepala', 'petugas', 'pegawai', 'frontdesk', 'pensiun', 'pindah'];
        $departments = DB::table('ktd_department')
            ->whereIn('status', [1, 2])
            ->orderBy('nama')
            ->get(['id', 'nama']);

        // Check if current user is admin (admin, superadmin, kepala)
        $currentUser = auth()->user();
        $isAdmin = in_array($currentUser->role, ['admin', 'superadmin', 'kepala']);

        return view('admin.users.index', [
            'title' => 'Manajemen Pengguna - SILATAR Admin',
            'breadcrumbs' => [
                ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['label' => 'Pengguna', 'url' => null],
            ],
            'users' => $users,
            'roles' => $roles,
            'departments' => $departments,
            'filters' => [
                'search' => $search,
                'role' => $role,
                'dept_id' => $deptId,
                'status' => $status,
            ],
            'isAdmin' => $isAdmin,
        ]);
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $roles = ['superadmin', 'admin', 'kasubbag', 'kasi', 'kepala', 'petugas', 'pegawai', 'frontdesk', 'pensiun', 'pindah'];
        $departments = DB::table('ktd_department')
            ->whereIn('status', [1, 2])
            ->orderBy('nama')
            ->get(['id', 'nama']);

        return view('admin.users.create', [
            'title' => 'Tambah Pengguna - SILATAR Admin',
            'breadcrumbs' => [
                ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['label' => 'Pengguna', 'url' => route('admin.users.index')],
                ['label' => 'Tambah', 'url' => null],
            ],
            'roles' => $roles,
            'departments' => $departments,
        ]);
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255', 'unique:users,email'],
            'nomor_induk' => ['required', 'numeric', 'unique:users,nomor_induk'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'string', Rule::in(['superadmin', 'admin', 'kasubbag', 'kasi', 'kepala', 'petugas', 'pegawai', 'frontdesk', 'pensiun', 'pindah'])],
            'dept_id' => ['nullable', 'numeric'],
            'jk' => ['nullable', 'string', Rule::in(['Pria', 'Wanita'])],
            'pekerjaan' => ['nullable', 'string', 'max:255'],
            'telp' => ['nullable', 'string', 'max:50'],
            'alamat' => ['nullable', 'string', 'max:500'],
            'status' => ['nullable', 'numeric'],
        ]);

        $userId = DB::table('users')->insertGetId([
            'name' => $validated['name'],
            'email' => $validated['email'] ?? null,
            'nomor_induk' => $validated['nomor_induk'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'dept_id' => $validated['dept_id'] ?? 0,
            'jk' => $validated['jk'] ?? 'Pria',
            'pekerjaan' => $validated['pekerjaan'] ?? '',
            'telp' => $validated['telp'] ?? null,
            'alamat' => $validated['alamat'] ?? null,
            'status' => $validated['status'] ?? 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()
            ->route('admin.users.edit', $userId)
            ->with('success', 'Pengguna berhasil ditambahkan. Lengkapi data lainnya di sini.');
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(int $id)
    {
        $user = DB::table('users')->where('id', $id)->first();

        if (!$user) {
            abort(404, 'Pengguna tidak ditemukan.');
        }

        // Initialize tenaga_ktd fields with null
        $user->nik = null;
        $user->kk = null;
        $user->npwp = null;
        $user->nikah = null;
        $user->jenis_pjob = null;
        $user->pjob = null;
        $user->jml_anak = null;
        $user->nama_ibu = null;
        $user->nama_istri_suami = null;
        $user->bio = null;
        $user->facebook = null;
        $user->twitter = null;
        $user->instagram = null;
        $user->linkedin = null;

        // Get data from tenaga_ktd table if exists
        $tenaga = DB::table('tenaga_ktd')
            ->where('user_id', $user->id)
            ->first();

        // Merge data from tenaga_ktd to user object for fields that might be in tenaga_ktd
        if ($tenaga) {
            // Fields that are only in tenaga_ktd
            $user->nik = $tenaga->nik ?? null;
            $user->kk = $tenaga->kk ?? null;
            $user->npwp = $tenaga->npwp ?? null;
            $user->nikah = $tenaga->nikah ?? null;
            $user->jenis_pjob = $tenaga->jenis_pjob ?? null;
            $user->pjob = $tenaga->pjob ?? null;
            $user->jml_anak = $tenaga->jml_anak ?? null;
            $user->nama_ibu = $tenaga->nama_ibu ?? null;
            $user->nama_istri_suami = $tenaga->nama_istri_suami ?? null;
            $user->bio = $tenaga->bio ?? null;
            $user->facebook = $tenaga->facebook ?? null;
            $user->twitter = $tenaga->twitter ?? null;
            $user->instagram = $tenaga->instagram ?? null;
            $user->linkedin = $tenaga->linkedin ?? null;

            // Use tenaga_ktd data if users table data is empty
            if (empty($user->instansi) && !empty($tenaga->instansi)) {
                $user->instansi = $tenaga->instansi;
            }
            if (empty($user->pekerjaan) && !empty($tenaga->pekerjaan)) {
                $user->pekerjaan = $tenaga->pekerjaan;
            }
            if (empty($user->telp) && !empty($tenaga->telp)) {
                $user->telp = $tenaga->telp;
            }
            if (empty($user->alamat) && !empty($tenaga->alamat)) {
                $user->alamat = $tenaga->alamat;
            }
            if (empty($user->tempat_lahir) && !empty($tenaga->tempat_lahir)) {
                $user->tempat_lahir = $tenaga->tempat_lahir;
            }
            if (empty($user->tanggal_lahir) && !empty($tenaga->tanggal_lahir)) {
                $user->tanggal_lahir = $tenaga->tanggal_lahir;
            }
            if (empty($user->jk) && !empty($tenaga->jenis_kelamin)) {
                $user->jk = $tenaga->jenis_kelamin;
            }
        }

        $roles = ['superadmin', 'admin', 'kasubbag', 'kasi', 'kepala', 'petugas', 'pegawai', 'frontdesk', 'pensiun', 'pindah'];
        $departments = DB::table('ktd_department')
            ->whereIn('status', [1, 2])
            ->orderBy('nama')
            ->get(['id', 'nama']);

        // Get madrasah list for assignment
        $madrasahs = DB::table('ktd_madrasah')
            ->where('status', 1)
            ->orderBy('kategori')
            ->orderBy('nama')
            ->get(['id', 'nama', 'nsm', 'kategori']);

        // Get supervisors list for custom supervisor assignment
        $supervisors = DB::table('users')
            ->whereIn('kat_jabatan', ['kepala', 'kasi', 'kasubbag'])
            ->where('status', 1)
            ->orderBy('kat_jabatan')
            ->orderBy('name')
            ->get(['id', 'name', 'kat_jabatan', 'dept_id'])
            ->map(function ($supervisor) {
                $dept = DB::table('ktd_department')
                    ->where('id', $supervisor->dept_id)
                    ->first();
                $supervisor->department_name = $dept->nama ?? '-';
                return $supervisor;
            });

        // Kategori jabatan options
        $katJabatanOptions = ['kepala', 'kasi', 'kasubbag', 'kaur', 'staf', 'guru', 'pelaksana', 'honorer'];

        return view('admin.users.edit', [
            'title' => 'Edit Pengguna - SILATAR Admin',
            'breadcrumbs' => [
                ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['label' => 'Pengguna', 'url' => route('admin.users.index')],
                ['label' => $user->name, 'url' => null],
            ],
            'user' => $user,
            'roles' => $roles,
            'departments' => $departments,
            'madrasahs' => $madrasahs,
            'supervisors' => $supervisors,
            'katJabatanOptions' => $katJabatanOptions,
        ]);
    }

    /**
     * Update the specified user.
     */
    public function update(Request $request, int $id)
    {
        $user = DB::table('users')->where('id', $id)->first();

        if (!$user) {
            abort(404, 'Pengguna tidak ditemukan.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('users')->ignore($id)],
            'nomor_induk' => ['required', 'numeric', Rule::unique('users')->ignore($id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'string', Rule::in(['superadmin', 'admin', 'kasubbag', 'kasi', 'kepala', 'petugas', 'pegawai', 'frontdesk', 'pensiun', 'pindah'])],
            'dept_id' => ['nullable', 'numeric'],
            'madrasah_id' => ['nullable', 'numeric', 'exists:ktd_madrasah,id'],
            'custom_supervisor_id' => ['nullable', 'numeric', 'exists:users,id'],
            'kat_jabatan' => ['nullable', 'string', Rule::in(['kepala', 'kasi', 'kasubbag', 'kaur', 'staf', 'guru', 'pelaksana', 'honorer'])],
            'jk' => ['nullable', 'string', Rule::in(['Pria', 'Wanita'])],
            'pekerjaan' => ['nullable', 'string', 'max:255'],
            'telp' => ['nullable', 'string', 'max:50'],
            'alamat' => ['nullable', 'string', 'max:500'],
            'status' => ['nullable', 'numeric'],
            'tanggal_lahir' => ['nullable', 'date'],
            'tempat_lahir' => ['nullable', 'string', 'max:100'],
            'nik' => ['nullable', 'string', 'max:20'],
            'kk' => ['nullable', 'string', 'max:20'],
            'npwp' => ['nullable', 'string', 'max:30'],
            'nikah' => ['nullable', 'string'],
            'jenis_pjob' => ['nullable', 'string', Rule::in(['ASN', 'NON'])],
            'pjob' => ['nullable', 'string', 'max:255'],
            'instansi' => ['nullable', 'string', 'max:255'],
            'jml_anak' => ['nullable', 'numeric'],
            'nama_ibu' => ['nullable', 'string', 'max:255'],
            'nama_istri_suami' => ['nullable', 'string', 'max:255'],
            'bio' => ['nullable', 'string', 'max:500'],
            'facebook' => ['nullable', 'string', 'max:255'],
            'twitter' => ['nullable', 'string', 'max:255'],
            'instagram' => ['nullable', 'string', 'max:255'],
            'linkedin' => ['nullable', 'string', 'max:255'],
        ]);

        // Update users table
        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'] ?? null,
            'nomor_induk' => $validated['nomor_induk'],
            'role' => $validated['role'],
            'dept_id' => $validated['dept_id'] ?? 0,
            'madrasah_id' => $validated['madrasah_id'] ?? null,
            'custom_supervisor_id' => $validated['custom_supervisor_id'] ?? null,
            'kat_jabatan' => $validated['kat_jabatan'] ?? null,
            'jk' => $validated['jk'] ?? 'Pria',
            'pekerjaan' => $validated['pekerjaan'] ?? '',
            'telp' => $validated['telp'] ?? null,
            'alamat' => $validated['alamat'] ?? null,
            'status' => $validated['status'] ?? 1,
            'updated_at' => now(),
        ];

        // Add optional fields if provided
        if (isset($validated['tanggal_lahir'])) {
            $updateData['tanggal_lahir'] = $validated['tanggal_lahir'];
        }
        if (isset($validated['tempat_lahir'])) {
            $updateData['tempat_lahir'] = $validated['tempat_lahir'];
        }

        // Only update password if provided
        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        DB::table('users')->where('id', $id)->update($updateData);

        // Update tenaga_ktd table (fields that exist only in tenaga_ktd)
        $tenagaData = [];
        if (isset($validated['nik'])) {
            $tenagaData['nik'] = $validated['nik'];
        }
        if (isset($validated['kk'])) {
            $tenagaData['kk'] = $validated['kk'];
        }
        if (isset($validated['npwp'])) {
            $tenagaData['npwp'] = $validated['npwp'];
        }
        if (isset($validated['nikah'])) {
            $tenagaData['nikah'] = $validated['nikah'];
        }
        if (isset($validated['jenis_pjob'])) {
            $tenagaData['jenis_pjob'] = $validated['jenis_pjob'];
        }
        if (isset($validated['pjob'])) {
            $tenagaData['pjob'] = $validated['pjob'];
        }
        if (isset($validated['instansi'])) {
            $tenagaData['instansi'] = $validated['instansi'];
        }
        if (isset($validated['jml_anak'])) {
            $tenagaData['jml_anak'] = $validated['jml_anak'];
        }
        if (isset($validated['nama_ibu'])) {
            $tenagaData['nama_ibu'] = $validated['nama_ibu'];
        }
        if (isset($validated['nama_istri_suami'])) {
            $tenagaData['nama_istri_suami'] = $validated['nama_istri_suami'];
        }
        if (isset($validated['bio'])) {
            $tenagaData['bio'] = $validated['bio'];
        }
        if (isset($validated['facebook'])) {
            $tenagaData['facebook'] = $validated['facebook'];
        }
        if (isset($validated['twitter'])) {
            $tenagaData['twitter'] = $validated['twitter'];
        }
        if (isset($validated['instagram'])) {
            $tenagaData['instagram'] = $validated['instagram'];
        }
        if (isset($validated['linkedin'])) {
            $tenagaData['linkedin'] = $validated['linkedin'];
        }

        // Update tenaga_ktd if record exists and has data to update
        $tenaga = DB::table('tenaga_ktd')->where('user_id', $id)->first();
        if ($tenaga && !empty($tenagaData)) {
            DB::table('tenaga_ktd')->where('user_id', $id)->update($tenagaData);
        } elseif (!empty($tenagaData)) {
            // Create tenaga_ktd record if it doesn't exist
            $tenagaData['user_id'] = $id;
            $tenagaData['nama'] = $validated['name'];
            $tenagaData['created_at'] = now();
            $tenagaData['updated_at'] = now();
            DB::table('tenaga_ktd')->insert($tenagaData);
        }

        return redirect()
            ->back()
            ->with('success', 'Data pengguna berhasil diperbarui.');
    }

    /**
     * Remove the specified user.
     */
    public function destroy(int $id)
    {
        $user = DB::table('users')->where('id', $id)->first();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Pengguna tidak ditemukan.'], 404);
        }

        // Prevent deleting own account
        if ($id === auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Anda tidak dapat menghapus akun sendiri.'], 400);
        }

        // Prevent deleting superadmin
        if ($user->role === 'superadmin') {
            return response()->json(['success' => false, 'message' => 'Tidak dapat menghapus user dengan role superadmin.'], 400);
        }

        DB::table('users')->where('id', $id)->delete();

        return response()->json(['success' => true, 'message' => 'Pengguna berhasil dihapus.']);
    }

    /**
     * Toggle user status (active/inactive).
     */
    public function toggleStatus(int $id)
    {
        $user = DB::table('users')->where('id', $id)->first();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Pengguna tidak ditemukan.'], 404);
        }

        $newStatus = $user->status === 1 ? 0 : 1;

        DB::table('users')->where('id', $id)->update([
            'status' => $newStatus,
            'updated_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => $newStatus === 1 ? 'Pengguna diaktifkan.' : 'Pengguna dinonaktifkan.',
            'new_status' => $newStatus,
        ]);
    }

    /**
     * Change user password.
     */
    public function changePassword(Request $request, int $id)
    {
        $request->validate([
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'password.required' => 'Password harus diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $user = DB::table('users')->where('id', $id)->first();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Pengguna tidak ditemukan.'], 404);
        }

        DB::table('users')->where('id', $id)->update([
            'password' => Hash::make($request->password),
            'updated_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password berhasil diubah.',
        ]);
    }

    /**
     * Change own password (current logged in user).
     */
    public function changePasswordOwn(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'current_password.required' => 'Password lama harus diisi.',
            'password.required' => 'Password baru harus diisi.',
            'password.min' => 'Password baru minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $user = auth()->user();

        // Verify old password
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['success' => false, 'message' => 'Password lama salah.'], 400);
        }

        DB::table('users')->where('id', $user->id)->update([
            'password' => Hash::make($request->password),
            'updated_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password berhasil diubah.',
        ]);
    }

    /**
     * Get user data for AJAX.
     */
    public function show(int $id)
    {
        $user = DB::table('users as u')
            ->leftJoin('ktd_department as dept', 'dept.id', '=', 'u.dept_id')
            ->where('u.id', $id)
            ->select([
                'u.*',
                'dept.nama as dept_name',
            ])
            ->first();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Pengguna tidak ditemukan.'], 404);
        }

        return response()->json(['success' => true, 'user' => $user]);
    }

    /**
     * Display user detail page.
     */
    public function detail(int $id)
    {
        $user = DB::table('users as u')
            ->leftJoin('ktd_department as dept', 'dept.id', '=', 'u.dept_id')
            ->leftJoin('ktd_madrasah as madrasah', 'madrasah.id', '=', 'u.madrasah_id')
            ->select([
                'u.*',
                'dept.nama as dept_name',
                'madrasah.nama as madrasah_name',
            ])
            ->where('u.id', $id)
            ->first();

        if (!$user) {
            abort(404, 'Pengguna tidak ditemukan.');
        }

        // Initialize tenaga_ktd fields with null
        $user->nik = null;
        $user->kk = null;
        $user->npwp = null;
        $user->nikah = null;
        $user->jenis_pjob = null;
        $user->pjob = null;
        $user->jml_anak = null;
        $user->nama_ibu = null;
        $user->nama_istri_suami = null;
        $user->bio = null;
        $user->facebook = null;
        $user->twitter = null;
        $user->instagram = null;
        $user->linkedin = null;

        // Get data from tenaga_ktd
        $tenaga = DB::table('tenaga_ktd')
            ->where('user_id', $user->id)
            ->first();

        // Merge data from tenaga_ktd
        if ($tenaga) {
            $user->nik = $tenaga->nik ?? null;
            $user->kk = $tenaga->kk ?? null;
            $user->npwp = $tenaga->npwp ?? null;
            $user->nikah = $tenaga->nikah ?? null;
            $user->jenis_pjob = $tenaga->jenis_pjob ?? null;
            $user->pjob = $tenaga->pjob ?? null;
            $user->jml_anak = $tenaga->jml_anak ?? null;
            $user->nama_ibu = $tenaga->nama_ibu ?? null;
            $user->nama_istri_suami = $tenaga->nama_istri_suami ?? null;
            $user->bio = $tenaga->bio ?? null;
            $user->facebook = $tenaga->facebook ?? null;
            $user->twitter = $tenaga->twitter ?? null;
            $user->instagram = $tenaga->instagram ?? null;
            $user->linkedin = $tenaga->linkedin ?? null;
        }

        return view('admin.users.show', [
            'title' => 'Detail Pengguna - SILATAR Admin',
            'breadcrumbs' => [
                ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['label' => 'Pengguna', 'url' => route('admin.users.index')],
                ['label' => $user->name, 'url' => null],
            ],
            'user' => $user,
        ]);
    }

    /**
     * Impersonate (login as) another user.
     */
    public function impersonate(Request $request)
    {
        $request->validate([
            'nip' => ['required', 'string', 'max:50'],
        ], [
            'nip.required' => 'NIP harus diisi.',
        ]);

        $targetUser = DB::table('users')
            ->where('nomor_induk', $request->nip)
            ->first();

        if (!$targetUser) {
            return back()->with('error', 'NIP tidak ditemukan dalam sistem.');
        }

        if ($targetUser->status != 1) {
            return back()->with('error', 'Akun tidak aktif. Silakan hubungi administrator.');
        }

        // Store current admin info in session before impersonating
        session(['impersonate' => [
            'id' => auth()->id(),
            'name' => auth()->user()->name,
            'role' => auth()->user()->role,
        ]]);

        // Login as target user
        auth()->loginUsingId($targetUser->id);
        session()->regenerate();

        return redirect()->intended(route('pelayanan'))->with('success', 'Anda sekarang masuk sebagai ' . $targetUser->name);
    }

    /**
     * Stop impersonating and return to admin account.
     */
    public function stopImpersonate(Request $request)
    {
        $impersonateData = session('impersonate');

        if (!$impersonateData) {
            return redirect()->route('home');
        }

        // Logout current (impersonated) user
        auth()->logout();

        // Restore original admin session
        $adminUser = DB::table('users')->where('id', $impersonateData['id'])->first();

        if ($adminUser) {
            auth()->loginUsingId($adminUser->id);
            session()->regenerate();
        }

        // Clear impersonate session
        session()->forget('impersonate');

        return redirect()->route('admin.dashboard')->with('success', 'Kembali ke akun admin: ' . $impersonateData['name']);
    }
}