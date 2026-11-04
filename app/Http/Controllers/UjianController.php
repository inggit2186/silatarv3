<?php

namespace App\Http\Controllers;

use App\Models\Ujian;
use App\Models\UjianJawaban;
use App\Models\UjianPeserta;
use App\Models\UjianSoal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UjianController extends Controller
{
    private const CACHE_SOAL = 300;

    private const CACHE_HASIL = 60;

    private const MAX_UPLOAD = 5120;

    private const EKSTENSI = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'jpg', 'jpeg', 'png', 'txt', 'zip', 'rar'];

    /**
     * Daftar ujian yang ditugaskan ke user yang sedang login.
     */
    public function index(Request $request)
    {
        $userId = $request->user()->id;

        $peserta = UjianPeserta::where('user_id', $userId)
            ->with(['ujian' => fn ($q) => $q->withCount('soal')])
            ->orderByDesc('id')
            ->get();

        $stats = [
            'total' => $peserta->count(),
            'berjalan' => $peserta->where('status', 'berjalan')->count(),
            'belum_mulai' => $peserta->where('status', 'belum_mulai')->count(),
            'selesai' => $peserta->where('status', 'selesai')->count(),
        ];

        return view('ujian.index', [
            'title' => 'Ujian Pegawai - SILATAR',
            'peserta' => $peserta,
            'stats' => $stats,
        ]);
    }

    /**
     * Halaman pengerjaan. Memulai sesi baru bila belum ada, atau melanjutkan sesi berjalan.
     */
    public function kerjakan(Request $request, int $id)
    {
        $userId = $request->user()->id;

        $ujian = Ujian::find($id);
        abort_unless($ujian, 404);

        $peserta = UjianPeserta::where('ujian_id', $id)->where('user_id', $userId)->first();
        abort_unless($peserta, 404);

        if ($peserta->status === 'selesai') {
            return redirect()->route('ujian.hasil', $id);
        }

        if ($ujian->status !== 'terbit') {
            return redirect()->route('ujian.index')
                ->with('error', 'Ujian ini belum dapat dikerjakan.');
        }

        if ($ujian->mulai_at && now()->lt($ujian->mulai_at)) {
            return redirect()->route('ujian.index')
                ->with('error', 'Ujian ini belum dimulai.');
        }

        $payload = $this->payloadSoal($id);

        if (empty($payload)) {
            return redirect()->route('ujian.index')
                ->with('error', 'Ujian ini belum memiliki soal.');
        }

        if ($peserta->status === 'belum_mulai' || ! $peserta->started_at) {
            $startedAt = now();
            $peserta->update([
                'status' => 'berjalan',
                'started_at' => $startedAt,
                'ends_at' => $startedAt->copy()->addMinutes($ujian->durasi_menit),
            ]);
        }

        // Sesi sudah lewat batas waktu tetapi belum pernah dikirim.
        if ($peserta->ends_at && now()->gte($peserta->ends_at)) {
            $this->tutupSesi($peserta);

            return redirect()->route('ujian.hasil', $id);
        }

        $jawaban = UjianJawaban::where('ujian_id', $id)
            ->where('user_id', $userId)
            ->get(['soal_id', 'pilihan_id', 'esai', 'file_path', 'file_nama', 'skor']);

        $sudahDijawab = [];
        $esai = [];
        $lampiran = [];

        foreach ($jawaban as $j) {
            if ($j->pilihan_id) {
                $sudahDijawab[(int) $j->soal_id] = (int) $j->pilihan_id;
            }
            if ($j->esai !== null && $j->esai !== '') {
                $esai[(int) $j->soal_id] = $j->esai;
            }
            if ($j->file_path) {
                $lampiran[(int) $j->soal_id] = [
                    'nama' => $j->file_nama,
                    'url' => route('ujian.berkas', $j->id),
                ];
            }
        }

        return view('ujian.kerjakan', [
            'title' => 'Ujian: '.$ujian->judul,
            'ujian' => $ujian,
            'peserta' => $peserta,
            'soal' => $this->tanpaKunci($payload),
            'sudahDijawab' => $sudahDijawab,
            'esai' => $esai,
            'lampiran' => $lampiran,
            'remainingSeconds' => max(0, $peserta->ends_at ? now()->diffInSeconds($peserta->ends_at, false) : 0),
        ]);
    }

    /**
     * Auto-save jawaban dalam satu batch.
     */
    public function simpanJawaban(Request $request, int $id)
    {
        $userId = $request->user()->id;

        $jawaban = $request->input('jawaban');
        if (! is_array($jawaban)) {
            return response()->json(['message' => 'Format jawaban tidak valid.'], 422);
        }

        $sesi = $this->cariSesi($request, $id);
        if (! $sesi) {
            return response()->json(['message' => 'Sesi ujian tidak ditemukan.'], 404);
        }

        if ($sesi->status === 'selesai') {
            return response()->json([
                'message' => 'Sesi ujian sudah ditutup.',
                'redirect' => route('ujian.hasil', $id),
            ], 409);
        }

        if ($sesi->ends_at && now()->gte($sesi->ends_at)) {
            $this->tutupSesi($sesi);

            return response()->json([
                'message' => 'Waktu ujian telah habis.',
                'redirect' => route('ujian.hasil', $id),
            ], 409);
        }

        $payload = $this->payloadSoal($id);
        $soalIds = array_column($payload, 'id');
        $pilihanMap = [];
        foreach ($payload as $s) {
            foreach ($s['pilihan'] as $p) {
                $pilihanMap[$p['id']] = (int) $s['id'];
            }
        }

        $now = now();
        $rows = [];

        foreach ($jawaban as $soalId => $nilai) {
            $soalId = (int) $soalId;

            if (! in_array($soalId, $soalIds, true)) {
                continue;
            }

            $isPg = isset($nilai['pilihan_id']) && $nilai['pilihan_id'] !== null && $nilai['pilihan_id'] !== '';
            $isEsai = isset($nilai['esai']) && is_string($nilai['esai']);

            if ($isPg) {
                $pilihanId = (int) $nilai['pilihan_id'];

                // Pilihan harus milik soal tersebut.
                if (($pilihanMap[$pilihanId] ?? null) !== $soalId) {
                    continue;
                }

                $rows[] = [
                    'ujian_id' => $id,
                    'soal_id' => $soalId,
                    'user_id' => $userId,
                    'pilihan_id' => $pilihanId,
                    'esai' => null,
                    'dijawab_at' => $now,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];

                continue;
            }

            if ($isEsai) {
                $rows[] = [
                    'ujian_id' => $id,
                    'soal_id' => $soalId,
                    'user_id' => $userId,
                    'pilihan_id' => null,
                    'esai' => $nilai['esai'],
                    'dijawab_at' => $now,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        if ($rows) {
            DB::table('ujian_jawaban')->upsert(
                $rows,
                ['user_id', 'soal_id'],
                ['pilihan_id', 'esai', 'dijawab_at', 'updated_at']
            );
        }

        return response()->json([
            'saved' => true,
            'saved_at' => $now->format('H:i:s'),
        ]);
    }

    /**
     * Unggah lampiran untuk jawaban esai.
     */
    public function uploadLampiran(Request $request, int $id)
    {
        $userId = $request->user()->id;

        $sesi = $this->cariSesi($request, $id);
        if (! $sesi) {
            return response()->json(['message' => 'Sesi ujian tidak ditemukan.'], 404);
        }

        if ($sesi->status === 'selesai') {
            return response()->json([
                'message' => 'Sesi ujian sudah ditutup.',
                'redirect' => route('ujian.hasil', $id),
            ], 409);
        }

        if ($sesi->ends_at && now()->gte($sesi->ends_at)) {
            $this->tutupSesi($sesi);

            return response()->json([
                'message' => 'Waktu ujian telah habis.',
                'redirect' => route('ujian.hasil', $id),
            ], 409);
        }

        $request->validate([
            'soal_id' => ['required', 'integer'],
            'berkas' => ['required', 'file', 'max:'.self::MAX_UPLOAD],
        ], [
            'berkas.required' => 'Berkas lampiran wajib dipilih.',
            'berkas.max' => 'Ukuran berkas maksimal 5 MB.',
        ]);

        $soalId = (int) $request->input('soal_id');

        $soalIds = array_column($this->payloadSoal($id), 'id');
        if (! in_array($soalId, $soalIds, true)) {
            return response()->json(['message' => 'Soal tidak valid.'], 422);
        }

        $berkas = $request->file('berkas');
        $ekstensi = strtolower($berkas->getClientOriginalExtension());

        if (! in_array($ekstensi, self::EKSTENSI, true)) {
            return response()->json(['message' => 'Format berkas tidak didukung.'], 422);
        }

        $namaAsli = $berkas->getClientOriginalName();
        $path = Storage::disk('public')->storeAs(
            "ujian/{$id}/{$userId}",
            Str::uuid().'.'.$ekstensi,
            'public'
        );

        $jawaban = UjianJawaban::firstOrNew([
            'user_id' => $userId,
            'soal_id' => $soalId,
        ]);

        $jawaban->ujian_id = $id;
        $jawaban->file_path = $path;
        $jawaban->file_nama = $namaAsli;
        $jawaban->dijawab_at = now();
        $jawaban->save();

        return response()->json([
            'saved' => true,
            'nama' => $namaAsli,
            'url' => route('ujian.berkas', $jawaban->id),
        ]);
    }

    /**
     * Kumpulkan jawaban dan tutup sesi.
     */
    public function submit(Request $request, int $id)
    {
        $userId = $request->user()->id;

        $sesi = $this->cariSesi($request, $id);
        if (! $sesi) {
            return response()->json(['message' => 'Sesi ujian tidak ditemukan.'], 404);
        }

        if ($sesi->status === 'selesai') {
            return response()->json([
                'saved' => true,
                'redirect' => route('ujian.hasil', $id),
            ]);
        }

        $this->tutupSesi($sesi);

        return response()->json([
            'saved' => true,
            'redirect' => route('ujian.hasil', $id),
        ]);
    }

    /**
     * Halaman hasil & pembahasan.
     */
    public function hasil(Request $request, int $id)
    {
        $userId = $request->user()->id;

        $ujian = Ujian::find($id);
        abort_unless($ujian, 404);

        $sesi = UjianPeserta::where('ujian_id', $id)->where('user_id', $userId)->first();
        abort_unless($sesi, 404);

        $data = Cache::remember(
            "ujian:{$id}:hasil:{$userId}",
            self::CACHE_HASIL,
            fn () => $this->susunHasil($id, $userId)
        );

        return view('ujian.hasil', [
            'title' => 'Hasil Ujian - SILATAR',
            'ujian' => $ujian,
            'sesi' => $sesi,
            'data' => $data,
        ]);
    }

    /**
     * Unduh lampiran esai. Hanya pemilik berkas atau admin yang boleh.
     */
    public function unduhBerkas(Request $request, int $jawabanId)
    {
        $jawaban = UjianJawaban::find($jawabanId);
        abort_unless($jawaban && $jawaban->file_path, 404);

        $user = $request->user();
        $isAdmin = in_array($user->role, ['petugas', 'kasi', 'kasubbag', 'admin', 'superadmin', 'kepala'], true);

        if ((int) $jawaban->user_id !== (int) $user->id && ! $isAdmin) {
            abort(403);
        }

        if (! Storage::disk('public')->exists($jawaban->file_path)) {
            abort(404);
        }

        return Storage::disk('public')->download($jawaban->file_path, $jawaban->file_nama);
    }

    /**
     * Sesi milik user untuk ujian tertentu.
     */
    private function cariSesi(Request $request, int $id): ?UjianPeserta
    {
        return UjianPeserta::where('ujian_id', $id)
            ->where('user_id', $request->user()->id)
            ->first();
    }

    /**
     * Tutup sesi: hitung skor, simpan nilai, dan kunci peserta.
     */
    private function tutupSesi(UjianPeserta $sesi): void
    {
        if ($sesi->status === 'selesai') {
            return;
        }

        $ujianId = (int) $sesi->ujian_id;
        $userId = (int) $sesi->user_id;

        $payload = $this->payloadSoal($ujianId);

        // Skor pilihan ganda: satu query untuk semua soal, satu query untuk menerapkan skor.
        $cases = [];
        foreach ($payload as $s) {
            foreach ($s['pilihan'] as $p) {
                if (! $p['benar']) {
                    continue;
                }
                $cases[] = "WHEN soal_id = {$s['id']} AND pilihan_id = {$p['id']} THEN {$s['poin']}";
            }
        }

        if ($cases) {
            $expr = 'CASE '.implode(' ', $cases).' ELSE 0 END';
            DB::table('ujian_jawaban')
                ->where('ujian_id', $ujianId)
                ->where('user_id', $userId)
                ->whereNotNull('pilihan_id')
                ->update(['skor' => DB::raw($expr)]);
        }

        $totalSkor = (float) DB::table('ujian_jawaban')
            ->where('ujian_id', $ujianId)
            ->where('user_id', $userId)
            ->sum('skor');

        $totalPoin = (float) array_sum(array_column($payload, 'poin'));

        $sesi->update([
            'status' => 'selesai',
            'submitted_at' => now(),
            'nilai' => $totalPoin > 0 ? round($totalSkor / $totalPoin * 100, 2) : 0,
        ]);

        Cache::forget("ujian:{$ujianId}:hasil:{$userId}");
    }

    /**
     * Payload soal lengkap dengan kunci jawaban, disimpan di cache.
     */
    private function payloadSoal(int $ujianId): array
    {
        return Cache::remember("ujian:{$ujianId}:soal", self::CACHE_SOAL, function () use ($ujianId) {
            return UjianSoal::with('pilihan')
                ->where('ujian_id', $ujianId)
                ->orderBy('urut')
                ->get()
                ->map(fn ($soal) => [
                    'id' => (int) $soal->id,
                    'tipe' => $soal->tipe,
                    'pertanyaan' => $soal->pertanyaan,
                    'petunjuk' => $soal->petunjuk,
                    'poin' => (int) $soal->poin,
                    'urut' => (int) $soal->urut,
                    'pilihan' => $soal->pilihan->map(fn ($p) => [
                        'id' => (int) $p->id,
                        'label' => $p->label,
                        'teks' => $p->teks,
                        'benar' => (bool) $p->benar,
                    ])->values()->all(),
                ])->values()->all();
        });
    }

    /**
     * Buang kunci jawaban sebelum dikirim ke browser peserta.
     */
    private function tanpaKunci(array $payload): array
    {
        return array_map(fn ($s) => [
            'id' => $s['id'],
            'tipe' => $s['tipe'],
            'pertanyaan' => $s['pertanyaan'],
            'petunjuk' => $s['petunjuk'] ?? null,
            'poin' => $s['poin'],
            'urut' => $s['urut'],
            'pilihan' => array_map(fn ($p) => [
                'id' => $p['id'],
                'label' => $p['label'],
                'teks' => $p['teks'],
            ], $s['pilihan']),
        ], $payload);
    }

    /**
     * Susun rekap hasil + pembahasan per soal.
     */
    private function susunHasil(int $ujianId, int $userId): array
    {
        $payload = $this->payloadSoal($ujianId);

        $jawaban = UjianJawaban::where('ujian_id', $ujianId)
            ->where('user_id', $userId)
            ->get()
            ->keyBy('soal_id');

        $benar = 0;
        $salah = 0;
        $skip = 0;
        $totalSkor = 0.0;

        $rincian = [];

        foreach ($payload as $s) {
            $j = $jawaban->get($s['id']);

            $kunci = null;
            $kunciLabel = null;
            foreach ($s['pilihan'] as $p) {
                if ($p['benar']) {
                    $kunci = $p;
                    $kunciLabel = $p['label'];
                    break;
                }
            }

            $dipilih = null;
            $dipilihLabel = null;
            if ($j && $j->pilihan_id) {
                foreach ($s['pilihan'] as $p) {
                    if ($p['id'] === (int) $j->pilihan_id) {
                        $dipilih = $p;
                        $dipilihLabel = $p['label'];
                        break;
                    }
                }
            }

            if ($s['tipe'] === 'pilihan_ganda') {
                if ($dipilih === null) {
                    $skip++;
                    $status = 'skip';
                } elseif ($kunci && $dipilih['id'] === $kunci['id']) {
                    $benar++;
                    $status = 'benar';
                } else {
                    $salah++;
                    $status = 'salah';
                }

                $skorSoal = $j && $j->skor !== null ? (float) $j->skor : ($status === 'benar' ? $s['poin'] : 0);
            } else {
                $status = 'esai';
                $skorSoal = (float) ($j?->skor ?? 0);
            }

            $totalSkor += $skorSoal;

            $rincian[] = [
                'nomor' => $s['urut'],
                'tipe' => $s['tipe'],
                'pertanyaan' => $s['pertanyaan'],
                'petunjuk' => $s['petunjuk'],
                'poin' => $s['poin'],
                'status' => $status,
                'dipilihLabel' => $dipilihLabel,
                'dipilihTeks' => $dipilih['teks'] ?? null,
                'kunciLabel' => $kunciLabel,
                'kunciTeks' => $kunci['teks'] ?? null,
                'esai' => $j?->esai,
                'file_nama' => $j?->file_nama,
                'file_url' => $j && $j->file_path ? route('ujian.berkas', $j->id) : null,
                'skor' => $j?->skor,
                'skorSoal' => round($skorSoal, 2),
                'dinilai' => $j && $j->skor !== null,
            ];
        }

        $totalPoin = (float) array_sum(array_column($payload, 'poin'));

        return [
            'rincian' => $rincian,
            'benar' => $benar,
            'salah' => $salah,
            'skip' => $skip,
            'totalPoin' => $totalPoin,
            'totalSkor' => round($totalSkor, 2),
            'nilai' => $totalPoin > 0 ? round($totalSkor / $totalPoin * 100, 2) : 0,
        ];
    }
}
