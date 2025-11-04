<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Newsletter extends Model
{
    protected $fillable = [
        'email',
        'status',
        'subscribed_at',
        'unsubscribed_at',
    ];

    protected function casts(): array
    {
        return [
            'subscribed_at' => 'datetime',
            'unsubscribed_at' => 'datetime',
        ];
    }

    /**
     * Scope untuk subscriber aktif
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Boot method
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($newsletter) {
            if (empty($newsletter->subscribed_at)) {
                $newsletter->subscribed_at = now();
            }
        });
    }
}
