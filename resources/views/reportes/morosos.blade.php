<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Clientes morosos</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 10px;
            color: #000;
            margin: 0;
            padding: 20px 25px;
        }

        h1 {
            font-size: 14px;
            margin: 0;
            text-align: center;
        }

        .sub {
            text-align: center;
            font-size: 9px;
            color: #666;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            text-align: left;
            padding: 4px 5px;
            border-bottom: 2px solid #000;
            font-size: 8px;
            text-transform: uppercase;
        }

        th.r {
            text-align: right;
        }

        td {
            padding: 3px 5px;
            border-bottom: 1px solid #ccc;
            font-size: 9px;
        }

        td.r {
            text-align: right;
        }

        .total {
            border-top: 2px solid #000;
            font-weight: bold;
        }

        .footer {
            text-align: center;
            font-size: 8px;
            color: #999;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div style="text-align: center; font-size: 10px; font-weight: bold; margin-bottom: 2px;">JUNTA DE AGUA DE SAN FRANCISCO DE LA PAZ, OLANCHO.</div>
    <h1>{{ $companyName }} &mdash; Clientes Morosos</h1>
    <div class="sub">{{ $fecha }} &middot; {{ $totalMorosos }} cliente(s) &middot; Deuda total: {{ $currencySymbol }} {{ number_format($totalDeudaGlobal, 2) }}</div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Cliente</th>
                <th>Documento</th>
                <th>Dirección</th>
                <th class="r">Cuotas</th>
                <th class="r">Días atraso</th>
                <th class="r">Deuda</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($morosos as $i => $moroso)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $moroso->cliente->full_name }}</td>
                    <td>{{ $moroso->cliente->document_number }}</td>
                    <td>{{ $moroso->cliente->address_line }}</td>
                    <td class="r">{{ $moroso->cuotasVencidas }}</td>
                    <td class="r">{{ $moroso->diasAtraso }}</td>
                    <td class="r">{{ $currencySymbol }} {{ number_format($moroso->totalDeuda, 2) }}</td>
                </tr>
            @endforeach
            @if ($morosos->isNotEmpty())
                <tr class="total">
                    <td colspan="4">Total</td>
                    <td class="r">{{ $morosos->sum('cuotasVencidas') }}</td>
                    <td></td>
                    <td class="r">{{ $currencySymbol }} {{ number_format($totalDeudaGlobal, 2) }}</td>
                </tr>
            @endif
        </tbody>
    </table>

    @if ($morosos->isEmpty())
        <p style="text-align: center; color: #888; margin-top: 30px;">No hay clientes morosos a la fecha.</p>
    @endif

    <div class="footer">Generado el {{ $fecha }}</div>
</body>
</html>
