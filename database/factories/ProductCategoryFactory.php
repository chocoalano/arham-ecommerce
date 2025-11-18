<?php

namespace Database\Factories;

use App\Models\ProductCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductCategoryFactory extends Factory
{
    protected $model = ProductCategory::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->words(2, true);

        return [
            'parent_id' => null,
            'name' => ucfirst($name),
            'slug' => Str::slug($name).'-'.Str::random(4),
            'description' => $this->faker->optional()->paragraph(),
            'image_path' => $this->faker->optional()->imageUrl(800, 600, 'food', true),
            'is_active' => true,
            'sort_order' => $this->faker->numberBetween(0, 20),
            'meta' => ['seo' => $this->faker->words(3)],
        ];
    }
}
