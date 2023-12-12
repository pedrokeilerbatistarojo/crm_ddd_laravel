<?php

namespace Domain\Products\Providers;

use Domain\Products\Contracts\Repositories\ProductsRepository as ProductsRepositoryInterface;
use Domain\Products\Contracts\Repositories\ProductDiscountsRepository as ProductDiscountsRepositoryInterface;
use Domain\Products\Contracts\Repositories\ProductTypesRepository as ProductTypesRepositoryInterface;
use Domain\Products\Contracts\Repositories\CategoriesRepository as CategoriesRepositoryInterface;
use Domain\Products\Contracts\Services\ProductsService as ProductsServiceInterface;
use Domain\Products\Repositories\CategoriesRepository;
use Domain\Products\Repositories\ProductsRepository;
use Domain\Products\Repositories\ProductDiscountsRepository;
use Domain\Products\Repositories\ProductTypesRepository;
use Domain\Products\Services\ProductsService;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class ProductsDomainServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(): array
    {
        return [
            // Repositories
            ProductsRepositoryInterface::class,
            ProductDiscountsRepositoryInterface::class,
            ProductTypesRepositoryInterface::class,
            CategoriesRepositoryInterface::class,

            // Services
            ProductsServiceInterface::class,
        ];
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        // Repositories
        $this->app->bind(ProductsRepositoryInterface::class, ProductsRepository::class);
        $this->app->bind(ProductDiscountsRepositoryInterface::class, ProductDiscountsRepository::class);
        $this->app->bind(ProductTypesRepositoryInterface::class, ProductTypesRepository::class);
        $this->app->bind(CategoriesRepositoryInterface::class, CategoriesRepository::class);

        // Services
        $this->app->bind(ProductsServiceInterface::class, ProductsService::class);
    }
}
