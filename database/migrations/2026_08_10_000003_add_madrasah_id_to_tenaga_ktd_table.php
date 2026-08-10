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
        Schema::table('tenaga_ktd', function (Blueprint $table) {
            if (!Schema::hasColumn('tenaga_ktd', 'madrasah_id')) {
                $table->unsignedBigInteger('madrasah_id')->nullable()->after('dept_id');
                $table->index('madrasah_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenaga_ktd', function (Blueprint $table) {
            $table->dropIndex(['madrasah_id']);
            $table->dropColumn('madrasah_id');
        });
    }
};
