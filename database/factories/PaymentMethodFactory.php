<?php

namespace Database\Factories;

use App\Models\PaymentMethod;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PaymentMethodFactory extends Factory
{
    protected $model = PaymentMethod::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->randomElement(['Transfer Bank', 'E-Wallet', 'Debit Card', 'Credit Card', 'OVO', 'GoPay', 'ShopeePay', 'Dana']);
        
        return [
            'name' => $name,
            'code' => Str::slug($name) . '-' . Str::random(4),
            'description' => $this->faker->sentence(),
            'config' => [
                'requires_verification' => $this->faker->boolean(),
                'auto_confirm' => $this->faker->boolean(),
            ],
            'is_active' => true,
        ];
    }

    public function cash(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Tunai',
            'code' => 'cash',
            'description' => 'Pembayaran tunai',
            'config' => [
                'requires_verification' => false,
                'auto_confirm' => true,
            ],
            'is_active' => true,
        ]);
    }

    public function qris(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'QRIS',
            'code' => 'qris',
            'description' => 'Pembayaran menggunakan QRIS',
            'config' => [
                'requires_verification' => true,
                'auto_confirm' => false,
                'merchant_id' => $this->faker->uuid(),
            ],
            'is_active' => true,
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
