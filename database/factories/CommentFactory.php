<?php

namespace Database\Factories;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    protected $model = Comment::class;

    public function definition(): array
    {
        return [
            'guest_name' => $this->faker->optional()->name(),
            'guest_email' => $this->faker->optional()->safeEmail(),
            'content' => $this->faker->sentence(20),
            'status' => 'approved',
        ];
    }
}
