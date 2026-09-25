@extends('adminlte::page')
@section('title','Ficha de treino')
@section('content_header')<div class="d-flex justify-content-between"><h1>{{ $plan->name }}</h1><form method="POST" action="{{ route('workout-plans.destroy',$plan) }}">@csrf @method('DELETE')<button class="btn btn-danger">Cancelar ficha</button></form></div>@stop
@section('content')<x-alerts /><div class="card"><div class="card-body"><dl class="row"><dt class="col-sm-3">Aluno</dt><dd class="col-sm-9">{{ $plan->student->user->name }}</dd><dt class="col-sm-3">Instrutor</dt><dd class="col-sm-9">{{ $plan->teacher->name }}</dd><dt class="col-sm-3">Status</dt><dd class="col-sm-9">{{ ucfirst($plan->status) }}</dd><dt class="col-sm-3">Descrição</dt><dd class="col-sm-9">{{ $plan->description ?: '-' }}</dd></dl></div></div>@stop
