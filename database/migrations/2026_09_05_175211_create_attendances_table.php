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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id') ->constrained('student_profiles') ->restrictOnDelete();
            $table->foreignId('unit_id') ->constrained('units') ->restrictOnDelete();
            $table->foreignId('registered_by') ->constrained('users') ->restrictOnDelete();
            $table->date('date');
            $table->time('entry_time');
            $table->time('exit_time') ->nullable();
            $table->string('type', 20);
            $table->text('notes') ->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
