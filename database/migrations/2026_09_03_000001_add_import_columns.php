<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('tenaga_ktd', 'rekening')) {
            Schema::table('tenaga_ktd', function (Blueprint $table) {
                $table->string('rekening')->nullable();
            });
        }

        if (!Schema::hasColumn('users', 'rekening')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('rekening')->nullable();
            });
        }

        if (!Schema::hasColumn('users', 'bank_kategori')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('bank_kategori')->nullable();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('tenaga_ktd', 'rekening')) {
            Schema::table('tenaga_ktd', function (Blueprint $table) {
                $table->dropColumn('rekening');
            });
        }

        $columnsToDrop = [];
        if (Schema::hasColumn('users', 'bank_kategori')) {
            $columnsToDrop[] = 'bank_kategori';
        }
        if (Schema::hasColumn('users', 'rekening')) {
            $columnsToDrop[] = 'rekening';
        }
        if (!empty($columnsToDrop)) {
            Schema::table('users', function (Blueprint $table) use ($columnsToDrop) {
                $table->dropColumn($columnsToDrop);
            });
        }
    }
};
