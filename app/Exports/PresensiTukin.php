<?php

namespace App\Exports;

use App\Models\User;
use App\Models\KtdTukin;
use App\Models\Department;
use App\Models\KtdPresensi;
use App\Models\Ketidakhadiran;
use App\Models\HariKerja;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Illuminate\Support\Collection;

class PresensiTukin implements FromView, ShouldAutoSize, WithStyles
{
    protected $satker;
    protected $tanggalView;

    public function __construct($satker, $tanggalView)
    {
        $this->satker = $satker;
        $this->tanggalView = $tanggalView;
    }

    public function view(): View
    {
        ini_set('max_execution_time', '0');
        set_time_limit(0);

        $xdate   = Carbon::parse($this->tanggalView);
        $month   = $xdate->format('M Y');
        $periode = $xdate->format('Y-m');
        $start   = $xdate->copy()->startOfMonth();
        $end     = $xdate->copy()->endOfMonth();
        $period  = CarbonPeriod::create($start, $end);

        Log::info("=== PRESENSI TUKIN EXPORT ===");
        Log::info("Satker: {$this->satker}; Periode: {$start->toDateString()} s/d {$end->toDateString()}");

        // Load ketidakhadiran statuses (dynamic from database)
        $ketidakhadiran = Ketidakhadiran::all();
        // Filter out TL and PSW (calculated from timing, not status values)
        $ketidakhadiranStatuses = $ketidakhadiran
            ->filter(fn($k) => !str_contains(strtolower($k->jenis), 'tl') && !str_contains(strtolower($k->jenis), 'psw'))
            ->pluck('jenis')
            ->map(fn($jenis) => strtolower(trim($jenis)))
            ->toArray();

        Log::info("Ketidakhadiran statuses (excluding TL/PSW): " . json_encode($ketidakhadiranStatuses));

        // Hari, libur
        $day = [];
        $hari = [];
        $libur = [];
        $allLibur = DB::table('hari_libur')
            ->whereBetween('tanggal', [$start->format('Y-m-d'), $end->format('Y-m-d')])
            ->get()
            ->keyBy(fn($l) => Carbon::parse($l->tanggal)->format('Y-m-d'));

        foreach ($period as $date) {
            $n   = (int)$date->day;
            $ymd = $date->format('Y-m-d');
            $day[$n] = $ymd;

            $weekDay = (int)$date->format('w');
            $hari[$n] = match ($weekDay) {
                5 => 'jumat',
                6 => 'sabtu',
                0 => 'minggu',
                default => 'biasa'
            };
            $libur[$n] = $allLibur->has($ymd) ? 1 : 0;
        }

        // Pass libur data to view for styling
        $liburDates = $allLibur->keys()->toArray();

        // Load ASN
        $users = User::with(['dept', 'tenaga'])
            ->where('dept_id', (string)$this->satker)
            ->whereNotIn('role', ['pindah', 'pensiun'])
            ->get();

        Log::info("Jumlah user ASN ditemukan: " . $users->count());

        // buat list NIP yang dinormalisasi (digits only)
        $nipList = $users->pluck('nomor_induk')
            ->filter()
            ->unique()
            ->map(fn($n) => preg_replace('/\D/', '', (string)$n))
            ->filter()
            ->unique()
            ->values();

        Log::info('Normalized ASN NIPs count: ' . $nipList->count());

        // Data reference
        $hariKerjaAll = HariKerja::all()->keyBy('id');

        // Load existing ktd_tukin records for this period
        $existingTukin = KtdTukin::whereIn('user_nip', $users->pluck('nomor_induk')->filter()->unique())
            ->where('periode', $periode)
            ->get()
            ->keyBy('user_nip');

        Log::info("Existing ktd_tukin records for this period: " . $existingTukin->count());

        // ---- Ambil Presensi ----
        $presensiAll = collect();

        $dateStart = $start->format('Y-m-d 00:00:00');
        $dateEnd   = $end->format('Y-m-d 23:59:59');

        if ($nipList->isNotEmpty()) {
            $nipArray = $nipList->toArray();

            try {
                $presRaw = KtdPresensi::select('*', DB::raw("REGEXP_REPLACE(user_nip, '[^0-9]', '') as nip_norm"))
                    ->whereBetween('tanggal', [$dateStart, $dateEnd])
                    ->whereIn(DB::raw("REGEXP_REPLACE(user_nip, '[^0-9]', '')"), $nipArray)
                    ->get();

                Log::info('Presensi fetched via REGEXP_REPLACE count: ' . $presRaw->count());
                $presensiAll = $presRaw->groupBy('nip_norm');
            } catch (\Throwable $e) {
                Log::warning('REGEXP_REPLACE query failed: ' . $e->getMessage() . ' — fallback to PHP filter');

                $presRaw = KtdPresensi::whereBetween('tanggal', [$dateStart, $dateEnd])->get();
                Log::info('Presensi fetched fallback (by date) count: ' . $presRaw->count());

                $presRaw = $presRaw->map(function ($p) {
                    $p->nip_norm = preg_replace('/\D/', '', (string) ($p->user_nip ?? ''));
                    return $p;
                });

                $presFiltered = $presRaw->filter(fn($p) => in_array($p->nip_norm, $nipArray));
                Log::info('Presensi after PHP filter count: ' . $presFiltered->count());

                $presensiAll = $presFiltered->groupBy('nip_norm');
            }
        } else {
            Log::info('NIP list kosong, presensiAll tetap kosong');
            $presensiAll = collect();
        }

        // -------- Proses tiap ASN --------
        foreach ($users as $asn) {
            // Check if user has existing record in ktd_tukin
            $existingRecord = $existingTukin->get($asn->nomor_induk);
            if (!$existingRecord) {
                Log::info("User {$asn->nomor_induk} skipped - no record in ktd_tukin");
                continue;
            }

            // Attach OLD tukin data to user object for Excel display
            $asn->tukin_lama = $existingRecord->tukin ?? 0;
            $asn->tk_jumlah_lama = $existingRecord->tk_jumlah ?? 0;
            $asn->tl_lama = $existingRecord->tl ?? 0;
            $asn->psw_lama = $existingRecord->psw ?? 0;
            $asn->hukdis_lama = $existingRecord->hukdis ?? 0;
            $asn->cpns_lama = $existingRecord->cpns ?? 0;
            $asn->total_potongan_lama = $existingRecord->total_potongan ?? 0;
            $asn->nett_lama = ($existingRecord->tukin ?? 0) - ($existingRecord->total_potongan ?? 0);

            // Get base tukin from existing record
            $baseTukin = intval($existingRecord->tukin);

            // Check if employee is CPNS (from tenaga_ktd.status)
            $isCpns = $asn->tenaga && strtolower($asn->tenaga->status) === 'cpns';
            if ($isCpns) {
                $baseTukin = intval(round(0.8 * $baseTukin));
                $asn->asn_status = 'CPNS (80%)';
            } else {
                $asn->asn_status = strtoupper($asn->tenaga->status ?? '-');
            }

            $absen = $ketidakhadiran->pluck('id')->mapWithKeys(fn($id) => [$id => 0])->toArray();

            // Hari kerja ASN
            $hk = DB::table('asn_harikerja')->where('user_id', $asn->id)->first();
            $asnhk = $hk ? $hk->harikerja : ($asn->dept->hari_kerja ?? null);

            $harilibur  = ($asnhk == 11) ? 'jumat' : (($asnhk == 10) ? 'minggu' : 'sabtu');
            $harilibur2 = ($asnhk == 11 || $asnhk == 10) ? 'none' : 'minggu';

            // ambil presensi user berdasarkan normalized nip
            $normNip = preg_replace('/\D/', '', (string) $asn->nomor_induk);
            $presensiUser = $presensiAll->get($normNip, collect());

            $processedTanggal = [];

            foreach ($presensiUser as $item) {
                $tglObj = Carbon::parse($item->tanggal);
                $tanggalKey = $tglObj->format('Y-m-d');
                if (isset($processedTanggal[$tanggalKey])) {
                    continue;
                }
                $processedTanggal[$tanggalKey] = true;

                $n = (int)$tglObj->day;
                if (!isset($hari[$n])) continue;

                $isLibur = $libur[$n] ?? 0;
                $hariIni = $hari[$n];
                $harikerja_id = $asnhk;

                $jamkerja = $hariKerjaAll->get($harikerja_id);
                if (!$jamkerja) continue;

                // Only process on working days
                $isWorkingDay = !$isLibur && $hariIni != $harilibur && $hariIni != $harilibur2;
                if (!$isWorkingDay) continue;

                // Check presensi status - dynamic from ktd_ketidakhadiran
                $status = strtolower(trim((string) ($item->status ?? '')));

                // If status is in ketidakhadiran statuses (excluding TL/PSW), count as ketidakhadiran
                if (in_array($status, $ketidakhadiranStatuses)) {
                    $ketidakhadiranItem = $ketidakhadiran->firstWhere('jenis', $item->status);
                    if ($ketidakhadiranItem) {
                        $absen[$ketidakhadiranItem->id] += 1;
                    }
                    continue; // Skip TL/PSW calculation for ketidakhadiran status
                }

                // Status not in ketidakhadiran = "hadir" - process TL/PSW logic
                $masuk  = $this->safeParseTime($item->m_absen);
                $pulang = $this->safeParseTime($item->p_absen);

                // If no clock-in and no clock-out on a working day, count as "Tanpa Keterangan"
                if (!$masuk && !$pulang) {
                    $tkItem = $ketidakhadiran->firstWhere('jenis', 'Tanpa Keterangan');
                    if ($tkItem) {
                        $absen[$tkItem->id] += 1;
                    }
                    continue;
                }

                // If has clock-in but no clock-out, count as PSW4
                if ($masuk && !$pulang) {
                    $pswItem = $ketidakhadiran->firstWhere('jenis', 'PSW4');
                    if ($pswItem) {
                        $absen[$pswItem->id] += 1;
                    }
                }

                // Absen masuk - hitung TL
                if ($masuk) {
                    $jamMasuk = Carbon::parse($jamkerja->masuk);
                    $terlambatKey = $this->hitungTerlambatKey($masuk, $jamMasuk);
                    if ($terlambatKey) {
                        $tlItem = $ketidakhadiran->firstWhere('jenis', $terlambatKey);
                        if ($tlItem) {
                            $absen[$tlItem->id] += 1;
                        }
                    }
                }

                // Absen pulang - hitung PSW
                if ($pulang) {
                    $jamPulangTarget = match ($hariIni) {
                        'jumat' => Carbon::parse($jamkerja->jumat),
                        'sabtu' => Carbon::parse($jamkerja->sabtu),
                        'minggu' => Carbon::parse($jamkerja->minggu),
                        default => Carbon::parse($jamkerja->biasa)
                    };
                    $pswKey = $this->hitungPulangKey($pulang, $jamPulangTarget);
                    if ($pswKey) {
                        $pswItem = $ketidakhadiran->firstWhere('jenis', $pswKey);
                        if ($pswItem) {
                            $absen[$pswItem->id] += 1;
                        }
                    }
                }
            }

            // Calculate deductions
            $totalPotongan = 0;
            $rekap_per_jenis = [];
            foreach ($ketidakhadiran as $k) {
                $jml = $absen[$k->id] ?? 0;
                if ($jml == 0) continue;

                $jmlKenaPotongan = strcasecmp((string) $k->jenis, 'Tanpa Keterangan') === 0
                    ? max(0, $jml - 1)
                    : $jml;

                $potongan = $jmlKenaPotongan * ($baseTukin * $k->potongan) / 100;
                $totalPotongan += $potongan;

                $rekap_per_jenis[$k->id] = [
                    'jenis' => $k->jenis,
                    'jml' => $jml,
                    'potongan' => $potongan
                ];
            }

            // Build detail_potongan_calc JSON
            $detailPotongan = [];
            foreach ($rekap_per_jenis as $k => $data) {
                $detailPotongan[strtolower(str_replace(' ', '_', $data['jenis']))] = $data['jml'];
            }

            // Save results to ktd_tukin
            $tukinFinal = $baseTukin;
            $totalPotonganFinal = $totalPotongan;
            $nett = max(0, ($tukinFinal - $totalPotonganFinal));

            KtdTukin::where('user_nip', $asn->nomor_induk)
                ->where('periode', $periode)
                ->update([
                    'tukin_final' => $tukinFinal,
                    'total_potongan_final' => $totalPotonganFinal,
                    'detail_potongan_calc' => $detailPotongan,
                ]);

            // Simpan hasil ke object user
            $asn->tukin_final = $tukinFinal;
            $asn->total_potongan_final = $totalPotonganFinal;
            $asn->nett = $nett;
            $asn->detail_potongan_calc = $detailPotongan;
        }

        return view('backend.export.TukinDate', [
            'date' => $month,
            'jenis' => $ketidakhadiran,
            'asn' => $users->sortBy('dept_id'),
            'liburDates' => $liburDates,
        ]);
    }

