<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Repositories\Contracts\CheckoutRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CheckoutController extends Controller
{
    public function __construct(private CheckoutRepositoryInterface $checkout) {}

    /** GET /checkout */
    public function index()
    {
        $customerId = (int) Auth::guard('customer')->id();
        $data = $this->checkout->prepare($customerId);

        if (empty($data['items'])) {
            return redirect()->route('cart.index')->with('warning', 'Keranjang masih kosong.');
        }

        // Preload quotes jika sudah ada alamat
        $quotes = [];
        if (! empty($data['address'])) {
            $quotes = $this->checkout->getShippingQuotes($data['address']);
        }

        return view('checkout', [
            'items' => $data['items'],
            'subtotal' => $data['subtotal'],
            'address' => $data['address'],
            'paymentMethods' => $data['payment_methods'],
            'quotes' => $quotes,
        ]);
    }

    /** POST /checkout/quotes (AJAX) */
    public function quotes(Request $request)
    {
        $this->validateAddress($request);

        $quotes = $this->checkout->getShippingQuotes($request->only([
            'recipient_name', 'email', 'phone',
            'address_line1', 'address_line2',
            'province_id', 'city_id', 'postal_code',
        ]));

        return response()->json(['success' => true, 'quotes' => $quotes]);
    }

    /** POST /checkout */
    public function store(Request $request)
    {
        $customerId = (int) Auth::guard('customer')->id();

        // Validasi form
        $validated = $this->validateCheckout($request);

        // Proses order
        $result = $this->checkout->placeOrder(
            $customerId,
            $request->only([
                'recipient_name', 'email', 'phone',
                'address_line1', 'address_line2',
                'province_id', 'city_id', 'postal_code',
            ]),
            $validated['shipping_code'],
            $validated['payment_method'],
            $request->input('order_note')
        );

        // Jika request AJAX (untuk Midtrans integration)
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json($result);
        }

        // Non-AJAX fallback (COD/Manual Transfer)
        return redirect()->to($result['redirect_url'])
            ->with('success', $result['message'] ?? 'Pesanan dibuat.');
    }

    /* ============ Helpers ============ */

    protected function validateAddress(Request $request): void
    {
        $request->validate([
            'recipient_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150'],
            'phone' => ['required', 'string', 'max:30'],
            'address_line1' => ['required', 'string', 'max:255'],
            'address_line2' => ['nullable', 'string', 'max:255'],
            'province_id' => ['nullable', 'integer'],
            'city_id' => ['nullable', 'integer'],
            'postal_code' => ['required', 'string', 'max:15'],
        ], [], [
            'recipient_name' => 'Nama Penerima',
            'email' => 'Email',
            'phone' => 'No. HP',
            'address_line1' => 'Alamat',
            'address_line2' => 'Detail Alamat',
            'province_id' => 'Provinsi',
            'city_id' => 'Kota/Kabupaten',
            'postal_code' => 'Kode Pos',
        ]);
    }

    protected function validateCheckout(Request $request): array
    {
        $rules = [
            // alamat
            'recipient_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150'],
            'phone' => ['required', 'string', 'max:30'],
            'address_line1' => ['required', 'string', 'max:255'],
            'address_line2' => ['nullable', 'string', 'max:255'],
            'province_id' => ['nullable', 'integer'],
            'city_id' => ['nullable', 'integer'],
            'postal_code' => ['required', 'string', 'max:15'],

            // checkout
            'shipping_code' => ['required', 'string', 'max:50'],
            'payment_method' => ['required', Rule::in(['midtrans', 'manual_transfer', 'cod'])],
            'order_note' => ['nullable', 'string', 'max:500'],
        ];

        $nice = [
            'recipient_name' => 'Nama Penerima',
            'email' => 'Email',
            'phone' => 'No. HP',
            'address_line1' => 'Alamat',
            'address_line2' => 'Detail Alamat',
            'province_id' => 'Provinsi',
            'city_id' => 'Kota/Kabupaten',
            'postal_code' => 'Kode Pos',
            'shipping_code' => 'Metode Pengiriman',
            'payment_method' => 'Metode Pembayaran',
            'order_note' => 'Catatan Pesanan',
        ];

        return $request->validate($rules, [], $nice);
    }

    // app/Http/Controllers/OrderController.php
    public function thankYou(string $orderNumber)
    {
        $order = Order::with(['items.purchasable'])
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        return view('thankyou', compact('order'));
    }
}
