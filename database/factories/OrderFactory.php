<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'order_number' => strtoupper(Str::random(4)).'-'.$this->faker->unique()->numerify('######'),
            'customer_name' => $this->faker->name(),
            'customer_email' => $this->faker->safeEmail(),
            'customer_phone' => $this->faker->phoneNumber(),
            'currency' => 'IDR',
            'subtotal' => 0,
            'discount_total' => 0,
            'tax_total' => 0,
            'shipping_total' => 0,
            'grand_total' => 0,
            'shipping_courier' => null,
            'shipping_service' => null,
            'shipping_cost' => null,
            'shipping_etd' => null,
            'weight_total_gram' => 0,
            'status' => 'pending',
            'placed_at' => now(),
            'paid_at' => null,
            'cancelled_at' => null,
            'source' => 'web',
            'notes' => null,
            'meta' => null,
        ];
    }
}
