<?php

namespace Database\Factories;

use App\Models\PaymentLog;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentLogFactory extends Factory
{
    protected $model = PaymentLog::class;

    public function definition(): array
    {
        return [
            'type' => 'notification',
            'headers' => ['X-Signature' => $this->faker->sha256],
            'payload' => ['status' => 'ok'],
            'ip_address' => $this->faker->ipv4(),
            'occurred_at' => now(),
        ];
    }
}
