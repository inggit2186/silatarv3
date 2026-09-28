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
        Schema::create('app_patches', function (Blueprint $table) {
            $table->id();
            $table->string('version', 20)->comment('Patch version (e.g., 2.0.1)');
            $table->integer('version_code')->default(1)->comment('Numeric version code for comparison');
            $table->string('file_name', 255)->comment('Patch file name');
            $table->string('file_path', 500)->comment('Full path to patch file');
            $table->bigInteger('file_size')->default(0)->comment('File size in bytes');
            $table->string('md5', 64)->nullable()->comment('MD5 hash of patch file');
            $table->text('changelog')->nullable()->comment('Description of changes');
            $table->boolean('is_mandatory')->default(false)->comment('Mandatory update flag');
            $table->boolean('is_active')->default(true)->comment('Active/inactive patch');
            $table->string('min_app_version', 20)->nullable()->comment('Minimum app version required');
            $table->string('max_app_version', 20)->nullable()->comment('Maximum app version (null = no limit)');
            $table->timestamps();

            $table->unique(['version']);
            $table->index(['version_code']);
            $table->index(['is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_patches');
    }
};
