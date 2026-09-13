@extends('adminlte::page')
@section('title', 'Nova unidade')
@section('content_header')<h1>Nova unidade</h1>@stop
@section('content')
    <x-alerts />
    <div class="card"><div class="card-body">
        <form method="POST" action="{{ route('units.store') }}">
            @include('units._form')
        </form>
    </div></div>
@stop
