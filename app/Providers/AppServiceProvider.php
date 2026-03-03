<?php

namespace App\Providers;

use App\Models\ProductImage;
use App\Observers\ProductImageObserver;
use Illuminate\Console\Command as ArtisanCommand;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Ensure Laravel app instance is always set on commands resolved lazily by Symfony.
        $this->app->resolving(ArtisanCommand::class, function (ArtisanCommand $command): void {
            $command->setLaravel($this->app);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register observers
        ProductImage::observe(ProductImageObserver::class);
    }
}
