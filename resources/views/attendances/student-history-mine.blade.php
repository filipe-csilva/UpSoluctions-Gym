@extends('adminlte::page')

@section('title', 'Minha frequência')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center"><h1>Minha frequência</h1><a href="{{ route('students.me') }}" class="btn btn-secondary">Meu perfil</a></div>
@stop

@section('content')
    <div class="card mb-3"><div class="card-body"><form method="GET" action="{{ route('student-attendance.history') }}" class="row g-2 align-items-end"><div class="col-md-4"><label class="form-label">De</label><input type="date" name="from" value="{{ request('from') }}" class="form-control"></div><div class="col-md-4"><label class="form-label">Até</label><input type="date" name="to" value="{{ request('to') }}" class="form-control"></div><div class="col-md-4"><button class="btn btn-primary">Filtrar</button></div></form></div></div>
    <div class="card"><div class="card-header"><strong>{{ $student->user->name }}</strong> · {{ $attendances->total() }} registros</div><div class="card-body table-responsive"><table class="table table-hover"><thead><tr><th>Data</th><th>Entrada</th><th>Saída</th><th>Unidade</th><th>Tipo</th></tr></thead><tbody>
        @forelse($attendances as $attendance)
            <tr><td>{{ $attendance->date->format('d/m/Y') }}</td><td>{{ $attendance->entry_time }}</td><td>{{ $attendance->exit_time ?: '-' }}</td><td>{{ $attendance->unit->name }}</td><td>{{ ucfirst($attendance->type) }}</td></tr>
        @empty
            <tr><td colspan="5" class="text-center">Nenhum registro no período.</td></tr>
        @endforelse
    </tbody></table></div><div class="card-footer">{{ $attendances->links() }}</div></div>
@stop
