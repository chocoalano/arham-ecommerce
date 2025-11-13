<?php

namespace App\Http\Controllers;

use App\Repositories\Contracts\CartRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    public function __construct(private CartRepositoryInterface $carts) {}

    public function index()
    {
        $cart = $items = null;
        $subtotal = 0;

        if (auth('customer')->check()) {
            $cart = $this->carts->getByCustomerId(auth('customer')->id(), ['items.purchasable']);
            if ($cart) {
                $items = $cart->items;
                $subtotal = $this->carts->getSummary(auth('customer')->id())['subtotal'] ?? 0;
            }
        }

        return view('cart', compact('cart', 'items', 'subtotal'));
    }

    public function count(Request $request)
    {
        if (! auth('customer')->check()) {
            return response()->json(['count' => 0, 'authenticated' => false]);
        }

        try {
            $count = $this->carts->countItems(auth('customer')->id());
            $cart = $this->carts->getByCustomerId(auth('customer')->id());

            return response()->json([
                'count' => $count,
                'authenticated' => true,
                'cart_id' => $cart?->id,
            ]);
        } catch (\Throwable $e) {
            Log::error('Cart count error: '.$e->getMessage(), ['trace' => $e->getTraceAsString()]);

            return response()->json([
                'count' => 0,
                'authenticated' => true,
                'error' => 'Failed to fetch cart count',
            ], 500);
        }
    }

    public function summary(Request $request)
    {
        if (! auth('customer')->check()) {
            return response()->json([
                'items' => [], 'total_items' => 0, 'subtotal' => 0, 'authenticated' => false,
            ]);
        }

        try {
            $s = $this->carts->getSummary(auth('customer')->id());

            return response()->json(array_merge($s, ['authenticated' => true]));
        } catch (\Throwable $e) {
            Log::error('Cart summary error: '.$e->getMessage());

            return response()->json([
                'items' => [], 'total_items' => 0, 'subtotal' => 0, 'authenticated' => true,
                'error' => 'Failed to fetch cart summary',
            ], 500);
        }
    }

    public function store(Request $request)
    {
        if (! auth('customer')->check()) {
            return response()->json([
                'success' => false, 'message' => 'Silakan login terlebih dahulu', 'requires_auth' => true,
            ], 401);
        }

        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'variant_id' => 'nullable|integer|exists:product_variants,id',
            'quantity' => 'nullable|integer|min:1|max:999',
        ]);

        try {
            $res = $this->carts->addItem(
                auth('customer')->id(),
                (int) $request->product_id,
                $request->variant_id ? (int) $request->variant_id : null,
                (int) ($request->quantity ?? 1)
            );

            return response()->json($res);
        } catch (\Throwable $e) {
            Log::error('Add to cart error: '.$e->getMessage(), ['payload' => $request->all()]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage() ?: 'Gagal menambahkan ke keranjang',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    public function updateItem(Request $request, $id)
    {
        if (! auth('customer')->check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $request->validate(['quantity' => 'required|integer|min:1|max:999']);

        try {
            $res = $this->carts->updateItemQuantity(auth('customer')->id(), (int) $id, (int) $request->quantity);

            return response()->json($res);
        } catch (\Throwable $e) {
            Log::error('Update cart item error: '.$e->getMessage());

            return response()->json([
                'success' => false, 'message' => 'Gagal memperbarui item',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    public function removeItem($id)
    {
        if (! auth('customer')->check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        try {
            $res = $this->carts->removeItem(auth('customer')->id(), (int) $id);

            return response()->json($res);
        } catch (\Throwable $e) {
            Log::error('Remove cart item error: '.$e->getMessage());

            return response()->json([
                'success' => false, 'message' => 'Gagal menghapus item',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    public function clear()
    {
        if (! auth('customer')->check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }

        try {
            $this->carts->clear(auth('customer')->id());

            return redirect()->route('cart.index')->with('success', 'Keranjang berhasil dikosongkan');
        } catch (\Throwable $e) {
            Log::error('Clear cart error: '.$e->getMessage());

            return redirect()->route('cart.index')->with('error', 'Gagal mengosongkan keranjang');
        }
    }

    public function moveFromWishlist(Request $request)
    {
        if (! auth('customer')->check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $request->validate(['wishlist_item_id' => 'required|integer|exists:wishlist_items,id']);

        try {
            $res = $this->carts->moveFromWishlist(auth('customer')->id(), (int) $request->wishlist_item_id);

            return response()->json($res);
        } catch (\Throwable $e) {
            Log::error('Move from wishlist error: '.$e->getMessage());

            return response()->json([
                'success' => false, 'message' => 'Gagal memindahkan item',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }
}
