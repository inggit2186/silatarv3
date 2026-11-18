<?php

namespace App\Services;

use App\Models\UjianPilihan;
use App\Models\UjianSoal;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class UjianSoalImportService
{
    /** Baris contoh pada template yang otomatis dilewati saat import. */
    private const PENANDA_CONTOH = '[contoh]';

    private const HEADER_TEMPLATE = [
        'tipe', 'pertanyaan', 'petunjuk', 'poin',
        'pilihan_a', 'pilihan_b', 'pilihan_c', 'pilihan_d', 'pilihan_e', 'kunci',
    ];

    /** Alternatif nama kolom yang diterima saat membaca file admin. */
    private const ALIAS_HEADER = [
        'pertanyaan' => ['pertanyaan', 'soal', 'pertanyaan_soal', 'isi_soal'],
        'tipe' => ['tipe', 'jenis', 'tipe_soal', 'jenis_soal'],
        'petunjuk' => ['petunjuk', 'keterangan'],
        'poin' => ['poin', 'point', 'bobot', 'nilai'],
        'kunci' => ['kunci', 'kunci_jawaban', 'jawaban_benar', 'kunci_benar'],
    ];

    private const LABEL_PILIHAN = ['A', 'B', 'C', 'D', 'E'];

    /**
     * Baca file Excel dan kembalikan baris soal yang sudah tervalidasi.
     *
     * @return array{rows: array<int, array<string, mixed>>, errors: array<int, string>}
     */
    public function parse(UploadedFile $file): array
    {
        try {
            $spreadsheet = IOFactory::load($file->getRealPath());
        } catch (\Throwable $e) {
            return [
                'rows' => [],
                'errors' => ['File Excel tidak dapat dibaca. Pastikan format .xlsx atau .xls valid.'],
            ];
        }

        $baris = $spreadsheet->getSheet(0)->toArray(null, true, false, true);

        if (empty($baris)) {
            return ['rows' => [], 'errors' => ['File Excel kosong.']];
        }

        $petaKolom = $this->petakanKolom(array_shift($baris));

        if (! isset($petaKolom['pertanyaan'])) {
            return [
                'rows' => [],
                'errors' => ['Kolom "pertanyaan" tidak ditemukan pada baris pertama file Excel. Gunakan template resmi untuk memformat file.'],
            ];
        }

        $rows = [];
        $errors = [];

        foreach ($baris as $nomor => $barisData) {
            $hasil = $this->barisSoal($barisData, $petaKolom, $nomor + 2);

            if ($hasil['error']) {
                $errors[] = $hasil['pesan'];

                continue;
            }

            if ($hasil['soal'] !== null) {
                $rows[] = $hasil['soal'];
            }
        }

        if (empty($rows) && empty($errors)) {
            $errors[] = 'File Excel tidak memuat baris soal.';
        }

        return ['rows' => $rows, 'errors' => $errors];
    }

    /**
     * Simpan hasil parse ke dalam satu ujian.
     *
     * @param  array<int, array<string, mixed>>  $rows
     * @return int jumlah soal yang berhasil dibuat
     */
    public function simpan(int $ujianId, array $rows): int
    {
        if (empty($rows)) {
            return 0;
        }

        $urut = (UjianSoal::where('ujian_id', $ujianId)->max('urut') ?? 0);

        return DB::transaction(function () use ($ujianId, $rows, &$urut) {
            $jumlah = 0;

            foreach ($rows as $data) {
                $urut++;

                $soal = UjianSoal::create([
                    'ujian_id' => $ujianId,
                    'tipe' => $data['tipe'],
                    'pertanyaan' => $data['pertanyaan'],
                    'petunjuk' => $data['petunjuk'],
                    'poin' => $data['poin'],
                    'urut' => $urut,
                ]);

                foreach ($data['pilihan'] as $index => $teks) {
                    UjianPilihan::create([
                        'soal_id' => $soal->id,
                        'label' => self::LABEL_PILIHAN[$index],
                        'teks' => $teks,
                        'benar' => $index === $data['kunci'],
                        'urut' => $index + 1,
                    ]);
                }

                $jumlah++;
            }

            return $jumlah;
        });
    }

    /**
     * Buat file template Excel untuk import soal.
     */
    public function template(): Spreadsheet
    {
        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Soal');

        $sheet->fromArray([self::HEADER_TEMPLATE], null, 'A1');
        $sheet->fromArray([
            ['pilihan_ganda', '[CONTOH] Apa ibukota Indonesia?', 'Pilih satu jawaban yang tepat', 10,
                'Jakarta', 'Bandung', 'Surabaya', 'Medan', 'Makassar', 'A'],
            ['essay', '[CONTOH] Jelaskan prinsip dasar Achieving.', 'Uraikan sesuai paham Anda', 20,
                '', '', '', '', '', ''],
        ], null, 'A2');

        foreach (range('A', 'J') as $kolom) {
            $sheet->getColumnDimension($kolom)->setAutoSize(true);
        }

        $sheet->getStyle('A1:J1')->getFont()->setBold(true);
        $sheet->getStyle('A1:J1')->getFill()->setFillType(Fill::FILL_SOLID);
        $sheet->getStyle('A1:J1')->getFill()->getStartColor()->setARGB('FF0891B2');
        $sheet->getStyle('A1:J1')->getFont()->setColor(new Color('FFFFFFFF'));
        $sheet->getStyle('A1:J1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $panduan = $spreadsheet->createSheet();
        $panduan->setTitle('Petunjuk');
        $panduan->fromArray([
            ['Kolom', 'Keterangan'],
            ['tipe', 'pilihan_ganda atau essay (kosongkan untuk deteksi otomatis)'],
            ['pertanyaan', 'Isi pertanyaan soal (wajib)'],
            ['petunjuk', 'Petunjuk singkat (opsional)'],
            ['poin', 'Bobot soal, default 1'],
            ['pilihan_a .. pilihan_e', 'Teks opsi jawaban, boleh dikosongkan di kolom belakang'],
            ['kunci', 'Huruf kunci untuk pilihan ganda (A-E). Kosong untuk soal essay'],
            ['', ''],
            ['Catatan', 'Baris contoh yang diawali [CONTOH] otomatis dilewati saat import'],
            ['Catatan', 'Hapus baris contoh sebelum mengunggah file'],
        ], null, 'A1');
        $panduan->getStyle('A1:B1')->getFont()->setBold(true);
        $panduan->getColumnDimension('A')->setAutoSize(true);
        $panduan->getColumnDimension('B')->setAutoSize(true);

        // Sheet soal harus menjadi sheet aktif agar file yang diunduh langsung terisi.
        $spreadsheet->setActiveSheetIndex(0);

        return $spreadsheet;
    }

    public function unduhTemplate(): string
    {
        $spreadsheet = $this->template();

        $nama = 'template-import-soal-ujian.xlsx';
        $lokasi = storage_path('app/exports/'.$nama);

        (new Xlsx($spreadsheet))->save($lokasi);

        return $lokasi;
    }

    /**
     * @param  array<string, mixed>  $barisData
     * @param  array<string, string>  $petaKolom
     * @return array{soal: array<string, mixed>|null, error: bool, pesan: string}
     */
    private function barisSoal(array $barisData, array $petaKolom, int $nomorBaris): array
    {
        $nilai = function (string $kolom) use ($barisData, $petaKolom) {
            if (! isset($petaKolom[$kolom])) {
                return '';
            }

            $sel = $barisData[$petaKolom[$kolom]] ?? '';

            return is_scalar($sel) ? trim((string) $sel) : '';
        };

        $pertanyaan = $nilai('pertanyaan');

        if ($pertanyaan === '') {
            return ['soal' => null, 'error' => false, 'pesan' => ''];
        }

        if (stripos($pertanyaan, self::PENANDA_CONTOH) === 0) {
            return ['soal' => null, 'error' => false, 'pesan' => ''];
        }

        $pilihan = [];
        foreach (range('a', 'e') as $huruf) {
            $teks = $nilai('pilihan_'.$huruf);
            if ($teks !== '') {
                $pilihan[] = $teks;
            }
        }

        $tipe = $this->normalisasiTipe($nilai('tipe'), ! empty($pilihan));

        if ($tipe === '') {
            return ['soal' => null, 'error' => true, 'pesan' => "Baris {$nomorBaris}: tipe soal tidak dikenali. Gunakan \"pilihan_ganda\" atau \"essay\"."];
        }

        $poin = $nilai('poin');
        $poin = $poin === '' ? 1 : (int) $poin;
        if ($poin < 1 || $poin > 100) {
            return ['soal' => null, 'error' => true, 'pesan' => "Baris {$nomorBaris}: poin harus berupa angka 1 sampai 100."];
        }

        $kunci = -1;

        if ($tipe === 'pilihan_ganda') {
            if (count($pilihan) < 2) {
                return ['soal' => null, 'error' => true, 'pesan' => "Baris {$nomorBaris}: soal pilihan ganda minimal memiliki 2 opsi jawaban."];
            }

            $kunci = $this->normalisasiKunci($nilai('kunci'));

            if ($kunci < 0) {
                return ['soal' => null, 'error' => true, 'pesan' => "Baris {$nomorBaris}: kunci jawaban wajib diisi dengan huruf A sampai E."];
            }

            if ($kunci >= count($pilihan)) {
                return ['soal' => null, 'error' => true, 'pesan' => "Baris {$nomorBaris}: kunci jawaban {$nilai('kunci')} tidak sesuai dengan jumlah opsi yang diisi."];
            }
        }

        return [
            'soal' => [
                'tipe' => $tipe,
                'pertanyaan' => $pertanyaan,
                'petunjuk' => $nilai('petunjuk') !== '' ? $nilai('petunjuk') : null,
                'poin' => $poin,
                'pilihan' => $pilihan,
                'kunci' => $kunci,
            ],
            'error' => false,
            'pesan' => '',
        ];
    }

    /**
     * @param  array<string, mixed>  $header
     * @return array<string, string>
     */
    private function petakanKolom(array $header): array
    {
        $peta = [];

        foreach ($header as $kolom => $nama) {
            $nama = strtolower(trim((string) $nama));
            $nama = preg_replace('/[^a-z0-9_]+/', '_', $nama) ?? '';
            $nama = trim($nama, '_');

            if ($nama === '') {
                continue;
            }

            if (preg_match('/^(pilihan|opsi)_([a-e])$/', $nama, $cocok)) {
                $peta['pilihan_'.$cocok[2]] = $kolom;

                continue;
            }

            foreach (self::ALIAS_HEADER as $key => $alias) {
                if (in_array($nama, $alias, true)) {
                    $peta[$key] = $kolom;

                    break;
                }
            }
        }

        return $peta;
    }

    private function normalisasiTipe(string $tipe, bool $adaPilihan): string
    {
        $tipe = strtolower(preg_replace('/[^a-z_]/', '', $tipe) ?? '');

        if (in_array($tipe, ['pilihan_ganda', 'pg', 'pilihan', 'multiplechoice', 'multiple_choice'], true)) {
            return 'pilihan_ganda';
        }

        if (in_array($tipe, ['essay', 'esai', 'uraian', 'openended'], true)) {
            return 'essay';
        }

        if ($tipe === '') {
            return $adaPilihan ? 'pilihan_ganda' : 'essay';
        }

        return '';
    }

    private function normalisasiKunci(string $kunci): int
    {
        $kunci = strtoupper(trim($kunci));

        if ($kunci === '') {
            return -1;
        }

        if (ctype_digit($kunci)) {
            $angka = (int) $kunci;

            return ($angka >= 1 && $angka <= 5) ? $angka - 1 : -1;
        }

        $kunci = preg_replace('/[^A-Z]/', '', $kunci) ?? '';
        if ($kunci === '') {
            return -1;
        }

        $index = array_search($kunci[0], self::LABEL_PILIHAN, true);

        return $index === false ? -1 : $index;
    }
}
