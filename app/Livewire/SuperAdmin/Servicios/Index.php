<?php

namespace App\Livewire\SuperAdmin\Servicios;

use App\Models\Scopes\TenantScope;
use App\Models\ServiceType;
use App\Models\Tenant;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $search = '';

    public ?int $filterTenantId = null;

    public bool $showModal = false;

    public ?int $editingId = null;

    public ?int $tenant_id = null;

    public string $name = '';

    public string $description = '';

    public string $monthly_price = '';

    public bool $active = true;

    public ?string $deleteError = null;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilterTenantId(): void
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
        $servicio = ServiceType::withoutGlobalScope(TenantScope::class)->findOrFail($id);

        $this->editingId = $servicio->id;
        $this->tenant_id = $servicio->tenant_id;
        $this->name = $servicio->name;
        $this->description = (string) $servicio->description;
        $this->monthly_price = (string) $servicio->monthly_price;
        $this->active = $servicio->active;
        $this->showModal = true;
    }

    public function save(): void
    {
        $data = $this->validate([
            'tenant_id' => 'required|exists:tenants,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'monthly_price' => 'required|numeric|min:0',
            'active' => 'boolean',
        ]);

        ServiceType::withoutGlobalScope(TenantScope::class)
            ->updateOrCreate(['id' => $this->editingId], $data);

        $this->showModal = false;
        $this->resetForm();
    }

    public function delete(int $id): void
    {
        $servicio = ServiceType::withoutGlobalScope(TenantScope::class)
            ->withCount('contracts')
            ->findOrFail($id);

        if ($servicio->contracts_count > 0) {
            $this->deleteError = 'No se puede eliminar un servicio que tiene contratos asociados.';

            return;
        }

        $servicio->delete();
    }

    public function resetForm(): void
    {
        $this->reset(['editingId', 'tenant_id', 'name', 'description', 'monthly_price', 'active']);
        $this->active = true;
        $this->resetErrorBag();
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.super-admin.servicios.index', [
            'servicios' => ServiceType::withoutGlobalScope(TenantScope::class)
                ->with('tenant')
                ->when($this->search, fn ($q) => $q->where('name', 'like', "%{$this->search}%"))
                ->when($this->filterTenantId, fn ($q) => $q->where('tenant_id', $this->filterTenantId))
                ->orderBy('tenant_id')
                ->orderBy('name')
                ->paginate(15),
            'tenants' => Tenant::orderBy('name')->get(),
        ]);
    }
}
