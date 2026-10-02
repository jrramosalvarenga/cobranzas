<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Estado de Resultados {{ $mesLabel }}</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 9px;
            color: #000;
            margin: 0;
            padding: 25px 30px;
        }

        .header-row {
            display: table;
            width: 100%;
            margin-bottom: 10px;
        }

        .header-logo {
            display: table-cell;
            width: 60px;
            vertical-align: middle;
        }

        .header-logo img {
            width: 50px;
            height: 50px;
            object-fit: contain;
        }

        .header-center {
            display: table-cell;
            text-align: center;
            vertical-align: middle;
        }

        .header-center .org {
            font-size: 10px;
            font-weight: bold;
        }

        .header-center .company {
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .header-center .title {
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            margin-top: 4px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            border: 1px solid #000;
            padding: 3px 4px;
            font-size: 8px;
            text-transform: uppercase;
            text-align: center;
            background: #f5f5f5;
        }

        td {
            border: 1px solid #000;
            padding: 2px 4px;
            font-size: 8px;
            vertical-align: top;
        }

        td.fecha {
            width: 60px;
            white-space: nowrap;
            text-align: center;
        }

        td.desc {
            text-align: left;
        }

        td.num {
            width: 65px;
            text-align: right;
            white-space: nowrap;
        }

        .saldo-header td {
            font-weight: bold;
            border-bottom: 2px solid #000;
        }

        .total-row td {
            font-weight: bold;
            border-top: 2px solid #000;
            font-size: 9px;
        }

        .footer {
            text-align: center;
            font-size: 7px;
            color: #999;
            margin-top: 8px;
        }
    </style>
</head>
<body>
    <div class="header-row">
        <div class="header-logo">
            <img src="{{ public_path('images/escudo-junta-agua.jpeg') }}">
        </div>
        <div class="header-center">
            <div class="org">JUNTA DE AGUA DE SAN FRANCISCO DE LA PAZ, OLANCHO.</div>
            <div class="company">{{ $companyName }}</div>
            <div class="title">ESTADO DE RESULTADOS MES DE {{ $mesLabel }}</div>
        </div>
        <div class="header-logo" style="text-align: right;">
            <img src="{{ public_path('images/escudo-honduras.png') }}">
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Descripción</th>
                <th>Ingresos</th>
                <th>Egresos</th>
                <th>Saldo</th>
            </tr>
        </thead>
        <tbody>
            <tr class="saldo-header">
                <td class="fecha"></td>
                <td class="desc">SALDO ANTERIOR</td>
                <td class="num"></td>
                <td class="num"></td>
                <td class="num">{{ number_format((float) $saldoAnterior, 2) }}</td>
            </tr>

            @php $saldo = (float) $saldoAnterior; @endphp

            @foreach ($movimientos as $mov)
                @php
                    if ($mov->tipo === 'ingreso') {
                        $saldo += $mov->monto;
                    } else {
                        $saldo -= $mov->monto;
                    }
                @endphp
                <tr>
                    <td class="fecha">{{ $mov->fecha->format('d/m/Y') }}</td>
                    <td class="desc">{{ $mov->descripcion }}</td>
                    <td class="num">{{ $mov->tipo === 'ingreso' ? number_format($mov->monto, 2) : '' }}</td>
                    <td class="num">{{ $mov->tipo === 'egreso' ? number_format($mov->monto, 2) : '' }}</td>
                    <td class="num">{{ number_format($saldo, 2) }}</td>
                </tr>
            @endforeach

            <tr class="total-row">
                <td class="fecha"></td>
                <td class="desc">TOTAL</td>
                <td class="num">{{ number_format($totalIngresos, 2) }}</td>
                <td class="num">{{ number_format($totalEgresos, 2) }}</td>
                <td class="num">{{ number_format($saldo, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">{{ $companyName }} &mdash; Generado el {{ now()->translatedFormat('d/m/Y H:i') }}</div>
</body>
</html>
