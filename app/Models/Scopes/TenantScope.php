<?php

namespace App\Models\Scopes;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class TenantScope implements Scope
{
    private static ?int $override = null;

    private static bool $resolving = false;

    public function apply(Builder $builder, Model $model): void
    {
        $tenantId = static::currentTenantId();

        if ($tenantId) {
            $builder->where($model->qualifyColumn('tenant_id'), $tenantId);
        }
    }

    public static function currentTenantId(): ?int
    {
        if (static::$override !== null) {
            return static::$override;
        }

        if (static::$resolving) {
            return null;
        }

        static::$resolving = true;

        try {
            $user = auth()->user();

            if (! $user) {
                return null;
            }

            if ($user->role === UserRole::SuperAdmin) {
                return session('tenant_id');
            }

            return $user->tenant_id;
        } finally {
            static::$resolving = false;
        }
    }

    public static function setOverride(?int $tenantId): void
    {
        static::$override = $tenantId;
    }
}
