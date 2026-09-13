<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StudentSeeder extends Seeder
{
    /**
     * Create sample units, users, and student profiles for local development.
     */
    public function run(): void
    {
        DB::transaction(function (): void {
            $unitIds = DB::table('units')->pluck('id');

            if ($unitIds->isEmpty()) {
                throw new \RuntimeException(
                    'Nenhuma unidade encontrada. Execute o DatabaseSeeder antes do StudentSeeder.'
                );
            }

            foreach (range(1, 10) as $number) {
                $user = User::factory()->create([
                    'name' => fake()->name(),
                    'unit_id' => $unitIds[($number - 1) % $unitIds->count()],
                    'role' => UserRole::STUDENT,
                ]);

                DB::table('student_profiles')->insert([
                    'user_id' => $user->id,
                    'cpf' => fake()->unique()->numerify('###.###.###-##'),
                    'birth_date' => fake()->dateTimeBetween('-45 years', '-18 years')->format('Y-m-d'),
                    'phone' => fake()->numerify('859########'),
                    'gender' => fake()->randomElement(['M', 'F']),
                    'address' => fake()->streetAddress(),
                    'number' => (string) fake()->numberBetween(1, 9999),
                    'neighborhood' => fake()->citySuffix(),
                    'city' => fake()->city(),
                    'state' => 'CE',
                    'zip_code' => fake()->numerify('########'),
                    'emergency_contact' => fake()->name(),
                    'emergency_phone' => fake()->numerify('859########'),
                    'notes' => 'Aluno criado pelo StudentSeeder.',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        });
    }
}
