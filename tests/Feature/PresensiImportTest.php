<?php

namespace Tests\Feature;

use App\Services\PresensiImportService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Tests\TestCase;

class PresensiImportTest extends TestCase
{
    private string $folder;

    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_induk');
        });
        Schema::create('ktd_presensi', function (Blueprint $table) {
            $table->id();
            foreach (['user_nip', 'tanggal', 'm_absen', 'p_absen', 'm_diff', 'p_diff', 'status', 'keterangan', 'import_batch_id', 'imported_by', 'imported_at', 'import_source', 'm_location'] as $column) {
                $table->string($column)->nullable();
            }
            $table->timestamps();
        });
        DB::table('users')->insert(['nomor_induk' => '198001012000011001']);
        $this->folder = 'storage/framework/testing/presensi-'.uniqid();
        File::ensureDirectoryExists(base_path($this->folder));
    }

    protected function tearDown(): void
    {
        File::deleteDirectory(base_path($this->folder));
        DB::disconnect('sqlite');
        parent::tearDown();
    }

    private function workbook(bool $invalid = false, bool $multipleSheets = false): string
    {
        $book = new Spreadsheet;
        $sheet = $book->getActiveSheet();
        $sheet->fromArray(['NAMA', 'NIP', 'JABATAN', 'TANGGAL', 'HARI', 'JAM MASUK', 'ABSEN MASUK', 'CEPAT TELAT', 'JAM PULANG', 'ABSEN PULANG']);
        $sheet->setCellValueExplicit('B2', $invalid ? '123' : ' 198001012000011001 ', DataType::TYPE_STRING);
        $sheet->setCellValue('D2', 46329);
        $sheet->setCellValue('G2', 0.3125);
        $sheet->setCellValue('H2', 0);
        $sheet->setCellValue('J2', '16:00');
        $sheet->setCellValue('AA2', 'extra');
        if ($multipleSheets) {
            $other = clone $sheet;
            $other->setTitle('Kedua');
            $other->setCellValue('D2', 46330);
            $book->addSheet($other);
        }
        $path = base_path($this->folder.'/input.xlsx');
        (new Xlsx($book))->save($path);
        $book->disconnectWorksheets();

        return $path;
    }

    public function test_serial_values_multisheet_and_columns_after_z_are_imported(): void
    {
        $service = app(PresensiImportService::class);
        $parsed = $service->parseExcel($this->workbook(multipleSheets: true));
        $this->assertTrue($parsed['success']);
        $this->assertCount(2, $parsed['data']);
        $validated = $service->validateData($parsed['data']);
        $this->assertSame(0, $validated['invalid_count']);
        $this->assertSame('07:30:00', $validated['valid_rows'][0]['absen_masuk']);
        $this->assertSame('0', $validated['valid_rows'][0]['m_diff']);
        $result = $service->importToDatabase($validated, 1);
        $this->assertTrue($result['success']);
        $this->assertSame(2, $result['imported_count']);
    }

    public function test_existing_attendance_is_overwritten_without_deleting_unrelated_fields(): void
    {
        $service = app(PresensiImportService::class);
        $parsed = $service->parseExcel($this->workbook());
        $validated = $service->validateData($parsed['data']);
        $service->importToDatabase($validated, 1);
        DB::table('ktd_presensi')->update(['m_absen' => '09:00:00', 'm_location' => 'Lokasi lama']);
        $result = $service->importToDatabase($validated, 1);
        $this->assertSame(1, $result['updated_count']);
        $this->assertSame(0, $result['imported_count']);
        $this->assertSame(1, DB::table('ktd_presensi')->count());
        $this->assertSame('07:30:00', DB::table('ktd_presensi')->value('m_absen'));
        $this->assertSame('Lokasi lama', DB::table('ktd_presensi')->value('m_location'));
        $this->assertSame(0, $service->rollbackImport($result['batch_id'])['deleted_count']);
    }

    public function test_dry_run_never_deletes_or_writes(): void
    {
        $path = $this->workbook();
        $this->artisan('presensi:import', ['--path' => $this->folder, '--dry-run' => true])->assertSuccessful();
        $this->assertFileExists($path);
        $this->assertSame(0, DB::table('ktd_presensi')->count());
    }

    public function test_invalid_rows_preserve_source_and_return_failure(): void
    {
        $path = $this->workbook(invalid: true);
        $this->artisan('presensi:import', ['--path' => $this->folder, '--force' => true])->assertFailed();
        $this->assertFileExists($path);
    }

    public function test_complete_import_deletes_source_unless_keep_files(): void
    {
        $path = $this->workbook();
        $this->artisan('presensi:import', ['--path' => $this->folder, '--force' => true, '--keep-files' => true])->assertSuccessful();
        $this->assertFileExists($path);
        $this->artisan('presensi:import', ['--path' => $this->folder, '--force' => true])->assertSuccessful();
        $this->assertFileDoesNotExist($path);
        $this->assertSame(1, DB::table('ktd_presensi')->count());
    }

    public function test_invalid_dates_times_and_numeric_nip_are_rejected(): void
    {
        $service = app(PresensiImportService::class);
        $row = array_fill(0, 18, null);
        $row[1] = 198001012000011001;
        $row[3] = '2026-02-30';
        $row[6] = '25:00';
        $result = $service->validateData([$row]);
        $this->assertSame(1, $result['invalid_count']);
        $this->assertGreaterThanOrEqual(3, count($result['invalid_rows'][0]['errors']));
    }

    public function test_database_failure_rolls_back_updates_and_inserts(): void
    {
        $service = app(PresensiImportService::class);
        $validated = $service->validateData($service->parseExcel($this->workbook())['data']);
        $bad = $validated['valid_rows'][0];
        $bad['keterangan'] = new \stdClass;
        $validated['valid_rows'][] = $bad;
        $result = $service->importToDatabase($validated, 1);
        $this->assertFalse($result['success']);
        $this->assertSame(0, $result['imported_count']);
        $this->assertSame(0, DB::table('ktd_presensi')->count());
    }
}
