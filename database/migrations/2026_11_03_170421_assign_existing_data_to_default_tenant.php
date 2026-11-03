<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /** @var list<string> */
    private array $tables = [
        'users',
        'clients',
        'service_types',
        'contracts',
        'cuotas',
        'payments',
        'accounting_entries',
    ];

    public function up(): void
    {
        $tenantId = DB::table('tenants')->insertGetId([
            'name' => 'Organización Principal',
            'slug' => 'default',
            'active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        foreach ($this->tables as $table) {
            DB::table($table)->whereNull('tenant_id')->update(['tenant_id' => $tenantId]);
        }
    }

    public function down(): void
    {
        $tenant = DB::table('tenants')->where('slug', 'default')->first();

        if ($tenant) {
            foreach ($this->tables as $table) {
                DB::table($table)->where('tenant_id', $tenant->id)->update(['tenant_id' => null]);
            }

            DB::table('tenants')->where('id', $tenant->id)->delete();
        }
    }
};
