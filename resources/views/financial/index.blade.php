@extends('adminlte::page')

@section('title', 'Financeiro')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Financeiro</h1>
        <div class="d-flex align-items-center justify-content-end gap-2 flex-wrap">
            <a href="{{ route('financial.cash-flow') }}" class="btn btn-success"><i class="bi bi-graph-up-arrow"></i> Fluxo de caixa</a>
            <a href="{{ route('financial.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Novo lançamento
            </a>
        </div>
    </div>
@stop

@section('content')
    <x-alerts />

    <div class="row g-3 mb-3">
        <div class="col-12 col-md-3"><div class="card h-100"><div class="card-body"><small class="text-muted">Receitas no período</small><h3 class="mb-0">R$ {{ number_format((float) $summary['income'], 2, ',', '.') }}</h3></div></div></div>
        <div class="col-12 col-md-3"><div class="card h-100"><div class="card-body"><small class="text-muted">Despesas no período</small><h3 class="mb-0">R$ {{ number_format((float) $summary['expenses'], 2, ',', '.') }}</h3></div></div></div>
        <div class="col-12 col-md-3"><div class="card h-100"><div class="card-body"><small class="text-muted">Total pago</small><h3 class="mb-0">R$ {{ number_format((float) $summary['paid'], 2, ',', '.') }}</h3></div></div></div>
        <div class="col-12 col-md-3"><div class="card h-100"><div class="card-body"><small class="text-muted">Em atraso</small><h3 class="mb-0">R$ {{ number_format((float) $summary['overdue'], 2, ',', '.') }}</h3></div></div></div>
    </div>

    <div class="card mb-3"><div class="card-body"><form method="GET" action="{{ route('financial.index') }}" class="row g-2 align-items-end"><div class="col-12 col-md-4"><label class="form-label">Buscar</label><input name="search" value="{{ request('search') }}" class="form-control" placeholder="Descrição ou aluno"></div><div class="col-6 col-md-2"><label class="form-label">De</label><input type="date" name="from" value="{{ request('from', $from->format('Y-m-d')) }}" class="form-control"></div><div class="col-6 col-md-2"><label class="form-label">Até</label><input type="date" name="to" value="{{ request('to', $to->format('Y-m-d')) }}" class="form-control"></div><div class="col-6 col-md-2"><label class="form-label">Tipo</label><select name="transaction_type" class="form-select"><option value="">Todos</option><option value="income" @selected(request('transaction_type') === 'income')>Receita</option><option value="expense" @selected(request('transaction_type') === 'expense')>Despesa</option></select></div><div class="col-6 col-md-2"><label class="form-label">Status</label><select name="status" class="form-select"><option value="">Todos</option><option value="pending" @selected(request('status') === 'pending')>Pendente</option><option value="paid" @selected(request('status') === 'paid')>Pago</option><option value="overdue" @selected(request('status') === 'overdue')>Em atraso</option><option value="cancelled" @selected(request('status') === 'cancelled')>Cancelado</option></select></div><div class="col-12 d-flex justify-content-end gap-2"><button class="btn btn-primary">Filtrar</button><a href="{{ route('financial.index') }}" class="btn btn-outline-secondary">Limpar</a></div></form></div></div>

    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Descrição</th>
                        <th>Aluno</th>
                        <th>Vencimento</th>
                        <th>Valor</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                        <tr>
                            <td>{{ $transaction->description }}</td>
                            <td>{{ $transaction->student->user->name }}</td>
                            <td>{{ $transaction->due_date->format('d/m/Y') }}</td>
                            <td>R$ {{ number_format((float) $transaction->amount, 2, ',', '.') }}</td>
                            <td>
                                @if ($transaction->status === 'paid')
                                    <span class="financial-status financial-status-paid">Pago</span>
                                @elseif ($transaction->isOverdue())
                                    <span class="financial-status financial-status-overdue">Em atraso</span>
                                @else
                                    <span class="financial-status financial-status-pending">Pendente</span>
                                @endif
                            </td>
                            <td class="text-end">
                                @if ($transaction->status !== 'paid' && $transaction->status !== 'cancelled')
                                    <a href="{{ route('financial.receive', $transaction) }}" class="btn btn-sm btn-success"><i class="bi bi-cash-coin"></i> Receber</a>
                                @endif
                                <a href="{{ route('financial.show', $transaction) }}" class="btn btn-sm btn-outline-primary">Visualizar</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Nenhum lançamento cadastrado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer d-flex justify-content-end">
            {{ $transactions->links() }}
        </div>
    </div>
@stop
