@extends('adminlte::page')

@section('title', 'Fluxo de caixa')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Fluxo de caixa</h1>
        <a href="{{ route('financial.index') }}" class="btn btn-secondary">Voltar ao financeiro</a>
    </div>
@stop

@section('content')
    <x-alerts />
    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('financial.cash-flow') }}" class="row g-2 align-items-end">
                <div class="col-12 col-md-4"><label class="form-label">De</label><input type="date" name="from" value="{{ request('from', $from->format('Y-m-d')) }}" class="form-control"></div>
                <div class="col-12 col-md-4"><label class="form-label">Até</label><input type="date" name="to" value="{{ request('to', $to->format('Y-m-d')) }}" class="form-control"></div>
                <div class="col-12 col-md-4"><button class="btn btn-primary">Filtrar período</button><a href="{{ route('financial.cash-flow') }}" class="btn btn-outline-secondary ms-2">Hoje</a></div>
            </form>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-12 col-md-3"><div class="card h-100 border-success"><div class="card-body"><small class="text-muted">Entradas recebidas</small><h3 class="text-success mb-0">R$ {{ number_format($summary['income'], 2, ',', '.') }}</h3></div></div></div>
        <div class="col-12 col-md-3"><div class="card h-100 border-danger"><div class="card-body"><small class="text-muted">Saídas pagas</small><h3 class="text-danger mb-0">R$ {{ number_format($summary['expenses'], 2, ',', '.') }}</h3></div></div></div>
        <div class="col-12 col-md-3"><div class="card h-100 border-primary"><div class="card-body"><small class="text-muted">Saldo do período</small><h3 class="{{ $summary['balance'] >= 0 ? 'text-success' : 'text-danger' }} mb-0">R$ {{ number_format($summary['balance'], 2, ',', '.') }}</h3></div></div></div>
        <div class="col-12 col-md-3"><div class="card h-100"><div class="card-body"><small class="text-muted">Movimentações</small><h3 class="mb-0">{{ $summary['movements'] }}</h3></div></div></div>
    </div>

    <div class="row g-3">
        <div class="col-12 col-xl-5">
            <div class="card h-100">
                <div class="card-header"><strong>Resumo diário</strong><div class="text-muted small">{{ $from->format('d/m/Y') }} a {{ $to->format('d/m/Y') }}</div></div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0"><thead><tr><th>Data</th><th>Entradas</th><th>Saídas</th><th>Saldo</th></tr></thead><tbody>
                        @foreach($dailySummary as $day)
                            <tr><td>{{ $day['date']->format('d/m/Y') }}</td><td class="text-success">R$ {{ number_format($day['income'], 2, ',', '.') }}</td><td class="text-danger">R$ {{ number_format($day['expenses'], 2, ',', '.') }}</td><td class="{{ $day['balance'] >= 0 ? 'text-success' : 'text-danger' }}">R$ {{ number_format($day['balance'], 2, ',', '.') }}</td></tr>
                        @endforeach
                    </tbody></table>
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-7">
            <div class="card h-100">
                <div class="card-header"><strong>Movimentações do caixa</strong></div>
                <div class="table-responsive"><table class="table table-hover mb-0"><thead><tr><th>Data</th><th>Descrição</th><th>Tipo</th><th>Valor</th></tr></thead><tbody>
                    @forelse($movements as $movement)
                        <tr><td>{{ $movement->paid_at->format('d/m/Y H:i') }}</td><td>{{ $movement->description }}@if($movement->student)<div class="small text-muted">{{ $movement->student->user->name }}</div>@endif</td><td>{{ $movement->transaction_type === 'income' ? 'Entrada' : 'Saída' }}</td><td class="{{ $movement->transaction_type === 'income' ? 'text-success' : 'text-danger' }}">{{ $movement->transaction_type === 'income' ? '+' : '-' }} R$ {{ number_format((float) $movement->amount, 2, ',', '.') }}</td></tr>
                    @empty
                        <tr><td colspan="4" class="text-center">Nenhuma movimentação no período.</td></tr>
                    @endforelse
                </tbody></table></div>
            </div>
        </div>
    </div>
@stop
