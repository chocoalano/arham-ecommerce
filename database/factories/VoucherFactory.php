<?php

namespace Database\Factories;

use App\Models\Voucher;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class VoucherFactory extends Factory
{
    protected $model = Voucher::class;

    public function definition(): array
    {
        $type = $this->faker->randomElement(['percent', 'fixed', 'free_shipping']);

        return [
            'code' => strtoupper(Str::random(8)),
            'type' => $type,
            'value' => $type === 'percent' ? $this->faker->numberBetween(5, 25) : ($type === 'fixed' ? $this->faker->numberBetween(5000, 50000) : 0),
            'max_discount' => $this->faker->optional(0.5)->numberBetween(10000, 100000),
            'min_subtotal' => $this->faker->optional(0.5)->numberBetween(50000, 250000),
            'usage_limit' => $this->faker->optional(0.4)->numberBetween(50, 1000),
            'used_count' => 0,
            'is_active' => true,
            'valid_from' => Carbon::now()->subDays($this->faker->numberBetween(0, 10)),
            'valid_until' => Carbon::now()->addDays($this->faker->numberBetween(10, 60)),
            'applicable' => ['scope' => 'all'],
        ];
    }
}
