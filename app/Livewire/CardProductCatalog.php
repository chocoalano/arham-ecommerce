<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductReview;
use App\Models\ProductVariant;
// Models
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Throwable;

class CardProductCatalog extends Component
{
    public int $productId;

    public array $p = [];

    public int $qty = 1;

    public bool $inWishlist = false;

    public bool $showActions = true;

    public ?int $variantId = null;

    // Properties untuk notification
    public string $notificationMessage = '';

    public string $notificationType = '';

    public function mount(
        int $productId,
        int $qty = 1,
        bool $inWishlist = false,
        bool $showActions = true,
        ?int $variantId = null
    ): void {
        $this->productId = $productId;
        $this->qty = max(1, $qty);
        $this->inWishlist = $inWishlist;
        $this->showActions = $showActions;
        $this->variantId = $variantId;

        $this->p = $this->buildCardData($this->productId);
    }

    /** === Actions === */
    public function goToDetail(): void
    {
        $url = $this->p['url'] ?? route('catalog.show', ['slug' => $this->p['slug'] ?? null]);
        $this->redirect($url, navigate: true);
    }

    /** === Helpers === */
    protected function buildCardData(int $id): array
    {
        $minSortPerProduct = ProductImage::query()
            ->select('product_id', DB::raw('MIN(sort_order) as min_sort'))
            ->groupBy('product_id');

        $minVariantPriceSub = ProductVariant::query()
            ->where('is_active', true)
            ->selectRaw('MIN(COALESCE(sale_price, price))')
            ->whereColumn('product_id', 'products.id');

        $avgRatingSub = ProductReview::query()
            ->where('status', 'approved')
            ->where('reviewable_type', Product::class)
            ->selectRaw('AVG(rating)')
            ->whereColumn('reviewable_id', 'products.id');

        $countRatingSub = ProductReview::query()
            ->where('status', 'approved')
            ->where('reviewable_type', Product::class)
            ->selectRaw('COUNT(*)')
            ->whereColumn('reviewable_id', 'products.id');

        $row = Product::query()
            ->leftJoin('product_images as thumb', function ($join) {
                $join->on('thumb.product_id', '=', 'products.id')
                    ->where('thumb.is_thumbnail', '=', 1);
            })
            ->leftJoinSub($minSortPerProduct, 'ms', fn ($join) => $join->on('ms.product_id', '=', 'products.id'))
            ->leftJoin('product_images as img', function ($join) {
                $join->on('img.product_id', '=', 'products.id')
                    ->on('img.sort_order', '=', 'ms.min_sort');
            })
            ->where('products.id', $id)
            ->select([
                'products.id',
                'products.slug',
                'products.name',
                'products.short_description',
                'products.price',
                'products.sale_price',
                DB::raw('COALESCE(thumb.path, img.path) as image_path'),
                DB::raw('COALESCE(thumb.path_ratio_99_119, img.path_ratio_99_119) as image_99_119'),
            ])
            ->selectSub($minVariantPriceSub, 'from_variant_price')
            ->selectSub($avgRatingSub, 'avg_rating')
            ->selectSub($countRatingSub, 'reviews_count')
            ->firstOrFail();

        $price = (float) ($row->price ?? 0);
        $sale = $row->sale_price !== null ? (float) $row->sale_price : null;
        $final = ($sale !== null && $sale > 0 && $sale < $price) ? $sale : $price;
        $discount = ($sale !== null && $sale > 0 && $sale < $price)
            ? (int) round((($price - $sale) / max(1, $price)) * 100)
            : null;
        $fromVariant = $row->from_variant_price !== null ? (float) $row->from_variant_price : null;

        return [
            'id' => $row->id,
            'slug' => $row->slug,
            'url' => route('catalog.show', ['slug' => $row->slug]),
            'name' => $row->name,
            'image' => $this->toUrl($row->image_path) ?? asset('images/placeholder.jpg'),
            'image_99_119' => $this->toUrl($row->image_99_119 ?? $row->image_path),
            'price' => $price,
            'sale_price' => $sale,
            'final_price' => $final,
            'from_variant' => $fromVariant,
            'discount' => $discount,
            'rating_avg' => $row->avg_rating !== null ? round((float) $row->avg_rating, 1) : null,
            'rating_count' => (int) ($row->reviews_count ?? 0),
            'is_new' => false,
        ];
    }

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
        } catch (Throwable $e) {
            return asset(ltrim($path, '/'));
        }
    }

    public function render()
    {
        return view('livewire.card-product-catalog');
    }
}
