<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BannerSlider extends Model
{
    protected $table = 'banner_sliders';
    protected $fillable = [
        'name',
        'description',
        'button_text',
        'link_url',
        'image_path',
        'image_path_108_53',
        'sort_order',
        'is_active',
        'discount_percent'
    ];
}
