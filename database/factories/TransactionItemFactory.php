<?php

namespace Database\Factories;

use App\Models\TransactionItem;
use App\Models\Transaction;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionItemFactory extends Factory
{
    protected $model = TransactionItem::class;

    public function definition(): array
    {
        $product = Product::factory()->create();
        $quantity = $this->faker->numberBetween(1, 10);
        $price = $this->faker->numberBetween(5000, 50000);

        return [
            'transaction_id' => Transaction::factory(),
            'product_id' => $product->id,
            'product_variant_id' => ProductVariant::factory()->create(['product_id' => $product->id])->id,
            'quantity' => $quantity,
            'price' => $price,
            'subtotal' => $quantity * $price,
            'notes' => $this->faker->sentence(),
        ];
    }

    public function withoutVariant(): static
    {
        return $this->state(fn (array $attributes) => [
            'product_variant_id' => null,
        ]);
    }
}
