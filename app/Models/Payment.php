<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'cuota_id',
        'user_id',
        'receipt_number',
        'amount',
        'method',
        'payment_date',
        'notes',
        'reversed_at',
        'reversed_reason',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'payment_date' => 'date',
            'reversed_at' => 'datetime',
        ];
    }

    public static function generateReceiptNumber(int $tenantId): string
    {
        $tenant = Tenant::find($tenantId);
        $prefix = $tenant ? strtoupper($tenant->slug) : 'SIN';

        $last = self::withoutGlobalScopes()
            ->where('tenant_id', $tenantId)
            ->where('receipt_number', 'like', $prefix.'-R%')
            ->orderByDesc('receipt_number')
            ->value('receipt_number');

        $lastNumber = $last ? (int) str_replace($prefix.'-R', '', $last) : 0;

        return $prefix.'-R'.str_pad((string) ($lastNumber + 1), 5, '0', STR_PAD_LEFT);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->whereNull('reversed_at');
    }

    public function isReversed(): bool
    {
        return $this->reversed_at !== null;
    }

    public function cuota(): BelongsTo
    {
        return $this->belongsTo(Cuota::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
