<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Enumerable;

class PresensiAbsensiExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
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

    public function collection(): Enumerable
    {
        // Get all days in month
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $this->month, $this->year);
        $days = [];

        // Get presensi data
        $presensi = DB::table('ktd_presensi')
            ->where('user_nip', $this->userNip)
            ->whereYear('tanggal', $this->year)
            ->whereMonth('tanggal', $this->month)
            ->get()
            ->keyBy(function ($row) {
                return date('Y-m-d', strtotime($row->tanggal));
            });

        // Build rows for each day
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = sprintf('%04d-%02d-%02d', $this->year, $this->month, $day);
            $dayOfWeek = date('N', strtotime($date)); // 1=Monday, 7=Sunday

            $row = [
                'date' => $date,
                'day' => $day,
                'day_name' => $this->getDayName($dayOfWeek),
                'is_weekend' => in_array($dayOfWeek, [6, 7]), // Saturday or Sunday
                'has_masuk' => isset($presensi[$date]) && !empty($presensi[$date]->m_absen) ? 1 : 0,
                'has_pulang' => isset($presensi[$date]) && !empty($presensi[$date]->p_absen) ? 1 : 0,
                'status' => isset($presensi[$date]) ? ($presensi[$date]->status ?? '-') : '-',
            ];

            $days[] = $row;
        }

        return collect($days);
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Hari',
            'Absen Masuk',
            'Absen Pulang',
            'Status',
        ];
    }

    public function map($row): array
    {
        $date = new \DateTime($row['date']);

        return [
            $date->format('d/m/Y'),
            $row['day_name'],
            $row['has_masuk'], // 1 if has absen masuk
            $row['has_pulang'], // 1 if has absen pulang
            $row['status'],
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $this->month, $this->year);

        // Title styling
        $sheet->mergeCells('A1:E1');
        $sheet->setCellValue('A1', 'REKAP ABSENSI HARIAN');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells('A2:E2');
        $sheet->setCellValue('A2', $this->userName . ' - NIP: ' . $this->userNip);
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(11);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells('A3:E3');
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

        $sheet->getStyle('A5:E5')->applyFromArray($headerStyle);

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

        $sheet->getStyle('A6:E' . ($daysInMonth + 5))->applyFromArray($dataStyle);

        // Highlight weekends (Saturday & Sunday)
        $weekendStyle = [
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['rgb' => 'FFF3CD'], // Kuning muda
            ],
        ];

        // Get presensi data to highlight days with attendance
        $presensi = DB::table('ktd_presensi')
            ->where('user_nip', $this->userNip)
            ->whereYear('tanggal', $this->year)
            ->whereMonth('tanggal', $this->month)
            ->get()
            ->keyBy(function ($row) {
                return date('Y-m-d', strtotime($row->tanggal));
            });

        // Apply styles for each row
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $rowNum = $day + 5;
            $date = sprintf('%04d-%02d-%02d', $this->year, $this->month, $day);
            $dayOfWeek = date('N', strtotime($date));

            // Highlight weekends
            if (in_array($dayOfWeek, [6, 7])) {
                $sheet->getStyle("A{$rowNum}:E{$rowNum}")->applyFromArray($weekendStyle);
            }

            // Highlight days with attendance (value 1) in green
            if (isset($presensi[$date])) {
                $hasMasuk = !empty($presensi[$date]->m_absen);
                $hasPulang = !empty($presensi[$date]->p_absen);

                if ($hasMasuk || $hasPulang) {
                    $attendanceStyle = [
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'color' => ['rgb' => 'D4EDDA'], // Hijau muda
                        ],
                    ];
                    $sheet->getStyle("A{$rowNum}:E{$rowNum}")->applyFromArray($attendanceStyle);
                }
            }
        }

        // Highlight value 1 cells
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $rowNum = $day + 5;
            $date = sprintf('%04d-%02d-%02d', $this->year, $this->month, $day);

            if (isset($presensi[$date])) {
                // Highlight C (Absen Masuk) if value is 1
                if (!empty($presensi[$date]->m_absen)) {
                    $sheet->getStyle("C{$rowNum}")->getFont()->setBold(true)->setColor(['rgb' => '155724']);
                }

                // Highlight D (Absen Pulang) if value is 1
                if (!empty($presensi[$date]->p_absen)) {
                    $sheet->getStyle("D{$rowNum}")->getFont()->setBold(true)->setColor(['rgb' => '155724']);
                }
            }
        }

        // Auto filter
        $sheet->setAutoFilter('A5:E5');

        // Freeze panes
        $sheet->freezePane('A6');

        // Column widths
        $sheet->getColumnDimension('A')->setWidth(15);
        $sheet->getColumnDimension('B')->setWidth(15);
        $sheet->getColumnDimension('C')->setWidth(15);
        $sheet->getColumnDimension('D')->setWidth(15);
        $sheet->getColumnDimension('E')->setWidth(20);

        return [];
    }

    protected function getDayName(int $dayOfWeek): string
    {
        $days = [
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
            7 => 'Minggu',
        ];

        return $days[$dayOfWeek] ?? 'Unknown';
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
