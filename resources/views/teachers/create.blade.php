@extends('adminlte::page')

@section('title', 'Cadastrar Instrutor')
@section('content_header')
    <div class="d-flex justify-content-between align-items-center"><h1>Cadastrar Instrutor</h1><a href="{{ route('teachers.index') }}" class="btn btn-secondary">Voltar</a></div>
@stop
@section('content')
    <x-alerts />
    <form method="POST" action="{{ route('teachers.store') }}" data-viacep>
        @csrf
        <div class="card card-primary mb-3"><div class="card-header"><h3 class="card-title">Dados de acesso</h3></div><div class="card-body"><div class="row g-3">
            <div class="col-md-6"><label class="form-label">Nome *</label><input type="text" name="name" class="form-control" value="{{ old('name') }}" required></div>
            <div class="col-md-6"><label class="form-label">Unidade *</label><select name="unit_id" class="form-control" required><option value="">Selecione</option>@foreach ($units as $unit)<option value="{{ $unit->id }}" @selected(old('unit_id') == $unit->id)>{{ $unit->name }}</option>@endforeach</select></div>
            <div class="col-md-6"><label class="form-label">E-mail *</label><input type="email" name="email" class="form-control" value="{{ old('email') }}" required></div>
            <div class="col-md-6"><div class="form-text mt-4">A senha será definida pelo instrutor através de um link enviado para este e-mail.</div></div>
        </div></div></div>
        <div class="card card-info mb-3"><div class="card-header"><h3 class="card-title">Dados pessoais</h3></div><div class="card-body"><div class="row g-3">
            <div class="col-md-4"><label class="form-label">CPF *</label><input type="text" name="cpf" class="form-control" value="{{ old('cpf') }}" maxlength="14" required></div>
            <div class="col-md-4"><label class="form-label">Data de nascimento *</label><input type="date" name="birth_date" class="form-control" value="{{ old('birth_date') }}" required></div>
            <div class="col-md-4"><label class="form-label">Telefone *</label><input type="text" name="phone" class="form-control" value="{{ old('phone') }}" required></div>
            <div class="col-md-4"><label class="form-label">Gênero</label><select name="gender" class="form-control"><option value="">Não informado</option><option value="Masculino" @selected(old('gender') === 'Masculino')>Masculino</option><option value="Feminino" @selected(old('gender') === 'Feminino')>Feminino</option><option value="Outro" @selected(old('gender') === 'Outro')>Outro</option></select></div>
        </div></div></div>
        <div class="card card-secondary mb-3"><div class="card-header"><h3 class="card-title">Endereço</h3></div><div class="card-body"><div class="row g-3">
            <div class="col-md-3"><label class="form-label">CEP</label><input type="text" name="zip_code" class="form-control" value="{{ old('zip_code') }}"></div>
            <div class="col-md-7"><label class="form-label">Endereço</label><input type="text" name="address" class="form-control" value="{{ old('address') }}"></div>
            <div class="col-md-2"><label class="form-label">Número</label><input type="text" name="number" class="form-control" value="{{ old('number') }}"></div>
            <div class="col-md-4"><label class="form-label">Bairro</label><input type="text" name="neighborhood" class="form-control" value="{{ old('neighborhood') }}"></div>
            <div class="col-md-6"><label class="form-label">Cidade</label><input type="text" name="city" class="form-control" value="{{ old('city') }}"></div>
            <div class="col-md-2"><label class="form-label">UF</label><input type="text" name="state" maxlength="2" class="form-control" value="{{ old('state') }}"></div>
            <div class="col-12"><label class="form-label">Observações</label><textarea name="notes" class="form-control" rows="3">{{ old('notes') }}</textarea></div>
        </div></div></div>
        <div class="d-flex gap-2"><button class="btn btn-success"><i class="bi bi-check-lg"></i> Salvar</button><a href="{{ route('teachers.index') }}" class="btn btn-secondary">Cancelar</a></div>
    </form>
@stop
