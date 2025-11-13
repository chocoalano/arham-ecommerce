@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@section('content')
@push('styles')
<style>
    /* Mengganti 'gap' agar kompatibel dengan browser yang lebih tua (opsional) atau memastikan konsistensi */
    .qty-control {
        display: flex;
        align-items: center;
        /* BS5 utility: d-flex align-items-center */
        gap: .5rem; /* Menambah jarak sedikit untuk tampilan yang lebih baik, dari .25rem menjadi .5rem */
    }

    /* Di Bootstrap 5, class btn sudah memiliki padding & text-align yang baik */
    .qty-control .btn {
        width: 2rem; /* Sedikit lebih kecil agar lebih elegan */
        height: 2rem;
        padding: 0;
        line-height: 2rem; /* Memastikan konten terpusat */
        text-align: center;
        /* Gunakan kelas Bootstrap 5: btn-sm */
    }

    /* Class untuk visual loading */
    .is-loading {
        opacity: .6;
        pointer-events: none;
        cursor: not-allowed; /* Menambah indikator visual */
    }

    /* Membuat ringkasan keranjang menempel saat digulir */
    .cart-summary-card {
        position: sticky;
        top: 90px;
    }

    /* Memastikan konten tabel terpusat secara vertikal */
    .table td, .table th {
        vertical-align: middle;
    }

    /* Perbaikan minor untuk input qty agar lebih mudah diklik */
    .qty-control input[type="number"] {
        max-width: 70px; /* Lebar yang lebih baik */
    }
</style>
@endpush

@livewire('breadscrumb')

