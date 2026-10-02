<?php

namespace App\Livewire\Contabilidad;

use App\Models\AccountingEntry;
use App\Models\Payment;
use Livewire\Attributes\Layout;
use Livewire\Component;

class EstadoCuenta extends Component
{
    public string $desde = '';

    public string $hasta = '';

    public function mount(): void
    {
        $this->desde = now()->startOfMonth()->format('Y-m-d');
        $this->hasta = now()->format('Y-m-d');
    }

    #[Layout('layouts.app')]
    public function render()
    {
        $desde = $this->desde ?: now()->format('Y-m-d');
        $hasta = $this->hasta ?: $desde;

        $pagosCobranza = Payment::query()
            ->active()
            ->whereDate('payment_date', '>=', $desde)
            ->whereDate('payment_date', '<=', $hasta)
            ->with(['cuota.contract.client', 'cuota.contract.serviceType'])
            ->orderBy('payment_date')
            ->get();

        $movimientos = AccountingEntry::query()
            ->whereDate('entry_date', '>=', $desde)
            ->whereDate('entry_date', '<=', $hasta)
            ->orderBy('entry_date')
            ->get();

        $ingresosCobranza = bcadd((string) $pagosCobranza->sum('amount'), '0.00', 2);
        $ingresosOtros = bcadd((string) $movimientos->where('type', 'ingreso')->sum('amount'), '0.00', 2);
        $totalEgresos = bcadd((string) $movimientos->where('type', 'egreso')->sum('amount'), '0.00', 2);

        $totalIngresos = bcadd($ingresosCobranza, $ingresosOtros, 2);
        $balance = bcsub($totalIngresos, $totalEgresos, 2);

        return view('livewire.contabilidad.estado-cuenta', [
            'pagosCobranza' => $pagosCobranza,
            'movimientos' => $movimientos,
            'ingresosCobranza' => $ingresosCobranza,
            'ingresosOtros' => $ingresosOtros,
            'totalIngresos' => $totalIngresos,
            'totalEgresos' => $totalEgresos,
            'balance' => $balance,
        ]);
    }
}
