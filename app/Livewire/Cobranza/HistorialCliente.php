<?php

namespace App\Livewire\Cobranza;

use App\Models\Client;
use App\Models\Payment;
use Livewire\Attributes\Layout;
use Livewire\Component;

class HistorialCliente extends Component
{
    public string $clientSearch = '';

    public ?int $selectedClientId = null;

    public bool $showReversalModal = false;

    public ?int $reversalPaymentId = null;

    public string $reversalReason = '';

    public function seleccionarCliente(int $id): void
    {
        $this->selectedClientId = $id;
        $this->clientSearch = '';
    }

    public function cambiarCliente(): void
    {
        $this->selectedClientId = null;
    }

    public function confirmarReversion(int $paymentId): void
    {
        $this->reversalPaymentId = $paymentId;
        $this->reversalReason = '';
        $this->showReversalModal = true;
    }

    public function cancelarReversion(): void
    {
        $this->showReversalModal = false;
        $this->reversalPaymentId = null;
        $this->reversalReason = '';
    }

    public function revertirPago(): void
    {
        $this->validate([
            'reversalReason' => ['required', 'string', 'min:3'],
        ], [
            'reversalReason.required' => 'Debe indicar el motivo de la reversión.',
            'reversalReason.min' => 'El motivo debe tener al menos 3 caracteres.',
        ]);

        $payment = Payment::findOrFail($this->reversalPaymentId);

        if ($payment->isReversed()) {
            $this->cancelarReversion();

            return;
        }

        $payment->update([
            'reversed_at' => now(),
            'reversed_reason' => $this->reversalReason,
        ]);

        $payment->cuota->refreshStatus();

        $this->cancelarReversion();
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

        $pagos = collect();

        if ($clienteSeleccionado) {
            $pagos = Payment::query()
                ->whereHas('cuota.contract', fn ($q) => $q->where('client_id', $clienteSeleccionado->id))
                ->with(['cuota.contract.serviceType', 'user'])
                ->latest('payment_date')
                ->latest('id')
                ->get();
        }

        return view('livewire.cobranza.historial-cliente', [
            'clientesEncontrados' => $clientesEncontrados,
            'clienteSeleccionado' => $clienteSeleccionado,
            'pagos' => $pagos,
        ]);
    }
}