<div class="page-section mb-80">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    {{-- Tambah border-0 untuk tampilan lebih modern ala BS5 card --}}
                    <div class="card-header bg-light d-flex align-items-center justify-content-between">
                        {{-- Menambah bg-light untuk header card agar terlihat lebih jelas --}}
                        <h5 class="mb-0">Keranjang</h5>
                        <div>
                            <button type="button" class="btn btn-sm btn-outline-danger" id="btn-clear-cart">
                                <i class="fa fa-trash"></i> Kosongkan Keranjang
                                {{-- Menggunakan fa-trash (lebih modern) dari fa-trash-o --}}
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table mb-0 table-hover" id="cart-table">
                                {{-- Menambah table-hover untuk interaksi yang lebih baik --}}
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
                                    <tr data-item-id="{{ $it->id }}"
                                        data-update-url="{{ route('cart.update', $it->id) }}"
                                        data-remove-url="{{ route('cart.destroy', $it->id) }}">
                                        <td>
                                            <a href="{{ $it->url }}" class="text-decoration-none">
                                                <img src="{{ $it->image ? asset('storage/'.$it->image) : asset('images/placeholder.jpg') }}"
                                                    alt="{{ $it->name }}"
                                                    class="img-fluid rounded"
                                                    width="64" height="64"
                                                    loading="lazy">
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ $it->url }}" class="text-body fw-semibold">{{ $it->name }}</a>
                                            {{-- Menambah fw-semibold agar nama produk lebih menonjol --}}
                                            @if(!empty($it->sku))
                                                <div class="text-muted small">SKU: {{ $it->sku }}</div>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="fw-bold text-danger">Rp. {{ number_format($it->price, 0, ',', '.') }}</div>
                                            {{-- Memberikan penekanan warna pada harga --}}
                                        </td>
                                        <td>
                                            <div class="qty-control">
                                                <button type="button" class="btn btn-outline-secondary btn-sm btn-qty-dec">−</button>
                                                <input type="number" class="form-control form-control-sm text-center input-qty" min="1" max="999" value="{{ $it->quantity }}" style="max-width:64px;">
                                                <button type="button" class="btn btn-outline-secondary btn-sm btn-qty-inc">+</button>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="row-subtotal fw-bold text-primary">Rp. {{ number_format($it->subtotal, 0, ',', '.') }}</div>
                                            {{-- Memberikan penekanan warna pada subtotal baris --}}
                                        </td>
                                        <td class="text-end">
                                            <button type="button" class="btn btn-outline-danger btn-sm btn-remove" aria-label="Hapus">
                                                <i class="fa fa-trash"></i>
                                                {{-- Menggunakan fa-trash --}}
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
                            <i class="fa fa-arrow-left"></i> Lanjut Belanja
                            {{-- Menambahkan ikon panah untuk kejelasan --}}
                        </a>
                        <a href="{{ route('checkout.index') }}" class="btn btn-primary">
                            {{-- Mengganti btn-secondary menjadi btn-primary untuk CTA utama --}}
                            Lanjut ke Checkout <i class="fa fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm cart-summary-card border-0">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">Ringkasan Belanja</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal</span>
                            <strong id="cart-subtotal" class="fw-bold text-primary">Rp {{ number_format($subtotal, 0, ',', '.') }}</strong>
                        </div>
                        <div class="text-muted small">* Belum termasuk ongkir & promo.</div>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('checkout.index') }}" class="btn btn-primary btn-lg w-100">Checkout</a>
                        {{-- Mengganti btn-secondary menjadi btn-primary dan menambah btn-lg untuk CTA utama --}}
                    </div>
                </div>

                <div class="mt-3 small text-muted">
                    <i class="fa fa-shield-alt me-1"></i> Belanja aman & pembayaran terlindungi.
                    {{-- Mengganti fa-shield menjadi fa-shield-alt dan menambah me-1 (margin-end) --}}
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
(function() {
    'use strict';

    // --- Konstanta dan Helpers
    const csrf     = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    const authUrl  = @json(route('login-register.index'));
    const urlCount = @json(route('cart.count'));
    const urlClear = @json(route('cart.clear'));

    const $$  = (s, c = document) => Array.from(c.querySelectorAll(s));
    const $   = (s, c = document) => c.querySelector(s);

    // Fungsi format mata uang IDR
    const formatIDR = (n) => 'Rp ' + (Math.round((+n || 0))).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');

    // Fungsi notifikasi (asumsi ada window.showNotification atau window.toastr)
    const notify = (type, message) => {
        if (typeof window.showNotification === 'function') return window.showNotification(type, message);
        if (window.toastr) return window.toastr[type || 'info'](message || '');
        console.log(`[${type}] ${message}`);
    };

    // Fungsi AJAX universal (mempertahankan logic jQuery/Fetch fallback)
    const ajax = (url, options = {}) => {
        // jQuery jika tersedia
        if (window.jQuery) {
            return new Promise((resolve, reject) => {
                const method = (options.method || 'GET').toUpperCase();
                // Untuk PUT/DELETE via POST di Laravel, method perlu di-spoof
                const data   = Object.assign({
                    ...(method === 'PUT' ? {_method: 'PUT'} : {}),
                    ...(method === 'DELETE' ? {_method: 'DELETE'} : {})
                }, options.data || {});

                const headers= Object.assign({'X-CSRF-TOKEN': csrf, 'Accept':'application/json'}, options.headers || {});

                jQuery.ajax({ url, method: 'POST', data, headers,
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
        let opts = Object.assign({
            headers: {'X-CSRF-TOKEN': csrf, 'Accept': 'application/json'}
        }, options);

        // Handle method spoofing untuk fetch
        if (options.method === 'PUT' || options.method === 'DELETE') {
            opts.method = 'POST';
            let bodyData = options.body ? JSON.parse(options.body) : {};
            bodyData._method = options.method;
            opts.body = JSON.stringify(bodyData);
            opts.headers['Content-Type'] = 'application/json';
        }

        return fetch(url, opts).then(async (res) => {
            let json = {};
            try { json = await res.json(); } catch {}
            if (res.status === 401) { setTimeout(()=>location.href = authUrl, 100); const e=new Error('Unauthorized'); e.status=401; e.payload=json; throw e; }
            if (!res.ok) { const e=new Error(json?.message || 'Request gagal'); e.status=res.status; e.payload=json; throw e; }
            return json;
        });
    };

    // Fungsi untuk menonaktifkan tombol/elemen saat loading
    const disable = (el) => {
        if (!el) return () => {};
        const originalText = el.innerHTML;
        el.classList.add('is-loading');
        el.setAttribute('aria-disabled','true');
        el.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...';
        return () => {
            el.classList.remove('is-loading');
            el.removeAttribute('aria-disabled');
            el.innerHTML = originalText;
        };
    };

    // Fungsi untuk memperbarui jumlah item di header (Livewire/custom function)
    const updateHeaderCount = () => {
        if (typeof window.updateCartCount === 'function') return window.updateCartCount();
        // Fallback: hitung summary jika perlu (URL count tidak digunakan, gunakan summary)
        // ajax(urlCount).catch(()=>{});
    };

    // Fungsi untuk memperbarui ringkasan keranjang
    const applySummary = (subtotal) => {
        $('#cart-subtotal').textContent = formatIDR(subtotal);
        window.dispatchEvent(new CustomEvent('cart:updated', { detail: { subtotal } }));
        if (window.Livewire?.dispatch) {
            try { window.Livewire.dispatch('cart:updated', { subtotal }); } catch {}
            try { window.Livewire.dispatch('cartUpdated', { subtotal }); } catch {}
        }
        updateHeaderCount();
    };

    // Fungsi untuk menghapus baris item dari tabel
    const removeRow = (tr) => {
        tr?.parentNode?.removeChild(tr);
        if ($$('#cart-table tbody tr:not(.empty-row)').length === 0) {
            const empty = document.createElement('tr');
            empty.className = 'empty-row';
            empty.innerHTML = '<td colspan="6" class="text-center py-5">Keranjang kosong</td>';
            $('#cart-table tbody').appendChild(empty);

            // Nonaktifkan tombol checkout jika keranjang kosong
            const checkoutBtn = $('.card-footer a.btn-primary');
            if (checkoutBtn) checkoutBtn.classList.add('disabled');
            $('#cart-summary-card a.btn-primary')?.classList.add('disabled');

            // Sembunyikan tombol "Kosongkan Keranjang"
            $('#btn-clear-cart').style.display = 'none';

        }
    };

    // Inisialisasi awal (nonaktifkan checkout jika keranjang kosong dari awal)
    document.addEventListener('DOMContentLoaded', () => {
        if ($$('#cart-table tbody tr:not(.empty-row)').length === 0) {
            const checkoutBtn = $('.card-footer a.btn-primary');
            if (checkoutBtn) checkoutBtn.classList.add('disabled');
            $('#cart-summary-card a.btn-primary')?.classList.add('disabled');
            $('#btn-clear-cart').style.display = 'none';
        } else {
            $('#btn-clear-cart').style.display = 'inline-block';
        }
    });

    // --- Events: qty increment/decrement
    document.addEventListener('click', function (e) {
        const btnInc = e.target.closest('.btn-qty-inc');
        const btnDec = e.target.closest('.btn-qty-dec');

        if (!btnInc && !btnDec) return;

        const tr   = e.target.closest('tr[data-item-id]');
        const input= $('.input-qty', tr);
        if (!tr || !input) return;

        let val = parseInt(input.value || '1', 10);

        // Pastikan nilai val dalam batas min/max
        const min = parseInt(input.min || '1', 10);
        const max = parseInt(input.max || '999', 10);

        if (btnInc) val = Math.min(max, val + 1);
        if (btnDec) val = Math.max(min, val - 1);

        // Hanya update jika nilai berubah
        if (parseInt(input.value, 10) !== val) {
            input.value = val;
            doUpdateQty(tr, val, btnInc || btnDec);
        }
    });

    // --- Events: qty manual change (debounced)
    let qtyTimer = null;
    document.addEventListener('input', function (e) {
        const inp = e.target.closest('.input-qty');
        if (!inp) return;

        clearTimeout(qtyTimer);
        const tr = inp.closest('tr[data-item-id]');
        let val = parseInt(inp.value || '1', 10);
        const min = parseInt(inp.min || '1', 10);
        const max = parseInt(inp.max || '999', 10);

        // Clamp value
        if (isNaN(val) || val < min) val = min;
        if (val > max) val = max;
        inp.value = val;

        qtyTimer = setTimeout(() => doUpdateQty(tr, val, inp), 500); // 500ms debounce
    });

    function doUpdateQty(tr, qty, el) {
        const url = tr.getAttribute('data-update-url');
        const enable = disable(el);

        ajax(url, {
            method: 'PUT',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ quantity: qty })
        }).then((resp) => {
            // update row subtotal
            const rowSubtotal = resp?.item?.formatted_subtotal || formatIDR(resp?.item?.subtotal || 0);
            $('.row-subtotal', tr).textContent = rowSubtotal;

            // update summary
            applySummary(resp?.cart?.subtotal || 0);
            notify('success', resp?.message || 'Jumlah item diperbarui');
        }).catch((err) => {
            if (err?.status === 401) return;
            // Kembalikan nilai input ke nilai sebelumnya jika ada error (perlu disimpan)
            // Untuk skenario ini, kita hanya notifikasi error
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

        ajax(url, { method: 'DELETE' }).then((resp) => {
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
        if (!confirm('Apakah Anda yakin ingin mengosongkan seluruh keranjang belanja? Tindakan ini tidak dapat dibatalkan.')) return;

        const btn = this; const enable = disable(btn);

        ajax(urlClear, { method: 'DELETE' }).then((resp) => {
            // bersihkan tbody
            const tbody = $('#cart-table tbody');
            tbody.innerHTML = ''; // Kosongkan
            // Tambahkan baris kosong
            const empty = document.createElement('tr');
            empty.className = 'empty-row';
            empty.innerHTML = '<td colspan="6" class="text-center py-5">Keranjang kosong</td>';
            tbody.appendChild(empty);

            // Perbarui ringkasan
            applySummary(0);

            // Nonaktifkan tombol checkout
            $('.card-footer a.btn-primary')?.classList.add('disabled');
            $('#cart-summary-card a.btn-primary')?.classList.add('disabled');
            btn.style.display = 'none';

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
