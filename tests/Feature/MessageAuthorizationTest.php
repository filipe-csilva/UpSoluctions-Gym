<?php

use App\Enums\UserRole;
use App\Models\Message;
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
        ->and($message->fresh()->read_by)->toBe($financial->id)
        ->and($message->fresh()->read_at)->not->toBeNull();
});

it('allows a student to send a message to their unit', function () {
    $unit = Unit::create(['name' => 'Unidade Centro', 'code' => 'CENTRO', 'active' => true]);
    $student = messageUser(UserRole::STUDENT->value, $unit, 'aluno-unidade@example.com');
    StudentProfile::create(['user_id' => $student->id, 'cpf' => '52998224725', 'birth_date' => '1990-01-01', 'phone' => '11999999999', 'number' => '10']);

    $response = $this->actingAs($student)->post(route('messages.store'), ['audience' => 'unit', 'subject' => 'Dúvida na recepção', 'body' => 'Preciso de ajuda com meu cadastro.']);

    $response->assertRedirect();
    expect(Message::first()->unit_id)->toBe($unit->id)
        ->and(Message::first()->recipient_id)->toBeNull();
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

    expect($reply->fresh()->read_by)->toBe($teacher->id)
        ->and($reply->fresh()->read_at)->not->toBeNull();
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
