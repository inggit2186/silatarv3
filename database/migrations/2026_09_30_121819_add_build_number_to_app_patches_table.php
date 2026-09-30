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
            $table->unsignedInteger('build_number')->nullable()->after('version_code');
        });
    }

    public function down(): void
    {
        Schema::table('app_patches', function (Blueprint $table) {
            $table->dropColumn('build_number');
        });
    }
};
