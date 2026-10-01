<?php

namespace Database\Factories;

use App\Models\AccountingEntry;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AccountingEntry>
 */
class AccountingEntryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type' => 'egreso',
            'category' => fake()->randomElement(AccountingEntry::CATEGORIAS_EGRESO),
            'description' => fake()->optional()->sentence(),
            'amount' => fake()->randomFloat(2, 10, 5000),
            'entry_date' => fake()->dateTimeBetween('-1 month', 'now')->format('Y-m-d'),
            'user_id' => User::factory(),
        ];
    }

    public function ingreso(): static
    {
        return $this->state(fn () => [
            'type' => 'ingreso',
            'category' => fake()->randomElement(AccountingEntry::CATEGORIAS_INGRESO),
        ]);
    }

    public function egreso(): static
    {
        return $this->state(fn () => [
            'type' => 'egreso',
            'category' => fake()->randomElement(AccountingEntry::CATEGORIAS_EGRESO),
        ]);
    }
}
