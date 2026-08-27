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
        if (!Schema::hasColumn('tenaga_ktd', 'instansi')) {
            Schema::table('tenaga_ktd', function (Blueprint $table) {
                $table->string('instansi')->nullable()->after('pjob');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenaga_ktd', function (Blueprint $table) {
            $table->dropColumn('instansi');
        });
    }
};
