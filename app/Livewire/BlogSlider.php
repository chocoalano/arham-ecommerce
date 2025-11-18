<?php

namespace App\Livewire;

use App\Models\Article;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;

class BlogSlider extends Component
{
    /** @var array<int, array<string, mixed>> */
    public array $posts = [];

    /** Banyaknya artikel ditampilkan */
    public int $limit = 6;

    /** Sertakan hanya artikel yang sudah terbit (published_at <= now) */
    public bool $onlyPublished = true;

    public function mount(int $limit = 6, bool $onlyPublished = true): void
    {
        $this->limit = $limit;
        $this->onlyPublished = $onlyPublished;
        $this->posts = $this->fetchPosts($this->limit, $this->onlyPublished);
    }

    /**
     * Ambil artikel:
     * - status ∈ {published, active} (supaya aman untuk variasi enum)
     * - published_at <= now() bila $onlyPublished = true
     * - eager load: author, categories (anti N+1)
     * - SELECT hanya kolom aman & umum: id, title, slug, excerpt, content, status, published_at, author_id, meta
     */
    protected function fetchPosts(int $limit, bool $onlyPublished): array
    {
        $cacheKey = "blog_slider_articles_v1_{$limit}_".($onlyPublished ? 'published' : 'all');

        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($limit, $onlyPublished) {
            $q = Article::query()
                ->with([
                    'author:id,name',                    // pastikan relasi author() -> belongsTo(User::class,'author_id')
                    'categories:id,slug,name',          // relasi categories() -> belongsToMany(ArticleCategory::class, ...)
                ])
                ->whereIn('status', ['published', 'active'])
                ->select([
                    'id', 'author_id', 'title', 'slug', 'excerpt', 'content',
                    'status', 'published_at', 'meta',
                ])
                ->orderByDesc('is_pinned')            // jika ada flag pin
                ->orderByDesc('published_at');

            if ($onlyPublished) {
                $q->whereNotNull('published_at')
                    ->where('published_at', '<=', now());
            }

            $articles = $q->limit($limit)->get();

            return $articles->map(function (Article $a) {
                $image = $this->pickImagePath($a); // cari path gambar dari beberapa kandidat
                $excerpt = $this->makeExcerpt($a->excerpt, $a->content);
                $readingTime = $this->estimateReadingTime($a->content);

                $firstCategory = optional($a->categories->first());
                $category = $firstCategory ? [
                    'name' => $firstCategory->name,
                    'url' => url('/blog/category/'.$firstCategory->slug), // ganti ke route() jika ada
                ] : null;

                return [
                    'id' => $a->id,
                    'title' => $a->title,
                    'slug' => $a->slug,
                    'excerpt' => $excerpt,
                    'image'        => $this->toUrl($image) ?? asset('images/placeholder.jpg'),
                    'author' => optional($a->author)->name,
                    'date' => optional($a->published_at)->format('Y-m-d'),
                    'reading_time' => $readingTime, // dalam menit (perkiraan)
                    'category' => $category,
                    'url' => url('/article/'.$a->slug),              // ganti ke route('articles.show', $a)
                ];
            })->toArray();
        });
    }

    /** Pilih path gambar artikel dari beberapa kandidat tanpa menyentuh SQL kolom yang belum tentu ada */
    protected function pickImagePath(Article $a): ?string
    {
        // kandidat atribut langsung
        $candidates = [
            data_get($a, 'image_path'),
            data_get($a, 'cover_path'),
            data_get($a, 'thumbnail_path'),
            data_get($a, 'cover'),
            data_get($a, 'image'),
        ];

        // kandidat dari meta JSON (misal disimpan di meta->image/cover/thumbnail)
        $meta = is_array($a->meta) ? $a->meta : json_decode((string) $a->meta, true);
        if (is_array($meta)) {
            $candidates[] = data_get($meta, 'image');
            $candidates[] = data_get($meta, 'cover');
            $candidates[] = data_get($meta, 'thumbnail');
            $candidates[] = data_get($meta, 'og_image');
        }

        foreach ($candidates as $path) {
            $path = is_string($path) ? trim($path) : '';
            if ($path !== '') {
                return $path;
            }
        }

        return null;
    }

    /** Buat excerpt yang aman */
    protected function makeExcerpt(?string $excerpt, ?string $content, int $limit = 140): string
    {
        $text = trim((string) ($excerpt ?: Str::limit(strip_tags((string) $content), $limit)));

        return $text;
    }

    /** Estimasi waktu baca (kata/200 wpm) */
    protected function estimateReadingTime(?string $htmlOrText): int
    {
        $text = strip_tags((string) $htmlOrText);
        $words = str_word_count($text);

        return max(1, (int) ceil($words / 200));
    }

    /** Konversi path → URL publik, dengan fallback placeholder */
    protected function toUrl(?string $path): string
    {
        if (! $path || trim((string) $path) === '') {
            return asset('images/placeholder.jpg');
        }
        if (preg_match('~^https?://~i', $path)) {
            return $path;
        }
        try {
            return Storage::url($path);
        } catch (\Throwable $e) {
            return asset(ltrim($path, '/'));
        }
    }

    public function render()
    {
        return view('livewire.blog-slider');
    }
}
