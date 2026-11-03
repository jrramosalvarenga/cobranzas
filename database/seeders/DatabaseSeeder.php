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

        $admin = User::withoutGlobalScopes()->updateOrCreate(
            ['email' => 'juanrra2013@gmail.com'],
            [
                'name' => 'Juan Ramon Ramos',
                'password' => Hash::make(env('ADMIN_PASSWORD', 'password')),
                'email_verified_at' => now(),
                'role' => 'super_admin',
                'tenant_id' => $tenant->id,
            ]
        );
    }
}
