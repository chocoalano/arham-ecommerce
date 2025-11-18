/**
 * PTK (Product Toolkit) - Cart & Wishlist Handler
 * Handles product detail navigation, quick view, cart, and wishlist operations
 */
window.PTK = window.PTK || (function () {
    'use strict';

    // ---- Configuration ----
    const config = {
        csrf: () => {
            // Try multiple ways to get CSRF token
            return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                || document.querySelector('input[name="_token"]')?.value
                || '';
        },
        routes: {
            cart: document.querySelector('meta[name="route-cart-store"]')?.getAttribute('content') || '/cart',
            wishlist: document.querySelector('meta[name="route-wishlist-store"]')?.getAttribute('content') || '/wishlist',
            auth: document.querySelector('meta[name="route-login"]')?.getAttribute('content') || '/login'
        }
    };

    // ---- Helpers ----
    const redirectToAuth = () => {
        setTimeout(() => { window.location.href = config.routes.auth; }, 100);
    };

    const notify = (type, message) => {
        // Try toastr first (most common)
        if (window.toastr && typeof window.toastr[type] === 'function') {
            return window.toastr[type](message || '');
        }
        // Try window.showNotification (custom)
        if (typeof window.showNotification === 'function') {
            return window.showNotification(type, message);
        }
        // Fallback to alert with type prefix
        alert(`[${type.toUpperCase()}] ${message}`);
    };

    // Broadcast events to update header components
    const broadcast = (name, detail = {}) => {
        console.log('[PTK] Broadcasting:', name, detail);

        // 1) Browser CustomEvent (Alpine/JS listener)
        window.dispatchEvent(new CustomEvent(name, { detail }));

        // 2) jQuery event (if header listens via jQuery)
        if (window.jQuery) {
            jQuery(document).trigger(name, detail);
        }

        // 3) Livewire global event
        if (window.Livewire) {
            try {
                // Original format (cart:updated, wishlist:updated)
                if (typeof window.Livewire.dispatch === 'function') {
                    window.Livewire.dispatch(name, detail);
                } else if (typeof window.Livewire.emit === 'function') {
                    window.Livewire.emit(name, detail);
                }

                // CamelCase format (cartUpdated, wishlistUpdated)
                const camel = name.replace(/[:\-](\w)/g, (_, c) => c.toUpperCase());
                if (camel !== name) {
                    if (typeof window.Livewire.dispatch === 'function') {
                        window.Livewire.dispatch(camel, detail);
                    } else if (typeof window.Livewire.emit === 'function') {
                        window.Livewire.emit(camel, detail);
                    }
                }
            } catch (e) {
                console.warn('[PTK] Livewire broadcast error:', e);
            }
        }
    };

    // Normalize error from jQuery XHR
    const makeErrFromXhr = (xhr) => {
        const err = new Error(
            (xhr?.responseJSON && xhr.responseJSON.message) ||
            xhr?.statusText ||
            'Request failed'
        );
        err.status = xhr?.status || 0;
        err.payload = xhr?.responseJSON || null;
        return err;
    };

    // POST helper: Promise & handle 401/419 -> redirect
    const post = (url, data = {}) => {
        const token = config.csrf();

        if (window.jQuery) {
            return new Promise((resolve, reject) => {
                jQuery.ajax({
                    url,
                    method: 'POST',
                    data,
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    success: (resp) => resolve(resp),
                    error: (xhr) => {
                        // Handle 401 Unauthorized
                        if (xhr && xhr.status === 401) {
                            redirectToAuth();
                            return;
                        }
                        // Handle 419 Page Expired (CSRF token mismatch)
                        if (xhr && xhr.status === 419) {
                            notify('error', 'Sesi Anda telah berakhir. Halaman akan dimuat ulang.');
                            setTimeout(() => window.location.reload(), 1500);
                            return;
                        }
                        reject(makeErrFromXhr(xhr));
                    }
                });
            });
        }

        // Fallback to fetch
        return fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': token,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(data),
            credentials: 'same-origin'
        }).then(async (res) => {
            const json = await res.json().catch(() => ({}));
            if (!res.ok) {
                // Handle 401 Unauthorized
                if (res.status === 401) {
                    redirectToAuth();
                    const e = new Error(json?.message || 'Unauthorized');
                    e.status = 401;
                    e.payload = json;
                    throw e;
                }
                // Handle 419 Page Expired
                if (res.status === 419) {
                    notify('error', 'Sesi Anda telah berakhir. Halaman akan dimuat ulang.');
                    setTimeout(() => window.location.reload(), 1500);
                    const e = new Error('CSRF token mismatch');
                    e.status = 419;
                    e.payload = json;
                    throw e;
                }
                const err = new Error(json?.message || 'Request failed');
                err.status = res.status;
                err.payload = json;
                throw err;
            }
            return json;
        });
    };

    const disableTemporarily = (el) => {
        if (!el) return () => {};
        el.setAttribute('aria-disabled', 'true');
        el.classList.add('is-loading');
        return () => {
            el.removeAttribute('aria-disabled');
            el.classList.remove('is-loading');
        };
    };

    // ---- Public API ----

    /**
     * Navigate to product detail page
     * @param {string} url - Product detail URL
     * @returns {boolean} false to prevent default link behavior
     */
    function detail(url) {
        if (url) window.location.href = url;
        return false;
    }

    /**
     * Open quick view modal for product
     * @param {number} productId - Product ID
     * @returns {boolean} false to prevent default link behavior
     */
    function quickView(productId) {
        window.dispatchEvent(new CustomEvent('open-quick-view', {
            detail: { productId }
        }));
        return false;
    }

    /**
     * Toggle product in wishlist
     * @param {number} productId - Product ID
     * @param {HTMLElement} anchorEl - The clicked element
     * @returns {boolean} false to prevent default link behavior
     */
    function wishlistToggle(productId, anchorEl) {
        const enable = disableTemporarily(anchorEl);

        post(config.routes.wishlist, { product_id: productId })
            .then((resp) => {
                const inWishlist = !!(resp && (resp.in_wishlist === true || resp.in_wishlist === 1));

                // Toggle icon
                const icon = anchorEl?.querySelector('.lnr-heart');
                if (icon) icon.classList.toggle('active', inWishlist);

                // Update aria-pressed
                anchorEl?.setAttribute('aria-pressed', inWishlist ? 'true' : 'false');

                // Broadcast to header components
                const count = resp?.count ?? null;
                broadcast('wishlist:updated', { inWishlist, productId, count });
                broadcast('wishlistUpdated', { inWishlist, productId, count });

                // Fallback update count helper
                if (typeof window.updateWishlistCount === 'function') {
                    window.updateWishlistCount();
                }

                notify('success', resp?.message || (inWishlist ? 'Ditambahkan ke wishlist' : 'Dihapus dari wishlist'));
            })
            .catch((err) => {
                if (err?.status === 401) return; // Already redirected
                if (err?.status === 419) return; // Already reloading
                notify('error', err?.payload?.message || 'Gagal memproses wishlist.');
            })
            .finally(enable);

        return false;
    }

    /**
     * Add product to cart
     * @param {number} productId - Product ID
     * @param {number} qty - Quantity to add
     * @param {number|null} variantId - Product variant ID (optional)
     * @param {HTMLElement} anchorEl - The clicked element
     * @returns {boolean} false to prevent default link behavior
     */
    function addToCart(productId, qty = 1, variantId = null, anchorEl) {
        const enable = disableTemporarily(anchorEl);
        const payload = { product_id: productId, quantity: qty || 1 };
        if (variantId) payload.variant_id = variantId;

        post(config.routes.cart, payload)
            .then((resp) => {
                // Product requires variant selection → open quick view
                if (resp && (resp.require_variant === true || resp.requires_variant === true || resp.code === 'REQUIRES_VARIANT')) {
                    window.dispatchEvent(new CustomEvent('open-quick-view', {
                        detail: { productId }
                    }));
                    return;
                }

                // Cart summary (if returned from API)
                const totalItems = resp?.cart_summary?.total_items ?? null;
                const subtotal = resp?.cart_summary?.subtotal ?? null;

                // Broadcast to header components
                broadcast('cart:updated', { productId, qty, totalItems, subtotal });
                broadcast('cartUpdated', { productId, qty, totalItems, subtotal });

                // Fallback update count helper
                if (typeof window.updateCartCount === 'function') {
                    window.updateCartCount();
                }

                notify('success', resp?.message || 'Produk ditambahkan ke keranjang');
            })
            .catch((err) => {
                if (err?.status === 401) return; // Already redirected
                if (err?.status === 419) return; // Already reloading
                if (err?.status === 422) {
                    // Validation error → open quick view for variant selection
                    window.dispatchEvent(new CustomEvent('open-quick-view', {
                        detail: { productId }
                    }));
                } else {
                    notify('error', err?.payload?.message || 'Gagal menambahkan ke keranjang.');
                }
            })
            .finally(enable);

        return false;
    }

    // Public API
    return {
        detail,
        quickView,
        wishlistToggle,
        addToCart
    };
})();
