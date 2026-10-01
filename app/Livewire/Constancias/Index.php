<?php

namespace App\Livewire\Constancias;

use App\Models\Client;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Index extends Component
{
    public string $search = '';

    /** @var Collection<int, Client> */
    public $clientes;

    public function mount(): void
    {
        $this->clientes = collect();
    }

    public function updatedSearch(): void
    {
        if (strlen($this->search) < 2) {
            $this->clientes = collect();

            return;
        }

        $this->clientes = Client::query()
            ->whereHas('contracts', fn ($q) => $q->where('status', 'active'))
            ->where(fn ($q) => $q
                ->where('full_name', 'ilike', "%{$this->search}%")
                ->orWhere('document_number', 'ilike', "%{$this->search}%"))
            ->with(['contracts' => fn ($q) => $q->where('status', 'active')->with('serviceType')])
            ->limit(20)
            ->get();
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.constancias.index');
    }
}
