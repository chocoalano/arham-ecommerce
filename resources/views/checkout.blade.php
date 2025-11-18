@extends('layouts.app')

@section('title', 'Checkout')

@push('styles')
<style>
    .is-loading { opacity:.6; pointer-events:none; }
    .form-hint { font-size:.875rem; color:#6c757d; }
    .summary-card { position: sticky; top: 90px; }
</style>
@endpush

@push('scripts')
<script type="text/javascript"
    src="{{ config('services.midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}"
    data-client-key="{{ config('services.midtrans.client_key') }}">
</script>
@endpush

@section('content')
@livewire('breadscrumb')

<div class="page-section mb-80">
    <div class="container">
        <div class="row">
            <div class="col-12">

                <!-- Checkout Form -->
                <form id="checkout-form" action="{{ route('checkout.store') }}" method="POST" class="checkout-form" novalidate>
                    @csrf
                    <div class="row row-40">

                        <div class="col-lg-7 mb-20">

                            <!-- Billing Address & Shipping Calculator (Livewire) -->
                            <div id="billing-form" class="mb-40">
                                <h4 class="checkout-title">Billing Address & Shipping</h4>

                                @livewire('shipping-calculator', ['address' => $address])
                            </div>

                            <!-- Order Notes -->
                            <div class="mb-40">
                                <h4 class="checkout-title">Order Notes</h4>
                                <div class="row">
                                    <div class="col-12 mb-20">
                                        <label>Order Notes (Optional)</label>
                                        <textarea name="order_note" rows="3" placeholder="Notes about your order, e.g. special notes for delivery">{{ old('order_note') }}</textarea>
                                        @error('order_note')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Hidden inputs for form submission -->
                            <input type="hidden" name="recipient_name" id="hidden_recipient_name">
                            <input type="hidden" name="email" id="hidden_email">
                            <input type="hidden" name="phone" id="hidden_phone">
                            <input type="hidden" name="address_line1" id="hidden_address_line1">
                            <input type="hidden" name="address_line2" id="hidden_address_line2">
                            <input type="hidden" name="province_id" id="hidden_province_id">
                            <input type="hidden" name="city_id" id="hidden_city_id">
                            <input type="hidden" name="postal_code" id="hidden_postal_code">
                            <input type="hidden" name="shipping_code" id="hidden_shipping_code">
                        </div>

                        <div class="col-lg-5">
                            <div class="row">

                                <!-- Cart Total -->
                                <div class="col-12 mb-60">
                                    <h4 class="checkout-title">Cart Total</h4>

                                    <div class="checkout-cart-total">
                                        <h4>Product <span>Total</span></h4>

                                        <ul>
                                            @foreach($items as $it)
                                            <li>{{ $it['name'] }} X {{ $it['quantity'] }} <span>{{ $it['formatted_subtotal'] }}</span></li>
                                            @endforeach
                                        </ul>

                                        <p>Sub Total <span id="sum-subtotal">Rp {{ number_format($subtotal, 0, ',', '.') }}</span></p>
                                        <p>Shipping Fee <span id="sum-shipping">Rp 0</span></p>

                                        <h4>Grand Total <span id="sum-total">Rp {{ number_format($subtotal, 0, ',', '.') }}</span></h4>
                                    </div>
                                </div>

                                <!-- Payment Method -->
                                <div class="col-12">
                                    <h4 class="checkout-title">Payment Method</h4>

                                    <div class="checkout-payment-method">

                                        <div class="single-method">
                                            <input type="radio" id="payment_midtrans" name="payment_method" value="midtrans"
                                                   @checked(old('payment_method','midtrans')==='midtrans')>
                                            <label for="payment_midtrans">Midtrans Payment Gateway</label>
                                            <p data-method="midtrans">
                                                Pay securely with Credit Card, Bank Transfer, E-Wallet (GoPay, OVO, DANA), and more through Midtrans.
                                            </p>
                                        </div>

                                        @foreach($paymentMethods as $pm)
                                        <div class="single-method">
                                            <input type="radio" id="payment_{{ $pm['code'] }}" name="payment_method" value="{{ $pm['code'] }}"
                                                   @checked(old('payment_method')===$pm['code'])>
                                            <label for="payment_{{ $pm['code'] }}">{{ $pm['label'] }}</label>
                                            <p data-method="{{ $pm['code'] }}">{{ $pm['description'] ?? 'Payment will be processed manually.' }}</p>
                                        </div>
                                        @endforeach

                                        @error('payment_method')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror

                                        <div class="single-method">
                                            <input type="checkbox" id="accept_terms" required>
                                            <label for="accept_terms">I've read and accept the terms & conditions*</label>
                                        </div>
                                    </div>

                                    <button type="submit" class="place-order" id="btn-submit">Place Order</button>
                                </div>

                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
(function(){
    'use strict';

    const authUrl = @json(route('login-register.index'));
    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    const $ = (s, c=document)=>c.querySelector(s);

    const fSub  = $('#sum-subtotal');
    const fShip = $('#sum-shipping');
    const fTot  = $('#sum-total');
    const btnSubmit = $('#btn-submit');
    const form = $('#checkout-form');

    const parseIDR = (str)=> {
        if (typeof str === 'number') return str;
        return parseFloat(String(str).replace(/[^\d]/g,'')) || 0;
    };
    const fmtIDR = (n)=> 'Rp ' + new Intl.NumberFormat('id-ID').format(n);

    const baseSubtotal = parseIDR(@json($subtotal));
    let currentShipping = 0;

    // Recalculate grand total
    const recalc = ()=>{
        const shipping = currentShipping || 0;
        fShip.textContent = fmtIDR(shipping);
        fTot.textContent  = fmtIDR(baseSubtotal + shipping);
    };

    // Toast notification
    function toast(type, msg){
        if (typeof window.showNotification === 'function') window.showNotification(type, msg);
        else if (window.toastr) window.toastr[type||'info'](msg||'');
        else alert(`[${type.toUpperCase()}] ${msg}`);
    }

    // Toggle loading state
    function toggleLoading(btn, loading){
        if (loading){
            btn?.classList.add('is-loading');
            btn?.setAttribute('disabled','');
            return ()=>{
                btn?.classList.remove('is-loading');
                btn?.removeAttribute('disabled');
            };
        }
    }

    // Listen to Livewire events
    document.addEventListener('livewire:init', () => {
        // When shipping is calculated
        Livewire.on('shipping-calculated', (event) => {
            console.log('Shipping calculated:', event);
            const data = event[0] || event;

            if (data.selected) {
                const selectedQuote = data.quotes.find(q => q.code === data.selected);
                if (selectedQuote) {
                    currentShipping = selectedQuote.cost;
                    $('#hidden_shipping_code').value = data.selected;
                    recalc();
                }
            }
        });

        // When shipping option is selected
        Livewire.on('shipping-selected', (event) => {
            console.log('Shipping selected:', event);
            const data = event[0] || event;

            currentShipping = data.cost;
            $('#hidden_shipping_code').value = data.code;
            recalc();
        });
    });

    // Before form submit, sync Livewire data to hidden fields
    form?.addEventListener('submit', function(e){
        e.preventDefault();

        // Sync all Livewire component values to hidden fields
        const recipientName = $('#recipientName')?.value;
        const email = $('#email')?.value;
        const phone = $('#phone')?.value;
        const addressLine1 = $('#addressLine1')?.value;
        const addressLine2 = $('#addressLine2')?.value;
        const provinceId = $('#provinceId')?.value;
        const cityId = $('#cityId')?.value;
        const postalCode = $('#postalCode')?.value;

        $('#hidden_recipient_name').value = recipientName || '';
        $('#hidden_email').value = email || '';
        $('#hidden_phone').value = phone || '';
        $('#hidden_address_line1').value = addressLine1 || '';
        $('#hidden_address_line2').value = addressLine2 || '';
        $('#hidden_province_id').value = provinceId || '';
        $('#hidden_city_id').value = cityId || '';
        $('#hidden_postal_code').value = postalCode || '';

        // Validation
        if (!recipientName || !email || !phone || !addressLine1 || !provinceId || !cityId || !postalCode) {
            toast('error', 'Please fill all required fields');
            return false;
        }

        if (!$('#hidden_shipping_code').value) {
            toast('error', 'Please calculate and select shipping method');
            return false;
        }

        const paymentMethod = form.payment_method?.value;
        if (!paymentMethod) {
            toast('error', 'Please select a payment method');
            return false;
        }

        const acceptTerms = $('#accept_terms');
        if (!acceptTerms?.checked) {
            toast('error', 'Please accept the terms & conditions');
            acceptTerms?.focus();
            return false;
        }

        // AJAX submission for Midtrans
        const enable = toggleLoading(btnSubmit, true);

        const formData = new FormData(form);

        // Use fetch for AJAX submission
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(resp => {
            if (!resp.success) {
                toast('error', resp.message || 'Failed to create order');
                enable();
                return;
            }

            // If payment method is Midtrans and snap_token exists
            if (paymentMethod === 'midtrans' && resp.snap_token) {
                if (typeof window.snap !== 'undefined') {
                    window.snap.pay(resp.snap_token, {
                        onSuccess: function(result){
                            console.log('Payment success:', result);

                            // Update order and payment status via AJAX
                            fetch(`/payment/update-status/${resp.order_number}`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': csrf,
                                    'Accept': 'application/json',
                                },
                                body: JSON.stringify({
                                    transaction_status: 'settlement',
                                    transaction_id: result.transaction_id,
                                    payment_type: result.payment_type,
                                    order_status: 'paid'
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                console.log('Status update response:', data);
                                toast('success', 'Payment successful!');
                                setTimeout(() => {
                                    window.location.href = resp.redirect_url || '/orders';
                                }, 1000);
                            })
                            .catch(err => {
                                console.error('Failed to update status:', err);
                                // Still redirect even if update fails
                                window.location.href = resp.redirect_url || '/orders';
                            });
                        },
                        onPending: function(result){
                            console.log('Payment pending:', result);

                            // Update order and payment status via AJAX
                            fetch(`/payment/update-status/${resp.order_number}`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': csrf,
                                    'Accept': 'application/json',
                                },
                                body: JSON.stringify({
                                    transaction_status: 'pending',
                                    transaction_id: result.transaction_id,
                                    payment_type: result.payment_type,
                                    order_status: 'pending_payment'
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                console.log('Status update response:', data);
                                toast('warning', 'Payment pending. Please complete your payment.');
                                setTimeout(() => {
                                    window.location.href = resp.redirect_url || '/orders';
                                }, 1000);
                            })
                            .catch(err => {
                                console.error('Failed to update status:', err);
                                // Still redirect even if update fails
                                window.location.href = resp.redirect_url || '/orders';
                            });
                        },
                        onError: function(result){
                            toast('error', 'Payment failed');
                            enable();
                        },
                        onClose: function(){
                            toast('warning', 'Payment popup closed');
                            setTimeout(() => {
                                window.location.href = resp.redirect_url || '/orders';
                            }, 2000);
                        }
                    });
                } else {
                    toast('error', 'Midtrans Snap not available');
                    enable();
                }
            } else {
                // Redirect for other payment methods
                window.location.href = resp.redirect_url || '/orders';
            }
        })
        .catch(err => {
            console.error('Submit error:', err);
            if (err.status === 401) {
                window.location.href = authUrl;
            } else {
                toast('error', 'Failed to submit order');
                enable();
            }
        });

        return false;
    });

    // Initialize
    recalc();
})();
</script>
@endpush
@endsection
