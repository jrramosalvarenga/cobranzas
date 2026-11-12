<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Tenant;
use Barryvdh\DomPDF\Facade\Pdf;

class ConstanciaAbonadoPdfController extends Controller
{
    public function __invoke(Contract $contract)
    {
        $contract->load(['client', 'serviceType']);

        abort_unless($contract->status === 'active', 404);

        $tenant = Tenant::find($contract->tenant_id);

        $pdf = Pdf::loadView('reportes.constancia-abonado', [
            'contrato' => $contract,
            'cliente' => $contract->client,
            'servicio' => $contract->serviceType,
            'fecha' => now()->translatedFormat('d \d\e F \d\e Y'),
            'tenant' => $tenant,
        ]);

        $pdf->setPaper('letter', 'portrait');

        return $pdf->stream('constancia-abonado-'.$contract->contract_number.'.pdf');
    }
}
