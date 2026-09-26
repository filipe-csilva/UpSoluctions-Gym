<form method="POST" action="{{ $formAction }}">
    @csrf
    @if ($formMethod !== 'POST') @method($formMethod) @endif
    <div class="row g-3">
        @if ($formMethod === 'POST')
            <div class="col-md-6"><label class="form-label">Aluno *</label><select name="student_id" class="form-select" required><option value="">Selecione</option>@foreach($students as $student)<option value="{{ $student->id }}" @selected(old('student_id') == $student->id)>{{ $student->user->name }}{{ $student->user->active ? '' : ' (inativo)' }}</option>@endforeach</select></div>
            <div class="col-md-6"><label class="form-label">Unidade *</label><select name="unit_id" class="form-select" required>@foreach($units as $unit)<option value="{{ $unit->id }}">{{ $unit->name }}</option>@endforeach</select></div>
        @endif
        <div class="col-md-6"><label class="form-label">Plano *</label><select id="plan_id" name="plan_id" class="form-select" required>@foreach($plans as $plan)<option value="{{ $plan->id }}" data-price="{{ number_format($plan->promotionalPrice(), 2, '.', '') }}" data-duration-months="{{ $plan->duration_months }}" data-installments="{{ $plan->installments ?? 1 }}" @selected(old('plan_id', $enrollment->plan_id ?? '') == $plan->id)>{{ $plan->name }} - R$ {{ number_format($plan->promotionalPrice(), 2, ',', '.') }}{{ $plan->isPromotionActive() ? ' (promoção)' : '' }}</option>@endforeach</select></div>
        <div class="col-md-3"><label class="form-label">Início *</label><input id="start_date" type="date" name="start_date" class="form-control" value="{{ old('start_date', isset($enrollment) ? $enrollment->start_date->format('Y-m-d') : today()->format('Y-m-d')) }}" readonly required></div>
        <div class="col-md-3"><label class="form-label">Fim *</label><input id="end_date" type="date" name="end_date" class="form-control" value="{{ old('end_date', isset($enrollment) ? $enrollment->end_date->format('Y-m-d') : today()->addMonth()->subDay()->format('Y-m-d')) }}" readonly required><small class="text-muted">Calculado pela duração do plano.</small></div>
        <div class="col-md-4"><label class="form-label">Valor total aplicado *</label><input id="price" type="number" step="0.01" name="price" class="form-control" value="{{ old('price', $enrollment->price ?? '') }}" readonly required><small class="text-muted">Valor e parcelas calculados pelo plano.</small></div>
        <div class="col-md-4"><label class="form-label">Dia de pagamento *</label><input type="number" min="1" max="31" name="payment_day" class="form-control" value="{{ old('payment_day', $enrollment->payment_day ?? 10) }}" required></div>
        <div class="col-md-4"><label class="form-label">Status *</label><input class="form-control" value="Ativa" readonly><input type="hidden" name="status" value="{{ old('status', $enrollment->status ?? 'active') }}"></div>
        <div class="col-12"><label class="form-label">Observações</label><textarea name="notes" class="form-control">{{ old('notes', $enrollment->notes ?? '') }}</textarea></div>
    </div>
    <button class="btn btn-success mt-3">Salvar</button> <a href="{{ route('enrollments.index') }}" class="btn btn-secondary mt-3">Cancelar</a>
</form>
@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const plan = document.getElementById('plan_id');
            const price = document.getElementById('price');
            const startDate = document.getElementById('start_date');
            const endDate = document.getElementById('end_date');
            const updateFields = function () {
                const selected = plan?.options[plan.selectedIndex];
                const duration = Number(selected?.dataset.durationMonths || 1);
                if (price && selected?.dataset.price) price.value = selected.dataset.price;
                if (!startDate?.value || !endDate) return;
                const [year, month, day] = startDate.value.split('-').map(Number);
                const date = new Date(Date.UTC(year, month - 1, day));
                date.setUTCMonth(date.getUTCMonth() + duration);
                date.setUTCDate(date.getUTCDate() - 1);
                endDate.value = date.toISOString().slice(0, 10);
            };
            plan?.addEventListener('change', updateFields);
            updateFields();
        });
    </script>
@endpush
