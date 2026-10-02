<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->date('suspended_from')->nullable()->after('status');
        });

        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE cuotas DROP CONSTRAINT cuotas_status_check');
            DB::statement("ALTER TABLE cuotas ADD CONSTRAINT cuotas_status_check CHECK (status::text = ANY (ARRAY['pendiente', 'parcial', 'pagada', 'vencida', 'cancelada']::text[]))");
        }
    }

    public function down(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->dropColumn('suspended_from');
        });

        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE cuotas DROP CONSTRAINT cuotas_status_check');
            DB::statement("ALTER TABLE cuotas ADD CONSTRAINT cuotas_status_check CHECK (status::text = ANY (ARRAY['pendiente', 'parcial', 'pagada', 'vencida']::text[]))");
        }
    }
};
