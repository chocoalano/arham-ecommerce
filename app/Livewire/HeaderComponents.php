<?php

namespace App\Livewire;

use App\Models\Cart;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductCategory;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Livewire\Component;

class HeaderComponents extends Component
{
    public $cartItemsCount = 0;
    public $wishlistItemsCount = 0;
    public $cartSubtotal = 0;
    public $searchQuery = '';

    protected $listeners = [
        'cartUpdated' => 'refreshCart',
        'wishlistUpdated' => 'refreshWishlist',
        'itemAddedToCart' => 'refreshCart',
        'itemRemovedFromCart' => 'refreshCart',
        'itemAddedToWishlist' => 'refreshWishlist',
        'itemRemovedFromWishlist' => 'refreshWishlist',
    ];

    public function mount()
    {
        $this->refreshCart();
        $this->refreshWishlist();
    }

    public function refreshCart()
    {
        if (! Auth::guard('customer')->check()) {
            $this->cartItemsCount = 0;
            $this->cartSubtotal   = 0;
            return;
        }

        // Eager-load purchasable (Product/Variant) + relasi pendukung
        $cart = Cart::query()
            ->where('customer_id', Auth::guard('customer')->id())
            ->with([
                'items.purchasable' => function (MorphTo $m) {
                    $m->morphWith([
                        Product::class        => ['images'],
                        ProductVariant::class => ['product.images'],
                    ]);
                },
            ])
            ->first();

        if (! $cart) {
            $this->cartItemsCount = 0;
            $this->cartSubtotal   = 0;
            return;
        }

        $this->cartItemsCount = (int) $cart->items->sum('quantity');
        // Gunakan subtotal snapshot bila ada, fallback ke price*qty
        $this->cartSubtotal   = (float) $cart->items->sum(function ($i) {
            if (! is_null($i->subtotal)) {
                return (float) $i->subtotal;
            }
            $price = (float) ($i->price ?? 0);
            $qty   = (int)   ($i->quantity ?? 1);
            return $price * $qty;
        });
    }

    public function refreshWishlist()
    {
        if (! Auth::guard('customer')->check()) {
            $this->wishlistItemsCount = 0;
            return;
        }

        $wishlist = Wishlist::where('customer_id', Auth::guard('customer')->id())->first();
        $this->wishlistItemsCount = $wishlist ? $wishlist->items()->count() : 0;
    }

    private function calculateCartSubtotal($cart)
    {
        // Disimpan untuk kompatibilitas ke belakang, tapi refreshCart sudah menghitung dengan lebih aman
        return (float) $cart->items->sum(function ($i) {
            if (! is_null($i->subtotal)) {
                return (float) $i->subtotal;
            }
            $price = (float) ($i->price ?? 0);
            $qty   = (int)   ($i->quantity ?? 1);
            return $price * $qty;
        });
    }

    public function removeCartItem($itemId)
    {
        try {
            if (! Auth::guard('customer')->check()) {
                session()->flash('error', 'Silakan login terlebih dahulu');
                return;
            }

            $cart = Cart::where('customer_id', Auth::guard('customer')->id())->first();

            if ($cart) {
                $cart->items()->where('id', $itemId)->delete();
                $this->refreshCart();
                $this->dispatch('cartUpdated');
                session()->flash('success', 'Produk berhasil dihapus dari keranjang');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan saat menghapus produk');
        }
    }

    public function search()
    {
        if (empty($this->searchQuery)) {
            return redirect()->route('catalog.index');
        }

        return redirect()->route('catalog.index', [
            'search' => $this->searchQuery,
        ]);
    }

    /**
     * Ambil kategori untuk menu (top-level categories)
     */
    public function getCategories()
    {
        return ProductCategory::query()
            ->whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->pluck('name');
    }

    /**
     * Item untuk mini-cart: kembalikan struktur yang kompatibel dengan tampilan lama
     * - product: App\Models\Product|null
     * - variant: App\Models\ProductVariant|null
     * - thumb_url: string|null
     */
    public function getCartItems()
    {
        if (! Auth::guard('customer')->check()) {
            return collect();
        }

        $cart = Cart::query()
            ->where('customer_id', Auth::guard('customer')->id())
            ->with([
                'items.purchasable' => function (MorphTo $m) {
                    $m->morphWith([
                        Product::class        => ['images'],
                        ProductVariant::class => ['product.images'],
                    ]);
                },
            ])
            ->first();

        if (! $cart) {
            return collect();
        }

        return $cart->items->map(function ($item) {
            $product   = null;
            $variant   = null;
            $thumbUrl  = null;

            if ($item->purchasable instanceof Product) {
                $product  = $item->purchasable;
                $thumb    = $product->images->firstWhere('is_thumbnail', true) ?? $product->images->first();
                $thumbUrl = $thumb?->path;
            } elseif ($item->purchasable instanceof ProductVariant) {
                $variant  = $item->purchasable;
                $product  = $variant->product; // sudah eager-load images
                $thumb    = $product?->images?->firstWhere('is_thumbnail', true) ?? $product?->images?->first();
                $thumbUrl = $thumb?->path;
            }

            return (object) [
                'id'        => $item->id,
                'quantity'  => (int) $item->quantity,
                'price'     => (float) ($item->price ?? 0),
                'subtotal'  => (float) ($item->subtotal ?? (($item->price ?? 0) * ($item->quantity ?? 1))),
                // Kompatibilitas dengan Blade lama:
                'product'   => $product,
                'variant'   => $variant,
                'thumb_url' => $thumbUrl,
                // tetap bawa raw item jika diperlukan
                'raw'       => $item,
            ];
        });
    }

    public function render()
    {
        return view('livewire.header-components', [
            'categories'      => $this->getCategories(),
            'cartItems'       => $this->getCartItems(),
            'isAuthenticated' => Auth::guard('customer')->check(),
        ]);
    }
}
