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

class PresensiDetailHorizontalExport implements \Maatwebsite\Excel\Concerns\Export, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    use Exportable;

    protected $users;
    protected $month;
    protected $year;
    public $presensiData;

    public function __construct($users, int $month, int $year)
    {
        $this->users = $users;
        $this->month = $month;
        $this->year = $year;

        // Load all presensi data for all users
        $this->loadPresensiData();
    }

    protected function loadPresensiData(): void
    {
        $nips = $this->users->pluck('nomor_induk')->toArray();

        // Ambil data presensi untuk semua user dalam dept_id ini
        $this->presensiData = DB::table('ktd_presensi')
            ->whereIn('user_nip', $nips)
            ->whereYear('tanggal', $this->year)
            ->whereMonth('tanggal', $this->month)
            ->get()
            ->groupBy('user_nip')
            ->map(function ($rows) {
                return $rows->keyBy(function ($row) {
                    return (int) date('d', strtotime($row->tanggal));
                });
            });
    }

    public function headings(): array
    {
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $this->month, $this->year);
        $headings = ['NIP', 'Nama'];

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $headings[] = $day;
        }

        $headings[] = 'Total Hari';

        return $headings;
    }

    public function collection(): Enumerable
    {
        return $this->users;
    }

    public function map($user): array
    {
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $this->month, $this->year);
        $mapped = [
            $user->nomor_induk,
            $user->name,
        ];

        $total = 0;
        $userPresensi = $this->presensiData->get($user->nomor_induk, collect());

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $hasPresensi = isset($userPresensi[$day]) &&
                (!empty($userPresensi[$day]->m_absen) || !empty($userPresensi[$day]->p_absen)) &&
                ($userPresensi[$day]->status === null);

            if ($hasPresensi) {
                $jamMasuk = $this->formatJam($userPresensi[$day]->m_absen);
                $jamPulang = $this->formatJam($userPresensi[$day]->p_absen);
                $mapped[] = "{$jamMasuk} / {$jamPulang}";
                $total++;
            } else {
                $mapped[] = '';
            }
        }

        $mapped[] = $total;

        return $mapped;
    }

    protected function formatJam($jam): string
    {
        if (empty($jam)) {
            return '-';
        }

        // Jika format HH:MM:SS, ambil HH:MM saja
        if (preg_match('/^(\d{2}:\d{2}):\d{2}$/', $jam, $matches)) {
            return $matches[1];
        }

        // Jika sudah format HH:MM, return langsung
        if (preg_match('/^\d{2}:\d{2}$/', $jam)) {
            return $jam;
        }

        return $jam;
    }

    public function styles(Worksheet $sheet): array
    {
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $this->month, $this->year);

        // Helper function to get column name
        $getColumnName = function ($index) {
            $column = '';
            while ($index > 0) {
                $index--;
                $column = chr(65 + ($index % 26)) . $column;
                $index = (int) ($index / 26);
            }
            return $column;
        };

        $lastCol = $getColumnName($daysInMonth + 3); // +3 for NIP, Nama, Total

        // Title styling
        $sheet->mergeCells("A1:{$lastCol}1");
        $sheet->setCellValue('A1', 'DETAIL JAM PRESENSI BULANAN');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells("A2:{$lastCol}2");
        $sheet->setCellValue('A2', 'Bulan: ' . $this->getMonthName() . ' ' . $this->year);
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(11);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells("A3:{$lastCol}3");
        $sheet->setCellValue('A3', 'Format: Jam Masuk / Jam Pulang');
        $sheet->getStyle('A3')->getFont()->setItalic(true)->setSize(10);
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
                'color' => ['rgb' => '6C757D'], // Abu-abu
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

        // Data styling
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

        $dataRows = $this->users->count() + 5;
        $sheet->getStyle("A6:{$lastCol}{$dataRows}")->applyFromArray($dataStyle);

        // Total column styling
        $totalCol = $getColumnName($daysInMonth + 2);
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

        $sheet->getStyle("{$totalCol}6:{$totalCol}{$dataRows}")->applyFromArray($totalStyle);

        // Auto filter
        $sheet->setAutoFilter("A5:{$lastCol}5");

        // Freeze panes
        $sheet->freezePane('C6');

        // Column widths
        $sheet->getColumnDimension('A')->setWidth(18); // NIP
        $sheet->getColumnDimension('B')->setWidth(25); // Nama
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $col = $getColumnName($day + 2);
            $sheet->getColumnDimension($col)->setWidth(14); // Width untuk "07:30/16:00"
        }
        $sheet->getColumnDimension($totalCol)->setWidth(10); // Total

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
