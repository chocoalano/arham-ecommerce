<?php

namespace App\Livewire;

use App\Models\Cart;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class CartWishlistIcons extends Component
{
    public int $cartCount = 0;

    public int $wishlistCount = 0;

    public float $cartSubtotal = 0;

    public $cartItems = [];

    public function mount(): void
    {
        $this->refreshCart();
        $this->refreshWishlist();
    }

    #[On('cartUpdated')]
    public function refreshCart(): void
    {
        if (Auth::guard('customer')->check()) {
            $customerId = Auth::guard('customer')->id();

            $cart = Cart::where('customer_id', $customerId)
                ->with(['items.purchasable' => function ($morphTo) {
                    $morphTo->morphWith([
                        \App\Models\Product::class => ['images'],
                        \App\Models\ProductVariant::class => ['product.images'],
                    ]);
                }])
                ->first();

            if ($cart) {
                $this->cartCount = $cart->items->sum('quantity');
                $this->cartSubtotal = $cart->items->sum(function ($item) {
                    $price = $item->purchasable->sale_price ?? $item->purchasable->price;

                    return $price * $item->quantity;
                });

                // Format cart items for display
                $this->cartItems = $cart->items->map(function ($item) {
                    $purchasable = $item->purchasable;
                    $price = $purchasable->sale_price ?? $purchasable->price;

                    // Get image
                    if ($purchasable instanceof \App\Models\Product) {
                        $image = $purchasable->images->where('is_thumbnail', true)->first()
                            ?? $purchasable->images->first();
                        $imageUrl = $image?->path_ratio_99_119 ?? $image?->path;
                        $url = route('catalog.show', $purchasable->slug);
                    } else {
                        $image = $purchasable->product->images->where('is_thumbnail', true)->first()
                            ?? $purchasable->product->images->first();
                        $imageUrl = $image?->path_ratio_99_119 ?? $image?->path;
                        $url = route('catalog.show', $purchasable->product->slug);
                    }

                    return [
                        'id' => $item->id,
                        'name' => $purchasable->name ?? $purchasable->product->name,
                        'quantity' => $item->quantity,
                        'price' => $price,
                        'image' => $imageUrl ? asset('storage/'.$imageUrl) : asset('images/no-image.png'),
                        'url' => $url,
                    ];
                })->toArray();
            } else {
                $this->cartCount = 0;
                $this->cartSubtotal = 0;
                $this->cartItems = [];
            }
        } else {
            $this->cartCount = 0;
            $this->cartSubtotal = 0;
            $this->cartItems = [];
        }
    }

    #[On('wishlistUpdated')]
    public function refreshWishlist(): void
    {
        if (Auth::guard('customer')->check()) {
            $customerId = Auth::guard('customer')->id();

            $wishlist = Wishlist::where('customer_id', $customerId)->first();

            if ($wishlist) {
                $this->wishlistCount = $wishlist->items()->count();
            } else {
                $this->wishlistCount = 0;
            }
        } else {
            $this->wishlistCount = 0;
        }
    }

    public function removeCartItem(int $itemId): void
    {
        try {
            if (Auth::guard('customer')->check()) {
                $customerId = Auth::guard('customer')->id();

                $cart = Cart::where('customer_id', $customerId)->first();

                if ($cart) {
                    // Use delete() instead of findOrFail to avoid exception
                    $deleted = $cart->items()->where('id', $itemId)->delete();

                    if ($deleted) {
                        $this->refreshCart();
                        $this->dispatch('cartUpdated');

                        // Dispatch browser event for notification
                        $this->dispatch('notify', [
                            'type' => 'success',
                            'message' => 'Item removed from cart',
                        ]);
                    }
                }
            }
        } catch (\Exception $e) {
            // Log error but don't break the UI
            \Log::error('Error removing cart item: '.$e->getMessage());

            // Refresh cart anyway to sync state
            $this->refreshCart();
            $this->dispatch('cartUpdated');
        }
    }

    public function render()
    {
        return view('livewire.cart-wishlist-icons');
    }
}
