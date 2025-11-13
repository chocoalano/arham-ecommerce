# Contoh Penggunaan Image Ratio di Views

## Image Ratios Available

| Ratio | Size | Use Case | Accessor |
|-------|------|----------|----------|
| 27:28 | 540×560px | Large banner, main featured | `ratio_27_28_url` |
| 108:53 | 540×265px | Wide banner, horizontal | `ratio_108_53_url` |
| 51:52 | 255×260px | Small square, grid | `ratio_51_52_url` |
| 99:119 | 198×238px | Small portrait, sidebar | `ratio_99_119_url` |

## 1. Featured Categories (Banner Layout)

```blade
{{-- resources/views/livewire/featured-categories.blade.php --}}

{{-- Large banner on left - use Ratio 27:28 (540×560) --}}
@if(isset($categories[0]))
    <div class="col-lg-6 col-md-6 mb-sm-30">
        <div class="banner">
            <a href="{{ $categories[0]['url'] }}">
                <img width="540" height="560" 
                     src="{{ $categories[0]['image_ratio_27_28'] ?? $categories[0]['image'] }}" 
                     class="img-fluid" 
                     alt="{{ $categories[0]['name'] }}">
            </a>
            <span class="banner-category-title">
                <a href="{{ $categories[0]['url'] }}">{{ $categories[0]['name'] }}</a>
            </span>
        </div>
    </div>
@endif

{{-- Wide banner - use Ratio 108:53 (540×265) --}}
@if(isset($categories[1]))
    <div class="col-lg-12 col-md-12 mb-30">
        <div class="banner">
            <a href="{{ $categories[1]['url'] }}">
                <img width="550" height="270" 
                     src="{{ $categories[1]['image_ratio_108_53'] ?? $categories[1]['image'] }}" 
                     class="img-fluid" 
                     alt="{{ $categories[1]['name'] }}">
            </a>
            <span class="banner-category-title">
                <a href="{{ $categories[1]['url'] }}">{{ $categories[1]['name'] }}</a>
            </span>
        </div>
    </div>
@endif

{{-- Small square banners - use Ratio 51:52 (255×260) --}}
@if(isset($categories[2]))
    <div class="col-lg-6 col-md-6 col-sm-6 col-6">
        <div class="banner">
            <a href="{{ $categories[2]['url'] }}">
                <img width="265" height="270" 
                     src="{{ $categories[2]['image_ratio_51_52'] ?? $categories[2]['image'] }}" 
                     class="img-fluid" 
                     alt="{{ $categories[2]['name'] }}">
            </a>
            <span class="banner-category-title">
                <a href="{{ $categories[2]['url'] }}">{{ $categories[2]['name'] }}</a>
            </span>
        </div>
    </div>
@endif

{{-- Small portrait banner - use Ratio 99:119 (198×238) --}}
@if(isset($categories[3]))
    <div class="col-lg-6 col-md-6 col-sm-6 col-6">
        <div class="banner">
            <a href="{{ $categories[3]['url'] }}">
                <img width="265" height="270" 
                     src="{{ $categories[3]['image_ratio_99_119'] ?? $categories[3]['image'] }}" 
                     class="img-fluid" 
                     alt="{{ $categories[3]['name'] }}">
            </a>
            <span class="banner-category-title">
                <a href="{{ $categories[3]['url'] }}">{{ $categories[3]['name'] }}</a>
            </span>
        </div>
    </div>
@endif
```

## 2. Product Grid (CardProductCatalog)

```blade
{{-- Use Ratio 51:52 for grid view --}}
@foreach($products as $product)
    <div class="product-item">
        <img src="{{ $product->thumbnail?->ratio_51_52_url ?? $product->thumbnail?->url }}" 
             alt="{{ $product->name }}"
             class="product-thumbnail">
    </div>
@endforeach
```

## 3. Product Detail Page

