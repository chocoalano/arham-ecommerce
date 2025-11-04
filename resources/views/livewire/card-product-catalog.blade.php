{{-- Card tanpa Livewire events — full onclick JS --}}
<div class="ptk-product shop-grid-view-product" data-product-id="{{ (int) $productId }}">
    <div class="image">
        <a href="{{ $p['url'] ?? '#' }}" title="Detail" onclick="return PTK.detail(@json($p['url'] ?? null));">
            <img width="300" height="360" src="{{ $p['image'] }}" class="img-fluid" alt="{{ $p['name'] }}" loading="lazy">
        </a>

        {{-- Quick view --}}
        <a class="hover-icon" href="#" title="Quick view" onclick="return PTK.quickView({{ (int) $productId }});">
            <i class="lnr lnr-eye"></i>
        </a>

        {{-- Wishlist toggle --}}
        <a class="hover-icon" href="#" title="{{ $inWishlist ? 'Remove from wishlist' : 'Add to wishlist' }}"
           aria-pressed="{{ $inWishlist ? 'true' : 'false' }}"
           onclick="return PTK.wishlistToggle({{ (int) $productId }}, this);">
            <i class="lnr lnr-heart {{ $inWishlist ? 'active' : '' }}"></i>
        </a>

        {{-- Add to cart --}}
        <a class="hover-icon" href="#" title="Add to cart" onclick="return PTK.addToCart({{ (int) $productId }}, 1, null, this);">
            <i class="lnr lnr-cart"></i>
        </a>

        {{-- Badge produk --}}
        <div class="product-badge">
            @if(!empty($p['discount']))
                <span class="discount-badge">-{{ $p['discount'] }}%</span>
            @endif
            @isset($p['is_new'])
                @if($p['is_new'])
                    <span class="new-badge">Baru</span>
                @endif
            @endisset
        </div>
    </div>

    <div class="content">
        <p class="product-title">
            <a href="{{ $p['url'] ?? '#' }}" onclick="return PTK.detail(@json($p['url'] ?? null));">
                {{ \Illuminate\Support\Str::limit($p['name'], 70) }}
            </a>
        </p>

        {{-- Harga --}}
        <p class="product-price">
            @if(!empty($p['from_variant']) && $p['from_variant'] > 0)
                <span class="discounted-price">Rp {{ number_format($p['from_variant'], 0, ',', '.') }}</span>
            @else
                @if(!empty($p['sale_price']) && $p['sale_price'] < $p['price'])
                    <span class="main-price discounted">Rp {{ number_format($p['price'], 0, ',', '.') }}</span>
                    <span class="discounted-price">Rp {{ number_format($p['final_price'], 0, ',', '.') }}</span>
                @else
                    <span class="main-price">Rp {{ number_format($p['price'], 0, ',', '.') }}</span>
                @endif
            @endif
        </p>
    </div>

    {{-- Rating bintang --}}
    <div class="rating">
        @php $stars = (int) round($p['rating_avg'] ?? 0); @endphp
        @for($i = 1; $i <= 5; $i++)
            <i class="lnr lnr-star {{ $i <= $stars ? 'active' : '' }}"></i>
        @endfor
    </div>
</div>

