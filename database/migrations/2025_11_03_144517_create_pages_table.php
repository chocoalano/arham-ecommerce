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
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('content');
            $table->json('sections')->nullable(); // For flexible content sections
            $table->json('meta')->nullable(); // For SEO meta tags
            $table->string('template')->default('default'); // Template type
            $table->boolean('is_active')->default(true);
            $table->boolean('show_in_footer')->default(false);
            $table->integer('footer_order')->nullable();
            $table->timestamps();

            $table->index('slug');
            $table->index(['is_active', 'show_in_footer']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
