<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * New Hybrid Versioning Fields:
     * - patch_count: Counter untuk patch (reset per appVersionCode)
     * - build_number: Total global builds counter
     */
    public function up(): void
    {
        Schema::table('app_patches', function (Blueprint $table) {
            // Patch count - counter untuk patch dalam satu appVersionCode
            // Reset ke 1 saat APK baru (appVersionCode berubah)
            // Default 0 untuk APK, > 0 untuk patch
            $table->integer('patch_count')
                ->default(0)
                ->after('version_code')
                ->comment('Patch counter (reset per appVersionCode)');

            // Build number - total global builds counter
            // Selalu increment setiap APK baru
            $table->integer('build_number')
                ->default(0)
                ->after('patch_count')
                ->comment('Global build counter (total builds forever)');

            // Add index for faster queries
            $table->index(['version_code', 'patch_count']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('app_patches', function (Blueprint $table) {
            $table->dropIndex(['version_code', 'patch_count']);
            $table->dropColumn(['patch_count', 'build_number']);
        });
    }
};
