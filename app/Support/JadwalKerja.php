<?php

namespace App\Support;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Aturan hari kerja & hari libur untuk rekapitulasi presensi.
 *
 * Sumber hari kerja pegawai:
 *  1. asn_harikerja (settingan per pegawai) -> hari_kerja.id
 *  2. fallback: ktd_department.hari_kerja (unit kerja pegawai) -> hari_kerja.id
 *
 * Kolom tabel hari_kerja:
 *  - masuk  : jam masuk pegawai
 *  - biasa  : jam pulang pada hari Senin-Kamis
 *  - jumat  : jam pulang pada hari Jumat (NULL = libur Jumat)
 *  - sabtu  : jam pulang pada hari Sabtu (NULL = libur Sabtu)
 *  - minggu : jam pulang pada hari Minggu (NULL = libur Minggu)
 *
 * Hari libur nasional & cuti bersama diambil dari tabel hari_libur.
 */
class JadwalKerja
{
    /**
     * Baris hari_kerja milik pegawai.
     */
    public static function forUser($user): ?object
    {
        $asn = DB::table('asn_harikerja')->where('user_id', $user->id)->first();
        $id = $asn->harikerja ?? null;

        if (! $id && ! empty($user->dept_id)) {
            $id = DB::table('ktd_department')->where('id', $user->dept_id)->value('hari_kerja');
        }

        if (! $id) {
            return null;
        }

        return DB::table('hari_kerja')->where('id', $id)->first();
    }

    /**
     * Jadwal kerja pada satu tanggal.
     *
     * @return array{jenis: string, masuk: ?string, pulang: ?string, is_working: bool}
     */
    public static function forDate(?object $hariKerja, Carbon $date): array
    {
        $jenis = match ((int) $date->format('w')) {
            5 => 'jumat',
            6 => 'sabtu',
            0 => 'minggu',
            default => 'biasa',
        };

        if (! $hariKerja) {
            return ['jenis' => $jenis, 'masuk' => null, 'pulang' => null, 'is_working' => false];
        }

        $pulang = $hariKerja->{$jenis} ?? null;

        return [
            'jenis' => $jenis,
            'masuk' => $hariKerja->masuk ?? null,
            'pulang' => ! empty($pulang) ? $pulang : null,
            'is_working' => ! empty($pulang),
        ];
    }

    /**
     * Hari libur nasional & cuti bersama pada rentang tanggal.
     *
     * @return array<string, object>
     */
    public static function liburMap(string $start, string $end): array
    {
        return DB::table('hari_libur')
            ->whereBetween('tanggal', [$start, $end])
            ->get()
            ->mapWithKeys(fn ($l) => [Carbon::parse($l->tanggal)->format('Y-m-d') => $l])
            ->all();
    }

    /**
     * Anotasi baris presensi dengan informasi hari kerja & hari libur,
     * sekaligus menghitung ringkasan hari kerja pada periode.
     *
     * @param  iterable  $rows
     * @return array<string, mixed>
     */
    public static function annotate(iterable $rows, $user, string $start, string $end): array
    {
        $hariKerja = self::forUser($user);
        $libur = self::liburMap($start, $end);

        $effective = 0;
        $liburNasional = 0;
        $liburJadwal = 0;

        $cursor = Carbon::parse($start);
        $last = Carbon::parse($end);
        while ($cursor->lessThanOrEqualTo($last)) {
            $ymd = $cursor->format('Y-m-d');
            $jadwal = self::forDate($hariKerja, $cursor);

            if (isset($libur[$ymd])) {
                $liburNasional++;
            } elseif ($jadwal['is_working']) {
                $effective++;
            } else {
                $liburJadwal++;
            }

            $cursor->addDay();
        }

        foreach ($rows as $row) {
            $date = Carbon::parse($row->tanggal);
            $ymd = $date->format('Y-m-d');
            $jadwal = self::forDate($hariKerja, $date);
            $liburRow = $libur[$ymd] ?? null;
            $hasAbsen = (bool) ($row->m_absen || $row->p_absen);

            $row->jadwal = $jadwal;
            $row->libur_keterangan = $liburRow->keterangan ?? null;
            $row->libur_status = $liburRow->status ?? null;
            $row->is_libur_nasional = $liburRow !== null;
            $row->is_hari_kerja = $jadwal['is_working'] && $liburRow === null;

            if (! $hasAbsen) {
                if ($liburRow) {
                    $row->status_override = [
                        'label' => $liburRow->status === 'cuti' ? 'Cuti Bersama' : 'Libur Nasional',
                        'tone' => 'blue',
                    ];
                } elseif (! $jadwal['is_working']) {
                    $row->status_override = ['label' => 'Libur', 'tone' => 'slate'];
                }
            }
        }

        return [
            'hari_kerja' => $hariKerja,
            'hari_kerja_efektif' => $effective,
            'hari_libur_nasional' => $liburNasional,
            'hari_libur_jadwal' => $liburJadwal,
        ];
    }
}
