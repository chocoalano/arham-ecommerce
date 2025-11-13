<?php

namespace Database\Factories;

use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderItemFactory extends Factory
{
    protected $model = OrderItem::class;

    public function definition(): array
    {
        $price = $this->faker->randomFloat(2, 8000, 300000);
        $qty = $this->faker->numberBetween(1, 4);

        return [
            'sku' => strtoupper($this->faker->bothify('ORD-####-??')),
            'name' => ucfirst($this->faker->words(3, true)),
            'weight_gram' => $this->faker->numberBetween(50, 1200),
            'quantity' => $qty,
            'price' => $price,
            'subtotal' => $price * $qty,
            'meta' => null,
        ];
    }
}
