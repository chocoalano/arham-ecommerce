# Quick Reference: Image Ratios

## 📐 Ratio Specifications

| Ratio | Width | Height | Size | File Suffix | Accessor Method | Use Case |
|-------|-------|--------|------|-------------|-----------------|----------|
| **27:28** | 540px | 560px | 540×560 | `_ratio_27_28.webp` | `->ratio_27_28_url` | Large banner, main featured category |
| **108:53** | 540px | 265px | 540×265 | `_ratio_108_53.webp` | `->ratio_108_53_url` | Wide banner, horizontal layout |
| **51:52** | 255px | 260px | 255×260 | `_ratio_51_52.webp` | `->ratio_51_52_url` | Small square, grid item |
| **99:119** | 198px | 238px | 198×238 | `_ratio_99_119.webp` | `->ratio_99_119_url` | Small portrait, sidebar |

## 🎨 Featured Categories Layout Mapping

```
┌─────────────────────────────────────────────────────────┐
│                    FEATURED CATEGORIES                   │
├──────────────────────────┬──────────────────────────────┤
│                          │                              │
│                          │    Wide Banner (108:53)      │
│   Large Banner (27:28)   │    540 × 265px               │
│   540 × 560px            │                              │
│                          ├─────────────┬────────────────┤
│                          │  Square     │   Portrait     │
│                          │  (51:52)    │   (99:119)     │
│                          │  255×260    │   198×238      │
└──────────────────────────┴─────────────┴────────────────┘
```

## 💾 Database Columns

```php
product_images table:
├── path                  // Original image
├── path_ratio_27_28      // 540×560px (WebP)
├── path_ratio_108_53     // 540×265px (WebP)
├── path_ratio_51_52      // 255×260px (WebP)
└── path_ratio_99_119     // 198×238px (WebP)
```

## 🔧 Model Accessors

```php
$image = ProductImage::first();

// Original
$image->url                    // storage/products/xxx_original.jpg

// Ratios
$image->ratio_27_28_url        // storage/products/xxx_ratio_27_28.webp
$image->ratio_108_53_url       // storage/products/xxx_ratio_108_53.webp
$image->ratio_51_52_url        // storage/products/xxx_ratio_51_52.webp
$image->ratio_99_119_url       // storage/products/xxx_ratio_99_119.webp
```

## 📝 Blade Usage Examples

### Large Banner
```blade
<img src="{{ $image->ratio_27_28_url }}" 
     width="540" height="560" 
     alt="Large banner">
```

### Wide Banner
```blade
<img src="{{ $image->ratio_108_53_url }}" 
     width="540" height="265" 
     alt="Wide banner">
```

### Small Square
```blade
<img src="{{ $image->ratio_51_52_url }}" 
     width="255" height="260" 
     alt="Small square">
```

### Small Portrait
```blade
<img src="{{ $image->ratio_99_119_url }}" 
     width="198" height="238" 
     alt="Small portrait">
```

## 📊 File Size Comparison

Estimated file sizes (actual varies by image content):

| Ratio | Dimensions | Approx Size | Savings vs Original |
|-------|-----------|-------------|---------------------|
| Original | Variable | 500KB - 2MB | - |
| 27:28 | 540×560 | 30-60KB | ~85-95% |
| 108:53 | 540×265 | 15-30KB | ~90-97% |
| 51:52 | 255×260 | 8-15KB | ~95-98% |
| 99:119 | 198×238 | 6-12KB | ~96-99% |

## 🚀 Performance Tips

1. **Always use the appropriate ratio** for each use case
2. **Lazy load** images that are below the fold
3. **Preload** critical images (hero/banner)
4. **Use srcset** for responsive images when needed
5. **Add loading="lazy"** to non-critical images

## 🎯 Best Practices

```blade
{{-- ✅ Good: Use specific ratio --}}
<img src="{{ $product->thumbnail->ratio_51_52_url }}" 
     width="255" height="260"
     loading="lazy"
     alt="{{ $product->name }}">

{{-- ❌ Bad: Use original for small display --}}
<img src="{{ $product->thumbnail->url }}" 
     width="255" height="260"
     alt="{{ $product->name }}">

{{-- ⚠️ Fallback: Auto-fallback to original if ratio missing --}}
<img src="{{ $product->thumbnail->ratio_51_52_url ?? $product->thumbnail->url }}" 
     alt="{{ $product->name }}">
```

## 🔄 Service Methods

```php
use App\Services\ImageProcessingService;

$service = new ImageProcessingService();

// Upload and generate ratios
$paths = $service->uploadWithRatios($file, 'products', 'public');
// Returns: [
//   'original' => 'products/xxx_original.jpg',
//   'ratio_27_28' => 'products/xxx_ratio_27_28.webp',
//   'ratio_108_53' => 'products/xxx_ratio_108_53.webp',
//   'ratio_51_52' => 'products/xxx_ratio_51_52.webp',
//   'ratio_99_119' => 'products/xxx_ratio_99_119.webp',
// ]

// Delete all versions
$service->deleteAllVersions($originalPath, 'public');
```

## 📋 Checklist for Implementation

- [ ] Upload image via Filament Admin
- [ ] Verify 5 files created in storage
- [ ] Verify database columns filled
- [ ] Update Blade views to use appropriate ratios
- [ ] Test image loading on frontend
- [ ] Verify responsive behavior
- [ ] Check WebP browser support fallback
- [ ] Add lazy loading where appropriate
- [ ] Test with different image sizes/formats
- [ ] Monitor storage usage

---

**Last Updated:** November 13, 2025  
**Version:** 2.0 (Updated Ratios)
