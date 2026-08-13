<?php

namespace App\Http\Controllers\Api;

use App\Models\JanjiTemu;
use App\Models\User;
use App\Models\Department;
use App\Services\WhatsAppService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class JanjiTemuController extends BaseApiController
{
    protected WhatsAppService $waService;

    public function __construct(WhatsAppService $waService)
    {
        $this->waService = $waService;
        Carbon::setLocale('id_ID');
    }

    // ═══════════════════════════════════════════════════════════════════════
    // USER ENDPOINTS
    // ═══════════════════════════════════════════════════════════════════════

    /**
     * Submit janji temu baru
     * POST /api/janji-temu
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'dept_id' => 'required|integer|exists:ktd_department,id',
            'nip_tujuan' => 'nullable|string|max:255',
            'tipe' => 'required|in:asn,satker',
            'tanggal' => 'required|date_format:Y-m-d',
            'jam' => 'required|date_format:H:i',
            'keterangan' => 'required|string|max:1000',
        ]);

        $user = $request->user();

        // Validate pegawai tujuan if tipe is ASN
        if ($request->tipe === 'asn' && $request->nip_tujuan) {
            $pegawai = User::where('nomor_induk', $request->nip_tujuan)
                ->where('dept_id', $request->dept_id)
                ->first();

            if (!$pegawai) {
                return $this->error('Pegawai tujuan tidak ditemukan di unit kerja tersebut', 422);
            }
        }

        // Format waktu
        $waktu = Carbon::parse($request->tanggal . ' ' . $request->jam);

        DB::beginTransaction();
        try {
            $janjiTemu = JanjiTemu::create([
                'nomor_induk' => $user->nomor_induk,
                'kategori' => $user->role ?? 'public',
                'tipe' => $request->tipe,
                'nama' => $user->name,
                'waktu' => $waktu,
                'nip_tujuan' => $request->nip_tujuan,
                'tujuan' => $request->keterangan,
                'asal' => $user->satker,
                'status' => 'APPOINTMENT',
                'onStaff' => 999, // Belum ditangani
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();

            // Send WhatsApp notification asynchronously
            $this->sendWhatsAppNotification($janjiTemu);

            return $this->success([
                'id' => $janjiTemu->id,
                'status' => $janjiTemu->status_label,
                'waktu' => $janjiTemu->waktu_formatted,
                'message' => 'Janji temu berhasil diajukan. Menunggu konfirmasi dari petugas.',
            ], 'Janji temu berhasil dibuat', 201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create janji temu', ['error' => $e->getMessage()]);
            return $this->error('Gagal membuat janji temu: ' . $e->getMessage(), 500);
        }
    }

    /**
     * List janji temu user (history)
     * GET /api/janji-temu/my-appointments
     */
    public function myAppointments(Request $request): JsonResponse
    {
        $user = $request->user();
        $perPage = $request->input('per_page', 10);
        $status = $request->input('status');

        $query = JanjiTemu::with(['pegawaiTujuan:id,name,nomor_induk', 'unitTujuan:id,nama'])
            ->where('nomor_induk', $user->nomor_induk)
            ->orderBy('waktu', 'desc');

        if ($status) {
            $query->where('status', $status);
        }

        $appointments = $query->paginate($perPage);

        // Format data for mobile
        $appointments->getCollection()->transform(function ($item) {
            return [
                'id' => $item->id,
                'waktu' => $item->waktu_formatted,
                'waktu_raw' => $item->waktu,
                'tujuan' => $item->tujuan,
                'status' => $item->status,
                'status_label' => $item->status_label,
                'status_color' => $item->status_color,
                'tipe' => $item->tipe,
                'target_nama' => $item->tipe === 'asn'
                    ? ($item->pegawaiTujuan->name ?? '-')
                    : ($item->unitTujuan->nama ?? '-'),
                'target_detail' => $item->tipe === 'asn'
                    ? ($item->pegawaiTujuan->kat_jabatan ?? '-')
                    : 'Langsung ke Seksi',
                'komen' => $item->komen,
                'can_cancel' => $item->canCancel(),
            ];
        });

        return $this->successPaginated($appointments, 'Daftar janji temu');
    }

    /**
     * Detail janji temu
     * GET /api/janji-temu/{id}
     */
    public function show(Request $request, int $id): JsonResponse
    {
        $user = $request->user();

        $janjiTemu = JanjiTemu::with([
            'pegawaiTujuan:id,name,nomor_induk,kat_jabatan,dept_id',
            'unitTujuan:id,nama',
            'staffPenangan:id,name',
        ])
        ->where('id', $id)
        ->where('nomor_induk', $user->nomor_induk)
        ->first();

        if (!$janjiTemu) {
            return $this->notFound('Janji temu tidak ditemukan');
        }

        return $this->success([
            'id' => $janjiTemu->id,
            'nomor_induk' => $janjiTemu->nomor_induk,
            'nama_pengaju' => $janjiTemu->nama,
            'waktu' => $janjiTemu->waktu_formatted,
            'waktu_raw' => $janjiTemu->waktu,
            'tujuan' => $janjiTemu->tujuan,
            'asal' => $janjiTemu->asal,
            'tipe' => $janjiTemu->tipe,
            'target_nama' => $janjiTemu->tipe === 'asn'
                ? $janjiTemu->pegawaiTujuan->name
                : $janjiTemu->unitTujuan->nama,
            'target_detail' => $janjiTemu->tipe === 'asn'
                ? [
                    'nip' => $janjiTemu->pegawaiTujuan->nomor_induk,
                    'jabatan' => $janjiTemu->pegawaiTujuan->kat_jabatan ?? '-',
                    'dept_id' => $janjiTemu->pegawaiTujuan->dept_id,
                ]
                : [
                    'dept_id' => $janjiTemu->unitTujuan->id,
                ],
            'status' => $janjiTemu->status,
            'status_label' => $janjiTemu->status_label,
            'status_color' => $janjiTemu->status_color,
            'komen' => $janjiTemu->komen,
            'staff_penangan' => $janjiTemu->staffPenangan->name ?? null,
            'can_cancel' => $janjiTemu->canCancel(),
            'created_at' => $janjiTemu->created_at->format('d M Y H:i'),
        ], 'Detail janji temu');
    }

    /**
     * Cancel janji temu (user only)
     * PUT /api/janji-temu/{id}/cancel
     */
    public function cancel(Request $request, int $id): JsonResponse
    {
        $user = $request->user();

        $janjiTemu = JanjiTemu::where('id', $id)
            ->where('nomor_induk', $user->nomor_induk)
            ->first();

        if (!$janjiTemu) {
            return $this->notFound('Janji temu tidak ditemukan');
        }

        if (!$janjiTemu->canCancel()) {
            return $this->error('Janji temu tidak dapat dibatalkan', 400);
        }

        $janjiTemu->update([
            'status' => 'CANCELLED',
            'komen' => $request->input('alasan', 'Dibatalkan oleh pengguna'),
            'updated_at' => now(),
        ]);

        // Notify staff if assigned
        if ($janjiTemu->onStaff != 999) {
            $this->sendCancellationNotification($janjiTemu);
        }

        return $this->success([
            'id' => $janjiTemu->id,
            'status' => 'CANCELLED',
            'status_label' => 'Dibatalkan',
        ], 'Janji temu berhasil dibatalkan');
    }

    // ═══════════════════════════════════════════════════════════════════════
    // ADMIN/STAFF ENDPOINTS
    // ═══════════════════════════════════════════════════════════════════════

    /**
     * List janji temu untuk admin/staff
     * GET /api/admin/janji-temu
     */
    public function adminIndex(Request $request): JsonResponse
    {
        $user = $request->user();
        $perPage = $request->input('per_page', 10);
        $status = $request->input('status');
        $search = $request->input('search');

        // Check if user is admin
        $isAdmin = in_array($user->role, ['superadmin', 'admin']);

        $query = JanjiTemu::with([
            'pegawaiTujuan:id,name,nomor_induk,dept_id',
            'unitTujuan:id,nama',
            'staffPenangan:id,name',
        ]);

        // If not admin, filter to show only relevant appointments
        if (!$isAdmin) {
            $query->where(function ($q) use ($user) {
                // Appointments directed to this user (tipe asn, nip_tujuan = user's nomor_induk)
                $q->where(function ($sub) use ($user) {
                    $sub->where('tipe', 'asn')
                        ->where('nip_tujuan', $user->nomor_induk);
                })
                // Or appointments directed to user's department (tipe satker, nip_tujuan = user's dept_id)
                ->orWhere(function ($sub) use ($user) {
                    $sub->where('tipe', 'satker')
                        ->where('nip_tujuan', $user->dept_id);
                });
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'LIKE', "%{$search}%")
                  ->orWhere('nomor_induk', 'LIKE', "%{$search}%")
                  ->orWhere('tujuan', 'LIKE', "%{$search}%");
            });
        }

        $appointments = $query->orderBy('created_at', 'desc')
            ->paginate($perPage);

        // Format for admin
        $appointments->getCollection()->transform(function ($item) {
            return [
                'id' => $item->id,
                'nomor_induk_pengaju' => $item->nomor_induk,
                'nama_pengaju' => $item->nama,
                'asal' => $item->asal,
                'waktu' => $item->waktu_formatted,
                'waktu_raw' => $item->waktu,
                'tujuan' => $item->tujuan,
                'tipe' => $item->tipe,
                'target_nama' => $item->tipe === 'asn'
                    ? ($item->pegawaiTujuan->name ?? '-')
                    : ($item->unitTujuan->nama ?? '-'),
                'status' => $item->status,
                'status_label' => $item->status_label,
                'status_color' => $item->status_color,
                'on_staff' => $item->onStaff,
                'staff_nama' => $item->staffPenangan->name ?? '-',
                'komen' => $item->komen,
                'can_process' => $item->canProcess(),
                'created_at' => $item->created_at->format('d M Y H:i'),
            ];
        });

        return $this->successPaginated($appointments, 'Daftar janji temu');
    }

    /**
     * Detail janji temu untuk admin
     * GET /api/admin/janji-temu/{id}
     */
    public function adminShow(int $id): JsonResponse
    {
        $janjiTemu = JanjiTemu::with([
            'pegawaiTujuan:id,name,nomor_induk,kat_jabatan,dept_id,telp',
            'unitTujuan:id,nama',
            'staffPenangan:id,name',
        ])
        ->findOrFail($id);

        return $this->success([
            'id' => $janjiTemu->id,
            'nomor_induk_pengaju' => $janjiTemu->nomor_induk,
            'nama_pengaju' => $janjiTemu->nama,
            'asal' => $janjiTemu->asal,
            'waktu' => $janjiTemu->waktu_formatted,
            'waktu_raw' => $janjiTemu->waktu,
            'tujuan' => $janjiTemu->tujuan,
            'tipe' => $janjiTemu->tipe,
            'target' => $janjiTemu->tipe === 'asn'
                ? [
                    'type' => 'pegawai',
                    'nama' => $janjiTemu->pegawaiTujuan->name,
                    'nip' => $janjiTemu->pegawaiTujuan->nomor_induk,
                    'jabatan' => $janjiTemu->pegawaiTujuan->kat_jabatan ?? '-',
                    'telp' => $janjiTemu->pegawaiTujuan->telp,
                ]
                : [
                    'type' => 'satker',
                    'nama' => $janjiTemu->unitTujuan->nama,
                    'dept_id' => $janjiTemu->unitTujuan->id,
                ],
            'status' => $janjiTemu->status,
            'status_label' => $janjiTemu->status_label,
            'status_color' => $janjiTemu->status_color,
            'komen' => $janjiTemu->komen,
            'on_staff' => $janjiTemu->onStaff,
            'staff_penangan' => $janjiTemu->staffPenangan->name ?? null,
            'can_process' => $janjiTemu->canProcess(),
            'created_at' => $janjiTemu->created_at->format('d M Y H:i'),
            'updated_at' => $janjiTemu->updated_at->format('d M Y H:i'),
        ], 'Detail janji temu');
    }

    /**
     * Approve janji temu
     * PUT /api/admin/janji-temu/{id}/approve
     */
    public function approve(Request $request, int $id): JsonResponse
    {
        $user = $request->user();

        $janjiTemu = JanjiTemu::findOrFail($id);

        if (!$janjiTemu->canProcess()) {
            return $this->error('Janji temu tidak dapat diproses', 400);
        }

        $janjiTemu->update([
            'status' => 'DITERIMA',
            'onStaff' => $user->id,
            'komen' => $request->input('komen', 'Disetujui oleh petugas'),
            'updated_at' => now(),
        ]);

        // Send approval notification to user
        $this->sendApprovalNotification($janjiTemu, true);

        return $this->success([
            'id' => $janjiTemu->id,
            'status' => 'APPROVED',
            'status_label' => 'Disetujui',
        ], 'Janji temu berhasil disetujui');
    }

    /**
     * Reject janji temu
     * PUT /api/admin/janji-temu/{id}/reject
     */
    public function reject(Request $request, int $id): JsonResponse
    {
        $user = $request->user();

        $request->validate([
            'komen' => 'required|string|max:500',
        ]);

        $janjiTemu = JanjiTemu::findOrFail($id);

        if (!$janjiTemu->canProcess()) {
            return $this->error('Janji temu tidak dapat diproses', 400);
        }

        $janjiTemu->update([
            'status' => 'DITOLAK',
            'onStaff' => $user->id,
            'komen' => $request->komen,
            'updated_at' => now(),
        ]);

        // Send rejection notification to user
        $this->sendApprovalNotification($janjiTemu, false);

        return $this->success([
            'id' => $janjiTemu->id,
            'status' => 'DITOLAK',
            'status_label' => 'Ditolak',
        ], 'Janji temu berhasil ditolak');
    }

    /**
     * Assign staff to handle appointment
     * PUT /api/admin/janji-temu/{id}/assign
     */
    public function assignStaff(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'staff_id' => 'required|integer|exists:users,id',
        ]);

        $janjiTemu = JanjiTemu::findOrFail($id);

        if (!$janjiTemu->canProcess()) {
            return $this->error('Janji temu tidak dapat diproses', 400);
        }

        $staff = User::find($request->staff_id);

        $janjiTemu->update([
            'onStaff' => $staff->id,
            'updated_at' => now(),
        ]);

        // Notify assigned staff
        $this->sendAssignmentNotification($janjiTemu, $staff);

        return $this->success([
            'id' => $janjiTemu->id,
            'staff_nama' => $staff->name,
        ], 'Petugas berhasil ditugaskan');
    }

    // ═══════════════════════════════════════════════════════════════════════
    // DEPARTMENT ENDPOINTS
    // ═══════════════════════════════════════════════════════════════════════

    /**
     * List departments for janji temu
     * GET /api/janji-temu/departments
     */
    public function departments(): JsonResponse
    {
        $departments = Department::select('id', 'nama')
            ->where('status', 1)
            ->whereHas('users', function ($q) {
                $q->whereNotIn('role', ['other', 'pensiun', 'pindah']);
            })
            ->orderBy('nama')
            ->get();

        return $this->success($departments, 'Daftar unit kerja');
    }

    /**
     * List employees in department for janji temu
     * GET /api/janji-temu/departments/{id}/employees
     */
    public function departmentEmployees(int $deptId): JsonResponse
    {
        $department = Department::findOrFail($deptId);

        // Get all users in this department (excluding inactive roles)
        $users = User::where('dept_id', $deptId)
            ->whereNotIn('role', ['other', 'pensiun', 'pindah'])
            ->select('id', 'name', 'nomor_induk', 'kat_jabatan', 'pekerjaan')
            ->get();

        // Separate head and regular employees (include all variations)
        $headPositions = ['kepala', 'kasubag', 'kasubbag', 'kasi'];
        $headUsers = $users->filter(fn($user) => in_array(strtolower($user->kat_jabatan ?? ''), $headPositions));
        $regularUsers = $users->filter(fn($user) => !in_array(strtolower($user->kat_jabatan ?? ''), $headPositions));

        // Sort regular employees by name
        $regularUsers = $regularUsers->sortBy('name')->values();

        // Combine: head first, then regular employees
        $sortedUsers = $headUsers->concat($regularUsers);

        return $this->success([
            'department' => [
                'id' => $department->id,
                'nama' => $department->nama,
            ],
            'employees' => $sortedUsers->map(fn($e) => [
                'id' => $e->id,
                'name' => $e->name,
                'nomor_induk' => $e->nomor_induk,
                'jabatan' => $e->pekerjaan ?? $e->kat_jabatan ?? '-',
                'is_head' => in_array(strtolower($e->kat_jabatan ?? ''), $headPositions),
            ])->values()->toArray(),
        ], 'Daftar pegawai');
    }

    // ═══════════════════════════════════════════════════════════════════════
    // WHATSAPP NOTIFICATION HELPERS
    // ═══════════════════════════════════════════════════════════════════════

    /**
     * Send WhatsApp notification when appointment is created
     */
    private function sendWhatsAppNotification(JanjiTemu $janjiTemu): void
    {
        try {
            if ($janjiTemu->tipe === 'asn' && $janjiTemu->nip_tujuan) {
                // Notify specific pegawai
                $pegawai = User::where('nomor_induk', $janjiTemu->nip_tujuan)->first();

                if ($pegawai && $pegawai->telp) {
                    $phone = WhatsAppService::normalizePhoneNumber($pegawai->telp);
                    $message = $this->formatNewAppointmentMessage($janjiTemu, $pegawai->name);
                    $this->waService->sendMessage($phone, $message);
                }
            } elseif ($janjiTemu->tipe === 'satker') {
                // Notify operator seksi
                $dept = Department::find($janjiTemu->nip_tujuan);

                if ($dept) {
                    // Get operator/admin in this department
                    $operator = User::where('dept_id', $dept->id)
                        ->whereIn('role', ['admin', 'frontdesk'])
                        ->where('telp', '!=', null)
                        ->first();

                    if ($operator) {
                        $phone = WhatsAppService::normalizePhoneNumber($operator->telp);
                        $message = $this->formatNewSatkerAppointmentMessage($janjiTemu, $dept->nama);
                        $this->waService->sendMessage($phone, $message);
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error('Failed to send WA notification', [
                'janji_temu_id' => $janjiTemu->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Format message for new appointment to pegawai
     */
    private function formatNewAppointmentMessage(JanjiTemu $janjiTemu, string $pegawaiName): string
    {
        return "🗓️ *JANJI TEMU BARU* 🗓️\n\n".
               "Halo, {$pegawaiName}!\n\n".
               "Anda memiliki janji temu baru dari:\n\n".
               "👤 *Pengaju:* {$janjiTemu->nama}\n".
               "🏢 *Asal:* {$janjiTemu->asal}\n".
               "📅 *Waktu:* {$janjiTemu->waktu_formatted}\n".
               "📝 *Keperluan:* {$janjiTemu->tujuan}\n\n".
               "Silakan cek aplikasi SILATAR untuk detail dan konfirmasi.\n\n".
               "Terima kasih 🙏";
    }

    /**
     * Format message for new appointment to seksi/satker
     */
    private function formatNewSatkerAppointmentMessage(JanjiTemu $janjiTemu, string $deptName): string
    {
        return "📋 *JANJI TEMU KE SEKSI* 📋\n\n".
               "Halo Operator {$deptName}!\n\n".
               "Ada janji temu baru yang masuk:\n\n".
               "👤 *Pengaju:* {$janjiTemu->nama}\n".
               "🏢 *Asal:* {$janjiTemu->asal}\n".
               "📅 *Waktu:* {$janjiTemu->waktu_formatted}\n".
               "📝 *Keperluan:* {$janjiTemu->tujuan}\n\n".
               "⚠️ *Status:* Menunggu Penugasan\n\n".
               "Silakan buka aplikasi SILATAR untuk menugaskan petugas.\n\n".
               "Terima kasih 🙏";
    }

    /**
     * Send notification when appointment is approved/rejected
     */
    private function sendApprovalNotification(JanjiTemu $janjiTemu, bool $isApproved): void
    {
        try {
            $user = User::where('nomor_induk', $janjiTemu->nomor_induk)->first();

            if (!$user || !$user->telp) {
                return;
            }

            $phone = WhatsAppService::normalizePhoneNumber($user->telp);

            if ($isApproved) {
                $message = "✅ *JANJI TEMU DISETUJUI* ✅\n\n".
                          "Halo, {$user->name}!\n\n".
                          "Janji temu Anda telah *DISETUJUI*:\n\n".
                          "📅 *Waktu:* {$janjiTemu->waktu_formatted}\n".
                          "💬 *Keterangan:* {$janjiTemu->komen}\n\n".
                          "Silakan datang sesuai jadwal.\n\n".
                          "Terima kasih 🙏";
            } else {
                $message = "❌ *JANJI TEMU DITOLAK* ❌\n\n".
                          "Halo, {$user->name}!\n\n".
                          "Mohon maaf, janji temu Anda telah *DITOLAK*:\n\n".
                          "📅 *Waktu:* {$janjiTemu->waktu_formatted}\n".
                          "💬 *Alasan:* {$janjiTemu->komen}\n\n".
                          "Silakan hubungi kami untuk informasi lebih lanjut.\n\n".
                          "Terima kasih 🙏";
            }

            $this->waService->sendMessage($phone, $message);

        } catch (\Exception $e) {
            Log::error('Failed to send WA approval notification', [
                'janji_temu_id' => $janjiTemu->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Send notification when appointment is cancelled
     */
    private function sendCancellationNotification(JanjiTemu $janjiTemu): void
    {
        try {
            $staff = User::find($janjiTemu->onStaff);

            if (!$staff || !$staff->telp) {
                return;
            }

            $phone = WhatsAppService::normalizePhoneNumber($staff->telp);
            $message = "🚫 *JANJI TEMU DIBATALKAN* 🚫\n\n".
                       "Halo, {$staff->name}!\n\n".
                       "Janji temu dari *{$janjiTemu->nama}* telah *DIBATALKAN*:\n\n".
                       "📅 *Waktu:* {$janjiTemu->waktu_formatted}\n".
                       "💬 *Alasan:* {$janjiTemu->komen}\n\n".
                       "Anda tidak perlu memproses janji temu ini.\n\n".
                       "Terima kasih 🙏";

            $this->waService->sendMessage($phone, $message);

        } catch (\Exception $e) {
            Log::error('Failed to send WA cancellation notification', [
                'janji_temu_id' => $janjiTemu->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Send notification when staff is assigned
     */
    private function sendAssignmentNotification(JanjiTemu $janjiTemu, User $staff): void
    {
        try {
            if (!$staff->telp) {
                return;
            }

            $phone = WhatsAppService::normalizePhoneNumber($staff->telp);
            $message = "📌 *PENUGASAN JANJI TEMU* 📌\n\n".
                       "Halo, {$staff->name}!\n\n".
                       "Anda ditugaskan menangani janji temu:\n\n".
                       "👤 *Pengaju:* {$janjiTemu->nama}\n".
                       "🏢 *Asal:* {$janjiTemu->asal}\n".
                       "📅 *Waktu:* {$janjiTemu->waktu_formatted}\n".
                       "📝 *Keperluan:* {$janjiTemu->tujuan}\n\n".
                       "Silakan cek aplikasi SILATAR untuk detail lengkap.\n\n".
                       "Terima kasih 🙏";

            $this->waService->sendMessage($phone, $message);

        } catch (\Exception $e) {
            Log::error('Failed to send WA assignment notification', [
                'janji_temu_id' => $janjiTemu->id,
                'staff_id' => $staff->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
