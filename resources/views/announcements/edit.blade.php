@extends('adminlte::page')
@section('title', 'Editar comunicado')
@section('content_header')<h1>Editar comunicado</h1>@stop
@section('content')<x-alerts /><div class="card"><div class="card-body">@include('announcements._form', ['formAction' => route('announcements.update', $announcement), 'formMethod' => 'PUT'])</div></div>@stop
