@extends('adminlte::page')
@section('title', 'Editar matrícula')
@section('content_header')<h1>Editar matrícula</h1>@stop
@section('content')<x-alerts /><div class="card"><div class="card-body">@include('enrollments._form', ['formAction' => route('enrollments.update', $enrollment), 'formMethod' => 'PUT'])</div></div>@stop
