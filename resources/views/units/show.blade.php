@extends('adminlte::page')
@section('title', 'Detalhes da unidade')
@section('content_header')
    <div class="d-flex justify-content-between align-items-center"><h1>Detalhes da unidade</h1><a href="{{ route('units.edit', $unit) }}" class="btn btn-primary">Editar</a></div>
@stop
@section('content')
    <x-alerts />
    <div class="card"><div class="card-body"><dl class="row mb-0">
        <dt class="col-sm-3">Nome</dt><dd class="col-sm-9">{{ $unit->name }}</dd>
        <dt class="col-sm-3">Código</dt><dd class="col-sm-9">{{ $unit->code }}</dd>
        <dt class="col-sm-3">Telefone</dt><dd class="col-sm-9">{{ $unit->phone ?: '-' }}</dd>
        <dt class="col-sm-3">E-mail</dt><dd class="col-sm-9">{{ $unit->email ?: '-' }}</dd>
        <dt class="col-sm-3">Endereço</dt><dd class="col-sm-9">{{ $unit->address ?: '-' }}, {{ $unit->number ?: 's/n' }}</dd>
        <dt class="col-sm-3">Cidade/UF</dt><dd class="col-sm-9">{{ $unit->city ?: '-' }}/{{ $unit->state ?: '-' }}</dd>
        <dt class="col-sm-3">Status</dt><dd class="col-sm-9">{{ $unit->active ? 'Ativa' : 'Inativa' }}</dd>
        <dt class="col-sm-3">Usuários vinculados</dt><dd class="col-sm-9">{{ $unit->users_count }}</dd>
    </dl></div></div>
    <form method="POST" action="{{ route('units.destroy', $unit) }}" onsubmit="return confirm('Deseja excluir esta unidade?')">
        @csrf @method('DELETE')
        <a href="{{ route('units.index') }}" class="btn btn-secondary">Voltar</a>
        <button class="btn btn-danger">Excluir</button>
    </form>
@stop
