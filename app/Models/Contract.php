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
        'description',
        'monthly_fee',
        'setup_fee',
        'setup_fee_due_date',
        'start_date',
        'end_date',
        'billing_day',
        'status',
        'suspended_from',
    ];

    protected static function booted(): void
    {
        static::creating(function (Contract $contract) {
            if (blank($contract->contract_number)) {
                $next = (self::withoutGlobalScopes()->max('id') ?? 0) + 1;
                $contract->contract_number = 'C-'.str_pad((string) ($next + 99), 5, '0', STR_PAD_LEFT);
            }
        });

        static::created(function (Contract $contract) {
            $expected = 'C-'.str_pad((string) ($contract->id + 99), 5, '0', STR_PAD_LEFT);
            if ($contract->contract_number !== $expected) {
                $contract->updateQuietly(['contract_number' => $expected]);
            }
        });
    }

    protected function casts(): array
    {
        return [
            'monthly_fee' => 'decimal:2',
            'setup_fee' => 'decimal:2',
            'setup_fee_due_date' => 'date',
            'start_date' => 'date',
            'end_date' => 'date',
            'suspended_from' => 'date',
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
