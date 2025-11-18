<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Page extends Model
{
    /** @use HasFactory<\Database\Factories\PageFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'sections',
        'meta',
        'template',
        'is_active',
        'show_in_footer',
        'footer_order',
    ];

    protected function casts(): array
    {
        return [
            'sections' => 'array',
            'meta' => 'array',
            'is_active' => 'boolean',
            'show_in_footer' => 'boolean',
        ];
    }

    /**
     * Boot method to auto-generate slug
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($page) {
            if (empty($page->slug)) {
                $page->slug = Str::slug($page->title);
            }
        });
    }

    /**
     * Scope untuk halaman aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope untuk halaman yang tampil di footer
     */
    public function scopeFooter($query)
    {
        return $query->where('show_in_footer', true)
            ->where('is_active', true)
            ->orderBy('footer_order');
    }

    /**
     * Get page by slug
     */
    public function scopeBySlug($query, string $slug)
    {
        return $query->where('slug', $slug);
    }

    /**
     * Get route key name (use slug for URLs)
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
