<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\Exercise;
use App\Models\PhysicalAssessment;
use App\Models\StudentProfile;
use App\Models\User;
use App\Models\WorkoutPlan;
use Illuminate\Database\Seeder;

class TrainingAssessmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $exercises = collect([
            ['name' => 'Supino reto', 'muscle_group' => 'Peito', 'equipment' => 'Barra e banco', 'instructions' => 'Controlar a descida.'],
            ['name' => 'Agachamento livre', 'muscle_group' => 'Pernas', 'equipment' => 'Barra', 'instructions' => 'Manter a coluna neutra.'],
            ['name' => 'Puxada frontal', 'muscle_group' => 'Costas', 'equipment' => 'Polia', 'instructions' => 'Puxar até a linha do peito.'],
        ])->map(fn (array $data): Exercise => Exercise::firstOrCreate(['name' => $data['name']], $data));

        $student = StudentProfile::with('user')->first();
        $teacher = User::where('role', 'teacher')->where('active', true)->first();

        if ($student && $teacher && $student->user?->unit_id) {
            Attendance::firstOrCreate(['student_id' => $student->id, 'date' => today()], ['unit_id' => $student->user->unit_id, 'registered_by' => $teacher->id, 'entry_time' => now()->format('H:i:s'), 'type' => 'regular']);
            $workout = WorkoutPlan::firstOrCreate(['student_id' => $student->id, 'name' => 'Ficha inicial'], ['teacher_id' => $teacher->id, 'start_date' => today(), 'status' => 'active', 'description' => 'Ficha de demonstração.']);
            foreach ($exercises as $sequence => $exercise) {
                $workout->exercises()->firstOrCreate(['exercise_id' => $exercise->id], ['day' => 'A', 'sets' => 3, 'repetitions' => '12', 'weight' => 10, 'rest_seconds' => 60, 'sequence' => $sequence + 1]);
            }
            PhysicalAssessment::firstOrCreate(['student_id' => $student->id, 'assessment_date' => today()], ['teacher_id' => $teacher->id, 'height' => 1.75, 'weight' => 78, 'bmi' => 25.47, 'body_fat' => 18, 'muscle_mass' => 35, 'notes' => 'Avaliação inicial de demonstração.']);
        }
    }
}
