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
        if (Schema::hasColumn('student_profiles', 'andress') && ! Schema::hasColumn('student_profiles', 'address')) {
            Schema::table('student_profiles', function (Blueprint $table): void {
                $table->renameColumn('andress', 'address');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('student_profiles', 'address') && ! Schema::hasColumn('student_profiles', 'andress')) {
            Schema::table('student_profiles', function (Blueprint $table): void {
                $table->renameColumn('address', 'andress');
            });
        }
    }
};
