<?php

namespace Domain\Localities\Providers;

use Domain\Localities\Contracts\Repositories\LocalitiesRepository as LocalitiesRepositoryInterface;
use Domain\Localities\Contracts\Repositories\ProvincesRepository as ProvincesRepositoryInterface;
use Domain\Localities\Contracts\Services\LocalitiesService as LocalitiesServiceInterface;
use Domain\Localities\Repositories\LocalitiesRepository;
use Domain\Localities\Repositories\ProvincesRepository;
use Domain\Localities\Services\LocalitiesService;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class LocalitiesDomainServiceProvider extends ServiceProvider implements DeferrableProvider
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
            LocalitiesRepositoryInterface::class,
            ProvincesRepositoryInterface::class,

            // Services
            LocalitiesServiceInterface::class,
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
        $this->app->bind(LocalitiesRepositoryInterface::class, LocalitiesRepository::class);
        $this->app->bind(ProvincesRepositoryInterface::class, ProvincesRepository::class);

        // Services
        $this->app->bind(LocalitiesServiceInterface::class, LocalitiesService::class);
    }
}
