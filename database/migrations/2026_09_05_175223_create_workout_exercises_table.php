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
        Schema::create('workout_exercises', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workout_plan_id') ->constrained('workout_plans') ->cascadeOnDelete();
            $table->foreignId('exercise_id') ->constrained('exercises') ->restrictOnDelete();
            $table->string('day', 20);
            $table->unsignedTinyInteger('sets');
            $table->string('repetitions', 30);
            $table->decimal('weight', 8, 2) ->nullable();
            $table->unsignedSmallInteger('rest_seconds') ->nullable();
            $table->unsignedSmallInteger('sequence');
            $table->text('notes') ->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workout_exercises');
    }
};
