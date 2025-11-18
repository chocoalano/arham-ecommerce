<?php

namespace App\Repositories\Contracts;

use App\Models\Cart;

interface CartRepositoryInterface
{
    /** Ambil cart customer (opsional eager load). */
    public function getByCustomerId(int $customerId, array $with = []): ?Cart;

    /** Ambil/cart bikin cart customer. */
    public function getOrCreateByCustomerId(int $customerId, string $currency = 'IDR'): Cart;

    /** Jumlah item (sum quantity) untuk customer. */
    public function countItems(int $customerId): int;

    /** Ringkasan cart (items, total_items, subtotal, formatted_subtotal). */
    public function getSummary(int $customerId): array;

    /**
     * Tambah item ke cart.
     * - Jika produk punya varian & variantId null → kembalikan requires_variant = true (tanpa error).
     * - Jika stok tidak cukup → lempar \RuntimeException('Stok tidak mencukupi')
     */
    public function addItem(int $customerId, int $productId, ?int $variantId = null, int $quantity = 1): array;

    /** Update qty item. Kembalikan item + ringkasan cart. */
    public function updateItemQuantity(int $customerId, int $cartItemId, int $quantity): array;

    /** Hapus item cart. Kembalikan ringkasan cart. */
    public function removeItem(int $customerId, int $cartItemId): array;

    /** Kosongkan cart. */
    public function clear(int $customerId): void;

    /**
     * Pindahkan item wishlist ke cart.
     * - Menghapus wishlist item jika sukses.
     * - Mengembalikan success, message, cart_summary.
     */
    public function moveFromWishlist(int $customerId, int $wishlistItemId): array;
}
