@extends('layouts.app')
@section('title', 'Pesanan Selesai')

@section('content')
{{-- Optional: jika kamu pakai breadscrumb Livewire, aktifkan baris ini --}}
{{-- @livewire('breadscrumb') --}}

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">

            {{-- Alert sukses --}}
            <div class="alert alert-success d-flex align-items-center p-4 rounded-3 shadow-sm">
                <i class="fa fa-check-circle me-3 fs-2"></i>
                <div>
                    <h4 class="mb-1">Terima kasih! Pesanan Anda telah kami terima.</h4>
                    @php
                        $noOrder = $order->order_number ?? ($orderNumber ?? '-');
                        $created = optional($order->created_at)->format('d M Y H:i');
                    @endphp
                    <div class="small">
                        No. Pesanan:
                        <strong id="orderNo">{{ $noOrder }}</strong>
                        <button type="button" class="btn btn-sm btn-outline-secondary ms-2"
                                onclick="copyOrderNumber('{{ $noOrder }}')">Salin</button>
                    </div>
                    <div class="text-muted small">Tanggal: {{ $created ?: now()->format('d M Y H:i') }}</div>
                </div>
            </div>

            <div class="row g-3">
                {{-- Kiri: Ringkasan & Pembayaran --}}
                <div class="col-lg-8">
                    {{-- Ringkasan Pesanan --}}
                    <div class="card shadow-sm">
                        <div class="card-header bg-white">
                            <h6 class="mb-0">Ringkasan Pesanan</h6>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Produk</th>
                                            <th class="text-center">Qty</th>
                                            <th class="text-end">Harga</th>
                                            <th class="text-end">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            // Bisa berupa koleksi Eloquent ($order->items) atau array map dari repo
                                            $items = $items ?? ($order->items ?? collect());
                                        @endphp

                                        @forelse($items as $it)
                                            @php
                                                $name = data_get($it,'name');
                                                $qty  = (int) data_get($it,'quantity', 1);
                                                $price= (float) data_get($it,'price', 0);
                                                $subtotal = (float) data_get($it,'subtotal', $qty * $price);
                                                $img  = data_get($it,'image');
                                                if (!$img && method_exists($it,'purchasable') && $it->relationLoaded('purchasable')) {
                                                    $p = $it->purchasable;
                                                    $img = method_exists($p,'firstImageUrl') ? $p->firstImageUrl() : data_get($p,'image_path');
                                                }
                                            @endphp
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        @if($img)
                                                            <img src="{{ $img }}" class="rounded me-3" width="48" height="48" alt="">
                                                        @endif
                                                        <div class="small">{{ $name }}</div>
                                                    </div>
                                                </td>
                                                <td class="text-center">x{{ $qty }}</td>
                                                <td class="text-end">Rp {{ number_format($price, 0, ',', '.') }}</td>
                                                <td class="text-end">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-muted py-4">Tidak ada item.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- Informasi Pembayaran --}}
                    <div class="card shadow-sm mt-3">
                        <div class="card-header bg-white">
                            <h6 class="mb-0">Informasi Pembayaran</h6>
                        </div>
                        <div class="card-body">
                            @php
                                $payment = $payment ?? ($order->payment ?? null);
                                $method  = data_get($payment,'payment_type', data_get($order,'payment_method', 'manual_transfer'));
                                $status  = data_get($payment,'transaction_status', data_get($order,'status', 'pending'));
                                $amount  = (float) data_get($payment,'gross_amount', data_get($order,'grand_total', 0));
                            @endphp

                            <dl class="row mb-0">
                                <dt class="col-sm-4">Metode</dt>
                                <dd class="col-sm-8">{{ strtoupper($method) }}</dd>
                                <dt class="col-sm-4">Status</dt>
                                <dd class="col-sm-8">
                                    <span class="badge {{ $status === 'settlement' || $status === 'paid' ? 'bg-success' : 'bg-warning text-dark' }}">
                                        {{ strtoupper($status) }}
                                    </span>
                                </dd>
                                <dt class="col-sm-4">Jumlah Tagihan</dt>
                                <dd class="col-sm-8 fw-semibold">Rp {{ number_format($amount, 0, ',', '.') }}</dd>
                            </dl>

                            @if($method === 'manual_transfer')
                                <hr>
                                <p class="mb-2">Silakan transfer ke rekening berikut:</p>
                                <ul class="mb-2">
                                    <li><strong>BCA</strong> 123456789 a.n. PT Contoh</li>
                                    <li><strong>Mandiri</strong> 987654321 a.n. PT Contoh</li>
                                </ul>
                                <p class="text-muted small mb-0">
                                    Unggah bukti transfer pada halaman detail pesanan atau kirim via WhatsApp CS.
                                </p>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Kanan: Total & Alamat --}}
                <div class="col-lg-4">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white">
                            <h6 class="mb-0">Ringkasan Pembayaran</h6>
                        </div>
                        <div class="card-body">
                            @php
                                $subtotal = (float) data_get($order,'subtotal', 0);
                                $discount = (float) data_get($order,'discount_total', 0);
                                $tax      = (float) data_get($order,'tax_total', 0);
                                $ship     = (float) (data_get($order,'shipping_total') ?? data_get($order,'shipping_cost', 0));
                                $grand    = (float) (data_get($order,'grand_total', $subtotal - $discount + $tax + $ship));
                            @endphp
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal</span>
                                <strong>Rp {{ number_format($subtotal, 0, ',', '.') }}</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Diskon</span>
                                <strong>- Rp {{ number_format($discount, 0, ',', '.') }}</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>PPN/Tax</span>
                                <strong>Rp {{ number_format($tax, 0, ',', '.') }}</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Ongkir</span>
                                <strong>Rp {{ number_format($ship, 0, ',', '.') }}</strong>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between fs-5">
                                <span>Total</span>
                                <strong>Rp {{ number_format($grand, 0, ',', '.') }}</strong>
                            </div>
                        </div>
                        <div class="card-footer bg-white d-grid gap-2">
                            <button type="button" class="btn btn-light" onclick="window.print()">Cetak</button>
                            <a href="{{ route('home') }}" class="btn btn-secondary">Kembali Belanja</a>
                        </div>
                    </div>

                    <div class="card shadow-sm mt-3">
                        <div class="card-header bg-white">
                            <h6 class="mb-0">Alamat Pengiriman</h6>
                        </div>
                        <div class="card-body">
                            @php
                                $shipSnap = $shipping
                                    ?? (is_array(data_get($order,'shipping_address_snapshot'))
                                            ? data_get($order,'shipping_address_snapshot')
                                            : json_decode(data_get($order,'shipping_address_snapshot','[]'), true));
                            @endphp
                            @if(!empty($shipSnap))
                                <div class="small">
                                    <div class="fw-semibold">{{ $shipSnap['recipient_name'] ?? '-' }}</div>
                                    <div>{{ $shipSnap['phone'] ?? '' }}</div>
                                    <div>{{ $shipSnap['address_line1'] ?? '' }}</div>
                                    <div>{{ $shipSnap['address_line2'] ?? '' }}</div>
                                    <div>{{ $shipSnap['postal_code'] ?? '' }}</div>
                                    <div class="text-muted">{{ $shipSnap['email'] ?? '' }}</div>
                                </div>
                            @else
                                <div class="text-muted small">Alamat tidak tersedia.</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div> {{-- /row --}}
        </div>
    </div>
</div>

@push('scripts')
<script>
function copyOrderNumber(no){
    if(!no) return;
    if(navigator.clipboard){
        navigator.clipboard.writeText(no).then(function(){
            toast('success','Nomor pesanan disalin');
        }).catch(()=>{});
    }
}
function toast(type,msg){
    if(window.showNotification) return window.showNotification(type,msg);
    if(window.toastr) return toastr[type||'info'](msg||'');
}
</script>
@endpush
@endsection