```blade
{{-- Main image - use Ratio 27:28 or 108:53 --}}
<div class="product-main-image">
    <img src="{{ $product->images->first()?->ratio_27_28_url }}" 
         alt="{{ $product->name }}">
</div>

{{-- Thumbnails - use Ratio 51:52 --}}
<div class="product-thumbnails">
    @foreach($product->images as $image)
        <img src="{{ $image->ratio_51_52_url }}" 
             alt="{{ $image->alt_text }}"
             class="thumbnail">
    @endforeach
</div>
```

## 4. Sidebar Banners

```blade
{{-- Use Ratio 99:119 for sidebar portrait banners --}}
<div class="sidebar-banner">
    <img src="{{ $product->thumbnail?->ratio_99_119_url }}" 
         alt="{{ $product->name }}"
         class="w-100">
</div>
```

## 5. Responsive Image dengan srcset

```blade
<img src="{{ $image->ratio_51_52_url }}"
     srcset="{{ $image->ratio_51_52_url }} 255w,
             {{ $image->ratio_108_53_url }} 540w,
             {{ $image->ratio_27_28_url }} 540w,
             {{ $image->url }} 2000w"
     sizes="(max-width: 768px) 255px,
            (max-width: 1200px) 540px,
            2000px"
     alt="{{ $image->alt_text }}">
```

## 6. Update FeaturedCategories Component

Untuk menggunakan ratio gambar yang berbeda, update method `fetchCategories()`:

```php
// app/Livewire/FeaturedCategories.php

protected function fetchCategories(int $limit, bool $onlyRoot, bool $hideEmpty): array
{
    // ... existing query code ...

    return $rows->map(function ($row) {
        // Untuk category image atau product image
        $effectivePath = $row->effective_image_path;
        
        return [
            'id'    => $row->id,
            'slug'  => $row->slug,
            'name'  => $row->name,
            'count' => (int) ($row->products_active_count ?? 0),
            
            // Original/default
            'image' => $this->toUrl($effectivePath),
            
            // Different ratios
            'image_square' => $this->toUrl($this->getRatioPath($effectivePath, 'square')),
            'image_wide' => $this->toUrl($this->getRatioPath($effectivePath, 'wide')),
            'image_tall' => $this->toUrl($this->getRatioPath($effectivePath, 'tall')),
            
            'url'   => url('/category/' . $row->slug),
        ];
    })->toArray();
}

protected function getRatioPath(?string $path, string $ratio): ?string
{
    if (!$path) {
        return null;
    }
    
    $pathInfo = pathinfo($path);
    $baseName = $pathInfo['filename'];
    
    // Cek apakah sudah ada suffix _original, _square, dll
    if (preg_match('/_(original|square|wide|tall)$/', $baseName, $matches)) {
        // Ganti suffix yang ada dengan ratio baru
        $baseName = preg_replace('/_(original|square|wide|tall)$/', '_' . $ratio, $baseName);
    } else {
        // Tambah suffix ratio
        $baseName .= '_' . $ratio;
    }
    
    // Path gambar ratio selalu WebP kecuali original
    $extension = $ratio === 'original' ? $pathInfo['extension'] : 'webp';
    
    return $pathInfo['dirname'] . '/' . $baseName . '.' . $extension;
}
```

## Tips Penggunaan

1. **Grid View**: Gunakan `square_url` untuk konsistensi ukuran
2. **Banner/Hero**: Gunakan `wide_url` untuk landscape yang bagus
3. **Mobile Portrait**: Gunakan `tall_url` untuk tampilan portrait
4. **Detail/Zoom**: Gunakan `url` (original) untuk kualitas maksimal
5. **Fallback**: Selalu sediakan fallback ke `url` jika ratio tidak ada

## Performance Tips

```blade
{{-- Lazy loading --}}
<img src="{{ $image->square_url }}" 
     loading="lazy" 
     alt="{{ $image->alt_text }}">

{{-- Preload untuk gambar penting --}}
<link rel="preload" 
      as="image" 
      href="{{ $product->thumbnail->wide_url }}">
```
