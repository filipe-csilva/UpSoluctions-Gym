<?php

use App\Enums\UserRole;
use App\Models\Message;
use App\Models\MessageRead;
use App\Models\StudentProfile;
use App\Models\Unit;
use App\Models\User;
use App\Models\WorkoutPlan;

function messageUser(string $role, Unit $unit, string $email): User
{
    return User::factory()->create(['role' => $role, 'unit_id' => $unit->id, 'email' => $email]);
}

it('allows a teacher to message only their workout students', function () {
    $unit = Unit::create(['name' => 'Unidade Centro', 'code' => 'CENTRO', 'active' => true]);
    $teacher = messageUser(UserRole::TEACHER->value, $unit, 'instrutor-mensagem@example.com');
    $studentUser = messageUser(UserRole::STUDENT->value, $unit, 'aluno-mensagem@example.com');
    $student = StudentProfile::create(['user_id' => $studentUser->id, 'cpf' => '52998224725', 'birth_date' => '1990-01-01', 'phone' => '11999999999', 'number' => '10']);
    WorkoutPlan::create(['student_id' => $student->id, 'teacher_id' => $teacher->id, 'name' => 'Treino', 'start_date' => today(), 'status' => 'active']);

    $response = $this->actingAs($teacher)->post(route('messages.store'), ['audience' => 'direct', 'recipient_id' => $studentUser->id, 'subject' => 'Treino de hoje', 'body' => 'Não esqueça o aquecimento.']);

    $response->assertRedirect();
    expect(Message::first()->recipient_id)->toBe($studentUser->id);
});

it('allows staff to read a unit message and records who read it', function () {
    $unit = Unit::create(['name' => 'Unidade Centro', 'code' => 'CENTRO', 'active' => true]);
    $admin = messageUser(UserRole::ADMIN->value, $unit, 'admin-mensagem@example.com');
    $financial = messageUser(UserRole::FINANCIAL->value, $unit, 'financeiro-mensagem@example.com');

    $response = $this->actingAs($admin)->post(route('messages.store'), ['audience' => 'unit', 'unit_id' => $unit->id, 'subject' => 'Atendimento', 'body' => 'Aluno aguardando atendimento.']);
    $message = Message::first();

    $this->actingAs($financial)->get(route('messages.show', $message))->assertOk();

    expect($response->isRedirect())->toBeTrue()
        ->and(MessageRead::query()->where('message_id', $message->id)->where('user_id', $financial->id)->exists())->toBeTrue();
});

it('does not allow a student to send a message to their unit', function () {
    $unit = Unit::create(['name' => 'Unidade Centro', 'code' => 'CENTRO', 'active' => true]);
    $student = messageUser(UserRole::STUDENT->value, $unit, 'aluno-unidade@example.com');
    StudentProfile::create(['user_id' => $student->id, 'cpf' => '52998224725', 'birth_date' => '1990-01-01', 'phone' => '11999999999', 'number' => '10']);

    $response = $this->actingAs($student)->post(route('messages.store'), ['audience' => 'unit', 'subject' => 'Dúvida na recepção', 'body' => 'Preciso de ajuda com meu cadastro.']);

    $response->assertForbidden();
    expect(Message::query()->count())->toBe(0);
});

it('allows all students in the unit to read a unit message', function () {
    $unit = Unit::create(['name' => 'Unidade Centro', 'code' => 'CENTRO', 'active' => true]);
    $student = messageUser(UserRole::STUDENT->value, $unit, 'aluno-unidade-1@example.com');
    $otherStudent = messageUser(UserRole::STUDENT->value, $unit, 'aluno-unidade-2@example.com');
    $admin = messageUser(UserRole::ADMIN->value, $unit, 'admin-unidade@example.com');
    $message = Message::create([
        'sender_id' => $admin->id,
        'unit_id' => $unit->id,
        'audience' => 'unit',
        'subject' => 'Aviso para os alunos',
        'body' => 'A unidade estará fechada no feriado.',
    ]);

    expect(Message::query()->visibleTo($student)->whereKey($message->id)->exists())->toBeTrue()
        ->and(Message::query()->visibleTo($otherStudent)->whereKey($message->id)->exists())->toBeTrue();
});

it('does not allow students or teachers to send a unit message', function () {
    $unit = Unit::create(['name' => 'Unidade Centro', 'code' => 'CENTRO', 'active' => true]);
    $student = messageUser(UserRole::STUDENT->value, $unit, 'aluno-sem-envio@example.com');
    $teacher = messageUser(UserRole::TEACHER->value, $unit, 'instrutor-sem-envio@example.com');

    $this->actingAs($student)
        ->post(route('messages.store'), ['audience' => 'unit', 'unit_id' => $unit->id, 'subject' => 'Aviso', 'body' => 'Não permitido.'])
        ->assertForbidden();

    $this->actingAs($teacher)
        ->post(route('messages.store'), ['audience' => 'unit', 'unit_id' => $unit->id, 'subject' => 'Aviso', 'body' => 'Não permitido.'])
        ->assertForbidden();
});

