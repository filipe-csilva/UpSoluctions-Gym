@extends('adminlte::page')
@section('title', 'Novo plano')
@section('content_header')<h1>Novo plano</h1>@stop
@section('content')
    <x-alerts /><div class="card"><div class="card-body">@include('plans._form', ['formAction' => route('plans.store'), 'formMethod' => 'POST'])</div></div>
@stop
