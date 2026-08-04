<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MigrateTenagaKtd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'madrasah:migrate-tenaga
                            {--create-table : Create tenaga_ktd table if not exists}
                            {--migrate-guru : Migrate data from guru_madrasah}
                            {--migrate-pegawai : Migrate data from pegawai_madrasah}
                            {--migrate-users : Migrate data from users table}
                            {--migrate-all : Run all migrations}
                            {--drop-old : Drop old tables after successful migration}
                            {--dry-run : Preview without making changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate and consolidate tenaga kependidikan data to unified tenaga_ktd table';

    /**
     * Convert invalid date to null
     */
    protected function safeDate($value): ?string
    {
        if (empty($value) || $value === '0000-00-00' || $value === '0000-00-00 00:00:00') {
            return null;
        }

        // Validate if it's a valid date
        try {
            $date = \Carbon\Carbon::parse($value);
            if ($date->year < 1900 || $date->year > 2100) {
                return null;
            }
            return $date->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('===========================================');
        $this->info('  MIGrasi Data Tenaga Kependidikan');
        $this->info('  guru_madrasah + pegawai_madrasah + users');
        $this->info('  => tenaga_ktd (unified)');
        $this->info('===========================================');
        $this->newLine();

        $dryRun = $this->option('dry-run');

        if ($dryRun) {
            $this->warn('DRY RUN MODE - Tidak ada perubahan yang akan disimpan.');
            $this->newLine();
        }

        // Step 1: Create table if needed
        if ($this->option('create-table') || $this->option('migrate-all')) {
            $this->createTenagaKtdTable($dryRun);
        }

        // Step 2: Migrate guru_madrasah
        if ($this->option('migrate-guru') || $this->option('migrate-all')) {
            $this->migrateGuruMadrasah($dryRun);
        }

        // Step 3: Migrate pegawai_madrasah
        if ($this->option('migrate-pegawai') || $this->option('migrate-all')) {
            $this->migratePegawaiMadrasah($dryRun);
        }

        // Step 4: Migrate users
        if ($this->option('migrate-users') || $this->option('migrate-all')) {
            $this->migrateUsers($dryRun);
        }

        // Step 5: Cleanup (optional)
        if ($this->option('drop-old') && !$dryRun) {
            $this->cleanupOldTables();
        }

        $this->newLine();
        $this->info('Migrasi selesai!');

        return Command::SUCCESS;
    }

    /**
     * Create tenaga_ktd table
     */
    protected function createTenagaKtdTable(bool $dryRun): void
    {
        $this->info('1. Membuat tabel tenaga_ktd...');

        if (Schema::hasTable('tenaga_ktd')) {
            $this->warn('   Tabel tenaga_ktd sudah ada. Lewati.');
            return;
        }

        if ($dryRun) {
            $this->line('   [DRY RUN] Akan membuat tabel dengan struktur:');
            $this->line('   - id, dept_id, created_by');
            $this->line('   - Basic: nama, kat_jabatan, status');
            $this->line('   - IDs: nomor_induk, nik, npwp, nuptk, npk, nrg');
            $this->line('   - Personal: tempat_lahir, tanggal_lahir, jenis_kelamin, nama_ibu');
            $this->line('   - Position: golongan, jabatan, pekerjaan, bidang_studi_diajar, serdik');
            $this->line('   - Education: pendidikan, jurusan, fakultas, universitas, tahun_lulus');
            $this->line('   - Work: tmt_tugas, kgb, masa_kerja_tahun, masa_kerja_bulan');
            $this->line('   - Contact: email, telp, alamat_ktp, alamat, keterangan');
            $this->line('   - Meta: is_active, user_id (nullable), source_table');
            $this->newLine();
            return;
        }

        Schema::create('tenaga_ktd', function ($table) {
            $table->id();
            $table->unsignedBigInteger('dept_id')->nullable()->index();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('user_id')->nullable()->index(); // link to users table

            // Basic
            $table->string('nama');
            $table->string('kat_jabatan', 50)->nullable()->index(); // guru, staf, Honor
            $table->string('status', 50)->nullable()->index(); // pns, pppk, honor

            // IDs
            $table->string('nomor_induk', 50)->nullable()->unique();
            $table->string('nik', 20)->nullable();
            $table->string('npwp', 30)->nullable();
            $table->string('nuptk', 20)->nullable();
            $table->string('npk', 20)->nullable();
            $table->string('nrg', 20)->nullable();

            // Personal
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('jenis_kelamin', 10)->nullable();
            $table->string('nama_ibu')->nullable();

            // Position
            $table->string('golongan', 20)->nullable();
            $table->string('jabatan')->nullable();
            $table->string('pekerjaan')->nullable();
            $table->string('bidang_studi_diajar')->nullable();
            $table->string('bidang_sertifikasi')->nullable();
            $table->string('serdik', 50)->nullable();

            // Education
            $table->string('pendidikan', 20)->nullable();
            $table->string('jurusan')->nullable();
            $table->string('fakultas')->nullable();
            $table->string('universitas')->nullable();
            $table->string('tahun_lulus', 10)->nullable();

            // Work
            $table->date('tmt_tugas')->nullable();
            $table->date('kgb')->nullable();
            $table->string('masa_kerja_tahun', 10)->nullable();
            $table->string('masa_kerja_bulan', 10)->nullable();

            // Contact
            $table->string('email')->nullable();
            $table->string('telp', 20)->nullable();
            $table->text('alamat_ktp')->nullable();
            $table->text('alamat')->nullable();
            $table->text('keterangan')->nullable();

            // Meta
            $table->boolean('is_active')->default(true);
            $table->string('source_table', 50)->nullable(); // guru_madrasah, pegawai_madrasah, users
            $table->timestamps();

            $table->index('nama');
        });

        $this->info('   ✓ Tabel tenaga_ktd berhasil dibuat.');
        $this->newLine();
    }

    /**
     * Migrate data from guru_madrasah
     */
    protected function migrateGuruMadrasah(bool $dryRun): void
    {
        $this->info('2. Migrasi data dari guru_madrasah...');

        if (!Schema::hasTable('guru_madrasah')) {
            $this->warn('   Tabel guru_madrasah tidak ditemukan. Lewati.');
            return;
        }

        $count = DB::table('guru_madrasah')->count();
        $this->line("   Ditemukan {$count} record di guru_madrasah.");

        if ($count === 0) {
            $this->line('   Tidak ada data untuk dimigrasi.');
            return;
        }

        // Show sample data
        $sample = DB::table('guru_madrasah')->first();
        $this->line('   Sample: ' . ($sample->nama ?? 'N/A') . ' - ' . ($sample->kat_jabatan ?? 'N/A'));
        $this->newLine();

        if ($dryRun) {
            $this->line('   [DRY RUN] Akan migrasi ' . $count . ' record dengan kat_jabatan="guru".');
            return;
        }

        // Get all guru_madrasah records
        $records = DB::table('guru_madrasah')->get();

        $bar = $this->output->createProgressBar($count);
        $bar->start();

        $migrated = 0;
        foreach ($records as $record) {
            // Check if already migrated (by nomor_induk or nik)
            $exists = DB::table('tenaga_ktd')
                ->where('nomor_induk', $record->nomor_induk)
                ->orWhere('nik', $record->nik)
                ->where('source_table', 'guru_madrasah')
                ->exists();

            if ($exists) {
                $bar->advance();
                continue;
            }

            DB::table('tenaga_ktd')->insert([
                'dept_id' => $record->dept_id,
                'created_by' => $record->created_by,
                'nama' => $record->nama,
                'kat_jabatan' => 'guru',
                'status' => $record->status,
                'nomor_induk' => $record->nomor_induk ?? null,
                'nik' => $record->nik,
                'npwp' => $record->npwp ?? null,
                'nuptk' => $record->nuptk ?? null,
                'npk' => $record->npk ?? null,
                'nrg' => $record->nrg ?? null,
                'tempat_lahir' => $record->tempat_lahir ?? null,
                'tanggal_lahir' => $this->safeDate($record->tanggal_lahir),
                'jenis_kelamin' => $record->jenis_kelamin,
                'nama_ibu' => $record->nama_ibu ?? null,
                'golongan' => $record->golongan,
                'jabatan' => $record->jabatan ?? null,
                'bidang_studi_diajar' => $record->bidang_studi_diajar ?? null,
                'bidang_sertifikasi' => $record->bidang_sertifikasi ?? null,
                'serdik' => $record->serdik ?? null,
                'pendidikan' => $record->pendidikan ?? null,
                'jurusan' => $record->jurusan,
                'fakultas' => $record->fakultas,
                'universitas' => $record->universitas,
                'tahun_lulus' => $record->tahun_lulus,
                'tmt_tugas' => $this->safeDate($record->tmt_tugas),
                'kgb' => $this->safeDate($record->kgb),
                'email' => $record->email ?? null,
                'telp' => $record->telp ?? null,
                'alamat' => $record->alamat ?? null,
                'is_active' => true,
                'source_table' => 'guru_madrasah',
                'created_at' => $record->created_at ?? now(),
                'updated_at' => $record->updated_at ?? now(),
            ]);

            $migrated++;
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        $this->info("   ✓ Berhasil migrasi {$migrated} record dari guru_madrasah.");
        $this->newLine();
    }

    /**
     * Migrate data from pegawai_madrasah
     */
    protected function migratePegawaiMadrasah(bool $dryRun): void
    {
        $this->info('3. Migrasi data dari pegawai_madrasah...');

        if (!Schema::hasTable('pegawai_madrasah')) {
            $this->warn('   Tabel pegawai_madrasah tidak ditemukan. Lewati.');
            return;
        }

        $count = DB::table('pegawai_madrasah')->count();
        $this->line("   Ditemukan {$count} record di pegawai_madrasah.");

        if ($count === 0) {
            $this->line('   Tidak ada data untuk dimigrasi.');
            return;
        }

        // Show sample data
        $sample = DB::table('pegawai_madrasah')->first();
        $this->line('   Sample: ' . ($sample->name ?? 'N/A') . ' - ' . ($sample->status ?? 'N/A'));
        $this->newLine();

        if ($dryRun) {
            $this->line('   [DRY RUN] Akan migrasi ' . $count . ' record dengan kat_jabatan="staf".');
            return;
        }

        // Get all pegawai_madrasah records
        $records = DB::table('pegawai_madrasah')->get();

        $bar = $this->output->createProgressBar($count);
        $bar->start();

        $migrated = 0;
        foreach ($records as $record) {
            // Check if already migrated
            $exists = DB::table('tenaga_ktd')
                ->where('nomor_induk', $record->nomor_induk)
                ->orWhere('nik', $record->nik)
                ->where('source_table', 'pegawai_madrasah')
                ->exists();

            if ($exists) {
                $bar->advance();
                continue;
            }

            // Determine kat_jabatan based on status
            $katJabatan = match (strtolower($record->status ?? '')) {
                'honor', 'honorer' => 'honorer',
                'pns', 'pppk' => 'staf',
                default => 'staf',
            };

            DB::table('tenaga_ktd')->insert([
                'dept_id' => $record->dept_id,
                'created_by' => $record->created_by,
                'nama' => $record->name,
                'kat_jabatan' => $katJabatan,
                'status' => $record->status,
                'nomor_induk' => $record->nomor_induk ?? null,
                'nik' => $record->nik,
                'npwp' => $record->npwp ?? null,
                'tempat_lahir' => $record->tempat_lahir ?? null,
                'tanggal_lahir' => $this->safeDate($record->tanggal_lahir),
                'jenis_kelamin' => $record->jk ?? null,
                'golongan' => $record->golongan,
                'jabatan' => $record->jabatan ?? null,
                'pekerjaan' => $record->pekerjaan ?? null,
                'jurusan' => $record->jurusan,
                'fakultas' => $record->fakultas,
                'universitas' => $record->universitas,
                'tmt_tugas' => $this->safeDate($record->tmt_tugas),
                'kgb' => $this->safeDate($record->kgb),
                'masa_kerja_tahun' => $record->masa_kerja_tahun ?? null,
                'masa_kerja_bulan' => $record->masa_kerja_bulan ?? null,
                'email' => $record->email ?? null,
                'telp' => $record->telp ?? null,
                'alamat_ktp' => $record->alamat_ktp ?? null,
                'alamat' => $record->alamat ?? null,
                'keterangan' => $record->keterangan ?? null,
                'is_active' => true,
                'source_table' => 'pegawai_madrasah',
                'created_at' => $record->created_at ?? now(),
                'updated_at' => $record->updated_at ?? now(),
            ]);

            $migrated++;
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        $this->info("   ✓ Berhasil migrasi {$migrated} record dari pegawai_madrasah.");
        $this->newLine();
    }

    /**
     * Migrate data from users table
     */
    protected function migrateUsers(bool $dryRun): void
    {
        $this->info('4. Migrasi data dari users (staf madrasah)...');

        // Get users that are from madrasah departments and have kat_jabatan = 'adm' or 'guru'
        $query = DB::table('users')
            ->join('ktd_department', 'users.dept_id', '=', 'ktd_department.id')
            ->whereIn('ktd_department.kategori', ['man', 'min', 'mtsn', 'ra', 'other'])
            ->whereIn('users.kat_jabatan', ['adm', 'guru'])
            ->whereNotIn('users.role', ['other', 'pensiun', 'pindah']);

        $count = $query->count();
        $this->line("   Ditemukan {$count} user yang eligible untuk migrasi.");

        if ($count === 0) {
            $this->line('   Tidak ada data untuk dimigrasi.');
            return;
        }

        // Show breakdown
        $admCount = DB::table('users')
            ->join('ktd_department', 'users.dept_id', '=', 'ktd_department.id')
            ->whereIn('ktd_department.kategori', ['man', 'min', 'mtsn', 'ra', 'other'])
            ->where('users.kat_jabatan', 'adm')
            ->count();

        $guruCount = DB::table('users')
            ->join('ktd_department', 'users.dept_id', '=', 'ktd_department.id')
            ->whereIn('ktd_department.kategori', ['man', 'min', 'mtsn', 'ra', 'other'])
            ->where('users.kat_jabatan', 'guru')
            ->count();

        $this->line("   - Adm/Staf: {$admCount}");
        $this->line("   - Guru: {$guruCount}");
        $this->newLine();

        if ($dryRun) {
            $this->line('   [DRY RUN] Akan migrasi ' . $count . ' user dengan user_id sebagai referensi.');
            return;
        }

        $records = $query->select([
            'users.id as user_id',
            'users.*',
            'ktd_department.kategori',
        ])->get();

        $bar = $this->output->createProgressBar($count);
        $bar->start();

        $migrated = 0;
        $skipped = 0;

        foreach ($records as $record) {
            // Determine kat_jabatan
            $katJabatan = match ($record->kat_jabatan) {
                'adm' => 'staf',
                'guru' => 'guru',
                default => $record->kat_jabatan,
            };

            // Determine status
            $status = match ($record->asn) {
                'cpns' => 'CPNS',
                'pns' => 'PNS',
                'pppk' => 'PPPK',
                default => 'Honorer',
            };

            // Prepare data
            $data = [
                'dept_id' => $record->dept_id,
                'user_id' => $record->user_id,
                'nama' => $record->name,
                'kat_jabatan' => $katJabatan,
                'status' => $status,
                'nomor_induk' => $record->nomor_induk ?? null,
                'nik' => $record->nik ?? null,
                'kk' => $record->kk ?? null,
                'npwp' => $record->npwp ?? null,
                'tempat_lahir' => $record->tempat_lahir ?? null,
                'tanggal_lahir' => $this->safeDate($record->tanggal_lahir),
                'jenis_kelamin' => $record->jk ?? null,
                'golongan' => $record->golongan ?? $record->gol ?? null,
                'jabatan' => $record->jabatan ?? null,
                'pekerjaan' => $record->pekerjaan ?? null,
                'jenis_guru' => $record->jenis_guru ?? null,
                'pendidikan' => $record->ijazah_pendidikan ?? null,
                'jurusan' => $record->ijazah_jurusan ?? null,
                'fakultas' => $record->ijazah_fakultas ?? null,
                'universitas' => $record->ijazah_universitas ?? null,
                'tahun_lulus' => $record->ijazah_tahun_lulus ?? null,
                'tmt_cpns' => $this->safeDate($record->tmt_cpns),
                'tmt_pns' => $this->safeDate($record->tmt_pns),
                'tmt_tugas' => $this->safeDate($record->tmt_tugas),
                'kgb' => $this->safeDate($record->kgb),
                'masa_kerja_tahun' => $record->masa_kerja_tahun ?? null,
                'masa_kerja_bulan' => $record->masa_kerja_bulan ?? null,
                'nikah' => $record->nikah ?? null,
                'jenis_pjob' => $record->jenis_pjob ?? null,
                'pjob' => $record->pjob ?? null,
                'req_tunjangan' => $record->req_tunjangan ?? null,
                'jml_anak' => $record->jml_anak ?? null,
                'email' => $record->email ?? null,
                'telp' => $record->telp ?? null,
                'alamat_ktp' => null,
                'alamat' => $record->alamat ?? null,
                'keterangan' => null,
                'bio' => $record->bio ?? null,
                'facebook' => $record->facebook ?? null,
                'twitter' => $record->twitter ?? null,
                'linkedin' => $record->linkedin ?? null,
                'instagram' => $record->instagram ?? null,
                'is_active' => $record->role !== 'pindah' && $record->role !== 'pensiun',
                'source_table' => 'users',
                'updated_at' => now(),
            ];

            // Check if already migrated by user_id
            $existing = DB::table('tenaga_ktd')
                ->where('user_id', $record->user_id)
                ->where('source_table', 'users')
                ->first();

            if ($existing) {
                // Update existing record
                DB::table('tenaga_ktd')
                    ->where('id', $existing->id)
                    ->update($data);
                $migrated++;
            } else {
                // Insert new record
                $data['created_at'] = $record->created_at ?? now();
                DB::table('tenaga_ktd')->insert($data);
                $migrated++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        $this->info("   ✓ Berhasil migrasi/update {$migrated} user dengan semua kolom dari users.");
        $this->newLine();
    }

    /**
     * Drop old tables
     */
    protected function cleanupOldTables(): void
    {
        $this->info('5. Cleanup tabel lama...');

        if (!$this->confirm('   Yakin ingin menghapus tabel guru_madrasah dan pegawai_madrasah?', false)) {
            $this->warn('   Batal menghapus tabel lama.');
            return;
        }

        // Check if all data has been migrated
        $guruCount = DB::table('guru_madrasah')->count();
        $pegawaiCount = DB::table('pegawai_madrasah')->count();

        $migratedFromGuru = DB::table('tenaga_ktd')->where('source_table', 'guru_madrasah')->count();
        $migratedFromPegawai = DB::table('tenaga_ktd')->where('source_table', 'pegawai_madrasah')->count();

        if ($guruCount > 0 && $migratedFromGuru < $guruCount) {
            $this->error("   Ada {$guruCount} data di guru_madrasah, tapi hanya {$migratedFromGuru} yang dimigrasi!");
            return;
        }

        if ($pegawaiCount > 0 && $migratedFromPegawai < $pegawaiCount) {
            $this->error("   Ada {$pegawaiCount} data di pegawai_madrasah, tapi hanya {$migratedFromPegawai} yang dimigrasi!");
            return;
        }

        Schema::dropIfExists('guru_madrasah');
        $this->line('   ✓ Tabel guru_madrasah dihapus.');

        Schema::dropIfExists('pegawai_madrasah');
        $this->line('   ✓ Tabel pegawai_madrasah dihapus.');

        $this->newLine();
        $this->info('   ✓ Cleanup selesai!');
    }
}
