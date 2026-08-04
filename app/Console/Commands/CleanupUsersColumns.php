<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CleanupUsersColumns extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'madrasah:cleanup-users
                            {--list : List columns that can be removed}
                            {--cleanup-all : Remove ALL columns that have been migrated to tenaga_ktd}
                            {--remove-duplicates : Remove duplicate columns (gol, ijazah_*)}
                            {--drop-old-tables : Drop guru_madrasah and pegawai_madrasah tables}
                            {--dry-run : Preview without making changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cleanup unnecessary columns from users table and drop old tables';

    /**
     * Columns that have been migrated to tenaga_ktd (can be safely removed)
     */
    protected array $migratedColumns = [
        'gol' => 'Duplikat dari golongan',
        'ijazah_jurusan' => 'Sudah ada di tenaga_ktd.jurusan',
        'ijazah_fakultas' => 'Sudah ada di tenaga_ktd.fakultas',
        'ijazah_universitas' => 'Sudah ada di tenaga_ktd.universitas',
        'ijazah_pendidikan' => 'Sudah ada di tenaga_ktd.pendidikan',
        'ijazah_tahun_lulus' => 'Sudah ada di tenaga_ktd.tahun_lulus',
        'tmt_cpns' => 'Sudah ada di tenaga_ktd.tmt_cpns',
        'tmt_pns' => 'Sudah ada di tenaga_ktd.tmt_pns',
        'nikah' => 'Sudah ada di tenaga_ktd.nikah',
        'jenis_pjob' => 'Sudah ada di tenaga_ktd.jenis_pjob',
        'pjob' => 'Sudah ada di tenaga_ktd.pjob',
        'req_tunjangan' => 'Sudah ada di tenaga_ktd.req_tunjangan',
        'jml_anak' => 'Sudah ada di tenaga_ktd.jml_anak',
        'kk' => 'Sudah ada di tenaga_ktd.kk',
        'bio' => 'Sudah ada di tenaga_ktd.bio',
        'facebook' => 'Sudah ada di tenaga_ktd.facebook',
        'twitter' => 'Sudah ada di tenaga_ktd.twitter',
        'linkedin' => 'Sudah ada di tenaga_ktd.linkedin',
        'instagram' => 'Sudah ada di tenaga_ktd.instagram',
        'jenis_guru' => 'Sudah ada di tenaga_ktd.jenis_guru',
        'nuptk' => 'Sudah ada di tenaga_ktd.nuptk',
        'npk' => 'Sudah ada di tenaga_ktd.npk',
        'nrg' => 'Sudah ada di tenaga_ktd.nrg',
        'bidang_studi_diajar' => 'Sudah ada di tenaga_ktd.bidang_studi_diajar',
        'serdik' => 'Sudah ada di tenaga_ktd.serdik',
    ];

    /**
     * Columns that might not be needed anymore
     */
    protected array $optionalColumns = [
        'asn_desc' => 'Deskripsi ASN, mungkin tidak diperlukan',
        'kode_tempat_lahir' => 'Kode tempat lahir, tidak digunakan',
        'tanggal_pensiun' => 'Tanggal pensiun, bisa dihitung dari data lain',
        'no_peserta_THK2' => 'THK2, mungkin tidak relevan',
        'status_THK2' => 'THK2 status, mungkin tidak relevan',
        'grade' => 'Grade, mungkin tidak digunakan',
        'tmt_pensiun' => 'TMT Pensiun, bisa dihitung',
        'gaji' => 'Gaji, mungkin sensitif',
        'bank' => 'Bank, mungkin sensitif',
        'rekening' => 'Rekening, mungkin sensitif',
        'harikerja_id' => 'Hari kerja ID, mungkin tidak digunakan',
    ];

    /**
     * Columns that are still used by other systems (DO NOT REMOVE)
     */
    protected array $protectedColumns = [
        'id', 'name', 'email', 'password', 'role', 'dept_id',
        'nomor_induk', 'jk', 'telp', 'alamat', 'pekerjaan', 'status',
        'tempat_lahir', 'tanggal_lahir', 'golongan', 'jabatan',
        'tmt_tugas', 'kgb', 'masa_kerja_tahun', 'masa_kerja_bulan',
        'npwp', 'asn', 'pp', 'nip', 'satker', 'instansi',
        'nomor_induk', 'remember_token', 'email_verified_at',
        'created_at', 'updated_at',
    ];

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('===========================================');
        $this->info('  Cleanup Tabel Users & Tabel Lama');
        $this->info('===========================================');
        $this->newLine();

        $dryRun = $this->option('dry-run');

        if ($dryRun) {
            $this->warn('DRY RUN MODE - Tidak ada perubahan yang akan disimpan.');
            $this->newLine();
        }

        // List columns
        if ($this->option('list')) {
            $this->listColumns();
        }

        // Cleanup all migrated columns
        if ($this->option('cleanup-all')) {
            $this->cleanupAll($dryRun);
        }

        // Remove duplicate columns
        if ($this->option('remove-duplicates')) {
            $this->removeDuplicateColumns($dryRun);
        }

        // Drop old tables
        if ($this->option('drop-old-tables')) {
            $this->dropOldTables($dryRun);
        }

        return Command::SUCCESS;
    }

    /**
     * List columns that can be removed
     */
    protected function listColumns(): void
    {
        $this->info('KOLOM YANG SUDAH DIMIGRASIKAN KE tenaga_ktd:');
        $this->line('--------------------------------------------');
        foreach ($this->migratedColumns as $col => $desc) {
            $exists = Schema::hasColumn('users', $col);
            $status = $exists ? '✅ ADA' : '❌ TIDAK ADA';
            $this->line("  {$status} | {$col}");
            $this->line("           └── {$desc}");
        }

        $this->newLine();
        $this->info('KOLOM OPSIONAL (pertimbangkan untuk dihapus):');
        $this->line('--------------------------------------------');
        foreach ($this->optionalColumns as $col => $desc) {
            $exists = Schema::hasColumn('users', $col);
            $status = $exists ? '✅ ADA' : '❌ TIDAK ADA';
            $this->line("  {$status} | {$col}");
            $this->line("           └── {$desc}");
        }

        $this->newLine();

        // Count total removable
        $removable = 0;
        foreach ($this->migratedColumns as $col => $desc) {
            if (Schema::hasColumn('users', $col)) {
                $removable++;
            }
        }
        foreach ($this->optionalColumns as $col => $desc) {
            if (Schema::hasColumn('users', $col)) {
                $removable++;
            }
        }
        $this->info("Total kolom yang bisa dihapus: {$removable}");

        $this->newLine();
    }

    /**
     * Cleanup all migrated columns
     */
    protected function cleanupAll(bool $dryRun): void
    {
        $this->info('Menghapus SEMUA kolom yang sudah dimigrasikan ke tenaga_ktd...');
        $this->newLine();

        $toRemove = array_merge($this->migratedColumns, $this->optionalColumns);
        $removed = 0;
        $skipped = 0;

        foreach ($toRemove as $col => $desc) {
            // Skip protected columns
            if (in_array($col, $this->protectedColumns)) {
                $this->line("   🔒 PROTECTED: {$col} - tidak bisa dihapus (still used)");
                continue;
            }

            if (!Schema::hasColumn('users', $col)) {
                $this->line("   ⏭️  Lewati: {$col} tidak ada di tabel.");
                $skipped++;
                continue;
            }

            if ($dryRun) {
                $this->line("   [DRY RUN] ✓ Akan menghapus: {$col}");
                $removed++;
                continue;
            }

            try {
                Schema::table('users', function ($table) use ($col) {
                    $table->dropColumn($col);
                });
                $this->line("   ✓ Berhasil menghapus: {$col}");
                $removed++;
            } catch (\Exception $e) {
                $this->error("   ✗ Gagal menghapus {$col}: " . $e->getMessage());
                $skipped++;
            }
        }

        $this->newLine();
        $this->info("Selesai: {$removed} kolom dihapus, {$skipped} dilewati.");
        $this->newLine();
    }

    /**
     * Remove duplicate columns from users table
     */
    protected function removeDuplicateColumns(bool $dryRun): void
    {
        $this->info('Menghapus kolom duplikat dari tabel users...');
        $this->newLine();

        $toRemove = $this->migratedColumns;
        $removed = 0;
        $skipped = 0;

        foreach ($toRemove as $col => $desc) {
            // Skip protected columns
            if (in_array($col, $this->protectedColumns)) {
                continue;
            }

            if (!Schema::hasColumn('users', $col)) {
                $this->line("   ⏭️  Lewati: {$col} tidak ada di tabel.");
                $skipped++;
                continue;
            }

            if ($dryRun) {
                $this->line("   [DRY RUN] ✓ Akan menghapus: {$col}");
                $removed++;
                continue;
            }

            try {
                Schema::table('users', function ($table) use ($col) {
                    $table->dropColumn($col);
                });
                $this->line("   ✓ Berhasil menghapus: {$col}");
                $removed++;
            } catch (\Exception $e) {
                $this->error("   ✗ Gagal menghapus {$col}: " . $e->getMessage());
                $skipped++;
            }
        }

        $this->newLine();
        $this->info("Selesai: {$removed} kolom dihapus, {$skipped} dilewati.");
        $this->newLine();

        // Also drop the duplicate gol/golongan columns
        if (!$dryRun && Schema::hasColumn('users', 'gol') && Schema::hasColumn('users', 'golongan')) {
            $this->warn('PERHATIAN: Terdeteksi duplikasi gol & golongan di users.');
            if ($this->confirm('Hapus kolom gol? (golongan akan disimpan)', true)) {
                Schema::table('users', function ($table) {
                    $table->dropColumn('gol');
                });
                $this->info('   ✓ Kolom gol dihapus.');
            }
        }
    }

    /**
     * Drop old tables
     */
    protected function dropOldTables(bool $dryRun): void
    {
        $this->info('Menghapus tabel lama...');
        $this->newLine();

        $tables = ['guru_madrasah', 'pegawai_madrasah'];

        foreach ($tables as $table) {
            if (!Schema::hasTable($table)) {
                $this->line("   ⏭️  Lewati: {$table} tidak ada.");
                continue;
            }

            $count = DB::table($table)->count();
            $this->line("   Tabel: {$table} ({$count} records)");

            if ($dryRun) {
                $this->line("   [DRY RUN] ✓ Akan menghapus tabel {$table}");
                continue;
            }

            if (!$this->confirm("Hapus tabel {$table} ({$count} records)?", false)) {
                $this->line("   Dilewati.");
                continue;
            }

            try {
                Schema::dropIfExists($table);
                $this->info("   ✓ Tabel {$table} dihapus.");
            } catch (\Exception $e) {
                $this->error("   ✗ Gagal menghapus {$table}: " . $e->getMessage());
            }
        }

        $this->newLine();
    }
}
