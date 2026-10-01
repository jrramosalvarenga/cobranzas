<?php

namespace Tests\Feature\Contabilidad;

use App\Livewire\Contabilidad\Index;
use App\Models\AccountingEntry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AccountingEntryManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_view_accounting_entries(): void
    {
        $this->get('/contabilidad')->assertRedirect('/login');
    }

    public function test_it_creates_an_expense_entry(): void
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(Index::class)
            ->set('type', 'egreso')
            ->set('category', 'Alquiler')
            ->set('amount', '150.00')
            ->set('entry_date', '2026-09-10')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('accounting_entries', [
            'type' => 'egreso',
            'category' => 'Alquiler',
            'amount' => '150.00',
            'user_id' => $user->id,
        ]);
    }

    public function test_it_creates_an_other_income_entry(): void
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(Index::class)
            ->set('type', 'ingreso')
            ->set('category', 'Donación')
            ->set('amount', '500.00')
            ->set('entry_date', '2026-09-10')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('accounting_entries', [
            'type' => 'ingreso',
            'category' => 'Donación',
            'amount' => '500.00',
        ]);
    }

    public function test_amount_must_be_greater_than_zero(): void
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(Index::class)
            ->set('type', 'egreso')
            ->set('category', 'Alquiler')
            ->set('amount', '0')
            ->set('entry_date', '2026-09-10')
            ->call('save')
            ->assertHasErrors(['amount']);
    }

    public function test_it_can_filter_entries_by_type(): void
    {
        $user = User::factory()->create();
        AccountingEntry::factory()->ingreso()->create(['user_id' => $user->id]);
        AccountingEntry::factory()->egreso()->create(['user_id' => $user->id]);

        Livewire::actingAs($user)
            ->test(Index::class)
            ->set('filterType', 'ingreso')
            ->assertViewHas('entries', fn ($entries) => $entries->total() === 1);
    }

    public function test_it_deletes_an_entry(): void
    {
        $user = User::factory()->create();
        $entry = AccountingEntry::factory()->create(['user_id' => $user->id]);

        Livewire::actingAs($user)
            ->test(Index::class)
            ->call('delete', $entry->id);

        $this->assertDatabaseMissing('accounting_entries', ['id' => $entry->id]);
    }
}
