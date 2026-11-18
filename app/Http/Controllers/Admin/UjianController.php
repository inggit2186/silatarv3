<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ujian;
use App\Models\UjianJawaban;
use App\Models\UjianPelanggaran;
use App\Models\UjianPeserta;
use App\Models\UjianPilihan;
use App\Models\UjianSoal;
use App\Services\UjianSoalImportService;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Format;
use Intervention\Image\ImageManager;

class UjianController extends Controller
{
    public function __construct(private UjianSoalImportService $importSoalService) {}

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
            'cover' => ['nullable', 'image', 'max:4096'],
            'file_soal' => ['nullable', 'file', 'mimes:xlsx,xls', 'max:5120'],
        ]);

        $validated['created_by'] = $request->user()->id;
        $validated['acak_soal'] = $request->boolean('acak_soal');
        unset($validated['cover']);

        // Ujian baru belum punya soal maupun peserta, jadi selalu dibuat sebagai draft.
        if ($validated['status'] === 'terbit') {
            $validated['status'] = 'draft';
        }

        $ujian = Ujian::create($validated);

        if ($request->hasFile('cover')) {
            $ujian->update(['cover_path' => $this->simpanCover($request->file('cover'))]);
        }

        if ($request->hasFile('file_soal')) {
            $hasil = $this->prosesImportSoal($ujian->id, $request->file('file_soal'));

            if (! $hasil['sukses']) {
                return redirect()->route('admin.ujian.edit', $ujian->id)
                    ->with('error', 'Ujian berhasil dibuat, tetapi impor soal gagal: '.$hasil['errors'][0])
                    ->with('import_errors', $hasil['errors']);
            }

            return redirect()->route('admin.ujian.edit', $ujian->id)
                ->with('success', 'Ujian berhasil dibuat. '.$hasil['pesan']);
        }

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
            'cover' => ['nullable', 'image', 'max:4096'],
            'remove_cover' => ['nullable', 'boolean'],
        ]);

        $validated['acak_soal'] = $request->boolean('acak_soal');
        unset($validated['cover'], $validated['remove_cover']);

        if ($request->hasFile('cover')) {
            $this->hapusCover($ujian->cover_path);
            $validated['cover_path'] = $this->simpanCover($request->file('cover'));
        } elseif ($request->boolean('remove_cover')) {
            $this->hapusCover($ujian->cover_path);
            $validated['cover_path'] = null;
        }

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

        $this->hapusCover($ujian->cover_path);

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

    public function importSoal(Request $request, int $id)
    {
        $ujian = Ujian::find($id);
        abort_unless($ujian, 404);

        $request->validate([
            'file_soal' => ['required', 'file', 'mimes:xlsx,xls', 'max:5120'],
        ]);

        $hasil = $this->prosesImportSoal($id, $request->file('file_soal'));

        if (! $hasil['sukses']) {
            return back()
                ->with('error', 'Impor soal gagal. Perbaiki file Excel lalu unggah ulang.')
                ->with('import_errors', $hasil['errors']);
        }

        return back()->with('success', $hasil['pesan']);
    }

    public function templateSoal()
    {
        $path = $this->importSoalService->unduhTemplate();

        return response()->download($path, 'template-import-soal-ujian.xlsx')
            ->deleteFileAfterSend(true);
    }

    /**
     * Simpan cover ujian sebagai WebP agar ringan di server.
     */
    private function simpanCover(UploadedFile $file): string
    {
        $nama = Str::uuid().'.webp';
        $path = 'ujian/cover/'.$nama;

        try {
            $manager = new ImageManager(new Driver);
            $img = $manager->decode($file->getPathname());

            // Lebar maksimum 1200px, rasio.aspect dipertahankan.
            $lebarMaks = 1200;
            if ($img->width() > $lebarMaks) {
                $tinggi = (int) round(($lebarMaks / $img->width()) * $img->height());
                $img->resize($lebarMaks, $tinggi);
            }

            $encoded = $img->encodeUsingFormat(Format::WEBP, quality: 82);
            Storage::disk('public')->put($path, $encoded->toString());
        } catch (\Exception $e) {
            // Cadangan: simpan berkas asli bila pemrosesan gambar gagal.
            $path = $file->storeAs('ujian/cover', Str::uuid().'.'.$file->getClientOriginalExtension(), 'public');
        }

        return $path;
    }

    /**
     * Hapus berkas cover lama dari storage.
     */
    private function hapusCover(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    /**
     * @return array{sukses: bool, errors: array<int, string>, pesan: string}
     */
    private function prosesImportSoal(int $ujianId, UploadedFile $file): array
    {
        $hasil = $this->importSoalService->parse($file);

        if (! empty($hasil['errors'])) {
            return ['sukses' => false, 'errors' => $hasil['errors'], 'pesan' => ''];
        }

        $jumlah = $this->importSoalService->simpan($ujianId, $hasil['rows']);

        Cache::forget("ujian:{$ujianId}:soal");

        return [
            'sukses' => true,
            'errors' => [],
            'pesan' => $jumlah.' soal berhasil diimpor dari file Excel.',
        ];
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
            ->select('id', 'name', 'nomor_induk')
            ->where(function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                    ->orWhere('nomor_induk', 'like', "%{$q}%");
            })
            ->orderBy('name')
            ->limit(20)
            ->get();

        $terdaftar = UjianPeserta::where('ujian_id', $id)->pluck('user_id')->all();

        return response()->json([
            'results' => $users->map(fn ($u) => [
                'id' => $u->id,
                'name' => $u->name,
                'nomor_induk' => $u->nomor_induk,
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

        $pelanggaran = UjianPelanggaran::where('ujian_id', $id)
            ->orderBy('created_at')
            ->get()
            ->groupBy('user_id');

        $nilaiTerisi = UjianPeserta::where('ujian_id', $id)
            ->whereNotNull('nilai')
            ->pluck('nilai');

        $stats = [
            'total' => UjianPeserta::where('ujian_id', $id)->count(),
            'selesai' => UjianPeserta::where('ujian_id', $id)->where('status', 'selesai')->count(),
            'berjalan' => UjianPeserta::where('ujian_id', $id)->where('status', 'berjalan')->count(),
            'rata_rata' => $nilaiTerisi->isEmpty() ? null : round($nilaiTerisi->avg(), 2),
            'total_peserta' => UjianPeserta::count(),
            'total_pelanggaran' => $pelanggaran->sum(fn ($items) => $items->count()),
        ];

        return view('admin.ujian.hasil', [
            'title' => 'Hasil Ujian - SILATAR Admin',
            'ujian' => $ujian,
            'peserta' => $peserta,
            'soalEssay' => $soalEssay,
            'jawabanEssay' => $jawabanEssay,
            'totalPoin' => $totalPoin,
            'stats' => $stats,
            'pelanggaran' => $pelanggaran,
        ]);
    }

    /**
     * Halaman rincian jawaban satu peserta.
     */
    public function jawabanPeserta(Request $request, int $id, int $userId)
    {
        $ujian = Ujian::find($id);
        abort_unless($ujian, 404);

        $peserta = UjianPeserta::where('ujian_id', $id)
            ->where('user_id', $userId)
            ->with('user')
            ->first();
        abort_unless($peserta, 404);

        $soal = UjianSoal::with('pilihan')
            ->where('ujian_id', $id)
            ->orderBy('urut')
            ->get();

        $jawaban = UjianJawaban::where('ujian_id', $id)
            ->where('user_id', $userId)
            ->get()
            ->keyBy('soal_id');

        $pelanggaran = UjianPelanggaran::where('ujian_id', $id)
            ->where('user_id', $userId)
            ->orderBy('created_at')
            ->get();

        return view('admin.ujian.jawaban-peserta', [
            'title' => 'Jawaban Peserta - SILATAR Admin',
            'ujian' => $ujian,
            'peserta' => $peserta,
            'rincian' => $this->susunRincianJawaban($soal, $jawaban),
            'pelanggaran' => $pelanggaran,
        ]);
    }

    /**
     * Hapus seluruh jawaban satu peserta supaya ia dapat mengulang ujian.
     */
    public function resetJawaban(Request $request, int $id, int $userId)
    {
        $peserta = UjianPeserta::where('ujian_id', $id)
            ->where('user_id', $userId)
            ->first();
        abort_unless($peserta, 404);

        if ($peserta->status === 'berjalan') {
            return back()->with('error', 'Peserta sedang mengerjakan ujian. Tunggu sesi selesai sebelum mereset jawaban.');
        }

        $jawaban = UjianJawaban::where('ujian_id', $id)
            ->where('user_id', $userId)
            ->get();

        // Lampiran esai ikut dihapus agar tidak menyisakan berkas sampah.
        foreach ($jawaban->pluck('file_path')->filter()->unique() as $path) {
            Storage::disk('public')->delete($path);
        }

        UjianJawaban::where('ujian_id', $id)
            ->where('user_id', $userId)
            ->delete();

        UjianPelanggaran::where('ujian_id', $id)
            ->where('user_id', $userId)
            ->delete();

        $peserta->update([
            'status' => 'belum_mulai',
            'started_at' => null,
            'ends_at' => null,
            'submitted_at' => null,
            'nilai' => null,
            'ip' => null,
            'user_agent' => null,
            'sidik_perangkat' => null,
        ]);

        Cache::forget("ujian:{$id}:hasil:{$userId}");

        return back()->with('success', 'Jawaban peserta telah dihapus. Peserta dapat mengerjakan ulang ujian.');
    }

    /**
     * Halaman cetak / export hasil ujian yang print friendly.
     */
    public function cetak(Request $request, int $id)
    {
        $ujian = Ujian::find($id);
        abort_unless($ujian, 404);

        $peserta = UjianPeserta::where('ujian_id', $id)
            ->with('user')
            ->orderBy('user_id')
            ->get();

        $soal = UjianSoal::with('pilihan')
            ->where('ujian_id', $id)
            ->orderBy('urut')
            ->get();

        $jawaban = UjianJawaban::where('ujian_id', $id)
            ->get()
            ->groupBy('user_id');

        $pelanggaran = UjianPelanggaran::where('ujian_id', $id)
            ->orderBy('created_at')
            ->get()
            ->groupBy('user_id');

        $nilaiTerisi = $peserta->whereNotNull('nilai')->pluck('nilai');

        $stats = [
            'total' => $peserta->count(),
            'selesai' => $peserta->where('status', 'selesai')->count(),
            'berjalan' => $peserta->where('status', 'berjalan')->count(),
            'belum_mulai' => $peserta->where('status', 'belum_mulai')->count(),
            'rata_rata' => $nilaiTerisi->isEmpty() ? null : round($nilaiTerisi->avg(), 2),
            'tertinggi' => $nilaiTerisi->isEmpty() ? null : round($nilaiTerisi->max(), 2),
            'terendah' => $nilaiTerisi->isEmpty() ? null : round($nilaiTerisi->min(), 2),
            'total_pelanggaran' => $pelanggaran->sum(fn ($items) => $items->count()),
        ];

        $rincianPeserta = [];

        foreach ($peserta as $item) {
            $rincianPeserta[$item->user_id] = $this->susunRincianJawaban(
                $soal,
                $jawaban->get($item->user_id, collect())->keyBy('soal_id')
            );
        }

        return view('admin.ujian.cetak', [
            'title' => 'Cetak Hasil Ujian - SILATAR Admin',
            'ujian' => $ujian,
            'peserta' => $peserta,
            'soal' => $soal,
            'rincianPeserta' => $rincianPeserta,
            'pelanggaran' => $pelanggaran,
            'stats' => $stats,
            'totalPoin' => $soal->sum('poin'),
            'fontBase' => asset('fonts/pdf'),
            'letterhead' => asset('assets/img/template/header.png'),
            'cetakPada' => now()->format('d/m/Y H:i'),
            'dicetakOleh' => $request->user()->name,
        ]);
    }

    /**
     * Susun rincian jawaban satu peserta terhadap seluruh soal ujian.
     *
     * @param  Collection<int, UjianSoal>  $soal
     * @param  Collection<int, UjianJawaban>  $jawaban  dikunci berdasarkan soal_id
     */
    private function susunRincianJawaban($soal, $jawaban): array
    {
        $benar = 0;
        $salah = 0;
        $kosong = 0;
        $totalSkor = 0.0;
        $totalPoin = 0;

        $rincian = [];

        foreach ($soal as $nomor => $s) {
            $totalPoin += (int) $s->poin;

            $j = $jawaban->get($s->id);
            $kunci = $s->pilihan->firstWhere('benar', true);
            $dipilih = $j && $j->pilihan_id
                ? $s->pilihan->firstWhere('id', $j->pilihan_id)
                : null;

            if ($s->tipe === 'pilihan_ganda') {
                if ($dipilih === null) {
                    $kosong++;
                    $status = 'kosong';
                } elseif ($kunci && $dipilih->id === $kunci->id) {
                    $benar++;
                    $status = 'benar';
                } else {
                    $salah++;
                    $status = 'salah';
                }

                $skorSoal = $j && $j->skor !== null ? (float) $j->skor : ($status === 'benar' ? (float) $s->poin : 0.0);
            } else {
                $status = 'esai';
                $skorSoal = (float) ($j?->skor ?? 0);
            }

            $totalSkor += $skorSoal;

            $rincian[] = [
                'nomor' => $nomor + 1,
                'soal_id' => $s->id,
                'tipe' => $s->tipe,
                'pertanyaan' => $s->pertanyaan,
                'petunjuk' => $s->petunjuk,
                'poin' => (int) $s->poin,
                'status' => $status,
                'pilihan' => $s->pilihan->map(fn ($p) => [
                    'id' => $p->id,
                    'label' => $p->label,
                    'teks' => $p->teks,
                    'benar' => (bool) $p->benar,
                    'dipilih' => $dipilih !== null && $dipilih->id === $p->id,
                ])->values(),
                'kunciLabel' => $kunci?->label,
                'kunciTeks' => $kunci?->teks,
                'dipilihLabel' => $dipilih?->label,
                'dipilihTeks' => $dipilih?->teks,
                'esai' => $j?->esai,
                'file_nama' => $j?->file_nama,
                'file_url' => $j && $j->file_path ? route('ujian.berkas', $j->id) : null,
                'skor' => $j?->skor,
                'skorSoal' => round($skorSoal, 2),
                'dijawab_at' => $j?->dijawab_at,
            ];
        }

        return [
            'rincian' => $rincian,
            'benar' => $benar,
            'salah' => $salah,
            'kosong' => $kosong,
            'totalPoin' => $totalPoin,
            'totalSkor' => round($totalSkor, 2),
            'nilai' => $totalPoin > 0 ? round($totalSkor / $totalPoin * 100, 2) : 0,
        ];
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