it('allows admin and manager to send a message to a specific student in scope', function () {
    $unit = Unit::create(['name' => 'Unidade Centro', 'code' => 'CENTRO', 'active' => true]);
    $admin = messageUser(UserRole::ADMIN->value, $unit, 'admin-aluno-especifico@example.com');
    $student = messageUser(UserRole::STUDENT->value, $unit, 'aluno-especifico@example.com');
    StudentProfile::create(['user_id' => $student->id, 'cpf' => '52998224725', 'birth_date' => '1990-01-01', 'phone' => '11999999999', 'number' => '10']);

    $this->actingAs($admin)
        ->post(route('messages.store'), ['audience' => 'direct', 'recipient_id' => $student->id, 'subject' => 'Aviso individual', 'body' => 'Sua mensalidade vence em cinco dias.'])
        ->assertRedirect();

    expect(Message::first()->recipient_id)->toBe($student->id);
});

it('allows a student to contact the reception and limits visibility to the unit staff', function () {
    $unit = Unit::create(['name' => 'Unidade Centro', 'code' => 'CENTRO', 'active' => true]);
    $student = messageUser(UserRole::STUDENT->value, $unit, 'aluno-recepcao@example.com');
    $otherStudent = messageUser(UserRole::STUDENT->value, $unit, 'outro-aluno-recepcao@example.com');
    $financial = messageUser(UserRole::FINANCIAL->value, $unit, 'financeiro-recepcao@example.com');
    StudentProfile::create(['user_id' => $student->id, 'cpf' => '52998224725', 'birth_date' => '1990-01-01', 'phone' => '11999999999', 'number' => '10']);

    $response = $this->actingAs($student)->post(route('messages.store'), [
        'audience' => 'reception',
        'subject' => 'Dúvida sobre atendimento',
        'body' => 'Preciso confirmar meu horário.',
    ]);
    $message = Message::first();

    $response->assertRedirect();
    expect(Message::query()->visibleTo($financial)->whereKey($message->id)->exists())->toBeTrue()
        ->and(Message::query()->visibleTo($otherStudent)->whereKey($message->id)->exists())->toBeFalse();
});

it('limits manager visibility to responsible units', function () {
    $managedUnit = Unit::create(['name' => 'Unidade Centro', 'code' => 'CENTRO', 'active' => true]);
    $otherUnit = Unit::create(['name' => 'Unidade Norte', 'code' => 'NORTE', 'active' => true]);
    $manager = messageUser(UserRole::MANAGER->value, $managedUnit, 'manager-unidades@example.com');
    $admin = messageUser(UserRole::ADMIN->value, $managedUnit, 'admin-unidades@example.com');
    $manager->managedUnits()->attach($managedUnit);
    $managedMessage = Message::create(['sender_id' => $admin->id, 'unit_id' => $managedUnit->id, 'audience' => 'reception', 'subject' => 'Centro', 'body' => 'Mensagem da unidade Centro.']);
    $otherMessage = Message::create(['sender_id' => $admin->id, 'unit_id' => $otherUnit->id, 'audience' => 'reception', 'subject' => 'Norte', 'body' => 'Mensagem da unidade Norte.']);

    expect(Message::query()->visibleTo($manager)->whereKey($managedMessage->id)->exists())->toBeTrue()
        ->and(Message::query()->visibleTo($manager)->whereKey($otherMessage->id)->exists())->toBeFalse();
});

it('shows the specific student option to admin and manager', function () {
    $unit = Unit::create(['name' => 'Unidade Centro', 'code' => 'CENTRO', 'active' => true]);
    $admin = messageUser(UserRole::ADMIN->value, $unit, 'admin-formulario@example.com');

    $this->actingAs($admin)
        ->get(route('messages.create'))
        ->assertOk()
        ->assertSee('Alunos da unidade')
        ->assertSee('Um aluno específico');
});

