@extends('adminlte::page')
@section('title','Editar exercício')
@section('content_header')<h1>Editar exercício</h1>@stop
@section('content')<x-alerts /><div class="card"><div class="card-body">@include('exercises._form',['formAction'=>route('exercises.update',$exercise),'formMethod'=>'PUT'])</div></div>@stop
