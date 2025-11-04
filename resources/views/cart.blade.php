@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@section('content')
@push('styles')
<style>
    .qty-control { display:flex; align-items:center; gap:.25rem; }
    .qty-control .btn { width:2.25rem; height:2.25rem; padding:0; line-height:2.25rem; text-align:center; }
    .is-loading { opacity:.6; pointer-events:none; }
    .cart-summary-card { position: sticky; top: 90px; }
    .table td, .table th { vertical-align: middle; }
</style>
@endpush

@livewire('breadscrumb')

<div class="page-section mb-80">
    <div class="container">
        <div class="row g-4">
            <!-- Items -->
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">Keranjang</h5>
                        <div>
                            <button type="button" class="btn btn-sm btn-outline-danger" id="btn-clear-cart">
                                <i class="fa fa-trash-o"></i> Kosongkan Keranjang
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table mb-0" id="cart-table">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width:80px">Gambar</th>
                                        <th>Produk</th>
                                        <th style="width:140px">Harga</th>
                                        <th style="width:160px">Jumlah</th>
                                        <th style="width:140px">Subtotal</th>
                                        <th style="width:60px"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                @forelse ($items as $it)
                                    <tr data-item-id="{{ $it['id'] }}"
                                        data-update-url="{{ route('cart.update', $it['id']) }}"
                                        data-remove-url="{{ route('cart.destroy', $it['id']) }}">
                                        <td>
                                            <a href="{{ $it['url'] }}" class="text-decoration-none">
                                                <img src="{{ $it['image'] ? asset('storage/'.$it['image']) : asset('images/placeholder.jpg') }}" alt="{{ $it['name'] }}" class="img-fluid rounded" width="64" height="64">
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ $it['url'] }}" class="text-body">{{ $it['name'] }}</a>
                                            @if(!empty($it['sku']))
                                                <div class="text-muted small">SKU: {{ $it['sku'] }}</div>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="fw-semibold">Rp. {{ number_format($it['price'], 0, ',', '.') }}</div>
                                        </td>
                                        <td>
                                            <div class="qty-control">
                                                <button type="button" class="btn btn-outline-secondary btn-sm btn-qty-dec">−</button>
                                                <input type="number" class="form-control form-control-sm text-center input-qty" min="1" max="999" value="{{ $it['quantity'] }}" style="max-width:64px;">
                                                <button type="button" class="btn btn-outline-secondary btn-sm btn-qty-inc">+</button>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="row-subtotal fw-semibold">Rp. {{ number_format($it['subtotal'], 0, ',', '.') }}</div>
                                        </td>
                                        <td class="text-end">
                                            <button type="button" class="btn btn-outline-danger btn-sm btn-remove" aria-label="Hapus">
                                                <i class="fa fa-trash-o"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="empty-row">
                                        <td colspan="6" class="text-center py-5">Keranjang kosong</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        <a href="{{ route('catalog.index') }}" class="btn btn-outline-secondary">
                            ← Lanjut Belanja
                        </a>
                        <a href="{{ route('checkout.index') }}" class="btn btn-secondary">
                            Lanjut ke Checkout →
                        </a>
                    </div>
                </div>
            </div>

            <!-- Summary -->
            <div class="col-lg-4">
                <div class="card shadow-sm cart-summary-card">
                    <div class="card-header">
                        <h6 class="mb-0">Ringkasan Belanja</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal</span>
                            <strong id="cart-subtotal">Rp {{ number_format($subtotal, 0, ',', '.') }}</strong>
                        </div>
                        <div class="text-muted small">* Belum termasuk ongkir & promo.</div>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('checkout.index') }}" class="btn btn-secondary w-100">Checkout</a>
                    </div>
                </div>

                <div class="mt-3 small text-muted">
                    <i class="fa fa-shield"></i> Belanja aman & pembayaran terlindungi.
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
(function() {
    'use strict';

    const csrf     = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    const authUrl  = @json(route('login-register.index'));
    const urlCount = @json(route('cart.count'));
    const urlSummary = @json(route('cart.summary'));
    const urlClear = @json(route('cart.clear'));

    const $$  = (s, c = document) => Array.from(c.querySelectorAll(s));
    const $   = (s, c = document) => c.querySelector(s);

    const formatIDR = (n) => 'Rp ' + (Math.round((+n || 0))).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');

    const notify = (type, message) => {
        if (typeof window.showNotification === 'function') return window.showNotification(type, message);
        if (window.toastr) return window.toastr[type || 'info'](message || '');
        console.log(`[${type}] ${message}`);
    };

    const ajax = (url, options = {}) => {
        // jQuery jika tersedia
        if (window.jQuery) {
            return new Promise((resolve, reject) => {
                const method = (options.method || 'GET').toUpperCase();
                const data   = options.data || {};
                const headers= Object.assign({'X-CSRF-TOKEN': csrf, 'Accept':'application/json'}, options.headers || {});
                jQuery.ajax({ url, method, data, headers,
                    success: (resp) => resolve(resp),
                    error: (xhr) => {
                        if (xhr?.status === 401) { setTimeout(()=>location.href = authUrl, 100); return; }
                        const e = new Error((xhr?.responseJSON && xhr.responseJSON.message) || xhr.statusText || 'Request gagal');
                        e.status = xhr?.status || 0; e.payload = xhr?.responseJSON || null; reject(e);
                    }
                });
            });
        }
        // fetch fallback
        const opts = Object.assign({
            headers: {'X-CSRF-TOKEN': csrf, 'Accept': 'application/json'}
        }, options);
        return fetch(url, opts).then(async (res) => {
            let json = {};
            try { json = await res.json(); } catch {}
            if (res.status === 401) { setTimeout(()=>location.href = authUrl, 100); const e=new Error('Unauthorized'); e.status=401; e.payload=json; throw e; }
            if (!res.ok) { const e=new Error(json?.message || 'Request gagal'); e.status=res.status; e.payload=json; throw e; }
            return json;
        });
    };

    const disable = (el) => { if (!el) return () => {}; el.classList.add('is-loading'); el.setAttribute('aria-disabled','true'); return ()=>{ el.classList.remove('is-loading'); el.removeAttribute('aria-disabled'); }; };

    const updateHeaderCount = () => {
        if (typeof window.updateCartCount === 'function') return window.updateCartCount();
        // fallback: hitung summary
        ajax(urlCount).catch(()=>{});
    };

    const applySummary = (subtotal) => {
        $('#cart-subtotal').textContent = formatIDR(subtotal);
        window.dispatchEvent(new CustomEvent('cart:updated', { detail: { subtotal } }));
        if (window.Livewire?.dispatch) {
            try { window.Livewire.dispatch('cart:updated', { subtotal }); } catch {}
            try { window.Livewire.dispatch('cartUpdated', { subtotal }); } catch {}
        }
        updateHeaderCount();
    };

    const removeRow = (tr) => {
        tr?.parentNode?.removeChild(tr);
        if (!$('#cart-table tbody').children.length) {
            const empty = document.createElement('tr');
            empty.className = 'empty-row';
            empty.innerHTML = '<td colspan="6" class="text-center py-5">Keranjang kosong</td>';
            $('#cart-table tbody').appendChild(empty);
        }
    };

    // --- Events: qty increment/decrement
    document.addEventListener('click', function (e) {
        const btnInc = e.target.closest('.btn-qty-inc');
        const btnDec = e.target.closest('.btn-qty-dec');

        if (!btnInc && !btnDec) return;

        const tr   = e.target.closest('tr[data-item-id]');
        const input= $('.input-qty', tr);
        if (!tr || !input) return;

        let val = parseInt(input.value || '1', 10);
        if (btnInc) val = Math.min(999, val + 1);
        if (btnDec) val = Math.max(1, val - 1);
        input.value = val;

        // trigger update
        doUpdateQty(tr, val, btnInc || btnDec);
    });

    // --- Events: qty manual change (debounced)
    let qtyTimer = null;
    document.addEventListener('input', function (e) {
        const inp = e.target.closest('.input-qty');
        if (!inp) return;

        clearTimeout(qtyTimer);
        const tr = inp.closest('tr[data-item-id]');
        let val = parseInt(inp.value || '1', 10);
        if (isNaN(val) || val < 1) val = 1;
        if (val > 999) val = 999;
        inp.value = val;

        qtyTimer = setTimeout(() => doUpdateQty(tr, val, inp), 300);
    });

    function doUpdateQty(tr, qty, el) {
        const enable = disable(el);
        const url = tr.getAttribute('data-update-url');
        const isJq = !!window.jQuery;

        const req = isJq
            ? ajax(url, { method: 'POST', data: { _method: 'PUT', quantity: qty } })
            : ajax(url, {
                method: 'POST',
                headers: {'Content-Type':'application/json'},
                body: JSON.stringify({ _method: 'PUT', quantity: qty })
              });

        req.then((resp) => {
            // update row subtotal
            const rowSubtotal = resp?.item?.formatted_subtotal || formatIDR(resp?.item?.subtotal || 0);
            $('.row-subtotal', tr).textContent = rowSubtotal;

            // update summary
            applySummary(resp?.cart?.subtotal || 0);
            notify('success', resp?.message || 'Jumlah item diperbarui');
        }).catch((err) => {
            if (err?.status === 401) return;
            notify('error', err?.payload?.message || err?.message || 'Gagal memperbarui jumlah');
        }).finally(enable);
    }

    // --- Remove item
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-remove');
        if (!btn) return;

        e.preventDefault();
        const tr  = btn.closest('tr[data-item-id]');
        const url = tr.getAttribute('data-remove-url');
        const enable = disable(btn);

        const isJq = !!window.jQuery;
        const req = isJq
            ? ajax(url, { method: 'POST', data: { _method: 'DELETE' } })
            : ajax(url, { method: 'DELETE' });

        req.then((resp) => {
            removeRow(tr);
            applySummary(resp?.cart?.subtotal || 0);
            notify('success', resp?.message || 'Item dihapus dari keranjang');
        }).catch((err) => {
            if (err?.status === 401) return;
            notify('error', err?.payload?.message || err?.message || 'Gagal menghapus item');
        }).finally(enable);
    });

    // --- Clear cart
    $('#btn-clear-cart')?.addEventListener('click', function () {
        if (!confirm('Kosongkan seluruh keranjang?')) return;

        const btn = this; const enable = disable(btn);

        const isJq = !!window.jQuery;
        const req = isJq
            ? ajax(urlClear, { method: 'DELETE' })
            : ajax(urlClear, { method: 'DELETE' });

        req.then((resp) => {
            // bersihkan tbody
            const tbody = $('#cart-table tbody');
            tbody.innerHTML = '<tr class="empty-row"><td colspan="6" class="text-center py-5">Keranjang kosong</td></tr>';
            applySummary(0);
            notify('success', resp?.message || 'Keranjang dikosongkan');
        }).catch((err) => {
            if (err?.status === 401) return;
            notify('error', err?.payload?.message || err?.message || 'Gagal mengosongkan keranjang');
        }).finally(enable);
    });
})();
</script>
@endpush
@endsection
