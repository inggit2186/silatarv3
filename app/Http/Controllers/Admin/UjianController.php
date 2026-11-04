<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ujian;
use App\Models\UjianJawaban;
use App\Models\UjianPeserta;
use App\Models\UjianPilihan;
use App\Models\UjianSoal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class UjianController extends Controller
{
    private array $statuses = [
        'draft' => 'Draft',
        'terbit' => 'Terbit',
        'selesai' => 'Selesai',
    ];

    private array $tipeSoal = [
        'pilihan_ganda' => 'Pilihan Ganda',
        'essay' => 'Esai',
    ];

    public function index(Request $request)
    {
        $query = Ujian::query()
            ->withCount(['soal', 'peserta'])
            ->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                    ->orWhere('kode', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $ujians = $query->paginate(15)->withQueryString();

        $stats = [
            'total' => Ujian::count(),
            'terbit' => Ujian::where('status', 'terbit')->count(),
            'draft' => Ujian::where('status', 'draft')->count(),
            'selesai' => Ujian::where('status', 'selesai')->count(),
            'peserta' => UjianPeserta::count(),
        ];

        return view('admin.ujian.index', [
            'title' => 'Manajemen Ujian - SILATAR Admin',
            'ujians' => $ujians,
            'statuses' => $this->statuses,
            'stats' => $stats,
        ]);
    }

    public function create(Request $request)
    {
        return view('admin.ujian.create', [
            'title' => 'Tambah Ujian - SILATAR Admin',
            'statuses' => $this->statuses,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode' => ['required', 'string', 'max:50', 'unique:ujian,kode'],
            'judul' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'materi' => ['nullable', 'string'],
            'durasi_menit' => ['required', 'integer', 'min:1', 'max:600'],
            'status' => ['required', 'in:draft,terbit,selesai'],
            'mulai_at' => ['nullable', 'date'],
            'selesai_at' => ['nullable', 'date', 'after_or_equal:mulai_at'],
            'acak_soal' => ['nullable', 'boolean'],
        ]);

        $validated['created_by'] = $request->user()->id;
        $validated['acak_soal'] = $request->boolean('acak_soal');

        // Ujian baru belum punya soal maupun peserta, jadi selalu dibuat sebagai draft.
        if ($validated['status'] === 'terbit') {
            $validated['status'] = 'draft';
        }

        $ujian = Ujian::create($validated);

        return redirect()->route('admin.ujian.edit', $ujian->id)
            ->with('success', 'Ujian berhasil dibuat. Tambahkan soal dan peserta untuk menerbitkannya.');
    }

    public function edit(Request $request, int $id)
    {
        $ujian = Ujian::with(['soal.pilihan', 'peserta.user'])->find($id);
        abort_unless($ujian, 404);

        return view('admin.ujian.edit', [
            'title' => 'Kelola Ujian - SILATAR Admin',
            'ujian' => $ujian,
            'statuses' => $this->statuses,
            'tipeSoal' => $this->tipeSoal,
        ]);
    }

    public function update(Request $request, int $id)
    {
        $ujian = Ujian::find($id);
        abort_unless($ujian, 404);

        $validated = $request->validate([
            'kode' => ['required', 'string', 'max:50', 'unique:ujian,kode,'.$id],
            'judul' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'materi' => ['nullable', 'string'],
            'durasi_menit' => ['required', 'integer', 'min:1', 'max:600'],
            'status' => ['required', 'in:draft,terbit,selesai'],
            'mulai_at' => ['nullable', 'date'],
            'selesai_at' => ['nullable', 'date', 'after_or_equal:mulai_at'],
            'acak_soal' => ['nullable', 'boolean'],
        ]);

        $validated['acak_soal'] = $request->boolean('acak_soal');

        if ($validated['status'] === 'terbit') {
            $soalCount = UjianSoal::where('ujian_id', $id)->count();
            $pesertaCount = UjianPeserta::where('ujian_id', $id)->count();

            if ($soalCount === 0 || $pesertaCount === 0) {
                return back()->with('error', 'Ujian belum dapat diterbitkan. Minimal 1 soal dan 1 peserta diperlukan.');
            }
        }

        $ujian->update($validated);

        return redirect()->route('admin.ujian.edit', $id)
            ->with('success', 'Data ujian berhasil diperbarui.');
    }

    public function destroy(Request $request, int $id)
    {
        $ujian = Ujian::find($id);
        abort_unless($ujian, 404);

        $ujian->delete();
        Cache::forget("ujian:{$id}:soal");

        return redirect()->route('admin.ujian.index')
            ->with('success', 'Ujian berhasil dihapus.');
    }

    public function storeSoal(Request $request, int $id)
    {
        $ujian = Ujian::find($id);
        abort_unless($ujian, 404);

        $validated = $request->validate([
            'tipe' => ['required', 'in:pilihan_ganda,essay'],
            'pertanyaan' => ['required', 'string'],
            'petunjuk' => ['nullable', 'string'],
            'poin' => ['required', 'integer', 'min:1', 'max:100'],
            'pilihan' => ['nullable', 'array'],
            'pilihan.*.id' => ['nullable', 'integer'],
            'pilihan.*.teks' => ['nullable', 'string'],
            'pilihan_benar' => ['nullable', 'integer'],
        ]);

        if ($validated['tipe'] === 'pilihan_ganda') {
            $opsi = array_filter($request->input('pilihan', []), fn ($p) => ! empty($p['teks']));
            if (count($opsi) < 2) {
                return back()->with('error', 'Soal pilihan ganda minimal memiliki 2 opsi jawaban.');
            }
            $request->merge(['pilihan' => array_values($opsi)]);
        }

        $urut = (UjianSoal::where('ujian_id', $id)->max('urut') ?? 0) + 1;

        $soal = UjianSoal::create([
            'ujian_id' => $id,
            'tipe' => $validated['tipe'],
            'pertanyaan' => $validated['pertanyaan'],
            'petunjuk' => $validated['petunjuk'] ?? null,
            'poin' => $validated['poin'],
            'urut' => $urut,
        ]);

        if ($validated['tipe'] === 'pilihan_ganda') {
            $this->syncPilihan($soal, $request->input('pilihan', []), $request->input('pilihan_benar'));
        }

        Cache::forget("ujian:{$id}:soal");

        return back()->with('success', 'Soal berhasil ditambahkan.');
    }

    public function updateSoal(Request $request, int $soalId)
    {
        $soal = UjianSoal::with('pilihan')->find($soalId);
        abort_unless($soal, 404);

        $validated = $request->validate([
            'tipe' => ['required', 'in:pilihan_ganda,essay'],
            'pertanyaan' => ['required', 'string'],
            'petunjuk' => ['nullable', 'string'],
            'poin' => ['required', 'integer', 'min:1', 'max:100'],
            'pilihan' => ['nullable', 'array'],
            'pilihan.*.id' => ['nullable', 'integer'],
            'pilihan.*.teks' => ['nullable', 'string'],
            'pilihan_benar' => ['nullable', 'integer'],
        ]);

        $soal->update([
            'tipe' => $validated['tipe'],
            'pertanyaan' => $validated['pertanyaan'],
            'petunjuk' => $validated['petunjuk'] ?? null,
            'poin' => $validated['poin'],
        ]);

        if ($validated['tipe'] === 'pilihan_ganda') {
            $opsi = array_values(array_filter($request->input('pilihan', []), fn ($p) => ! empty($p['teks'])));
            if (count($opsi) < 2) {
                return back()->with('error', 'Soal pilihan ganda minimal memiliki 2 opsi jawaban.');
            }
            $this->syncPilihan($soal, $opsi, $request->input('pilihan_benar'));
        } else {
            $soal->pilihan()->delete();
        }

        Cache::forget("ujian:{$soal->ujian_id}:soal");

        return back()->with('success', 'Soal berhasil diperbarui.');
    }

    public function destroySoal(Request $request, int $soalId)
    {
        $soal = UjianSoal::find($soalId);
        abort_unless($soal, 404);

        $ujianId = $soal->ujian_id;
        $soal->delete();

        Cache::forget("ujian:{$ujianId}:soal");

        return back()->with('success', 'Soal berhasil dihapus.');
    }

    public function storePilihan(Request $request, int $soalId)
    {
        $soal = UjianSoal::find($soalId);
        abort_unless($soal, 404);

        $validated = $request->validate([
            'teks' => ['required', 'string'],
            'benar' => ['nullable', 'boolean'],
        ]);

        $urut = ($soal->pilihan()->max('urut') ?? 0) + 1;
        $label = chr(65 + $urut);

        UjianPilihan::create([
            'soal_id' => $soalId,
            'label' => $label,
            'teks' => $validated['teks'],
            'benar' => $request->boolean('benar'),
            'urut' => $urut,
        ]);

        Cache::forget("ujian:{$soal->ujian_id}:soal");

        return back()->with('success', 'Pilihan jawaban berhasil ditambahkan.');
    }

    public function updatePilihan(Request $request, int $pilihanId)
    {
        $pilihan = UjianPilihan::find($pilihanId);
        abort_unless($pilihan, 404);

        $validated = $request->validate([
            'teks' => ['required', 'string'],
            'benar' => ['nullable', 'boolean'],
        ]);

        $pilihan->update([
            'teks' => $validated['teks'],
            'benar' => $request->boolean('benar'),
        ]);

        Cache::forget("ujian:{$pilihan->soal->ujian_id}:soal");

        return back()->with('success', 'Pilihan jawaban berhasil diperbarui.');
    }

    public function destroyPilihan(Request $request, int $pilihanId)
    {
        $pilihan = UjianPilihan::find($pilihanId);
        abort_unless($pilihan, 404);

        $ujianId = $pilihan->soal->ujian_id;

        DB::table('ujian_jawaban')
            ->where('pilihan_id', $pilihan->id)
            ->update(['pilihan_id' => null]);

        $pilihan->delete();

        Cache::forget("ujian:{$ujianId}:soal");

        return back()->with('success', 'Pilihan jawaban berhasil dihapus.');
    }

    public function storePeserta(Request $request, int $id)
    {
        $ujian = Ujian::find($id);
        abort_unless($ujian, 404);

        $validated = $request->validate([
            'user_ids' => ['required', 'array', 'min:1'],
            'user_ids.*' => ['integer', 'exists:users,id'],
        ]);

        $terdaftar = UjianPeserta::where('ujian_id', $id)->pluck('user_id')->all();

        $tambah = array_values(array_diff($validated['user_ids'], $terdaftar));

        if ($tambah) {
            $rows = array_map(fn ($userId) => [
                'ujian_id' => $id,
                'user_id' => $userId,
                'status' => 'belum_mulai',
                'created_at' => now(),
                'updated_at' => now(),
            ], $tambah);

            UjianPeserta::insert($rows);
        }

        return back()->with('success', count($tambah) > 0
            ? count($tambah).' peserta berhasil ditambahkan.'
            : 'Peserta yang dipilih sudah terdaftar.');
    }

    public function destroyPeserta(Request $request, int $pesertaId)
    {
        $peserta = UjianPeserta::find($pesertaId);
        abort_unless($peserta, 404);

        if ($peserta->status === 'berjalan') {
            return back()->with('error', 'Peserta sedang mengerjakan ujian dan tidak dapat dihapus.');
        }

        DB::table('ujian_jawaban')
            ->where('ujian_id', $peserta->ujian_id)
            ->where('user_id', $peserta->user_id)
            ->delete();

        $peserta->delete();

        return back()->with('success', 'Peserta berhasil dihapus dari ujian ini.');
    }

    public function cariPeserta(Request $request, int $id)
    {
        $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
        ]);

        $q = trim((string) $request->query('q', ''));

        $users = DB::table('users')
            ->select('id', 'name', 'nip', 'dept_id', 'role')
            ->where(function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                    ->orWhere('nip', 'like', "%{$q}%");
            })
            ->orderBy('name')
            ->limit(20)
            ->get();

        $terdaftar = UjianPeserta::where('ujian_id', $id)->pluck('user_id')->all();

        return response()->json([
            'results' => $users->map(fn ($u) => [
                'id' => $u->id,
                'name' => $u->name,
                'nip' => $u->nip,
                'role' => $u->role,
                'terdaftar' => in_array($u->id, $terdaftar, true),
            ]),
        ]);
    }

    public function hasil(Request $request, int $id)
    {
        $ujian = Ujian::find($id);
        abort_unless($ujian, 404);

        $peserta = UjianPeserta::where('ujian_id', $id)
            ->with('user')
            ->orderBy('user_id')
            ->paginate(20);

        $soalEssay = UjianSoal::where('ujian_id', $id)
            ->where('tipe', 'essay')
            ->orderBy('urut')
            ->get();

        $jawabanEssay = UjianJawaban::where('ujian_id', $id)
            ->whereIn('soal_id', $soalEssay->pluck('id'))
            ->get()
            ->groupBy('user_id');

        $totalPoin = UjianSoal::where('ujian_id', $id)->sum('poin');

        $nilaiTerisi = UjianPeserta::where('ujian_id', $id)
            ->whereNotNull('nilai')
            ->pluck('nilai');

        $stats = [
            'total' => UjianPeserta::where('ujian_id', $id)->count(),
            'selesai' => UjianPeserta::where('ujian_id', $id)->where('status', 'selesai')->count(),
            'berjalan' => UjianPeserta::where('ujian_id', $id)->where('status', 'berjalan')->count(),
            'rata_rata' => $nilaiTerisi->isEmpty() ? null : round($nilaiTerisi->avg(), 2),
            'total_peserta' => UjianPeserta::count(),
        ];

        return view('admin.ujian.hasil', [
            'title' => 'Hasil Ujian - SILATAR Admin',
            'ujian' => $ujian,
            'peserta' => $peserta,
            'soalEssay' => $soalEssay,
            'jawabanEssay' => $jawabanEssay,
            'totalPoin' => $totalPoin,
            'stats' => $stats,
        ]);
    }

    public function updateSkor(Request $request, int $jawabanId)
    {
        $jawaban = UjianJawaban::find($jawabanId);
        abort_unless($jawaban, 404);

        $validated = $request->validate([
            'skor' => ['required', 'numeric', 'min:0', 'max:100'],
        ]);

        $soal = UjianSoal::find($jawaban->soal_id);
        $skor = min((float) $validated['skor'], (float) ($soal?->poin ?? 100));

        $jawaban->update(['skor' => $skor]);

        $this->hitungUlangNilai($jawaban->ujian_id, $jawaban->user_id);

        return back()->with('success', 'Skor jawaban berhasil diperbarui.');
    }

    private function hitungUlangNilai(int $ujianId, int $userId): void
    {
        $totalPoin = UjianSoal::where('ujian_id', $ujianId)->sum('poin');
        if ($totalPoin <= 0) {
            return;
        }

        $totalSkor = UjianJawaban::where('ujian_id', $ujianId)
            ->where('user_id', $userId)
            ->sum('skor');

        UjianPeserta::where('ujian_id', $ujianId)
            ->where('user_id', $userId)
            ->update(['nilai' => round($totalSkor / $totalPoin * 100, 2), 'updated_at' => now()]);

        Cache::forget("ujian:{$ujianId}:hasil:{$userId}");
    }

    /**
     * Sinkronkan pilihan jawaban tanpa mengubah id yang sudah dipakai jawaban peserta.
     */
    private function syncPilihan(UjianSoal $soal, array $opsi, ?int $kunciIndex): void
    {
        $kunciIndex = $kunciIndex !== null ? (int) $kunciIndex : null;
        $existing = $soal->pilihan()->get()->keyBy('id');

        $keepIds = [];

        foreach (array_values($opsi) as $index => $opsiItem) {
            $label = chr(65 + $index);
            $benar = $kunciIndex === $index;

            if (! empty($opsiItem['id']) && $existing->has($opsiItem['id'])) {
                $existing[$opsiItem['id']]->update([
                    'label' => $label,
                    'teks' => $opsiItem['teks'],
                    'benar' => $benar,
                    'urut' => $index,
                ]);
                $keepIds[] = $opsiItem['id'];

                continue;
            }

            $pilihan = UjianPilihan::create([
                'soal_id' => $soal->id,
                'label' => $label,
                'teks' => $opsiItem['teks'],
                'benar' => $benar,
                'urut' => $index,
            ]);
            $keepIds[] = $pilihan->id;
        }

        $existing->except($keepIds)->each(function ($pilihan) {
            // Jawaban peserta yang menunjuk opsi ini menjadi tidak berlaku.
            DB::table('ujian_jawaban')
                ->where('pilihan_id', $pilihan->id)
                ->update(['pilihan_id' => null]);

            $pilihan->delete();
        });
    }
}
