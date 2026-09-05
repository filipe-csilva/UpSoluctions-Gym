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
        Schema::create('workout_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id') ->constrained('student_profiles') ->restrictOnDelete();
            $table->foreignId('teacher_id') ->constrained('users') ->restrictOnDelete();
            $table->string('name', 150);
            $table->text('description') ->nullable();
            $table->date('start_date');
            $table->date('end_date') ->nullable();
            $table->string('status', 20);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workout_plans');
    }
};
