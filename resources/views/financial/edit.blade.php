@extends('adminlte::page')

@section('title', 'Editar lançamento financeiro')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Editar lançamento financeiro</h1>
        <a href="{{ route('financial.show', $transaction) }}" class="btn btn-secondary">Voltar</a>
    </div>
@stop

@section('content')
    <x-alerts />
    <div class="card"><div class="card-body"><form method="POST" action="{{ route('financial.update', $transaction) }}">@csrf @method('PUT')<div class="row g-3"><div class="col-md-6"><label class="form-label">Descrição</label><input name="description" class="form-control" value="{{ $transaction->description }}" disabled></div><div class="col-md-6"><label class="form-label">Aluno</label><input class="form-control" value="{{ $transaction->student->user->name }}" disabled></div><div class="col-md-4"><label class="form-label">Valor</label><input class="form-control" value="R$ {{ number_format((float) $transaction->amount, 2, ',', '.') }}" disabled></div><div class="col-md-4"><label class="form-label">Status *</label><select name="status" class="form-select" required><option value="pending" @selected($transaction->status === 'pending')>Pendente</option><option value="paid" @selected($transaction->status === 'paid')>Pago</option><option value="overdue" @selected($transaction->status === 'overdue')>Em atraso</option><option value="cancelled" @selected($transaction->status === 'cancelled')>Cancelado</option></select></div><div class="col-md-4"><label class="form-label">Forma de pagamento</label><input name="payment_method" class="form-control" value="{{ $transaction->payment_method }}"></div><div class="col-12"><label class="form-label">Observações</label><textarea name="notes" class="form-control">{{ $transaction->notes }}</textarea></div></div><button class="btn btn-success mt-3"><i class="bi bi-check-lg"></i> Salvar</button></form></div></div>
@stop
