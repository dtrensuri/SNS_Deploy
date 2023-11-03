<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->text('content')->nullable();
            $table->integer('total_impressions')->nullable()->default(0);
            $table->integer('total_engaged')->nullable()->default(0);
            $table->integer('total_reactions')->nullable()->default(0);
            $table->integer('total_comment')->nullable()->default(0);
            $table->integer('total_share')->nullable()->default(0);
            $table->string('post_id')->unique();
            $table->string('platform');
            $table->dateTime('scheduled_time')->nullable();
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};