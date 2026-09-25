@extends('adminlte::page')
@section('title', 'Detalhes da matrícula')
@section('content_header')<div class="d-flex justify-content-between align-items-center"><h1>Detalhes da matrícula</h1><div><a href="{{ route('enrollments.edit', $enrollment) }}" class="btn btn-primary">Editar</a><form class="d-inline" method="POST" action="{{ route('enrollments.destroy', $enrollment) }}" onsubmit="return confirm('Cancelar matrícula?')">@csrf @method('DELETE')<button class="btn btn-danger">Cancelar</button></form></div></div>@stop
@section('content')
    <x-alerts />
    <div class="card"><div class="card-body"><dl class="row"><dt class="col-sm-3">Aluno</dt><dd class="col-sm-9">{{ $enrollment->student->user->name }}</dd><dt class="col-sm-3">Plano</dt><dd class="col-sm-9">{{ $enrollment->plan->name }}</dd><dt class="col-sm-3">Período</dt><dd class="col-sm-9">{{ $enrollment->start_date->format('d/m/Y') }} a {{ $enrollment->end_date->format('d/m/Y') }}</dd><dt class="col-sm-3">Valor</dt><dd class="col-sm-9">R$ {{ number_format((float) $enrollment->price, 2, ',', '.') }}</dd><dt class="col-sm-3">Status</dt><dd class="col-sm-9"><span class="enrollment-status enrollment-status-{{ $enrollment->status }}">{{ ['active' => 'Ativa', 'suspended' => 'Suspensa', 'cancelled' => 'Cancelada', 'expired' => 'Expirada'][$enrollment->status] ?? ucfirst($enrollment->status) }}</span></dd></dl></div></div>
@stop
