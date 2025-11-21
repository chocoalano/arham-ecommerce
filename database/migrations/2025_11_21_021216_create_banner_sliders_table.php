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
        Schema::create('banner_sliders', function (Blueprint $table) {
            $table->id();

            // --- Konten Utama Slider ---
            $table->string('name')->comment('Judul/Headline besar untuk slider.');
            $table->text('description')->nullable()->comment('Paragraf deskripsi kecil.');
            $table->string('button_text')->default('Belanja Sekarang')->comment('Teks pada tombol aksi.');
            $table->string('link_url')->comment('URL yang dituju ketika tombol diklik (bisa ke produk, kategori, atau halaman lain).');

            // --- Gambar (Sesuai Kebutuhan Hero Slider) ---
            // Simpan path utama. Jika Anda memiliki beberapa rasio, Anda bisa tambahkan kolom tambahan.
            $table->string('image_path')->comment('Path file gambar utama banner.');
            // Tambahan opsional, jika Anda ingin menyimpan path gambar dengan rasio tertentu
            $table->string('image_path_108_53')->nullable()->comment('Path gambar untuk rasio 108:53 (untuk tampilan desktop).');

            // --- Pengaturan Tampilan & Urutan ---
            $table->unsignedSmallInteger('sort_order')->default(0)->index()->comment('Urutan tampilan banner di slider. Diurutkan dari yang terkecil.');
            $table->boolean('is_active')->default(true)->comment('Status apakah banner aktif dan ditampilkan.');

            // --- Diskon/Promosi (Opsional) ---
            $table->unsignedTinyInteger('discount_percent')->nullable()->comment('Persentase diskon yang ditampilkan (misal: 15).');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banner_sliders');
    }
};
