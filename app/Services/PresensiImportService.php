<?php

namespace App\Services;

use App\Models\KtdPresensi;
use App\Models\Department;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Exception;

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
        try {
            $spreadsheet = IOFactory::load($filePath);
            $sheet = $spreadsheet->getActiveSheet();
            $highestRow = $sheet->getHighestRow();
            $highestColumn = ord($sheet->getHighestColumn());

            $data = [];
            for ($row = 2; $row <= $highestRow; $row++) {
                $rowData = [];
                for ($col = 'A'; ord($col) <= $highestColumn; $col++) {
                    $rowData[] = $sheet->getCell($col . $row)->getValue();
                }
                $data[] = $rowData;
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
        for ($col = 'A'; $col <= $sheet->getHighestColumn(); $col++) {
            $headers[] = $sheet->getCell($col . '1')->getValue();
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
            $rowNumber = $index + 2; // +2 karena header di row 1, dan index 0 = row 2
            $rowErrors = [];

            // Validasi NIP (required)
            $nip = $row[1] ?? null;
            if (empty($nip)) {
                $rowErrors[] = 'NIP kosong';
            } elseif (!preg_match('/^\d{18}$/', $nip)) {
                $rowErrors[] = 'NIP harus 18 digit';
            }

            // Validasi Tanggal (required)
            $tanggal = $row[3] ?? null;
            if (empty($tanggal)) {
                $rowErrors[] = 'Tanggal kosong';
            } else {
                $tanggal = $this->parseDate($tanggal);
                if (!$tanggal) {
                    $rowErrors[] = 'Format tanggal tidak valid';
                }
            }

            // Cek apakah NIP ada di database
            if (!empty($nip) && preg_match('/^\d{18}$/', $nip)) {
                $userExists = DB::table('users')
                    ->where('nomor_induk', $nip)
                    ->exists();

                if (!$userExists) {
                    $rowErrors[] = 'NIP tidak ditemukan di database';
                }
            }

            // Ambil data dari Excel dengan mapping yang benar
            $absenMasuk = $row[6] ?? null;      // G: ABSEN MASUK
            $absenPulang = $row[9] ?? null;     // J: ABSEN PULANG
            $cepatTelat = $row[7] ?? null;      // H: CEPAT TELAT (dalam menit)
            $psw = $row[10] ?? null;            // K: PSW (dalam menit)
            $jenisTugas = $row[12] ?? null;     // M: JENIS TUGAS

            // Convert menit ke format yang sesuai
            $mDiff = $this->convertMinutesToDiff($cepatTelat);
            $pDiff = $this->convertMinutesToDiff($psw);

            // Jika ada error, masukkan ke invalidRows
            if (count($rowErrors) > 0) {
                $this->invalidRows[] = [
                    'row' => $rowNumber,
                    'data' => $row,
                    'errors' => $rowErrors,
                ];
            } else {
                $this->validRows[] = [
                    'row' => $rowNumber,
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
        $this->batchId = 'IMPORT_' . date('Ymd_His') . '_' . uniqid();
        $importedCount = 0;
        $skippedCount = 0;
        $errors = [];

        try {
            DB::beginTransaction();

            foreach ($validatedData['valid_rows'] as $rowData) {
                // Cek duplikat (NIP + tanggal sudah ada)
                $exists = KtdPresensi::where('user_nip', $rowData['nip'])
                    ->whereDate('tanggal', $rowData['tanggal'])
                    ->exists();

                if ($exists) {
                    $skippedCount++;
                    continue;
                }

                // Insert data dengan mapping yang benar
                KtdPresensi::create([
                    'user_nip' => $rowData['nip'],
                    'tanggal' => $rowData['tanggal'],
                    'm_absen' => $rowData['absen_masuk'] ?: null,    // ABSEN MASUK
                    'm_diff' => $rowData['m_diff'],                  // CEPAT TELAT (menit)
                    'p_absen' => $rowData['absen_pulang'] ?: null,   // ABSEN PULANG
                    'p_diff' => $rowData['p_diff'],                  // PSW (menit)
                    'status' => $rowData['jenis_tugas'] ?: null,     // JENIS TUGAS
                    'keterangan' => $rowData['keterangan'] ?: null,
                    'import_batch_id' => $this->batchId,
                    'imported_by' => $userId,
                    'imported_at' => now(),
                    'import_source' => 'excel_manual',
                ]);

                $importedCount++;
            }

            DB::commit();

            Log::info("Import presensi berhasil", [
                'batch_id' => $this->batchId,
                'imported' => $importedCount,
                'skipped' => $skippedCount,
                'user_id' => $userId,
            ]);

            return [
                'success' => true,
                'batch_id' => $this->batchId,
                'imported_count' => $importedCount,
                'skipped_count' => $skippedCount,
                'message' => "Berhasil import {$importedCount} data presensi",
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Import presensi gagal: ' . $e->getMessage(), [
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
            $deletedCount = KtdPresensi::where('import_batch_id', $batchId)
                ->delete();

            Log::info("Rollback import presensi", [
                'batch_id' => $batchId,
                'deleted' => $deletedCount,
            ]);

            return [
                'success' => true,
                'deleted_count' => $deletedCount,
                'message' => "Berhasil rollback {$deletedCount} data presensi",
            ];

        } catch (\Exception $e) {
            Log::error('Rollback import gagal: ' . $e->getMessage());
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
        if (empty($minutes) || !is_numeric($minutes)) {
            return null;
        }

        $minutes = (int) $minutes;

        if ($minutes > 0) {
            return "+{$minutes}";
        } elseif ($minutes < 0) {
            return (string) $minutes;
        }

        return "0";
    }

    /**
     * Parse date dari Excel format
     */
    protected function parseDate($value)
    {
        if (empty($value)) {
            return null;
        }

        // Jika sudah dalam format yyyy-mm-dd
        if (preg_match('/^\d{4}-\d{2}-\d{2}/', $value)) {
            return substr($value, 0, 10);
        }

        // Coba parse sebagai timestamp
        $timestamp = strtotime($value);
        if ($timestamp) {
            return date('Y-m-d', $timestamp);
        }

        return null;
    }

    /**
     * Validate time format
     */
    protected function isValidTime($time): bool
    {
        return preg_match('/^([01]\d|2[0-3]):([0-5]\d)(:[0-5]\d)?$/', $time);
    }
}
