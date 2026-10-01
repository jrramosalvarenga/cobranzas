<?php

namespace App\Livewire\Contratos;

use App\Models\Client;
use App\Models\Contract;
use App\Models\Cuota;
use App\Models\ServiceType;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $search = '';

    public string $statusFilter = '';

    public bool $showModal = false;

    public ?int $editingId = null;

    public string $clientSearch = '';

    public ?int $client_id = null;

    public ?int $service_type_id = null;

    public string $contract_number = '';

    public string $monthly_fee = '';

    public string $start_date = '';

    public int $billing_day = 1;

    public string $status = 'active';

    public string $setup_fee = '';

    public string $setup_fee_due_date = '';

    public ?string $deleteError = null;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function create(): void
    {
        $this->resetForm();
        $this->contract_number = 'C-'.str_pad((string) (Contract::max('id') + 1), 5, '0', STR_PAD_LEFT);
        $this->start_date = now()->toDateString();
        $this->showModal = true;
    }

    public function edit(int $id): void
    {
        $contrato = Contract::findOrFail($id);

        $this->editingId = $contrato->id;
        $this->client_id = $contrato->client_id;
        $this->service_type_id = $contrato->service_type_id;
        $this->contract_number = $contrato->contract_number;
        $this->monthly_fee = $contrato->monthly_fee !== null ? (string) $contrato->monthly_fee : '';
        $this->setup_fee = $contrato->setup_fee !== null ? (string) $contrato->setup_fee : '';
        $this->setup_fee_due_date = $contrato->setup_fee_due_date?->toDateString() ?? '';
        $this->start_date = $contrato->start_date->toDateString();
        $this->billing_day = $contrato->billing_day;
        $this->status = $contrato->status;
        $this->showModal = true;
    }

    public function save(): void
    {
        $data = $this->validate([
            'client_id' => 'required|exists:clients,id',
            'service_type_id' => 'required|exists:service_types,id',
            'contract_number' => ['required', 'string', 'max:50', Rule::unique('contracts', 'contract_number')->ignore($this->editingId)],
            'monthly_fee' => 'nullable|numeric|min:0',
            'setup_fee' => 'nullable|numeric|min:0',
            'setup_fee_due_date' => 'nullable|required_with:setup_fee|date',
            'start_date' => 'required|date',
            'billing_day' => 'required|integer|min:1|max:28',
            'status' => 'required|in:active,suspended,cancelled',
        ]);

        $data['monthly_fee'] = $data['monthly_fee'] !== '' ? $data['monthly_fee'] : null;
        $data['setup_fee'] = $data['setup_fee'] !== '' ? $data['setup_fee'] : null;
        $data['setup_fee_due_date'] = $data['setup_fee_due_date'] !== '' ? $data['setup_fee_due_date'] : null;

        $isNew = $this->editingId === null;

        $contract = Contract::updateOrCreate(['id' => $this->editingId], $data);

        if ($isNew && $data['setup_fee'] !== null) {
            $startDate = $contract->start_date;

            Cuota::create([
                'contract_id' => $contract->id,
                'period_year' => $startDate->year,
                'period_month' => 0,
                'amount' => $data['setup_fee'],
                'due_date' => $data['setup_fee_due_date'],
                'status' => 'pendiente',
            ]);
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function delete(int $id): void
    {
        $contrato = Contract::withCount('cuotas')->findOrFail($id);

        if ($contrato->cuotas_count > 0) {
            $this->deleteError = 'No se puede eliminar un contrato que ya tiene cuotas generadas. Puede cancelarlo en su lugar.';

            return;
        }

        $contrato->delete();
    }

    public function resetForm(): void
    {
        $this->reset([
            'editingId', 'client_id', 'service_type_id', 'contract_number',
            'monthly_fee', 'setup_fee', 'setup_fee_due_date', 'start_date',
            'billing_day', 'status', 'clientSearch',
        ]);
        $this->billing_day = 1;
        $this->status = 'active';
        $this->resetErrorBag();
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.contratos.index', [
            'contratos' => Contract::query()
                ->with(['client', 'serviceType'])
                ->when($this->search, fn ($q) => $q->whereHas('client', fn ($c) => $c->where('full_name', 'like', "%{$this->search}%")
                    ->orWhere('document_number', 'like', "%{$this->search}%")))
                ->when($this->statusFilter, fn ($q) => $q->where('status', $this->statusFilter))
                ->latest()
                ->paginate(10),
            'clientesOpciones' => Client::query()
                ->when($this->clientSearch, fn ($q) => $q->where('full_name', 'like', "%{$this->clientSearch}%")
                    ->orWhere('document_number', 'like', "%{$this->clientSearch}%"))
                ->orderBy('full_name')
                ->limit(50)
                ->get(),
            'servicios' => ServiceType::query()
                ->where('active', true)
                ->orWhere('id', $this->service_type_id)
                ->orderBy('name')
                ->get(),
        ]);
    }
}
