<?php

namespace App\Http\Controllers;

use App\Models\Cuota;
use Barryvdh\DomPDF\Facade\Pdf;

class MorososPdfController extends Controller
{
    public function __invoke()
    {
        $cuotasVencidas = Cuota::query()
            ->whereIn('status', ['pendiente', 'parcial', 'vencida'])
            ->whereDate('due_date', '<', now()->startOfDay())
            ->with(['contract.client', 'contract.serviceType'])
            ->get();

        $morosos = $cuotasVencidas
            ->groupBy(fn (Cuota $c) => $c->contract->client_id)
            ->map(function ($cuotas) {
                $cliente = $cuotas->first()->contract->client;
                $totalDeuda = $cuotas->reduce(fn ($carry, $c) => bcadd($carry, $c->saldo(), 2), '0.00');
                $cuotasMasAntigua = $cuotas->sortBy('due_date')->first();
                $diasAtraso = (int) $cuotasMasAntigua->due_date->diffInDays(now());

                return (object) [
                    'cliente' => $cliente,
                    'cuotas' => $cuotas->sortBy('due_date')->values(),
                    'totalDeuda' => $totalDeuda,
                    'cuotasVencidas' => $cuotas->count(),
                    'diasAtraso' => $diasAtraso,
                ];
            })
            ->sortByDesc('diasAtraso')
            ->values();

        $totalDeudaGlobal = $morosos->reduce(fn ($carry, $m) => bcadd($carry, $m->totalDeuda, 2), '0.00');

        $pdf = Pdf::loadView('reportes.morosos', [
            'morosos' => $morosos,
            'totalMorosos' => $morosos->count(),
            'totalDeudaGlobal' => $totalDeudaGlobal,
            'fecha' => now()->translatedFormat('d/m/Y H:i'),
            'companyName' => config('cobranzas.company_name'),
            'currencySymbol' => config('cobranzas.currency_symbol'),
        ]);

        $pdf->setPaper('letter', 'portrait');

        return $pdf->stream('morosos-'.now()->format('Y-m-d').'.pdf');
    }
}
