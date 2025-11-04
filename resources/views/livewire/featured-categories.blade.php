{{-- Featured Categories (dinamis, anti N+1, dengan fallback image) --}}
<div class="featured-categories mb-40">
    <div class="container">
        <div class="row">
            @forelse($categories as $cat)
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4" wire:key="cat-{{ $cat['id'] }}">
                    <a href="{{ $cat['url'] }}" class="d-block text-decoration-none">
                        <div class="single-featured-category position-relative overflow-hidden">
                            <div class="ratio ratio-4x3 bg-light rounded">
                                <img
                                    src="{{ $cat['image'] }}"
                                    alt="{{ $cat['name'] }}"
                                    class="w-100 h-100 object-fit-cover rounded">
                            </div>

                            <div class="pt-2">
                                <h6 class="mb-1 text-dark">{{ $cat['name'] }}</h6>
                                <div class="text-muted small">
                                    {{ number_format($cat['count'], 0, ',', '.') }} produk
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info mb-0">
                        Belum ada kategori aktif yang memiliki produk.
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>
