<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductImage extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'path', 'path_ratio_27_28', 'path_ratio_108_53', 'path_ratio_51_52', 'path_ratio_99_119', 'alt_text', 'is_thumbnail', 'sort_order'];

    protected $casts = [
        'is_thumbnail' => 'boolean',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get URL for original image
     */
    public function getUrlAttribute(): string
    {
        return $this->getImageUrl($this->path);
    }

    /**
     * Get URL for ratio 27:28 (540x560) - Large banner
     */
    public function getRatio2728UrlAttribute(): string
    {
        return $this->getImageUrl($this->path_ratio_27_28 ?? $this->path);
    }

    /**
     * Get URL for ratio 108:53 (540x265) - Wide banner
     */
    public function getRatio10853UrlAttribute(): string
    {
        return $this->getImageUrl($this->path_ratio_108_53 ?? $this->path);
    }

    /**
     * Get URL for ratio 51:52 (255x260) - Small square banner
     */
    public function getRatio5152UrlAttribute(): string
    {
        return $this->getImageUrl($this->path_ratio_51_52 ?? $this->path);
    }

    /**
     * Get URL for ratio 99:119 (198x238) - Small portrait banner
     */
    public function getRatio99119UrlAttribute(): string
    {
        return $this->getImageUrl($this->path_ratio_99_119 ?? $this->path);
    }

    /**
     * Get image URL with fallback
     */
    protected function getImageUrl(?string $path): string
    {
        if (! $path || trim($path) === '') {
            return asset('images/placeholder.jpg');
        }

        if (preg_match('~^https?://~i', $path)) {
            return $path;
        }

        try {
            return \Storage::url($path);
        } catch (\Throwable $e) {
            return asset(ltrim($path, '/'));
        }
    }
}
