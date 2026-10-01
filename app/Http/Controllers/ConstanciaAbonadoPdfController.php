<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use Barryvdh\DomPDF\Facade\Pdf;

class ConstanciaAbonadoPdfController extends Controller
{
    public function __invoke(Contract $contract)
    {
        $contract->load(['client', 'serviceType']);

        abort_unless($contract->status === 'active', 404);

        $pdf = Pdf::loadView('reportes.constancia-abonado', [
            'contrato' => $contract,
            'cliente' => $contract->client,
            'servicio' => $contract->serviceType,
            'fecha' => now()->translatedFormat('d \d\e F \d\e Y'),
            'companyName' => config('cobranzas.company_name'),
        ]);

        $pdf->setPaper('letter', 'portrait');

        return $pdf->stream('constancia-abonado-'.$contract->contract_number.'.pdf');
    }
}
