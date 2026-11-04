<?php

namespace App\Services;

use App\Models\KtdPresensi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class PresensiImportService
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

        $spreadsheet = null;
        try {
            $spreadsheet = IOFactory::load($filePath);
            $data = [];
            $headers = [];
            $emptyCount = 0;
            foreach ($spreadsheet->getWorksheetIterator() as $sheet) {
                $highestRow = $sheet->getHighestDataRow();
                $highestColumn = Coordinate::columnIndexFromString($sheet->getHighestDataColumn());
                if ($highestRow === 1 && $highestColumn === 1 && $sheet->getCell('A1')->getValue() === null) {
                    continue;
                }
                $headers = $this->getHeaders($sheet);
                foreach ([1 => 'NIP', 3 => 'TANGGAL', 6 => 'ABSEN MASUK', 9 => 'ABSEN PULANG'] as $index => $expected) {
                    if (strtoupper(trim((string) ($headers[$index] ?? ''))) !== $expected) {
                        throw new \RuntimeException("Header sheet {$sheet->getTitle()} kolom ".Coordinate::stringFromColumnIndex($index + 1)." harus {$expected}");
                    }
                }
                for ($row = 2; $row <= $highestRow; $row++) {
                    $rowData = [];
                    for ($col = 1; $col <= max(18, $highestColumn); $col++) {
                        $rowData[] = $sheet->getCell([$col, $row])->getCalculatedValue();
                    }
                    if (count(array_filter($rowData, fn ($value) => $value !== null && trim((string) $value) !== '')) === 0) {
                        $emptyCount++;

                        continue;
                    }
                    if (is_numeric($rowData[3]) && $rowData[3] > 0) {
                        $rowData[3] = Date::excelToDateTimeObject((float) $rowData[3])->format('Y-m-d');
                    }
                    $rowData['_row'] = $row;
                    $rowData['_sheet'] = $sheet->getTitle();
                    $data[] = $rowData;
                }
            }

            return [
                'success' => true,
                'headers' => $headers,
                'data' => $data,
                'total_rows' => count($data),
                'empty_count' => $emptyCount,
            ];
        } catch (\Throwable $e) {
            Log::error('Excel parse error: '.$e->getMessage());

            return [
                'success' => false,
                'error' => 'Gagal membaca file Excel: '.$e->getMessage(),
            ];
        } finally {
            $spreadsheet?->disconnectWorksheets();
        }
    }

    /**
     * Get headers from Excel sheet
     */
    protected function getHeaders($sheet): array
    {
        $headers = [];
        for ($col = 1; $col <= Coordinate::columnIndexFromString($sheet->getHighestDataColumn()); $col++) {
            $headers[] = $sheet->getCell([$col, 1])->getCalculatedValue();
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

        $headers = [
            'A' => 'NAMA',
            'B' => 'NIP',
            'C' => 'JABATAN',
            'D' => 'TANGGAL',
            'E' => 'HARI',
            'F' => 'JAM MASUK',
            'G' => 'ABSEN MASUK',
            'H' => 'CEPAT TELAT',
            'I' => 'JAM PULANG',
            'J' => 'ABSEN PULANG',
            'K' => 'PSW',
            'L' => 'LIBUR',
            'M' => 'JENIS TUGAS',
            'N' => 'KETERANGAN',
            'O' => 'KETERANGAN 2',
            'P' => 'SATKER_2',
            'Q' => 'SATKER_3',
            'R' => 'STATUS PEGAWAI',
        ];

        foreach ($data as $index => $row) {
            $rowNumber = $row['_row'] ?? $index + 2;
            $rowErrors = [];
            $sheetName = $row['_sheet'] ?? '-';

            $rawNip = $row[1] ?? null;
            $nip = is_string($rawNip) ? trim(str_replace("\u{00A0}", ' ', $rawNip)) : '';
            if (is_int($rawNip) || is_float($rawNip)) {
                $rowErrors[] = 'NIP numerik Excel tidak aman (presisi 18 digit); ekspor ulang NIP sebagai teks';
            }
            if (empty($nip)) {
                $rowErrors[] = 'NIP kosong';
            } elseif (! preg_match('/^\d{18}$/', $nip)) {
                $rowErrors[] = 'NIP harus 18 digit';
            }

            // Validasi Tanggal (required)
            $tanggal = $row[3] ?? null;
            if (empty($tanggal)) {
                $rowErrors[] = 'Tanggal kosong';
            } else {
                $tanggal = $this->parseDate($tanggal);
                if (! $tanggal) {
                    $rowErrors[] = 'Format tanggal tidak valid';
                }
            }

            // Cek apakah NIP ada di database
            if (! empty($nip) && preg_match('/^\d{18}$/', $nip)) {
                $userExists = DB::table('users')
                    ->where('nomor_induk', $nip)
                    ->exists();

                if (! $userExists) {
                    $rowErrors[] = 'NIP tidak ditemukan di database';
                }
            }

            // Ambil data dari Excel dengan mapping yang benar
            $absenMasuk = $row[6] ?? null;      // G: ABSEN MASUK
            $absenPulang = $row[9] ?? null;     // J: ABSEN PULANG
            $cepatTelat = $row[7] ?? null;      // H: CEPAT TELAT (dalam menit)
            $psw = $row[10] ?? null;            // K: PSW (dalam menit)
            $jenisTugas = $row[12] ?? null;     // M: JENIS TUGAS

            foreach (['ABSEN MASUK' => $absenMasuk, 'ABSEN PULANG' => $absenPulang] as $field => $value) {
                if ($value !== null && trim((string) $value) !== '' && $this->normalizeTime($value) === null) {
                    $rowErrors[] = "{$field}: format jam tidak valid";
                }
            }
            $absenMasuk = $this->normalizeTime($absenMasuk);
            $absenPulang = $this->normalizeTime($absenPulang);
            foreach (['CEPAT TELAT' => $cepatTelat, 'PSW' => $psw] as $field => $value) {
                if ($value !== null && trim((string) $value) !== '' && ! is_numeric($value)) {
                    $rowErrors[] = "{$field}: harus angka menit";
                }
            }

            $mDiff = $this->convertMinutesToDiff($cepatTelat);
            $pDiff = $this->convertMinutesToDiff($psw);

            // Jika ada error, masukkan ke invalidRows
            if (count($rowErrors) > 0) {
                $this->invalidRows[] = [
                    'row' => $rowNumber,
                    'sheet' => $sheetName,
                    'data' => $row,
                    'errors' => $rowErrors,
                ];
            } else {
                $this->validRows[] = [
                    'row' => $rowNumber,
                    'sheet' => $sheetName,
                    'nip' => $nip,
                    'nama' => $row[0] ?? '',
                    'jabatan' => $row[2] ?? '',
                    'tanggal' => $tanggal,
                    'hari' => $row[4] ?? '',
                    'absen_masuk' => $absenMasuk,
                    'absen_pulang' => $absenPulang,
                    'm_diff' => $mDiff,
                    'p_diff' => $pDiff,
                    'jenis_tugas' => $jenisTugas,
                    'keterangan' => $row[13] ?? '',
                    'keterangan2' => $row[14] ?? '',
                    'satker' => $row[15] ?? null,
                    'status_pegawai' => $row[17] ?? '',
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
     * Import data ke database
     */
    public function importToDatabase(array $validatedData, int $userId): array
    {
        $this->batchId = 'IMPORT_'.date('Ymd_His').'_'.uniqid();
        $importedCount = 0;
        $updatedCount = 0;
        $transactionLevel = DB::transactionLevel();

        try {
            DB::beginTransaction();

            foreach ($validatedData['valid_rows'] as $rowData) {
                $existing = KtdPresensi::where('user_nip', $rowData['nip'])
                    ->whereDate('tanggal', $rowData['tanggal'])
                    ->lockForUpdate()
                    ->get();

                $values = [
                    'user_nip' => $rowData['nip'],
                    'tanggal' => $rowData['tanggal'],
                    'm_absen' => $rowData['absen_masuk'] ?: null,    // ABSEN MASUK
                    'm_diff' => $rowData['m_diff'],                  // CEPAT TELAT (menit)
                    'p_absen' => $rowData['absen_pulang'] ?: null,   // ABSEN PULANG
                    'p_diff' => $rowData['p_diff'],                  // PSW (menit)
                    'status' => $rowData['jenis_tugas'] ?: null,     // JENIS TUGAS
                    'keterangan' => $rowData['keterangan'] ?: null,
                ];
                if ($existing->isNotEmpty()) {
                    // Preserve batch ownership so rollback cannot delete pre-existing records.
                    unset($values['user_nip'], $values['tanggal']);
                    foreach ($existing as $record) {
                        $record->update($values);
                    }
                    $updatedCount++;
                } else {
                    KtdPresensi::create($values + [
                        'import_batch_id' => $this->batchId,
                        'imported_by' => $userId,
                        'imported_at' => now(),
                        'import_source' => 'excel_manual',
                    ]);
                    $importedCount++;
                }
            }

            DB::commit();

            Log::info('Import presensi berhasil', [
                'batch_id' => $this->batchId,
                'imported' => $importedCount,
                'updated' => $updatedCount,
                'user_id' => $userId,
            ]);

            return [
                'success' => true,
                'batch_id' => $this->batchId,
                'imported_count' => $importedCount,
                'skipped_count' => 0,
                'updated_count' => $updatedCount,
                'message' => "Berhasil import {$importedCount} data baru dan memperbarui {$updatedCount} data presensi",
            ];

        } catch (\Throwable $e) {
            if (DB::transactionLevel() > $transactionLevel) {
                DB::rollBack();
            }
            Log::error('Import presensi gagal: '.$e->getMessage(), [
                'batch_id' => $this->batchId,
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'imported_count' => 0,
                'updated_count' => 0,
                'skipped_count' => 0,
                'error' => 'Gagal import data (seluruh perubahan file dibatalkan), sheet '.($rowData['sheet'] ?? '-').' baris '.($rowData['row'] ?? '-').': '.$e->getMessage(),
            ];
        }
    }

    /**
     * Rollback import berdasarkan batch ID
     */
    public function rollbackImport(string $batchId): array
    {
        try {
            $deletedCount = KtdPresensi::where('import_batch_id', $batchId)
                ->delete();

            Log::info('Rollback import presensi', [
                'batch_id' => $batchId,
                'deleted' => $deletedCount,
            ]);

            return [
                'success' => true,
                'deleted_count' => $deletedCount,
                'message' => "Berhasil rollback {$deletedCount} data presensi",
            ];

        } catch (\Exception $e) {
            Log::error('Rollback import gagal: '.$e->getMessage());

            return [
                'success' => false,
                'error' => 'Gagal rollback: '.$e->getMessage(),
            ];
        }
    }

    /**
     * Get riwayat import
     */
    public function getImportHistory(): array
    {
        return KtdPresensi::select('import_batch_id', 'imported_by', 'imported_at')
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
                    'total_records' => KtdPresensi::where('import_batch_id', $item->import_batch_id)->count(),
                ];
            })
            ->toArray();
    }

    /**
     * Convert menit ke format diff yang sesuai
     * Format: +XX atau -XX (menit)
     */
    protected function convertMinutesToDiff($minutes): ?string
    {
        if ($minutes === null || $minutes === '' || ! is_numeric($minutes)) {
            return null;
        }

        $minutes = (int) $minutes;

        if ($minutes > 0) {
            return "+{$minutes}";
        } elseif ($minutes < 0) {
            return (string) $minutes;
        }

        return '0';
    }

    /**
     * Parse date dari Excel format
     */
    protected function parseDate($value)
    {
        if (empty($value)) {
            return null;
        }

        if (is_numeric($value) && $value > 0 && $value < 2958466) {
            return Date::excelToDateTimeObject((float) $value)->format('Y-m-d');
        }
        $value = trim((string) $value);
        foreach (['Y-m-d', 'd/m/Y', 'd-m-Y', 'Y-m-d H:i:s'] as $format) {
            $date = \DateTimeImmutable::createFromFormat('!'.$format, $value);
            if ($date && $date->format($format) === $value) {
                return $date->format('Y-m-d');
            }
        }

        return null;
    }

    /**
     * Validate time format
     */
    protected function normalizeTime($value): ?string
    {
        if ($value === null || trim((string) $value) === '') {
            return null;
        }
        if (is_numeric($value) && $value >= 0 && $value < 1) {
            return gmdate('H:i:s', (int) round((float) $value * 86400) % 86400);
        }
        $value = trim((string) $value);
        if ($this->isValidTime($value)) {
            return strlen($value) === 5 ? $value.':00' : $value;
        }

        return null;
    }

    protected function isValidTime($time): bool
    {
        return preg_match('/^([01]\d|2[0-3]):([0-5]\d)(:[0-5]\d)?$/', $time);
    }
}
