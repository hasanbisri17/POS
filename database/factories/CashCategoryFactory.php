<?php

namespace Database\Factories;

use App\Models\CashCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class CashCategoryFactory extends Factory
{
    protected $model = CashCategory::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'description' => $this->faker->sentence(),
            'type' => $this->faker->randomElement(['income', 'expense']),
            'is_active' => true,
            'is_system' => false,
        ];
    }

    public function income(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'income',
        ]);
    }

    public function expense(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'expense',
        ]);
    }

    public function system(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_system' => true,
        ]);
    }

    public function sales(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Penjualan',
            'description' => 'Pemasukan dari penjualan produk',
            'type' => 'income',
            'is_system' => true,
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
