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
        Schema::table('ktd_layanan', function (Blueprint $table) {
            if (!Schema::hasColumn('ktd_layanan', 'tipe')) {
                $table->string('tipe')->default('normal')->after('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ktd_layanan', function (Blueprint $table) {
            $table->dropColumn('tipe');
        });
    }
};
