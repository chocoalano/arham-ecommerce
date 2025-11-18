# Image Processing Service

## Overview
Service ini otomatis menggenerate 4 versi gambar dengan ratio berbeda saat upload gambar produk di Filament Admin.

## Ratio yang Di-generate

| Ratio | Dimensi | Suffix | Use Case |
|-------|---------|--------|----------|
| Original | Sesuai upload | `_original` | Kualitas maksimal, backup |
| 27:28 | 540×560px | `_ratio_27_28` | Large banner, featured category |
| 108:53 | 540×265px | `_ratio_108_53` | Wide banner, horizontal layout |
| 51:52 | 255×260px | `_ratio_51_52` | Small square banner, grid item |
| 99:119 | 198×238px | `_ratio_99_119` | Small portrait, sidebar banner |

## Database Schema

### Kolom di `product_images`:
- `path` - Original path (existing)
- `path_ratio_27_28` - Ratio 27:28 (540×560px)
- `path_ratio_108_53` - Ratio 108:53 (540×265px)
- `path_ratio_51_52` - Ratio 51:52 (255×260px)
- `path_ratio_99_119` - Ratio 99:119 (198×238px)

## Usage

### 1. Upload Otomatis (Filament Admin)
Saat upload gambar di Product Form, otomatis akan generate 4 ratio.

### 2. Mengakses Gambar di Blade

```blade
{{-- Original --}}
<img src="{{ $product->images->first()->url }}" alt="{{ $product->name }}">

{{-- Ratio 27:28 (540×560) - Large banner --}}
<img src="{{ $product->images->first()->ratio_27_28_url }}" alt="{{ $product->name }}">

{{-- Ratio 108:53 (540×265) - Wide banner --}}
<img src="{{ $product->images->first()->ratio_108_53_url }}" alt="{{ $product->name }}">

{{-- Ratio 51:52 (255×260) - Small square --}}
<img src="{{ $product->images->first()->ratio_51_52_url }}" alt="{{ $product->name }}">

{{-- Ratio 99:119 (198×238) - Small portrait --}}
<img src="{{ $product->images->first()->ratio_99_119_url }}" alt="{{ $product->name }}">
```

### 3. Manual Processing

```php
use App\Services\ImageProcessingService;

$service = new ImageProcessingService();

// Upload dengan ratio
$paths = $service->uploadWithRatios($uploadedFile, 'products', 'public');
// Returns: [
//   'original' => '...', 
//   'ratio_27_28' => '...', 
//   'ratio_108_53' => '...', 
//   'ratio_51_52' => '...', 
//   'ratio_99_119' => '...'
// ]

// Hapus semua versi
$service->deleteAllVersions($originalPath, 'public');
```

## File Format
- Original: Menggunakan format upload asli (JPG/PNG/WEBP)
- Generated ratios: WebP dengan quality 85%

## Performance
- Menggunakan GD Library native PHP (no external dependencies)
- Cover mode: Crop smart untuk mempertahankan aspect ratio
- Preserves transparency untuk PNG/WEBP

## Storage Structure
```
storage/app/public/products/
├── 1699876543_abc123_original.jpg
├── 1699876543_abc123_ratio_27_28.webp
├── 1699876543_abc123_ratio_108_53.webp
├── 1699876543_abc123_ratio_51_52.webp
└── 1699876543_abc123_ratio_99_119.webp
```

## Migration
```bash
php artisan migrate
```

Migrations: 
- `2025_11_13_072512_add_ratio_paths_to_product_images_table.php`
- `2025_11_13_073550_update_product_images_ratio_columns.php`
