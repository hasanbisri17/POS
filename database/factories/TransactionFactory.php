<?php

namespace Database\Factories;

use App\Models\Transaction;
use App\Models\User;
use App\Models\PaymentMethod;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    public function definition(): array
    {
        $totalAmount = $this->faker->numberBetween(10000, 100000);
        $paymentAmount = $this->faker->numberBetween($totalAmount, $totalAmount + 50000);

        return [
            'invoice_number' => Transaction::generateInvoiceNumber(),
            'payment_method_id' => PaymentMethod::factory(),
            'total_amount' => $totalAmount,
            'payment_amount' => $paymentAmount,
            'change_amount' => $paymentAmount - $totalAmount,
            'status' => $this->faker->randomElement(['pending', 'completed', 'cancelled']),
            'notes' => $this->faker->sentence(),
            'created_by' => User::factory(),
        ];
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
        ]);
    }

    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'cancelled',
        ]);
    }

    public function cash(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'payment_method_id' => PaymentMethod::where('code', 'cash')->first()?->id 
                    ?? PaymentMethod::factory()->cash()->create()->id,
            ];
        });
    }

    public function qris(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'payment_method_id' => PaymentMethod::where('code', 'qris')->first()?->id 
                    ?? PaymentMethod::factory()->qris()->create()->id,
            ];
        });
    }

    public function today(): static
    {
        return $this->state(fn (array $attributes) => [
            'created_at' => now(),
        ]);
    }

    public function thisMonth(): static
    {
        return $this->state(fn (array $attributes) => [
            'created_at' => $this->faker->dateTimeBetween(now()->startOfMonth(), now()),
        ]);
    }
}
