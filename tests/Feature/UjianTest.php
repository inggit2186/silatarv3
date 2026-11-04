<?php

namespace Tests\Feature;

use App\Models\Ujian;
use App\Models\UjianJawaban;
use App\Models\UjianPeserta;
use App\Models\UjianPilihan;
use App\Models\UjianSoal;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
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

        \Illuminate\Support\Facades\Cache::forget("ujian:{$this->ujian->id}:soal");
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
}
