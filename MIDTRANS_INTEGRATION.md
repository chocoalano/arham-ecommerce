# Midtrans Payment Integration Guide

## Overview
Integrasi Midtrans Snap telah ditambahkan ke sistem checkout untuk mendukung pembayaran online (kartu kredit, transfer bank, e-wallet, dll).

## Setup

### 1. Environment Variables
Tambahkan konfigurasi Midtrans ke file `.env`:

```bash
MIDTRANS_SERVER_KEY=your-server-key-here
MIDTRANS_CLIENT_KEY=your-client-key-here
MIDTRANS_IS_PRODUCTION=false  # true untuk production
MIDTRANS_IS_SANITIZED=true
MIDTRANS_IS_3DS=true
```

**Mendapatkan API Keys:**
1. Daftar/Login ke [Midtrans Dashboard](https://dashboard.midtrans.com/)
2. Pilih environment (Sandbox untuk testing, Production untuk live)
3. Settings > Access Keys
4. Copy Server Key dan Client Key

### 2. Webhook Configuration
Konfigurasi webhook di Midtrans Dashboard:

**Settings > Configuration > Payment Notification URL:**
```
https://yourdomain.com/payment/notification
```

**Callback URLs (automatically configured):**
- Finish: `https://yourdomain.com/payment/finish/{order_number}`
- Unfinish: `https://yourdomain.com/payment/unfinish/{order_number}`
- Failed: `https://yourdomain.com/payment/failed/{order_number}`

**Pengaturan CSRF:**
Route `/payment/notification` harus di-exclude dari CSRF verification karena Midtrans tidak mengirim CSRF token.

Edit `bootstrap/app.php`:
```php
->withMiddleware(function (Middleware $middleware): void {
    $middleware->validateCsrfTokens(except: [
        'payment/notification',
    ]);
})
```

## Cara Kerja

### 1. Customer Flow
1. Customer mengisi form checkout (alamat, shipping, payment method)
2. Pilih "Pembayaran Online" (Midtrans)
3. Klik "Buat Pesanan"
4. Popup Midtrans Snap muncul dengan pilihan metode pembayaran
5. Customer memilih & menyelesaikan pembayaran
6. Redirect ke halaman Thank You

### 2. Backend Flow
```php
CheckoutController::store()
  ↓
CheckoutRepository::placeOrder()
  ↓ (creates Order, OrderItems, Payment)
MidtransService::createSnapToken()
  ↓ (returns snap_token)
Return JSON response dengan snap_token
  ↓
Frontend menampilkan Midtrans Snap popup
```

### 3. Payment Notification Flow
```
Midtrans → POST /payment/notification
  ↓
PaymentController::notification()
  ↓
MidtransService::handleNotification()
  ↓
Update Payment & Order status
```

## Payment Status Mapping

| Midtrans Status | Order Status | Description |
|----------------|--------------|-------------|
| `pending` | `pending` | Menunggu pembayaran |
| `capture` (accept) | `processing` | Pembayaran berhasil (kartu kredit) |
| `settlement` | `processing` | Pembayaran berhasil & settlement |
| `deny` | `cancelled` | Pembayaran ditolak |
| `cancel` | `cancelled` | Pembayaran dibatalkan |
| `expire` | `cancelled` | Pembayaran kadaluarsa |

## Testing

### Sandbox Mode
Gunakan test credentials dari Midtrans:

**Test Credit Cards:**
- Visa: `4811 1111 1111 1114`
- Mastercard: `5211 1111 1111 1117`
- CVV: `123`
- Expiry: Any future date

**Test Bank Transfer:**
- Virtual Account akan dibuat otomatis
- Gunakan simulator di Midtrans Dashboard untuk approve payment

### Test Flow
1. Set `MIDTRANS_IS_PRODUCTION=false`
2. Gunakan sandbox credentials
3. Lakukan checkout dengan "Pembayaran Online"
4. Gunakan test card/bank transfer
5. Verifikasi webhook notification received di logs
6. Cek status order & payment di database

## Model Changes

### Payment Model
Fields tambahan untuk Midtrans:
- `provider` - Payment provider (midtrans/cod/manual_transfer)
- `midtrans_transaction_id` - Midtrans transaction ID
- `order_id_ref` - Order reference ID
- `transaction_status` - Status dari Midtrans
- `payment_type` - Tipe pembayaran (credit_card, bank_transfer, dll)
- `fraud_status` - Fraud check status
- `raw_response` - Full Midtrans response (JSON)
- `settlement_time` - Waktu settlement

### Order Model
Fields terkait pembayaran:
- `paid_at` - Timestamp pembayaran sukses
- `cancelled_at` - Timestamp pembatalan

## Frontend Integration

### Midtrans Snap JS
Script otomatis loaded di checkout page:
```html
<script src="https://app.sandbox.midtrans.com/snap/snap.js"></script>
```

### Payment Popup
```javascript
window.snap.pay(snapToken, {
    onSuccess: function(result) { },
    onPending: function(result) { },
    onError: function(result) { },
    onClose: function() { }
});
```

## Security Notes

1. **Server Key**: Jangan pernah expose Server Key di frontend
2. **Signature Validation**: MidtransService otomatis validasi signature dari webhook
3. **HTTPS Required**: Production harus menggunakan HTTPS
4. **Webhook IP Whitelist**: Consider whitelist Midtrans IPs di firewall

## Troubleshooting

### Snap Popup Tidak Muncul
- Cek console browser untuk error
- Pastikan Client Key benar
- Pastikan Snap JS loaded

### Webhook Tidak Terima
- Cek Midtrans Dashboard > Settings > Notification URL
- Cek logs: `storage/logs/laravel.log`
- Test dengan Postman menggunakan sample webhook dari Midtrans docs

### Payment Status Tidak Update
- Cek PaymentLog table untuk history
- Verifikasi signature dari Midtrans
- Pastikan Server Key benar

## Files Changed/Created

### New Files:
- `app/Services/MidtransService.php` - Core Midtrans integration
- `app/Http/Controllers/PaymentController.php` - Webhook handler

### Modified Files:
- `config/services.php` - Midtrans config
- `app/Repositories/Eloquent/CheckoutRepository.php` - Snap token generation
- `app/Http/Controllers/CheckoutController.php` - AJAX response
- `resources/views/checkout.blade.php` - Snap JS integration
- `routes/web.php` - Payment routes
- `.env.example` - Midtrans env variables

## Support

Dokumentasi Midtrans:
- [Snap Documentation](https://docs.midtrans.com/en/snap/overview)
- [Payment Notification](https://docs.midtrans.com/en/after-payment/http-notification)
- [Testing Payment](https://docs.midtrans.com/en/technical-reference/sandbox-test)
