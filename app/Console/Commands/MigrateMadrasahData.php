<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrateMadrasahData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'madrasah:migrate-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate existing madrasah data from ktd_department to ktd_madrasah';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting madrasah data migration...');

        DB::beginTransaction();

        try {
            // 1. Get all madrasah entries from ktd_department
            $madrasahs = DB::table('ktd_department')
                ->whereIn('kategori', ['mi', 'mts', 'ma', 'man', 'mtsn', 'min', 'ra'])
                ->where('status', '!=', 0)
                ->get();

            $this->info("Found {$madrasahs->count()} madrasah entries in ktd_department");

            $deptToMadrasah = []; // Mapping: dept_id => madrasah_id

            foreach ($madrasahs as $dept) {
                // Check if madrasah already exists (by NSM)
                $existing = DB::table('ktd_madrasah')
                    ->where('nsm', $dept->nsm)
                    ->where('dept_id', $dept->id)
                    ->first();

                if ($existing) {
                    $deptToMadrasah[$dept->id] = $existing->id;
                    $this->line("  SKIP: {$dept->nama} (NSM: {$dept->nsm}) - already exists");
                    continue;
                }

                // Insert new madrasah record
                $madrasahId = DB::table('ktd_madrasah')->insertGetId([
                    'dept_id' => $dept->id,
                    'nama' => $dept->nama,
                    'nsm' => $dept->nsm,
                    'npsm' => $dept->npsm,
                    'kategori' => $dept->kategori,
                    'status_lembaga' => $dept->status_lembaga,
                    'jalan' => $dept->jalan,
                    'jorong' => $dept->jorong,
                    'nagari' => $dept->nagari,
                    'kecamatan' => $dept->kecamatan,
                    'koordinat' => $dept->koordinat,
                    'telepon' => $dept->telepon,
                    'email' => $dept->email,
                    'website' => $dept->website,
                    'waktu_belajar' => $dept->waktu_belajar,
                    'visi' => $dept->visi,
                    'sk_pendirian' => $dept->sk_pendirian,
                    'tanggal_sk' => $dept->tanggal_sk,
                    'komite_lembaga' => $dept->komite_lembaga,
                    'akreditasi' => $dept->akreditasi,
                    'tanggal_akreditasi' => $dept->tanggal_akreditasi,
                    'status_kkm' => $dept->status_kkm,
                    'jarak_pusat_provinsi' => $dept->jarak_pusat_provinsi,
                    'jarak_pusat_kabupaten' => $dept->jarak_pusat_kabupaten,
                    'jarak_kecamatan' => $dept->jarak_kecamatan,
                    'jarak_kanwil_kemenag' => $dept->jarak_kanwil_kemenag,
                    'jarak_kemenag_kab' => $dept->jarak_kemenag_kab,
                    'jarak_kua' => $dept->jarak_kua,
                    'jarak_ra_terdekat' => $dept->jarak_ra_terdekat,
                    'jarak_mi_terdekat' => $dept->jarak_mi_terdekat,
                    'jarak_mts_terdekat' => $dept->jarak_mts_terdekat,
                    'jarak_ma_terdekat' => $dept->jarak_ma_terdekat,
                    'jarak_pontren_terdekat' => $dept->jarak_pontren_terdekat,
                    'jarak_tk_terdekat' => $dept->jarak_tk_terdekat,
                    'jarak_sd_terdekat' => $dept->jarak_sd_terdekat,
                    'jarak_smp_terdekat' => $dept->jarak_smp_terdekat,
                    'jarak_sma_terdekat' => $dept->jarak_sma_terdekat,
                    'status' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $deptToMadrasah[$dept->id] = $madrasahId;
                $this->line("  CREATED: {$dept->nama} => madrasah_id={$madrasahId}");
            }

            // 2. Update users table - assign madrasah_id
            $updatedUsers = 0;
            foreach ($deptToMadrasah as $deptId => $madrasahId) {
                $count = DB::table('users')
                    ->where('dept_id', $deptId)
                    ->whereNull('madrasah_id')
                    ->update(['madrasah_id' => $madrasahId]);
                $updatedUsers += $count;
            }
            $this->info("Updated {$updatedUsers} users with madrasah_id");

            // 3. Update tenaga_ktd table - assign madrasah_id
            $updatedTenaga = 0;
            foreach ($deptToMadrasah as $deptId => $madrasahId) {
                $count = DB::table('tenaga_ktd')
                    ->where('dept_id', $deptId)
                    ->whereNull('madrasah_id')
                    ->update(['madrasah_id' => $madrasahId]);
                $updatedTenaga += $count;
            }
            $this->info("Updated {$updatedTenaga} tenaga_ktd records with madrasah_id");

            // 4. Update ktd_laporan_semester_madrasah
            $updatedSemester = 0;
            foreach ($deptToMadrasah as $deptId => $madrasahId) {
                $count = DB::table('ktd_laporan_semester_madrasah')
                    ->where('dept_id', $deptId)
                    ->whereNull('madrasah_id')
                    ->update(['madrasah_id' => $madrasahId]);
                $updatedSemester += $count;
            }
            $this->info("Updated {$updatedSemester} semester reports with madrasah_id");

            // 5. Update ktd_laporan_bulanan_madrasah
            $updatedBulanan = 0;
            foreach ($deptToMadrasah as $deptId => $madrasahId) {
                $count = DB::table('ktd_laporan_bulanan_madrasah')
                    ->where('dept_id', $deptId)
                    ->whereNull('madrasah_id')
                    ->update(['madrasah_id' => $madrasahId]);
                $updatedBulanan += $count;
            }
            $this->info("Updated {$updatedBulanan} bulanan reports with madrasah_id");

            DB::commit();
            $this->info('Migration completed successfully!');
            $this->newLine();
            $this->info('Summary:');
            $this->info("  - Madrasah entries created: " . count($deptToMadrasah));
            $this->info("  - Users updated: {$updatedUsers}");
            $this->info("  - Tenaga KTD updated: {$updatedTenaga}");
            $this->info("  - Semester reports updated: {$updatedSemester}");
            $this->info("  - Bulanan reports updated: {$updatedBulanan}");

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Migration failed: " . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
