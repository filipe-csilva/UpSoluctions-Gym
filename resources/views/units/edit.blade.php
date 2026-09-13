@extends('adminlte::page')
@section('title', 'Editar unidade')
@section('content_header')<h1>Editar unidade</h1>@stop
@section('content')
    <div class="card"><div class="card-body">
        <form method="POST" action="{{ route('units.update', $unit) }}">
            @include('units._form')
        </form>
    </div></div>
@stop
