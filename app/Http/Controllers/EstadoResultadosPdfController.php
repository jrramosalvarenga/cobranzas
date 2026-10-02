<?php

namespace App\Http\Controllers;

use App\Models\AccountingEntry;
use App\Models\Payment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class EstadoResultadosPdfController extends Controller
{
    public function __invoke(Request $request)
    {
        $desde = $request->query('desde', now()->startOfMonth()->toDateString());
        $hasta = $request->query('hasta', now()->toDateString());

        $saldoAnterior = $this->calcularSaldoAnterior($desde);

        $movimientos = $this->obtenerMovimientos($desde, $hasta);

        $totalIngresos = $movimientos->where('tipo', 'ingreso')->sum('monto');
        $totalEgresos = $movimientos->where('tipo', 'egreso')->sum('monto');

        $mesLabel = Carbon::parse($desde)->translatedFormat('F Y');

        $pdf = Pdf::loadView('reportes.estado-resultados', [
            'movimientos' => $movimientos,
            'saldoAnterior' => $saldoAnterior,
            'totalIngresos' => $totalIngresos,
            'totalEgresos' => $totalEgresos,
            'mesLabel' => mb_strtoupper($mesLabel),
            'desde' => $desde,
            'hasta' => $hasta,
            'companyName' => config('cobranzas.company_name'),
            'currencySymbol' => config('cobranzas.currency_symbol'),
        ]);

        $pdf->setPaper('letter', 'portrait');

        return $pdf->stream('estado-resultados-'.Carbon::parse($desde)->format('Y-m').'.pdf');
    }

    private function calcularSaldoAnterior(string $desde): string
    {
        $pagosAnteriores = Payment::query()
            ->active()
            ->whereDate('payment_date', '<', $desde)
            ->sum('amount');

        $ingresosAnteriores = AccountingEntry::query()
            ->ingresos()
            ->whereDate('entry_date', '<', $desde)
            ->sum('amount');

        $egresosAnteriores = AccountingEntry::query()
            ->egresos()
            ->whereDate('entry_date', '<', $desde)
            ->sum('amount');

        $total = bcadd((string) $pagosAnteriores, (string) $ingresosAnteriores, 2);

        return bcsub($total, (string) $egresosAnteriores, 2);
    }

    private function obtenerMovimientos(string $desde, string $hasta)
    {
        $pagos = Payment::query()
            ->active()
            ->whereDate('payment_date', '>=', $desde)
            ->whereDate('payment_date', '<=', $hasta)
            ->with(['cuota.contract.client', 'cuota.contract.serviceType'])
            ->get()
            ->map(fn (Payment $p) => (object) [
                'fecha' => $p->payment_date,
                'descripcion' => $this->descripcionPago($p),
                'tipo' => 'ingreso',
                'monto' => (float) $p->amount,
            ]);

        $entradas = AccountingEntry::query()
            ->whereDate('entry_date', '>=', $desde)
            ->whereDate('entry_date', '<=', $hasta)
            ->get()
            ->map(fn (AccountingEntry $e) => (object) [
                'fecha' => $e->entry_date,
                'descripcion' => $e->description ?: $e->category,
                'tipo' => $e->type === 'ingreso' ? 'ingreso' : 'egreso',
                'monto' => (float) $e->amount,
            ]);

        return $pagos->concat($entradas)->sortBy('fecha')->values();
    }

    private function descripcionPago(Payment $p): string
    {
        $client = $p->cuota->contract->client->full_name;
        $service = $p->cuota->contract->serviceType->name;
        $contract = $p->cuota->contract;
        $desc = $contract->description ? " /{$contract->description}" : '';

        return "Deposito a cuenta por cobro domiciliar /{$service}{$desc} {$client}";
    }
}
