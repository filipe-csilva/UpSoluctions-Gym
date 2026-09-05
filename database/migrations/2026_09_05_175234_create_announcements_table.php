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
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_id') ->nullable() ->constrained('units') ->nullOnDelete(); $table->foreignId('created_by') ->constrained('users') ->restrictOnDelete();
            $table->string('title', 150);
            $table->text('message');
            $table->string('target_role', 20) ->nullable();
            $table->dateTime('start_at') ->nullable();
            $table->dateTime('end_at') ->nullable();
            $table->boolean('active') ->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
