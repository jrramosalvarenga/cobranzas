<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cuota_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->string('receipt_number');
            $table->decimal('amount', 10, 2);
            $table->enum('method', ['efectivo', 'transferencia', 'otro'])->default('efectivo');
            $table->date('payment_date');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('receipt_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
