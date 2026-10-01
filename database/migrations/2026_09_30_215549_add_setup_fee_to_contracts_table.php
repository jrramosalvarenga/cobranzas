<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->decimal('setup_fee', 10, 2)->nullable()->after('monthly_fee');
            $table->date('setup_fee_due_date')->nullable()->after('setup_fee');
        });
    }

    public function down(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->dropColumn(['setup_fee', 'setup_fee_due_date']);
        });
    }
};
