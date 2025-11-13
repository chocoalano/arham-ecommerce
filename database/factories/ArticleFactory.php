<?php

namespace Database\Factories;

use App\Models\Article;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ArticleFactory extends Factory
{
    protected $model = Article::class;

    public function definition(): array
    {
        $title = ucfirst($this->faker->unique()->sentence(6));

        return [
            'author_id' => null,
            'title' => $title,
            'slug' => Str::slug($title).'-'.Str::random(5),
            'excerpt' => $this->faker->optional()->sentence(16),
            'content' => $this->faker->paragraphs(5, true),
            'status' => 'published',
            'reading_time' => $this->faker->numberBetween(2, 8),
            'cover_image' => $this->faker->optional()->imageUrl(1200, 700, 'business', true),
            'meta_title' => $this->faker->optional()->sentence(3),
            'meta_description' => $this->faker->optional()->sentence(10),
            'published_at' => now(),
            'scheduled_at' => null,
            'is_pinned' => false,
            'meta' => null,
        ];
    }
}
