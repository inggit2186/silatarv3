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
        Schema::table('news', function (Blueprint $table) {
            $table->boolean('is_slideshow')->default(false)->after('is_featured');
            $table->string('writer', 255)->nullable()->after('author_id');
            $table->string('editor', 255)->nullable()->after('writer');
            $table->string('photographer', 255)->nullable()->after('editor');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('news', function (Blueprint $table) {
            $table->dropColumn(['is_slideshow', 'writer', 'editor', 'photographer']);
        });
    }
};