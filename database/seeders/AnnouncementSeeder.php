<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Seeder;

class AnnouncementSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::query()->where('email', 'admin@upsoluctions.com.br')->firstOrFail();
        $unit = Unit::query()->where('active', true)->orderBy('id')->first();

        $announcements = [
            [
                'title' => 'Bem-vindos à nova semana de treinos',
                'message' => 'Confira sua ficha de treino e mantenha a rotina de exercícios em dia.',
                'target_role' => 'all',
                'unit_id' => null,
                'is_default' => true,
            ],
            [
                'title' => 'Manutenção programada da unidade',
                'message' => 'A unidade terá manutenção preventiva em horário programado. Consulte a recepção para mais informações.',
                'target_role' => 'all',
                'unit_id' => $unit?->id,
                'is_default' => false,
            ],
            [
                'title' => 'Avaliações físicas disponíveis',
                'message' => 'As avaliações físicas estão disponíveis para agendamento com a equipe de instrutores.',
                'target_role' => 'student',
                'unit_id' => null,
                'is_default' => false,
            ],
        ];

        foreach ($announcements as $announcement) {
            Announcement::updateOrCreate(
                ['title' => $announcement['title'], 'created_by' => $admin->id],
                $announcement + [
                    'created_by' => $admin->id,
                    'active' => true,
                    'start_at' => now()->subDay(),
                    'end_at' => now()->addMonths(3),
                ],
            );
        }
    }
}
