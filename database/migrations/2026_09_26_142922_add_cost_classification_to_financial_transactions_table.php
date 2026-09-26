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
            $table->string('cost_classification', 20)->default('variable')->after('transaction_type')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('financial_transactions', function (Blueprint $table): void {
            $table->dropColumn('cost_classification');
        });
    }
};
