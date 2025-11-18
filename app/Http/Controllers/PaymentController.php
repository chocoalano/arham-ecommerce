<?php

namespace App\Http\Controllers;

use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function __construct(private MidtransService $midtrans) {}

    /**
     * Handle Midtrans payment notification webhook
     * POST /payment/notification
     */
    public function notification(Request $request)
    {
        try {
            $notificationData = $request->all();

            Log::info('Midtrans notification received', $notificationData);

            $result = $this->midtrans->handleNotification($notificationData);

            return response()->json([
                'success' => true,
                'message' => $result['message'],
            ]);
        } catch (\Exception $e) {
            Log::error('Midtrans notification error: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Handle payment finish redirect from Midtrans
     * GET /payment/finish/{order_number}
     */
    public function finish(string $orderNumber)
    {
        return redirect()->route('checkout.thankyou', $orderNumber)
            ->with('success', 'Pembayaran sedang diproses. Kami akan menginformasikan status pembayaran Anda.');
    }

    /**
     * Handle payment unfinish (user close popup)
     * GET /payment/unfinish/{order_number}
     */
    public function unfinish(string $orderNumber)
    {
        return redirect()->route('checkout.thankyou', $orderNumber)
            ->with('warning', 'Pembayaran belum selesai. Silakan selesaikan pembayaran Anda.');
    }

    /**
     * Handle payment error
     * GET /payment/failed/{order_number}
     */
    public function failed(string $orderNumber)
    {
        return redirect()->route('checkout.thankyou', $orderNumber)
            ->with('error', 'Terjadi kesalahan saat memproses pembayaran. Silakan coba lagi.');
    }

    /**
     * Update payment and order status from frontend
     * POST /payment/update-status/{order_number}
     */
    public function updateStatus(Request $request, string $orderNumber)
    {
        try {
            $validated = $request->validate([
                'transaction_status' => 'required|string',
                'transaction_id' => 'nullable|string',
                'payment_type' => 'nullable|string',
                'order_status' => 'required|string',
            ]);

            // Find the order
            $order = \App\Models\Order::query()->where('order_number', $orderNumber)->firstOrFail();

            // Update order status
            $order->status = $validated['order_status'];
            $order->save();

            // Update payment status
            $payment = \App\Models\Payment::query()->where('order_id', $order->id)->first();
            if ($payment) {
                $payment->transaction_status = $validated['transaction_status'];

                if (! empty($validated['transaction_id'])) {
                    $payment->midtrans_transaction_id = $validated['transaction_id'];
                }

                if (! empty($validated['payment_type'])) {
                    $payment->payment_type = $validated['payment_type'];
                }

                // Set payment time if successful
                if ($validated['transaction_status'] === 'settlement') {
                    $payment->payment_time = now();
                }

                $payment->save();
            }

            // Log the payment status update
            \App\Models\PaymentLog::create([
                'payment_id' => $payment->id ?? null,
                'order_id' => $order->id,
                'status' => $validated['transaction_status'],
                'data' => json_encode([
                    'source' => 'frontend_callback',
                    'transaction_id' => $validated['transaction_id'] ?? null,
                    'payment_type' => $validated['payment_type'] ?? null,
                    'order_status' => $validated['order_status'],
                ]),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Payment and order status updated successfully',
                'order_status' => $order->status,
                'payment_status' => $payment->transaction_status ?? null,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found',
            ], 404);
        } catch (\Exception $e) {
            Log::error('Failed to update payment status: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to update status: '.$e->getMessage(),
            ], 500);
        }
    }
}
