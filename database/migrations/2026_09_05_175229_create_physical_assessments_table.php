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
        Schema::create('physical_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id') ->constrained('student_profiles') ->restrictOnDelete();
            $table->foreignId('teacher_id') ->constrained('users') ->restrictOnDelete();
            $table->date('assessment_date');
            $table->decimal('height', 5, 2);
            $table->decimal('weight', 6, 2);
            $table->decimal('body_fat', 5, 2) ->nullable();
            $table->decimal('muscle_mass', 6, 2) ->nullable();
            $table->decimal('bmi', 5, 2) ->nullable();
            $table->decimal('waist', 6, 2) ->nullable();
            $table->decimal('abdomen', 6, 2) ->nullable();
            $table->decimal('hip', 6, 2) ->nullable();
            $table->decimal('chest', 6, 2) ->nullable();
            $table->decimal('arm', 6, 2) ->nullable();
            $table->decimal('thigh', 6, 2) ->nullable();
            $table->text('notes') ->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('physical_assessments');
    }
};
