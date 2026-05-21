<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users_request_answers', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('request_id')->index();
            $table->string('no_req', 50)->index();
            $table->unsignedBigInteger('syarat_id')->index();
            $table->longText('value')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users_request_answers');
    }
};
