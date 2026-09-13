<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('units', 'address') && ! Schema::hasColumn('units', 'address')) {
            Schema::table('units', function (Blueprint $table): void {
                $table->renameColumn('address', 'address');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('units', 'address') && ! Schema::hasColumn('units', 'address')) {
            Schema::table('units', function (Blueprint $table): void {
                $table->renameColumn('address', 'address');
            });
        }
    }
};
