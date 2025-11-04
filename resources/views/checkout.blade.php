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

                            <!-- Billing Address -->
                            <div id="billing-form" class="mb-40">
                                <h4 class="checkout-title">Billing Address</h4>

                                <div class="row">
                                    <div class="col-md-6 col-12 mb-20">
                                        <label>Recipient Name*</label>
                                        <input type="text" name="recipient_name" placeholder="Recipient Name"
                                               value="{{ old('recipient_name', $address['recipient_name'] ?? '') }}" required>
                                        @error('recipient_name')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 col-12 mb-20">
                                        <label>Phone Number*</label>
                                        <input type="text" name="phone" placeholder="Phone Number"
                                               value="{{ old('phone', $address['phone'] ?? '') }}" required>
                                        @error('phone')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-12 mb-20">
                                        <label>Email Address*</label>
                                        <input type="email" name="email" placeholder="Email Address"
                                               value="{{ old('email', $address['email'] ?? '') }}" required>
                                        @error('email')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-12 mb-20">
                                        <label>Address*</label>
                                        <input type="text" name="address_line1" placeholder="Address line 1"
                                               value="{{ old('address_line1', $address['address_line1'] ?? '') }}" required>
                                        <input type="text" name="address_line2" placeholder="Address line 2 (Optional)"
                                               value="{{ old('address_line2', $address['address_line2'] ?? '') }}">
                                        @error('address_line1')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 col-12 mb-20">
                                        <label>Province ID</label>
                                        <input type="number" name="province_id" placeholder="Province ID (Optional)"
                                               value="{{ old('province_id', $address['province_id'] ?? '') }}">
                                        @error('province_id')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 col-12 mb-20">
                                        <label>City ID</label>
                                        <input type="number" name="city_id" placeholder="City ID (Optional)"
                                               value="{{ old('city_id', $address['city_id'] ?? '') }}">
                                        @error('city_id')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 col-12 mb-20">
                                        <label>Postal Code*</label>
                                        <input type="text" name="postal_code" placeholder="Postal Code"
                                               value="{{ old('postal_code', $address['postal_code'] ?? '') }}" required>
                                        @error('postal_code')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-12 mb-20">
                                        <button type="button" class="btn btn-secondary" id="btn-refresh-quotes">
                                            <i class="fa fa-refresh"></i> Calculate Shipping Cost
                                        </button>
                                    </div>

                                    <div class="col-12 mb-20">
                                        <label>Order Notes (Optional)</label>
                                        <textarea name="order_note" rows="3" placeholder="Notes about your order, e.g. special notes for delivery">{{ old('order_note') }}</textarea>
                                        @error('order_note')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Shipping Method -->
                            <div id="shipping-form" class="mb-40">
                                <h4 class="checkout-title">Shipping Method</h4>

                                <div id="shipping-quotes">
                                    @if(!empty($quotes))
                                        <div class="row">
                                            @foreach($quotes as $i => $q)
                                            <div class="col-12 mb-20">
                                                <div class="single-method">
                                                    <input type="radio" id="ship-{{ $q['code'] }}" name="shipping_code"
                                                           value="{{ $q['code'] }}" class="js-ship" data-cost="{{ (float) $q['cost'] }}"
                                                           @checked(old('shipping_code') ? old('shipping_code')===$q['code'] : $i===0)>
                                                    <label for="ship-{{ $q['code'] }}">{{ $q['label'] }} - <strong>Rp {{ number_format($q['cost'], 0, ',', '.') }}</strong></label>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-muted">Click "Calculate Shipping Cost" button above to get shipping options.</p>
                                    @endif
                                    @error('shipping_code')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
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

    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    const authUrl = @json(route('login-register.index'));
    const quotesUrl = @json(route('checkout.quotes'));

    const $ = (s, c=document)=>c.querySelector(s);
    const $$= (s, c=document)=>Array.from(c.querySelectorAll(s));

    const fSub  = $('#sum-subtotal');
    const fShip = $('#sum-shipping');
    const fTot  = $('#sum-total');
    const btnSubmit = $('#btn-submit');
    const btnRefresh = $('#btn-refresh-quotes');
    const form = $('#checkout-form');

    const parseIDR = (str)=> {
        if (typeof str === 'number') return str;
        return parseFloat(String(str).replace(/[^\d]/g,'')) || 0;
    };
    const fmtIDR = (n)=> 'Rp ' + (Math.round(+n)).toString().replace(/\B(?=(\d{3})+(?!\d))/g,'.');

    const recalc = () => {
        const subtotal = parseIDR(fSub.textContent);
        const shipCost = $('input.js-ship:checked')?.dataset.cost || 0;
        fShip.textContent = fmtIDR(shipCost);
        fTot.textContent = fmtIDR(subtotal + (+shipCost));
    };

    document.addEventListener('change', function(e){
        if (e.target.matches('input.js-ship')) recalc();
    });

    // Ambil Quotes via AJAX
    btnRefresh?.addEventListener('click', function(){
        const enable = toggleLoading(this, true);

        const payload = {
            recipient_name: form.recipient_name.value,
            email:          form.email.value,
            phone:          form.phone.value,
            address_line1:  form.address_line1.value,
            address_line2:  form.address_line2.value,
            province_id:    form.province_id.value || null,
            city_id:        form.city_id.value || null,
            postal_code:    form.postal_code.value
        };

        ajax(quotesUrl, { method: 'POST', json: payload })
            .then(resp=>{
                const wrap = $('#shipping-quotes');
                wrap.innerHTML = '';
                if (!resp.quotes || !resp.quotes.length) {
                    wrap.innerHTML = '<p class="text-muted">Shipping not available for this address.</p>';
                    recalc(); return;
                }
                wrap.innerHTML = '<div class="row"></div>';
                const row = wrap.querySelector('.row');
                resp.quotes.forEach((q, idx) => {
                    const id = 'ship-'+q.code;
                    const checked = idx===0 ? 'checked' : '';
                    row.insertAdjacentHTML('beforeend', `
                        <div class="col-12 mb-20">
                            <div class="single-method">
                                <input type="radio" id="${id}" name="shipping_code" value="${q.code}"
                                       class="js-ship" data-cost="${q.cost}" ${checked}>
                                <label for="${id}">${q.label} - <strong>${fmtIDR(q.cost)}</strong></label>
                            </div>
                        </div>
                    `);
                });
                recalc();
            })
            .catch(err=>{
                if (err.status===401) return location.href = authUrl;
                toast('error', err.message || 'Gagal memuat ongkir');
            })
            .finally(()=>enable());
    });

    // Submit dengan AJAX untuk Midtrans integration
    form?.addEventListener('submit', function(e){
        e.preventDefault();

        const enable = toggleLoading(btnSubmit, true);
        const paymentMethod = form.payment_method.value;

        const formData = new FormData(form);

        ajax(form.action, {
            method: 'POST',
            data: formData
        })
        .then(resp=>{
            if (!resp.success) {
                toast('error', resp.message || 'Gagal membuat pesanan');
                enable();
                return;
            }

            // Jika payment method Midtrans dan ada snap_token
            if (paymentMethod === 'midtrans' && resp.snap_token) {
                // Tampilkan Midtrans Snap popup
                if (typeof window.snap !== 'undefined') {
                    window.snap.pay(resp.snap_token, {
                        onSuccess: function(result){
                            console.log('Payment success:', result);
                            location.href = resp.redirect_url;
                        },
                        onPending: function(result){
                            console.log('Payment pending:', result);
                            location.href = resp.redirect_url;
                        },
                        onError: function(result){
                            console.log('Payment error:', result);
                            toast('error', 'Pembayaran gagal. Silakan coba lagi.');
                            enable();
                        },
                        onClose: function(){
                            console.log('Payment popup closed');
                            toast('warning', 'Anda menutup popup pembayaran. Silakan selesaikan pembayaran Anda.');
                            // Redirect ke thank you page anyway
                            setTimeout(() => {
                                location.href = resp.redirect_url;
                            }, 2000);
                        }
                    });
                } else {
                    toast('error', 'Midtrans Snap belum tersedia');
                    enable();
                }
            } else {
                // COD atau Manual Transfer - langsung redirect
                location.href = resp.redirect_url;
            }
        })
        .catch(err=>{
            if (err.status===401) return location.href = authUrl;
            if (err.status===422 && err.errors) {
                // Validation errors
                const firstError = Object.values(err.errors)[0];
                toast('error', Array.isArray(firstError) ? firstError[0] : firstError);
            } else {
                toast('error', err.message || 'Gagal membuat pesanan');
            }
            enable();
        });
    });

    /* -------------- helpers -------------- */

    function toggleLoading(el, state) {
        if (!el) return ()=>{};
        if (state) { el.classList.add('is-loading'); el.setAttribute('disabled','disabled'); }
        return () => { el.classList.remove('is-loading'); el.removeAttribute('disabled'); };
    }

    function ajax(url, {method='GET', json=null, data=null, headers={}}={}) {
        // jQuery tersedia?
        if (window.jQuery && !(data instanceof FormData)) {
            return new Promise((resolve, reject)=>{
                const opts = {
                    url, method,
                    data: json ? JSON.stringify(json) : (data || {}),
                    headers: Object.assign({'X-CSRF-TOKEN': csrf, 'Accept':'application/json'}, json ? {'Content-Type':'application/json'}:{} , headers),
                    success: (resp)=>resolve(resp),
                    error:   (xhr)=>{
                        if (xhr?.status===401) { reject({status:401, message:'Unauthorized'}); return; }
                        const errors = xhr?.responseJSON?.errors || null;
                        reject({
                            status:xhr?.status||0,
                            message:(xhr?.responseJSON && xhr.responseJSON.message)||xhr.statusText||'Request gagal',
                            errors: errors
                        });
                    }
                };
                jQuery.ajax(opts);
            });
        }
        // fetch fallback
        const opts = {
            method,
            headers: Object.assign({'X-CSRF-TOKEN': csrf, 'Accept':'application/json'}, headers)
        };
        if (json) {
            opts.headers['Content-Type']='application/json';
            opts.body = JSON.stringify(json);
        } else if (data instanceof FormData) {
            opts.body = data;
        } else if (data) {
            opts.body = data;
        }
        return fetch(url, opts).then(async res=>{
            let js = {};
            try { js = await res.json(); } catch {}
            if (!res.ok) throw {
                status:res.status,
                message: js?.message || 'Request gagal',
                errors: js?.errors || null
            };
            return js;
        });
    }

    function toast(type, msg) {
        if (typeof window.showNotification === 'function') window.showNotification(type, msg);
        else if (window.toastr) window.toastr[type||'info'](msg||'');
        else alert(msg);
    }

    // Inisialisasi total (jika ada radio terpilih)
    recalc();
})();
</script>
@endpush
@endsection
