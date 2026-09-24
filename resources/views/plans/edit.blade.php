@extends('adminlte::page')
@section('title', 'Editar plano')
@section('content_header')<h1>Editar plano</h1>@stop
@section('content')
    <x-alerts /><div class="card"><div class="card-body">@include('plans._form', ['formAction' => route('plans.update', $plan), 'formMethod' => 'PUT'])</div></div>
@stop
