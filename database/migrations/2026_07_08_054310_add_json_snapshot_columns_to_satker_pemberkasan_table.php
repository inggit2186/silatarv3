<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('satker_pemberkasan', function (Blueprint $table) {
            $table->json('files')->nullable()->after('deskripsi');
            $table->json('metadata')->nullable()->after('files');
            $table->json('requirements_snapshot')->nullable()->after('metadata');
            $table->boolean('is_migrated')->default(false)->after('status');
            $table->timestamp('migrated_at')->nullable()->after('is_migrated');
        });
    }

    public function down(): void
    {
        Schema::table('satker_pemberkasan', function (Blueprint $table) {
            $table->dropColumn([
                'files',
                'metadata',
                'requirements_snapshot',
                'is_migrated',
                'migrated_at',
            ]);
        });
    }
};
