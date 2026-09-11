<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class FetchIndonesianHolidays extends Command
{
    protected $signature = 'holidays:fetch {year?}';
    protected $description = 'Fetch Indonesian public holidays from Nager.Date API and save to hari_libur table';

    public function handle()
    {
        $year = $this->argument('year') ?? date('Y');

        $this->info("Fetching Indonesian holidays for {$year}...");

        try {
            $holidays = [];

            // Try multiple API sources
            $apiSources = [
                ['name' => 'Nager.Date', 'url' => "https://date.nager.at/Api/v2/PublicHoliday/CountryCode/ID/{$year}"],
                ['name' => 'Nager.Date v2', 'url' => "https://date.nager.at/Api/v2/PublicHolidays/{$year}/ID"],
            ];

            foreach ($apiSources as $source) {
                if (!empty($holidays)) break;

                try {
                    $this->line("Trying {$source['name']} API...");
                    $response = Http::timeout(10)->get($source['url']);

                    if ($response->successful()) {
                        $data = $response->json();
                        if (!empty($data) && is_array($data)) {
                            // Normalize API response format
                            $holidays = array_map(function ($item) use ($year) {
                                return [
                                    'date' => $item['date'] ?? $item['Date'] ?? '',
                                    'localName' => $item['localName'] ?? $item['LocalName'] ?? $item['name'] ?? $item['Name'] ?? '',
                                    'status' => 'libur',
                                ];
                            }, $data);
                            $this->info("✓ Fetched " . count($holidays) . " holidays from {$source['name']}");
                        }
                    }
                } catch (\Exception $e) {
                    $this->warn("  ✗ {$source['name']} API failed: " . $e->getMessage());
                }
            }

            // If all APIs failed, use hardcoded Indonesian holidays
            if (empty($holidays)) {
                $this->warn("All APIs failed, using local holiday database...");
                $holidays = $this->getIndonesianHolidays($year);
            }

            if (empty($holidays)) {
                $this->warn("No holidays found for {$year}");
                return 0;
            }

            $this->info("Found " . count($holidays) . " holidays. Syncing to database...");

            $synced = 0;
            $skipped = 0;

            foreach ($holidays as $holiday) {
                $date = Carbon::parse($holiday['date']);
                $localName = $holiday['localName'] ?? $holiday['name'];
                $status = $holiday['status'] ?? 'libur';

                // Check if holiday already exists
                $existing = DB::table('hari_libur')
                    ->whereDate('tanggal', $date->format('Y-m-d'))
                    ->first();

                if (!$existing) {
                    DB::table('hari_libur')->insert([
                        'tanggal' => $date->format('Y-m-d'),
                        'keterangan' => $localName,
                        'status' => $status,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    $synced++;
                    $this->line("  ✓ {$localName} ({$date->format('d M Y')}) [{$status}]");
                } else {
                    $skipped++;
                    $this->line("  → {$localName} ({$date->format('d M Y')}) - already exists");
                }
            }

            $this->info("✓ Sync complete: {$synced} added, {$skipped} skipped");
            Log::info("Holiday sync completed for {$year}: {$synced} added, {$skipped} skipped");

            return 0;

        } catch (\Exception $e) {
            $this->error("Error fetching holidays: " . $e->getMessage());
            Log::error("Holiday fetch error: " . $e->getMessage());
            return 1;
        }
    }

    /**
     * Get hardcoded Indonesian holidays
     */
    protected function getIndonesianHolidays(int $year): array
    {
        // Base holidays (fixed dates) - libur
        $holidays = [
            ['date' => "{$year}-01-01", 'localName' => 'Tahun Baru Masehi', 'status' => 'libur'],
            ['date' => "{$year}-05-01", 'localName' => 'Hari Buruh Internasional', 'status' => 'libur'],
            ['date' => "{$year}-05-20", 'localName' => 'Hari Kebangkitan Nasional', 'status' => 'libur'],
            ['date' => "{$year}-06-01", 'localName' => 'Hari Lahir Pancasila', 'status' => 'libur'],
            ['date' => "{$year}-08-17", 'localName' => 'Hari Kemerdekaan Republik Indonesia', 'status' => 'libur'],
            ['date' => "{$year}-08-18", 'localName' => 'Hari Kemerdekaan RI (Cuti Bersama)', 'status' => 'cuti'],
            ['date' => "{$year}-12-25", 'localName' => 'Hari Raya Natal', 'status' => 'libur'],
        ];

        // Islamic holidays (approximate dates, may vary)
        // These are rough estimates - actual dates depend on Hijri calendar
        if ($year == 2026) {
            $holidays = array_merge($holidays, [
                ['date' => '2026-01-29', 'localName' => 'Tahun Baru Imlek', 'status' => 'libur'],
                ['date' => '2026-03-19', 'localName' => 'Hari Suci Nyepi', 'status' => 'libur'],
                ['date' => '2026-04-03', 'localName' => 'Wafat Isa Almasih', 'status' => 'libur'],
                ['date' => '2026-05-01', 'localName' => 'Hari Raya Waisak', 'status' => 'libur'],
                ['date' => '2026-05-14', 'localName' => 'Kenaikan Isa Almasih', 'status' => 'libur'],
                ['date' => '2026-05-19', 'localName' => 'Hari Raya Idul Fitri 1447 H', 'status' => 'libur'],
                ['date' => '2026-05-20', 'localName' => 'Hari Raya Idul Fitri 1447 H (Cuti Bersama)', 'status' => 'cuti'],
                ['date' => '2026-05-21', 'localName' => 'Hari Raya Idul Fitri 1447 H (Cuti Bersama)', 'status' => 'cuti'],
                ['date' => '2026-05-26', 'localName' => 'Hari Raya Idul Adha 1447 H', 'status' => 'libur'],
                ['date' => '2026-05-27', 'localName' => 'Hari Raya Idul Adha 1447 H (Cuti Bersama)', 'status' => 'cuti'],
                ['date' => '2026-06-08', 'localName' => 'Tahun Baru Islam 1448 H', 'status' => 'libur'],
                ['date' => '2026-08-27', 'localName' => 'Maulid Nabi Muhammad SAW', 'status' => 'libur'],
            ]);
        } elseif ($year == 2027) {
            $holidays = array_merge($holidays, [
                ['date' => '2027-02-18', 'localName' => 'Tahun Baru Imlek', 'status' => 'libur'],
                ['date' => '2027-03-08', 'localName' => 'Hari Suci Nyepi', 'status' => 'libur'],
                ['date' => '2027-03-26', 'localName' => 'Wafat Isa Almasih', 'status' => 'libur'],
                ['date' => '2027-05-01', 'localName' => 'Hari Raya Waisak', 'status' => 'libur'],
                ['date' => '2027-05-06', 'localName' => 'Kenaikan Isa Almasih', 'status' => 'libur'],
                ['date' => '2027-05-08', 'localName' => 'Hari Raya Idul Fitri 1448 H', 'status' => 'libur'],
                ['date' => '2027-05-15', 'localName' => 'Hari Raya Idul Adha 1448 H', 'status' => 'libur'],
                ['date' => '2027-05-28', 'localName' => 'Tahun Baru Islam 1449 H', 'status' => 'libur'],
                ['date' => '2027-08-16', 'localName' => 'Maulid Nabi Muhammad SAW', 'status' => 'libur'],
            ]);
        }

        return $holidays;
    }
}
