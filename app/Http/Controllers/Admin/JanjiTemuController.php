<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JanjiTemu;
use App\Models\User;
use App\Models\Department;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class JanjiTemuController extends Controller
{
    protected WhatsAppService $waService;

    public function __construct(WhatsAppService $waService)
    {
        $this->waService = $waService;
    }

    /**
     * Display a listing of janji temu appointments.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $isAdmin = in_array($user->role, ['superadmin', 'admin']);
        $status = $request->input('status');
        $search = $request->input('search');

        $query = DB::table('ktd_bukutamu');

        // Filter based on role
        if (!$isAdmin) {
            $query->where(function ($q) use ($user) {
                // Appointments directed to this user (tipe asn)
                $q->where(function ($sub) use ($user) {
                    $sub->where('tipe', 'asn')
                        ->where('nip_tujuan', $user->nomor_induk);
                })
                // Or appointments directed to user's department (tipe satker)
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

        $janjiTemuList = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.janji-temu-index', compact('janjiTemuList', 'status', 'search'));
    }

    /**
     * Display the specified janji temu appointment.
     */
    public function show($id)
    {
        $janjiTemu = DB::table('ktd_bukutamu')->where('id', $id)->first();

        if (!$janjiTemu) {
            return redirect()->route('admin.janji-temu')
                ->with('error', 'Janji temu tidak ditemukan');
        }

        // Get target info
        $targetNama = '-';
        $targetDetail = '-';
        $targetTelp = null;

        if ($janjiTemu->tipe === 'asn' && $janjiTemu->nip_tujuan) {
            $pegawai = DB::table('users')
                ->where('nomor_induk', $janjiTemu->nip_tujuan)
                ->first();

            if ($pegawai) {
                $targetNama = $pegawai->name;
                $targetDetail = $pegawai->kat_jabatan ?? '-';
                $targetTelp = $pegawai->telp;
            }
        } elseif ($janjiTemu->tipe === 'satker' && $janjiTemu->nip_tujuan) {
            $dept = DB::table('ktd_department')
                ->where('id', $janjiTemu->nip_tujuan)
                ->first();

            if ($dept) {
                $targetNama = $dept->nama;
                $targetDetail = 'Unit Kerja';
            }
        }

        // Get staff info
        $staffNama = '-';
        if ($janjiTemu->onStaff && $janjiTemu->onStaff != 999) {
            $staff = DB::table('users')->where('id', $janjiTemu->onStaff)->first();
            $staffNama = $staff->name ?? '-';
        }

        return view('admin.janji-temu-detail', compact('janjiTemu', 'targetNama', 'targetDetail', 'targetTelp', 'staffNama'));
    }

    /**
     * Approve janji temu appointment.
     */
    public function approve($id, Request $request)
    {
        $user = $request->user();

        $appointment = DB::table('ktd_bukutamu')->where('id', $id)->first();

        if (!$appointment) {
            return redirect()->route('admin.janji-temu')
                ->with('error', 'Janji temu tidak ditemukan');
        }

        if (!in_array($appointment->status, ['APPOINTMENT', 'PENDING'])) {
            return redirect()->route('admin.janji-temu')
                ->with('error', 'Janji temu tidak dapat diproses');
        }

        DB::table('ktd_bukutamu')
            ->where('id', $id)
            ->update([
                'status' => 'DITERIMA',
                'onStaff' => $user->id,
                'komen' => $request->input('komen', 'Disetujui oleh petugas'),
                'updated_at' => now(),
            ]);

        // Send notification
        $this->sendApprovalNotification($appointment, true);

        return redirect()->route('admin.janji-temu.show', $id)
            ->with('success', 'Janji temu berhasil disetujui');
    }

    /**
     * Reject janji temu appointment.
     */
    public function reject($id, Request $request)
    {
        $user = $request->user();

        $request->validate([
            'komen' => 'required|string|max:500',
        ]);

        $appointment = DB::table('ktd_bukutamu')->where('id', $id)->first();

        if (!$appointment) {
            return redirect()->route('admin.janji-temu')
                ->with('error', 'Janji temu tidak ditemukan');
        }

        if (!in_array($appointment->status, ['APPOINTMENT', 'PENDING'])) {
            return redirect()->route('admin.janji-temu')
                ->with('error', 'Janji temu tidak dapat diproses');
        }

        DB::table('ktd_bukutamu')
            ->where('id', $id)
            ->update([
                'status' => 'DITOLAK',
                'onStaff' => $user->id,
                'komen' => $request->komen,
                'updated_at' => now(),
            ]);

        // Send notification
        $this->sendApprovalNotification($appointment, false);

        return redirect()->route('admin.janji-temu.show', $id)
            ->with('success', 'Janji temu berhasil ditolak');
    }

    /**
     * Send approval/rejection notification to user
     */
    private function sendApprovalNotification($appointment, bool $isApproved)
    {
        try {
            $user = DB::table('users')
                ->where('nomor_induk', $appointment->nomor_induk)
                ->where('telp', '!=', null)
                ->first();

            if (!$user || !$user->telp) {
                return;
            }

            $phone = WhatsAppService::normalizePhoneNumber($user->telp);
            $waktuFormatted = \Carbon\Carbon::parse($appointment->waktu)->format('d M Y, H:i');

            if ($isApproved) {
                $message = "✅ *JANJI TEMU DISETUJUI* ✅\n\n".
                          "Halo, {$user->name}!\n\n".
                          "Janji temu Anda telah *DISETUJUI*:\n\n".
                          "📅 *Waktu:* {$waktuFormatted}\n".
                          "💬 *Keterangan:* {$appointment->komen}\n\n".
                          "Silakan datang sesuai jadwal.\n\n".
                          "Terima kasih 🙏";
            } else {
                $message = "❌ *JANJI TEMU DITOLAK* ❌\n\n".
                          "Halo, {$user->name}!\n\n".
                          "Mohon maaf, janji temu Anda telah *DITOLAK*:\n\n".
                          "📅 *Waktu:* {$waktuFormatted}\n".
                          "💬 *Alasan:* {$appointment->komen}\n\n".
                          "Silakan hubungi kami untuk informasi lebih lanjut.\n\n".
                          "Terima kasih 🙏";
            }

            $this->waService->sendMessage($phone, $message);
        } catch (\Exception $e) {
            Log::error('Failed to send WA notification', ['error' => $e->getMessage()]);
        }
    }
}
