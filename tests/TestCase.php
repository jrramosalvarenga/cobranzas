<?php

namespace Tests;

use App\Models\Scopes\TenantScope;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function createUserWithTenant(array $userAttrs = [], array $tenantAttrs = []): User
    {
        $tenant = Tenant::factory()->create($tenantAttrs);

        return User::factory()->create(array_merge(['tenant_id' => $tenant->id], $userAttrs));
    }

    protected function actingAsWithTenant(User $user): static
    {
        TenantScope::setOverride($user->tenant_id);

        return $this->actingAs($user);
    }

    protected function tearDown(): void
    {
        TenantScope::setOverride(null);

        parent::tearDown();
    }
}
