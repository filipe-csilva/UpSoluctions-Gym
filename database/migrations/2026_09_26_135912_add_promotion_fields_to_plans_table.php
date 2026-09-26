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
        Schema::table('plans', function (Blueprint $table): void {
            $table->string('promotion_type', 20)->nullable()->after('price');
            $table->decimal('promotion_value', 10, 2)->nullable()->after('promotion_type');
            $table->date('promotion_start_date')->nullable()->after('promotion_value');
            $table->date('promotion_end_date')->nullable()->after('promotion_start_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table): void {
            $table->dropColumn(['promotion_type', 'promotion_value', 'promotion_start_date', 'promotion_end_date']);
        });
    }
};
