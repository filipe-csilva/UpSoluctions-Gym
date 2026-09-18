<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('users')->whereIn('role', ['admin', 'manager', 'financial'])->get()->each(function (object $user): void {
            DB::table('employee_profiles')->updateOrInsert(['user_id' => $user->id], ['created_at' => now(), 'updated_at' => now()]);
        });
    }

    public function down(): void
    {
        DB::table('employee_profiles')->whereIn('user_id', function ($query): void {
            $query->select('id')->from('users')->whereIn('role', ['admin', 'manager', 'financial']);
        })->delete();
    }
};
