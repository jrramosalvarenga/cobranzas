<?php

namespace App\Models;

use Database\Factories\TenantFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tenant extends Model
{
    /** @use HasFactory<TenantFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'settings',
        'active',
    ];

    protected function casts(): array
    {
        return [
            'settings' => 'array',
            'active' => 'boolean',
        ];
    }

    public function logoUrl(): ?string
    {
        $path = $this->settings['logo'] ?? null;

        return $path ? asset('storage/'.$path) : null;
    }

    public function primaryColor(): string
    {
        return $this->settings['primary_color'] ?? '#0f2a4a';
    }

    public function secondaryColor(): string
    {
        return $this->settings['secondary_color'] ?? '#0d7c5f';
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function clients(): HasMany
    {
        return $this->hasMany(Client::class);
    }

    public function serviceTypes(): HasMany
    {
        return $this->hasMany(ServiceType::class);
    }
}
