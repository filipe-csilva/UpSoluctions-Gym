@extends('adminlte::page')
@section('title', 'Editar usuário')
@section('content_header')<h1>Editar usuário</h1>@stop
@section('content')<x-alerts />@include('users._form', ['action' => route('users.update', $user), 'method' => 'PUT'])@stop
