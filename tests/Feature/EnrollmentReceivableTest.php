<?php

use App\Enums\UserRole;
use App\Models\FinancialTransaction;
use App\Models\Plan;
use App\Models\StudentProfile;
use App\Models\Unit;
use App\Models\User;

it('creates an accounts receivable transaction when an enrollment is created', function () {
    $unit = Unit::create(['name' => 'Unidade Centro', 'code' => 'CENTRO', 'active' => true]);
    $studentUser = User::factory()->create(['role' => UserRole::STUDENT, 'unit_id' => $unit->id]);
    $student = StudentProfile::create([
        'user_id' => $studentUser->id,
        'cpf' => '52998224725',
        'birth_date' => '1990-01-01',
        'phone' => '11999999999',
        'number' => '10',
    ]);
    $plan = Plan::create([
        'name' => 'Plano Mensal',
        'duration_months' => 1,
        'price' => 89.90,
        'promotion_type' => 'percentage',
        'promotion_value' => 10,
        'promotion_start_date' => '2026-09-01',
        'promotion_end_date' => '2026-09-30',
        'active' => true,
    ]);
    $admin = User::factory()->create(['role' => UserRole::ADMIN]);

    $response = $this->actingAs($admin)->post(route('enrollments.store'), [
        'student_id' => $student->id,
        'plan_id' => $plan->id,
        'unit_id' => $unit->id,
        'start_date' => '2026-09-26',
        'end_date' => '2026-09-27',
        'price' => '89.90',
        'status' => 'active',
        'payment_day' => 10,
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('enrollments', [
        'student_id' => $student->id,
        'plan_id' => $plan->id,
        'start_date' => '2026-09-26 00:00:00',
        'end_date' => '2026-10-25 00:00:00',
    ]);
    $this->assertDatabaseHas('financial_transactions', [
        'student_id' => $student->id,
        'unit_id' => $unit->id,
        'description' => 'Mensalidade - Plano Mensal',
        'amount' => 80.91,
        'due_date' => '2026-09-26 00:00:00',
        'status' => 'pending',
        'transaction_type' => 'income',
    ]);

    $transaction = FinancialTransaction::query()->where('student_id', $student->id)->firstOrFail();
    $this->actingAs($admin)
        ->post(route('financial.mark-paid', $transaction), ['payment_method' => 'pix'])
        ->assertRedirect(route('financial.show', $transaction));
    $this->assertDatabaseHas('financial_transactions', ['id' => $transaction->id, 'status' => 'paid', 'payment_method' => 'pix']);
    $this->actingAs($admin)->get(route('financial.cash-flow'))->assertOk()->assertSee('Entradas recebidas')->assertSee('80,91');
    $this->actingAs($admin)->get(route('reports.index', ['type' => 'cash_flow', 'from' => '2026-09-26', 'to' => '2026-09-26']))->assertOk()->assertSee('Fluxo de caixa')->assertSee('Mensalidade - Plano Mensal');
    expect($admin->fresh()->role?->value)->toBe('admin');
    $this->actingAs($admin->fresh())->post(route('financial.reverse', $transaction), ['reason' => 'Pagamento devolvido'])->assertRedirect(route('financial.show', $transaction));
    $this->assertDatabaseHas('financial_transactions', ['id' => $transaction->id, 'status' => 'pending', 'paid_at' => null]);
    $this->actingAs($admin)->get(route('financial.receive', $transaction))->assertOk()->assertSee('Forma de pagamento');
});

it('creates one receivable per installment for an installment plan', function () {
    $unit = Unit::create(['name' => 'Unidade Norte', 'code' => 'NORTE', 'active' => true]);
    $studentUser = User::factory()->create(['role' => UserRole::STUDENT, 'unit_id' => $unit->id]);
    $student = StudentProfile::create([
        'user_id' => $studentUser->id,
        'cpf' => '39053344705',
        'birth_date' => '1990-01-01',
        'phone' => '11999999999',
        'number' => '11',
    ]);
    $plan = Plan::create(['name' => 'Plano Trimestral', 'duration_months' => 3, 'installments' => 3, 'price' => 239.90, 'active' => true]);
    $admin = User::factory()->create(['role' => UserRole::ADMIN]);

    $response = $this->actingAs($admin)->post(route('enrollments.store'), [
        'student_id' => $student->id,
        'plan_id' => $plan->id,
        'unit_id' => $unit->id,
        'start_date' => '2026-09-26',
        'end_date' => '2026-12-25',
        'price' => '239.90',
        'status' => 'active',
        'payment_day' => 10,
    ]);

    $response->assertRedirect();
    expect($student->fresh()->user->active)->toBeTrue();
    expect($plan->enrollments()->first()->financialTransactions)->toHaveCount(3);
    $this->assertDatabaseHas('financial_transactions', ['description' => 'Mensalidade - Plano Trimestral - Parcela 1/3', 'amount' => 79.97, 'due_date' => '2026-09-26 00:00:00']);
    $this->assertDatabaseHas('financial_transactions', ['description' => 'Mensalidade - Plano Trimestral - Parcela 3/3', 'amount' => 79.96, 'due_date' => '2026-11-26 00:00:00']);
});
