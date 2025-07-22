<?php

namespace Database\Factories;

use App\Models\StockMovement;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class StockMovementFactory extends Factory
{
    protected $model = StockMovement::class;

    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'quantity' => $this->faker->numberBetween(1, 50),
            'type' => $this->faker->randomElement(['in', 'out']),
            'notes' => $this->faker->sentence(),
            'created_by' => User::factory(),
            'transaction_id' => null,
        ];
    }

    public function stockIn(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'in',
            'notes' => 'Penambahan stok manual',
        ]);
    }

    public function stockOut(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'out',
            'notes' => 'Pengurangan stok manual',
        ]);
    }

    public function fromTransaction(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'out',
            'notes' => 'Pengurangan stok dari transaksi penjualan',
            'transaction_id' => \App\Models\Transaction::factory(),
        ]);
    }

    public function initialStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'in',
            'notes' => 'Stok awal produk',
        ]);
    }
}
