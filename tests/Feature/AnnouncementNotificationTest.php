<?php

use App\Enums\UserRole;
use App\Models\Announcement;
use App\Models\AnnouncementRead;
use App\Models\User;
use App\Notifications\AnnouncementPublished;
use Illuminate\Support\Facades\Notification;

it('creates an internal notification for the announcement audience', function () {
    Notification::fake();

    $admin = User::factory()->create(['role' => UserRole::ADMIN]);
    $student = User::factory()->create(['role' => UserRole::STUDENT]);

    $response = $this->actingAs($admin)->post(route('announcements.store'), [
        'title' => 'Aviso importante',
        'message' => 'Mensagem de teste.',
        'target_role' => UserRole::STUDENT->value,
        'active' => '1',
    ]);

    $response->assertRedirect();
    Notification::assertSentTo($student, AnnouncementPublished::class);
});

it('allows students to read only active announcements intended for them', function () {
    $admin = User::factory()->create(['role' => UserRole::ADMIN]);
    $student = User::factory()->create(['role' => UserRole::STUDENT]);

    $visibleResponse = $this->actingAs($admin)->post(route('announcements.store'), [
        'title' => 'Aviso para alunos',
        'message' => 'Mensagem disponível para os alunos.',
        'target_role' => UserRole::STUDENT->value,
        'active' => '1',
    ]);
    $visibleAnnouncement = Announcement::query()->latest('id')->firstOrFail();

    $hiddenResponse = $this->actingAs($admin)->post(route('announcements.store'), [
        'title' => 'Aviso inativo',
        'message' => 'Mensagem indisponível.',
        'target_role' => UserRole::STUDENT->value,
        'active' => '0',
    ]);
    $hiddenAnnouncement = Announcement::query()->latest('id')->firstOrFail();

    expect($visibleResponse->isRedirect())->toBeTrue()
        ->and($hiddenResponse->isRedirect())->toBeTrue();

    $this->actingAs($student)
        ->get(route('announcements.index'))
        ->assertSee('Aviso para alunos')
        ->assertDontSee('Aviso inativo');

    $this->actingAs($student)
        ->get(route('announcements.show', $visibleAnnouncement))
        ->assertSee('Mensagem disponível para os alunos.');

    $this->actingAs($student)
        ->get(route('announcements.show', $hiddenAnnouncement))
        ->assertNotFound();

    $this->actingAs($student)
        ->get(route('announcements.create'))
        ->assertNotFound();
});

it('marks a notification as read when the user verifies it', function () {
    $student = User::factory()->create(['role' => UserRole::STUDENT]);
    $announcement = Announcement::create([
        'created_by' => $student->id,
        'title' => 'Aviso de teste',
        'message' => 'Mensagem de teste.',
        'target_role' => UserRole::STUDENT->value,
        'active' => true,
    ]);
    $student->notify(new AnnouncementPublished($announcement));
    $notification = $student->unreadNotifications()->firstOrFail();

    $this->actingAs($student)
        ->get(route('notifications.read', $notification->id))
        ->assertRedirect(route('panel', absolute: false));

    expect($notification->fresh()->read_at)->not->toBeNull();
});

it('records announcement reading independently for each student', function () {
    $admin = User::factory()->create(['role' => UserRole::ADMIN]);
    $firstStudent = User::factory()->create(['role' => UserRole::STUDENT]);
    $secondStudent = User::factory()->create(['role' => UserRole::STUDENT]);
    $announcement = Announcement::create([
        'created_by' => $admin->id,
        'title' => 'Comunicado para alunos',
        'message' => 'Mensagem para todos os alunos.',
        'target_role' => UserRole::STUDENT->value,
        'active' => true,
    ]);

    $this->actingAs($firstStudent)->get(route('announcements.show', $announcement))->assertOk();

    expect(AnnouncementRead::query()->where('announcement_id', $announcement->id)->where('user_id', $firstStudent->id)->exists())->toBeTrue()
        ->and(AnnouncementRead::query()->where('announcement_id', $announcement->id)->where('user_id', $secondStudent->id)->exists())->toBeFalse();
});

it('keeps the default announcement visible to students created later', function () {
    $admin = User::factory()->create(['role' => UserRole::ADMIN]);
    $oldAnnouncement = Announcement::create([
        'created_by' => $admin->id,
        'title' => 'Comunicado antigo',
        'message' => 'Não deve aparecer.',
        'target_role' => UserRole::STUDENT->value,
        'active' => true,
        'is_default' => false,
    ]);
    $defaultAnnouncement = Announcement::create([
        'created_by' => $admin->id,
        'title' => 'Comunicado inicial padrão',
        'message' => 'Este comunicado permanece disponível.',
        'target_role' => UserRole::STUDENT->value,
        'active' => true,
        'is_default' => true,
    ]);
    $student = User::factory()->create(['role' => UserRole::STUDENT]);
    $student->forceFill(['created_at' => now()->addMinute()])->save();

    $this->actingAs($student)
        ->get(route('announcements.index'))
        ->assertSee('Comunicado inicial padrão')
        ->assertDontSee('Comunicado antigo');

    expect($oldAnnouncement->id)->not->toBe($defaultAnnouncement->id);
});
