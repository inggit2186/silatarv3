<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ppid_sections', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('page_id');
            $table->string('section_key', 100);
            $table->enum('section_type', [
                'text',
                'list',
                'card_grid',
                'timeline',
                'stats',
                'table',
                'form_fields',
                'image'
            ]);
            $table->string('title', 255)->nullable();
            $table->longText('content')->nullable();
            $table->json('metadata')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_visible')->default(true);
            $table->timestamps();

            $table->foreign('page_id')->references('id')->on('ppid_pages')->onDelete('cascade');
            $table->unique(['page_id', 'section_key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ppid_sections');
    }
};
