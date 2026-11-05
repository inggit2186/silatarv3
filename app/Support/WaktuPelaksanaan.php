<?php

namespace App\Support;

use Carbon\Carbon;

class WaktuPelaksanaan
{
    /**
     * Format label waktu pelaksanaan untuk ditampilkan.
     * Contoh: "08:00-09:30 WIB (90 menit)", "08:00 WIB", "90 menit", atau null bila kosong.
     */
    public static function label(?string $jamMulai, ?int $durasiMenit): ?string
    {
        $jamMulai = self::normalisasiJam($jamMulai);
        $durasiMenit = $durasiMenit !== null && $durasiMenit > 0 ? $durasiMenit : null;

        if ($jamMulai === '' && $durasiMenit === null) {
            return null;
        }

        if ($durasiMenit === null) {
            return $jamMulai.' WIB';
        }

        $selesai = self::hitungSelesai($jamMulai, $durasiMenit);

        if ($selesai === null) {
            return $durasiMenit.' menit';
        }

        return "{$jamMulai}-{$selesai} WIB ({$durasiMenit} menit)";
    }

    /**
     * Pisahkan label waktu menjadi bagian jam dan durasi untuk ditampilkan pada dua baris.
     * Contoh: jam "07:00 - 07:30 WIB", durasi "(30 Menit)".
     */
    public static function bagian(?string $jamMulai, ?int $durasiMenit): array
    {
        $jamMulai = self::normalisasiJam($jamMulai);
        $durasiMenit = $durasiMenit !== null && $durasiMenit > 0 ? $durasiMenit : null;

        if ($jamMulai === '' && $durasiMenit === null) {
            return ['jam' => null, 'durasi' => null];
        }

        if ($durasiMenit === null) {
            return ['jam' => $jamMulai.' WIB', 'durasi' => null];
        }

        $selesai = self::hitungSelesai($jamMulai, $durasiMenit);

        if ($selesai === null) {
            return ['jam' => null, 'durasi' => "({$durasiMenit} Menit)"];
        }

        return [
            'jam' => "{$jamMulai} - {$selesai} WIB",
            'durasi' => "({$durasiMenit} Menit)",
        ];
    }

    /**
     * Normalisasi jam menjadi format 24 jam (HH:MM).
     * Nilai 12 jam (mis. "2:30 PM") dikonversi, nilai rusak dibiarkan apa adanya.
     */
    private static function normalisasiJam(?string $jamMulai): string
    {
        $trimmed = trim((string) $jamMulai);
        if ($trimmed === '') {
            return '';
        }

        foreach (['H:i', 'H:i:s', 'H.i', 'g:i A', 'g:i a'] as $format) {
            try {
                $carbon = Carbon::createFromFormat($format, $trimmed);

                if ($carbon !== false
                    && $carbon->format($format) === $trimmed
                    && $carbon->hour <= 23
                    && $carbon->minute <= 59) {
                    return $carbon->format('H:i');
                }
            } catch (\Throwable $exception) {
                continue;
            }
        }

        return $trimmed;
    }

    /**
     * Ubah jam mulai menjadi menit sejak tengah malam (untuk pengurutan).
     * Mengembalikan null bila kosong / tidak valid.
     */
    public static function menit(?string $jamMulai): ?int
    {
        $jamMulai = self::normalisasiJam($jamMulai);

        if ($jamMulai === '') {
            return null;
        }

        try {
            $carbon = Carbon::createFromFormat('H:i', $jamMulai);

            return ($carbon->hour * 60) + $carbon->minute;
        } catch (\Throwable $exception) {
            return null;
        }
    }

    /**
     * Ubah durasi (menit) menjadi label ringkas.
     * Contoh: 90 → "1 jam 30 menit", 60 → "1 jam", 45 → "45 menit", ≤0 → null.
     */
    public static function durasiLabel(?int $durasiMenit): ?string
    {
        if ($durasiMenit === null || $durasiMenit <= 0) {
            return null;
        }

        $jam = intdiv($durasiMenit, 60);
        $menit = $durasiMenit % 60;

        if ($jam === 0) {
            return "{$menit} menit";
        }

        if ($menit === 0) {
            return "{$jam} jam";
        }

        return "{$jam} jam {$menit} menit";
    }

    /**
     * Hitung jam selesai dari jam mulai + durasi (menit).
     */
    private static function hitungSelesai(string $jamMulai, int $durasiMenit): ?string
    {
        try {
            $mulai = Carbon::createFromFormat('H:i', $jamMulai);

            return $mulai->copy()->addMinutes($durasiMenit)->format('H:i');
        } catch (\Throwable $exception) {
            return null;
        }
    }
}
