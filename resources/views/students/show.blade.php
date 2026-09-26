@extends('adminlte::page')

@section('title', 'Detalhes do aluno')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Detalhes do aluno</h1>
        @if (in_array(auth()->user()?->role?->value, ['admin', 'manager'], true) && $student)
            <div class="d-flex gap-2">
                <a href="{{ route('students.edit', $student) }}" class="btn btn-primary"><i class="bi bi-pencil"></i> Editar</a>
                <a href="{{ route('students.enrollments.history', $student) }}" class="btn btn-info"><i class="bi bi-clock-history"></i> Matrículas</a>
                <a href="{{ route('attendances.student-history', $student) }}" class="btn btn-outline-info"><i class="bi bi-calendar-check"></i> Frequência</a>
                <form method="POST" action="{{ route('students.destroy', $student) }}" onsubmit="return confirm('Deseja excluir este aluno?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" title="Excluir aluno"><i class="bi bi-trash"></i> Excluir</button>
                </form>
            </div>
        @endif
    </div>
@stop

@section('content')
    <x-alerts />
    @php($isActive = $student?->user?->active ?? $user?->active ?? false)
    @php($gender = match (strtolower((string) $student?->gender)) {
        'f' => 'Feminino',
        'm' => 'Masculino',
        default => $student?->gender ?: '-',
    })
    <div class="card">
        <div class="card-header"><h3 class="card-title">{{ $student?->user?->name ?? auth()->user()?->name }}</h3></div>
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-sm-3">E-mail</dt><dd class="col-sm-9">{{ $student?->user?->email ?? auth()->user()?->email ?? '-' }}</dd>
                <dt class="col-sm-3">Unidade</dt><dd class="col-sm-9">{{ $student?->user?->unit?->name ?? auth()->user()?->unit?->name ?? '-' }}</dd>
                <dt class="col-sm-3">CPF</dt><dd class="col-sm-9">{{ $student?->cpf ?? '-' }}</dd>
                <dt class="col-sm-3">Nascimento</dt><dd class="col-sm-9">{{ $student?->birth_date?->format('d/m/Y') ?? '-' }}</dd>
                <dt class="col-sm-3">Telefone</dt><dd class="col-sm-9">{{ $student?->phone ?? '-' }}</dd>
                <dt class="col-sm-3">Status</dt><dd class="col-sm-9"><span class="badge text-bg-{{ $isActive ? 'success' : 'secondary' }}">{{ $isActive ? 'Ativo' : 'Inativo' }}</span></dd>
                <dt class="col-sm-3">Gênero</dt><dd class="col-sm-9">{{ $gender }}</dd>
                <dt class="col-sm-3">Endereço</dt><dd class="col-sm-9">{{ $student?->address ?: '-' }}, {{ $student?->number ?: 's/n' }}</dd>
                <dt class="col-sm-3">Cidade/UF</dt><dd class="col-sm-9">{{ $student?->city ?: '-' }}/{{ $student?->state ?: '-' }}</dd>
                <dt class="col-sm-3">Contato de emergência</dt><dd class="col-sm-9">{{ $student?->emergency_contact ?: '-' }} {{ $student?->emergency_phone ? '(' . $student->emergency_phone . ')' : '' }}</dd>
                <dt class="col-sm-3">Observações</dt><dd class="col-sm-9">{{ $student?->notes ?: '-' }}</dd>
            </dl>
            @if (!$student)
                <x-alerts type="warning" message="Seu perfil de aluno ainda não possui dados complementares cadastrados." />
            @endif
        </div>
    </div>
@stop
