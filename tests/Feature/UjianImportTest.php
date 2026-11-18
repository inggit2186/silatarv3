<?php

namespace Tests\Feature;

use App\Models\Ujian;
use App\Models\UjianPilihan;
use App\Models\UjianSoal;
use App\Models\User;
use App\Services\UjianSoalImportService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Tests\TestCase;

class UjianImportTest extends TestCase
{
    use DatabaseTransactions;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::where('role', 'superadmin')->firstOrFail();
    }

    public function test_admin_dapat_impor_soal_pilihan_ganda_dan_esai(): void
    {
        $ujian = $this->buatUjian();

        $response = $this->actingAs($this->admin)->post(route('admin.ujian.soal.import', $ujian->id), [
            'file_soal' => $this->buatFileExcel([
                ['pilihan_ganda', 'Ibu kota Indonesia adalah?', 'Pilih yang tepat', 20,
                    'Jakarta', 'Bandung', 'Surabaya', '', '', 'A'],
                ['essay', 'Jelaskan prinsip pelayanan publik.', '', 30,
                    '', '', '', '', '', ''],
            ]),
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertSame(2, UjianSoal::where('ujian_id', $ujian->id)->count());

        $pg = UjianSoal::where('ujian_id', $ujian->id)->where('tipe', 'pilihan_ganda')->firstOrFail();
        $this->assertSame('Ibu kota Indonesia adalah?', $pg->pertanyaan);
        $this->assertSame('Pilih yang tepat', $pg->petunjuk);
        $this->assertSame(20, (int) $pg->poin);

        $pilihan = $pg->pilihan()->orderBy('urut')->get();
        $this->assertCount(3, $pilihan);
        $this->assertSame(['A', 'B', 'C'], $pilihan->pluck('label')->all());
        $this->assertTrue((bool) $pilihan[0]->benar);
        $this->assertFalse((bool) $pilihan[1]->benar);
        $this->assertSame('Jakarta', $pilihan[0]->teks);

        $essay = UjianSoal::where('ujian_id', $ujian->id)->where('tipe', 'essay')->firstOrFail();
        $this->assertSame(30, (int) $essay->poin);
        $this->assertCount(0, $essay->pilihan);
    }

    public function test_soal_import_ditambahkan_setelah_soal_yang_sudah_ada(): void
    {
        $ujian = $this->buatUjian();

        $soalLama = UjianSoal::create([
            'ujian_id' => $ujian->id,
            'tipe' => 'essay',
            'pertanyaan' => 'Soal lama',
            'poin' => 10,
            'urut' => 1,
        ]);

        $this->actingAs($this->admin)->post(route('admin.ujian.soal.import', $ujian->id), [
            'file_soal' => $this->buatFileExcel([
                ['essay', 'Soal baru dari Excel', '', 5, '', '', '', '', '', ''],
            ]),
        ])->assertRedirect();

        $soalBaru = UjianSoal::where('ujian_id', $ujian->id)->where('pertanyaan', 'Soal baru dari Excel')->firstOrFail();
        $this->assertSame(2, (int) $soalBaru->urut);
        $this->assertSame(1, (int) $soalLama->urut);
    }

    public function test_impor_dibatalkan_bila_kunci_jawaban_tidak_ada(): void
    {
        $ujian = $this->buatUjian();

        $response = $this->actingAs($this->admin)->post(route('admin.ujian.soal.import', $ujian->id), [
            'file_soal' => $this->buatFileExcel([
                ['pilihan_ganda', 'Soal tanpa kunci', '', 10, 'opsi satu', 'opsi dua', '', '', '', ''],
            ]),
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertNotEmpty(session('import_errors'));

        $this->assertSame(0, UjianSoal::where('ujian_id', $ujian->id)->count());
    }

    public function test_impor_dibatalkan_bila_kunci_tidak_sesuai_opsi(): void
    {
        $ujian = $this->buatUjian();

        $this->actingAs($this->admin)->post(route('admin.ujian.soal.import', $ujian->id), [
            'file_soal' => $this->buatFileExcel([
                ['pilihan_ganda', 'Kunci di luar jangkauan', '', 10, 'opsi satu', 'opsi dua', '', '', '', 'E'],
            ]),
        ])->assertRedirect();

        $this->assertSame(0, UjianSoal::where('ujian_id', $ujian->id)->count());
    }

    public function test_baris_contoh_dari_template_dilewati(): void
    {
        $ujian = $this->buatUjian();

        $this->actingAs($this->admin)->post(route('admin.ujian.soal.import', $ujian->id), [
            'file_soal' => $this->buatFileExcel([
                ['pilihan_ganda', '[CONTOH] Pertanyaan contoh', '', 10, 'a', 'b', '', '', '', 'A'],
                ['essay', 'Pertanyaan asli', '', 10, '', '', '', '', '', ''],
            ]),
        ])->assertRedirect();

        $this->assertSame(1, UjianSoal::where('ujian_id', $ujian->id)->count());
        $this->assertDatabaseHas('ujian_soal', [
            'ujian_id' => $ujian->id,
            'pertanyaan' => 'Pertanyaan asli',
        ]);
    }

    public function test_import_dari_halaman_tambah_ujian_membuat_soal_otomatis(): void
    {
        $kode = 'UJN-IMP-'.uniqid();

        $response = $this->actingAs($this->admin)->post(route('admin.ujian.store'), [
            'kode' => $kode,
            'judul' => 'Ujian Hasil Import',
            'durasi_menit' => 45,
            'status' => 'draft',
            'acak_soal' => 0,
            'file_soal' => $this->buatFileExcel([
                ['pilihan_ganda', 'Soal dari halaman tambah', '', 10, 'satu', 'dua', '', '', '', 'B'],
            ]),
        ]);

        $response->assertRedirect();

        $ujian = Ujian::where('kode', $kode)->firstOrFail();
        $this->assertSame(1, UjianSoal::where('ujian_id', $ujian->id)->count());

        $pilihan = UjianPilihan::whereIn('soal_id', UjianSoal::where('ujian_id', $ujian->id)->pluck('id'))->get();
        $this->assertCount(2, $pilihan);
        $this->assertFalse((bool) $pilihan[0]->benar);
        $this->assertTrue((bool) $pilihan[1]->benar);
    }

    public function test_halaman_tambah_ujian_menampilkan_form_import(): void
    {
        $this->actingAs($this->admin)
            ->get(route('admin.ujian.create'))
            ->assertOk()
            ->assertSee('Import Soal dari Excel', false)
            ->assertSee(route('admin.ujian.soal.template'), false);
    }

    public function test_halaman_kelola_ujian_menampilkan_form_import(): void
    {
        $ujian = $this->buatUjian();

        $this->actingAs($this->admin)
            ->get(route('admin.ujian.edit', $ujian->id))
            ->assertOk()
            ->assertSee('Import Soal dari Excel', false)
            ->assertSee(route('admin.ujian.soal.import', $ujian->id), false);
    }

    public function test_template_excel_dapat_diunduh(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.ujian.soal.template'));

        $response->assertOk();
        $this->assertStringContainsString(
            'spreadsheetml',
            (string) $response->headers->get('content-type')
        );
    }

    public function test_template_bisa_dibaca_kembali_oleh_importer(): void
    {
        $service = app(UjianSoalImportService::class);

        $file = new UploadedFile(
            $service->unduhTemplate(),
            'template-import-soal-ujian.xlsx',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            null,
            true
        );

        $hasil = $service->parse($file);

        // Baris contoh pada template harus dilewati, bukan dianggap soal.
        $this->assertEmpty($hasil['rows']);
        $this->assertNotEmpty($hasil['errors']);
        $this->assertStringNotContainsString('Kolom "pertanyaan" tidak ditemukan', implode(' ', $hasil['errors']));
    }

    private function buatUjian(): Ujian
    {
        return Ujian::create([
            'kode' => 'UJN-IMP-'.uniqid(),
            'judul' => 'Ujian Target Import',
            'durasi_menit' => 30,
            'status' => 'draft',
            'created_by' => $this->admin->id,
        ]);
    }

    /**
     * @param  array<int, array<int, mixed>>  $baris
     */
    private function buatFileExcel(array $baris): UploadedFile
    {
        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->fromArray([
            ['tipe', 'pertanyaan', 'petunjuk', 'poin',
                'pilihan_a', 'pilihan_b', 'pilihan_c', 'pilihan_d', 'pilihan_e', 'kunci'],
        ], null, 'A1');

        foreach ($baris as $index => $data) {
            $sheet->fromArray([$data], null, 'A'.($index + 2));
        }

        $lokasi = tempnam(sys_get_temp_dir(), 'soal-').'.xlsx';
        (new Xlsx($spreadsheet))->save($lokasi);

        return new UploadedFile(
            $lokasi,
            'soal-ujian.xlsx',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            null,
            true
        );
    }
}
