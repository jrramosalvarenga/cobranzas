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
        @if($tenant?->logoPath())
            <div class="header-logo">
                <img src="{{ $tenant->logoPath() }}">
            </div>
        @endif
        <div class="header-center">
            <div class="org">{{ $tenant?->name ?? '' }}</div>
            <div class="company">{{ $tenant?->companyName() ?? config('cobranzas.company_name') }}</div>
            <div class="sub">Constancia generada el {{ $fecha }}</div>
        </div>
        @if($tenant?->logoSecondaryPath())
            <div class="header-logo" style="text-align: right;">
                <img src="{{ $tenant->logoSecondaryPath() }}">
            </div>
        @endif
    </div>

    <div class="title">Constancia de Abonado</div>

    <div class="body-text">
        <p>
            Por medio de la presente se hace constar que
            <span class="dato">{{ $cliente->full_name }}</span>,
            identificado/a con documento N° <span class="dato">{{ $cliente->document_number }}</span>,
            con domicilio en <span class="dato">{{ $cliente->address_line }}</span>,
            es abonado/a activo/a de <span class="dato">{{ $tenant?->companyName() ?? config('cobranzas.company_name') }}</span>.
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
            Dada en {{ $tenant?->address() ?: 'la localidad' }}, a los {{ now()->translatedFormat('d') }} días
            del mes de {{ now()->translatedFormat('F') }} de {{ now()->translatedFormat('Y') }}.
        </p>
    </div>

    <div class="firma-section">
        <div class="firma-linea">Firma y sello</div>
    </div>

    <div class="footer">{{ $tenant?->companyName() ?? config('cobranzas.company_name') }}@if($tenant?->address()) &mdash; {{ $tenant->address() }}@endif</div>
</body>
</html>
