<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $unitId = DB::table('units')->value('id');

        if ($unitId === null) {
            $unitId = DB::table('units')->insertGetId([
                'name' => 'Unidade Centro',
                'code' => 'UNIT-01',
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        User::firstOrCreate(
            ['email' => 'admin@upsoluctions.com.br'],
            [
                'name' => 'UpSoluctions',
                'password' => Hash::make('P@ssw0rd'),
                'unit_id' => $unitId,
                'role' => UserRole::ADMIN,
                'email_verified_at' => now(),
            ],
        );

        $this->call(StudentSeeder::class);
        $this->call(TeacherSeeder::class);

    }
}
