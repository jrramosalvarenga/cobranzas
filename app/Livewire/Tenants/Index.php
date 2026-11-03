<?php

namespace App\Livewire\Tenants;

use App\Models\Tenant;
use Illuminate\Support\Str;
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

    public string $slug = '';

    public bool $active = true;

    public ?string $deleteError = null;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatedName(string $value): void
    {
        if (! $this->editingId) {
            $this->slug = Str::slug($value);
        }
    }

    public function create(): void
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit(int $id): void
    {
        $tenant = Tenant::findOrFail($id);

        $this->editingId = $tenant->id;
        $this->name = $tenant->name;
        $this->slug = $tenant->slug;
        $this->active = $tenant->active;
        $this->showModal = true;
    }

    public function save(): void
    {
        $uniqueSlug = $this->editingId
            ? 'unique:tenants,slug,'.$this->editingId
            : 'unique:tenants,slug';

        $data = $this->validate([
            'name' => 'required|string|max:255',
            'slug' => ['required', 'string', 'max:255', $uniqueSlug],
            'active' => 'boolean',
        ]);

        $data['slug'] = Str::slug($data['slug']);

        Tenant::updateOrCreate(['id' => $this->editingId], $data);

        $this->showModal = false;
        $this->resetForm();
    }

    public function delete(int $id): void
    {
        $tenant = Tenant::withCount('users')->findOrFail($id);

        if ($tenant->users_count > 0) {
            $this->deleteError = 'No se puede eliminar una organización que tiene usuarios asignados.';

            return;
        }

        $tenant->delete();
    }

    public function switchTenant(int $id): void
    {
        $tenant = Tenant::findOrFail($id);
        session(['tenant_id' => $tenant->id]);

        $this->redirect(route('dashboard'), navigate: true);
    }

    public function resetForm(): void
    {
        $this->reset(['editingId', 'name', 'slug', 'active']);
        $this->active = true;
        $this->resetErrorBag();
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.tenants.index', [
            'tenants' => Tenant::query()
                ->withCount(['users', 'clients'])
                ->when($this->search, fn ($q) => $q->where('name', 'like', "%{$this->search}%"))
                ->orderBy('name')
                ->paginate(10),
            'currentTenantId' => session('tenant_id'),
        ]);
    }
}