    // ========== STYLE EXCEL ==========

    public function styles(Worksheet $sheet): ?array
    {
        $cek = User::where('dept_id', $this->satker)
            ->whereNotIn('role', ['pindah', 'pensiun'])
            ->count();

        $sheet->freezePane('C5');
        $sheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 4);
        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
        $sheet->getPageSetup()->setScale(70);
        $sheet->getPageMargins()->setTop(0.25);

        // Style legend row at the bottom
        $lastRow = $cek + 6; // +6 for header rows and data rows
        $sheet->getStyle("A{$lastRow}:Z{$lastRow}")->applyFromArray([
            'font' => ['size' => 10, 'color' => ['rgb' => '666666']],
            'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'F5F5F5']],
            'borders' => ['top' => ['borderStyle' => 'thin', 'color' => ['rgb' => 'CCCCCC']]],
        ]);

        return [];
    }

    // Helper parse
    protected function safeParseTime($time)
    {
        if (empty($time) || $time === '00:00:00') return null;
        if ($time instanceof Carbon) return $time;
        try {
            return Carbon::parse($time);
        } catch (\Exception) {
            return null;
        }
    }

    protected function hitungTerlambatKey(Carbon $masuk, Carbon $jamMasuk = null)
    {
        if (!$jamMasuk || $masuk->lte($jamMasuk)) return null;
        $diff = $masuk->diffInMinutes($jamMasuk);
        return match (true) {
            $diff <= 30 => 'TL1',
            $diff <= 60 => 'TL2',
            $diff <= 90 => 'TL3',
            default => 'TL4'
        };
    }

    protected function hitungPulangKey(Carbon $pulang, Carbon $jamPulangTarget = null)
    {
        if (!$jamPulangTarget) return null;
        if ($pulang->between($jamPulangTarget->copy()->subMinutes(30), $jamPulangTarget->copy()->subSecond()))
            return 'PSW1';
        if ($pulang->between($jamPulangTarget->copy()->subMinutes(60), $jamPulangTarget->copy()->subMinutes(30)->subSecond()))
            return 'PSW2';
        if ($pulang->between($jamPulangTarget->copy()->subMinutes(90), $jamPulangTarget->copy()->subMinutes(60)->subSecond()))
            return 'PSW3';
        if ($pulang->lt($jamPulangTarget->copy()->subMinutes(90)) && $pulang->gt(Carbon::parse('12:00:00')))
            return 'PSW4';
        return null;
    }
}
