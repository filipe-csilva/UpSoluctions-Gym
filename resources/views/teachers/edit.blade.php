@extends('adminlte::page')

@section('title', 'Editar instrutor')
@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Editar instrutor</h1>
        <a href="{{ route('teachers.show', $teacher) }}" class="btn btn-secondary">Voltar</a>
    </div>
@stop

@section('content')
    <x-alerts />
    <form method="POST" action="{{ route('teachers.update', $teacher) }}" data-viacep>
        @csrf
        @method('PUT')
        <div class="card card-primary mb-3"><div class="card-header"><h3 class="card-title">Dados do instrutor</h3></div><div class="card-body"><div class="row g-3">
            <div class="col-md-6"><label class="form-label">Nome *</label><input name="name" class="form-control" value="{{ old('name', $teacher->user->name) }}" required></div>
            <div class="col-md-6"><label class="form-label">E-mail *</label><input type="email" name="email" class="form-control" value="{{ old('email', $teacher->user->email) }}" required></div>
            <div class="col-md-6"><label class="form-label">Unidade *</label><select name="unit_id" class="form-control" required>@foreach ($units as $unit)<option value="{{ $unit->id }}" @selected(old('unit_id', $teacher->user->unit_id) == $unit->id)>{{ $unit->name }}</option>@endforeach</select></div>
            <div class="col-md-6"><label class="form-label">Status *</label><select name="active" class="form-control"><option value="1" @selected(old('active', $teacher->user->active))>Ativo</option><option value="0" @selected(! old('active', $teacher->user->active))>Inativo</option></select></div>
            <div class="col-md-4"><label class="form-label">CPF *</label><input name="cpf" class="form-control" value="{{ old('cpf', $teacher->cpf) }}" required></div>
            <div class="col-md-4"><label class="form-label">Nascimento *</label><input type="date" name="birth_date" class="form-control" value="{{ old('birth_date', $teacher->birth_date?->format('Y-m-d')) }}" required></div>
            <div class="col-md-4"><label class="form-label">Telefone *</label><input name="phone" class="form-control" value="{{ old('phone', $teacher->phone) }}" required></div>
            <div class="col-md-4"><label class="form-label">Gênero</label><select name="gender" class="form-control"><option value="">Não informado</option><option value="Masculino" @selected(old('gender', $teacher->gender) === 'Masculino')>Masculino</option><option value="Feminino" @selected(old('gender', $teacher->gender) === 'Feminino')>Feminino</option><option value="Outro" @selected(old('gender', $teacher->gender) === 'Outro')>Outro</option></select></div>
        </div></div></div>
        <div class="card card-secondary mb-3"><div class="card-header"><h3 class="card-title">Endereço</h3></div><div class="card-body"><div class="row g-3">
            <div class="col-md-3"><label class="form-label">CEP</label><input name="zip_code" class="form-control" value="{{ old('zip_code', $teacher->zip_code) }}"></div>
            <div class="col-md-7"><label class="form-label">Endereço</label><input name="address" class="form-control" value="{{ old('address', $teacher->address) }}"></div>
            <div class="col-md-2"><label class="form-label">Número</label><input name="number" class="form-control" value="{{ old('number', $teacher->number) }}"></div>
            <div class="col-md-4"><label class="form-label">Bairro</label><input name="neighborhood" class="form-control" value="{{ old('neighborhood', $teacher->neighborhood) }}"></div>
            <div class="col-md-6"><label class="form-label">Cidade</label><input name="city" class="form-control" value="{{ old('city', $teacher->city) }}"></div>
            <div class="col-md-2"><label class="form-label">UF</label><input name="state" maxlength="2" class="form-control" value="{{ old('state', $teacher->state) }}"></div>
            <div class="col-12"><label class="form-label">Observações</label><textarea name="notes" class="form-control" rows="3">{{ old('notes', $teacher->notes) }}</textarea></div>
        </div></div></div>
        <button class="btn btn-success"><i class="bi bi-check-lg"></i> Salvar alterações</button>
        <a href="{{ route('teachers.show', $teacher) }}" class="btn btn-secondary">Cancelar</a>
    </form>
@stop
