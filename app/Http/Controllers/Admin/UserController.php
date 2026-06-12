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

        $roles = ['superadmin', 'admin', 'kasubbag', 'kasi', 'kepala', 'petugas', 'pegawai', 'frontdesk', 'pensiun', 'pindah'];
        $departments = DB::table('ktd_department')
            ->whereIn('status', [1, 2])
            ->orderBy('nama')
            ->get(['id', 'nama']);

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
            'jk' => ['nullable', 'string', Rule::in(['Pria', 'Wanita'])],
            'pekerjaan' => ['nullable', 'string', 'max:255'],
            'telp' => ['nullable', 'string', 'max:50'],
            'alamat' => ['nullable', 'string', 'max:500'],
            'status' => ['nullable', 'numeric'],
            'tanggal_lahir' => ['nullable', 'date'],
            'tempat_lahir' => ['nullable', 'string', 'max:100'],
            'nikah' => ['nullable', 'numeric'],
            'jenis_pjob' => ['nullable', 'string', Rule::in(['ASN', 'NON'])],
            'pjob' => ['nullable', 'string', 'max:255'],
            'instansi' => ['nullable', 'string', 'max:255'],
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'] ?? null,
            'nomor_induk' => $validated['nomor_induk'],
            'role' => $validated['role'],
            'dept_id' => $validated['dept_id'] ?? 0,
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
        if (isset($validated['nikah'])) {
            $updateData['nikah'] = $validated['nikah'];
        }
        if (isset($validated['jenis_pjob'])) {
            $updateData['jenis_pjob'] = $validated['jenis_pjob'];
        }
        if (isset($validated['pjob'])) {
            $updateData['pjob'] = $validated['pjob'];
        }
        if (isset($validated['instansi'])) {
            $updateData['instansi'] = $validated['instansi'];
        }

        // Only update password if provided
        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        DB::table('users')->where('id', $id)->update($updateData);

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