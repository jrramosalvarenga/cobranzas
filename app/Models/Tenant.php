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
        'license_expires_at',
    ];

    protected function casts(): array
    {
        return [
            'settings' => 'array',
            'active' => 'boolean',
            'license_expires_at' => 'date',
        ];
    }

    public function licenseExpired(): bool
    {
        return $this->license_expires_at !== null && $this->license_expires_at->isPast();
    }

    public function licenseExpiresSoon(): bool
    {
        if ($this->license_expires_at === null) {
            return false;
        }

        return ! $this->license_expires_at->isPast()
            && $this->license_expires_at->diffInDays(now()) <= 30;
    }

    public function licenseDaysRemaining(): ?int
    {
        if ($this->license_expires_at === null) {
            return null;
        }

        if ($this->license_expires_at->isPast()) {
            return 0;
        }

        return (int) now()->diffInDays($this->license_expires_at);
    }

    public function logoUrl(): ?string
    {
        $path = $this->settings['logo'] ?? null;

        return $path ? asset('storage/'.$path) : null;
    }

    public function logoSecondaryUrl(): ?string
    {
        $path = $this->settings['logo_secondary'] ?? null;

        return $path ? asset('storage/'.$path) : null;
    }

    public function logoPath(): ?string
    {
        $path = $this->settings['logo'] ?? null;

        return $path ? storage_path('app/public/'.$path) : null;
    }

    public function logoSecondaryPath(): ?string
    {
        $path = $this->settings['logo_secondary'] ?? null;

        return $path ? storage_path('app/public/'.$path) : null;
    }

    public function companyName(): string
    {
        return $this->settings['company_name'] ?? $this->name;
    }

    public function address(): string
    {
        return $this->settings['address'] ?? '';
    }

    public function slogan(): string
    {
        return $this->settings['slogan'] ?? '';
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
