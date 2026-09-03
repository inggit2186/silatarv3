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
        Schema::table('ktd_presensi', function (Blueprint $table) {
            $table->string('import_batch_id')->nullable()->after('manual_unit_kerja');
            $table->integer('imported_by')->nullable()->after('import_batch_id');
            $table->timestamp('imported_at')->nullable()->after('imported_by');
            $table->string('import_source')->nullable()->after('imported_at')->default('system');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ktd_presensi', function (Blueprint $table) {
            $table->dropColumn([
                'import_batch_id',
                'imported_by',
                'imported_at',
                'import_source',
            ]);
        });
    }
};
