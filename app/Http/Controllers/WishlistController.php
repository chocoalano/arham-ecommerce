<?php

namespace App\Http\Controllers;

use App\Repositories\Contracts\WishlistRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function __construct(private WishlistRepositoryInterface $repo) {}

    /** Halaman wishlist */
    public function index(Request $request)
    {
        $customerId = (int) Auth::guard('customer')->id();

        $items = $this->repo->items($customerId);

        return view('wishlist', [
            'items' => collect($items),
            'count' => count($items),
        ]);
    }

    /** Toggle (AJAX): POST /api/wishlist */
    public function store(Request $request)
    {
        if (! Auth::guard('customer')->check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $data = $request->validate([
            'product_id' => ['nullable', 'integer', 'exists:products,id', 'required_without:variant_id'],
            'variant_id' => ['nullable', 'integer', 'exists:product_variants,id'],
        ]);

        $customerId = (int) Auth::guard('customer')->id();

        $resp = $this->repo->toggle(
            $customerId,
            $data['product_id'] ?? null,
            $data['variant_id'] ?? null
        );

        return response()->json($resp);
    }

    /** Detail item (AJAX): GET /api/wishlist/items/{id} */
    public function show(Request $request, $id)
    {
        if (! Auth::guard('customer')->check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }
        $customerId = (int) Auth::guard('customer')->id();

        $resp = $this->repo->showItem($customerId, (int) $id);

        return response()->json($resp);
    }

    /** Update item (AJAX): PATCH /api/wishlist/items/{id} */
    public function update(Request $request, $id)
    {
        if (! Auth::guard('customer')->check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $data = $request->validate([
            'variant_id' => ['nullable', 'integer', 'exists:product_variants,id'],
        ]);

        $customerId = (int) Auth::guard('customer')->id();

        $resp = $this->repo->updateItem($customerId, (int) $id, $data['variant_id'] ?? null);

        return response()->json($resp);
    }

    /** Hapus item (AJAX): DELETE /api/wishlist/items/{id} */
    public function destroy(Request $request, $id)
    {
        if (! Auth::guard('customer')->check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }
        $customerId = (int) Auth::guard('customer')->id();

        $resp = $this->repo->destroyItem($customerId, (int) $id);

        return response()->json($resp);
    }

    /** Count (AJAX): GET /api/wishlist/count */
    public function count(Request $request)
    {
        if (! Auth::guard('customer')->check()) {
            return response()->json(['count' => 0, 'authenticated' => false]);
        }
        $customerId = (int) Auth::guard('customer')->id();

        return response()->json([
            'count' => $this->repo->count($customerId),
            'authenticated' => true,
        ]);
    }
}
