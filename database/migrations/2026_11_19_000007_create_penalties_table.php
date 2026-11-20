<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penalties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->unsignedTinyInteger('missed_scans')->default(0);
            $table->decimal('amount', 8, 2)->default(0);
            $table->string('sanction')->nullable();
            $table->enum('status', ['unpaid', 'paid', 'served'])->default('unpaid');
            $table->timestamps();

            // one penalty row for each student in each event
            $table->unique(['event_id', 'student_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penalties');
    }
};
