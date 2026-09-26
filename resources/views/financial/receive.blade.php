@extends('adminlte::page')

@section('title', 'Receber lançamento')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Receber lançamento</h1>
        <a href="{{ route('financial.show', $transaction) }}" class="btn btn-secondary">Voltar</a>
    </div>
@stop

@section('content')
    <x-alerts />
    <div class="card">
        <div class="card-body">
            <dl class="row mb-4">
                <dt class="col-sm-3">Descrição</dt><dd class="col-sm-9">{{ $transaction->description }}</dd>
                <dt class="col-sm-3">Aluno</dt><dd class="col-sm-9">{{ $transaction->student->user->name }}</dd>
                <dt class="col-sm-3">Valor</dt><dd class="col-sm-9">R$ {{ number_format((float) $transaction->amount, 2, ',', '.') }}</dd>
                <dt class="col-sm-3">Vencimento</dt><dd class="col-sm-9">{{ $transaction->due_date->format('d/m/Y') }}</dd>
            </dl>
            <form method="POST" action="{{ route('financial.mark-paid', $transaction) }}" class="row g-3">
                @csrf
                <div class="col-md-6">
                    <label for="payment_method" class="form-label">Forma de pagamento *</label>
                    <select id="payment_method" name="payment_method" class="form-select" required>
                        <option value="">Selecione</option>
                        <option value="cash">Dinheiro</option>
                        <option value="pix">PIX</option>
                        <option value="credit_card">Cartão de crédito</option>
                        <option value="debit_card">Cartão de débito</option>
                        <option value="bank_transfer">Transferência bancária</option>
                        <option value="other">Outro</option>
                    </select>
                </div>
                <div class="col-12"><button class="btn btn-success"><i class="bi bi-check-lg"></i> Confirmar recebimento</button></div>
            </form>
        </div>
    </div>
@stop
