<?php

namespace App\Repositories\Eloquent;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductReview;
use App\Models\ProductVariant;
use App\Models\Wishlist;
use App\Models\WishlistItem;
use App\Repositories\Contracts\WishlistRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Throwable;

class WishlistRepository implements WishlistRepositoryInterface
{
    public function getOrCreateByCustomerId(int $customerId)
    {
        return Wishlist::firstOrCreate(['customer_id' => $customerId]);
    }

    public function count(int $customerId): int
    {
        $wishlist = Wishlist::where('customer_id', $customerId)->first();
        if (! $wishlist) {
            return 0;
        }

        return (int) WishlistItem::where('wishlist_id', $wishlist->id)->count();
    }

    public function items(int $customerId): array
    {
        $wishlist = Wishlist::with('items')
            ->where('customer_id', $customerId)
            ->first();

        if (! $wishlist) {
            return [];
        }

        $productIds = $wishlist->items
            ->where('purchasable_type', Product::class)
            ->pluck('purchasable_id')->all();

        $variantIds = $wishlist->items
            ->where('purchasable_type', ProductVariant::class)
            ->pluck('purchasable_id')->all();

        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

        $variants = ProductVariant::with('product')
            ->whereIn('id', $variantIds)
            ->get()->keyBy('id');

        // (opsional) ambil agregat rating sekaligus
        $ratings = ProductReview::select('reviewable_id',
            DB::raw('AVG(rating) as avg_rating'),
            DB::raw('COUNT(*) as reviews_count'))
            ->where('reviewable_type', Product::class)
            ->whereIn('reviewable_id', array_unique(array_merge(
                $products->keys()->all(),
                $variants->pluck('product_id')->all()
            )))
            ->where('status', 'approved')
            ->groupBy('reviewable_id')
            ->get()
            ->keyBy('reviewable_id');

        return $wishlist->items
            ->sortByDesc('created_at')
            ->map(function (WishlistItem $wi) use ($products, $variants, $ratings) {
                $product = null;
                $variant = null;

                if ($wi->purchasable_type === Product::class) {
                    $product = $products->get($wi->purchasable_id);
                } else {
                    $variant = $variants->get($wi->purchasable_id);
                    $product = $variant?->product;
                }

                if (! $product) {
                    return null;
                }

                $card = $this->buildCardData($product, $variant, $ratings[$product->id] ?? null);

                return [
                    'id' => $wi->id,
                    'purchasable_type' => $wi->purchasable_type,
                    'purchasable_id' => $wi->purchasable_id,
                    'added_at' => $wi->created_at,
                    'card' => $card,
                ];
            })
            ->filter()
            ->values()
            ->all();
    }

    public function toggle(int $customerId, ?int $productId = null, ?int $variantId = null): array
    {
        return DB::transaction(function () use ($customerId, $productId, $variantId) {
            $wishlist = $this->getOrCreateByCustomerId($customerId);

            $purchasableType = Product::class;
            $purchasableId = (int) ($productId ?? 0);

            if (! empty($variantId)) {
                $variant = ProductVariant::with('product')
                    ->where('id', (int) $variantId)
                    ->where('is_active', true)
                    ->firstOrFail();

                $purchasableType = ProductVariant::class;
                $purchasableId = (int) $variant->id;
            }

            $existing = WishlistItem::where('wishlist_id', $wishlist->id)
                ->where('purchasable_type', $purchasableType)
                ->where('purchasable_id', $purchasableId)
                ->first();

            if ($existing) {
                $existing->delete();
                $inWishlist = false;
                $message = 'Dihapus dari wishlist';
            } else {
                WishlistItem::create([
                    'wishlist_id' => $wishlist->id,
                    'purchasable_type' => $purchasableType,
                    'purchasable_id' => $purchasableId,
                ]);
                $inWishlist = true;
                $message = 'Ditambahkan ke wishlist';
            }

            $count = WishlistItem::where('wishlist_id', $wishlist->id)->count();

            return [
                'success' => true,
                'message' => $message,
                'in_wishlist' => $inWishlist,
                'count' => (int) $count,
            ];
        });
    }

    public function showItem(int $customerId, int $itemId): array
    {
        $wishlist = Wishlist::where('customer_id', $customerId)->firstOrFail();

        $item = WishlistItem::where('wishlist_id', $wishlist->id)
            ->where('id', $itemId)
            ->firstOrFail();

        $product = null;
        $variant = null;

        if ($item->purchasable_type === Product::class) {
            $product = Product::findOrFail($item->purchasable_id);
        } else {
            $variant = ProductVariant::with('product')->findOrFail($item->purchasable_id);
            $product = $variant->product;
        }

        // rating agregat untuk produk ini
        $ratingAgg = ProductReview::select('reviewable_id',
            DB::raw('AVG(rating) as avg_rating'),
            DB::raw('COUNT(*) as reviews_count'))
            ->where('reviewable_type', Product::class)
            ->where('reviewable_id', $product->id)
            ->where('status', 'approved')
            ->groupBy('reviewable_id')
            ->first();

        $card = $this->buildCardData($product, $variant, $ratingAgg);

        return [
            'success' => true,
            'item' => [
                'id' => $item->id,
                'purchasable_type' => $item->purchasable_type,
                'purchasable_id' => $item->purchasable_id,
                'added_at' => $item->created_at,
                'card' => $card,
            ],
        ];
    }

