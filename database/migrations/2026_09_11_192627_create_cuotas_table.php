<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cuotas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_id')->constrained()->cascadeOnDelete();
            $table->smallInteger('period_year');
            $table->unsignedTinyInteger('period_month');
            $table->decimal('amount', 10, 2);
            $table->date('due_date');
            $table->enum('status', ['pendiente', 'parcial', 'pagada', 'vencida'])->default('pendiente');
            $table->timestamps();

            $table->unique(['contract_id', 'period_year', 'period_month']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cuotas');
    }
};
