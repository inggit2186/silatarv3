<?php

namespace Tests\Feature;

use App\Http\Controllers\UjianController;
use App\Models\Ujian;
use App\Models\UjianJawaban;
use App\Models\UjianPeserta;
use App\Models\UjianPilihan;
use App\Models\UjianSoal;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UjianTest extends TestCase
{
    use DatabaseTransactions;

    private Ujian $ujian;

    private UjianSoal $soalPg;

    private UjianSoal $soalEssay;

    private User $peserta;

    private User $bukanPeserta;

    protected function setUp(): void
    {
        parent::setUp();

        $admin = User::where('role', 'superadmin')->firstOrFail();
        $this->peserta = $this->userDenganProfilLengkap();
        $this->bukanPeserta = User::where('id', '<>', $this->peserta->id)
            ->whereNotIn('role', ['pensiun', 'pindah'])
            ->firstOrFail();

        $this->ujian = Ujian::create([
            'kode' => 'UJN-TEST-'.uniqid(),
            'judul' => 'Ujian Otomatis',
            'durasi_menit' => 30,
            'status' => 'terbit',
            'mulai_at' => now()->subHour(),
            'created_by' => $admin->id,
        ]);

        $this->soalPg = UjianSoal::create([
            'ujian_id' => $this->ujian->id,
            'tipe' => 'pilihan_ganda',
            'pertanyaan' => 'Ibu kota Indonesia adalah?',
            'poin' => 20,
            'urut' => 1,
        ]);

        $this->pilihanBenar = UjianPilihan::create([
            'soal_id' => $this->soalPg->id,
            'label' => 'A',
            'teks' => 'Jakarta',
            'benar' => true,
            'urut' => 0,
        ]);

        $this->pilihanSalah = UjianPilihan::create([
            'soal_id' => $this->soalPg->id,
            'label' => 'B',
            'teks' => 'Bandung',
            'benar' => false,
            'urut' => 1,
        ]);

        $this->soalEssay = UjianSoal::create([
            'ujian_id' => $this->ujian->id,
            'tipe' => 'essay',
            'pertanyaan' => 'Jelaskan prinsip pelayanan publik.',
            'poin' => 30,
            'urut' => 2,
        ]);

        UjianPeserta::create([
            'ujian_id' => $this->ujian->id,
            'user_id' => $this->peserta->id,
            'status' => 'belum_mulai',
        ]);

        Cache::forget("ujian:{$this->ujian->id}:soal");
    }

    private UjianPilihan $pilihanBenar;

    private UjianPilihan $pilihanSalah;

    /**
     * Middleware EnsureProfileComplete mengalihkan halaman view bila data profil belum lengkap,
     * sehingga peserta uji harus memakai user yang profilnya sudah lengkap.
     */
    private function userDenganProfilLengkap(): User
    {
        $candidates = User::whereNotIn('role', ['pensiun', 'pindah', 'superadmin', 'admin'])
            ->orderBy('id')
            ->get();

        foreach ($candidates as $user) {
            $tenaga = DB::table('tenaga_ktd')
                ->where(function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                    if (! empty($user->nomor_induk)) {
                        $q->orWhere('nomor_induk', $user->nomor_induk);
                    }
                })
                ->first();

            if (empty($user->pp)) {
                continue;
            }

            $nikUser = (string) ($user->nip ?? '');
            $nikTenaga = (string) ($tenaga->nik ?? '');

            if (! preg_match('/^\d{16}$/', $nikUser) && ! preg_match('/^\d{16}$/', $nikTenaga)) {
                continue;
            }

            if (! preg_match('/^\d{16}$/', (string) ($tenaga->kk ?? ''))) {
                continue;
            }

            return $user;
        }

        $this->markTestSkipped('Tidak ada pengguna dengan profil lengkap pada database pengujian.');
    }

    public function test_daftar_ujian_hanya_menampilkan_ujian_yang_ditugaskan(): void
    {
        // Ujian lain yang peserta ini tidak diberi.
        $ujianLain = Ujian::create([
            'kode' => 'UJN-LAIN-'.uniqid(),
            'judul' => 'Ujian Milik Orang Lain',
            'durasi_menit' => 10,
            'status' => 'terbit',
            'created_by' => User::where('role', 'superadmin')->value('id'),
        ]);

        $response = $this->actingAs($this->peserta)->get(route('ujian.index'));
        $response->assertOk();
        $response->assertSee($this->ujian->judul);
        $response->assertDontSee($ujianLain->judul);
    }

    public function test_peserta_ditunjuk_dapat_membuka_halaman_pengerjaan(): void
    {
        $response = $this->actingAs($this->peserta)->get(route('ujian.kerjakan', $this->ujian->id));
        $response->assertOk();
        $response->assertSee('Ibu kota Indonesia adalah?');

        // Sesi harus terbentuk dengan batas waktu sesuai durasi.
        $sesi = UjianPeserta::where('ujian_id', $this->ujian->id)
            ->where('user_id', $this->peserta->id)
            ->first();

        $this->assertSame('berjalan', $sesi->status);
        $this->assertNotNull($sesi->ends_at);
    }

    public function test_kunci_jawaban_tidak_pernah_dikirim_ke_browser(): void
    {
        $response = $this->actingAs($this->peserta)->get(route('ujian.kerjakan', $this->ujian->id));

        $response->assertOk();
        $response->assertSee('Ibu kota Indonesia adalah?');
        // Kolom kunci tidak boleh muncul sama sekali pada payload peserta.
        $response->assertDontSee('benar', false);
    }

    public function test_peserta_yang_tidak_ditunjuk_ditolak(): void
    {
        $this->actingAs($this->bukanPeserta)
            ->get(route('ujian.kerjakan', $this->ujian->id))
            ->assertNotFound();
    }

    public function test_menyimpan_jawaban_dalam_satu_batch(): void
    {
        $this->actingAs($this->peserta)->get(route('ujian.kerjakan', $this->ujian->id));

        $response = $this->actingAs($this->peserta)->postJson(route('ujian.jawaban', $this->ujian->id), [
            'jawaban' => [
                $this->soalPg->id => ['pilihan_id' => $this->pilihanBenar->id],
                $this->soalEssay->id => ['esai' => 'Pelayanan harus responsif dan ramah.'],
            ],
        ]);

        $response->assertOk()->assertJson(['saved' => true]);

        $this->assertDatabaseHas('ujian_jawaban', [
            'user_id' => $this->peserta->id,
            'soal_id' => $this->soalPg->id,
            'pilihan_id' => $this->pilihanBenar->id,
        ]);

        $this->assertDatabaseHas('ujian_jawaban', [
            'user_id' => $this->peserta->id,
            'soal_id' => $this->soalEssay->id,
            'esai' => 'Pelayanan harus responsif dan ramah.',
        ]);
    }

    public function test_pilihan_dari_soal_lain_ditolak(): void
    {
        $soalLain = UjianSoal::create([
            'ujian_id' => $this->ujian->id,
            'tipe' => 'pilihan_ganda',
            'pertanyaan' => 'Soal kedua?',
            'poin' => 10,
            'urut' => 3,
        ]);

        $pilihanLain = UjianPilihan::create([
            'soal_id' => $soalLain->id,
            'label' => 'A',
            'teks' => 'Opsi soal kedua',
            'benar' => false,
            'urut' => 0,
        ]);

        $this->actingAs($this->peserta)->get(route('ujian.kerjakan', $this->ujian->id));

        $this->actingAs($this->peserta)->postJson(route('ujian.jawaban', $this->ujian->id), [
            'jawaban' => [
                $this->soalPg->id => ['pilihan_id' => $pilihanLain->id],
            ],
        ])->assertOk();

        $this->assertDatabaseMissing('ujian_jawaban', [
            'user_id' => $this->peserta->id,
            'soal_id' => $this->soalPg->id,
            'pilihan_id' => $pilihanLain->id,
        ]);
    }

    public function test_submit_menghitung_nilai_dan_menutup_sesi(): void
    {
        $this->actingAs($this->peserta)->get(route('ujian.kerjakan', $this->ujian->id));

        $this->actingAs($this->peserta)->postJson(route('ujian.jawaban', $this->ujian->id), [
            'jawaban' => [
                $this->soalPg->id => ['pilihan_id' => $this->pilihanBenar->id],
            ],
        ])->assertOk();

        $this->actingAs($this->peserta)
            ->postJson(route('ujian.submit', $this->ujian->id))
            ->assertOk()
            ->assertJson(['saved' => true])
            ->assertJsonStructure(['redirect']);

        $sesi = UjianPeserta::where('ujian_id', $this->ujian->id)
            ->where('user_id', $this->peserta->id)
            ->first();

        $this->assertSame('selesai', $sesi->status);
        $this->assertNotNull($sesi->submitted_at);

        // 20 poin benar dari total 50 poin.
        $this->assertEquals(40.0, (float) $sesi->nilai);

        // Skor pilihan ganda tersimpan pada baris jawaban.
        $this->assertEquals(20.0, (float) UjianJawaban::where('user_id', $this->peserta->id)
            ->where('soal_id', $this->soalPg->id)
            ->value('skor'));
    }

    public function test_halaman_hasil_menampilkan_pembahasan(): void
    {
        $this->actingAs($this->peserta)->get(route('ujian.kerjakan', $this->ujian->id));

        $this->actingAs($this->peserta)->postJson(route('ujian.submit', $this->ujian->id))->assertOk();

        $response = $this->actingAs($this->peserta)->get(route('ujian.hasil', $this->ujian->id));
        $response->assertOk();
        $response->assertSee('Ibu kota Indonesia adalah?');
        $response->assertSee('Kunci Jawaban');
    }

    public function test_sesi_yang_sudah_selesai_diarahkan_ke_halaman_hasil(): void
    {
        $this->actingAs($this->peserta)->get(route('ujian.kerjakan', $this->ujian->id));

        $this->actingAs($this->peserta)->postJson(route('ujian.submit', $this->ujian->id))->assertOk();

        $this->actingAs($this->peserta)
            ->get(route('ujian.kerjakan', $this->ujian->id))
            ->assertRedirect(route('ujian.hasil', $this->ujian->id));
    }

    public function test_admin_dapat_membuka_halaman_manajemen_ujian(): void
    {
        $admin = User::where('role', 'superadmin')->firstOrFail();

        $this->actingAs($admin)->get(route('admin.ujian.index'))->assertOk();
        $this->actingAs($admin)->get(route('admin.ujian.edit', $this->ujian->id))->assertOk();
        $this->actingAs($admin)->get(route('admin.ujian.hasil', $this->ujian->id))->assertOk();
    }

    public function test_admin_dapat_mengunggah_cover_ujian(): void
    {
        $admin = User::where('role', 'superadmin')->firstOrFail();

        Storage::fake('public');

        $cover = UploadedFile::fake()->image('cover.jpg', 800, 500);

        $this->actingAs($admin)->put(route('admin.ujian.update', $this->ujian->id), [
            'kode' => $this->ujian->kode,
            'judul' => $this->ujian->judul,
            'durasi_menit' => $this->ujian->durasi_menit,
            'status' => $this->ujian->status,
            'acak_soal' => 0,
            'cover' => $cover,
        ])->assertRedirect(route('admin.ujian.edit', $this->ujian->id));

        $this->ujian->refresh();

        $this->assertNotNull($this->ujian->cover_path, 'Cover ujian tidak tersimpan.');
        Storage::disk('public')->assertExists($this->ujian->cover_path);
    }

    public function test_admin_dapat_menghapus_cover_ujian(): void
    {
        $admin = User::where('role', 'superadmin')->firstOrFail();

        Storage::fake('public');

        $path = Storage::disk('public')->put('ujian/cover/awal.webp', 'isi');
        $this->ujian->update(['cover_path' => $path]);

        $this->actingAs($admin)->put(route('admin.ujian.update', $this->ujian->id), [
            'kode' => $this->ujian->kode,
            'judul' => $this->ujian->judul,
            'durasi_menit' => $this->ujian->durasi_menit,
            'status' => $this->ujian->status,
            'acak_soal' => 0,
            'remove_cover' => 1,
        ])->assertRedirect(route('admin.ujian.edit', $this->ujian->id));

        $this->ujian->refresh();

        $this->assertNull($this->ujian->cover_path, 'Cover ujian tidak terhapus.');
        Storage::disk('public')->assertMissing($path);
    }

    public function test_cover_ujian_tampil_pada_daftar_ujian_peserta(): void
    {
        Storage::fake('public');

        $path = 'ujian/cover/uji.webp';
        Storage::disk('public')->put($path, 'isi');
        $this->ujian->update(['cover_path' => $path]);

        $response = $this->actingAs($this->peserta)->get(route('ujian.index'));

        $response->assertOk();
        $response->assertSee('ujian/cover/uji.webp');
    }

    public function test_admin_bisa_mencari_peserta_berdasarkan_nomor_induk(): void
    {
        $admin = User::where('role', 'superadmin')->firstOrFail();

        $target = User::whereNotNull('nomor_induk')->where('nomor_induk', '!=', '')->firstOrFail();

        $response = $this->actingAs($admin)
            ->getJson(route('admin.ujian.peserta.cari', $this->ujian->id).'?q='.$target->nomor_induk);

        $response->assertOk();

        $results = collect($response->json('results'));

        $this->assertTrue(
            $results->contains('id', $target->id),
            'Peserta tidak ditemukan saat dicari memakai nomor induk.'
        );

        // Nomor induk yang dikembalikan harus berasal dari kolom nomor_induk.
        $cocok = $results->firstWhere('id', $target->id);
        $this->assertSame($target->nomor_induk, $cocok['nomor_induk']);
    }

    public function test_admin_bisa_mencari_peserta_berdasarkan_nama(): void
    {
        $admin = User::where('role', 'superadmin')->firstOrFail();

        $response = $this->actingAs($admin)
            ->getJson(route('admin.ujian.peserta.cari', $this->ujian->id).'?q='.$this->peserta->name);

        $response->assertOk();

        $results = collect($response->json('results'));

        $this->assertTrue(
            $results->contains('id', $this->peserta->id),
            'Peserta tidak ditemukan saat dicari memakai nama.'
        );
    }

    public function test_admin_dapat_melihat_jawaban_peserta(): void
    {
        $admin = User::where('role', 'superadmin')->firstOrFail();

        UjianJawaban::create([
            'ujian_id' => $this->ujian->id,
            'soal_id' => $this->soalPg->id,
            'user_id' => $this->peserta->id,
            'pilihan_id' => $this->pilihanSalah->id,
            'dijawab_at' => now(),
        ]);

        $response = $this->actingAs($admin)->get(
            route('admin.ujian.peserta.jawaban', [$this->ujian->id, $this->peserta->id])
        );

        $response->assertOk();
        $response->assertSee($this->peserta->name);
        $response->assertSee($this->soalPg->pertanyaan);
        $response->assertSee($this->pilihanSalah->teks);
        $response->assertSee($this->pilihanBenar->teks);
    }

    public function test_halaman_jawaban_peserta_menolak_peserta_yang_tidak_terdaftar(): void
    {
        $admin = User::where('role', 'superadmin')->firstOrFail();

        $this->actingAs($admin)->get(
            route('admin.ujian.peserta.jawaban', [$this->ujian->id, $this->bukanPeserta->id])
        )->assertNotFound();
    }

    public function test_admin_dapat_mereset_jawaban_peserta(): void
    {
        $admin = User::where('role', 'superadmin')->firstOrFail();

        UjianJawaban::create([
            'ujian_id' => $this->ujian->id,
            'soal_id' => $this->soalPg->id,
            'user_id' => $this->peserta->id,
            'pilihan_id' => $this->pilihanBenar->id,
            'skor' => 20,
            'dijawab_at' => now(),
        ]);

        DB::table('ujian_pelanggaran')->insert([
            'ujian_id' => $this->ujian->id,
            'user_id' => $this->peserta->id,
            'jenis' => 'tab_baru',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        UjianPeserta::where('ujian_id', $this->ujian->id)
            ->where('user_id', $this->peserta->id)
            ->update([
                'status' => 'selesai',
                'started_at' => now()->subMinutes(30),
                'ends_at' => now(),
                'submitted_at' => now(),
                'nilai' => 100,
            ]);

        $this->actingAs($admin)
            ->post(route('admin.ujian.peserta.reset', [$this->ujian->id, $this->peserta->id]))
            ->assertRedirect();

        $this->assertSame(
            0,
            UjianJawaban::where('ujian_id', $this->ujian->id)->where('user_id', $this->peserta->id)->count(),
            'Jawaban peserta belum dihapus.'
        );

        $this->assertSame(
            0,
            DB::table('ujian_pelanggaran')->where('ujian_id', $this->ujian->id)->where('user_id', $this->peserta->id)->count(),
            'Riwayat pelanggaran peserta belum dihapus.'
        );

        $peserta = UjianPeserta::where('ujian_id', $this->ujian->id)
            ->where('user_id', $this->peserta->id)
            ->first();

        $this->assertSame('belum_mulai', $peserta->status);
        $this->assertNull($peserta->nilai);
        $this->assertNull($peserta->started_at);
        $this->assertNull($peserta->ends_at);
        $this->assertNull($peserta->submitted_at);
    }

    public function test_reset_jawaban_ditolak_saat_peserta_sedang_berjalan(): void
    {
        $admin = User::where('role', 'superadmin')->firstOrFail();

        UjianPeserta::where('ujian_id', $this->ujian->id)
            ->where('user_id', $this->peserta->id)
            ->update(['status' => 'berjalan', 'started_at' => now(), 'ends_at' => now()->addMinutes(10)]);

        $this->actingAs($admin)
            ->post(route('admin.ujian.peserta.reset', [$this->ujian->id, $this->peserta->id]))
            ->assertRedirect();

        $this->assertSame(
            'berjalan',
            UjianPeserta::where('ujian_id', $this->ujian->id)->where('user_id', $this->peserta->id)->value('status'),
            'Status peserta berubah padahal sesi sedang berjalan.'
        );
    }

    public function test_admin_dapat_mencetak_hasil_ujian(): void
    {
        $admin = User::where('role', 'superadmin')->firstOrFail();

        UjianJawaban::create([
            'ujian_id' => $this->ujian->id,
            'soal_id' => $this->soalPg->id,
            'user_id' => $this->peserta->id,
            'pilihan_id' => $this->pilihanBenar->id,
            'dijawab_at' => now(),
        ]);

        $response = $this->actingAs($admin)->get(route('admin.ujian.cetak', $this->ujian->id));

        $response->assertOk();
        $response->assertSee($this->ujian->kode);
        $response->assertSee($this->ujian->judul);
        $response->assertSee($this->peserta->name);
        $response->assertSee($this->soalPg->pertanyaan);
    }

    public function test_halaman_hasil_tidak_membocorkan_kunci_sebelum_ujian_selesai(): void
    {
        // Peserta belum memulai ujian.
        $response = $this->actingAs($this->peserta)->get(route('ujian.hasil', $this->ujian->id));
        $response->assertRedirect(route('ujian.kerjakan', $this->ujian->id));
        $response->assertDontSee($this->pilihanBenar->teks);

        // Peserta sedang mengerjakan ujian.
        $this->actingAs($this->peserta)->get(route('ujian.kerjakan', $this->ujian->id))->assertOk();

        $sesi = UjianPeserta::where('ujian_id', $this->ujian->id)
            ->where('user_id', $this->peserta->id)
            ->first();
        $this->assertSame('berjalan', $sesi->status);

        $response = $this->actingAs($this->peserta)->get(route('ujian.hasil', $this->ujian->id));
        $response->assertRedirect(route('ujian.kerjakan', $this->ujian->id));
        $response->assertDontSee($this->pilihanBenar->teks);

        // Setelah ujian ditutup, halaman hasil boleh dibuka.
        $this->actingAs($this->peserta)->postJson(route('ujian.submit', $this->ujian->id))->assertOk();

        $this->actingAs($this->peserta)->get(route('ujian.hasil', $this->ujian->id))->assertOk();
    }

    public function test_sesi_yang_kedaluwarsa_langsung_menutup_dan_membuka_halaman_hasil(): void
    {
        // Sesi sudah berjalan tetapi batas waktu sudah lewat.
        UjianPeserta::where('ujian_id', $this->ujian->id)
            ->where('user_id', $this->peserta->id)
            ->update([
                'status' => 'berjalan',
                'started_at' => now()->subHour(),
                'ends_at' => now()->subMinute(),
            ]);

        $this->actingAs($this->peserta)
            ->get(route('ujian.kerjakan', $this->ujian->id))
            ->assertRedirect(route('ujian.hasil', $this->ujian->id));

        $sesi = UjianPeserta::where('ujian_id', $this->ujian->id)
            ->where('user_id', $this->peserta->id)
            ->first();
        $this->assertSame('selesai', $sesi->status);

        // Halaman hasil harus terbuka, tidak berputar kembali ke pengerjaan.
        $this->actingAs($this->peserta)->get(route('ujian.hasil', $this->ujian->id))->assertOk();
    }

    public function test_urutan_soal_tetap_urut_bila_acak_soal_nonaktif(): void
    {
        $response = $this->actingAs($this->peserta)->get(route('ujian.kerjakan', $this->ujian->id));
        $response->assertOk();

        $ids = array_column($response->viewData('soal'), 'id');
        $this->assertSame([$this->soalPg->id, $this->soalEssay->id], $ids);
    }

    public function test_urutan_soal_diacak_bila_acak_soal_aktif(): void
    {
        $soalTambahan = [];
        foreach (range(3, 6) as $urut) {
            $soalTambahan[] = UjianSoal::create([
                'ujian_id' => $this->ujian->id,
                'tipe' => 'pilihan_ganda',
                'pertanyaan' => 'Soal tambahan nomor '.$urut,
                'poin' => 10,
                'urut' => $urut,
            ])->id;
        }

        $this->ujian->update(['acak_soal' => true]);
        Cache::forget("ujian:{$this->ujian->id}:soal");

        $response = $this->actingAs($this->peserta)->get(route('ujian.kerjakan', $this->ujian->id));
        $response->assertOk();

        $soal = $response->viewData('soal');
        $ids = array_column($soal, 'id');
        $canonical = [$this->soalPg->id, $this->soalEssay->id, ...$soalTambahan];

        // Seluruh soal tetap ada, hanya urutannya yang diacak.
        $this->assertEqualsCanonicalizing($canonical, $ids);

        // Urutan yang sama harus muncul lagi saat halaman dimuat ulang.
        $ulang = $this->actingAs($this->peserta)->get(route('ujian.kerjakan', $this->ujian->id));
        $this->assertSame($ids, array_column($ulang->viewData('soal'), 'id'));

        // Label pilihan diurutkan ulang mengikuti posisi tampil.
        foreach ($soal as $s) {
            $labels = array_column($s['pilihan'], 'label');
            $this->assertSame(
                array_map(fn ($i) => chr(65 + $i), array_keys($labels)),
                $labels
            );
        }
    }

    public function test_penilaian_tetap_benar_saat_soal_diacak(): void
    {
        $this->ujian->update(['acak_soal' => true]);
        Cache::forget("ujian:{$this->ujian->id}:soal");

        $this->actingAs($this->peserta)->get(route('ujian.kerjakan', $this->ujian->id));

        $this->actingAs($this->peserta)->postJson(route('ujian.jawaban', $this->ujian->id), [
            'jawaban' => [
                $this->soalPg->id => ['pilihan_id' => $this->pilihanBenar->id],
            ],
        ])->assertOk();

        $this->actingAs($this->peserta)->postJson(route('ujian.submit', $this->ujian->id))->assertOk();

        $sesi = UjianPeserta::where('ujian_id', $this->ujian->id)
            ->where('user_id', $this->peserta->id)
            ->first();

        $this->assertSame('selesai', $sesi->status);
        // 20 poin benar dari total 50 poin.
        $this->assertEquals(40.0, (float) $sesi->nilai);
    }

    public function test_acak_soal_konsisten_untuk_seed_yang_sama_dan_berbeda_untuk_seed_lain(): void
    {
        $items = range(1, 6);

        $a = $this->acakArray($items, 'seed-a');
        $b = $this->acakArray($items, 'seed-a');
        $c = $this->acakArray($items, 'seed-b');

        $this->assertSame($a, $b, 'Seed yang sama harus menghasilkan urutan yang sama.');
        $this->assertEqualsCanonicalizing($items, $a, 'Hasil acak harus memuat seluruh item.');
        $this->assertNotSame($a, $c, 'Seed yang berbeda harus menghasilkan urutan yang berbeda.');
    }

    public function test_halaman_pengerjaan_memuat_penjaga_tab_dan_layar_penh(): void
    {
        $response = $this->actingAs($this->peserta)->get(route('ujian.kerjakan', $this->ujian->id));

        $response->assertOk();
        $response->assertSee('Masuk Layar Penuh');
        $response->assertSee('Lanjut tanpa layar penuh');

        // Penjaga di sisi peramban harus ikut terkirim ke halaman.
        $response->assertSee('X-Ujian-Perangkat', false);
        $response->assertSee('perpindahan_fokus', false);
        $response->assertSee('keluar_layar_penh', false);
        $response->assertSee('sesi_ganda', false);
    }

    public function test_pelanggaran_bisa_dicatat_dan_ditampilkan_di_halaman_hasil(): void
    {
        $this->actingAs($this->peserta)->get(route('ujian.kerjakan', $this->ujian->id))->assertOk();

        $response = $this->actingAs($this->peserta)->postJson(route('ujian.pelanggaran', $this->ujian->id), [
            'jenis' => 'perpindahan_fokus',
            'keterangan' => 'Membuka tab lain',
        ]);

        $response->assertOk()->assertJson(['ok' => true, 'total' => 1]);

        $this->assertDatabaseHas('ujian_pelanggaran', [
            'ujian_id' => $this->ujian->id,
            'user_id' => $this->peserta->id,
            'jenis' => 'perpindahan_fokus',
            'keterangan' => 'Membuka tab lain',
        ]);

        // Admin melihat rekap pelanggaran di halaman hasil ujian.
        $admin = User::where('role', 'superadmin')->firstOrFail();

        $this->actingAs($admin)
            ->get(route('admin.ujian.hasil', $this->ujian->id))
            ->assertOk()
            ->assertSee('Berpindah tab / jendela')
            ->assertSee('1 kali');
    }

    public function test_jenis_pelanggaran_tidak_dikenal_ditolak(): void
    {
        $this->actingAs($this->peserta)->get(route('ujian.kerjakan', $this->ujian->id))->assertOk();

        $this->actingAs($this->peserta)
            ->postJson(route('ujian.pelanggaran', $this->ujian->id), ['jenis' => 'ngawur'])
            ->assertStatus(422);

        $this->assertSame(0, DB::table('ujian_pelanggaran')->where('ujian_id', $this->ujian->id)->count());
    }

    public function test_pelanggaran_ditolak_setelah_sesi_selesai(): void
    {
        $this->actingAs($this->peserta)->get(route('ujian.kerjakan', $this->ujian->id))->assertOk();
        $this->actingAs($this->peserta)->postJson(route('ujian.submit', $this->ujian->id))->assertOk();

        $this->actingAs($this->peserta)
            ->postJson(route('ujian.pelanggaran', $this->ujian->id), ['jenis' => 'tab_baru'])
            ->assertStatus(409);

        $this->assertSame(0, DB::table('ujian_pelanggaran')->where('ujian_id', $this->ujian->id)->count());
    }

    public function test_perangkat_pertama_mendaftarkan_sesi_dan_perangkat_kedua_tercatat_sebagai_sesi_ganda(): void
    {
        $this->actingAs($this->peserta)->get(route('ujian.kerjakan', $this->ujian->id))->assertOk();

        // Perangkat pertama terdaftar pada sesi.
        $this->actingAs($this->peserta)
            ->postJson(route('ujian.perangkat', $this->ujian->id), [], ['X-Ujian-Perangkat' => 'perangkat-a'])
            ->assertOk()
            ->assertJson(['ok' => true]);

        $sesi = UjianPeserta::where('ujian_id', $this->ujian->id)
            ->where('user_id', $this->peserta->id)
            ->first();

        $this->assertNotNull($sesi->sidik_perangkat);

        // Perangkat yang sama tidak dianggap pelanggaran.
        $this->actingAs($this->peserta)
            ->postJson(route('ujian.perangkat', $this->ujian->id), [], ['X-Ujian-Perangkat' => 'perangkat-a'])
            ->assertOk();

        $this->assertSame(0, DB::table('ujian_pelanggaran')->where('ujian_id', $this->ujian->id)->count());

        // Perangkat berbeda dicatat sebagai sesi ganda.
        $this->actingAs($this->peserta)
            ->postJson(route('ujian.perangkat', $this->ujian->id), [], ['X-Ujian-Perangkat' => 'perangkat-b'])
            ->assertStatus(409);

        $this->assertDatabaseHas('ujian_pelanggaran', [
            'ujian_id' => $this->ujian->id,
            'user_id' => $this->peserta->id,
            'jenis' => 'sesi_ganda',
        ]);
    }

    public function test_peserta_yang_bukan_peserta_ujian_tidak_boleh_mencatat_pelanggaran(): void
    {
        $this->actingAs($this->bukanPeserta)
            ->postJson(route('ujian.pelanggaran', $this->ujian->id), ['jenis' => 'tab_baru'])
            ->assertNotFound();
    }

    /**
     * Panggil helper pengacak pada controller secara langsung.
     */
    private function acakArray(array $items, string $seed): array
    {
        $method = new \ReflectionMethod(UjianController::class, 'acakArray');
        $method->setAccessible(true);

        return $method->invoke(new UjianController, $items, $seed);
    }
}
