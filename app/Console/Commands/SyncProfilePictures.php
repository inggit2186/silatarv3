<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class SyncProfilePictures extends Command
{
    protected $signature = 'sync:pp {--dry-run : Preview without saving}';
    protected $description = 'Sync profile pictures from external API to local storage';

    public function handle(): int
    {
        // Increase memory limit for image processing
        ini_set('memory_limit', '512M');

        $this->info('Fetching data from API...');

        $response = Http::timeout(60)->get('https://ptsp.kemenagtanahdatar.cloud/api/v1/getASNList');

        if (!$response->successful()) {
            $this->error('Failed to fetch API data');
            return 1;
        }

        $apiData = $response->json();
        $apiUsers = collect($apiData['data'])->keyBy('id');

        $this->info("API users count: " . $apiUsers->count());

        $localUsers = DB::table('users')->get();
        $this->info("Local users count: " . $localUsers->count());

        // Initialize ImageManager with GD driver
        $manager = new ImageManager(new Driver());

        $matched = 0;
        $skipped = 0;
        $updated = 0;
        $errors = 0;
        $dryRun = $this->option('dry-run');

        $this->newLine();
        $this->info("Starting sync with image compression (300x300, WebP, 75% quality)...");

        $bar = $this->output->createProgressBar($localUsers->count());
        $bar->start();

        foreach ($localUsers as $user) {
            $apiUser = $apiUsers->get($user->id);

            if (!$apiUser) {
                $skipped++;
                $bar->advance();
                continue;
            }

            $matched++;

            $ppUrl = $apiUser['pp'] ?? null;
            $nomorInduk = $apiUser['nomor_induk'] ?? null;

            if (!$ppUrl || !$nomorInduk || str_contains($ppUrl, 'defaultpp.png')) {
                $skipped++;
                $bar->advance();
                continue;
            }

            // Create folder in public storage (for symlink access)
            $folderPath = 'users_berkas/' . $nomorInduk;
            $filename = $nomorInduk . '.pp.webp';
            $fullPath = $folderPath . '/' . $filename;

            if (!$dryRun) {
                try {
                    // Download image
                    $imageResponse = Http::timeout(30)->get($ppUrl);

                    if ($imageResponse->successful()) {
                        // Process and compress image with Intervention Image
                        $image = $manager->decodeBinary($imageResponse->body());

                        // Resize to max 300x300 while maintaining aspect ratio (cover)
                        $image->cover(300, 300);

                        // Encode as webp with quality 75%
                        $encodedImage = $image->encodeUsingFormat(\Intervention\Image\Format::WEBP, quality: 75);

                        // Use 'public' disk to save in storage/app/public
                        if (!Storage::disk('public')->exists($folderPath)) {
                            Storage::disk('public')->makeDirectory($folderPath);
                        }

                        // Delete old files with same pattern
                        $oldFiles = Storage::disk('public')->files($folderPath);
                        foreach ($oldFiles as $oldFile) {
                            if (str_contains($oldFile, '.pp.')) {
                                Storage::disk('public')->delete($oldFile);
                            }
                        }

                        // Save new file
                        Storage::disk('public')->put($fullPath, $encodedImage->toString());

                        // Free memory
                        unset($image, $encodedImage, $imageResponse);

                        // Update database with only filename (path constructed in blade)
                        DB::table('users')
                            ->where('id', $user->id)
                            ->update([
                                'pp' => $filename,
                                'nomor_induk' => $nomorInduk
                            ]);

                        $updated++;
                    }
                } catch (\Exception $e) {
                    $this->error("\nError processing PP for user {$user->id}: " . $e->getMessage());
                    $errors++;
                }
            }

            $bar->advance();

            // Force garbage collection every 50 users
            if ($matched % 50 === 0) {
                gc_collect_cycles();
            }
        }

        $bar->finish();
        $this->newLine(2);

        $this->table(
            ['Metric', 'Count'],
            [
                ['Matched users', $matched],
                ['Skipped (no PP)', $skipped],
                ['Updated', $updated],
                ['Errors', $errors],
            ]
        );

        if ($dryRun) {
            $this->warn('Dry run mode - no changes were saved');
        } else {
            $this->info("Sync completed! Images resized to 300x300 WebP (quality 75%)");
        }

        return $errors > 0 ? 1 : 0;
    }
}