<script>
window.PTK = window.PTK || (function () {
    'use strict';

    // ---- Helpers ----
    const csrf       = () => document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    const urlcart    = '{{ route('cart.store') }}';
    const urlwishlist= '{{ route('wishlist.store') }}';
    const authUrl    = '{{ route('login-register.index') }}'; // redirect target saat 401

    const redirectToAuth = () => setTimeout(() => { window.location.href = authUrl; }, 100);

    const notify = (type, message) => {
        if (typeof window.showNotification === 'function') return window.showNotification(type, message);
        if (window.toastr) return toastr[type || 'info'](message || '');
        console.log(`[${type}] ${message}`);
    };

    // kirim sinyal ke HeaderComponents tanpa refresh
    const broadcast = (name, detail = {}) => {
        // 1) Browser CustomEvent (Alpine/JS listener di header)
        window.dispatchEvent(new CustomEvent(name, { detail }));

        // 2) jQuery event (jika header dengar via jQuery)
        if (window.jQuery) jQuery(document).trigger(name, detail);

        // 3) Livewire global event (jika header pakai Livewire listener)
        // dukung dua gaya penamaan: with colon dan camelCase
        if (window.Livewire && typeof window.Livewire.dispatch === 'function') {
            try { window.Livewire.dispatch(name, detail); } catch {}
            // varian camelCase (mis. cartUpdated / wishlistUpdated)
            const camel = name.replace(/[:\-](\w)/g, (_,c)=>c.toUpperCase());
            try { window.Livewire.dispatch(camel, detail); } catch {}
        }
    };

    // Normalisasi error dari jQuery XHR
    const makeErrFromXhr = (xhr) => {
        const err = new Error((xhr?.responseJSON && xhr.responseJSON.message) || xhr?.statusText || 'Request failed');
        err.status  = xhr?.status || 0;
        err.payload = xhr?.responseJSON || null;
        return err;
    };

    // POST helper: Promise & handle 401 -> redirect
    const post = (url, data = {}) => {
        if (window.jQuery) {
            return new Promise((resolve, reject) => {
                jQuery.ajax({
                    url, method: 'POST', data,
                    headers: { 'X-CSRF-TOKEN': csrf(), 'Accept': 'application/json' },
                    success: (resp) => resolve(resp),
                    error: (xhr) => {
                        if (xhr && xhr.status === 401) { redirectToAuth(); return; }
                        reject(makeErrFromXhr(xhr));
                    }
                });
            });
        }
        // Fallback fetch
        return fetch(url, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrf(), 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify(data),
        }).then(async (res) => {
            const json = await res.json().catch(() => ({}));
            if (!res.ok) {
                if (res.status === 401) { redirectToAuth(); const e=new Error(json?.message||'Unauthorized'); e.status=401; e.payload=json; throw e; }
                const err = new Error(json?.message || 'Request failed'); err.status = res.status; err.payload = json; throw err;
            }
            return json;
        });
    };

    const disableTemporarily = (el) => {
        if (!el) return () => {};
        el.setAttribute('aria-disabled', 'true');
        el.classList.add('is-loading');
        return () => { el.removeAttribute('aria-disabled'); el.classList.remove('is-loading'); };
    };

    // ---- Actions ----
    function detail(url) {
        if (url) window.location.href = url;
        return false;
    }

    function quickView(productId) {
        window.dispatchEvent(new CustomEvent('open-quick-view', { detail: { productId } }));
        return false;
    }

    function wishlistToggle(productId, anchorEl) {
        const enable = disableTemporarily(anchorEl);
        post(urlwishlist, { product_id: productId })
            .then((resp) => {
                const inWishlist = !!(resp && (resp.in_wishlist === true || resp.in_wishlist === 1));
                // toggle icon
                const icon = anchorEl?.querySelector('.lnr-heart');
                if (icon) icon.classList.toggle('active', inWishlist);
                // aria-pressed
                anchorEl?.setAttribute('aria-pressed', inWishlist ? 'true' : 'false');

                // broadcast ke header (tanpa refresh)
                // jika API mengembalikan total wishlist, kirim; jika tidak null-kan
                const count = resp?.count ?? null;
                broadcast('wishlist:updated', { inWishlist, productId, count });

                // fallback update count via helper bila ada
                if (typeof window.updateWishlistCount === 'function') window.updateWishlistCount();

                notify('success', resp?.message || (inWishlist ? 'Ditambahkan ke wishlist' : 'Dihapus dari wishlist'));
            })
            .catch((err) => {
                if (err?.status === 401) return; // sudah redirect
                notify('error', err?.payload?.message || 'Gagal memproses wishlist.');
            })
            .finally(enable);
        return false;
    }

    function addToCart(productId, qty = 1, variantId = null, anchorEl) {
        const enable = disableTemporarily(anchorEl);
        const payload = { product_id: productId, quantity: qty || 1 };
        if (variantId) payload.variant_id = variantId;

        post(urlcart, payload)
            .then((resp) => {
                // Minta pilih varian → buka quick view, tetap tanpa refresh
                if (resp && (resp.require_variant === true || resp.requires_variant === true || resp.code === 'REQUIRES_VARIANT')) {
                    window.dispatchEvent(new CustomEvent('open-quick-view', { detail: { productId } }));
                    return;
                }

                // ringkasan cart (jika ada dari API)
                const totalItems = resp?.cart_summary?.total_items ?? null;
                const subtotal   = resp?.cart_summary?.subtotal ?? null;

                // broadcast ke header (tanpa refresh)
                broadcast('cart:updated', { productId, qty, totalItems, subtotal });

                // fallback update count via helper bila ada
                if (typeof window.updateCartCount === 'function') window.updateCartCount();

                notify('success', resp?.message || 'Produk ditambahkan ke keranjang');
            })
            .catch((err) => {
                if (err?.status === 401) return; // sudah redirect
                if (err?.status === 422) {
                    window.dispatchEvent(new CustomEvent('open-quick-view', { detail: { productId } }));
                } else {
                    notify('error', err?.payload?.message || 'Gagal menambahkan ke keranjang.');
                }
            })
            .finally(enable);
        return false;
    }

    return { detail, quickView, wishlistToggle, addToCart };
})();
</script>
