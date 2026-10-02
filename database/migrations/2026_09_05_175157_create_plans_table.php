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
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->unsignedTinyInteger('duration_months');
            $table->unsignedSmallInteger('installments')->default(1);
            $table->decimal('price', 10, 2);
            $table->string('promotion_type', 20)->nullable();
            $table->decimal('promotion_value', 10, 2)->nullable();
            $table->date('promotion_start_date')->nullable();
            $table->date('promotion_end_date')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
