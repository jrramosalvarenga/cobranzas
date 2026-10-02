<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccountingEntry extends Model
{
    use HasFactory;

    public const CATEGORIAS_INGRESO = [
        'Venta de agua',
        'Donación',
        'Venta de activo',
        'Intereses',
        'Otro ingreso',
    ];

    public const CATEGORIAS_EGRESO = [
        'Alquiler',
        'Servicios públicos',
        'Salarios',
        'Mantenimiento',
        'Combustible',
        'Insumos de oficina',
        'Impuestos',
        'Otro gasto',
    ];

    protected $fillable = [
        'type',
        'category',
        'description',
        'amount',
        'entry_date',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'entry_date' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeIngresos(Builder $query): Builder
    {
        return $query->where('type', 'ingreso');
    }

    public function scopeEgresos(Builder $query): Builder
    {
        return $query->where('type', 'egreso');
    }
}
