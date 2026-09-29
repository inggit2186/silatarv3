<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('app_patches', function (Blueprint $table) {
            // Add update_type column after is_active
            // patch = hot code push (Dart only)
            // apk = full APK update (native + Dart)
            $table->enum('update_type', ['patch', 'apk'])->default('patch')->after('is_active');

            // Add APK file URL for apk type updates
            $table->string('apk_url', 500)->nullable()->after('update_type');

            // Add file size hint (string untuk display friendly)
            $table->string('size_hint', 20)->nullable()->after('apk_url');

            // Index for faster queries
            $table->index(['update_type', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('app_patches', function (Blueprint $table) {
            $table->dropIndex(['update_type', 'is_active']);
            $table->dropColumn(['update_type', 'apk_url', 'size_hint']);
        });
    }
};
