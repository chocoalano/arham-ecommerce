<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // --- 📦 PRODUCT CATALOG ENHANCED ---

        // 1. Tambah tabel Brands
        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('logo_path')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('product_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('product_categories')->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('image_path')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->unique();
            $table->string('name');
            $table->string('slug')->unique();
            $table->foreignId('brand_id')->nullable()->constrained('brands')->nullOnDelete();
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->unsignedInteger('weight_gram')->default(0);
            $table->unsignedInteger('length_mm')->nullable();
            $table->unsignedInteger('width_mm')->nullable();
            $table->unsignedInteger('height_mm')->nullable();
            $table->unsignedInteger('stock')->default(0);
            $table->decimal('price', 16, 2)->default(0);
            $table->decimal('sale_price', 16, 2)->nullable();
            $table->boolean('is_featured')->default(false);
            $table->enum('status', ['draft', 'active', 'archived'])->default('active');
            $table->json('attributes')->nullable();
            $table->string('currency', 3)->default('IDR');
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['name']);
        });

        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('path');
            $table->string('alt_text')->nullable();
            $table->boolean('is_thumbnail')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('product_category_product', function (Blueprint $table) {
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('product_category_id')->constrained('product_categories')->cascadeOnDelete();
            $table->primary(['product_id', 'product_category_id']);
        });

        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('sku')->unique();
            $table->string('name');
            $table->json('options')->nullable();
            $table->unsignedInteger('weight_gram')->default(0);
            $table->decimal('price', 16, 2)->default(0);
            $table->decimal('sale_price', 16, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // --- 🏷️ MARKETING: VOUCHERS / COUPONS ---
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->enum('type', ['percent', 'fixed', 'free_shipping'])->default('percent');
            $table->decimal('value', 16, 2)->default(0);
            $table->decimal('max_discount', 16, 2)->nullable();
            $table->decimal('min_subtotal', 16, 2)->nullable();
            $table->unsignedInteger('usage_limit')->nullable();
            $table->unsignedInteger('used_count')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamp('valid_from')->nullable();
            $table->timestamp('valid_until')->nullable();
            $table->json('applicable')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // --- 👤 CUSTOMERS (Pelanggan) ---
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        // Asumsi: Tabel 'users' untuk admin/penulis artikel sudah ada.

        // --- 🏠 CUSTOMER & ADDRESS (RajaOngkir-compatible IDs) ---
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            // PERBAIKAN: Menggunakan 'customer_id'
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->string('label')->nullable();
            $table->string('recipient_name');
            $table->string('phone');
            $table->string('address_line1');
            $table->string('address_line2')->nullable();
            $table->string('postal_code', 10)->nullable();
            $table->unsignedInteger('rajaongkir_province_id')->nullable();
            $table->string('province_name')->nullable();
            $table->unsignedInteger('rajaongkir_city_id')->nullable();
            $table->string('city_name')->nullable();
            $table->unsignedInteger('rajaongkir_subdistrict_id')->nullable();
            $table->string('subdistrict_name')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->boolean('is_default_shipping')->default(false);
            $table->boolean('is_default_billing')->default(false);
            $table->timestamps();
            $table->softDeletes();
            // PERBAIKAN: Menggunakan 'customer_id'
            $table->index(['customer_id', 'rajaongkir_city_id', 'rajaongkir_subdistrict_id'], 'addr_geo_idx');
        });

        // --- 🛒 CARTS ---
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            // PERBAIKAN: Menggunakan 'customer_id'
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->string('session_id')->nullable();
            $table->string('currency', 3)->default('IDR');
            $table->foreignId('address_id')->nullable()->constrained('addresses')->nullOnDelete();
            $table->foreignId('voucher_id')->nullable()->constrained('vouchers')->nullOnDelete();
            $table->timestamp('expires_at')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
            // PERBAIKAN: Menggunakan 'customer_id'
            $table->index(['customer_id', 'session_id']);
        });

        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_id')->constrained('carts')->cascadeOnDelete();
            // Catatan: Karena tidak ada inventaris, kolom 'purchasable' di sini hanya menunjuk ke produk/varian
            // Tanpa validasi stok saat checkout.
            $table->morphs('purchasable');
            $table->string('sku');
            $table->string('name');
            $table->unsignedInteger('weight_gram')->default(0);
            $table->unsignedInteger('quantity')->default(1);
            $table->decimal('price', 16, 2)->default(0);
            $table->decimal('subtotal', 16, 2)->default(0);
            $table->json('meta')->nullable();
            $table->timestamps();
        });

        Schema::create('shipping_quotes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_id')->nullable()->constrained('carts')->cascadeOnDelete();
            $table->foreignId('address_id')->nullable()->constrained('addresses')->nullOnDelete();
            $table->string('courier', 20);
            $table->string('service', 50);
            $table->decimal('cost', 16, 2);
            $table->string('etd')->nullable();
            $table->json('rajaongkir_response')->nullable();
            $table->timestamps();
            $table->index(['cart_id', 'courier']);
        });

        // --- 🧾 ORDERS ---
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            // PERBAIKAN: Menggunakan 'customer_id'
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->foreignId('voucher_id')->nullable()->constrained('vouchers')->nullOnDelete();

            $table->string('customer_name');
            $table->string('customer_email')->nullable();
            $table->string('customer_phone')->nullable();

            $table->foreignId('billing_address_id')->nullable()->constrained('addresses')->nullOnDelete();
            $table->longText('billing_address_snapshot');
            $table->foreignId('shipping_address_id')->nullable()->constrained('addresses')->nullOnDelete();
            $table->longText('shipping_address_snapshot');

            $table->string('currency', 3)->default('IDR');
            $table->decimal('subtotal', 16, 2)->default(0);
            $table->decimal('discount_total', 16, 2)->default(0);
            $table->decimal('tax_total', 16, 2)->default(0);
            $table->decimal('shipping_total', 16, 2)->default(0);
            $table->decimal('grand_total', 16, 2)->default(0);

            $table->string('shipping_courier', 20)->nullable();
            $table->string('shipping_service', 50)->nullable();
            $table->decimal('shipping_cost', 16, 2)->nullable();
            $table->string('shipping_etd')->nullable();
            $table->unsignedInteger('weight_total_gram')->default(0);

            $table->enum('status', [
                'pending',
                'awaiting_payment',
                'paid',
                'processing',
                'shipped',
                'completed',
                'cancelled',
                'refunded',
                'expired',
            ])->default('pending');
            $table->timestamp('placed_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->string('source')->default('web');
            $table->text('notes')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->morphs('purchasable');
            $table->string('sku');
            $table->string('name');
            $table->unsignedInteger('weight_gram')->default(0);
            $table->unsignedInteger('quantity')->default(1);
            $table->decimal('price', 16, 2)->default(0);
            $table->decimal('subtotal', 16, 2)->default(0);
            $table->json('meta')->nullable();
            $table->timestamps();
        });

        // --- 💳 PAYMENTS (Midtrans) ---
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            // PERBAIKAN: Menggunakan 'customer_id'
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->string('provider')->default('midtrans');
            $table->string('midtrans_transaction_id')->nullable()->index();
            $table->string('order_id_ref')->nullable()->index();
            $table->enum('transaction_status', [
                'authorize', 'capture', 'settlement', 'pending', 'deny', 'cancel', 'expire', 'failure', 'refund', 'partial_refund', 'chargeback', 'partial_chargeback',
            ])->nullable()->index();
            $table->string('payment_type')->nullable();
            $table->string('fraud_status')->nullable();
            $table->decimal('gross_amount', 16, 2)->nullable();
            $table->string('currency', 3)->default('IDR');
            $table->timestamp('transaction_time')->nullable();
            $table->timestamp('settlement_time')->nullable();
            $table->timestamp('expiry_time')->nullable();
            $table->json('va_numbers')->nullable();
            $table->string('permata_va_number')->nullable();
            $table->string('bill_key')->nullable();
            $table->string('biller_code')->nullable();
            $table->string('masked_card')->nullable();
            $table->string('bank')->nullable();
            $table->string('store')->nullable();
            $table->string('signature_key')->nullable();
            $table->json('actions')->nullable();
            $table->json('raw_response')->nullable();
            $table->decimal('refund_amount', 16, 2)->nullable();
            $table->timestamp('refunded_at')->nullable();
            $table->timestamps();
        });

        Schema::create('payment_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')->nullable()->constrained('payments')->cascadeOnDelete();
            $table->foreignId('order_id')->nullable()->constrained('orders')->cascadeOnDelete();
            $table->string('type')->default('notification');
            $table->json('headers')->nullable();
            $table->json('payload')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamp('occurred_at')->useCurrent();
            $table->timestamps();
        });

        // --- 🚚 SHIPMENTS ---
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->string('courier', 20);
            $table->string('service', 50)->nullable();
            $table->string('waybill')->nullable()->index();
            $table->decimal('cost', 16, 2)->nullable();
            $table->string('etd')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->string('receiver_name')->nullable();
            $table->enum('status', ['pending', 'packed', 'shipped', 'in_transit', 'delivered', 'returned', 'cancelled'])->default('pending');
            $table->json('raw_response')->nullable();
            $table->unsignedInteger('origin_id')->nullable();
            $table->unsignedInteger('destination_id')->nullable();
            $table->timestamps();
        });

        // --- ⭐ PRODUCT REVIEWS & RATINGS ---
        Schema::create('product_reviews', function (Blueprint $table) {
            $table->id();
            // PERBAIKAN: Menggunakan 'customer_id'
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->morphs('reviewable');
            $table->unsignedTinyInteger('rating');
            $table->text('title')->nullable();
            $table->longText('content');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->foreignId('parent_id')->nullable()->constrained('product_reviews')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['rating', 'status']);
        });

        Schema::create('review_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_review_id')->constrained('product_reviews')->cascadeOnDelete();
            $table->string('path');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        // --- ❤️ CUSTOMER WISHLISTS ---
        Schema::create('wishlists', function (Blueprint $table) {
            $table->id();
            // PERBAIKAN: Menggunakan 'customer_id'
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->string('name')->default('Default Wishlist');
            $table->timestamps();
            // PERBAIKAN: Menggunakan 'customer_id'
            $table->unique('customer_id');
        });

        Schema::create('wishlist_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wishlist_id')->constrained('wishlists')->cascadeOnDelete();
            $table->morphs('purchasable');
            $table->text('notes')->nullable();
            $table->decimal('price_at_addition', 16, 2)->nullable();
            $table->timestamps();

            $table->unique(['wishlist_id', 'purchasable_type', 'purchasable_id'], 'wishlist_item_unique');
        });

        // --- 📝 CONTENT: ARTICLES / BLOG ---
        Schema::create('article_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('article_categories')->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            // Biarkan 'author_id' ke tabel 'users' (asumsi untuk Admin/Penulis)
            $table->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('excerpt', 500)->nullable();
            $table->longText('content');
            $table->enum('status', ['draft', 'published', 'scheduled'])->default('draft');
            $table->unsignedInteger('reading_time')->nullable();
            $table->string('cover_image')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamp('scheduled_at')->nullable();
            $table->boolean('is_pinned')->default(false);
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['status', 'published_at']);
        });

        Schema::create('article_article_category', function (Blueprint $table) {
            $table->foreignId('article_id')->constrained('articles')->cascadeOnDelete();
            $table->foreignId('article_category_id')->constrained('article_categories')->cascadeOnDelete();
            $table->primary(['article_id', 'article_category_id']);
        });

        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        Schema::create('article_tag', function (Blueprint $table) {
            $table->foreignId('article_id')->constrained('articles')->cascadeOnDelete();
            $table->foreignId('tag_id')->constrained('tags')->cascadeOnDelete();
            $table->primary(['article_id', 'tag_id']);
        });

        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->constrained('articles')->cascadeOnDelete();
            // PERBAIKAN: Menggunakan 'customer_id'
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('comments')->nullOnDelete();
            $table->string('guest_name')->nullable();
            $table->string('guest_email')->nullable();
            $table->longText('content');
            $table->enum('status', ['pending', 'approved', 'spam'])->default('pending');
            $table->timestamps();
            $table->softDeletes();
            $table->index(['status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop Content
        Schema::dropIfExists('comments');
        Schema::dropIfExists('article_tag');
        Schema::dropIfExists('tags');
        Schema::dropIfExists('article_article_category');
        Schema::dropIfExists('articles');
        Schema::dropIfExists('article_categories');

        // Drop Wishlists
        Schema::dropIfExists('wishlist_items');
        Schema::dropIfExists('wishlists');

        // Drop Reviews
        Schema::dropIfExists('review_images');
        Schema::dropIfExists('product_reviews');

        // Drop Transactions
        Schema::dropIfExists('shipments');
        Schema::dropIfExists('payment_logs');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');

        // Drop Cart & Address
        Schema::dropIfExists('shipping_quotes');
        Schema::dropIfExists('cart_items');
        Schema::dropIfExists('carts');
        Schema::dropIfExists('addresses');
        Schema::dropIfExists('customers'); // Harus di-drop setelah yang lain

        // Drop Catalog
        Schema::dropIfExists('product_variants');
        Schema::dropIfExists('product_images');
        Schema::dropIfExists('product_category_product');
        Schema::dropIfExists('products');
        Schema::dropIfExists('brands');
        Schema::dropIfExists('product_categories');
        Schema::dropIfExists('vouchers');

        // Catatan: 'users' (untuk admin/author) tidak di-drop di sini karena diasumsikan sudah ada di migrasi terpisah.
    }
};
