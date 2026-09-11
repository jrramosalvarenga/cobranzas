<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Recibo {{ $receiptNumber }}</title>
    <style>
        @page {
            size: {{ config('cobranzas.printer_width_mm') }}mm auto;
            margin: 0;
        }

        * {
            box-sizing: border-box;
        }

        body {
            width: {{ config('cobranzas.printer_width_mm') }}mm;
            margin: 0 auto;
            padding: 6px 8px;
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            color: #000;
        }

        h1 {
            font-size: 14px;
            text-align: center;
            margin: 0 0 4px;
        }

        .center {
            text-align: center;
        }

        .line {
            border-top: 1px dashed #000;
            margin: 6px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            padding: 2px 0;
            vertical-align: top;
        }

        .right {
            text-align: right;
        }

        .totales td {
            font-weight: bold;
            padding-top: 4px;
        }

        .footer {
            margin-top: 10px;
            text-align: center;
            font-size: 11px;
        }
    </style>
</head>
<body>
    <h1>{{ config('cobranzas.company_name') }}</h1>
    <div class="center">Recibo de pago</div>
    <div class="center">N° {{ $receiptNumber }}</div>

    <div class="line"></div>

    <table>
        <tr><td>Fecha:</td><td class="right">{{ \Illuminate\Support\Carbon::parse($fecha)->format('d/m/Y') }}</td></tr>
        <tr><td>Cliente:</td><td class="right">{{ $cliente->full_name }}</td></tr>
        <tr><td>Documento:</td><td class="right">{{ $cliente->document_number }}</td></tr>
        <tr><td>Dirección:</td><td class="right">{{ $cliente->address_line }}</td></tr>
        <tr><td>Método:</td><td class="right">{{ ucfirst($metodo) }}</td></tr>
        @if ($cajero)
            <tr><td>Atendió:</td><td class="right">{{ $cajero->name }}</td></tr>
        @endif
    </table>

    <div class="line"></div>

    <table>
        @foreach ($pagos as $pago)
            <tr>
                <td>
                    {{ $pago->cuota->contract->serviceType->name }}<br>
                    <small>{{ $pago->cuota->periodLabel() }}</small>
                </td>
                <td class="right">{{ config('cobranzas.currency_symbol') }} {{ number_format($pago->amount, 2) }}</td>
            </tr>
        @endforeach
    </table>

    <div class="line"></div>

    <table class="totales">
        <tr>
            <td>TOTAL</td>
            <td class="right">{{ config('cobranzas.currency_symbol') }} {{ number_format($total, 2) }}</td>
        </tr>
    </table>

    <div class="footer">
        ¡Gracias por su pago!
    </div>

    <script>
        window.onload = function () {
            window.print();
        };
    </script>
</body>
</html>
