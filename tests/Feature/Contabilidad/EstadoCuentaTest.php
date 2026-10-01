<?php

namespace Tests\Feature\Contabilidad;

use App\Livewire\Contabilidad\EstadoCuenta;
use App\Models\AccountingEntry;
use App\Models\Client;
use App\Models\Contract;
use App\Models\Cuota;
use App\Models\Payment;
use App\Models\ServiceType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class EstadoCuentaTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_view_the_statement(): void
    {
        $this->get('/contabilidad/estado-cuenta')->assertRedirect('/login');
    }

    public function test_it_nets_collections_and_other_income_against_expenses_for_the_selected_range(): void
    {
        $user = User::factory()->create();

        $serviceType = ServiceType::create([
            'name' => 'Agua',
            'monthly_price' => 100,
            'active' => true,
        ]);

        $client = Client::create([
            'full_name' => 'Cliente Prueba',
            'document_number' => '0001',
            'address_line' => 'Calle Falsa 123',
        ]);

        $contract = Contract::create([
            'client_id' => $client->id,
            'service_type_id' => $serviceType->id,
            'contract_number' => 'C-0001',
            'monthly_fee' => 100,
            'start_date' => '2026-08-01',
            'status' => 'active',
        ]);

        $cuota = Cuota::create([
            'contract_id' => $contract->id,
            'period_year' => 2026,
            'period_month' => 9,
            'amount' => 100,
            'due_date' => '2026-09-05',
            'status' => 'pendiente',
        ]);

        Payment::create([
            'cuota_id' => $cuota->id,
            'user_id' => $user->id,
            'receipt_number' => 'R-000001',
            'amount' => 100,
            'method' => 'efectivo',
            'payment_date' => '2026-09-10',
        ]);

        AccountingEntry::factory()->ingreso()->create([
            'user_id' => $user->id,
            'amount' => 50,
            'entry_date' => '2026-09-10',
        ]);

        AccountingEntry::factory()->egreso()->create([
            'user_id' => $user->id,
            'amount' => 30,
            'entry_date' => '2026-09-10',
        ]);

        // Outside the selected range, must not be counted.
        AccountingEntry::factory()->egreso()->create([
            'user_id' => $user->id,
            'amount' => 999,
            'entry_date' => '2026-01-01',
        ]);

        Livewire::actingAs($user)
            ->test(EstadoCuenta::class)
            ->set('desde', '2026-09-10')
            ->set('hasta', '2026-09-10')
            ->assertViewHas('totalIngresos', '150.00')
            ->assertViewHas('totalEgresos', '30.00')
            ->assertViewHas('balance', '120.00');
    }
}
