<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_session_id')->constrained('event_sessions')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->dateTime('check_in_at')->nullable();
            $table->dateTime('check_out_at')->nullable();
            $table->timestamps();

            // a student has only one attendance row for each session, even with a double click
            $table->unique(['event_session_id', 'student_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
