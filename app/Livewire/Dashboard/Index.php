<?php

namespace App\Livewire\Dashboard;

use App\Models\Client;
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

        return view('livewire.dashboard.index', [
            'totalClientes' => Client::count(),
            'contratosActivos' => Contract::where('status', 'active')->count(),
            'cuotasPendientesCount' => $cuotasPendientes->count(),
            'cuotasPendientesMonto' => $cuotasPendientes->sum(fn ($c) => (float) $c->saldo()),
            'cuotasVencidasCount' => $cuotasVencidas->count(),
            'cobradoEsteMes' => $cobradoEsteMes,
            'ultimosPagos' => $ultimosPagos,
        ]);
    }
}
