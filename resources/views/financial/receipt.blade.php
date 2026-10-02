<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>Recibo {{ $transaction->id }} - {{ $companyName }}</title>
    <style>
        :root { color: #172033; font-family: Arial, sans-serif; }
        * { box-sizing: border-box; }
        body { margin: 0; background: #eef2f7; }
        .receipt-toolbar { display: flex; justify-content: center; gap: 8px; padding: 16px; }
        .receipt-toolbar a, .receipt-toolbar button { border: 0; border-radius: 4px; padding: 9px 14px; color: #fff; cursor: pointer; font-size: 13px; text-decoration: none; }
        .receipt-toolbar a { background: #198754; }
        .receipt-toolbar a.secondary { background: #0d6efd; }
        .receipt-toolbar button { background: #6c757d; }
        .receipt { width: 100%; max-width: 760px; margin: 0 auto 24px; padding: 34px; background: #fff; }
        .receipt-header { display: flex; justify-content: space-between; gap: 24px; padding-bottom: 18px; border-bottom: 2px solid #198754; }
        .receipt-brand { font-size: 23px; font-weight: 700; }
        .receipt-project { margin-top: 4px; color: #ef4444; font-size: 12px; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; }
        .receipt-meta { color: #64748b; font-size: 12px; line-height: 1.6; text-align: right; }
        h1 { margin: 26px 0 8px; font-size: 24px; }
        .receipt-subtitle { margin: 0 0 20px; color: #64748b; }
        .receipt-details { width: 100%; border-collapse: collapse; }
        .receipt-details th, .receipt-details td { padding: 10px 0; border-bottom: 1px solid #dbe3ec; text-align: left; font-size: 13px; }
        .receipt-details th { width: 35%; color: #64748b; font-weight: 600; }
        .receipt-total { margin-top: 22px; padding: 14px; border-radius: 5px; background: #e9f7ef; color: #087443; font-size: 18px; font-weight: 700; text-align: right; }
        .receipt-footer { margin-top: 36px; color: #64748b; font-size: 11px; text-align: center; }
        @page { size: A4; margin: 14mm; }
        @media print { body { background: #fff; } .receipt-toolbar { display: none; } .receipt { max-width: none; margin: 0; padding: 0; } }
        @if ($format === '80mm')
        @page { size: 80mm auto; margin: 0; }
        body { width: 80mm; background: #fff; }
        .receipt { width: 72mm; max-width: 72mm; margin: 0 auto; padding: 5mm 0; }
        .receipt-toolbar { width: 100vw; }
        .receipt-header { display: block; padding-bottom: 10px; }
        .receipt-brand { font-size: 16px; }
        .receipt-project { font-size: 9px; }
        .receipt-meta { margin-top: 5px; text-align: left; font-size: 10px; }
        h1 { margin: 16px 0 5px; font-size: 17px; }
        .receipt-subtitle { margin-bottom: 12px; font-size: 11px; }
        .receipt-details th, .receipt-details td { padding: 6px 0; font-size: 10px; }
        .receipt-details th { width: 38%; }
        .receipt-total { margin-top: 14px; padding: 9px; font-size: 14px; }
        .receipt-footer { margin-top: 22px; font-size: 9px; }
        @media print { .receipt { width: 72mm; max-width: 72mm; padding: 5mm 0; } }
        @endif
    </style>
</head>
<body onload="window.print()">
    <div class="receipt-toolbar">
        <a href="{{ route('financial.receipt', ['financial' => $transaction, 'format' => 'a4']) }}">Imprimir A4</a>
        <a href="{{ route('financial.receipt', ['financial' => $transaction, 'format' => '80mm']) }}" class="secondary">Imprimir bobina 80 mm</a>
        <button type="button" onclick="closeReceipt()">Fechar</button>
    </div>
    <main class="receipt">
        <header class="receipt-header"><div><div class="receipt-brand">{{ $companyName }}</div><div class="receipt-project">{{ $projectName }}</div></div><div class="receipt-meta">Recibo nº {{ $transaction->id }}<br>{{ $transaction->paid_at?->format('d/m/Y H:i') }}@if($companyDocument)<br>{{ $companyDocument }}@endif @if($companyPhone)<br>{{ $companyPhone }}@endif</div></header>
        <h1>Recibo de pagamento</h1>
        <p class="receipt-subtitle">Recebemos o pagamento referente ao lançamento abaixo.</p>
        <table class="receipt-details"><tr><th>Pagador</th><td>{{ $transaction->student?->user?->name ?? 'Cliente' }}</td></tr><tr><th>Descrição</th><td>{{ $transaction->description }}</td></tr><tr><th>Unidade</th><td>{{ $transaction->unit?->name ?? '-' }}</td></tr><tr><th>Vencimento</th><td>{{ $transaction->due_date?->format('d/m/Y') ?? '-' }}</td></tr><tr><th>Forma de pagamento</th><td>{{ ['cash' => 'Dinheiro', 'pix' => 'PIX', 'credit_card' => 'Cartão de crédito', 'debit_card' => 'Cartão de débito', 'bank_transfer' => 'Transferência bancária', 'other' => 'Outro'][$transaction->payment_method] ?? $transaction->payment_method ?? '-' }}</td></tr></table>
        <div class="receipt-total">Valor recebido: R$ {{ number_format((float) $transaction->amount, 2, ',', '.') }}</div>
        <div class="receipt-footer">Documento emitido em {{ now()->format('d/m/Y H:i') }} · {{ $companyName }}</div>
    </main>
    <script>
        function closeReceipt() {
            const fallbackUrl = @json(route('financial.index'));

            window.close();

            // Browsers only close tabs opened by script. The receipt is also
            // opened by a form target, so return to the financial list when
            // the browser refuses to close the tab.
            window.setTimeout(() => {
                if (!window.closed) {
                    window.location.replace(fallbackUrl);
                }
            }, 150);
        }
    </script>
</body>
</html>
