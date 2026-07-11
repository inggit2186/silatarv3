<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Sharp\Image;

class OptimizeImagesCommand extends Command
{
    protected $signature = 'images:optimize
                            {--path= : Specific path to optimize}
                            {--quality=80 : WebP quality (1-100)}
                            {--resize= : Max width to resize (optional)}
                            {--dry-run : Show what would be converted without converting}
                            {--replace : Replace original files with WebP}';

    protected $description = 'Optimize images by converting to WebP format with compression';

    public function handle(): int
    {
        $basePath = public_path('assets/img');
        $specificPath = $this->option('path');
        $quality = (int) $this->option('quality');
        $maxWidth = $this->option('resize') ? (int) $this->option('resize') : null;
        $dryRun = $this->option('dry-run');
        $replace = $this->option('replace');

        if ($specificPath) {
            $targetPath = public_path('assets/img/' . ltrim($specificPath, '/'));
        } else {
            $targetPath = $basePath;
        }

        if (!File::exists($targetPath)) {
            $this->error("Path not found: {$targetPath}");
            return self::FAILURE;
        }

        $this->info("Optimizing images in: {$targetPath}");
        $this->info("Quality: {$quality}%");
        if ($maxWidth) {
            $this->info("Max width: {$maxWidth}px");
        }
        if ($replace) {
            $this->warn("Mode: REPLACE original files");
        } else {
            $this->info("Mode: Create .webp copies");
        }
        $this->info("");

        $stats = [
            'scanned' => 0,
            'converted' => 0,
            'skipped' => 0,
            'failed' => 0,
            'size_before' => 0,
            'size_after' => 0,
        ];

        $extensions = ['png', 'jpg', 'jpeg', 'gif'];
        $files = File::allFiles($targetPath);

        foreach ($files as $file) {
            $extension = strtolower($file->getExtension());

            if (!in_array($extension, $extensions)) {
                continue;
            }

            $stats['scanned']++;
            $currentPath = $file->getPathname();
            $currentSize = filesize($currentPath);
            $stats['size_before'] += $currentSize;

            // Determine output path
            if ($replace) {
                $outputPath = preg_replace('/\.(png|jpg|jpeg|gif)$/i', '.webp', $currentPath);
            } else {
                $outputPath = preg_replace('/\.(png|jpg|jpeg|gif)$/i', '.webp', $currentPath);
            }

            // Skip if output already exists
            if (File::exists($outputPath) && !$replace) {
                $stats['skipped']++;
                continue;
            }

            if ($dryRun) {
                $this->line("  [DRY-RUN] Would convert: {$file->getFilename()} ({$this->formatBytes($currentSize)})");
                $stats['converted']++;
                $stats['size_after'] += $currentSize * 0.5; // Estimate 50% reduction
                continue;
            }

            try {
                // Read image with Sharp
                $sharp = \imagecreatefromstring(File::get($currentPath));

                if (!$sharp) {
                    throw new \Exception("Failed to read image");
                }

                // Get original dimensions
                $origWidth = imagesx($sharp);
                $origHeight = imagesy($sharp);

                // Resize if needed
                $width = $origWidth;
                $height = $origHeight;
                if ($maxWidth && $origWidth > $maxWidth) {
                    $ratio = $maxWidth / $origWidth;
                    $width = $maxWidth;
                    $height = (int) ($origHeight * $ratio);
                }

                // Create resized image
                $resized = imagecreatetruecolor($width, $height);
                imagealphablending($resized, false);
                imagesavealpha($resized, true);
                imagecopyresampled($resized, $sharp, 0, 0, 0, 0, $width, $height, $origWidth, $origHeight);

                // Save as WebP
                imagewebp($resized, $outputPath, $quality);

                // Cleanup
                imagedestroy($sharp);
                imagedestroy($resized);

                $newSize = filesize($outputPath);
                $stats['size_after'] += $newSize;
                $stats['converted']++;

                $reduction = round((1 - $newSize / $currentSize) * 100, 1);
                $arrow = $reduction > 0 ? '→' : '←';
                $color = $reduction > 0 ? '<info>✓</info>' : '<comment>!</comment>';
                $this->line("  {$color} {$file->getFilename()}: {$this->formatBytes($currentSize)} {$arrow} {$this->formatBytes($newSize)} ({$reduction}%)");

                // Optionally remove original
                if ($replace && $currentPath !== $outputPath) {
                    File::delete($currentPath);
                    $this->line("    <info>Deleted original:</info> {$currentPath}");
                }

            } catch (\Exception $e) {
                $this->error("  ✗ Failed: {$file->getFilename()}: {$e->getMessage()}");
                $stats['failed']++;
            }
        }

        $this->info("");
        $this->info("=== Summary ===");
        $this->info("Scanned: {$stats['scanned']} files");
        $this->info("Converted: {$stats['converted']} files");
        $this->info("Skipped: {$stats['skipped']} files");
        $this->info("Failed: {$stats['failed']} files");
        $this->info("");

        if ($stats['size_before'] > 0) {
            $totalReduction = round((1 - $stats['size_after'] / $stats['size_before']) * 100, 1);
            $this->info("Size before: {$this->formatBytes($stats['size_before'])}");
            $this->info("Size after: {$this->formatBytes($stats['size_after'])}");
            $this->info("Total reduction: {$totalReduction}%");
        }

        return self::SUCCESS;
    }

    protected function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }
        return round($bytes, 1) . ' ' . $units[$i];
    }
}
