@extends('adminlte::page')
@section('title', 'Detalhes do plano')
@section('content_header')
    <div class="d-flex justify-content-between align-items-center"><h1>Detalhes do plano</h1><div class="d-flex gap-2"><a href="{{ route('plans.index') }}" class="btn btn-secondary">Voltar</a><a href="{{ route('plans.edit', $plan) }}" class="btn btn-primary"><i class="bi bi-pencil"></i> Editar</a><form method="POST" action="{{ route('plans.destroy', $plan) }}" onsubmit="return confirm('Deseja excluir este plano?')">@csrf @method('DELETE')<button class="btn btn-danger"><i class="bi bi-trash"></i> Excluir</button></form></div></div>
@stop
@section('content')
    <x-alerts /><div class="card"><div class="card-body"><dl class="row mb-0"><dt class="col-sm-3">Nome</dt><dd class="col-sm-9">{{ $plan->name }}</dd><dt class="col-sm-3">Descrição</dt><dd class="col-sm-9">{{ $plan->description ?: '-' }}</dd><dt class="col-sm-3">Duração</dt><dd class="col-sm-9">{{ $plan->duration_months }} {{ $plan->duration_months === 1 ? 'mês' : 'meses' }}</dd><dt class="col-sm-3">Valor mensal</dt><dd class="col-sm-9">R$ {{ number_format((float) $plan->price, 2, ',', '.') }}</dd><dt class="col-sm-3">Status</dt><dd class="col-sm-9"><span class="status-pill status-pill-{{ $plan->active ? 'success' : 'neutral' }}">{{ $plan->active ? 'Ativo' : 'Inativo' }}</span></dd></dl></div></div>
@stop
