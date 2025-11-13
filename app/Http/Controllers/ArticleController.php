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
        $article = Article::query()
            ->where('slug', $slug)
            ->firstOrFail();

        // Artikel terkait sederhana: 3 artikel terbaru lainnya
        $related = Article::query()
            ->where('id', '<>', $article->id)
            ->orderByDesc('created_at')
            ->limit(3)
            ->get();

        return view('articles_detail', [
            'article' => $article,
            'related' => $related,
        ]);
    }
}
