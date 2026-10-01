<?php

namespace App\Livewire\Dashboard;

use App\Models\AccountingEntry;
use App\Models\Contract;
use App\Models\Cuota;
use App\Models\Payment;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Index extends Component
{
    #[Layout('layouts.app')]
    public function render()
    {
        $year = now()->year;
        $month = now()->month;

        $ingresosPorDia = collect(range(4, 0))->map(function (int $daysAgo) {
            $fecha = now()->subDays($daysAgo)->startOfDay();

            $ingresoCobranza = (string) Payment::whereDate('payment_date', $fecha)->sum('amount');
            $ingresoOtros = (string) AccountingEntry::ingresos()->whereDate('entry_date', $fecha)->sum('amount');

            return [
                'fecha' => $fecha,
                'total' => bcadd($ingresoCobranza, $ingresoOtros, 2),
            ];
        });

        $ingresosHoy = $ingresosPorDia->last()['total'];
        $ingresosUltimos5Dias = $ingresosPorDia->reduce(fn ($carry, $dia) => bcadd($carry, $dia['total'], 2), '0.00');

        $cuotasMes = Cuota::query()
            ->where('period_year', $year)
            ->where('period_month', $month)
            ->get();

        $cuotasPendientes = $cuotasMes->whereIn('status', ['pendiente', 'vencida']);
        $cuotasVencidas = $cuotasMes->filter(fn ($c) => $c->displayStatus() === 'vencida');

        $cobradoEsteMes = Payment::query()
            ->whereYear('payment_date', $year)
            ->whereMonth('payment_date', $month)
            ->sum('amount');

        $ultimosPagos = Payment::query()
            ->with(['cuota.contract.client', 'cuota.contract.serviceType'])
            ->latest('id')
            ->limit(8)
            ->get();

        $totalMorosos = Cuota::query()
            ->whereIn('cuotas.status', ['pendiente', 'parcial', 'vencida'])
            ->whereDate('cuotas.due_date', '<', now()->startOfDay())
            ->join('contracts', 'cuotas.contract_id', '=', 'contracts.id')
            ->distinct()
            ->count('contracts.client_id');

        return view('livewire.dashboard.index', [
            'totalMorosos' => $totalMorosos,
            'contratosActivos' => Contract::where('status', 'active')->count(),
            'cuotasPendientesCount' => $cuotasPendientes->count(),
            'cuotasPendientesMonto' => $cuotasPendientes->sum(fn ($c) => (float) $c->saldo()),
            'cuotasVencidasCount' => $cuotasVencidas->count(),
            'cobradoEsteMes' => $cobradoEsteMes,
            'ultimosPagos' => $ultimosPagos,
            'ingresosHoy' => $ingresosHoy,
            'ingresosUltimos5Dias' => $ingresosUltimos5Dias,
            'ingresosPorDia' => $ingresosPorDia,
        ]);
    }
}
