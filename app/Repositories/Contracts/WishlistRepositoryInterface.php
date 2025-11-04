<?php

namespace App\Repositories\Contracts;

interface WishlistRepositoryInterface
{
    /** Ambil (atau buat) wishlist milik customer */
    public function getOrCreateByCustomerId(int $customerId);

    /** Hitung total item wishlist customer */
    public function count(int $customerId): int;

    /** Ambil semua item (sudah di-map ke struktur kartu siap render) */
    public function items(int $customerId): array;

    /** Toggle product/variant ke/dari wishlist */
    public function toggle(int $customerId, ?int $productId = null, ?int $variantId = null): array;

    /** Detail 1 item + card */
    public function showItem(int $customerId, int $itemId): array;

    /** Ubah item: pindah ke variant tertentu atau kembalikan ke product */
    public function updateItem(int $customerId, int $itemId, ?int $variantId = null): array;

    /** Hapus 1 item dari wishlist */
    public function destroyItem(int $customerId, int $itemId): array;
}
