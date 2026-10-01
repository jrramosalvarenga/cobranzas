<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cuotas', function (Blueprint $table) {
            $table->string('description')->nullable()->after('period_month');
            $table->dropUnique(['contract_id', 'period_year', 'period_month']);
            $table->unique(['contract_id', 'period_year', 'period_month', 'description'], 'cuotas_period_unique');
        });
    }

    public function down(): void
    {
        Schema::table('cuotas', function (Blueprint $table) {
            $table->dropUnique('cuotas_period_unique');
            $table->unique(['contract_id', 'period_year', 'period_month']);
            $table->dropColumn('description');
        });
    }
};
