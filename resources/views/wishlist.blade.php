@extends('layouts.app')

@section('title', 'Wishlist Saya')

@section('content')
    @livewire('breadscrumb')

    <div class="page-section mb-80">
        <div class="container">
            <div class="row">
                <div class="col-12">

                    <div class="cart-table table-responsive">
                        <table class="table wishlist-table">
                            <thead>
                                <tr>
                                    <th class="pro-thumbnail">Gambar</th>
                                    <th class="pro-title">Produk</th>
                                    <th class="pro-price">Harga</th>
                                    <th class="pro-added">Ditambahkan</th>
                                    <th class="pro-actions">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($items as $item)
                                    @php $card = $item['card'] ?? []; @endphp

                                    <tr
                                        data-item-id="{{ $item['id'] }}"
                                        data-delete-url="{{ route('wishlist.destroy', ['id' => $item['id']]) }}"
                                        data-product-id="{{ $card['id'] ?? '' }}"
                                        data-variant-id="{{ $item['purchasable_type'] === \App\Models\ProductVariant::class ? $item['purchasable_id'] : '' }}"
                                    >
                                        <td class="pro-thumbnail">
                                            <a href="{{ $card['url'] ?? '#' }}" class="js-no-nav">
                                                <img
                                                    src="{{ $card['image'] ?? asset('images/placeholder.jpg') }}"
                                                    alt="{{ $card['name'] ?? 'Produk' }}"
                                                    width="80" height="80" lazzyload="lazy">
                                            </a>
                                        </td>

                                        <td class="pro-title">
                                            <a href="{{ $card['url'] ?? '#' }}" class="js-no-nav">
                                                {{ $card['name'] ?? '-' }}
                                            </a>
                                        </td>

                                        <td class="pro-price">
                                            @php
                                                $price = $card['price'] ?? 0;
                                                $final = $card['final_price'] ?? $price;
                                                $sale  = $card['sale_price'] ?? null;
                                            @endphp

                                            @if(!is_null($sale) && $sale > 0 && $sale < $price)
                                                <span class="amount text-danger fw-bold">Rp {{ number_format($final, 0, ',', '.') }}</span>
                                                <small class="text-muted text-decoration-line-through d-block">
                                                    Rp {{ number_format($price, 0, ',', '.') }}
                                                </small>
                                            @else
                                                <span class="amount">Rp {{ number_format($final, 0, ',', '.') }}</span>
                                            @endif
                                        </td>

                                        <td class="pro-added">
                                            {{ \Illuminate\Support\Carbon::parse($item['added_at'])->format('d M Y H:i') }}
                                        </td>

                                        <td class="pro-actions">
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-outline-primary btn-move-to-cart">
                                                    <i class="lnr lnr-cart"></i> Tambah ke Keranjang
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger btn-remove-wishlist">
                                                    <i class="fa fa-trash-o"></i> Hapus
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="empty-row">
                                        <td colspan="5" class="text-center">Tidak ada wishlist</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    (function () {
        'use strict';

        const csrf   = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        const authUrl= @json(route('login-register.index'));
        const urlCart= @json(route('cart.store'));

        // ----- Helpers -----
        const notify = (type, msg) => {
            if (typeof window.showNotification === 'function') return window.showNotification(type, msg);
            if (window.toastr) return toastr[type || 'info'](msg || '');
            console.log(`[${type}] ${msg}`);
        };

        const preventHashNav = (e) => {
            const a = e.target.closest('a.js-no-nav[href="#"]');
            if (a) { e.preventDefault(); return false; }
        };
        document.addEventListener('click', preventHashNav);

        const onEmptyTable = () => {
            const tbody = document.querySelector('.wishlist-table tbody');
            if (!tbody) return;
            if (!tbody.querySelector('tr') || tbody.querySelectorAll('tr').length === 0) {
                const tr = document.createElement('tr');
                tr.className = 'empty-row';
                tr.innerHTML = '<td colspan="5" class="text-center">Tidak ada wishlist</td>';
                tbody.appendChild(tr);
            }
        };

        const ajax = (url, options={}) => {
            // jQuery jika ada
            if (window.jQuery) {
                return new Promise((resolve, reject) => {
                    const method = (options.method || 'GET').toUpperCase();
                    const data   = options.data || {};
                    const headers= options.headers || {};
                    jQuery.ajax({
                        url,
                        method,
                        data,
                        headers: Object.assign({'X-CSRF-TOKEN': csrf, 'Accept':'application/json'}, headers),
                        success: (resp) => resolve(resp),
                        error:   (xhr) => {
                            if (xhr?.status === 401) { setTimeout(()=>location.href = authUrl, 100); return; }
                            const e = new Error((xhr?.responseJSON && xhr.responseJSON.message) || xhr.statusText || 'Request gagal');
                            e.status = xhr?.status || 0; e.payload = xhr?.responseJSON || null;
                            reject(e);
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

        const disable = (el) => { if(!el) return ()=>{}; el.disabled = true; el.classList.add('is-loading'); return ()=>{ el.disabled=false; el.classList.remove('is-loading'); }; };

        // ----- REMOVE ITEM (AJAX) -----
        document.addEventListener('click', function (e) {
            const btn = e.target.closest('.btn-remove-wishlist');
            if (!btn) return;
            e.preventDefault();

            const tr  = btn.closest('tr');
            const url = tr?.getAttribute('data-delete-url');
            if (!url) return;

            const enable = disable(btn);

            // jQuery: method spoof _method=DELETE, fetch: TRUE method=DELETE
            const isJq = !!window.jQuery;
            const req = isJq
                ? ajax(url, { method: 'POST', data: { _method: 'DELETE' } })
                : ajax(url, { method: 'DELETE' });

            req.then((resp) => {
                // Hapus row tanpa reload
                tr.parentNode.removeChild(tr);
                onEmptyTable();

                // Update header count tanpa reload
                if (typeof window.updateWishlistCount === 'function') window.updateWishlistCount();
                window.dispatchEvent(new CustomEvent('wishlist:updated', { detail: { count: resp?.count ?? null } }));
                if (window.Livewire?.dispatch) {
                    try { window.Livewire.dispatch('wishlist:updated', { count: resp?.count ?? null }); } catch {}
                    try { window.Livewire.dispatch('wishlistUpdated', { count: resp?.count ?? null }); } catch {}
                }

                notify('success', resp?.message || 'Item wishlist dihapus');
            }).catch((err) => {
                if (err?.status === 401) return; // sudah redirect
                notify('error', err?.payload?.message || err?.message || 'Gagal menghapus item');
            }).finally(enable);
        });

        // ----- MOVE/ADD TO CART (AJAX) -----
        document.addEventListener('click', function (e) {
            const btn = e.target.closest('.btn-move-to-cart');
            if (!btn) return;
            e.preventDefault();

            const tr          = btn.closest('tr');
            const productId   = parseInt(tr?.getAttribute('data-product-id') || 0, 10);
            const variantId   = parseInt(tr?.getAttribute('data-variant-id') || 0, 10) || null;
            if (!productId) return;

            const enable = disable(btn);

            // payload API cart
            const payload = {
                product_id: productId,
                quantity: 1
            };
            if (variantId) payload.variant_id = variantId;

            const isJq = !!window.jQuery;
            const req  = isJq
                ? ajax(urlCart, { method: 'POST', data: payload })
                : ajax(urlCart, {
                    method: 'POST',
                    headers: {'Content-Type':'application/json'},
                    body: JSON.stringify(payload)
                  });

            req.then((resp) => {
                // Jika butuh pilih varian, buka quick view (tetap tanpa reload)
                if (resp && (resp.require_variant === true || resp.requires_variant === true || resp.code === 'REQUIRES_VARIANT')) {
                    window.dispatchEvent(new CustomEvent('open-quick-view', { detail: { productId } }));
                    return;
                }

                // Update header keranjang
                if (typeof window.updateCartCount === 'function') window.updateCartCount();
                window.dispatchEvent(new CustomEvent('cart:updated', {
                    detail: {
                        productId,
                        totalItems: resp?.cart_summary?.total_items ?? null,
                        subtotal: resp?.cart_summary?.subtotal ?? null
                    }
                }));
                if (window.Livewire?.dispatch) {
                    try { window.Livewire.dispatch('cart:updated', { totalItems: resp?.cart_summary?.total_items ?? null }); } catch {}
                    try { window.Livewire.dispatch('cartUpdated', { totalItems: resp?.cart_summary?.total_items ?? null }); } catch {}
                }

                notify('success', resp?.message || 'Produk ditambahkan ke keranjang');
            }).catch((err) => {
                if (err?.status === 401) return; // redirect sudah dilakukan
                if (err?.status === 422) {
                    window.dispatchEvent(new CustomEvent('open-quick-view', { detail: { productId } }));
                    return;
                }
                notify('error', err?.payload?.message || err?.message || 'Gagal menambahkan ke keranjang');
            }).finally(enable);
        });

    })();
    </script>
    @endpush
@endsection
