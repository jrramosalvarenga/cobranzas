<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->foreignId('service_type_id')->constrained()->restrictOnDelete();
            $table->string('contract_number')->unique();
            $table->decimal('monthly_fee', 10, 2)->nullable();
            $table->date('start_date');
            $table->unsignedTinyInteger('billing_day')->default(1);
            $table->enum('status', ['active', 'suspended', 'cancelled'])->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
