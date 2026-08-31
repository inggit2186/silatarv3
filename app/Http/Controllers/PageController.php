<?php

namespace App\Http\Controllers;

use App\Models\SatkerPemberkasan;
use App\Services\WhatsAppService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function home()
    {
        // Fetch slideshow news (is_slideshow = 1) - ONLY slideshow news, no fill
        $slideshowNews = DB::table('news')
            ->where('status', 'published')
            ->where('is_slideshow', 1)
            ->orderByDesc('publish_date')
            ->limit(5)
            ->get();

        // Fetch featured news (is_featured = 1) - ONLY featured news, max 3
        $featuredNews = DB::table('news')
            ->where('status', 'published')
            ->where('is_featured', 1)
            ->orderByDesc('publish_date')
            ->limit(3)
            ->get();

        // Fetch latest news - max 6 latest published news
        $latestNews = DB::table('news')
            ->where('status', 'published')
            ->orderByDesc('publish_date')
            ->limit(6)
            ->get();

        return view('welcome', [
            'slideshowNews' => $slideshowNews,
            'featuredNews' => $featuredNews,
            'latestNews' => $latestNews,
        ]);
    }

    public function allNews(Request $request)
    {
        $query = DB::table('news')
            ->where('status', 'published')
            ->orderByDesc('publish_date');

        // Filter by category if provided
        if ($request->has('category') && $request->category) {
            $query->where('category', $request->category);
        }

        $allNews = $query->paginate(12);

        // Get unique categories for filter
        $categories = DB::table('news')
            ->where('status', 'published')
            ->select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view('news.index', [
            'news' => $allNews,
            'categories' => $categories,
            'selectedCategory' => $request->category,
        ]);
    }

    public function newsShow($slug)
    {
        // Try to find by slug first, then by id
        $news = DB::table('news')
            ->where('slug', $slug)
            ->orWhere('id', $slug)
            ->first();

        if (!$news || $news->status !== 'published') {
            abort(404);
        }

        // Track view
        $this->trackNewsView($news->id);

        // Get related news (same category, excluding current)
        $relatedNews = DB::table('news')
            ->where('status', 'published')
            ->where('category', $news->category)
            ->where('id', '!=', $news->id)
            ->orderByDesc('publish_date')
            ->limit(3)
            ->get();

        // Get updated view counts
        $newsStats = DB::table('news')->where('id', $news->id)->first(['view_count', 'unique_view_count']);

        return view('news.show', [
            'news' => $news,
            'relatedNews' => $relatedNews,
            'viewCount' => $newsStats->view_count ?? 0,
            'uniqueViewCount' => $newsStats->unique_view_count ?? 0,
        ]);
    }

    /**
     * Track news view.
     */
    private function trackNewsView(int $newsId): void
    {
        $ipAddress = request()->ip();
        $sessionId = session()->getId();
        $userAgent = request()->userAgent();
        $viewedAt = now();

        // Check if this IP/session already viewed this news today
        $alreadyViewed = DB::table('news_views')
            ->where('news_id', $newsId)
            ->where('ip_address', $ipAddress)
            ->whereDate('viewed_at', $viewedAt->toDateString())
            ->exists();

        if ($alreadyViewed) {
            // Only increment total view count
            DB::table('news')->where('id', $newsId)->increment('view_count');
        } else {
            // Increment both view counts
            DB::table('news')->where('id', $newsId)->increment('view_count');
            DB::table('news')->where('id', $newsId)->increment('unique_view_count');

            // Log the unique view
            DB::table('news_views')->insert([
                'news_id' => $newsId,
                'ip_address' => $ipAddress,
                'session_id' => $sessionId,
                'user_agent' => $userAgent,
                'viewed_at' => $viewedAt,
            ]);
        }
    }

    public function pelayanan()
    {
        $kantorUnits = $this->departmentSelection('kantor', 1)->values();

        return view('pelayanan', [
            'kantorUnits' => $kantorUnits,
            'selectedKantorUnitId' => null,
            'generalServices' => $this->generalServices(),
            'specialServicesByUnit' => $this->specialServicesByUnit($kantorUnits->pluck('id')->all()),
        ]);
    }

    public function janjiTemu(int $deptId, Request $request)
    {
        $dept = DB::table('ktd_department')->where('id', $deptId)->first();
        abort_unless($dept, 404);

        $user = auth()->user();
        $employeeId = $request->query('employee_id');
        $isDirect = $request->query('direct') == '1';

        $targetData = null;

        if ($employeeId) {
            $employee = DB::table('users')->where('id', $employeeId)->first();
            if ($employee) {
                $targetData = [
                    'type' => 'employee',
                    'employee_id' => $employee->id,
                    'employee_name' => $employee->name,
                    'employee_role' => $this->personLabel($employee),
                    'employee_nip' => $employee->nomor_induk,
                    'employee_photo' => $this->personPhotoUrl($employee),
                ];
            }
        } elseif ($isDirect) {
            $targetData = [
                'type' => 'direct',
                'employee_id' => null,
                'employee_name' => 'Langsung ke Seksi',
                'employee_role' => 'Tanpa Pegawai Tertentu',
                'employee_nip' => null,
                'employee_photo' => asset('assets/img/ikon/505.webp'),
            ];
        }

        return view('janji-temu', [
            'deptId' => $deptId,
            'deptName' => $dept->nama,
            'targetData' => $targetData,
            'requester' => [
                'name' => $user?->name,
                'identity' => $user?->nomor_induk ?? '',
            ],
        ]);
    }

    public function submitJanjiTemu(int $deptId, Request $request)
    {
        $user = auth()->user();
        abort_unless($user, 403);

        $validator = Validator::make($request->all(), [
            'tanggal' => ['required'],
            'jam' => ['required'],
            'keterangan' => ['required', 'max:1000'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();
        $waktu = $data['tanggal'] . ' ' . $data['jam'] . ':00';

        $userNip = $user->nomor_induk ?? null;
        $userName = $user->name ?? '-';
        $userSatker = $user->satker ?? null;
        $tipe = $request->input('tipe', 'asn');
        $tujuan = $data['keterangan'];

        if($tipe === 'direct') {
            $tipex = 'satker';
        }else{
            $tipex = 'asn';
        }

        $insertData = [
            'nomor_induk' => $userNip,
            'kategori' => $user->role ?? 'public',
            'tipe' => $tipex,
            'nama' => $userName,
            'waktu' => $waktu,
            'nip_tujuan' => $request->input('nip_tujuan'),
            'tujuan' => $tujuan,
            'asal' => $userSatker,
            'status' => 'APPOINTMENT',
            'onStaff' => 999,
            'komen' => null,
            'ttd' => 'NONE',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $janjiTemuId = DB::table('ktd_bukutamu')->insertGetId($insertData);

        // Kirim WhatsApp notification
        $this->sendWhatsAppNotification(
            $janjiTemuId,
            $tipex,
            $request->input('nip_tujuan'),
            $userName,
            $userSatker,
            $waktu,
            $tujuan
        );

        return redirect()->route('pelayanan')->with('success', 'Janji temu berhasil diajukan.');
    }

    /**
     * Kirim WhatsApp notification ke tujuan tamu
     */
    private function sendWhatsAppNotification(
        int $janjiTemuId,
        string $tipe,
        ?string $nipTujuan,
        string $namaPengaju,
        ?string $asal,
        string $waktu,
        string $keterangan
    ): void {
        try {
            $waService = new WhatsAppService();
            $waktuFormatted = Carbon::parse($waktu)->format('d M Y, H:i');

            if ($tipe === 'asn' && $nipTujuan) {
                // Notify pegawai tujuan
                $pegawai = DB::table('users')
                    ->where('nomor_induk', $nipTujuan)
                    ->where('telp', '!=', null)
                    ->first();

                if ($pegawai) {
                    $phone = WhatsAppService::normalizePhoneNumber($pegawai->telp);
                    $message = "🗓️ *JANJI TEMU BARU* 🗓️\n\n".
                               "Halo, {$pegawai->name}!\n\n".
                               "Anda memiliki janji temu baru dari:\n\n".
                               "👤 *Pengaju:* {$namaPengaju}\n".
                               "🏢 *Asal:* {$asal}\n".
                               "📅 *Waktu:* {$waktuFormatted}\n".
                               "📝 *Keperluan:* {$keterangan}\n\n".
                               "Silakan cek aplikasi SILATAR untuk detail dan konfirmasi.\n\n".
                               "Terima kasih 🙏";

                    $waService->sendMessage($phone, $message);
                }
            } elseif ($tipe === 'satker' && $nipTujuan) {
                // Notify operator seksi
                $dept = DB::table('ktd_department')
                    ->where('id', $nipTujuan)
                    ->first();

                if ($dept) {
                    // Get operator/admin in this department
                    $operator = DB::table('users')
                        ->where('dept_id', $nipTujuan)
                        ->whereIn('role', ['admin', 'frontdesk'])
                        ->where('telp', '!=', null)
                        ->first();

                    if ($operator) {
                        $phone = WhatsAppService::normalizePhoneNumber($operator->telp);
                        $message = "📋 *JANJI TEMU KE SEKSI* 📋\n\n".
                                   "Halo Operator {$dept->nama}!\n\n".
                                   "Ada janji temu baru yang masuk:\n\n".
                                   "👤 *Pengaju:* {$namaPengaju}\n".
                                   "🏢 *Asal:* {$asal}\n".
                                   "📅 *Waktu:* {$waktuFormatted}\n".
                                   "📝 *Keperluan:* {$keterangan}\n\n".
                                   "⚠️ *Status:* Menunggu Penugasan\n\n".
                                   "Silakan buka aplikasi SILATAR untuk menugaskan petugas.\n\n".
                                   "Terima kasih 🙏";

                        $waService->sendMessage($phone, $message);
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error('Failed to send WA notification for janji temu', [
                'janji_temu_id' => $janjiTemuId,
                'error' => $e->getMessage(),
            ]);
        }
    }

    // ═══════════════════════════════════════════════════════════════════════
    // JANJI TEMU - History & Detail
    // ═══════════════════════════════════════════════════════════════════════

    /**
     * Halaman history janji temu user
     */
    public function janjiTemuHistory()
    {
        $user = auth()->user();
        abort_unless($user, 403);

        $janjiTemuList = DB::table('ktd_bukutamu')
            ->where('nomor_induk', $user->nomor_induk)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('janji-temu-history', [
            'user' => $user,
            'janjiTemuList' => $janjiTemuList,
        ]);
    }

    /**
     * Halaman detail janji temu
     */
    public function janjiTemuDetail(int $id)
    {
        $user = auth()->user();
        abort_unless($user, 403);

        $janjiTemu = DB::table('ktd_bukutamu')
            ->where('id', $id)
            ->where('nomor_induk', $user->nomor_induk)
            ->first();

        abort_unless($janjiTemu, 404);

        // Get target info
        $targetNama = '-';
        $targetDetail = '-';
        $targetPhoto = null;

        if ($janjiTemu->tipe === 'asn' && $janjiTemu->nip_tujuan) {
            $pegawai = DB::table('users')
                ->where('nomor_induk', $janjiTemu->nip_tujuan)
                ->first();

            if ($pegawai) {
                $targetNama = $pegawai->name;
                $targetDetail = $pegawai->kat_jabatan ?? '-';
                $targetPhoto = $pegawai->foto ?? null;
            }
        } elseif ($janjiTemu->tipe === 'satker' && $janjiTemu->nip_tujuan) {
            $dept = DB::table('ktd_department')
                ->where('id', $janjiTemu->nip_tujuan)
                ->first();

            if ($dept) {
                $targetNama = $dept->nama;
                $targetDetail = 'Langsung ke Seksi';
            }
        }

        // Get staff penangan
        $staffNama = '-';
        if ($janjiTemu->onStaff && $janjiTemu->onStaff != 999) {
            $staff = DB::table('users')
                ->where('id', $janjiTemu->onStaff)
                ->first();
            $staffNama = $staff->name ?? '-';
        }

        return view('janji-temu-detail', [
            'user' => $user,
            'janjiTemu' => $janjiTemu,
            'targetNama' => $targetNama,
            'targetDetail' => $targetDetail,
            'targetPhoto' => $targetPhoto,
            'staffNama' => $staffNama,
        ]);
    }

    /**
     * Batalkan janji temu
     */
    public function janjiTemuCancel(int $id, Request $request)
    {
        $user = auth()->user();
        abort_unless($user, 403);

        $janjiTemu = DB::table('ktd_bukutamu')
            ->where('id', $id)
            ->where('nomor_induk', $user->nomor_induk)
            ->first();

        abort_unless($janjiTemu, 404);

        // Check if can cancel
        if (!in_array($janjiTemu->status, ['APPOINTMENT', 'PENDING'])) {
            return back()->with('error', 'Janji temu tidak dapat dibatalkan');
        }

        $alasan = $request->input('alasan', 'Dibatalkan oleh pengguna');

        DB::table('ktd_bukutamu')
            ->where('id', $id)
            ->update([
                'status' => 'CANCELLED',
                'komen' => $alasan,
                'updated_at' => now(),
            ]);

        // Notify staff if assigned
        if ($janjiTemu->onStaff && $janjiTemu->onStaff != 999) {
            $this->sendCancelNotification($janjiTemu, $alasan);
        }

        return redirect()->route('janji-temu-history')
            ->with('success', 'Janji temu berhasil dibatalkan');
    }

    /**
     * Kirim notifikasi pembatalan ke staff
     */
    private function sendCancelNotification(object $janjiTemu, string $alasan): void
    {
        try {
            $staff = DB::table('users')
                ->where('id', $janjiTemu->onStaff)
                ->where('telp', '!=', null)
                ->first();

            if (!$staff) {
                return;
            }

            $waService = new WhatsAppService();
            $phone = WhatsAppService::normalizePhoneNumber($staff->telp);
            $waktuFormatted = Carbon::parse($janjiTemu->waktu)->format('d M Y, H:i');

            $message = "🚫 *JANJI TEMU DIBATALKAN* 🚫\n\n".
                       "Halo, {$staff->name}!\n\n".
                       "Janji temu dari *{$janjiTemu->nama}* telah *DIBATALKAN*:\n\n".
                       "📅 *Waktu:* {$waktuFormatted}\n".
                       "💬 *Alasan:* {$alasan}\n\n".
                       "Anda tidak perlu memproses janji temu ini.\n\n".
                       "Terima kasih 🙏";

            $waService->sendMessage($phone, $message);
        } catch (\Exception $e) {
            Log::error('Failed to send cancel notification', ['error' => $e->getMessage()]);
        }
    }

    // ═══════════════════════════════════════════════════════════════════════
    // JANJI TEMU - Admin/Staff
    // ═══════════════════════════════════════════════════════════════════════

    /**
     * Halaman admin - list janji temu
     */
    public function adminJanjiTemu()
    {
        $user = auth()->user();
        abort_unless($user, 403);

        $isAdmin = in_array($user->role, ['superadmin', 'admin']);

        $query = DB::table('ktd_bukutamu');

        // Filter by department if not admin
        if (!$isAdmin) {
            $query->where(function ($q) use ($user) {
                $q->where('nip_tujuan', $user->dept_id)
                  ->orWhere('onStaff', $user->id);
            });
        }

        $janjiTemuList = $query->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.janji-temu-index', [
            'user' => $user,
            'janjiTemuList' => $janjiTemuList,
        ]);
    }

    /**
     * Halaman admin - detail janji temu
     */
    public function adminJanjiTemuDetail(int $id)
    {
        $user = auth()->user();
        abort_unless($user, 403);

        $janjiTemu = DB::table('ktd_bukutamu')
            ->where('id', $id)
            ->first();

        abort_unless($janjiTemu, 404);

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

        // Get staff penangan
        $staffNama = '-';
        if ($janjiTemu->onStaff && $janjiTemu->onStaff != 999) {
            $staff = DB::table('users')
                ->where('id', $janjiTemu->onStaff)
                ->first();
            $staffNama = $staff->name ?? '-';
        }

        return view('admin.janji-temu-detail', [
            'user' => $user,
            'janjiTemu' => $janjiTemu,
            'targetNama' => $targetNama,
            'targetDetail' => $targetDetail,
            'targetTelp' => $targetTelp,
            'staffNama' => $staffNama,
        ]);
    }

    /**
     * Approve janji temu
     */
    public function adminJanjiTemuApprove(int $id, Request $request)
    {
        $user = auth()->user();
        abort_unless($user, 403);

        $janjiTemu = DB::table('ktd_bukutamu')
            ->where('id', $id)
            ->first();

        abort_unless($janjiTemu, 404);

        if (!in_array($janjiTemu->status, ['APPOINTMENT', 'PENDING'])) {
            return back()->with('error', 'Janji temu tidak dapat diproses');
        }

        $komen = $request->input('komen', 'Disetujui oleh petugas');

        DB::table('ktd_bukutamu')
            ->where('id', $id)
            ->update([
                'status' => 'APPROVED',
                'onStaff' => $user->id,
                'komen' => $komen,
                'updated_at' => now(),
            ]);

        // Send approval notification
        $this->sendApprovalNotification($janjiTemu, true, $komen);

        return redirect()->route('admin.janji-temu.detail', $id)
            ->with('success', 'Janji temu berhasil disetujui');
    }

    /**
     * Reject janji temu
     */
    public function adminJanjiTemuReject(int $id, Request $request)
    {
        $user = auth()->user();
        abort_unless($user, 403);

        $validator = Validator::make($request->all(), [
            'komen' => ['required', 'max:500'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $janjiTemu = DB::table('ktd_bukutamu')
            ->where('id', $id)
            ->first();

        abort_unless($janjiTemu, 404);

        if (!in_array($janjiTemu->status, ['APPOINTMENT', 'PENDING'])) {
            return back()->with('error', 'Janji temu tidak dapat diproses');
        }

        DB::table('ktd_bukutamu')
            ->where('id', $id)
            ->update([
                'status' => 'REJECTED',
                'onStaff' => $user->id,
                'komen' => $request->komen,
                'updated_at' => now(),
            ]);

        // Send rejection notification
        $this->sendApprovalNotification($janjiTemu, false, $request->komen);

        return redirect()->route('admin.janji-temu.detail', $id)
            ->with('success', 'Janji temu berhasil ditolak');
    }

    /**
     * Kirim notifikasi approve/reject ke pengaju
     */
    private function sendApprovalNotification(object $janjiTemu, bool $isApproved, string $komen): void
    {
        try {
            $pengaju = DB::table('users')
                ->where('nomor_induk', $janjiTemu->nomor_induk)
                ->where('telp', '!=', null)
                ->first();

            if (!$pengaju) {
                return;
            }

            $waService = new WhatsAppService();
            $phone = WhatsAppService::normalizePhoneNumber($pengaju->telp);
            $waktuFormatted = Carbon::parse($janjiTemu->waktu)->format('d M Y, H:i');

            if ($isApproved) {
                $message = "✅ *JANJI TEMU DISETUJUI* ✅\n\n".
                          "Halo, {$pengaju->name}!\n\n".
                          "Janji temu Anda telah *DISETUJUI*:\n\n".
                          "📅 *Waktu:* {$waktuFormatted}\n".
                          "💬 *Keterangan:* {$komen}\n\n".
                          "Silakan datang sesuai jadwal.\n\n".
                          "Terima kasih 🙏";
            } else {
                $message = "❌ *JANJI TEMU DITOLAK* ❌\n\n".
                          "Halo, {$pengaju->name}!\n\n".
                          "Mohon maaf, janji temu Anda telah *DITOLAK*:\n\n".
                          "📅 *Waktu:* {$waktuFormatted}\n".
                          "💬 *Alasan:* {$komen}\n\n".
                          "Silakan hubungi kami untuk informasi lebih lanjut.\n\n".
                          "Terima kasih 🙏";
            }

            $waService->sendMessage($phone, $message);
        } catch (\Exception $e) {
            Log::error('Failed to send approval notification', ['error' => $e->getMessage()]);
        }
    }

    public function whistleblowing()
    {
        $user = auth()->user();
        return view('whistleblowing', [
            'user' => $user,
        ]);
    }

    public function submitWhistleblowing(Request $request)
    {
        $user = auth()->user();

        $validator = Validator::make($request->all(), [
            'judul' => ['required', 'max:244'],
            'keterangan' => ['required', 'max:5000'],
            'email' => ['required', 'email', 'max:255'],
            'telp' => ['nullable', 'max:255'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();

        // Generate kode
        $kode = 'PENGADUAN' . date('Ymd') . str_pad(mt_rand(1, 999), 3, '0', STR_PAD_LEFT);

        $insertData = [
            'kode' => $kode,
            'jenis' => 'PENGADUAN',
            'user_nip' => $user->nomor_induk ?? 0,
            'nama' => $user->name ?? '-',
            'email' => $data['email'],
            'telp' => $data['telp'] ?? null,
            'judul' => $data['judul'],
            'keterangan' => $data['keterangan'],
            'filename' => null,
            'status' => 'PENDING',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        DB::table('ktd_pengaduan')->insert($insertData);

        return redirect()->route('pelayanan')->with('success', 'Pengaduan berhasil diajukan. Kami akan segera memproses laporan Anda.');
    }

    public function unitEmployees(int $deptId)
    {
        $departmentRow = DB::table('ktd_department')->where('id', $deptId)->first();

        // Get leaders (kepala/kasi)
        $head = $this->departmentHead($deptId, $departmentRow->kategori ?? 'kantor', $departmentRow->nama ?? '');
        $pltHead = $head ? null : $this->departmentPltHead($deptId);
        $leader = $head ?? $pltHead;
        $leaderLabel = $head ? $this->personLabel($head) : ($pltHead ? 'PLT Kepala' : 'Kepala');
        $isPlt = ! $head && $pltHead;

        $leaders = [];
        if ($leader) {
            $leaders[] = [
                'id' => $leader->id,
                'name' => $leader->name,
                'role_label' => $leaderLabel,
                'is_plt' => $isPlt,
                'nomor_induk' => $leader->nomor_induk ?? '-',
                'avatar_text' => $this->personInitials($leader->name),
                'photo_path' => $this->personPhotoUrl($leader),
            ];
        }

        // Get regular employees (excluding leaders)
        $excludeIds = array_filter([$head?->id, $pltHead?->id]);
        $employees = DB::table('users')
            ->where('dept_id', $deptId)
            ->whereNotIn('role', ['other', 'pensiun', 'pindah'])
            ->when($excludeIds, fn ($q) => $q->whereNotIn('id', $excludeIds))
            ->orderByRaw("FIELD(kat_jabatan, 'kaur', 'kasubag', 'pelaksana', 'staf', 'honorer', 'guru')")
            ->orderBy('name', 'asc')
            ->get()
            ->map(function ($item) {
                $isKaur = strtolower($item->kat_jabatan ?? '') === 'kaur';
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'role_label' => $this->personLabel($item),
                    'nomor_induk' => $item->nomor_induk ?? '-',
                    'avatar_text' => $this->personInitials($item->name),
                    'photo_path' => $this->personPhotoUrl($item),
                    'is_kaur' => $isKaur,
                ];
            })
            ->values()
            ->all();

        return response()->json([
            'leaders' => $leaders,
            'employees' => $employees,
            'dept_id' => $deptId,
        ]);
    }

    public function requestService(int $serviceId, Request $request = null)
    {
        $service = $this->serviceDetail($serviceId);
        $requester = auth()->user();

        $appointmentData = null;
        $req = $request ?? request();
        $employeeId = $req->query('employee_id');
        $isDirect = $req->query('direct') === 'true';
        $tahunPelajaran = $req->query('tahun_pelajaran');
        $semester = $req->query('semester');
        $tahun = $req->query('tahun'); // For TPG Bulanan (1038)
        $bulan = $req->query('bulan');

        // For TPG Semester service (1037), check for existing submission
        $existingSubmission = null;
        $existingFiles = [];
        if ($serviceId === 1037 && $tahunPelajaran && $semester) {
            // Replace "/" with "-" in tahunPelajaran for file-safe noreq
            $tpSafe = str_replace('/', '-', $tahunPelajaran);
            $existingNoreq = strtoupper("PAIS-TPG-SEMESTER-{$requester->id}-{$tpSafe}-{$semester}");
            $existingSubmission = DB::table('satker_pemberkasan')
                ->where('noreq', $existingNoreq)
                ->first();

            if ($existingSubmission) {
                // Decode files JSON snapshot (may be double-encoded: string -> JSON string -> array)
                $filesRaw = $existingSubmission->files ?? null;
                if (is_array($filesRaw)) {
                    $filesData = $filesRaw;
                } elseif (is_string($filesRaw)) {
                    $decoded = json_decode($filesRaw, true);
                    // Handle double-encoded JSON (e.g., '"[...]"' becomes '[...]' as string)
                    if (is_string($decoded)) {
                        $decoded = json_decode($decoded, true);
                    }
                    $filesData = is_array($decoded) ? $decoded : [];
                } else {
                    $filesData = [];
                }
                foreach ($filesData as $file) {
                    $syaratId = $file['syarat_id'] ?? 0;
                    if ($file['filename'] && $file['filename'] !== 'NONE') {
                        $existingFiles[$syaratId] = [
                            'filename' => $file['filename'],
                            'filetype' => $file['filetype'] ?? null,
                            'size' => $file['size'] ?? null,
                            'url' => route('pelayanan.tpg.preview-file', [
                                'pemberkasanId' => $existingSubmission->id,
                                'syaratId' => $syaratId,
                            ]),
                        ];
                    }
                }
            }
        }

        // For TPG Bulanan service (1038), check for existing submission
        if ($serviceId === 1038 && $tahun && $bulan) {
            // Use tahun directly for noreq (no "/" to replace)
            $existingNoreq = strtoupper("PAIS-TPG-BULANAN-{$requester->id}-{$tahun}-{$bulan}");
            $existingSubmission = DB::table('satker_pemberkasan')
                ->where('noreq', $existingNoreq)
                ->first();

            if ($existingSubmission) {
                // Decode files JSON snapshot (may be double-encoded)
                $filesRaw = $existingSubmission->files ?? null;
                if (is_array($filesRaw)) {
                    $filesData = $filesRaw;
                } elseif (is_string($filesRaw)) {
                    $decoded = json_decode($filesRaw, true);
                    if (is_string($decoded)) {
                        $decoded = json_decode($decoded, true);
                    }
                    $filesData = is_array($decoded) ? $decoded : [];
                } else {
                    $filesData = [];
                }
                foreach ($filesData as $file) {
                    $syaratId = $file['syarat_id'] ?? 0;
                    if ($file['filename'] && $file['filename'] !== 'NONE') {
                        $existingFiles[$syaratId] = [
                            'filename' => $file['filename'],
                            'filetype' => $file['filetype'] ?? null,
                            'size' => $file['size'] ?? null,
                            'url' => route('pelayanan.tpg-bulanan.preview-file', [
                                'pemberkasanId' => $existingSubmission->id,
                                'syaratId' => $syaratId,
                            ]),
                        ];
                    }
                }
            }
        }

        // For PENMAD TPG Bulanan service (1081), check for existing submission
        if ($serviceId === 1081 && $tahun && $bulan) {
            // Use tahun directly for noreq (no "/" to replace)
            $existingNoreq = strtoupper("PENMAD-TPG-BULANAN-{$requester->id}-{$tahun}-{$bulan}");
            $existingSubmission = DB::table('satker_pemberkasan')
                ->where('noreq', $existingNoreq)
                ->first();

            if ($existingSubmission) {
                // Decode files JSON snapshot (may be double-encoded)
                $filesRaw = $existingSubmission->files ?? null;
                if (is_array($filesRaw)) {
                    $filesData = $filesRaw;
                } elseif (is_string($filesRaw)) {
                    $decoded = json_decode($filesRaw, true);
                    if (is_string($decoded)) {
                        $decoded = json_decode($decoded, true);
                    }
                    $filesData = is_array($decoded) ? $decoded : [];
                } else {
                    $filesData = [];
                }
                foreach ($filesData as $file) {
                    $syaratId = $file['syarat_id'] ?? 0;
                    if ($file['filename'] && $file['filename'] !== 'NONE') {
                        $existingFiles[$syaratId] = [
                            'filename' => $file['filename'],
                            'filetype' => $file['filetype'] ?? null,
                            'size' => $file['size'] ?? null,
                            'url' => route('pelayanan.penmad-tpg-bulanan.preview-file', [
                                'pemberkasanId' => $existingSubmission->id,
                                'syaratId' => $syaratId,
                            ]),
                        ];
                    }
                }
            }
        }

        // For PENMAD Pengawas Bulanan service (1082), check for existing submission
        if ($serviceId === 1082 && $tahun && $bulan) {
            // Use tahun directly for noreq (no "/" to replace)
            $existingNoreq = strtoupper("PENMAD-PENGAWAS-BULANAN-{$requester->id}-{$tahun}-{$bulan}");
            $existingSubmission = DB::table('satker_pemberkasan')
                ->where('noreq', $existingNoreq)
                ->first();

            if ($existingSubmission) {
                // Decode files JSON snapshot (may be double-encoded)
                $filesRaw = $existingSubmission->files ?? null;
                if (is_array($filesRaw)) {
                    $filesData = $filesRaw;
                } elseif (is_string($filesRaw)) {
                    $decoded = json_decode($filesRaw, true);
                    if (is_string($decoded)) {
                        $decoded = json_decode($decoded, true);
                    }
                    $filesData = is_array($decoded) ? $decoded : [];
                } else {
                    $filesData = [];
                }
                foreach ($filesData as $file) {
                    $syaratId = $file['syarat_id'] ?? 0;
                    if ($file['filename'] && $file['filename'] !== 'NONE') {
                        $existingFiles[$syaratId] = [
                            'filename' => $file['filename'],
                            'filetype' => $file['filetype'] ?? null,
                            'size' => $file['size'] ?? null,
                            'url' => route('pelayanan.penmad-pengawas-bulanan.preview-file', [
                                'pemberkasanId' => $existingSubmission->id,
                                'syaratId' => $syaratId,
                            ]),
                        ];
                    }
                }
            }
        }

        if ($employeeId) {
            $employee = DB::table('users')->where('id', $employeeId)->first();
            if ($employee) {
                $appointmentData = [
                    'type' => 'employee',
                    'employee_id' => $employee->id,
                    'employee_name' => $employee->name,
                    'employee_role' => $this->personLabel($employee),
                    'employee_photo' => $this->personPhotoUrl($employee),
                ];
            }
        } elseif ($isDirect) {
            $appointmentData = [
                'type' => 'direct',
                'employee_id' => null,
                'employee_name' => 'Langsung ke Seksi',
                'employee_role' => 'Tanpa Pegawai Tertentu',
                'employee_photo' => asset('assets/img/ikon/505.webp'),
            ];
        }

        return view('service-request', array_merge($this->requestFormViewData($service, $requester, false, $existingSubmission, [], $existingFiles), [
            'service' => $service,
            'requester' => [
                'name' => $requester?->name,
                'identity' => $requester?->nomor_induk ?? '',
            ],
            'appointmentData' => $appointmentData,
            'tahunPelajaran' => $tahunPelajaran,
            'semester' => $semester,
            'tahun' => $tahun,
            'bulan' => $bulan,
            'existingSubmission' => $existingSubmission,
        ]));
    }

    public function editRequest(int $requestId)
    {
        $user = auth()->user();

        abort_unless($user, 403);

        $requestRow = DB::table('users_request')
            ->where('id', $requestId)
            ->where('user_id', $user->id)
            ->first();

        abort_unless($requestRow, 404);

        $service = $this->serviceDetail((int) $requestRow->layanan_id);
        $existingAnswers = DB::table('users_request_answers')
            ->where('request_id', $requestRow->id)
            ->pluck('value', 'syarat_id')
            ->all();

        $existingFiles = DB::table('users_berkas')
            ->where('no_req', $requestRow->no_req)
            ->get()
            ->keyBy('syarat_id')
            ->map(function ($file) use ($requestRow) {
                return [
                    'filename' => $file->filename,
                    'filetype' => $file->filetype,
                    'size' => $file->size,
                    'url' => route('pengajuan-saya.preview-file', [
                        'requestId' => $requestRow->id,
                        'syaratId' => $file->syarat_id,
                    ]),
                ];
            })
            ->all();
        $requestDescription = preg_replace('/^Nomor Identitas:.*?\n\n/s', '', (string) $requestRow->deskripsi);

        return view('service-request', array_merge($this->requestFormViewData($service, $user, true, $requestRow, $existingAnswers, $existingFiles), [
            'service' => $service,
            'requester' => [
                'name' => $user->name,
                'identity' => $user->nomor_induk ?? '',
            ],
            'requestDescription' => $requestDescription,
            'backUrl' => route('pengajuan-saya'),
        ]));
    }

    public function deleteRequest(int $requestId): \Illuminate\Http\RedirectResponse
    {
        $user = auth()->user();
        abort_unless($user, 403);

        $requestRow = DB::table('users_request')
            ->where('id', $requestId)
            ->where('user_id', $user->id)
            ->first();

        abort_unless($requestRow, 404);

        // Only allow delete for DRAFT status
        if ($requestRow->status !== 'DRAFT') {
            return redirect()
                ->route('pengajuan-saya')
                ->with('error', 'Hanya draft yang dapat dihapus.');
        }

        // Delete associated files from storage
        $berkas = DB::table('users_berkas')
            ->where('no_req', $requestRow->no_req)
            ->get();

        foreach ($berkas as $file) {
            $path = "{$user->nomor_induk}/{$file->filename}";
            Storage::disk('users_berkas')->delete($path);
        }

        // Delete file records
        DB::table('users_berkas')
            ->where('no_req', $requestRow->no_req)
            ->delete();

        // Delete the request record
        DB::table('users_request')
            ->where('id', $requestId)
            ->where('user_id', $user->id)
            ->delete();

        return redirect()
            ->route('pengajuan-saya')
            ->with('success', 'Draft berhasil dihapus.');
    }

    public function previewRequestFile(int $requestId, int $syaratId)
    {
        $user = auth()->user();

        abort_unless($user, 403);

        $requestRow = DB::table('users_request')
            ->where('id', $requestId)
            ->where('user_id', $user->id)
            ->first();

        abort_unless($requestRow, 404);

        $file = DB::table('users_berkas')
            ->where('no_req', $requestRow->no_req)
            ->where('syarat_id', $syaratId)
            ->first();

        abort_unless($file, 404);

        $path = "service-requests/{$requestRow->no_req}/{$file->filename}";

        abort_unless(Storage::disk('public')->exists($path), 404);

        return Storage::disk('public')->response($path);
    }

    /**
     * Preview uploaded file for TPG service (from satker_pemberkasan)
     */
    public function previewTpgFile(int $pemberkasanId, int $syaratId)
    {
        $user = auth()->user();
        abort_unless($user, 403);

        $pemberkasan = DB::table('satker_pemberkasan')
            ->where('id', $pemberkasanId)
            ->where('user_id', $user->id)
            ->first();

        abort_unless($pemberkasan, 404);

        // Decode files (handle double-encoded JSON)
        $filesRaw = $pemberkasan->files ?? null;
        $filesData = [];
        if (is_array($filesRaw)) {
            $filesData = $filesRaw;
        } elseif (is_string($filesRaw)) {
            $decoded = json_decode($filesRaw, true);
            if (is_string($decoded)) {
                $decoded = json_decode($decoded, true);
            }
            $filesData = is_array($decoded) ? $decoded : [];
        }

        $fileEntry = collect($filesData)->firstWhere('syarat_id', $syaratId);

        abort_unless($fileEntry && !empty($fileEntry['filename']) && $fileEntry['filename'] !== 'NONE', 404);

        $path = "{$user->nomor_induk}/{$fileEntry['filename']}";

        abort_unless(Storage::disk('users_berkas')->exists($path), 404);

        return Storage::disk('users_berkas')->response($path);
    }

    public function editTpgRequest(int $pemberkasanId, Request $request = null)
    {
        $user = auth()->user();
        abort_unless($user, 403);

        $pemberkasan = DB::table('satker_pemberkasan')
            ->where('id', $pemberkasanId)
            ->where('user_id', $user->id)
            ->first();

        abort_unless($pemberkasan, 404);

        // Decode metadata
        $metadata = json_decode($pemberkasan->metadata ?? '{}', true);
        if (is_string($metadata)) {
            $metadata = json_decode($metadata, true) ?? [];
        }

        $tahunPelajaran = $metadata['tahun_pelajaran'] ?? '';
        $semester = $metadata['semester'] ?? '';

        // Get service info (service ID 1037 for TPG)
        $serviceId = 1037;
        $service = $this->serviceDetail($serviceId);

        // Decode existing files
        $filesRaw = $pemberkasan->files ?? null;
        $filesData = [];
        if (is_array($filesRaw)) {
            $filesData = $filesRaw;
        } elseif (is_string($filesRaw)) {
            $decoded = json_decode($filesRaw, true);
            if (is_string($decoded)) {
                $decoded = json_decode($decoded, true);
            }
            $filesData = is_array($decoded) ? $decoded : [];
        }

        $existingFiles = [];
        foreach ($filesData as $file) {
            $syaratId = $file['syarat_id'] ?? 0;
            if (!empty($file['filename']) && $file['filename'] !== 'NONE') {
                $existingFiles[$syaratId] = [
                    'filename' => $file['filename'],
                    'filetype' => $file['filetype'] ?? null,
                    'size' => $file['size'] ?? null,
                    'url' => route('pelayanan.tpg.preview-file', [
                        'pemberkasanId' => $pemberkasanId,
                        'syaratId' => $syaratId,
                    ]),
                ];
            }
        }

        return view('service-request', array_merge(
            $this->requestFormViewData($service, $user, false, $pemberkasan, [], $existingFiles),
            [
                'service' => $service,
                'tahunPelajaran' => $tahunPelajaran,
                'semester' => $semester,
                'existingSubmission' => $pemberkasan,
                'isEditing' => true,
                'editPemberkasanId' => $pemberkasanId,
            ]
        ));
    }

    public function updateTpgRequest(Request $request, int $pemberkasanId): \Illuminate\Http\RedirectResponse
    {
        $user = auth()->user();
        abort_unless($user, 403);

        $pemberkasan = DB::table('satker_pemberkasan')
            ->where('id', $pemberkasanId)
            ->where('user_id', $user->id)
            ->first();

        abort_unless($pemberkasan, 404);

        // Validate required fields
        $validated = $request->validate([
            'tahun_pelajaran' => ['required', 'string'],
            'semester' => ['required', 'string'],
        ]);

        $serviceId = 1037;
        $service = $this->serviceDetail($serviceId);
        $requirements = $service['requirements'];

        $kategori = 'PAIS-TPG-SEMESTER';
        $tahunPelajaran = $validated['tahun_pelajaran'];
        $semester = $validated['semester'];
        $isDraft = $request->input('submit_action') === 'draft';

        // item_id: 1=Ganjil, 2=Genap (case-insensitive)
        $itemId = strtoupper($semester) === 'GENAP' ? 2 : 1;

        // Parse tahun from tahun_pelajaran (supports both "2026/2027" and "2026-2027")
        $tahunParts = preg_split('[/-]', $tahunPelajaran);
        $tahun = (int) ($tahunParts[0] ?? date('Y'));

        // waktu: Ganjil=July, Genap=January (tahun berikutnya)
        $waktuBulan = $itemId === 1 ? 7 : 1;
        $waktuTahun = $itemId === 1 ? $tahun : $tahun + 1;
        $waktuDate = Carbon::createFromDate($waktuTahun, $waktuBulan, 1)->startOfMonth();

        // Keep existing noreq
        $noreq = $pemberkasan->noreq;

        // Generate deskripsi
        $deskripsi = "[{$kategori}] Semester {$semester} TP. {$tahunPelajaran}";

        // Build files snapshot
        // Get existing files from database to preserve
        $existingFilesRaw = $pemberkasan->files ?? null;

        $existingFiles = [];
        if (is_array($existingFilesRaw)) {
            $existingFiles = $existingFilesRaw;
        } elseif (is_string($existingFilesRaw)) {
            $decoded = json_decode($existingFilesRaw, true);
            if (is_string($decoded)) {
                $decoded = json_decode($decoded, true);
            }
            $existingFiles = is_array($decoded) ? $decoded : [];
        }

        // Get deleted file IDs from request (handle both array and comma-separated string)
        $deletedFilesInput = $request->input('deleted_files', []);
        if (is_array($deletedFilesInput)) {
            $deletedFileIds = array_map('intval', $deletedFilesInput);
        } elseif (is_string($deletedFilesInput) && !empty($deletedFilesInput)) {
            $deletedFileIds = array_map('intval', explode(',', $deletedFilesInput));
        } else {
            $deletedFileIds = [];
        }

        $filesSnapshot = $this->buildFilesSnapshot($user, $serviceId, $noreq, $requirements, $request, $existingFiles, $deletedFileIds);

        // Build metadata
        $metadata = [
            'tahun_pelajaran' => $tahunPelajaran,
            'semester' => $semester,
            'kategori' => $kategori,
            'tahun_ajaran' => $tahun,
            'submitted_at' => now()->toIso8601String(),
            'is_draft' => $isDraft,
            'updated_at' => now()->toIso8601String(),
        ];

        // Build requirements snapshot
        $requirementsSnapshot = collect($requirements)->map(function ($req) {
            return [
                'id' => $req['id'],
                'title' => $req['title'],
                'note' => $req['note'],
                'is_required' => $req['is_required'],
                'type' => $req['type_normalized'],
            ];
        })->toArray();

        // Update to database
        SatkerPemberkasan::where('id', $pemberkasanId)
            ->where('user_id', $user->id)
            ->update([
                'waktu' => $waktuDate,
                'item_id' => $itemId,
                'deskripsi' => $deskripsi,
                'keterangan' => $request->input('deskripsi') ?? '<NoKomen>',
                'status' => $isDraft ? 'DRAFT' : 'SUBMITTED',
                'files' => json_encode($filesSnapshot),
                'metadata' => json_encode($metadata),
                'requirements_snapshot' => json_encode($requirementsSnapshot),
                'updated_at' => now(),
            ]);

        $message = $isDraft
            ? "Draft {$service['title']} sudah diperbarui."
            : "Pengajuan {$service['title']} sudah diperbarui.";

        if (! $isDraft) {
            $serviceData = DB::table('ktd_layanan')->find($serviceId);
            if ($serviceData && ! empty($serviceData->petugas)) {
                $this->notifyPetugasViaWhatsApp(
                    (int) $serviceData->petugas,
                    $service['title'],
                    $noreq,
                    $user->name,
                    null,
                    "Semester {$semester} TP. {$tahunPelajaran}",
                    $request->input('deskripsi') ?? null
                );
            }
        }

        return redirect()
            ->route('pengajuan-saya')
            ->with('success', $message);
    }

    public function deleteTpgRequest(int $pemberkasanId): \Illuminate\Http\RedirectResponse
    {
        $user = auth()->user();
        abort_unless($user, 403);

        $pemberkasan = DB::table('satker_pemberkasan')
            ->where('id', $pemberkasanId)
            ->where('user_id', $user->id)
            ->first();

        abort_unless($pemberkasan, 404);

        // Only allow delete for DRAFT status
        if ($pemberkasan->status !== 'DRAFT') {
            return redirect()
                ->route('pengajuan-saya')
                ->with('error', 'Hanya draft yang dapat dihapus.');
        }

        // Delete associated files from storage
        $filesRaw = $pemberkasan->files ?? '[]';
        $filesData = json_decode($filesRaw, true);
        if (is_string($filesData)) {
            $filesData = json_decode($filesData, true);
        }
        if (!is_array($filesData)) {
            $filesData = [];
        }
        foreach ($filesData as $file) {
            if (!empty($file['filename']) && $file['filename'] !== 'NONE') {
                $path = "{$user->nomor_induk}/{$file['filename']}";
                Storage::disk('users_berkas')->delete($path);
            }
        }

        // Delete the record
        DB::table('satker_pemberkasan')
            ->where('id', $pemberkasanId)
            ->where('user_id', $user->id)
            ->delete();

        return redirect()
            ->route('pengajuan-saya')
            ->with('success', 'Draft berhasil dihapus.');
    }

    // ==========================================
    // TPG BULANAN METHODS (Service 1038)
    // ==========================================

    /**
     * Preview uploaded file for TPG Bulanan services
     * Unified for PAIS-TPG-BULANAN and PENMAD-TPG-BULANAN
     */
    public function previewTpgBulananFile(int $pemberkasanId, int $syaratId)
    {
        $user = auth()->user();
        abort_unless($user, 403);

        $pemberkasan = DB::table('satker_pemberkasan')
            ->where('id', $pemberkasanId)
            ->where('user_id', $user->id)
            ->first();

        abort_unless($pemberkasan, 404);

        // Decode files (handle double-encoded JSON)
        $filesRaw = $pemberkasan->files ?? null;
        $filesData = [];
        if (is_array($filesRaw)) {
            $filesData = $filesRaw;
        } elseif (is_string($filesRaw)) {
            $decoded = json_decode($filesRaw, true);
            if (is_string($decoded)) {
                $decoded = json_decode($decoded, true);
            }
            $filesData = is_array($decoded) ? $decoded : [];
        }

        $fileEntry = collect($filesData)->firstWhere('syarat_id', $syaratId);

        abort_unless($fileEntry && !empty($fileEntry['filename']) && $fileEntry['filename'] !== 'NONE', 404);

        $path = "{$user->nomor_induk}/{$fileEntry['filename']}";

        abort_unless(Storage::disk('users_berkas')->exists($path), 404);

        return Storage::disk('users_berkas')->response($path);
    }

    /**
     * Handle TPG Bulanan submission
     * Unified for PAIS-TPG-BULANAN (1038) and PENMAD-TPG-BULANAN (1081)
     */
    public function submitTpgBulananRequest(Request $request, int $serviceId): \Illuminate\Http\RedirectResponse
    {
        $service = $this->serviceDetail($serviceId);
        $requirements = $service['requirements'];
        $requester = $request->user();
        $isDraft = $request->input('submit_action') === 'draft';

        abort_unless($requester, 403);

        // Validate required fields
        $validated = $request->validate([
            'tahun' => ['required', 'string'],
            'bulan' => ['required', 'string'],
        ]);

        // Check for missing required files (will show as warning)
        $missingFiles = $this->validateRequiredFiles($requirements, $request);

        // Determine kategori based on service ID
        $kategori = $this->resolveTpgBulananKategori($serviceId);
        $tahun = (int) ($validated['tahun'] ?? date('Y'));
        $bulan = $validated['bulan'];

        // Map bulan name to number (1-12)
        $bulanMap = [
            'Januari' => 1, 'Februari' => 2, 'Maret' => 3, 'April' => 4,
            'Mei' => 5, 'Juni' => 6, 'Juli' => 7, 'Agustus' => 8,
            'September' => 9, 'Oktober' => 10, 'November' => 11, 'Desember' => 12,
        ];
        $itemId = $bulanMap[$bulan] ?? $bulanMap[ucfirst(strtolower($bulan))] ?? date('n');

        // Determine waktu (start date of the month)
        $waktuDate = Carbon::createFromDate($tahun, $itemId, 1)->startOfMonth();

        // Generate noreq (unique request number)
        // Format: {KATEGORI}-{USERID}-{TAHUN}-{BULAN}
        $noreq = strtoupper("{$kategori}-{$requester->id}-{$tahun}-{$bulan}");

        // Generate deskripsi
        $deskripsi = "[{$kategori}] {$bulan} {$tahun}";

        // Build files snapshot from uploaded files
        $filesSnapshot = $this->buildFilesSnapshot($requester, $serviceId, $noreq, $requirements, $request);

        // Build metadata JSON
        $metadata = [
            'tahun' => $tahun,
            'bulan' => $bulan,
            'bulan_number' => $itemId,
            'kategori' => $kategori,
            'tahun_ajaran' => $tahun,
            'submitted_at' => now()->toIso8601String(),
            'is_draft' => $isDraft,
        ];

        // Build requirements snapshot (preserve state at submission time)
        $requirementsSnapshot = collect($requirements)->map(function ($req) {
            return [
                'id' => $req['id'],
                'title' => $req['title'],
                'note' => $req['note'],
                'is_required' => $req['is_required'],
                'type' => $req['type_normalized'],
            ];
        })->toArray();

        // Save to satker_pemberkasan
        SatkerPemberkasan::updateOrCreate(
            ['noreq' => $noreq],
            [
                'user_id' => $requester->id,
                'tipe' => $kategori,
                'layanan_id' => $serviceId,
                'dept_id' => (string) $requester->dept_id,
                'waktu' => $waktuDate,
                'item_id' => $itemId,
                'deskripsi' => $deskripsi,
                'keterangan' => $request->input('deskripsi') ?? '<NoKomen>',
                'status' => $isDraft ? 'DRAFT' : 'SUBMITTED',
                'files' => json_encode($filesSnapshot),
                'metadata' => json_encode($metadata),
                'requirements_snapshot' => json_encode($requirementsSnapshot),
                'is_migrated' => true,
                'migrated_at' => now(),
            ]
        );

        $message = $isDraft
            ? "Draft {$service['title']} sudah disimpan."
            : "Pengajuan {$service['title']} sudah diterima.";

        // Add warning if there are missing required files
        if (!empty($missingFiles)) {
            $message .= " Perhatian: Bahan wajib belum lengkap - " . implode(', ', $missingFiles) . ". Silakan lengkapi sebelum mengirim final.";
        }

        if (! $isDraft) {
            $serviceData = DB::table('ktd_layanan')->find($serviceId);
            if ($serviceData && ! empty($serviceData->petugas)) {
                $this->notifyPetugasViaWhatsApp(
                    (int) $serviceData->petugas,
                    $service['title'],
                    $noreq,
                    $requester->name,
                    null,
                    "{$bulan} {$tahun}",
                    $request->input('deskripsi') ?? null
                );
            }
        }

        return redirect()
            ->route('pengajuan-saya')
            ->with('success', $message);
    }

    /**
     * Edit TPG Bulanan request
     * Unified for PAIS-TPG-BULANAN and PENMAD-TPG-BULANAN
     */
    public function editTpgBulananRequest(int $pemberkasanId, Request $request = null)
    {
        $user = auth()->user();
        abort_unless($user, 403);

        $pemberkasan = DB::table('satker_pemberkasan')
            ->where('id', $pemberkasanId)
            ->where('user_id', $user->id)
            ->first();

        abort_unless($pemberkasan, 404);

        // Decode metadata
        $metadata = json_decode($pemberkasan->metadata ?? '{}', true);
        if (is_string($metadata)) {
            $metadata = json_decode($metadata, true) ?? [];
        }

        $tahun = $metadata['tahun'] ?? '';
        $bulan = $metadata['bulan'] ?? '';

        // Get service info from the pemberkasan record
        $serviceId = $pemberkasan->layanan_id;
        $service = $this->serviceDetail($serviceId);

        // Decode existing files
        $filesRaw = $pemberkasan->files ?? null;
        $filesData = [];
        if (is_array($filesRaw)) {
            $filesData = $filesRaw;
        } elseif (is_string($filesRaw)) {
            $decoded = json_decode($filesRaw, true);
            if (is_string($decoded)) {
                $decoded = json_decode($decoded, true);
            }
            $filesData = is_array($decoded) ? $decoded : [];
        }

        $existingFiles = [];
        foreach ($filesData as $file) {
            $syaratId = $file['syarat_id'] ?? 0;
            if (!empty($file['filename']) && $file['filename'] !== 'NONE') {
                $existingFiles[$syaratId] = [
                    'filename' => $file['filename'],
                    'filetype' => $file['filetype'] ?? null,
                    'size' => $file['size'] ?? null,
                    'url' => route('pelayanan.tpg-bulanan.preview-file', [
                        'pemberkasanId' => $pemberkasanId,
                        'syaratId' => $syaratId,
                    ]),
                ];
            }
        }

        return view('service-request', array_merge(
            $this->requestFormViewData($service, $user, false, $pemberkasan, [], $existingFiles),
            [
                'service' => $service,
                'tahun' => $tahun,
                'bulan' => $bulan,
                'existingSubmission' => $pemberkasan,
                'isEditing' => true,
                'editPemberkasanId' => $pemberkasanId,
                'isTpgBulanan' => true,
            ]
        ));
    }

    /**
     * Update TPG Bulanan request
     * Unified for PAIS-TPG-BULANAN and PENMAD-TPG-BULANAN
     */
    public function updateTpgBulananRequest(Request $request, int $pemberkasanId): \Illuminate\Http\RedirectResponse
    {
        $user = auth()->user();
        abort_unless($user, 403);

        $pemberkasan = DB::table('satker_pemberkasan')
            ->where('id', $pemberkasanId)
            ->where('user_id', $user->id)
            ->first();

        abort_unless($pemberkasan, 404);

        // Validate required fields
        $validated = $request->validate([
            'tahun' => ['required', 'string'],
            'bulan' => ['required', 'string'],
        ]);

        $serviceId = $pemberkasan->layanan_id;
        $service = $this->serviceDetail($serviceId);
        $requirements = $service['requirements'];

        // Determine kategori based on service ID
        $kategori = $this->resolveTpgBulananKategori($serviceId);
        $tahun = (int) ($validated['tahun'] ?? date('Y'));
        $bulan = $validated['bulan'];
        $isDraft = $request->input('submit_action') === 'draft';

        // Map bulan name to number (1-12)
        $bulanMap = [
            'Januari' => 1, 'Februari' => 2, 'Maret' => 3, 'April' => 4,
            'Mei' => 5, 'Juni' => 6, 'Juli' => 7, 'Agustus' => 8,
            'September' => 9, 'Oktober' => 10, 'November' => 11, 'Desember' => 12,
        ];
        $itemId = $bulanMap[$bulan] ?? $bulanMap[ucfirst(strtolower($bulan))] ?? date('n');

        // waktu: start date of the month
        $waktuDate = Carbon::createFromDate($tahun, $itemId, 1)->startOfMonth();

        // Keep existing noreq
        $noreq = $pemberkasan->noreq;

        // Generate deskripsi
        $deskripsi = "[{$kategori}] {$bulan} {$tahun}";

        // Build files snapshot
        // Get existing files from database to preserve
        $existingFilesRaw = $pemberkasan->files ?? null;
        $existingFiles = [];
        if (is_array($existingFilesRaw)) {
            $existingFiles = $existingFilesRaw;
        } elseif (is_string($existingFilesRaw)) {
            $decoded = json_decode($existingFilesRaw, true);
            if (is_string($decoded)) {
                $decoded = json_decode($decoded, true);
            }
            $existingFiles = is_array($decoded) ? $decoded : [];
        }

        // Get deleted file IDs from request (handle both array and comma-separated string)
        $deletedFilesInput = $request->input('deleted_files', []);
        if (is_array($deletedFilesInput)) {
            $deletedFileIds = array_map('intval', $deletedFilesInput);
        } elseif (is_string($deletedFilesInput) && !empty($deletedFilesInput)) {
            $deletedFileIds = array_map('intval', explode(',', $deletedFilesInput));
        } else {
            $deletedFileIds = [];
        }

        $filesSnapshot = $this->buildFilesSnapshot($user, $serviceId, $noreq, $requirements, $request, $existingFiles, $deletedFileIds);

        // Build metadata
        $metadata = [
            'tahun' => $tahun,
            'bulan' => $bulan,
            'bulan_number' => $itemId,
            'kategori' => $kategori,
            'tahun_ajaran' => $tahun,
            'submitted_at' => now()->toIso8601String(),
            'is_draft' => $isDraft,
            'updated_at' => now()->toIso8601String(),
        ];

        // Build requirements snapshot
        $requirementsSnapshot = collect($requirements)->map(function ($req) {
            return [
                'id' => $req['id'],
                'title' => $req['title'],
                'note' => $req['note'],
                'is_required' => $req['is_required'],
                'type' => $req['type_normalized'],
            ];
        })->toArray();

        // Update to database
        SatkerPemberkasan::where('id', $pemberkasanId)
            ->where('user_id', $user->id)
            ->update([
                'waktu' => $waktuDate,
                'item_id' => $itemId,
                'deskripsi' => $deskripsi,
                'keterangan' => $request->input('deskripsi') ?? '<NoKomen>',
                'status' => $isDraft ? 'DRAFT' : 'SUBMITTED',
                'files' => json_encode($filesSnapshot),
                'metadata' => json_encode($metadata),
                'requirements_snapshot' => json_encode($requirementsSnapshot),
                'updated_at' => now(),
            ]);

        $message = $isDraft
            ? "Draft {$service['title']} sudah diperbarui."
            : "Pengajuan {$service['title']} sudah diperbarui.";

        if (! $isDraft) {
            $serviceData = DB::table('ktd_layanan')->find($serviceId);
            if ($serviceData && ! empty($serviceData->petugas)) {
                $this->notifyPetugasViaWhatsApp(
                    (int) $serviceData->petugas,
                    $service['title'],
                    $noreq,
                    $user->name,
                    null,
                    "{$bulan} {$tahun}",
                    $request->input('deskripsi') ?? null
                );
            }
        }

        return redirect()
            ->route('pengajuan-saya')
            ->with('success', $message);
    }

    /**
     * Delete TPG Bulanan request
     * Unified for PAIS-TPG-BULANAN and PENMAD-TPG-BULANAN
     */
    public function deleteTpgBulananRequest(int $pemberkasanId): \Illuminate\Http\RedirectResponse
    {
        $user = auth()->user();
        abort_unless($user, 403);

        $pemberkasan = DB::table('satker_pemberkasan')
            ->where('id', $pemberkasanId)
            ->where('user_id', $user->id)
            ->first();

        abort_unless($pemberkasan, 404);

        // Only allow delete for DRAFT status
        if ($pemberkasan->status !== 'DRAFT') {
            return redirect()
                ->route('pengajuan-saya')
                ->with('error', 'Hanya draft yang dapat dihapus.');
        }

        // Delete associated files from storage
        $filesRaw = $pemberkasan->files ?? '[]';
        $filesData = json_decode($filesRaw, true);
        if (is_string($filesData)) {
            $filesData = json_decode($filesData, true);
        }
        if (!is_array($filesData)) {
            $filesData = [];
        }
        foreach ($filesData as $file) {
            if (!empty($file['filename']) && $file['filename'] !== 'NONE') {
                $path = "{$user->nomor_induk}/{$file['filename']}";
                Storage::disk('users_berkas')->delete($path);
            }
        }

        // Delete the record
        DB::table('satker_pemberkasan')
            ->where('id', $pemberkasanId)
            ->where('user_id', $user->id)
            ->delete();

        return redirect()
            ->route('pengajuan-saya')
            ->with('success', 'Draft berhasil dihapus.');
    }

    // ==========================================
    // PENMAD TPG BULANAN METHODS (Service 1081)
    // Pass-through methods - unified logic in TPG Bulanan methods above
    // ==========================================

    /**
     * @deprecated Use previewTpgBulananFile()
     */
    public function previewPenmadTpgBulananFile(int $pemberkasanId, int $syaratId)
    {
        return $this->previewTpgBulananFile($pemberkasanId, $syaratId);
    }

    /**
     * @deprecated Use submitTpgBulananRequest()
     */
    public function submitPenmadTpgBulananRequest(Request $request, int $serviceId): \Illuminate\Http\RedirectResponse
    {
        return $this->submitTpgBulananRequest($request, $serviceId);
    }

    /**
     * @deprecated Use editTpgBulananRequest()
     */
    public function editPenmadTpgBulananRequest(int $pemberkasanId, Request $request = null)
    {
        return $this->editTpgBulananRequest($pemberkasanId, $request);
    }

    /**
     * @deprecated Use updateTpgBulananRequest()
     */
    public function updatePenmadTpgBulananRequest(Request $request, int $pemberkasanId): \Illuminate\Http\RedirectResponse
    {
        return $this->updateTpgBulananRequest($request, $pemberkasanId);
    }

    /**
     * @deprecated Use deleteTpgBulananRequest()
     */
    public function deletePenmadTpgBulananRequest(int $pemberkasanId): \Illuminate\Http\RedirectResponse
    {
        return $this->deleteTpgBulananRequest($pemberkasanId);
    }

    /**
     * Resolve kategori string from service ID for TPG Bulanan
     */
    private function resolveTpgBulananKategori(int $serviceId): string
    {
        return match ($serviceId) {
            1081 => 'PENMAD-TPG-BULANAN',
            1082 => 'PENMAD-PENGAWAS-BULANAN',
            default => 'PAIS-TPG-BULANAN',
        };
    }

    // ==========================================
    // PENMAD PENGAWAS BULANAN METHODS (Service 1082)
    // Pass-through methods - unified logic in TPG Bulanan methods above
    // ==========================================

    /**
     * @deprecated Use previewTpgBulananFile()
     */
    public function previewPenmadPengawasBulananFile(int $pemberkasanId, int $syaratId)
    {
        return $this->previewTpgBulananFile($pemberkasanId, $syaratId);
    }

    /**
     * @deprecated Use submitTpgBulananRequest()
     */
    public function submitPenmadPengawasBulananRequest(Request $request, int $serviceId): \Illuminate\Http\RedirectResponse
    {
        return $this->submitTpgBulananRequest($request, $serviceId);
    }

    /**
     * @deprecated Use editTpgBulananRequest()
     */
    public function editPenmadPengawasBulananRequest(int $pemberkasanId, Request $request = null)
    {
        return $this->editTpgBulananRequest($pemberkasanId, $request);
    }

    /**
     * @deprecated Use updateTpgBulananRequest()
     */
    public function updatePenmadPengawasBulananRequest(Request $request, int $pemberkasanId): \Illuminate\Http\RedirectResponse
    {
        return $this->updateTpgBulananRequest($request, $pemberkasanId);
    }

    /**
     * @deprecated Use deleteTpgBulananRequest()
     */
    public function deletePenmadPengawasBulananRequest(int $pemberkasanId): \Illuminate\Http\RedirectResponse
    {
        return $this->deleteTpgBulananRequest($pemberkasanId);
    }

    public function myRequests()
    {
        $user = auth()->user();

        abort_unless($user, 403);

        // ==========================================
        // TPG TYPES (satker_pemberkasan)
        // ==========================================
        $tpgTypes = ['PAIS-TPG-SEMESTER', 'PAIS-TPG-BULANAN', 'PENMAD-TPG-BULANAN', 'PENMAD-PENGAWAS-BULANAN'];

        // ==========================================
        // QUERY 1: Regular requests summary (users_request)
        // ==========================================
        $regularSummary = DB::table('users_request')
            ->where('user_id', $user->id)
            ->selectRaw('
                COUNT(*) as total,
                SUM(status = "DRAFT") as draft,
                SUM(status = "UNCHECK") as pending,
                SUM(status IN ("PENDING","DITERIMA","DIPROSES")) as processed,
                SUM(status IN ("SUKSES","DITOLAK","BATAL")) as done
            ')
            ->first();

        // ==========================================
        // QUERY 2: TPG summary (satker_pemberkasan) - all 4 types combined
        // ==========================================
        $tpgSummary = DB::table('satker_pemberkasan')
            ->where('user_id', $user->id)
            ->whereIn('tipe', $tpgTypes)
            ->selectRaw('
                COUNT(*) as total,
                SUM(status = "DRAFT") as draft,
                SUM(status = "SUBMITTED") as pending,
                SUM(status IN ("PENDING","DITERIMA","DIPROSES")) as processed,
                SUM(status IN ("SUKSES","DITOLAK","BATAL")) as done
            ')
            ->first();

        // ==========================================
        // QUERY 3: Janji Temu summary (ktd_bukutamu)
        // ==========================================
        $janjiTemuSummary = DB::table('ktd_bukutamu')
            ->where('nomor_induk', $user->nomor_induk)
            ->selectRaw('
                COUNT(*) as total,
                SUM(status = "APPOINTMENT") as appointment,
                SUM(status = "PENDING") as pending,
                SUM(status = "APPROVED") as approved,
                SUM(status = "REJECTED") as rejected,
                SUM(status = "CANCELLED") as cancelled
            ')
            ->first();

        // Combine summaries
        $summary = [
            'total' => ($regularSummary->total ?? 0) + ($tpgSummary->total ?? 0) + ($janjiTemuSummary->total ?? 0),
            'draft' => ($regularSummary->draft ?? 0) + ($tpgSummary->draft ?? 0),
            'pending' => ($regularSummary->pending ?? 0) + ($tpgSummary->pending ?? 0) + ($janjiTemuSummary->appointment ?? 0) + ($janjiTemuSummary->pending ?? 0),
            'processed' => ($regularSummary->processed ?? 0) + ($tpgSummary->processed ?? 0) + ($janjiTemuSummary->approved ?? 0),
            'done' => ($regularSummary->done ?? 0) + ($tpgSummary->done ?? 0) + ($janjiTemuSummary->rejected ?? 0) + ($janjiTemuSummary->cancelled ?? 0),
            // Janji temu specific
            'janji_temu' => [
                'total' => $janjiTemuSummary->total ?? 0,
                'appointment' => $janjiTemuSummary->appointment ?? 0,
                'pending' => $janjiTemuSummary->pending ?? 0,
                'approved' => $janjiTemuSummary->approved ?? 0,
                'rejected' => $janjiTemuSummary->rejected ?? 0,
                'cancelled' => $janjiTemuSummary->cancelled ?? 0,
            ],
        ];

        // ==========================================
        // QUERY 4: Combined paginated requests (union of regular + all TPG)
        // ==========================================
        $regularRequests = DB::table('users_request as ur')
            ->where('ur.user_id', $user->id)
            ->leftJoin('ktd_layanan as layanan', 'layanan.id', '=', 'ur.layanan_id')
            ->select([
                'ur.id',
                'ur.no_req',
                'ur.pemohon',
                'ur.layanan_id',
                'ur.judul',
                'ur.deskripsi',
                'ur.status',
                'ur.kategori',
                'ur.created_at',
                'ur.updated_at',
                DB::raw('COALESCE(layanan.nama, ur.judul) as layanan_name'),
                DB::raw('COALESCE(layanan.deskripsi, ur.deskripsi) as layanan_description'),
                DB::raw('NULL as tipe'),
                DB::raw('NULL as metadata'),
                DB::raw('NULL as files_data'),
            ])
            ->selectSub(function ($query) {
                $query->from('users_berkas as ub')
                    ->selectRaw('COUNT(*)')
                    ->whereColumn('ub.no_req', 'ur.no_req');
            }, 'file_count')
            ->orderByDesc('ur.created_at')
            ->orderByDesc('ur.id');

        $tpgRequests = DB::table('satker_pemberkasan as sp')
            ->where('sp.user_id', $user->id)
            ->whereIn('sp.tipe', $tpgTypes)
            ->select([
                'sp.id',
                'sp.noreq as no_req',
                DB::raw('NULL as pemohon'),
                'sp.layanan_id',
                DB::raw('NULL as judul'),
                'sp.deskripsi',
                'sp.status',
                'sp.tipe as kategori',
                'sp.created_at',
                'sp.updated_at',
                DB::raw("CASE sp.tipe
                    WHEN 'PAIS-TPG-SEMESTER' THEN 'Pemberkasan TPG Semester'
                    WHEN 'PAIS-TPG-BULANAN' THEN 'Pemberkasan TPG Bulanan'
                    WHEN 'PENMAD-TPG-BULANAN' THEN 'Pemberkasan TPG Bulanan PENMAD'
                    WHEN 'PENMAD-PENGAWAS-BULANAN' THEN 'Pemberkasan TPG Bulanan Pengawas'
                    ELSE sp.tipe END as layanan_name"),
                DB::raw("CASE sp.tipe
                    WHEN 'PAIS-TPG-SEMESTER' THEN 'Pemberkasan Pencairan Tunjangan Profesi Guru'
                    WHEN 'PAIS-TPG-BULANAN' THEN 'Pemberkasan Pencairan Tunjangan Profesi Guru Perbulan'
                    WHEN 'PENMAD-TPG-BULANAN' THEN 'Pemberkasan Pencairan Tunjangan Profesi Guru Perbulan PENMAD'
                    WHEN 'PENMAD-PENGAWAS-BULANAN' THEN 'Pemberkasan Pencairan Tunjangan Profesi Guru Pengawas Perbulan'
                    ELSE sp.tipe END as layanan_description"),
                'sp.tipe',
                'sp.metadata',
                DB::raw('sp.files as files_data'),
                DB::raw('0 as file_count'),
            ])
            ->orderByDesc('sp.created_at');

        $combinedQuery = $regularRequests->unionAll($tpgRequests);

        $allRequests = DB::query()->fromSub($combinedQuery, 'combined')
            ->orderByDesc('created_at')
            ->paginate(12)
            ->withQueryString();

        // Post-process: count files for TPG requests
        foreach ($allRequests as $request) {
            if (!empty($request->tipe) && in_array($request->tipe, $tpgTypes) && !empty($request->files_data)) {
                $files = json_decode($request->files_data, true);
                if (is_string($files)) {
                    $files = json_decode($files, true);
                }
                $request->file_count = is_array($files) ? count(array_filter($files, fn($f) => !empty($f['filename']) && $f['filename'] !== 'NONE')) : 0;
            }
        }

        // ==========================================
        // QUERY 5: Janji Temu list (ktd_bukutamu)
        // ==========================================
        $janjiTemuList = DB::table('ktd_bukutamu')
            ->where('nomor_induk', $user->nomor_induk)
            ->orderByDesc('created_at')
            ->paginate(12)
            ->withQueryString();

        return view('pengajuan-saya', [
            'summary' => $summary,
            'requests' => $allRequests,
            'janjiTemuList' => $janjiTemuList,
        ]);
    }

    public function laporanKinerja(Request $request)
    {
        $user = $request->user();

        abort_unless($user, 403);

        $activeTab = $request->string('tab')->toString();
        $activeTab = in_array($activeTab, ['harian', 'bulanan', 'humas'], true) ? $activeTab : 'harian';

        // Handle year parameter for bulanan tab, or month for other tabs
        $selectedYear = $request->input('year');
        $selectedMonth = $request->string('month')->toString();

        if ($activeTab === 'bulanan') {
            // For bulanan tab, use year parameter
            if (!$selectedYear || !preg_match('/^\d{4}$/', $selectedYear)) {
                $selectedYear = now()->format('Y');
            }
            $selectedYear = (int) $selectedYear;
            // Convert year to month format for display (use January as default)
            $selectedMonth = $selectedYear . '-01';
        } else {
            // For harian and humas tabs, use month parameter
            if (! preg_match('/^\d{4}-\d{2}$/', $selectedMonth)) {
                $selectedMonth = now()->format('Y-m');
            }
            $selectedYear = (int) Carbon::parse($selectedMonth)->format('Y');
        }

        $selectedMonthStart = Carbon::createFromFormat('Y-m', $selectedMonth)->startOfMonth();
        $selectedMonthEnd = $selectedMonthStart->copy()->endOfMonth();

        $selectedYear = $request->string('year')->toString();
        if (! preg_match('/^\d{4}$/', $selectedYear)) {
            $selectedYear = now()->format('Y');
        }

        $selectedYearStart = Carbon::createFromFormat('Y', $selectedYear)->startOfYear();
        $selectedYearEnd = $selectedYearStart->copy()->endOfYear();

        $search = trim((string) $request->query('search', ''));
        $printMode = $request->boolean('print');
        $editingDate = $request->string('edit_date')->toString();
        if (! preg_match('/^\d{4}-\d{2}-\d{2}$/', $editingDate)) {
            $editingDate = '';
        }

        // New query: 1 row per user per date (Option A: JSON Column)
        $dailyQuery = DB::table('satker_kegiatan')
            ->where('user_id', $user->id)
            ->whereBetween('tanggal', [$selectedMonthStart->toDateString(), $selectedMonthEnd->toDateString()])
            ->orderBy('tanggal');

        // For search, we need to search in JSON - handle separately
        $dailyEntries = $dailyQuery->get();

        // Process JSON data format
        $dailyGroups = collect([])
            ->keyBy(fn ($item) => $item['date'])
            ->toArray();

        $totalEntries = 0;
        $totalVolume = 0;
        $latestUpdate = null;

        foreach ($dailyEntries as $row) {
            $date = Carbon::parse($row->tanggal)->toDateString();
            $jsonData = json_decode((string) ($row->data_json ?? '{"items":[]}'), true) ?: ['items' => []];
            $items = $jsonData['items'] ?? [];

            // Handle legacy format (direct columns instead of JSON)
            if (empty($items) && !empty($row->kegiatan)) {
                $items = [[
                    'id' => $row->id,
                    'k' => $row->kegiatan,
                    'v' => $row->volume ?? 0,
                    's' => $row->satuan ?? 'Kegiatan'
                ]];
            }

            // Filter by search if needed
            if ($search !== '') {
                $items = array_filter($items, function ($item) use ($search) {
                    $kegiatan = $item['k'] ?? ($item['kegiatan'] ?? '');
                    return stripos($kegiatan, $search) !== false;
                });
            }

            if (empty($items)) {
                continue;
            }

            $mappedItems = array_map(function ($item) use ($row) {
                $volume = (int) ($item['v'] ?? 0);
                $satuan = $item['s'] ?? 'Kegiatan';

                return [
                    'id' => $item['id'] ?? null,
                    'kegiatan' => trim((string) ($item['k'] ?? '')),
                    'volume' => $volume,
                    'satuan' => $satuan,
                    'meta' => $volume > 0 ? trim($volume . ' ' . $satuan) : $satuan,
                    'tanggal' => $row->tanggal,
                ];
            }, array_values($items));

            $dayVolume = array_sum(array_column($mappedItems, 'volume'));
            $dayEntries = count($mappedItems);
            $totalEntries += $dayEntries;
            $totalVolume += $dayVolume;

            if ($row->updated_at && (!$latestUpdate || $row->updated_at > $latestUpdate)) {
                $latestUpdate = $row->updated_at;
            }

            $dateCarbon = Carbon::parse($date);

            if (!isset($dailyGroups[$date])) {
                $dailyGroups[$date] = [
                    'date' => $dateCarbon->toDateString(),
                    'label' => $this->indonesianDateLabel($dateCarbon),
                    'items' => [],
                    'entries' => 0,
                    'volume' => 0,
                    'row_id' => $row->id,
                ];
            }

            $dailyGroups[$date]['items'] = array_merge($dailyGroups[$date]['items'], $mappedItems);
            $dailyGroups[$date]['entries'] += $dayEntries;
            $dailyGroups[$date]['volume'] += $dayVolume;
        }

        // Sort by date and re-index
        $dailyGroups = collect($dailyGroups)->sortBy('date')->values();

        $dailySummary = [
            'entries' => $totalEntries,
            'days' => count($dailyGroups),
            'volume' => $totalVolume,
            'latest_update' => $latestUpdate,
        ];

        // Default activity units - not from database
        $activityUnits = collect([
            'Kegiatan',
            'Dokumen',
            'Modul',
            'Jam',
            'Berkas',
            'Orang',
            'Paket',
            'Unit',
            'Lembar',
            'Buah',
            'Laporan',
            'Data',
        ]);

        $monthlyActivityStats = DB::table('satker_kegiatan')
            ->where('user_id', $user->id)
            ->whereBetween('tanggal', [$selectedYearStart->toDateString(), $selectedYearEnd->toDateString()])
            ->selectRaw("DATE_FORMAT(tanggal, '%Y-%m-01') as month_key")
            ->selectRaw('COUNT(*) as entries')
            ->selectRaw('COUNT(DISTINCT DATE(tanggal)) as days')
            ->selectRaw('COALESCE(SUM(volume), 0) as total_volume')
            ->groupBy('month_key')
            ->get()
            ->keyBy('month_key');

        $monthlyRecaps = DB::table('satker_ckh as ckh')
            ->where('ckh.user_id', $user->id)
            ->whereBetween('ckh.bulan', [$selectedYearStart->toDateString(), $selectedYearEnd->toDateString()])
            ->orderByDesc('ckh.bulan')
            ->orderByDesc('ckh.updated_at')
            ->get()
            ->map(function ($item) use ($monthlyActivityStats) {
                $monthCarbon = Carbon::parse($item->bulan)->startOfMonth();
                $stats = $monthlyActivityStats->get($monthCarbon->toDateString());
                $status = strtoupper(trim((string) ($item->status ?? 'KOSONG')));
                $pdfPath = filled($item->filename)
                    ? "satker_ckh/{$item->user_id}/{$item->filename}"
                    : '';

                return [
                    'id' => $item->id,
                    'month_key' => $monthCarbon->toDateString(),
                    'month_label' => $monthCarbon->translatedFormat('F'),
                    'days' => (int) ($stats->days ?? 0),
                    'entries' => (int) ($stats->entries ?? 0),
                    'total_volume' => (int) ($stats->total_volume ?? 0),
                    'status' => $status,
                    'status_label' => $this->reportStatusLabel($status),
                    'status_tone' => $this->reportStatusTone($status),
                    'status_class' => $this->reportStatusClass($status),
                    'sending_label' => filled($item->sending)
                        ? Carbon::parse($item->sending)->translatedFormat('d F Y H:i')
                        : '-',
                    'pdf_exists' => $pdfPath !== '' && Storage::disk('public')->exists($pdfPath),
                    'pdf_url' => $pdfPath !== '' && Storage::disk('public')->exists($pdfPath)
                        ? Storage::disk('public')->url($pdfPath)
                        : null,
                    'latest_update' => $item->updated_at,
                ];
            })
            ->values();

        // Rekap Bulanan dari satker_ckh - data laporan kinerja bulanan user
        // For bulanan tab, show only current user's data for the selected year
        if ($activeTab === 'bulanan') {
            $yearStart = Carbon::createFromFormat('Y-m-d', $selectedYear . '-01-01')->startOfYear();
            $yearEnd = Carbon::createFromFormat('Y-m-d', $selectedYear . '-12-31')->endOfYear();
        } else {
            $yearStart = $selectedMonthStart;
            $yearEnd = $selectedMonthEnd;
        }

        // For bulanan tab, show only current user's reports
        $bulananReports = DB::table('satker_ckh as ck')
            ->leftJoin('users as u', 'u.id', '=', 'ck.user_id')
            ->leftJoin('ktd_department as dept', 'dept.id', '=', 'ck.dept_id')
            ->whereBetween('ck.bulan', [$yearStart->toDateString(), $yearEnd->toDateString()])
            // Filter: only show current user's reports for bulanan tab
            ->where('ck.user_id', $user->id)
            ->select([
                'ck.id',
                'ck.user_id',
                'ck.dept_id',
                'ck.bulan',
                'ck.filename',
                'ck.status',
                'ck.alasan',
                'ck.petugas',
                'ck.sending',
                'ck.created_at',
                'ck.updated_at',
                'u.name as user_name',
                'u.nomor_induk',
                'dept.nama as dept_name',
            ])
            ->orderBy('ck.bulan', 'desc')
            ->get()
            ->map(function ($item) {
                $statusConfig = [
                    'KOSONG' => ['label' => 'Belum Kirim', 'color' => 'slate'],
                    'DIKIRIM' => ['label' => 'Dikirim', 'color' => 'amber'],
                    'DISETUJUI' => ['label' => 'Disetujui', 'color' => 'emerald'],
                    'DITOLAK' => ['label' => 'Ditolak', 'color' => 'rose'],
                ];
                $status = $statusConfig[$item->status] ?? ['label' => $item->status, 'color' => 'slate'];
                // Handle bulan field - could be date only or datetime
                $bulanRaw = is_string($item->bulan) ? substr($item->bulan, 0, 10) : $item->bulan;
                $bulanDate = Carbon::createFromFormat('Y-m-d', $bulanRaw);

                return [
                    'id' => $item->id,
                    'user_id' => $item->user_id,
                    'user_name' => $item->user_name ?? 'Unknown',
                    'nomor_induk' => $item->nomor_induk ?? '-',
                    'dept_name' => $item->dept_name ?? '-',
                    'bulan' => $bulanDate->format('F Y'),
                    'bulan_raw' => $bulanRaw,
                    'filename' => $item->filename,
                    'status' => $item->status,
                    'status_label' => $status['label'],
                    'status_color' => $status['color'],
                    'alasan' => $item->alasan,
                    'sending' => $item->sending,
                    'created_at' => $item->created_at,
                    'updated_at' => $item->updated_at,
                ];
            });

        // Filter humas data by selected year (only for humas tab)
        $humasYearFilter = null;
        if ($activeTab === 'humas') {
            $humasYearFilter = $request->input('humas_year');
            if (!$humasYearFilter || !preg_match('/^\d{4}$/', $humasYearFilter)) {
                $humasYearFilter = (int) date('Y');
            }
        }

        $humasData = DB::table('laporan_humas as lh')
            ->leftJoin('users as u', 'u.id', '=', 'lh.user_id')
            ->where('lh.user_id', $user->id)
            ->when($humasYearFilter, function ($query) use ($humasYearFilter) {
                // Filter by year if humas_year is provided
                return $query->whereRaw("LEFT(lh.bulan, 4) = ?", [$humasYearFilter]);
            })
            ->orderByDesc('lh.bulan')
            ->get()
            ->map(function ($item) {
                $data = json_decode((string) ($item->data ?? '{}'), true) ?: [];
                $platforms = collect(['facebook', 'instagram', 'tiktok', 'website', 'youtube'])->map(function (string $channel) use ($data) {
                    $channelData = $data[$channel] ?? [];

                    return [
                        'name' => $channel,
                        'has_data' => filled(data_get($channelData, 'first.date')) || filled(data_get($channelData, 'last.date')),
                        'first_date' => data_get($channelData, 'first.date'),
                        'first_content' => data_get($channelData, 'first.content'),
                        'first_link' => data_get($channelData, 'first.link'),
                        'last_date' => data_get($channelData, 'last.date'),
                        'last_content' => data_get($channelData, 'last.content'),
                        'last_link' => data_get($channelData, 'last.link'),
                    ];
                })->values();

                $verifikatorName = null;
                if ($item->verifikator_id) {
                    $verifikator = DB::table('users')->where('id', $item->verifikator_id)->first();
                    $verifikatorName = $verifikator?->name;
                }

                return [
                    'id' => $item->id,
                    'month_label' => $item->bulan ? Carbon::createFromFormat('Y-m-d', substr($item->bulan, 0, 10))->format('m/Y') : '-',
                    'bulan_full' => $item->bulan ? substr($item->bulan, 0, 7) : '',
                    'author' => $item->name ?? 'Unknown',
                    'status' => $item->status ?: '-',
                    'verifikator' => $verifikatorName,
                    'comment' => $item->komen ?: $item->user_komen ?: '-',
                    'updated_at' => $item->updated_at,
                    'platforms' => $platforms,
                    'active_channels' => $platforms->where('has_data', true)->count(),
                ];
            });

        $tabLabels = [
            'harian' => [
                'label' => 'Kinerja Harian',
                'icon' => 'document',
                'tone' => 'rose',
            ],
            'bulanan' => [
                'label' => 'Laporan Kinerja Bulanan',
                'icon' => 'calendar',
                'tone' => 'slate',
            ],
            'humas' => [
                'label' => 'Laporan Humas',
                'icon' => 'megaphone',
                'tone' => 'amber',
            ],
        ];

        $editingGroup = null;
        if ($editingDate !== '') {
            $editingRow = DB::table('satker_kegiatan')
                ->where('user_id', $user->id)
                ->whereDate('tanggal', $editingDate)
                ->first();

            if ($editingRow) {
                $dateCarbon = Carbon::parse($editingDate);
                $jsonData = json_decode((string) ($editingRow->data_json ?? '{"items":[]}'), true) ?: ['items' => []];
                $items = $jsonData['items'] ?? [];

                $editingGroup = [
                    'row_id' => $editingRow->id,
                    'date' => $dateCarbon->toDateString(),
                    'label' => $this->indonesianDateLabel($dateCarbon),
                    'items' => array_map(function ($item) {
                        return [
                            'id' => $item['id'] ?? null,
                            'kegiatan' => trim((string) ($item['k'] ?? '')),
                            'volume' => (int) ($item['v'] ?? 0),
                            'satuan' => $item['s'] ?? 'Kegiatan',
                        ];
                    }, $items),
                ];
            }
        }

        // Alias for view compatibility
        $humasReports = $humasData;

        return view('laporan-kinerja', [
            'activeTab' => $activeTab,
            'selectedMonth' => $selectedMonth,
            'selectedMonthLabel' => $this->indonesianMonthLabel($selectedMonthStart),
            'selectedYear' => $selectedYear,
            'selectedYearLabel' => $selectedYear,
            'search' => $search,
            'printMode' => $printMode,
            'editingGroup' => $editingGroup,
            'tabLabels' => $tabLabels,
            'dailyGroups' => $dailyGroups,
            'dailySummary' => $dailySummary,
            'activityUnits' => $activityUnits,
            'monthlyRecaps' => $monthlyRecaps,
            'bulananReports' => $bulananReports,
            'humasData' => $humasData,
            'humasYear' => $humasYearFilter,
            'monthlySummary' => [
                'months' => $monthlyRecaps->count(),
                'days' => $monthlyRecaps->sum('days'),
                'entries' => $monthlyRecaps->sum('entries'),
                'volume' => $monthlyRecaps->sum('total_volume'),
                'latest_update' => optional($monthlyRecaps->sortByDesc('latest_update')->first())->latest_update,
            ],
            'humasReports' => $humasReports,
        ]);
    }

    public function laporanKinerjaBawahan(Request $request)
    {
        $user = $request->user();

        abort_unless($user, 403);

        // Check role - only kepala, kasubbag, or kasi can access
        $allowedRoles = ['kepala', 'kasubbag', 'kasi'];
        $userRole = strtolower(trim((string) ($user->kat_jabatan ?? '')));

        if (!in_array($userRole, $allowedRoles)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        if (!$user->dept_id) {
            return view('laporan-kinerja-bawahan', [
                'error' => 'Unit kerja Anda belum ditetapkan. Hubungi administrator.',
                'reports' => collect([]),
                'selectedMonth' => date('Y-m'),
                'selectedMonthLabel' => now()->format('F Y'),
                'totalUsers' => 0,
                'userRole' => $userRole,
                'tabLabels' => [
                    'harian' => ['label' => 'Kinerja Harian'],
                    'bulanan' => ['label' => 'Laporan Bulanan'],
                    'humas' => ['label' => 'Laporan Kehumasan'],
                ],
            ]);
        }

        $selectedMonth = $request->string('month')->toString();
        if (!preg_match('/^\d{4}-\d{2}$/', $selectedMonth)) {
            $selectedMonth = now()->subMonth()->format('Y-m');
        }

        $monthStart = Carbon::createFromFormat('Y-m', $selectedMonth)->startOfMonth();
        $monthEnd = $monthStart->copy()->endOfMonth();

        // Get all users in the same department (excluding kepala/head and current user)
        $bawahanUsers = DB::table('users')
            ->where('dept_id', $user->dept_id)
            ->where('id', '!=', $user->id)
            ->where('kat_jabatan', '!=', 'kepala')
            ->whereNotIn('role', ['pindah', 'pensiun'])
            ->select(['id', 'name', 'kat_jabatan', 'pekerjaan'])
            ->get();

        $userIds = $bawahanUsers->pluck('id')->toArray();
        $totalUsers = count($userIds);

        // Get laporan bulanan (satker_ckh) data for all bawahan in selected month
        $submittedReports = DB::table('satker_ckh as ck')
            ->leftJoin('users as u', 'u.id', '=', 'ck.user_id')
            ->leftJoin('ktd_department as dept', 'dept.id', '=', 'ck.dept_id')
            ->whereIn('ck.user_id', $userIds)
            ->whereBetween('ck.bulan', [$monthStart->toDateString(), $monthEnd->toDateString()])
            ->select([
                'ck.id',
                'ck.user_id',
                'ck.dept_id',
                'ck.bulan',
                'ck.filename',
                'ck.status',
                'ck.alasan',
                'ck.petugas',
                'ck.sending',
                'ck.created_at',
                'ck.updated_at',
                'u.name as user_name',
                'u.pekerjaan',
                'dept.nama as dept_name',
            ])
            ->get()
            ->keyBy('user_id');

        // Build reports list - include ALL users, mark those without reports as "Belum Upload"
        $allReports = collect($bawahanUsers)->map(function ($user) use ($submittedReports, $monthStart) {
            $report = $submittedReports->get($user->id);

            if ($report) {
                $bulanDate = Carbon::createFromFormat('Y-m-d', substr($report->bulan, 0, 10));
                $statusColors = [
                    'DISETUJUI' => 'emerald',
                    'DIKIRIM' => 'amber',
                    'DITOLAK' => 'rose',
                ];

                return [
                    'id' => $report->id,
                    'user_id' => $report->user_id,
                    'user_name' => $report->user_name ?? $user->name,
                    'jabatan' => $report->pekerjaan ?? $user->pekerjaan ?? '-',
                    'bulan' => $bulanDate->format('Y-m'),
                    'bulan_label' => $bulanDate->format('F Y'),
                    'filename' => $report->filename,
                    'status' => $report->status,
                    'status_color' => $statusColors[$report->status] ?? 'slate',
                    'alasan' => $report->alasan,
                    'sending' => $report->sending,
                    'sending_formatted' => $this->indonesianDateTimeFormat($report->sending),
                    'created_at' => $report->created_at,
                    'updated_at' => $report->updated_at,
                    'has_report' => true,
                ];
            }

            // User has not submitted any report
            return [
                'id' => null,
                'user_id' => $user->id,
                'user_name' => $user->name,
                'jabatan' => $user->pekerjaan ?? '-',
                'bulan' => $monthStart->format('Y-m'),
                'bulan_label' => $monthStart->format('F Y'),
                'filename' => null,
                'status' => 'BELUM_UPLOAD',
                'status_color' => 'slate',
                'alasan' => null,
                'sending' => null,
                'sending_formatted' => '-',
                'created_at' => null,
                'updated_at' => null,
                'has_report' => false,
            ];
        })->sortBy('user_name')->values();

        // Sorting - default order by status priority (Ditolak, Dikirim, Disetujui, Belum Upload)
        $sortBy = $request->query('sort', 'status_priority');
        $sortDir = $request->query('dir', 'asc');

        // Define status priority for sorting
        $statusPriority = [
            'DITOLAK' => 1,
            'DIKIRIM' => 2,
            'DISETUJUI' => 3,
            'BELUM_UPLOAD' => 4,
        ];

        // Apply sorting based on field
        if ($sortBy === 'nama') {
            $allReports = $sortDir === 'asc'
                ? $allReports->sortBy('user_name')
                : $allReports->sortByDesc('user_name');
        } elseif ($sortBy === 'tanggal') {
            $allReports = $allReports->sortBy(function ($item) {
                return $item['sending'] ?? '1970-01-01';
            });
            if ($sortDir === 'desc') {
                $allReports = $allReports->reverse()->values();
            }
        } elseif ($sortBy === 'status') {
            $allReports = $allReports->sortBy(function ($item) use ($statusPriority) {
                return $statusPriority[$item['status']] ?? 99;
            });
            if ($sortDir === 'desc') {
                $allReports = $allReports->reverse()->values();
            }
        } else {
            // Default: sort by status priority (Ditolak, Dikirim, Disetujui, Belum Upload)
            $allReports = $allReports->sortBy(function ($item) use ($statusPriority) {
                return $statusPriority[$item['status']] ?? 99;
            });
        }

        // Calculate statistics from all reports (before pagination)
        $stats = [
            'total' => $allReports->count(),
            'disetujui' => $allReports->where('status', 'DISETUJUI')->count(),
            'dikirim' => $allReports->where('status', 'DIKIRIM')->count(),
            'ditolak' => $allReports->where('status', 'DITOLAK')->count(),
            'belum_upload' => $allReports->where('status', 'BELUM_UPLOAD')->count(),
        ];

        // Paginate the results (12 per page)
        $perPage = 12;
        $currentPage = (int) $request->query('page', 1);
        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $allReports->forPage($currentPage, $perPage),
            $allReports->count(),
            $perPage,
            $currentPage,
            ['path' => route('laporan-kinerja.bawahan', ['month' => $selectedMonth])]
        );

        return view('laporan-kinerja-bawahan', [
            'reports' => $paginator,
            'selectedMonth' => $selectedMonth,
            'selectedMonthLabel' => $monthStart->format('F Y'),
            'reportStats' => $stats,
            'userRole' => $userRole,
            'bawahanUsers' => $bawahanUsers,
            'deptName' => DB::table('ktd_department')->where('id', $user->dept_id)->value('nama') ?? 'Unit Kerja',
            'error' => null,
            'sortBy' => $sortBy,
            'sortDir' => $sortDir,
            'tabLabels' => [
                'harian' => ['label' => 'Kinerja Harian'],
                'bulanan' => ['label' => 'Laporan Bulanan'],
                'humas' => ['label' => 'Laporan Kehumasan'],
            ],
        ]);
    }

    public function profil(Request $request)
    {
        $user = $request->user();

        abort_unless($user, 403);

        $menuItems = [
            [
                'id' => 'data-pribadi',
                'title' => 'Data Pribadi',
                'icon' => '513.webp',
                'route' => 'profil.edit',
            ],
            [
                'id' => 'riwayat-pendidikan',
                'title' => 'Riwayat Pendidikan',
                'icon' => '514.webp',
                'route' => null,
            ],
            [
                'id' => 'riwayat-pekerjaan',
                'title' => 'Riwayat Pekerjaan',
                'icon' => '515.webp',
                'route' => null,
            ],
            [
                'id' => 'data-kgb',
                'title' => 'Data Kenaikan Gaji Berkala',
                'icon' => 'KGB.webp',
                'route' => null,
            ],
            [
                'id' => 'riwayat-slip-gaji',
                'title' => 'Riwayat Slip Gaji',
                'icon' => '516.webp',
                'route' => null,
            ],
            [
                'id' => 'dokumen-amprah',
                'title' => 'Dokumen Amprah',
                'icon' => 'keu003.webp',
                'route' => null,
            ],
            [
                'id' => 'laporan-kinerja',
                'title' => 'Laporan Kinerja',
                'icon' => 'LaporanKinerja.webp',
                'route' => 'laporan-kinerja',
            ],
        ];

        return view('profil', [
            'menuItems' => $menuItems,
            'user' => $user,
            'userDept' => $user->dept_id ? DB::table('ktd_department')->where('id', $user->dept_id)->value('nama') : null,
        ]);
    }

    public function editProfil(Request $request)
    {
        $user = $request->user();

        abort_unless($user, 403);

        // Get department name
        if ($user->dept_id == 999 || $user->dept_id == 998) {
            $satuanKerja = $user->satker ?? '-';
        } elseif ($user->dept_id) {
            $satuanKerja = DB::table('ktd_department')->where('id', $user->dept_id)->value('nama') ?? '-';
        } else {
            $satuanKerja = '-';
        }

        return view('profil-edit', [
            'user' => $user,
            'satuanKerja' => $satuanKerja,
        ]);
    }

    public function updateProfil(Request $request)
    {
        $user = $request->user();

        abort_unless($user, 403);

        $validator = Validator::make($request->all(), [
            'nip' => ['nullable', 'string', 'max:50'],
            'tempat_lahir' => ['nullable', 'string', 'max:100'],
            'tanggal_lahir' => ['nullable', 'date'],
            'jenis_kelamin' => ['nullable', 'string', 'in:laki-laki,perempuan'],
            'alamat' => ['nullable', 'string', 'max:500'],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email'],
            'npwp' => ['nullable', 'string', 'max:30'],
            'rekening' => ['nullable', 'string', 'max:30'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'facebook' => ['nullable', 'string', 'max:255'],
            'twitter' => ['nullable', 'string', 'max:255'],
            'linkedin' => ['nullable', 'string', 'max:255'],
            'instagram' => ['nullable', 'string', 'max:255'],
            'nikah' => ['nullable', 'in:0,1'],
            'jenis_pjob' => ['nullable', 'string', 'max:50'],
            'pjob' => ['nullable', 'string', 'max:255'],
            'jml_anak' => ['nullable', 'integer', 'min:0'],
            'req_tunjangan' => ['nullable', 'in:0,1'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();

        // Map jk values to jenis_kelamin for compatibility
        $jkMap = [
            'laki-laki' => 'laki-laki',
            'perempuan' => 'perempuan',
            'Pria' => 'laki-laki',
            'Wanita' => 'perempuan',
        ];
        $jenisKelamin = $data['jenis_kelamin'] ?? null;
        if ($jenisKelamin && isset($jkMap[$jenisKelamin])) {
            $jenisKelamin = $jkMap[$jenisKelamin];
        }

        DB::table('users')->where('id', $user->id)->update([
            'nip' => $data['nip'] ?? null,
            'tempat_lahir' => $data['tempat_lahir'] ?? null,
            'tanggal_lahir' => $data['tanggal_lahir'] ?? null,
            'jk' => $jenisKelamin,
            'alamat' => $data['alamat'] ?? null,
            'telp' => $data['no_hp'] ?? null,
            'email' => $data['email'] ?? null,
            'npwp' => $data['npwp'] ?? null,
            'rekening' => $data['rekening'] ?? null,
            'bio' => $data['bio'] ?? null,
            'facebook' => $data['facebook'] ?? null,
            'twitter' => $data['twitter'] ?? null,
            'linkedin' => $data['linkedin'] ?? null,
            'instagram' => $data['instagram'] ?? null,
            'nikah' => $data['nikah'] ?? null,
            'jenis_pjob' => $data['jenis_pjob'] ?? null,
            'pjob' => $data['pjob'] ?? null,
            'jml_anak' => $data['jml_anak'] ?? null,
            'req_tunjangan' => $data['req_tunjangan'] ?? null,
            'updated_at' => now(),
        ]);

        return redirect()->route('profil')->with('success', 'Profil berhasil diperbarui.');
    }

    public function ubahPassword(Request $request)
    {
        $user = $request->user();
        abort_unless($user, 403);

        return view('ubah-password');
    }

    public function updatePassword(Request $request)
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
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Password lama salah.'], 400);
            }
            return back()->withErrors(['current_password' => 'Password lama salah.'])->withInput();
        }

        DB::table('users')->where('id', $user->id)->update([
            'password' => Hash::make($request->password),
            'updated_at' => now(),
        ]);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Password berhasil diubah.']);
        }

        return redirect()->route('home')->with('success', 'Password berhasil diubah.');
    }

    public function rekapLaporanKinerja(Request $request)
    {
        $user = $request->user();

        abort_unless($user, 403);

        $selectedMonth = $request->string('month')->toString();
        if (! preg_match('/^\d{4}-\d{2}$/', $selectedMonth)) {
            $selectedMonth = now()->format('Y-m');
        }

        $selectedMonthStart = Carbon::createFromFormat('Y-m', $selectedMonth)->startOfMonth();
        $selectedMonthEnd = $selectedMonthStart->copy()->endOfMonth();
        $periodLabel = $this->indonesianMonthLabel($selectedMonthStart);

        $unitName = DB::table('ktd_department')
            ->where('id', $user->dept_id)
            ->value('nama');

        // Cek apakah dept_id memerlukan input manual atasan
        $specialDeptIds = [998, 999];
        $deptId = (int) $user->dept_id;

        if (in_array($deptId, $specialDeptIds)) {
            // Tampilkan form input atasan
            return view('pdf.supervisor-input', [
                'deptId' => $deptId,
                'month' => $selectedMonth,
                'tab' => $request->input('tab', 'harian'),
                'unitName' => $unitName ?: '-',
                'periodLabel' => $periodLabel,
            ]);
        }

        $dailyEntries = DB::table('satker_kegiatan')
            ->where('user_id', $user->id)
            ->whereBetween('tanggal', [$selectedMonthStart->toDateString(), $selectedMonthEnd->toDateString()])
            ->orderBy('tanggal')
            ->orderBy('created_at')
            ->get();

        $dailyGroups = $dailyEntries
            ->groupBy(fn ($row) => Carbon::parse($row->tanggal)->toDateString())
            ->map(function ($items, $date) {
                $dateCarbon = Carbon::parse($date);

                $allItems = [];
                foreach ($items as $item) {
                    // Try JSON format first
                    $jsonData = json_decode((string) ($item->data_json ?? '{"items":[]}'), true) ?: ['items' => []];
                    $itemsArr = $jsonData['items'] ?? [];

                    // Handle legacy format (direct columns)
                    if (empty($itemsArr) && !empty($item->kegiatan)) {
                        $itemsArr = [[
                            'k' => $item->kegiatan,
                            'v' => $item->volume ?? 0,
                            's' => $item->satuan ?? 'Kegiatan'
                        ]];
                    }

                    foreach ($itemsArr as $it) {
                        $volume = (int) ($it['v'] ?? ($it['volume'] ?? 0));
                        $unit = trim((string) ($it['s'] ?? ($it['satuan'] ?? 'Kegiatan')));

                        $allItems[] = [
                            'kegiatan' => trim((string) ($it['k'] ?? ($it['kegiatan'] ?? ''))),
                            'volume' => $volume,
                            'satuan' => $unit,
                            'meta' => $volume > 0 ? trim($volume . ' ' . $unit) : $unit,
                        ];
                    }
                }

                return [
                    'date' => $dateCarbon->toDateString(),
                    'label' => $this->indonesianDateLabel($dateCarbon),
                    'items' => $allItems,
                ];
            })
            ->values()
            ->all();

        // Cek PLT/PJH di tabel plt_plh
        $pltPlh = DB::table('plt_plh')
            ->where('dept_id_plh', $user->dept_id)
            ->first();

        $isPlh = false;
        $isCustomSupervisor = false;
        $signatureName = '..................................';
        $signatureNip = '';

        // Cek apakah user adalah atasan (kepala, kasi, kasubbag)
        $atasanJabatan = ['kepala', 'kasi', 'kasubbag'];
        $isUserAtasan = in_array($user->kat_jabatan, $atasanJabatan);

        // Cek custom supervisor dulu (priority tertinggi)
        if (!empty($user->custom_supervisor_id)) {
            $customSupervisor = DB::table('users')
                ->where('id', $user->custom_supervisor_id)
                ->first();

            if ($customSupervisor) {
                $isCustomSupervisor = true;
                $signatureName = $customSupervisor->name;
                $signatureNip = $customSupervisor->nomor_induk
                    ? 'NIP. ' . $customSupervisor->nomor_induk
                    : '';
            }
        }

        if ($isCustomSupervisor) {
            // Custom supervisor sudah di-set di atas, skip logic lain
            // Signature name dan nip sudah di-set
        } elseif ($isUserAtasan) {
            // Jika user adalah atasan, penandatangan adalah Kepala Kankemenag
            $kepalaKankemenag = DB::table('users')
                ->where('role', 'kepala')
                ->first();

            if ($kepalaKankemenag) {
                $signatureName = $kepalaKankemenag->name;
                $signatureNip = $kepalaKankemenag->nomor_induk ? 'NIP. ' . $kepalaKankemenag->nomor_induk : '';
            }
        } elseif ($pltPlh) {
            // PLT exist - gunakan user PLT
            $pltUser = DB::table('users')->where('id', $pltPlh->user_id)->first();
            if ($pltUser) {
                $isPlh = true;
                $signatureName = $pltUser->name;
                $signatureNip = $pltUser->nomor_induk ? 'NIP. ' . $pltUser->nomor_induk : '';
            }
        } else {
            // Cari kepala/kasi/kasubbag berdasarkan dept_id
            $kepala = DB::table('users')
                ->where('dept_id', $user->dept_id)
                ->whereIn('kat_jabatan', $atasanJabatan)
                ->first();

            if ($kepala) {
                $signatureName = $kepala->name;
                $signatureNip = $kepala->nomor_induk ? 'NIP. ' . $kepala->nomor_induk : '';
            }
        }

        // Determine signature label based on user role and dept_id
        $specialDeptIds = [998, 999];
        $kepalaLabel = in_array((int) $user->dept_id, $specialDeptIds)
            ? ($user->satker ?? $unitName)
            : ($unitName ?: '-');

        if ($isCustomSupervisor) {
            // Custom supervisor - tentukan label berdasarkan kat_jabatan supervisor
            $customSupervisorJabatan = $customSupervisor->kat_jabatan ?? '';
            if ($customSupervisorJabatan === 'kepala') {
                $signatureLabel = 'Mengetahui<br>Kepala Kankemenag Kab. Tanah Datar,';
            } else {
                $signatureLabel = "Mengetahui<br>Kepala {$kepalaLabel},";
            }
        } elseif ($isUserAtasan) {
            $signatureLabel = 'Mengetahui<br>Kepala Kankemenag Kab. Tanah Datar,';
        } elseif ($isPlh) {
            $signatureLabel = 'Mengetahui<br>PLT Kepala,';
        } else {
            $signatureLabel = "Mengetahui<br>Kepala {$kepalaLabel},";
        }

        $pdfData = [
            'userName' => $user->name,
            'userNip' => $user->nomor_induk ?: '-',
            'unitName' => $unitName ?: '-',
            'positionName' => trim((string) ($user->pekerjaan ?: '-')) ?: '-',
            'periodLabel' => $periodLabel,
            'dailyGroups' => $dailyGroups,
            'headerImage' => $this->assetToDataUri(public_path('assets/img/template/header.png')),
            'generatedAt' => now()->translatedFormat('d F Y H:i'),
            'signatureName' => $signatureName,
            'signatureNip' => $signatureNip,
            'signatureImage' => null,
            'signatureLabel' => $signatureLabel,
            'watermarkText' => 'Kankemenag Kab.Tanah Datar',
        ];

        $pdf = Pdf::loadView('pdf.laporan-kinerja-harian', $pdfData)
            ->setPaper('a4', 'portrait')
            ->setOption('isRemoteEnabled', true)
            ->setOption('isHtml5ParserEnabled', true);

        $filename = sprintf('%s.kinerja-%s.pdf', $user->id, $selectedMonthStart->format('m-Y'));
        $storagePath = "satker_ckh/{$user->id}/{$filename}";
        $pdfBinary = $pdf->output();

        // Ensure directory exists before saving
        $fullDirPath = storage_path('app/public/satker_ckh/' . $user->id);
        if (! is_dir($fullDirPath)) {
            if (! mkdir($fullDirPath, 0755, true) && ! is_dir($fullDirPath)) {
                Log::error('Gagal membuat direktori untuk PDF CKH', [
                    'user_id' => $user->id,
                    'path' => $fullDirPath,
                ]);
            }
        }

        // Save PDF with error handling
        $saved = Storage::disk('public')->put($storagePath, $pdfBinary);
        if (! $saved) {
            Log::error('Gagal menyimpan PDF CKH ke storage', [
                'user_id' => $user->id,
                'storage_path' => $storagePath,
                'full_path' => $fullDirPath,
                'file_exists' => file_exists($fullDirPath),
                'is_writable' => is_writable($fullDirPath ?? ''),
            ]);
            // Still return the PDF to user even if storage fails
        }

        $reportData = [
            'item_id' => 1,
            'dept_id' => $user->dept_id,
            'user_id' => $user->id,
            'bulan' => $selectedMonthStart->toDateString(),
            'filename' => $filename,
            'status' => 'DIKIRIM',
            'alasan' => null,
            'petugas' => 777,
            'sending' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ];

        DB::table('satker_ckh')->updateOrInsert(
            [
                'item_id' => 1,
                'dept_id' => $user->dept_id,
                'user_id' => $user->id,
                'bulan' => $selectedMonthStart->toDateString(),
            ],
            $reportData
        );

        return response($pdfBinary, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
        ]);
    }

    public function submitSupervisor(Request $request)
    {
        $user = $request->user();

        abort_unless($user, 403);

        $validator = Validator::make($request->all(), [
            'dept_id' => ['required', 'integer'],
            'month' => ['required', 'string'],
            'tab' => ['nullable', 'string'],
            'supervisor_name' => ['required', 'string', 'max:255'],
            'supervisor_nip' => ['required', 'string', 'max:50'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();

        $specialDeptIds = [998, 999];
        if (! in_array((int) $data['dept_id'], $specialDeptIds)) {
            abort(403, 'Departemen tidak valid untuk input atasan.');
        }

        $selectedMonth = $data['month'];
        $selectedMonthStart = Carbon::createFromFormat('Y-m', $selectedMonth)->startOfMonth();
        $selectedMonthEnd = $selectedMonthStart->copy()->endOfMonth();
        $periodLabel = $this->indonesianMonthLabel($selectedMonthStart);

        $unitName = DB::table('ktd_department')
            ->where('id', $user->dept_id)
            ->value('nama');

        $dailyEntries = DB::table('satker_kegiatan')
            ->where('user_id', $user->id)
            ->whereBetween('tanggal', [$selectedMonthStart->toDateString(), $selectedMonthEnd->toDateString()])
            ->orderBy('tanggal')
            ->orderBy('created_at')
            ->get();

        $dailyGroups = $dailyEntries
            ->groupBy(fn ($row) => Carbon::parse($row->tanggal)->toDateString())
            ->map(function ($items, $date) {
                $dateCarbon = Carbon::parse($date);

                $allItems = [];
                foreach ($items as $item) {
                    $jsonData = json_decode((string) ($item->data_json ?? '{"items":[]}'), true) ?: ['items' => []];
                    $itemsArr = $jsonData['items'] ?? [];

                    if (empty($itemsArr) && ! empty($item->kegiatan)) {
                        $itemsArr = [[
                            'k' => $item->kegiatan,
                            'v' => $item->volume ?? 0,
                            's' => $item->satuan ?? 'Kegiatan',
                        ]];
                    }

                    foreach ($itemsArr as $it) {
                        $volume = (int) ($it['v'] ?? ($it['volume'] ?? 0));
                        $unit = trim((string) ($it['s'] ?? ($it['satuan'] ?? 'Kegiatan')));

                        $allItems[] = [
                            'kegiatan' => trim((string) ($it['k'] ?? ($it['kegiatan'] ?? ''))),
                            'volume' => $volume,
                            'satuan' => $unit,
                            'meta' => $volume > 0 ? trim($volume . ' ' . $unit) : $unit,
                        ];
                    }
                }

                return [
                    'date' => $dateCarbon->toDateString(),
                    'label' => $this->indonesianDateLabel($dateCarbon),
                    'items' => $allItems,
                ];
            })
            ->values()
            ->all();

        $pltPlh = DB::table('plt_plh')
            ->where('dept_id_plh', $user->dept_id)
            ->first();

        $isPlh = false;
        $signatureName = $data['supervisor_name'];
        $signatureNip = 'NIP. ' . $data['supervisor_nip'];

        if ($pltPlh) {
            $pltUser = DB::table('users')->where('id', $pltPlh->user_id)->first();
            if ($pltUser) {
                $isPlh = true;
                $signatureName = $pltUser->name;
                $signatureNip = $pltUser->nomor_induk ? 'NIP. ' . $pltUser->nomor_induk : '';
            }
        }

        // For dept_id 998/999, use user's satker field
        $kepalaLabel = $user->satker ?? $unitName;

        $pdfData = [
            'userName' => $user->name,
            'userNip' => $user->nomor_induk ?: '-',
            'unitName' => $unitName ?: '-',
            'positionName' => trim((string) ($user->pekerjaan ?: '-')) ?: '-',
            'periodLabel' => $periodLabel,
            'dailyGroups' => $dailyGroups,
            'headerImage' => $this->assetToDataUri(public_path('assets/img/template/header.png')),
            'generatedAt' => now()->translatedFormat('d F Y H:i'),
            'signatureName' => $signatureName,
            'signatureNip' => $signatureNip,
            'signatureImage' => null,
            'signatureLabel' => $isPlh ? 'Mengetahui<br>PLT Kepala,' : "Mengetahui<br>Kepala {$kepalaLabel},",
            'watermarkText' => 'Kankemenag Kab.Tanah Datar',
        ];

        $pdf = Pdf::loadView('pdf.laporan-kinerja-harian', $pdfData)
            ->setPaper('a4', 'portrait')
            ->setOption('isRemoteEnabled', true)
            ->setOption('isHtml5ParserEnabled', true);

        $filename = sprintf('%s.kinerja-%s.pdf', $user->id, $selectedMonthStart->format('m-Y'));
        $storagePath = "satker_ckh/{$user->id}/{$filename}";
        $pdfBinary = $pdf->output();

        // Ensure directory exists before saving
        $fullDirPath = storage_path('app/public/satker_ckh/' . $user->id);
        if (! is_dir($fullDirPath)) {
            if (! mkdir($fullDirPath, 0755, true) && ! is_dir($fullDirPath)) {
                Log::error('Gagal membuat direktori untuk PDF CKH', [
                    'user_id' => $user->id,
                    'path' => $fullDirPath,
                ]);
            }
        }

        // Save PDF with error handling
        $saved = Storage::disk('public')->put($storagePath, $pdfBinary);
        if (! $saved) {
            Log::error('Gagal menyimpan PDF CKH ke storage', [
                'user_id' => $user->id,
                'storage_path' => $storagePath,
                'full_path' => $fullDirPath,
                'file_exists' => file_exists($fullDirPath),
                'is_writable' => is_writable($fullDirPath ?? ''),
            ]);
        }

        $reportData = [
            'item_id' => 1,
            'dept_id' => $user->dept_id,
            'user_id' => $user->id,
            'bulan' => $selectedMonthStart->toDateString(),
            'filename' => $filename,
            'status' => 'DIKIRIM',
            'alasan' => null,
            'petugas' => 777,
            'sending' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ];

        DB::table('satker_ckh')->updateOrInsert(
            [
                'item_id' => 1,
                'dept_id' => $user->dept_id,
                'user_id' => $user->id,
                'bulan' => $selectedMonthStart->toDateString(),
            ],
            $reportData
        );

        return response($pdfBinary, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
        ]);
    }

    public function downloadLaporanKinerjaPdf(Request $request, int $reportId)
    {
        $user = $request->user();

        abort_unless($user, 403);

        $report = DB::table('satker_ckh')
            ->where('id', $reportId)
            ->where('user_id', $user->id)
            ->first();

        abort_unless($report, 404);
        abort_unless(filled($report->filename), 404);

        $storagePath = "satker_ckh/{$report->user_id}/{$report->filename}";

        abort_unless(Storage::disk('public')->exists($storagePath), 404);

        return Storage::disk('public')->response($storagePath, $report->filename, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $report->filename . '"',
        ]);
    }

    public function replaceLaporanKinerjaFile(Request $request, int $reportId)
    {
        $user = $request->user();

        abort_unless($user, 403);

        $validator = Validator::make($request->all(), [
            'file' => ['required', 'file', 'mimes:pdf', 'max:10240'], // max 10MB
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Get existing report
        $report = DB::table('satker_ckh')
            ->where('id', $reportId)
            ->where('user_id', $user->id)
            ->first();

        abort_unless($report, 404);

        // Check if status allows replacement (only DIKIRIM or DITOLAK)
        if (!in_array($report->status, ['DIKIRIM', 'DITOLAK'])) {
            return back()->with('error', 'File hanya bisa diganti jika status laporan DIKIRIM atau DITOLAK.');
        }

        // Get uploaded file
        $uploadedFile = $request->file('file');

        // Delete old file if exists
        if ($report->filename) {
            $oldStoragePath = "satker_ckh/{$report->user_id}/{$report->filename}";
            if (Storage::disk('public')->exists($oldStoragePath)) {
                Storage::disk('public')->delete($oldStoragePath);
            }
        }

        // Generate new filename
        $filename = sprintf('%s.kinerja-%s.pdf', $user->id, $report->bulan);
        $storagePath = "satker_ckh/{$user->id}/{$filename}";

        // Ensure directory exists before saving
        $fullDirPath = storage_path('app/public/satker_ckh/' . $user->id);
        if (! is_dir($fullDirPath)) {
            if (! mkdir($fullDirPath, 0755, true) && ! is_dir($fullDirPath)) {
                Log::error('Gagal membuat direktori untuk PDF CKH', [
                    'user_id' => $user->id,
                    'path' => $fullDirPath,
                ]);
            }
        }

        // Store new file with error handling
        $fileContent = file_get_contents($uploadedFile->getRealPath());
        $saved = Storage::disk('public')->put($storagePath, $fileContent);
        if (! $saved) {
            Log::error('Gagal menyimpan PDF CKH (replace) ke storage', [
                'user_id' => $user->id,
                'report_id' => $reportId,
                'storage_path' => $storagePath,
            ]);
            return back()->with('error', 'Gagal menyimpan file. Silakan coba lagi.');
        }

        // Update record - set status to DIKIRIM
        DB::table('satker_ckh')
            ->where('id', $reportId)
            ->update([
                'filename' => $filename,
                'status' => 'DIKIRIM',
                'alasan' => null,
                'sending' => now(),
                'updated_at' => now(),
            ]);

        return redirect()
            ->back()
            ->with('success', 'File berhasil diganti. Status laporan diubah menjadi DIKIRIM.');
    }

    public function uploadLaporanKinerjaManual(Request $request)
    {
        $user = $request->user();

        abort_unless($user, 403);

        $validator = Validator::make($request->all(), [
            'bulan' => ['required', 'string', 'regex:/^\d{4}-\d{2}$/'],
            'file' => ['required', 'file', 'mimes:pdf', 'max:10240'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();

        // Parse bulan to get the date
        $bulanDate = Carbon::createFromFormat('Y-m', $data['bulan'])->startOfMonth();

        // Check if report already exists and is DISETUJUI (approved)
        $existingReport = DB::table('satker_ckh')
            ->where('user_id', $user->id)
            ->where('bulan', $bulanDate->toDateString())
            ->where('status', 'DISETUJUI')
            ->first();

        if ($existingReport) {
            return back()->with('error', 'Tidak dapat mengupload laporan. Laporan sudah DISETUJUI oleh atasan.');
        }

        // Get uploaded file
        $uploadedFile = $request->file('file');

        // Delete old file if exists
        $oldReport = DB::table('satker_ckh')
            ->where('user_id', $user->id)
            ->where('bulan', $bulanDate->toDateString())
            ->first();

        if ($oldReport && $oldReport->filename) {
            $oldStoragePath = "satker_ckh/{$user->id}/{$oldReport->filename}";
            if (Storage::disk('public')->exists($oldStoragePath)) {
                Storage::disk('public')->delete($oldStoragePath);
            }
        }

        // Generate filename
        $filename = sprintf('%s.kinerja-%s.pdf', $user->id, $bulanDate->format('m-Y'));
        $storagePath = "satker_ckh/{$user->id}/{$filename}";

        // Ensure directory exists before saving
        $fullDirPath = storage_path('app/public/satker_ckh/' . $user->id);
        if (! is_dir($fullDirPath)) {
            if (! mkdir($fullDirPath, 0755, true) && ! is_dir($fullDirPath)) {
                Log::error('Gagal membuat direktori untuk PDF CKH', [
                    'user_id' => $user->id,
                    'path' => $fullDirPath,
                ]);
            }
        }

        // Store file with error handling
        $fileContent = file_get_contents($uploadedFile->getRealPath());
        $saved = Storage::disk('public')->put($storagePath, $fileContent);
        if (! $saved) {
            Log::error('Gagal menyimpan PDF CKH (upload manual) ke storage', [
                'user_id' => $user->id,
                'bulan' => $data['bulan'],
                'storage_path' => $storagePath,
            ]);
            return back()->with('error', 'Gagal menyimpan file. Silakan coba lagi.');
        }

        // Update or insert record
        $reportData = [
            'item_id' => 1,
            'dept_id' => $user->dept_id,
            'user_id' => $user->id,
            'bulan' => $bulanDate->toDateString(),
            'filename' => $filename,
            'status' => 'DIKIRIM',
            'alasan' => null,
            'petugas' => 777,
            'sending' => now(),
            'created_at' => $oldReport?->created_at ?? now(),
            'updated_at' => now(),
        ];

        DB::table('satker_ckh')->updateOrInsert(
            [
                'user_id' => $user->id,
                'bulan' => $bulanDate->toDateString(),
            ],
            $reportData
        );

        $bulanLabel = $this->indonesianMonthLabel($bulanDate);

        return redirect()
            ->back()
            ->with('success', "Laporan {$bulanLabel} berhasil diupload.");
    }

    public function storeLaporanKinerja(Request $request)
    {
        $user = $request->user();

        abort_unless($user, 403);

        $validator = Validator::make($request->all(), [
            'tanggal' => ['required', 'date'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.kegiatan' => ['nullable', 'string', 'max:1000'],
            'items.*.volume' => ['nullable', 'integer', 'min:0'],
            'items.*.satuan' => ['nullable', 'string', 'max:50'],
            'tab' => ['nullable', 'string'],
            'month' => ['nullable', 'string'],
            'search' => ['nullable', 'string'],
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('open_add_modal', true);
        }

        $data = $validator->validated();
        $rows = $this->sanitizeActivityRows($data['items'] ?? [], (string) ($request->input('default_unit', 'Kegiatan')))
            ->filter(fn ($item) => $item['kegiatan'] !== '')
            ->values();

        if ($rows->isEmpty()) {
            return back()
                ->withErrors(['items' => 'Tambahkan minimal satu kegiatan.'])
                ->withInput()
                ->with('open_add_modal', true);
        }

        foreach ($rows as $index => $row) {
            $rowValidator = Validator::make($row, [
                'kegiatan' => ['required', 'string', 'max:1000'],
                'volume' => ['nullable', 'integer', 'min:0'],
                'satuan' => ['required', 'string', 'max:50'],
            ]);

            if ($rowValidator->fails()) {
                return back()
                    ->withErrors($rowValidator)
                    ->withInput()
                    ->with('open_add_modal', true);
            }
        }

        // Build JSON data structure (Option A: JSON Column)
        $jsonItems = $rows->map(fn ($row, $index) => [
            'id' => null, // null = new item
            'k' => $row['kegiatan'],
            'v' => (int) ($row['volume'] ?? 0),
            's' => $row['satuan'],
        ])->toArray();

        $jsonData = json_encode(['items' => $jsonItems], JSON_UNESCAPED_UNICODE);
        $tanggal = $data['tanggal'];
        $submittedAt = now();

        // Check if record exists for this user + date
        $existing = DB::table('satker_kegiatan')
            ->where('user_id', $user->id)
            ->whereDate('tanggal', $tanggal)
            ->first();

        if ($existing) {
            // Merge new items with existing data
            $existingData = json_decode((string) ($existing->data_json ?? '{"items":[]}'), true) ?: ['items' => []];
            $existingItems = $existingData['items'] ?? [];

            // Append new items (those with null id)
            $maxId = 0;
            foreach ($existingItems as $item) {
                if (isset($item['id']) && $item['id'] > $maxId) {
                    $maxId = $item['id'];
                }
            }

            foreach ($jsonItems as &$item) {
                if ($item['id'] === null) {
                    $item['id'] = ++$maxId;
                }
            }
            unset($item);

            $allItems = array_merge($existingItems, $jsonItems);
            $newJsonData = json_encode(['items' => $allItems], JSON_UNESCAPED_UNICODE);

            // Update existing row with merged data
            DB::table('satker_kegiatan')
                ->where('id', $existing->id)
                ->update([
                    'data_json' => $newJsonData,
                    'updated_at' => $submittedAt,
                ]);
        } else {
            // Insert new row with JSON data
            // Re-assign IDs starting from 1
            $jsonItems = array_map(function ($item, $index) {
                $item['id'] = $index + 1;
                return $item;
            }, $jsonItems, array_keys($jsonItems));

            DB::table('satker_kegiatan')->insert([
                'user_id' => $user->id,
                'tanggal' => $tanggal,
                'kegiatan' => $rows->first()['kegiatan'], // Keep first for compatibility
                'volume' => $rows->first()['volume'] ?? 0,
                'satuan' => $rows->first()['satuan'] ?? 'Kegiatan',
                'staff_id' => $user->id,
                'data_json' => json_encode(['items' => $jsonItems], JSON_UNESCAPED_UNICODE),
                'created_at' => $submittedAt,
                'updated_at' => $submittedAt,
            ]);
        }

        // Logika duplikasi kegiatan ke atasan/supervisor (migrate dari format lama)
        $this->duplicateKegiatanToAtasan($user, $tanggal, $rows, $submittedAt);

        return redirect()
            ->route('laporan-kinerja', [
                'tab' => 'harian',
                'month' => Carbon::parse($tanggal)->format('Y-m'),
            ])
            ->with('success', 'Kegiatan harian berhasil ditambahkan.');
    }

    /**
     * Duplicate kegiatan to atasan/supervisor based on specific conditions
     * Migrated from old format (kegiatan table) to new format (satker_kegiatan table)
     */
    private function duplicateKegiatanToAtasan($user, string $tanggal, $rows, $submittedAt)
    {
        // Helper function to insert/update duplicate kegiatan
        $insertDuplicate = function (int $targetUserId, string $prefix, array $items) use ($tanggal, $submittedAt) {
            // Build JSON items with prefix
            $duplicateItems = array_map(function ($item, $index) use ($prefix) {
                return [
                    'id' => $index + 1,
                    'k' => $prefix . ' ' . $item['kegiatan'],
                    'v' => (int) ($item['volume'] ?? 0),
                    's' => $item['satuan'],
                ];
            }, $items, array_keys($items));

            $duplicateJsonData = json_encode(['items' => $duplicateItems], JSON_UNESCAPED_UNICODE);

            // Check if record exists for target user + date
            $existing = DB::table('satker_kegiatan')
                ->where('user_id', $targetUserId)
                ->whereDate('tanggal', $tanggal)
                ->first();

            if ($existing) {
                // Merge with existing data
                $existingData = json_decode((string) ($existing->data_json ?? '{"items":[]}'), true) ?: ['items' => []];
                $existingItems = $existingData['items'] ?? [];

                // Get max ID
                $maxId = 0;
                foreach ($existingItems as $item) {
                    if (isset($item['id']) && $item['id'] > $maxId) {
                        $maxId = $item['id'];
                    }
                }

                // Assign IDs to new items
                foreach ($duplicateItems as &$item) {
                    $item['id'] = ++$maxId;
                }
                unset($item);

                $allItems = array_merge($existingItems, $duplicateItems);
                $newJsonData = json_encode(['items' => $allItems], JSON_UNESCAPED_UNICODE);

                DB::table('satker_kegiatan')
                    ->where('id', $existing->id)
                    ->update([
                        'data_json' => $newJsonData,
                        'updated_at' => $submittedAt,
                    ]);
            } else {
                $firstItem = !empty($itemsArray) ? $itemsArray[0] : null;
                DB::table('satker_kegiatan')->insert([
                    'user_id' => $targetUserId,
                    'tanggal' => $tanggal,
                    'kegiatan' => $firstItem['kegiatan'] ?? '',
                    'volume' => $firstItem['volume'] ?? 0,
                    'satuan' => $firstItem['satuan'] ?? 'Kegiatan',
                    'staff_id' => $user->id ?? null,
                    'data_json' => $duplicateJsonData,
                    'created_at' => $submittedAt,
                    'updated_at' => $submittedAt,
                ]);
            }
        };

        // Convert rows collection to array for reuse
        $itemsArray = $rows->toArray();

        // Kondisi 1: dept_id = 5 + role petugas/pegawai -> koordinasi ke atasan
        if ($user->dept_id == 5 && in_array($user->role, ['petugas', 'pegawai'])) {
            $atasan = DB::table('users')
                ->where(function ($query) {
                    $query->where('kat_jabatan', 'kasi')
                        ->orWhere('kat_jabatan', 'kepala')
                        ->orWhere('kat_jabatan', 'kasubbag');
                })
                ->where('dept_id', 5)
                ->first();

            if ($atasan) {
                $insertDuplicate($atasan->id, 'Mengkoordinir staff dalam', $itemsArray);
            }
        }

        // Kondisi 2: user_id IN (10, 15, 2478) + dalam periode tertentu -> bantu ke user_id 16
        $specialUserIds = [10, 15, 2478];
        if (in_array($user->id, $specialUserIds)) {
            $date = Carbon::now()->format('Y-m-d H:i');
            $start = Carbon::parse($date)->startOfMonth()->addDays(2)->format('Y-m-d 07:00');
            $end = Carbon::parse($date)->endOfMonth()->format('Y-m-d 23:00');

            if ($date > $start && $date < $end) {
                $insertDuplicate(16, 'Membantu dalam', $itemsArray);
            }
        }

        // Kondisi 3: user_id = 15 -> bantu ke user_id 45
        if ($user->id == 15) {
            $insertDuplicate(45, 'Membantu dalam', $itemsArray);
        }

        // Kondisi 4: dept_id = 6 + role petugas/pegawai -> koordinasi ke user_id 29
        if ($user->dept_id == 6 && in_array($user->role, ['petugas', 'pegawai'])) {
            $insertDuplicate(29, 'Mengkoordinir staff dalam', $itemsArray);
        }
    }

    public function storeHumas(Request $request)
    {
        $user = $request->user();
        abort_unless($user, 403);

        $validated = $request->validate([
            'bulan' => ['required', 'date_format:Y-m'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        // Process platform data from request
        $platforms = ['facebook', 'instagram', 'tiktok', 'website', 'youtube'];
        $platformData = [];

        foreach ($platforms as $platform) {
            $platformData[$platform] = [
                'first' => [
                    'date' => $request->input("{$platform}.first.date") ?: '',
                    'content' => $request->input("{$platform}.first.content") ?: '',
                    'link' => $request->input("{$platform}.first.link") ?: '',
                ],
                'last' => [
                    'date' => $request->input("{$platform}.last.date") ?: '',
                    'content' => $request->input("{$platform}.last.content") ?: '',
                    'link' => $request->input("{$platform}.last.link") ?: '',
                ],
            ];
        }

        // Check if record already exists for this month
        $existing = DB::table('laporan_humas')
            ->where('user_id', $user->id)
            ->where('bulan', $validated['bulan'] . '-01')
            ->first();

        if ($existing) {
            // Update existing record
            DB::table('laporan_humas')
                ->where('id', $existing->id)
                ->update([
                    'data' => json_encode($platformData),
                    'user_komen' => $validated['comment'] ?? '',
                    'updated_at' => now(),
                ]);
        } else {
            // Insert new record
            DB::table('laporan_humas')->insert([
                'user_id' => $user->id,
                'dept_id' => $user->dept_id ?? null,
                'bulan' => $validated['bulan'] . '-01',
                'data' => json_encode($platformData),
                'user_komen' => $validated['comment'] ?? '',
                'status' => 'tersimpan',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return redirect()
            ->route('laporan-kinerja', ['tab' => 'humas'])
            ->with('success', 'Laporan humas berhasil disimpan.');
    }

    public function destroyHumas(Request $request, int $id)
    {
        $user = $request->user();
        abort_unless($user, 403);

        $report = DB::table('laporan_humas')
            ->where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        abort_unless($report, 404);

        DB::table('laporan_humas')->where('id', $id)->delete();

        return redirect()
            ->route('laporan-kinerja', ['tab' => 'humas'])
            ->with('success', 'Laporan humas berhasil dihapus.');
    }

    public function updateLaporanKinerjaByDate(Request $request)
    {
        $user = $request->user();

        abort_unless($user, 403);

        $validator = Validator::make($request->all(), [
            'tanggal' => ['required', 'date'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.kegiatan' => ['nullable', 'string', 'max:1000'],
            'items.*.volume' => ['nullable', 'integer', 'min:0'],
            'items.*.satuan' => ['nullable', 'string', 'max:50'],
            'tab' => ['nullable', 'string'],
            'month' => ['nullable', 'string'],
            'search' => ['nullable', 'string'],
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('open_edit_modal', true);
        }

        $data = $validator->validated();
        $rows = $this->sanitizeActivityRows($data['items'] ?? [], (string) $request->input('default_unit', 'Kegiatan'))
            ->filter(fn ($item) => $item['kegiatan'] !== '')
            ->values();

        if ($rows->isEmpty()) {
            return back()
                ->withErrors(['items' => 'Tambahkan minimal satu kegiatan.'])
                ->withInput()
                ->with('open_edit_modal', true);
        }

        foreach ($rows as $row) {
            $rowValidator = Validator::make($row, [
                'kegiatan' => ['required', 'string', 'max:1000'],
                'volume' => ['nullable', 'integer', 'min:0'],
                'satuan' => ['required', 'string', 'max:50'],
            ]);

            if ($rowValidator->fails()) {
                return back()
                    ->withErrors($rowValidator)
                    ->withInput()
                    ->with('open_edit_modal', true);
            }
        }

        $tanggal = $data['tanggal'];
        $submittedAt = now();

        // Build JSON data structure
        $jsonItems = [];
        $itemId = 1;
        foreach ($rows as $row) {
            $jsonItems[] = [
                'id' => $itemId++,
                'k' => $row['kegiatan'],
                'v' => (int) ($row['volume'] ?? 0),
                's' => $row['satuan'],
            ];
        }

        $jsonData = json_encode(['items' => $jsonItems], JSON_UNESCAPED_UNICODE);

        // Delete ALL existing records for this date (handles legacy format with multiple rows)
        // Then insert a new single row with JSON format
        DB::table('satker_kegiatan')
            ->where('user_id', $user->id)
            ->whereDate('tanggal', $tanggal)
            ->delete();

        // Insert new record with JSON format
        DB::table('satker_kegiatan')->insert([
            'user_id' => $user->id,
            'tanggal' => $tanggal,
            'kegiatan' => $rows->first()['kegiatan'],
            'volume' => $rows->first()['volume'] ?? 0,
            'satuan' => $rows->first()['satuan'] ?? 'Kegiatan',
            'data_json' => $jsonData,
            'created_at' => $submittedAt,
            'updated_at' => $submittedAt,
        ]);

        return redirect()
            ->route('laporan-kinerja', [
                'tab' => 'harian',
                'month' => Carbon::parse($tanggal)->format('Y-m'),
            ])
            ->with('success', 'Data laporan kinerja berhasil diperbarui.');
    }

    public function updateLaporanKinerja(Request $request, int $activityId)
    {
        $user = $request->user();

        abort_unless($user, 403);

        $activity = DB::table('satker_kegiatan')
            ->where('id', $activityId)
            ->where('user_id', $user->id)
            ->first();

        abort_unless($activity, 404);

        $data = $request->validate([
            'tanggal' => ['required', 'date'],
            'kegiatan' => ['required', 'string', 'max:1000'],
            'volume' => ['nullable', 'integer', 'min:0'],
            'satuan' => ['nullable', 'string', 'max:50'],
            'tab' => ['nullable', 'string'],
            'month' => ['nullable', 'string'],
            'search' => ['nullable', 'string'],
        ]);

        $updatedAt = now();

        DB::table('satker_kegiatan')
            ->where('id', $activityId)
            ->where('user_id', $user->id)
            ->update([
                'tanggal' => $data['tanggal'],
                'kegiatan' => trim($data['kegiatan']),
                'volume' => (int) ($data['volume'] ?? 0),
                'satuan' => trim((string) ($data['satuan'] ?? '')),
                'updated_at' => $updatedAt,
            ]);

        $month = Carbon::parse($data['tanggal'])->format('Y-m');

        return redirect()
            ->route('laporan-kinerja', [
                'tab' => 'harian',
                'month' => $month,
                'search' => trim((string) ($data['search'] ?? '')),
            ])
            ->with('success', 'Data laporan kinerja berhasil diperbarui.');
    }

    public function deleteLaporanKinerja(Request $request, int $activityId)
    {
        $user = $request->user();

        abort_unless($user, 403);

        $activity = DB::table('satker_kegiatan')
            ->where('id', $activityId)
            ->where('user_id', $user->id)
            ->first();

        abort_unless($activity, 404);

        $data = $request->validate([
            'tab' => ['nullable', 'string'],
            'month' => ['nullable', 'string'],
            'search' => ['nullable', 'string'],
        ]);

        DB::table('satker_kegiatan')
            ->where('id', $activityId)
            ->where('user_id', $user->id)
            ->delete();

        $month = $data['month'] ?? Carbon::parse($activity->tanggal)->format('Y-m');

        return redirect()
            ->route('laporan-kinerja', [
                'tab' => 'harian',
                'month' => $month,
                'search' => trim((string) ($data['search'] ?? '')),
            ])
            ->with('success', 'Data laporan kinerja berhasil dihapus.');
    }

    public function deleteLaporanKinerjaByDate(Request $request)
    {
        $user = $request->user();

        abort_unless($user, 403);

        $data = $request->validate([
            'tanggal' => ['required', 'date'],
            'tab' => ['nullable', 'string'],
            'month' => ['nullable', 'string'],
            'search' => ['nullable', 'string'],
        ]);

        DB::table('satker_kegiatan')
            ->where('user_id', $user->id)
            ->whereDate('tanggal', $data['tanggal'])
            ->delete();

        $month = Carbon::parse($data['tanggal'])->format('Y-m');

        return redirect()
            ->route('laporan-kinerja', [
                'tab' => 'harian',
                'month' => $month,
                'search' => trim((string) ($data['search'] ?? '')),
            ])
            ->with('success', 'Semua kegiatan pada tanggal tersebut berhasil dihapus.');
    }

    /**
     * Verify (approve/reject) laporan kinerja from bawahan.
     */
    public function verifyLaporanKinerja(Request $request)
    {
        $user = $request->user();
        abort_unless($user, 403);

        // Custom validation messages
        $messages = [
            'user_id.required' => 'User ID diperlukan.',
            'bulan.required' => 'Bulan diperlukan.',
            'action.required' => 'Action diperlukan.',
            'action.in' => 'Action tidak valid.',
        ];

        $validator = Validator::make($request->all(), [
            'user_id' => ['required', 'integer'],
            'bulan' => ['required', 'string'],
            'action' => ['required', 'in:approve,reject'],
            'alasan' => ['nullable', 'string', 'max:500'],
        ], $messages);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $data = $validator->validated();

        // Verify user is kepala/kasi/kasubbag and has same dept_id
        $validJabatan = ['kepala', 'kasi', 'kasubbag'];
        $atasanRole = in_array(strtolower($user->kat_jabatan ?? ''), $validJabatan);

        // Cek apakah user adalah custom supervisor dari pembuat laporan
        $reportOwner = DB::table('users')
            ->where('id', $data['user_id'])
            ->first();

        $isCustomSupervisorOfUser = false;
        if ($reportOwner && !empty($reportOwner->custom_supervisor_id)) {
            $isCustomSupervisorOfUser = ($reportOwner->custom_supervisor_id == $user->id);
        }

        // Izinkan verifikasi jika:
        // 1. User adalah atasan berdasarkan hierarki (kepala/kasi/kasubbag)
        // 2. ATAU user adalah custom supervisor dari pembuat laporan
        if (!$atasanRole && !$isCustomSupervisorOfUser) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki hak untuk memverifikasi laporan.',
            ], 403);
        }

        // Find the report by user_id and bulan
        // Convert Y-m to Y-m-01 format for database query
        $bulanDate = Carbon::createFromFormat('Y-m', $data['bulan'])->startOfMonth()->toDateString();

        $report = DB::table('satker_ckh')
            ->where('user_id', $data['user_id'])
            ->where('bulan', $bulanDate)
            ->first();

        if (!$report) {
            return response()->json([
                'success' => false,
                'message' => 'Laporan tidak ditemukan.',
            ], 404);
        }

        // Update status
        $newStatus = $data['action'] === 'approve' ? 'DISETUJUI' : 'DITOLAK';

        try {
            // Get report info before update
            $reportInfo = DB::table('satker_ckh')
                ->where('id', $report->id)
                ->first();

            // Get bawahan user info
            $bawahanUser = DB::table('users')
                ->where('id', $data['user_id'])
                ->first();

            // Check if signature is enabled by atasan
            $signature = DB::table('user_signatures')
                ->where('user_id', $user->id)
                ->where('is_active', true)
                ->first();

            // Update status
            DB::table('satker_ckh')
                ->where('id', $report->id)
                ->update([
                    'status' => $newStatus,
                    'alasan' => $data['action'] === 'reject' ? ($data['alasan'] ?? null) : null,
                    'updated_at' => now(),
                ]);

            // Log activity (optional - skip if table doesn't exist)
            try {
                DB::table('activities')->insert([
                    'user_id' => $user->id,
                    'activity_type' => 'verifikasi_laporan',
                    'description' => "Laporan kinerja {$data['bulan']} " . ($data['action'] === 'approve' ? 'disetujui' : 'ditolak') . " oleh atasan",
                    'ref_id' => $report->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } catch (\Exception $e) {
                // Skip logging if table doesn't exist
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal update status: ' . $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => $data['action'] === 'approve'
                ? 'Laporan berhasil disetujui.'
                : 'Laporan berhasil ditolak.',
            'new_status' => $newStatus,
        ]);
    }

    /**
     * Get user signature settings.
     */
    public function getSignature(Request $request)
    {
        $user = $request->user();

        $signature = DB::table('user_signatures')
            ->where('user_id', $user->id)
            ->first();

        // Default values from users table
        $defaultName = $user->name ?? '';
        $defaultNip = $user->nomor_induk ?? '';

        if ($signature) {
            // Use saved values, fallback to user defaults if empty
            $signature->signature_name = $signature->signature_name ?: $defaultName;
            $signature->nip = $signature->nip ?: ($defaultNip ? 'NIP. ' . $defaultNip : '');
        } else {
            // No signature exists, use user defaults
            $signature = (object) [
                'signature_name' => $defaultName,
                'signature_image' => '',
                'nip' => $defaultNip ? 'NIP. ' . $defaultNip : '',
                'is_active' => false,
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $signature,
        ]);
    }

    /**
     * Save user signature settings.
     */
    public function saveSignature(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'signature_name' => ['nullable', 'string', 'max:255'],
            'signature_image' => ['nullable', 'string'],
            'nip' => ['nullable', 'string', 'max:50'],
            'is_active' => ['nullable', 'string'],
        ]);

        $data = $request->only(['signature_name', 'signature_image', 'nip']);
        $data['user_id'] = $user->id;

        // Convert is_active: '1' = true, anything else = false
        $isActiveRaw = $request->input('is_active', '0');
        $data['is_active'] = ($isActiveRaw === '1') ? true : false;

        // Format NIP with "NIP. " prefix if not empty and doesn't have it
        if (!empty($data['nip']) && !str_starts_with($data['nip'], 'NIP. ')) {
            $data['nip'] = 'NIP. ' . $data['nip'];
        }

        // If signature_image is base64 data, save to file
        if (!empty($request->input('signature_image')) && str_starts_with($request->input('signature_image'), 'data:image')) {
            $imageData = $request->input('signature_image');
            $image = str_replace('data:image/png;base64,', '', $imageData);
            $image = str_replace(' ', '+', $image);
            $decoded = base64_decode($image);

            $filename = 'signatures/' . $user->id . '_' . time() . '.png';
            Storage::disk('public')->put($filename, $decoded);
            $data['signature_image'] = '/storage/' . $filename;
        }

        // Update or insert signature
        $existing = DB::table('user_signatures')
            ->where('user_id', $user->id)
            ->first();

        if ($existing) {
            DB::table('user_signatures')
                ->where('user_id', $user->id)
                ->update($data);
        } else {
            DB::table('user_signatures')->insert($data);
        }

        return response()->json([
            'success' => true,
            'message' => 'Tanda tangan berhasil disimpan.',
            'is_active' => $data['is_active'],
        ]);
    }

    /**
     * Upload signature image.
     */
    public function uploadSignatureImage(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'signature_image' => ['required', 'image', 'max:2048'],
        ]);

        $file = $request->file('signature_image');
        $path = $file->storeAs(
            'signatures',
            $user->id . '_' . time() . '.' . $file->getClientOriginalExtension(),
            'public'
        );

        return response()->json([
            'success' => true,
            'path' => '/storage/' . $path,
        ]);
    }

    /**
     * Generate PDF with signature for approval.
     */
    private function generateApprovedPdf($reportInfo, $atasan, $periodLabel)
    {
        // Get signature if active
        $signature = DB::table('user_signatures')
            ->where('user_id', $atasan->id)
            ->where('is_active', true)
            ->first();

        // Get bawahan user data
        $bawahanUser = DB::table('users')
            ->where('id', $reportInfo->user_id)
            ->first();

        // Get unit name
        $unitName = DB::table('ktd_department')
            ->where('id', $bawahanUser->dept_id ?? $reportInfo->dept_id)
            ->value('nama') ?: '-';

        // Get activities
        $monthStart = Carbon::parse($reportInfo->bulan)->startOfMonth();
        $monthEnd = $monthStart->copy()->endOfMonth();

        $dailyEntries = DB::table('satker_kegiatan')
            ->where('user_id', $reportInfo->user_id)
            ->whereBetween('tanggal', [$monthStart->toDateString(), $monthEnd->toDateString()])
            ->orderBy('tanggal')
            ->orderBy('created_at')
            ->get();

        $dailyGroups = $dailyEntries
            ->groupBy(fn ($row) => Carbon::parse($row->tanggal)->toDateString())
            ->map(function ($items, $date) {
                $dateCarbon = Carbon::parse($date);
                $allItems = [];

                foreach ($items as $item) {
                    $jsonData = json_decode((string) ($item->data_json ?? '{"items":[]}'), true) ?: ['items' => []];
                    $itemsArr = $jsonData['items'] ?? [];

                    if (empty($itemsArr) && ! empty($item->kegiatan)) {
                        $itemsArr = [[
                            'k' => $item->kegiatan,
                            'v' => $item->volume ?? 0,
                            's' => $item->satuan ?? 'Kegiatan',
                        ]];
                    }

                    foreach ($itemsArr as $it) {
                        $volume = (int) ($it['v'] ?? ($it['volume'] ?? 0));
                        $unit = trim((string) ($it['s'] ?? ($it['satuan'] ?? 'Kegiatan')));

                        $allItems[] = [
                            'kegiatan' => trim((string) ($it['k'] ?? ($it['kegiatan'] ?? ''))),
                            'volume' => $volume,
                            'satuan' => $unit,
                            'meta' => $volume > 0 ? trim($volume . ' ' . $unit) : $unit,
                        ];
                    }
                }

                return [
                    'date' => $dateCarbon->toDateString(),
                    'label' => $this->indonesianDateLabel($dateCarbon),
                    'items' => $allItems,
                ];
            })
            ->values()
            ->all();

        // Determine signature data - use signature data if exists, otherwise use atasan data
        $signatureName = $signature->signature_name ?? $atasan->name;
        $signatureNip = $signature->nip ?? ($atasan->nomor_induk ? 'NIP. ' . $atasan->nomor_induk : '');

        // Process signature image - convert storage path to proper URL/path for DomPDF
        $signatureImage = null;
        if (!empty($signature->signature_image)) {
            $imagePath = $signature->signature_image;
            // If it's a storage path, convert to absolute path
            if (str_starts_with($imagePath, '/storage/')) {
                $fullPath = public_path(ltrim($imagePath, '/'));
                if (file_exists($fullPath)) {
                    // Use absolute file path for DomPDF
                    $signatureImage = $fullPath;
                }
            } elseif (file_exists($imagePath)) {
                $signatureImage = $imagePath;
            }
        }

        // Check PLT
        $deptId = $bawahanUser->dept_id ?? $reportInfo->dept_id;
        $pltPlh = DB::table('plt_plh')
            ->where('dept_id_plh', $deptId)
            ->first();

        if ($pltPlh) {
            $pltUser = DB::table('users')->where('id', $pltPlh->user_id)->first();
            if ($pltUser) {
                $signatureName = $signature->signature_name ?? $pltUser->name;
                $signatureNip = $signature->nip ?? ($pltUser->nomor_induk ? 'NIP. ' . $pltUser->nomor_induk : '');
            }
        }

        // Determine signature label based on dept_id
        $specialDeptIds = [998, 999];
        $kepalaLabel = in_array((int) ($bawahanUser->dept_id ?? $reportInfo->dept_id), $specialDeptIds)
            ? ($bawahanUser->satker ?? $unitName)
            : ($unitName ?: '-');

        $pdfData = [
            'userName' => $bawahanUser->name ?? '-',
            'userNip' => $bawahanUser->nomor_induk ?? '-',
            'unitName' => $unitName,
            'positionName' => $bawahanUser->pekerjaan ?? '-',
            'periodLabel' => $periodLabel,
            'dailyGroups' => $dailyGroups,
            'headerImage' => $this->assetToDataUri(public_path('assets/img/template/header.png')),
            'generatedAt' => now()->translatedFormat('d F Y H:i'),
            'signatureName' => $signatureName,
            'signatureNip' => $signatureNip,
            'signatureImage' => $signatureImage,
            'signatureLabel' => $pltPlh ? 'Mengetahui<br>PLT Kepala,' : "Mengetahui<br>Kepala {$kepalaLabel},",
        ];

        $pdf = Pdf::loadView('pdf.laporan-kinerja-harian', $pdfData)
            ->setPaper('a4', 'portrait')
            ->setOption('isRemoteEnabled', true)
            ->setOption('isHtml5ParserEnabled', true);

        return $pdf->output();
    }

    private function sanitizeActivityRows(array $items, string $defaultUnit = 'Kegiatan')
    {
        return collect($items)
            ->map(function ($item) use ($defaultUnit) {
                return [
                    'kegiatan' => trim((string) ($item['kegiatan'] ?? '')),
                    'volume' => (int) ($item['volume'] ?? 0),
                    'satuan' => trim((string) ($item['satuan'] ?? $defaultUnit)),
                ];
            });
    }

    /**
     * Handle TPG/Pemberkasan specific submission (Service 1037)
     * Uses JSON snapshot in satker_pemberkasan
     */
    public function submitTpgRequest(Request $request, int $serviceId): \Illuminate\Http\RedirectResponse
    {
        $service = $this->serviceDetail($serviceId);
        $requirements = $service['requirements'];
        $requester = $request->user();
        $isDraft = $request->input('submit_action') === 'draft';

        abort_unless($requester, 403);

        // Validate required fields
        $validated = $request->validate([
            'tahun_pelajaran' => ['required', 'string'],
            'semester' => ['required', 'string'],
        ]);

        // Check for missing required files (will show as warning)
        $missingFiles = $this->validateRequiredFiles($requirements, $request);

        // TPG specific params
        $kategori = 'PAIS-TPG-SEMESTER'; // Fixed for service 1037
        $tahunPelajaran = $validated['tahun_pelajaran'];
        $semester = $validated['semester'];

        // Determine item_id based on semester (1=Ganjil, 2=Genap)
        $itemId = strtoupper($semester) === 'GENAP' ? 2 : 1;

        // Extract tahun from tahun_pelajaran (supports both "2026/2027" and "2026-2027")
        $tahunParts = preg_split('[/-]', $tahunPelajaran);
        $tahun = (int) ($tahunParts[0] ?? date('Y'));

        // Determine waktu (start date of semester)
        // Ganjil: July (bulan 7), Genap: January (bulan 1) of the following year
        $waktuBulan = $itemId === 1 ? 7 : 1;
        $waktuTahun = $itemId === 1 ? $tahun : $tahun + 1;
        $waktuDate = Carbon::createFromDate($waktuTahun, $waktuBulan, 1)->startOfMonth();

        // Generate noreq (unique request number)
        // Format: PAIS-TPG-SEMESTER-{USERID}-{TAHUNPELAJARAN}-{SEMESTER}
        // Example: PAIS-TPG-SEMESTER-45-2026-2027-GANJIL
        // Note: Replace "/" with "-" for file-safe naming
        $tpSafe = str_replace('/', '-', $tahunPelajaran);
        $noreq = strtoupper("{$kategori}-{$requester->id}-{$tpSafe}-{$semester}");

        // Generate deskripsi
        $deskripsi = "[{$kategori}] Semester {$semester} TP. {$tahunPelajaran}";

        // Build files snapshot from uploaded files
        $filesSnapshot = $this->buildFilesSnapshot($requester, $serviceId, $noreq, $requirements, $request);

        // Build metadata JSON
        $metadata = [
            'tahun_pelajaran' => $tahunPelajaran,
            'semester' => $semester,
            'kategori' => $kategori,
            'tahun_ajaran' => $tahun,
            'submitted_at' => now()->toIso8601String(),
            'is_draft' => $isDraft,
        ];

        // Build requirements snapshot (preserve state at submission time)
        $requirementsSnapshot = collect($requirements)->map(function ($req) {
            return [
                'id' => $req['id'],
                'title' => $req['title'],
                'note' => $req['note'],
                'is_required' => $req['is_required'],
                'type' => $req['type_normalized'],
            ];
        })->toArray();

        // Save to satker_pemberkasan
        SatkerPemberkasan::updateOrCreate(
            ['noreq' => $noreq],
            [
                'user_id' => $requester->id,
                'tipe' => $kategori,
                'layanan_id' => $serviceId,
                'dept_id' => (string) $requester->dept_id,
                'waktu' => $waktuDate,
                'item_id' => $itemId,
                'deskripsi' => $deskripsi,
                'keterangan' => $request->input('deskripsi') ?? '<NoKomen>',
                'status' => $isDraft ? 'DRAFT' : 'SUBMITTED',
                'files' => json_encode($filesSnapshot),
                'metadata' => json_encode($metadata),
                'requirements_snapshot' => json_encode($requirementsSnapshot),
                'is_migrated' => true,
                'migrated_at' => now(),
            ]
        );

        $message = $isDraft
            ? "Draft {$service['title']} sudah disimpan."
            : "Pengajuan {$service['title']} sudah diterima.";

        // Add warning if there are missing required files
        if (!empty($missingFiles)) {
            $message .= " Perhatian: Bahan wajib belum lengkap - " . implode(', ', $missingFiles) . ". Silakan lengkapi sebelum mengirim final.";
        }

        if (! $isDraft) {
            $serviceData = DB::table('ktd_layanan')->find($serviceId);
            if ($serviceData && ! empty($serviceData->petugas)) {
                $this->notifyPetugasViaWhatsApp(
                    (int) $serviceData->petugas,
                    $service['title'],
                    $noreq,
                    $requester->name,
                    null,
                    "Semester {$semester} TP. {$tahunPelajaran}",
                    $request->input('deskripsi') ?? null
                );
            }
        }

        return redirect()
            ->route('pengajuan-saya')
            ->with('success', $message);
    }

    /**
     * Build files snapshot JSON for storage
     * @param array $existingFiles Optional existing files from database to preserve
     */
    private function buildFilesSnapshot($requester, int $serviceId, string $noreq, array $requirements, Request $request, array $existingFiles = [], array $deletedFileIds = []): array
    {
        $files = [];

        foreach ($requirements as $requirement) {
            $syaratId = (int) $requirement['id'];
            $type = $requirement['type_normalized'];
            $fieldKey = $this->requirementFieldKey($type, $syaratId);
            $uploadedFile = $request->file($fieldKey);

            // Check if file was deleted by user
            $isDeleted = in_array($syaratId, $deletedFileIds);

            // Check for existing file (only if not deleted)
            $existingEntry = null;
            if (!$isDeleted) {
                $existingEntry = collect($existingFiles)->firstWhere('syarat_id', $syaratId);
            }

            $fileEntry = [
                'syarat_id' => $syaratId,
                'title' => $requirement['title'],
                'type' => $type,
                'is_required' => $requirement['is_required'],
                'filename' => $existingEntry['filename'] ?? 'NONE',
                'filetype' => $existingEntry['filetype'] ?? null,
                'size' => $existingEntry['size'] ?? null,
                'status' => $existingEntry['status'] ?? 0,
                'uploaded_at' => $existingEntry['uploaded_at'] ?? null,
            ];

            // Handle file upload (only if new file is uploaded)
            if ($uploadedFile) {
                $extension = strtolower($uploadedFile->getClientOriginalExtension() ?: $uploadedFile->extension());
                $safeName = Str::slug($requirement['title'] ?? "syarat_{$syaratId}", '');
                $filename = "{$noreq}.{$requester->id}.{$safeName}.{$extension}";
                $path = "{$requester->nomor_induk}/{$filename}";

                // Save file to storage/app/users_berkas/{nomor_induk}/{filename}
                Storage::disk('users_berkas')->put($path, file_get_contents($uploadedFile->getRealPath()));

                $fileEntry['filename'] = $filename;
                $fileEntry['filetype'] = $extension;
                $fileEntry['size'] = (string) $uploadedFile->getSize();
                $fileEntry['status'] = 1;
                $fileEntry['uploaded_at'] = now()->toIso8601String();
            }

            $files[] = $fileEntry;
        }

        return $files;
    }

    /**
     * Determine layanan_id based on kategori
     */
    private function determineTpgLayananId(string $kategori, int $defaultServiceId): int
    {
        return match (strtoupper($kategori)) {
            'PAIS-TPG-BULANAN' => 1038,
            'PENMAD-TPG-BULANAN' => 1081,
            'PENMAD-TPG-PENGAWAS-BULANAN' => 1082,
            default => $defaultServiceId,
        };
    }

    /**
     * Generate description for TPG submission
     */
    private function generateTpgDescription(string $kategori, int $layananId, Carbon $bulanDate, ?string $semester): string
    {
        $kategoriUpper = strtoupper($kategori);
        $formattedMonth = $bulanDate->translatedFormat('F Y');
        $formattedYear = $bulanDate->translatedFormat('Y');

        return match ($layananId) {
            1037 => "[{$kategoriUpper}] Semester {$semester} {$formattedYear}/" . $bulanDate->copy()->addYear()->translatedFormat('Y'),
            default => "[{$kategoriUpper}] {$formattedMonth}",
        };
    }

    /**
     * Process individual TPG requirement
     */
    private function processTpgRequirement($requester, int $layananId, string $noreq, array $requirement, Request $request, Carbon $bulanDate): void
    {
        $syaratId = (int) $requirement['id'];
        $type = $requirement['type_normalized'];

        // Get field key for the requirement
        $fieldKey = $this->requirementFieldKey($type, $syaratId);
        $uploadedFile = $request->file($fieldKey);

        // Determine filename (existing or placeholder)
        $filename = $this->getTpgFilename($requester, $layananId, $noreq, $syaratId, $requirement, $bulanDate);

        // Handle file upload if present
        if ($uploadedFile) {
            $filename = $this->uploadTpgFile($uploadedFile, $requester, $noreq, $requirement, $syaratId);
        }

        // File info is saved in satker_pemberkasan.files JSON snapshot
    }

    /**
     * Get existing filename or default placeholder
     */
    private function getTpgFilename($requester, int $layananId, string $noreq, int $syaratId, array $requirement, Carbon $bulanDate): string
    {
        // Special handling for PAIS-TPG-BULANAN with specific syarat
        if ($layananId === 1038 && $syaratId === 419) {
            $filename = $this->getPaisTpgSpecialFilename($requester, $bulanDate);
            if ($filename !== 'NONE') {
                return $filename;
            }
        }

        // Check existing file
        $existing = DB::table('satker_filepemberkasan')
            ->where([
                'user_id' => $requester->id,
                'noreq' => $noreq,
                'layanan_id' => $layananId,
                'syarat_id' => $syaratId,
            ])
            ->value('filename');

        return $existing ?: 'NONE';
    }

    /**
     * Get special filename for PAIS-TPG-BULANAN service
     */
    private function getPaisTpgSpecialFilename($requester, Carbon $bulanDate): string
    {
        $start = $bulanDate->copy()->startOfMonth()->subMonth();

        $kinerjaFile = DB::table('satker_ckh')
            ->where([
                'user_id' => $requester->id,
                'bulan' => $start->format('Y-m-d'),
            ])
            ->first();

        if (!$kinerjaFile || $kinerjaFile->filename === 'NONE' || $kinerjaFile->status !== 'DISETUJUI') {
            return 'NONE';
        }

        $path1 = "{$requester->nomor_induk}/Kinerja/{$kinerjaFile->filename}";
        $path2 = "{$requester->nomor_induk}/Request/{$kinerjaFile->filename}";

        if (Storage::disk('users_berkas')->exists($path1)) {
            Storage::disk('users_berkas')->delete($path2);
            Storage::disk('users_berkas')->copy($path1, $path2);
            return $kinerjaFile->filename;
        }

        return 'NONE';
    }

    /**
     * Upload TPG file
     */
    private function uploadTpgFile($file, $requester, string $noreq, array $requirement, int $syaratId): string
    {
        $extension = strtolower($file->getClientOriginalExtension() ?: $file->extension());
        $safeName = Str::slug($requirement['title'] ?? "syarat_{$syaratId}", '');
        $filename = "{$noreq}.{$requester->id}.{$safeName}.{$extension}";
        $path = "{$requester->nomor_induk}/Request/{$filename}";

        Storage::disk('users_berkas')->put($path, file_get_contents($file->getRealPath()));

        return $filename;
    }

    /**
     * Determine TPG file status
     */
    private function getTpgFileStatus(int $layananId, int $syaratId, array $requirement, $requester, string $filename): int
    {
        // Check if this requirement has a linked file in users_files
        $linkedFile = DB::table('users_files')
            ->where([
                'user_id' => (string) $requester->nomor_induk,
                'files_id' => $requirement['ktdfiles_id'] ?? 0,
            ])
            ->first();

        if ($linkedFile) {
            return 1; // Has linked file
        }

        // Check if there's an uploaded file
        if ($filename && $filename !== 'NONE') {
            return 1;
        }

        return 0;
    }

    public function submitServiceRequest(Request $request, int $serviceId)
    {
        $service = $this->serviceDetail($serviceId);
        $requirements = $service['requirements'];
        $requester = $request->user();
        $isDraft = $request->input('submit_action') === 'draft';
        $editingRequestId = (int) $request->input('request_id', 0);
        $existingRequest = null;
        $existingFiles = [];

        abort_unless($requester, 403);

        if ($editingRequestId > 0) {
            $existingRequest = DB::table('users_request')
                ->where('id', $editingRequestId)
                ->where('user_id', $requester->id)
                ->first();

            abort_unless($existingRequest, 404);
            abort_unless((int) $existingRequest->layanan_id === $service['id'], 403);

            $existingFiles = DB::table('users_berkas')
                ->where('no_req', $existingRequest->no_req)
                ->get()
                ->keyBy('syarat_id');
        }

        $rules = [
            'deskripsi' => ['required', 'string', 'max:2000'],
        ];

        foreach ($requirements as $requirement) {
            $type = $requirement['type_normalized'];
            $fieldKey = $this->requirementFieldKey($type, (int) $requirement['id']);
            $hasExistingUpload = $existingRequest && $this->isUploadRequirement($type) && isset($existingFiles[$requirement['id']]);

            $rules[$fieldKey] = $this->requirementValidationRules($requirement, $isDraft, $hasExistingUpload);
        }

        $data = $request->validate($rules);

        $userId = $requester->id;
        $requestNumber = $existingRequest?->no_req ?? $this->generateRequestNumber($service['dept_id'], $service['id']);
        $submittedAt = now();
        $uploadRoot = "service-requests/{$requestNumber}";
        $deskripsi = "Nomor Identitas: {$requester->nomor_induk}\n\n{$data['deskripsi']}";
        DB::transaction(function () use ($service, $data, $requirements, $requestNumber, $userId, $submittedAt, $uploadRoot, $deskripsi, $request, $isDraft, $requester, $existingRequest, $existingFiles) {
            $requestRowId = $existingRequest?->id;

            if ($existingRequest) {
                DB::table('users_request')
                    ->where('id', $existingRequest->id)
                    ->update([
                        'pemohon' => $requester->name,
                        'no_surat' => null,
                        'tgl_surat' => null,
                        'dept_id' => $service['dept_id'],
                        'layanan_id' => $service['id'],
                        'judul' => $service['title'],
                        'deskripsi' => $deskripsi,
                        'file_offline' => 'NONE',
                        'status' => $isDraft ? 'DRAFT' : 'UNCHECK',
                        'staff_id' => 999,
                        'step' => null,
                        'petugas' => null,
                        'kategori' => 'Personal',
                        'komentar' => null,
                        'updated_at' => $submittedAt,
                    ]);
            } else {
                $requestRowId = DB::table('users_request')->insertGetId([
                    'no_req' => $requestNumber,
                    'pemohon' => $requester->name,
                    'no_surat' => null,
                    'tgl_surat' => null,
                    'user_id' => $userId,
                    'dept_id' => $service['dept_id'],
                    'layanan_id' => $service['id'],
                    'judul' => $service['title'],
                    'deskripsi' => $deskripsi,
                    'file_offline' => 'NONE',
                    'status' => $isDraft ? 'DRAFT' : 'UNCHECK',
                    'staff_id' => 999,
                    'step' => null,
                    'petugas' => null,
                    'kategori' => 'Personal',
                    'komentar' => null,
                    'created_at' => $submittedAt,
                    'updated_at' => $submittedAt,
                ]);
            }

            foreach ($requirements as $requirement) {
                $requirementId = (int) $requirement['id'];
                $type = $requirement['type_normalized'];
                $fieldKey = $this->requirementFieldKey($type, $requirementId);

                if ($this->isUploadRequirement($type)) {
                    $uploadedFile = $request->file($fieldKey);
                    $existingFile = $existingFiles[$requirementId] ?? null;
                    $filename = $existingFile->filename ?? 'NONE';
                    $filetype = $existingFile->filetype ?? null;
                    $size = $existingFile->size ?? null;

                    if ($uploadedFile) {
                        if ($existingFile) {
                            Storage::disk('public')->delete("service-requests/{$requestNumber}/{$existingFile->filename}");
                        }

                        $extension = $uploadedFile->getClientOriginalExtension() ?: $uploadedFile->extension();
                        $safeName = Str::slug($requirement['title'], '');
                        $filename = "{$requestNumber}.{$userId}.{$safeName}." . strtolower($extension);
                        $path = $uploadedFile->storeAs($uploadRoot, $filename, 'public');
                        $filetype = strtolower($extension);
                        $size = (string) $uploadedFile->getSize();
                    }

                    DB::table('users_berkas')->updateOrInsert([
                        'no_req' => $requestNumber,
                        'syarat_id' => $requirementId,
                    ], [
                        'kategori' => 'Personal',
                        'user_id' => $userId,
                        'no_req' => $requestNumber,
                        'layanan_id' => $service['id'],
                        'syarat_id' => $requirementId,
                        'filename' => $filename,
                        'filetype' => $filetype,
                        'size' => $size,
                        'status' => 0,
                        'created_at' => $submittedAt,
                        'updated_at' => $submittedAt,
                    ]);
                } else {
                    $rawValue = $data['values'][$requirementId] ?? null;
                    $value = is_string($rawValue) ? trim($rawValue) : $rawValue;
                    DB::table('users_request_answers')->updateOrInsert([
                        'request_id' => $requestRowId,
                        'syarat_id' => $requirementId,
                    ], [
                        'no_req' => $requestNumber,
                        'value' => $value === '' ? null : $value,
                        'created_at' => $submittedAt,
                        'updated_at' => $submittedAt,
                    ]);
                }
            }
        });

        $message = $isDraft
            ? "Draft {$service['title']} sudah disimpan."
            : "Pengajuan {$service['title']} sudah diterima.";

        if (! $isDraft) {
            $serviceData = DB::table('ktd_layanan')->find($service['id']);
            if ($serviceData && ! empty($serviceData->petugas)) {
                $this->notifyPetugasViaWhatsApp(
                    (int) $serviceData->petugas,
                    $service['title'],
                    $requestNumber,
                    $requester->name,
                    $data['deskripsi'] ?? null
                );
            }
        }

        return redirect()
            ->route('pengajuan-saya')
            ->with('success', $message);
    }

    public function satuanKerja()
    {
        $departmentGroups = $this->departmentGroups();
        $externalGroups = $this->externalGroups();

        return view('satuan-kerja', [
            'sections' => [
                [
                    'key' => 'kantor',
                    'label' => 'Kantor',
                    'description' => 'Kategori kantor dengan status aktif 1.',
                    'count' => $departmentGroups['kantor']->total(),
                    'cards' => $departmentGroups['kantor'],
                ],
                [
                    'key' => 'kua',
                    'label' => 'KUA',
                    'description' => 'Kategori KUA dengan status 2.',
                    'count' => $departmentGroups['kua']->total(),
                    'cards' => $departmentGroups['kua'],
                ],
                [
                    'key' => 'min',
                    'label' => 'MIN',
                    'description' => 'Kategori MIN dengan status 2.',
                    'count' => $departmentGroups['min']->total(),
                    'cards' => $departmentGroups['min'],
                ],
                [
                    'key' => 'mtsn',
                    'label' => 'MTsN',
                    'description' => 'Kategori MTsN dengan status 2.',
                    'count' => $departmentGroups['mtsn']->total(),
                    'cards' => $departmentGroups['mtsn'],
                ],
                [
                    'key' => 'man',
                    'label' => 'MAN',
                    'description' => 'Kategori MAN dengan status 2.',
                    'count' => $departmentGroups['man']->total(),
                    'cards' => $departmentGroups['man'],
                ],
                [
                    'key' => 'swasta-lainnya',
                    'label' => 'Swasta/Lainnya',
                    'description' => 'Data PP dari users dengan dept_id 999.',
                    'count' => $externalGroups['swasta-lainnya']->total(),
                    'cards' => $externalGroups['swasta-lainnya'],
                ],
                [
                    'key' => 'pemerintah-daerah',
                    'label' => 'Pemerintah Daerah',
                    'description' => 'Data PP dari users dengan dept_id 998.',
                    'count' => $externalGroups['pemerintah-daerah']->total(),
                    'cards' => $externalGroups['pemerintah-daerah'],
                ],
            ],
        ]);
    }

    public function satuanKerjaDetail(int $department)
    {
        $departmentRow = DB::table('ktd_department')->where('id', $department)->first();

        abort_unless($departmentRow, 404);

        $head = $this->departmentHead($departmentRow->id, $departmentRow->kategori, $departmentRow->nama);
        $pltHead = $head ? null : $this->departmentPltHead($departmentRow->id);
        $leader = $head ?? $pltHead;
        $leaderLabel = $this->departmentHeadLabel($departmentRow->kategori, $departmentRow->nama, (bool) $pltHead);

        $peoplePaginator = $this->departmentPeople($departmentRow->id, $leader?->id);
        $peopleData = $peoplePaginator->items();
        $kaurs = [];
        $others = [];

        foreach ($peopleData as $person) {
            if ($person['is_kaur'] ?? false) {
                $kaurs[] = $person;
            } else {
                $others[] = $person;
            }
        }

        return view('unit-kerja-detail', [
            'department' => [
                'id' => $departmentRow->id,
                'name' => $departmentRow->nama,
                'description' => $departmentRow->deskripsi,
                'code' => $departmentRow->kode,
                'category' => $departmentRow->kategori,
                'cover' => asset("assets/img/seksi/{$departmentRow->id}.webp"),
            ],
            'leader' => $leader ? $this->personCard($leader, $leaderLabel, true) : null,
            'leaderLabel' => $leaderLabel,
            'kaurs' => $kaurs,
            'people' => $peoplePaginator,
        ]);
    }

    private function departmentGroups(): array
    {
        return [
            'kantor' => $this->departmentCards('kantor', 1),
            'kua' => $this->departmentCards('kua', 2),
            'min' => $this->departmentCards('min', 2),
            'mtsn' => $this->departmentCards('mtsn', 2),
            'man' => $this->departmentCards('man', 2),
        ];
    }

    private function departmentSelection(string $kategori, int $status)
    {
        return DB::table('ktd_department')
            ->whereRaw('LOWER(kategori) = ?', [strtolower($kategori)])
            ->where('status', $status)
            ->orderBy('id')
            ->get()
            ->map(function ($item) use ($kategori) {
                $head = $this->departmentHead($item->id, $kategori, $item->nama);
                $pltHead = $head ? null : $this->departmentPltHead($item->id);
                $activeHead = $head ?? $pltHead;

                $employeeCount = DB::table('users')
                    ->where('dept_id', $item->id)
                    ->count();

                // Build head photo path
                $headPhotoPath = null;
                if ($activeHead && ! empty($activeHead->pp) && ! empty($activeHead->nomor_induk)) {
                    $headPhotoPath = asset("storage/users_berkas/{$activeHead->nomor_induk}/{$activeHead->pp}");
                }

                return [
                    'id' => $item->id,
                    'title' => $item->nama,
                    'subtitle' => $item->deskripsi ?: $item->kode,
                    'meta_label' => 'Nama Unit',
                    'meta_value' => $item->nama,
                    'extra_label' => 'Pegawai',
                    'extra_value' => $employeeCount,
                    'head_label' => $this->departmentHeadLabel($kategori, $item->nama, (bool) $pltHead),
                    'head_value' => $activeHead?->name,
                    'head_photo' => $headPhotoPath,
                    'head_initials' => $activeHead ? Str::upper(Str::substr($activeHead->name, 0, 2)) : Str::upper(Str::substr($item->nama, 0, 2)),
                    'cover' => Str::upper(Str::substr($item->nama, 0, 2)),
                    'cover_path' => asset("assets/img/seksi/{$item->id}.webp"),
                    'href' => route('unit-kerja.detail', $item->id),
                    'type' => $kategori,
                ];
            });
    }

    private function generalServices(): array
    {
        return [
            [
                'key' => 'konsultasi',
                'title' => 'Konsultasi',
                'description' => 'Panduan awal untuk menjawab kebutuhan dan pertanyaan layanan.',
                'tag' => 'Layanan umum',
                'cover_path' => asset('assets/img/ikon/777.webp'),
            ],
            [
                'key' => 'janji-temu',
                'title' => 'Janji Temu',
                'description' => 'Atur appointment dengan pegawai/unit kerja.',
                'tag' => 'Layanan umum',
                'cover_path' => asset('assets/img/ikon/508.webp'),
            ],
            [
                'key' => 'pengaduan',
                'title' => 'Pengaduan',
                'description' => 'Sampaikan keluhan atau masukan untuk ditindaklanjuti.',
                'tag' => 'Layanan umum',
                'cover_path' => asset('assets/img/ikon/888.webp'),
            ],
            [
                'key' => 'satu-data',
                'title' => 'Satu Data',
                'description' => 'Akses data ringkas yang dipakai sebagai referensi layanan.',
                'tag' => 'Layanan umum',
                'cover_path' => asset('assets/img/ikon/507.webp'),
            ],
        ];
    }

    private function specialServicesByUnit(array $departmentIds): array
    {
        if ($departmentIds === []) {
            return [];
        }

        return DB::table('ktd_layanan')
            ->whereIn('dept_id', $departmentIds)
            ->orderBy('dept_id')
            ->orderByDesc('spesial')
            ->orderBy('id')
            ->get()
            ->groupBy('dept_id')
            ->map(function ($items) {
                return $items->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'dept_id' => $item->dept_id,
                        'title' => $item->nama,
                        'description' => $item->deskripsi ?: 'Layanan khusus pada unit kerja ini.',
                        'waktu' => $item->waktu ?: '-',
                        'biaya' => $this->serviceCostLabel($item->biaya),
                        'output' => $item->output ?: '-',
                        'status_label' => $this->serviceStatusLabel($item->status),
                        'is_spesial' => (int) $item->spesial === 1,
                        'requirements' => $this->serviceRequirements((int) $item->id),
                        'cover_path' => $this->serviceIconPath($item->id),
                    ];
                })->values();
            })
            ->all();
    }

    private function serviceRequirements(int $serviceId): array
    {
        return DB::table('ktd_syarat')
            ->where('layanan_id', $serviceId)
            ->orderByRaw("CASE LOWER(COALESCE(type, '')) 
                WHEN 'file' THEN 0
                WHEN 'image' THEN 1
                WHEN 'date' THEN 2
                WHEN 'datetime' THEN 3
                WHEN 'input' THEN 4
                WHEN 'textarea' THEN 5
                WHEN 'option' THEN 6
                ELSE 99
            END")
            ->orderByDesc('utama')
            ->orderByDesc('wajib')
            ->orderBy('id')
            ->get()
            ->map(function ($item) {
                $type = $this->normalizedRequirementType($item->type);

                return [
                    'id' => $item->id,
                    'title' => $item->syarat,
                    'note' => $item->keterangan ?: null,
                    'is_required' => (int) ($item->wajib ?? 0) === 1,
                    'is_primary' => (int) ($item->utama ?? 0) === 1,
                    'type' => $item->type,
                    'type_normalized' => $type,
                    'type_label' => $this->requirementTypeLabel($type),
                    'input_type' => $this->requirementInputType($type),
                    'options' => $this->requirementOptions($item->value),
                    'value' => $item->value,
                ];
            })
            ->values()
            ->all();
    }

    private function serviceDetail(int $serviceId): array
    {
        $service = DB::table('ktd_layanan')->where('id', $serviceId)->first();

        abort_unless($service, 404);

        $unitName = DB::table('ktd_department')
            ->where('id', $service->dept_id)
            ->value('nama');

        return [
            'id' => (int) $service->id,
            'dept_id' => (int) $service->dept_id,
            'unit_name' => $unitName ?? '-',
            'title' => $service->nama,
            'description' => $service->deskripsi ?: 'Layanan khusus pada unit kerja ini.',
            'waktu' => $service->waktu ?: '-',
            'biaya' => $this->serviceCostLabel($service->biaya),
            'output' => $service->output ?: '-',
            'status_label' => $this->serviceStatusLabel($service->status),
            'is_spesial' => (int) $service->spesial === 1,
            'requirements' => $this->serviceRequirements((int) $service->id),
            'cover_path' => $this->serviceIconPath((int) $service->id),
        ];
    }

    private function generateRequestNumber(int $departmentId, int $serviceId): string
    {
        $departmentCode = DB::table('ktd_department')
            ->where('id', $departmentId)
            ->value('kode');

        $departmentCode = strtoupper(preg_replace('/[^A-Z0-9]/i', '', (string) $departmentCode));

        if ($departmentCode === '') {
            $departmentCode = 'DEP';
        }

        return sprintf('%s-%s-%s-%03d', $departmentCode, $serviceId, now()->format('ymdHis'), random_int(0, 999));
    }

    private function notifyPetugasViaWhatsApp(int $petugasUserId, string $namaLayanan, string $noReq, string $pemohon, ?string $deskripsi = null, ?string $periode = null, ?string $keterangan = null): void
    {
        try {
            $petugas = DB::table('users')->find($petugasUserId);
            if (! $petugas || empty($petugas->telp)) {
                return;
            }

            $phone = '62' . WhatsAppService::normalizePhoneNumber($petugas->telp);
            $waService = new WhatsAppService();

            $lines = [
                "📣 *Notifikasi Pengajuan Layanan*",
                "━━━━━━━━━━━━━━━━━━━━━━",
                "",
                "👤 *Pemohon:* {$pemohon}",
                "🏢 *Layanan:* {$namaLayanan}",
                "🔖 *No. Request:* {$noReq}",
            ];

            if ($deskripsi) {
                $lines[] = "📝 *Deskripsi:* " . Str::limit(strip_tags($deskripsi), 100);
            }

            if ($keterangan) {
                $lines[] = "💬 *Keterangan:* " . Str::limit(strip_tags($keterangan), 100);
            }

            if ($periode) {
                $lines[] = "📅 *Periode:* {$periode}";
            }

            $lines[] = "";
            $lines[] = "🕐 *Waktu:* " . now()->format('d M Y, H:i');
            $lines[] = "";
            $lines[] = "━━━━━━━━━━━━━━━━━━━━━━";
            $lines[] = "Yth. *Bpk/Ibu Staff*,";
            $lines[] = "Mohon Ditindaklanjuti melalui link berikut:";
            $lines[] = "";
            $lines[] = "🔗 https://kemenagtanahdatar.id/admin/dashboard";
            $lines[] = "";
            $lines[] = "🙏 *Terima Kasih*";
            $lines[] = "━━━━━━━━━━━━━━━━━━━━━━";

            $message = implode("\n", $lines);

            $waService->sendMessage($phone, $message);
        } catch (\Exception $e) {
            Log::warning('WA notification to petugas failed', [
                'petugas_id' => $petugasUserId,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public static function notifyUserViaWhatsApp(int $userId, string $namaLayanan, string $noReq, string $status, ?string $keterangan = null): void
    {
        try {
            $user = DB::table('users')->find($userId);
            if (! $user || empty($user->telp)) {
                return;
            }

            $phone = '62' . WhatsAppService::normalizePhoneNumber($user->telp);
            $waService = new WhatsAppService();

            $statusIcons = [
                'SUBMITTED' => '📤',
                'PENDING' => '⏳',
                'DITERIMA' => '✅',
                'DIPROSES' => '🔄',
                'SUKSES' => '🎉',
                'DITOLAK' => '❌',
                'BATAL' => '🚫',
            ];

            $statusLabels = [
                'SUBMITTED' => 'Terkirim',
                'PENDING' => 'Menunggu Verifikasi',
                'DITERIMA' => 'Diterima',
                'DIPROSES' => 'Sedang Diproses',
                'SUKSES' => 'Selesai',
                'DITOLAK' => 'Ditolak',
                'BATAL' => 'Dibatalkan',
            ];

            $icon = $statusIcons[$status] ?? '📋';
            $label = $statusLabels[$status] ?? $status;

            $lines = [
                "{$icon} *Update Status Pengajuan Layanan*",
                "━━━━━━━━━━━━━━━━━━━━━━",
                "",
                "👤 *Pemohon:* {$user->name}",
                "🏢 *Layanan:* {$namaLayanan}",
                "🔖 *No. Request:* {$noReq}",
                "📊 *Status:* {$icon} *{$label}*",
            ];

            if ($keterangan && $status === 'DITOLAK') {
                $lines[] = "";
                $lines[] = "📝 *Keterangan:* " . Str::limit(strip_tags($keterangan), 200);
            }

            $lines[] = "";
            $lines[] = "🕐 *Waktu:* " . now()->translatedFormat('d M Y, H:i');
            $lines[] = "";
            $lines[] = "━━━━━━━━━━━━━━━━━━━━━━";
            $lines[] = "Yth. *{$user->name}*,";
            $lines[] = "Silakan cek status pengajuan Anda melalui link berikut:";
            $lines[] = "";
            $lines[] = "🔗 https://kemenagtanahdatar.id/pengajuan-saya";
            $lines[] = "";
            $lines[] = "🙏 *Terima Kasih*";
            $lines[] = "━━━━━━━━━━━━━━━━━━━━━━";

            $message = implode("\n", $lines);

            $waService->sendMessage($phone, $message);
        } catch (\Exception $e) {
            Log::warning('WA notification to user failed', [
                'user_id' => $userId,
                'error' => $e->getMessage(),
            ]);
        }
    }

    private function normalizedRequirementType(?string $type): string
    {
        $normalized = strtolower(trim((string) $type));

        return in_array($normalized, ['file', 'image', 'input', 'textarea', 'date', 'datetime', 'option'], true)
            ? $normalized
            : 'file';
    }

    private function requirementTypeLabel(string $type): string
    {
        return match ($type) {
            'file' => 'PDF',
            'image' => 'Gambar',
            'input' => 'Input',
            'textarea' => 'Textarea',
            'date' => 'Tanggal',
            'datetime' => 'Tanggal & Waktu',
            'option' => 'Pilihan',
            default => 'File',
        };
    }

    private function requirementInputType(string $type): string
    {
        return match ($type) {
            'date' => 'date',
            'datetime' => 'datetime-local',
            default => 'text',
        };
    }

    private function requirementOptions($value): array
    {
        if (! is_string($value) || trim($value) === '') {
            return [];
        }

        $decoded = json_decode($value, true);

        if (! is_array($decoded)) {
            $normalized = preg_split('/[\r\n,|;]+/', $value) ?: [];

            return array_values(array_filter(array_map('trim', $normalized)));
        }

        return array_values(array_filter(array_map(static fn ($option) => (string) $option, $decoded), static fn ($option) => trim($option) !== ''));
    }

    private function isUploadRequirement(string $type): bool
    {
        return in_array($type, ['file', 'image'], true);
    }

    private function reportStatusLabel(string $status): string
    {
        return match ($status) {
            'DIKIRIM' => 'Dikirim',
            'DISETUJUI' => 'Disetujui',
            'DITOLAK' => 'Ditolak',
            'KOSONG' => 'Kosong',
            default => $status !== '' ? Str::headline(strtolower($status)) : 'Kosong',
        };
    }

    private function reportStatusTone(string $status): string
    {
        return match ($status) {
            'DISETUJUI' => 'emerald',
            'DITOLAK' => 'rose',
            'DIKIRIM' => 'amber',
            default => 'slate',
        };
    }

    private function reportStatusClass(string $status): string
    {
        return match ($this->reportStatusTone($status)) {
            'emerald' => 'border-emerald-200 bg-emerald-50 text-emerald-700',
            'rose' => 'border-rose-200 bg-rose-50 text-rose-700',
            'amber' => 'border-amber-200 bg-amber-50 text-amber-700',
            default => 'border-slate-200 bg-slate-50 text-slate-500',
        };
    }

    /**
     * Validate that all required file uploads are present
     * Returns array of missing required file names, empty if all ok
     */
    private function validateRequiredFiles(array $requirements, Request $request, array $existingFiles = []): array
    {
        $missing = [];

        foreach ($requirements as $requirement) {
            if (!$requirement['is_required']) {
                continue;
            }

            $type = $requirement['type_normalized'];
            if (!in_array($type, ['file', 'image'])) {
                continue;
            }

            $syaratId = (int) $requirement['id'];
            $fieldKey = $this->requirementFieldKey($type, $syaratId);

            // Check if new file is uploaded
            $hasNewUpload = $request->hasFile($fieldKey);

            // Check if existing file is already saved
            $hasExistingFile = isset($existingFiles[$syaratId])
                && !empty($existingFiles[$syaratId]['filename'])
                && $existingFiles[$syaratId]['filename'] !== 'NONE';

            if (!$hasNewUpload && !$hasExistingFile) {
                $missing[] = $requirement['title'];
            }
        }

        return $missing;
    }

    private function requestFormViewData(array $service, $requester, bool $editing = false, $requestRecord = null, array $existingAnswers = [], array $existingFiles = []): array
    {
        return [
            'service' => $service,
            'requester' => [
                'name' => $requester?->name,
                'identity' => $requester?->nomor_induk ?? '',
            ],
            'editing' => $editing,
            'requestRecord' => $requestRecord,
            'existingAnswers' => $existingAnswers,
            'existingFiles' => $existingFiles,
        ];
    }

    private function indonesianDateLabel(Carbon $date): string
    {
        $days = [
            'Minggu',
            'Senin',
            'Selasa',
            'Rabu',
            'Kamis',
            'Jumat',
            'Sabtu',
        ];

        $months = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        return sprintf(
            '%s, %d %s %d',
            $days[$date->dayOfWeek] ?? $date->format('l'),
            $date->day,
            $months[$date->month] ?? $date->format('F'),
            $date->year
        );
    }

    private function indonesianMonthLabel(Carbon $date): string
    {
        $months = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        return sprintf(
            '%s %d',
            $months[$date->month] ?? $date->format('F'),
            $date->year
        );
    }

    private function indonesianDateTimeFormat(?string $dateTime): string
    {
        if (!$dateTime) {
            return '-';
        }

        try {
            $date = Carbon::parse($dateTime);
            $days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            $months = [1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'];

            return sprintf(
                '%d %s %d, %02d:%02d',
                $date->day,
                $months[$date->month] ?? $date->format('F'),
                $date->year,
                $date->hour,
                $date->minute
            );
        } catch (\Exception $e) {
            return '-';
        }
    }

    private function assetToDataUri(string $path): string
    {
        if (! file_exists($path)) {
            return '';
        }

        $mime = mime_content_type($path) ?: 'image/png';

        return 'data:' . $mime . ';base64,' . base64_encode((string) file_get_contents($path));
    }

    private function requirementFieldKey(string $type, int $requirementId): string
    {
        return $this->isUploadRequirement($type) ? "files.{$requirementId}" : "values.{$requirementId}";
    }

    private function requirementValidationRules(array $requirement, bool $isDraft, bool $hasExistingUpload = false): array
    {
        $type = $requirement['type_normalized'];
        $isRequired = (bool) $requirement['is_required'] && ! $isDraft && ! $hasExistingUpload;
        $baseRules = $isRequired ? ['required'] : ['nullable'];

        return match ($type) {
            'file' => array_merge($baseRules, ['file', 'mimes:pdf', 'max:10240']),
            'image' => array_merge($baseRules, ['file', 'image', 'max:10240']),
            'textarea' => array_merge($baseRules, ['string', 'max:5000']),
            'input' => array_merge($baseRules, ['string', 'max:1000']),
            'date' => array_merge($baseRules, ['date']),
            'datetime' => array_merge($baseRules, ['date_format:Y-m-d\TH:i']),
            'option' => $this->requirementOptions($requirement['value'] ?? null) !== []
                ? array_merge($baseRules, ['string', Rule::in($this->requirementOptions($requirement['value'] ?? null))])
                : array_merge($baseRules, ['string', 'max:1000']),
            default => array_merge($baseRules, ['string', 'max:1000']),
        };
    }

    private function serviceIconPath(int $serviceId): string
    {
        // First check if specific icon exists for this service
        $specificIconPath = public_path("assets/img/ikon/{$serviceId}.webp");
        if (file_exists($specificIconPath)) {
            return asset("assets/img/ikon/{$serviceId}.webp");
        }

        // Fallback to random icon
        $icons = [
            'humas.webp',
            'presensi.webp',
            'RekapPresensi.webp',
            'LaporanKinerja.webp',
            'FileUploaded.webp',
            'tukin.webp',
            'uangmakan.webp',
            'logohalal.webp',
            '777.webp',
            '508.webp',
            '888.webp',
            '507.webp',
        ];

        $icon = $icons[$serviceId % count($icons)] ?? 'FileUploaded.webp';

        return asset("assets/img/ikon/{$icon}");
    }

    private function serviceCostLabel(?int $biaya): string
    {
        if ($biaya === null) {
            return 'Tidak tercantum';
        }

        if ($biaya <= 0) {
            return 'Gratis';
        }

        return 'Rp ' . number_format($biaya, 0, ',', '.');
    }

    private function serviceStatusLabel(?int $status): string
    {
        if ($status === null) {
            return 'Tidak diketahui';
        }

        return $status === 1 ? 'Aktif' : 'Nonaktif';
    }

    private function departmentCards(string $kategori, int $status)
    {
        $pageName = 'page_' . str_replace('-', '_', strtolower($kategori));

        $paginator = DB::table('ktd_department')
            ->whereRaw('LOWER(kategori) = ?', [strtolower($kategori)])
            ->where('status', $status)
            ->orderBy('id')
            ->paginate(6, ['*'], $pageName);

        $paginator->withPath(route('satuan-kerja'));
        $paginator->appends(['tab' => $kategori]);

        $paginator->setCollection($paginator->getCollection()->map(function ($item) use ($kategori) {
                $head = $this->departmentHead($item->id, $kategori, $item->nama);
                $pltHead = $head ? null : $this->departmentPltHead($item->id);
                $activeHead = $head ?? $pltHead;
                $employeeCount = DB::table('users')
                    ->where('dept_id', $item->id)
                    ->count();

                // Build head photo path
                $headPhotoPath = null;
                if ($activeHead && ! empty($activeHead->pp) && ! empty($activeHead->nomor_induk)) {
                    $headPhotoPath = asset("storage/users_berkas/{$activeHead->nomor_induk}/{$activeHead->pp}");
                }

                return [
                    'id' => $item->id,
                    'title' => $item->nama,
                    'subtitle' => $item->deskripsi ?: $item->kode,
                    'meta_label' => 'Nama Unit',
                    'meta_value' => $item->nama,
                    'extra_label' => 'Pegawai',
                    'extra_value' => $employeeCount,
                    'head_label' => $this->departmentHeadLabel($kategori, $item->nama, (bool) $pltHead),
                    'head_value' => $activeHead?->name,
                    'head_photo' => $headPhotoPath,
                    'head_initials' => $activeHead ? Str::upper(Str::substr($activeHead->name, 0, 2)) : Str::upper(Str::substr($item->nama, 0, 2)),
                    'cover' => Str::upper(Str::substr($item->nama, 0, 2)),
                    'cover_path' => asset("assets/img/seksi/{$item->id}.webp"),
                    'href' => route('unit-kerja.detail', $item->id),
                    'type' => $kategori,
                ];
            }));

        return $paginator;
    }

    private function externalGroups(): array
    {
        return [
            'swasta-lainnya' => $this->userCards(999),
            'pemerintah-daerah' => $this->userCards(998),
        ];
    }

    private function userCards(int $deptId)
    {
        $pageName = 'page_user_' . $deptId;

        $paginator = DB::table('users')
            ->where('dept_id', $deptId)
            ->orderBy('id')
            ->paginate(6, ['*'], $pageName);

        $paginator->withPath(route('satuan-kerja'));
        $paginator->appends(['tab' => $deptId === 999 ? 'swasta-lainnya' : 'pemerintah-daerah']);

        $paginator->setCollection($paginator->getCollection()->map(function ($item) use ($deptId) {
            // Build user photo path
            $userPhotoPath = null;
            if (! empty($item->pp) && ! empty($item->nomor_induk)) {
                $userPhotoPath = asset("storage/users_berkas/{$item->nomor_induk}/{$item->pp}");
            }

            return [
                'id' => $item->id,
                'title' => $item->name,
                'subtitle' => 'PP',
                'meta_label' => 'Nomor Induk',
                'meta_value' => $item->nomor_induk,
                'extra_label' => 'Satker',
                'extra_value' => $item->satker ?: '-',
                'head_label' => 'Pegawai',
                'head_value' => $item->name,
                'head_photo' => $userPhotoPath,
                'head_initials' => Str::upper(Str::substr($item->name, 0, 2)),
                'cover' => 'PP',
                'cover_path' => $userPhotoPath,
                'dept_id' => $deptId,
                'type' => 'user',
            ];
        }));

        return $paginator;
    }

    private function departmentPeople(int $deptId, ?int $excludeUserId = null)
    {
        $pageName = 'page_people_' . $deptId;
        $query = DB::table('users')
            ->where('dept_id', $deptId)
            ->whereNotIn('role', ['other', 'pensiun', 'pindah'])
            ->when($excludeUserId, fn ($builder) => $builder->where('id', '!=', $excludeUserId))
            ->orderBy('id');

        $paginator = $query->paginate(8, ['*'], $pageName);
        $paginator->withPath(route('unit-kerja.detail', $deptId));

        $paginator->setCollection($paginator->getCollection()->map(function ($item) use ($deptId) {
            $isKaur = strtolower($item->kat_jabatan ?? '') === 'kaur';
            return $this->personCard($item, $this->personLabel($item), false, $isKaur);
        }));

        return $paginator;
    }

    private function departmentHead(int $deptId, string $kategori, string $departmentName)
    {
        $query = DB::table('users')
            ->where('dept_id', $deptId)
            ->orderBy('id');

        if (strtolower($kategori) === 'kantor') {
            if ($this->isDepartmentName($departmentName, 'Sub-Bagian Tata Usaha')) {
                $query->where('role', 'kasubbag');
            } elseif ($this->isDepartmentName($departmentName, [
                'Seksi Pendidikan Agama Islam',
                'Seksi PD PONTREN',
                'Seksi Pendidikan Madrasah',
                'Seksi BIMAS Islam',
                'Penyelenggara Zakat dan Wakaf',
            ])) {
                $query->where('role', 'kasi');
            } else {
                $query->where('kat_jabatan', 'kepala');
            }
        } else {
            $query->where('kat_jabatan', 'kepala');
        }

        return $query->first();
    }

    private function departmentHeadLabel(string $kategori, string $departmentName, bool $isPlt = false): string
    {
        if ($isPlt) {
            return 'PLT Kepala';
        }

        if (strtolower($kategori) !== 'kantor') {
            return 'Kepala';
        }

        if ($this->isDepartmentName($departmentName, 'Sub-Bagian Tata Usaha')) {
            return 'Kasubbag';
        }

        if ($this->isDepartmentName($departmentName, [
            'Seksi Pendidikan Agama Islam',
            'Seksi PD PONTREN',
            'Seksi Pendidikan Madrasah',
            'Seksi BIMAS Islam',
            'Penyelenggara Zakat dan Wakaf',
        ])) {
            return 'Kasi';
        }

        if ($this->isDepartmentName($departmentName, [
            'Pengawas',
            'Bagian Operasional Kantor(Non ASN)',
        ])) {
            return 'Ketua';
        }

        return 'Kepala';
    }

    private function departmentPltHead(int $deptId)
    {
        return DB::table('plt_plh')
            ->join('users', 'users.id', '=', 'plt_plh.user_id')
            ->where('plt_plh.dept_id_plh', $deptId)
            ->orderByDesc('plt_plh.id')
            ->select('users.id', 'users.name', 'users.nomor_induk', 'users.satker', 'users.role', 'users.kat_jabatan', 'users.pp')
            ->first();
    }

    private function personCard(object $item, string $roleLabel, bool $featured = false, bool $isKaur = false): array
    {
        return [
            'id' => $item->id,
            'name' => $item->name,
            'role_label' => $roleLabel,
            'nomor_induk' => $item->nomor_induk ?: '-',
            'satker' => $item->satker ?: '-',
            'avatar_text' => $this->personInitials($item->name),
            'photo_path' => $this->personPhotoUrl($item),
            'featured' => $featured,
            'is_kaur' => $isKaur,
        ];
    }

    private function personLabel(object $item): string
    {
        if (! empty($item->pekerjaan)) {
            return Str::headline($item->pekerjaan);
        }

        if (! empty($item->kat_jabatan)) {
            return Str::headline($item->kat_jabatan);
        }

        if (! empty($item->role)) {
            return Str::headline($item->role);
        }

        return 'Pegawai';
    }

    private function personInitials(string $name): string
    {
        $words = preg_split('/\s+/', trim($name)) ?: [];
        $letters = collect($words)
            ->filter()
            ->take(2)
            ->map(function ($word) {
                return Str::upper(Str::substr($word, 0, 1));
            })
            ->implode('');

        return $letters !== '' ? $letters : 'PP';
    }

    private function personPhotoUrl(object $item): ?string
    {
        if (empty($item->pp) || empty($item->nomor_induk)) {
            return null;
        }

        $relativePath = "storage/users_berkas/{$item->nomor_induk}/{$item->pp}";
        $localPath = public_path($relativePath);

        if (file_exists($localPath)) {
            return asset($relativePath);
        }

        return "https://ptsp.kemenagtanahdatar.cloud/uploads/UsersBerkas/{$item->nomor_induk}/{$item->pp}";
    }

    private function isDepartmentName(string $departmentName, string|array $expected): bool
    {
        $candidates = is_array($expected) ? $expected : [$expected];

        foreach ($candidates as $candidate) {
            if (Str::lower(trim($departmentName)) === Str::lower(trim($candidate))) {
                return true;
            }
        }

        return false;
    }

    /**
     * Profil Kantor page.
     */

    /**
     * Sejarah page.
     */
    public function sejarah()
    {
        return view('pages.sejarah');
    }

    /**
     * Struktur Organisasi page.
     */
    public function strukturOrganisasi()
    {
        return view('pages.struktur-organisasi');
    }

    /**
     * Profil Madrasah page.
     */
    public function profilMadrasah()
    {
        $user = auth()->user();
        $formData = [
            'nama' => '',
            'nsm' => '',
            'npsm' => '',
            'status_lembaga' => '',
            'is_status_readonly' => false,
            'is_nama_readonly' => false,
            'jalan' => '',
            'jorong' => '',
            'nagari' => '',
            'kecamatan' => '',
            'koordinat' => '',
            'telepon' => '',
            'email' => '',
            'website' => '',
            'waktu_belajar' => '',
            'visi' => '',
            'sk_pendirian' => '',
            'tanggal_sk' => '',
            'komite_lembaga' => '',
            'akreditasi' => '',
            'tanggal_akreditasi' => '',
            'status_kkm' => '',
            'jarak_pusat_provinsi' => '',
            'jarak_pusat_kabupaten' => '',
            'jarak_kecamatan' => '',
            'jarak_kanwil_kemenag' => '',
            'jarak_kemenag_kab' => '',
            'jarak_kua' => '',
            'jarak_ra_terdekat' => '',
            'jarak_mi_terdekat' => '',
            'jarak_mts_terdekat' => '',
            'jarak_ma_terdekat' => '',
            'jarak_pontren_terdekat' => '',
            'jarak_tk_terdekat' => '',
            'jarak_sd_terdekat' => '',
            'jarak_smp_terdekat' => '',
            'jarak_sma_terdekat' => '',
        ];

        // Check if user is from madrasah category (mi, mts, ma, man, mtsn, min, ra) OR dept_id 999/998
        $dept = $user && $user->dept_id ? DB::table('ktd_department')->where('id', $user->dept_id)->first() : null;
        $kategoriLower = strtolower($dept->kategori ?? '');
        $isMadrasahCategory = in_array($kategoriLower, ['mi', 'mts', 'ma', 'man', 'mtsn', 'min', 'ra']);
        $isSwastaDept = in_array($user->dept_id, [999, 998]); // Dept untuk madrasah swasta
        $shouldAutoCreate = ($isMadrasahCategory || $isSwastaDept) && !$user->madrasah_id;

        // Auto-create madrasah for users without madrasah_id but with madrasah category dept or swasta dept
        if ($shouldAutoCreate) {
            // Use satker as default name, fallback to dept->nama
            $defaultNama = $user->satker ?? $dept->nama ?? 'Madrasah Baru';

            // Create new madrasah entry
            $madrasahId = DB::table('ktd_madrasah')->insertGetId([
                'dept_id' => $user->dept_id,
                'nama' => $defaultNama,
                'kategori' => $kategoriLower ?: 'other',
                'status_lembaga' => $dept->status_lembaga ?? 'Swasta',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Update user's madrasah_id
            DB::table('users')->where('id', $user->id)->update([
                'madrasah_id' => $madrasahId,
                'updated_at' => now(),
            ]);

            // Refresh user data
            $user = auth()->user()->fresh();
        }

        // Get madrasah data based on user's madrasah_id (preferred) or dept_id (fallback)
        $madrasah = null;
        if ($user && $user->madrasah_id) {
            $madrasah = DB::table('ktd_madrasah')->where('id', $user->madrasah_id)->first();
        } elseif ($user && $user->dept_id) {
            // Backward compatibility: fallback to ktd_department if madrasah_id not set
            $madrasah = DB::table('ktd_department')->where('id', $user->dept_id)->first();
        }

        if ($madrasah) {
            // Nama Madrasah bisa diedit untuk user dengan dept_id madrasah (mi, mts, ma, dll)
            // Tapi read-only untuk madrasah negeri (man, min, mtsn)
            $kategoriLower = strtolower($madrasah->kategori ?? '');
            if (in_array($kategoriLower, ['man', 'min', 'mtsn'])) {
                // Madrasah negeri - nama read-only
                $formData['is_nama_readonly'] = true;
            } else {
                // Madrasah swasta atau lainnya - nama bisa diedit
                $formData['is_nama_readonly'] = false;
            }
            $formData['nama'] = $madrasah->nama ?? '';

            // Map columns to form fields
            $formData['status_lembaga'] = $madrasah->status_lembaga ?? $madrasah->status ?? '';
            $formData['jarak_kecamatan'] = $madrasah->jarak_kecamatan ?? '';
            $formData['jarak_kua'] = $madrasah->jarak_kua ?? '';
            $formData['jarak_kemenag_kab'] = $madrasah->jarak_kemenag_kab ?? '';
            $formData['jarak_kanwil_kemenag'] = $madrasah->jarak_kanwil_kemenag ?? '';

            // Handle alamat field (might contain multiple parts)
            if (!empty($madrasah->alamat)) {
                $alamatParts = explode(',', $madrasah->alamat);
                $formData['jalan'] = trim($alamatParts[0] ?? '');
                $formData['jorong'] = trim($alamatParts[1] ?? '');
                $formData['nagari'] = trim($alamatParts[2] ?? '');
                $formData['kecamatan'] = trim($alamatParts[3] ?? '');
            } else {
                // Use individual fields if available
                $formData['jalan'] = $madrasah->jalan ?? '';
                $formData['jorong'] = $madrasah->jorong ?? '';
                $formData['nagari'] = $madrasah->nagari ?? '';
                $formData['kecamatan'] = $madrasah->kecamatan ?? '';
            }

            // Map other fields if available
            if (!empty($madrasah->telepon)) {
                $formData['telepon'] = $madrasah->telepon;
            }
            if (!empty($madrasah->email)) {
                $formData['email'] = $madrasah->email;
            }
            if (!empty($madrasah->website)) {
                $formData['website'] = $madrasah->website;
            }
            if (!empty($madrasah->koordinat)) {
                $formData['koordinat'] = $madrasah->koordinat;
            }
            if (!empty($madrasah->akreditasi)) {
                $formData['akreditasi'] = $madrasah->akreditasi;
            }
            if (!empty($madrasah->waktu_belajar)) {
                $formData['waktu_belajar'] = $madrasah->waktu_belajar;
            }
            if (!empty($madrasah->nsm)) {
                $formData['nsm'] = $madrasah->nsm;
            }
            if (!empty($madrasah->npsm)) {
                $formData['npsm'] = $madrasah->npsm;
            }

            // Map missing fields
            if (!empty($madrasah->tanggal_akreditasi)) {
                $formData['tanggal_akreditasi'] = $madrasah->tanggal_akreditasi;
            }
            if (!empty($madrasah->status_kkm)) {
                $formData['status_kkm'] = $madrasah->status_kkm;
            }
            if (!empty($madrasah->tanggal_sk)) {
                $formData['tanggal_sk'] = $madrasah->tanggal_sk;
            }
            if (!empty($madrasah->komite_lembaga)) {
                $formData['komite_lembaga'] = $madrasah->komite_lembaga;
            }
            if (!empty($madrasah->visi)) {
                $formData['visi'] = $madrasah->visi;
            }

            // Map jarak fields
            if (!empty($madrasah->jarak_pusat_provinsi)) {
                $formData['jarak_pusat_provinsi'] = $madrasah->jarak_pusat_provinsi;
            }
            if (!empty($madrasah->jarak_pusat_kabupaten)) {
                $formData['jarak_pusat_kabupaten'] = $madrasah->jarak_pusat_kabupaten;
            }
            if (!empty($madrasah->jarak_kecamatan)) {
                $formData['jarak_kecamatan'] = $madrasah->jarak_kecamatan;
            }
            if (!empty($madrasah->jarak_kanwil_kemenag)) {
                $formData['jarak_kanwil_kemenag'] = $madrasah->jarak_kanwil_kemenag;
            }
            if (!empty($madrasah->jarak_kemenag_kab)) {
                $formData['jarak_kemenag_kab'] = $madrasah->jarak_kemenag_kab;
            }
            if (!empty($madrasah->jarak_kua)) {
                $formData['jarak_kua'] = $madrasah->jarak_kua;
            }
            if (!empty($madrasah->jarak_ra_terdekat)) {
                $formData['jarak_ra_terdekat'] = $madrasah->jarak_ra_terdekat;
            }
            if (!empty($madrasah->jarak_mi_terdekat)) {
                $formData['jarak_mi_terdekat'] = $madrasah->jarak_mi_terdekat;
            }
            if (!empty($madrasah->jarak_mts_terdekat)) {
                $formData['jarak_mts_terdekat'] = $madrasah->jarak_mts_terdekat;
            }
            if (!empty($madrasah->jarak_ma_terdekat)) {
                $formData['jarak_ma_terdekat'] = $madrasah->jarak_ma_terdekat;
            }
            if (!empty($madrasah->jarak_pontren_terdekat)) {
                $formData['jarak_pontren_terdekat'] = $madrasah->jarak_pontren_terdekat;
            }
            if (!empty($madrasah->jarak_tk_terdekat)) {
                $formData['jarak_tk_terdekat'] = $madrasah->jarak_tk_terdekat;
            }
            if (!empty($madrasah->jarak_sd_terdekat)) {
                $formData['jarak_sd_terdekat'] = $madrasah->jarak_sd_terdekat;
            }
            if (!empty($madrasah->jarak_smp_terdekat)) {
                $formData['jarak_smp_terdekat'] = $madrasah->jarak_smp_terdekat;
            }
            if (!empty($madrasah->jarak_sma_terdekat)) {
                $formData['jarak_sma_terdekat'] = $madrasah->jarak_sma_terdekat;
            }

            // Check kategori: jika MAN/MIN/MTSN, status lembaga = NEGERI dan read-only
            $kategoriLower = strtolower($madrasah->kategori ?? '');
            if (in_array($kategoriLower, ['man', 'min', 'mtsn'])) {
                $formData['status_lembaga'] = 'NEGERI';
                $formData['is_status_readonly'] = true;
            }
        }

        return view('madrasah.profilmadrasah', [
            'formData' => $formData,
        ]);
    }

    /**
     * Save Profil Madrasah - update ktd_department.
     */
    public function saveProfilMadrasah(Request $request)
    {
        $user = auth()->user();

        // Determine madrasah_id (preferred) or dept_id (fallback)
        $madrasahId = $user->madrasah_id ?? null;
        $deptId = $user->dept_id ?? null;

        if (!$madrasahId && !$deptId) {
            return redirect()->back()->with('error', 'Unit kerja tidak ditemukan');
        }

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nsm' => 'nullable|string|max:50',
            'npsm' => 'nullable|string|max:50',
            'status_lembaga' => 'nullable|string|max:20',
            'jarak_pusat_provinsi' => 'nullable|string|max:50',
            'jarak_pusat_kabupaten' => 'nullable|string|max:50',
            'jarak_kecamatan' => 'nullable|string|max:50',
            'jarak_kanwil_kemenag' => 'nullable|string|max:50',
            'jarak_kemenag_kab' => 'nullable|string|max:50',
            'jarak_kua' => 'nullable|string|max:50',
            'jarak_ra_terdekat' => 'nullable|string|max:50',
            'jarak_mi_terdekat' => 'nullable|string|max:50',
            'jarak_mts_terdekat' => 'nullable|string|max:50',
            'jarak_ma_terdekat' => 'nullable|string|max:50',
            'jarak_pontren_terdekat' => 'nullable|string|max:50',
            'jarak_tk_terdekat' => 'nullable|string|max:50',
            'jarak_sd_terdekat' => 'nullable|string|max:50',
            'jarak_smp_terdekat' => 'nullable|string|max:50',
            'jarak_sma_terdekat' => 'nullable|string|max:50',
            'telepon' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|string|max:255',
            'koordinat' => 'nullable|string|max:100',
            'waktu_belajar' => 'nullable|string|max:20',
            'visi' => 'nullable|string',
            'sk_pendirian' => 'nullable|string|max:255',
            'tanggal_sk' => 'nullable|date',
            'komite_lembaga' => 'nullable|string|max:30',
            'akreditasi' => 'nullable|string|max:10',
            'tanggal_akreditasi' => 'nullable|date',
            'status_kkm' => 'nullable|string|max:20',
        ]);

        $data = [
            'nama' => $request->input('nama'),
            'nsm' => $request->input('nsm'),
            'npsm' => $request->input('npsm'),
            'status_lembaga' => $request->input('status_lembaga'),
            'jalan' => $request->input('jalan'),
            'jorong' => $request->input('jorong'),
            'nagari' => $request->input('nagari'),
            'kecamatan' => $request->input('kecamatan'),
            'telepon' => $request->input('telepon'),
            'email' => $request->input('email'),
            'website' => $request->input('website'),
            'koordinat' => $request->input('koordinat'),
            'waktu_belajar' => $request->input('waktu_belajar'),
            'visi' => $request->input('visi'),
            'sk_pendirian' => $request->input('sk_pendirian'),
            'tanggal_sk' => $request->input('tanggal_sk'),
            'komite_lembaga' => $request->input('komite_lembaga'),
            'akreditasi' => $request->input('akreditasi'),
            'tanggal_akreditasi' => $request->input('tanggal_akreditasi'),
            'status_kkm' => $request->input('status_kkm'),
            'jarak_pusat_provinsi' => $request->input('jarak_pusat_provinsi'),
            'jarak_pusat_kabupaten' => $request->input('jarak_pusat_kabupaten'),
            'jarak_kecamatan' => $request->input('jarak_kecamatan'),
            'jarak_kanwil_kemenag' => $request->input('jarak_kanwil_kemenag'),
            'jarak_kemenag_kab' => $request->input('jarak_kemenag_kab'),
            'jarak_kua' => $request->input('jarak_kua'),
            'jarak_ra_terdekat' => $request->input('jarak_ra_terdekat'),
            'jarak_mi_terdekat' => $request->input('jarak_mi_terdekat'),
            'jarak_mts_terdekat' => $request->input('jarak_mts_terdekat'),
            'jarak_ma_terdekat' => $request->input('jarak_ma_terdekat'),
            'jarak_pontren_terdekat' => $request->input('jarak_pontren_terdekat'),
            'jarak_tk_terdekat' => $request->input('jarak_tk_terdekat'),
            'jarak_sd_terdekat' => $request->input('jarak_sd_terdekat'),
            'jarak_smp_terdekat' => $request->input('jarak_smp_terdekat'),
            'jarak_sma_terdekat' => $request->input('jarak_sma_terdekat'),
            'updated_at' => now(),
        ];

        // Filter out null values
        $data = array_filter($data, fn($v) => $v !== null);

        // Update madrasah table (preferred) or department table (fallback)
        if ($madrasahId) {
            DB::table('ktd_madrasah')->where('id', $madrasahId)->update($data);
        } elseif ($deptId) {
            DB::table('ktd_department')->where('id', $deptId)->update($data);
        }

        // Sync nama madrasah ke users.satker jika nama berubah
        $namaMadrasah = $request->input('nama');
        if ($namaMadrasah && $user->satker !== $namaMadrasah) {
            DB::table('users')->where('id', $user->id)->update([
                'satker' => $namaMadrasah,
                'updated_at' => now(),
            ]);
        }

        return redirect()->back()->with('success', 'Profil madrasah berhasil disimpan!');
    }

    /**
     * Pegawai Madrasah page - daftar pegawai berdasarkan dept_id user.
     */
    /**
     * Pegawai Madrasah page - daftar pegawai/staf berdasarkan madrasah_id user.
     */
    public function pegawaiMadrasah(Request $request)
    {
        $user = auth()->user();
        $madrasahId = $user->madrasah_id ?? null;
        $deptId = $user->dept_id ?? null;
        $deptName = 'Madrasah';
        $isMadrasahType = false;

        // Auto-create madrasah if needed (same logic as profilMadrasah)
        if (!$madrasahId && $deptId) {
            $dept = DB::table('ktd_department')->where('id', $deptId)->first();
            $kategoriLower = strtolower($dept->kategori ?? '');
            $isMadrasahCategory = in_array($kategoriLower, ['mi', 'mts', 'ma', 'man', 'mtsn', 'min', 'ra']);
            $isSwastaDept = in_array($deptId, [999, 998]);

            if (($isMadrasahCategory || $isSwastaDept) && !$user->madrasah_id) {
                $defaultNama = $user->satker ?? $dept->nama ?? 'Madrasah Baru';
                $madrasahId = DB::table('ktd_madrasah')->insertGetId([
                    'dept_id' => $deptId,
                    'nama' => $defaultNama,
                    'kategori' => $kategoriLower ?: 'other',
                    'status_lembaga' => $dept->status_lembaga ?? 'Swasta',
                    'status' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                DB::table('users')->where('id', $user->id)->update([
                    'madrasah_id' => $madrasahId,
                    'updated_at' => now(),
                ]);
                $user = auth()->user()->fresh();
            }
        }

        // Get madrasah info (preferred) or fallback to department
        $madrasah = null;
        if ($madrasahId) {
            $madrasah = DB::table('ktd_madrasah')->where('id', $madrasahId)->first();
        } elseif ($deptId) {
            $madrasah = DB::table('ktd_department')->where('id', $deptId)->first();
        }

        if ($madrasah) {
            $deptName = $madrasah->nama ?? 'Madrasah';
            $kategoriLower = strtolower($madrasah->kategori ?? '');
            $isMadrasahType = in_array($kategoriLower, ['man', 'min', 'mtsn', 'ra']);
        }

        // Query with madrasah_id (preferred) or dept_id (fallback)
        $query = DB::table('tenaga_ktd')
            ->whereIn('kat_jabatan', ['staf', 'honorer'])
            ->where('is_active', true);

        if ($madrasahId) {
            $query->where('madrasah_id', $madrasahId);
        } else {
            $query->where('dept_id', $deptId);
        }

        $pegawaiList = $query->orderBy('nama')->paginate(15);

        // Add profile photo URLs from users table via user_id
        $pegawaiList->getCollection()->transform(function ($item) {
            if ($item->user_id) {
                $userData = DB::table('users')->where('id', $item->user_id)->first();
                if ($userData && $userData->pp && $userData->nomor_induk) {
                    $item->photo_url = asset('storage/users_berkas/' . $userData->nomor_induk . '/' . $userData->pp);
                } else {
                    $item->photo_url = null;
                }
            } else {
                $item->photo_url = null;
            }
            $item->initials = $item->nama ? strtoupper(substr($item->nama, 0, 2)) : 'NA';
            return $item;
        });

        // Summary stats - ASN: PNS/CPNS/PPPK, Non ASN: Honorer
        // Helper closure for building the stats query
        $buildStatsQuery = function ($query) use ($madrasahId, $deptId) {
            if ($madrasahId) {
                $query->where('madrasah_id', $madrasahId);
            } else {
                $query->where('dept_id', $deptId);
            }
            return $query;
        };

        $stats = [
            'total' => $pegawaiList->total(),
            'asn' => $buildStatsQuery(
                DB::table('tenaga_ktd')->whereIn('kat_jabatan', ['staf', 'honorer'])->whereIn('status', ['PNS', 'CPNS', 'PPPK'])
            )->count(),
            'honorer' => $buildStatsQuery(
                DB::table('tenaga_ktd')->whereIn('kat_jabatan', ['staf', 'honorer'])->whereNotIn('status', ['PNS', 'CPNS', 'PPPK'])
            )->count(),
        ];

        return view('madrasah.pegawaimadrasah', [
            'pegawaiList' => $pegawaiList,
            'deptName' => $deptName,
            'isMadrasahType' => $isMadrasahType,
            'stats' => $stats,
        ]);
    }

    /**
     * Guru Madrasah page - daftar guru berdasarkan madrasah_id user.
     */
    public function guruMadrasah(Request $request)
    {
        $user = auth()->user();
        $madrasahId = $user->madrasah_id ?? null;
        $deptId = $user->dept_id ?? null;
        $deptName = 'Madrasah';

        // Auto-create madrasah if needed (same logic as profilMadrasah)
        if (!$madrasahId && $deptId) {
            $dept = DB::table('ktd_department')->where('id', $deptId)->first();
            $kategoriLower = strtolower($dept->kategori ?? '');
            $isMadrasahCategory = in_array($kategoriLower, ['mi', 'mts', 'ma', 'man', 'mtsn', 'min', 'ra']);
            $isSwastaDept = in_array($deptId, [999, 998]);

            if (($isMadrasahCategory || $isSwastaDept) && !$user->madrasah_id) {
                $defaultNama = $user->satker ?? $dept->nama ?? 'Madrasah Baru';
                $madrasahId = DB::table('ktd_madrasah')->insertGetId([
                    'dept_id' => $deptId,
                    'nama' => $defaultNama,
                    'kategori' => $kategoriLower ?: 'other',
                    'status_lembaga' => $dept->status_lembaga ?? 'Swasta',
                    'status' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                DB::table('users')->where('id', $user->id)->update([
                    'madrasah_id' => $madrasahId,
                    'updated_at' => now(),
                ]);
                $user = auth()->user()->fresh();
            }
        }

        // Get madrasah info (preferred) or fallback to department
        $madrasah = null;
        if ($madrasahId) {
            $madrasah = DB::table('ktd_madrasah')->where('id', $madrasahId)->first();
        } elseif ($deptId) {
            $madrasah = DB::table('ktd_department')->where('id', $deptId)->first();
        }

        if ($madrasah) {
            $deptName = $madrasah->nama ?? 'Madrasah';
        }

        // Get only guru based on madrasah_id (preferred) or dept_id (fallback) from tenaga_ktd
        $guruQuery = DB::table('tenaga_ktd')
            ->where('kat_jabatan', 'guru')
            ->where('is_active', true);

        if ($madrasahId) {
            $guruQuery->where('madrasah_id', $madrasahId);
        } else {
            $guruQuery->where('dept_id', $deptId);
        }

        $guruList = $guruQuery->orderBy('nama')->paginate(15);

        // Add profile photo URLs from users table via user_id
        $guruList->getCollection()->transform(function ($item) {
            if ($item->user_id) {
                $userData = DB::table('users')->where('id', $item->user_id)->first();
                if ($userData && $userData->pp && $userData->nomor_induk) {
                    $item->photo_url = asset('storage/users_berkas/' . $userData->nomor_induk . '/' . $userData->pp);
                } else {
                    $item->photo_url = null;
                }
            } else {
                $item->photo_url = null;
            }
            $item->initials = $item->nama ? strtoupper(substr($item->nama, 0, 2)) : 'NA';
            return $item;
        });

        // Helper closure for building the stats query
        $buildStatsQuery = function ($query) use ($madrasahId, $deptId) {
            if ($madrasahId) {
                $query->where('madrasah_id', $madrasahId);
            } else {
                $query->where('dept_id', $deptId);
            }
            return $query;
        };

        // Summary stats - serdik: sertifikasi / non-sertifikasi
        $stats = [
            'total' => $guruList->total(),
            'sertifikasi' => $buildStatsQuery(
                DB::table('tenaga_ktd')->where('kat_jabatan', 'guru')->where('serdik', 'sertifikasi')
            )->count(),
            'belum_sertifikasi' => $buildStatsQuery(
                DB::table('tenaga_ktd')->where('kat_jabatan', 'guru')->whereIn('serdik', ['non-sertifikasi', 'non-guru', 'unknown'])
            )->count(),
        ];

        return view('madrasah.gurumadrasah', [
            'guruList' => $guruList,
            'deptName' => $deptName,
            'stats' => $stats,
        ]);
    }

    /**
     * Save Pegawai Madrasah - insert/update ke tabel tenaga_ktd.
     * Uses madrasah_id from user (preferred) or dept_id (fallback).
     */
    public function savePegawaiMadrasah(Request $request)
    {
        $user = auth()->user();
        $madrasahId = $user->madrasah_id ?? null;
        $deptId = $user->dept_id ?? null;

        if (!$madrasahId && !$deptId) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Unit kerja tidak ditemukan'], 400);
            }
            return redirect()->back()->with('error', 'Unit kerja tidak ditemukan');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:pns,pppk,honor,honorer',
            'nomor_induk' => 'nullable|string|max:50',
            'nik' => 'nullable|string|max:20',
            'npwp' => 'nullable|string|max:30',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'jk' => 'nullable|in:Pria,Wanita',
            'golongan' => 'nullable|string|max:20',
            'jabatan' => 'nullable|string|max:100',
            'tmt_tugas' => 'nullable|date',
            'kgb' => 'nullable|date',
            'masa_kerja_tahun' => 'nullable|string|max:10',
            'masa_kerja_bulan' => 'nullable|string|max:10',
            'jurusan' => 'nullable|string|max:100',
            'fakultas' => 'nullable|string|max:100',
            'universitas' => 'nullable|string|max:100',
            'email' => 'nullable|email|max:255',
            'telp' => 'nullable|string|max:20',
            'alamat_ktp' => 'nullable|string',
            'alamat' => 'nullable|string',
            'keterangan' => 'nullable|string',
        ]);

        // Map status values
        $statusMap = [
            'pns' => 'PNS',
            'pppk' => 'PPPK',
            'honor' => 'Honorer',
            'honorer' => 'Honorer',
        ];

        $data = [
            'madrasah_id' => $madrasahId,
            'dept_id' => $deptId, // Keep for backward compatibility
            'created_by' => $user->id,
            'nama' => $validated['name'],
            'kat_jabatan' => 'staf',
            'status' => $statusMap[$validated['status']] ?? 'Honorer',
            'nomor_induk' => $validated['nomor_induk'] ?? null,
            'nik' => $validated['nik'] ?? null,
            'npwp' => $validated['npwp'] ?? null,
            'tempat_lahir' => $validated['tempat_lahir'] ?? null,
            'tanggal_lahir' => $validated['tanggal_lahir'] ?? null,
            'jenis_kelamin' => $validated['jk'] ?? null,
            'golongan' => $validated['golongan'] ?? null,
            'jabatan' => $validated['jabatan'] ?? null,
            'tmt_tugas' => $validated['tmt_tugas'] ?? null,
            'kgb' => $validated['kgb'] ?? null,
            'masa_kerja_tahun' => $validated['masa_kerja_tahun'] ?? null,
            'masa_kerja_bulan' => $validated['masa_kerja_bulan'] ?? null,
            'jurusan' => $validated['jurusan'] ?? null,
            'fakultas' => $validated['fakultas'] ?? null,
            'universitas' => $validated['universitas'] ?? null,
            'email' => $validated['email'] ?? null,
            'telp' => $validated['telp'] ?? null,
            'alamat_ktp' => $validated['alamat_ktp'] ?? null,
            'alamat' => $validated['alamat'] ?? null,
            'keterangan' => $validated['keterangan'] ?? null,
            'is_active' => true,
            'source_table' => 'manual',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // Filter out null values
        $data = array_filter($data, fn($v) => $v !== null);

        DB::table('tenaga_ktd')->insert($data);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Pegawai berhasil ditambahkan']);
        }

        return redirect()->back()->with('success', 'Pegawai berhasil ditambahkan!');
    }

    /**
     * Update Pegawai Madrasah.
     */
    public function updatePegawaiMadrasah(Request $request)
    {
        $user = auth()->user();
        $deptId = $user->dept_id ?? null;

        if (!$deptId) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Unit kerja tidak ditemukan'], 400);
            }
            return redirect()->back()->with('error', 'Unit kerja tidak ditemukan');
        }

        $validated = $request->validate([
            'id' => 'required|integer',
            'name' => 'required|string|max:255',
            'status' => 'required|in:PNS,PPPK,Honorer,pns,pppk,honorer',
            'nomor_induk' => 'nullable|string|max:50',
            'nik' => 'nullable|string|max:20',
            'npwp' => 'nullable|string|max:30',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'jk' => 'nullable|in:Pria,Wanita',
            'golongan' => 'nullable|string|max:20',
            'jabatan' => 'nullable|string|max:100',
            'tmt_tugas' => 'nullable|date',
            'kgb' => 'nullable|date',
            'masa_kerja_tahun' => 'nullable|integer|min:0',
            'masa_kerja_bulan' => 'nullable|integer|min:0|max:11',
            'jurusan' => 'nullable|string|max:100',
            'fakultas' => 'nullable|string|max:100',
            'universitas' => 'nullable|string|max:100',
            'email' => 'nullable|email|max:255',
            'telp' => 'nullable|string|max:20',
            'alamat_ktp' => 'nullable|string',
            'alamat' => 'nullable|string',
            'keterangan' => 'nullable|string',
        ]);

        $id = $validated['id'];

        // Check if record exists and has no user_id
        $existing = DB::table('tenaga_ktd')->where('id', $id)->first();
        if (!$existing) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
            }
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }

        if ($existing->user_id) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Tidak dapat mengedit data yang sudah terhubung dengan user'], 403);
            }
            return redirect()->back()->with('error', 'Tidak dapat mengedit data yang sudah terhubung dengan user');
        }

        // Map status values
        $statusMap = [
            'pns' => 'PNS', 'pppk' => 'PPPK', 'honorer' => 'Honorer',
            'honor' => 'Honorer', 'PNS' => 'PNS', 'PPPK' => 'PPPK', 'Honorer' => 'Honorer',
        ];

        $data = [
            'nama' => $validated['name'],
            'status' => $statusMap[strtolower($validated['status'])] ?? $validated['status'],
            'nomor_induk' => $validated['nomor_induk'] ?? null,
            'nik' => $validated['nik'] ?? null,
            'npwp' => $validated['npwp'] ?? null,
            'tempat_lahir' => $validated['tempat_lahir'] ?? null,
            'tanggal_lahir' => $validated['tanggal_lahir'] ?? null,
            'jenis_kelamin' => $validated['jk'] ?? null,
            'golongan' => $validated['golongan'] ?? null,
            'jabatan' => $validated['jabatan'] ?? null,
            'tmt_tugas' => $validated['tmt_tugas'] ?? null,
            'kgb' => $validated['kgb'] ?? null,
            'masa_kerja_tahun' => $validated['masa_kerja_tahun'] ?? null,
            'masa_kerja_bulan' => $validated['masa_kerja_bulan'] ?? null,
            'jurusan' => $validated['jurusan'] ?? null,
            'fakultas' => $validated['fakultas'] ?? null,
            'universitas' => $validated['universitas'] ?? null,
            'email' => $validated['email'] ?? null,
            'telp' => $validated['telp'] ?? null,
            'alamat_ktp' => $validated['alamat_ktp'] ?? null,
            'alamat' => $validated['alamat'] ?? null,
            'keterangan' => $validated['keterangan'] ?? null,
            'updated_at' => now(),
        ];

        $data = array_filter($data, fn($v) => $v !== null);

        DB::table('tenaga_ktd')->where('id', $id)->update($data);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Pegawai berhasil diperbarui']);
        }

        return redirect()->back()->with('success', 'Pegawai berhasil diperbarui!');
    }

    /**
     * Delete Pegawai Madrasah.
     */
    public function deletePegawaiMadrasah(Request $request)
    {
        $user = auth()->user();

        try {
            $validated = $request->validate([
                'id' => 'required|integer',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'message' => 'Validasi gagal: ' . collect($e->errors())->flatten()->first()], 422);
        }

        $id = $validated['id'];

        // Check if record exists
        $existing = DB::table('tenaga_ktd')->where('id', $id)->first();
        if (!$existing) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
        }

        // Check if has user_id
        if ($existing->user_id) {
            return response()->json(['success' => false, 'message' => 'Tidak dapat menghapus data yang sudah terhubung dengan user'], 403);
        }

        DB::table('tenaga_ktd')->where('id', $id)->delete();

        return response()->json(['success' => true, 'message' => 'Pegawai berhasil dihapus']);
    }

    /**
     * Save Guru Madrasah - insert/update ke tabel tenaga_ktd.
     * Uses madrasah_id from user (preferred) or dept_id (fallback).
     */
    public function saveGuruMadrasah(Request $request)
    {
        $user = auth()->user();
        $madrasahId = $user->madrasah_id ?? null;
        $deptId = $user->dept_id ?? null;

        if (!$madrasahId && !$deptId) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Unit kerja tidak ditemukan'], 400);
            }
            return redirect()->back()->with('error', 'Unit kerja tidak ditemukan');
        }

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'kat_jabatan' => 'required|in:guru,kepala',
            'status' => 'required|in:PNS,PPPK,HONOR',
            'nomor_induk' => 'nullable|string|max:50',
            'nik' => 'nullable|string|max:20',
            'npwp' => 'nullable|string|max:30',
            'nuptk' => 'nullable|string|max:20',
            'npk' => 'nullable|string|max:20',
            'nrg' => 'nullable|string|max:20',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:Pria,Wanita',
            'nama_ibu' => 'nullable|string|max:100',
            'golongan' => 'nullable|string|max:20',
            'jabatan' => 'nullable|string|max:100',
            'bidang_studi_diajar' => 'nullable|string|max:100',
            'bidang_sertifikasi' => 'nullable|string|max:100',
            'serdik' => 'nullable|in:sertifikasi,non-sertifikasi',
            'tmt_tugas' => 'nullable|date',
            'kgb' => 'nullable|date',
            'pendidikan' => 'nullable|string|max:20',
            'jurusan' => 'nullable|string|max:100',
            'fakultas' => 'nullable|string|max:100',
            'universitas' => 'nullable|string|max:100',
            'tahun_lulus' => 'nullable|string|max:10',
            'email' => 'nullable|email|max:255',
            'telp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
        ]);

        $data = [
            'madrasah_id' => $madrasahId,
            'dept_id' => $deptId, // Keep for backward compatibility
            'created_by' => $user->id,
            'nama' => $validated['nama'],
            'kat_jabatan' => $validated['kat_jabatan'],
            'status' => $validated['status'],
            'nomor_induk' => $validated['nomor_induk'] ?? null,
            'nik' => $validated['nik'] ?? null,
            'npwp' => $validated['npwp'] ?? null,
            'nuptk' => $validated['nuptk'] ?? null,
            'npk' => $validated['npk'] ?? null,
            'nrg' => $validated['nrg'] ?? null,
            'tempat_lahir' => $validated['tempat_lahir'] ?? null,
            'tanggal_lahir' => $validated['tanggal_lahir'] ?? null,
            'jenis_kelamin' => $validated['jenis_kelamin'] ?? null,
            'nama_ibu' => $validated['nama_ibu'] ?? null,
            'golongan' => $validated['golongan'] ?? null,
            'jabatan' => $validated['jabatan'] ?? null,
            'bidang_studi_diajar' => $validated['bidang_studi_diajar'] ?? null,
            'bidang_sertifikasi' => $validated['bidang_sertifikasi'] ?? null,
            'serdik' => $validated['serdik'] ?? null,
            'tmt_tugas' => $validated['tmt_tugas'] ?? null,
            'kgb' => $validated['kgb'] ?? null,
            'pendidikan' => $validated['pendidikan'] ?? null,
            'jurusan' => $validated['jurusan'] ?? null,
            'fakultas' => $validated['fakultas'] ?? null,
            'universitas' => $validated['universitas'] ?? null,
            'tahun_lulus' => $validated['tahun_lulus'] ?? null,
            'email' => $validated['email'] ?? null,
            'telp' => $validated['telp'] ?? null,
            'alamat' => $validated['alamat'] ?? null,
            'is_active' => true,
            'source_table' => 'manual',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // Filter out null values
        $data = array_filter($data, fn($v) => $v !== null);

        DB::table('tenaga_ktd')->insert($data);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Guru berhasil ditambahkan']);
        }

        return redirect()->back()->with('success', 'Guru berhasil ditambahkan!');
    }

    /**
     * Update Guru Madrasah.
     */
    public function updateGuruMadrasah(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'id' => 'required|integer',
            'nama' => 'required|string|max:255',
            'kat_jabatan' => 'nullable|in:guru,kepala',
            'status' => 'nullable|in:PNS,PPPK,HONOR',
            'nomor_induk' => 'nullable|string|max:50',
            'nik' => 'nullable|string|max:20',
            'npwp' => 'nullable|string|max:30',
            'nuptk' => 'nullable|string|max:20',
            'npk' => 'nullable|string|max:20',
            'nrg' => 'nullable|string|max:20',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:Pria,Wanita',
            'nama_ibu' => 'nullable|string|max:100',
            'golongan' => 'nullable|string|max:20',
            'jabatan' => 'nullable|string|max:100',
            'bidang_studi_diajar' => 'nullable|string|max:100',
            'bidang_sertifikasi' => 'nullable|string|max:100',
            'serdik' => 'nullable|in:sertifikasi,non-sertifikasi',
            'tmt_tugas' => 'nullable|date',
            'kgb' => 'nullable|date',
            'pendidikan' => 'nullable|string|max:20',
            'jurusan' => 'nullable|string|max:100',
            'fakultas' => 'nullable|string|max:100',
            'universitas' => 'nullable|string|max:100',
            'tahun_lulus' => 'nullable|string|max:10',
            'email' => 'nullable|email|max:255',
            'telp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
        ]);

        $id = $validated['id'];

        // Check if record exists
        $existing = DB::table('tenaga_ktd')->where('id', $id)->first();
        if (!$existing) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
            }
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }

        if ($existing->user_id) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Tidak dapat mengedit data yang sudah terhubung dengan user'], 403);
            }
            return redirect()->back()->with('error', 'Tidak dapat mengedit data yang sudah terhubung dengan user');
        }

        $data = [
            'nama' => $validated['nama'],
            'kat_jabatan' => $validated['kat_jabatan'] ?? $existing->kat_jabatan,
            'status' => $validated['status'] ?? $existing->status,
            'nomor_induk' => $validated['nomor_induk'] ?? null,
            'nik' => $validated['nik'] ?? null,
            'npwp' => $validated['npwp'] ?? null,
            'nuptk' => $validated['nuptk'] ?? null,
            'npk' => $validated['npk'] ?? null,
            'nrg' => $validated['nrg'] ?? null,
            'tempat_lahir' => $validated['tempat_lahir'] ?? null,
            'tanggal_lahir' => $validated['tanggal_lahir'] ?? null,
            'jenis_kelamin' => $validated['jenis_kelamin'] ?? null,
            'nama_ibu' => $validated['nama_ibu'] ?? null,
            'golongan' => $validated['golongan'] ?? null,
            'jabatan' => $validated['jabatan'] ?? null,
            'bidang_studi_diajar' => $validated['bidang_studi_diajar'] ?? null,
            'bidang_sertifikasi' => $validated['bidang_sertifikasi'] ?? null,
            'serdik' => $validated['serdik'] ?? null,
            'tmt_tugas' => $validated['tmt_tugas'] ?? null,
            'kgb' => $validated['kgb'] ?? null,
            'pendidikan' => $validated['pendidikan'] ?? null,
            'jurusan' => $validated['jurusan'] ?? null,
            'fakultas' => $validated['fakultas'] ?? null,
            'universitas' => $validated['universitas'] ?? null,
            'tahun_lulus' => $validated['tahun_lulus'] ?? null,
            'email' => $validated['email'] ?? null,
            'telp' => $validated['telp'] ?? null,
            'alamat' => $validated['alamat'] ?? null,
            'updated_at' => now(),
        ];

        $data = array_filter($data, fn($v) => $v !== null);

        DB::table('tenaga_ktd')->where('id', $id)->update($data);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Guru berhasil diperbarui']);
        }

        return redirect()->back()->with('success', 'Guru berhasil diperbarui!');
    }

    /**
     * Delete Guru Madrasah.
     */
    public function deleteGuruMadrasah(Request $request)
    {
        try {
            $validated = $request->validate([
                'id' => 'required|integer',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'message' => 'Validasi gagal: ' . collect($e->errors())->flatten()->first()], 422);
        }

        $id = $validated['id'];

        $existing = DB::table('tenaga_ktd')->where('id', $id)->first();
        if (!$existing) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
        }

        if ($existing->user_id) {
            return response()->json(['success' => false, 'message' => 'Tidak dapat menghapus data yang sudah terhubung dengan user'], 403);
        }

        DB::table('tenaga_ktd')->where('id', $id)->delete();

        return response()->json(['success' => true, 'message' => 'Guru berhasil dihapus']);
    }

    /**
     * Laporan Semester Madrasah page.
     */
    /**
     * Laporan Semester Madrasah page - uses madrasah_id (preferred) or dept_id (fallback).
     */
    public function laporanSemesterMadrasah(Request $request)
    {
        $user = auth()->user();
        $madrasahId = $user->madrasah_id ?? null;
        $deptId = $user->dept_id ?? null;
        $deptName = 'Madrasah';

        // Auto-create madrasah if needed (same logic as profilMadrasah)
        if (!$madrasahId && $deptId) {
            $dept = DB::table('ktd_department')->where('id', $deptId)->first();
            $kategoriLower = strtolower($dept->kategori ?? '');
            $isMadrasahCategory = in_array($kategoriLower, ['mi', 'mts', 'ma', 'man', 'mtsn', 'min', 'ra']);
            $isSwastaDept = in_array($deptId, [999, 998]);

            if (($isMadrasahCategory || $isSwastaDept) && !$user->madrasah_id) {
                $defaultNama = $user->satker ?? $dept->nama ?? 'Madrasah Baru';
                $madrasahId = DB::table('ktd_madrasah')->insertGetId([
                    'dept_id' => $deptId,
                    'nama' => $defaultNama,
                    'kategori' => $kategoriLower ?: 'other',
                    'status_lembaga' => $dept->status_lembaga ?? 'Swasta',
                    'status' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                DB::table('users')->where('id', $user->id)->update([
                    'madrasah_id' => $madrasahId,
                    'updated_at' => now(),
                ]);
                $user = auth()->user()->fresh();
            }
        }

        // Get madrasah info (preferred) or fallback to department
        $madrasah = null;
        if ($madrasahId) {
            $madrasah = DB::table('ktd_madrasah')->where('id', $madrasahId)->first();
        } elseif ($deptId) {
            $madrasah = DB::table('ktd_department')->where('id', $deptId)->first();
        }

        if ($madrasah) {
            $deptName = $madrasah->nama ?? 'Madrasah';
        }

        // Get selected semester and tahun ajaran
        $selectedSemester = $request->input('semester', 'genap');
        $tahunAjaran = $request->input('tahun_ajaran', $this->getDefaultAcademicYear());

        // Get existing report if any
        $existingReport = null;
        $latestReport = null;
        if ($madrasahId || $deptId) {
            // Helper closure for building the report query with madrasah_id or dept_id
            $buildReportQuery = function ($query) use ($madrasahId, $deptId) {
                if ($madrasahId) {
                    $query->where('madrasah_id', $madrasahId);
                } else {
                    $query->where('dept_id', $deptId);
                }
                return $query;
            };

            // Check exact match for selected period
            $existingReport = $buildReportQuery(
                DB::table('ktd_laporan_semester_madrasah')
                    ->where('semester', $selectedSemester)
                    ->where('tahun_ajaran', $tahunAjaran)
            )->first();

            // If no exact match, get the latest data from same madrasah (for template)
            if (!$existingReport) {
                $latestReport = $buildReportQuery(
                    DB::table('ktd_laporan_semester_madrasah')
                )->orderBy('created_at', 'desc')->first();
            }
        }

        // Determine which data to use: exact match first, then latest as template
        $sourceReport = $existingReport ?? $latestReport;
        $hasTemplateFromLatest = !$existingReport && $latestReport !== null;

        // Generate academic year options
        $academicYearOptions = $this->generateAcademicYearOptions();

        // Default form data structure
        $formData = [
            'keadaanGedung' => $sourceReport ? json_decode($sourceReport->keadaan_gedung_json ?? '{}', true) : $this->getDefaultKeadaanGedung(),
            'saranaPendidikan' => $sourceReport ? json_decode($sourceReport->sarana_pendidikan_json ?? '{}', true) : $this->getDefaultSaranaPendidikan(),
            'bantuanPemerintah' => $sourceReport ? json_decode($sourceReport->bantuan_pemerintah_json ?? '{}', true) : $this->getDefaultBantuanPemerintah(),
            'bantuanNonPemerintah' => $sourceReport ? json_decode($sourceReport->bantuan_non_pemerintah_json ?? '{}', true) : $this->getDefaultBantuanNonPemerintah(),
            'dataGuruPegawai' => $sourceReport ? json_decode($sourceReport->data_guru_pegawai_json ?? '{}', true) : $this->getDefaultDataGuruPegawai(),
            'tingkatPendidikan' => $sourceReport ? json_decode($sourceReport->tingkat_pendidikan_json ?? '{}', true) : $this->getDefaultTingkatPendidikan(),
            'sertifikasi' => $sourceReport ? json_decode($sourceReport->sertifikasi_json ?? '{}', true) : $this->getDefaultSertifikasi(),
            'banyakHariSekolah' => $sourceReport?->banyak_hari_sekolah ?? '',
            'absensiSiswa' => $sourceReport ? json_decode($sourceReport->absensi_siswa_json ?? '{}', true) : $this->getDefaultAbsensiSiswa(),
            'luasTanah' => $sourceReport ? json_decode($sourceReport->luas_tanah_json ?? '{}', true) : $this->getDefaultLuasTanah(),
            'sertifikatTanah' => $sourceReport ? json_decode($sourceReport->sertifikat_tanah_json ?? '{}', true) : $this->getDefaultSertifikatTanah(),
        ];

        $reportStatus = $existingReport?->status;
        $hasExistingData = $existingReport !== null;
        $submittedAt = $existingReport?->submitted_at;
        $catatanAdmin = $existingReport?->catatan_admin;
        $templateInfo = $hasTemplateFromLatest ? "Data template dari {$latestReport->semester} TA {$latestReport->tahun_ajaran}" : null;
        $formattedSubmittedAt = $submittedAt ? \Carbon\Carbon::parse($submittedAt)->timezone('Asia/Jakarta')->format('d M Y, H:i') : null;

        return view('madrasah.laporansemester', [
            'deptName' => $deptName,
            'deptId' => $deptId,
            'selectedSemester' => $selectedSemester,
            'tahunAjaran' => $tahunAjaran,
            'academicYearOptions' => $academicYearOptions,
            'formData' => $formData,
            'reportStatus' => $reportStatus,
            'hasExistingData' => $hasExistingData,
            'hasTemplateFromLatest' => $hasTemplateFromLatest,
            'templateInfo' => $templateInfo,
            'submittedAt' => $submittedAt,
            'formattedSubmittedAt' => $formattedSubmittedAt,
            'catatanAdmin' => $catatanAdmin,
        ]);
    }

    /**
     * Get default academic year (e.g., 2025/2026).
     */
    private function getDefaultAcademicYear(): string
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;
        $startYear = $currentMonth >= 7 ? $currentYear : $currentYear - 1;
        return $startYear . '/' . ($startYear + 1);
    }

    /**
     * Generate academic year options.
     */
    private function generateAcademicYearOptions(): array
    {
        $defaultYear = $this->getDefaultAcademicYear();
        [$startYear] = explode('/', $defaultYear);
        $startYear = (int) $startYear;
        $options = [];

        for ($year = $startYear - 2; $year <= $startYear + 2; $year++) {
            $options[] = ($year) . '/' . ($year + 1);
        }

        return $options;
    }

    /**
     * Get default keadaan gedung data.
     */
    private function getDefaultKeadaanGedung(): array
    {
        $labels = [
            'Ruang Kelas', 'Ruang Kamad', 'Ruang Guru', 'Ruang TU',
            'Ruang Lab. IPA', 'Ruang Lab. Komputer', 'Ruang Perpustakaan',
            'Ruang Keterampilan', 'Ruang Seni', 'Ruang UKS', 'Aula',
            'Musholla / Ibadah', 'WC', 'Kamar Mandi', 'Kantin'
        ];

        return array_map(fn($label) => [
            'label' => $label,
            'baik' => '',
            'ringan' => '',
            'sedang' => '',
            'berat' => '',
            'isCustom' => false,
        ], $labels);
    }

    /**
     * Get default sarana pendidikan data.
     */
    private function getDefaultSaranaPendidikan(): array
    {
        $labels = [
            'Bangku Uk. 1 Siswa', 'Bangku Uk. 2 Siswa', 'Kursi Siswa',
            'Lemari', 'Rak Buku', 'Papan Tulis', 'Komputer Kantor',
            'Komputer Siswa', 'Alat Peraga', 'PKn', 'Bahasa Indonesia',
            'Matematika', 'IPA', 'IPS', 'Atlas', 'Globe'
        ];

        return array_map(fn($label) => [
            'label' => $label,
            'baik' => '',
            'ringan' => '',
            'sedang' => '',
            'berat' => '',
            'isCustom' => false,
        ], $labels);
    }

    /**
     * Get default bantuan pemerintah data.
     */
    private function getDefaultBantuanPemerintah(): array
    {
        return array_map(fn($label) => [
            'label' => $label,
            'diterima' => '',
            'terserap' => '',
            'isCustom' => false,
        ], ['BOS', 'BSM', 'Block Grant']);
    }

    /**
     * Get default bantuan non pemerintah data.
     */
    private function getDefaultBantuanNonPemerintah(): array
    {
        return array_map(fn($label) => [
            'label' => $label,
            'diterima' => '',
            'terserap' => '',
            'isCustom' => false,
        ], ['Sumbangan']);
    }

    /**
     * Get default data guru pegawai.
     */
    private function getDefaultDataGuruPegawai(): array
    {
        $labels = [
            'Kepala Madrasah', 'Wakil Kepala Madrasah', 'Guru Mapel Umum',
            'Guru Penjaskes', 'Guru Agama', 'Guru BK', 'Guru B. Inggris',
            'Ka TU', 'Staf TU', 'Bendahara', 'Personel Lainnya'
        ];

        return array_map(fn($label) => [
            'label' => $label,
            'l' => '',
            'p' => '',
            'isCustom' => false,
        ], $labels);
    }

    /**
     * Get default tingkat pendidikan.
     */
    private function getDefaultTingkatPendidikan(): array
    {
        $labels = ['< SLTA', 'Diploma I (D1)', 'Diploma II (D2)', 'Diploma III (D3)', 'Strata I (S1)', 'Strata II (S2)', 'Strata III (S3)'];

        return array_map(fn($label) => [
            'label' => $label,
            'l' => '',
            'p' => '',
            'isCustom' => false,
        ], $labels);
    }

    /**
     * Get default sertifikasi.
     */
    private function getDefaultSertifikasi(): array
    {
        $labels = ['PNS Kemenag', 'PNS Diknas', 'GTT / GTY', 'PPPK', 'PPPK Paruh Waktu', 'Belum Sertifikasi'];

        return array_map(fn($label) => [
            'label' => $label,
            'l' => '',
            'p' => '',
            'isCustom' => false,
        ], $labels);
    }

    /**
     * Get default absensi siswa.
     */
    private function getDefaultAbsensiSiswa(): array
    {
        $labels = ['Sakit', 'Ijin', 'Alpa / Tanpa Keterangan'];

        return array_map(fn($label) => [
            'label' => $label,
            'l' => '',
            'p' => '',
            'isCustom' => false,
        ], $labels);
    }

    /**
     * Get default luas tanah.
     */
    private function getDefaultLuasTanah(): array
    {
        return [
            'total' => '',
            'perkaranganLuas' => '',
            'perkaranganTerbangun' => '',
            'kebun' => '',
            'lapanganOlahraga' => '',
            'masjidMusholla' => '',
            'wc' => '',
            'perpustakaan' => '',
        ];
    }

    /**
     * Get default sertifikat tanah.
     */
    private function getDefaultSertifikatTanah(): array
    {
        return [
            'statusKepemilikan' => '',
            'nomor' => '',
            'tanggal' => '',
            'luas' => '',
        ];
    }

    /**
     * Save Laporan Semester Madrasah.
     * Uses madrasah_id from user (preferred) or dept_id (fallback).
     */
    public function saveLaporanSemesterMadrasah(Request $request)
    {
        $user = auth()->user();
        $madrasahId = $user->madrasah_id ?? null;
        $deptId = $user->dept_id ?? $request->input('dept_id');

        if (!$madrasahId && !$deptId) {
            return redirect()->back()->with('error', 'Unit kerja tidak ditemukan');
        }

        // Normalize semester (accept both uppercase and lowercase)
        $semesterInput = strtolower($request->input('semester', 'ganjil'));
        $semester = in_array($semesterInput, ['ganjil', 'genap']) ? $semesterInput : 'ganjil';

        $validated = $request->validate([
            'semester' => 'required|string|in:ganjil,genap,Ganjil,Genap',
            'tahun_ajaran' => 'required|string',
        ]);

        $action = $request->input('action', 'draft');
        $status = $action === 'submit' ? 'submitted' : 'draft';

        $data = [
            'madrasah_id' => $madrasahId,
            'dept_id' => $deptId, // Keep for backward compatibility
            'semester' => $semester,
            'tahun_ajaran' => $validated['tahun_ajaran'],
            'status' => $status,
            'keadaan_gedung_json' => json_encode($request->input('keadaanGedung', [])),
            'sarana_pendidikan_json' => json_encode($request->input('saranaPendidikan', [])),
            'bantuan_pemerintah_json' => json_encode($request->input('bantuanPemerintah', [])),
            'bantuan_non_pemerintah_json' => json_encode($request->input('bantuanNonPemerintah', [])),
            'data_guru_pegawai_json' => json_encode($request->input('dataGuruPegawai', [])),
            'tingkat_pendidikan_json' => json_encode($request->input('tingkatPendidikan', [])),
            'sertifikasi_json' => json_encode($request->input('sertifikasi', [])),
            'banyak_hari_sekolah' => $request->input('banyakHariSekolah', 0),
            'absensi_siswa_json' => json_encode($request->input('absensiSiswa', [])),
            'luas_tanah_json' => json_encode($request->input('luasTanah', [])),
            'sertifikat_tanah_json' => json_encode($request->input('sertifikatTanah', [])),
        ];

        if ($status === 'submitted') {
            $data['submitted_at'] = now();
        }

        // Helper closure for building the report query with madrasah_id or dept_id
        $buildReportQuery = function ($query) use ($madrasahId, $deptId) {
            if ($madrasahId) {
                $query->where('madrasah_id', $madrasahId);
            } else {
                $query->where('dept_id', $deptId);
            }
            return $query;
        };

        // Check if record exists
        $existing = $buildReportQuery(
            DB::table('ktd_laporan_semester_madrasah')
                ->where('semester', $semester)
                ->where('tahun_ajaran', $validated['tahun_ajaran'])
        )->first();

        if ($existing) {
            $data['updated_at'] = now();
            DB::table('ktd_laporan_semester_madrasah')
                ->where('id', $existing->id)
                ->update($data);
        } else {
            $data['created_at'] = now();
            $data['updated_at'] = now();
            DB::table('ktd_laporan_semester_madrasah')->insert($data);
        }

        $message = $status === 'submitted'
            ? 'Laporan semester berhasil dikirim!'
            : 'Draft laporan semester berhasil disimpan!';

        return redirect()->back()->with('success', $message);
    }

    /**
     * Laporan Bulanan Madrasah page - uses madrasah_id (preferred) or dept_id (fallback).
     */
    public function laporanBulananMadrasah(Request $request)
    {
        $user = auth()->user();
        $madrasahId = $user->madrasah_id ?? null;
        $deptId = $user->dept_id ?? null;
        $deptName = 'Madrasah';
        $kategori = 'min';

        // Auto-create madrasah if needed (same logic as profilMadrasah)
        if (!$madrasahId && $deptId) {
            $dept = DB::table('ktd_department')->where('id', $deptId)->first();
            $kategoriLower = strtolower($dept->kategori ?? '');
            $isMadrasahCategory = in_array($kategoriLower, ['mi', 'mts', 'ma', 'man', 'mtsn', 'min', 'ra']);
            $isSwastaDept = in_array($deptId, [999, 998]);

            if (($isMadrasahCategory || $isSwastaDept) && !$user->madrasah_id) {
                $defaultNama = $user->satker ?? $dept->nama ?? 'Madrasah Baru';
                $madrasahId = DB::table('ktd_madrasah')->insertGetId([
                    'dept_id' => $deptId,
                    'nama' => $defaultNama,
                    'kategori' => $kategoriLower ?: 'other',
                    'status_lembaga' => $dept->status_lembaga ?? 'Swasta',
                    'status' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                DB::table('users')->where('id', $user->id)->update([
                    'madrasah_id' => $madrasahId,
                    'updated_at' => now(),
                ]);
                $user = auth()->user()->fresh();
            }
        }

        // Get madrasah info (preferred) or fallback to department
        $madrasah = null;
        if ($madrasahId) {
            $madrasah = DB::table('ktd_madrasah')->where('id', $madrasahId)->first();
        } elseif ($deptId) {
            $madrasah = DB::table('ktd_department')->where('id', $deptId)->first();
        }

        if ($madrasah) {
            $deptName = $madrasah->nama ?? 'Madrasah';
            $kategori = strtolower($madrasah->kategori ?? 'min');
        }

        // Get form parameters
        $bulanIndonesia = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $currentMonthIndex = (int)now()->format('n') - 1;
        $bulanLaporan = $request->input('bulan', $bulanIndonesia[$currentMonthIndex]);
        $tahunLaporan = $request->input('tahun', (string)now()->year);
        $tahunAjaran = $request->input('tahun_ajaran', $this->getDefaultAcademicYear());
        $semester = $request->input('semester', now()->month >= 7 ? 'Ganjil' : 'Genap');

        // Check if user selected a period (form submitted)
        $isUserSelection = $request->has('bulan') || $request->has('tahun') || $request->has('tahun_ajaran') || $request->has('semester');

        // Helper closure for building the report query with madrasah_id or dept_id
        $buildReportQuery = function ($query) use ($madrasahId, $deptId) {
            if ($madrasahId) {
                $query->where('madrasah_id', $madrasahId);
            } else {
                $query->where('dept_id', $deptId);
            }
            return $query;
        };

        // Get existing report for selected period
        $existingReport = null;
        if ($madrasahId || $deptId) {
            $semesterLower = strtolower($semester);
            $existingReport = $buildReportQuery(
                DB::table('ktd_laporan_bulanan_madrasah')
                    ->where('bulan_laporan', $bulanLaporan)
                    ->where('tahun_laporan', $tahunLaporan)
                    ->where('tahun_ajaran', $tahunAjaran)
                    ->where('semester', $semesterLower)
            )->first();
        }

        // If no data for selected period and user selected a period, get latest data
        $studentCounts = [];
        $hasExistingData = $existingReport !== null;
        $reportStatus = null;
        $submittedAt = null;
        $adminNote = null;
        $templateInfo = null;

        if (!$existingReport && $isUserSelection && ($madrasahId || $deptId)) {
            // Get the latest report for student counts (ignore mutation rows)
            $latestReport = $buildReportQuery(
                DB::table('ktd_laporan_bulanan_madrasah')
            )->orderBy('tahun_laporan', 'desc')
                ->orderByRaw("FIELD(bulan_laporan, 'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember') desc")
                ->first();

            if ($latestReport) {
                $studentCounts = json_decode($latestReport->student_counts_json ?? '{}', true);
                $templateInfo = "data dari {$latestReport->bulan_laporan} {$latestReport->tahun_laporan} (Semester {$latestReport->semester} TA {$latestReport->tahun_ajaran})";
            }
        } elseif ($existingReport) {
            // Has data for selected period
            $studentCounts = json_decode($existingReport->student_counts_json ?? '{}', true);
            $reportStatus = $existingReport->status;
            $submittedAt = $existingReport->submitted_at;
            $adminNote = $existingReport->catatan_admin;
        }

        // Mutation rows always empty when no exact match (per requirements)
        $mutationRows = $existingReport ? json_decode($existingReport->mutation_rows_json ?? '[]', true) : [];

        // Status labels
        $statusLabels = [
            'draft' => 'Draft',
            'submitted' => 'Sudah Dikirim',
            'revisi' => 'Perlu Revisi',
            'approved' => 'Disetujui'
        ];

        // Madrasah type label
        $typeLabels = [
            'min' => 'MIN (Madrasah Ibtidaiyah)',
            'mtsn' => 'MTsN (Madrasah Tsanawiyah)',
            'man' => 'MAN (Madrasah Aliyah)',
            'sma' => 'SMA',
            'smk' => 'SMK',
            'other' => 'Madrasah'
        ];
        $madrasahTypeLabel = $typeLabels[$kategori] ?? 'Madrasah';

        // Office name
        $officeName = $user?->dept?->nama ?? 'Kantor Kementerian Agama Kab. Tanah Datar';

        // Count rombel
        $rb = is_array($studentCounts) ? count($studentCounts) : 0;

        // Get class levels based on student counts
        $classLevels = $this->getMadrasahClassLevels($kategori, $studentCounts);

        return view('madrasah.laporanbulanan', [
            'deptName' => $deptName,
            'deptId' => $deptId,
            'kategori' => $kategori,
            'madrasahTypeLabel' => $madrasahTypeLabel,
            'bulan_laporan' => $bulanLaporan,
            'tahun_laporan' => $tahunLaporan,
            'tahun_ajaran' => $tahunAjaran,
            'semester' => $semester,
            'nama_madrasah' => $deptName,
            'rb' => $rb,
            'office_name' => $officeName,
            'studentCounts' => $studentCounts,
            'classLevels' => $classLevels,
            'mutationRows' => $mutationRows,
            'hasExistingData' => $hasExistingData,
            'currentStatus' => $reportStatus,
            'currentStatusLabel' => $hasExistingData ? ($statusLabels[$reportStatus] ?? 'Draft') : null,
            'formattedSubmittedAt' => $submittedAt ? \Carbon\Carbon::parse($submittedAt)->timezone('Asia/Jakarta')->format('d M Y, H:i') : 'Belum dikirim',
            'currentAdminNote' => $adminNote ?? 'Belum ada catatan admin',
            'templateInfo' => $templateInfo,
        ]);
    }

    /**
     * Get class levels based on madrasah category.
     */
    private function getMadrasahClassLevels(string $kategori, array $existingCounts = []): array
    {
        $levels = match($kategori) {
            'min' => [
                ['name' => 'I (Satu)', 'prefix' => 'I', 'classes' => [
                    ['code' => 'I.A'], ['code' => 'I.B'], ['code' => 'I.C']
                ]],
                ['name' => 'II (Dua)', 'prefix' => 'II', 'classes' => [
                    ['code' => 'II.A'], ['code' => 'II.B'], ['code' => 'II.C']
                ]],
                ['name' => 'III (Tiga)', 'prefix' => 'III', 'classes' => [
                    ['code' => 'III.A'], ['code' => 'III.B'], ['code' => 'III.C']
                ]],
                ['name' => 'IV (Empat)', 'prefix' => 'IV', 'classes' => [
                    ['code' => 'IV.A'], ['code' => 'IV.B']
                ]],
                ['name' => 'V (Lima)', 'prefix' => 'V', 'classes' => [
                    ['code' => 'V.A'], ['code' => 'V.B']
                ]],
                ['name' => 'VI (Enam)', 'prefix' => 'VI', 'classes' => [
                    ['code' => 'VI.A'], ['code' => 'VI.B']
                ]],
            ],
            'mtsn' => [
                ['name' => 'VII (Tujuh)', 'prefix' => 'VII', 'classes' => [
                    ['code' => 'VII.A'], ['code' => 'VII.B'], ['code' => 'VII.C']
                ]],
                ['name' => 'VIII (Delapan)', 'prefix' => 'VIII', 'classes' => [
                    ['code' => 'VIII.A'], ['code' => 'VIII.B'], ['code' => 'VIII.C']
                ]],
                ['name' => 'IX (Sembilan)', 'prefix' => 'IX', 'classes' => [
                    ['code' => 'IX.A'], ['code' => 'IX.B'], ['code' => 'IX.C']
                ]],
            ],
            'man' => [
                ['name' => 'X (Sepuluh)', 'prefix' => 'X', 'classes' => [
                    ['code' => 'X.A'], ['code' => 'X.B'], ['code' => 'X.C']
                ]],
                ['name' => 'XI (Sebelas)', 'prefix' => 'XI', 'classes' => [
                    ['code' => 'XI.A'], ['code' => 'XI.B'], ['code' => 'XI.C']
                ]],
                ['name' => 'XII (Dua Belas)', 'prefix' => 'XII', 'classes' => [
                    ['code' => 'XII.A'], ['code' => 'XII.B'], ['code' => 'XII.C']
                ]],
            ],
            default => [
                ['name' => 'Kelas I', 'prefix' => 'I', 'classes' => [
                    ['code' => 'I.A'], ['code' => 'I.B'], ['code' => 'I.C']
                ]],
                ['name' => 'Kelas II', 'prefix' => 'II', 'classes' => [
                    ['code' => 'II.A'], ['code' => 'II.B'], ['code' => 'II.C']
                ]],
                ['name' => 'Kelas III', 'prefix' => 'III', 'classes' => [
                    ['code' => 'III.A'], ['code' => 'III.B'], ['code' => 'III.C']
                ]],
            ],
        };

        // Merge with existing counts to preserve any custom rombel
        if (!empty($existingCounts)) {
            foreach ($levels as &$level) {
                foreach ($level['classes'] as &$class) {
                    $code = $class['code'];
                    if (isset($existingCounts[$code])) {
                        $class['l'] = $existingCounts[$code]['l'] ?? 0;
                        $class['p'] = $existingCounts[$code]['p'] ?? 0;
                    }
                }
            }
        }

        return $levels;
    }

    /**
     * Save Laporan Bulanan Madrasah.
     * Uses madrasah_id from user (preferred) or dept_id (fallback).
     */
    public function saveLaporanBulananMadrasah(Request $request)
    {
        $user = auth()->user();
        $madrasahId = $user->madrasah_id ?? null;
        $deptId = $user->dept_id ?? $request->input('dept_id');

        if (!$madrasahId && !$deptId) {
            return redirect()->back()->with('error', 'Dept ID atau Madrasah ID diperlukan');
        }

        $validated = $request->validate([
            'bulan_laporan' => 'required|string',
            'tahun_laporan' => 'required|integer|min:2000|max:2100',
            'tahun_ajaran' => 'required|string',
            'semester' => 'required|in:Ganjil,Genap',
        ]);

        $action = $request->input('action', 'draft');
        $status = $action === 'submit' ? 'submitted' : 'draft';

        // Get madrasah name for snapshot
        $madrasahName = '';
        if ($madrasahId) {
            $madrasahName = DB::table('ktd_madrasah')->where('id', $madrasahId)->value('nama') ?? '';
        } elseif ($deptId) {
            $madrasahName = DB::table('ktd_department')->where('id', $deptId)->value('nama') ?? '';
        }

        $data = [
            'madrasah_id' => $madrasahId,
            'dept_id' => $deptId, // Keep for backward compatibility
            'bulan_laporan' => $validated['bulan_laporan'],
            'tahun_laporan' => $validated['tahun_laporan'],
            'tahun_ajaran' => $validated['tahun_ajaran'],
            'semester' => strtolower($validated['semester']),
            'status' => $status,
            'nama_madrasah_snapshot' => $madrasahName,
            'instansi_snapshot' => 'Kantor Kementerian Agama Kab. Tanah Datar',
            'student_counts_json' => json_encode($request->input('studentCounts', [])),
            'mutation_rows_json' => json_encode($request->input('mutationRows', [])),
            'rb' => count($request->input('studentCounts', [])),
        ];

        if ($status === 'submitted') {
            $data['submitted_at'] = now();
        }

        // Normalize semester for query (lowercase to match DB enum)
        $semesterLower = strtolower($validated['semester']);

        // Helper closure for building the report query with madrasah_id or dept_id
        $buildReportQuery = function ($query) use ($madrasahId, $deptId) {
            if ($madrasahId) {
                $query->where('madrasah_id', $madrasahId);
            } else {
                $query->where('dept_id', $deptId);
            }
            return $query;
        };

        // Check if record exists
        $existing = $buildReportQuery(
            DB::table('ktd_laporan_bulanan_madrasah')
                ->where('bulan_laporan', $validated['bulan_laporan'])
                ->where('tahun_laporan', $validated['tahun_laporan'])
                ->where('tahun_ajaran', $validated['tahun_ajaran'])
                ->where('semester', $semesterLower)
        )->first();

        if ($existing) {
            $data['updated_at'] = now();
            DB::table('ktd_laporan_bulanan_madrasah')
                ->where('id', $existing->id)
                ->update($data);
            $reportId = $existing->id;
        } else {
            $data['created_at'] = now();
            $data['updated_at'] = now();
            $reportId = DB::table('ktd_laporan_bulanan_madrasah')->insertGetId($data);
        }

        $message = $status === 'submitted'
            ? 'Laporan berhasil dikirim!'
            : 'Draft berhasil disimpan!';

        return redirect()->back()->with('success', $message);
    }

    // ═══════════════════════════════════════════════════════════════════════
    // PRESENSI ACARA
    // ═══════════════════════════════════════════════════════════════════════

    // ═══════════════════════════════════════════════════════════════════════
    // PRESENSI ACARA - NIP Based (No Login Required)
    // ═══════════════════════════════════════════════════════════════════════

    /**
     * Halaman input NIP untuk presensi acara
     */
    public function presensiAcaraNip(int $id)
    {
        $acara = DB::table('ktd_acara')->where('id', $id)->first();
        abort_unless($acara, 404);

        // Build OG tags data for social media preview
        $ogTitle = $acara->judul . ' - Presensi Acara';
        $ogDescription = $acara->lokasi . ' | ' .
                         \Carbon\Carbon::parse($acara->tanggal)->format('d M Y') . ', ' .
                         $acara->jam_mulai . ' - ' . $acara->jam_selesei . ' WIB';
        $ogImage = $acara->filename ? asset('storage/acara/' . $acara->filename) : asset('favicon.webp');
        $ogUrl = url('/presensi-acara/' . $id);

        return view('presensi-acara-nip', [
            'acara' => $acara,
            'ogTitle' => $ogTitle,
            'ogDescription' => $ogDescription,
            'ogImage' => $ogImage,
            'ogUrl' => $ogUrl,
        ]);
    }

    /**
     * Submit NIP dan redirect ke halaman presensi
     */
    public function presensiAcaraNipSubmit(int $id, Request $request)
    {
        $request->validate([
            'nomor_induk' => 'required|numeric',
        ]);

        $nomorInduk = $request->nomor_induk;

        // Check if user exists
        $user = DB::table('users')
            ->where('nomor_induk', $nomorInduk)
            ->first();

        if (!$user) {
            return back()->with('error', 'NIP tidak ditemukan dalam sistem');
        }

        // Store nomor_induk in session
        session(['nomor_induk' => $nomorInduk]);

        // Redirect to presensi page
        return redirect()->route('presensi-acara.show', $id);
    }

    /**
     * Halaman presensi acara (with NIP from session)
     */
    public function presensiAcara(int $id)
    {
        $nomorInduk = session('nomor_induk');
        if (!$nomorInduk) {
            return redirect()->route('presensi-acara.input', $id);
        }

        $acara = DB::table('ktd_acara')->where('id', $id)->first();
        abort_unless($acara, 404);

        // Get user info
        $user = DB::table('users')->where('nomor_induk', $nomorInduk)->first();
        $userName = $user ? $user->name : '-';
        $jabatan = $user ? $user->pekerjaan : null;
        $userPhoto = $user ? $user->pp : null;

        // Get unit kerja name
        $unitKerja = null;
        if ($user && $user->dept_id) {
            $dept = DB::table('ktd_department')->where('id', $user->dept_id)->first();
            $unitKerja = $dept ? $dept->nama : null;
        }

        // Check if already has attendance
        $attendance = DB::table('ktd_presensi_acara')
            ->where('acara_id', $id)
            ->where('user_nip', $nomorInduk)
            ->first();

        $sudahPresensi = $attendance ? true : false;
        $statusKehadiran = $attendance ? $attendance->status : null;
        $keterangan = $attendance ? $attendance->keterangan : null;

        // Build OG tags data for social media preview
        $ogTitle = $acara->judul . ' - Presensi Acara';
        $ogDescription = $userName . ' | ' .
                         \Carbon\Carbon::parse($acara->tanggal)->format('d M Y') . ', ' .
                         $acara->jam_mulai . ' - ' . $acara->jam_selesei . ' WIB';
        $ogImage = $acara->filename ? asset('storage/acara/' . $acara->filename) : asset('favicon.webp');
        $ogUrl = url('/presensi-acara/' . $id . '/show');

        return view('presensi-acara', [
            'acara' => $acara,
            'nomorInduk' => $nomorInduk,
            'userName' => $userName,
            'jabatan' => $jabatan,
            'unitKerja' => $unitKerja,
            'userPhoto' => $userPhoto,
            'sudahPresensi' => $sudahPresensi,
            'statusKehadiran' => $statusKehadiran,
            'keterangan' => $keterangan,
            'ogTitle' => $ogTitle,
            'ogDescription' => $ogDescription,
            'ogImage' => $ogImage,
            'ogUrl' => $ogUrl,
        ]);
    }

    /**
     * Submit presensi hadir
     */
    public function presensiAcaraHadir(int $id, Request $request)
    {
        $request->validate([
            'nomor_induk' => 'required|numeric',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'foto' => 'nullable|string',
        ]);

        $nomorInduk = $request->nomor_induk;
        $acara = DB::table('ktd_acara')->where('id', $id)->first();
        abort_unless($acara, 404);

        // Save photo if provided
        $fotoPath = null;
        if ($request->has('foto') && $request->foto) {
            $fotoPath = $this->saveAcaraPhoto($request->foto, $id, $nomorInduk);
        }

        // Calculate distance if acara has location
        $distance = null;
        if ($acara->latitude && $acara->longitude && $acara->radius && $acara->radius > 0
            && $request->latitude && $request->longitude
            && $request->latitude != 0 && $request->longitude != 0) {
            $distance = $this->calculateDistance(
                $acara->latitude, $acara->longitude,
                $request->latitude, $request->longitude
            );
        }

        // Check if already has attendance
        $existingAttendance = DB::table('ktd_presensi_acara')
            ->where('acara_id', $id)
            ->where('user_nip', $nomorInduk)
            ->first();

        if ($existingAttendance) {
            DB::table('ktd_presensi_acara')
                ->where('id', $existingAttendance->id)
                ->update([
                    'status' => 'hadir',
                    'keterangan' => null,
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                    'distance' => $distance,
                    'foto' => $fotoPath,
                    'tanggal' => Carbon::now('Asia/Jakarta')->format('Y-m-d'),
                    'waktu_absen' => Carbon::now('Asia/Jakarta')->format('H:i:s'),
                    'updated_at' => now(),
                ]);
        } else {
            DB::table('ktd_presensi_acara')->insert([
                'acara_id' => $id,
                'user_nip' => $nomorInduk,
                'status' => 'hadir',
                'keterangan' => null,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'distance' => $distance,
                'foto' => $fotoPath,
                'tanggal' => Carbon::now('Asia/Jakarta')->format('Y-m-d'),
                'waktu_absen' => Carbon::now('Asia/Jakarta')->format('H:i:s'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return redirect()->route('presensi-acara.show', $id)
            ->with('success', 'Presensi berhasil!')
            ->with('nomor_induk', $nomorInduk);
    }

    /**
     * Submit presensi tidak hadir
     */
    public function presensiAcaraTidakHadir(int $id, Request $request)
    {
        $request->validate([
            'nomor_induk' => 'required|numeric',
            'keterangan' => 'required|string|max:500',
        ]);

        $nomorInduk = $request->nomor_induk;
        $acara = DB::table('ktd_acara')->where('id', $id)->first();
        abort_unless($acara, 404);

        // Check if already has attendance
        $existingAttendance = DB::table('ktd_presensi_acara')
            ->where('acara_id', $id)
            ->where('user_nip', $nomorInduk)
            ->first();

        if ($existingAttendance) {
            DB::table('ktd_presensi_acara')
                ->where('id', $existingAttendance->id)
                ->update([
                    'status' => 'tidak_hadir',
                    'keterangan' => $request->keterangan,
                    'updated_at' => now(),
                ]);
        } else {
            DB::table('ktd_presensi_acara')->insert([
                'acara_id' => $id,
                'user_nip' => $nomorInduk,
                'status' => 'tidak_hadir',
                'keterangan' => $request->keterangan,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return redirect()->route('presensi-acara.show', $id)
            ->with('success', 'Keterangan berhasil dikirim!')
            ->with('nomor_induk', $nomorInduk);
    }

    /**
     * Save acara photo
     */
    private function saveAcaraPhoto(string $base64Photo, int $acaraId, string $userNip): ?string
    {
        try {
            if (str_contains($base64Photo, 'base64,')) {
                $base64Photo = explode('base64,', $base64Photo)[1];
            }

            $imageData = base64_decode($base64Photo);
            $filename = 'presensi_acara_' . $acaraId . '_' . $userNip . '_' . time() . '.jpg';
            $path = 'presensi_acara/' . $filename;

            Storage::disk('public')->put($path, $imageData);

            return $path;
        } catch (\Exception $e) {
            Log::error('Failed to save acara photo', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Calculate distance between two coordinates (Haversine formula)
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2): float
    {
        $earthRadius = 6371000; // meters

        $lat1 = deg2rad($lat1);
        $lon1 = deg2rad($lon1);
        $lat2 = deg2rad($lat2);
        $lon2 = deg2rad($lon2);

        $dLat = $lat2 - $lat1;
        $dLon = $lon2 - $lon1;

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos($lat1) * cos($lat2) * sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    /**
     * Calculate distance from user to office (department)
     */
    private function calculateDistanceFromOffice(?int $deptId, float $userLat, float $userLon): ?float
    {
        // Jika koordinat user tidak valid, return null
        if ($userLat == 0 && $userLon == 0) {
            return null;
        }

        // Ambil data department
        $dept = DB::table('ktd_department')->where('id', $deptId)->first();
        if (!$dept) {
            return null;
        }

        // Cek apakah department memiliki koordinat
        if (empty($dept->latitude) || empty($dept->longitude)) {
            return null;
        }

        $officeLat = (float) $dept->latitude;
        $officeLon = (float) $dept->longitude;

        // Validasi koordinat office
        if ($officeLat == 0 && $officeLon == 0) {
            return null;
        }

        return $this->calculateDistance($officeLat, $officeLon, $userLat, $userLon);
    }

    // ═══════════════════════════════════════════════════════════════════════
    // PRESENSI ERROR - Alternatif presensi ketika sistem error
    // ═══════════════════════════════════════════════════════════════════════

    /**
     * Halaman presensi error (user harus login)
     */
    public function presensiError()
    {
        $user = auth()->user();
        $today = Carbon::now('Asia/Jakarta')->toDateString();

        // Cek apakah sudah presensi hari ini
        $presensi = DB::table('ktd_presensi')
            ->where('user_nip', $user->nomor_induk)
            ->whereDate('tanggal', $today)
            ->first();

        // Ambil nama unit kerja
        $unitKerja = null;
        if ($user->dept_id) {
            $dept = DB::table('ktd_department')->where('id', $user->dept_id)->first();
            $unitKerja = $dept ? $dept->nama : null;
        }

        return view('presensi-error', [
            'user' => $user,
            'unitKerja' => $unitKerja,
            'presensi' => $presensi,
        ]);
    }

    /**
     * Submit presensi error (masuk/pulang)
     */
    public function presensiErrorSubmit(Request $request)
    {
        $request->validate([
            'jenis' => 'required|in:masuk,pulang',
            'alasan' => 'required|in:SISTEM_ERROR,TUGAS_LUAR',
            'keterangan_tugas_luar' => 'required_if:alasan,TUGAS_LUAR|nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'jarak_meter' => 'nullable|numeric',
            'alamat' => 'nullable|string',
            'foto' => 'required|string',
        ]);

        $user = auth()->user();
        $jenis = $request->input('jenis');
        $alasan = $request->input('alasan');
        $now = Carbon::now('Asia/Jakarta');
        $today = $now->toDateString();
        $jamActual = $now->format('H:i:s');

        // Waktu tetap untuk presensi error
        $jamMasuk = '05:59:00';
        $jamPulang = '19:59:00';

        // Tentukan status berdasarkan alasan
        $status = $alasan === 'SISTEM_ERROR' ? 'SISTEM_ERROR' : 'TUGAS_LUAR';

        // Tentukan keterangan
        $keterangan = $alasan === 'TUGAS_LUAR'
            ? $request->input('keterangan_tugas_luar', 'Tugas Luar')
            : 'Dilaporkan melalui halaman Presensi Error (Sistem Error)';

        // Simpan foto
        $fotoPath = $this->saveErrorPresensiPhoto($request->foto, $user->nomor_induk);
        if (!$fotoPath) {
            return back()->with('error', 'Gagal menyimpan foto. Silakan coba lagi.');
        }

        // Cek apakah sudah ada record presensi hari ini
        $presensi = DB::table('ktd_presensi')
            ->where('user_nip', $user->nomor_induk)
            ->whereDate('tanggal', $today)
            ->first();

        $dataUpdate = [
            'status' => $status,
            'keterangan' => $keterangan,
            'updated_at' => now(),
        ];

        // Simpan data atasan manual untuk dept 998/999
        $specialDeptIds = [998, 999];
        if (in_array((int) $user->dept_id, $specialDeptIds)) {
            $dataUpdate['manual_supervisor_name'] = $request->input('supervisor_name', '');
            $dataUpdate['manual_supervisor_nip'] = $request->input('supervisor_nip', '');
            $dataUpdate['manual_unit_kerja'] = $request->input('unit_kerja_manual', '');
        }

        if ($jenis === 'masuk') {
            // Validasi belum presensi masuk
            if ($presensi && $presensi->m_absen) {
                return back()->with('error', 'Presensi masuk hari ini sudah dilakukan');
            }

            $dataUpdate['m_absen'] = $jamMasuk;
            $dataUpdate['m_latitude'] = $request->input('latitude', 0);
            $dataUpdate['m_longitude'] = $request->input('longitude', 0);
            $dataUpdate['m_location'] = $fotoPath;
            $dataUpdate['m_alamat'] = $request->input('alamat', '');
            $dataUpdate['error_masuk_taken_at'] = $jamActual;

            // Hitung jarak dari kantor
            $distance = $this->calculateDistanceFromOffice(
                $user->dept_id,
                $request->input('latitude', 0),
                $request->input('longitude', 0)
            );
            $dataUpdate['m_distance'] = $distance ?? $request->input('jarak_meter', 0);
        } else {
            // Validasi belum presensi pulang
            if ($presensi && $presensi->p_absen) {
                return back()->with('error', 'Presensi pulang hari ini sudah dilakukan');
            }

            $dataUpdate['p_absen'] = $jamPulang;
            $dataUpdate['p_latitude'] = $request->input('latitude', 0);
            $dataUpdate['p_longitude'] = $request->input('longitude', 0);
            $dataUpdate['p_location'] = $fotoPath;
            $dataUpdate['p_alamat'] = $request->input('alamat', '');
            $dataUpdate['error_pulang_taken_at'] = $jamActual;

            // Hitung jarak dari kantor
            $distance = $this->calculateDistanceFromOffice(
                $user->dept_id,
                $request->input('latitude', 0),
                $request->input('longitude', 0)
            );
            $dataUpdate['p_distance'] = $distance ?? $request->input('jarak_meter', 0);
        }

        try {
            $presensiId = null;
            if ($presensi) {
                DB::table('ktd_presensi')
                    ->where('id', $presensi->id)
                    ->update($dataUpdate);
                $presensiId = $presensi->id;
            } else {
                $dataInsert = array_merge($dataUpdate, [
                    'user_nip' => $user->nomor_induk,
                    'dept_id' => $user->dept_id,
                    'tanggal' => $today,
                    'created_at' => now(),
                ]);
                $presensiId = DB::table('ktd_presensi')->insertGetId($dataInsert);
            }

            // Redirect ke halaman presensi error dengan sukses + buka surat di tab baru
            $suratUrl = route('presensi-error.surat', ['id' => $presensiId, 'jenis' => $jenis]);
            return redirect()->route('presensi-error')
                ->with('success', "Presensi {$jenis} berhasil dilaporkan!")
                ->with('suratUrl', $suratUrl);
        } catch (\Exception $e) {
            \Log::error('Failed to save error presensi', ['error' => $e->getMessage()]);
            return back()->with('error', 'Gagal menyimpan data presensi: ' . $e->getMessage());
        }
    }

    /**
     * Simpan foto presensi error ke storage
     */
    private function saveErrorPresensiPhoto(string $base64Photo, string $userNip): ?string
    {
        try {
            if (str_contains($base64Photo, 'base64,')) {
                $base64Photo = explode('base64,', $base64Photo)[1];
            }

            $imageData = base64_decode($base64Photo);
            $filename = 'presensi_error_' . $userNip . '_' . time() . '.jpg';
            $path = 'presensi_error/' . $filename;

            Storage::disk('public')->put($path, $imageData);

            return $path;
        } catch (\Exception $e) {
            \Log::error('Failed to save error presensi photo', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Generate Surat Keterangan Presensi Error (PDF)
     */
    public function suratKeteranganPresensiError(int $id, string $jenis)
    {
        $presensi = DB::table('ktd_presensi')->where('id', $id)->first();
        abort_unless($presensi, 404);

        $user = auth()->user();
        abort_unless($user->nomor_induk === $presensi->user_nip, 403);

        // Ambil data user dari tabel users
        $userData = DB::table('users')->where('nomor_induk', $presensi->user_nip)->first();
        abort_unless($userData, 404);

        // Ambil nama unit kerja
        $unitKerja = '-';
        if ($userData->dept_id) {
            $dept = DB::table('ktd_department')->where('id', $userData->dept_id)->first();
            if ($dept) {
                $unitKerja = $dept->nama;
            }
        }

        // ═══════════════════════════════════════════════════════════════════
        // Logic Atasan (sama dengan laporan kinerja)
        // ═══════════════════════════════════════════════════════════════════
        $specialDeptIds = [998, 999];
        $deptId = (int) $userData->dept_id;

        $kepalaNama = '..................................';
        $kepalaNip = '';
        $kepalaSignaturePath = null;
        $isPlh = false;

        // Cek apakah user adalah atasan (kepala, kasi, kasubbag)
        $atasanJabatan = ['kepala', 'kasi', 'kasubbag'];
        $isUserAtasan = in_array($userData->kat_jabatan, $atasanJabatan);

        if (in_array($deptId, $specialDeptIds)) {
            // Dept 998/999: ambil data atasan dari input manual yang disimpan di ktd_presensi
            $kepalaNama = $presensi->manual_supervisor_name ?? '..................................';
            $kepalaNip = $presensi->manual_supervisor_nip ?? '';
            $unitKerja = $presensi->manual_unit_kerja ?? $unitKerja;
        } elseif ($isUserAtasan) {
            // Jika user adalah atasan, penandatangan adalah Kepala Kankemenag
            $kepalaKankemenag = DB::table('users')
                ->where('role', 'kepala')
                ->first();

            if ($kepalaKankemenag) {
                $kepalaNama = $kepalaKankemenag->name;
                $kepalaNip = $kepalaKankemenag->nomor_induk ?? '';

                // Ambil foto tanda tangan
                if ($kepalaKankemenag->pp) {
                    $kepalaCheck = storage_path('app/public/users_berkas/' . $kepalaKankemenag->nomor_induk . '/' . $kepalaKankemenag->pp);
                    if (file_exists($kepalaCheck)) {
                        $kepalaSignaturePath = $kepalaCheck;
                    }
                }
            }
        } else {
            // Cek PLT/PLH di tabel plt_plh
            $pltPlh = DB::table('plt_plh')
                ->where('dept_id_plh', $userData->dept_id)
                ->first();

            if ($pltPlh) {
                // PLT exist - gunakan user PLT
                $pltUser = DB::table('users')->where('id', $pltPlh->user_id)->first();
                if ($pltUser) {
                    $isPlh = true;
                    $kepalaNama = $pltUser->name;
                    $kepalaNip = $pltUser->nomor_induk ?? '';

                    if ($pltUser->pp) {
                        $kepalaCheck = storage_path('app/public/users_berkas/' . $pltUser->nomor_induk . '/' . $pltUser->pp);
                        if (file_exists($kepalaCheck)) {
                            $kepalaSignaturePath = $kepalaCheck;
                        }
                    }
                }
            } else {
                // Cari kepala/kasi/kasubbag berdasarkan dept_id
                $kepala = DB::table('users')
                    ->where('dept_id', $userData->dept_id)
                    ->whereIn('kat_jabatan', $atasanJabatan)
                    ->first();

                if ($kepala) {
                    $kepalaNama = $kepala->name;
                    $kepalaNip = $kepala->nomor_induk ?? '';

                    if ($kepala->pp) {
                        $kepalaCheck = storage_path('app/public/users_berkas/' . $kepala->nomor_induk . '/' . $kepala->pp);
                        if (file_exists($kepalaCheck)) {
                            $kepalaSignaturePath = $kepalaCheck;
                        }
                    }
                }
            }
        }

        // Tentukan label penandatangan
        if ($isUserAtasan) {
            $kepalaLabel = 'Mengetahui<br>Kepala Kankemenag Kab. Tanah Datar,';
        } elseif ($isPlh) {
            $kepalaLabel = 'Mengetahui<br>PLT Kepala,';
        } else {
            $kepalaLabel = "Mengetahui<br>Kepala {$unitKerja},";
        }

        // Format tanggal
        $tanggal = Carbon::parse($presensi->tanggal)->locale('id_ID')->isoFormat('dddd, D MMMM Y');

        // Ambil data berdasarkan jenis presensi
        if ($jenis === 'masuk') {
            $jam = $presensi->m_absen ?? '-';
            $jamActual = $presensi->error_masuk_taken_at ?? '-';
            $latitude = $presensi->m_latitude ?? '-';
            $longitude = $presensi->m_longitude ?? '-';
            $distance = $presensi->m_distance ?? '-';
            $fotoPath = $presensi->m_location ?? null;
            $alamat = $presensi->m_alamat ?? '';
        } else {
            $jam = $presensi->p_absen ?? '-';
            $jamActual = $presensi->error_pulang_taken_at ?? '-';
            $latitude = $presensi->p_latitude ?? '-';
            $longitude = $presensi->p_longitude ?? '-';
            $distance = $presensi->p_distance ?? '-';
            $fotoPath = $presensi->p_location ?? null;
            $alamat = $presensi->p_alamat ?? '';
        }

        // Format jarak
        $distanceFormatted = '-';
        if ($distance && $distance != '-') {
            $distanceInMeters = round((float) $distance);
            if ($distanceInMeters >= 1000) {
                $km = floor($distanceInMeters / 1000);
                $remainingMeters = $distanceInMeters % 1000;
                $distanceFormatted = $km . ' km ' . $remainingMeters . ' meter';
            } else {
                $distanceFormatted = $distanceInMeters . ' meter';
            }
        }

        // Format lokasi
        $lokasi = '-';
        if ($latitude && $longitude && $latitude != '-' && $longitude != '-') {
            $lokasi = $latitude . ', ' . $longitude;
        }

        // Path foto
        $fotoFullPath = null;
        if ($fotoPath) {
            $fotoFullPath = storage_path('app/public/' . $fotoPath);
            if (!file_exists($fotoFullPath)) {
                $fotoFullPath = null;
            }
        }

        // Header image
        $headerPath = public_path('assets/img/template/header.webp');
        $headerExists = file_exists($headerPath);

        // Tanda tangan user
        $userSignaturePath = null;
        if ($userData->pp) {
            $userSignatureCheck = storage_path('app/public/users_berkas/' . $userData->nomor_induk . '/' . $userData->pp);
            if (file_exists($userSignatureCheck)) {
                $userSignaturePath = $userSignatureCheck;
            }
        }

        // Generate nomor surat unik/random
        $randomNumber = strtoupper(substr(uniqid(), -6));
        $nomorSurat = 'SK-PE/' . $randomNumber . '/' . now()->format('m/Y');

        $pdfData = [
            'nomorSurat' => $nomorSurat,
            'tanggal' => $tanggal,
            'nama' => $userData->name,
            'nip' => $userData->nomor_induk,
            'jabatan' => $userData->pekerjaan ?? '-',
            'unitKerja' => $unitKerja,
            'jam' => $jam,
            'jamActual' => $jamActual,
            'jenisPresensi' => $jenis === 'masuk' ? 'Presensi Masuk' : 'Presensi Pulang',
            'alasan' => $presensi->status ?? 'SISTEM_ERROR',
            'lokasi' => $lokasi,
            'alamat' => $alamat,
            'jarak' => $distanceFormatted,
            'keterangan' => $presensi->keterangan ?? 'Dilaporkan melalui Presensi Error',
            'fotoPath' => $fotoFullPath,
            'headerPath' => $headerExists ? $headerPath : null,
            'kepalaLabel' => $kepalaLabel,
            'kepalaNama' => $kepalaNama,
            'kepalaNip' => $kepalaNip,
            'kepalaSignaturePath' => $kepalaSignaturePath,
            'userSignaturePath' => $userSignaturePath,
        ];

        $pdf = Pdf::loadView('pdf.surat-keterangan-presensi-error', $pdfData)
            ->setPaper('a4', 'portrait')
            ->setOption('isRemoteEnabled', true)
            ->setOption('isHtml5ParserEnabled', true);

        return $pdf->stream('surat-keterangan-presensi-error-' . $presensi->user_nip . '.pdf');
    }
}
