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
        Schema::create('financial_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enrollment_id') ->constrained('enrollments') ->restrictOnDelete();
            $table->foreignId('student_id') ->constrained('student_profiles') ->restrictOnDelete();
            $table->foreignId('unit_id') ->constrained('units') ->restrictOnDelete();
            $table->string('description', 255);
            $table->decimal('amount', 10, 2);
            $table->date('due_date');
            $table->dateTime('paid_at')->nullable();
            $table->string('status', 20);
            $table->string('payment_method', 30)->nullable();
            $table->string('transaction_type', 20);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('financial_transactions');
    }
};
