{{-- resources/views/livewire/top-selling-products.blade.php --}}
<div class="double-row-product-slider mb-80">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center mb-40">
                <div class="section-title">
                    <h2><span>Koleksi</span> Terbaru</h2>
                    <p>Jelajahi koleksi produk baru kami, Anda pasti akan menemukan apa yang Anda cari.</p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <!--=======  top selling product slider container  =======-->

                <div class="ptk-slider double-row-slider-container" data-row="2" @if(!app()->runningUnitTests())
                wire:ignore @endif>
                    @forelse($items as $p)
                        <div class="col" wire:key="topselling-{{ $p['id'] }}">
                            @livewire('card-product-catalog', ['productId' => $p['id']], key('card-product-catalog-' . $p['id']))
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="alert alert-info mb-0">
                                Belum ada data penjualan atau produk tidak ditemukan.
                            </div>
                        </div>
                    @endforelse
                </div>

                <!--=======  End of top selling product slider container  =======-->
            </div>
        </div>
    </div>
</div>
