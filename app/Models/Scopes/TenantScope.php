<?php

namespace App\Models\Scopes;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class TenantScope implements Scope
{
    private static ?int $override = null;

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

        $user = auth()->user();

        if (! $user) {
            return null;
        }

        if ($user->role === UserRole::SuperAdmin) {
            return session('tenant_id');
        }

        return $user->tenant_id;
    }

    public static function setOverride(?int $tenantId): void
    {
        static::$override = $tenantId;
    }
}
