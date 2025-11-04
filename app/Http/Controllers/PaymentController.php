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
            Log::error('Midtrans notification error: ' . $e->getMessage());

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
}
