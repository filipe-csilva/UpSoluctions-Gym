@extends('adminlte::page')

@section('title', 'Meu financeiro')
@section('content_header')<h1>Meu financeiro</h1>@stop
@section('content')
    <x-alerts />
    <div class="row g-3 mb-3"><div class="col-md-3"><div class="card"><div class="card-body"><small class="text-muted">Total</small><h3>R$ {{ number_format((float) $summary['total'], 2, ',', '.') }}</h3></div></div></div><div class="col-md-3"><div class="card"><div class="card-body"><small class="text-muted">Pago</small><h3>R$ {{ number_format((float) $summary['paid'], 2, ',', '.') }}</h3></div></div></div><div class="col-md-3"><div class="card"><div class="card-body"><small class="text-muted">Pendente</small><h3>R$ {{ number_format((float) $summary['pending'], 2, ',', '.') }}</h3></div></div></div><div class="col-md-3"><div class="card"><div class="card-body"><small class="text-muted">Em atraso</small><h3>R$ {{ number_format((float) $summary['overdue'], 2, ',', '.') }}</h3></div></div></div></div>
    <div class="card"><div class="card-body table-responsive"><table class="table table-hover align-middle"><thead><tr><th>Descrição</th><th>Unidade</th><th>Vencimento</th><th>Valor</th><th>Status</th><th></th></tr></thead><tbody>
        @forelse($transactions as $transaction)
            <tr><td>{{ $transaction->description }}</td><td>{{ $transaction->unit?->name ?? '-' }}</td><td>{{ $transaction->due_date->format('d/m/Y') }}</td><td>R$ {{ number_format((float) $transaction->amount, 2, ',', '.') }}</td><td>@if($transaction->status === 'paid')<span class="financial-status financial-status-paid">Pago</span>@elseif($transaction->isOverdue())<span class="financial-status financial-status-overdue">Em atraso</span>@else<span class="financial-status financial-status-pending">Pendente</span>@endif</td><td class="text-end"><a href="{{ route('student-financial.show', $transaction) }}" class="btn btn-sm btn-outline-primary">Visualizar</a></td></tr>
        @empty
            <tr><td colspan="6" class="text-center">Nenhum vencimento encontrado.</td></tr>
        @endforelse
    </tbody></table></div><div class="card-footer d-flex justify-content-end">{{ $transactions->links() }}</div></div>
@stop
