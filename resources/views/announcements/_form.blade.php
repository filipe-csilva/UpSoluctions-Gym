<form method="POST" action="{{ $formAction }}">
    @csrf
    @if ($formMethod !== 'POST') @method($formMethod) @endif
    <div class="row g-3">
        <div class="col-md-8"><label class="form-label">Título *</label><input name="title" class="form-control" value="{{ old('title', $announcement->title) }}" required></div>
        <div class="col-md-4"><label class="form-label">Unidade</label><select name="unit_id" class="form-select"><option value="">Todas as unidades</option>@foreach($units as $unit)<option value="{{ $unit->id }}" @selected(old('unit_id', $announcement->unit_id) == $unit->id)>{{ $unit->name }}</option>@endforeach</select></div>
        <div class="col-md-4"><label class="form-label">Destinatários</label><select name="target_role" class="form-select"><option value="all" @selected(old('target_role', $announcement->target_role) === 'all')>Todos</option>@foreach(['admin'=>'Administradores','manager'=>'Managers','teacher'=>'Instrutores','student'=>'Alunos'] as $value => $label)<option value="{{ $value }}" @selected(old('target_role', $announcement->target_role) === $value)>{{ $label }}</option>@endforeach</select></div>
        <div class="col-md-4"><label class="form-label">Início</label><input type="datetime-local" name="start_at" class="form-control" value="{{ old('start_at', $announcement->start_at?->format('Y-m-d\TH:i')) }}"></div>
        <div class="col-md-4"><label class="form-label">Fim</label><input type="datetime-local" name="end_at" class="form-control" value="{{ old('end_at', $announcement->end_at?->format('Y-m-d\TH:i')) }}"></div>
        <div class="col-12"><label class="form-label">Mensagem *</label><textarea name="message" rows="6" class="form-control" required>{{ old('message', $announcement->message) }}</textarea></div>
        <div class="col-12 form-check ms-2"><input type="hidden" name="active" value="0"><input id="active" type="checkbox" name="active" value="1" class="form-check-input" @checked(old('active', $announcement->active))><label for="active" class="form-check-label">Comunicado ativo</label></div>
    </div>
    <button class="btn btn-success mt-3"><i class="bi bi-check-lg"></i> Salvar</button>
    <a href="{{ route('announcements.index') }}" class="btn btn-secondary mt-3">Cancelar</a>
</form>
