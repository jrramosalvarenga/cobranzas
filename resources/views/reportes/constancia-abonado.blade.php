<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Constancia de Abonado</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            color: #000;
            margin: 0;
            padding: 72px 72px 60px;
            line-height: 2;
        }

        .header-row {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }

        .header-logo {
            display: table-cell;
            width: 70px;
            vertical-align: middle;
        }

        .header-logo img {
            width: 60px;
            height: 60px;
            object-fit: contain;
        }

        .header-center {
            display: table-cell;
            text-align: center;
            vertical-align: middle;
        }

        .header-center .org {
            font-size: 11pt;
            font-weight: bold;
        }

        .header-center .company {
            font-size: 14pt;
            font-weight: bold;
            text-transform: uppercase;
        }

        .header-center .sub {
            font-size: 10pt;
            color: #555;
        }

        .title {
            text-align: center;
            font-size: 14pt;
            font-weight: bold;
            text-transform: uppercase;
            margin: 30px 0 20px;
        }

        .body-text {
            text-align: justify;
            text-indent: 36pt;
        }

        .body-text p {
            margin: 0 0 0 0;
        }

        .dato {
            font-weight: bold;
        }

        .firma-section {
            margin-top: 80px;
            text-align: center;
        }

        .firma-linea {
            display: inline-block;
            width: 250px;
            border-top: 1px solid #000;
            padding-top: 5px;
            font-size: 10pt;
        }

        .footer {
            text-align: center;
            font-size: 9pt;
            color: #999;
            position: fixed;
            bottom: 40px;
            left: 72px;
            right: 72px;
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
            <div class="sub">Constancia generada el {{ $fecha }}</div>
        </div>
        <div class="header-logo" style="text-align: right;">
            <img src="{{ public_path('images/escudo-honduras.png') }}">
        </div>
    </div>

    <div class="title">Constancia de Abonado</div>

    <div class="body-text">
        <p>
            Por medio de la presente se hace constar que
            <span class="dato">{{ $cliente->full_name }}</span>,
            identificado/a con documento N° <span class="dato">{{ $cliente->document_number }}</span>,
            con domicilio en <span class="dato">{{ $cliente->address_line }}</span>,
            es abonado/a activo/a de <span class="dato">{{ $companyName }}</span>.
        </p>

        <p>
            El abonado cuenta con el contrato N° <span class="dato">{{ $contrato->contract_number }}</span>,
            correspondiente al servicio de <span class="dato">{{ $servicio->name }}</span>,
            con fecha de inicio <span class="dato">{{ $contrato->start_date->translatedFormat('d \d\e F \d\e Y') }}</span>
            y una tarifa mensual de <span class="dato">{{ config('cobranzas.currency_symbol') }} {{ number_format($contrato->effectiveMonthlyFee(), 2) }}</span>.
        </p>

        <p>
            Se extiende la presente constancia a solicitud del interesado/a para los fines que estime conveniente.
        </p>

        <p>
            Dada en San Francisco de la Paz, Olancho, a los {{ now()->translatedFormat('d') }} días
            del mes de {{ now()->translatedFormat('F') }} de {{ now()->translatedFormat('Y') }}.
        </p>
    </div>

    <div class="firma-section">
        <div class="firma-linea">Firma y sello</div>
    </div>

    <div class="footer">{{ $companyName }} &mdash; San Francisco de la Paz, Olancho</div>
</body>
</html>
