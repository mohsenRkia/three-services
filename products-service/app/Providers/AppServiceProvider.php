<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use src\Domain\Product\RepositoryInterface;
use src\Infrastructure\Persistence\Product\ProductRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(RepositoryInterface::class, ProductRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
