<?php

namespace Database\Factories;

use App\Models\CashTransaction;
use App\Models\CashCategory;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CashTransactionFactory extends Factory
{
    protected $model = CashTransaction::class;

    public function definition(): array
    {
        return [
            'cash_category_id' => CashCategory::factory(),
            'amount' => $this->faker->numberBetween(10000, 100000),
            'type' => $this->faker->randomElement(['income', 'expense']),
            'description' => $this->faker->sentence(),
            'transaction_date' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'created_by' => User::factory(),
            'transaction_id' => null,
        ];
    }

    public function income(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'income',
            'cash_category_id' => CashCategory::factory()->income(),
        ]);
    }

    public function expense(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'expense',
            'cash_category_id' => CashCategory::factory()->expense(),
        ]);
    }

    public function fromSales(): static
    {
        return $this->state(function (array $attributes) {
            $transaction = Transaction::factory()->create();
            
            return [
                'type' => 'income',
                'cash_category_id' => CashCategory::factory()->sales(),
                'amount' => $transaction->total_amount,
                'description' => 'Pemasukan dari penjualan #' . $transaction->invoice_number,
                'transaction_id' => $transaction->id,
                'transaction_date' => $transaction->created_at,
            ];
        });
    }

    public function today(): static
    {
        return $this->state(fn (array $attributes) => [
            'transaction_date' => now(),
        ]);
    }

    public function thisMonth(): static
    {
        return $this->state(fn (array $attributes) => [
            'transaction_date' => $this->faker->dateTimeBetween(now()->startOfMonth(), now()),
        ]);
    }
}
