<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Page>
 */
class PageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence(3);

        return [
            'title' => $title,
            'slug' => \Illuminate\Support\Str::slug($title),
            'content' => fake()->paragraphs(5, true),
            'sections' => null,
            'meta' => [
                'description' => fake()->sentence(10),
                'keywords' => fake()->words(5, true),
            ],
            'template' => 'default',
            'is_active' => true,
            'show_in_footer' => fake()->boolean(30),
            'footer_order' => fake()->numberBetween(1, 10),
        ];
    }

    /**
     * Indicate that the page should be shown in footer
     */
    public function footer(): static
    {
        return $this->state(fn (array $attributes) => [
            'show_in_footer' => true,
        ]);
    }

    /**
     * Indicate that the page is inactive
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
