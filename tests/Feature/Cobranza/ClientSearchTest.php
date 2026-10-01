<?php

namespace Tests\Feature\Cobranza;

use App\Livewire\Cobranza\Registrar;
use App\Models\Client;
use App\Models\Contract;
use App\Models\ServiceType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ClientSearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_clients_with_a_contract_appear_in_the_search_results(): void
    {
        $user = User::factory()->create();

        $serviceType = ServiceType::create([
            'name' => 'Agua',
            'monthly_price' => 100,
            'active' => true,
        ]);

        $clienteConContrato = Client::create([
            'full_name' => 'Ana Con Contrato',
            'document_number' => '0001',
            'address_line' => 'Calle 1',
        ]);

        Contract::create([
            'client_id' => $clienteConContrato->id,
            'service_type_id' => $serviceType->id,
            'contract_number' => 'C-0001',
            'monthly_fee' => 100,
            'start_date' => '2026-08-01',
            'status' => 'active',
        ]);

        Client::create([
            'full_name' => 'Ana Sin Contrato',
            'document_number' => '0002',
            'address_line' => 'Calle 2',
        ]);

        Livewire::actingAs($user)
            ->test(Registrar::class)
            ->set('clientSearch', 'Ana')
            ->assertViewHas('clientesEncontrados', function ($clientes) use ($clienteConContrato) {
                return $clientes->count() === 1
                    && $clientes->first()->is($clienteConContrato);
            });
    }
}
