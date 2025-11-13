<?php

namespace App\Repositories\Eloquent;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Wishlist;
use App\Models\WishlistItem;
use App\Repositories\Contracts\CartRepositoryInterface;
use Illuminate\Support\Facades\DB;

class CartRepository implements CartRepositoryInterface
{
    public function getByCustomerId(int $customerId, array $with = []): ?Cart
    {
        return Cart::with($with)->where('customer_id', $customerId)->first();
    }

    public function getOrCreateByCustomerId(int $customerId, string $currency = 'IDR'): Cart
    {
        return Cart::firstOrCreate(
            ['customer_id' => $customerId],
            ['currency' => $currency, 'expires_at' => now()->addDays(7)]
        );
    }

    public function countItems(int $customerId): int
    {
        $cart = $this->getByCustomerId($customerId);
        if (! $cart) {
            return 0;
        }

        return (int) CartItem::where('cart_id', $cart->id)->sum('quantity');
    }

    public function getSummary(int $customerId): array
    {
        $cart = $this->getByCustomerId($customerId, ['items.purchasable']);
        if (! $cart) {
            return [
                'items' => [],
                'total_items' => 0,
                'subtotal' => 0.0,
                'formatted_subtotal' => 'Rp 0',
                'cart_id' => null,
            ];
        }

        $items = $cart->items->map(function (CartItem $item) {
            return [
                'id' => $item->id,
                'sku' => $item->sku,
                'name' => $item->name,
                'quantity' => (int) $item->quantity,
                'price' => (float) $item->price,
                'subtotal' => (float) $item->subtotal,
                'purchasable_type' => $item->purchasable_type,
                'purchasable_id' => $item->purchasable_id,
            ];
        })->values();

        $total = (int) $cart->items->sum('quantity');
        $subtotal = $this->subtotalByCartId($cart->id);

        return [
            'items' => $items,
            'total_items' => $total,
            'subtotal' => $subtotal,
            'formatted_subtotal' => 'Rp '.number_format($subtotal, 0, ',', '.'),
            'cart_id' => $cart->id,
        ];
    }

    public function addItem(int $customerId, int $productId, ?int $variantId = null, int $quantity = 1): array
    {
        // Cek apakah butuh varian
        $hasVariants = ProductVariant::where('product_id', $productId)
            ->where('is_active', true);
        $cek = $hasVariants->exists();

        if ($cek && ! $variantId) {
            $variantId = $hasVariants->first()->id;
        }

        $result = DB::transaction(function () use ($customerId, $productId, $variantId, $quantity) {
            $cart = $this->getOrCreateByCustomerId($customerId);

            // Tentukan purchasable
            if ($variantId) {
                $variant = ProductVariant::with('product')
                    ->where('id', $variantId)
                    ->where('is_active', true)
                    ->firstOrFail();

                $purchasableType = ProductVariant::class;
                $purchasableId = $variantId;
                $price = (float) ($variant->sale_price ?? $variant->price ?? 0);
                $sku = $variant->sku ?? '';
                $name = ($variant->product->name ?? '').' - '.($variant->name ?? '');
                $weightGram = $variant->weight_gram ?? $variant->product->weight_gram ?? 0;

                if (isset($variant->stock_quantity) && $variant->stock_quantity < $quantity) {
                    throw new \RuntimeException('Stok tidak mencukupi');
                }
            } else {
                $product = Product::findOrFail($productId);

                $purchasableType = Product::class;
                $purchasableId = $productId;
                $price = (float) (($product->sale_price && $product->sale_price < $product->price)
                    ? $product->sale_price
                    : $product->price);
                $sku = $product->sku ?? '';
                $name = $product->name;
                $weightGram = $product->weight_gram ?? 0;

                if (isset($product->stock_quantity) && $product->stock_quantity < $quantity) {
                    throw new \RuntimeException('Stok tidak mencukupi');
                }
            }

            // Upsert item
            $item = CartItem::where('cart_id', $cart->id)
                ->where('purchasable_type', $purchasableType)
                ->where('purchasable_id', $purchasableId)
                ->first();

            if ($item) {
                $newQty = $item->quantity + $quantity;
                $item->update([
                    'quantity' => $newQty,
                    'price' => $price,
                    'subtotal' => $price * $newQty,
                ]);

                $action = 'updated';
                $message = 'Jumlah produk di keranjang diperbarui';
            } else {
                CartItem::create([
                    'cart_id' => $cart->id,
                    'purchasable_type' => $purchasableType,
                    'purchasable_id' => $purchasableId,
                    'sku' => $sku,
                    'name' => $name,
                    'weight_gram' => $weightGram,
                    'quantity' => $quantity,
                    'price' => $price,
                    'subtotal' => $price * $quantity,
                ]);

                $action = 'added';
                $message = 'Produk berhasil ditambahkan ke keranjang';
            }

            return [
                'success' => true,
                'message' => $message,
                'action' => $action,
                'cart_id' => $cart->id,
            ];
        });

        // Tambahkan ringkasan terbaru
        $summary = $this->getSummary($customerId);

        return array_merge($result, [
            'cart_summary' => [
                'total_items' => $summary['total_items'],
                'subtotal' => $summary['subtotal'],
                'formatted_subtotal' => $summary['formatted_subtotal'],
            ],
        ]);
    }

