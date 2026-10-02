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
            ->get()
            ->groupBy(fn (Payment $p) => $p->payment_date->toDateString())
            ->map(fn ($group, $date) => (object) [
                'fecha' => Carbon::parse($date),
                'descripcion' => 'Cobros domiciliares mes de '.Carbon::parse($date)->translatedFormat('F Y').' ('.Carbon::parse($date)->format('d/m/Y').')',
                'tipo' => 'ingreso',
                'monto' => (float) $group->sum('amount'),
            ])
            ->values();

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
}
