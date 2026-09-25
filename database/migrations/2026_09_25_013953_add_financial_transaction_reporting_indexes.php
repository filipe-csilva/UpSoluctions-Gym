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
        Schema::table('financial_transactions', function (Blueprint $table): void {
            $table->index(['unit_id', 'status', 'due_date'], 'financial_unit_status_due_index');
            $table->index(['transaction_type', 'paid_at'], 'financial_type_paid_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('financial_transactions', function (Blueprint $table): void {
            $table->dropIndex('financial_unit_status_due_index');
            $table->dropIndex('financial_type_paid_index');
        });
    }
};
