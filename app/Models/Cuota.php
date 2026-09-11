<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cuota extends Model
{
    protected $fillable = [
        'contract_id',
        'period_year',
        'period_month',
        'amount',
        'due_date',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'due_date' => 'date',
        ];
    }

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function totalPaid(): string
    {
        return (string) $this->payments()->sum('amount');
    }

    public function saldo(): string
    {
        return bcsub((string) $this->amount, $this->totalPaid(), 2);
    }

    public function refreshStatus(): void
    {
        $saldo = $this->saldo();

        if (bccomp($saldo, '0.00', 2) <= 0) {
            $status = 'pagada';
        } elseif (bccomp($this->totalPaid(), '0.00', 2) > 0) {
            $status = 'parcial';
        } elseif ($this->due_date->isPast()) {
            $status = 'vencida';
        } else {
            $status = 'pendiente';
        }

        if ($status !== $this->status) {
            $this->update(['status' => $status]);
        }
    }

    public function displayStatus(): string
    {
        if (in_array($this->status, ['pagada', 'parcial'], true)) {
            return $this->status;
        }

        return $this->due_date->isPast() ? 'vencida' : 'pendiente';
    }

    public function periodLabel(): string
    {
        $meses = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre',
        ];

        return $meses[$this->period_month].' '.$this->period_year;
    }
}
