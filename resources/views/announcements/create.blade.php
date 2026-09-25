@extends('adminlte::page')
@section('title', 'Novo comunicado')
@section('content_header')<h1>Novo comunicado</h1>@stop
@section('content')<x-alerts /><div class="card"><div class="card-body">@include('announcements._form', ['formAction' => route('announcements.store'), 'formMethod' => 'POST'])</div></div>@stop
