<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\Comment;
use App\Models\Customer;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class ContentSeeder extends Seeder
{
    public function run(): void
    {
        // Article categories
        $parents = ArticleCategory::factory()->count(3)->create();
        foreach ($parents as $p) {
            ArticleCategory::factory()->count(rand(1, 3))->create(['parent_id' => $p->id]);
        }

        // Tags
        $tags = Tag::factory()->count(8)->create();

        // Articles
        $allCats = ArticleCategory::all();
        $articles = Article::factory()->count(10)->create();

        foreach ($articles as $a) {
            $a->categories()->sync($allCats->random(rand(1, 2))->pluck('id')->toArray());
            $a->tags()->sync($tags->random(rand(1, 3))->pluck('id')->toArray());
        }

        // Comments by customers
        $customers = Customer::all();
        foreach ($articles as $a) {
            foreach (range(1, rand(1, 4)) as $_) {
                $cust = $customers->random();
                Comment::factory()->create([
                    'article_id' => $a->id,
                    'customer_id' => $cust->id,
                ]);
            }
        }
    }
}
