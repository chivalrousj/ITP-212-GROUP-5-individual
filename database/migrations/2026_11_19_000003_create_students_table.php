<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            // unique + cascade: one student row per user, removed when the user is deleted
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            // restrict on delete: a program that still has students cannot be deleted
            $table->foreignId('program_id')->constrained('programs')->restrictOnDelete();
            $table->string('student_no')->unique();
            $table->enum('sex', ['Male', 'Female']);
            $table->unsignedTinyInteger('year_level');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