    public function updateItemQuantity(int $customerId, int $cartItemId, int $quantity): array
    {
        return DB::transaction(function () use ($customerId, $cartItemId, $quantity) {
            $cart = $this->getOrCreateByCustomerId($customerId);
            $item = CartItem::where('cart_id', $cart->id)->where('id', $cartItemId)->firstOrFail();

            $item->update([
                'quantity' => $quantity,
                'subtotal' => $item->price * $quantity,
            ]);

            $summary = $this->getSummary($customerId);

            return [
                'success' => true,
                'message' => 'Jumlah item diperbarui',
                'item' => [
                    'id' => $item->id,
                    'quantity' => (int) $item->quantity,
                    'subtotal' => (float) $item->subtotal,
                    'formatted_subtotal' => 'Rp '.number_format($item->subtotal, 0, ',', '.'),
                ],
                'cart' => [
                    'total_items' => $summary['total_items'],
                    'subtotal' => $summary['subtotal'],
                    'formatted_subtotal' => $summary['formatted_subtotal'],
                ],
            ];
        });
    }

    public function removeItem(int $customerId, int $cartItemId): array
    {
        return DB::transaction(function () use ($customerId, $cartItemId) {
            $cart = $this->getOrCreateByCustomerId($customerId);
            $item = CartItem::where('cart_id', $cart->id)->where('id', $cartItemId)->firstOrFail();

            $item->delete();

            $summary = $this->getSummary($customerId);

            return [
                'success' => true,
                'message' => 'Item berhasil dihapus dari keranjang',
                'cart' => [
                    'total_items' => $summary['total_items'],
                    'subtotal' => $summary['subtotal'],
                    'formatted_subtotal' => $summary['formatted_subtotal'],
                ],
            ];
        });
    }

    public function clear(int $customerId): void
    {
        $cart = $this->getByCustomerId($customerId);
        if ($cart) {
            CartItem::where('cart_id', $cart->id)->delete();
        }
    }

    public function moveFromWishlist(int $customerId, int $wishlistItemId): array
    {
        return DB::transaction(function () use ($customerId, $wishlistItemId) {
            $wishlist = Wishlist::where('customer_id', $customerId)->firstOrFail();

            /** @var WishlistItem $wishlistItem */
            $wishlistItem = WishlistItem::where('wishlist_id', $wishlist->id)
                ->where('id', $wishlistItemId)
                ->with('purchasable')
                ->firstOrFail();

            $productId = $wishlistItem->purchasable_type === Product::class
                ? $wishlistItem->purchasable_id
                : ($wishlistItem->purchasable->product_id ?? null);

            $variantId = $wishlistItem->purchasable_type === ProductVariant::class
                ? $wishlistItem->purchasable_id
                : null;

            $add = $this->addItem($customerId, (int) $productId, $variantId, 1);

            if (! empty($add['success'])) {
                $wishlistItem->delete();

                return [
                    'success' => true,
                    'message' => 'Item dipindahkan ke keranjang',
                    'cart_summary' => $add['cart_summary'] ?? [],
                ];
            }

            return $add; // bisa mengandung requires_variant
        });
    }

    /** Hitung subtotal dari cart_id. */
    protected function subtotalByCartId(int $cartId): float
    {
        return (float) CartItem::where('cart_id', $cartId)->sum('subtotal');
    }
}
