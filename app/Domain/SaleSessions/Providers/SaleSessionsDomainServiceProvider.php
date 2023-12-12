<?php

namespace Domain\SaleSessions\Providers;

use Domain\SaleSessions\Contracts\Repositories\SaleSessionsRepository as SaleSessionsRepositoryInterface;
use Domain\SaleSessions\Contracts\Services\SaleSessionsService as SaleSessionsServiceInterface;
use Domain\SaleSessions\Repositories\SaleSessionsRepository;
use Domain\SaleSessions\Services\SaleSessionsService;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class SaleSessionsDomainServiceProvider extends ServiceProvider implements DeferrableProvider
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
            SaleSessionsRepositoryInterface::class,

            // Services
            SaleSessionsServiceInterface::class,
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
        $this->app->bind(SaleSessionsRepositoryInterface::class, SaleSessionsRepository::class);

        // Services
        $this->app->bind(SaleSessionsServiceInterface::class, SaleSessionsService::class);
    }
}
