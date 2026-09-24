@extends('adminlte::page')
@section('title', 'Nova matrícula')
@section('content_header')<h1>Nova matrícula</h1>@stop
@section('content')<x-alerts /><div class="card"><div class="card-body">@include('enrollments._form', ['formAction' => route('enrollments.store'), 'formMethod' => 'POST'])</div></div>@stop
