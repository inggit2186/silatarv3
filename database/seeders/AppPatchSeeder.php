<?php

namespace Database\Seeders;

use App\Models\AppPatch;
use Illuminate\Database\Seeder;

class AppPatchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Sample patch update (Dart only - small)
        AppPatch::create([
            'version' => '2.0.1',
            'version_code' => 2,
            'update_type' => 'patch',
            'file_name' => 'silatar-patch-2.0.1.zip',
            'file_path' => 'patches/silatar-patch-2.0.1.zip',
            'file_size' => 1024 * 150, // 150 KB
            'md5' => 'demo-md5-hash-for-patch',
            'size_hint' => '150 KB',
            'changelog' => "- Perbaikan bug halaman login\n- Update tampilan daftar layanan\n- Perbaikan notifikasi",
            'is_mandatory' => false,
            'is_active' => true,
        ]);

        // Sample APK update (full APK - for native/plugin changes)
        AppPatch::create([
            'version' => '2.1.0',
            'version_code' => 100,
            'update_type' => 'apk',
            'file_name' => 'silatar-2.1.0.apk',
            'file_path' => 'patches/apk/silatar-2.1.0.apk',
            'file_size' => 1024 * 1024 * 35, // 35 MB
            'md5' => 'demo-md5-hash-for-apk',
            'apk_url' => 'patches/apk/silatar-2.1.0.apk',
            'size_hint' => '35 MB',
            'changelog' => "- Update plugin kamera ke versi terbaru\n- Perbaikan crash pada Android 14\n- Tambah fitur baru: scan dokumen",
            'is_mandatory' => false,
            'is_active' => true,
        ]);
    }
}
