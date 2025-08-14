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

        Schema::create('checkpoints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('video_lesson_id')->references('id')->on('video_lessons')->constrained()->onDelete('cascade');
            $table->unsignedInteger('timestamp_seconds');
            $table->enum('event_type', ['quiz', 'note', 'popup']);
            $table->json('event_data');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('checkpoints');
    }
};
