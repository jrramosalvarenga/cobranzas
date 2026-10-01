<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Constancia de Abonado</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #000; margin: 0; padding: 40px 50px; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { font-size: 16px; margin: 0; text-transform: uppercase; }
        .header .company { font-size: 14px; font-weight: bold; margin-bottom: 4px; }
        .header .sub { font-size: 10px; color: #666; }
        .title { text-align: center; font-size: 15px; font-weight: bold; text-transform: uppercase; margin: 30px 0 20px; text-decoration: underline; }
        .body { line-height: 1.8; text-align: justify; margin: 0 20px; }
        .body .dato { font-weight: bold; }
        .firma { margin-top: 80px; text-align: center; }
        .firma .linea { display: inline-block; width: 250px; border-top: 1px solid #000; padding-top: 4px; font-size: 10px; }
        .footer { text-align: center; font-size: 8px; color: #999; position: fixed; bottom: 20px; left: 0; right: 0; }
    </style>
</head>
<body>
    <div class="header">
        <div style="font-size: 11px; font-weight: bold;">JUNTA DE AGUA DE SAN FRANCISCO DE LA PAZ, OLANCHO.</div>
        <div class="company">{{ $companyName }}</div>
        <div class="sub">Constancia generada el {{ $fecha }}</div>
    </div>

    <div class="title">Constancia de Abonado</div>

    <div class="body">
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
            con fecha de inicio <span class="dato">{{ $contrato->start_date->translatedFormat('d/m/Y') }}</span>
            y una tarifa mensual de <span class="dato">{{ config('cobranzas.currency_symbol') }} {{ number_format($contrato->effectiveMonthlyFee(), 2) }}</span>.
        </p>

        <p>
            Se extiende la presente constancia a solicitud del interesado/a para los fines que estime conveniente.
        </p>
    </div>

    <div class="firma">
        <div class="linea">Firma y sello</div>
    </div>

    <div class="footer">{{ $companyName }} &mdash; Constancia de abonado generada el {{ $fecha }}</div>
</body>
</html>
