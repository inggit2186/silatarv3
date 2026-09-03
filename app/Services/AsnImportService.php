<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;

class AsnImportService
{
    protected $errors = [];
    protected $validRows = [];
    protected $skippedRows = [];

    /**
     * Parse file Excel dan return data
     */
    public function parseExcel($filePath): array
    {
        ini_set('memory_limit', '1024M');

        try {
            $spreadsheet = IOFactory::load($filePath);
            $sheet = $spreadsheet->getActiveSheet();
            $highestRow = $sheet->getHighestRow();

            // Validate headers
            $expectedHeaders = ['No', 'Kategori', 'ASN', 'Nama', 'JK', 'NIP', 'NIK', 'KK', 'NPWP', 'Serdik', 'Kategori Bank', 'Rekening'];
            $actualHeaders = [];
            $colLetters = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L'];
            foreach ($colLetters as $col) {
                $actualHeaders[] = $sheet->getCell($col . '1')->getValue();
            }

            if ($actualHeaders !== $expectedHeaders) {
                return [
                    'success' => false,
                    'error' => 'Header Excel tidak sesuai. Diperlukan: ' . implode(', ', $expectedHeaders),
                ];
            }

            $data = [];
            for ($row = 2; $row <= $highestRow; $row++) {
                $data[] = [
                    'no' => $sheet->getCell('A' . $row)->getValue(),
                    'kategori' => $sheet->getCell('B' . $row)->getValue(),
                    'asn' => $sheet->getCell('C' . $row)->getValue(),
                    'nama' => $sheet->getCell('D' . $row)->getValue(),
                    'jk' => $sheet->getCell('E' . $row)->getValue(),
                    'nip' => $this->cleanValue($sheet->getCell('F' . $row)->getValue()),
                    'nik' => $this->cleanValue($sheet->getCell('G' . $row)->getValue()),
                    'kk' => $this->cleanValue($sheet->getCell('H' . $row)->getValue()),
                    'npwp' => $this->cleanValue($sheet->getCell('I' . $row)->getValue()),
                    'serdik' => $sheet->getCell('J' . $row)->getValue(),
                    'bank_kategori' => $sheet->getCell('K' . $row)->getValue(),
                    'rekening' => $this->cleanValue($sheet->getCell('L' . $row)->getValue()),
                ];

                if ($row % 100 == 0) {
                    gc_collect_cycles();
                }
            }

            return [
                'success' => true,
                'data' => $data,
                'total_rows' => $highestRow - 1,
            ];
        } catch (\Exception $e) {
            Log::error('Excel parse error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Gagal membaca file Excel: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Validasi data — cek NIP ada di DB, kumpulkan status untuk preview
     */
    public function validateData(array $data): array
    {
        $this->errors = [];
        $this->validRows = [];
        $this->skippedRows = [];

        // Collect all NIPs from Excel
        $nips = array_filter(array_column($data, 'nip'));

        // Fetch existing records from DB
        $existingTenaga = DB::table('tenaga_ktd')
            ->whereIn('nomor_induk', $nips)
            ->pluck('nomor_induk')
            ->toArray();

        $existingUsers = DB::table('users')
            ->whereIn('nomor_induk', $nips)
            ->pluck('nomor_induk')
            ->toArray();

        foreach ($data as $index => $row) {
            $rowNum = $index + 2; // +2 because index starts at 0 and header is row 1
            $nip = $row['nip'];

            // Skip if NIP is empty
            if (empty($nip)) {
                $this->skippedRows[] = array_merge($row, [
                    'status' => 'skip',
                    'reason' => 'NIP kosong',
                    'row' => $rowNum,
                ]);
                continue;
            }

            // Skip if NIP not found in tenaga_ktd
            if (!in_array($nip, $existingTenaga)) {
                $this->skippedRows[] = array_merge($row, [
                    'status' => 'skip',
                    'reason' => 'NIP tidak ditemukan di database',
                    'row' => $rowNum,
                ]);
                continue;
            }

            // Check if user also exists
            $hasUser = in_array($nip, $existingUsers);

            $this->validRows[] = array_merge($row, [
                'status' => 'update',
                'has_user' => $hasUser,
                'row' => $rowNum,
            ]);
        }

        return [
            'valid' => $this->validRows,
            'skipped' => $this->skippedRows,
            'total_valid' => count($this->validRows),
            'total_skipped' => count($this->skippedRows),
            'total_rows' => count($data),
        ];
    }

    /**
     * Execute import — UPDATE ONLY untuk data yang valid
     */
    public function importData(array $validatedData, int $userId): array
    {
        $batchId = uniqid('import_');
        $updatedCount = 0;
        $skippedCount = 0;
        $errors = [];

        $validRows = $validatedData['valid'];
        $batchSize = 100;

        try {
            foreach (array_chunk($validRows, $batchSize) as $batch) {
                DB::transaction(function () use ($batch, $userId, &$updatedCount, &$skippedCount, &$errors) {
                    foreach ($batch as $row) {
                        try {
                            $nip = $row['nip'];

                            // Build tenaga_ktd update — only non-empty Excel values
                            $tenagaUpdate = $this->mapToTenagaKtd($row);
                            if (!empty($tenagaUpdate)) {
                                DB::table('tenaga_ktd')
                                    ->where('nomor_induk', $nip)
                                    ->update($tenagaUpdate);
                            }

                            // Build users update — only non-empty Excel values
                            $userUpdate = $this->mapToUsers($row);
                            if (!empty($userUpdate) && $row['has_user']) {
                                DB::table('users')
                                    ->where('nomor_induk', $nip)
                                    ->update($userUpdate);
                            }

                            $updatedCount++;
                        } catch (\Exception $e) {
                            $skippedCount++;
                            $errors[] = "Row {$row['row']} (NIP: {$row['nip']}): " . $e->getMessage();
                            Log::error("Import error row {$row['row']}: " . $e->getMessage());
                        }
                    }
                });
            }

            // Log import activity
            $this->logImport($batchId, $validatedData, $userId, $updatedCount, $skippedCount, $errors);

            return [
                'success' => true,
                'batch_id' => $batchId,
                'message' => "Import selesai: {$updatedCount} data di-update, {$skippedCount} dilewati.",
                'updated_count' => $updatedCount,
                'skipped_count' => $skippedCount,
                'errors' => $errors,
            ];
        } catch (\Exception $e) {
            Log::error('Import error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Gagal import: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Map Excel row ke kolom tenaga_ktd (UPDATE ONLY, skip kosong)
     */
    protected function mapToTenagaKtd(array $row): array
    {
        $update = [];

        // Only add to update array if Excel value is not empty/null
        if (!empty($row['nama'])) {
            $update['nama'] = $row['nama'];
        }
        if (!empty($row['asn'])) {
            $update['kat_jabatan'] = $row['asn'];
        }
        if (!empty($row['kategori'])) {
            $update['status'] = $row['kategori'];
        }
        if (!empty($row['jk'])) {
            $update['jenis_kelamin'] = $row['jk'] === 'Pria' ? 'Laki-laki' : 'Perempuan';
        }
        if (!empty($row['nik'])) {
            $update['nik'] = $row['nik'];
        }
        if (!empty($row['kk'])) {
            $update['kk'] = $row['kk'];
        }
        if (!empty($row['npwp'])) {
            $update['npwp'] = $row['npwp'];
        }

        // Serdik: NONE → null, otherwise keep value
        $update['serdik'] = ($row['serdik'] === 'NONE' || empty($row['serdik'])) ? null : $row['serdik'];

        if (!empty($row['rekening'])) {
            $update['rekening'] = $row['rekening'];
        }

        return $update;
    }

    /**
     * Map Excel row ke kolom users (UPDATE ONLY, skip kosong)
     */
    protected function mapToUsers(array $row): array
    {
        $update = [];

        if (!empty($row['nama'])) {
            $update['name'] = $row['nama'];
        }
        if (!empty($row['jk'])) {
            $update['jk'] = $row['jk'] === 'Pria' ? 'Laki-laki' : 'Perempuan';
        }
        if (!empty($row['asn'])) {
            $update['kat_jabatan'] = $row['asn'];
        }
        if (!empty($row['bank_kategori'])) {
            $update['bank_kategori'] = $row['bank_kategori'];
        }
        if (!empty($row['rekening'])) {
            $update['rekening'] = $row['rekening'];
        }

        return $update;
    }

    /**
     * Clean numeric string values (remove spaces, leading zeros preserved)
     */
    protected function cleanValue($value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        // Convert to string and trim
        $val = trim((string) $value);

        return $val === '' ? null : $val;
    }

    /**
     * Log import activity
     */
    protected function logImport(
        string $batchId,
        array $validatedData,
        int $userId,
        int $updatedCount,
        int $skippedCount,
        array $errors
    ): void {
        try {
            DB::table('activities')->insert([
                'user_id' => $userId,
                'activity' => 'import_asn',
                'description' => json_encode([
                    'batch_id' => $batchId,
                    'total_rows' => $validatedData['total_rows'],
                    'updated' => $updatedCount,
                    'skipped' => $skippedCount,
                    'errors' => count($errors),
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to log import activity: ' . $e->getMessage());
        }
    }

    /**
     * Get import history from activities table
     */
    public function getImportHistory(): array
    {
        try {
            return DB::table('activities')
                ->where('activity', 'import_asn')
                ->join('users', 'activities.user_id', '=', 'users.id')
                ->select('activities.*', 'users.name as user_name')
                ->orderByDesc('activities.created_at')
                ->limit(20)
                ->get()
                ->map(function ($item) {
                    $desc = json_decode($item->description, true);
                    return [
                        'batch_id' => $desc['batch_id'] ?? '',
                        'total_rows' => $desc['total_rows'] ?? 0,
                        'updated' => $desc['updated'] ?? 0,
                        'skipped' => $desc['skipped'] ?? 0,
                        'errors' => $desc['errors'] ?? 0,
                        'user_name' => $item->user_name,
                        'created_at' => $item->created_at,
                    ];
                })
                ->toArray();
        } catch (\Exception $e) {
            Log::error('Failed to get import history: ' . $e->getMessage());
            return [];
        }
    }
}
