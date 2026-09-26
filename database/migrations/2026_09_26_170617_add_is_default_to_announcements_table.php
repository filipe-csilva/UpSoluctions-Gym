<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('announcements', function (Blueprint $table): void {
            $table->boolean('is_default')->default(false)->after('active');
        });

        $initialAnnouncementId = DB::table('announcements')->orderBy('id')->value('id');
        if ($initialAnnouncementId !== null) {
            DB::table('announcements')->where('id', $initialAnnouncementId)->update(['is_default' => true]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('announcements', function (Blueprint $table): void {
            $table->dropColumn('is_default');
        });
    }
};
