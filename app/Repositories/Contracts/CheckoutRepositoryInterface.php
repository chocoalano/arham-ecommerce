<?php

namespace App\Repositories\Contracts;

interface CheckoutRepositoryInterface
{
    /** Data awal halaman checkout (items, subtotal, alamat default, opsi pembayaran). */
    public function prepare(int $customerId): array;

    /** Ambil daftar ongkir berdasarkan alamat (AJAX). */
    public function getShippingQuotes(array $addressData): array;

    /** Proses buat pesanan + payment + shipment + kosongkan cart. */
    public function placeOrder(
        int $customerId,
        array $addressData,
        string $shippingCode,
        string $paymentMethod,
        ?string $orderNote = null
    ): array;
}
