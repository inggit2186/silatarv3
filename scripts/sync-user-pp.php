<?php

declare(strict_types=1);

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$baseUrl = 'https://ptsp.kemenagtanahdatar.cloud/uploads/UsersBerkas';
$targetRoot = public_path('assets/img/users');
$users = DB::table('users')
    ->whereNotNull('pp')
    ->where('pp', '<>', '')
    ->select('id', 'nomor_induk', 'pp')
    ->orderBy('id')
    ->get();

$total = $users->count();
$synced = 0;
$skipped = 0;
$failed = 0;

foreach ($users as $index => $user) {
    $directory = $targetRoot . DIRECTORY_SEPARATOR . $user->nomor_induk;
    $filePath = $directory . DIRECTORY_SEPARATOR . $user->pp;
    $fileExists = file_exists($filePath) && filesize($filePath) > 0;

    if ($fileExists) {
        $skipped++;
        if (($index + 1) % 100 === 0 || $index + 1 === $total) {
            echo sprintf("[%d/%d] skipped %d, synced %d, failed %d\n", $index + 1, $total, $skipped, $synced, $failed);
        }
        continue;
    }

    if (! is_dir($directory)) {
        mkdir($directory, 0777, true);
    }

    $remoteUrl = $baseUrl . '/' . rawurlencode((string) $user->nomor_induk) . '/' . rawurlencode((string) $user->pp);

    try {
        $response = Http::timeout(60)->retry(3, 1000)->get($remoteUrl);

        if (! $response->successful()) {
            throw new RuntimeException('HTTP ' . $response->status());
        }

        file_put_contents($filePath, $response->body());
        $synced++;
    } catch (Throwable $e) {
        $failed++;
        fwrite(STDERR, sprintf("Failed: user_id=%d nomor_induk=%s file=%s reason=%s\n", $user->id, $user->nomor_induk, $user->pp, $e->getMessage()));
    }

    if (($index + 1) % 100 === 0 || $index + 1 === $total) {
        echo sprintf("[%d/%d] skipped %d, synced %d, failed %d\n", $index + 1, $total, $skipped, $synced, $failed);
    }
}

echo sprintf("Done. total=%d synced=%d skipped=%d failed=%d\n", $total, $synced, $skipped, $failed);
