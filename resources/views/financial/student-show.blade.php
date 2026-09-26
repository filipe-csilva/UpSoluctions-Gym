@extends('adminlte::page')
@section('title', 'Meu lançamento financeiro')
@section('content_header')<div class="d-flex justify-content-between align-items-center"><h1>Meu lançamento financeiro</h1><a href="{{ route('student-financial.index') }}" class="btn btn-secondary">Voltar</a></div>@stop
@section('content')
    <div class="card"><div class="card-body"><dl class="row"><dt class="col-sm-3">Descrição</dt><dd class="col-sm-9">{{ $transaction->description }}</dd><dt class="col-sm-3">Unidade</dt><dd class="col-sm-9">{{ $transaction->unit?->name ?? '-' }}</dd><dt class="col-sm-3">Valor</dt><dd class="col-sm-9">R$ {{ number_format((float) $transaction->amount, 2, ',', '.') }}</dd><dt class="col-sm-3">Vencimento</dt><dd class="col-sm-9">{{ $transaction->due_date->format('d/m/Y') }}</dd><dt class="col-sm-3">Status</dt><dd class="col-sm-9">@if($transaction->status === 'paid')<span class="financial-status financial-status-paid">Pago</span>@elseif($transaction->isOverdue())<span class="financial-status financial-status-overdue">Em atraso</span>@else<span class="financial-status financial-status-pending">Pendente</span>@endif</dd></dl></div></div>
@stop
