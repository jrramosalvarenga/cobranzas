<?php

namespace App\Livewire\Tenants;

use App\Models\Tenant;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Index extends Component
{
    use WithFileUploads;
    use WithPagination;

    public string $search = '';

    public bool $showModal = false;

    public ?int $editingId = null;

    public string $name = '';

    public string $slug = '';

    public bool $active = true;

    public string $primary_color = '#0f2a4a';

    public string $secondary_color = '#0d7c5f';

    public $logo = null;

    public ?string $currentLogoUrl = null;

    public bool $removeLogo = false;

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
        $this->primary_color = $tenant->primaryColor();
        $this->secondary_color = $tenant->secondaryColor();
        $this->currentLogoUrl = $tenant->logoUrl();
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
            'primary_color' => 'required|string|regex:/^#[0-9a-fA-F]{6}$/',
            'secondary_color' => 'required|string|regex:/^#[0-9a-fA-F]{6}$/',
            'logo' => 'nullable|image|max:2048',
        ]);

        $data['slug'] = Str::slug($data['slug']);

        $tenant = Tenant::updateOrCreate(['id' => $this->editingId], [
            'name' => $data['name'],
            'slug' => $data['slug'],
            'active' => $data['active'],
        ]);

        $settings = $tenant->settings ?? [];
        $settings['primary_color'] = $data['primary_color'];
        $settings['secondary_color'] = $data['secondary_color'];

        if ($this->removeLogo && isset($settings['logo'])) {
            Storage::disk('public')->delete($settings['logo']);
            unset($settings['logo']);
        }

        if ($this->logo) {
            if (isset($settings['logo'])) {
                Storage::disk('public')->delete($settings['logo']);
            }
            $settings['logo'] = $this->logo->store('tenant-logos', 'public');
        }

        $tenant->update(['settings' => $settings]);

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
        $this->reset(['editingId', 'name', 'slug', 'active', 'primary_color', 'secondary_color', 'logo', 'currentLogoUrl', 'removeLogo']);
        $this->active = true;
        $this->primary_color = '#0f2a4a';
        $this->secondary_color = '#0d7c5f';
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
