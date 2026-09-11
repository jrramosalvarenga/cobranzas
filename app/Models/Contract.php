<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Contract extends Model
{
    protected $fillable = [
        'client_id',
        'service_type_id',
        'contract_number',
        'monthly_fee',
        'start_date',
        'billing_day',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'monthly_fee' => 'decimal:2',
            'start_date' => 'date',
            'billing_day' => 'integer',
        ];
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function serviceType(): BelongsTo
    {
        return $this->belongsTo(ServiceType::class);
    }

    public function cuotas(): HasMany
    {
        return $this->hasMany(Cuota::class);
    }

    public function effectiveMonthlyFee(): string
    {
        return $this->monthly_fee ?? $this->serviceType->monthly_price;
    }
}
