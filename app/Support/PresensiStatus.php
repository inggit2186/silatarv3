<?php

namespace App\Support;

/**
 * Pemetaan status presensi (tabel ktd_presensi) ke label, tone warna,
 * dan ikon geometris agar tampilan web maupun PDF konsisten.
 */
class PresensiStatus
{
    /**
     * @return array{label: string, tone: string, accent: string, bg: string, fg: string}
     */
    public static function meta(?string $status, bool $hasAbsen = false): array
    {
        $status = trim((string) $status);

        $meta = match (true) {
            $status === 'MASUK' => ['label' => 'Hadir', 'tone' => 'emerald'],
            $status === 'TERLAMBAT' => ['label' => 'Terlambat', 'tone' => 'amber'],
            $status === 'PULANG' => ['label' => 'Sudah Pulang', 'tone' => 'blue'],
            $status === 'PULANG_CEPAT' => ['label' => 'Pulang Cepat', 'tone' => 'orange'],
            $status === 'SISTEM_ERROR' => ['label' => 'Sistem Error', 'tone' => 'red'],
            $status === 'TUGAS_LUAR', $status === 'Dinas Luar' => ['label' => 'Tugas Luar', 'tone' => 'amber'],
            $status === 'LUPA_PRESNSI_PUSAKA' => ['label' => 'Lupa Presensi', 'tone' => 'purple'],
            $status === '' => $hasAbsen
                ? ['label' => 'Hadir', 'tone' => 'emerald']
                : ['label' => 'Tanpa Absensi', 'tone' => 'slate'],
            str_starts_with($status, 'Cuti') => ['label' => $status, 'tone' => 'blue'],
            str_contains($status, 'Libur') => ['label' => $status, 'tone' => 'slate'],
            default => ['label' => $status, 'tone' => 'slate'],
        };

        $palette = [
            'emerald' => ['accent' => '#047857', 'bg' => '#d1fae5', 'fg' => '#065f46'],
            'amber' => ['accent' => '#b45309', 'bg' => '#fef3c7', 'fg' => '#92400e'],
            'blue' => ['accent' => '#1d4ed8', 'bg' => '#dbeafe', 'fg' => '#1e40af'],
            'orange' => ['accent' => '#c2410c', 'bg' => '#ffedd5', 'fg' => '#9a3412'],
            'red' => ['accent' => '#b91c1c', 'bg' => '#fee2e2', 'fg' => '#991b1b'],
            'purple' => ['accent' => '#6d28d9', 'bg' => '#ede9fe', 'fg' => '#5b21b6'],
            'slate' => ['accent' => '#64748b', 'bg' => '#e2e8f0', 'fg' => '#334155'],
        ];

        return $meta + $palette[$meta['tone']];
    }

    /**
     * @return list<string>
     */
    public static function errorStatuses(): array
    {
        return ['SISTEM_ERROR', 'TUGAS_LUAR', 'LUPA_PRESNSI_PUSAKA'];
    }
}
