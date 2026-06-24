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
            $table->unsignedBigInteger('view_count')->default(0)->after('status');
            $table->unsignedBigInteger('unique_view_count')->default(0)->after('view_count');
        });

        Schema::create('news_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('news_id')->constrained('news')->onDelete('cascade');
            $table->string('ip_address', 45)->nullable(); // IPv4/IPv6
            $table->string('session_id')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamp('viewed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('news', function (Blueprint $table) {
            $table->dropColumn(['view_count', 'unique_view_count']);
        });
        Schema::dropIfExists('news_views');
    }
};
