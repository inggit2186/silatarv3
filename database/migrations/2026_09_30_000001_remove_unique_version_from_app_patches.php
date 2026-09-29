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
            // Remove unique constraint on version column
            // Only version_code should be unique
            $table->dropUnique(['version']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('app_patches', function (Blueprint $table) {
            // Restore unique constraint on version
            $table->unique(['version']);
        });
    }
};