    public function updateItem(int $customerId, int $itemId, ?int $variantId = null): array
    {
        return DB::transaction(function () use ($customerId, $itemId, $variantId) {
            $wishlist = Wishlist::where('customer_id', $customerId)->firstOrFail();

            /** @var WishlistItem $item */
            $item = WishlistItem::where('wishlist_id', $wishlist->id)
                ->where('id', $itemId)
                ->firstOrFail();

            $product = null;
            $variant = null;

            if (! empty($variantId)) {
                // pindah ke variant tertentu
                $variant = ProductVariant::with('product')
                    ->where('id', (int) $variantId)
                    ->where('is_active', true)
                    ->firstOrFail();

                $item->purchasable_type = ProductVariant::class;
                $item->purchasable_id = (int) $variant->id;
                $item->save();

                $product = $variant->product;
            } else {
                // kembalikan ke product base (jika awalnya variant)
                if ($item->purchasable_type === ProductVariant::class) {
                    $variant = ProductVariant::with('product')->findOrFail($item->purchasable_id);
                    $product = $variant->product;

                    $item->purchasable_type = Product::class;
                    $item->purchasable_id = (int) $product->id;
                    $item->save();
                } else {
                    $product = Product::findOrFail($item->purchasable_id);
                }
            }

            // rating agregat untuk produk ini
            $ratingAgg = ProductReview::select('reviewable_id',
                DB::raw('AVG(rating) as avg_rating'),
                DB::raw('COUNT(*) as reviews_count'))
                ->where('reviewable_type', Product::class)
                ->where('reviewable_id', $product->id)
                ->where('status', 'approved')
                ->groupBy('reviewable_id')
                ->first();

            $card = $this->buildCardData($product, $variant, $ratingAgg);

            return [
                'success' => true,
                'message' => 'Wishlist item diperbarui',
                'item' => [
                    'id' => $item->id,
                    'purchasable_type' => $item->purchasable_type,
                    'purchasable_id' => $item->purchasable_id,
                    'card' => $card,
                ],
            ];
        });
    }

    public function destroyItem(int $customerId, int $itemId): array
    {
        return DB::transaction(function () use ($customerId, $itemId) {
            $wishlist = Wishlist::where('customer_id', $customerId)->firstOrFail();

            $item = WishlistItem::where('wishlist_id', $wishlist->id)
                ->where('id', $itemId)
                ->firstOrFail();

            $item->delete();

            return [
                'success' => true,
                'message' => 'Item wishlist dihapus',
                'count' => (int) WishlistItem::where('wishlist_id', $wishlist->id)->count(),
            ];
        });
    }

    /* ================= Helpers ================ */

    protected function buildCardData(Product $product, ?ProductVariant $variant = null, $ratingAgg = null): array
    {
        $thumb = ProductImage::where('product_id', $product->id)
            ->where('is_thumbnail', 1)
            ->value('path');

        $fallbackImage = ProductImage::where('product_id', $product->id)
            ->orderBy('sort_order')
            ->value('path');

        $imagePath = $thumb ?: $fallbackImage ?: null;

        // rating dari agregasi (jika dipass)
        $avgRating = $ratingAgg ? (float) $ratingAgg->avg_rating : (float) ProductReview::where('reviewable_type', Product::class)
            ->where('reviewable_id', $product->id)
            ->where('status', 'approved')->avg('rating');
        $reviewsCount = $ratingAgg ? (int) $ratingAgg->reviews_count : (int) ProductReview::where('reviewable_type', Product::class)
            ->where('reviewable_id', $product->id)
            ->where('status', 'approved')->count();

        if ($variant) {
            $price = (float) ($variant->price ?? 0);
            $sale = isset($variant->sale_price) ? (float) $variant->sale_price : null;
            $name = $product->name.' - '.($variant->name ?? '');
        } else {
            $price = (float) ($product->price ?? 0);
            $sale = isset($product->sale_price) ? (float) $product->sale_price : null;
            $name = $product->name;
        }

        $final = ($sale !== null && $sale > 0 && $sale < $price) ? $sale : $price;

        $discount = ($sale !== null && $sale > 0 && $sale < $price)
            ? (int) round((($price - $sale) / max(1, $price)) * 100)
            : null;

        return [
            'id' => $product->id,
            'slug' => $product->slug,
            'url' => url('/product/'.$product->slug),
            'name' => $name,
            'image' => $this->toUrl($imagePath),
            'price' => $price,
            'sale_price' => $sale,
            'final_price' => $final,
            'discount' => $discount,
            'rating_avg' => $avgRating ? round($avgRating, 1) : null,
            'rating_count' => $reviewsCount,
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
}
