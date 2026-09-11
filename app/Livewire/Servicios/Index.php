<?php

namespace App\Livewire\Servicios;

use App\Models\ServiceType;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $search = '';

    public bool $showModal = false;

    public ?int $editingId = null;

    public string $name = '';

    public string $description = '';

    public string $monthly_price = '';

    public bool $active = true;

    public ?string $deleteError = null;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function create(): void
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit(int $id): void
    {
        $servicio = ServiceType::findOrFail($id);

        $this->editingId = $servicio->id;
        $this->name = $servicio->name;
        $this->description = (string) $servicio->description;
        $this->monthly_price = (string) $servicio->monthly_price;
        $this->active = $servicio->active;
        $this->showModal = true;
    }

    public function save(): void
    {
        $data = $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'monthly_price' => 'required|numeric|min:0',
            'active' => 'boolean',
        ]);

        ServiceType::updateOrCreate(['id' => $this->editingId], $data);

        $this->showModal = false;
        $this->resetForm();
    }

    public function delete(int $id): void
    {
        $servicio = ServiceType::withCount('contracts')->findOrFail($id);

        if ($servicio->contracts_count > 0) {
            $this->deleteError = 'No se puede eliminar un servicio que tiene contratos asociados.';

            return;
        }

        $servicio->delete();
    }

    public function resetForm(): void
    {
        $this->reset(['editingId', 'name', 'description', 'monthly_price', 'active']);
        $this->active = true;
        $this->resetErrorBag();
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.servicios.index', [
            'servicios' => ServiceType::query()
                ->when($this->search, fn ($q) => $q->where('name', 'like', "%{$this->search}%"))
                ->orderBy('name')
                ->paginate(10),
        ]);
    }
}
