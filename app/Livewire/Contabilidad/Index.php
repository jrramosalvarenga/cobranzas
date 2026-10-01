<?php

namespace App\Livewire\Contabilidad;

use App\Models\AccountingEntry;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $filterType = '';

    public string $filterFrom = '';

    public string $filterTo = '';

    public bool $showModal = false;

    public ?int $editingId = null;

    public string $type = 'egreso';

    public string $category = '';

    public string $description = '';

    public string $amount = '';

    public string $entry_date = '';

    public ?string $deleteError = null;

    public function updatingFilterType(): void
    {
        $this->resetPage();
    }

    public function updatingFilterFrom(): void
    {
        $this->resetPage();
    }

    public function updatingFilterTo(): void
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
        $entry = AccountingEntry::findOrFail($id);

        $this->editingId = $entry->id;
        $this->type = $entry->type;
        $this->category = $entry->category;
        $this->description = (string) $entry->description;
        $this->amount = (string) $entry->amount;
        $this->entry_date = $entry->entry_date->format('Y-m-d');
        $this->showModal = true;
    }

    public function save(): void
    {
        $data = $this->validate([
            'type' => 'required|in:ingreso,egreso',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'amount' => 'required|numeric|min:0.01',
            'entry_date' => 'required|date',
        ]);

        if ($this->editingId) {
            AccountingEntry::whereKey($this->editingId)->update($data);
        } else {
            AccountingEntry::create([...$data, 'user_id' => Auth::id()]);
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function delete(int $id): void
    {
        AccountingEntry::findOrFail($id)->delete();
    }

    public function resetForm(): void
    {
        $this->reset(['editingId', 'category', 'description', 'amount']);
        $this->type = 'egreso';
        $this->entry_date = now()->format('Y-m-d');
        $this->resetErrorBag();
    }

    public function mount(): void
    {
        $this->entry_date = now()->format('Y-m-d');
    }

    #[Layout('layouts.app')]
    public function render()
    {
        $entries = AccountingEntry::query()
            ->with('user')
            ->when($this->filterType, fn ($q) => $q->where('type', $this->filterType))
            ->when($this->filterFrom, fn ($q) => $q->whereDate('entry_date', '>=', $this->filterFrom))
            ->when($this->filterTo, fn ($q) => $q->whereDate('entry_date', '<=', $this->filterTo))
            ->orderByDesc('entry_date')
            ->orderByDesc('id')
            ->paginate(10);

        return view('livewire.contabilidad.index', [
            'entries' => $entries,
            'categoriasIngreso' => AccountingEntry::CATEGORIAS_INGRESO,
            'categoriasEgreso' => AccountingEntry::CATEGORIAS_EGRESO,
        ]);
    }
}
