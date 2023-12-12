<?php

namespace Domain\Companies\Providers;

use Domain\Companies\Contracts\Repositories\CompaniesRepository as CompaniesRepositoryInterface;
use Domain\Companies\Contracts\Services\CompaniesService as CompaniesServiceInterface;
use Domain\Companies\Repositories\CompaniesRepository;
use Domain\Companies\Services\CompaniesService;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class CompaniesDomainServiceProvider extends ServiceProvider implements DeferrableProvider
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
            CompaniesRepositoryInterface::class,

            // Services
            CompaniesServiceInterface::class,
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
        $this->app->bind(CompaniesRepositoryInterface::class, CompaniesRepository::class);

        // Services
        $this->app->bind(CompaniesServiceInterface::class, CompaniesService::class);
    }
}