it('allows admin to send a direct message to a teacher or manager', function () {
    $unit = Unit::create(['name' => 'Unidade Centro', 'code' => 'CENTRO', 'active' => true]);
    $admin = messageUser(UserRole::ADMIN->value, $unit, 'admin-equipe@example.com');
    $teacher = messageUser(UserRole::TEACHER->value, $unit, 'instrutor-equipe@example.com');
    $manager = messageUser(UserRole::MANAGER->value, $unit, 'manager-equipe@example.com');
    $financial = messageUser(UserRole::FINANCIAL->value, $unit, 'financeiro-equipe@example.com');

    $this->actingAs($admin)
        ->post(route('messages.store'), ['audience' => 'direct', 'recipient_id' => $teacher->id, 'subject' => 'Orientação', 'body' => 'Verifique a agenda da unidade.'])
        ->assertRedirect();

    $this->actingAs($admin)
        ->post(route('messages.store'), ['audience' => 'direct', 'recipient_id' => $manager->id, 'subject' => 'Orientação', 'body' => 'Verifique o atendimento da unidade.'])
        ->assertRedirect();

    $this->actingAs($admin)
        ->post(route('messages.store'), ['audience' => 'direct', 'recipient_id' => $financial->id, 'subject' => 'Orientação', 'body' => 'Verifique os lançamentos financeiros.'])
        ->assertRedirect();

    expect(Message::query()->whereIn('recipient_id', [$teacher->id, $manager->id, $financial->id])->count())->toBe(3);
});

it('allows manager to send a direct message to admin, teacher, and financial staff', function () {
    $unit = Unit::create(['name' => 'Unidade Centro', 'code' => 'CENTRO', 'active' => true]);
    $manager = messageUser(UserRole::MANAGER->value, $unit, 'manager-equipe-2@example.com');
    $admin = messageUser(UserRole::ADMIN->value, $unit, 'admin-equipe-2@example.com');
    $teacher = messageUser(UserRole::TEACHER->value, $unit, 'instrutor-equipe-2@example.com');
    $financial = messageUser(UserRole::FINANCIAL->value, $unit, 'financeiro-equipe-2@example.com');

    foreach ([$admin, $teacher, $financial] as $recipient) {
        $this->actingAs($manager)
            ->post(route('messages.store'), ['audience' => 'direct', 'recipient_id' => $recipient->id, 'subject' => 'Alinhamento', 'body' => 'Mensagem para a equipe.'])
            ->assertRedirect();
    }

    expect(Message::query()->where('sender_id', $manager->id)->count())->toBe(3);
});

it('allows the recipient to reply to a direct message', function () {
    $unit = Unit::create(['name' => 'Unidade Centro', 'code' => 'CENTRO', 'active' => true]);
    $teacher = messageUser(UserRole::TEACHER->value, $unit, 'instrutor-resposta@example.com');
    $studentUser = messageUser(UserRole::STUDENT->value, $unit, 'aluno-resposta@example.com');
    $student = StudentProfile::create(['user_id' => $studentUser->id, 'cpf' => '52998224725', 'birth_date' => '1990-01-01', 'phone' => '11999999999', 'number' => '10']);
    WorkoutPlan::create(['student_id' => $student->id, 'teacher_id' => $teacher->id, 'name' => 'Treino', 'start_date' => today(), 'status' => 'active']);

    $message = Message::create(['sender_id' => $teacher->id, 'recipient_id' => $studentUser->id, 'audience' => 'direct', 'subject' => 'Treino de hoje', 'body' => 'Faça o aquecimento.']);
    $response = $this->actingAs($studentUser)->post(route('messages.reply', $message), ['body' => 'Tudo bem, vou fazer.']);

    $response->assertRedirect(route('messages.show', $message, absolute: false));
    expect($message->fresh()->replies()->first()->sender_id)->toBe($studentUser->id);
});

it('does not mark a message as read when its sender opens it', function () {
    $unit = Unit::create(['name' => 'Unidade Centro', 'code' => 'CENTRO', 'active' => true]);
    $admin = messageUser(UserRole::ADMIN->value, $unit, 'admin-leitura@example.com');
    $message = Message::create(['sender_id' => $admin->id, 'audience' => 'all', 'subject' => 'Aviso', 'body' => 'Mensagem enviada.']);

    $this->actingAs($admin)->get(route('messages.show', $message))->assertOk();

    expect($message->fresh()->read_at)->toBeNull()
        ->and($message->fresh()->read_by)->toBeNull();
});

it('identifies and marks an unread reply when the conversation is opened', function () {
    $unit = Unit::create(['name' => 'Unidade Centro', 'code' => 'CENTRO', 'active' => true]);
    $teacher = messageUser(UserRole::TEACHER->value, $unit, 'instrutor-notificacao@example.com');
    $student = messageUser(UserRole::STUDENT->value, $unit, 'aluno-notificacao@example.com');
    $message = Message::create([
        'sender_id' => $teacher->id,
        'recipient_id' => $student->id,
        'audience' => 'direct',
        'subject' => 'Dúvida',
        'body' => 'Como está o treino?',
    ]);
    $reply = Message::create([
        'parent_id' => $message->id,
        'sender_id' => $student->id,
        'recipient_id' => $teacher->id,
        'audience' => 'direct',
        'subject' => 'Re: Dúvida',
        'body' => 'Está tudo certo.',
    ]);

    $this->actingAs($teacher)
        ->get(route('messages.show', $message))
        ->assertSee('Está tudo certo.');

    expect(MessageRead::query()->where('message_id', $reply->id)->where('user_id', $teacher->id)->exists())->toBeTrue();
});

