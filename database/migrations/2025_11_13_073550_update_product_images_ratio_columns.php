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
        Schema::table('product_images', function (Blueprint $table) {
            // Hapus kolom lama
            $table->dropColumn(['path_square', 'path_wide', 'path_tall']);

            // Tambah kolom baru dengan ratio spesifik
            $table->string('path_ratio_27_28')->nullable()->after('path')->comment('Ratio 27:28 - 540x560px');
            $table->string('path_ratio_108_53')->nullable()->after('path_ratio_27_28')->comment('Ratio 108:53 - 540x265px');
            $table->string('path_ratio_51_52')->nullable()->after('path_ratio_108_53')->comment('Ratio 51:52 - 255x260px');
            $table->string('path_ratio_99_119')->nullable()->after('path_ratio_51_52')->comment('Ratio 99:119 - 198x238px');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_images', function (Blueprint $table) {
            // Hapus kolom baru
            $table->dropColumn(['path_ratio_27_28', 'path_ratio_108_53', 'path_ratio_51_52', 'path_ratio_99_119']);

            // Kembalikan kolom lama
            $table->string('path_square')->nullable()->after('path');
            $table->string('path_wide')->nullable()->after('path_square');
            $table->string('path_tall')->nullable()->after('path_wide');
        });
    }
};
