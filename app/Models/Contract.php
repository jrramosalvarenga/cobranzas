<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Contract extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
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
            if (blank($contract->contract_number) && $contract->tenant_id) {
                $contract->contract_number = static::generateContractNumber($contract->tenant_id);
            }
        });
    }

    public static function generateContractNumber(int $tenantId): string
    {
        $tenant = Tenant::find($tenantId);
        $prefix = $tenant ? strtoupper($tenant->slug) : 'SIN';

        $last = self::withoutGlobalScopes()
            ->where('tenant_id', $tenantId)
            ->where('contract_number', 'like', $prefix.'-%')
            ->orderByDesc('contract_number')
            ->value('contract_number');

        $lastNumber = $last ? (int) str_replace($prefix.'-', '', $last) : 0;

        return $prefix.'-'.str_pad((string) ($lastNumber + 1), 5, '0', STR_PAD_LEFT);
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
