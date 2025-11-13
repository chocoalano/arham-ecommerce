<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production', false);
        Config::$isSanitized = config('services.midtrans.is_sanitized', true);
        Config::$is3ds = config('services.midtrans.is_3ds', true);
    }

    /**
     * Create Snap payment token for an order
     */
    public function createSnapToken(Order $order, Payment $payment): string
    {
        $params = [
            'transaction_details' => [
                'order_id' => $order->order_number,
                'gross_amount' => (int) $order->grand_total,
            ],
            'customer_details' => [
                'first_name' => $order->customer_name ?? 'Customer',
                'email' => $order->customer_email,
                'phone' => $order->customer_phone,
            ],
            'item_details' => $this->buildItemDetails($order),
            'callbacks' => [
                'finish' => route('payment.finish', $order->order_number),
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);

            // Update payment dengan token
            $payment->update([
                'midtrans_transaction_id' => $order->order_number,
                'order_id_ref' => $order->order_number,
                'provider' => 'midtrans',
            ]);

            return $snapToken;
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to create Midtrans snap token: '.$e->getMessage());
        }
    }

    /**
     * Build item details from order items
     */
    protected function buildItemDetails(Order $order): array
    {
        $items = [];

        foreach ($order->items as $item) {
            $items[] = [
                'id' => $item->sku ?? $item->id,
                'price' => (int) $item->price,
                'quantity' => $item->quantity,
                'name' => substr($item->name, 0, 50), // Midtrans limit 50 chars
            ];
        }

        // Add shipping cost as item
        if ($order->shipping_cost > 0) {
            $items[] = [
                'id' => 'SHIPPING',
                'price' => (int) $order->shipping_cost,
                'quantity' => 1,
                'name' => 'Biaya Pengiriman',
            ];
        }

        // Add discount if exists
        if ($order->discount_total > 0) {
            $items[] = [
                'id' => 'DISCOUNT',
                'price' => -(int) $order->discount_total,
                'quantity' => 1,
                'name' => 'Diskon',
            ];
        }

        // Add tax if exists
        if ($order->tax_total > 0) {
            $items[] = [
                'id' => 'TAX',
                'price' => (int) $order->tax_total,
                'quantity' => 1,
                'name' => 'Pajak',
            ];
        }

        return $items;
    }

    /**
     * Handle Midtrans notification callback
     */
    public function handleNotification(array $notificationData): array
    {
        try {
            $notification = new \Midtrans\Notification;

            $transactionStatus = $notification->transaction_status;
            $fraudStatus = $notification->fraud_status ?? null;
            $orderId = $notification->order_id;
            $paymentType = $notification->payment_type;

            // Find payment by order_id_ref
            $payment = Payment::where('order_id_ref', $orderId)->firstOrFail();
            $order = $payment->order;

            // Update payment details
            $payment->update([
                'transaction_status' => $transactionStatus,
                'payment_type' => $paymentType,
                'fraud_status' => $fraudStatus,
                'transaction_time' => $notification->transaction_time ?? now(),
                'raw_response' => $notificationData,
            ]);

            // Handle different transaction statuses
            $status = $this->determineOrderStatus($transactionStatus, $fraudStatus);

            if ($status) {
                $order->update(['status' => $status]);

                // Update payment-specific fields
                if ($transactionStatus === 'settlement' || $transactionStatus === 'capture') {
                    $payment->update([
                        'settlement_time' => now(),
                    ]);
                    $order->update([
                        'paid_at' => now(),
                    ]);
                } elseif ($transactionStatus === 'cancel' || $transactionStatus === 'deny' || $transactionStatus === 'expire') {
                    $order->update([
                        'cancelled_at' => now(),
                    ]);
                }
            }

            // Log the payment update
            $payment->logs()->create([
                'status' => $transactionStatus,
                'message' => "Midtrans notification received: {$transactionStatus}",
                'payload' => $notificationData,
            ]);

            return [
                'success' => true,
                'message' => 'Notification processed successfully',
                'order_status' => $status,
            ];
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to handle notification: '.$e->getMessage());
        }
    }

    /**
     * Determine order status based on Midtrans transaction status
     */
    protected function determineOrderStatus(string $transactionStatus, ?string $fraudStatus): ?string
    {
        if ($transactionStatus === 'capture') {
            return $fraudStatus === 'accept' ? 'processing' : 'pending';
        }

        if ($transactionStatus === 'settlement') {
            return 'processing';
        }

        if ($transactionStatus === 'pending') {
            return 'pending';
        }

        if (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
            return 'cancelled';
        }

        return null;
    }

    /**
     * Check transaction status from Midtrans API
     */
    public function checkTransactionStatus(string $orderId): array
    {
        try {
            $status = Transaction::status($orderId);

            return [
                'order_id' => $status->order_id,
                'transaction_status' => $status->transaction_status,
                'fraud_status' => $status->fraud_status ?? null,
                'payment_type' => $status->payment_type,
                'transaction_time' => $status->transaction_time,
                'gross_amount' => $status->gross_amount,
            ];
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to check transaction status: '.$e->getMessage());
        }
    }
}
