<?php

namespace App\Livewire\Cuotas;

use App\Models\Client;
use App\Models\Contract;
use App\Models\Cuota;
use Livewire\Attributes\Layout;
use Livewire\Component;

class CobrosAdicionales extends Component
{
    public string $alcance = 'general';

    public string $clientSearch = '';

    /** @var array<int, bool> */
    public array $selectedClientIds = [];

    public string $description = '';

    public string $amount = '';

    public string $due_date = '';

    public ?string $message = null;

    public ?string $error = null;

    public function mount(): void
    {
        $this->due_date = now()->toDateString();
    }

    public function agregarCliente(int $id): void
    {
        $this->selectedClientIds[$id] = true;
        $this->clientSearch = '';
    }

    public function quitarCliente(int $id): void
    {
        unset($this->selectedClientIds[$id]);
    }

    public function updatedAlcance(): void
    {
        $this->selectedClientIds = [];
        $this->clientSearch = '';
    }

    public function generar(): void
    {
        $this->message = null;
        $this->error = null;

        $this->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'due_date' => 'required|date',
        ]);

        if ($this->alcance === 'especifico' && empty($this->selectedClientIds)) {
            $this->error = 'Agregue al menos un cliente para aplicar el cobro.';

            return;
        }

        $query = Contract::where('status', 'active');

        if ($this->alcance === 'especifico') {
            $query->whereIn('client_id', array_keys($this->selectedClientIds));
        }

        $contracts = $query->get();

        if ($contracts->isEmpty()) {
            $this->error = 'No se encontraron contratos activos para aplicar el cobro.';

            return;
        }

        $year = now()->year;
        $generados = 0;

        foreach ($contracts as $contract) {
            Cuota::create([
                'contract_id' => $contract->id,
                'period_year' => $year,
                'period_month' => 0,
                'description' => $this->description,
                'amount' => $this->amount,
                'due_date' => $this->due_date,
                'status' => 'pendiente',
            ]);

            $generados++;
        }

        $this->message = "Cobro adicional generado en {$generados} contrato(s).";
        $this->reset(['description', 'amount', 'selectedClientIds']);
        $this->due_date = now()->toDateString();
    }

    #[Layout('layouts.app')]
    public function render()
    {
        $clientesEncontrados = collect();

        if ($this->alcance === 'especifico' && $this->clientSearch !== '') {
            $clientesEncontrados = Client::query()
                ->whereHas('contracts', fn ($q) => $q->where('status', 'active'))
                ->whereNotIn('id', array_keys($this->selectedClientIds))
                ->where(fn ($q) => $q
                    ->where('full_name', 'like', "%{$this->clientSearch}%")
                    ->orWhere('document_number', 'like', "%{$this->clientSearch}%"))
                ->orderBy('full_name')
                ->limit(10)
                ->get();
        }

        $clientesSeleccionados = ! empty($this->selectedClientIds)
            ? Client::whereIn('id', array_keys($this->selectedClientIds))->orderBy('full_name')->get()
            : collect();

        return view('livewire.cuotas.cobros-adicionales', [
            'clientesEncontrados' => $clientesEncontrados,
            'clientesSeleccionados' => $clientesSeleccionados,
        ]);
    }
}
