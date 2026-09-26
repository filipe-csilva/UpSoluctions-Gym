<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>{{ $title }} - {{ $companyName }}</title>
    <style>
        body { font-family: Arial, sans-serif; color: #172033; margin: 32px; }
        .report-header { display: flex; justify-content: space-between; align-items: flex-start; padding-bottom: 18px; border-bottom: 3px solid #ef4444; }
        .brand { color: #172033; font-size: 24px; font-weight: 700; }
        .brand span { color: #ef4444; }
        .report-meta { color: #64748b; font-size: 12px; line-height: 1.7; text-align: right; }
        h1 { margin: 24px 0 4px; font-size: 24px; }
        p { color: #64748b; margin: 0; }
        table { border-collapse: collapse; width: 100%; margin-top: 24px; }
        th, td { border: 1px solid #cbd5e1; padding: 8px; text-align: left; font-size: 12px; }
        th { color: #ffffff; background: #172033; }
        .report-footer { margin-top: 24px; color: #64748b; font-size: 11px; text-align: center; }
        @media print { body { margin: 12px; } .no-print { display: none; } }
    </style>
</head>
<body onload="window.print()">
    <div class="no-print"><button onclick="window.print()">Imprimir / Salvar PDF</button></div>
    <header class="report-header">
        <div class="brand">{{ $companyName }}</div>
        <div class="report-meta">
            <div>Relatório gerado em {{ $generatedAt->format('d/m/Y H:i') }}</div>
            <div>Unidade: {{ $unitName }}</div>
            <div>Período: {{ $from->format('d/m/Y') }} até {{ $to->format('d/m/Y') }}</div>
        </div>
    </header>
    <h1>{{ $title }}</h1>
    <p>Dados consolidados do período selecionado.</p>
    <table>
        <thead><tr>@foreach($headers as $header)<th>{{ $header }}</th>@endforeach</tr></thead>
        <tbody>
            @forelse($rows as $row)
                <tr>@foreach($row as $value)<td>{{ $value }}</td>@endforeach</tr>
            @empty
                <tr><td colspan="{{ count($headers) }}">Nenhum registro encontrado.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="report-footer">GymControl · Relatório administrativo</div>
</body>
</html>
