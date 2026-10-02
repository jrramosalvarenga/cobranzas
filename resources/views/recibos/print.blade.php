<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Recibo {{ $receiptNumber }}</title>
    <style id="page-size"></style>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            width: {{ config('cobranzas.printer_width_mm') }}mm;
            margin: 0 auto;
            padding: 4px 4px;
            font-family: 'Courier New', Courier, monospace;
            font-size: 10px;
            color: #000;
            line-height: 1.3;
        }

        h1 {
            font-size: 11px;
            text-align: center;
            margin: 0 0 2px;
        }

        .center {
            text-align: center;
        }

        .line {
            border-top: 1px dashed #000;
            margin: 4px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            padding: 1px 0;
            vertical-align: top;
            font-size: 10px;
        }

        .label {
            font-size: 9px;
            white-space: nowrap;
        }

        .value {
            text-align: right;
            word-break: break-word;
        }

        .right {
            text-align: right;
        }

        .totales td {
            font-weight: bold;
            padding-top: 3px;
            font-size: 11px;
        }

        .footer {
            margin-top: 6px;
            text-align: center;
            font-size: 9px;
        }

        .header-row {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
            margin-bottom: 2px;
        }

        .header-row img {
            width: 36px;
            height: 36px;
            object-fit: contain;
        }

        .header-text {
            flex: 1;
            text-align: center;
            min-width: 0;
        }
    </style>
</head>
<body>
    <div class="header-row">
        <img src="{{ asset('images/escudo-junta-agua.jpeg') }}" alt="">
        <div class="header-text">
            <div style="font-size: 8px; font-weight: bold; line-height: 1.2;">JUNTA DE AGUA DE SAN FRANCISCO DE LA PAZ, OLANCHO.</div>
            <div style="font-size: 11px; font-weight: bold; margin: 1px 0;">{{ config('cobranzas.company_name') }}</div>
            <div style="font-size: 9px;">Recibo de pago</div>
            <div style="font-size: 9px;">N° {{ $receiptNumber }}</div>
        </div>
        <img src="{{ asset('images/escudo-honduras.webp') }}" alt="">
    </div>

    <div class="line"></div>

    <table>
        <tr><td class="label">Fecha:</td><td class="value">{{ \Illuminate\Support\Carbon::parse($fecha)->format('d/m/Y') }}</td></tr>
        <tr><td class="label">Cliente:</td><td class="value">{{ $cliente->full_name }}</td></tr>
        <tr><td class="label">Doc:</td><td class="value">{{ $cliente->document_number }}</td></tr>
        <tr><td class="label">Dir:</td><td class="value">{{ $cliente->address_line }}</td></tr>
        <tr><td class="label">Método:</td><td class="value">{{ ucfirst($metodo) }}</td></tr>
        @if ($cajero)
            <tr><td class="label">Atendió:</td><td class="value">{{ $cajero->name }}</td></tr>
        @endif
    </table>

    <div class="line"></div>

    <table>
        @foreach ($pagos as $pago)
            <tr>
                <td style="font-size: 9px;">
                    {{ $pago->cuota->contract->serviceType->name }}<br>
                    <small>{{ $pago->cuota->periodLabel() }}</small>
                </td>
                <td class="right" style="white-space: nowrap;">{{ config('cobranzas.currency_symbol') }} {{ number_format($pago->amount, 2) }}</td>
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

    <div class="footer" style="font-style: italic;">
        No hay vida sin agua
    </div>

    <script>
        window.onload = function () {
            var widthMm = {{ config('cobranzas.printer_width_mm') }};
            var heightPx = document.body.scrollHeight + 20;
            var heightMm = Math.ceil(heightPx * 25.4 / 96);

            document.getElementById('page-size').textContent =
                '@page { size: ' + widthMm + 'mm ' + heightMm + 'mm; margin: 0; }';

            setTimeout(function () { window.print(); }, 500);
        };
    </script>
</body>
</html>
