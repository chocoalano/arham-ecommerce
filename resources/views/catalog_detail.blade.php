@extends('layouts.app')
@section('content')
    @livewire('breadscrumb')
    <div class="single-product-page-content mb-80">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 mb-md-50 mb-sm-50">
                    <!-- single product tabstyle one image gallery -->
                    <div class="product-image-slider pts1-product-image-slider pts-product-image-slider pts1-product-image-slider flex-row-reverse">
                        <!--product large image start -->
                        <div class="tab-content product-large-image-list pts-product-large-image-list pts1-product-large-image-list" id="myTabContent">
                            @forelse($product->images as $index => $image)
                                <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}" id="single-slide-{{ $index + 1 }}" role="tabpanel" aria-labelledby="single-slide-tab-{{ $index + 1 }}">
                                    <!--Single Product Image Start-->
                                    <div class="single-product-img img-full">
                                        <img width="540" height="560" src="{{ asset('storage/' . ($image->path_ratio_27_28 ?? $image->path)) }}" class="img-fluid" alt="{{ $image->alt_text ?? $product->name }}" loading="lazy">
                                        <a href="{{ asset('storage/' . $image->path) }}" class="big-image-popup"><i class="fa fa-search-plus"></i></a>
                                    </div>
                                    <!--Single Product Image End-->
                                </div>
                            @empty
                                <div class="tab-pane fade show active" id="single-slide-1" role="tabpanel" aria-labelledby="single-slide-tab-1">
                                    <!--Single Product Image Start-->
                                    <div class="single-product-img img-full">
                                        <img width="600" height="719" src="{{ asset('images/placeholder.jpg') }}" class="img-fluid" alt="{{ $product->name }}">
                                    </div>
                                    <!--Single Product Image End-->
                                </div>
                            @endforelse
                        </div>
                        <!--product large image End-->

                        <!--product small image slider Start-->
                        @if($product->images->isNotEmpty())
                            <div class="product-small-image-list pts-product-small-image-list pts1-product-small-image-list">
                                <div class="nav small-image-slider pts-small-image-slider pts1-small-image-slider" role="tablist">
                                    @foreach($product->images as $index => $image)
                                        <div class="single-small-image img-full">
                                            <a data-bs-toggle="tab" id="single-slide-tab-{{ $index + 1 }}" href="#single-slide-{{ $index + 1 }}">
                                                <img width="255" height="260" src="{{ asset('storage/' . ($image->path_ratio_51_52 ?? $image->path)) }}" class="img-fluid" alt="{{ $image->alt_text ?? $product->name }}" loading="lazy">
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        <!--product small image slider End-->
                    </div>
                    <!-- end of single product tabstyle one image gallery -->
                </div>
                <div class="col-lg-6">
                    <!--=======  single product details  =======-->

                    <div class="single-product-details-container">

                        <p class="product-title mb-15">{{ $product->name }}</p>

                        <div class="d-flex align-items-center gap-3 mb-15">
                            <p class="reference-text mb-0">SKU: {{ $product->sku }}</p>
                            @if($product->brand)
                                <span class="text-muted">|</span>
                                <p class="mb-0"><strong>Brand:</strong> {{ $product->brand->name }}</p>
                            @endif
                        </div>

                        @if($product->reviews_count > 0)
                            <div class="rating d-inline-block mb-15">
                                @php
                                    $rating = $product->reviews_avg_rating ?? 0;
                                    $fullStars = floor($rating);
                                    $emptyStars = 5 - $fullStars;
                                @endphp
                                @for($i = 0; $i < $fullStars; $i++)
                                    <i class="lnr lnr-star active"></i>
                                @endfor
                                @for($i = 0; $i < $emptyStars; $i++)
                                    <i class="lnr lnr-star"></i>
                                @endfor
                                <span class="ms-2">({{ number_format($rating, 1) }})</span>
                            </div>
                        @endif

                        <p class="review-links d-inline-block {{ $product->reviews_count > 0 ? 'ms-3' : 'mb-15' }}">
                            <a href="#review"><i class="fa fa-comment-o"></i> Read reviews ({{ $product->reviews_count ?? 0 }}) </a>
                            <a href="#review"><i class="fa fa-pencil"></i> Write a review</a>
                        </p>

                        <p class="product-price mb-30">
                            @if($product->sale_price && $product->sale_price < $product->price)
                                <span class="main-price discounted">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                <span class="discounted-price">Rp {{ number_format($product->sale_price, 0, ',', '.') }}</span>
                                @php
                                    $discount = (($product->price - $product->sale_price) / $product->price) * 100;
                                @endphp
                                <span class="badge bg-danger ms-2">-{{ round($discount) }}%</span>
                            @else
                                <span class="main-price">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                            @endif
                        </p>

                        @if($product->short_description)
                            <p class="product-description mb-15">
                                {{ $product->short_description }}
                            </p>
                        @endif

                        {{-- Stock Availability --}}
                        <div class="mb-15">
                            @if($product->stock > 0)
                                <span class="badge bg-success">
                                    <i class="fa fa-check"></i> In Stock ({{ $product->stock }} units)
                                </span>
                            @else
                                <span class="badge bg-danger">
                                    <i class="fa fa-times"></i> Out of Stock
                                </span>
                            @endif
                        </div>

                        {{-- Product Categories --}}
                        @if($product->categories->isNotEmpty())
                            <div class="mb-30">
                                <strong>Categories:</strong>
                                @foreach($product->categories as $category)
                                    <a href="{{ route('catalog.index', ['category' => $category->slug]) }}" class="badge bg-secondary text-decoration-none">
                                        {{ $category->name }}
                                    </a>
                                @endforeach
                            </div>
                        @endif

                        {{-- Product Variants --}}
                        @if($product->variants->isNotEmpty())
                            <div class="mb-30">
                                <p class="mb-15"><strong>Select Variant:</strong></p>
                                <select id="variant-selector" class="form-select mb-3" style="max-width: 300px;">
                                    <option value="">-- Select a variant --</option>
                                    @foreach($product->variants as $variant)
                                        <option value="{{ $variant->id }}"
                                                data-price="{{ $variant->sale_price ?? $variant->price }}"
                                                data-active="{{ $variant->is_active ? '1' : '0' }}">
                                            {{ $variant->name }}
                                            @if($variant->options)
                                                (
                                                @foreach($variant->options as $key => $value)
                                                    {{ ucfirst($key) }}: {{ $value }}{{ !$loop->last ? ', ' : '' }}
                                                @endforeach
                                                )
                                            @endif
                                            - Rp {{ number_format($variant->sale_price ?? $variant->price, 0, ',', '.') }}
                                            @if(!$variant->is_active)
                                                (Inactive)
                                            @endif
                                        </option>
                                    @endforeach
                                </select>

                                <div class="d-flex flex-wrap gap-2">
                                    @foreach($product->variants as $variant)
                                        <div class="border rounded p-2 {{ !$variant->is_active ? 'opacity-50' : '' }}" style="min-width: 150px;">
                                            <div class="fw-bold">{{ $variant->name }}</div>
                                            @if($variant->options)
                                                <small class="text-muted">
                                                    @foreach($variant->options as $key => $value)
                                                        {{ ucfirst($key) }}: {{ $value }}{{ !$loop->last ? ', ' : '' }}
                                                    @endforeach
                                                </small>
                                            @endif
                                            <div class="text-primary">
                                                @if($variant->sale_price && $variant->sale_price < $variant->price)
                                                    <span class="text-decoration-line-through small">Rp {{ number_format($variant->price, 0, ',', '.') }}</span>
                                                    <span class="fw-bold">Rp {{ number_format($variant->sale_price, 0, ',', '.') }}</span>
                                                @else
                                                    <span class="fw-bold">Rp {{ number_format($variant->price, 0, ',', '.') }}</span>
                                                @endif
                                            </div>
                                            @if(!$variant->is_active)
                                                <small class="badge bg-secondary">Inactive</small>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="cart-buttons mb-30">
                            <p class="mb-15">Quantity</p>
                            <div class="pro-qty mr-10">
                                <input type="text" id="product-quantity" value="1" min="1" max="{{ $product->stock }}">
                                <a href="#" class="inc qty-btn"><i class="fa fa-angle-up"></i></a>
                                <a href="#" class="dec qty-btn"><i class="fa fa-angle-down"></i></a>
                            </div>
                            <button type="button"
                                    id="add-to-cart-btn"
                                    class="pataku-btn"
                                    data-product-id="{{ $product->id }}"
                                    {{ $product->stock <= 0 ? 'disabled' : '' }}>
                                <i class="fa fa-shopping-cart"></i>
                                {{ $product->stock > 0 ? 'Add to Cart' : 'Out of Stock' }}
                            </button>
                        </div>
                        <p class="wishlist-link mb-30">
                            <a href="#"
                               id="add-to-wishlist-btn"
                               data-product-id="{{ $product->id }}">
                                <i class="fa fa-heart"></i> Add to wishlist
                            </a>
                        </p>
                        <div class="social-share-buttons mb-30">
                            <p>Share</p>
                            <ul>
                                <li><a class="twitter" href="#"><i class="fa fa-twitter"></i></a></li>
                                <li><a class="facebook" href="#"><i class="fa fa-facebook"></i></a></li>
                                <li><a class="google-plus" href="#"><i class="fa fa-google-plus"></i></a></li>
                                <li><a class="pinterest" href="#"><i class="fa fa-pinterest"></i></a></li>
                            </ul>
                        </div>
                    </div>

                    <!--=======  End of single product details  =======-->
                </div>
            </div>
        </div>
    </div>
    <div class="single-product-tab-section mb-80">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="tab-slider-wrapper">
                        <nav>
                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                <a class="nav-item nav-link active" id="description-tab" data-bs-toggle="tab" href="#description" role="tab" aria-selected="true">Description</a>
                                <a class="nav-item nav-link" id="features-tab" data-bs-toggle="tab" href="#features" role="tab" aria-selected="false" tabindex="-1">Features</a>
                            </div>
                        </nav>
                        <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="description" role="tabpanel" aria-labelledby="description-tab">
                                <p class="product-desc">{!! $product->description !!}</p>
                            </div>
                            <div class="tab-pane fade" id="features" role="tabpanel" aria-labelledby="features-tab">
                                <table class="table-data-sheet">
                                    <tbody>
                                        <tr class="odd">
                                            <td>Product Name</td>
                                            <td>{{ $product->name }}</td>
                                        </tr>
                                        <tr class="even">
                                            <td>SKU</td>
                                            <td>{{ $product->sku }}</td>
                                        </tr>
                                        @if($product->brand)
                                            <tr class="odd">
                                                <td>Brand</td>
                                                <td>{{ $product->brand->name }}</td>
                                            </tr>
                                        @endif
                                        <tr class="even">
                                            <td>Weight</td>
                                            <td>{{ $product->weight_gram }} gram</td>
                                        </tr>
                                        @if($product->length_mm || $product->width_mm || $product->height_mm)
                                            <tr class="odd">
                                                <td>Dimensions</td>
                                                <td>
                                                    @if($product->length_mm)L: {{ $product->length_mm }}mm @endif
                                                    @if($product->width_mm)W: {{ $product->width_mm }}mm @endif
                                                    @if($product->height_mm)H: {{ $product->height_mm }}mm @endif
                                                </td>
                                            </tr>
                                        @endif
                                        <tr class="even">
                                            <td>Stock</td>
                                            <td>{{ $product->stock }} units</td>
                                        </tr>
                                        <tr class="odd">
                                            <td>Status</td>
                                            <td>
                                                <span class="badge {{ $product->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                                                    {{ ucfirst($product->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                        @if($product->attributes && is_array($product->attributes))
                                            @foreach($product->attributes as $key => $value)
                                                <tr class="{{ $loop->even ? 'even' : 'odd' }}">
                                                    <td>{{ ucfirst(str_replace('_', ' ', $key)) }}</td>
                                                    <td>{{ is_array($value) ? implode(', ', $value) : $value }}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const productId = {{ $product->id }};
    const csrfToken = '{{ csrf_token() }}';
    const hasVariants = {{ $product->variants->isNotEmpty() ? 'true' : 'false' }};

    // Toast notification helper
    function toast(type, msg) {
        if (typeof window.showNotification === 'function') {
            window.showNotification(type, msg);
        } else if (window.toastr) {
            window.toastr[type || 'info'](msg || '');
        } else {
            alert(`[${type.toUpperCase()}] ${msg}`);
        }
    }

    // Quantity controls
    const quantityInput = document.getElementById('product-quantity');
    let maxStock = {{ $product->stock }};

    // Variant selector
    const variantSelector = document.getElementById('variant-selector');
    let selectedVariantId = null;

    if (variantSelector) {
        variantSelector.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            selectedVariantId = this.value ? parseInt(this.value) : null;

            if (selectedVariantId) {
                // Check if variant is active
                const isActive = selectedOption.dataset.active === '1';

                // Update add to cart button state based on variant active status and product stock
                const addToCartBtn = document.getElementById('add-to-cart-btn');
                if (!isActive || maxStock <= 0) {
                    addToCartBtn.disabled = true;
                    addToCartBtn.innerHTML = '<i class="fa fa-shopping-cart"></i> ' + (!isActive ? 'Variant Inactive' : 'Out of Stock');
                } else {
                    addToCartBtn.disabled = false;
                    addToCartBtn.innerHTML = '<i class="fa fa-shopping-cart"></i> Add to Cart';
                }
            }
        });
    }

    document.querySelectorAll('.qty-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            let currentVal = parseInt(quantityInput.value) || 1;

            if (this.classList.contains('inc')) {
                if (currentVal < maxStock) {
                    quantityInput.value = currentVal + 1;
                }
            } else {
                if (currentVal > 1) {
                    quantityInput.value = currentVal - 1;
                }
            }
        });
    });

    // Add to Cart
    const addToCartBtn = document.getElementById('add-to-cart-btn');
    if (addToCartBtn) {
        addToCartBtn.addEventListener('click', function(e) {
            e.preventDefault();

            if (this.disabled) return;

            // Check if variant is required but not selected
            if (hasVariants && !selectedVariantId) {
                toast('warning', 'Please select a product variant first.');
                return;
            }

            const quantity = parseInt(quantityInput.value) || 1;
            const originalText = this.innerHTML;

            // Disable button and show loading
            this.disabled = true;
            this.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Adding...';

            // Prepare request
            const requestData = {
                product_id: productId,
                quantity: quantity
            };

            // Add variant_id if selected
            if (selectedVariantId) {
                requestData.variant_id = selectedVariantId;
            }

            // Make AJAX request
            fetch('{{ route("cart.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(requestData)
            })
            .then(response => {
                if (response.status === 401) {
                    window.location.href = '{{ route("login-register.index") }}';
                    return;
                }
                return response.json();
            })
            .then(data => {
                if (data && data.success) {
                    // Show success message
                    toast('success', data.message || 'Product added to cart successfully!');

                    // Dispatch event to update cart count
                    window.dispatchEvent(new CustomEvent('cartUpdated'));

                    // Also try Livewire events
                    if (window.Livewire) {
                        if (typeof window.Livewire.dispatch === 'function') {
                            window.Livewire.dispatch('cartUpdated');
                        } else if (typeof window.Livewire.emit === 'function') {
                            window.Livewire.emit('cartUpdated');
                        }
                    }

                    // Reset quantity to 1
                    quantityInput.value = 1;
                } else if (data && data.requires_variant) {
                    toast('warning', 'Please select a product variant first.');
                } else {
                    toast('error', data.message || 'Failed to add product to cart.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                toast('error', 'An error occurred. Please try again.');
            })
            .finally(() => {
                // Re-enable button
                addToCartBtn.disabled = false;
                addToCartBtn.innerHTML = originalText;
            });
        });
    }

    // Add to Wishlist
    const addToWishlistBtn = document.getElementById('add-to-wishlist-btn');
    if (addToWishlistBtn) {
        addToWishlistBtn.addEventListener('click', function(e) {
            e.preventDefault();

            const icon = this.querySelector('i');
            const originalIcon = icon.className;

            // Show loading
            icon.className = 'fa fa-spinner fa-spin';

            // Prepare request
            const requestData = {
                product_id: productId
            };

            // Add variant_id if selected
            if (selectedVariantId) {
                requestData.variant_id = selectedVariantId;
            }

            // Make AJAX request
            fetch('{{ route("wishlist.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(requestData)
            })
            .then(response => {
                if (response.status === 401) {
                    window.location.href = '{{ route("login-register.index") }}';
                    return;
                }
                return response.json();
            })
            .then(data => {
                if (data && data.success) {
                    // Toggle icon based on action
                    if (data.action === 'added' || data.in_wishlist) {
                        icon.className = 'fa fa-heart';
                        icon.style.color = '#e74c3c';
                        this.innerHTML = '<i class="fa fa-heart" style="color: #e74c3c;"></i> Remove from wishlist';
                    } else {
                        icon.className = 'fa fa-heart-o';
                        icon.style.color = '';
                        this.innerHTML = '<i class="fa fa-heart"></i> Add to wishlist';
                    }

                    // Show message
                    toast('success', data.message || 'Wishlist updated!');

                    // Dispatch event to update wishlist count
                    window.dispatchEvent(new CustomEvent('wishlistUpdated'));

                    // Also try Livewire events
                    if (window.Livewire) {
                        if (typeof window.Livewire.dispatch === 'function') {
                            window.Livewire.dispatch('wishlistUpdated');
                        } else if (typeof window.Livewire.emit === 'function') {
                            window.Livewire.emit('wishlistUpdated');
                        }
                    }
                } else {
                    toast('error', data.message || 'Failed to update wishlist.');
                    icon.className = originalIcon;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                toast('error', 'An error occurred. Please try again.');
                icon.className = originalIcon;
            });
        });
    }
});
</script>
@endpush
