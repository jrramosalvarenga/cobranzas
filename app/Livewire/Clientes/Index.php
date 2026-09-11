<?php

namespace App\Livewire\Clientes;

use App\Models\Client;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $search = '';

    public bool $showModal = false;

    public ?int $editingId = null;

    public string $full_name = '';

    public string $document_number = '';

    public string $phone = '';

    public string $email = '';

    public string $address_line = '';

    public string $reference = '';

    public ?string $latitude = null;

    public ?string $longitude = null;

    public string $notes = '';

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
        $cliente = Client::findOrFail($id);

        $this->editingId = $cliente->id;
        $this->full_name = $cliente->full_name;
        $this->document_number = $cliente->document_number;
        $this->phone = (string) $cliente->phone;
        $this->email = (string) $cliente->email;
        $this->address_line = $cliente->address_line;
        $this->reference = (string) $cliente->reference;
        $this->latitude = $cliente->latitude !== null ? (string) $cliente->latitude : null;
        $this->longitude = $cliente->longitude !== null ? (string) $cliente->longitude : null;
        $this->notes = (string) $cliente->notes;
        $this->showModal = true;
    }

    public function setLocation($lat, $lng): void
    {
        $this->latitude = number_format((float) $lat, 7, '.', '');
        $this->longitude = number_format((float) $lng, 7, '.', '');
    }

    public function save(): void
    {
        $data = $this->validate([
            'full_name' => 'required|string|max:255',
            'document_number' => ['required', 'string', 'max:50', Rule::unique('clients', 'document_number')->ignore($this->editingId)],
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'address_line' => 'required|string|max:255',
            'reference' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'notes' => 'nullable|string|max:1000',
        ]);

        Client::updateOrCreate(['id' => $this->editingId], $data);

        $this->showModal = false;
        $this->resetForm();
    }

    public function delete(int $id): void
    {
        $cliente = Client::withCount('contracts')->findOrFail($id);

        if ($cliente->contracts_count > 0) {
            $this->deleteError = 'No se puede eliminar un cliente que tiene contratos asociados.';

            return;
        }

        $cliente->delete();
    }

    public function resetForm(): void
    {
        $this->reset([
            'editingId', 'full_name', 'document_number', 'phone', 'email',
            'address_line', 'reference', 'latitude', 'longitude', 'notes',
        ]);
        $this->resetErrorBag();
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.clientes.index', [
            'clientes' => Client::query()
                ->when($this->search, fn ($q) => $q->where('full_name', 'like', "%{$this->search}%")
                    ->orWhere('document_number', 'like', "%{$this->search}%"))
                ->orderBy('full_name')
                ->paginate(10),
        ]);
    }
}
