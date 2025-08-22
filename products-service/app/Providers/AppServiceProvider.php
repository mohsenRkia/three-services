<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use src\Domain\Product\RepositoryReadInterface;
use src\Domain\Product\RepositoryWriteInterface;
use src\Infrastructure\Persistence\Product\ProductWriteRepository;
use src\Infrastructure\Persistence\Product\ProductReadRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(RepositoryWriteInterface::class, ProductWriteRepository::class);
        $this->app->bind(RepositoryReadInterface::class, ProductReadRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
