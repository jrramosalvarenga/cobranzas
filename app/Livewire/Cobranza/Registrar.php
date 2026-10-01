<?php

namespace App\Livewire\Cobranza;

use App\Models\Client;
use App\Models\Cuota;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Registrar extends Component
{
    public string $clientSearch = '';

    public ?int $selectedClientId = null;

    /** @var array<int, string> cuota_id => monto a pagar */
    public array $montos = [];

    /** @var array<int, bool> cuota_id => seleccionada */
    public array $seleccion = [];

    public string $method = 'efectivo';

    public string $payment_date = '';

    public string $notes = '';

    public ?string $error = null;

    public bool $editingLocation = false;

    public string $loc_address_line = '';

    public string $loc_reference = '';

    public ?string $loc_latitude = null;

    public ?string $loc_longitude = null;

    public ?string $locationMessage = null;

    public bool $showConfirmModal = false;

    public function mount(): void
    {
        $this->payment_date = now()->toDateString();
    }

    public function seleccionarCliente(int $id): void
    {
        $this->selectedClientId = $id;
        $this->clientSearch = '';
        $this->montos = [];
        $this->seleccion = [];
        $this->error = null;
        $this->editingLocation = false;
        $this->locationMessage = null;
    }

    public function cambiarCliente(): void
    {
        $this->selectedClientId = null;
        $this->montos = [];
        $this->seleccion = [];
        $this->editingLocation = false;
    }

    public function editarUbicacion(): void
    {
        $cliente = Client::findOrFail($this->selectedClientId);

        $this->loc_address_line = $cliente->address_line;
        $this->loc_reference = (string) $cliente->reference;
        $this->loc_latitude = $cliente->latitude !== null ? (string) $cliente->latitude : null;
        $this->loc_longitude = $cliente->longitude !== null ? (string) $cliente->longitude : null;
        $this->locationMessage = null;
        $this->editingLocation = true;
    }

    public function cancelarUbicacion(): void
    {
        $this->editingLocation = false;
    }

    public function setLocation($lat, $lng): void
    {
        $this->loc_latitude = number_format((float) $lat, 7, '.', '');
        $this->loc_longitude = number_format((float) $lng, 7, '.', '');
    }

    public function guardarUbicacion(): void
    {
        $data = $this->validate([
            'loc_address_line' => 'required|string|max:255',
            'loc_reference' => 'nullable|string|max:255',
            'loc_latitude' => 'nullable|numeric|between:-90,90',
            'loc_longitude' => 'nullable|numeric|between:-180,180',
        ]);

        Client::whereKey($this->selectedClientId)->update([
            'address_line' => $data['loc_address_line'],
            'reference' => $data['loc_reference'] ?: null,
            'latitude' => $data['loc_latitude'] ?: null,
            'longitude' => $data['loc_longitude'] ?: null,
        ]);

        $this->editingLocation = false;
        $this->locationMessage = 'Domicilio actualizado correctamente.';
    }

    public function toggleCuota(int $cuotaId): void
    {
        if (! empty($this->seleccion[$cuotaId])) {
            unset($this->seleccion[$cuotaId], $this->montos[$cuotaId]);

            return;
        }

        $cuota = Cuota::findOrFail($cuotaId);
        $this->seleccion[$cuotaId] = true;
        $this->montos[$cuotaId] = $cuota->saldo();
    }

    public function confirmarPago(): void
    {
        $this->error = null;

        $cuotaIds = array_keys(array_filter($this->seleccion));

        if (empty($cuotaIds)) {
            $this->error = 'Seleccione al menos una cuota para registrar el pago.';

            return;
        }

        $cuotas = Cuota::whereIn('id', $cuotaIds)->get()->keyBy('id');

        $this->validate([
            'method' => 'required|in:efectivo,transferencia,otro',
            'payment_date' => 'required|date',
        ]);

        foreach ($cuotaIds as $cuotaId) {
            $monto = (float) ($this->montos[$cuotaId] ?? 0);
            $saldo = (float) $cuotas[$cuotaId]->saldo();

            if ($monto <= 0 || $monto > $saldo + 0.001) {
                $this->error = 'El monto a pagar de una de las cuotas seleccionadas es inválido (debe ser mayor a 0 y no exceder el saldo).';

                return;
            }
        }

        $this->showConfirmModal = true;
    }

    public function registrarPago(): void
    {
        $this->showConfirmModal = false;

        $cuotaIds = array_keys(array_filter($this->seleccion));
        $cuotas = Cuota::whereIn('id', $cuotaIds)->get()->keyBy('id');

        $receiptNumber = 'R-'.str_pad((string) (Payment::max('id') + 1), 6, '0', STR_PAD_LEFT);

        DB::transaction(function () use ($cuotaIds, $cuotas, $receiptNumber) {
            foreach ($cuotaIds as $cuotaId) {
                Payment::create([
                    'cuota_id' => $cuotaId,
                    'user_id' => Auth::id(),
                    'receipt_number' => $receiptNumber,
                    'amount' => $this->montos[$cuotaId],
                    'method' => $this->method,
                    'payment_date' => $this->payment_date,
                    'notes' => $this->notes ?: null,
                ]);

                $cuotas[$cuotaId]->refreshStatus();
            }
        });

        $this->montos = [];
        $this->seleccion = [];
        $this->notes = '';

        $this->dispatch('recibo-generado', url: route('recibos.print', $receiptNumber));
    }

    #[Layout('layouts.app')]
    public function render()
    {
        $clientesEncontrados = collect();

        if ($this->clientSearch !== '' && $this->selectedClientId === null) {
            $clientesEncontrados = Client::query()
                ->whereHas('contracts')
                ->where(fn ($q) => $q
                    ->where('full_name', 'like', "%{$this->clientSearch}%")
                    ->orWhere('document_number', 'like', "%{$this->clientSearch}%"))
                ->orderBy('full_name')
                ->limit(10)
                ->get();
        }

        $clienteSeleccionado = $this->selectedClientId
            ? Client::find($this->selectedClientId)
            : null;

        $cuotasPendientes = collect();

        if ($clienteSeleccionado) {
            $cuotasPendientes = Cuota::query()
                ->whereHas('contract', fn ($q) => $q->where('client_id', $clienteSeleccionado->id))
                ->whereIn('status', ['pendiente', 'parcial', 'vencida'])
                ->with('contract.serviceType')
                ->orderBy('due_date')
                ->get();
        }

        return view('livewire.cobranza.registrar', [
            'clientesEncontrados' => $clientesEncontrados,
            'clienteSeleccionado' => $clienteSeleccionado,
            'cuotasPendientes' => $cuotasPendientes,
        ]);
    }
}
