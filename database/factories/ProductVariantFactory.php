<?php

namespace Database\Factories;

use App\Models\ProductVariant;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductVariantFactory extends Factory
{
    protected $model = ProductVariant::class;

    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'name' => $this->faker->randomElement([
                'Ukuran Gelas',
                'Level Gula',
                'Level Es',
                'Topping',
            ]),
            'price_adjustment' => $this->faker->numberBetween(0, 5000),
            'is_active' => true,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    public function size(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => $this->faker->randomElement([
                'Small',
                'Medium',
                'Large',
            ]),
            'price_adjustment' => $this->faker->randomElement([0, 2000, 4000]),
        ]);
    }

    public function sugarLevel(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => $this->faker->randomElement([
                'Less Sugar',
                'Normal Sugar',
                'Extra Sugar',
            ]),
            'price_adjustment' => 0,
        ]);
    }

    public function topping(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => $this->faker->randomElement([
                'Bubble',
                'Jelly',
                'Pudding',
                'Cheese Foam',
            ]),
            'price_adjustment' => $this->faker->randomElement([3000, 4000, 5000]),
        ]);
    }
}
