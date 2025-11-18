<?php

namespace Database\Factories;

use App\Models\Shipment;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShipmentFactory extends Factory
{
    protected $model = Shipment::class;

    public function definition(): array
    {
        return [
            'courier' => $this->faker->randomElement(['jne', 'tiki', 'pos', 'sicepat']),
            'service' => $this->faker->randomElement(['REG', 'YES', 'OKE']),
            'waybill' => $this->faker->optional()->bothify('WB########'),
            'cost' => $this->faker->randomFloat(2, 8000, 45000),
            'etd' => $this->faker->randomElement(['1-2 HARI', '2-3 HARI', '3-5 HARI']),
            'shipped_at' => null,
            'delivered_at' => null,
            'receiver_name' => null,
            'status' => 'pending',
            'raw_response' => null,
            'origin_id' => $this->faker->numberBetween(1, 2000),
            'destination_id' => $this->faker->numberBetween(1, 2000),
        ];
    }
}
