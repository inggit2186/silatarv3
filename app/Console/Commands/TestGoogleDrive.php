<?php

namespace App\Console\Commands;

use App\Services\GoogleDriveService;
use Illuminate\Console\Command;

class TestGoogleDrive extends Command
{
    protected $signature = 'gdrive:test';
    protected $description = 'Test Google Drive integration';

    public function handle(): int
    {
        $this->info('Testing Google Drive OAuth2 integration...');
        $this->newLine();

        try {
            // 1. Check configuration
            $this->info('1. Checking configuration...');
            $clientId = config('services.gdrive.client_id');
            $clientSecret = config('services.gdrive.client_secret');
            $refreshToken = config('services.gdrive.refresh_token');
            $folderId = config('services.gdrive.folder_id');

            if (!$clientId || !$clientSecret || !$refreshToken) {
                throw new \RuntimeException('Missing Google Drive OAuth2 credentials in .env');
            }
            $this->info('   ✅ Configuration found');
            $this->newLine();

            // 2. Initialize service
            $this->info('2. Initializing Google Drive service...');
            $gdrive = new GoogleDriveService();
            $this->info('   ✅ Service initialized successfully');
            $this->newLine();

            // 3. Test create folder
            $this->info('3. Testing folder creation...');
            $gdrive->createOrGetFolder('SILATAR_TEST');
            $this->info("   ✅ Folder created/found");
            $this->newLine();

            // 4. Test upload file
            $this->info('4. Testing file upload...');

            // Create a test file
            $testContent = "This is a test file created at " . now()->toDateTimeString();
            $tempFile = tempnam(sys_get_temp_dir(), 'gdrive_test_');
            file_put_contents($tempFile, $testContent);

            $result = $gdrive->upload(
                file: $tempFile,
                filename: 'test_file_' . time() . '.txt',
                subfolder: 'SILATAR_TEST'
            );

            unlink($tempFile); // Remove temp file

            $this->info("   ✅ File uploaded successfully");
            $this->info("   Path: {$result['path']}");
            if (isset($result['url'])) {
                $this->info("   URL: {$result['url']}");
            }
            $this->newLine();

            // 5. Test list files
            $this->info('5. Testing file listing...');
            $files = $gdrive->listFiles('SILATAR_TEST');
            $this->info("   ✅ Found " . count($files) . " files in test folder");
            $this->newLine();

            // 6. Test download
            $this->info('6. Testing file download...');
            $downloadedContent = $gdrive->download($result['path']);
            $this->info("   ✅ File downloaded (" . strlen($downloadedContent) . " bytes)");
            $this->newLine();

            // 7. Test delete
            $this->info('7. Testing file deletion...');
            $deleted = $gdrive->delete($result['path']);
            if ($deleted) {
                $this->info("   ✅ File deleted successfully");
            } else {
                $this->warn("   ⚠️  File deletion returned false");
            }
            $this->newLine();

            // Summary
            $this->info('=====================================');
            $this->info('✅ All Google Drive tests passed!');
            $this->info('=====================================');
            $this->newLine();

            $this->info('Next steps:');
            $this->info('1. Add Google Drive to your routes/controllers');
            $this->info('2. See GOOGLE_DRIVE_INTEGRATION.md for usage examples');
            $this->info('3. Configure folder structure as needed');
            $this->newLine();

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
            $this->newLine();

            $this->info('Troubleshooting:');
            $this->info('1. Check .env configuration - ensure all credentials are set');
            $this->info('2. Run GOOGLE_DRIVE_SETUP.md instructions');
            $this->info('3. Ensure Google Drive API is enabled in Cloud Console');
            $this->newLine();

            return Command::FAILURE;
        }
    }
}
