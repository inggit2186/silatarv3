<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('tenaga_ktd', 'bank')) {
            Schema::table('tenaga_ktd', function (Blueprint $table) {
                $table->string('bank', 100)->nullable()->after('rekening');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('tenaga_ktd', 'bank')) {
            Schema::table('tenaga_ktd', function (Blueprint $table) {
                $table->dropColumn('bank');
            });
        }
    }
};
