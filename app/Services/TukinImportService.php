<?php

namespace App\Services;

use App\Models\KtdTukin;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Exception;

class TukinImportService
{
    protected $errors = [];
    protected $validRows = [];
    protected $invalidRows = [];
    protected $batchId;

    /**
     * Parse Excel file dan return data
     */
    public function parseExcel($filePath): array
    {
        // Increase memory limit for large files
        ini_set('memory_limit', '1024M');

        try {
            $spreadsheet = IOFactory::load($filePath);
            $sheet = $spreadsheet->getActiveSheet();
            $highestRow = $sheet->getHighestRow();
            $highestCol = $sheet->getHighestColumn();

            // Convert column letter to index (A=0, B=1, ..., Z=25, AA=26, etc.)
            $highestColIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestCol);

            $data = [];
            for ($row = 2; $row <= $highestRow; $row++) {
                $rowData = [];
                for ($colIndex = 1; $colIndex <= $highestColIndex; $colIndex++) {
                    $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex);
                    $rowData[] = $sheet->getCell($colLetter . $row)->getValue();
                }
                $data[] = $rowData;

                // Free memory every 100 rows
                if ($row % 100 == 0) {
                    gc_collect_cycles();
                }
            }

            return [
                'success' => true,
                'headers' => $this->getHeaders($sheet),
                'data' => $data,
                'total_rows' => $highestRow - 1, // Excluding header
            ];
        } catch (Exception $e) {
            Log::error('Excel parse error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Gagal membaca file Excel: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Get headers from Excel sheet
     */
    protected function getHeaders($sheet): array
    {
        $headers = [];
        $highestCol = $sheet->getHighestColumn();
        $highestColIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestCol);

        for ($colIndex = 1; $colIndex <= $highestColIndex; $colIndex++) {
            $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex);
            $headers[] = $sheet->getCell($colLetter . '1')->getValue();
        }
        return $headers;
    }

    /**
     * Validate data dari Excel
     */
    public function validateData(array $data): array
    {
        $this->errors = [];
        $this->validRows = [];
        $this->invalidRows = [];

        foreach ($data as $index => $row) {
            $rowNumber = $index + 2; // +2 karena header di row 1, dan index 0 = row 2
            $rowErrors = [];

            // Validasi NIP (required)
            $nip = $row[2] ?? null; // Kolom C
            if (empty($nip)) {
                $rowErrors[] = 'NIP kosong';
            } elseif (!preg_match('/^\d{18}$/', (string) $nip)) {
                $rowErrors[] = 'NIP harus 18 digit';
            }

            // Validasi Tahun (required)
            $tahun = $row[0] ?? null; // Kolom A
            if (empty($tahun)) {
                $rowErrors[] = 'Tahun kosong';
            }

            // Validasi Bulan (required)
            $bulan = $row[1] ?? null; // Kolom B
            if (empty($bulan)) {
                $rowErrors[] = 'Bulan kosong';
            } elseif ($bulan < 1 || $bulan > 12) {
                $rowErrors[] = 'Bulan tidak valid (1-12)';
            }

            // Cek apakah NIP ada di database
            if (!empty($nip) && preg_match('/^\d{18}$/', (string) $nip)) {
                $userExists = DB::table('users')
                    ->where('nomor_induk', $nip)
                    ->exists();

                if (!$userExists) {
                    $rowErrors[] = 'NIP tidak ditemukan di database';
                }
            }

            // Jika ada error, masukkan ke invalidRows
            if (count($rowErrors) > 0) {
                $this->invalidRows[] = [
                    'row' => $rowNumber,
                    'data' => $row,
                    'errors' => $rowErrors,
                ];
            } else {
                // Parse periode
                $periode = sprintf('%04d-%02d', $tahun, $bulan);

                // Convert golongan
                $golonganExcel = $row[4] ?? null; // Kolom E
                $golongan = $this->convertGolongan($golonganExcel);

                $this->validRows[] = [
                    'row' => $rowNumber,
                    'periode' => $periode,
                    'nip' => (string) $nip,
                    'nama' => $row[3] ?? '',
                    'golongan_excel' => $golonganExcel,
                    'golongan' => $golongan,
                    'tukin' => $row[8] ?? 0,       // Kolom I
                    'tk_jumlah' => $row[9] ?? 0,   // Kolom J
                    'tk_persen' => $row[10] ?? 0,  // Kolom K
                    'tl' => $row[11] ?? 0,          // Kolom L (Potongan Telat)
                    'tl_persen' => $row[12] ?? 0,   // Kolom M
                    'psw' => $row[13] ?? 0,         // Kolom N
                    'psw_persen' => $row[14] ?? 0,  // Kolom O
                    'hukdis' => $row[15] ?? 0,      // Kolom P
                    'hukdis_persen' => $row[16] ?? 0, // Kolom Q
                    'cpns' => $row[17] ?? 0,        // Kolom R
                    'cpns_persen' => $row[18] ?? 0, // Kolom S
                    'skp' => $row[19] ?? 0,         // Kolom T
                    'skp_persen' => $row[20] ?? 0,  // Kolom U
                    'tb' => $row[21] ?? 0,          // Kolom V
                    'tb_persen' => $row[22] ?? 0,   // Kolom W
                    'potongan_lain' => $row[23] ?? 0, // Kolom X
                    'potongan_lain_persen' => $row[24] ?? 0, // Kolom Y
                    'total_potongan' => $row[25] ?? 0, // Kolom Z
                ];
            }
        }

        return [
            'valid_count' => count($this->validRows),
            'invalid_count' => count($this->invalidRows),
            'valid_rows' => $this->validRows,
            'invalid_rows' => $this->invalidRows,
        ];
    }

    /**
     * Convert golongan dari format Excel ke format database
     * Format Excel: III/a, IV/b, IX, V
     * Format Database: 3a, 4b, 9, 5
     */
    public function convertGolongan(?string $excelGolongan): ?string
    {
        if (empty($excelGolongan)) {
            return null;
        }

        // Roman numeral mapping
        $romanMap = [
            'X' => '10', 'IX' => '9', 'VIII' => '8', 'VII' => '7',
            'VI' => '6', 'V' => '5', 'IV' => '4', 'III' => '3',
            'II' => '2', 'I' => '1',
        ];

        // Try to match roman numeral
        foreach ($romanMap as $roman => $number) {
            if (str_starts_with($excelGolongan, $roman)) {
                $letter = substr($excelGolongan, strlen($roman));
                return $number . strtolower($letter);
            }
        }

        // If no roman numeral found, return as-is
        return strtolower($excelGolongan);
    }

    /**
     * Import data ke database
     */
    public function importToDatabase(array $validatedData, int $userId): array
    {
        $this->batchId = 'TUKIN_' . date('Ymd_His') . '_' . uniqid();
        $importedCount = 0;
        $skippedCount = 0;
        $updatedGolonganCount = 0;

        try {
            DB::beginTransaction();

            foreach ($validatedData['valid_rows'] as $rowData) {
                // Update users.golongan jika ada perubahan
                if (!empty($rowData['golongan'])) {
                    $userGolongan = DB::table('users')
                        ->where('nomor_induk', $rowData['nip'])
                        ->value('golongan');

                    if ($userGolongan !== $rowData['golongan']) {
                        DB::table('users')
                            ->where('nomor_induk', $rowData['nip'])
                            ->update(['golongan' => $rowData['golongan']]);
                        $updatedGolonganCount++;
                    }
                }

                // Insert/Update data tukin (1 record per user per periode)
                $existing = KtdTukin::where('user_nip', $rowData['nip'])
                    ->where('periode', $rowData['periode'])
                    ->first();

                if ($existing) {
                    $existing->update([
                        'tukin' => $rowData['tukin'],
                        'tk_jumlah' => $rowData['tk_jumlah'],
                        'tk_persen' => $rowData['tk_persen'],
                        'tl' => $rowData['tl'],
                        'tl_persen' => $rowData['tl_persen'],
                        'psw' => $rowData['psw'],
                        'psw_persen' => $rowData['psw_persen'],
                        'hukdis' => $rowData['hukdis'],
                        'hukdis_persen' => $rowData['hukdis_persen'],
                        'cpns' => $rowData['cpns'],
                        'cpns_persen' => $rowData['cpns_persen'],
                        'skp' => $rowData['skp'],
                        'skp_persen' => $rowData['skp_persen'],
                        'tb' => $rowData['tb'],
                        'tb_persen' => $rowData['tb_persen'],
                        'potongan_lain' => $rowData['potongan_lain'],
                        'potongan_lain_persen' => $rowData['potongan_lain_persen'],
                        'total_potongan' => $rowData['total_potongan'],
                        'import_batch_id' => $this->batchId,
                        'imported_by' => $userId,
                        'imported_at' => now(),
                        'import_source' => 'excel_manual',
                    ]);
                } else {
                    KtdTukin::create([
                        'periode' => $rowData['periode'],
                        'user_nip' => $rowData['nip'],
                        'tukin' => $rowData['tukin'],
                        'tk_jumlah' => $rowData['tk_jumlah'],
                        'tk_persen' => $rowData['tk_persen'],
                        'tl' => $rowData['tl'],
                        'tl_persen' => $rowData['tl_persen'],
                        'psw' => $rowData['psw'],
                        'psw_persen' => $rowData['psw_persen'],
                        'hukdis' => $rowData['hukdis'],
                        'hukdis_persen' => $rowData['hukdis_persen'],
                        'cpns' => $rowData['cpns'],
                        'cpns_persen' => $rowData['cpns_persen'],
                        'skp' => $rowData['skp'],
                        'skp_persen' => $rowData['skp_persen'],
                        'tb' => $rowData['tb'],
                        'tb_persen' => $rowData['tb_persen'],
                        'potongan_lain' => $rowData['potongan_lain'],
                        'potongan_lain_persen' => $rowData['potongan_lain_persen'],
                        'total_potongan' => $rowData['total_potongan'],
                        'import_batch_id' => $this->batchId,
                        'imported_by' => $userId,
                        'imported_at' => now(),
                        'import_source' => 'excel_manual',
                    ]);
                }

                $importedCount++;
            }

            DB::commit();

            Log::info("Import tukin berhasil", [
                'batch_id' => $this->batchId,
                'imported' => $importedCount,
                'updated_golongan' => $updatedGolonganCount,
                'user_id' => $userId,
            ]);

            return [
                'success' => true,
                'batch_id' => $this->batchId,
                'imported_count' => $importedCount,
                'skipped_count' => $skippedCount,
                'updated_golongan_count' => $updatedGolonganCount,
                'message' => "Berhasil import {$importedCount} data tukin",
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Import tukin gagal: ' . $e->getMessage(), [
                'batch_id' => $this->batchId,
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'error' => 'Gagal import data: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Rollback import berdasarkan batch ID
     */
    public function rollbackImport(string $batchId): array
    {
        try {
            $deletedCount = KtdTukin::where('import_batch_id', $batchId)
                ->delete();

            Log::info("Rollback import tukin", [
                'batch_id' => $batchId,
                'deleted' => $deletedCount,
            ]);

            return [
                'success' => true,
                'deleted_count' => $deletedCount,
                'message' => "Berhasil rollback {$deletedCount} data tukin",
            ];

        } catch (\Exception $e) {
            Log::error('Rollback import tukin gagal: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Gagal rollback: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Get riwayat import
     */
    public function getImportHistory(): array
    {
        return KtdTukin::select('import_batch_id', 'imported_by', 'imported_at')
            ->whereNotNull('import_batch_id')
            ->groupBy('import_batch_id', 'imported_by', 'imported_at')
            ->orderByDesc('imported_at')
            ->get()
            ->map(function ($item) {
                $user = DB::table('users')->find($item->imported_by);
                return [
                    'batch_id' => $item->import_batch_id,
                    'imported_by' => $user->name ?? 'Unknown',
                    'imported_at' => $item->imported_at,
                    'total_records' => KtdTukin::where('import_batch_id', $item->import_batch_id)->count(),
                ];
            })
            ->toArray();
    }
}
