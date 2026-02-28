<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    /**
     * Tampilkan daftar artikel (mendukung pencarian & pagination).
     */
    public function index(Request $request)
    {
        $perPage = (int) ($request->integer('per_page') ?: 12);
        $q = trim((string) $request->get('q', ''));

        $articles = Article::query()
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($x) use ($q) {
                    $x->where('title', 'like', "%{$q}%")
                        ->orWhere('slug', 'like', "%{$q}%");
                });
            })
            ->orderByDesc('created_at')      // aman di berbagai skema
            ->paginate($perPage)
            ->withQueryString();             // bawa parameter query saat paginate

        return view('articles', [
            'articles' => $articles,
            'q' => $q,
        ]);
    }

    /**
     * Tampilkan detail artikel berdasarkan slug.
     */
    public function show(string $slug)
    {
        $article = Article::with('categories')
            ->where('slug', $slug)
            ->firstOrFail();

        $categoryIds = $article->categories->pluck('id');

        // 5 artikel terkait berdasarkan kategori yang sama
        $related = Article::query()
            ->where('id', '<>', $article->id)
            ->where('status', 'published')
            ->when($categoryIds->isNotEmpty(), fn($q) =>
                $q->whereHas('categories', fn($c) =>
                    $c->whereIn('article_article_category.article_category_id', $categoryIds)
                )
            )
            ->orderByDesc('published_at')
            ->limit(5)
            ->get();

        // 5 top artikel (pinned terlebih dahulu, lalu terbaru)
        $topArticles = Article::query()
            ->where('id', '<>', $article->id)
            ->where('status', 'published')
            ->orderByDesc('is_pinned')
            ->orderByDesc('published_at')
            ->limit(5)
            ->get();

        // 5 latest artikel
        $latestArticles = Article::query()
            ->where('id', '<>', $article->id)
            ->where('status', 'published')
            ->orderByDesc('published_at')
            ->limit(5)
            ->get();

        return view('articles_detail', [
            'article'        => $article,
            'related'        => $related,
            'topArticles'    => $topArticles,
            'latestArticles' => $latestArticles,
        ]);
    }
}
