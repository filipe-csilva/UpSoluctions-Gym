@extends('adminlte::page')
@section('title','Novo exercício')
@section('content_header')<h1>Novo exercício</h1>@stop
@section('content')<x-alerts /><div class="card"><div class="card-body">@include('exercises._form',['formAction'=>route('exercises.store'),'formMethod'=>'POST'])</div></div>@stop