it('shows a unit message reply as unread for the student who sent it', function () {
    $unit = Unit::create(['name' => 'Unidade Centro', 'code' => 'CENTRO', 'active' => true]);
    $student = messageUser(UserRole::STUDENT->value, $unit, 'aluno-resposta-unidade@example.com');
    $admin = messageUser(UserRole::ADMIN->value, $unit, 'admin-resposta-unidade@example.com');
    $message = Message::create([
        'sender_id' => $student->id,
        'unit_id' => $unit->id,
        'audience' => 'unit',
        'subject' => 'Dúvida sobre mensalidade',
        'body' => 'Preciso de ajuda.',
    ]);
    $reply = Message::create([
        'parent_id' => $message->id,
        'sender_id' => $admin->id,
        'unit_id' => $unit->id,
        'audience' => 'unit',
        'subject' => 'Re: Dúvida sobre mensalidade',
        'body' => 'Vamos verificar para você.',
    ]);

    $this->actingAs($student)
        ->get(route('messages.index'))
        ->assertSee('Dúvida sobre mensalidade')
        ->assertSee('1 mensagem(ns) não lida(s)');

    expect($message->fresh()->read_at)->toBeNull()
        ->and($reply->fresh()->read_at)->toBeNull();
});

it('records a unit message read separately for each student', function () {
    $unit = Unit::create(['name' => 'Unidade Centro', 'code' => 'CENTRO', 'active' => true]);
    $firstStudent = messageUser(UserRole::STUDENT->value, $unit, 'aluno-leitura-1@example.com');
    $secondStudent = messageUser(UserRole::STUDENT->value, $unit, 'aluno-leitura-2@example.com');
    $admin = messageUser(UserRole::ADMIN->value, $unit, 'admin-leitura-unidade@example.com');
    $message = Message::create(['sender_id' => $admin->id, 'unit_id' => $unit->id, 'audience' => 'unit', 'subject' => 'Aviso da unidade', 'body' => 'Mensagem para todos os alunos.']);

    $this->actingAs($firstStudent)->get(route('messages.show', $message))->assertOk();

    expect(MessageRead::query()->where('message_id', $message->id)->where('user_id', $firstStudent->id)->exists())->toBeTrue()
        ->and(MessageRead::query()->where('message_id', $message->id)->where('user_id', $secondStudent->id)->exists())->toBeFalse();
});

it('assigns a reception message to the first staff member who opens it', function () {
    $unit = Unit::create(['name' => 'Unidade Centro', 'code' => 'CENTRO', 'active' => true]);
    $student = messageUser(UserRole::STUDENT->value, $unit, 'aluno-responsavel@example.com');
    $firstAdmin = messageUser(UserRole::ADMIN->value, $unit, 'admin-responsavel-1@example.com');
    $secondAdmin = messageUser(UserRole::ADMIN->value, $unit, 'admin-responsavel-2@example.com');
    $message = Message::create([
        'sender_id' => $student->id,
        'unit_id' => $unit->id,
        'audience' => 'reception',
        'subject' => 'Atendimento da recepção',
        'body' => 'Preciso de ajuda.',
    ]);

    $this->actingAs($firstAdmin)->get(route('messages.show', $message))->assertOk();

    expect((int) $message->fresh()->assigned_to)->toBe($firstAdmin->id)
        ->and(Message::query()->visibleTo($firstAdmin)->whereKey($message->id)->exists())->toBeTrue()
        ->and(Message::query()->visibleTo($secondAdmin)->whereKey($message->id)->exists())->toBeFalse()
        ->and(Message::query()->visibleTo($student)->whereKey($message->id)->exists())->toBeTrue();
});

it('does not show a student messages created before the student account', function () {
    $unit = Unit::create(['name' => 'Unidade Centro', 'code' => 'CENTRO', 'active' => true]);
    $admin = messageUser(UserRole::ADMIN->value, $unit, 'admin-data-cadastro@example.com');
    $student = messageUser(UserRole::STUDENT->value, $unit, 'aluno-data-cadastro@example.com');
    $message = Message::create(['sender_id' => $admin->id, 'audience' => 'all', 'subject' => 'Aviso antigo', 'body' => 'Não deve aparecer.']);
    $student->forceFill(['created_at' => now()->addMinute()])->save();

    expect(Message::query()->visibleTo($student->fresh())->whereKey($message->id)->exists())->toBeFalse();
});
