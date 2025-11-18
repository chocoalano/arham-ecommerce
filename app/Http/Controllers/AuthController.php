<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Display customer profile dashboard
     */
    public function index()
    {
        $customer = Auth::guard('customer')->user();

        // Get customer statistics
        $stats = [
            'total_orders' => Order::where('customer_id', $customer->id)->count(),
            'pending_orders' => Order::where('customer_id', $customer->id)->where('status', 'pending')->count(),
            'total_spent' => Order::where('customer_id', $customer->id)
                ->whereIn('status', ['processing', 'completed'])
                ->sum('grand_total'),
        ];

        // Get recent orders
        $recentOrders = Order::where('customer_id', $customer->id)
            ->with('items')
            ->latest()
            ->take(5)
            ->get();

        // Get all orders for orders tab
        $orders = Order::where('customer_id', $customer->id)
            ->with('items')
            ->latest()
            ->get();

        // Get addresses
        $addresses = Address::where('customer_id', $customer->id)
            ->orderByDesc('created_at')
            ->get();

        return view('auth.index', compact('customer', 'stats', 'recentOrders', 'orders', 'addresses'));
    }

    /**
     * Update customer profile
     */
    public function update(Request $request, int $id)
    {
        $customer = Auth::guard('customer')->user();

        if ($customer->id !== $id) {
            return back()->with('error', 'Unauthorized action.');
        }

        // Check if password update
        if ($request->filled('current_password')) {
            $request->validate([
                'current_password' => 'required',
                'password' => 'required|min:8|confirmed',
            ], [], [
                'current_password' => 'Password Lama',
                'password' => 'Password Baru',
            ]);

            // Verify current password
            if (! Hash::check($request->current_password, $customer->password)) {
                return back()->with('error', 'Password lama tidak sesuai.');
            }

            $customer->password = Hash::make($request->password);
            $customer->save();

            return back()->with('success', 'Password berhasil diubah.');
        }

        // Update profile
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:150|unique:customers,email,'.$customer->id,
            'phone' => 'nullable|string|max:20',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:male,female',
        ], [], [
            'name' => 'Nama',
            'email' => 'Email',
            'phone' => 'No. Telepon',
            'birth_date' => 'Tanggal Lahir',
            'gender' => 'Gender',
        ]);

        $customer->update($validated);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Logout customer
     */
    public function logout(Request $request)
    {
        Auth::guard('customer')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Anda berhasil logout.');
    }

    /**
     * Get order detail
     */
    public function getOrder(int $id)
    {
        $customer = Auth::guard('customer')->user();

        $order = Order::with(['items.purchasable', 'payments'])
            ->where('id', $id)
            ->where('customer_id', $customer->id)
            ->first();

        if (! $order) {
            return response()->json(['success' => false, 'message' => 'Order not found'], 404);
        }

        // Get the latest payment for this order
        $payment = $order->payments->first();

        // Add payment to order object for consistency with frontend
        $order->payment = $payment;

        return response()->json(['success' => true, 'order' => $order]);
    }

    /**
     * Get address detail
     */
    public function getAddress(int $id)
    {
        $customer = Auth::guard('customer')->user();

        $address = Address::where('id', $id)
            ->where('customer_id', $customer->id)
            ->first();

        if (! $address) {
            return response()->json(['success' => false, 'message' => 'Address not found'], 404);
        }

        return response()->json(['success' => true, 'address' => $address]);
    }

    /**
     * Store new address
     */
    public function storeAddress(Request $request)
    {
        $customer = Auth::guard('customer')->user();

        $validated = $request->validate([
            'recipient_name' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'address_line1' => 'required|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'province' => 'required|string|max:100',
            'postal_code' => 'required|string|max:10',
            'is_default' => 'nullable|boolean',
        ]);

        // If this is set as default, remove default from other addresses
        if ($request->is_default) {
            Address::where('customer_id', $customer->id)->update(['is_default' => false]);
            $validated['is_default'] = true;
        }

        $validated['customer_id'] = $customer->id;

        $address = Address::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Address added successfully!',
            'address' => $address,
        ]);
    }

    /**
     * Update address
     */
    public function updateAddress(Request $request, int $id)
    {
        $customer = Auth::guard('customer')->user();

        $address = Address::where('id', $id)
            ->where('customer_id', $customer->id)
            ->first();

        if (! $address) {
            return response()->json(['success' => false, 'message' => 'Address not found'], 404);
        }

        $validated = $request->validate([
            'recipient_name' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'address_line1' => 'required|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'province' => 'required|string|max:100',
            'postal_code' => 'required|string|max:10',
            'is_default' => 'nullable|boolean',
        ]);

        // If this is set as default, remove default from other addresses
        if ($request->is_default) {
            Address::where('customer_id', $customer->id)
                ->where('id', '!=', $id)
                ->update(['is_default' => false]);
            $validated['is_default'] = true;
        }

        $address->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Address updated successfully!',
            'address' => $address,
        ]);
    }

    /**
     * Delete address
     */
    public function deleteAddress(int $id)
    {
        $customer = Auth::guard('customer')->user();

        $address = Address::where('id', $id)
            ->where('customer_id', $customer->id)
            ->first();

        if (! $address) {
            return response()->json(['success' => false, 'message' => 'Address not found'], 404);
        }

        $address->delete();

        return response()->json([
            'success' => true,
            'message' => 'Address deleted successfully!',
        ]);
    }
}
