<?php

namespace Database\Seeders;

use App\Models\Ujian;
use App\Models\UjianPeserta;
use App\Models\UjianPilihan;
use App\Models\UjianSoal;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class UjianSeeder extends Seeder
{
    public function run(): void
    {
        $adminId = DB::table('users')->where('role', 'superadmin')->value('id')
            ?? DB::table('users')->min('id');

        $ujian = Ujian::updateOrCreate(
            ['kode' => 'UJN-2026-001'],
            [
                'judul' => 'Ujian Pengetahuan Kepegawaian 2026',
                'deskripsi' => 'Ujian pengetahuan umum kepegawaian meliputi regulasi, administrasi, dan integrasi layanan.',
                'materi' => "Materi rujukan:\n1. Regulasi tentang kepegawaian Kementerian Agama\n2. Pedoman pelayanan dan administrasi perkantoran\n3. Integrasi layanan melalui portal SILATAR",
                'durasi_menit' => 30,
                'status' => 'terbit',
                'mulai_at' => now()->subDay(),
                'selesai_at' => now()->addMonth(),
                'acak_soal' => false,
                'created_by' => $adminId,
            ]
        );

        // Bersihkan soal lama agar seeder dapat dijalankan berulang kali.
        UjianSoal::where('ujian_id', $ujian->id)->delete();

        $daftarSoal = [
            [
                'tipe' => 'pilihan_ganda',
                'pertanyaan' => 'Siapa yang berwenang menetapkan Jabatan Fungsional di Kementerian Agama?',
                'petunjuk' => 'Pilih satu jawaban yang paling tepat.',
                'poin' => 20,
                'pilihan' => [
                    ['teks' => 'Menteri Agama', 'benar' => true],
                    ['teks' => 'Sekretaris Jenderal', 'benar' => false],
                    ['teks' => 'Kepala Biro keenam', 'benar' => false],
                    ['teks' => 'Inspektur Jenderal', 'benar' => false],
                ],
            ],
            [
                'tipe' => 'pilihan_ganda',
                'pertanyaan' => 'Dokumen apa yang menjadi dasar pelaksanaan Urusan Employment di kantor?',
                'petunjuk' => null,
                'poin' => 20,
                'pilihan' => [
                    ['teks' => 'Surat Keputusan Menteri Agama', 'benar' => true],
                    ['teks' => 'Surat Edaran Kepala Kantor', 'benar' => false],
                    ['teks' => 'Nota Dinas', 'benar' => false],
                    ['teks' => 'Risalah Rapat', 'benar' => false],
                ],
            ],
            [
                'tipe' => 'pilihan_ganda',
                'pertanyaan' => 'Batas waktu pelaporan yang bersifat rutin adalah:',
                'petunjuk' => null,
                'poin' => 20,
                'pilihan' => [
                    ['teks' => '5 hari kerja', 'benar' => false],
                    ['teks' => '7 hari kerja', 'benar' => true],
                    ['teks' => '10 hari kerja', 'benar' => false],
                    ['teks' => '14 hari kerja', 'benar' => false],
                ],
            ],
            [
                'tipe' => 'pilihan_ganda',
                'pertanyaan' => 'Layanan pada portal SILATAR yang berkaitan dengan pengungkapan informasi adalah:',
                'petunjuk' => null,
                'poin' => 20,
                'pilihan' => [
                    ['teks' => 'Public Information Disclosure', 'benar' => true],
                    ['teks' => 'Janji Temu', 'benar' => false],
                    ['teks' => 'Pengajuan Surat', 'benar' => false],
                    ['teks' => 'Laporan Kinerja', 'benar' => false],
                ],
            ],
            [
                'tipe' => 'pilihan_ganda',
                'pertanyaan' => 'Prinsip pengelolaan arsip elektronik pada unit kerja adalah:',
                'petunjuk' => null,
                'poin' => 20,
                'pilihan' => [
                    ['teks' => 'Penyimpanan terpusat dengan pencadangan berkala', 'benar' => true],
                    ['teks' => 'Penyimpanan pada komputer pribadi masing-masing', 'benar' => false],
                    ['teks' => 'Penyimpanan hanya dalam bentuk cetak', 'benar' => false],
                    ['teks' => 'Tidak ada arsip elektronik yang perlu disimpan', 'benar' => false],
                ],
            ],
            [
                'tipe' => 'essay',
                'pertanyaan' => 'Uraikan langkah-langkah pelayanan pengajuan surat resmi pada Kantor Kementerian Agama Kabupaten Tanah Datar.',
                'petunjuk' => 'Sebutkan minimal tiga langkah.',
                'poin' => 40,
                'pilihan' => [],
            ],
            [
                'tipe' => 'essay',
                'pertanyaan' => 'Jelaskan prinsip AKHLAK dalam pelayanan publik dan penerapannya di unit kerja.',
                'petunjuk' => 'Jelaskan satu prinsip beserta contoh penerapannya.',
                'poin' => 40,
                'pilihan' => [],
            ],
        ];

        foreach ($daftarSoal as $index => $item) {
            $soal = UjianSoal::create([
                'ujian_id' => $ujian->id,
                'tipe' => $item['tipe'],
                'pertanyaan' => $item['pertanyaan'],
                'petunjuk' => $item['petunjuk'],
                'poin' => $item['poin'],
                'urut' => $index + 1,
            ]);

            foreach ($item['pilihan'] as $opsiIndex => $opsi) {
                UjianPilihan::create([
                    'soal_id' => $soal->id,
                    'label' => chr(65 + $opsiIndex),
                    'teks' => $opsi['teks'],
                    'benar' => $opsi['benar'],
                    'urut' => $opsiIndex,
                ]);
            }
        }

        // Peserta contoh: lima pegawai aktif yang profilnya sudah lengkap,
        // supaya middleware EnsureProfileComplete tidak mengalihkan ke /profil/edit.
        $pesertaIds = DB::table('users')
            ->whereNotIn('role', ['pensiun', 'pindah'])
            ->orderBy('id')
            ->pluck('id')
            ->filter(fn ($userId) => $this->profilLengkap($userId))
            ->take(5);

        foreach ($pesertaIds as $userId) {
            UjianPeserta::firstOrCreate(
                ['ujian_id' => $ujian->id, 'user_id' => $userId],
                ['status' => 'belum_mulai']
            );
        }

        Cache::forget("ujian:{$ujian->id}:soal");

        $this->command?->info("Ujian contoh '{$ujian->kode}' siap: ".count($daftarSoal).' soal, '.count($pesertaIds).' peserta.');
    }

    /**
     * Cek apakah data profil pengguna cukup lengkap agar boleh masuk halaman ujian.
     */
    private function profilLengkap(int $userId): bool
    {
        $user = DB::table('users')->where('id', $userId)->first();

        if (! $user) {
            return false;
        }

        if (in_array(strtolower((string) $user->role), ['superadmin', 'admin'], true)) {
            return true;
        }

        $tenaga = DB::table('tenaga_ktd')
            ->where(function ($q) use ($user) {
                $q->where('user_id', $user->id);
                if (! empty($user->nomor_induk)) {
                    $q->orWhere('nomor_induk', $user->nomor_induk);
                }
            })
            ->first();

        if (empty($user->pp)) {
            return false;
        }

        $nikUser = (string) ($user->nip ?? '');
        $nikTenaga = (string) ($tenaga->nik ?? '');

        if (! preg_match('/^\d{16}$/', $nikUser) && ! preg_match('/^\d{16}$/', $nikTenaga)) {
            return false;
        }

        if (! preg_match('/^\d{16}$/', (string) ($tenaga->kk ?? ''))) {
            return false;
        }

        return true;
    }
}
