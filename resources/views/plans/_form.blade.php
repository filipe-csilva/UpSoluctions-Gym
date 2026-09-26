<form method="POST" action="{{ $formAction }}">
    @csrf
    @if ($formMethod !== 'POST') @method($formMethod) @endif
    <div class="row g-3">
        <div class="col-md-8"><label for="name" class="form-label">Nome *</label><input id="name" name="name" class="form-control" value="{{ old('name', $plan->name) }}" required></div>
        <div class="col-md-4"><label for="active" class="form-label">Status *</label><select id="active" name="active" class="form-select"><option value="1" @selected(old('active', $plan->active))>Ativo</option><option value="0" @selected(! old('active', $plan->active))>Inativo</option></select></div>
        <div class="col-md-4"><label for="duration_months" class="form-label">Duração em meses *</label><input id="duration_months" name="duration_months" type="number" min="1" max="120" class="form-control" value="{{ old('duration_months', $plan->duration_months) }}" required></div>
        <div class="col-md-4"><label for="installments" class="form-label">Parcelas *</label><input id="installments" name="installments" type="number" min="1" max="120" class="form-control" value="{{ old('installments', $plan->installments ?? 1) }}" required><small class="text-muted">Use 1 para cobrança única.</small></div>
        <div class="col-md-4"><label for="price" class="form-label">Valor total *</label><input id="price" name="price" type="number" min="0" step="0.01" class="form-control" value="{{ old('price', $plan->price) }}" required></div>
        <div class="col-md-4"><label for="promotion_type" class="form-label">Tipo de promoção</label><select id="promotion_type" name="promotion_type" class="form-select"><option value="">Sem promoção</option><option value="percentage" @selected(old('promotion_type', $plan->promotion_type) === 'percentage')>Percentual (%)</option><option value="fixed" @selected(old('promotion_type', $plan->promotion_type) === 'fixed')>Valor fixo (R$)</option></select></div>
        <div class="col-md-4"><label for="promotion_value" class="form-label">Desconto</label><input id="promotion_value" name="promotion_value" type="number" min="0" step="0.01" class="form-control" value="{{ old('promotion_value', $plan->promotion_value) }}"><small class="text-muted">Para percentual, use de 0 a 100.</small></div>
        <div class="col-md-4"><label for="promotion_start_date" class="form-label">Início da promoção</label><input id="promotion_start_date" name="promotion_start_date" type="date" class="form-control" value="{{ old('promotion_start_date', $plan->promotion_start_date?->format('Y-m-d')) }}"></div>
        <div class="col-md-4"><label for="promotion_end_date" class="form-label">Fim da promoção</label><input id="promotion_end_date" name="promotion_end_date" type="date" class="form-control" value="{{ old('promotion_end_date', $plan->promotion_end_date?->format('Y-m-d')) }}"></div>
        <div class="col-12"><label for="description" class="form-label">Descrição</label><textarea id="description" name="description" rows="4" class="form-control">{{ old('description', $plan->description) }}</textarea></div>
    </div>
    <div class="mt-3"><button class="btn btn-success"><i class="bi bi-check-lg"></i> Salvar</button> <a href="{{ route('plans.index') }}" class="btn btn-secondary">Cancelar</a></div>
</form>
