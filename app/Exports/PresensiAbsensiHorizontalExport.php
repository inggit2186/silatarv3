<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\Exportable;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Enumerable;

class PresensiAbsensiHorizontalExport implements \Maatwebsite\Excel\Concerns\Export, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    use Exportable;

    protected $userId;
    protected $month;
    protected $year;
    protected $userName;
    protected $userNip;
    protected $presensiData;

    public function __construct(int $userId, int $month, int $year)
    {
        $this->userId = $userId;
        $this->month = $month;
        $this->year = $year;

        // Get user info
        $user = DB::table('users')->find($userId);
        $this->userName = $user->name ?? 'Unknown';
        $this->userNip = $user->nomor_induk ?? 'Unknown';

        // Load presensi data
        $this->loadPresensiData();
    }

    protected function loadPresensiData(): void
    {
        $this->presensiData = DB::table('ktd_presensi')
            ->where('user_nip', $this->userNip)
            ->whereYear('tanggal', $this->year)
            ->whereMonth('tanggal', $this->month)
            ->get()
            ->keyBy(function ($row) {
                return (int) date('d', strtotime($row->tanggal));
            });
    }

    public function headings(): array
    {
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $this->month, $this->year);
        $headings = ['Hari'];

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $headings[] = $day;
        }

        $headings[] = 'Total';
        $headings[] = 'Keterangan';

        return $headings;
    }

    public function collection(): Enumerable
    {
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $this->month, $this->year);

        // Get all days with day names
        $data = [];
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

        // Build rows for each day of week
        for ($dayOfWeek = 0; $dayOfWeek < 7; $dayOfWeek++) {
            $row = [
                'day_name' => $days[$dayOfWeek],
                'values' => [],
                'total' => 0,
            ];

            // Check each day of month
            for ($day = 1; $day <= $daysInMonth; $day++) {
                $date = sprintf('%04d-%02d-%02d', $this->year, $this->month, $day);
                $actualDayOfWeek = (int) date('N', strtotime($date)) - 1; // 0=Monday, 6=Sunday

                if ($actualDayOfWeek === $dayOfWeek) {
                    // Check if has presensi
                    $hasPresensi = isset($this->presensiData[$day]) &&
                        (!empty($this->presensiData[$day]->m_absen) || !empty($this->presensiData[$day]->p_absen));

                    $row['values'][$day] = $hasPresensi ? 1 : 0;
                    if ($hasPresensi) {
                        $row['total']++;
                    }
                } else {
                    $row['values'][$day] = null; // Empty for days not matching this day of week
                }
            }

            $data[] = $row;
        }

        return collect($data);
    }

    public function map($row): array
    {
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $this->month, $this->year);
        $mapped = [$row['day_name']];

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $mapped[] = $row['values'][$day] ?? '';
        }

        $mapped[] = $row['total'];
        $mapped[] = ''; // Keterangan column

        return $mapped;
    }

    public function styles(Worksheet $sheet): array
    {
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $this->month, $this->year);
        $lastCol = chr(65 + $daysInMonth + 2); // +2 for Hari and Total columns

        // Title styling
        $sheet->mergeCells("A1:{$lastCol}1");
        $sheet->setCellValue('A1', 'REKAP ABSENSI BULANAN - FORMAT HORIZONTAL');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells("A2:{$lastCol}2");
        $sheet->setCellValue('A2', $this->userName . ' - NIP: ' . $this->userNip);
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(11);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells("A3:{$lastCol}3");
        $sheet->setCellValue('A3', 'Bulan: ' . $this->getMonthName() . ' ' . $this->year);
        $sheet->getStyle('A3')->getFont()->setBold(true)->setSize(11);
        $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Header styling (row 5)
        $headerStyle = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 10,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['rgb' => '28A745'], // Hijau
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ];

        $sheet->getStyle("A5:{$lastCol}5")->applyFromArray($headerStyle);

        // Data styling (row 6-12)
        $dataStyle = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ];

        $sheet->getStyle("A6:{$lastCol}12")->applyFromArray($dataStyle);

        // Highlight weekends (Saturday row 11, Sunday row 12)
        $weekendStyle = [
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['rgb' => 'FFF3CD'], // Kuning muda
            ],
        ];

        $sheet->getStyle("A11:{$lastCol}11")->applyFromArray($weekendStyle); // Sabtu
        $sheet->getStyle("A12:{$lastCol}12")->applyFromArray($weekendStyle); // Minggu

        // Highlight value 1 (green)
        $value1Style = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => '155724'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['rgb' => 'D4EDDA'], // Hijau muda
            ],
        ];

        // Apply to all cells with value 1
        for ($row = 6; $row <= 12; $row++) {
            for ($col = 2; $col <= $daysInMonth + 1; $col++) {
                $cell = $sheet->getCellByRowAndColumn($row, $col);
                if ($cell->getValue() === 1) {
                    $sheet->getStyleByColumnAndRow($col, $row)->applyFromArray($value1Style);
                }
            }
        }

        // Total column styling
        $totalStyle = [
            'font' => [
                'bold' => true,
                'size' => 11,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['rgb' => 'E2E3E5'], // Abu-abu
            ],
        ];

        $sheet->getStyle("{$lastCol}6:{$lastCol}12")->applyFromArray($totalStyle);

        // Auto filter
        $sheet->setAutoFilter("A5:{$lastCol}5");

        // Freeze panes
        $sheet->freezePane('B6');

        // Column widths
        $sheet->getColumnDimension('A')->setWidth(12);
        for ($col = 2; $col <= $daysInMonth + 1; $col++) {
            $sheet->getColumnDimension(chr(64 + $col))->setWidth(6);
        }
        $sheet->getColumnDimension($lastCol)->setWidth(8);
        $sheet->getColumnDimension(chr(ord($lastCol) + 1))->setWidth(15);

        return [];
    }

    protected function getMonthName(): string
    {
        $months = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        return $months[$this->month] ?? 'Unknown';
    }
}
