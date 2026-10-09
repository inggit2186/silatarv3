<?php

namespace App\Exports;

use App\Models\HariKerja;
use App\Models\Ketidakhadiran;
use App\Models\KtdPresensi;
use App\Models\KtdTukin;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PresensiTukin implements FromView, ShouldAutoSize, WithStyles
{
    protected $identifier; // dept_id (int/string) or group_key (string)

    protected $tanggalView;

    protected $type; // 'dept' or 'group'

    public function __construct($identifier, $tanggalView, $type = 'dept')
    {
        $this->identifier = $identifier;
        $this->tanggalView = $tanggalView;
        $this->type = $type;
    }

    public function view(): View
    {
        ini_set('max_execution_time', '0');
        set_time_limit(0);

        $xdate = Carbon::parse($this->tanggalView);
        $month = $xdate->format('M Y');
        $periode = $xdate->format('Y-m');
        $start = $xdate->copy()->startOfMonth();
        $end = $xdate->copy()->endOfMonth();
        $period = CarbonPeriod::create($start, $end);

        Log::info('=== PRESENSI TUKIN EXPORT ===');
        Log::info("Type: {$this->type}; Identifier: {$this->identifier}; Periode: {$start->toDateString()} s/d {$end->toDateString()}");

        // Load ketidakhadiran statuses (dynamic from database)
        $ketidakhadiran = Ketidakhadiran::all();
        // Filter out TL and PSW (calculated from timing, not status values)
        $ketidakhadiranStatuses = $ketidakhadiran
            ->filter(fn ($k) => ! str_contains(strtolower($k->jenis), 'tl') && ! str_contains(strtolower($k->jenis), 'psw'))
            ->pluck('jenis')
            ->map(fn ($jenis) => strtolower(trim($jenis)))
            ->toArray();

        Log::info('Ketidakhadiran statuses (excluding TL/PSW): '.json_encode($ketidakhadiranStatuses));

        // Hari, libur
        $day = [];
        $hari = [];
        $libur = [];
        $allLibur = DB::table('hari_libur')
            ->whereBetween('tanggal', [$start->format('Y-m-d'), $end->format('Y-m-d')])
            ->get()
            ->keyBy(fn ($l) => Carbon::parse($l->tanggal)->format('Y-m-d'));

        foreach ($period as $date) {
            $n = (int) $date->day;
            $ymd = $date->format('Y-m-d');
            $day[$n] = $ymd;

            $weekDay = (int) $date->format('w');
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

        // Load users based on type
        if ($this->type === 'group') {
            $users = $this->loadUsersByGroup($this->identifier);
        } else {
            // Load users by dept_id (default behavior)
            $users = User::with(['dept', 'tenaga'])
                ->where('dept_id', (string) $this->identifier)
                ->whereNotIn('role', ['pindah', 'pensiun'])
                ->get();
        }

        Log::info('Jumlah user ditemukan: '.$users->count());

        // buat list NIP yang dinormalisasi (digits only)
        $nipList = $users->pluck('nomor_induk')
            ->filter()
            ->unique()
            ->map(fn ($n) => preg_replace('/\D/', '', (string) $n))
            ->filter()
            ->unique()
            ->values();

        Log::info('Normalized ASN NIPs count: '.$nipList->count());

        // Data reference
        $hariKerjaAll = HariKerja::all()->keyBy('id');

        // Load existing ktd_tukin records for this period
        $existingTukin = KtdTukin::whereIn('user_nip', $users->pluck('nomor_induk')->filter()->unique())
            ->where('periode', $periode)
            ->get()
            ->keyBy('user_nip');

        Log::info('Existing ktd_tukin records for this period: '.$existingTukin->count());

        // ---- Ambil Presensi ----
        $presensiAll = collect();

        $dateStart = $start->format('Y-m-d 00:00:00');
        $dateEnd = $end->format('Y-m-d 23:59:59');

        if ($nipList->isNotEmpty()) {
            $nipArray = $nipList->toArray();

            try {
                $presRaw = KtdPresensi::select('*', DB::raw("REGEXP_REPLACE(user_nip, '[^0-9]', '') as nip_norm"))
                    ->whereBetween('tanggal', [$dateStart, $dateEnd])
                    ->whereIn(DB::raw("REGEXP_REPLACE(user_nip, '[^0-9]', '')"), $nipArray)
                    ->get();

                Log::info('Presensi fetched via REGEXP_REPLACE count: '.$presRaw->count());
                $presensiAll = $presRaw->groupBy('nip_norm');
            } catch (\Throwable $e) {
                Log::warning('REGEXP_REPLACE query failed: '.$e->getMessage().' — fallback to PHP filter');

                $presRaw = KtdPresensi::whereBetween('tanggal', [$dateStart, $dateEnd])->get();
                Log::info('Presensi fetched fallback (by date) count: '.$presRaw->count());

                $presRaw = $presRaw->map(function ($p) {
                    $p->nip_norm = preg_replace('/\D/', '', (string) ($p->user_nip ?? ''));

                    return $p;
                });

                $presFiltered = $presRaw->filter(fn ($p) => in_array($p->nip_norm, $nipArray));
                Log::info('Presensi after PHP filter count: '.$presFiltered->count());

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
            if (! $existingRecord) {
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

            $absen = $ketidakhadiran->pluck('id')->mapWithKeys(fn ($id) => [$id => 0])->toArray();

            // Hari kerja ASN
            $hk = DB::table('asn_harikerja')->where('user_id', $asn->id)->first();
            $asnhk = $hk ? $hk->harikerja : ($asn->dept->hari_kerja ?? null);

            $harilibur = ($asnhk == 11) ? 'jumat' : (($asnhk == 10) ? 'minggu' : 'sabtu');
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

                $n = (int) $tglObj->day;
                if (! isset($hari[$n])) {
                    continue;
                }

                $isLibur = $libur[$n] ?? 0;
                $hariIni = $hari[$n];
                $harikerja_id = $asnhk;

                $jamkerja = $hariKerjaAll->get($harikerja_id);
                if (! $jamkerja) {
                    continue;
                }

                // Only process on working days
                $isWorkingDay = ! $isLibur && $hariIni != $harilibur && $hariIni != $harilibur2;
                if (! $isWorkingDay) {
                    continue;
                }

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
                $masuk = $this->safeParseTime($item->m_absen);
                $pulang = $this->safeParseTime($item->p_absen);

                // If no clock-in and no clock-out on a working day, count as "Tanpa Keterangan"
                if (! $masuk && ! $pulang) {
                    $tkItem = $ketidakhadiran->firstWhere('jenis', 'Tanpa Keterangan');
                    if ($tkItem) {
                        $absen[$tkItem->id] += 1;
                    }

                    continue;
                }

                // If has clock-in but no clock-out, count as PSW4
                if ($masuk && ! $pulang) {
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
                if ($jml == 0) {
                    continue;
                }

                $jmlKenaPotongan = strcasecmp((string) $k->jenis, 'Tanpa Keterangan') === 0
                    ? max(0, $jml - 1)
                    : $jml;

                $potongan = $jmlKenaPotongan * ($baseTukin * $k->potongan) / 100;
                $totalPotongan += $potongan;

                $rekap_per_jenis[$k->id] = [
                    'jenis' => $k->jenis,
                    'jml' => $jml,
                    'potongan' => $potongan,
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

        // Sort users based on type
        if ($this->type === 'group') {
            $users = $users->sortBy('name');
        } else {
            $users = $users->sortBy('dept_id');
        }

        return view('backend.export.TukinDate', [
            'date' => $month,
            'jenis' => $ketidakhadiran,
            'asn' => $users,
            'liburDates' => $liburDates,
        ]);
    }

    // ========== STYLE EXCEL ==========

    public function styles(Worksheet $sheet): ?array
    {
        $sheet->freezePane('C5');
        $sheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 4);
        $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
        $sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_A4);
        $sheet->getPageSetup()->setScale(70);
        $sheet->getPageMargins()->setTop(0.25);

        // Count users based on type
        if ($this->type === 'group') {
            $group = $this->resolveGroup($this->identifier);
            if ($group) {
                $query = DB::table('users')
                    ->join('tenaga_ktd', 'users.nomor_induk', '=', 'tenaga_ktd.nomor_induk')
                    ->where('users.bank_kategori', $group['bank_kategori'])
                    ->where('tenaga_ktd.status', $group['status'])
                    ->where('users.status', 1);
                if (isset($group['serdik'])) {
                    $query->where('tenaga_ktd.serdik', $group['serdik']);
                }
                $cek = $query->count();
            } else {
                $cek = 0;
            }
        } else {
            $cek = User::where('dept_id', $this->identifier)
                ->whereNotIn('role', ['pindah', 'pensiun'])
                ->count();
        }

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
        if (empty($time) || $time === '00:00:00') {
            return null;
        }
        if ($time instanceof Carbon) {
            return $time;
        }
        try {
            return Carbon::parse($time);
        } catch (\Exception) {
            return null;
        }
    }

    protected function hitungTerlambatKey(Carbon $masuk, ?Carbon $jamMasuk = null)
    {
        if (! $jamMasuk || $masuk->lte($jamMasuk)) {
            return null;
        }
        $diff = $masuk->diffInMinutes($jamMasuk);

        return match (true) {
            $diff <= 30 => 'TL1',
            $diff <= 60 => 'TL2',
            $diff <= 90 => 'TL3',
            default => 'TL4'
        };
    }

    protected function hitungPulangKey(Carbon $pulang, ?Carbon $jamPulangTarget = null)
    {
        if (! $jamPulangTarget) {
            return null;
        }
        if ($pulang->between($jamPulangTarget->copy()->subMinutes(30), $jamPulangTarget->copy()->subSecond())) {
            return 'PSW1';
        }
        if ($pulang->between($jamPulangTarget->copy()->subMinutes(60), $jamPulangTarget->copy()->subMinutes(30)->subSecond())) {
            return 'PSW2';
        }
        if ($pulang->between($jamPulangTarget->copy()->subMinutes(90), $jamPulangTarget->copy()->subMinutes(60)->subSecond())) {
            return 'PSW3';
        }
        if ($pulang->lt($jamPulangTarget->copy()->subMinutes(90)) && $pulang->gt(Carbon::parse('12:00:00'))) {
            return 'PSW4';
        }

        return null;
    }

    /**
     * Load users by group_key (bank kategori)
     */
    protected function loadUsersByGroup(string $groupKey): \Illuminate\Support\Collection
    {
        // Parse group key to get bank_kategori, status, and serdik
        $group = $this->resolveGroup($groupKey);

        if (! $group) {
            Log::warning("Group not found: {$groupKey}");
            return collect();
        }

        $query = DB::table('users')
            ->join('tenaga_ktd', 'users.nomor_induk', '=', 'tenaga_ktd.nomor_induk')
            ->leftJoin('ktd_department', 'users.dept_id', '=', 'ktd_department.id')
            ->where('users.bank_kategori', $group['bank_kategori'])
            ->where('tenaga_ktd.status', $group['status'])
            ->where('users.status', 1)
            ->whereNotIn('users.role', ['pindah', 'pensiun']);

        if (isset($group['serdik'])) {
            if ($group['serdik'] === 'non-guru') {
                $query->where('tenaga_ktd.serdik', 'non-guru');
            } else {
                $query->where(function ($q) use ($group) {
                    $q->where('tenaga_ktd.serdik', $group['serdik'])
                        ->orWhereNull('tenaga_ktd.serdik');
                });
            }
        }

        $query->whereNotNull('users.nomor_induk')
            ->where('users.nomor_induk', '!=', '');

        // Select needed columns and convert to User-like objects
        $results = $query->select(
            'users.id',
            'users.name',
            'users.nomor_induk',
            'users.dept_id',
            'users.bank_kategori',
            'ktd_department.nama as dept_nama',
            'ktd_department.hari_kerja as hari_kerja',
            'tenaga_ktd.status as tenaga_status',
            'tenaga_ktd.serdik'
        )->get();

        // Convert to User model-like objects with relations
        $users = collect();
        foreach ($results as $row) {
            $user = new User();
            $user->id = $row->id;
            $user->name = $row->name;
            $user->nomor_induk = $row->nomor_induk;
            $user->dept_id = $row->dept_id;

            // Create dept relation
            $dept = new \stdClass();
            $dept->nama = $row->dept_nama ?? '-';
            $dept->hari_kerja = $row->hari_kerja;
            $user->dept = $dept;

            // Create tenaga relation
            $tenaga = new \stdClass();
            $tenaga->status = $row->tenaga_status;
            $tenaga->serdik = $row->serdik;
            $user->tenaga = $tenaga;

            $users->push($user);
        }

        Log::info('Users loaded by group: '.$users->count());

        return $users;
    }

    /**
     * Resolve group definition dari group_key
     */
    protected function resolveGroup(string $groupKey): ?array
    {
        $labels = [
            'pns_keagamaan_bank_nagari' => ['bk' => 'KEAGAMAAN_BANK NAGARI', 'status' => 'pns'],
            'pppk_keagamaan_bank_nagari' => ['bk' => 'KEAGAMAAN_BANK NAGARI', 'status' => 'pppk'],
            'pns_keagamaan_nagari' => ['bk' => 'KEAGAMAAN_PPPK_NAGARI', 'status' => 'pns'],
            'pppk_keagamaan_nagari' => ['bk' => 'KEAGAMAAN_PPPK_NAGARI', 'status' => 'pppk'],
            'pns_keagamaan_bsi' => ['bk' => 'KEAGAMAAN_BSI', 'status' => 'pns'],
            'cpns_keagamaan_bsi' => ['bk' => 'KEAGAMAAN_BSI', 'status' => 'cpns'],
            'pns_kependidikan_bank_nagari_serdik' => ['bk' => 'KEPENDIDIKAN_BANK NAGARI', 'status' => 'pns', 'serdik' => 'sertifikasi'],
            'pns_kependidikan_bank_nagari_nonserdik' => ['bk' => 'KEPENDIDIKAN_BANK NAGARI', 'status' => 'pns', 'serdik' => 'non-sertifikasi'],
            'pns_kependidikan_bank_nagari_nonguru' => ['bk' => 'KEPENDIDIKAN_BANK NAGARI', 'status' => 'pns', 'serdik' => 'non-guru'],
            'pns_kependidikan_bank_nagari_unknown' => ['bk' => 'KEPENDIDIKAN_BANK NAGARI', 'status' => 'pns', 'serdik' => 'unknown'],
            'pppk_kependidikan_bsi_serdik' => ['bk' => 'KEPENDIDIKAN_PPPK_BSI', 'status' => 'pppk', 'serdik' => 'sertifikasi'],
            'pppk_kependidikan_bsi_nonserdik' => ['bk' => 'KEPENDIDIKAN_PPPK_BSI', 'status' => 'pppk', 'serdik' => 'non-sertifikasi'],
            'pppk_kependidikan_bsi_nonguru' => ['bk' => 'KEPENDIDIKAN_PPPK_BSI', 'status' => 'pppk', 'serdik' => 'non-guru'],
            'pppk_kependidikan_bsi_unknown' => ['bk' => 'KEPENDIDIKAN_PPPK_BSI', 'status' => 'pppk', 'serdik' => 'unknown'],
            'pppk_kependidikan_nagari_serdik' => ['bk' => 'KEPENDIDIKAN_PPPK_NAGARI', 'status' => 'pppk', 'serdik' => 'sertifikasi'],
            'pppk_kependidikan_nagari_nonserdik' => ['bk' => 'KEPENDIDIKAN_PPPK_NAGARI', 'status' => 'pppk', 'serdik' => 'non-sertifikasi'],
            'pppk_kependidikan_nagari_nonguru' => ['bk' => 'KEPENDIDIKAN_PPPK_NAGARI', 'status' => 'pppk', 'serdik' => 'non-guru'],
            'pppk_kependidikan_nagari_unknown' => ['bk' => 'KEPENDIDIKAN_PPPK_NAGARI', 'status' => 'pppk', 'serdik' => 'unknown'],
            'pns_kependidikan_bri_serdik' => ['bk' => 'KEPENDIDIKAN_BRI', 'status' => 'pns', 'serdik' => 'sertifikasi'],
            'pns_kependidikan_bri_nonserdik' => ['bk' => 'KEPENDIDIKAN_BRI', 'status' => 'pns', 'serdik' => 'non-sertifikasi'],
            'pns_kependidikan_bri_nonguru' => ['bk' => 'KEPENDIDIKAN_BRI', 'status' => 'pns', 'serdik' => 'non-guru'],
            'pns_kependidikan_bri_unknown' => ['bk' => 'KEPENDIDIKAN_BRI', 'status' => 'pns', 'serdik' => 'unknown'],
            'pppk_kependidikan_bri_serdik' => ['bk' => 'KEPENDIDIKAN_BRI', 'status' => 'pppk', 'serdik' => 'sertifikasi'],
            'pppk_kependidikan_bri_nonserdik' => ['bk' => 'KEPENDIDIKAN_BRI', 'status' => 'pppk', 'serdik' => 'non-sertifikasi'],
            'pppk_kependidikan_bri_nonguru' => ['bk' => 'KEPENDIDIKAN_BRI', 'status' => 'pppk', 'serdik' => 'non-guru'],
            'pppk_kependidikan_bri_unknown' => ['bk' => 'KEPENDIDIKAN_BRI', 'status' => 'pppk', 'serdik' => 'unknown'],
            'pns_kependidikan_bsi_serdik' => ['bk' => 'KEPENDIDIKAN_BSI', 'status' => 'pns', 'serdik' => 'sertifikasi'],
            'pns_kependidikan_bsi_nonserdik' => ['bk' => 'KEPENDIDIKAN_BSI', 'status' => 'pns', 'serdik' => 'non-sertifikasi'],
            'pns_kependidikan_bsi_nonguru' => ['bk' => 'KEPENDIDIKAN_BSI', 'status' => 'pns', 'serdik' => 'non-guru'],
            'pns_kependidikan_bsi_unknown' => ['bk' => 'KEPENDIDIKAN_BSI', 'status' => 'pns', 'serdik' => 'unknown'],
            'cpns_kependidikan_bsi_nonserdik' => ['bk' => 'KEPENDIDIKAN_BSI', 'status' => 'cpns', 'serdik' => 'non-sertifikasi'],
            'pppk_kependidikan_bsi_serdik_bsi' => ['bk' => 'KEPENDIDIKAN_BSI', 'status' => 'pppk', 'serdik' => 'sertifikasi'],
            'pppk_kependidikan_bsi_nonserdik_bsi' => ['bk' => 'KEPENDIDIKAN_BSI', 'status' => 'pppk', 'serdik' => 'non-sertifikasi'],
        ];

        if (! isset($labels[$groupKey])) {
            return null;
        }

        $def = $labels[$groupKey];

        return [
            'bank_kategori' => $def['bk'],
            'status' => $def['status'],
            'serdik' => $def['serdik'] ?? null,
        ];
    }
}
