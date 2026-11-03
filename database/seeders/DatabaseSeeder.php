<?php

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $tenant = Tenant::firstOrCreate(
            ['slug' => 'default'],
            ['name' => 'Organización Principal', 'active' => true]
        );

        $admin = User::withoutGlobalScopes()->firstOrCreate(
            ['email' => 'juanrra2013@gmail.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('cobranzas123'),
                'email_verified_at' => now(),
                'role' => 'admin',
                'tenant_id' => $tenant->id,
            ]
        );

        if (! $admin->tenant_id) {
            $admin->update(['tenant_id' => $tenant->id]);
        }
    }
}
