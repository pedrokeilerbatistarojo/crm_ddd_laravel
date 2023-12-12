<?php

namespace Domain\Festives\Providers;

use Domain\Festives\Contracts\Repositories\FestivesRepository as FestivesRepositoryInterface;
use Domain\Festives\Contracts\Services\FestivesService as FestivesServiceInterface;
use Domain\Festives\Repositories\FestivesRepository;
use Domain\Festives\Services\FestivesService;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class FestivesDomainServiceProvider extends ServiceProvider implements DeferrableProvider
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
            FestivesRepositoryInterface::class,

            // Services
            FestivesServiceInterface::class,
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
        $this->app->bind(FestivesRepositoryInterface::class, FestivesRepository::class);

        // Services
        $this->app->bind(FestivesServiceInterface::class, FestivesService::class);
    }
}
