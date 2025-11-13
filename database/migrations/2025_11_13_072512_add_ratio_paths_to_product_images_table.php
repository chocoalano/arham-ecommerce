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
            $table->string('path_square')->nullable()->after('path');
            $table->string('path_wide')->nullable()->after('path_square');
            $table->string('path_tall')->nullable()->after('path_wide');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_images', function (Blueprint $table) {
            $table->dropColumn(['path_square', 'path_wide', 'path_tall']);
        });
    }
};
