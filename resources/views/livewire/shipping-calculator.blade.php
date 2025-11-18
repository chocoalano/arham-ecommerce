<div>
    {{-- Flash Messages --}}
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session()->has('warning'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            {{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        {{-- Recipient Information --}}
        <div class="col-md-6">
            <div class="form-group mb-3">
                <label for="recipientName">Recipient Name <span class="text-danger">*</span></label>
                <input type="text" wire:model="recipientName" id="recipientName"
                    class="form-control @error('recipientName') is-invalid @enderror" placeholder="Recipient Name">
                @error('recipientName')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group mb-3">
                <label for="email">Email <span class="text-danger">*</span></label>
                <input type="email" wire:model="email" id="email"
                    class="form-control @error('email') is-invalid @enderror" placeholder="Email">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group mb-3">
                <label for="phone">Phone <span class="text-danger">*</span></label>
                <input type="text" wire:model="phone" id="phone"
                    class="form-control @error('phone') is-invalid @enderror" placeholder="Phone">
                @error('phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group mb-3">
                <label for="postalCode">Postal Code <span class="text-danger">*</span></label>
                <input type="text" wire:model="postalCode" id="postalCode"
                    class="form-control @error('postalCode') is-invalid @enderror" placeholder="Postal Code">
                @error('postalCode')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Address --}}
        <div class="col-12">
            <div class="form-group mb-3">
                <label for="addressLine1">Address Line 1 <span class="text-danger">*</span></label>
                <input type="text" wire:model="addressLine1" id="addressLine1"
                    class="form-control @error('addressLine1') is-invalid @enderror" placeholder="Address Line 1">
                @error('addressLine1')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="col-12">
            <div class="form-group mb-3">
                <label for="addressLine2">Address Line 2 (Optional)</label>
                <input type="text" wire:model="addressLine2" id="addressLine2" class="form-control"
                    placeholder="Address Line 2">
            </div>
        </div>

        {{-- Province & City --}}
        <div class="col-md-6">
            <div class="form-group mb-3">
                <label for="provinceId">Province <span class="text-danger">*</span></label>
                <select wire:model.live="provinceId" id="provinceId"
                    class="form-control @error('provinceId') is-invalid @enderror">
                    <option value="">Select Province</option>
                    @foreach ($provinces as $province)
                        <option value="{{ $province['id'] }}">{{ $province['name'] }}</option>
                    @endforeach
                </select>
                @error('provinceId')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group mb-3">
                <label for="cityId">City <span class="text-danger">*</span></label>
                <select wire:model="cityId" id="cityId" class="form-control @error('cityId') is-invalid @enderror"
                    @if(empty($cities)) disabled @endif>
                    <option value="">Select City</option>
                    @foreach ($cities as $city)
                        <option value="{{ $city['id'] }}">{{ $city['name'] }}</option>
                    @endforeach
                </select>
                @error('cityId')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Calculate Button --}}
        <div class="col-12">
            <button type="button" wire:click="calculateShipping" class="btn btn-primary" wire:loading.attr="disabled"
                wire:target="calculateShipping">
                <span wire:loading.remove wire:target="calculateShipping">Calculate Shipping Cost</span>
                <span wire:loading wire:target="calculateShipping">
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    Calculating...
                </span>
            </button>
        </div>

        {{-- Shipping Options --}}
        @if (!empty($quotes))
            <div class="col-12 mt-4">
                <h5 class="mb-3">Select Shipping Method</h5>

                <div class="shipping-options">
                    @foreach ($quotes as $i => $quote)
                        @php
                            $isActive = ($selectedShipping === ($quote['code'] ?? ''));
                            $id = 'shipping_' . $i; // id aman untuk HTML
                        @endphp

                        {{-- radio disembunyikan ala Bootstrap 5 --}}
                        <input
                            type="radio"
                            class="btn-check"
                            name="selectedShipping"
                            id="{{ $id }}"
                            wire:model.live="selectedShipping"
                            value="{{ $quote['code'] ?? '' }}"
                            autocomplete="off"
                            {{ $isActive ? 'checked' : '' }}
                        >

                        {{-- label as card (full clickable) --}}
                        <label for="{{ $id }}" class="card mb-3 {{ $isActive ? 'border-primary shadow-sm' : 'border' }}">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-start justify-content-between gap-3">
                                    <div class="flex-grow-1">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="fw-semibold">{{ $quote['label'] ?? '-' }}</span>
                                            @if($isActive)
                                                <i class="fa fa-check-circle text-primary" aria-hidden="true"></i>
                                                <span class="visually-hidden">Dipilih</span>
                                            @endif
                                        </div>

                                        <div class="text-muted small mt-1">
                                            <i class="fa fa-truck me-1" aria-hidden="true"></i>
                                            {{ $quote['carrier'] ?? '-' }}
                                            <span class="mx-1">—</span>
                                            {{ $quote['service'] ?? '-' }}
                                        </div>

                                        <div class="text-muted small">
                                            <i class="fa fa-clock-o me-1" aria-hidden="true"></i>
                                            Estimasi: {{ $quote['etd_days'] ?? 'N/A' }} hari
                                        </div>
                                    </div>

                                    <div class="text-end">
                                        <span class="badge bg-primary fs-6 px-3 py-2">
                                            Rp {{ number_format($quote['cost'] ?? 0, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </label>
                    @endforeach

                </div>
            </div>
        @endif

    </div>
</div>
