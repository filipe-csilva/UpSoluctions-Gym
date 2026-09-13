<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\TeacherProfile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TeacherSeeder extends Seeder
{
    /**
     * Create five sample instructors distributed across two units.
     */
    public function run(): void
    {
        DB::transaction(function (): void {
            $unitIds = DB::table('units')->orderBy('id')->pluck('id');

            if ($unitIds->isEmpty()) {
                throw new \RuntimeException('Nenhuma unidade encontrada. Execute o DatabaseSeeder antes do TeacherSeeder.');
            }

            if ($unitIds->count() === 1) {
                $unitIds->push(DB::table('units')->insertGetId([
                    'name' => 'Unidade Norte',
                    'code' => 'UNIT-02',
                    'active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]));
            }

            $teachers = [
                ['name' => 'Instrutor Centro 1', 'email' => 'instrutor.centro1@upsoluctions.com.br', 'unit_id' => $unitIds[0]],
                ['name' => 'Instrutor Centro 2', 'email' => 'instrutor.centro2@upsoluctions.com.br', 'unit_id' => $unitIds[0]],
                ['name' => 'Instrutor Centro 3', 'email' => 'instrutor.centro3@upsoluctions.com.br', 'unit_id' => $unitIds[0]],
                ['name' => 'Instrutor Norte 1', 'email' => 'instrutor.norte1@upsoluctions.com.br', 'unit_id' => $unitIds[1]],
                ['name' => 'Instrutor Norte 2', 'email' => 'instrutor.norte2@upsoluctions.com.br', 'unit_id' => $unitIds[1]],
            ];

            foreach ($teachers as $teacherData) {
                $user = User::firstOrCreate(
                    ['email' => $teacherData['email']],
                    [
                        'name' => $teacherData['name'],
                        'password' => Hash::make('P@ssw0rd'),
                        'unit_id' => $teacherData['unit_id'],
                        'role' => UserRole::TEACHER,
                        'active' => true,
                        'email_verified_at' => now(),
                    ],
                );

                TeacherProfile::firstOrCreate(
                    ['user_id' => $user->id],
                    [
                        'cpf' => fake()->unique()->numerify('###.###.###-##'),
                        'birth_date' => fake()->dateTimeBetween('-45 years', '-18 years')->format('Y-m-d'),
                        'phone' => fake()->numerify('859########'),
                        'specialty' => fake()->randomElement(['Musculação', 'Funcional', 'Personal trainer']),
                        'registration' => fake()->numerify('CREF-######-G/CE'),
                        'active' => true,
                    ],
                );
            }
        });
    }
}
