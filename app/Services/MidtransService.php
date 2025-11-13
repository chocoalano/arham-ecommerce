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
            // Extract data from notification array
            $orderId = $notificationData['order_id'] ?? null;
            $statusCode = $notificationData['status_code'] ?? null;
            $grossAmount = $notificationData['gross_amount'] ?? null;
            $signatureKey = $notificationData['signature_key'] ?? null;
            $transactionStatus = $notificationData['transaction_status'] ?? null;
            $fraudStatus = $notificationData['fraud_status'] ?? null;
            $paymentType = $notificationData['payment_type'] ?? null;
            $transactionId = $notificationData['transaction_id'] ?? null;

            if (! $orderId || ! $statusCode || ! $grossAmount || ! $signatureKey) {
                throw new \RuntimeException('Missing required notification fields');
            }

            // Validate signature key for security
            $serverKey = config('services.midtrans.server_key');
            $calculatedSignature = hash('sha512', $orderId.$statusCode.$grossAmount.$serverKey);

            if ($signatureKey !== $calculatedSignature) {
                \Log::error('Invalid Midtrans signature', [
                    'order_id' => $orderId,
                    'received_signature' => $signatureKey,
                    'calculated_signature' => $calculatedSignature,
                ]);

                throw new \RuntimeException('Invalid signature key');
            }

            \Log::info('Valid Midtrans notification received', [
                'order_id' => $orderId,
                'transaction_status' => $transactionStatus,
                'payment_type' => $paymentType,
            ]);

            // Find payment by order_id_ref
            $payment = Payment::where('order_id_ref', $orderId)->firstOrFail();
            $order = $payment->order;

            \Log::info('Processing Midtrans notification', [
                'order_id' => $orderId,
                'transaction_status' => $transactionStatus,
                'fraud_status' => $fraudStatus,
                'payment_id' => $payment->id,
            ]);

            // Prepare payment update data
            $paymentUpdateData = [
                'midtrans_transaction_id' => $transactionId,
                'transaction_status' => $transactionStatus,
                'payment_type' => $paymentType,
                'fraud_status' => $fraudStatus,
                'transaction_time' => $notificationData['transaction_time'] ?? now(),
                'signature_key' => $signatureKey,
                'raw_response' => $notificationData,
            ];

            // Add expiry time if available
            if (isset($notificationData['expiry_time'])) {
                $paymentUpdateData['expiry_time'] = $notificationData['expiry_time'];
            }

            // Add actions if available
            if (isset($notificationData['actions']) && is_array($notificationData['actions'])) {
                $paymentUpdateData['actions'] = $notificationData['actions'];
            }

            // Add VA numbers if available
            if (isset($notificationData['va_numbers']) && is_array($notificationData['va_numbers'])) {
                $paymentUpdateData['va_numbers'] = $notificationData['va_numbers'];
                if (isset($notificationData['va_numbers'][0]['bank'])) {
                    $paymentUpdateData['bank'] = $notificationData['va_numbers'][0]['bank'];
                }
            }

            // Add Permata VA if available
            if (isset($notificationData['permata_va_number'])) {
                $paymentUpdateData['permata_va_number'] = $notificationData['permata_va_number'];
            }

            // Add bill info if available (for convenience store)
            if (isset($notificationData['bill_key'])) {
                $paymentUpdateData['bill_key'] = $notificationData['bill_key'];
                $paymentUpdateData['biller_code'] = $notificationData['biller_code'] ?? null;
            }

            // Add masked card if available
            if (isset($notificationData['masked_card'])) {
                $paymentUpdateData['masked_card'] = $notificationData['masked_card'];
            }

            // Add store info if available
            if (isset($notificationData['store'])) {
                $paymentUpdateData['store'] = $notificationData['store'];
            }

            // Handle different transaction statuses
            $orderStatus = $this->determineOrderStatus($transactionStatus, $fraudStatus);

            if ($orderStatus) {
                $orderUpdateData = ['status' => $orderStatus];

                // Update payment-specific fields based on status
                if ($transactionStatus === 'settlement' || ($transactionStatus === 'capture' && $fraudStatus === 'accept')) {
                    $paymentUpdateData['settlement_time'] = now();
                    $orderUpdateData['paid_at'] = now();

                    \Log::info('Payment successful, updating order status to paid', [
                        'order_id' => $orderId,
                        'order_status' => $orderStatus,
                    ]);
                } elseif ($transactionStatus === 'cancel' || $transactionStatus === 'deny' || $transactionStatus === 'expire') {
                    $orderUpdateData['cancelled_at'] = now();

                    \Log::warning('Payment failed/cancelled', [
                        'order_id' => $orderId,
                        'transaction_status' => $transactionStatus,
                    ]);
                }

                // Update payment first
                $payment->update($paymentUpdateData);

                // Then update order
                $order->update($orderUpdateData);
            } else {
                // Update payment even if order status is not determined
                $payment->update($paymentUpdateData);

                \Log::warning('Order status not determined for transaction status', [
                    'order_id' => $orderId,
                    'transaction_status' => $transactionStatus,
                ]);
            }

            // Log the payment update
            $payment->logs()->create([
                'order_id' => $order->id,
                'type' => 'midtrans_notification',
                'payload' => [
                    'transaction_status' => $transactionStatus,
                    'fraud_status' => $fraudStatus,
                    'payment_type' => $paymentType,
                    'message' => "Midtrans notification: {$transactionStatus}".($fraudStatus ? " (fraud: {$fraudStatus})" : ''),
                    'raw_notification' => $notificationData,
                ],
                'headers' => request()->headers->all(),
                'ip_address' => request()->ip(),
                'occurred_at' => now(),
            ]);

            return [
                'success' => true,
                'message' => 'Notification processed successfully',
                'order_status' => $orderStatus,
                'transaction_status' => $transactionStatus,
            ];
        } catch (\Exception $e) {
            \Log::error('Midtrans notification handler failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'notification' => $notificationData,
            ]);

            throw new \RuntimeException('Failed to handle notification: '.$e->getMessage());
        }
    }

    /**
     * Determine order status based on Midtrans transaction status
     */
    protected function determineOrderStatus(string $transactionStatus, ?string $fraudStatus): ?string
    {
        if ($transactionStatus === 'capture') {
            return $fraudStatus === 'accept' ? 'paid' : 'pending';
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
