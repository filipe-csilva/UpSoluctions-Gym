@extends('adminlte::page')

@section('title', 'Relatórios')

@section('content_header')
    <div class="reports-page-heading d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3">
        <h1 class="mb-0">Relatórios</h1>
        <div class="reports-export-actions d-flex flex-wrap gap-2 w-100 w-md-auto">
            <a href="{{ route('reports.export', request()->only(['type', 'from', 'to', 'unit_id'])) }}" class="btn btn-success"><i class="bi bi-filetype-csv"></i> CSV</a>
            <a href="{{ route('reports.excel', request()->only(['type', 'from', 'to', 'unit_id'])) }}" class="btn btn-outline-success"><i class="bi bi-file-earmark-spreadsheet"></i> Excel</a>
            <a href="{{ route('reports.pdf', request()->only(['type', 'from', 'to', 'unit_id'])) }}" target="_blank" class="btn btn-danger"><i class="bi bi-filetype-pdf"></i> PDF</a>
        </div>
    </div>
@stop

@section('content')
    <x-alerts />

    <div class="card mb-3 reports-filter-card">
        <div class="card-body">
            <form method="GET" action="{{ route('reports.index') }}" class="row g-2 align-items-end reports-filter-form">
                <div class="col-12 col-sm-6 col-lg-3"><label class="form-label">Relatório</label><select name="type" class="form-select"><option value="financial" @selected($reportType === 'financial')>Financeiro</option><option value="cash_flow" @selected($reportType === 'cash_flow')>Fluxo de caixa</option><option value="overdue_installments" @selected($reportType === 'overdue_installments')>Parcelas vencidas</option><option value="students" @selected($reportType === 'students')>Alunos</option><option value="enrollments" @selected($reportType === 'enrollments')>Matrículas</option><option value="attendance" @selected($reportType === 'attendance')>Presença</option><option value="assessments" @selected($reportType === 'assessments')>Avaliações físicas</option></select></div>
                <div class="col-12 col-sm-6 col-lg-2"><label class="form-label">De</label><input type="date" name="from" value="{{ request('from', $from->format('Y-m-d')) }}" class="form-control"></div>
                <div class="col-12 col-sm-6 col-lg-2"><label class="form-label">Até</label><input type="date" name="to" value="{{ request('to', $to->format('Y-m-d')) }}" class="form-control"></div>
                <div class="col-12 col-sm-6 col-lg-3"><label class="form-label">Unidade</label><select name="unit_id" class="form-select"><option value="">Todas</option>@foreach($units as $unit)<option value="{{ $unit->id }}" @selected($unitId === $unit->id)>{{ $unit->name }}</option>@endforeach</select></div>
                <div class="col-12 col-lg-2"><button class="btn btn-primary w-100"><i class="bi bi-funnel"></i> Filtrar</button></div>
            </form>
        </div>
    </div>

    <div class="row g-3 mb-3 reports-metrics">
        <div class="col-12 col-sm-6 col-xl-3"><div class="card h-100"><div class="card-body"><small class="text-muted">Receitas pagas</small><h3>R$ {{ number_format((float) $income, 2, ',', '.') }}</h3></div></div></div>
        <div class="col-12 col-sm-6 col-xl-3"><div class="card h-100"><div class="card-body"><small class="text-muted">Despesas pagas</small><h3>R$ {{ number_format((float) $expenses, 2, ',', '.') }}</h3></div></div></div>
        <div class="col-12 col-sm-6 col-xl-3"><div class="card h-100"><div class="card-body"><small class="text-muted">Inadimplência</small><h3>R$ {{ number_format((float) $overdue, 2, ',', '.') }}</h3></div></div></div>
        <div class="col-12 col-sm-6 col-xl-3"><div class="card h-100"><div class="card-body"><small class="text-muted">Resultado</small><h3>R$ {{ number_format((float) $income - (float) $expenses, 2, ',', '.') }}</h3></div></div></div>
    </div>

    <div class="card reports-results-card">
        <div class="card-header"><h3 class="card-title mb-0">Resultados do relatório</h3></div>
        <div class="card-body p-0">
            <div class="table-responsive reports-table-wrapper">
                <table class="table table-hover align-middle reports-results-table mb-0">
                    <thead><tr>@foreach($reportHeaders as $header)<th>{{ $header }}</th>@endforeach</tr></thead>
                    <tbody>
                        @forelse($reportRows as $row)
                            <tr>@foreach($row as $value)<td>{{ $value }}</td>@endforeach</tr>
                        @empty
                            <tr><td colspan="{{ count($reportHeaders) }}" class="text-center text-muted">Nenhum registro encontrado para os filtros informados.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop
