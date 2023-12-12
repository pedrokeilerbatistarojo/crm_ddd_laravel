<?php

namespace Domain\Discounts\Providers;

use Domain\Discounts\Contracts\Repositories\DiscountsRepository as DiscountsRepositoryInterface;
use Domain\Discounts\Contracts\Services\DiscountsService as DiscountsServiceInterface;
use Domain\Discounts\Repositories\DiscountsRepository;
use Domain\Discounts\Services\DiscountsService;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class DiscountsDomainServiceProvider extends ServiceProvider implements DeferrableProvider
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
            DiscountsRepositoryInterface::class,

            // Services
            DiscountsServiceInterface::class,
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
        $this->app->bind(DiscountsRepositoryInterface::class, DiscountsRepository::class);

        // Services
        $this->app->bind(DiscountsServiceInterface::class, DiscountsService::class);
    }
}
