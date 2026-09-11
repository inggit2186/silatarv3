<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ppid_gallery', function (Blueprint $table) {
            $table->id();
            $table->string('page_slug', 100);
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->string('image_path', 500);
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('page_slug');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ppid_gallery');
    }
};
