{{-- resources/views/livewire/top-selling-products.blade.php --}}
<div class="container">
    <div class="ptk-slider double-row-slider-container" data-row="2" @if(!app()->runningUnitTests()) wire:ignore @endif>

        @forelse($items as $p)
            <div class="col" wire:key="topselling-{{ $p['id'] }}">
                @livewire('card-product-catalog', ['productId' => $p['id']], key('card-product-catalog-'.$p['id']))
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info mb-0">
                    Belum ada data penjualan atau produk tidak ditemukan.
                </div>
            </div>
        @endforelse

    </div>
</div>
