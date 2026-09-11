<?php

namespace App\Livewire\Cuotas;

use App\Models\Cuota;
use App\Services\CuotaGenerator;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $search = '';

    public string $statusFilter = '';

    public int $year;

    public int $month;

    public ?string $message = null;

    public function mount(): void
    {
        $this->year = now()->year;
        $this->month = now()->month;
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function updatingYear(): void
    {
        $this->resetPage();
    }

    public function updatingMonth(): void
    {
        $this->resetPage();
    }

    public function generar(CuotaGenerator $generator): void
    {
        $cantidad = $generator->generarParaPeriodo($this->year, $this->month);

        $this->message = $cantidad > 0
            ? "Se generaron {$cantidad} cuota(s) para el periodo seleccionado."
            : 'No hay contratos activos pendientes de generar cuota para este periodo (o ya fueron generadas).';
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.cuotas.index', [
            'cuotas' => Cuota::query()
                ->with(['contract.client', 'contract.serviceType'])
                ->where('period_year', $this->year)
                ->where('period_month', $this->month)
                ->when($this->statusFilter, fn ($q) => $q->where('status', $this->statusFilter))
                ->when($this->search, fn ($q) => $q->whereHas('contract.client', fn ($c) => $c->where('full_name', 'like', "%{$this->search}%")))
                ->latest('due_date')
                ->paginate(15),
        ]);
    }
}
