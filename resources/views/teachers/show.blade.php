@extends('adminlte::page')

@section('title', 'Detalhes do instrutor')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Detalhes do instrutor</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('teachers.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Voltar</a>
            <a href="{{ route('teachers.students', $teacher) }}" class="btn btn-info"><i class="bi bi-people"></i> Alunos vinculados</a>
            <a href="{{ route('teachers.edit', $teacher) }}" class="btn btn-primary"><i class="bi bi-pencil"></i> Editar</a>
            <form method="POST" action="{{ route('teachers.destroy', $teacher) }}" onsubmit="return confirm('Deseja excluir este instrutor?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" title="Excluir instrutor"><i class="bi bi-trash"></i> Excluir</button>
            </form>
        </div>
    </div>
@stop

@section('content')
    <x-alerts />
    <div class="card">
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-sm-3">Nome</dt><dd class="col-sm-9">{{ $teacher->user->name }}</dd>
                <dt class="col-sm-3">E-mail</dt><dd class="col-sm-9">{{ $teacher->user->email }}</dd>
                <dt class="col-sm-3">Unidade</dt><dd class="col-sm-9">{{ $teacher->user->unit?->name ?? '-' }}</dd>
                <dt class="col-sm-3">CPF</dt><dd class="col-sm-9">{{ $teacher->cpf }}</dd>
                <dt class="col-sm-3">Telefone</dt><dd class="col-sm-9">{{ $teacher->phone }}</dd>
                <dt class="col-sm-3">Nascimento</dt><dd class="col-sm-9">{{ $teacher->birth_date?->format('d/m/Y') ?? '-' }}</dd>
                <dt class="col-sm-3">Status</dt><dd class="col-sm-9"><span class="badge text-bg-{{ $teacher->user->active ? 'success' : 'secondary' }}">{{ $teacher->user->active ? 'Ativo' : 'Inativo' }}</span></dd>
            </dl>
        </div>
    </div>
@stop
