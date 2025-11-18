@extends('layouts.app')

@section('title', 'Profil Saya')

@push('styles')
<style>
    .myaccount-tab-menu a {
        display: block;
        padding: 15px 20px;
        background: #f8f8f8;
        border: 1px solid #ebebeb;
        border-bottom: none;
        font-weight: 500;
        color: #333;
        transition: all 0.3s ease;
        text-decoration: none;
    }
    .myaccount-tab-menu a:last-child {
        border-bottom: 1px solid #ebebeb;
    }
    .myaccount-tab-menu a:hover,
    .myaccount-tab-menu a.active {
        background: #333;
        color: #fff;
        border-color: #333;
    }
    .myaccount-tab-menu a i {
        margin-right: 10px;
        font-size: 16px;
    }
    .myaccount-content {
        padding: 30px;
        border: 1px solid #ebebeb;
        background: #fff;
    }
    .myaccount-content h3 {
        font-size: 24px;
        font-weight: 600;
        margin-bottom: 20px;
        border-bottom: 2px solid #333;
        padding-bottom: 10px;
    }
    .welcome {
        background: #f8f8f8;
        padding: 15px 20px;
        border-left: 3px solid #333;
    }
    .welcome strong {
        color: #333;
    }
    .logout {
        color: #e74c3c;
        text-decoration: underline;
    }
    .myaccount-table table thead {
        background: #f8f8f8;
    }
    .myaccount-table table th,
    .myaccount-table table td {
        padding: 15px;
        vertical-align: middle;
    }
    .account-details-form input,
    .account-details-form select {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #ebebeb;
        background: #f8f8f8;
        transition: all 0.3s ease;
    }
    .account-details-form input:focus,
    .account-details-form select:focus {
        border-color: #333;
        background: #fff;
        outline: none;
    }
    .save-change-btn {
        background: #333;
        color: #fff;
        border: none;
        padding: 12px 30px;
        font-weight: 500;
        text-transform: uppercase;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .save-change-btn:hover {
        background: #555;
    }
    .edit-address-btn {
        background: #333;
        color: #fff;
        padding: 10px 20px;
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-block;
    }
    .edit-address-btn:hover {
        background: #555;
        color: #fff;
    }
    .badge {
        padding: 5px 10px;
        border-radius: 3px;
        font-size: 12px;
        font-weight: 500;
    }
    .badge-warning { background: #f39c12; color: #fff; }
    .badge-info { background: #3498db; color: #fff; }
    .badge-success { background: #27ae60; color: #fff; }
    .badge-danger { background: #e74c3c; color: #fff; }
    .modal-header {
        background: #333;
        color: #fff;
    }
    .modal-header .btn-close {
        filter: invert(1);
    }
    .order-item {
        border-bottom: 1px solid #ebebeb;
        padding: 15px 0;
    }
    .order-item:last-child {
        border-bottom: none;
    }
    .address-actions {
        margin-top: 10px;
    }
    .address-actions button {
        margin-right: 5px;
    }
</style>
@endpush

@section('content')
@livewire('breadscrumb')

<div class="page-section mb-80">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <!-- Alert Messages -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fa fa-check-circle"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fa fa-exclamation-circle"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="row">
                    <!-- My Account Tab Menu Start -->
                    <div class="col-lg-3 col-12">
                        <div class="myaccount-tab-menu nav" role="tablist">
                            <a href="#dashboad" class="active" data-bs-toggle="tab">
                                <i class="fa fa-dashboard"></i> Dashboard
                            </a>
                            <a href="#orders" data-bs-toggle="tab">
                                <i class="fa fa-cart-arrow-down"></i> Orders
                            </a>
                            <a href="#address-edit" data-bs-toggle="tab">
                                <i class="fa fa-map-marker"></i> Address
                            </a>
                            <a href="#account-info" data-bs-toggle="tab">
                                <i class="fa fa-user"></i> Account Details
                            </a>
                            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fa fa-sign-out"></i> Logout
                            </a>
                        </div>
                    </div>
                    <!-- My Account Tab Menu End -->

                    <!-- My Account Tab Content Start -->
                    <div class="col-lg-9 col-12">
                        <div class="tab-content" id="myaccountContent">
                            <!-- Single Tab Content Start - Dashboard -->
                            <div class="tab-pane fade show active" id="dashboad" role="tabpanel">
                                <div class="myaccount-content">
                                    <h3>Dashboard</h3>

                                    <div class="welcome mb-20">
                                        <p>Hello, <strong>{{ $customer->name }}</strong> (If Not <strong>{{ $customer->name }} !</strong>
                                            <a href="#" class="logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                                Logout
                                            </a>)
                                        </p>
                                    </div>

                                    <p class="mb-0">From your account dashboard. you can easily check &amp; view your recent orders,
                                        manage your shipping and billing addresses and edit your password and account details.</p>

                                    <!-- Statistics -->
                                    <div class="row mt-4">
                                        <div class="col-md-4 mb-3">
                                            <div class="alert alert-info text-center">
                                                <h4 class="mb-0">{{ $stats['total_orders'] }}</h4>
                                                <small>Total Orders</small>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <div class="alert alert-warning text-center">
                                                <h4 class="mb-0">{{ $stats['pending_orders'] }}</h4>
                                                <small>Pending Orders</small>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <div class="alert alert-success text-center">
                                                <h4 class="mb-0">Rp {{ number_format($stats['total_spent'], 0, ',', '.') }}</h4>
                                                <small>Total Spent</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Single Tab Content End -->

                            <!-- Single Tab Content Start - Orders -->
                            <div class="tab-pane fade" id="orders" role="tabpanel">
                                <div class="myaccount-content">
                                    <h3>Orders</h3>

                                    <div class="myaccount-table table-responsive text-center">
                                        <table class="table table-bordered">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>Order</th>
                                                    <th>Date</th>
                                                    <th>Status</th>
                                                    <th>Total</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @forelse($orders as $order)
                                                <tr>
                                                    <td>#{{ $order->order_number }}</td>
                                                    <td>{{ $order->created_at->format('M d, Y') }}</td>
                                                    <td>
                                                        @php
                                                            $statusClass = match($order->status) {
                                                                'pending' => 'badge-warning',
                                                                'processing' => 'badge-info',
                                                                'completed' => 'badge-success',
                                                                'cancelled' => 'badge-danger',
                                                                default => 'badge-secondary'
                                                            };
                                                        @endphp
                                                        <span class="badge {{ $statusClass }}">{{ ucfirst($order->status) }}</span>
                                                    </td>
                                                    <td>Rp {{ number_format($order->grand_total, 0, ',', '.') }}</td>
                                                    <td>
                                                        <button type="button" class="btn btn-sm btn-dark" onclick="viewOrder({{ $order->id }})">
                                                            View
                                                        </button>
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="5">No orders found.</td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!-- Single Tab Content End -->

                            <!-- Single Tab Content Start - Address -->
                            <div class="tab-pane fade" id="address-edit" role="tabpanel">
                                <div class="myaccount-content">
                                    <h3>Billing Address</h3>

                                    @if($addresses->count() > 0)
                                        @foreach($addresses as $address)
                                        <div class="mb-4 p-3" style="border: 1px solid #ebebeb; background: #f8f8f8;">
                                            <address>
                                                <p><strong>{{ $address->recipient_name }}</strong></p>
                                                <p>{{ $address->address_line1 }}<br>
                                                @if($address->address_line2)
                                                    {{ $address->address_line2 }}<br>
                                                @endif
                                                {{ $address->city }}, {{ $address->province }} {{ $address->postal_code }}</p>
                                                <p>Mobile: {{ $address->phone }}</p>
                                            </address>

                                            <div class="address-actions">
                                                <button type="button" class="btn btn-sm edit-address-btn" onclick="editAddress({{ $address->id }})">
                                                    <i class="fa fa-edit"></i> Edit Address
                                                </button>
                                                <button type="button" class="btn btn-sm btn-danger" onclick="deleteAddress({{ $address->id }})">
                                                    <i class="fa fa-trash"></i> Delete
                                                </button>
                                            </div>
                                        </div>
                                        @endforeach

                                        <button type="button" class="btn btn-dark mt-3" onclick="addAddress()">
                                            <i class="fa fa-plus"></i> Add New Address
                                        </button>
                                    @else
                                        <div class="alert alert-info">
                                            <p class="mb-0">You haven't set up any addresses yet. Add your first address to make checkout faster!</p>
                                        </div>
                                        <button type="button" class="btn btn-dark" onclick="addAddress()">
                                            <i class="fa fa-plus"></i> Add New Address
                                        </button>
                                    @endif
                                </div>
                            </div>
                            <!-- Single Tab Content End -->

                            <!-- Single Tab Content Start - Account Info -->
                            <div class="tab-pane fade" id="account-info" role="tabpanel">
                                <div class="myaccount-content">
                                    <h3>Account Details</h3>

                                    <div class="account-details-form">
                                        <form action="{{ route('auth.update', $customer->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="row">
                                                <div class="col-lg-6 col-12 mb-30">
                                                    <input id="name" name="name" placeholder="Full Name" type="text"
                                                           value="{{ old('name', $customer->name) }}" required>
                                                    @error('name')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>

                                                <div class="col-lg-6 col-12 mb-30">
                                                    <input id="email" name="email" placeholder="Email Address" type="email"
                                                           value="{{ old('email', $customer->email) }}" required>
                                                    @error('email')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>

                                                <div class="col-lg-6 col-12 mb-30">
                                                    <input id="phone" name="phone" placeholder="Phone Number" type="text"
                                                           value="{{ old('phone', $customer->phone) }}">
                                                    @error('phone')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>

                                                <div class="col-lg-6 col-12 mb-30">
                                                    <input id="birth_date" name="birth_date" placeholder="Birth Date" type="date"
                                                           value="{{ old('birth_date', $customer->birth_date) }}">
                                                    @error('birth_date')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>

                                                <div class="col-12 mb-30">
                                                    <select id="gender" name="gender">
                                                        <option value="">Select Gender</option>
                                                        <option value="male" {{ old('gender', $customer->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                                        <option value="female" {{ old('gender', $customer->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                                    </select>
                                                    @error('gender')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>

                                                <div class="col-12 mb-30">
                                                    <h4>Password change</h4>
                                                </div>

                                                <div class="col-12 mb-30">
                                                    <input id="current-pwd" name="current_password" placeholder="Current Password" type="password">
                                                    @error('current_password')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>

                                                <div class="col-lg-6 col-12 mb-30">
                                                    <input id="new-pwd" name="password" placeholder="New Password" type="password">
                                                    @error('password')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>

                                                <div class="col-lg-6 col-12 mb-30">
                                                    <input id="confirm-pwd" name="password_confirmation" placeholder="Confirm Password" type="password">
                                                </div>

                                                <div class="col-12">
                                                    <button class="save-change-btn">Save Changes</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- Single Tab Content End -->
                        </div>
                    </div>
                    <!-- My Account Tab Content End -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Hidden Logout Form -->
<form id="logout-form" action="{{ route('auth.logout') }}" method="POST" style="display: none;">
    @csrf
</form>

<!-- Order Detail Modal -->
<div class="modal fade" id="orderDetailModal" tabindex="-1" aria-labelledby="orderDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="orderDetailModalLabel">Order Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="orderDetailContent">
                <div class="text-center py-5">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Address Modal -->
<div class="modal fade" id="addressModal" tabindex="-1" aria-labelledby="addressModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addressModalLabel">Add Address</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addressForm">
                <div class="modal-body">
                    <input type="hidden" id="address_id" name="address_id">

                    <div class="mb-3">
                        <label for="recipient_name" class="form-label">Recipient Name *</label>
                        <input type="text" class="form-control" id="recipient_name" name="recipient_name" required>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone Number *</label>
                        <input type="text" class="form-control" id="address_phone" name="phone" required>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="mb-3">
                        <label for="address_line1" class="form-label">Address Line 1 *</label>
                        <textarea class="form-control" id="address_line1" name="address_line1" rows="2" required></textarea>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="mb-3">
                        <label for="address_line2" class="form-label">Address Line 2</label>
                        <textarea class="form-control" id="address_line2" name="address_line2" rows="2"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="city" class="form-label">City *</label>
                            <input type="text" class="form-control" id="city" name="city" required>
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="province" class="form-label">Province *</label>
                            <input type="text" class="form-control" id="province" name="province" required>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="postal_code" class="form-label">Postal Code *</label>
                        <input type="text" class="form-control" id="postal_code" name="postal_code" required>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="is_default" name="is_default" value="1">
                        <label class="form-check-label" for="is_default">
                            Set as default address
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-dark">Save Address</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
<script>
// View Order Detail
function viewOrder(orderId) {
    const modal = new bootstrap.Modal(document.getElementById('orderDetailModal'));
    modal.show();

    fetch(`/auth/orders/${orderId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayOrderDetail(data.order);
            } else {
                document.getElementById('orderDetailContent').innerHTML =
                    '<div class="alert alert-danger">Failed to load order details.</div>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('orderDetailContent').innerHTML =
                '<div class="alert alert-danger">An error occurred while loading order details.</div>';
        });
}

function displayOrderDetail(order) {
    let statusClass = '';
    switch(order.status) {
        case 'pending': statusClass = 'badge-warning'; break;
        case 'processing': statusClass = 'badge-info'; break;
        case 'completed': statusClass = 'badge-success'; break;
        case 'cancelled': statusClass = 'badge-danger'; break;
        default: statusClass = 'badge-secondary';
    }

    let itemsHtml = '';
    order.items.forEach(item => {
        itemsHtml += `
            <div class="order-item">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <strong>${item.name}</strong>
                        ${item.variant_name ? `<br><small class="text-muted">${item.variant_name}</small>` : ''}
                    </div>
                    <div class="col-md-2 text-center">${item.quantity}x</div>
                    <div class="col-md-4 text-end">
                        <strong>Rp ${parseInt(item.price).toLocaleString('id-ID')}</strong>
                    </div>
                </div>
            </div>
        `;
    });

    let paymentButton = '';
    if (order.payment && order.payment.snap_token && order.status === 'pending') {
        paymentButton = `
            <div class="alert alert-warning mt-3">
                <strong>Payment Required</strong>
                <p class="mb-2">This order is awaiting payment. Click the button below to complete your payment.</p>
                <button type="button" class="btn btn-primary" onclick="payOrder('${order.payment.snap_token}')">
                    <i class="fa fa-credit-card"></i> Pay Now
                </button>
            </div>
        `;
    }

    const html = `
        <div class="order-detail">
            <div class="row mb-3">
                <div class="col-md-6">
                    <h6>Order #${order.order_number}</h6>
                    <small class="text-muted">${new Date(order.created_at).toLocaleDateString('en-US', {
                        year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit'
                    })}</small>
                </div>
                <div class="col-md-6 text-end">
                    <span class="badge ${statusClass}">${order.status.toUpperCase()}</span>
                </div>
            </div>

            <hr>

            <h6 class="mb-3">Order Items</h6>
            ${itemsHtml}

            <hr>

            <div class="row">
                <div class="col-md-6 offset-md-6">
                    <table class="table table-sm">
                        <tr>
                            <td><strong>Subtotal:</strong></td>
                            <td class="text-end">Rp ${parseFloat(order.subtotal).toLocaleString('id-ID', {minimumFractionDigits: 0, maximumFractionDigits: 0})}</td>
                        </tr>
                        <tr>
                            <td><strong>Shipping:</strong></td>
                            <td class="text-end">Rp ${parseFloat(order.shipping_cost || order.shipping_total || 0).toLocaleString('id-ID', {minimumFractionDigits: 0, maximumFractionDigits: 0})}</td>
                        </tr>
                        ${order.discount_total > 0 ? `
                        <tr>
                            <td><strong>Discount:</strong></td>
                            <td class="text-end text-danger">-Rp ${parseFloat(order.discount_total).toLocaleString('id-ID', {minimumFractionDigits: 0, maximumFractionDigits: 0})}</td>
                        </tr>
                        ` : ''}
                        ${order.tax_total > 0 ? `
                        <tr>
                            <td><strong>Tax:</strong></td>
                            <td class="text-end">Rp ${parseFloat(order.tax_total).toLocaleString('id-ID', {minimumFractionDigits: 0, maximumFractionDigits: 0})}</td>
                        </tr>
                        ` : ''}
                        <tr class="table-active">
                            <td><strong>Grand Total:</strong></td>
                            <td class="text-end"><strong>Rp ${parseFloat(order.grand_total).toLocaleString('id-ID', {minimumFractionDigits: 0, maximumFractionDigits: 0})}</strong></td>
                        </tr>
                    </table>
                </div>
            </div>

            <hr>

            <div class="row">
                <div class="col-md-6">
                    <h6>Shipping Address</h6>
                    <address>
                        <strong>${order.customer_name}</strong><br>
                        ${order.shipping_address_snapshot ? order.shipping_address_snapshot : 'Address not available'}<br>
                        ${order.customer_phone ? 'Phone: ' + order.customer_phone : ''}
                    </address>
                </div>
                <div class="col-md-6">
                    <h6>Payment Information</h6>
                    ${order.payment ? `
                        <p class="mb-1"><strong>Payment Method:</strong> ${order.payment.payment_method || 'N/A'}</p>
                        <p class="mb-1"><strong>Payment Status:</strong> <span class="badge ${order.payment.status === 'paid' ? 'badge-success' : 'badge-warning'}">${order.payment.status}</span></p>
                    ` : '<p class="text-muted">No payment information available</p>'}
                </div>
            </div>

            ${paymentButton}
        </div>
    `;

    document.getElementById('orderDetailContent').innerHTML = html;
}

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

function payOrder(snapToken) {
    window.snap.pay(snapToken, {
        onSuccess: function(result) {
            toast('success', 'Payment successful!');
            location.reload();
        },
        onPending: function(result) {
            toast('warning', 'Payment pending. Please complete your payment.');
            location.reload();
        },
        onError: function(result) {
            toast('error', 'Payment failed. Please try again.');
        },
        onClose: function() {
            console.log('Payment popup closed');
        }
    });
}

// Address Management
function addAddress() {
    document.getElementById('addressModalLabel').textContent = 'Add New Address';
    document.getElementById('addressForm').reset();
    document.getElementById('address_id').value = '';
    const modal = new bootstrap.Modal(document.getElementById('addressModal'));
    modal.show();
}

function editAddress(addressId) {
    document.getElementById('addressModalLabel').textContent = 'Edit Address';

    fetch(`/auth/addresses/${addressId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const address = data.address;
                document.getElementById('address_id').value = address.id;
                document.getElementById('recipient_name').value = address.recipient_name;
                document.getElementById('address_phone').value = address.phone;
                document.getElementById('address_line1').value = address.address_line1;
                document.getElementById('address_line2').value = address.address_line2 || '';
                document.getElementById('city').value = address.city;
                document.getElementById('province').value = address.province;
                document.getElementById('postal_code').value = address.postal_code;
                document.getElementById('is_default').checked = address.is_default == 1;

                const modal = new bootstrap.Modal(document.getElementById('addressModal'));
                modal.show();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            toast('error', 'Failed to load address details.');
        });
}

function deleteAddress(addressId) {
    if (!confirm('Are you sure you want to delete this address?')) {
        return;
    }

    fetch(`/auth/addresses/${addressId}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            toast('success', 'Address deleted successfully!');
            location.reload();
        } else {
            toast('error', 'Failed to delete address: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        toast('error', 'An error occurred while deleting the address.');
    });
}

// Address Form Submit
document.getElementById('addressForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const addressId = document.getElementById('address_id').value;
    const url = addressId ? `/auth/addresses/${addressId}` : '/auth/addresses';
    const method = addressId ? 'PUT' : 'POST';

    const formData = new FormData(this);
    const data = Object.fromEntries(formData.entries());

    // Clear previous errors
    document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

    fetch(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            toast('success', data.message);
            location.reload();
        } else {
            if (data.errors) {
                // Display validation errors
                for (const [field, messages] of Object.entries(data.errors)) {
                    const input = document.getElementById(field) || document.getElementById('address_' + field);
                    if (input) {
                        input.classList.add('is-invalid');
                        const feedback = input.nextElementSibling;
                        if (feedback && feedback.classList.contains('invalid-feedback')) {
                            feedback.textContent = messages[0];
                        }
                    }
                }
            } else {
                toast('error', 'Failed to save address: ' + (data.message || 'Unknown error'));
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        toast('error', 'An error occurred while saving the address.');
    });
});
</script>
@endpush
@endsection
