<?php

namespace App\Livewire\Usuarios;

use App\Enums\UserRole;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
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

    public string $email = '';

    public string $password = '';

    public string $password_confirmation = '';

    public string $role = 'cobrador';

    public ?int $tenant_id = null;

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
        $user = User::findOrFail($id);

        $this->editingId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role->value;
        $this->tenant_id = $user->tenant_id;
        $this->password = '';
        $this->password_confirmation = '';
        $this->showModal = true;
    }

    public function save(): void
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->editingId)],
            'role' => ['required', new Enum(UserRole::class)],
        ];

        if (auth()->user()->isSuperAdmin()) {
            $rules['tenant_id'] = 'required|exists:tenants,id';
        }

        if ($this->editingId) {
            $rules['password'] = 'nullable|string|min:6|confirmed';
        } else {
            $rules['password'] = 'required|string|min:6|confirmed';
        }

        $data = $this->validate($rules);

        $userData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $data['role'],
        ];

        if (auth()->user()->isSuperAdmin() && isset($data['tenant_id'])) {
            $userData['tenant_id'] = $data['tenant_id'];
        }

        if (! empty($data['password'])) {
            $userData['password'] = bcrypt($data['password']);
        }

        if ($this->editingId) {
            User::withoutGlobalScopes()->findOrFail($this->editingId)->update($userData);
        } else {
            $userData['email_verified_at'] = now();
            User::create($userData);
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function delete(int $id): void
    {
        if ($id === auth()->id()) {
            return;
        }

        User::findOrFail($id)->delete();
    }

    public function resetForm(): void
    {
        $this->reset(['editingId', 'name', 'email', 'password', 'password_confirmation', 'role', 'tenant_id']);
        $this->resetErrorBag();
    }

    #[Layout('layouts.app')]
    public function render()
    {
        $query = auth()->user()->isSuperAdmin()
            ? User::withoutGlobalScopes()->with('tenant')
            : User::query();

        return view('livewire.usuarios.index', [
            'usuarios' => $query
                ->when($this->search, fn ($q) => $q->where('name', 'ilike', "%{$this->search}%")
                    ->orWhere('email', 'ilike', "%{$this->search}%"))
                ->orderBy('name')
                ->paginate(10),
            'tenants' => auth()->user()->isSuperAdmin()
                ? Tenant::orderBy('name')->get()
                : collect(),
        ]);
    }
}
