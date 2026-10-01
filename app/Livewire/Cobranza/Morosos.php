<?php

namespace App\Livewire\Cobranza;

use App\Models\Cuota;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Morosos extends Component
{
    public string $search = '';

    #[Layout('layouts.app')]
    public function render()
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
            ->when($this->search, fn ($col) => $col->filter(
                fn ($m) => str_contains(mb_strtolower($m->cliente->full_name), mb_strtolower($this->search))
                    || str_contains($m->cliente->document_number, $this->search)
            ))
            ->sortByDesc('diasAtraso')
            ->values();

        return view('livewire.cobranza.morosos', [
            'morosos' => $morosos,
            'totalMorosos' => $morosos->count(),
            'totalDeudaGlobal' => $morosos->reduce(fn ($carry, $m) => bcadd($carry, $m->totalDeuda, 2), '0.00'),
        ]);
    }
}
