<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\Exportable;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Enumerable;

class PresensiDetailExport implements \Maatwebsite\Excel\Concerns\Export, WithMultipleSheets
{
    use Exportable;

    protected $userId;
    protected $month;
    protected $year;
    protected $userName;
    protected $userNip;

    public function __construct(int $userId, int $month, int $year)
    {
        $this->userId = $userId;
        $this->month = $month;
        $this->year = $year;

        // Get user info
        $user = DB::table('users')->find($userId);
        $this->userName = $user->name ?? 'Unknown';
        $this->userNip = $user->nomor_induk ?? 'Unknown';
    }

    public function sheets(): array
    {
        return [
            'Rekap Presensi' => new PresensiDetailSheet(
                $this->userId,
                $this->month,
                $this->year,
                $this->userName,
                $this->userNip
            ),
        ];
    }
}

class PresensiDetailSheet implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $userId;
    protected $month;
    protected $year;
    protected $userName;
    protected $userNip;

    public function __construct(int $userId, int $month, int $year, string $userName, string $userNip)
    {
        $this->userId = $userId;
        $this->month = $month;
        $this->year = $year;
        $this->userName = $userName;
        $this->userNip = $userNip;
    }

    public function collection(): Enumerable
    {
        return DB::table('ktd_presensi')
            ->where('user_nip', $this->userNip)
            ->whereYear('tanggal', $this->year)
            ->whereMonth('tanggal', $this->month)
            ->orderBy('tanggal')
            ->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal',
            'Hari',
            'Jam Masuk',
            'Telat (Menit)',
            'Jam Pulang',
            'PSW (Menit)',
            'Status',
            'Keterangan',
        ];
    }

    public function map($row): array
    {
        $hari = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
        ];

        $date = new \DateTime($row->tanggal);
        $dayName = $hari[$date->format('l')] ?? $date->format('l');

        return [
            $row->id,
            $date->format('d/m/Y'),
            $dayName,
            $row->m_absen ?: '-',
            $row->m_diff ?: '-',
            $row->p_absen ?: '-',
            $row->p_diff ?: '-',
            $row->status ?: '-',
            $row->keterangan ?: '-',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        // Title styling
        $sheet->mergeCells('A1:I1');
        $sheet->setCellValue('A1', 'REKAP PRESENSI BULANAN');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells('A2:I2');
        $sheet->setCellValue('A2', $this->userName . ' - NIP: ' . $this->userNip);
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(11);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells('A3:I3');
        $sheet->setCellValue('A3', 'Bulan: ' . $this->getMonthName() . ' ' . $this->year);
        $sheet->getStyle('A3')->getFont()->setBold(true)->setSize(11);
        $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Header styling
        $headerStyle = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 10,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['rgb' => '2E86AB'], // Biru
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

        $sheet->getStyle('A5:I5')->applyFromArray($headerStyle);

        // Data styling
        $dataStyle = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ];

        $sheet->getStyle('A6:I' . $sheet->getHighestRow())->applyFromArray($dataStyle);

        // Auto filter
        $sheet->setAutoFilter('A5:I5');

        // Freeze panes
        $sheet->freezePane('A6');

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
