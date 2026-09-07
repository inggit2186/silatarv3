<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users_request', function (Blueprint $table) {
            $table->string('tujuan')->nullable()->after('lampiran');
        });
    }

    public function down(): void
    {
        Schema::table('users_request', function (Blueprint $table) {
            $table->dropColumn('tujuan');
        });
    }
};
